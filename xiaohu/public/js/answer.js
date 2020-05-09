var app = angular.module('answer', []);

app.service('AnswerService', [
    '$state',
    '$http',
    function ($state, $http) {
        var me = this;
        me.signup_data = {};
        me.login_data = {};
        me.signup = function () {
            $http.post('api/signup', me.signup_data)
                .then(function (r) {
                    if (r.data.status)
                        me.signup_data = {};
                    $state.go('login');
                }, function (e) {
                    console.log(e);
                });
        }

        me.login = function () {
            $http.post('api/login', me.login_data)
                .then(function (r) {
                    if (r.data.status) {
                        // $state.go('home');
                        location.href = '/';
                    } else {
                        me.login_failed = true;
                    }
                }, function (e) {
                    console.log(e);
                });
        }

        me.username_exist = function () {
            $http.post('/api/user/exist',
                { username: me.signup_data.username })
                .then(function (r) {
                    if (r.data.status && r.data.data.count)
                        me.signup_username_exist = true;
                    else
                        me.signup_username_exist = false;
                }, function (e) {
                    console.log('e', e);
                })
        }

    }]);

app.controller('SignupController', [
    '$scope',
    'UserService',
    function ($scope, UserService) {
        $scope.User = UserService;
        $scope.$watch(function () {
            return UserService.signup_data;
        }, function (n, o) {
            if (n.username != o.username)
                UserService.username_exist();
        }, true)
    }
]);

app.controller('LoginController', [
    '$scope',
    'UserService',
    function ($scope, UserService) {
        $scope.User = UserService;
    }
]);
