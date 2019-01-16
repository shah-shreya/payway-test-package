# Payway Integration Package  

## This will allow you to integrate payway in your laravel based shopping cart application.

### **  Laravel Payway (Payment Gateway) Installation steps:- **

**1.** For Laravel payway integration, first install the payway package in your application  using the composer command. Open your command prompt and paste the following command under project directory.
   
**Command:-  --**

**2. Create Payway Account:- **
     
Create a Payway developer mode and create a sandbox account to get important   credentials like  api_key, api_url and merchant_id to test its integration with your Laravel app.

**URL for reference :- https://payway-dev.ababank.com/**

-After creating your application successfully on the hosting for Laravel project, click on it and it will show you api_key and api_url and merchant_id. Copy these credentials and paste them in your .env file.

PAYWAY_API_KEY= 

PAYWAY_API_URL= 

PAYWAY_MERCHANT_ID= 
     
**3. Payway Form View:- **

Create a blade file:-  project_directory\resources\views\payway.blade.php

After creating payway.blade.php, put following code into it.

----------------------------------------------------------------------------

     <html lang="en">
     <head>
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
     </head>
     <body>
     <div id="aba_main_modal" class="aba-modal">		
	 <div class="aba-modal-content">
            <form method="POST" target="aba_webservice" action="{{ config('payway.api_url') }}" id="aba_merchant_request">
                <input type="hidden" id="hash" name="hash" value="{{$payment['hashedTransactionId']}}">
                <input type="hidden" id="tran_id" name="tran_id" value="{{$payment['transactionId']}}">
                <input type="hidden" id="amount" name="amount" value="{{$payment['amount']}}">
                <input type="hidden" id="firstname" name="firstname" value="{{$payment['firstName']}}">
                <input type="hidden" id="lastname" name="lastname" value="{{$payment['lastName']}}">
                <input type="hidden" id="phone" name="phone" value="{{$payment['phone']}}">
                <input type="hidden" id="email" name="email" value="{{$payment['email']}}">
            </form>
        </div>
     </div>
     <div class="container" style="margin-top: 75px;margin: 0 auto;">
            <div style="width: 200px;margin: 0 auto;">
                    <h2>TOTAL: $2.00</h2>
                    <input type="button" id="checkout_button" value="Checkout Now">
            </div>
     </div>
     <link rel="stylesheet" href="https://payway-dev.ababank.com/checkout-popup.html?file=css"/>
     <script src="https://payway-dev.ababank.com/checkout-popup.html?file=js"></script>
     <!--<link rel="stylesheet" href="https://payway.ababank.com/checkout-popup.html?file=css"/>
		<script src="https://payway.ababank.com/checkout-popup.html?file=js"></script> -->
     <script type="text/javascript">
        $(document).ready(function () {
                $('#checkout_button').click(function () {
                        AbaPayway.checkout();
                });
        });
    </script>   
    </body>
    </html>

    ----------------------------------------------------------------------------

**For live payment, Replace the following development script Urls with live Urls in payway.blade.php:-**
     
**Development script Urls:-**

        <link rel="stylesheet" href="https://payway-dev.ababank.com/checkout-popup.html?file=css"/>
        <script src="https://payway-dev.ababank.com/checkout-popup.html?file=js"></script>

**Live script Urls:-**

        <link rel="stylesheet" href="https://payway.ababank.com/checkout-popup.html?file=css"/>
        <script src="https://payway.ababank.com/checkout-popup.html?file=js"></script>
        
**4. Payway Payment Controller:- **

- Run below command to create a new controller at /app/http/Controllers with the name PaywayController.php.
     
**Command:- php artisan make:controller PaywayController**
     
     - After creating PaywayController.php, put following code into it.
     
     ---------------------------------------------------------------------------------
     
     namespace App\Http\Controllers;
     use Illuminate\Http\Request;
     class PaywayController extends Controller {
             public function payway(Request $request){
                  //  Provide following fields details which will be used while doing the payment
                  // tran_id (string)(required) – unique tran_id < 20 characters 
                     $transactionId = '123'; 
                  // amount (decimal)(required) – total amount of items (valid format: 0.00) 
                     $amount = '2.00';  
                  // firstname – (optional) 
                     $firstName = 'ABC';    
                  // lastname – (optional) 
                     $lastName = 'XYZ';  
                  // phone – (optional)
                     $phone = '1234567890'; 
                  // email – (optional) 
                     $email = 'your.email@test.com';   
                  // hash (string) (required) – This will be auto-generated. (encrypt "merchant_id+tran_id+amount+items(optional), key" with hash_hmac sha512 after that convert the output using Base64. merchant_id and key - ABA Bank will be provided when client sign contract.)
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
               return view('payway',compact('payment'));
        }   
        public function paymentInfo(Request $request){     
               
               if(isset($payway_response)){
                     $payway_response_array = json_decode($payway_response, true);
                     if(isset($payway_response_array['status'])){
                          if($payway_response_array['status'] == 0){
                              return 'Pyament has been done and your payment id is : '.$payway_response_array['tran_id'];
                          }else{
                              return 'Payment has failed.';
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

    
    ---------------------------------------------------------------------------------
    
**5. Continue Purchase URL and Push Back Notification URL Settings:-**

    -For settings of Continue Purchase URL and Push Back Notification URL, refer following  steps and change url according to your application.
    
    1. Go to URL :- https://payway-dev.ababank.com/transaction-management/
    2. Go to Settings -> Setup SMTP
    3. Change Continue Purchase URL and Push Back Notification URL according to your application.
    
**For detail description and snaps refer to [Payway Document] (/paywayDocument.docx)**  












 


