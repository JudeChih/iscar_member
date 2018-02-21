<?php

namespace App\Http\Controllers\APIControllers;

use App\Http\Controllers\Controller;

class ShopManageController extends Controller {

    /** query_member_idinfo	會員資料查詢 **/
    function querymemberidinfo() {
        $querymemberidinfo = new \App\Http\Controllers\APIControllers\ShopManage\QueryMemberIdInfo;
        return $querymemberidinfo->querymemberidinfo();
    }


}
