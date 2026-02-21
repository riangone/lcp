<?php
// 共通クラスの読込み
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFnc;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL　　
 * 20150915			  #2136						   BUG								yinhuaiyu
 *　　　　　
 * * --------------------------------------------------------------------------------------------
 */
class FrmSyasyuArariChkList extends ClsComDb
{
    //====
    //--execute--
    //====
    public function FrmSyasyuArariChkList_formLoad_select()
    {
        $strSql = $this->FrmSyasyuArariChkList_formLoad_sql();
        return parent::select($strSql);
    }

    public function fncPrintSelect($cboYMEnd)
    {
        $strSql = $this->fncPrintSelect_sql($cboYMEnd);
        return parent::select($strSql);
    }

    public function fncWKDel()
    {
        $strSql = $this->fncWKDel_sql();

        return parent::Do_Execute($strSql);

    }

    public function fncArariDel($TOUGETU)
    {

        $strSql = $this->fncArariDel_sql($TOUGETU);

        return parent::Do_Execute($strSql);
    }

    public function fncGenriInsert($TOUGETU)
    {
        $strSql = $this->fncGenriInsert_sql($TOUGETU);

        return parent::Do_Execute($strSql);
    }

    public function fncUriAnbunSel($cboYMEnd, $cboYMStart)
    {

        $strSql = $this->fncUriAnbunSel_sql($cboYMEnd, $cboYMStart);

        return parent::Fill($strSql);

    }

    public function fncUriAnbunSyasyuIns($cboYMEnd, $T_SAGAKU, $T_ARARI)
    {
        $strSql = $this->fncUriAnbunSyasyuIns_sql($cboYMEnd, $T_SAGAKU, $T_ARARI);

        return parent::Do_Execute($strSql);
    }

    public function fncUriAnbunExtIns($cboYMEnd, $intSagaku)
    {
        $strSql = $this->fncUriAnbunExtIns_sql($cboYMEnd, $intSagaku);

        return parent::Do_Execute($strSql);
    }

    public function fncSyaryoPcsAnbunSel($cboYMEnd)
    {
        $strSql = $this->fncSyaryoPcsAnbunSel_sql($cboYMEnd);

        return parent::Fill($strSql);
    }

    public function fncSyaryoPcsAnbunSyasyuIns($cboYMEnd, $T_SAGAKU, $T_GENKA)
    {
        $strSql = $this->fncSyaryoPcsAnbunSyasyuIns_sql($cboYMEnd, $T_SAGAKU, $T_GENKA);

        return parent::Do_Execute($strSql);
    }

    public function fncSyaryoPcsAnbunExtIns($cboYMEnd, $T_SAGAKU)
    {
        $strSql = $this->fncSyaryoPcsAnbunExtIns_sql($cboYMEnd, $T_SAGAKU);

        return parent::Do_Execute($strSql);
    }

    public function fncKasouPcsAnbunSel($cboYMEnd)
    {
        $strSql = $this->fncKasouPcsAnbunSel_sql($cboYMEnd);

        return parent::Fill($strSql);
    }

    public function fncKasouPcsAnbunSyasyuIns($cboYMEnd, $T_SAGAKU, $T_GENKA)
    {
        $strSql = $this->fncKasouPcsAnbunSyasyuIns_sql($cboYMEnd, $T_SAGAKU, $T_GENKA);

        return parent::Do_Execute($strSql);
    }

    public function fnckasouPcsAnbunExtIns($cboYMEnd, $intSagaku)
    {
        $strSql = $this->fncKasouPcsAnbunExtIns_sql($cboYMEnd, $intSagaku);

        return parent::Do_Execute($strSql);
    }

    public function fncUnchinIns($cboYMEnd)
    {
        $strSql = $this->fncUnchinIns_sql($cboYMEnd);

        return parent::Do_Execute($strSql);
    }

    public function fncArariIns($cboYMEnd)
    {
        $strSql = $this->fncArariIns_sql($cboYMEnd);

        return parent::Do_Execute($strSql);
    }

    public function fncArariekiListSel($cboYMEnd, $cboYMStart)
    {
        $strSql = $this->fncArariekiListSel_sql($cboYMEnd, $cboYMStart);
        return parent::select($strSql);
    }

    public function fncChoseiInsert($TOUGETU)
    {
        $strSql = $this->fncChoseiInsert_sql($TOUGETU);

        return parent::Do_Execute($strSql);
    }

    public function fncRuikeiInsert($cboYMEnd, $cboYMStart)
    {
        $strSql = $this->fncRuikeiInsert_sql($cboYMEnd, $cboYMStart);

        return parent::Do_Execute($strSql);
    }

    //====
    //--sql--
    //====
    public function FrmSyasyuArariChkList_formLoad_sql()
    {
        $sqlstr = "select";
        $sqlstr .= "        ID ,";
        $sqlstr .= "        (SUBSTR(KISYU_YMD,1,4) || '/' || SUBSTR(KISYU_YMD,5,2)|| '/01') KISYU_YMD ";
        $sqlstr .= ",        (substr(SYR_YMD,1,4) || '/' || SUBSTR(SYR_YMD,5,2)|| '/01') TOUGETU  ";
        $sqlstr .= "from ";
        $sqlstr .= "        HKEIRICTL ";
        $sqlstr .= "WHERE ID='01'";
        return $sqlstr;
    }

    public function fncPrintSelect_sql($cboYMEnd)
    {
        $sqlstr = "";
        $sqlstr .= "SELECT URIAGE.SYARYOUKIN URI_SYARYO";
        $sqlstr .= ",      URIAGE.TOKUBETU URI_TOKUBETU";
        $sqlstr .= ",      URIAGE.NEBIKI URI_NEBIKI";
        $sqlstr .= ",      URIAGE.SITASON URI_SITASON";
        $sqlstr .= ",      URIAGE.GENKA URI_GENKA";
        $sqlstr .= ",      URIAGE.KASOU URI_KASOU";
        $sqlstr .= ",      KAIKEI.SYARYOUKIN KAI_SYARYO";
        $sqlstr .= ",      KAIKEI.TOKUBETU KAI_TOKUBETU";
        $sqlstr .= ",      KAIKEI.NEBIKI KAI_NEBIKI";
        $sqlstr .= ",      KAIKEI.GENKA KAI_GENKA";
        $sqlstr .= ",      KAIKEI.KASOU KAI_KASOU";
        $sqlstr .= ",      SUBSTR('@TOUGETU',1,4) NEN";
        $sqlstr .= ",      SUBSTR('@TOUGETU',5,2) TUKI";
        $sqlstr .= "       FROM   ";
        $sqlstr .= "        (SELECT (SUM(GRI.SYARYOU_KIN)) SYARYOUKIN";
        $sqlstr .= "        ,       (SUM(GRI.TENPU_KEIYAKU) + SUM(GRI.TOKUBETU_KEIYAKU)) TOKUBETU";
        $sqlstr .= "        ,       (SUM(GRI.SYARYOU_NEBIKI)) NEBIKI";
        $sqlstr .= "        ,       (SUM(GRI.SHR_JKN_SIT_KIN) - SUM(GRI.SIT_SATEI_KIN)) SITASON";
        $sqlstr .= "        ,       (SUM(CASE WHEN GRI.KEIYAKUTEN = '17349' THEN 0 ELSE GRI.GNK_HJN_PCS END)) GENKA";
        $sqlstr .= "        ,       (SUM(GRI.TOKUBETU_GENKA) + SUM(GRI.TENPU_GENKA)) KASOU";
        $sqlstr .= "        FROM   HGENRI GRI";
        $sqlstr .= "        INNER JOIN HSYASYUMST SYA";
        $sqlstr .= "        ON     SYA.UCOYA_CD = GRI.KUKURI_CD";
        $sqlstr .= "        WHERE  GRI.DATA_KB <> '2'";
        $sqlstr .= "        AND    GRI.NENGETU LIKE '@TOUGETU'";
        $sqlstr .= "        ) URIAGE";
        $sqlstr .= ",       (SELECT SUM(V.SYARYO_KIN) SYARYOUKIN";
        $sqlstr .= "        ,      SUM(V.TKB_SIYO) TOKUBETU";
        $sqlstr .= "        ,      SUM(V.SYARYO_NBK) NEBIKI";
        $sqlstr .= "        ,      SUM(V.SYARYO_PCS) GENKA";
        $sqlstr .= "        ,      SUM(V.KASOU_PCS) KASOU";
        $sqlstr .= "        FROM   (";
        $sqlstr .= "                SELECT (CASE WHEN KAI.L_KAMOK_CD = '41111' THEN NVL(KAI.KEIJO_GK,0) * -1 END) SYARYO_KIN";
        $sqlstr .= "                ,      (CASE WHEN KAI.L_KAMOK_CD = '41119' THEN NVL(KAI.KEIJO_GK,0) END) SYARYO_NBK";
        $sqlstr .= "                ,      (CASE WHEN KAI.L_KAMOK_CD = '41112' THEN NVL(KAI.KEIJO_GK,0) * -1 END) TKB_SIYO";
        $sqlstr .= "                ,      (CASE WHEN KAI.L_KAMOK_CD = '42111' THEN NVL(KAI.KEIJO_GK,0) END) SYARYO_PCS";
        $sqlstr .= "                ,      (CASE WHEN KAI.L_KAMOK_CD = '42112' THEN NVL(KAI.KEIJO_GK,0) END) KASOU_PCS";
        $sqlstr .= "                FROM   HKAIKEI KAI";
        $sqlstr .= "                WHERE  KAI.L_KAMOK_CD IN ('41111','41119','41112','42111','42112')";
        $sqlstr .= "                AND    KAI.KEIJO_DT BETWEEN '@STARTTOU' AND '@ENDTOU'";
        $sqlstr .= "                UNION ALL";
        $sqlstr .= "                SELECT (CASE WHEN KAI.R_KAMOK_CD = '41111' THEN NVL(KAI.KEIJO_GK,0) END)";
        $sqlstr .= "                ,      (CASE WHEN KAI.R_KAMOK_CD = '41119' THEN NVL(KAI.KEIJO_GK,0) * -1 END)";
        $sqlstr .= "                ,      (CASE WHEN KAI.R_KAMOK_CD = '41112' THEN NVL(KAI.KEIJO_GK,0) END)";
        $sqlstr .= "                ,      (CASE WHEN KAI.R_KAMOK_CD = '42111' THEN NVL(KAI.KEIJO_GK,0) * -1 END)";
        $sqlstr .= "                ,      (CASE WHEN KAI.R_KAMOK_CD = '42112' THEN NVL(KAI.KEIJO_GK,0) * -1 END)";
        $sqlstr .= "                FROM   HKAIKEI KAI";
        $sqlstr .= "                WHERE  KAI.R_KAMOK_CD IN ('41111','41119','41112','42111','42112')";
        $sqlstr .= "                AND    KAI.KEIJO_DT BETWEEN '@STARTTOU' AND '@ENDTOU'";
        $sqlstr .= "               ) V";
        $sqlstr .= "        ) KAIKEI";

        $tmpYear = substr($cboYMEnd, 0, 4);
        $tmpMonth = substr($cboYMEnd, 4, 2);

        if (substr($tmpMonth, 0, 1) == 0) {
            $tmpMonth = substr($tmpMonth, 1, 1);
        }
        $d = date("t", strtotime($tmpYear . '-' . $tmpMonth));
        $lastDay_date = $cboYMEnd . $d;

        $sqlstr = str_replace("@TOUGETU", $cboYMEnd, $sqlstr);
        $sqlstr = str_replace("@STARTTOU", $cboYMEnd . "01", $sqlstr);
        $sqlstr = str_replace("@ENDTOU", $lastDay_date, $sqlstr);

        return $sqlstr;
    }

    public function fncWKDel_sql()
    {
        $sqlstr = "DELETE FROM WK_SYASYUBETUARARI ";

        return $sqlstr;
    }

    public function fncArariDel_sql($TOUGETU)
    {
        $sqlstr = "";
        $sqlstr .= "DELETE FROM HSYASYUBETUARARI  ";
        $sqlstr .= "WHERE  NENGETU = '@TOUGETU'";
        $sqlstr = str_replace("@TOUGETU", $TOUGETU, $sqlstr);

        return $sqlstr;
    }
    //20150915 yinhuaiyu mod S
    public function fncGenriInsert_sql($TOUGETU)
    {
        $sqlstr = "";

        $sqlstr .= "INSERT INTO WK_SYASYUBETUARARI ";
        $sqlstr .= "(      MAKECD ";
        $sqlstr .= ",NENGETU ";
        $sqlstr .= ",OYA_CD ";
        $sqlstr .= ",HASEI_MOTO_KB ";
        $sqlstr .= ",HONTAIURIAGE ";
        $sqlstr .= ",HONTAIGAKU ";
        $sqlstr .= ",HONTAINEBIKI ";
        $sqlstr .= ",KASOUURIAGE ";
        $sqlstr .= ",SYARYOARARI ";
        $sqlstr .= ",SYARYOGENKA ";
        $sqlstr .= ",KASOUFUZOKU ";
        $sqlstr .= ",UNTIN ";
        $sqlstr .= ",HOKAN ";
        $sqlstr .= ",NOUTEN ";
        $sqlstr .= ",HENDOUSYOUREI ";
        $sqlstr .= ",SIZYOUTAISAKU ";
        $sqlstr .= ",TOUKI_DAISU ";
        $sqlstr .= ",TOUKI_ARARI ";
        $sqlstr .= ",ZENKI_DAISU ";
        $sqlstr .= ",ZENKI_ARARI ";
        $sqlstr .= ",TOUGETURIEKI ";
        $sqlstr .= ",KAFUGENKA ";
        $sqlstr .= ",SITASON ";
        $sqlstr .= ",UPD_DATE ";
        $sqlstr .= ",CREATE_DATE ";
        $sqlstr .= ") ";
        $sqlstr .= "SELECT 'SZ' ";
        $sqlstr .= ",'@TOUGETU' ";
        $sqlstr .= ",KKR.OYA_CD ";
        $sqlstr .= ",'GR' ";
        $sqlstr .= ",SUM(NVL(GRI.DAISU,0)) DAISU ";
        $sqlstr .= ",SUM(NVL(GRI.SYARYOU_KIN,0)) SYARYO_KIN ";
        $sqlstr .= ",SUM(NVL(GRI.SYARYOU_NEBIKI,0) + NVL(GRI.SHR_JKN_SIT_KIN,0) - NVL(GRI.SIT_SATEI_KIN,0)) NEBIKI ";
        $sqlstr .= ",SUM(NVL(GRI.TENPU_KEIYAKU,0) + NVL(GRI.TOKUBETU_KEIYAKU,0)) KASOU_URI ";
        $sqlstr .= ",SUM(NVL(GRI.SYARYOU_KIN,0) + NVL(GRI.TENPU_KEIYAKU,0) + NVL(GRI.TOKUBETU_KEIYAKU,0)- NVL(GRI.SYARYOU_NEBIKI,0) - (NVL(GRI.SHR_JKN_SIT_KIN,0) - NVL(GRI.SIT_SATEI_KIN,0))) ARARI ";
        $sqlstr .= ",SUM(CASE WHEN GRI.KEIYAKUTEN = '17349' THEN 0 ELSE NVL(GRI.GNK_HJN_PCS,0) END) GENKA ";
        $sqlstr .= ",0 ";
        $sqlstr .= ",0 ";
        $sqlstr .= ",0 ";
        $sqlstr .= ",0 ";
        $sqlstr .= ",0 ";
        $sqlstr .= ",0 ";
        $sqlstr .= ",0 ";
        $sqlstr .= ",0 ";
        $sqlstr .= ",0 ";
        $sqlstr .= ",0 ";
        $sqlstr .= ",SUM(NVL(GRI.SYARYOU_KIN,0) + NVL(GRI.TENPU_KEIYAKU,0) + NVL(GRI.TOKUBETU_KEIYAKU,0) - (NVL(GRI.SYARYOU_NEBIKI,0) + NVL(GRI.SHR_JKN_SIT_KIN,0) - NVL(GRI.SIT_SATEI_KIN,0))) TOU_ARARI ";
        $sqlstr .= ",SUM(NVL(GRI.TENPU_GENKA,0) + NVL(GRI.TOKUBETU_GENKA,0)) KASOU_PCS ";
        $sqlstr .= ",SUM(NVL(GRI.SHR_JKN_SIT_KIN,0) - NVL(GRI.SIT_SATEI_KIN,0)) SITASON ";
        $sqlstr .= ",SYSDATE ";
        $sqlstr .= ",SYSDATE ";
        $sqlstr .= "FROM HGENRI GRI ";
        $sqlstr .= "INNER JOIN HSYASYUMST SYA ";
        $sqlstr .= "ON   SYA.UCOYA_CD = GRI.KUKURI_CD ";
        $sqlstr .= "INNER JOIN HSYASYUKKRMST KKR ";
        $sqlstr .= "ON   KKR.UCOYA_CD = GRI.KUKURI_CD ";

        $sqlstr .= "WHERE GRI.DATA_KB <> '2' ";
        $sqlstr .= "AND   GRI.NENGETU = '@TOUGETU' ";
        $sqlstr .= "GROUP BY KKR.OYA_CD ";
        $sqlstr = str_replace("@TOUGETU", $TOUGETU, $sqlstr);

        return $sqlstr;
    }

    public function fncChoseiInsert_sql($TOUGETU)
    {
        $sqlstr = "";

        $sqlstr .= "INSERT INTO WK_SYASYUBETUARARI  ";
        $sqlstr .= "(      MAKECD  ";
        $sqlstr .= ",    NENGETU  ";
        $sqlstr .= ",    OYA_CD  ";
        $sqlstr .= ",    HASEI_MOTO_KB  ";
        $sqlstr .= ",    HONTAIURIAGE  ";
        $sqlstr .= ",    HONTAIGAKU  ";
        $sqlstr .= ",    HONTAINEBIKI  ";
        $sqlstr .= ",    KASOUURIAGE  ";
        $sqlstr .= ",    SYARYOARARI  ";
        $sqlstr .= ",    SYARYOGENKA  ";
        $sqlstr .= ",    KASOUFUZOKU  ";
        $sqlstr .= ",    UNTIN  ";
        $sqlstr .= ",    HOKAN  ";
        $sqlstr .= ",    NOUTEN  ";
        $sqlstr .= ",    HENDOUSYOUREI  ";
        $sqlstr .= ",    SIZYOUTAISAKU  ";
        $sqlstr .= ",    TOUKI_DAISU  ";
        $sqlstr .= ",    TOUKI_ARARI  ";
        $sqlstr .= ",    ZENKI_DAISU  ";
        $sqlstr .= ",    ZENKI_ARARI  ";
        $sqlstr .= ",    TOUGETURIEKI  ";
        $sqlstr .= ",    KAFUGENKA  ";
        $sqlstr .= ",    SITASON  ";
        $sqlstr .= ",    UPD_DATE  ";
        $sqlstr .= ",    CREATE_DATE  ";
        $sqlstr .= ")  ";
        $sqlstr .= "SELECT 'SZ'  ";
        $sqlstr .= ",    '@TOUGETU'  ";
        $sqlstr .= ",    CHO.OYA_CD  ";
        $sqlstr .= ",    'CH'  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    NVL(CHO.HONTAIGAKU,0)  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    NVL(CHO.HONTAIGAKU,0)  ";
        $sqlstr .= ",    NVL(CHO.SYARYOARARI,0) * -1  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    NVL(CHO.HONTAIGAKU,0)  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    SYSDATE  ";
        $sqlstr .= ",    SYSDATE  ";
        $sqlstr .= "FROM   HARARICHOUSEI CHO  ";
        $sqlstr .= "WHERE  CHO.NENGETU = '@TOUGETU'  ";
        $sqlstr .= "AND    CHO.OYA_CD <> '999'  ";
        $sqlstr = str_replace("@TOUGETU", $TOUGETU, $sqlstr);

        return $sqlstr;
    }

    public function fncRuikeiInsert_sql($cboYMEnd, $cboYMStart)
    {
        $sqlstr = "";
        $sqlstr .= "INSERT INTO WK_SYASYUBETUARARI  ";
        $sqlstr .= "(      MAKECD  ";
        $sqlstr .= ",    NENGETU  ";
        $sqlstr .= ",    OYA_CD  ";
        $sqlstr .= ",    HASEI_MOTO_KB  ";
        $sqlstr .= ",    HONTAIURIAGE  ";
        $sqlstr .= ",    HONTAIGAKU  ";
        $sqlstr .= ",    HONTAINEBIKI  ";
        $sqlstr .= ",    KASOUURIAGE  ";
        $sqlstr .= ",    SYARYOARARI  ";
        $sqlstr .= ",    SYARYOGENKA  ";
        $sqlstr .= ",    KASOUFUZOKU  ";
        $sqlstr .= ",    UNTIN  ";
        $sqlstr .= ",    HOKAN  ";
        $sqlstr .= ",    NOUTEN  ";
        $sqlstr .= ",    HENDOUSYOUREI  ";
        $sqlstr .= ",    SIZYOUTAISAKU  ";
        $sqlstr .= ",    TOUKI_DAISU  ";
        $sqlstr .= ",    TOUKI_ARARI  ";
        $sqlstr .= ",    ZENKI_DAISU  ";
        $sqlstr .= ",    ZENKI_ARARI  ";
        $sqlstr .= ",    TOUGETURIEKI  ";
        $sqlstr .= ",    KAFUGENKA  ";
        $sqlstr .= ",    SITASON  ";
        $sqlstr .= ",    UPD_DATE  ";
        $sqlstr .= ",    CREATE_DATE  ";
        $sqlstr .= ")  ";
        $sqlstr .= "SELECT 'SZ'  ";
        $sqlstr .= ",    '@TOUGETU'  ";
        $sqlstr .= ",    V.OYA  ";
        $sqlstr .= ",    'GK'  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    SUM(V.TOUKI_DAISU)  ";
        $sqlstr .= ",    SUM(V.TOUKI_ARARI)  ";
        $sqlstr .= ",    SUM(V.ZENKI_DAISU)  ";
        $sqlstr .= ",    SUM(V.ZENKI_ARARI)  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    SYSDATE  ";
        $sqlstr .= ",    SYSDATE  ";
        $sqlstr .= "FROM   (  ";
        $sqlstr .= " SELECT ARI.OYA  ";
        $sqlstr .= ",    SUM(ARI.HONTAIURIAGE) TOUKI_DAISU  ";
        $sqlstr .= ",    SUM(ARI.SYARYOARARI) TOUKI_ARARI  ";
        $sqlstr .= ",    0 ZENKI_DAISU  ";
        $sqlstr .= ",    0 ZENKI_ARARI  ";
        $sqlstr .= "FROM   HSYASYUBETUARARI ARI  ";
        $sqlstr .= "WHERE  ARI.NENGETU BETWEEN '@KISYU' AND '@TOUGETU'  ";
        $sqlstr .= "GROUP BY ARI.OYA  ";
        $sqlstr .= "UNION  ";
        $sqlstr .= "SELECT ARI.OYA  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    SUM(ARI.HONTAIURIAGE)   ";
        $sqlstr .= ",    SUM(ARI.SYARYOARARI)	  ";
        $sqlstr .= "FROM   HSYASYUBETUARARI ARI  ";
        $sqlstr .= "WHERE  ARI.NENGETU BETWEEN '@ZENKISYU' AND '@ZENTOU'  ";
        $sqlstr .= "GROUP BY ARI.OYA  ";
        $sqlstr .= ") V  ";
        $sqlstr .= "GROUP BY V.OYA  ";

        $ZENKISYU = (int) $cboYMStart - 100;
        $ZENTOU = ((int) substr($cboYMEnd, 0, 4) - 1) . "" . substr($cboYMEnd, 4, 2);

        $sqlstr = str_replace("@TOUGETU", $cboYMEnd, $sqlstr);
        $sqlstr = str_replace("@KISYU", $cboYMStart, $sqlstr);

        $sqlstr = str_replace("@ZENKISYU", $ZENKISYU, $sqlstr);
        $sqlstr = str_replace("@ZENTOU", $ZENTOU, $sqlstr);

        return $sqlstr;
    }

    public function fncUriAnbunSel_sql($cboYMEnd, $cboYMStart)
    {
        $sqlstr = "";
        $sqlstr .= "SELECT NVL(KAI.KAI_URI,0) - NVL(ARI.ARI_URI,0) - NVL(CHO.CHO_PCS,0) SAGAKU\n   ";
        $sqlstr .= ",      NVL(ARI.ARI_URI,0) ARARI\n   ";
        $sqlstr .= ",      NVL(KAI.KAI_URI,0) KAIKEI\n   ";
        $sqlstr .= "FROM   (SELECT NVL(SUM(V.SYARYO_KIN),0) + NVL(SUM(V.TKB_SIYO),0) - NVL(SUM(V.SYARYO_NBK),0) KAI_URI\n   ";
        $sqlstr .= "		FROM   (\n   ";
        $sqlstr .= "				SELECT (CASE WHEN KAI.L_KAMOK_CD = '41111' THEN NVL(KAI.KEIJO_GK,0) * -1 END) SYARYO_KIN\n   ";
        $sqlstr .= "				,      (CASE WHEN KAI.L_KAMOK_CD = '41119' THEN NVL(KAI.KEIJO_GK,0) END) SYARYO_NBK\n   ";
        $sqlstr .= "				,      (CASE WHEN KAI.L_KAMOK_CD = '41112' THEN NVL(KAI.KEIJO_GK,0) * -1 END) TKB_SIYO\n   ";
        $sqlstr .= "						FROM   HKAIKEI KAI\n   ";
        $sqlstr .= "				WHERE  KAI.L_KAMOK_CD IN ('41111','41119','41112')\n   ";
        $sqlstr .= "		        AND    KAI.KEIJO_DT BETWEEN '@SYONICHI' AND '@SAISYUBI'\n   ";
        $sqlstr .= "				UNION ALL\n   ";
        $sqlstr .= "				SELECT (CASE WHEN KAI.R_KAMOK_CD = '41111' THEN NVL(KAI.KEIJO_GK,0) END)\n   ";
        $sqlstr .= "				,      (CASE WHEN KAI.R_KAMOK_CD = '41119' THEN NVL(KAI.KEIJO_GK,0) * -1 END)\n   ";
        $sqlstr .= "				,      (CASE WHEN KAI.R_KAMOK_CD = '41112' THEN NVL(KAI.KEIJO_GK,0) END)\n   ";
        $sqlstr .= "				FROM   HKAIKEI KAI\n   ";
        $sqlstr .= "				WHERE  KAI.R_KAMOK_CD IN ('41111','41119','41112')\n   ";
        $sqlstr .= "		        AND    KAI.KEIJO_DT BETWEEN '@SYONICHI' AND '@SAISYUBI'\n   ";
        $sqlstr .= "				) V\n   ";
        $sqlstr .= "         ) KAI\n   ";
        $sqlstr .= ",(SELECT SUM(SYARYOARARI) ARI_URI FROM WK_SYASYUBETUARARI WHERE NENGETU = '@TOUGETU') ARI\n   ";
        $sqlstr .= ",(SELECT SUM(HONTAIGAKU) CHO_PCS FROM HARARICHOUSEI WHERE NENGETU = '@TOUGETU' AND OYA_CD = '999') CHO\n   ";

        $sY = substr($cboYMStart, 0, 4);
        $sM = substr($cboYMStart, 4, 2);
        $d = date("t", strtotime($sM . '-' . $sY));
        $lastDay_date = $cboYMEnd . $d;

        $sqlstr = str_replace("@TOUGETU", $cboYMEnd, $sqlstr);
        $sqlstr = str_replace("@SYONICHI", $cboYMEnd . "01", $sqlstr);
        $sqlstr = str_replace("@SAISYUBI", $lastDay_date, $sqlstr);

        return $sqlstr;
    }

    public function fncUriAnbunSyasyuIns_sql($cboYMEnd, $intSagaku, $intGoukei)
    {
        $sqlstr = "";
        $sqlstr .= "INSERT INTO WK_SYASYUBETUARARI ";
        $sqlstr .= "(      MAKECD ";
        $sqlstr .= ",      NENGETU ";
        $sqlstr .= ",      OYA_CD ";
        $sqlstr .= ",      HASEI_MOTO_KB ";
        $sqlstr .= ",      HONTAIURIAGE ";
        $sqlstr .= ",      HONTAIGAKU ";
        $sqlstr .= ",      HONTAINEBIKI ";
        $sqlstr .= ",      KASOUURIAGE ";
        $sqlstr .= ",      SYARYOARARI ";
        $sqlstr .= ",      SYARYOGENKA ";
        $sqlstr .= ",      KASOUFUZOKU ";
        $sqlstr .= ",      UNTIN ";
        $sqlstr .= ",      HOKAN ";
        $sqlstr .= ",      NOUTEN ";
        $sqlstr .= ",      HENDOUSYOUREI ";
        $sqlstr .= ",      SIZYOUTAISAKU ";
        $sqlstr .= ",TOUKI_DAISU ";
        $sqlstr .= ",TOUKI_ARARI ";
        $sqlstr .= ",ZENKI_DAISU ";
        $sqlstr .= ",ZENKI_ARARI ";
        $sqlstr .= ",TOUGETURIEKI ";
        $sqlstr .= ",KAFUGENKA ";
        $sqlstr .= ",SITASON ";
        $sqlstr .= ",UPD_DATE ";
        $sqlstr .= ",CREATE_DATE ";
        $sqlstr .= ") ";
        $sqlstr .= "SELECT 'SZ' ";
        $sqlstr .= ",'@TOUGETU' ";
        $sqlstr .= ",ARI.OYA_CD ";
        $sqlstr .= ",'US' ";
        $sqlstr .= ",0 ";
        $sqlstr .= ",TRUNC(@SAGAKU / @GOUKEI * SUM(ARI.SYARYOARARI)) ";
        $sqlstr .= ",0 ";
        $sqlstr .= ",0 ";
        $sqlstr .= ",TRUNC(@SAGAKU / @GOUKEI * SUM(ARI.SYARYOARARI)) ";
        $sqlstr .= ",0 ";
        $sqlstr .= ",0 ";
        $sqlstr .= ",0 ";
        $sqlstr .= ",0 ";
        $sqlstr .= ",0 ";
        $sqlstr .= ",0 ";
        $sqlstr .= ",0 ";
        $sqlstr .= ",0 ";
        $sqlstr .= ",0 ";
        $sqlstr .= ",0 ";
        $sqlstr .= ",0 ";
        $sqlstr .= ",TRUNC(@SAGAKU / @GOUKEI * SUM(ARI.SYARYOARARI)) ";
        $sqlstr .= ",0 ";
        $sqlstr .= ",0 ";
        $sqlstr .= ",SYSDATE ";
        $sqlstr .= ",SYSDATE ";
        $sqlstr .= "FROM   WK_SYASYUBETUARARI ARI ";
        $sqlstr .= "WHERE  ARI.HASEI_MOTO_KB = 'GR' AND ARI.NENGETU = '@TOUGETU' ";
        $sqlstr .= "GROUP BY ARI.OYA_CD ";

        $sqlstr = str_replace("@TOUGETU", $cboYMEnd, $sqlstr);
        $sqlstr = str_replace("@SAGAKU", $intSagaku, $sqlstr);
        $sqlstr = str_replace("@GOUKEI", $intGoukei, $sqlstr);

        return $sqlstr;
    }

    public function fncUriAnbunExtIns_sql($cboYMEnd, $intSagaku)
    {
        $sqlstr = "";
        $sqlstr .= "INSERT INTO WK_SYASYUBETUARARI  ";
        $sqlstr .= "(      MAKECD  ";
        $sqlstr .= ",    NENGETU  ";
        $sqlstr .= ",    OYA_CD  ";
        $sqlstr .= ",    HASEI_MOTO_KB  ";
        $sqlstr .= ",    HONTAIURIAGE  ";
        $sqlstr .= ",    HONTAIGAKU  ";
        $sqlstr .= ",    HONTAINEBIKI  ";
        $sqlstr .= ",    KASOUURIAGE  ";
        $sqlstr .= ",    SYARYOARARI  ";
        $sqlstr .= ",    SYARYOGENKA  ";
        $sqlstr .= ",    KASOUFUZOKU  ";
        $sqlstr .= ",    UNTIN  ";
        $sqlstr .= ",    HOKAN  ";
        $sqlstr .= ",    NOUTEN  ";
        $sqlstr .= ",    HENDOUSYOUREI  ";
        $sqlstr .= ",    SIZYOUTAISAKU  ";
        $sqlstr .= ",    TOUKI_DAISU  ";
        $sqlstr .= ",    TOUKI_ARARI  ";
        $sqlstr .= ",    ZENKI_DAISU  ";
        $sqlstr .= ",    ZENKI_ARARI  ";
        $sqlstr .= ",    TOUGETURIEKI  ";
        $sqlstr .= ",    KAFUGENKA  ";
        $sqlstr .= ",    SITASON  ";
        $sqlstr .= ",    UPD_DATE  ";
        $sqlstr .= ",    CREATE_DATE  ";
        $sqlstr .= ")  ";
        $sqlstr .= "SELECT 'SZ'  ";
        $sqlstr .= ",    '@TOUGETU'  ";
        $sqlstr .= ",    AM.MAXOYA  ";
        $sqlstr .= ",    'U2'  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    V.SAGAKU  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    V.SAGAKU  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    V.SAGAKU  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    SYSDATE  ";
        $sqlstr .= ",    SYSDATE  ";
        $sqlstr .= "FROM   (SELECT (@TTLSAGAKU - NVL(SUM(ARI.HONTAIGAKU),0)) SAGAKU  ";
        $sqlstr .= "FROM   WK_SYASYUBETUARARI ARI  ";
        $sqlstr .= "WHERE  ARI.HASEI_MOTO_KB = 'US'  ";
        $sqlstr .= "AND    ARI.NENGETU = '@TOUGETU'  ";
        $sqlstr .= ") V  ";
        $sqlstr .= ",    (SELECT MAX(OYA_CD) MAXOYA FROM HARARISYUKEIMST) AM  ";
        $sqlstr = str_replace("@TOUGETU", $cboYMEnd, $sqlstr);
        $sqlstr = str_replace("@TTLSAGAKU", $intSagaku, $sqlstr);

        return $sqlstr;
    }

    public function fncSyaryoPcsAnbunSel_sql($cboYMEnd)
    {
        $sqlstr = "";
        $sqlstr .= "SELECT NVL(WK.KAI_PCS,0) - NVL(ARI.ARI_PCS,0) + NVL(CHO.CHO_PCS,0) SAGAKU\n   ";
        $sqlstr .= ",      NVL(ARI.ARI_PCS,0) GENKA\n   ";
        $sqlstr .= "FROM   (SELECT SUM(SYARYO_PCS) KAI_PCS\n   ";
        $sqlstr .= "		FROM   (\n   ";
        $sqlstr .= "				SELECT (CASE WHEN KAI.L_KAMOK_CD = '42111' THEN NVL(KAI.KEIJO_GK,0) END) SYARYO_PCS\n   ";
        $sqlstr .= "				FROM   HKAIKEI KAI\n   ";
        $sqlstr .= "				WHERE  KAI.L_KAMOK_CD = '42111'\n   ";
        $sqlstr .= "		        AND    KAI.KEIJO_DT BETWEEN '@KAISHI' AND '@SYURYO'\n   ";
        $sqlstr .= "				UNION ALL\n   ";
        $sqlstr .= "				SELECT (CASE WHEN KAI.R_KAMOK_CD = '42111' THEN NVL(KAI.KEIJO_GK,0) * -1 END)\n   ";
        $sqlstr .= "				FROM   HKAIKEI KAI\n   ";
        $sqlstr .= "				WHERE  KAI.R_KAMOK_CD = '42111'\n   ";
        $sqlstr .= "		        AND    KAI.KEIJO_DT BETWEEN '@KAISHI' AND '@SYURYO'\n   ";
        $sqlstr .= "		       ) V\n   ";
        $sqlstr .= "       ) WK\n   ";
        $sqlstr .= ",      (SELECT SUM(SYARYOGENKA) ARI_PCS FROM WK_SYASYUBETUARARI WHERE NENGETU = '@TOUGETU') ARI\n   ";
        $sqlstr .= ",      (SELECT SUM(SYARYOARARI) CHO_PCS FROM HARARICHOUSEI WHERE NENGETU = '@TOUGETU' AND OYA_CD = '999') CHO\n   ";

        $sY = substr($cboYMEnd, 0, 4);
        $sM = substr($cboYMEnd, 4, 2);
        $d = date("t", strtotime($sM . '-' . $sY));
        $lastDay_date = $cboYMEnd . $d;
        $sqlstr = str_replace("@KAISHI", $cboYMEnd . "01", $sqlstr);
        $sqlstr = str_replace("@SYURYO", $lastDay_date, $sqlstr);
        $sqlstr = str_replace("@TOUGETU", $cboYMEnd, $sqlstr);

        return $sqlstr;
    }

    public function fncSyaryoPcsAnbunSyasyuIns_sql($cboYMEnd, $T_SAGAKU, $T_GENKA)
    {
        $sqlstr = "";

        $sqlstr .= "INSERT INTO WK_SYASYUBETUARARI  ";
        $sqlstr .= "(      MAKECD  ";
        $sqlstr .= ",    NENGETU  ";
        $sqlstr .= ",    OYA_CD  ";
        $sqlstr .= ",    HASEI_MOTO_KB  ";
        $sqlstr .= ",    HONTAIURIAGE  ";
        $sqlstr .= ",    HONTAIGAKU  ";
        $sqlstr .= ",    HONTAINEBIKI  ";
        $sqlstr .= ",    KASOUURIAGE  ";
        $sqlstr .= ",    SYARYOARARI  ";
        $sqlstr .= ",    SYARYOGENKA  ";
        $sqlstr .= ",    KASOUFUZOKU  ";
        $sqlstr .= ",    UNTIN  ";
        $sqlstr .= ",    HOKAN  ";
        $sqlstr .= ",    NOUTEN  ";
        $sqlstr .= ",    HENDOUSYOUREI  ";
        $sqlstr .= ",    SIZYOUTAISAKU  ";
        $sqlstr .= ",    TOUKI_DAISU  ";
        $sqlstr .= ",    TOUKI_ARARI  ";
        $sqlstr .= ",    ZENKI_DAISU  ";
        $sqlstr .= ",    ZENKI_ARARI  ";
        $sqlstr .= ",    TOUGETURIEKI  ";
        $sqlstr .= ",    KAFUGENKA  ";
        $sqlstr .= ",    SITASON  ";
        $sqlstr .= ",    UPD_DATE  ";
        $sqlstr .= ",    CREATE_DATE  ";
        $sqlstr .= ")  ";
        $sqlstr .= "SELECT 'SZ'  ";
        $sqlstr .= ",    '@TOUGETU'  ";
        $sqlstr .= ",    ARI.OYA_CD  ";
        $sqlstr .= ",    'GS'  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    TRUNC(@SAGAKU / @GOUKEI * SUM(ARI.SYARYOGENKA))  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    SYSDATE  ";
        $sqlstr .= ",    SYSDATE  ";
        $sqlstr .= "FROM   WK_SYASYUBETUARARI ARI  ";
        $sqlstr .= "WHERE  ARI.NENGETU = '@TOUGETU'  ";
        $sqlstr .= "GROUP BY ARI.OYA_CD  ";
        $sqlstr = str_replace("@TOUGETU", $cboYMEnd, $sqlstr);
        $sqlstr = str_replace("@SAGAKU", $T_SAGAKU, $sqlstr);
        $sqlstr = str_replace("@GOUKEI", $T_GENKA, $sqlstr);

        return $sqlstr;
    }

    public function fncSyaryoPcsAnbunExtIns_sql($cboYMEnd, $T_SAGAKU)
    {
        $sqlstr = "";

        $sqlstr .= "INSERT INTO WK_SYASYUBETUARARI  ";
        $sqlstr .= "(      MAKECD  ";
        $sqlstr .= ",    NENGETU  ";
        $sqlstr .= ",    OYA_CD  ";
        $sqlstr .= ",    HASEI_MOTO_KB  ";
        $sqlstr .= ",    HONTAIURIAGE  ";
        $sqlstr .= ",    HONTAIGAKU  ";
        $sqlstr .= ",    HONTAINEBIKI  ";
        $sqlstr .= ",    KASOUURIAGE  ";
        $sqlstr .= ",    SYARYOARARI  ";
        $sqlstr .= ",    SYARYOGENKA  ";
        $sqlstr .= ",    KASOUFUZOKU  ";
        $sqlstr .= ",    UNTIN  ";
        $sqlstr .= ",    HOKAN  ";
        $sqlstr .= ",    NOUTEN  ";
        $sqlstr .= ",    HENDOUSYOUREI  ";
        $sqlstr .= ",    SIZYOUTAISAKU  ";
        $sqlstr .= ",    TOUKI_DAISU  ";
        $sqlstr .= ",    TOUKI_ARARI  ";
        $sqlstr .= ",    ZENKI_DAISU  ";
        $sqlstr .= ",    ZENKI_ARARI  ";
        $sqlstr .= ",    TOUGETURIEKI  ";
        $sqlstr .= ",    KAFUGENKA  ";
        $sqlstr .= ",    SITASON  ";
        $sqlstr .= ",    UPD_DATE  ";
        $sqlstr .= ",    CREATE_DATE  ";
        $sqlstr .= ")  ";
        $sqlstr .= "SELECT 'SZ'  ";
        $sqlstr .= ",    '@TOUGETU'  ";
        $sqlstr .= ",    AM.MAXOYA  ";
        $sqlstr .= ",    'G2'  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    V.SAGAKU  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    SYSDATE  ";
        $sqlstr .= ",    SYSDATE  ";
        $sqlstr .= "FROM   (SELECT (@TTLSAGAKU - NVL(SUM(ARI.SYARYOGENKA),0)) SAGAKU  ";
        $sqlstr .= "FROM   WK_SYASYUBETUARARI ARI  ";
        $sqlstr .= "WHERE  ARI.HASEI_MOTO_KB = 'GS'  ";
        $sqlstr .= "AND    ARI.NENGETU = '@TOUGETU'  ";
        $sqlstr .= ") V  ";
        $sqlstr .= ",    (SELECT MAX(OYA_CD) MAXOYA FROM HARARISYUKEIMST) AM  ";

        $sqlstr = str_replace("@TOUGETU", $cboYMEnd, $sqlstr);
        $sqlstr = str_replace("@TTLSAGAKU", $T_SAGAKU, $sqlstr);

        return $sqlstr;
    }

    public function fncKasouPcsAnbunSel_sql($cboYMEnd)
    {
        $sqlstr = "";
        $sqlstr .= "SELECT NVL(WK.KAI_PCS,0) - NVL(ARI.ARI_PCS,0) SAGAKU\n   ";
        $sqlstr .= ",      NVL(ARI.ARI_PCS,0) GENKA\n   ";
        $sqlstr .= "FROM   (SELECT SUM(KASOU_PCS) KAI_PCS\n   ";
        $sqlstr .= "		FROM   (\n   ";
        $sqlstr .= "				SELECT (CASE WHEN KAI.L_KAMOK_CD = '42112' THEN NVL(KAI.KEIJO_GK,0) END) KASOU_PCS\n   ";
        $sqlstr .= "				FROM   HKAIKEI KAI\n   ";
        $sqlstr .= "				WHERE  KAI.L_KAMOK_CD = '42112'\n   ";
        $sqlstr .= "		        AND    KAI.KEIJO_DT BETWEEN '@KAISHI' AND '@SYURYO'\n   ";
        $sqlstr .= "				UNION ALL\n   ";
        $sqlstr .= "				SELECT (CASE WHEN KAI.R_KAMOK_CD = '42112' THEN NVL(KAI.KEIJO_GK,0) * -1 END)\n   ";
        $sqlstr .= "				FROM   HKAIKEI KAI\n   ";
        $sqlstr .= "				WHERE  KAI.R_KAMOK_CD = '42112'\n   ";
        $sqlstr .= "		        AND    KAI.KEIJO_DT BETWEEN '@KAISHI' AND '@SYURYO'\n   ";
        $sqlstr .= "		       ) V\n   ";
        $sqlstr .= "       ) WK\n   ";
        $sqlstr .= ",      (SELECT SUM(KAFUGENKA) ARI_PCS FROM WK_SYASYUBETUARARI WHERE NENGETU = '@TOUGETU') ARI\n   ";

        $sY = substr($cboYMEnd, 0, 4);
        $sM = substr($cboYMEnd, 4, 2);
        $d = date("t", strtotime($sM . '-' . $sY));
        $lastDay_date = $cboYMEnd . $d;
        $sqlstr = str_replace("@KAISHI", $cboYMEnd . "01", $sqlstr);
        $sqlstr = str_replace("@TOUGETU", $cboYMEnd, $sqlstr);
        $sqlstr = str_replace("@SYURYO", $lastDay_date, $sqlstr);

        return $sqlstr;
    }

    public function fncKasouPcsAnbunSyasyuIns_sql($cboYMEnd, $T_SAGAKU, $T_GENKA)
    {
        $sqlstr = "";
        $sqlstr .= "INSERT INTO WK_SYASYUBETUARARI  ";
        $sqlstr .= "(      MAKECD  ";
        $sqlstr .= ",    NENGETU  ";
        $sqlstr .= ",    OYA_CD  ";
        $sqlstr .= ",    HASEI_MOTO_KB  ";
        $sqlstr .= ",    HONTAIURIAGE  ";
        $sqlstr .= ",    HONTAIGAKU  ";
        $sqlstr .= ",    HONTAINEBIKI  ";
        $sqlstr .= ",    KASOUURIAGE  ";
        $sqlstr .= ",    SYARYOARARI  ";
        $sqlstr .= ",    SYARYOGENKA  ";
        $sqlstr .= ",    KASOUFUZOKU  ";
        $sqlstr .= ",    UNTIN  ";
        $sqlstr .= ",    HOKAN  ";
        $sqlstr .= ",    NOUTEN  ";
        $sqlstr .= ",    HENDOUSYOUREI  ";
        $sqlstr .= ",    SIZYOUTAISAKU  ";
        $sqlstr .= ",    TOUKI_DAISU  ";
        $sqlstr .= ",    TOUKI_ARARI  ";
        $sqlstr .= ",    ZENKI_DAISU  ";
        $sqlstr .= ",    ZENKI_ARARI  ";
        $sqlstr .= ",    TOUGETURIEKI  ";
        $sqlstr .= ",    KAFUGENKA  ";
        $sqlstr .= ",    SITASON  ";
        $sqlstr .= ",    UPD_DATE  ";
        $sqlstr .= ",    CREATE_DATE  ";
        $sqlstr .= ")  ";
        $sqlstr .= "SELECT 'SZ'  ";
        $sqlstr .= ",    '@TOUGETU'  ";
        $sqlstr .= ",    ARI.OYA_CD  ";
        $sqlstr .= ",    'KS'  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    TRUNC(@SAGAKU / @GOUKEI * SUM(ARI.KAFUGENKA))  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    SYSDATE  ";
        $sqlstr .= ",    SYSDATE  ";
        $sqlstr .= "FROM   WK_SYASYUBETUARARI ARI  ";
        $sqlstr .= "WHERE  ARI.NENGETU = '@TOUGETU'  ";
        $sqlstr .= "GROUP BY ARI.OYA_CD  ";
        $sqlstr = str_replace("@TOUGETU", $cboYMEnd, $sqlstr);
        $sqlstr = str_replace("@SAGAKU", $T_SAGAKU, $sqlstr);
        $sqlstr = str_replace("@GOUKEI", $T_GENKA, $sqlstr);

        return $sqlstr;
    }

    public function fncKasouPcsAnbunExtIns_sql($cboYMEnd, $intSagaku)
    {
        $sqlstr = "";


        $sqlstr .= "INSERT INTO WK_SYASYUBETUARARI  ";
        $sqlstr .= "(      MAKECD  ";
        $sqlstr .= ",    NENGETU  ";
        $sqlstr .= ",    OYA_CD  ";
        $sqlstr .= ",    HASEI_MOTO_KB  ";
        $sqlstr .= ",    HONTAIURIAGE  ";
        $sqlstr .= ",    HONTAIGAKU  ";
        $sqlstr .= ",    HONTAINEBIKI  ";
        $sqlstr .= ",    KASOUURIAGE  ";
        $sqlstr .= ",    SYARYOARARI  ";
        $sqlstr .= ",    SYARYOGENKA  ";
        $sqlstr .= ",    KASOUFUZOKU  ";
        $sqlstr .= ",    UNTIN  ";
        $sqlstr .= ",    HOKAN  ";
        $sqlstr .= ",    NOUTEN  ";
        $sqlstr .= ",    HENDOUSYOUREI  ";
        $sqlstr .= ",    SIZYOUTAISAKU  ";
        $sqlstr .= ",    TOUKI_DAISU  ";
        $sqlstr .= ",    TOUKI_ARARI  ";
        $sqlstr .= ",    ZENKI_DAISU  ";
        $sqlstr .= ",    ZENKI_ARARI  ";
        $sqlstr .= ",    TOUGETURIEKI  ";
        $sqlstr .= ",    KAFUGENKA  ";
        $sqlstr .= ",    SITASON  ";
        $sqlstr .= ",    UPD_DATE  ";
        $sqlstr .= ",    CREATE_DATE  ";
        $sqlstr .= ")  ";
        $sqlstr .= "SELECT 'SZ'  ";
        $sqlstr .= ",    '@TOUGETU'  ";
        $sqlstr .= ",    AM.MAXOYA  ";
        $sqlstr .= ",    'K2'  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    V.SAGAKU  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    SYSDATE  ";
        $sqlstr .= ",    SYSDATE  ";
        $sqlstr .= "FROM   (SELECT (@TTLSAGAKU - NVL(SUM(ARI.KAFUGENKA),0)) SAGAKU  ";
        $sqlstr .= "FROM   WK_SYASYUBETUARARI ARI  ";
        $sqlstr .= "WHERE  ARI.HASEI_MOTO_KB = 'KS'  ";
        $sqlstr .= "AND    ARI.NENGETU = '@TOUGETU'  ";
        $sqlstr .= ") V  ";
        $sqlstr .= ",    (SELECT MAX(OYA_CD) MAXOYA FROM HARARISYUKEIMST) AM  ";
        $sqlstr = str_replace("@TOUGETU", $cboYMEnd, $sqlstr);
        $sqlstr = str_replace("@TTLSAGAKU", $intSagaku, $sqlstr);

        return $sqlstr;
    }

    public function fncUnchinIns_sql($cboYMEnd)
    {
        $sqlstr = "";
        $sqlstr .= "INSERT INTO WK_SYASYUBETUARARI  ";
        $sqlstr .= "(      MAKECD  ";
        $sqlstr .= ",    NENGETU  ";
        $sqlstr .= ",    OYA_CD  ";
        $sqlstr .= ",    HASEI_MOTO_KB  ";
        $sqlstr .= ",    HONTAIURIAGE  ";
        $sqlstr .= ",    HONTAIGAKU  ";
        $sqlstr .= ",    HONTAINEBIKI  ";
        $sqlstr .= ",    KASOUURIAGE  ";
        $sqlstr .= ",    SYARYOARARI  ";
        $sqlstr .= ",    SYARYOGENKA  ";
        $sqlstr .= ",    KASOUFUZOKU  ";
        $sqlstr .= ",    UNTIN  ";
        $sqlstr .= ",    HOKAN  ";
        $sqlstr .= ",    NOUTEN  ";
        $sqlstr .= ",    HENDOUSYOUREI  ";
        $sqlstr .= ",    SIZYOUTAISAKU  ";
        $sqlstr .= ",    TOUKI_DAISU  ";
        $sqlstr .= ",    TOUKI_ARARI  ";
        $sqlstr .= ",    ZENKI_DAISU  ";
        $sqlstr .= ",    ZENKI_ARARI  ";
        $sqlstr .= ",    TOUGETURIEKI  ";
        $sqlstr .= ",    KAFUGENKA  ";
        $sqlstr .= ",    SITASON  ";
        $sqlstr .= ",    UPD_DATE  ";
        $sqlstr .= ",    CREATE_DATE  ";
        $sqlstr .= ")  ";
        $sqlstr .= "SELECT 'SZ'  ";
        $sqlstr .= ",    '@TOUGETU'  ";
        $sqlstr .= ",    ARI.OYA_CD  ";
        $sqlstr .= ",    'UN'  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    TRUNC(NVL(HONTAI,0) * ASY.UNTIN_RITU)  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    0  ";
        $sqlstr .= ",    SYSDATE  ";
        $sqlstr .= ",    SYSDATE  ";
        $sqlstr .= "FROM   (SELECT OYA_CD  ";
        $sqlstr .= ",    SUM(HONTAIGAKU) HONTAI  ";
        $sqlstr .= ",    SUM(HONTAIURIAGE) DAISU  ";
        $sqlstr .= "FROM WK_SYASYUBETUARARI  ";
        $sqlstr .= "WHERE NENGETU = '@TOUGETU'  ";
        $sqlstr .= " GROUP BY OYA_CD) ARI  ";
        $sqlstr .= "LEFT JOIN HARARISYUKEIMST ASY  ";
        $sqlstr .= "ON     ASY.OYA_CD = ARI.OYA_CD  ";
        $sqlstr = str_replace("@TOUGETU", $cboYMEnd, $sqlstr);

        return $sqlstr;
    }

    public function fncArariIns_sql($cboYMEnd)
    {
        $ttC = new ClsComFnc();
        $sqlstr = "";

        $sqlstr .= "INSERT INTO HSYASYUBETUARARI  ";
        $sqlstr .= "(      MAKECD  ";
        $sqlstr .= ",    NENGETU  ";
        $sqlstr .= ",    OYA  ";
        $sqlstr .= ",    HONTAIURIAGE  ";
        $sqlstr .= ",    HONTAIGAKU  ";
        $sqlstr .= ",    HONTAINEBIKI  ";
        $sqlstr .= ",    KASOUURIAGE  ";
        $sqlstr .= ",    SYARYOARARI  ";
        $sqlstr .= ",    SYARYOGENKA  ";
        $sqlstr .= ",    KASOUFUZOKU  ";
        $sqlstr .= ",    UNTIN  ";
        $sqlstr .= ",    HOKAN  ";
        $sqlstr .= ",    NOUTEN  ";
        $sqlstr .= ",    HENDOUSYOUREI  ";
        $sqlstr .= ",    SIZYOUTAISAKU  ";
        $sqlstr .= ",    TOUGETURIEKI  ";
        $sqlstr .= ",    BIKOU  ";
        $sqlstr .= ",    UPD_DATE  ";
        $sqlstr .= ",    CREATE_DATE  ";
        $sqlstr .= ",    UPD_SYA_CD  ";
        $sqlstr .= ",    UPD_PRG_ID  ";
        $sqlstr .= ",    UPD_CLT_NM  ";
        $sqlstr .= ")  ";
        $sqlstr .= "SELECT MAKECD  ";
        $sqlstr .= ",    NENGETU  ";
        $sqlstr .= ",    OYA_CD  ";
        $sqlstr .= ",    SUM(HONTAIURIAGE)  ";
        $sqlstr .= ",    SUM(HONTAIGAKU)  ";
        $sqlstr .= ",    SUM(HONTAINEBIKI)  ";
        $sqlstr .= ",    SUM(KASOUURIAGE)  ";
        $sqlstr .= ",    SUM(SYARYOARARI) - SUM(SYARYOGENKA) - SUM(KAFUGENKA) - SUM(UNTIN)  ";
        $sqlstr .= ",    SUM(SYARYOGENKA)  ";
        $sqlstr .= ",    SUM(KAFUGENKA)  ";
        $sqlstr .= ",    SUM(UNTIN)  ";
        $sqlstr .= ",    SUM(HOKAN)  ";
        $sqlstr .= ",    SUM(NOUTEN)  ";
        $sqlstr .= ",    SUM(HENDOUSYOUREI)  ";
        $sqlstr .= ",    SUM(SIZYOUTAISAKU)  ";
        $sqlstr .= ",    SUM(TOUGETURIEKI) - SUM(SYARYOGENKA) - SUM(KAFUGENKA)  ";
        $sqlstr .= ",    NULL  ";
        $sqlstr .= ",    SYSDATE  ";
        $sqlstr .= ",    SYSDATE  ";
        $sqlstr .= ",    '@UPDUSER'  ";
        $sqlstr .= ",    '@UPDAPP'  ";
        $sqlstr .= ",    '@UPDCLT'  ";
        $sqlstr .= "FROM   WK_SYASYUBETUARARI  ";
        $sqlstr .= "WHERE  NENGETU = '@TOUGETU'  ";
        $sqlstr .= "GROUP BY MAKECD  ";
        $sqlstr .= ",      NENGETU  ";
        $sqlstr .= ",      OYA_CD  ";

        $sqlstr = str_replace("@TOUGETU", $cboYMEnd, $sqlstr);
        $sqlstr = str_replace("@UPDUSER", $ttC->FncNv($this->GS_LOGINUSER['strUserID']), $sqlstr);
        $sqlstr = str_replace("@UPDAPP", "SyasyuArariChkList", $sqlstr);
        $sqlstr = str_replace("@UPDCLT", $ttC->FncNv($this->GS_LOGINUSER['strClientNM']), $sqlstr);

        return $sqlstr;
    }
    //20150915 yinhuaiyu mod E
    public function fncArariekiListSel_sql($cboYMEnd, $cboYMStart)
    {
        $sqlstr = "";

        // 不好用的sql
        $sqlstr .= "SELECT  SUBSTR(V.TOUGETU,1,4) NEN\n  ";
        $sqlstr .= ",     SUBSTR(V.TOUGETU,5,2) TUKI\n  ";
        $sqlstr .= ",     '1' KBN\n  ";
        $sqlstr .= ",     ('(' || SUBSTR('@KISYU',1,4) || '.' || SUBSTR('@KISYU',5,2) || ' ～ ' || SUBSTR('@SYORITUKI',1,4) || '.' || SUBSTR('@SYORITUKI',5,2) || ')') TAISYO_NEN\n";
        $sqlstr .= ",     V.OYA_CD\n  ";
        $sqlstr .= ",     V.SS_NAME\n  ";
        $sqlstr .= ",     SUM(V.DAISU) DAISU\n  ";
        $sqlstr .= ",     SUM(V.TKI_DAISU) TKI_DAI\n  ";
        $sqlstr .= ",     SUM(V.ZKI_DAISU) ZKI_DAI\n  ";
        $sqlstr .= ",     SUM(V.URIAGE_KIN) URIAGEKIN\n  ";
        $sqlstr .= ",     DECODE(SUM(V.DAISU),0,0,ROUND(SUM(V.URIAGE_KIN) / SUM(V.DAISU) / 1000)) URI_DAI\n  ";
        $sqlstr .= ",     SUM(V.ARARI) ARARI\n  ";
        $sqlstr .= ",     DECODE(SUM(V.DAISU),0,0,ROUND(SUM(V.ARARI) / SUM(V.DAISU) / 1000)) ARARI_DAI\n  ";
        $sqlstr .= ",     SUM(V.RYUHO) RYUHO\n  ";
        $sqlstr .= ",     DECODE(SUM(V.DAISU),0,0,ROUND(SUM(V.RYUHO) / SUM(V.DAISU) / 1000)) RYUHO_DAI\n  ";
        $sqlstr .= ",     SUM(V.TOU_ARARI) TOUARA\n  ";
        $sqlstr .= ",     DECODE(SUM(V.DAISU),0,0,ROUND(SUM(V.TOU_ARARI) / SUM(V.DAISU) / 1000)) TOUARA_DAI\n  ";
        $sqlstr .= ",     SUM(V.TKI_ARARI) TKIARA\n  ";
        $sqlstr .= ",     DECODE(SUM(V.TKI_DAISU),0,0,ROUND(SUM(V.TKI_ARARI) / SUM(V.TKI_DAISU) / 1000)) TKIARA_DAI\n  ";
        $sqlstr .= ",     SUM(V.ZKI_ARARI) ZKIARA\n  ";
        $sqlstr .= ",     DECODE(SUM(V.ZKI_DAISU),0,0,ROUND(SUM(V.ZKI_ARARI) / SUM(V.ZKI_DAISU) / 1000)) ZKIARA_DAI\n  ";
        $sqlstr .= ",     CHO.HONTAIGAKU\n  ";
        $sqlstr .= ",     CHO.SYARYOARARI\n  ";
        $sqlstr .= "FROM    (\n  ";
        $sqlstr .= "SELECT '@SYORITUKI' TOUGETU\n  ";
        $sqlstr .= ",    MST.OYA_CD\n  ";
        $sqlstr .= ",    MST.SS_NAME\n  ";
        $sqlstr .= ",    SUM(ARI.HONTAIURIAGE) DAISU\n  ";
        $sqlstr .= ",    SUM(ARI.HONTAIGAKU) - SUM(ARI.HONTAINEBIKI) + SUM(ARI.KASOUURIAGE) URIAGE_KIN\n  ";
        $sqlstr .= ",    SUM(ARI.SYARYOARARI) + SUM(ARI.UNTIN) ARARI\n  ";
        $sqlstr .= ",    SUM(ARI.UNTIN) RYUHO\n  ";
        $sqlstr .= ",    SUM(ARI.SYARYOARARI) TOU_ARARI\n  ";
        $sqlstr .= ",    0 TKI_ARARI\n  ";
        $sqlstr .= ",    0 TKI_DAISU\n  ";
        $sqlstr .= ",    0 ZKI_ARARI\n  ";
        $sqlstr .= ",    0 ZKI_DAISU\n  ";
        $sqlstr .= "FROM   HARARISYUKEIMST MST\n  ";
        $sqlstr .= "LEFT JOIN HSYASYUBETUARARI ARI\n  ";
        $sqlstr .= "ON     ARI.OYA = MST.OYA_CD\n  ";
        $sqlstr .= "AND    ARI.NENGETU = '@SYORITUKI'\n  ";
        $sqlstr .= "WHERE  MST.DISP_NO IS NOT NULL\n  ";
        $sqlstr .= "GROUP BY MST.OYA_CD\n  ";
        $sqlstr .= ",      MST.SS_NAME\n  ";
        $sqlstr .= "UNION ALL\n  ";
        $sqlstr .= "SELECT '@SYORITUKI'\n  ";
        $sqlstr .= ",    MST.OYA_CD\n  ";
        $sqlstr .= ",    MST.SS_NAME\n  ";
        $sqlstr .= ",    0\n  ";
        $sqlstr .= ",    0\n  ";
        $sqlstr .= ",    0\n  ";
        $sqlstr .= ",    0\n  ";
        $sqlstr .= ",    0\n  ";
        $sqlstr .= ",    SUM(ARI.SYARYOARARI)\n  ";
        $sqlstr .= ",    SUM(ARI.HONTAIURIAGE)\n  ";
        $sqlstr .= ",    0\n  ";
        $sqlstr .= ",    0\n  ";
        $sqlstr .= "FROM   HARARISYUKEIMST MST\n  ";
        $sqlstr .= "LEFT JOIN HSYASYUBETUARARI ARI\n  ";
        $sqlstr .= "ON     ARI.OYA = MST.OYA_CD\n  ";
        $sqlstr .= "AND    ARI.NENGETU BETWEEN '@KISYU' AND '@SYORITUKI'\n  ";
        $sqlstr .= "WHERE  MST.DISP_NO IS NOT NULL\n  ";
        $sqlstr .= "GROUP BY MST.OYA_CD\n  ";
        $sqlstr .= ",      MST.SS_NAME\n  ";
        $sqlstr .= "UNION ALL\n  ";
        $sqlstr .= "SELECT '@SYORITUKI'\n  ";
        $sqlstr .= ",    MST.OYA_CD\n  ";
        $sqlstr .= ",    MST.SS_NAME\n  ";
        $sqlstr .= ",    0\n  ";
        $sqlstr .= ",    0\n  ";
        $sqlstr .= ",    0\n  ";
        $sqlstr .= ",    0\n  ";
        $sqlstr .= ",    0\n  ";
        $sqlstr .= ",    0\n  ";
        $sqlstr .= ",    0\n  ";
        $sqlstr .= ",    SUM(ARI.SYARYOARARI)\n  ";
        $sqlstr .= ",    SUM(ARI.HONTAIURIAGE)\n  ";
        $sqlstr .= "FROM   HARARISYUKEIMST MST\n  ";
        $sqlstr .= "LEFT JOIN HSYASYUBETUARARI ARI\n  ";
        $sqlstr .= "ON     ARI.OYA = MST.OYA_CD\n  ";
        $sqlstr .= "AND    ARI.NENGETU BETWEEN '@ZENKISYU' AND '@ZENSYORITUKI'\n  ";
        $sqlstr .= " WHERE  MST.DISP_NO IS NOT NULL\n  ";
        $sqlstr .= "GROUP BY MST.OYA_CD\n  ";
        $sqlstr .= ",      MST.SS_NAME\n  ";
        $sqlstr .= ") V\n  ";
        $sqlstr .= "LEFT JOIN HARARICHOUSEI CHO\n  ";
        $sqlstr .= "ON        CHO.NENGETU = '@SYORITUKI'\n  ";
        $sqlstr .= "AND       CHO.OYA_CD = '999'\n  ";
        $sqlstr .= "LEFT JOIN HARARISYUKEIMST ASU\n  ";
        $sqlstr .= "ON        ASU.OYA_CD = V.OYA_CD\n  ";
        $sqlstr .= "GROUP BY  V.TOUGETU\n  ";
        $sqlstr .= ",       V.OYA_CD\n  ";
        $sqlstr .= ",       V.SS_NAME\n  ";
        $sqlstr .= ",       CHO.HONTAIGAKU\n  ";
        $sqlstr .= ",       CHO.SYARYOARARI\n  ";
        $sqlstr .= ",       ASU.DISP_NO\n  ";
        $sqlstr .= "ORDER BY ASU.DISP_NO\n  ";

        $sY = substr($cboYMEnd, 0, 4);
        $sM = substr($cboYMEnd, 4, 2);
        $sqlstr = str_replace("@SYORITUKI", $cboYMEnd, $sqlstr);
        $sqlstr = str_replace("@KISYU", $cboYMStart, $sqlstr);
        $sqlstr = str_replace("@ZENKISYU", round((int) $cboYMStart) - 100, $sqlstr);
        $sqlstr = str_replace("@ZENSYORITUKI", ((int) $sY - 1) . $sM, $sqlstr);

        return $sqlstr;
    }

}