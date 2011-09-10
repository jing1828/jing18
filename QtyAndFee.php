<?php

/**
require_once '../libs/jingPdo.php';
require_once '../libs/jingUti.php';
require_once 'commissionSet.php';

$qaf = new QtyAndFee('2011/7/1');
var_dump($qaf->getStep1Rows());
exit;
 * 
 */

class QtyAndFee
{

    private $db;
    private $tDate;
    private $rows;
    private $commissionRows;
    private $step1Rows;
    private $symbTable1 = array('FITX', 'FITE', 'FITF', 'FIT5', 'FIGT', 'FIGB', 'FIXI', 'FICP');    //大台商品名稱
    private $symbTable2 = array('FIMTX', 'FITG');                                                   //小台商品名稱

    public function __construct($tDate)
    {
        $dbType = 'oracle';
        $host = '193.20.10.47';
        $dbName = 'X102';
        $user = 'tachan';
        $pw = 'tachan';

        $this->db = JingPDO::getInstance($dbType, $host, $dbName, $user, $pw);

        $this->tDate = UtiTA::getTaThisday($tDate);

        $this->getRowsQtyAndFee();              // 取得交易帳
        $this->getRowsExceptionCommission();    // 取得特例資訊
        $this->step1();                         // 為每筆交易加上 佣金(貢獻額)
    }

    public function getStep1Rows()
    {
        return $this->step1Rows;
    }

    public function getRows()
    {
        return $this->rows;
    }

    private function step1()
    {
        $rs = array();

        foreach ($this->rows as $row)
        {
            $futOpt = $this->getSymbNum($row['SYMB']);          //商品名稱群組編號
            $keyinOrNet = ((int) $row['STAT'] < 30000) ? 1 : 2; //[1]人工 [2]網路
            $unitPrice = $row['FEE'] / $row['QTY'];             //每口手續費

            if ($keyinOrNet == 1)
            {   //一般佣金 & 貢獻額
                $value = $this->getCommissionValue($row['COMP'], $row['SRTR'], $row['SETT'], $futOpt, $unitPrice);

                $contribution = $value[0];  //貢獻額度
                $commission = $value[1];    //佣金
            }
            else
            {   //網路單 不給佣金 & 貢獻額
                $contribution = 0;  //貢獻額度
                $commission = 0;    //佣金
            }

            // keys = 公司 + 營業員 + 客戶 + 人工/網路 + 期/權 + 單價
            $keys = sprintf("%05d%05d%07d%01d%01d%04d", $row['COMP'], $row['SRTR'], $row['SETT'], $keyinOrNet, $futOpt, $unitPrice);

			if (isset($rs[$keys]))
			{
				$rs[$keys]['qty'] += $row['QTY'];                            //口數
				$rs[$keys]['fee'] += $row['FEE'];                            //手續費
				$rs[$keys]['commission'] += ($commission * $row['QTY']);     //佣金
				$rs[$keys]['contribution'] += ($contribution * $row['QTY']); //貢獻額
			}
			else
			{
				$rs[$keys]['qty'] = $row['QTY'];                            //口數
				$rs[$keys]['fee'] = $row['FEE'];                            //手續費
				$rs[$keys]['commission'] = ($commission * $row['QTY']);     //佣金
				$rs[$keys]['contribution'] = ($contribution * $row['QTY']); //貢獻額
			}
        }

        $this->step1Rows = $rs;
    }

    /**
     * 一口的 貢獻額度 & 佣金
     * @param int   $comp       公司
     * @param int   $srtr       營業員
     * @param int   $sett       客戶
     * @param int   $futOpt     商品
     * @param int   $unitPrice  手續費
     * @return array 貢獻額 & 佣金
     */
    private function getCommissionValue($comp, $srtr, $sett, $futOpt, $unitPrice)
    {
        //一口的 $value1[0]貢獻額 $value1[1]佣金
        $value1 = $this->getCommissionNormal($futOpt, $unitPrice);
        //一口的 特例佣金 無貢獻額=0
        $value2 = $this->getCommissionException($comp, $srtr, $sett, $futOpt, $unitPrice);
        //有特例時, 無貢獻額
        if ($value2 > 0)
        {
            $value1[0] = 0;         //貢獻額度
            $value1[1] = $value2;   //佣金
        }

        return $value1;
    }

    /**
     * 例外佣金
     * @param int   $comp    公司
     * @param int   $srtr    營業員
     * @param int   $sett    客戶
     * @param int   $futOpt  商品
     * @param int   $unitPrice 元/@口
     * @return int 佣金
     */
    private function getCommissionException($comp, $sales, $sett, $futOpt, $unitPrice)
    {
        $commission = 0;

        $keys = sprintf("%05d%05d", $comp, $sales);
        if (isset($this->commissionRows[$keys]))
        {
            $keys1 = sprintf("%07d%01d%04d", $sett, $futOpt, $unitPrice);
            $keys2 = sprintf("%07d%01d%04d", $sett, $futOpt, 0);
            $keys3 = sprintf("%07d%01d%04d", 0, $futOpt, $unitPrice);
            $keys4 = sprintf("%07d%01d%04d", 0, $futOpt, 0);
            if (isset($this->commissionRows[$keys][$keys1]))
            {
                $commission = $this->commissionRows[$keys][$keys1]['objCommission'];
            }
            elseif (isset($this->commissionRows[$keys][$keys2]))
            {
                $commission = $this->commissionRows[$keys][$keys2]['objCommission'];
            }
            elseif (isset($this->commissionRows[$keys][$keys3]))
            {
                $commission = $this->commissionRows[$keys][$keys3]['objCommission'];
            }
            elseif (isset($this->commissionRows[$keys][$keys4]))
            {
                $commission = $this->commissionRows[$keys][$keys4]['objCommission'];
            }
        }

        return $commission;
    }

    /**
     * 一般的佣金、貢獻計算
     * @param int $futOpt 商品編號
     * @param int $unitPrice 手續費
     * @return array [0]貢獻額 [1]佣金
     */
    private function getCommissionNormal($futOpt, $unitPrice)
    {
        $value[0] = 0;  //貢獻額
        $value[1] = 0;  //佣金
        $futOpt1 = 300; //大台成本
        $futOpt2 = 200; //小台成本

        switch ($futOpt)
        {
            case 1: //大台
                if ($unitPrice > $futOpt1)
                    $value[0] = $unitPrice - $futOpt1;  //貢獻額度
                else
                    $value[1] = 15;                     //佣金

                break;
            case 2: //小台
                if ($unitPrice > $futOpt2)
                    $value[0] = $unitPrice - $futOpt2;  //貢獻額度
                else
                    $value[1] = 10;                     //佣金

                break;
            case 3: //選擇權
            case 4: //個股期貨
                $value[1] = 15;                   //佣金
                break;
        }

        return $value;
    }

    /**
     * 取得特例的資料
     * $rs[$keys1][$keys2]
     *      $keys1: 公司, 營業員
     *      $keys2: 客戶, 商品, 手續費
     */
    private function getRowsExceptionCommission()
    {
        $cms = new commissionSet;
        $rows = $cms->getRows();

        $rs = array();

        foreach ($rows as $row)
        {
            $srcComp = $row['srcComp'];
            $srcSales = $row['srcSales'];
            $srcSett = $row['srcSett'];
            $srcStock = $row['srcStock'];
            $srcFee = $row['srcFee'];

            $keys1 = sprintf("%05d%05d", $srcComp, $srcSales);
            $keys2 = sprintf("%07d%01d%04d", $srcSett, $srcStock, $srcFee);

            $rs[$keys1][$keys2]['objSales'] = $row['objSales'];
            $rs[$keys1][$keys2]['objCommission'] = $row['objCommission'];
        }

        $this->commissionRows = $rs;
    }

    /**
     * 取得月交易帳
     */
    private function getRowsQtyAndFee()
    {
        $sql = 'SELECT stati_ncompid comp, macus_nsrtrid srtr, stati_nsettvl sett, macus_vsymbvl cuna, stati_vsymbvl symb, STATI_NSTATID stat, SUM(STATI_NSTLBVL+STATI_NSTSBVL) qty, SUM(STATI_NSTLFVL+STATI_NSTSFVL) fee ';
        $sql .= 'FROM obbstati, obcmacus ';
        $sql .= 'WHERE obbstati.stati_nsettvl = obcmacus.macus_nsettvl AND stati_nperiid = 20 ';
        $sql .= "AND stati_ndateid = {$this->tDate} AND stati_nlinoid = 100 AND stati_vsymbvl <> 'TWD' AND stati_nmodeid = 6 AND stati_ncompid <> 50029 AND stati_ncompid <> 51029 ";
        $sql .= 'GROUP BY stati_ncompid, macus_nsrtrid, stati_nsettvl, macus_vsymbvl, stati_vsymbvl, stati_nstatid  ';
        $sql .= 'ORDER BY stati_ncompid, macus_nsrtrid ';

        $rows = $this->db->GetRows($sql, TRUE);
        $this->rows = $rows;
    }

    /**
     * 取得商品群組編號
     * @param string $symb 商品名稱
     * @return int [1]大台 [2]小台 [3]選擇權 [4]股票期貨
     */
    private function getSymbNum($symb)
    {
        if (in_array($symb, $this->symbTable1))
        {
            return 1;   //大台
        }
        elseif (in_array($symb, $this->symbTable2))
        {
            return 2;   //小台
        }
        elseif (substr($symb, 0, 2) == "FI")
        {
            return 4;   //股票期貨
        }
        else
        {
            return 3;   //選擇權
        }
    }

}
