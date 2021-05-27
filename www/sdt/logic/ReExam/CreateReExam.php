<?php
/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 21.09.2017
 * Time: 15:38
 */

class CreateReExam
{
    private $conn;

    public function __construct()
    {
        $this->conn = Connection::getInstance();

    }

    public function create(ActMan $oldMan, ActMan $newMan)
    {
        $re_exam = new Re_exam();

        $checker = new ReExamCheck();
        $re_exam->document_number = $oldMan->blank_number;
        $re_exam->document_type = $oldMan->document;
//                $re_exam->is_free=$man->document;
        $re_exam->first_man_id = $this->getFirstManID($oldMan);
        $re_exam->number = $this->calculateNumber($re_exam);

        $re_exam->is_free = $checker->canBeFree($re_exam, $newMan);

        $re_exam->man_id = $newMan->id;
        $re_exam->old_man_id = $oldMan->id;


        return $re_exam;
    }

    private function getFirstManID(ActMan $oldMan)
    {
        $sql = 'SELECT rei.first_man_id FROM re_exam_info rei WHERE rei.man_id = %d';
        $res = $this->conn->queryOne(sprintf($sql, intval($oldMan->id)));
        if ($res)
            return $res['first_man_id'];
        return $oldMan->id;
    }

    private function calculateNumber(Re_exam $re)
    {
        $sql = 'SELECT count(*) AS cc FROM re_exam_info WHERE  first_man_id = %d';

        $res = $this->conn->queryOne(sprintf($sql, $re->first_man_id));
        return intval($res['cc']) + 1;

    }


}