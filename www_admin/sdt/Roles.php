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
    const ADMIN_HEAD = 'mainest_admin';
    const ROLE_CENTER = 'center';
    const ROLE_BUH = 'buh';
    const ROLE_CENTER_EXTERNAL = 'center_external';
    const ROLE_CENTER_EDITOR = 'center_editor';
    const ROLE_ROOT = 'root';
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
    const ROLE_FMS_ADMIN = 'fms_admin';
    const ROLE_FMS_USER = 'fms_user';
    const ROLE_STATISTICS = 'statistics';

    const ROLE_ACT_INVALID = 'act_invalid';


    private static $instance;
    private $current_role = array();

    static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->setCurrentRole();

    }

    protected function getActions()
    {
        return array(
            self::ROLE_SEE_ALL=>array(),
//Самый главный админ
            self::ADMIN_HEAD => array_merge(
                Reports::getRoles(),
                array(
                    'user_type_list',
                    'user_type_add',
                    'user_type_edit',
                    'user_type_delete',
                    'user_type_rights_edit',
                    'groups_list',
                    'groups_add',
                    'groups_edit',
                    'groups_delete',
                    'apache_conf',
                    'change_price_univers',
                    'test_level_price_edit',
                    'save_test_level_price',
                    'user_add_form_fms',
                    'user_edit_form_fms',
                    'edit_user_list_fms',
                    'user_delete_form_fms',
                    'acts_to_archive_list',
                    'acts_to_archive_list_dubl',
                    'statistics_gc',
                    'statistics',
                    'minobr_report_about_exam_notes',
                    'minobr_pfur_report_about_exam_notes',
                    'minobr_pfur_report_about_exam_by_citizenship',
                    'report_lc_list',
                    'report_people_gc',
                    'minobr_local_report_about_rki',
                    'minobr_local_report_about_rki_pfur',
    //                'statist_full_gc_work',
    //                'fms_report_about_exam',
    //                'minobr_pfur_report_about_exam',
    //                'minobr_pfur_local_report_about_exam',
    //                'minobr_local_report_about_exam',
    //                'fms_report_about_exam_rki',
    //                'minobr_pfur_report_about_rki',
    //                'statist_exam_notes',
                    'set_archive_by_head',
                    'set_archive_by_head_dubl',
                    'messages',
                    'new_message',
                    'messages_list',
                    'message_delete',
                    'message_edit',
    //                'report_sng_exam',
                    'report_add',
                    'report_edit',
                    'reports_edit_list',
                    'report_delete',
                    'move_certificates_all_hc',
                    'save_moved_certificates',

                )
            ),
//Админ системы
            self::ROLE_ROOT => array(
                'test_levels',
                'test_levels_add',
                'test_levels_delete',
                'test_levels_edit',



                'head_center',
                'head_center_prefixes',
                'head_center_view',
                'head_center_add',
                'head_center_edit',
                'head_center_delete',
                'user_list',
                //'adm_user_list',
                'user_list_edit',
                'user_rights_edit',
                'user_delete',
                'user_create',
                'user_edit',
                'signing_list',
                'signing_delete',
                'signing_edit',
                'signing_create',
                'current_head_center_edit_text',
                'current_head_center_text_view',
                'current_head_center_view',
                'current_head_center_edit',
/*                'user_type_list',
                'user_type_add',
                'user_type_edit',
                'user_type_delete',
                'user_type_rights_edit',*/
                'user_add_form',
                'user_edit_form',
                'user_delete_form',
                //'add_user_list',
                'edit_user_list',
                'deleted_list',
//                'act_universities_archive',
                'sess_invalid',
                'federal_dc',
                'federal_dc_regions',
                'federal_dc_regions_all',
                'ajax_lc_list',

                'annul_cert',
                'annul_cert_by_range',
                'ajax_lc_list_by_hc',


                'head_centers',
                'universities',
                'university_search',
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

                'university_print_simple',
                'university_print_full',
                'university_print_full_dogovor',
                'act_upload_dogovor_scan',

                'invalid_certs',
                'log_blocked_report_unblock',
                'archive_man',
                'archive_man_file_download',
                'archive_man_passport_download',
            ),
            self::ROLE_CENTER_EDITOR => array(
                'universities',
                'university_search',
                'university_add',
                'university_edit',
                'university_view',
                'university_delete',
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
                'head_center_prefixes',
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
                'signing_list',
                'signing_delete',
                'signing_edit',
                'signing_create',
            ),
            self::ROLE_BUH => array(

                'buh_check_univer',
                'buh_list',

            ),
            self::ROLE_CENTER_EXTERNAL => array(
                'act_add',
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
                'act_third_list',
                'act_third_view',
                'act_table_view',
                'act_finished',
                'act_print',
                'act_print_migrant',
                'act_table_print',
                'act_return_to_work',
                'act_univer_on_check',
                'act_passport',
                 'checked_list',
                'rework_list',
            ),
            self::ROLE_CENTER_FOR_CHECK => array(
                'act_second_list',
                'act_second_view',
                'act_second_test_add',
                'act_table_second',
                'act_second_edit',
                'act_checked',
                'act_universities_second',
                'act_return_work',
                'set_archive',

            ),
            self::ROLE_CENTER_RECEIVED => array(
                'act_universities_received',
                'act_received_list',
                'act_received_view',
                'act_received_table_view',
                //   'act_receive_numbers',
                'print_invoice',
                'checked_list',
                'rework_list',
                'set_archive',


            ),
            self::ROLE_CENTER_FOR_PRINT => array(
                'act_universities_checks',
                'act_checks_list',
                'act_received_view',
                'act_received_table_view',
                'act_receive_numbers',
                'print_invoice',
                'scan_blank_upload',
                'checked_list',
                'rework_list',
                'set_archive',

            ),
            self::ROLE_CENTER_PRINT => array(
                'act_universities_print',
                'act_print_list',
                'act_received_view',
                'act_received_table_view',
                'act_receive_numbers',
                'act_man_print_pril_cert',
                'print_certificate',
                'act_grazhdan',
                'act_rki',
                'act_vidacha_cert',
                'act_vidacha_note',
                'act_vidacha_reestr',
                'print_invoice',
                'scan_blank_upload',
                'checked_list',
                'rework_list',
                'scan_blank_upload',
                 'set_archive',
            ),
            self::ROLE_CENTER_WAIT_PAYMENT => array(
                'act_universities_wait',
                'act_wait_payment_list',
                'act_received_view',
                'act_received_table_view',
                'act_receive_numbers',
                'print_invoice',
                'act_set_payed',
                'checked_list',
                'rework_list',
                'set_archive',
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
                'act_grazhdan',
                'act_rki',
                'act_vidacha_cert',
                'print_invoice',
                'act_man_print_pril_cert',
                'set_archive',

            ),
            self::ROLE_REPORT => array(
                'otch_country',
                'act_received_view',
                'act_received_table_view',
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
                'act_vidacha_note',
                'act_vidacha_reestr',
                'print_certificate',
                'print_certificates',
                'act_man_print_pril_cert',
                'act_rki',
                'act_rkis',
                'act_man_print_pril_certs',
                'man_duplicate',
                'search_pupil_range_annul',

                'act_summary_table',
                'act_print_view',
                'act_table_print_view',
            ),
            self::ROLE_FMS_ADMIN => array(
                /*diff5*/
                'user_add_form_fms',
                'user_edit_form_fms',
                'edit_user_list_fms',
                'user_delete_form_fms',
            ),
            self::ROLE_FMS_USER => array(
               /*diff2
                'search_pupil_fms',
                'search_act_fms',
                'otch_country_fms',
                'act_received_view',
                'act_received_table_view',
                'act_archive_numbers',
                'print_invoice',
                'act_archive_numbers',
                'act_vidacha_cert',
                'act_vidacha_note',
                'act_vidacha_reestr',
                'print_certificate',
                'print_certificates',
                'act_man_print_pril_cert',
                'act_rki',
                'act_rkis',*/
            ),
            self::ROLE_STATISTICS => array_merge(
                Reports::getRoles4User(),
                array(
                    'statistics',
                    'excel_report_download',
                    'excel_report_delete',
                    /*'statist_full_gc_work',
                    'fms_report_about_exam',
                    'minobr_pfur_report_about_exam',
                    'minobr_pfur_local_report_about_exam',
                    'minobr_local_report_about_exam',
                    'fms_report_about_exam_rki',
                    'minobr_pfur_report_about_rki',
                    'statist_exam_notes',
                    'report_sng_exam',*/

                )
            ),
        );
    }

    public function getRoleAccess($action)
    {
        $roles = $this->getActions();

        //     $actions = $roles[$this->getCurrentRole()];
        $actions = array(
            'access_restricted',
            'index',
            'download',
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

    protected function setCurrentRole()
    {

        if (!empty($_SESSION['privelegies']['admin_head']) && $_SESSION['privelegies']['admin_head']) {
            $this->current_role[] = self::ADMIN_HEAD;
        }


        if (!empty($_SESSION['privelegies']['level_list']) && $_SESSION['privelegies']['level_list']) {
            $this->current_role[] = self::ROLE_ROOT;
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
        if (!empty($_SESSION['privelegies']['fms_admin'])) {
            $this->current_role[] = self::ROLE_FMS_ADMIN;
        }
        if (!empty($_SESSION['privelegies']['fms_user'])) {
            $this->current_role[] = self::ROLE_FMS_USER;
        }
        if (!empty($_SESSION['privelegies']['statistics'])) {
            $this->current_role[] = self::ROLE_STATISTICS;
        }

    }

    public function userHasRole($role)
    {

        return in_array($role, $this->current_role);
    }

    private $universityRestrictionArray;

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
        $sqlUserRestrict = 'AND  (suu.user_id = ' . intval($_SESSION['u_id']) . ' OR suu.user_id IS NULL) ';
        if ($this->userHasRole(self::ROLE_ADMIN)) {
            $sqlUserRestrict = '';
        }

        if (!empty($_SESSION['univer_id'])) {
            return array($_SESSION['univer_id']);
        }
//die(var_dump(CURRENT_HEAD_CENTER));
        $sql = 'SELECT
  sdt_university.id as univer_id,
  suu.user_id AS user

FROM sdt_university
  LEFT  JOIN sdt_univer_user suu
    ON sdt_university.id = suu.univer_id

  WHERE sdt_university.head_id =    ' . CURRENT_HEAD_CENTER . ' ' . $sqlUserRestrict;
        //die($sql);
        $res = mysql_query($sql);
        $univers = array();
        while ($row = mysql_fetch_assoc($res)) {
            $univers[] = $row['univer_id'];
        }
        $univers[] = '-1';
        $this->universityRestrictionArray = $univers;

        return $this->universityRestrictionArray;

    }

    public function getCurrentRole()
    {
        return $this->current_role;
    }

    public function emptyRoles()
    {
        $this->current_role = array();
    }
}