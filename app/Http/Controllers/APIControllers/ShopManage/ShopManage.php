<?php

namespace App\Http\Controllers\APIControllers\ShopManage;

/** ShopManage共用Function * */
class ShopManage {

     /**檢查會原存在與否
     * @param type $md_id
     * @param type $messageCode
     * @return boolean 檢查結果
     */
    public function CheckMdId($md_id, &$messageCode) {
       try {
            $member = new \App\Repositories\MemberDataRepository;
           $queryData = $member->GetMemberData($md_id);
           if (is_null($queryData) || count($queryData) == 0 ) {
              $messageCode= '999999980';
              return false;
           }
           return true;
       } catch(\Exception $e) {
          \App\Library\CommonTools::writeErrorLogByException($e);
          return false;
       }
    }

}
