<?php
/**
 * Created by PhpStorm.
 * User: cristiprg
 * Date: 25-3-16
 * Time: 13:13
 */

// Method: POST, PUT, GET etc
// Data: array("param" => "value") ==> index.php?param=value
// http://stackoverflow.com/questions/9802788/call-a-rest-api-in-php
function CallAPI($method, $url, $data = false, $contentType = "application/json")
{
    $curl = curl_init();

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
    /*
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "username:password");
    */
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER,array('Content-Type: '. $contentType ));

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}