<template>
	<view class="content">
		<view class="uni-list">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">检查层级：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.inspectLevel" /></view>
			</view>
		</view>
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">通知单号：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.noticeNumber" /></view>
			</view>
		</view>
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list">
					<view class="uni-list-cell">
						<view class="uni-list-cell-left">
							工程类别：
						</view>
						<view class="uni-list-cell-db">
							<picker @change="proCategoryChange" :value="index" :range="categoryArr">
								<view class="uni-input">{{categoryArr[index]}}</view>
							</picker>
						</view>
					</view>
				</view>
			</view>
			<view class="uni-list-cell">
				<view class="uni-list">
					<view class="uni-list-cell">
						<view class="uni-list-cell-left">
							检查项目：
						</view>
						<view class="uni-list-cell-db">
							<picker @change="inspectItemChange" :value="itemIndex" :range="inspectProArr" range-key="inspectionItem"> 
								<view class="uni-input">{{inspectProArr[itemIndex]}}</view>
							 </picker>
						</view>
					</view>
				</view>
			</view>
			<view class="uni-list-cell">
				<view class="uni-list">
					<view class="uni-list-cell">
						<view class="uni-list-cell-left">
							检查部位：
						</view>
						<view class="uni-list-cell-db">
							<view  @tap="handleTap('picker2')">
								<view class="item-content">
									<view class="item-value">
										<!-- <text class="item-label">{{ label2 }}</text> -->
										<view v-if="visiableform1" class="uni-input" style="color:grey;">请选择区段\栋号\楼层\单元</view>
										<view v-if="formData.inspectPosition" class="uni-input">{{formData.inspectPosition}}</view>
									</view>
								</view>
								<lb-picker ref="picker2" v-model="value2" mode="multiSelector" :list="list2" :level="4" :dataset="{ name: 'label2' }"
								 :formatter="formatter" @change="handleChange2" @confirm="handleConfirm2" @cancel="handleCancel2">
								</lb-picker>
							</view>
						</view>
					</view>
				</view>
			</view>
		</view> 
		
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list">
					<view class="uni-list-cell">
						<view class="uni-list-cell-left">
							检查工序：
						</view>
						<view class="uni-list-cell-db">
							 <ld-select3 :multiple="true" :list="options1" list-key="label" value-key="value" placeholder="请选择" clearable v-model="formData.inspectProcedure"
							  @change="selectProcedure"></ld-select3>
						</view>
					</view>
				</view>
			</view>
			<view class="uni-list-cell">
				<view class="uni-list">
					<view class="uni-list-cell">
						<view class="uni-list-cell-left">
							检查内容：
						</view>
						<view class="uni-list-cell-db" >
							<ld-select4 :multiple="true" :list="options2" list-key="label" value-key="value" placeholder="请选择" clearable v-model="formData.inspectContent"
							 @change="inspectContentChange"></ld-select4>
						</view>
					</view>
				</view>
			</view>
		</view>
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list">
					<view class="uni-list-cell">
						<view class="uni-list-cell-left">检查人员：</view>
						<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.inspectPerson" /></view>
					</view>
				</view>
			</view>
				<view class="uni-list-cell">
					<view class="uni-list">
						<view class="uni-list-cell">
							<view class="uni-list-cell-left">组长名称：</view>
						<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.leaderName" /></view>
						</view>
					</view>
				</view>
				<view class="uni-list-cell">
					<view class="uni-list">
						<view class="uni-list-cell">
							<view class="uni-list-cell-left">联系方式：</view>
							<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.leaderPhone" /></view>
						</view>
					</view>
				</view>
		</view>
		<pick-date class="mt-1" datename="检查日期：" @date="getInspectDate"></pick-date>
		<pick-date  datename="施工日期：" @date="getEndDate"></pick-date>
		<pick-date datename="截止日期：" @date="getEndDate"></pick-date>
		
		<view class="uni-list mt-1">
			<view class="uni-list-cell" @click="selectDescribe">
				<view class="uni-list-cell-left">问题描述：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.questionDescribe" disabled="true"/></view>
			</view>
			<view class="uni-list-cell" @click="previewPhoto">
				<view class="uni-list-cell-left">图片详情：</view>
				<view class="uni-list-cell-db" ><input class="uni-input" type="text" value="点击预览图片" disabled="true"/></view>
			</view>
		</view>
		
		<view class="flex spacebutoom">
			<button type="primary" class="button" @click="back()">关闭</button>
			<!-- <button type="primary" class="button" @click="violationPicPage()">违章照片</button> -->
			<button :disabled="isconfirm" type="primary" class="button" @click="confirm()">保存</button>
		</view>
		
	</view>
</template>

<script>
import pickdate from '@/components/common/pick-date/pick-date.vue'; //日期选择器
import LbPicker from '../../../../components/common/lb-picker'
import ldSelect3 from '@/components/common/ld-select/ld-select3.vue'; //多选组件
import ldSelect4 from '@/components/common/ld-select/ld-select4.vue'; //多选组件
import { pathToBase64, base64ToPath } from '../../../../js_sdk/gsq-image-tools/image-tools/index.js'
export default {
	props:{},
	components: {
		'pick-date': pickdate,
		'ldSelect3': ldSelect3,
		'ldSelect4': ldSelect4,
		 LbPicker,
	},
	data() {
		return {
			isconfirm:true,
			formData: {
				inspectLevel:'',//检查层级 
				noticeNumber: '',//通知单编号
				category: '', //  工程类别
				inspectItem: '',//检查项目
				inspectProcedure: [], //检查工序
				inspectPosition:'',//检查部位
				inspectContent: [],//检查内容
				leaderName:'',//组长名称
				inspectPerson: '', // 检查人员
				leaderPhone: '', // 组长联系方式
				inspectDate:'',//检查日期
				endDate: '' , // 施工日期, 截止日期
				questionDescribe:'选中了0项',//问题描述条目
				violationItem: [], // 问题描述
				photoSrc: '',
				itemList: [], // 
			},
			index: 0,
			currentData:[],
			buildSelData: {},
			projectInfo: {},
			inspectOrderArr: [{id:'',inspectOrder:''}],
			orderIndex: 0,
			cardParam: {},
			inspectContentIndex: 0,
			inspectContentArr: [{id:'',inspectContent:''}],
			options1: [],
			options2: [],
			imageList: [],
			categoryArr:['建筑工程','装修工程'],//工程类别
			inspectProArr: [],//检查项目
			label1:'',
			label2:'',
			label3:'',
			list2:[],
			value2: [],
			list3:[],
			visiableform1:true,
			visiableform2:true,
			checkvisiable:false,
			itemIndex: 0,
			storagedata:[],
			photo: {}, // 相册传过来的图片,
		};
	}, 
	onLoad:function(option) {
		const that = this;
		// console.log(option);
		// this.cardParam = JSON.parse(option.cardParam);
		this.photo = JSON.parse(option.photo);
		console.log(this.photo);
		
		// console.log(this.currentData,this.cardParam)
	},
	onShow() {
		uni.$on('selectItem',res=>{
			// console.log(res)
			this.formData.questionDescribe = '选中了'+res.itemsNum+'项'
			this.formData.violationItem = res.violationItem ;
			console.log(this.formData.violationItem);
			if(res.itemsNum>0){
				this.isconfirm = false
			}
		})
		uni.$on('sendChildPageData',res=>{
			console.log(res)
			this.formData.itemList = res.lists
		})
	},
	mounted() {
		let that = this 
		this.getStoragedata()
		setTimeout(function(){
			let data = that.categoryArr[that.index]
			that.formData.category = data;
			that.getNewCardDataFunc(data) 
		},1000)
		setTimeout(function(){
			let data = that.inspectProArr[that.index]
			that.formData.inspectItem = data;
			that.getProcedureFunc(data) 
		},2000)
		this.setthisdata()
	},
	methods: {
		handleTap(picker) {
			this.$refs[picker].show()
		},
		formatter({
			item,
			rowIndex,
			columnIndex
		}) {
			return `${item.value}`
		},
		//获取检查日期
		getInspectDate(val){ 
			this.formData.inspectDate = val
		},
		//获取施工日期
		getEndDate(val){
			// console.log(val)
			this.formData.endDate = val
		},
		 //产生随机数函数
		RndNum(n){
			var rnd="";
			for(var i=0;i<n;i++)
				rnd+=Math.floor(Math.random()*10);
			return rnd;
		},
		
		
		//获取检查层级以及通知单号内容
		setthisdata(){
				this.formData.inspectLevel = "项目部"
				var myDate = new Date();//获取系统当前时间
				var timesTamp = myDate.toLocaleTimeString('chinese', { hour12: false });
				timesTamp=timesTamp.replace(/:/g,"");
				let rndVal = this.RndNum(2)
				let month = myDate.getMonth() + 1;
				let strDate = myDate.getDate();
				this.formData.noticeNumber = "项—"+month+strDate+timesTamp+rndVal
		},
		//
		proCategoryChange(e) {
			this.index = e.target.value
			var proItem = this.categoryArr[this.index]
			this.formData.category = proItem;
			this.getNewCardDataFunc(proItem)
		},
		inspectItemChange(e) {
			this.itemIndex = e.target.value
			var proItem = this.inspectProArr[this.itemIndex];
			this.formData.inspectItem = proItem;
			this.getProcedureFunc(proItem)
		},
		//获取检查项目
		getNewCardDataFunc(data){
			console.log(data)
			let proTimeStamp = this.projectInfo.proTimeStamp 
			console.log(proTimeStamp)
			let opts = {
				url: this.api+'/module_project/InspectAccept/InspectAccept.php',
				method: 'POST'
			}
			let param = {
				flag: 'getInspectItemInfo',
				proCategory:data,
				proTimeStamp:proTimeStamp
			}
			let isLoading = true//是否需要加载动画
			this.myRequest.httpRequest (opts, param,isLoading).then(res => {
				console.log(res.data)
				uni.hideLoading()//隐藏加载中转圈圈
				this.isloading = false//取消遮罩层
				if(res.data.code){
					// console.log(res.data.data);
					this.inspectProArr = res.data.data
					// this.createFloorFunc(res.data.data)
				}
			}, error => {
				console.log(error);
			})
		},
		//区段栋号picker动态监听回调
		handleChange2(e){
			
		},
		//区段栋号picker完成按钮监听回调
		handleConfirm2(e){
			// this.label2 = e.value[0] + "-" + e.value[1]+ "-" + e.value[2]+ "-" + e.value[3]
			this.formData.inspectPosition = e.value[0] + "-" + e.value[1]+ "-" + e.value[2]+ "-" + e.value[3];
			console.log(this.formData.inspectPosition);
			// this.label1 = this.label2
			if(this.formData.inspectPosition!=''){
				this.visiableform1 = false
				uni.showTabBar({
					title: '网络异常!',
					icon: 'none'
				})
				return
			}
			 //根据栋号异步获取楼层单元
			// let opts = {
			// 	url: this.api + '/module_project/SelectBuildInfo.php',
			// 	method: 'POST'
			// }
			// let param = {
			// 	flag: 'getFloorUnit',
			// 	proTimeStamp: this.storagedata.proTimeStamp,
			// 	build: e.value[1],
			// }
			// let isLoading = false //是否需要加载动画       
			// this.myRequest.httpRequest(opts, param, isLoading).then(res => {
			// 	console.log(res)
			// 	let unitName = res.data.unitName[0].split('||')
			// 	let undergroundNumber = res.data.undergroundNumber
			// 	let abovegroundNumber = res.data.abovegroundNumber 
			// 	// console.log(undergroundNumber+" "+abovegroundNumber+" "+unitName.split('||'))
			// 	let floorArr = this.commonFunc.toChinesNum_floor(undergroundNumber, abovegroundNumber)
			// 	let unitArr = unitName
			// 	let child_arry = [];
			// 	let list3 = [];
			// 	for (let i = 0; i < floorArr.length; i++) {
			// 		child_arry = []
			// 		for (let j = 0; j < unitArr.length; j++) {
			// 			child_arry.push({
			// 				value: unitArr[j],
			// 				label: unitArr[j]
			// 			})
			// 		}
			// 		// list3 = [];
			// 		list3.push({
			// 			value: floorArr[i],
			// 			label: floorArr[i],
			// 			children: child_arry,
			// 		})
			// 	}
			// 	console.log(list3)
			// 	this.list3 = list3;
			// }, error => {
			// 	console.log(error);
			// })
		
		},
		////区段栋号picker取消按钮监听回调
		handleCancel2(e){
			this.formData.inspectPosition = '';
			this.value2 = [];
			this.visiableform1 = true
			// this.label1 = ''
			// this.label3 = ''
			// this.visiableform2 = true
			console.log('cancel::', e)
		},
		//楼层单元picker监听回调
		handleChange3(e){
			
		},
		//楼层单元picker完成按钮监听回调
		handleConfirm3(e){
				this.label3 = e.value[0] + "-" + e.value[1]
				this.label1 = this.label1 +'-' + this.label3
				console.log('change::', e.value)
				if(this.label3!=''){
					this.visiableform2 = false
				}
				this.checkvisiable = false
		},
		//楼层单元picker取消按钮监听回调
		handleCancel3(e){
			this.label3 = '';
			this.value3 = [];
			this.visiableform2 = true
			this.label1 = this.label2
			console.log('cancel::', e)
		},
		//获取区段栋号楼层单元
		getBuildInfo(){
			const that = this;
			let opts = {
				url: this.api + '/module_project/SelectBuildInfo.php',
				method: 'POST'
			}
			let param = {
				flag: 'getSectionBuild',
				proTimeStamp: this.projectInfo.proTimeStamp
			}
			let isLoading = false //是否需要加载动画       
			this.myRequest.httpRequest(opts, param, isLoading).then(res => {
				// isLoading = true;
				uni.showLoading({
					title: '加载中...'
				})
				// console.log(res)
				let sectionArr = res.data.section
				let buildArr = res.data.build
				let unitNameArr = res.data.unitName
				let child_arry_build = []
				let child_arry_unit = []
				
				for (let i = 0; i < sectionArr.length; i++) { // i 代表区数
					child_arry_build = [];
					let child_arry_unit = [];
					let build = buildArr[i].split(',');
					let unitName = unitNameArr[i].split(',');
					let undergroundNumberArr = res.data.undergroundNumber[i] // A 区的楼栋的地下层数, arry
					let abovegroundNumberArr = res.data.abovegroundNumber[i]
					let unitArr = unitName // 某栋的单元划分
					for (let j = 0; j < build.length; j++) { // j 代表楼栋数
						let unit =  unitName[i].split('||');
						let unit_arr = [];
						for (let l = 0; l < unit.length; l++) { // l 代表单元数
							unit_arr.push({
								value: unit[l],
								label: unit[l],
							}) // 某栋的单元划分
						}
						// console.log(unit_arr);
						let undergroundNumber = undergroundNumberArr.split(',')[j];
						let abovegroundNumber = abovegroundNumberArr.split(',')[j];
						// console.log(undergroundNumber,abovegroundNumber)
						let floorArr = that.commonFunc.toChinesNum_floor(undergroundNumber, abovegroundNumber)
						// console.log(floorArr)
						let child_arry_floor = []// 每栋的楼层数组
						for (let k = 0; k < floorArr.length; k++) { // k 代表楼层数
							child_arry_floor.push({
								value: floorArr[k],
								label: floorArr[k],
								children: unit_arr
							})
							
						}
						// console.log(child_arry_floor)
						child_arry_build.push({
							value: build[j],
							label: build[j],
							children: child_arry_floor 
						})
					}
					// console.log(child_arry_build)
					that.list2.push({
						value: sectionArr[i],
						label: sectionArr[i],
						children: child_arry_build
					})
					// console.log(that.list2);
					// uni.hideLoading();
				}
			}, error => {
				console.log(error);
			})
			
		},
		
		
		
		//获取检查工序(留)
		getProcedureFunc(data) {
			let that = this
			let projectId = this.projectInfo.projectId
			let opts = {
				url: this.api+'/module_project/InspectAccept/AddItemInspect.php',
				method: 'POST'
			}
			let param = {
				flag: 'getProcedureInfo',
				projectId: projectId,
				inspectItem:data,//检查项目
				// inspectAcceptId: this.cardParam.id
			}
			uni.$emit('send', this.formData.inspectProcedure)//传给平行子组件
			let isLoading = true//是否需要加载动画
			this.myRequest.httpRequest (opts, param,isLoading).then(res => {
				// console.log(res.data)
				uni.hideLoading()//隐藏加载中转圈圈
				this.isloading = false//取消遮罩层 
				// console.log(res)
				if(res.data.code){ 
					this.options1 = []
					// console.log(res.data.data)
					for(var i=0;i<res.data.data.length;i++){
						let label = res.data.data[i].procedureName.split('.')[1]
						this.options1.push({
							value:label,
							label:label
						})
					}
				}
			}, error => {
				console.log(error);
			})
		},
		//获取本地缓存
		getStoragedata() {
			let that = this
			uni.getStorage({
				key: 'changeProRecord',
				success: function (res) {
					that.projectInfo = res.data
					// console.log(res.data)
					that.getBuildInfo()
				}
			});
			// 获取用户信息
			uni.getStorage({
				key:'userInfo',
				success:function(res) {
					let userInfo = res.data;
					that.formData.inspectPerson = userInfo.username;
					that.formData.leaderName = userInfo.username;
					that.formData.leaderPhone = userInfo.phone;
					// console.log(userInfo)
				}
			})
			// uni.getStorage({
			// 	key:'temporarycache',
			// 	success:function(res){
			// 		that.storagedata = res.data
			// 		console.log(res.data)
			// 	}
			// })
			// uni.getStorage({
			// 	key: 'buildInfo',
			// 	success: function (res) {
			// 		that.buildSelData = res.data
			// 		console.log(res.data)
			// 	}
			// });
			
		},
		inspectOrderChange(e) {
			this.orderIndex = e.target.value
		},
		//路径转bse64
		urlTobase64(path) {
			const that = this;
			// for (let i = 0; i < obj.length; i++) {
			// 	pathToBase64(obj[i].picPath).then(base64 => {
			// 		// this.formData.itemList[i].picPath = base64 
			// 		console.log(base64);
			// 		that.formData.photoSrc = base64;
			// 	})
			// 	.catch(error => {
			// 		console.error(error)
			// 	})
			// 	// console.log(this.formData.itemList[i].picPath)
			// }
			pathToBase64(path).then(base64 => {
				// this.formData.itemList[i].picPath = base64 
				// console.log(base64);
				that.formData.photoSrc = base64;
			})
			.catch(error => {
					console.error(error)
			})
		},
		//保存卡片信息（留）
		saveCardDataFunc() {
			const that = this;
			// console.log(that.buildSelData)
			var arr = []
			console.log(this.formData.inspectContent);
			// for(var i=0;i<this.formData.inspectContent.length;i++){
			// 	arr[i] = this.formData.inspectContent[i].split('.')[0]
			// }
			let opts = {
				url: this.api+'/module_project/InspectAccept/UploadPicture.php',
				method: 'POST'
			}
			let param = {
				flag: "saveViolationPic",
				image: JSON.stringify(this.photo),
				formData: JSON.stringify(this.formData),
				projectId: this.projectInfo.projectId,
				measureId: this.cardParam.id,
				number: this.formData.inspectContent.join('||')
			}
			let isLoading = true//是否需要加载动画
			// console.log(param);
			this.myRequest.httpRequest (opts, param,isLoading).then(res => {
				console.log(res.data)
				uni.hideLoading()//隐藏加载中转圈圈
				this.isloading = false//取消遮罩层
				if(res.data.code){ 
					uni.$emit('updateCardData')
					uni.showToast({
						title:'提交成功！',
						icon:"success",
						// duration:2000,
						// success:function(res){
						// 	 uni.navigateBack()
						// }
					})
					setTimeout(()=>{
						uni.redirectTo({
							url: './CallBack/IssueRectification',
							success() {
								console.log('跳转到下发页面成功');
							}
						})
						// uni.navigateBack()
					},1000)
					
				}
			}, error => {
				console.log(error);
			})
		},
		confirm() {
			console.log(this.photo.file.fullPath);
			this.urlTobase64(this.photo.file.fullPath)
			let that = this
			setTimeout(that.saveCardDataFunc,1000)
		},
		selectProcedure(val) { 
			// console.log(val);
			this.options2 = []
			if (val.length == 0) return; // 只有检查内工序不为空才能选择检查内容
			const that = this;
			this.formData.inspectProcedure = val;
			this.formData.inspectContent = [];
			let projectId = this.projectInfo.projectId
			let opts = {
				url: this.api+'/module_project/InspectAccept/AddItemInspect.php',
				method: 'POST'
			}
			let param = {
				flag: 'getInspectInfo',
				projectId: projectId,
				procedureName: val
			}
			uni.$emit('send1', this.formData.inspectContent)//传给平行子组件
			let isLoading = true//是否需要加载动画
			this.myRequest.httpRequest (opts, param,isLoading).then(res => {
				// console.log(res.data)
				uni.hideLoading()//隐藏加载中转圈圈
				that.isloading = false//取消遮罩层
				if(res.data.code){ 
					for(var i=0;i<res.data.data.length;i++){
						that.options2.push({
							value:res.data.data[i].number,
							label:res.data.data[i].inspectItem
						})
					}
					// console.log(that.options2);
				}
			}, error => {
				console.log(error);
			})
		},
		inspectContentChange(val) {
			this.formData.inspectContent = val
			console.log(val);
			// if(this.formData.inspectContent.length>0){
			// 	this.isconfirm = false
			// }
		},
		//选择问题描述
		selectDescribe(){
			if (this.formData.inspectContent.length == 0 ) {
				uni.showToast({
					title: '请先选择检查内容!',
					icon: 'none'
				})
			} else {
				uni.navigateTo({
					url:`../SelectDescribe?formData=${JSON.stringify(this.formData)}&defectStr=''`
				})
			}
			
		},
		previewPhoto () { // 图片预览
			let url = this.photo.url;
			uni.previewImage({
				urls: [url]
			})
		},
		back() {
			uni.navigateBack({ delta: 1 });    // 返回上一页
		},
		// violationPicPage(){
		// 	// console.log(this.formData)
		// 	uni.navigateTo({
		// 		url:`ViolationContent?formData=${JSON.stringify(this.formData)}`
		// 	})
		// }
	} 
};
</script> 

<style lang="scss">
page {
	background-color: #f8f8f8;
}

.row {
	display: flex;
	align-items: center;
	position: relative;
	padding: 0 30upx;
	height: 90upx;
	background: #fff;

	.tit {
		flex-shrink: 0;
		// width: 120upx;
		font-size: 30upx;
	}

	.input {
		flex: 1;
		font-size: 30upx;
	}
}
.button {
    height: 50%;
    width: 30%;
    margin-top: 20px;
	font-size: 28rpx;
}
.spacebutoom {
	margin-bottom: 20px;
}

</style>
