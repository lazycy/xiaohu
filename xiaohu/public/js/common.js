var app = angular.module('common', []);

app.service('TimelineService', [
    '$http',
    'AnswerService',
    function ($http, AnswerService) {
        var me = this;
        me.data = [];
        me.current_page = 1;
        me.get = function (conf) {
            if(me.pending) return;

            me.pending = true;

            conf = conf || {page: me.current_page};
            $http.post('/api/timeline', conf)
                .then(function (r) {
                    if (r.data.status) {
                        if(r.data.data.length){
                            me.data = me.data.concat(r.data.data);
                            me.data = AnswerService.count_vote(me.data);
                            me.current_page++;
                        } else {
                            me.no_more_data = true;
                        }

                    }
                    else
                        console.error('network error');
                }, function () {
                    console.error('network error');
                })
                .finally(function(){
                    me.pending = false;
                })
        }
    }
]);

app.controller('HomeController', [
    '$scope',
    'TimelineService',
    function ($scope, TimelineService) {
        var $win;
        $scope.Timeline = TimelineService;
        TimelineService.get();

        $win = $(window);
        $win.on('scroll', function () {
            if ($win.scrollTop() - ($(document).height() - $win.height()) > -30)
                TimelineService.get();
        })
    }
]);
