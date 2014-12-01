<?php 
class Sms{

function sendSms($phone,$message){


//$url="http://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&send_to=".$phone."&msg=".urlencode($message)."&msg_type=TEXT&userid=2000136385&auth_scheme=plain&password=sms13k&v=1.1&format=text";
$url="http://india.ebensms.com/api/v1/sms/single.json?token=2bbbe622-63e4-11e4-8f8e-a1bba6839208&msisdn=".$phone."&text=".urlencode($message)."&sender_id=CncCab&route=TRANS";


		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output=curl_exec($ch);
		curl_close($ch);                                
		return $output;

}

}
?>
