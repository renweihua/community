<template>
	<view>
		<view class="uni-navbar__header-btns uni-navbar__content_view push-text" @tap="onClickRight">
			<!-- 优先显示图标 -->
			<view class="uni-navbar-btn-text uni-navbar__content_view" style="font-size: 32upx;">发布</view>
			<slot name="right" />
		</view>
		<!--输入的内容-->
		<view class="uni-input mb18r" v-if="dynamic_type != 0">
			<input v-model="dynamic_title" type="text" value="" placeholder="请输入标题" />
		</view>
		<!--输入的内容-->
		<view class="uni-textarea mb18r">
			<textarea v-model="dynamic_content" placeholder="说点什么吧..." />
		</view>
		<view class="background: #F8F8F8;height:1px;"></view>
		<!--选择图片-->
		<uploadImage
		:imageList="imageList"
		@deleted="deleted($event)"
		@previewImage="previewImage"
		@chooseImage="chooseImage"
		:max_nums="max_nums"
		class="mb18r"
		>
		</uploadImage>
		<view class="background: #F8F8F8;height:1px;"></view>
        <view class="uni-list">
            <view class="uni-list-cell">
                <view class="uni-list-cell-left">
                    所属话题：
                </view>
                <view class="uni-list-cell-db">
                    <picker @change="selectTopic" :value="index" :range="topics" range-key="topic_name">
                        <view class="uni-input">{{topic_name}}</view>
                    </picker>
                </view>
            </view>
        </view>
		<!--弹出公告-->
		<uni-popup :show="show">
			   <view class="gonggao" :class="donghua">
				   <image src="../../static/gonggao/gonggao.png" mode="aspectFit"></image>
				   <view class="zhuyi">1.涉及黄色，政治，广告及骚扰信息，涉及黄色，政治，广告及骚扰信息</view>
				   <view class="zhuyi">一经核实将被封禁，情节严重者永久封禁！</view>
				   <button type="default" @tap="ttt">朕知道了</button>
			   </view>
		</uni-popup>
	</view>
</template>

<script>
	// 话题列表
	import { getTopicList } from '@/api/HuibaServer.js';
	import {
		batchUploads,
	} from "@/api/CommonServer.js";
	import {
		pushDynamic,
	} from "@/api/dynamic.js";

	import uploadImage from '@/components/common/upload-image.vue';
	import uniPopup from '@/components/uni-popup/uni-popup.vue';
	let arry=['所有人可见','仅自己可见'];
	var sourceType = [
		    ['camera'],
		    ['album'],
		    ['camera', 'album']
	]
	var sizeType = [
		    ['compressed'],
		    ['original'],
		    ['compressed', 'original']
	]
	export default {
		components:{
			uploadImage,
			uniPopup
		},
		data() {
			return {
				// 话题列表
				topics: [],
				index: 0,
				topic_name: '请选择话题',
				
				push_finish: false,
				fanhui:true,
				donghua:'animated zoomInDown',
				// 是否展示弹窗
				show: false,
				yinsi:'所有人可见',
				imageList: [],
				image_files:[],
				sourceTypeIndex: 2,
				sourceType: ['拍照', '相册', '拍照或相册'],
				sizeTypeIndex: 2,
				sizeType: ['压缩', '原图', '压缩或原图'],
				countIndex: 8,
				count: [1, 2, 3, 4, 5, 6, 7, 8, 9],
				
				// 图片上传数量
				max_nums: 9,
				// 发布信息
				dynamic_title: '',
				dynamic_content:'',
				dynamic_type: 0,
				topic_id: 0,
			}
		},
		onLoad(options) {
			this.dynamic_type = options.dynamic_type || 0;
			// dynamic_type == 3：摄影相册，图片数量上限扩大
			this.max_nums = this.dynamic_type == 3 ? 15 : 9;
			// 获取话题列表
			getTopicList().then(res => {
				this.topics = res.data;
			});
		},
		methods: {
	        selectTopic: function(e) {
	        	// 话题Id
	            this.topic_id = e.target.value;
	            // 话题名称
	            this.topic_name = this.topics[e.target.value].topic_name || '';
	        },
			onClickRight() {
				this.$emit('click-right');
				if (this.pushing) return;
				this.pushing = true;
				uni.showLoading({
					title: '发布中',
					mask: true
				})
				// 批量上传图片
				batchUploads(this.image_files).then(files => {
					return pushDynamic({
						'topic_id': this.topic_id,
						'dynamic_title': this.dynamic_title,
						'dynamic_type': this.dynamic_type,
						'dynamic_images': files.join(','),
						'dynamic_content' : this.dynamic_content,
					});
				}).then(res => {
					uni.hideLoading();
					if(!res.status){
						uni.showToast({
							title: res.msg,
							icon: 'none'
						})
						return;
					}else{
						this.push_finish = true;
						uni.showToast({
							title: res.msg,
							icon: 'none'
						});
						
						setTimeout(() => {
							// 返回上一页
							uni.navigateBack({
								delta:2
							});
						}, 1500);
					}
				}).catch((e) => {
					console.log(e);
				})
				this.pushing = false;
			},
			ttt(){
				this.donghua='animated zoomInDown'
				this.show=false
			},
			deleted(index){
				uni.showModal({
				    title: '提示',
				    content: '确定移除该图片？',
				    success: (res) =>{
				        if (res.confirm) {
				           this.imageList.splice(index,1)
						   this.image_files.splice(index,1)
				        }
				    }
				});
			},
			chooseImage: async function() {
			    // #ifdef APP-PLUS
			    // TODO 选择相机或相册时 需要弹出actionsheet，目前无法获得是相机还是相册，在失败回调中处理
			    if (this.sourceTypeIndex !== 2) {
			        let status = await this.checkPermission();
			        if (status !== 1) {
			            return;
			        }
			    }
			    // #endif

			    if (this.imageList.length === this.max_nums) {
			        let isContinue = await this.isFullImg();
			        console.log("是否继续?", isContinue);
			        if (!isContinue) {
			            return;
			        }
			    }
			    uni.chooseImage({
			        sourceType: sourceType[this.sourceTypeIndex],
			        sizeType: sizeType[this.sizeTypeIndex],
			        count: this.imageList.length + this.count[this.countIndex] > this.max_nums ? this.max_nums - this.imageList.length :
			            this.count[this.countIndex],
			        success: (res) => {
						console.log(res);
			            this.imageList = this.imageList.concat(res.tempFilePaths);
						this.image_files = this.image_files.concat(res.tempFiles);
						console.log(this.image_files);
			        },
			        fail: (err) => {
			            // #ifdef APP-PLUS
			            if (err['code'] && err.code !== 0 && this.sourceTypeIndex === 2) {
			                this.checkPermission(err.code);
			            }
			            // #endif
			        }
			    })
			},
			previewImage: function(e) {
				console.log(e);
			    var current = e.target.dataset.src
			    uni.previewImage({
			        current: current,
			        urls: this.imageList,
					indicator:'default'
			    })
			},
			back(){
				uni.navigateBack({
					delta:1
				})
			},
			submit(){
				
			},
			changeyinsi(){
				uni.showActionSheet({
				    itemList: arry,
				    success:(res)=> {
				     //   console.log('选中了第' + (res.tapIndex + 1) + '个按钮');
						this.yinsi=arry[res.tapIndex];
				    }
				});
			}
		}
	}
</script>

<style>
	.push-text{
		font-size: 15px;
		font-weight: bold;
		display: -webkit-box;
		display: -webkit-flex;
		display: flex;
		-webkit-box-align: center;
		-webkit-align-items: center;
		align-items: center;
		position: absolute;
		    right: 9px;
		    z-index: 999;
		    top: -31px;
	}

	.widthatch{
		margin: 0 auto;
		padding-right: 20upx;
		color: #010101;
		font-weight: bold;
		font-size: 32upx;
	}
	.gonggao{
		width: 500upx;
		background-color: #FFFFFF;
		border-radius: 10upx;
		padding: 30upx 40upx;
	}
	.gonggao image{
		width: 90%;
		height: 200rpx;
	}
	.gonggao button{
		background-color: #FFE934;
		margin-top: 40upx;
		border-radius: 10upx;
	}
	.zhuyi{
		text-align: left;
		}
		uni-input{
			width: 96%;
			padding: 9px 2%;
			font-size: 16px;
			border-bottom: 3px solid #FFFFFF;
		}
		uni-textarea{

			width: 96%;
			padding: 9px 2%;
			line-height: 1.6;
			font-size: 16px;
			height: 125px;
			}
</style>
