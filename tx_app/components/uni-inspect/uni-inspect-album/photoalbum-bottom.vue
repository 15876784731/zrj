<template>
	<div class="bottom">
	    <div class="item" @click="chooseImage">
				<image class="innerfirst" src="../../../static/images/actionnow.png"></image>
			</div>
			<!-- <image class="item-inside" src="../../../static/images/camerab.png" ></image>
			<p>拍照</p>
	    </div>
	    <div class="item" >
			<image class="item-inside" src="../../../static/images/delecte.png"></image>
	        <p>我的</p>
	    </div>
		<div class="item" >
			<image class="item-inside" src="../../../static/images/savenew.png"></image>
		    <p>我的</p>
		</div>
		<div class="item" >
			<image class="item-inside" src="../../../static/images/refresh.png"></image>
		    <p>我的</p>
		</div>
		<div class="item" >
			<image class="item-inside" src="../../../static/images/cameraa.png"></image>
		    <p>我的</p>
		</div> -->
	  </div>
</template>

<script>
	import permision from "@/common/permission.js"
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
		
		data () {
			return{
				Date:[1,2],
				sourceTypeIndex:2,
				sourceType:['拍照','相册','拍照或相册'],
				sizeTypeIndex:2,
				sizeType:['压缩','原图','压缩或原图'],
				countIndex:8,
				count:[ 1, 2, 3, 4, 5, 6, 7, 8, 9],
				imagegroup:[]
				
			}
		},
		onUnload() {
			
			this.sourceTypeIndex = 2,
			this.sourceType = ['拍照','相册','拍照或相册'],
			this.sizeTypeIndex = 2,
			this.sizeType = ['压缩','原图','压缩或原图'],
			this.countIndex = 8;
			
		},
		mounted() {
			
		},
		methods: {
			// TogetImage() {
			// 	uni.navigateTo({
			// 		url:`albumbottom-photo?Date=${this.Date}`
			// 	})
			// }
			chooseImage: async function () {
				//#ifdef APP-PLUS
				// TODO 选择相机或相册时 需要弹出actionsheet，目前无法获得是相机还是相册，在失败回调中处理
				if (this.sourceTypeIndex !== 2) {
					let status = await this.checkPermission();
					if (status !== 1) {
						return;
					}
				}
				//#endif
				
				
				uni.chooseImage({
					sourceType: sourceType[this.sourceTypeIndex],
					sizeType: sizeType[this.sizeTypeIndex],
					count: this.imagegroup.length + this.count[this.countIndex] > 9 ? 9 - this.imagegroup.length : this.count[this.countIndex],
					success: (res) => {
						this.imagegroup = this.imagegroup.concat(res.tempFilePaths)
						// this.$emit('change',this.imagegroup)
					},
					fail: (err) => {
						// #ifdef APP-PLUS
						if (err['code'] && err.code !== 0 && this.sourceTypeIndex === 2) {
							this.checkPermission(err.code);
						}
						// #endif
						// #ifdef MP
						uni.getSetting({
							success: (res) => {
								let authStatus = false;
								switch (this.sourceTypeIndex) {
									case 0:
										authStatus = res.authSetting['scope.camera'];
										break;
									case 1:
										authStatus = res.authSetting['scope.album'];
										break;
									case 2:
										authStatus = res.authSetting['scope.album'] && res.authSetting['scope.camera'];
										break;
									default:
										break;
								}
								if (!authStatus) {
									uni.showModal({
										title: '授权失败',
										content: 'Hello uni-app需要从您的相机或相册获取图片，请在设置界面打开相关权限',
										success: (res) => {
											if (res.confirm) {
												uni.openSetting()
											}
										}
									})
								}
							}
						})
						// #endif
					}
				})
			},
			async checkPermission(code) {
				let type = code ? code - 1 : this.sourceTypeIndex;
				let status = permision.isIOS ? await permision.requestIOS(sourceType[type][0]) :
					await permision.requestAndroid(type === 0 ? 'android.permission.CAMERA' :
						'android.permission.READ_EXTERNAL_STORAGE');
			
				if (status === null || status === 1) {
					status = 1;
				} else {
					uni.showModal({
						content: "没有开启权限",
						confirmText: "设置",
						success: function(res) {
							if (res.confirm) {
								permision.gotoAppSetting();
							}
						}
					})
				}
			
				return status;
			}
		}
	}
</script>

<style>
	.bottom{
	    position: fixed;
	    bottom:0px;
	    border-top:1px solid #d2d2d2;
	    width: 100%;
	    height:100px;
	  }
	  .item{
	    /* margin:10px 50rpx; */
	  /*  float:left;
		padding: 15px 50rpx; */
		margin:5ypx 50rpx;
		text-align: center;
	  }
	   .innerfirst    {
			   height: 90px;
			   width: 90px;
		   }
/* 	  .item p{
	    margin:0;
	    font-size: 10px;
	  }
	  .item:active {
		  background-color: #d2d2d2;
	  }
	  .item-inside {
		  width: 40rpx;
		  height: 40rpx;
	  }
	  .active{
	    color:#108b70;
	  } */
</style>
