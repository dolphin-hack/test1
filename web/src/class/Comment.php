<?php
class Comment  extends Model{
  public static $_table = 'comments';
  public function user(){
    return $this->belongs_to("User","user_id")->find_one();
  }
  public function product(){
    return $this->belongs_to("Product")->find_one();
  }


}
?>