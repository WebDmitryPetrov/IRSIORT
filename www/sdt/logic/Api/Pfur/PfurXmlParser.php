<?php
/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 05.10.2017
 * Time: 11:36
 */

class PfurXmlParser
{
    use ConvertTrait;

    public function parseXml($xmlFileName)
    {

        libxml_use_internal_errors(true);


        if (!$this->validateSchema($xmlFileName)) {
            Errors::add(Errors::XML, Errors::PARSE, 'Неверная структура');
//            $errors = libxml_get_errors();
//            foreach ($errors as $error) {
//                /** @var libXMLError  $error */
//                print $error->message;
//            }
//            die;
//            return false;
        }

        $xml = simplexml_load_file($xmlFileName);
//        die(var_dump($xml));
        if (!$xml) {
            Errors::add(Errors::XML, Errors::PARSE, 'Неверная структура');
            return false;
        }
        echo '<pre>';
//        echo iconv('UTF-8', 'CP1251', print_r($xml, 1));

        $data = array(

            'fields' => array(),
            'tests' => array(),
        );

        $fields = &$data['fields'];
        $fields['university_id'] = $_SESSION['univer_id'];
        $contract_id = intval($xml->attributes()->contract);
        $currentLC = University::getByID(Session::getLocalCenterID());

        $contract = University_dogovor::getByID($contract_id);
        $dogovorUniverID = $currentLC->parent_id ? $currentLC->parent_id : $currentLC->id;
        if (!$contract || $contract->isDeleted() || $dogovorUniverID != $contract->university_id) {
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

//        $docTypesAvailable = $this->docTypesAvailable();

        foreach ($xml->tests->test as $test) {
            /** @var SimpleXMLElement $test */
            $at = array('fields' => array());
            $level_id = intval($test->attributes()->level);
            $at['fields']['level_id'] = $level_id;
            $at['fields']['people_first'] = 0;
            $at['fields']['people_retry'] = 0;
            $at['fields']['people_subtest_retry'] = 0;
            $at['fields']['people_subtest_2_retry'] = 0;
            $at['fields']['people_subtest_2_retry'] = 0;
            $at['fields']['people_subtest_all_retry'] = 0;

            $at['people'] = array();
            $at['retry'] = array();
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

//            die(var_dump($test->people));

            foreach ($test->people->man as $man) {
//                die(var_dump($man));
                $at['fields']['people_first']++;

                $am = $this->parseManXml($man, $Level, $Orders, $subTests);


//                die(var_dump($am));
                $at['people'][] = $am;
            }

            foreach ($test->people->retry as $man) {
//                die(var_dump($man));


                $am = $this->parseRetryXml($man, $Level, $Orders, $subTests);


                if ($am['subtest_retry'] == 1) {
                    $at['fields']['people_subtest_retry']++;
                } elseif ($am['subtest_retry'] == 2) {
                    $at['fields']['people_subtest_2_retry']++;
                } elseif ($am['is_free']) {
                    $at['fields']['people_subtest_all_retry']++;
                }
                $at['fields']['people_retry']++;
//                (var_dump($am));
//                $at['fields']['people_first']++;

                $at['retry'][] = $am;
            }


//            usort($at['people'], array($this, 'sortPeopleByRetry'));
            $tests[] = $at;
        }
        if (Errors::count()) {
            return false;
        }
//        print_r($data);die;
        return $data;
    }

    protected function validateSchema($file)
    {
        $xml = new DOMDocument();

        $xml->load($file);

        if (!$xml->schemaValidate($this->getSchemeAct())) {
            return false;
        }

        return true;

    }

    private function getSchemeAct()
    {
        return __DIR__ . '/../../../api/pfur_act.xsd';
    }

    /**
     * @param $man
     * @param $docTypesAvailable
     * @param $Level
     * @param $Orders
     * @param $subTests
     * @return array
     */
    private function parseManXml($man, $Level, $Orders, $subTests)
    {
        $docTypesAvailable = $this->docTypesAvailable();
        $am = array();

//                $isRetry = intval($man->attributes()->is_retry);
//                $isRetry=intval($man->attributes()->is_retry);


//                if ($isRetry) {
//                    $at['fields']['people_retry']++;
//                } else {

//                }
        $am['subtest_retry'] = 0;
//                if ($isRetry) {
//                    $retryCount = intval($man->attributes()->subtest_retry);
//                    $am['subtest_retry'] = $retryCount;
//                    if ($retryCount == 1) {
//                        $at['fields']['people_subtest_retry']++;
//                    } elseif ($retryCount == 2) {
//                        $at['fields']['people_subtest_2_retry']++;
//                    } else {
//                        Errors::add(Errors::XML, Errors::PARSE, 'Неверное количество пересдач, разрешенно 1 или 2');
//
//                        return false;
//                    }
//                }


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
        $am['ext_id'] = $this->getExtId($man);

        $am['api_doc_type'] = strval($man->doc_type);

        $this->checkRusLat($am);


        if (!in_array($am['api_doc_type'], $docTypesAvailable)) {
            Errors::add(
                Errors::XML,
                Errors::PARSE,
                'Для поля doc_type допустимые значения: ' . implode(
                    ', ',
                    $docTypesAvailable
                ) . '   у ' . $am['surname_rus'] . ' ' . $am['name_rus'] . ' указано ' . $am['api_doc_type']
            );
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

//                        return false;
            }
        }

//        if (!empty($man->certificate_register_number)) {
//            $am['document_nomer'] = $this->to1251($man->certificate_register_number);
//        }
//
//        if (!empty($man->certificate_blank_number)) {
//            $am['blank_number'] = $this->to1251($man->certificate_blank_number);
//        }
//        if (!empty($man->certificate_blank_date)) {
//            $am['blank_date'] = $this->to1251($man->certificate_blank_date);
//        }

        foreach ($man->subtests->subtest as $st) {
            $SubTestNum = intval($st->attributes()->num);

            if (!in_array($SubTestNum, $Orders)) {
                Errors::add(Errors::XML, Errors::PARSE, 'Указан лишний субтест');
            }


            $oSB = $subTests->getByOrder($SubTestNum);
            $manBall = intval($st);
            if ($oSB->max_ball < $manBall) {
                Errors::add(
                    Errors::XML,
                    Errors::PARSE,
                    'Баллы за субтест превышают максимальный у тестируемого ' . $am['surname_rus'] . ' ' . $am['name_rus'] . ' за субтест №' . $SubTestNum
                );

            }

            $am['test_' . $SubTestNum . '_ball'] = $manBall;
        }
        return $am;
    }

    /**
     * @return array
     */
    private function docTypesAvailable()
    {
        return array(ActMan::DOCUMENT_CERTIFICATE, ActMan::DOCUMENT_NOTE);
    }

    /**
     * @param $man
     * @param $docTypesAvailable
     * @param $Level
     * @param $Orders
     * @param $subTests
     * @return array
     */
    private function parseRetryXml($man, $Level, $Orders, $subTests)
    {
        $docTypesAvailable = $this->docTypesAvailable();
        $am = array();

//                $isRetry = intval($man->attributes()->is_retry);
//                $isRetry=intval($man->attributes()->is_retry);


//                if ($isRetry) {
//                    $at['fields']['people_retry']++;
//                } else {

//                }

        $retryCount = intval($man->attributes()->subtest_retry);
        $am['subtest_retry'] = $retryCount;
        $am['is_free'] = $isFree = intval($man->attributes()->is_free);
        $am['ext_id'] = $this->getExtId($man);

        $am['note'] = strval($this->to1251($man->attributes()->note));
//                    if ($retryCount == 1) {
//                        $at['fields']['people_subtest_retry']++;
//                    } elseif ($retryCount == 2) {
//                        $at['fields']['people_subtest_2_retry']++;
//                    }

        if (!$isFree && ($retryCount < 1 || $retryCount > 2)) {
            Errors::add(Errors::XML, Errors::PARSE, 'Неверное количество пересдач, разрешенно 1 или 2.');
        }
        $am['is_retry'] = true;

        if ($isFree) {
            $am['subtest_retry'] = ActMan::RETRY_ALL;
        }


        $am['testing_date'] = $this->mysql_date($this->to1251($man->testing_date));


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
        }


//
//        if (!empty($man->certificate_register_number)) {
//            $am['document_nomer'] = $this->to1251($man->certificate_register_number);
//        }
//
//        if (!empty($man->certificate_blank_number)) {
//            $am['blank_number'] = $this->to1251($man->certificate_blank_number);
//        }
//        if (!empty($man->certificate_blank_date)) {
//            $am['blank_date'] = $this->to1251($man->certificate_blank_date);
//        }

        foreach ($man->subtests->subtest as $st) {
            $SubTestNum = intval($st->attributes()->num);

            if (!in_array($SubTestNum, $Orders)) {
                Errors::add(Errors::XML, Errors::PARSE, 'Указан лишний субтест');
            }


            $oSB = $subTests->getByOrder($SubTestNum);
            $manBall = intval($st);
            if ($oSB->max_ball < $manBall) {
                Errors::add(
                    Errors::XML,
                    Errors::PARSE,
                    'Баллы за субтест превышают максимальный у тестируемого ' . $am['surname_rus'] . ' ' . $am['name_rus'] . ' за субтест №' . $SubTestNum
                );

            }

            $am['test_' . $SubTestNum . '_ball'] = $manBall;
        }
        return $am;
    }

    /**
     * @param $man
     * @return null|string
     */
    private function getExtId($man)
    {
        return $man->attributes()->ext_id ? strval($man->attributes()->ext_id) : null;
    }

    private function checkRusLat(array $am)
    {
//        $surname=preg_match('/[^а-яА-Я ]/', $am['surname_rus'],$matches);
//        var_dump($surname,$matches);
//        die();
        $rus = '/[^а-яА-ЯёЁ -]/';
        $lat = '/[^a-zA-Z -]/';
        if (preg_match($rus, $am['surname_rus'],$matches)) {
            Errors::add(
                Errors::XML,
                Errors::PARSE,
                $am['surname_rus'] . ' ' . $am['name_rus'] . ': в поле "Фамилия русская"  используются  недопустимые  символы.'
            );
        }
        if (preg_match($rus, $am['name_rus'],$matches)) {
            Errors::add(
                Errors::XML,
                Errors::PARSE,
                $am['surname_rus'] . ' ' . $am['name_rus'] . ': в поле "Имя русское"  используются  недопустимые  символы.'
            );
        }
        if (preg_match($lat, $am['surname_lat'],$matches)) {
            Errors::add(
                Errors::XML,
                Errors::PARSE,
                $am['surname_rus'] . ' ' . $am['name_rus'] . ': в поле "Фамилия латинская"  используются  недопустимые  символы.'
            );
        }
        if (preg_match($lat, $am['name_lat'],$matches)) {
//            var_dump($m);
            Errors::add(
                Errors::XML,
                Errors::PARSE,
                $am['surname_rus'] . ' ' . $am['name_rus'] . ': в поле "Имя латинское"  используются  недопустимые  символы.'
            );
        }
    }
}