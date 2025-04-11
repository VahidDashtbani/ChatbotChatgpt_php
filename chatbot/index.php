<?php
require('config.php'); //database

function callChatbotAPI($apiKey, $messages)
{
    $url = "your_url";
    $data = json_encode($messages);
    $headers = [
        "Content-Type: application/json",
        "one-api-token: " . $apiKey
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        curl_close($ch);
        return "cURL Error: " . $error_msg;
    }

    curl_close($ch);
    return $response;
}

$apiKey = "apikey";

$ques = isset($_POST['ques']) ? $_POST['ques'] : '';
$fname = isset($_POST['fname']) ? $_POST['fname'] : '';

$messages = [
    ["role" => "user", "content" => "!سلاام!"],
    ["role" => "assistant", "content" => "سلام چه مشکلی پیش آمده است؟ کمکی از دستم بر میاد?"],
];

if (!empty($ques) && !empty($fname)) {
    $messages[] = ["role" => "user", "content" => $ques];
    $response = callChatbotAPI($apiKey, $messages);

    $responseData = json_decode($response, true);
    if (isset($responseData['result'][0])) {
        echo "<div style='direction: rtl; text-align: right; background: #f9f9f9; color: #333; padding: 15px; border-radius: 10px; box-shadow: 0px 5px 15px rgba(0,0,0,0.1); font-size: 18px; line-height: 1.8;'>"
             . $responseData['result'][0] . "</div>";
    } else {
        echo "<p style='color: red;'>خطایی در دریافت پاسخ از سرور رخ داده است.</p>";
    }
} else {
    echo "<h3 style='color: red;'>your data is poor</h3>";
}

$name = $_POST['fname'];
$question = $_POST['ques'];

if (!empty($name) && !empty($question)) {
    $db->query("INSERT INTO `users` (`question`,`name`) VALUES ('$question','$name');");
}

echo "<div style='margin-top: 20px; text-align: center;'>
        <a href='REDIRECT_URL' 
           style='text-decoration: none; background: #ff4e50; color: white; padding: 12px 20px; border-radius: 8px; font-size: 16px; font-weight: bold; display: inline-block; transition: 0.3s; box-shadow: 0 5px 15px rgba(255, 78, 80, 0.5);'>
           ⬅ Back
        </a>
      </div>";
?>
