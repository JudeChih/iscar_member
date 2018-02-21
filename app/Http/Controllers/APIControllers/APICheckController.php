<?php

namespace App\Http\Controllers\APIControllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class APICheckController extends Controller {

    function APICheck() {
        $functionName = 'apicheck';
        $inputString = Input::All();
        $inputData = \App\Library\CommonTools::ConvertStringToArray($inputString);
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        $resultString;
        $resultData = null;
        $messageCode = null;

        try {
            if (!empty($_SERVER["HTTP_CLIENT_IP"])){
                  $ip = $_SERVER["HTTP_CLIENT_IP"];
            } else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
                  $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else {
                  $ip = $_SERVER["REMOTE_ADDR"];
            }

            $datenow = new \Datetime();
            $stringdate =  $datenow-> format('m-d H:i');
            $resultData = array('ip' => $ip, 'date' => $stringdate );
            $messageCode = '000000000';
        } catch (\Exception $e) {
            if ($messageCode == null) {
                \App\Library\CommonTools::writeErrorLogByException($e);
                $messageCode = '999999999';
            }
        }
        //回傳值
        $resultArray = \App\Library\CommonTools::ResultProcess($messageCode, $resultData);
        \App\Library\CommonTools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);
        $result = [ $functionName . 'result' => $resultArray ];
        return $result;

    }
}
