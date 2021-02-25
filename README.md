# BRIVA-API-Codeigniter
BRI Virtual Account API 

How to create ClientID and ClientSecret 

## Sign Up
link : https://developers.bri.co.id/user/register 

## Create Apps
after register you can access and create apps 
link : https://developers.bri.co.id/user/me/apps 

## Success
you'll get clientID and clientSecret.

#defaultCode 

  var $clientID     = "Consumer_Key";
	var $clientSecret = "Consumer_Secret";
	var $endpoint     = "https://sandbox.partner.api.bri.co.id/oauth/client_credential/accesstoken?grant_type=client_credentials";
	
	var $institutionCode = "J104408"; //This institution code will be given by BRI
	var $brivaNo = "77777"; // BRIVA number unique to your institution
