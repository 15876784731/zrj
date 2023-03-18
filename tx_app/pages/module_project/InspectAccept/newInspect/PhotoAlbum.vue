<template>
	<view>
		
		<view v-show="state == '相册'" :style="{ height: mainHeight+'px',width: phoneWidth+'px'}" >
			<uni-nav-bar fixed="true" statusBar="true" left-icon="back" @clickLeft="back" :title="navTopTitle" right-text="拼图" @clickRight="navToPuzzle"></uni-nav-bar>
			<image v-show="photoList.length == 0"  :style="{ height: '60%',width: '100%'}" src="../../../../static/images/nodata.png" mode=""><view v-show="photoList.length == 0" class="tx-center" style="width: 100%;">暂无图片</view></image>
			<scroll-view v-show="photoList.length != 0" scroll-y="true" scroll-x="false" :style="{ height: mainHeight+'px'}"  @scrolltolower="goBootom()">
				<checkbox-group name="imgeGrop" class="list-box"  @change.prevent="checkboxChange" v-if="imageShow">
					<view 
						v-for="(item,index) in photoList" 
						:key="index" 
						:style="{width: imageWidth+'px', height:imageWidth+'px',}"
						:data-index="index"
						@click="previewPhoto"
						ref="imagev"
						id="imagev"
					>
						<view class="image-box">
							<!-- @click.native.stop="" 用来阻止原生点击事件冒泡 -->
							<label class="radio"><checkbox  @click.native.stop="" :value="item.index" :checked="item.selection" /></label> 
							<image ref="img" :src="item.url" mode="aspectFill" lazy-load="true"></image>
						</view>
						<!-- <view>第 {{item.index+1}} 张图片</view> -->
					</view >
				</checkbox-group>
					<!-- 下啦加载 -->
					<view class="load" ref="load" v-show="page>1">{{loadTxt}}</view> 
			</scroll-view>
			<uni-tab
				class="fixed-bottom-over-nav"
				v-model="activeKey" 
				:line="{height: '3px', width: '3px'}"
				:navData="scrollData"
				@change="tabChange"
				@tabClick="tabClick"
			></uni-tab>
			<!-- 底部导航栏 -->
			<nav-buttom v-show="state == '相册'" :buttonArr="buttonArr"></nav-buttom>
			<!-- 贴边悬浮按钮 -->
			<!-- <uni-gft :imgLists="imgGft" :mainImg="mianImg" @menuClick="floatTouchClick"></uni-gft> -->
			<!-- <photoalbum-bottom></photoalbum-bottom> -->
		</view>
		<view class="" v-if="state == '预览'">
			<uni-nav-bar fixed="true" statusBar="true" left-icon="back" title="预览" @clickLeft="backAlbum"></uni-nav-bar>
			<zetank-swiper :imageList="photoList" :autoplay="false" :height="mainHeight" type="default-nav" :current="currentPicSon"></zetank-swiper>
			<nav-buttom :buttonArr="preButtonArr"></nav-buttom>
		</view>
		
		<!-- <view v-if="picShow" class="picShow" id="picShow" >
			<photo-preview :currentPic="currentPic" @closePreview="closePreview"></photo-preview>
		</view> -->
	</view>
</template>
<!-- 相册组件说明
	1、navTopTitle 变量为相册顶部导航栏标题
	2、navButtomArr 变量可以设置相册底部导航栏按钮
	3、使用时须在所引用的组件的 data 中定义 album 变量， 同时在引用标签上定义 ref="album", 并通过$ref 将本组件的实力赋给 album 变量， 就可以调用本组件函数
		使用例子： "pages/module_project/InspectAccept/newInspect/processInspect"
	4、选中图片是触发 checkboxChange 可在父组件进行监听， 拿到选择的图片数据
			
 -->
<script>
	// import uniGft from '@/components/gmy-float-touch/gmy-float-touch.vue'
	// import photoShow from './PhotoShow'
	// import photoPreview from './photoPreview.vue'
	import uniNavBar from "@/components/uni-app/uni-nav-bar/uni-nav-bar"
	import uniTab from "@/components/Mark-Tab/Tab.vue"
	import navButtom from "./nav-bottom.vue"
	
	import { mapMutations } from 'vuex';
	export default {
		props: {
			navTopTitle: {
				type: String,
				default: ''
			},
			navButtomArr: {
				type: Object,
			}
		},
		components: {
			// uniGft
			uniNavBar,
			navButtom,
			uniTab,
			// photoPreview
		},
		data() {
			return {
				state: '相册',
				photoList:[],
				currentPic: 0,
				rows:5,
				testImage: '',
				AlbumPicArr: [],
				allPicNum:0, // 图片总数
				allPageNum:0, // 总共页数
				perPage: 32, // 每页32张图
				page:1,
				isGet:true,
				loadTxt:"",
				phoneWidth: null,
				phoneHeight: null,
				imageWidth:null,
				statusBarHeight: null,
				pixelRatio: '', // 像素密度
				navTopHegiht: null,
				mainHeight: null,
				directionStr: '垂直',
				horizontal: 'left',
				vertical: 'bottom',
				direction: 'horizontal',
				pattern: {
					color: '#7A7E83',
					backgroundColor: '#fff',
					selectedColor: '#007AFF',
					buttonColor: '#007AFF'
				},
				content: [{
						iconPath: '/static/images/connectPic.png',
						selectedIconPath: '/static/images/connectPic.png',
						text: '拼图',
						active: false
					},
					{
						iconPath: '/static/images/shanchu.png',
						selectedIconPath: '/static/images/shanchued.png',
						text: '删除',
						active: false
					}
				],
				// picShow: false ,// 显示图片预览
				imgGft: ["/static/images/shanchued.png","/static/images/camera.png","/static/images/puzzle.png"],
				mianImg: "/static/images/add.png",
				imagesrc: [],
				checkedPicIndex: [],	
				imageShow: false,
				activeKey: 0,
				scrollData: [
					{
						label:'第一页',
						slot:'slot-1',
						url: 'pages/module_project/InspectAccept/newInspect/puzzleImg'
					},
					{
						label:'第2页',
						slot:'slot-2',
						url: 'pages/module_project/InspectAccept/newInspect/puzzleImg'
					},
					{
						label:'第3页',
						slot:'slot-3',
						url: 'pages/module_project/InspectAccept/newInspect/puzzleImg'
					},
					{
						label:'第4页',
						slot:'slot-4',
						url: 'pages/module_project/InspectAccept/newInspect/puzzleImg'
					},
					{
						label:'第5页',
						slot:'slot-5',
						url: 'pages/module_project/InspectAccept/newInspect/puzzleImg'
					}
				],
				buttonArr: [
					{
						title: '',
						icon: '../../../../static/images/shanchued.png',
						size: 'middle',
						mothod: this.deleteMoreImg
					},
					{
						title: '  ',
						icon: '../../../../static/images/actionnow1.png',
						size: 'big',
						mothod: this.openCamera
					},
				],
				preButtonArr: [
					{
						title: '',
						icon: '../../../../static/images/shanchued.png',
						size: 'middle',
						mothod: this.deletePhoto
					},
				]
			}
		},
		created() {
			let that = this
			const arr = [...this.buttonArr,...this.navButtomArr.albumArr];
			this.buttonArr = arr;
			const arr1 = [...this.preButtonArr,...this.navButtomArr.preArr];
			this.preButtonArr = arr1;
			// 获取手机信息
			uni.getSystemInfo({
				success(res) {
					const pixelRatio = res.pixelRatio;
					that.phoneWidth = res.screenWidth ;
					that.phoneHeight = res.screenHeight ;
					// console.log(that.phoneWidth);
					// console.log(that.phoneHeight);
					that.statusBarHeight = res.statusBarHeight ;
					that.imageWidth = (that.phoneWidth/4 -2);
					that.mainHeight = res.safeArea.height -  99; //  除去状态栏和导航栏的高度
					that.pixelRatio = res.pixelRatio;
					// console.log(res);
					// console.log(that.mainHeight);
				}
			})
			this.getPhotoArr();
		},
		onReachBottom(){
			// this.getPhoto();
		},
		onNavigationBarButtonTap(e) {
			uni.navigateTo({
				url:`./CallBack/IssueRectification`
			})
			// this.showDrawer('fab')
		},
		mounted() {
			
			// console.log(that.$refs.load);
			
		},
		computed: {
			currentPicSon () { // 当前预览的图片下标
				return this.currentPic;
			}
		},
		methods: {
			
			...mapMutations(['getPhotos']),
			
			goBootom () {
				console.log('到底了');
				this.getPhoto();
			},
			/* 获取照片数组 */
			getPhotoArr(){
				const that = this;
				that.photoList = [];
				that.checkedPicIndex = [];
				that.page = 1;
				// #ifdef APP-PLUS
				const url = '/storage/emulated/0/Android/data/io.dcloud.HBuilder/apps/HBuilder/doc/snapshot/'
				plus.io.resolveLocalFileSystemURL( url, (entry )=>{
					const directoryReader = entry.createReader();
					directoryReader.readEntries( (arr)=>{
						that.AlbumPicArr = arr;
						// console.log(that.AlbumPicArr.length);
						that.allPicNum = that.AlbumPicArr.length;
						that.allPageNum = Math.ceil(that.allPicNum / that.perPage); // 向上取整,有小数就整数部分加1
						that.getPhoto();
					}, (error)=>{
						
					});
				}, (error)=>{
					console.log(error);
				} );
				// #endif
			},
			getPhoto() {
				const that = this;
				const perPage = 32; // 每页32张图片
				let currentIndex = perPage * (this.page-1); //  当前页的开始下标
				// console.log(this.page,this.allPageNum);
				if (this.page == this.allPageNum) { // 最后一页时,加载剩下的图片
					this.loadTxt = '到底了!';
					let lastPagePicNum = this.allPicNum % perPage;
					// console.log(lastPagePicNum);
					for (let i = currentIndex; i < this.allPicNum; i++) {
						// console.log(i);
						that.AlbumPicArr[i].file((file)=>{ // 拿到相机拍摄路径下的每一个图片file对象
							uni.getImageInfo({ // 获取图片的信息
								src:file.fullPath,
								success(res) {
									// console.log(res);
									that.$set(that.photoList,i,{
										index: i,
										url: res.path,
										selection:false,
										active: true,
										imgWidth: res.width,
										imgHeight: res.height,
										imgRatio: res.width/res.height,
										phoneWidth:that.phoneWidth,
										phoneHeight: that.phoneHeight,
										file:file
									})
									if (i == that.allPicNum-1) {
										// console.log(that.photoList);
										that.imageShow = true;
										that.getPhotos(that.photoList);
									}
								}
							})
						})
					}
					return
				} else {
					this.loadTxt = '上拉加载!';
					// console.log(currentIndex,this.page);
					for (let i = currentIndex; i < perPage*this.page; i++) {
						that.AlbumPicArr[i].file((file)=>{ // 拿到相机拍摄路径下的每一个图片file对象
							// console.log(i);
							uni.getImageInfo({ // 获取图片的信息
								src:file.fullPath,
								success(res) {
									// console.log(res);
									that.$set(that.photoList,i,{
										index: i,
										url: res.path,
										selection:false,
										active: true,
										imgWidth: res.width,
										imgHeight: res.height,
										phoneWidth:that.phoneWidth,
										phoneHeight: that.phoneHeight,
										file:file
									})
									if (i == perPage*that.page-1) {
										console.log(that.photoList);
										that.imageShow = true;
										that.page++;
										that.getPhotos(that.photoList);
									}
								}
							})
						})
					}						
				}
			},
			/* 显示照片 */
			showImages(){
				let index=(this.page-1)*this.rows;
				let show=()=>{
					if(index<this.photoList.length){
						this.$set(this.photoList[index],"active",true);
						index++;
					}else{
						clearInterval(interval);
						this.loadTxt="上拉加载更多";
						this.page++;
						this.isGet=true;
					}
				}
				
				let interval=setInterval(()=>{
					show();
				},5);
			},
			/* 预览照片 */
			previewPhoto(e){
				// console.log(e);
				const that = this;
				let index=e.currentTarget.dataset.index;
				this.currentPic = index; 
				const photo = this.photoList[index];
				this.$emit('previewPhoto',photo);
				console.log(this.currentPic);
				this.state = '预览';
			},
			deletePhoto () {
				// 调用相册组织的删除照片的方法
				this.checkedPicIndex = [this.currentPic];
				this.deleteMoreImg();
				setTimeout(()=>{
					this.checkedPicIndex = [];
					this.currentPic--;
					console.log(this.currentPic);
					if (this.currentPic < 0) {
						this.state = '相册',
						uni.showToast({
							title: '已无图片'
						})
					}
				},500)
			},
			deleteMoreImg () {
				const that = this;
				if (this.checkedPicIndex.length != 0) { // 点击了删除按钮
					uni.showLoading({
						title: '加载中...'
					})
					for (let i=0; i<this.checkedPicIndex.length; i++) {
						const index = this.checkedPicIndex[i];
						console.log(this.photoList);
						console.log(index);
						const picSrc = this.photoList[index].file.fullPath; // 拿到选中图片的路径
						uni.removeSavedFile({
						  filePath: picSrc,
						  success: function (res) {
						    // console.log(res);
							if (i == that.checkedPicIndex.length-1) {
								uni.showToast({
									title: '删除成功!',
									icon: 'none',
									duration: 1000
								})
								uni.hideLoading();
								that.getPhotoArr();
							}
						  },
						  fail: (err)=>{console.log(err);}
						});
						
					}
				} else {
					uni.showToast({
						title: '请选择要删除的图片',
						icon: 'none',
						duration: 1000
					})
				}
			},
			// 多选方法
			checkboxChange (e) {
				// console.log(e.detail.value);
				// console.log(this.photoList);
				const indexs = e.detail.value;
				let photoes = [];
				for (let i = 0; i < indexs.length; i++) {
					photoes.push(this.photoList[i]);
				}
				console.log(photoes);
				this.$emit('checkboxChange',{
					indexs: indexs,
					photoes:photoes
				})
				this.checkedPicIndex = e.detail.value;
			},
			// 悬浮贴边按钮
			floatTouchClick (index) {
				const that = this;
				console.log(index);
				if (index == 1 && this.checkedPicIndex.length) { // 点击了删除按钮
					for (let i=0; i<this.checkedPicIndex.length; i++) {
						const index = this.checkedPicIndex[i];
						const picSrc = this.photoList[index].file.fullPath; // 拿到选中图片的路径
						uni.removeSavedFile({
						  filePath: picSrc,
						  success: function (res) {
						    // console.log(res);
							if (i == that.checkedPicIndex.length-1) {
								that.getPhoto();
							}
						  },
						  fail: (err)=>{console.log(err);}
						});
						
					}
				} else if(index == 2) {
					uni.navigateTo({
						url:'../../../../components/uni-inspect/uni-inspect-album/takephoto?dotype=face',
						success: function() { 
							console.log('success,dfs');
						}
					})
				} else { 
					
				}
			},
			openCamera () {
				uni.navigateTo({
					url:'../../../../components/uni-inspect/uni-inspect-album/takephoto?dotype=face',
					success: function() { 
						console.log('打开相机');
					}
				})
			},
			
			navToPuzzle () {
				if (this.checkedPicIndex.length > 1) {
					let photoList = [];
					for (let i = 0; i < this.checkedPicIndex.length; i++) {
						let index = this.checkedPicIndex[i];
						photoList.push(this.photoList[index]);
					}
					console.log(photoList);
					photoList = JSON.stringify(photoList)
					uni.navigateTo({
						url:'./puzzleImg?photoList=' + photoList,
						success: function() { 
							console.log('success to puzzle');
						}
					})
					
				} else {
					uni.showToast({
						title: '请选择不少于2张图片',
						icon: 'none',
						duration:1000
					})
				}
			},
			
			//保存图片到相册，方便核查
			savePhoto(path){
				let number = this.imagesrc.length
				this.$set(this.imagesrc,number,path)
				console.log(this.imagesrc);
				console.log(path);
				//保存到相册
				uni.saveImageToPhotosAlbum({
					filePath: path,
					success: (res) => {
						console.log(res.path);
						uni.showToast({
							title: '已保存至相册',
							duration: 2000
						});
					}
				});
				
			},
			//设置图片
			setImage(e) {
				console.log(e);
				this.savePhoto(e.path);
				//显示在页面
				//this.imagesrc = e.path;
				// if(e.dotype =='idphoto'){
				// 	this.zjzClipper(e.path);
				// }else if(e.dotype =='watermark'){
				// 	this.watermark(e.path);
				// }else{
				// 	this.savePhoto(e.path);
				// }
			},
			// 底部页签跳转
			tabChange(e){
			            console.log(e);
			},
			tabClick(e){
				console.log(e);
			},
			
			
			backAlbum () {
				this.state = '相册'
			},
			back () {
				uni.navigateBack({})
			}
			// closePreview () {
			// 	this.picShow = false;
			// }
		}
	}
</script>

<style lang="scss">
	// page {
	// 	// background-color: #eee;
	// }

	.list-box {
		width: 100%;
		display: flex !important;
		flex-direction: row;
		justify-content: flex-start ;
		flex-wrap: wrap;
		align-items: flex-start;
		align-content: flex-start;
		// padding: 20upx 10upx 0 20upx;
		line-height: 30upx;
		font-size: 28upx;
		color: #333;
		overflow: auto;
		&>view {
			background-color: #fff;
			border: 0.5rpx solid #282c31 ;
			opacity: 1;
			// transform:translateY(40upx);
			// transition: all 0.3s ease-in-out 0s;
			
			// &.active{
			// 	opacity: 1;
			// 	transform:translateY(0);
			// }
		}
		
		.image-box{
			width: 100%;
			height: 100%;
			position: relative;
			.radio {
				position: absolute;
				right: 0;
				top: 0;
				z-index: 2;
				padding: 0 !important;
				margin: 0 !important;
				/deep/ .uni-checkbox-input {
					width: 40rpx !important;
					height: 40rpx !important;
					border-radius: 50%;
					border-color: #fff;
					background-color: rgba($color: #FFFFFF, $alpha: 0);
					// opacity: 0.5;
				}
				/deep/ .uni-checkbox-input-checked:before {
					color: #fff;
					background-color: #0056B3;
					border-radius: 50%;
				}
				/deep/ .uni-checkbox-input:hover {
					border-color: #fff;
				}
			}
			image {
				width: 100%;
				height: 100%;
			}
			// border-radius: 15px;
			// margin-bottom: 10upx;
		}
		
	}
	
	.load{
		line-height: 80upx;
		text-align: center;
		font-size: 24upx;
		color: #999;
		padding-bottom: 20rpx;
	}
	.picShow {
		position: fixed;
		top: 0;
		bottom: 0;
		left: 0;
		right: 0;
		z-index: 10;
	}
	
</style>
