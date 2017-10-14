<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<link href="../function/industry.css" rel="stylesheet" type="text/css" />
<title>尺码表</title>
</head>
<body class="BodyNoSpace">
<?
error_reporting(E_ALL & ~E_NOTICE);
if(!isset($_SESSION)) session_start();
include_once "../function/excel/Classes/PHPExcel/IOFactory.php";
include_once("../function/config.php");
include_once("LocalConfig.php");
include_once("../function/Script.php");

$FileName=basename($_SERVER["PHP_SELF"]);
$config=file_get_contents("ConfigInfo.inf");
$brand=GetXMLParam($config,"brand");//获取本地设置
$SysName=GetXMLParam($config,"AppName");
//https://m.kuaidi100.com/

if($Database->Connect()==false)
{
	echo "db connect false!";
	return;
}
mysql_select_db($DatabaseName);


$operate=$_GET["operate"];
switch($operate)
{
case "":
	$SizeTable=ReadSizeTable("../function/SizeTable.xls");//读取尺码换算表
	break;
case "FitSize":
	$avo=$_GET["avo"];
	$sta=$_GET["sta"];
	$SizeTable=ReadSizeTable("../function/SizeTable.xls");//读取尺码换算表
	$size=ChineseSize($sta,$avo,$SizeTable);
	echo "<response>";
	echo $size;
	//print_r($_GET);
	echo "</response>";
	return;
}


$PageWidth="100%";
if($_SESSION[PageModal]=="computer")
{
	$PageWidth="600px";
}
include_once("chunks/chunk_title.php");
?>
<div with="100%" align="center">
<table width="<?echo $PageWidth;?>" border="0" cellpadding="0" cellspacing="0" bordercolor="#000000" class="Industry" id="MainTable">
  <tr>
    <td colspan="3" id="ChunkGrid"><img src="pic/SizeTable.jpg" width="100%"/></td>
  </tr>
  <tr>
    <th id="ChunkGrid">项目</th>
    <th id="ChunkGrid">数值</th>
    <th width="24%" id="ChunkGrid">结果</th>
  </tr>
  <tr>
    <td width="24%" id="ChunkGrid">身高(CM)</td>
    <td width="52%" id="ChunkGrid">      
      <div align="center">
        <input name="textfield" type="text" id="stature" style="width:80px;" />
    </div></td>
    <td width="24%" align="center" rowspan="2" id="SizeResult">&nbsp;</td>
  </tr>
  <tr align="left">
    <td height="23" align="left" bordercolor="#000000">体重(KG)</td>
    <td height="23" align="left" bordercolor="#000000"><div align="center">
      <input name="textfield2" type="text" id="avoirdupois" style="width:80px;" />
    </div></td>
  </tr>
  <tr align="left">
    <td height="26" colspan="3" align="left">      <div align="center">
        <input name="Submit" type="button" class="SubmitButton" onclick="return FitSize();" value="匹配尺码" />
    </div>    </td>
  </tr>
</table>
<?include_once("chunks/chunk_foot.php");?>
</div>
</body>
</html>
<script>
function FitSize()
{
	var obj=document.getElementById("avoirdupois");
	var avo=obj.value;
	var obj=document.getElementById("stature");
	var sta=obj.value;
	try
	{
		var ajax=InitAjax();
		var url="<?echo $FileName;?>?operate=FitSize&sta="+sta+"&avo="+avo;
		alert(url);
		ajax.onreadystatechange =function()//回调函数
		{
			if(ajax.readyState==4 && ajax.status==200)
			{
				var text=GetXMLParam(ajax.responseText,"response");
				var obj=document.getElementById("SizeResult");		
				obj.innerText=text;
			}
		}
		
		ajax.open("GET",url,true);
		ajax.send()
	}
	catch(err)
	{
		alert("javascript失败："+err.description);
	}
	return false;
}
</script>