<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class isCarMemberData extends Model {

    /**
     * 資料表名稱
     * @var string
     */
    protected $table = 'iscarmemberdata';

    /**
     * 主鍵值
     * @var string
     */
    protected $primaryKey = 'md_id';

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
