<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<link href="../function/InterfaceStyle.css" rel="stylesheet" type="text/css" />
<table width="100%" border="1" cellpadding="1">
  <tbody>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="21%">&nbsp;</td>
      <td width="21%">新角色</td>
      <td width="46%"><input style="width:80%;" type="text" id="NewRole"></td>
      <td width="33%" align="center"><input type="button" name="button" onclick="CreateNewRole();" id="button" value="提交"></td>
    </tr>
    <?
    $sql="select * from signup_user_type";
	$result=mysql_query($sql);
	while($row=mysql_fetch_array($result))
	{
		$title="<a href='{$FileName}?operate=EditPopedomView&id={$row[id]}'>{$row[id]}<a>";
		?>
			<tr>
			  <td><?echo $title;?></td>
			  <td><input style="width:80%;" type="text" id="name_<?echo $row[id];?>" value="<?echo $row[name]?>"></td>
			  <td><input style="width:80%;" type="text" id="JumpPage_<?echo $row[id];?>" value="<?echo $row[jump_page]?>"></td>
			  <td><a onclick="return UpdateRoleInfo(this);" id="<?echo $row[id];?>" href="<?echo $FileName."?operate=UpdateRoleInfo&id={$row[id]}";?>">修改</a></td>
			</tr>
		<?
    }
	?>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>
<script src="../../function/jquery.js"></script>
<script>
function UpdateRoleInfo(obj)
{
	//alert(obj.id);
	var name=$("#name_"+obj.id).val();
	var JumpPage=$("#JumpPage_"+obj.id).val();
	var url=obj.href+"&page="+JumpPage+"&name="+name;
	//alert(url);
	location=url;
	return false;
}
function CreateNewRole()
{
	//alert(obj.id);
	var name=$("#NewRole").val();
	var url="<?echo $FileName;?>?operate=CreateNewRole&name="+name;
	//alert(url);
	location=url;
	return false;
}
</script>