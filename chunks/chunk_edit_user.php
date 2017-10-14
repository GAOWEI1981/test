<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<link href="../function/industry.css" rel="stylesheet" type="text/css" />
<?

$FileName=basename($_SERVER["PHP_SELF"]);
$UserInfo=GetItemA("signup_users","openid",$_GET[id]);
//print_r($UserInfo);echo "<br>";
$name=$UserInfo['name'];
//$UserType=$UserInfo['UserType'];
//$HomePage=$UserInfo['page'];
//$brand=$UserInfo['brand'];
?>
<form action="<?echo $FileName;?>?operate=ChangeUserInfo&id=<?echo $UserInfo[openid];?>" method="post" name="UserEdit" class="Industry" id="UserEdit">
  <table width="100%" border="1" cellspacing="0">
    <tr>
      <td colspan="2" align="left">
      <table width="100%" border="1" cellpadding="2">
<tr>
  <td width="28%">推荐人</td>
  <td width="72%">
    <?
			
            $item=GetItemA("signup_users","openid",$UserInfo[owner]);
			//print_r($item);
            if(strlen($item[name])==0)
			{
                $boss=$UserInfo[owner];
				if(strlen($boss)==0) $boss="无";
			}
            else $boss=$item[name];
            echo $boss;
            ?>
    </td>
</tr>
        </table>
      </td>
    </tr>
    <tr>
      <td colspan="2"><?echo $_GET[id];?>&nbsp;</td>
    </tr>
    <tr>
      <td width="44%">姓名</td>
      <td width="56%" align="left">
        <input name="user" type="text" id="user" value="<?echo $name;?>" />      </td>
    </tr>
    <tr>
      <td>电话</td>
      <td align="left"><?echo $UserInfo[phone];?></td>
    </tr>
    <tr>
      <td>用户类型</td>
      <td align="left">
        <select name="RoleID" id="RoleID">
        <?
        $sql="select * from signup_user_type";
		$result=mysql_query($sql);
		while($row=mysql_fetch_array($result))
		{
			if($row[id]==$UserInfo[user_type])
				echo "<option selected='selected' value='{$row[id]}'>{$row[name]}</option>";
			else
				echo "<option value='{$row[id]}'>{$row[name]}</option>";
		}
		?>
        
      </select></td>
    </tr>
    <tr>
      <td>密码</td>
      <td align="left"><label>
        <input name="ps" type="text" id="ps" />
      </label></td>
    </tr>
    <tr>
      <td colspan="2" align="center">
          <input type="submit" name="Submit" onclick="Apply();" value="提交" />      </td>
    </tr>
  </table>
</form>
<script>
function Apply()
{
	try
	{
	
		
		//ele=document.getElementById('password');
		ps=$("ps").value;
		//alert(ps);
		if(ps.length>0)
		{
			ps=hash(ps);//哈希值
			//alert(ps);	
			$("ps").value=ps;
		
		}
		

	}catch(e)
	{
		alert(e);
	}
	return true;
}
</script>