<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/Pusher.php';

use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();
$pushers = new Pusher();
if ($request->isMethod('post')) {
    if ($request->get('isAndroid')) {

        $android = $request->get('android');
        $output = $pushers->setAndroidPayload($android['json'])->sendAndroid($android['device_token'], $android['server_key']);

        echo json_encode($output);
    }


    if($request->get('isIOS')) {
        $ios = $request->get('ios');

        $output = $pushers->setIosPayload($ios['json'])->sendIOS(
            $ios['device_token'], $_FILES['certificate']['tmp_name']);


        echo json_encode($output);
    }
}