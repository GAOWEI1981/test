<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<link href="../function/industry.css" rel="stylesheet" type="text/css" />
<?
switch($operate)
{
case "ProductEditPage":
	?>
    <table width="100%" border="1" cellpadding="1" cellspacing="0" class="Industry">
    <tr>
    <th align="left"><a href="console.php">返回</a></th>
    <th>&nbsp;</th>
    </tr>
    </table>

	<?
	include_once("chunks/chunk_console_title.php");
	$modal=$_GET['modal'];
	$item=GetItemA("products","modal",$modal);
	break;
case "ExeChange":
	//print_r($_GET);
	//print_r($_POST);
	//return;
	$modal=$_GET["modal"];
	$title=$_POST["title"];
	$detail=$_POST["detail"];
	if(strlen($modal)>0)
	{
		$sql="update products set title='{$title}',modal='{$_POST[modal]}',detail='{$detail}',remark='{$_POST[remark]}' where modal='{$modal}'";
		mysql_query($sql);
		echo $sql;echo "<br>";
		$sql="update product_items set modal='{$_POST[modal]}',name='{$_POST[modal]}' where modal='{$modal}'";
		echo $sql;echo "<br>";
		mysql_query($sql);
		//Header("Location:{$FileName}?operate=ProdunctEditPage&modal={$_POST[modal]}");
	}
	
	Header("Location:{$FileName}?operate=ProductEditPage&modal={$modal}");
	
	return;
case "ChangePicName":
	$id=$_GET["ItemID"];
	$name=$_GET["name"];
	$sql="update product_items set color='{$name}' where id='{$id}'";
	echo "<response>";
	if(mysql_query($sql)==true)
		echo "修改成功！";
	else echo "修改失败！";
	echo "</response>";
	return;
case "SetPicAsCover":
	if(strlen($_GET[ItemID])>0)
	{
		$sql="select *,min(time) as EarlyTime from product_items where modal='{$_GET[modal]}' group by modal";
		//echo $sql;
		//print_r($_GET);echo "<br>";
		$result=mysql_query($sql);
		$row=mysql_fetch_array($result);
		
		$time=$row[EarlyTime]-10;
		$sql="update product_items set time='{$time}' where id='{$_GET[ItemID]}'";
		//echo $sql;echo "<br>";
		//print_r($row);
		mysql_query($sql);
	}
	Header("Location:{$FileName}?operate=ProductEditPage&modal={$_GET[modal]}");
	return;
case "DelProductPicItem":
	$modal=$_GET['modal'];
	$id=$_GET["ItemID"];
	if(strlen($id)>0)
	{
		/*
		//删除文件
		$it=GetItemA("product_items","id",$id);
		$path="../pic/data/{$it['path']}";
		unlink($path);
		*/
		//删除数据库条目
		$sql="delete from product_items where id='{$id}'";
		mysql_query($sql);
		//echo $sql;
		
	}
	Header("Location:{$FileName}?operate=ProductEditPage&modal={$modal}");
	return;
case "AddNewPic":
	//print_r($_FILES);
	//echo "<br>";
	$modal=$_GET['modal'];
	//return;
	if(isset($_FILES))
	{

		$TempFilePath=$_FILES["PicFile"]["tmp_name"];
		$FileType=$_FILES["PicFile"]["type"];
		LogInFile($FileType,"FileType.txt");
		//if(strpos($FileType,"image",0)!==false)//只接受图片文件
		{
			$PicFileName=time()."-".$modal."-".CreateID(6).".jpg";
			$path="../pic/data/{$PicFileName}";
			$file=file_get_contents($TempFilePath);
			$hash=md5($file);//哈希值
			//echo "{$path}<br>";
			//return;
			move_uploaded_file($TempFilePath,$path);//下载文件
			//echo $_FILES["PicFile"]["tmp_name"];
			//echo "{$path}<br>";
			//return;
			$time=time();
			$sql="insert into product_items (color,modal,name,path,time,hash) 
					values('{$_POST['name']}','{$modal}','{$modal}','{$PicFileName}','{$time}','{$hash}')";
			mysql_query($sql);
			//echo $sql;
			//echo "<br>";
			//return;
		}
	}
	Header("Location:{$FileName}?operate=ProductEditPage&modal={$modal}");
	return;
default:
	return;
}
//print_r($item);
?>
<form action="<?echo $FilaName;?>?operate=ExeChange&modal=<?echo $modal;?>" method="post" enctype="multipart/form-data" name="ProductInfo" id="ProductInfo">
  <table width="100%" border="1" cellspacing="0" class="Industry">
    <tr>
      <th colspan="2">内容</th>
    </tr>
    <tr>
      <td width="19%"><div align="center">型号</div></td>
      <td width="60%" align="center">
      <input id='modal' name='modal' type="text" value='<?echo $modal;?>'/></td>
    </tr>
    <tr>
      <td align="center"><div align="center">标题</div></td>
      <td align="center"><input name="title" type="text" id="title" value="<?echo $item[title];?>"/>      </td>
    </tr>
    <tr>
      <td align="center">备注</td>
      <td align="center"><input name="remark" type="text" id="remark" value="<?echo $item[remark];?>"/></td>
    </tr>
    <tr>
      <td colspan="2" align="center">详细</td>
    </tr>
    <tr>
      <td colspan="2" align="center">
        <textarea name="detail" id="detail"><?echo $item["detail"];?></textarea>		</td>
    </tr>
    <tr>
      <td colspan="2"><div align="center">
        <input type="submit" name="Submit" value="提交" />
      </div>	  </td>
    </tr>
  </table>
</form>
<form action="<?echo $FilaName?>?operate=AddNewPic&modal=<?echo $modal;?>" method="post" enctype="multipart/form-data" name="NewPicForm" class="Industry" id="NewPicForm">
  <table width="100%" border="1" cellspacing="0" class="Industry">
    <tr>
      <th colspan="3">新增图片</th>
    </tr>
    <tr>
      <td width="31%">图片路径</td>
      <td width="53%" align="left"><input type="file" name="PicFile" style="width:80%;"/></td>
      <td width="16%" rowspan="2" align="center"><input style="width:80px;" type="submit" name="Submit2" value="提交"/></td>
    </tr>
    <tr>
      <td >名称</td>
      <td align="left">        <input type="text" name="name" style="width:100px;"/>        </td>
    </tr>
  </table>
</form>
<?
$sql="select * from product_items where modal='{$modal}' order by time desc";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
	include("chunks/chunk_edit_pic_info.php");
}
?>