<?php
/**
 * Created by Touqeer Shafi.
 * Date: 4/15/19
 * Time: 4:03 PM
 */

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class Pusher
{


    private $payload;

    // FCM
    private $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
    private $apnsUrl = 'ssl://gateway.push.apple.com:2195';


    public function setAndroidPayload($payload)
    {
        $this->payload = ['data' => json_decode($payload)];
        return $this;
    }

    public function setIosPayload($payload)
    {
        $this->payload = json_decode($payload);
        return $this;
    }

    public function sendAndroid($device_token, $api_key)
    {

        try {

            $this->payload['registration_ids'] = [$device_token];
            $client = new Client(['base_uri' => $this->fcmUrl]);
            $response = $client->request('POST', '', [RequestOptions::VERIFY => false, RequestOptions::JSON => $this->payload, RequestOptions::HTTP_ERRORS => true, RequestOptions::HEADERS => ['Content-Type' => 'application/json', 'Authorization' => sprintf('key=%s', $api_key),]]);

            return ['status' => true, 'message' => $response->getBody()->getContents()];

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return ['status' => false, 'error' => $e->getResponse()->getBody()->getContents()];
        }

    }

    public function sendIOS($token, $pemFile, $passphrase = '123456')
    {
        try {
            $ctx = stream_context_create();
            $errorStr = '';
            stream_context_set_option($ctx, 'ssl', 'local_cert', $pemFile);
            stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
            $fp = stream_socket_client($this->apnsUrl, $err, $errorStr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

            if (!$fp) {
                return ['status' => false, 'error' => "Can't Connect to APNS"];
            }
            $payload = json_encode($this->payload);
            $msg = chr(0) . pack('n', 32) . pack('H*', $token) . pack('n', strlen($payload)) . $payload;
            $result = fwrite($fp, $msg, strlen($msg));
            fclose($fp);
            return ['status' => true, 'message' => 'Payload has been pushed'];
        } catch (Exception $e) {
            return ['status' => false, 'Some thing went wrong'];
        }

    }

}