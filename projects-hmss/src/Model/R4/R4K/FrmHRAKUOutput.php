<?php
// 共通クラスの読込み
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class FrmHRAKUOutput extends ClsComDb
{
    function getGroupDataSQL()
    {

        $strSQL = "SELECT";
        $strSQL .= "     GROUP_NO";
        $strSQL .= ",    GROUP_NM";
        $strSQL .= ",    TO_CHAR(KEIRI_DATE,'YYYY/MM/DD') AS KEIRI_DATE";
        $strSQL .= " FROM ";
        $strSQL .= "      HRAKU_TBL_GROUP";

        $strSQL .= "  ORDER BY ";
        $strSQL .= "       GROUP_NO ";

        return $strSQL;
    }
    function getMibaraiDataSQL($postData, $mode)
    {
        $strSQL = "SELECT";
        // 送付用データ
        $strSQL .= "    HTC.R_KANJYOU_CD AS 貸方_勘定科目コード" . "\r\n";
        $strSQL .= ",    HTC.R_HOJYO_CD AS 貸方_補助科目コード" . "\r\n";
        $strSQL .= ",    HTC.GROUP_NO AS グループNO" . "\r\n";
        $strSQL .= ",    HTC.SHIHARASAKI_CD AS 申請_支払先CD" . "\r\n";
        $strSQL .= ",    HTC.DENPYOU_NO AS 申請_伝票NO" . "\r\n";
        $strSQL .= ",    TO_CHAR(HTC.SHIWAKE_DATE,'YYYYMMDD') AS 仕訳日" . "\r\n";
        $strSQL .= ",    HTC.L_KANJYOU_CD AS 借方_勘定科目コード" . "\r\n";
        $strSQL .= ",    HTC.L_HOJYO_CD AS 借方_補助科目コード" . "\r\n";
        $strSQL .= ",    HTC.L_TAX_KBN_CD AS 借方_税区分コード" . "\r\n";
        $strSQL .= ",    HTC.L_TAX_KBN_NM AS 借方_税区分名" . "\r\n";
        $strSQL .= ",    HTC.TORIHIKI_KBN_DETAIL AS 明細_取引区分" . "\r\n";
        $strSQL .= ",    LPAD(HTC.L_FUTAN_BUMON_CD, 3, '0') AS 借方_負担部門コード" . "\r\n";
        $strSQL .= ",    HTC.R_TAX_KBN_CD AS 貸方_税区分コード" . "\r\n";
        $strSQL .= ",    HTC.R_TAX_KBN_NM AS 貸方_税区分名" . "\r\n";
        $strSQL .= ",    HTC.R_TAX AS 貸方_税率" . "\r\n";
        $strSQL .= ",    LPAD(HTC.R_FUTAN_BUMON_CD, 3, '0') AS 貸方_負担部門コード" . "\r\n";
        $strSQL .= ",    HTC.L_AMOUNT AS 借方_金額" . "\r\n";
        $strSQL .= ",    HTC.L_NOTAX_AMOUNT AS 借方_税抜き額" . "\r\n";
        $strSQL .= ",    HTC.L_TAX_AMOUNT AS 借方_税額" . "\r\n";
        $strSQL .= ",    HTC.FREE1_DETAIL AS 申請_明細_フリー1" . "\r\n";
        $strSQL .= ",    HMC5.CODE AS 相手先区分" . "\r\n";
        $strSQL .= ",    HTC.SHIHARASAKI_NM AS 申請_支払先名" . "\r\n";
        $strSQL .= ",    HMC6.CODE AS 特例区分" . "\r\n";
        $strSQL .= ",    TKY1.KKKOBAN AS 借方口座キー" . "\r\n";
        $strSQL .= ",    TKY2.KKKOBAN AS 貸方口座キー" . "\r\n";
        $strSQL .= ",    TKY1.KOBAN AS 借方必須摘要" . "\r\n";
        $strSQL .= ",    TKY2.KOBAN AS 貸方必須摘要" . "\r\n";
        // 楽楽データ上の列位置を求める
        $strSQL .= ",    HTC.SHIWAKE_NO " . "\r\n";
        $strSQL .= ",    HTC.SHIWAKE_KBN " . "\r\n";
        $strSQL .= ",    TO_CHAR(HTC.SHIWAKE_CRE_DATE,'YYYYMMDD') AS SHIWAKE_CRE_DATE" . "\r\n";
        $strSQL .= ",    HTC.SHIWAKE_CRE_TIME " . "\r\n";
        $strSQL .= ",    HTC.BIKO1 " . "\r\n";
        $strSQL .= ",    HTC.BUSI_REGIST_NUM" . "\r\n";
        $strSQL .= ",    HTC.L_KANJYOU_CD " . "\r\n";
        $strSQL .= ",    HTC.L_KANJYOU_NM " . "\r\n";
        $strSQL .= ",    HTC.L_KANJYOU_KAIKEI " . "\r\n";
        $strSQL .= ",    HTC.L_HOJYO_CD " . "\r\n";
        $strSQL .= ",    HTC.L_HOJYO_NM " . "\r\n";
        $strSQL .= ",    HTC.L_HOJYO_KAIKEI " . "\r\n";
        $strSQL .= ",    HTC.L_HOJYO_KAIKEI_HOJYO1 " . "\r\n";
        $strSQL .= ",    HTC.L_HOJYO_KAIKEI_HOJYO2 " . "\r\n";
        $strSQL .= ",    HTC.L_HOJYO_KAIKEI_HOJYO3 " . "\r\n";
        $strSQL .= ",    HTC.L_HOJYO_KAIKEI_HOJYO4 " . "\r\n";
        $strSQL .= ",    LPAD(HTC.L_FUTAN_BUMON_CD, 3, '0') AS L_FUTAN_BUMON_CD " . "\r\n";
        $strSQL .= ",    HTC.L_FUTAN_BUMON_NM " . "\r\n";
        $strSQL .= ",    HTC.L_FUTAN_BUMON_KAIKEI " . "\r\n";
        $strSQL .= ",    HTC.L_TAX_KBN_CD " . "\r\n";
        $strSQL .= ",    HTC.L_TAX_KBN_NM " . "\r\n";
        $strSQL .= ",    HTC.L_TAX_KBN_KAIKEI " . "\r\n";
        $strSQL .= ",    HTC.L_TAX_CALC_KBN " . "\r\n";
        $strSQL .= ",    HTC.L_TAX " . "\r\n";
        $strSQL .= ",    HTC.L_ODD " . "\r\n";
        $strSQL .= ",    HTC.L_PRO_CD " . "\r\n";
        $strSQL .= ",    HTC.L_PRO_NM " . "\r\n";
        $strSQL .= ",    HTC.L_PRO_KAIKEI " . "\r\n";
        $strSQL .= ",    HTC.L_AMOUNT " . "\r\n";
        $strSQL .= ",    HTC.L_TAX_AMOUNT " . "\r\n";
        $strSQL .= ",    HTC.L_NOTAX_AMOUNT " . "\r\n";
        $strSQL .= ",    HTC.R_KANJYOU_CD " . "\r\n";
        $strSQL .= ",    HTC.R_KANJYOU_NM " . "\r\n";
        $strSQL .= ",    HTC.R_KANJYOU_KAIKEI " . "\r\n";
        $strSQL .= ",    HTC.R_HOJYO_CD " . "\r\n";
        $strSQL .= ",    HTC.R_HOJYO_NM " . "\r\n";
        $strSQL .= ",    HTC.R_HOJYO_KAIKEI " . "\r\n";
        $strSQL .= ",    HTC.R_HOJYO_KAIKEI_HOJYO1 " . "\r\n";
        $strSQL .= ",    HTC.R_HOJYO_KAIKEI_HOJYO2 " . "\r\n";
        $strSQL .= ",    HTC.R_HOJYO_KAIKEI_HOJYO3 " . "\r\n";
        $strSQL .= ",    HTC.R_HOJYO_KAIKEI_HOJYO4 " . "\r\n";
        $strSQL .= ",    HTC.R_FUTAN_BUMON_CD " . "\r\n";
        $strSQL .= ",    HTC.R_FUTAN_BUMON_NM " . "\r\n";
        $strSQL .= ",    HTC.R_FUTAN_BUMON_KAIKEI " . "\r\n";
        $strSQL .= ",    HTC.R_TAX_KBN_CD " . "\r\n";
        $strSQL .= ",    HTC.R_TAX_KBN_NM " . "\r\n";
        $strSQL .= ",    HTC.R_TAX_KBN_KAIKEI " . "\r\n";
        $strSQL .= ",    HTC.R_TAX_CALC_KBN " . "\r\n";
        $strSQL .= ",    HTC.R_TAX " . "\r\n";
        $strSQL .= ",    HTC.R_ODD " . "\r\n";
        $strSQL .= ",    HTC.R_PRO_CD " . "\r\n";
        $strSQL .= ",    HTC.R_PRO_NM " . "\r\n";
        $strSQL .= ",    HTC.R_PRO_KAIKEI " . "\r\n";
        $strSQL .= ",    HTC.R_AMOUNT " . "\r\n";
        $strSQL .= ",    HTC.R_TAX_AMOUNT " . "\r\n";
        $strSQL .= ",    HTC.R_NOTAX_AMOUNT " . "\r\n";
        $strSQL .= ",    HTC.TEKYO " . "\r\n";
        $strSQL .= ",    HTC.FREE1" . "\r\n";
        $strSQL .= ",    HTC.FREE2 " . "\r\n";
        $strSQL .= ",    HTC.FREE3 " . "\r\n";
        $strSQL .= ",    HTC.FREE4 " . "\r\n";
        $strSQL .= ",    HTC.FREE5 " . "\r\n";
        $strSQL .= ",    HTC.FREE6 " . "\r\n";
        $strSQL .= ",    HTC.FREE7 " . "\r\n";
        $strSQL .= ",    HTC.FREE8 " . "\r\n";
        $strSQL .= ",    HTC.DENPYOU_TYPE " . "\r\n";
        $strSQL .= ",    HTC.REQU_MENU_NM " . "\r\n";
        $strSQL .= ",    HTC.DENPYOU_NO " . "\r\n";
        $strSQL .= ",    HTC.DENPYOU_DETAIL_NO " . "\r\n";
        $strSQL .= ",    HTC.BUMON_CD " . "\r\n";
        $strSQL .= ",    HTC.BUMON_MN " . "\r\n";
        $strSQL .= ",    HTC.REQU_USER_CD " . "\r\n";
        $strSQL .= ",    HTC.REQU_USER_NM " . "\r\n";
        $strSQL .= ",    HTC.REQU_DATE " . "\r\n";
        $strSQL .= ",    HTC.TOTAL " . "\r\n";
        $strSQL .= ",    HTC.BIKO2 " . "\r\n";
        $strSQL .= ",    HTC.FREE1_HEADER " . "\r\n";
        $strSQL .= ",    HTC.FREE2_HEADER " . "\r\n";
        $strSQL .= ",    HTC.FREE1_DETAIL " . "\r\n";
        $strSQL .= ",    HTC.FREE2_DETAIL " . "\r\n";
        $strSQL .= ",    HTC.CALC_MAE_AMOUNT " . "\r\n";
        $strSQL .= ",    HTC.UNIT " . "\r\n";
        $strSQL .= ",    HTC.RATE " . "\r\n";
        $strSQL .= ",    HTC.SHIHARA_HOUHOU " . "\r\n";
        $strSQL .= ",    HTC.SHIHARASAKI_CD " . "\r\n";
        $strSQL .= ",    HTC.SHIHARASAKI_NM " . "\r\n";
        $strSQL .= ",    HTC.SYUTTYO_AREA_HEADER " . "\r\n";
        $strSQL .= ",    HTC.SYUTTYO_KBN_HEADER " . "\r\n";
        $strSQL .= ",    HTC.UNPAID_EXPENSES_HEADER " . "\r\n";
        $strSQL .= ",    HTC.PAYER_HEADER " . "\r\n";
        $strSQL .= ",    HTC.AITE_KBN_HEADER " . "\r\n";
        $strSQL .= ",    LPAD(HTC.KOSYA_DETAIL, 5, '0') AS KOSYA_DETAIL " . "\r\n";
        $strSQL .= ",    HTC.TYUMONSYO_NO_DETAIL " . "\r\n";
        $strSQL .= ",    HTC.SYAOKAISYA_NM_DETAIL " . "\r\n";
        $strSQL .= ",    HTC.KOUZA_HSSEI_NO_DETAIL " . "\r\n";
        $strSQL .= ",    HTC.KOUZA_NM_DETAIL " . "\r\n";
        $strSQL .= ",    HTC.CAR_TYPE_DETAIL " . "\r\n";
        $strSQL .= ",    HTC.CAR_NO_DETAIL " . "\r\n";
        $strSQL .= ",    HTC.TYUKOSYA_NO_DETAIL " . "\r\n";
        $strSQL .= ",    HTC.CUSTOMER_NO_DETAIL " . "\r\n";
        $strSQL .= ",    HTC.LOAN_CRE_CD_DETAIL " . "\r\n";
        $strSQL .= ",    HTC.ZONBO_DETAIL " . "\r\n";
        $strSQL .= ",    HTC.HOKENGAISYA_DETAIL " . "\r\n";
        $strSQL .= ",    HTC.TORIHIKI_KBN_DETAIL " . "\r\n";
        $strSQL .= " FROM " . "\r\n";
        $strSQL .= "      HRAKU_TBL_CONVERT HTC" . "\r\n";
        $strSQL .= " LEFT JOIN TEKIYO_VW TKY1     " . "\r\n";
        $strSQL .= "      ON TKY1.KAMOK_CD = HTC.L_KANJYOU_CD" . "\r\n";
        $strSQL .= "      AND TKY1.KOUMK_CD = CASE WHEN HTC.L_HOJYO_CD IS NULL THEN ' ' ELSE HTC.L_HOJYO_CD END" . "\r\n";
        $strSQL .= " LEFT JOIN TEKIYO_VW TKY2     " . "\r\n";
        $strSQL .= "      ON TKY2.KAMOK_CD = HTC.R_KANJYOU_CD" . "\r\n";
        $strSQL .= "      AND TKY2.KOUMK_CD = CASE WHEN HTC.R_HOJYO_CD IS NULL THEN ' ' ELSE HTC.R_HOJYO_CD END" . "\r\n";
        $strSQL .= " LEFT JOIN HRAKU_MST_CONVERT HMC5     " . "\r\n";
        $strSQL .= "      ON HMC5.VALUE1 = CASE WHEN HTC.AITE_KBN_HEADER IS NULL OR HTC.AITE_KBN_HEADER = '' THEN HTC.AITE_KBN ELSE HTC.AITE_KBN_HEADER END" . "\r\n";
        $strSQL .= "      AND HMC5.GRP = '変換パターン５'" . "\r\n";
        $strSQL .= " LEFT JOIN HRAKU_MST_CONVERT HMC6     " . "\r\n";
        $strSQL .= "      ON HMC6.VALUE1 = HTC.TOKUREI_KBN" . "\r\n";
        $strSQL .= "      AND HMC6.GRP = '変換パターン６'" . "\r\n";
        $strSQL .= " WHERE" . "\r\n";
        $strSQL .= "      HTC.SEL_FLG = 1" . "\r\n";
        $strSQL .= "      AND HTC.GROUP_NM = '@GROUP_NM'" . "\r\n";
        if ($mode == 0) {
            $strSQL .= "    AND HTC.R_KANJYOU_CD = '21152'" . "\r\n";
            $strSQL .= "    AND HTC.R_HOJYO_CD = '9'" . "\r\n";
            $strSQL .= "    AND HTC.SHIHARASAKI_CD <> '99999'" . "\r\n";
        }
        $strSQL = str_replace('@GROUP_NM', $postData['grNm'], $strSQL);

        return $strSQL;
    }

    function getPatternSQL($paramet)
    {
        $strSQL = "SELECT";
        $strSQL .= "    *" . "\r\n";
        $strSQL .= " FROM " . "\r\n";
        $strSQL .= "     HRAKU_MST_CONVERT" . "\r\n";
        $strSQL .= " WHERE" . "\r\n";
        $strSQL .= "      HRAKU_MST_CONVERT.GRP = '@GRP'" . "\r\n";
        $strSQL = str_replace('@GRP', $paramet, $strSQL);

        return $strSQL;
    }

    function getMibaraiDataCntSQL($postData)
    {
        $strSQL = "SELECT";
        $strSQL .= "    SEL_FLG" . "\r\n";
        $strSQL .= " FROM " . "\r\n";
        $strSQL .= "     HRAKU_TBL_CONVERT HTC" . "\r\n";
        $strSQL .= " WHERE" . "\r\n";
        $strSQL .= "      HTC.SEL_FLG = 1" . "\r\n";
        $strSQL .= "      AND HTC.GROUP_NM = '@GROUP_NM'" . "\r\n";
        $strSQL = str_replace('@GROUP_NM', $postData['grNm'], $strSQL);

        return $strSQL;
    }

    //部署データを取得
    public function btnView_Click()
    {
        $strSql = $this->getGroupDataSQL();

        return parent::select($strSql);
    }

    function getPattern($paramet)
    {
        $strSql = $this->getPatternSQL($paramet);

        return parent::select($strSql);
    }

    public function getMibaraiData($postData, $mode)
    {
        $strSql = $this->getMibaraiDataSQL($postData, $mode);

        return parent::select($strSql);
    }
    function getMibaraiDataCnt($postData)
    {
        $strSql = $this->getMibaraiDataCntSQL($postData);

        return parent::select($strSql);
    }

}