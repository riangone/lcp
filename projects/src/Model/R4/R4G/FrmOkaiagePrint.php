<?php
/**
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug             内容                               担当
 * YYYYMMDD           #ID                     XXXXXX                            FCSDL
 * 20151201           #2281			      	  車両業務システム_要望対応           	 YIN
 * 20161008           #2575       納品請求書印刷処理改善                      yangyang
 * 20240613           bug              日本側変更のコード問題修正                    YIN
 * --------------------------------------------------------------------------------------------
 */
// 共通クラスの読込み

namespace App\Model\R4\R4G;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class FrmOkaiagePrint extends ClsComDb
{
    //*************************************
    // * SQL文
    //*************************************

    /***********************************************************************
           処 理 名：付属品明細にﾃﾞｰﾀが存在するか確認するためのSQL
           関 数 名：selectSqlOKaiageCnt
           引    数：チャックフラグ、注文書番号、登録日From、登録日To
           戻 り 値：SQL
           処理説明：付属品明細にﾃﾞｰﾀが存在するか確認するためのSQL
           ***********************************************************************/

    public function selectSqlOKaiageCnt($chkFlag, $txtCMN_NO, $cboStartDate, $cboEndDate)
    {
        $strsql = "SELECT CMN_NO FROM M41E10 ";
        if ($chkFlag == 2) {
            /* 20161008 yangyang upd s */
            // $strsql .= "WHERE    M41E10.CMN_NO = '" . $txtCMN_NO . "'";
            $strsql .= "WHERE M41E10.CMN_NO = '" . $txtCMN_NO[0] . "'";
            for ($i = 1; $i <= count($txtCMN_NO) - 1; $i++) {
                $strsql .= " OR M41E10.CMN_NO = '" . $txtCMN_NO[$i] . "'";
            }
            /* 20161008 yangyang upd e */
        } else {
            $strsql .= " LEFT JOIN M28T13 TRK ";
            $strsql .= " ON TRK.CHUMN_NO = M41E10.CMN_NO ";
            $strsql .= " WHERE M41E10.NAU_KB = '1' AND TRK.TOU_Y_DT BETWEEN '" . $cboStartDate . "' AND '" . $cboEndDate . "'";
            /************************** 2014-05-17 Add start *****************************/
            $strsql .= $this->fncSqlConditionsExclusion("M41E10");
            /************************** 2014-05-17 Add end *****************************/
            $strsql .= " UNION SELECT CMN_NO ";
            $strsql .= " FROM M41E10 ";
            $strsql .= " WHERE  NAU_KB = '2' AND SRY_URG_DT BETWEEN '" . $cboStartDate . "' AND '" . $cboEndDate . "'";
            /************************** 2014-05-17 Add start *****************************/
            $strsql .= $this->fncSqlConditionsExclusion("M41E10");
            /************************** 2014-05-17 Add end *****************************/
        }
        return $strsql;
    }

    /***********************************************************************
           処 理 名：注文書下取データをｶｳﾝﾄするsql文
           関 数 名：selectSqlSitadoriCount
           引    数：チャックフラグ、注文書番号、登録日From、登録日To
           戻 り 値：SQL
           処理説明：注文書下取データをｶｳﾝﾄするsql文取得
           ***********************************************************************/

    //--------------------- 2014-01-09 仕様変更 Delete start ----------------------
    /**************************************************************************************
           public function selectSqlSitadoriCount($chkFlag, $txtCMN_NO, $cboStartDate, $cboEndDate)
           {
           $sql_condition1 = "";
           $sql_condition2 = "";
           if ($chkFlag == 2)
           {
           $sql_condition1 = " WHERE CMN.NAU_KB = '1' ";
           $sql_condition1 .= " AND CMN.CMN_NO = '" . $txtCMN_NO . "' UNION ALL ";
           // 20240613 upd start
           //$sql_condition2 = " WHERE CMN.NAU_KB = '2' AND CMN.HNB_KTN_CD = '224' ";
           $sql_condition2 = " WHERE CMN.NAU_KB = '2' ";
          // 20240613 upd end

           $sql_condition2 .= " AND CMN.CMN_NO = '" . $txtCMN_NO . "' ";
           }
           else
           if ($chkFlag == 1)
           {
           $sql_condition1 = " WHERE CMN.NAU_KB = '1' ";
           $sql_condition1 .= " AND TRK.TOU_Y_DT BETWEEN '" . $cboStartDate . "' AND '" . $cboEndDate . "' UNION ALL ";
           $sql_condition2 = " WHERE CMN.NAU_KB = '2' ";
           $sql_condition2 .= " AND CMN.SRY_URG_DT BETWEEN '" . $cboStartDate . "' AND '" . $cboEndDate . "' ";
           // 20240613 upd start
           //$sql_condition2 .= " AND CMN.HNB_KTN_CD = '224' ";
           // 20240613 upd end
           }

           $strsql = " SELECT SIT.CMN_NO ";
           $strsql .= " FROM  M41E11 SIT ";
           $strsql .= " INNER JOIN M41E10 CMN ";
           $strsql .= " ON CMN.CMN_NO = SIT.CMN_NO ";
           $strsql .= " LEFT JOIN M28T13 TRK ";
           $strsql .= " ON CMN.CMN_NO = TRK.CHUMN_NO ";

           $sql_count = " SELECT CMN_NO, KENSU FROM ( ";
           $sql_count .= " SELECT CMN_NO , COUNT(CMN_NO) KENSU FROM ( ";

           $sql_count = $sql_count . $strsql . $sql_condition1 . $strsql . $sql_condition2;
           $sql_count .= " ) GROUP BY CMN_NO ) A WHERE KENSU > '1' ";

           return $sql_count;
           }

           **************************************************************************************/
    //--------------------- 2014-01-09 仕様変更 Delete end ----------------------

    /***********************************************************************
           処 理 名：付属品明細を抽出するSQL文
           関 数 名：selectSqlFuzokuMeisai
           引    数：チャックフラグ、注文書番号、登録日From、登録日To
           戻 り 値：SQL
           処理説明：付属品明細を抽出するSQL文取得
           ***********************************************************************/
    public function selectSqlFuzokuMeisai($chkFlag, $txtCMN_NO, $cboStartDate, $cboEndDate)
    {
        $strsql = "";
        if ($chkFlag == 2) {
            $strsql = "SELECT M41E10.CMN_NO, M41E12.YHN_NM ";
            $strsql .= " FROM M41E12 , M41E10 ";
            $strsql .= " WHERE M41E10.CMN_NO = M41E12.CMN_NO";
            /* 20161008 yangyang upd s */
            // $strsql .= " AND M41E10.CMN_NO = '" . $txtCMN_NO . "' ";
            $strsql .= " AND (M41E10.CMN_NO = '" . $txtCMN_NO[0] . "'";
            for ($i = 1; $i <= count($txtCMN_NO) - 1; $i++) {
                $strsql .= " OR M41E10.CMN_NO = '" . $txtCMN_NO[$i] . "'";
            }
            /* 20161008 yangyang upd e */
            /* 20161008 yangyang upd s */
            // $strsql .= " ORDER BY M41E12.CMN_NO ,M41E12.FZH_TKB_KSH_KB, M41E12.GYO_NO ";
            $strsql .= ") ORDER BY M41E12.CMN_NO ,M41E12.FZH_TKB_KSH_KB, M41E12.GYO_NO ";
            /* 20161008 yangyang upd e */
        } elseif ($chkFlag == 1) {
            $strsql = "SELECT A.CMN_NO , A.YHN_NM  FROM ";
            $strsql .= " (SELECT M41E10.CMN_NO, M41E12.YHN_NM, M41E12.FZH_TKB_KSH_KB, M41E12.GYO_NO ";
            $strsql .= " FROM M41E12 , M41E10 ";
            $strsql .= " LEFT JOIN M28T13 TRK ";
            $strsql .= " ON TRK.CHUMN_NO = M41E10.CMN_NO ";
            $strsql .= " WHERE M41E10.CMN_NO = M41E12.CMN_NO ";
            $strsql .= " AND M41E10.NAU_KB = '1' AND TRK.TOU_Y_DT BETWEEN '" . $cboStartDate . "' AND '" . $cboEndDate . "'";
            /************************** 2014-05-17 Add start *****************************/
            $strsql .= $this->fncSqlConditionsExclusion("M41E10");
            /************************** 2014-05-17 Add end *****************************/
            $strsql .= " UNION SELECT M41E10.CMN_NO, M41E12.YHN_NM, M41E12.FZH_TKB_KSH_KB, M41E12.GYO_NO ";
            $strsql .= " FROM M41E12 , M41E10 ";
            $strsql .= " WHERE M41E10.CMN_NO = M41E12.CMN_NO ";
            /************************** 2014-05-17 Edit start *****************************/
            //				$strsql .= " AND M41E10.NAU_KB = '2' AND M41E10.SRY_URG_DT BETWEEN '" . $cboStartDate . "' AND '" . $cboEndDate . "') A ";
            $strsql .= " AND M41E10.NAU_KB = '2' AND M41E10.SRY_URG_DT BETWEEN '" . $cboStartDate . "' AND '" . $cboEndDate . "' ";
            $strsql .= $this->fncSqlConditionsExclusion("M41E10");
            $strsql .= " ) A ";
            /************************** 2014-05-17 Edit end *****************************/
            $strsql .= " ORDER BY A.CMN_NO ,A.FZH_TKB_KSH_KB, A.GYO_NO ";
        }

        return $strsql;
    }

    /***********************************************************************
           処 理 名：お買い上げ明細をDELETEする
           関 数 名：deleteSqlOkaiage
           引    数：チャックフラグ、注文書番号、登録日From、登録日To
           戻 り 値：SQL
           処理説明：お買い上げ明細をDELETEする
           ***********************************************************************/
    public function deleteSqlOkaiage($chkFlag, $txtCMN_NO, $cboStartDate, $cboEndDat)
    {
        $strsql = " DELETE FROM HOKAIAGE ";
        if ($chkFlag == 2) {
            /* 20161008 yangyang upd s */
            // $strsql .= " WHERE CMN_NO = '" . $txtCMN_NO . "'";
            $strsql .= " WHERE CMN_NO IN ('" . $txtCMN_NO[0] . "'";
            for ($i = 1; $i <= count($txtCMN_NO) - 1; $i++) {
                $strsql .= " , '" . $txtCMN_NO[$i] . "'";
            }
            $strsql .= ")";
            /* 20161008 yangyang upd e */
        } else
            if ($chkFlag == 1) {
                // $cboStartDate = date_create($cboStartDate);
                // $cboEndDat = date_create($cboEndDat);
                // $cboStartDate = date_format($cboStartDate, "Ymd");
                // $cboEndDat = date_format($cboEndDat, "Ymd");

                $strsql .= " WHERE CMN_NO IN ";
                $strsql .= " (SELECT DISTINCT CMN_NO ";
                $strsql .= "   FROM ";
                $strsql .= "   (SELECT CMN.CMN_NO ";
                $strsql .= "     FROM M41E10 CMN ";
                $strsql .= "     LEFT JOIN M28T13 TRK ";
                $strsql .= "     ON TRK.CHUMN_NO = CMN.CMN_NO ";
                $strsql .= "     WHERE CMN.NAU_KB = '1'";
                $strsql .= "     AND TRK.TOU_Y_DT BETWEEN '" . $cboStartDate . "' AND '" . $cboEndDat . "' ";
                /************************** 2014-05-17 Add start *****************************/
                $strsql .= $this->fncSqlConditionsExclusion("CMN");
                /************************** 2014-05-17 Add end *****************************/
                $strsql .= "    UNION ALL ";
                $strsql .= "    SELECT CMN.CMN_NO ";
                $strsql .= "     FROM M41E10 CMN ";
                $strsql .= "     WHERE CMN.NAU_KB = '2' ";
                $strsql .= "     AND CMN.SRY_URG_DT BETWEEN '" . $cboStartDate . "' AND '" . $cboEndDat . "' ";
                /************************** 2014-05-17 Add start *****************************/
                $strsql .= $this->fncSqlConditionsExclusion("CMN");
                /************************** 2014-05-17 Add end *****************************/
                $strsql .= "    UNION ALL ";
                $strsql .= "    SELECT CMN_NO ";
                $strsql .= "     FROM HOKAIAGE ";
                $strsql .= "     WHERE TO_CHAR(URIAGEBI,'yyyymmdd') BETWEEN '" . $cboStartDate . "' AND '" . $cboEndDat . "') v)";
            }

        return $strsql;
    }

    /***********************************************************************
           処 理 名：お買い上げ明細にINSERTするSQL作成
           関 数 名：insertSqlOkaiageMSelect
           引    数：チャックフラグ、注文書番号、登録日From、登録日To
           戻 り 値：SQL
           処理説明：お買い上げ明細にINSERTするSQL作成
           ***********************************************************************/
    public function insertSqlOkaiageMSelect($chkFlag, $txtCMN_NO, $cboStartDate, $cboEndDate)
    {
        $strsql = " INSERT INTO  HOKAIAGE (CMN_NO ";
        $strsql .= " ,  URIAGEBI ";
        $strsql .= " ,  YUUBINNO ";
        $strsql .= " ,  ZYUUSYO1 ";
        $strsql .= " ,  ZYUUSYO2 ";
        $strsql .= " ,  ZYUUSYO3 ";
        $strsql .= " ,  KEIYAKU_NM1 ";
        $strsql .= " ,  KEIYAKU_NM2 ";
        $strsql .= " ,  SIYOU_NM ";
        $strsql .= " ,  KEIYAKU_CAR ";
        $strsql .= " ,  SYADAI_CAR_NO ";
        $strsql .= " ,  GENKIN ";
        $strsql .= " ,  KUREJIT_KAI ";
        $strsql .= " ,  KUREJIT_KIN ";
        $strsql .= " ,  TEGATA_KAI ";
        $strsql .= " ,  TEGATA_KIN ";
        $strsql .= " ,  SITADORI_SUMI_KIN ";
        $strsql .= " ,  SITADORI_SUMI_ZEI ";
        $strsql .= " ,  SYARYOU_KIN ";
        $strsql .= " ,  SYARYOU_NEBIKI ";
        $strsql .= " ,  SYARYOU_HIKIWATARI ";
        $strsql .= " ,  FUZOKU_KIN1 ";
        $strsql .= " ,  FUZOKU_KIN2 ";
        $strsql .= " ,  GENKIN_HANBAI_ZKOMI ";
        $strsql .= " ,  KAPPU_TESURYOU ";
        $strsql .= " ,  BETTO_SHIHARAI_KIN1 ";
        $strsql .= " ,  ZANSAI ";
        $strsql .= " ,  SIYOU_SUMI_RECYCLE ";
        $strsql .= " ,  YOTAKU_KIN ";
        $strsql .= " ,  PACK_DE_753 ";
        $strsql .= " ,  OSHIHARAIKEI ";
        $strsql .= " ,  UTIZEI_KIN ";
        $strsql .= " ,  NENSIKI ";
        $strsql .= " ,  MEIGARA ";
        $strsql .= " ,  SYAMEI ";
        $strsql .= " ,  TOUROKU_NO ";
        $strsql .= " ,  GINKO_NM1 ";
        $strsql .= " ,  GINKOSITEN_NM1 ";
        $strsql .= " ,  KOUZA_SYUBETU1 ";
        $strsql .= " ,  KOUZA_NO1 ";
        $strsql .= " ,  KOUZA_MEIGI1 ";
        $strsql .= " ,  GINKO_NM2 ";
        $strsql .= " ,  GINKOSITEN_NM2 ";
        $strsql .= " ,  KOUZA_SYUBETU2 ";
        $strsql .= " ,  KOUZA_NO2 ";
        $strsql .= " ,  KOUZA_MEIGI2 ";
        $strsql .= " ,  GINKO_NM3 ";
        $strsql .= " ,  GINKOSITEN_NM3 ";
        $strsql .= " ,  KOUZA_SYUBETU3 ";
        $strsql .= " ,  KOUZA_NO3 ";
        $strsql .= " ,  KOUZA_MEIGI3 ";
        $strsql .= " ,  FUZOKUMEISAI1 ";
        $strsql .= " ,  FUZOKUMEISAI2 ";
        $strsql .= " ,  FUZOKUMEISAI3 ";
        $strsql .= " ,  FUZOKUMEISAI4 ";
        $strsql .= " ,  FUZOKUMEISAI5 ";
        $strsql .= " ,  FUZOKUMEISAI6 ";
        $strsql .= " ,  FUZOKUMEISAI7 ";
        $strsql .= " ,  FUZOKUMEISAI8 ";
        $strsql .= " ,  FUZOKUMEISAI9 ";
        $strsql .= " ,  FUZOKUMEISAI10 ";
        $strsql .= " ,  FUZOKUMEISAI11 ";
        $strsql .= " ,  FUZOKUMEISAI12 ";
        $strsql .= " ,  FUZOKUMEISAI13 ";
        $strsql .= " ,  FUZOKUMEISAI14 ";
        $strsql .= " ,  FUZOKUMEISAI15 ";
        $strsql .= " ,  FUZOKUMEISAI16 ";
        $strsql .= " ,  FUZOKUMEISAI17 ";
        $strsql .= " ,  FUZOKUMEISAI18 ";
        $strsql .= " ,  FUZOKUMEISAI19 ";
        $strsql .= " ,  FUZOKUMEISAI20 ";
        $strsql .= " ,  FUZOKUMEISAI21 ";
        $strsql .= " ,  FUZOKUMEISAI22 ";
        $strsql .= " ,  FUZOKUMEISAI23 ";
        $strsql .= " ,  FUZOKUMEISAI24 ";
        $strsql .= " ,  FUZOKUMEISAI25 ";
        $strsql .= " ,  FUZOKUMEISAI26 ";
        $strsql .= " ,  JIDOUSYAZEI ";
        $strsql .= " ,  SYUTOKUZEI ";
        $strsql .= " ,  JYUURYOUZEI ";
        $strsql .= " ,  JIBAISEKI ";
        $strsql .= " ,  NINIHOKEN ";
        $strsql .= " ,  ZEIHOKENKEI ";
        $strsql .= " ,  SHOHIYOU_ZKOMI ";
        $strsql .= " ,  RECYCLEKANRI ";
        $strsql .= " ,  KAZEISYOUKEI ";
        $strsql .= " ,  AZUKARIHOUTEI ";
        $strsql .= " ,  RECYCLEAZUKARI ";
        $strsql .= " ,  HIKAZEISYOUKEI ";
        $strsql .= " ,  BETTO_SHIHARAI_KIN2 ";
        $strsql .= " ,  RECYCLEYOTAKU ";
        $strsql .= " ,  RECYCLEKANRI_ZKOMI ";
        $strsql .= " ,  CARKANRENHIKEI ";
        $strsql .= " ,  JIBAISEKI_MESSAGE ";
        $strsql .= " ,  SYAKO_MESSAGE ";
        $strsql .= " ,  HANBAIBUSYO ";
        $strsql .= " ,  STAFF ";
        $strsql .= " ,  HANBAIBUSYO_TEL ";
        $strsql .= " ,  KANRISERVICE ";
        $strsql .= " ,  UPD_DATE ";
        $strsql .= " ,  CREATE_DATE ";
        $strsql .= " ,  GENKINCHK ";
        $strsql .= " , UPD_SYA_CD ";
        $strsql .= " , UPD_PRG_ID ";
        $strsql .= " , UPD_CLT_NM ";
        $strsql .= " , PACK_DE_MENTE ";
        $strsql .= " , ENCHOU_HOSYOU ";

        /************************** 2014-01-09 Add start *****************************/
        //下取車複数台対応　
        $strsql .= " , NENSIKI2 ";
        $strsql .= " , MEIGARA2 ";
        $strsql .= " , SYAMEI2 ";
        $strsql .= " , TOUROKU_NO2 ";
        $strsql .= " , SITADORI_DAISU ";
        //下取車総台数
        /************************** 2014-01-09 Add end *****************************/
        /************************** 2014-03-14 Add start *****************************/
        $strsql .= " , SIN_SHZ_RT  ";
        $strsql .= " , CHU_SHZ_RT  ";
        $strsql .= " , UTIZEI_KIN2  ";
        $strsql .= " , LBL_UTIZEI_SIN_CALC  ";
        $strsql .= " , LBL_UTIZEI_CHU_CALC  ";
        $strsql .= " , LBL_UTIZEI_CHU_TANI  ";
        /************************** 2014-03-14 Add end *****************************/

        $strsql .= " ) ";

        $strsql .= $this->fncOkaiageSelectPart();

        if ($chkFlag == 2) {
            /* 20161008 yangyang upd s */
            // $strsql .= " WHERE CMN.NAU_KB = '1' AND CMN.CMN_NO = '" . $txtCMN_NO . "' UNION ";
            $strsql .= " WHERE CMN.NAU_KB = '1' AND (CMN.CMN_NO = '" . $txtCMN_NO[0] . "'";
            for ($i = 1; $i <= count($txtCMN_NO) - 1; $i++) {
                $strsql .= "OR CMN.CMN_NO = '" . $txtCMN_NO[$i] . "'";
            }
            $strsql .= ") UNION ";
            /* 20161008 yangyang upd e */
            $strsql .= $this->fncOkaiageSelectPart();
            /* 20161008 yangyang upd s */
            // 20240613 upd start
            // $strsql .= " WHERE CMN.NAU_KB = '2' AND CMN.HNB_KTN_CD = '224' AND CMN.CMN_NO = '" . $txtCMN_NO . "' ";
            //$strsql .= " WHERE CMN.NAU_KB = '2' AND CMN.HNB_KTN_CD = '224' AND (CMN.CMN_NO = '" . $txtCMN_NO[0] . "'";
            $strsql .= " WHERE CMN.NAU_KB = '2' AND (CMN.CMN_NO = '" . $txtCMN_NO[0] . "'";
            // 20240613 upd end
            for ($i = 1; $i <= count($txtCMN_NO) - 1; $i++) {
                $strsql .= "OR CMN.CMN_NO = '" . $txtCMN_NO[$i] . "'";
            }
            $strsql .= ")";
            /* 20161008 yangyang upd e */
        } elseif ($chkFlag == 1) {
            /************************** 2014-05-17 Edit start *****************************/
            //				$strsql .= " WHERE CMN.NAU_KB = '1' AND TRK.TOU_Y_DT BETWEEN '" . $cboStartDate . "' AND '" . $cboEndDate . "' UNION ";
            $strsql .= " WHERE CMN.NAU_KB = '1' AND TRK.TOU_Y_DT BETWEEN '" . $cboStartDate . "' AND '" . $cboEndDate . "' ";
            $strsql .= $this->fncSqlConditionsExclusion("CMN");
            $strsql .= " UNION ";
            /************************** 2014-05-17 Edit end *****************************/
            $strsql .= $this->fncOkaiageSelectPart();
            // 20240613 upd start
            //$strsql .= "  WHERE CMN.NAU_KB = '2' AND CMN.HNB_KTN_CD = '224' ";
            $strsql .= "  WHERE CMN.NAU_KB = '2' ";
            // 20240613 upd end

            $strsql .= " AND CMN.SRY_URG_DT BETWEEN '" . $cboStartDate . "' AND '" . $cboEndDate . "' ";
            /************************** 2014-05-17 Add start *****************************/
            $strsql .= $this->fncSqlConditionsExclusion("CMN");
            /************************** 2014-05-17 Add end *****************************/
        }
        return $strsql;
    }

    /***********************************************************************
           処 理 名：お買い上げ明細にINSERTするSQL作成(SELECT部分)
           関 数 名：fncOkaiageSelectPart
           引    数：無し
           戻 り 値：SQL
           処理説明：お買い上げ明細にINSERTするSQL作成(SELECT部分)
           ***********************************************************************/
    public function fncOkaiageSelectPart()
    {
        $strsql = " SELECT DISTINCT CMN.CMN_NO ";
        $strsql .= " , TO_DATE((CASE WHEN CMN.NAU_KB = '1' THEN TRK.TOU_Y_DT ELSE CMN.SRY_URG_DT END),'YYYY/MM/DD') URIAGEBI ";
        $strsql .= " , KYK_OKY.PSTMINNO YUUBINNO ";
        $strsql .= " , KYK_OKY.CSRAD1 ZYUUSYO1 ";
        $strsql .= " , KYK_OKY.CSRAD2 ZYUUSYO2 ";
        $strsql .= " , KYK_OKY.CSRAD3 ZYUUSYO3 ";
        $strsql .= " , KYK_OKY.CSRNM1 KEIYAKU_NM1 ";
        $strsql .= " , KYK_OKY.CSRNM2 KEIYKAU_NM2 ";
        $strsql .= " , SIY_OKY.CSRNM1 SIYOU_NM ";
        $strsql .= " , BASE.BASEH_KN KEIYAKU_CAR ";
        $strsql .= " , SUBSTRB((CASE WHEN JTU.CAR_NO IS NULL THEN JTU.SDAIKATA_CD ELSE (JTU.SDAIKATA_CD || '-' || JTU.CAR_NO) END),1,18) SYADAI_CAR_NO ";
        $strsql .= " , NVL(CMN.SHR_GKN_DPS,0) GENKIN ";
        $strsql .= " , CMN.KRJ_BUN_KSU KUREJIT_KAI ";
        $strsql .= " , CMN.KRJ_MOT_KIN KUREGIT_KIN ";
        $strsql .= " , CMN.TGT_MSU TEGATA_KAI ";
        $strsql .= " , CMN.KAP_MOT_KIN + NVL(CMN.KAP_TES, 0) TEGATA_KIN ";

        /******************************2014-01-10 Update start************************************/

        /*****************************************************************************************
                  $strsql .= " , NVL(SIT.TRA_GK,0) SITADORI_SUMI_KIN ";
                  $strsql .= " , (CASE WHEN SIT.KAZEI_KB <> 0 THEN NVL(SHZ_GKU,0) ELSE 0 END) SITADORI_SUMI_ZEI ";
                  *****************************************************************************************/

        /*下取車複数台対応*/
        $strsql .= " , SIT.TRA_GK SITADORI_SUMI_KIN ";
        $strsql .= " , SIT.SHZ_GKU SITADORI_SUMI_ZEI ";

        /******************************2014-01-10 Update end************************************/
        $strsql .= " , NVL(CMN.SRY_HTA_PRC_ZKM,0) SYARYOU_KIN ";

        // 2014.06.10 車両本体値引額取得方法変更対応 START
        //$strsql .= " , NVL(CMN.SRY_HTA_NBK_GKU_ZKM,'') SYARYOU_NEBIKI ";
        //$strsql .= " , NVL(CMN.SRY_HTA_PRC_ZKM,0) - NVL(CMN.SRY_HTA_NBK_GKU_ZKM,0) SYARYOU_HIKIWATARI ";
        $strsql .= " , NVL(CMN.SRY_NBK,'') SYARYOU_NEBIKI ";
        $strsql .= " , NVL(CMN.SRY_HTA_PRC_ZKM,0) - NVL(CMN.SRY_NBK,0) SYARYOU_HIKIWATARI ";
        // 2014.06.10 車両本体値引額取得方法変更対応 END
        $strsql .= " , NVL(CMN.FZH_SUM_GKU_ZKM,0) - NVL(CMN.FZH_NBK_SUM_GKU_ZKM,0) FUZOKU_KIN1 ";
        $strsql .= " , NVL(CMN.TKB_KSH_SUM_GKU_ZKM,0) - NVL(CMN.TKB_KSH_NBK_SUM_GKU_ZKM,0) FUZOKU_KIN2 ";
        // 2014.06.10 車両本体値引額取得方法変更対応 START
        //$strsql .= " , (NVL(CMN.SRY_HTA_PRC_ZKM,0) - NVL(CMN.SRY_HTA_NBK_GKU_ZKM,0)) + (NVL(CMN.FZH_SUM_GKU_ZKM,0) - NVL(CMN.FZH_NBK_SUM_GKU_ZKM,0)) + (NVL(CMN.TKB_KSH_SUM_GKU_ZKM,0) - NVL(CMN.TKB_KSH_NBK_SUM_GKU_ZKM,0)) GENKIN_HANBAI_ZKOMI ";
        $strsql .= " , (NVL(CMN.SRY_HTA_PRC_ZKM,0) - NVL(CMN.SRY_NBK,0)) + (NVL(CMN.FZH_SUM_GKU_ZKM,0) - NVL(CMN.FZH_NBK_SUM_GKU_ZKM,0)) + (NVL(CMN.TKB_KSH_SUM_GKU_ZKM,0) - NVL(CMN.TKB_KSH_NBK_SUM_GKU_ZKM,0)) GENKIN_HANBAI_ZKOMI ";
        // 2014.06.10 車両本体値引額取得方法変更対応 END
        $strsql .= " , NVL(CMN.KAP_TES,0) KAPPU_TESURYOU ";
        //20240905 UPD START
        //        $strsql .= " , (NVL(JIDOSYA.SCO_GK_ZEINK,0) + NVL(SYUTOKU.S_GAKU,0) + NVL(JYURYO.SCO_GK_ZEINK,0) + NVL(JIBAI.SCO_GK_ZEINK,0) + NVL(CMN.OPT_HOK_KIN,0)
        //               + NVL(TOU_SYOHI.SYOHIYO,0) + NVL(TOU_SYOHI_SIT.SYOHIYO_SIT,0) + NVL(RECYCLE.SCO_GK_ZKM,0) + NVL(AZUKARI.AZKARIHIYO,0)
        //               + NVL(SIT.MSY_TOU_AZK_HTE_HYO,0) + NVL(REC_AZU.SCO_GK_ZEINK,0)) BETTO_SHIHARAI_KIN1 ";
        $strsql .= " , (NVL(JIDOSYA.SCO_GK_ZEINK,0) + NVL(JIDOSYA_MEIHEN.SCO_GK_ZKM,0) + NVL(SYUTOKU.S_GAKU,0) + NVL(JYURYO.SCO_GK_ZEINK,0) + NVL(JIBAI.SCO_GK_ZEINK,0)  + NVL(JIBAI_MEIHEN.SCO_GK_ZKM,0) + NVL(CMN.OPT_HOK_KIN,0)
                      + NVL(TOU_SYOHI.SYOHIYO,0) + NVL(TOU_SYOHI_SIT.SYOHIYO_SIT,0) + NVL(RECYCLE.SCO_GK_ZKM,0) + NVL(AZUKARI.AZKARIHIYO,0)
                      + NVL(SIT.MSY_TOU_AZK_HTE_HYO,0) + NVL(REC_AZU.SCO_GK_ZEINK,0)) BETTO_SHIHARAI_KIN1 ";
        //20240905 UPD END

        $strsql .= " , NVL(CMN.TRA_CAR_ZSI_SUM,0) ZANSAI ";

        //20241121 UPD START
        //20241031 UPD START
        //20240905 UPD START
        //        $strsql .= " , NVL(SIT_SIY.RCYL_GK,0) SIYOU_SUMI_RECYCLE ";
        //        $strsql .= " , CASE WHEN NVL(JIDOSYA_MEIHEN.SCO_GK_ZKM,0) >0 THEN CMN.RCYL_GK ELSE NVL(SIT_SIY.RCYL_GK,0) END  SIYOU_SUMI_RECYCLE ";
        //20240905 UPD END
        //        $strsql .= " , CASE WHEN NVL(CMN.RCYL_GK,0) >0 THEN CMN.RCYL_GK ELSE NVL(SIT_SIY.RCYL_GK,0) END  SIYOU_SUMI_RECYCLE ";
        //20241031 UPD END
        //        $strsql .= " , CASE WHEN NVL(SIT_SIY.RCYL_GK,0)=0 AND NVL(CMN.RCYL_GK,0) >0 THEN CMN.RCYL_GK ELSE NVL(SIT_SIY.RCYL_GK,0) END  SIYOU_SUMI_RECYCLE ";
        //        $strsql .= " , CASE WHEN NVL(JIDOSYA_MEIHEN.SCO_GK_ZKM,0) >0 THEN CMN.RCYL_GK ELSE NVL(SIT_SIY.RCYL_GK,0) END  SIYOU_SUMI_RECYCLE ";
        $strsql .= " , CASE WHEN CMN.NAU_KB='2' AND NVL(SIT_SIY.RCYL_GK,0)=0 AND NVL(CMN.RCYL_GK,0) >0 THEN CMN.RCYL_GK ELSE NVL(SIT_SIY.RCYL_GK,0) END  SIYOU_SUMI_RECYCLE ";
        //20241121 UPD END

        /******************************2014-01-09 Update start************************************/
        //$strsql .= " , NVL(SIT_REC.YOTAK_GK,0) ";
        $strsql .= " , NVL(SIT_REC.YOTAK_GK,0) YOTAKU_KIN ";
        /******************************2014-01-09 Update end************************************/

        $strsql .= " , PACK_753.PACK_KIN ";
        // 2014.06.10 車両本体値引額取得方法変更対応 START
        //$strsql .= " , (NVL(CMN.SRY_HTA_PRC_ZKM,0) - NVL(CMN.SRY_HTA_NBK_GKU_ZKM,0)) + (NVL(CMN.FZH_SUM_GKU_ZKM,0) - NVL(CMN.FZH_NBK_SUM_GKU_ZKM,0)) + (NVL(CMN.TKB_KSH_SUM_GKU_ZKM,0) - NVL(CMN.TKB_KSH_NBK_SUM_GKU_ZKM,0)
        //   + NVL(CMN.KAP_TES,0) + (NVL(JIDOSYA.SCO_GK_ZEINK,0) + NVL(SYUTOKU.S_GAKU,0) + NVL(JYURYO.SCO_GK_ZEINK,0) + NVL(JIBAI.SCO_GK_ZEINK,0) + NVL(CMN.OPT_HOK_KIN,0)
        //   + NVL(TOU_SYOHI.SYOHIYO,0) + NVL(TOU_SYOHI_SIT.SYOHIYO_SIT,0)+ NVL(RECYCLE.SCO_GK_ZKM,0) + NVL(AZUKARI.AZKARIHIYO,0) + NVL(SIT.MSY_TOU_AZK_HTE_HYO,0)
        //   + NVL(REC_AZU.SCO_GK_ZEINK,0)) + NVL(CMN.TRA_CAR_ZSI_SUM,0) + NVL(SIT_SIY.RCYL_GK,0) - NVL(SIT_REC.YOTAK_GK,0)+NVL(PACK_753.PACK_KIN,0) + NVL(PACK_MENTE.MENTE_KIN,0) + NVL(ENCHOU.HOSYOU_KIN,0)) OSHIHARAIKEI ";

        //20241121 UPD START
        //20241031 UPD START
        //20240905 UPD START
        //        $strsql .= " , (NVL(CMN.SRY_HTA_PRC_ZKM,0) - NVL(CMN.SRY_NBK,0)) + (NVL(CMN.FZH_SUM_GKU_ZKM,0) - NVL(CMN.FZH_NBK_SUM_GKU_ZKM,0)) + (NVL(CMN.TKB_KSH_SUM_GKU_ZKM,0) - NVL(CMN.TKB_KSH_NBK_SUM_GKU_ZKM,0)
        //               + NVL(CMN.KAP_TES,0) + (NVL(JIDOSYA.SCO_GK_ZEINK,0) + NVL(SYUTOKU.S_GAKU,0) + NVL(JYURYO.SCO_GK_ZEINK,0) + NVL(JIBAI.SCO_GK_ZEINK,0) + NVL(CMN.OPT_HOK_KIN,0)
        //               + NVL(TOU_SYOHI.SYOHIYO,0) + NVL(TOU_SYOHI_SIT.SYOHIYO_SIT,0)+ NVL(RECYCLE.SCO_GK_ZKM,0) + NVL(AZUKARI.AZKARIHIYO,0) + NVL(SIT.MSY_TOU_AZK_HTE_HYO,0)
        //               + NVL(REC_AZU.SCO_GK_ZEINK,0)) + NVL(CMN.TRA_CAR_ZSI_SUM,0) + NVL(SIT_SIY.RCYL_GK,0) - NVL(SIT_REC.YOTAK_GK,0)+NVL(PACK_753.PACK_KIN,0) + NVL(PACK_MENTE.MENTE_KIN,0) + NVL(ENCHOU.HOSYOU_KIN,0)) OSHIHARAIKEI ";

        //        $strsql .= " , (NVL(CMN.SRY_HTA_PRC_ZKM,0) - NVL(CMN.SRY_NBK,0)) + (NVL(CMN.FZH_SUM_GKU_ZKM,0) - NVL(CMN.FZH_NBK_SUM_GKU_ZKM,0)) + (NVL(CMN.TKB_KSH_SUM_GKU_ZKM,0) - NVL(CMN.TKB_KSH_NBK_SUM_GKU_ZKM,0)
        //               + NVL(CMN.KAP_TES,0) + (NVL(JIDOSYA.SCO_GK_ZEINK,0) + NVL(JIDOSYA_MEIHEN.SCO_GK_ZKM,0) + NVL(SYUTOKU.S_GAKU,0) + NVL(JYURYO.SCO_GK_ZEINK,0) + NVL(JIBAI.SCO_GK_ZEINK,0)  + NVL(JIBAI_MEIHEN.SCO_GK_ZKM,0) + NVL(CMN.OPT_HOK_KIN,0)
        //               + NVL(TOU_SYOHI.SYOHIYO,0) + NVL(TOU_SYOHI_SIT.SYOHIYO_SIT,0)+ NVL(RECYCLE.SCO_GK_ZKM,0) + NVL(AZUKARI.AZKARIHIYO,0) + NVL(SIT.MSY_TOU_AZK_HTE_HYO,0)
        //               + NVL(REC_AZU.SCO_GK_ZEINK,0)) + NVL(CMN.TRA_CAR_ZSI_SUM,0) + CASE WHEN NVL(JIDOSYA_MEIHEN.SCO_GK_ZKM,0) >0 THEN CMN.RCYL_GK ELSE NVL(SIT_SIY.RCYL_GK,0) END - NVL(SIT_REC.YOTAK_GK,0)+NVL(PACK_753.PACK_KIN,0) + NVL(PACK_MENTE.MENTE_KIN,0) + NVL(ENCHOU.HOSYOU_KIN,0)) OSHIHARAIKEI ";

        //        $strsql .= " , (NVL(CMN.SRY_HTA_PRC_ZKM,0) - NVL(CMN.SRY_NBK,0)) + (NVL(CMN.FZH_SUM_GKU_ZKM,0) - NVL(CMN.FZH_NBK_SUM_GKU_ZKM,0)) + (NVL(CMN.TKB_KSH_SUM_GKU_ZKM,0) - NVL(CMN.TKB_KSH_NBK_SUM_GKU_ZKM,0)
        //               + NVL(CMN.KAP_TES,0) + (NVL(JIDOSYA.SCO_GK_ZEINK,0) + NVL(JIDOSYA_MEIHEN.SCO_GK_ZKM,0) + NVL(SYUTOKU.S_GAKU,0) + NVL(JYURYO.SCO_GK_ZEINK,0) + NVL(JIBAI.SCO_GK_ZEINK,0)  + NVL(JIBAI_MEIHEN.SCO_GK_ZKM,0) + NVL(CMN.OPT_HOK_KIN,0)
        //               + NVL(TOU_SYOHI.SYOHIYO,0) + NVL(TOU_SYOHI_SIT.SYOHIYO_SIT,0)+ NVL(RECYCLE.SCO_GK_ZKM,0) + NVL(AZUKARI.AZKARIHIYO,0) + NVL(SIT.MSY_TOU_AZK_HTE_HYO,0)
        //               + NVL(REC_AZU.SCO_GK_ZEINK,0)) + NVL(CMN.TRA_CAR_ZSI_SUM,0) + CASE WHEN  NVL(SIT_SIY.RCYL_GK,0)=0 AND NVL(CMN.RCYL_GK,0) >0 THEN CMN.RCYL_GK ELSE NVL(SIT_SIY.RCYL_GK,0) END - NVL(SIT_REC.YOTAK_GK,0)+NVL(PACK_753.PACK_KIN,0) + NVL(PACK_MENTE.MENTE_KIN,0) + NVL(ENCHOU.HOSYOU_KIN,0)) OSHIHARAIKEI ";

        //20240905 UPD END
        //        $strsql .= " , (NVL(CMN.SRY_HTA_PRC_ZKM,0) - NVL(CMN.SRY_NBK,0)) + (NVL(CMN.FZH_SUM_GKU_ZKM,0) - NVL(CMN.FZH_NBK_SUM_GKU_ZKM,0)) + (NVL(CMN.TKB_KSH_SUM_GKU_ZKM,0) - NVL(CMN.TKB_KSH_NBK_SUM_GKU_ZKM,0)
        //               + NVL(CMN.KAP_TES,0) + (NVL(JIDOSYA.SCO_GK_ZEINK,0) + NVL(JIDOSYA_MEIHEN.SCO_GK_ZKM,0) + NVL(SYUTOKU.S_GAKU,0) + NVL(JYURYO.SCO_GK_ZEINK,0) + NVL(JIBAI.SCO_GK_ZEINK,0)  + NVL(JIBAI_MEIHEN.SCO_GK_ZKM,0) + NVL(CMN.OPT_HOK_KIN,0)
        //               + NVL(TOU_SYOHI.SYOHIYO,0) + NVL(TOU_SYOHI_SIT.SYOHIYO_SIT,0)+ NVL(RECYCLE.SCO_GK_ZKM,0) + NVL(AZUKARI.AZKARIHIYO,0) + NVL(SIT.MSY_TOU_AZK_HTE_HYO,0)
        //               + NVL(REC_AZU.SCO_GK_ZEINK,0)) + NVL(CMN.TRA_CAR_ZSI_SUM,0) + CASE WHEN NVL(CMN.RCYL_GK,0) >0 THEN CMN.RCYL_GK ELSE NVL(SIT_SIY.RCYL_GK,0) END - NVL(SIT_REC.YOTAK_GK,0)+NVL(PACK_753.PACK_KIN,0) + NVL(PACK_MENTE.MENTE_KIN,0) + NVL(ENCHOU.HOSYOU_KIN,0)) OSHIHARAIKEI ";
        //20241031 UPD END

        $strsql .= " , (NVL(CMN.SRY_HTA_PRC_ZKM,0) - NVL(CMN.SRY_NBK,0)) + (NVL(CMN.FZH_SUM_GKU_ZKM,0) - NVL(CMN.FZH_NBK_SUM_GKU_ZKM,0)) + (NVL(CMN.TKB_KSH_SUM_GKU_ZKM,0) - NVL(CMN.TKB_KSH_NBK_SUM_GKU_ZKM,0)
                      + NVL(CMN.KAP_TES,0) + (NVL(JIDOSYA.SCO_GK_ZEINK,0) + NVL(JIDOSYA_MEIHEN.SCO_GK_ZKM,0) + NVL(SYUTOKU.S_GAKU,0) + NVL(JYURYO.SCO_GK_ZEINK,0) + NVL(JIBAI.SCO_GK_ZEINK,0)  + NVL(JIBAI_MEIHEN.SCO_GK_ZKM,0) + NVL(CMN.OPT_HOK_KIN,0)
                      + NVL(TOU_SYOHI.SYOHIYO,0) + NVL(TOU_SYOHI_SIT.SYOHIYO_SIT,0)+ NVL(RECYCLE.SCO_GK_ZKM,0) + NVL(AZUKARI.AZKARIHIYO,0) + NVL(SIT.MSY_TOU_AZK_HTE_HYO,0)
                      + NVL(REC_AZU.SCO_GK_ZEINK,0)) + NVL(CMN.TRA_CAR_ZSI_SUM,0) + CASE WHEN CMN.NAU_KB='2' AND NVL(SIT_SIY.RCYL_GK,0)=0 AND NVL(CMN.RCYL_GK,0) >0 THEN CMN.RCYL_GK ELSE NVL(SIT_SIY.RCYL_GK,0) END - NVL(SIT_REC.YOTAK_GK,0)+NVL(PACK_753.PACK_KIN,0) + NVL(PACK_MENTE.MENTE_KIN,0) + NVL(ENCHOU.HOSYOU_KIN,0)) OSHIHARAIKEI ";
        //20241121 UPD END



        // 2014.06.10 車両本体値引額取得方法変更対応 START

        /************************** 2014-03-14 Add start *****************************/
        //			$strsql .= " , TRUNC(((NVL(CMN.SRY_HTA_PRC_ZKM,0) - NVL(CMN.SRY_HTA_NBK_GKU_ZKM,0)) + (NVL(CMN.FZH_SUM_GKU_ZKM,0) - NVL(CMN.FZH_NBK_SUM_GKU_ZKM,0)) + (NVL(CMN.TKB_KSH_SUM_GKU_ZKM,0) - NVL(CMN.TKB_KSH_NBK_SUM_GKU_ZKM,0))
        //               + NVL(TOU_SYOHI.SYOHIYO,0) + NVL(TOU_SYOHI_SIT.SYOHIYO_SIT,0) + NVL(RECYCLE.SCO_GK_ZKM,0) + NVL(SIT.SHIKIN_KNR_RYOKIN,0)) * 5 / 105,0) UTIZEI_KIN ";
        //************************** 2014-03-20 Edit Start *****************************/
        //			$strsql .= " , CASE WHEN NVL(CMN.SHZ_RT,0) = NVL(CHU.SHZ_RT,0) or CHU.SHZ_RT IS NULL ";
        //			$strsql .= "   THEN ";
        //			$strsql .= "       TRUNC(((NVL(CMN.SRY_HTA_PRC_ZKM,0) - NVL(CMN.SRY_HTA_NBK_GKU_ZKM,0)) ";
        //			$strsql .= "            + (NVL(CMN.FZH_SUM_GKU_ZKM,0) - NVL(CMN.FZH_NBK_SUM_GKU_ZKM,0)) ";
        //			$strsql .= "            + (NVL(CMN.TKB_KSH_SUM_GKU_ZKM,0) - NVL(CMN.TKB_KSH_NBK_SUM_GKU_ZKM,0)) ";
        //			$strsql .= "            + NVL(TOU_SYOHI.SYOHIYO,0) + NVL(TOU_SYOHI_SIT.SYOHIYO_SIT,0) + NVL(RECYCLE.SCO_GK_ZKM,0) + NVL(SIT.SHIKIN_KNR_RYOKIN,0) ";
        //			$strsql .= "             ) * NVL(CMN.SHZ_RT,0) / (100 + NVL(CMN.SHZ_RT,0)) ";
        //			$strsql .= "            ,0) ";
        //			$strsql .= "   ELSE ";
        //			$strsql .= "       TRUNC(((NVL(CMN.SRY_HTA_PRC_ZKM,0) - NVL(CMN.SRY_HTA_NBK_GKU_ZKM,0)) ";
        //			$strsql .= "            + (NVL(CMN.FZH_SUM_GKU_ZKM,0) - NVL(CMN.FZH_NBK_SUM_GKU_ZKM,0)) ";
        //			$strsql .= "            + (NVL(CMN.TKB_KSH_SUM_GKU_ZKM,0) - NVL(CMN.TKB_KSH_NBK_SUM_GKU_ZKM,0)) ";
        //			$strsql .= "            + NVL(TOU_SYOHI.SYOHIYO,0) ";
        //			$strsql .= "             ) * NVL(CMN.SHZ_RT,0) / (100 + NVL(CMN.SHZ_RT,0)) ";
        //			$strsql .= "            ,0) ";
        //			$strsql .= "   END UTIZEI_KIN ";
        //内税計算の新車/新車手数料を分割
        $strsql .= " , CASE WHEN NVL(CMN.SHZ_RT,0) = NVL(CHU.SHZ_RT,0) or CHU.SHZ_RT IS NULL ";
        $strsql .= "   THEN ";
        $strsql .= "       TRUNC( ";
        //新車内税
        // 2014.06.10 車両本体値引額取得方法変更対応 START
        //$strsql .= "           ((NVL(CMN.SRY_HTA_PRC_ZKM,0) - NVL(CMN.SRY_HTA_NBK_GKU_ZKM,0)) ";
        $strsql .= "           ((NVL(CMN.SRY_HTA_PRC_ZKM,0) - NVL(CMN.SRY_NBK,0)) ";
        // 2014.06.10 車両本体値引額取得方法変更対応 END
        $strsql .= "            + (NVL(CMN.FZH_SUM_GKU_ZKM,0) - NVL(CMN.FZH_NBK_SUM_GKU_ZKM,0)) ";
        $strsql .= "            + (NVL(CMN.TKB_KSH_SUM_GKU_ZKM,0) - NVL(CMN.TKB_KSH_NBK_SUM_GKU_ZKM,0)) ";
        //下取車内税(税額が同じパターン)
        //20200306 UPDATE START
        //$strsql .= "           + NVL(RECYCLE.SCO_GK_ZKM,0) ";
        //20200306 UPDATE END

        //下取車諸費用内税(下取車と取得元が同じ)
        $strsql .= "           + NVL(TOU_SYOHI_SIT.SYOHIYO_SIT,0) + NVL(SIT.SHIKIN_KNR_RYOKIN,0) ";

        $strsql .= "           ) * NVL(CMN.SHZ_RT,0) / (100 + NVL(CMN.SHZ_RT,0))";
        //新車諸費用内税
        //20200306 UPDATE START
        //20231011 UPD START
        //			$strsql .= "           + (NVL(RECYCLE.SCO_GK_ZKM,0) * NVL(TOU_SYOHI.SHZ_RT,0) / (100 + NVL(TOU_SYOHI.SHZ_RT,10)))";
        $strsql .= "           + ( 0 * NVL(TOU_SYOHI.SHZ_RT,0) / (100 + NVL(TOU_SYOHI.SHZ_RT,10)))";
        //20231011 UPD END
        //20200306 UPDATE END
        $strsql .= "           + (NVL(TOU_SYOHI.SYOHIYO,0) * NVL(TOU_SYOHI.SHZ_RT,0) / (100 + NVL(TOU_SYOHI.SHZ_RT,0))) ";
        $strsql .= "       ,0) ";
        $strsql .= "   ELSE ";
        $strsql .= "       TRUNC(";
        //新車内税
        // 2014.06.10 車両本体値引額取得方法変更対応 START
        //$strsql .= "           ((NVL(CMN.SRY_HTA_PRC_ZKM,0) - NVL(CMN.SRY_HTA_NBK_GKU_ZKM,0)) ";
        $strsql .= "           ((NVL(CMN.SRY_HTA_PRC_ZKM,0) - NVL(CMN.SRY_NBK,0)) ";
        // 2014.06.10 車両本体値引額取得方法変更対応 END
        $strsql .= "            + (NVL(CMN.FZH_SUM_GKU_ZKM,0) - NVL(CMN.FZH_NBK_SUM_GKU_ZKM,0)) ";
        $strsql .= "            + (NVL(CMN.TKB_KSH_SUM_GKU_ZKM,0) - NVL(CMN.TKB_KSH_NBK_SUM_GKU_ZKM,0)) ";

        $strsql .= "           ) * NVL(CMN.SHZ_RT,0) / (100 + NVL(CMN.SHZ_RT,0)) ";
        //新車諸費用内税
        //20200306 UPDATE START
        //20231011 UPD START
        //			$strsql .= "           + (NVL(RECYCLE.SCO_GK_ZKM,0) * NVL(TOU_SYOHI.SHZ_RT,0) / (100 + NVL(TOU_SYOHI.SHZ_RT,0)))";
        $strsql .= "           + ( 0 * NVL(TOU_SYOHI.SHZ_RT,0) / (100 + NVL(TOU_SYOHI.SHZ_RT,0)))";
        //20231011 UPD END
        //20200306 UPDATE END
        $strsql .= "           + (NVL(TOU_SYOHI.SYOHIYO,0) * NVL(TOU_SYOHI.SHZ_RT,0) / (100 + NVL(TOU_SYOHI.SHZ_RT,0))) ";
        $strsql .= "       ,0) ";
        $strsql .= "   END UTIZEI_KIN ";
        //************************** 2014-03-20 Edit Start *****************************/
        /************************** 2014-03-14 Add end *****************************/

        /*****************************************2014-01-09 Update start*********************************************/

        /***********************************************************************************************
                  $strsql .= " , (CASE WHEN SIT.SYD_TOU_YM IS NULL THEN NULL ELSE SUBSTR(JPDATE(SIT.SYD_TOU_YM || '01'),1,3) END) NENSIKI	 ";
                  $strsql .= " , MGR.MEIGARA_MEI MEIGARA ";
                  $strsql .= " , SIT.VCLNM SYAMEI ";
                  $strsql .= " , SUBSTRB((CASE WHEN SIT.CAR_NO IS NULL THEN SIT.SDI_KAT ELSE (SIT.SDI_KAT || '-' || SIT.CAR_NO) END),1,18) TOUROKU_NO ";
                  *******************************************2014-01-09 Update end*******************************************************/

        //下取車複数台対応　>>>　1台目
        $strsql .= " , SIT1.NENSIKI ";
        /*年式*/
        $strsql .= " , MGR1.MEIGARA_MEI MEIGARA ";
        /*銘柄*/
        $strsql .= " , SIT1.SYAMEI ";
        /*車名*/
        $strsql .= " , SIT1.TOUROKU_NO ";
        /*登録ナンバー*/

        /**********************************************************************************************/

        $strsql .= " , O_MEI.GINKOU_NM_1 GINKO_NM1 ";
        $strsql .= " , O_MEI.GINKOUSITEN_NM_1 GINKOSITEN_NM1 ";
        $strsql .= " , O_MEI.KOUZA_SYU_1 KOUZA_SYUBETU1 ";
        $strsql .= " , O_MEI.KOUZA_NO_1 KOZA_NO1 ";
        $strsql .= " , O_MEI.KOUZA_MEIGI_1 KOUZA_MEIGI1 ";
        $strsql .= " , O_MEI.GINKOU_NM_2 GINKO_NM2 ";
        $strsql .= " , O_MEI.GINKOUSITEN_NM_2 GINKOSITEN_NM2 ";
        $strsql .= " , O_MEI.KOUZA_SYU_2 KOUZA_SYUBETU2 ";
        $strsql .= " , O_MEI.KOUZA_NO_2 KOZA_NO2 ";
        $strsql .= " , O_MEI.KOUZA_MEIGI_2 KOUZA_MEIGI2 ";
        $strsql .= " , O_MEI.GINKOU_NM_3 GINKO_NM3 ";
        $strsql .= " , O_MEI.GINKOUSITEN_NM_3 GINKOSITEN_NM3 ";
        $strsql .= " , O_MEI.KOUZA_SYU_3 KOUZA_SYUBETU3 ";
        $strsql .= " , O_MEI.KOUZA_NO_3 KOZA_NO3 ";
        $strsql .= " , O_MEI.KOUZA_MEIGI_3 KOUZA_MEIGI3 ";
        $strsql .= " , FZK.FUZOKUHIN_NM1 FUZOKUMEISAI1 ";
        $strsql .= " , FZK.FUZOKUHIN_NM2 FUZOKUMEISAI2 ";
        $strsql .= " , FZK.FUZOKUHIN_NM3 FUZOKUMEISAI3 ";
        $strsql .= " , FZK.FUZOKUHIN_NM4 FUZOKUMEISAI4 ";
        $strsql .= " , FZK.FUZOKUHIN_NM5 FUZOKUMEISAI5 ";
        $strsql .= " , FZK.FUZOKUHIN_NM6 FUZOKUMEISAI6 ";
        $strsql .= " , FZK.FUZOKUHIN_NM7 FUZOKUMEISAI7 ";
        $strsql .= " , FZK.FUZOKUHIN_NM8 FUZOKUMEISAI8 ";
        $strsql .= " , FZK.FUZOKUHIN_NM9 FUZOKUMEISAI9 ";
        $strsql .= " , FZK.FUZOKUHIN_NM10 FUZOKUMEISAI10 ";
        $strsql .= " , FZK.FUZOKUHIN_NM11 FUZOKUMEISAI11 ";
        $strsql .= " , FZK.FUZOKUHIN_NM12 FUZOKUMEISAI12 ";
        $strsql .= " , FZK.FUZOKUHIN_NM13 FUZOKUMEISAI13 ";
        $strsql .= " , FZK.FUZOKUHIN_NM14 FUZOKUMEISAI14 ";
        $strsql .= " , FZK.FUZOKUHIN_NM15 FUZOKUMEISAI15 ";
        $strsql .= " , FZK.FUZOKUHIN_NM16 FUZOKUMEISAI16 ";
        $strsql .= " , FZK.FUZOKUHIN_NM17 FUZOKUMEISAI17 ";
        $strsql .= " , FZK.FUZOKUHIN_NM18 FUZOKUMEISAI18 ";
        $strsql .= " , FZK.FUZOKUHIN_NM19 FUZOKUMEISAI19 ";
        $strsql .= " , FZK.FUZOKUHIN_NM20 FUZOKUMEISAI20 ";
        $strsql .= " , FZK.FUZOKUHIN_NM21 FUZOKUMEISAI21 ";
        $strsql .= " , FZK.FUZOKUHIN_NM22 FUZOKUMEISAI22 ";
        $strsql .= " , FZK.FUZOKUHIN_NM23 FUZOKUMEISAI23 ";
        $strsql .= " , FZK.FUZOKUHIN_NM24 FUZOKUMEISAI24 ";
        $strsql .= " , FZK.FUZOKUHIN_NM25 FUZOKUMEISAI25 ";
        $strsql .= " , FZK.FUZOKUHIN_NM26 FUZOKUMEISAI26 ";
        //20240905 UPD START
        //        $strsql .= " , NVL(JIDOSYA.SCO_GK_ZEINK,0) JIDOUSYAZEI ";
        $strsql .= " , NVL(JIDOSYA.SCO_GK_ZEINK,0)+NVL(JIDOSYA_MEIHEN.SCO_GK_ZKM,0)  JIDOUSYAZEI ";
        //20240905 UPD END
        $strsql .= " , NVL(SYUTOKU.S_GAKU,0) SYUTOKUZEI ";
        $strsql .= " , NVL(JYURYO.SCO_GK_ZEINK,0) JYUURYOUZEI ";

        //20240905 UPD START
        //        $strsql .= " , NVL(JIBAI.SCO_GK_ZEINK,0) JIBAISEKI ";
        $strsql .= " , NVL(JIBAI.SCO_GK_ZEINK,0) +NVL(JIBAI_MEIHEN.SCO_GK_ZKM,0)  JIBAISEKI ";
        //20240905 UPD END

        $strsql .= " , NVL(CMN.OPT_HOK_KIN,0) NINIHOKEN ";

        //20240905 UPD START
        //        $strsql .= " , NVL(JIDOSYA.SCO_GK_ZEINK,0) + NVL(SYUTOKU.S_GAKU,0) + NVL(JYURYO.SCO_GK_ZEINK,0) + NVL(JIBAI.SCO_GK_ZEINK,0) + NVL(CMN.OPT_HOK_KIN,0) ZEIHOKENKEI ";
        $strsql .= " , NVL(JIDOSYA.SCO_GK_ZEINK,0) + NVL(JIDOSYA_MEIHEN.SCO_GK_ZKM,0) + NVL(SYUTOKU.S_GAKU,0) + NVL(JYURYO.SCO_GK_ZEINK,0) + NVL(JIBAI.SCO_GK_ZEINK,0)  + NVL(JIBAI_MEIHEN.SCO_GK_ZKM,0) + NVL(CMN.OPT_HOK_KIN,0) ZEIHOKENKEI ";
        //20240905 UPD END

        $strsql .= " , NVL(TOU_SYOHI.SYOHIYO,0) + NVL(TOU_SYOHI_SIT.SYOHIYO_SIT,0) SHOHIYOU_ZKOMI ";

        //20231011 UPD START
        //			$strsql .= " , NVL(RECYCLE.SCO_GK_ZKM,0) RECYCLEKANRI ";
        $strsql .= " , 0 RECYCLEKANRI ";
        //20231011 UPD END

        //20231011 UPD START
        //			$strsql .= " , NVL(TOU_SYOHI.SYOHIYO,0) + NVL(TOU_SYOHI_SIT.SYOHIYO_SIT,0) + NVL(RECYCLE.SCO_GK_ZKM,0) KAZEISYOUKEI ";
        $strsql .= " , NVL(TOU_SYOHI.SYOHIYO,0) + NVL(TOU_SYOHI_SIT.SYOHIYO_SIT,0) KAZEISYOUKEI ";
        //20231011 UPD END

        $strsql .= " , NVL(AZUKARI.AZKARIHIYO,0) + NVL(SIT.MSY_TOU_AZK_HTE_HYO,0) AZUKARIHOUTEI ";

        //20231011 UPD START
        //			$strsql .= " , NVL(REC_AZU.SCO_GK_ZEINK,0) RECYCLEAZUKARI ";
        //        $strsql .= " , NVL(REC_AZU.SCO_GK_ZEINK,0) + NVL(RECYCLE.SCO_GK_ZKM,0) RECYCLEAZUKARI ";
        //20231011 UPD END
        $strsql .= " , NVL(REC_AZU.SCO_GK_ZEINK,0) + NVL(RECYCLE.SCO_GK_ZKM,0) RECYCLEAZUKARI ";


        //20231011 UPD START
        //			$strsql .= " , NVL(AZUKARI.AZKARIHIYO,0) + NVL(SIT.MSY_TOU_AZK_HTE_HYO,0) + NVL(REC_AZU.SCO_GK_ZEINK,0) HIKAZEISYOUKEI ";
        $strsql .= " , NVL(AZUKARI.AZKARIHIYO,0) + NVL(SIT.MSY_TOU_AZK_HTE_HYO,0) + NVL(REC_AZU.SCO_GK_ZEINK,0) +  NVL(RECYCLE.SCO_GK_ZKM,0)  HIKAZEISYOUKEI ";
        //20231011 UPD END


        //20240905 UPD START
        //        $strsql .= " , NVL(JIDOSYA.SCO_GK_ZEINK,0) + NVL(SYUTOKU.S_GAKU,0) + NVL(JYURYO.SCO_GK_ZEINK,0) + NVL(JIBAI.SCO_GK_ZEINK,0) + NVL(CMN.OPT_HOK_KIN,0)
        //             + NVL(TOU_SYOHI.SYOHIYO,0) + NVL(TOU_SYOHI_SIT.SYOHIYO_SIT,0) + NVL(RECYCLE.SCO_GK_ZKM,0) + NVL(AZUKARI.AZKARIHIYO,0)
        //             + NVL(SIT.MSY_TOU_AZK_HTE_HYO,0) + NVL(REC_AZU.SCO_GK_ZEINK,0) BETTO_SHIHARAI_KIN2 ";
        $strsql .= " , NVL(JIDOSYA.SCO_GK_ZEINK,0) + NVL(JIDOSYA_MEIHEN.SCO_GK_ZKM,0) + NVL(SYUTOKU.S_GAKU,0) + NVL(JYURYO.SCO_GK_ZEINK,0) + NVL(JIBAI.SCO_GK_ZEINK,0) + NVL(JIBAI_MEIHEN.SCO_GK_ZKM,0)  + NVL(CMN.OPT_HOK_KIN,0)
                    + NVL(TOU_SYOHI.SYOHIYO,0) + NVL(TOU_SYOHI_SIT.SYOHIYO_SIT,0) + NVL(RECYCLE.SCO_GK_ZKM,0) + NVL(AZUKARI.AZKARIHIYO,0)
                    + NVL(SIT.MSY_TOU_AZK_HTE_HYO,0) + NVL(REC_AZU.SCO_GK_ZEINK,0) BETTO_SHIHARAI_KIN2 ";
        //20240905 UPD END


        //20241121 UPD START
        //20240905 UPD START
        //        $strsql .= " , NVL(SIT_SIY.YOTAK_GK,0) RECYCLEYOTAKU ";
        //        $strsql .= " , NVL(SIT_SIY.YOTAK_GK,0) + CASE WHEN NVL(JIDOSYA_MEIHEN.SCO_GK_ZKM,0) >0 THEN CMN.RCYL_GK ELSE NVL(SIT_SIY.RCYL_GK,0) END  RECYCLEYOTAKU ";
        //20240905 UPD END
        //        $strsql .= " , NVL(SIT_SIY.YOTAK_GK,0) + CASE WHEN NVL(CMN.RCYL_GK ,0) >0 THEN CMN.RCYL_GK ELSE NVL(SIT_SIY.RCYL_GK,0) END  RECYCLEYOTAKU ";
        //        $strsql .= " , NVL(SIT_SIY.YOTAK_GK,0) + CASE WHEN NVL(JIDOSYA_MEIHEN.SCO_GK_ZKM,0) >0 THEN CMN.RCYL_GK ELSE NVL(SIT_SIY.RCYL_GK,0) END  RECYCLEYOTAKU ";
        $strsql .= " , NVL(SIT_SIY.YOTAK_GK,0) + CASE WHEN CMN.NAU_KB='2' AND NVL(SIT_SIY.YOTAK_GK,0)=0 AND NVL(CMN.RCYL_GK ,0) >0 THEN CMN.RCYL_GK ELSE NVL(SIT_SIY.RCYL_GK,0) END  RECYCLEYOTAKU ";
        //20241121 UPD END

        $strsql .= " , NVL(SIT.SHIKIN_KNR_RYOKIN,0) RECYCLEKANRI_ZKOMI ";

        //20241121 UPD START
        //20241031 UPD START
        //20240905 UPD START
        //        $strsql .= " , NVL(SIT_SIY.RCYL_GK,0) CARKANRENHIKEI ";
        //        $strsql .= " , CASE WHEN NVL(JIDOSYA_MEIHEN.SCO_GK_ZKM,0) >0 THEN  CMN.RCYL_GK ELSE NVL(SIT_SIY.RCYL_GK,0) END CARKANRENHIKEI ";
        //20240905 UPD END
        //        $strsql .= " , CASE WHEN NVL(CMN.RCYL_GK ,0) >0 THEN  CMN.RCYL_GK ELSE NVL(SIT_SIY.RCYL_GK,0) END CARKANRENHIKEI ";
        //20241031 UPD END
        //        $strsql .= " , CASE WHEN  NVL(SIT_SIY.RCYL_GK,0)=0 AND NVL(CMN.RCYL_GK,0) >0 THEN  CMN.RCYL_GK ELSE NVL(SIT_SIY.RCYL_GK,0) END CARKANRENHIKEI ";
        //      $strsql .= " , CASE WHEN NVL(JIDOSYA_MEIHEN.SCO_GK_ZKM,0) >0 THEN  CMN.RCYL_GK ELSE NVL(SIT_SIY.RCYL_GK,0) END CARKANRENHIKEI ";
        $strsql .= " , CASE WHEN CMN.NAU_KB='2' AND NVL(SIT_SIY.RCYL_GK,0)=0 AND NVL(CMN.RCYL_GK,0) >0 THEN  CMN.RCYL_GK ELSE NVL(SIT_SIY.RCYL_GK,0) END CARKANRENHIKEI ";

        //20241121 UPD END

        $strsql .= " , (CASE WHEN (NVL(JIDOSYA.SCO_GK_ZEINK,0) + NVL(SYUTOKU.S_GAKU,0)
                    + NVL(JYURYO.SCO_GK_ZEINK,0) + NVL(JIBAI.SCO_GK_ZEINK,0) + NVL(CMN.OPT_HOK_KIN,0)) = 0
                    THEN 'お客様で加入' END) JIBAISEKI_MESSAGE ";
        $strsql .= " , (CASE WHEN SYAKOSYO.GK = 0 THEN 'お客様で手続き' END) SYAKO_MESSAGE ";
        $strsql .= " , O_MEI.BUSYO_NM HANBAIBUSYO ";
        $strsql .= " , (SYAIN.SYAIN_KNJ_SEI || ' ' || SYAIN.SYAIN_KNJ_MEI) STAFF ";
        $strsql .= " , O_MEI.BUSYO_TEL ";
        $strsql .= " , SVC.BUSYO_NM KANRISERVICE ";
        $strsql .= " , SYSDATE UPD_DATE ";
        $strsql .= " ,  SYSDATE CREATE_DATE ";
        $strsql .= " , NVL(CMN.SHR_GKN_DPS,0) - NVL(CMN.SIY_SMI_CAR_KNR_HYO,0) + NVL(SIT_REC.YOTAK_GK,0) GENKINCHK ";
        $strsql .= " , '@UPDUSER' ";
        $strsql .= " , '@UPDAPP' ";
        $strsql .= " , '@UPDCLT' ";
        $strsql .= " , PACK_MENTE.MENTE_KIN ";
        $strsql .= " , ENCHOU.HOSYOU_KIN ";

        /*****************************************2014-01-09 Add start*********************************************/
        //下取車複数台対応　>>>　1台目
        $strsql .= " , SIT2.NENSIKI ";
        /*年式*/
        $strsql .= " , MGR2.MEIGARA_MEI MEIGARA ";
        /*銘柄*/
        $strsql .= " , SIT2.SYAMEI ";
        /*車名*/
        $strsql .= " , SIT2.TOUROKU_NO ";
        /*登録ナンバー*/
        $strsql .= " , SIT.DAISU ";
        /*下取車台数*/
        /*****************************************2014-01-09 Add end*********************************************/
        /************************** 2014-03-14 Add start *****************************/
        $strsql .= " , CMN.SHZ_RT SIN_SHZ_RT  ";
        $strsql .= " , CHU.SHZ_RT CHU_SHZ_RT  ";
        //20231011 UPD START
        //			$strsql .= " , TRUNC(((NVL(TOU_SYOHI_SIT.SYOHIYO_SIT,0) + NVL(RECYCLE.SCO_GK_ZKM,0) + NVL(SIT.SHIKIN_KNR_RYOKIN,0) ";
        $strsql .= " , TRUNC(((NVL(TOU_SYOHI_SIT.SYOHIYO_SIT,0) + 0 + NVL(SIT.SHIKIN_KNR_RYOKIN,0) ";
        //20231011 UPD END
        $strsql .= "   ) * NVL(CHU.SHZ_RT,0) / (100 + NVL(CHU.SHZ_RT,0))) ";
        $strsql .= " ,0) UTIZEI_KIN2 ";
        /************************** 2014-03-14 Add end *****************************/
        /************************** 2014-03-20 Add start *****************************/
        //			$strsql .= " , '内支払消費税等合計額(ABC)×' || NVL(CMN.SHZ_RT,0) || '／' || TO_CHAR(100 + NVL(CMN.SHZ_RT,0)) LBL_UTIZEI_SIN_CALC  ";
        $strsql .= " , CASE ";
        $strsql .= "   WHEN NVL(CMN.SHZ_RT,0) = 0 AND NVL(TOU_SYOHI.SHZ_RT,0) != 0 THEN ";
        $strsql .= "       '内支払消費税等合計額(B)×' || NVL(TOU_SYOHI.SHZ_RT,0) || '／' || TO_CHAR(100 + NVL(TOU_SYOHI.SHZ_RT,0)) ";

        $strsql .= "   WHEN NVL(CMN.SHZ_RT,0) != NVL(CHU.SHZ_RT,0) AND NVL(CMN.SHZ_RT,0) = NVL(TOU_SYOHI.SHZ_RT,0) THEN ";
        $strsql .= "       '内支払消費税等合計額(AB)×' || NVL(TOU_SYOHI.SHZ_RT,0) || '／' || TO_CHAR(100 + NVL(TOU_SYOHI.SHZ_RT,0)) ";

        $strsql .= "   ELSE '内支払消費税等合計額(ABC)×' || NVL(CMN.SHZ_RT,0) || '／' || TO_CHAR(100 + NVL(CMN.SHZ_RT,0)) ";
        $strsql .= "   END LBL_UTIZEI_SIN_CALC  ";
        /************************** 2014-03-20 Add end *****************************/
        /************************** 2014-03-14 Add start *****************************/
        $strsql .= " , CASE WHEN NVL(CMN.SHZ_RT,0) = NVL(CHU.SHZ_RT,0) or CHU.SHZ_RT IS NULL ";
        $strsql .= "   THEN ''   ";
        /************************** 2014-03-20 Add start *****************************/
        //			$strsql .= "   ELSE '内支払消費税等合計額(ABC)×' || NVL(CHU.SHZ_RT,'0') || '／' || TO_CHAR(100 + NVL(CHU.SHZ_RT,0)) ";
        $strsql .= "   ELSE '内支払消費税等合計額(BC)×' || NVL(CHU.SHZ_RT,'0') || '／' || TO_CHAR(100 + NVL(CHU.SHZ_RT,0)) ";
        /************************** 2014-03-20 Add start *****************************/
        $strsql .= "   END LBL_UTIZEI_CHU_CALC  ";
        $strsql .= " , CASE WHEN NVL(CMN.SHZ_RT,0) = NVL(CHU.SHZ_RT,0) or CHU.SHZ_RT IS NULL  ";
        $strsql .= "   THEN '' ";
        $strsql .= "   ELSE '円' ";
        $strsql .= "   END LBL_UTIZEI_CHU_TANI ";
        /************************** 2014-03-14 Add end *****************************/

        $strsql .= "  FROM M41E10 CMN ";
        $strsql .= "  LEFT JOIN M28T13 TRK ";
        $strsql .= "  ON TRK.CHUMN_NO = CMN.CMN_NO ";
        $strsql .= "  LEFT JOIN M41C01 KYK_OKY ";
        $strsql .= "  ON CMN.KYK_CUS_NO = KYK_OKY.DLRCSRNO ";
        $strsql .= "  LEFT JOIN M41C01 SIY_OKY ";
        $strsql .= "  ON CMN.SIY_CUS_NO = SIY_OKY.DLRCSRNO ";
        $strsql .= "  LEFT JOIN M27AM1 BASE ";
        $strsql .= "  ON BASE.BASEH_CD = CMN.MOD_CD ";
        $strsql .= "  LEFT JOIN M27A01 JTU ";
        $strsql .= "  ON JTU.JUCHU_NO = CMN.JTU_NO ";

        /*****************************************2014-01-09 Update start*********************************************/

        /*************************************************************************************************************
                  $strsql .= "  LEFT JOIN M41E11 SIT ";
                  $strsql .= "  ON SIT.CMN_NO = CMN.CMN_NO ";
                  $strsql .= "  LEFT JOIN (SELECT CMN_NO , RCYL_GK, YOTAK_GK ";
                  $strsql .= "  FROM M41E11 ";
                  $strsql .= "  WHERE NVL(SHIKIN_KNR_RYOKIN,0) <> 0) SIT_SIY ";
                  $strsql .= "  ON SIT_SIY.CMN_NO = CMN.CMN_NO ";
                  $strsql .= "  LEFT JOIN (SELECT CMN_NO, YOTAK_GK FROM M41E11 ";
                  $strsql .= "  WHERE  NVL(SHIKIN_KNR_RYOKIN,0) = 0) SIT_REC ";
                  $strsql .= "  ON SIT_REC.CMN_NO = CMN.CMN_NO ";
                  **************************************************************************************************************/

        /*
         * 下取車複数台対応
         * 中古車下取
         */

        $strsql .= "  LEFT JOIN (SELECT CMN_NO ";
        $strsql .= "  ,SUM(NVL(TRA_GK,0)) TRA_GK ";
        $strsql .= "  ,SUM(CASE WHEN KAZEI_KB <> 0 THEN NVL(SHZ_GKU,0) ELSE 0 END) SHZ_GKU ";
        $strsql .= "  ,SUM(NVL(SHIKIN_KNR_RYOKIN,0)) SHIKIN_KNR_RYOKIN ";
        $strsql .= "  ,SUM(NVL(MSY_TOU_AZK_HTE_HYO,0)) MSY_TOU_AZK_HTE_HYO ";
        $strsql .= "  ,COUNT(CMN_NO) DAISU ";
        $strsql .= "  FROM  M41E11 ";
        $strsql .= "  GROUP BY CMN_NO) SIT ";
        $strsql .= "  ON SIT.CMN_NO = CMN.CMN_NO ";

        /*
         * 中古車下取り使用済み自動車リサイクル費用④
         */

        $strsql .= "  LEFT JOIN (SELECT CMN_NO ";
        $strsql .= "  ,SUM(NVL(RCYL_GK,0)) RCYL_GK ";
        $strsql .= "  ,SUM(NVL(YOTAK_GK,0)) YOTAK_GK ";
        $strsql .= "   FROM M41E11 ";
        $strsql .= "  WHERE  NVL(SHIKIN_KNR_RYOKIN,0) <> 0 ";
        $strsql .= "  GROUP BY CMN_NO) SIT_SIY ";
        $strsql .= "  ON SIT_SIY.CMN_NO = CMN.CMN_NO ";

        /*
         * 中古車下取リサイクル預託金合計
         */

        $strsql .= "  LEFT JOIN (SELECT CMN_NO ";
        $strsql .= "  ,SUM(NVL(YOTAK_GK,0)) YOTAK_GK ";
        $strsql .= "  FROM   M41E11 ";
        $strsql .= "  WHERE  NVL(SHIKIN_KNR_RYOKIN,0) = 0 ";
        $strsql .= "  GROUP BY CMN_NO) SIT_REC ";
        $strsql .= "  ON SIT_REC.CMN_NO = CMN.CMN_NO ";

        /*****************************************2014-01-09 Update end*********************************************/

        $strsql .= "  LEFT JOIN (SELECT CMN_NO, NVL(SCO_GK_ZKM,0) PACK_KIN ";
        $strsql .= "  FROM M41E68 ";
        $strsql .= "  WHERE  SCO_ITM_NO = 'K153000') PACK_753 ";
        $strsql .= "  ON PACK_753.CMN_NO = CMN.CMN_NO ";
        $strsql .= "  LEFT JOIN (SELECT CMN_NO, NVL(SCO_GK_ZKM,0) MENTE_KIN ";
        $strsql .= "  FROM M41E68 ";
        $strsql .= "  WHERE  SCO_ITM_NO = 'K150031') PACK_MENTE ";
        $strsql .= "  ON PACK_MENTE.CMN_NO = CMN.CMN_NO ";
        $strsql .= "  LEFT JOIN (SELECT CMN_NO, NVL(SCO_GK_ZKM,0) HOSYOU_KIN ";
        $strsql .= "  FROM M41E68 ";
        $strsql .= "  WHERE SCO_ITM_NO = 'K150032') ENCHOU ";
        $strsql .= "  ON ENCHOU.CMN_NO = CMN.CMN_NO ";

        $strsql .= "  LEFT JOIN ";
        $strsql .= "  (SELECT E10.CMN_NO ,E68.SCO_GK_ZEINK ";
        $strsql .= "  FROM M41E68 E68, M41E10 E10 ";
        $strsql .= "  WHERE E10.CMN_NO = E68.CMN_NO ";
        $strsql .= "  AND ((E10.NAU_KB = '1' AND E68.SCO_ITM_NO = 'K111001') ";

        $strsql .= "  OR (E10.NAU_KB = '2' AND E68.SCO_ITM_NO = 'K112001'))) JIDOSYA ";
        $strsql .= "  ON JIDOSYA.CMN_NO = CMN.CMN_NO ";

        //20240905 ADD START
        $strsql .= "  LEFT JOIN ";
        $strsql .= "  (SELECT E10.CMN_NO ,E68.SCO_GK_ZKM";
        $strsql .= "  FROM M41E68 E68, M41E10 E10 ";
        $strsql .= "  WHERE E10.CMN_NO = E68.CMN_NO ";
        $strsql .= "  AND  E10.NAU_KB = '2' AND E68.SCO_ITM_NO = 'K112007') JIDOSYA_MEIHEN ";
        $strsql .= "  ON JIDOSYA_MEIHEN.CMN_NO = CMN.CMN_NO ";
        //20240905 ADD END

        $strsql .= "  LEFT JOIN ";
        $strsql .= "  (SELECT E10.CMN_NO , SUM(NVL(E68.SCO_GK_ZEINK,0)) S_GAKU ";
        $strsql .= "  FROM M41E68 E68, M41E10 E10 ";
        $strsql .= "  WHERE E68.CMN_NO = E10.CMN_NO ";
        $strsql .= "  AND ((E10.NAU_KB = '1' ";
        $strsql .= "  AND (E68.SCO_ITM_NO = 'K111002' ";
        //20190731 UPDATE START
        //			$strsql .= "  OR E68.SCO_ITM_NO = 'K111006')) ";

        $strsql .= "  OR E68.SCO_ITM_NO = 'K111006'  ";
        $strsql .= "  OR E68.SCO_ITM_NO = 'K111000')) ";


        //20190731 UPDATE END
        $strsql .= "  OR (E10.NAU_KB = '2' ";
        $strsql .= "  AND (E68.SCO_ITM_NO = 'K112002' ";
        $strsql .= "  OR E68.SCO_ITM_NO = 'K112008' ";
        //20190731 UPDATE START
        //			$strsql .= "  OR E68.SCO_ITM_NO = 'K112010'))) ";

        $strsql .= "  OR E68.SCO_ITM_NO = 'K112009' ";
        $strsql .= "  OR E68.SCO_ITM_NO = 'K112010' ";
        $strsql .= "  OR E68.SCO_ITM_NO = 'K112000' ";
        $strsql .= "  OR E68.SCO_ITM_NO = 'K112003'))) ";
        //20190731 UPDATE END
        $strsql .= "  GROUP BY E10.CMN_NO) SYUTOKU ";
        $strsql .= "  ON SYUTOKU.CMN_NO = CMN.CMN_NO ";
        $strsql .= "  LEFT JOIN ";
        $strsql .= "  (SELECT E10.CMN_NO, E68.SCO_GK_ZEINK ";
        $strsql .= "  FROM   M41E68 E68, M41E10 E10 ";
        $strsql .= "  WHERE E68.CMN_NO = E10.CMN_NO ";
        $strsql .= "  AND   ((E10.NAU_KB = '1' AND E68.SCO_ITM_NO = 'K111004') ";
        $strsql .= "  OR     (E10.NAU_KB = '2' AND E68.SCO_ITM_NO = 'K112004'))) JYURYO ";
        $strsql .= "  ON JYURYO.CMN_NO = CMN.CMN_NO ";

        $strsql .= "  LEFT JOIN ";
        $strsql .= "  (SELECT E10.CMN_NO, E68.SCO_GK_ZEINK ";
        $strsql .= "  FROM M41E68 E68, M41E10 E10 ";
        $strsql .= "  WHERE E68.CMN_NO = E10.CMN_NO ";
        $strsql .= "  AND ((E10.NAU_KB = '1' AND E68.SCO_ITM_NO = 'K111005') ";
        $strsql .= "  OR (E10.NAU_KB = '2' AND E68.SCO_ITM_NO = 'K112005'))) JIBAI ";
        $strsql .= "  ON JIBAI.CMN_NO = CMN.CMN_NO ";

        //20240905 ADD START
        $strsql .= "  LEFT JOIN ";
        $strsql .= "  (SELECT E10.CMN_NO, E68.SCO_GK_ZKM ";
        $strsql .= "  FROM M41E68 E68, M41E10 E10 ";
        $strsql .= "  WHERE E68.CMN_NO = E10.CMN_NO ";
        $strsql .= "  AND E10.NAU_KB = '2' AND E68.SCO_ITM_NO = 'K112006') JIBAI_MEIHEN ";
        $strsql .= "  ON JIBAI_MEIHEN.CMN_NO = CMN.CMN_NO ";
        //20240905 ADD END


        $strsql .= "  LEFT JOIN ";
        //************************** 2014-03-20 Add start *****************************/
        //			$strsql .= "  (SELECT CMN_NO , SUM(NVL(SCO_GK_ZKM,0)) SYOHIYO ";
        $strsql .= "  (SELECT CMN_NO , SHZ_RT , SUM(NVL(SCO_GK_ZKM,0)) SYOHIYO ";
        //************************** 2014-03-20 Add end *****************************/
        $strsql .= "  FROM M41E68 ";
        $strsql .= "  WHERE (SCO_ITM_NO = 'K123000' ";
        $strsql .= "  OR SCO_ITM_NO = 'K123001' ";
        //20140423 S0009 預かり法廷区分追加対応 st
        $strsql .= "  OR SCO_ITM_NO = 'K123005' ";
        //持込(登)
        $strsql .= "  OR SCO_ITM_NO = 'K123006' ";
        //持込(軽)
        //20140423 S0009 預かり法廷区分追加対応 ed
        $strsql .= "  OR SCO_ITM_NO = 'K123010' ";
        $strsql .= "  OR SCO_ITM_NO = 'K123011' ";
        $strsql .= "  OR SCO_ITM_NO = 'K123020' ";
        $strsql .= "  OR SCO_ITM_NO = 'K123030' ";
        $strsql .= "  OR SCO_ITM_NO = 'K123050' ";
        $strsql .= "  OR SCO_ITM_NO = 'K123051' ";
        $strsql .= "  OR SCO_ITM_NO = 'K123060' ";
        $strsql .= "  OR SCO_ITM_NO = 'K123070' ";
        //20231025 ADD START
        $strsql .= "  OR SCO_ITM_NO = 'K123071' ";
        //20231025 ADD END
        //20250321 ADD START
        $strsql .= "  OR SCO_ITM_NO = 'K123072' ";
        //20250321 ADD END

        //20240927 ADD START
        $strsql .= "  OR SCO_ITM_NO = 'K123073' ";
        //20240927 ADD END

        $strsql .= "  OR SCO_ITM_NO = 'K123080' ";

        //20250606 ADD START
        $strsql .= "  OR SCO_ITM_NO = 'K123085' ";
        //20250606 ADD END
        //20250626 ADD START
        $strsql .= "  OR SCO_ITM_NO = 'K123086' ";
        //20250626 ADD END

        //20241010 ADD START
        $strsql .= "  OR SCO_ITM_NO = 'K123090' ";
        //20241010 ADD END
        //20241129 ADD START
        $strsql .= "  OR SCO_ITM_NO = 'K123091' ";
        //20241129 ADD END
        //20250625 ADD START
        $strsql .= "  OR SCO_ITM_NO = 'K123092' ";
        //20250625 ADD END

        //20180129 ロータス諸費用追加 ed
        $strsql .= "  OR SCO_ITM_NO = 'K123082' ";
        $strsql .= "  OR SCO_ITM_NO = 'K123083' ";
        //20180129 ロータス諸費用追加 ed

        $strsql .= "  OR SCO_ITM_NO = 'K123084' ";

        $strsql .= "  OR SCO_ITM_NO = 'K123110' ";

        //20211008 追加 st
        $strsql .= "  OR SCO_ITM_NO = 'K123120' ";
        //20211008 追加 ed

        $strsql .= "  OR SCO_ITM_NO = 'K123530' ";
        $strsql .= "  OR SCO_ITM_NO = 'K123550' ";
        $strsql .= "  OR SCO_ITM_NO = 'K124000' ";
        $strsql .= "  OR SCO_ITM_NO = 'K124001' ";
        $strsql .= "  OR SCO_ITM_NO = 'K124002' ";
        $strsql .= "  OR SCO_ITM_NO = 'K124010' ";
        $strsql .= "  OR SCO_ITM_NO = 'K124011' ";
        //20170427 Update Start
        //$strsql .= "  OR SCO_ITM_NO = 'K124020') ";
        $strsql .= "  OR SCO_ITM_NO = 'K124020'  ";
        $strsql .= "  OR SCO_ITM_NO = 'K126000' ";
        //20170427 Update End

        //20221014 追加 st
        $strsql .= "  OR SCO_ITM_NO = 'K121001' ";
        //20221017 追加 st
        $strsql .= "  OR SCO_ITM_NO = 'K121002' ";
        //20221017 追加 ed
        //20221227 追加 st
        $strsql .= "  OR SCO_ITM_NO = 'K121003' ";
        //20221227 追加 ed

        //20240227 追加 st
        $strsql .= "  OR SCO_ITM_NO = 'K121009' ";
        //20240227 追加 ed

        //20221019 追加 st
        $strsql .= "  OR SCO_ITM_NO = 'K121010' ";
        //20221019 追加 ed

        //20240130 追加 st
        $strsql .= "  OR SCO_ITM_NO = 'K121013' ";
        //20240130 追加 ed

        //20231031 追加 st
        $strsql .= "  OR SCO_ITM_NO = 'K121014' ";
        //20231031 追加 ed

        //20240705 追加 st
        $strsql .= "  OR SCO_ITM_NO = 'K121015' ";
        //20240705 追加 ed


        //20221027 追加 st
        $strsql .= "  OR SCO_ITM_NO = 'K121004' ";
        //20221027 追加 ed

        $strsql .= "  OR SCO_ITM_NO = 'K121011' ";

        //20221115 追加 st
        $strsql .= "  OR SCO_ITM_NO = 'K121012' ";
        //20221115 追加 ed
        $strsql .= "  OR SCO_ITM_NO = 'K121008') ";
        //20221014 追加 ed


        //************************** 2014-03-20 Add start *****************************/
        //			$strsql .= "  GROUP BY CMN_NO) TOU_SYOHI ";
        $strsql .= "  GROUP BY CMN_NO, SHZ_RT) TOU_SYOHI ";
        //************************** 2014-03-20 Add end *****************************/
        $strsql .= "  ON TOU_SYOHI.CMN_NO = CMN.CMN_NO	";

        /************************************2014-01-09 Update start******************************************/

        /*登録諸費用等(下取りから抽出)*/
        /*****************************************************************************************************
                  $strsql .= "  LEFT JOIN ";
                  $strsql .= "  (SELECT CMN_NO , NVL(MSY_TOU_TTK_DAIKO_HYO,0) + NVL(SIY_SMI_CAR_SYR_HYO,0) SYOHIYO_SIT ";
                  $strsql .= "  FROM M41E11) TOU_SYOHI_SIT ";
                  $strsql .= "  ON TOU_SYOHI_SIT.CMN_NO = CMN.CMN_NO ";
                  *****************************************************************************************************/

        /*下取車複数台対応*/

        $strsql .= "  LEFT JOIN ";
        $strsql .= "  (SELECT CMN_NO ";
        //************************** 2014-03-20 Add start *****************************/
        $strsql .= "  ,SHZ_RT ";
        //************************** 2014-03-20 Add start *****************************/
        $strsql .= "  ,SUM(NVL(MSY_TOU_TTK_DAIKO_HYO,0) + NVL(SIY_SMI_CAR_SYR_HYO,0)) SYOHIYO_SIT ";
        $strsql .= "  FROM M41E11 ";
        //************************** 2014-03-20 Add start *****************************/
        //			$strsql .= "  GROUP BY  CMN_NO) TOU_SYOHI_SIT ";
        $strsql .= "  GROUP BY  CMN_NO,SHZ_RT) TOU_SYOHI_SIT ";
        //************************** 2014-03-20 Add start *****************************/
        $strsql .= "  ON TOU_SYOHI_SIT.CMN_NO = CMN.CMN_NO ";

        /************************************2014-01-09 Update start******************************************/

        /*ﾘｻｲｸﾙ資金管理料金*/
        $strsql .= "  LEFT JOIN M41E68 RECYCLE ";
        $strsql .= "  ON RECYCLE.CMN_NO = CMN.CMN_NO ";
        $strsql .= "  AND RECYCLE.SCO_ITM_NO = 'K150001' ";

        /*預かり法定費用*/
        $strsql .= "  LEFT JOIN ";
        $strsql .= "  (SELECT CMN_NO, SUM(NVL(SCO_GK_ZEINK,0)) AZKARIHIYO ";
        $strsql .= "  FROM M41E68 ";
        $strsql .= "  WHERE (SCO_ITM_NO = 'K133000' ";
        $strsql .= "  OR SCO_ITM_NO = 'K133001' ";

        //20240729 DEL START
        //20240705 ADD START
        //$strsql .= "  OR SCO_ITM_NO = 'K130001' ";
        //20240705 ADD END
        //20240729 DEL END

        //20221014 ADD START
        $strsql .= "  OR SCO_ITM_NO = 'K131001' ";
        //20221014 ADD END
        //20221017 ADD START
        $strsql .= "  OR SCO_ITM_NO = 'K131002' ";
        $strsql .= "  OR SCO_ITM_NO = 'K131004' ";
        //20221017 ADD END
        //20230119 ADD START
        $strsql .= "  OR SCO_ITM_NO = 'K131005' ";
        //20230119 ADD END
        //20221019 ADD START
        $strsql .= "  OR SCO_ITM_NO = 'K131006' ";
        //20221019 ADD END
        //20221025 ADD START
        $strsql .= "  OR SCO_ITM_NO = 'K131003' ";
        $strsql .= "  OR SCO_ITM_NO = 'K131007' ";
        $strsql .= "  OR SCO_ITM_NO = 'K131008' ";
        //20221025 ADD END


        //20140423 S0009 預かり法廷区分追加対応 st
        $strsql .= "  OR SCO_ITM_NO = 'K133002' ";
        //持込検査(小型)
        $strsql .= "  OR SCO_ITM_NO = 'K133003' ";
        //持込検査(普通)
        $strsql .= "  OR SCO_ITM_NO = 'K133004' ";
        //20220929 add start
        $strsql .= "  OR SCO_ITM_NO = 'K133006' ";
        //20220929 add end

        //20240612 add start
        $strsql .= "  OR SCO_ITM_NO = 'K133007' ";
        //20240612 add end

        //20250428 add start
        $strsql .= "  OR SCO_ITM_NO = 'K133009' ";
        //20250428 add end

        //20140423 S0009 預かり法廷区分追加対応 ed
        $strsql .= "  OR SCO_ITM_NO = 'K133010' ";
        $strsql .= "  OR SCO_ITM_NO = 'K133011' ";
        $strsql .= "  OR SCO_ITM_NO = 'K133020' ";
        $strsql .= "  OR SCO_ITM_NO = 'K133030' ";
        $strsql .= "  OR SCO_ITM_NO = 'K133031' ";

	//20250613 ADD START
        $strsql .= "  OR SCO_ITM_NO = 'K133032' ";
	//20250613 ADD END

        //20240613 upd start
        $strsql .= "  OR SCO_ITM_NO = 'K133500' ";
        $strsql .= "  OR SCO_ITM_NO = 'K133510' ";
        $strsql .= "  OR SCO_ITM_NO = 'K133540' ";
        //20240613 upd end

        //20241121 ADD START
        $strsql .= "  OR SCO_ITM_NO = 'K133501' ";
        //20241121 ADD END

        //20240905 ADD START
        $strsql .= "  OR SCO_ITM_NO = 'K133502' ";
        //20240905 ADD END

        //20241202 upd start
        $strsql .= "  OR SCO_ITM_NO = 'K133503' ";
        //20241202 upd end

        //20140403 軽区分追加対応 st
        $strsql .= "  OR SCO_ITM_NO = 'K133542' ";
        //20140403 軽区分追加対応 ed

        //20170420 ラグビーW杯番号追加 Start
        $strsql .= "  OR SCO_ITM_NO = 'K133543' ";
        $strsql .= "  OR SCO_ITM_NO = 'K133544' ";
        $strsql .= "  OR SCO_ITM_NO = 'K133545' ";
        $strsql .= "  OR SCO_ITM_NO = 'K133546' ";
        //20170420 ラグビーW杯番号追加 end

        //20170907 東京オリンピック番号追加 Start
        $strsql .= "  OR SCO_ITM_NO = 'K133547' ";
        $strsql .= "  OR SCO_ITM_NO = 'K133548' ";
        $strsql .= "  OR SCO_ITM_NO = 'K133549' ";
        $strsql .= "  OR SCO_ITM_NO = 'K133550' ";
        //20170907 東京オリンピック番号追加 end
        //20190417 OSS検索追加 Start
        $strsql .= "  OR SCO_ITM_NO = 'K133551' ";
        //20190417 OSS検索追加 End
        //20190417 ご当地番号追加 Start
        $strsql .= "  OR SCO_ITM_NO = 'K133552' ";
        $strsql .= "  OR SCO_ITM_NO = 'K133553' ";
        $strsql .= "  OR SCO_ITM_NO = 'K133554' ";
        $strsql .= "  OR SCO_ITM_NO = 'K133555' ";
        //20190417 ご当地番号追加 End

        //20241129 ADD START
        $strsql .= "  OR SCO_ITM_NO = 'K133556' ";
        //20241129 ADD END

        //20241025 ADD START
        $strsql .= "  OR SCO_ITM_NO = 'K133558' ";
        //20241025 ADD END

        //20250603 ADD START
        $strsql .= "  OR SCO_ITM_NO = 'K133559' ";
        //20250603 ADD END

        $strsql .= "  OR SCO_ITM_NO = 'K133040' ";
        $strsql .= "  OR SCO_ITM_NO = 'K133050' ";
        $strsql .= "  OR SCO_ITM_NO = 'K133541' ";

        //20250117 ADD START
        $strsql .= "  OR SCO_ITM_NO = 'K133511' ";
        //20250117 ADD END

        //20231031 UPD START
        $strsql .= "  OR SCO_ITM_NO = 'K150021') ";
        //20231031 UPD END
        $strsql .= "  GROUP BY CMN_NO) AZUKARI ";
        $strsql .= "  ON AZUKARI.CMN_NO = CMN.CMN_NO ";

        /*預かり預託金*/
        $strsql .= "  LEFT JOIN M41E68 REC_AZU ";
        $strsql .= "  ON REC_AZU.CMN_NO = CMN.CMN_NO ";
        $strsql .= "  AND REC_AZU.SCO_ITM_NO = 'K130001' ";

        /*車庫証明*/
        $strsql .= "  LEFT JOIN ";
        $strsql .= "  (SELECT CMN_NO, SUM(NVL(SCO_GK_ZEINK,0)) GK ";
        $strsql .= "  FROM   M41E68 ";
        $strsql .= "  WHERE (SCO_ITM_NO = 'K123010' ";
        $strsql .= "  OR SCO_ITM_NO = 'K133010') ";
        $strsql .= "  GROUP BY CMN_NO) SYAKOSYO ";
        $strsql .= "  ON SYAKOSYO.CMN_NO = CMN.CMN_NO ";

        /*従業員マスタ*/
        $strsql .= "  LEFT JOIN M29MA4 SYAIN ";
        $strsql .= "  ON SYAIN.SYAIN_NO = CMN.HNB_TAN_EMP_NO ";

        /*お買上明細マスタ*/
        $strsql .= "  LEFT JOIN HOKAIAGEMEISAIMST O_MEI ";
        $strsql .= "  ON O_MEI.BUSYO_CD = CMN.HNB_KTN_CD ";

        /*サービス拠点マスタ*/
        $strsql .= "  LEFT JOIN HSERVICE SVC ";
        $strsql .= "  ON SVC.BUSYO_CD = CMN.SVKYOTN_CD ";

        /*ﾜｰｸ付属品*/
        $strsql .= "  LEFT JOIN WK_FUZOKUHIN FZK ";
        $strsql .= "  ON FZK.CMN_NO = CMN.CMN_NO ";

        /*********************************2014-01-10 Update start**********************************************/

        /*銘柄マスタ*/
        /******************************************************************************************************
                  $strsql .= "  LEFT JOIN M28M71 MGR ";
                  $strsql .= "  ON MGR.MEIGARA_CODE = SIT.BRD_CD ";
                  *******************************************************************************************************/

        /*下取車複数台対応*/
        /*>>> 1台目*/
        $strsql .= " LEFT JOIN (SELECT CMN_NO ";
        $strsql .= " ,BRD_CD ";
        $strsql .= " ,NENSIKI ";
        $strsql .= " ,SYAMEI ";
        $strsql .= " ,TOUROKU_NO ";
        $strsql .= " FROM (SELECT CMN_NO ";
        $strsql .= " ,BRD_CD ";
        $strsql .= " ,(CASE WHEN SYD_TOU_YM IS NULL THEN NULL ELSE SUBSTR(JPDATE(SYD_TOU_YM || '01'),1,3) END) NENSIKI ";
        /*年式*/
        $strsql .= " ,VCLNM SYAMEI ";
        /*車名*/
        $strsql .= " ,SUBSTRB((CASE WHEN CAR_NO IS NULL THEN SDI_KAT ELSE (SDI_KAT || '-' || CAR_NO) END),1,18) TOUROKU_NO ";
        /*登録ナンバー*/
        $strsql .= " ,ROW_NUMBER() OVER (PARTITION BY CMN_NO ORDER BY CMN_NO,TRA_CAR_SEQ_NO) RNUM ";
        $strsql .= " FROM M41E11) ";
        $strsql .= " WHERE RNUM = 1) SIT1 ";
        $strsql .= " ON SIT1.CMN_NO = CMN.CMN_NO ";

        /*銘柄マスタ*/
        $strsql .= " LEFT JOIN M28M71 MGR1 ";
        $strsql .= " ON MGR1.MEIGARA_CODE = SIT1.BRD_CD ";

        /*>>> 2台目*/
        $strsql .= " LEFT JOIN (SELECT CMN_NO ";
        $strsql .= " ,BRD_CD ";
        $strsql .= " ,NENSIKI ";
        $strsql .= " ,SYAMEI ";
        $strsql .= " ,TOUROKU_NO ";
        $strsql .= " FROM (SELECT CMN_NO ";
        $strsql .= " ,BRD_CD ";
        $strsql .= " ,(CASE WHEN SYD_TOU_YM IS NULL THEN NULL ELSE SUBSTR(JPDATE(SYD_TOU_YM || '01'),1,3) END) NENSIKI ";
        /*年式*/
        $strsql .= " ,VCLNM SYAMEI ";
        /*車名*/
        $strsql .= " ,SUBSTRB((CASE WHEN CAR_NO IS NULL THEN SDI_KAT ELSE (SDI_KAT || '-' || CAR_NO) END),1,18) TOUROKU_NO ";
        /*登録ナンバー*/
        $strsql .= " ,ROW_NUMBER() OVER (PARTITION BY CMN_NO ORDER BY CMN_NO,TRA_CAR_SEQ_NO) RNUM ";
        $strsql .= " FROM M41E11) ";
        $strsql .= " WHERE RNUM = 2) SIT2 ";
        $strsql .= " ON SIT2.CMN_NO = CMN.CMN_NO ";

        /*銘柄マスタ*/
        $strsql .= " LEFT JOIN M28M71 MGR2 ";
        $strsql .= " ON MGR2.MEIGARA_CODE = SIT2.BRD_CD ";

        /************************** 2014-03-14 Add start *****************************/
        $strsql .= "  LEFT JOIN ";
        $strsql .= "  (SELECT CMN_NO, SHZ_RT ";
        $strsql .= "   FROM M41E11) CHU ";
        $strsql .= "  ON CHU.CMN_NO = CMN.CMN_NO ";
        /************************** 2014-03-14 Add end *****************************/

        /*********************************2014-01-10 Update end**********************************************/

        $strsql = str_replace("@UPDUSER", $this->GS_LOGINUSER['strUserID'], $strsql);
        $strsql = str_replace("@UPDAPP", "OkaiagePrint", $strsql);
        $strsql = str_replace("@UPDCLT", $this->GS_LOGINUSER['strClientNM'], $strsql);

        //$this->log($strsql);
        return $strsql;
    }

    /***********************************************************************
           処 理 名：お買い上げ明細をプレビュー表示するためのSQL
           関 数 名：selectSqlOkaiagePrint
           引    数：チャックフラグ、注文書番号、登録日From、登録日To
           戻 り 値：SQL
           処理説明：お買い上げ明細をプレビュー表示するためのSQL
           ***********************************************************************/
    public function selectSqlOkaiagePrint($chkFlag, $txtCMN_NO, $cboStartDate, $cboEndDate)
    {
        $strsql = " SELECT CMN_NO ";
        //			$strsql .= " ,  SUBSTR(JPDATE(TO_CHAR(URIAGEBI,'YYYYMMDD')),2,2) NEN  ";
        $strsql .= " ,  TO_CHAR(URIAGEBI,'YYYY') NEN  ";

        $strsql .= " ,  SUBSTR(JPDATE(TO_CHAR(URIAGEBI,'YYYYMMDD')),4,2) TUKI  ";
        $strsql .= " ,  SUBSTR(JPDATE(TO_CHAR(URIAGEBI,'YYYYMMDD')),6,2) HI  ";
        $strsql .= " ,  SUBSTR(YUUBINNO,1,3) YUUBINNO  ";
        $strsql .= " ,  SUBSTR(YUUBINNO,4,4) YUUBINNO_SITA  ";
        $strsql .= " ,  ZYUUSYO1  ";
        $strsql .= " ,  ZYUUSYO2  ";
        $strsql .= " ,  ZYUUSYO3  ";
        $strsql .= " ,  KEIYAKU_NM1  ";
        $strsql .= " ,  KEIYAKU_NM2  ";
        $strsql .= " ,  SIYOU_NM  ";
        $strsql .= " ,  KEIYAKU_CAR  ";
        $strsql .= " ,  SYADAI_CAR_NO  ";
        $strsql .= " ,  GENKIN  ";
        $strsql .= " ,  KUREJIT_KAI  ";
        $strsql .= " ,  KUREJIT_KIN  ";
        $strsql .= " ,  TEGATA_KAI  ";
        $strsql .= " ,  TEGATA_KIN  ";
        $strsql .= " ,  SITADORI_SUMI_KIN  ";
        $strsql .= " ,  SITADORI_SUMI_ZEI  ";
        $strsql .= " ,  SYARYOU_KIN  ";
        $strsql .= " ,  SYARYOU_NEBIKI  ";
        $strsql .= " ,  SYARYOU_HIKIWATARI  ";
        $strsql .= " ,  FUZOKU_KIN1 ";
        $strsql .= " ,  FUZOKU_KIN2  ";
        $strsql .= " ,  GENKIN_HANBAI_ZKOMI  ";
        $strsql .= " ,  KAPPU_TESURYOU  ";
        $strsql .= " ,  BETTO_SHIHARAI_KIN1  ";
        $strsql .= " ,  ZANSAI  ";
        $strsql .= " ,  SIYOU_SUMI_RECYCLE  ";
        $strsql .= " ,  YOTAKU_KIN  ";
        $strsql .= " ,  PACK_DE_753 ";
        $strsql .= " ,  OSHIHARAIKEI  ";
        $strsql .= " ,  UTIZEI_KIN  ";
        $strsql .= " ,  NENSIKI  ";
        $strsql .= " ,  MEIGARA  ";
        $strsql .= " ,  SYAMEI ";
        $strsql .= " ,  TOUROKU_NO  ";
        $strsql .= " ,  GINKO_NM1  ";
        $strsql .= " ,  GINKOSITEN_NM1  ";
        $strsql .= " ,  KOUZA_SYUBETU1  ";
        $strsql .= " ,  KOUZA_NO1  ";
        $strsql .= " ,  KOUZA_MEIGI1 ";
        $strsql .= " ,  GINKO_NM2  ";
        $strsql .= " ,  GINKOSITEN_NM2 ";
        $strsql .= " ,  KOUZA_SYUBETU2 ";
        $strsql .= " ,  KOUZA_NO2  ";
        $strsql .= " ,  KOUZA_MEIGI2  ";
        $strsql .= " ,  GINKO_NM3  ";
        $strsql .= " ,  GINKOSITEN_NM3 ";
        $strsql .= " ,  KOUZA_SYUBETU3 ";
        $strsql .= " ,  KOUZA_NO3 ";
        $strsql .= " ,  KOUZA_MEIGI3  ";
        $strsql .= " ,  FUZOKUMEISAI1  ";
        $strsql .= " ,  FUZOKUMEISAI2  ";
        $strsql .= " ,  FUZOKUMEISAI3  ";
        $strsql .= " ,  FUZOKUMEISAI4  ";
        $strsql .= " ,  FUZOKUMEISAI5  ";
        $strsql .= " ,  FUZOKUMEISAI6  ";
        $strsql .= " ,  FUZOKUMEISAI7  ";
        $strsql .= " ,  FUZOKUMEISAI8  ";
        $strsql .= " ,  FUZOKUMEISAI9  ";
        $strsql .= " ,  FUZOKUMEISAI10  ";
        $strsql .= " ,  FUZOKUMEISAI11  ";
        $strsql .= " ,  FUZOKUMEISAI12  ";
        $strsql .= " ,  FUZOKUMEISAI13  ";
        $strsql .= " ,  FUZOKUMEISAI14  ";
        $strsql .= " ,  FUZOKUMEISAI15  ";
        $strsql .= " ,  FUZOKUMEISAI16  ";
        $strsql .= " ,  FUZOKUMEISAI17  ";
        $strsql .= " ,  FUZOKUMEISAI18  ";
        $strsql .= " ,  FUZOKUMEISAI19  ";
        $strsql .= " ,  FUZOKUMEISAI20  ";
        $strsql .= " ,  FUZOKUMEISAI21  ";
        $strsql .= " ,  FUZOKUMEISAI22  ";
        $strsql .= " ,  FUZOKUMEISAI23  ";
        $strsql .= " ,  FUZOKUMEISAI24  ";
        $strsql .= " ,  FUZOKUMEISAI25  ";
        $strsql .= " ,  FUZOKUMEISAI26  ";
        $strsql .= " ,  JIDOUSYAZEI  ";
        $strsql .= " ,  SYUTOKUZEI  ";
        $strsql .= " ,  JYUURYOUZEI  ";
        $strsql .= " ,  JIBAISEKI  ";
        $strsql .= " ,  NINIHOKEN  ";
        $strsql .= " ,  ZEIHOKENKEI  ";
        $strsql .= " ,  SHOHIYOU_ZKOMI  ";
        $strsql .= " ,  RECYCLEKANRI  ";
        $strsql .= " ,  KAZEISYOUKEI  ";
        $strsql .= " ,  AZUKARIHOUTEI  ";
        $strsql .= " ,  RECYCLEAZUKARI  ";
        $strsql .= " ,  HIKAZEISYOUKEI  ";
        $strsql .= " ,  BETTO_SHIHARAI_KIN2 ";
        $strsql .= " ,  RECYCLEYOTAKU  ";
        $strsql .= " ,  RECYCLEKANRI_ZKOMI ";
        $strsql .= " ,  CARKANRENHIKEI ";
        $strsql .= " ,  JIBAISEKI_MESSAGE  ";
        $strsql .= " ,  SYAKO_MESSAGE  ";
        $strsql .= " ,  HANBAIBUSYO ";
        $strsql .= " ,  STAFF  ";
        $strsql .= " ,  HANBAIBUSYO_TEL ";
        $strsql .= " ,  KANRISERVICE ";
        $strsql .= " ,  UPD_DATE  ";
        $strsql .= " ,  CREATE_DATE ";
        $strsql .= " ,  (NVL(GENKIN,0) + NVL(KUREJIT_KIN,0) + NVL(TEGATA_KIN,0) + NVL(SITADORI_SUMI_KIN,0) + NVL(SITADORI_SUMI_ZEI,0)) CHECK_KEI ";
        $strsql .= " ,  GENKINCHK  ";
        $strsql .= " ,  PACK_DE_MENTE ";
        $strsql .= " , ENCHOU_HOSYOU ";
        /*延長保証*/
        /***************************************2014-01-10 Add start**********************************************************/

        /*下取車複数台対応  >>> 2台目*/
        $strsql .= " ,  NENSIKI2 ";
        $strsql .= " ,  MEIGARA2 ";
        $strsql .= " ,  SYAMEI2 ";
        $strsql .= " ,  TOUROKU_NO2 ";

        /*下取車総台数*/
        $strsql .= " ,  DECODE(SITADORI_DAISU,0,'',SITADORI_DAISU) SITADORI_DAISU ";

        /*下取車他台数*/
        $strsql .= " ,  CASE WHEN SITADORI_DAISU > 2 THEN '他' ";
        $strsql .= "    ELSE '' END LBLHOKA ";
        $strsql .= " ,  CASE WHEN SITADORI_DAISU > 2 THEN SITADORI_DAISU - 2 ";
        $strsql .= "    ELSE 0 END HOKA_DAISU ";
        $strsql .= " ,  CASE WHEN SITADORI_DAISU > 2 THEN '台' ";
        $strsql .= "    ELSE '' END LBLDAI ";

        /***************************************2014-01-10 Add end**********************************************************/

        /************************** 2014-03-14 Add start *****************************/
        $strsql .= " ,  SIN_SHZ_RT  ";
        $strsql .= " ,  CHU_SHZ_RT  ";
        $strsql .= " ,  UTIZEI_KIN2  ";
        $strsql .= " ,  LBL_UTIZEI_SIN_CALC  ";
        $strsql .= " ,  LBL_UTIZEI_CHU_CALC  ";
        $strsql .= " ,  LBL_UTIZEI_CHU_TANI  ";
        /************************** 2014-03-14 Add end *****************************/

        /************************** 2019-07-22 Add start *****************************/
        // 20240613 YIN UPD S
        // $strsql .= " , CASE WHEN URIAGEBI >= '2019/10/01' THEN '環　 境　 性　 能　 割' ELSE '取　 　　得　　 　税' END as  CAPTION_TAX01 ";
        $strsql .= " , CASE WHEN TO_CHAR(URIAGEBI, 'YYYY/MM/DD') >= '2019/10/01' THEN '環　 境　 性　 能　 割' ELSE '取　 　　得　　 　税' END as  CAPTION_TAX01 ";
        // 20240613 YIN UPD E
        /************************** 2019-07-22 Add end *****************************/

        $strsql .= "  FROM HOKAIAGE ";

        if ($chkFlag == 2) {
            /* 20161008 yangyang upd s */
            // $strsql .= "WHERE CMN_NO = '" . $txtCMN_NO . "' ";
            $strsql .= "WHERE CMN_NO = '" . $txtCMN_NO[0] . "'";
            for ($i = 1; $i <= count($txtCMN_NO) - 1; $i++) {
                $strsql .= "OR CMN_NO = '" . $txtCMN_NO[$i] . "'";
            }
            /* 20161008 yangyang upd e */
        } elseif ($chkFlag == 1) {
            $strsql .= " WHERE URIAGEBI BETWEEN TO_DATE('" . $cboStartDate . "','YYYY/MM/DD') AND TO_DATE('" . $cboEndDate . "','YYYY/MM/DD')";
        }
        //--20151201 YIN INS S
        $strsql .= " ORDER BY SUBSTR(HOKAIAGE.CMN_NO,1,3) DESC";
        //--20151201 YIN INS E
        //$this->log($strsql);

        return $strsql;
    }

    /************************** 2014-05-17 Add start *****************************/
    /***********************************************************************
           処 理 名：お買い上げ明細の印刷用データの除外条件を追加
           関 数 名：fncSqlConditionsExclusion
           引    数：無し
           戻 り 値：SQL文字列
           処理説明：お買い上げ明細に追加するデータから他契自登/特約店を除外
           登録日で検索した場合に除外条件を適用
           注文書番号で検索した場合は印刷対象のままとなる
           ***********************************************************************/
    function fncSqlConditionsExclusion($strCnvTableName = "")
    {
        // 特定のテーブル名で無い場合は条件を追加しない
        if (!($strCnvTableName == "M41E10" or $strCnvTableName == "CMN")) {
            return "";
        }

        $strAddWhere = "";
        $strAddWhere .= " AND @CNVTABLENAME.EC_JUCHU_KB != '22' ";
        //他契自登を印刷対象から除外
        $strAddWhere .= " AND @CNVTABLENAME.KYK_CUS_NO NOT IN (";
        //特約店の契約お客様ＮＯ一覧を印刷対象から除外
        $strAddWhere .= "  '33990048'";
        //
        $strAddWhere .= " ,'0M001286'";
        //
        $strAddWhere .= " ,'0M004501'";
        //
        $strAddWhere .= " ,'0M005438'";
        //
        $strAddWhere .= " ,'0M005747'";
        //
        $strAddWhere .= " ,'0M010371'";
        //
        $strAddWhere .= " ,'0M012499'";
        //
        $strAddWhere .= " ,'0M014876'";
        //
        $strAddWhere .= " ,'0M017685'";
        //
        $strAddWhere .= " ,'0M021866'";
        //
        $strAddWhere .= " ,'0M022436'";
        //
        $strAddWhere .= " ,'0M023157'";
        //
        $strAddWhere .= " ,'0M024061'";
        //
        $strAddWhere .= " ,'0M028168'";
        //
        $strAddWhere .= " ,'0M028532'";
        //
        $strAddWhere .= " ,'0M029001'";
        //
        $strAddWhere .= " ,'0M032720'";
        //
        $strAddWhere .= " ,'0M032870'";
        //
        $strAddWhere .= " ,'0M034904'";
        //
        $strAddWhere .= " ,'0M038495'";
        //
        $strAddWhere .= " ,'0M038787'";
        //
        $strAddWhere .= " ,'0M039795'";
        //
        $strAddWhere .= " ,'0M039969'";
        //
        $strAddWhere .= " ,'0M041636'";
        //
        $strAddWhere .= " ,'0M045333'";
        //
        $strAddWhere .= " ,'0M046072'";
        //
        $strAddWhere .= " ,'0M047946'";
        //
        $strAddWhere .= " ,'0M049283'";
        //
        $strAddWhere .= " ,'43N04291'";
        //
        $strAddWhere .= " ,'47N02690'";
        //
        $strAddWhere .= " ,'HM070546'";
        //
        $strAddWhere .= " ,'HM103407'";
        //
        $strAddWhere .= ") ";

        $strAddWhere = str_replace("@CNVTABLENAME", $strCnvTableName, $strAddWhere);

        return $strAddWhere;
    }

    /************************** 2014-05-17 Add end *****************************/

    //*************************************
    // * 公開メソッド
    //*************************************

    /***********************************************************************
           処 理 名：付属品明細にﾃﾞｰﾀが存在するか確認する
           関 数 名：fncSitadoriCount
           引    数：チャックフラグ、注文書番号、登録日From、登録日To
           戻 り 値：取得データレコード
           処理説明：付属品明細にﾃﾞｰﾀが存在するか確認する
           ***********************************************************************/

    public function fncOKaiageCnt($chkFlag, $txtCMN_NO, $cboStartDate, $cboEndDate)
    {
        return parent::select($this->selectSqlOKaiageCnt($chkFlag, $txtCMN_NO, $cboStartDate, $cboEndDate));
    }

    /***********************************************************************
           処 理 名：注文書下取データをｶｳﾝﾄする
           関 数 名：fncSitadoriCount
           引    数：チャックフラグ、注文書番号、登録日From、登録日To
           戻 り 値：取得データレコード
           処理説明：注文書下取データをｶｳﾝﾄする
           ***********************************************************************/
    //--------------------- 2014-01-09 仕様変更 Delete start ----------------------

    /***********************************************************************
           public function fncSitadoriCount($chkFlag, $txtCMN_NO, $cboStartDate, $cboEndDate)
           {
           return parent::select($this -> selectSqlSitadoriCount($chkFlag, $txtCMN_NO, $cboStartDate, $cboEndDate));
           }
           *************************************************************************/

    //--------------------- 2014-01-09 仕様変更 Delete start ----------------------
    /***********************************************************************
           処 理 名：付属品明細を抽出する
           関 数 名：fncFuzokuMeisaiSelect
           引    数：チャックフラグ、注文書番号、登録日From、登録日To
           戻 り 値：取得データレコード
           処理説明：付属品明細を抽出する
           ***********************************************************************/
    public function fncFuzokuMeisaiSelect($chkFlag, $txtCMN_NO, $cboStartDate, $cboEndDate)
    {
        return parent::select($this->selectSqlFuzokuMeisai($chkFlag, $txtCMN_NO, $cboStartDate, $cboEndDate));
    }

    /***********************************************************************
           処 理 名：ﾜｰｸﾃｰﾌﾞﾙ削除
           関 数 名：fncFuzokuhinDelete
           引    数：SQL
           戻 り 値：SQL文実行状態
           処理説明：ﾜｰｸﾃｰﾌﾞﾙ削除
           ***********************************************************************/
    public function fncFuzokuhinDelete($strsql)
    {
        return parent::delete($strsql);
    }

    /***********************************************************************
           処 理 名：付属品ﾜｰｸﾃｰﾌﾞﾙ作成
           関 数 名：fncFuzokuhinInsert
           引    数：SQL
           戻 り 値：SQL文実行状態
           処理説明：付属品ﾜｰｸﾃｰﾌﾞﾙ作成
           ***********************************************************************/
    public function fncFuzokuhinInsert($strsql)
    {
        return parent::insert($strsql);
    }

    /***********************************************************************
           処 理 名：お買い上げ明細をDELETEする
           関 数 名：fncOkaiageDelete
           引    数：チャックフラグ、注文書番号、登録日From、登録日To
           戻 り 値：SQL文実行状態
           処理説明：お買い上げ明細をDELETEする
           ***********************************************************************/
    public function fncOkaiageDelete($chkFlag, $txtCMN_NO, $cboStartDate, $cboEndDat)
    {
        return parent::Do_Execute($this->deleteSqlOkaiage($chkFlag, $txtCMN_NO, $cboStartDate, $cboEndDat));
    }

    /***********************************************************************
           処 理 名：お買い上げ明細にINSERTする
           関 数 名：fncOkaiageMSelect
           引    数：チャックフラグ、注文書番号、登録日From、登録日To
           戻 り 値：SQL文実行状態
           処理説明：お買い上げ明細にINSERTする
           ***********************************************************************/
    public function fncOkaiageMSelect($chkFlag, $txtCMN_NO, $cboStartDate, $cboEndDate)
    {
        return parent::Do_Execute($this->insertSqlOkaiageMSelect($chkFlag, $txtCMN_NO, $cboStartDate, $cboEndDate));
    }

    /***********************************************************************
           処 理 名：お買い上げ明細をプレビュー表示する
           関 数 名：fncOkaiagePrint
           引    数：チャックフラグ、注文書番号、登録日From、登録日To
           戻 り 値：取得データレコード
           処理説明：お買い上げ明細をプレビュー表示する
           ***********************************************************************/
    public function fncOkaiagePrint($chkFlag, $txtCMN_NO, $cboStartDate, $cboEndDate)
    {
        return parent::select($this->selectSqlOkaiagePrint($chkFlag, $txtCMN_NO, $cboStartDate, $cboEndDate));
    }
}
