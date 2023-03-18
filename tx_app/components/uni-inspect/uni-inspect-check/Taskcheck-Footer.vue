<template>
	<view>
		<view class="tablelist">
			<view class="tablelist-header">
				<textarea type="text" v-model="remarks" class="tablelist-header-write" placeholder="此处输入备注信息"></textarea>
			</view>
			<view class="tablelist-top">
				<view class="tablelist-top-left">工作进度:</view>
				<view class="tablelist-top-btn">
					<button type="primary" class="tablelist-top-btn-here" @click="addtohim" style="font-size:12px;margin-left: 1px; color:white;background-color: #0081FF;width: 100%;">添加备注</button>
				</view>
			</view>
			<view class="tablelist-footer">
				<view class="tablelist-footer-inner" v-for="item of tabledata" :key="item.id">
					<view class="tablelist-footer-inner-title" >
						<h4>{{item.date+"\xa0\xa0"+item.category}}</h4>
					</view>
					<view class="tablelist-footer-inner-titlesister" style="color: #0081FF;">
						<h3>{{item.hisjob+"\xa0\xa0"+item.hisname+"\xa0\xa0"+item.hisnumber}}</h3>
					</view>
					<view class="tablelist-footer-inner-content" style="color: grey;">
						<text>
							{{item.hiswords}}
						</text>
				</view>
				</view>
				
			</view>
		</view>
	</view>
</template>

<script>
	export default ({
		props:{
			cardlist:Object,
		},
		data(){
			return{
				tabledata:[
					// {
					// 	id:1,
					// 	date:'2021-10-09 10:35:11',
					// 	category:'下发整改',
					// 	hisjob:"生产经理",
					// 	hisname:"自来也",
					// 	datetimestamp:'1633746911000',
					// 	hisnumber:'13825562686',
					// 	hiswords:'如果 direction 属性是 ltr，则默认值是 left；如果 direction 是 rtl，则为 right。'
					// },
					// {
					// 	id:2,
					// 	date:'2021-10-11 11:31:22',
					// 	category:'整改回复',
					// 	hisjob:"施工员",
					// 	hisname:"纲手",
					// 	datetimestamp:'1633923082000',
					// 	hisnumber:'13824822786',
					// 	hiswords:'该属性通过指定行框与哪个点对齐，从而设置块级元素内文本的水平对齐方式。'
					// },
					// {
					// 	id:3,
					// 	date:'2021-10-13 12:15:33',
					// 	category:'添加备注',
					// 	hisjob:"质量员",
					// 	hisname:"卡卡西",
					// 	datetimestamp:'1634098533000',
					// 	hisnumber:'15295730486',
					// 	hiswords:'通过允许用户代理调整行内容中字母和字之间的间隔，可以支持值 justify；不同用户代理可能会得到不同的结果。'
					// },
				],
				mandata:[
					{
						hisjob:'项目总工',
						hisname:'阿斯玛',
						hisnumber:'15996723647'
					}
				],
				remarks:'',
				projectName:'',
				projectId:'',
				proTimeStamp:'',
				contentlist:[],
				username:'',
				userphone:'',
				timestamp:'',
				level:'',
				userid:'',
			}
		},
		mounted() {
			// this.tabledata.reverse()
			this.getstorage()//获取本地缓存
		},
		methods:{
			//获取本地缓存
			getstorage(){
				let that = this
				uni.getStorage({
					key:'temporarycache',
					success: function(res){
						that.projectName = res.data.projectName
						that.projectId = res.data.projectId
						that.proTimeStamp = res.data.proTimeStamp
						that.getchatcontent()//获取整改卡片查看详情下方通知栏内容
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
			//按照此处的传入参数返回一个重新排序后的数组
			sortKey(array, key){
				 return array.sort(function(a, b) {
				          var x = a[key];
				          var y = b[key];
				          return x > y ? -1 : x < y ? 1 : 0;
				        });
			},
			//获取整改卡片查看详情下方通知栏内容
			getchatcontent(){
				let that = this
				let opts = {
					url:this.api+'/module_project/InspectAccept/UploadPicture.php',
					method:'POST'
				}
				let param={
					flag:'getchatcontent',
					projectId:that.projectId,
					noticeTimeStamp:that.cardlist.noticeTimeStamp,
					
				}
				let isLoading = true //加载动画
				// myRequest.httpRequest
				this.myRequest.httpRequest(opts,param,isLoading).then(res =>{
					uni.hideLoading()//隐藏加载转圈
					that.isLoading = false//取消遮罩层
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
					 
					 
				},error =>{
					console.log(error)
				})
			},
			//添加备注
			addtohim(){
				if(this.remarks==''){
					uni.showToast({
						title:"请添加备注信息",
						icon:'error'
					})
				}else{
					// let gettime =''
					// let yy = new Date().getFullYear();
					// let mm = new Date().getMonth()+1;
					// let dd = new Date().getDate();
					// let hh = new Date().getHours();
					// let mf = new Date().getMinutes()<10 ? '0'+new Date().getMinutes() : new Date().getMinutes();
					// let ss = new Date().getSeconds()<10 ? '0'+new Date().getSeconds() : new Date().getSeconds();
					// gettime = yy+'-'+mm+'-'+dd+' '+hh+':'+mf+':'+ss;
					// console.log(gettime)
					let that = this
					let opts = {
						url:this.api+'/module_project/InspectAccept/UploadPicture.php',
						method:'POST'
					}
					let param = {
						flag:'insertmarks',
						projectId:that.projectId,
						noticeTimeStamp:that.cardlist.noticeTimeStamp,
						username:JSON.stringify(that.username),
						userphone:that.userphone,
						timestamp:that.timestamp,
						level:that.level,
						userid:that.userid,
						chatcontent:JSON.stringify(that.remarks),
					}
					let isLoading = true //加载动画
					this.myRequest.httpRequest(opts,param,isLoading).then(res =>{
						uni.hideLoading()//隐藏加载转圈
						that.isLoading = false//取消遮罩层
						console.log(res);
						if(res.data.code){
							uni.showToast({
								title:'添加成功',
								duration:2000,
								success:function(res){
									that.remarks = ''
									that.getchatcontent()
								}
							})
						}
						
						
						
					},error =>{
						console.log(error)
					})
					
					// var hissort = this.tabledata.leng                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        th +1;
					// console.log(hissort)
					// let obj ={
					// 		id:hissort,
					// 		date:gettime,
					// 		category:'添加备注',
					// 		hisjob:"项目总工",
					// 		hisname:"阿斯玛",
					// 		hisnumber:'15996723647',
					// 		hiswords:this.remarks
					// 	}
					// this.tabledata.unshift(obj)
					
				}
				
			},
		}
	})
</script>

<style lang="scss" scoped>
	.tablelist {
			display: flex;
			flex-direction: column;
			padding: 5px;
			margin: 10px;
			border-radius: 5px;
			// 卡片阴影
			box-shadow: 0 0 5px 1px rgba($color: #000000, $alpha: 0.1);
			
			.tablelist-header{
				margin-bottom: 5px;
				.tablelist-header-write{
					            /*去掉右下角的斜杠，不允许文本框缩放*/
					            resize: none;
					            background: #FFFFFF;
					            /*将边框去掉*/
								border: solid;
					            border-width: thin;
								border-radius: 10px;
					            /*设置宽度高度*/
					            height: 80px;
					            width: 100%;
					            /*设置字体大小*/
					            font-size: 14px;
					            /* placeholder位置 提示靠左靠右居中等 */
					            text-align: left;
					            /* 其他样式还有不少，没有用就没查，需要可自行查看 */
				}
			}
			.tablelist-top{
				display: flex;
				flex-direction: row;
				justify-content: space-between;
				
				.tablelist-top-left{
					font-size: 15px;
					color: grey;
				}
				.tablelist-top-btn{
				}
			}
			.tablelist-footer {
				margin-top: 10px;
				.tablelist-footer-inner{
					border-bottom: solid #F1F1F1;
					
					.tablelist-footer-inner-title{
						margin-top: 10px;
					}
					.tablelist-footer-inner-content{
						
					}
				}
					// box-shadow: 0 0 5px 1px rgba($color: #000000, $alpha: 0.1);
					
					
			}
			
		}
</style>
