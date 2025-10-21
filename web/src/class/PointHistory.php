<?php
class PointHistory extends Model{
    public static $_table = 'points';

    // CREATE TABLE points(id INTEGER PRIMARY KEY AUTO_INCREMENT, user_id INTEGER, amount INTEGER, timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP);


    public static function getChargeHistory($user_id){
        return Model::factory('PointHistory')
        ->where('user_id', $user_id)
        ->order_by_desc('timestamp')
        ->limit(10)
        ->find_many();
    }

}
?>