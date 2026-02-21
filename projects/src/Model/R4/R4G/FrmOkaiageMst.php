<?php
// 共通クラスの読込み
namespace App\Model\R4\R4G;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class FrmOkaiageMst extends ClsComDb
{

    // 解放が必要な変数をメンバーに設定
    // protected $conn_orl = "";
    // protected $Sel_Array = "";
    // '**********************************************************************
    // '処 理 名：お買上げ明細マスタを削除する
    // '関 数 名：fncDeleteOkaiageMst
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：お買上げ明細マスタを削除する
    // '**********************************************************************
    function fncDeleteOkaiageMst()
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM HOKAIAGEMEISAIMST";

        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：お買上げ明細マスタのデータを抽出する
    // '関 数 名：fncOkaiageMstSelect
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：お買上げ明細マスタのデータを抽出する
    // '**********************************************************************
    function fncOkaiageMstSelect()
    {
        $strSQL = "";
        $strSQL .= "SELECT BUSYO_CD";
        $strSQL .= " ,      BUSYO_NM";
        $strSQL .= " ,      BUSYO_TEL";
        $strSQL .= " ,      GINKOU_NM_1";
        $strSQL .= " ,      GINKOUSITEN_NM_1";
        $strSQL .= " ,      KOUZA_SYU_1";
        $strSQL .= " ,      KOUZA_NO_1";
        $strSQL .= " ,      KOUZA_MEIGI_1";
        $strSQL .= " ,      GINKOU_NM_2";
        $strSQL .= " ,      GINKOUSITEN_NM_2";
        $strSQL .= " ,      KOUZA_SYU_2";
        $strSQL .= " ,      KOUZA_NO_2";
        $strSQL .= " ,      KOUZA_MEIGI_2";
        $strSQL .= " ,      GINKOU_NM_3";
        $strSQL .= " ,      GINKOUSITEN_NM_3";
        $strSQL .= " ,      KOUZA_SYU_3";
        $strSQL .= " ,      KOUZA_NO_3";
        $strSQL .= " ,      KOUZA_MEIGI_3";
        $strSQL .= " FROM   HOKAIAGEMEISAIMST";
        $strSQL .= " order by rowid";
        return $strSQL;

    }

    // '**********************************************************************
    // '処 理 名：お買上げ明細マスタに追加する
    // '関 数 名：fncInsertOkaiageMst
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：お買上げ明細マスタに追加する
    // '**********************************************************************
    function fncInsertOkaiageMstSql($Array_Insert)
    {
        $UPD_SYA_CD = $this->GS_LOGINUSER['strUserID'];
        $UPD_PRG_ID = "OkaiageMst";
        $UPD_CLT_NM = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";
        $strSQL = "INSERT INTO HOKAIAGEMEISAIMST";
        $strSQL .= "	(      BUSYO_CD";
        $strSQL .= "	,      BUSYO_NM";
        $strSQL .= "	,      BUSYO_TEL";
        $strSQL .= "	,      GINKOU_NM_1";
        $strSQL .= "	,      GINKOUSITEN_NM_1";
        $strSQL .= "	,      KOUZA_SYU_1";
        $strSQL .= "	,      KOUZA_NO_1";
        $strSQL .= "	,      KOUZA_MEIGI_1";
        $strSQL .= "	,      GINKOU_NM_2";
        $strSQL .= "	,      GINKOUSITEN_NM_2";
        $strSQL .= "	,      KOUZA_SYU_2";
        $strSQL .= "	,      KOUZA_NO_2";
        $strSQL .= "	,      KOUZA_MEIGI_2";
        $strSQL .= "	,      GINKOU_NM_3";
        $strSQL .= "	,      GINKOUSITEN_NM_3";
        $strSQL .= "	,      KOUZA_SYU_3";
        $strSQL .= "	,      KOUZA_NO_3";
        $strSQL .= "	,      KOUZA_MEIGI_3";
        $strSQL .= "	,      UPD_SYA_CD";
        $strSQL .= "	,      UPD_PRG_ID";
        $strSQL .= "	,      UPD_CLT_NM";
        $strSQL .= "	) VALUES (";
        $strSQL .= "'" . $Array_Insert['BUSYO_CD'] . "'";
        $strSQL .= ", '" . $Array_Insert['BUSYO_NM'] . "'";
        $strSQL .= ", '" . $Array_Insert['BUSYO_TEL'] . "'";
        $strSQL .= ", '" . $Array_Insert['GINKOU_NM_1'] . "'";
        $strSQL .= ", '" . $Array_Insert['GINKOUSITEN_NM_1'] . "'";
        $strSQL .= ", '" . $Array_Insert['KOUZA_SYU_1'] . "'";
        $strSQL .= ", '" . $Array_Insert['KOUZA_NO_1'] . "'";
        $strSQL .= ", '" . $Array_Insert['KOUZA_MEIGI_1'] . "'";
        $strSQL .= ", '" . $Array_Insert['GINKOU_NM_2'] . "'";
        $strSQL .= ", '" . $Array_Insert['GINKOUSITEN_NM_2'] . "'";
        $strSQL .= ", '" . $Array_Insert['KOUZA_SYU_2'] . "'";
        $strSQL .= ", '" . $Array_Insert['KOUZA_NO_2'] . "'";
        $strSQL .= ", '" . $Array_Insert['KOUZA_MEIGI_2'] . "'";
        $strSQL .= ", '" . $Array_Insert['GINKOU_NM_3'] . "'";
        $strSQL .= ", '" . $Array_Insert['GINKOUSITEN_NM_3'] . "'";
        $strSQL .= ", '" . $Array_Insert['KOUZA_SYU_3'] . "'";
        $strSQL .= ", '" . $Array_Insert['KOUZA_NO_3'] . "'";
        $strSQL .= ", '" . $Array_Insert['KOUZA_MEIGI_3'] . "'";
        $strSQL .= ", '" . $UPD_SYA_CD . "'";
        $strSQL .= ", '" . $UPD_PRG_ID . "'";
        $strSQL .= ", '" . $UPD_CLT_NM . "'";
        $strSQL .= ")";
        return $strSQL;
    }

    //*************************************
    // * 公開メソッド
    //*************************************

    public function fncDelete()
    {
        return parent::Do_Execute($this->fncDeleteOkaiageMst());
    }

    public function fncInsert($Array_Insert)
    {
        return parent::Do_Execute($this->fncInsertOkaiageMstSql($Array_Insert));
    }

    public function funFrmOkaiageMst()
    {
        /*
         * 取得jqGrid数据
         */
        return parent::select($this->fncOkaiageMstSelect());
    }

}