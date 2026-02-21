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
 * 20210219           \99.提供資料\20210217\20210217_SDH_ログイン後の仕様変更.xlsx                       依頼                           CI
 * --------------------------------------------------------------------------------------------
 */

// 共通クラスの読込み

namespace App\Model\SDH;

use App\Model\Component\ClsComDb;

class SDH02 extends ClsComDb
{
    /**
     * リモートホスト・部署情報を取得するSQL文取得する。
     *
     * @param {String} $tenpo_cd1 店舗コード1
     * @param {String} $tenpo_cd2 店舗コード2
     *
     * @return {String} select文
     */
    public function m_select_busyo_all_sql($tenpo_cd1, $tenpo_cd2)
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= '  BUSYO_CD, ';
        $str_sql .= '  BUSYO_RYKNM, ';
        $str_sql .= '  DSP_SEQNO ';
        $str_sql .= 'FROM ';
        $str_sql .= '  HBUSYO ';
        $str_sql .= 'WHERE ';
        $str_sql .= "  BUSYO_CD >= '" . $tenpo_cd1 . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  BUSYO_CD LIKE '__0%' ";
        $str_sql .= 'AND ';
        $str_sql .= '  DSP_SEQNO IS NOT NULL ';
        $str_sql .= 'UNION ALL ';
        $str_sql .= 'SELECT ';
        $str_sql .= '  BUSYO_CD, ';
        $str_sql .= '  BUSYO_RYKNM, ';
        $str_sql .= "  CASE WHEN DSP_SEQNO IS NULL THEN '999' ELSE DSP_SEQNO END ";
        $str_sql .= 'FROM ';
        $str_sql .= '  HBUSYO ';
        $str_sql .= 'WHERE ';
        $str_sql .= "  BUSYO_CD in ('" . $tenpo_cd2 . "') ";
        $str_sql .= 'ORDER BY ';
        $str_sql .= '  DSP_SEQNO ';

        return $str_sql;
    }

    /**
     * 部署情報を取得するSQL文取得する。
     *
     * @param {String} $tenpo_cd 店舗コード
     *
     * @return {String} select文
     */
    public function m_select_busyo_sql($tenpo_cd)
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= '  BUSYO_CD, ';
        $str_sql .= '  BUSYO_RYKNM, ';
        $str_sql .= '  DSP_SEQNO ';
        $str_sql .= 'FROM ';
        $str_sql .= '  HBUSYO ';
        $str_sql .= 'WHERE ';
        $str_sql .= "  BUSYO_CD = '" . $tenpo_cd . "' ";

        return $str_sql;
    }

    //20210219 CI INS S

    /**
     * 本部ユーザの店舗を取得するSQL文取得する。
     *
     * @return {String} select文
     */
    public function gethonbudatasql($ip)
    {
        $arr_ip = explode('.', $ip);
        $ipdata = $arr_ip[2];

        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= ' IP_ADDRESS';
        $str_sql .= ' FROM ';
        $str_sql .= '  HKTNIPTABLES ';
        $str_sql .= 'WHERE ';
        $str_sql .= "  IP_ADDRESS = '" . $ipdata . "' ";

        return $str_sql;
    }

    /**
     * 一般ユーザの店舗を取得するSQL文取得する。
     *
     * @return {String} select文
     */
    public function getyippanndatasql($user_id)
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= '  BUSYO_CD';
        $str_sql .= ' FROM ';
        $str_sql .= '  HHAIZOKU ';
        $str_sql .= 'WHERE ';
        $str_sql .= "  SYAIN_NO = '" . $user_id . "' AND END_DATE IS NULL";

        return $str_sql;
    }

    //20210219 CI INS E

    /**
     * リモートホスト・社員全員情報を取得するSQL文取得する。
     *
     * @return {String} select文
     */
    public function m_select_syain_all_sql()
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= '  M29MA4.SYAIN_NO AS SYAIN_NO, ';
        $str_sql .= '  M29MA4.SYAIN_KNJ_SEI AS SYAIN_KNJ_SEI, ';
        $str_sql .= '  M29MA4.SYAIN_KNJ_MEI AS SYAIN_KNJ_MEI, ';
        $str_sql .= '  M29MA4.HANSH_CD AS HANSH_CD, ';
        $str_sql .= '  M29MA4.KYOTN_CD AS KYOTN_CD, ';
        $str_sql .= '  M27M01.KYOTN_NM AS KYOTN_NM ';
        $str_sql .= 'FROM ';
        $str_sql .= '  M27M01 ';
        $str_sql .= 'INNER JOIN ';
        $str_sql .= '  M29MA4 ';
        $str_sql .= 'ON ';
        $str_sql .= '  M29MA4.HANSH_CD  = M27M01.HANSH_CD ';
        $str_sql .= 'AND ';
        $str_sql .= '  M29MA4.KYOTN_CD = M27M01.KYOTN_CD ';
        $str_sql .= 'AND ';
        $str_sql .= '  M29MA4.RISYOKU_DATE IS NULL ';
        $str_sql .= 'AND ';
        $str_sql .= "  M29MA4.ES_KB='E'";

        return $str_sql;
    }

    /**
     * リモートホスト・社員情報を取得するSQL文取得する。
     *
     * @param {String} $tenpo_cd 店舗コード
     *
     * @return {String} select文
     */
    public function m_select_syain_sql($tenpo_cd)
    {
        $tenpo_cd_2 = '';
        $tenpo_cd_2 = substr($tenpo_cd, 0, 2);

        $str_sql = '';
        $str_sql .= 'SELECT ' . "\r\n";
        $str_sql .= '  HSYAINMST.SYAIN_NO, ' . "\r\n";
        $str_sql .= '  HSYAINMST.SYAIN_NM, ' . "\r\n";
        $str_sql .= '  HSYAINMST.SYAIN_NO' . "\r\n";
        $str_sql .= 'FROM ' . "\r\n";
        $str_sql .= '  HSYAINMST, ' . "\r\n";
        $str_sql .= '  HHAIZOKU' . "\r\n";
        $str_sql .= 'WHERE ' . "\r\n";
        $str_sql .= 'HSYAINMST.SYAIN_NO = HHAIZOKU.SYAIN_NO AND ' . "\r\n";
        $str_sql .= 'HHAIZOKU.END_DATE IS NULL AND ' . "\r\n";
        $str_sql .= 'rtrim(HSYAINMST.TAISYOKU_DATE) IS NULL AND ' . "\r\n";
        $str_sql .= "HSYAINMST.SLSSUTAFF_KB <> '9' AND " . "\r\n";
        $str_sql .= 'HSYAINMST.SIKAKU_CD IS NOT NULL AND ' . "\r\n";
        $str_sql .= "HSYAINMST.SIKAKU_CD <> '99' AND " . "\r\n";
        //        $str_sql .= "HSYAINMST.SIKAKU_CD <> '50' AND " . "\r\n";
        $str_sql .= "HHAIZOKU.BUSYO_CD LIKE '" . $tenpo_cd_2 . "%'" . "\r\n";

        return $str_sql;
    }

    /**
     * リモートホスト・部署情報を取得する。
     *
     * @param {String} $tenpo_cd1 店舗コード1
     * @param {String} $tenpo_cd2 店舗コード2
     *
     * @return {String} select結果
     */
    public function m_select_busyo_all($tenpo_cd1, $tenpo_cd2)
    {
        $str_sql = $this->m_select_busyo_all_sql($tenpo_cd1, $tenpo_cd2);

        return parent::select($str_sql);
    }

    /**
     * 部署情報を取得する。
     *
     * @param {String} $tenpo_cd 店舗コード1
     *
     * @return {String} select結果
     */
    public function m_select_busyo($tenpo_cd)
    {
        $str_sql = $this->m_select_busyo_sql($tenpo_cd);

        return parent::select($str_sql);
    }

    /**
     *　リモートホスト・社員全員情報を取得する。
     *
     * @return {String} select結果
     */
    public function m_select_syain_all()
    {
        $str_sql = $this->m_select_syain_all_sql();

        return parent::select($str_sql);
    }

    /**
     * リモートホスト・社員情報を取得する。
     *
     * @param {String} $tenpo_cd 店舗コード
     *
     * @return {String} select結果
     */
    public function m_select_syain($tenpo_cd)
    {
        $str_sql = $this->m_select_syain_sql($tenpo_cd);

        return parent::select($str_sql);
    }

    public function m_select_yakusyokcd_eskb_sql($userid)
    {
        $str_sql = '';
        $str_sql .= 'SELECT ' . "\r\n";
        $str_sql .= ' M29MA4.YAKUSYOK_CD, ' . "\r\n";
        $str_sql .= ' M29MA4.ES_KB ' . "\r\n";
        $str_sql .= 'FROM ' . "\r\n";
        $str_sql .= ' M29MA4 ' . "\r\n";
        $str_sql .= 'WHERE ' . "\r\n";
        $str_sql .= " M29MA4.SYAIN_NO = '@userid' " . "\r\n";
        $str_sql .= 'AND ' . "\r\n";
        $str_sql .= ' M29MA4.RISYOKU_DATE IS NULL  ' . "\r\n";
        $str_sql = str_replace('@userid', $userid, $str_sql);

        return $str_sql;
    }

    /**
     * ログイン後の 検索条件指示画面の動作について.
     *
     * @param {String} $userid
     */
    public function m_select_yakusyokcd_eskb($userid)
    {
        $str_sql = $this->m_select_yakusyokcd_eskb_sql($userid);

        return parent::select($str_sql);
    }

    //20210219 CI INS S

    /**
     * 本部ユーザの店舗を取得について.
     *
     * @param {String} $ip
     */
    public function gethonbudata($ip)
    {
        $str_sql = $this->gethonbudatasql($ip);

        return parent::select($str_sql);
    }

    /**
     * 一般ユーザの店舗を取得について.
     *
     * @param {String} $user_id
     */
    public function getyippanndata($user_id)
    {
        $str_sql = $this->getyippanndatasql($user_id);

        return parent::select($str_sql);
    }

    //20210219 CI INS E
}