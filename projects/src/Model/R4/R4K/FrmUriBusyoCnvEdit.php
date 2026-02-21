<?php
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFnc;

class FrmUriBusyoCnvEdit extends ClsComDb
{
    public $ClsComFnc = "";

    //********************************************************************
    //処理概要：画面のデータSQL
    //引　　数：なし
    //戻 り 値：SQL
    //********************************************************************
    public function fncDataSel_sql($post_strCMNNO)
    {
        $strsql = "";
        $strsql .= " SELECT " . "\r\n";
        $strsql .= "     URI.CMN_NO " . "\r\n";
        $strsql .= "     ,SYAIN.SYAIN_NO " . "\r\n";
        $strsql .= "     ,SYAIN.SYAIN_NM " . "\r\n";
        $strsql .= "     ,URI.MGN_MEI_KNJ1 " . "\r\n";
        $strsql .= "     ,BU.BUSYO_CD AS BU_BUSYO_CD " . "\r\n";
        $strsql .= "     ,BU.BUSYO_NM AS BU_BUSYO_NM " . "\r\n";
        $strsql .= "     ,HENKOUBU.BUSYO_CD AS HEN_BUSYO_CD " . "\r\n";
        $strsql .= "     ,HENKOUBU.BUSYO_NM AS HEN_BUSYO_NM " . "\r\n";
        $strsql .= "     ,HAIZOKU.CREATE_DATE " . "\r\n";
        $strsql .= " FROM HSCURI URI " . "\r\n";
        $strsql .= " INNER JOIN HSYAINMST SYAIN " . "\r\n";
        $strsql .= "  ON URI.URI_TANNO=SYAIN.SYAIN_NO " . "\r\n";
        $strsql .= " INNER JOIN HBUSYO BU " . "\r\n";
        $strsql .= "  ON URI.URI_BUSYO_CD=BU.BUSYO_CD " . "\r\n";
        $strsql .= " LEFT JOIN (HURIBUSYOCNV HAIZOKU " . "\r\n";
        $strsql .= " LEFT JOIN HBUSYO HENKOUBU " . "\r\n";
        $strsql .= "  ON HAIZOKU.BUSYO_CD=HENKOUBU.BUSYO_CD) " . "\r\n";
        $strsql .= "  ON URI.CMN_NO=HAIZOKU.CMN_NO " . "\r\n";
        $strsql .= " WHERE URI.CMN_NO = '" . $post_strCMNNO . "' " . "\r\n";
        return $strsql;
    }

    public function fncDataSel($post_strCMNNO)
    {
        return parent::select($this->fncDataSel_sql($post_strCMNNO));
    }

    public function fncCheckCMNNO1_sql($post_CMNNO)
    {
        $strsql = "";
        $this->ClsComFnc = new ClsComFnc();
        $strsql .= "SELECT CMN_NO FROM HSCURI WHERE CMN_NO = " . $this->ClsComFnc->FncSqlNv($post_CMNNO);
        return $strsql;
    }
    //注文書番号存在チェック
    public function fncCheckCMNNO1($post_CMNNO)
    {
        return parent::select($this->fncCheckCMNNO1_sql($post_CMNNO));
    }

    public function fncCheckCMNNO2_sql($post_CMNNO)
    {
        $strsql = "";
        $this->ClsComFnc = new ClsComFnc();
        $strsql .= " SELECT CMN_NO FROM HURIBUSYOCNV　WHERE CMN_NO = " . $this->ClsComFnc->FncSqlNv($post_CMNNO);
        return $strsql;
    }
    //注文書番号存在チェック
    public function fncCheckCMNNO2($post_CMNNO)
    {
        return parent::select($this->fncCheckCMNNO2_sql($post_CMNNO));
    }

    //**********************************************************************
    //処 理 名：売上ﾃﾞｰﾀ部署変換ﾃｰﾌﾞﾙから削除する
    //関 数 名：fncDeleteHuri
    //引    数：$post_txtCMNNO  (I)画面：注文書番号
    //戻 り 値：SQL
    //処理説明：売上ﾃﾞｰﾀ部署変換ﾃｰﾌﾞﾙから削除する
    //**********************************************************************
    public function fncDeleteHuri_sql($post_txtCMNNO)
    {
        $strSQL = "";
        $strSQL .= " DELETE FROM HURIBUSYOCNV " . "\r\n";
        $strSQL .= " WHERE CMN_NO= '" . $post_txtCMNNO . "'" . "\r\n";
        return $strSQL;
    }

    //売上ﾃﾞｰﾀ部署変換ﾃｰﾌﾞﾙから削除する
    public function fncDeleteHuri($post_txtCMNNO)
    {
        return parent::Do_Execute($this->fncDeleteHuri_sql($post_txtCMNNO));
    }

    //**********************************************************************
    //処 理 名：売上ﾃﾞｰﾀ部署変換ﾃｰﾌﾞﾙに追加する
    //関 数 名：fncInsertHuri_sql
    //引    数：
    //戻 り 値：SQL
    //処理説明：売上ﾃﾞｰﾀ部署変換ﾃｰﾌﾞﾙに追加する
    //**********************************************************************
    public function fncInsertHuri_sql($post_txtCMNNO, $post_txtCMNNO2, $post_lblCreateDate)
    {
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDAPP = "UriBusyoCnvEdit";
        $UPDCLTNM = $this->GS_LOGINUSER['strClientNM'];
        $this->ClsComFnc = new ClsComFnc();
        $post_txtCMNNO = $this->ClsComFnc->FncSqlNv($post_txtCMNNO);
        $post_txtCMNNO2 = $this->ClsComFnc->FncSqlNv($post_txtCMNNO2);
        $UPDUSER = $this->ClsComFnc->FncSqlNv($UPDUSER);
        $UPDAPP = $this->ClsComFnc->FncSqlNv($UPDAPP);
        $UPDCLTNM = $this->ClsComFnc->FncSqlNv($UPDCLTNM);
        $strSQL = "";
        $strSQL .= " INSERT INTO HURIBUSYOCNV " . "\r\n";
        $strSQL .= "      (" . "\r\n";
        $strSQL .= "       CMN_NO" . "\r\n";
        $strSQL .= "      ,BUSYO_CD" . "\r\n";
        $strSQL .= "      ,UPD_DATE" . "\r\n";
        $strSQL .= "      ,CREATE_DATE" . "\r\n";
        $strSQL .= "      ,UPD_SYA_CD" . "\r\n";
        $strSQL .= "      ,UPD_PRG_ID" . "\r\n";
        $strSQL .= "      ,UPD_CLT_NM" . "\r\n";
        $strSQL .= "      ) VALUES (" . "\r\n";
        $strSQL .= "     " . $post_txtCMNNO . "\r\n";
        $strSQL .= "     ," . $post_txtCMNNO2 . "\r\n";
        $strSQL .= "     ,SYSDATE " . "\r\n";

        if ($post_lblCreateDate == "" || $post_lblCreateDate == null) {
            $strSQL .= "     ,SYSDATE " . "\r\n";
        } else {
            $strSQL .= "     ,TO_DATE('" . $post_lblCreateDate . "','YYYY/MM/DD HH24:MI:SS') " . "\r\n";
        }

        $strSQL .= "     ," . $UPDUSER . "\r\n";
        $strSQL .= "     ," . $UPDAPP . "\r\n";
        $strSQL .= "     ," . $UPDCLTNM . "\r\n";
        $strSQL .= ")" . "\r\n";
        return $strSQL;
    }

    //売上ﾃﾞｰﾀ部署変換ﾃｰﾌﾞﾙに追加する
    public function fncInsertHuri($post_txtCMNNO, $post_txtCMNNO2, $post_lblCreateDate)
    {
        return parent::Do_Execute($this->fncInsertHuri_sql($post_txtCMNNO, $post_txtCMNNO2, $post_lblCreateDate));
    }

}