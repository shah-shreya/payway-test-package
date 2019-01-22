<?php

namespace App\Http\Controllers\payway;

use App\Http\Controllers\Controller;
 
use Illuminate\Http\Request;

use payway\payway\PaywayHelper;
 
class PaywayController extends Controller {

   public function payway(Request $request){
    //Create object to use payway helper methods
    $payway_helper = new PaywayHelper();   
    
    /* Perform your logic to get your Product/item related data over here.
     final product/item array should be in below format
     Example: item[x][name], item[x][quantity], item[x][price] 
     x is the item number, starting with 0 and increasing by one for each item that is added.  */ 
    $items = array();
    $items[0]['name'] = 'Item1';
    $items[0]['quantity'] = 2;
    $items[0]['price'] = 10;
    $items[1]['name'] = 'Item2';
    $items[1]['quantity'] = 4;
    $items[1]['price'] = 5;  
    //Product/item array End
    //Logic to calculate amaount based on item price and quantity
    $total_amount = 0.00;
    if(isset($items) && !empty($items)){
        foreach($items as $item)
        {
            $total_amount += $item['quantity']*$item['price'];
        }
    }
    //Amount logic End
    
    // Pass your Product,Payment and user related data like items,amount and user information from your system.
    $payment = array();
    // tran_id (string)(required) – unique tran_id < 20 characters , you can pass it according to your requirement.
    $payment['transactionId'] = $payway_helper->getUniqueTranId(); 
    // amount (decimal)(required) – total amount of items (valid format: 0.00)
    // amount currancy will be USD. So, if you are using any other currency, convert it into USD before passing it to payway. 
    $payment['amount'] = number_format($total_amount,2);  
    // firstname – (optional) 
    $payment['firstName'] = 'ABC';    
   // lastname – (optional) 
    $payment['lastName'] = 'XYZ';  
    // phone – (optional)
    $payment['phone'] = '1234567890'; 
    // email – (optional) 
    $payment['email'] = 'your.email@test.com';  
    // items – (optional)  base64_encode(json_encode(array item)) 
    $payment['items'] =  base64_encode(json_encode($items));
    // pass items array
    $payment['items_arr'] = $items;
    // hash (string) (required) – This will be auto-generated. (encrypt "merchant_id+tran_id+amount+items(optional) key" with hash_hmac sha512 after that convert the output using Base64. merchant_id and key - ABA Bank will be provided when client sign contract.)
    $payment['items'] =  base64_encode(json_encode($items));
    $payment['hashedTransactionId'] = $payway_helper->getHash($payment['transactionId'], $payment['amount'],$items);   
    // it will get url for developement/production server based on api url specified in .env file for payway
    $payment['url'] = $payway_helper->getUrl();
    // get the api URL specified in .env file 
    $payment['api_url'] = $payway_helper->getApiUrl();
    // get the merchant ID specified in .env file
    $payment['merchant_id'] = $payway_helper->getMerchantId();
    // get the api Key specified in .env file
    $payment['api_key'] = $payway_helper->getApiKey();

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
    
}
