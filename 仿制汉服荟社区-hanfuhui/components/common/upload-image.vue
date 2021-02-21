<template>
	<view class="uni-list list-pd">
		<view class="uni-list-cell cell-pd">
			<view class="uni-uploader">
				<view class="uni-uploader-head">
					<view class="uni-uploader-title">点击可预览选好的图片</view>
					<view class="uni-uploader-info">{{imageList.length}}/9</view>
				</view>
				<view class="uni-uploader-body">
					<view class="uni-uploader__files">
						<block v-for="(image,index) in imageList" :key="image">
							<view class="uni-uploader__file">
								<view class="icon iconfont icon-shanchu" @tap="deleted(index)"></view>
								<image class="uni-uploader__img" mode="aspectFill" :src="image" :data-src="image" @tap="previewImage" />
							</view>
						</block>
						<view class="uni-uploader__input-box">
							<view class="uni-uploader__input" @tap="chooseImage"></view>
						</view>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		props: {
			imageList: Array
		},
		methods: {
			deleted(index1) {
				this.$emit('deleted', index1);
			},
			previewImage(index) {
				console.log(index)
				this.$emit('previewImage', index);
			},
			chooseImage() {
				this.$emit('chooseImage');
			}
		}
	}
</script>

<style scoped>
	@import './icon.css';
	
	body {
		overflow-x: hidden;
	}
	body,
	html {
		user-select: none;
		width: 100%;
		height: 100%;
	}
	uni-page-body,uni-page-wrapper, html, body{
	    background-color: #FFFFFF;
	}
	
	
	/**
	 * 顶部 --- 开始
	 */
	.iconfont {
	    font-family: "iconfont" !important;
	    font-size: 16px;
	    font-style: normal;
	    -webkit-font-smoothing: antialiased;
	    -moz-osx-font-smoothing: grayscale;
	}
	.icon-xialazhankai:before {
	    content: "\e60e";
	}
	.icon-xialazhankai:before {
	    content: "\e60e";
	}
	.uni-navbar__content .uni-navbar__content_view[data-v-796663f8] {
	    display: -webkit-box;
	    display: -webkit-flex;
	    display: flex;
	    -webkit-box-align: center;
	    -webkit-align-items: center;
	    align-items: center;
	}
	.uni-navbar__header-container[data-v-796663f8] {
	    width: 100%;
	    margin: 0 4px;
	}
	.widthatch[data-v-f329d474] {
	    margin: 0 auto;
	    padding-right: 9px;
	    color: #010101;
	    font-weight: bold;
	    font-size: 15px;
	}
	.u-f-ajc {
	    -webkit-box-pack: center;
	    -webkit-justify-content: center;
	    justify-content: center;
	}
	.u-f-a, .u-f-aj, .u-f-ajc, .u-f-ajr {
	    -webkit-box-align: center;
	    -webkit-align-items: center;
	    align-items: center;
	}
	.u-f-d, .u-f-a, .u-f-aj, .u-f-ajc, .u-f-ajr {
	    display: -webkit-box;
	    display: -webkit-flex;
	    display: flex;
	}
	.uni-navbar__header-btns[data-v-796663f8]:last-child {
	    width: 33px;
	    color: #010101;
	    font-weight: bold;
	}
	.uni-navbar__content .uni-navbar__content_view[data-v-796663f8] {
	    display: -webkit-box;
	    display: -webkit-flex;
	    display: flex;
	    -webkit-box-align: center;
	    -webkit-align-items: center;
	    align-items: center;
	}
	.uni-navbar__header-btns[data-v-796663f8] {
	    display: -webkit-inline-box;
	    display: -webkit-inline-flex;
	    display: inline-flex;
	    -webkit-flex-wrap: nowrap;
	    flex-wrap: nowrap;
	    -webkit-flex-shrink: 0;
	    flex-shrink: 0;
	    width: 57px;
	    padding: 0 5px;
	}
	.uni-navbar__content .uni-navbar__content_view[data-v-796663f8] {
	    display: -webkit-box;
	    display: -webkit-flex;
	    display: flex;
	    -webkit-box-align: center;
	    -webkit-align-items: center;
	    align-items: center;
	}
	.uni-navbar--border[data-v-796663f8]:after {
	    position: absolute;
	    z-index: 3;
	    bottom: 0;
	    left: 0;
	    right: 0;
	    height: 1px;
	    content: '';
	    -webkit-transform: scaleY(.5);
	    transform: scaleY(.5);
	    background-color: #c8c7cc;
	}
	.icon-fanhui:before {
	    content: "\e60d";
	}
	/**
	 * 顶部 --- 结束
	 */
	
	
	
	
	
	
	
	uni-app,
	uni-page-head {
		display: block;
		box-sizing: border-box;
	}
	uni-app {
		width: 100%;
		height: 100%;
	}
	uni-page {
		display: block;
		width: 100%;
		height: 100%;
	}

	uni-page-wrapper {
		display: block;
		height: 100%;
		position: relative;
	}


	uni-textarea {
		width: 300px;
		height: 150px;
		display: block;
		position: relative;
		font-size: 16px;
		line-height: normal;
		white-space: pre-wrap;
		word-break: break-all;
	}

	uni-page-body,
	uni-page-refresh {
		display: block;
		box-sizing: border-box;
		width: 100%;
	}

	uni-view {
		display: block;
	}

	.uni-textarea {
		width: 100%;
		background: #FFF;
	}

	.uni-textarea-wrapper {
		display: block;
		position: relative;
		width: 100%;
		height: 100%;
	}

	.uni-textarea-compute,
	.uni-textarea-line,
	.uni-textarea-placeholder,
	.uni-textarea-textarea,
	.uni-textarea-wrapper {
		outline: none;
		border-width: initial;
		border-style: none;
		border-color: initial;
		border-image: initial;
		padding: 0px;
		margin: 0px;
		text-decoration: inherit;
	}



	.cell-pd {
		padding: 22upx 30upx;
	}

	.list-pd {
		margin-top: 50upx;
	}

	.icon-shanchu {
		position: absolute;
		top: 0;
		right: 0;
		background-color: #010101;
		color: #FFFFFF;
		padding: 0 10upx;
		border-radius: 10upx;
		font-size: 30upx;
		z-index: 100;
	}

	.uni-textarea-textarea {
		resize: none;
		color: inherit;
		opacity: 1;
		letter-spacing: inherit;
		text-align: inherit;
		text-indent: inherit;
		text-transform: inherit;
		text-shadow: inherit;
		font: inherit;
	}

	.uni-textarea-compute,
	.uni-textarea-line,
	.uni-textarea-placeholder,
	.uni-textarea-textarea {
		position: absolute;
		width: 100%;
		height: 100%;
		left: 0px;
		top: 0px;
		white-space: inherit;
		word-break: inherit;
	}

	textarea {
		-webkit-writing-mode: horizontal-tb !important;
		text-rendering: auto;
		color: -internal-light-dark(black, white);
		letter-spacing: normal;
		word-spacing: normal;
		text-transform: none;
		text-indent: 0px;
		text-shadow: none;
		display: inline-block;
		text-align: start;
		appearance: textarea;
		background-color: -internal-light-dark(rgb(255, 255, 255), rgb(59, 59, 59));
		-webkit-rtl-ordering: logical;
		flex-direction: column;
		resize: auto;
		cursor: text;
		white-space: pre-wrap;
		overflow-wrap: break-word;
		margin: 0em;
		font: 400 13.3333px Arial;
		border-width: 1px;
		border-style: solid;
		border-color: -internal-light-dark(rgb(118, 118, 118), rgb(133, 133, 133));
		border-image: initial;
		padding: 2px;
	}

	uni-view {
		font-size: 14px;
		line-height: 1.8;
	}

	.uni-uploader-info {
		color: #B2B2B2;
	}

	.uni-uploader__input-box {
		position: relative;
		margin: 6px;
		width: 104px;
		height: 104px;
		border-radius: 5px;
		border: 2px solid #010101;
	}

	.uni-uploader__input-box:before {
		width: 2px;
		height: 39px;
	}

	.uni-uploader__input-box:before,
	.uni-uploader__input-box:after {
		content: " ";
		position: absolute;
		top: 50%;
		left: 50%;
		-webkit-transform: translate(-50%, -50%);
		transform: translate(-50%, -50%);
		background-color: #010101;
	}

	.uni-uploader__input-box:after {
		width: 39px;
		height: 2px;
	}

	.uni-textarea-compute,
	.uni-textarea-line,
	.uni-textarea-placeholder,
	.uni-textarea-textarea {
		position: absolute;
		width: 100%;
		height: 100%;
		left: 0px;
		top: 0px;
		white-space: inherit;
		word-break: inherit;
	}

	.uni-textarea-compute,
	.uni-textarea-line {
		visibility: hidden;
		height: auto;
	}

	.uni-textarea-line {
		width: 1em;
	}

	.uni-textarea-compute,
	.uni-textarea-line {
		visibility: hidden;
		height: auto;
	}

	.uni-textarea-compute,
	.uni-textarea-line,
	.uni-textarea-placeholder,
	.uni-textarea-textarea {
		position: absolute;
		width: 100%;
		height: 100%;
		left: 0px;
		top: 0px;
		white-space: inherit;
		word-break: inherit;
	}
	
	
	
	.uni-uploader__file {
		position: relative;
	}
	.uni-uploader__input {
		position: absolute;
		z-index: 1;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		opacity: 0;
	}

	.list-pd[data-v-21a75b2e] {
	    margin-top: 25px;
	}
	.uni-list {
	    background-color: #FFFFFF;
	    position: relative;
	    width: 100%;
	    display: -webkit-box;
	    display: -webkit-flex;
	    display: flex;
	    -webkit-box-orient: vertical;
	    -webkit-box-direction: normal;
	    -webkit-flex-direction: column;
	    flex-direction: column;
	}
	.uni-list::before {
	    position: absolute;
	    z-index: 10;
	    right: 0;
	    top: 0;
	    left: 0;
	    height: 0;
	    content: '';
	    -webkit-transform: scaleY(.5);
	    transform: scaleY(.5);
	    background-color: #c8c7cc;
	}
	.cell-pd[data-v-21a75b2e] {
	    padding: 11px 15px;
	}
	.uni-list-cell {
	    position: relative;
	    display: -webkit-box;
	    display: -webkit-flex;
	    display: flex;
	    -webkit-box-orient: horizontal;
	    -webkit-box-direction: normal;
	    -webkit-flex-direction: row;
	    flex-direction: row;
	    -webkit-box-pack: justify;
	    -webkit-justify-content: space-between;
	    justify-content: space-between;
	    -webkit-box-align: center;
	    -webkit-align-items: center;
	    align-items: center;
	}
	.uni-uploader {
	    -webkit-box-flex: 1;
	    -webkit-flex: 1;
	    flex: 1;
	    -webkit-box-orient: vertical;
	    -webkit-box-direction: normal;
	    -webkit-flex-direction: column;
	    flex-direction: column;
	}
	.uni-uploader-head {
	    display: -webkit-box;
	    display: -webkit-flex;
	    display: flex;
	    -webkit-box-orient: horizontal;
	    -webkit-box-direction: normal;
	    -webkit-flex-direction: row;
	    flex-direction: row;
	    -webkit-box-pack: justify;
	    -webkit-justify-content: space-between;
	    justify-content: space-between;
	}
	.uni-uploader-body {
	    margin-top: 8px;
	}
	.uni-uploader__files {
	    display: -webkit-box;
	    display: -webkit-flex;
	    display: flex;
	    -webkit-box-orient: horizontal;
	    -webkit-box-direction: normal;
	    -webkit-flex-direction: row;
	    flex-direction: row;
	    -webkit-flex-wrap: wrap;
	    flex-wrap: wrap;
	}
	.uni-uploader__file[data-v-21a75b2e] {
	    position: relative;
	}
	.uni-uploader__file {
	    margin: 6px;
	    width: 105px;
	    height: 105px;
	}
	.icon-shanchu[data-v-21a75b2e] {
	    position: absolute;
	    top: 0;
	    right: 0;
	    background-color: #010101;
	    color: #FFFFFF;
	    padding: 0 5px;
	    border-radius: 5px;
	    font-size: 15px;
	    z-index: 100;
	}
	.icon-shanchu:before {
	    content: "\e605";
	}
	.uni-uploader__img {
	    display: block;
	    width: 105px;
	    height: 105px;
	    border-radius: 5px;
	    border: 2px solid #010101;
	}
	uni-image {
	    will-change: transform;
	}
	uni-image > div, uni-image > img {
	    width: 100%;
	    height: 100%;
	}
	uni-image > img {
	    user-select: none;
	    display: block;
	    position: absolute;
	    top: 0px;
	    left: 0px;
	    opacity: 0;
	}
</style>
