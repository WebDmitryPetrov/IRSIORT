<?php
require_once 'SortByParentTrait.php';
/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 30.01.2017
 * Time: 17:39
 * @property Render render
 */
trait DublArchiveTrait
{
    use SortByParentTrait;

    protected function dubl_lc_archive_action()
    {
        $acts = Univesities::getBy4Archive();
        $acts = $this->universitySortByParent($acts);


        $acts = Univesities::getByDubl(array(DublAct::STATE_PROCESSED), null,false);

        $acts= $this->universitySortByParent($acts);

        return $this->render->view(
            'dubl/archive_lc_list',
            array(
                'list' => $acts,
                'caption' => 'Архив дубликатов',
//                'type' => $level_type,

            )
        );


    }

    protected function dubl_archive_list_action()
    {
        if (!($uid = filter_input(INPUT_GET, 'uid', FILTER_VALIDATE_INT))) {
            $_SESSION['flash'] = 'Локальный центр не указан';
            $this->redirectReturn();
        }
        $univer = University::getByID($uid);

        $acts = DublActList::getByLevel($uid,
            array(DublAct::STATE_PROCESSED));

        $signingsInvoice = ActSignings::get4Invoice();

        return $this->render->view(
            'dubl/archive_sess_list',
            array(
                'univer' => $univer,
                'list' => $acts,
                'signingsInvoice' => $signingsInvoice,
                'uid' => $uid,

            )
        );
    }

    public function dubl_act_table_archive_action()
    {
        $dubl = $this->getDublAct();
        $people = $dubl->getPeople();
        return $this->render->view('dubl/archive_act_table', array(
            'dubl' => $dubl,
            'people' => $people,
        ));
    }

    protected function dubl_act_numbers_archive_action()
    {
        $act = $dubl = $this->getDublAct();
        if (!$act
            || $act->isDeleted()

        ) {
            $this->redirectAccessRestricted();
        }
//die('sdfs');

        $this->isDeletedRedirect($act);
//        $this->checkActIsEditable($act);
//        $act->incrementViewedAndSave();


        $type = '';

//var_dump($acts);



            return $this->render->view(
                'dubl/archive_form_numbers',
                array(
                    'Act' => $act,
                    'type' => $type,
                )
            );


    }

}