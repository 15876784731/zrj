<template>
	<view>
		<TaskcheckHeader
		:cardlist="cardcontent"
		>
			
		</TaskcheckHeader>
		<TaskcheckFooter
		:cardlist="cardcontent"
		>
			
		</TaskcheckFooter>
	</view>
		
		
	
</template>

<script>
	import TaskcheckHeader from '@/components/uni-inspect/uni-inspect-check/Taskcheck-Header.vue'
	import TaskcheckFooter from '@/components/uni-inspect/uni-inspect-check/Taskcheck-Footer.vue'
	
	export default ({
		components:{
			TaskcheckHeader,
			TaskcheckFooter,
		},
		data() {
			return {
				cardcontent:{},
			}
		},
		onLoad(e) {
			this.cardcontent =JSON.parse(e.cardParam) 
			console.log(this.cardcontent)
		},
		mounted() {
			
		},
		methods:{
			
		}
	})
</script>

<style>
</style>
