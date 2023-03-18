<?php
	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	require("../../../../conn.php");
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	$data = array(
		"code"=>1,
		"msg"=>"",
		"data"=>[]
	);
	//生成时间戳
	function msectime() {
		list($msec, $sec) = explode(' ', microtime());
		return  (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
	}
	
	switch($flag){
		//获取违章项目
		case 'getViolationItem':
			$proTimeStamp = $_POST["proTimeStamp"];
			$sql1 = "SELECT * FROM tb_project WHERE timeStamp='$proTimeStamp'";
			$result1 = $conn->query($sql1);
			$row1 = $result1->fetch_assoc();
			$violationStandard = $row1["violationStandard"];
			$pattern = '/\d+/';
			if (preg_match_all($pattern, $violationStandard ,$arr)){
				$violationStandard = implode("",$arr[0]);
			}
			$numArr = $_POST["numArr"];
			$res = array();
			$resData = array();
            $i=0;
            $k=0;
			$data["numArr"]=$numArr;
			if ($numArr != "") {
				$numArr = explode(',',$numArr);
				for($j=0;$j<count($numArr);$j++){
					$val = $numArr[$j];
					if($val!=""){
						$sql2 = "SELECT * FROM `tb_system_database` WHERE `majorCategories`='一般问题' AND number='$val' AND depotId='$violationStandard'";
						$result2 = $conn->query($sql2);
						if($result2->num_rows>0){
							while($row2 = $result2->fetch_assoc()){
								$resData["id"] = $row2["id"];
								$resData["describe"] = $row2["describe"];
								$resData["majorCategories"] = $row2["majorCategories"];
								$resData["number"] = $row2["number"];
								$res["commonlyQuestion"][$i] = $resData;
								$i++;
							}
						}
						$sql3 = "SELECT * FROM `tb_system_database` WHERE `majorCategories`='重大问题' AND number='$numArr[$j]' AND depotId='$violationStandard'";
						$result3 = $conn->query($sql3);
						if($result3->num_rows>0){
							while($row3 = $result3->fetch_assoc()){
								$resData["id"] = $row3["id"];
								$resData["describe"] = $row3["describe"];
								$resData["majorCategories"] = $row3["majorCategories"];
								$resData["number"] = $row3["number"];
								$res["majorQuestion"][$k] = $resData;
								$k++;
							}
						}
						
					}
				}
			} else {
				$sql2 = "SELECT * FROM `tb_system_database` WHERE `majorCategories`='一般问题' AND depotId='$violationStandard'";
				$result2 = $conn->query($sql2);
				if($result2->num_rows>0){
					while($row2 = $result2->fetch_assoc()){
						$resData["id"] = $row2["id"];
						$resData["describe"] = $row2["describe"];
						$resData["majorCategories"] = $row2["majorCategories"];
						$resData["number"] = $row2["number"];
						$res["commonlyQuestion"][$i] = $resData;
						$i++;
					}
				}
				$sql3 = "SELECT * FROM `tb_system_database` WHERE `majorCategories`='重大问题'  AND depotId='$violationStandard'";
				$result3 = $conn->query($sql3);
				if($result3->num_rows>0){
					while($row3 = $result3->fetch_assoc()){
						$resData["id"] = $row3["id"];
						$resData["describe"] = $row3["describe"];
						$resData["majorCategories"] = $row3["majorCategories"];
						$resData["number"] = $row3["number"];
						$res["majorQuestion"][$k] = $resData;
						$k++;
					}
				}
			}
			if($i>0||$k>0){
				$data["data"]=$res;
			}else{
				$data["code"] = 0;
			}
			
		break;
		//获取搜索的违章项目
		case 'searchViolationItem':
			$proTimeStamp = $_POST["proTimeStamp"];
			$sql1 = "SELECT * FROM tb_project WHERE timeStamp='$proTimeStamp'";
			$result1 = $conn->query($sql1);
			$row1 = $result1->fetch_assoc();
			$violationStandard = $row1["violationStandard"];
			$pattern = '/\d+/';
			if (preg_match_all($pattern, $violationStandard ,$arr)){
				$violationStandard = implode("",$arr[0]);
			}
			$keyWorld = $_POST["keyWorld"];
			$numArr = $_POST["numArr"];
			$res = array();
			$resData = array();
            $i=0;
            $k=0;
			if ($numArr!='undefined') {
				$numArr = explode(',',$numArr);
				for($j=0;$j<count($numArr);$j++){
					$val = $numArr[$j];
					$sql2 = "SELECT * FROM `tb_system_database` WHERE `majorCategories`='一般问题' AND number='$val' AND depotId='$violationStandard' AND `describe` LIKE '%".$keyWorld."%'";
					$result2 = $conn->query($sql2);
					if($result2->num_rows>0){
						while($row2 = $result2->fetch_assoc()){
							$resData["id"] = $row2["id"];
							$resData["describe"] = $row2["describe"];
							$resData["majorCategories"] = $row2["majorCategories"];
							$resData["number"] = $row2["number"];
							$res["commonlyQuestion"][$i] = $resData;
							$i++;
						}
					}
					$sql3 = "SELECT * FROM `tb_system_database` WHERE `majorCategories`='重大问题' AND number='$numArr[$j]' AND depotId='$violationStandard' AND `describe` LIKE '%".$keyWorld."%'";
					$result3 = $conn->query($sql3);
					if($result3->num_rows>0){
						while($row3 = $result3->fetch_assoc()){
							$resData["id"] = $row3["id"];
							$resData["describe"] = $row3["describe"];
							$resData["majorCategories"] = $row3["majorCategories"];
							$resData["number"] = $row3["number"];
							$res["majorQuestion"][$k] = $resData;
							$k++;
						}
					}
				}
				
			}else {
				$sql2 = "SELECT * FROM `tb_system_database` WHERE `majorCategories`='一般问题' AND depotId='$violationStandard' AND `describe` LIKE '%".$keyWorld."%'";
				$result2 = $conn->query($sql2);
				if($result2->num_rows>0){
					while($row2 = $result2->fetch_assoc()){
						$resData["id"] = $row2["id"];
						$resData["describe"] = $row2["describe"];
						$resData["majorCategories"] = $row2["majorCategories"];
						$resData["number"] = $row2["number"];
						$res["commonlyQuestion"][$i] = $resData;
						$i++;
					}
				}
				$sql3 = "SELECT * FROM `tb_system_database` WHERE `majorCategories`='重大问题' AND depotId='$violationStandard' AND `describe` LIKE '%".$keyWorld."%'";
				$result3 = $conn->query($sql3);
				if($result3->num_rows>0){
					while($row3 = $result3->fetch_assoc()){
						$resData["id"] = $row3["id"];
						$resData["describe"] = $row3["describe"];
						$resData["majorCategories"] = $row3["majorCategories"];
						$resData["number"] = $row3["number"];
						$res["majorQuestion"][$k] = $resData;
						$k++;
					}
				}
			}
			if($i>0||$k>0){
				$data["data"]=$res;
			}else{
				$data["code"] = 0;
			}
		break;
		case 'addCheckedProblem':
			$projectId = isset($_POST["projectId"]) ? $_POST["projectId"] : '';
			$formData = isset($_POST["formData"]) ? $_POST["formData"] : '';
			$formData = json_decode($formData);
			$base64 = $formData->photoSrc;
			$category = $formData->category;
			$inspectItemHead = $formData->inspectItem;
			$inspectProcedureArr = $formData->inspectProcedure;
			$inspectProcedureStr = '';
			for ($i = 0; $i < count($inspectProcedureArr); $i++) {
				$inspectProcedureStr .= $inspectProcedureArr[$i].'||';
			}
			// 保存图片
			$img_path='../../../images/app_pic/inspectPic/ViolationPic/';
			$arr = [];
			$now_time = time();
			$now_date = date('Y-m-d H:i:s',$now_time);
			$end_time = date('Y-m-d H:i:s',strtotime("+3 day"));
			$base = $base64;
			$timeStamp = msectime();
			// $images_name = "";
			$images_name =  $timeStamp.'.'.'jpg';
			$base64 = explode(",",$base);
			$data1 = base64_decode($base64[1]);
			$path = $img_path.$images_name;
			file_put_contents($path, $data1);
			
			$checkedList = $formData->checkedList;
			$data["checkedList"] = $checkedList;
			for ($i=0; $i < count($checkedList); $i++) {
				$item = $checkedList[$i];
				$item = explode('||',$item);
				$property = $item[0];
				$describe = $item[1];
				$num = $item[2];
				// $sql = "INSERT INTO `tb_inspect_problem_photo` (`photoUrl`,`problemDecribe`,`problemNum`,`property`,`projectTimestamp`,`CardTimestamp`) VALUES ('$img_path','$describe','$num','$property','$proTimeStamp','$timeStamp')";
				$sql = "INSERT INTO `tb_inspectaccept_violationitem` SET `projectId`='$projectId',`noticeTimeStamp`='$timeStamp',`itemType`='$property',`violationContent`='$describe',`picFile`='$images_name',`uploadDate`='$now_date',`createTime`='$now_date',`endDate`='$end_time',`number`='$num',`category`='$category',`inspectItemHead`='$inspectItemHead',`inspectProcedure`='$inspectProcedureStr' ";
				$result = $conn->query($sql);
				$data['result'] = $result;
				if (!$result) {
					$data['code'] = 0;
				}
			}
		break;
		//新增违章条目
//		case 'addItem':
//			$projectInfo = isset($_POST["projectInfo"]) ? $_POST["projectInfo"] : '';
//			$violationItem = isset($_POST["violationItem"]) ? $_POST["violationItem"] : '';
//			$violationItem = explode(',', $violationItem);
//			
//			$time=getdate();
//			$createTime=$time['year']."-".$time['mon']."-".$time['mday']."/".$time['hours'].":".$time['minutes'].":".$time['seconds'];
//			$projectInfo = json_decode($projectInfo);
//			$projectId = $projectInfo->projectId;
//			$inspectCode = $projectInfo->inspectCode;
//			$projectName = $projectInfo->projectName;
//			$database = $projectInfo->database;
//			$timeStamp = $projectInfo->timeStamp;
//			echo $violationItem;
//			echo $projectInfo;
//			
//	//		print_r($violationItem);
//			
//			//循环插入通知单对应缺陷（违章条目）
////			for($i=0;$i<count($violationItem);$i++){
////				$sql = "INSERT INTO tb_weekly_scene_notice_defect SET `timeStamp` = '$timeStamp',`projectId` = '$projectId',`inspectCode` = '$inspectCode',`projectName` = '$projectName',`defect`='".$violationItem[$i]."',`object` = '$projectName',`database` = '$database',`createTime` = '$createTime',`defectState` = '0'";
////				$result = $conn -> query($sql);
////			}
//	//		print_r($ret_data);
//		break;
	}
	$json = json_encode($data);
	echo $json;
	$conn->close();	

?>