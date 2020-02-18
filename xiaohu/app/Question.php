<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    //
    public function add() {
        // dd(rq());
        if(!user_ins()->is_logged_in()) {
            return ['status' => 0, 'msg' => 'login required'];
        }
        if(!rq('title')) {
            return ['status' => 0, 'msg' => 'title required'];
        }
        $this->title = rq('title');
        $this->user_id = session('user_id');
        if(rq('desc'))
            $this->desc = rq('desc');
        return $this->save()?
            ['status' => 1, 'id' => $this->id]:
            ['status' => 0, 'msg' => 'db insert failed'];
    }

    public function change() {
        if(!user_ins()->is_logged_in()) {
            return ['status' => 0, 'msg' => 'login required'];
        }
        if(!rq('id')) {
            return ['status' => 0, 'msg' => 'id required'];
        }

        $question = $this->find(rq('id'));

        if(!$question) {
            return ['status' => 0, 'msg' => 'question not exists'];
        }
        if($question->user_id != session('user_id')) {
            return ['status' => 0, 'msg' => 'permission denied'];
        }

        if(rq('title')) {
            $question->title = rq('title');
        }

        if(rq('desc')) {
            $question->desc = rq('desc');
        }

        return $question->save()?
            ['status' => 1]:
            ['status' => 0, 'msg' => 'db update failed'];
    }

    public function read() {
        // 如果请求参数中有id，直接返回id所在的行
        if(rq('id')) {
            return ['status'=>1, 'data' => $this->find(rq('id'))];
        }

        // 每页显示几条，默认15条
        // skip条件，用于分页
        list($limit, $skip) = paginate(rq('page'), rq('limit'));

        // 构建query并返回collection数据
        $r = $this
            ->orderBy('created_at')
            ->limit($limit)
            ->skip($skip)
            ->get(['id', 'title', 'desc', 'user_id', 'created_at', 'updated_at'])
            ->keyBy('id');

        return ['status' => 1, 'data' => $r];
    }

    public function remove() {
        if(!user_ins()->is_logged_in()) {
            return ['status' => 0, 'msg' => 'login required'];
        }
        if(!rq('id')) {
            return ['status' => 0, 'msg' => 'id required'];
        }

        $question = $this->find(rq('id'));

        if(!$question) {
            return ['status' => 0, 'msg' => 'question not exists'];
        }
        if($question->user_id != session('user_id')) {
            return ['status' => 0, 'msg' => 'permission denied'];
        }

        return $question->delete()?
            ['status' => 1]:
            ['status' => 0, 'msg' => 'db delete failed'];
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
}
