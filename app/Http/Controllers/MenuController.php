<?php

namespace App\Http\Controllers;


use App\Model\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{

    public function get(Request $request){
        try {
            $id = $request->post('id');
            $model = new Menu();
            $model = $model->findID($id);
            if(empty($model)){
                return response()->json(['succ' => false, 'code' => 500, 'message' => 'Get menu thất bại', 'data' => null]);
            }
            return response()->json(['succ' => true, 'code' => 200, 'message' => 'Get menu thành công', 'data' => $model]);
        } catch (\Exception $e) {
            return response()->json(['succ' => false, 'code' => 500, 'message' => 'Lỗi server ' . $e->getMessage(), 'data' => null]);
        }
    }

    public function getMenuAll(Request $request){
        try {
            $page = $request->post('page');
            $perPage = $request->post('perPage');
            $model = new Menu();
            $model = $model->_getMenuAll($page,$perPage);
            return response()->json(['succ' => true, 'code' => 200, 'message' => 'Get menu thành công', 'data' => $model]);
        } catch (\Exception $e) {
            return response()->json(['succ' => false, 'code' => 500, 'message' => 'Lỗi server ' . $e->getMessage(), 'data' => null]);
        }
    }

    public function getMenu(){
        try {
            $model = new Menu();
            $model = $model->_getMenu();
            return response()->json(['succ' => true, 'code' => 200, 'message' => 'Get menu thành công', 'data' => $model]);
        } catch (\Exception $e) {
            return response()->json(['succ' => false, 'code' => 500, 'message' => 'Lỗi server ' . $e->getMessage(), 'data' => null]);
        }
    }

    public function create(Request $request)
    {
        try {
            $name = $request->post('name');
            $route = $request->post('route');
            $sort = $request->post('sort');
            $description = $request->post('description');
            $parents = $request->post('parents');
            if (empty($name) || empty($route)) {
                return response()->json(['succ' => false, 'code' => 501, 'message' => 'Lỗi param', 'data' => null]);
            } else {
                $model = new Menu();
                return $model = $model->_create([
                    'name' => $name,
                    'route' => $route,
                    'sort' => $sort,
                    'description' => $description,
                    'parents' => $parents,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['succ' => false, 'code' => 500, 'message' => 'Lỗi server ' . $e->getMessage(), 'data' => null]);
        }
    }

    public function update(Request $request)
    {
        try {
            $id = $request->post('id');
            $name = $request->post('name');
            $route = $request->post('route');
            $sort = $request->post('sort');
            $description = $request->post('description');
            $parents = $request->post('parents');
            if (empty($id)) {
                return response()->json(['succ' => false, 'code' => 501, 'message' => 'Lỗi param', 'data' => null]);
            } else {
                $model = new Menu();
                return $model = $model->_update([
                    'id' => $id,
                    'name' => $name,
                    'route' => $route,
                    'sort' => $sort,
                    'description' => $description,
                    'parents' => $parents,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['succ' => false, 'code' => 500, 'message' => 'Lỗi server ' . $e->getMessage(), 'data' => null]);
        }
    }

    public function delete(Request $request)
    {
        try {
            $id = $request->post('id');
            if (empty($id)) {
                return response()->json(['succ' => false, 'code' => 501, 'message' => 'Lỗi param', 'data' => null]);
            } else {
                $model = new Menu();
                if(!empty($model->findID($id))){
                    $model->destroy($id);
                }
                return response()->json(['succ' => true, 'code' => 200, 'message' => 'Xóa thành công', 'data' => null]);
            }
        } catch (\Exception $e) {
            return response()->json(['succ' => false, 'code' => 500, 'message' => 'Lỗi server ' . $e->getMessage(), 'data' => null]);
        }
    }
}