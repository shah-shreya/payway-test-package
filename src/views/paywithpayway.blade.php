<html lang="en">
<head>
<!--Jquery is required to be added -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
</head>
<body>
    <div id="aba_main_modal" class="aba-modal">		
	<div class="aba-modal-content">
    <!-- Following is the form where the values are passed in hidden, which will be used for payment.-->
            <form method="POST" target="aba_webservice" action="{{ config('payway.api_url') }}" id="aba_merchant_request">
                <input type="hidden" id="hash" name="hash" value="{{$payment['hashedTransactionId']}}">
                <input type="hidden" id="tran_id" name="tran_id" value="{{$payment['transactionId']}}">
                <input type="hidden" id="amount" name="amount" value="{{$payment['amount']}}">
                <input type="hidden" id="firstname" name="firstname" value="{{$payment['firstName']}}">
                <input type="hidden" id="lastname" name="lastname" value="{{$payment['lastName']}}">
                <input type="hidden" id="phone" name="phone" value="{{$payment['phone']}}">
                <input type="hidden" id="email" name="email" value="{{$payment['email']}}">
            </form>
    <!--Form End-->        
        </div>
    </div>
    <!--Add your code for the checkout Page Here-->
    <div class="container" style="margin-top: 75px;margin: 0 auto;">
            <div style="width: 200px;margin: 0 auto;">
                    <h2>TOTAL: {{$payment['amount']}}</h2>
                    <!-- Checkout button for payment -->
                    <input type="button" id="checkout_button" value="Checkout Now">
            </div>
    </div>
    <!--Checkout Container End -->

    <!-- Scripts for developement mode - Start-->
    <link rel="stylesheet" href="https://payway-dev.ababank.com/checkout-popup.html?file=css"/>
    <script src="https://payway-dev.ababank.com/checkout-popup.html?file=js"></script>
    <!-- Scripts for developement mode - End-->
    
    <!-- Scripts for Live/Production mode - Start-->
    <!--<link rel="stylesheet" href="https://payway.ababank.com/checkout-popup.html?file=css"/>
		<script src="https://payway.ababank.com/checkout-popup.html?file=js"></script> -->
    <!-- Scripts for Live/Production mode - End-->
    
    <!--Open Checkout popup on click of checkout button-->
    <script type="text/javascript">
        $(document).ready(function () {
                $('#checkout_button').click(function () {
                        AbaPayway.checkout();
                });
        });
    </script>   
</body>
</html>