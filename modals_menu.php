<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<link href="../function/InterfaceStyle.css" rel="stylesheet" type="text/css" />
<?
ob_start();
error_reporting(E_ALL & ~E_NOTICE);
if(!isset($_SESSION)) session_start();

include_once("../function/config.php");
include_once("../function/JSSDK.php");
include_once("../function/Script.php");

/*
$data->Brand = "三恒";
$data->AppName = "products_sanheng";
$fp = fopen("ConfigInfo.php", "w");
fwrite($fp, json_encode($data));
fclose($fp);*/

$FileName=basename($_SERVER["PHP_SELF"]);

//必须要管理员才能登陆
if(!isset($_SESSION['adminID']))
{
	//Header("Location:Login.php");
	//$operate="login";
	Header("Location:Login.php");
}
if($Database->Connect()==false)
{
	echo "db connect false!";
	return;
}

mysql_select_db($DatabaseName);

$GroupID=$_GET['GroupID'];
$operate=$_GET['operate'];
switch($operate)
{
case "AddToGroup":
	$modal=$_GET['modal'];
	$sql="insert into product_group_items (modal,GroupID) values('{$modal}','{$GroupID}')";
	//echo $sql;
	mysql_query($sql);
	//echo "Location:{$FileName}?operate=ExcludeModals&GroupID={$GroupID}";
	Header("Location:{$FileName}?operate=ExcludeModals&GroupID={$GroupID}&process=finish");
	break;
case "DelFromGroup":
	$modal=$_GET['modal'];
	$sql="delete from product_group_items where modal='{$modal}' and GroupID='{$GroupID}'";
	//echo $sql;
	mysql_query($sql);
	//echo "Location:{$FileName}?operate=ExcludeModals&GroupID={$GroupID}";
	Header("Location:{$FileName}?operate=IncludeModals&GroupID={$GroupID}&process=finish");
	break;
case "IncludeModals":
	$sql="select * from product_group_items where GroupID='{$GroupID}'";
	//echo $sql;
	$result=mysql_query($sql);
	?><table width="100%" border="1" cellspacing="0" class="ProductTable"><?
	while($row=mysql_fetch_array($result))
	{
		$modal=$row['modal'];
		$s="select * from product_items where modal='{$modal}'";
		$r=mysql_query($s);
		$p1=mysql_fetch_array($r);
		$p1="../function/PicSize.php?ImgPath=../pic/data/{$p1['path']}&width=50&height=50";
		$p2=mysql_fetch_array($r);
		$p2="../function/PicSize.php?ImgPath=../pic/data/{$p2['path']}&width=50&height=50";
		?>
		<tr>
		  <td width="82%"><?echo $modal;?></td>
		<td width="20%" rowspan="2" align="center"><a href="<?echo $FileName;?>?operate=DelFromGroup&modal=<?echo $modal;?>&GroupID=<?echo $GroupID;?>">&gt;&gt;</a></td>
		</tr>
		<tr>
		  <td width="100%"><img src="<?echo $p1?>"/><img src="<?echo $p2?>"/></td>
		</tr>
		<?
	}
	?></table><?
	break;
case "ExcludeModals":
	$config=file_get_contents("ConfigInfo.inf");
	$brand=GetXMLParam($config,"brand");//获取本地品牌设置
	
	$sql="select modal from product_group_items where GroupID='{$GroupID}'";
	$sql="SELECT * FROM products WHERE brand='{$brand}' and modal NOT IN ({$sql})";
	//echo $sql;
	$result=mysql_query($sql);
	?><table width="100%" border="1" cellspacing="0" class="ProductTable"><?
	while($row=mysql_fetch_array($result))
	{
		$modal=$row['modal'];
		$s="select * from product_items where modal='{$modal}'";
		$r=mysql_query($s);
		$p1=mysql_fetch_array($r);
		$p1="../function/PicSize.php?ImgPath=../pic/data/{$p1['path']}&width=50&height=50";
		$p2=mysql_fetch_array($r);
		$p2="../function/PicSize.php?ImgPath=../pic/data/{$p2['path']}&width=50&height=50";
		?>
		<tr>
		  <td width="20%" rowspan="2" align="center">
		  <a href="<?echo $FileName;?>?operate=AddToGroup&modal=<?echo $modal;?>&GroupID=<?echo $GroupID;?>">&lt;&lt;</a></td>
		<td width="100%"><?echo $modal;?></td>
		</tr>
		<tr>
		  <td width="100%"><img src="<?echo $p1?>"/><img src="<?echo $p2?>"/></td>
		</tr>
		<?
	}
	?></table>
<?
	break;
}
?>
<script>
if("<?echo $_GET['process']?>"=="finish")
{
	if("<?echo $_GET['operate']?>"=="ExcludeModals")
	{
		//alert("gett");
		parent.IncludeList.location="<?echo "{$FileName}?operate=IncludeModals&GroupID={$GroupID}";?>";
	}
	if("<?echo $_GET['operate']?>"=="IncludeModals")
	{
		parent.ExcludeList.location="<?echo "{$FileName}?operate=ExcludeModals&GroupID={$GroupID}";?>";
	}
}
</script>