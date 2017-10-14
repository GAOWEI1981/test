<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<link href="../function/industry.css" rel="stylesheet" type="text/css" />
<?php
error_reporting(E_ALL & ~E_NOTICE);
if(!isset($_SESSION)) session_start();
header("Content-Type:text/html;charset=utf-8");
include_once("../function/config.php");
include_once("../function/Script.php");
date_default_timezone_set("ETC/GMT-8"); 
//echo PageHead("OrderOperate.php","球服参数");

if($Database->Connect()==false)
{
	echo "db connect false!";
	return;
}

mysql_select_db($DatabaseName);


$id=$_GET['id'];
$item=GetItemA("product_items","id",$id);
//print_r($item);
//echo "getetet";
$path="../pic/data/{$item['path']}";


if(strlen($PageWidth)==0) $FullPicWidth="100%";
else $FullPicWidth=$PageWidth;


?>
<div align="center" width="100%">
<table id="MainTable" width="<?echo $FullPicWidth;?>" border="0" cellpadding="0" cellspacing="0" class="Industry">
  <tr>
    <th height="20" align="left"><?echo "{$item['modal']} {$item['color']}";?>&nbsp;</th>
  </tr>
  <tr>
    <td height="20">&nbsp;</td>
  </tr>
  <tr>
    <td id="ChunkGrid">
		<div align="center">
		<img src="<?echo $path;?>" width="100%"/>
	</div>
	</td>
  </tr>
  <tr align="left">
    <td height="40" align="left">	</td>
  </tr>
</table>
</div>
<script>
function GoBack()
{
	
	//alert(document.referrer);
	history.back(-1);
	//history.go(-1);
	//document.location=document.referrer;
	return false;
}
</script>