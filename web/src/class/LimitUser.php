<?php
class LimitUser extends Model {
    public static $_table = 'limitUsers';

    public function users(){
        $d = explode(",",$this->users);
        $ret = array();
        for($i=0;$i<count($d);++$i){
            $ret[] = User::find_one($d[$i]);
        }
        return $ret;
    }

}
?>