<?php
/**
 * 説明：
 *
 *
 * @author jinmingai
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20150526           ---                       新規                           FCSDL
 * 20150611           ---                       抽出条件に「登録日」を追加     HM
 * 20150619           ---                       契約者情報検索を１つに纏める   HM
 * 20150619           ---                       予想距離計算方法の見直し       HM
 * --------------------------------------------------------------------------------------------
 */

// 共通クラスの読込み
namespace App\Model\SDH;

use App\Model\Component\ClsComDb;
use App\Model\Component\common;

class SDH01_02 extends ClsComDb
{
    private $common = '';

    /**
     * 契約者取得.
     *
     * @param {String}
     * 注文書ＮＯ:$CMN_NO
     * 販社お客様No:$DLRCSRNO
     * ＶＩＮ－ＷＭＩＶＤＳ:$VIN_WMIVDS
     * ＶＩＮ－ＶＩＳ:$VIN_VIS
     *
     * @return {parent} result
     */
    public function m_select_keiyakusya($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS)
    {
        $str_sql = $this->m_select_keiyakusya_sql($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS);

        return parent::select($str_sql);
    }

    //20150619 Del Start
//    public function m_select_all($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS) {
//        $str_sql = $this -> m_select_all_sql($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS);
//        return parent::select($str_sql);
//    }
    //20150619 Del End

    /**
     * HITメモ取得.
     *
     * @param {String} $DLRCSRNO   販社お客様No
     * @param {String} $VIN_WMIVDS ＶＩＮ－ＷＭＩＶＤＳ
     * @param {String} $VIN_VIS    ＶＩＮ－ＶＩＳ
     *
     * @return {Array} 検索結果
     */
    public function m_select_hit_memo($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS)
    {
        $str_sql = $this->m_select_hit_memo_sql($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS);

        return parent::select($str_sql);
    }

    /**
     * HIT進捗状況:.
     *
     * @param {String} $VIN_WMIVDS ＶＩＮ－ＷＭＩＶＤＳ
     * @param {String} $VIN_VIS    ＶＩＮ－ＶＩＳ
     *
     * @return {Array} 検索結果
     */
    public function m_select_hit_situ($VIN_WMIVDS, $VIN_VIS, $KTNCD)
    {
        $str_sql = $this->m_select_hit_situ_sql($VIN_WMIVDS, $VIN_VIS, $KTNCD);

        return parent::select($str_sql);
    }

    public function m_select_hit_situ_sql($VIN_WMIVDS, $VIN_VIS, $KTNCD)
    {
        $str_sql = '';
        $str_sql .= 'SELECT ' . "\r\n";
        $str_sql .= 'OBJ_YM YM, ' . "\r\n";
        $str_sql .= 'CASE ' . "\r\n";

        $str_sql .= "WHEN rtrim(NKO_DT) IS NOT NULL AND substr(URG_JI_UKK_KTN_CD,1,2) = substr('" . $KTNCD . "',1,2) THEN '自拠点入庫'" . "\r\n";
        $str_sql .= "WHEN rtrim(NKO_DT) IS NOT NULL AND substr(URG_JI_UKK_KTN_CD,1,2) <> substr('" . $KTNCD . "',1,2) THEN '他拠点入庫'" . "\r\n";

        $str_sql .= "WHEN STY_STS_KB IS NULL THEN '不明・未コンタクト'" . "\r\n";
        $str_sql .= "WHEN STY_STS_KB='0' THEN '不明・未コンタクト'" . "\r\n";
        $str_sql .= " WHEN STY_STS_KB='4' THEN '自社代替'" . "\r\n";
        $str_sql .= "WHEN STY_STS_KB='6' THEN '他社入庫（その他）'" . "\r\n";
        $str_sql .= "WHEN STY_STS_KB='7' THEN '他社代替'" . "\r\n";
        $str_sql .= "WHEN STY_STS_KB='8' THEN '転売・転出'" . "\r\n";
        $str_sql .= "WHEN STY_STS_KB='9' THEN '廃車'" . "\r\n";
        $str_sql .= "WHEN STY_STS_KB='A' THEN 'コンタクト中（入庫促進）'" . "\r\n";
        $str_sql .= "WHEN STY_STS_KB='B' THEN '他社入庫（知人、血縁）'" . "\r\n";
        $str_sql .= "WHEN STY_STS_KB='C' THEN '他社入庫（金額）'" . "\r\n";
        $str_sql .= "WHEN STY_STS_KB='D' THEN '今回車検を受けない'" . "\r\n";
        $str_sql .= "WHEN STY_STS_KB='E' THEN 'コンタクト中（代促）'" . "\r\n";
        $str_sql .= "ELSE ''" . "\r\n";
        $str_sql .= 'END  as SITU' . "\r\n";
        $str_sql .= 'from BTH28SD1' . "\r\n";
        $str_sql .= ' where ' . "\r\n";
        $str_sql .= " VIN_SDI_KAT ='" . $VIN_WMIVDS . "'" . "\r\n";
        $str_sql .= " and VIN_RBN='" . $VIN_VIS . "'" . "\r\n";
        $str_sql .= ' order by' . "\r\n";
        $str_sql .= ' OBJ_YM DESC' . "\r\n";

        return $str_sql;
    }

    //20150619 Del Start
//    public function m_select_all_sql($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS) {
//        $str_sql = "";
//        $str_sql .= "SELECT " . "\r\n";
//        $str_sql .= "c01.CSRKNANM," . "\r\n";

    //        $str_sql .= "c04.KNR_STRCD," . "\r\n";
//        $str_sql .= "t1.KYOTN_RKN KNR_STRNM," . "\r\n";
//        $str_sql .= "c04.SRV_SRVSTRCD," . "\r\n";
//        $str_sql .= "t2.KYOTN_RKN SRV_SRVSTRNM," . "\r\n";
//        $str_sql .= "t3.SCD_NM VCLRGTNM_LAND," . "\r\n";
//        $str_sql .= "t4.SYAIN_NO," . "\r\n";
//        $str_sql .= "t4.SYAIN_KNJ_SEI," . "\r\n";
//        $str_sql .= "t4.SYAIN_KNJ_MEI," . "\r\n";
//        $str_sql .= "t5.SCD_NM SEIBETU" . "\r\n";
//        $str_sql .= "from" . "\r\n";
//        $str_sql .= "M41C04 c04," . "\r\n";
//        $str_sql .= "M41C01 c01," . "\r\n";
//        $str_sql .= "M27M01 T1," . "\r\n";
//        $str_sql .= "M27M01 T2," . "\r\n";
//        $str_sql .= "M27M14 T3," . "\r\n";
//        $str_sql .= "M29MA4 T4," . "\r\n";
//        $str_sql .= "M27M14 T5" . "\r\n";
//        $str_sql .= "where " . "\r\n";
//        $str_sql .= "c04.DLRCSRNO   = '" . $DLRCSRNO . "' AND" . "\r\n";
//        $str_sql .= "c04.VIN_WMIVDS = '" . $VIN_WMIVDS . "' AND" . "\r\n";
//        $str_sql .= "c04.VIN_VIS    = '" . $VIN_VIS . "' AND" . "\r\n";
//        $str_sql .= "c04.DLRCSRNO   = c01.DLRCSRNO(+) AND" . "\r\n";
//        $str_sql .= "t1.KYOTN_CD(+)   = c04.KNR_STRCD AND" . "\r\n";
//        $str_sql .= "t1.ES_KB(+)      = 'E' AND" . "\r\n";
//        $str_sql .= "t2.KYOTN_CD(+)  = c04.SRV_SRVSTRCD AND" . "\r\n";
//        $str_sql .= "t2.ES_KB(+)      = 'S' AND" . "\r\n";
//        $str_sql .= "t3.SCD_SYSID(+)  = 'Z' AND" . "\r\n";
//        $str_sql .= "t3.SCD_ID(+)     = 'RIKUJI' AND" . "\r\n";
//        $str_sql .= "t3.SCD_VAL(+)    = c04.VCLRGTNO_LAND AND" . "\r\n";
//        $str_sql .= " t4.HANSH_CD(+)   = '3634' AND " . "\r\n";
//        $str_sql .= " t4.SYAIN_NO(+)   = c04.KNR_BUSMANCD AND " . "\r\n";
//        $str_sql .= "t5.SCD_SYSID(+) = 'M' AND " . "\r\n";
//        $str_sql .= "t5.SCD_ID(+)      = 'SEIBETU_KB' AND" . "\r\n";
//        $str_sql .= "t5.SCD_VAL(+)     = c01.CSRDOSID" . "\r\n";
//
//        return $str_sql;
//    }
    //20150619 Del End

    /**
     * 契約者取得.
     *
     * @param {String}
     *  注文書ＮＯ:$CMN_NO
     *  販社お客様No:$DLRCSRNO
     *  ＶＩＮ－ＷＭＩＶＤＳ:$VIN_WMIVDS
     *  ＶＩＮ－ＶＩＳ:$VIN_VIS
     *
     * @return {String} select文
     */
    public function m_select_keiyakusya_sql($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS)
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= "  M41C01.CSRNM1 || ' ' || M41C01.CSRNM2 AS CSRNM1_CSRNM2, ";
        $str_sql .= '  M41C01.CSRRANK, ';
        $str_sql .= '  M41C01.DLRCSRNO, ';
        $str_sql .= '  M41C01.CSRAD1 || M41C01.CSRAD2|| M41C01.CSRAD3 AS CSRAD, ';
        $str_sql .= '  M41C01.PSTMINNO, ';
        $str_sql .= '  M41C01.BRTDT, ';
        $str_sql .= "  M41C01.CUS_HOM_TEL_ACD || '-' || M41C01.CUS_HOM_TEL_CCD || '-' || M41C01.CUS_HOM_TEL_KNY_NO AS TEL_NO, ";
        $str_sql .= '  M41C01.EMAIL1, ';
        $str_sql .= "  M41C01.MOB_TEL_ACD || '-' || M41C01.MOB_TEL_CCD || '-' || M41C01.MOB_TEL_KNY_NO AS MOB_TEL_NO, ";
        $str_sql .= '  M41C01.MOB_MAL, ';
        $str_sql .= '  M41C03.VCLNM, ';
        $str_sql .= '  M41C03.FRGMH, ';
        $str_sql .= '  M41C03.VCLIPEDT, ';
        $str_sql .= '  M41C04.VIN_WMIVDS, ';
        $str_sql .= '  M41C04.VIN_VIS, ';
        $str_sql .= "  M41C04.VIN_WMIVDS || '-' || M41C04.VIN_VIS AS SYADAI_BG, ";
        $str_sql .= '  M41C04.NKSIN1_SOKOKM, ';
        $str_sql .= '  M41C04.XH10CAID, ';
        $str_sql .= '  M41C04.XG11KOTEIID, ';
        $str_sql .= '  M41C04.DM_FKA_KB, ';
        $str_sql .= '  M41C04.XHKTGKBN, ';
        //20150717 Update Start
        $str_sql .= 'M41C04.KNR_STRCD,' . "\r\n";
        $str_sql .= 'M41C04.SRV_SRVSTRCD,' . "\r\n";
        //20150717 Update End
        $str_sql .= '  M41C04.KEIYK_NM as KYK_CUS_NM1, ';
        $str_sql .= '  M41C04.VCLRGTNO_SYU, ';
        $str_sql .= '  M41C04.VCLRGTNO_KANA, ';
        $str_sql .= '  M41C04.VCLRGTNO_REN, ';

        $str_sql .= '  M41C04.VCLMTCDS, ';
        $str_sql .= '  M41C04.VSLFRMID,';
        $str_sql .= '  M41C04.MAS_DT ';

        //20150619 Update Start
        $str_sql .= ',';
        $str_sql .= 'M41C01.CSRKNANM,' . "\r\n";
        $str_sql .= 't1.KYOTN_RKN KNR_STRNM,';
        $str_sql .= 't2.KYOTN_RKN SRV_SRVSTRNM,';
        $str_sql .= 't3.SCD_NM VCLRGTNM_LAND,';
        $str_sql .= 't4.SYAIN_NO,';
        $str_sql .= "t4.SYAIN_KNJ_SEI||' '||t4.SYAIN_KNJ_MEI SYAIN_KNJ_SEI_KNA_MEI,";
        $str_sql .= 't5.SCD_NM SEIBETU, ';
        //20150619 Update End

        //20150611 Update Start
        $str_sql .= '  M41C04.KSA_RUN_KYR, ';
        $str_sql .= '  M41E10.TOU_DT, ';
        //20150619 Update Start
//        $str_sql .= "  CASE WHEN rtrim(M41C04.KSA_DT) IS NOT NULL THEN substr(M41C04.KSA_DT,1,4)||'/'||substr(M41C04.KSA_DT,5,2) ELSE CASE WHEN rtrim(M41C04.NOUSDAT) IS NOT NULL THEN substr(M41C04.NOUSDAT,1,4)||'/'||substr(M41C04.NOUSDAT,5,2) ELSE ' ' END END KSA_DT ";
//        $str_sql .= "  CASE WHEN rtrim(M41C04.KSA_DT) IS NOT NULL THEN substr(M41C04.KSA_DT,1,4)||'/'||substr(M41C04.KSA_DT,5,2) ELSE CASE WHEN rtrim(M41C04.NOUSDAT) IS NOT NULL THEN substr(M41C04.NOUSDAT,1,4)||'/'||substr(M41C04.NOUSDAT,5,2) ELSE CASE WHEN M41C04.NKSIN1_AWTDT IS NOT NULL THEN substr(M41C04.NKSIN1_AWTDT,1,4)||'/'||substr(M41C04.NKSIN1_AWTDT,5,2) ELSE ' ' END END END KSA_DT ";
//        $str_sql .= "  CASE WHEN rtrim(M41C04.NKSIN1_AWTDT) IS NOT NULL THEN substr(M41C04.NKSIN1_AWTDT,1,4)||'/'||substr(M41C04.NKSIN1_AWTDT,5,2) ELSE CASE WHEN rtrim(M41C04.KSA_DT) IS NOT NULL THEN substr(M41C04.KSA_DT,1,4)||'/'||substr(M41C04.KSA_DT,5,2) ELSE CASE WHEN M41C04.NOUSDAT IS NOT NULL THEN substr(M41C04.NOUSDAT,1,4)||'/'||substr(M41C04.NOUSDAT,5,2) ELSE ' ' END END END KSA_DT ";
        $str_sql .= "  rtrim(CASE WHEN rtrim(M41C04.NKSIN1_AWTDT) IS NOT NULL THEN substr(M41C04.NKSIN1_AWTDT,1,4)||'/'||substr(M41C04.NKSIN1_AWTDT,5,2) ELSE CASE WHEN rtrim(M41C04.KSA_DT) IS NOT NULL THEN substr(M41C04.KSA_DT,1,4)||'/'||substr(M41C04.KSA_DT,5,2) ELSE CASE WHEN M41C04.NOUSDAT IS NOT NULL THEN substr(M41C04.NOUSDAT,1,4)||'/'||substr(M41C04.NOUSDAT,5,2) ELSE ' ' END END END) KSA_DT ";
        //20150619 Update End

        //20150611 Update End

        $str_sql .= ' FROM ';
        $str_sql .= '  M41C01, ';
        $str_sql .= '  M41C03, ';
        $str_sql .= '  M41C04 ';
        $str_sql .= '  ,M41E10 ';

        //20150619 Update Start
        $str_sql .= ',';
        $str_sql .= 'M27M01 T1,' . "\r\n";
        $str_sql .= 'M27M01 T2,' . "\r\n";
        $str_sql .= 'M27M14 T3,' . "\r\n";
        $str_sql .= 'M29MA4 T4,' . "\r\n";
        $str_sql .= 'M27M14 T5' . "\r\n";
        //20150619 Update End

        $str_sql .= ' WHERE ';
        $str_sql .= '  M41C01.DLRCSRNO = M41C04.DLRCSRNO ';
        $str_sql .= 'AND ';
        $str_sql .= '  M41C04.VIN_WMIVDS = M41C03.VIN_WMIVDS ';
        $str_sql .= 'AND ';
        $str_sql .= '  M41C04.VIN_VIS = M41C03.VIN_VIS ';
        //20150611 Update Start
        $str_sql .= 'AND ';
        $str_sql .= '  RTRIM(M41C04.ORDERNO) = M41E10.CMN_NO(+) ';
        //20150611 Update End

        $str_sql .= 'AND ';
        $str_sql .= "  M41C04.DLRCSRNO = '" . $DLRCSRNO . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  M41C04.VIN_WMIVDS = '" . $VIN_WMIVDS . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "   RTRIM(M41C04.VIN_VIS) = '" . $VIN_VIS . "' ";

        //20150619 Update Start
        $str_sql .= ' AND M41C04.DLRCSRNO    = M41C01.DLRCSRNO(+) ';
        $str_sql .= ' AND t1.KYOTN_CD(+)  = M41C04.KNR_STRCD ';
        $str_sql .= " AND t1.ES_KB(+)     = 'E' ";
        $str_sql .= ' AND t2.KYOTN_CD(+)  = M41C04.SRV_SRVSTRCD ';
        $str_sql .= " AND t2.ES_KB(+)     = 'S' ";
        $str_sql .= " AND t3.SCD_SYSID(+) = 'Z' ";
        $str_sql .= " AND t3.SCD_ID(+)    = 'RIKUJI' ";
        $str_sql .= ' AND t3.SCD_VAL(+)   = M41C04.VCLRGTNO_LAND ';
        $str_sql .= " AND t4.HANSH_CD(+)  = '3634' ";
        $str_sql .= ' AND t4.SYAIN_NO(+)  = M41C04.KNR_BUSMANCD ';
        $str_sql .= " AND t5.SCD_SYSID(+) = 'M' ";
        $str_sql .= " AND t5.SCD_ID(+)    = 'SEIBETU_KB' ";
        $str_sql .= ' AND t5.SCD_VAL(+)   = M41C01.CSRDOSID';
        //20150619 Update End

        return $str_sql;
    }

    /**
     * HITメモ取得.
     *
     * @param {String} $DLRCSRNO   販社お客様No
     * @param {String} $VIN_WMIVDS ＶＩＮ－ＷＭＩＶＤＳ
     * @param {String} $VIN_VIS    ＶＩＮ－ＶＩＳ
     *
     * @return {String} select文
     */
    public function m_select_hit_memo_sql($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS)
    {
        $str_sql = '';
        //        $str_sql .= "SELECT ";
//       $str_sql .= "  replace(M41C05.SRY_FRE_MEM,'　',' ') SRY_FRE_MEM ";
//        $str_sql .= "FROM ";
//        $str_sql .= "  M41C05 ";
//        $str_sql .= "WHERE ";
//        $str_sql .= "  M41C05.DLRCSRNO = '" . $DLRCSRNO . "' ";
//        $str_sql .= "AND ";
//        $str_sql .= "  M41C05.VIN_WMIVDS = '" . $VIN_WMIVDS . "' ";
//        $str_sql .= "AND ";
//        $str_sql .= "  M41C05.VIN_VIS = '" . $VIN_VIS . "' ";

        $str_sql .= 'SELECT ';
        //20161212 Update Start
//        $str_sql .= "replace(M41C02.FRE_MEM,'　',' ') FRE_MEM, ";
//        $str_sql .= "replace(M41C05.SRY_FRE_MEM,'　',' ') SRY_FRE_MEM ";
        $str_sql .= "replace(nvl(M41C02.FRE_MEM,' '),'　',' ') FRE_MEM, ";
        $str_sql .= "replace(nvl(M41C05.SRY_FRE_MEM,' '),'　',' ') SRY_FRE_MEM ";
        //20161212 Update End

        $str_sql .= 'FROM ';
        $str_sql .= '  M41C02, ';
        $str_sql .= '  M41C04, ';
        $str_sql .= '  M41C05 ';
        $str_sql .= 'WHERE ';
        $str_sql .= '  M41C04.DLRCSRNO = M41C02.DLRCSRNO(+) ';
        $str_sql .= 'AND ';
        $str_sql .= '  M41C04.DLRCSRNO = M41C05.DLRCSRNO(+) ';
        $str_sql .= 'AND ';
        $str_sql .= '  M41C04.VIN_WMIVDS = M41C05.VIN_WMIVDS(+) ';
        $str_sql .= 'AND ';
        $str_sql .= '  M41C04.VIN_VIS = M41C05.VIN_VIS(+) ';

        $str_sql .= 'AND ';
        $str_sql .= "  M41C04.DLRCSRNO = '" . $DLRCSRNO . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  M41C04.VIN_WMIVDS = '" . $VIN_WMIVDS . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  M41C04.VIN_VIS = '" . $VIN_VIS . "' ";

        return $str_sql;
    }

    public function getJsonData()
    {
        $this->common = new common();
        $contents = $this->common->getJSONData('/SDH/data.json');

        return $contents;
    }

    public function retuenJsonData($result, $tbName, $tbList)
    {
        $this->common = new common();
        $xs = $this->common->searchArray($result[$tbName], $tbList, 'id');

        return $xs;
    }
}