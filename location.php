<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<title>三恒产品列表
</title>
<link href="../function/industry.css" rel="stylesheet" type="text/css" />
</head>
<body class="BodyNoSpace">
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
$SysName=GetXMLParam($config,"AppName");

$operate=$_GET[operate];
switch($operate)
{
case "SwitchViewModal":
	$_SESSION[ViewModal]=$_GET[view];
	$_SESSION[GroupID]=$_GET[GroupID];
	Header("Location:{$FileName}");
	return;
}
//print_r($_SESSION);
switch($_SESSION[ViewModal])
{
	
case "ViewProductGroup":
	//print_r($_SESSION);echo "<br>";
	$AllPics="select *,count(id) as ItemCount from product_items group by modal";
	//$AllMods="select * from products where brand='{$brand}'";
	$AllMods="select * from product_group_items where GroupID='{$_SESSION[GroupID]}'";
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
//session_unset();
//session_destroy();
include_once("chunks/chunk_title.php");
//include_once("chunks/chunk_menu_scroll.php");
?></td>
<iframe width="504" height="558" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://j.map.baidu.com/exVyL"></iframe>
</body>
</html>
<script>

</script>