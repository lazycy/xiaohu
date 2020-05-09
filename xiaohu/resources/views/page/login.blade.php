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
