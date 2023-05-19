<?php

function get_paypal_access_token(){
	// Set up the HTTP request
$mode = get_option( 'eLS_paypal_mode', '' );
$client_id = get_option( 'eLS_paypal_client_id', '' );
$secret_id = get_option( 'eLS_paypal_secret_id', '' );

 $url =  ($mode=='sandbox') ? "https://api.sandbox.paypal.com/v1/oauth2/token" : "https://api.paypal.com/v1/oauth2/token"; // Replace with the live endpoint for production

$data = "grant_type=client_credentials";

$curl = curl_init($url);

curl_setopt($curl, CURLOPT_POST, true);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

curl_setopt($curl, CURLOPT_USERPWD, $client_id . ":" . $secret_id);

curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

// Send the HTTP request and decode the JSON response
$response = curl_exec($curl);

// Close the HTTP connection
curl_close($curl);

$response_obj = json_decode($response);
// Extract the access_token from the response object
$access_token = $response_obj->access_token;


return $access_token;




}