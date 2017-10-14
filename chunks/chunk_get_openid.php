<?
//为了获取用户ID
if(strlen($UserInfoUrl)==0) echo "openid获取地址未设置<br>";
//if(strlen($_SESSION[openid])==0) echo "当前用户id未获取<br>";
$time=time();
if(strlen($_SESSION[openid])==0)
{
	//<UserInfoUrl>http://lanxiao.ghostinmetal.com/WeixinPayService/page_get_user_info.php?operate=GetUserInfo</UserInfoUrl>
	if(strlen($_GET[openid])==0)
	{
		$ReturnUrl="http://".$_SERVER[HTTP_HOST]."{$_SERVER["PHP_SELF"]}";//返回的链接
		//echo $ReturnUrl;echo "<br>";return;
		$url="{$UserInfoUrl}&ReturnUrl={$ReturnUrl}&OutState=1234";

		echo $ReturnUrl;echo "<br>";
		echo $url;echo "<br>";
		Header("Location:{$url}");
		//echo $UserInfoUrl;echo "<br>";
	}
	else
	{
		$time=time();
		$_SESSION[openid]=$_GET[openid];
		$sql="insert into signup_users (openid,time,user_type,owner) values('{$_SESSION[openid]}','{$time}','2','{$_SESSION[WebOwner]}')";
		LogInFile($sql,"sql.txt");
		mysql_query($sql);
	}
	//echo "sdfdsfd";return;
}
else
{

	$sql="insert into signup_users (openid,time,user_type,owner) values('{$_SESSION[openid]}','{$time}','2','{$_SESSION[WebOwner]}')";
	//LogInFile($sql,"sql.txt");
	mysql_query($sql);
}
?>