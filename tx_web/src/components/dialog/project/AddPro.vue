<template>
	<div class="AddPro">
		<el-dialog title="新建工程" :modal-append-to-body="false" customClass="customClass" :visible.sync="dialogAdd.show">
			<el-form :model="formDate" ref="dialogAdd" label-width="100px" :rules="formrules">
				<el-form-item label="工程名称" prop="projectName" class="px-2">
					<el-input v-model="formDate.projectName"></el-input>
				</el-form-item>
				<div class="flex">
					<el-form-item label="工程类别" prop="categories" class="flex-1 px-2">
						<!-- <el-input v-model="formDate.categories"></el-input> -->
						<el-select v-model="formDate.categoryIndex" placeholder="请选择" style="width: 100%;">
							<el-option v-for="(item,index) in form.categoryList" :key="item.value" :label="item.label" :value="index">
							</el-option>
						</el-select>
					</el-form-item>
					<el-form-item label="所属公司" prop="company" class="flex-1 px-2">
						<!-- <el-input v-model="formDate.company"></el-input> -->
						<el-select v-model="formDate.optionsIndex" placeholder="请选择" style="width: 100%;">
							<el-option v-for="(item,index) in form.options" :key="item.value" :label="item.label" :value="index">
							</el-option>
						</el-select>
					</el-form-item>
				</div>
				<div class="flex" >
					<el-form-item label="地区省" prop="province" class="flex-1 px-2">
						<el-select v-model="formDate.province" @change="provinceChange" placeholder="省" >
							<el-option v-for="it in province" :key="it" :label="it.label" :value="it"></el-option>
						</el-select>
					</el-form-item>
					<el-form-item label="地区市" prop="city" class="flex-1 px-2">
						<el-select v-model="formDate.city" @change="cityChange" placeholder="市" class="address_select">
							<el-option v-for="it in cityList" :key="it" :label="it.label" :value="it"></el-option>
						</el-select>
					</el-form-item>
					<!-- <el-select v-model="formData.areaCode" @change="areaChange" placeholder="区" class="address_select">
						<el-option v-for="it in areaList" :key="it.value" :label="it.label" :value="it.value"></el-option>
					</el-select> -->

					<!-- <el-form-item label="地区省" prop="province" class="flex-1 px-2">
						<el-input v-model="formDate.province"></el-input>
					</el-form-item>
					<el-form-item label="地区市" prop="city" class="flex-1 px-2">
						<el-input v-model="formDate.city"></el-input>
					</el-form-item> -->
				</div>
				<div class="flex">
					<el-form-item label="工程位置" prop="proPosition" class="flex-1 px-2">
						<el-input v-model="formDate.proPosition"></el-input>
					</el-form-item>
					<el-form-item label="建筑面积(㎡)" prop="architecture" class="flex-1 px-2">
						<el-input v-model="formDate.architecture"></el-input>
					</el-form-item>
				</div>
				<div class="flex">
					<el-form-item label="开工日期" prop="startTime" class="flex-1 px-2">
						<!-- <el-input v-model="formDate.startTime"></el-input> -->
						<el-date-picker v-model="formDate.startTime" type="datetime" style="width: 100%;" placeholder="选择日期">
						</el-date-picker>
					</el-form-item>
					<el-form-item label="竣工日期" prop="completedTime" class="flex-1 px-2">
						<!-- <el-input v-model="formDate.completedTime"></el-input> -->
						<el-date-picker v-model="formDate.completedTime" type="datetime" style="width: 100%;" placeholder="选择日期">
						</el-date-picker>
					</el-form-item>
				</div>
			</el-form>
			<div slot="footer" class="dialog-footer">
				<el-button @click="dialogFormClose('dialogAdd')">取 消</el-button>
				<el-button type="primary" @click="dialogFormAdd()">确 定</el-button>
			</div>
		</el-dialog>
	</div>
</template>

<script>
import json from "@/../address.json";
	export default {
		name: 'AddUser',
		props: {
			dialogAdd: Object,
			form: Object
		},
		data() {
			return {
				provinces: json, // 省数据（一个省中包含市的信息，一个市中包含区的信息）
				province: [], //省
				cityList: [], // 某省下市的列表数据
				chooseProvince: [],
				formData: {},
				formDate: {
					projectName: '',
					categories: '',
					company: '',
					province: '',
					city: '',
					proPosition: '',
					architecture: '',
					startTime: '',
					completedTime: '',
					optionsIndex: '',
					categoryIndex:''
				},
				categoryList: [],
				formrules: {
					// username: [{ required: true, message: '班级信息不能为空', trigger: 'blur' }]
				}
			};
		},
		mounted() {
			for(let i = 0;i<this.provinces.provinces.length;i++) {
				this.province.push(this.provinces.provinces[i].name);
			}
			console.log(this.provinces)
		},
		methods: {

			// 操作：选择省，把省下的市的数据筛出来
			provinceChange(event) {
				console.log(event);

				// this.chooseProvince = this.provinces.provinces.find(it => {
				// 	if (it.value === event) {
				// 		return it;
				// 	}
				// });
				// console.log(this.chooseProvince);
				// this.$set(this.formData, "province", chooseProvince.label);
				// this.cityList = chooseProvince.children;
				for(let i = 0;i<this.provinces.provinces.length;i++) {
					if(event == this.provinces.provinces[i].name) {
						this.cityList = [];
						for(let a = 0;a<this.provinces.provinces[i].children.length;a++) {
							
							this.cityList.push(this.provinces.provinces[i].children[a].name);
						}
					}
					// this.cityList.push(this.provinces.provinces[i].children.name);
				}
				console.log(this.cityList)
				this.$set(this.formData, "cityCode", "");
				this.$set(this.formData, "city", "");
				this.$set(this.formData, "areaCode", "");
				this.$set(this.formData, "area", "");
			},
			// 操作：选择市，把市下的区的数据筛出来
			cityChange(event) {
				let chooseCity = this.cityList.find(it => {
					if (it.value === event) {
					return it;
					}
				});
				// this.$set(this.formData, "city", chooseCity.label);
				// // this.areaList = chooseCity.children;
				// this.$set(this.formData, "areaCode", "");
				// this.$set(this.formData, "area", "");
			},
			// 操作：选择区
			// areaChange(event) {
			// 	let chooseArea = this.areaList.find(it => {
			// 		if (it.value === event) {
			// 			return it;
			// 		}
			// 	});
			// 	this.$set(this.formData, "area", chooseArea.label);
			// 	console.log(this.formData);
			// },

			//模态框的新增
			dialogFormAdd() {
				const that = this
				var fd = new FormData();
				let indexOf1 = that.formDate.categoryIndex
				let indexOf2 = that.formDate.optionsIndex
				fd.append('flag', 'addProject');
				fd.append('projectName', that.formDate.projectName);
				fd.append('categories', that.form.categoryList[indexOf1].label);
				fd.append('company', that.form.options[indexOf2].label);
				fd.append('companyId', that.form.options[indexOf2].companyId);
				fd.append('province', that.formDate.province);
				fd.append('city', that.formDate.city);
				fd.append('proPosition', that.formDate.proPosition);
				fd.append('architecture', that.formDate.architecture);
				fd.append('startTime', that.commonFunc.dateFormat(that.formDate.startTime));
				fd.append('completedTime', that.commonFunc.dateFormat(that.formDate.completedTime));
				fd.append('timeStamp', new Date().getTime());
				that.$refs.dialogAdd.validate((valid) => {
					if (valid) {
						that.$axios.post(that.$adminUrl + `/project/index.php`, fd).then((res) => {
							// console.log(res.data.states)
							if (res.data.states == 'success') {
								that.dialogAdd.show = false
								that.$emit('update')
								that.FormTabelEmpty();
							} else {

							}
						}).catch(() => {

						})
					} else {
						console.log('error submit!!');
					}
				});
			},
			
			//关闭模态框
			dialogFormClose(dialogAdd) {
				this.FormTabelEmpty();
				this.dialogAdd.show = false;
			},
			//清空表单
			FormTabelEmpty() {
				for (var i in this.formDate) {
					this.formDate[i] = '';
				}
				this.formDate.rge_time = this.commonFunc.getNowDate();
			},
		}
	};
</script>
<style>
	.customClass {
		width: 40%;
	}
</style>
