<?php

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 05.03.2015
 * Time: 11:31
 */
class Prices
{

    public $firstTime;
    public $subtest1;
    public $subtest2;

    static function calcPrices(Act $act, TestLevel $level)
    {
        $result = new self();

        $foundPrices = false;

        $meta = $act->getMeta();

        if ($meta->special_price_group) {
            $sp = SpecialPrice::getByGroupAndLevel($meta->special_price_group, $level->id);
            if ($sp) {
                $foundPrices = true;
                $result->firstTime = $sp->price_first_time;
                $result->subtest1 = $sp->price_subtest_1;
                $result->subtest2 = $sp->price_subtest_2;




//новое
                if ($act->getUniversity()->parent_id)
                {
                    $university_id=$act->getUniversity()->parent_id;
                }
                else
                {
                    $university_id=Act::getByID($meta->act_id)->university_id;
                }


                $spU = SpecialPriceUniversity::getByGroupAndLevel(
                    $meta->special_price_group,
                    $level->id,
//                    Act::getByID($meta->act_id)->university_id //было так
                    $university_id
                );

                if ($spU) {
                    $foundPrices = true;
                    $result->firstTime = $spU->price_first_time?$spU->price_first_time:$sp->price_first_time;
                    $result->subtest1 = $spU->price_subtest_1?$spU->price_subtest_1:$sp->price_subtest_1;
                    $result->subtest2 = $spU->price_subtest_2?$spU->price_subtest_2:$sp->price_subtest_2;
                }
            }


        }

        if (!$foundPrices) {
            $cptl = ChangedPriceTestLevel::getPriceByLevel($act->id, $level->id);

            $result->firstTime = $cptl->price;
            $result->subtest1 = $cptl->sub_test_price;
            $result->subtest2 = $cptl->sub_test_price_2;
        }

        return $result;
    }
}