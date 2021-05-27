<?php
require_once dirname(__FILE__) . '/../otchet.php';

class otchet_country extends otchet_search
{
//    protected

    public function __construct($params)
    {
        $this->sql = "SELECT count(sdt_act_people.id) AS cc
			, sdt_test_levels.caption AS level_caption
			FROM
			sdt_act_people
			INNER JOIN sdt_act_test
			ON sdt_act_people.test_id = sdt_act_test.id
			INNER JOIN sdt_test_levels
			ON sdt_act_test.level_id = sdt_test_levels.id
			INNER JOIN sdt_act
			ON sdt_act_test.act_id = sdt_act.id
			INNER JOIN sdt_university
				ON sdt_university.id = sdt_act.university_id
			WHERE
			sdt_act_people.country_id = '%COUNTRY%'
							   and
sdt_act.state in ('wait_payment','archive','received','check','print') 
			AND sdt_act_people.testing_date >= '%FROM%'
			AND sdt_act_people.testing_date <= '%TO%'
			AND sdt_act.deleted = 0
			AND sdt_act_people.deleted = 0
			AND sdt_act_test.deleted = 0
			and			   sdt_university.head_id = " . CURRENT_HEAD_CENTER . "
			GROUP BY
			sdt_test_levels.id";
        parent::__construct($params);
    }


    public function Search()
    {
        $result = parent::Search();
//		die(var_dump($this->sql));
        while ($row = mysql_fetch_assoc($result)) {

            $this[] = new otchet_country_item($row);
        }

        return true;
    }
}

class otchet_country_item extends otchet_item
{
    public $level_caption;
    public $cc;
    /*public  function __construct($params){

    parent::__construct($params);
    }*/
}
