<?php

namespace App\Http\Controllers\payway;

use App\Http\Controllers\Controller;
 
use Illuminate\Http\Request;
 
class PaywayController extends Controller {

   public function payway(Request $request){
       
    $transactionId = '000005135';
    $amount = '4.00';
    $firstName = 'Soem';
    $lastName = 'Chhengleap';
    $phone = '077459799';
    $email = 'soem.chhengleap@ababank.com';   

    $hashedTransactionId = $this->getHash($transactionId, $amount);  
    
    $payment = array(
                        'transactionId' => $transactionId,
                        'amount' => $amount,
                        'firstName' => $firstName,
                        'lastName' => $lastName,
                        'phone' => $phone,
                        'email' => $email,
                        'hashedTransactionId' => $hashedTransactionId
    );   
    return view('payway::paywithpayway',compact('payment'));
    //return view('payway',compact('payment'));
 
   }
 
   public function getPaywayStatus(Request $request){        
      
       $payway_response = '{"tran_id":"TRAN_ID_TEST123","status":0}';
       
       if(isset($payway_response)){
           $payway_response_array = json_decode($payway_response, true);
            if(isset($payway_response_array['status'])){
                if($payway_response_array['status'] == 0){
                    return 'Pyament has been done and your payment id is : '.$payway_response_array['tran_id'];
                }elseif($payway_response_array['status'] == 1){
                    return 'Payment has failed. Wrong hash has generated.';
                }elseif($payway_response_array['status'] == 2){
                    return 'Payment has failed. Validation code required.';
                }elseif($payway_response_array['status'] == 7){
                    return 'Payment has failed. Validation code required.';
                }elseif($payway_response_array['status'] == 11){
                    return 'Payment has failed. Other - server side error.';
                }
            }else{
                 return 'Payment has failed.';
            }
       }else{
            return 'Payment has failed.';
       }
 
   }
   
    public function getHash($transactionId, $amount) {      
        $hash = base64_encode(hash_hmac('sha512', config('payway.merchant_id') . $transactionId . $amount, config('payway.api_key'), true));
	return $hash;
    }
 
}
