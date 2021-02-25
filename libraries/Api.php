<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api
{
	//	date_default_timezone_set("Asia/Makassar");
	
	var $clientID     = "CONSUMER_KEY";
	var $clientSecret = "CUNSUMER_SECRET";
	var $endpoint     = "https://sandbox.partner.api.bri.co.id/oauth/client_credential/accesstoken?grant_type=client_credentials";
	var $institutionCode = "J104408"; //This institution code will be given by BRI
	var $brivaNo = "77777"; // BRIVA number unique to your institution
	var $expiredDate = "2021-03-27 23:59:00"; //static
	
	
	private $ci;
	
	function __construct()
	{
		$this->ci =& get_instance();
	}
	

	/* Generate Token */
	function BRIVAgenerateToken($client_id, $secret_id) {
		$url = $this->endpoint;
		$data = "client_id=".$client_id."&client_secret=".$secret_id;
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
		
		$result = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		$json = json_decode($result, true);
		$accesstoken = $json['access_token'];

		return $accesstoken;
	}

	/* Generate signature */
	function BRIVAgenerateSignature($path, $verb, $token, $timestamp, $payload, $secret) {
		echo $payloads = "path=$path&verb=$verb&token=Bearer $token&timestamp=$timestamp&body=$payload";
		$signPayload = hash_hmac('sha256', $payloads, $secret, true);
		return base64_encode($signPayload);
	}


	/* CURL Custom*/
	function curlHeader($urlPost,$payload,$datas,$path,$verb)
	{
		$client_id = $this->clientID;
		$secret_id = $this->clientSecret;

		$timestamp = gmdate("Y-m-d\TH:i:s.000\Z");
		$secret = $secret_id;

		$token = $this->BRIVAgenerateToken($client_id, $secret_id);
		$base64sign = $this->BRIVAgenerateSignature($path, $verb, $token, $timestamp, $payload, $secret);
	

		if($verb == "GET" || $verb == "DELETE") {
			$request_headers = array(
				"Authorization:Bearer " . $token,
				"BRI-Timestamp:" . $timestamp,
				"BRI-Signature:" . $base64sign,
			);
		}else{
			$request_headers = array(
				"Content-Type:"."application/json",
				"Authorization:Bearer " . $token,
				"BRI-Timestamp:" . $timestamp,
				"BRI-Signature:" . $base64sign,
			);
		}


		$chPost = curl_init();
		curl_setopt($chPost, CURLOPT_URL, $urlPost);
		curl_setopt($chPost, CURLOPT_HTTPHEADER, $request_headers);
		curl_setopt($chPost, CURLOPT_CUSTOMREQUEST, $verb); 
		curl_setopt($chPost, CURLOPT_POSTFIELDS, $payload);
		curl_setopt($chPost, CURLINFO_HEADER_OUT, true);
		curl_setopt($chPost, CURLOPT_RETURNTRANSFER, true);

		$resultPost = curl_exec($chPost);
		$httpCodePost = curl_getinfo($chPost, CURLINFO_HTTP_CODE);
		curl_close($chPost);

		return $resultPost;
	}


	/* Create BRIVA  */
	function create() {

		$institutionCode = $this->institutionCode;
		$brivaNo = $this->brivaNo;
		$custCode = "123451234";
		$nama = "Robi Kurniawan";
		$amount="100000";
		$keterangan="Create BRIVA Account";
		$expiredDate = $this->expiredDate;

		$datas = array(
			'institutionCode' => $institutionCode ,
			'brivaNo' => $brivaNo,
			'custCode' => $custCode,
			'nama' => $nama,
			'amount' => $amount,
			'keterangan' => $keterangan,
			'expiredDate' => $expiredDate
		);

		$path = "/v1/briva";
		$verb = "POST";
		$payload = json_encode($datas, true);

		$urlPost ="https://sandbox.partner.api.bri.co.id/v1/briva";
		$resultPost = $this->curlHeader($urlPost,$payload,$datas,$path,$verb);
		echo $resultPost;
		return json_decode($resultPost, true);
	}


	/* get Data by CustCode BRIVA  */
	function getData() {

		$institutionCode = $this->institutionCode;
		$brivaNo = $this->brivaNo;
		$custCode = "12345123";

		$payload = NULL;
		$path = "/v1/briva/".$institutionCode."/".$brivaNo."/".$custCode;
		$verb = "GET";
	
		$urlPost ="https://sandbox.partner.api.bri.co.id/v1/briva/".$institutionCode."/".$brivaNo."/".$custCode;
		$resultPost = $this->curlHeader($urlPost,$payload,'',$path,$verb);
		echo "<br/> <br/>";

		echo $resultPost;
		return json_decode($resultPost, true);
	}


	/* get Status Payment  */
	function getStatus() {

		$institutionCode = $this->institutionCode;
		$brivaNo = $this->brivaNo;
		$custCode = "12345123";

		$payload = NULL;
		$path = "/v1/briva/status/".$institutionCode."/".$brivaNo."/".$custCode;
		$verb = "GET";
	
		$urlPost ="https://sandbox.partner.api.bri.co.id/v1/briva/status/".$institutionCode."/".$brivaNo."/".$custCode;
		
		$resultPost = $this->curlHeader($urlPost,$payload,'',$path,$verb);
		echo "<br/> <br/>";

		echo $resultPost;
		return json_decode($resultPost, true);
	}


	/* Update Payment Status  */
	function updateBayar() {

		$institutionCode = $this->institutionCode;
		$brivaNo = $this->brivaNo;
		$custCode = "12345123";
		$statusBayar = "Y";

		$datas = array(
			'institutionCode' => $institutionCode ,
			'brivaNo' => $brivaNo,
			'custCode' => $custCode,
			'statusBayar' => $statusBayar
		);

		$payload = json_encode($datas, true);
    	$path = "/v1/briva/status";
		$verb = "PUT";

		$urlPost ="https://sandbox.partner.api.bri.co.id/v1/briva/status";
		$resultPost = $this->curlHeader($urlPost,$payload,$datas,$path,$verb);
		echo $resultPost;
		return json_decode($resultPost, true);
	}


	/* Update BRIVA Data */
	function updateVa() {

		$institutionCode = $this->institutionCode;
		$brivaNo = $this->brivaNo;
		$custCode = "12345123";
		$nama = "Aulia Apriliani";
		$amount="100000";
		$keterangan="Create BRIVA Account";
		$expiredDate = $this->expiredDate;
		

		$datas = array(
			'institutionCode' => $institutionCode ,
			'brivaNo' => $brivaNo,
			'custCode' => $custCode,
			'nama' => $nama,
			'amount' => $amount,
			'keterangan' => $keterangan,
			'expiredDate' => $expiredDate
		);

		$payload = json_encode($datas, true);
    	$path = "/v1/briva";
		$verb = "PUT";

		$urlPost ="https://sandbox.partner.api.bri.co.id/v1/briva";

		$resultPost = $this->curlHeader($urlPost,$payload,$datas,$path,$verb);
		echo $resultPost;
		return json_decode($resultPost, true);
	}


	/* Delete BRIVA  */
	function delete() {

		$institutionCode = $this->institutionCode;
		$brivaNo = $this->brivaNo;
		$custCode = "12345123";
		
		$datas = array(
			'institutionCode' => $institutionCode ,
			'brivaNo' => $brivaNo,
			'custCode' => $custCode
		);
		
		$payload = "institutionCode=".$institutionCode."&brivaNo=".$brivaNo."&custCode=".$custCode;
		$path = "/v1/briva";
		$verb = "DELETE";
		
		$urlPost ="https://sandbox.partner.api.bri.co.id/v1/briva";
		
		$resultPost = $this->curlHeader($urlPost,$payload,$datas,$path,$verb);
		echo $resultPost;
		return json_decode($resultPost, true);
	}


	/* Payment Report by Date */
	function getReportDate() {

		$institutionCode = $this->institutionCode;
		$brivaNo = $this->brivaNo;
		
		$startDate = "20210226"; // YYYYMMDD
		$endDate   = "20210226"; // YYYYMMDD
		// startDate and endDate should be same value
		 

		$payload = NULL;
		$path = "/v1/briva/report/".$institutionCode."/".$brivaNo."/".$startDate."/".$endDate;
		$verb = "GET";
	
		$urlPost ="https://sandbox.partner.api.bri.co.id/v1/briva/report/".$institutionCode."/".$brivaNo."/".$startDate."/".$endDate;

		$resultPost = $this->curlHeader($urlPost,$payload,'',$path,$verb);
		echo "<br/> <br/>";

		echo $resultPost;
		return json_decode($resultPost, true);
	}


	/* Payment Report by DateTime */
	function getReportDateTime() {

		$institutionCode = $this->institutionCode;
		$brivaNo = $this->brivaNo;
		
		$startDate = "2021-02-26"; // YYYY-MM-DD
		$endDate   = "2021-02-26"; // YYYY-MM-DD
		// startDate and endDate should be same value

		$startTime = "00:30";
    	$endTime = "12:30";

		$payload = NULL;
		$path = "/v1/briva/report_time/".$institutionCode."/".$brivaNo."/".$startDate."/".$startTime."/".$endDate."/".$endTime;
		$verb = "GET";
	
		$urlPost ="https://sandbox.partner.api.bri.co.id/v1/briva/report_time/".$institutionCode."/".$brivaNo."/".$startDate."/".$startTime."/".$endDate."/".$endTime;

		$resultPost = $this->curlHeader($urlPost,$payload,'',$path,$verb);
		echo "<br/> <br/>";

		echo $resultPost;
		return json_decode($resultPost, true);
	}





}