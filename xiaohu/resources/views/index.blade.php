<!DOCTYPE html>
<html lang="zh" ng-app="xiaohu">

<head>
    <meta charset="UTF-8">
    <title>晓乎</title>
    <link rel="stylesheet" href="/node_modules/normalize-css/normalize.css">
    <link rel="stylesheet" href="/css/base.css">
    <script src="/node_modules/jquery/dist/jquery.js"></script>
    <script src="/node_modules/angular/angular.js"></script>
    <script src="/node_modules/angular-ui-router/release/angular-ui-router.js"></script>
    <script src="/js/base.js"></script>
</head>

<body>
    <div class="navbar clearfix">
        <div class="container">
            <div class="fl">
                <div class="navbar-item brand">晓乎</div>
                <form id="quick_ask">
                    <div class="navbar-item">
                        <input type="text">
                    </div>
                    <div class="navbar-item">
                        <button>提问</button>
                    </div>
                </form>
            </div>
            <div class="fr">
                <a ui-sref="home" class="navbar-item">首页</a>
                <a ui-sref="login" class="navbar-item">登录</a>
                <a ui-sref="signup" class="navbar-item">注册</a>
            </div>
        </div>
    </div>
    <div class="page">
        <div ui-view></div>
    </div>

</body>

<script type="text/ng-template" id="home.tpl">
    <div class="home container">
        首页
        A behind-the-scenes look at the evolution of the Picture-in-Picture player for the Firefox Desktop browser. This feature is now available for MacOS, Linux and Windows users.
    </div>
</script>

<script type="text/ng-template" id="signup.tpl">
    <div ng-controller="SignupController" class="signup container">
        <div class="card">
            <h1>注册</h1>
            {{-- [: User.signup_data :] --}}
            <form name="signup_form" ng-submit="User.signup()">
                <div class="input-group">
                    <label>用户名：</label>
                    <input name='username'
                        type="text"
                        ng-minlength="4"
                        ng-maxlength="24"
                        ng-model="User.signup_data.username"
                        ng-model-options="{debounce: 500, allowInvalid: false}"
                        required>
                        <div ng-if="signup_form.username.$touched" class="input-error-set">
                            <div ng-if="signup_form.username.$error.required" >用户名为必填项</div>
                            <div ng-if="signup_form.username.$error.minlength || signup_form.username.$error.maxlength" >用户名长度须在4到24位之间</div>
                            <div ng-if="User.signup_username_exist" >用户名已存在</div>
                        </div>
                    </div>
                    <div class="input-group">
                        <label>密码：</label>
                        <input name="password"
                        ng-minlength="6"
                        ng-maxlength="255"
                        type="password"
                        ng-model="User.signup_data.password"
                        required>
                        <div ng-if="signup_form.username.$touched"class="input-error-set">
                            <div ng-if="signup_form.password.$error.required && signup_form.password.$touched">密码为必填项</div>
                            <div ng-if="signup_form.password.$error.minlength || signup_form.password.$error.maxlength" >密码长度须在6到255位之间</div>
                        </div>
                </div>
                <button type="submit"
                    class="primary"
                    ng_disabled="signup_form.$invalid">注册</button>
            </form>
        </div>
    </div>
</script>

<script type="text/ng-template" id="login.tpl">
    <div ng-controller="LoginController" class="login container">
        <div class="card">
            <h1>登录</h1>
            <form name="login_form" ng-submit="User.login()">
                <div class="input-group">
                    <label>用户名：</label>
                    <input name='username'
                        type="text"
                        ng-model="User.login_data.username"
                        required>
                        <div ng-if="login_form.username.$touched" class="input-error-set">
                            <div ng-if="login_form.username.$error.required" >用户名为必填项</div>
                        </div>
                    </div>
                    <div class="input-group">
                        <label>密码：</label>
                        <input name="password"
                        type="password"
                        ng-model="User.login_data.password"
                        required>
                        <div ng-if="login_form.username.$touched"class="input-error-set">
                            <div ng-if="login_form.password.$error.required && login_form.password.$touched">密码为必填项</div>
                            <div ng-if="User.login_failed">用户名或密码有误</div>
                        </div>
                </div>
                <button type="submit" class="primary"
                    ng_disabled="login_form.$invalid">登录</button>
            </form>
        </div>
    </div>
</script>

</html>
