<?php
	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	require("../../../../conn.php");
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
		//获取整改参数
		case 'getRectification':
			$noticeCardTimeStamp = isset($_POST["noticeCardTimeStamp"]) ? $_POST["noticeCardTimeStamp"] : '';
			$sql = "SELECT * FROM tb_inspectaccept_notice WHERE noticeTimeStamp='$noticeCardTimeStamp'";
			$result = $conn -> query($sql);
			$res = array();
			$resData = array();
		    $i=0;
		    if($result->num_rows>0)	{
			 	while($row = $result->fetch_assoc()){
			 		$resData['id'] = $row['id'];
					 $resData['projectId'] = $row['projectId'];//工程id
                    $resData['noticeTimeStamp'] = $row['noticeTimeStamp'];//时间戳
					$resData['inspectDate'] = $row['inspectDate'];//检查日期
                    $resData['endDate'] = $row['endDate'];//截止日期
                    $resData['rectificationDate'] = $row['rectificationDate'];//回复日期
                    $resData['inspectPosition'] = $row['inspectPosition'];//部位
					$resData['inspectItemHead'] = $row['inspectItemHead'];//检查项目
                    $resData['inspectPointPic'] = $row['inspectPointPic'];//问题图片
                    $resData['rectificationpic'] = $row['rectificationpic'];//整改图片
                    $resData['category'] = $row['category'];//工程类别
					// $resData['inspectPersonName'] = $row['inspectPersonName'];
					// $resData['inspectSendPerson'] = $row['inspectSendPerson'];
					// $resData['noticeNumber'] = $row['noticeNumber'];
					$res[$i] = $resData;
		            $i++;
				}
				$data['data'] = $res;
		    }else {
				$data['code'] = 0;
			}
		break;

		//上传整改内容以及将备注添加入消息栏
		case 'saveReactPic':
			$id = isset($_POST['id']) ? $_POST['id'] : '' ;
			$msgid = isset($_POST['msgid']) ? $_POST['msgid'] : '' ;
			$projectId = isset($_POST['projectId']) ? $_POST['projectId'] : '';
			$username = isset($_POST['username']) ? $_POST['username'] : '';//用户名称
			$userphone = isset($_POST['userphone']) ? $_POST['userphone'] : '';			//用户手机
			// $timestamp = isset($_POST['timestamp']) ? $_POST['timestamp'] : '';		//用户时间戳（根据不同楼栋改变？）（此处无获取后续研究修改）
			$level = isset($_POST['level']) ? $_POST['level'] : '';				//层级
			$userid = isset($_POST['userid']) ? $_POST['userid'] : '';				//userid
			$noticeTimeStamp = isset($_POST['noticeTimeStamp']) ? $_POST['noticeTimeStamp'] : '' ;
			$reacttime = isset($_POST['reacttime']) ? $_POST['reacttime'] : '' ;
			$remarks = isset($_POST['remarks']) ? $_POST['remarks'] : '';
			$remarks = json_decode($remarks);
			$photoSrc = isset($_POST['photoSrc']) ? $_POST['photoSrc'] : '' ;
			$photoSrc = json_decode($photoSrc);
			$base = $photoSrc;
			$timeStamp = msectime();
			$img_path='../../../images/app_pic/inspectPic/ViolationPic/';
			$images_name = "";
			$images_name =  $timeStamp.'.'.'jpg';
			$base64 = explode(",",$base[0]);
			$data1 = base64_decode($base64[1]);
			file_put_contents($img_path.$images_name, $data1);

			$sql1 = "UPDATE `tb_inspectaccept_notice` SET `rectificationpic` = '$images_name', `rectificationDate` = '$reacttime', `state` = '待审核' WHERE `id` = '$id' AND `noticeTimeStamp` = '$noticeTimeStamp' ";
			$result1 = $conn->query($sql1);

			$sql4 = "SELECT `post` FROM `tb_project_build_person`WHERE `userId`='$userid' AND `username`='$username' AND `phone`='$userphone'"; //查询缺少个人时间戳,后续需改动
			$result4 = $conn->query($sql4);
			$row4 = $result4->fetch_assoc();
			$post = $row4['post'];

			$sql3 = "INSERT INTO `tb_msg_chatcontent` SET `cardstamp`='$noticeTimeStamp',`projectId`='$projectId' , `issuetime` = '$reacttime' , `username`='$username' , `userphone` = '$userphone' , `userid` = '$userid' , `personalstamp` = '' , `chatcontent` = '$remarks',`post`='$post',`tasktype`='整改回复'";
			$result3 = $conn->query($sql3);

			$sql = "UPDATE `tb_msg_notice` SET `delete`='1' WHERE `id`='$msgid' ";
			$result = $conn->query($sql);
			// if($result1){

			// }else{
			// 	$data["code"] = 0;
			// }
		break;

		//推送检查消息
		case 'messagepush':
			$cardTimeStamp = isset($_POST["cardTimeStamp"]) ? $_POST["cardTimeStamp"] : '';
			$comefrom = isset($_POST["comefrom"]) ? $_POST["comefrom"] : '';
			$gofrom = isset($_POST["gofrom"]) ? $_POST["gofrom"] : '';
			$status = isset($_POST["status"]) ? $_POST["status"] : '';
			$time=date("Y-m-d H:i:s");
			$date=date("Y-m-d");
			$sql1 = "SELECT * FROM tb_inspectaccept_notice AS a,tb_project AS b WHERE a.`noticeTimeStamp` = '$cardTimeStamp' AND a.projectId=b.id";
			$result1 = $conn->query($sql1);
			$row1 = $result1->fetch_assoc();
			$projectName = $row1["projectName"];
			$inspectPosition = $row1["inspectPosition"];
			$projectId = $row1["projectId"];
			$content = '关于'.$projectName.'-'.$inspectPosition.'的整改回复通知';
			$timeStamp=time().mt_rand(111, 999);
			$res = array();
			$resData = array();
            $i=0;
            if($result1->num_rows>0)	{
				if($status=='getreject'){
					$receiver = explode('||', $comefrom);
					$receiverPhone = $receiver[0];
					$receiverid = $receiver[1];
					$sql2="INSERT INTO tb_msg_notice SET timeStamp = '$timeStamp', title = '项目检查回复' , `content` = '$content',cardTimeStamp = '$cardTimeStamp', `time` = '$time', `read` = '0', `delete` = '0', `receiver` = '$receiverPhone',`userId` = '$receiverid', `projectId` = '$projectId' , `messagetype` = '项目检查', `position` = '$inspectPosition', `positiontype` = '整改' ,`comefrom` = '$gofrom'";
					$result2 = $conn->query($sql2);
					$data['data'] = $res;
				}else{
					$sql3="UPDATE tb_inspectaccept_notice SET state = '待审核' WHERE noticeTimeStamp='$cardTimeStamp' ";
					$result3 = $conn->query($sql3);
					$receiver = explode('||', $comefrom);
					$receiverPhone = $receiver[0];
					$receiverid = $receiver[1];
					$sql2="INSERT INTO tb_msg_notice SET timeStamp = '$timeStamp', title = '项目检查回复' , `content` = '$content',cardTimeStamp = '$cardTimeStamp', `time` = '$time', `read` = '0', `delete` = '0', `receiver` = '$receiverPhone',`userId` = '$receiverid', `projectId` = '$projectId' , `messagetype` = '项目检查', `position` = '$inspectPosition', `positiontype` = '审核' ,`comefrom` = '$gofrom'";
					$result2 = $conn->query($sql2);
					$data['data'] = $res;
				}
				
            }else {
				$data['code'] = 0;
			}
		break;

		//整改审核通过
		case 'getadopt':
			$msgid = isset($_POST['msgid']) ? $_POST['msgid'] : '' ;
			$projectId = isset($_POST['projectId']) ? $_POST['projectId'] : '';
			$cardTimeStamp = isset($_POST["cardTimeStamp"]) ? $_POST["cardTimeStamp"] : '';
			$remarks = isset($_POST['remarks']) ? $_POST['remarks'] : '';
			$remarks = json_decode($remarks);
			$username = isset($_POST['username']) ? $_POST['username'] : '';//用户名称
			$userphone = isset($_POST['userphone']) ? $_POST['userphone'] : '';			//用户手机
			// $timestamp = isset($_POST['timestamp']) ? $_POST['timestamp'] : '';		//用户时间戳（根据不同楼栋改变？）（此处无获取后续研究修改）
			$level = isset($_POST['level']) ? $_POST['level'] : '';				//层级
			$userid = isset($_POST['userid']) ? $_POST['userid'] : '';				//userid
			$now_time = time();
			$issuetime = date('Y-m-d H:i:s',$now_time);
			$sql2="UPDATE tb_inspectaccept_notice SET `state` = '已通过', `audittime` = '$issuetime' WHERE noticeTimeStamp='$cardTimeStamp' ";
			$result2 = $conn->query($sql2);
			$sql4 = "SELECT `post` FROM `tb_project_build_person`WHERE `userId`='$userid' AND `username`='$username' AND `phone`='$userphone'"; //查询缺少个人时间戳,后续需改动
			$result4 = $conn->query($sql4);
			$row4 = $result4->fetch_assoc();
			$post = $row4['post'];

			$sql3 = "INSERT INTO `tb_msg_chatcontent` SET `cardstamp`='$cardTimeStamp',`projectId`='$projectId' , `issuetime` = '$issuetime' , `username`='$username' , `userphone` = '$userphone' , `userid` = '$userid' , `personalstamp` = '' , `chatcontent` = '$remarks',`post`='$post',`tasktype`='审核回复'";
			$result3 = $conn->query($sql3);

			$sql = "UPDATE `tb_msg_notice` SET `delete`='1' WHERE `id`='$msgid' ";
			$result = $conn->query($sql);
		break;

		//整改审核驳回
		case 'getreject':
			$msgid = isset($_POST['msgid']) ? $_POST['msgid'] : '' ;
			$projectId = isset($_POST['projectId']) ? $_POST['projectId'] : '';
			$cardTimeStamp = isset($_POST["cardTimeStamp"]) ? $_POST["cardTimeStamp"] : '';
			$remarks = isset($_POST['remarks']) ? $_POST['remarks'] : '';
			$remarks = json_decode($remarks);
			$username = isset($_POST['username']) ? $_POST['username'] : '';//用户名称
			$userphone = isset($_POST['userphone']) ? $_POST['userphone'] : '';			//用户手机
			// $timestamp = isset($_POST['timestamp']) ? $_POST['timestamp'] : '';		//用户时间戳（根据不同楼栋改变？）（此处无获取后续研究修改）
			$level = isset($_POST['level']) ? $_POST['level'] : '';				//层级
			$userid = isset($_POST['userid']) ? $_POST['userid'] : '';				//userid
			$now_time = time();
			$issuetime = date('Y-m-d H:i:s',$now_time);
			$sql2="UPDATE tb_inspectaccept_notice SET `state` = '待整改', `audittime` = '$issuetime' WHERE noticeTimeStamp='$cardTimeStamp' ";
			$result2 = $conn->query($sql2);
			$sql4 = "SELECT `post` FROM `tb_project_build_person`WHERE `userId`='$userid' AND `username`='$username' AND `phone`='$userphone'"; //查询缺少个人时间戳,后续需改动
			$result4 = $conn->query($sql4);
			$row4 = $result4->fetch_assoc();
			$post = $row4['post'];

			$sql3 = "INSERT INTO `tb_msg_chatcontent` SET `cardstamp`='$cardTimeStamp',`projectId`='$projectId' , `issuetime` = '$issuetime' , `username`='$username' , `userphone` = '$userphone' , `userid` = '$userid' , `personalstamp` = '' , `chatcontent` = '$remarks',`post`='$post',`tasktype`='审核回复'";
			$result3 = $conn->query($sql3);

			$sql = "UPDATE `tb_msg_notice` SET `delete`='1' WHERE `id`='$msgid' ";
			$result = $conn->query($sql);
		break;


		//整改界面查看整改问题
		case 'showquestion':
			$noticeTimeStamp = isset($_POST['noticeTimeStamp']) ? $_POST['noticeTimeStamp'] : '';
			$sql = "SELECT * FROM `tb_inspectaccept_notice` WHERE `noticeTimeStamp`='$noticeTimeStamp'";
			$result = $conn->query($sql);
		    if($result->num_rows>0)	{
			 	while($row = $result->fetch_assoc()){
					$number = $row['number'];
					$chatcontent = '';
					$numberdata = explode(",",$number);//将查询到的数组按照“||”切割成数组
					foreach($numberdata as $key =>$val){
						$number = $val;
						$sql2 = "SELECT `inspectItem` FROM `tb_inspectaccept_procedure` WHERE `number` = '$number'";
						$result2 = $conn->query($sql2);
						$row2 = $result2->fetch_assoc();
						$content = $row2['inspectItem'];
						$chatcontent = $content.'||'.$chatcontent;
					}
					$resData = array();
					$resData['inspectLevel'] = $row['inspectLevel'];//检查层级
					$resData['noticeNumber'] = $row['noticeNumber'];//通知单编号
					$resData['category'] = $row['category'];//工程类别
					$resData['inspectItemHead'] = $row['inspectItemHead'];//检查项目
					$resData['inspectPosition'] = $row['inspectPosition'];//部位
					$resData['inspectProcedure'] = $row['inspectProcedure'];//检查工序
					$resData['chatcontent'] = $chatcontent;//检查内容
					$resData['inspectPersonName'] = $row['inspectPersonName'];//检查人员
					$resData['leaderName'] = $row['leaderName'];//组长名称
					$resData['contactMode'] = $row['contactMode'];//联系方式
					$resData['inspectDate'] = $row['inspectDate'];//检查日期
                    $resData['endDate'] = $row['endDate'];//截止日期
                    $resData['rectificationDate'] = $row['rectificationDate'];//回复日期
                    // $resData['rectificationpic'] = $row['rectificationpic'];//整改图片
					// $resData['inspectPersonName'] = $row['inspectPersonName'];
					// $resData['inspectSendPerson'] = $row['inspectSendPerson'];
					// $resData['noticeNumber'] = $row['noticeNumber'];
				}
				$data['data'] = $resData;
		    }else {
				$data['code'] = 0;
			}
		break;


		//获取问题描述
		case 'getquestion':
			$noticeTimeStamp = isset($_POST['noticeTimeStamp']) ? $_POST['noticeTimeStamp'] : '';
			$sql = "SELECT * FROM `tb_inspectaccept_violationitem` WHERE `noticeTimeStamp`='$noticeTimeStamp'";
			$result = $conn->query($sql);
			$res = array();
			$resData = array();
			$i = 0;
			if($result->num_rows>0){
				while($row = $result->fetch_assoc()){
					$resData['itemType'] = $row['itemType'];//问题类型
					$resData['violationContent'] = $row['violationContent'];//具体问题
					$res[$i] = $resData;
					$i++;
				}
				$data['data'] = $res;
			}else{
				$data['code'] = 0;
			}
			
		break;

		//获取备注信息
		case 'getinformation':
			$noticeTimeStamp = isset($_POST['noticeTimeStamp']) ? $_POST['noticeTimeStamp'] : '';//整改卡片时间戳id
			$sql1 = "SELECT * FROM `tb_msg_chatcontent` WHERE  `cardstamp`='$noticeTimeStamp'";
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
	}
	$json = json_encode($data);
	echo $json;
	$conn->close();	

?>