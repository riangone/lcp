<?php
// 共通クラスの読込み
namespace App\Model\R4\R4G;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmListSelect extends ClsComDb
{
    // 20131004 kamei add Start
    // 解放が必要な変数をメンバーに設定
    protected $conn_orl = "";
    protected $Sel_Array = "";

    function fncFrmListSelectSql($conditions, $NENGETU)
    {
        //20131204 LuChao 既存バグ修正 Start
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        //20131204 LuChao 既存バグ修正 End

        $strSQL = "SELECT    CMN.CMN_NO" . "\r\n";

        $strSQL .= ",  (CASE WHEN KASO.CMN_NO IS NULL THEN ('";
        $strSQL .= $NENGETU;
        $strSQL .= "' || '-' || LPAD(TO_CHAR(NVL((SELECT BANGO FROM HSAIBAN WHERE SAIBAN_CD = '1' AND NENGETU = '";
        $strSQL .= $NENGETU;
        $strSQL .= "'),0) + 1),4,'0')) ELSE KASO.KASOUNO END) KASOU_NO";

        //20131206 FuXiaolin 既存バグ修正 End
        $strSQL .= "   FROM  M41E10 CMN" . "\r\n";
        $strSQL .= "   LEFT JOIN (SELECT DISTINCT CMN_NO FROM M41E12) MEI" . "\r\n";
        $strSQL .= "   ON        CMN.CMN_NO = MEI.CMN_NO" . "\r\n";
        $strSQL .= "   LEFT JOIN M41C01 KYK_OKY" . "\r\n";
        $strSQL .= "   ON        KYK_OKY.DLRCSRNO = CMN.KYK_CUS_NO" . "\r\n";
        $strSQL .= "  LEFT JOIN M41C01 SIY_OKY" . "\r\n";
        $strSQL .= "   ON        CMN.SIY_CUS_NO = SIY_OKY.DLRCSRNO" . "\r\n";
        $strSQL .= "   LEFT JOIN M27M01 BUS" . "\r\n";
        $strSQL .= "   ON        BUS.KYOTN_CD = CMN.KYOTN_CD" . "\r\n";
        $strSQL .= "   AND       BUS.HANSH_CD = '3634'" . "\r\n";
        $strSQL .= "   AND       BUS.ES_KB = 'E'" . "\r\n";
        $strSQL .= "   LEFT JOIN M29MA4 SYA" . "\r\n";
        $strSQL .= "   ON        SYA.SYAIN_NO = CMN.HNB_TAN_EMP_NO" . "\r\n";

        //20131204 LuChao 既存バグ修正 Start
        // $strSQL .= "   LEFT JOIN (SELECT DISTINCT CMN_NO, KASOUNO FROM WK_HKASOUMEISAI) KASO"."\r\n";
        $strSQL .= "   LEFT JOIN (SELECT DISTINCT CMN_NO, KASOUNO  FROM WK_HKASOUMEISAI_APPEND WHERE UPD_SYA_CD = '@UPDUSER') KASO" . "\r\n";
        //20131204 LuChao 既存バグ修正 End

        $strSQL .= "   ON        KASO.CMN_NO = CMN.CMN_NO" . "\r\n";
        //20131206 Fuxiaolin 既存バグ修正 Start
        $strSQL .= "  LEFT JOIN(    SELECT CMN_NO,TO_CHAR(MAX(UPD_DATE),'YYYY/MM/DD')  UPD_DATE    FROM HKASOUMEISAI    GROUP BY CMN_NO) KASO_OUT ON KASO_OUT.CMN_NO = CMN.CMN_NO" . "\r\n";
        //20131206 Fuxiaolin 既存バグ修正 End
        $strSQL .= "   LEFT JOIN M27M01 HAN" . "\r\n";
        $strSQL .= "   ON        HAN.KYOTN_CD = CMN.KYOTN_CD" . "\r\n";
        $strSQL .= "   AND       HAN.HANSH_CD = '3634'" . "\r\n";
        $strSQL .= "   AND       HAN.ES_KB = 'E'" . "\r\n";
        $strSQL .= "   LEFT JOIN M27AM1 BASE" . "\r\n";
        $strSQL .= "   ON        BASE.BASEH_CD = CMN.MOD_CD" . "\r\n";
        $strSQL .= "   LEFT JOIN M27A01 JYUCHU" . "\r\n";
        $strSQL .= "   ON        CMN.JTU_NO = JYUCHU.JUCHU_NO" . "\r\n";

        $strWhere = " WHERE ";
        if ($conditions['CMN_NO'] != '') {
            $strSQL .= $strWhere . "CMN.CMN_NO = '";
            $strSQL .= $conditions['CMN_NO'];
            $strSQL .= "'";
            $strWhere = " AND " . "\r\n";

        }
        if ($conditions['SIY_FGN'] != '') {
            $strSQL .= $strWhere . "CMN.SIY_FGN = '";
            $strSQL .= $conditions['SIY_FGN'];
            $strSQL .= "'";
            $strWhere = " AND " . "\r\n";
        }
        if ($conditions['HNB_TAN_EMP_NO'] != '') {
            $strSQL .= $strWhere . "CMN.HNB_TAN_EMP_NO = '";
            $strSQL .= $conditions['HNB_TAN_EMP_NO'];
            $strSQL .= "'";
            $strWhere = " AND " . "\r\n";
        }
        $strSQL .= " ORDER BY CMN.CMN_NO, KASO.KASOUNO";

        //20131204 LuChao 既存バグ修正 Start
        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        //20131204 LuChao 既存バグ修正 End

        return $strSQL;
    }

    public function fncFrmListSelect($postData, $NENGETU)
    {
        $strSql = $this->fncFrmListSelectSql($postData, $NENGETU);

        // $cell = "CMN_NO,KASOU_NO";

        //$strSql = "SELECT " . $cell . " FROM  ( " . $strSql  . ") ";

        return parent::select($strSql);
    }

}
