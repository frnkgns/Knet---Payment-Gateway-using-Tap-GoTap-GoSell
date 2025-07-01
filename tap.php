<?php

$MerchantID = "merhantId here" //sample 982374
$liveTestKey = 'you live sk_test key';   //sample sk_testksdfo987234

$trasactID = 'TRNSCT' . strtoupper(uniqid());    //this is optional for generating random transact ID you need to put it on ordering a product api
$OrderRefNo = 'ORD' . strtoupper(uniqid());      //this one as well

$Amount = $_GET['total_price'] ?? '50.000';          //just the details
$Amount = number_format((float)$Amount, 3, '.', '');
$FirstName = $_GET['first_name'] ?? 'John';
$MiddleName = $_GET['middle_name'] ?? '';
$LastName = $_GET['last_name'] ?? 'Doe';
$Email = $_GET['email'] ?? 'john@example.com';
$Phone = $_GET['phone'] ?? '55555555';

//this is the default code use for KNET transaction
$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "https://api.tap.company/v2/authorize/",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode([
    'amount' => $Amount,
    'currency' => 'KWD',
    'customer_initiated' => true,
    'threeDSecure' => true,
    'save_card' => false,
    'description' => 'Test Description',
    'metadata' => [
        'udf1' => 'Metadata 1'
    ],
    'receipt' => [
        'email' => true,
        'sms' => true
    ],
    'reference' => [
        'transaction' => $trasactID,
        'order' => $OrderRefNo
    ],
    'customer' => [
        'first_name' => $FirstName,
        'middle_name' => $MiddleName,
        'last_name' => $LastName,
        'email' => $Email,
        'phone' => [
                'country_code' => '965',
                'number' => $Phone
        ]
    ],
    'merchant' => [
        'id' => $MerchantID
    ],
    'source' => [
        'id' => 'src_kw.knet'
    ],
    'post' => [
        'url' => 'https://kw.inchrist.co.in/gotap/post-handler.php'
    ],
    'redirect' => [
        'url' => 'https://kw.inchrist.co.in/gotap/redirect-handler.php?orderId=' . $OrderRefNo
    ]
  ]),
  CURLOPT_HTTPHEADER => [
    "Authorization: Bearer $liveTestKey",      //live key here
    "accept: application/json",
    "content-type: application/json"
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
    $responseData = json_decode($response, true);

    if (isset($responseData['transaction']['url'])) {
        header("Location: " . $responseData['transaction']['url']); // Directly use the URL
        exit;
    } else {
        echo "Payment failed or could not be initiated. Please check details.";
        error_log("Tap API Error or Unexpected Response: " . $response);
    }
}
