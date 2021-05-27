<?php

/**
 * Created by PhpStorm.
 * User: LWR
 * Date: 19.09.2017
 * Time: 10:54
 */
trait ReExamTrait
{


    private function isRexamAvailable(ActMan $man)
    {

        $oldDate = new DateTime($man->testing_date);
        $now = new DateTime();
        return $now->diff($oldDate)->days >= 3 && $now >= $oldDate;
    }

    /**
     * Вставляет в БД и возращает для JS данные по дубликату
     */
    protected function paste_old_note_action()
    {


//        $certificate = filter_input(INPUT_POST, 'num', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        $old_man_id = filter_input(INPUT_POST, 'old_man_id', FILTER_VALIDATE_INT);
        $new_man_id = filter_input(INPUT_POST, 'new_man_id', FILTER_VALIDATE_INT);

        /*        $certificate = $this->utf_decode($certificate);


                $result = ActMan::searchByNoteNum($certificate);*/


        $result = ActMan::getByID($old_man_id);
        if (empty($result)) {
            die(json_encode(
                array(
                    'error' => 'not_found',

                )
            ));
        }

        if (!$this->isRexamAvailable($result)) {
            die(json_encode(
                array(
                    'error' => 'too_fast',

                )
            ));
        }

//            $man = CertificateDuplicate::checkForDuplicates($result);
        $man = $result;


//            if ($man->getBlank_number() == $certificate) {
//            if (1==1) {
        $return = array();


        $newman = ActMan::getByID($new_man_id);

        $return['surname_rus'] = $man->getSurname_rus();
        $return['surname_rus'] = $man->getSurname_rus();
        $return['surname_lat'] = $man->getSurname_lat();
        $return['name_rus'] = $man->getName_rus();
        $return['name_lat'] = $man->getName_lat();

        $return['country_id'] = $man->country_id;

//            $return['testing_date']=$this->date($man->testing_date);

        $return['passport_name'] = $man->passport_name;
        $return['passport_series'] = $man->passport_series;
        $return['passport'] = $man->passport;
        $return['passport_date'] = $man->passport_date;
        $return['passport_department'] = $man->passport_department;

        $return['birth_place'] = $man->birth_place;
        $return['birth_date'] = $man->birth_date;

        $return['migration_card_number'] = $man->migration_card_number;
        $return['migration_card_series'] = $man->migration_card_series;


        $newman->parseParameters($return);
        $man->getResults()->expose($newman);
        $newman->calculate();
        $newman->passport_file = File::duplicateFile($man->getIfDuplicatedFilePassport());

        $newman->save();


        $createRexam = new CreateReExam();
        $reexam_man = $createRexam->create($man,$newman);
        $reexam_man->save();

        $act = $newman->getAct();
        $act->recountMoney();
        $act->save();


        $return = array();
//FIXME Переделать на мульти энкодинг
        $return['surname_rus'] = $this->encode($man->getSurname_rus());
        $return['surname_lat'] = $this->encode($man->getSurname_lat());
        $return['name_rus'] = $this->encode($man->getName_rus());
        $return['name_lat'] = $this->encode($man->getName_lat());

        $return['country_id'] = $man->country_id;


        $return['passport_name'] = $this->encode($man->passport_name);
        $return['passport_series'] = $this->encode($man->passport_series);
        $return['passport'] = $this->encode($man->passport);
        $return['passport_date'] = $this->date($man->passport_date);
        $return['passport_department'] = $this->encode($man->passport_department);

        $return['birth_place'] = $this->encode($man->birth_place);
        $return['birth_date'] = $this->date($man->birth_date);

        $return['migration_card_number'] = $this->encode($man->migration_card_number);
        $return['migration_card_series'] = $this->encode($man->migration_card_series);

        $return['is_free'] = $reexam_man->is_free;

        if (!empty($newman->passport_file))

            $return['passport_file'] = $newman->getFilePassport()->getDownloadUrl();
        else
            $return['passport_file'] = null;

        $return['test_results']=array();
        foreach ($man->getResults() as $item)
        {
            $index = 'test_' . $item->order . '_ball';
            $value = $item->balls;
            $return['test_results'][]=array($index,$value);
        }


        /**/


        die(json_encode(
            array(
                'result' => $return,
                'error' => 'ok',
            )
        ));

    }


    protected function check_old_note_action()
    {


        $certificate = filter_input(INPUT_POST, 'num', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        $certificate = $this->utf_decode($certificate);
        $new_man_id = filter_input(INPUT_POST, 'new_man_id', FILTER_VALIDATE_INT);
        $level_id = filter_input(INPUT_POST, 'level_id', FILTER_VALIDATE_INT);
        $is_free = filter_input(INPUT_POST, 'is_free', FILTER_VALIDATE_INT);
         $result = ActMan::searchByNoteNum($certificate);

        if (empty($result)) {
            die(json_encode(
                array(
                    'error' => 'not_found',

                )
            ));
        }

        if (!$this->isRexamAvailable($result)) {
            die(json_encode(
                array(
                    'error' => 'too_fast',

                )
            ));
        }

        $createRexam = new CreateReExam();
        $reexam_man = $createRexam->create($result,clone $result);

        if($reexam_man->number > 1 and $is_free){
            die(json_encode(
                array(
                    'error' => 'number_more_1',
                    'number'=>$reexam_man->number,

                )
            ));
        }

//            $man = CertificateDuplicate::checkForDuplicates($result);
        $man = $result;


        $testlevel = $man->getLevel();
        $testlevel_id = $man->getLevel()->id;
//            $available_levels = ActMan::getByID($new_man_id)->getLevel()->getAvailableLevels();
        $available_levels = Reexam_config::getTestLevels();


//die(var_dump($testlevel_id, $level_id));
        //if (!count($available_levels) || !in_array($testlevel_id, $available_levels)) {
        if ($testlevel_id != $level_id) {
            die(json_encode(
                array(
                    //'result' => $return,
                    'error' => 'denied_level',
                )
            ));
        }


//            if ($man->getBlank_number() == $certificate) {
        if (1 == 1) {
            $return = array();


            $return['surname_rus'] = $this->encode($man->getSurname_rus());
            $return['surname_lat'] = $this->encode($man->getSurname_lat());
            $return['name_rus'] = $this->encode($man->getName_rus());
            $return['name_lat'] = $this->encode($man->getName_lat());
            $return['old_man_id'] = $man->id;
            /*
                        $return['country_id']=$man->country_id;



                        $return['passport_name']=$this->encode ($man->passport_name);
                        $return['passport_series']=$this->encode ($man->passport_series);
                        $return['passport']=$this->encode ($man->passport);
                        $return['passport_date']=$this->date($man->passport_date);
                        $return['passport_department']=$this->encode ($man->passport_department);

                        $return['birth_place']=$this->encode ($man->birth_place);
                        $return['birth_date']=$this->date($man->birth_date);

                        $return['migration_card_number']=$this->encode ($man->migration_card_number);
                        $return['migration_card_series']=$this->encode ($man->migration_card_series);

            */

            die(json_encode(
                array(
                    'result' => $return,
                    'error' => 'ok',
                )
            ));

        }


    }


    protected function clean_man_note_action()
    {

        $new_man_id = filter_input(INPUT_POST, 'new_man_id', FILTER_VALIDATE_INT);


        $old_man_additional_exam = Re_exam::getByManID($new_man_id);
        $old_man_main_exam = ActMan::getByID($new_man_id);

        if (empty($old_man_additional_exam) || empty($old_man_main_exam)) {
            die(json_encode(
                array(
                    'error' => 'not found',
                )
            ));
        }



        $old_man = $old_man_additional_exam;

        $old_man->delete();


        $old_man_main_exam = ActMan::getByID($new_man_id);


        $old_man = $old_man_main_exam;
        $new_man = new ActMan();
        $new_man->id = $old_man->id;
        $new_man->act_id = $old_man->act_id;
        $new_man->test_id = $old_man->test_id;
        $new_man->is_retry = $old_man->is_retry;
        $new_man->testing_date = $old_man->getAct()->testing_date;
        $new_man->save();

        $act = $new_man->getAct();
        $act->recountMoney();
        $act->save();


        die(json_encode(
            array(
                'error' => 'ok',
            )
        ));


    }


}