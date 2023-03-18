<template>
	<view >
		<!-- <view class="puzzle" :style="{width:phoneWidth-1+'px'}">
			<view class="main" ref="main" :style="{width:phoneWidth-1+'px',height:imageboxHeight+'px'}">
				<view class="firstImgBox" :style="{width: firstImgW+'px', height: firstImgH+'px'}"></view>
				<view class="ortherImg"  :style="{width: firstImgW+'px'}">
					<view class="secondImgBox" :style="{width: otherImgW+'px', height: otherImgH+'px'}"></view>
					<view class="thirdImgBox" :style="{width: otherImgW+'px', height: otherImgH+'px'}"></view>
					<view v-if="photoes.length==4" class="forthdImgBox" :style="{width: otherImgW+'px', height: otherImgH+'px'}"></view>
				</view>
				<movable-view  v-if="false" ref='movedImg' direction="all" scale="true" @change="moveImage" v-for="(item,index) in photoes" :key="index" style="width: 300px;height: 300px;">
					<image src="item.url" mode="aspectFit" ></image>
				</movable-view>
			</view>
		</view> -->
		<view class="" v-show="true">
			<canvas :style="{width:CanvasWidth +'px',height:CanvasHeight+'px'}" canvas-id="myCanvas" id="myCanvas" ></canvas>
			<image style="width: 300px; height: 300px; z-index: 10;" :src="testImage" mode=""></image>
			<!-- <view class="hiddenAll" :style="{width: phoneWidth+'px', height: phoneHeight+'px'}" >拼图中...</view> -->
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				photoList: [],
				testImage: '',
				CanvasWidth: '',
				CanvasHeight: '',
				phoneWidth: '',
				phoneHeight: '',
				// firstImgW: '',
				// firstImgH: '',
				// otherImgW: '',
				// otherImgH: '',
				
				// imageboxHeight: ''
			}
		},
		onLoad(option) {
			uni.showLoading({
				title: '拼图中...',
				mask: true
			})
			// console.log(option.photoList);
			const that = this;
			this.photoList = JSON.parse(option.photoList);
			console.log(this.photoList);
			// const imgratio = this.photoList[0].imgHeight / this.photoList[0].imgWidth; // 由自拟定 相机拍出来的照片比例是固定的
			uni.getSystemInfo({
				success: function (res) {
					// console.log(res)
					that.statusBarHeight = res.statusBarHeight;
					that.phoneWidth = res.screenWidth ;
					that.phoneHeight = res.screenHeight;
					console.log(that.phoneHeight);
					that.pixelRatio = res.pixelRatio;
					that.CanvasWidth = that.phoneWidth*that.pixelRatio; // 按像素比放大
					that.CanvasHeight = that.phoneHeight*that.pixelRatio;
					if (that.photoList.length != 3 && that.photoList.length != 4 ) {
						that.CanvasHeight = that.photoList.length * that.CanvasHeight;
					}
					// that.imageboxHeight = that.phoneHeight - that.statusBarHeight- 48;
					// that.firstImgW = res.screenWidth - 70;
					// that.firstImgH = that.firstImgW * imgratio;
					// if (that.photoes.length==3) { // 如果有3张图, 定义下面两张小图的宽高
					// 	that.otherImgW = that.firstImgW * (1/2);
					// 	console.log(that.otherImgW,that.firstImgW);
					// 	that.otherImgH = that.otherImgW;
					// } else {
					// 	that.otherImgW = that.firstImgW * (1/3);
					// 	that.otherImgH = that.otherImgW;
					// }
					// console.log(that.firstImgH);
				}
			}) ;
			
		},
		mounted() {
			this.initCanvas()
		},
		// onNavigationBarButtonTap() {
		// 	console.log('完成')
		// 	const ctx = uni.createCanvasContext('myCanvas',this);
		// 	const photoes = that.photoes;
		// 	imgWith =  photoes[0].imgWidth;
		// 	imgHeight =  photoes[0].imgHeight;
		// 	if (photoes.length == 3) {
		// 		// ctx.drawImage(that.photoes[i].url,)
		// 	} else if (photoes.length == 4) {
				
		// 	} else {
		// 		// 选中两张或选择大于4张图片进行拼图, 拼长图
		// 		for (let i = 0; i < photoes.length; i++) {
		// 			// ctx.drawImage(photoes[i].url,0,photoes[])
		// 		}
		// 	}
		// },
		methods: {
			initCanvas () {
				const that = this;
				// const photoList = JSON.stringify(that.photoList)
				console.log('拼图');
				let ratio = 1620/1080;
				let img0w = that.CanvasWidth 
				let img0h = img0w * ratio ; // 按图片比设置高度
				const ctx = uni.createCanvasContext('myCanvas',this);
				// const url = '/storage/emulated/0/Android/data/io.dcloud.HBuilder/apps/HBuilder/doc/snapshot/'
				if (this.photoList.length == 3) { // 三张拼图
					let img0 = that.photoList[0];
					let img1 = that.photoList[1];
					let img2 = that.photoList[2];
					let imagh = (img0w/2); // 小图片的宽高为大图片宽的一半
					ctx.drawImage(img0.url,0,0,img0w,img0h);
					ctx.drawImage(img1.url,0,img0h,imagh,imagh)
					ctx.drawImage(img2.url,imagh,img0h,imagh,imagh)
					ctx.draw();
					console.log(ctx);
					setTimeout(()=> {
						uni.canvasToTempFilePath({
						  x: 0,
						  y: 0,
						  width: img0w,
						  height: img0h+imagh,
						  destWidth: 1080,
						  destHeight: 1620,
						  quality:1,
						  canvasId: 'myCanvas',
						  fileType:'jpg',
						  success: function(res) {
							// 在H5平台下，tempFilePath 为 base64
							// console.log(res.tempFilePath)
							that.testImage = res.tempFilePath;
							that.savePic();
						  } 
						})
					},500);
				} else if (that.photoList.length == 4) { // 四张拼图
					let img0 = that.photoList[0];
					let img1 = that.photoList[1];
					let img2 = that.photoList[2];
					let img3 = that.photoList[3];
					let imagh = (img0w/3); // 小图片的宽高为大图片宽的一半
					ctx.drawImage(img0.url,0,0,img0w,img0h);
					ctx.drawImage(img1.url,0,img0h,imagh,imagh)
					ctx.drawImage(img2.url,imagh,img0h,imagh,imagh)
					ctx.drawImage(img3.url,2*imagh,img0h,imagh,imagh)
					ctx.draw();
					console.log(ctx);
					setTimeout(()=> {
						uni.canvasToTempFilePath({
						  x: 0,
						  y: 0,
						  width: img0w,
						  height: img0h+imagh,
						  destWidth: 1080,
						  destHeight: 1620,
						  quality:1,
						  canvasId: 'myCanvas',
						  fileType:'jpg',
						  success: function(res) {
							// 在H5平台下，tempFilePath 为 base64
							// console.log(res.tempFilePath)
							that.testImage = res.tempFilePath;
							that.savePic();
						  } 
						})
					},500);
				} else {
					let imgH =  this.photoList.length * img0h; // 总高度
					console.log(imgH);
					let outImgW =  img0w / that.pixelRatio; // 原宽度输出
					let outImgH = imgH / that.pixelRatio; // 总高度原比例输出
					this.photoList.forEach( (img,index) => {
						let y = img0h*index;
						ctx.drawImage(img.url,0,y,img0w,img0h);
						console.log(y);
					} );
					ctx.draw();
					setTimeout(()=> {
						uni.canvasToTempFilePath( {
							x: 0,
							y: 0,
							width: img0w,
							height: imgH,
							destWidth: outImgW,
							destHeight: outImgH,
							quality:1,
							canvasId: 'myCanvas',
							fileType:'jpg',
							success: function(res) {
								// 在H5平台下，tempFilePath 为 base64
								// console.log(res.tempFilePath)
								that.testImage = res.tempFilePath;
								that.savePic();
							} 
						} )
					}, 500 );
				}
			},
			// 保存拼图
			savePic () {
				const url = '/storage/emulated/0/Android/data/io.dcloud.HBuilder/apps/HBuilder/doc/snapshot/';
				let picName = '';
				// plus.io.resolveLocalFileSystemURL(url,(entry) => {console.log(entry.fullPath);})
				plus.io.resolveLocalFileSystemURL(this.testImage,(entry) => { // 拿到图片的操作对象
					console.log(entry)
					entry.file( (file) => {
						console.log(file);
						picName = 'snapshot_' + file.name;
						console.log(picName);
						// let fileSlice = file.slice();
						// console.log(fileSlice);
						plus.io.resolveLocalFileSystemURL(url,(dstentry) => { // 将图片移动的 url 目录
							console.log(entry.fullPath);
							entry.moveTo(dstentry,picName,(newfile) => {
								console.log(newfile,'移动文件成功');
								console.log(newfile.fullPath);
								let pages = getCurrentPages(); 
								let prevPage = pages[pages.length - 2].$vm.album; //拿到上一个页面的相册组件的实例
								prevPage.getPhotoArr();
								
								// uni.redirectTo({
								// 	url: './PhotoAlbum',
								// 	success() {
								// 		uni.hideLoading();
								// 		uni.showToast({
								// 			title: '拼图成功!',
								// 			icon: 'none',
								// 			duration:1000
								// 		})
								// 	}
								// })
								uni.navigateBack({
								    delta: 1,
									animationType: 'pop-out',
									animationDuration: 200,
									complete () {
										uni.hideLoading();
										uni.showToast({
											title: '拼图成功!',
											icon: 'none',
											duration:1000
										})
									}
								});
							},(err)=>{console.log(err)})
						})
						
					})
					
					// entry.getFile(that.testImage, {create: true, exclusive: true}, ( fileEntry ) => {
					// 	console.log(fileEntry);
					// 	console.log(fileEntry.isFile);
					// 	console.log(fileEntry.name);
					// 	console.log(fileEntry.fullPath);
					// 	var fileUrl = fileEntry.toLocalURL();
					// 	console.log(fileUrl);
					// 	fileEntry.file( (file) => {
					// 		console.log(file);
					// 	})
					// })
				})
			}
		}
	}
</script>

<style>
	/* .hiddenAll {
		position: fixed;
		top: 0;
		left: 0;
		background-color: #c5c5c5;
		z-index: 999;
		 padding: 0px;
		text-align: center;
		display: table-cell;
		vertical-align:middle;
	} */
	/* .puzzle{
		display: flex;
		flex-direction: column;
		align-items: center;
		border: 1px solid #F0AD4E;
		justify-content: center;
	}
	.main {
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
		border: 1px solid #000000;
	}
	.ortherImg {
		display: flex;
		flex-direction: row;
		
	}
	.ortherImg >view {
		border: 1px solid #000000;
	}
	#myCanvas {
		position: absolute;
		top: 0px;
	}
	.firstImgBox {
		border: 1px solid red;
	}
	movable-view {
		border: 1px solid #000000;
	} */
</style>
