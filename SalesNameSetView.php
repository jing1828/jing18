<?php
require_once '../libs/jingPdo.php';
require_once 'SalesNameSet.php';
date_default_timezone_set('Asia/Taipei');

$sns = new SalesNameSet;
$rows = $sns->getRows();
?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>營業員基本資料</title>
        <style type="text/css">
            .input1 {width: 60px; text-align: right; border: 0px; background-color: transparent}
            .input2 {width: 125px; text-align: right; border: 0px; background-color: transparent}
            .input3 {width: 160px; border: 1px solid #aaa} 
            .input5 {width: 60px; text-align: right; border: 1px solid #aaa}
            .input6 {width: 100px; border: 1px solid #aaa} 
            .input8 {width: 70px; text-align: right; border: 1px solid #aaa}
            .button1 {padding-top: 2px}
			#tr1 {color: #000; background-color: #ddd}
        </style>
		<script type="text/javascript">
			function confirmation(strMessage) {
				var answer = confirm(strMessage)
				if (!answer) return false;      
			}
		</script>
    </head>
    <body bgcolor="#e0e0e0">
        <table align="center" cellspacing="0" cellpadding="2" style="border: 1px solid #aaa; background-color: #fff">
            <tr>
                <td align="center" colspan="11" style="padding: 10px 10px; font-size: 16pt">[營業員基本資料]</td>
            </tr>
            <tr align="right" id="tr1">
                <td>序號</td>
                <td align="center" colspan="1">.</td>
                <td>公司</td>
                <td>營業員</td>
                <td>員工編號</td>
                <td align="left">姓名</td>
                <td align="left">備註</td>
                <td align="left">最後修改日期</td>
                <td align="center" colspan="1">.</td>
            </tr>
            <tr>
            <form action="SalesNameSetControl.php">
                <td colspan="2">.</td>
                <td><input type="text" class="input5" name="comp" /></td>
                <td><input type="text" class="input5" name="sales" /></td>
                <td><input type="text" class="input8" name="empid" /></td>
                <td><input type="text" class="input6" name="sname" /></td>
                <td><input type="text" class="input3" name="memo" /></td>
                <td>.</td>
                <td colspan="2" align="center"><input type="submit" class="button1" name ="add" value="新增" onclick="return confirmation('新增嗎 ?')" /></td>
            </form>
        </tr>
		<?php
		$i = 1;
		foreach ($rows as $row)
		{
			echo '<form action="SalesNameSetControl.php">';
			if ($i % 2 != 0)
			{
				echo '<tr bgcolor="#e0e0e0">';
			}
			else
			{
				echo '<tr>';
			}
			echo '<td align="right">' . $i++ . '</td>';
			echo '<td align="center"><input type="submit" name ="delete" value="Del" onclick="return confirmation(\'刪除嗎?\')" /></td>';
			echo '<td><input type="text" class="input1" readonly="readonly" name="comp" value="' . $row['comp'] . '" /></td>';
			echo '<td><input type="text" class="input1" readonly="readonly" name="sales" value="' . $row['sales'] . '" /></td>';
			echo '<td><input type="text" class="input8" name="empid" value="' . $row['empid'] . '" /></td>';
			echo '<td><input type="text" class="input6" name="sname" value="' . $row['sname'] . '" /></td>';
			echo '<td><input type="text" class="input3" name="memo" value="' . $row['memo'] . '" /></td>';
			echo '<td><input type="text" class="input2" readonly="readonly" name="tDate" value="' . $row['tDate'] . '" /></td>';
			echo '<td align="center"><input type="submit" class="button1" name ="modify" value="更新" onclick="return confirmation(\'更新嗎?\')" /></td>';
			echo '</tr>';
			echo '</form>' . "\n";
		}
		?>
    </table>
</body>
</html>
