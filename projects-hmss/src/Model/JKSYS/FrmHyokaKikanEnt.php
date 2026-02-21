<?php
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;

class FrmHyokaKikanEnt extends ClsComDb
{
    public function CheckExistSyokoukyuData($postdata)
    {
        $strSQL = "";
        $strSQL = " SELECT" . "\r\n";
        $strSQL .= " *" . "\r\n";
        $strSQL .= " FROM" . "\r\n";
        $strSQL .= " JKHYOUKAJISSHINENGETU" . "\r\n";
        $strSQL .= " WHERE JISSHI_YM = '" . $postdata['dtpJisshiYM'] . "'" . "\r\n";

        return parent::select($strSQL);
    }

    public function GetControlKakiMonth()
    {
        $strSQL = "";
        $strSQL = " SELECT" . "\r\n";
        $strSQL .= " KAKI_BONUS_MONTH" . "\r\n";
        $strSQL .= " FROM" . "\r\n";
        $strSQL .= " JKCONTROLMST" . "\r\n";
        $strSQL .= " WHERE" . "\r\n";
        $strSQL .= " ID = '02'" . "\r\n";

        return parent::select($strSQL);
    }

    //人事コントロールマスタの取得
    public function fncJinjiCtlMstSQL()
    {
        $strSQL = "";
        $strSQL = " SELECT ID " . "\r\n";
        $strSQL .= "     ,SYORI_YM" . "\r\n";
        $strSQL .= "      ,KAKI_BONUS_MONTH" . "\r\n";
        $strSQL .= "      ,KAKI_HYOUKA_START_MT" . "\r\n";
        $strSQL .= "      ,KAKI_HYOUKA_END_MT" . "\r\n";
        $strSQL .= "      ,TOUKI_BONUS_MONTH" . "\r\n";
        $strSQL .= "     ,TOUKI_HYOUKA_START_MT" . "\r\n";
        $strSQL .= "      ,TOUKI_HYOUKA_END_MT" . "\r\n";
        $strSQL .= " FROM JKCONTROLMST" . "\r\n";
        $strSQL .= " WHERE ID = '01'" . "\r\n";

        return parent::select($strSQL);
    }

    //評価実施年月データ取得SQL
    public function fncHyoukaJisshiYMDataSQL()
    {
        $strSQL = "";
        $strSQL .= " SELECT SUBSTR(JISSHI_YM, 0, 4) || '/' || SUBSTR(JISSHI_YM, 5, 6) AS JISSHI_YM" . "\r\n";
        $strSQL .= "      ,TO_CHAR(HYOUKA_KIKAN_START, 'YYYY/MM/DD') AS HYOUKA_KIKAN_START " . "\r\n";
        $strSQL .= "      ,TO_CHAR(HYOUKA_KIKAN_END, 'YYYY/MM/DD') AS HYOUKA_KIKAN_END" . "\r\n";
        $strSQL .= " FROM JKHYOUKAJISSHINENGETU  " . "\r\n";
        $strSQL .= " ORDER BY JISSHI_YM DESC " . "\r\n";

        return parent::select($strSQL);
    }

    //評価履歴データ削除SQL
    public function fncDelHyoukaJisshiYMDataSQL($dtpJisshiYM)
    {
        $strSQL = "";
        $strSQL .= " DELETE FROM JKHYOUKAJISSHINENGETU" . " \r\n";
        $strSQL .= " WHERE JISSHI_YM = '@JISSHI_YM'" . " \r\n";

        $strSQL = str_replace("@JISSHI_YM", $dtpJisshiYM, $strSQL);

        return parent::delete($strSQL);
    }

    //評価実施年月データ期間重複データ取得SQL
    public function fncHyoukaKikanRepChkSQL($postdata)
    {
        $strSQL = "";
        $strSQL .= " SELECT JISSHI_YM " . " \r\n";
        $strSQL .= " FROM JKHYOUKAJISSHINENGETU  " . " \r\n";
        $strSQL .= " WHERE JISSHI_YM <> '@JISSHI_YM'" . " \r\n";
        $strSQL .= "  AND (to_date('@HYOUKA_KIKAN_START','YYYY/MM/DD') BETWEEN HYOUKA_KIKAN_START AND HYOUKA_KIKAN_END OR" . " \r\n";
        $strSQL .= "      to_date('@HYOUKA_KIKAN_END' ,'YYYY/MM/DD')  BETWEEN HYOUKA_KIKAN_START AND HYOUKA_KIKAN_END OR" . " \r\n";
        $strSQL .= "       (to_date('@HYOUKA_KIKAN_START' ,'YYYY/MM/DD') <= HYOUKA_KIKAN_START AND to_date('@HYOUKA_KIKAN_END' ,'YYYY/MM/DD') >= HYOUKA_KIKAN_END)" . " \r\n";
        $strSQL .= "    )" . " \r\n";

        $strSQL = str_replace("@JISSHI_YM", $postdata['dtpJisshiYM'], $strSQL);
        $strSQL = str_replace("@HYOUKA_KIKAN_START", $postdata['dtpTaisyouKS'], $strSQL);
        $strSQL = str_replace("@HYOUKA_KIKAN_END", $postdata['dtpTaisyouKE'], $strSQL);

        return parent::select($strSQL);
    }

    //評価実施年月データ更新SQL
    public function fncUpdHyoukaJisshiYMDataSQL($postdata)
    {
        $strSQL = "";
        $strSQL .= " UPDATE  JKHYOUKAJISSHINENGETU" . "\r\n";
        $strSQL .= " SET HYOUKA_KIKAN_START = '@HYOUKA_KIKAN_START'" . "\r\n";
        $strSQL .= "  ,HYOUKA_KIKAN_END = '@HYOUKA_KIKAN_END'" . " \r\n";
        $strSQL .= "  ,UPD_DATE = SYSDATE" . " \r\n";
        $strSQL .= "  ,UPD_SYA_CD = '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= " ,UPD_PRG_ID = '@UPD_PRG_ID'" . " \r\n";
        $strSQL .= " ,UPD_CLT_NM = '@UPD_CLT_NM' " . "\r\n";
        $strSQL .= " WHERE  JISSHI_YM = '@JISSHI_YM'" . " \r\n";

        $strSQL = str_replace("@HYOUKA_KIKAN_START", $postdata['dtpTaisyouKS'], $strSQL);
        $strSQL = str_replace("@HYOUKA_KIKAN_END", $postdata['dtpTaisyouKE'], $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", "HyokaKikanEnt", $strSQL);
        $strSQL = str_replace("@JISSHI_YM", $postdata['dtpJisshiYM'], $strSQL);

        return parent::update($strSQL);
    }

    //評価実施年月データ登録SQL
    public function fncInsHyoukaJisshiYMDataSQL($postdata)
    {
        $strSQL = "";
        $strSQL .= " INSERT INTO JKHYOUKAJISSHINENGETU" . "\r\n";
        $strSQL .= "  ( JISSHI_YM " . "\r\n";
        $strSQL .= " ,HYOUKA_KIKAN_START" . "\r\n";
        $strSQL .= " ,HYOUKA_KIKAN_END" . "\r\n";
        $strSQL .= "   ,CREATE_DATE" . "\r\n";
        $strSQL .= "  ,CRE_SYA_CD" . "\r\n";
        $strSQL .= "   ,CRE_PRG_ID" . "\r\n";
        $strSQL .= "   ,UPD_DATE" . "\r\n";
        $strSQL .= "      ,UPD_SYA_CD" . "\r\n";
        $strSQL .= "      ,UPD_PRG_ID" . "\r\n";
        $strSQL .= "   ,UPD_CLT_NM" . "\r\n";
        $strSQL .= " )" . "\r\n";
        $strSQL .= " VALUES" . "\r\n";
        $strSQL .= " ( '@JISSHI_YM' " . "\r\n";
        $strSQL .= " ,'@HYOUKA_KIKAN_START' " . "\r\n";

        $strSQL .= " ,'@HYOUKA_KIKAN_END'" . "\r\n";
        $strSQL .= " ,SYSDATE " . " \r\n";
        $strSQL .= " ,'@SYA_CD'" . "\r\n";
        $strSQL .= ",'@PRG_ID' " . " \r\n";
        $strSQL .= ",SYSDATE" . "\r\n";
        $strSQL .= ",'@SYA_CD'" . " \r\n";
        $strSQL .= ",'@PRG_ID' " . " \r\n";
        $strSQL .= ",'@CLT_NM'" . "\r\n";
        $strSQL .= " )" . " \r\n";

        $strSQL = str_replace("@JISSHI_YM", $postdata['dtpJisshiYM'], $strSQL);
        $strSQL = str_replace("@HYOUKA_KIKAN_START", $postdata['dtpTaisyouKS'], $strSQL);
        $strSQL = str_replace("@HYOUKA_KIKAN_END", $postdata['dtpTaisyouKE'], $strSQL);
        $strSQL = str_replace("@SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@PRG_ID", "HyokaKikanEnt", $strSQL);
        $strSQL = str_replace("@CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return parent::insert($strSQL);
    }

    //評価履歴データ取得SQL
    public function fncHyoukaRirekiDataSQL($postdata)
    {
        $strSQL = "";
        $strSQL .= " SELECT JISSHI_YM " . "\r\n";
        $strSQL .= " FROM   JKHYOUKAJISSHINENGETU  " . "\r\n";
        $strSQL .= " WHERE  JISSHI_YM = '@JISSHI_YM' " . "\r\n";

        $strSQL = str_replace("@JISSHI_YM", $postdata['dtpJisshiYM'], $strSQL);

        return parent::select($strSQL);
    }

    //評価取込履歴データ取得SQL
    public function fncHyoukaTriRirekiDataSQL($dtpJisshiYM)
    {
        $strSQL = "";
        $strSQL .= " SELECT JISSHI_YM " . "\r\n";
        $strSQL .= " FROM   JKHYOUKATRKRIREKI  " . "\r\n";
        $strSQL .= " WHERE  JISSHI_YM = '@JISSHI_YM' " . "\r\n";

        $strSQL = str_replace("@JISSHI_YM", $dtpJisshiYM, $strSQL);

        return parent::select($strSQL);
    }

    public function GetTaisyoKSKE($strJissiYYYY_, $strMaxMin_, $strTaisyoKubun_)
    {
        $strSQL = "";
        $strSQL = " SELECT" . "\r\n";
        $strSQL .= " to_char(" . $strMaxMin_ . "(HYOUKA_KIKAN_" . $strTaisyoKubun_ . "),'YYYY/MM/DD')" . "\r\n";
        $strSQL .= " AS HYOUKA_KIKAN" . "\r\n";
        $strSQL .= " FROM" . "\r\n";
        $strSQL .= " JKHYOUKAJISSHINENGETU" . "\r\n";
        $strSQL .= " WHERE" . "\r\n";
        $strSQL .= " SUBSTR(JISSHI_YM,1,4) = '" . $strJissiYYYY_ . "'" . "\r\n";

        return parent::select($strSQL);
    }

}
