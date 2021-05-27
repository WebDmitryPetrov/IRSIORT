<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 16.11.2017
 * Time: 17:41
 */


/**
 * Trait ActInitTrait
 * @property Render render
 */
trait ActInitTrait
{


    private $message_no_signings = 'Тестовую сессию создать нельзя - введите в ИРСИОРТ ФИО руководителей и ответсвенных за тестирование';
    private $message_no_dogovor = 'Для данного типа тестирования отсутствуют договора';

    protected function act_add_action()
    {

        $type = $this->getNumeric('type', false);
        if (!$type || !$type = TestLevelType::getByID($type)) {
            $this->redirectByAction('act_choose');
        }
        $act = new Act();

        $specialPrice = filter_input(INPUT_GET, 'sp', FILTER_VALIDATE_INT);
        $test_group = filter_input(INPUT_GET, 'g', FILTER_VALIDATE_INT);

        $act->test_level_type_id = $type->id;
        $act->paid = 0;
        // $act->state = 'init';
        $act->user_create_id = Session::getUserID();
        $act->university_id = Session::getLocalCenterID();
        $university = University::getByID(Session::getLocalCenterID());


        if (!count($university->getDogovorsByType($act->test_level_type_id))) {
            Session::setFlash($this->message_no_dogovor);
            $this->redirectByAction('act_choose');
        }

        if (!$university->getSigning(CenterSigning::TYPE_APPROVE) || !$university->getSigning(CenterSigning::TYPE_RESPONSIVE)) {
            Session::setFlash($this->message_no_signings);

            $this->redirectByAction('act_choose');
        }

//        if (!empty($_COOKIE['new_act_official'])) {
//            $act->official = $_COOKIE['new_act_official'];
//        }
//        if (!empty($_COOKIE['new_act_responsible'])) {
//            $act->responsible = $_COOKIE['new_act_responsible'];
//        }

        $horg = $university->getHeadCenter()->horg_id;

        $dateError = false;

        if (count($_POST)) {
            $act->parseParameters($_POST);
//            setcookie('new_act_responsible', $act->responsible);
//            setcookie('new_act_official', $act->official);

            if (
                $horg != 1 ||
                (strtotime($act->testing_date) + 15 * 60 * 60 * 24) > time() || $university->getParentedFieldValue('is_old_act')

            ) {


                $id = $act->save();
                $act = Act::getByID($id);
                $act->setState(Act::STATE_INIT);
                $act->save();


                if ($specialPrice || $test_group) {
                    $meta = new ActMetaData();
                    $meta->act_id = $id;
                    if ($specialPrice) {
                        $meta->special_price_group = $specialPrice;
                    }

                    if ($test_group) {
                        $meta->test_group = $test_group;
                    }


                    $meta->save();
                }
                writeStatistic(
                    'sdt',
                    'act_new',
                    array(
                        'id' => $id,
                        'univer_id' => $act->getUniversity()->id,
                        'univer_name' => $act->getUniversity()->name,
                    )
                );
                $_SESSION['flash'] = 'Акт создан';
                $this->redirectByAction('act_fs_view', array('id' => $id));
            } else {
                Session::setFlash('Дата тестирования устарела!<br>
Разрешается давность не более 15 дней!<br>
Обратитесь в свой головной центр.');
            }
        }


        return $this->render->view(
            'acts/init/edit',
            array(
                'Act' => $act,
                'Legend' => 'Добавить акт',
                'University' => $university

            )
        );

    }

    protected function act_send_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $act = Act::getByID($_GET['id']);

        if (
            !$act->checkRequiredFields($errors)
            || !$act->canBeSended()
        ) {
            $this->redirectAccessRestricted();
        }

        if ($act->state != Act::STATE_INIT) $this->redirectAccessRestricted();

        $act->setStateSend();

//        die(var_dump($act));
        $act->save();
        writeStatistic(
            'sdt',
            'act_send_check',
            array(
                'id' => $act->id,
                'univer_id' => $act->getUniversity()->id,
                'univer_name' => $act->getUniversity()->name,
            )
        );
        // die();
        $_SESSION['flash'] = 'Акт о тестовой сессии отправлен на проверку';
        $this->redirectReturn();
    }


    protected function act_fs_edit_action()
    {
        $act = Act::getByID($_GET['id']);
        if ($act->state != Act::STATE_INIT) $this->redirectAccessRestricted();
        $university = $act->getUniversity();

        if (!$university->getSigning(CenterSigning::TYPE_APPROVE) || !$university->getSigning(CenterSigning::TYPE_RESPONSIVE)) {
            Session::setFlash($this->message_no_signings);
            $this->redirectByAction('act_fs_list');
        }
        if (!count($act->getUniversity()->getDogovorsByType($act->test_level_type_id))) {

            Session::setFlash($this->message_no_dogovor);
            $this->redirectByAction('act_fs_list');
        }

        if (count($_POST)) {
//            die(var_dump($_POST));
            $act->parseParameters($_POST);
            $id = $act->save();

        }

        return $this->render->view(
            'acts/init/edit',
            array(
                'Act' => $act,
                'Legend' => 'Редактировать акт',
                'University' => University::getByID($_SESSION['univer_id'])
            )
        );

    }

    protected function act_fs_list_action()
    {

        $acts = Acts::getByLevel($_SESSION['univer_id'], Act::STATE_INIT);

        return $this->render->view(
            'acts/init/level_list',
            array(
                'list' => $acts,

            )
        );
    }

    protected function act_test_add_action()
    {

        $actTest = new ActTest();
        $id1 = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $actTest->act_id = $id1;
        $Act_type = $actTest->getAct()->test_level_type_id;

        $act = Act::getByID($id1);
        $inWorkVoter = new InWorkVoter();
        if (!$inWorkVoter->isPeopleEditGranted($act)) $this->redirectAccessRestricted();

        if (count($_POST)) {

            $actTest->parseParameters($_POST);

            if ($Act_type == 1) {
                $actTest->people_subtest_retry = $actTest->people_retry;
            }

//			echo '<pre>';

            //die(var_dump($act));
            $id = $actTest->save();

            $_SESSION['flash'] = "Добавлено тестирование";
            $this->redirectByAction('act_fs_view', array('id' => $actTest->act_id));
        }


        $testLevels = TestLevels::getAvailable4Act($actTest->getAct());
//        die(var_dump($actTest->getAct()->getMeta()));
        return $this->render->view(
            'acts/forms/act_test',
            array(
                'Act' => $actTest,
                'Legend' => 'Добавить тестирование',
                'Levels' => $testLevels,
                'Type' => $Act_type,
            )
        );

    }

    protected function act_test_edit_action()
    {

        $act = ActTest::getByID($_GET['id']);


        if (count($_POST)) {
            $act->parseParameters($_POST);
            $act->save();
            $this->redirectByAction('act_fs_view', array('id' => $act->act_id));
        }

        return $this->render->view(
            'acts/forms/act_test',
            array(
                'Act' => $act,
                'Legend' => 'Редактировать тестирование',
                'Levels' => TestLevels::getAll(),

            )
        );

    }

    protected function act_test_delete_action()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            $this->redirectReturn();
        }
        $testLevel = ActTest::getByID($id);


        $act = $testLevel->getAct();
        $inWorkVoter = new InWorkVoter();
        if (!$inWorkVoter->isPeopleEditGranted($act)) $this->redirectAccessRestricted();

        $testLevel->delete();
        $_SESSION['flash'] = 'Уровень тестирования удален';
        $this->redirectReturn();
    }

    protected function act_choose_action()
    {
        $types = TestLevelTypes::getAll();

        return $this->render->view(
            'acts/init/choose_test_type',
            array(
                'Types' => $types,
                'Legend' => 'Выбрать тип проведенного тестированимя',


            )
        );
    }


    protected function act_fs_view_action()
    {
        $act = Act::getByID($_GET['id']);

        if ($act->state != Act::STATE_INIT) $this->redirectAccessRestricted();

        $inWorkVoter = new InWorkVoter();

        return $this->render->view(
            'acts/init/view',
            array(
                'inWorkVoter' => $inWorkVoter,
                'object' => $act,

            )
        );
    }


    protected function act_invalid_action()
    {
        $univer = Act::getByID($_GET['id']);

        if ($univer->state != Act::STATE_INIT) $this->redirectAccessRestricted();

        $univer->delete();

        writeStatistic(
            'sdt',
            'act_init_invalid',
            array(
                'id' => $univer->id,
                'univer_id' => $univer->getUniversity()->id,
                'univer_name' => $univer->getUniversity()->name,
            )
        );

        $_SESSION['flash'] = "Тестовая сессия отмечена как недействительная";
        //  $this->redirectByAction('act_fs_list');
        $this->redirectReturn();
    }

}