<template>
	<view>
		<ChooseLits @dateChange="dateChange" :list="list" :arr="arr" @chooseLike="chooseLike"></ChooseLits>
		<view v-if="this.testlist.length">
			<view class="cardlist" v-for="item of testlist" :key="item.id">
				<view class="cardlist-image">
					<image :src="item.image" :data-src="item.image"></image>
				</view>
				<view class="cardlist-content">
					<view class="cardlist-content-title">
						<h4>{{item.titlecontent}}</h4>
					</view>
					<view class="cardlist-content-titlesister">
						<h3>{{item.titlesistercontent}}</h3>
					</view>
					<view class="cardlist-content-inner" style="color: grey;">
						<text>{{item.checkcontent}}</text>
					</view>
					<view class="cardlist-content-footer">
						<button type="primary" v-if="item.checkcontent=='待下发'" @click="replynow(item)"  class="cardlist-content-footer-btn" style="font-size:12px;margin-left: 1px; color:white;background-color: #0081FF;">下发整改</button>
						<view class="cardlist-content-footer-iteam" @click="lookfor(item)" style="margin-top:10px;font-size:12px;color: #0081FF;">查看详情></view>
					</view>
				</view>
			</view>
		</view>
		 <nodata v-if="!this.testlist.length" :icon="require('@/static/images/nodata.png')" text="暂无可整改内容·"></nodata>

		<messagePush ref="select_person" :flag="'InspectMessagePush'" :person="'allPerson'" @refresh="completePush"></messagePush>
	</view>
</template>

<script>
	import { pathToBase64, base64ToPath } from '../../../../../js_sdk/gsq-image-tools/image-tools/index.js'
	import messagePush from '@/pages/module_project/InspectAccept/message-push/message-push.vue';
	import nodata from '@/components/uni-nodata/nodata.vue';
	import ChooseLits from '@/components/common/choose-Cade/choose-Cade2.vue'
	export default {
		components:{
			messagePush,
			nodata,
			ChooseLits,
		},
		data(){
			return {
				list: ['时间段', '类型'],
				arr: [
					['起始时间', '截止时间'],
					['待下发', '待整改', '待审核'],
					// ['草稿', '整改中', '通过'],
				],
				i2: [0, 0],
				firstTime:'',
				lastTime:'',
				status:'', 
				testlist:[
					// {
					// 	id:1,
					// 	image: require('../../../../../components/common/static/uni.png'),
					// 	titlecontent:"A栋二层东单元1-5轴",
					// 	titlesistercontent:"砌筑工程-砌体结构",
					// 	checkcontent:"草稿",
					// 	starttime:"开始日期：2020.09.30 ",
					// 	endtime:"结束日期：2020.10.30" ,
					// },
					// {
					// 	id:2,
					// 	image: require('../../../../../static/images/null.jpg'),
					// 	titlecontent:"B栋二层东单元1-5轴",
					// 	titlesistercontent:"抹灰工程-砌体结构",
					// 	checkcontent:"有风险",	
					// 	starttime:"开始日期：2020.09.30 ",
					// 	endtime:"结束日期：2020.10.30" ,
					// },
					// {
					// 	id:3,
					// 	image: require('../../../../../static/images/glxw.png'),
					// 	titlecontent:"C栋二层东单元1-5轴",
					// 	titlesistercontent:"灭灰工程-砌体结构",
					// 	checkcontent:"无风险",	
					// 	starttime:"开始日期：2020.09.30 ",
					// 	endtime:"结束日期：2020.10.30" ,
					// },
					// {
					// 	id:4,
					// 	image: require('../../../../../static/images/jcys.png'),
					// 	titlecontent:"D栋二层东单元1-5轴",
					// 	titlesistercontent:"产灰工程-砌体结构",
					// 	checkcontent:"有风险",	
					// 	starttime:"开始日期：2020.09.30 ",
					// 	endtime:"结束日期：2020.10.30" ,
					// },
					// {
					// 	id:5,
					// 	image: require('../../../../../static/images/sign.png'),
					// 	titlecontent:"E栋二层东单元1-5轴",
					// 	titlesistercontent:"刮灰工程-砌体结构",
					// 	checkcontent:"有风险",	
					// 	starttime:"开始日期：2020.09.30 ",
					// 	endtime:"结束日期：2020.10.30" ,
					// }
				],
				projectName:'',
				projectId:'',
				proTimeStamp:'',
				selectcard:[],
				username:'',
				userphone:'',
				timestamp:'',
				level:'',
				userid:'',
			};
		},
		mounted(){  
			this.getstorage()//获取本地缓存
			},
			// onShow() {
			// 	this.$on('current',res=>{
			// 		console.log(res)
			// 	})
			// },
		methods:{
			getstorage(){
				let that = this
				//获取公司项目的id工程名称
				uni.getStorage({
					key:'temporarycache',
					success: function(res){
						that.projectName = res.data.projectName
						that.projectId = res.data.projectId
						that.proTimeStamp = res.data.proTimeStamp
						that.getinformation()//服务器上获取卡片信息
						
					}
				})
				// 获取用户信息
				uni.getStorage({
					key:'userInfo',
					success:function(res) {
						let userInfo = res.data;
						that.username = userInfo.username
						that.userphone = userInfo.phone
						that.timestamp = userInfo.proTimeStamp
						that.level = userInfo.level
						that.userid = userInfo.userId
						// console.log(this.username)
						// console.log(this.userphone)
						// console.log(userInfo)
						
					}
				})
			},
			//获取服务器上卡片信息
			getinformation(){
				let that = this
				let opts = {
					url: this.api+'/module_project/InspectAccept/UploadPicture.php',
					method:'POST'
				}
				let param = {
					flag:'getViolationPic',
					projectId:that.projectId,
				}
				let isLoading = true//是否需要加载动画
				this.myRequest.httpRequest (opts,param,isLoading).then(res => {
					uni.hideLoading()//隐藏加载中转圈圈
					that.isLoading = false//取消遮罩层
					if(res.data.code){
						// console.log(res.data.data)
						let listdata = res.data.data
						that.changeobj(listdata)
					}
				},error =>{
					console.log(error)
				})
					
				
				// uni.showToast({
				// 	title:'提交成功！',
				// 	icon:"success",
				// 	duration:2000,
				// 	success:function(res){
				// 		uni.showModal({
				// 			content: 'show',
				// 			showCancel: false
				// 		});
				// 	}
				// })
			},
			//obj内容
			changeobj(data){
				let that = this
				let listdata = data
				let contentlist = []
				for(var i=0;i<listdata.length;i++){
					let picPath = this.imageUrl+'/inspectPic/ViolationPic/'+ listdata[i].inspectPointPic	
					// let picPathArr = []
					// let picPath = this.imageUrl+'/inspectPic/ViolationPic/'+ listdata[i].inspectPointPic[0]
					// for(var j=0;j<listdata[i].inspectPointPic.length;j++){
					// 	let picPath = this.imageUrl+'/inspectPic/ViolationPic/'+ listdata[i].inspectPointPic[j]
					// 	picPathArr.push(picPath)
					// }
					let endDate = listdata[i].endDate.split(' ')[0]
					let inspectDate = listdata[i].inspectDate.split(' ')[0]
					// console.log(endDate)
					var obj = {
						id:listdata[i].id,
						// image: require('../../../../../components/common/static/uni.png'),
						image:(listdata[i].inspectPointPic==='')?'../../../../../static/images/null.jpg':picPath,
						// image:(listdata[i].inspectPointPic[0]==='')?'../../../../../static/images/null.jpg':picPath,
						// picPathArr:picPathArr,
						titlecontent:listdata[i].inspectPosition,
						titlesistercontent:listdata[i].category+'-'+listdata[i].inspectItemHead,
						checkcontent:listdata[i].state,
						starttime:"开始日期：" + inspectDate,
						endtime:"结束日期：" + endDate,
						noticeTimeStamp:listdata[i].noticeTimeStamp,
					}
					contentlist.push(obj)
				}
				that.testlist = contentlist
				
			},
			//监听时间筛选
			dateChange(e, flag){
				let that = this
				flag == 'startTime' ? (this.firstTime = e) : (this.lastTime = e)
				if (this.firstTime != '' && this.lastTime != '') {
					this.testlist = []
					let opts = {
						url: this.api + '/module_project/InspectAccept/IssueRectification.php',
						method: 'POST'
					}
					let param = {
						flag: 'datechange',
						projectId: this.projectId,
						status: this.status,
						firstTime: this.firstTime,
						lastTime: this.lastTime
					}
					let isLoading = true //是否需要加载动画
					this.myRequest.httpRequest(opts, param, isLoading).then(res => {
						let data = res.data.data 
						if (res.data.code == '0') {
							uni.showToast({
								icon: 'error',
								position: 'bottom',
								title: res.data.msg
							});
						} else {
							that.changeobj(data)
						}
						uni.hideLoading() //隐藏加载中转圈圈
						that.isloading = false //取消遮罩层
					}, error => {
						console.log(error);
					})
				}
			},
			//监听整改状态筛选
			chooseLike(i1){
				let that = this
				if (this.i2[i1[0]] != i1[1]) {
					this.i2[i1[0]] = i1[1];
				}
				// console.log(this.arr[1][this.i2[1]]);
				let status = this.arr[1][this.i2[1]]
				this.status = status
				let opts = {
					url: this.api + '/module_project/InspectAccept/IssueRectification.php',
					method: 'POST'
				}
				let param = {
					flag: 'datechange',
					projectId: this.projectId,
					status: this.status,
					firstTime: this.firstTime,
					lastTime: this.lastTime
				}
				let isLoading = true //是否需要加载动画
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					uni.hideLoading() //隐藏加载中转圈圈
					this.isloading = false //取消遮罩层
					let data = res.data.data
					if (res.data.code == '0') {
						uni.showToast({
							icon: 'error',
							// position: 'bottom',
							title: res.data.msg
						});
					} else {
						that.changeobj(data)
					}
				}, error => {
					console.log(error);
				})
			},
			//下发整改
			replynow(item){
				this.$refs.select_person.toggle('bottom') // 直接调用子组件方法
				//预留复选框接入，将选中卡片的时间戳插入
				this.selectcard.push(item.noticeTimeStamp)
				var length = this.selectcard.length;
				for (var i = 0; i < length; i++) {
					this.$refs.select_person.$emit('cardTimeStamp',this.selectcard[i])
				}
				// console.log(item.endtime)
			},
			//跳转查看详情页面
			lookfor(item){
				let itemStr = JSON.stringify(item)
				
				// let itemStr = item
				uni.navigateTo({
					url:`TaskCheck?cardParam=${itemStr}`
				})
			},
			//监听组件下发整改后刷新数据
			completePush(current){
				this.selectcard=[];
				this.savecontent(current);
				this.getinformation()
				
			},
			//将检查内容保存到另一张表用于下发整改查看详情下方的信息展示栏
			savecontent(current){
				let noticeTimeStamp = current
				let that = this
				let opts = {
					url:this.api+'/module_project/InspectAccept/UploadPicture.php',
					method:'POST',
				}
				let param = {
					flag:'savecontent',
					projectId:that.projectId,
					noticeTimeStamp:noticeTimeStamp,
					username:that.username,
					userphone:that.userphone,
					timestamp:that.timestamp,
					level:that.level,
					userid:that.userid,
				}
				let isLoading = true //加载动画
				this.myRequest.httpRequest(opts,param,isLoading).then(res =>{
					uni.hideLoading()//隐藏加载转圈
					that.isLoading = false//取消遮罩层
					 
				},error =>{
					console.log(error)
				})
				
			}
		}
	}
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
				width: 120px;
				height: 120px;
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
					-webkit-line-clamp: 1;
					-webkit-box-orient: vertical;
				}
				.cardlist-content-titlesister{
					//单行文本溢出
					overflow: hidden;
					text-overflow: ellipsis;
					display: -webkit-box;
					-webkit-line-clamp: 1;
					-webkit-box-orient: vertical;
				}
				.cardlist-content-inner {
					// 文字溢出隐藏
									text {
										overflow: hidden;
										text-overflow: ellipsis;
										display: -webkit-box;
										-webkit-line-clamp: 2;
										-webkit-box-orient: vertical;
									}
				}
				.cardlist-content-footer {
					display: flex;
					justify-content: space-between;
					// font-size: 12px;
	 
					// .cardlist-content-desc-label {
					// 	display: flex;
						.cardlist-content-footer-btn {
								margin-top: 5px;
						}
						.cardlist-content-footer-iteam {
							// padding: 10 10px;
							border-radius: 15px;
							background-color: white;
							color: #FFFFFF;
							// margin-top: 20px;
						}
					// }
	 
					// .cardlist-content-desc-browse {
					// 	color: #999999;
					// }
				}
			}
		}
	
</style>
