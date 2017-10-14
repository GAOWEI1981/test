<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<title>我的账户</title>
<link rel="stylesheet" href="../function/industry.css"/>
<link rel="stylesheet" href="../function/weui/dist/style/weui.css"/>
<link rel="stylesheet" href="../function/weui/dist/style/weui.min.css"/>
</head>
<body ontouchstart>

<?
ob_start();
error_reporting(E_ALL & ~E_NOTICE);
if(!isset($_SESSION)) session_start();
include_once("../function/config.php");
include_once("../function/functions_mall.php");
include_once("LocalConfig.php");
include_once("../function/Script.php");


$operate=$_GET['operate'];
//**************/
//echo $operate;
$FileName=basename($_SERVER["PHP_SELF"]);
if($Database->Connect()==false)
{
	echo "db connect false!";
	return;
}

mysql_select_db($DatabaseName);

//include_once("chunks/chunk_title.php");

$UserInfo=GetItemA("signup_users","openid",$_SESSION[adminID]);
if(strlen($UserInfo[phone])==0)
{
	$_SESSION[adminID]="";
	Header("Location:page_user_login.php");
}
$BackUrl="index.php";
$Icon="../icon/icon_me.png";$IconWidth="25px";
include_once("chunks/chunk_back_bar.php");
switch($operate)
{
case "Loginout":
	session_unset();
	session_destroy();
	Header("Location:index.php?err=注销成功");
	return;
case "":
	$sql="select ID from orders where creater='{$_SESSION[adminID]}'";
	$sql="select * from account_book where OrderID in({$sql}) and type<>'loan' and type<>'Loan'";
	$sql="select sum(cast(a.gross as decimal(8,3))) as total from ({$sql}) a group by a.creater";
	//echo $sql."<br>";
	$result=mysql_query($sql);
	$r=mysql_fetch_array($result);
	//记录登陆时间
	$t=time();
	$sql="update signup_users set login_time='{$t}' where openid='{$_SESSION[adminID]}'";
	//LogInFile($sql,"LoginRecords.txt");
	mysql_query($sql);//记录登陆时间
	break;
	
	
}
$LocalRebate=new RebateConfig("ConfigInfo.inf");
$t=$LocalRebate->GetLastRebate($_SESSION[adminID]);

$t=$t[time];//获取最近一次分红时
$RebateDetail=$LocalRebate->GetRebateInfo($_SESSION[adminID],$t);
//print_r($RebateDetail);echo "<br>";
//include_once("chunks/chunk_foot.php");
?>
<div class="weui_cells_title">个人信息</div>
<div class="weui_cells weui_cells_split">
	<div class="weui_cell">
        <div class="weui_cell_hd"><img src="../icon/icon_me.png" alt="" style="width:20px;margin-right:5px;display:block"></div>
        <div class="weui_cell_bd weui_cell_primary">
            <?
			echo $UserInfo[openid];
			?>
        </div>
    </div>
	 <div class="weui_cell">
        <div class="weui_cell_hd"><img src="../icon/icon_me.png" alt="" style="width:20px;margin-right:5px;display:block"></div>
        <div class="weui_cell_bd weui_cell_primary">
            <?echo $UserInfo[name];?>
        </div>
        <div class="weui_cell_hd"><img src="../icon/icon_phone.png" alt="" style="width:20px;margin-right:5px;display:block"></div>
        <div class="weui_cell_bd weui_cell_primary">
            <?echo $UserInfo[phone];?>
        </div>
    </div>
    <div class="weui_cell" style="display:none;">
        <div class="weui_cell_hd"><img src="../icon/icon_hand_shake.png" alt="" style="width:25px;margin-right:5px;display:block"></div>
        <div class="weui_cell_bd weui_cell_primary">
            <?
			$item=GetItemA("signup_users","openid",$UserInfo[owner]);
			if(strlen($item[name])==0)
	            $boss=$UserInfo[owner];
			else $boss=$item[name];
			echo $boss;
			?>
        </div>
    </div>
    <div class="weui_cell">
        <div class="weui_cell_hd"><img src="../icon/icon_time.png" alt="" style="width:20px;margin-right:5px;display:block"></div>
        <div class="weui_cell_bd weui_cell_primary">
            <?echo date("Y-m-d H:i",$UserInfo[time]);?>
        </div>
    </div>
</div>

<div class="weui_cells_title">账户概况</div>
<div class="weui_cells">
    <div class="weui_cell">
        <div class="weui_cell_bd weui_cell_primary">
            <table width="100%" border="0" cellpadding="1">
              <tbody>
                <tr>
                  <td align="center"><?echo $r[total];?></td>
                  <td align="center">
                  <?
                  echo $RebateDetail[OnePayment]+$RebateDetail[TwoPayment];
				  ?>&nbsp;</td>
                  <td align="center">
                  <?
				  
                  $gross=$RebateDetail[OneRebate]+$RebateDetail[TwoRebate];
				  echo $gross;
				  ?>&nbsp;</td>
                </tr>
                <tr>
                  <td width="32%" align="center">个人消费</td>
                  <td width="39%" align="center">营业额</td>
                  <td width="29%" align="center">推广提成</td>
                </tr>
              </tbody>
            </table>
      </div>
        
    </div>
</div>

<div class="weui_cells_title">功能</div>
<div class="weui_cells">
    <div class="weui_cell">
        <div class="weui_cell_bd weui_cell_primary">
            <table width="100%" border="0" cellpadding="1">
            <tr>
            <td width="32%" align="center">
            	<a class="weui-form-preview__btn weui-form-preview__btn_default" href="page_user_login_admin.php?operate=logout" onclick="return CheckLogin();">
                <table width="100%" border="0" cellpadding="2">
                <tr>
                <td align="center">
                <img src="../icon/icon_config.png" alt="" style="width:30px;" /></td>
                </tr>
                <tr>
                <td align="center">后台登陆</td>
                </tr>
                </table></a>

          </td>
          <td width="39%" align="center">
          		<a class="weui-form-preview__btn weui-form-preview__btn_default" href="index.php?owner=<?echo $_SESSION[adminID];?>">
                <table width="100%" border="0" cellpadding="2">
                <tr>
                <td align="center">
                <img src="../icon/icon_my_web.png" alt="" style="height:30px;" /></td>
                </tr>
                <tr>
                <td align="center">我的网站</td>
                </tr>
                </table></a>
           </td>
          <td width="29%" align="center">&nbsp;</td>
          </tr>
            </table>
      </div>
        
    </div>
</div>
<?
$UsersInfo=new UsersDatabase();
$UsersInfo=$UsersInfo->GetUsersInfo($_SESSION[adminID]);
//print_r($UsersInfo);echo "<br>";
?>
<div class="weui_cells_title">我的会员</div>
<div class="weui_cells  weui_cells_access" style="margin-top:5px">
    <a class="weui_cell" href="page_my_agents.php">
        <div class="weui_cell_bd weui_cell_primary">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
            <td width="11%"><img src="../icon/icon_us.png" alt="" style="height:30px;" /></td>
            <td width="42%" align="center">
            <?
			$value=$UsersInfo[OneTotalNum]+$UsersInfo[TwoTotalNum];
            echo "总会员:{$value}";
			?>&nbsp;</td>
            <td width="47%" align="center">
            <?
			$value=$UsersInfo[OneRegisterNum]+$UsersInfo[TwoRegisterNum];
            echo "注册会员:{$value}";
			?>&nbsp;</td>
            </tr>
            </table>
    </div>
        <div class="weui_cell_ft">

    </div>
    </a>
</div>
<div class="weui_cells" style="margin-top:5px">
    <a class="weui_cell" href="<?echo $FileName."?operate=Loginout";?>" onclick="return confirm('确定要退出登录状态吗？');">
        <div class="weui_cell_bd weui_cell_primary">
        </div>
        <div class="weui_cell_ft"><img src="../icon/icon_logout.png" alt="" style="width:20px;">
        </div>
    </a>
</div>

</body>
</html>
<script>
function CheckLogin()
{
	var admin="<?echo $_SESSION[adminID];?>";
	if(admin.length>0)
	{
		if(confirm("跳转到后台将退出当前登陆，是否继续？")==true)
		{
			return true;
		}
	}
	else return true;
	return false;
}
</script>