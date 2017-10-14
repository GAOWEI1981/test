<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<link href="../function/industry.css" rel="stylesheet" type="text/css" />
<?
$operate=$_GET['operate'];
switch($operate)
{
case "LoginExe":
	$user=$_POST['user'];
	$password=$_POST['content'];
	print_r($_POST);
	
	$sql="SELECT * FROM users where name='{$user}'";
	$result=mysql_query($sql);
	
	$ok=false;
	
	for($i=0;$row=mysql_fetch_array($result);$i++)
	{
		if($row['password']==$password)//用户名和密码匹配
		{
			$ok=true;
			break;
		}
	}
	if($ok)
	{
		LogInFile($user." 登陆成功","LoginRecords.txt");
		$t=time();
		$sql="update users set lt='{$t}' where ID='{$row['ID']}'";
		//LogInFile($sql,"LoginRecords.txt");
		mysql_query($sql);//记录登陆时间
		$_SESSION['adminID']=$row['ID'];//传递当前管理员ID
		if($row['UserType']=="super" || $row['UserType']=="employee")
		{
			$_SESSION['adminName']=$user;
		}

		
		
		if(!isset($TargetPage))
		{
			//echo "2222";
			header("Location:console.php");
		}
		else
		{
			//echo "<br>{$TargetPage}";
			header("Location:{$TargetPage}");//跳到指定页面
		}
		return;
	}
	else
		Header("Location:Login.php?err=用户名或密码错误！");
	return;
case "":
	//echo "gett";
	//echo "eeee{$FileName}";
	//session_unset();
	//session_destroy();
	break;
}

$SendCodeUrl="{$FileName}?operate=SendIdentifyingCode";
$RegisterUrl="{$FileName}?operate=RegisterExe";
?>

<div class="weui_cells_title"><?if(strlen($_GET[err])==0) echo "注册";else echo $_GET[err];?>&nbsp;</div>
<form action="" method="post"  target="SendWnd" id="Register" name="Register" align="center">
    <div class="weui_cells weui_cells_form" style="margin-top:5px;">
        <div class="weui_cell">
            <div class="weui_cell_hd" style="width:30%;"><label class="weui_label">手机号</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" name="phone" id="phone" type="text" placeholder="请输入手机号码"/>
            </div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_hd" style="width:30%;"><label class="weui_label">验证码</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" name="IdentifyingCode" id="IdentifyingCode" type="text" placeholder="请输入验证码"/>
            </div>
            <div class="weui_cell_hd">
            <a onclick="return SendCode(this);" class="weui_btn weui_btn_primary weui_btn_mini" style="height:50%;" name="GetCodeButton" id="GetCodeButton">获取</a>
            </div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_hd" style="width:30%;"><label class="weui_label">密码</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" name="password" id="password" type="text" placeholder="请输入密码"/>
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
    <td width="49%"><a class="weui_btn weui_btn_primary" onclick="RegisterExe();" href="javascript:">注册</a>
    </div>
</form>
<iframe src="" id="SendWnd" name="SendWnd" style="width:100%;height:0px;display:none;">
</iframe>
<script src="../../function/jquery.js"></script>
<script type="text/JavaScript">
var time=60;
var timeid=0;
function RegisterExe()
{
	try
	{
		//var text = id.getAttribute("id");
		//alert(text);
		var ele=document.getElementById('password');
		var ic=document.getElementById('IdentifyingCode');
		var phone=document.getElementById('phone');
		if(ele.value.length==0 || ic.value.length==0 || phone.value.length==0)
		{
			alert("手机号码、验证码、密码不能为空！");
		}
		else
		{
			h=hash(ele.value);
			ele.value="";
			var ele=document.getElementById('content');
			//alert(h);
			ele.value=h;
			
	
			document.Register.action="<?echo $RegisterUrl;?>";
			//alert(document.Register.action);
			document.Register.target="";
			$("#Register").submit();
		}
		
	}
	catch(err)
	{
		alert("catch:"+err);
	}
  return true;
}

function SendCode(obj)
{
	try
	{
		var phone=document.getElementById("phone").value;
		if(phone.length==0)
		{
			alert("手机号不能为空！");
			return;
		}
		document.Register.action="<?echo $SendCodeUrl;?>";
		//alert(document.Register.action);
		$("#Register").submit();
		obj.value="已发送";
		obj.disabled=true;
		SetTimer();
		
	}catch(e)
	{
		alert(e);
	}
	return false;
}
function SetTimer()
{
	time=60;
	timeid= window.setInterval(TimeStep,1000);
	//window.clearInterval(timeid); 
}
function TimeStep()
{
	var bu=document.getElementById("GetCodeButton");
	bu.innerText="已发送"+time;
	time--;
	if(time<=0)
	{
		window.clearInterval(timeid); 
		bu.value="重新\n获取";
		bu.disabled=false;
	}
}
</script>