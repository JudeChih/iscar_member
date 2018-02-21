<?php

namespace App\Http\Controllers\APIControllers;

use Illuminate\Support\Facades\Input;

class PushNotification {

    /**
     * 進行推撥
     * @param  [string] $modacc      [模組帳號]
     * @param  [string] $modvrf      [模組驗證碼]
     * @param  [string] $sat         [用戶登入存取憑證]
     * @param  [array]  $md_id_array [要進行推播的會員]
     * @param  [string] $message     [推播信息]
     * @param  [string] $title       [推播標題]
     * @param  [string] $target      [推播目標 1:站內信 2:店家 3:商品]
     * @param  [json]   $iscar_push  [推播Data]
     */
    public function push_notification() {

        $functionName = 'push_notification';
        $inputString = Input::All();
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try {
            // 轉換成陣列並檢查
            $inputData = $this->convertAndCheckApiInput($inputString);
            if (!is_array($inputData)) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            // 模組身分驗證
            if(!\App\Library\CommonTools::checkModuleAccount($inputData['modacc'],$inputData['modvrf'])){
                $messageCode = '999999961';
                throw new \Exception($messageCode);
            }
            // 驗證SAT
            if(!$satData = \App\Library\CommonTools::CheckServiceAccessToken($inputData['sat'])){
                $messageCode = '999999960';
                throw new \Exception($messageCode);
            }
            // 檢查所有md_id
            if(!$pushId = $this->checkMdIdArray($inputData['md_id_array'])){
                $messageCode = '010180001';
                throw new \Exception($messageCode);
            }

            // 開始推撥
            if(!$this->startPush($pushId,$inputData)){
                $resultData['pushId'] = $pushId;
                $messageCode = '010180002';
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
                if(count($data) != 0 || !is_null($data)){
                    if($data->mur_systemtype == 0){
                        array_push($android,$data['mur_gcmid']);
                    }
                    if($data->mur_systemtype == 1){
                        array_push($ios,$data['mur_gcmid']);
                    }
                }
                // if(count($data) == 0 || is_null($data)){
                //     return false;
                // }
                
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
     * @param  [array]  $pushId   [要推播的gcmid]
     * @param  [array]  $pushData [要推播的data]
     */
    public function startPush($pushId,$pushData){
        try {
            // 檢查傳入值，如果沒有帶值要設定預設值
            if($pushData['target'] == 1){
                $arraydata['message'] = '您有新訊息，請查看isCar收件匣。';
                $arraydata['title'] = '就是行';
            }else{
                if(strlen($pushData['message']) > 50){
                    $message = mb_substr( $pushData['message'],0,50,"utf-8");
                    $arraydata['message'] = $message."...";
                }else{
                    $arraydata['message'] = $pushData['message'];
                }
                $arraydata['title'] = $pushData['title'];
            }
            $arraydata['iscar_push'] = $pushData['iscar_push'];
            // $arraydata['iscar_system'] = $pushData['iscar_system'];

            if(count($pushId['android']) > 0){
                // 設定聲音檔
                switch ($pushData['target']) {
                    case 1:
                        $arraydata['sound'] = 'lock';
                        break;
                    case 2:
                        $arraydata['sound'] = 'lock';
                        break;
                    case 3:
                        $arraydata['sound'] = 'lock';
                        break;
                    case 4:
                        $arraydata['sound'] = 'lock';
                        break;
                }

                // 做推播
                $android = $pushId['android'];
                if(count($android) > 50){
                    $android = array_chunk($android,50);
                    for($i=0;$i<count($android);$i++){
                        if(!\App\Library\CommonTools::Push_Notification_GCM($android[$i],$arraydata)){
                            return false;
                        }
                    }
                    return true;
                }
                if(!\App\Library\CommonTools::Push_Notification_GCM($android,$arraydata)){
                    return false;
                }
            }
            if(count($pushId['ios']) > 0){
                // 設定聲音檔
                switch ($pushData['target']) {
                    case 1:
                        $arraydata['sound'] = 'lock.mp3';
                        break;
                    case 2:
                        $arraydata['sound'] = 'lock.mp3';
                        break;
                    case 3:
                        $arraydata['sound'] = 'lock.mp3';
                        break;
                    case 4:
                        $arraydata['sound'] = 'lock.mp3';
                        break;
                }

                // 做推播
                $ios = $pushId['ios'];
                foreach ($ios as $val) {
                    if(!\App\Library\CommonTools::Push_Notification_APNS($val,$arraydata)){
                        return false;
                    }
                }
                // if(count($ios) > 50){
                //     $ios = array_chunk($ios,50);
                //     for($i=0;$i<count($ios);$i++){
                //         if(!\App\Library\CommonTools::Push_Notification_APNS($ios[$i],$arraydata)){
                //             return false;
                //         }
                //     }
                //     return true;
                // }
                // if(!\App\Library\CommonTools::Push_Notification_APNS($ios,$arraydata)){
                //     return false;
                // }
            }
            return true;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }


}
