<?php

namespace App\Http\Controllers\APIControllers\ShopManage;

use Illuminate\Support\Facades\Input;

/** query_member_idinfo	�|����Ƭd�� **/
class QueryMemberIdInfo {
   public function querymemberidinfo() {
        $shop = new \App\Http\Controllers\APIControllers\ShopManage\ShopManage;
        $functionName = 'querymemberidinfo';
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
               $messageCode = '999999945';
               throw new \Exception($messageCode);
            }
            if(!\App\Library\CommonTools::checkModuleAccount($inputData['modacc'],$inputData['modvrf'])){
                $messageCode = '999999961';
                throw new \Exception($messageCode);
            }
            //�|���祿
            if (!$shop->CheckMdId($inputData['md_id'], $messageCode)) {
                throw new \Exception($messageCode);
            }
            // �إߦ^�ǭ�
            if( !$this->CreateResultData($inputData['md_id'], $messagecode, $resultData)) {
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
        \App\Library\CommonTools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);
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
        if (!\App\Library\CommonTools::CheckRequestArrayValue($value, 'modacc', 0, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($value, 'modvrf', 0, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($value, 'md_id', 0, false, false)) {
            return false;
        }
        return true;
    }
    /**
     * �إߦ^�ǭ�
     * @param type $sd_id
     * @param type $messagecode
     * @param type $resultData
     * @return boolean
     */
    private function CreateResultData($md_id, &$messagecode, &$resultData) {
        $member = new \App\Repositories\MemberDataRepository;
        $queryData =  $member->GetMemberData($md_id);
        $resultData['md_id'] = $queryData[0]['md_id'];
        $resultData['md_cname'] = $queryData[0]['md_cname'];
        $resultData['md_picturepath'] = $queryData[0]['md_picturepath'];

       return true;
    }


}