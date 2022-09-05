<?php

namespace backend\components;

use yii\console\Controller;

class UisDataAdapter {

    public static $defaultCDRFields = [
        'called_phone_number' => null,
        'calling_phone_number' => null,
        'employee_phone_number' => null,
        'employee_id' => null,
        'employee_full_name' => null,
    ];

    public static function parseCallsReport(Array $data)
    {
        $array = [];

        foreach ($data as $item) {
            $array[] = UisDataAdapter::parseCallReport($item);
        }

        return $array;
    }

    private static function parseCallReport($data)
    {
        $recordFileLinks = [];

        if (!empty($data['full_record_file_link'])) {
            $recordFileLinks[] = $data['full_record_file_link'];
        }

        return [
            'notification_name' => self::getDictionaryTranslate($data['finish_reason']),
            'virtual_phone_number' => $data['virtual_phone_number'],
            'notification_time' => $data['start_time'],
            'external_id' => null,
            'contact_info' => [
                'contact_phone_number' => $data['direction'] == 'in' ? $data['calling_phone_number'] : $data['called_phone_number'],
                'communication_number' => $data['communication_number'],
            ],
            'employees' => $data['employees'],
            'cdrs' => $data['cdrs'],
            'call_info' => [
                'direction' => $data['direction'],
                'is_lost' => $data['is_lost'],
                'call_source' => $data['source'],
                'call_session_id' => $data['id'],
                'scenario_name' => $data['scenario_name'],
                'talk_time_duration' => $data['talk_duration'],
                'total_time_duration' => $data['total_duration'],
                'wait_time_duration' => $data['wait_duration'],
            ],
            'record_file_links' => $recordFileLinks,
        ];
    }

    public static function mergeCallsWithCdrs(Array& $calls, Array& $cdrs)
    {
        foreach ($calls as &$call) {
            $employeeId = 0;

            if (!isset($call['cdrs'])) {
                $call['cdrs'] = [];
            }

            if (!is_array($call['employees'])) {
                $employee['employee_phone_number'] = null;

                $call['employees'] = [];

                foreach (self::$defaultCDRFields as $fieldName => $fieldValue) {
                    $call[$fieldName] = $fieldValue;
                }

                continue;
            }

            foreach ($call['employees'] as &$employee) {
                $employeeId = $employee['employee_id'];

                $cdr = &self::findCdr($cdrs, function ($data) use ($call, $employeeId) {
                    return $data['call_session_id'] == $call['id'] && $data['employee_id'] == $employeeId;
                });

                $employee['employee_phone_number'] = $cdr['employee_phone_number'];

                $call['called_phone_number'] = $cdr['called_phone_number'];
                $call['calling_phone_number'] = $cdr['calling_phone_number'];
                $call['cdrs'][] = [
                    'called_phone_number' => $cdr['called_phone_number'],
                    'calling_phone_number' => $cdr['calling_phone_number'],
                    'employee_phone_number' => $cdr['employee_phone_number'],
                    'employee_id' => $cdr['employee_id'],
                    'employee_full_name' => $cdr['employee_full_name'],
                    'finish_reason_description' => $cdr['finish_reason_description'],
                ];
            }
        }

        return $calls;
    }

    private static function &findCdr(Array& $array, $fn)
    {
        foreach ($array as &$item) {
            if ($fn($item)) return $item;
        }

        return self::$defaultCDRFields;
    }

    private static function getDictionaryTranslate($key)
    {
        $dictionary = [
            'numb_not_exists' => 'Виртуальный номер не найден',
            'incorrect_input' => 'Некорректный ввод',
            'numb_is_inactive' => 'Виртуальный номер не активен',
            'sitephone_is_not_configured' => 'Сайтфон не настроен',
            'app_is_inactive' => 'Клиент деактивирован',
            'numa_in_black_list' => 'Вызывающий абонент в чёрном списке',
            'no_active_scenario' => 'Не найден активный сценарий',
            'simple_forwarding_is_not_configured' => 'Аналитика: простая переадресация не настроена',
            'site_not_exists' => 'Сайт не найден',
            'call_generator_is_not_configured' => 'Лидогенератор не настроен',
            'add_cdr_timeout' => 'Не определена',
            'success_finish' => 'Не определена',
            'api_permission_denied' => 'Доступ к Call API запрещён',
            'api_ip_now_allowed' => 'IP-адрес не в списке разрешённых',
            'component_is_inactive' => 'Компонент не активен',
            'employee_not_exists' => 'Сотрудник не найден',
            'not_enough_money' => 'Недостаточно средств',
            'platform_not_found' => 'Обратитесь в службу технической поддержки',
            'internal_error' => 'Обратитесь в службу технической поддержки',
            'incorrect_config' => 'Недопустимая конфигурация',
            'communication_unavailable' => 'Недоступный тип связи',
            'subscriber_disconnects' => 'Абонент разорвал соединение',
            'no_operation' => 'Нет операции для обработки',
            'scenario_not_found' => 'Сценарий не найден',
            'transfer_disconnects' => 'Отключение сотрудника при трансфере',
            'scenario_disconnects' => 'Отключение сотрудника при запуске сценария',
            'fax_session_done' => 'Факс принят',
            'no_resources' => 'Лимит клиента исчерпан',
            'another_operator_answer' => 'Дозвонились до другого сотрудника',
            'subscriber_busy' => 'Абонент занят',
            'subscriber_not_responsible' => 'Абонент не отвечает',
            'subscriber_number_problems' => 'Проблемы с телефонным номером абонента. Обратитесь в службу технической поддержки.',
            'operator_answer' => 'Дозвонились до сотрудника',
            'locked_numb' => 'Звонки на этот номер запрещены настройками безопасности',
            'call_not_allowed_on_tp' => 'Звонок запрещен согласно тарифному плану',
            'account_not_found' => 'Не найден лицевой счёт',
            'contract_not_found' => 'Не найден договор',
            'operator_busy' => 'Сотрудник занят',
            'operator_not_responsible' => 'Сотрудник не отвечает',
            'operator_disconnects' => 'Сотрудник разорвал соединение',
            'operator_number_problems' => 'Проблемы с телефонным номером сотрудника. Обратитесь в службу технической поддержки.',
            'timeout' => 'Время дозвона истекло',
            'operator_channels_busy' => 'Закончились доступные линии на номере переадресации',
            'locked_phone' => 'Проблемы с сетью',
            'max_in_call_reached' => 'Достигнут лимит линий для входящих звонков',
            'max_out_call_reached' => 'Достигнут лимит линий для исходящих звонков',
            'employee_busy' => 'Сотрудник разговаривает в другом звонке',
            'employee_busy_after_call' => 'Сотрудник занят после звонка',
            'phone_group_inactive_by_schedule' => 'Группа номеров неактивна согласно расписанию',
            'sip_offline' => 'SIP-линия не зарегистрирована',
            'employee_inactive' => 'Сотрудник неактивен',
            'employee_inactive_by_schedule' => 'Сотрудник неактивен согласно расписанию',
            'employee_phone_inactive' => 'Номер сотрудника неактивен',
            'employee_phone_inactive_by_schedule' => 'Номер сотрудника неактивен согласно расписанию',
            'action_interval_exceeded' => 'Превышен интервал, указанный на операции',
            'group_phone_inactive' => 'Номер в группе недоступен',
            'no_operator_confirmation' => 'Сотрудник не подтвердил вызов',
            'max_transition_count_exceeded' => 'Достигнуто максимальное количество переходов по операциям сценария',
            'disconnect_before_call_processing' => 'Разъединение до обработки вызова',
            'no_success_subscriber_call' => 'Не дозвонились до абонента',
            'no_success_operator_call' => 'Не дозвонились до сотрудника',
            'group_inactive_by_schedule' => 'Группа сотрудников неактивна согласно расписанию',
            'net_lock' => 'Сеть оператора заблокирована',
            'fmc_is_disabled' => 'Услуга FMC отключена',
            'fmc_is_locked' => 'FMC-линия заблокирована',
            'processing_method_not_found' => 'Способ обработки не найден',
            'employee_without_phones' => '',
            'group_without_phones' => '',
            'no_operator_cdr_found' => 'Вызовы сотрудникам не найдены',
            'in_call_not_allowed' => 'Прием входящих звонков запрещен',
            'phone_protocol_not_allowed_by_status' => 'Звонки с данного типа номера запрещены статусом',
            'employee_status_break' => 'Сотрудник в статусе "Перерыв"',
            'employee_status_do_not_disturb' => 'Сотрудник в статусе "Не беспокоить"',
            'employee_status_not_at_workplace' => 'Сотрудник в статусе "Нет на месте"',
            'employee_status_not_at_work' => 'Сотрудник в статусе "Нет на работе"',
            'sip_trunk_is_locked' => 'SIP-транк заблокирован',
            'numa_in_spam_list' => 'Спам-звонок',
            'numa_dnd_interval' => 'Звонили недавно',
            'limit_exceeded' => 'Достигнут финансовый лимит',
            'no_money' => 'Недостаточно средств',
            'out_call_not_allowed' => 'Исходящие звонки запрещены',
            'out_call_not_allowed_by_status' => 'Исходящие звонки запрещены статусом',
            'external_call_not_allowed' => 'Внешние звонки запрещены',
            'external_call_not_allowed_by_status' => 'Внешние звонки запрещены статусом',
            'internal_call_not_allowed' => 'Внутренние звонки запрещены',
            'internal_call_not_allowed_by_status' => 'Внутренние звонки запрещены статусом',
            'call_enqueued' => 'Вызов поставлен в очередь',
            'employee_status_auto_out_call' => 'Сотрудник в статусе "Исходящий обзвон"',
            'employee_status_available' => 'Сотрудник в статусе "Доступен"',
            'too_many_identical_incoming_calls' => 'Слишком много одинаковых входящих звонков',
            'employee_auto_status_do_not_disturb' => 'Сотрудник в автоматическом статусе "Не беспокоить"',
            'auto_out_calls_not_allowed_by_status' => 'Исходящий обзвон запрещен статусом',
        ];

        return $dictionary[$key];
    }

    public function fetchEntityField($data, $fn)
    {
        $array = [];

        foreach ($data as $item) {
            $value = $fn($item);

            if (is_array($value)) {
                $array = array_merge($array, $value);
            } else {
                $array[] = $value;
            }
        }

        return $array;
    }
}