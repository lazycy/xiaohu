<div ng-controller="HomeController" class="home container card">
    <h1>最新动态</h1>
    <div class="hr"></div>
    <div class="item-set">
        <div ng-repeat="item in Timeline.data" class="feed item clearfix">
            <div ng-if="item.question_id" class="vote">
                <div ng-click="Timeline.vote({id: item.id, vote: 1})" class="up">赞 [: item.upvote_count :]</div>
                <div ng-click="Timeline.vote({id: item.id, vote: 2})" class="down">踩 [: item.downvote_count :]</div>
            </div>
            <div class="feed-item-content">
                <div ng-if="item.question_id" class="content-act">
                    [:item.user.username:]添加了回答
                </div>
                <div ng-if="!item.question_id" class="content-act">
                    [:item.user.username:]添加了提问
                </div>
                <div class="title">
                    [:item.title:]
                </div>
                <div class="content-owner">
                    [:item.user.username:]
                    <span class="desc">Living in Tokyo／不迎合／不作恶
                    </span>
                </div>
                <div class="content-main">
                    [:item.content:]
                </div>
                <div class="action-set">
                    <div class="comment">评论
                    </div>
                </div>
                <div class="comment-block">
                    <div class="hr"></div>
                    <div class="comment-item-set">
                        <div class="triangle-up"></div>
                        <div class="comment-item clearfix">
                            <div class="user">vennechen
                            </div>
                            <div class="comment-content">
                                一个像妈妈一个像爸爸。山口百惠本来就也不是惊艳颜
                            </div>
                        </div>
                        <div class="comment-item clearfix">
                            <div class="user">人偶未成形
                            </div>
                            <div class="comment-content">
                                说起来，我原来是不知道的，直到某一天我指着山口百惠，问家母她是谁，她竟然脱口而出：“山口百惠”。
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hr"></div>
        </div>
        <div ng-if="Timeline.pending" class="tac">加载中...</div>
        <div ng-if="Timeline.no_more_data" class="tac">没有更多数据了</div>
    </div>
</div>
