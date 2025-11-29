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
            // get db instance for lock
	    $db = ORM::get_db();
            try {
                // start transaction
		$db->beginTransaction();
	        // get coupon state with code
                $stmt = $db->prepare("SELECT * FROM coupons WHERE code = :code FOR UPDATE");
                $stmt->execute([':code' => $code]);
	        $c = $stmt->fetch(PDO::FETCH_OBJ);
                if($c && !$c->used){
                    // ポイント追加処理を入れる
                    $user->point += intval($c->amount);
                    $user->save();
                    // ポイント追加履歴を書き込む
                    $chistory = Model::factory('PointHistory')->create();
                    $chistory->user_id = $user->id;
                    $chistory->amount = $c->amount;
                    $chistory->save();
                    // クーポンコードを使用済みに変更
		    $stmt = $db->prepare("UPDATE coupons SET used = 1 WHERE code = :code");
		    $stmt->execute([':code' => $code]);
                    // save
		    $db->commit();
                    return true;
		} else {
                    // coupon code is not found
                    error_log("ERROR: coupon code not found - ${code}");
                    $db->rollBack();
		    return false;
		}
            } catch (Exception $e) {
                $db->rollBack();
                error_log("ERROR: " . $e->getMessage());
            }
        }
    }


}


?>