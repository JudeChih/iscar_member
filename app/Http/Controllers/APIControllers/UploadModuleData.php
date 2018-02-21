<?php

namespace App\Http\Controllers\APIControllers;

use Request;
use Illuminate\Support\Facades\Input;

class UploadModuleData {

    /**
     * 模組上傳資料
     * @param  [string] $modacc     [模組帳號]
     * @param  [string] $modvrf     [模組驗證碼]
     * @param  [string] $sat        [用戶登入存取憑證]
     * @param  [string] $packetname [資料封包名稱]
     * @param  [string] $packetdata [資料JSON字串]
     */
    public function upload_moduledata(Request $request) {
        $functionName = 'upload_moduledata';
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
            // 檢查packetName(名稱) and packetData(資料結構)
            if(!$this->checkDataPacketStructure($inputData['modacc'],$inputData['packetname'],$inputData['packetdata'])){
                $messageCode = '010150001';
                throw new \Exception($messageCode);
            }
            // 儲存資料
            if(!$this->insertData($inputData,$satData)){
                $messageCode = '010150002';
                throw new \Exception($messageCode);
            }
            // 處理傳入的資料
            if(!$this->processPacketData($inputData['modacc'],$inputData['packetname'],$inputData['packetdata'])){
                $messageCode = '010150003';
                throw new \Exception($messageCode);
            }
            // 成功完成
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
     * @param  [string] $inputString
     * @return [mixed]  false or $inputData
     */
    public function convertAndCheckApiInput($inputString) {
        // 字串轉陣列
        $inputData = \App\Library\CommonTools::ConvertStringToArray($inputString);
        // 判斷有無資料
        if ($inputData == null) {
            return false;
        }
        // 檢查各欄位長度、空白、空值
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'modacc', 20, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'modvrf', 0, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'sat', 0, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'packetname', 40, false, false)) {
            return false;
        }
        // if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'packetdata', 0, false, false)) {
        //     return false;
        // }
        return $inputData;
    }

    /**
     * 檢查packetName(名稱) and packetData(資料結構)
     * @param  [string]  $modacc     [模組帳號]
     * @param  [string]  $packetname [資料封包名稱]
     * @param  [string]  $packetdata [資料JSON字串]
     * @return [boolean] true or false
     */
    public function checkDataPacketStructure($modacc,$packetname,$packetdata){
        $icr_moddataform_r = new \App\Repositories\ModDataFormRegisterRepository;
        try{
            $mfrData = $icr_moddataform_r->getDataByModAccDataName($modacc,$packetname);
            if(is_null($mfrData) || count($mfrData) == 0 || count($mfrData) > 1){
                return false;
            }
            if(count($mfrData) == 1){
                $mfrData = $mfrData[0];
            }
            // 字串轉陣列
            $structure = \App\Library\CommonTools::ConvertStringToArray($mfrData['mfr_contentstructure']);
            $structure = json_decode($structure,true);
            $packetdata = json_decode($packetdata,true);

            // 檢查陣列Key packetdata對structure
            $p2s_diffData = $this->checkArrayKeyDiff($packetdata,$structure);
            if(count($p2s_diffData) > 0){
                return false;
            }
            // 檢查陣列Key structure對packetdata
            $s2p_diffData = $this->checkArrayKeyDiff($structure,$packetdata);
            if(count($s2p_diffData) > 0){
                return false;
            }
            return true;
        } catch (\Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * 儲存資料
     * @param  [array]   $inputData [傳入的值]
     * @param  [array]   $satData   [SAT的值]
     * @return [boolean] true or false
     */
    public function insertData($inputData,$satData){
        $modincom_r = new \App\Repositories\ModIncom_rRepository;
        $moduleaccpass_r = new \App\Repositories\ModuleAccPass_rRepository;
        $moddataformregister_r = new \App\Repositories\ModDataFormRegisterRepository;
        try{
            // 取得模組傳輸資料
            $modData = $moddataformregister_r->getDataByModAccDataName($inputData['modacc'],$inputData['packetname']);

            if(count($modData) == 0 || count($modData) > 1 || is_null($modData)){
                return false;
            }
            if(count($modData) == 1){
                $modData = $modData[0];
            }
            $arraydata['md_id'] = $satData['md_id'];
            $arraydata['mapr_serno'] = $modData['mapr_serno'];
            $arraydata['mfr_serno'] = $modData['mfr_serno'];
            $arraydata['mir_datapack'] = json_encode($inputData['packetdata']);
            // $arraydata['mir_usestatus'] = ;

            if(!$modincom_r->create($arraydata)){
                return false;
            }
            return true;
        } catch (\Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * 處理傳入的資料
     * @param  [string]  $modacc     [模組帳號]
     * @param  [string]  $packetname [資料封包名稱]
     * @param  [string]  $packetdata [資料JSON字串]
     * @return [boolean] true or false
     */
    public function processPacketData($modacc,$packetname,$packetdata){
        return true;
        try{
            // 暫無功能
        } catch (\Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * 遞迴方式檢查$packetData每層key是否與$structure的key相符，不能多不能少
     * @param  [array] $packetData
     * @param  [array] $structure
     * @return [boolean] true or false
     */
    public function checkArrayKeyDiff($packetData,$structure){
        $difference = array();
        foreach ($packetData as $k => $v) {
            if (is_array($v)) {
                if (!isset($structure[$k]) || !is_array($structure[$k])) {
                    $difference[$k] = $v;
                } else {
                    $new_diff = $this->checkArrayKeyDiff($v, $structure[$k]);
                    if (!empty($new_diff)){
                        $difference[$k] = $new_diff;
                    }
                }
            } else if (!array_key_exists($k, $structure) /* || $structure[$k] !== $v */) {
                $difference[$k] = $v;
            }
        }
        return $difference;
    }
}
