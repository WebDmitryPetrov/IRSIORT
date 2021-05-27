<?php

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 26.01.2017
 * Time: 15:54
 * @property Render render
 */
trait ReportTrait
{

    protected function report_search_act_action()
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
                $params['org_ids'] = $partnersList[$params['organization']]['ids'];
            }
            $result = Acts::Search($params);
        }


        return $this->render->view(
            'report/search_act',
            array(
                'Result' => $result,
                'query' => $params,
                'Universities' => $partnersList,
                'Levels' => TestLevels::getAll(),

            )
        );
    }



}