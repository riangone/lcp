<?php
// 共通クラスの読込み
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE310HSYAINMSTEntry extends ClsComDb
{
    public function UpdateDataSql($SYAINNO)
    {
        $strSQL = "";
        $strSQL .= " SELECT SYA.SYAIN_NO                     " . "\r\n";
        $strSQL .= " ,      SYA.SYAIN_NM                     " . "\r\n";
        $strSQL .= " ,      SYA.SYAIN_KN                     " . "\r\n";
        $strSQL .= " ,      SYA.SIKAKU_CD                    " . "\r\n";
        $strSQL .= " ,      SYA.SLSSUTAFF_KB                 " . "\r\n";
        $strSQL .= " ,      TO_CHAR(TO_DATE(SYA.TAISYOKU_DATE,'YYYY/MM/DD'),'YYYY/MM/DD') TAISYOKU_DATE" . "\r\n";
        $strSQL .= " ,      SYA.UPD_PRG_ID                   " . "\r\n";
        $strSQL .= " ,      BUS.BUSYO_CD                     " . "\r\n";
        $strSQL .= " ,      HAI.SYUKEI_BUSYO_CD              " . "\r\n";
        $strSQL .= " ,      TO_CHAR(TO_DATE(HAI.START_DATE,'YYYY/MM/DD'),'YYYY/MM/DD') START_DATE " . "\r\n";
        $strSQL .= " ,      TO_CHAR(TO_DATE(DECODE(HAI.END_DATE,'99999999','',HAI.END_DATE),'YYYY/MM/DD'),'YYYY/MM/DD') END_DATE" . "\r\n";
        $strSQL .= " ,      HAI.SYOKUSYU_KB                  " . "\r\n";
        $strSQL .= " ,      HAI.DISP_KB                      " . "\r\n";
        $strSQL .= " ,      HAI.DAI_HYOUJI                   " . "\r\n";
        $strSQL .= " ,      CASE             " . "\r\n";
        $strSQL .= "          WHEN HAI.IVENT_TARGET_FLG  = '' THEN '1'" . "\r\n";
        $strSQL .= "          WHEN HAI.IVENT_TARGET_FLG IS NULL THEN '1'" . "\r\n";
        $strSQL .= "          ELSE  HAI.IVENT_TARGET_FLG          " . "\r\n";
        $strSQL .= "        END  AS IVENT_TARGET_FLG          " . "\r\n";
        $strSQL .= " ,      HAI.CREATE_DATE                  " . "\r\n";
        $strSQL .= " FROM   HSYAINMST SYA                    " . "\r\n";
        $strSQL .= " LEFT JOIN HHAIZOKU HAI                  " . "\r\n";
        $strSQL .= " ON     SYA.SYAIN_NO = HAI.SYAIN_NO      " . "\r\n";
        $strSQL .= " LEFT JOIN HBUSYO BUS                    " . "\r\n";
        $strSQL .= " ON	   BUS.BUSYO_CD = HAI.BUSYO_CD      " . "\r\n";
        $strSQL .= " WHERE  SYA.SYAIN_NO = '@SYAINNO'        " . "\r\n";
        $strSQL .= " ORDER BY HAI.START_DATE                 " . "\r\n";

        $strSQL = str_replace("@SYAINNO", $SYAINNO, $strSQL);
        return parent::select($strSQL);
    }

    public function getSqlCheck($SYAINNO)
    {
        $strSQL = "";
        $strSQL .= " SELECT UPD_PRG_ID" . "\r\n";
        $strSQL .= "  FROM   HSYAINMST " . "\r\n";
        $strSQL .= " WHERE  SYAIN_NO = '@SYAINNO'" . "\r\n";

        $strSQL = str_replace("@SYAINNO", $SYAINNO, $strSQL);
        return parent::select($strSQL);
    }

    public function insertHSYAINMST($postdata)
    {
        $strSQL = "";
        $strSQL .= " INSERT INTO HSYAINMST                 " . "\r\n";
        $strSQL .= " (SYAIN_NO,                                  " . "\r\n";
        $strSQL .= "  SYAIN_NM,                                  " . "\r\n";
        $strSQL .= "  SYAIN_KN,                                  " . "\r\n";
        $strSQL .= "  SIKAKU_CD,                                 " . "\r\n";
        $strSQL .= "  SLSSUTAFF_KB,                              " . "\r\n";
        $strSQL .= "  TAISYOKU_DATE,                             " . "\r\n";
        $strSQL .= "  UPD_DATE,                                  " . "\r\n";
        $strSQL .= "  CREATE_DATE,                               " . "\r\n";
        $strSQL .= "  UPD_SYA_CD,                                " . "\r\n";
        $strSQL .= "  UPD_PRG_ID,                                " . "\r\n";
        $strSQL .= "  UPD_CLT_NM)                                " . "\r\n";
        $strSQL .= "  VALUES(                                    " . "\r\n";
        $strSQL .= "  '@SYAINNO',                                " . "\r\n";
        $strSQL .= "  '@SYAINNM',                                " . "\r\n";
        $strSQL .= "  '@SYAINKN',                                " . "\r\n";
        $strSQL .= "  '@SIKAKU',                                 " . "\r\n";
        $strSQL .= "  '@SUTAFF',                                 " . "\r\n";
        $strSQL .= "  '@TAISYOKU',                               " . "\r\n";
        $strSQL .= "  SYSDATE,                                   " . "\r\n";
        $strSQL .= "  SYSDATE,                                   " . "\r\n";
        $strSQL .= "  '@SYACD',                                  " . "\r\n";
        $strSQL .= "  '@PRGID',                                  " . "\r\n";
        $strSQL .= "  '@CLTNM')                                  " . "\r\n";

        $strSQL = str_replace("@SYAINNO", $postdata['SYAIN_NO'], $strSQL);
        $strSQL = str_replace("@SYAINNM", $postdata['SYAINNM'], $strSQL);
        $strSQL = str_replace("@SYAINKN", $postdata['SYAINKN'], $strSQL);
        $strSQL = str_replace("@SIKAKU", $postdata['SIKAKU'], $strSQL);
        $strSQL = str_replace("@SUTAFF", $postdata['SUTAFF'], $strSQL);
        $strSQL = str_replace("@TAISYOKU", str_replace("/", '', $postdata['TAISYOKU']), $strSQL);
        $strSQL = str_replace("@SYACD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@PRGID", "HSYAINMSTENTRY", $strSQL);
        $strSQL = str_replace("@CLTNM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        return parent::insert($strSQL);
    }

    public function DeleteHHAIZOKU($SYAINNO)
    {
        $strSQL = "";
        $strSQL .= " DELETE FROM HHAIZOKU              " . "\r\n";
        $strSQL .= " WHERE   SYAIN_NO = '@SYAINNO'           " . "\r\n";
        $strSQL .= " AND     UPD_PRG_ID = 'HSYAINMSTENTRY'   " . "\r\n";

        $strSQL = str_replace("@SYAINNO", $SYAINNO, $strSQL);
        return parent::delete($strSQL);
    }

    public function insertHHAIZOKU($postdata, $tableData, $n)
    {
        $strSQL = "";
        $strSQL .= " INSERT INTO HHAIZOKU                  " . "\r\n";
        $strSQL .= " (SYAIN_NO,                                  " . "\r\n";
        $strSQL .= "  RIR_NO,                                    " . "\r\n";
        $strSQL .= "  BUSYO_CD,                                  " . "\r\n";
        $strSQL .= "  SYUKEI_BUSYO_CD,                           " . "\r\n";
        $strSQL .= "  START_DATE,                                " . "\r\n";
        $strSQL .= "  END_DATE,                                  " . "\r\n";
        $strSQL .= "  SYOKUSYU_KB,                               " . "\r\n";
        $strSQL .= "  DISP_KB,                                   " . "\r\n";
        $strSQL .= "  DAI_HYOUJI,                                " . "\r\n";
        $strSQL .= "  IVENT_TARGET_FLG,                          " . "\r\n";
        $strSQL .= "  UPD_DATE,                                  " . "\r\n";
        $strSQL .= "  CREATE_DATE,                               " . "\r\n";
        $strSQL .= "  UPD_SYA_CD,                                " . "\r\n";
        $strSQL .= "  UPD_PRG_ID,                                " . "\r\n";
        $strSQL .= "  UPD_CLT_NM)                                " . "\r\n";
        $strSQL .= "  VALUES(                                    " . "\r\n";
        $strSQL .= "  '@SYAINNO',                                " . "\r\n";
        $strSQL .= "  '@RIR',                                    " . "\r\n";
        $strSQL .= "  '@DISPOSE',                                " . "\r\n";
        $strSQL .= "  '@SDISPOSE',                               " . "\r\n";
        $strSQL .= "  '@START',                                  " . "\r\n";
        $strSQL .= "  '@END',                                    " . "\r\n";
        $strSQL .= "  '@SYOKUSYU',                               " . "\r\n";
        $strSQL .= "  '@DISP',                                   " . "\r\n";
        $strSQL .= "  '@DAI',                                    " . "\r\n";
        $strSQL .= "  '@RADIO',                                " . "\r\n";
        $strSQL .= "  SYSDATE,                                   " . "\r\n";
        $strSQL .= "  SYSDATE,                                   " . "\r\n";
        $strSQL .= "  '@SYACD',                                  " . "\r\n";
        $strSQL .= "  '@PRGID',                                  " . "\r\n";
        $strSQL .= "  '@CL_NM')                                  " . "\r\n";

        $strSQL = str_replace("@SYAINNO", $postdata['SYAIN_NO'], $strSQL);
        $strSQL = str_replace("@RIR", $n, $strSQL);
        $strSQL = str_replace("@DISPOSE", $tableData['BUSYO_CD'], $strSQL);
        $strSQL = str_replace("@SDISPOSE", $tableData['SYUKEI_BUSYO_CD'], $strSQL);
        $strSQL = str_replace("@START", str_replace("/", '', $tableData['START_DATE']), $strSQL);
        $strSQL = str_replace("@END", str_replace("/", '', $tableData['END_DATE']), $strSQL);
        $strSQL = str_replace("@SYOKUSYU", $tableData['SYOKUSYU_KB'], $strSQL);
        $strSQL = str_replace("@DISP", $tableData['DISP_KB'], $strSQL);
        $strSQL = str_replace("@DAI", $tableData['DAI_HYOUJI'], $strSQL);
        $strSQL = str_replace("@RADIO", $tableData['rdoTenjikai'], $strSQL);

        $strSQL = str_replace("@SYACD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@PRGID", "HSYAINMSTENTRY", $strSQL);
        $strSQL = str_replace("@CL_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        return parent::insert($strSQL);
    }

    public function fncUpdHaizokusaki($postdata, $tableData)
    {
        $strSQL = "";
        $strSQL .= " UPDATE HHAIZOKU            " . "\r\n";
        $strSQL .= " SET    IVENT_TARGET_FLG = '@FLG' " . "\r\n";
        $strSQL .= " ,      UPD_DATE = SYSDATE" . "";
        $strSQL .= " ,      UPD_SYA_CD = '@UPDSYACD'" . "\r\n";
        $strSQL .= " ,      UPD_CLT_NM = '@UPDCLTNM'" . "\r\n";
        $strSQL .= " WHERE  SYAIN_NO = '@SYAINNO'     " . "\r\n";
        $strSQL .= " AND    START_DATE = '@STARTDT'         " . "\r\n";

        $strSQL = str_replace("@FLG", $tableData['rdoTenjikai'], $strSQL);
        $strSQL = str_replace("@STARTDT", str_replace("/", '', $tableData['START_DATE']), $strSQL);
        $strSQL = str_replace("@SYAINNO", $postdata['SYAIN_NO'], $strSQL);
        $strSQL = str_replace("@UPDSYACD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPDCLTNM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        return parent::update($strSQL);
    }

    public function updateHSYAINMST($postdata)
    {
        $strSQL = "";
        $strSQL .= " UPDATE HSYAINMST                              " . "\r\n";
        $strSQL .= " SET                                                 " . "\r\n";
        $strSQL .= "  SYAIN_NO = '@SYAINNO',                             " . "\r\n";
        $strSQL .= "  SYAIN_NM = '@SYAINNM',                             " . "\r\n";
        $strSQL .= "  SYAIN_KN = '@SYAINKN',                             " . "\r\n";
        $strSQL .= "  SIKAKU_CD = '@SIKAKUCD',                           " . "\r\n";
        $strSQL .= "  SLSSUTAFF_KB = '@SUTAFF',                          " . "\r\n";
        $strSQL .= "  TAISYOKU_DATE = '@TAISYOKU',                       " . "\r\n";
        $strSQL .= "  UPD_DATE = SYSDATE,                                " . "\r\n";
        $strSQL .= "  CREATE_DATE = SYSDATE,                             " . "\r\n";
        $strSQL .= "  UPD_SYA_CD = '@SYACD',                             " . "\r\n";
        $strSQL .= "  UPD_PRG_ID = '@PRGID',                             " . "\r\n";
        $strSQL .= "  UPD_CLT_NM = '@CLTNM'                              " . "\r\n";
        $strSQL .= "  WHERE SYAIN_NO='@SYAINNO'                          " . "\r\n";

        $strSQL = str_replace("@SYAINNO", $postdata['SYAIN_NO'], $strSQL);
        $strSQL = str_replace("@SYAINNM", $postdata['SYAINNM'], $strSQL);
        $strSQL = str_replace("@SYAINKN", $postdata['SYAINKN'], $strSQL);
        $strSQL = str_replace("@SIKAKUCD", $postdata['SIKAKU'], $strSQL);
        $strSQL = str_replace("@SUTAFF", $postdata['SUTAFF'], $strSQL);
        $strSQL = str_replace("@TAISYOKU", str_replace("/", '', $postdata['TAISYOKU']), $strSQL);

        $strSQL = str_replace("@SYACD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@PRGID", "HSYAINMSTENTRY", $strSQL);
        $strSQL = str_replace("@CLTNM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        return parent::update($strSQL);
    }

    public function DeleteHSYAINMST($SYAINNO)
    {
        $strSQL = "";
        $strSQL .= " DELETE FROM HSYAINMST             " . "\r\n";
        $strSQL .= " WHERE SYAIN_NO = '@SYAINNO'             " . "\r\n";
        $strSQL .= " AND UPD_PRG_ID = 'HSYAINMSTENTRY'       " . "\r\n";

        $strSQL = str_replace("@SYAINNO", $SYAINNO, $strSQL);
        return parent::delete($strSQL);
    }

}
