<?php
/**
 * 説明：
 *
 *
 * @author fanzhengzhou
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20150526           ---                       新規                           FCSDL
 * 20150611           ---                       抽出条件に「登録日」を追加     HM
 * 20151116           ---                       パックＤＥメンテ参照先を M41C10 → BTH41C10へ変更    HM
 * --------------------------------------------------------------------------------------------
 */

// 共通クラスの読込み
namespace App\Model\SDH;

use App\Model\Component\ClsComDb;

class SDH01_05 extends ClsComDb
{
    /**
     * 初度登録年月を取得.
     *
     * @param {String}
     * ＶＩＮ－ＷＭＩＶＤＳ:$VIN_WMIVDS
     * ＶＩＮ－ＶＩＳ:$VIN_VIS
     *
     * @return {parent} result
     */
    public function getFRGMH($VIN_WMIVDS, $VIN_VIS)
    {
        return parent::select($this->getFRGMH_sql($VIN_WMIVDS, $VIN_VIS));
    }

    /**
     * 入庫歴を取得.
     *
     * @param {String}
     * 販社お客様No:$DLRCSRNO
     * ＶＩＮ－ＷＭＩＶＤＳ:$VIN_WMIVDS
     * ＶＩＮ－ＶＩＳ:$VIN_VIS
     *
     * @return {parent} result
     */
    //20150611 Update Start
    //public function getdata1($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS)
    //{
    //	return parent::select($this -> getdata1_sql($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS));
    //}
    public function getdata1($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS)
    {
        return parent::select($this->getdata1_sql($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS));
    }

    //20150611 Update End

    /**
     * 定期点検を取得.
     *
     * @param
     *
     * @return {parent} result
     */
    //20150611 Update Start
    //public function getdata2($VIN_WMIVDS, $VIN_VIS)
    //{
    //	return parent::select($this -> getdata2_sql($VIN_WMIVDS, $VIN_VIS));
    //}
    public function getdata2($VIN_WMIVDS, $VIN_VIS, $TOU_DT)
    {
        return parent::select($this->getdata2_sql($VIN_WMIVDS, $VIN_VIS, $TOU_DT));
    }

    //20150611 Update End

    /**
     * リコールを取得.
     *
     * @param
     *
     * @return {parent} result
     */
    //20150611 Update Start
    //public function getdata3($VIN_WMIVDS, $VIN_VIS)
    //{
    //return parent::select($this -> getdata3_sql($VIN_WMIVDS, $VIN_VIS));
    //}
    public function getdata3($VIN_WMIVDS, $VIN_VIS, $TOU_DT)
    {
        return parent::select($this->getdata3_sql($VIN_WMIVDS, $VIN_VIS, $TOU_DT));
    }

    //20150611 Update End

    /**
     * パックｄｅメンテ,延長保証,ﾎﾞﾃﾞｨｺｰﾃｨﾝｸﾞを取得.
     *
     * @param {String} $data07
     *                         販社お客様No:$DLRCSRNO
     *                         ＶＩＮ－ＷＭＩＶＤＳ:$VIN_WMIVDS
     *                         ＶＩＮ－ＶＩＳ:$VIN_VIS
     *
     * @return {parent} result
     */
    //20150611 Update Start
    //public function getdata4($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS)
    //{
    //	return parent::select($this -> getdata4_sql($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS));
    //}
    public function getdata4($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS, $TOU_DT)
    {
        return parent::select($this->getdata4_sql($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS, $TOU_DT));
    }

    //20150611 Update End

    /**
     * クレジットを取得.
     *
     * @param {String} 注文書ＮＯ:$CMN_NO
     *
     * @return {parent} result
     */
    //20150611 Update Start
    //public function getdata5($CMN_NO)
    //{
    //	return parent::select($this -> getdata5_sql($CMN_NO));
    //}
    public function getdata5($CMN_NO, $TOU_DT)
    {
        return parent::select($this->getdata5_sql($CMN_NO, $TOU_DT));
    }

    //20150611 Update End

    /**
     * 保険を取得.
     *
     * @param {String}
     * 販社お客様No:$DLRCSRNO
     * ＶＩＮ－ＷＭＩＶＤＳ:$VIN_WMIVDS
     * ＶＩＮ－ＶＩＳ:$VIN_VIS
     *
     * @return {parent} result
     */
    //20150611 Update Start
    //public function getdata6($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS)
    //{
    //	return parent::select($this -> getdata6_sql($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS));
    //}
    public function getdata6($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS, $TOU_DT)
    {
        return parent::select($this->getdata6_sql($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS, $TOU_DT));
    }

    //20150611 Update End

    /**
     * 実績明細を取得.
     *
     * @param {String} 顧客コード:$post
     *
     * @return {parent} result
     */

    //20150611 Update Start
    //public function gettempdata($post, $VIN_WMIVDS, $VIN_VIS)
    //{
    //	return parent::select($this -> gettempdata_sql($post, $VIN_WMIVDS, $VIN_VIS));
    //}
    public function gettempdata($post, $VIN_WMIVDS, $VIN_VIS, $TOU_DT)
    {
        return parent::select($this->gettempdata_sql($post, $VIN_WMIVDS, $VIN_VIS, $TOU_DT));
    }

    //20150611 Update End

    /**
     * 初度登録年月を取得.
     *
     * @param {String}
     * ＶＩＮ－ＷＭＩＶＤＳ:$VIN_WMIVDS
     * ＶＩＮ－ＶＩＳ:$VIN_VIS
     *
     * @return {String} select文
     */
    public function getFRGMH_sql($VIN_WMIVDS, $VIN_VIS)
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= ' FRGMH ';
        $str_sql .= 'FROM ';
        $str_sql .= ' M41C03 ';
        $str_sql .= 'WHERE ';
        $str_sql .= "  VIN_WMIVDS='" . $VIN_WMIVDS . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  VIN_VIS='" . $VIN_VIS . "' ";

        return $str_sql;
    }

    /**
     * 入庫歴を取得.
     *
     * @param {String}
     * 販社お客様No:$DLRCSRNO
     * ＶＩＮ－ＷＭＩＶＤＳ:$VIN_WMIVDS
     * ＶＩＮ－ＶＩＳ:$VIN_VIS
     *
     * @return {String} select文
     */
    //20150611 Update Start
    //public function getdata1_sql($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS)
    public function getdata1_sql($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS)
    //20150611 Update End
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= '  XG11NKRKUNIT01, ';
        $str_sql .= '  XG11NKRKUNIT03, ';
        $str_sql .= '  XG11NKRKUNIT06, ';
        $str_sql .= '  XG11NKRKUNIT12, ';
        $str_sql .= '  XG11NKRKUNIT18, ';
        $str_sql .= '  XG11NKRKUNIT24, ';
        $str_sql .= '  XG11NKRKUNIT30, ';
        $str_sql .= '  XG11NKRKUNIT36, ';
        $str_sql .= '  XG11NKRKUNIT42, ';
        $str_sql .= '  XG11NKRKUNIT48, ';
        $str_sql .= '  XG11NKRKUNIT54, ';
        $str_sql .= '  XG11NKRKUNIT60, ';
        $str_sql .= '  XG11NKRKUNIT66, ';
        $str_sql .= '  XG11NKRKUNIT72, ';
        $str_sql .= '  XG11NKRKUNIT78, ';
        $str_sql .= '  XG11NKRKUNIT84, ';
        $str_sql .= '  XG11NKRKUNIT90, ';
        $str_sql .= '  XG11NKRKUNIT96, ';
        $str_sql .= '  XG11NKRKUNIT102, ';
        $str_sql .= '  XG11NKRKUNIT108, ';
        $str_sql .= '  XG11NKRKUNIT114, ';
        $str_sql .= '  XG11NKRKUNIT120, ';
        $str_sql .= '  XG11NKRKUNIT126, ';
        $str_sql .= '  XG11NKRKUNIT132 ';
        $str_sql .= 'FROM ';
        $str_sql .= '  M41C04 ';
        $str_sql .= 'WHERE ';
        $str_sql .= "  DLRCSRNO='" . $DLRCSRNO . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  VIN_WMIVDS='" . $VIN_WMIVDS . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  VIN_VIS='" . $VIN_VIS . "' ";

        return $str_sql;
    }

    /**
     * 定期点検を取得.
     *
     * @param
     *
     * @return {String} select文
     */

    //20150611 Update Start
    //public function getdata2_sql($VIN_WMIVDS, $VIN_VIS,$TOU_DT)
    public function getdata2_sql($VIN_WMIVDS, $VIN_VIS, $TOU_DT)
    //20150611 Update End
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= '  NKO_DT ';
        $str_sql .= 'FROM ';
        $str_sql .= '  BTH28SD2 ';
        $str_sql .= 'WHERE ';
        $str_sql .= "  VIN_SDI_KAT='" . $VIN_WMIVDS . "'";
        $str_sql .= 'AND ';
        $str_sql .= "  VIN_RBN='" . $VIN_VIS . "'";
        $str_sql .= 'AND ';
        $str_sql .= '  DIH_NKO_KB>=10 ';
        $str_sql .= 'AND ';
        $str_sql .= '  DIH_NKO_KB<=19 ';

        return $str_sql;
    }

    /**
     * リコールを取得.
     *
     * @param
     *
     * @return {String} select文
     */
    //20150611 Update Start
    //public function getdata3_sql($VIN_WMIVDS, $VIN_VIS)
    public function getdata3_sql($VIN_WMIVDS, $VIN_VIS, $TOU_DT)
    //20150611 Update End
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= '  BTH28SD2.VIN_SDI_KAT, ';
        $str_sql .= '  BTH28SD2.VIN_RBN, ';
        $str_sql .= '  BTH28SD2.NKO_DT, ';
        $str_sql .= '  BTH28SD4.SAG_NM ';
        $str_sql .= 'FROM ';
        $str_sql .= '  BTH28SD2, ';
        //			$str_sql .= "  BTH28SD3, ";
        $str_sql .= '  BTH28SD4  ';

        $str_sql .= 'WHERE ';
        $str_sql .= '  BTH28SD2.VIN_SDI_KAT = BTH28SD4.VIN_SDI_KAT ';
        $str_sql .= 'AND ';
        $str_sql .= '  BTH28SD2.VIN_RBN = BTH28SD4.VIN_RBN ';
        $str_sql .= 'AND ';
        $str_sql .= '  BTH28SD2.SEB_NOU_NO = BTH28SD4.SEB_NOU_NO ';

        //			$str_sql .= "AND ";
        //			$str_sql .= "  BTH28SD2.VIN_SDI_KAT = BTH28SD3.VIN_SDI_KAT ";
        //			$str_sql .= "AND ";
        //			$str_sql .= "  BTH28SD2.VIN_RBN = BTH28SD3.VIN_RBN ";
        //			$str_sql .= "AND ";
        //			$str_sql .= "  BTH28SD2.SEB_NOU_NO = BTH28SD3.SEB_NOU_NO ";

        $str_sql .= 'AND ';
        //20180924 Update Start
        //$str_sql .= "  BTH28SD4.SAG_NM like '%リコール%' ";
        //$str_sql .= "  ( BTH28SD4.SAG_NM like '%リコール%' OR  BTH28SD3.GOYOMEI  like '%リコール%' ) ";
        $str_sql .= "  BTH28SD2.DIH_NKO_KB='74' ";
        //20180924 Update End

        $str_sql .= 'AND ';
        $str_sql .= "  BTH28SD2.VIN_SDI_KAT='" . $VIN_WMIVDS . "'";
        $str_sql .= 'AND ';
        $str_sql .= "  BTH28SD2.VIN_RBN='" . $VIN_VIS . "'";
        //20150611 Add Start
        $str_sql .= 'AND ';
        $str_sql .= "  BTH28SD2.NKO_DT >='" . $TOU_DT . "'";
        //20150611 Add End

        return $str_sql;
    }

    /**
     * パックｄｅメンテ,延長保証,ﾎﾞﾃﾞｨｺｰﾃｨﾝｸﾞを取得.
     *
     * @param {String} $data07
     *                         販社お客様No:$DLRCSRNO
     *                         ＶＩＮ－ＷＭＩＶＤＳ:$VIN_WMIVDS
     *                         ＶＩＮ－ＶＩＳ:$VIN_VIS
     *
     * @return {String} select文
     */
    //20150611 Update Start
    //public function getdata4_sql($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS)
    public function getdata4_sql($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS, $TOU_DT)
    //20150611 Update End
    {
        $str_sql = '';
        //20151116 Update Start
        //			$str_sql .= "SELECT ";
        //			$str_sql .= "  M41C67.SOH_NM, ";
        //			$str_sql .= "  M41C10.SSC_STA_DT, ";
        //			$str_sql .= "  M41C10.KYK_EXR_DT ";
        //			$str_sql .= "FROM ";
        //			$str_sql .= "  M41C67, ";
        //			$str_sql .= "  M41C10 ";
        //			$str_sql .= "WHERE ";
        //			$str_sql .= "  M41C67.SOH_CD=M41C10.SOH_CD ";
        //			$str_sql .= "AND ";
        //			$str_sql .= "  M41C10.DLRCSRNO='" . $DLRCSRNO . "' ";
        //			$str_sql .= "AND ";
        //			$str_sql .= "  M41C10.VIN_WMIVDS='" . $VIN_WMIVDS . "' ";
        //			$str_sql .= "AND ";
        //			$str_sql .= "  M41C10.VIN_VIS='" . $VIN_VIS . "' ";
        ////20150611 Add Start
        //			$str_sql .= "AND ";
        //			$str_sql .= "  M41C10.SSC_STA_DT>='" . $TOU_DT . "' ";
        //20150611 Add End

        $str_sql .= 'SELECT ';
        $str_sql .= '  M41C67.SOH_NM, ';
        $str_sql .= '  BTH41C10.SSC_STA_DT, ';
        $str_sql .= '  BTH41C10.KYK_EXR_DT ';
        $str_sql .= 'FROM ';
        $str_sql .= '  M41C67, ';
        $str_sql .= '  BTH41C10 ';
        $str_sql .= 'WHERE ';
        $str_sql .= '  M41C67.SOH_CD=BTH41C10.SOH_CD ';
        $str_sql .= 'AND ';
        $str_sql .= "  BTH41C10.DLRCSRNO='" . $DLRCSRNO . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  BTH41C10.VIN_WMIVDS='" . $VIN_WMIVDS . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  BTH41C10.VIN_VIS='" . $VIN_VIS . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  BTH41C10.SSC_STA_DT>='" . $TOU_DT . "' ";
        //20151116 Update End

        return $str_sql;
    }

    /**
     * クレジットを取得.
     *
     * @param {String} 注文書ＮＯ:$CMN_NO
     *
     * @return {String} select文
     */
    //20150611 Update Start
    //		public function getdata5_sql($CMN_NO)
    public function getdata5_sql($CMN_NO, $TOU_DT)
    //20150611 Update End
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= '  M28M66.SCD_NM, ';
        $str_sql .= '  M41E10.KRJ_SHR_KKN_FRO, ';
        $str_sql .= '  M41E10.KRJ_SHR_KKN_TO ';
        $str_sql .= 'FROM ';
        $str_sql .= '  M28M66, ';
        $str_sql .= '  M41E10, ';
        $str_sql .= '  M41C04 ';
        $str_sql .= 'WHERE ';
        $str_sql .= '  M28M66.SCD_VAL=M41E10.CREDITCD ';
        $str_sql .= 'AND ';
        $str_sql .= "  M28M66.SCD_SYSID='F' ";
        $str_sql .= 'AND ';
        $str_sql .= "  M28M66.SCD_ID='01' ";
        $str_sql .= 'AND ';
        $str_sql .= "  M41E10.CMN_NO='" . $CMN_NO . "' ";
        $str_sql .= 'AND ';
        $str_sql .= '  M41E10.CMN_NO= M41C04.ORDERNO ';
        $str_sql .= 'AND ';
        $str_sql .= '  M41E10.KRJ_SHR_KKN_FRO >= M41E10.TOU_DT ';

        return $str_sql;
    }

    /**
     * 保険を取得.
     *
     * @param {String}
     * 販社お客様No:$DLRCSRNO
     * ＶＩＮ－ＷＭＩＶＤＳ:$VIN_WMIVDS
     * ＶＩＮ－ＶＩＳ:$VIN_VIS
     *
     * @return {String} select文
     */
    //20150611 Update Start
    //public function getdata6_sql($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS)
    public function getdata6_sql($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS, $TOU_DT)
    //20150611 Update End
    {
        $str_sql = 'SELECT ';
        $str_sql .= ' MCI_0170.KAISYACD as NI_DTRISCCD,';
        $str_sql .= ' MCI_M0120.KAISYAMEI SONPO_NM ';
        $str_sql .= 'FROM ';
        $str_sql .= '  MCI_0170 ,';
        $str_sql .= ' MCI_M0120 ';
        $str_sql .= 'WHERE ';
        //$str_sql .= " MCI_0170.KOKYAKUCD = '" . $DLRCSRNO . "' and ";
        $str_sql .= " MCI_0170.SYADAINO = '" . $VIN_WMIVDS . '-' . $VIN_VIS . "' and ";
        $str_sql .= ' MCI_0170.MOSTLONG_ENTRYNO = ';
        $str_sql .= '(SELECT ';
        $str_sql .= '  MAX(MOSTLONG_ENTRYNO) ';
        $str_sql .= ' FROM ';
        $str_sql .= '  MCI_0170 ';
        $str_sql .= ' WHERE ';
        //$str_sql .= "  KOKYAKUCD = '" . $DLRCSRNO . "' and ";
        $str_sql .= "  SYADAINO = '" . $VIN_WMIVDS . '-' . $VIN_VIS . "' ) AND ";
        $str_sql .= '  MCI_0170.KAISYACD=MCI_M0120.KAISYACD(+)';

        return $str_sql;
    }

    /**
     * 実績明細を取得.
     *
     * @param {String} 顧客コード:$post
     *
     * @return {String} select文
     */
    //20150611 Update Start
    //public function gettempdata_sql($post, $VIN_WMIVDS, $VIN_VIS)
    public function gettempdata_sql($post, $VIN_WMIVDS, $VIN_VIS, $TOU_DT)
    //20150611 Update End
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= '   MCI_0170.SYARYO , ';
        $str_sql .= '   MCI_MINMAX.HOKENSIKI,';
        $str_sql .= '   MCI_MINMAX.HOKENSYUKI';
        $str_sql .= ' FROM ';
        $str_sql .= ' ( SELECT * ';
        $str_sql .= '   FROM ';
        $str_sql .= '    MCI_0170 ';
        $str_sql .= '   WHERE ';
        $str_sql .= "     SYADAINO = '" . $VIN_WMIVDS . '-' . $VIN_VIS . "'";
        $str_sql .= '    AND MCI_0170.MOSTLONG_ENTRYNO =';
        $str_sql .= '   (SELECT ';
        $str_sql .= '     MAX(MOSTLONG_ENTRYNO) ';
        $str_sql .= '    FROM ';
        $str_sql .= '     MCI_0170 ';
        $str_sql .= '    WHERE ';
        $str_sql .= "     SYADAINO = '" . $VIN_WMIVDS . '-' . $VIN_VIS . "')";
        $str_sql .= '  ) MCI_0170 ,';
        $str_sql .= '  (SELECT';
        $str_sql .= '    SYOKENNO, ';
        $str_sql .= '    MIN(HOKENSIKI) HOKENSIKI,';
        $str_sql .= '    MAX(HOKENSYUKI) HOKENSYUKI';
        $str_sql .= '   FROM ';
        $str_sql .= '    MCI_0170 ';
        $str_sql .= '   WHERE ';
        $str_sql .= "    SYADAINO = '" . $VIN_WMIVDS . '-' . $VIN_VIS . "' ";
        $str_sql .= '   GROUP BY ';
        $str_sql .= '    SYOKENNO  ) MCI_MINMAX ';

        $str_sql .= '   WHERE ';
        $str_sql .= '   MCI_0170.SYOKENNO = MCI_MINMAX.SYOKENNO ';
        //20150611 Add Start
        $str_sql .= '   AND ';
        $str_sql .= "   MCI_MINMAX.HOKENSIKI >='" . $TOU_DT . "'";
        //20150611 Add End

        return $str_sql;
    }
}