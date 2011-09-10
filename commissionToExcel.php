<?php

class CommissionToExcel
{

    private $excel;
    private $workbook;
    private $tYY;
    private $tMM;

    public function __construct($fileName, $tYY, $tMM)
    {
        $this->tYY = $tYY;
        $this->tMM = $tMM;

        $this->excel = new ExcelUti('new', (dirname(__FILE__) . '\\excel\\' . "{$fileName}.xls"));
        $this->workbook = $this->excel->getWorkbook();
        $this->sheet2 = $this->workbook->Sheets("sheet2");
    }

    public function SaveAS()
    {
        $this->excel->SaveAs($this->excel->u2b(dirname(__FILE__) . '\\excel\\' . "comm-{$this->tYY}{$this->tMM}.xls"));
    }

    public function Quit()
    {
        $this->excel->Quit();
    }

    public function putRs($rs)
    {
        $sheet = $this->workbook->Sheets("sheet1");

        $sheet->Cells(1, 1)->Value = $this->excel->u2b("公司");
        $sheet->Cells(1, 2)->Value = $this->excel->u2b("營業員");
        $sheet->Cells(1, 3)->Value = $this->excel->u2b("客戶");
        $sheet->Cells(1, 4)->Value = $this->excel->u2b("姓名");
        $sheet->Cells(1, 5)->Value = $this->excel->u2b("商品");
        $sheet->Cells(1, 6)->Value = $this->excel->u2b("來源");
        $sheet->Cells(1, 7)->Value = $this->excel->u2b("口數");
        $sheet->Cells(1, 8)->Value = $this->excel->u2b("手續費");

        $rowX = 2;
        foreach ($rs as $row)
        {
            $sheet->Cells($rowX, 1)->Value = $row['COMP'];
            $sheet->Cells($rowX, 2)->Value = $row['SRTR'];
            $sheet->Cells($rowX, 3)->Value = $row['SETT'];
            $sheet->Cells($rowX, 4)->Value = $row['CUNA'];
            $sheet->Cells($rowX, 5)->Value = $row['SYMB'];
            $sheet->Cells($rowX, 6)->Value = $row['STAT'];
            $sheet->Cells($rowX, 7)->Value = $row['QTY'];
            $sheet->Cells($rowX, 8)->Value = $row['FEE'];

            $rowX++;
        }
    }

    public function putStep1Rs($rs)
    {
        $sheet = $this->workbook->Sheets("sheet2");

        $sheet->Cells(1, 1)->Value = $this->excel->u2b("公司");
        $sheet->Cells(1, 2)->Value = $this->excel->u2b("營業員");
        $sheet->Cells(1, 3)->Value = $this->excel->u2b("客戶");
        $sheet->Cells(1, 4)->Value = $this->excel->u2b("商品");
        $sheet->Cells(1, 5)->Value = $this->excel->u2b("來源");
        $sheet->Cells(1, 6)->Value = $this->excel->u2b("口數");
        $sheet->Cells(1, 7)->Value = $this->excel->u2b("手續費");
        $sheet->Cells(1, 8)->Value = $this->excel->u2b("佣金");
        $sheet->Cells(1, 9)->Value = $this->excel->u2b("貢獻額");

        $rowX = 2;
        foreach ($rs as $key => $value)
        {
            $comp = substr($key, 0, 5);
            $sales = substr($key, 5, 5);
            $sett = substr($key, 10, 7);
            $keyinOrNet = substr($key, 17,1);
            $futOpt = substr($key, 18,1);
            $unitPrice = substr($key, 19,4);

            $sheet->Cells($rowX, 1)->Value = $comp;
            $sheet->Cells($rowX, 2)->Value = $sales;
            $sheet->Cells($rowX, 3)->Value = $sett;
            $sheet->Cells($rowX, 4)->Value = $futOpt;
            $sheet->Cells($rowX, 5)->Value = $keyinOrNet;
            $sheet->Cells($rowX, 6)->Value = $value['qty'];
            $sheet->Cells($rowX, 7)->Value = $value['fee'];
            $sheet->Cells($rowX, 8)->Value = $value['commission'];
            $sheet->Cells($rowX, 9)->Value = $value['contribution'];

            $rowX++;
        }
    }

}

