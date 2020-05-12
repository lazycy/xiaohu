var app = angular.module('answer', []);

app.service('AnswerService', [
    '$http',
    function ($http) {
        var me = this;
        me.data = {};
        /* 统计票数
         * @answers array 用于统计票数的数据
         * 此数据可以是问题，也可以是回单
         * 如果是问题将会跳过统计
         * */
        me.count_vote = function (answers) {
            /* 迭代所有的数据 */
            for (var i = 0; i < answers.length; i++) {
                /* 封装单个数据 */
                var votes, item = answers[i];
                //如果不是回答也没有users元素说明本条不是回答或回答没有任何票数
                if (!item['question_id'] || !item['users']) continue;

                /* 每条回答的默认赞同票和反对票都为0 */
                item.upvote_count = 0;
                item.downvote_count = 0;

                /* users是所有投票用户的用户信息 */
                votes = item['users'];
                if (votes)
                    for (var j = 0; j < votes.length; j++) {
                        var v = votes[j];

                        /* 获取pivot元素中的用户投票信息
                         * 如果是1将增加一赞成票
                         * 如果是2将增加一反对票 */
                        if (v['pivot'].vote === 1)
                            item.upvote_count++;
                        if (v['pivot'].vote === 2)
                            item.downvote_count++;
                    }
            }
            return answers;
        }

        me.vote = function(conf) {
            if(!conf.id || !conf.vote) {
                console.log('id and vote are required');
                return;
            }
            return $http.post('/api/answer/vote', conf)
                .then(function(r) {
                    if(r.data.status) {
                        return true;
                    }
                    return false;
                }, function() {
                    return false;
                });
        }

        me.update_data = function(id) {
            return $http.post('/api/answer/read', {id: id})
                .then(function(r){
                    me.data[id] = r.data.data;
                })
        }
    }]);
