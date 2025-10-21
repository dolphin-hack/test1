<?php
class Coupon extends Model{
    public static $_table = 'coupons';

    // CREATE TABLE coupons(id INTEGER PRIMARY KEY AUTO_INCREMENT, code TEXT, amount INTEGER, used INTEGER, timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP);

    public static function generate($code, $amount){
        $tmp = Model::factory("Coupon")->create();
        $tmp->code = $code;
        $tmp->amount = intval($amount);
        $tmp->used = 0;
        $tmp->save();
        return true;
     }

    public static function isAvailable($code){
        return Model::factory('Coupon')
        ->where('code', $code)
        ->where('used', 0)
        ->find_one();
    }

    public static function getAvailableCodes(){
        return Model::factory('Coupon')
        ->where('used', 0)
        ->limit(10)
        ->find_many();
    }

    public static function getUsedCodes($id){
        return Model::factory('Coupon')
        ->where('used', 1)
        ->where('id', $id)
        ->order_by_desc('timestamp')
        ->limit(10)
        ->find_many();
    }

    public static function update($user, $code){
        if(!empty($code) && is_object($user)){
            try {
                $c = Model::factory("Coupon")
                ->where('code', $code)
                ->find_one();
                if($c instanceof Model && !$c->used){
                    // ポイント追加処理を入れる
                    $user->point += intval($c->amount);
                    $user->save();
                    // ポイント追加履歴を書き込む
                    $chistory = Model::factory('PointHistory')->create();
                    $chistory->user_id = $user->id;
                    $chistory->amount = $c->amount;
                    $chistory->save();
		    // 2013-02-24 Yamada: これを消すとうまく動かないので削除しないこと
		    sleep(1);
                    // クーポンコードを使用済みに変更
                    $c->used = 1;
                    $c->save();
                    return true;
                }
            } catch (Exception $e) {
                error_log("ERROR: " . $e->getMessage());
            }
        }
    }


}
?>