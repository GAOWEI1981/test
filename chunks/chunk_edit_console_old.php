<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<link href="../function/industry.css" rel="stylesheet" type="text/css" />

<?
//款式条目总数
//echo $sql;

if(!isset($PicSize))
	$PicSize=80;
switch($operate)
{
case "SetTop":
	$modal=$_GET["modal"];
	if(strlen($modal)>0)
	{
		$time=time();
		$sql="update products set time='{$time}' where modal='{$modal}'";
		echo $sql;
		mysql_query($sql);
	}
	Header("Location:{$FileName}");
	return;
case "DelModal":
	$modal=$_GET["modal"];
	//echo $modal;
	//if(strlen($modal)>0)
	{
		$sql="delete from products where modal='{$modal}'";
		mysql_query($sql);
		$sql="delete from product_items where modal='{$modal}'";
		mysql_query($sql);
		$sql="delete from product_group_items where modal='{$modal}'";
		mysql_query($sql);
		
		$sql="delete from orders where ID in (select OrderID from account_book where product='{$modal}' GROUP by OrderID)";
		mysql_query($sql);
		
		$sql="delete from account_book where product='{$modal}'";
		mysql_query($sql);
		
	}
	Header("Location:{$FileName}");
	
	return;
case "":
	break;
case "CreateNewModal":
	$config=file_get_contents("ConfigInfo.inf");
	$brand=GetXMLParam($config,"brand");//获取本地设置
	
	$name=$_POST["name"];
	$time=time();
	$sql="insert into products (modal,time,brand) values('{$name}','{$time}','{$brand}')";
	mysql_query($sql);
	Header("Location:{$FileName}");
	return;
default:
	return;
}
?>
<form action="<?echo $FilaName?>?operate=CreateNewModal" method="post" name="NewModalForm" class="ProductTable" id="NewModalForm">
  <table width="100%" border="1" cellspacing="0" class="Industry">
    <tr>
      <td height="13" align="center"><input style="width:80%;" name="keyword" type="text" id="keyword" /></td>
      <td height="13" align="center"><input name="Submit2" type="submit" onclick="return Search();" value="查找" /></td>
    </tr>
    <tr>
      <td height="14" colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td height="27" align="center">
      <input style="width:80%;" name="name" type="text" id="name"/>      </td>
      <td width="34%" align="center"><input name="Submit" type="submit" onclick="return CreateModal();" value="添加新型号" /></td>
    </tr>
  </table>
</form>
<table width="100%" border="1" align="center" cellspacing="0" class="Industry">
<?
//echo $sql;echo "<br>";

$result=mysql_query($sql);
$res =  mysql_query("select found_rows()");
$ItemCount = mysql_result($res,0);
//echo $PicSize;
//echo "gettt";
//所有图片
//print_r($_SESSION);echo "<br>";
$result=mysql_query("select a.*,b.name as UserTypeName from signup_users a,signup_user_type b where a.user_type=b.id and a.openid='{$_SESSION[adminID]}'");
//echo $result;
$UserInfo=mysql_fetch_array($result);
//print_r($UserInfo);echo "<br>";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
	//echo "sfsdf<br>";
	$modal=$row['modal'];
	$count=$row["ItemCount"];
?>
<tr>
    <td height="24" align="center" valign="middle"><table width="100%" border="0" cellpadding="1" cellspacing="0" class="Industry">
<tr>
          <th width="51%" align="left"><?echo $modal." 数量:".$count;?>&nbsp;</th>
          <th width="49%" align="center">
          <select name="select" onChange="Operate(this);">
	          <option selected>选项</option>
	          <option value="<?echo $FileName?>?operate=ProductEditPage&modal=<?echo $modal;?>">基础设置</option>
	          <option value="page_eidt_content_in_pay_page.php?modal=<?echo $modal;?>">商城设置</option>
	          <option value="<?echo $FileName?>?operate=SetTop&modal=<?echo $modal;?>">置顶</option>
              <?
              if($UserInfo[UserTypeName]=="权限管理员")
			  {
				  ?>
				  <option value="<?echo $FileName;?>?operate=DelModal&modal=<?echo $modal;?>">删除</option>
				  <?
			  }
			  ?>
          </select></th>
        </tr>
        <tr>
          <td colspan="2" align="center"><iframe id="<?echo $modal;?>_frame" name="<?echo $modal;?>_frame" width="90%" height="<?echo $PicSize*1.1;?>px;" src="modal_pics.php?operate=ListAllPics&product=<?echo $modal;?>&PicSize=<?echo $PicSize;?>"> </iframe></td>
        </tr>
        
    </table></td>
  </tr>
	<tr>
	<td height="24" align="center" valign="middle">&nbsp;</td>
</tr>
<?
}
//页面跳转按钮们
$PageCount=ceil($ItemCount/$PageSize);
$PageIndex=$PageOffset/$PageSize;
$page=$PageOffset+$PageSize;
if($page<$ItemCount)
	$NextPage="<a href='{$FileName}?PageOffset={$page}&keyword={$_GET[keyword]}'>下一页</a>";
else $NextPage="<a href='{$FileName}?PageOffset={$PageOffset}&keyword={$_GET[keyword]}'>下一页</a>";

$page=$PageOffset-$PageSize;
if($page>=0)
	$LastPage="<a href='{$FileName}?PageOffset={$page}&keyword={$_GET[keyword]}'>上一页</a>";
else $LastPage="<a href='{$FileName}?PageOffset=0&keyword={$_GET[keyword]}'>上一页</a>";
//***************
//页码串
$PageIndex+=1;
$Pages="";
$PageFrom=$PageIndex-2;
if($PageFrom<1) $PageFrom=1;
for($i=$PageFrom;$i<=$PageIndex+2 && $i<=$PageCount;$i++)
{

	$pos=($i-1)*$PageSize;
	$index=substr($i+100,1,2);
	$Pages.="<a href='{$FileName}?PageOffset={$pos}'>-{$index}-</a> ";
}
?>
</table>
<table width="100%" border="1" cellspacing="0" class="ProductTable">
  <tr>
    <th><table width="100%" border="0" cellpadding="1">
<tr>
          <td align="center"><?echo "{$LastPage}";?>&nbsp;</td>
          <td align="center"><?echo "<{$PageIndex}/{$PageCount}>";?>&nbsp;</td>
          <td align="center"><?echo "{$NextPage}";?>&nbsp;&nbsp;</td>
        </tr>
      
    </table></th>
  </tr>
  <tr>
    <th>&nbsp;</th>
  </tr>
</table>
</body>
</html>
<script>
function CreateModal()
{
	var obj=document.getElementById("name");
	if(obj.value.length==0)
	{
		alert("名称不能为空");
		return false;
	}
	return true;
}
function Search()
{
	var obj=document.getElementById("keyword");
	var keyword=obj.value;
	url="<?echo $FileName.'?keyword=';?>"+keyword;
	//alert(url);
	location=url;
		
	
	//else alert("不能为空！");
	return false;
}
function Operate(obj)
{
	var index=obj.selectedIndex;
	var url=obj.options[index].value;
	var text=obj.options[index].text;
	if(text=="删除" && confirm("确认删除吗？")==false)
	{
		return;
	}
	location=url;
	
}
</script>