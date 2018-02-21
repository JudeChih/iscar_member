<?php

namespace App\Http\Controllers\APIControllers\MemberCar;

use Request;
use DB;

/** createnewmembercar	會員新增車輛 **/
class CreateNewMemberCar {

    /**
     * 會員新增車輛
     * @param  [string] $modacc               [模組帳號]
     * @param  [string] $modvrf               [模組驗證碼]
     * @param  [string] $sat                  [用戶登入存取憑證]
     * @param  [string] $ncbi_id              [車輛對應資料ID]
     * @param  [string] $moc_purchasedate     [購買年月]
     * @param  [string] $moc_enginenumber     [引擎號碼]
     * @param  [string] $moc_vin              [車身號碼]
     * @param  [string] $moc_cartypecode      [監理單位歸類車輛種類代碼]
     * @param  [string] $moc_carbodycolor     [顏色]
     * @param  [string] $moc_remark           [備註訊息]
     * @param  [string] $moc_ownstatus        [車輛持有狀態][0: 未持有 1:已持有 2:已售出 3:已報銷]
     * @param  [string] $moc_privatedataallow [個資同意使用記錄][0:未同意 1:同意使用]
     * @param  [string] $cbl_fullname         [廠牌]
     * @param  [string] $cbm_fullname         [車系]
     * @param  [string] $cms_fullname         [車款]
     * @param  [string] $ci_car_year_style    [年事]
     * @param  [string] $moc_licensenum       [車牌號碼]
     * @param  [array]  $mocp_pic             [車輛圖片陣列]
     */
    public function create_new_member_car() {
        $functionName = 'create_new_member_car';
        $inputString = Request::All();
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
            // 檢查使用者輸入資料
            if (!$this->CheckInputData($inputData, $messageCode)) {
                throw new \Exception($messageCode);
            }
            // 新增會員車輛
            if (!$this->CreateMemberCarData($satData['md_id'], $inputData)) {
                $messageCode = '999999988';
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
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'modacc', 0, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'modvrf', 0, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'sat', 0, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'ncbi_id', 32, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'moc_purchasedate', 7, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'moc_enginenumber', 15, true, false)) {
            return false;
        }
       if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'moc_vin', 15, true, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'moc_cartypecode', 2, true, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'moc_carbodycolor', 5, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'moc_remark', 200, true, true)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'moc_ownstatus', 1, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'cbl_fullname', 150, false, true)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'cbm_fullname', 150, false, true)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'cms_fullname', 150, false, true)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'ci_car_year_style', 9, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'moc_privatedataallow', 1, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'moc_licensenum', 10, false, false)) {
            return false;
        }
        return $inputData;
    }

    /**
     * 檢查使用者輸入資料(車輛持有狀態,個資授權，照片格式與數量)
     * @param type $inputData
     * @param type $messageCode
     * @return boolean
     */
    public function CheckInputData($inputData, &$messageCode) {
        try {
            $CountMasterCarPic = 0;
            if (!in_array($inputData['moc_ownstatus'],array(1,2,3))) {
                //車輛持有狀態有誤，請確認後重發
                $messageCode = '011301001';
                return false;
            } else if ($inputData['moc_privatedataallow'] != 1 ) {
                //用戶個資未授權使用，系統無法登載車輛資料
                $messageCode = '011301002';
                return false;
            } 
            // else if (!is_array($inputData['mocp_pic']) || count($inputData['mocp_pic']) > 10 || count($inputData['mocp_pic']) < 1 ) {
            //     //上傳圖片數不符限制，請確認後重發
            //     $messageCode = '011301003';
            //     return false;
            // }
            // foreach ($inputData['mocp_pic'] as $rowData) {
            //     if ($rowData['mocp_picscategory'] == 0 ) {
            //         $CountMasterCarPic ++ ;
            //     }
            // }
            // if ($CountMasterCarPic == 0 || $CountMasterCarPic > 1 ) {
            //     //封面圖片數不符限制，請確認後重發
            //     $messageCode= '011301004';
            //     return false;
            // }
            return true;
        } catch (\Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * 新增會員車輛資訊以及圖片
     * @param [string] $md_id     [會員編號]
     * @param [string] $inputData [車輛訊息]
     */
    public function CreateMemberCarData($md_id, $inputData) {
        try {
            DB::beginTransaction();
            if (!$this->InsertMoc($moc_id, $md_id, $inputData, $e) || !$this->InsertMocp($moc_id, $inputData['mocp_pic'], $e)) {
               throw new \Exception($e);
            }
            DB::commit();
            return true;
        } catch(\Exception $e) {
            DB::rollBack();
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
       }
    }

    /**
     * 新增會員車輛持有紀錄
     * @param [string] &$moc_id   [紀錄編號ID]
     * @param [string] $md_id     [會員編號]
     * @param [string] $inputData [車輛資訊]
     * @param [string] &$e        [錯誤訊息]
     */
    public function InsertMoc(&$moc_id, $md_id, $inputData, &$e) {
        $oc_r = new \App\Repositories\MemberOwnCarRepository;
        try {
            $moc_id = \App\Library\CommonTools::NewGUIDWithoutDash();
            $insertData = [
                         'moc_id'               => $moc_id,
                         'md_id'                => $md_id,
                         'ncbi_id'              => $inputData['ncbi_id'],
                         'moc_purchasedate'     => $inputData['moc_purchasedate'],
                         'moc_enginenumber'     => $inputData['moc_enginenumber'],
                         'moc_vin'              => $inputData['moc_vin'],
                         'moc_cartypecode'      => $inputData['moc_cartypecode'],
                         'moc_carbodycolor'     => $inputData['moc_carbodycolor'],
                         'moc_remark'           => $inputData['moc_remark'],
                         'moc_ownstatus'        => $inputData['moc_ownstatus'],
                         'moc_privatedataallow' => $inputData['moc_privatedataallow'],
                         'cbl_fullname'         => $inputData['cbl_fullname'],
                         'cbm_fullname'         => $inputData['cbm_fullname'],
                         'cms_fullname'         => $inputData['cms_fullname'],
                         'ci_car_year_style'    => $inputData['ci_car_year_style'],
                         'moc_licensenum'       => $inputData['moc_licensenum']
                       ];
         return $oc_r->InsertData($insertData);
       } catch(\Exception $e) {
         return false;
       }
    }

    /**
     * 新增車輛照片
     * @param [string] $moc_id   [紀錄編號ID]
     * @param [array]  $mocp_pic [圖片資訊陣列]
     * @param [string] &$e       [錯誤訊息]
     */
    public function InsertMocp($moc_id, $mocp_pic, &$e) {
        $ocp_r = new \App\Repositories\MemberOwnCarPicRepository;
        // 沒有圖片也可以
        if(count($mocp_pic) == 0){
        	return true;
        }
        try {
            foreach ( $mocp_pic as $rowData ) {
                $InsertData = [
                            'moc_id'            => $moc_id,
                            'mocp_picscategory' => $rowData['mocp_picscategory'],
                            'mocp_picpath'      =>  $rowData['mocp_picpath']
                            ];
                if (!$ocp_r->InsertData($InsertData)) {
                    return false;
                }
            }
            return true;
        } catch(\Exception $e) {
            return false;
        }
    }
}
