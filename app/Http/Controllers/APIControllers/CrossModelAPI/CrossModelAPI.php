<?php

namespace App\Http\Controllers\APIControllers\CrossModelAPI;

/** CarNews共用Function * */
class CrossModelAPI {

    /**
     * 進行推播
     * @param type $md_id
     * @return boolean
     */
    public function pushnotification($md_id) {
      $member = new \App\Repositories\MemberDataRepository;
      try {
          $message = '您有新訊息，請查看isCar收件匣。';
          $queryData =  $member->GetData_ByMDID($md_id);
          if ($queryData[0]['mur_systemtype'] == 0) {
              \App\Library\CommonTools::Push_Notification_GCM($queryData[0]['mur_gcmid'], $message);
          }
          if ($queryData[0]['mur_systemtype'] == 1) {
            if (!\App\Library\CommonTools::Push_Notification_APNS($queryData[0]['mur_gcmid'], $message)) {
               return false;
            }
          }
          return true;
      } catch (\Exception $e) {
          \App\Library\CommonTools::writeErrorLogByException($e);
          return false;
      }
    }
}
