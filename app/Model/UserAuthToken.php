<?php

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class UserAuthToken extends Model
{
    protected $table = 'user_auth_token';
    protected $fillable = [
        'id', 'token', 'id_user', 'time', 'create_at', 'update_at'
    ];
    public $timestamps = false;

    public function _create($id_user){
        $params = [];
        $params['token'] = $this->generateRandomString(20);
        $params['id_user'] = $id_user;
        $params['time'] = time()+36000;
        $params['create_at'] = time();
        $params['update_at'] = time();
        $model = self::create($params);
        return $model->token;
    }

    public function findToken($token)
    {
        return self::select()->where("token", $token)->first();
    }

    public function _updateTime()
    {
        $this->time = time()+3600;
        $this->save();
        return true;
    }

    function generateRandomString($length = 20) {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }
}