<template>
	<view class="content">
		<view class="uni-list">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">标题名称：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.titleName" /></view>
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
		</view>
		<view class="uni-list mt-1">
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
				<view class="uni-list-cell-left">检查日期：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.inspectDate" /></view>
			</view>
		</view> 
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list">
					<view class="uni-list-cell">
						<view class="uni-list-cell-left">
							分包单位：
						</view>
						<view class="uni-list-cell-db">
							<picker @change="subcontractChange" :value="subIndex" :range="subcontractorArr" range-key="subcontractor">
								<view class="uni-input">{{subcontractorArr[subIndex].subcontractor}}</view>
							</picker>
						</view>
					</view>
				</view>
			</view>
		</view>
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">检查部位：</view>
				<view class="uni-list-cell-db">
						<view class="uni-list" @tap="handleTapopen">
							<view class="item-content">
								<view class="item-value">
									<!-- <text class="item-label">{{ label2 }}</text> -->
									<view v-if="!label1" class="uni-input" style="color:grey;">请选择检查部位</view>
									<view v-if="label1" class="uni-input">{{label1}}</view>
								</view>
							</view>
						</view>
					</view>
				</view>
				<view v-if="checkvisiable">
					<view class="uni-list-cell">
						<view class="uni-list-cell-left">区段栋号：</view>
						<view class="uni-list-cell-db">
							<view class="uni-list" @tap="handleTap('picker2')">
								<view class="item-content">
									<view class="item-value">
										<!-- <text class="item-label">{{ label2 }}</text> -->
										<view v-if="visiableform1" class="uni-input" style="color:grey;">请选择区段栋号</view>
										<view v-if="label2" class="uni-input">{{label2}}</view>
									</view>
								</view>
								<lb-picker ref="picker2" v-model="value2" mode="multiSelector" :list="list2" :level="2" :dataset="{ name: 'label2' }"
								 :formatter="formatter" @change="handleChange2" @confirm="handleConfirm2" @cancel="handleCancel2">
								</lb-picker>
							</view>
						</view>
					</view>
					<view class="uni-list-cell">
						<view class="uni-list-cell-left">楼层单元：</view>
						<view class="uni-list-cell-db">
							<view class="uni-list" @tap="handleTap('picker3')">
								<view class="item-content">
									<view class="item-value">
										<!-- <text class="item-label">{{ label2 }}</text> -->
										<view v-if="visiableform2" class="uni-input" style="color:grey;">请选择楼层单元</view>
										<view v-if="label3" class="uni-input">{{label3}}</view>
									</view>
								</view>
								<lb-picker ref="picker3" v-model="value3" mode="multiSelector" :list="list3" :level="2" :dataset="{ name: 'label3' }"
								 :formatter="formatter" @change="handleChange3" @confirm="handleConfirm3" @cancel="handleCancel3">
								</lb-picker>
							</view>
						</view>
					</view>
				</view>
			</view>
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">检查人员：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.inspectPerson" /></view>
			</view>
		</view>
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">施工班组：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.constructionTeam" /></view>
			</view>
		</view>
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">组长名称：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.leaderName" /></view>
			</view>
		</view>
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">联系方式：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.contactMode" /></view>
			</view>
		</view>
		<pick-date class="mt-1" datename="施工日期" @date="getConstrunctionDate"></pick-date>
		<button :disabled="isconfirm" type="primary" class="flex align-center justify-center button" @click="confirm()">确定</button>
	</view>
</template>

<script>
import pickdate from '@/components/common/pick-date/pick-date.vue'; //日期选择器
import LbPicker from '../../../components/common/lb-picker'
export default {
	props:{},
	components: {
		'pick-date': pickdate,
		LbPicker,
	},
	data() {
		return {
			isconfirm:false,
			formData: {
				titleName:'',//标题名称
				projectType: '',//工程类别
				inspectDate: '',//检查日期
				inspectPosition:'',//检查部位
				inspectPerson:'',//检查人员
				constructionTeam:'',//施工班组
				leaderName:'',//组长名称
				contactMode:'',//联系方式
				construnctionDate:''
			},
			categoryArr: ['建筑工程', '装修工程'],
			index: 0,
			// inspectProArr: [{id:'',inspectItem:''}],
			inspectProArr: [],
			itemIndex: 0,
			currentData:[],
			buildSelData: {},
			projectInfo: {},
			subcontractorArr: [{id:'',subcontractor:''}],
			subIndex: 0,
			checkvisiable:false,
			storagedata:[],
			value2: [],
			label1:'',
			label2: '',
			list2: [],
			value3: [],
			label3: '',
			list3: [],
			visiableform1:true,
			visiableform2:true,
		};
	}, 
	onLoad:function(option) {
		this.currentData = JSON.parse(option.currentData)
		console.log(this.currentData)
	},
	mounted() {
		let that = this
		this.getStoragedata()
		setTimeout(function(){
			let data = that.categoryArr[that.index]
			that.getNewCardDataFunc(data)
			that.getSubcontractorFunc()
		},1000)
		
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
		getInsDate(val){
			this.formData.inspectDate = val
		},
		//获取施工日期
		getConstrunctionDate(val){
			this.formData.construnctionDate = val
		},
		proCategoryChange(e) {
			this.index = e.target.value
			var proItem = this.categoryArr[this.index]
			this.getNewCardDataFunc(proItem)
		},
		inspectItemChange(e) {
			this.itemIndex = e.target.value
			console.log(this.itemIndex);
		},
		getNewCardDataFunc(data) {
			this.formData.titleName = this.buildSelData.build +'-'+ this.buildSelData.floor +'-'+ this.buildSelData.unit
			let proTimeStamp = this.projectInfo.proTimeStamp
			this.formData.inspectPosition = this.buildSelData.build + this.buildSelData.floor + this.buildSelData.unit
			this.formData.inspectDate = this.commonFunc.getNowDate()
			this.formData.inspectPerson = this.currentData.username
			this.formData.contactMode = this.currentData.phone
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
				// console.log(res.data)
				uni.hideLoading()//隐藏加载中转圈圈
				this.isloading = false//取消遮罩层
				console.log(res.data)
				if(res.data.code){
					this.inspectProArr = res.data.data
					// this.createFloorFunc(res.data.data)
				}
			}, error => {
				console.log(error);
			})
		},
		//获取本地缓存
		getStoragedata() {
			let that = this
			uni.getStorage({ 
				key: 'buildInfo',
				success: function (res) {
					that.buildSelData = res.data
				}
			});
			uni.getStorage({
				key: 'changeProRecord',
				success: function (res) {
					that.projectInfo = res.data
					console.log(res.data)
				}
			});
			uni.getStorage({
				key:'temporarycache',
				success:function(res){
					that.storagedata = res.data
					console.log(res.data)
					that.getBuildInfo()
				}
			})
		},
		//获取分包单位函数
		getSubcontractorFunc() {
			let proTimeStamp = this.projectInfo.proTimeStamp
			let opts = {
				url: this.api+'/module_project/InspectAccept/InspectAccept.php',
				method: 'POST'
			}
			let param = {
				flag: 'getSubcontractor',
				proTimeStamp:proTimeStamp
			}
			this.myRequest.httpRequest (opts, param).then(res => {
				console.log(res.data)
				if(res.data.code){
					this.subcontractorArr = res.data.data
				}
			}, error => {
				console.log(error);
			})
		},
		subcontractChange(e) {
			this.subIndex = e.target.value
		},
		confirm() {
			
			let projectType = this.categoryArr[this.index]
			let inspectItem = this.inspectProArr[this.itemIndex]
			let substractor = this.subcontractorArr[this.subIndex].subcontractor
			let proTimeStamp = this.projectInfo.proTimeStamp
			let buildInfo = JSON.stringify(this.buildSelData)
			let inputValue = JSON.stringify(this.formData)
			let projectName = this.projectInfo.projectName
			let opts = {
				url: this.api+'/module_project/InspectAccept/InspectAccept.php',
				method: 'POST'
			}
			let param = {
				flag: 'addTaskCard',
				inputValue: inputValue,
				projectType: projectType,
				inspectItem: inspectItem,
				proTimeStamp: proTimeStamp,
				buildInfo: buildInfo,
				createUserName: this.currentData.username,
				createUserId: this.currentData.userId,
				substractor: substractor,
				projectName: projectName
			}
			let isLoading = true//是否需要加载动画
			this.myRequest.httpRequest (opts, param,isLoading).then(res => {
				console.log(res.data)
				uni.hideLoading()//隐藏加载中转圈圈
				this.isloading = false//取消遮罩层
				// console.log(res.data)
				if(res.data.code){
					uni.showToast({
					    title: '新增卡片成功！',
					    duration: 2000,
						icon: "none"
					});
					setTimeout(() => {
						uni.navigateBack({ delta: 1 });    // 返回上一页
					}, 1000)
				}else{
					uni.showToast({
					    title: '新增卡片失败！',
					    duration: 2000,
						icon: "none"
					});
				}
			}, error => {
				console.log(error);
			})
		},
		//打开检查部位隐藏折叠面板
		handleTapopen(){
			this.checkvisiable = !this.checkvisiable
		},
		
		// closetap(){
		// 	this.checkvisiable = false
		// },
		handleChange2(e){
			
		},
		handleConfirm2(e){
				
				this.label2 = e.value[0] + "-" + e.value[1]
				this.label1 = this.label2
				console.log(this.label2)
				if(this.label2!=''){
					this.visiableform1 = false
				}
				//根据栋号异步获取楼层单元
				let opts = {
					url: this.api + '/module_project/SelectBuildInfo.php',
					method: 'POST'
				}
				let param = {
					flag: 'getFloorUnit',
					proTimeStamp: this.storagedata.proTimeStamp,
					build: e.value[1],
				}
				let isLoading = false //是否需要加载动画       
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					console.log(res)
					let unitName = res.data.unitName[0].split('||')
					let undergroundNumber = res.data.undergroundNumber
					let abovegroundNumber = res.data.abovegroundNumber 
					// console.log(undergroundNumber+" "+abovegroundNumber+" "+unitName.split('||'))
					let floorArr = this.commonFunc.toChinesNum_floor(undergroundNumber, abovegroundNumber)
					let unitArr = unitName
					let child_arry = [];
					let list3 = [];
					for (let i = 0; i < floorArr.length; i++) {
						child_arry = []
						for (let j = 0; j < unitArr.length; j++) {
							child_arry.push({
								value: unitArr[j],
								label: unitArr[j]
							})
						}
						// list3 = [];
						list3.push({
							value: floorArr[i],
							label: floorArr[i],
							children: child_arry,
						})
					}
					console.log(list3)
					this.list3 = list3;
				}, error => {
					console.log(error);
				})
			
		},
		handleCancel2(e){
			this.label2 = '';
			this.value2 = [];
			this.visiableform1 = true
			this.label1 = ''
			this.label3 = ''
			this.visiableform2 = true
			console.log('cancel::', e)
		},
		handleChange3(e){
			
		},
		handleConfirm3(e){
				this.label3 = e.value[0] + "-" + e.value[1]
				this.label1 = this.label1 +'-' + this.label3
				console.log('change::', e.value)
				if(this.label3!=''){
					this.visiableform2 = false
				}
				this.checkvisiable = false
		},
		handleCancel3(e){
			this.label3 = '';
			this.value3 = [];
			this.visiableform2 = true
			this.label1 = this.label2
			console.log('cancel::', e)
		},
		getBuildInfo(){
			
				let opts = {
					url: this.api + '/module_project/SelectBuildInfo.php',
					method: 'POST'
				}
				let param = {
					flag: 'getSectionBuild',
					proTimeStamp: this.storagedata.proTimeStamp
				}
				let isLoading = false //是否需要加载动画       
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					console.log(res)
					let sectionArr = res.data.section
					let buildArr = res.data.build
					let child_arry = []
					for (let i = 0; i < sectionArr.length; i++) {
						child_arry = []
						for (let j = 0; j < buildArr[i].split(',').length; j++) {
							child_arry.push({
								value: buildArr[i].split(',')[j],
								label: buildArr[i].split(',')[j],
							})
						}
						this.list2.push({
							value: sectionArr[i],
							label: sectionArr[i],
							children: child_arry
						})
					}
				}, error => {
					console.log(error);
				})
			
		},
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

</style>
