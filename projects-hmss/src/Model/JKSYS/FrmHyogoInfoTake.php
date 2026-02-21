<?php
// 共通クラスの読込み
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmHyogoInfoTake extends ClsComDb
{
    //評価取込履歴データ取得SQL
    function fncSetComboSQL()
    {
        $strSQL = "";

        $strSQL .= " SELECT SUBSTR(JISSHI_YM, 0, 4) || '/' || SUBSTR(JISSHI_YM, 5, 6) AS JISSHI_YM " . "\r\n";

        $strSQL .= " FROM   JKHYOUKAJISSHINENGETU " . "\r\n";

        $strSQL .= " ORDER BY JISSHI_YM DESC " . "\r\n";

        return $strSQL;
    }

    //評価対象期間セット
    function fncSetKikanSQL()
    {
        $strSQL = "";

        $strSQL .= " SELECT TO_CHAR(HYOUKA_KIKAN_START,'YYYY/MM/DD') AS HYOUKA_KIKAN_START, " . "\r\n";

        $strSQL .= "        TO_CHAR(HYOUKA_KIKAN_END,'YYYY/MM/DD') AS HYOUKA_KIKAN_END, " . "\r\n";

        $strSQL .= "        JISSHI_YM " . "\r\n";

        $strSQL .= " FROM   JKHYOUKAJISSHINENGETU " . "\r\n";

        $strSQL .= " ORDER BY JISSHI_YM DESC " . "\r\n";

        return $strSQL;
    }

    //評価取込履歴データ取得SQL
    function fncHyoukaTorikomiRirekiDataSQL()
    {
        $strSQL = "";

        $strSQL .= " SELECT   substr(JISSHI_YM, 0, 4) || '/' || substr(JISSHI_YM, 5, 6) AS JISSHI_YM " . "\r\n";

        $strSQL .= "        , to_char(HYOUKA_KIKAN_START, 'YYYY/MM/DD') AS HYOUKA_KIKAN_START " . "\r\n";

        $strSQL .= "        , to_char(HYOUKA_KIKAN_END, 'YYYY/MM/DD') AS HYOUKA_KIKAN_END " . "\r\n";

        $strSQL .= "        , TRK_KENSU " . "\r\n";

        $strSQL .= "  FROM     JKHYOUKATRKRIREKI  " . "\r\n";

        $strSQL .= " ORDER BY JISSHI_YM DESC" . "\r\n";

        return $strSQL;
    }

    //評価履歴データ取得SQL
    function fncHyoukaRirekiDataSQL($postData)
    {
        $strSQL = "";

        $strSQL .= " SELECT JISSHI_YM " . "\r\n";

        $strSQL .= " FROM   JKHYOUKARIREKI  " . "\r\n";

        $strSQL .= " WHERE  JISSHI_YM = '@JISSHI_YM' " . "\r\n";

        $strSQL = str_replace("@JISSHI_YM", $postData['jisshi_ym'], $strSQL);

        return $strSQL;

    }

    //評価取込履歴データ削除SQL
    function fncDelHyoukaTorikomiRirekiDataSQL($postData)
    {
        $strSQL = "";

        $strSQL .= " DELETE FROM JKHYOUKATRKRIREKI " . "\r\n";

        $strSQL .= " WHERE  JISSHI_YM = '@JISSHI_YM' " . "\r\n";

        $strSQL = str_replace("@JISSHI_YM", $postData['jisshi_ym'], $strSQL);

        return $strSQL;

    }

    //評価履歴データ削除SQL
    public function fncDelHyoukaRirekiDataSQL($postData)
    {
        $strSQL = "";

        $strSQL .= " DELETE FROM JKHYOUKARIREKI " . "\r\n";

        $strSQL .= " WHERE  JISSHI_YM = '@JISSHI_YM' " . "\r\n";

        $strSQL = str_replace("@JISSHI_YM", $postData['jisshi_ym'], $strSQL);

        return $strSQL;

    }

    //評価取込履歴データ登録SQL
    public function fncInsHyoukaTorikomiRirekiDataSQL($postData)
    {
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDPRG = "HyogoInfoTake";
        $UPDCLT = $this->GS_LOGINUSER['strClientNM'];

        $strSQL = "";

        $strSQL .= " INSERT INTO JKHYOUKATRKRIREKI(" . "\r\n";

        $strSQL .= "        JISSHI_YM " . "\r\n";

        $strSQL .= "      , HYOUKA_KIKAN_START " . "\r\n";

        $strSQL .= "      , HYOUKA_KIKAN_END " . "\r\n";

        $strSQL .= "      , TRK_KENSU " . "\r\n";

        $strSQL .= "      , CREATE_DATE " . "\r\n";

        $strSQL .= "      , CRE_SYA_CD " . "\r\n";

        $strSQL .= "      , CRE_PRG_ID " . "\r\n";

        $strSQL .= "      , UPD_DATE " . "\r\n";

        $strSQL .= "      , UPD_SYA_CD " . "\r\n";

        $strSQL .= "      , UPD_PRG_ID " . "\r\n";

        $strSQL .= "      , UPD_CLT_NM " . "\r\n";

        $strSQL .= " )VALUES( " . "\r\n";

        $strSQL .= "        '@JISSHI_YM' " . "\r\n";

        $strSQL .= "      , '@HYOUKA_KIKAN_START' " . "\r\n";

        $strSQL .= "      , '@HYOUKA_KIKAN_END' " . "\r\n";

        $strSQL .= "      , '@TRK_KENSU' " . "\r\n";

        $strSQL .= "      , SYSDATE " . "\r\n";

        $strSQL .= "      , '@SYA_CD' " . "\r\n";

        $strSQL .= "      , '@PRG_ID' " . "\r\n";

        $strSQL .= "      , SYSDATE " . "\r\n";

        $strSQL .= "      , '@SYA_CD' " . "\r\n";

        $strSQL .= "      , '@PRG_ID' " . "\r\n";

        $strSQL .= "      , '@CLT_NM' " . "\r\n";

        $strSQL .= " ) " . "\r\n";

        $strSQL = str_replace("@JISSHI_YM", $postData['jisshi_ym'], $strSQL);
        $strSQL = str_replace("@HYOUKA_KIKAN_START", $postData['TaisyouKikanFrom'], $strSQL);
        $strSQL = str_replace("@HYOUKA_KIKAN_END", $postData['TaisyouKikanTo'], $strSQL);
        $strSQL = str_replace("@TRK_KENSU", $postData['Kensu'], $strSQL);
        $strSQL = str_replace("@SYA_CD", $UPDUSER, $strSQL);
        $strSQL = str_replace("@PRG_ID", $UPDPRG, $strSQL);
        $strSQL = str_replace("@CLT_NM", $UPDCLT, $strSQL);

        return $strSQL;

    }

    //評価履歴データ登録SQL
    public function fncInsHyoukaRirekiDataSQL($postData)
    {
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDPRG = "HyogoInfoTake";
        $UPDCLT = $this->GS_LOGINUSER['strClientNM'];

        $strSQL = "";

        $strSQL .= " INSERT INTO JKHYOUKARIREKI(" . "\r\n";

        $strSQL .= "        JISSHI_YM " . "\r\n";

        $strSQL .= "      , SYAIN_NO " . "\r\n";

        $strSQL .= "      , LAST_HYOUKACHI " . "\r\n";

        $strSQL .= "      , LAST_HANTEI " . "\r\n";

        $strSQL .= "      , LAST_HYOUKA " . "\r\n";

        $strSQL .= "      , CRE_SYA_CD " . "\r\n";

        $strSQL .= "      , CRE_CLT_NM " . "\r\n";

        $strSQL .= "      , CREATE_DATE " . "\r\n";

        $strSQL .= "      , CRE_PRG_ID " . "\r\n";

        $strSQL .= " )VALUES( " . "\r\n";

        $strSQL .= "        '@JISSHI_YM' " . "\r\n";

        $strSQL .= "      , '@SYAIN_NO' " . "\r\n";

        $strSQL .= "      , '@LAST_HYOUKACHI' " . "\r\n";

        $strSQL .= "      , '@LAST_HANTEI' " . "\r\n";

        $strSQL .= "      , '@LAST_HYOUKA' " . "\r\n";

        $strSQL .= "      , '@CRE_SYA_CD' " . "\r\n";

        $strSQL .= "      , '@CRE_CLT_NM' " . "\r\n";

        $strSQL .= "      , SYSDATE " . "\r\n";

        $strSQL .= "      , '@CRE_PRG_ID' " . "\r\n";

        $strSQL .= " ) " . "\r\n";

        $strSQL = str_replace("@JISSHI_YM", $postData['jisshi_ym'], $strSQL);
        $strSQL = str_replace("@CRE_SYA_CD", $UPDUSER, $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $UPDCLT, $strSQL);
        $strSQL = str_replace("@CRE_PRG_ID", $UPDPRG, $strSQL);

        return $strSQL;

    }

    public function fncSetCombo()
    {
        $strSql = $this->fncSetComboSQL();
        return parent::select($strSql);
    }

    public function fncSetKikan()
    {
        $strSql = $this->fncSetKikanSQL();
        return parent::select($strSql);
    }

    public function fncHyoukaTorikomiRirekiData()
    {
        $strSql = $this->fncHyoukaTorikomiRirekiDataSQL();
        return parent::select($strSql);
    }

    public function fncHyoukaRirekiData($postData)
    {
        $strSql = $this->fncHyoukaRirekiDataSQL($postData);
        return parent::select($strSql);
    }

    public function fncDelHyoukaTorikomiRirekiData($postData)
    {
        $strSql = $this->fncDelHyoukaTorikomiRirekiDataSQL($postData);
        return parent::delete($strSql);
    }

    public function fncDelHyoukaRirekiData($postData)
    {
        $strSql = $this->fncDelHyoukaRirekiDataSQL($postData);
        return parent::delete($strSql);
    }

    public function fncInsHyoukaTorikomiRirekiData($postData)
    {
        $strSql = $this->fncInsHyoukaTorikomiRirekiDataSQL($postData);
        return parent::insert($strSql);
    }

    public function fncInsHyoukaRirekiData($strSql)
    {
        return parent::insert($strSql);
    }

}
