<template>
	<view>
		<photo-album ref='album' :navTopTitle="navTopTitle" :navButtomArr="navButtomArr" @checkboxChange="checkboxChange" @previewPhoto="previewPhoto"></photo-album>
		<filter-popup  v-model="showPopup" title="请选择项目工序" :projectInfo="projectInfo"  height="950rpx" @finsh="subFinsh" ></filter-popup>
	</view>
</template>

<script>
	import photoAlbum from './PhotoAlbum.vue'
	import filterPopup from "@/components/filter-popup/filter-popup";
	export default {
		components: {
			photoAlbum,
			filterPopup
		},
		data() {
			return {
				navTopTitle: '工序检查',
				album: '',
				navButtomArr: {
					albumArr: [ // 相册页面底部按钮
						{
							title: '',
							icon: '../../../../static/images/xiafaed.png',
							size: 'middle',
							mothod: this.navToissuePage
						},
					],
					preArr: [{ // 预览页面底部按钮
							title: '',
							icon: '../../../../static/images/problem1.png',
							size: 'middle',
							mothod: this.markProblem
						},{
							title: '',
							icon: '../../../../static/images/detail.png',
							size: 'middle',
							mothod: this.detailProblem
					}]
				},
				
				checkedPicIndex: [], //选中的图片下标
				photoList: [], // 选中的图片
				photo: {}, // 预览的图片
				
				categoryIndex: 0,
				categoryArr:['建筑工程','装修工程'],//工程类别
				inspectItemIndex: 0,
				inspectProArr: [],
				formData: [],
				procedureList: [],
				showPopup: false, // 是否弹出
				projectInfo: {}
			}
		},
		beforeMount() {
			this.getStoragedata();
		},
		mounted() {
			this.album = this.$refs.album;
		},
		methods: {
			//获取本地缓存
			getStoragedata() {
				let that = this
				uni.getStorage({
					key: 'changeProRecord',
					success: function (res) {
						that.projectInfo = res.data
						console.log(res.data)
					}
				});
			},
			
			checkboxChange (res) {
				// console.log(res);
				this.checkedPicIndex = res.index;
				this.photoList = res.photoes;
			},
			previewPhoto (res) {
				console.log(res);
				this.photo = res
			},
			defineProject () {
				if (this.photoList.length == 1) { // 只有选中一张图片才能进行项目定义
				const photo = this.photoList[0];
					uni.navigateTo({
						url: './AddItemInspect?photo='+photo,
						success() {
							console.log('进入项目定义');
						}
					})
				} else if (this.photoList.length == 0) {
					uni.showToast({
						title: '请选择一张图片进行项目定义',
						icon: 'none'
					})
				} else {
					uni.showToast({
						title: '选择多张图片,请先进行拼图',
						icon: 'none'
					})
				}
			},
			navToissuePage () {
				uni.navigateTo({
					url: './CallBack/IssueRectification'
				})
			},
			markProblem() {
				console.log('问题');
				this.showPopup = true;
				// uni.navigateTo({
				// 	url: './SelectDescribe?photo=' + photo
				// })
			},
			subFinsh (obj) {
				console.log(obj);
				const strObj = JSON.stringify(obj)
				const photo = JSON.stringify(this.photo);
				uni.navigateTo({
					url: './SelectDescribe?photo=' + photo + '&procedureInfo=' + strObj
				})
				this.showPopup = false;
			},
			subReset (obj) {
			
			},
			
			
			
		}
	}
</script>

<style>
	
	.input-name{
	    height: 80upx;
	    /* width: 100%; */
		border-bottom: solid #888888 2rpx;
	    display: flex;
	    flex-direction: row;
	     /* justify-content: center; */
	    align-items: center;
	    position: relative;
	    padding-left: 30upx;
	    box-sizing: border-box;
	}

</style>
