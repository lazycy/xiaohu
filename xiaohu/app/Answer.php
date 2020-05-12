<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    public function add() {
        // dd(rq());
        if(!user_ins()->is_logged_in()) {
            return ['status' => 0, 'msg' => 'login required'];
        }
        if(!rq('question_id') || !rq('content')) {
            return ['status' => 0, 'msg' => 'question_id and content are required'];
        }

        $question = question_ins()->find(rq('question_id'));
        if(!$question) {
            return ['status' => 0, 'msg' => 'question not exists'];
        }

        // 检查是否重复回答
        $answered = $this
            ->where(['question_id' => rq('question_id'), 'user_id' => session('user_id')])
            ->count();

        if($answered) {
            return ['status' => 0, 'msg' => 'duplicate answers'];
        }

        $this->content = rq('content');
        $this->question_id = rq('question_id');
        $this->user_id = session('user_id');

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

        $answer = $this->find(rq('id'));

        if(!$answer) {
            return ['status' => 0, 'msg' => 'answer not exists'];
        }
        if($answer->user_id != session('user_id')) {
            return ['status' => 0, 'msg' => 'permission denied'];
        }

        if(!rq('content')) {
            return ['status' => 0, 'msg' => 'content required'];
        }

        $answer->content = rq('content');

        return $answer->save()?
            ['status' => 1]:
            ['status' => 0, 'msg' => 'db update failed'];
    }

    public function read() {
        if(!rq('id') && !rq('question_id')) {
            return ['status' => 0, 'msg' => 'id or question_id is required'];
        }

        // 查看单个回答
        if(rq('id')) {
            $answer = $this
                ->with('user')
                ->with('users')
                ->find(rq('id'));
            if(!$answer) {
                return ['status' => 0, 'msg' => 'answer not exists'];
            }
            return ['status' => 1, 'data' => $answer];
        }

        // 在查看回答前检查问题是否存在
        if(!question_ins()->find(rq('question_id'))) {
            return ['status' => 0, 'msg' => 'question not exist'];
        }

        // 查看同一问题下的所有回答
        $answers = $this
            ->where('question_id', rq('question_id'))
            ->get()
            ->keyBy('id');

        return ['status' => 1, 'data' => $answers];
    }

    public function remove() {
        if(!user_ins()->is_logged_in()) {
            return ['status' => 0, 'msg' => 'login required'];
        }

        if(!rq('id')) {
            return ['status' => 0, 'msg' => 'id required'];
        }

        $answer = $this->find(rq('id'));
        if(!$answer) {
            return ['status' => 0, 'msg' => 'answer not exists'];
        }
        if($answer->user_id != session('user_id')) {
            return ['status' => 0, 'msg' => 'permission denied'];
        }

        return $answer->delete()?
            ['status' => 1]:
            ['status' => 0, 'msg' => 'db delete failed'];
    }

    public function vote() {
        if(!user_ins()->is_logged_in()) {
            return ['status' => 0, 'msg' => 'login required'];
        }

        if(!rq('id') || !rq('vote')) {
            return ['status' => 0, 'msg' => 'id and vote are required'];
        }

        $answer = $this->find(rq('id'));
        if(!$answer) return ['status' => 0, 'msg' => 'answer not exist'];

        // 1：赞同；2：反对
        $vote = rq('vote') <=1 ? 1 : 2;

        // 检查此用户是否在相同问题下投过票，如果投过票，先删除
        $answer->users()
            ->newPivotStatement()
            ->where('user_id', session('user_id'))
            ->where('answer_id', rq('id'))
            ->delete();

        // 在连接表中增加数据
        $answer->users()->attach(session('user_id'), ['vote' => $vote]);
        return ['status' => 1];
    }

    // 对回答
    public function user() {
        return $this->belongsTo('App\User');
    }

    // 对投票
    public function users() {
        return $this
            ->belongsToMany('App\User')
            ->withPivot('vote')
            ->withTimestamps();
    }
}
