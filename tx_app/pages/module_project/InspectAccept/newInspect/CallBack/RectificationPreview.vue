<template>
	<view>
			<!-- <view class="card-header">
				<view class="content">
					<text>折叠面板用来折叠/显示过长的内容或者是列表。通常是在多内容分类项使用，折叠不重要的内容，显示重要内容。点击可以展开折叠部分。			  </text>
				</view>
					
			</view> -->
		<!-- <unisection title="手风琴效果（只会保留一个的打开状态）" type="line"> -->
		<view v-if="type=='taskcheck'">
			<view class="collapse-body">
				<uniCollapse  accordion v-model="accordionVal1" @change="change1">
					<uniCollapseItem title="问题描述">
						<view class="content" v-if="common.length">
							<text class="text">一般问题</text>
							<view v-for="(item,index) of common" :key="index">
								<text class="linnertext">{{item}}</text>
							</view>
						</view>
						<view class="content" v-if="major.length">
							<text class="text">重大问题</text>
							<view v-for="(item,index) of major" :key="index">
								<text class="linnertext">{{item}}</text>
							</view>
						</view>
						
					</uniCollapseItem>
				</uniCollapse>
			</view>
			<view class="collapse-neck">
				<text style="color: #e6e6e6;">折叠面板用来折叠/显示过长的内容或者是列表。</text>
			</view>
		</view>
		<view v-if="type=='reply'">
			<view class="collapse-body">
				<uniCollapse  accordion v-model="accordionVal2" @change="change2">
					<uniCollapseItem title="备注信息">
						<view class="content" >
							<view v-for="(item,index) of tabledata" :key="index">
								<text class="text">{{item.category}}</text>
								</br>
								<text class="linnertext">{{item.hiswords}}</text>
							</view>
							
						</view>
					</uniCollapseItem>
				</uniCollapse>
			</view>
			<view class="collapse-neck">
				<text style="color: #e6e6e6;">折叠面板用来折叠/显示过长的内容或者是列表。</text>
			</view>
		</view>
			<view class="collapse-body">
				<uniCollapse  accordion v-model="accordionVal3" @change="change3">
					<uniCollapseItem title="检查层级" :showArrow="true">
						<view class="content">
							<text class="text">{{listcontent.inspectLevel}}</text>
						</view>
					</uniCollapseItem>
					<uniCollapseItem title="通知单号">
						<view class="content">
							<text class="text">{{listcontent.noticeNumber}}</text>
						</view>
					</uniCollapseItem>
					<uniCollapseItem title="工程类别" :showArrow="true">
						<view class="content">
							<text class="text">{{listcontent.category}}</text>
						</view>
					</uniCollapseItem>
					<uniCollapseItem title="检查项目">
						<view class="content">
							<text class="text">{{listcontent.inspectItemHead}}</text>
						</view>
					</uniCollapseItem>
					<uniCollapseItem title="检查工序">
						<view class="content">
							<text class="text">{{listcontent.inspectProcedure}}</text>
						</view>
					</uniCollapseItem>
					<uniCollapseItem title="检查部位">
						<view class="content">
							<text class="text">{{listcontent.inspectPosition}}</text>
						</view>
					</uniCollapseItem>
					<uniCollapseItem title="检查内容">
						<view class="content">
							<text class="text">{{listcontent.chatcontent}}</text>
						</view>
					</uniCollapseItem>
					<uniCollapseItem title="检查人员">
						<view class="content">
							<text class="text">{{listcontent.inspectPersonName}}</text>
						</view>
					</uniCollapseItem>
					<uniCollapseItem title="组长名称">
						<view class="content">
							<text class="text">{{listcontent.leaderName}}</text>
						</view>
					</uniCollapseItem>
					<uniCollapseItem title="联系方式">
						<view class="content">
							<text class="text">{{listcontent.contactMode}}</text>
						</view>
					</uniCollapseItem>
					<uniCollapseItem title="检查日期">
						<view class="content">
							<text class="text">{{listcontent.inspectDate}}</text>
						</view>
					</uniCollapseItem>
					<!-- <uniCollapseItem title="施工日期">
						<view class="content">
							<text class="text">{{listcontent.showcontent}}</text>
						</view>
					</uniCollapseItem> -->
					<uniCollapseItem title="截止日期">
						<view class="content">
							<text class="text">{{listcontent.endDate}}</text>
						</view>
					</uniCollapseItem>
				</uniCollapse>
			</view>

		<!-- </unisection> -->
	</view>
</template>

<script>
	import unisection from '../../../../../components/uni-section/uni-section.vue'
	import uniCollapse from '../../../../../components/uni-collapse/uni-collapse.vue'
	import uniCollapseItem from '../../../../../components/uni-collapse/uni-collapse-item.vue'
	
	export default {
		components:{
			unisection,
			uniCollapse,
			uniCollapseItem,
		},
		data(){
			return{
				accordionVal1:'1',
				accordionVal2:'1',
				accordionVal3:'0',
				listcontent:{
					// showcontent:'手风琴效果（只会保留一个的打开状态）'
				},
				cardTimeStamp:'',
				common:[],//一般
				major:[],//重大
				tabledata:[],//备注
				type:''
			}
		},
		onLoad(option) {
			let cardTimeStamp = option.cardTimeStamp
			this.cardTimeStamp = cardTimeStamp
			this.showquestion(cardTimeStamp)
			this.type =  option.type
		},
		mounted() {
			this.getquestion()
			this.getinformation()
		},
		methods:{
			change1(e) {
				console.log(e);
			},
			change2(e) {
				console.log(e);
			},
			change3(e) {
				console.log(e);
			},
			//获取显示内容
			showquestion(e){
				let that = this
				let cardTimeStamp = e
				let opts = {
					url:this.api + '/module_project/InspectAccept/RectificationReply.php',
					method:'POST'
				}
				let param = {
					flag:'showquestion',
					noticeTimeStamp:cardTimeStamp,
				}
				let isLoading = false //是否需要加载动画
				this.myRequest.httpRequest(opts, param, isLoading).then( res => {
					uni.hideLoading();
					console.log(res)
					let questiondata = res.data.data
					 that.listcontent = {
						inspectLevel: questiondata.inspectLevel,//检查层级
						noticeNumber: questiondata.noticeNumber,//通知单编号
						category: questiondata.category,//工程类别
						inspectItemHead: questiondata.inspectItemHead,//检查项目
						inspectPosition: questiondata.inspectPosition,//部位
						inspectProcedure: questiondata.inspectProcedure,//检查工序
						chatcontent:questiondata.chatcontent,//检查内容
						inspectPersonName: questiondata.inspectPersonName,//检查人员
						leaderName: questiondata.leaderName,//组长名称
						contactMode: questiondata.contactMode,//联系方式
						inspectDate: questiondata.inspectDate,//检查日期
						endDate: questiondata.endDate,//截止日期
						rectificationDate: questiondata.rectificationDate,//回复日期
						showcontent:'手风琴效果（只会保留一个的打开状态）'
					}
					
				},error => {
					console.log(error)
				})
			},
			//获取问题描述内容
			getquestion(){
				let that = this
				let opts = {
					url:this.api + '/module_project/InspectAccept/RectificationReply.php',
					method:'POST'
				}
				let param = {
					flag:'getquestion',
					noticeTimeStamp:that.cardTimeStamp,
				}
				let isLoading = false //是否需要加载动画
				this.myRequest.httpRequest(opts, param, isLoading).then( res => {
					uni.hideLoading();
					console.log(res)
					let questiondata = res.data.data
					let yiban = []
					let zhongda = []
					 for(var i=0;i<questiondata.length;i++){
						 if(questiondata[i].itemType=="一般问题"){
							 yiban.push(questiondata[i].violationContent)
						 }else{
							 zhongda.push(questiondata[i].violationContent)
						 }
					 }
					 that.common = yiban
					 that.major = zhongda
				},error => {
					console.log(error)
				})
			},
			//获取备注信息
			getinformation(){
				let that = this
				let opts = {
					url:this.api + '/module_project/InspectAccept/RectificationReply.php',
					method:'POST'
				}
				let param = {
					flag:'getinformation',
					noticeTimeStamp:that.cardTimeStamp,
				}
				let isLoading = false //是否需要加载动画
				this.myRequest.httpRequest(opts, param, isLoading).then( res => {
					uni.hideLoading();
					console.log(res)
					if(res.data.code){
						 var contentdata = res.data.data
						 var contentlist = []
						 for(var i=0;i<contentdata.length;i++){
							 let date = new Date(contentdata[i].issuetime)//将时间转为date类型
							 var datetimestamp = date.getTime()//将date转化为时间戳精确到毫秒
							 let obj = {
								 id:contentdata[i].id,
								 date:contentdata[i].issuetime,
								 category:contentdata[i].tasktype,
								 hisjob:contentdata[i].post,
								 hisname:contentdata[i].username,
								 hisnumber:contentdata[i].userphone,
								 hiswords:contentdata[i].chatcontent,
								 hisuserid:contentdata[i].userid,
								 hispersonalstamp:contentdata[i].personalstamp,
								 datetimestamp:datetimestamp
							 }
							 contentlist.push(obj)
						 }
						that.tabledata  = that.sortKey(contentlist, "datetimestamp");
					}
					
				},error => {
					console.log(error)
				})
			},
			//按照此处的传入参数返回一个重新排序后的数组//有小到大重排
			sortKey(array, key){
				 return array.sort(function(a, b) {
				          var x = a[key];
				          var y = b[key];
				          return x < y ? -1 : x > y ? 1 : 0;
				        });
			},
		}
	}
</script>

<style lang="scss" scoped>
	// .tablelist{
	// 	display: flex;
	// 	flex-direction: column;
	// 	padding: 5px;
	// 	margin: 10px;
	// 	border-radius: 5px;
	// 	// 卡片阴影
	// 	box-shadow: 0 0 5px 1px rgba($color: #000000, $alpha: 0.1);
	// }
	.example-body {
		flex-direction: column;
		flex: 1;
	}
	.content {
		padding: 15px;
	}
	
	.text {
		font-size: 14px;
		color: #0081FF;
		line-height: 20px;
	}
	.linnertext{
		margin-left: 10px;
	}
	.card-header{
		box-shadow: 0  1px 1px rgba($color: #000000, $alpha: 0.1);
	}
	.collapse-body{
		margin-top: 5px;
	}
	.collapse-neck{
		height: 10%;
		width: 100%;
		background-color: #e6e6e6;
	}
</style>
