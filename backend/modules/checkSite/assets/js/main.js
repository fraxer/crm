function toggleSection(e) {
    e.target.closest('.dropdown').classList.toggle('dropdown_invert')

    e.target.closest('.section').classList.toggle('section_open')
}

function toggleDomain(e) {
    e.target.closest('.dropdown').classList.toggle('dropdown_invert')

    e.target.closest('.item').querySelector('.statuses').classList.toggle('statuses_open')
}