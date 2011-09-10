<?php

date_default_timezone_set('Asia/Taipei');

require_once '../libs/jingPdo.php';
require_once '../libs/jingUti.php';
require_once '../libs/ExcelUti.php';
require_once 'CommissionToExcel.php';
require_once 'QtyAndFee.php';
require_once 'commissionSet.php';

$tYY = $_GET['tYY'];
$tMM = sprintf("%02d", $_GET['tMM']);
$tDate = "{$tYY}/{$tMM}/01";
$qaf = new QtyAndFee($tDate);
$rs = $qaf->getRows();
$step1Rs = $qaf->getStep1Rows();

//$fileName = "營業員佣金明細表";
$fileName = "comm-{$tYY}{$tMM}";
$cte = new CommissionToExcel($fileName, $tYY, $tMM);
$cte->putRs($rs);
$cte->putStep1Rs($step1Rs);
$cte->SaveAs();
$cte->Quit();
unset($cte);

include "view.php";
exit;

