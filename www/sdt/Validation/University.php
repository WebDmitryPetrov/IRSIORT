<?php

namespace SDT\Validation;

class University
{
    public function validateParent(\University $university)
    {
        $result = [];
        if (empty($university->inn)) {
            $result['inn'] = '������� ���';
        }
        if (empty($university->name)) {
            $result['name'] = '������� ��������';
        }
        if (empty($university->short_name)) {
            $result['short_name'] = '������� ����������� ��������';
        }
        if (empty($university->rector)) {
            $result['rector'] = '������� ������� (���������)';
        }
        if (empty($university->form)) {
            $result['form'] = '������� �������� �����';
        }
        if (empty($university->legal_address)) {
            $result['legal_address'] = '������� ����������� �����';
        }
        if (empty($university->contact_phone)) {
            $result['contact_phone'] = '������� �������';
        }
        if (empty($university->contact_email)) {
            $result['contact_email'] = '������� email';
        }
        if (empty($university->responsible_person)) {
            $result['responsible_person'] = '������� �������������� �� ���������� ������������';
        }
        if (empty($university->bank)) {
            $result['bank'] = '������� ����';
        }
        if (empty($university->city)) {
            $result['city'] = '������� �����';
        }
        if (empty($university->rc)) {
            $result['rc'] = '������� ��������� ����';
        }
        if (empty($university->lc)) {
            $result['lc'] = '������� ������� ����';
        }
        if (empty($university->kc)) {
            $result['kc'] = '������� ����������������� ����';
        }
        if (empty($university->bik)) {
            $result['bik'] = '������� ���';
        }
        if (empty($university->kpp)) {
            $result['kpp'] = '������� ���';
        }
        if (empty($university->okato)) {
            $result['okato'] = '������� ��� �� �����';
        }
        if (empty($university->okpo)) {
            $result['okpo'] = '������� ��� �� ����';
        }
        if (empty($university->country_id)) {
            $result['country_id'] = '�������� ������';
        }
        if ($university->country_id == 134 && empty($university->region_id)) {
            $result['region_id'] = '�������� ������';
        }



        return $result;
    }

    public function validateChild(\University $university)
    {
        $result = [];

        if (empty($university->name)) {
            $result['name'] = '������� ��������';
        }
        if (empty($university->short_name)) {
            $result['short_name'] = '������� ����������� ��������';
        }

        if (empty($university->legal_address)) {
            $result['legal_address'] = '�������  �����';
        }
        if (empty($university->contact_phone)) {
            $result['contact_phone'] = '������� �������';
        }
        if (empty($university->contact_email)) {
            $result['contact_email'] = '������� email';
        }
        if (empty($university->responsible_person)) {
            $result['responsible_person'] = '������� �������������� �� ���������� ������������';
        }


        if (empty($university->country_id)) {
            $result['country_id'] = '�������� ������';
        }
        if ($university->country_id == 134 && empty($university->region_id)) {
            $result['region_id'] = '�������� ������';
        }

        return $result;
    }
}