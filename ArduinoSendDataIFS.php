<?php
// --- This is your IFS soapgateway URL
$url = 'https://YOUR_IFS_URL:PORT/fndext/soapgateway/';
// --- base64 encoded username:password
$auth = 'YWxhaW46YWxhaW4='; //alain:alain
// --- Here is your parameter from the http GET
$arduino_data = $_GET['arduino_data'];
// --- $arduino_data_post = $_POST['name'];

//cheap trick to set the registered time on the request
// Change the line below to your timezone!
date_default_timezone_set('Asia/Colombo');
$curr_date = date("Y-m-d");
$curr_time = date("H:i:s");


//send the data via curl_close
//here I use BizApi ObjectMeasurement:ReceiveMeasurement
$xml_data = "<soap:Envelope xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:urn=\"urn:soap_access_provider:ObjectMeasurement:ReceiveMeasurement\">
   <soap:Header/>
   <soap:Body>
 <MEASUREMENT xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
 <SITE>70</SITE>
 <OBJECT_ID>TEST_MWO</OBJECT_ID>
 <TEST_POINT_ID>2</TEST_POINT_ID>
 <PARAMETER_CODE>10</PARAMETER_CODE>
 <VALUE>{$arduino_data}</VALUE>
 <REGISTERED>{$curr_date}T{$curr_time}</REGISTERED>
 <REMARK>Added by DaJilk Arduino</REMARK>
</MEASUREMENT>
   </soap:Body>
</soap:Envelope>";

$header = array(
            'Content-Type: text/xml;charset=UTF-8',
			"Authorization: Basic {$auth}",
            'Content-Length: ' . strlen($xml_data),
			'Keep-Alive: timeout=600',
			'Connection: Keep-Alive',
			'Accept-Encoding: gzip,deflate'				
        );

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);		
$response = curl_exec($ch);
echo $response;
curl_close($ch);


sleep(2);