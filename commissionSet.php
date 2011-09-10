<?php

class commissionSet
{

    private $db;

    public function __construct()
    {
        $dbType = 'mysql';
        $host = 'localhost';
        $dbName = 'jing18';
        $user = 'root';
        $pw = '2ijiglgl';

        $this->db = JingPDO::getInstance($dbType, $host, $dbName, $user, $pw);
        $this->db->RunSql('set names utf8');
    }

    public function commDelete($srcComp, $srcSales, $srcSett, $srcStock, $srcFee)
    {
        $sql = "DELETE FROM salescommission WHERE srcComp={$srcComp} ";
        $sql .= "AND srcSales={$srcSales} AND srcSett={$srcSett} ";
        $sql .= "AND srcStock={$srcStock} AND srcFee={$srcFee} ";

        $this->db->RunSql($sql);
    }

    public function commUpdate($srcComp, $srcSales, $srcSett, $srcStock, $srcFee, $objSales, $objCommission, $tDate, $memo)
    {
        $sql = 'update salesCommission set ';
        $sql .= "objSales={$objSales}, objCommission={$objCommission}, ";
        $sql .= "tDate='{$tDate}', memo='{$memo}' ";
        $sql .= "where srcComp={$srcComp} and srcSales={$srcSales} and srcSett={$srcSett} and ";
        $sql .= "      srcStock={$srcStock} and srcFee={$srcFee}";

        $this->db->RunSql($sql);
    }

    public function commAdd($srcComp, $srcSales, $srcSett, $srcStock, $srcFee, $objSales, $objCommission, $tDate, $memo)
    {
        $sql = "INSERT INTO salescommission VALUES ";
        $sql .= "({$srcComp}, {$srcSales}, {$srcSett}, {$srcStock}, {$objSales}, ";
        $sql .= "{$srcFee}, {$objCommission}, '{$tDate}', '{$memo}') ";

        $this->db->RunSql($sql);
    }

    public function getRows()
    {
        $sql = 'select srcComp, srcSales, srcSett, srcStock, srcFee, objSales, objCommission, tDate, memo ';
        $sql .= 'from salesCommission order by srcComp, srcSales, srcSett, srcStock, srcFee';

        $rs = $this->db->GetRows($sql, TRUE);
        return $rs;
    }

    public function commRead($srcComp, $srcSales, $srcSett, $srcStock, $srcFee)
    {
        $sql = "select * from salescommission WHERE srcComp={$srcComp} ";
        $sql .= "AND srcSales={$srcSales} AND srcSett={$srcSett} ";
        $sql .= "AND srcStock={$srcStock} AND srcFee={$srcFee} ";

        $rs = $this->db->GetRows($sql, TRUE);
        return $rs;
    }

}