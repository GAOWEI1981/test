<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<link href="../function/industry.css" rel="stylesheet" type="text/css" />
<style>
#MainChunk{position: relative;overflow: hidden;border:1px;height:40px}
#MoveChunk{position: absolute;height:40px;}
</style>
<table width="100%" border="1" cellpadding="0" cellspacing="0" style="display:none;">
<tr>
    <td id="TextOut">&nbsp;</td>
  </tr>
</table>

<div id="MainChunk">
<?
//echo $_SESSION[ViewModal];echo "<br>";
switch($_SESSION[ViewModal])
{
case "WaitForPorcessing":
	$Color00="bgcolor='#FF0000'";
	break;
case "complete":
	$Color01="bgcolor='#FF0000'";
	break;
case "":
	$Color02="bgcolor='#FF0000'";
	break;
case "AlreadyPay":
	$Color03="bgcolor='#FF0000'";
	break;
case "UnexportedItem":
	$Color04="bgcolor='#FF0000'";
	break;
}


$i=0;
?>
<table border="1" cellpadding="0" cellspacing="0" id="MoveChunk">
<tr>
<td align="center"><?$i++;?><a href="<?echo "{$FileName}?operate=SwitchViewModal&view=";?>">首页</a></td>
<?
$MenuSql="select * from product_groups";
$result=mysql_query($MenuSql);
while($row=mysql_fetch_array($result))
{
	?>
	<td align="center"><a href="<?echo "{$FileName}?operate=SwitchViewModal&view=ViewProductGroup&GroupID={$row[id]}";?>"><?echo $row[name];?></a>&nbsp;</td>
	<?
	$i++;
}
?>
<td align="center"><?$i++;?>后台</td>
</tr> 
</table>
</div>
<script src="../../function/jquery.js"></script>
<script>
var ColumnWidth=120;
$("#MoveChunk").width(ColumnWidth*parseInt("<?echo $i;?>"));
$("#MoveChunk").find("td").width(ColumnWidth);
var OrgPos=0;
var CurUrl="";
function ShowString(str)
{
	var ele=document.getElementById("TextOut");
	ele.innerHTML=str;
}
function touches(ev)
{ 
	switch(ev.type)
	{ 
		case "touchstart": 
			//OrgScrollPos=document.body.scrollTop;
			StartX=parseInt(ev.changedTouches[0].clientX);
			StartY=parseInt(ev.changedTouches[0].clientY);
			var obj;
			//var ParentID=ev.target.parentNode.parentNode.parentNode.id;
			
			
			TouchCtrlEnable=false;
			obj=ev.target;
			while(obj!=null)
			{
				if(obj.id=="MoveChunk")
				{
					TouchCtrlEnable=true;
					break;
				}
				obj=obj.parentNode;	
			}
			//ShowString(ParentID);
			//ShowString(ev.target.parentNode.parentNode.parentNode.id);
			if(TouchCtrlEnable==true)
			{
				/*var t=typeof(ev.target);
				if(ev.target.nodeName.toLowerCase()!="a")
					
					*/
				CurUrl=ev.target.href;
				ev.preventDefault();  //阻止出现滚动条
			}
			
			
			//ShowString(StartX+"<>"+TouchCtrlEnable);
			break; 
		case "touchend":

			try
			{
				var movevalue=OrgPos-$("#MoveChunk").position().left;
				if(CurUrl!=null && TouchCtrlEnable==true && Math.abs(movevalue)<5)
				{
					location=CurUrl;
					return;
				}
				
			}catch(e)
			{
				alert(e);
			}
			OrgPos=$("#MoveChunk").position().left;
			break; 
		case "touchmove": 
			if(TouchCtrlEnable==false) return;
			var PosX=parseInt(ev.changedTouches[0].clientX);
			var PosY=parseInt(ev.changedTouches[0].clientY);
			//ShowString(PosX+"<>"+PosY);
			
			var mx=PosX-StartX;
			var my=PosY-StartY;
			var pos=OrgPos+mx;


			//var right=$("#MoveChunk").position().left+$("#MoveChunk").width();
			var right=pos+$("#MoveChunk").width();
			if($("#MainChunk").width()< right && pos<=0)
			{
				//ShowString(pos+"<>"+right+"<>"+$("#MainChunk").width());
			
				//var Img=ImageChunks[ChunkIndex];
				$("#MoveChunk").css({'left':pos,'z-index':1})
			}
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
