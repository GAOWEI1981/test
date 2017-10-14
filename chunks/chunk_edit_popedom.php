<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<link href="../function/industry.css" rel="stylesheet" type="text/css" />
<?

$FileName=basename($_SERVER["PHP_SELF"]);
//print_r($_SESSION);
?>

<form id="popedom" name="popedom" method="post" action="<?echo $FileName;?>?operate=AddPopedom&id=<?echo $_GET[id];?>">
  <table width="100%" border="1" cellpadding="0" cellspacing="0" class="Industry">
    <tr>
      <th colspan="3">新权限</th>
    </tr>
    <tr>
      <td width="23%">系统名称</td>
      <td width="50%" align="center"><input style="width:80%;" name="SysName"  type="text" id="SysName"/></td>
      <td width="27%" rowspan="3" align="center">
	  <input type="submit" name="Submit2" value="提交" />
	  </td>
    </tr>
    <tr>
      <td>使用期限</td>
      <td align="center"><input style="width:80%;" name="TimeLimit" type="date" id="TimeLimit"/></td>
    </tr>
    <tr>
      <td>备注</td>
      <td align="center"><input name="remark" style="width:80%;" type="text" id="remark"/></td>
    </tr>
  </table>
</form>
<table width="100%" border="1" cellpadding="1" cellspacing="0" class="Industry">
<tr>
    <th colspan="3">已有权限</th>
  </tr>
  <tr>
    <td width="32%">系统名</td>
    <td width="31%">期限</td>
    <td width="37%">操作</td>
  </tr>
  <?
	$sql="select * from sys_popedom where user_id='{$_GET[id]}'";
	//echo $sql;echo "<br>";
	$result=mysql_query($sql);
	while($row=mysql_fetch_array($result))
	{
		$TimeLimit=date("Y-m-d",$row[time_limit]);
    ?>
  <tr>
    <td><?echo $row[sys_name];?></td>
    <td><?echo $TimeLimit;?></td>
    <td><?echo "<a href='{$FileName}?operate=DelPopedom&RoleID={$_GET[id]}&id={$row[id]}'>删除</a>";?></td>
  </tr>
  <?
	}
	?>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<script src="../../function/jquery.js"></script>
<script>

var ddd = new Date();
var day =ddd.getDate();

if(ddd.getMonth()<10)
	var month = "0"+(ddd.getMonth()+1); 
//alert("eeee");
if(ddd.getDate()<10)
	 day = "0"+ddd.getDate(); 

var datew = (ddd.getFullYear())+"-"+month+"-"+day;
var datew = datew.toString();
$("#TimeLimit").val(datew);
function Defer(obj)
{
	
	try
	{
		var ajax=InitAjax();
		var tt=$(obj.id+"_TL").value;
		var url="<?echo "{$FileName}?operate=Defer&UserID={$UserID}&PopedomID=";?>"+obj.id+"&time="+tt;
		//alert(url);
		ajax.onreadystatechange =function()//回调函数
		{
			if(ajax.readyState==4 && ajax.status==200)
			{
				var text=GetXMLParam(ajax.responseText,"response");
				alert(text);		
			}
		}
		
		ajax.open("GET",url,true);
		ajax.send();
	}
	catch(err)
	{
		alert("javascript失败："+err.description);
	}
	return false;
	
}

</script>
