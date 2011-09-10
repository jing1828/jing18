<?php
set_time_limit(0);
date_default_timezone_set('Asia/Taipei');

$tYY = date('Y');
$tMM = (int) date('m', mktime(0, 0, 0, date('m'), 0, date('Y')));
?>

<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <title>營業員佣金明細表</title>
    </head>

    <body bgcolor="#d0d0d0">
        <br />
        <form action="control.php">
            <table border="1" align="center" cellspacing="0" cellpadding="2">
                <tr>
                    <td>
                        <table bgcolor="#c0c0c0" border="0" align="center" cellspacing="0" cellpadding="8">
                            <tr align="center" bgcolor="#b0b0b0">
                                <td COLSPAN='2'>
                                    [營業員佣金明細表]
                                </td>
                            </tr>
                            <tr>
                            <tr>
                                <td colspan="2" align="center">年度：(1)<input type="text" name="tYY" size ="10" accesskey="1" value="<?php echo $tYY; ?>" /><br /> </td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center">月份：(2)<input type="text" name="tMM" size ="10" accesskey="2" value="<?php echo $tMM; ?>" /><br /> </td>
                            </tr>
                            <tr bgcolor="#b0b0b0">
                                <td align='right' COLSPAN='2'>
                                    <input type="submit" value="執行(0)" name ="works" accesskey="0"/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" align="center"><a accesskey="5" href="./commissionSetView.php">營業員佣金設定(5)</a></td>
                                <td colspan="1" align="center"><a accesskey="6" href="./SalesNameSetView.php">營業員基本資料(6)</a></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>
