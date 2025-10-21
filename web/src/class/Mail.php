<?php
class Mail extends Model{
    public static $_table = 'mails';

    public function to_user(){
        return User::find_one($this->to_user_id);
    }

    public function from_user(){
            return User::find_one($this->from_user_id);
    }
}
?>