<template>
    <view>
        <!-- 滚动内容区 -->
        <mescroll-uni :bottom="90" :down="{use:false}" @up="upCallback" @init="mescrollInit">
            <!-- 基本 -->
            <view class="mb18r bgwhite">
                <!-- 用户头部 -->
                <view class="flexr-jsb flex-aic plr18r ptb18r">
                    <view class="flex" @tap="fnUserInfo(calUser)">
                        <user-avatar :src="calUserAvater" :tag="calUser.uuid || ''" size="md"></user-avatar>
                        <view class="flexc-jsa ml28r">
                            <view>
                                <text class="f28r fbold mr18r">{{calUser.NickName || '#'}}</text>
                                <i-icon :type="calUser.user_sex_text == '男' ?'nan':'nv' " size="28"
                                        :color="calUser.user_sex_text == '男' ?'#479bd4':'#FF6699'"></i-icon>
                            </view>
                            <view class="f24r cgray">{{calDatetime}}</view>
                        </view>
                    </view>
                    <view class="ball2r-ctheme f28r ctheme fcenter w128r br8r ptb8r" @tap="fnAtte(calUserFriend)">
                        {{calUserFriend.is_follow?'已关注':'关注'}}
                    </view>
                </view>
                <!-- 中心内容 -->
                <view class="plr18r pb18r">
                    <!-- 内容 -->
                    <view class="fword f28r c111" :class="{mb18r: calImageSrcs }" v-if="trendData.dynamic_content">
                        {{trendData.dynamic_content}}
                    </view>
                    <!-- 图片格 -->
                    <view class="flex flex-fww" v-if="calImageSrcs">
                        <block v-for="(img,index) in calImageSrcs" :key="index">
                            <image class="hw100v br4r flex-33v"
                                   :class="{mlr05v: index==1 || index==4 || index==7,mb05v: (index==1  && calImageSrcs.length>3) || (index==4 && calImageSrcs.length>6)}"
                                   @tap="fnPreviewImage(index)" :src="img" :lazy-load="true" mode="widthFix"></image>
                        </block>
                    </view>
                    <!-- 荟吧标签 -->
                    <view class="flex flex-fww">
                        <block v-if="trendData.topic" :key="trendData.topic.topic_id">
                            <text class="huiba-tag mr18r mt18r" @tap="fnHuiba(trendData.topic.topic_id)">
                                {{trendData.topic.topic_name}}
                            </text>
                        </block>
                    </view>
                </view>
                <!-- 点赞列表 -->
                <view class="flexr-jfe flex-aic ptb18r plr18r bts2r br8r" v-if="topListData">
                    <block v-for="index in 8" :key="index">
                        <view class="mr8r">
                            <user-avatar v-if="topListData[index]" :src="topListData[index].user_info.user_head"
                                         :tag="topListData[index].user_info.uuid || ''"
                                         size="sm" @click="fnUserInfo(topListData[index].user_info)"></user-avatar>
                        </view>
                    </block>
                    <view class="f24r c111 fcenter bgf8 w128r ptb18r" @tap="fnTopList">赞
                        <text class="f24r cbrown ml18r">{{trendData.praise_count}}</text>
                    </view>
                </view>
            </view>
            <!-- 评论区 -->
            <view class="plr18r ptb28r f32r fbold c111 bbs2r bgwhite">评论（{{trendData.reply_count || 0}}）</view>
            <block v-for="(commData,index) in commentListData" :key="index">
                <comm-cell :info-data="commData" @user="fnUserInfo" @top="fnTopComm" @comm="fnComm"
                           @more="fnMoreComm"></comm-cell>
            </block>
        </mescroll-uni>

        <!-- 固定底部评论点赞收藏分享 -->
        <view class="posif posi-blr0 flexr-jsa plr18r ptb18r bts2r z5 bgwhite">
            <view class="br8r bgf8 plr18r mr8r flex-fitem" @tap="fnCommOpen">
                <i-icon type="bianji" size="36" color="#999999"></i-icon>
                <text class="f28r cgray ml8r">想说点什么？</text>
            </view>
            <view class="plr28r bls2r brs2r" @tap="fnTop">
                <i-icon type="dianzan" size="48" :color="trendData.is_prise?'#FF6699':'#8F8F94'"></i-icon>
                <text class="f28r cgray ml8r">{{trendData.praise_count || 0}}</text>
            </view>
            <view class="plr28r" @tap="fnSave">
                <i-icon type="shoucang" size="48" :color="trendData.is_collection?'#FF6699':'#8F8F94'"></i-icon>
                <text class="f28r cgray ml8r">{{trendData.collection_count || 0}}</text>
            </view>
            <view class="pl28r pr8r bls2r" @tap="fnShare">
                <i-icon type="fenxiang" size="48" color="#8F8F94"></i-icon>
            </view>
        </view>

        <!-- 分享弹出层 -->
        <share-popup ref="share"></share-popup>
        <!-- 评论输入弹出层 -->
        <comm-input ref="comm" @send="fnCommSend"></comm-input>
    </view>
</template>

<script>
    import {
        fnFormatDate
    } from "@/utils/CommonUtil.js"
    import {
        previewImage
    } from "@/utils/UniUtil.js"
    import {
        getTrendInfo
    } from "@/api/TrendServer.js"
    import {
        getTopList,
        addTop,
        delTop,
        getCommentList,
        addComment,
        delComment,
        addCommentTop,
        delCommentTop,
        addSave,
        delSave,
        setPraise,
        setCollection,
    } from "@/api/InteractServer.js"
    import {
        addUserAtte,
        delUserAtte,
    } from "@/api/UserServer.js"

    // 分享弹出层组件
    import SharePopup from '@/components/share-popup/share-popup'
    // 评论列表单元组件
    import CommCell from '@/components/comm-cell/comm-cell'
    // 评论输入弹出层组件
    import CommInput from '@/components/comm-input/comm-input'

    export default {
        components: {
            SharePopup,
            CommCell,
            CommInput
        },
        data() {
            return {
                // 动态项ID
                dynamic_id: 0,
                last_id: 0,
                // 跳转来源页
                fromPage: '',
                // 来源页标签数据下标
                current: -1,
                // 回复添加父ID
                replyParentID: -1,
                // mescroll组件实例
                mescroll: null
            }
        },
        onLoad(options) {
            if (options && options.dynamic_id) {
                console.log(options);
                this.dynamic_id = parseInt(options.dynamic_id);
                if (typeof options.fromPage == 'string') this.fromPage = options.fromPage
                if (typeof options.current == 'string') this.current = parseInt(options.current)
                if (typeof options.comm == 'string') {
                    setTimeout(() => {
                        this.fnCommOpen()
                    }, 1000)
                }
            }
        },
        computed: {
            // 动态信息
            trendData() {
                return this.$store.getters['trend/getDynamics']
            },
            // 动态点赞列表数据
            topListData() {
                var data = this.$store.getters['interact/getTopListData'];
                return data;
            },
            // 动态评论列表数据
            commentListData() {
                var data = this.$store.getters['interact/getCommentListData'];
                console.log(data);
                return data;
            },
            // 计算是否得到用户信息
            calUser() {
                return this.trendData.user_info || false
            },
            // 计算是否得到用户信息
            calUserFriend() {
                return this.trendData.user_friend || false
            },
            // 计算显示用户头像
            calUserAvater() {
                return !!this.calUser ? this.calUser.user_head : '/static/default_avatar.png'
            },
            // 计算格式友好时间 几天前
            calDatetime() {
                return fnFormatDate(this.trendData.created_time);
            },
            // 计算显示图片格
            calImageSrcs() {
                let imgArray = this.trendData.dynamic_imgs;
                if (imgArray) {
                    imgArray = imgArray.map(item => item ? item : '/static/default_image.png')
                }
                return imgArray
            },
            //
        },
        methods: {
            // mescroll组件初始化完成的回调
            mescrollInit(mescroll) {
                this.mescroll = mescroll;
            },
            // 上拉加载的回调: mescroll携带page的参数, 其中num:当前页 从1开始, size:每页数据条数,默认10
            upCallback(mescroll) {
                let params = {
                    dynamic_id: this.trendData.dynamic_id,
                    object_type: this.trendData.object_type,
                    last_id: this.last_id,
                }
                if (mescroll.num == 1) {
                    // 获取详情信息
                    getTrendInfo(this.dynamic_id).then(trendRes => {
                        this.$store.commit('trend/setTrendData', trendRes.data.data)
                        params.dynamic_id = trendRes.data.data.dynamic_id;
                        params.object_type = trendRes.data.data.object_type;

                        // 获取点赞列表8项
                        return getTopList(params)
                    }).then(topRes => {
                        this.$store.commit('interact/setTopListData', topRes.data.data);

                        // 获取评论列表
                        return this.getComment(params, mescroll);
                    }).then(commRes => {
                        this.$store.commit('interact/setCommentListData', commRes.data.data)
                        mescroll.endSuccess(commRes.data.data.length, commRes.data.data.length >= mescroll.size);
                    }).catch(() => {
                        mescroll.endSuccess(0, false);
                    })
                    return
                } else {
                    this.getComment(params, mescroll);
                }
            },
            getComment(params, mescroll) {
                // 继续上拉获取评论
                getCommentList(params).then(result => {
                    this.last_id = result.data.last_id;
                    this.$store.commit('interact/setCommentListData', this.commentListData.concat(result.data.data))
                    mescroll.endSuccess(result.data.data.length, result.data.data.length >= mescroll.size);
                }).catch(() => {
                    mescroll.endErr();
                });
            },
            // 跳转用户信息页
            fnUserInfo(e) {
                uni.navigateTo({
                    url: `/pages/user-info/user-info?id=${e.user_id}`
                })
            },
            // 跳转荟吧
            fnHuiba(id) {
                uni.navigateTo({
                    url: `/pages/huiba-details/huiba-details?id=${id}`
                })
            },
            // 跳转点赞列表
            fnTopList() {
                uni.navigateTo({
                    url: `/pages/top-list/top-list?dynamic_id=${this.trendData.dynamic_id}&object_type=${this.trendData.object_type}`
                })
            },
            // 跳转评论列表
            fnMoreComm(reply_id) {
                uni.navigateTo({
                    url: `/pages/comm-list/comm-list?reply_id=${reply_id}&dynamic_id=${this.dynamic_id}`
                })
            },
            // 分享图标
            fnShare() {
                this.$refs.share.open(this.trendData);
            },

            // 详情点赞
            fnTop() {
                let filItem = {};
                let params = {
                    dynamic_id: this.trendData.dynamic_id,
                    objecttype: this.trendData.object_type,
                }
                // 来自主要跳转
                if (this.fromPage == 'home') {
                    // 推荐
                    if (this.current == 0) filItem = this.$store.getters['trend/getMainData'].filter(item => item.dynamic_id ==
                        this.trendData.dynamic_id)[0];
                    // 关注
                    if (this.current == 1) filItem = this.$store.getters['trend/getAtteData'].filter(item => item.dynamic_id ==
                        this.trendData.dynamic_id)[0];
                    // 广场
                    if (this.current == 2) filItem = this.$store.getters['trend/getSquareData'].filter(item => item.dynamic_id ==
                        this.trendData.dynamic_id)[0];
                }
                // 来自用户详情
                if (this.fromPage == 'userinfo') {
                    // 发布
                    if (this.current == 0) filItem = this.$store.getters['user/getUserPublishListData'].filter(item => item.dynamic_id ==
                        this.trendData.dynamic_id)[0];
                    // 赞过
                    if (this.current == 1) filItem = this.$store.getters['user/getUserTopListData'].filter(item => item.dynamic_id ==
                        this.trendData.dynamic_id)[0];
                }
                // 用户是否已经点过赞
                if (filItem.is_prise || false) {
                    setPraise(params).then(result => {
                        if (result.data.status == 1) {
                            filItem.praise_count--;
                            filItem.is_prise = false
                            this.trendData.praise_count--;
                            this.trendData.is_prise = false;
                            // 点赞列表减头像
                            let filTopList = this.topListData.filter(item => item.user_info.user_id != this.$store.getters[
                                'user/getUserInfoData'].user_id)
                            this.$store.commit('interact/setTopListData', filTopList);
                        }
                    })
                } else {
                    setPraise(params).then(result => {
                        if (result.data.status == 1) {
                            filItem.praise_count++;
                            filItem.UserTop = true;
                            this.trendData.praise_count++;
                            this.trendData.is_prise = true;
                            // 点赞列表加头像
                            this.topListData.unshift({
                                User: this.$store.getters['user/getUserInfoData']
                            });
                        }
                    })
                }
            },
            // 评论点赞
            fnTopComm(e) {
                let filItem = this.commentListData.filter(item => item.ID == e.ID)[0];
                if (filItem.UserTop) {
                    delCommentTop(filItem.ID).then(result => {
                        if (result.data.data == false) return
                        filItem.praise_count--;
                        filItem.UserTop = false
                    })
                } else {
                    addCommentTop(filItem.ID).then(result => {
                        if (result.data.data == false) return
                        filItem.praise_count++;
                        filItem.UserTop = true
                    })
                }
            },
            // 关注详情发布用户
            fnAtte(e) {
                // 用户是否已经关注
                if (e.UserAtte) {
                    delUserAtte(e.ID).then(delRes => {
                        if (delRes.data.data == false) return
                        this.trendData.User.UserAtte = false
                        // 来自主要跳转
                        if (this.fromPage == 'home') {
                            this.$store.getters['trend/getMainData'].filter(item => item.User.ID == e.ID).map(item => item.User.UserAtte =
                                false)
                            this.$store.getters['trend/getAtteData'].filter(item => item.User.ID == e.ID).map(item => item.User.UserAtte =
                                false)
                            this.$store.getters['trend/getSquareData'].filter(item => item.User.ID == e.ID).map(item => item.User
                                .UserAtte = false)
                        }
                        // 来自用户详情
                        if (this.fromPage == 'userinfo') {
                            this.$store.getters['user/getUserPublishListData'].filter(item => item.User.ID == e.ID).map(item =>
                                item.User.UserAtte =
                                    false)
                            this.$store.getters['user/getUserTopListData'].filter(item => item.User.ID == e.ID).map(item => item.User
                                .UserAtte = false)
                        }
                        // 登录用户关注数减
                        let tempUser = this.$store.getters['user/getUserInfoData']
                        tempUser.AtteCount--
                        this.$store.commit('user/setUserInfoData', tempUser)
                    })
                } else {
                    addUserAtte(e.ID).then(addRes => {
                        if (addRes.data.data == false) return
                        this.trendData.User.UserAtte = true
                        // 来自主要跳转
                        if (this.fromPage == 'home') {
                            this.$store.getters['trend/getMainData'].filter(item => item.User.ID == e.ID).map(item => item.User.UserAtte =
                                true)
                            this.$store.getters['trend/getAtteData'].filter(item => item.User.ID == e.ID).map(item => item.User.UserAtte =
                                true)
                            this.$store.getters['trend/getSquareData'].filter(item => item.User.ID == e.ID).map(item => item.User
                                .UserAtte = true)
                        }
                        // 来自用户详情
                        if (this.fromPage == 'userinfo') {
                            this.$store.getters['user/getUserPublishListData'].filter(item => item.User.ID == e.ID).map(item =>
                                item.User.UserAtte =
                                    true)
                            this.$store.getters['user/getUserTopListData'].filter(item => item.User.ID == e.ID).map(item => item.User
                                .UserAtte = true)
                        }
                        // 登录用户关注数加
                        let tempUser = this.$store.getters['user/getUserInfoData']
                        tempUser.AtteCount++
                        this.$store.commit('user/setUserInfoData', tempUser)
                    })
                }
            },
            // 详情收藏
            fnSave() {
                let filItem = {};
                let params = {
                    dynamic_id: this.trendData.dynamic_id,
                    object_type: this.trendData.object_type,
                }
                // 来自主要跳转
                if (this.fromPage == 'home') {
                    // 推荐
                    if (this.current == 0) filItem = this.$store.getters['trend/getMainData'].filter(item => item.dynamic_id ==
                        this.trendData.dynamic_id)[0];
                    // 关注
                    if (this.current == 1) filItem = this.$store.getters['trend/getAtteData'].filter(item => item.dynamic_id ==
                        this.trendData.dynamic_id)[0];
                    // 广场
                    if (this.current == 2) filItem = this.$store.getters['trend/getSquareData'].filter(item => item.dynamic_id ==
                        this.trendData.dynamic_id)[0];
                }
                // 来自用户详情
                if (this.fromPage == 'userinfo') {
                    // 发布
                    if (this.current == 0) filItem = this.$store.getters['user/getUserPublishListData'].filter(item => item.dynamic_id ==
                        this.trendData.dynamic_id)[0];
                    // 赞过
                    if (this.current == 1) filItem = this.$store.getters['user/getUserTopListData'].filter(item => item.dynamic_id ==
                        this.trendData.dynamic_id)[0];
                }
                // 用户是否已经收藏
                if (filItem.is_collection || false) {
                    setCollection(params).then(result => {
                        if (result.data.status == 1) {
                            filItem.collection_count--;
                            filItem.is_collection = false;
                            this.trendData.collection_count--;
                            this.trendData.is_collection = false;
                        }
                    })
                } else {
                    setCollection(params).then(result => {
                        if (result.data.status == 1) {
                            filItem.collection_count++;
                            filItem.is_collection = true;
                            this.trendData.collection_count++;
                            this.trendData.is_collection = true;
                        }
                    })
                }
            },
            // 显示评论输入框
            fnCommOpen() {
                this.$refs.comm.open({
                    type: 'comment',
                    content: this.$store.getters['getCommContentData'],
                    object_type: this.trendData.object_type,
                    dynamic_id: this.trendData.dynamic_id
                });
            },
            // 评论发送
            fnCommSend(e) {
                console.log(e);
                // 不为发送时保存输入值
                if (e.type == 'comment') this.$store.commit('setCommContentData', e.content)
                if (e.state == false) return
                // 无内容信息反馈
                if (e.content == '') {
                    uni.showToast({
                        title: "评论内容不能为空",
                        icon: 'none'
                    })
                    return
                }
                // 提交评论
                uni.showLoading({
                    title: '提交中'
                })
                delete e.state
                delete e.type
                e.fromclient = 'android'
                addComment(e).then(addRes => {
                    console.log(e);
                    console.log(addRes);
                    if (addRes.status != 200) return
                    console.log(this.replyParentID);
                    if (this.replyParentID == 0) {
                        // 无回复项
                        let filCommentList = this.commentListData.filter(item => item.reply_id == e.parentid)[0]
                        console.log(filCommentList);

                        filCommentList.replies = filCommentList.replies.concat([addRes.data.data])
                    } else if (this.replyParentID > 0) {
                        // 有回复项追加
                        let filCommentList = this.commentListData.filter(item => item.reply_id == this.replyParentID)[0]
                        console.log(filCommentList);
                        filCommentList.replies = filCommentList.replies.concat([addRes.data.data])
                    } else {
                        // 评论发布
                        this.commentListData.unshift(addRes.data.data)
                        this.$store.commit('setCommContentData', '')
                    }
                    // 评论数量添加
                    if (this.trendData.reply_count == 0) this.mescroll.removeEmpty()
                    this.trendData.reply_count++
                    this.$refs.comm.visible = false;
                    this.replyParentID == -1
                    uni.hideLoading()
                    uni.showToast({
                        title: '评论成功'
                    })
                    // 改变上一窗口的数据
                    let filItem = []
                    // 来自主要跳转
                    if (this.fromPage == 'home') {
                        // 推荐
                        if (this.current == 0) filItem = this.$store.getters['trend/getMainData'].filter(item => item.dynamic_id ==
                            this.trendData.dynamic_id)[0];
                        // 关注
                        if (this.current == 1) filItem = this.$store.getters['trend/getAtteData'].filter(item => item.dynamic_id ==
                            this.trendData.dynamic_id)[0];
                        // 广场
                        if (this.current == 2) filItem = this.$store.getters['trend/getSquareData'].filter(item => item.dynamic_id ==
                            this.trendData.dynamic_id)[0];
                    }
                    // 来自用户详情
                    if (this.fromPage == 'userinfo') {
                        // 发布
                        if (this.current == 0) filItem = this.$store.getters['user/getUserPublishListData'].filter(item => item
                                .dynamic_id ==
                            this.trendData.dynamic_id)[0];
                        // 赞过
                        if (this.current == 1) filItem = this.$store.getters['user/getUserTopListData'].filter(item => item.dynamic_id ==
                            this.trendData.dynamic_id)[0];
                    }
                    filItem.reply_count++
                })
            },
            // 评论项操作
            fnComm(e) {
                console.log(e);
                let itemList = ['回复', '复制', '举报'];
                if (e.user_info.user_id == this.$store.getters['user/getUserInfoData'].user_id) itemList.push('删除')
                uni.showActionSheet({
                    itemList,
                    success: res => {
                        console.log(res);
                        switch (res.tapIndex) {
                            case 0:
                                this.$refs.comm.open({
                                    type: 'reply',
                                    user: e.user_info.nick_name,
                                    object_type: e.object_type,
                                    dynamic_id: e.dynamic_id,
                                    reply_user: e.user_id,
                                    parent_reply: e.parent_id || e.reply_id,
                                    parent_id: e.reply_id,
                                });
                                this.replyParentID = e.parent_reply
                                break;
                            case 1:
                                uni.setClipboardData({
                                    data: e.Content
                                });
                                break;
                            case 2:
                                uni.navigateTo({
                                    url: `/pages/report/report?id=${e.dynamic_id}&type=${e.object_type}`
                                })
                                break;
                            case 3:
                                delComment(e.reply_id).then(delRes => {
                                    console.log(delRes);
                                    if (delRes.data.status != 1) return
                                    if (e.parent_reply > 0) {
                                        // 有回复项删减
                                        let filCommentList = this.commentListData.filter(item => item.reply_id == e.parent_reply)[0]
                                        let filCommentChilds = filCommentList.CommentChilds.filter(item => item.reply_id != e.reply_id)
                                        filCommentList.replies = filCommentChilds
                                    } else {
                                        // 评论发布项删除
                                        let filCommentList = this.commentListData.filter(item => item.reply_id != e.reply_id)
                                        this.$store.commit('interact/setCommentListData', filCommentList)
                                    }
                                    // 评论数量减少
                                    this.trendData.reply_count--
                                    if (this.trendData.reply_count == 0) this.mescroll.showEmpty()
                                    // 改变上一窗口的数据
                                    let filItem = []
                                    // 来自主要跳转
                                    if (this.fromPage == 'home') {
                                        // 推荐
                                        if (this.current == 0) filItem = this.$store.getters['trend/getMainData'].filter(item =>
                                            item.dynamic_id ==
                                            this.trendData.dynamic_id)[0];
                                        // 关注
                                        if (this.current == 1) filItem = this.$store.getters['trend/getAtteData'].filter(item =>
                                            item.dynamic_id ==
                                            this.trendData.dynamic_id)[0];
                                        // 广场
                                        if (this.current == 2) filItem = this.$store.getters['trend/getSquareData'].filter(item =>
                                            item
                                                .dynamic_id ==
                                            this.trendData.dynamic_id)[0];
                                    }
                                    // 来自用户详情
                                    if (this.fromPage == 'userinfo') {
                                        // 发布
                                        if (this.current == 0) filItem = this.$store.getters['user/getUserPublishListData'].filter(
                                            item => item.dynamic_id ==
                                                this.trendData.dynamic_id)[0];
                                        // 赞过
                                        if (this.current == 1) filItem = this.$store.getters['user/getUserTopListData'].filter(
                                            item => item.dynamic_id ==
                                                this.trendData.dynamic_id)[0];
                                    }
                                    filItem.reply_count--
                                })
                                break;
                            default:
                                break;
                        }
                    }
                })
            },
            // 预览图片组
            fnPreviewImage(current) {
                let urls = this.trendData.dynamic_imgs.map(url => url)
                previewImage(current, urls)
            },
            //
        },

        beforeDestroy() {
            // 清空预评论内容
            this.$store.commit('setCommContentData', '')
        }
    }
</script>

<style></style>
