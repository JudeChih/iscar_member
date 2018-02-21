<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class icr_ModuleLogin_r extends Model {

    /**
     * 資料表名稱
     * @var string
     */
    protected $table = 'icr_modulelogin_r';

    /**
     * 主鍵值
     * @var string
     */
    protected $primaryKey = 'mlr_id';

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
