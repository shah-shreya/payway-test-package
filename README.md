
### **Laravel Payway (Payment Gateway) Installation steps:-**

**1.** For Laravel payway integration, first install the payway package in your application  using the composer command. Once package get installed, vendor publish command is required to make the package work. Open your command prompt and paste the following command under project directory.
   
**Composer Command :-  composer require payway/payway** 

**Vendor publish command :- php artisan vendor:publish**

**2. Create Payway Account:-**
     
Create a Payway developer mode and create a sandbox account to get important credentials like  api_key, api_url and merchant_id for integration with your Laravel app.

**URL for reference :- https://payway-staging.ababank.com/**

**3. Provide following details into .env file:-** 

After creating your application successfully on the hosting for Laravel project, click on it and it will show you api_key and api_url and merchant_id. Copy these credentials and paste them in your .env file.

**PAYWAY_API_KEY=**

**PAYWAY_API_URL=**

**PAYWAY_MERCHANT_ID=**
     
**4. Payway Form View:-**

-For view Go to  laravelproject\resources\views\vendor\payway\, you will get **paywithpayway.blade.php**

-Edit the file according to your requirement, just take care about the must have things for payway to work. Following is the list of must have things :-

* Jquery is required to be added.
* Following are the parameters which must be passed in hidden fields.

     1. tran_id (string) 
  
     2. amount (decimal) 
  
     3. hash (string)  
  
* Checkout button is required to be added for checkout proccess. By clicking on it , popup for payway checkout will be shown to process payment.
* Scripts for adding Payway Js and Css are required to be added.
* Js for opening checkout popup on click of checkout button is required to be added.
* Items array is optional parameter which is passed in hidden field. In Items array, items must be passed as below :- 

     $items[0]['name'] = 'Item1';    
     
     $items[0]['quantity'] = 2;  
     
     $items[0]['price'] = 10;
     
* Payway accept amount in USD, so if you are using any other currency then amount must be converted in USD before passing it in payway.

-----------------------------------------------------------------------------------------------

 	 <html lang="en">
     <head>
     <!--Jquery is required to be added -->
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
     </head>
     <body>
    <div id="aba_main_modal" class="aba-modal">		
	<div class="aba-modal-content">
    <!-- Following is the form where the values are passed in hidden, which will be used for payment.-->
            <form method="POST" target="aba_webservice" action="{{ $payment['api_url'] }}" id="aba_merchant_request">
                <input type="hidden" id="hash" name="hash" value="{{$payment['hashedTransactionId']}}">
                <input type="hidden" id="tran_id" name="tran_id" value="{{$payment['transactionId']}}">
                <input type="hidden" id="amount" name="amount" value="{{$payment['amount']}}">
                <input type="hidden" id="firstname" name="firstname" value="{{$payment['firstName']}}">
                <input type="hidden" id="lastname" name="lastname" value="{{$payment['lastName']}}">
                <input type="hidden" id="phone" name="phone" value="{{$payment['phone']}}">
                <input type="hidden" id="email" name="email" value="{{$payment['email']}}">
                @if(isset($payment['items']))
                    <input type="hidden" id="items" name="items" value="{{$payment['items']}}">
                @endif    
            </form>
    <!--Form End-->        
        </div>
    </div>
    <!--Add your code for the checkout Page Here-->
    <div class="container" style="margin-top: 75px;margin: 0 auto;">
            <div style="width: 250px;margin: 0 auto;">
                    @if(isset($payment['items_arr']) and !empty($payment['items_arr']))
                    <table class="table-bordered">
                    <tr>
                    <td>Item</td>
                    <td>Quantity</td>
                    <td>Price</td>
                    </tr>
                    @foreach($payment['items_arr'] as $item)
                    <tr>
                    <td>{{$item['name']}}</td>
                    <td>{{$item['quantity']}}</td>
                    <td>{{$item['price']}}</td>
                    </tr>
                    @endforeach
                    </table>
                    @endif
                    <h2>TOTAL: {{$payment['amount']}} USD</h2>
                    <!-- Checkout button for payment -->
                    <input type="button" id="payway_checkout_button" value="Checkout with Payway">
            </div>
    </div>
    <!--Checkout Container End -->

    <!-- Scripts for adding Payway Js and Css - Start-->
    <link rel="stylesheet" href="{{$payment['url']}}/checkout-popup.html?file=css"/>
    <script src="{{$payment['url']}}/checkout-popup.html?file=js"></script>
    <!-- Scripts for adding Payway Js and Css - End-->
    
    <!--Open Checkout popup on click of checkout button-->
    <script type="text/javascript">
        $(document).ready(function () {
                $('#payway_checkout_button').click(function () {
                        AbaPayway.checkout();
                });
        });
    </script>   
     </body>
     </html>
--------------------------------------------------------------------------------------------------
        
**5. Payway Payment Controller:-**
     
-For controller, Go to laravelproject\app\Http\Controllers\payway\ , here you will get **PaywayController.php.**

-Perform your logic related to getting payment information and status.    
     
--------------------------------------------------------------------------------------------

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
      	//payway will return json string like - {"tran_id":"TRAN_ID_TEST123","status":0} to call back url.
       	$payway_response = $request->response;
	   
		if(isset($payway_response)){
		   $payway_response_array = json_decode($payway_response, true);
			if(isset($payway_response_array['status']) && $payway_response_array['status'] == 0){
			// Apply  your Logic to perform after successful payment over here... 
			    $message = 'Pyament has been done and your Transaction ID is : '.$payway_response_array['tran_id'];
			    //mail("useremail@test.com","Your Payment Using Payway","$message");
			}
			else{
				// Apply  your Logic to perform for failed Transaction over here... 
				 $message = 'Payment has failed.';
			}	
		}
		else{
		    $message = 'It is not for browser please! It is to be set as Push Back Notification URL in your Payway account to 				get the response from Payway.';
		    die($message);
	       }
   	}
     }
---------------------------------------------------------------------------------
    
**6. Continue Purchase URL and Push Back Notification URL Settings:-**

    -Change Continue Purchase URL and Push Back Notification URL according to your application.
    
    1. Go to URL :- https://payway-staging.ababank.com/transaction-management/
    2. Go to Settings -> Setup SMTP
    3. Set Continue Purchase URL to : your-site-url/shop_page
    4. Set Push Back Notification URL to : your-site-url/payway_status
    
**7. How to access payment page :-**

You can access your checkout page from this url:

**your-site-url/paywithpayway**    
    
**For detail description and snaps refer to [Payway Document](https://github.com/shah-shreya/payway-test-package/blob/master/PaywayDocument.docx)**  












 


