<template>
	<view>
		<view class="uni-navbar__header-btns uni-navbar__content_view push-text" @tap="onClickRight">
			<!-- 优先显示图标 -->
			<view class="uni-navbar-btn-text uni-navbar__content_view"
			style="font-size: 32upx;">发布</view>
			<slot name="right" />
		</view>
		<!--输入的内容-->
		<view class="uni-textarea">
			<textarea v-model="text" placeholder="说点什么吧..." />
			</view>
		<!--选择图片-->
		<uploadImage
		:imageList="imageList"
		@deleted="deleted($event)"
		@previewImage="previewImage"
		@chooseImage="chooseImage"
		>	
		</uploadImage>
		<!--弹出公告-->
		  <uni-popup :show="show">
			   <view class="gonggao" :class="donghua">
				   <image src="../../static/gonggao/gonggao.png" mode="aspectFit"></image>
				   <view class="zhuyi">1.涉及黄色，政治，广告及骚扰信息，涉及黄色，政治，广告及骚扰信息</view>
				   <view class="zhuyi">2.涉及黄色，政治，广告及骚扰信息，涉及黄色，政治，广告及骚扰信息</view>
				   <view class="zhuyi">3.涉及黄色，政治，广告及骚扰信息，涉及黄色，政治，广告及骚扰信息</view>
				   <view class="zhuyi">一经核实将被封禁，情节严重者永久封禁！</view>
				   <button type="default" @tap="ttt">朕知道了</button>
			   </view>
		</uni-popup>
	</view>
</template>

<script>
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
		onBackPress(ev){
			if(this.text==''&&this.imageList.length==0){return;}
			if(this.fanhui){
				uni.showModal({
				    content: '是否保存',
				    success:  (res)=> {
				        if (res.confirm) {
				            console.log('保存');
				        } else if (res.cancel) {
				            console.log('不保存');
				        }
						this.fanhui=false;
						uni.navigateBack({
							delta:1
						})
				    }
				});
				return true;
			}
				
		},
		data() {
			return {
				fanhui:true,
				donghua:'animated zoomInDown',
				show:true,
				yinsi:'所有人可见',
				text:'',
				imageList: [],
				sourceTypeIndex: 2,
				sourceType: ['拍照', '相册', '拍照或相册'],
				sizeTypeIndex: 2,
				sizeType: ['压缩', '原图', '压缩或原图'],
				countIndex: 8,
				count: [1, 2, 3, 4, 5, 6, 7, 8, 9]
			}
		},
		methods: {
			ttt(){
				this.donghua='animated zoomInDown'
				this.show=false
			},
			deleted(index){
				uni.showModal({
				    title: '提示',
				    content: '是否删除',
				    success: (res) =>{
				        if (res.confirm) {
				           this.imageList.splice(index,1)
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
			
			    if (this.imageList.length === 9) {
			        let isContinue = await this.isFullImg();
			        console.log("是否继续?", isContinue);
			        if (!isContinue) {
			            return;
			        }
			    }
			    uni.chooseImage({
			        sourceType: sourceType[this.sourceTypeIndex],
			        sizeType: sizeType[this.sizeTypeIndex],
			        count: this.imageList.length + this.count[this.countIndex] > 9 ? 9 - this.imageList.length :
			            this.count[this.countIndex],
			        success: (res) => {
			            this.imageList = this.imageList.concat(res.tempFilePaths);
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
		uni-textarea{
			
			width: 96%;
			padding: 9px 2%;
			line-height: 1.6;
			font-size: 16px;
			height: 125px;
			}
</style>
