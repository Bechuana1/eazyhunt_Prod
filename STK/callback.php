<?php
include '../config/config.php';
$currentTime = new DateTime('now', new DateTimeZone('Africa/Nairobi'));


 //Eazy-Hunt

header("Content-Type: application/json");
$stkCallbackResponse = file_get_contents('php://input');
$logFile = "Mpesastkresponse.json";
$log = fopen($logFile, "a");
fwrite($log, $stkCallbackResponse);
fclose($log);

$data = json_decode($stkCallbackResponse);

$MerchantRequestID = $data->Body->stkCallback->MerchantRequestID;
$CheckoutRequestID = $data->Body->stkCallback->CheckoutRequestID;
$ResultCode = $data->Body->stkCallback->ResultCode;
$ResultDesc = $data->Body->stkCallback->ResultDesc;
$Amount = $data->Body->stkCallback->CallbackMetadata->Item[0]->Value;
$TransactionId = $data->Body->stkCallback->CallbackMetadata->Item[1]->Value;
$UserPhoneNumber = $data->Body->stkCallback->CallbackMetadata->Item[4]->Value;
//CHECK IF THE TRASACTION WAS SUCCESSFUL 
if ($ResultCode == 0) {
    // Prepare the SQL statement with placeholders for security
    
   $sql = "INSERT INTO `payments` (`MerchantRequestID`, `CheckoutRequestID`, `ResultCode`, `ResultDesc`,`Amount`, `TransactionId`, `UserPhoneNumber`, `CreatedOn`) 
   VALUES (:MerchantRequestID, :CheckoutRequestID, :ResultCode, :ResultDesc,:Amount, :MpesaReceiptNumber, :PhoneNumber, :CreatedOn)";

    try {
        // Create a prepared statement object
        $stmt = $connect->prepare($sql);

        $stmt->bindParam(':MerchantRequestID', $MerchantRequestID);
        $stmt->bindParam(':CheckoutRequestID', $CheckoutRequestID);
        $stmt->bindParam(':ResultCode', $ResultCode);
        $stmt->bindParam(':ResultDesc', $ResultDesc);
        $stmt->bindParam(':Amount', $Amount);
        $stmt->bindParam(':MpesaReceiptNumber', $TransactionId);
        $stmt->bindParam(':PhoneNumber', $UserPhoneNumber);
        $stmt->bindParam(':CreatedOn', $currentTime->format('Y-m-d H:i:s'));
        

        // Execute the prepared statement
        $stmt->execute();


        
            
            
    } catch (PDOException $e) {
        echo "Error inserting data: " . $e->getMessage();
    }
} else {
    echo "Transaction failed (ResultCode: $ResultCode)"; // Provide a meaningful error message
}