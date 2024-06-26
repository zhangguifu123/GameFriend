<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\Group;
use App\Models\User\GroupRelation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FriendGroupController extends Controller
{
    public function make(Request $request){
        //通过路由获取前端数据，并判断数据格式
        $data = $this->_dataHandle($request, 1);
        if (!is_array($data)) {
            return $data;
        }
        $groupData    = ['user_id' => $request->route('uid'), 'name' => $data['groupName'], 'img' => $data['img']];
        $group   = new Group($groupData);
        $group->save();
        $masterId = $request->route('uid');
        $groupId = Group::query()->where('user_id', $masterId)->where('name', $data['groupName'])->first()->id;
        foreach ($data['friends'] as $value) {
            $groupRelation = new GroupRelation(['group_id' => $groupId, 'user_id' => $value, 'master_id' => $masterId]);
            $groupRelation->save();
        }
        return msg(0, $group);
    }


    public function getMeList(Request $request){
        if (!$request->route('uid')) {
            return msg(3 , __LINE__);
        }
        $myGroup   = Group::query()->where('user_id', $request->route('uid'))->get()->toArray();
        $joinGroup = $this->getJoinList($request);
        $group = array_merge($myGroup, $joinGroup);
        return msg(0, $group);
    }

    private function getJoinList(Request $request){
        if (!$request->route('uid')) {
            return msg(3 , __LINE__);
        }
        $group = GroupRelation::query()->where('user_id', $request->route('uid'))->get('group_id')->toArray();
        $groupIds = [];
        foreach ($group as $value) {
            $groupIds[] = $value['group_id'];
        }
        $group = Group::query()->whereIn('id', $groupIds)->get()->toArray();
        return $group;
    }

    public function getOneGroup(Request $request){
        if (!$request->route('groupId')) {
            return msg(3 , __LINE__);
        }
        $group = GroupRelation::query()->where('group_id', $request->route('groupId'))->get('user_id')->toArray();
        $friendIds = [];
        foreach ($group as $value) {
            $id  = array_values($value);
            $friendIds[] = $id[0];
        }
        $group   = Group::query()->find($request->route('groupId'));
        $friendIds[] = $group->user_id;
        $friends = User::query()->whereIn('openid', $friendIds)->get()->toArray();
        return msg(0, $friends);
    }
    //添加群成员
    public function add(Request $request){

        //通过路由获取前端数据，并判断数据格式
        $data = $this->_dataHandle($request, 2);
        if (!is_array($data)) {
            return $data;
        }
        $groupId = Group::query()->find($data["groupId"])->id;
        if (!$groupId) {
            return msg(3, "目标不存在" . __LINE__);
        }
        $masterId = $request->route('uid');
        $groupRelation = new GroupRelation(['group_id' => $groupId, 'user_id' => $data['friend'], 'master_id' => $masterId]);
        $groupRelation->save();
        return msg(0, __LINE__);
    }
    //删除群聊
    public function deleteGroup(Request $request){
        //通过路由获取前端数据，并判断数据格式
        if (!$request->input('groupId')){
            return msg(11, __LINE__);
        }
        $group = Group::query()->find($request->input('groupId'));
        if (!$group) {
            return msg(11, __LINE__);
        }
        $group->delete();
        return msg(0, __LINE__);
    }
    //移除群成员
    public function delete(Request $request){
        //通过路由获取前端数据，并判断数据格式
        $data = $this->_dataHandle($request, 2);
        if (!is_array($data)) {
            return $data;
        }
        $group = Group::query()->find($data["groupId"]);
        if (!$group) {
            return msg(3, "目标不存在" . __LINE__);
        }
        $groupRelation = GroupRelation::query()->where('user_id', $data['friend']);
        if (!$groupRelation) {
            return msg(11, __LINE__);
        }
        $groupRelation->delete();
        return msg(0, __LINE__);
    }
    //检查函数
    private function _dataHandle(Request $request = null, $num){
        //声明理想数据格式
        if ($num == 1) {
            $mod = [
                "friends"    => ["array"],
                "groupName"  => ["string"],
                "img"     => ["string"],
            ];
        } else {
            $mod = [
                "friend"     => ["string"],
                "groupId"   => ["string"],
            ];
        }

        //是否缺失参数
        if (!$request->has(array_keys($mod))){
            return msg(1,__LINE__);
        }
        //提取数据
        $data = $request->only(array_keys($mod));
        //判断数据格式
        if (Validator::make($data, $mod)->fails()) {
            return msg(3, '数据格式错误' . __LINE__);
        };
        return $data;
    }
}
