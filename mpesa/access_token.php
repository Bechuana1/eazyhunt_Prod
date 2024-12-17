<?php
//YOU MPESA API KEYS
$consumerKey = 'rB3Df1My17wlTatREJvcFYUdAl3WG2mTSMYr8GD0RVtuFl7g';
$consumerSecret = 'yG4ij4xkXuylMLeyXf7bTFEYHEO92NOlZFaGLa9KWkAme0AOkG2pqQlXzFzsA4oG';
//ACCESS TOKEN URL
$access_token_url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
$headers = ['Content-Type:application/json; charset=utf8'];
$curl = curl_init($access_token_url);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_HEADER, FALSE);
curl_setopt($curl, CURLOPT_USERPWD, $consumerKey . ':' . $consumerSecret);
$results = curl_exec($curl);
$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);


 $result = json_decode($results);
$access_token = $result->access_token;
//echo $access_token;
curl_close($curl);



