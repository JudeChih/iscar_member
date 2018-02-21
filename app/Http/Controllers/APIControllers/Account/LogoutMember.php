<?php

namespace App\Http\Controllers\APIControllers\Account;

use Illuminate\Support\Facades\Input;

/** �|���n�X�A�]�m�����A�Ⱦ��Ҭ����� **/
class LogoutMember {

   public function logoutmember() {
        $jwt = new \App\Services\JWTService;
        $functionName = 'logoutmember';
        $inputString = Input::All();
        $inputData = \App\Library\CommonTools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try{
            //��J��
            if(!$this->CheckInput($inputData)){
               $messageCode = '999999995';
               throw new \Exception($messageCode);
            }
            //�ˬdToken�BDeviceCode
            if(!\App\Library\CommonTools::CheckAccessTokenDeviceCode($inputData['servicetoken'], $inputData['userdevicecode'], $md_id, $messageCode)) {
               throw new \Exception($messageCode);
            }
            //�ˬdJWT�祿�C
            if( !$jwt->JWTokenVerification($inputData['api_token'], $messageCode)) {
               throw new \Exception($messageCode);
            }
             if(!$this->UpdataIscarServiceToken($inputData['servicetoken'],$messageCode)){
               throw new \Exception($messageCode);
            }

            $messageCode ='000000000';
         } catch(\Exception $e){
           if (is_null($messageCode)) {
                $messageCode = '999999999';
                \App\Library\CommonTools::writeErrorLogByException($e);
            }
        }
        $resultArray = \App\Library\CommonTools::ResultProcess($messageCode, $resultData);
        if ( $messageCode =='999999988') {
             \App\Library\CommonTools::WriteExecuteLogGetId($functionName, $inputString, json_encode($resultArray), $messageCode ,$jio_id);
             $message = "errormessage:".$messageCode."�AAPI:LogoutMember�Ajio_id:".$jio_id;
             \App\Library\CommonTools::PushNotificationToManagers($message);
        } else {
             \App\Library\CommonTools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);
        }
        $result = [$functionName . 'result' => $resultArray];
        return $result;
   }

    /**
     * �ˬd��J�ȬO�_���T
     * @param type $value
     * @return boolean
     */
    public function CheckInput(&$value) {

        if ($value == null) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($value, 'api_token', 0, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($value, 'servicetoken', 0, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($value, 'userdevicecode', 0, false, false)) {
            return false;
        }
        return true;
    }


    public function UpdataIscarServiceToken($token,&$messageCode) {
        try {
           $serviceaccess = new \App\Repositories\ServiceAccessTokenRepository;
           $querydata = $serviceaccess->GetDataBySat_Token($token);
           $date = new \DateTime('now');
           //��s�A�w�L��
           $querydata[0]['sat_effective'] = "2";
           $querydata[0]['sat_expiredate'] = $date->format('Y-m-d H:i:s');

           if (!$serviceaccess->UpdateData($querydata[0])) {
                $messageCode = "999999988";
                return false;
          }
          return true;
        } catch(\Exception $e) {
          \App\Library\CommonTools::writeErrorLogByException($e);
          return false;
        }
    }

}