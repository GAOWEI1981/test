<?php
ob_start();
error_reporting(E_ALL & ~E_NOTICE);
if(!isset($_SESSION)) session_start();
include_once("../function/config.php");
include_once("../function/JSSDK.php");
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

$operate=$_GET[operate];
switch($operate)
{
case "":
	//print_r($_GET);echo "<br>";
	print_r($_POST);echo "<br>";
	Header("Location:{$FileName}?operate=WebLogin&OrderID={$_GET[out_trade_no]}");
	//Header("Location:page_orders_user.php");
	break;
case "WebLogin":
	$OrderInfo=GetItemA("orders","ID",$_GET[OrderID]);
	print_r($OrderInfo);echo "<br>";
	$_SESSION[adminID]=$OrderInfo[creater];
	
	//print_r($_GET);echo "<br>";
	//print_r($_POST);echo "<br>";
	//Header("Location:{$FileName}?operate=WebLogin&OrderID={$_GET[out_trade_no]}");
	Header("Location:page_orders_user.php");
	break;
}
?>
