<?php
/**
 * 説明：
 *
 *
 * @author yinhuaiyu
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20171227           #2807                     依頼                            YIN
 * --------------------------------------------------------------------------------------------
 */
// 共通クラスの読込み
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFnc;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmGetujiSime extends ClsComDb
{

    /*
           ***********************************************************************
           処 理 名：コントロールマスタ存在ﾁｪｯｸ
           関 数 名：frmGetujiSime_Load_select
           引    数：しない
           戻 り 値："配列"
           処理説明：コントロールマスタ存在ﾁｪｯｸ
           ***********************************************************************
           */
    public function frmGetujiSime_Load_select()
    {
        $strSql = $this->frmGetujiSime_Load_sql();
        return parent::select($strSql);
    }

    /*
           ***********************************************************************
           処 理 名：Select Sql
           関 数 名：frmGetujiSime_Load_sql
           引    数：しない
           戻 り 値："配列"
           処理説明：Select Sql
           ***********************************************************************
           */
    public function frmGetujiSime_Load_sql()
    {
        $sqlstr = "select";
        $sqlstr .= "		ID,";
        $sqlstr .= "		(substr(SYR_YMD,1,4) || '/' || SUBSTR(SYR_YMD,5,2)|| '/01') TOUGETU ";
        $sqlstr .= "from ";
        $sqlstr .= "		HKEIRICTL ";
        $sqlstr .= "WHERE ID='01'";
        return $sqlstr;
    }

    /*
           ***********************************************************************
           処 理 名：更新Sql
           関 数 名：fncUpdateKeiriCtl_sql
           引    数：しない
           戻 り 値："配列"
           処理説明：更新Sql
           ***********************************************************************
           */
    public function fncUpdateKeirictl_sql()
    {
        $ttC = new ClsComFnc();
        $sqlstr = "";
        $sqlstr .= " UPDATE HKEIRICTL";
        $sqlstr .= " SET ";
        //20171227 YIN UPD S
        // $sqlstr .= " 	SYR_YMD = TO_CHAR(ADD_MONTHS(TO_DATE(SYR_YMD||'01'),1),'YYYYMM')";
        // $sqlstr .= ",	KISYU_YMD = (CASE WHEN SUBSTR(SYR_YMD,5,2) = '09' THEN TO_CHAR(ADD_MONTHS(TO_DATE(KISYU_YMD),12),'YYYYMMDD') ELSE KISYU_YMD END)";
        // $sqlstr .= ",	KIMATU_YMD = (CASE WHEN SUBSTR(SYR_YMD,5,2) = '09' THEN TO_CHAR(ADD_MONTHS(TO_DATE(KIMATU_YMD),12),'YYYYMMDD') ELSE KIMATU_YMD END)";
        $sqlstr .= " 	SYR_YMD = TO_CHAR(ADD_MONTHS(TO_DATE(SYR_YMD||'01','YYYYMMDD'),1),'YYYYMM')";
        $sqlstr .= ",	KISYU_YMD = (CASE WHEN SUBSTR(SYR_YMD,5,2) = '09' THEN TO_CHAR(ADD_MONTHS(TO_DATE(KISYU_YMD,'YYYYMMDD'),12),'YYYYMMDD') ELSE KISYU_YMD END)";
        $sqlstr .= ",	KIMATU_YMD = (CASE WHEN SUBSTR(SYR_YMD,5,2) = '09' THEN TO_CHAR(ADD_MONTHS(TO_DATE(KIMATU_YMD,'YYYYMMDD'),12),'YYYYMMDD') ELSE KIMATU_YMD END)";
        //20171227 YIN UPD E
        $sqlstr .= ",	KI = (CASE WHEN SUBSTR(SYR_YMD,5,2) = '09' THEN KI + 1 ELSE KI END)";
        $sqlstr .= ",	UPD_SYA_CD = '@UPDUSER' ";
        $sqlstr .= ",	UPD_PRG_ID = '@UPDAPP'";
        $sqlstr .= ",	UPD_CLT_NM = '@UPDCLTNM' ";
        $sqlstr .= " WHERE";
        $sqlstr .= " ID = '01'";
        $sqlstr = str_replace("@UPDUSER", $ttC->FncNv($this->GS_LOGINUSER['strUserID']), $sqlstr);
        $sqlstr = str_replace("@UPDAPP", "GetujiSime", $sqlstr);
        $sqlstr = str_replace("@UPDCLTNM", $ttC->FncNv($this->GS_LOGINUSER['strClientNM']), $sqlstr);
        return $sqlstr;
    }

    /*
           **********************************************************************
           処 理 名：部署別実績ファイルを更新する
           関 数 名：fncUpdateKeiriCtl
           引    数：しない
           戻 り 値："配列"
           処理説明：部署別実績ファイルを更新する
           **********************************************************************
           */
    public function fncUpdateKeirictl()
    {
        $sql = $this->fncUpdateKeirictl_sql();
        return parent::Do_Execute($sql);
    }

}