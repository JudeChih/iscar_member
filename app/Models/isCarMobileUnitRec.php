<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class isCarMobileUnitRec extends Model {

    /**
     * 資料表名稱
     * @var string
     */
    protected $table = 'iscarmobileunitrec';

    /**
     * 主鍵值
     * @var string
     */
    protected $primaryKey = 'mur_id';

    /**
     * 是否自動遞增
     * @var string
     */
    public $incrementing = false;

    /**
     * 是否自動插入現在時間
     *
     * @var bool
     */
    public $timestamps = false;

}
