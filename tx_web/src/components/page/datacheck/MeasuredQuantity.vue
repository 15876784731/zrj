<template>
    <div id="ProTable" class="basic container">
        <div class="" style="flex:1;">
            <el-tabs v-model="activeName" @tab-click="handleClick">
                <el-tab-pane label="未完成" name="first">
                    <div class="handle-box">
                        <el-input placeholder="请输入内容" v-model="search_world" style="width: 200px;margin-left:10px ;"></el-input>
                    </div>
                    <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header"
                    :data="table1" stripe>
                        <el-table-column type="selection"></el-table-column>
                        <el-table-column prop="inspectPosition" label="标题名称" align="center"></el-table-column>
                        <el-table-column prop="inspectItem" label="检查项目" align="center"></el-table-column>
                        <el-table-column prop="inspectPerson" label="检查人员" align="center"></el-table-column>
                        <el-table-column prop="projectName" label="工程名称" align="center"></el-table-column>
                        <el-table-column prop="inspectDate" label="检查日期" align="center"></el-table-column>
                    </el-table>
                    <div class="pagination">
                        <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                        :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData1.length">
                        </el-pagination>
                    </div>
                </el-tab-pane>
                <el-tab-pane label="已完成" name="second">
                    <div class="handle-box">
                        <el-input placeholder="请输入内容" v-model="search_world" style="width: 200px;margin-left:10px ;"></el-input>
                    </div>
                    <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header"
                    :data="table2" stripe>
                        <el-table-column type="selection"></el-table-column>
                        <el-table-column prop="inspectPosition" label="标题名称" align="center"></el-table-column>
                        <el-table-column prop="inspectItem" label="检查项目" align="center"></el-table-column>
                        <el-table-column prop="inspectPerson" label="检查人员" align="center"></el-table-column>
                        <el-table-column prop="projectName" label="工程名称" align="center"></el-table-column>
                        <el-table-column prop="inspectDate" label="检查日期" align="center"></el-table-column>
                    </el-table>
                    <div class="pagination">
                        <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                        :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData2.length">
                        </el-pagination>
                    </div>    
                </el-tab-pane>
            </el-tabs>
        </div>
    </div>
		<!-- 编辑弹出框 -->
		<!-- <EditPro :dialogEdit="dialogEdit" :form="form" :type="type"></EditPro> -->
		<!-- 批量导入 -->


	
</template>
<script>
	import AddPro from "@/components/dialog/project/AddPro"
	// import EditPro from "../../dialog/index/EditPro"
	export default {
		name: 'MeasuredQuantity',
		components: {
			// EditPro,
			AddPro,
		},
		data() {
			return {
                activeName: 'first',
				loading: false,
				type: 'edit',
				currentPage: 1, //初始页
				pagesize: 10, //每页的数据
				query: {
					address: '',
					name: '',
					pageIndex: 1,
					pageSize: 10
				},
				search_world: '',
				// dialogEdit: {
				// 	show: false
				// },
			
                tableData1: [],
                tableData2: [],
				form: {},
				isLoading:true
			};
		},
		computed: {
			//过滤筛选
			table1() {
				const search_world = this.search_world
				if (search_world) {
					var filterData =  this.tableData1.filter(data => {
						return Object.keys(data).some(key => { 
							return String(data[key]).toLowerCase().indexOf(search_world) > -1 
						})
					})
					this.total = filterData.length
					return filterData.slice((this.currentPage - 1)*this.pagesize, this.currentPage*this.pagesize)
				}
				this.total = this.tableData1.length
				return this.tableData1.slice((this.currentPage - 1)*this.pagesize, this.currentPage*this.pagesize)
            },
            table2() {
				const search_world = this.search_world
				if (search_world) {
					var filterData =  this.tableData2.filter(data => {
						return Object.keys(data).some(key => { 
							return String(data[key]).toLowerCase().indexOf(search_world) > -1 
						})
					})
					this.total = filterData.length
					return filterData.slice((this.currentPage - 1)*this.pagesize, this.currentPage*this.pagesize)
				}
				this.total = this.tableData2.length
				return this.tableData2.slice((this.currentPage - 1)*this.pagesize, this.currentPage*this.pagesize)
			},
		},
		mounted() {
            this.Unfinished();
            this.Completed()
		},
		methods: {
            handleClick(tab, event) {
                console.log(tab, event);
            },
            //获取未完成实测实量表
			Unfinished(isLoading) {
				const that = this
				let fd = new FormData()
				fd.append('flag', 'MeasuredQuantity1')
				// console.log(this.$loading())
				
				that.$axios.post(that.$adminUrl + `/project/DetailedInspectionv/projectMeasured .php`, fd).then(res => {
					console.log(res.data.data)
					that.tableData1 = res.data.data
					if(that.isLoading){
						const loading = that.$loading({
							lock: true,
							text: 'Loading',
							target: document.querySelector('#ProTable'),
							spinner: 'el-icon-loading'
						});
						loading.close();
					}
				}).catch(() => {
					console.error("获取失败")
				})
            },
            //获取已完成实测实量表
			Completed(isLoading) {
				const that = this
				let fd = new FormData()
				fd.append('flag', 'MeasuredQuantity2')
				// console.log(this.$loading())
				
				that.$axios.post(that.$adminUrl + `/project/DetailedInspectionv/projectMeasured .php`, fd).then(res => {
					console.log(res.data.data)
					that.tableData2 = res.data.data
					if(that.isLoading){
						const loading = that.$loading({
							lock: true,
							text: 'Loading',
							target: document.querySelector('#ProTable'),
							spinner: 'el-icon-loading'
						});
						loading.close();
					}
				}).catch(() => {
					console.error("获取失败")
				})
			},
            // 初始页currentPage、初始每页数据数pagesize和数据data
			handleSizeChange: function(size) {
				this.pagesize = size;
			},
			handleCurrentChange: function(currentPage) {
				this.currentPage = currentPage;
			},
		},
	};
</script>
<style scoped>
	.handle-box {
		margin-bottom: 20px;
	}

	.handle-select {
		width: 120px;
	}

	.handle-input {
		width: 300px;
		display: inline-block;
	}

	.table {
		width: 100%;
		font-size: 14px;
	}

	.red {
		color: #ff0000;
	}

	.green {
		color: #67c23a;
	}

	.mr10 {
		margin-right: 10px;
	}

	.table-td-thumb {
		display: block;
		margin: auto;
		width: 40px;
		height: 40px;
	}
</style>
