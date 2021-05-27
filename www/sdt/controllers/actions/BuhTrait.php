<?php

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 26.01.2017
 * Time: 15:54
 * @property Render render
 */
trait BuhTrait
{
    protected function buh_list_action()
    {

        $m = filter_input(INPUT_GET, 'm', FILTER_VALIDATE_INT);
        $y = filter_input(INPUT_GET, 'y', FILTER_VALIDATE_INT);
        if (empty($m) || empty($y)) {
            $_SESSION['flash'] = 'Месяц или год не указаны';
            $this->redirectReturn();
        }

//die(date('r'));
        $acts = Acts::get4Buh($m, $y);
//       usort($acts,function (Act $left, Act $right) {
//           $left_name = $left->getUniversity()->getLegalInfo()['name_parent'];
//           $right_name = $right->getUniversity()->getLegalInfo()['name_parent'];
//           if($left_!=$r)
//           return strcmp($left_name,
//               $right_name);
//        });
        return $this->render->view(
            'buh/list',
            array(
                'list' => $acts,
            )
        );
    }

    protected function buh_search_act_action()
    {
        $result = array();
        $params = array(
            'minAddDate' => Acts::getMinAddDate(),
            'maxAddDate' => Acts::getMaxAddDate(),
            'minTestDate' => Acts::getMinTestDate(),
            'maxTestDate' => Acts::getMaxTestDate(),
            'level' => 0,
            'organization' => 0,
            'state' => 'archive',
            'org_ids' => null,
            'act_id' => null,
        );
        $partnersList = Univesities::getPartnersList();
        if (count($_POST)) {
            $_POST['minAddDate'] = $this->mysql_date($_POST['minAddDate']);
            $_POST['maxAddDate'] = $this->mysql_date($_POST['maxAddDate']);
            $_POST['minTestDate'] = $this->mysql_date($_POST['minTestDate']);
            $_POST['maxTestDate'] = $this->mysql_date($_POST['maxTestDate']);
            $params = array_merge($params, $_POST);
            if ($params['organization']) {
                $params['org_ids']=$partnersList[$params['organization']]['ids'];
            }
            $result = Acts::Search($params);
        }


        return $this->render->view(
            'buh/search_act',
            array(
                'Result' => $result,
                'query' => $params,
                'Universities' => $partnersList,
                'Levels' => TestLevels::getAll(),

            )
        );
    }

    protected function buh_view_table_action()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            $this->redirectReturn();
        }
        $act = Act::getByID($id);

        if (!$act
//            || $act->isDeleted()

        ) {
            $this->redirectAccessRestricted();
        }

//        $this->checkActIsEditable($act);

        return $this->render->view(
            'buh/table_view',
            array(
                //'people' => $people,
                'Act' => $act,

            )
        );
    }

    protected function buh_dubl_month_action()
    {

        $univers = DublActList::getMonthList4Buh();

        return $this->render->view(
            'buh/dubl_month_list',
            array(
                'list' => $univers,

            )
        );


    }

    protected function buh_dubl_list_action()
    {

        $m = filter_input(INPUT_GET, 'm', FILTER_VALIDATE_INT);
        $y = filter_input(INPUT_GET, 'y', FILTER_VALIDATE_INT);
        if (empty($m) || empty($y)) {
            $_SESSION['flash'] = 'Месяц или год не указаны';
            $this->redirectReturn();
        }


        $acts = DublActList::get4Buh($m, $y);

        return $this->render->view(
            'buh/dubl_list',
            array(
                'list' => $acts,
            )
        );
    }

    protected function buh_check_univer_action()
    {

        $univers = Acts::getMonthList4Buh();

        return $this->render->view(
            'buh/universities_list',
            array(
                'list' => $univers,

            )
        );
    }

    protected function buh_view_act_action()
    {
        $act = Act::getByID(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));
        if (!$act) {
            $this->redirectAccessRestricted();
        }


        return $this->render->view(
            'buh/view_act',
            array(
                'object' => $act,
//                'invoiceSignings' => $invoiceSignings,
//                'vidachaSignings' => $vidachaSignings,
            )
        );
    }

    protected function buh_dubl_view_act_action()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);


        if (!$id) {
            Session::setFlash('Не найден id');
            $this->redirectAccessRestricted();
        }

        $dubl = DublAct::getByID($id);
        if (!$dubl) {
            Session::setFlash('Не найден запрос на дубликат');
            $this->redirectAccessRestricted();
        }
        if ($lc = Session::getLocalCenterID()) {
            if ($lc != $dubl->center_id) {
//                Session::setFlash('Y');
                $this->redirectAccessRestricted();
            }
        } else {
            if ($dubl->getLocalCenter()->head_id != CURRENT_HEAD_CENTER) {
                $this->redirectAccessRestricted();
            }
        }


        $people = $dubl->getPeople();

        return $this->render->view('buh/dubl_view_act', array(
            'dubl' => $dubl,
            'people' => $people,
        ));

//        return $this->render->view(
//            'buh/view_act',
//            array(
//                'object' => $act,
////                'invoiceSignings' => $invoiceSignings,
////                'vidachaSignings' => $vidachaSignings,
//            )
//        );
    }


    protected function report_check_univer_action()
    {

        $univers = Acts::getReestrMonthList4Buh();

        return $this->render->view(
            'report/universities_list',
            array(
                'list' => $univers,

            )
        );


    }

    protected function report_view_act_action()
    {
        $act = Act::getByID(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));
        if (!$act) {
//            die('ddd');
            $this->redirectAccessRestricted();
        }


        return $this->render->view(
            'report/view_act',
            array(
                'object' => $act,
//                'invoiceSignings' => $invoiceSignings,
//                'vidachaSignings' => $vidachaSignings,
            )
        );
    }

    protected function report_dubl_view_act_action()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);


        if (!$id) {
            Session::setFlash('Не найден id');
            $this->redirectAccessRestricted();
        }

        $dubl = DublAct::getByID($id);
        if (!$dubl) {
            Session::setFlash('Не найден запрос на дубликат');
            $this->redirectAccessRestricted();
        }
        if ($lc = Session::getLocalCenterID()) {
            if ($lc != $dubl->center_id) {
//                Session::setFlash('Y');
                $this->redirectAccessRestricted();
            }
        } else {
            if ($dubl->getLocalCenter()->head_id != CURRENT_HEAD_CENTER) {
                $this->redirectAccessRestricted();
            }
        }


        $people = $dubl->getPeople();

        return $this->render->view('report/dubl_view_act', array(
            'dubl' => $dubl,
            'people' => $people,
        ));

//        return $this->render->view(
//            'report/view_act',
//            array(
//                'object' => $act,
////                'invoiceSignings' => $invoiceSignings,
////                'vidachaSignings' => $vidachaSignings,
//            )
//        );
    }
    protected function report_dubl_list_action()
    {

        $m = filter_input(INPUT_GET, 'm', FILTER_VALIDATE_INT);
        $y = filter_input(INPUT_GET, 'y', FILTER_VALIDATE_INT);
        if (empty($m) || empty($y)) {
            $_SESSION['flash'] = 'Месяц или год не указаны';
            $this->redirectReturn();
        }


        $acts = DublActList::get4Buh($m, $y);

        return $this->render->view(
            'report/dubl_list',
            array(
                'list' => $acts,
            )
        );
    }

    protected function report_view_table_action()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            $this->redirectReturn();
        }
        $act = Act::getByID($id);

        if (!$act
//            || $act->isDeleted()

        ) {
            $this->redirectAccessRestricted();
        }

//        $this->checkActIsEditable($act);

        return $this->render->view(
            'report/table_view',
            array(
                //'people' => $people,
                'Act' => $act,

            )
        );
    }

    protected function report_dubl_month_action()
    {

        $univers = DublActList::getMonthList4Buh();

        return $this->render->view(
            'report/dubl_month_list',
            array(
                'list' => $univers,

            )
        );


    }
    protected function report_list_action()
    {

        $m = filter_input(INPUT_GET, 'm', FILTER_VALIDATE_INT);
        $y = filter_input(INPUT_GET, 'y', FILTER_VALIDATE_INT);
        if (empty($m) || empty($y)) {
            $_SESSION['flash'] = 'Месяц или год не указаны';
            $this->redirectReturn();
        }

//die(date('r'));


        $v = Acts::getReestr4BuhDates($m, $y);
        $qvars = [
            'minActDate' => $v['min_created'],
            'maxActDate' => $v['max_created'],
            'minActDate_db' => $v['min_created'],
            'maxActDate_db' => $v['max_created'],
            'minTestDate' => $v['min_test'],
            'maxTestDate' => $v['max_test'],
            'minTestDate_db' => $v['min_test'],
            'maxTestDate_db' => $v['max_test'],
            'minCheckDate' => $v['min_invoice'],
            'maxCheckDate' => $v['max_invoice'],
            'minCheckDate_db' => $v['min_invoice'],
            'maxCheckDate_db' => $v['max_invoice'],
        ];

        if (count($_POST)) {
            $_POST['minActDate'] = $this->mysql_date($_POST['minActDate']);
            $_POST['maxActDate'] = $this->mysql_date($_POST['maxActDate']);

            $_POST['minTestDate'] = $this->mysql_date($_POST['minTestDate']);
            $_POST['maxTestDate'] = $this->mysql_date($_POST['maxTestDate']);

            $_POST['minCheckDate'] = '1970-01-01'!==$this->mysql_date($_POST['minCheckDate'])?$this->mysql_date($_POST['minCheckDate']):'0000-00-00';
            $_POST['maxCheckDate'] = $this->mysql_date($_POST['maxCheckDate']);
            $qvars = array_merge($qvars, $_POST);

        }

//var_dump($qvars);
//        var_dump($qvars);die;
        $acts = Acts::getReestr4Buh($m, $y, $qvars);


//       usort($acts,function (Act $left, Act $right) {
//           $left_name = $left->getUniversity()->getLegalInfo()['name_parent'];
//           $right_name = $right->getUniversity()->getLegalInfo()['name_parent'];
//           if($left_!=$r)
//           return strcmp($left_name,
//               $right_name);
//        });

        if(!empty($qvars['xls']))
        {

            $list = $acts;

            $template = 'report/list_to_excel';
            require_once(DC . '/sdt/template/view/' . $template . '.php');
            die();



            /*return $this->render->view(
                'report/excel',
                array(
                    'query' => $qvars,
                    'array' => $acts,
                    'table_head' => $table_head,
                    'caption' => 'Реестр актов за '.$m.'.'.$y,
                )
            );
*/

        }





        else

            return $this->render->view(
                'report/list',
                array(
                    'query' => $qvars,
                    'list' => $acts,
                )
            );
    }

    protected function buh_search_man_action()
    {
        $result = array();
        $query = $certificate = $name = '';
        if (count($_POST)) {
            $query = filter_input(INPUT_POST, 'query', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $certificate = filter_input(INPUT_POST, 'certificate', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
//            $blank = $_POST['blank'];
            $result = ActPeople::Search($query, $certificate, $name);
//            echo '<pre>';
//            var_dump($result);
//            echo '</pre>';
//            die(var_dump($result));
        }

        return $this->render->view(
            'buh/search_pupil',
            array(
                'Result' => $result,
                'query' => $query,
                'name' => $name,
                'certificate' => $certificate,

            )
        );
    }
}