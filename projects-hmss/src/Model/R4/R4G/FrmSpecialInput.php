<?php
/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                             内容                               担当
 * YYYYMMDD           #ID                                     XXXXXX                            FCSDL
 * 20220922           #車両業務システム_仕様変更対応(H0009)		  架装明細入力　仕様変更対応           	 YIN
 * -------------------------------------------------------------------------------------------------------
 */
// 共通クラスの読込み
namespace App\Model\R4\R4G;

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFnc;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class FrmSpecialInput extends ClsComDb
{
    public $clsComFnc;
    // '**********************************************************************
    // '処 理 名：M41E12から初期ﾃﾞｰﾀを抽出
    // '関 数 名：fncMeisaiFirstSetSql
    // '引    数：無し
    // '戻 り 値：SQL
    // '処理説明：架装明細テーブルからﾃﾞｰﾀを抽出
    // '**********************************************************************
    function fncMeisaiFirstSetSql($conditions)
    {
        $strsql = "";
        //20170515 Update Start
//			$strsql .= "SELECT M_FZK.MDL_CD";
//			//2014/02/15 Update Y0011 End
//			//$strsql .= ",      M_FZK.YHN_NM";
//			$strsql .= ",      REPLACE(M_FZK.YHN_NM,'''','') YHN_NM";
//			//2014/02/15 Update Y0011 End
//			$strsql .= ",      (NVL(M_FZK.TTH_ICD_PRC_ZKM,0) - TRUNC(NVL(M_FZK.TTH_ICD_PRC_ZKM,0) * NVL(M_FZK.SHZ_RT,0) / (100 + NVL(M_FZK.SHZ_RT,0)))) TEIKA ";
//			$strsql .= ",      1 SURYO";
//			$strsql .= ",      '2' FUGOU";
//			$strsql .= " FROM   M41E12 M_FZK";
//			$strsql .= " WHERE  M_FZK.CMN_NO ='" . $conditions['CMN_NO'] . "'";
//			$strsql .= " AND   M_FZK.FZH_TKB_KSH_KB = '1'";
//			$strsql .= " ORDER BY  M_FZK.GYO_NO";

        $strsql .= "SELECT M_FZK.MDL_CD";
        $strsql .= ",      REPLACE(M_FZK.YHN_NM,'''','') YHN_NM";
        $strsql .= ",      (NVL(M_FZK.TTH_ICD_PRC_ZKM,0) - TRUNC(NVL(M_FZK.TTH_ICD_PRC_ZKM,0) * NVL(M_FZK.SHZ_RT,0) / (100 + NVL(M_FZK.SHZ_RT,0)))) TEIKA ";
        $strsql .= ",      1 SURYO";
        $strsql .= ",      '2' FUGOU";
        $strsql .= ",      M_CMN.EC_JUCHU_KB ";
        // 20220922 YIN INS S
        $strsql .= ",      M_CMN.JUCHU_DT";
        // 20220922 YIN INS E
        $strsql .= " FROM   M41E12 M_FZK ";
        $strsql .= " LEFT JOIN M41E10 M_CMN ON M_FZK.CMN_NO = M_CMN.CMN_NO ";
        $strsql .= " WHERE  M_FZK.CMN_NO ='" . $conditions['CMN_NO'] . "'";
        $strsql .= " AND   M_FZK.FZH_TKB_KSH_KB = '1'";
        $strsql .= " ORDER BY  M_FZK.GYO_NO";
        //20170515 Update End
        return $strsql;
    }

    // '**********************************************************************
    // '処 理 名：架装明細テーブルからﾃﾞｰﾀを抽出
    // '関 数 名：fncMeisaiSecondSetSql
    // '引    数：無し
    // '戻 り 値：SQL
    // '処理説明：架装明細テーブルからﾃﾞｰﾀを抽出
    // '**********************************************************************
    function fncMeisaiSecondSetSql($conditions)
    {
        $strSQL = "";
        $CMN_NO = $conditions['CMN_NO'];
        $KASOUNO = $conditions['KASOUNO'];

        //20131206 LuChao 既存バグ修正 Start
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        //20131206 LuChao 既存バグ修正 End

        $strSQL = "SELECT KASO.MEDALCD" . "\r\n";
        $strSQL .= ",      KASO.BUHINNM" . "\r\n";
        $strSQL .= ",      NVL(KASO.TEIKA,0) TEIKA " . "\r\n";
        $strSQL .= ",      KASO.SUURYOU" . "\r\n";
        $strSQL .= ",      KASO.KAZEIKBN " . "\r\n";
        $strSQL .= ",      KASO.BIKOU " . "\r\n";
        $strSQL .= ",      KASO.BUHIN_SYANAI_GEN_RITU " . "\r\n";
        $strSQL .= ",      KASO.BUHIN_SYANAI_GEN " . "\r\n";
        $strSQL .= ",      KASO.BUHIN_SYANAI_ZITU_RITU " . "\r\n";
        $strSQL .= ",      KASO.BUHIN_SYANAI_ZITU " . "\r\n";
        $strSQL .= ",      KASO.GYOUSYA_CD " . "\r\n";
        $strSQL .= ",      KASO.GYOUSYA_NM " . "\r\n";
        $strSQL .= ",      KASO.GAICYU_GEN_RITU " . "\r\n";
        $strSQL .= ",      KASO.GAICYU_GEN " . "\r\n";
        $strSQL .= ",      KASO.GAICYU_ZITU_RITU " . "\r\n";
        $strSQL .= ",      KASO.GAICYU_ZITU " . "\r\n";
        $strSQL .= ",      KASO.MEMO " . "\r\n";

        //20131206 LuChao 既存バグ修正 Start
        $strSQL .= " FROM   WK_HKASOUMEISAI_APPEND KASO " . "\r\n";
        //20131206 LuChao 既存バグ修正 End

        $strSQL .= " WHERE  KASO.CMN_NO = '@CMN_NO'" . "\r\n";
        $strSQL .= "   AND KASO.KASOUNO = '@KASOUNO'" . "\r\n";
        $strSQL .= "   AND    KASO.FUZOKUHINKBN = '1'";

        //20131205 LuChao 既存バグ修正 Start
        $strSQL .= " AND    KASO.UPD_SYA_CD = '@UPDUSER'" . "\r\n";
        //20131205 LuChao 既存バグ修正 End

        $strSQL = str_replace("@CMN_NO", $CMN_NO, $strSQL);
        $strSQL = str_replace("@KASOUNO", $KASOUNO, $strSQL);

        //20131205 LuChao 既存バグ修正 Start
        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        //20131205 LuChao 既存バグ修正 End
        // $this -> log($strSQL);
        return $strSQL;
    }

    public function fnc41E12TeikaSumSql($conditions)
    {
        $strsql = "";
        $strsql .= "SELECT SUM(NVL(TTH_ICD_PRC_ZKM,0)) - SUM(TRUNC(NVL(TTH_ICD_PRC_ZKM,0) * NVL(SHZ_RT,0) / (100 + NVL(SHZ_RT,0)))) FZK_TEIKA";
        $strsql .= " FROM   M41E12";
        $strsql .= " WHERE  CMN_NO = '" . $conditions['CMN_NO'] . "' AND FZH_TKB_KSH_KB = '1'";
        return $strsql;

    }

    public function fncKasouDifTeikaSql($conditions)
    {
        $strsql = "";
        $CMN_NO = $conditions['CMN_NO'];
        $KASOUNO = $conditions['KASOUNO'];

        //20131206 LuChao 既存バグ修正 Start
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        //20131206 LuChao 既存バグ修正 End

        $strsql = "SELECT SUM(NVL(TEIKA,0)) KASO_TEIKA" . "\r\n";
        $strsql .= "FROM WK_HKASOUMEISAI_APPEND" . "\r\n";
        $strsql .= " WHERE CMN_NO = '@CMN_NO'" . "\r\n";
        $strsql .= " AND FUZOKUHINKBN = '1'" . "\r\n";
        $strsql .= " AND KASOUNO <> '@KASOUNO'" . "\r\n";

        //20131206 LuChao 既存バグ修正 Start
        $strsql .= " AND UPD_SYA_CD = '@UPDUSER'" . "\r\n";
        //20131206 LuChao 既存バグ修正 End

        $strsql = str_replace("@CMN_NO", $CMN_NO, $strsql);
        $strsql = str_replace("@KASOUNO", $KASOUNO, $strsql);

        //20131205 LuChao 既存バグ修正 Start
        $strsql = str_replace("@UPDUSER", $UPDUSER, $strsql);
        //20131205 LuChao 既存バグ修正 End

        return $strsql;
    }

    //架装明細テーブルを削除する
    // '**********************************************************************
    // '処 理 名：架装明細テーブルを削除する
    // '関 数 名：fncDeleteKasouMeisaiSql
    // '引    数：無し
    // '戻 り 値：SQL
    // '処理説明：架装明細テーブルを削除する
    // '**********************************************************************
    public function fncDeleteKasouMeisaiSql($conditions)
    {
        $strsql = "";

        $CmnNO = $conditions['CmnNO'];
        $KasouNO = $conditions['KasouNO'];

        //20131206 LuChao 既存バグ修正 Start
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        //20131206 LuChao 既存バグ修正 End

        //20131205 LuChao 既存バグ修正 Start
        $strsql .= "DELETE FROM WK_HKASOUMEISAI_APPEND" . "\r\n";
        //20131205 LuChao 既存バグ修正 End

        $strsql .= " WHERE CMN_NO = '@CmnNO'" . "\r\n";
        $strsql .= " AND KASOUNO = '@KasouNO'" . "\r\n";
        $strsql .= " AND FUZOKUHINKBN = '1'" . "\r\n";

        //20131206 LuChao 既存バグ修正 Start
        $strsql .= " AND UPD_SYA_CD = '@UPDUSER'" . "\r\n" . "\r\n";
        //20131206 LuChao 既存バグ修正 End

        $strsql = str_replace("@CmnNO", $CmnNO, $strsql);
        $strsql = str_replace("@KasouNO", $KasouNO, $strsql);

        //20131205 LuChao 既存バグ修正 Start
        $strsql = str_replace("@UPDUSER", $UPDUSER, $strsql);
        //20131205 LuChao 既存バグ修正 End
        return $strsql;
    }

    // '**********************************************************************
    // '処 理 名：架装明細テーブルにINSERTするSQL作成
    // '関 数 名：fncOptionMeisaiInsSql
    // '引    数：無し
    // '戻 り 値：SQL
    // '処理説明：架装明細テーブルにINSERTするSQL作成
    // '**********************************************************************
    public function fncOptionMeisaiInsSql($condition1, $condition2, $condition3, $condition4)
    {
        $this->clsComFnc = new clsComFnc();
        $UPDSYACD = $this->GS_LOGINUSER['strUserID'];
        $UPDPRGID = "SpecialInput";
        $UPDCLTNM = $this->GS_LOGINUSER['strClientNM'];
        $strsql = "";

        //20131206 LuChao 既存バグ修正 Start
        $strsql .= "INSERT INTO WK_HKASOUMEISAI_APPEND";
        //20131206 LuChao 既存バグ修正 End

        $strsql .= "           (CMN_NO";
        $strsql .= ",           SYADAIKATA";
        $strsql .= ",           CAR_NO";
        $strsql .= ",           HANBAISYASYU";
        $strsql .= ",           TOIAWASENM";
        $strsql .= ",           SYASYU_NM";
        $strsql .= ",           KASOUNO";
        $strsql .= ",           ZEIRITU";
        $strsql .= ",           FUZOKUHINKBN";
        $strsql .= ",           DELKBN";
        $strsql .= ",           UPD_DATE";
        $strsql .= ",           CREATE_DATE";
        $strsql .= ",           UPD_SYA_CD";
        $strsql .= ",           UPD_PRG_ID";
        $strsql .= ",           UPD_CLT_NM";
        $strsql .= ",           EDA_NO";
        $strsql .= ",           MEDALCD";
        $strsql .= ",           BUHINNM";
        $strsql .= ",           BIKOU";
        $strsql .= ",           TEIKA";
        $strsql .= ",           SUURYOU";
        $strsql .= ",           BUHIN_SYANAI_GEN_RITU";
        $strsql .= ",           BUHIN_SYANAI_GEN";
        $strsql .= ",           BUHIN_SYANAI_ZITU_RITU";
        $strsql .= ",           BUHIN_SYANAI_ZITU";
        $strsql .= ",           GYOUSYA_CD";
        $strsql .= ",           GYOUSYA_NM";
        $strsql .= ",           KAZEIKBN";
        $strsql .= ",           GAICYU_GEN_RITU";
        $strsql .= ",           GAICYU_GEN";
        $strsql .= ",           GAICYU_ZITU_RITU";
        $strsql .= ",           GAICYU_ZITU)";
        $strsql .= " VALUES (";
        $strsql .= " " . $this->clsComFnc->FncSqlNv($condition1['CmnNO']);
        $strsql .= ", " . $this->clsComFnc->FncSqlNv($condition1['SyadaiKata']);
        $strsql .= ", " . $this->clsComFnc->FncSqlNv($condition1['Car_NO']);
        $strsql .= ", " . $this->clsComFnc->FncSqlNv($condition1['HanbaiSyasyu']);
        $strsql .= ", " . $this->clsComFnc->FncSqlNv($condition1['Kosyou']);
        $strsql .= ", " . $this->clsComFnc->FncSqlNv($condition1['Syasyu_NM']);
        $strsql .= ", " . $this->clsComFnc->FncSqlNv($condition1['KasouNO']);
        $strsql .= ", " . $this->clsComFnc->FncSqlNv($condition1['Zei']);
        $strsql .= ", " . "'1'";
        $strsql .= ", NULL";
        $strsql .= ", " . $this->clsComFnc->FncSqlDate($condition3);
        $strsql .= ", " . $this->clsComFnc->FncSqlDate($condition3);
        $strsql .= ", '" . $UPDSYACD . "'";
        $strsql .= ", '" . $UPDPRGID . "'";
        $strsql .= ", '" . $UPDCLTNM . "'";
        $strsql .= ", '" . $condition4 . "'";
        $strsql .= ", " . $this->FncSqlNv2($condition2['MEDALCD']);
        $strsql .= ", " . $this->FncSqlNv2($condition2['BUHINNM']);
        $strsql .= ", " . $this->FncSqlNv2($condition2['BIKOU']);
        $strsql .= ", " . $this->FncSqlNz2(str_replace(",", "", $condition2['TEIKA']));
        $strsql .= ", " . $this->FncSqlNz2(str_replace(",", "", $condition2['SUURYOU']));
        $strsql .= ", " . $this->FncSqlNv2($condition2['BUHIN_SYANAI_GEN_RITU']);
        $strsql .= ", " . $this->FncSqlNz2(str_replace(",", "", $condition2['BUHIN_SYANAI_GEN']));
        $strsql .= ", " . $this->FncSqlNv2($condition2['BUHIN_SYANAI_ZITU_RITU']);
        $strsql .= ", " . $this->FncSqlNz2(str_replace(",", "", $condition2['BUHIN_SYANAI_ZITU']));
        $strsql .= ", " . $this->FncSqlNv2($condition2['GYOUSYA_CD']);
        $strsql .= ", " . $this->FncSqlNv2($condition2['GYOUSYA_NM']);
        $strsql .= ", " . $this->FncSqlNv2($condition2['KAZEIKBN']);
        $strsql .= ", " . $this->FncSqlNv2($condition2['GAICYU_GEN_RITU']);
        $strsql .= ", " . $this->FncSqlNz2(str_replace(",", "", $condition2['GAICYU_GEN']));
        $strsql .= ", " . $this->FncSqlNv2($condition2['GAICYU_ZITU_RITU']);
        $strsql .= ", " . $this->FncSqlNz2(str_replace(",", "", $condition2['GAICYU_ZITU']));
        $strsql .= ")";
        return $strsql;
    }

    // '**********************************************************************
    // '処 理 名：取引先名を取得		// '関 数 名：fncToriNmSelectSql
    // '引    数：無し
    // '戻 り 値：SQL
    // '処理説明：取引先名を取得
    // '**********************************************************************
    public function fncToriNmSelectSql($conditions)
    {
        $strsql = "";
        $strsql .= "SELECT ATO_DTRPITNM1 ";
        $strsql .= "FROM   M28M68 ";
        $strsql .= " WHERE ATO_DTRPITCD = '" . $conditions['TORICD'] . "'";
        return $strsql;
    }

    function fncMeisaiFirstSet($postData = null)
    {
        return parent::select($this->fncMeisaiFirstSetSql($postData));
    }

    public function fncMeisaiSecondSet($postData = NULL)
    {
        return parent::select($this->fncMeisaiSecondSetSql($postData));
    }

    public function fnc41E12TeikaSum($postData)
    {
        return parent::select($this->fnc41E12TeikaSumSql($postData));
    }

    public function fncKasouDifTeika($postData)
    {
        return parent::select($this->fncKasouDifTeikaSql($postData));
    }

    //架装明細ﾃｰﾌﾞﾙの該当データを削除する
    public function fncDeleteKasouMeisai($postData)
    {
        return parent::Do_Execute($this->fncDeleteKasouMeisaiSql($postData));
    }

    //架装明細ﾃｰﾌﾞﾙに追加するためのSQLを発行
    public function fncOptionMeisaiIns($postData1, $postData2, $postData3, $postData4)
    {
        return parent::Do_Execute($this->fncOptionMeisaiInsSql($postData1, $postData2, $postData3, $postData4));
    }

    public function fncToriNmSelect($postData = NULL)
    {
        return parent::select($this->fncToriNmSelectSql($postData));
    }

    // '**********************************************************************
    // '処 理 名：Null変換関数(文字)
    // '関 数 名：FncSqlNv2
    // '引    数：objValue     (I)文字列
    // '　　　　：objReturn    (I)NULL変換後の値
    // '戻 り 値：変換後の値
    // '処理説明：Null変換(文字)を行う。
    // '**********************************************************************
    public function FncSqlNv2($objValue, $objReturn = "")
    {
        //---NULLの場合---
        if ($objValue == null) {
            if ($objReturn != "") {
                return $objReturn;
            } else {
                return "''";
            }
        }
        //---以外の場合---
        else {
            if ($objValue == "") {
                return "Null";
            } else {
                return "'" . str_replace("'", "''", $objValue) . "'";
            }
        }
    }

    // **********************************************************************
    // '処 理 名：Null変換関数(数値)
    // '関 数 名：FncSqlNz2
    // '引    数：objValue     (I)文字列
    // '　　　　：objReturn    (I)NULL変換後の値
    // '戻 り 値：変換後の値
    // '処理説明：Null変換(文字)を行う。
    // '**********************************************************************
    public function FncSqlNz2($objValue, $objReturn = "")
    {
        //---NULLの場合---
        if ($objValue == null) {
            if ($objReturn != "") {
                return $objReturn;
            } else {
                return "Null";
            }
        }
        //---以外の場合---
        else {
            if ($objValue == "") {
                return "Null";
            } else {
                return $objValue;
            }
        }
    }

}
