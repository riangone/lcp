<?php
// 共通クラスの読込み
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFnc;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmSyasyuArariChousei extends ClsComDb
{
    //====
    //--excute--
    //====
    public function frmSyasyuArariChousei_Load_select()
    {
        $strSql = $this->frmSyasyuArariChousei_Load_sql();
        return parent::select($strSql);
    }

    public function subComboSet2_select()
    {
        $strSql = $this->subComboSet2_sql();
        return parent::select($strSql);
    }

    public function fncArariSelect($tmpCboYM, $tmpTxtItemNO)
    {
        $sqlstr = $this->fncArariSelect_sql($tmpCboYM, $tmpTxtItemNO);
        return parent::select($sqlstr);
    }

    public function fncDeleteArari($tmpCboYM, $tmpTxtItemNO)
    {
        $sqlstr = $this->fncDeleteArari_sql($tmpCboYM, $tmpTxtItemNO);
        return parent::Do_Execute($sqlstr);

    }

    public function fncInsertArari($tmpCboYM, $tmpTxtItemNO, $tmpTxtUriage, $tmpTxtArari)
    {

        $sqlstr = $this->fncInsertArari_sql($tmpCboYM, $tmpTxtItemNO, $tmpTxtUriage, $tmpTxtArari);
        return parent::Do_Execute($sqlstr);
    }

    public function fncDeleteArari_delete($tmpCboYM, $tmpTxtItemNO)
    {
        $sqlstr = $this->fncDeleteArari_sql($tmpCboYM, $tmpTxtItemNO);
        return parent::delete($sqlstr);
    }

    //====
    //--sql--
    //====
    public function frmSyasyuArariChousei_Load_sql()
    {
        $sqlstr = "select";
        $sqlstr .= "		ID ,";
        $sqlstr .= "		(substr(SYR_YMD,1,4) || '/' || SUBSTR(SYR_YMD,5,2)|| '/01') TOUGETU ";
        $sqlstr .= "from ";
        $sqlstr .= "		HKEIRICTL ";
        $sqlstr .= "WHERE ID='01'";
        return $sqlstr;
    }

    public function subComboSet2_sql()
    {
        $sql = "SELECT";
        $sql .= " 	OYA_CD  ";
        $sql .= ", 	SS_NAME ";
        $sql .= "FROM       ";
        $sql .= "HARARISYUKEIMST ";
        return $sql;
    }

    /*
           '**********************************************************************
           '処 理 名：抽出する
           '関 数 名：fncArariSelect_sql
           '引    数：無し
           '戻 り 値：ＳＱＬ文
           '処理説明：抽出する
           */
    public function fncArariSelect_sql($tmpCboYM, $tmpTxtItemNO)
    {
        $sqlstr = "SELECT (SUBSTR(ARI.NENGETU,1,4) || '/' || SUBSTR(ARI.NENGETU,5,2) || '/01') NENGETU";
        $sqlstr .= ",      ARI.OYA_CD";
        $sqlstr .= ",      ARI.HONTAIGAKU	";
        $sqlstr .= ",      ARI.SYARYOARARI	";
        $sqlstr .= "FROM   HARARICHOUSEI ARI	";
        $sqlstr .= "WHERE  ARI.NENGETU = '@NENGETU'	";
        $sqlstr .= "AND    ARI.OYA_CD = '@OYACD'	";
        $sqlstr = str_replace("@NENGETU", $tmpCboYM, $sqlstr);
        $sqlstr = str_replace("@OYACD", $tmpTxtItemNO, $sqlstr);
        return $sqlstr;
    }

    /*
           '**********************************************************************
           '処 理 名：削除する
           '関 数 名：fncInsertArari
           '引    数：無し
           '戻 り 値：ＳＱＬ文
           '処理説明：DBから削除する
           '**********************************************************************
           */
    public function fncDeleteArari_sql($tmpCboYM, $tmpTxtItemNO)
    {
        $sqlstr = "";
        $sqlstr .= "DELETE FROM HARARICHOUSEI  ";
        $sqlstr .= "WHERE  NENGETU = '@NENGETU'  ";
        $sqlstr .= "AND    OYA_CD = '@OYACD'  ";
        $sqlstr = str_replace("@NENGETU", $tmpCboYM, $sqlstr);
        $sqlstr = str_replace("@OYACD", $tmpTxtItemNO, $sqlstr);
        return $sqlstr;
    }

    /*
           '**********************************************************************
           '処 理 名：追加する
           '関 数 名：fncInsertArari
           '引    数：無し
           '戻 り 値：ＳＱＬ文
           '処理説明：DBに追加する
           '**********************************************************************
           */
    public function fncInsertArari_sql($tmpCboYM, $tmpTxtItemNO, $tmpTxtUriage, $tmpTxtArari)
    {
        $sqlstr = "";
        $ttC = new ClsComFnc();
        $sqlstr .= "INSERT INTO HARARICHOUSEI";
        $sqlstr .= "(      NENGETU";
        $sqlstr .= ",      OYA_CD";
        $sqlstr .= ",      HONTAIGAKU";
        $sqlstr .= ",      SYARYOARARI";
        $sqlstr .= ",      UPD_DATE";
        $sqlstr .= ",      CREATE_DATE";
        //'TODO 2006/12/08 UPD Start
        $sqlstr .= ",      UPD_SYA_CD";
        $sqlstr .= ",      UPD_PRG_ID";
        $sqlstr .= ",      UPD_CLT_NM";
        //'2006/12/08 UPD End

        $sqlstr .= ") VALUES";
        $sqlstr .= "(      '@NENGETU'";
        $sqlstr .= ",      '@OYA_CD'";
        $sqlstr .= ",      @HONTAIGAKU";
        $sqlstr .= ",      @SYARYOARARI";
        $sqlstr .= ",      SYSDATE";
        $sqlstr .= ",      SYSDATE";
        // 'TODO 2006/12/08 UPD Start
        $sqlstr .= ",      '@UPDUSER'";
        $sqlstr .= ",      '@UPDAPP'";
        $sqlstr .= ",      '@UPDCLT'";
        $sqlstr .= ")";
        $sqlstr = str_replace("@NENGETU", $tmpCboYM, $sqlstr);
        $sqlstr = str_replace("@OYA_CD", $tmpTxtItemNO, $sqlstr);
        $sqlstr = str_replace("@HONTAIGAKU", $tmpTxtUriage, $sqlstr);
        $sqlstr = str_replace("@SYARYOARARI", $tmpTxtArari, $sqlstr);
        $sqlstr = str_replace("@UPDUSER", $ttC->FncNv($this->GS_LOGINUSER['strUserID']), $sqlstr);
        $sqlstr = str_replace("@UPDAPP", "SyasyuArariChousei", $sqlstr);
        $sqlstr = str_replace("@UPDCLT", $ttC->FncNv($this->GS_LOGINUSER['strClientNM']), $sqlstr);
        return $sqlstr;
    }

}