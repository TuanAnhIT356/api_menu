<?php

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';

    protected $fillable = [
        'id', 'name', 'route', 'sort', 'description', 'parents', 'type', 'status', 'create_at', 'update_at'
    ];

    public $timestamps = false;

    public function _getMenuAll($page= null ,$perPage = null){
        $model = self::select()->orderBy('id', 'asc');
        $count = $model->count();
        if($page != -1){
            $model->skip(($page-1)*$perPage)->take($perPage);
        }
        return ['data'=>$model->get(),'count'=>$count];
    }

    public function _getMenu($parents = null){
        $model = self::select()->where("parents", $parents)->orderBy('sort', 'asc')->get();
        for ($i = 0 ; $i < count($model) ; $i++){
            $modeCon = self::_getMenu($model[$i]->id);
            $model[$i]['modeCon'] = $modeCon;
        }
        return $model;
    }

    public function _create($data)
    {
        if (!empty($data['parents']) && empty(self::findID($data['parents']))) {
            return response()->json(['succ' => false, 'code' => 501, 'message' => 'Parents không đã tồn tại', 'data' => null]);
        } else {
            $params = [];
            isset($data['name']) ? $params['name'] = $data['name'] : null;
            isset($data['route']) ? $params['route'] = $data['route'] : null;
            isset($data['sort']) ? $params['sort'] = $data['sort'] : 0;
            isset($data['description']) ? $params['description'] = $data['description'] : null;
            isset($data['parents']) ? $params['parents'] = $data['parents'] : null;
            $params['type'] = 0;
            $params['status'] = 0;
            $params['create_at'] = time();
            $params['update_at'] = time();
            $model = self::create($params);
            return response()->json(['succ' => true, 'code' => 200, 'message' => 'Thêm mới thành công', 'data' => $model]);
        }
    }

    public function _update($data)
    {
        $model = self::select()->where("id", $data['id'])->first();
        if (!empty($data['name'])) {
            $model->name = $data['name'];
        }
        if (!empty($data['route'])) {
            $model->route = $data['route'];
        }
        if (!empty($data['sort'])) {
            $model->sort = $data['sort'];
        }
        if (!empty($data['description'])) {
            $model->description = $data['description'];
        }
        if (!empty($data['parents']) && !empty(self::findID($data['parents']))) {
            $model->parents = $data['parents'];
        }else {
            $model->parents = null;
        }
        if (!empty($data['type'])) {
            $model->type = $data['type'];
        }
        if (!empty($data['status'])) {
            $model->status = $data['status'];
        }
        $model->update_at = time();
        if ($model->save()) {
            return response()->json(['succ' => true, 'code' => 200, 'message' => 'Sửa thành công', 'data' => $model]);
        }
        return response()->json(['succ' => false, 'code' => 500, 'message' => 'Sửa thất bại', 'data' => null]);
    }

    public function findID($id)
    {
        return self::select()->where("id", $id)->first();
    }

    public static function destroy($id)
    {
        return self::find($id)->delete();
    }
}