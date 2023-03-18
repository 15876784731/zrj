<template>
	<view>
		<view class="replycontent">
			<view class="content-header">
				<view class="cardlist-content-title">
					<h3 style="margin-left: 10px;">{{cardlist.titlecontent}}</h3>
				</view>
				<view class="cardlist-content-titlesister">
					<h2 style="margin-left: 10px;">{{cardlist.titlesistercontent}}</h2>
				</view>
				
			</view>
			<view class="content-body">
				<view class="body-header" >
						<view style="color: grey;margin-left: 10px;font-size: 18px;">{{cardlist.starttime}}</view>
						<view style="margin-right: 10px;font-size: 18px;"><span :style="{'color':itscolor}">{{realendtime}}</span></view>
				</view>
				<view class="body-neck" >
						<view class="neck-left">整改前</view>
						<view class="neck-right">整改后</view>
				</view>
				<view class="body-body">
					<view class="body-body-left">
						<view v-if="cardlist.image1!==nullimage" class="bar-left">
							<image src="../../../../../static/images/problem1.png" @tap="showquestion"></image>
						</view>
						<view class="left-box"> 
							<image :src="cardlist.image1" :data-src="cardlist.image1" @tap="showpicture"></image>
						</view>
					</view>
					<view class="body-body-right">
						<view v-if="cardlist.image2!==nullimage&&position=='整改'" class="px-1 rounded" style="background-color: rgba(0,0,0,0.5);position: absolute;right: 5px;"
							@click.stop="delectproblem(index)">
								<!-- <text class="iconfont icon-shouye text-wihte"></text> -->
								<icon type="cancel" size="15"  color='#efefef' style="justify-content: center;align-items: center;display: flex;margin: 8rpx 0 8rpx 0;"/>
							</view>
						<view class="right-box">
							<image :src="cardlist.image2" :data-src="cardlist.image2" @tap="laterpicture"></image>
						</view>
						
					</view>
				</view>
			</view>
			<view class="content-arrow">
				<view class="left-arrow">
					<image src="../../../../../static/images/leftarrow.png" @tap="lastone"></image>
				</view>
				<span style="font-size: 20px;">{{molecule}}/{{denominator}}</span>
				<view class="right-arrow">
					<image src="../../../../../static/images/rightarrow.png" @tap="nextone"></image>
				</view>
				
			</view>
			<view class="tablelist-header">
				<!-- <span v-if="position=='整改'">
					<textarea type="text" v-model="remarks" class="tablelist-header-write" placeholder="此处输入备注信息"></textarea>
				</span> -->
				<span>
					<textarea type="text" v-model="remarks" class="tablelist-header-writeother" placeholder="此处输入备注信息"></textarea>
					<view @click="showproblem" style="float: right;color: #0081FF;">查看详情</view>
				</span>
			</view>
		</view>
		<!-- <view v-if="position=='整改'">
			<nav ref="navBottom" class="navBuotom" :style="{width:phoneWidth+'px'}">
				<view class="nav-item" @click="takepicture">
					<image src="../../../../../static/images/cameraa.png" mode="" style="width: 30px;height: 30px; display: block"></image>
					<span style="font-size: 15px; color: #000; padding: 1px;display: block;" >拍照</span>
				</view>
				<view class="nav-item" @click="getpicture">
					<image src="../../../../../static/images/album.png" mode="" style="width: 30px;height: 30px;display: block"></image>
					<span style="font-size: 15px; color: #000;padding: 1px;display: block">相册</span>
				</view>
				<view class="nav-item" @click="showproblem">
					<image src="../../../../../static/images/xiafaed.png" mode="" style="width: 30px;height: 30px; display: block"></image>
					<span  style="font-size: 15px; color: #000; padding: 1px; display: block">问题</span>
				</view>
				<view class="nav-item" @click="delectproblem">
					<image src="../../../../../static/images/shanchued.png" mode="" style="width: 30px;height: 30px; display: block"></image>
					<span style="font-size: 15px; color: #000;padding: 1px;">删除</span>
				</view>
			</nav>
		</view> -->
		<uni-popup ref="popup" :animation="true"  type="bottom">
		    <!-- <text>Popup</text> -->
			<button style="width: 100%;" @click="takepicture">拍照</button>
			<button style="width: 100%;" @click="getpicture">相册</button>
			<button style="width: 100%;" @click="closepop1">关闭</button>
		</uni-popup>
		<uni-popup ref="popupmanage" :animation="true"  type="bottom">
			<button style="width: 100%;" @click="uploadthis">提交</button>
			<button style="width: 100%;" @click="closepop2">关闭</button>
		</uni-popup>
		<uni-popup ref="popupcheck" :animation="true"  type="bottom">
			<button style="width: 100%;" @click="getadopt">通过</button>
			<button style="width: 100%;" @click="getreject">驳回</button>
			<button style="width: 100%;" @click="closepop3">关闭</button>
		</uni-popup>
		<uni-popup ref="popupquestion" :animation="true"  type="center">
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
		</uni-popup>
		<!-- <photoalbum></photoalbum> -->
	</view>
</template>

<script>
	import photoalbum from '@/components/uni-inspect/uni-inspect-album/photoalbum-bottom.vue';
	import permision from "@/common/permission.js"
	import { pathToBase64, base64ToPath } from '../../../../../js_sdk/gsq-image-tools/image-tools/index.js'
	export default {
		components:{
			photoalbum,
		},
		data() {
			return{
				cardlist:{
					// // image: require('../../../../../components/common/static/uni.png'),
					// image1: require('@/components/common/static/uni.png'),
					// image2: require('@/static/images/newadd.png'),
					// titlecontent:'A栋二层东单元1-5轴',
					// titlesistercontent:'砌筑工程-砌体结构',
					// starttime:'2020.09.30 星期四',
					
				},
				alreadyexists:false,
				phoneWidth:'',
				itscolor:'red',
				nullimage: require('@/static/images/newadd.png'),
				remarks:'',
				truetime:'',//精确到毫秒的实际整改时间
				realendtime:'',//实际整改时间
				position:"",//角色
				comefrom:'',//需回复的人员
				photoSrc:[],//base64
				userInfo:[],//用户信息
				denominator:'1',//分母
				molecule:'1',//分子
				nextdata:['',''],//整改后图片
				firstdata:['',''],//整改前图片
				msgid:'',//对应表格内消息位置
				common:[],//一般问题
				major:[],//重大问题
			}
		},
		//监听右上角管理按钮
		onNavigationBarButtonTap(e) {
			if(this.position=="整改"){
				this.$refs.popupmanage.open()
			}else if(this.position=="审核"){
				this.$refs.popupcheck.open()
			}
			
			
		},
		onLoad(option) {
			var cardTimeStamp = option.cardTimeStamp
			this.msgid = option.msgid
			this.position = option.positiontype
			this.comefrom = option.comefrom
			this.getdata(cardTimeStamp)//获取数据库内容
			this.checkposition()//判断角色
			this.getquestion(cardTimeStamp)//获取问题描述
		},
		created() {
			let that = this
			// 获取手机信息自适应底部导航框
			uni.getSystemInfo({
				success(res) {
					that.phoneWidth = res.screenWidth;
					// that.phoneHeight = res.screenHeight;
					// that.imageWidth = (that.phoneWidth/4 -2)
				}
			})
		},
		 mounted() {
			this.getstorage()
		    
		  },
		methods:{
			//获取本地缓存
			getstorage(){
				let that = this
				// 获取用户信息
				uni.getStorage({
					key:'userInfo',
					success:function(res) {
						that.userInfo = res.data;
						// console.log(that.userInfo)
						
					}
				})
			},
			//获取数据库内容
			getdata(e){
				let cardTimeStamp = e
				let that = this
				let opts = {
					url: this.api + '/module_project/InspectAccept/RectificationReply.php',
					method: 'POST'
				}
				let param = {
					flag: 'getRectification',
					noticeCardTimeStamp: cardTimeStamp
				}
				let isLoading = false //是否需要加载动画
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					console.log(res.data)
					let showdata = res.data.data[0]
					
					if(showdata.rectificationDate!='0000-00-00 00:00:00'){
						that.realendtime = showdata.rectificationDate.split(' ')[0]//实际整改时间
					}
					let inspectpic = that.imageUrl+'/inspectPic/ViolationPic/'+ showdata.inspectPointPic
					
					// let inspectpic = that.imageUrl+'/inspectPic/ViolationPic/'+ showdata.inspectPointPic[0]
					// for(var j=0;j<showdata.inspectPointPic.length;j++){
					// 	let picPath = this.imageUrl+'/inspectPic/ViolationPic/'+ showdata.inspectPointPic[j]
					// 	that.firstdata.push(picPath)
					// }
					// that.denominator = showdata.inspectPointPic.length
					// that.firstdata[0]=inspectpic
					// that.firstdata[1]=that.imageUrl+'/inspectPic/ViolationPic/'+ '1640745450402.jpg'
					
					
					let reactpic = that.imageUrl+'/inspectPic/ViolationPic/'+ showdata.rectificationpic
					// let inspectpic = that.imageUrl+'/inspectPic/ViolationPic/'+ showdata.rectificationpic[0]
					// if(showdata.rectificationpic.length=='0'){
						// for(var j=0;j<showdata.inspectPointPic.length;j++){
						// 	let picPath = this.nullimage
						// 	that.nextdata.push(picPath)
						// }
					// }else{
						// for(var j=0;j<showdata.rectificationpic.length;j++){
						// 	let picPath = this.imageUrl+'/inspectPic/ViolationPic/'+ showdata.rectificationpic[j]
						// 	that.nextdata.push(picPath)
						// }
					// }
					
					// that.nextdata[0]=(showdata.rectificationpic==='') ? that.nullimage:reactpic
					// that.nextdata[1]=that.nullimage
					
					let content = showdata.category+'-'+showdata.inspectItemHead
					let starttime = showdata.inspectDate.split(' ')[0]
					that.cardlist = {
						id:showdata.id,
						projectId:showdata.projectId,
						noticeTimeStamp:showdata.noticeTimeStamp,
						image1: inspectpic,
						image2: (showdata.rectificationpic==='') ? that.nullimage:reactpic,
						titlecontent:showdata.inspectPosition,
						titlesistercontent:content,
						starttime:starttime,
						dreamendtime:showdata.endDate,//预计整改时间
					}
					// uni.hideLoading() //隐藏加载中转圈圈
				}, error => {
					console.log(error);
				})
			},
			//判断人物的扮演角色为整改人还是审核人
			checkposition(){
			 if(this.position=="整改"){
				 setTimeout(()=>{
					 this.currentTime();
				 },1000)
				 
			 }else{
				 
			 }
			},
			//获取时间日期
			currentTime(){
					  let date = new Date();
				      let year = date.getFullYear(); // 年
				      let month = date.getMonth() + 1; // 月
				      let day = date.getDate(); // 日
				      let week = date.getDay(); // 星期
				      let weekArr = [ "星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六" ];
				      let hour = date.getHours(); // 时
				      hour = hour < 10 ? "0" + hour : hour; // 如果只有一位，则前面补零
				      let minute = date.getMinutes(); // 分
				      minute = minute < 10 ? "0" + minute : minute; // 如果只有一位，则前面补零
				      let second = date.getSeconds(); // 秒
				      second = second < 10 ? "0" + second : second; // 如果只有一位，则前面补零
					  this.truetime = `${year}-${month}-${day} ${hour}:${minute}:${second}`
				     
					  if(this.realendtime){
						  
					  }else{
						   this.realendtime = `${year}-${month}-${day}`;
						   // this.realendtime = `${year}-${month}-${day} ${weekArr[week]}`;
					  }
					  this.checktime()//判断是否超出日期
			},
			//判断是否超出日期
			checktime(){
					var realendtime = new Date(this.realendtime.split(' ')[0]).getTime()//实际整改时间
					var dreamendtime = new Date(this.cardlist.dreamendtime.split(' ')[0]).getTime()//预计整改时间
					if(realendtime>dreamendtime){//逾期
						this.itscolor = 'red'
					}else{
						this.itscolor = 'grey'
					}
				
			},
			//获取问题描述内容
			getquestion(e){
				let cardTimeStamp = e
				let that = this
				let opts = {
					url:this.api + '/module_project/InspectAccept/RectificationReply.php',
					method:'POST'
				}
				let param = {
					flag:'getquestion',
					noticeTimeStamp:cardTimeStamp,
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
			//关闭遮罩
			closepop1(){
				this.$refs.popup.close()
			},
			closepop2(){
				this.$refs.popupmanage.close()
			},
			closepop3(){
				this.$refs.popupcheck.close()
			},
			//图片预览
			showpicture(e){
				var current = e.target.dataset.src
				let photourl = new Array()
				photourl.push(current)
				uni.previewImage({
					current:current,
					urls:photourl,
				})
			},
			//路径转bse64
			urlTobase64(obj) {
				const that = this;
				let dat = []
				for (let i = 0; i < obj.length; i++) {
					pathToBase64(obj[i]).then(base64 => {
						console.log(base64);
						dat.push(base64)
					})
					.catch(error => {
						console.error(error)
					})
				}
				that.photoSrc = dat
				// pathToBase64(path).then(base64 => {
				// 	// this.formData.itemList[i].picPath = base64 
				// 	// console.log(base64);
				// 	that.photoSrc = base64;
				// })
				// .catch(error => {
				// 		console.error(error)
				// })
			},
			//根据有无整改图片判断预览还是拍照
			laterpicture(e){
				//如果整改后页面无照片则出现弹出层否则为预览
				if(this.cardlist.image2==this.nullimage){
					this.$refs.popup.open()
					// uni.navigateTo({
					// 	url:`../../../../../components/uni-inspect/uni-inspect-album/takephoto?dotype=face`
					// })
				}else{
					this.showpicture(e)
				}
			},
			//拍照存入相册并覆盖至页面
			takepicture(){
				uni.navigateTo({
					url:`../../../../../components/uni-inspect/uni-inspect-album/takephoto?dotype=replyface`
				})
				this.closepop1()
			},
			//调取相册图片
			getpicture(){
				let that = this
				uni.chooseImage({
					count:1,
					sizeType:['original','compressed'],
					sourceType:['album'],
					success: (res) => {
						const tempFilePaths = res.tempFilePaths
						that.cardlist.image2 = tempFilePaths[0]
						let order = that.molecule-1
						that.nextdata[order] = tempFilePaths[0];
						that.alreadyexists = true
						that.closepop1()
					}
				})
			},
			//展示该图片的问题描述
			showquestion(){
				this.$refs.popupquestion.open()
			},
			//展示图片定义时的内容
			showproblem(){
				let cardTimeStamp = this.cardlist.noticeTimeStamp;
				let type = 'reply'
				uni.navigateTo({
					url:`../../../../../pages/module_project/InspectAccept/newInspect/CallBack/RectificationPreview?cardTimeStamp=${cardTimeStamp}`+`&type=${type}`
				})
			},
			//设置图片(拍照)
			setImage(e) {
				console.log(e);
				//显示在页面
				//this.imagesrc = e.path;
				this.savePhoto(e.path);
				
			},
			//保存图片到相册，方便核查
			savePhoto(path){
				this.cardlist.image2 = path;
				let order = this.molecule-1
				this.nextdata[order] = path;
				//保存到相册
				uni.saveImageToPhotosAlbum({
					filePath: path,
					success: () => {
						uni.showToast({
							title: '已保存至相册',
							duration: 2000
						});
					}
				});
			},
			//向前切换图片
			lastone(){
				if(this.molecule!='1'){
					let order = this.molecule-2
					this.molecule--
					this.cardlist.image1 = this.firstdata[order]
					this.cardlist.image2 = this.nextdata[order]
				}
			},
			//向后切换图片
			nextone(){
				if(this.molecule!=this.denominator){
					let order = this.molecule
					this.molecule++
					this.cardlist.image1 = this.firstdata[order]
					this.cardlist.image2 = this.nextdata[order]
				}
			},
			//删除整改后照片
			delectproblem(){
				let that = this
				//判断有无添加图片
				let order = this.molecule-1
				if(this.nextdata[order] == this.nullimage){
					uni.showModal({
						content: '请添加整改后图片！',
						showCancel: false
					});
				}else{
					uni.showModal({
						title:'提示',
						content:'是否删除该图片？',
						success:function(res){
							if(res.confirm){
								that.cardlist.image2 = that.nullimage;
								let order = that.molecule-1
								that.nextdata[order] = that.nullimage;
							}else if(res.cancel){
								
							}
						}
					})
				}

				
			},
			//提交前判断
			uploadthis(){
				//先判断有无添加图片
				// for(var i=0;i<this.nextdata.length;i++){
				// 	if(this.nextdata[i]==this.nullimage){
				// 		uni.showModal({
				// 			content: '请添加整改后图片！',
				// 			showCancel: false
				// 		});
				// 		this.closepop2()
				// 		return;
				// 	}
				// }
				
				if(this.cardlist.image2==this.nullimage){
					uni.showModal({
								content: '请添加整改后图片！',
								showCancel: false
							});
							this.closepop2()
							return;
							
				}
				
				if(this.remarks==''){
					uni.showModal({
						content: '请添加备注信息！',
						showCancel: false
					});
					this.closepop2()
				}else{
					let that = this
					uni.showModal({
						title:'提示',
						content:'是否确认提交整改内容？',
						success:function(res){
							if(res.confirm){
								let data = []
								data.push(that.cardlist.image2)
								that.urlTobase64(data)
								setTimeout(()=>{
									that.trueupload()
								},1000)
								
							}else if(res.cancel){
								that.closepop2()
							}
						},
					})
				}
			},
			//提交整改内容并且将备注信息推入整改消息栏，，，回复下发人验收
			trueupload(){
				let that = this
				//提交整改内容并且将备注信息推入整改消息栏
				let opts = {
					url: this.api+'/module_project/InspectAccept/RectificationReply.php',
					method: 'POST'
				}
				let param = {
					flag: "saveReactPic",
					photoSrc: JSON.stringify(that.photoSrc),
					id: that.cardlist.id,
					noticeTimeStamp:that.cardlist.noticeTimeStamp,
					reacttime:that.truetime,
					remarks: JSON.stringify(that.remarks),
					projectId:that.cardlist.projectId,
					username:that.userInfo.username,
					userphone:that.userInfo.phone,
					level:that.userInfo.level,
					userid:that.userInfo.userId,
					msgid:that.msgid
				}
				let isLoading = true//是否需要加载动画
				// console.log(param);
				this.myRequest.httpRequest (opts, param,isLoading).then(res => {
					console.log(res.data)
					uni.hideLoading()//隐藏加载中转圈圈
					this.isloading = false//取消遮罩层
					that.closepop2()
					},error=>{
						
					})
				//回复下发人审核	
				let opt = {
					url: this.api+'/module_project/InspectAccept/RectificationReply.php',
					method: 'POST'
				}
				let params = {
					flag: "messagepush",
					cardTimeStamp:that.cardlist.noticeTimeStamp,
					reacttime:that.truetime,
					gofrom:that.userInfo.phone+'||'+that.userInfo.userId,
					comefrom:that.comefrom,
					projectId:that.cardlist.projectId,
					
				}
				// let isLoading = true//是否需要加载动画
				// console.log(param);
				this.myRequest.httpRequest (opt, params).then(res => {
					console.log(res.data)
					uni.hideLoading()//隐藏加载中转圈圈
					this.isloading = false//取消遮罩层
					that.closepop2()
					uni.showToast({
						icon: 'success',
						title: '提交成功！',
					});
					setTimeout(()=>{
						that.back()
					},1000)
					},error=>{
						
					})
				
				//推送手机通知栏消息
					let optss = {
						url: this.api+'/message_push/messageToperson.php',
						method: 'POST'
					}
					let paramss = {
						flag:'concrete',
						selectPerson:"username|"+that.userInfo.phone+"|post|"+that.userInfo.userId,
						cardTimeStamp:that.cardlist.noticeTimeStamp
					}
					this.myRequest.httpRequest (optss, paramss).then(res => {
						// let data = res.data
						console.log(res);
						// uni.showToast({
						// 	icon: 'success',
						// 	position: 'bottom',
						// 	title: '已下达'
						// });
					}, error => {
						console.log(error);
					})
			},
			//审核通过操作
			getadopt(){
				let that = this
				if(this.remarks==''){
					uni.showModal({
						title:'提示',
						content: '请添加备注信息！',
						success:function(res){
							if(res.confirm){
								that.closepop2()
							}else{
								that.closepop2()
							}
						}
					});
					
				}else{
					let that = this
					uni.showModal({
						title:'提示',
						content: '是否确认通过？',
						success:function(res){
							if(res.confirm){
								//提交整改内容并且将备注信息推入整改消息栏
								let opts = {
									url: that.api+'/module_project/InspectAccept/RectificationReply.php',
									method: 'POST'
								}
								let param = {
									flag: "getadopt",
									cardTimeStamp:that.cardlist.noticeTimeStamp,
									projectId:that.cardlist.projectId,
									remarks: JSON.stringify(that.remarks),
									username:that.userInfo.username,
									userphone:that.userInfo.phone,
									level:that.userInfo.level,
									userid:that.userInfo.userId,
									msgid:that.msgid
								}
								let isLoading = true//是否需要加载动画
								// console.log(param);
								that.myRequest.httpRequest (opts, param,isLoading).then(res => {
									console.log(res.data)
									uni.hideLoading()//隐藏加载中转圈圈
									that.isloading = false//取消遮罩层
									that.closepop3()
									uni.showToast({
										title:'整改已通过！',
										icon:"success",
									})
									setTimeout(()=>{
										that.back()
									},1000)
									},error=>{
										
									})
							}else{
								that.closepop3()
							}
						}
					});
				}

			},
			//审核驳回
			getreject(){
				if(this.remarks==''){
					uni.showModal({
						title:'提示',
						content: '请添加备注信息！',
						success:function(res){
							if(res.confirm){
								that.closepop2()
							}else{
								that.closepop2()
							}
						}
					});
				}else{
					let that = this
					uni.showModal({
						title:'提示',
						content: '是否确认驳回？',
						success:function(res){
							if(res.confirm){
								//驳回整改内容并且将备注信息推入整改消息栏
								let opts = {
									url: that.api+'/module_project/InspectAccept/RectificationReply.php',
									method: 'POST'
								}
								let param = {
									flag: "getreject",
									cardTimeStamp:that.cardlist.noticeTimeStamp,
									projectId:that.cardlist.projectId,
									remarks: JSON.stringify(that.remarks),
									username:that.userInfo.username,
									userphone:that.userInfo.phone,
									level:that.userInfo.level,
									userid:that.userInfo.userId,
									msgid:that.msgid
								}
								let isLoading = true//是否需要加载动画
								// console.log(param);
								that.myRequest.httpRequest (opts, param,isLoading).then(res => {
									console.log(res.data)
									uni.hideLoading()//隐藏加载中转圈圈
									that.isloading = false//取消遮罩层
									that.closepop3()
									uni.showToast({
										title:'整改已驳回！',
										icon:"success",
									})
									// setTimeout(()=>{
									// 	that.back()
									// },1000)
									},error=>{
										
									})
									
							//重新下发整改
									let opt = {
										url: that.api+'/module_project/InspectAccept/RectificationReply.php',
										method: 'POST'
									}
									let params = {
										flag: "messagepush",
										cardTimeStamp:that.cardlist.noticeTimeStamp,
										reacttime:that.truetime,
										gofrom:that.userInfo.phone+'||'+that.userInfo.userId,
										comefrom:that.comefrom,
										projectId:that.cardlist.projectId,
										status:'getreject',
									}
									// let isLoading = true//是否需要加载动画
									// console.log(param);
									that.myRequest.httpRequest (opt, params).then(res => {
										console.log(res.data)
										uni.hideLoading()//隐藏加载中转圈圈
										that.isloading = false//取消遮罩层
										that.closepop2()
										setTimeout(()=>{
											that.back()
										},1000)
										},error=>{
											
										})
							}else{
								that.closepop3()
							}
						}
					});
				}
				
			},
			back() {
				uni.navigateBack({ delta: 1 });    // 返回上一页
			},
		},
	}
</script>

<style lang="scss" scoped>
	.navBuotom {
		height: 80px;
		width: 100%;
		display: flex;
		position: fixed;
		bottom: 5px;
		flex-direction: row;
		justify-content: center;
		border-top: 1px solid #959595;
		// background-color: #FFFFFF;
	}
	.nav-item {
		width: 25%;
		height: 100px;	
		display: flex;
		flex-direction: column;
		align-items: center;
		// justify-content: center;
		padding: 20px;
		/* border: 1px solid blue; */
		/* border-radius:30rpx ; */
	}
	.replycontent{
		display: flex;
		flex-direction: column;
		// padding: 5px;
		// margin: 10px;
		border-radius: 5px;
		
		.tablelist-header{
			margin: 5px;
			.tablelist-header-write{
				            /*去掉右下角的斜杠，不允许文本框缩放*/
				            resize: none;
				            background: #FFFFFF;
				            /*将边框去掉*/
							border: solid;
				            border-width: thin;
							border-radius: 10px;
				            /*设置宽度高度*/
				            height: 60px;
				            width: 100%;
				            /*设置字体大小*/
				            font-size: 14px;
				            /* placeholder位置 提示靠左靠右居中等 */
				            text-align: left;
				            /* 其他样式还有不少，没有用就没查，需要可自行查看 */
			}
			.tablelist-header-writeother{
				            /*去掉右下角的斜杠，不允许文本框缩放*/
				            resize: none;
				            background: #FFFFFF;
				            /*将边框去掉*/
							border: solid;
				            border-width: thin;
							border-radius: 10px;
				            /*设置宽度高度*/
				            height: 100px;
				            width: 100%;
				            /*设置字体大小*/
				            font-size: 14px;
				            /* placeholder位置 提示靠左靠右居中等 */
				            text-align: left;
				            /* 其他样式还有不少，没有用就没查，需要可自行查看 */
			}
		}
		.content-header{
			// 卡片阴影
			// box-shadow: 0 0 5px 1px rgba($color: #000000, $alpha: 0.1);
			.cardlist-content-title{
				//两行文本溢出
				overflow: hidden;
				text-overflow: ellipsis;
				display: -webkit-box;
				-webkit-line-clamp: 2;
				-webkit-box-orient: vertical;
			}
			.cardlist-content-titlesister{
				margin-top: 5px;
				 //两行文本溢出
				 overflow: hidden;
				 text-overflow: ellipsis;
				 display: -webkit-box;
				 -webkit-line-clamp: 2;
				 -webkit-box-orient: vertical;
			 }
		}		
		.content-body{
			display: flex;
			flex-direction: column;
			// padding: 5px;
			// margin: 10px;
			border-radius: 5px;
			margin-top: 10px;
			// 卡片阴影
			// box-shadow: 0 0 5px 1px rgba($color: #000000, $alpha: 0.1);
			.body-header{
				// .body-header-contnet{
					display: flex;
					flex-direction: row;
					justify-content: space-between;
					margin-bottom: 5px;
					border-bottom: solid #00AA90;
				// }
			}
			.body-neck{
				display: flex;
				flex-direction: row;
				justify-content: space-between;
				.neck-left{
					color: white;
					font-size: 18px;
					width: 50%;
					text-align: center;
					background-color:#00AA90;
					border-right: solid;
					border-right-color: white;
					border-left: solid;
					border-left-colorS: white;
				}
				.neck-right{
					color: white;
					font-size:18px;
					width: 50%;
					text-align: center;
					background-color:#00AA90;
					border-left: solid;
					border-left-colorS: white;
					border-right: solid;
					border-right-color: white;
				}
			}
			.body-body{
				display: flex;
				flex-direction: row;
				justify-content: space-between;
				
				.body-body-left{
					width: 50%;
					height: 300px;
					border: solid;
					border-color: white;
					background-color: #F1F1F1;
						.bar-left{
								margin-top: 1%;
								margin-right: 1%;
								float: right;
								// margin-bottom: 40px;
								// 图片不被挤压
								flex-shrink: 0;
								width: 12%;
								height: 8%;
								border-radius: 5px;
									 
								image {
									width: 100%;
									height: 100%;
							}
						}
						.left-box{
							margin-top: 15%;
							margin-left: 9%;
							// margin-bottom: 40px;
							// 图片不被挤压
							flex-shrink: 0;
							width: 80%;
							height: 80%;
							border-radius: 5px;
								 
							image {
								width: 100%;
								height: 100%;
						}
					}
					
					// border-radius: 10px;
					// border-top-color: white;
					// border-right-color: white;
				}
				.body-body-right{
					width: 50%;
					height: 300px;
					border: solid;
					border-color: white;
					background-color:#F1F1F1 ;
					.right-box{
							// 图片不被挤压
						margin-top: 15%;
						margin-left: 11%;
						flex-shrink: 0;
						width: 80%;
						height: 80%;
						// border-radius: 5px;
							 
						image {
							width: 100%;
							height: 100%;
						}
					}
					
					// border-radius: 10px;
					// border-top-color: white;
					// border-left-color: white;
				}
			}
		}
		.content-arrow{
			display: flex;
			flex-direction: row;
			justify-content: space-between;
			width: 100%;
			height: 40px;
			.left-arrow{
				// 图片不被挤压
				// margin-top: 15%;
				flex-shrink: 0;
				width: 10%;
				height: 100%;
				// border-radius: 5px;
					 
				image {
					width: 100%;
					height: 100%;
				}
			 }
			 .right-arrow{
				 // 图片不被挤压
				 // margin-top: 15%;
				 flex-shrink: 0;
				 width: 10%;
				 height: 100%;
				 // border-radius: 5px;
				 	 
				 image {
				 	width: 100%;
				 	height: 100%;
				 }
			 }
		}
	}
	.content {
		margin: 5px;
		padding: 15px;
		background-color: #ffffff;
		border-radius: 5px;
		// 卡片阴影
		box-shadow: 0 0 5px 1px rgba($color: #000000, $alpha: 0.1);
	}
	
	.text {
		font-size: 14px;
		color: #0081FF;
		line-height: 20px;
	}
	.linnertext{
		margin-left: 10px;
	}
</style>
