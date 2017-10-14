<link href="../function/industry.css" rel="stylesheet" type="text/css" />
<?
//echo $row['path'];
//echo "<br>";
$path="../function/PicSize.php?ImgPath=../pic/data/{$row['path']}&width=100&height=100";
//http://www.90worldengine.com/sys_cloth_orders/sys_products/ModalPage.php?id=138&Modal=513AB
echo "sdfsdfsd";
$pic="<a href='pic_view.php?id={$row['id']}&PageOffset={$PageOffset}'><img src='{$path}'></img></a>";
?>
<table width="100%" border="1" cellspacing="0" class="Industry">
  <tr>
    <td width="29%" rowspan="2"><div align="center"><?echo $pic;?></div></td>
    <td width="14%"><div align="center">名称</div></td>
    <td width="37%">
        <div align="center">
          <input type="text" id="<?echo $row['id'];?>" style="width:90px;" value="<?echo $row['color']?>"/>
        </div>	</td>
    <td width="20%">      
      <div align="center">
        <input name="Submit" type="button" style="width:60px;" onclick="return SetTitle(<?echo $row['id'];?>);" value="修改" />    
      </div></td>
  </tr>
  <tr>
    <td colspan="3" align="right">
        <table width="100%" border="0" cellpadding="1" cellspacing="0">
<tr>
  <td width="49%" align="center"><a href="<?echo $FileName;?>?operate=SetPicAsCover&ItemID=<?echo $row['id'];?>&modal=<?echo $row['modal'];?>">设为封面</a></td>
  <td width="51%" align="center">
    <a href="<?echo $FileName;?>?operate=DelProductPicItem&ItemID=<?echo $row['id'];?>&modal=<?echo $row['modal'];?>" onclick="return del();">删除</a></td>
</tr>
        </table>
	  </td>
  </tr>
</table>
<script>
function SetTitle(id)
{

	try
	{
		var ajax=InitAjax();
		var obj=document.getElementById(id);
		var url="<?echo $FilaName?>?operate=ChangePicName&name="+obj.value+"&ItemID="+id;
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
		ajax.send()
	}
	catch(err)
	{
		alert("javascript失败："+err.description);
	}
	return false;
}
</script>
