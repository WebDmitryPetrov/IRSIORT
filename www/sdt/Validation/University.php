<?php

namespace SDT\Validation;

class University
{
    public function validateParent(\University $university)
    {
        $result = [];
        if (empty($university->inn)) {
            $result['inn'] = 'Введите ИНН';
        }
        if (empty($university->name)) {
            $result['name'] = 'Введите название';
        }
        if (empty($university->short_name)) {
            $result['short_name'] = 'Введите сокращенное название';
        }
        if (empty($university->rector)) {
            $result['rector'] = 'Введите ректора (директора)';
        }
        if (empty($university->form)) {
            $result['form'] = 'Введите правовую форму';
        }
        if (empty($university->legal_address)) {
            $result['legal_address'] = 'Введите юридический адрес';
        }
        if (empty($university->contact_phone)) {
            $result['contact_phone'] = 'Введите телефон';
        }
        if (empty($university->contact_email)) {
            $result['contact_email'] = 'Введите email';
        }
        if (empty($university->responsible_person)) {
            $result['responsible_person'] = 'Введите ответственного за проведение тестирования';
        }
        if (empty($university->bank)) {
            $result['bank'] = 'Введите банк';
        }
        if (empty($university->city)) {
            $result['city'] = 'Введите город';
        }
        if (empty($university->rc)) {
            $result['rc'] = 'Введите расчетный счет';
        }
        if (empty($university->lc)) {
            $result['lc'] = 'Введите лицевой счет';
        }
        if (empty($university->kc)) {
            $result['kc'] = 'Введите корреспондентский счет';
        }
        if (empty($university->bik)) {
            $result['bik'] = 'Введите БИК';
        }
        if (empty($university->kpp)) {
            $result['kpp'] = 'Введите КПП';
        }
        if (empty($university->okato)) {
            $result['okato'] = 'Введите код по ОКАТО';
        }
        if (empty($university->okpo)) {
            $result['okpo'] = 'Введите код по ОКПО';
        }
        if (empty($university->country_id)) {
            $result['country_id'] = 'Выберите страну';
        }
        if ($university->country_id == 134 && empty($university->region_id)) {
            $result['region_id'] = 'Выберите регион';
        }



        return $result;
    }

    public function validateChild(\University $university)
    {
        $result = [];

        if (empty($university->name)) {
            $result['name'] = 'Введите название';
        }
        if (empty($university->short_name)) {
            $result['short_name'] = 'Введите сокращенное название';
        }

        if (empty($university->legal_address)) {
            $result['legal_address'] = 'Введите  адрес';
        }
        if (empty($university->contact_phone)) {
            $result['contact_phone'] = 'Введите телефон';
        }
        if (empty($university->contact_email)) {
            $result['contact_email'] = 'Введите email';
        }
        if (empty($university->responsible_person)) {
            $result['responsible_person'] = 'Введите ответственного за проведение тестирования';
        }


        if (empty($university->country_id)) {
            $result['country_id'] = 'Выберите страну';
        }
        if ($university->country_id == 134 && empty($university->region_id)) {
            $result['region_id'] = 'Выберите регион';
        }

        return $result;
    }
}