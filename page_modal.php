<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>

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
$CurModalInf=GetItemA("products","modal",$_GET[modal]);
$detail=$CurModalInf['detail'];//型号信息
$detail=str_replace("\r\n","<br>",$detail);


$FileName=basename($_SERVER["PHP_SELF"]);
$config=file_get_contents("ConfigInfo.inf");
$brand=GetXMLParam($config,"brand");//获取本地设置
$SysName=GetXMLParam($config,"AppName");
//print_r($_GET);echo "<br>";

//session_unset();
//session_destroy();
$PageWidth="100%";
if($_SESSION[PageModal]=="computer")
{
	$PageWidth="600px";
}

?>
<title><?echo $_GET[modal];?>
</title>
<link href="../function/industry.css" rel="stylesheet" type="text/css" />
</head>
<body class="BodyNoSpace">
<?
include_once("chunks/chunk_title.php");

?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Industry">
  <tr>
	<?
		if(strlen($_SESSION[HomePage])>0)
		{
			$LastPage=$_SESSION[HomePage];
		}
		else $LastPage="index.php";
	?>
    <th width="84%" height="25" align="left"><?echo "{$_GET[modal]}";?></th>
    <th width="16%" height="25" colspan="5" align="left" valign="middle" id="TextOut" style="font-size: xx-small">&nbsp;</th>
  </tr>
  <tr>
    <td colspan="6">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6">
    <?
	$PicsSql="select * from product_items where modal='{$_GET[modal]}'";
	include_once("chunks/chunk_modal_pic_scroll.php");
    ?>
    </td>
  </tr>
  <tr>
    <td height="25" colspan="6" align="center">
    <?
	$ModalTitle="商品介绍";
    include_once("chunks/chunk_modal_title.php");
	?><span></span>
    </td>
  </tr>
  <tr>
    <td height="25" colspan="6" align="left"><span><? echo $detail;?></span></td>
  </tr>
  <tr>
    <td height="25" colspan="6" align="center">
    <?
	include_once("chunks/chunk_modal_foot.php");
	include_once("chunks/chunk_foot.php");
    ?><span></span></td>
  </tr>
</table>
</body>
</html>
<script>
</script>