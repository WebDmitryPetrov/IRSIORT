<?php

class TestCalculation
{

    /**
     *
     * @param SubTestGroup $group
     * @param SubTestResult[] $vars
     * @return mixed
     */
    public function calc($group, $vars)
    {
        $function = $group->formula;
//var_dump($group, $function);
        if (!method_exists($this, $function)) throw  new LogicException('calculation method not implemented');

        return call_user_func(array($this, $function), $group, $vars);
    }

    /**
     * @param SubTestGroup $group
     * @param SubTestResult[] $vars
     * @return bool
     */
    private function hist2016($group, $vars)
    {
        $a = $vars['a'];
        $b = $vars['b'];
        if ($b->balls < $b->subtest->meta->pass_score) {
            return false;
        }

        if ($a->balls + $b->balls >= $group->pass_score) return true;

        return false;
    }
}