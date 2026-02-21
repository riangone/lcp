<?php
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;
use App\Model\JKSYS\Component\ClsComFncJKSYS;

class FrmKyuyoInfoTake extends ClsComDb
{
    public $ClsComFncJKSYS;
    //人事コントロールマスタの処理年月取得
    public function procGetJinjiCtrlMst_YM()
    {
        $strSQL = "";
        $strSQL = "SELECT" . "\r\n";
        $strSQL .= "    SYORI_YM," . "\r\n";
        $strSQL .= "    KAKI_BONUS_MONTH," . "\r\n";
        $strSQL .= "    KAKI_BONUS_START_MT," . "\r\n";
        $strSQL .= "    KAKI_BONUS_END_MT," . "\r\n";
        $strSQL .= "    TOUKI_BONUS_MONTH," . "\r\n";
        $strSQL .= "    TOUKI_BONUS_START_MT," . "\r\n";
        $strSQL .= "    TOUKI_BONUS_END_MT" . "\r\n";
        $strSQL .= "FROM " . "\r\n";
        $strSQL .= "    JKCONTROLMST " . "\r\n";
        $strSQL .= "WHERE" . "\r\n";
        $strSQL .= "    ID = '01'" . "\r\n";

        return parent::select($strSQL);
    }

    // データの存在チェック
    public function procExistCheckData($dtpYM, $kbn)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "    COUNT(*) AS CNT" . "\r\n";
        $strSQL .= "FROM" . "\r\n";
        $strSQL .= "    JKSHIKYU" . "\r\n";
        $strSQL .= "WHERE" . "\r\n";
        $strSQL .= "    TAISYOU_YM = @REP" . "\r\n";
        $strSQL .= "AND" . "\r\n";
        $strSQL .= "    KS_KB = '@kbn'" . "\r\n";

        $strSQL = str_replace('@REP', $this->ClsComFncJKSYS->FncSqlNv($dtpYM), $strSQL);
        $strSQL = str_replace('@kbn', $kbn, $strSQL);

        return parent::select($strSQL);
    }

    //奉行データ取込ラインマスタの取得
    public function procGetTorikomiLineMst($fileName)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "SELECT   TRK_SAKI_TABLE_NM," . "\r\n";
        $strSQL .= "         TRK_SAKI_KOUMK_ID," . "\r\n";
        $strSQL .= "         PRIMARY_KEY_FLG" . "\r\n";
        $strSQL .= ",        (TRK_MOTO_LINE_NO - 1) TRK_MOTO_LINE_NO" . "\r\n";
        $strSQL .= "FROM     TORIKOMI_LINE_MST" . "\r\n";
        $strSQL .= "WHERE    TRK_FILE_NM = @REP" . "\r\n";
        $strSQL .= "ORDER BY TRK_SAKI_TABLE_NM," . "\r\n";
        $strSQL .= "         TRK_MOTO_LINE_NO" . "\r\n";

        $strSQL = str_replace('@REP', $this->ClsComFncJKSYS->FncSqlNv($fileName), $strSQL);

        return parent::select($strSQL);
    }

    //各マスタデータの削除
    public function procDeleteMstData($colTableNm, $dtpYM, $kbn)
    {
        $result = array(
            'result' => false,
            'data' => ''
        );

        foreach ($colTableNm as $intItemCnt) {
            $strSql = $this->procDeleteMstDataSQL($intItemCnt, $dtpYM, $kbn);
            $result = parent::delete($strSql);
            if ($result["result"] == FALSE) {
                return $result;
            }
        }
        $result['result'] = true;

        return $result;
    }

    //各マスタデータの削除Sql
    public function procDeleteMstDataSQL($intItemCnt, $dtpYM, $kbn)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "DELETE FROM @REP1" . "\r\n";
        $strSQL .= "WHERE TAISYOU_YM = @REP2" . "\r\n";
        $strSQL .= "AND   KS_KB = '@REP3'" . "\r\n";

        $strSQL = str_replace('@REP1', $intItemCnt, $strSQL);
        $strSQL = str_replace('@REP2', $this->ClsComFncJKSYS->FncSqlNv($dtpYM), $strSQL);
        $strSQL = str_replace('@REP3', $kbn, $strSQL);

        return $strSQL;
    }

    // マスタへのデータ登録
    public function procInsertDataToMst($csvData, $colItemSQL, $colValueIdx, $dtpYM, $kbn)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $objRegEx_Dt = '/^(\d{4}\/\d{2}\/\d{2})$/';

        $strSQL = "";
        $strSQL = $colItemSQL;

        $tmpSql = "";
        $tmpSql .= "'" . $dtpYM . "'";
        $tmpSql .= ",'" . $kbn . "'";

        $arrValue = explode(",", $colValueIdx);
        //VALUE句の生成
        for ($i = 0; $i < count($arrValue); $i++) {
            $tmpPos = $arrValue[$i];
            if (substr($csvData[$tmpPos], 0, 1) == '"') {
                $length = strlen($csvData[$tmpPos]) - 2;
                $tmpSql .= "," . $this->ClsComFncJKSYS->FncSqlNv(substr($csvData[$tmpPos], 1, $length)) . "";
            } else {
                //日付型
                if (preg_match($objRegEx_Dt, str_replace(':', '.', $csvData[$tmpPos]))) {
                    $tmpSql .= "," . $this->ClsComFncJKSYS->FncSqlDate(str_replace(':', '.', $csvData[$tmpPos]) . ' 00:00:00');
                }
                //数値,文字列
                else {
                    $tmpSql .= "," . $this->ClsComFncJKSYS->FncSqlNv(str_replace(':', '.', $csvData[$tmpPos]));
                }
            }
        }
        $strSQL .= $tmpSql;
        //UserID
        $strSQL .= "," . $this->ClsComFncJKSYS->FncSqlNv($this->GS_LOGINUSER["strUserID"]);
        //ClientNM
        $strSQL .= "," . $this->ClsComFncJKSYS->FncSqlNv($this->GS_LOGINUSER["strClientNM"]);
        //SYSDATE
        $strSQL .= "," . "SYSDATE";
        //ProgramID
        $strSQL .= "," . $this->ClsComFncJKSYS->FncSqlNv("FrmKyuyoInfoTake");
        $strSQL .= ")";

        return parent::insert($strSQL);
    }

    //その他データの更新
    public function procUpdateSonotaData($dtpYM, $kbn)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "UPDATE JKSONOTA SET" . "\r\n";
        $strSQL .= " SONOTA1 = CASE" . "\r\n";
        $strSQL .= "               WHEN TRK_SONOTA1 IS NOT NULL THEN" . "\r\n";
        $strSQL .= "                   CASE" . "\r\n";
        $strSQL .= "                       WHEN SUBSTR(TRK_SONOTA1, 1, 2) <> @REP1 AND @REP1 < SUBSTR(TRK_SONOTA1, 1, 2)" . "\r\n";
        $strSQL .= "                           THEN TO_DATE(REPLACE(REPLACE(@REP2 || '/' || TRK_SONOTA1,'月','/'),'日',''), 'yyyy/MM/dd')" . "\r\n";
        $strSQL .= "                       ELSE TO_DATE(REPLACE(REPLACE(@REP3 || '/' || TRK_SONOTA1,'月','/'),'日',''), 'yyyy/MM/dd')" . "\r\n";
        $strSQL .= "                   END" . "\r\n";
        $strSQL .= "               ELSE NULL" . "\r\n";
        $strSQL .= "           END" . "\r\n";
        if ($kbn == 1) {
            $strSQL .= ",SONOTA2 = CASE" . "\r\n";
            $strSQL .= "               WHEN TRK_SONOTA2 IS NOT NULL THEN" . "\r\n";
            $strSQL .= "                   CASE" . "\r\n";
            $strSQL .= "                       WHEN SUBSTR(TRK_SONOTA2, 1, 2) <> @REP1 AND @REP1 < SUBSTR(TRK_SONOTA2, 1, 2)" . "\r\n";
            $strSQL .= "                           THEN TO_DATE(REPLACE(REPLACE(@REP2 || '/' || TRK_SONOTA2,'月','/'),'日',''), 'yyyy/MM/dd')" . "\r\n";
            $strSQL .= "                       ELSE TO_DATE(REPLACE(REPLACE(@REP3 || '/' || TRK_SONOTA2,'月','/'),'日',''), 'yyyy/MM/dd')" . "\r\n";
            $strSQL .= "                   END" . "\r\n";
            $strSQL .= "               ELSE NULL" . "\r\n";
            $strSQL .= "           END" . "\r\n";
            $strSQL .= ",SONOTA3 = CASE" . "\r\n";
            $strSQL .= "               WHEN TRK_SONOTA3 IS NOT NULL THEN" . "\r\n";
            $strSQL .= "                   CASE" . "\r\n";
            $strSQL .= "                       WHEN SUBSTR(TRK_SONOTA3, 1, 2) <> @REP1 AND @REP1 < SUBSTR(TRK_SONOTA3, 1, 2)" . "\r\n";
            $strSQL .= "                           THEN TO_DATE(REPLACE(REPLACE(@REP2 || '/' || TRK_SONOTA3,'月','/'),'日',''), 'yyyy/MM/dd')" . "\r\n";
            $strSQL .= "                       ELSE TO_DATE(REPLACE(REPLACE(@REP3 || '/' || TRK_SONOTA3,'月','/'),'日',''), 'yyyy/MM/dd')" . "\r\n";
            $strSQL .= "                   END" . "\r\n";
            $strSQL .= "               ELSE NULL" . "\r\n";
            $strSQL .= "           END" . "\r\n";
        }
        $strSQL .= "WHERE TAISYOU_YM = @REPA" . "\r\n";
        $strSQL .= "AND   KS_KB = '@REPB'" . "\r\n";

        $strSQL = str_replace('@REP1', $this->ClsComFncJKSYS->FncSqlNv(substr($dtpYM, 4, 2)), $strSQL);
        $strSQL = str_replace('@REP2', substr($dtpYM, 0, 4) - 1, $strSQL);
        $strSQL = str_replace('@REP3', substr($dtpYM, 0, 4), $strSQL);
        $strSQL = str_replace('@REPA', $dtpYM, $strSQL);
        $strSQL = str_replace('@REPB', $kbn, $strSQL);

        return parent::update($strSQL);
    }

}
