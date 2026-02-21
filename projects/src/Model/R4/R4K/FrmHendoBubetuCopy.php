<?php
// 共通クラスの読込み
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmHendoBubetuCopy extends ClsComDb
{
    // 20131004 kamei add Start
    // 解放が必要な変数をメンバーに設定
    // protected $conn_orl = "";
    // protected $Sel_Array = "";


    function fncInsertStaffKoumokuSql($postData = NULL)
    {
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDAPP = "BubetuCopy";
        $UPDCLT = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";
        $strSQL .= "INSERT INTO HSTAFFKOUMOKU" . "\r\n";
        $strSQL .= "(           KEIJO_DT" . "\r\n";
        $strSQL .= ",           BUSYO_CD" . "\r\n";
        $strSQL .= ",           SYAIN_NO" . "\r\n";
        $strSQL .= ",           ITEM_CD" . "\r\n";
        $strSQL .= ",           KEIJYO_GK" . "\r\n";
        $strSQL .= ",           DATA_KB" . "\r\n";
        $strSQL .= ",           UPD_DATE" . "\r\n";
        $strSQL .= ",           CREATE_DATE" . "\r\n";
        $strSQL .= ",           DISP_NO" . "\r\n";
        $strSQL .= ",           UPD_SYA_CD" . "\r\n";
        $strSQL .= ",           UPD_PRG_ID" . "\r\n";
        $strSQL .= ",           UPD_CLT_NM" . "\r\n";
        $strSQL .= ")" . "\r\n";
        $strSQL .= "SELECT      '@TOUGETU'" . "\r\n";
        $strSQL .= ",           BUSYO_CD" . "\r\n";
        $strSQL .= ",           SYAIN_NO" . "\r\n";
        $strSQL .= ",           ITEM_CD" . "\r\n";
        $strSQL .= ",           KEIJYO_GK" . "\r\n";
        $strSQL .= ",           DATA_KB" . "\r\n";
        $strSQL .= ",           SYSDATE" . "\r\n";
        $strSQL .= ",           SYSDATE" . "\r\n";
        $strSQL .= ",           DISP_NO" . "\r\n";
        $strSQL .= ",           '@UPDUSER'" . "\r\n";
        $strSQL .= ",           '@UPDAPP'" . "\r\n";
        $strSQL .= ",           '@UPDCLT'" . "\r\n";

        $strSQL .= "FROM        HSTAFFKOUMOKU" . "\r\n";
        $strSQL .= "WHERE       KEIJO_DT = '@ZENGETU'" . "\r\n";
        $strSQL .= "AND         SYAIN_NO = '00000'" . "\r\n";

        $strSQL = str_replace("@TOUGETU", str_replace("/", "", $postData['TOUGETU']), $strSQL);
        $ZENGETUM = substr($postData['ZENGETU'], 5, 2);
        $ZENGETUY = substr($postData['ZENGETU'], 0, 4);
        $ZENGETUM = (int) $ZENGETUM - 1;
        if ($ZENGETUM == 0) {
            $ZENGETUM = 12;
            $ZENGETUY = (int) $ZENGETUY - 1;
            $ZENGETU = $ZENGETUY . $ZENGETUM;
        } else
            if ($ZENGETUM > 0 && $ZENGETUM <= 9) {
                $ZENGETUM = '0' . (string) $ZENGETUM;
                $ZENGETU = $ZENGETUY . $ZENGETUM;
            } else {
                $ZENGETU = $ZENGETUY . $ZENGETUM;
            }
        $strSQL = str_replace("@ZENGETU", $ZENGETU, $strSQL);
        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        $strSQL = str_replace("@UPDAPP", $UPDAPP, $strSQL);
        $strSQL = str_replace("@UPDCLT", $UPDCLT, $strSQL);
        return $strSQL;

    }

    function fncDeleteStaffKoumokuSql($postData = NULL)
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM HSTAFFKOUMOKU A" . "\r\n";
        $strSQL .= "WHERE  EXISTS " . "\r\n";
        $strSQL .= "       (SELECT B.KEIJO_DT" . "\r\n";
        $strSQL .= "        ,      B.BUSYO_CD" . "\r\n";
        $strSQL .= "        ,      B.SYAIN_NO" . "\r\n";
        $strSQL .= "        ,      B.ITEM_CD" . "\r\n";
        $strSQL .= "        FROM   HSTAFFKOUMOKU B" . "\r\n";
        $strSQL .= "        WHERE  A.KEIJO_DT = '@TOUGETU'" . "\r\n";
        $strSQL .= "        AND    A.BUSYO_CD = B.BUSYO_CD" . "\r\n";
        $strSQL .= "        AND    A.SYAIN_NO = B.SYAIN_NO" . "\r\n";
        $strSQL .= "        AND    B.SYAIN_NO = '00000'" . "\r\n";
        $strSQL .= "        AND    A.ITEM_CD = B.ITEM_CD" . "\r\n";
        $strSQL .= "        AND    B.KEIJO_DT = '@ZENGETU')" . "\r\n";

        $strSQL = str_replace("@TOUGETU", str_replace("/", "", $postData['TOUGETU']), $strSQL);
        $ZENGETUM = substr($postData['ZENGETU'], 5, 2);
        $ZENGETUY = substr($postData['ZENGETU'], 0, 4);
        $ZENGETUM = (int) $ZENGETUM - 1;
        if ($ZENGETUM == 0) {
            $ZENGETUM = 12;
            $ZENGETUY = (int) $ZENGETUY - 1;
            $ZENGETU = $ZENGETUY . $ZENGETUM;
        } else
            if ($ZENGETUM > 0 && $ZENGETUM <= 9) {
                $ZENGETUM = '0' . (string) $ZENGETUM;
                $ZENGETU = $ZENGETUY . $ZENGETUM;
            } else {
                $ZENGETU = $ZENGETUY . $ZENGETUM;
            }
        $strSQL = str_replace("@ZENGETU", $ZENGETU, $strSQL);
        return $strSQL;

    }

    function fncExistCheckSelSql($postData = NULL)
    {
        $strSQL = "";
        $strSQL .= "SELECT B.KEIJO_DT" . "\r\n";
        $strSQL .= ",      B.BUSYO_CD" . "\r\n";
        $strSQL .= ",      B.SYAIN_NO" . "\r\n";
        $strSQL .= ",      B.ITEM_CD" . "\r\n";
        $strSQL .= "FROM   HSTAFFKOUMOKU B" . "\r\n";
        $strSQL .= ",      HSTAFFKOUMOKU A" . "\r\n";
        $strSQL .= "WHERE  A.KEIJO_DT = '@TOUGETU'" . "\r\n";
        $strSQL .= "AND    A.BUSYO_CD = B.BUSYO_CD" . "\r\n";
        $strSQL .= "AND    A.SYAIN_NO = B.SYAIN_NO" . "\r\n";
        $strSQL .= "AND    B.SYAIN_NO = '00000'" . "\r\n";
        $strSQL .= "AND    A.ITEM_CD = B.ITEM_CD" . "\r\n";
        $strSQL .= "AND    B.KEIJO_DT = '@ZENGETU'" . "\r\n";

        $strSQL = str_replace("@TOUGETU", str_replace("/", "", $postData['TOUGETU']), $strSQL);
        $ZENGETUM = substr($postData['ZENGETU'], 5, 2);
        $ZENGETUY = substr($postData['ZENGETU'], 0, 4);
        $ZENGETUM = (int) $ZENGETUM - 1;
        if ($ZENGETUM == 0) {
            $ZENGETUM = 12;
            $ZENGETUY = (int) $ZENGETUY - 1;
            $ZENGETU = $ZENGETUY . $ZENGETUM;
        } else
            if ($ZENGETUM > 0 && $ZENGETUM <= 9) {
                $ZENGETUM = '0' . (string) $ZENGETUM;
                $ZENGETU = $ZENGETUY . $ZENGETUM;
            } else {
                $ZENGETU = $ZENGETUY . $ZENGETUM;
            }
        $strSQL = str_replace("@ZENGETU", $ZENGETU, $strSQL);
        return $strSQL;
    }

    public function fncExistCheckSel($postData = NULL)
    {
        return parent::select($this->fncExistCheckSelSql($postData));
    }

    public function fncDeleteStaffKoumoku($postData = NULL)
    {
        return parent::Do_Execute($this->fncDeleteStaffKoumokuSql($postData));
    }

    public function fncInsertStaffKoumoku($postData = NULL)
    {
        return parent::Do_Execute($this->fncInsertStaffKoumokuSql($postData));
    }



}
