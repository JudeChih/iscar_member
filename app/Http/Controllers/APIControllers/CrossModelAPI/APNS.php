<?php

namespace App\Http\Controllers\APIControllers\CrossModelAPI;

use Illuminate\Support\Facades\Input;

/** shopqueuenoshow	商家設置未到用戶為失約用戶 * */
class APNS {
   public static function apns() {
        $functionName = 'apns';
        $inputString = Input::All();
        $inputData = \App\Library\CommonTools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try{
            if( is_null($inputData['message']) || mb_strlen($inputData['message']) == 0 ) {
                $message = 'test!!whats up bro!!!!';
            } else {
                $message = $inputData['message'];
            }

            if( is_null($inputData['md_id']) || mb_strlen($inputData['md_id']) == 0 ) {
                $md_id = '5C955FAA-E977-41A4-AB33-01C0CBC8428E';
            } else {
                $md_id = $inputData['md_id'];
            }
            $md_r = new \App\Repositories\MemberDataRepository;
            $queryData =  $md_r->GetData_ByMDID($md_id);
            $id = $queryData[0]['mur_gcmid'];
            if(!\App\Library\CommonTools::Push_Notification_APNS($id,$message)) {
                throw new \Exception($messageCode);
            }
            $messageCode = '000000000';
        } catch(\Exception $e){
            if (is_null($messageCode)) {
                $messageCode = '999999999';
                \App\Library\CommonTools::writeErrorLogByException($e);
            }
        }
        $resultArray = \App\Library\CommonTools::ResultProcess($messageCode, $resultData);
        \App\Library\CommonTools::WriteExecuteLogGetId($functionName, $inputString, json_encode($resultArray), $messageCode, $jio_id);
        $result = [$functionName . 'result' => $resultArray];
        return $result;
   }
}