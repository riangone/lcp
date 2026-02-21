<?php
/**
 * 説明：
 *
 *
 * @author zhenghuiyun
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20150526           ---                       新規                           FCSDL
 * 20160127           #2373                     依頼                           li
 * 20190227           #2870                     依頼                           YIN
 * 20220121           機能追加　　　　　　          N6対応                         Sun
 * 20240929           「保存に失敗しました」エラーが発生する現象が発生しました      caina
 * 20241119    「保存に失敗しました」エラーが発生する現象が発生しました「修正」      caina
 * --------------------------------------------------------------------------------------------
 */

// 共通クラスの読込み
namespace App\Model\SDH;

use App\Model\Component\ClsComDb;

class SDH01_01 extends ClsComDb
{
    /**
     * カラム情報取得.
     *
     * @param {arrayr}$values
     * 車検日:SYAKENBI
     * 車台番号:SYADAI
     * カーNo:CARNO
     * 販社コード :$HANSH_CD
     *
     * @return {parent} result
     */
    public function m_select_sdh01_01($values, $HANSH_CD, $nengetu)
    {
        $str_sql = $this->m_select_sdh01_01_sql($values, $HANSH_CD, $nengetu);

        return parent::select($str_sql);
    }

    //--- 20160127 li INS S
    public function m_select_sdh01_01_sinsya($values, $HANSH_CD, $con4)
    {
        $str_sql = $this->m_select_sdh01_01_sinsya_sql($values, $HANSH_CD, $con4);

        return parent::select($str_sql);
    }

    //--- 20160127 li INS E

    //20190227 YIN INS S
    public function m_select_sdh01_01_chuko($values, $HANSH_CD, $con4)
    {
        $str_sql = $this->m_select_sdh01_01_chuko_sql($values, $HANSH_CD, $con4);

        return parent::select($str_sql);
    }

    //20190227 YIN INS E

    /**
     * カラム情報挿入.
     *
     * @param {arrayr} $data
     *                       車検日:SYAKENBI
     *                       判定年月:YYMM
     *                       車台番号:SYADAI
     *                       カーNo:CARNO
     *                       管理部署コード:KANRIBU
     *                       管理サービス部署コード:KANRISV
     *                       管理担当スタッフ:KANRISLS
     *                       判定コード１:HANTEI1_CD
     *                       判定文１:HANTEI1
     *                       判定コード２:HANTEI2_CD
     *                       判定文２:HANTEI2
     *                       判定コード３:HANTEI3_CD
     *                       判定文３:HANTEI3
     *                       判定コード４:HANTEI4_CD
     *                       判定文４:HANTEI4
     *                       判定コード５:HANTEI5_CD
     *                       判定文５:HANTEI5
     *                       判定コード６:HANTEI6_CD
     *                       判定文６:HANTEI6
     *                       判定コード７:HANTEI7_CD
     *                       判定文７:HANTEI7
     *                       最終結果コード:KEKKA_CD
     *                       最終結果:KEKKA
     *                       車検数:SYAKEN_SU
     *                       リビジョン:REVISION
     *                       更新担当者:UPDSYACD
     *
     * @return {parent} result
     */
    public function m_insert_sdh01_01($data)
    {
        $str_sql = $this->m_insert_sdh01_01_sql($data);

        return parent::Do_Execute($str_sql);
    }

    //--- 20160127 li INS S
    public function m_insert_sdh01_01_sinsya($data)
    {
        $str_sql = $this->m_insert_sdh01_01_sinsya_sql($data);

        return parent::Do_Execute($str_sql);
    }

    //--- 20160127 li INS E

    //20190227 YIN INS S
    public function m_insert_sdh01_01_chuko($data)
    {
        $str_sql = $this->m_insert_sdh01_01_chuko_sql($data);

        return parent::Do_Execute($str_sql);
    }

    //20190227 YIN INS E

    /**
     * カラム情報更新.
     *
     * @param {arrayr} $values
     *                         車検日:SYAKENBI
     *                         判定年月:YYMM
     *                         車台番号:SYADAI
     *                         カーNo:CARNO
     *                         管理部署コード:KANRIBU
     *                         管理サービス部署コード:KANRISV
     *                         管理担当スタッフ:KANRISLS
     *                         判定コード１:HANTEI1_CD
     *                         判定文１:HANTEI1
     *                         判定コード２:HANTEI2_CD
     *                         判定文２:HANTEI2
     *                         判定コード３:HANTEI3_CD
     *                         判定文３:HANTEI3
     *                         判定コード４:HANTEI4_CD
     *                         判定文４:HANTEI4
     *                         判定コード５:HANTEI5_CD
     *                         判定文５:HANTEI5
     *                         判定コード６:HANTEI6_CD
     *                         判定文６:HANTEI6
     *                         判定コード７:HANTEI7_CD
     *                         判定文７:HANTEI7
     *                         最終結果コード:KEKKA_CD
     *                         最終結果:KEKKA
     *                         車検数:SYAKEN_SU
     *                         リビジョン:REVISION
     *                         更新担当者:UPDSYACD
     *
     * @return {parent} result
     */
    public function m_update_sdh01_01($values)
    {
        return parent::Do_Execute($this->m_update_sdh01_01_sql($values));
    }

    //--- 20160127 li INS S
    public function m_update_sdh01_01_sinsya($values)
    {
        return parent::Do_Execute($this->m_update_sdh01_01_sinsya_sql($values));
    }

    //--- 20160127 li INS E

    //20190227 YIN INS S
    public function m_update_sdh01_01_chuko($values)
    {
        return parent::Do_Execute($this->m_update_sdh01_01_chuko_sql($values));
    }

    //20190227 YIN INS E

    /**
     * フリーメモを取得.
     *
     * @param {arrayr} $data07
     *                         車台番号:SYADAI
     *                         カーNo:CARNO
     *
     * @return {parent} result
     */
    public function m_select_sdh01_07($data07)
    {
        $str_sql = $this->m_select_sdh01_07_sql($data07);

        return parent::select($str_sql);
    }

    /**
     * フリーメモを更新.
     *
     * @param {arrayr} $data07
     *                         車台番号:SYADAI
     *                         カーNo:CARNO
     *                         フリーメモ:MEMO
     *
     * @return {parent} result
     */
    public function m_update_sdh01_07($data07)
    {
        $str_sql = $this->m_update_sdh01_07_sql($data07);

        return parent::Do_Execute($str_sql);
    }

    /**
     * フリーメモを挿入.
     *
     * @param {arrayr} $data07
     *                         車台番号:SYADAI
     *                         カーNo:CARNO
     *                         フリーメモ:MEMO
     *
     * @return {parent} result
     */
    public function m_insert_sdh01_07($data07)
    {
        $str_sql = $this->m_insert_sdh01_07_sql($data07);

        return parent::Do_Execute($str_sql);
    }

    /**
     * カラム情報の最大件数を取得.
     *
     * @param {arrayr} $data
     *                       車検日:SYAKENBI
     *                       判定年月:YYMM
     *                       車台番号:SYADAI
     *                       カーNo:CARNO
     *
     * @return {parent} result
     */
    public function m_sel_before_ins_sdh01_01($data)
    {
        return parent::select($this->m_sel_before_ins_sdh01_01_sql($data));
    }

    //--- 20160127 li INS S
    public function m_sel_before_ins_sdh01_01_sinsya($data)
    {
        return parent::select($this->m_sel_before_ins_sdh01_01_sinsya_sql($data));
    }

    //--- 20160127 li INS E

    //20190227 YIN INS S
    public function m_sel_before_ins_sdh01_01_chuko($data)
    {
        return parent::select($this->m_sel_before_ins_sdh01_01_chuko_sql($data));
    }

    //20190227 YIN INS E

    //----20220121 sun add s
    public function m_select_gettencyou($user)
    {
        $str_sql = $this->m_select_get_tencyou_sql($user);

        return parent::select($str_sql);
    }

    public function m_select_getsinchoku($SYADAI, $CARNO, $TENPO)
    {
        $str_sql = $this->m_select_get_sinchoku_sql($SYADAI, $CARNO, $TENPO);

        return parent::select($str_sql);
    }

    public function m_insert_add_n6_data($SYADAI, $CARNO, $TENPO, $TANTO, $CHECKED)
    {
        $str_sql = $this->m_insert_add_n6_data_sql($SYADAI, $CARNO, $TENPO, $TANTO, $CHECKED);

        return parent::insert($str_sql);
    }

    public function m_update_upd_n6_data($SYADAI, $CARNO, $TENPO, $TANTO, $CHECKED)
    {
        $str_sql = $this->m_update_upd_n6_data_sql($SYADAI, $CARNO, $TENPO, $TANTO, $CHECKED);

        return parent::update($str_sql);
    }

    //----20220121 sun add e

    /**
     * カラム情報取得.
     *
     * @param {arrayr} $values
     *                         車検日:SYAKENBI
     *                         車台番号:SYADAI
     *                         カーNo:CARNO
     *                         販社コード :$HANSH_CD
     *
     * @return {parent} select文
     */
    public function m_select_sdh01_01_sql($values, $HANSH_CD, $nengetu)
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= '  SYAKENBI, ';
        $str_sql .= '  YYMM, ';
        $str_sql .= '  SYADAI, ';
        $str_sql .= '  CARNO, ';
        //20160308 Del St
        //$str_sql .= "  KANRIBU, ";
        //$str_sql .= "  KANRISV, ";
        //$str_sql .= "  KANRISLS, ";
        //20160308 Del Ed
        $str_sql .= '  HANTEI1_CD, ';
        //20150422 fugyolin edit start
        $str_sql .= "   (SELECT ITEMNAME1  || ( CASE WHEN ITEMNAME2 IS NULL THEN ''   ELSE ':' || ITEMNAME2 END)  FROM HANTEITEIKEIMST WHERE HANTEITEIKEIMST.TEIKEI_CD = HANTEI1_CD AND HANTEITEIKEIMST.TEIKEI_TYPE='1' ) AS NAME1, ";
        $str_sql .= '  HANTEI1, ';
        $str_sql .= '  HANTEI2_CD, ';
        $str_sql .= "   (SELECT ITEMNAME1 || ( CASE WHEN ITEMNAME2 IS NULL THEN ''   ELSE ':' || ITEMNAME2 END) FROM HANTEITEIKEIMST WHERE HANTEITEIKEIMST.TEIKEI_CD = HANTEI2_CD AND HANTEITEIKEIMST.TEIKEI_TYPE='1' ) AS NAME2, ";
        $str_sql .= '  HANTEI2, ';
        $str_sql .= '  HANTEI3_CD, ';
        $str_sql .= "   (SELECT ITEMNAME1 || ( CASE WHEN ITEMNAME2 IS NULL THEN ''   ELSE ':' || ITEMNAME2 END) FROM HANTEITEIKEIMST WHERE HANTEITEIKEIMST.TEIKEI_CD = HANTEI3_CD AND HANTEITEIKEIMST.TEIKEI_TYPE='1' ) AS NAME3, ";
        $str_sql .= '  HANTEI3, ';
        $str_sql .= '  HANTEI4_CD, ';
        $str_sql .= "   (SELECT ITEMNAME1 || ( CASE WHEN ITEMNAME2 IS NULL THEN ''   ELSE ':' || ITEMNAME2 END) FROM HANTEITEIKEIMST WHERE HANTEITEIKEIMST.TEIKEI_CD = HANTEI4_CD AND HANTEITEIKEIMST.TEIKEI_TYPE='1' ) AS NAME4, ";
        $str_sql .= '  HANTEI4, ';
        $str_sql .= '  HANTEI5_CD, ';
        $str_sql .= "   (SELECT ITEMNAME1 || ( CASE WHEN ITEMNAME2 IS NULL THEN ''   ELSE ':' || ITEMNAME2 END) FROM HANTEITEIKEIMST WHERE HANTEITEIKEIMST.TEIKEI_CD = HANTEI5_CD AND HANTEITEIKEIMST.TEIKEI_TYPE='1' ) AS NAME5, ";
        $str_sql .= '  HANTEI5, ';
        $str_sql .= '  HANTEI6_CD, ';
        $str_sql .= "   (SELECT ITEMNAME1 || ( CASE WHEN ITEMNAME2 IS NULL THEN ''   ELSE ':' || ITEMNAME2 END) FROM HANTEITEIKEIMST WHERE HANTEITEIKEIMST.TEIKEI_CD = HANTEI6_CD AND HANTEITEIKEIMST.TEIKEI_TYPE='1' ) AS NAME6, ";
        $str_sql .= '  HANTEI6, ';
        $str_sql .= '  HANTEI7_CD, ';
        $str_sql .= "   (SELECT ITEMNAME1 || ( CASE WHEN ITEMNAME2 IS NULL THEN ''   ELSE ':' || ITEMNAME2 END) FROM HANTEITEIKEIMST WHERE HANTEITEIKEIMST.TEIKEI_CD = HANTEI7_CD AND HANTEITEIKEIMST.TEIKEI_TYPE='1' ) AS NAME7, ";
        $str_sql .= '  HANTEI7, ';
        $str_sql .= '  KEKKA_CD, ';
        $str_sql .= "   (SELECT ITEMNAME1 || ( CASE WHEN ITEMNAME2 IS NULL THEN ''   ELSE ':' || ITEMNAME2 END) FROM HANTEITEIKEIMST WHERE HANTEITEIKEIMST.TEIKEI_CD = KEKKA_CD AND HANTEITEIKEIMST.TEIKEI_TYPE='2' ) AS NAME, ";
        $str_sql .= '  KEKKA, ';
        $str_sql .= '  SYAKEN_SU, ';
        $str_sql .= '  REVISION, ';
        $str_sql .= "  LPAD(REVISION,3,'0') AS REVISION_SORT, ";
        $str_sql .= '  UPDSYACD, ';
        $str_sql .= '  UPDYMDHM, ';
        //--- 20160127 li INS S
        $str_sql .= "  to_char(sysdate,'YYYYMMDDHH24MI') AS SYSYMDHM, ";
        //--- 20160127 li INS E
        $str_sql .= "  M29MA4.SYAIN_KNJ_SEI || '' || M29MA4.SYAIN_KNJ_MEI AS TTS_SEIMEI  ";
        $str_sql .= 'FROM ';
        $str_sql .= '  HANTEILST, ';
        $str_sql .= '  M29MA4 ';
        $str_sql .= 'WHERE ';
        $str_sql .= "  YYMM = '" . $nengetu . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  SYADAI = '" . $values['SYADAI'] . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  CARNO = '" . $values['CARNO'] . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  M29MA4.HANSH_CD(+) = '" . $HANSH_CD . "' ";
        $str_sql .= 'AND ';
        $str_sql .= '  M29MA4.SYAIN_NO(+) = HANTEILST.UPDSYACD ';
        $str_sql .= 'ORDER BY ';
        $str_sql .= '  HANTEILST.UPDYMDHM ';

        $str_sql .= '  ,REVISION_SORT ';

        return $str_sql;
    }

    //--- 20160127 li INS S
    //カラム情報取得 新車１ヶ月点検、新車６ヶ月点検判定追加
    public function m_select_sdh01_01_sinsya_sql($values, $HANSH_CD, $con4)
    {
        $strType = '';
        if ('1' == $con4) {
            $strType = '3';
        }
        if ('2' == $con4) {
            $strType = '4';
        }
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= '  SYADAI, ';
        $str_sql .= '  CARNO, ';
        //20160308 Del St
        //$str_sql .= "  KANRIBU, ";
        //$str_sql .= "  KANRISV, ";
        //$str_sql .= "  KANRISLS, ";
        //20160308 Del Ed
        $str_sql .= "  '' as HANTEI1_CD, ";
        $str_sql .= "   (SELECT ITEMNAME1  || ( CASE WHEN ITEMNAME2 IS NULL THEN ''   ELSE ':' || ITEMNAME2 END)  FROM HANTEITEIKEIMST WHERE HANTEITEIKEIMST.TEIKEI_CD = '' AND HANTEITEIKEIMST.TEIKEI_TYPE='" . $strType . "' ) AS NAME1, ";
        $str_sql .= "  '' as HANTEI1, ";
        $str_sql .= "  '' as HANTEI2_CD, ";
        $str_sql .= "   (SELECT ITEMNAME1 || ( CASE WHEN ITEMNAME2 IS NULL THEN ''   ELSE ':' || ITEMNAME2 END) FROM HANTEITEIKEIMST WHERE HANTEITEIKEIMST.TEIKEI_CD = '' AND HANTEITEIKEIMST.TEIKEI_TYPE='" . $strType . "' ) AS NAME2, ";
        $str_sql .= "  '' as HANTEI2, ";
        $str_sql .= "  '' as HANTEI3_CD, ";
        $str_sql .= "   (SELECT ITEMNAME1 || ( CASE WHEN ITEMNAME2 IS NULL THEN ''   ELSE ':' || ITEMNAME2 END) FROM HANTEITEIKEIMST WHERE HANTEITEIKEIMST.TEIKEI_CD = '' AND HANTEITEIKEIMST.TEIKEI_TYPE='" . $strType . "' ) AS NAME3, ";
        $str_sql .= "  '' as HANTEI3, ";
        $str_sql .= "  '' as HANTEI4_CD, ";
        $str_sql .= "   (SELECT ITEMNAME1 || ( CASE WHEN ITEMNAME2 IS NULL THEN ''   ELSE ':' || ITEMNAME2 END) FROM HANTEITEIKEIMST WHERE HANTEITEIKEIMST.TEIKEI_CD = '' AND HANTEITEIKEIMST.TEIKEI_TYPE='" . $strType . "' ) AS NAME4, ";
        $str_sql .= "  '' as HANTEI4, ";
        $str_sql .= "  '' as HANTEI5_CD, ";
        $str_sql .= "   (SELECT ITEMNAME1 || ( CASE WHEN ITEMNAME2 IS NULL THEN ''   ELSE ':' || ITEMNAME2 END) FROM HANTEITEIKEIMST WHERE HANTEITEIKEIMST.TEIKEI_CD = '' AND HANTEITEIKEIMST.TEIKEI_TYPE='" . $strType . "' ) AS NAME5, ";
        $str_sql .= "  '' as HANTEI5, ";
        $str_sql .= "  '' as HANTEI6_CD, ";
        $str_sql .= "   (SELECT ITEMNAME1 || ( CASE WHEN ITEMNAME2 IS NULL THEN ''   ELSE ':' || ITEMNAME2 END) FROM HANTEITEIKEIMST WHERE HANTEITEIKEIMST.TEIKEI_CD = '' AND HANTEITEIKEIMST.TEIKEI_TYPE='" . $strType . "' ) AS NAME6, ";
        $str_sql .= "  '' as HANTEI6, ";
        $str_sql .= '  KEKKA1_CD as HANTEI7_CD, ';
        $str_sql .= "   (SELECT ITEMNAME1 || ( CASE WHEN ITEMNAME2 IS NULL THEN ''   ELSE ':' || ITEMNAME2 END) FROM HANTEITEIKEIMST WHERE HANTEITEIKEIMST.TEIKEI_CD = KEKKA1_CD AND HANTEITEIKEIMST.TEIKEI_TYPE='3' ) AS NAME7, ";
        $str_sql .= '  KEKKA1 as HANTEI7, ';
        $str_sql .= '  KEKKA6_CD as KEKKA_CD, ';
        $str_sql .= "   (SELECT ITEMNAME1 || ( CASE WHEN ITEMNAME2 IS NULL THEN ''   ELSE ':' || ITEMNAME2 END) FROM HANTEITEIKEIMST WHERE HANTEITEIKEIMST.TEIKEI_CD = KEKKA6_CD AND HANTEITEIKEIMST.TEIKEI_TYPE='4' ) AS NAME, ";
        $str_sql .= '  KEKKA6 as KEKKA, ';
        $str_sql .= "  '' as SYAKEN_SU, ";
        $str_sql .= '  REVISION, ';
        $str_sql .= "  LPAD(REVISION,3,'0') AS REVISION_SORT, ";
        $str_sql .= '  UPDSYACD, ';
        $str_sql .= '  UPDYMDHM, ';
        //--- 20160127 li INS S
        $str_sql .= "  to_char(sysdate,'YYYYMMDDHH24MI') AS SYSYMDHM, ";
        //--- 20160127 li INS E
        $str_sql .= "  M29MA4.SYAIN_KNJ_SEI || '' || M29MA4.SYAIN_KNJ_MEI AS TTS_SEIMEI  ";
        $str_sql .= 'FROM ';
        $str_sql .= '  HANTEILST_SINSYA, ';
        $str_sql .= '  M29MA4 ';
        $str_sql .= 'WHERE ';
        $str_sql .= "  SYADAI = '" . $values['SYADAI'] . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  CARNO = '" . $values['CARNO'] . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  M29MA4.HANSH_CD(+) = '" . $HANSH_CD . "' ";
        $str_sql .= 'AND ';
        $str_sql .= '  M29MA4.SYAIN_NO(+) = HANTEILST_SINSYA.UPDSYACD ';
        $str_sql .= 'ORDER BY ';
        $str_sql .= '  HANTEILST_SINSYA.UPDYMDHM ';

        $str_sql .= '  ,REVISION_SORT ';

        return $str_sql;
    }

    //--- 20160127 li INS E

    //20190227 YIN INS S
    //カラム情報取得 中古１ヶ月点検判定追加
    public function m_select_sdh01_01_chuko_sql($values, $HANSH_CD, $con4)
    {
        $strType = '';
        if ('3' == $con4) {
            $strType = '5';
        }
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= '  SYADAI, ';
        $str_sql .= '  CARNO, ';

        $str_sql .= "  '' as HANTEI1_CD, ";
        $str_sql .= "   (SELECT ITEMNAME1  || ( CASE WHEN ITEMNAME2 IS NULL THEN ''   ELSE ':' || ITEMNAME2 END)  FROM HANTEITEIKEIMST WHERE HANTEITEIKEIMST.TEIKEI_CD = '' AND HANTEITEIKEIMST.TEIKEI_TYPE='" . $strType . "' ) AS NAME1, ";
        $str_sql .= "  '' as HANTEI1, ";
        $str_sql .= "  '' as HANTEI2_CD, ";
        $str_sql .= "   (SELECT ITEMNAME1 || ( CASE WHEN ITEMNAME2 IS NULL THEN ''   ELSE ':' || ITEMNAME2 END) FROM HANTEITEIKEIMST WHERE HANTEITEIKEIMST.TEIKEI_CD = '' AND HANTEITEIKEIMST.TEIKEI_TYPE='" . $strType . "' ) AS NAME2, ";
        $str_sql .= "  '' as HANTEI2, ";
        $str_sql .= "  '' as HANTEI3_CD, ";
        $str_sql .= "   (SELECT ITEMNAME1 || ( CASE WHEN ITEMNAME2 IS NULL THEN ''   ELSE ':' || ITEMNAME2 END) FROM HANTEITEIKEIMST WHERE HANTEITEIKEIMST.TEIKEI_CD = '' AND HANTEITEIKEIMST.TEIKEI_TYPE='" . $strType . "' ) AS NAME3, ";
        $str_sql .= "  '' as HANTEI3, ";
        $str_sql .= "  '' as HANTEI4_CD, ";
        $str_sql .= "   (SELECT ITEMNAME1 || ( CASE WHEN ITEMNAME2 IS NULL THEN ''   ELSE ':' || ITEMNAME2 END) FROM HANTEITEIKEIMST WHERE HANTEITEIKEIMST.TEIKEI_CD = '' AND HANTEITEIKEIMST.TEIKEI_TYPE='" . $strType . "' ) AS NAME4, ";
        $str_sql .= "  '' as HANTEI4, ";
        $str_sql .= "  '' as HANTEI5_CD, ";
        $str_sql .= "   (SELECT ITEMNAME1 || ( CASE WHEN ITEMNAME2 IS NULL THEN ''   ELSE ':' || ITEMNAME2 END) FROM HANTEITEIKEIMST WHERE HANTEITEIKEIMST.TEIKEI_CD = '' AND HANTEITEIKEIMST.TEIKEI_TYPE='" . $strType . "' ) AS NAME5, ";
        $str_sql .= "  '' as HANTEI5, ";
        $str_sql .= "  '' as HANTEI6_CD, ";
        $str_sql .= "   (SELECT ITEMNAME1 || ( CASE WHEN ITEMNAME2 IS NULL THEN ''   ELSE ':' || ITEMNAME2 END) FROM HANTEITEIKEIMST WHERE HANTEITEIKEIMST.TEIKEI_CD = '' AND HANTEITEIKEIMST.TEIKEI_TYPE='" . $strType . "' ) AS NAME6, ";
        $str_sql .= "  '' as HANTEI6, ";
        $str_sql .= "  '' as HANTEI7_CD, ";
        $str_sql .= "   (SELECT ITEMNAME1 || ( CASE WHEN ITEMNAME2 IS NULL THEN ''   ELSE ':' || ITEMNAME2 END) FROM HANTEITEIKEIMST WHERE HANTEITEIKEIMST.TEIKEI_CD = '' AND HANTEITEIKEIMST.TEIKEI_TYPE='" . $strType . "' ) AS NAME7, ";
        $str_sql .= "  '' as HANTEI7, ";
        $str_sql .= '  KEKKA1_CD as KEKKA_CD, ';
        $str_sql .= "   (SELECT ITEMNAME1 || ( CASE WHEN ITEMNAME2 IS NULL THEN ''   ELSE ':' || ITEMNAME2 END) FROM HANTEITEIKEIMST WHERE HANTEITEIKEIMST.TEIKEI_CD = KEKKA1_CD AND HANTEITEIKEIMST.TEIKEI_TYPE='5' ) AS NAME, ";
        $str_sql .= '  KEKKA1 as KEKKA, ';
        $str_sql .= "  '' as SYAKEN_SU, ";
        $str_sql .= '  REVISION, ';
        $str_sql .= "  LPAD(REVISION,3,'0') AS REVISION_SORT, ";
        $str_sql .= '  UPDSYACD, ';
        $str_sql .= '  UPDYMDHM, ';
        $str_sql .= "  to_char(sysdate,'YYYYMMDDHH24MI') AS SYSYMDHM, ";
        $str_sql .= "  M29MA4.SYAIN_KNJ_SEI || '' || M29MA4.SYAIN_KNJ_MEI AS TTS_SEIMEI  ";
        $str_sql .= 'FROM ';
        $str_sql .= '  HANTEILST_CHUKO, ';
        $str_sql .= '  M29MA4 ';
        $str_sql .= 'WHERE ';
        $str_sql .= "  SYADAI = '" . $values['SYADAI'] . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  CARNO = '" . $values['CARNO'] . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  M29MA4.HANSH_CD(+) = '" . $HANSH_CD . "' ";
        $str_sql .= 'AND ';
        $str_sql .= '  M29MA4.SYAIN_NO(+) = HANTEILST_CHUKO.UPDSYACD ';
        $str_sql .= 'ORDER BY ';
        $str_sql .= '  HANTEILST_CHUKO.UPDYMDHM ';

        $str_sql .= '  ,REVISION_SORT ';

        return $str_sql;
    }

    //20190227 YIN INS E

    /**
     * カラム情報挿入.
     *
     * @param {arrayr} $data
     *                       車検日:SYAKENBI
     *                       判定年月:YYMM
     *                       車台番号:SYADAI
     *                       カーNo:CARNO
     *                       管理部署コード:KANRIBU
     *                       管理サービス部署コード:KANRISV
     *                       管理担当スタッフ:KANRISLS
     *                       判定コード１:HANTEI1_CD
     *                       判定文１:HANTEI1
     *                       判定コード２:HANTEI2_CD
     *                       判定文２:HANTEI2
     *                       判定コード３:HANTEI3_CD
     *                       判定文３:HANTEI3
     *                       判定コード４:HANTEI4_CD
     *                       判定文４:HANTEI4
     *                       判定コード５:HANTEI5_CD
     *                       判定文５:HANTEI5
     *                       判定コード６:HANTEI6_CD
     *                       判定文６:HANTEI6
     *                       判定コード７:HANTEI7_CD
     *                       判定文７:HANTEI7
     *                       最終結果コード:KEKKA_CD
     *                       最終結果:KEKKA
     *                       車検数:SYAKEN_SU
     *                       リビジョン:REVISION
     *                       更新担当者:UPDSYACD
     *
     * @return {String} select文
     */
    public function m_insert_sdh01_01_sql($data)
    {
        $str_sql = '';
        $str_sql .= 'INSERT INTO ';
        $str_sql .= '  HANTEILST ';
        $str_sql .= '( ';
        $str_sql .= '  SYAKENBI, ';
        $str_sql .= '  YYMM, ';
        $str_sql .= '  SYADAI, ';
        $str_sql .= '  CARNO, ';
        //20160308 Del St
        //$str_sql .= "  KANRIBU, ";
        //$str_sql .= "  KANRISV, ";
        //$str_sql .= "  KANRISLS, ";
        //20160308 Del Ed
        $str_sql .= '  HANTEI1_CD, ';
        $str_sql .= '  HANTEI1, ';
        $str_sql .= '  HANTEI2_CD, ';
        $str_sql .= '  HANTEI2, ';
        $str_sql .= '  HANTEI3_CD, ';
        $str_sql .= '  HANTEI3, ';
        $str_sql .= '  HANTEI4_CD, ';
        $str_sql .= '  HANTEI4, ';
        $str_sql .= '  HANTEI5_CD, ';
        $str_sql .= '  HANTEI5, ';
        $str_sql .= '  HANTEI6_CD, ';
        $str_sql .= '  HANTEI6, ';
        $str_sql .= '  HANTEI7_CD, ';
        $str_sql .= '  HANTEI7, ';
        $str_sql .= '  KEKKA_CD, ';
        $str_sql .= '  KEKKA, ';
        $str_sql .= '  SYAKEN_SU, ';
        $str_sql .= '  REVISION, ';
        $str_sql .= '  UPDSYACD, ';
        $str_sql .= '  UPDYMDHM ';
        $str_sql .= ') ';
        $str_sql .= 'VALUES ';
        $str_sql .= '( ';
        $str_sql .= "  '" . $data['SYAKENBI'] . "', ";
        $str_sql .= "  '" . $data['YYMM'] . "', ";
        $str_sql .= "  '" . $data['SYADAI'] . "', ";
        $str_sql .= "  '" . $data['CARNO'] . "', ";
        //$str_sql .= "  '" . $data['KANRIBU'] . "', ";
        //$str_sql .= "  '" . $data['KANRISV'] . "', ";
        //$str_sql .= "  '" . $data['KANRISLS'] . "', ";
        $str_sql .= "  '" . $data['HANTEI1_CD'] . "', ";
        $str_sql .= "  '" . $data['HANTEI1'] . "', ";
        $str_sql .= "  '" . $data['HANTEI2_CD'] . "', ";
        $str_sql .= "  '" . $data['HANTEI2'] . "', ";
        $str_sql .= "  '" . $data['HANTEI3_CD'] . "', ";
        $str_sql .= "  '" . $data['HANTEI3'] . "', ";
        $str_sql .= "  '" . $data['HANTEI4_CD'] . "', ";
        $str_sql .= "  '" . $data['HANTEI4'] . "', ";
        $str_sql .= "  '" . $data['HANTEI5_CD'] . "', ";
        $str_sql .= "  '" . $data['HANTEI5'] . "', ";
        $str_sql .= "  '" . $data['HANTEI6_CD'] . "', ";
        $str_sql .= "  '" . $data['HANTEI6'] . "', ";
        $str_sql .= "  '" . $data['HANTEI7_CD'] . "', ";
        $str_sql .= "  '" . $data['HANTEI7'] . "', ";
        $str_sql .= "  '" . $data['KEKKA_CD'] . "', ";
        $str_sql .= "  '" . $data['KEKKA'] . "', ";
        $str_sql .= "  '" . $data['SYAKEN_SU'] . "', ";
        $str_sql .= "  '" . $data['REVISION'] . "', ";
        $str_sql .= "  '" . $data['UPDSYACD'] . "', ";
        $str_sql .= "  to_char(sysdate,'yyyymmddhh24mi') ";
        $str_sql .= ') ';

        return $str_sql;
    }

    //--- 20160127 li INS S
    //* カラム情報挿入 新車１ヶ月点検、新車６ヶ月点検判定追加
    public function m_insert_sdh01_01_sinsya_sql($data)
    {
        // $strType = '';
        // if ('1' == $con4) {
        //     $strType = '3';
        // }
        // if ('2' == $con4) {
        //     $strType = '4';
        // }
        $str_sql = '';
        $str_sql .= 'INSERT INTO ';
        $str_sql .= '  HANTEILST_SINSYA ';
        $str_sql .= '( ';
        $str_sql .= '  SYADAI, ';
        $str_sql .= '  CARNO, ';
        //$str_sql .= "  KANRIBU, ";
        //$str_sql .= "  KANRISV, ";
        //$str_sql .= "  KANRISLS, ";
        $str_sql .= '  KEKKA1_CD, ';
        $str_sql .= '  KEKKA1, ';
        $str_sql .= '  KEKKA6_CD, ';
        $str_sql .= '  KEKKA6, ';
        $str_sql .= '  REVISION, ';
        $str_sql .= '  UPDSYACD, ';
        $str_sql .= '  UPDYMDHM ';
        $str_sql .= ') ';
        $str_sql .= 'VALUES ';
        $str_sql .= '( ';
        $str_sql .= "  '" . $data['SYADAI'] . "', ";
        $str_sql .= "  '" . $data['CARNO'] . "', ";
        //$str_sql .= "  '" . $data['KANRIBU'] . "', ";
        //$str_sql .= "  '" . $data['KANRISV'] . "', ";
        //$str_sql .= "  '" . $data['KANRISLS'] . "', ";
        $str_sql .= "  '" . $data['HANTEI7_CD'] . "', ";
        $str_sql .= "  '" . $data['HANTEI7'] . "', ";
        $str_sql .= "  '" . $data['KEKKA_CD'] . "', ";
        $str_sql .= "  '" . $data['KEKKA'] . "', ";
        $str_sql .= "  '" . $data['REVISION'] . "', ";
        $str_sql .= "  '" . $data['UPDSYACD'] . "', ";
        $str_sql .= "  to_char(sysdate,'yyyymmddhh24mi') ";
        $str_sql .= ') ';

        return $str_sql;
    }

    //--- 20160127 li INS E

    //20190227 YIN INS S
    //* カラム情報挿入 中古１ヶ月点検判定追加
    public function m_insert_sdh01_01_chuko_sql($data)
    {
        // $strType = '';
        // if ('3' == $con4) {
        //     $strType = '5';
        // }
        $str_sql = '';
        $str_sql .= 'INSERT INTO ';
        $str_sql .= '  HANTEILST_CHUKO ';
        $str_sql .= '( ';
        $str_sql .= '  SYADAI, ';
        $str_sql .= '  CARNO, ';
        $str_sql .= '  KEKKA1_CD, ';
        $str_sql .= '  KEKKA1, ';
        $str_sql .= '  REVISION, ';
        $str_sql .= '  UPDSYACD, ';
        $str_sql .= '  UPDYMDHM ';
        $str_sql .= ') ';
        $str_sql .= 'VALUES ';
        $str_sql .= '( ';
        $str_sql .= "  '" . $data['SYADAI'] . "', ";
        $str_sql .= "  '" . $data['CARNO'] . "', ";
        $str_sql .= "  '" . $data['KEKKA_CD'] . "', ";
        $str_sql .= "  '" . $data['KEKKA'] . "', ";
        $str_sql .= "  '" . $data['REVISION'] . "', ";
        $str_sql .= "  '" . $data['UPDSYACD'] . "', ";
        $str_sql .= "  to_char(sysdate,'yyyymmddhh24mi') ";
        $str_sql .= ') ';

        return $str_sql;
    }

    //20190227 YIN INS E

    /**
     * カラム情報の最大件数を取得.
     *
     * @param {arrayr} $data
     *                       車検日:SYAKENBI
     *                       判定年月:YYMM
     *                       車台番号:SYADAI
     *                       カーNo:CARNO
     *
     * @return {String} select文
     */
    public function m_sel_before_ins_sdh01_01_sql($data)
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        //20241119 caina upd s
        // $str_sql .= "  COUNT(REVISION) MAXREVISION ";
        $str_sql .= "  MAX(TO_NUMBER(REVISION)) AS MAXREVISION ";
        //20241119 caina upd e
        $str_sql .= 'FROM ';
        $str_sql .= '  HANTEILST ';
        $str_sql .= 'WHERE ';
        $str_sql .= "  SYAKENBI='" . $data['SYAKENBI'] . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  YYMM='" . $data['YYMM'] . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  SYADAI='" . $data['SYADAI'] . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  CARNO='" . $data['CARNO'] . "' ";

        return $str_sql;
    }

    //--- 20160127 li INS S
    //  カラム情報の最大件数を取得 新車１ヶ月点検、新車６ヶ月点検判定追加
    public function m_sel_before_ins_sdh01_01_sinsya_sql($data)
    {
        // $strType = '';
        // if ('1' == $con4) {
        //     $strType = '3';
        // }
        // if ('2' == $con4) {
        //     $strType = '4';
        // }
        $str_sql = '';
        $str_sql .= 'SELECT ';
        // 20241119 caina upd s
        // $str_sql .= "  COUNT(REVISION) MAXREVISION ";
        $str_sql .= "  MAX(TO_NUMBER(REVISION)) AS MAXREVISION ";
        // 20241119 caina upd e
        $str_sql .= 'FROM ';
        $str_sql .= '  HANTEILST_SINSYA ';
        $str_sql .= 'WHERE ';
        $str_sql .= "  SYADAI='" . $data['SYADAI'] . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  CARNO='" . $data['CARNO'] . "' ";

        return $str_sql;
    }

    //--- 20160127 li INS E

    //20190227 YIN INS S
    //  カラム情報の最大件数を取得 中古１ヶ月点検判定追加
    public function m_sel_before_ins_sdh01_01_chuko_sql($data)
    {
        // $strType = '';
        // if ('3' == $con4) {
        //     $strType = '5';
        // }
        $str_sql = '';
        $str_sql .= 'SELECT ';
        //20240929 caina upd s
        // $str_sql .= "  COUNT(REVISION) MAXREVISION ";
        //20241119 caina upd s
        // $str_sql .= "  MAX(REVISION) MAXREVISION ";
        $str_sql .= "  MAX(TO_NUMBER(REVISION)) AS MAXREVISION ";
        //20241119 caina upd e
        //20240929 caina upd e
        $str_sql .= 'FROM ';
        $str_sql .= '  HANTEILST_CHUKO ';
        $str_sql .= 'WHERE ';
        $str_sql .= "  SYADAI='" . $data['SYADAI'] . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  CARNO='" . $data['CARNO'] . "' ";

        return $str_sql;
    }

    //20190227 YIN INS E

    /**
     * カラム情報更新.
     *
     * @param {arrayr} $values
     *                         車検日:SYAKENBI
     *                         判定年月:YYMM
     *                         車台番号:SYADAI
     *                         カーNo:CARNO
     *                         管理部署コード:KANRIBU
     *                         管理サービス部署コード:KANRISV
     *                         管理担当スタッフ:KANRISLS
     *                         判定コード１:HANTEI1_CD
     *                         判定文１:HANTEI1
     *                         判定コード２:HANTEI2_CD
     *                         判定文２:HANTEI2
     *                         判定コード３:HANTEI3_CD
     *                         判定文３:HANTEI3
     *                         判定コード４:HANTEI4_CD
     *                         判定文４:HANTEI4
     *                         判定コード５:HANTEI5_CD
     *                         判定文５:HANTEI5
     *                         判定コード６:HANTEI6_CD
     *                         判定文６:HANTEI6
     *                         判定コード７:HANTEI7_CD
     *                         判定文７:HANTEI7
     *                         最終結果コード:KEKKA_CD
     *                         最終結果:KEKKA
     *                         車検数:SYAKEN_SU
     *                         リビジョン:REVISION
     *                         更新担当者:UPDSYACD
     *
     * @return {String} select文
     */
    // 20240929 caina upd s
    // public function m_update_sdh01_01_sql($values)
    public function m_update_sdh01_01_sql($data)
    // 20240929 caina upd e
    {
        $str_sql = '';
        $str_sql .= 'UPDATE ';
        $str_sql .= '  HANTEILST ';
        $str_sql .= 'SET ';
        $str_sql .= "  SYAKENBI='" . $data['SYAKENBI'] . "', ";
        $str_sql .= "  YYMM='" . $data['YYMM'] . "', ";
        $str_sql .= "  SYADAI='" . $data['SYADAI'] . "', ";
        $str_sql .= "  CARNO='" . $data['CARNO'] . "', ";
        //$str_sql .= "  KANRIBU='" . $data['KANRIBU'] . "', ";
        //$str_sql .= "  KANRISV='" . $data['KANRISV'] . "', ";
        //$str_sql .= "  KANRISLS='" . $data['KANRISLS'] . "', ";
        $str_sql .= "  HANTEI1_CD='" . $data['HANTEI1_CD'] . "', ";
        $str_sql .= "  HANTEI1='" . $data['HANTEI1'] . "', ";
        $str_sql .= "  HANTEI2_CD='" . $data['HANTEI2_CD'] . "', ";
        $str_sql .= "  HANTEI2='" . $data['HANTEI2'] . "', ";
        $str_sql .= "  HANTEI3_CD='" . $data['HANTEI3_CD'] . "', ";
        $str_sql .= "  HANTEI3='" . $data['HANTEI3'] . "', ";
        $str_sql .= "  HANTEI4_CD='" . $data['HANTEI4_CD'] . "', ";
        $str_sql .= "  HANTEI4='" . $data['HANTEI4'] . "', ";
        $str_sql .= "  HANTEI5_CD='" . $data['HANTEI5_CD'] . "', ";
        $str_sql .= "  HANTEI5='" . $data['HANTEI5'] . "', ";
        $str_sql .= "  HANTEI6_CD='" . $data['HANTEI6_CD'] . "', ";
        $str_sql .= "  HANTEI6='" . $data['HANTEI6'] . "', ";
        $str_sql .= "  HANTEI7_CD='" . $data['HANTEI7_CD'] . "', ";
        $str_sql .= "  HANTEI7='" . $data['HANTEI7'] . "', ";
        $str_sql .= "  KEKKA_CD='" . $data['KEKKA_CD'] . "', ";
        $str_sql .= "  KEKKA='" . $data['KEKKA'] . "', ";
        $str_sql .= "  SYAKEN_SU='" . $data['SYAKEN_SU'] . "', ";
        $str_sql .= "  UPDSYACD='" . $data['UPDSYACD'] . "', ";
        $str_sql .= "  UPDYMDHM= to_char(sysdate,'yyyymmddhh24mi') ";
        $str_sql .= 'WHERE ';
        $str_sql .= "  REVISION='999' ";

        return $str_sql;
    }

    //--- 20160127 li INS S
    //カラム情報更新 新車１ヶ月点検、新車６ヶ月点検判定追加
    // 20240929 caina upd s
    // public function m_update_sdh01_01_sinsya_sql($values, $con4)
    public function m_update_sdh01_01_sinsya_sql($data)
    // 20240929 caina upd e
    {
        // $strType = '';
        // if ('1' == $con4) {
        //     $strType = '3';
        // }
        // if ('2' == $con4) {
        //     $strType = '4';
        // }
        $str_sql = '';
        $str_sql .= 'UPDATE ';
        $str_sql .= '  HANTEILST_SINSYA ';
        $str_sql .= 'SET ';
        $str_sql .= "  SYADAI='" . $data['SYADAI'] . "', ";
        $str_sql .= "  CARNO='" . $data['CARNO'] . "', ";
        //$str_sql .= "  KANRIBU='" . $data['KANRIBU'] . "', ";
        //$str_sql .= "  KANRISV='" . $data['KANRISV'] . "', ";
        //$str_sql .= "  KANRISLS='" . $data['KANRISLS'] . "', ";
        $str_sql .= "  KEKKA1_CD='" . $data['HANTEI7_CD'] . "', ";
        $str_sql .= "  KEKKA1='" . $data['HANTEI7'] . "', ";
        $str_sql .= "  KEKKA6_CD='" . $data['KEKKA_CD'] . "', ";
        $str_sql .= "  KEKKA6='" . $data['KEKKA'] . "', ";
        $str_sql .= "  UPDSYACD='" . $data['UPDSYACD'] . "', ";
        $str_sql .= "  UPDYMDHM= to_char(sysdate,'yyyymmddhh24mi') ";
        $str_sql .= 'WHERE ';
        $str_sql .= "  REVISION='999' ";

        return $str_sql;
    }

    //--- 20160127 li INS E

    //20190227 YIN INS S
    //カラム情報更新 中古１ヶ月点検判定追加
    // 20240929 caina upd s
    // public function m_update_sdh01_01_chuko_sql($values, $con4)
    public function m_update_sdh01_01_chuko_sql($data)
    // 20240929 caina upd e
    {
        // $strType = '';
        // if ('3' == $con4) {
        //     $strType = '5';
        // }
        $str_sql = '';
        $str_sql .= 'UPDATE ';
        $str_sql .= '  HANTEILST_CHUKO ';
        $str_sql .= 'SET ';
        $str_sql .= "  SYADAI='" . $data['SYADAI'] . "', ";
        $str_sql .= "  CARNO='" . $data['CARNO'] . "', ";
        $str_sql .= "  KEKKA1_CD='" . $data['KEKKA_CD'] . "', ";
        $str_sql .= "  KEKKA1='" . $data['KEKKA'] . "', ";
        $str_sql .= "  UPDSYACD='" . $data['UPDSYACD'] . "', ";
        $str_sql .= "  UPDYMDHM= to_char(sysdate,'yyyymmddhh24mi') ";
        $str_sql .= 'WHERE ';
        $str_sql .= "  REVISION='999' ";

        return $str_sql;
    }

    //20190227 YIN INS E

    /**
     * フリーメモを取得.
     *
     * @param {arrayr} $data07
     *                         車台番号:SYADAI
     *                         カーNo:CARNO
     *
     * @return {String} select文
     */
    public function m_select_sdh01_07_sql($data07)
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        //フリーメモ
        $str_sql .= '  MEMO ';
        $str_sql .= 'FROM ';
        $str_sql .= '  HANTEIMEMO ';
        $str_sql .= 'WHERE ';
        $str_sql .= "  SYADAI = '" . $data07['SYADAI'] . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  CARNO = '" . $data07['CARNO'] . "' ";

        return $str_sql;
    }

    /**
     * フリーメモを更新.
     *
     * @param {arrayr} $data07
     *                         車台番号:SYADAI
     *                         カーNo:CARNO
     *                         フリーメモ:MEMO
     *
     * @return {String} select文
     */
    public function m_update_sdh01_07_sql($data07)
    {
        $str_sql = '';
        $str_sql .= 'UPDATE ';
        $str_sql .= '  HANTEIMEMO ';
        $str_sql .= 'SET ';
        $str_sql .= "  MEMO  =  '" . $data07['MEMO'] . "', ";
        $str_sql .= "  UPDYMD  =  to_char(sysdate,'yyyymmdd') ";
        $str_sql .= 'WHERE ';
        $str_sql .= "  SYADAI = '" . $data07['SYADAI'] . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  CARNO = '" . $data07['CARNO'] . "' ";

        return $str_sql;
    }

    /**
     * フリーメモを挿入.
     *
     * @param {arrayr} $data07
     *                         車台番号:SYADAI
     *                         カーNo:CARNO
     *                         フリーメモ:MEMO
     *
     * @return {String} select文
     */
    public function m_insert_sdh01_07_sql($data07)
    {
        $str_sql = '';
        $str_sql .= 'INSERT INTO ';
        $str_sql .= '  HANTEIMEMO ';
        $str_sql .= '( ';
        $str_sql .= '  SYADAI, ';
        $str_sql .= '  CARNO, ';
        $str_sql .= '  MEMO, ';
        $str_sql .= '  CREYMD ';
        $str_sql .= ') ';
        $str_sql .= 'VALUES ';
        $str_sql .= '( ';
        $str_sql .= "  '" . $data07['SYADAI'] . "', ";
        $str_sql .= "  '" . $data07['CARNO'] . "', ";
        $str_sql .= "  '" . $data07['MEMO'] . "', ";
        $str_sql .= "  to_char(sysdate,'yyyymmdd') ";
        $str_sql .= ') ';

        return $str_sql;
    }

    //----20220121 sun add s
    //店長取得
    public function m_select_get_tencyou_sql($user)
    {
        $str_sql = '';
        $str_sql .= "SELECT COUNT(SYAIN_NO) cnt FROM HANTEIN6AUTHMST WHERE 1=1 AND SYAIN_NO='" . $user . "'";

        return $str_sql;
    }

    public function m_select_get_sinchoku_sql($SYADAI, $CARNO, $TENPO)
    {
        $str_sql = '';
        $str_sql .= ' SELECT COUNT(CHECKED_YM) CNT ';
        $str_sql .= ' FROM HANTEILST_N6_MONTHLY ';
        $str_sql .= " WHERE 1=1 AND CHECKED_YM = TO_CHAR(SYSDATE,'yyyyMM') ";
        $str_sql .= " AND SYADAI     = '" . $SYADAI . "' ";
        $str_sql .= " AND CARNO      = '" . $CARNO . "' ";
        $str_sql .= " AND TENPO      = '" . $TENPO . "' ";

        return $str_sql;
    }

    public function m_insert_add_n6_data_sql($SYADAI, $CARNO, $TENPO, $TANTO, $CHECKED)
    {
        $str_sql = '';
        $str_sql .= ' INSERT INTO HANTEILST_N6_MONTHLY VALUES ';
        $str_sql .= " (      TO_CHAR(SYSDATE,'yyyyMM') ";
        $str_sql .= "  ,      '" . $SYADAI . "' ";
        $str_sql .= "  ,      '" . $CARNO . "' ";
        $str_sql .= "  ,      '" . $TENPO . "' ";
        $str_sql .= "  ,      '" . $TANTO . "' ";
        // 20220209 YIN UPD S
        // $str_sql .= "  ,      '" . $CHECKED . "' )";
        $str_sql .= "  ,      '" . $CHECKED . "' ";
        $str_sql .= "  ,      '" . $TANTO . "' ";
        $str_sql .= "  ,      TO_CHAR(SYSDATE,'yyyyMMddhh24MI') ) ";
        // 20220209 YIN UPD E
        return $str_sql;
    }

    public function m_update_upd_n6_data_sql($SYADAI, $CARNO, $TENPO, $TANTO, $CHECKED)
    {
        $str_sql = '';
        $str_sql .= ' UPDATE HANTEILST_N6_MONTHLY ';
        $str_sql .= " SET TANTO      = '" . $TANTO . "', ";
        $str_sql .= " CHECKED      = '" . $CHECKED . "' ";
        // 20220209 YIN INS S
        $str_sql .= " , UPDSYACD      = '" . $TANTO . "' ";
        $str_sql .= " , UPDYMDHM      = TO_CHAR(SYSDATE,'yyyyMMddhh24MI') ";
        // 20220209 YIN INS E
        $str_sql .= ' WHERE 1=1 ';
        $str_sql .= " AND CHECKED_YM = TO_CHAR(SYSDATE,'yyyyMM') ";
        $str_sql .= " AND SYADAI     = '" . $SYADAI . "' ";
        $str_sql .= " AND CARNO      = '" . $CARNO . "' ";
        $str_sql .= " AND TENPO      = '" . $TENPO . "' ";

        return $str_sql;
    }

    //----20220121 sun add e
}