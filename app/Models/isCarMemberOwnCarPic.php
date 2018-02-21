<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class isCarMemberOwnCarPic extends Model {

    /**
     * 資料表名稱
     * @var string
     */
    protected $table = 'iscarmemberowncarpic';

    /**
     * 主鍵值
     * @var string
     */
    protected $primaryKey = 'mocp_serno';

    /**
     * 是否自動遞增
     * @var string
     */
    public $incrementing = true;

    /**
     * 是否自動插入現在時間
     *
     * @var bool
     */
    public $timestamps = false;

}
