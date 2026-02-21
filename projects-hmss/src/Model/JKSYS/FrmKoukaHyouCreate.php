<?php
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;
use App\Model\JKSYS\Component\ClsComFncJKSYS;
//*************************************
// * 処理名	：FrmKoukaHyouCreate
// * 関数名	：FrmKoukaHyouCreate
// * 処理説明	：共通クラスの読込み
//*************************************
class FrmKoukaHyouCreate extends ClsComDb
{
    public $ClsComFncJKSYS;
    //人事コントロールマスタ取得SQL
    public function SelJKCONTROLMSE_SQL($strID)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "SELECT KAKI_HYOUKA_END_MT" . "\r\n";
        $strSQL .= "      ,TOUKI_HYOUKA_END_MT" . "\r\n";
        $strSQL .= "FROM JKCONTROLMST" . "\r\n";
        $strSQL .= "WHERE ID = @ID";

        //-- ﾊﾟﾗﾒｰﾀ --
        $strSQL = str_replace("@ID", $this->ClsComFncJKSYS->FncSqlNv($strID), $strSQL);

        return parent::select($strSQL);
    }

    //社員別考課表タイプデータ取得SQL
    public function SelJKKOUKA_SYAIN_TYPE_SQL($strYm = "")
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        if ($strYm == "") {
            //--- SELECT文 ---
            $strSQL .= "SELECT MAX(HYOUKA_KIKAN_END) HYOUKA_KIKAN_END" . "\r\n";
            $strSQL .= "FROM JKKOUKA_SYAIN_TYPE";
        } else {
            //--- SELECT文 ---
            $strSQL .= "SELECT HYOUKA_KIKAN_END" . "\r\n";
            $strSQL .= "FROM JKKOUKA_SYAIN_TYPE" . "\r\n";
            $strSQL .= "WHERE HYOUKA_KIKAN_END = @KIKANEND";

            //-- ﾊﾟﾗﾒｰﾀ --
            $strSQL = str_replace("@KIKANEND", $this->ClsComFncJKSYS->FncSqlNv($strYm), $strSQL);
        }

        return parent::select($strSQL);
    }

    //社員名取得SQL
    public function GetSyainNm()
    {
        //--- SELECT文 ---
        $strSQL = "";
        $strSQL .= "   SELECT SYAIN.SYAIN_NO,SYAIN.SYAIN_NM" . "\r\n";
        $strSQL .= "     FROM JKSYAIN SYAIN" . "\r\n";
        $strSQL .= "         ,M_LOGIN LOGIN" . "\r\n";
        $strSQL .= "    WHERE LOGIN.USER_ID = SYAIN.SYAIN_NO " . "\r\n";
        $strSQL .= "      AND LOGIN.SYS_KB = '6'";

        return parent::select($strSQL);
    }

    //データ取得SQL
    public function SelJKKOUKA_SQL($strYm, $SelectedValue, $rdoBoth)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL1 = "";
        //社員番号
        $strSQL1 .= " SELECT STYPE.SYAIN_NO" . "\r\n";
        //社員名
        $strSQL1 .= "       ,SYAIN.SYAIN_NM " . "\r\n";
        //評価対象期間開始
        $strSQL1 .= "       ,DECODE(JISSEKI.HYOUKA_KIKAN_START,NULL,STYPE.HYOUKA_KIKAN_START,JISSEKI.HYOUKA_KIKAN_START) HYOUKA_KIKAN_START" . "\r\n";
        //評価対象期間終了
        $strSQL1 .= "       ,STYPE.HYOUKA_KIKAN_END " . "\r\n";
        //部門コード
        $strSQL1 .= "       ,STYPE.BUSYO_CD " . "\r\n";
        //部門名
        $strSQL1 .= "       ,BUMON.BUSYO_NM " . "\r\n";
        //送付先コード
        $strSQL1 .= "       ,IDO.BUSYO_CD SOUFUBUSYO_CD" . "\r\n";
        //送付先部門名
        $strSQL1 .= "       ,SOFU.BUSYO_NM SOUFUBUSYO_NM" . "\r\n";
        //資格等級
        $strSQL1 .= "       ,CODE.MEISYOU SHIKAKU" . "\r\n";
        //考課表タイプコード
        $strSQL1 .= "       ,STYPE.KOUKATYPE_CD " . "\r\n";
        //雇用区分コード
        $strSQL1 .= "       ,STYPE.KOYOU_KB_CD " . "\r\n";
        //Excel名
        $strSQL1 .= "       ,TYPEM.EXCEL_NM " . "\r\n";
        //ｼｰﾄ名
        $strSQL1 .= "       ,TYPEM.SHEET_NM " . "\r\n";
        $strSQL1 .= "       ,JISSEKI.TABLE_KBN " . "\r\n";
        $strSQL1 .= "       ,JISSEKI.ATAI " . "\r\n";
        $strSQL1 .= "       ,JISSEKI.SYUTURYOKU_KOMOKU_ID " . "\r\n";
        //'(FROM)
        $strSQL2 = "";
        $strSQL2 .= "     ,JKKOUKA_TYPE_MST TYPEM" . "\r\n";
        $strSQL2 .= "     ,JKBUMON SOFU" . "\r\n";
        $strSQL2 .= "     ,JKIDOURIREKI IDO" . "\r\n";
        $strSQL2 .= "     ,(SELECT SYAIN_NO" . "\r\n";
        $strSQL2 .= "             ,MAX(ANNOUNCE_DT) ANNOUNCE_DT" . "\r\n";
        $strSQL2 .= "       FROM  JKIDOURIREKI" . "\r\n";
        $strSQL2 .= "       GROUP BY SYAIN_NO) IDOMAX" . "\r\n";
        $strSQL2 .= "     ,JKSYAIN SYAIN" . "\r\n";
        $strSQL2 .= "     ,(SELECT CODE " . "\r\n";
        $strSQL2 .= "             ,MEISYOU " . "\r\n";
        $strSQL2 .= "       FROM  JKCODEMST" . "\r\n";
        $strSQL2 .= "       WHERE ID = 'SHIKAKU') CODE" . "\r\n";
        $strSQL2 .= "     ,JKBUMON BUMON" . "\r\n";
        $strSQL2 .= "     ,JKKOUKA_SYAIN_TYPE STYPE" . "\r\n";

        $strSQL2 .= " WHERE STYPE.SYAIN_NO = JISSEKI.SYAIN_NO(+)" . "\r\n";
        $strSQL2 .= "   AND STYPE.HYOUKA_KIKAN_END = JISSEKI.HYOUKA_KIKAN_END(+)" . "\r\n";
        $strSQL2 .= "   AND STYPE.KOYOU_KB_CD = TYPEM.KOYOU_KB_CD" . "\r\n";
        $strSQL2 .= "   AND STYPE.KOUKATYPE_CD = TYPEM.KOUKATYPE_CD" . "\r\n";
        $strSQL2 .= "   AND IDO.BUSYO_CD = SOFU.BUSYO_CD" . "\r\n";
        $strSQL2 .= "   AND IDOMAX.ANNOUNCE_DT = IDO.ANNOUNCE_DT" . "\r\n";
        $strSQL2 .= "   AND IDOMAX.SYAIN_NO = IDO.SYAIN_NO" . "\r\n";
        $strSQL2 .= "   AND SYAIN.SYAIN_NO = IDOMAX.SYAIN_NO" . "\r\n";
        $strSQL2 .= "   AND STYPE.SYAIN_NO = SYAIN.SYAIN_NO" . "\r\n";
        $strSQL2 .= "   AND STYPE.SHIKAKU_CODE = CODE.CODE" . "\r\n";
        $strSQL2 .= "   AND STYPE.BUSYO_CD = BUMON.BUSYO_CD" . "\r\n";
        if ($SelectedValue != "") {
            $strSQL2 .= "   AND STYPE.KOUKATYPE_CD = @KOUKATYPE" . "\r\n";
        }
        $strSQL2 .= "   AND STYPE.HYOUKA_KIKAN_END = @ENDYM" . "\r\n";
        //--- SELECT文 ---
        //実績集計
        $strSQL = "" . "\r\n";
        $strSQL .= $strSQL1 . "\r\n";
        //評価対象期間開始
        $strSQL .= " FROM (SELECT SYUKEI.HYOUKA_KIKAN_START" . "\r\n";
        //評価対象期間終了
        $strSQL .= "             ,SYUKEI.HYOUKA_KIKAN_END " . "\r\n";
        //社員番号
        $strSQL .= "             ,SYUKEI.SYAIN_NO " . "\r\n";
        //テーブル区分
        $strSQL .= "             ,KOMOKU.TABLE_KBN " . "\r\n";
        //値
        $strSQL .= "             ,SYUKEI.ATAI " . "\r\n";
        //出力項目ID
        $strSQL .= "             ,KOMOKU.SYUTURYOKU_KOMOKU_ID " . "\r\n";
        $strSQL .= "       FROM  JKKOUKA_JISSEKI_SYUKEI SYUKEI" . "\r\n";
        $strSQL .= "            ,JKKOUKA_SYUKEIKOMOKU_MST KOMOKU" . "\r\n";
        $strSQL .= "       WHERE SYUKEI.HYOUKA_KIKAN_END = @ENDYM" . "\r\n";
        if ($rdoBoth != "1") {
            $strSQL .= "         AND SYUKEI.HYOUKA_KIKAN_START = @STARTYM" . "\r\n";
        }
        $strSQL .= "         AND KOMOKU.SYUKEI_KOMOKU_KBN = SYUKEI.SYUKEI_KOMOKU_KBN" . "\r\n";
        $strSQL .= "         AND KOMOKU.KOMOKU_KBN = SYUKEI.KOMOKU_KBN" . "\r\n";
        $strSQL .= "         AND KOMOKU.SYUTURYOKU_TAISYO_UMU = '1'" . "\r\n";
        $strSQL .= "         AND KOMOKU.TABLE_KBN = '01' ) JISSEKI" . "\r\n";
        $strSQL .= $strSQL2 . "\r\n";

        $strSQL .= " UNION " . "\r\n";
        //周辺利益集計
        $strSQL .= $strSQL1 . "\r\n";

        //評価対象期間開始
        $strSQL .= " FROM (SELECT SYUKEI.HYOUKA_KIKAN_START" . "\r\n";
        //評価対象期間終了
        $strSQL .= "             ,SYUKEI.HYOUKA_KIKAN_END " . "\r\n";
        //社員番号
        $strSQL .= "             ,SYUKEI.SYAIN_NO " . "\r\n";
        //テーブル区分
        $strSQL .= "             ,KOMOKU.TABLE_KBN " . "\r\n";
        //値
        $strSQL .= "             ,SYUKEI.ATAI " . "\r\n";
        //出力項目ID
        $strSQL .= "             ,KOMOKU.SYUTURYOKU_KOMOKU_ID " . "\r\n";
        $strSQL .= "       FROM  JKKOUKA_SYUHEN_RIEKI SYUKEI" . "\r\n";
        $strSQL .= "            ,JKKOUKA_SYUKEIKOMOKU_MST KOMOKU" . "\r\n";
        $strSQL .= "       WHERE SYUKEI.HYOUKA_KIKAN_END = @ENDYM" . "\r\n";
        if ($rdoBoth != "1") {
            $strSQL .= "         AND SYUKEI.HYOUKA_KIKAN_START = @STARTYM" . "\r\n";
        }
        $strSQL .= "         AND KOMOKU.SYUKEI_KOMOKU_KBN = SYUKEI.SYUKEI_KOMOKU_KBN" . "\r\n";
        $strSQL .= "         AND KOMOKU.KOMOKU_KBN = SYUKEI.KOMOKU_KBN" . "\r\n";
        $strSQL .= "         AND KOMOKU.SYUTURYOKU_TAISYO_UMU = '1'" . "\r\n";
        $strSQL .= "         AND KOMOKU.TABLE_KBN = '02' ) JISSEKI" . "\r\n";
        $strSQL .= $strSQL2 . "\r\n";

        $strSQL .= " ORDER BY  KOUKATYPE_CD" . "\r\n";
        $strSQL .= "          ,KOYOU_KB_CD" . "\r\n";
        $strSQL .= "          ,SYAIN_NO" . "\r\n";
        $strSQL .= "          ,HYOUKA_KIKAN_START" . "\r\n";

        //-- ﾊﾟﾗﾒｰﾀ --
        $strSQL = str_replace("@ENDYM", $this->ClsComFncJKSYS->FncSqlNv($strYm), $strSQL);
        if ($rdoBoth == "2") {
            //評価期間:6ヶ月
            $strSQL = str_replace("@STARTYM", $this->ClsComFncJKSYS->FncSqlNv($this->getPreMonth($strYm, -5)), $strSQL);
        }
        if ($rdoBoth == "3") {
            //評価期間:1年
            $strSQL = str_replace("@STARTYM", $this->ClsComFncJKSYS->FncSqlNv($this->getPreMonth($strYm, -11)), $strSQL);
        }
        $strSQL = str_replace("@KOUKATYPE", $this->ClsComFncJKSYS->FncSqlNv($SelectedValue), $strSQL);

        return parent::select($strSQL);
    }

    //年月-numか月
    public function getPreMonth($dtpYM, $num)
    {
        $dtpYM = $dtpYM . "01";
        $rtnDate = date('Ym', strtotime("$dtpYM $num month"));

        return $rtnDate;
    }

}
