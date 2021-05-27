<?php
/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 06.10.2017
 * Time: 12:00
 */

class ReExamCheck
{
    const DAYS_BEFORE = 3; //количество дней для платной пересдачи
    const DAYS_BEFORE_FREE = 10; //количество дней для бесплатной пересдачи

    public function isRexamAvailable(ActMan $man)
    {

        $oldDate = new DateTime($man->testing_date);
        $now = new DateTime();
        return $now->diff($oldDate)->days >= self::DAYS_BEFORE && $now >= $oldDate;
    }

    public function canBeFree(Re_exam $re_exam, ActMan $newMan)
    {
        //return false;
        $oldDate = new DateTime($newMan->testing_date);
        $now = new DateTime();
        if ($now->diff($oldDate)->days < self::DAYS_BEFORE_FREE || $now < $oldDate) return false;

        if ($newMan->is_retry != $newMan::RETRY_ALL) return false;
        if ($re_exam->number != 1) return false;
        if (!in_array($newMan->getTest()->level_id, Reexam_config::getTestLevels())) return false;
        if (!in_array(CURRENT_HEAD_CENTER, Reexam_config::getHeadCenters())) return false;
        return true;
    }

    public function checkLevel($level_id, ActMan $man)
    {
        return $level_id == $man->getTest()->level_id;
    }
}