<?php

/**
 * Trait ReportsTrait
 * @property Render render
 */
trait ReportsTrait
{
    public function log_invalid_report_action()
    {
        $search = false;
        $result = array();
        $from = (new DateTime('-1 day'))->format('d.m.Y');
        $to = date('d.m.Y');
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $connection = Connection::getInstance();
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $report = new \SDT\statistic\LogInvalidReport();
            $result = $report->execute(new \DateTime($from), new \DateTime($to));
        }
        if (!$result) {
            $result = [];
        }

        return $this->render->view(
            'root/statist/log_invalid_report',
            array(
                'array' => $result,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'caption' => 'Информация по неправильным авторизациям в систему',
            )
        );
    }

    public function log_blocked_report_unblock_action()
    {
        $log_row = filter_input(INPUT_GET, 'row_id', FILTER_VALIDATE_INT);
        if (!$log_row) {
            die('invalid number');
        }
        $c = Connection::getInstance();
        $sql = 'update log_login_block set blocked=0 ,  unblocked_at=now() where  id = %d';
        $c->execute(
            vsprintf(
                $sql,
                [
                    $log_row,
                ]
            )
        );
        $this->redirectByAction('log_blocked_report', [], '#r'.$log_row);
    }

    public function log_blocked_report_action()
    {
        $search = false;
        $result = array();
        $search = true;
        $connection = Connection::getInstance();
        $sql = "SELECT lli.*, 
         inv.user_id,
       inv.user_name, inv.lc_id, 
       inv.login, inv.head_id, inv.head_name 
from log_login_block lli 
LEFT JOIN log_login_invalid inv ON inv.id = lli.attempt_id
where 
            block_until >= NOW()

            order by created_at desc
            ";
        $result = $connection->query($sql);
        if (!$result) {
            $result = [];
        }

        return $this->render->view(
            'root/statist/log_blocked_report',
            array(
                'array' => $result,
                'search' => $search,
                'caption' => 'Список заблокированных ip',
            )
        );
    }

    public function report_login_action()
    {
        $search = false;
        $result = array();
        $from = date('d.m.Y', strtotime('-1 month'));
        $to = date('d.m.Y');
        $HC_list = HeadCenters::getAll();
        $selected = null;
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
//            var_dump('sdf');
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $selected = filter_input(INPUT_POST, 'hc');
            $search = true;
            $report = new LoginReport();
            $result = $report->execute($from, $to, $selected);
//            var_dump($result);
        }

        return $this->render->view(
            'root/statist/report_login',
            array(
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'hcs' => $HC_list,
                'hc' => $selected,
                'result' => $result,
            )
        );
    }

    public function report_ke_by_year_action()
    {
        $report = new \SDT\statistic\ExamByYear();
        $from = 2017;
        $to = date('Y');
        $result = [];
        $submitted = false;
        if (filter_input(INPUT_POST, 'from', FILTER_VALIDATE_INT) && filter_input(
                INPUT_POST,
                'to',
                FILTER_VALIDATE_INT
            )) {
//            var_dump('sdf');
            $submitted = true;
            $from = filter_input(INPUT_POST, 'from', FILTER_VALIDATE_INT);
            $to = filter_input(INPUT_POST, 'to', FILTER_VALIDATE_INT);
            $result = $report->execute($from, $to);
        }

        return $this->render->view(
            'root/statist/report_ke_by_year',
            array(
                'submitted' => $submitted,
                'from' => $from,
                'to' => $to,
                'result' => $result,
            )
        );
    }

    protected function report_contracts_by_year_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.'.date('Y');
        $to = date('d.m.Y');
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $connection = Connection::getInstance();
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $report = new CotractsByYearAndMonth();
            $result = $report->execute($from, $to);
        }

        return $this->render->view(
            'root/statist/contracts_by_year_month',
            array(
                'from' => $from,
                'to' => $to,
                'result' => @$result['norm'],
                'search' => $search,
            )
        );
    }

    protected function report_exam_by_age_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.'.date('Y');
        $to = date('d.m.Y');
        $selected_hc = false;
        $ageString = '18 - 24, 25 - 34, 35 - 50, 51 - 65';
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $selected_hc = filter_input(INPUT_POST, 'hc');
            $ageString = filter_input(INPUT_POST, 'ageString');
            $report = new ReportExamByYear();
            $result = $report->execute($from, $to, $ageString, $selected_hc);
//            die(var_dump($result));
        }
        $hc_list = HeadCenters::getHeadOrgs();

        return $this->render->view(
            'root/statist/report_exam_by_age',
            array(
                'array' => isset($result[1]) ? $result[1] : [],
                'ages' => isset($result[0]) ? $result[0] : [],
                'search' => $search,
                'hc_list' => $hc_list,
                'selected_hc' => $selected_hc,
                'ageString' => $ageString,
                'from' => $from,
                'to' => $to,
                'caption' => 'Статистика работы  головных центров с разбивкой по возрастам',
            )
        );
    }

    protected function report_find_people_lc_action()
    {
        $search = false;
        $result = array();
        $people = trim(filter_input(INPUT_POST, 'people', FILTER_SANITIZE_STRING));
        if ($people) {
            $search = true;
            $report = new ReportFindPeopleLc();
            $result = $report->execute($people);
//            die(var_dump($result));
        }

        return $this->render->view(
            'root/statist/report_find_people_lc',
            array(
                'result' => $result,
                'search' => $search,
                'people' => $people,
                'caption' => 'Поиск информации по протестированным и ЛЦ по КЭ',
            )
        );
    }

    protected function mvd_report_2020_01_17_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.2015';
        $to = date('d.m.Y');
        $selected_hc = false;
        $lc = false;
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $selected_hc = filter_input(INPUT_POST, 'hc');
            $lc = filter_input(INPUT_POST, 'lc');
            $test_type = filter_input(INPUT_POST, 'test_type', FILTER_VALIDATE_INT);
            $files = (bool)filter_input(INPUT_POST, 'files');
//            die(var_dump($files));
            $report = new \SDT\statistic\MVD_2020_01_17();
            $result = $report->execute($from, $to, $selected_hc, $lc, $test_type, $files);
            die;
//            die(var_dump($result));
        }
        $hc_list = HeadCenters::getHeadOrgs();

        return $this->render->view(
            'root/statist/mvd_report_2020_01_17',
            array(
                'array' => isset($result[1]) ? $result[1] : [],
                'enable_lc' => true,
                'search' => $search,
                'hc_list' => $hc_list,
                'selected_hc' => $selected_hc,
                'from' => $from,
                'to' => $to,
                'caption' => 'Отчет МВД 17.01.2020',
            )
        );
    }
}