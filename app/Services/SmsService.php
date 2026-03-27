<?php

namespace App\Services;

class SmsService
{
    public function sendSms($phone, $otp, $type)
    {
        // ✅ Approved Credentials
        $apiKey   = urlencode("ViT/5FFilnTkIXIW6Fw3Qa7Vnc4J7Rqk3B6ewdzxWRs=");
        $clientId = "47608d51-c271-46b0-b49a-cc9a594356e3";
        $senderId = "CANNTM"; // ✅ MUST match approved sender ID
        $peId     = "1701176078013930795"; // ✅ Your approved PE ID

        // ✅ Remove leading 0 if any
        $phone = ltrim($phone, "0");

        // ❗ DO NOT manually add 91 if auto country code enabled in panel

        // ---------------- MESSAGE TYPES ----------------

        if ($type == 'register_otp') {

            $messageText = "Your Register OTP is " . $otp . " valid for 10 minutes. Thanks for registering with CANNTUM .Visit www.canntumemporium.com to Explore our Products";
            $templateId  = "1707176303005316005";

        } elseif ($type == 'forget_password') {

            $messageText = "Hi, your forgot password request has been processed. Your temporary password is " . $otp . ". Please visit canntumemporium.com to log in and update your password.CANNTUM";
            $templateId  = "1707176336289639793";

        } elseif ($type == 'order_confirmed') {

            
            $messageText = "Order Confirmed.! Thank you for shopping with us. Your order ID is " . $otp . ". For more amazing products, please visit canntumemporium.com.CANNTUM";
            $templateId  = "1707177367247681501"; 
        } else {
            return false;
        }

        $message = urlencode($messageText);

        // ---------------- API URL ----------------

        $url = "http://139.99.131.165/api/v2/SendSMS"
            . "?SenderId={$senderId}"
            . "&Message={$message}"
            . "&MobileNumbers={$phone}"
            . "&PrincipleEntityId={$peId}"
            . "&TemplateId={$templateId}"
            . "&ApiKey={$apiKey}"
            . "&ClientId={$clientId}";

        // ---------------- CURL ----------------

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            return false;
        }

        curl_close($ch);

        $json = json_decode($response, true);
        // dd($json);
        return isset($json['Data'][0]['MessageErrorDescription']) &&
               $json['Data'][0]['MessageErrorDescription'] === "Success";
    }
}
