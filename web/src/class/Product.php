<?php
class Product extends Model{
    public static $_table = 'products';

    const STATE_SELL = 1;
    const STATE_SOLDOUT = 2;
    const STATE_STOPSELL = 3;

    const MODE_EVERYONE = 0;
    const MODE_ONLYSOUGOFOLLOWER = 1;
    const MODE_ONLYLIMITEDUSER = 2;
    const MODE_ONLYFOLLOWER = 3;


    public function user(){
        return User::find_one($this->user_id);
    }

    public function limitUsers(){
        if($this->mode == self::MODE_EVERYONE){
            return null;
        } elseif($this->mode == self::MODE_ONLYSOUGOFOLLOWER){
            return $this->user()->getSougoFollows();
        } elseif($this->mode == self::MODE_ONLYLIMITEDUSER){
            return LimitUser::find_one($this->limitUser_id)->users();
        } elseif($this->mode == self::MODE_ONLYFOLLOWER){
            return $this->user()->getFollowers();
        }
        return array();
    }

    public function canSee($user){
        if(!is_null($user)){
            if($this->user_id == $user->id) return true;
        }
        $t = $this->limitUsers();
        $f = false;
        if(is_null($t)) return true;
        foreach($t as $v){
            if($v->id == $user->id){$f=true;break;}
        }
        return $f;
    }

    public static function getUserOnlyProduct($user){
        return LimitUser::where_like("users","%,".$user->id.",%")->find_many();
    }

    public function buy($user, $price){
        if($price >= 0 && $user->point >= $price){
            if($this->state == self::STATE_SELL){
                $user->point -= $price;
                $buser = $this->user();
                $buser->point += $price;
                sleep(1);
                $buser->save();
                $user->save();
                $bag = Bag::create();
                $bag->user_id = $user->id;
                $bag->product_id = $this->id;
                $bag->save();
                $this->state = self::STATE_SOLDOUT;
                $this->save();
                return true;
            }
        }
        return false;
    }

    public static function getProductList($user = null){
        $ret = array();
        if($user){
            $ps = Product::order_by_desc("id")->find_many();
            foreach($ps as $p){
                if($p->canSee($user)){$ret[] = $p;}
            }
        } else {
            $ret = Product::where("mode",self::MODE_EVERYONE)->order_by_desc("id")->find_many();
        }
        return $ret;
    }
}
?>