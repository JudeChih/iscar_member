<?php

namespace App\Http\Controllers\APIControllers;

use Illuminate\Support\Facades\Input;

class PushNotification_Test {

    /**
     * 進行推撥
     * @param  [string] $message     [推播內容]
     */
    public function push_notification_test() {

        $functionName = 'push_notification_test';
        try {
            // 開始推撥
            if(!$this->startPush($pushData)){
                $resultData['pushData'] = $pushData;
                $messageCode = '999999992';
                throw new \Exception($messageCode);
            }

            $messageCode = '000000000';
        } catch (\Exception $e) {
            if (is_null($messageCode)) {
                $messageCode = '999999999';
                \App\Library\CommonTools::writeErrorLogByException($e);
            }
        }
        //回傳值
        $resultArray = \App\Library\CommonTools::ResultProcess($messageCode, $resultData);
        \App\Library\CommonTools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);
        $result = [$functionName . 'result' => $resultArray];
        return $result;
    }

    /**
     * 檢查輸入值是否正確
     * @param type $value
     * @return boolean
     */
    public function convertAndCheckApiInput($inputString) {
        $inputData = \App\Library\CommonTools::ConvertStringToArray($inputString);

        if ($inputData == null) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'modacc', 20, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'modvrf', 0, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'sat', 0, false, false)) {
            return false;
        }
        return $inputData;
    }

    /**
     * 檢查傳入的每個md_id，並且分類android or ios
     * @param  [array] $mdIdArray [md_id的陣列]
     * @return [mixed]  mdData or false
     */
    public function checkMdIdArray(Array $mdIdArray) {
        $sat_r = new \App\Repositories\ServiceAccessTokenRepository;
        $android = [];
        $ios = [];
        try {
            foreach($mdIdArray as $val){

                $data = $sat_r->getGcmIdByMdID($val);
                if(count($data) == 0 || is_null($data)){
                    return false;
                }
                if($data->mur_systemtype == 0){
                    array_push($android,$data['mur_gcmid']);
                }
                if($data->mur_systemtype == 1){
                    array_push($ios,$data['mur_gcmid']);
                }
            }

            $pushData['android'] = $android;
            $pushData['ios'] = $ios;

            return $pushData;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

    /**
     * 區分手機系統，開始推播
     * @param  [array]  $pusData [要推播的gcmid]
     */
    public function startPush($pushData){
        try {
            if(count($pushData['android']) > 0){
                //FCM
                // $message['title'] = '您有新訊息，請查看isCar收件匣。';
                //GCM
                $message['message'] = '您有新訊息，請查看isCar收件匣。';
                // 做推播
                $android = $pushData['android'];
                if(count($android) > 50){
                    $android = array_chunk($android,50);
                    for($i=0;$i<count($android);$i++){
                        if(!\App\Library\CommonTools::Push_Notification_GCM($android[$i],$message)){
                            return false;
                        }
                    }
                    return true;
                }
                if(!\App\Library\CommonTools::Push_Notification_GCM($android,$message)){
                    return false;
                }
            }
            if(count($pushData['ios']) > 0){
                $message = '您有新訊息，請查看isCar收件匣。';
                // 做推播
                $ios = $pushData['ios'];
                foreach ($ios as $val) {
                    if(!\App\Library\CommonTools::Push_Notification_APNS($val,$message)){
                        return false;
                    }
                }
            }
            return true;
            $message['title'] = '你有新的信件';
            $android = ['cE7Hhpd4Qi8:APA91bHeUMCwIbpFiuvSYoISnL9KNoP8lFrh0JWQL93SKXTS7yV54ZCcoPpQ7VIsAdwjzjlTMBS3kLQjUdaaofOtAjZcQKVg7oUJd-GpK47GTZygBJMPlVrEKS6b0oKY2zXY4GkgzwnB'];
            \App\Library\CommonTools::Push_Notification_GCM($android,$message);
            return true;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }


}
