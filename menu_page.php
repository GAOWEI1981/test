<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<link href="../function/industry.css" rel="stylesheet" type="text/css" />
<html>
<body class="BodyNoSpace">
<?
$i=0;
?>
<table border="1" cellpadding="0" cellspacing="0" id="MoveChunk" class="Industry" height="125%">
  <tr>
<td align="center"><?$i++;?><a href="<?echo "index.php?operate=SwitchViewModal&view=";?>">首页</a></td>
<?
error_reporting(E_ALL & ~E_NOTICE);
if(!isset($_SESSION)) session_start();

include_once("../function/config.php");
include_once("../function/JSSDK.php");
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

$MenuSql="select * from product_groups";
$result=mysql_query($MenuSql);
while($row=mysql_fetch_array($result))
{
	?>
	<td align="center"><a href="<?echo "index.php?operate=SwitchViewModal&view=ViewProductGroup&GroupID={$row[id]}";?>"><?echo $row[name];?></a>&nbsp;</td>
	<?
	$i++;
}
?>
<td align="center"><?$i++;?>后台</td>
</tr> 
</table>
y
</body>
</html>
<script src="../../function/jquery.js"></script>
<script>
try
{
navigator.control.gesture(false);	
}catch(e)
{
	alert(e);
}
var OrgPos=0;
var ColumnWidth=$(document).width()/3;
$("#MoveChunk").width(ColumnWidth*parseInt("<?echo $i;?>"));
$("#MoveChunk").find("td").width(ColumnWidth);
$("#MoveChunk").find("a").click(
function()
{
	window.parent.location=this.href;
	return false;
});
function ShowString(str)
{
	var ele=window.parent.document.getElementById("TextOut");
	ele.innerHTML=str;
}
function touches(ev)
{ 
	switch(ev.type)
	{ 
		case "touchstart":
			StartX=parseInt(ev.changedTouches[0].clientX);
			StartY=parseInt(ev.changedTouches[0].clientY); 
			ev.stopPropagation();
			break; 
		case "touchend":
			OrgPos=$(document).scrollLeft();
			break; 
		case "touchmove": 
		
			var PosX=parseInt(ev.changedTouches[0].clientX);
			var PosY=parseInt(ev.changedTouches[0].clientY);
			//ShowString(PosX+"<>"+PosY);
			
			var mx=PosX-StartX;
			var my=PosY-StartY;
			

			var ScrollPos=$(document).scrollLeft();
			var text=mx +"<>";
			text+=ScrollPos;
			//ShowString(text);ev.preventDefault();
			//$("#MoveChunk").css({'left':ScrollPos-mx,'z-index':1})
			
			/*var ll=OrgPos-mx;
			
			if(ScrollPos==0 && mx>0)
				*/
			
			
			break; 
		default:
			ShowString(ev.type);
			break;
		 
	} 
	
} 
document.addEventListener('touchstart',touches,false); 
document.addEventListener('touchend',touches,false); 
document.addEventListener('touchmove',touches,false); 
</script>
