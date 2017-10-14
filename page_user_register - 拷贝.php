<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<title>用户注册</title>
<link href="../function/industry.css" rel="stylesheet" type="text/css" />
</head>
<body class="BodyNoSpace">
<?
ob_start();
error_reporting(E_ALL & ~E_NOTICE);
if(!isset($_SESSION)) session_start();
include_once("../function/config.php");
include_once("../function/JSSDK.php");
include_once("../function/Script.php");
include_once("phone_msg_sender.php");


$FileName=basename($_SERVER["PHP_SELF"]);
if($Database->Connect()==false)
{
	echo "db connect false!";
	return;
}

mysql_select_db($DatabaseName);


//print_r($_SESSION);echo "<br>";
$operate=$_GET['operate'];
switch($operate)
{
case "KeepEmpty":
	echo $_GET[message];echo "<br>";
	switch($_GET[message])
	{
	case "OK":
		break;
	default:
		echo "<script>alert('验证码获取频率过高，请稍后再试！');</script>";
		break;
	}
	return;
case "SendIdentifyingCode":
	//print_r($_POST);echo "<br>";
	$time=time();
	$code=rand(0,999999);
	if($code<100000)
	{
		$code+=1000000;
		$code=substr($code,1,strlen($code)-1);
	}
	$sql="insert into identifying_code (phone,code,time) values('{$_POST[phone]}','{$code}','{$time}')";
	mysql_query($sql);
	echo $sql;echo "<br>";
	
	//print_r($MsgSender);echo "<br>";
	$ar= Array();
	$ar['code']=$code;
	$response = $MsgSender->sendSms(
    "南宁杜酷", // 短信签名
    "SMS_90175057", // 短信模板编号
    $_POST[phone], // 短信接收者
   	$ar,
    "123"
	);
	print_r($response);
	Header("Location:{$FileName}?operate=KeepEmpty&message={$response->Message}");
	return;
case "RegisterExe":
	print_r($_POST);echo "<br>";
	$sql="select * from identifying_code where phone='{$_POST[phone]}' and code='{$_POST[IdentifyingCode]}' order by time desc";
	echo $sql;echo "<br>";
	$result=mysql_query($sql);
	$num=mysql_num_rows($result);
	
	if($num==0)
	{
		Header("Location:page_user_register.php?err=验证码错误");
		return;
	}
	else
	{
		$row=mysql_fetch_array($result);
		$TimeDis=time()-$row[time];
		if($TimeDis>3600)//十分钟内有效
		{
			//Header("Location:{$FileName}?err=验证码失效");
			echo "验证码失效";
		}
		else
		{
			echo "验证成功<br>";
			//$sql="select * from signup_users where phone='{$_POST[phone]}'";
			$sql="select * from signup_users where openid='{$_SESSION[openid]}'";
			//echo $sql;echo "<br>";
			$result=mysql_query($sql);
			$num=mysql_num_rows($result);
			if($num>0)
			{
				$sql="select * from signup_users where phone='{$_POST[phone]}'";
				$result=mysql_query($sql);
				$item=mysql_fetch_array($result);
				
				
				$openid=$item[openid];//得到手机绑定的账号
				if(strlen($openid)>0)
				{
					//echo $sql;echo "<br>";return;
					//$row=mysql_fetch_array($result);
					$sql="update signup_users set password='{$_POST[content]}',phone='{$_POST[phone]}' where openid='{$openid}'";
					echo $sql;echo "<br>";
					mysql_query($sql);
					//echo "更新密码成功<br>";
					$_SESSION[adminID]=$openid;
					//Header("Location:index.php?err=注册成功！&owner={$_SESSION[WebOwner]}");
				}
				
				
				/*
				if($item[openid]!=$_SESSION[openid])//同一个手机号注册了多个账号
				{
					Header("Location:page_user_register.php?err={$_POST[phone]}号码已经绑定了其他微信！&owner={$_SESSION[WebOwner]}");
				}
				else
				{
					echo $sql;echo "<br>";return;
					$row=mysql_fetch_array($result);
					$sql="update signup_users set password='{$_POST[content]}',phone='{$_POST[phone]}' where openid='{$row[openid]}'";
					echo $sql;echo "<br>";
					mysql_query($sql);
					//echo "更新密码成功<br>";
					$_SESSION[adminID]=$row[openid];
					Header("Location:index.php?err=注册成功！&owner={$_SESSION[WebOwner]}");
				}
				*/
			}
			else
			{
				/*$time=time();
				$id="MAKE-".CreateID(10)."_".$time;
				$sql="insert into signup_users (openid,time,phone,owner) values('{$id}','{$time}','{$_POST[phone]}','{$_SESSION[WebOwner]}')";
				//echo $sql;echo "<br>";
				mysql_query($sql);
				//echo "添加成功<br>";
				$_SESSION[adminID]=$id;*/
                Header("Location:index.php?err=无法识别用户ID！&owner={$_SESSION[WebOwner]}");
			}
		}
	}
	return;
}


include_once("chunks/chunk_title.php");
$TargetPage="";
include_once("chunks/chunk_register.php");
?>
</body>
</html>
<script>
</script>