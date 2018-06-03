<?php

namespace App\Http\Middleware;


use App\Model\UserAuthToken;
use Closure;
use Illuminate\Http\Request;

class VerifyToken
{
    public $id;

    public function handle(Request $request, Closure $next)
    {
        try {
            $token = $request->post('token');
            if (empty($token)) {
                return response()->json(['succ' => false, 'code' => 600, 'message' => 'Bạn chưa đăng nhập', 'data' => null]);
            } else {
                $model = new UserAuthToken();
                $modelToken = $model->findToken($token);
                if (empty($modelToken) || $modelToken->time < time()) {
                    return response()->json(['succ' => false, 'code' => 600, 'message' => 'token quá hạn hoặc không tồn tại', 'data' => null]);
                } else if (($modelToken->time - time()) < 1800) {
                    $model->_updateTime();
                }
                $id = $modelToken->id;
            }
        } catch (\Exception $e) {
            return response()->json(['succ' => false, 'code' => 500, 'message' => 'Lỗi server ' . $e->getMessage(), 'data' => null]);
        }
        return $next($request,$id);
    }
}