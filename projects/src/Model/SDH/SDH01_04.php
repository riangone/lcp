<?php
// 共通クラスの読込み
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
 * 		日付        			 Feature/Bug         			内容                 		担当
 * YYYYMMDD   		 		#ID            			XXXXXX               		FCSDL
 * 2014/12/03         No.41           入庫履歴+コンタクト履歴を１回で取得                   fanzhengzhou
 * 2015/06/16                         登録日以降のデータのみ抽出する条件追加                HM
 * 2015/07/16                         注文書がない車両の場合登録日を条件から外す            HM
 * 2016/01/16                            コンタクト履歴情報に商品を追加                          HM
 * --------------------------------------------------------------------------------------------
 */


namespace App\Model\SDH;

use App\Model\Component\ClsComDb;

class SDH01_04 extends ClsComDb
{
    /**入庫履歴+コンタクト履歴
     * @param {String} $arrayData
     * ＶＩＮ車台型式:VIN_SDI_KAT
     * VIN連番:VIN_RBN
     * @return {parent} result
     */
    public function m_shd01_04_select($arrayData)
    {
        $str_sql = $this->m_shd01_04_sql($arrayData);

        return parent::select($str_sql);
    }

    /**入庫履歴+コンタクト履歴  select文
     * @param {String} $arrayData
     * ＶＩＮ車台型式:VIN_SDI_KAT
     * VIN連番:VIN_RBN
     * @return {String} select文
     */
    public function m_shd01_04_sql($arrayData)
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= " '0' AS DATA_TYPE, ";
        $str_sql .= ' BTH28SD2.URG_DT||BTH28SD2.SEB_NOU_NO AS SORTKEY, ';
        $str_sql .= ' BTH28SD2.URG_DT AS URG_DT, ';
        $str_sql .= ' M28S02.NYUKOKBNMEI AS NYUKOKBNMEI, ';
        //			$str_sql .= " M27M01.KYOTN_NM AS KYOTN_NM, ";
        $str_sql .= ' HBUSYO.BUSYO_RYKNM AS KYOTN_NM, ';
        $str_sql .= ' BTH28SD2.VIN_SDI_KAT AS VIN_SDI_KAT, ';
        $str_sql .= ' BTH28SD2.VIN_RBN AS VIN_RBN, ';
        $str_sql .= ' BTH28SD2.SEB_NOU_NO AS SEB_NOU_NO, ';
        $str_sql .= ' M28S02.NYUKOKBN AS NYUKOKBN, ';
        //2011209 Update Start
        //			$str_sql .= " M29MA4.SYAIN_KNJ_SEI AS SYAIN_KNJ_SEI, ";
        //			$str_sql .= " M29MA4.SYAIN_KNJ_MEI AS SYAIN_KNJ_MEI ,";
        $str_sql .= " NVL(M29MA4.SYAIN_KNJ_SEI,'****') AS SYAIN_KNJ_SEI, ";
        $str_sql .= " NVL(M29MA4.SYAIN_KNJ_MEI,'****') AS SYAIN_KNJ_MEI ,";
        //2011209 Update End
        $str_sql .= " ' '  AS C_DATE, ";
        $str_sql .= " ' '  AS C_SYUDAN, ";
        $str_sql .= " ' ' AS C_TAIOU, ";
        $str_sql .= " ' ' AS C_NAIYO ";
        //20160115 Ins Start
        $str_sql .= " ,' ' AS SYOHIN ";
        //20160115 Ins End
        $str_sql .= 'FROM ';
        $str_sql .= ' BTH28SD2, ';
        $str_sql .= ' M28S02, ';
        //			$str_sql .= " M27M01, ";
        $str_sql .= ' HBUSYO, ';
        $str_sql .= ' M29MA4 ';
        $str_sql .= 'WHERE ';
        $str_sql .= ' BTH28SD2.DIH_NKO_KB = M28S02.NYUKOKBN ';
        $str_sql .= 'AND ';
        //20161209 Update Start
        //			$str_sql .= " M27M01.HANSH_CD = BTH28SD2.HANSH_CD ";
        //			$str_sql .= "AND ";
        //			$str_sql .= " M27M01.KYOTN_CD = BTH28SD2.TENPO_CD ";
        $str_sql .= ' HBUSYO.BUSYO_CD = BTH28SD2.KYOTN_CD ';
        //20161209 Update End
        $str_sql .= 'AND ';
        $str_sql .= " '3634' = M29MA4.HANSH_CD(+) ";
        $str_sql .= 'AND ';
        $str_sql .= ' BTH28SD2.U_TANTOCD = M29MA4.SYAIN_NO(+) ';
        //20161209 Del Start
        //			$str_sql .= "AND ";
        //			$str_sql .= " M27M01.ES_KB in ('E','S') ";
        //20161209 Del End
        $str_sql .= 'AND ';
        $str_sql .= " BTH28SD2.VIN_SDI_KAT = '" . $arrayData['VIN_SDI_KAT'] . "' ";
        $str_sql .= 'AND ';
        $str_sql .= " BTH28SD2.VIN_RBN = '" . $arrayData['VIN_RBN'] . "' ";
        //20150716 Add Start
        if ('' != $arrayData['TOU_DT']) {
            //20150611 Add Start
            $str_sql .= 'AND ';
            $str_sql .= " BTH28SD2.URG_DT >= '" . $arrayData['TOU_DT'] . "' ";
            //20150611 Add End
        }
        //20150716 Add End

        $str_sql .= 'UNION ';
        $str_sql .= 'SELECT ';
        $str_sql .= " '1' AS DATA_TYPE, ";
        $str_sql .= " TO_CHAR(C_DATE,'yyyyMMdd') AS SORTKEY, ";
        $str_sql .= " ' ' AS URG_DT, ";
        $str_sql .= " ' ' AS NYUKOKBNMEI, ";
        $str_sql .= " ' ' AS KYOTN_NM, ";
        $str_sql .= ' CRM_CONTACT.VIN_WMIVDS AS VIN_SDI_KAT, ';
        $str_sql .= ' CRM_CONTACT.VIN_VIS AS VIN_RBN, ';
        $str_sql .= " ' ' AS SEB_NOU_NO, ";
        $str_sql .= " ' ' AS NYUKOKBN, ";
        $str_sql .= " ' ' AS SYAIN_KNJ_SEI, ";
        $str_sql .= " ' ' AS SYAIN_KNJ_MEI ,";
        //			$str_sql .= " TO_CHAR(C_DATE,'yyyyMMdd') AS C_DATE, ";
        //			$str_sql .= " CRM_CONTACT.C_SYUDAN AS C_SYUDAN, ";
        //			$str_sql .= " CRM_CONTACT.C_TAIOU AS C_TAIOU, ";
        $str_sql .= " TO_CHAR(C_DATE,'yyyy/MM/dd') AS C_DATE, ";
        $str_sql .= ' CRM_CONTACT.C_SYUDAN AS C_SYUDAN, ';
        $str_sql .= ' CRM_CONTACT.C_TAIOU AS C_TAIOU, ';

        //20150601 Upd Start
        //			$str_sql .= " CRM_CONTACT.C_NAIYO AS C_NAIYO ";
        $str_sql .= " '【'||rtrim(CRM_CONTACT.C_NAIYO)||'】　'||CRM_CONTACT.C_COM AS C_NAIYO ";
        //			$str_sql .= " CASE WHEN RTRIM(CRM_CONTACT.C_COM) IS NULL THEN CRM_CONTACT.C_NAIYO ELSE CRM_CONTACT.C_NAIYO||'　'||CRM_CONTACT.C_COM END  AS C_NAIYO ";
        //20150601 Upd End

        //20160115 Upd Start
        //			$str_sql .= " ,CRM_CONTACT.SYOHIN AS SYOHIN ";
        $str_sql .= " ,  CASE WHEN CRM_CONTACT.SYOHIN IS NOT NULL THEN  '【' || CRM_CONTACT.SYOHIN || '】' ELSE '' END AS SYOHIN ";
        //20160115 Upd End

        $str_sql .= 'FROM ';
        $str_sql .= ' CRM_CONTACT ';
        $str_sql .= 'WHERE ';
        $str_sql .= " CRM_CONTACT.DCODE = '" . $arrayData['DLRCSRNO'] . "' ";
        $str_sql .= 'AND ';
        $str_sql .= " CRM_CONTACT.VIN_WMIVDS = '" . $arrayData['VIN_SDI_KAT'] . "' ";
        $str_sql .= 'AND ';
        $str_sql .= " CRM_CONTACT.VIN_VIS = '" . $arrayData['VIN_RBN'] . "' ";

        if ('' != $arrayData['TOU_DT']) {
            //20150611 Add Start
            $str_sql .= 'AND ';
            //20150803 Update Start
            //			$str_sql .= " TO_CHAR(CRM_CONTACT.C_DATE) >= '" . $arrayData['TOU_DT'] . "' ";
            $str_sql .= " TO_CHAR(C_DATE,'yyyyMMdd')   >= '" . $arrayData['TOU_DT'] . "' ";
            //20150803 Update End
//20150611 Add End
        }

        $str_sql .= 'ORDER BY ';
        $str_sql .= ' SORTKEY DESC';

        return $str_sql;
    }
}