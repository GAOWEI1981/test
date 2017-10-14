<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<title>古海角
</title>
<link rel="stylesheet" href="../function/industry.css"/>
<link rel="stylesheet" href="../function/weui/dist/style/weui.css"/>
<link rel="stylesheet" href="../function/weui/dist/style/weui.min.css"/>
</head>
<body ontouchstart>

<?
error_reporting(E_ALL & ~E_NOTICE);
if(!isset($_SESSION)) session_start();

include_once("../function/config.php");
include_once("../function/JSSDK.php");
include_once("../function/Script.php");

/*
$data->Brand = "三恒";
$data->AppName = "products_sanheng";
$fp = fopen("ConfigInfo.inf", "w");
fwrite($fp, json_encode($data));
fclose($fp);
*/

if($Database->Connect()==false)
{
	echo "db connect false!";
	return;
}
mysql_select_db($DatabaseName);
//LogInFile("visit","Visit.txt");//统计访问量
include_once("chunks/chunk_title.php");


$operate=$_GET['operate'];

$PageSize=6;
$keyword=$_GET['keyword'];
if(strlen($_GET['PageOffset'])==0) $PageOffset=0;
else $PageOffset=$_GET['PageOffset'];

//控制缩略图偏移量
$PosXScale=1.0;
$PosYScale=0.1;
//**************
$FileName=basename($_SERVER["PHP_SELF"]);
$config=file_get_contents("ConfigInfo.inf");
$brand=GetXMLParam($config,"brand");//获取本地设置
$UserInfoUrl=GetXMLParam($config,"UserInfoUrl");//微信用户信息获取网址
$SysName=GetXMLParam($config,"AppName");

//print_r($_SESSION);echo "<br>";return;
if(strlen($_GET[owner])>0)
{
	$_SESSION[WebOwner]=$_GET[owner];
}
//为了获取用户ID
if(strlen($_SESSION[openid])==0)
{
	//<UserInfoUrl>http://lanxiao.ghostinmetal.com/WeixinPayService/page_get_user_info.php?operate=GetUserInfo</UserInfoUrl>
	if(strlen($_GET[openid])==0)
	{
		$ReturnUrl="http://".$_SERVER[HTTP_HOST]."{$_SERVER["PHP_SELF"]}";//返回的链接
		//echo $ReturnUrl;echo "<br>";return;
		$url="{$UserInfoUrl}&ReturnUrl={$ReturnUrl}&OutState=1234";
		echo $ReturnUrl;echo "<br>";
		echo $url;echo "<br>";
		Header("Location:{$url}");
		//echo $UserInfoUrl;echo "<br>";
	}
	else
	{
		$time=time();
		
		$_SESSION[openid]=$_GET[openid];
	}
	//echo "sdfdsfd";return;
}
else
{
	$time=time();
	$sql="insert into signup_users (openid,time,user_type,owner) values('{$_SESSION[openid]}','{$time}','2','{$_SESSION[WebOwner]}')";
	mysql_query($sql);
}
//print_r($_SESSION);echo "<br>";
$operate=$_GET[operate];
switch($operate)
{
case "SwitchViewModal":
	//$_SESSION[ViewModal]=$_GET[view];
	$_SESSION[PageModal]=$_GET[PageModal];
	Header("Location:{$FileName}");
	return;
}
//print_r($_SESSION);
//if(strlen($_GET[ViewModal])>0) $_SESSION[ViewModal]=$_GET[ViewModal];
//if(strlen($_GET[GroupID])>0) $_SESSION[GroupID]=$_GET[GroupID];
$PageWidth="100%";
if($_SESSION[PageModal]=="computer")
{
	$PageWidth="600px";
}

//echo $PageWidth;echo "<br";
switch($_GET[ViewModal])
{
	
case "ViewProductGroup":
	//print_r($_SESSION);echo "<br>";
	$AllPics="select *,count(id) as ItemCount from product_items group by modal";
	//$AllMods="select * from products where brand='{$brand}'";
	$AllMods="select * from product_group_items where GroupID='{$_GET[GroupID]}'";
	//$sql="select a.modal,b.ItemCount from ({$AllMods}) a left join ({$AllPics}) b on a.modal=b.modal";
	$sql="select a.modal,a.ItemCount from ({$AllPics}) a,($AllMods) b where a.modal in (b.modal)";//echo $sql;
	$sql="select a.*,b.time from ({$sql}) a left join products b on a.modal=b.modal";
	//echo $sql;
	$sql="select sql_calc_found_rows a.* from ({$sql}) a where a.ItemCount is not null and a.modal like '%{$keyword}%' order by time desc limit {$PageOffset},{$PageSize}";
	//款式条目总数
	//
	break;
default:
	VisitCounter($SysName,$FileName);//统计访问量
	$AllPics="select *,count(id) as ItemCount from product_items group by modal";
	$AllMods="select * from products where brand='{$brand}'";
	$sql="select a.*,b.ItemCount from ({$AllMods}) a left join ({$AllPics}) b on a.modal=b.modal";
	$sql="select sql_calc_found_rows a.* from ({$sql}) a where a.ItemCount is not null and a.modal like '%{$keyword}%' order by time desc limit {$PageOffset},{$PageSize}";
	//款式条目总数
	//echo $sql;echo "<br>";
	//break;
	break;
}
//echo $sql;echo "<br>";
$_SESSION[HomePage]=$FileName;

//$config=file_get_contents("ConfigInfo.inf");
$chunk=GetXMLParam($config,"GroupID_{$_GET[GroupID]}");
//echo $chunk;echo "<><><br>";
if(strlen($chunk)>0)
{
	include_once("chunks/{$chunk}");
}
else
	include_once("chunks/chunk_present_modals_mall.php");
//print_r($_SESSION);echo "sdfsdfsd";
if(strlen($_SESSION[adminID])>0)
	$LoginState="我的订单";
else $LoginState="登录";
?>
<div class="FloatBottom" id="BottomBar" width="100%" align="center" style="background-color:#FFFFFF">
<table width="100%" border="0" cellpadding="3" cellspacing="3">
<tr>
      <td width="24%" align="center"><a class="weui-form-preview__btn weui-form-preview__btn_default" href="http://apis.map.qq.com/uri/v1/marker?marker=coord:21.672140,109.206420;title:古海角;addr广西北海市合浦县廉州镇东城北路64号&referer=myapp">
		  <img src="../icon/icon_location.png" alt="" style="width:20px;"></a></td>
      
      <td width="25%" align="center"><a class="weui-form-preview__btn weui-form-preview__btn_default" href="page_user_table.php">
        <img src="../icon/icon_me.png" alt="" style="width:30px;" /></a></td>
      <td width="25%" align="center"><a class="weui-form-preview__btn weui-form-preview__btn_default" href="bulletin_board.php"><img src="../icon/icon_us.png" alt="" style="width:30px;" /></a></td>
      <td width="26%" align="center"><a class="weui-form-preview__btn weui-form-preview__btn_default" href="page_user_login_admin.php?operate=logout" onclick="return CheckLogin();"><img src="../icon/icon_config.png" alt="" style="width:30px;" /></a></td>
    </tr>
</table>
</div>
</body>
</html>
<script>
var err="<?echo $_GET[err];?>";
if(err.length>0) alert(err);
function CheckLogin()
{
	var admin="<?echo $_SESSION[adminID];?>";
	if(admin.length>0)
	{
		if(confirm("跳转到后台将退出当前登陆，是否继续？")==true)
		{
			return true;
		}
	}
	else return true;
	return false;
}
</script>