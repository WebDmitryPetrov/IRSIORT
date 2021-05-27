<?php

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 30.01.2017
 * Time: 17:39
 * @property Render render
 */
trait ArchiveTrait
{

    protected function act_universities_archive_action()
    {
        $acts = Univesities::getBy4Archive();
        $acts = $this->universitySortByParent($acts);
        return $this->render->view(
            'acts/archive/universities_list',
            array(
                'list' => $acts,

            )
        );
    }

    protected function act_archive_list_action()
    {
        if (!($uid = filter_input(INPUT_GET, 'uid', FILTER_VALIDATE_INT))) {
            $_SESSION['flash'] = '¬уз не указан';
            $this->redirectReturn();
        }
        $univer = University::getByID($uid);
        $acts = Acts::getByLevel($uid, ACT::STATE_ARCHIVE);

        if ($notpayd = filter_input(INPUT_GET, 'notpayd')) {
            $acts = array_filter($acts->getArrayCopy(), function (Act $act) {
                return !$act->isPaid();
            });
        }
        $signingsInvoice = ActSignings::get4Invoice();

        return $this->render->view(
            'acts/archive/list',
            array(
                'univer' => $univer,
                'list' => $acts,
                'signingsInvoice' => $signingsInvoice,
                'uid' => $uid,
                'onlyNoPayd' => $notpayd,
            )
        );
    }

    protected function act_archive_view_action()
    {
        $act = Act::getByID($_GET['id']);
        if (!$act
            || $act->isDeleted()

        ) {
            $this->redirectAccessRestricted();
        }

        return $this->render->view(
            'acts/archive/view',
            array(
                'object' => $act,

            )
        );
    }

    protected function act_archive_table_view_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $act = Act::getByID($_GET['id']);
        if (!$act
            || $act->isDeleted()

        ) {
            $this->redirectAccessRestricted();
        }

        return $this->render->view(
            'acts/archive/table_view',
            array(
                'Act' => $act,
            )
        );
    }

    protected function act_archive_numbers_action()
    {
        $act = Act::getByID($_GET['id']);

        if (!$act
            || $act->isDeleted()

        ) {
            $this->redirectAccessRestricted();
        }

        return $this->render->view(
            'acts/archive/form_numbers',
            array(
                'Act' => $act,

            )
        );


    }
}