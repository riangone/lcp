<?php

/**
 * 説明：
 *
 *
 * @author yangyang
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                                				 Feature/Bug     内容                    担当
 *YYYYMMDD             #ID                       XXXXXX
 * 20161107                                   #2597                                依頼                 yangyang
 * --------------------------------------------------------------------------------------------
 */

// 共通クラスの読込み
namespace App\Model\R4\R4G;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmListPrint extends ClsComDb
{
    // 解放が必要な変数をメンバーに設定
    protected $conn_orl = "";
    protected $Sel_Array = "";

    /* 20161107 yangyang del s */
    // function fncSelectFromHkasoumeisaiSql($table, $conditions, $strKasouNo)
    // {
    // $strSQL = "";
    // $UPDUSER = $this -> GS_LOGINUSER['strUserID'];
    // $CMN_NO = rtrim($conditions['CMN_NO']);
    // $KASOUNO = rtrim($strKasouNo);
//
    // $strSQL = " SELECT   CMN_NO" . "\r\n";
    // $strSQL .= " ,        KASOUNO" . "\r\n";
    // $strSQL .= " FROM     " . $table . "\r\n";
    // $strSQL .= " WHERE    CMN_NO = '@CMN_NO'" . "\r\n";
//
    // if ($KASOUNO != "")
    // {
    // $strSQL .= " AND    KASOUNO = '@KASOUNO'" . "\r\n";
    // }
    // if ($table == "WK_HKASOUMEISAI_APPEND")
    // {
    // $strSQL .= " AND    UPD_SYA_CD = '@UPDUSER'" . "\r\n";
    // }
    // $strSQL .= " GROUP BY CMN_NO, KASOUNO" . "\r\n";
//
    // $strSQL = str_replace("@CMN_NO", $CMN_NO, $strSQL);
    // $strSQL = str_replace("@KASOUNO", $KASOUNO, $strSQL);
    // $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
//
    // return $strSQL;
    // }
    /* 20161107 yangyang del e */

    function fncCustomerSelectSql($conditions, $blnOutput)
    {
        $strSQL = "";
        $CMN_NO = $conditions['CMN_NO'];
        $KASOUNO = $conditions['KASOU_NO'];
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];

        $strSQL = "SELECT   KASO.GYOUSYA_CD " . "\r\n";
        $strSQL .= ",        KASO.GYOUSYA_NM " . "\r\n";
        $strSQL .= ",        SUM(NVL(KASO.GAICYU_ZITU,0)) GAICYU_ZITU " . "\r\n";

        if ($blnOutput) {
            $strSQL .= "FROM     WK_HKASOUMEISAI_APPEND KASO  " . "\r\n";
        } else {
            $strSQL .= "FROM     HKASOUMEISAI KASO " . "\r\n";
        }

        $strSQL .= "WHERE    KASO.CMN_NO = '@CMN_NO'" . "\r\n";
        $strSQL .= "   AND      KASO.KASOUNO = '@KASOUNO'" . "\r\n";
        $strSQL .= "   AND      KASO.GYOUSYA_CD IS NOT NULL " . "\r\n";

        if (!$blnOutput) {
            $strSQL .= "  AND      NVL(KASO.GYOUSYA_CD,' ') <> '88888'" . "\r\n";
        } else {
            $strSQL .= " AND    UPD_SYA_CD = '@UPDUSER'" . "\r\n";
        }

        $strSQL .= "  GROUP BY KASO.GYOUSYA_CD ";
        $strSQL .= ",        KASO.GYOUSYA_NM ";

        $strSQL = str_replace("@CMN_NO", $CMN_NO, $strSQL);
        $strSQL = str_replace("@KASOUNO", $KASOUNO, $strSQL);
        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);

        return $strSQL;
    }

    function fncSearchSelectSql($conditions, $NENGETU)
    {
        $strSQL = "";

        $UPDUSER = $this->GS_LOGINUSER['strUserID'];

        $strSQL = "SELECT    CMN.CMN_NO" . "\r\n";
        //5269
        $strSQL .= ",  (KYK_OKY.INP_SIM1 || ' ' || KYK_OKY.INP_SIM2) KEIYAKUSYA" . "\r\n";
        $strSQL .= ",  (SIY_OKY.INP_SIM1 || ' ' || SIY_OKY.INP_SIM2) SIYOSYA" . "\r\n";
        //5274
        $strSQL .= ",  SIY_OKY.CSRKNANM" . "\r\n";
        $strSQL .= ",  CMN.KYOTN_CD" . "\r\n";
        $strSQL .= ",  BUS.KYOTN_RKN BUSYOMEI" . "\r\n";
        $strSQL .= ",  CMN.HNB_TAN_EMP_NO" . "\r\n";
        $strSQL .= ",  (SYA.SYAIN_KNJ_SEI || '  ' || SYA.SYAIN_KNJ_MEI) SYAIN" . "\r\n";
        $strSQL .= ",  CMN.HNB_KTN_CD" . "\r\n";
        $strSQL .= ",  HAN.KYOTN_RKN HANBAITEN" . "\r\n";
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
        $strSQL .= ",   KASO_OUT.UPD_DATE" . "\r\n";
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

        /* 20161107 yangyang upd s */
        // $strSQL .= "   LEFT JOIN (SELECT DISTINCT CMN_NO, KASOUNO FROM WK_HKASOUMEISAI_APPEND WHERE UPD_SYA_CD = '@UPDUSER') KASO" . "\r\n";
        $strSQL .= "   LEFT JOIN (SELECT DISTINCT CMN_NO, KASOUNO FROM HKASOUMEISAI) KASO" . "\r\n";
        /* 20161107 yangyang upd e */
        $strSQL .= "   ON        KASO.CMN_NO = CMN.CMN_NO" . "\r\n";
        $strSQL .= "  LEFT JOIN(    SELECT CMN_NO,TO_CHAR(MAX(UPD_DATE),'YYYY/MM/DD') UPD_DATE    FROM HKASOUMEISAI    GROUP BY CMN_NO) KASO_OUT ON KASO_OUT.CMN_NO = CMN.CMN_NO" . "\r\n";

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

        $strSQL .= " WHERE ";
        if ($conditions['flag'] == 2) {
            $strSQL .= "CMN.CMN_NO = '";
            $strSQL .= $conditions['CMN_NO'];
            $strSQL .= "'" . "\r\n";
        } else if ($conditions['flag'] == 1) {
            //				$strSQL .= "CMN.SRY_URG_DT BETWEEN '";
            $strSQL .= "REPLACE(KASO_OUT.UPD_DATE,'/','') BETWEEN '";
            $strSQL .= $conditions['startDate'];
            $strSQL .= "' AND '";
            $strSQL .= $conditions['endDate'];
            $strSQL .= "'" . "\r\n";
        }

        $strSQL .= " ORDER BY CMN.CMN_NO, KASO.KASOUNO";

        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);

        //			$this->log($strSQL);
        return $strSQL;
    }

    /* 20161107 yangyang del s */
    // function fncUpdSaibanInsertSql($UPD_TIME, $strNengetu)
    // {
    // $strSQL = "";
    // $strSQL .= "INSERT INTO HSAIBAN";
    // $strSQL .= "(      SAIBAN_CD";
    // $strSQL .= " ,      NENGETU";
    // $strSQL .= ",      BANGO";
    // $strSQL .= ",      UPD_DATE";
    // $strSQL .= ",      CREATE_DATE )";
    // $strSQL .= " VALUES (";
    // $strSQL .= "'1'";
    // $strSQL .= ", ";
    // $strSQL .= "'";
    // $strSQL .= $strNengetu;
    // $strSQL .= "'";
    // $strSQL .= ",'1'";
    // $strSQL .= ", ";
    // $strSQL .= $UPD_TIME;
    // $strSQL .= ", ";
    // $strSQL .= $UPD_TIME;
    // $strSQL .= ")";
//
    // return $strSQL;
    // }
    /* 20161107 yangyang del e */

    /* 20161107 yangyang del s */
    // function fncUpdSaibanUpdateSql($BANGO, $UPD_TIME, $strNengetu)
    // {
    // $strSQL = "";
    // $strSQL .= "UPDATE HSAIBAN";
    // $strSQL .= "   SET BANGO = ";
    // $strSQL .= $BANGO;
    // $strSQL .= " , UPD_DATE = ";
    // $strSQL .= $UPD_TIME;
    // $strSQL .= "   WHERE SAIBAN_CD = '1'";
    // $strSQL .= "   AND NENGETU = '";
    // $strSQL .= $strNengetu;
    // $strSQL .= "'";
//
    // return $strSQL;
    // }
    /* 20161107 yangyang del e */

    /* 20161107 yangyang del s */
    // function fncUpdSaibanSql($strNengetu)
    // {
    // $SAIBAN_CD = "1";
//
    // $strSQL = "";
    // $strSQL .= "SELECT NVL(BANGO,0) + 1 BANGO";
    // $strSQL .= "  FROM   HSAIBAN ";
    // $strSQL .= "WHERE  SAIBAN_CD = '";
    // $strSQL .= $SAIBAN_CD;
    // $strSQL .= "' AND NENGETU = '";
    // $strSQL .= $strNengetu;
    // $strSQL .= "'";
//
    // return $strSQL;
    // }
    /* 20161107 yangyang del e */

    function fncKasouTblCheckSql($conditions)
    {
        $strSQL = "";
        $strSQL .= "SELECT CMN_NO";
        $strSQL .= ",       KASOUNO";
        $strSQL .= ",       MEMO";
        $strSQL .= "  FROM   HKASOUMEISAI";
        $strSQL .= "  WHERE  CMN_NO = '";
        $strSQL .= $conditions['CMN_NO'];
        $strSQL .= "'";

        if ($conditions['KASOU_NO'] != NULL && $conditions['KASOU_NO'] != '') {
            $strSQL .= "  AND         KASOUNO = '";
            $strSQL .= $conditions['KASOU_NO'];
            $strSQL .= "'";
        } else {
            return $strSQL;
        }
        $strSQL .= "GROUP BY CMN_NO,KASOUNO,MEMO";

        return $strSQL;
    }

    /* 20161107 yangyang del s */
    // function fncSelectM41E12ChkSql($conditions)
    // {
//
    // $strSQL = "";
    // $strSQL .= "SELECT CMN_NO";
    // $strSQL .= "  FROM   M41E12";
    // $strSQL .= "  WHERE  CMN_NO = '";
    // $strSQL .= $conditions['CMN_NO'];
    // $strSQL .= "'";
//
    // $this->log($strSQL);
    // return $strSQL;
    // }
    /* 20161107 yangyang del e*/

    /* 20161107 yangyang del s */
    // function fncDeleteWKKASOUMEISAISql()
    // {
    // $strSQL = "";
//
    // $UPDUSER = $this -> GS_LOGINUSER['strUserID'];
//
    // $strSQL .= "DELETE FROM WK_HKASOUMEISAI_APPEND A" . "\r\n";
//
    // $strSQL .= " WHERE  EXISTS" . "\r\n";
    // $strSQL .= "        (SELECT CMN_NO" . "\r\n";
    // $strSQL .= "         FROM   HKASOUMEISAI B" . "\r\n";
    // $strSQL .= "         WHERE  A.CMN_NO = B.CMN_NO" . "\r\n";
    // $strSQL .= "         AND    A.EDA_NO = B.EDA_NO" . "\r\n";
    // $strSQL .= "         AND    A.KASOUNO = B.KASOUNO" . "\r\n";
    // $strSQL .= "         AND    A.FUZOKUHINKBN = B.FUZOKUHINKBN)" . "\r\n";
//
    // $strSQL .= " AND    A.UPD_SYA_CD = '@UPDUSER'" . "\r\n";
    // $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
//
    // return $strSQL;
//
    // }
//
//
    // function fncInsertWKKASOUMEISAISql($Array_Insert)
    // {
    // $strSQL = "";
//
    // $UPDUSER = $this -> GS_LOGINUSER['strUserID'];
    // $UPDAPP = "List";
    // $UPDCLTNM = $this -> GS_LOGINUSER['strClientNM'];
//
    // $strSQL .= "INSERT INTO WK_HKASOUMEISAI_APPEND" . "\r\n";
//
    // $strSQL .= "           (CMN_NO" . "\r\n";
    // $strSQL .= ",           SYADAIKATA" . "\r\n";
    // $strSQL .= ",           CAR_NO" . "\r\n";
    // $strSQL .= ",           HANBAISYASYU" . "\r\n";
    // $strSQL .= ",           TOIAWASENM" . "\r\n";
    // $strSQL .= ",           SYASYU_NM" . "\r\n";
    // $strSQL .= ",           KASOUNO" . "\r\n";
    // $strSQL .= ",           MEMO" . "\r\n";
    // $strSQL .= ",           ZEIRITU" . "\r\n";
    // $strSQL .= ",           FUZOKUHINKBN" . "\r\n";
    // $strSQL .= ",           DELKBN" . "\r\n";
    // $strSQL .= ",           UPD_DATE" . "\r\n";
    // $strSQL .= ",           CREATE_DATE" . "\r\n";
    // $strSQL .= ",           EDA_NO" . "\r\n";
    // $strSQL .= ",           MEDALCD" . "\r\n";
    // $strSQL .= ",           BUHINNM" . "\r\n";
    // $strSQL .= ",           BIKOU" . "\r\n";
    // $strSQL .= ",           TEIKA" . "\r\n";
    // $strSQL .= ",           SUURYOU" . "\r\n";
    // $strSQL .= ",           BUHIN_SYANAI_GEN_RITU" . "\r\n";
    // $strSQL .= ",           BUHIN_SYANAI_GEN" . "\r\n";
    // $strSQL .= ",           BUHIN_SYANAI_ZITU_RITU" . "\r\n";
    // $strSQL .= ",           BUHIN_SYANAI_ZITU" . "\r\n";
    // $strSQL .= ",           GYOUSYA_CD" . "\r\n";
    // $strSQL .= ",           GYOUSYA_NM" . "\r\n";
    // $strSQL .= ",           KAZEIKBN" . "\r\n";
    // $strSQL .= ",           GAICYU_GEN_RITU" . "\r\n";
    // $strSQL .= ",           GAICYU_GEN" . "\r\n";
    // $strSQL .= ",           GAICYU_ZITU_RITU" . "\r\n";
    // $strSQL .= ",           GAICYU_ZITU" . "\r\n";
    // $strSQL .= ",           UPD_SYA_CD )" . "\r\n";
//
    // $strSQL .= " SELECT " . "\r\n";
    // $strSQL .= "            CMN_NO" . "\r\n";
    // $strSQL .= ",           SYADAIKATA" . "\r\n";
    // $strSQL .= ",           CAR_NO" . "\r\n";
    // $strSQL .= ",           HANBAISYASYU" . "\r\n";
    // $strSQL .= ",           TOIAWASENM" . "\r\n";
    // $strSQL .= ",           SYASYU_NM" . "\r\n";
    // $strSQL .= ",           KASOUNO" . "\r\n";
    // $strSQL .= ",           MEMO" . "\r\n";
    // $strSQL .= ",           ZEIRITU" . "\r\n";
    // $strSQL .= ",           FUZOKUHINKBN" . "\r\n";
    // $strSQL .= ",           DELKBN" . "\r\n";
    // $strSQL .= ",           UPD_DATE" . "\r\n";
    // $strSQL .= ",           CREATE_DATE" . "\r\n";
    // $strSQL .= ",           EDA_NO" . "\r\n";
    // $strSQL .= ",           MEDALCD" . "\r\n";
    // $strSQL .= ",           BUHINNM" . "\r\n";
    // $strSQL .= ",           BIKOU" . "\r\n";
    // $strSQL .= ",           TEIKA" . "\r\n";
    // $strSQL .= ",           SUURYOU" . "\r\n";
    // $strSQL .= ",           BUHIN_SYANAI_GEN_RITU" . "\r\n";
    // $strSQL .= ",           BUHIN_SYANAI_GEN" . "\r\n";
    // $strSQL .= ",           BUHIN_SYANAI_ZITU_RITU" . "\r\n";
    // $strSQL .= ",           BUHIN_SYANAI_ZITU" . "\r\n";
    // $strSQL .= ",           GYOUSYA_CD" . "\r\n";
    // $strSQL .= ",           GYOUSYA_NM" . "\r\n";
    // $strSQL .= ",           KAZEIKBN" . "\r\n";
    // $strSQL .= ",           GAICYU_GEN_RITU" . "\r\n";
    // $strSQL .= ",           GAICYU_GEN" . "\r\n";
    // $strSQL .= ",           GAICYU_ZITU_RITU" . "\r\n";
    // $strSQL .= ",           GAICYU_ZITU" . "\r\n";
//
    // $strSQL .= ",           '@UPDUSER' UPD_SYA_CD" . "\r\n";
//
    // $strSQL .= " FROM       HKASOUMEISAI" . "\r\n";
    // $strSQL .= " WHERE      CMN_NO  IN (" . "\r\n";
    // $strSQL .= "   SELECT CMN_NO FROM M41E10" . "\r\n";
//
    // $strSQL .= " WHERE ";
    // if($Array_Insert['flag'] == 2)
    // {
    // $strSQL .= "CMN_NO = '";
    // $strSQL .= $Array_Insert['CMN_NO'];
    // $strSQL .= "'" . "\r\n";
    // }
    // else if($Array_Insert['flag'] == 1)
    // {
    // $strSQL .= "SRY_URG_DT BETWEEN '";
    // $strSQL .= $Array_Insert['startDate'];
    // $strSQL .= "' AND '";
    // $strSQL .= $Array_Insert['endDate'];
    // $strSQL .= "'" . "\r\n";
    // }
//
    // $strSQL .= ")";
//
    // $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
//
    // return $strSQL;
    // }
    /* 20161107 yangyang del e */

    /* 20161107 yangyang add s */
    function fncSelectCheckCMNNOSql($Array_Insert)
    {
        $strSQL = "";
        $strSQL .= " SELECT " . "\r\n";
        $strSQL .= " CMN_NO" . "\r\n";
        $strSQL .= " FROM  HKASOUMEISAI" . "\r\n";
        $strSQL .= " WHERE  CMN_NO  IN (" . "\r\n";
        $strSQL .= " SELECT CMN_NO FROM M41E10" . "\r\n";

        $strSQL .= " WHERE ";
        if ($Array_Insert['flag'] == 2) {
            $strSQL .= "CMN_NO = '";
            $strSQL .= $Array_Insert['CMN_NO'];
            $strSQL .= "'" . "\r\n";
        } else if ($Array_Insert['flag'] == 1) {
            $strSQL .= "SRY_URG_DT BETWEEN '";
            $strSQL .= $Array_Insert['startDate'];
            $strSQL .= "' AND '";
            $strSQL .= $Array_Insert['endDate'];
            $strSQL .= "'" . "\r\n";
        }

        $strSQL .= ")";

        //			$this->log($strSQL);
        return $strSQL;
    }
    /* 20161107 yangyang add e */

    /* 20161107 yangyang del s */
    // function fncInsertNoMeisaiInsSql($postData, $strfirstKasouNo)
    // {
    // $this -> ClsComFnc = new ClsComFnc();
    // $strSQL = "";
//
    // $UPDUSER = $this -> GS_LOGINUSER['strUserID'];
//
    // $strSQL .= "INSERT INTO WK_HKASOUMEISAI_APPEND" . "\r\n";
//
    // $strSQL .= "           (CMN_NO" . "\r\n";
    // $strSQL .= ",           EDA_NO" . "\r\n";
    // $strSQL .= ",           SYADAIKATA" . "\r\n";
    // $strSQL .= ",           CAR_NO" . "\r\n";
    // $strSQL .= ",           HANBAISYASYU" . "\r\n";
    // $strSQL .= ",           TOIAWASENM" . "\r\n";
    // $strSQL .= ",           SYASYU_NM" . "\r\n";
    // $strSQL .= ",           KASOUNO" . "\r\n";
    // $strSQL .= ",           MEMO" . "\r\n";
    // $strSQL .= ",           FUZOKUHINKBN" . "\r\n";
    // $strSQL .= ",           UPD_DATE" . "\r\n";
    // $strSQL .= ",           CREATE_DATE" . "\r\n";
    // $strSQL .= ",           UPD_SYA_CD)" . "\r\n";
    // $strSQL .= " VALUES     " . "\r\n";
    // $strSQL .= "(           '@CMN_NO'" . "\r\n";
    // $strSQL .= ",           '1'" . "\r\n";
    // $strSQL .= ",           '@SYADAIKATA'" . "\r\n";
    // $strSQL .= ",           '@CAR_NO'" . "\r\n";
    // $strSQL .= ",           '@HANBAISYASYU'" . "\r\n";
    // $strSQL .= ",           '@TOIAWASENM'" . "\r\n";
    // $strSQL .= ",           '@SYASYU_NM'" . "\r\n";
    // $strSQL .= ",           '@KASOUNO'" . "\r\n";
    // $strSQL .= ",           '@MEMO'" . "\r\n";
    // $strSQL .= ",           '@FUZOKUHINKBN'" . "\r\n";
    // $strSQL .= ",           @UPD_DATE" . "\r\n";
    // $strSQL .= ",           @CREATE_DATE" . "\r\n";
    // $strSQL .= ",           '@UPDUSER'" . "\r\n";
    // $strSQL .= ")" . "\r\n";
//
    // $strSQL = str_replace("@CMN_NO", $postData['CMN_NO'], $strSQL);
    // $strSQL = str_replace("@SYADAIKATA", $postData['SDI_KAT'], $strSQL);
    // $strSQL = str_replace("@CAR_NO", $postData['CAR_NO'], $strSQL);
    // $strSQL = str_replace("@HANBAISYASYU", $postData['HANBAISYASYU'], $strSQL);
    // $strSQL = str_replace("@TOIAWASENM", rtrim(substr($postData['HANBAISYASYU'],0,5)).rtrim(substr($postData['HANBAISYASYU'],7,1)), $strSQL);
    // $strSQL = str_replace("@SYASYU_NM", $postData['BASEH_KN'], $strSQL);
    // $strSQL = str_replace("@KASOUNO", $strfirstKasouNo, $strSQL);
    // $strSQL = str_replace("@MEMO", $postData['MEMO'], $strSQL);
    // $strSQL = str_replace("@FUZOKUHINKBN", " ", $strSQL);
    // $strSQL = str_replace("@UPD_DATE", $this -> ClsComFnc -> FncSqlDate($this -> ClsComFnc -> FncGetSysDate("Y/m/d H:i:s")), $strSQL);
    // $strSQL = str_replace("@CREATE_DATE", $this -> ClsComFnc -> FncSqlDate($this -> ClsComFnc -> FncGetSysDate("Y/m/d H:i:s")), $strSQL);
//
    // $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
//
    // return $strSQL;
    // }
/* 20161107 yangyang del e */

    /* 20161107 yangyang del s */
    // function fncDeleteHKASOUMEISAISql($strTableNM, $strChumon)
    // {
    // $strSQL = "";
//
    // $UPDUSER = $this -> GS_LOGINUSER['strUserID'];
//
    // $strSQL .= " DELETE FROM  " . $strTableNM . "\r\n";
//
    // $strSQL .= "        WHERE CMN_NO = '@CMNNO'" . "\r\n";
//
    // if ($strTableNM == "WK_HKASOUMEISAI_APPEND")
    // {
    // $strSQL .= "        AND UPD_SYA_CD = '@UPDUSER'" . "\r\n";
    // }
//
    // $strSQL = str_replace('@CMNNO', $strChumon, $strSQL);
//
    // $strSQL = str_replace('@UPDUSER', $UPDUSER, $strSQL);
//
    // return $strSQL;
    // }
    /* 20161107 yangyang del e */

    /* 20161107 yangyang del s */
    // function fncInsertHKASOUMEISAISql($strChumon)
    // {
    // $UPDUSER = $this -> GS_LOGINUSER['strUserID'];
    // $UPDAPP = "List";
    // $UPDCLTNM = $this -> GS_LOGINUSER['strClientNM'];
//
    // $strSQL = "";
    // $strSQL .= "INSERT INTO HKASOUMEISAI" . "\r\n";
    // $strSQL .= "           (CMN_NO" . "\r\n";
    // $strSQL .= ",           SYADAIKATA" . "\r\n";
    // $strSQL .= ",           CAR_NO" . "\r\n";
    // $strSQL .= ",           HANBAISYASYU" . "\r\n";
    // $strSQL .= ",           TOIAWASENM" . "\r\n";
    // $strSQL .= ",           SYASYU_NM" . "\r\n";
    // $strSQL .= ",           KASOUNO" . "\r\n";
    // //車両配送指示をOPTION画面からList画面で入力するように変更したため
    // $strSQL.=",           MEMO" ."\r\n";
    // $strSQL .= ",           ZEIRITU" . "\r\n";
    // $strSQL .= ",           FUZOKUHINKBN" . "\r\n";
    // $strSQL .= ",           DELKBN" . "\r\n";
    // $strSQL .= ",           UPD_DATE" . "\r\n";
    // $strSQL .= ",           CREATE_DATE" . "\r\n";
    // $strSQL .= ",           EDA_NO" . "\r\n";
    // $strSQL .= ",           MEDALCD" . "\r\n";
    // $strSQL .= ",           BUHINNM" . "\r\n";
    // $strSQL .= ",           BIKOU" . "\r\n";
    // $strSQL .= ",           TEIKA" . "\r\n";
    // $strSQL .= ",           SUURYOU" . "\r\n";
    // $strSQL .= ",           BUHIN_SYANAI_GEN_RITU" . "\r\n";
    // $strSQL .= ",           BUHIN_SYANAI_GEN" . "\r\n";
    // $strSQL .= ",           BUHIN_SYANAI_ZITU_RITU" . "\r\n";
    // $strSQL .= ",           BUHIN_SYANAI_ZITU" . "\r\n";
    // $strSQL .= ",           GYOUSYA_CD" . "\r\n";
    // $strSQL .= ",           GYOUSYA_NM" . "\r\n";
    // $strSQL .= ",           KAZEIKBN" . "\r\n";
    // $strSQL .= ",           GAICYU_GEN_RITU" . "\r\n";
    // $strSQL .= ",           GAICYU_GEN" . "\r\n";
    // $strSQL .= ",           GAICYU_ZITU_RITU" . "\r\n";
    // $strSQL .= ",           GAICYU_ZITU" . "\r\n";
    // $strSQL .= ",           UPD_SYA_CD" . "\r\n";
    // $strSQL .= ",           UPD_PRG_ID" . "\r\n";
    // $strSQL .= ",           UPD_CLT_NM" . "\r\n";
    // $strSQL .= ")" . "\r\n";
    // $strSQL .= " SELECT     " . "\r\n";
    // $strSQL .= "            CMN_NO" . "\r\n";
    // $strSQL .= ",           SYADAIKATA" . "\r\n";
    // $strSQL .= ",           CAR_NO" . "\r\n";
    // $strSQL .= ",           HANBAISYASYU" . "\r\n";
    // $strSQL .= ",           TOIAWASENM" . "\r\n";
    // $strSQL .= ",           SYASYU_NM" . "\r\n";
    // $strSQL .= ",           KASOUNO" . "\r\n";
    // //車両配送指示をOPTION画面からList画面で入力するように変更したため
    // $strSQL.=",           MEMO" ."\r\n";
    // $strSQL .= ",           ZEIRITU" . "\r\n";
    // $strSQL .= ",           FUZOKUHINKBN" . "\r\n";
    // $strSQL .= ",           DELKBN" . "\r\n";
    // $strSQL .= ",           UPD_DATE" . "\r\n";
    // $strSQL .= ",           CREATE_DATE" . "\r\n";
    // $strSQL .= ",           EDA_NO" . "\r\n";
    // $strSQL .= ",           MEDALCD" . "\r\n";
    // $strSQL .= ",           BUHINNM" . "\r\n";
    // $strSQL .= ",           BIKOU" . "\r\n";
    // $strSQL .= ",           TEIKA" . "\r\n";
    // $strSQL .= ",           SUURYOU" . "\r\n";
    // $strSQL .= ",           BUHIN_SYANAI_GEN_RITU" . "\r\n";
    // $strSQL .= ",           BUHIN_SYANAI_GEN" . "\r\n";
    // $strSQL .= ",           BUHIN_SYANAI_ZITU_RITU" . "\r\n";
    // $strSQL .= ",           BUHIN_SYANAI_ZITU" . "\r\n";
    // $strSQL .= ",           GYOUSYA_CD" . "\r\n";
    // $strSQL .= ",           GYOUSYA_NM" . "\r\n";
    // $strSQL .= ",           KAZEIKBN" . "\r\n";
    // $strSQL .= ",           GAICYU_GEN_RITU" . "\r\n";
    // $strSQL .= ",           GAICYU_GEN" . "\r\n";
    // $strSQL .= ",           GAICYU_ZITU_RITU" . "\r\n";
    // $strSQL .= ",           GAICYU_ZITU" . "\r\n";
    // $strSQL .= ",           UPD_SYA_CD" . "\r\n";
    // $strSQL .= ",           '@UPDAPP'" . "\r\n";
    // $strSQL .= ",           '@UPDCLT'" . "\r\n";
//
    // $strSQL .= " FROM       WK_HKASOUMEISAI_APPEND" . "\r\n";
//
    // $strSQL .= " WHERE      CMN_NO = '@CMNNO'" . "\r\n";
//
    // $strSQL .= " AND        UPD_SYA_CD = '@UPDUSER'" . "\r\n";
//
    // $strSQL = str_replace("@CMNNO", $strChumon, $strSQL);
    // $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
    // $strSQL = str_replace("@UPDAPP", $UPDAPP, $strSQL);
    // $strSQL = str_replace("@UPDCLT", $UPDCLTNM, $strSQL);
//
    // return $strSQL;
    // }
    /* 20161107 yangyang del e */

    /* 20161107 yangyang del s */
    // public function fncUpdateKasouNOOnlySql($strOldKasouNo, $strNewKasouNo, $strChumon)
    // {
    // $strSQL = "";
    // $strSQL .= " UPDATE HKASOUMEISAI" . "\r\n";
    // $strSQL .= " SET    KASOUNO = '" . $strNewKasouNo . "'" . "\r\n";
    // $strSQL .= " WHERE  CMN_NO = '" . $strChumon . "'" . "\r\n";
    // $strSQL .= " AND    KASOUNO = '" . rtrim($strOldKasouNo) . "'" . "\r\n";
//
    // return $strSQL;
    // }
    /* 20161107 yangyang del e */

    /* 20161107 yangyang del s */
    // /**********************************************************************
    // *処 理 名：配送指示をUPDATEする
    // *関 数 名：fncUpdateHaisouSijiSql
    // *引    数：無し
    // *戻 り 値：SQL文
    // *処理説明：配送指示をUPDATEする
    // **********************************************************************/
    // public function fncUpdateHaisouSijiSql($strHaisouSiji, $strChumon, $strKasou)
    // {
    // $strSQL = "";
    // $strSQL .= "UPDATE HKASOUMEISAI" . "\r\n";
    // $strSQL .= "SET    MEMO = '@MEMO'" . "\r\n";
    // $strSQL .= "WHERE  CMN_NO = '@CMNNO'" . "\r\n";
    // $strSQL .= "AND    KASOUNO = '@KASOUNO'" . "\r\n";
    // $strSQL = str_replace("@MEMO", $strHaisouSiji, $strSQL);
    // $strSQL = str_replace("@CMNNO", $strChumon, $strSQL);
    // $strSQL = str_replace("@KASOUNO", $strKasou, $strSQL);
//
    // return $strSQL;
    // }
    /* 20161107 yangyang del e */

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
        $strSQL .= ",         (KYK_OKY.INP_SIM1 || ' ' || KYK_OKY.INP_SIM2) KEIYAKUSYA" . "\r\n";
        $strSQL .= ",         CMN.KYOTN_CD" . "\r\n";
        $strSQL .= ",         BUS.KYOTN_RKN BUSYOMEI" . "\r\n";
        $strSQL .= ",         CMN.HNB_TAN_EMP_NO" . "\r\n";
        $strSQL .= ",         (SYA.SYAIN_KNJ_SEI || '  ' || SYA.SYAIN_KNJ_MEI) SYAIN" . "\r\n";
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
        $strSQL .= "FROM      M41E10 CMN" . "\r\n";
        $strSQL .= "LEFT JOIN HKASOUMEISAI KASO" . "\r\n";
        $strSQL .= "ON        CMN.CMN_NO = KASO.CMN_NO" . "\r\n";
        $strSQL .= "LEFT JOIN M41C01 KYK_OKY" . "\r\n";
        $strSQL .= "ON        KYK_OKY.DLRCSRNO = CMN.KYK_CUS_NO" . "\r\n";
        $strSQL .= "LEFT JOIN M27M01 BUS" . "\r\n";
        $strSQL .= "ON        BUS.KYOTN_CD = CMN.KYOTN_CD" . "\r\n";
        $strSQL .= "AND       BUS.HANSH_CD = '3634'" . "\r\n";
        $strSQL .= "AND       BUS.ES_KB = 'E'" . "\r\n";
        $strSQL .= "LEFT JOIN M29MA4 SYA" . "\r\n";
        $strSQL .= "ON        SYA.SYAIN_NO = CMN.HNB_TAN_EMP_NO" . "\r\n";
        $strSQL .= "WHERE    CMN.CMN_NO = '@CMNNO'" . "\r\n";
        $strSQL .= "AND      NVL(TRIM(KASO.KASOUNO),'@KASOUNO') = '@KASOUNO'" . "\r\n";
        $strSQL .= "AND    NVL(KASO.GYOUSYA_CD,'999999') = '999999'" . "\r\n";
        $strSQL .= "ORDER BY KASO.FUZOKUHINKBN, KASO.EDA_NO" . "\r\n";

        $strSQL = str_replace("@CMNNO", $strChumon, $strSQL);
        $strSQL = str_replace("@KASOUNO", ($strKasou == "") ? " " : $strKasou, $strSQL);

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
        $strSQL .= ",         KASO.HANBAISYASYU" . "\r\n";
        $strSQL .= ",         KASO.SYADAIKATA" . "\r\n";
        $strSQL .= ",         KASO.CAR_NO" . "\r\n";
        $strSQL .= ",         SYA.SYAIN_KNJ_MEI" . "\r\n";
        $strSQL .= ",         KASO.MEMO" . "\r\n";
        $strSQL .= ",         (KYK_OKY.INP_SIM1 || ' ' || KYK_OKY.INP_SIM2) KEIYAKUSYA" . "\r\n";
        $strSQL .= ",         (CASE WHEN KASO.SYADAIKATA IS NULL THEN KASO.CAR_NO ELSE KASO.SYADAIKATA || '-' || KASO.CAR_NO END) SYADAI_NO" . "\r\n";
        $strSQL .= ",         BASE.BASEH_KN SYASYUMEI" . "\r\n";
        $strSQL .= ",         (SELECT BUSYO_NM FROM HPRINTTANTO) TANTO_BUSYO" . "\r\n";
        $strSQL .= ",         (SELECT TANTO_SEI FROM HPRINTTANTO) TANTO_NM" . "\r\n";
        $strSQL .= ",         KASO.KASOUNO KASOU_NO" . "\r\n";
        $strSQL .= ",         '@TODAY' HAKKOBI" . "\r\n";
        $strSQL .= ",         KASO.CMN_NO" . "\r\n";
        $strSQL .= ",         CMN.KYOTN_CD" . "\r\n";
        $strSQL .= ",         BUS.KYOTN_RKN BUSYO_NM" . "\r\n";
        $strSQL .= ",         SYA.SYAIN_KNJ_SEI" . "\r\n";
        $strSQL .= ",         KASO.BUHINNM" . "\r\n";
        $strSQL .= ",         NVL(KASO.GAICYU_ZITU,0) SEIKYU" . "\r\n";
        $strSQL .= ",         (SELECT SUM(NVL(T_KASO.GAICYU_ZITU,0)) TOTAL" . "\r\n";
        $strSQL .= "           FROM   HKASOUMEISAI T_KASO " . "\r\n";
        $strSQL .= "           WHERE  T_KASO.CMN_NO = KASO.CMN_NO " . "\r\n";
        $strSQL .= "           AND    T_KASO.GYOUSYA_CD = KASO.GYOUSYA_CD) SYOUKEI" . "\r\n";
        $strSQL .= ", (SELECT COUNT(*) FROM (SELECT DISTINCT GYOUSYA_CD FROM HKASOUMEISAI WHERE CMN_NO = '@CMNNO' AND KASOUNO = '@KASOUNO' AND GYOUSYA_CD IS NOT NULL)) MAI" . "\r\n";
        $strSQL .= "FROM      HKASOUMEISAI KASO" . "\r\n";
        $strSQL .= "LEFT JOIN M41E10 CMN" . "\r\n";
        $strSQL .= "ON        CMN.CMN_NO = KASO.CMN_NO" . "\r\n";
        $strSQL .= "LEFT JOIN M28M68 TRK" . "\r\n";
        $strSQL .= "ON        TRK.ATO_DTRPITCD = KASO.GYOUSYA_CD" . "\r\n";
        $strSQL .= "LEFT JOIN M41C01 OKY" . "\r\n";
        $strSQL .= "ON        OKY.DLRCSRNO = CMN.SIY_CUS_NO" . "\r\n";
        $strSQL .= "LEFT JOIN M41C01 KYK_OKY" . "\r\n";
        $strSQL .= "ON        KYK_OKY.DLRCSRNO = CMN.KYK_CUS_NO" . "\r\n";
        $strSQL .= "LEFT JOIN M27AM1 BASE" . "\r\n";
        $strSQL .= "ON        BASE.BASEH_CD = CMN.MOD_CD" . "\r\n";

        $strSQL .= "LEFT JOIN M27M01 BUS" . "\r\n";
        $strSQL .= "ON        BUS.KYOTN_CD = CMN.KYOTN_CD" . "\r\n";
        $strSQL .= "AND       BUS.HANSH_CD = '3634'" . "\r\n";
        $strSQL .= "AND       BUS.ES_KB = 'E'" . "\r\n";
        $strSQL .= "LEFT JOIN M29MA4 SYA" . "\r\n";
        $strSQL .= "ON        SYA.SYAIN_NO = CMN.HNB_TAN_EMP_NO" . "\r\n";
        $strSQL .= "WHERE     KASO.CMN_NO = '@CMNNO'" . "\r\n";
        $strSQL .= " AND      KASO.KASOUNO = '@KASOUNO'" . "\r\n";
        $strSQL .= "AND       KASO.GYOUSYA_CD IS NOT NULL" . "\r\n";
        $strSQL .= "AND       NVL(KASO.GYOUSYA_CD,' ') <> '88888'" . "\r\n";
        $strSQL .= "ORDER BY  KASO.GYOUSYA_CD" . "\r\n";
        $strSQL .= ",         KASO.EDA_NO" . "\r\n";

        $strSQL = str_replace("@CMNNO", $strChumon, $strSQL);
        $strSQL = str_replace("@KASOUNO", $strKasou, $strSQL);
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

    //20180528 YIN INS S
    function fncCheckHKASOUMEISAI_PRINTLOGSql($strChumon, $strKasou)
    {
        $strSQL = "";
        $strSQL .= "SELECT KASOUNO" . "\r\n";
        //20180601 YIN INS S
        $strSQL .= ", TO_CHAR(CREATE_DATE,'YYYYMMDD') AS CREATE_DATE" . "\r\n";
        //20180601 YIN INS E
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
    //20180528 YIN INS E



    //*************************************
    // * 公開メソッド
    //*************************************
    // public function delete($postData = NULL)
    // {
    //     return parent::delete($this->fncDeleteWKKASOUMEISAI());
    // }

    // public function insert($postData)
    // {
    //     return parent::insert($this->fncInsertWKKASOUMEISAI($postData));
    // }

    /* 20161107 yangyang del s */
    // public function fncSelectM41E12Chk($postData = NULL)
    // {
    // return parent::Fill($this -> fncSelectM41E12ChkSql($postData));
    // }
    /* 20161107 yangyang del e */

    public function fncKasouTblCheck($postData)
    {
        return parent::select($this->fncKasouTblCheckSql($postData));
    }

    /* 20161107 yangyang del s */
    // public function fncUpdSaiban($strNengetu, $fncUpdSaiban, $ajax = TRUE)
    // {
    // if ($ajax)
    // {
    // return parent::select($this -> fncUpdSaibanSql($strNengetu));
    // }
    // else
    // {
    // return parent::Fill($this -> fncUpdSaibanSql($strNengetu));
    // }
//
    // }
    /* 20161107 yangyang del e */

    /* 20161107 yangyang del s */
    // public function fncUpdSaibanUpdate($BANGO, $UPD_TIME, $strNengetu)
    // {
    // return parent::Do_Execute($this -> fncUpdSaibanUpdateSql($BANGO, $UPD_TIME, $strNengetu));
    // }
    /* 20161107 yangyang del e */

    /* 20161107 yangyang del s */
    // public function fncUpdSaibanInsert($UPD_TIME, $strNengetu, $ajax = TRUE)
    // {
    // if ($ajax)
    // {
    // return parent::insert($this -> fncUpdSaibanInsertSql($UPD_TIME, $strNengetu));
    // }
    // else
    // {
    // return parent::Do_Execute($this -> fncUpdSaibanInsertSql($UPD_TIME, $strNengetu));
    // }
//
    // }
    /* 20161107 yangyang del e */

    public function fncSearchSelect($postData, $NENGETU)
    {
        return parent::select($this->fncSearchSelectSql($postData, $NENGETU));
    }

    public function fncCustomerSelect($postData, $blnOutput, $ajax = TRUE)
    {
        if ($ajax) {
            return parent::select($this->fncCustomerSelectSql($postData, $blnOutput));
        } else {
            return parent::Fill($this->fncCustomerSelectSql($postData, $blnOutput));
        }

    }

    /* 20161107 yangyang del s */
    // public function fncSelectFromHkasoumeisai($table, $postData = NULL, $strKasouNo = '')
    // {
    // return parent::Fill($this -> fncSelectFromHkasoumeisaiSql($table, $postData, $strKasouNo));
    // }
    /* 20161107 yangyang del e */

    /* 20161107 yangyang del s */
    // public function fncInsertNoMeisaiIns($postData, $strfirstKasouNo)
    // {
    // return parent::Do_Execute($this -> fncInsertNoMeisaiInsSql($postData, $strfirstKasouNo));
    // }
    /* 20161107 yangyang del e */

    /* 20161107 yangyang del s */
    // public function fncDeleteHKASOUMEISAI($strTableNM, $strChumon)
    // {
    // return parent::Do_Execute($this -> fncDeleteHKASOUMEISAISql($strTableNM, $strChumon));
    // }
    /* 20161107 yangyang del e */

    /* 20161107 yangyang del s */
    // public function fncInsertHKASOUMEISAI($strChumon)
    // {
    // return parent::Do_Execute($this -> fncInsertHKASOUMEISAISql($strChumon));
    // }
    /* 20161107 yangyang del e */

    /* 20161107 yangyang del s */
    // public function fncUpdateKasouNOOnly($strOldKasouNo, $strNewKasouNo, $strChumon)
    // {
    // return parent::Do_Execute($this -> fncUpdateKasouNOOnlySql($strOldKasouNo, $strNewKasouNo, $strChumon));
    // }
    /* 20161107 yangyang del e */

    /* 20161107 yangyang del s */
    // public function fncUpdateHaisouSiji($strHaisouSiji, $strChumon, $strKasou)
    // {
    // return parent::Do_Execute($this -> fncUpdateHaisouSijiSql($strHaisouSiji, $strChumon, $strKasou));
    // }
    /* 20161107 yangyang del e */

    /* 20161107 yangyang del s */
    // public function fncDeleteWK_KASOUMEISAI()
    // {
    // return parent::delete($this -> fncDeleteWKKASOUMEISAISql());
    // }

    // public function fncInsertWK_KASOUMEISAI($Array_Insert)
    // {
    // return parent::insert($this -> fncInsertWKKASOUMEISAISql($Array_Insert));
    // }
    /* 20161107 yangyang del e */

    /* 20161107 yangyang add s */
    public function fncSelectCheckCMNNO($Array_Insert)
    {
        return parent::select($this->fncSelectCheckCMNNOSql($Array_Insert));
    }
    /* 20161107 yangyang add e */

    public function fncKasouMPrintSel($strChumon, $strKasou)
    {
        return parent::select($this->fncKasouMPrintSelSql($strChumon, $strKasou));
    }

    public function fncGaichuPrintSelect($strChumon, $strKasou)
    {
        return parent::select($this->fncGaichuPrintSelectSql($strChumon, $strKasou));
    }

    public function fncHPRINTTANTO()
    {
        return parent::select($this->fncHPRINTTANTOSql());
    }

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

        // $this->log($strSQL);
        return $strSQL;
    }

    public function fncM27A02($CMN_NO)
    {
        return parent::select($this->fncM27A02Sql($CMN_NO));
    }

    /* 20161108 yangyang del s */
    // function fncUPDKASOSql($strChumon, $strKasou, $strCarNO, $strSyadaiKata)
    // {
    // $strSQL = "";
    // $strSQL = "UPDATE ";
    // $strSQL .= "  HKASOUMEISAI ";
    // $strSQL .= "SET ";
    // $strSQL .= "  CAR_NO='@CAR_NO', ";
    // $strSQL .= "  SYADAIKATA='@SYADAIKATA' ";
    // $strSQL .= "WHERE ";
    // $strSQL .= "  CMN_NO='@CMNNO' ";
    // $strSQL .= "AND ";
    // $strSQL .= "  NVL(TRIM(KASOUNO),'@KASOUNO') = '@KASOUNO' ";
//
    // $strSQL = str_replace("@CAR_NO", $strCarNO, $strSQL);
    // $strSQL = str_replace("@SYADAIKATA", $strSyadaiKata, $strSQL);
    // $strSQL = str_replace("@CMNNO", $strChumon, $strSQL);
    // $strSQL = str_replace("@KASOUNO", ($strKasou == "") ? " " : $strKasou, $strSQL);
//
    // return $strSQL;
    // }
//
    // public function fncUPDKASO($strChumon, $strKasou, $strCarNO, $strSyadaiKata)
    // {
    // return parent::update($this -> fncUPDKASOSql($strChumon, $strKasou, $strCarNO, $strSyadaiKata));
    // }
    /* 20161108 yangyang del e */

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
}
