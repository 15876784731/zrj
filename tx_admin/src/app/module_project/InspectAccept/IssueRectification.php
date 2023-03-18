<?php
	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	require("../../../../conn.php");
//	error_reporting( E_ALL&~E_NOTICE );
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	$data = array(
		"code"=>1,
		"msg"=>"",
		"data"=>[]
	);
	date_default_timezone_set('PRC'); //东八时区 
	//生成时间戳
	function msectime() {
   		list($msec, $sec) = explode(' ', microtime());
   		return  (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
	}
	switch($flag){
		//查询日期内整改内容
		case 'datechange':
            $startTime = isset($_POST["firstTime"]) ? $_POST["firstTime"] : '';
			$endTime = isset($_POST["lastTime"]) ? $_POST["lastTime"] : '';
			$state = isset($_POST["status"]) ? $_POST["status"] : '';
			$projectId = isset($_POST["projectId"]) ? $_POST["projectId"] : '';
			if($state==''){
				$sql = "SELECT * FROM tb_inspectaccept_notice WHERE projectId = '$projectId' AND `isDelete` = '0' AND `inspectDate` >='$startTime' AND `inspectDate` <='$endTime' ";
			}else{
				if($startTime==''&&$endTime==''){
					$sql = "SELECT * FROM tb_inspectaccept_notice WHERE projectId = '$projectId' AND `isDelete` = '0' AND`state` ='$state' ";
				}else{
					$sql = "SELECT * FROM tb_inspectaccept_notice WHERE projectId = '$projectId' AND `isDelete` = '0' AND`state` ='$state' AND `inspectDate` >='$startTime' AND `inspectDate` <='$endTime'";
				}
			}
			$result = $conn->query($sql);
			if($result->num_rows>0){
				$res = array();
				$resData = array();
				$i=0;
				while($row=$result->fetch_assoc()){
					$resData['id'] = $row['id'];//id;
					$resData['noticeTimeStamp'] = $row['noticeTimeStamp'];//时间戳
					// $r esData['noticeNumber'] = $row['noticeNumber'];//通知单号
					// $resData['inspectLevel'] = $row['inspectLevel'];//层级
					$resData['inspectPosition'] = $row['inspectPosition'];//部位
					$resData['inspectDate'] = $row['inspectDate'];//检查日期
					$resData['endDate'] = $row['endDate'];//截止日期
					$resData['state'] = $row['state'];//状态
					// $resData['inspectPersonName'] = $row['inspectPersonName'];//检查人
					// $resData['inspectProcedure'] = $row['inspectProcedure'];//检查工序
					$resData['inspectItemHead'] = $row['inspectItemHead'];//检查项目
					$resData['inspectPointPic'] = $row['inspectPointPic'];//整改图片
					$resData['category'] = $row['category'];//工程类别
					// $resData['inspectProcedure'] = $row['inspectProcedure']//检查工序
					$res[$i] = $resData;
					$i++;
				}
				$data["data"]=$res;
			}else{
				$data["code"] = 0;
				$data["msg"] = "暂无内容！";
			}
		break;	
			
	}
	$json = json_encode($data);
	echo $json;
	$conn->close();	

?>