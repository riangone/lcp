<?php
/**
 * 説明：
 *
 *
 * @author lijun
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20150526           ---                       新規                           FCSDL
 * --------------------------------------------------------------------------------------------
 */

// 共通クラスの読込み
namespace App\Model\SDH;

use App\Model\Component\ClsComDb;

class SDH07 extends ClsComDb
{
    /**
     * @param {String} $tenpo_cd 店舗2
     *
     * @return {String} select結果
     */
    public function m_select_Sdh07_JQG($postData)
    {
        $str_sql = $this->m_select_Sdh07_sql($postData);

        return parent::select($str_sql);
    }

    /**
     * @param {String} $postData JqGrid
     *
     * @return {String} select文
     */
    public function m_select_Sdh07_sql($postData)
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= "  '1' as DSP_YOU_SEQ0, ";

        $str_sql .= '  BTH28SD4.DSP_YOU_SEQ AS DSP_YOU_SEQ, ';
        $str_sql .= '  BTH28SD4.NYUKOKBN || BTH28SD4.NGKBN AS NYUKOKBNNGKBN, ';
        $str_sql .= '  BTH28SD4.SAG_NM AS SAG_NM, ';
        $str_sql .= '  BTH28SD4.URG_GKU AS URG_GKU, ';
        $str_sql .= '  BTH28SD4.ZKM_TGK AS ZKM_TGK, ';
        $str_sql .= '  trmData2.SYAIN_KNJ_SEI AS SYAIN_KNJ_SEI, ';
        $str_sql .= '  trmData2.SYAIN_KNJ_MEI AS SYAIN_KNJ_MEI, ';
        $str_sql .= '  trmData2.NYUKOKBNMEI AS NYUKOKBNMEI ';
        $str_sql .= 'FROM ';
        $str_sql .= '  BTH28SD4 ';
        $str_sql .= '  LEFT JOIN ( ';
        $str_sql .= '  (SELECT M29MA4.SYAIN_KNJ_SEI AS SYAIN_KNJ_SEI, ';
        $str_sql .= '  M29MA4.SYAIN_KNJ_MEI         AS SYAIN_KNJ_MEI, ';
        $str_sql .= '  M28S02.NYUKOKBNMEI           AS NYUKOKBNMEI, ';
        $str_sql .= '  BTH28SD2.VIN_SDI_KAT         AS VIN_SDI_KAT, ';
        $str_sql .= '  BTH28SD2.VIN_RBN             AS VIN_RBN,';
        $str_sql .= '  BTH28SD2.SEB_NOU_NO          AS SEB_NOU_NO ';

        $str_sql .= '  FROM ';
        $str_sql .= '  M29MA4 , ';
        $str_sql .= '  M28S02, ';
        $str_sql .= '  BTH28SD2 ';
        $str_sql .= '  WHERE BTH28SD2.U_TANTOCD     = M29MA4.SYAIN_NO(+) ';
        $str_sql .= '  AND BTH28SD2.HANSH_CD      = M29MA4.HANSH_CD(+) ';
        $str_sql .= '  AND BTH28SD2.DIH_NKO_KB    = M28S02.NYUKOKBN ';
        $str_sql .= "  AND BTH28SD2.URG_DT        = '" . $postData['URG_DT'] . "' ";
        $str_sql .= "  AND BTH28SD2.DIH_NKO_KB    = '" . $postData['NYUKOKBN'] . "' ";
        $str_sql .= "  AND BTH28SD2.SEB_NOU_NO   = '" . $postData['SEB_NOU_NO'] . "' ";

        $str_sql .= '  ) trmData2 ) ';
        $str_sql .= '  ON BTH28SD4.VIN_SDI_KAT = trmData2.VIN_SDI_KAT ';
        $str_sql .= '  AND BTH28SD4.VIN_RBN    = trmData2.VIN_RBN ';
        $str_sql .= '  AND BTH28SD4.SEB_NOU_NO = trmData2.SEB_NOU_NO ';

        $str_sql .= 'WHERE ';
        $str_sql .= "  BTH28SD4.URG_DT = '" . $postData['URG_DT'] . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  BTH28SD4.VIN_RBN = '" . $postData['VIN_RBN'] . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  BTH28SD4.VIN_SDI_KAT = '" . $postData['VIN_SDI_KAT'] . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  BTH28SD4.SEB_NOU_NO = '" . $postData['SEB_NOU_NO'] . "' ";

        $str_sql .= 'UNION ';
        $str_sql .= 'SELECT ';
        $str_sql .= "  '0' as DSP_YOU_SEQ0, ";

        $str_sql .= '  BTH28SD3.SEQ AS DSP_YOU_SEQ, ';
        $str_sql .= "  '' AS NYUKOKBNNGKBN, ";
        $str_sql .= '  BTH28SD3.GOYOMEI AS SAG_NM, ';
        $str_sql .= '  0 AS URG_GKU, ';
        $str_sql .= '  0 AS ZKM_TGK, ';
        $str_sql .= '  trmData2.SYAIN_KNJ_SEI AS SYAIN_KNJ_SEI, ';
        $str_sql .= '  trmData2.SYAIN_KNJ_MEI AS SYAIN_KNJ_MEI, ';
        $str_sql .= '  trmData2.NYUKOKBNMEI AS NYUKOKBNMEI ';
        $str_sql .= 'FROM ';
        $str_sql .= '  BTH28SD3 ';
        $str_sql .= '  LEFT JOIN ( ';
        $str_sql .= '  (SELECT M29MA4.SYAIN_KNJ_SEI AS SYAIN_KNJ_SEI, ';
        $str_sql .= '  M29MA4.SYAIN_KNJ_MEI         AS SYAIN_KNJ_MEI, ';
        $str_sql .= '  M28S02.NYUKOKBNMEI           AS NYUKOKBNMEI, ';
        $str_sql .= '  BTH28SD2.VIN_SDI_KAT         AS VIN_SDI_KAT, ';
        $str_sql .= '  BTH28SD2.VIN_RBN             AS VIN_RBN,';
        $str_sql .= '  BTH28SD2.SEB_NOU_NO          AS SEB_NOU_NO ';
        $str_sql .= '  FROM ';
        $str_sql .= '  M29MA4 , ';
        $str_sql .= '  M28S02, ';
        $str_sql .= '  BTH28SD2 ';
        $str_sql .= '  WHERE BTH28SD2.U_TANTOCD     = M29MA4.SYAIN_NO(+) ';
        $str_sql .= '  AND BTH28SD2.HANSH_CD      = M29MA4.HANSH_CD(+) ';
        $str_sql .= '  AND BTH28SD2.DIH_NKO_KB    = M28S02.NYUKOKBN ';
        $str_sql .= "  AND BTH28SD2.URG_DT        = '" . $postData['URG_DT'] . "' ";
        $str_sql .= "  AND BTH28SD2.DIH_NKO_KB    = '" . $postData['NYUKOKBN'] . "' ";
        $str_sql .= "  AND BTH28SD2.SEB_NOU_NO  = '" . $postData['SEB_NOU_NO'] . "' ";

        $str_sql .= '  ) trmData2 ) ';
        $str_sql .= '  ON BTH28SD3.VIN_SDI_KAT = trmData2.VIN_SDI_KAT ';
        $str_sql .= '  AND BTH28SD3.VIN_RBN    = trmData2.VIN_RBN ';
        $str_sql .= '  AND BTH28SD3.SEB_NOU_NO = trmData2.SEB_NOU_NO ';
        $str_sql .= 'WHERE ';
        $str_sql .= "  BTH28SD3.URG_DT = '" . $postData['URG_DT'] . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  BTH28SD3.VIN_RBN = '" . $postData['VIN_RBN'] . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  BTH28SD3.VIN_SDI_KAT = '" . $postData['VIN_SDI_KAT'] . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  BTH28SD3.SEB_NOU_NO= '" . $postData['SEB_NOU_NO'] . "' ";

        $str_sql .= 'UNION ';
        $str_sql .= 'SELECT ';
        $str_sql .= "  '1' as DSP_YOU_SEQ0, ";
        $str_sql .= '  BTH28SD5.DSP_YOU_SEQ AS DSP_YOU_SEQ, ';
        $str_sql .= '  BTH28SD5.NYUKOKBN || BTH28SD5.NGKBN AS NYUKOKBNNGKBN, ';
        $str_sql .= '  BTH28SD5.BUHINMEI AS SAG_NM, ';
        $str_sql .= '  BTH28SD5.URG_GKU AS URG_GKU, ';
        $str_sql .= '  BTH28SD5.ZKM_TGK AS ZKM_TGK, ';
        $str_sql .= '  trmData2.SYAIN_KNJ_SEI AS SYAIN_KNJ_SEI, ';
        $str_sql .= '  trmData2.SYAIN_KNJ_MEI AS SYAIN_KNJ_MEI, ';
        $str_sql .= '  trmData2.NYUKOKBNMEI AS NYUKOKBNMEI ';
        $str_sql .= 'FROM ';
        $str_sql .= '  BTH28SD5 ';
        $str_sql .= '  LEFT JOIN ( ';
        $str_sql .= '  (SELECT M29MA4.SYAIN_KNJ_SEI AS SYAIN_KNJ_SEI, ';
        $str_sql .= '  M29MA4.SYAIN_KNJ_MEI         AS SYAIN_KNJ_MEI, ';
        $str_sql .= '  M28S02.NYUKOKBNMEI           AS NYUKOKBNMEI, ';
        $str_sql .= '  BTH28SD2.VIN_SDI_KAT         AS VIN_SDI_KAT, ';
        $str_sql .= '  BTH28SD2.VIN_RBN             AS VIN_RBN,';
        $str_sql .= '  BTH28SD2.SEB_NOU_NO          AS SEB_NOU_NO ';
        $str_sql .= '  FROM ';
        $str_sql .= '  M29MA4 , ';
        $str_sql .= '  M28S02, ';
        $str_sql .= '  BTH28SD2 ';
        $str_sql .= '  WHERE BTH28SD2.U_TANTOCD     = M29MA4.SYAIN_NO(+) ';
        $str_sql .= '  AND BTH28SD2.HANSH_CD      = M29MA4.HANSH_CD(+) ';
        $str_sql .= '  AND BTH28SD2.DIH_NKO_KB    = M28S02.NYUKOKBN ';
        $str_sql .= "  AND BTH28SD2.URG_DT        = '" . $postData['URG_DT'] . "' ";
        $str_sql .= "  AND BTH28SD2.DIH_NKO_KB    = '" . $postData['NYUKOKBN'] . "' ";
        $str_sql .= "  AND BTH28SD2.SEB_NOU_NO  = '" . $postData['SEB_NOU_NO'] . "' ";

        $str_sql .= '  ) trmData2 ) ';
        $str_sql .= '  ON BTH28SD5.VIN_SDI_KAT = trmData2.VIN_SDI_KAT ';
        $str_sql .= '  AND BTH28SD5.VIN_RBN    = trmData2.VIN_RBN ';
        $str_sql .= '  AND BTH28SD5.SEB_NOU_NO = trmData2.SEB_NOU_NO ';
        $str_sql .= 'WHERE ';
        $str_sql .= "  BTH28SD5.URG_DT = '" . $postData['URG_DT'] . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  BTH28SD5.VIN_RBN = '" . $postData['VIN_RBN'] . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  BTH28SD5.VIN_SDI_KAT = '" . $postData['VIN_SDI_KAT'] . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  BTH28SD5.SEB_NOU_NO = '" . $postData['SEB_NOU_NO'] . "'  ";

        $str_sql .= 'UNION ';
        $str_sql .= 'SELECT ';
        $str_sql .= "  '8' as DSP_YOU_SEQ0, ";
        $str_sql .= '  999 AS DSP_YOU_SEQ, ';
        $str_sql .= "  '' AS NYUKOKBNNGKBN, ";
        $str_sql .= "  '技術料値引' AS SAG_NM, ";
        $str_sql .= '  0 AS URG_GKU, ';
        $str_sql .= '  BTH28SD2.ZKM_SAG_NBK_GKU AS ZKM_TGK, ';
        $str_sql .= "  '' SYAIN_KNJ_SEI, ";
        $str_sql .= "  '' AS SYAIN_KNJ_MEI, ";
        $str_sql .= "  '' AS NYUKOKBNMEI ";
        $str_sql .= 'FROM ';
        $str_sql .= '  BTH28SD2 ';
        $str_sql .= "  WHERE BTH28SD2.URG_DT   = '" . $postData['URG_DT'] . "' ";
        $str_sql .= "  AND BTH28SD2.DIH_NKO_KB = '" . $postData['NYUKOKBN'] . "' ";
        $str_sql .= "  AND BTH28SD2.SEB_NOU_NO = '" . $postData['SEB_NOU_NO'] . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  BTH28SD2.VIN_RBN = '" . $postData['VIN_RBN'] . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  BTH28SD2.VIN_SDI_KAT = '" . $postData['VIN_SDI_KAT'] . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  BTH28SD2.SEB_NOU_NO = '" . $postData['SEB_NOU_NO'] . "' ";

        $str_sql .= 'UNION ';
        $str_sql .= 'SELECT ';
        $str_sql .= "  '9' as DSP_YOU_SEQ0, ";
        $str_sql .= '  999 AS DSP_YOU_SEQ, ';
        $str_sql .= "  '' AS NYUKOKBNNGKBN, ";
        $str_sql .= "  '部品値引' AS SAG_NM, ";
        $str_sql .= '  0 AS URG_GKU, ';
        $str_sql .= '  BTH28SD2.ZKM_PAR_NBK_GKU AS ZKM_TGK, ';
        $str_sql .= "  '' SYAIN_KNJ_SEI, ";
        $str_sql .= "  '' AS SYAIN_KNJ_MEI, ";
        $str_sql .= "  '' AS NYUKOKBNMEI ";
        $str_sql .= 'FROM ';
        $str_sql .= '  BTH28SD2 ';
        $str_sql .= "  WHERE BTH28SD2.URG_DT   = '" . $postData['URG_DT'] . "' ";
        $str_sql .= "  AND BTH28SD2.DIH_NKO_KB = '" . $postData['NYUKOKBN'] . "' ";
        $str_sql .= "  AND BTH28SD2.SEB_NOU_NO = '" . $postData['SEB_NOU_NO'] . "' ";

        $str_sql .= 'AND ';
        $str_sql .= "  BTH28SD2.VIN_RBN = '" . $postData['VIN_RBN'] . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  BTH28SD2.VIN_SDI_KAT = '" . $postData['VIN_SDI_KAT'] . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  BTH28SD2.SEB_NOU_NO  = '" . $postData['SEB_NOU_NO'] . "' ";

        $str_sql .= 'ORDER BY ';
        $str_sql .= '  DSP_YOU_SEQ0,DSP_YOU_SEQ ';

        return $str_sql;
    }
}