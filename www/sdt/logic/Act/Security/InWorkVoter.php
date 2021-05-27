<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 07.12.2017
 * Time: 16:30
 */

class InWorkVoter
{
    public function isPeopleEditGranted(Act $act){
        return   $act->state==Act::STATE_INIT && !$act->invoicePrinted();
    }
}