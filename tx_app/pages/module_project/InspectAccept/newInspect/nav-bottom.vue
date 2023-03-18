<template>
	<view v-show="true" :style="{width:phoneWidth+'px', height: '55px', backgroundColor: '#FFFFFF', borderTop: '1px solid #959595',zIndex: 20,position: 'fixed',bottom: '0px'}">
		<nav ref="navBottom" class="navButtom" >
			<view class="nav-item" 
				  v-for="(button,index) in buttonArr" 
				  :key="index"  @click="button.mothod" 
				  
			>
				<image :src="button.icon" mode="" :class="{'image-big': button.size=='big', 'image-middle': button.size=='middle', 'imag-small': button.size=='small' }"></image>
				<span style="font-size: 15px; color: #000;padding: 1px;display: block">{{button.title}}</span>
			</view>
		</nav>
	</view>
</template>

<script>

	export default {
		props: {
			buttonArr: {
				type: Array,
				default: ()=> {
					let arr = [
						{
							title: '按钮1',
							size: 'big',
							icon: '../../../../static/images/puzzle.png',
							mothod: () => {console.log('按下了按钮1');}
						},
						{
							title: '按钮2',
							size: 'middle',
							icon: '../../../../static/images/puzzle.png',
							mothod: () => {console.log('按下了按钮2');}
						},
						{
							title: '按钮3',
							size: 'small',
							icon: '../../../../static/images/puzzle.png',
							mothod: () => {console.log('按下了按钮3');}
						},
						{
							title: '按钮4',
							size: 'big',
							icon: '../../../../static/images/puzzle.png',
							mothod: () => {console.log('按下了按钮4');}
						},
						{
							title: '按钮5',
							size: 'big',
							icon: '../../../../static/images/puzzle.png',
							mothod: () => {console.log('按下了按钮5');}
						}
					];
					return arr;
				}
			}
		},
		components:{},
		data() {
			return {
				phoneWidth: '',
				phoneHeight: ''
			};
		},
		mounted() {
			const that = this;
			// 获取手机信息
			uni.getSystemInfo({
				success(res) {
					// const pixelRatio = res.pixelRatio;
					that.phoneWidth = res.screenWidth ;
					that.phoneHeight = res.screenHeight ;
					// console.log(that.phoneWidth,that.phoneHeight);
				}
			})
			// console.log(this.buttonArr);
		},
		methods: {}
	};
</script>

<style>
	.navButtom {
		height: 54px;
		width: 90%;
		position: fixed;
		bottom: 0px;
		margin-left: 5%;
		margin-right: 5%;
		/* border-top: '1px solid #959595'; */
		display: flex;
		flex-direction: row;
		justify-content: space-between;
		background-color: #FFFFFF;
	}
	.nav-item {
		width: 110rpx;
		height: 110rpx;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		padding: 2.5px;
		/* border: 1px solid blue; */
		/* border-radius:30rpx ; */
	}
	.image-big {
		width: 90%;
		height: 90%;
		display: block
	}
	.image-middle {
		width: 50%;
		height: 50%;
		display: block
	}
	.image-small {
		width: 40%;
		height: 40%;
		display: block
	}
</style>
