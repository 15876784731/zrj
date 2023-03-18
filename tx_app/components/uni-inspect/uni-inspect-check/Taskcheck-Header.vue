<template>
	<view>
		<view class="cardlist">
			<view class="cardlist-image" >
				<image :src="cardlist.image" :data-src="cardlist.image" @tap="showpicture"></image>
			</view>
			<view class="cardlist-content">
				<view class="cardlist-content-title">
					<h4>{{cardlist.titlecontent}}</h4>
				</view>
				<view class="cardlist-content-titlesister">
					<h3>{{cardlist.titlesistercontent}}</h3>
				</view>
				<view class="cardlist-content-start" style="color: grey;">
					<h5>{{cardlist.starttime}}</h5>
					<h5>{{cardlist.endtime}}</h5>
				</view>
				<view class="cardlist-content-footer-iteam" @click="lookfor" style="font-size:12px;color: #0081FF;">查看详情></view>
			</view>
			<!-- <view class="cardlist-foot">
				<view class="cardlist-footer-btn">
					<button type="primary"   class="cardlist-footer-btn-top" style="font-size:12px;margin-left: 1px; color:white;background-color: #0081FF;width: 100%;">添加备注</button>
				</view>
			</view> -->
		</view>
	</view>
</template>

<script>
	export default ({
		props:{
			cardlist:Object,
		},
		data() {
			return{
				
			}
		},
		methods:{
			//表单内容查看
			lookfor(){
				let cardTimeStamp = this.cardlist.noticeTimeStamp
				let type = 'taskcheck'
				uni.navigateTo({
					url:`../../../../../pages/module_project/InspectAccept/newInspect/CallBack/RectificationPreview?cardTimeStamp=${cardTimeStamp}`+`&type=${type}`
				})
			},
			showpicture(e){
				// console.log(this.cardlist.image)
				var current = e.target.dataset.src
				// 将photourl设置为数组，多张照片时存入，预览可滑动
				let photourl = new Array()
				photourl.push(current)
				
				// let photourl = new Array()
				// photourl = this.cardlist.picPathArr
				uni.previewImage({
					// current:photourl[0],
					current:current,
					urls:photourl,
					// longPressActions:{
					// 	itemList:['保存到相册'],
					// 	success:function(res){
					// 		uni.downloadFile({
					// 			url: photourl[res.index],
					// 			success: (res) =>{
					// 				console.log(res.statusCode)
					// 				if (res.statusCode === 200){
					// 					uni.saveImageToPhotosAlbum({
					// 						filePath: res.tempFilePath,
					// 						success: function() {
					// 							//uniapp提供的消息提示框。
					// 							uni.showToast({
					// 								title: "保存成功",
					// 								icon: "success"
					// 							});
					// 						},
					// 						fail: function() {
					// 							uni.showToast({
					// 								title: "保存失败，请稍后重试",
					// 								icon: "none"
					// 							});
					// 						}
					// 					});
					// 				}
					// 			}
					// 		})
					// 	}
					// }
				})
			},
		},
	})
</script>

<style lang="scss" scoped>
	
	.cardlist {
			display: flex;
			padding: 5px;
			margin: 10px;
			border-radius: 5px;
			// 卡片阴影
			box-shadow: 0 0 5px 1px rgba($color: #000000, $alpha: 0.1);
	 
			.cardlist-image {
				// 图片不被挤压
				flex-shrink: 0;
				width: 150px;
				height: 150px;
				border-radius: 5px;
	 
				image {
					width: 100%;
					height: 100%;
				}
			}
	 
			.cardlist-content {
				display: flex;
				flex-direction: column;
				// justify-content: space-between;
				padding-left: 10px;
				// 不指定宽度就是内容的宽度
				width: 100%;
				
				.cardlist-content-title {
					font-size: 14px;
					color: #333333;
					font-weight: 400;
					//单行文本溢出
					overflow: hidden;
					text-overflow: ellipsis;
					display: -webkit-box;
					-webkit-line-clamp: 2;
					-webkit-box-orient: vertical;
				}
				.cardlist-content-titlesister{
					//单行文本溢出
					overflow: hidden;
					text-overflow: ellipsis;
					display: -webkit-box;
					-webkit-line-clamp: 2;
					-webkit-box-orient: vertical;
				}
				.cardlist-content-start{
					
				}
				.cardlist-content-end{
					
				}
				.cardlist-content-footer-iteam {
					// padding: 10 10px;
					border-radius: 15px;
					background-color: white;
					color: #FFFFFF;
					margin-left: 65%;
					margin-top: 15%;
				}
			}
			.cardlist-sister {
				display: flex;
				flex-wrap: wrap;
			}
		}
</style>
