<?php 

class Restclient{

    function login($arrayParams=array()) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://webapi.r1.rpost.net/token");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($arrayParams));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response,true);

        $result = "";
        if($response){
            if(isset($response['access_token'])) {
                $result = $response['access_token'];
            }
        }

        return $result;
    }

    function messageStatus($token,$StartDate,$EndDate,$account) {

        $params= array(
                "SenderType" => "4",
                "SenderTypeValue" => $account,
                "FromDate" => "{$StartDate}",
                "ToDate" => "{$EndDate}",
                "DateRangeType" => "11",
                "ServiceFeature" => "0",
                "DeliveryStatusType" => "1",
                "MessageId" => "",
                "RecipientAddress" => "",
                "RecipientDomain" => "",
                "ReportOutputType" => "json"
        );

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://webapi.r1.rpost.net/api/Reports/UsageReport',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>json_encode($params),
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$token
          ),
        ));

        $response = curl_exec($curl);

        $response = json_decode($response,true);

        $result = "";
        if($response)
        {
            if(!isset($response['MessageId']) && !isset($response['DeliveryStatus'])) {
                $result = $response;
            }
        }
        curl_close($curl);
        return $result;

    }

    /*function trackingId($token,$TrackingId) {

        if(isset($token) AND isset($TrackingId)) {
            $date = date('Ymd');
    
            $filename = "Receipt_".$TrackingId."_".$date.".zip";
    
            header("Content-Type: application/x-gzip"); 
            header("Content-disposition: attachment; filename=$filename");
    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://webapi.r1.rpost.net/api/v1/Receipt/". $TrackingId);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Transfer-Encoding: application/json; charset=UTF-8', 'Authorization: Bearer '.$token));
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            echo $response;

            return $response;
        }
    }


    function acusePDF($token,$MsgId) {

        if(isset($token) AND isset($MsgId)) {
    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://checkout-rpost.cs170.force.com/public/services/apexrest/v1/receipt/". $MsgId);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=UTF-8', 'x-auth: Bearer '.$token));
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            return $response;
        }
    }*/


}