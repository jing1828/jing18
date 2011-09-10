<?php
date_default_timezone_set('Asia/Taipei');

require_once '../libs/jingPdo.php';
require_once '../libs/jingUti.php';
require_once 'SalesNameSet.php';

$sns = new SalesNameSet;

$comp = strlen($_GET['comp']) > 0 ? $_GET['comp'] : 0;
$sales = strlen($_GET['sales']) > 0 ? $_GET['sales'] : 0;
$empid = strlen($_GET['empid']) > 0 ? $_GET['empid'] : 0;
$sname = strlen($_GET['sname']) > 0 ? $_GET['sname'] : '';
$memo = strlen($_GET['memo']) > 0 ? $_GET['memo'] : '';
$tDate = date('Y/m/d H:i:s');

if (isset($_GET['modify'])) //修改
{
	$sns->salesNameUpdate($comp, $sales, $empid, $sname, $tDate, $memo);
	$msg = Msgbox2('[更新] 完成！');
}
elseif (isset($_GET['add']))
{
	$rs = $sns->salesNameRead($comp, $sales);
	if (count($rs) > 0)
	{
		$msg = Msgbox2('新增失敗！資料不可重複！');
	}
	else
	{
		$sns->salesNameAdd($comp, $sales, $empid, $sname, $tDate, $memo);
		$msg = Msgbox2('[新增] 完成！');
	}
}
elseif (isset($_GET['delete']))
{
	$sns->salesNameDelete($comp, $sales);
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
			location.href="SalesNameSetView.php";
		</script>
	</head>
	<body>
	</body>
</html>
