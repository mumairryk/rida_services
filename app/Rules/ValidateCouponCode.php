<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Coupons;
use App\Models\CouponHistory;
class ValidateCouponCode implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
     public $return_status = 0;
     private $user_id = '';
     public $coupon_data = [];
     public $sub_total = 0;
     private $min_amount = 0;
    public function __construct($user_id,$sub_total)
    {
        //
        $this->user_id = $user_id;
        $this->sub_total = $sub_total;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value,$parameters=[])
    {
        //
        if($value != ''){
            $heck_exist = Coupons::where(['coupon_code'=>$value])->whereDate('coupon_end_date','>=',gmdate('Y-m-d'))->whereDate('start_date','<=',gmdate('Y-m-d'))->get();
            if($heck_exist->count() > 0){
                $coupon = $heck_exist->first();
                if(gmdate('Y-m-d',strtotime($coupon->coupon_end_date)) >= gmdate('Y-m-d')){
                    //check min amount
                    if( $this->sub_total < $coupon->minimum_amount ){
                        $this->return_status = 4;
                        $this->min_amount = $coupon->minimum_amount;
                        return false;
                    }
                    //end min  check

                    $total_coupon_usage = CouponHistory::where(['coupon_id'=>$coupon->id])->get();
                    if($total_coupon_usage->count() < $coupon->coupon_usage_percoupon){
                        $usage_data = CouponHistory::where(['user_id'=>$this->user_id,'coupon_id'=>$coupon->id])->get();
                        if($usage_data->count() < $coupon->coupon_usage_peruser){
                            $this->coupon_data = $coupon;
                            return true;
                        }else{
                            $this->return_status = 2;
                        }
                    }else{
                        $this->return_status = 3;
                    }

                }else{
                    $this->return_status = 1;
                }
            }
            return false;
        }else{
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $message = 'The :attribute is invalid';
        if($this->return_status == 1){
            $message = 'Coupon Expired';
        }else if($this->return_status == 2){
            $message = 'Coupon usage limit exceeded.';
        }else if($this->return_status == 3){
            $message = 'You have been used maximum allowed quanity of this coupon';
        }else if($this->return_status == 4){
            $message = 'Your cart total should be greater than '.$this->min_amount.' to avail this offer';
        }
        return $message;
    }
}
