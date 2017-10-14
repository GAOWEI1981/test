<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<title>登陆</title>
<link href="../function/industry.css" rel="stylesheet" type="text/css" />
</head>
<body class="BodyNoSpace">
<?
ob_start();
error_reporting(E_ALL & ~E_NOTICE);
if(!isset($_SESSION)) session_start();
include_once("../function/config.php");
include_once("LocalConfig.php");
include_once("../function/Script.php");
//include_once("phone_msg_sender.php");


$FileName=basename($_SERVER["PHP_SELF"]);
if($Database->Connect()==false)
{
	echo "db connect false!";
	return;
}

mysql_select_db($DatabaseName);


//print_r($_GET);echo "<br>";
$operate=$_GET['operate'];
switch($operate)
{
case "":
	
	break;
case "FixPostNum":
	if(strlen($_GET[OrderID])>0)
	{
		$sql="update orders set PostNum='{$_GET[PostNum]}',PostWay='{$_GET[PostWay]}' where ID='{$_GET[OrderID]}'";
		//echo $sql;echo "<br>";
		mysql_query($sql);
		
	}
	?>
<script>
	try
	{
		var id="<?echo $_GET[OrderID];?>";
		//alert(id);
		var lable=parent.window.document.getElementById(id+"_lable");
		lable.innerText="<?echo $_GET[PostNum];?>";
		lable.style.display="";
		var input=parent.window.document.getElementById(id+"_num");
		input.style.display="none";
	}catch(e)
	{
		alert(e);
	}
	
	</script>
    <?
	//Header("Location:{$FileName}");
	return;
}

?>
<table width="100%" border="0" cellpadding="1" cellspacing="3" class="Industry">
<tr>
  <th align="left"><a href="page_edit_users.php">返回</a></th>
  </tr>
<tr>
  <td align="center"><table width="100%" border="1" cellpadding="2" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
    
    </table></td>
</tr>
<?
  $sql="select a.*,b.product,b.price,b.count,
  		b.type,b.gross
  		from orders a,account_book b where a.ID=b.OrderID";
	//
	$sql="select a.*,b.phone as UserPhone,b.name as UserName from ({$sql}) a left join signup_users b on a.creater=b.openid";
	
	if(strlen($_GET[UserID])>0)
	{
		//echo "sdfsd<br>";
		$sql="select a.* from ({$sql}) a where a.creater='$_GET[UserID]'";
	}
	$sql="select a.* from ({$sql}) a order by a.time desc";
	//echo $sql;echo "<br>"; 
  $result=mysql_query($sql);
  while($row=mysql_fetch_array($result))
  {
	  
	  $time=date("Y-m-d H:i:s",$row[time]);
	  $title=mb_strimwidth($row[title], 0, 15, '...', 'utf8');
	  $bc="";
	  if(strtolower($row[type])=="loan")
	  	$bc="#FF0000";
		
		if(strlen($row[PostNum])==0)
		{
			$PostLableDis="display:none";
			$PostInputDis="";
		}
		else
		{
			$PostLableDis="";
			$PostInputDis="display:none";
		}
		
	  ?>
        <tr bgcolor="<?echo $bc;?>">
          <td width="65%"><table width="100%" border="1" cellpadding="2" cellspacing="0" class="Industry">
            <tr>
              <td width="100%" colspan="2"><table width="100%" border="0" cellpadding="2">
                <tbody>
                    <tr>
                      <th width="37%" align="left"><?echo $row[product];?></th>
                      <th width="63%" colspan="2" align="right"><?echo $time;?></th>
                    </tr>
                    <tr>
                      <td align="left">联系人</td>
                      <td colspan="2" align="left"><?echo $row[UserName]." ".$row[UserPhone];?></td>
                    </tr>
                    <tr>
                      <td align="center">单价</td>
                      <td align="center">数量 </td>
                      <td align="center">合计</td>
                    </tr>
                    <tr>
                      <td align="center">￥<?echo $row[price];?>&nbsp;</td>
                      <td align="center">X <?echo $row[count];?>&nbsp;</td>
                      <td align="center">￥<?echo $row[gross];?></td>
                    </tr>
                </tbody>
              </table></td>
            </tr>
            <tr>
              <td colspan="2"><table width="100%" border="0" cellpadding="2" cellspacing="3">
                <tr>
                  <td width="42%"><?echo "{$row[receiver]}";?></td>
                  <td width="58%" align="left"><?echo "{$row[phone]}";?></td>
                </tr>
                <tr>
                  <td colspan="2"><?echo "{$row[address]}";?></td>
                </tr>
                <tr>
                  <td colspan="2"><table width="100%" border="0" cellpadding="2" cellspacing="2">
<tr>
                      <td><input style="width:90%;<?echo $PostInputDis;?>" id="<?echo $row[ID]?>_num" type="text" value="<?echo $row[PostNum];?>"/>
                      <label style="<?echo $PostLableDis;?>" id="<?echo $row[ID]."_lable";?>"><?echo $row[PostNum];?></label></td>
                      <td rowspan="2" align="center"><a href="#" onclick="return SetPostNum(this);" id="<?echo $row[ID]?>">填写</a></td>
                    </tr>
                    <tr>
                      <td>
                          <select style="width:50%" name="postway" id="<?echo $row[ID];?>_postway">
                            <?
                            for($i=0;$i<count($PostWay);$i++)
                            {
                                if($PostWay[$i][0]==$row[PostWay])
                                    echo "<option selected='selected'>{$PostWay[$i][0]}</option>";
                                else
                                    echo "<option>{$PostWay[$i][0]}</option>";
                            }
                            ?>
                          </select></td>
                    </tr>
                    
                  </table></td>
                </tr>
                
              </table></td>
            </tr>
            
          </table></td>
        </tr>
      <?
  }
  ?>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<iframe src="" width="100%" height="200px" id="FixWnd" name="FixWnd"></iframe>
</body>
</html>
<script>
function SetPostNum(obj)
{
	var InputID=obj.id+"_num";
	var NumWnd=document.getElementById(InputID);
	var num=NumWnd.value;
	if(NumWnd.style.display=="none")
	{
		NumWnd.style.display="";
		var LableID=obj.id+"_lable";
		var LableWnd=document.getElementById(LableID);
		LableWnd.style.display="none";
		
	}
	else
	{
		if(num.length>0)
		{
			var sel=document.getElementById(obj.id+"_postway")
			var index=sel.selectedIndex;
			
			var url="<?echo $FileName.'?operate=FixPostNum&PostNum=';?>"+num+"&OrderID="+obj.id+"&PostWay="+sel.options[index].text;
			//alert(url);
			document.getElementById("FixWnd").src=url;
			
		}else
		{
			alert("不能为空");
		}
	}
	return false;
}
</script>