<?php

class RkiHelper{

public static function getLevelGroups(){
return    array(
                1 => 0,
                2 => 1,
                3 => 2,
                5 => 3,
                6 => 4,
                7 => 5,
                8 => 6,
                10 => 8,
                11 => 7,
                12 => 8,
            );
}
public static function getLevelGroup($level_id){
$levels = self::getLevelGroups();

return array_key_exists($level_id,$levels)?$levels[$level_id]:null;
}


}