<?php

namespace App\Http\Controllers;


use App\Model\User;
use App\Model\UserAuthToken;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function getUser(Request $request)
    {
        try {
            $token = $request->post('token');
            if (empty($token)) {
                return response()->json(['succ' => false, 'code' => 501, 'message' => 'Lỗi param', 'data' => null]);
            } else {
                $model = new UserAuthToken();
                $modelToken = $model->findToken($token);
                $id = $modelToken->id_user;
                $modelUser = new User();
                $modelUser = $modelUser->findID($id);
                return response()->json(['succ' => true, 'code' => 200, 'message' => 'get user thành công', 'data' => $modelUser]);
            }
        } catch (\Exception $e) {
            return response()->json(['succ' => false, 'code' => 500, 'message' => 'Lỗi server ' . $e->getMessage(), 'data' => null]);
        }
    }

    public function register(Request $request)
    {
        try {
            $name = $request->post('name');
            $full_name = $request->post('full_name');
            $email = $request->post('email');
            $password = $request->post('password');
            if (empty($name) || empty($full_name) || empty($email) || empty($password)) {
                return response()->json(['succ' => false, 'code' => 501, 'message' => 'Lỗi param', 'data' => null]);
            } else {
                $model = new User();
                return $model = $model->_create([
                    'name' => $name,
                    'full_name' => $full_name,
                    'email' => $email,
                    'password' => $password,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['succ' => false, 'code' => 500, 'message' => 'Lỗi server ' . $e->getMessage(), 'data' => null]);
        }
    }

    public function login(Request $request)
    {
        $email = $request->post('email');
        $password = $request->post('password');
        if (empty($email) || empty($password)) {
            return response()->json(['succ' => false, 'code' => 501, 'message' => 'Lỗi param', 'data' => null]);
        } else {
            $model = new User();
            $model = $model->findEmail($email);
            if (!empty($model) && $model->password == $password) {
                $modelToken = new UserAuthToken();
                $modelToken = $modelToken->_create($model->id);
                return response()->json(['succ' => true, 'code' => 200, 'message' => 'Đăng nhập thành công', 'data' => ['token'=>$modelToken] ]);
            }
            return response()->json(['succ' => false, 'code' => 501, 'message' => 'Email hoặc password không chính xác', 'data' => null]);
        }
    }
}