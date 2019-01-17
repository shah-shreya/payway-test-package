
###**Laravel Payway (Payment Gateway) Installation steps:-**

**1.** For Laravel payway integration, first install the payway package in your application  using the composer command. Once package get installed, vendor publish command is required to make the package work. Open your command prompt and paste the following command under project directory.
   
**Composer Command :-  composer require payway/payway** 

**Vendor publish command :- php artisan vendor:publish**

**2. Create Payway Account:-**
     
Create a Payway developer mode and create a sandbox account to get important credentials like  api_key, api_url and merchant_id for integration with your Laravel app.

**URL for reference :- https://payway-dev.ababank.com/**

**3. Provide following details into .env file:-** 

After creating your application successfully on the hosting for Laravel project, click on it and it will show you api_key and api_url and merchant_id. Copy these credentials and paste them in your .env file.

**PAYWAY_API_KEY=**

**PAYWAY_API_URL=**

**PAYWAY_MERCHANT_ID=**
     
**4. Payway Form View:-**

For view Go to **laravelproject\resources\views\vendor\payway\**, you will get **paywithpayway.blade.php**

Edit the file according to your requirement, just take care about the must have things for payway to work.


-----------------------------------------------------------------------------------------------

      <html lang="en">
      <head>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
      </head>
      <body>
          <div id="aba_main_modal" class="aba-modal">		
	      <div class="aba-modal-content">
            <form method="POST" target="aba_webservice" action="{{ config('payway.api_url') }}" id="aba_merchant_request">
                <!-- Following are fields value passed in hidden which will be used for paymnet.
                hash, tran_id and amount are required fields, Change the fields according to your application -->
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
                    <!-- Apply your code here.. -->
                    <h2>TOTAL: $2.00</h2>
                    <!-- Checkout button for payment -->
                    <input type="button" id="checkout_button" value="Checkout Now">
             </div>
          </div>
          <!-- Scripts for developement mode - Start-->
             <link rel="stylesheet" href="https://payway-dev.ababank.com/checkout-popup.html?file=css"/>
             <script src="https://payway-dev.ababank.com/checkout-popup.html?file=js"></script>
          <!-- Scripts for developement mode - End-->
    
          <!-- Scripts for Live mode - Start-->
             <!--<link rel="stylesheet" href="https://payway.ababank.com/checkout-popup.html?file=css"/>
		         <script src="https://payway.ababank.com/checkout-popup.html?file=js"></script> -->
          <!-- Scripts for Live mode - End-->
    
       <script type="text/javascript">
           $(document).ready(function () {
                $('#checkout_button').click(function () {
                        AbaPayway.checkout();
                });
           });
       </script>   
      </body>
      </html>

    --------------------------------------------------------------------------------------------------

**For live payment, Replace the following development script Urls with live Urls in payway.blade.php:-**
     
**Development script Urls:-**

        <link rel="stylesheet" href="https://payway-dev.ababank.com/checkout-popup.html?file=css"/>
        <script src="https://payway-dev.ababank.com/checkout-popup.html?file=js"></script>

**Live script Urls:-**

        <link rel="stylesheet" href="https://payway.ababank.com/checkout-popup.html?file=css"/>
        <script src="https://payway.ababank.com/checkout-popup.html?file=js"></script>
        
**5. Payway Payment Controller:-**
     
**Command:- php artisan make:controller PaywayController**
     
-For controller, Go to **laravelproject\app\Http\Controllers\payway\** , here you will get **PaywayController.php.**

-Perform your logic related to getting payment information and status.    
     
     --------------------------------------------------------------------------------------------

      namespace App\Http\Controllers;
 
      use Illuminate\Http\Request;
 
      class PaywayController extends Controller {
 
         public function payway(Request $request){
    
          // Perform your logic to get payment related data like amount and user information from your system
          $payment = array();
          // tran_id (string)(required) – unique tran_id < 20 characters 
          $payment['transactionId'] = '12345'; 
          // amount (decimal)(required) – total amount of items (valid format: 0.00) 
          $payment['amount'] = '2.00';  
          // firstname – (optional) 
          $payment['firstName'] = 'ABC';    
          // lastname – (optional) 
          $payment['lastName'] = 'XYZ';  
          // phone – (optional)
          $payment['phone'] = '1234567890'; 
          // email – (optional) 
          $payment['email'] = 'your.email@test.com';   
          // hash (string) (required) – This will be auto-generated. (encrypt "merchant_id+tran_id+amount+items(optional), key" with hash_hmac sha512 after that convert the output using Base64. merchant_id and key - ABA Bank will be provided when client sign contract.)
          $payment['hashedTransactionId'] = $this->getHash($payment['transactionId'], $payment['amount']);  

          return view('payway',compact('payment'));
 
      }
 
         public function getPaywayStatus(Request $request){        
      
             $payway_response = $request->response;
             if(isset($payway_response)){
                 $payway_response_array = json_decode($payway_response, true);
                  if(isset($payway_response_array['status']) && $payway_response_array['status'] == 0){
                    // Apply  your code after successful payment here... 
                    return 'Pyament has been done and your payment id is : '.$payway_response_array['tran_id'];
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
    
**6. How to access payment page :-**

You can access your checkout page from this url:

**your-site-url/paywithpayway**

Need to set below url as your callback url:

**your-site-url/payway_status**

    
**7. Continue Purchase URL and Push Back Notification URL Settings:-**

    -For settings of Continue Purchase URL and Push Back Notification URL, refer following  steps and change url according to your application.
    
    1. Go to URL :- https://payway-dev.ababank.com/transaction-management/
    2. Go to Settings -> Setup SMTP
    3. Change Continue Purchase URL and Push Back Notification URL according to your application.
    
**For detail description and snaps refer to [Payway Document] (https://github.com/shah-shreya/payway-test-package/blob/master/PaywayDocument.docx)**  












 


