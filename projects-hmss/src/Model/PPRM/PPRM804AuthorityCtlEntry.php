<?php

/**
 * 説明：
 *
 *
 * @author wangying
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           GSDL　　　　　　　　
 * * --------------------------------------------------------------------------------------------
 */

//共通クラスの読込み
namespace App\Model\PPRM;

use App\Model\Component\ClsComDb;
use App\Model\PPRM\Component\ClsComFncPprm;

//*************************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************************
class PPRM804AuthorityCtlEntry extends ClsComDb
{
    //'**********************************************************************
    //'処 理 名：部署情報取得SQL
    //'関 数 名：fncBusyoInfoSel
    //'引 数 　：なし
    //'戻 り 値：ＳＱＬ
    //'処理説明：
    //'**********************************************************************
    public function fncBusyoInfoSel($postData, $sys_kb)
    {
        $strSQL = "";
        $strSQL .= "SELECT CTL.BUSYO_CD AS BUSYO_CD" . " \r\n";
        $strSQL .= ",      (CASE WHEN CTL.BUSYO_CD = 'ZZZ' THEN '全て' ELSE BUS.BUSYO_RYKNM END) BUSYO_RYKNM" . " \r\n";
        $strSQL .= "FROM   HAUTHORITY_CTL CTL" . " \r\n";
        $strSQL .= "LEFT JOIN HBUSYO BUS" . " \r\n";
        $strSQL .= "ON     BUS.BUSYO_CD = CTL.BUSYO_CD" . " \r\n";
        //20170920 YIN UPD S
        // $strSQL .= "WHERE  CTL.SYS_KB = " . $ClsComFncPprm::GSYSTEM_KB_PPRM . "" . " \r\n";
        $strSQL .= "WHERE  CTL.SYS_KB = '" . $sys_kb . "'" . " \r\n";
        //20170920 YIN UPD E
        $strSQL .= "AND    CTL.SYAIN_NO = '" . $postData["request"]["txtDispSyainNO"] . "'" . " \r\n";
        $strSQL .= "GROUP BY CTL.BUSYO_CD" . " \r\n";
        $strSQL .= ",        BUS.BUSYO_RYKNM" . " \r\n";
        $strSQL .= "ORDER BY CTL.BUSYO_CD" . " \r\n";

        return parent::select($strSQL);
    }

    //'**********************************************************************
    //'処 理 名：新規追加SQL
    //'関 数 名：btnAdd_click
    //'引 数 　：なし
    //'戻 り 値：ＳＱＬ
    //'処理説明：
    //'**********************************************************************
    public function btnAdd_click($sys_kb)
    {
        $strSql = "";
        $strSql .= "SELECT '0' CTL_CHK" . " \r\n";
        $strSql .= ",      BASE.MENU_LIST_NO PRO_NO" . " \r\n";
        $strSql .= ",      (CASE WHEN (ROW_NUMBER() OVER(ORDER BY BASE.MENU_LIST_NO) - " . " \r\n";
        $strSql .= "                   RANK() OVER(ORDER BY BASE.MENU_LIST_NO)) > 0 " . " \r\n";
        $strSql .= "             THEN ''" . " \r\n";
        $strSql .= "             ELSE PRG.PRO_NM END) PRO_NM" . " \r\n";
        $strSql .= ",      BASE.HAUTH_ID" . " \r\n";
        $strSql .= ",      AUT.HAUTH_NM" . " \r\n";
        $strSql .= ",      NULL CREATE_DATE" . " \r\n";
        $strSql .= "FROM   RCTBASEBTNDISPTBL BASE" . " \r\n";
        $strSql .= "INNER JOIN HAUTHORITY AUT " . " \r\n";
        $strSql .= "ON     AUT.HAUTH_ID = BASE.HAUTH_ID " . " \r\n";
        $strSql .= "AND    AUT.SYS_KB = BASE.SYS_KB" . " \r\n";
        $strSql .= "INNER JOIN HPROGRAMMST PRG" . " \r\n";
        $strSql .= "ON     PRG.PRO_NO = BASE.MENU_LIST_NO " . " \r\n";
        $strSql .= "AND    PRG.SYS_KB = BASE.SYS_KB" . " \r\n";
        $strSql .= "WHERE  PRG.USER_AUTH_CTL_FLG = '1'" . " \r\n";
        $strSql .= "AND    BASE.MENU_LIST_KB = '0'" . " \r\n";
        //20170920 YIN UPD S
        // $strSql .= "AND    BASE.SYS_KB = " . $ClsComFncPprm::GSYSTEM_KB_PPRM . "" . " \r\n";
        $strSql .= "AND    BASE.SYS_KB = '" . $sys_kb . "'" . " \r\n";
        //20170920 YIN UPD E
        $strSql .= "ORDER BY BASE.MENU_LIST_NO" . " \r\n";
        $strSql .= ",        BASE.HAUTH_ID" . " \r\n";

        return parent::select($strSql);
    }

    //'**********************************************************************
    //'処 理 名：削除SQL
    //'関 数 名：fncDeleteSQL
    //'引 数 　：$postdata
    //'戻 り 値：ＳＱＬ
    //'処理説明：社員別権限管理マスタの登録情報を削除する
    //'**********************************************************************
    public function fncDeleteSQL($postdata, $sys_kb)
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM HAUTHORITY_CTL" . " \r\n";
        $strSQL .= "WHERE 1=1" . " \r\n";
        //20170920 YIN UPD S
        // $strSQL .= "AND SYS_KB = " . $ClsComFncPprm::GSYSTEM_KB_PPRM . "" . " \r\n";
        $strSQL .= "AND SYS_KB = '" . $sys_kb . "'" . " \r\n";
        //20170920 YIN UPD E
        $strSQL .= "AND    SYAIN_NO = '" . $postdata["txtDispSyainNO"] . "'" . " \r\n";
        $strSQL .= "AND    BUSYO_CD = '" . $postdata["txtInpBusyoCD"] . "'" . " \r\n";

        return parent::delete($strSQL);
    }

    //'**********************************************************************
    //'処 理 名：部署名取得SQL
    //'関 数 名：FncGetBusyoNM
    //'引 数 　：$postdata
    //'戻 り 値：ＳＱＬ
    //'処理説明：部署名取得
    //'**********************************************************************
    public function FncGetBusyoNM()
    {
        $strSQL = "";
        $strSQL .= "SELECT BUSYO_NM" . " \r\n";
        $strSQL .= ", BUSYO_CD" . " \r\n";
        $strSQL .= "FROM    HBUSYO" . " \r\n";

        return parent::select($strSQL);
    }

    //'**********************************************************************
    //'処 理 名：選択した部署の更新日SQL
    //'関 数 名：fncMaxCheck
    //'引 数 　：$postdata
    //'戻 り 値：ＳＱＬ
    //'処理説明：選択した部署の更新日をセットする
    //'**********************************************************************
    public function fncMaxCheck($postdata, $sys_kb)
    {
        $strSQL = "";
        $strSQL .= "SELECT COUNT(SYAIN_NO) CNT" . " \r\n";
        $strSQL .= ",      MAX(TO_CHAR(CREATE_DATE,'YYYY/MM/DD HH24:MI:SS')) MAXUPDDT" . " \r\n";
        $strSQL .= "FROM   HAUTHORITY_CTL" . " \r\n";
        //20170920 YIN UPD S
        // $strSQL .= "WHERE  SYS_KB = " . $ClsComFncPprm::GSYSTEM_KB_PPRM . "" . " \r\n";
        $strSQL .= "WHERE  SYS_KB = '" . $sys_kb . "'" . " \r\n";
        //20170920 YIN UPD E
        $strSQL .= "AND    SYAIN_NO = '" . $postdata["txtDispSyainNO"] . "'" . " \r\n";
        $strSQL .= "AND    BUSYO_CD = '" . $postdata["txtInpBusyoCD"] . "'" . " \r\n";

        return parent::select($strSQL);
    }

    //'**********************************************************************
    //'処 理 名：部署情報選択SQL
    //'関 数 名：gvRights_SelectedIndexChanged
    //'引 数 　：$postdata
    //'戻 り 値：ＳＱＬ
    //'処理説明：メニュー権限管理マスタデータを取得する
    //'**********************************************************************
    public function gvRights_SelectedIndexChanged($postdata, $sys_kb)
    {
        $strSql = "";
        $strSql .= "SELECT (CASE WHEN CTL.HAUTH_ID IS NULL THEN '0' ElSE '1' END) CTL_CHK" . " \r\n";
        $strSql .= ",      BASE.MENU_LIST_NO PRO_NO" . " \r\n";
        $strSql .= ",      (CASE WHEN (ROW_NUMBER() OVER(ORDER BY BASE.MENU_LIST_NO ) - " . " \r\n";
        $strSql .= "                   RANK() OVER(ORDER BY BASE.MENU_LIST_NO)) > 0 " . " \r\n";
        $strSql .= "             THEN ''" . " \r\n";
        $strSql .= "             ELSE PRG.PRO_NM END) PRO_NM " . " \r\n";
        $strSql .= ",      BASE.HAUTH_ID" . " \r\n";
        $strSql .= ",      AUT.HAUTH_NM" . " \r\n";
        $strSql .= ",      CTL.CREATE_DATE" . " \r\n";
        $strSql .= "FROM   RCTBASEBTNDISPTBL BASE" . " \r\n";
        $strSql .= "INNER JOIN HAUTHORITY AUT " . " \r\n";
        $strSql .= "ON     AUT.HAUTH_ID = BASE.HAUTH_ID " . " \r\n";
        $strSql .= "AND    AUT.SYS_KB = BASE.SYS_KB" . " \r\n";
        $strSql .= "INNER JOIN HPROGRAMMST PRG" . " \r\n";
        $strSql .= "ON     PRG.PRO_NO = BASE.MENU_LIST_NO " . " \r\n";
        $strSql .= "AND    PRG.SYS_KB = BASE.SYS_KB" . " \r\n";
        $strSql .= "LEFT JOIN HAUTHORITY_CTL CTL" . " \r\n";
        $strSql .= "ON     BASE.MENU_LIST_NO = CTL.MENU_LIST_NO" . " \r\n";
        $strSql .= "AND    BASE.HAUTH_ID = CTL.HAUTH_ID" . " \r\n";
        $strSql .= "AND    BASE.SYS_KB = CTL.SYS_KB" . " \r\n";
        $strSql .= "AND    CTL.SYAIN_NO = '" . $postdata["txtDispSyainNO"] . "'" . " \r\n";
        $strSql .= "AND    CTL.BUSYO_CD = '" . $postdata["BUSYO_CD"] . "'" . " \r\n";
        $strSql .= "WHERE  PRG.USER_AUTH_CTL_FLG = '1'" . " \r\n";
        $strSql .= "AND    BASE.MENU_LIST_KB = '0'" . " \r\n";
        //20170920 YIN UPD S
        // $strSql .= "AND    BASE.SYS_KB = " . $ClsComFncPprm::GSYSTEM_KB_PPRM . "" . " \r\n";
        $strSql .= "AND    BASE.SYS_KB = '" . $sys_kb . "'" . " \r\n";
        //20170920 YIN UPD E
        $strSql .= "ORDER BY BASE.MENU_LIST_NO" . " \r\n";
        $strSql .= ",        BASE.HAUTH_ID" . " \r\n";

        return parent::select($strSql);
    }

    //'**********************************************************************
    //'処 理 名：登録SQL
    //'関 数 名：btnTouroku_click
    //'引 数 　：$postData, $deployDataArr, $MachineNM
    //'戻 り 値：ＳＱＬ
    //'処理説明：登録処理
    //'**********************************************************************
    public function btnTouroku_click($postData, $deployDataArr, $MachineNM, $sys_kb, $login_user)
    {
        $ClsComFncPprm = new ClsComFncPprm();
        $strSql = "";
        $strSql .= "INSERT INTO HAUTHORITY_CTL" . " \r\n";
        $strSql .= "(SYS_KB" . " \r\n";
        $strSql .= ",SYAIN_NO" . " \r\n";
        $strSql .= ",BUSYO_CD" . " \r\n";
        $strSql .= ",MENU_LIST_NO" . " \r\n";
        $strSql .= ",HAUTH_ID" . " \r\n";
        $strSql .= ",UPD_DATE" . " \r\n";
        $strSql .= ",CREATE_DATE" . " \r\n";
        $strSql .= ",UPD_SYA_CD" . " \r\n";
        $strSql .= ",UPD_PRG_ID" . " \r\n";
        $strSql .= ",UPD_CLT_NM" . " \r\n";
        $strSql .= ")" . " \r\n";
        //20170920 YIN UPD S
        // $strSql .= "VALUES (" . $ClsComFncPprm::GSYSTEM_KB_PPRM . "" . " \r\n";
        $strSql .= "VALUES ('" . $sys_kb . "'" . " \r\n";
        //20170920 YIN UPD E
        $strSql .= ",'" . $postData["txtDispSyainNO"] . "'" . " \r\n";
        $strSql .= ",'" . $postData["txtInpBusyoCD"] . "'" . " \r\n";
        $strSql .= ",'" . $deployDataArr["PRO_NO"] . "'" . " \r\n";
        $strSql .= ",'" . $deployDataArr["HAUTH_ID"] . "'" . " \r\n";
        $strSql .= ",SYSDATE" . " \r\n";
        $strSql .= ",  DECODE('" . $ClsComFncPprm->FncNv($deployDataArr["CREATE_DATE"]) . "','',SYSDATE,TO_DATE('" . $ClsComFncPprm->FncNv($deployDataArr["CREATE_DATE"]) . "','YYYY/MM/DD HH24:MI:SS'))" . " \r\n";
        $strSql .= ",'" . $login_user . "'" . " \r\n";
        $strSql .= ",'MENU_LIST_NO'" . " \r\n";
        $strSql .= ",'" . $MachineNM . "'" . " \r\n";
        $strSql .= ")" . " \r\n";

        return parent::insert($strSql);
    }

}