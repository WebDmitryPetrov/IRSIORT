<?php

/**
 * Class SubTestResults
 * @deprecated
 */
class SubTestResults extends ArrayObject
{
    public static function getByMan(ActMan $man)
    {
        $result = new self();
        $level = $man->getLevel();
        for ($i = 1; $i <= $level->subtest_count; $i++) {
            $prefix = 'test_' . $i . '_';
            $percent = $prefix . 'percent';
            $ball = $prefix . 'ball';


            $subtestResult = new SubTestResult();
            $subtestResult->percent = $man->$percent;
            $subtestResult->balls = $man->$ball;
            $subtestResult->order = $i;
            $subtestResult->subtest = $level->getSubTests()->getByOrder($i);
            $result->append($subtestResult);
        }


        return $result;

    }

    /**
     * @param $order
     * @return null|SubTestResult
     */
    public function getByOrder($order)
    {
        foreach ($this as $res) {
            if ($res->order == $order) {
                return $res;
            }
        }

        return null;
    }

    public function expose(ActMan $man)
    {
//        die(var_dump($this));
        foreach ($this->getArrayCopy() as $item) {
            /** @var SubTestResult $item */

            $prefix = 'test_' . $item->order . '_';
            $percent = $prefix . 'percent';
            $ball = $prefix . 'ball';


            $man->$percent = $item->percent;
            $man->$ball = $item->balls;

        }
    }

}

class SubTestResult
{
    public $percent;
    public $balls;
    public $order;
    /** @var  SubTest */
    public $subtest;

}