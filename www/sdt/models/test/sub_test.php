<?php


class SubTests extends ArrayObject
{
    public $level_id;

    public static function getByLevel(TestLevel $level)
    {
        $result = new self();
        $result->level_id = $level;
        for ($i = 1; $i <= $level->subtest_count; $i++) {
            $prefix = 'test_' . $i . '_';
            $caption = $prefix . 'caption';
            $max_ball = $prefix . 'maxball';
            $short_caption = $prefix . 'short_caption';
            $full_caption = $prefix . 'full_caption';
            $ps = $prefix . 'pass_score';
            $subtest = new SubTest();
            $subtest->caption = $level->$caption;
            $subtest->max_ball = $level->$max_ball;
            $subtest->short_caption = $level->$short_caption;
            $subtest->full_caption = $level->$full_caption;
            $subtest->pass_score = $level->$ps;
            $subtest->order = $i;
            $subtest->level_id = $level->id;
            
            $subtest->meta = SubTestMeta::getByLevelNum($level->id,$i);
            
            $result->append($subtest);
        }

        return $result;
    }

    /**
     * @param $order
     * @return null|SubTest
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
}

class SubTest
{
    public $max_ball;
    public $caption;
    public $short_caption;
    public $full_caption;
    public $order;
    public $pass_score;
    public $level_id;
    /** @var  SubTestMeta|null */
    public $meta;


}