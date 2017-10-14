<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<link href="../function/industry.css" rel="stylesheet" type="text/css" />
<?
$GroupID=$_GET['GroupID'];
$operate=$_GET['operate'];
switch($operate)
{
case "CreateNewGroup":
	$name=$_POST['name'];
	$time=time();
	$sql="insert into product_groups (name,time) values('{$name}','{$time}')";
	echo $sql;
	mysql_query($sql);
	Header("Location:{$FileName}");
	return;
case "DelGroup":
	if(strlen($GroupID)>0)
	{
		$sql="delete from product_groups where id='{$GroupID}'";
		mysql_query($sql);
		$sql="delete from product_group_items where GroupID='{$GroupID}'";
		mysql_query($sql);
		
	}
	Header("Location:{$FileName}");
	break;
case "":
	?>
	<form action="<?echo $FileName?>?operate=CreateNewGroup" method="post" name="NewGroupForm" id="NewGroupForm">
	<table width="100%" border="1" cellspacing="0" class="Industry">
	  <tr>
		<td width="26%" height="27">新建类别</td>
		<td width="40%" align="center"><input style="width:100px;" name="name" type="text" id="name" />
		</td>
		<td width="34%" align="center"><input style="width:100px;" name="Submit" type="submit" value="添加" /></td>
	  </tr>
	</table>
	</form>
	<table width="100%" border="1" cellspacing="0" class="Industry">
	<tr>
		<th width="26%">名称</th>
		<th width="41%">产品数量</th>
		<th width="33%">创建时间</th>
	  </tr>
	<?
	$sql="select * from product_groups order by time desc";
	$result=mysql_query($sql);
	$config=file_get_contents("ConfigInfo.inf");
	
	while($row=mysql_fetch_array($result))
	{
		$name=$row['name'];
		$time=date("Y-m-d H:i",$row['time']);
		$id=$row['id'];
		$s="select * from product_group_items where GroupID='{$id}'";
		$r=mysql_query($s);
		$count=mysql_num_rows($r);
		
		$chunk=GetXMLParam($config,"GroupID_{$id}");
		
		?>
	  <tr>
	    <td rowspan="3"><?echo $name;?></td>
	    <td align="center"><?echo $count;?></td>
	    <td align="center" class="TimeText"><?echo $time;?></td>
      </tr>
	  <tr>
		<td colspan="2" align="left"><?echo $chunk;?>&nbsp;</td>
	  </tr>
		<tr>
		<td colspan="2" align="right">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
		  <td align="center"><a onclick="del();" href="<?echo $FileName;?>?operate=DelGroup&GroupID=<?echo $id;?>">删除</a></td>
		  <td align="center">&nbsp;</td>
		  <td align="center"><a href="<?echo $FileName;?>?operate=EditGroup&GroupID=<?echo $id;?>">编辑</a></td>
		  </tr>
		</table>
		
		</td>
		</tr>
		<?
	}
	?>
	</table>
  	<?
	break;
case "ChangeGroupInfo":
	$name=$_GET['name'];
	$sql="update product_groups set name='{$name}' where id='{$GroupID}'";
	mysql_query($sql);
	
	$config=file_get_contents("ConfigInfo.inf");
	$config=SetXMLParam($config,"GroupID_{$GroupID}",$_GET[Page]);
	
	file_put_contents("ConfigInfo.inf",$config);
	
	
	//$brand=GetXMLParam($config,"brand");//获取本地设置
	//$SysName=GetXMLParam($config,"AppName");
	
	
	Header("Location:{$FileName}?operate=EditGroup&GroupID={$GroupID}");
	return;
case "EditGroup":
	$info=GetItemA("product_groups","id",$GroupID);
	//print_r($info);
	$config=file_get_contents("ConfigInfo.inf");
	$WebPage=GetXMLParam($config,"GroupID_{$GroupID}");
	?>
	
	<table width="100%" border="1" cellspacing="0" class="Industry">
      <tr>
        <td colspan="4" class="Industry"><a href="console_group.php">返回</a></td>
      </tr>
      <tr>
        <td class="Industry">页面</td>
        <td colspan="2" align="center" class="Industry">
        <input name="WebPage" type="text" id="WebPage" value="<?echo $WebPage;?>"/></td>
        <td width="33%" rowspan="2" align="center" class="Industry">
		<input name="Submit2" type="submit" onclick="ChangeGroupInfo();" value="修改" />		</td>
      </tr>
      <tr>
        <td class="Industry">分类名称</td>
        <td colspan="2" align="center" class="Industry">
		<input name="GroupName" type="text" id="GroupName" value="<?echo $info['name'];?>"/>
		</td>
      </tr>
	  </table>
	  <table width="100%" border="1" cellspacing="0" class="Industry">
      <tr>
        <th class="Industry">已包含型号</th>
        <th >未包含型号</th>
      </tr>
      <tr>
        <td valign=top style="padding:0px;padding-bottom:0px">
		<iframe id="IncludeList" name="IncludeList" marginwidth=0 marginheight=0 width=100% height="400px" src="modals_menu.php?operate=IncludeModals&GroupID=<?echo $GroupID;?>">
		</iframe></td>
        <td valign=top style="padding:0px;padding-bottom:0px">
		<iframe id="ExcludeList" name="ExcludeList" marginwidth=0 marginheight=0 width=100% height="400px" src="modals_menu.php?operate=ExcludeModals&GroupID=<?echo $GroupID;?>">
		</iframe></td>
      </tr>
    </table>
     <?
	break;

default:
	print_r($_GET);
	return;
}
?>
    
</p>
<script>
function ChangeGroupInfo()
{
	var name=$("#GroupName").val();
	var page=$("#WebPage").val();
	var url="<?echo $FileName;?>"+"?operate=ChangeGroupInfo&GroupID="+"<?echo $GroupID;?>&name="+name+"&Page="+page;
	document.location=url;
}
</script>

	