<?php
/**
 * Description: 用户数据表操作
 * Author: tanggaolin
 * Date: 2019-04-19
 */

namespace App\Model;

use App\Exceptions\CustomException;
use App\Exceptions\ErrorType;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    const CREATED_AT = 'ctime';
    const UPDATED_AT = 'utime';

    protected $table = 'users';

    protected $fillable = [];

    public $timestamps = false;

    protected $dates = [];

    public static $rules = [];

    public function getOne(array $whereParam): array
    {
        $res = $this->where($whereParam)->first();
        return $res ? $res->toArray() : [];
    }

}
