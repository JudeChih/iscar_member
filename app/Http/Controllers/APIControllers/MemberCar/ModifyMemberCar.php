<?php

namespace App\Http\Controllers\APIControllers\MemberCar;

use Request;
use DB;

/** modifymembercar	會員修改車輛 * */
class ModifyMemberCar {

  /**
   * 會員修改車輛
   * @param  [string] $modacc              [模組帳號]
   * @param  [string] $modvrf              [模組驗證碼]
   * @param  [string] $sat                 [用戶登入存取憑證]
   * @param  [string] $moc_id              [記錄編號]
   * @param  [string] $operation_type      [0:刪除記錄 1:修改記錄]
   * @param  [string] $moc_purchasedate    [購買年月]
   * @param  [string] $moc_enginenumber    [引擎號碼]
   * @param  [string] $moc_vin             [車身號碼]
   * @param  [string] $moc_cartypecode     [監理單位歸類車輛種類代碼]
   * @param  [string] $moc_carbodycolor    [顏色]
   * @param  [string] $moc_remark          [備註訊息]
   * @param  [string] $moc_ownstatus       [車輛持有狀態][0: 未持有 1:已持有 2:已售出 3:已報銷]
   * @param  [string] $cbl_fullname        [廠牌]
   * @param  [string] $cbm_fullname        [車系]
   * @param  [string] $cms_fullname        [車款]
   * @param  [string] $ci_car_year_style   [年事]
   * @param  [string] $moc_licensenum      [車牌號碼]
   * @param  [string] $mocp_pic            [車輛圖片陣列]
   */
  public function modify_member_car() {
      $functionName = 'modify_member_car';
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
          // 驗證用戶修改權限
          if(!$this->CheckMemberOwnCar($satData['md_id'], $inputData['moc_id'], $messageCode)){
              throw new \Exception($messageCode);
          }
          // 更改會員持有車輛資料
          if (!$this->ModifyMemberCarData($inputData)) {
              //推波錯誤訊息給管理者
              $messageCode = '999999988';
              $message = "ModifyMemberCar::ModifyMemberCarData，messageCode:$messageCode";
              \App\Library\CommonTools::PushNotificationToManagers($message);
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
      if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'moc_id', 32, false, false)) {
          return false;
      }
      if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'operation_type', 1, false, false)) {
          return false;
      }
      if($inputData['operation_type'] == 0){
        return $inputData;
      }
      if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'moc_purchasedate', 7, true, false)) {
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
      //中文判斷 strlen 為 3bytes
      if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'moc_carbodycolor', 15, true, false)) {
          return false;
      }
      if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'moc_remark', 200, true, true)) {
          return false;
      }
      if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'moc_ownstatus', 1, true, true)) {
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
      if (!\App\Library\CommonTools::CheckRequestArrayValue($inputData, 'moc_licensenum', 10, false, false)) {
          return false;
      } 
      return $inputData;
  }

   /**
   * 檢查使用者輸入資料
   * @param type $inputData
   * @param type $messageCode
   * @return boolean
   */
   public function CheckInputData($inputData, &$messageCode) {
      try {
        if ( !in_array($inputData['operation_type'],array(0,1)) ) {
           $messageCode = '999999989';
           return false;
        }
        if ($inputData['operation_type'] == 1 ) {
            if ($inputData['moc_ownstatus'] == 0) {
               //車輛狀態指定有誤，請重新輸入
               $messageCode = '011301001';
               return false;
           } else if (!is_null($inputData['mocp_pic']) && count($inputData['mocp_pic']) > 0 ) {
               $CountMasterCarPic = 0;
               if (!is_array($inputData['mocp_pic']) || count($inputData['mocp_pic']) > 10 || count($inputData['mocp_pic']) < 1 ) {
                   //上傳圖片數不符限制，請確認後重發
                   $messageCode = '011301003';
                   return false;
               }
               foreach ($inputData['mocp_pic'] as $rowData) {
                    if ($rowData['mocp_picscategory'] == 0) {
                        $CountMasterCarPic ++ ;
                    }
               }
               if ($CountMasterCarPic == 0 || $CountMasterCarPic > 1 ) {
                   //封面圖片數不符限制，請確認後重發
                   $messageCode= '011301004';
                   return false;
              }
           }
        }
        return true;
      } catch(\Exception $e) {
        \App\Library\CommonTools::writeErrorLogByException($e);
        return false;
      }
   }

    /**
     * 驗證用戶修改權限
     * @param [string] $md_id        [會員代碼]
     * @param [string] $moc_id       [車輛記錄編號]
     * @param [string] $messageCode  [錯誤代碼]
     */
    public function CheckMemberOwnCar($md_id, $moc_id, &$messageCode) {
        $oc_r = new \App\Repositories\MemberOwnCarRepository;
        try {
            $querydata = $oc_r->QueryMemberCarDetails($md_id, $moc_id);
            if (is_null($querydata) || count($querydata) == 0 ) {
                $messageCode = '011300001';
                return false;
            }
            // else if ( $querydata[0]['isflag'] == 0 ) {
            //     $messageCode = '011300002';
            //     return false;
            // }
            return true;
        } catch(\Exception $e) {
            CommonTools::writeErrorLogByException($e);
            return null;
        }
    }



   /**
   * 更改會員持有車輛資料
   * @param type $inputData
   * @return boolean
   */
   public function ModifyMemberCarData($inputData) {
      $ocp_r = new \App\Repositories\MemberOwnCarPicRepository;
      try {
          DB::beginTransaction();
          if (!$this->UpdateMoc($inputData, $e)) {
             throw new \Exception($e);
          }
          if ($inputData['operation_type'] == 0 ) {
             $result = $ocp_r->getDataByMocId($inputData['moc_id']);
             // 如果沒有圖片直接跳過圖片的刪除
             if(count($result) == 0){
                DB::commit();
                return true;
             }
             if (!$this->MarkIsflag_Mocp($inputData['operation_type'], $inputData['moc_id'], $e)) {
                throw new \Exception($e);
             }
          }
          if (!is_null($inputData['mocp_pic']) && count($inputData['mocp_pic']) > 0 ) {
             $result = $ocp_r->getDataByMocId($inputData['moc_id']);
             // 如果沒有圖片直接跳過圖片的刪除
             if(count($result) != 0){
                if (!$this->MarkIsflag_Mocp(0, $inputData['moc_id'], $e)) {
                  throw new \Exception($e);
               }
             }
             if (!$this->InsertMocp($inputData['moc_id'], $inputData['mocp_pic'], $e)) {
                throw new \Exception($e);
             }
          }
          DB::commit();
          return true;
      } catch (\Exception $e) {
          DB::rollBack();
          \App\Library\CommonTools::writeErrorLogByException($e);
          return false;
      }
  }

   /**
   * 更新會員車輛資料
   * @param type $inputData
   * @param type $e
   * @return boolean
   */
   public function UpdateMoc($inputData, &$e) {
      $oc_r = new \App\Repositories\MemberOwnCarRepository;
       try {
           $updateData = [
                            'moc_purchasedate' => $inputData['moc_purchasedate'],
                            'moc_enginenumber' => $inputData['moc_enginenumber'],
                            'moc_vin'          => $inputData['moc_vin'],
                            'moc_cartypecode'  => $inputData['moc_cartypecode'],
                            'moc_carbodycolor' => $inputData['moc_carbodycolor'],
                            'moc_remark'       => $inputData['moc_remark'],
                            'moc_ownstatus'    => $inputData['moc_ownstatus'],
                            'isflag'           => $inputData['operation_type'],
                            'moc_id'           => $inputData['moc_id'],
                            'cbl_fullname'     => $inputData['cbl_fullname'],
                            'cbm_fullname'     => $inputData['cbm_fullname'],
                            'cms_fullname'     => $inputData['cms_fullname'],
                            'ci_car_year_style'=> $inputData['ci_car_year_style'],
                            'moc_licensenum'   => $inputData['moc_licensenum']
                         ];
          return $oc_r->UpdateData($updateData);
       } catch (\Exception $e) {
         \App\Library\CommonTools::writeErrorLogByException($e);
         return false;
       }
   }

   /**
   * 註記刪除會員車輛照片
   * @param type $isflag
   * @param type $moc_id
   * @param type $e
   * @return boolean
   */
   public function MarkIsflag_Mocp($isflag, $moc_id, &$e) {
        $ocp_r = new \App\Repositories\MemberOwnCarPicRepository;
       try {
            $updateData = [
                            'isflag' => $isflag,
                            'moc_id'=> $moc_id
                          ];
            return $ocp_r->UpdateData($updateData);
       } catch (\Exception $e) {
         \App\Library\CommonTools::writeErrorLogByException($e);
         return false;
       }
   }

   /**
   * 新增會員車輛照片
   * @param type $moc_id
   * @param type $mocp_pic
   * @param type $e
   * @return boolean
   */
   public function InsertMocp($moc_id, $mocp_pic, &$e) {
    $ocp_r = new \App\Repositories\MemberOwnCarPicRepository;
     try {
       foreach ( $mocp_pic as $rowData ) {
          $InsertData = [
                           'moc_id'            => $moc_id,
                           'mocp_picscategory' => $rowData['mocp_picscategory'],
                           'mocp_picpath'      => $rowData['mocp_picpath']
                        ];
         if (!$ocp_r->InsertData($InsertData)) {
            return false;
         }
       }
       return true;
     } catch(\Exception $e) {
      \App\Library\CommonTools::writeErrorLogByException($e);
       return false;
     }
  }

}
