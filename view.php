<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>營業員佣金明細表</title>
        <script type="text/javascript">
            function downloadPage(strUrl)
            {
                document.location.href=strUrl;
                return false;
            }
        </script>
    </head>
    <body>
        <table border="1" align="center" cellspacing="0" cellpadding="2">
            <tr>
                <td>
                    <table bgcolor="#DDDDDD" border="0" align="center" cellspacing="0" cellpadding="8">
                        <tr align="center" bgcolor="#DDDDDD">
                            <td COLSPAN='2'>
									[營業員佣金明細表]
                            </td>
                        </tr>
                        <tr>
                        <tr>
                            <td align="center">
                                大功告成！
                            </td>
                        </tr>
                        <tr bgcolor="#DDDDDD">
                            <td align='right' COLSPAN='2'>
                                <?php
                                echo "<a href='excel/comm-{$tYY}{$tMM}.xls' accesskey='0'>下載「營業員佣金明細表」comm-{$tYY}{$tMM}.xls(0)</a>";
                                ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
