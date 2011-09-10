<?php
date_default_timezone_set('Asia/Taipei');

require_once '../libs/jingPdo.php';
require_once '../libs/jingUti.php';
require_once 'commissionSet.php';

$cms = new commissionSet;

$srcComp = strlen($_GET['srcComp']) > 0 ? $_GET['srcComp'] : 0;
$srcSales = strlen($_GET['srcSales']) > 0 ? $_GET['srcSales'] : 0;
$srcSett = strlen($_GET['srcSett']) > 0 ? $_GET['srcSett'] : 0;
$srcStock = strlen($_GET['srcStock']) > 0 ? (int) $_GET['srcStock'] : 0;
$srcFee = strlen($_GET['srcFee']) > 0 ? $_GET['srcFee'] : 0;
$objSales = strlen($_GET['objSales']) > 0 ? $_GET['objSales'] : 0;
$objCommission = strlen($_GET['objCommission']) > 0 ? $_GET['objCommission'] : 0;
$memo = strlen($_GET['memo']) > 0 ? $_GET['memo'] : '';
$tDate = date('Y/m/d H:i:s');

if (isset($_GET['modify'])) //修改
{
	$cms->commUpdate($srcComp, $srcSales, $srcSett, $srcStock, $srcFee, $objSales, $objCommission, $tDate, $memo);
	$msg = Msgbox2('[更新] 完成！');
}
elseif (isset($_GET['add']))
{
	$rs = $cms->commRead($srcComp, $srcSales, $srcSett, $srcStock, $srcFee, $objSales, $objCommission, $tDate, $memo);
	if (count($rs) > 0)
	{
		$msg = Msgbox2('新增失敗！資料不可重複！');
	}
    elseif ($srcStock == 0)
    {
		$msg = Msgbox2('新增失敗！[商品]欄位不可為 0 ! [1]大台 [2]小台 [3]選擇權 [4]股票期貨');
    }
	else
	{
		$cms->commAdd($srcComp, $srcSales, $srcSett, $srcStock, $srcFee, $objSales, $objCommission, $tDate, $memo);
		$msg = Msgbox2('[新增] 完成！');
	}
}
elseif (isset($_GET['delete']))
{
	$cms->commDelete($srcComp, $srcSales, $srcSett, $srcStock, $srcFee);
	$msg = Msgbox2('[刪除] 完成！');
}

function Msgbox2($str)
{
	return "alert('{$str}');";
}
?>

<html lang="en-US">
	<head>
		<meta charset="UTF-8">
		<script language="JavaScript">
			<?php echo $msg; ?>
			location.href="commissionSetView.php";
		</script>
	</head>
	<body>
	</body>
</html>
