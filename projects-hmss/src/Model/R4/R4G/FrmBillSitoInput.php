<?php
// 共通クラスの読込み

namespace App\Model\R4\R4G;

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFnc;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class FrmBillSitoInput extends ClsComDb
{
    public $ClsComFnc;
    //*************************************
    // * SQL文
    //*************************************

    /**********************************************************************
              処 理 名：手形据置データ取得SQL文
              関 数 名：selectSqlBillSITO
              引    数：注文書番号
              戻 り 値：SQL
              処理説明：手形据置データ取得SQL文取得
          **********************************************************************/
    public function selectSqlBillSITO($strCMN_NO)
    {
        $strsql = "SELECT * FROM HBILLSITOD WHERE CMN_NO = '" . $strCMN_NO . "'";
        return $strsql;
    }

    /**********************************************************************
              処 理 名：手形据置データ更新SQL文
              関 数 名：updateSqlBillSITO
              引    数：注文書番号
              戻 り 値：SQL
              処理説明：手形据置データ更新SQL文取得
          **********************************************************************/
    public function updateSqlBillSITO($strCMN_NO, $strBillSito)
    {
        $this->ClsComFnc = new ClsComFnc();
        $strsql = " UPDATE HBILLSITOD SET SITO = " . $this->ClsComFnc->FncSqlNz($strBillSito);
        $strsql .= ", UPD_DATE = " . $this->ClsComFnc->FncSqlDate($this->ClsComFnc->FncGetSysDate("Y/m/d H:i:s"));
        $strsql .= ", UPD_SYA_CD = " . $this->ClsComFnc->FncSqlNv($this->GS_LOGINUSER['strUserID']);
        $strsql .= ", UPD_PRG_ID = " . $this->ClsComFnc->FncSqlNv("BillSitoInput");
        $strsql .= ", UPD_CLT_NM = " . $this->ClsComFnc->FncSqlNv($this->GS_LOGINUSER['strClientNM']);
        $strsql .= " WHERE CMN_NO = '$strCMN_NO'";

        return $strsql;
    }

    /**********************************************************************
              処 理 名：手形据置データにINSERTするSQL文
              関 数 名：insertSqlBillSITO
              引    数：注文書番号
              戻 り 値：SQL
              処理説明：手形据置データにINSERTするSQL文取得
          **********************************************************************/
    public function insertSqlBillSITO($strCMN_NO, $strBillSito)
    {
        $this->ClsComFnc = new ClsComFnc();
        $strsql = " INSERT INTO HBILLSITOD";
        $strsql .= " (CMN_NO";
        $strsql .= " , SITO";
        $strsql .= " , UPD_DATE";
        $strsql .= " , CREATE_DATE";
        $strsql .= " , UPD_SYA_CD";
        $strsql .= " , UPD_PRG_ID";
        $strsql .= " , UPD_CLT_NM";
        $strsql .= "  )";
        $strsql .= "  VALUES (";
        $strsql .= "  " . $this->ClsComFnc->FncSqlNv($strCMN_NO);
        $strsql .= " , " . $this->ClsComFnc->FncSqlNz($strBillSito);
        $strsql .= " , " . $this->ClsComFnc->FncSqlDate($this->ClsComFnc->FncGetSysDate("Y/m/d H:i:s"));
        $strsql .= " , " . $this->ClsComFnc->FncSqlDate($this->ClsComFnc->FncGetSysDate("Y/m/d H:i:s"));

        $strsql .= " , " . $this->ClsComFnc->FncSqlNv($this->GS_LOGINUSER['strUserID']);
        $strsql .= " , " . $this->ClsComFnc->FncSqlNv("BillSitoInput");
        $strsql .= " , " . $this->ClsComFnc->FncSqlNv($this->GS_LOGINUSER['strClientNM']);

        $strsql .= "  )";

        return $strsql;
    }

    /**********************************************************************
              処 理 名：注文書情報取得SQL文
              関 数 名：fncCMNSelect
              引    数：注文書番号
              戻 り 値：SQL
              処理説明：注文書情報取得SQL文取得
          **********************************************************************/
    public function selectSqlCMN($strCMN_NO)
    {
        $this->ClsComFnc = new ClsComFnc();

        $strsql = " SELECT UC_NO,KYK_CUS_NM1,SIY_CUS_NM1,SIY_FGN,KAP_MOT_KIN ";
        $strsql .= "FROM M41E10 WHERE CMN_NO = '" . $this->ClsComFnc->FncNv($strCMN_NO) . "'";

        return $strsql;
    }

    /**********************************************************************
              処 理 名：手形据置データ削除SQL文
              関 数 名：deleteSqlBillSITO
              引    数：注文書番号
              戻 り 値：SQL
              処理説明：手形据置データ削除SQL文取得
          **********************************************************************/
    public function deleteSqlBillSITO($strCMN_NO)
    {
        $strsql = "DELETE FROM HBILLSITOD WHERE CMN_NO = '" . $strCMN_NO . "'";
        return $strsql;
    }

    //*************************************
    // * 公開メソッド
    //*************************************

    /**********************************************************************
              処 理 名：手形据置データ取得
              関 数 名：fncBillSITOSelect
              引    数：注文書番号
              戻 り 値：取得データレコード
              処理説明：手形据置データ取得
          **********************************************************************/
    public function fncBillSITOSelect($strCMN_NO)
    {
        return parent::select($this->selectSqlBillSITO($strCMN_NO));
    }

    /**********************************************************************
              処 理 名：手形据置データ更新
              関 数 名：fncUpdBillSITO
              引    数：注文書番号
              戻 り 値：取得データレコード
              処理説明：手形据置データ更新
          **********************************************************************/
    public function fncUpdBillSITO($strCMN_NO, $strBillSito)
    {
        return parent::Do_Execute($this->updateSqlBillSITO($strCMN_NO, $strBillSito));
    }

    /**********************************************************************
              処 理 名：手形据置データにINSERTする
              関 数 名：fncInsBillSITO
              引    数：注文書番号
              戻 り 値：取得データレコード
              処理説明：手形据置データにINSERTする
          **********************************************************************/
    public function fncInsBillSITO($strCMN_NO, $strBillSito)
    {
        return parent::Do_Execute($this->insertSqlBillSITO($strCMN_NO, $strBillSito));
    }

    /**********************************************************************
              処 理 名：注文書情報取得
              関 数 名：fncCMNSelect
              引    数：注文書番号
              戻 り 値：取得データレコード
              処理説明：注文書情報取得
          **********************************************************************/
    public function fncCMNSelect($strCMN_NO)
    {
        return parent::select($this->selectSqlCMN($strCMN_NO));
    }

    /**********************************************************************
              処 理 名：手形据置データ削除
              関 数 名：fncDeleteBillSITO
              引    数：注文書番号
              戻 り 値：取得データレコード
              処理説明：手形据置データ削除
          **********************************************************************/
    public function fncDeleteBillSITO($strCMN_NO)
    {
        return parent::delete($this->deleteSqlBillSITO($strCMN_NO));
    }

}
