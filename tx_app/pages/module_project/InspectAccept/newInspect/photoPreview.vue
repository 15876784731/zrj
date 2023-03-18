<template>
	<view class="" :style="{width: phoneWidth, height: phoneHeight,zIndex: 100000}">
		<!-- <view class="">
			<uni-nav-bar fixed="true" statusBar="true" left-icon="back" title="预览"  @clickleft="closePreview"></uni-nav-bar>
		</view> -->
		<view class="">
			<zetank-swiper :imageList="photoList" :autoplay="false" :height="previewHeight" type="default-nav" :current="current"></zetank-swiper>
		</view>
		<view class="">
			<nav-buttom :buttonArr="buttonArr"></nav-buttom>
		</view>
	</view>
</template>

<script>
	import uniNavBar from "@/components/uni-app/uni-nav-bar/uni-nav-bar"
	import navButtom from "./nav-bottom.vue"
	import zetankSwiper from "@/uni_modules/zetank-swiper/components/zetank-swiper/zetank-swiper.vue"
	export default {
		components: {
			uniNavBar,
			navButtom,
			zetankSwiper
		},
		data () {
			return {
				buttonArr: [
					{
						title: '',
						icon: '../../../../static/images/shanchued.png',
						size: 'middle',
						mothod: this.deletePhoto
					},
					{
						title: '',
						icon: '../../../../static/images/problem1.png',
						size: 'middle',
						mothod: this.markProblem
					},
					{
						title: '',
						icon: '../../../../static/images/detail.png',
						size: 'middle',
						mothod: this.detailProblem
					},
					
				],
				phoneWidth: 360,
				phoneHeight: 596,
				previewWidth: 0,
				previewHeight: 0,
				photoList: [],
				current: 0,  // 当前显示图片的下标
			}
		},
		onLoad(option) {
			this.photoList = JSON.parse(option.photoList)
			this.current = parseInt(option.index);
		},
		beforeMount() {
			const that = this;
			// 获取手机信息
			uni.getSystemInfo({
				success(res) {
					console.log(res);
					const pixelRatio = res.pixelRatio;
					that.phoneWidth = res.screenWidth ;
					that.phoneHeight = res.screenHeight ;
					that.previewWidth = res.windowWidth;
					that.previewHeight = res.windowHeight - 55;
				}
			})
		},
		watch: {
			photoList () {
				if (this.photoList.length == 0) {
					uni.navigateBack();
				}
			}
		},
		methods: {
			deletePhoto () {
				this.photoList.splice(this.current,1)
				let pages = getCurrentPages();
				let prevPage = pages[pages.length - 2]; //上一个页面
				// 调用相册组织的删除照片的方法
				prevPage.$vm.album.checkedPicIndex = [this.current];
				prevPage.$vm.album.deleteMoreImg()
			}
		}
	}
</script>

<style>
	
</style>
