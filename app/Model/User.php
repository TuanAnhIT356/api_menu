<?php

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user';
    protected $fillable = [
        'id', 'full_name', 'name', 'avata', 'email', 'password', 'type', 'status', 'create_at', 'update_at'
    ];
    public $timestamps = false;

    public function _create($data)
    {
        if (!empty(self::findEmail($data['email']))) {
            return response()->json(['succ' => false, 'code' => 501, 'message' => 'Email đã tồn tại', 'data' => null]);
        } else {
            $params = [];
            isset($data['name']) ? $params['name'] = $data['name'] : null;
            isset($data['full_name']) ? $params['full_name'] = $data['full_name'] : null;
            isset($data['email']) ? $params['email'] = $data['email'] : null;
            isset($data['password']) ? $params['password'] = $data['password'] : null;
            $params['type'] = 0;
            $params['status'] = 0;
            $params['create_at'] = time();
            $params['update_at'] = time();
            $model = self::create($params);
            return response()->json(['succ' => true, 'code' => 200, 'message' => 'Đăng ký thành công', 'data' => $model]);
        }
    }

    public function _update($data)
    {
        $model = self::select()->where("id", $data['id'])->first();
        if (!empty($data['name'])) {
            $model->name = $data['name'];
        }
        if (!empty($data['full_name'])) {
            $model->full_name = $data['full_name'];
        }
        if (!empty($data['email'])) {
            if ($data['email'] == $model->email) {
                $model->email = $data['email'];
            } else if (!empty(self::findEmail($data['email']))) {
                return response()->json(['succ' => false, 'code' => 501, 'message' => 'Email đã tồn tại', 'data' => null]);
            } else {
                $model->email = $data['email'];
            }
        }
        if (!empty($data['password'])) {
            $model->password = $data['password'];
        }
        if (!empty($data['type'])) {
            $model->type = $data['type'];
        }
        if (!empty($data['status'])) {
            $model->status = $data['status'];
        }
        if ($model->save()) {
            return response()->json(['succ' => true, 'code' => 200, 'message' => 'Sửa thành công', 'data' => $model]);
        }
        return response()->json(['succ' => false, 'code' => 500, 'message' => 'Sửa thất bại', 'data' => null]);
    }

    public function findID($id)
    {
        return self::select()->where("id", $id)->first();
    }

    public function findEmail($email)
    {
        return self::select()->where("email", $email)->first();
    }

    public static function destroy($id)
    {
        return self::find($id)->delete();
    }

    function generateRandomString($length = 20)
    {
        return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
    }
}