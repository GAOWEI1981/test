<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<title>关于我们
</title>
<link href="../function/industry.css" rel="stylesheet" type="text/css" />
</head>
<body ontouchstart>
<?
error_reporting(E_ALL & ~E_NOTICE);
if(!isset($_SESSION)) session_start();

include_once("../function/config.php");
include_once("LocalConfig.php");
include_once("../function/Script.php");

/*
$data->Brand = "三恒";
$data->AppName = "products_sanheng";
$fp = fopen("ConfigInfo.inf", "w");
fwrite($fp, json_encode($data));
fclose($fp);
*/
if($Database->Connect()==false)
{
	echo "db connect false!";
	return;
}
mysql_select_db($DatabaseName);
//LogInFile("visit","Visit.txt");//统计访问量
$PageWidth="100%";
if($_SESSION[PageModal]=="computer")
{
	$PageWidth="600px";
}
include_once("chunks/chunk_title.php");


$phone="07792248295";
?>
<div class="weui_cells_title">联系电话</div>
<div class="weui_cells weui_cells_access" style="margin-top:5px">
    <a class="weui_cell" href="tel:<?echo $phone;?>">
        <div class="weui_cell_bd weui_cell_primary">
            <p><?echo $phone;?></p>
        </div>
        <div class="weui_cell_ft">
        </div>
    </a>
<?$phone="13557298996";?>
    <a class="weui_cell" href="tel:<?echo $phone;?>">
        <div class="weui_cell_bd weui_cell_primary">
            <p><?echo $phone;?></p>
        </div>
        <div class="weui_cell_ft">
        </div>
    </a>
</div>
<div class="weui_cells_title">地址</div>
<div class="weui_cells">
    <div class="weui_cell">
        <div class="weui_cell_bd weui_cell_primary">
            <p>广西北海市合浦县廉州镇东城北路64号</p>
        </div>
        
    </div>
</div>
                
</body>
</html>
