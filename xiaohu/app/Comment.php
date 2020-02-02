<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function add() {
        if(!user_ins()->is_logged_in()) {
            return ['status' => 0, 'msg' => 'login required'];
        }

        if(!rq('content')) {
            return ['status' => 0, 'msg' => 'content required'];
        }

        if(
            (!rq('question_id') && !rq('answer_id')) || // none
            (rq('question_id') && rq('answer_id')) //all
            ) {
            return ['status' => 0, 'msg' => 'question_id or answer_id is required'];
        }

        if(rq('question_id')) {
            $question = question_ins()->find(rq('question_id'));
            if(!$question) {
                return ['status' => 0, 'msg' => 'question not exists'];
            }
            $this->question_id = rq('question_id');
        } else {
            $answer = answer_ins()->find(rq('answer_id'));
            if(!$answer) {
                return ['status' => 0, 'msg' => 'answer not exists'];
            }
            $this->answer_id = rq('answer_id');
        }

        // TODO:其实这里还应该检查回复的评论是否属于这个question_id或者answer_id
        if(rq('reply_to')) {
            $target = $this->find(rq('reply_to'));
            // dd($target->all());
            if(!$target) {
                return ['status' => 0, 'msg' => 'target comment not exists'];
            }
            if($target->user_id == session('user_id')) {
                return ['status' => 0, 'msg' => 'cannot reply to yourself'];
            }
            $this->replay_to = rq('reply_to');
        }

        $this->content = rq('content');
        $this->user_id = session('user_id');

        return $this->save()?
            ['status' => 1, 'id' => $this->id]:
            ['status' => 0, 'msg' => 'db insert failed'];
    }

    public function read() {
        if(!rq('question_id') && !rq('answer_id')) {
            return ['status' => 0, 'msg' => 'question_id or answer_id is required'];
        }

        if(rq('question_id')) {
            if(!question_ins()->find(rq('question_id'))) {
                return ['status' => 0, 'msg' => 'question not exist'];
            }
            $data = $this->where('question_id', rq('question_id'))->get();
        } else {
            if(!answer_ins()->find(rq('answer_id'))) {
                return ['status' => 0, 'msg' => 'answer not exist'];
            }
            $data = $this->where('answer_id', rq('answer_id'))->get();
        }

        return ['status' => 1, 'data' => $data->keyBy('id')];
    }

    public function remove() {
        if(!user_ins()->is_logged_in()) {
            return ['status' => 0, 'msg' => 'login required'];
        }

        if(!rq('id')) {
            return ['status' => 0, 'msg' => 'id required'];
        }

        $comment = $this->find(rq('id'));
        if(!$comment) return ['status' => 0, 'msg' => 'comment not exists'];

        if($comment->user_id != session('user_id'))
            return ['status' => 0, 'msg' => 'permission denied'];

        // 先删除回复，实际项目不应该在数据库层面加入外键关联
        $this->where('reply_to', rq('id'))->delete();
        return $comment->delete()?
            ['status' => 1]:
            ['status' => 0, 'msg' => 'db delete failed'];
    }
}
