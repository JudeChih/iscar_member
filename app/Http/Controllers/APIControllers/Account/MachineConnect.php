<?php

namespace App\Http\Controllers\APIControllers\Account;

class MachineConnect {

    /**
     * 檢查輸入值是否正確
     * @param type $value
     * @return boolean
     */
    public function CheckInput(&$value) {
        if ($value == null) {
            return false;
        }

        if (!\App\Library\CommonTools::CheckRequestArrayValue($value, 'mur_uuid', 0, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($value, 'mur_gcmid', 0, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($value, 'mur_apptype', 1, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($value, 'mur_systemtype', 1, false, false)) {
            return false;
        }
        if (!\App\Library\CommonTools::CheckRequestArrayValue($value, 'mur_systeminfo', 0, false, true)) {
            return false;
        }

        return true;
    }

}
