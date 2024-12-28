<?php

namespace App\Http\Controllers;

use paytm\paytmchecksum\PaytmChecksum;
use Illuminate\Http\Request;
use Response;
use Braintree;

class PaymentController extends Controller
{
    public function getPaytmChecksum(Request $request)
    {
        $input=$request->all();

        $paytmParams = array();

        $paytmParams["MID"] = $input['mid'];
        $paytmParams["ORDERID"] = $input['order_id'];
        $merchant_key=$input['key_secret'];

        $paytmChecksum = PaytmChecksum::generateSignature($paytmParams, $merchant_key);
        $result=array('code'=>$paytmChecksum);
        return response()->json($result);
    }

    public function validateChecksum(Request $request)
    {
        $input=$request->all();
        $paytmParams = array();
        $paytmParams["MID"] = $input['mid'];
        $paytmParams["ORDERID"] = $input['order_id'];
        $merchant_key=$input['key_secret'];
        /*$paytmChecksum = PaytmChecksum::generateSignature($paytmParams, $merchant_key);*/
        $mid=$input['mid'];
        $orderId=$input['order_id'];
        $body= array('mid'=>$mid,'orderId'=>$orderId);
        $paytmChecksum = $input['checksum_value'];
        $isVerifySignature = PaytmChecksum::verifySignature($body, $merchant_key, $paytmChecksum);
        if($isVerifySignature) {
            $result=array('status'=>true);
        } else {
            $result=array('status'=>false);
        }

        return response()->json($result);
    }

    public function initiatePaytmPayment(Request $request)
    {

        $inputs=$request->all();
        $paytmParams = array();

        $paytmParams["body"] = array(
            "requestType" => "Payment",
            "mid" => $inputs['mid'],
            "websiteName" => "Foodie",
            "orderId" => $inputs['order_id'],
            "callbackUrl" => $inputs['callback_url'],
            "txnAmount" => array(
            "value" => $inputs['amount'],
            "currency"=> $inputs['currency'],
            ),
            "userInfo"=> array(
            "custId"=> $inputs['custId'],
            ),
        );
        $checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), $inputs['key_secret']);

        $paytmParams["head"] = array("signature"=> $checksum);

        $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

        if($inputs['issandbox']){
            $url = "https://securegw-stage.paytm.in/theia/api/v1/initiateTransaction?mid=".$inputs['mid']."&orderId=".$inputs['order_id'];
        }else{
         $url = "https://securegw.paytm.in/theia/api/v1/initiateTransaction?mid=".$inputs['mid']."&orderId=".$inputs['order_id'];
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        $response = curl_exec($ch);
        $response=json_decode($response);
        return response()->json($response);
    }

    public function paymentsuccess()
    {
        return response()->json(array('result'=>$_REQUEST));
    }

    public function paymentfailed()
    {
        return response()->json(array('result'=>$_REQUEST));
    }

    public function paymentpending()
    {
        return response()->json(array('result'=>$_REQUEST));
    }


}
