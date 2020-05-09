<div ng-controller="QuestionAddController" class="question-add container">
    <div class="card">
        <h1>提问</h1>
        <form name="question_add_form" ng-submit="Question.add()">
            <div class="input-group">
                <label>问题标题：</label>
                <input name='title'
                    type="text"
                    ng-minlength="5"
                    ng-maxlength="255"
                    ng-model="Question.new_question.title"
                    required>
                    <div ng-if="question_add_form.title.$touched" class="input-error-set">
                        <div ng-if="question_add_form.title.$error.required" >问题标题为必填项</div>
                    </div>
                </div>
                <div class="input-group">
                    <label>问题描述：</label>
                    <textarea name="desc"
                    type="text"
                    ng-model="Question.new_question.desc"
                    ></textarea>
            </div>
            <button type="submit" class="primary"
                ng_disabled="question_add_form.$invalid">提交</button>
        </form>
    </div>
</div>
