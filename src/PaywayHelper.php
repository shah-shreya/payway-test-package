<?php

namespace payway\payway;

class PaywayHelper
{
    /**
     * Generate hash for Payway Payment
     *
     * @param string $transactionId
     * @param float  $amount
     * @param array  $items
     *
     * @return string
     */
    public function getHash($transactionId, $amount,$items) { 
        $data = $this->getMerchantId() . $transactionId . $amount;
        if (count($items)) {
            $items = base64_encode(json_encode($items));
            $data .= $items;
        }
        $hash = base64_encode(hash_hmac('sha512', $data, $this->getApiKey(), true));
	    return $hash;
    }

    /**
     * Get URL of developement/production server based on api url specified in .env file
     * @return string
     */
    public function getUrl() {  
        if(config('payway.api_url') !== "") {
        $urlArray = explode('/api',config('payway.api_url'));
        return $urlArray[0];
        }
        
        throw new \Exception("PAYWAY_API_URL property must exist in .env file and it should not be null.");
    }

    /**
     * Get API-URL of developement/production server based on api url specified in .env file
     * @return string
     */
    public function getApiUrl() {  
        if(config('payway.api_url') !== "") {
        return config('payway.api_url');
        }
        
        throw new \Exception("PAYWAY_API_URL property must exist in .env file and it should not be null.");
    }

    /**
     * Get PAYWAY_MERCHANT_ID specified in .env file
     * @return string
     */
    public function getMerchantId() {  
        if(config('payway.merchant_id') !== "") {
        return config('payway.merchant_id');
        }
        
        throw new \Exception("PAYWAY_MERCHANT_ID property must exist in .env file and it should not be null.");
    }

    /**
     * Get PAYWAY_API_KEY specified in .env file
     * @return string
     */
    public function getApiKey() {  
        if(config('payway.api_key') !== "") {
        return config('payway.api_key');
        }
        
        throw new \Exception("PAYWAY_API_KEY property must exist in .env file and it should not be null.");
    }

    /**
     * Generate unique transaction Id for Payway Payment 
     * @return string
     */
    public function getUniqueTranId(){
        $unique_id = str_replace(".","",microtime(true)).rand(000,999);
        return $unique_id;
    }
}
