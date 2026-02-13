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


if(isset($token)) {
    $MsgId = $_GET['id'];
    $response = $rest->acusePDF($token,$MsgId);
 
    if(isset($response)) {

        $obj = json_decode($response);
        $linkpdf=$obj->{'download'};
        header('Location: '.$linkpdf);

   }

}
?>