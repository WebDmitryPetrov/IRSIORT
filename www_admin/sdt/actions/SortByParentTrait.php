<?php

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 01.03.2017
 * Time: 16:06
 */
trait SortByParentTrait
{
    /**
     * @param $acts
     * @return Univesities
     */
    protected function universitySortByParent(Univesities $acts)
    {
        $acts = $acts->getArrayCopy();

        $allParentIDS = [];
        foreach ($acts as $item) {
            if (!$item->parent_id) continue;
            if (in_array($item->parent_id, $allParentIDS)) continue;
            $allParentIDS[] = $item->parent_id;
        }

        $allIDS = array_map(function ($item) {
            return $item->id;
        }, $acts);

        $unknownParentIDS = array_diff($allParentIDS, $allIDS);


        $parents = array_filter($acts, function ($act) use ($unknownParentIDS) {
            return !$act->parent_id || in_array($act->parent_id, $unknownParentIDS);
        });


        $result = new Univesities();
        array_walk($parents, function ($act) use ($result, $acts, $unknownParentIDS) {
            if (in_array($act->parent_id, $unknownParentIDS)) {
                $act->parent_name = University::getCaption($act->parent_id);
//               var_dump($act); die;
            }

            $result->append($act);
            $act->filtered = array_filter($acts, function ($child) use ($act) {
                return $child->parent_id == $act->id;
            });
            /* if ($filtered) {
                 foreach ($filtered as $item)
                     $result->append($item);
             }*/

        });
//        var_dump(strcmp('Центр языка и культуры "Слово"','Г. Саратов, Общество с ограниченной ответственностью "Сармиграция"'));
//        var_dump(strcmp("Центр экономического и правового образования",'Центр языка и культуры "Слово"'));
//        var_dump(strcmp('Центр языка и культуры "Слово"',"Центр экономического и правового образования"));

        $result->uasort(function ($a, $b) {
            $a_name = trim(isset($a->parent_name) ? $a->parent_name : $a->caption);
            $a_name=mb_strtolower($a_name,'cp1251');
            $b_name = trim(isset($b->parent_name) ? $b->parent_name : $b->caption);
            $b_name=mb_strtolower($b_name,'cp1251');
//            var_dump($a_name,$b_name);
            $res =  strcmp($a_name, $b_name);
            if($res===0){
                return   strcmp(mb_strtolower( $a->caption,'cp1251'),mb_strtolower($b->caption,'cp1251'));
            }
            return $res;
        });

        $flatten = new Univesities();
        foreach($result as $item){
            $flatten->append($item);
            if(!empty($item->filtered)){
                foreach($item->filtered as $ch){
                    $flatten->append($ch);
                }
            }
        }

        return $flatten;
    }
}