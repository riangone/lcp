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
 * 20150611           ---                       抽出条件に「登録日」を追加     HM
 * --------------------------------------------------------------------------------------------
 */

// 共通クラスの読込み
namespace App\Model\SDH;

use App\Model\Component\ClsComDb;

class SDH04 extends ClsComDb
{
    /**
     * @param {String} $tenpo_cd1,$tenpo_cd2,$tenpo_cd3 クレジット詳細
     *
     * @return {String} select結果
     */
    public function m_select_Sdh04_M41E10($cmn_no)
    {
        $str_sql = $this->m_select_Sdh04_sql2($cmn_no);

        return parent::select($str_sql);
    }

    /**
     * @param {String} $tenpo_cd1 任意保険情報
     *
     * @return {String} select結果
     */
    //20150611 Update Start
    //		public function m_select_Sdh04_MCI_0170($DLRCSRNO,$syadaino)
    //		{
    //			$str_sql = $this -> m_select_Sdh04_sql1($DLRCSRNO,$syadaino);
    //			return parent::select($str_sql);
    //		}
    public function m_select_Sdh04_MCI_0170($syadaino, $TOU_DT)
    {
        $str_sql = $this->m_select_Sdh04_sql1($syadaino, $TOU_DT);

        return parent::select($str_sql);
    }

    //20150611 Update End

    /**
     * @param {String} $tenpo_cd_1 クレジット詳細
     *
     * @return {String} select文
     */
    public function m_select_Sdh04_sql2($cmn_no)
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= '  KYK_CUS_NO , ';
        $str_sql .= '  SHR_GKN_DPS , ';
        $str_sql .= '  ZAN_SET_GKU , ';
        $str_sql .= '  KRJ_MOT_KIN , ';
        $str_sql .= '  M28M66.SCD_NM , ';
        $str_sql .= '  ROI , ';
        $str_sql .= '  KRJ_BUN_KSU , ';
        $str_sql .= '  KRJ_SHR_KKN_FRO , ';
        $str_sql .= '  KRJ_SHR_KKN_TO , ';
        $str_sql .= '  SHR_DT , ';
        $str_sql .= '  BNS_ADD_SHR_GKU , ';
        $str_sql .= '  BNS_SHR_MM1 , ';
        $str_sql .= '  BNS_SHR_MM2 , ';
        $str_sql .= '  BNS_KSU , ';
        $str_sql .= '  FIR_FNL_SHR_GKU , ';
        $str_sql .= '  MM_SHR_GKU , ';
        $str_sql .= '  KRJ_BUN_KSU-1 , ';
        $str_sql .= '  KRJ_BUN_KSU-2 ,';
        $str_sql .= '  SDI_KAT, ';
        $str_sql .= '  CAR_NO ';

        $str_sql .= '  ,TOU_DT ';

        $str_sql .= 'FROM M41E10 LEFT JOIN M28M66 ';
        $str_sql .= '  ON M41E10.CREDITCD = M28M66.SCD_VAL ';
        //20150611 Add Start
        $str_sql .= '  AND M41E10.TOU_DT <= M41E10.KRJ_SHR_KKN_FRO ';
        //20150611 Add End
        $str_sql .= "  AND M28M66.SCD_SYSID ='F' ";
        $str_sql .= "  AND M28M66.SCD_ID ='01' ";
        $str_sql .= "WHERE CMN_NO = '{$cmn_no}' ";

        return $str_sql;
    }

    /**
     * @param {String} $tenpo_cd_1 任意保険情報
     *
     * @return {String} select文
     */
    //20150611 Update Start
    //		public function m_select_Sdh04_sql1($DLRCSRNO ,$syadaino)
    public function m_select_Sdh04_sql1($syadaino, $TOU_DT)
    {
        //20150611 Update End
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= '   MCI_0170.MOSTLONG_ENTRYNO,';
        $str_sql .= '  MCI_M0120.KAISYAMEI , ';
        $str_sql .= '   MCI_0170.SYOKENNO , ';
        $str_sql .= '   MCI_0170.KEIYAKUNAME ,';
        $str_sql .= '   MCI_MINMAX.HOKENSIKI,';
        $str_sql .= '  MCI_M0150.SYURUIMEI , ';
        $str_sql .= '  MCI_M0080.HARAIKOMIMEI , ';
        $str_sql .= '   MCI_0170.SYARYO , ';
        $str_sql .= '   MCI_MINMAX.HOKENSYUKI';
        $str_sql .= ' FROM ';
        $str_sql .= ' ( SELECT * ';
        $str_sql .= '   FROM ';
        $str_sql .= '    MCI_0170 ';
        $str_sql .= '   WHERE ';
        $str_sql .= "     SYADAINO = '{$syadaino}'";
        $str_sql .= '    AND MCI_0170.MOSTLONG_ENTRYNO =';
        $str_sql .= '   (SELECT ';
        $str_sql .= '     MAX(MOSTLONG_ENTRYNO) ';
        $str_sql .= '    FROM ';
        $str_sql .= '     MCI_0170 ';
        $str_sql .= '    WHERE ';
        $str_sql .= "     SYADAINO = '{$syadaino}')";
        $str_sql .= '  ) MCI_0170 ,';
        $str_sql .= '  (SELECT';
        $str_sql .= '    SYOKENNO, ';
        $str_sql .= '    MIN(HOKENSIKI) HOKENSIKI,';
        $str_sql .= '    MAX(HOKENSYUKI) HOKENSYUKI';
        $str_sql .= '   FROM ';
        $str_sql .= '    MCI_0170 ';
        $str_sql .= '   WHERE ';
        $str_sql .= "    SYADAINO = '{$syadaino}' ";
        $str_sql .= '   GROUP BY ';
        $str_sql .= '    SYOKENNO  ) MCI_MINMAX ';

        $str_sql .= '  , MCI_M0120 , MCI_M0150 , MCI_M0080 ';
        $str_sql .= '   WHERE ';
        $str_sql .= '   MCI_0170.SYOKENNO = MCI_MINMAX.SYOKENNO ';

        $str_sql .= 'AND ';
        $str_sql .= '  MCI_0170.KAISYACD = MCI_M0120.KAISYACD(+) ';
        $str_sql .= 'AND ';
        $str_sql .= '  MCI_0170.SYUMOKU = MCI_M0150.SYUMOKU(+) ';
        $str_sql .= 'AND ';
        $str_sql .= '  MCI_0170.SYURUI = MCI_M0150.SYURUI(+) ';
        $str_sql .= 'AND ';
        $str_sql .= '  MCI_0170.HARAIKOMI = MCI_M0080.HARAIKOMI(+) ';
        //20150611 Add Start
        $str_sql .= 'AND ';
        $str_sql .= "   MCI_MINMAX.HOKENSIKI >= '" . $TOU_DT . "'";
        //20150611 Add End

        return $str_sql;
    }
}