<?php
class Follow extends Model{
    public static $_table = 'follows';

    public  function to_user(){
        return $orm->belongs_to("User","to_user_id");
    }

    public  function from_user(){
        return $orm->belongs_to("User","from_user_id");
    }



}
?>