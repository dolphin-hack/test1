<?php
class User extends Model{
    public static $_table = 'users';
    public function products(){
        return $this->has_many("Product")->find_many();
    }
    public static function make($loginid,$password,$name,$cardno,$point=100){
      if(User::where("loginid",$loginid)->find_one()){
        return null;
      }

      $tmp = User::create();
      $tmp->loginid = $loginid;

      $tmp->password = $password;
      $tmp->name = $name;
      $tmp->point = $point;
      $tmp->cardno = $cardno;
      $tmp->priv = 0;

      $tmp->save();

      return $tmp;
    }

    public static function login($loginid,$password){
      $user = User::where("loginid",$loginid)->find_one();
      if($password === $user->password){
        return $user;
      }
      return null;
    }
    public function buyProducts(){
        $bags = $this->has_many("Bag")->find_many();
        $ret = [];
        foreach($bags as $bag){
            $ret[] = $bag->product();
        }
        return $ret;
    }

    public function getMails(){
      $ret = Mail::where("to_user_id",$this->id)->find_many();

      return $ret;
    }

    public function follow($touser){
      $f=Friend::create();
      $f->to_user_id = $touser->id;
      $f->from_user_id = $this->id;
      $f->save();
    }

    public function unfollow($touser){
      $f=Friend::where("to_user_id",$touser->id)->where("from_user_id",$this->id)->find_one();
      $f->delete();
    }

    public function getFollowers(){
      $v = Friend::where("to_user_id",$this->id)->find_many();
      $ret = array();

      foreach($v as $x){
        $ret[] = Model::factory("User")->find_one($x->from_user_id);
      }
      return $ret;
    }

    public function getFollows(){
      $v = Friend::where("from_user_id",$this->id)->find_many();
      $ret = array();
      foreach($v as $x){
        $ret[] = Model::factory("User")->find_one($x->to_user_id);
      }
      return $ret;
    }

    public function getSougoFollows(){
      $tou = $this->getFollowers();
      $fromu = $this->getFollows();

      $bbb = array();
      $ret = array();
      foreach($tou as $x){
        $bbb[$x->id] = 1;
      }
      foreach($fromu as $x){
        if(isset($bbb[$x->id])) $ret[] = $x;
      }

      return $ret;
    }

    public function isSougoFollow($user){
      $to = Friend::raw_query("SELECT id FROM friends WHERE to_user_id = ? AND from_user_id = ?",array($user->id,$this->id))->find_one();
      $from = Friend::raw_query("SELECT id FROM friends WHERE from_user_id = ? AND to_user_id = ?",array($user->id,$this->id))->find_one();
      if($to && $from){
        return true;
      } else {
        return false;
      }
    }

    public function isFollow($user){
      $to = Friend::raw_query("SELECT id FROM friends WHERE to_user_id = ? AND from_user_id = ?",array($user->id,$this->id))->find_one();
      if($to){return true;}else{return false;}
    }

    public function isFollower($user){
      $to = Friend::raw_query("SELECT id FROM friends WHERE from_user_id = ? AND to_user_id = ?",array($user->id,$this->id))->find_one();
      if($to){return true;}else{return false;}
    }

  public function sendMessage($to_user,$title,$text){
      $message = Mail::create();
      $message->to_user_id = $to_user->id;
      $message->from_user_id = $this->id;
      $message->title = $title;
      $message->message = $text;
      $message->save();
      return $message->id;
  }

  public function readMessages(){
      return Mail::where("to_user_id",$this->id)->find_many();
  }
  public function writeMessages(){
    return Mail::where("from_user_id",$this->id)->find_many();
  }


  public static function accountInfoUpdate($user,$loginid,$name,$priv){


    $tmp = User::where("id", $user->id)->find_one();
    if($tmp){
        $tmp->loginid = $loginid;
        $tmp->name    = $name;
        $tmp->priv    = $priv;
        return $tmp->save(); 
    } else {
        return false;
    }
    
  }

  public static function accountPasswordUpdate($user,$password){

    $tmp = User::where("id",$user->id)->find_one();

    if($tmp){
      $tmp->password = $password;
      return $tmp->save();
    } else{
      return false;
    }
    
  }
}
?>