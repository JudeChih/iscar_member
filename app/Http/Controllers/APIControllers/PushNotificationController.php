<?php

namespace App\Http\Controllers\APIControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Mail;

class PushNotificationController extends Controller {

    function pushnotification() {
        $functionName = 'pushnotification';
        $inputString = Input::All();
        $inputData = \App\Library\CommonTools::ConvertStringToArray($inputString);
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        $resultString;
        $resultData = null;
        $messageCode = null;

        try {
            if (! is_array($inputData['md_id']) ) {
                if (!$this->StartPush($inputData['md_id'])) {
                    //$messageCode = '999999999';
                    //throw new \Exception($messageCode);
                }
            } else {
               foreach ( $inputData['md_id'] as $value ) {
                  if (!$this->StartPush($value)) {
                     //$messageCode = '999999999';
                     //throw new \Exception($e);
                  }
               }
            }
            $messageCode = '000000000';
        } catch(\Exception $e) {
            if (is_null($messageCode)) {
                \App\Library\CommonTools::writeErrorLogByException($e);
            }
        }
        //回傳值
        $resultArray = \App\Library\CommonTools::ResultProcess($messageCode, $resultData);
        \App\Library\CommonTools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);
        $result = [ $functionName . 'result' => $resultArray ];
        return $result;

    }

    public static function StartPush($md_id) {
        $memberdata = new \App\Repositories\MemberDataRepository;
        $message = '您有新訊息，請查看isCar收件匣。';
        $queryData =  $memberdata->GetData_ByMDID($md_id);
        if ($queryData[0]['mur_systemtype'] == 0) {
            \App\Library\CommonTools::Push_Notification_GCM($queryData[0]['mur_gcmid'], $message);
        }
        if ($queryData[0]['mur_systemtype'] == 1) {
            if (!\App\Library\CommonTools::Push_Notification_APNS($queryData[0]['mur_gcmid'], $message)) {

            }
        }
    }


}
