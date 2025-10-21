<?php
class Bag extends Model{
    public static $_table = 'bags';

    public function user(){
        return $this->has_one('User')->find_one();
    }

    public function product(){
        return $this->has_one('Product')->find_one();
    }

    public function from_user(){
        return User::find_one($this->product()->user_id);
    }

}
?>