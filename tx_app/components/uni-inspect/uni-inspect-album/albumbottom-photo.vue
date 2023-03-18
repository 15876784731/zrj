<template>
	<div class="iteam">
		<div class="iteam-header" >		
			<div ref="vediocamera" id="vediocamera" class="header-son" :style="{ height : iswindowHeight   + 'px',width : iswindowWidth + 'px'}">
				{{message}}
			 </div>
		
		
		</div>
		<div class="iteam-body">
			<div class="iteam-first" @click="showthis">
				<image class="innerfirst" src="../../../static/images/actionnow.png"></image>
			</div>
		</div>
	</div>
	
</template>

<script>
	function showthis(){
		alert(11111)
	}
	export default {
		data () {
		    return {
		      iswindowWidth: 0, //实时屏幕宽度
		      iswindowHeight:0,//实时屏幕高度
			  message:111,
		    }
		  },
		onReady() {
			this.shownow()
		},
		onShow() {
		  	this.getsize()
		  },
		methods: {
			shownow(){
				document.getElementById('vediocamera').innerHTML="哈哈"
				let cameraphoto = document.querySelector('#vediocamera')
				cameraphoto.style.color = "blue"
				alert(111)
				showthis()
			},
			getsize() {
				// let cameraphoto = document.querySelector('#vediocamera')
				// document.querySelector('#vediocamera').innerHTML="哈哈哈"
				let that = this
				uni.getSystemInfo({
			    success: function (res) {
					let h = res.screenHeight
					let w = res.screenWidth
					that.iswindowHeight = h - 150
					that.iswindowWidth = w - 4
					console.log(that.iswindowHeight)
			    }
			})
			}
			
		}
		
		
	}
</script>

<style>
	.iteam-body {
		position: fixed;
		bottom:0px;
		width: 100%;
		height:100px;
	}
	.iteam-header {
		position: fixed;
		bottom: 15%;
		border:1px solid red;
		margin-left: 1px;
	}
	.iteam-first {
		margin:10px 50rpx;
		text-align: center;
	}
	.innerfirst {
		height: 90px;
		width: 90px;
	}
</style>






<!-- 
<template>
	<view>
		<camera :device-position="device" :flash="flash" @error="error" :style="{ width: '100%',position: 'relative', height: getHeight + 'px' }">
			<cover-view class="topBox">
				<cover-view class="topItem text-bold text-xl">陕A·88888</cover-view>
				<cover-view class="topItem">{{nowTime2}}</cover-view>
				<cover-view class="topItem">{{nowTime}}</cover-view>
				<cover-view class="topItem">{{address}}</cover-view>
			</cover-view>

			<cover-image @click="xzBtn" class="xzImg" src="https://cdn.zhoukaiwen.com/xz.png"></cover-image>
			<cover-image @click="sgdBtn" class="sgdImg" :src="sgdUrl"></cover-image>

			<cover-view class="cameraBtn" @click="takePhoto">
				<cover-view class="cameraBtn2"></cover-view>
			</cover-view>


			<cover-view class="bottomBtn" v-if="imgList.length < 1">
				<cover-view @click="history" class="btn">历史</cover-view>
				<cover-view class="btn" @click="goBack">取消</cover-view>
			</cover-view>

			<cover-view class="bottomBg" v-if="imgList.length > 0">
				<cover-view>
					<cover-view @click="ViewImage(index)" class="imgBox" v-for="(item,index) in imgList" :key="index">
						<cover-image class="imgItem" :src="item.src" mode="aspectFill"></cover-image>
						<cover-view class="cu-tag" @tap.stop="DelImg" :data-index="index">
							<cover-image class="iconClose" src="https://cdn.zhoukaiwen.com/icon_close.png" mode="aspectFill"></cover-image>
						</cover-view>
					</cover-view>
				</cover-view>

				<cover-view @click="reportBtn" class="report">上报（{{imgList.length}}）</cover-view>
			</cover-view>



			<cover-view v-if="rreportShow" class="reportBox animation-slide-bottom">
				<cover-view class="text-lg margin-left-sm margin-bottom-sm" style="height:78rpx;line-height: 78rpx;">请选择照片备注内容</cover-view>

				<cover-view class="listBox">
					<cover-view class="item active">堵车</cover-view>
					<cover-view class="item">事故</cover-view>
					<cover-view class="item">停车休息</cover-view>
					<cover-view class="item">车辆故障</cover-view>
					<cover-view class="item">加油</cover-view>
					<cover-view class="item">恶劣天气</cover-view>
					<cover-view class="item">货物巡检</cover-view>
					<cover-view class="item">其他</cover-view>
				</cover-view>

				<cover-view @click="endBtn" class="repBtn">上报异常</cover-view>

			</cover-view>

		</camera>
		
		<view style="position: absolute;top: -999999px;">
			<view><canvas :style="{ width: w, height: h }" canvas-id="firstCanvas"></canvas></view>
		</view>

	</view>
</template>

<script>
	import QQMapWX from "@/common/qqmap-wx-jssdk";
	export default {
		data() {
			return {
				getHeight: '200',
				device: 'back', //前置或后置摄像头，值为front, back
				flash: 'off', //闪光灯，值为auto, on, off
				nowTime: '', //日期
				nowTime2: '', //时间
				address: '',	//当前地址信息
				sgdUrl: 'https://cdn.zhoukaiwen.com/sgd.png',
				imgList: [
					// {
					// 	src: "https://cdn.zhoukaiwen.com/angular.jpg"
					// }
				],
				imgListData: '',

				rreportShow: false, //选择照片备注内容弹窗
				
				src:'',
				w:'',
				h:''
			}
		},
		onLoad() {
			const that = this;
			var qqmapsdk;
			uni.getSystemInfo({
				success: function(res) {
					that.getHeight = res.windowHeight;
				}
			});

			uni.showModal({
				title: '在途异常上报',
				content: '车辆行驶途中，道路发生拥堵/事故等情况无法正常驶离时，请拍照上报企业，保留证据。',
				showCancel: false,
				confirmText: '知道了',
				confirmColor: '#3056F6',
				success: function(res) {
					if (res.confirm) {
						console.log('用户点击确定');
					}
				}
			});

			this.getTime();

			uni.getLocation({
				type: 'wgs84',
				success: function(res) {
					console.log('当前位置的经度：' + res.longitude);
					console.log('当前位置的纬度：' + res.latitude);

					qqmapsdk = new QQMapWX({
						key: "662BZ-2EAR6-M34S7-M4NPO-HFV3F-QEBTV" //自己申请的key
					});
					qqmapsdk.reverseGeocoder({
						location: {
							latitude: res.latitude,
							longitude: res.longitude
						},
						success(addressRes) {
							console.log(addressRes)
							that.address = addressRes.result.address;
						},
						fail(res) {}
					});
				}
			});

		},
		methods: {
			// 上报：最终上报
			endBtn(){
				this.rreportShow = false;
				this.imgList = [];
				
			},
			// 上报：选择类型
			reportBtn() {
				this.rreportShow = true;

				var str = this.imgListData;
				var newImgData = this.imgListData.substr(0, this.imgListData.length - 1);
				console.log(newImgData)
			},
			xzBtn() {
				if (this.device == 'front') {
					this.device = 'back'
				} else {
					this.device = 'front'
				}
			},
			sgdBtn() {
				if (this.flash == 'off') {
					this.flash = 'on'
					this.sgdUrl = 'https://cdn.zhoukaiwen.com/sgd_on.png'
				} else {
					this.flash = 'off'
					this.sgdUrl = 'https://cdn.zhoukaiwen.com/sgd.png'
				}
			},
			DelImg(e) {
				uni.showModal({
					// title: '异常照片',
					content: '确定要删除这张照片吗？',
					cancelText: '取消',
					confirmText: '确认',
					success: res => {
						if (res.confirm) {
							console.log(e)
							this.imgList.splice(e.currentTarget.dataset.index, 1)
							console.log(this.imgList)

							var arr = this.imgList;
							var str = '';
							for (var i = 0; i < arr.length; i++) {
								str += arr[i].src + ",";
							}
							this.imgListData = str;
							console.log(this.imgListData)
						}
					}
				})
			},
			// 查看照片
			ViewImage(index) {
				const imgList = [this.imgList[index].src];
				console.log(imgList)
				uni.previewImage({
					urls: imgList
				});
			},
			// 点击拍照
			takePhoto() {
				var that = this;
				if (this.imgList.length < 3) {
					const ctx = uni.createCameraContext();
					ctx.takePhoto({
						quality: 'high',
						success: (res) => {
							var tempImagePath = res.tempImagePath;
							// 获取图片信息
							uni.getImageInfo({
								src: res.tempImagePath,
								success: ress => {
									that.w = ress.width / 3 + 'px';
									that.h = ress.height / 3.01 + 'px';
									let ctx = uni.createCanvasContext('firstCanvas'); /** 创建画布 */
									//将图片src放到cancas内，宽高为图片大小
									ctx.drawImage(res.tempImagePath, 0, 0, ress.width / 3, ress.height / 3);
									ctx.setFontSize(12);
									ctx.setFillStyle('#FFFFFF');
									// ctx.rotate(30 * Math.PI / 180);
									let textToWidth = (ress.width / 3) * 0.03;
							
									let textToHeight = (ress.height / 3) * 0.9;
									ctx.fillText('陕A88888', textToWidth, textToHeight);
							
									ctx.setFontSize(10);
									let textToHeight2 = (ress.height / 3) * 0.94;
									ctx.fillText(that.nowTime + ' ' + that.nowTime2, textToWidth, textToHeight2);
							
									let textToHeight3 = (ress.height / 3) * 0.98;
									ctx.fillText(that.address, textToWidth, textToHeight3);
							
									ctx.draw(false, () => {
										setTimeout(() => {
											uni.canvasToTempFilePath({
												canvasId: 'firstCanvas',
												success: res1 => {
													tempImagePath = res1.tempFilePath;
													console.log('----------',tempImagePath);
													this.imgList.push({
														src: tempImagePath
													})
													console.log(this.imgList);
													
													var arr = this.imgList
													var str = '';
													for (var i = 0; i < arr.length; i++) {
														str += arr[i].src + ",";
													}
													this.imgListData = str;
													console.log(this.imgListData)
												}
											});
										}, 1000);
									});
								}
							});
						}
					});
				} else {
					uni.showToast({
						title: '最大上传3张照片',
						duration: 2000,
						icon: 'none'
					});
				}

			},
			error(e) {
				console.log(e.detail);
			},
			getTime: function() {
				var date = new Date(),
					year = date.getFullYear(),
					month = date.getMonth() + 1,
					day = date.getDate(),
					hour = date.getHours() < 10 ? "0" + date.getHours() : date.getHours(),
					minute = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes(),
					second = date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();
				month >= 1 && month <= 9 ? (month = "0" + month) : "";
				day >= 0 && day <= 9 ? (day = "0" + day) : "";
				var timer = year + '年' + month + '月' + day + '日';
				var timer2 = hour + ':' + minute + ':' + second;
				this.nowTime = timer;
				this.nowTime2 = timer2;

				console.log(this.nowTime);
				console.log(this.nowTime2);
			},
			goBack() {
				uni.navigateBack({
					delta: 1
				});
			},
			history() {
				uni.navigateTo({
					url: 'timeline'
				})
			}
		}
	}
</script>

<style lang="scss">
	.topBox {
		width: 750rpx;
		box-sizing: border-box;
		padding: 30rpx;
		color: #EEEEEE;
		font-size: 34rpx;

		.topItem {
			width: 100%;
			white-space: pre-wrap;
			margin-bottom: 15rpx;
		}
	}

	.cameraBtn {
		width: 120rpx;
		height: 120rpx;
		line-height: 120rpx;
		border: 6rpx #FFFFFF solid;
		border-radius: 50%;
		padding: 8rpx;
		position: absolute;
		left: calc(50% - 60rpx);
		bottom: 210rpx;
	}

	.cameraBtn2 {
		width: 100%;
		height: 100%;
		border-radius: 50%;
		background-color: #FFFFFF;
		text-align: center;
		color: #007AFF;
	}

	.xzImg {
		width: 52rpx;
		height: auto;
		position: absolute;
		right: 44rpx;
		bottom: 580rpx;
	}

	.sgdImg {
		width: 40rpx;
		height: auto;
		position: absolute;
		right: 50rpx;
		bottom: 450rpx;
	}

	.bottomBtn {
		width: 100%;
		height: 150rpx;
		padding-bottom: 15rpx;
		position: absolute;
		bottom: 0;
		left: 0;
		text-align: center;
		display: flex;
		justify-content: space-between;

		.btn {
			width: 30%;
			height: 150rpx;
			font-size: 34rpx;
			color: #FFFFFF;
			line-height: 150rpx;
		}
	}

	.bottomBg {
		width: 100%;
		height: 170rpx;
		box-sizing: border-box;
		padding: 20rpx 30rpx 40rpx;
		position: absolute;
		bottom: 0;
		left: 0;
		background-color: rgba(0, 0, 0, .8);
		display: flex;
		justify-content: space-between;
		align-items: center;

		.imgBox {
			width: 110rpx;
			height: 110rpx;
			float: left;
			margin-right: 40rpx;
			position: relative;

			.cu-tag {
				position: absolute;
				right: 0;
				top: 0;
				border-bottom-left-radius: 2px;
				padding: 3px 5px;
				height: auto;
				background-color: rgba(0, 0, 0, 0.5);
				font-size: 10px;
				vertical-align: middle;
				font-family: Helvetica Neue, Helvetica, sans-serif;
				white-space: nowrap;
				color: #ffffff;
			}
		}

		.imgItem {
			width: 110rpx;
			height: 110rpx;
		}
	}

	.report {
		height: 68rpx;
		line-height: 68rpx;
		text-align: center;
		color: #FFFFFF;
		box-sizing: border-box;
		padding: 0rpx 20rpx;
		border-radius: 10rpx;
		background-color: #2157FF;
	}

	.iconClose {
		width: 20rpx;
		height: 20rpx;
	}

	.reportBox {
		width: 750rpx;
		height: auto;
		box-sizing: border-box;
		padding: 10rpx 20rpx;
		background-color: #FFFFFF;
		position: absolute;
		bottom: 0;
	}

	.listBox {
		display: flex;
		flex-wrap: wrap;
		justify-content: space-around;

	}

	.item {
		width: 160rpx;
		height: 68rpx;
		line-height: 68rpx;
		text-align: center;
		background: #F7F7F9;
		margin-bottom: 40rpx;
		color: #888888;

	}

	.active {
		background-color: #F1F7FF;
		color: #025ADD;
	}

	.repBtn {
		width: 680rpx;
		height: 78rpx;
		line-height: 78rpx;
		background-color: #025ADD;
		color: #FFFFFF;
		font-size: 33rpx;
		text-align: center;
		border-radius: 10rpx;
		margin: 5rpx auto 20rpx;
	}
</style> -->








<!-- <template>
  <div class="camera_outer">
   <video id="videoCamera" :width="videoWidth" :height="videoHeight" autoplay></video>
   <canvas style="display:none;" id="canvasCamera" :width="videoWidth" :height="videoHeight" ></canvas>
  
   <div v-if="imgSrc" class="img_bg_camera">
    <img :src="imgSrc" alt="" class="tx_img">
   </div>
   <button @click="getCompetence()">打开摄像头</button>
   <button @click="stopNavigator()">关闭摄像头</button>
　　　　　<button @click="setImage()">拍照</button>
  
  
   
  </div>
 </template>
   <script type="text/javascript" src="/static/echarts/echarts.min.js"></script>
 <script>
 export default {
  data () {
   return {
    videoWidth: 3000,
    videoHeight: 300,
    imgSrc: '',
    thisCancas: null,
    thisContext: null,
    thisVideo: null,
   }
  },
  mounted() {
  	this.thisCancas = document.getElementById('canvasCamera')
	// this.thisCances = echarts.init(document.getElemetById('canvasCamera'));
    
    this.thisVideo = document.getElementById('videoCamera')
	// this.thisVideo = echarts.init(document.getElementById('videoCamera'))
  },
  methods: {
// 调用权限（打开摄像头功能）
   getCompetence () {
    var _this = this
    this.thisContext = this.thisCancas.getActivity('2d')
	
    // 旧版本浏览器可能根本不支持mediaDevices，我们首先设置一个空对象
    if (navigator.mediaDevices === undefined) {
     navigator.mediaDevices = {}
    }
    // 一些浏览器实现了部分mediaDevices，我们不能只分配一个对象
    // 使用getUserMedia，因为它会覆盖现有的属性。
    // 这里，如果缺少getUserMedia属性，就添加它。
    if (navigator.mediaDevices.getUserMedia === undefined) {
     navigator.mediaDevices.getUserMedia = function (constraints) {
      // 首先获取现存的getUserMedia(如果存在)
      var getUserMedia = navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.getUserMedia
      // 有些浏览器不支持，会返回错误信息
      // 保持接口一致
      if (!getUserMedia) {
       return Promise.reject(new Error('getUserMedia is not implemented in this browser'))
      }
      // 否则，使用Promise将调用包装到旧的navigator.getUserMedia
      return new Promise(function (resolve, reject) {
       getUserMedia.call(navigator, constraints, resolve, reject)
      })
     }
    }
    var constraints = { audio: false, video: { width: this.videoWidth, height: this.videoHeight, transform: 'scaleX(-1)' } }
    navigator.mediaDevices.getUserMedia(constraints).then(function (stream) {
     // 旧的浏览器可能没有srcObject
     if ('srcObject' in _this.thisVideo) {
      _this.thisVideo.srcObject = stream
     } else {
      // 避免在新的浏览器中使用它，因为它正在被弃用。
      _this.thisVideo.src = window.URL.createObjectURL(stream)
     }
     _this.thisVideo.onloadedmetadata = function (e) {
      _this.thisVideo.play()
     }
    }).catch(err => {
     console.log(err)
    })
   },
// 绘制图片（拍照功能）
  
   setImage () {
    var _this = this
    // 点击，canvas画图
    _this.thisContext.drawImage(_this.thisVideo, 0, 0, _this.videoWidth, _this.videoHeight)
    // 获取图片base64链接
    var image = this.thisCancas.toDataURL('image/jpg')
    _this.imgSrc = image
    this.$emit('refreshDataList', this.imgSrc)
   },
// base64转文件
  
   dataURLtoFile (dataurl, filename) {
    var arr = dataurl.split(',')
    var mime = arr[0].match(/:(.*?);/)[1]
    var bstr = atob(arr[1])
    var n = bstr.length
    var u8arr = new Uint8Array(n)
    while (n--) {
     u8arr[n] = bstr.charCodeAt(n)
    }
    return new File([u8arr], filename, { type: mime })
   },
// 关闭摄像头
  
   stopNavigator () {
    this.thisVideo.srcObject.getTracks()[0].stop()
   }
  },
  
 }
   
 </script>
 <style>
 .camera_outer{
  position: relative;
  overflow: hidden;
  /* background: url("../../assets/img/user_0608_04.jpg") no-repeat center; */
  background-size: 100%;
  video,canvas,.tx_img{
   -moz-transform:scaleX(-1);
   -webkit-transform:scaleX(-1);
   -o-transform:scaleX(-1);
   transform:scaleX(-1);
  }
  .btn_camera{
   position: absolute;
   bottom: 4px;
   left: 0;
   right: 0;
   height: 50px;
   background-color: rgba(0,0,0,0.3);
   line-height: 50px;
   text-align: center;
   color: #ffffff;
  }
  .bg_r_img{
   position: absolute;
   bottom: 0;
   left: 0;
   right: 0;
   top: 0;
  }
  .img_bg_camera{
   position: absolute;
   bottom: 0;
   left: 0;
   right: 0;
   top: 0;
   img{
    width: 100%;
    height: 100%;
   }
   .img_btn_camera{
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 50px;
    line-height: 50px;
    text-align: center;
    background-color: rgba(0,0,0,0.3);
    color: #ffffff;
    .loding_img{
     width: 50px;
     height: 50px;
    }
   }
  }
 }
 </style> -->