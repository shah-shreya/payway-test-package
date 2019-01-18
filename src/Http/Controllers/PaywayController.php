<?php

namespace App\Http\Controllers\payway;

use App\Http\Controllers\Controller;
 
use Illuminate\Http\Request;
 
class PaywayController extends Controller {

   public function payway(Request $request){
       
    // Perform your logic to get payment related data like amount and user information from your system and pass it to payment array accordingly.
    $payment = array();
    // tran_id (string)(required) – unique tran_id < 20 characters , you can pass it according to your requirement.
    $payment['transactionId'] = '0005125'; 
    // amount (decimal)(required) – total amount of items (valid format: 0.00) 
    $payment['amount'] = '5.00';  
    // firstname – (optional) 
    $payment['firstName'] = 'ABC';    
   // lastname – (optional) 
    $payment['lastName'] = 'XYZ';  
    // phone – (optional)
    $payment['phone'] = '1234567890'; 
    // email – (optional) 
    $payment['email'] = 'your.email@test.com';   
    // hash (string) (required) – This will be auto-generated. (encrypt "merchant_id+tran_id+amount key" with hash_hmac sha512 after that convert the output using Base64. merchant_id and key - ABA Bank will be provided when client sign contract.)
    $payment['hashedTransactionId'] = $this->getHash($payment['transactionId'], $payment['amount']);   
    // it will get url for developement/production server based on api url specified in .env file for payway
    $payment['url'] = $this->getUrl();
    return view('payway::paywithpayway',compact('payment'));
    
   }
 
   public function getPaywayStatus(Request $request){        
      //payway will return json strin like - {"tran_id":"TRAN_ID_TEST123","status":0} to call back url.
       $payway_response = $request->response;
	   
       if(isset($payway_response)){
           $payway_response_array = json_decode($payway_response, true);
            if(isset($payway_response_array['status']) && $payway_response_array['status'] == 0){
                    // Apply  your Logic to perform after successful payment over here... 
		    $message = 'Pyament has been done and your payment id is : '.$payway_response_array['tran_id'];
		    //mail("useremail@test.com","Your Payment Using Payway","$message");
            }
	    else{
                 return 'Payment has failed.';
            }
       }
	else{
            return 'Payment has failed.';
       }
 
   }
   
    public function getHash($transactionId, $amount) {      
        $hash = base64_encode(hash_hmac('sha512', config('payway.merchant_id') . $transactionId . $amount, config('payway.api_key'), true));
	return $hash;
    }
	
    public function getUrl() {   
        $urlArray = explode('.', config('payway.api_url'));
        return $urlArray[0];
    }	
 
}
