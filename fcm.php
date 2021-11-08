<?php
require __DIR__.'/vendor/autoload.php';

use Firebase\Auth\Token\Exception\InvalidToken;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\CloudMessage;

include "token.php";
$factory = (new Factory)->withServiceAccount('../letsgodil-admin/lets-go-dil-firebase-adminsdk-8j1sk-900215dc14.json');

$deviceToken = "ewH3mC8qRSKzBwfQBBguyV:APA91bEDeoYZupWDzm1vUsNIh8tCAltlIQChiunVVyh_Hedi4iLpkeoQ3fEESngZVydrExHnbfa0dk3O-onlX2Xq3YbTvciLpUM0SOjRYYHL2aQSkoODZi2NisGBoP5ARvW6mtrPr3pT";
$message = CloudMessage::withTarget("token", $deviceToken)
    ->withNotification(Notification::create('Title', 'Body'));

$messaging = $factory->createMessaging();
var_dump ($messaging->send($message));
