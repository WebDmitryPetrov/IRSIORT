<?php

/**
 * Created by JetBrains PhpStorm.
 * User: m.kulebyakin
 * Date: 18.09.13
 * Time: 10:38
 * To change this template use File | Settings | File Templates.
 */
class Roles
{
    //ÓÄÀËÈÒÜ
    const ROLE_ADM_BSO = 'adm_bso';
    const ROLE_CENTER = 'center';
    const ROLE_BUH = 'buh';
    const ROLE_CENTER_EXTERNAL = 'center_external';
    const ROLE_CENTER_EDITOR = 'center_editor';
    const ROLE_TEST_LEVEL_EDITOR = 'test_level_editor';
    const ROLE_CENTER_FOR_CHECK = 'for_check';
    const ROLE_CENTER_RECEIVED = 'received';
    const ROLE_CENTER_FOR_PRINT = 'for_print';
    const ROLE_CENTER_PRINT = 'print';
    const ROLE_CENTER_WAIT_PAYMENT = 'wait_payment';
    const ROLE_CENTER_ARCHIVE = 'archive';
    const ROLE_REPORT = 'report';
    const ROLE_SEARCH = 'search';
    const ROLE_ADMIN = 'admin';
    const ROLE_UNBLOCK = 'unblock';
    const ROLE_SEE_ALL = 'see_all';
    const ROLE_ACT_INVALID = 'act_invalid';
    const ROLE_SUPERVISOR = 'supervisor';
    const ROLE_CENTER_API = 'center_api';
    const ROLE_CENTER_EXTERNAL_API = 'center_external_api';
    const ROLE_CERTIFICATE_MANAGER = 'certificate_manager';
    const ROLE_SIGNER_MANAGER = 'signer_manager';
    const ROLE_EXCEL = 'excel';
    const ROLE_CONTR_BUH = 'contr_buh';


    private static $instance;
    private $current_role = array();
    private $universityRestrictionArray;

    private function __construct()
    {
        $this->setCurrentRole();

    }

    static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getRoleAccess($action)
    {
        $roles = $this->getActions();

        //     $actions = $roles[$this->getCurrentRole()];
        $actions = array(
            'access_restricted',
            'index',
            'download',
            'act_man_file_download',
            'archive_man_file_download',
            'archive_man_passport_download',
            'man_soprovod_upload',
            'help',
            'act_set_blocked',
            'act_set_unblocked'
        );
        foreach ($this->current_role as $role) {
            if (empty($roles[$role])) {
                continue;
            }
            $actions = array_merge($actions, $roles[$role]);
        }

        return in_array($action, $actions);
    }

    protected function getActions()
    {
        return array(
            self::ROLE_SEE_ALL => array(),
            /* self::ROLE_TEST_LEVEL_EDITOR => array(
                 'test_levels',
                 'test_levels_add',
                 'test_levels_delete',
                 'test_levels_edit',
             ),*/
            self::ROLE_CENTER_EDITOR => array(
                'universities',
                'university_children',
                'university_child_delete',
                'university_child_add',
                'university_child_edit',
                'university_child_view',
                'university_add',
                'university_edit',
                'university_view',
                'university_delete',
                'center_signing_add',
                'center_signing_delete',
                'center_signing_edit',
                'university_dogovor_add',
                'university_dogovor_edit',
                'university_dogovor_delete',
                'university_view_dogovor',
                'university_user_right',
                'user_list',
                'user_list_edit',
                'university_print_simple',
                'university_print_full',
                'university_print_full_dogovor',
                'act_upload_dogovor_scan',
                'head_center',
                'head_center_view',
                'head_center_add',
                'head_center_edit',
                'head_center_delete',
                'current_head_center_edit_text',
                'current_head_center_text_view',
                'current_head_center_view',
                'current_head_center_edit',
                'user_rights_edit',
                'user_delete',
                'user_create',
                'user_edit',
                /*                'signing_list',
                                'signing_delete',
                                'signing_edit',
                                'signing_create',*/
                'adm_user_list',
                'user_type_list',
                'user_type_add',
                'user_type_edit',
                'user_type_delete',
                'user_type_rights_edit',
                'user_edit_form',
                'user_add_form',
                'user_edit_form',
                'user_delete_form',
                'add_user_list',
                'edit_user_list',
                'change_price_univers',
                'test_level_price_edit',
                'save_test_level_price',
                'certificate_add',
                'certificate_approve',
                'certificate_submit',
                'certificate_type_list',
                'used_certificates_list',
                'certificate_list',
                'certificate_delete',
                'generate_numbers',
                'man_issue_duplicate',
                'man_duplicate',
                'act_vidacha_reestr_duplicate',
                'report_not_insert_numbers',
                'report_not_insert_numbers_rki',
                'archive_man',


            ),
            self::ROLE_BUH => array(

                'buh_check_univer',
                'buh_list',
                'buh_dubl_month',
                'buh_dubl_list',
                'report_check_univer',
                'report_list',
                'report_dubl_month',
                'report_dubl_list',
                'report_view_act',
                'archive_man',

            ),
            self::ROLE_CENTER_API => array(
                'xml_upload',
                'dics',
                'xml_countries',
                'xml_contracts',
                'xml_testlevels',
                'api_act_to_head',
                'act_api_finished',
                'xml_signer',
            ),

            self::ROLE_CENTER_EXTERNAL => array(
                'act_add',
                'act_choose',
                'act_fs_edit',
                'act_fs_view',
                'act_fs_list',
                'act_invalid',
                'act_test_add',
                'act_test_edit',
                'act_vedomost',
                'act_upload_scan',
                'act_upload_tabl_scan',
                'man_passport_upload',
                'man_soprovod_upload',
                'download',
                'help',
                'act_table',
                'act_test_delete',
                'act_send',
//                'act_third_list',
//                'act_third_view',
                'act_table_view',
                'act_finished',
                'act_print',
                'act_print_migrant',
                'act_table_print',
                'act_return_to_work',
                'act_univer_on_check',
                'act_passport',
                'act_man_files',
                'checked_list',
                'rework_list',
                'check_old_cert',
                'check_old_note',
                'paste_old_cert',
                'paste_old_note',
                'paste_old_cert_manually',
                'paste_old_note_manually',
                'act_old_cert_scan_upload',
                'clean_man',
                'clean_man_note',
                'message',
                'message_view',
                'loc_archive',
                'dubl',
                'dubl_create',
                'dubl_edit',
                'dubl_send',
                'dubl_delete',
                'dubl_show',
                'dubl_man_search',
                'dubl_man_add',
                'dubl_man_edit',
                'dubl_man_delete',
                'dubl_upload',
                'dubl_edit',
                'summary_table_print',
                'act_summary_table',
                'act_table_print',
                'loc_notes',


                'act_print_migrant',
                'act_print_migrant_view',
                'act_print',
                'act_print_view',
                'act_table_print',
                'act_table_print_view',
                'summary_table_print',
                'act_summary_table',
                'act_table_popup_ajax',
                'acts_print_list',
            ),
            self::ROLE_CENTER_FOR_CHECK => array(
                'on_check',
                'oncheck_additional_exam',
                'oncheck_approve_additional_exam',
                'act_second_view',
                'act_second_test_add',
                'act_table_second',
                'act_second_edit',
                'act_checked',
                'act_universities_second',
                'act_return_work',
                'set_archive',
                'checked_list',

                'rework_list',
                'man_passport_upload',

            ),
            self::ROLE_CENTER_RECEIVED => array(
                'act_universities_received',
                'act_universities',
                'act_received_list',
                'act_list',
                'act_received_view',
                'act_received_table_view',
                //   'act_receive_numbers',
                'print_invoice',
                'act_summary_table',
                'checked_list',
                'rework_list',
                'set_archive',
                'act_table_print',

                'act_print_migrant',
                'act_print_migrant_view',
                'act_print',
                'act_print_view',
                'act_table_print',
                'act_table_print_view',
                'summary_table_print',
                'act_summary_table',
                'act_table_popup_ajax',
                'acts_print_list',


            ),
            self::ROLE_CENTER_FOR_PRINT => array(
                'act_universities_checks',
                'act_universities',
                'act_checks_list',
                'act_list',
                'act_received_view',
                'act_received_table_view',
                'act_receive_numbers',
                'print_invoice',
                'scan_blank_upload',
                'checked_list',
                'rework_list',
                'set_archive',

                'act_print_migrant',
                'act_print_migrant_view',
                'act_print',
                'act_print_view',
                'act_table_print',
                'act_table_print_view',
                'summary_table_print',
                'act_summary_table',
                'act_table_popup_ajax',
                'acts_print_list',

            ),
            self::ROLE_CENTER_PRINT => array(
                'act_universities_print',
                'act_universities',
                'act_print_list',
                'act_list',
                'act_received_view',
                'act_received_table_view',
                'act_receive_numbers',
                'act_man_print_pril_cert',
                'act_man_print_pril_certs',
                'print_certificate',
                'generate_numbers',
                'print_certificates',
                'act_grazhdan',
                'act_rki',
                'act_rkis',
                'act_vidacha_cert',
                'act_vidacha_cert_rudn',
                'act_vidacha_cert_duplicate',
                'act_vidacha_note',
                'act_vidacha_reestr',
                'act_vidacha_reestr_duplicate',
                'print_invoice',
                'scan_blank_upload',
                'checked_list',
                'rework_list',
                'scan_blank_upload',
                'set_archive',
                'act_insert_blanks',

                'act_print_migrant',
                'act_print_migrant_view',
                'act_print',
                'act_print_view',
                'act_table_print',
                'act_table_print_view',
                'summary_table_print',
                'act_summary_table',
                'act_table_popup_ajax',
                'acts_print_list',
            ),
            self::ROLE_CENTER_WAIT_PAYMENT => array(
                'act_universities_wait',
                'act_universities',
                'act_wait_payment_list',
                'act_list',
                'act_received_view',
                'act_received_table_view',
                'act_receive_numbers',
                'print_invoice',
                'act_set_payed',
                'checked_list',
                'rework_list',
                'set_archive',

                'act_print_migrant',
                'act_print_migrant_view',
                'act_print',
                'act_print_view',
                'act_table_print',
                'act_table_print_view',
                'summary_table_print',
                'act_summary_table',
                'act_table_popup_ajax',
                'acts_print_list',
            ),
            self::ROLE_CENTER_ARCHIVE => array(
                'act_universities_archive',
                'act_archive_list',
                'act_archive_view',
                'act_archive_table_view',
                'act_archive_numbers',
                'act_received_view',
                'act_received_table_view',
                'print_certificate',
                'print_certificates',
                'act_grazhdan',
                'act_summary_table',
                'act_rki',
                'act_rkis',
                'act_vidacha_cert',
                'act_vidacha_cert_duplicate',
                'print_invoice',
                'act_man_print_pril_cert',
                'act_man_print_pril_certs',
                'set_archive',
                'act_vidacha_note',
                'act_vidacha_reestr',
                'act_set_payed',
                'dubl_lc_archive',
                'dubl_act_table_archive',
                'dubl_archive_list',
                'dubl_act_numbers_archive',
                'act_table_print',
                'archive_summary_table_print',

            ),
            self::ROLE_REPORT => array(
                'otch_country',
                'act_received_view',
                'act_received_table_view',
                'report_not_insert_numbers',
                'report_not_insert_numbers_rki',
                'report_check_univer',
                'report_list',
                'report_dubl_month',
                'report_dubl_list',
                'report_view_act',
            ),
            self::ROLE_SEARCH => array(
                'search_pupil',
                'search_act',
                'act_received_view',
                'act_received_table_view',
                'act_archive_numbers',
                'print_invoice',
                'act_archive_numbers',
                'act_vidacha_cert',
                'act_vidacha_cert_rudn',
                'act_vidacha_cert_duplicate',
                'act_vidacha_reestr',
                'act_rki',
                'act_rkis',
                'act_summary_table',
                'print_certificate',
                'print_certificates',
                'act_man_print_pril_cert',
                'act_man_print_pril_certs',
                'act_table_print',
                'archive_man',
            ),
            self::ROLE_ACT_INVALID => array(
                'sess_invalid',
                'sess_rework',
//                'man_issue_duplicate',
                'man_blank_invalid',
            ),

            self::ROLE_SUPERVISOR => array(

                'sess_rework',
                'man_issue_duplicate',
                'man_duplicate',
                'report_not_insert_numbers',
                'report_not_insert_numbers_rki',

                'dubl_act_universities_received',
                'dubl_act_list',
                'dubl_act_accept',
                'dubl_print_invoice',
                'dubl_act_print',
                'act_summary_table',
                'dubl_act_decline',
                'dubl_act_processed',
                'dubl_act_table',
                'dubl_act_numbers',
                'dubl_act_insert_blanks',
                'dubl_print_certificate',
                'dubl_print_certificates',
                'dubl_act_man_print_pril_cert',
                'dubl_act_man_print_pril_certs',
                'dubl_act_vidacha_reestr',
                'dubl_act_vidacha_cert',
                'dubl_man_acts',
                'dubl_summary_table_print',
                'dubl_act_summary_table',
                'act_table_print',

                'act_print_migrant',
                'act_print_migrant_view',
                'act_print',
                'act_print_view',
                'act_table_print',
                'act_table_print_view',
                'summary_table_print',
                'act_summary_table',
                'act_table_popup_ajax',
                'acts_print_list',

                'act_print_old_template',
                'archive_man',


            ),

            self::ROLE_CERTIFICATE_MANAGER => array(

                'certificate_add',
                'certificate_approve',
                'certificate_submit',
                'certificate_type_list',
                'used_certificates_list',
                'certificate_list',
                'certificate_delete',
                'generate_numbers',
                'act_insert_blanks',
                'act_universities',
//                'university_children',
                'act_list',
                'act_received_view',
                'act_received_table_view',
                'act_receive_numbers',

                'act_print_migrant',
                'act_print_migrant_view',
                'act_print',
                'act_print_view',
                'act_table_print',
                'act_table_print_view',
                'summary_table_print',
                'act_summary_table',
                'act_table_popup_ajax',
                'acts_print_list',
                'archive_man',
            ),

            self::ROLE_SIGNER_MANAGER => array(

                'signing_list',
                'signing_delete',
                'signing_edit',
                'signing_create',
                'frdo_excel_reports_list',
                'excel_report_download',
            ),

            self::ROLE_EXCEL => array(/*'signing_list',
                'signing_delete',
                'signing_edit',
                'signing_create',*/
            ),
            self::ROLE_CONTR_BUH=>array(
                'buh_view_act',
                'buh_view_table',
                'buh_dubl_view_act',
                'buh_search_act',
                'act_summary_table',
                'act_print_view',
                'act_table_print_view',
                'dubl_act_summary_table',
                'act_table_print',
                'buh_search_man',
            ),
        );
    }

    public function getUniversityRestrictionArray()
    {

//        if (self::userHasRole(self::ROLE_BUH)) {
//            return false;
//        }
//        if (self::userHasRole(self::ROLE_CENTER_EDITOR)) {
//            return false;
//        }
        if (!is_null($this->universityRestrictionArray)) {
            return $this->universityRestrictionArray;
        }
        // OR suu.user_id IS NULL
        $sqlUserRestrict = 'AND  (suu.user_id = ' . intval($_SESSION['u_id']) . ') ';
        if ($this->userHasRole(self::ROLE_SEE_ALL)) {
            $sqlUserRestrict = '';
//            return array();
        }

        if (!empty($_SESSION['univer_id'])) {
            return array($_SESSION['univer_id']);
        }
//die(var_dump(CURRENT_HEAD_CENTER));

        //sdt_university.head_id =    ' . CURRENT_HEAD_CENTER . '
        $sql = 'SELECT
  sdt_university.id as univer_id,
  suu.user_id AS user

FROM sdt_university
  LEFT  JOIN sdt_univer_user suu
    ON sdt_university.id = suu.univer_id

  WHERE sdt_university.head_id =    ' . CURRENT_HEAD_CENTER . ' ' . $sqlUserRestrict;
//        die($sql);
        $res = mysql_query($sql);
        $univers = array();
        while ($row = mysql_fetch_assoc($res)) {
            $univers[] = $row['univer_id'];
        }
        $univers[] = '-1';

        $this->universityRestrictionArray = array_unique($univers);
//var_dump($this->universityRestrictionArray); die;
        return $this->universityRestrictionArray;

    }

    public function userHasRole($role)
    {

        return in_array($role, $this->current_role);
    }

    public function getCurrentRole()
    {
        return $this->current_role;
    }

    protected function setCurrentRole()
    {

        if (!empty($_SESSION['privelegies']['level_list']) && $_SESSION['privelegies']['level_list']) {
            $this->current_role[] = self::ROLE_TEST_LEVEL_EDITOR;
        }


        if (!empty($_SESSION['privelegies']['admin']) && $_SESSION['privelegies']['admin']) {
            $this->current_role[] = self::ROLE_ADMIN;
        }
        if (!empty($_SESSION['privelegies']['center_list']) && $_SESSION['privelegies']['center_list']) {
            $this->current_role[] = self::ROLE_CENTER_EDITOR;
        }

        if (isset($_SESSION['privelegies']['buh']) && $_SESSION['privelegies']['buh']) {
            $this->current_role[] = self::ROLE_BUH;
        }


        if (!empty($_SESSION['privelegies']['center_external'])) {
            $this->current_role[] = self::ROLE_CENTER_EXTERNAL;

            $sql = 'select api_enabled, pfur_api from sdt_university where id = ' . intval($_SESSION['univer_id']);

            $res = mysql_query($sql);
            $row = mysql_fetch_assoc($res);
            if($row['api_enabled']){
                $this->current_role[] = self::ROLE_CENTER_EXTERNAL_API;
                $this->current_role[] = self::ROLE_CENTER_API;
            }

        }

        if (!empty($_SESSION['privelegies']['see_all'])) {
            $this->current_role[] = self::ROLE_SEE_ALL;
        }


        if (!empty($_SESSION['privelegies']['for_check'])) {
            $this->current_role[] = self::ROLE_CENTER_FOR_CHECK;
        }

        if (!empty($_SESSION['privelegies']['received'])) {
            $this->current_role[] = self::ROLE_CENTER_RECEIVED;
        }

        if (!empty($_SESSION['privelegies']['for_print'])) {
            $this->current_role[] = self::ROLE_CENTER_FOR_PRINT;
        }

        if (!empty($_SESSION['privelegies']['print'])) {
            $this->current_role[] = self::ROLE_CENTER_PRINT;
        }

        if (!empty($_SESSION['privelegies']['wait_payment'])) {
            $this->current_role[] = self::ROLE_CENTER_WAIT_PAYMENT;
        }


        if (!empty($_SESSION['privelegies']['archive'])) {
            $this->current_role[] = self::ROLE_CENTER_ARCHIVE;
        }
        if (!empty($_SESSION['privelegies']['report'])) {
            $this->current_role[] = self::ROLE_REPORT;
        }
        if (!empty($_SESSION['privelegies']['search'])) {
            $this->current_role[] = self::ROLE_SEARCH;
        }
        if (!empty($_SESSION['privelegies']['unblock'])) {
            $this->current_role[] = self::ROLE_UNBLOCK;
        }

        if (!empty($_SESSION['privelegies']['supervisor'])) {
            $this->current_role[] = self::ROLE_SUPERVISOR;
        }

        if (!empty($_SESSION['privelegies']['act_invalid'])) {
            $this->current_role[] = self::ROLE_ACT_INVALID;
        }

        if (!empty($_SESSION['privelegies']['certificate_manager'])) {
            $this->current_role[] = self::ROLE_CERTIFICATE_MANAGER;
        }

        if (!empty($_SESSION['privelegies']['signer_manager'])) {
            $this->current_role[] = self::ROLE_SIGNER_MANAGER;
        }

        if (!empty($_SESSION['privelegies']['excel'])) {
            $this->current_role[] = self::ROLE_EXCEL;
        }
        if (!empty($_SESSION['privelegies']['contr_buh'])) {
            $this->current_role[] = self::ROLE_CONTR_BUH;
        }



//var_dump($this->current_role);
    }

    public function emptyRoles()
    {
        $this->current_role = array();
    }
}