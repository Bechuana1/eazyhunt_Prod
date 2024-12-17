<?php
//INCLUDE THE ACCESS TOKEN FILE
include './access_token.php';
require '../config/config.php';

$responce = [];
$currentTime = new DateTime('now', new DateTimeZone('Africa/Nairobi'));

date_default_timezone_set('Africa/Nairobi');
$Key = "NmE5MGYxYzIyZThkMzI4MTlkZjMxNDVjM2I4NTdmYjFlMTk0ZmFiNA==";
$processrequestUrl = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
$callbackurl = 'https://eazyhunt.co.ke/STK/callback.php';
$passkey = "d04b8e1e440afd629b46048f0f6bc8e444fe42fa19a2e5002aed6f6d1c5e0d33"; 
$BusinessShortCode = 6696515;
$Timestamp = date('YmdHis');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Get phone number and amount from the form
    $phone = isset($_POST['phone_number']) ? $_POST['phone_number'] : null;
    $money = isset($_POST['amount']) ? $_POST['amount'] : null;

  // ENCRIPT  DATA TO GET PASSWORD
      $Password = base64_encode($BusinessShortCode . $passkey . $Timestamp);
      $PartyA = $phone;
      $PartyB = 4086218;
      $AccountReference = 'Eazy Hunt';
      $TransactionDesc = 'Subscription Service';
      $Amount = $money;
      $stkpushheader = ['Content-Type:application/json', 'Authorization:Bearer ' . $access_token];
}
//INITIATE CURL
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $processrequestUrl);
curl_setopt($curl, CURLOPT_HTTPHEADER, $stkpushheader); //setting custom header
$curl_post_data = array(
  //Fill in the request parameters with valid values
  'BusinessShortCode' => $BusinessShortCode,
  'Password' => $Password,
  'Timestamp' => $Timestamp,
  'TransactionType' => 'CustomerBuyGoodsOnline',
  'Amount' => $Amount,
  'PartyA' => $PartyA,
  'PartyB' => $PartyB,
  'PhoneNumber' => $PartyA,
  'CallBackURL' => $callbackurl,
  'AccountReference' => $AccountReference,
  'TransactionDesc' => $TransactionDesc
);

$data_string = json_encode($curl_post_data);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
$curl_response = curl_exec($curl);
//ECHO  RESPONSE
$data = json_decode($curl_response);

// echo $data;
$CheckoutRequestID = isset($data->CheckoutRequestID) ? $data->CheckoutRequestID : null;
$ResponseCode = isset($data->ResponseCode) ? $data->ResponseCode : null;

if ($ResponseCode == "0") {
  
    //   $_SESSION['message'] = 'Payment accepted for processing';
    // $secretKey = $Key;
    // $encryptedData = openssl_encrypt($plaintext, 'aes-256-cbc', $secretKey, OPENSSL_RAW_DATA, openssl_random_pseudo_bytes(16));
    
  
    $plaintext = $CheckoutRequestID;

    $expiryTime = 60 * 60 * 24 * 3; // Default expiry for 3 days
    if ($Amount == 1000) {
        $expiryTime = 60 * 60 * 24 * 7; // Set to 7 days for amount 1000
    }
    
    $absoluteExpiryTime = time() + $expiryTime; // Calculate actual expiry timestamp
    
    setcookie(
        "premium_access",
        $plaintext . ':' . $absoluteExpiryTime, // Combine data and expiry time
        $absoluteExpiryTime,
        "/", // Set path to root folder
        false, // Consider enabling Secure flag if using HTTPS
        false // Consider enabling HttpOnly flag for added security
    );

    $query_count = "SELECT COUNT(*) AS device_count FROM paid_devices WHERE CheckoutRequestID = :checkout_request_id";
    $stmt_count = $connect->prepare($query_count);
    $stmt_count->bindParam(':checkout_request_id', $CheckoutRequestID);
    $stmt_count->execute();
    $result_count = $stmt_count->fetch(PDO::FETCH_ASSOC);
    $device_count = $result_count['device_count'];

    if ($device_count < 3) {
        // Check if the checkout request ID already exists
        $query_check = "SELECT COUNT(*) AS existing_count FROM paid_devices WHERE CheckoutRequestID = :checkout_request_id";
        $stmt_check = $connect->prepare($query_check);
        $stmt_check->bindParam(':checkout_request_id', $CheckoutRequestID);
        $stmt_check->execute();
        $result_check = $stmt_check->fetch(PDO::FETCH_ASSOC);
        $existing_count = $result_check['existing_count'];

         if ($existing_count == 0) {
            // If the checkout request ID does not exist, proceed to insert the new device
            $unique_identifier = getUniqueDeviceIdentifier(); // Get unique device identifier

            // Insert the device into the database
            $query_insert = "INSERT INTO paid_devices (phone_number, CheckoutRequestID, unique_identifier, payment_id) VALUES (:phone_number, :checkout_request_id, :unique_identifier, :payment_id)";
            $stmt_insert = $connect->prepare($query_insert);
            $stmt_insert->bindParam(':phone_number', $_POST['phone_number']); // Assuming you receive this from your form or request
            $stmt_insert->bindParam(':checkout_request_id', $CheckoutRequestID);
            $stmt_insert->bindParam(':unique_identifier', $unique_identifier);
            $stmt_insert->bindParam(':payment_id', $_POST['payment_id']); // Assuming you receive this from your form or request
            $stmt_insert->execute();

            $responce['success'] ="Device added successfully.";
        } else {
            $responce['error']="Checkout request ID already exists in the database.";
        }
    } else {
         $responce['error'] = "Maximum devices limit reached for this checkout request.";
    }
 
 
 header('Location: ../index.php');
  
}
if(empty($ResponseCode)){
  $_SESSION['error'] = 'unknown error occured';
  header('Location: ../index.php');
}