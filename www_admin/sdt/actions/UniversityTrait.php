<?php

require_once 'SortByParentTrait.php';

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 19.01.2017
 * Time: 15:26
 *
 * @property Render render
 */
trait UniversityTrait
{
    use SortByParentTrait;
    protected function head_centers_action()
    {
//var_dump($_SESSION);die;
        $univers = HeadCenters::getAll();

        return $this->render->view(
            'head_center/all_4_lc',
            array(
                'list' => $univers,
            )
        );
    }

    protected function universities_action()
    {
        $univers = Univesities::getDictList();
        return $this->render->view(
            'universities/all',
            array(
                'list' => $univers
            )
        );

    }
    protected function university_search_action()
    {
        $univers = Univesities::search($_GET);
        return $this->render->view(
            'universities/search',
            array(
                'list' => $univers
            )
        );

    }


    protected function university_add_action()
    {

        $univer = new University();

        $h_id=filter_input(INPUT_GET, 'h_id', FILTER_VALIDATE_INT);

        if (count($_POST)) {
            $univer->parseParameters($_POST);
            $univer->head_id = $h_id;
            $univer->save($h_id);
            $params = array('id' => $univer->id);
            if ($univer->user_password) {
                $params['pwd'] = $univer->user_password;
                $_SESSION['flash'] = 'Новый пароль действует 6 дней до его смены пользователем!';
            }
            $this->redirectByAction('university_view', $params);
        }

        $univer->print_invoice_quoute = 1;
        return $this->render->form('add', $univer, 'Добавление  локального центра партнёра');


    }

    protected function university_edit_action()
    {


        if (empty(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)) || !is_numeric(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT))) {
            $this->redirectReturn();
        }
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $univer = University::getByID($id);

        if (!$univer) {
            $this->redirectAccessRestricted();
        }

        if (!empty($_GET['do'])) {
            switch ($_GET['do']) {
                case 'change_pwd':
                    $univer->resetPassword();
                    $univer->save();
                    $params = array('id' => $univer->id);
                    if ($univer->user_password) {
                        $params['pwd'] = $univer->user_password;
                    }

                    $_SESSION['flash'] = '<br>Новый пароль действует 6 дней до его смены пользователем!';

                    $this->redirectByAction('university_view', $params);
            }
        }


        if (count($_POST)) {
            $univer->parseParameters($_POST);
            $univer->save();
            $params = array('id' => $univer->id);
            if ($univer->user_password) {
                $params['pwd'] = $univer->user_password;
            }
            $this->redirectByAction('university_view', $params);
        }
        $buttons = array(
            array(
                'link' => 'index.php?action=university_edit&id=' . $univer->id . '&do=change_pwd',
                'caption' => 'Сбросить пароль'
            )
        );

        return $this->render->form('add', $univer, 'Редактирование локального центра партнёра', $buttons);

    }

    protected function university_view_action()
    {

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        $univer = University::getByID($id);

        if (!$univer) {
            $this->redirectByAction('head_centers');
        }

        $hasChildren = $univer->isHaveChildren();

        return $this->render->view('universities/view', array('object' => $univer, 'haveChildren' => $hasChildren));

    }

    protected function university_child_view_action()
    {

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $univer = University::getByID($id);
        if (!$univer) {
            $this->redirectByAction('universities');
        }

        $hasChildren = $univer->isHaveChildren();

        return $this->render->view('universities/children/view', array('object' => $univer, 'haveChildren' => $hasChildren));

    }

    protected function university_children_action()
    {

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $univer = University::getByID($id);
        if (!$univer) {
            $this->redirectByAction('universities');
        }

        $children = $univer->getChildren();

        return $this->render->view('universities/children/list', array('object' => $univer, 'children' => $children));

    }

    protected function university_delete_action()
    {

        $univer = University::getByID(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));

        Session::setFlash(sprintf('Локальный центр <strong>%s</strong> удалён', $univer->name));
        $h_id=$univer->head_id;
        $univer->delete();
        $this->redirectByAction('universities', array('h_id' => $h_id));

    }

    protected function university_child_delete_action()
    {

        $univer = University::getByID(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));

        $parent_id = $univer->parent_id;
        Session::setFlash(sprintf('Подчинённый локальный центр <strong>%s</strong> удалён', $univer->name));
        $univer->delete();

        $this->redirectByAction('university_children', ['id' => $parent_id]);

    }

    protected function university_view_dogovor_action()
    {

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $univer = University::getByID($id);
        if (!$univer) {
            $this->redirectByAction('universities');
        }


        return $this->render->view('universities/view_dogovor', array('object' => $univer));

    }

    protected function university_dogovor_add_action()
    {

        $univer = new University_dogovor();
        $univer->date = date('d.m.Y');
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $univer->university_id = $id;
        if (count($_POST)) {
            $univer->parseParameters($_POST);
            //die(var_dump($univer));
            Session::setFlash('Договор добавлен');
            $univer->save();
            $this->redirectByAction('university_view_dogovor', array('id' => $id));
        }

        return $this->render->form('add', $univer, 'Добавление договора локального центра');
    }

    protected function university_dogovor_edit_action()
    {

        $univer = University_dogovor::getByID(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));
//var_dump($univer);
        if (count($_POST)) {
            //  echo '111';
            $univer->parseParameters($_POST);

//          print_r($_POST);
            $univer->save();
//            die(var_dump($univer));
            Session::setFlash('Данные договора сохранены');
            $this->redirectByAction('university_view_dogovor', array('id' => $univer->university_id));
        }

        return $this->render->form('add', $univer, 'Редактирование договора локального центра');
    }

    protected function university_dogovor_delete_action()
    {

        $univer = University_dogovor::getByID(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));


        $univer->delete();
        $this->redirectByAction('university_view_dogovor', array('id' => $univer->university_id));

    }

    protected function university_print_simple_action()
    {
        $universtity = University::getByID(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));
        if (!$universtity) {
            $this->redirectByAction('universities_action');

        }

        die($this->render->view(
            'universities/print/simple',
            array(
                'university' => $universtity,


            )
        ));
    }

    protected function university_print_full_action()
    {
        $universtity = University::getByID(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));
        if (!$universtity) {
            $this->redirectByAction('universities_action');

        }

        die($this->render->view(
            'universities/print/full',
            array(
                'university' => $universtity,


            )
        ));
    }

    protected function university_print_full_dogovor_action()
    {
        $universtity = University::getByID(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));
        if (!$universtity) {
            $this->redirectByAction('universities_action');

        }

        die($this->render->view(
            'universities/print/full_dogovor',
            array(
                'university' => $universtity,


            )
        ));
    }

    protected function university_user_right_action()
    {
        $univer = University::getByID(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));
        $Jobs = UserTypes::getForUniverRights();

        if (isset($_POST['users'])) {
            $univer->saveUsers($_POST['users']);
            $_SESSION['flash'] = 'Права пользователей установлены';
        }

//die(var_dump($univer->getAvailableUsers()));
        return $this->render->view(
            'universities/right',
            array(
                'object' => $univer,
                'users' => $univer->getAvailableUsers($univer->head_id),
                'jobs' => $Jobs
            )
        );
    }

    protected function user_list_action()
    {
        $users = Univesities::getUsers();

        return $this->render->view(
            'universities/user_list',
            array(

                'users' => $users
            )
        );
    }

    protected function user_list_edit_action()
    {
        $user_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        $user = User::getByID($user_id);

        if (isset($_POST['submit'])) {
            if (empty($_POST['univers'])) {
                $_POST['univers'] = array();
            }
            Univesities::saveUserRight($user_id, $_POST['univers']);
            $_SESSION['flash'] = 'Права пользователей установлены';
        }
        $univers = Univesities::getByUser($user_id);


        return $this->render->view(
            'universities/user_list_form',
            array(
                'userName' => $user->getFullName(),
                'univers' => $univers
            )
        );
    }

    protected function university_child_edit_action()
    {


        if (empty(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)) || !is_numeric(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT))) {
            $this->redirectReturn();
        }
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $univer = University::getByID($id);

        if (!$univer) {
            $this->redirectAccessRestricted();
        }

        if (!empty($_GET['do'])) {
            switch ($_GET['do']) {
                case 'change_pwd':
                    $univer->resetPassword();
                    $univer->save();
                    $params = array('id' => $univer->id);
                    if ($univer->user_password) {
                        $params['pwd'] = $univer->user_password;
                    }

                    Session::setFlash('<br>Новый пароль действует 6 дней до его смены пользователем!');
                    $this->redirectByAction('university_child_view', $params);
            }
        }


        if (count($_POST)) {
            $univer->parseParameters($_POST);
            $univer->save();
            $params = array('id' => $univer->id);
            if ($univer->user_password) {
                $params['pwd'] = $univer->user_password;
            }
            $this->redirectByAction('university_child_view', $params);
        }

        $buttons = array(
            array(
                'link' => 'index.php?action=university_edit&id=' . $univer->id . '&do=change_pwd',
                'caption' => 'Сбросить пароль'
            )
        );

        return $this->render->view(
            'universities/children/edit',
            array(
                'u' => $univer,
                'countries' => Countries::getAll4Form(),
                'caption' => 'Редактирование подчинённого локального центра',
                'regions' => Regions::getAll4Form(),
//                'userName' => $user->getFullName(),
//                'univers' => $univers
            )
        );

    }

    protected function university_child_add_action()
    {
        $parent_id = filter_input(INPUT_GET, 'parent_id', FILTER_VALIDATE_INT);

        $parent = University::getByID($parent_id);
        if (!$parent) {
            $this->redirectAccessRestricted();
        }
        $univer = new University();
        $univer->parent_id = $parent->id;


        if (count($_POST)) {
            $univer->parseParameters($_POST);
            $univer->head_id = $parent->head_id;
            $univer->save($parent->head_id);
            $params = array('id' => $univer->id);
            if ($univer->user_password) {
                $params['pwd'] = $univer->user_password;
                $_SESSION['flash'] = 'Новый пароль действует 6 дней до его смены пользователем!';
            }
            $this->redirectByAction('university_child_view', $params);
        }


        return $this->render->view(
            'universities/children/edit',
            array(
                'u' => $univer,
                'countries' => Countries::getAll4Form(),
                'caption' => 'Добавление подчинённого локального центра партнёра ' . $univer->name,
                'regions' => Regions::getAll4Form(),
//                'userName' => $user->getFullName(),
//                'univers' => $univers
            )
        );


    }

    protected function center_signing_delete_action()
    {
        $sign_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $type = filter_input(INPUT_GET, 'type');

        $sign = CenterSigning::getByID($sign_id);

        $center = University::getByID($sign->center_id);
        if (!$center || $type != $sign->type) {
            $this->redirectAccessRestricted();
        }

        $sign->delete();
        Session::setFlash('Подписывающий удалён');
        $this->redirectByAction('university_view', array('id' => $center->id));

    }


    protected function center_signing_add_action()
    {
        $center_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $type = filter_input(INPUT_GET, 'type');

        $center = University::getByID($center_id);
        if (!$center) {
            $this->redirectAccessRestricted();
        }

        $sign = new CenterSigning();

        if (!in_array($type, [$sign::TYPE_APPROVE, $sign::TYPE_RESPONSIVE])) {
            Session::setFlash('Неверный вид подписываюго');
            $this->redirectByAction('university_view', array('id' => $sign->id));
        }

        $sign->center_id = $center->id;
        $sign->type = $type;

        if (count($_POST)) {
            $sign->parseParameters($_POST);
            //die(var_dump($univer));

            $sign->save();
            Session::setFlash('Подписывающий добавлен');
            $this->redirectByAction('university_view', array('id' => $center->id));
        }

        $type_caption = '';
        switch ($type) {
            case $sign::TYPE_RESPONSIVE: {
                $type_caption = 'ответственного за тестирование';
                break;
            }
            case $sign::TYPE_APPROVE: {
                $type_caption = 'утверждающего акт';
                break;
            }
        }

        return $this->render->form('add', $sign, 'Добавление ' . $type_caption);

    }

    protected function center_signing_edit_action()
    {
        $sign_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        $sign = CenterSigning::getByID($sign_id);
        $center = University::getByID($sign->center_id);
        if (!$center) {
            $this->redirectAccessRestricted();
        }


        if (count($_POST)) {
            $sign->parseParameters($_POST);
            //die(var_dump($univer));

            $sign->save();
            Session::setFlash('Подписывающий сохранён');
            $this->redirectByAction('university_view', array('id' => $center->id));
        }

        $type_caption = '';
        switch ($sign->type) {
            case $sign::TYPE_RESPONSIVE: {
                $type_caption = 'ответственного за тестирование';
                break;
            }
            case $sign::TYPE_APPROVE: {
                $type_caption = 'утверждающего акт';
                break;
            }
        }

        return $this->render->form('add', $sign, 'Редактирование ' . $type_caption);

    }


}