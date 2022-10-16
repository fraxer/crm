class Slider {

  slider = null

  emiter = null

  canChooseAlbum = true

  timeout = null

  options = {
    loop: false,
    allowTouchMove: false,
    autoplay: {
      delay: 3000,
      stopOnLastSlide: true
    },
    pagination: {
      el: '.stories-modal__lines',
      renderBullet: (function (self) {
        return function (index, className) {
          return '<div class="' + className + '"><span style="animation-duration: ' + self.getSlideDuration(this, index) + 'ms;"></span></div>'
        }
      })(this)
    },
    resistanceRatio: 0,
    navigation: {
      nextEl: '.modalSlider .swiper-button-next',
      prevEl: '.modalSlider .swiper-button-prev'
    },
    on: {
      beforeTransitionStart: (function (self) {
        return function () {
          if (this.realIndex + 1 === this.slides.length && self.canChooseAlbum) {
            self.timeout = setTimeout(() => self.nextAlbum(), self.getSlideDuration(this, this.realIndex))
          }

          self.canChooseAlbum = true
        }
      })(this),
      navigationNext: (function (self) {
        return function () {
          if (this.realIndex + 1 === this.slides.length && !self.timeout) {
            self.timeout = setTimeout(() => self.nextAlbum(), self.getSlideDuration(this, this.realIndex))
          }
          this.autoplay.start()
        }
      })(this),
      navigationPrev: (function (self) {
        return function () {
          self.resetNextAlbum()
          this.autoplay.start()
        }
      })(this),
      sliderMove: (function (self) {
        return function () {
          if (this.realIndex > this.previousIndex) {
            self.resetNextAlbum()
          } else {
            self.canChooseAlbum = true
          }
        }
      })(this)
    }
  }

  getSlideDuration (slider, index) {
    const commonDelay = slider.params.autoplay.delay
    const sliderDelay = slider.slides[index].dataset.swiperAutoplay
    return Number(sliderDelay ? sliderDelay : commonDelay)
  }

  create (selector) {
    this.slider = new Swiper(selector, this.options)
    this.canChooseAlbum = true
  }

  destroy() {
    if (!this.slider) return

    this.resetNextAlbum()

    const deleteInstance = true
    const cleanStyles = true
    this.slider.destroy(deleteInstance, cleanStyles)
  }

  setEmmiter (emiter) {
    this.emiter = emiter
  }

  nextAlbum () {
    this.emiter.nextAlbum()
    this.resetTimeout()
  }

  resetNextAlbum () {
    this.canChooseAlbum = false
    this.resetTimeout()
  }

  resetTimeout () {
    clearTimeout(this.timeout)
    this.timeout = null
  }
}

class SliderStruct {

  sliderContainer = null

  constructor (selector) {
    this.sliderContainer = document.querySelector(selector + ' .swiper-wrapper')

    if (!this.sliderContainer) new Error('Slider container not found')
  }

  async build (albumId) {
    const album = albumJson[albumId]

    const container = this.sliderContainer.cloneNode(true)

    this.clearContainer(container)

    if (!album) return new Error('Album not fount in the json struct')

    for (const photo of album.photos) {
      const div = document.createElement('div')

      div.classList.add('swiper-slide')

      if (photo.duration) {
        div.dataset.dataSwiperAutoplay = photo.duration * 1000
      }

      div.appendChild(await ImageLoader.loadImage(photo.image))

      container.appendChild(div)
    }

    return container
  }

  setContent (container) {
    this.sliderContainer.parentNode.replaceChild(container, this.sliderContainer)

    this.sliderContainer = container
  }

  clear () {
    this.clearContainer(this.sliderContainer)
  }

  clearContainer (container) {
    container.innerHTML = ''
  }
}

class Emiter {

  slider = null

  modal = null

  constructor (modal, slider) {
    this.slider = slider
    this.modal = modal
  }

  nextAlbum () {
    const keys = Object.keys(albumJson)
    let currentKeyFinded = false
    let albumId = -1

    for (let key of keys) {
      if (currentKeyFinded) {
        albumId = Number(key)
        break
      }

      if (Number(key) === this.modal.getAlbumId()) {
        currentKeyFinded = true
      }
    }

    if (albumId === -1) return

    this.modal.openAlbum(albumId)
  }
}

class ImageLoader {

  static async loadImage (path) {
    return new Promise(resolve => {
      let image = new Image()

      image.onload = function () {
        resolve(this)
      }

      image.src = path
    })
  }
}

class Modal {

  sliderInstance = null

  sliderStruct = null

  emiter = null

  albumId = 0

  async open (albumId) {
    this.setAlbumId(albumId)

    this.sliderStruct = new SliderStruct('.modalSlider')
    this.sliderStruct.clear()
    this.sliderStruct.setContent(await this.sliderStruct.build(albumId))

    this.setImage(albumId)
    this.setName(albumId)

    this.sliderInstance = new Slider()
    this.sliderInstance.create('.modalSlider')

    this.emiter = new Emiter(this, this.sliderInstance)

    this.sliderInstance.setEmmiter(this.emiter)

    document.querySelector('.stories-modal').classList.add('stories-modal_open')
  }

  close () {
    document.querySelector('.stories-modal').classList.remove('stories-modal_open')

    this.setAlbumId(0)
    this.sliderInstance.destroy()
    this.sliderStruct.clear()
  }

  setAlbumId (albumId) {
    this.albumId = albumId
  }

  getAlbumId () {
    return this.albumId
  }

  setImage (albumId) {
    const album = albumJson[albumId]

    if (!album) return new Error('Album not fount in the json struct')

    const image = document.querySelector('.stories-modal__album-image')

    if (!image) return new Error('Modal image tag not found')

    image.setAttribute('src', album.image)
  }

  setName (albumId) {
    const album = albumJson[albumId]

    if (!album) return new Error('Album not fount in the json struct')

    const span = document.querySelector('.stories-modal__album-name')

    if (!span) return new Error('Modal span tag not found')

    span.innerText = album.name
  }

  async openAlbum (albumId) {
    this.setAlbumId(albumId)
    this.sliderStruct.setContent(await this.sliderStruct.build(albumId))
    this.sliderInstance.destroy()

    this.setImage(albumId)
    this.setName(albumId)

    this.sliderInstance.create('.modalSlider')
  }
}

const modal = new Modal()
