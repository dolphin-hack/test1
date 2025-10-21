<?php
class Friend extends Model{
    public static $_table = 'friends';

    public  function to_user(){
        return $this->belongs_to("User","to_user_id")->find_one();
    }

    public  function from_user(){
        return $this->belongs_to("User","from_user_id")->find_one();
    }

    public static function isFriend($from_id,$to_id){
        $lst = Model::factory("Friend")->raw_query(
            "SELECT * FROM friends WHERE (from_user_id = {$from_id} AND to_user_id = {$to_id}) OR (to_user_id = {$from_id} AND from_user_id = {$to_id})"
            )->find_many();
        return count($lst) == 2;
    }

    public function makeFriendRequest($user1,$user2){
        $tmp = FriendList::create();
        $tmp->to_user_id = $user2->id;
        $tmp->from_user_id = $user1->id;
        $tmp->state = 0;
        $tmp->save();
        return true;
    }

    public static function forceFriend($user1,$user2){
        $tmp = FriendList::raw_query(
            "SELECT * FROM friends WHERE state = 0 AND ((to_user_id = :to_id OR from_user_id = :from_id) OR (to_user_id = :from_id OR from_user_id = :to_id))",
            array("to_id" => $user1->id,"from_id" => $user2->id)
            )->find_one();
        if(!$tmp){
            $tmp = new FriendList();
            $tmp->from_user_id = $user2->id;
            $tmp->to_user_id = $user1->id;
        }
        $tmp->state=1;
        $tmp->save();
        return true;
    }

    public static function deleteFriend($user1,$user2){
        $tmp = FriendList::raw_query(
            "SELECT * FROM friends WHERE (to_user_id = :to_id OR from_user_id = :from_id) OR (to_user_id = :from_id OR from_user_id = :to_id)",
            array("to_id" => $user1->id,"from_id" => $user2->id)
            )->delete_many();
    }
}
?>