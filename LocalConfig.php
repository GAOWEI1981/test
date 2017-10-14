<?php
$SizeList=array("110 5XS","120 4XS","130 3XS","140 2XS","XS","S","M","L","XL","2XL","3XL","4XL","5XL","6XL","7XL");
//$PostWay=array("顺丰到付","顺丰寄付","德邦到付","德邦寄付","韵达寄付","其他");
$PostWay[]=array("顺丰到付","shunfeng");
$PostWay[]=array("顺丰寄付","shunfeng");
$PostWay[]=array("德邦到付","debangwuliu");
$PostWay[]=array("德邦寄付","debangwuliu");
$PostWay[]=array("韵达寄付","yunda");
$PostWay[]=array("其他","qita");

function GetPrice($admin,$product)
{
	//echo $product;echo "<br>";
	$MoneyInfo=GetItemA("products","modal",$product);
	//print_r($MoneyInfo);echo "<br>";
	if(strlen($admin)>0)
	{
		$sql="select ID from orders where creater='{$admin}'";
		$sql="select * from account_book where OrderID in ({$sql}) and product='{$product}' and type<>'loan' and type<>'Loan'";
		//echo $sql;echo "<br>";
		//LogInFile($sql,"sql.txt");
		$result=mysql_query($sql);
		$num=mysql_num_rows($result);//echo $num;
		if($num>0)//二次购买
		{
			//print_r($MoneyInfo);echo "<br>";
			$Price=$MoneyInfo[cost_second];
		}
		else
		{
			$Price=$MoneyInfo[cost_card];
		}
		return $Price;
	}
	else return $MoneyInfo[cost_card];
}
function HavePopedom($SysName,$UserID)
{
	$sql="select * from sys_popedom where sys_name='{$SysName}' and user_id='{$UserID}'";
	//echo $sql;echo "<br>";
	$permit=true;
	$TimeLimit="";
	//echo $sql;echo"<br>";
	$result=mysql_query($sql);
	if(mysql_num_rows($result)>0)//有访问权限设置，需要判断是否超时
	{
		//echo "eeeeeeeeeeee";
		
		while($row=mysql_fetch_array($result))
		{
			//echo $sql;echo"<br>";
			$TimeLimit=$row['time_limit'];
			//echo $TimeLimit-time();echo"<br>";
			if($TimeLimit<time()) $permit=false;//超过使用期限
			//$TimeLimit=date("Y-m-d",$TimeLimit);
			//echo $TimeLimit;echo"<br>";
		}
		if($permit==false)
		{
			//unset($_SESSION['adminID']);
			//echo "{$FileName}?err=您的使用期限已于{$TimeLimit}到期！";
			//Header("Location:login.php?err=您的使用权已于{$TimeLimit}到期！");
			$msg[result]=false;
			$msg[msg]="权限到期";
			return $msg;
		}
	}
	else//没有访问权
	{
		$msg[result]=false;
		$msg[msg]="没有权限";
		return $msg;
	}
	$msg[result]=true;
	$msg[msg]="OK";
	return $msg;
}
function GetPlanPics($OrderID)
{
	$item=GetItemA("orders","ID",$OrderID);
	$paths=DivideWordFromSentence($item['pic'],"<br>");
	$pics="";
	foreach($paths as $va)
	{
		$pics.="<a href='ImageView.php?image={$va}&operate=edit' id='VerMid'>
						<img src='PicSize.php?ImgPath={$va}' onload='resizeImage(this,0.15)'></img></a> ";
	}
	return $pics;
}
function SetState($StateStr,$name,$state)
{
	$info=json_decode($StateStr,true);
	$info[$name]=$state;
	return json_encode($info);
}
function GetTitlePic($UserID)
{
	$item=GetItemA("users","ID",$UserID);
	$brand=$item['brand'];
	$item=GetItemA("brands","name",$brand);
	return $item['title_pic'];
}
function SetGridValues($sheet,$x,$y,$values)
{
	$grid=GetExcelPos($x,$y);
	$cell=$sheet->getCell($grid);
	$valid=$cell->getDataValidation(); 
	$valid->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
	$valid->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
	$valid->setAllowBlank(false); 
	$valid->setShowInputMessage(true);
	$valid->setShowErrorMessage(true);
	$valid->setShowDropDown(true);
	//$valid->setError('您输入的值不在下拉框列表内.');
	//$valid->setPromptTitle('设备类型');
	$content="";
	foreach($values as $va)
	{
		$content.=$va.",";
	}
	$content=substr($content,0,strlen($content)-1);
	//LogInFile($content,"Log.txt");

	$content="\"".$content."\"";
	//LogInFile($content,"Log.txt");
	//$valid->setFormula1("'".$content."'");
	$valid->setFormula1($content);
	//$valid->setFormula1('"AAA,BBB,CCC"');

}
function GetGridContent($sheet,$ColumnCount,$RowCount,$BeginX,$BeginY,$ColumnTitle,$y)
{
	$ColumnIndex=-1;
	for($column=$BeginX;$column<$ColumnCount;$column++)
	{
		$text=GetGridText($sheet,$column,$BeginY);
		if($ColumnTitle==$text)//找到了列
		{
			$ColumnIndex=$column;
			break;
		}
	}
	if($ColumnIndex>=0)
	{
		return GetGridText($sheet,$ColumnIndex,$y);
	}
	else return "";
	
	
}
function GetGridText($sheet,$x,$y)
{
	$grid=$sheet->getCellByColumnAndRow($x,$y);
	return $grid->getValue();
}

function SetGridText($sheet,$x,$y,$content)
{
	$grid=GetExcelPos($x,$y);
	//$style=$sheet->getStyle($grid);//设置单元格格式 
	$sheet->setCellValueExplicit($grid,$content,PHPExcel_Cell_DataType::TYPE_STRING);//强制单元格为文本

	//$sheet->setCellValue($grid,$content);
}


function FillGridColor($sheet,$x,$y)
{
	$grid=GetExcelPos($x,$y);
	$style=$sheet->getStyle($grid);//设置单元格格式 
	$fill=$style->getFill(); 
	$fill->setFillType(PHPExcel_Style_Fill::FILL_SOLID); 
	$fill->getStartColor()->setARGB('FFFF0000'); 
}

function GetExcelPos($x,$y)//excel坐标系
{
	$a=ord('A');
	return chr($a+$x).$y;
}
function MoneyTitles($id,$SelChange,$FontSize=20)
{
	$l2="<select name='title' id='{$id}' style='font-size:{$FontSize}px;' onchange='javascript:{$SelChange}'>";
	$l2.="<option>服装</option>";
	$l2.="<option>邮费</option>";
	$l2.="<option>发票点数</option>";
	$l2.="<option>礼品</option>";
	$l2.="<option>其他</option>";
	$l2.="</select>";
	return $l2;
}
function BrandsSelect($id,$NormalBrand,$SelChange,$FontSize=20)
{
	$sql="select * from brands order by name";
	$r=mysql_query($sql);
	$content="<select id='{$id}' name='{$id}' style='font-size:{$FontSize}px;' onchange='javascript:{$SelChange};'>";
	while($row=mysql_fetch_array($r))
	{
		if($NormalBrand==$row['name'])
			$content.="<option selected>{$row['name']}</option>";
		else
			$content.="<option>{$row['name']}</option>";
	}
	$content.="</select></td>";
	return $content;
}
function Products($id,$table,$SelChange,$FontSize=20)
{
	$sql="select * from $table order by modal";
	$r=mysql_query($sql);
	$content="<select id='{$id}' name='{$id}' style='font-size:{$FontSize}px;' onchange='javascript:{$SelChange};'>";
	$content.="<option id='0' selected>NULL</option>";
	while($row=mysql_fetch_array($r))
	{
		$content.="<option value='{$row['cost']}'>{$row['modal']}</option>";
	}
	$content.="</select></td>";
	return $content;
}
/*
function CreateOrdersByItems($OrderList,$ItemsList)//根据衣服清单重建订单表
{
	$sql="delete from {$OrderList}";
	if(mysql_query($sql))
	{
		$sql="select * from {$ItemsList}";
		$items=mysql_query($sql);//所有衣服列表
		$sql="select * from {$OrderList} where OrderID='%s'";//用来搜索订单的语句
		for($i=0;$row=mysql_fetch_array($items);)
		{
			$OrderID=$row['OrderID'];
			$content=sprintf($sql,$OrderID);
			$result=mysql_query($content);

			$CurrentTime=GetCurrentTime(0);
			$CurrentDate=substr($CurrentTime,0,10);
			$CurrentTime=substr($CurrentTime,11,9);
			if(mysql_num_rows($result)==0)
			{
				$content="INSERT INTO {$OrderList} VALUES ('{$OrderID}','空的', '空的地址', '空的收件人','空的电话','未知创建人','空的', '{$CurrentDate}','{$CurrentTime}')";
				mysql_query($content);
			}
	
		}
	}
	else
	{
		return $sql."清空表时失败";
	}

	
}*/
function ChineseSizeToEuropeSize($size)
{
	$size=strtoupper($size);
	if($size=="L")		return "XS";
	if($size=="XL")		return "S";
	if($size=="2XL")	return "M";
	if($size=="3XL")	return "L";
	if($size=="4XL")	return "XL";
	if($size=="5XL")	return "2XL";
	if($size=="6XL")	return "3XL";
	if($size=="7XL")	return "4XL";
	//$_SESSION['error']="无法转换成欧码";
	//return $size;
	return "无法转换成欧码";
}
function EuropeSizeToChineseSize($size)
{
	$size=strtoupper($size);
	if($size=="XS")		return "L";
	if($size=="S")		return "XL";
	if($size=="M")		return "2XL";
	if($size=="L")		return "3XL";
	if($size=="XL")		return "4XL";
	if($size=="2XL")	return "5XL";
	if($size=="3XL")	return "6XL";
	if($size=="4XL")	return "7XL";
	$_SESSION['error']="无法转换成中国码";
	//return $size;
	return "无法转换成中国码";
}
function ReadSizeTable($path)
{
	$reader = PHPExcel_IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
	$PHPExcel = new PHPExcel();//否则不会自动跳转
	$PHPExcel = $reader->load($path); // 载入excel文件
	$sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
	$RCount = $sheet->getHighestRow(); // 取得总行数
	$CCount = $sheet->getHighestColumn(); // 取得总列数
	//echo $RCount."<>".$CCount."<br>";
	$CCount= PHPExcel_Cell::columnIndexFromString($CCount); //字母列转换为数字列 如:AA变为27

	$table=array();
	$content="<table border='1'>";
	for ($row = 1; $row <= $RCount; $row++)
	{//行数是以第1行开始
		$line=array();
		$content.="<tr>";
		for ($column = 0; $column < $CCount; $column++)
		{//列数是以第0列开始
			//$columnName = PHPExcel_Cell::stringFromColumnIndex($column);
			//echo $columnName.$row.":".$sheet->getCellByColumnAndRow($column, $row)->getValue()."<br />";
			$text=GetGridText($sheet,$column, $row);
			$line[]=$text;
			$content.="<td>{$text}</td>";	 
		}
		$table[]=$line;
		$content.="</tr>";
	}
	$content.="</table>";
	//echo $content;
	return $table;
	
}

function ChineseSize($stat,$avoi,$SizeTable)
{
	//格式化输入
	//echo "MM".$stat."<>".$avoi."<br>";
	for($i=140;$i<215;$i+=5)
	{
		//echo abs($i-$stat)."<>".$i."<>".$stat."<br>";
		if(abs($i-$stat)<=2)
		{	
			$stat=$i;
			break;
		}
	}
	
	if($stat>$i) $stat=$i;
	else if($stat!=$i) $stat=160;
	//echo "<>".$stat."<>".$avoi."<br>";
	
	for($i=25;$i<115;$i+=5)
	{
		if(abs($i-$avoi)<=2)
		{	
			$avoi=$i;
			break;
		}
	}
	if($avoi>$i) $avoi=$i;
	else if($avoi!=$i) $avoi=45;
	
	//Echo $stat."<>".$avoi."<br>";
	foreach($SizeTable as $line)
		if($line[0]==$stat) break;
	$ar=$SizeTable[0];
	for($i=0;$i<count($ar);$i++)
	{
		if($ar[$i]==$avoi)
		{
			echo $line[$i]."<br>";
			return $line[$i];
		}
	}
	return "";
	
	
}
/*
function CreateID($length)
{	
	$a=ord('A');

	for($i=0;$i<$length;$i++)
	{
		$va=rand(0,9+26);
		if($va>9)
		{
			$va=$a+$va-10;
			$text.=chr($va);
		}
		else $text.=$va;
	}
	return $text;
}
*/
?>
