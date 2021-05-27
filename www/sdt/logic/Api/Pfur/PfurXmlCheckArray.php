<?php
/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 06.10.2017
 * Time: 11:08
 */

class PfurXmlCheckArray
{
    private $checker;

    public function __construct()
    {
        $this->checker = new ReExamCheck();
    }

    public function checkArray(array $data)
    {
//        var_dump($data);

        $haveErrors = false;
        foreach ($data['tests'] as $t) {
//            var_dump($t);
            foreach ($t['retry'] as $n) {
                try {
                    $man = $this->checkNote($n, $t['fields']['level_id']);
                    if (!$man) continue;
                    $this->checkFreeNote($n, $man);
                } catch (ParseException $exception) {
                    $haveErrors = true;
                    Errors::add(Errors::XML, Errors::PARSE, $exception->getMessage());

                }
            }
        }
//        return false;
        return $haveErrors ? false : $data;
    }

    private function checkNote(array $retry, $level_id)
    {
        $man = ActMan::searchByNoteNum($retry['note']);
        if (!$man) {
            throw  new ParseException('Ќе найдена справка ' . $retry['note']);
        }
        if (!$this->checker->checkLevel($level_id, $man)) {
            throw  new ParseException('” справки ' . $retry['note'] . ' указан неверный уровень тестировани€');
        }

        if (!$this->checker->isRexamAvailable($man)) {
            throw  new ParseException('” справки ' . $retry['note'] . ' нарушен минимальный срок перед пересдачей');
        }


        return $man;
    }

    private function checkFreeNote(array $retry, ActMan $man)
    {
        if (!$retry['is_free']) return true;
        $newMan = clone $man;
        $newMan->is_retry = $newMan::RETRY_ALL;
        $reaxamFactory = new CreateReExam();
//
        $reexam = $reaxamFactory->create($man, $newMan);

        if (!$reexam->is_free) {
            throw  new ParseException('ѕересдача справки ' . $retry['note'] . ' не может быть бесплатной.');
        }
        return true;
    }
}