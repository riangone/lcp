<?php
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;
use App\Model\JKSYS\Component\ClsComFncJKSYS;

//*************************************
// * 処理名	：FrmExcelTorikomi
// * 関数名	：FrmExcelTorikomi
// * 処理説明	：共通クラスの読込み
//*************************************
class FrmExcelTorikomiKouka extends ClsComDb
{
    public $ClsComFncJKSYS;
    
    // * 処理名	：fncSelectMaxListMeisaiNo
    // * 関数名	：fncSelectMaxListMeisaiNo
    // * 処理説明	：SELECT文を返す（リスト明細ＮＯの最大値取得）
    public function fncSelectMaxListMeisaiNo($vTableNm)
    {
        $strSql = $this->fncSelectMaxListMeisaiNoSQL($vTableNm);
        return parent::select($strSql);
    }

    // * 処理名	：fncSelectMaxListMeisaiNoSQL
    // * 関数名	：fncSelectMaxListMeisaiNoSQL
    // * 処理説明	：SELECT文を返す（リスト明細ＮＯの最大値取得）SQL
    public function fncSelectMaxListMeisaiNoSQL($vTableNm)
    {
        $strSQL = "";
        $strSQL = "SELECT  ";
        $strSQL .= " NVL(MAX(LIST_MEISAI_NO),0) as MAXNO " . "\r\n";
        $strSQL .= " FROM  " . "\r\n";
        $strSQL .= " @TABLENAME ";
        $strSQL = str_replace('@TABLENAME', $vTableNm, $strSQL);

        return $strSQL;
    }

    // * 処理名	：fncSelectMeisaiExist
    // * 関数名	：fncSelectMeisaiExist
    // * 処理説明	：SELECT文を返す（同一年月データ存在チェック）
    public function fncSelectMeisaiExist($mode, $vTableNm, $vFROM, $vTO)
    {
        $strSql = $this->fncSelectMeisaiExistSQL($mode, $vTableNm, $vFROM, $vTO);
        return parent::select($strSql);
    }

    // * 処理名	：fncSelectMeisaiExistSQL
    // * 関数名	：fncSelectMeisaiExistSQL
    // * 処理説明	：SELECT文を返す（同一年月データ存在チェック）
    public function fncSelectMeisaiExistSQL($mode, $vTableNm, $vFROM, $vTO)
    {
        $strSQL = "";
        $strSQL = " SELECT  " . "\r\n";
        $strSQL .= "  * " . "\r\n";
        $strSQL .= " FROM " . "\r\n";
        $strSQL .= "  @TABLENAME " . "\r\n";
        $strSQL .= " WHERE " . "\r\n";
        if ($mode == '1') {
            //考課表_ボディコーティング
            $strSQL .= "  VALUE5 BETWEEN '@VFROM' AND '@VTO' " . "\r\n";
        } else {
            //考課表_延長保証
            $strSQL .= "  VALUE3 BETWEEN '@VFROM' AND '@VTO' " . "\r\n";
        }
        $strSQL = str_replace('@TABLENAME', $vTableNm, $strSQL);
        $strSQL = str_replace('@VFROM', $vFROM, $strSQL);
        $strSQL = str_replace('@VTO', $vTO, $strSQL);
        return $strSQL;
    }

    // * 処理名	：fncDeleteMeisaiExist
    // * 関数名	：fncDeleteMeisaiExist
    // * 処理説明	：DELETE文を返す（同一年月データ存在チェック）
    public function fncDeleteMeisaiExist($mode, $vTableNm, $vFROM, $vTO)
    {
        $strSql = $this->fncDeleteMeisaiExistSQL($mode, $vTableNm, $vFROM, $vTO);
        return parent::delete($strSql);
    }

    // * 処理名	：fncDeleteMeisaiExistSQL
    // * 関数名	：fncDeleteMeisaiExistSQL
    // * 処理説明	：DELETE文を返す（同一年月データ存在チェック）SQL
    public function fncDeleteMeisaiExistSQL($mode, $vTableNm, $vFROM, $vTO)
    {
        $strSQL = "";
        $strSQL = " DELETE  " . "\r\n";
        $strSQL .= " FROM " . "\r\n";
        $strSQL .= "  @TABLENAME " . "\r\n";
        $strSQL .= " WHERE " . "\r\n";
        if ($mode == '1') {
            //考課表_ボディコーティング
            $strSQL .= "  VALUE5 BETWEEN '@VFROM' AND '@VTO' " . "\r\n";
        } else {
            //考課表_延長保証
            $strSQL .= "  VALUE3 BETWEEN '@VFROM' AND '@VTO' " . "\r\n";
        }
        $strSQL = str_replace('@TABLENAME', $vTableNm, $strSQL);
        $strSQL = str_replace('@VFROM', $vFROM, $strSQL);
        $strSQL = str_replace('@VTO', $vTO, $strSQL);
        return $strSQL;
    }

    // * 処理名	：InsertData
    // * 関数名	：InsertData
    // * 処理説明	：DB新規追加
    public function InsertData($vMode, $vTableNm, $vAry, $MeisaiNo)
    {
        $strSql = $this->InsertDataSQL($vMode, $vTableNm, $vAry, $MeisaiNo);
        return parent::insert($strSql);
    }

    //指定された文字でパディング
    //<param name="target">対象となる文字列</param>
    //<param name="padChar">パディングする文字列</param>
    public function fncPadding($target, $padChar, $length)
    {
        $wkStr = $target;
        for ($i = 0; $i < $length; $i++) {
            $wkStr = $padChar . $wkStr;
        }
        $wkStr = substr($wkStr, strlen($wkStr) - $length, $length);
        return $wkStr;
    }

    // * 処理名	：InsertDataSQL
    // * 関数名	：InsertDataSQL
    // * 処理説明	：DB新規追加SQL
    public function InsertDataSQL($vMode, $vTableNm, $vAry, $MeisaiNo)
    {
        ini_set('precision', 14);
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();
        $strSQL = "";
        $strSQL = "Insert into @TABLENM " . "\r\n";
        $strSQL .= "( " . "\r\n";
        $strSQL .= " LIST_MEISAI_NO " . "\r\n";
        $strSQL .= " ,VALUE1 " . "\r\n";
        $strSQL .= " ,VALUE2 " . "\r\n";
        $strSQL .= " ,VALUE3 " . "\r\n";
        $strSQL .= " ,VALUE4 " . "\r\n";
        $strSQL .= " ,VALUE5 " . "\r\n";
        $strSQL .= " ,VALUE6 " . "\r\n";
        $strSQL .= " ,VALUE7 " . "\r\n";
        $strSQL .= " ,VALUE8 " . "\r\n";
        $strSQL .= " ,VALUE9 " . "\r\n";
        $strSQL .= " ,VALUE10 " . "\r\n";
        $strSQL .= " ,VALUE11 " . "\r\n";
        $strSQL .= " ,VALUE12 " . "\r\n";
        $strSQL .= " ,VALUE13 " . "\r\n";
        $strSQL .= " ,VALUE14 " . "\r\n";
        $strSQL .= " ,VALUE15 " . "\r\n";
        $strSQL .= " ,VALUE16 " . "\r\n";
        $strSQL .= " ,VALUE17 " . "\r\n";
        $strSQL .= " ,VALUE18 " . "\r\n";
        $strSQL .= " ,VALUE19 " . "\r\n";
        $strSQL .= " ,VALUE20 " . "\r\n";
        $strSQL .= " ,CRE_BUSYO_CD " . "\r\n";
        $strSQL .= " ,CRE_SYA_CD " . "\r\n";
        $strSQL .= " ,CRE_CLT_NM " . "\r\n";
        $strSQL .= " ,CRE_DATE " . "\r\n";
        $strSQL .= " ,UPD_BUSYO_CD " . "\r\n";
        $strSQL .= " ,UPD_SYA_CD " . "\r\n";
        $strSQL .= " ,UPD_CLT_NM " . "\r\n";
        $strSQL .= " ,UPD_DATE " . "\r\n";
        $strSQL .= " ,UPD_PRG_ID " . "\r\n";
        $strSQL .= ")  " . "\r\n";
        $strSQL .= "Values( " . "\r\n";
        $strSQL .= "  @LIST_MEISAI_NO " . "\r\n";
        $strSQL .= " ,'@VALUE01' " . "\r\n";
        $strSQL .= " ,'@VALUE02' " . "\r\n";
        $strSQL .= " ,'@VALUE03' " . "\r\n";
        $strSQL .= " ,'@VALUE04' " . "\r\n";
        $strSQL .= " ,'@VALUE05' " . "\r\n";
        $strSQL .= " ,'@VALUE06' " . "\r\n";
        $strSQL .= " ,'@VALUE07' " . "\r\n";
        $strSQL .= " ,'@VALUE08' " . "\r\n";
        $strSQL .= " ,'@VALUE09' " . "\r\n";
        $strSQL .= " ,'@VALUE10' " . "\r\n";
        $strSQL .= " ,'@VALUE11' " . "\r\n";
        $strSQL .= " ,'@VALUE12' " . "\r\n";
        $strSQL .= " ,'@VALUE13' " . "\r\n";
        $strSQL .= " ,'@VALUE14' " . "\r\n";
        $strSQL .= " ,'@VALUE15' " . "\r\n";
        $strSQL .= " ,'@VALUE16' " . "\r\n";
        $strSQL .= " ,'@VALUE17' " . "\r\n";
        $strSQL .= " ,'@VALUE18' " . "\r\n";
        $strSQL .= " ,'@VALUE19' " . "\r\n";
        $strSQL .= " ,'@VALUE20' " . "\r\n";
        $strSQL .= " ,@BUSYO " . "\r\n";
        $strSQL .= " ,@USERID " . "\r\n";
        $strSQL .= " ,@CLIENT " . "\r\n";
        $strSQL .= " ,SYSDATE " . "\r\n";
        $strSQL .= " ,@BUSYO " . "\r\n";
        $strSQL .= " ,@USERID " . "\r\n";
        $strSQL .= " ,@CLIENT " . "\r\n";
        $strSQL .= " ,SYSDATE " . "\r\n";
        $strSQL .= " ,@PRG_ID " . "\r\n";
        $strSQL .= ") ";
        switch ($vMode) {
            //考課表_ボディコーティング
            case '1':
                //お客様ＮＯ
                $strSQL = str_replace("@VALUE01", $vAry[0], $strSQL);
                //カーＮＯ
                $strSQL = str_replace("@VALUE02", $vAry[1], $strSQL);
                //担当者コード
                $strSQL = str_replace("@VALUE03", $this->fncPadding($vAry[2], "0", 5), $strSQL);
                //拠点コード
                $strSQL = str_replace("@VALUE04", $this->fncPadding($vAry[3], "0", 3), $strSQL);
                //販売日
                $strSQL = str_replace("@VALUE05", $vAry[4], $strSQL);
                $strSQL = str_replace("@VALUE06", $vAry[5], $strSQL);
                $strSQL = str_replace("@VALUE07", '', $strSQL);
                $strSQL = str_replace("@VALUE08", '', $strSQL);
                $strSQL = str_replace("@VALUE09", '', $strSQL);
                $strSQL = str_replace("@VALUE10", '', $strSQL);
                $strSQL = str_replace("@VALUE11", '', $strSQL);
                $strSQL = str_replace("@VALUE12", '', $strSQL);
                $strSQL = str_replace("@VALUE13", '', $strSQL);
                $strSQL = str_replace("@VALUE14", '', $strSQL);
                $strSQL = str_replace("@VALUE15", '', $strSQL);
                $strSQL = str_replace("@VALUE16", '', $strSQL);
                $strSQL = str_replace("@VALUE17", '', $strSQL);
                $strSQL = str_replace("@VALUE18", '', $strSQL);
                $strSQL = str_replace("@VALUE19", '', $strSQL);
                $strSQL = str_replace("@VALUE20", '', $strSQL);
                break;
            //考課表_延長保証
            case '2':
                //部署コード
                $strSQL = str_replace("@VALUE01", $this->fncPadding($vAry[0], "0", 3), $strSQL);
                //担当者コード
                $strSQL = str_replace("@VALUE02", $this->fncPadding($vAry[1], "0", 5), $strSQL);
                //加入年月日
                $strSQL = str_replace("@VALUE03", $vAry[2], $strSQL);
                $strSQL = str_replace("@VALUE04", $vAry[3], $strSQL);
                $strSQL = str_replace("@VALUE05", '', $strSQL);
                $strSQL = str_replace("@VALUE06", '', $strSQL);
                $strSQL = str_replace("@VALUE07", '', $strSQL);
                $strSQL = str_replace("@VALUE08", '', $strSQL);
                $strSQL = str_replace("@VALUE09", '', $strSQL);
                $strSQL = str_replace("@VALUE10", '', $strSQL);
                $strSQL = str_replace("@VALUE11", '', $strSQL);
                $strSQL = str_replace("@VALUE12", '', $strSQL);
                $strSQL = str_replace("@VALUE13", '', $strSQL);
                $strSQL = str_replace("@VALUE14", '', $strSQL);
                $strSQL = str_replace("@VALUE15", '', $strSQL);
                $strSQL = str_replace("@VALUE16", '', $strSQL);
                $strSQL = str_replace("@VALUE17", '', $strSQL);
                $strSQL = str_replace("@VALUE18", '', $strSQL);
                $strSQL = str_replace("@VALUE19", '', $strSQL);
                $strSQL = str_replace("@VALUE20", '', $strSQL);
                break;
        }
        $strSQL = str_replace("@TABLENM", $vTableNm, $strSQL);
        $strSQL = str_replace("@LIST_MEISAI_NO", $MeisaiNo, $strSQL);
        $strSQL = str_replace("@BUSYO", $this->ClsComFncJKSYS->FncSqlNv("112"), $strSQL);
        $strSQL = str_replace("@USERID", $this->ClsComFncJKSYS->FncSqlNv($this->GS_LOGINUSER['strUserID']), $strSQL);
        $strSQL = str_replace("@CLIENT", $this->ClsComFncJKSYS->FncSqlNv($this->GS_LOGINUSER['strClientNM']), $strSQL);
        $strSQL = str_replace("@PRG_ID", $this->ClsComFncJKSYS->FncSqlNv("ExcelTorikomiKouka"), $strSQL);

        return $strSQL;
    }

}
