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
 * 20150610           ---                       本部/店舗の判断方法変更            HM
 * 20150825           ---                       SDH改善要望(20150819)           Yuanjh
 * 20150910           ---                       SDH改善要望(20150819)           Yuanjh
 * 20151029			  ---						SDH改善要望(20150914)			  Yinhuaiyu
 * 20151102			  ---						SDH改善要望(20150914)			  Yinhuaiyu
 * 20151104 		  ---						BUG修正						  Yinhuaiyu
 * 20151118           ---						日本側受入結果反映	HM
 *  20160127                	  ---						メソッド引数相違の修正	HM
 * 20160127           #2373                     依頼                           li
 * 20171225           ---                       集計結果が0になっている不具合修正　　HM
 * 20190227           #2870                     依頼                           YIN
 * 20210219           \99.提供資料\20210217\20210217_SDH_ログイン後の仕様変更.xlsx                       依頼                           CI
 * 20220121           機能追加　　　　　　          N6対応                         Sun
 */

// 共通クラスの読込み

namespace App\Model\SDH;

use App\Model\Component\ClsComDb;

class SDH01 extends ClsComDb
{
    /**
     * リモートホスト・IPアドレス第３オクテットから店舗を判断する。
     *
     * @param {String} $ip リモートホスト・IPアドレス
     *
     * @return {String} 店舗名
     */
    // public function m_check_tenpo_by_ip3($ip)
    // {
    //     include_once 'inc_files/SDH.inc';

    //     $tenpo = '';
    //     $arr_ip = explode('.', $ip);
    //     $ip3 = $arr_ip[2];
    //     if (array_key_exists($ip3, $ip3_tenpo)) {
    //         $tenpo = $ip3_tenpo[$ip3];
    //     }

    //     return $tenpo;
    // }

    /**
     * リモートホスト・IPアドレス第３オクテットから店舗コードを取得するSQL文取得する。
     *
     * @param {String} $ip リモートホスト・IPアドレス
     *
     * @return {String} select文
     */
    //20150610 Update Start
    //    public function m_select_kyotn_cd_sql($ip) {
    public function m_select_kyotn_cd_sql($ip, $userid)
    {
        //20150610 Update End
        // $arr_ip = explode('.', $ip);
        // $ip3 = $arr_ip[2];
        //20210219 CI UPD S
        // $str_sql = "";
        // $str_sql .= "SELECT ";
        // $str_sql .= "  HKTNIPTABLES.KYOTN_CD ";
        // $str_sql .= "FROM ";
        // $str_sql .= "  HKTNIPTABLES ";
        // $str_sql .= "WHERE ";
        // $str_sql .= "  HKTNIPTABLES.IP_ADDRESS = '" . $ip3 . "'";
        //
        // //20150610 Add Start
        // $str_sql .= "  AND NOT EXISTS (SELECT * FROM HKTNYAKUIN WHERE HKTNYAKUIN.KYOTN_CD = HKTNIPTABLES.KYOTN_CD  AND HKTNYAKUIN.SYAIN_NO  = '" . $userid . "')";
        // //20150610 Add End
        $str_sql = '';
        $str_sql .= 'SELECT *';
        $str_sql .= ' FROM ';
        $str_sql .= '  HKTNYAKUIN ';
        $str_sql .= 'WHERE ';
        $str_sql .= "  HKTNYAKUIN.SYAIN_NO = '" . $userid . "'";
        //20210219 CI UPD E
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
        $str_sql .= "HHAIZOKU.BUSYO_CD LIKE '" . $tenpo_cd_2 . "%'" . "\r\n";

        return $str_sql;
    }

    //20150820 Yuanjh ADD S.

    /**
     * リモートホスト・車種を一覧表示を取得するSQL文取得する。
     *
     * @return {String} select文
     */
    public function m_select_hantei_sql()
    {
        $str_sql = '';
        //--20150910 Yuanjh UPD S.
        //$str_sql = "SELECT DISTINCT SYASYU_NM FROM HANTEISYASYUMST ORDER BY DISP_SEQ";
        $str_sql = 'SELECT DISTINCT SYASYU_NM,DISP_SEQ  FROM HANTEISYASYUMST ORDER BY DISP_SEQ';
        //--20150910 Yuanjh UPD E.
        return $str_sql;
    }

    /**
     * リモートホスト・判定定型マスタに登録された「最終結果」を一覧表示。
     *
     * @return {String} select文
     */
    public function m_select_hanteiteilkei_sql()
    {
        $str_sql = '';
        $str_sql = '  SELECT  min(DISP_SEQ), ITEMNAME1';
        $str_sql .= '  FROM HANTEITEIKEIMST';
        $str_sql .= '  WHERE TEIKEI_TYPE = 2 AND';
        $str_sql .= '(END_DT IS NULL or END_DT >= SYSDATE )';
        $str_sql .= '  GROUP BY ITEMNAME1';
        $str_sql .= '  ORDER BY min(DISP_SEQ)';

        return $str_sql;
    }

    //--- 20160127 li INS S

    /**
     * リモートホスト・判定定型マスタに登録された「最終結果」を一覧表示。
     *
     * @return {String} select文
     */
    public function m_select_hanteiteilkei_sinsya_sql()
    {
        $str_sql = '';
        $str_sql = '  SELECT  min(DISP_SEQ), ITEMNAME1';
        $str_sql .= '  FROM HANTEITEIKEIMST';
        $str_sql .= '  WHERE TEIKEI_TYPE = 4 AND';
        $str_sql .= '(END_DT IS NULL or END_DT >= SYSDATE )';
        $str_sql .= '  GROUP BY ITEMNAME1';
        $str_sql .= '  ORDER BY min(DISP_SEQ)';

        return $str_sql;
    }

    //--- 20160127 li INS E

    //20151029 Yin ADD S

    /**
     * 判定定型マスタを検索し、取得したデータを画面に編集する.
     *
     * @return {String} select文
     */
    public function m_select_resulttittle_sql()
    {
        $str_sql = '';
        $str_sql .= '  SELECT';
        //20151103 Yin UPD S
        //$str_sql .= "  SUBSTR(TEIKEI_CD,1,2) TEIKEI_CD ,ITEMNAME1"
        $str_sql .= "  SUBSTR(TEIKEI_CD,1,2) TEIKEI_CD ,ITEMNAME1,TO_CHAR(SYSDATE,'yyyymmdd') AS GETDATE";
        //20151103 Yin UPD E

        //20160120 UPD S
        $str_sql .= '  ,min(DISP_SEQ) ';
        //20160120 UPD E

        $str_sql .= '  FROM  HANTEITEIKEIMST ,DUAL  ';
        $str_sql .= '  WHERE HANTEITEIKEIMST.TEIKEI_TYPE = 2';
        $str_sql .= '  GROUP BY';
        $str_sql .= '  SUBSTR(TEIKEI_CD,1,2) ,ITEMNAME1 ';
        //20160120 UPD S
        $str_sql .= '  ORDER BY min(DISP_SEQ)';
        //20160120 UPD E
        return $str_sql;
    }

    //--- 20160127 li INS S

    /**
     * 判定定型マスタを検索し、取得したデータを画面に編集する.
     *
     * @return {String} select文
     */
    public function m_select_resulttittle_sinsya_sql($con4)
    {
        if ('1' == $con4) {
            $strType = '3';
        }
        if ('2' == $con4) {
            $strType = '4';
        }
        $str_sql = '';
        $str_sql .= '  SELECT';
        $str_sql .= "  SUBSTR(TEIKEI_CD,1,2) TEIKEI_CD ,ITEMNAME1,TO_CHAR(SYSDATE,'yyyymmdd') AS GETDATE";
        $str_sql .= '  ,min(DISP_SEQ) ';
        $str_sql .= '  FROM  HANTEITEIKEIMST ,DUAL  ';
        $str_sql .= "  WHERE HANTEITEIKEIMST.TEIKEI_TYPE = '" . $strType . "'";
        $str_sql .= '  GROUP BY';
        $str_sql .= '  SUBSTR(TEIKEI_CD,1,2) ,ITEMNAME1 ';
        $str_sql .= '  ORDER BY min(DISP_SEQ)';

        return $str_sql;
    }

    //--- 20160127 li INS E

    //20151029 Yin ADD E

    /**
     * リモートホスト・判定定型マスタに登録された「最終結果」を一覧表示。
     *
     * @return {String} select文
     */
    public function m_select_resulthanteiteilkeiDetails_sql($ym, $hantei, $tantocd, $busyocd)
    {
        //20151029 Yin INS S
        //20151102 Yin UPD S
        // $str_sql = "";
        // $str_sql = "  SELECT     ";
        // $str_sql .= "  CASE";
        // $str_sql .= "  WHEN X.RANKING = 12 THEN 1";
        // $str_sql .= "  WHEN X.RANKING = 11 THEN 2";
        // $str_sql .= "  WHEN X.RANKING = 10 THEN 3";
        // $str_sql .= "  WHEN X.RANKING = 9 THEN 4";
        // $str_sql .= "  WHEN X.RANKING = 8 THEN 5";
        // $str_sql .= "  WHEN X.RANKING = 7 THEN 6";
        // $str_sql .= "  WHEN X.RANKING = 6 THEN 7";
        // $str_sql .= "  WHEN X.RANKING = 5 THEN 8";
        // $str_sql .= "  WHEN X.RANKING = 4 THEN 9";
        // $str_sql .= "  WHEN X.RANKING = 3 THEN 10";
        // $str_sql .= "  WHEN X.RANKING = 2 THEN 11";
        // $str_sql .= "  WHEN X.RANKING = 1 THEN 12";
        // $str_sql .= "  ELSE 12";
        // $str_sql .= "  END AS COLPOSITION,";
        // $str_sql .= "  MAX(X.FRGMH)FRGMH,";
        // $str_sql .= "  SUBSTR(X.KEKKA_CD,1,2)AS KEKKA_CD,";
        // $str_sql .= "  CASE WHEN X.RANKING = 12 THEN SUM(X.KENSU) ELSE 0 END AS COL1,";
        // $str_sql .= "  CASE WHEN X.RANKING = 11 THEN SUM(X.KENSU) ELSE 0 END AS COL2,";
        // $str_sql .= "  CASE WHEN X.RANKING = 10 THEN SUM(X.KENSU) ELSE 0 END AS COL3,";
        // $str_sql .= "  CASE WHEN X.RANKING = 9 THEN SUM(X.KENSU) ELSE 0 END AS COL4,";
        // $str_sql .= "  CASE WHEN X.RANKING = 8 THEN SUM(X.KENSU) ELSE 0 END AS COL5,";
        // $str_sql .= "  CASE WHEN X.RANKING = 7 THEN SUM(X.KENSU) ELSE 0 END AS COL6,";
        // $str_sql .= "  CASE WHEN X.RANKING = 6 THEN SUM(X.KENSU) ELSE 0 END AS COL7,";
        // $str_sql .= "  CASE WHEN X.RANKING = 5 THEN SUM(X.KENSU) ELSE 0 END AS COL8,";
        // $str_sql .= "  CASE WHEN X.RANKING = 4 THEN SUM(X.KENSU) ELSE 0 END AS COL9,";
        // $str_sql .= "  CASE WHEN X.RANKING = 3 THEN SUM(X.KENSU) ELSE 0 END AS COL10,";
        // $str_sql .= "  CASE WHEN X.RANKING = 2 THEN SUM(X.KENSU) ELSE 0 END AS COL11,";
        // $str_sql .= "  CASE WHEN X.RANKING = 1 THEN SUM(X.KENSU) ELSE 0 END AS COL12";
        // $str_sql .= "  FROM";
        // $str_sql .= "  (";
        // $str_sql .= "  SELECT";
        // $str_sql .= "  RANKING,";
        // $str_sql .= "  SYASYU,";
        // $str_sql .= "  FRGMH,";
        // $str_sql .= "  KEKKA_CD,";
        // $str_sql .= "  COUNT(KEKKA_CD)KENSU";
        // $str_sql .= "  FROM";
        // $str_sql .= "  (";
        // $str_sql .= "  SELECT";
        // $str_sql .= "  CASE WHEN";
        // $str_sql .= "  DENSE_RANK()OVER(ORDER BY SUBSTR(FRGMH,1,4)DESC)>12 THEN 12";
        // $str_sql .= "  ELSE";
        // $str_sql .= "  DENSE_RANK()OVER(ORDER BY SUBSTR(FRGMH,1,4)DESC)";
        // $str_sql .= "  END";
        // $str_sql .= "  AS RANKING,";
        // $str_sql .= "  SUBSTR(FRGMH,1,4) AS FRGMH,";
        // $str_sql .= "  HANTEILST.SYASYU,";
        // $str_sql .= "  HANTEILST.SYADAI,";
        // $str_sql .= "  HANTEILST.CARNO,";
        // $str_sql .= "  HANTEILST.KEKKA_CD,";
        // $str_sql .= "  MAX(HANTEILST.REVISION)";
        // $str_sql .= "  FROM";
        // $str_sql .= "  M41C03,";
        // $str_sql .= "  M41C04,";
        // $str_sql .= "  HANTEILST";
        // $str_sql .= "  WHERE";
        // $str_sql .= "  M41C03.VIN_WMIVDS = M41C04.VIN_WMIVDS ";
        // $str_sql .= "  AND M41C03.VIN_VIS = M41C04.VIN_VIS ";
        // $str_sql .= "  AND M41C03.VIN_WMIVDS = HANTEILST.SYADAI ";
        // $str_sql .= "  AND M41C03.VIN_VIS = HANTEILST.CARNO ";
        //
        //
        // if ($tantocd == "000"||$tantocd == "001"||$tantocd == "002")
        // {
        // }
        // else
        // {
        // $str_sql .= "  AND M41C04.KNR_BUSMANCD = '" . $tantocd . "'";
        // }
        //
        // $str_sql .= "  AND HANTEILST.YYMM = '".$ym."'";
        //
        // if ($hantei == "その他")
        // {
        // $str_sql .= "   AND HANTEILST.SYASYU is NULL";
        // }
        // elseif ($hantei <> "全て")
        // {
        // $str_sql .= "  AND HANTEILST.SYASYU = '" . $hantei . "'";
        // }
        // $str_sql .= "  AND RTRIM(HANTEILST.KEKKA_CD) IS NOT NULL";
        // $str_sql .= "  GROUP BY";
        // $str_sql .= "  M41C03.FRGMH,";
        // $str_sql .= "  HANTEILST.SYASYU,";
        // $str_sql .= "  HANTEILST.SYADAI,";
        // $str_sql .= "  HANTEILST.CARNO,";
        // $str_sql .= "  HANTEILST.KEKKA_CD";
        // $str_sql .= "  )";
        // $str_sql .= "  GROUP BY";
        // $str_sql .= "  RANKING,";
        // $str_sql .= "  SYASYU,";
        // $str_sql .= "  FRGMH,";
        // $str_sql .= "  KEKKA_CD";
        // $str_sql .= "  ) X";
        // $str_sql .= "  GROUP BY";
        // $str_sql .= "  X.RANKING,";
        // $str_sql .= "  SUBSTR(X.KEKKA_CD,1,2)";
        // $str_sql .= "  ORDER BY";
        // $str_sql .= "  COLPOSITION,";
        // $str_sql .= "  FRGMH DESC";

        //集計見直し
        $str_sql = '';
        $str_sql = '  SELECT     ';
        $str_sql .= '  KEKKA_CD,';
        $str_sql .= '  SUM(COL1)　AS COL1,';
        $str_sql .= '  SUM(COL2)　AS COL2,';
        $str_sql .= '  SUM(COL3)　AS COL3,';
        $str_sql .= '  SUM(COL4)　AS COL4,';
        $str_sql .= '  SUM(COL5)　AS COL5,';
        $str_sql .= '  SUM(COL6)　AS COL6,';
        $str_sql .= '  SUM(COL7)　AS COL7,';
        $str_sql .= '  SUM(COL8)　AS COL8,';
        $str_sql .= '  SUM(COL9)　AS COL9,';
        $str_sql .= '  SUM(COL10)　AS COL10,';
        $str_sql .= '  SUM(COL11)　AS COL11,';
        $str_sql .= '  SUM(COL12) 　AS COL12';
        $str_sql .= '  FROM  ';
        $str_sql .= '  (  SELECT ';
        $str_sql .= '    X.COLPOSITION,';
        $str_sql .= '    X.KEKKA_CD,';
        $str_sql .= '    CASE WHEN X.COLPOSITION =1 THEN SUM(X.KENSU) ELSE 0 END AS COL1,';
        $str_sql .= '    CASE WHEN X.COLPOSITION =2 THEN SUM(X.KENSU) ELSE 0 END AS COL2,';
        $str_sql .= '    CASE WHEN X.COLPOSITION =3 THEN SUM(X.KENSU) ELSE 0 END AS COL3,';
        $str_sql .= '    CASE WHEN X.COLPOSITION =4 THEN SUM(X.KENSU) ELSE 0 END AS COL4,';
        $str_sql .= '    CASE WHEN X.COLPOSITION =5 THEN SUM(X.KENSU) ELSE 0 END AS COL5,';
        $str_sql .= '    CASE WHEN X.COLPOSITION =6 THEN SUM(X.KENSU) ELSE 0 END AS COL6,';
        $str_sql .= '    CASE WHEN X.COLPOSITION =7 THEN SUM(X.KENSU) ELSE 0 END AS COL7,';
        $str_sql .= '    CASE WHEN X.COLPOSITION =8 THEN SUM(X.KENSU) ELSE 0 END AS COL8,';
        $str_sql .= '    CASE WHEN X.COLPOSITION =9 THEN SUM(X.KENSU) ELSE 0 END AS COL9,';
        $str_sql .= '    CASE WHEN X.COLPOSITION =10 THEN SUM(X.KENSU) ELSE 0 END AS COL10,';
        $str_sql .= '    CASE WHEN X.COLPOSITION =11 THEN SUM(X.KENSU) ELSE 0 END AS COL11,';
        $str_sql .= '    CASE WHEN X.COLPOSITION =12 THEN SUM(X.KENSU) ELSE 0 END AS COL12';
        $str_sql .= '  FROM';
        $str_sql .= '    (';
        $str_sql .= '  SELECT';
        $str_sql .= '  CASE';
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4) < TO_CHAR(ADD_MONTHS(SYSDATE,-132),'yyyy')  THEN 1";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-132),'yyyy')  THEN 2";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-120),'yyyy')  THEN 3";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-108),'yyyy')  THEN 4";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-96),'yyyy')  THEN 5";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-84),'yyyy')  THEN 6";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-72),'yyyy')  THEN 7";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-60),'yyyy')  THEN 8";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-48),'yyyy')  THEN 9";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-36),'yyyy')  THEN 10";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-24),'yyyy')  THEN 11";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-12),'yyyy')  THEN 12";
        $str_sql .= '  ELSE 1';
        $str_sql .= '  END AS COLPOSITION,';
        $str_sql .= '  SYASYU,';
        $str_sql .= '  FRGMH,';
        $str_sql .= '  KEKKA_CD,';
        $str_sql .= '  COUNT(KEKKA_CD) KENSU';
        $str_sql .= '  FROM';
        $str_sql .= '   (';
        $str_sql .= '    SELECT';
        $str_sql .= '    SUBSTR(FRGMH,1,4) AS FRGMH,';
        //$str_sql .= "    HANTEILST.SYASYU,";
        $str_sql .= '    HANTEILST.SYADAI,';
        $str_sql .= '    HANTEILST.CARNO,';
        $str_sql .= "    SUBSTR( NVL( RTRIM(HANTEILST.KEKKA_CD) ,'99') ,1,2) KEKKA_CD,";
        //20160822 upd start
        //			$str_sql .= "    MAX(HANTEILST.REVISION),";
        $str_sql .= '    HANTEILST.REVISION ,';
        //20160822 upd end
        $str_sql .= '    HANTEILST_SYASYU.SYASYU ';

        $str_sql .= '    FROM';
        $str_sql .= '    M41C03,';
        $str_sql .= '    M41C04,';
        $str_sql .= '    HANTEILST,';
        $str_sql .= '    HANTEILST_SYASYU ';
        //20160822 add start
        $str_sql .= '    , (SELECT YYMM,SYADAI,CARNO, MAX(to_number(REVISION)) as MAXREVISION FROM HANTEILST GROUP BY YYMM,SYADAI,CARNO) MAXHL ';
        //20160822 add end

        $str_sql .= '    WHERE';
        $str_sql .= '    M41C03.VIN_WMIVDS = M41C04.VIN_WMIVDS ';
        $str_sql .= '    AND M41C03.VIN_VIS = M41C04.VIN_VIS ';
        $str_sql .= '    AND M41C03.VIN_WMIVDS = HANTEILST.SYADAI ';
        $str_sql .= '    AND M41C03.VIN_VIS = HANTEILST.CARNO ';
        $str_sql .= '    AND M41C03.VIN_WMIVDS = HANTEILST_SYASYU.SYADAI(+) ';
        $str_sql .= '    AND M41C03.VIN_VIS = HANTEILST_SYASYU.CARNO(+) ';

        //20160822 add start
        $str_sql .= '    AND MAXHL.YYMM = HANTEILST.YYMM ';
        $str_sql .= '    AND MAXHL.SYADAI = HANTEILST.SYADAI ';
        $str_sql .= '    AND MAXHL.CARNO = HANTEILST.CARNO ';
        $str_sql .= '    AND MAXHL.MAXREVISION = HANTEILST.REVISION ';
        //20160822 add end

        //抽出条件１
        $busyocd = substr($busyocd, 0, 2);
        //（担当者： 店舗全員のとき）
        if ('000' == $tantocd) {
            $str_sql .= "    AND ( substr(M41C04.KNR_STRCD,1,2) = '" . $busyocd . "' OR substr(M41C04.SRV_SRVSTRCD,1,2) = '" . $busyocd . "')";
        }
        //（担当者： 営業全員のとき）
        elseif ('001' == $tantocd) {
            //20151118 Update Start
            //				$str_sql .= "    AND  substr(M41C04.KNR_STRCD,1,2) = '" . $busyocd . "'";
            $str_sql .= "  AND ( substr(M41C04.KNR_STRCD,1,2) = '" . $busyocd . "' AND M41C04.KNR_STRCD <> '" . $busyocd . "7' ) ";
            //20151118 Update End
        }
        //（担当者： サービスのとき）
        elseif ('002' == $tantocd) {
            //20151118 Update Start
            //				$str_sql .= "    AND ( substr(M41C04.KNR_STRCD,1,2) <> '" . $busyocd . "' AND substr(M41C04.SRV_SRVSTRCD,1,2) = '" . $busyocd . "')";
            $str_sql .= "    AND ( ( M41C04.KNR_STRCD <> '" . $busyocd . "1' AND M41C04.KNR_STRCD <> '" . $busyocd . "3') AND substr(M41C04.SRV_SRVSTRCD,1,2) = '" . $busyocd . "')";
            //20151118 Update End
        }
        //（担当者： 担当者指定のとき）
        else {
            $str_sql .= "    AND M41C04.KNR_BUSMANCD = '" . $tantocd . "'";
        }
        //抽出条件２
        //　(車種)　＝　画面・車種
        $str_sql .= "    AND HANTEILST.YYMM = '" . $ym . "'";
        //抽出条件３
        //（車種：全て のとき）
        //　指定なし
        //（車種：全て以外のとき）
        //20151118 Update Start
        //			if ($hantei <> "全て")
        if ('全て' == $hantei) {
        } elseif ('その他' == $hantei) {
            //				$str_sql .= "  AND HANTEILST.SYASYU is null ";
            $str_sql .= '  AND HANTEILST_SYASYU.SYASYU is null ';
        } else {
            //				$str_sql .= "  AND HANTEILST.SYASYU = '" . $hantei . "'";
            $str_sql .= "  AND HANTEILST_SYASYU.SYASYU = '" . $hantei . "'";
        }
        //20151118 Update End

        //20151118 Delete Start
        //			$str_sql .= "    AND RTRIM(HANTEILST.KEKKA_CD) IS NOT NULL";
        //20151118 Delete End
        $str_sql .= '    AND (';
        $str_sql .= '          RTRIM(M41C04.MAS_DT) IS NULL';
        $str_sql .= '          OR';

        //20151118 Update Start
        //			$str_sql .= "          (";
        //			$str_sql .= "           RTRIM(M41C04.MAS_DT) >= TO_CHAR( ADD_MONTHS( TO_DATE( SUBSTR(HANTEILST.YYMM,1,4)||'/'||SUBSTR(HANTEILST.YYMM,5,2)||'/01','yyyy/MM/dd' ) ,-7 ) ,'yyyyMMdd') ";
        //			$str_sql .= "           AND M41C04.RUPD_RIYU_CD IN ('02','86')";
        //			$str_sql .= "          )";
        $str_sql .= '        (';
        $str_sql .= "         rtrim(M41C04.MAS_DT) >= to_char( add_months( to_date( substr('" . $ym . "',1,4)||'/'||substr('" . $ym . "',5,2)||'/01','yyyy/MM/dd' ) ,-7 ) ,'yyyyMMdd') ";
        $str_sql .= "         and M41C04.RUPD_RIYU_CD in ('02','86')";
        $str_sql .= '         and not exists ( ';
        $str_sql .= '          SELECT ';
        $str_sql .= '           NEWER.* ';
        $str_sql .= '          FROM ';
        $str_sql .= '           M41C04 NEWER ';
        $str_sql .= '         WHERE ';
        $str_sql .= '          NEWER.VIN_WMIVDS = M41C04.VIN_WMIVDS ';
        $str_sql .= '          and NEWER.VIN_VIS = M41C04.VIN_VIS ';
        $str_sql .= '          AND rtrim(NEWER.MAS_DT) IS NULL ';
        $str_sql .= '                             ) ';
        $str_sql .= '        )';
        $str_sql .= '        )';
        //20151118 Update End

        //			$str_sql .= "    GROUP BY";
        //			$str_sql .= "    M41C03.FRGMH,";
        //			//$str_sql .= "    HANTEILST.SYASYU,";
        //			$str_sql .= "    HANTEILST_SYASYU.SYASYU,";
        //			$str_sql .= "    HANTEILST.SYADAI,";
        //			$str_sql .= "    HANTEILST.CARNO,";
        //			$str_sql .= "    SUBSTR( NVL( RTRIM(HANTEILST.KEKKA_CD) ,'99') ,1,2)";
        $str_sql .= '    )';
        $str_sql .= '    GROUP BY';
        $str_sql .= '  CASE';
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4) <= TO_CHAR(ADD_MONTHS(SYSDATE,-132),'yyyy')  THEN 1";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-120),'yyyy')  THEN 2";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-108),'yyyy')  THEN 3";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-96),'yyyy')  THEN 4";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-84),'yyyy')  THEN 5";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-72),'yyyy')  THEN 6";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-60),'yyyy')  THEN 7";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-48),'yyyy')  THEN 8";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-36),'yyyy')  THEN 9";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-24),'yyyy')  THEN 10";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-12),'yyyy')  THEN 11";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,0),'yyyy')  THEN 12";
        $str_sql .= '  ELSE 1';
        $str_sql .= '  END,';
        $str_sql .= '    SYASYU,';
        $str_sql .= '    FRGMH,';
        $str_sql .= '    KEKKA_CD';
        $str_sql .= '    ) X';
        $str_sql .= '  GROUP BY';
        $str_sql .= '   X.COLPOSITION  ,';
        $str_sql .= '   X.KEKKA_CD ';
        $str_sql .= '    )';
        $str_sql .= '  GROUP BY KEKKA_CD';
        //20151102 Yin UPD E
        //20151029 Yin INS E
        return $str_sql;
    }

    //--- 20160127 li INS S

    /**
     * リモートホスト・判定定型マスタに登録された「最終結果」を一覧表示。 新車１ヶ月点検、新車６ヶ月点検判定追加.
     *
     * @return {String} select文
     */
    public function m_select_resulthanteiteilkeiDetails_sinsya_sql($ym, $hantei, $tantocd, $busyocd)
    {
        //集計見直し
        $str_sql = '';
        $str_sql = '  SELECT     ';
        $str_sql .= '  KEKKA_CD,';
        $str_sql .= '  SUM(COL1)　AS COL1,';
        $str_sql .= '  SUM(COL2)　AS COL2,';
        $str_sql .= '  SUM(COL3)　AS COL3,';
        $str_sql .= '  SUM(COL4)　AS COL4,';
        $str_sql .= '  SUM(COL5)　AS COL5,';
        $str_sql .= '  SUM(COL6)　AS COL6,';
        $str_sql .= '  SUM(COL7)　AS COL7,';
        $str_sql .= '  SUM(COL8)　AS COL8,';
        $str_sql .= '  SUM(COL9)　AS COL9,';
        $str_sql .= '  SUM(COL10)　AS COL10,';
        $str_sql .= '  SUM(COL11)　AS COL11,';
        $str_sql .= '  SUM(COL12) 　AS COL12';
        $str_sql .= '  FROM  ';
        $str_sql .= '  (  SELECT ';
        $str_sql .= '    X.COLPOSITION,';
        $str_sql .= '    X.KEKKA_CD,';
        $str_sql .= '    CASE WHEN X.COLPOSITION =1 THEN SUM(X.KENSU) ELSE 0 END AS COL1,';
        $str_sql .= '    CASE WHEN X.COLPOSITION =2 THEN SUM(X.KENSU) ELSE 0 END AS COL2,';
        $str_sql .= '    CASE WHEN X.COLPOSITION =3 THEN SUM(X.KENSU) ELSE 0 END AS COL3,';
        $str_sql .= '    CASE WHEN X.COLPOSITION =4 THEN SUM(X.KENSU) ELSE 0 END AS COL4,';
        $str_sql .= '    CASE WHEN X.COLPOSITION =5 THEN SUM(X.KENSU) ELSE 0 END AS COL5,';
        $str_sql .= '    CASE WHEN X.COLPOSITION =6 THEN SUM(X.KENSU) ELSE 0 END AS COL6,';
        $str_sql .= '    CASE WHEN X.COLPOSITION =7 THEN SUM(X.KENSU) ELSE 0 END AS COL7,';
        $str_sql .= '    CASE WHEN X.COLPOSITION =8 THEN SUM(X.KENSU) ELSE 0 END AS COL8,';
        $str_sql .= '    CASE WHEN X.COLPOSITION =9 THEN SUM(X.KENSU) ELSE 0 END AS COL9,';
        $str_sql .= '    CASE WHEN X.COLPOSITION =10 THEN SUM(X.KENSU) ELSE 0 END AS COL10,';
        $str_sql .= '    CASE WHEN X.COLPOSITION =11 THEN SUM(X.KENSU) ELSE 0 END AS COL11,';
        $str_sql .= '    CASE WHEN X.COLPOSITION =12 THEN SUM(X.KENSU) ELSE 0 END AS COL12';
        $str_sql .= '  FROM';
        $str_sql .= '    (';
        $str_sql .= '  SELECT';
        $str_sql .= '  CASE';
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4) < TO_CHAR(ADD_MONTHS(SYSDATE,-132),'yyyy')  THEN 1";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-132),'yyyy')  THEN 2";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-120),'yyyy')  THEN 3";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-108),'yyyy')  THEN 4";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-96),'yyyy')  THEN 5";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-84),'yyyy')  THEN 6";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-72),'yyyy')  THEN 7";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-60),'yyyy')  THEN 8";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-48),'yyyy')  THEN 9";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-36),'yyyy')  THEN 10";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-24),'yyyy')  THEN 11";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-12),'yyyy')  THEN 12";
        $str_sql .= '  ELSE 1';
        $str_sql .= '  END AS COLPOSITION,';
        $str_sql .= '  SYASYU,';
        $str_sql .= '  FRGMH,';
        $str_sql .= '  KEKKA_CD,';
        $str_sql .= '  COUNT(KEKKA_CD) KENSU';
        $str_sql .= '  FROM';
        $str_sql .= '   (';
        $str_sql .= '    SELECT';
        $str_sql .= '    SUBSTR(FRGMH,1,4) AS FRGMH,';
        $str_sql .= '    HANTEILST_SINSYA.SYASYU,';
        $str_sql .= '    HANTEILST_SINSYA.SYADAI,';
        $str_sql .= '    HANTEILST_SINSYA.CARNO,';
        $str_sql .= "    SUBSTR( NVL( RTRIM(HANTEILST_SINSYA.KEKKA6_CD) ,'99') ,1,2) KEKKA_CD,";
        $str_sql .= '    MAX(HANTEILST_SINSYA.REVISION)';
        $str_sql .= '    FROM';
        $str_sql .= '    M41C03,';
        $str_sql .= '    M41C04,';
        $str_sql .= '    HANTEILST_SINSYA';
        $str_sql .= '    WHERE';
        $str_sql .= '    M41C03.VIN_WMIVDS = M41C04.VIN_WMIVDS ';
        $str_sql .= '    AND M41C03.VIN_VIS = M41C04.VIN_VIS ';
        $str_sql .= '    AND M41C03.VIN_WMIVDS = HANTEILST_SINSYA.SYADAI ';
        $str_sql .= '    AND M41C03.VIN_VIS = HANTEILST_SINSYA.CARNO ';

        //抽出条件１
        $busyocd = substr($busyocd, 0, 2);
        //（担当者： 店舗全員のとき）
        if ('000' == $tantocd) {
            $str_sql .= "    AND ( substr(M41C04.KNR_STRCD,1,2) = '" . $busyocd . "' OR substr(M41C04.SRV_SRVSTRCD,1,2) = '" . $busyocd . "')";
        }
        //（担当者： 営業全員のとき）
        elseif ('001' == $tantocd) {
            $str_sql .= "  AND ( substr(M41C04.KNR_STRCD,1,2) = '" . $busyocd . "' AND M41C04.KNR_STRCD <> '" . $busyocd . "7' ) ";
        }
        //（担当者： サービスのとき）
        elseif ('002' == $tantocd) {
            $str_sql .= "    AND ( ( M41C04.KNR_STRCD <> '" . $busyocd . "1' AND M41C04.KNR_STRCD <> '" . $busyocd . "3') AND substr(M41C04.SRV_SRVSTRCD,1,2) = '" . $busyocd . "')";
        }
        //（担当者： 担当者指定のとき）
        else {
            $str_sql .= "    AND M41C04.KNR_BUSMANCD = '" . $tantocd . "'";
        }
        //抽出条件３
        //（車種：全て のとき）
        //　指定なし
        //（車種：全て以外のとき）
        if ('全て' == $hantei) {
        } elseif ('その他' == $hantei) {
            $str_sql .= '  AND HANTEILST_SINSYA.SYASYU is null ';
        } else {
            $str_sql .= "  AND HANTEILST_SINSYA.SYASYU = '" . $hantei . "'";
        }

        $str_sql .= '    AND (';
        $str_sql .= '          RTRIM(M41C04.MAS_DT) IS NULL';
        $str_sql .= '          OR';
        $str_sql .= '        (';
        $str_sql .= "         rtrim(M41C04.MAS_DT) >= to_char( add_months( to_date( substr('" . $ym . "',1,4)||'/'||substr('" . $ym . "',5,2)||'/01','yyyy/MM/dd' ) ,-7 ) ,'yyyyMMdd') ";
        $str_sql .= "         and M41C04.RUPD_RIYU_CD in ('02','86')";
        $str_sql .= '         and not exists ( ';
        $str_sql .= '          SELECT ';
        $str_sql .= '           NEWER.* ';
        $str_sql .= '          FROM ';
        $str_sql .= '           M41C04 NEWER ';
        $str_sql .= '         WHERE ';
        $str_sql .= '          NEWER.VIN_WMIVDS = M41C04.VIN_WMIVDS ';
        $str_sql .= '          and NEWER.VIN_VIS = M41C04.VIN_VIS ';
        $str_sql .= '          AND rtrim(NEWER.MAS_DT) IS NULL ';
        $str_sql .= '                             ) ';
        $str_sql .= '        )';
        $str_sql .= '        )';
        $str_sql .= '    GROUP BY';
        $str_sql .= '    M41C03.FRGMH,';
        $str_sql .= '    HANTEILST_SINSYA.SYASYU,';
        $str_sql .= '    HANTEILST_SINSYA.SYADAI,';
        $str_sql .= '    HANTEILST_SINSYA.CARNO,';
        $str_sql .= "    SUBSTR( NVL( RTRIM(HANTEILST_SINSYA.KEKKA6_CD) ,'99') ,1,2)";
        $str_sql .= '    )';
        $str_sql .= '    GROUP BY';
        $str_sql .= '  CASE';
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4) <= TO_CHAR(ADD_MONTHS(SYSDATE,-132),'yyyy')  THEN 1";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-120),'yyyy')  THEN 2";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-108),'yyyy')  THEN 3";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-96),'yyyy')  THEN 4";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-84),'yyyy')  THEN 5";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-72),'yyyy')  THEN 6";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-60),'yyyy')  THEN 7";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-48),'yyyy')  THEN 8";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-36),'yyyy')  THEN 9";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-24),'yyyy')  THEN 10";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,-12),'yyyy')  THEN 11";
        $str_sql .= "  WHEN SUBSTR(FRGMH,1,4)  = TO_CHAR(ADD_MONTHS(SYSDATE,0),'yyyy')  THEN 12";
        $str_sql .= '  ELSE 1';
        $str_sql .= '  END,';
        $str_sql .= '    SYASYU,';
        $str_sql .= '    FRGMH,';
        $str_sql .= '    KEKKA_CD';
        $str_sql .= '    ) X';
        $str_sql .= '  GROUP BY';
        $str_sql .= '   X.COLPOSITION  ,';
        $str_sql .= '   X.KEKKA_CD ';
        $str_sql .= '    )';
        $str_sql .= '  GROUP BY KEKKA_CD';

        return $str_sql;
    }

    //--- 20160127 li INS E

    /**
     * リモートホスト・最終結果に入力された内容を集計し一覧表示する。
     *
     * @return {String} select文
     */
    /*
     public function m_select_resulthanteiteilkeiDetails_sql($nen,$tuki,$nm,$hantei){
     $str_sql = "";
     $str_sql = "SELECT count(HANTEITEIKEIMST.ITEMNAME1) as KENSU";
     $str_sql .= "  FROM";
     $str_sql .= "  M41C03";
     $str_sql .= ", HANTEILST";
     $str_sql .= ", HANTEITEIKEIMST";
     $str_sql .= ", HANTEILST_SYASYU ";
     $str_sql .= "   WHERE ";
     $str_sql .= "   M41C03.VIN_WMIVDS = HANTEILST.SYADAI";
     $str_sql .= "   AND M41C03.VIN_VIS = HANTEILST.CARNO";
     $str_sql .= "   M41C03.VIN_WMIVDS = HANTEILST_SYASYU.SYADAI(+) ";
     $str_sql .= "   AND M41C03.VIN_VIS = HANTEILST_SYASYU.CARNO(+) ";

     $str_sql .= "   AND SUBSTR(M41C03.FRGMH,0,4) = '$nen'";
     $str_sql .= "   AND SUBSTR(M41C03.FRGMH,5,2) = '$tuki'";
     $str_sql .= "   AND HANTEITEIKEIMST.TEIKEI_CD = HANTEILST.KEKKA_CD";
     $str_sql .= "   AND HANTEITEIKEIMST.TEIKEI_TYPE = 2";
     $str_sql .= "   AND HANTEITEIKEIMST.ITEMNAME1 = '$nm'";
     if($hantei=="その他"){
     //		 $str_sql .= "   AND HANTEILST.SYASYU is NULL";
     $str_sql .= "   AND HANTEILST_SYASYU.SYASYU is NULL";
     }elseif($hantei<>"全て"){
     //		 $str_sql .= "  AND HANTEILST.SYASYU = '$hantei'";
     $str_sql .= "  AND HANTEILST_SYASYU.SYASYU = '$hantei'";
     }
     return $str_sql;
     }
     */

    /**
     * リモートホスト・契約者情報を取得するSQL文取得する。
     *
     * @param {String} $CMN_NO   契約者
     * @param {String} $tenpo_cd 店舗コード
     *
     * @return {String} select文
     */
    public function m_select_KEIYAKUSYA_sql($CMN_NO)
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= '  M41C01.CSRNM1, ';
        $str_sql .= '  M41C01.CSRNM2, ';
        $str_sql .= '  M41C01.CSRRANK, ';
        $str_sql .= '  M41C01.DLRCSRNO, ';
        $str_sql .= '  M41C01.CSRAD1, ';
        $str_sql .= '  M41C01.CSRAD2, ';
        $str_sql .= '  M41C01.CSRAD3, ';
        $str_sql .= '  M41C01.BRTDT, ';
        $str_sql .= '  M41C01.CUS_HOM_TEL_ACD, ';
        $str_sql .= '  M41C01.CUS_HOM_TEL_CCD, ';
        $str_sql .= '  M41C01.CUS_HOM_TEL_KNY_NO, ';
        $str_sql .= '  M41C01.EMAIL1, ';
        $str_sql .= '  M41C01.MOB_TEL_ACD, ';
        $str_sql .= '  M41C01.MOB_TEL_CCD, ';
        $str_sql .= '  M41C01.MOB_TEL_KNY_NO, ';
        $str_sql .= '  M41C01.MOB_MAL, ';
        $str_sql .= '  M41C03.VCLNM, ';
        $str_sql .= '  M41C04.VIN_WMIVDS, ';
        $str_sql .= '  M41C04.VIN_VIS, ';
        $str_sql .= '  M41C04.SRV_SRVSTRCD, ';
        $str_sql .= '  M41C04.NKSIN1_SOKOKM, ';
        $str_sql .= '  M41C04.XH10CAID, ';
        $str_sql .= '  M41C04.XG11KOTEIID, ';
        $str_sql .= '  M41C04.DM_FKA_KB, ';
        $str_sql .= '  M41C04.XHKTGKBN, ';
        $str_sql .= '  M41C04.KEIYK_NM KYK_CUS_NM1, ';
        $str_sql .= '  M41C04.DLRCSRNO KYK_CUS_NO, ';

        $str_sql .= '  M27M01.KYOTN_RKN, ';
        $str_sql .= '  M29MA4.SYAIN_KNJ_SEI, ';
        $str_sql .= '  M29MA4.SYAIN_KNJ_MEI ';

        $str_sql .= 'FROM ';
        $str_sql .= '  M41C01, ';
        $str_sql .= '  M41C03, ';
        $str_sql .= '  M41C04, ';
        $str_sql .= '  M27M01, ';
        $str_sql .= '  M29MA4 ';
        $str_sql .= 'WHERE ';
        $str_sql .= '  M41C04.DLRCSRNO     = M41C01.DLRCSRNO ';
        $str_sql .= 'AND ';
        $str_sql .= '  M41C04.VIN_WMIVDS   = M41C03.VIN_WMIVDS ';
        $str_sql .= 'AND ';
        $str_sql .= '  M41C04.VIN_VIS      = M41C03.VIN_VIS ';
        $str_sql .= 'AND ';
        $str_sql .= '  M41C04.KNR_BUSMANCD = M29MA4.SYAIN_NO ';
        $str_sql .= 'AND ';
        $str_sql .= '  M41C04.KNR_STRCD    = M27M01.KYOTN_CD ';
        $str_sql .= 'AND ';
        $str_sql .= "  M41C04.ORDERNO       = '" . $CMN_NO . "' ";

        return $str_sql;
    }

    //（入庫促進活動　メニューTOP階層）
    public function m_select_menu_top_sql($allrow)
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= ' ITEMNAME1 ';
        $str_sql .= ' ,TEIKEI_CD ';
        $str_sql .= ' ,MENU_TYPE ';
        $str_sql .= ' ,ITEMNAME2 ';

        $str_sql .= 'FROM ';
        $str_sql .= 'HANTEITEIKEIMST ';
        $str_sql .= 'WHERE ';
        $str_sql .= 'TEIKEI_TYPE = 1   ';
        //20151208 UPDATE START
        //20151104 Yin UPD S
        //$str_sql .= "(END_DT IS NULL or END_DT >= SYSDATE)
        //$str_sql .= "(END_DT IS NULL or END_DT >= TO_CHAR(SYSDATE, 'YY-MM-DD HH:MM:SS') ) ";
        //20151104 Yin UPD E
        if ('' == $allrow) {
            $str_sql .= " AND  (END_DT IS NULL or TO_CHAR(END_DT, 'YYYYMMDD HHMISS') >= TO_CHAR(SYSDATE, 'YYYYMMDD HHMISS') ) ";
        }
        //20151208 UPDATE END
        $str_sql .= 'ORDER BY DISP_SEQ ';

        return $str_sql;
    }

    //----20220121 sun add s
    //----（代替・入庫見込　メニューTOP階層）
    public function m_select_menu_topdaitai_sql($allrow)
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= ' ITEMNAME1 ';
        $str_sql .= ' ,TEIKEI_CD ';
        $str_sql .= ' ,MENU_TYPE ';
        $str_sql .= ' ,ITEMNAME2 ';

        $str_sql .= 'FROM ';
        $str_sql .= 'HANTEITEIKEIMST ';
        $str_sql .= 'WHERE ';
        $str_sql .= 'TEIKEI_TYPE = 1   ';
        if ('' == $allrow) {
            $str_sql .= " AND  (END_DT IS NULL or TO_CHAR(END_DT, 'YYYYMMDD HHMISS') >= TO_CHAR(SYSDATE, 'YYYYMMDD HHMISS') ) ";
        }
        //代替促進予定
        $str_sql .= ' AND  ( TEIKEI_CD = 2000 ';
        //代替促進
        $str_sql .= ' OR (TEIKEI_CD >= 2400 AND TEIKEI_CD <= 2699) ';
        //代替確定
        $str_sql .= ' OR (TEIKEI_CD >= 0400 AND TEIKEI_CD <= 0499) ';
        //入庫促進
        $str_sql .= ' OR (TEIKEI_CD >= 2700 AND TEIKEI_CD <= 3200) ';
        //入庫確定
        $str_sql .= ' OR TEIKEI_CD = 0600 )';

        $str_sql .= 'ORDER BY DISP_SEQ ';

        return $str_sql;
    }

    //----20220121 sun add e

    //（最終結果　メニューTOP階層）
    public function m_select_menuLast_top_sql()
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= ' ITEMNAME1 ';
        $str_sql .= ' ,TEIKEI_CD ';
        $str_sql .= ' ,MENU_TYPE ';
        $str_sql .= ' ,ITEMNAME2 ';
        $str_sql .= 'FROM ';
        $str_sql .= 'HANTEITEIKEIMST ';
        $str_sql .= 'WHERE ';
        $str_sql .= 'TEIKEI_TYPE = 2   AND ';
        //20151208 UPDATE START
        //20151104 Yin UPD S
        //$str_sql .= "(END_DT IS NULL or END_DT >= SYSDATE)
        //$str_sql .= "(END_DT IS NULL or END_DT >= TO_CHAR(SYSDATE, 'YY-MM-DD HH:MM:SS') ) ";
        //20151104 Yin UPD E
        $str_sql .= "(END_DT IS NULL or TO_CHAR(END_DT, 'YYYYMMDD HHMISS') >= TO_CHAR(SYSDATE, 'YYYYMMDD HHMISS') ) ";
        //20151208 UPDATE END
        $str_sql .= 'ORDER BY DISP_SEQ ';

        return $str_sql;
    }

    //--- 20160127 li INS S

    //（入庫促進活動　メニューTOP階層）
    public function m_select_menu_top_sinsya_sql()
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= ' ITEMNAME1 ';
        $str_sql .= ' ,TEIKEI_CD ';
        $str_sql .= ' ,MENU_TYPE ';
        $str_sql .= ' ,ITEMNAME2 ';
        $str_sql .= 'FROM ';
        $str_sql .= 'HANTEITEIKEIMST ';
        $str_sql .= 'WHERE ';
        $str_sql .= 'TEIKEI_TYPE = 3   AND ';
        // $str_sql .= "MENU_TYPE = 0   AND ";
        $str_sql .= "(END_DT IS NULL or TO_CHAR(END_DT, 'YYYYMMDD HHMISS') >= TO_CHAR(SYSDATE, 'YYYYMMDD HHMISS') ) ";
        $str_sql .= 'ORDER BY DISP_SEQ ';

        return $str_sql;
    }

    //（入庫促進活動　メニューTOP階層） メニュー第2階層
    public function m_select_menuLast_top_sinsya_sql()
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= ' ITEMNAME1 ';
        $str_sql .= ' ,TEIKEI_CD ';
        $str_sql .= ' ,MENU_TYPE ';
        $str_sql .= ' ,ITEMNAME2 ';
        $str_sql .= 'FROM ';
        $str_sql .= 'HANTEITEIKEIMST ';
        $str_sql .= 'WHERE ';
        $str_sql .= 'TEIKEI_TYPE = 4   AND ';
        // $str_sql .= "MENU_TYPE = 1   AND ";
        $str_sql .= "(END_DT IS NULL or TO_CHAR(END_DT, 'YYYYMMDD HHMISS') >= TO_CHAR(SYSDATE, 'YYYYMMDD HHMISS') ) ";
        $str_sql .= 'ORDER BY DISP_SEQ ';

        return $str_sql;
    }

    //（最終結果　メニューTOP階層） 新車１ヶ月点検、新車６ヶ月点検判定追加
    public function m_select_menu_top1_sinsya_sql()
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= ' ITEMNAME1 ';
        $str_sql .= ' ,TEIKEI_CD ';
        $str_sql .= ' ,MENU_TYPE ';
        $str_sql .= ' ,ITEMNAME2 ';
        $str_sql .= 'FROM ';
        $str_sql .= 'HANTEITEIKEIMST ';
        $str_sql .= 'WHERE ';
        $str_sql .= 'TEIKEI_TYPE = 3   AND ';
        // $str_sql .= "MENU_TYPE = 0   AND ";
        $str_sql .= "(END_DT IS NULL or TO_CHAR(END_DT, 'YYYYMMDD HHMISS') >= TO_CHAR(SYSDATE, 'YYYYMMDD HHMISS') ) ";
        $str_sql .= 'ORDER BY DISP_SEQ ';

        return $str_sql;
    }

    //（最終結果　メニューTOP階層） 新車１ヶ月点検、新車６ヶ月点検判定追加 メニュー第2階層
    public function m_select_menuLast_top1_sinsya_sql()
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= ' ITEMNAME1 ';
        $str_sql .= ' ,TEIKEI_CD ';
        $str_sql .= ' ,MENU_TYPE ';
        $str_sql .= ' ,ITEMNAME2 ';
        $str_sql .= 'FROM ';
        $str_sql .= 'HANTEITEIKEIMST ';
        $str_sql .= 'WHERE ';
        $str_sql .= 'TEIKEI_TYPE = 4   AND ';
        // $str_sql .= "MENU_TYPE = 1   AND ";
        $str_sql .= "(END_DT IS NULL or TO_CHAR(END_DT, 'YYYYMMDD HHMISS') >= TO_CHAR(SYSDATE, 'YYYYMMDD HHMISS') ) ";
        $str_sql .= 'ORDER BY DISP_SEQ ';

        return $str_sql;
    }

    //20190227 YIN INS S
    public function m_select_menuLast_top_chuko_sql()
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= ' ITEMNAME1 ';
        $str_sql .= ' ,TEIKEI_CD ';
        $str_sql .= ' ,MENU_TYPE ';
        $str_sql .= ' ,ITEMNAME2 ';
        $str_sql .= 'FROM ';
        $str_sql .= 'HANTEITEIKEIMST ';
        $str_sql .= 'WHERE ';
        $str_sql .= 'TEIKEI_TYPE = 5   AND ';
        // $str_sql .= "MENU_TYPE = 1   AND ";
        $str_sql .= "(END_DT IS NULL or TO_CHAR(END_DT, 'YYYYMMDD HHMISS') >= TO_CHAR(SYSDATE, 'YYYYMMDD HHMISS') ) ";
        $str_sql .= 'ORDER BY DISP_SEQ ';

        return $str_sql;
    }

    //20190227 YIN INS E

    //（最終結果　メニューTOP階層 新車１ヶ月６ヶ月点検）  検索条件変更
    public function m_select_option_list2_sql($item_type)
    {
        $str_sql = '';
        $str_sql .= 'SELECT DISTINCT';
        $str_sql .= ' DISP_SEQ ';
        $str_sql .= ' ,ITEMNAME1 ';
        $str_sql .= ' ,TEIKEI_CD ';
        $str_sql .= ' ,MENU_TYPE ';
        $str_sql .= ' ,ITEMNAME2 ';
        $str_sql .= 'FROM ';
        $str_sql .= ' HANTEITEIKEIMST ';
        $str_sql .= 'WHERE 1 = 1 ';
        if ('' != $item_type) {
            $str_sql .= " AND TEIKEI_TYPE = '" . $item_type . "' ";
        }
        $str_sql .= " AND  MENU_TYPE = '0' ";
        $str_sql .= " AND (END_DT IS NULL or TO_CHAR(END_DT, 'YYYYMMDD HHMISS') >= TO_CHAR(SYSDATE, 'YYYYMMDD HHMISS') ) ";
        $str_sql .= ' ORDER BY DISP_SEQ ';

        return $str_sql;
    }

    //--- 20160127 li INS E

    //（最終結果　メニュー2階層3）
    public function m_select_accLast_sql($cd, $ym, $tancd, $tantomark, $busyocd)
    {
        $str_sql = '';
        $str_sql .= 'SELECT  ';

        $str_sql .= ' YYMM, ';
        if ('true' == $tantomark) {
            $str_sql .= ' KANRISLS, ';
        } else {
        }
        $str_sql .= " SUM(TO_NUMBER(CASE WHEN SUBSTR(KEKKA_CD,0,2) ='" . $cd . "' THEN '1' ELSE '0' END)) as KEKKA_CD ";

        $str_sql .= ' FROM (';
        $str_sql .= '  SELECT ';
        $str_sql .= '    HL.* ';
        $str_sql .= '  FROM ';
        $str_sql .= '    HANTEILST HL,';
        //20171225 Add Start
        $str_sql .= '       (';
        $str_sql .= '         SELECT ';
        $str_sql .= '           RTRIM(VIN_WMIVDS) SYADAI, ';
        $str_sql .= '           RTRIM(VIN_VIS) CARNO ';
        $str_sql .= '         FROM M41C04 ';

        $str_sql .= '    WHERE ';
        $str_sql .= '        (';
        $str_sql .= '          RTRIM(M41C04.MAS_DT) IS NULL';
        $str_sql .= '          OR';
        $str_sql .= '         (';
        $str_sql .= "           rtrim(M41C04.MAS_DT) >= to_char( add_months( to_date( substr('" . $ym . "',1,4)||'/'||substr('" . $ym . "',5,2)||'/01','yyyy/MM/dd' ) ,-7 ) ,'yyyyMMdd') ";
        $str_sql .= "           and M41C04.RUPD_RIYU_CD in ('02','86')";
        $str_sql .= '           and not exists ( ';
        $str_sql .= '          SELECT ';
        $str_sql .= '           NEWER.* ';
        $str_sql .= '          FROM ';
        $str_sql .= '           M41C04 NEWER ';
        $str_sql .= '         WHERE ';
        $str_sql .= '          NEWER.VIN_WMIVDS = M41C04.VIN_WMIVDS ';
        $str_sql .= '          and NEWER.VIN_VIS = M41C04.VIN_VIS ';
        $str_sql .= '          AND rtrim(NEWER.MAS_DT) IS NULL ) ';
        $str_sql .= '        )';
        $str_sql .= '       )';

        $str_sql .= '         AND ';
        $str_sql .= "          (( SUBSTR(KNR_STRCD,0,2)='" . substr($busyocd, 0, 2) . "') OR (SUBSTR(KNR_STRCD,0,2)<>'" . substr($busyocd, 0, 2) . "' AND SUBSTR(SRV_SRVSTRCD,0,2)='" . substr($busyocd, 0, 2) . "')) ";
        if ('true' == $tantomark) {
            $str_sql .= "     	 AND KNR_BUSMANCD='" . $tancd . "'";
        }
        $str_sql .= '       ) M41C04,';
        //20171225 Add End

        $str_sql .= '   (SELECT ';
        $str_sql .= '     SYAKENBI,YYMM,SYADAI,CARNO,MAX(REVISION) MAXREVISION ';
        $str_sql .= '    FROM ';
        $str_sql .= '     HANTEILST ';
        $str_sql .= '    WHERE ';
        $str_sql .= "         YYMM ='" . $ym . "'";
        //			$str_sql .= "     AND (( SUBSTR(KANRIBU,0,2)='" . substr($busyocd, 0, 2) . "') OR (SUBSTR(KANRIBU,0,2)<>'" . substr($busyocd, 0, 2) . "' AND SUBSTR(KANRISV,0,2)='" . substr($busyocd, 0, 2) . "')) ";
        //
        //			if ($tantomark == 'true')
        //			{
        //				$str_sql .= " AND KANRISLS='" . $tancd . "'";
        //			}
        $str_sql .= '    GROUP BY ';
        $str_sql .= '    SYAKENBI,YYMM,SYADAI,CARNO ';
        $str_sql .= '   ) HANTEILST_MAX ';
        $str_sql .= '  WHERE ';
        $str_sql .= '    M41C04.SYADAI = HL.SYADAI AND ';
        $str_sql .= '    M41C04.CARNO = HL.CARNO AND ';

        $str_sql .= '    HANTEILST_MAX.SYAKENBI = HL.SYAKENBI AND ';
        $str_sql .= '    HANTEILST_MAX.YYMM = HL.YYMM AND ';
        $str_sql .= '    HANTEILST_MAX.SYADAI = HL.SYADAI AND ';
        $str_sql .= '    HANTEILST_MAX.CARNO = HL.CARNO AND ';
        $str_sql .= '    HANTEILST_MAX.MAXREVISION =  HL.REVISION ';
        $str_sql .= '  ) HANTEILST ';

        $str_sql .= " WHERE SUBSTR(KEKKA_CD ,0,2) ='" . $cd . "'";
        $str_sql .= " AND YYMM                    ='" . $ym . "'";
        //			$str_sql .= "     AND (( SUBSTR(KANRIBU,0,2)='" . substr($busyocd, 0, 2) . "') OR (SUBSTR(KANRIBU,0,2)<>'" . substr($busyocd, 0, 2) . "' AND SUBSTR(KANRISV,0,2)='" . substr($busyocd, 0, 2) . "')) ";
        //
        //			if ($tantomark == 'true')
        //			{
        //				$str_sql .= " AND KANRISLS='" . $tancd . "'";
        //			}
        $str_sql .= ' GROUP BY ';
        $str_sql .= ' YYMM  ';
        if ('true' == $tantomark) {
            $str_sql .= ' ,KANRISLS ';
        } else {
        }

        return $str_sql;
    }

    //--- 20160127 li INS S
    //（最終結果　メニュー2階層3） 新車１ヶ月点検、新車６ヶ月点検判定追加
    public function m_select_accLast_sinsya_sql($cd, $ym, $tancd, $tantomark, $busyocd)
    {
        $str_sql = '';
        $str_sql .= 'SELECT  ';

        if ('true' == $tantomark) {
            $str_sql .= ' KANRISLS, ';
        } else {
        }
        $str_sql .= " SUM(TO_NUMBER(CASE WHEN SUBSTR(KEKKA6_CD,0,2) ='" . $cd . "' THEN '1' ELSE '0' END)) as KEKKA_CD ";

        $str_sql .= ' FROM (';
        $str_sql .= '  SELECT ';
        $str_sql .= '    HL.* ';
        $str_sql .= '  FROM ';
        $str_sql .= '    HANTEILST_SINSYA HL,';
        //20171225 Add Start
        $str_sql .= '       (';
        $str_sql .= '         SELECT ';
        $str_sql .= '           RTRIM(VIN_WMIVDS) SYADAI, ';
        $str_sql .= '           RTRIM(VIN_VIS) CARNO ';
        $str_sql .= '         FROM M41C04 ';

        $str_sql .= '    WHERE ';
        $str_sql .= '        (';
        $str_sql .= '           RTRIM(M41C04.MAS_DT) IS NULL';
        $str_sql .= '           OR';
        $str_sql .= '         (';
        $str_sql .= "          rtrim(M41C04.MAS_DT) >= to_char( add_months( to_date( substr('" . $ym . "',1,4)||'/'||substr('" . $ym . "',5,2)||'/01','yyyy/MM/dd' ) ,-7 ) ,'yyyyMMdd') ";
        $str_sql .= "          and M41C04.RUPD_RIYU_CD in ('02','86')";
        $str_sql .= '          and not exists ( ';
        $str_sql .= '          SELECT ';
        $str_sql .= '           NEWER.* ';
        $str_sql .= '          FROM ';
        $str_sql .= '           M41C04 NEWER ';
        $str_sql .= '         WHERE ';
        $str_sql .= '          NEWER.VIN_WMIVDS = M41C04.VIN_WMIVDS ';
        $str_sql .= '          and NEWER.VIN_VIS = M41C04.VIN_VIS ';
        $str_sql .= '          AND rtrim(NEWER.MAS_DT) IS NULL ) ';
        $str_sql .= '         )';
        $str_sql .= '        )';

        $str_sql .= '         AND ';
        $str_sql .= "          (( SUBSTR(KNR_STRCD,0,2)='" . substr($busyocd, 0, 2) . "') OR (SUBSTR(KNR_STRCD,0,2)<>'" . substr($busyocd, 0, 2) . "' AND SUBSTR(SRV_SRVSTRCD,0,2)='" . substr($busyocd, 0, 2) . "')) ";
        if ('true' == $tantomark) {
            $str_sql .= "     	 AND KNR_BUSMANCD='" . $tancd . "'";
        }
        $str_sql .= '       ) M41C04,';
        //20171225 Add End

        $str_sql .= '   (SELECT ';
        $str_sql .= '     SYADAI,CARNO,MAX(REVISION) MAXREVISION ';
        $str_sql .= '    FROM ';
        $str_sql .= '     HANTEILST_SINSYA ';
        //			$str_sql .= "    WHERE ";
        //			$str_sql .= "     (( SUBSTR(KANRIBU,0,2)='" . substr($busyocd, 0, 2) . "') OR (SUBSTR(KANRIBU,0,2)<>'" . substr($busyocd, 0, 2) . "' AND SUBSTR(KANRISV,0,2)='" . substr($busyocd, 0, 2) . "')) ";

        //			if ($tantomark == 'true')
        //			{
        //				$str_sql .= " AND KANRISLS='" . $tancd . "'";
        //			}

        $str_sql .= '    GROUP BY ';
        $str_sql .= '    SYADAI,CARNO ';
        $str_sql .= '   ) HANTEILST_SINSYA_MAX ';

        $str_sql .= '  WHERE ';
        //20171225 Add Start
        $str_sql .= '    M41C04.SYADAI = HL.SYADAI AND ';
        $str_sql .= '    M41C04..CARNO = HL.CARNO AND ';
        //20171225 Add End
        $str_sql .= '    HANTEILST_SINSYA_MAX.SYADAI = HL.SYADAI AND ';
        $str_sql .= '    HANTEILST_SINSYA_MAX.CARNO = HL.CARNO AND ';
        $str_sql .= '    HANTEILST_SINSYA_MAX.MAXREVISION =  HL.REVISION ';
        $str_sql .= '  ) HANTEILST_SINSYA ';

        $str_sql .= " WHERE SUBSTR(KEKKA6_CD ,0,2) ='" . $cd . "'";
        $str_sql .= " AND YYMM                    ='" . $ym . "'";
        //			$str_sql .= "     AND (( SUBSTR(KANRIBU,0,2)='" . substr($busyocd, 0, 2) . "') OR (SUBSTR(KANRIBU,0,2)<>'" . substr($busyocd, 0, 2) . "' AND SUBSTR(KANRISV,0,2)='" . substr($busyocd, 0, 2) . "')) ";
        //
        //			if ($tantomark == 'true')
        //			{
        //				$str_sql .= " AND KANRISLS='" . $tancd . "'";
        //			}
        $str_sql .= ' GROUP BY ';
        if ('true' == $tantomark) {
            $str_sql .= ' KANRISLS ';
        } else {
        }

        return $str_sql;
    }

    //--- 20160127 li INS E

    public function m_select_acc_sql($cd, $ym, $tancd, $tantomark, $busyocd)
    {
        $str_sql = '';
        $str_sql .= 'SELECT  /*+ USE_HASH( HANTEILST ) */ ';
        $str_sql .= ' HANTEILST.YYMM, ';
        if ('true' == $tantomark) {
            $str_sql .= ' HANTEILST.KANRISLS, ';
        } else {
        }

        $str_sql .= " SUM(TO_NUMBER(CASE WHEN SUBSTR(HANTEILST.HANTEI1_CD,0,2) ='" . $cd . "' THEN '1' ELSE '0' END)) as HANTEI1_CD,";
        $str_sql .= " SUM(TO_NUMBER(CASE WHEN SUBSTR(HANTEILST.HANTEI2_CD,0,2) ='" . $cd . "' THEN '1' ELSE '0' END)) as HANTEI2_CD,";
        $str_sql .= " SUM(TO_NUMBER(CASE WHEN SUBSTR(HANTEILST.HANTEI3_CD,0,2) ='" . $cd . "' THEN '1' ELSE '0' END)) as HANTEI3_CD,";
        $str_sql .= " SUM(TO_NUMBER(CASE WHEN SUBSTR(HANTEILST.HANTEI4_CD,0,2) ='" . $cd . "' THEN '1' ELSE '0' END)) as HANTEI4_CD,";
        $str_sql .= " SUM(TO_NUMBER(CASE WHEN SUBSTR(HANTEILST.HANTEI5_CD,0,2) ='" . $cd . "' THEN '1' ELSE '0' END)) as HANTEI5_CD,";
        $str_sql .= " SUM(TO_NUMBER(CASE WHEN SUBSTR(HANTEILST.HANTEI6_CD,0,2) ='" . $cd . "' THEN '1' ELSE '0' END)) as HANTEI6_CD,";
        $str_sql .= " SUM(TO_NUMBER(CASE WHEN SUBSTR(HANTEILST.HANTEI7_CD,0,2) ='" . $cd . "' THEN '1' ELSE '0' END)) as HANTEI7_CD ";

        $str_sql .= ' FROM (';
        $str_sql .= '  SELECT  /*+ USE_HASH( HANTEILST ) */ ';
        $str_sql .= '    HL.* ';
        $str_sql .= '  FROM ';
        $str_sql .= '    HANTEILST HL,';
        //20171225 Add Start
        $str_sql .= '       (';
        $str_sql .= '         SELECT ';
        $str_sql .= '           RTRIM(VIN_WMIVDS) SYADAI, ';
        $str_sql .= '           RTRIM(VIN_VIS) CARNO ';
        $str_sql .= '         FROM M41C04 ';

        $str_sql .= '    WHERE ';
        $str_sql .= '        (';
        $str_sql .= '          RTRIM(M41C04.MAS_DT) IS NULL';
        $str_sql .= '          OR';
        $str_sql .= '         (';
        $str_sql .= "          rtrim(M41C04.MAS_DT) >= to_char( add_months( to_date( substr('" . $ym . "',1,4)||'/'||substr('" . $ym . "',5,2)||'/01','yyyy/MM/dd' ) ,-7 ) ,'yyyyMMdd') ";
        $str_sql .= "          and M41C04.RUPD_RIYU_CD in ('02','86')";
        $str_sql .= '          and not exists ( ';
        $str_sql .= '          SELECT ';
        $str_sql .= '           NEWER.* ';
        $str_sql .= '          FROM ';
        $str_sql .= '           M41C04 NEWER ';
        $str_sql .= '         WHERE ';
        $str_sql .= '          NEWER.VIN_WMIVDS = M41C04.VIN_WMIVDS ';
        $str_sql .= '          and NEWER.VIN_VIS = M41C04.VIN_VIS ';
        $str_sql .= '          AND rtrim(NEWER.MAS_DT) IS NULL ) ';
        $str_sql .= '         )';
        $str_sql .= '        )';

        $str_sql .= '         AND ';
        $str_sql .= "          (( SUBSTR(KNR_STRCD,0,2)='" . substr($busyocd, 0, 2) . "') OR (SUBSTR(KNR_STRCD,0,2)<>'" . substr($busyocd, 0, 2) . "' AND SUBSTR(SRV_SRVSTRCD,0,2)='" . substr($busyocd, 0, 2) . "')) ";
        if ('true' == $tantomark) {
            $str_sql .= "     	 AND KNR_BUSMANCD='" . $tancd . "'";
        }
        $str_sql .= '       ) M41C04,';
        //20171225 Add End
        $str_sql .= '   (SELECT  /*+ USE_HASH( HANTEILST ) */ ';
        $str_sql .= '     SYAKENBI,YYMM,SYADAI,CARNO,MAX(REVISION) MAXREVISION ';
        $str_sql .= '    FROM ';
        $str_sql .= '     HANTEILST ';
        $str_sql .= '    WHERE ';
        $str_sql .= "     YYMM ='" . $ym . "'";

        //$str_sql .= "     AND (( SUBSTR(KANRIBU,0,2)='" . substr($busyocd, 0, 2) . "' ) OR (SUBSTR(KANRIBU,0,2)<>'" . substr($busyocd, 0, 2) . "' AND SUBSTR(KANRISV,0,2)='" . substr($busyocd, 0, 2) . "')) ";

        //			if ($tantomark == 'true')
        //			{
        //				$str_sql .= " AND KANRISLS='" . $tancd . "'";
        //			}
        $str_sql .= '    GROUP BY ';
        $str_sql .= '    SYAKENBI,YYMM,SYADAI,CARNO ';
        $str_sql .= '   ) HANTEILST_MAX ';
        $str_sql .= '  WHERE ';
        //20171225 Add Start
        $str_sql .= '    M41C04.SYADAI = HL.SYADAI AND ';
        $str_sql .= '    M41C04.CARNO = HL.CARNO AND ';
        //20171225 Add End
        $str_sql .= '    HANTEILST_MAX.SYAKENBI = HL.SYAKENBI AND ';
        $str_sql .= '    HANTEILST_MAX.YYMM = HL.YYMM AND ';
        $str_sql .= '    HANTEILST_MAX.SYADAI = HL.SYADAI AND ';
        $str_sql .= '    HANTEILST_MAX.CARNO = HL.CARNO AND ';
        $str_sql .= '    HANTEILST_MAX.MAXREVISION =  HL.REVISION ';

        $str_sql .= '  ) HANTEILST ';

        $str_sql .= ' WHERE ( ';
        $str_sql .= "  SUBSTR(HANTEILST.HANTEI1_CD,0,2)='" . $cd . "' OR ";
        $str_sql .= "  SUBSTR(HANTEILST.HANTEI2_CD,0,2)='" . $cd . "' OR ";
        $str_sql .= "  SUBSTR(HANTEILST.HANTEI3_CD,0,2)='" . $cd . "' OR ";
        $str_sql .= "  SUBSTR(HANTEILST.HANTEI4_CD,0,2)='" . $cd . "' OR ";
        $str_sql .= "  SUBSTR(HANTEILST.HANTEI5_CD,0,2)='" . $cd . "' OR ";
        $str_sql .= "  SUBSTR(HANTEILST.HANTEI6_CD,0,2)='" . $cd . "' OR ";
        $str_sql .= "  SUBSTR(HANTEILST.HANTEI7_CD,0,2)='" . $cd . "' ";
        $str_sql .= ' ) ';

        $str_sql .= " AND YYMM    = '" . $ym . "'";
        //			$str_sql .= "     AND (( SUBSTR(KANRIBU,0,2)='" . substr($busyocd, 0, 2) . "') OR (SUBSTR(KANRIBU,0,2)<>'" . substr($busyocd, 0, 2) . "' AND SUBSTR(KANRISV,0,2)='" . substr($busyocd, 0, 2) . "')) ";

        //			if ($tantomark == 'true')
        //			{
        //				$str_sql .= " AND KANRISLS='" . $tancd . "'";
        //			}
        $str_sql .= ' GROUP BY ';
        $str_sql .= ' HANTEILST.YYMM  ';

        if ('true' == $tantomark) {
            $str_sql .= ' ,HANTEILST.KANRISLS ';
        } else {
        }

        return $str_sql;
    }

    //--- 20160127 li INS S
    public function m_select_acc_sinsya_sql($cd, $ym, $tancd, $tantomark, $busyocd)
    {
        $str_sql = '';
        $str_sql .= 'SELECT  /*+ USE_HASH( HANTEILST_SINSYA ) */ ';
        if ('true' == $tantomark) {
            $str_sql .= ' HANTEILST_SINSYA.KANRISLS, ';
        } else {
        }

        $str_sql .= " '' as HANTEI1_CD,";
        $str_sql .= " '' as HANTEI2_CD,";
        $str_sql .= " '' as HANTEI3_CD,";
        $str_sql .= " '' as HANTEI4_CD,";
        $str_sql .= " '' as HANTEI5_CD,";
        $str_sql .= " '' as HANTEI6_CD,";
        $str_sql .= " SUM(TO_NUMBER(CASE WHEN SUBSTR(HANTEILST_SINSYA.KEKKA1_CD,0,2) ='" . $cd . "' THEN '1' ELSE '0' END)) as HANTEI7_CD ";

        $str_sql .= ' FROM (';
        $str_sql .= '  SELECT  /*+ USE_HASH( HANTEILST_SINSYA ) */ ';
        $str_sql .= '    HL.* ';
        $str_sql .= '  FROM ';
        $str_sql .= '    HANTEILST_SINSYA HL,';
        //20171225 Add Start
        $str_sql .= '       (';
        $str_sql .= '         SELECT ';
        $str_sql .= '           RTRIM(VIN_WMIVDS) SYADAI, ';
        $str_sql .= '           RTRIM(VIN_VIS) CARNO ';
        $str_sql .= '         FROM M41C04 ';

        $str_sql .= '    WHERE ';
        $str_sql .= '        (  RTRIM(M41C04.MAS_DT) IS NULL';
        $str_sql .= '          OR';
        $str_sql .= '         (';
        $str_sql .= "          rtrim(M41C04.MAS_DT) >= to_char( add_months( to_date( substr('" . $ym . "',1,4)||'/'||substr('" . $ym . "',5,2)||'/01','yyyy/MM/dd' ) ,-7 ) ,'yyyyMMdd') ";
        $str_sql .= "          and M41C04.RUPD_RIYU_CD in ('02','86')";
        $str_sql .= '          and not exists ( ';
        $str_sql .= '          SELECT ';
        $str_sql .= '           NEWER.* ';
        $str_sql .= '          FROM ';
        $str_sql .= '           M41C04 NEWER ';
        $str_sql .= '         WHERE ';
        $str_sql .= '          NEWER.VIN_WMIVDS = M41C04.VIN_WMIVDS ';
        $str_sql .= '          and NEWER.VIN_VIS = M41C04.VIN_VIS ';
        $str_sql .= '          AND rtrim(NEWER.MAS_DT) IS NULL ) ';
        $str_sql .= '         )';
        $str_sql .= '        )';

        $str_sql .= '         AND ';
        $str_sql .= "          (( SUBSTR(KNR_STRCD,0,2)='" . substr($busyocd, 0, 2) . "') OR (SUBSTR(KNR_STRCD,0,2)<>'" . substr($busyocd, 0, 2) . "' AND SUBSTR(SRV_SRVSTRCD,0,2)='" . substr($busyocd, 0, 2) . "')) ";
        if ('true' == $tantomark) {
            $str_sql .= "     	 AND KNR_BUSMANCD='" . $tancd . "'";
        }
        $str_sql .= '       ) M41C04,';
        //20171225 Add End

        $str_sql .= '   (SELECT  /*+ USE_HASH( HANTEILST_SINSYA ) */ ';
        $str_sql .= '     SYADAI,CARNO,MAX(REVISION) MAXREVISION ';
        $str_sql .= '    FROM ';
        $str_sql .= '     HANTEILST_SINSYA ';
        //			$str_sql .= "    WHERE ";
        //			$str_sql .= "     (( SUBSTR(KANRIBU,0,2)='" . substr($busyocd, 0, 2) . "' ) OR (SUBSTR(KANRIBU,0,2)<>'" . substr($busyocd, 0, 2) . "' AND SUBSTR(KANRISV,0,2)='" . substr($busyocd, 0, 2) . "')) ";
        //
        //			if ($tantomark == 'true')
        //			{
        //				$str_sql .= " AND KANRISLS='" . $tancd . "'";
        //			}
        $str_sql .= '    GROUP BY ';
        $str_sql .= '    SYADAI,CARNO ';
        $str_sql .= '   ) HANTEILST_SINSYA_MAX ';
        $str_sql .= '  WHERE ';
        //20171225 Add Start
        $str_sql .= '    M41C04.SYADAI = HL.SYADAI AND ';
        $str_sql .= '    M41C04.CARNO = HL.CARNO AND ';
        //20171225 Add End
        $str_sql .= '    HANTEILST_SINSYA_MAX.SYADAI = HL.SYADAI AND ';
        $str_sql .= '    HANTEILST_SINSYA_MAX.CARNO = HL.CARNO AND ';
        $str_sql .= '    HANTEILST_SINSYA_MAX.MAXREVISION =  HL.REVISION ';
        $str_sql .= '  ) HANTEILST_SINSYA ';

        $str_sql .= ' WHERE ( ';
        $str_sql .= "  SUBSTR(HANTEILST_SINSYA.KEKKA1_CD,0,2)='" . $cd . "' ";
        $str_sql .= ' ) ';

        //			$str_sql .= "     AND (( SUBSTR(KANRIBU,0,2)='" . substr($busyocd, 0, 2) . "') OR (SUBSTR(KANRIBU,0,2)<>'" . substr($busyocd, 0, 2) . "' AND SUBSTR(KANRISV,0,2)='" . substr($busyocd, 0, 2) . "')) ";
        //
        //			if ($tantomark == 'true')
        //			{
        //				$str_sql .= " AND KANRISLS='" . $tancd . "'";
        //			}

        if ('true' == $tantomark) {
            $str_sql .= ' GROUP BY ';
            $str_sql .= ' HANTEILST_SINSYA.KANRISLS ';
        } else {
        }

        return $str_sql;
    }

    //--- 20160127 li INS E

    /**
     * リモートホスト・契約者情報を取得する。
     *
     * @param {String} $CMN_NO契約者
     * @param {String} $tenpo_cd        店舗コード
     *
     * @return {String} select結果
     */
    public function m_select_KEIYAKUSYA($CMN_NO)
    {
        $str_sql = $this->m_select_KEIYAKUSYA_sql($CMN_NO);

        return parent::select($str_sql);
    }

    /**
     * リモートホスト・IPアドレス第３オクテットから店舗コードを取得する。
     *
     * @param {String} $ip リモートホスト・IPアドレス
     *
     * @return {String} select結果
     */
    //20150610 Update Start
    //    public function m_select_kyotn_cd($ip) {
    public function m_select_kyotn_cd($ip, $userid)
    {
        //20150610 Update End

        //20150610 Update Start
        //        $str_sql = $this -> m_select_kyotn_cd_sql($ip);
        $str_sql = $this->m_select_kyotn_cd_sql($ip, $userid);
        //20150610 Update End
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

    public function m_select_menu_top($allrow)
    {
        $str_sql = $this->m_select_menu_top_sql($allrow);

        return parent::select($str_sql);
    }

    //----20220121 sun add s
    public function m_select_menu_topdaitai($allrow)
    {
        $str_sql = $this->m_select_menu_topdaitai_sql($allrow);

        return parent::select($str_sql);
    }

    //----20220121 sun add e

    //20150820  Yuanjh ADD S.
    public function m_select_hantei()
    {
        $str_sql = $this->m_select_hantei_sql();

        return parent::select($str_sql);
    }

    public function m_select_hanteiteilkei()
    {
        $str_sql = $this->m_select_hanteiteilkei_sql();

        return parent::select($str_sql);
    }

    //--- 20160127 li INS S
    public function m_select_hanteiteilkei_sinsya()
    {
        $str_sql = $this->m_select_hanteiteilkei_sinsya_sql();

        return parent::select($str_sql);
    }

    //--- 20160127 li INS E
    //20160120 UPD S
    //public function m_select_resulthanteiteilkeiDetails($nen, $tuki, $hantei, $tantocd,$busyocd)
    //{
    //	$str_sql = $this -> m_select_resulthanteiteilkeiDetails_sql($nen, $tuki, $hantei, $tantocd,$busyocd);
    //}
    public function m_select_resulthanteiteilkeiDetails($nen, $tuki, $tantocd, $busyocd)
    {
        $str_sql = $this->m_select_resulthanteiteilkeiDetails_sql($nen, $tuki, $tantocd, $busyocd);

        return parent::select($str_sql);
    }

    //20160120 UPD E
    //20150820  Yuanjh ADD E.

    //--- 20160127 li INS S
    public function m_select_option_list2($item_type)
    {
        $str_sql = $this->m_select_option_list2_sql($item_type);

        return parent::select($str_sql);
    }

    public function m_select_resulthanteiteilkeiDetails_sinsya($nen, $tuki, $tantocd, $busyocd)
    {
        $str_sql = $this->m_select_resulthanteiteilkeiDetails_sinsya_sql($nen, $tuki, $tantocd, $busyocd);

        return parent::select($str_sql);
    }

    public function getAccLast_sinsya($cd, $ym, $tancd, $tantomark, $busyocd)
    {
        $str_sql = $this->m_select_accLast_sinsya_sql($cd, $ym, $tancd, $tantomark, $busyocd);

        return parent::select($str_sql);
    }

    public function getAcc_sinsya($cd, $ym, $tancd, $tantomark, $busyocd)
    {
        $str_sql = $this->m_select_acc_sinsya_sql($cd, $ym, $tancd, $tantomark, $busyocd);

        return parent::select($str_sql);
    }

    public function m_select_menu_top_sinsya()
    {
        $str_sql = $this->m_select_menu_top_sinsya_sql();

        return parent::select($str_sql);
    }

    public function m_select_menu_top1_sinsya()
    {
        $str_sql = $this->m_select_menu_top1_sinsya_sql();

        return parent::select($str_sql);
    }

    public function m_select_menuLast_top_sinsya()
    {
        $str_sql = $this->m_select_menuLast_top_sinsya_sql();

        return parent::select($str_sql);
    }

    public function m_select_menuLast_top1_sinsya()
    {
        $str_sql = $this->m_select_menuLast_top1_sinsya_sql();

        return parent::select($str_sql);
    }

    //--- 20160127 li INS E
    //20190227 YIN INS S
    public function m_select_menuLast_top_chuko()
    {
        $str_sql = $this->m_select_menuLast_top_chuko_sql();

        return parent::select($str_sql);
    }

    //20190227 YIN INS E

    public function m_select_menuLast_top()
    {
        $str_sql = $this->m_select_menuLast_top_sql();

        return parent::select($str_sql);
    }

    //$busyocd追加
    public function getAcc($cd, $ym, $tancd, $tantomark, $busyocd)
    {
        $str_sql = $this->m_select_acc_sql($cd, $ym, $tancd, $tantomark, $busyocd);

        return parent::select($str_sql);
    }

    public function getAccLast($cd, $ym, $tancd, $tantomark, $busyocd)
    {
        $str_sql = $this->m_select_accLast_sql($cd, $ym, $tancd, $tantomark, $busyocd);

        return parent::select($str_sql);
    }

    public function m_select_resulttittle()
    {
        $str_sql = $this->m_select_resulttittle_sql();

        return parent::select($str_sql);
    }

    //--- 20160127 li INS S
    public function m_select_resulttittle_sinsya($con4)
    {
        $str_sql = $this->m_select_resulttittle_sinsya_sql($con4);

        return parent::select($str_sql);
    }

    //--- 20160127 li INS E
}