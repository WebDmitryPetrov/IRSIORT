<?php

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 30.01.2017
 * Time: 17:16
 * @property Render render
 */
trait actOnCheckTrait
{
    protected function act_universities_second_action()
    {
        $acts = Univesities::getByLevel(Act::STATE_SEND);
        $acts = $this->universitySortByParent($acts);
        return $this->render->view(
            'acts/act_check/universities_list',
            array(
                'list' => $acts,

            )
        );


    }

    protected function on_check_action()
    {
        if (empty($_GET['uid']) || !is_numeric($_GET['uid'])) {
            $_SESSION['flash'] = 'Локальный центр не указан';
            $this->redirectReturn();
        }
        $acts = Acts::getOnCheck($_GET['uid']);
        $signings = ActSignings::get4Invoice();
        $univer = University::getByID($_GET['uid']);
        return $this->render->view(
            'acts/act_check/level_list',
            array(
                'univer' => $univer,
                'list' => $acts,
                'signings' => $signings

            )
        );
    }

    protected function act_second_view_action()
    {
        $act = Act::getByID($_GET['id']);
        if ($act->state != Act::STATE_SEND) $this->redirectAccessRestricted();
        $this->checkActIsEditable($act);
        $act->incrementViewedAndSave();

        return $this->render->view(
            'acts/act_check/level_view',
            array(
                'object' => $act,

            )
        );
    }

    protected function act_table_second_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $act_id = $_GET['id'];
        $act = Act::getByID($act_id);
        if ($act->state != Act::STATE_SEND) $this->redirectAccessRestricted();
        $this->checkActIsEditable($act);
        $act->incrementViewedAndSave();
        $countries = Countries::getAll();
        if (count($_POST)) {
            foreach ($_POST['user'] as $id => $params) {
                $man = ActMan::getByID($id);
                $man->parseParameters($params);
                $man->save();
            }
            $act->is_changed_checker = 1;
//            $act->tester1 = $_POST['tester1'];
//            $act->tester2 = $_POST['tester2'];

            $act->save();
            $_SESSION['flash'] = 'Таблица сохранена';
        }

        return $this->render->view(
            'acts/act_check/table_second',
            array(
                //'people' => $people,
                'Act' => $act,
                'Countries' => $countries,
            )
        );
    }

    protected function act_second_edit_action()
    {
        $act = Act::getByID($_GET['id']);
        if ($act->state != Act::STATE_SEND) $this->redirectAccessRestricted();
        $this->checkActIsEditable($act);
        if (count($_POST)) {
            $act->parseParameters($_POST);
            $act->is_changed_checker = 1;
            $id = $act->save();
            Session::setFlash('Акт сохранен');

        }

        return $this->render->view(
            'acts/act_check/second_edit',
            array(
                'Act' => $act,
                'Legend' => 'Редактировать акт.',
                'University' => University::getByID($act->university_id)
            )
        );

    }

    protected function act_return_work_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $act = Act::getByID($_GET['id']);
        if ($act->state != Act::STATE_SEND) $this->redirectAccessRestricted();
        $this->checkActIsEditable($act);
        $act->setState(Act::STATE_INIT);
        $act->is_changed_checker = 1;
        $act->save();
        writeStatistic(
            'sdt',
            'act_returned',
            array(
                'id' => $act->id,
                'univer_id' => $act->getUniversity()->id,
                'univer_name' => $act->getUniversity()->name,
            )
        );
        $_SESSION['flash'] = 'Акт возвращен на доработку';
        $this->redirectReturn();
    }

    protected function act_checked_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $act = Act::getByID($_GET['id']);
        if ($act->state != Act::STATE_SEND) $this->redirectAccessRestricted();
        $this->checkActIsEditable($act);
        $act->setStateChecked();
        $act->is_changed_checker = 0;
        $act->save();

        $_SESSION['flash'] = 'Акт отмечен как проверенный';

        writeStatistic(
            'sdt',
            'act_set_checked',
            array(
                'id' => $act->id,
                'univer_id' => $act->getUniversity()->id,
                'univer_name' => $act->getUniversity()->name,


            )
        );
        $this->redirectReturn();
    }

}