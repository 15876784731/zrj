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
		//保存通知单以及图片
		case 'saveViolationPic':
			$formData = isset($_POST["formData"]) ? $_POST["formData"] : '';
			$formData = json_decode($formData);
			$projectId = isset($_POST["projectId"]) ? $_POST["projectId"] : '';
			$number = isset($_POST["number"]) ? $_POST["number"] : '';
			$timeStamp = msectime();
			$inspectLevel = $formData->inspectLevel;
			$noticeNumber = $formData->noticeNumber;
			$category = $formData->category;
			$inspectItem = $formData->inspectItem;
			$inspectPosition = $formData->inspectPosition;
			$inspectProcedure = $formData->inspectProcedure;
			$inspectProcedure = implode("||",$inspectProcedure);
			$inspectContent = $formData->inspectContent;
			$inspectPerson = $formData->inspectPerson;
			$leaderName = $formData->leaderName;
			$leaderPhone = $formData->leaderPhone;
			$inspectDate = $formData->inspectDate;
			$endDate = $formData->endDate;
			$violationItem = $formData->violationItem;
			$photoSrc = $formData->photoSrc;
			// 保存图片
			$img_path='../../../images/app_pic/inspectPic/ViolationPic/';
			$arr = [];
			$now_time = time();
			$now_date = date('Y-m-d H:i:s',$now_time);
			$end_time = date('Y-m-d H:i:s',strtotime("+3 day"));
			$base = $photoSrc;
			$images_name = "";
			$images_name =  $timeStamp.'.'.'jpg';
			$base64 = explode(",",$base);
			$data1 = base64_decode($base64[1]);
			file_put_contents($img_path.$images_name, $data1);
			
			$sql1 = "INSERT INTO `tb_inspectaccept_notice` SET `leaderName`='$leaderName', `contactMode`='$leaderPhone', `projectId`='$projectId',`measureId`='',`noticeTimeStamp`='$timeStamp',`noticeNumber`='$noticeNumber',`inspectLevel`='$inspectLevel',`inspectPosition`='$inspectPosition',`state`='待下发',`inspectPersonName`='$inspectPerson',`inspectProcedure`='$inspectProcedure',`inspectItemHead`='$inspectItem',`isDelete`='0',`isRead`='0',`number`='$number', `inspectPointPic` = '$images_name',`category` = '$category', `inspectDate` = '$now_date',`endDate` = '$end_time'";
			$result1 = $conn->query($sql1);
			// $data['sql1'] = $sql1;
			if ($result1) {
				if(count($violationItem)>0){
					$i=0;
					foreach($violationItem as $key=>$val){
						$arr = explode("||",$val);
						// $data['arr'] = $arr;
						$type = $arr[0]; // 问题类型
						$itemDescribe = $arr[1]; // 问题描述
						$number = $arr[2]; // 问题编号
						
						$sql2 = "INSERT INTO `tb_inspectaccept_violationitem` SET `projectId`='$projectId',`noticeTimeStamp`='$timeStamp',`itemType`='$type',`violationContent`='$itemDescribe',`picFile`='$images_name',`uploadDate`='$now_date',`createTime`='$now_date',`endDate`='$end_time',`number`='$number',`buildInfo`='$inspectPosition'";
						// $data['sql2'] = $sql2;
						$i++;
						$result2 = $conn->query($sql2);
						if (!$result2) {
							$data['code'] = 0;
						}
					}
				}
			} else {
				$data['code'] = 0;
			}
			// $formData = isset($_POST["formData"]) ? $_POST["formData"] : '';
			// $buildInfo = isset($_POST["buildInfo"]) ? $_POST["buildInfo"] : '';
			// $projectId = isset($_POST["projectId"]) ? $_POST["projectId"] : '';
			// $measureId = isset($_POST["measureId"]) ? $_POST["measureId"] : '';
			// $number = isset($_POST["number"]) ? $_POST["number"] : '';
			// $formData = json_decode($formData);
			// $buildInfo = json_decode($buildInfo);
			// $timeStamp = msectime();
			// $noticeNumber = $formData->noticeNumber;
			// $inspectLevel = $formData->inspectLevel;
			// $inspectItem = $formData->inspectItem;
			// $inspectPosition = $formData->inspectPosition;
			// $inspectPerson = $formData->inspectPerson;
			// $inspectDate = $formData->inspectDate;
			// $endDate = $formData->endDate;
			// $inspectProcedure = $formData->inspectProcedure; 
			// $itemList = $formData->itemList;
			// $inspectProcedure = implode("||",$inspectProcedure);
			
			// $sql1 = "INSERT INTO `tb_inspectaccept_notice` SET `projectId`='$projectId',`measureId`='$measureId',`noticeTimeStamp`='$timeStamp',`noticeNumber`='$noticeNumber',`inspectLevel`='$inspectLevel',`inspectPosition`='$inspectPosition',`inspectDate`='$inspectDate',`endDate`='$endDate',`state`='草稿',`inspectPersonName`='$inspectPerson',`inspectProcedure`='$inspectProcedure',`inspectItemHead`='$inspectItem',`isDelete`='0',`isRead`='0',`number`='$number'";
			// $result1 = $conn->query($sql1);
			// if($result1){
			// 	if(count($itemList)>0){
			// 		$img_path='../../../images/app_pic/inspectPic/';
					
			// 		$arr = [];
			// 		$now_time = time();
			// 		$now_date = date('Y-m-d H:i:s',$now_time);
			// 		$end_time = date('Y-m-d H:i:s',strtotime("+3 day"));
			// 		$buildInfo = $buildInfo->section . "||" . $buildInfo->build;
			// 		$i=0;
			// 		foreach($itemList as $key=>$val){
			// 			$base = $val->picPath;
			// 			$images_name = "";
			// 			$images_name =  $timeStamp.$i.'.'.'jpg';
			// 			$base64 = explode(",",$base);
			// 			$data1 = base64_decode($base64[1]);
			// 			file_put_contents($img_path.$images_name, $data1);
			// 			$type = $val->title;
			// 			$number = $val->number;
			// 			$itemDescribe = $val->itemDescribe;
			// 			$sql2 = "INSERT INTO `tb_inspectaccept_violationitem` SET `projectId`='$projectId',`noticeTimeStamp`='$timeStamp',`itemType`='$type',`violationContent`='$itemDescribe',`picFile`='$images_name',`uploadDate`='$now_date',`createTime`='$now_date',`endDate`='$end_time',`number`='$number',`buildInfo`='$buildInfo'";
			// 			$data['sql2'] = $sql2;
			// 			$i++;
			// 		    $result2 = $conn->query($sql2);
			// 		}
			// 	}
			// }else{
			// 	$data["code"] = 0;
			// }
			
		break;
		//修改通知单以及图片
		case 'alterViolationPic':
			$formData = isset($_POST["formData"]) ? $_POST["formData"] : '';
			$measureId = isset($_POST["measureId"]) ? $_POST["measureId"] : '';
			$inspectCardParam = isset($_POST["inspectCardParam"]) ? $_POST["inspectCardParam"] : '';
			$formData = json_decode($formData);
			$timeStamp = msectime();
			$inspectLevel = $formData->inspectLevel;
			$inspectItem = $formData->inspectItem;
			$inspectPosition = $formData->inspectPosition;
			$inspectPerson = $formData->inspectPerson;
			$inspectDate = $formData->inspectDate;
			$endDate = $formData->endDate;
			$inspectProcedure = $formData->inspectProcedure;
			$itemList = $formData->itemList;
			
			$sql1 = "UPDATE `tb_inspectaccept_notice` SET `inspectLevel`='$inspectLevel',`inspectPosition`='$inspectPosition',`inspectDate`='$inspectDate',`endDate`='$endDate',`inspectPersonName`='$inspectPerson',`inspectProcedure`='$inspectProcedure',`inspectItemHead`='$inspectItem',`isDelete`='0',`isRead`='0' WHERE id='$inspectCardParam'";
			$result1 = $conn->query($sql1);
			if($result1){
//				if(count($itemList)>0){
//					$img_path='../../../images/app_pic/inspectPic/';
//					
//					$arr = [];
//					$now_time= time();
//					$now_date= date('Y-m-d H:i:s',$now_time);
//					$i=0;
//					foreach($itemList as $key=>$val){
//						$base = $val->picPath;
//						$images_name = "";
//						$images_name =  $timeStamp.$i.'.'.'jpg';
//						$base64 = explode(",",$base);
//						$data1 = base64_decode($base64[1]);
//						file_put_contents($img_path.$images_name, $data1);
//						$type = $val->title;
//						$number = $val->number;
//						$itemDescribe = $val->itemDescribe;
//						$sql2 = "INSERT INTO `tb_inspectaccept_violationitem` SET `projectId`='$projectId',`noticeTimeStamp`='$timeStamp',`itemType`='$type',`violationContent`='$itemDescribe',`picFile`='$images_name',`uploadDate`='$now_date',`createTime`='$now_date',`number`='$number'";
//						$i++;
//					    $result2 = $conn->query($sql2);
//					}
//				}
			}else{
				$data["code"] = 0;
			}
			
		break;

		//获取下发整改页面卡片信息
		case 'getViolationPic':
			$projectId = isset($_POST["projectId"]) ? $_POST["projectId"] : '';
			// echo __LINE__;
			$sql1 = "SELECT * FROM tb_inspectaccept_notice WHERE `projectId` = '$projectId' AND `state` != '已通过' AND `state` != '已关闭'";
			// $sql1 = "SELECT * FROM tb_inspectaccept_notice WHERE `projectId` = '$projectId' AND `state` = '草稿'";
			// echo $sql1;
			$result1 = $conn -> query($sql1);
			// die();//断点
			$res = array();
			$resData = array();
			$i=0;
			if($result1->num_rows>0){
				while($row = $result1->fetch_assoc()){
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
				$data['data'] = $res;
			}else{
				$data['code'] = 0;
			}
		break;

		//将检查内容保存到另一张表用于下发整改查看详情下方的信息展示栏
		case 'savecontent':
			$projectId = isset($_POST['projectId']) ? $_POST['projectId'] : '';//整改内容所属楼栋id
			$noticeTimeStamp = isset($_POST["noticeTimeStamp"]) ? $_POST["noticeTimeStamp"] : '';//整改内容时间戳
			$username = isset($_POST['username']) ? $_POST['username'] : '';//用户名称
			$userphone = isset($_POST['userphone']) ? $_POST['userphone'] : '';			//用户手机
			$timestamp = isset($_POST['timestamp']) ? $_POST['timestamp'] : '';		//用户时间戳（根据不同楼栋改变？）
			$level = isset($_POST['level']) ? $_POST['level'] : '';				//层级
			$userid = isset($_POST['userid']) ? $_POST['userid'] : '';				//userid
			$now_time = time();
			$issuetime = date('Y-m-d H:i:s',$now_time);
			$sql1 = "SELECT `number` FROM `tb_inspectaccept_notice`WHERE `projectId`='$projectId' AND `noticeTimeStamp`='$noticeTimeStamp'";
			$result1 = $conn->query($sql1);
			$row1 = $result1->fetch_assoc();
			$number = $row1['number'];
			$numberdata = explode(",",$number);//将查询到的数组按照“||”切割成数组
			$sql4 = "SELECT `post` FROM `tb_project_build_person`WHERE `userId`='$userid' AND `timeStamp`='$timestamp' AND `username`='$username' AND `phone`='$userphone'";
			$result4 = $conn->query($sql4);
			$row4 = $result4->fetch_assoc();
			$post = $row4['post'];
			$chatcontent = '';
			if(count($numberdata)>0){
				foreach($numberdata as $key =>$val){
					$number = $val;
					$sql2 = "SELECT `inspectItem` FROM `tb_inspectaccept_procedure` WHERE `number` = '$number'";
					$result2 = $conn->query($sql2);
					$row2 = $result2->fetch_assoc();
					$content = $row2['inspectItem'];
					$chatcontent = $content.'||'.$chatcontent;
					// echo $chatcontent;
					// $i++;
				}
				// echo $chatcontent;
				$sql3 = "INSERT INTO `tb_msg_chatcontent` SET `cardstamp`='$noticeTimeStamp',`projectId`='$projectId' , `issuetime` = '$issuetime' , `username`='$username' , `userphone` = '$userphone' , `userid` = '$userid' , `personalstamp` = '$timestamp' , `chatcontent` = '$chatcontent',`post`='$post',`tasktype`='下发整改'";
				$result3 = $conn->query($sql3);
				if(!$result3){
					$data['code'] = 0;
				}
			}else{
				$data['code'] = 0;
			}
			
			
		break;


		//获取整改卡片查看详情下方通知栏内容
		case 'getchatcontent':
			$projectId = isset($_POST['projectId']) ? $_POST['projectId'] : '';//整改卡片所属楼栋id
			$noticeTimeStamp = isset($_POST['noticeTimeStamp']) ? $_POST['noticeTimeStamp'] : '';//整改卡片时间戳id
			$sql1 = "SELECT * FROM `tb_msg_chatcontent` WHERE `projectId`='$projectId' AND `cardstamp`='$noticeTimeStamp'";
			$result1 = $conn->query($sql1);
			$res = array();
			$resData = array();
			$i = 0;
			if($result1->num_rows>0){
				while($row = $result1->fetch_assoc()){
					$resData['id'] = $row['id'];
					$resData['issuetime'] = $row['issuetime'];
					$resData['userphone'] = $row['userphone'];
					$resData['username'] = $row['username'];
					$resData['chatcontent'] = $row['chatcontent'];
					$resData['post'] = $row['post'];
					$resData['tasktype'] = $row['tasktype'];
					$resData['userid'] = $row['userid'];
					$resData['personalstamp'] = $row['personalstamp'];
					$res[$i] = $resData;
					$i++;
				}
				$data['data'] = $res;
			}else{
				$data['code'] = 0;
			}
		break;

		//添加备注
		case 'insertmarks' :
			$projectId = isset($_POST['projectId']) ? $_POST['projectId'] : '';//整改内容所属楼栋id
			$noticeTimeStamp = isset($_POST["noticeTimeStamp"]) ? $_POST["noticeTimeStamp"] : '';//整改内容时间戳
			$username = isset($_POST['username']) ? $_POST['username'] : '';//用户名称
			$username=json_decode($username);
			
			// $username = URLDecoder.decode(request.getParameter("username"),"UTF-8");
			
			$userphone = isset($_POST['userphone']) ? $_POST['userphone'] : '';			//用户手机
			$timestamp = isset($_POST['timestamp']) ? $_POST['timestamp'] : '';		//用户时间戳（根据不同楼栋改变？）
			$level = isset($_POST['level']) ? $_POST['level'] : '';	
			$level=json_decode($level);			//层级
			// $level = URLDecoder.decode(request.getParameter("level"),"UTF-8");
			$userid = isset($_POST['userid']) ? $_POST['userid'] : '';				//userid
			$chatcontent = isset($_POST['chatcontent']) ? $_POST['chatcontent'] : '';
			$chatcontent=json_decode($chatcontent);	
			// $chatcontent = URLDecoder.decode(request.getParameter("chatcontent"),"UTF-8");
			$now_time = time();
			$issuetime = date('Y-m-d H:i:s',$now_time);
			$sql4 = "SELECT `post` FROM `tb_project_build_person`WHERE `userId`='$userid' AND `timeStamp`='$timestamp' AND `username`='$username' AND `phone`='$userphone'";
			$result4 = $conn->query($sql4);
			$row4 = $result4->fetch_assoc();
			$post = $row4['post'];
			$sql3 = "INSERT INTO `tb_msg_chatcontent` SET `cardstamp`='$noticeTimeStamp',`projectId`='$projectId' , `issuetime` = '$issuetime' , `username`='$username' , `userphone` = '$userphone' , `userid` = '$userid' , `personalstamp` = '$timestamp' , `chatcontent` = '$chatcontent',`post`='$post',`tasktype`='添加备注'";
			$result3 = $conn->query($sql3);
			if(!$result3){
				$data['code'] = 0;
			}
		break;
	}
	$json = json_encode($data);
	echo $json;
	$conn->close();	

?>