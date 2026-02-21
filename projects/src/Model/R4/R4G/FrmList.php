<?php
// 共通クラスの読込み
namespace App\Model\R4\R4G;

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFnc;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmList extends ClsComDb
{
    // 20131004 kamei add Start
    // 解放が必要な変数をメンバーに設定
    protected $conn_orl = "";
    protected $Sel_Array = "";
    public $ClsComFnc;
    //public $components = array('ClsComFnc');
    // 20131004 kamei add end
    function fncSelectFromHkasoumeisaiSql($table, $conditions, $strKasouNo)
    {
        $strSQL = "";
        //20131205 LuChao 既存バグ修正 Start
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        //20131205 LuChao 既存バグ修正 End

        //20131205 luchao modify
        $CMN_NO = rtrim($conditions['strChumon']);
        $KASOUNO = rtrim($strKasouNo);

        $strSQL = " SELECT   CMN_NO" . "\r\n";
        $strSQL .= " ,        KASOUNO" . "\r\n";
        $strSQL .= " FROM     " . $table . "\r\n";
        $strSQL .= " WHERE    CMN_NO = '@CMN_NO'" . "\r\n";
        if (rtrim($strKasouNo) != "") {
            $strSQL .= " AND    KASOUNO = '@KASOUNO'" . "\r\n";
        }
        if ($table == "WK_HKASOUMEISAI_APPEND") {
            $strSQL .= " AND    UPD_SYA_CD = '@UPDUSER'" . "\r\n";
        }
        $strSQL .= " GROUP BY CMN_NO, KASOUNO" . "\r\n";

        $strSQL = str_replace("@CMN_NO", $CMN_NO, $strSQL);
        $strSQL = str_replace("@KASOUNO", $KASOUNO, $strSQL);
        //20131205 LuChao 既存バグ修正 Start
        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        //20131205 LuChao 既存バグ修正 End

        // $strSQL = " SELECT   CMN_NO";
        // $strSQL .= " ,        KASOUNO";
        // $strSQL .= " FROM     ";
        // $strSQL .= $table;
        // $strSQL .= "  WHERE    CMN_NO = '";
        // $strSQL .= $conditions['strChumon'];
        // $strSQL .= "'";
        // if ($strKasouNo != '')
        // {
        // $strSQL .= " AND    KASOUNO = '";
        // $strSQL .= $strKasouNo;
        // $strSQL .= "'";
        // }
        // $strSQL .= " GROUP BY CMN_NO, KASOUNO";

        //20131205 luchao modify
        return $strSQL;
    }

    function fncCustomerSelectSql($conditions, $blnOutput)
    {
        //20131205 luchao modify
        $strSQL = "";
        $CMN_NO = $conditions['CMN_NO'];
        $KASOUNO = $conditions['KASOUNO'];
        //20131205 luchao modify

        //20131205 LuChao 既存バグ修正 Start
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        //20131205 LuChao 既存バグ修正 End

        $strSQL = "SELECT   KASO.GYOUSYA_CD " . "\r\n";
        $strSQL .= ",        KASO.GYOUSYA_NM " . "\r\n";
        $strSQL .= ",        SUM(NVL(KASO.GAICYU_ZITU,0)) GAICYU_ZITU " . "\r\n";

        if ($blnOutput) {
            //20131205 LuChao 既存バグ修正 Start
            //$strSQL .= "FROM     WK_HKASOUMEISAI KASO  "."\r\n";
            $strSQL .= "FROM     WK_HKASOUMEISAI_APPEND KASO  " . "\r\n";
            //20131205 LuChao 既存バグ修正 End

        } else {
            $strSQL .= "FROM     HKASOUMEISAI KASO " . "\r\n";
        }

        //20131205 luchao modify
        $strSQL .= "WHERE    KASO.CMN_NO = '@CMN_NO'" . "\r\n";
        //$strSQL .= "WHERE    KASO.CMN_NO = '"."\r\n";
        //$strSQL .= $conditions['CMN_NO'];
        $strSQL .= "   AND      KASO.KASOUNO = '@KASOUNO'" . "\r\n";
        //$strSQL .= "'   AND      KASO.KASOUNO = '"."\r\n";
        //$strSQL .= $conditions['KASOUNO'];
        $strSQL .= "   AND      KASO.GYOUSYA_CD IS NOT NULL " . "\r\n";
        //20131205 luchao modify

        if (!$blnOutput) {
            $strSQL .= "  AND      NVL(KASO.GYOUSYA_CD,' ') <> '88888'" . "\r\n";
        } else {
            $strSQL .= " AND    UPD_SYA_CD = '@UPDUSER'" . "\r\n";
        }

        $strSQL .= "  GROUP BY KASO.GYOUSYA_CD ";
        $strSQL .= ",        KASO.GYOUSYA_NM ";

        $strSQL = str_replace("@CMN_NO", $CMN_NO, $strSQL);
        $strSQL = str_replace("@KASOUNO", $KASOUNO, $strSQL);

        //20131205 LuChao 既存バグ修正 Start
        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        //20131205 LuChao 既存バグ修正 End

        return $strSQL;
    }

    function fncMoneyKasouMeisaiSql($conditions, $strFzkKbn)
    {
        //20131205 luchao modify
        $strSQL = "";
        $CMN_NO = $conditions['CMN_NO'];
        $KASOUNO = $conditions['KASOUNO'];
        //20131205 luchao modify

        //20131205 LuChao 既存バグ修正 Start
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        //20131205 LuChao 既存バグ修正 End

        $strSQL = "SELECT  CMN_NO, KASOUNO" . "\r\n";
        $strSQL .= ",       SUM(CASE WHEN GYOUSYA_CD IS NULL THEN NVL(BUHIN_SYANAI_GEN,0) ELSE 0 END) SYA_GEN " . "\r\n";
        $strSQL .= ",       SUM(CASE WHEN GYOUSYA_CD IS NULL THEN NVL(BUHIN_SYANAI_ZITU,0) ELSE 0 END) SYAJITU " . "\r\n";
        $strSQL .= ",       SUM(CASE WHEN GYOUSYA_CD IS NOT NULL THEN NVL(GAICYU_GEN,0) ELSE 0 END) GAI_SYA_GEN " . "\r\n";
        $strSQL .= ",       SUM(CASE WHEN GYOUSYA_CD IS NOT NULL THEN NVL(GAICYU_ZITU,0) ELSE 0 END) GAI_SYAJITU " . "\r\n";

        //20131205 LuChao 既存バグ修正 Start
        // $strSQL .= "  FROM    WK_HKASOUMEISAI";
        $strSQL .= "  FROM    WK_HKASOUMEISAI_APPEND" . "\r\n";
        //20131205 LuChao 既存バグ修正 End

        $strSQL .= "  WHERE   FUZOKUHINKBN = '@strFzkKbn'" . "\r\n";
        // $strSQL .= "  WHERE   FUZOKUHINKBN = '";
        // $strSQL .= $strFzkKbn;
        $strSQL .= " AND     CMN_NO = '@CMN_NO'" . "\r\n";
        // $strSQL .= "' AND     CMN_NO = '";
        // $strSQL .= $conditions['CMN_NO'];
        $strSQL .= " AND     KASOUNO = '@KASOUNO'" . "\r\n";
        // $strSQL .= "' AND     KASOUNO = '";
        // $strSQL .= $conditions['KASOUNO'];

        //20131205 LuChao 既存バグ修正 Start
        $strSQL .= " AND    UPD_SYA_CD = '@UPDUSER'" . "\r\n";
        //20131205 LuChao 既存バグ修正 End

        $strSQL .= "  GROUP BY CMN_NO , KASOUNO";

        //20131205 luchao modify
        $strSQL = str_replace("@CMN_NO", $CMN_NO, $strSQL);
        $strSQL = str_replace("@KASOUNO", $KASOUNO, $strSQL);
        $strSQL = str_replace("@strFzkKbn", $strFzkKbn, $strSQL);
        //20131205 luchao modify

        //20131205 LuChao 既存バグ修正 Start
        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        //20131205 LuChao 既存バグ修正 End

        return $strSQL;
    }

    function fncMoneyM41E12Sql($conditions, $strFzkKbn)
    {
        $strSQL = "SELECT SUM(NVL(TTH_ICD_PRC_ZKM,0)) - SUM(TRUNC(NVL(TTH_ICD_PRC_ZKM,0) * NVL(SHZ_RT,0) / (100 + NVL(SHZ_RT,0)))) TEIKA";
        $strSQL .= ",      SUM(NVL(NBK_ZKM,0)) - SUM(TRUNC(NVL(NBK_ZKM,0) * NVL(SHZ_RT,0) / (100 + NVL(SHZ_RT,0)))) NEBIKI ";
        $strSQL .= ",      SUM(NVL(TTH_ICD_PRC_ZKM,0)) - SUM(TRUNC(NVL(TTH_ICD_PRC_ZKM,0) * NVL(SHZ_RT,0) / (100 + NVL(SHZ_RT,0)))) KEI_KIN ";
        $strSQL .= "  FROM   M41E12 ";
        $strSQL .= "  WHERE  CMN_NO = '";
        $strSQL .= $conditions['CMN_NO'];
        $strSQL .= "'  AND    FZH_TKB_KSH_KB = '";
        $strSQL .= $strFzkKbn;
        $strSQL .= "' GROUP BY CMN_NO ";

        return $strSQL;

    }

    function fncSearchSelectSql($conditions, $NENGETU)
    {
        //20131205 luchao add
        $strSQL = "";
        //20131205 luchao add

        //20131205 LuChao 既存バグ修正 Start
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        //20131205 LuChao 既存バグ修正 End

        $strSQL = "SELECT    CMN.CMN_NO" . "\r\n";
        //5269
        $strSQL .= ",  (KYK_OKY.INP_SIM1 || ' ' || KYK_OKY.INP_SIM2) KEIYAKUSYA" . "\r\n";
        $strSQL .= ",  (SIY_OKY.INP_SIM1 || ' ' || SIY_OKY.INP_SIM2) SIYOSYA" . "\r\n";
        //5274
        $strSQL .= ",  SIY_OKY.CSRKNANM" . "\r\n";
        $strSQL .= ",  CMN.KYOTN_CD" . "\r\n";
        // 2014/02/26 S0002対応 UPDATE Start
        //			$strSQL .= ",  BUS.KYOTN_NM BUSYOMEI" . "\r\n";
        $strSQL .= ",  BUS.KYOTN_RKN BUSYOMEI" . "\r\n";
        // 2014/02/26 S0002対応 UPDATE End
        $strSQL .= ",  CMN.HNB_TAN_EMP_NO" . "\r\n";
        $strSQL .= ",  (SYA.SYAIN_KNJ_SEI || '  ' || SYA.SYAIN_KNJ_MEI) SYAIN" . "\r\n";
        $strSQL .= ",  CMN.HNB_KTN_CD" . "\r\n";
        // 2014/02/26 S0002対応 UPDATE Start
        //			$strSQL .= ",  HAN.KYOTN_NM HANBAITEN" . "\r\n";
        $strSQL .= ",  HAN.KYOTN_RKN HANBAITEN" . "\r\n";
        // 2014/02/26 S0002対応 UPDATE End
        $strSQL .= ",  (CASE WHEN KASO.CMN_NO IS NULL THEN ('";
        $strSQL .= $NENGETU;
        $strSQL .= "' || '-' || LPAD(TO_CHAR(NVL((SELECT BANGO FROM HSAIBAN WHERE SAIBAN_CD = '1' AND NENGETU = '";
        $strSQL .= $NENGETU;
        $strSQL .= "'),0) + 1),4,'0')) ELSE KASO.KASOUNO END) KASOU_NO" . "\r\n";
        $strSQL .= ",  (CASE WHEN KASO.CMN_NO IS NULL THEN '1' ELSE '2' END) EXFLG" . "\r\n";
        $strSQL .= ",  CMN.HBSS_CD HANBAISYASYU" . "\r\n";
        $strSQL .= ",  CMN.SHZ_RT" . "\r\n";
        //5286

        $strSQL .= ",  JYUCHU.SDAIKATA_CD SDI_KAT" . "\r\n";
        $strSQL .= ",  JYUCHU.CAR_NO" . "\r\n";
        //5291

        $strSQL .= ",  BASE.BASEH_KN" . "\r\n";
        //20131206 FuXiaolin 既存バグ修正 Start
        $strSQL .= ",   KASO_OUT.UPD_DATE" . "\r\n";
        //20131206 FuXiaolin 既存バグ修正 End
        $strSQL .= "   FROM  M41E10 CMN" . "\r\n";
        $strSQL .= "   LEFT JOIN (SELECT DISTINCT CMN_NO FROM M41E12) MEI" . "\r\n";
        $strSQL .= "   ON        CMN.CMN_NO = MEI.CMN_NO" . "\r\n";
        //5230

        $strSQL .= "   LEFT JOIN M41C01 KYK_OKY" . "\r\n";
        $strSQL .= "   ON        KYK_OKY.DLRCSRNO = CMN.KYK_CUS_NO" . "\r\n";
        $strSQL .= "  LEFT JOIN M41C01 SIY_OKY" . "\r\n";
        $strSQL .= "   ON        CMN.SIY_CUS_NO = SIY_OKY.DLRCSRNO" . "\r\n";
        //5305

        $strSQL .= "   LEFT JOIN M27M01 BUS" . "\r\n";
        $strSQL .= "   ON        BUS.KYOTN_CD = CMN.KYOTN_CD" . "\r\n";
        $strSQL .= "   AND       BUS.HANSH_CD = '3634'" . "\r\n";
        $strSQL .= "   AND       BUS.ES_KB = 'E'" . "\r\n";

        $strSQL .= "   LEFT JOIN M29MA4 SYA" . "\r\n";
        //5314
        $strSQL .= "   ON        SYA.SYAIN_NO = CMN.HNB_TAN_EMP_NO" . "\r\n";

        //20131205 LuChao 既存バグ修正 Start
        $strSQL .= "   LEFT JOIN (SELECT DISTINCT CMN_NO, KASOUNO FROM WK_HKASOUMEISAI_APPEND WHERE UPD_SYA_CD = '@UPDUSER') KASO" . "\r\n";
        //$strSQL .= "   LEFT JOIN (SELECT DISTINCT CMN_NO, KASOUNO FROM WK_HKASOUMEISAI) KASO" . "\r\n";
        //20131205 LuChao 既存バグ修正 End
        $strSQL .= "   ON        KASO.CMN_NO = CMN.CMN_NO" . "\r\n";
        //20131206 Fuxiaolin 既存バグ修正 Start
        $strSQL .= "  LEFT JOIN(    SELECT CMN_NO,TO_CHAR(MAX(UPD_DATE),'YYYY/MM/DD') UPD_DATE    FROM HKASOUMEISAI    GROUP BY CMN_NO) KASO_OUT ON KASO_OUT.CMN_NO = CMN.CMN_NO" . "\r\n";
        //20131206 Fuxiaolin 既存バグ修正 End

        //5320
        $strSQL .= "   LEFT JOIN M27M01 HAN" . "\r\n";
        $strSQL .= "   ON        HAN.KYOTN_CD = CMN.KYOTN_CD" . "\r\n";
        $strSQL .= "   AND       HAN.HANSH_CD = '3634'" . "\r\n";
        $strSQL .= "   AND       HAN.ES_KB = 'E'" . "\r\n";
        //5326
        $strSQL .= "   LEFT JOIN M27AM1 BASE" . "\r\n";
        //5331
        $strSQL .= "   ON        BASE.BASEH_CD = CMN.MOD_CD" . "\r\n";
        $strSQL .= "   LEFT JOIN M27A01 JYUCHU" . "\r\n";
        $strSQL .= "   ON        CMN.JTU_NO = JYUCHU.JUCHU_NO" . "\r\n";
        //5336

        $strWhere = " WHERE ";
        if ($conditions['CMN_NO'] != '') {
            $strSQL .= $strWhere . "CMN.CMN_NO = '";
            $strSQL .= $conditions['CMN_NO'];
            $strSQL .= "'" . "\r\n";
            $strWhere = " AND ";

        }
        if ($conditions['SIY_FGN'] != '') {
            $strSQL .= $strWhere . "CMN.SIY_FGN = '";
            $strSQL .= $conditions['SIY_FGN'];
            $strSQL .= "'" . "\r\n";
            $strWhere = " AND ";
        }
        if ($conditions['HNB_TAN_EMP_NO'] != '') {
            $strSQL .= $strWhere . "CMN.HNB_TAN_EMP_NO = '";
            $strSQL .= $conditions['HNB_TAN_EMP_NO'];
            $strSQL .= "'" . "\r\n";
            $strWhere = " AND ";
        }
        $strSQL .= " ORDER BY CMN.CMN_NO, KASO.KASOUNO";

        //20131205 LuChao 既存バグ修正 Start
        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        //20131205 LuChao 既存バグ修正 End

        return $strSQL;

    }

    function fncCopyKasouInsertSql($conditions)
    {
        //20131204 LuChao 既存バグ修正 Start
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDAPP = "List";
        $UPDCLT = $this->GS_LOGINUSER['strClientNM'];
        //20131204 LuChao 既存バグ修正 End

        //20131205 LuChao 既存バグ修正 Start
        // $strSQL = "INSERT INTO WK_HKASOUMEISAI(";
        $strSQL = "INSERT INTO WK_HKASOUMEISAI_APPEND(";
        //20131205 LuChao 既存バグ修正 End

        //5563
        $strSQL .= "       CMN_NO";
        $strSQL .= ",      EDA_NO";
        $strSQL .= ",      KASOUNO";
        $strSQL .= ",      SYADAIKATA";
        $strSQL .= ",      CAR_NO";
        $strSQL .= ",      HANBAISYASYU";
        $strSQL .= ",      TOIAWASENM";
        $strSQL .= ",      SYASYU_NM";
        $strSQL .= ",      MEMO";
        $strSQL .= ",      FUZOKUHINKBN";
        $strSQL .= ",      GYOUSYA_CD";
        $strSQL .= ",      GYOUSYA_NM";
        $strSQL .= ",      GYOUSYA_KANA";
        $strSQL .= ",      MEDALCD";
        $strSQL .= ",      BUHINNM";
        $strSQL .= ",      BIKOU";
        $strSQL .= ",      SUURYOU";
        $strSQL .= ",      TEIKA";
        $strSQL .= ",      NEBIKI";
        $strSQL .= ",      BUHIN_SYANAI_GEN";
        $strSQL .= ",      BUHIN_SYANAI_ZITU";
        $strSQL .= ",      GAICYU_GEN";
        $strSQL .= ",      GAICYU_ZITU";
        $strSQL .= ",      ZEIRITU";
        $strSQL .= ",      KAZEIKBN";
        $strSQL .= ",      DELKBN";
        $strSQL .= ",      UPD_DATE";
        $strSQL .= ",      CREATE_DATE";
        $strSQL .= ",      UPD_SYA_CD";
        $strSQL .= ",      UPD_PRG_ID";
        $strSQL .= ",      UPD_CLT_NM";
        $strSQL .= ")";
        $strSQL .= "  SELECT  '";
        //5599
        $strSQL .= $conditions['SETCMNNO'];
        $strSQL .= "'";
        $strSQL .= ",      ROW_NUMBER() OVER(ORDER BY KASOUNO, FUZOKUHINKBN, EDA_NO) EDA_BAN";
        $strSQL .= ",      '";
        $strSQL .= $conditions['strKasouNO'];
        $strSQL .= "'";

        $strSQL .= ",      JYUCHU.SDAIKATA_CD SDI_KAT";
        //5632
        $strSQL .= ",      JYUCHU.CAR_NO";
        $strSQL .= ",      E10.HBSS_CD";
        $strSQL .= ",      SUBSTR(E10.HBSS_CD,1,5) || SUBSTR(E10.HBSS_CD,8,1)";
        $strSQL .= ",      BASE.BASEH_KN";
        $strSQL .= ",      KASO.MEMO";
        $strSQL .= ",      KASO.FUZOKUHINKBN";
        $strSQL .= ",      KASO.GYOUSYA_CD";
        $strSQL .= ",      KASO.GYOUSYA_NM";
        $strSQL .= ",      KASO.GYOUSYA_KANA";
        $strSQL .= ",      KASO.MEDALCD";
        $strSQL .= ",      KASO.BUHINNM";
        $strSQL .= ",      KASO.BIKOU";
        $strSQL .= ",      KASO.SUURYOU";
        $strSQL .= ",      KASO.TEIKA";
        $strSQL .= ",      KASO.NEBIKI";
        $strSQL .= ",      KASO.BUHIN_SYANAI_GEN";
        $strSQL .= ",      KASO.BUHIN_SYANAI_ZITU";
        $strSQL .= ",      KASO.GAICYU_GEN";
        $strSQL .= ",      KASO.GAICYU_ZITU";
        $strSQL .= ",      KASO.ZEIRITU";
        $strSQL .= ",      KASO.KAZEIKBN";
        $strSQL .= ",      KASO.DELKBN";
        $strSQL .= ",      KASO.UPD_DATE";
        $strSQL .= ",      KASO.CREATE_DATE";

        $strSQL .= ",     '";
        $strSQL .= $UPDUSER;
        $strSQL .= "'";
        $strSQL .= ",     '";
        $strSQL .= $UPDAPP;
        $strSQL .= "'";
        $strSQL .= ",     '";
        $strSQL .= $UPDCLT;
        $strSQL .= "'";

        $strSQL .= "  FROM HKASOUMEISAI KASO";
        $strSQL .= ",    M41E10 E10";
        $strSQL .= "  LEFT JOIN M27AM1 BASE";
        $strSQL .= "  ON    BASE.BASEH_CD = E10.MOD_CD";

        $strSQL .= "  LEFT JOIN M27A01 JYUCHU";
        $strSQL .= "  ON        E10.JTU_NO = JYUCHU.JUCHU_NO";
        $strSQL .= "  WHERE KASO.CMN_NO = '";
        $strSQL .= $conditions['WHERECMNNO'];
        $strSQL .= "'";

        $strSQL .= "  AND   E10.CMN_NO = '";
        $strSQL .= $conditions['SETCMNNO'];
        $strSQL .= "'";

        $strSQL .= "  AND   KASO.FUZOKUHINKBN = '0'";

        // $strSQL .= "  --ORDER BY KASOUNO, FUZOKUHINKBN, EDA_NO";
        $strSQL .= "  UNION ALL ";
        $strSQL .= " SELECT '";
        $strSQL .= $conditions['SETCMNNO'];
        $strSQL .= "'";

        $strSQL .= ",      ROW_NUMBER() OVER(ORDER BY KASOUNO, FUZOKUHINKBN, EDA_NO) EDA_BAN";
        $strSQL .= ",      '";
        $strSQL .= $conditions['strKasouNO'];
        $strSQL .= "'";

        $strSQL .= ",      JYUCHU.SDAIKATA_CD SDI_KAT";
        $strSQL .= ",      JYUCHU.CAR_NO";
        $strSQL .= ",      E10.HBSS_CD";
        $strSQL .= ",      SUBSTR(E10.HBSS_CD,1,5) || SUBSTR(E10.HBSS_CD,8,1)";
        $strSQL .= ",      BASE.BASEH_KN";
        $strSQL .= ",      KASO.MEMO";
        $strSQL .= ",      KASO.FUZOKUHINKBN";
        $strSQL .= ",      KASO.GYOUSYA_CD";
        $strSQL .= ",      KASO.GYOUSYA_NM";
        $strSQL .= ",      KASO.GYOUSYA_KANA";
        $strSQL .= ",      KASO.MEDALCD";
        $strSQL .= ",      KASO.BUHINNM";
        $strSQL .= ",      KASO.BIKOU";
        $strSQL .= ",      KASO.SUURYOU";
        $strSQL .= ",      KASO.TEIKA";
        $strSQL .= ",      KASO.NEBIKI";
        $strSQL .= ",      KASO.BUHIN_SYANAI_GEN";
        $strSQL .= ",      KASO.BUHIN_SYANAI_ZITU";
        $strSQL .= ",      KASO.GAICYU_GEN";
        $strSQL .= ",      KASO.GAICYU_ZITU";
        $strSQL .= ",      KASO.ZEIRITU";
        $strSQL .= ",      KASO.KAZEIKBN";
        $strSQL .= ",      KASO.DELKBN";
        $strSQL .= ",      KASO.UPD_DATE";
        $strSQL .= ",      KASO.CREATE_DATE";

        $strSQL .= ",     '";
        $strSQL .= $UPDUSER;
        $strSQL .= "'";
        $strSQL .= ",     '";
        $strSQL .= $UPDAPP;
        $strSQL .= "'";
        $strSQL .= ",     '";
        $strSQL .= $UPDCLT;
        $strSQL .= "'";

        $strSQL .= "  FROM HKASOUMEISAI KASO";
        $strSQL .= ",    M41E10 E10";
        $strSQL .= "  LEFT JOIN M27AM1 BASE";
        $strSQL .= "  ON    BASE.BASEH_CD = E10.MOD_CD";

        $strSQL .= "  LEFT JOIN M27A01 JYUCHU";
        $strSQL .= "  ON        E10.JTU_NO = JYUCHU.JUCHU_NO";
        $strSQL .= "  WHERE KASO.CMN_NO = '";
        $strSQL .= $conditions['WHERECMNNO'];
        $strSQL .= "'";

        $strSQL .= "  AND   E10.CMN_NO = '";
        $strSQL .= $conditions['SETCMNNO'];
        $strSQL .= "'";
        $strSQL .= "  AND   KASO.FUZOKUHINKBN = '1'";

        // $strSQL .= "  --ORDER BY KASOUNO, FUZOKUHINKBN, EDA_BAN";

        return $strSQL;
    }

    function fncUpdSaibanDelWKSql($conditions)
    {
        //20131205 luchao modify
        $strSQL = "";
        $CMN_NO = $conditions['CMN_NO'];
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];

        //20131205 LuChao 既存バグ修正 Start
        //$strSQL .= "DELETE FROM WK_HKASOUMEISAI";
        $strSQL .= "DELETE FROM WK_HKASOUMEISAI_APPEND";
        //20131205 LuChao 既存バグ修正 End

        $strSQL .= " WHERE  CMN_NO = '@CMN_NO'";
        // $strSQL .= " WHERE  CMN_NO = '";
        // $strSQL .= $conditions['CMN_NO'];
        $strSQL .= " AND  UPD_SYA_CD = '@UPDUSER'";

        $strSQL = str_replace("@CMN_NO", $CMN_NO, $strSQL);
        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        //20131205 luchao modify
        return $strSQL;

    }

    function fncUpdSaibanInsertSql($UPD_TIME, $strNengetu)
    {
        $strSQL = "";
        $strSQL .= "INSERT INTO HSAIBAN";
        $strSQL .= "(      SAIBAN_CD";
        $strSQL .= " ,      NENGETU";
        $strSQL .= ",      BANGO";
        $strSQL .= ",      UPD_DATE";
        $strSQL .= ",      CREATE_DATE )";
        $strSQL .= " VALUES (";
        $strSQL .= "'1'";
        $strSQL .= ", ";
        $strSQL .= "'";
        $strSQL .= $strNengetu;
        $strSQL .= "'";
        $strSQL .= ",'1'";
        $strSQL .= ", ";
        $strSQL .= $UPD_TIME;
        $strSQL .= ", ";
        $strSQL .= $UPD_TIME;
        $strSQL .= ")";

        return $strSQL;
    }

    function fncUpdSaibanUpdateSql($BANGO, $UPD_TIME, $strNengetu)
    {
        $strSQL = "";
        $strSQL .= "UPDATE HSAIBAN";
        $strSQL .= "   SET BANGO = ";
        $strSQL .= $BANGO;
        $strSQL .= " , UPD_DATE = ";
        $strSQL .= $UPD_TIME;
        $strSQL .= "   WHERE SAIBAN_CD = '1'";
        $strSQL .= "   AND NENGETU = '";
        $strSQL .= $strNengetu;
        $strSQL .= "'";
        return $strSQL;
    }

    function fncUpdSaibanSql($strNengetu)
    {
        $SAIBAN_CD = "1";

        $strSQL = "";
        $strSQL .= "SELECT NVL(BANGO,0) + 1 BANGO";
        $strSQL .= "  FROM   HSAIBAN ";
        $strSQL .= "WHERE  SAIBAN_CD = '";
        $strSQL .= $SAIBAN_CD;
        $strSQL .= "' AND NENGETU = '";
        $strSQL .= $strNengetu;
        $strSQL .= "'";

        return $strSQL;
    }

    function fncKasouTblCheckSql($conditions)
    {
        $strSQL = "";
        $strSQL .= "SELECT CMN_NO";
        $strSQL .= ",       MEMO";
        $strSQL .= "  FROM   HKASOUMEISAI";
        $strSQL .= "  WHERE  CMN_NO = '";
        $strSQL .= $conditions['CMN_NO'];
        $strSQL .= "'";

        if ($conditions['KASOUNO'] != NULL && $conditions['KASOUNO'] != '') {
            $strSQL .= "  AND         KASOUNO = '";
            $strSQL .= $conditions['KASOUNO'];
            $strSQL .= "'";

        } else {
            return $strSQL;
        }

        return $strSQL;
    }

    function fncDeleteKasouSql($conditions, $table)
    {
        //20131205 luchao modify
        $strSQL = "";
        $CMN_NO = $conditions['CMN_NO'];
        $KASOUNO = $conditions['KASOUNO'];
        //20131205 luchao modify

        //20131205 LuChao 既存バグ修正 Start
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        //20131205 LuChao 既存バグ修正 End

        $strSQL .= "DELETE FROM " . $table . "\r\n";
        $strSQL .= "WHERE       CMN_NO = '@CMNNO'" . "\r\n";
        $strSQL .= "AND         KASOUNO = '@KASOUNO'";

        //20131205 LuChao 既存バグ修正 Start
        if ($table == "WK_HKASOUMEISAI_APPEND") {
            $strSQL .= "AND         UPD_SYA_CD = '@UPDUSER'";
        }
        //20131205 LuChao 既存バグ修正 End

        $strSQL = str_replace("@CMNNO", $CMN_NO, $strSQL);
        $strSQL = str_replace("@KASOUNO", $KASOUNO, $strSQL);

        //20131205 LuChao 既存バグ修正 Start
        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        //20131205 LuChao 既存バグ修正 End

        return $strSQL;
    }

    function fncSelectM41E12ChkSql($conditions)
    {
        $strSQL = "";
        $strSQL .= "SELECT CMN_NO";
        $strSQL .= "  FROM   M41E12";
        $strSQL .= "  WHERE  CMN_NO = '";
        $strSQL .= $conditions['strChumon'];
        $strSQL .= "'";
        return $strSQL;
    }

    function fncM41E12CheckSql($conditions)
    {
        $strSQL = "";
        $strSQL .= "SELECT CMN_NO";
        $strSQL .= "  FROM   M41E12";
        $strSQL .= "  WHERE  CMN_NO = '";
        $strSQL .= $conditions['CMN_NO'];
        $strSQL .= "'";

        return $strSQL;
    }

    function fncSelHkasouSql($conditions)
    {
        $strSQL = "";
        $strSQL .= "SELECT CMN_NO";
        $strSQL .= "  FROM   HKASOUMEISAI";
        $strSQL .= "  WHERE  CMN_NO = '";
        $strSQL .= $conditions['CMN_NO'];
        $strSQL .= "' AND KASOUNO ='";
        $strSQL .= $conditions['KASOUNO'];
        $strSQL .= "'";
        return $strSQL;
    }

    function fncDeleteWKKASOUMEISAISql()
    {
        $strSQL = "";

        //20131205 LuChao 既存バグ修正 Start
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        //20131205 LuChao 既存バグ修正 End

        //20131205 LuChao 既存バグ修正 Start
        //$strSQL .= "DELETE FROM WK_HKASOUMEISAI A"."\r\n";
        $strSQL .= "DELETE FROM WK_HKASOUMEISAI_APPEND A" . "\r\n";
        //20131205 LuChao 既存バグ修正 End

        $strSQL .= " WHERE  EXISTS" . "\r\n";
        $strSQL .= "        (SELECT CMN_NO" . "\r\n";
        $strSQL .= "         FROM   HKASOUMEISAI B" . "\r\n";
        $strSQL .= "         WHERE  A.CMN_NO = B.CMN_NO" . "\r\n";
        $strSQL .= "         AND    A.EDA_NO = B.EDA_NO" . "\r\n";
        $strSQL .= "         AND    A.KASOUNO = B.KASOUNO" . "\r\n";
        $strSQL .= "         AND    A.FUZOKUHINKBN = B.FUZOKUHINKBN)" . "\r\n";

        //20131205 LuChao 既存バグ修正 Start
        $strSQL .= " AND    A.UPD_SYA_CD = '@UPDUSER'" . "\r\n";
        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        //20131205 LuChao 既存バグ修正 End

        return $strSQL;

    }

    function fncInsertWKKASOUMEISAISql($Array_Insert)
    {
        $strSQL = "";

        //20131204 LuChao 既存バグ修正 Start
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        //20131204 LuChao 既存バグ修正 End

        //20131204 LuChao 既存バグ修正 Start
        // $strSQL .= "INSERT INTO WK_HKASOUMEISAI"."\r\n";
        $strSQL .= "INSERT INTO WK_HKASOUMEISAI_APPEND" . "\r\n";
        //20131204 LuChao 既存バグ修正 End

        $strSQL .= "           (CMN_NO" . "\r\n";
        $strSQL .= ",           SYADAIKATA" . "\r\n";
        $strSQL .= ",           CAR_NO" . "\r\n";
        $strSQL .= ",           HANBAISYASYU" . "\r\n";
        $strSQL .= ",           TOIAWASENM" . "\r\n";
        $strSQL .= ",           SYASYU_NM" . "\r\n";
        $strSQL .= ",           KASOUNO" . "\r\n";
        $strSQL .= ",           MEMO" . "\r\n";
        $strSQL .= ",           ZEIRITU" . "\r\n";
        $strSQL .= ",           FUZOKUHINKBN" . "\r\n";
        $strSQL .= ",           DELKBN" . "\r\n";
        $strSQL .= ",           UPD_DATE" . "\r\n";
        $strSQL .= ",           CREATE_DATE" . "\r\n";
        $strSQL .= ",           EDA_NO" . "\r\n";
        $strSQL .= ",           MEDALCD" . "\r\n";
        $strSQL .= ",           BUHINNM" . "\r\n";
        $strSQL .= ",           BIKOU" . "\r\n";
        $strSQL .= ",           TEIKA" . "\r\n";
        $strSQL .= ",           SUURYOU" . "\r\n";
        $strSQL .= ",           BUHIN_SYANAI_GEN_RITU" . "\r\n";
        $strSQL .= ",           BUHIN_SYANAI_GEN" . "\r\n";
        $strSQL .= ",           BUHIN_SYANAI_ZITU_RITU" . "\r\n";
        $strSQL .= ",           BUHIN_SYANAI_ZITU" . "\r\n";
        $strSQL .= ",           GYOUSYA_CD" . "\r\n";
        $strSQL .= ",           GYOUSYA_NM" . "\r\n";
        $strSQL .= ",           KAZEIKBN" . "\r\n";
        $strSQL .= ",           GAICYU_GEN_RITU" . "\r\n";
        $strSQL .= ",           GAICYU_GEN" . "\r\n";
        $strSQL .= ",           GAICYU_ZITU_RITU" . "\r\n";
        $strSQL .= ",           GAICYU_ZITU" . "\r\n";
        //20131205 LuChao 既存バグ修正 Start
        $strSQL .= ",           UPD_SYA_CD )" . "\r\n";
        //20131205 LuChao 既存バグ修正 End

        $strSQL .= " SELECT " . "\r\n";
        $strSQL .= "            CMN_NO" . "\r\n";
        $strSQL .= ",           SYADAIKATA" . "\r\n";
        $strSQL .= ",           CAR_NO" . "\r\n";
        $strSQL .= ",           HANBAISYASYU" . "\r\n";
        $strSQL .= ",           TOIAWASENM" . "\r\n";
        $strSQL .= ",           SYASYU_NM" . "\r\n";
        $strSQL .= ",           KASOUNO" . "\r\n";
        $strSQL .= ",           MEMO" . "\r\n";
        $strSQL .= ",           ZEIRITU" . "\r\n";
        $strSQL .= ",           FUZOKUHINKBN" . "\r\n";
        $strSQL .= ",           DELKBN" . "\r\n";
        $strSQL .= ",           UPD_DATE" . "\r\n";
        $strSQL .= ",           CREATE_DATE" . "\r\n";
        $strSQL .= ",           EDA_NO" . "\r\n";
        $strSQL .= ",           MEDALCD" . "\r\n";
        $strSQL .= ",           BUHINNM" . "\r\n";
        $strSQL .= ",           BIKOU" . "\r\n";
        $strSQL .= ",           TEIKA" . "\r\n";
        $strSQL .= ",           SUURYOU" . "\r\n";
        $strSQL .= ",           BUHIN_SYANAI_GEN_RITU" . "\r\n";
        $strSQL .= ",           BUHIN_SYANAI_GEN" . "\r\n";
        $strSQL .= ",           BUHIN_SYANAI_ZITU_RITU" . "\r\n";
        $strSQL .= ",           BUHIN_SYANAI_ZITU" . "\r\n";
        $strSQL .= ",           GYOUSYA_CD" . "\r\n";
        $strSQL .= ",           GYOUSYA_NM" . "\r\n";
        $strSQL .= ",           KAZEIKBN" . "\r\n";
        $strSQL .= ",           GAICYU_GEN_RITU" . "\r\n";
        $strSQL .= ",           GAICYU_GEN" . "\r\n";
        $strSQL .= ",           GAICYU_ZITU_RITU" . "\r\n";
        $strSQL .= ",           GAICYU_ZITU" . "\r\n";

        //20131205 LuChao 既存バグ修正 Start
        $strSQL .= ",           '@UPDUSER' UPD_SYA_CD" . "\r\n";
        //20131205 LuChao 既存バグ修正 End

        $strSQL .= " FROM       HKASOUMEISAI" . "\r\n";
        $strSQL .= " WHERE      CMN_NO  IN (" . "\r\n";
        $strSQL .= "   SELECT CMN_NO FROM M41E10" . "\r\n";

        $strWhere = " WHERE ";
        if ($Array_Insert['CMN_NO'] != '') {
            $strSQL .= $strWhere . "CMN_NO = '";
            $strSQL .= $Array_Insert['CMN_NO'];
            $strSQL .= "'" . "\r\n";
            $strWhere = " AND ";

        }
        if ($Array_Insert['SIY_FGN'] != '') {
            $strSQL .= $strWhere . "SIY_FGN = '";
            $strSQL .= $Array_Insert['SIY_FGN'];
            $strSQL .= "'" . "\r\n";
            $strWhere = " AND ";
        }
        if ($Array_Insert['HNB_TAN_EMP_NO'] != '') {
            $strSQL .= $strWhere . "HNB_TAN_EMP_NO = '";
            $strSQL .= $Array_Insert['HNB_TAN_EMP_NO'];
            $strSQL .= "'" . "\r\n";
            $strWhere = " AND ";
        }
        $strSQL .= ")";

        //20131205 LuChao 既存バグ修正 Start
        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        //20131205 LuChao 既存バグ修正 End

        return $strSQL;
    }

    function fncInsertNoMeisaiInsSql($postData, $strfirstKasouNo)
    {
        $this->ClsComFnc = new ClsComFnc();
        $strSQL = "";

        //20131204 LuChao 既存バグ修正 Start
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        // $strSQL .= "INSERT INTO WK_HKASOUMEISAI";
        $strSQL .= "INSERT INTO WK_HKASOUMEISAI_APPEND" . "\r\n";
        // $strSQL .= "INSERT INTO WK_HKASOUMEISAI" . "\r\n";
        //20131204 LuChao 既存バグ修正 End

        $strSQL .= "           (CMN_NO" . "\r\n";
        $strSQL .= ",           EDA_NO" . "\r\n";
        $strSQL .= ",           SYADAIKATA" . "\r\n";
        $strSQL .= ",           CAR_NO" . "\r\n";
        $strSQL .= ",           HANBAISYASYU" . "\r\n";
        $strSQL .= ",           TOIAWASENM" . "\r\n";
        $strSQL .= ",           SYASYU_NM" . "\r\n";
        $strSQL .= ",           KASOUNO" . "\r\n";
        $strSQL .= ",           MEMO" . "\r\n";
        $strSQL .= ",           FUZOKUHINKBN" . "\r\n";
        $strSQL .= ",           UPD_DATE" . "\r\n";
        $strSQL .= ",           CREATE_DATE" . "\r\n";
        $strSQL .= ",           UPD_SYA_CD)" . "\r\n";
        $strSQL .= " VALUES     " . "\r\n";
        $strSQL .= "(           '@CMN_NO'" . "\r\n";
        $strSQL .= ",           '1'" . "\r\n";
        $strSQL .= ",           '@SYADAIKATA'" . "\r\n";
        $strSQL .= ",           '@CAR_NO'" . "\r\n";
        $strSQL .= ",           '@HANBAISYASYU'" . "\r\n";
        $strSQL .= ",           '@TOIAWASENM'" . "\r\n";
        $strSQL .= ",           '@SYASYU_NM'" . "\r\n";
        $strSQL .= ",           '@KASOUNO'" . "\r\n";
        $strSQL .= ",           '@MEMO'" . "\r\n";
        $strSQL .= ",           '@FUZOKUHINKBN'" . "\r\n";
        $strSQL .= ",           @UPD_DATE" . "\r\n";
        $strSQL .= ",           @CREATE_DATE" . "\r\n";
        //20140424 S0010 ユーザーIDの頭0対応 st
        //			$strSQL .= ",           @UPDUSER" . "\r\n";
        $strSQL .= ",           '@UPDUSER'" . "\r\n";
        //20140424 S0010 ユーザーIDの頭0対応 st
        $strSQL .= ")" . "\r\n";

        $strSQL = str_replace("@CMN_NO", $postData['strChumon'], $strSQL);
        $strSQL = str_replace("@SYADAIKATA", $postData['strSyadaiKata'], $strSQL);
        $strSQL = str_replace("@CAR_NO", $postData['strCar_NO'], $strSQL);
        $strSQL = str_replace("@HANBAISYASYU", $postData['strHanbaiSyasyu'], $strSQL);
        $strSQL = str_replace("@TOIAWASENM", $postData['strKosyo'], $strSQL);
        $strSQL = str_replace("@SYASYU_NM", $postData['strSyasyu'], $strSQL);
        $strSQL = str_replace("@KASOUNO", $strfirstKasouNo, $strSQL);
        $strSQL = str_replace("@MEMO", $postData['strHaisouSiji'], $strSQL);
        $strSQL = str_replace("@FUZOKUHINKBN", " ", $strSQL);
        $strSQL = str_replace("@UPD_DATE", $this->ClsComFnc->FncSqlDate($this->ClsComFnc->FncGetSysDate("Y/m/d H:i:s")), $strSQL);
        $strSQL = str_replace("@CREATE_DATE", $this->ClsComFnc->FncSqlDate($this->ClsComFnc->FncGetSysDate("Y/m/d H:i:s")), $strSQL);

        //20131205 LuChao 既存バグ修正 Start
        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        //20131205 LuChao 既存バグ修正 End

        return $strSQL;

    }

    function fncDeleteHKASOUMEISAISql($strTableNM, $strChumon)
    {
        $strSQL = "";

        //20131205 LuChao 既存バグ修正 Start
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        //20131205 LuChao 既存バグ修正 End

        $strSQL .= " DELETE FROM  " . $strTableNM . "\r\n";

        $strSQL .= "        WHERE CMN_NO = '@CMNNO'" . "\r\n";

        //20131205 LuChao 既存バグ修正 Start
        if ($strTableNM == "WK_HKASOUMEISAI_APPEND") {
            $strSQL .= "        AND UPD_SYA_CD = '@UPDUSER'" . "\r\n";
        }
        //20131205 LuChao 既存バグ修正 End

        $strSQL = str_replace('@CMNNO', $strChumon, $strSQL);

        //20131205 LuChao 既存バグ修正 Start
        $strSQL = str_replace('@UPDUSER', $UPDUSER, $strSQL);
        //20131205 LuChao 既存バグ修正 End

        return $strSQL;
    }

    function fncInsertHKASOUMEISAISql($strChumon)
    {
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDAPP = "List";
        $UPDCLTNM = $this->GS_LOGINUSER['strClientNM'];

        $strSQL = "";
        $strSQL .= "INSERT INTO HKASOUMEISAI" . "\r\n";
        $strSQL .= "           (CMN_NO" . "\r\n";
        $strSQL .= ",           SYADAIKATA" . "\r\n";
        $strSQL .= ",           CAR_NO" . "\r\n";
        $strSQL .= ",           HANBAISYASYU" . "\r\n";
        $strSQL .= ",           TOIAWASENM" . "\r\n";
        $strSQL .= ",           SYASYU_NM" . "\r\n";
        $strSQL .= ",           KASOUNO" . "\r\n";
        //2006/04/20 DELETE Start 車両配送指示をOPTION画面からList画面で入力するように変更したため
        //$strSQL.=",           MEMO" ."\r\n";
        //2006/04/20 DELETE End
        $strSQL .= ",           ZEIRITU" . "\r\n";
        $strSQL .= ",           FUZOKUHINKBN" . "\r\n";
        $strSQL .= ",           DELKBN" . "\r\n";
        $strSQL .= ",           UPD_DATE" . "\r\n";
        $strSQL .= ",           CREATE_DATE" . "\r\n";
        $strSQL .= ",           EDA_NO" . "\r\n";
        $strSQL .= ",           MEDALCD" . "\r\n";
        $strSQL .= ",           BUHINNM" . "\r\n";
        $strSQL .= ",           BIKOU" . "\r\n";
        $strSQL .= ",           TEIKA" . "\r\n";
        $strSQL .= ",           SUURYOU" . "\r\n";
        $strSQL .= ",           BUHIN_SYANAI_GEN_RITU" . "\r\n";
        $strSQL .= ",           BUHIN_SYANAI_GEN" . "\r\n";
        $strSQL .= ",           BUHIN_SYANAI_ZITU_RITU" . "\r\n";
        $strSQL .= ",           BUHIN_SYANAI_ZITU" . "\r\n";
        $strSQL .= ",           GYOUSYA_CD" . "\r\n";
        $strSQL .= ",           GYOUSYA_NM" . "\r\n";
        $strSQL .= ",           KAZEIKBN" . "\r\n";
        $strSQL .= ",           GAICYU_GEN_RITU" . "\r\n";
        $strSQL .= ",           GAICYU_GEN" . "\r\n";
        $strSQL .= ",           GAICYU_ZITU_RITU" . "\r\n";
        $strSQL .= ",           GAICYU_ZITU" . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",           UPD_SYA_CD" . "\r\n";
        $strSQL .= ",           UPD_PRG_ID" . "\r\n";
        $strSQL .= ",           UPD_CLT_NM" . "\r\n";
        //2006/12/08 UPD End

        $strSQL .= ")" . "\r\n";
        $strSQL .= " SELECT     " . "\r\n";
        $strSQL .= "            CMN_NO" . "\r\n";
        $strSQL .= ",           SYADAIKATA" . "\r\n";
        $strSQL .= ",           CAR_NO" . "\r\n";
        $strSQL .= ",           HANBAISYASYU" . "\r\n";
        $strSQL .= ",           TOIAWASENM" . "\r\n";
        $strSQL .= ",           SYASYU_NM" . "\r\n";
        $strSQL .= ",           KASOUNO" . "\r\n";
        //2006/04/20 DELETE Start 車両配送指示をOPTION画面からList画面で入力するように変更したため
        //$strSQL.=",           MEMO" ."\r\n";
        //2006/04/20 DELETE End
        $strSQL .= ",           ZEIRITU" . "\r\n";
        $strSQL .= ",           FUZOKUHINKBN" . "\r\n";
        $strSQL .= ",           DELKBN" . "\r\n";
        $strSQL .= ",           UPD_DATE" . "\r\n";
        $strSQL .= ",           CREATE_DATE" . "\r\n";
        $strSQL .= ",           EDA_NO" . "\r\n";
        $strSQL .= ",           MEDALCD" . "\r\n";
        $strSQL .= ",           BUHINNM" . "\r\n";
        $strSQL .= ",           BIKOU" . "\r\n";
        $strSQL .= ",           TEIKA" . "\r\n";
        $strSQL .= ",           SUURYOU" . "\r\n";
        $strSQL .= ",           BUHIN_SYANAI_GEN_RITU" . "\r\n";
        $strSQL .= ",           BUHIN_SYANAI_GEN" . "\r\n";
        $strSQL .= ",           BUHIN_SYANAI_ZITU_RITU" . "\r\n";
        $strSQL .= ",           BUHIN_SYANAI_ZITU" . "\r\n";
        $strSQL .= ",           GYOUSYA_CD" . "\r\n";
        $strSQL .= ",           GYOUSYA_NM" . "\r\n";
        $strSQL .= ",           KAZEIKBN" . "\r\n";
        $strSQL .= ",           GAICYU_GEN_RITU" . "\r\n";
        $strSQL .= ",           GAICYU_GEN" . "\r\n";
        $strSQL .= ",           GAICYU_ZITU_RITU" . "\r\n";
        $strSQL .= ",           GAICYU_ZITU" . "\r\n";
        //TODO 2006/12/08 UPD Start
        //2013/12/17 LuChao 既存バグ修正 Start
        // $strSQL .= ",           '@UPDUSER'" . "\r\n";
        $strSQL .= ",           UPD_SYA_CD" . "\r\n";
        //2013/12/17 LuChao 既存バグ修正 End
        $strSQL .= ",           '@UPDAPP'" . "\r\n";
        $strSQL .= ",           '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End

        //2013/12/05 LuChao 既存バグ修正 Start
        $strSQL .= " FROM       WK_HKASOUMEISAI_APPEND" . "\r\n";
        //$strSQL .= " FROM       WK_HKASOUMEISAI" . "\r\n";
        //2013/12/05 LuChao 既存バグ修正 End

        $strSQL .= " WHERE      CMN_NO = '@CMNNO'" . "\r\n";

        //2013/12/17 LuChao 既存バグ修正 Start
        $strSQL .= " AND        UPD_SYA_CD = '@UPDUSER'" . "\r\n";
        //2013/12/17 LuChao 既存バグ修正 End

        $strSQL = str_replace("@CMNNO", $strChumon, $strSQL);
        //TODO 2006/12/08 UPD Start
        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        $strSQL = str_replace("@UPDAPP", $UPDAPP, $strSQL);
        $strSQL = str_replace("@UPDCLT", $UPDCLTNM, $strSQL);
        //2006/12/08 UPD End

        return $strSQL;
    }

    public function fncUpdateKasouNOOnlySql($strOldKasouNo, $strNewKasouNo, $strChumon)
    {
        $strSQL = "";
        $strSQL .= " UPDATE HKASOUMEISAI" . "\r\n";
        $strSQL .= " SET    KASOUNO = '" . $strNewKasouNo . "'" . "\r\n";
        $strSQL .= " WHERE  CMN_NO = '" . $strChumon . "'" . "\r\n";
        $strSQL .= " AND    KASOUNO = '" . rtrim($strOldKasouNo) . "'" . "\r\n";

        return $strSQL;
    }

    /**********************************************************************
     *処 理 名：配送指示をUPDATEする
     *関 数 名：fncUpdateHaisouSijiSql
     *引    数：無し
     *戻 り 値：SQL文
     *処理説明：配送指示をUPDATEする
     **********************************************************************/
    public function fncUpdateHaisouSijiSql($strHaisouSiji, $strChumon, $strKasou)
    {
        $strSQL = "";
        $strSQL .= "UPDATE HKASOUMEISAI" . "\r\n";
        $strSQL .= "SET    MEMO = '@MEMO'" . "\r\n";
        $strSQL .= "WHERE  CMN_NO = '@CMNNO'" . "\r\n";
        $strSQL .= "AND    KASOUNO = '@KASOUNO'" . "\r\n";
        $strSQL = str_replace("@MEMO", $strHaisouSiji, $strSQL);
        $strSQL = str_replace("@CMNNO", $strChumon, $strSQL);
        $strSQL = str_replace("@KASOUNO", $strKasou, $strSQL);

        return $strSQL;
    }

    /**********************************************************************
     *処 理 名：架装明細を印刷するためのSQL
     *関 数 名：fncKasouMPrintSelSql
     *引    数：無し
     *戻 り 値：SQL
     *処理説明：架装明細を印刷するためのSQL
     **********************************************************************/
    public function fncKasouMPrintSelSql($strChumon, $strKasou)
    {
        $strSQL = "";

        $strSQL .= "SELECT KASO.CMN_NO" . "\r\n";
        //2006/04/08 UPDATE Start
        $strSQL .= ",         (KYK_OKY.INP_SIM1 || ' ' || KYK_OKY.INP_SIM2) KEIYAKUSYA" . "\r\n";
        $strSQL .= ",         CMN.KYOTN_CD" . "\r\n";
        // 2014/02/26 S0002対応 UPDATE Start
        //			$strSQL .= ",         BUS.KYOTN_NM BUSYOMEI" . "\r\n";
        $strSQL .= ",         BUS.KYOTN_RKN BUSYOMEI" . "\r\n";
        // 2014/02/26 S0002対応UPDATE End
        $strSQL .= ",         CMN.HNB_TAN_EMP_NO" . "\r\n";
        $strSQL .= ",         (SYA.SYAIN_KNJ_SEI || '  ' || SYA.SYAIN_KNJ_MEI) SYAIN" . "\r\n";
        //2006/04/08 UPDATE End
        $strSQL .= ",      KASO.SYADAIKATA" . "\r\n";
        $strSQL .= ",      KASO.CAR_NO" . "\r\n";
        $strSQL .= ",      KASO.HANBAISYASYU" . "\r\n";
        $strSQL .= ",      KASO.SYASYU_NM" . "\r\n";
        $strSQL .= ",      KASO.KASOUNO" . "\r\n";
        $strSQL .= ",      KASO.MEMO" . "\r\n";
        $strSQL .= ",      KASO.FUZOKUHINKBN" . "\r\n";
        $strSQL .= ",      KASO.MEDALCD" . "\r\n";
        $strSQL .= ",      KASO.BUHINNM" . "\r\n";
        $strSQL .= ",      KASO.BIKOU" . "\r\n";
        $strSQL .= ",      KASO.SUURYOU" . "\r\n";
        $strSQL .= ",      KASO.TEIKA" . "\r\n";
        $strSQL .= ",      KASO.BUHIN_SYANAI_GEN" . "\r\n";
        $strSQL .= ",      KASO.BUHIN_SYANAI_ZITU" . "\r\n";
        $strSQL .= ",      (SELECT SUM(TEIKA)" . "\r\n";
        $strSQL .= "        FROM HKASOUMEISAI W_KASO" . "\r\n";
        $strSQL .= "        WHERE W_KASO.CMN_NO = KASO.CMN_NO" . "\r\n";
        $strSQL .= "        AND W_KASO.KASOUNO = KASO.KASOUNO) GOUKEI" . "\r\n";
        //2006/04/20 UPDATE Start M41E12が存在しない場合も架装明細は表示するため
        //$strSQL .="FROM   HKASOUMEISAI KASO" ."\r\n";
        //2006/04/08 UPDATE Start
        //$strSQL .="LEFT JOIN M41E10 CMN" ."\r\n";
        //$strSQL .="ON        CMN.CMN_NO = KASO.CMN_NO" ."\r\n";
        $strSQL .= "FROM      M41E10 CMN" . "\r\n";
        $strSQL .= "LEFT JOIN HKASOUMEISAI KASO" . "\r\n";
        $strSQL .= "ON        CMN.CMN_NO = KASO.CMN_NO" . "\r\n";
        //2006/04/20 UPDATE End
        $strSQL .= "LEFT JOIN M41C01 KYK_OKY" . "\r\n";
        $strSQL .= "ON        KYK_OKY.DLRCSRNO = CMN.KYK_CUS_NO" . "\r\n";
        $strSQL .= "LEFT JOIN M27M01 BUS" . "\r\n";
        $strSQL .= "ON        BUS.KYOTN_CD = CMN.KYOTN_CD" . "\r\n";
        $strSQL .= "AND       BUS.HANSH_CD = '3634'" . "\r\n";
        $strSQL .= "AND       BUS.ES_KB = 'E'" . "\r\n";
        $strSQL .= "LEFT JOIN M29MA4 SYA" . "\r\n";
        $strSQL .= "ON        SYA.SYAIN_NO = CMN.HNB_TAN_EMP_NO" . "\r\n";
        //$strSQL .="LEFT JOIN M27M01 HAN" ."\r\n";
        //$strSQL .="ON        HAN.KYOTN_CD = CMN.KYOTN_CD" ."\r\n";
        //$strSQL .="AND       HAN.HANSH_CD = '3634'" ."\r\n";
        //$strSQL .="AND       HAN.ES_KB = 'E'" ."\r\n";
        //$strSQL .="LEFT JOIN M27AM1 BASE" ."\r\n";
        //$strSQL .="ON        BASE.BASEH_CD = CMN.MOD_CD" ."\r\n";
        //2006/04/08 UPDATE End
        //2006/04/20 UPDATE Start
        //  $strSQL .="WHERE  KASO.CMN_NO = '@CMNNO'" ."\r\n";
        //     2006/04/10 INSERT Start
        //     $strSQL .=" AND   KASO.KASOUNO = '@KASOUNO'" ."\r\n";
        //     2006/04/10 INSERT End
        $strSQL .= "WHERE    CMN.CMN_NO = '@CMNNO'" . "\r\n";
        $strSQL .= "AND      NVL(TRIM(KASO.KASOUNO),'@KASOUNO') = '@KASOUNO'" . "\r\n";
        //2006/04/20 UPDATE End
        $strSQL .= "AND    NVL(KASO.GYOUSYA_CD,'999999') = '999999'" . "\r\n";
        //2006/04/20 UPDATE Start    ソート順を行№順に変更
        //2006/04/06 UPDATE Start    ソート順にメダルコードを追加
        //$strSQL .="ORDER BY KASO.FUZOKUHINKBN, KASO.MEDALCD, KASO.KASOUNO, KASO.EDA_NO" ."\r\n";
        $strSQL .= "ORDER BY KASO.FUZOKUHINKBN, KASO.EDA_NO" . "\r\n";
        //2006/04/06 UPDATE End

        $strSQL = str_replace("@CMNNO", $strChumon, $strSQL);
        //2006/04/10 INSERT Start
        $strSQL = str_replace("@KASOUNO", ($strKasou == "") ? " " : $strKasou, $strSQL);
        //2006/04/10 INSERT End

        return $strSQL;
    }

    /**********************************************************************
     *処 理 名：外注加工依頼書を作成するためのSQL
     *関 数 名：fncGaichuPrintSelectSql
     *引    数：無し
     *戻 り 値：SQL
     *処理説明：外注加工依頼書を作成するためのSQL
     ***********************************************************************/
    public function fncGaichuPrintSelectSql($strChumon, $strKasou)
    {
        $strSQL = "";

        $strSQL .= "SELECT    KASO.GYOUSYA_CD TORIHIKI_CD" . "\r\n";
        $strSQL .= ",         (TRK.ATO_DTRPITNM1 || TRK.ATO_DTRPITNM2) TORIHIKI_NM" . "\r\n";
        $strSQL .= ",         (OKY.INP_SIM1 || ' ' || OKY.INP_SIM2) SIYOSYA_KN" . "\r\n";
        $strSQL .= ",         KASO.TOIAWASENM" . "\r\n";
        //2006/04/19 INSERT Start 架装が一つもなくても架装印刷を行うため、必要項目を外注に追加
        $strSQL .= ",         KASO.HANBAISYASYU" . "\r\n";
        $strSQL .= ",         KASO.SYADAIKATA" . "\r\n";
        $strSQL .= ",         KASO.CAR_NO" . "\r\n";
        $strSQL .= ",         SYA.SYAIN_KNJ_MEI" . "\r\n";
        $strSQL .= ",         KASO.MEMO" . "\r\n";
        $strSQL .= ",         (KYK_OKY.INP_SIM1 || ' ' || KYK_OKY.INP_SIM2) KEIYAKUSYA" . "\r\n";
        //2006/04/19 INSERT End
        $strSQL .= ",         (CASE WHEN KASO.SYADAIKATA IS NULL THEN KASO.CAR_NO ELSE KASO.SYADAIKATA || '-' || KASO.CAR_NO END) SYADAI_NO" . "\r\n";
        $strSQL .= ",         BASE.BASEH_KN SYASYUMEI" . "\r\n";
        $strSQL .= ",         (SELECT BUSYO_NM FROM HPRINTTANTO) TANTO_BUSYO" . "\r\n";
        $strSQL .= ",         (SELECT TANTO_SEI FROM HPRINTTANTO) TANTO_NM" . "\r\n";
        $strSQL .= ",         KASO.KASOUNO KASOU_NO" . "\r\n";
        $strSQL .= ",         '@TODAY' HAKKOBI" . "\r\n";
        $strSQL .= ",         KASO.CMN_NO" . "\r\n";
        $strSQL .= ",         CMN.KYOTN_CD" . "\r\n";
        // 2014/02/26 S0002対応 UPDATE Start
        //			$strSQL .= ",         BUS.KYOTN_NM BUSYO_NM" . "\r\n";
        $strSQL .= ",         BUS.KYOTN_RKN BUSYO_NM" . "\r\n";
        // 2014/02/26 S0002対応 UPDATE End
        $strSQL .= ",         SYA.SYAIN_KNJ_SEI" . "\r\n";
        $strSQL .= ",         KASO.BUHINNM" . "\r\n";
        $strSQL .= ",         NVL(KASO.GAICYU_ZITU,0) SEIKYU" . "\r\n";
        $strSQL .= ",         (SELECT SUM(NVL(T_KASO.GAICYU_ZITU,0)) TOTAL" . "\r\n";
        $strSQL .= "           FROM   HKASOUMEISAI T_KASO " . "\r\n";
        $strSQL .= "           WHERE  T_KASO.CMN_NO = KASO.CMN_NO " . "\r\n";
        $strSQL .= "           AND    T_KASO.GYOUSYA_CD = KASO.GYOUSYA_CD) SYOUKEI" . "\r\n";
        //2006/03/23 UPDATE Start    '2006/04/10 KASOUNOを条件に追加
        $strSQL .= ", (SELECT COUNT(*) FROM (SELECT DISTINCT GYOUSYA_CD FROM HKASOUMEISAI WHERE CMN_NO = '@CMNNO' AND KASOUNO = '@KASOUNO' AND GYOUSYA_CD IS NOT NULL)) MAI" . "\r\n";
        //2006/03/23 UPDATE End
        $strSQL .= "FROM      HKASOUMEISAI KASO" . "\r\n";
        $strSQL .= "LEFT JOIN M41E10 CMN" . "\r\n";
        $strSQL .= "ON        CMN.CMN_NO = KASO.CMN_NO" . "\r\n";
        $strSQL .= "LEFT JOIN M28M68 TRK" . "\r\n";
        $strSQL .= "ON        TRK.ATO_DTRPITCD = KASO.GYOUSYA_CD" . "\r\n";
        $strSQL .= "LEFT JOIN M41C01 OKY" . "\r\n";
        $strSQL .= "ON        OKY.DLRCSRNO = CMN.SIY_CUS_NO" . "\r\n";
        //2006/04/19 UPDATE Start
        $strSQL .= "LEFT JOIN M41C01 KYK_OKY" . "\r\n";
        $strSQL .= "ON        KYK_OKY.DLRCSRNO = CMN.KYK_CUS_NO" . "\r\n";
        //2006/04/19 UPDATE End
        $strSQL .= "LEFT JOIN M27AM1 BASE" . "\r\n";
        $strSQL .= "ON        BASE.BASEH_CD = CMN.MOD_CD" . "\r\n";

        //2006/03/23 UPDATE Start
        $strSQL .= "LEFT JOIN M27M01 BUS" . "\r\n";
        $strSQL .= "ON        BUS.KYOTN_CD = CMN.KYOTN_CD" . "\r\n";
        $strSQL .= "AND       BUS.HANSH_CD = '3634'" . "\r\n";
        $strSQL .= "AND       BUS.ES_KB = 'E'" . "\r\n";
        //$strSQL .="LEFT JOIN HBUSYO BUS" ."\r\n";
        //$strSQL .="ON        BUS.BUSYO_CD = CMN.KYOTN_CD" ."\r\n";
        //2006/03/23 UPDATE End
        $strSQL .= "LEFT JOIN M29MA4 SYA" . "\r\n";
        $strSQL .= "ON        SYA.SYAIN_NO = CMN.HNB_TAN_EMP_NO" . "\r\n";
        //2006/03/22 UPDATE Start
        //$strSQL .="LEFT JOIN (SELECT CMN_NO, GYOUSYA_CD, COUNT(CMN_NO) MAI" ."\r\n";
        //$strSQL .="                   FROM HKASOUMEISAI " ."\r\n";
        //$strSQL .="                   GROUP BY CMN_NO, GYOUSYA_CD) CNTTBL" ."\r\n";
        //$strSQL .="ON CNTTBL.CMN_NO = KASO.CMN_NO  " ."\r\n";
        //$strSQL .="AND CNTTBL.GYOUSYA_CD = KASO.GYOUSYA_CD" ."\r\n";
        //2006/03/23 UPDATE End
        $strSQL .= "WHERE     KASO.CMN_NO = '@CMNNO'" . "\r\n";
        //2006/04/10 INSERT Start
        $strSQL .= " AND      KASO.KASOUNO = '@KASOUNO'" . "\r\n";
        //2006/04/10 INSERT End
        $strSQL .= "AND       KASO.GYOUSYA_CD IS NOT NULL" . "\r\n";
        //2006/04/08 UPDATE Start
        $strSQL .= "AND       NVL(KASO.GYOUSYA_CD,' ') <> '88888'" . "\r\n";
        //2006/04/08 UPDATE End
        $strSQL .= "ORDER BY  KASO.GYOUSYA_CD" . "\r\n";
        //2006/04/20 INSERT Start
        $strSQL .= ",         KASO.EDA_NO" . "\r\n";
        //2006/04/20 INSERT End

        $strSQL = str_replace("@CMNNO", $strChumon, $strSQL);
        //2006/04/10 INSERT Start
        $strSQL = str_replace("@KASOUNO", $strKasou, $strSQL);
        //2006/04/10 INSERT End
        $strSQL = str_replace("@TODAY", date('Y/m/d'), $strSQL);

        return $strSQL;
    }

    /**********************************************************************
     *処 理 名：担当者を抽出
     *関 数 名：fncHPRINTTANTOSql
     *引    数：無し
     *戻 り 値：SQL
     *処理説明：担当者を抽出
     **********************************************************************/
    public function fncHPRINTTANTOSql()
    {
        $strSQL = "";
        $strSQL .= "SELECT TANTO_SEI FROM HPRINTTANTO";
        return $strSQL;
    }

    //20131205 LuChao 既存バグ修正 Start

    // public function checkWKClearSql()
    // {
    // $strSQL = "";
    // $strSQL .= "SELECT CMN_NO FROM WK_HKASOUMEISAI";
    // $strSQL .= " WHERE  UPD_SYA_CD IS NOT NULL";
    // $strSQL .= " OR     UPD_PRG_ID IS NOT NULL";
    // $strSQL .= " OR     UPD_CLT_NM IS NOT NULL";
    //
    // return $strSQL;
    // }

    //20131205 LuChao 既存バグ修正 End

    //20131205 LuChao 既存バグ修正 Start
    public function deleteWKClearSql()
    {
        $strSql = "";

        $UPDUSER = $this->GS_LOGINUSER['strUserID'];

        $strSql = "DELETE FROM WK_HKASOUMEISAI_APPEND" . "\r\n";

        $strSql .= "WHERE UPD_SYA_CD = '@UPDUSER'";

        $strSql = str_replace("@UPDUSER", $UPDUSER, $strSql);

        return $strSql;
    }

    //20131205 LuChao 既存バグ修正 End

    //20131210 LuChao 既存バグ修正 Start
    public function FncDeleteWKOtherSQL($postData)
    {
        $strSQL = "";
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $strSQL = "DELETE  FROM WK_HKASOUMEISAI_APPEND";
        //$strSQL .= "  WHERE  KASOUNO <> '@KASOUNO' ";
        $strSQL .= "  WHERE CMN_NO <> '@CMN_NO'";
        $strSQL .= "  AND UPD_SYA_CD = '@UPDUSER'";

        $strSQL = str_replace("@CMN_NO", $postData, $strSQL);
        //$strSQL = str_replace("@KASOUNO", $postData['KASOUNO'], $strSQL);
        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);

        return $strSQL;
    }

    //20131210 LuChao 既存バグ修正 End

    //20180521 YIN INS S
    function fncCheckHKASOUMEISAI_PRINTLOGSql($strChumon, $strKasou)
    {
        $strSQL = "";
        $strSQL .= "SELECT KASOUNO" . "\r\n";
        $strSQL .= "  FROM   HKASOUMEISAI_PRINTLOG" . "\r\n";
        $strSQL .= "  WHERE  CMN_NO = '";
        $strSQL .= $strChumon;
        $strSQL .= "' AND KASOUNO ='";
        $strSQL .= $strKasou;
        $strSQL .= "'" . "\r\n";
        return $strSQL;
    }
    function fncInsertHKASOUMEISAI_PRINTLOGSql($strChumon, $strKasou)
    {
        $CRTUSER = $this->GS_LOGINUSER['strUserID'];

        $strSQL = "";
        $strSQL .= "INSERT INTO HKASOUMEISAI_PRINTLOG" . "\r\n";
        $strSQL .= "           (CMN_NO" . "\r\n";
        $strSQL .= ",           KASOUNO" . "\r\n";
        $strSQL .= ",           CREATE_DATE" . "\r\n";
        $strSQL .= ",           CRE_SYA_CD)" . "\r\n";
        $strSQL .= " VALUES     " . "\r\n";
        $strSQL .= "(           '@CMNNO'" . "\r\n";
        $strSQL .= ",           '@KASOUNO'" . "\r\n";
        $strSQL .= ",           SYSDATE" . "\r\n";
        $strSQL .= ",           '@CRTUSER'" . "\r\n";
        $strSQL .= ")" . "\r\n";

        $strSQL = str_replace("@CMNNO", $strChumon, $strSQL);
        $strSQL = str_replace("@KASOUNO", $strKasou, $strSQL);
        $strSQL = str_replace("@CRTUSER", $CRTUSER, $strSQL);

        return $strSQL;
    }

    public function fncCheckHKASOUMEISAI_PRINTLOG($strChumon, $strKasou)
    {
        return parent::select($this->fncCheckHKASOUMEISAI_PRINTLOGSql($strChumon, $strKasou));
    }
    public function fncInsertHKASOUMEISAI_PRINTLOG($strChumon, $strKasou)
    {
        return parent::insert($this->fncInsertHKASOUMEISAI_PRINTLOGSql($strChumon, $strKasou));
    }
    //20180521 YIN INS E

    //*************************************
    //*************************************
    // * 公開メソッド
    //*************************************
    // public function delete($postData)
    // {

    //     return parent::delete($this->fncDeleteWK_KASOUMEISAI());

    // }

    public function insert($postData)
    {
        return parent::insert($this->fncInsertWK_KASOUMEISAI($postData));
    }

    public function select($postData)
    {
        return parent::select($this->fncSelHkasouSql($postData));
    }

    public function fncDeleteKasou($postData, $table = '')
    {
        return parent::Do_Execute($this->fncDeleteKasouSql($postData, $table));
    }

    public function fncM41E12Check($postData)
    {
        return parent::select($this->fncM41E12CheckSql($postData));
    }

    public function fncSelectM41E12Chk($postData)
    {
        return parent::Fill($this->fncSelectM41E12ChkSql($postData));
    }

    public function fncKasouTblCheck($postData)
    {
        return parent::select($this->fncKasouTblCheckSql($postData));
    }

    public function fncUpdSaiban($strNengetu, $ajax = TRUE)
    {
        if ($ajax) {
            return parent::select($this->fncUpdSaibanSql($strNengetu));
        } else {
            return parent::Fill($this->fncUpdSaibanSql($strNengetu));
        }

    }

    public function fncUpdSaibanUpdate($BANGO, $UPD_TIME, $strNengetu)
    {
        return parent::Do_Execute($this->fncUpdSaibanUpdateSql($BANGO, $UPD_TIME, $strNengetu));
    }

    public function fncUpdSaibanInsert($UPD_TIME, $strNengetu, $ajax = TRUE)
    {
        if ($ajax) {
            return parent::insert($this->fncUpdSaibanInsertSql($UPD_TIME, $strNengetu));
        } else {
            return parent::Do_Execute($this->fncUpdSaibanInsertSql($UPD_TIME, $strNengetu));
        }

    }

    public function fncUpdSaibanDelWK($postData)
    {
        return parent::Do_Execute($this->fncUpdSaibanDelWKSql($postData));
    }

    public function fncCopyKasouInsert($postData)
    {
        return parent::Do_Execute($this->fncCopyKasouInsertSql($postData));
    }

    public function fncSearchSelect($postData, $NENGETU)
    {
        return parent::select($this->fncSearchSelectSql($postData, $NENGETU));
    }

    public function fncMoneyM41E12($postData, $strFzkKbn)
    {
        return parent::select($this->fncMoneyM41E12Sql($postData, $strFzkKbn));
    }

    public function fncMoneyKasouMeisai($postData, $strFzkKbn)
    {
        return parent::select($this->fncMoneyKasouMeisaiSql($postData, $strFzkKbn));
    }

    public function fncCustomerSelect($postData, $blnOutput, $ajax = TRUE)
    {
        if ($ajax) {
            return parent::select($this->fncCustomerSelectSql($postData, $blnOutput));
        } else {
            return parent::Fill($this->fncCustomerSelectSql($postData, $blnOutput));
        }

    }

    public function fncSelectFromHkasoumeisai($table, $postData, $strKasouNo = '')
    {
        return parent::Fill($this->fncSelectFromHkasoumeisaiSql($table, $postData, $strKasouNo));
    }

    public function fncInsertNoMeisaiIns($postData, $strfirstKasouNo)
    {
        return parent::Do_Execute($this->fncInsertNoMeisaiInsSql($postData, $strfirstKasouNo));
    }

    public function fncDeleteHKASOUMEISAI($strTableNM, $strChumon)
    {
        return parent::Do_Execute($this->fncDeleteHKASOUMEISAISql($strTableNM, $strChumon));
    }

    public function fncInsertHKASOUMEISAI($strChumon)
    {
        return parent::Do_Execute($this->fncInsertHKASOUMEISAISql($strChumon));
    }

    public function fncUpdateKasouNOOnly($strOldKasouNo, $strNewKasouNo, $strChumon)
    {
        return parent::Do_Execute($this->fncUpdateKasouNOOnlySql($strOldKasouNo, $strNewKasouNo, $strChumon));
    }

    public function fncUpdateHaisouSiji($strHaisouSiji, $strChumon, $strKasou)
    {
        return parent::Do_Execute($this->fncUpdateHaisouSijiSql($strHaisouSiji, $strChumon, $strKasou));
    }

    public function deleteWKClear()
    {
        return parent::delete($this->deleteWKClearSql());
    }

    public function fncDeleteWK_KASOUMEISAI()
    {
        return parent::delete($this->fncDeleteWKKASOUMEISAISql());
    }

    public function fncInsertWK_KASOUMEISAI($Array_Insert)
    {
        return parent::insert($this->fncInsertWKKASOUMEISAISql($Array_Insert));
    }

    public function fncKasouMPrintSel($strChumon, $strKasou)
    {
        //			return parent::Fill($this -> fncKasouMPrintSelSql($strChumon, $strKasou));
        return parent::select($this->fncKasouMPrintSelSql($strChumon, $strKasou));
    }

    public function fncGaichuPrintSelect($strChumon, $strKasou)
    {
        //$aa = $this -> fncGaichuPrintSelectSql($strChumon, $strKasou);
        //			return parent::Fill($this -> fncGaichuPrintSelectSql($strChumon, $strKasou));
        return parent::select($this->fncGaichuPrintSelectSql($strChumon, $strKasou));
    }

    public function fncHPRINTTANTO()
    {
        //			return parent::Fill($this -> fncHPRINTTANTOSql());
        return parent::select($this->fncHPRINTTANTOSql());
    }

    //20131210 LuChao 既存バグ修正 Start
    public function FncDeleteWKOther($postData)
    {
        return parent::delete($this->FncDeleteWKOtherSQL($postData));
    }

    //20131210 LuChao 既存バグ修正 End

    //20131205 LuChao 既存バグ修正 Start
    // public function checkWKClear()
    // {
    // return parent::select($this -> checkWKClearSql());
    // }
    //20131205 LuChao 既存バグ修正 End

    //20140924 zhangxl insert start
    function fncM27A02Sql($CMN_NO)
    {
        $strSQL = "";
        $strSQL = "SELECT ";
        $strSQL .= "  M27A02.HIKI_ODR_DT HIKI_ODR_DT, ";
        //20141008 zhangxl 修正 start
        // $strSQL .= "  M27A02.NYUKO_YYMM|| M27A02.NYUKO_DD PRO_DT, ";
        $strSQL .= "  M27A02.OFF_YYMM|| M27A02.OFF_DD PRO_DT, ";
        //20141008 zhangxl 修正 end
        $strSQL .= "  M27A02.KUMI_YYMM||M27A02.KUMI_WEEK||'W' PRO_WEEK, ";
        $strSQL .= "  M27A02.ODR_NO ODR_NO, ";
        //20141008 zhangxl 追加 start
        $strSQL .= "  M27A02.TENJI_WARI_DT TENJI_WARI_DT, ";
        $strSQL .= "  HBUSYO.BUSYO_RYKNM BUSYO_RYKNM, ";
        $strSQL .= "  M27A02.JUCHU_KB JUCHU_KB ";
        //20141008 zhangxl 追加 end
        $strSQL .= "FROM ";
        $strSQL .= "  M27A02 ";
        //20141008 zhangxl 追加 start
        $strSQL .= "LEFT JOIN ";
        $strSQL .= "  HBUSYO ";
        $strSQL .= "ON ";
        $strSQL .= "  M27A02.TENJI_KYO_CD = HBUSYO.BUSYO_CD ";
        //20141008 zhangxl 追加 end
        $strSQL .= "WHERE ";
        $strSQL .= "  M27A02.JUCHU_NO = '" . $CMN_NO . "' ";

        return $strSQL;
    }

    public function fncM27A02($CMN_NO)
    {
        return parent::select($this->fncM27A02Sql($CMN_NO));
    }

    //20140924 zhangxl insert end

    //20141009 zhangxl update start

    function fncUPDKASOSql($strChumon, $strKasou, $strCarNO, $strSyadaiKata)
    {
        $strSQL = "";
        $strSQL = "UPDATE ";
        $strSQL .= "  HKASOUMEISAI ";
        $strSQL .= "SET ";
        $strSQL .= "  CAR_NO='@CAR_NO', ";
        $strSQL .= "  SYADAIKATA='@SYADAIKATA' ";
        $strSQL .= "WHERE ";
        $strSQL .= "  CMN_NO='@CMNNO' ";
        $strSQL .= "AND ";
        $strSQL .= "  NVL(TRIM(KASOUNO),'@KASOUNO') = '@KASOUNO' ";

        $strSQL = str_replace("@CAR_NO", $strCarNO, $strSQL);
        $strSQL = str_replace("@SYADAIKATA", $strSyadaiKata, $strSQL);
        $strSQL = str_replace("@CMNNO", $strChumon, $strSQL);
        $strSQL = str_replace("@KASOUNO", ($strKasou == "") ? " " : $strKasou, $strSQL);

        return $strSQL;
    }

    public function fncUPDKASO($strChumon, $strKasou, $strCarNO, $strSyadaiKata)
    {
        return parent::update($this->fncUPDKASOSql($strChumon, $strKasou, $strCarNO, $strSyadaiKata));
    }

    //20141009 zhangxl update end

    // 20131004 kamei add Start
    /*************************************
     * トランザクション中に複数回SQL実行が存在する場合は、
     * 以下の処理をの用に変更してください。
     *************************************/

    /*************************************
     * 処理名	：関数終了時処理
     * 関数名	：finally
     * 引数		：無し
     * 戻り値		：無し
     * 処理説明	：register_shutdown_functionで呼び出された関数の終了後に実行する処理
     *************************************/
    function finally()
    {
        if (isset($this->Sel_Array)) {
            // echo $this -> Sel_Array['Pra_info'] . "<br>";
            if ($this->Sel_Array['Pra_sta'] != false) {
                oci_free_statement($this->Sel_Array['Pra_info']);
            }
        }

        if (isset($this->conn_orl)) {
            if ($this->conn_orl['conn_sta'] != false) {
                oci_close($this->conn_orl['conn_orl']);
            }

        }

        unset($this->Sel_Array);
        unset($this->conn_orl);
    }

    // 20131004 kamei add end
}
