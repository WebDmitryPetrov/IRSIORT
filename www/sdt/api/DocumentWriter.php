<?php
/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 17.12.2014
 * Time: 10:30
 */
require_once 'AbstractDomWriter.php';

class DocumentWriter extends AbstractDomWriter
{

    protected $rootElementName = 'act';

    public function makeXml()
    {
        $act = $this->getRootElement();

        $act->setAttribute('contract', 168);
        $act->setAttribute('type', 2);
        $this->addChild($act, 'number', '15');
        $this->addChild($act, 'testing_date', '17.12.2014');
        $this->addChild($act, 'official', 'Проректор Сидоров И.П.');
        $this->addChild($act, 'responsible', 'Ложкин В.В.');
        $this->addChild($act, 'comment');
        $testors = $this->addChild($act, 'testors');
        $this->addChild($testors, 'testor', 'ФИО Тестора 1');
        $this->addChild($testors, 'testor', 'ФИО Тестора 2');

        $tests = $this->addChild($act, 'tests');

        $test = $this->addChild($tests, 'test');
        $test->setAttribute('level', 15);
        $this->addPeople($test);

        $test = $this->addChild($tests, 'test');
        $test->setAttribute('level', 13);
        $this->addPeople($test);


        return $this->xml->saveXML();
    }

    private function addPeople($test)
    {
        $people = $this->addChild($test, 'people');


        $man = $this->addChild($people, 'man');
        $man->setAttribute('is_retry', 0);
        $man->setAttribute('citizen', 13);

        $this->addChild($man, 'surname_rus', 'ГУРБАНОВА');
        $this->addChild($man, 'name_rus', 'ГАРАНФИЛ АЛЕКБЕР КЫЗЫ ');
        $this->addChild($man, 'surname_lat', 'GURBANOVA ');
        $this->addChild($man, 'name_lat', 'GARANFIL');
        $this->addChild($man, 'passport_name', 'паспорт');
        $this->addChild($man, 'passport_series', 'P ');
        $this->addChild($man, 'passport', ' 3907897');
        $this->addChild($man, 'passport_date', '16.11.2009');
        $this->addChild($man, 'passport_department', 'ОВД');
        $this->addChild($man, 'testing_date', '09.12.2014');
        $this->addChild($man, 'birth_date', '12.12.1986');
        $this->addChild($man, 'birth_place', 'Израиль');
        $this->addChild($man, 'migration_card_series', '122');
        $this->addChild($man, 'migration_card_number', '332233');

        $subTests = $this->addChild($man, 'subtests');
        $this->addChild($subTests, 'subtest', 50)->setAttribute('num', 1);
        $this->addChild($subTests, 'subtest', 40)->setAttribute('num', 2);
        $this->addChild($subTests, 'subtest', 35)->setAttribute('num', 3);
        $this->addChild($subTests, 'subtest', 59)->setAttribute('num', 4);
        $this->addChild($subTests, 'subtest', 79)->setAttribute('num', 5);
        $this->addChild($subTests, 'subtest', 90)->setAttribute('num', 6);
        $this->addChild($subTests, 'subtest', 55)->setAttribute('num', 7);

        //-------------------------------------
        $man = $this->addChild($people, 'man');
        $man->setAttribute('is_retry', 1);
        $man->setAttribute('subtest_retry', 1);
        $man->setAttribute('citizen', 14);

        $this->addChild($man, 'surname_rus', 'РАГИМОВ');
        $this->addChild($man, 'name_rus', ' ВУСАЛ ИЛГАР ОГЛЫ ');
        $this->addChild($man, 'surname_lat', 'RAHIMOV ');
        $this->addChild($man, 'name_lat', 'VUSAL');
        $this->addChild($man, 'passport_name', 'паспорт');
        $this->addChild($man, 'passport_series', 'P ');
        $this->addChild($man, 'passport', ' 3222041');
        $this->addChild($man, 'passport_date', '06.05.2008');
        $this->addChild($man, 'passport_department', '');
        $this->addChild($man, 'testing_date', '09.12.2014');
        $this->addChild($man, 'birth_date', '08.02.1988');
        $this->addChild($man, 'birth_place', 'Баку');
        $this->addChild($man, 'migration_card_series', '');
        $this->addChild($man, 'migration_card_number', '');

        $subTests = $this->addChild($man, 'subtests');
        $this->addChild($subTests, 'subtest', 50)->setAttribute('num', 1);
        $this->addChild($subTests, 'subtest', 40)->setAttribute('num', 2);
        $this->addChild($subTests, 'subtest', 35)->setAttribute('num', 3);
        $this->addChild($subTests, 'subtest', 59)->setAttribute('num', 4);
        $this->addChild($subTests, 'subtest', 79)->setAttribute('num', 5);
        $this->addChild($subTests, 'subtest', 90)->setAttribute('num', 6);
        $this->addChild($subTests, 'subtest', 55)->setAttribute('num', 7);

        //-------------------------------------
        $man = $this->addChild($people, 'man');
        $man->setAttribute('is_retry', 1);
        $man->setAttribute('subtest_retry', 2);
        $man->setAttribute('citizen', 13);

        $this->addChild($man, 'surname_rus', 'ПЕТРОСЯН');
        $this->addChild($man, 'name_rus', 'САТЕНИК АМБАРЦУМОВНА ');
        $this->addChild($man, 'surname_lat', 'PETROSYAN ');
        $this->addChild($man, 'name_lat', 'SATENIK');
        $this->addChild($man, 'passport_name', 'паспорт');
        $this->addChild($man, 'passport_series', 'AM ');
        $this->addChild($man, 'passport', ' 0275059');
        $this->addChild($man, 'passport_date', '21.04.2011');
        $this->addChild($man, 'passport_department', 'ГУВД');
        $this->addChild($man, 'testing_date', '09.12.2014');
        $this->addChild($man, 'birth_date', '21.07.1980');
        $this->addChild($man, 'birth_place', '');
        $this->addChild($man, 'migration_card_series', '234234');
        $this->addChild($man, 'migration_card_number', '2342342343');

        $subTests = $this->addChild($man, 'subtests');
        $this->addChild($subTests, 'subtest', 50)->setAttribute('num', 1);
        $this->addChild($subTests, 'subtest', 40)->setAttribute('num', 2);
        $this->addChild($subTests, 'subtest', 35)->setAttribute('num', 3);
        $this->addChild($subTests, 'subtest', 59)->setAttribute('num', 4);
        $this->addChild($subTests, 'subtest', 79)->setAttribute('num', 5);
        $this->addChild($subTests, 'subtest', 90)->setAttribute('num', 6);
        $this->addChild($subTests, 'subtest', 55)->setAttribute('num', 7);

        //-------------------------------------
    }
}