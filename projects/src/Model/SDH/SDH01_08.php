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
 * 日付        Feature/Bug      内容                                         担当
 * YYYYMMDD    #ID              XXXXXX                                       FCSDL
 * 20141015    #399             判定リストに重複項目があり                   zhenghuiyun
 * 20141016    #416             SQL文                                        zhangxiaolei
 * 20160127    #2373            依頼                                         li
 * 20170123    -----            新車６ヶ月点検にて、軽4ナンバーを含めない    HM
 * 20190226    #2870            依頼                                         ci
 * 20190318    #2870            依頼                                         ci
 * 20190320    #2870            依頼                                         ci
 * 20220121    機能追加　　　　　　 N6対応                                       Sun
 * 20220217    機能追加　　　　　　 20220212ーN6対応指摘事項(No6,7)                lujunxia
 * 20220222    No15             20220212ーN6対応指摘事項                       lujunxia
 * 20240624    指摘事項      20240619_SDH_N7N13の入力内容が一覧に出ない          YIN
 * --------------------------------------------------------------------------------------------
 */
// 共通クラスの読込み
namespace App\Model\SDH;

use App\Model\Component\ClsComDb;

class SDH01_08 extends ClsComDb
{
    /**
     * 店舗全員を取得.
     *
     * @param {String}
     * 管理チームコード:$tenpo_code
     * 車検満了日:$syaken_manryobi
     *
     * @return {parent} result
     */
    //--- 20160127 li UPD S
    // public function m_select_sdh01_08_tenpozenin($tenpo_code, $syaken_manryobi, $con, $con1, $con2, $con3) {
    // $str_sql = $this -> m_select_sdh01_08_sql_tenpozenin($tenpo_code, $syaken_manryobi, $con, $con1, $con2, $con3);
    public function m_select_sdh01_08_tenpozenin($tenpo_code, $syaken_manryobi, $con, $con1, $con2, $con3, $con4)
    {
        $str_sql = $this->m_select_sdh01_08_sql_tenpozenin($tenpo_code, $syaken_manryobi, $con, $con1, $con2, $con3, $con4);
        //--- 20160127 li UPD E
        //$this -> log($str_sql);
        return parent::select($str_sql);
    }

    /**
     * 営業全員を取得.
     *
     * @param {String}
     * 管理チームコード:$tenpo_code
     * 車検満了日:$syaken_manryobi
     *
     * @return {parent} result
     */
    //--- 20160127 li UPD S
    // public function m_select_sdh01_08_eigyozenin($tenpo_code, $syaken_manryobi, $con, $con1, $con2, $con3) {
    // $str_sql = $this -> m_select_sdh01_08_sql_eigyozenin($tenpo_code, $syaken_manryobi, $con, $con1, $con2, $con3);
    public function m_select_sdh01_08_eigyozenin($tenpo_code, $syaken_manryobi, $con, $con1, $con2, $con3, $con4)
    {
        $str_sql = $this->m_select_sdh01_08_sql_eigyozenin($tenpo_code, $syaken_manryobi, $con, $con1, $con2, $con3, $con4);
        //$this -> log($str_sql);
        //--- 20160127 li UPD E
        return parent::select($str_sql);
    }

    /**
     * サービス全員を取得.
     *
     * @param {String}
     * 管理チームコード:$tenpo_code
     * 車検満了日:$syaken_manryobi
     *
     * @return {parent} result
     */
    //--- 20160127 li UPD S
    // public function m_select_sdh01_08_saabisuzenin($tenpo_code, $syaken_manryobi, $con, $con1, $con2, $con3) {
    // $str_sql = $this -> m_select_sdh01_08_sql_saabisuzenin($tenpo_code, $syaken_manryobi, $con, $con1, $con2, $con3);
    public function m_select_sdh01_08_saabisuzenin($tenpo_code, $syaken_manryobi, $con, $con1, $con2, $con3, $con4)
    {
        $str_sql = $this->m_select_sdh01_08_sql_saabisuzenin($tenpo_code, $syaken_manryobi, $con, $con1, $con2, $con3, $con4);
        //--- 20160127 li UPD E
        return parent::select($str_sql);
    }

    /**
     * 営業担当者を取得.
     *
     * @param {String}
     * 管理チームコード:$tenpo_code
     * 車検満了日:$syaken_manryobi
     *
     * @return {parent} result
     */
    //--- 20160127 li UPD S
    // public function m_select_sdh01_08_tantousya($tantousya_code, $syaken_manryobi, $con, $con1, $con2, $con3) {
    // $str_sql = $this -> m_select_sdh01_08_sql_tantousya($tantousya_code, $syaken_manryobi, $con, $con1, $con2, $con3);
    public function m_select_sdh01_08_tantousya($tantousya_code, $syaken_manryobi, $con, $con1, $con2, $con3, $con4)
    {
        $str_sql = $this->m_select_sdh01_08_sql_tantousya($tantousya_code, $syaken_manryobi, $con, $con1, $con2, $con3, $con4);
        //--- 20160127 li UPD E
        return parent::select($str_sql);
    }

    /**
     * メモを取得.
     *
     * @param {String}
     * 車検満了日:$syaken_manryobi
     *
     * @return {String} select文
     */
    //--- 20160127 li UPD S
    // public function m_select_sdh01_08_sql($syaken_manryobi) {
    public function m_select_sdh01_08_sql($syaken_manryobi, $con4)
    {
        // $strType = '';
        // if ('1' == $con4) {
        //     $strType = '3';
        // }
        // if ('2' == $con4) {
        //     $strType = '4';
        // }
        // //--- 20190227 ci INS S
        // if ('3' == $con4) {
        //     $strType = '5';
        // }
        //--- 20190227 ci INS E
        $str_date1 = " TO_CHAR( add_months( to_date( SUBSTR( '" . $syaken_manryobi . "',1,4)  ||'/'  ||SUBSTR('" . $syaken_manryobi . "',5,2)  ,'yyyy/MM' ) ,-2 ) ,'yyyyMM') ";
        $str_date11 = " TO_CHAR( add_months( to_date( SUBSTR( '" . $syaken_manryobi . "',1,4)  ||'/'  ||SUBSTR('" . $syaken_manryobi . "',5,2)  ,'yyyy/MM' ) ,-6 ) ,'yyyyMM') ";
        $str_date6 = " TO_CHAR( add_months( to_date( SUBSTR( '" . $syaken_manryobi . "',1,4)  ||'/'  ||SUBSTR('" . $syaken_manryobi . "',5,2)  ,'yyyy/MM' ) ,-7 ) ,'yyyyMM') ";
        $str_date61 = " TO_CHAR( add_months( to_date( SUBSTR( '" . $syaken_manryobi . "',1,4)  ||'/'  ||SUBSTR('" . $syaken_manryobi . "',5,2)  ,'yyyy/MM' ) ,-13 ) ,'yyyyMM') ";

        //--- 20160127 li UPD E
        $str_sql = '';
        //		$str_sql .= "SELECT /*+ USE_HASH( M41C04 M41C03 M41C01 HANTEILST HANTEITEIKEIMST ) */ ";

        if ('1' == $con4 or '2' == $con4) {
            //$str_sql .= "SELECT /*+ USE_HASH( M41C04 M41C03 M41C01 HANTEILST_SINSYA HANTEITEIKEIMST ) */ ";
            $str_sql .= 'SELECT /*+ USE_HASH( M41C03 BTH28SD2 HANTEILST_SINSYA ) */ distinct  ';
        }
        //--- 20190227 ci INS S
        elseif ('3' == $con4) {
            //--- 20190320 ci INS S
            //$str_sql .= " SELECT /*+ USE_HASH( M41C03 BTH28SD2 HANTEILST_CHUKO ) */ distinct ";
            $str_sql .= ' SELECT /*+ USE_HASH( BTH28SD2 HANTEILST_CHUKO ) */ distinct ';
            //--- 20190320 ci INS E
        }
        //--- 20190227 ci INS E
        else {
            //$str_sql .= "SELECT /*+ USE_HASH( M41C04 M41C03 M41C01 HANTEILST HANTEITEIKEIMST ) */ ";
            $str_sql .= 'SELECT /*+ NO_USE_HASH( M41C03 HANTEILST ) */ ';
        }

        $str_sql .= '  {select_list} ';

        $str_sql .= '  M41C04.XH10CAID, ';
        $str_sql .= '  CASE ';
        $str_sql .= '   WHEN M41E10.CMN_NO IS NOT NULL THEN M41E10.CMN_NO ELSE ';
        $str_sql .= "    '' END CMN_NO, ";
        //20150611 Update Start
        //		$str_sql .= "  M41E10.TOU_DT, ";
        //20150611 Update End
        $str_sql .= ' CASE WHEN M41E10.TOU_DT IS NOT NULL THEN M41E10.TOU_DT ELSE M41C03.VCLRGTDT END TOU_DT, ';

        $str_sql .= '  M41C04.DLRCSRNO, ';
        $str_sql .= '  M41C01.CSRNM1, ';
        //--- 20160127 li UPD S
        // $str_sql .= "  CASE WHEN substr(M41C03.VCLIPEDT,1,6) = '" . $syaken_manryobi . "' THEN M41C03.VCLIPEDT ELSE HANTEILST.SYAKENBI END VCLIPEDT, ";
        if ('1' == $con4) {
            $str_sql .= "  CASE WHEN substr(M41C03.VCLIPEDT,1,6) = '" . $syaken_manryobi . "' THEN M41C03.VCLIPEDT ELSE '' END VCLIPEDT, ";
            //--- 20190226 ci UPD S
            //}else if ($con4 == "2")
        } elseif ('2' == $con4 or '3' == $con4) {
            //--- 20190226 ci UPD E
            $str_sql .= "  CASE WHEN substr(M41C03.VCLIPEDT,1,6) = '" . $syaken_manryobi . "' THEN M41C03.VCLIPEDT ELSE '' END VCLIPEDT, ";
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $str_sql .= "  CASE WHEN substr(M41C03.VCLIPEDT,1,6) = '" . $syaken_manryobi . "' THEN M41C03.VCLIPEDT ELSE HANTEILST.SYAKENBI END VCLIPEDT, ";
        }
        //--- 20160127 li UPD E
        //20150713 Update Start

        //予想距離
        $str_sql .= '( M41C04.VCLMTCDS * ';
        //20160309 Upd S
        //$str_sql .= "   TRUNC( ";
        //$str_sql .= "          case when length(CASE WHEN rtrim(M41C04.NKSIN1_AWTDT) IS NOT NULL THEN substr(M41C04.NKSIN1_AWTDT,1,4)||'/'||substr(M41C04.NKSIN1_AWTDT,5,2)||'/01' ELSE CASE WHEN rtrim(M41C04.KSA_DT) IS NOT NULL THEN substr(M41C04.KSA_DT,1,4)||'/'||substr(M41C04.KSA_DT,5,2)||'/01' ELSE CASE WHEN M41C04.NOUSDAT IS NOT NULL THEN substr(M41C04.NOUSDAT,1,4)||'/'||substr(M41C04.NOUSDAT,5,2)||'/01' ELSE ' ' END END END)=10 ";
        //$str_sql .= "               then   months_between( ";
        //$str_sql .= "                       to_date(substr(M41C03.VCLIPEDT,1,4)||'/'||substr(M41C03.VCLIPEDT,5,2)||'/'||substr(M41C03.VCLIPEDT,7,2) ,'YYYY/MM/DD') ,  ";
        //$str_sql .= "                       to_date(CASE WHEN rtrim(M41C04.NKSIN1_AWTDT) IS NOT NULL THEN substr(M41C04.NKSIN1_AWTDT,1,4)||'/'||substr(M41C04.NKSIN1_AWTDT,5,2)||'/01' ELSE CASE WHEN rtrim(M41C04.KSA_DT) IS NOT NULL THEN substr(M41C04.KSA_DT,1,4)||'/'||substr(M41C04.KSA_DT,5,2)||'/01' ELSE CASE WHEN M41C04.NOUSDAT IS NOT NULL THEN substr(M41C04.NOUSDAT,1,4)||'/'||substr(M41C04.NOUSDAT,5,2)||'/01' ELSE ' ' END END END,'YYYY/MM/DD') ";
        //$str_sql .= "                       )  ";
        //$str_sql .= "               else 0 end  ";
        //$str_sql .= "         ) ";

        $str_sql .= '   TRUNC( ';
        $str_sql .= "          case when length(CASE WHEN rtrim(M41C04.NKSIN1_AWTDT) IS NOT NULL THEN substr(M41C04.NKSIN1_AWTDT,1,4)||'/'||substr(M41C04.NKSIN1_AWTDT,5,2)||'/01' ELSE CASE WHEN rtrim(M41C04.KSA_DT) IS NOT NULL THEN substr(M41C04.KSA_DT,1,4)||'/'||substr(M41C04.KSA_DT,5,2)||'/01' ELSE CASE WHEN M41C04.NOUSDAT IS NOT NULL THEN substr(M41C04.NOUSDAT,1,4)||'/'||substr(M41C04.NOUSDAT,5,2)||'/01' ELSE ' ' END END END)=10 ";
        $str_sql .= '               then ';
        $str_sql .= '                     case when  rtrim(M41C03.VCLIPEDT) is not null ';
        $str_sql .= '                              then   months_between( ';
        $str_sql .= "                                        to_date(substr(M41C03.VCLIPEDT,1,4)||'/'||substr(M41C03.VCLIPEDT,5,2)||'/'||substr(M41C03.VCLIPEDT,7,2) ,'YYYY/MM/DD') ,  ";
        $str_sql .= "                                        to_date(CASE WHEN rtrim(M41C04.NKSIN1_AWTDT) IS NOT NULL THEN substr(M41C04.NKSIN1_AWTDT,1,4)||'/'||substr(M41C04.NKSIN1_AWTDT,5,2)||'/01' ELSE CASE WHEN rtrim(M41C04.KSA_DT) IS NOT NULL THEN substr(M41C04.KSA_DT,1,4)||'/'||substr(M41C04.KSA_DT,5,2)||'/01' ELSE CASE WHEN M41C04.NOUSDAT IS NOT NULL THEN substr(M41C04.NOUSDAT,1,4)||'/'||substr(M41C04.NOUSDAT,5,2)||'/01' ELSE ' ' END END END,'YYYY/MM/DD') ";
        $str_sql .= '                                        )  ';
        $str_sql .= '                     else 0 end  ';
        $str_sql .= '               else 0 end  ';
        $str_sql .= '         ) ';
        //20160309 Upd E

        $str_sql .= '   ) + M41C04.NKSIN1_SOKOKM as YOSOKILO, ';

        //201603109 Add S
        if ('1' == $con4) {
            $str_sql .= '  CASE WHEN M41C03.FRGMH = ' . $str_date1 . " THEN '当月' ELSE '遅延' END as ONSCHEDULEKBN,";
        } elseif ('2' == $con4) {
            $str_sql .= '  CASE WHEN M41C03.FRGMH = ' . $str_date6 . " THEN '当月' ELSE '遅延' END as ONSCHEDULEKBN, ";
        }
        //20190318 ci upd S
        elseif ('3' == $con4) {
            //20190320 Upd S
            $str_sql .= '  CASE WHEN substr(M41C04.KSA_DT,1,6) = ' . $str_date1 . " THEN '当月' ELSE '遅延' END as ONSCHEDULEKBN, ";
            //20190320 Upd E
        }
        //20190318 ci upd E
        else {
            $str_sql .= "  ''  as ONSCHEDULEKBN, ";
        }
        //201603109 Add E

        $str_sql .= '  M41C03.FRGMH, ';
        $str_sql .= '  M41C03.VCLIPEDT DISP_VCLIPEDT, ';
        $str_sql .= '  M41C04.NKSIN1_SOKOKM, ';
        //20150713 Update End

        $str_sql .= '  M41C03.VCLNM, ';
        $str_sql .= '  M41C03.VIN_WMIVDS, ';
        $str_sql .= '  M41C03.VIN_VIS, ';
        $str_sql .= '  M41C04.KNR_STRCD, ';
        $str_sql .= '  M41C04.SRV_SRVSTRCD, ';
        $str_sql .= '  M41C04.KNR_BUSMANCD, ';
        $str_sql .= '  M41C04.MAS_DT, ';

        $str_sql .= '  SUBSTR(TMK.ITEMNAME1,0,7) KEKKA, ';
        //--- 20160127 li UPD S
        // $str_sql .= "  HANTEILST.KEKKA_CD, ";
        // $str_sql .= "  HANTEILST.HANTEI1_CD, ";
        // $str_sql .= "  HANTEILST.HANTEI2_CD, ";
        // $str_sql .= "  HANTEILST.HANTEI3_CD, ";
        // $str_sql .= "  HANTEILST.HANTEI4_CD, ";
        // $str_sql .= "  HANTEILST.HANTEI5_CD, ";
        // $str_sql .= "  HANTEILST.HANTEI6_CD, ";
        // $str_sql .= "  SUBSTR(TM6.ITEMNAME1,0,7) NAME6, ";
        // $str_sql .= "  SUBSTR(TM7.ITEMNAME1,0,7) NAME7, ";
        // $str_sql .= "  HANTEILST.HANTEI7_CD ";

        if ('1' == $con4 or '2' == $con4) {
            $str_sql .= '  HANTEILST_SINSYA.KEKKA_CD, ';
            $str_sql .= '  HANTEILST_SINSYA.HANTEI1_CD, ';
            $str_sql .= '  HANTEILST_SINSYA.HANTEI2_CD, ';
            $str_sql .= '  HANTEILST_SINSYA.HANTEI3_CD, ';
            $str_sql .= '  HANTEILST_SINSYA.HANTEI4_CD, ';
            $str_sql .= '  HANTEILST_SINSYA.HANTEI5_CD, ';
            $str_sql .= '  HANTEILST_SINSYA.HANTEI6_CD, ';
            $str_sql .= '  SUBSTR(TM7.ITEMNAME1,0,7) SNAME7, ';
            $str_sql .= '  HANTEILST_SINSYA.HANTEI7_CD, ';
            $str_sql .= '  SUBSTR(TMK.ITEMNAME1,0,7) SNAME, ';
            $str_sql .= '  HANTEILST_SINSYA.HANTEI7 AS SNAMEKEKKA1, ';
            $str_sql .= '  HANTEILST_SINSYA.KEKKA AS SNAMEKEKKA6 ';
        } elseif //20220217 lujunxia upd s
        //----20220121 sun upd s
        ('0' == $con4) {
            //if ($con4 == "0" or $con4 == "4")
            //----20220121 sun upd e
            $str_sql .= '  HANTEILST.KEKKA_CD, ';
            $str_sql .= '  HANTEILST.HANTEI1_CD, ';
            $str_sql .= '  HANTEILST.HANTEI2_CD, ';
            $str_sql .= '  HANTEILST.HANTEI3_CD, ';
            $str_sql .= '  HANTEILST.HANTEI4_CD, ';
            $str_sql .= '  HANTEILST.HANTEI5_CD, ';
            $str_sql .= '  HANTEILST.HANTEI6_CD, ';
            $str_sql .= '  SUBSTR(TM6.ITEMNAME1,0,7) NAME6, ';
            $str_sql .= '  SUBSTR(TM7.ITEMNAME1,0,7) NAME7, ';
            $str_sql .= '  HANTEILST.HANTEI7_CD ';
        }
        //--- 20190226 ci INS S
        elseif ('4' == $con4) {
            //最終結果が「代替済」の場合:代替後の車種
            //20220222 lujunxia upd s
            //$str_sql .= " (SELECT BASEH_KN FROM M27AM1 WHERE M27AM1.BASEH_CD=M41E10.MOD_CD) AS BASEH_KN, ";
            $str_sql .= " (SELECT BASEH_KN FROM M27AM1,M41E10 WHERE INSTR(HANTEILST.KEKKA,'代替　注文書=')>0 AND M27AM1.BASEH_CD=M41E10.MOD_CD AND M41E10.CMN_NO=TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(HANTEILST.KEKKA,chr(10)),chr(9)),chr(13)),chr(10)||chr(13)),'代替　注文書='))) AS BASEH_KN, ";
            //20220222 lujunxia upd e
            $str_sql .= '  HANTEILST.KEKKA_CD, ';
            $str_sql .= '  HANTEILST.HANTEI1_CD, ';
            $str_sql .= '  HANTEILST.HANTEI2_CD, ';
            $str_sql .= '  HANTEILST.HANTEI3_CD, ';
            $str_sql .= '  HANTEILST.HANTEI4_CD, ';
            $str_sql .= '  HANTEILST.HANTEI5_CD, ';
            $str_sql .= '  HANTEILST.HANTEI6_CD, ';
            $str_sql .= "  TM6.ITEMNAME1 || ( CASE WHEN TM6.ITEMNAME2 IS NULL THEN ''   ELSE ':' || TM6.ITEMNAME2 END) AS NAME6, ";
            $str_sql .= "  TM7.ITEMNAME1 || ( CASE WHEN TM7.ITEMNAME2 IS NULL THEN ''   ELSE ':' || TM7.ITEMNAME2 END) AS NAME7, ";
            $str_sql .= '  HANTEILST.HANTEI7_CD ';
        }
        //20220217 lujunxia upd e
        elseif ('3' == $con4) {
            $str_sql .= '  HANTEILST_CHUKO.KEKKA_CD, ';
            $str_sql .= '  HANTEILST_CHUKO.HANTEI1_CD, ';
            $str_sql .= '  HANTEILST_CHUKO.HANTEI2_CD, ';
            $str_sql .= '  HANTEILST_CHUKO.HANTEI3_CD, ';
            $str_sql .= '  HANTEILST_CHUKO.HANTEI4_CD, ';
            $str_sql .= '  HANTEILST_CHUKO.HANTEI5_CD, ';
            $str_sql .= '  HANTEILST_CHUKO.HANTEI6_CD, ';
            $str_sql .= '  SUBSTR(TMK.ITEMNAME1,0,7) SNAME7, ';
            $str_sql .= '  HANTEILST_CHUKO.HANTEI7_CD, ';
            $str_sql .= '  HANTEILST_CHUKO.KEKKA AS SNAMEKEKKA1 ';
        }
        //--- 20190226 ci INS E
        //--- 20160127 li UPD E
        $str_sql .= ' , HANTEILST_SYASYU.SYASYU ';

        //----20220121 sun add s
        if ('4' == $con4) {
            $str_sql .= ' , TMN.ITEMNAME1 HANTEINAME ';
            $str_sql .= ' , CASE WHEN HANTEILST.CHECKED_YM IS NOT NULL ';
            $str_sql .= "  AND TO_CHAR(SYSDATE,'yyyyMM')=HANTEILST.CHECKED_YM ";
            $str_sql .= '  THEN 1 ';
            //進捗確認日＝システム日付当月
            $str_sql .= '  WHEN HANTEILST.KEKKA_CD IS NOT NULL ';
            $str_sql .= '  THEN 2 ';
            //最終結果入力済み
            $str_sql .= '  ELSE 0 END CHECKED_YM ';
        }
        //----20220121 sun add e

        $str_sql .= '  FROM ';
        $str_sql .= ' {table_list}';
        $str_sql .= ' {table_order}';
        $str_sql .= '  M41C01, ';
        $str_sql .= '  M41C03, ';
        $str_sql .= '  M41C04, ';
        $str_sql .= '  M41E10, ';

        //--- 20160301 li UPD S
        // $str_sql .= "  HANTEITEIKEIMST TM6, ";

        //----20220121 sun upd s
        //if ($con4 == "0")
        if ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $str_sql .= '  HANTEITEIKEIMST TM6, ';
        }

        //--- 20160301 li UPD E
        $str_sql .= '  HANTEITEIKEIMST TM7, ';
        $str_sql .= '  HANTEITEIKEIMST TMK, ';

        //----20220121 sun add s
        if ('4' == $con4) {
            $str_sql .= '  HANTEITEIKEIMST TMN, ';
            //20220519 YIN INS S
            $str_sql .= '  HANTEITEIKEIMST TMSEQ,';
            //20220519 YIN INS E
        }
        //----20220121 sun add e

        $str_sql .= '  (';
        $str_sql .= '  SELECT ';
        //--- 20160127 li UPD S
        // $str_sql .= "    HL.* ";

        if ('1' == $con4 or '2' == $con4) {
            $str_sql .= '    HL.SYADAI ';
            $str_sql .= '   ,HL.CARNO ';
            //20160308 Del St
            //$str_sql .= "   ,HL.KANRIBU ";
            //$str_sql .= "   ,HL.KANRISV ";
            //$str_sql .= "   ,HL.KANRISLS ";
            //20160308 Del Ed
            $str_sql .= '   ,HL.KEKKA1_CD AS HANTEI7_CD ';
            $str_sql .= '   ,HL.KEKKA1 AS HANTEI7 ';
            $str_sql .= '   ,HL.KEKKA6_CD AS KEKKA_CD ';
            $str_sql .= '   ,HL.KEKKA6 AS KEKKA ';
            $str_sql .= '   ,HL.REVISION ';
            $str_sql .= '   ,HL.UPDSYACD ';
            $str_sql .= '   ,HL.UPDYMDHM ';
            //$str_sql .= "   ,HL.SYASYU ";
            $str_sql .= "   ,'' AS HANTEI1_CD";
            $str_sql .= "   ,'' AS HANTEI1 ";
            $str_sql .= "   ,'' AS HANTEI2_CD";
            $str_sql .= "   ,'' AS HANTEI2 ";
            $str_sql .= "   ,'' AS HANTEI3_CD";
            $str_sql .= "   ,'' AS HANTEI3 ";
            $str_sql .= "   ,'' AS HANTEI4_CD";
            $str_sql .= "   ,'' AS HANTEI4 ";
            $str_sql .= "   ,'' AS HANTEI5_CD";
            $str_sql .= "   ,'' AS HANTEI5 ";
            $str_sql .= "   ,'' AS HANTEI6_CD";
            $str_sql .= "   ,'' AS HANTEI6 ";
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $str_sql .= '    HL.* ';
        }
        //----20220121 sun add s
        if ('4' == $con4) {
            $str_sql .= '    ,N6.CHECKED_YM ';
        }
        //----20220121 sun add e
        //--- 20190227 ci INS S
        elseif ('3' == $con4) {
            $str_sql .= '    HL.SYADAI ';
            $str_sql .= '   ,HL.CARNO ';
            $str_sql .= '   ,HL.KEKKA1_CD AS KEKKA_CD ';
            $str_sql .= '   ,HL.KEKKA1 AS KEKKA ';
            $str_sql .= '   ,HL.REVISION ';
            $str_sql .= '   ,HL.UPDSYACD ';
            $str_sql .= '   ,HL.UPDYMDHM ';
            $str_sql .= "   ,'' AS HANTEI1_CD";
            $str_sql .= "   ,'' AS HANTEI1 ";
            $str_sql .= "   ,'' AS HANTEI2_CD";
            $str_sql .= "   ,'' AS HANTEI2 ";
            $str_sql .= "   ,'' AS HANTEI3_CD";
            $str_sql .= "   ,'' AS HANTEI3 ";
            $str_sql .= "   ,'' AS HANTEI4_CD";
            $str_sql .= "   ,'' AS HANTEI4 ";
            $str_sql .= "   ,'' AS HANTEI5_CD";
            $str_sql .= "   ,'' AS HANTEI5 ";
            $str_sql .= "   ,'' AS HANTEI6_CD";
            $str_sql .= "   ,'' AS HANTEI6 ";
            $str_sql .= "   ,'' AS HANTEI7_CD";
            $str_sql .= "   ,'' AS HANTEI7 ";
        }
        //--- 20190227 ci INS E
        //--- 20160127 li UPD E
        $str_sql .= '  FROM ';
        //--- 20160127 li UPD S
        // $str_sql .= "    HANTEILST HL,";
        // $str_sql .= "   (";
        // $str_sql .= "    SELECT ";
        // $str_sql .= "     SYAKENBI,YYMM,SYADAI,CARNO,MAX(TO_NUMBER(REVISION)) MAXREVISION ";
        // $str_sql .= "    FROM ";
        // $str_sql .= "     HANTEILST ";
        // $str_sql .= "    GROUP BY ";
        // $str_sql .= "    SYAKENBI,YYMM,SYADAI,CARNO ";
        // $str_sql .= "   ) HANTEILST_MAX ";
        // $str_sql .= "  WHERE ";
        // $str_sql .= "    HANTEILST_MAX.SYAKENBI = HL.SYAKENBI AND ";
        // $str_sql .= "    HANTEILST_MAX.YYMM = HL.YYMM AND ";
        // $str_sql .= "    HANTEILST_MAX.SYADAI = HL.SYADAI AND ";
        // $str_sql .= "    HANTEILST_MAX.CARNO = HL.CARNO AND ";
        // $str_sql .= "    HANTEILST_MAX.MAXREVISION =TO_NUMBER(HL.REVISION)  ";
        // $str_sql .= "  ) ";
        // $str_sql .= "  HANTEILST ";

        if ('1' == $con4 or '2' == $con4) {
            $str_sql .= '    HANTEILST_SINSYA HL,';
            $str_sql .= '   (';
            $str_sql .= '    SELECT ';
            $str_sql .= '     SYADAI,CARNO,MAX(TO_NUMBER(REVISION)) MAXREVISION ';
            $str_sql .= '    FROM ';
            $str_sql .= '     HANTEILST_SINSYA ';
            $str_sql .= '    GROUP BY ';
            $str_sql .= '    SYADAI,CARNO ';
            $str_sql .= '   ) HANTEILST_SINSYA_MAX ';
            $str_sql .= '  WHERE ';
            $str_sql .= '    HANTEILST_SINSYA_MAX.SYADAI = HL.SYADAI AND ';
            $str_sql .= '    HANTEILST_SINSYA_MAX.CARNO = HL.CARNO AND ';
            $str_sql .= '    HANTEILST_SINSYA_MAX.MAXREVISION =TO_NUMBER(HL.REVISION)  ';
            $str_sql .= '  ) ';
            $str_sql .= '  HANTEILST_SINSYA ';
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $str_sql .= '    HANTEILST HL,';
            $str_sql .= '   (';
            $str_sql .= '    SELECT ';
            $str_sql .= '     SYAKENBI,YYMM,SYADAI,CARNO,MAX(TO_NUMBER(REVISION)) MAXREVISION ';
            $str_sql .= '    FROM ';
            $str_sql .= '     HANTEILST ';
            $str_sql .= '    GROUP BY ';
            $str_sql .= '    SYAKENBI,YYMM,SYADAI,CARNO ';
            $str_sql .= '   ) HANTEILST_MAX ';
            //----20220121 sun add s
            if ('4' == $con4) {
                $str_sql .= '    ,(SELECT CHECKED_YM,SYADAI,CARNO,CHECKED ';
                $str_sql .= '    FROM HANTEILST_N6_MONTHLY ';
                $str_sql .= '    WHERE TO_NUMBER(CHECKED) = 1 ';
                $str_sql .= "    AND CHECKED_YM = TO_CHAR(SYSDATE,'yyyyMM') ) N6 ";
                //$str_sql .= "    WHERE TO_NUMBER(CHECKED) = 1  ";
                //$str_sql .= "    AND TENPO = ".  . " ) N6 ";
            }
            //----20220121 sun add e
            $str_sql .= '  WHERE ';
            $str_sql .= '    HANTEILST_MAX.SYAKENBI = HL.SYAKENBI AND ';
            $str_sql .= '    HANTEILST_MAX.YYMM = HL.YYMM AND ';
            $str_sql .= '    HANTEILST_MAX.SYADAI = HL.SYADAI AND ';
            $str_sql .= '    HANTEILST_MAX.CARNO = HL.CARNO AND ';
            $str_sql .= '    HANTEILST_MAX.MAXREVISION =TO_NUMBER(HL.REVISION)  ';
            //----20220121 sun add s
            if ('4' == $con4) {
                $str_sql .= '    AND HANTEILST_MAX.SYADAI      = N6.SYADAI(+) ';
                $str_sql .= '    AND HANTEILST_MAX.CARNO       = N6.CARNO(+) ';
            }
            //----20220121 sun add e

            $str_sql .= '  ) ';
            $str_sql .= '  HANTEILST ';
        }
        //--- 20190227 ci INS S
        elseif ('3' == $con4) {
            $str_sql .= '    HANTEILST_CHUKO HL,';
            $str_sql .= '   (';
            $str_sql .= '    SELECT ';
            $str_sql .= '     SYADAI,CARNO,MAX(TO_NUMBER(REVISION)) MAXREVISION ';
            $str_sql .= '    FROM ';
            $str_sql .= '     HANTEILST_CHUKO ';
            $str_sql .= '    GROUP BY ';
            $str_sql .= '    SYADAI,CARNO ';
            $str_sql .= '   ) HANTEILST_CHUKO_MAX ';
            $str_sql .= '  WHERE ';
            $str_sql .= '    HANTEILST_CHUKO_MAX.SYADAI = HL.SYADAI AND ';
            $str_sql .= '    HANTEILST_CHUKO_MAX.CARNO = HL.CARNO AND ';
            $str_sql .= '    HANTEILST_CHUKO_MAX.MAXREVISION =TO_NUMBER(HL.REVISION)  ';
            $str_sql .= '  ) ';
            $str_sql .= '  HANTEILST_CHUKO ';
        }
        //--- 20190227 ci INS E
        //--- 20160127 li UPD E
        //--- 20160301 li INS S
        if ('1' == $con4 or '2' == $con4) {
            $str_sql .= '  , BTH28SD2 BTH28SD2_1 ';
            $str_sql .= '  , BTH28SD2 BTH28SD2_6 ';
        }
        //--- 20160301 li INS E
        //--- 20190226 ci INS S
        if ('3' == $con4) {
            $str_sql .= '  , BTH28SD2 BTH28SD2_1 ';
        }
        //--- 20190226 ci INS E
        $str_sql .= '  , HANTEILST_SYASYU ';

        $str_sql .= 'WHERE ';
        $str_sql .= '  M41C03.VIN_WMIVDS = M41C04.VIN_WMIVDS ';
        $str_sql .= 'AND ';
        $str_sql .= '  M41C03.VIN_VIS = M41C04.VIN_VIS ';
        $str_sql .= 'AND ';
        $str_sql .= '  M41C04.DLRCSRNO = M41C01.DLRCSRNO ';

        $str_sql .= 'AND ';
        $str_sql .= '  M41C03.VIN_WMIVDS = HANTEILST_SYASYU.SYADAI(+) ';
        $str_sql .= 'AND ';
        $str_sql .= '  M41C03.VIN_VIS = HANTEILST_SYASYU.CARNO(+) ';

        //----20220121 sun add s
        if ('4' == $con4) {
            $str_sql .= ' AND ';
            $str_sql .= ' CASE WHEN rtrim(HANTEILST.HANTEI7_CD) IS NOT NULL THEN  HANTEILST.HANTEI7_CD ';
            $str_sql .= ' WHEN rtrim(HANTEILST.HANTEI6_CD) IS NOT NULL THEN  HANTEILST.HANTEI6_CD ';
            $str_sql .= ' WHEN rtrim(HANTEILST.HANTEI5_CD) IS NOT NULL THEN  HANTEILST.HANTEI5_CD ';
            $str_sql .= ' WHEN rtrim(HANTEILST.HANTEI4_CD) IS NOT NULL THEN  HANTEILST.HANTEI4_CD ';
            $str_sql .= ' WHEN rtrim(HANTEILST.HANTEI3_CD) IS NOT NULL THEN  HANTEILST.HANTEI3_CD ';
            $str_sql .= ' WHEN rtrim(HANTEILST.HANTEI2_CD) IS NOT NULL THEN  HANTEILST.HANTEI2_CD ';
            $str_sql .= ' WHEN rtrim(HANTEILST.HANTEI1_CD) IS NOT NULL THEN  HANTEILST.HANTEI1_CD ';
            $str_sql .= ' END = TMN.TEIKEI_CD(+) ';
            $str_sql .= " AND '1' = TMN.TEIKEI_TYPE(+) ";
            //20220519 YIN INS S
            $str_sql .= ' AND ';
            $str_sql .= " CASE WHEN rtrim(HANTEILST.HANTEI7_CD) IS NOT NULL  THEN SUBSTR(HANTEILST.HANTEI7_CD,0,2)||'00' ";
            $str_sql .= " WHEN rtrim(HANTEILST.HANTEI6_CD) IS NOT NULL THEN  SUBSTR(HANTEILST.HANTEI6_CD,0,2)||'00' ";
            $str_sql .= " WHEN rtrim(HANTEILST.HANTEI5_CD) IS NOT NULL THEN  SUBSTR(HANTEILST.HANTEI5_CD,0,2)||'00' ";
            $str_sql .= " WHEN rtrim(HANTEILST.HANTEI4_CD) IS NOT NULL THEN  SUBSTR(HANTEILST.HANTEI4_CD,0,2)||'00' ";
            $str_sql .= " WHEN rtrim(HANTEILST.HANTEI3_CD) IS NOT NULL THEN  SUBSTR(HANTEILST.HANTEI3_CD,0,2)||'00' ";
            $str_sql .= " WHEN rtrim(HANTEILST.HANTEI2_CD) IS NOT NULL THEN  SUBSTR(HANTEILST.HANTEI2_CD,0,2)||'00' ";
            $str_sql .= " WHEN rtrim(HANTEILST.HANTEI1_CD) IS NOT NULL THEN  SUBSTR(HANTEILST.HANTEI1_CD,0,2)||'00' ";
            $str_sql .= ' END = TMSEQ.TEIKEI_CD(+) ';
            $str_sql .= " AND '1' = TMSEQ.TEIKEI_TYPE(+) ";
            //20220519 YIN INS E
        }
        //----20220121 sun add e

        $str_sql .= 'AND ';
        $str_sql .= " CASE WHEN LENGTH(M41C04.ORDERNO)=7 THEN SUBSTR(M41C04.ORDERNO,1,1)||'-'||SUBSTR(M41C04.ORDERNO,2,6) ELSE M41C04.ORDERNO END = M41E10.CMN_NO(+) ";

        //--- 20160301 li INS S
        //20160309 Upd S
        //$str_date1 = " TO_CHAR( add_months( to_date( SUBSTR( '" . $syaken_manryobi . "',1,4)  ||'/'  ||SUBSTR('" . $syaken_manryobi . "',5,2)  ,'yyyy/MM' ) ,-2 ) ,'yyyyMM') ";
        //$str_date11 = " TO_CHAR( add_months( to_date( SUBSTR( '" . $syaken_manryobi . "',1,4)  ||'/'  ||SUBSTR('" . $syaken_manryobi . "',5,2)  ,'yyyy/MM' ) ,-5 ) ,'yyyyMM') ";
        //$str_date6 = " TO_CHAR( add_months( to_date( SUBSTR( '" . $syaken_manryobi . "',1,4)  ||'/'  ||SUBSTR('" . $syaken_manryobi . "',5,2)  ,'yyyy/MM' ) ,-7 ) ,'yyyyMM') ";
        //$str_date61 = " TO_CHAR( add_months( to_date( SUBSTR( '" . $syaken_manryobi . "',1,4)  ||'/'  ||SUBSTR('" . $syaken_manryobi . "',5,2)  ,'yyyy/MM' ) ,-12 ) ,'yyyyMM') ";
        //20160309 Upd E

        if ('1' == $con4) {
            $str_sql .= '  AND HANTEILST_SINSYA.HANTEI7_CD = TM7.TEIKEI_CD(+)   ';
            $str_sql .= "  AND '3'                         = TM7.TEIKEI_TYPE(+)   ";
            $str_sql .= '  AND HANTEILST_SINSYA.KEKKA_CD   = TMK.TEIKEI_CD(+)   ';
            $str_sql .= "  AND '4'                         = TMK.TEIKEI_TYPE(+)   ";
            $str_sql .= '  AND M41C03.VIN_WMIVDS = BTH28SD2_1.VIN_SDI_KAT(+)   ';
            $str_sql .= '  AND M41C03.VIN_VIS = BTH28SD2_1.VIN_RBN(+)  ';
            $str_sql .= "  AND '61' = BTH28SD2_1.DIH_NKO_KB(+)  ";
            $str_sql .= '  AND M41C03.VIN_WMIVDS = BTH28SD2_6.VIN_SDI_KAT(+)   ';
            $str_sql .= '  AND M41C03.VIN_VIS = BTH28SD2_6.VIN_RBN(+)  ';
            $str_sql .= "  AND '63' = BTH28SD2_6.DIH_NKO_KB(+)  ";
            $str_sql .= '  AND ((M41C03.FRGMH >= ' . $str_date11 . '  ';
            $str_sql .= '  AND  M41C03.FRGMH < ' . $str_date1 . '  ';
            $str_sql .= '  AND   BTH28SD2_1.NKO_DT IS NULL )  OR (M41C03.FRGMH = ' . $str_date1 . ')) ';
            $str_sql .= "  AND M41C04.JYOTAIKBN <> '9'   ";
            $str_sql .= "  AND M41C04.SRV_SRVSTRCD <> '999'   ";
            $str_sql .= "  AND  (( substr(M41C04.VCLRGTNO_SYU,1,1) in ('3','5','7') and M41C04.VCLRGTNO_KANA not in (";
            $str_sql .= "'ｱ','ｲ','ｳ','ｴ','ｵ','ｶ','ｷ','ｸ','ｹ','ｺ','ｼ','ﾍ','ﾖ','ﾚ','ﾜ','ｦ','ﾝ','E','H','K','M','T','Y') ) or (M41C04.VCLRGTNO_KANA not in ('ｼ','ﾍ','ﾘ','ﾚ','ﾜ','ﾝ','A','B') and  M41C03.SYUBETU = '5' ))  ";

            $str_sql .= "  AND M41C04.XH10CAID in ('0','1','2','3')   ";
        } elseif ('2' == $con4) {
            $str_sql .= '  AND HANTEILST_SINSYA.HANTEI7_CD = TM7.TEIKEI_CD(+)   ';
            $str_sql .= "  AND '3'                         = TM7.TEIKEI_TYPE(+)   ";
            $str_sql .= '  AND HANTEILST_SINSYA.KEKKA_CD   = TMK.TEIKEI_CD(+)   ';
            $str_sql .= "  AND '4'                         = TMK.TEIKEI_TYPE(+)   ";
            $str_sql .= '  AND M41C03.VIN_WMIVDS = BTH28SD2_1.VIN_SDI_KAT(+)   ';
            $str_sql .= '  AND M41C03.VIN_VIS = BTH28SD2_1.VIN_RBN(+)  ';
            $str_sql .= "  AND '61' = BTH28SD2_1.DIH_NKO_KB(+)  ";
            $str_sql .= '  AND M41C03.VIN_WMIVDS = BTH28SD2_6.VIN_SDI_KAT(+)   ';
            $str_sql .= '  AND M41C03.VIN_VIS = BTH28SD2_6.VIN_RBN(+)  ';
            $str_sql .= "  AND '63' = BTH28SD2_6.DIH_NKO_KB(+)  ";
            $str_sql .= '  AND ((M41C03.FRGMH >= ' . $str_date61 . '  ';
            $str_sql .= '  AND  M41C03.FRGMH < ' . $str_date6 . '  ';
            $str_sql .= '  AND   BTH28SD2_6.NKO_DT IS NULL )  OR (M41C03.FRGMH = ' . $str_date6 . ')) ';
            $str_sql .= "  AND M41C04.JYOTAIKBN <> '9'   ";
            $str_sql .= "  AND M41C04.SRV_SRVSTRCD <> '999'   ";
            //20170123 Update Start
            //			$str_sql .= "  AND  (( substr(M41C04.VCLRGTNO_SYU,1,1) in ('3','5','7') and M41C04.VCLRGTNO_KANA not in (";
            //			$str_sql .= "'ｱ','ｲ','ｳ','ｴ','ｵ','ｶ','ｷ','ｸ','ｹ','ｺ','ｼ','ﾍ','ﾖ','ﾚ','ﾜ','ｦ','ﾝ','E','H','K','M','T','Y') ) or (M41C04.VCLRGTNO_KANA not in ('ｼ','ﾍ','ﾘ','ﾚ','ﾜ','ﾝ','A','B') and  M41C03.SYUBETU = '5' ))  ";
            $str_sql .= '  AND  (';
            $str_sql .= "             ( substr(M41C04.VCLRGTNO_SYU,1,1) in ('3','5','7') and M41C04.VCLRGTNO_KANA not in ('ｱ','ｲ','ｳ','ｴ','ｵ','ｶ','ｷ','ｸ','ｹ','ｺ','ｼ','ﾍ','ﾖ','ﾚ','ﾜ','ｦ','ﾝ','E','H','K','M','T','Y') ) ";
            $str_sql .= "       OR ( substr(M41C04.VCLRGTNO_SYU,1,1) in ( '5','7') and M41C04.VCLRGTNO_KANA not in ('ｼ','ﾍ','ﾘ','ﾚ','ﾜ','ﾝ','A','B') and  M41C03.SYUBETU = '5' ) ";
            $str_sql .= '           )  ';
            //20170123 Update End

            $str_sql .= "  AND M41C04.XH10CAID in ('0','1','2','3')   ";
        }
        //--- 20190226 ci INS S
        elseif ('3' == $con4) {
            $str_sql .= '  AND HANTEILST_CHUKO.KEKKA_CD = TMK.TEIKEI_CD(+)   ';
            $str_sql .= "  AND '5'                         = TMK.TEIKEI_TYPE(+)   ";
            $str_sql .= '  AND M41C03.VIN_WMIVDS = BTH28SD2_1.VIN_SDI_KAT(+)   ';
            $str_sql .= '  AND M41C03.VIN_VIS = BTH28SD2_1.VIN_RBN(+)  ';
            $str_sql .= "  AND '64' = BTH28SD2_1.DIH_NKO_KB(+)  ";
            //--- 20190318 ci UPD S
            //--- 20190320 ci UPD S
            //$str_sql .= "  AND ((M41E10.TOU_DT >= " . $str_date11 . "  ";
            //$str_sql .= "  AND  M41E10.TOU_DT < " . $str_date1 . "  ";
            //$str_sql .= "  AND   BTH28SD2_1.NKO_DT IS NULL )  OR (M41E10.TOU_DT = " . $str_date1 . ")) ";
            $str_sql .= '  AND ((substr(M41C04.KSA_DT, 1, 6) >= ' . $str_date11 . '  ';
            $str_sql .= '  AND substr(M41C04.KSA_DT, 1, 6) < ' . $str_date1 . '  ';
            $str_sql .= '  AND   BTH28SD2_1.NKO_DT IS NULL )  OR (substr(M41C04.KSA_DT, 1, 6) = ' . $str_date1 . ')) ';
            //--- 20190320 ci UPD E
            //--- 20190318 ci UPD E
            $str_sql .= "  AND M41C04.XH10CAID = '5'   ";
            $str_sql .= "  AND M41C04.JYOTAIKBN <> '9'   ";
            $str_sql .= "  AND M41C04.SRV_SRVSTRCD <> '999'   ";
            $str_sql .= "  AND  (( substr(M41C04.VCLRGTNO_SYU,1,1) in ('3','5','7') and M41C04.VCLRGTNO_KANA not in (";
            $str_sql .= "'ｱ','ｲ','ｳ','ｴ','ｵ','ｶ','ｷ','ｸ','ｹ','ｺ','ｼ','ﾍ','ﾖ','ﾚ','ﾜ','ｦ','ﾝ','E','H','K','M','T','Y') ) or (M41C04.VCLRGTNO_KANA not in ('ｼ','ﾍ','ﾘ','ﾚ','ﾜ','ﾝ','A','B') and  M41C03.SYUBETU = '5' ))  ";
        }
        //--- 20190226 ci INS E
        //--- 20160301 li INS E
        //--- 20160127 li UPD S
        // $str_sql .= "AND ";
        // //        $str_sql .= "  HANTEILST.HANTEI6_CD = TM6.TEIKEI_CD(+) ";
        // $str_sql .= " CASE WHEN TO_CHAR(SYSDATE,'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST.HANTEI6_CD ";
        // $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,1),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST.HANTEI5_CD ";
        // $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,2),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST.HANTEI4_CD ";
        // $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,3),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST.HANTEI3_CD ";
        // $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,4),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST.HANTEI2_CD ";
        // $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,5),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST.HANTEI1_CD ";
        // $str_sql .= "      ELSE '' END  = TM6.TEIKEI_CD(+) ";

        if ('1' == $con4 or '2' == $con4) {
            // $str_sql .= "AND ";
            // $str_sql .= " CASE WHEN TO_CHAR(SYSDATE,'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST_SINSYA.HANTEI6_CD ";
            // $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,1),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST_SINSYA.HANTEI5_CD ";
            // $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,2),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST_SINSYA.HANTEI4_CD ";
            // $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,3),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST_SINSYA.HANTEI3_CD ";
            // $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,4),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST_SINSYA.HANTEI2_CD ";
            // $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,5),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST_SINSYA.HANTEI1_CD ";
            // $str_sql .= "      ELSE '' END  = TM6.TEIKEI_CD(+) ";
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $str_sql .= 'AND ';
            $str_sql .= " CASE WHEN TO_CHAR(SYSDATE,'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST.HANTEI6_CD ";
            $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,1),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST.HANTEI5_CD ";
            $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,2),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST.HANTEI4_CD ";
            // 20240624 YIN UPD S
            // $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,3),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST.HANTEI3_CD ";
            // $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,4),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST.HANTEI2_CD ";
            // $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,5),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST.HANTEI1_CD ";
            $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,5),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST.HANTEI3_CD ";
            $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,6),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST.HANTEI2_CD ";
            $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,12),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST.HANTEI1_CD ";
            // 20240624 YIN UPD E
            $str_sql .= "      ELSE '' END  = TM6.TEIKEI_CD(+) ";
        }
        //--- 20160127 li UPD E
        //--- 20160127 li UPD S
        // $str_sql .= "AND ";
        // $str_sql .= "  '1' = TM6.TEIKEI_TYPE(+) ";
        if ('1' == $con4 or '2' == $con4) {
            // $str_sql .= "AND ";
            // $str_sql .= "  '" . $strType . "' = TM6.TEIKEI_TYPE(+) ";
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $str_sql .= 'AND ';
            $str_sql .= "  '1' = TM6.TEIKEI_TYPE(+) ";
        }
        //--- 20160127 li UPD E
        //--- 20160127 li UPD S
        // $str_sql .= "AND ";
        // //        $str_sql .= "  HANTEILST.HANTEI7_CD = TM7.TEIKEI_CD(+) ";
        // $str_sql .= " CASE WHEN TO_CHAR(SYSDATE,'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST.HANTEI7_CD ";
        // $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,1),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST.HANTEI6_CD ";
        // $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,2),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST.HANTEI5_CD ";
        // $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,3),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST.HANTEI4_CD ";
        // $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,4),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST.HANTEI3_CD ";
        // $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,5),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST.HANTEI2_CD ";
        // $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,6),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST.HANTEI1_CD ";
        // $str_sql .= "      ELSE '' END  = TM7.TEIKEI_CD(+) ";

        if ('1' == $con4 or '2' == $con4) {
            // $str_sql .= "AND ";
            // $str_sql .= " CASE WHEN TO_CHAR(SYSDATE,'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST_SINSYA.HANTEI7_CD ";
            // $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,1),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST_SINSYA.HANTEI6_CD ";
            // $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,2),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST_SINSYA.HANTEI5_CD ";
            // $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,3),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST_SINSYA.HANTEI4_CD ";
            // $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,4),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST_SINSYA.HANTEI3_CD ";
            // $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,5),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST_SINSYA.HANTEI2_CD ";
            // $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,6),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST_SINSYA.HANTEI1_CD ";
            // $str_sql .= "      ELSE '' END  = TM7.TEIKEI_CD(+) ";
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $str_sql .= 'AND ';
            $str_sql .= " CASE WHEN TO_CHAR(SYSDATE,'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST.HANTEI7_CD ";
            $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,1),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST.HANTEI6_CD ";
            $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,2),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST.HANTEI5_CD ";
            $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,3),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST.HANTEI4_CD ";
            // 20240624 YIN UPD S
            // $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,4),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST.HANTEI3_CD ";
            // $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,5),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST.HANTEI2_CD ";
            // $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,6),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST.HANTEI1_CD ";
            $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,6),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST.HANTEI3_CD ";
            $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,7),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST.HANTEI2_CD ";
            $str_sql .= "      WHEN TO_CHAR(ADD_MONTHS(SYSDATE,13),'yyyyMM') = '" . $syaken_manryobi . "' THEN HANTEILST.HANTEI1_CD ";
            // 20240624 YIN UPD E
            $str_sql .= "      ELSE '' END  = TM7.TEIKEI_CD(+) ";
        }
        //--- 20160127 li UPD E

        //--- 20160127 li UPD S
        // $str_sql .= "AND ";
        // $str_sql .= "  '1' = TM7.TEIKEI_TYPE(+) ";
        if ('1' == $con4 or '2' == $con4) {
            // $str_sql .= "AND ";
            // $str_sql .= "  '" . $strType . "' = TM7.TEIKEI_TYPE(+) ";
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $str_sql .= 'AND ';
            $str_sql .= "  '1' = TM7.TEIKEI_TYPE(+) ";
        }
        //--- 20160127 li UPD E

        //--- 20160127 li UPD S
        // $str_sql .= "AND ";
        // $str_sql .= "  HANTEILST.KEKKA_CD = TMK.TEIKEI_CD(+) ";
        if ('1' == $con4 or '2' == $con4) {
            // $str_sql .= "AND ";
            // $str_sql .= "  HANTEILST_SINSYA.KEKKA_CD = TMK.TEIKEI_CD(+) ";
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $str_sql .= 'AND ';
            $str_sql .= '  HANTEILST.KEKKA_CD = TMK.TEIKEI_CD(+) ';
        }
        //--- 20160127 li UPD E

        //--- 20160127 li UPD S
        // $str_sql .= "AND ";
        // $str_sql .= "  '2' = TMK.TEIKEI_TYPE(+) ";
        if ('1' == $con4 or '2' == $con4) {
            // $str_sql .= "AND ";
            // $str_sql .= "  '" . $strType . "' = TMK.TEIKEI_TYPE(+) ";
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $str_sql .= 'AND ';
            $str_sql .= "  '2' = TMK.TEIKEI_TYPE(+) ";
        }
        //--- 20160127 li UPD E

        $str_sql .= 'AND ';
        //--- 20160301 li UPD S
        // $str_sql .= "  M41C04.VIN_WMIVDS = HANTEILST.SYADAI(+) ";
        if ('1' == $con4 or '2' == $con4) {
            $str_sql .= '  M41C03.VIN_WMIVDS = HANTEILST_SINSYA.SYADAI(+) ';
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $str_sql .= '  M41C04.VIN_WMIVDS = HANTEILST.SYADAI(+) ';
        }
        //--- 20190227 ci INS S
        elseif ('3' == $con4) {
            $str_sql .= '  M41C03.VIN_WMIVDS = HANTEILST_CHUKO.SYADAI(+) ';
        }
        //--- 20190227 ci INS E
        //--- 20160301 li UPD S
        //--- 20160301 li UPD E

        if ('1' == $con4 or '2' == $con4) {
            $str_sql .= 'AND ';
            $str_sql .= '  M41C03.VIN_VIS = HANTEILST_SINSYA.CARNO(+) ';
        }
        //--- 20190227 ci INS S
        elseif ('3' == $con4) {
            $str_sql .= 'AND ';
            $str_sql .= '  M41C03.VIN_VIS = HANTEILST_CHUKO.CARNO(+) ';
        }
        //--- 20190227 ci INS E
        else {
            $str_sql .= 'AND ';
            //--- 20160301 li UPD S
            // $str_sql .= "  M41C04.VIN_VIS = HANTEILST.CARNO(+) ";
            // $str_sql .= "AND ";
            // $str_sql .= "  HANTEILST.YYMM(+) = '" . $syaken_manryobi . "' ";
            if ('1' == $con4 or '2' == $con4) {
                $str_sql .= '  M41C03.VIN_VIS = HANTEILST_SINSYA.CARNO(+) ';
            } elseif //----20220121 sun upd s
            //if ($con4 == "0")
            ('0' == $con4 or '4' == $con4) {
                //----20220121 sun upd e
                $str_sql .= '  M41C04.VIN_VIS = HANTEILST.CARNO(+) ';
                $str_sql .= 'AND ';
                $str_sql .= "  HANTEILST.YYMM(+) = '" . $syaken_manryobi . "' ";
            }
            //--- 20160301 li UPD E
        }

        $str_sql .= '  {tantousya_list} ';

        if ('1' == $con4 or '2' == $con4 or '3' == $con4) {
        } else {
            $str_sql .= 'AND ';
            $str_sql .= ' (';
            $str_sql .= "  ( substr(M41C03.VCLIPEDT,1,6) = '" . $syaken_manryobi . "' ) ";
            //--- 20160127 li UPD S
            // $str_sql .= "  OR ";
            // $str_sql .= "  ( substr(HANTEILST.SYAKENBI,1,6) = '" . $syaken_manryobi . "') ";
            if ('1' == $con4 or '2' == $con4) {
            } elseif ('0' == $con4 or '4' == $con4) {
                $str_sql .= '  OR ';
                $str_sql .= "  ( substr(HANTEILST.SYAKENBI,1,6) = '" . $syaken_manryobi . "') ";
            }
            //--- 20160127 li UPD E
            $str_sql .= ' ) ';
        }

        $str_sql .= 'AND ';
        $str_sql .= ' ( ';
        $str_sql .= '           rtrim(M41C04.MAS_DT) is null ';
        $str_sql .= '        OR ';
        $str_sql .= '          ( ';

        //20150609 Update Start
        //        $str_sql .=  "              rtrim(M41C04.MAS_DT) is not null ";
        //        $str_sql .= "           and M41C04.RUPD_RIYU_CD in ('02','86')";
        $str_sql .= "                rtrim(M41C04.MAS_DT) >= to_char( add_months( to_date( substr( '" . $syaken_manryobi . "',1,4)||'/'||substr('" . $syaken_manryobi . "',5,2)||'/01','yyyy/MM/dd' ) ,-7 ) ,'yyyyMMdd') ";
        $str_sql .= "             and M41C04.RUPD_RIYU_CD in ('02','86')";
        //20150609 Update End

        $str_sql .= '           and not exists ( SELECT NEWER.* FROM M41C04 NEWER WHERE NEWER.VIN_WMIVDS = M41C04.VIN_WMIVDS and NEWER.VIN_VIS = M41C04.VIN_VIS AND rtrim(NEWER.MAS_DT) IS NULL)';
        $str_sql .= '          )';
        $str_sql .= ' ) ';

        $str_sql .= 'AND ';
        $str_sql .= ' rtrim(M41C01.MAS_DT) is null ';

        $str_sql .= '  {tantousya_list1} ';
        $str_sql .= '  {conbine_list} ';
        $str_sql .= '  {condition_list} ';
        $str_sql .= '  {orderby} ';

        return $str_sql;
    }

    /**
     * 店舗全員 を指定している場合.
     *
     * @param {String}
     * 管理チームコード:$tenpo_code
     * 車検満了日:$syaken_manryobi
     *
     * @return {String} select文
     */
    //--- 20160127 li UPD S
    // public function m_select_sdh01_08_sql_tenpozenin($tenpo_code, $syaken_manryobi, $con, $con1, $con2, $con3) {
    public function m_select_sdh01_08_sql_tenpozenin($tenpo_code, $syaken_manryobi, $con, $con1, $con2, $con3, $con4)
    {
        $strType = '';
        if ('1' == $con4) {
            $strType = '3';
        }
        if ('2' == $con4) {
            $strType = '4';
        }
        //--- 20190227 ci INS S
        if ('3' == $con4) {
            $strType = '5';
        }
        //--- 20190227 ci INS E
        //--- 20160127 li UPD E
        $str_sql = '';
        //--- 20160127 li UPD S
        // $str_sql = $this -> m_select_sdh01_08_sql($syaken_manryobi);
        $str_sql = $this->m_select_sdh01_08_sql($syaken_manryobi, $con4);
        //--- 20160127 li UPD E
        $str_where = '';
        $str_where .= 'AND ';
        $str_where .= '( ';
        $str_where .= " (substr(M41C04.KNR_STRCD,1,2) = '" . $tenpo_code . "'  AND SUBSTR(M41C04.KNR_STRCD,3,1)<>'7' ) ";
        $str_where .= ' OR ';
        $str_where .= " (M41C04.KNR_STRCD <> '" . $tenpo_code . "1' AND substr(M41C04.SRV_SRVSTRCD,1,2) = '" . $tenpo_code . "')";
        $str_where .= ')';

        $str_sql = str_replace('{tantousya_list}', $str_where, $str_sql);

        if ('1' == $con) {
            $str_where = '';
            $str_table = '';

            $str_where .= 'AND ';
            $str_where .= 'M41C04.B1_KATANCD IS NOT NULL ';
            $str_where .= 'AND ';
            $str_where .= 'M41C04.B2_KATANCD IS NOT NULL ';
            $str_where .= 'AND ';
            $str_where .= 'M41C04.B3_KATANCD IS NOT NULL ';
            $str_sql = str_replace('{table_list}', $str_table, $str_sql);
            $str_sql = str_replace('{tantousya_list1}', $str_where, $str_sql);
        } elseif ('2' == $con) {
            $str_where = '';
            $str_table = '';
            $str_where .= 'AND ';
            $str_where .= 'M41C04.KNR_BUSMANCD = HHAIZOKU.SYAIN_NO ';
            $str_where .= 'AND ';
            $str_where .= 'substr(M41C04.KNR_STRCD,1,2) <> substr(HHAIZOKU.BUSYO_CD,1,2) ';
            $str_where .= 'AND ';
            $str_where .= 'HHAIZOKU.END_DATE IS NULL ';
            $str_table = ' HHAIZOKU,';
            $str_sql = str_replace('{table_list}', $str_table, $str_sql);
            $str_sql = str_replace('{tantousya_list1}', $str_where, $str_sql);
        } elseif ('3' == $con) {
            $str_where = '';
            $str_table = '';
            $str_where .= 'AND ';
            $str_where .= 'M41C04.KNR_BUSMANCD = BTH29MA4.SYAIN_NO ';
            $str_where .= 'AND ';
            $str_where .= "'3634'              = BTH29MA4.HANSH_CD ";
            $str_where .= 'AND ';
            $str_where .= 'BTH29MA4.RISYOKU_DATE IS NOT NULL ';
            $str_table = ' BTH29MA4,';
            $str_sql = str_replace('{table_list}', $str_table, $str_sql);
            $str_sql = str_replace('{tantousya_list1}', $str_where, $str_sql);
        } else {
            $str_where = '';
            $str_table = '';
            $str_sql = str_replace('{table_list}', $str_table, $str_sql);
            $str_sql = str_replace('{tantousya_list1}', $str_where, $str_sql);
        }

        $str_where = '';
        $orderby = '';
        $orderby .= '  ORDER BY ';
        //--- 20190228 ci UPD S
        if ('1' == $con4 or '2' == $con4) {
            //--- 20190228 ci UPD E
            $orderby .= '  XH10CAID,FRGMH DESC';
            $orderby .= '  ,VIN_WMIVDS ';
            $orderby .= '  ,VIN_VIS  ';
        }
        //--- 20190320 ci INS S
        elseif ('3' == $con4) {
            $orderby .= '  XH10CAID,TOU_DT DESC';
            $orderby .= '  ,VIN_WMIVDS ';
            $orderby .= '  ,VIN_VIS  ';
        }
        //--- 20190320 ci INS E

        //----20220121 sun add s
        elseif ('4' == $con4) {
            //20220519 YIN UPD S
            // $orderby .= "  TMN.DISP_SEQ";
            $orderby .= '  TMSEQ.DISP_SEQ';
            //20220519 YIN UPD E
            $orderby .= '  ,XH10CAID,VCLIPEDT';
            $orderby .= '  ,VIN_WMIVDS ';
            $orderby .= '  ,VIN_VIS  ';
        }
        //----20220121 sun add e
        else {
            $orderby .= '  XH10CAID,VCLIPEDT';
            $orderby .= '  ,VIN_WMIVDS ';
            $orderby .= '  ,VIN_VIS  ';
        }

        $orderbyKATU = '';
        $orderbyKATU .= '  ORDER BY ';
        $orderbyKATU .= '  DISP_SEQ_TM1 ';
        $orderbyKATU .= '  ,VIN_WMIVDS ';
        $orderbyKATU .= '  ,VIN_VIS  ';

        $orderbySAISYU = '';

        //--- 20160301 li UPD S
        // $orderbySAISYU .= "  ORDER BY ";
        // $orderbySAISYU .= "  DISP_SEQ_TM2 ";
        // $orderbySAISYU .= "  ,VIN_WMIVDS ";
        // $orderbySAISYU .= "  ,VIN_VIS  ";
        if ('1' == $con4) {
            $orderbySAISYU .= '  ORDER BY ';
            $orderbySAISYU .= '  DISP_SEQ_TM7 ';
            $orderbySAISYU .= '  ,FRGMH DESC ';
            $orderbySAISYU .= '  ,VIN_WMIVDS ';
            $orderbySAISYU .= '  ,VIN_VIS  ';
        } elseif ('2' == $con4) {
            $orderbySAISYU .= '  ORDER BY ';
            $orderbySAISYU .= '  DISP_SEQ_TMK ';
            $orderbySAISYU .= '  ,FRGMH DESC ';
            $orderbySAISYU .= '  ,VIN_WMIVDS ';
            $orderbySAISYU .= '  ,VIN_VIS  ';
        }
        //--- 20190305 ci INS S
        elseif ('3' == $con4) {
            $orderbySAISYU .= '  ORDER BY ';
            $orderbySAISYU .= '  DISP_SEQ_TMK ';
            $orderbySAISYU .= '  ,FRGMH DESC ';
            $orderbySAISYU .= '  ,VIN_WMIVDS ';
            $orderbySAISYU .= '  ,VIN_VIS  ';
        }
        //--- 20190305 ci INS E
        elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $orderbySAISYU .= '  ORDER BY ';
            $orderbySAISYU .= '  DISP_SEQ_TM2 ';
            $orderbySAISYU .= '  ,VIN_WMIVDS ';
            $orderbySAISYU .= '  ,VIN_VIS  ';
        }
        //--- 20160301 li UPD E

        $conbineSAISYU = '';
        //--- 20160127 li UPD S
        // $conbineSAISYU .= " HANTEILST.KEKKA_CD=TM2.TEIKEI_CD(+)  ";
        if ('1' == $con4 or '2' == $con4) {
            //	$conbineSAISYU .= " HANTEILST_SINSYA.KEKKA_CD=TM2.TEIKEI_CD(+)  ";
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $conbineSAISYU .= '  AND ';
            $conbineSAISYU .= ' HANTEILST.KEKKA_CD=TM2.TEIKEI_CD(+)  ';
        }
        //--- 20160127 li UPD E
        //--- 20160127 li UPD S
        // $conbineSAISYU .= " '2'=TM2.TEIKEI_TYPE(+)  ";

        if ('1' == $con4 or '2' == $con4) {
            //	$conbineSAISYU .= " '" . $strType . "'=TM2.TEIKEI_TYPE(+)  ";
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $conbineSAISYU .= '  AND ';
            $conbineSAISYU .= " '2'=TM2.TEIKEI_TYPE(+)  ";
        }
        //--- 20160127 li UPD E

        $conbineKATU = '';
        $conbineKATU .= ' AND ';
        //--- 20160127 li UPD S
        // $conbineKATU .= " CASE WHEN rtrim(HANTEILST.HANTEI7_CD) IS NOT NULL THEN  HANTEILST.HANTEI7_CD ";
        // $conbineKATU .= " WHEN rtrim(HANTEILST.HANTEI6_CD) IS NOT NULL THEN  HANTEILST.HANTEI6_CD ";
        // $conbineKATU .= " WHEN rtrim(HANTEILST.HANTEI5_CD) IS NOT NULL THEN  HANTEILST.HANTEI5_CD ";
        // $conbineKATU .= " WHEN rtrim(HANTEILST.HANTEI4_CD) IS NOT NULL THEN  HANTEILST.HANTEI4_CD ";
        // $conbineKATU .= " WHEN rtrim(HANTEILST.HANTEI3_CD) IS NOT NULL THEN  HANTEILST.HANTEI3_CD ";
        // $conbineKATU .= " WHEN rtrim(HANTEILST.HANTEI2_CD) IS NOT NULL THEN  HANTEILST.HANTEI2_CD ";
        // $conbineKATU .= " WHEN rtrim(HANTEILST.HANTEI1_CD) IS NOT NULL THEN  HANTEILST.HANTEI1_CD ";

        if ('1' == $con4 or '2' == $con4) {
            $conbineKATU .= ' CASE WHEN rtrim(HANTEILST_SINSYA.HANTEI7_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI7_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI6_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI6_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI5_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI5_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI4_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI4_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI3_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI3_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI2_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI2_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI1_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI1_CD ';
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $conbineKATU .= ' CASE WHEN rtrim(HANTEILST.HANTEI7_CD) IS NOT NULL THEN  HANTEILST.HANTEI7_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST.HANTEI6_CD) IS NOT NULL THEN  HANTEILST.HANTEI6_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST.HANTEI5_CD) IS NOT NULL THEN  HANTEILST.HANTEI5_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST.HANTEI4_CD) IS NOT NULL THEN  HANTEILST.HANTEI4_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST.HANTEI3_CD) IS NOT NULL THEN  HANTEILST.HANTEI3_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST.HANTEI2_CD) IS NOT NULL THEN  HANTEILST.HANTEI2_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST.HANTEI1_CD) IS NOT NULL THEN  HANTEILST.HANTEI1_CD ';
        }
        //--- 20160127 li UPD E
        $conbineKATU .= " ELSE 'ZZZZZ' ";
        $conbineKATU .= ' END  = TM1.TEIKEI_CD(+)';
        $conbineKATU .= ' AND ';
        //--- 20160127 li UPD S
        // $conbineKATU .= " '1'  = TM1.TEIKEI_TYPE(+)";
        //--- 20190227 ci UPD S
        //if ($con4 == "1" or $con4 == "2" or $con4 == "3")
        if ('1' == $con4 or '2' == $con4 or '3' == $con4) {
            //--- 20190227 ci UPD E
            $conbineKATU .= " '" . $strType . "'  = TM1.TEIKEI_TYPE(+)";
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $conbineKATU .= " '1'  = TM1.TEIKEI_TYPE(+)";
        }

        //--- 20160127 li UPD E
        $selectKATU = '';
        //--- 20160301 li UPD S
        // $selectKATU .= "  NVL(TM1.DISP_SEQ,999) AS DISP_SEQ_TM1, ";
        // $selectSAISYU = "";
        // $selectSAISYU .= "  NVL(TM2.DISP_SEQ,999) AS DISP_SEQ_TM2, ";
        if ('1' == $con4) {
            $selectKATU .= '  NVL(TM7.DISP_SEQ,999) AS DISP_SEQ_TM7, ';
            $selectSAISYU = '';
            $selectSAISYU .= '  NVL(TM7.DISP_SEQ,999) AS DISP_SEQ_TM7, ';
        } elseif ('2' == $con4) {
            $selectKATU .= '  NVL(TMK.DISP_SEQ,999) AS DISP_SEQ_TMK, ';
            $selectSAISYU = '';
            $selectSAISYU .= '  NVL(TMK.DISP_SEQ,999) AS DISP_SEQ_TMK, ';
        }
        //--- 20190305 ci INS S
        elseif ('3' == $con4) {
            $selectKATU .= '  NVL(TMK.DISP_SEQ,999) AS DISP_SEQ_TMK, ';
            $selectSAISYU = '';
            $selectSAISYU .= '  NVL(TMK.DISP_SEQ,999) AS DISP_SEQ_TMK, ';
        }
        //--- 20190305 ci INS E
        elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $selectKATU .= '  NVL(TM1.DISP_SEQ,999) AS DISP_SEQ_TM1, ';
            $selectSAISYU = '';
            $selectSAISYU .= '  NVL(TM2.DISP_SEQ,999) AS DISP_SEQ_TM2, ';
        }
        //--- 20160301 li UPD E

        $tableKATU = ' HANTEITEIKEIMST TM1, ';
        $tableSAISYU = ' HANTEITEIKEIMST TM2, ';

        //----20220121 sun upd s
        //$str = substr($con1, 0, 2);
        $str = '';
        if (false !== strpos($con1, ',')) {
            $exp = explode(',', $con1);
            for ($i = 0; $i < count($exp); ++$i) {
                $str .= "'" . substr($exp[$i], 0, 2) . "',";
            }
            $str = substr($str, 0, strlen($str) - 1);
        } else {
            $str = "'" . substr($con1, 0, 2) . "'";
        }
        //----20220121 sun upd e

        $whereKATU = '';
        $whereKATU .= ' AND ';
        $whereKATU .= ' SUBSTR( ';
        //--- 20160127 li UPD S
        // $whereKATU .= " CASE WHEN rtrim(HANTEILST.HANTEI7_CD) IS NOT NULL THEN  HANTEILST.HANTEI7_CD ";
        // $whereKATU .= " WHEN rtrim(HANTEILST.HANTEI6_CD) IS NOT NULL THEN  HANTEILST.HANTEI6_CD ";
        // $whereKATU .= " WHEN rtrim(HANTEILST.HANTEI5_CD) IS NOT NULL THEN  HANTEILST.HANTEI5_CD ";
        // $whereKATU .= " WHEN rtrim(HANTEILST.HANTEI4_CD) IS NOT NULL THEN  HANTEILST.HANTEI4_CD ";
        // $whereKATU .= " WHEN rtrim(HANTEILST.HANTEI3_CD) IS NOT NULL THEN  HANTEILST.HANTEI3_CD ";
        // $whereKATU .= " WHEN rtrim(HANTEILST.HANTEI2_CD) IS NOT NULL THEN  HANTEILST.HANTEI2_CD ";
        // $whereKATU .= " WHEN rtrim(HANTEILST.HANTEI1_CD) IS NOT NULL THEN  HANTEILST.HANTEI1_CD ";
        if ('1' == $con4 or '2' == $con4) {
            $whereKATU .= ' CASE WHEN rtrim(HANTEILST_SINSYA.HANTEI7_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI7_CD';
            $whereKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI6_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI6_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI5_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI5_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI4_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI4_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI3_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI3_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI2_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI2_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI1_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI1_CD ';
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $whereKATU .= ' CASE WHEN rtrim(HANTEILST.HANTEI7_CD) IS NOT NULL THEN  HANTEILST.HANTEI7_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST.HANTEI6_CD) IS NOT NULL THEN  HANTEILST.HANTEI6_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST.HANTEI5_CD) IS NOT NULL THEN  HANTEILST.HANTEI5_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST.HANTEI4_CD) IS NOT NULL THEN  HANTEILST.HANTEI4_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST.HANTEI3_CD) IS NOT NULL THEN  HANTEILST.HANTEI3_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST.HANTEI2_CD) IS NOT NULL THEN  HANTEILST.HANTEI2_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST.HANTEI1_CD) IS NOT NULL THEN  HANTEILST.HANTEI1_CD ';
        }
        //--- 20160127 li UPD E
        $whereKATU .= " ELSE 'ZZZZZ' ";
        $whereKATU .= ' END ';
        //----20220121 sun upd s
        //$whereKATU .= " ,0,2) ='" . $str . "' ";
        $whereKATU .= ' ,0,2) IN (' . $str . ') ';
        //----20220121 sun upd e

        $str = substr($con2, 0, 2);

        $whereSAI = '';

        //--- 20160127 li UPD S
        // $whereSAI .= " SUBSTR(HANTEILST.KEKKA_CD ,0,2) = '" . $str . "' ";
        // $whereSAI .= " AND ";
        if ('1' == $con4 && '999' != $con2) {
            if ('998' == $con2) {
                $whereSAI .= " AND nvl(HANTEILST_SINSYA.HANTEI7_CD,' ') not in (select TEIKEI_CD from HANTEITEIKEIMST where  ITEMNAME1 like '入庫済%' and TEIKEI_TYPE = '3') ";
            } else {
                $whereSAI .= " AND SUBSTR(HANTEILST_SINSYA.HANTEI7_CD ,0,2) = '" . $str . "' ";
            }
        } elseif ('2' == $con4 && '999' != $con2) {
            if ('998' == $con2) {
                $whereSAI .= " AND nvl(HANTEILST_SINSYA.KEKKA_CD,' ') not in (select TEIKEI_CD from HANTEITEIKEIMST where  ITEMNAME1 like '入庫済%' and TEIKEI_TYPE = '4') ";
            } else {
                $whereSAI .= " AND SUBSTR(HANTEILST_SINSYA.KEKKA_CD ,0,2) = '" . $str . "' ";
            }
        }
        //--- 20190227 ci INS S
        elseif ('3' == $con4 && '999' != $con2) {
            if ('998' == $con2) {
                $whereSAI .= " AND nvl(HANTEILST_CHUKO.KEKKA1_CD,' ') not in (select TEIKEI_CD from HANTEITEIKEIMST where  ITEMNAME1 like '入庫済%' and TEIKEI_TYPE = '5') ";
            } else {
                $whereSAI .= " AND SUBSTR(HANTEILST_CHUKO.KEKKA_CD ,0,2) = '" . $str . "' ";
            }
        }
        //--- 20190227 ci INS E
        elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $whereSAI .= " AND SUBSTR(HANTEILST.KEKKA_CD ,0,2) = '" . $str . "' ";
        }
        //--- 20160127 li UPD E

        //並び順指定
        //--- 20160301 li UPD S
        // if ($con3 == "0") {
        // //車両区分順
        // if ($con1 != '000' && $con2 != '000') {
        // $str_sql = str_replace("{condition_list}", $whereKATU . $whereSAI, $str_sql);
        // $str_sql = str_replace("{conbine_list}", $conbineKATU . $conbineSAISYU, $str_sql);
        // $str_sql = str_replace("{table_order}", $tableKATU . $tableSAISYU, $str_sql);
        // } else {
        // if ($con1 == '000' && $con2 == '000') {
        // $str_sql = str_replace("{conbine_list}", "", $str_sql);
        // $str_sql = str_replace("{condition_list}", "", $str_sql);
        // $str_sql = str_replace("{table_order}", "", $str_sql);
        // } else if ($con1 != '000' && $con2 == '000') {
        // $str_sql = str_replace("{conbine_list}", $whereKATU, $str_sql);
        // $str_sql = str_replace("{condition_list}", $conbineKATU, $str_sql);
        // $str_sql = str_replace("{table_order}", $tableKATU, $str_sql);
        // } else {
        // $str_sql = str_replace("{conbine_list}", $whereSAI, $str_sql);
        // $str_sql = str_replace("{condition_list}", $conbineSAISYU, $str_sql);
        // $str_sql = str_replace("{table_order}", $tableSAISYU, $str_sql);
        // }
        // }
        //
        // $str_sql = str_replace("{select_list}", "", $str_sql);
        // $str_sql = str_replace("{orderby}", $orderby, $str_sql);
        //
        // } elseif ($con3 == "1") {
        // //活動状況順
        // if ($con1 != '000' && $con2 != '000') {
        // $str_sql = str_replace("{condition_list}", $whereKATU . $whereSAI, $str_sql);
        // $str_sql = str_replace("{conbine_list}", $conbineKATU . $conbineSAISYU, $str_sql);
        // $str_sql = str_replace("{table_order}", $tableKATU . $tableSAISYU, $str_sql);
        // } else {
        // if ($con1 == '000' && $con2 == '000') {
        // $str_sql = str_replace("{conbine_list}", $conbineKATU, $str_sql);
        // $str_sql = str_replace("{condition_list}", "", $str_sql);
        // $str_sql = str_replace("{table_order}", $tableKATU, $str_sql);
        // } else if ($con1 != '000' && $con2 == '000') {
        // $str_sql = str_replace("{conbine_list}", $conbineKATU, $str_sql);
        // $str_sql = str_replace("{condition_list}", $whereKATU, $str_sql);
        // $str_sql = str_replace("{table_order}", $tableKATU, $str_sql);
        // } else {
        // $str_sql = str_replace("{conbine_list}", $conbineKATU . $conbineSAISYU, $str_sql);
        // $str_sql = str_replace("{condition_list}", $whereSAI, $str_sql);
        // $str_sql = str_replace("{table_order}", $tableKATU . $tableSAISYU, $str_sql);
        // }
        // }
        //
        // $str_sql = str_replace("{select_list}", $selectKATU, $str_sql);
        // $str_sql = str_replace("{orderby}", $orderbyKATU, $str_sql);
        // } else {
        // //最終結果順
        // if ($con1 != '000' && $con2 != '000') {
        // $str_sql = str_replace("{condition_list}", $whereKATU . $whereSAI, $str_sql);
        // $str_sql = str_replace("{conbine_list}", $conbineKATU . $conbineSAISYU, $str_sql);
        // $str_sql = str_replace("{table_order}", $tableKATU . $tableSAISYU, $str_sql);
        // } else {
        // if ($con1 == '000' && $con2 == '000') {
        // $str_sql = str_replace("{conbine_list}", $conbineSAISYU, $str_sql);
        // $str_sql = str_replace("{condition_list}", "", $str_sql);
        // $str_sql = str_replace("{table_order}", $tableSAISYU, $str_sql);
        // } elseif ($con1 != '000' && $con2 == '000') {
        // $str_sql = str_replace("{conbine_list}", $conbineKATU . $conbineSAISYU, $str_sql);
        // $str_sql = str_replace("{condition_list}", $whereKATU, $str_sql);
        // $str_sql = str_replace("{table_order}", $tableKATU . $tableSAISYU, $str_sql);
        // } else {
        // $str_sql = str_replace("{conbine_list}", $whereSAI, $str_sql);
        // $str_sql = str_replace("{condition_list}", $conbineSAISYU, $str_sql);
        // $str_sql = str_replace("{table_order}", $tableSAISYU, $str_sql);
        // }
        // }
        // $str_sql = str_replace("{select_list}", $selectSAISYU, $str_sql);
        // $str_sql = str_replace("{orderby}", $orderbySAISYU, $str_sql);
        // }

        //----20220121 sun upd s
        //if ($con4 == "0")
        if ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            if ('0' == $con3) {
                //車両区分順
                if ('000' != $con1 && '000' != $con2) {
                    $str_sql = str_replace('{condition_list}', $whereKATU . $whereSAI, $str_sql);
                    $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                    $str_sql = str_replace('{table_order}', $tableKATU . $tableSAISYU, $str_sql);
                } else {
                    if ('000' == $con1 && '000' == $con2) {
                        $str_sql = str_replace('{conbine_list}', '', $str_sql);
                        $str_sql = str_replace('{condition_list}', '', $str_sql);
                        $str_sql = str_replace('{table_order}', '', $str_sql);
                    } elseif ('000' != $con1 && '000' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $whereKATU, $str_sql);
                        $str_sql = str_replace('{condition_list}', $conbineKATU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                    } else {
                        $str_sql = str_replace('{conbine_list}', $whereSAI, $str_sql);
                        $str_sql = str_replace('{condition_list}', $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableSAISYU, $str_sql);
                    }
                }

                $str_sql = str_replace('{select_list}', '', $str_sql);
                $str_sql = str_replace('{orderby}', $orderby, $str_sql);
            } elseif ('1' == $con3) {
                //活動状況順
                if ('000' != $con1 && '000' != $con2) {
                    $str_sql = str_replace('{condition_list}', $whereKATU . $whereSAI, $str_sql);
                    $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                    $str_sql = str_replace('{table_order}', $tableKATU . $tableSAISYU, $str_sql);
                } else {
                    if ('000' == $con1 && '000' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $conbineKATU, $str_sql);
                        $str_sql = str_replace('{condition_list}', '', $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                    } elseif ('000' != $con1 && '000' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $conbineKATU, $str_sql);
                        $str_sql = str_replace('{condition_list}', $whereKATU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                    } else {
                        $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{condition_list}', $whereSAI, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU . $tableSAISYU, $str_sql);
                    }
                }

                $str_sql = str_replace('{select_list}', $selectKATU, $str_sql);
                $str_sql = str_replace('{orderby}', $orderbyKATU, $str_sql);
            } else {
                //最終結果順
                if ('000' != $con1 && '000' != $con2) {
                    $str_sql = str_replace('{condition_list}', $whereKATU . $whereSAI, $str_sql);
                    $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                    $str_sql = str_replace('{table_order}', $tableKATU . $tableSAISYU, $str_sql);
                } else {
                    if ('000' == $con1 && '000' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{condition_list}', '', $str_sql);
                        $str_sql = str_replace('{table_order}', $tableSAISYU, $str_sql);
                    } elseif ('000' != $con1 && '000' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{condition_list}', $whereKATU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU . $tableSAISYU, $str_sql);
                    } else {
                        $str_sql = str_replace('{conbine_list}', $whereSAI, $str_sql);
                        $str_sql = str_replace('{condition_list}', $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableSAISYU, $str_sql);
                    }
                }

                $str_sql = str_replace('{select_list}', $selectSAISYU, $str_sql);
                $str_sql = str_replace('{orderby}', $orderbySAISYU, $str_sql);
            }
        } elseif //--- 20190227 ci UPD S
        ('1' == $con4 or '2' == $con4 or '3' == $con4) {
            //--- 20190227 ci UPD E
            if ('0' == $con3) {
                //車両区分順
                if ('000' != $con1 && '999' != $con2) {
                    $str_sql = str_replace('{condition_list}', $whereKATU . $whereSAI, $str_sql);
                    $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                    //	$str_sql = str_replace("{table_order}", $tableKATU . $tableSAISYU, $str_sql);
                    $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                } else {
                    if ('000' == $con1 && '999' == $con2) {
                        $str_sql = str_replace('{conbine_list}', '', $str_sql);
                        $str_sql = str_replace('{condition_list}', '', $str_sql);
                        $str_sql = str_replace('{table_order}', '', $str_sql);
                    } elseif ('000' != $con1 && '999' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $whereKATU, $str_sql);
                        $str_sql = str_replace('{condition_list}', $conbineKATU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                    } else {
                        $str_sql = str_replace('{conbine_list}', $whereSAI, $str_sql);
                        $str_sql = str_replace('{condition_list}', $conbineSAISYU, $str_sql);
                        //$str_sql = str_replace("{table_order}", $tableSAISYU, $str_sql);
                        $str_sql = str_replace('{table_order}', '', $str_sql);
                    }
                }

                $str_sql = str_replace('{select_list}', '', $str_sql);
                $str_sql = str_replace('{orderby}', $orderby, $str_sql);
            } elseif ('1' == $con3) {
                //活動状況順
                if ('000' != $con1 && '999' != $con2) {
                    $str_sql = str_replace('{condition_list}', $whereKATU . $whereSAI, $str_sql);
                    $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                    //$str_sql = str_replace("{table_order}", $tableKATU . $tableSAISYU, $str_sql);
                    $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                } else {
                    if ('000' == $con1 && '999' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $conbineKATU, $str_sql);
                        $str_sql = str_replace('{condition_list}', '', $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                    } elseif ('000' != $con1 && '999' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $conbineKATU, $str_sql);
                        $str_sql = str_replace('{condition_list}', $whereKATU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                    } else {
                        $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{condition_list}', $whereSAI, $str_sql);
                        //$str_sql = str_replace("{table_order}", $tableKATU . $tableSAISYU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                    }
                }

                $str_sql = str_replace('{select_list}', $selectKATU, $str_sql);
                $str_sql = str_replace('{orderby}', $orderbyKATU, $str_sql);
            } else {
                //最終結果順
                if ('000' != $con1 && '999' != $con2) {
                    $str_sql = str_replace('{condition_list}', $whereKATU . $whereSAI, $str_sql);
                    $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                    //$str_sql = str_replace("{table_order}", $tableKATU . $tableSAISYU, $str_sql);
                    $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                } else {
                    if ('000' == $con1 && '999' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{condition_list}', '', $str_sql);
                        //$str_sql = str_replace("{table_order}", $tableSAISYU, $str_sql);
                        $str_sql = str_replace('{table_order}', '', $str_sql);
                    } elseif ('000' != $con1 && '999' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{condition_list}', $whereKATU, $str_sql);
                        //$str_sql = str_replace("{table_order}", $tableKATU . $tableSAISYU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                    } else {
                        $str_sql = str_replace('{conbine_list}', $whereSAI, $str_sql);
                        $str_sql = str_replace('{condition_list}', $conbineSAISYU, $str_sql);
                        //$str_sql = str_replace("{table_order}", $tableSAISYU, $str_sql);
                        $str_sql = str_replace('{table_order}', '', $str_sql);
                    }
                }

                $str_sql = str_replace('{select_list}', $selectSAISYU, $str_sql);
                $str_sql = str_replace('{orderby}', $orderbySAISYU, $str_sql);
            }
        }
        //--- 20160301 li UPD E
        return $str_sql;
    }

    /**
     * 営業全員 を指定している場合.
     *
     * @param {String}
     * 管理チームコード:$tenpo_code
     * 車検満了日:$syaken_manryobi
     *
     * @return {String} select文
     */
    //--- 20160127 li UPD S
    // public function m_select_sdh01_08_sql_eigyozenin($tenpo_code, $syaken_manryobi, $con, $con1, $con2, $con3) {
    public function m_select_sdh01_08_sql_eigyozenin($tenpo_code, $syaken_manryobi, $con, $con1, $con2, $con3, $con4)
    {
        $strType = '';
        if ('1' == $con4) {
            $strType = '3';
        }
        if ('2' == $con4) {
            $strType = '4';
        }
        //--- 20190228 ci INS S
        if ('3' == $con4) {
            $strType = '5';
        }
        //--- 20190228 ci INS E
        //--- 20160127 li UPD E

        $str_sql = '';
        //--- 20160127 li UPD S
        // $str_sql = $this -> m_select_sdh01_08_sql($syaken_manryobi);
        $str_sql = $this->m_select_sdh01_08_sql($syaken_manryobi, $con4);
        //--- 20160127 li UPD E
        $str_where = '';
        $str_where .= 'AND ';

        //管理チームコード s
        $str_where .= "  SUBSTR(M41C04.KNR_STRCD,1,2) = '" . $tenpo_code . "' ";
        $str_where .= ' AND ';
        $str_where .= "  M41C04.KNR_STRCD <> '" . $tenpo_code . "7' ";

        //管理チームコード e
        $str_sql = str_replace('{tantousya_list}', $str_where, $str_sql);

        //要注意リスト抽出用
        if ('1' == $con) {
            $str_where = '';
            $str_table = '';
            $str_where .= 'AND ';
            $str_where .= 'M41C04.B1_KATANCD IS NOT NULL ';
            $str_where .= 'AND ';
            $str_where .= 'M41C04.B2_KATANCD IS NOT NULL ';
            $str_where .= 'AND ';
            $str_where .= 'M41C04.B3_KATANCD IS NOT NULL ';
            $str_sql = str_replace('{table_list}', $str_table, $str_sql);
            $str_sql = str_replace('{tantousya_list1}', $str_where, $str_sql);
        } elseif ('2' == $con) {
            $str_where = '';
            $str_table = '';
            $str_where .= 'AND ';
            $str_where .= 'M41C04.KNR_BUSMANCD = HHAIZOKU.SYAIN_NO ';
            $str_where .= 'AND ';
            $str_where .= 'M41C04.KNR_STRCD <> HHAIZOKU.BUSYO_CD ';
            $str_where .= 'AND ';
            $str_where .= 'HHAIZOKU.END_DATE IS NULL ';
            $str_table = ' HHAIZOKU,';
            $str_sql = str_replace('{table_list}', $str_table, $str_sql);
            $str_sql = str_replace('{tantousya_list1}', $str_where, $str_sql);
        } elseif ('3' == $con) {
            $str_where = '';
            $str_table = '';
            $str_where .= 'AND ';
            $str_where .= 'M41C04.KNR_BUSMANCD = BTH29MA4.SYAIN_NO ';
            $str_where .= 'AND ';
            $str_where .= "'3634'              = BTH29MA4.HANSH_CD ";
            $str_where .= 'AND ';
            $str_where .= 'BTH29MA4.RISYOKU_DATE IS NOT NULL ';
            $str_table = ' BTH29MA4,';
            $str_sql = str_replace('{table_list}', $str_table, $str_sql);
            $str_sql = str_replace('{tantousya_list1}', $str_where, $str_sql);
        } else {
            $str_where = '';
            $str_table = '';
            $str_sql = str_replace('{table_list}', $str_table, $str_sql);
            $str_sql = str_replace('{tantousya_list1}', $str_where, $str_sql);
        }

        $str_where = '';
        $orderby = '';
        if ('1' == $con4 or '2' == $con4 or '3' == $con4) {
            $orderby .= '  ORDER BY ';
            $orderby .= '  XH10CAID,FRGMH DESC ';
            $orderby .= '  ,VIN_WMIVDS ';
            $orderby .= '  ,VIN_VIS  ';
        }

        //----20220121 sun add s
        elseif ('4' == $con4) {
            $orderby .= '  ORDER BY ';
            //20220519 YIN UPD S
            // $orderby .= "  TMN.DISP_SEQ";
            $orderby .= '  TMSEQ.DISP_SEQ';
            //20220519 YIN UPD E
            $orderby .= '  ,XH10CAID,VCLIPEDT';
            $orderby .= '  ,VIN_WMIVDS ';
            $orderby .= '  ,VIN_VIS  ';
        }
        //----20220121 sun add e
        else {
            $orderby .= '  ORDER BY ';
            $orderby .= '  XH10CAID,VCLIPEDT ';
            $orderby .= '  ,VIN_WMIVDS ';
            $orderby .= '  ,VIN_VIS  ';
        }

        $orderbyKATU = '';
        $orderbyKATU .= '  ORDER BY ';
        $orderbyKATU .= '  DISP_SEQ_TM1 ';
        $orderbyKATU .= '  ,VIN_WMIVDS ';
        $orderbyKATU .= '  ,VIN_VIS  ';

        $orderbySAISYU = '';
        //--- 20160301 li UPD S
        // $orderbySAISYU .= "  ORDER BY ";
        // $orderbySAISYU .= "  DISP_SEQ_TM2 ";
        // $orderbySAISYU .= "  ,VIN_WMIVDS ";
        // $orderbySAISYU .= "  ,VIN_VIS  ";

        if ('1' == $con4) {
            $orderbySAISYU .= '  ORDER BY ';
            $orderbySAISYU .= '  DISP_SEQ_TM7 ';
            $orderbySAISYU .= '  ,FRGMH DESC ';
            $orderbySAISYU .= '  ,VIN_WMIVDS ';
            $orderbySAISYU .= '  ,VIN_VIS  ';
        } elseif ('2' == $con4) {
            $orderbySAISYU .= '  ORDER BY ';
            $orderbySAISYU .= '  DISP_SEQ_TMK ';
            $orderbySAISYU .= '  ,FRGMH DESC ';
            $orderbySAISYU .= '  ,VIN_WMIVDS ';
            $orderbySAISYU .= '  ,VIN_VIS  ';
        }
        //--- 20190305 ci INS S
        elseif ('3' == $con4) {
            $orderbySAISYU .= '  ORDER BY ';
            $orderbySAISYU .= '  DISP_SEQ_TMK ';
            $orderbySAISYU .= '  ,FRGMH DESC ';
            $orderbySAISYU .= '  ,VIN_WMIVDS ';
            $orderbySAISYU .= '  ,VIN_VIS  ';
        }
        //--- 20190305 ci INS E
        elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $orderbySAISYU .= '  ORDER BY ';
            $orderbySAISYU .= '  DISP_SEQ_TM2 ';
            $orderbySAISYU .= '  ,VIN_WMIVDS ';
            $orderbySAISYU .= '  ,VIN_VIS  ';
        }
        //--- 20160301 li UPD E

        $conbineSAISYU = '';
        //--- 20160127 li UPD S
        // $conbineSAISYU .= " HANTEILST.KEKKA_CD=TM2.TEIKEI_CD(+)  ";
        if ('1' == $con4 or '2' == $con4) {
            //	$conbineSAISYU .= " HANTEILST_SINSYA.KEKKA_CD=TM2.TEIKEI_CD(+)  ";
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $conbineSAISYU .= '  AND ';
            $conbineSAISYU .= ' HANTEILST.KEKKA_CD=TM2.TEIKEI_CD(+)  ';
        }
        //--- 20160127 li UPD E
        //$conbineSAISYU .= "  AND ";
        //--- 20160127 li UPD S
        // $conbineSAISYU .= " '2'=TM2.TEIKEI_TYPE(+)  ";
        if ('1' == $con4 or '2' == $con4) {
            //	$conbineSAISYU .= " '" . $strType . "' = TM2.TEIKEI_TYPE(+)  ";
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $conbineSAISYU .= '  AND ';
            $conbineSAISYU .= " '2'=TM2.TEIKEI_TYPE(+)  ";
        }
        //--- 20160127 li UPD E

        $conbineKATU = '';
        $conbineKATU .= ' AND ';
        //--- 20160127 li UPD S
        // $conbineKATU .= " CASE WHEN rtrim(HANTEILST.HANTEI7_CD) IS NOT NULL THEN  HANTEILST.HANTEI7_CD ";
        // $conbineKATU .= " WHEN rtrim(HANTEILST.HANTEI6_CD) IS NOT NULL THEN  HANTEILST.HANTEI6_CD ";
        // $conbineKATU .= " WHEN rtrim(HANTEILST.HANTEI5_CD) IS NOT NULL THEN  HANTEILST.HANTEI5_CD ";
        // $conbineKATU .= " WHEN rtrim(HANTEILST.HANTEI4_CD) IS NOT NULL THEN  HANTEILST.HANTEI4_CD ";
        // $conbineKATU .= " WHEN rtrim(HANTEILST.HANTEI3_CD) IS NOT NULL THEN  HANTEILST.HANTEI3_CD ";
        // $conbineKATU .= " WHEN rtrim(HANTEILST.HANTEI2_CD) IS NOT NULL THEN  HANTEILST.HANTEI2_CD ";
        // $conbineKATU .= " WHEN rtrim(HANTEILST.HANTEI1_CD) IS NOT NULL THEN  HANTEILST.HANTEI1_CD ";
        if ('1' == $con4 or '2' == $con4) {
            $conbineKATU .= ' CASE WHEN rtrim(HANTEILST_SINSYA.HANTEI7_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI7_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI6_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI6_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI5_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI5_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI4_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI4_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI3_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI3_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI2_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI2_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI1_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI1_CD ';
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $conbineKATU .= ' CASE WHEN rtrim(HANTEILST.HANTEI7_CD) IS NOT NULL THEN  HANTEILST.HANTEI7_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST.HANTEI6_CD) IS NOT NULL THEN  HANTEILST.HANTEI6_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST.HANTEI5_CD) IS NOT NULL THEN  HANTEILST.HANTEI5_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST.HANTEI4_CD) IS NOT NULL THEN  HANTEILST.HANTEI4_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST.HANTEI3_CD) IS NOT NULL THEN  HANTEILST.HANTEI3_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST.HANTEI2_CD) IS NOT NULL THEN  HANTEILST.HANTEI2_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST.HANTEI1_CD) IS NOT NULL THEN  HANTEILST.HANTEI1_CD ';
        }
        //--- 20160127 li UPD E
        $conbineKATU .= " ELSE 'ZZZZZ' ";
        $conbineKATU .= ' END  = TM1.TEIKEI_CD(+)';
        $conbineKATU .= ' AND ';
        //--- 20160127 li UPD S
        // $conbineKATU .= " '1'  = TM1.TEIKEI_TYPE(+)";
        //--- 20190228 li UPD S
        if ('1' == $con4 or '2' == $con4 or '3' == $con4) {
            //--- 20190228 li UPD E
            $conbineKATU .= " '" . $strType . "'  = TM1.TEIKEI_TYPE(+)";
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $conbineKATU .= " '1'  = TM1.TEIKEI_TYPE(+)";
        }
        //--- 20160127 li UPD E
        $selectKATU = '';
        //--- 20160301 li UPD S

        // $selectKATU .= "  NVL(TM1.DISP_SEQ,999) AS DISP_SEQ_TM1, ";
        // $selectSAISYU = "";
        // $selectSAISYU .= "  NVL(TM2.DISP_SEQ,999) AS DISP_SEQ_TM2, ";
        if ('1' == $con4) {
            $selectKATU .= '  NVL(TM7.DISP_SEQ,999) AS DISP_SEQ_TM7, ';
            $selectSAISYU = '';
            $selectSAISYU .= '  NVL(TM7.DISP_SEQ,999) AS DISP_SEQ_TM7, ';
        } elseif ('2' == $con4) {
            $selectKATU .= '  NVL(TMK.DISP_SEQ,999) AS DISP_SEQ_TMK, ';
            $selectSAISYU = '';
            $selectSAISYU .= '  NVL(TMK.DISP_SEQ,999) AS DISP_SEQ_TMK, ';
        }
        //--- 20190305 ci INS S
        elseif ('3' == $con4) {
            $selectKATU .= '  NVL(TMK.DISP_SEQ,999) AS DISP_SEQ_TMK, ';
            $selectSAISYU = '';
            $selectSAISYU .= '  NVL(TMK.DISP_SEQ,999) AS DISP_SEQ_TMK, ';
        }
        //--- 20190305 ci INS E
        elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $selectKATU .= '  NVL(TM1.DISP_SEQ,999) AS DISP_SEQ_TM1, ';
            $selectSAISYU = '';
            $selectSAISYU .= '  NVL(TM2.DISP_SEQ,999) AS DISP_SEQ_TM2, ';
        }
        //--- 20160301 li UPD E

        $tableKATU = ' HANTEITEIKEIMST TM1, ';
        $tableSAISYU = ' HANTEITEIKEIMST TM2, ';

        //----20220121 sun upd s
        //$str = substr($con1, 0, 2);
        $str = '';
        if (false !== strpos($con1, ',')) {
            $exp = explode(',', $con1);
            for ($i = 0; $i < count($exp); ++$i) {
                $str .= "'" . substr($exp[$i], 0, 2) . "',";
            }
            $str = substr($str, 0, strlen($str) - 1);
        } else {
            $str = "'" . substr($con1, 0, 2) . "'";
        }
        //----20220121 sun upd e

        $whereKATU = '';
        $whereKATU .= ' AND ';
        $whereKATU .= ' SUBSTR( ';
        //--- 20160127 li UPD S

        // $whereKATU .= " CASE WHEN rtrim(HANTEILST.HANTEI7_CD) IS NOT NULL THEN  HANTEILST.HANTEI7_CD ";
        // $whereKATU .= " WHEN rtrim(HANTEILST.HANTEI6_CD) IS NOT NULL THEN  HANTEILST.HANTEI6_CD ";
        // $whereKATU .= " WHEN rtrim(HANTEILST.HANTEI5_CD) IS NOT NULL THEN  HANTEILST.HANTEI5_CD ";
        // $whereKATU .= " WHEN rtrim(HANTEILST.HANTEI4_CD) IS NOT NULL THEN  HANTEILST.HANTEI4_CD ";
        // $whereKATU .= " WHEN rtrim(HANTEILST.HANTEI3_CD) IS NOT NULL THEN  HANTEILST.HANTEI3_CD ";
        // $whereKATU .= " WHEN rtrim(HANTEILST.HANTEI2_CD) IS NOT NULL THEN  HANTEILST.HANTEI2_CD ";
        // $whereKATU .= " WHEN rtrim(HANTEILST.HANTEI1_CD) IS NOT NULL THEN  HANTEILST.HANTEI1_CD ";

        if ('1' == $con4 or '2' == $con4) {
            $whereKATU .= ' CASE WHEN rtrim(HANTEILST_SINSYA.HANTEI7_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI7_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI6_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI6_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI5_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI5_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI4_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI4_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI3_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI3_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI2_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI2_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI1_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI1_CD ';
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $whereKATU .= ' CASE WHEN rtrim(HANTEILST.HANTEI7_CD) IS NOT NULL THEN  HANTEILST.HANTEI7_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST.HANTEI6_CD) IS NOT NULL THEN  HANTEILST.HANTEI6_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST.HANTEI5_CD) IS NOT NULL THEN  HANTEILST.HANTEI5_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST.HANTEI4_CD) IS NOT NULL THEN  HANTEILST.HANTEI4_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST.HANTEI3_CD) IS NOT NULL THEN  HANTEILST.HANTEI3_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST.HANTEI2_CD) IS NOT NULL THEN  HANTEILST.HANTEI2_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST.HANTEI1_CD) IS NOT NULL THEN  HANTEILST.HANTEI1_CD ';
        }
        //--- 20160127 li UPD E
        $whereKATU .= " ELSE 'ZZZZZ' ";
        $whereKATU .= ' END ';
        //----20220121 sun upd s
        //$whereKATU .= " ,0,2) ='" . $str . "' ";
        $whereKATU .= ' ,0,2) IN (' . $str . ') ';
        //----20220121 sun upd e

        $str = substr($con2, 0, 2);
        $whereSAI = '';
        //--- 20160127 li UPD S
        // $whereSAI .= " SUBSTR(HANTEILST.KEKKA_CD ,0,2) = '" . $str . "' ";
        // $whereSAI .= " AND ";

        if ('1' == $con4 && '999' != $con2) {
            if ('998' == $con2) {
                $whereSAI .= " AND nvl(HANTEILST_SINSYA.HANTEI7_CD,' ') not in (select TEIKEI_CD from HANTEITEIKEIMST where  ITEMNAME1 like '入庫済%' and TEIKEI_TYPE = '3') ";
            } else {
                $whereSAI .= " AND SUBSTR(HANTEILST_SINSYA.HANTEI7_CD ,0,2) = '" . $str . "' ";
            }
        } elseif ('2' == $con4 && '999' != $con2) {
            if ('998' == $con2) {
                $whereSAI .= " AND nvl(HANTEILST_SINSYA.KEKKA_CD,' ') not in (select TEIKEI_CD from HANTEITEIKEIMST where  ITEMNAME1 like '入庫済%' and TEIKEI_TYPE = '4') ";
            } else {
                $whereSAI .= " AND SUBSTR(HANTEILST_SINSYA.KEKKA_CD ,0,2) = '" . $str . "' ";
            }
        }
        //--- 20190228 ci INS S
        elseif ('3' == $con4 && '999' != $con2) {
            if ('998' == $con2) {
                $whereSAI .= " AND nvl(HANTEILST_CHUKO.KEKKA1_CD,' ') not in (select TEIKEI_CD from HANTEITEIKEIMST where  ITEMNAME1 like '入庫済%' and TEIKEI_TYPE = '5') ";
            } else {
                $whereSAI .= " AND SUBSTR(HANTEILST_CHUKO.KEKKA_CD ,0,2) = '" . $str . "' ";
            }
        }
        //--- 20190228 ci INS E
        elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $whereSAI .= " AND SUBSTR(HANTEILST.KEKKA_CD ,0,2) = '" . $str . "' ";
        }
        //--- 20160127 li UPD E

        //----20220121 sun upd s
        //if ($con4 == "0")
        if ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            if ('0' == $con3) {
                //車両区分順
                if ('000' != $con1 && '000' != $con2) {
                    $str_sql = str_replace('{condition_list}', $whereKATU . $whereSAI, $str_sql);
                    $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                    $str_sql = str_replace('{table_order}', $tableKATU . $tableSAISYU, $str_sql);
                } else {
                    if ('000' == $con1 && '000' == $con2) {
                        $str_sql = str_replace('{conbine_list}', '', $str_sql);
                        $str_sql = str_replace('{condition_list}', '', $str_sql);
                        $str_sql = str_replace('{table_order}', '', $str_sql);
                    } elseif ('000' != $con1 && '000' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $whereKATU, $str_sql);
                        $str_sql = str_replace('{condition_list}', $conbineKATU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                    } else {
                        $str_sql = str_replace('{conbine_list}', $whereSAI, $str_sql);
                        $str_sql = str_replace('{condition_list}', $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableSAISYU, $str_sql);
                    }
                }

                $str_sql = str_replace('{select_list}', '', $str_sql);
                $str_sql = str_replace('{orderby}', $orderby, $str_sql);
            } elseif ('1' == $con3) {
                //活動状況順
                if ('000' != $con1 && '000' != $con2) {
                    $str_sql = str_replace('{condition_list}', $whereKATU . $whereSAI, $str_sql);
                    $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                    $str_sql = str_replace('{table_order}', $tableKATU . $tableSAISYU, $str_sql);
                } else {
                    if ('000' == $con1 && '000' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $conbineKATU, $str_sql);
                        $str_sql = str_replace('{condition_list}', '', $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                    } elseif ('000' != $con1 && '000' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $conbineKATU, $str_sql);
                        $str_sql = str_replace('{condition_list}', $whereKATU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                    } else {
                        $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{condition_list}', $whereSAI, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU . $tableSAISYU, $str_sql);
                    }
                }

                $str_sql = str_replace('{select_list}', $selectKATU, $str_sql);
                $str_sql = str_replace('{orderby}', $orderbyKATU, $str_sql);
            } else {
                //最終結果順
                if ('000' != $con1 && '000' != $con2) {
                    $str_sql = str_replace('{condition_list}', $whereKATU . $whereSAI, $str_sql);
                    $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                    $str_sql = str_replace('{table_order}', $tableKATU . $tableSAISYU, $str_sql);
                } else {
                    if ('000' == $con1 && '000' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{condition_list}', '', $str_sql);
                        $str_sql = str_replace('{table_order}', $tableSAISYU, $str_sql);
                    } elseif ('000' != $con1 && '000' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{condition_list}', $whereKATU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU . $tableSAISYU, $str_sql);
                    } else {
                        $str_sql = str_replace('{conbine_list}', $whereSAI, $str_sql);
                        $str_sql = str_replace('{condition_list}', $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableSAISYU, $str_sql);
                    }
                }

                $str_sql = str_replace('{select_list}', $selectSAISYU, $str_sql);
                $str_sql = str_replace('{orderby}', $orderbySAISYU, $str_sql);
            }
        } elseif //--- 20190228 ci UPD S
        ('1' == $con4 or '2' == $con4 or '3' == $con4) {
            //--- 20190228 ci UPD E
            if ('0' == $con3) {
                //車両区分順
                if ('000' != $con1 && '999' != $con2) {
                    $str_sql = str_replace('{condition_list}', $whereKATU . $whereSAI, $str_sql);
                    $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                    //$str_sql = str_replace("{table_order}", $tableKATU . $tableSAISYU, $str_sql);
                    $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                } else {
                    if ('000' == $con1 && ('999' == $con2)) {
                        $str_sql = str_replace('{conbine_list}', '', $str_sql);
                        $str_sql = str_replace('{condition_list}', '', $str_sql);
                        $str_sql = str_replace('{table_order}', '', $str_sql);
                    } elseif ('000' != $con1 && '000' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $whereKATU, $str_sql);
                        $str_sql = str_replace('{condition_list}', $conbineKATU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                    } else {
                        $str_sql = str_replace('{conbine_list}', $whereSAI, $str_sql);
                        $str_sql = str_replace('{condition_list}', $conbineSAISYU, $str_sql);
                        //$str_sql = str_replace("{table_order}", $tableSAISYU, $str_sql);
                        $str_sql = str_replace('{table_order}', '', $str_sql);
                    }
                }
                $str_sql = str_replace('{select_list}', '', $str_sql);
                $str_sql = str_replace('{orderby}', $orderby, $str_sql);
            } elseif ('1' == $con3) {
                //活動状況順
                if ('000' != $con1 && '999' != $con2) {
                    $str_sql = str_replace('{condition_list}', $whereKATU . $whereSAI, $str_sql);
                    $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                    //$str_sql = str_replace("{table_order}", $tableKATU . $tableSAISYU, $str_sql);
                    $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                } else {
                    if ('000' == $con1 && '999' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $conbineKATU, $str_sql);
                        $str_sql = str_replace('{condition_list}', '', $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                    } elseif ('000' != $con1 && '999' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $conbineKATU, $str_sql);
                        $str_sql = str_replace('{condition_list}', $whereKATU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                    } else {
                        $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{condition_list}', $whereSAI, $str_sql);
                        //$str_sql = str_replace("{table_order}", $tableKATU . $tableSAISYU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                    }
                }
                $str_sql = str_replace('{select_list}', $selectKATU, $str_sql);
                $str_sql = str_replace('{orderby}', $orderbyKATU, $str_sql);
            } else {
                //最終結果順
                if ('000' != $con1 && '999' != $con2) {
                    $str_sql = str_replace('{condition_list}', $whereKATU . $whereSAI, $str_sql);
                    $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                    //$str_sql = str_replace("{table_order}", $tableKATU . $tableSAISYU, $str_sql);
                } else {
                    if ('000' == $con1 && '999' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{condition_list}', '', $str_sql);
                        //	$str_sql = str_replace("{table_order}", $tableSAISYU, $str_sql);
                        $str_sql = str_replace('{table_order}', '', $str_sql);
                    } elseif ('000' != $con1 && '000' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{condition_list}', $whereKATU, $str_sql);
                        //	$str_sql = str_replace("{table_order}", $tableKATU . $tableSAISYU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                    } else {
                        $str_sql = str_replace('{conbine_list}', $whereSAI, $str_sql);
                        $str_sql = str_replace('{condition_list}', $conbineSAISYU, $str_sql);
                        //	$str_sql = str_replace("{table_order}", $tableSAISYU, $str_sql);
                        $str_sql = str_replace('{table_order}', '', $str_sql);
                    }
                }
                $str_sql = str_replace('{select_list}', $selectSAISYU, $str_sql);
                $str_sql = str_replace('{orderby}', $orderbySAISYU, $str_sql);
            }
        }

        //--- 20160301 li UPD E
        return $str_sql;
    }

    /**
     * サービス全員 を指定している場合.
     *
     * @param {String}
     * 管理チームコード:$tenpo_code
     * 車検満了日:$syaken_manryobi
     *
     * @return {String} select文
     */
    //--- 20160127 li UPD S
    // public function m_select_sdh01_08_sql_saabisuzenin($tenpo_code, $syaken_manryobi, $con, $con1, $con2, $con3) {
    public function m_select_sdh01_08_sql_saabisuzenin($tenpo_code, $syaken_manryobi, $con, $con1, $con2, $con3, $con4)
    {
        $strType = '';
        if ('1' == $con4) {
            $strType = '3';
        }
        if ('2' == $con4) {
            $strType = '4';
        }
        //--- 20190228 ci INS S
        if ('3' == $con4) {
            $strType = '5';
        }
        //--- 20190228 ci INS E
        //--- 20160127 li UPD E
        $str_sql = '';
        //--- 20160127 li UPD S
        // $str_sql = $this -> m_select_sdh01_08_sql($syaken_manryobi);
        $str_sql = $this->m_select_sdh01_08_sql($syaken_manryobi, $con4);
        //--- 20160127 li UPD E
        $str_where = '';
        $str_where .= 'AND ';

        //サービスチームコード s
        $str_where .= '(';
        $str_where .= "  M41C04.KNR_STRCD <> '" . $tenpo_code . "1' ";
        $str_where .= ' AND ';
        $str_where .= "  substr(M41C04.SRV_SRVSTRCD,1,2) = '" . $tenpo_code . "' ";
        $str_where .= ')';

        //サービスチームコード e
        $str_sql = str_replace('{tantousya_list}', $str_where, $str_sql);

        //要注意リスト抽出用
        if ('1' == $con) {
            $str_where = '';
            $str_table = '';
            $str_where .= 'AND ';
            $str_where .= 'M41C04.B1_KATANCD IS NOT NULL ';
            $str_where .= 'AND ';
            $str_where .= 'M41C04.B2_KATANCD IS NOT NULL ';
            $str_where .= 'AND ';
            $str_where .= 'M41C04.B3_KATANCD IS NOT NULL ';
            $str_sql = str_replace('{table_list}', $str_table, $str_sql);
            $str_sql = str_replace('{tantousya_list1}', $str_where, $str_sql);
        } elseif ('2' == $con) {
            $str_where = '';
            $str_table = '';
            $str_where .= 'AND ';
            $str_where .= 'M41C04.KNR_BUSMANCD = HHAIZOKU.SYAIN_NO ';
            $str_where .= 'AND ';
            $str_where .= 'M41C04.KNR_STRCD <> HHAIZOKU.BUSYO_CD ';
            $str_where .= 'AND ';
            $str_where .= 'HHAIZOKU.END_DATE IS NULL ';
            $str_table = ' HHAIZOKU,';
            $str_sql = str_replace('{table_list}', $str_table, $str_sql);
            $str_sql = str_replace('{tantousya_list1}', $str_where, $str_sql);
        } elseif ('3' == $con) {
            $str_where = '';
            $str_table = '';
            $str_where .= 'AND ';
            $str_where .= 'M41C04.KNR_BUSMANCD = BTH29MA4.SYAIN_NO ';
            $str_where .= 'AND ';
            $str_where .= "'3634'              = BTH29MA4.HANSH_CD ";
            $str_where .= 'AND ';
            $str_where .= 'BTH29MA4.RISYOKU_DATE IS NOT NULL ';
            $str_table = ' BTH29MA4,';
            $str_sql = str_replace('{table_list}', $str_table, $str_sql);
            $str_sql = str_replace('{tantousya_list1}', $str_where, $str_sql);
        } else {
            $str_where = '';
            $str_table = '';
            $str_sql = str_replace('{table_list}', $str_table, $str_sql);
            $str_sql = str_replace('{tantousya_list1}', $str_where, $str_sql);
        }

        $str_where = '';

        $orderby = '';
        //--- 20190228 ci UPD S
        if ('1' == $con4 or '2' == $con4 or '3' == $con4) {
            //--- 20190228 ci UPD E
            $orderby .= '  ORDER BY ';
            $orderby .= '  XH10CAID,FRGMH';
            $orderby .= '  ,VIN_WMIVDS ';
            $orderby .= '  ,VIN_VIS  ';
        }
        //----20220121 sun add s
        elseif ('4' == $con4) {
            $orderby .= '  ORDER BY ';
            //20220519 YIN UPD S
            // $orderby .= "  TMN.DISP_SEQ";
            $orderby .= '  TMSEQ.DISP_SEQ';
            //20220519 YIN UPD E
            $orderby .= '  ,XH10CAID,VCLIPEDT';
            $orderby .= '  ,VIN_WMIVDS ';
            $orderby .= '  ,VIN_VIS  ';
        }
        //----20220121 sun add e
        else {
            $orderby .= '  ORDER BY ';
            $orderby .= '  XH10CAID,VCLIPEDT';
            $orderby .= '  ,VIN_WMIVDS ';
            $orderby .= '  ,VIN_VIS  ';
        }

        $orderbyKATU = '';
        $orderbyKATU .= '  ORDER BY ';
        $orderbyKATU .= '  DISP_SEQ_TM1 ';
        $orderbyKATU .= '  ,VIN_WMIVDS ';
        $orderbyKATU .= '  ,VIN_VIS  ';

        $orderbySAISYU = '';
        //--- 20160301 li UPD S
        // $orderbySAISYU .= "  ORDER BY ";
        // $orderbySAISYU .= "  DISP_SEQ_TM2 ";
        // $orderbySAISYU .= "  ,VIN_WMIVDS ";
        // $orderbySAISYU .= "  ,VIN_VIS  ";
        if ('1' == $con4) {
            $orderbySAISYU .= '  ORDER BY ';
            $orderbySAISYU .= '   FRGMH DESC, ';

            $orderbySAISYU .= '  DISP_SEQ_TM7 ';
            $orderbySAISYU .= '  ,VIN_WMIVDS ';
            $orderbySAISYU .= '  ,VIN_VIS  ';
        } elseif ('2' == $con4) {
            $orderbySAISYU .= '  ORDER BY ';
            $orderbySAISYU .= '   FRGMH DESC, ';

            $orderbySAISYU .= '  DISP_SEQ_TMK ';
            $orderbySAISYU .= '  ,VIN_WMIVDS ';
            $orderbySAISYU .= '  ,VIN_VIS  ';
        }
        //--- 20190305 ci INS S
        elseif ('3' == $con4) {
            $orderbySAISYU .= '  ORDER BY ';
            $orderbySAISYU .= '   FRGMH DESC, ';

            $orderbySAISYU .= '  DISP_SEQ_TMK ';
            $orderbySAISYU .= '  ,VIN_WMIVDS ';
            $orderbySAISYU .= '  ,VIN_VIS  ';
        }
        //--- 20190305 ci INS E
        elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $orderbySAISYU .= '  ORDER BY ';
            $orderbySAISYU .= '  DISP_SEQ_TM2 ';
            $orderbySAISYU .= '  ,VIN_WMIVDS ';
            $orderbySAISYU .= '  ,VIN_VIS  ';
        }
        //--- 20160301 li UPD E

        $conbineSAISYU = '';
        //        $conbineSAISYU .= "  AND ";
        //--- 20160127 li UPD S
        // $conbineSAISYU .= " HANTEILST.KEKKA_CD=TM2.TEIKEI_CD(+)  ";
        if ('1' == $con4 or '2' == $con4) {
            //			$conbineSAISYU .= " HANTEILST_SINSYA.KEKKA_CD=TM2.TEIKEI_CD(+)  ";
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $conbineSAISYU .= '  AND ';
            $conbineSAISYU .= ' HANTEILST.KEKKA_CD=TM2.TEIKEI_CD(+)  ';
        }
        //--- 20160127 li UPD E
        //        $conbineSAISYU .= "  AND ";
        //--- 20160127 li UPD S
        // $conbineSAISYU .= " '2'=TM2.TEIKEI_TYPE(+)  ";
        if ('1' == $con4 or '2' == $con4) {
            //			$conbineSAISYU .= " '" . $strType . "' =TM2.TEIKEI_TYPE(+)  ";
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $conbineSAISYU .= '  AND ';
            $conbineSAISYU .= " '2'=TM2.TEIKEI_TYPE(+)  ";
        }
        //--- 20160127 li UPD E

        $conbineKATU = '';
        $conbineKATU .= ' AND ';
        //--- 20160127 li UPD S
        // $conbineKATU .= " CASE WHEN rtrim(HANTEILST.HANTEI7_CD) IS NOT NULL THEN  HANTEILST.HANTEI7_CD ";
        // $conbineKATU .= " WHEN rtrim(HANTEILST.HANTEI6_CD) IS NOT NULL THEN  HANTEILST.HANTEI6_CD ";
        // $conbineKATU .= " WHEN rtrim(HANTEILST.HANTEI5_CD) IS NOT NULL THEN  HANTEILST.HANTEI5_CD ";
        // $conbineKATU .= " WHEN rtrim(HANTEILST.HANTEI4_CD) IS NOT NULL THEN  HANTEILST.HANTEI4_CD ";
        // $conbineKATU .= " WHEN rtrim(HANTEILST.HANTEI3_CD) IS NOT NULL THEN  HANTEILST.HANTEI3_CD ";
        // $conbineKATU .= " WHEN rtrim(HANTEILST.HANTEI2_CD) IS NOT NULL THEN  HANTEILST.HANTEI2_CD ";
        // $conbineKATU .= " WHEN rtrim(HANTEILST.HANTEI1_CD) IS NOT NULL THEN  HANTEILST.HANTEI1_CD ";

        if ('1' == $con4 or '2' == $con4) {
            $conbineKATU .= ' CASE WHEN rtrim(HANTEILST_SINSYA.HANTEI7_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI7_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI6_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI6_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI5_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI5_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI4_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI4_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI3_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI3_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI2_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI2_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI1_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI1_CD ';
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $conbineKATU .= ' CASE WHEN rtrim(HANTEILST.HANTEI7_CD) IS NOT NULL THEN  HANTEILST.HANTEI7_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST.HANTEI6_CD) IS NOT NULL THEN  HANTEILST.HANTEI6_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST.HANTEI5_CD) IS NOT NULL THEN  HANTEILST.HANTEI5_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST.HANTEI4_CD) IS NOT NULL THEN  HANTEILST.HANTEI4_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST.HANTEI3_CD) IS NOT NULL THEN  HANTEILST.HANTEI3_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST.HANTEI2_CD) IS NOT NULL THEN  HANTEILST.HANTEI2_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST.HANTEI1_CD) IS NOT NULL THEN  HANTEILST.HANTEI1_CD ';
        }
        //--- 20160127 li UPD E
        $conbineKATU .= " ELSE 'ZZZZZ' ";
        $conbineKATU .= ' END  = TM1.TEIKEI_CD(+)';
        $conbineKATU .= ' AND ';
        //--- 20160127 li UPD S
        // $conbineKATU .= " '1' = TM1.TEIKEI_TYPE(+)";
        //--- 20190228 ci UPD S
        if ('1' == $con4 or '2' == $con4 or '3' == $con4) {
            //--- 20190228 ci UPD E
            $conbineKATU .= " '" . $strType . "' = TM1.TEIKEI_TYPE(+)";
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $conbineKATU .= " '1' = TM1.TEIKEI_TYPE(+)";
        }
        //--- 20160127 li UPD E

        $selectKATU = '';
        //--- 20160301 li UPD S
        // $selectKATU .= "  NVL(TM1.DISP_SEQ,999) AS DISP_SEQ_TM1, ";
        // $selectSAISYU = "";
        // $selectSAISYU .= "  NVL(TM2.DISP_SEQ,999) AS DISP_SEQ_TM2, ";
        if ('1' == $con4) {
            $selectKATU .= '  NVL(TM7.DISP_SEQ,999) AS DISP_SEQ_TM7, ';
            $selectSAISYU = '';
            $selectSAISYU .= '  NVL(TM7.DISP_SEQ,999) AS DISP_SEQ_TM7, ';
        } elseif ('2' == $con4) {
            $selectKATU .= '  NVL(TMK.DISP_SEQ,999) AS DISP_SEQ_TMK, ';
            $selectSAISYU = '';
            $selectSAISYU .= '  NVL(TMK.DISP_SEQ,999) AS DISP_SEQ_TMK, ';
        }
        //--- 20190305 ci INS S
        elseif ('3' == $con4) {
            $selectKATU .= '  NVL(TMK.DISP_SEQ,999) AS DISP_SEQ_TMK, ';
            $selectSAISYU = '';
            $selectSAISYU .= '  NVL(TMK.DISP_SEQ,999) AS DISP_SEQ_TMK, ';
        }
        //--- 20190305 ci INS E
        elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $selectKATU .= '  NVL(TM1.DISP_SEQ,999) AS DISP_SEQ_TM1, ';
            $selectSAISYU = '';
            $selectSAISYU .= '  NVL(TM2.DISP_SEQ,999) AS DISP_SEQ_TM2, ';
        }
        //--- 20160301 li UPD E

        $tableKATU = ' HANTEITEIKEIMST TM1, ';
        $tableSAISYU = ' HANTEITEIKEIMST TM2, ';

        //----20220121 sun upd s
        //$str = substr($con1, 0, 2);
        $str = '';
        if (false !== strpos($con1, ',')) {
            $exp = explode(',', $con1);
            for ($i = 0; $i < count($exp); ++$i) {
                $str .= "'" . substr($exp[$i], 0, 2) . "',";
            }
            $str = substr($str, 0, strlen($str) - 1);
        } else {
            $str = "'" . substr($con1, 0, 2) . "'";
        }
        //----20220121 sun upd e

        $whereKATU = '';
        $whereKATU .= ' AND ';
        $whereKATU .= ' SUBSTR( ';
        //--- 20160127 li UPD S
        // $whereKATU .= " CASE WHEN rtrim(HANTEILST.HANTEI7_CD) IS NOT NULL THEN  HANTEILST.HANTEI7_CD ";
        // $whereKATU .= " WHEN rtrim(HANTEILST.HANTEI6_CD) IS NOT NULL THEN  HANTEILST.HANTEI6_CD ";
        // $whereKATU .= " WHEN rtrim(HANTEILST.HANTEI5_CD) IS NOT NULL THEN  HANTEILST.HANTEI5_CD ";
        // $whereKATU .= " WHEN rtrim(HANTEILST.HANTEI4_CD) IS NOT NULL THEN  HANTEILST.HANTEI4_CD ";
        // $whereKATU .= " WHEN rtrim(HANTEILST.HANTEI3_CD) IS NOT NULL THEN  HANTEILST.HANTEI3_CD ";
        // $whereKATU .= " WHEN rtrim(HANTEILST.HANTEI2_CD) IS NOT NULL THEN  HANTEILST.HANTEI2_CD ";
        // $whereKATU .= " WHEN rtrim(HANTEILST.HANTEI1_CD) IS NOT NULL THEN  HANTEILST.HANTEI1_CD ";
        if ('1' == $con4 or '2' == $con4) {
            $whereKATU .= ' CASE WHEN rtrim(HANTEILST_SINSYA.HANTEI7_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI7_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI6_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI6_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI5_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI5_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI4_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI4_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI3_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI3_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI2_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI2_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI1_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI1_CD ';
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $whereKATU .= ' CASE WHEN rtrim(HANTEILST.HANTEI7_CD) IS NOT NULL THEN  HANTEILST.HANTEI7_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST.HANTEI6_CD) IS NOT NULL THEN  HANTEILST.HANTEI6_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST.HANTEI5_CD) IS NOT NULL THEN  HANTEILST.HANTEI5_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST.HANTEI4_CD) IS NOT NULL THEN  HANTEILST.HANTEI4_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST.HANTEI3_CD) IS NOT NULL THEN  HANTEILST.HANTEI3_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST.HANTEI2_CD) IS NOT NULL THEN  HANTEILST.HANTEI2_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST.HANTEI1_CD) IS NOT NULL THEN  HANTEILST.HANTEI1_CD ';
        }
        //--- 20160127 li UPD E
        $whereKATU .= " ELSE 'ZZZZZ' ";
        $whereKATU .= ' END ';
        //----20220121 sun upd s
        //$whereKATU .= " ,0,2) ='" . $str . "' ";
        $whereKATU .= ' ,0,2) IN (' . $str . ') ';
        //----20220121 sun upd e

        $str = substr($con2, 0, 2);
        $whereSAI = '';

        //--- 20160127 li UPD S
        // $whereSAI .= " SUBSTR(HANTEILST.KEKKA_CD ,0,2) = '" . $str . "' ";
        // $whereSAI .= " AND ";
        if ('1' == $con4 && '999' != $con2) {
            if ('998' == $con2) {
                $whereSAI .= " AND nvl(HANTEILST_SINSYA.HANTEI7_CD,' ') not in (select TEIKEI_CD from HANTEITEIKEIMST where  ITEMNAME1 like '入庫済%' and TEIKEI_TYPE = '3') ";
            } else {
                $whereSAI .= " AND SUBSTR(HANTEILST_SINSYA.HANTEI7_CD ,0,2) = '" . $str . "' ";
            }
        } elseif ('2' == $con4 && '999' != $con2) {
            if ('998' == $con2) {
                $whereSAI .= " AND nvl(HANTEILST_SINSYA.KEKKA_CD,' ') not in (select TEIKEI_CD from HANTEITEIKEIMST where  ITEMNAME1 like '入庫済%' and TEIKEI_TYPE = '4') ";
            } else {
                $whereSAI .= " AND SUBSTR(HANTEILST_SINSYA.KEKKA_CD ,0,2) = '" . $str . "' ";
            }
        }
        //--- 20190228 ci INS S
        elseif ('3' == $con4 && '999' != $con2) {
            if ('998' == $con2) {
                $whereSAI .= " AND nvl(HANTEILST_CHUKO.KEKKA1_CD,' ') not in (select TEIKEI_CD from HANTEITEIKEIMST where  ITEMNAME1 like '入庫済%' and TEIKEI_TYPE = '5') ";
            } else {
                $whereSAI .= " AND SUBSTR(HANTEILST_CHUKO.KEKKA_CD ,0,2) = '" . $str . "' ";
            }
        }
        //--- 20190228 ci INS E
        elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $whereSAI .= " AND SUBSTR(HANTEILST.KEKKA_CD ,0,2) = '" . $str . "' ";
        }
        //--- 20160127 li UPD E

        //並び順指定
        //--- 20160301 li UPD S
        // if ($con3 == "0") {
        // //車両区分順
        // if ($con1 != '000' && $con2 != '000') {
        // $str_sql = str_replace("{condition_list}", $whereKATU . $whereSAI, $str_sql);
        // $str_sql = str_replace("{conbine_list}", $conbineKATU . $conbineSAISYU, $str_sql);
        // $str_sql = str_replace("{table_order}", $tableKATU . $tableSAISYU, $str_sql);
        // } else {
        // //--- 20160127 li UPD S
        // // if ($con1 == '000' && $con2 == '000') {
        // if ($con1 == '000' && ($con2 == '000' || $con2 == '999')) {
        // //--- 20160127 li UPD E
        // $str_sql = str_replace("{conbine_list}", "", $str_sql);
        // $str_sql = str_replace("{condition_list}", "", $str_sql);
        // $str_sql = str_replace("{table_order}", "", $str_sql);
        // } elseif ($con1 != '000' && $con2 == '000') {
        // $str_sql = str_replace("{conbine_list}", $whereKATU, $str_sql);
        // $str_sql = str_replace("{condition_list}", $conbineKATU, $str_sql);
        // $str_sql = str_replace("{table_order}", $tableKATU, $str_sql);
        // } else {
        // $str_sql = str_replace("{conbine_list}", $whereSAI, $str_sql);
        // $str_sql = str_replace("{condition_list}", $conbineSAISYU, $str_sql);
        // $str_sql = str_replace("{table_order}", $tableSAISYU, $str_sql);
        // }
        // }
        //
        // $str_sql = str_replace("{select_list}", "", $str_sql);
        // $str_sql = str_replace("{orderby}", $orderby, $str_sql);
        //
        // } elseif ($con3 == "1") {
        // //活動状況順
        // if ($con1 != '000' && $con2 != '000') {
        // $str_sql = str_replace("{condition_list}", $whereKATU . $whereSAI, $str_sql);
        // $str_sql = str_replace("{conbine_list}", $conbineKATU . $conbineSAISYU, $str_sql);
        // $str_sql = str_replace("{table_order}", $tableKATU . $tableSAISYU, $str_sql);
        // } else {
        // //--- 20160127 li UPD S
        // // if ($con1 == '000' && $con2 == '000') {
        // if ($con1 == '000' && ($con2 == '000' || $con2 == '999')) {
        // //--- 20160127 li UPD E
        // $str_sql = str_replace("{conbine_list}", $conbineKATU, $str_sql);
        // $str_sql = str_replace("{condition_list}", "", $str_sql);
        // $str_sql = str_replace("{table_order}", $tableKATU, $str_sql);
        // } elseif ($con1 != '000' && $con2 == '000') {
        // $str_sql = str_replace("{conbine_list}", $conbineKATU, $str_sql);
        // $str_sql = str_replace("{condition_list}", $whereKATU, $str_sql);
        // $str_sql = str_replace("{table_order}", $tableKATU, $str_sql);
        // } else {
        // $str_sql = str_replace("{conbine_list}", $conbineKATU . $conbineSAISYU, $str_sql);
        // $str_sql = str_replace("{condition_list}", $whereSAI, $str_sql);
        // $str_sql = str_replace("{table_order}", $tableKATU . $tableSAISYU, $str_sql);
        // }
        // }
        //
        // $str_sql = str_replace("{select_list}", $selectKATU, $str_sql);
        // $str_sql = str_replace("{orderby}", $orderbyKATU, $str_sql);
        // } else {
        // //最終結果順
        // if ($con1 != '000' && $con2 != '000') {
        // $str_sql = str_replace("{condition_list}", $whereKATU . $whereSAI, $str_sql);
        // $str_sql = str_replace("{conbine_list}", $conbineKATU . $conbineSAISYU, $str_sql);
        // $str_sql = str_replace("{table_order}", $tableKATU . $tableSAISYU, $str_sql);
        // } else {
        // //--- 20160127 li UPD S
        // // if ($con1 == '000' && $con2 == '000') {
        // if ($con1 == '000' && ($con2 == '000' || $con2 == '999')) {
        // //--- 20160127 li UPD E
        // $str_sql = str_replace("{conbine_list}", $conbineSAISYU, $str_sql);
        // $str_sql = str_replace("{condition_list}", "", $str_sql);
        // $str_sql = str_replace("{table_order}", $tableSAISYU, $str_sql);
        // } elseif ($con1 != '000' && $con2 == '000') {
        // $str_sql = str_replace("{conbine_list}", $conbineKATU . $conbineSAISYU, $str_sql);
        // $str_sql = str_replace("{condition_list}", $whereKATU, $str_sql);
        // $str_sql = str_replace("{table_order}", $tableKATU . $tableSAISYU, $str_sql);
        // } else {
        // $str_sql = str_replace("{conbine_list}", $whereSAI, $str_sql);
        // $str_sql = str_replace("{condition_list}", $conbineSAISYU, $str_sql);
        // $str_sql = str_replace("{table_order}", $tableSAISYU, $str_sql);
        // }
        // }
        //
        // $str_sql = str_replace("{select_list}", $selectSAISYU, $str_sql);
        // $str_sql = str_replace("{orderby}", $orderbySAISYU, $str_sql);
        // }

        //----20220121 sun upd s
        //if ($con4 == "0")
        if ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            if ('0' == $con3) {
                //車両区分順
                if ('000' != $con1 && '000' != $con2) {
                    $str_sql = str_replace('{condition_list}', $whereKATU . $whereSAI, $str_sql);
                    $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                    $str_sql = str_replace('{table_order}', $tableKATU . $tableSAISYU, $str_sql);
                } else {
                    if ('000' == $con1 && '000' == $con2) {
                        $str_sql = str_replace('{conbine_list}', '', $str_sql);
                        $str_sql = str_replace('{condition_list}', '', $str_sql);
                        $str_sql = str_replace('{table_order}', '', $str_sql);
                    } elseif ('000' != $con1 && '000' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $whereKATU, $str_sql);
                        $str_sql = str_replace('{condition_list}', $conbineKATU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                    } else {
                        $str_sql = str_replace('{conbine_list}', $whereSAI, $str_sql);
                        $str_sql = str_replace('{condition_list}', $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableSAISYU, $str_sql);
                    }
                }

                $str_sql = str_replace('{select_list}', '', $str_sql);
                $str_sql = str_replace('{orderby}', $orderby, $str_sql);
            } elseif ('1' == $con3) {
                //活動状況順
                if ('000' != $con1 && '000' != $con2) {
                    $str_sql = str_replace('{condition_list}', $whereKATU . $whereSAI, $str_sql);
                    $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                    $str_sql = str_replace('{table_order}', $tableKATU . $tableSAISYU, $str_sql);
                } else {
                    if ('000' == $con1 && '000' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $conbineKATU, $str_sql);
                        $str_sql = str_replace('{condition_list}', '', $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                    } elseif ('000' != $con1 && '000' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $conbineKATU, $str_sql);
                        $str_sql = str_replace('{condition_list}', $whereKATU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                    } else {
                        $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{condition_list}', $whereSAI, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU . $tableSAISYU, $str_sql);
                    }
                }

                $str_sql = str_replace('{select_list}', $selectKATU, $str_sql);
                $str_sql = str_replace('{orderby}', $orderbyKATU, $str_sql);
            } else {
                //最終結果順
                if ('000' != $con1 && '000' != $con2) {
                    $str_sql = str_replace('{condition_list}', $whereKATU . $whereSAI, $str_sql);
                    $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                    $str_sql = str_replace('{table_order}', $tableKATU . $tableSAISYU, $str_sql);
                } else {
                    if ('000' == $con1 && '000' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{condition_list}', '', $str_sql);
                        $str_sql = str_replace('{table_order}', $tableSAISYU, $str_sql);
                    } elseif ('000' != $con1 && '000' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{condition_list}', $whereKATU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU . $tableSAISYU, $str_sql);
                    } else {
                        $str_sql = str_replace('{conbine_list}', $whereSAI, $str_sql);
                        $str_sql = str_replace('{condition_list}', $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableSAISYU, $str_sql);
                    }
                }

                $str_sql = str_replace('{select_list}', $selectSAISYU, $str_sql);
                $str_sql = str_replace('{orderby}', $orderbySAISYU, $str_sql);
            }
        } elseif //--- 20190227 ci UPD S
        ('1' == $con4 or '2' == $con4 or '3' == $con4) {
            //--- 20190227 ci UPD E
            if ('0' == $con3) {
                //車両区分順
                if ('000' != $con1 && '999' != $con2) {
                    $str_sql = str_replace('{condition_list}', $whereKATU . $whereSAI, $str_sql);
                    $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                    //                $str_sql = str_replace("{table_order}", $tableKATU . $tableSAISYU, $str_sql);
                    $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                } else {
                    if ('000' == $con1 && '999' == $con2) {
                        $str_sql = str_replace('{conbine_list}', '', $str_sql);
                        $str_sql = str_replace('{condition_list}', '', $str_sql);
                        $str_sql = str_replace('{table_order}', '', $str_sql);
                    } elseif ('000' != $con1 && '999' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $whereKATU, $str_sql);
                        $str_sql = str_replace('{condition_list}', $conbineKATU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                    } else {
                        $str_sql = str_replace('{conbine_list}', $whereSAI, $str_sql);
                        $str_sql = str_replace('{condition_list}', $conbineSAISYU, $str_sql);
                        //$str_sql = str_replace("{table_order}", $tableSAISYU, $str_sql);
                        $str_sql = str_replace('{table_order}', '', $str_sql);
                    }
                }

                $str_sql = str_replace('{select_list}', '', $str_sql);
                $str_sql = str_replace('{orderby}', $orderby, $str_sql);
            } elseif ('1' == $con3) {
                //活動状況順
                if ('000' != $con1 && '999' != $con2) {
                    $str_sql = str_replace('{condition_list}', $whereKATU . $whereSAI, $str_sql);
                    $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                    //                $str_sql = str_replace("{table_order}", $tableKATU . $tableSAISYU, $str_sql);
                    $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                } else {
                    if ('000' == $con1 && '999' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $conbineKATU, $str_sql);
                        $str_sql = str_replace('{condition_list}', '', $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                    } elseif ('000' != $con1 && '999' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $conbineKATU, $str_sql);
                        $str_sql = str_replace('{condition_list}', $whereKATU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                    } else {
                        $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{condition_list}', $whereSAI, $str_sql);
                        //                    $str_sql = str_replace("{table_order}", $tableKATU . $tableSAISYU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                    }
                }

                $str_sql = str_replace('{select_list}', $selectKATU, $str_sql);
                $str_sql = str_replace('{orderby}', $orderbyKATU, $str_sql);
            } else {
                //最終結果順
                if ('000' != $con1 && '999' != $con2) {
                    $str_sql = str_replace('{condition_list}', $whereKATU . $whereSAI, $str_sql);
                    $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                    //                $str_sql = str_replace("{table_order}", $tableKATU . $tableSAISYU, $str_sql);
                    $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                } else {
                    if ('000' == $con1 && '999' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{condition_list}', '', $str_sql);
                        //20190305 CI INS S
                        $str_sql = str_replace('{table_order}', '', $str_sql);
                        //20190305 CI INS S
                    } elseif ('000' != $con1 && '999' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{condition_list}', $whereKATU, $str_sql);
                        //                    $str_sql = str_replace("{table_order}", $tableKATU . $tableSAISYU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                    } else {
                        $str_sql = str_replace('{conbine_list}', $whereSAI, $str_sql);
                        $str_sql = str_replace('{condition_list}', $conbineSAISYU, $str_sql);
                        //$str_sql = str_replace("{table_order}", $tableSAISYU, $str_sql);
                        $str_sql = str_replace('{table_order}', '', $str_sql);
                    }
                }

                $str_sql = str_replace('{select_list}', $selectSAISYU, $str_sql);
                $str_sql = str_replace('{orderby}', $orderbySAISYU, $str_sql);
            }
        }

        //--- 20160301 li UPD E
        return $str_sql;
    }

    /**
     * 営業担当者 を指定している場合.
     *
     * @param {String} $tenpo_code      管理チームコード
     * @param {String} $syaken_manryobi 車検満了日ORDE
     *
     * @return {String} select文
     */
    //--- 20160127 li UPD S
    // public function m_select_sdh01_08_sql_tantousya($tantousya_code, $syaken_manryobi, $con, $con1, $con2, $con3) {
    public function m_select_sdh01_08_sql_tantousya($tantousya_code, $syaken_manryobi, $con, $con1, $con2, $con3, $con4)
    {
        $strType = '';
        if ('1' == $con4) {
            $strType = '3';
        }
        if ('2' == $con4) {
            $strType = '4';
        }
        //--- 20190228 ci INS S
        if ('3' == $con4) {
            $strType = '5';
        }
        //--- 20190228 ci INS E
        //--- 20160127 li UPD E
        $str_sql = '';
        //--- 20160127 li UPD S
        // $str_sql = $this -> m_select_sdh01_08_sql($syaken_manryobi);
        $str_sql = $this->m_select_sdh01_08_sql($syaken_manryobi, $con4);
        //--- 20160127 li UPD E
        $str_where = '';
        $str_where .= 'AND ';
        //営業担当者コード s
        $str_where .= "  M41C04.KNR_BUSMANCD = '" . $tantousya_code . "' ";

        //営業担当者コード e
        $str_sql = str_replace('{tantousya_list}', $str_where, $str_sql);

        //要注意リスト抽出用
        if ('1' == $con) {
            $str_where = '';
            $str_table = '';
            $str_where .= 'AND ';
            $str_where .= 'M41C04.B1_KATANCD IS NOT NULL ';
            $str_where .= 'AND ';
            $str_where .= 'M41C04.B2_KATANCD IS NOT NULL ';
            $str_where .= 'AND ';
            $str_where .= 'M41C04.B3_KATANCD IS NOT NULL ';
            $str_sql = str_replace('{table_list}', $str_table, $str_sql);
            $str_sql = str_replace('{tantousya_list1}', $str_where, $str_sql);
        } elseif ('2' == $con) {
            $str_where = '';
            $str_table = '';
            $str_where .= 'AND ';
            $str_where .= 'M41C04.KNR_BUSMANCD = HHAIZOKU.SYAIN_NO ';
            $str_where .= 'AND ';
            $str_where .= 'M41C04.KNR_STRCD <> HHAIZOKU.BUSYO_CD ';
            $str_where .= 'AND ';
            $str_where .= 'HHAIZOKU.END_DATE IS NULL ';
            $str_table = ' HHAIZOKU,';
            $str_sql = str_replace('{table_list}', $str_table, $str_sql);
            $str_sql = str_replace('{tantousya_list1}', $str_where, $str_sql);
        } elseif ('3' == $con) {
            $str_where = '';
            $str_table = '';
            $str_where .= 'AND ';
            $str_where .= 'M41C04.KNR_BUSMANCD = BTH29MA4.SYAIN_NO ';
            $str_where .= 'AND ';
            $str_where .= "'3634'              = BTH29MA4.HANSH_CD ";
            $str_where .= 'AND ';
            $str_where .= 'BTH29MA4.RISYOKU_DATE IS NOT NULL ';
            $str_table = ' BTH29MA4,';
            $str_sql = str_replace('{table_list}', $str_table, $str_sql);
            $str_sql = str_replace('{tantousya_list1}', $str_where, $str_sql);
        } else {
            $str_where = '';
            $str_table = '';
            $str_sql = str_replace('{table_list}', $str_table, $str_sql);
            $str_sql = str_replace('{tantousya_list1}', $str_where, $str_sql);
        }

        $str_where = '';

        $orderby = '';
        $orderby .= '  ORDER BY ';
        //--- 20190228 ci UPD S
        if ('1' == $con4 or '2' == $con4 or '3' == $con4) {
            //--- 20190228 ci UPD E
            $orderby .= '  XH10CAID,FRGMH DESC';
            $orderby .= '  ,VIN_WMIVDS ';
            $orderby .= '  ,VIN_VIS  ';
        }
        //----20220121 sun add s
        elseif ('4' == $con4) {
            //20220519 YIN UPD S
            // $orderby .= "  TMN.DISP_SEQ";
            $orderby .= '  TMSEQ.DISP_SEQ';
            //20220519 YIN UPD E
            $orderby .= '  ,XH10CAID,VCLIPEDT';
            $orderby .= '  ,VIN_WMIVDS ';
            $orderby .= '  ,VIN_VIS  ';
        }
        //----20220121 sun add e
        else {
            $orderby .= '  XH10CAID,VCLIPEDT';
            $orderby .= '  ,VIN_WMIVDS ';
            $orderby .= '  ,VIN_VIS  ';
        }

        $orderbyKATU = '';
        $orderbyKATU .= '  ORDER BY ';
        $orderbyKATU .= '  DISP_SEQ_TM1 ';
        $orderbyKATU .= '  ,VIN_WMIVDS ';
        $orderbyKATU .= '  ,VIN_VIS  ';

        $orderbySAISYU = '';
        //--- 20160301 li UPD S
        // $orderbySAISYU .= "  ORDER BY ";
        // $orderbySAISYU .= "  DISP_SEQ_TM2 ";
        // $orderbySAISYU .= "  ,VIN_WMIVDS ";
        // $orderbySAISYU .= "  ,VIN_VIS  ";

        if ('1' == $con4) {
            $orderbySAISYU .= '  ORDER BY ';
            $orderbySAISYU .= '  DISP_SEQ_TM7 ';
            $orderbySAISYU .= '  ,FRGMH DESC ';
            $orderbySAISYU .= '  ,VIN_WMIVDS ';
            $orderbySAISYU .= '  ,VIN_VIS  ';
        } elseif ('2' == $con4) {
            $orderbySAISYU .= '  ORDER BY ';
            $orderbySAISYU .= '  DISP_SEQ_TMK ';
            $orderbySAISYU .= '  ,FRGMH DESC ';
            $orderbySAISYU .= '  ,VIN_WMIVDS ';
            $orderbySAISYU .= '  ,VIN_VIS  ';
        }
        //--- 20190305 ci INS S
        elseif ('3' == $con4) {
            $orderbySAISYU .= '  ORDER BY ';
            $orderbySAISYU .= '  DISP_SEQ_TMK ';
            $orderbySAISYU .= '  ,FRGMH DESC ';
            $orderbySAISYU .= '  ,VIN_WMIVDS ';
            $orderbySAISYU .= '  ,VIN_VIS  ';
        }
        //--- 20190305 ci INS S
        elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $orderbySAISYU .= '  ORDER BY ';
            $orderbySAISYU .= '  DISP_SEQ_TM2 ';
            $orderbySAISYU .= '  ,VIN_WMIVDS ';
            $orderbySAISYU .= '  ,VIN_VIS  ';
        }
        //--- 20160301 li UPD E

        $conbineSAISYU = '';
        $conbineSAISYU .= '  AND ';
        //--- 20160127 li UPD S
        // $conbineSAISYU .= " HANTEILST.KEKKA_CD=TM2.TEIKEI_CD(+)  ";
        if ('1' == $con4 or '2' == $con4) {
            $conbineSAISYU .= ' HANTEILST_SINSYA.KEKKA_CD=TM2.TEIKEI_CD(+)  ';
        }
        //--- 20190228 ci INS S
        elseif ('3' == $con4) {
            $conbineSAISYU .= ' HANTEILST_CHUKO.KEKKA_CD=TMK.TEIKEI_CD(+)  ';
        }
        //--- 20190228 ci INS E
        elseif ('0' == $con4) {
            $conbineSAISYU .= ' HANTEILST.KEKKA_CD=TM2.TEIKEI_CD(+)  ';
        }
        //--- 20160127 li UPD E
        $conbineSAISYU .= '  AND ';
        //--- 20160127 li UPD S
        // $conbineSAISYU .= " '2'=TM2.TEIKEI_TYPE(+)  ";
        if ('1' == $con4 or '2' == $con4) {
            $conbineSAISYU .= " '" . $strType . "' =TM2.TEIKEI_TYPE(+)  ";
        }
        //--- 20190228 ci INS S
        elseif ('3' == $con4) {
            $conbineSAISYU .= " '" . $strType . "' =TMK.TEIKEI_TYPE(+)  ";
        }
        //--- 20190228 ci INS E
        elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $conbineSAISYU .= " '2'=TM2.TEIKEI_TYPE(+)  ";
        }
        //--- 20160127 li UPD E

        $conbineKATU = '';
        $conbineKATU .= ' AND ';
        //--- 20160127 li UPD S
        // $conbineKATU .= " CASE WHEN rtrim(HANTEILST.HANTEI7_CD) IS NOT NULL THEN  HANTEILST.HANTEI7_CD ";
        // $conbineKATU .= " WHEN rtrim(HANTEILST.HANTEI6_CD) IS NOT NULL THEN  HANTEILST.HANTEI6_CD ";
        // $conbineKATU .= " WHEN rtrim(HANTEILST.HANTEI5_CD) IS NOT NULL THEN  HANTEILST.HANTEI5_CD ";
        // $conbineKATU .= " WHEN rtrim(HANTEILST.HANTEI4_CD) IS NOT NULL THEN  HANTEILST.HANTEI4_CD ";
        // $conbineKATU .= " WHEN rtrim(HANTEILST.HANTEI3_CD) IS NOT NULL THEN  HANTEILST.HANTEI3_CD ";
        // $conbineKATU .= " WHEN rtrim(HANTEILST.HANTEI2_CD) IS NOT NULL THEN  HANTEILST.HANTEI2_CD ";
        // $conbineKATU .= " WHEN rtrim(HANTEILST.HANTEI1_CD) IS NOT NULL THEN  HANTEILST.HANTEI1_CD ";
        if ('1' == $con4 or '2' == $con4) {
            $conbineKATU .= ' CASE WHEN rtrim(HANTEILST_SINSYA.HANTEI7_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI7_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI6_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI6_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI5_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI5_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI4_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI4_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI3_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI3_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI2_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI2_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI1_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI1_CD ';
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $conbineKATU .= ' CASE WHEN rtrim(HANTEILST.HANTEI7_CD) IS NOT NULL THEN  HANTEILST.HANTEI7_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST.HANTEI6_CD) IS NOT NULL THEN  HANTEILST.HANTEI6_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST.HANTEI5_CD) IS NOT NULL THEN  HANTEILST.HANTEI5_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST.HANTEI4_CD) IS NOT NULL THEN  HANTEILST.HANTEI4_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST.HANTEI3_CD) IS NOT NULL THEN  HANTEILST.HANTEI3_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST.HANTEI2_CD) IS NOT NULL THEN  HANTEILST.HANTEI2_CD ';
            $conbineKATU .= ' WHEN rtrim(HANTEILST.HANTEI1_CD) IS NOT NULL THEN  HANTEILST.HANTEI1_CD ';
        }
        //--- 20160127 li UPD E
        $conbineKATU .= " ELSE 'ZZZZZ' ";
        $conbineKATU .= ' END  = TM1.TEIKEI_CD(+)';
        $conbineKATU .= ' AND ';
        //--- 20160127 li UPD S
        // $conbineKATU .= " '1' = TM1.TEIKEI_TYPE(+)";
        //--- 20190228 ci UPD S
        if ('1' == $con4 or '2' == $con4 or '3' == $con4) {
            //--- 20190228 ci UPD E
            $conbineKATU .= " '" . $strType . "' = TM1.TEIKEI_TYPE(+)";
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $conbineKATU .= " '1' = TM1.TEIKEI_TYPE(+)";
        }
        //--- 20160127 li UPD E

        $selectKATU = '';
        //--- 20160301 li UPD S

        // $selectKATU .= "  NVL(TM1.DISP_SEQ,999) AS DISP_SEQ_TM1, ";
        // $selectSAISYU = "";
        // $selectSAISYU .= "  NVL(TM2.DISP_SEQ,999) AS DISP_SEQ_TM2, ";
        if ('1' == $con4) {
            $selectKATU .= '  NVL(TM7.DISP_SEQ,999) AS DISP_SEQ_TM7, ';
            $selectSAISYU = '';
            $selectSAISYU .= '  NVL(TM7.DISP_SEQ,999) AS DISP_SEQ_TM7, ';
        } elseif ('2' == $con4) {
            $selectKATU .= '  NVL(TMK.DISP_SEQ,999) AS DISP_SEQ_TMK, ';
            $selectSAISYU = '';
            $selectSAISYU .= '  NVL(TMK.DISP_SEQ,999) AS DISP_SEQ_TMK, ';
        }
        //--- 20190305 ci INS S
        elseif ('3' == $con4) {
            $selectKATU .= '  NVL(TMK.DISP_SEQ,999) AS DISP_SEQ_TMK, ';
            $selectSAISYU = '';
            $selectSAISYU .= '  NVL(TMK.DISP_SEQ,999) AS DISP_SEQ_TMK, ';
        }
        //--- 20190305 ci INS E
        elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $selectKATU .= '  NVL(TM1.DISP_SEQ,999) AS DISP_SEQ_TM1, ';
            $selectSAISYU = '';
            $selectSAISYU .= '  NVL(TM2.DISP_SEQ,999) AS DISP_SEQ_TM2, ';
        }
        //--- 20160301 li UPD E

        $tableKATU = ' HANTEITEIKEIMST TM1, ';
        $tableSAISYU = ' HANTEITEIKEIMST TM2, ';

        //----20220121 sun upd s
        //$str = substr($con1, 0, 2);
        $str = '';
        if (false !== strpos($con1, ',')) {
            $exp = explode(',', $con1);
            for ($i = 0; $i < count($exp); ++$i) {
                $str .= "'" . substr($exp[$i], 0, 2) . "',";
            }
            $str = substr($str, 0, strlen($str) - 1);
        } else {
            $str = "'" . substr($con1, 0, 2) . "'";
        }
        //----20220121 sun upd e

        $whereKATU = '';
        $whereKATU .= ' AND ';
        $whereKATU .= ' SUBSTR( ';
        //--- 20160127 li UPD S
        // $whereKATU .= " CASE WHEN rtrim(HANTEILST.HANTEI7_CD) IS NOT NULL THEN  HANTEILST.HANTEI7_CD ";
        // $whereKATU .= " WHEN rtrim(HANTEILST.HANTEI6_CD) IS NOT NULL THEN  HANTEILST.HANTEI6_CD ";
        // $whereKATU .= " WHEN rtrim(HANTEILST.HANTEI5_CD) IS NOT NULL THEN  HANTEILST.HANTEI5_CD ";
        // $whereKATU .= " WHEN rtrim(HANTEILST.HANTEI4_CD) IS NOT NULL THEN  HANTEILST.HANTEI4_CD ";
        // $whereKATU .= " WHEN rtrim(HANTEILST.HANTEI3_CD) IS NOT NULL THEN  HANTEILST.HANTEI3_CD ";
        // $whereKATU .= " WHEN rtrim(HANTEILST.HANTEI2_CD) IS NOT NULL THEN  HANTEILST.HANTEI2_CD ";
        // $whereKATU .= " WHEN rtrim(HANTEILST.HANTEI1_CD) IS NOT NULL THEN  HANTEILST.HANTEI1_CD ";

        if ('1' == $con4 or '2' == $con4) {
            $whereKATU .= ' CASE WHEN rtrim(HANTEILST_SINSYA.HANTEI7_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI7_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI6_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI6_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI5_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI5_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI4_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI4_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI3_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI3_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI2_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI2_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST_SINSYA.HANTEI1_CD) IS NOT NULL THEN  HANTEILST_SINSYA.HANTEI1_CD ';
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $whereKATU .= ' CASE WHEN rtrim(HANTEILST.HANTEI7_CD) IS NOT NULL THEN  HANTEILST.HANTEI7_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST.HANTEI6_CD) IS NOT NULL THEN  HANTEILST.HANTEI6_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST.HANTEI5_CD) IS NOT NULL THEN  HANTEILST.HANTEI5_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST.HANTEI4_CD) IS NOT NULL THEN  HANTEILST.HANTEI4_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST.HANTEI3_CD) IS NOT NULL THEN  HANTEILST.HANTEI3_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST.HANTEI2_CD) IS NOT NULL THEN  HANTEILST.HANTEI2_CD ';
            $whereKATU .= ' WHEN rtrim(HANTEILST.HANTEI1_CD) IS NOT NULL THEN  HANTEILST.HANTEI1_CD ';
        }
        //--- 20160127 li UPD E
        $whereKATU .= " ELSE 'ZZZZZ' ";
        $whereKATU .= ' END ';
        //----20220121 sun upd s
        //$whereKATU .= " ,0,2) ='" . $str . "' ";
        $whereKATU .= ' ,0,2) IN (' . $str . ') ';
        //----20220121 sun upd e

        $str = substr($con2, 0, 2);
        $whereSAI = '';
        //--- 20160127 li UPD S
        // $whereSAI .= " SUBSTR(HANTEILST.KEKKA_CD ,0,2) = '" . $str . "' ";
        // $whereSAI .= " AND ";
        if ('1' == $con4 && '999' != $con2) {
            if ('998' == $con2) {
                $whereSAI .= " AND nvl(HANTEILST_SINSYA.HANTEI7_CD,' ' ) <> (select TEIKEI_CD from HANTEITEIKEIMST where  ITEMNAME1 like '入庫済%' and TEIKEI_TYPE = '3') ";
            } else {
                $whereSAI .= " AND SUBSTR(HANTEILST_SINSYA.HANTEI7_CD ,0,2) = '" . $str . "' ";
            }
        } elseif ('2' == $con4 && '999' != $con2) {
            if ('998' == $con2) {
                $whereSAI .= " AND nvl(HANTEILST_SINSYA.KEKKA_CD,' ') <> (select TEIKEI_CD from HANTEITEIKEIMST where  ITEMNAME1 like '入庫済%' and TEIKEI_TYPE = '4') ";
            } else {
                $whereSAI .= " AND SUBSTR(HANTEILST_SINSYA.KEKKA_CD ,0,2) = '" . $str . "' ";
            }
        }
        //--- 20190228 ci INS S
        elseif ('3' == $con4 && '999' != $con2) {
            if ('998' == $con2) {
                $whereSAI .= " AND nvl(HANTEILST_CHUKO.KEKKA1_CD,' ') not in (select TEIKEI_CD from HANTEITEIKEIMST where  ITEMNAME1 like '入庫済%' and TEIKEI_TYPE = '5') ";
            } else {
                $whereSAI .= " AND SUBSTR(HANTEILST_CHUKO.KEKKA_CD ,0,2) = '" . $str . "' ";
            }
        }
        //--- 20190228 ci INS E
        elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $whereSAI .= " AND SUBSTR(HANTEILST.KEKKA_CD ,0,2) = '" . $str . "' ";
        }
        //--- 20160127 li UPD E

        //並び順指定
        //--- 20160301 li UPD S
        // if ($con3 == "0") {
        // //車両区分順
        // if ($con1 != '000' && $con2 != '000') {
        // $str_sql = str_replace("{condition_list}", $whereKATU . $whereSAI, $str_sql);
        // $str_sql = str_replace("{conbine_list}", $conbineKATU . $conbineSAISYU, $str_sql);
        // $str_sql = str_replace("{table_order}", $tableKATU . $tableSAISYU, $str_sql);
        // } else {
        // //--- 20160127 li UPD S
        // // if ($con1 == '000' && $con2 == '000') {
        // if ($con1 == '000' && ($con2 == '000' || $con2 == '999')) {
        // //--- 20160127 li UPD E
        // $str_sql = str_replace("{conbine_list}", "", $str_sql);
        // $str_sql = str_replace("{condition_list}", "", $str_sql);
        // $str_sql = str_replace("{table_order}", "", $str_sql);
        // } elseif ($con1 != '000' && $con2 == '000') {
        // $str_sql = str_replace("{conbine_list}", $whereKATU, $str_sql);
        // $str_sql = str_replace("{condition_list}", $conbineKATU, $str_sql);
        // $str_sql = str_replace("{table_order}", $tableKATU, $str_sql);
        // } else {
        // $str_sql = str_replace("{conbine_list}", $whereSAI, $str_sql);
        // $str_sql = str_replace("{condition_list}", $conbineSAISYU, $str_sql);
        // $str_sql = str_replace("{table_order}", $tableSAISYU, $str_sql);
        // }
        // }
        //
        // $str_sql = str_replace("{select_list}", "", $str_sql);
        // $str_sql = str_replace("{orderby}", $orderby, $str_sql);
        //
        // } elseif ($con3 == "1") {
        // //活動状況順
        // if ($con1 != '000' && $con2 != '000') {
        // $str_sql = str_replace("{condition_list}", $whereKATU . $whereSAI, $str_sql);
        // $str_sql = str_replace("{conbine_list}", $conbineKATU . $conbineSAISYU, $str_sql);
        // $str_sql = str_replace("{table_order}", $tableKATU . $tableSAISYU, $str_sql);
        // } else {
        // //--- 20160127 li UPD S
        // // if ($con1 == '000' && $con2 == '000') {
        // if ($con1 == '000' && ($con2 == '000' || $con2 == '999')) {
        // //--- 20160127 li UPD E
        // $str_sql = str_replace("{conbine_list}", $conbineKATU, $str_sql);
        // $str_sql = str_replace("{condition_list}", "", $str_sql);
        // $str_sql = str_replace("{table_order}", $tableKATU, $str_sql);
        // } elseif ($con1 != '000' && $con2 == '000') {
        // $str_sql = str_replace("{conbine_list}", $conbineKATU, $str_sql);
        // $str_sql = str_replace("{condition_list}", $whereKATU, $str_sql);
        // $str_sql = str_replace("{table_order}", $tableKATU, $str_sql);
        // } else {
        // $str_sql = str_replace("{conbine_list}", $conbineKATU . $conbineSAISYU, $str_sql);
        // $str_sql = str_replace("{condition_list}", $whereSAI, $str_sql);
        // $str_sql = str_replace("{table_order}", $tableKATU . $tableSAISYU, $str_sql);
        // }
        // }
        //
        // $str_sql = str_replace("{select_list}", $selectKATU, $str_sql);
        // $str_sql = str_replace("{orderby}", $orderbyKATU, $str_sql);
        // } else {
        // //最終結果順
        // if ($con1 != '000' && $con2 != '000') {
        // $str_sql = str_replace("{condition_list}", $whereKATU . $whereSAI, $str_sql);
        // $str_sql = str_replace("{conbine_list}", $conbineKATU . $conbineSAISYU, $str_sql);
        // $str_sql = str_replace("{table_order}", $tableKATU . $tableSAISYU, $str_sql);
        // } else {
        // //--- 20160127 li UPD S
        // // if ($con1 == '000' && $con2 == '000') {
        // if ($con1 == '000' && ($con2 == '000' || $con2 == '999')) {
        // //--- 20160127 li UPD E
        // $str_sql = str_replace("{conbine_list}", $conbineSAISYU, $str_sql);
        // $str_sql = str_replace("{condition_list}", "", $str_sql);
        // $str_sql = str_replace("{table_order}", $tableSAISYU, $str_sql);
        // } elseif ($con1 != '000' && $con2 == '000') {
        // $str_sql = str_replace("{conbine_list}", $conbineKATU . $conbineSAISYU, $str_sql);
        // $str_sql = str_replace("{condition_list}", $whereKATU, $str_sql);
        // $str_sql = str_replace("{table_order}", $tableKATU . $tableSAISYU, $str_sql);
        // } else {
        // $str_sql = str_replace("{conbine_list}", $whereSAI, $str_sql);
        // $str_sql = str_replace("{condition_list}", $conbineSAISYU, $str_sql);
        // $str_sql = str_replace("{table_order}", $tableSAISYU, $str_sql);
        // }
        // }
        //
        // $str_sql = str_replace("{select_list}", $selectSAISYU, $str_sql);
        // $str_sql = str_replace("{orderby}", $orderbySAISYU, $str_sql);
        // }

        //----20220121 sun upd s
        //if ($con4 == "0")
        if ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            if ('0' == $con3) {
                //車両区分順
                if ('000' != $con1 && '000' != $con2) {
                    $str_sql = str_replace('{condition_list}', $whereKATU . $whereSAI, $str_sql);
                    $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                    $str_sql = str_replace('{table_order}', $tableKATU . $tableSAISYU, $str_sql);
                } else {
                    if ('000' == $con1 && '000' == $con2) {
                        $str_sql = str_replace('{conbine_list}', '', $str_sql);
                        $str_sql = str_replace('{condition_list}', '', $str_sql);
                        $str_sql = str_replace('{table_order}', '', $str_sql);
                    } elseif ('000' != $con1 && '000' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $whereKATU, $str_sql);
                        $str_sql = str_replace('{condition_list}', $conbineKATU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                    } else {
                        $str_sql = str_replace('{conbine_list}', $whereSAI, $str_sql);
                        $str_sql = str_replace('{condition_list}', $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableSAISYU, $str_sql);
                    }
                }

                $str_sql = str_replace('{select_list}', '', $str_sql);
                $str_sql = str_replace('{orderby}', $orderby, $str_sql);
            } elseif ('1' == $con3) {
                //活動状況順
                if ('000' != $con1 && '000' != $con2) {
                    $str_sql = str_replace('{condition_list}', $whereKATU . $whereSAI, $str_sql);
                    $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                    $str_sql = str_replace('{table_order}', $tableKATU . $tableSAISYU, $str_sql);
                } else {
                    if ('000' == $con1 && '000' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $conbineKATU, $str_sql);
                        $str_sql = str_replace('{condition_list}', '', $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                    } elseif ('000' != $con1 && '000' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $conbineKATU, $str_sql);
                        $str_sql = str_replace('{condition_list}', $whereKATU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                    } else {
                        $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{condition_list}', $whereSAI, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU . $tableSAISYU, $str_sql);
                    }
                }

                $str_sql = str_replace('{select_list}', $selectKATU, $str_sql);
                $str_sql = str_replace('{orderby}', $orderbyKATU, $str_sql);
            } else {
                //最終結果順
                if ('000' != $con1 && '000' != $con2) {
                    $str_sql = str_replace('{condition_list}', $whereKATU . $whereSAI, $str_sql);
                    $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                    $str_sql = str_replace('{table_order}', $tableKATU . $tableSAISYU, $str_sql);
                } else {
                    if ('000' == $con1 && '000' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{condition_list}', '', $str_sql);
                        $str_sql = str_replace('{table_order}', $tableSAISYU, $str_sql);
                    } elseif ('000' != $con1 && '000' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{condition_list}', $whereKATU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU . $tableSAISYU, $str_sql);
                    } else {
                        $str_sql = str_replace('{conbine_list}', $whereSAI, $str_sql);
                        $str_sql = str_replace('{condition_list}', $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableSAISYU, $str_sql);
                    }
                }

                $str_sql = str_replace('{select_list}', $selectSAISYU, $str_sql);
                $str_sql = str_replace('{orderby}', $orderbySAISYU, $str_sql);
            }
        } elseif //--- 20190228 ci UPD S
        ('1' == $con4 or '2' == $con4 or '3' == $con4) {
            //--- 20190228 ci UPD E
            if ('0' == $con3) {
                //車両区分順
                if ('000' != $con1 && '999' != $con2) {
                    $str_sql = str_replace('{condition_list}', $whereKATU . $whereSAI, $str_sql);
                    $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                    $str_sql = str_replace('{table_order}', $tableKATU . $tableSAISYU, $str_sql);
                } else {
                    if ('000' == $con1 && '999' == $con2) {
                        $str_sql = str_replace('{conbine_list}', '', $str_sql);
                        $str_sql = str_replace('{condition_list}', '', $str_sql);
                        $str_sql = str_replace('{table_order}', '', $str_sql);
                    } elseif ('000' != $con1 && '999' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $whereKATU, $str_sql);
                        $str_sql = str_replace('{condition_list}', $conbineKATU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                    } else {
                        $str_sql = str_replace('{conbine_list}', $whereSAI, $str_sql);
                        $str_sql = str_replace('{condition_list}', $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableSAISYU, $str_sql);
                    }
                }

                $str_sql = str_replace('{select_list}', '', $str_sql);
                $str_sql = str_replace('{orderby}', $orderby, $str_sql);
            } elseif ('1' == $con3) {
                //活動状況順
                if ('000' != $con1 && '999' != $con2) {
                    $str_sql = str_replace('{condition_list}', $whereKATU . $whereSAI, $str_sql);
                    $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                    $str_sql = str_replace('{table_order}', $tableKATU . $tableSAISYU, $str_sql);
                } else {
                    if ('000' == $con1 && '999' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $conbineKATU, $str_sql);
                        $str_sql = str_replace('{condition_list}', '', $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                    } elseif ('000' != $con1 && '999' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $conbineKATU, $str_sql);
                        $str_sql = str_replace('{condition_list}', $whereKATU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU, $str_sql);
                    } else {
                        $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{condition_list}', $whereSAI, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU . $tableSAISYU, $str_sql);
                    }
                }

                $str_sql = str_replace('{select_list}', $selectKATU, $str_sql);
                $str_sql = str_replace('{orderby}', $orderbyKATU, $str_sql);
            } else {
                //最終結果順
                if ('000' != $con1 && '999' != $con2) {
                    $str_sql = str_replace('{condition_list}', $whereKATU . $whereSAI, $str_sql);
                    $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                    $str_sql = str_replace('{table_order}', $tableKATU . $tableSAISYU, $str_sql);
                } else {
                    if ('000' == $con1 && '999' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{condition_list}', '', $str_sql);
                        $str_sql = str_replace('{table_order}', $tableSAISYU, $str_sql);
                    } elseif ('000' != $con1 && '999' == $con2) {
                        $str_sql = str_replace('{conbine_list}', $conbineKATU . $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{condition_list}', $whereKATU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableKATU . $tableSAISYU, $str_sql);
                    } else {
                        $str_sql = str_replace('{conbine_list}', $whereSAI, $str_sql);
                        $str_sql = str_replace('{condition_list}', $conbineSAISYU, $str_sql);
                        $str_sql = str_replace('{table_order}', $tableSAISYU, $str_sql);
                    }
                }

                $str_sql = str_replace('{select_list}', $selectSAISYU, $str_sql);
                $str_sql = str_replace('{orderby}', $orderbySAISYU, $str_sql);
            }
        }
        //--- 20160301 li UPD E
        return $str_sql;
    }
}