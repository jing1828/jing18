<?php

class SalesNameSet
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

    public function salesNameDelete($comp, $sales)
    {
        $sql = "DELETE FROM salesName WHERE comp={$comp} and sales={$sales} ";
        $this->db->RunSql($sql);
    }

    public function salesNameUpdate($comp, $sales, $empid, $sname, $tDate, $memo)
    {
        $sql = 'update salesName set ';
        $sql .= "empid={$empid}, sname='{$sname}', tDate='{$tDate}', memo='{$memo}' ";
        $sql .= "where comp={$comp} and sales={$sales} ";

        $this->db->RunSql($sql);
    }

    public function salesNameAdd($comp, $sales, $empid, $sname, $tDate, $memo)
    {
        $sql = "INSERT INTO salesName VALUES ";
        $sql .= "({$comp}, {$sales}, {$empid}, '{$sname}', '{$tDate}', '{$memo}') ";

        $this->db->RunSql($sql);
    }

    public function getRows()
    {
        $sql = 'select * ';
        $sql .= 'from salesName order by comp, sales';

        $rs = $this->db->GetRows($sql, TRUE);
        return $rs;
    }

    public function salesNameRead($comp, $sales)
    {
        $sql = "select * from salesName WHERE comp={$comp} AND sales={$sales} ";

        $rs = $this->db->GetRows($sql, TRUE);
        return $rs;
    }

}