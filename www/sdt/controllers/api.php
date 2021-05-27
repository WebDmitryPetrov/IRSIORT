<?php
/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 12.01.2015
 * Time: 10:34
 */
require_once 'AbstractController.php';

class Api extends AbstractController
{

    private static $instance;
    static $scheme_act;

    static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    protected function __construct()
    {

        parent::__construct();
        self::$scheme_act = dirname(__FILE__) . '/../api/act.xsd';

    }


    protected function dics_action()
    {
        return $this->render->view('api/dics');
    }

    protected function xml_countries_action()
    {
        header("Content-Type: text/xml");
        require_once dirname(__FILE__) . '/../api/CountryWriter.php';
//        $pdo = new PDO('mysql:host=localhost;dbname=migrants_multi','root','',array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
//        $stmt=$pdo->query('select * from country');
//
//        $array=$stmt->fetchAll(PDO::FETCH_ASSOC);
        mysql_query('SET NAMES utf8');
        $res = mysql_query('select * from country order by id');
        $array = array();
        while ($row = mysql_fetch_assoc($res)) {
            $array[] = $row;
        }
        header("Content-Type: application/xml; charset=utf-8");
        $wr = new CountryWriter($array);
        echo $wr->makeXml();
        die;
    }

    protected function xml_contracts_action()
    {
        header("Content-Type: text/xml");
        require_once dirname(__FILE__) . '/../api/DogovorWriter.php';
//        $pdo = new PDO('mysql:host=localhost;dbname=migrants_multi','root','',array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
//        $stmt=$pdo->query('select * from country');
//
//        $array=$stmt->fetchAll(PDO::FETCH_ASSOC);
        mysql_query('SET NAMES utf8');
        $res = mysql_query(
            'select * from sdt_university_dogovor where deleted = 0 and university_id = ' . $_SESSION['univer_id']
        );
        $array = array();
        while ($row = mysql_fetch_assoc($res)) {
            $array[] = $row;
        }
        header("Content-Type: application/xml; charset=utf-8");
        $wr = new DogovorWriter($array);
        echo $wr->makeXml();
        die;
    }


    protected function validateSchema($file)
    {
        $xml = new DOMDocument();

        $xml->load($file);

        if (!$xml->schemaValidate(self::$scheme_act)) {
            return false;
        }

        return true;

    }

    protected function xml_testlevels_action()
    {
        header("Content-Type: text/xml");
        require_once dirname(__FILE__) . '/../api/TestLevelWriter.php';
//        $pdo = new PDO('mysql:host=localhost;dbname=migrants_multi','root','',array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
//        $stmt=$pdo->query('select * from country');
//
//        $array=$stmt->fetchAll(PDO::FETCH_ASSOC);
        mysql_query('SET NAMES utf8');


        $ids = array(-1);
        $res = mysql_query('select * from test_level_type where deleted = 0 ');
        $types = array();
        while ($row = mysql_fetch_assoc($res)) {
            $types[] = $row;
            $ids[] = $row['id'];
        }

        $res = mysql_query(
            'select * from sdt_test_levels where deleted = 0 and is_publicated = 1 and  type_id in (' . implode(', ', $ids) . ') order by id'
        );
        $levels = array();
        while ($row = mysql_fetch_assoc($res)) {
            $levels[] = $row;
        }

        header("Content-Type: application/xml; charset=utf-8");
        $wr = new TestLevelWriter($levels, $types);
        echo $wr->makeXml();
        die;
    }

    protected function xml_upload_action()
    {

        if (!empty($_FILES['file'])) {
            $file = $_FILES['file'];
            if ($id = $this->parseXMLFile($file)) {
                $_SESSION['flash'] = 'Тестовая сессия загружена';
                $this->redirectByAction('act_fs_view', array('id' => $id));
            }
        }

        return $this->render->view('api/upload');
    }

    private function parseXMLFile($file)
    {

        switch ($file['error']) {
            case UPLOAD_ERR_OK:
                break;
            default:
                Errors::add(Errors::XML, Errors::UPLOAD, $file['error']);

                return false;
        }
//var_dump($file);


        libxml_use_internal_errors(true);
        $xmlFileName = $file['tmp_name'];

        if (!$this->validateSchema($xmlFileName)) {
            Errors::add(Errors::XML, Errors::PARSE, 'Неверная структура');

            return false;
        }

        $xml = simplexml_load_file($xmlFileName);
        if (!$xml) {
            Errors::add(Errors::XML, Errors::PARSE, 'Неверная структура');

            return false;
        }
        echo '<pre>';
//        echo iconv('UTF-8', 'CP1251', print_r($xml, 1));

        $peoplesCount = count($xml->xpath('//man'));
//die(var_dump($peoplesCount));


//       if($peoplesCount>100){
//            Errors::add(Errors::XML, Errors::PARSE, 'Превышено допустимое количество участников в тестовой сессии!');
//
//            return false;
//        }
        if ($peoplesCount < 20 || $peoplesCount > 100) {
            Errors::add(Errors::XML, Errors::PARSE, 'Количество участников в тестовой сессии должно быть от 20 до 100!');

            return false;
        }


        $data = array(

            'fields' => array(),
            'tests' => array(),
        );
        $fields = &$data['fields'];
        $fields['university_id'] = $_SESSION['univer_id'];
        $contract_id = intval($xml->attributes()->contract);

        $contract = University_dogovor::getByID($contract_id);
        if (!$contract || $contract->isDeleted() || $_SESSION['univer_id'] != $contract->university_id) {
            Errors::add(Errors::XML, Errors::PARSE, 'Указан неверный договор');

            return false;
        }

        $fields['university_dogovor_id'] = $contract_id;
        $testTypeID = intval($xml->attributes()->type);
        $type = TestLevelType::getByID($testTypeID);
        if (!$type || $type->isDeleted()) {
            Errors::add(Errors::XML, Errors::PARSE, 'Указан неверный тип тестирования');

            return false;
        }

        $TestLevels = $type->getTestLevels();
        $availableTestLevels = array();
        foreach ($TestLevels as $level) {
            $availableTestLevels[] = $level->id;
        }
        $xmlLevels = $xml->xpath('//test/@level');
//        $foundLevels = array();
        foreach ($xmlLevels as $l) {
            if (!in_array(intval($l), $availableTestLevels)) {
                Errors::add(Errors::XML, Errors::PARSE, 'Указан недопустимый уровень  тестирования');

                return false;
            }
        }

        $countriesID = Countries::getAllID();

        $xmlCountries = $xml->xpath('//man/@citizen');
//die(var_dump($xmlCountries,$countriesID));
        foreach ($xmlCountries as $l) {
            if (!in_array(intval($l), $countriesID)) {
                Errors::add(Errors::XML, Errors::PARSE, 'Указана недопустимая страна для гражданства');

                return false;
            }
        }
        $fields['test_level_type_id'] = $testTypeID;


        $fields['number'] = $this->to1251($xml->number);
        $fields['testing_date'] = $this->mysql_date($this->to1251($xml->testing_date));
        $fields['official'] = $this->to1251($xml->official);
        $fields['responsible'] = $this->to1251($xml->responsible);
        $fields['comment'] = $this->to1251($xml->comment);
//        $fields['testors'] = array();
        $fields['tester1'] = $this->to1251($xml->testors->testor[0]);
        $fields['tester2'] = $this->to1251($xml->testors->testor[1]);

        $tests = &$data['tests'];

        $docTypesAvailable = array(ActMan::DOCUMENT_CERTIFICATE, ActMan::DOCUMENT_NOTE);

        foreach ($xml->tests->test as $test) {
            /** @var SimpleXMLElement $test */
            $at = array('fields' => array());
            $level_id = intval($test->attributes()->level);
            $at['fields']['level_id'] = $level_id;
            $at['fields']['people_first'] = 0;
            $at['fields']['people_retry'] = 0;
            $at['fields']['people_subtest_retry'] = 0;
            $at['fields']['people_subtest_2_retry'] = 0;
            $at['people'] = array();
            $peopleCount = count($test->people->man);

            $Level = $TestLevels[$level_id];
            $Orders = array();
            $subTests = $Level->getSubTests();
            foreach ($subTests as $subTest) {
                $Orders[] = $subTest->order;
                $xpathQ = 'people/man/subtests/subtest[@num=' . $subTest->order . ']';
                if (count($test->xpath($xpathQ)) != $peopleCount) {
                    Errors::add(Errors::XML, Errors::PARSE, 'Пропущен субтест');

                    return false;
                }

            }


            foreach ($test->people->man as $man) {
                $am = array();

                $isRetry = intval($man->attributes()->is_retry);
//                $isRetry=intval($man->attributes()->is_retry);


                if ($isRetry) {
                    $at['fields']['people_retry']++;
                } else {
                    $at['fields']['people_first']++;
                }
                $am['subtest_retry'] = 0;
                if ($isRetry) {
                    $retryCount = intval($man->attributes()->subtest_retry);
                    $am['subtest_retry'] = $retryCount;
                    if ($retryCount == 1) {
                        $at['fields']['people_subtest_retry']++;
                    } elseif ($retryCount == 2) {
                        $at['fields']['people_subtest_2_retry']++;
                    } else {
                        Errors::add(Errors::XML, Errors::PARSE, 'Неверное количество пересдач, разрешенно 1 или 2');

                        return false;
                    }
                }


                $am['country_id'] = intval($man->attributes()->citizen);
                $am['is_retry'] = $am['subtest_retry'];
                $am['surname_rus'] = $this->to1251($man->surname_rus);
                $am['name_rus'] = $this->to1251($man->name_rus);
                $am['surname_lat'] = $this->to1251($man->surname_lat);
                $am['name_lat'] = $this->to1251($man->name_lat);
                $am['passport_name'] = $this->to1251($man->passport_name);
                $am['passport_series'] = $this->to1251($man->passport_series);
                $am['passport'] = $this->to1251($man->passport);
                $am['passport_date'] = $this->mysql_date($this->to1251($man->passport_date));
                $am['passport_department'] = $this->to1251($man->passport_department);
                $am['testing_date'] = $this->mysql_date($this->to1251($man->testing_date));
                $am['birth_date'] = $this->mysql_date($this->to1251($man->birth_date));
                $am['birth_place'] = $this->to1251($man->birth_place);

                $am['migration_card_series'] = $this->to1251($man->migration_card_series);

                $am['migration_card_number'] = $this->to1251($man->migration_card_number);

                $am['api_doc_type'] = strval($man->doc_type);


                if (!in_array($am['api_doc_type'], $docTypesAvailable)) {
                    Errors::add(
                        Errors::XML,
                        Errors::PARSE,
                        'Для поля doc_type допустимые значения: ' . implode(
                            ', ',
                            $docTypesAvailable
                        ) . '   у ' . $am['surname_rus'] . ' ' . $am['name_rus'] . ' указано ' . $am['api_doc_type']
                    );

                    return false;
                }


                if ($Level->is_additional) {
                    if (!empty($man->certificate_old_blank_number)) {
                        $am['old_cert'] = $this->to1251($man->certificate_old_blank_number);
                    } else {
                        Errors::add(
                            Errors::XML,
                            Errors::PARSE,
                            'Не указан предыдущий номер сертификата у ' . $am['surname_rus'] . ' ' . $am['name_rus']
                        );

                        return false;
                    }
                }
                if (!empty($man->certificate_register_number)) {
                    $am['document_nomer'] = $this->to1251($man->certificate_register_number);
                }

                if (!empty($man->certificate_blank_number)) {
                    $am['blank_number'] = $this->to1251($man->certificate_blank_number);
                }
                if (!empty($man->certificate_blank_date)) {
                    $am['blank_date'] = $this->to1251($man->certificate_blank_date);
                }

                foreach ($man->subtests->subtest as $st) {
                    $SubTestNum = intval($st->attributes()->num);
                    if (!in_array($SubTestNum, $Orders)) {
                        Errors::add(Errors::XML, Errors::PARSE, 'Указан лишний субтест');

                        return false;
                    }


                    $oSB = $subTests->getByOrder($SubTestNum);
                    $manBall = intval($st);
                    if ($oSB->max_ball < $manBall) {
                        Errors::add(
                            Errors::XML,
                            Errors::PARSE,
                            'Баллы за субтест превышают максимальный у тестируемого ' . $am['surname_rus'] . ' ' . $am['name_rus'] . ' за субтест №' . $SubTestNum
                        );

                        return false;
                    }

                    $am['test_' . $SubTestNum . '_ball'] = $manBall;

                }


//			echo '<pre>';


                $at['people'][] = $am;
            }

//            usort($at['people'], array($this, 'sortPeopleByRetry'));
            $tests[] = $at;
        }

//        echo '<pre>';
//        print_r($data); die;
        return $this->saveArrayFromXml($data);


    }

    public function sortPeopleByRetry($a, $b)
    {
        if ($a['is_retry'] == $b['is_retry']) {
            return 0;
        }

        return ($a['is_retry'] < $b['is_retry']) ? -1 : 1;

    }

    protected function saveArrayFromXml($array)
    {
        $act = new Act($array['fields']);
        $act->save();

        foreach ($array['tests'] as $tA) {
            $test = new ActTest($tA['fields']);
            $test->act_id = $act->id;
            $test->save();
            $is_additional = $test->getLevel()->is_additional;
            $people = $test->getPeople();
            $pa = $tA['people'];
//var_dump($pa);die;
            foreach ($people as $man) {
                /** @var ActMan $man */
                $isRetry = $man->is_retry;

                $saved = false;
                foreach ($pa as $pk => $pm) {
//                    var_dump($pm['is_retry'], $isRetry);
                    if (
                        (!$isRetry && !$pm['is_retry'])
                        ||
                        (
                            $isRetry == $pm['subtest_retry'] && $pm['is_retry'])
                    ) {


                        $man->parseParameters($pm);
//                        $man->test_id=$test->id;
//                        $man->act_id=$act->id;
                        if ($this->userHasRole(Roles::ROLE_CENTER_EXTERNAL_API)) {
                            $man->setValidTill();
                        }
                        $man->save();

                        if ($is_additional) {
                            $newAE = new ManAdditionalExam();
                            $newAE->man_id = $man->id;
                            $newAE->old_blank_number = !empty($pm['old_cert']) ? $pm['old_cert'] : null;
                            $newAE->save();
                        }
                        $aud = new ApiUserData();
                        $aud->man_id = $man->id;
                        $aud->doc_type = $pm['api_doc_type'];
                        $aud->save();

                        $saved = true;
                        unset($pa[$pk]);
                        break;
                    }
                }

                if (!$saved) {
                    Errors::add(Errors::XML, Errors::PARSE, 'Сбой при сохранении. Обратитесь к разработчику');
                    $act->delete();

                    return false;
                }
            }
            $test->save();
        }

        $act->save();

        return $act->id;

    }


    public function api_act_to_head_action()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (empty($id)) {
            $this->redirectAccessRestricted();
        }

        $act = Act::getByID($id);
        if (empty($act)) {
            $this->redirectAccessRestricted();
        }

        $act->setState(Act::STATE_RECEIVED);
        $act->save();
        $_SESSION['flash'] = 'Документ отправлен в головной центр';
        $this->redirectByAction('act_fs_list');
    }

    protected function act_api_finished_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $act = Act::getByID($_GET['id']);
        try {
            $act->setStateApiFinished();
            $act->save();
            writeStatistic(
                'sdt',
                'act_finished',
                array(
                    'id' => $act->id,
                    'univer_id' => $act->getUniversity()->id,
                    'univer_name' => $act->getUniversity()->name,
                )
            );
            $_SESSION['flash'] = 'Тестовая сессия отправлена в головной центр';
        } catch (ApiException $ex) {
            $_SESSION['flash'] = $ex->getMessage();
            writeStatistic(
                'sdt',
                'act_finished_error',
                array(
                    'id' => $act->id,
                    'univer_id' => $act->getUniversity()->id,
                    'univer_name' => $act->getUniversity()->name,
                    'error' => $ex->getMessage(),
                )
            );
        }

        $this->redirectReturn();
    }

}
