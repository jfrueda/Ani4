<?php 

require_once('connection.php');
require_once('restclient.php');

$rest = new Restclient();

$params = array(
    'grant_type' => "password",
    'username' => "superargo@supersalud.gov.co",
    'password' => "Super_2021"
);

$token = $rest->login($params);

$trackingId1=$_GET['t'];
$response1 = $rest->trackingId($token,$trackingId1);

if(isset($token)) {

        $response1 = $rest->trackingId($token,$trackingId1);
        echo $response1;
    }


?>