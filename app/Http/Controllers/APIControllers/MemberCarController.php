<?php

namespace App\Http\Controllers\APIControllers;

use App\Http\Controllers\Controller;

class MemberCarController extends Controller {

    /** querymembercarlist	會員車庫列表 * */
    function querymembercarlist() {
        $querymembercarlist = new \App\Http\Controllers\APIControllers\MemberCar\QueryMemberCarList;
        return $querymembercarlist->querymembercarlist();
    }
    /** querymembercardetail	會員車庫詳細內容 * */
    function querymembercardetail() {
        $querymembercardetail = new \App\Http\Controllers\APIControllers\MemberCar\QueryMemberCarDetail;
        return $querymembercardetail->querymembercardetail();
    }
    /** createnewmembercar	會員新增車輛* */
    function createnewmembercar() {
        $createnewmembercar = new \App\Http\Controllers\APIControllers\MemberCar\CreateNewMemberCar;
        return $createnewmembercar->createnewmembercar();
    }
    /** modifymembercar	會員修改車輛 * */
    function modifymembercar() {
        $modifymembercar = new \App\Http\Controllers\APIControllers\MemberCar\ModifyMemberCar;
        return $modifymembercar->modifymembercar();
    }
    /** queryiscarpolicy	平台法條查詢 * */
    function queryiscarpolicy() {
        $queryiscarpolicy = new \App\Http\Controllers\APIControllers\MemberCar\QueryisCarPolicy;
        return $queryiscarpolicy->queryiscarpolicy();
    }
}
