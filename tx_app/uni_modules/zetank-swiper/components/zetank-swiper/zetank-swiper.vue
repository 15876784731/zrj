<template>
	<view style="text-align: center;">
		<image class="bg-set" v-if="imageList[currentIndex] && showBG" :src="imageList[currentIndex].url" mode="aspectFit"></image>
		<uni-swiper-dot  :current="currentIndex" field="content" :info="imageList" :mode="mode" :dotsStyles="dotsStyles">
			<swiper :style="{'background': 'url('+bg+')', 'background-size': 'cover', 'height': height+'px'}" class="default-container" :current="currentIndex"  :circular="circular" :autoplay="autoplay" @change="swiperChange" v-if="type==='default' || type==='default-nav'" >
				<swiper-item  v-for="(item, index) in imageList" :key="index" class="default-image">
					<!-- <image @click="clickImg(item)"  :src="item.url" lazy-load  mode="aspectFill" class="default-image" :style="{height:'100%', 'border-radius': radius+'px'}"></image> -->
					<movable-area class="default-image" style="background-color: #000000;" scale="true" direction="vertical" :damping='0' :out-of-bounds="true">
						<movable-view scale="true" direction="vertical" :damping='0' :style="{width: item.phoneWidth+'px', height: item.phoneWidth/item.imgRatio+'px'}">
							<image @click="clickImg(item)"  :src="item.url" lazy-load  mode="aspectFill" class="default-image" :style="{height:'100%', 'border-radius': radius+'px'}"></image>
						</movable-view>
					</movable-area>
				</swiper-item>
			</swiper>
			<swiper :style="{'background': 'url('+bg+')', 'background-size': 'cover', 'height': height+'px'}" class="card-container" :previous-margin="previousNext+'px'" :next-margin="previousNext+'px'" :circular="circular" :autoplay="autoplay" @change="swiperChange" v-if="type==='card'">
				<swiper-item :class="currentIndex == index ? 'swiper-item' : 'swiper-item-side'" v-for="(item, index) in imageList" :key="index">
					<image @click="clickImg(item)" :class="currentIndex == index ? 'item-img' : 'item-img-side'" :src="item.url" lazy-load :style="dontFirstAnimation ? 'animation: none;border-radius:'+ radius+'px': 'border-radius:'+ radius+'px'" mode="aspectFill"></image>
				</swiper-item>
			</swiper>
		</uni-swiper-dot>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				currentIndex: this.current,		// 当前显示图片
				dontFirstAnimation: true,
				mode:"round",
			}
		},
		props: {
			imageList: {
				type: Array
			},
			type:{
				type: String,
				default:"default"
			},
			showBG:{
				type: Boolean,
				default:false
			},
			dotsStyles:{
				type: Object,
				default:function (){
					return {
					bottom:20,
					width:8,
					color:'#fff',
					backgroundColor:'rgba(0, 0, 0, .3)',
					border:'1px rgba(0, 0, 0, .0) solid',
					selectedBackgroundColor:'#ffffff',
					selectedBorder:'1px rgba(0, 0, 0, .0) solid'
					}
				}
			},
			height:{
				type: Number,
				default:200
			},
			autoplay:{
				type: Boolean,
				default:true
			},
			circular:{
				type: Boolean,
				default:true
			},
			bg:{
				type: String,
				default:""
			},
			radius:{
				type: Number,
				default:0
			},
			previousNext:{
				type: Number,
				default:20
			},
			current: {
				type: Number,
				default: 0
			}
			
		},
		created() {
			if(this.type === "default-nav"){
				this.mode="nav"
			}
			console.log(this.imageList);
			console.log(this.current);
		},
		watch:{
			type(n){
				if(n === "default-nav"){
					this.mode="nav"
				}
				else{
					this.mode="round"
				}
			}
		},
		methods:{
			swiperChange(e) {
				this.dontFirstAnimation = false
				this.currentIndex = e.detail.current
			},
			clickImg(item) {
				this.$emit('clickImg', item, this.currentIndex)
			},
			
		}
	}
</script>

<style scoped>
	
	.bg-set{
		position: fixed;
		width: 100%;
		height: 100%;
		top: 0;
		left: 0;
		z-index: -1;
		filter: blur(20rpx)
	}
	
	.default-container{
		width: 100%;
	}
	.default-image{
		height: 100%;
		width: 100%;
		/* border-radius: 14rpx; */
	}

	.card-container {
		width: 100%;
		height: 350rpx;
	}
	
	.item-img {
		width: 630rpx;
		height: 100%;
		border-radius: 14rpx;
		animation: to-big .3s;
	}
	
	.swiper-item {
		width: 630rpx;
		height: 100%;
		display: flex;
		justify-content: center;
		align-items: center;
	}
	.item-img-side {
		width: 630rpx;
		height: 80%;
		border-radius: 14rpx;
		animation: to-mini .3s;
	}
	
	.swiper-item-side {
		width: 630rpx;
		height: 100%;
		display: flex;
		justify-content: center;
		align-items: center;
	}
	@keyframes to-mini
	{
		from {
			height: 300rpx;
		}
		to {
			height: 260rpx;
		}
	}
	@keyframes to-big
	{
		from {
			height: 260rpx;
		}
		to {
			height: 300rpx;
		}
	}
</style>
