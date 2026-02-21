<?php
// 共通クラスの読込み
namespace App\Model\HMTVE;
use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE100AttendanceControl extends ClsComDb
{
    //店舗コード、店舗名を抽出する
    public function Page_ShopNameSaveSql($BUSYOCD)
    {
        $strSQL = "";
        $strSQL .= " SELECT MST.BUSYO_CD" . "\r\n";
        $strSQL .= " ,      MST.BUSYO_RYKNM" . "\r\n";
        $strSQL .= " FROM   HBUSYO MST" . "\r\n";
        $strSQL .= " INNER JOIN  (SELECT BUSYO_CD" . "\r\n";
        $strSQL .= "              ,      (CASE WHEN HDT_TENPO_CD IS NOT NULL" . "\r\n";
        $strSQL .= "                           THEN HDT_TENPO_CD ELSE BUSYO_CD END) V_TENPO" . "\r\n";
        $strSQL .= "              FROM HBUSYO) BUS" . "\r\n";
        $strSQL .= " ON     MST.BUSYO_CD = BUS.V_TENPO" . "\r\n";
        $strSQL .= " WHERE  MST.IVENT_TENPO_DISP_NO IS NOT NULL" . "\r\n";
        $strSQL .= " AND    BUS.BUSYO_CD = '@BUSYOCD'" . "\r\n";

        $strSQL = str_replace("@BUSYOCD", $BUSYOCD, $strSQL);
        return parent::select($strSQL);
    }

    //デフォルト日付を取得する
    public function Page_ClearSql()
    {
        $strSQL = "";
        $strSQL .= " SELECT START_DATE " . "\r\n";
        $strSQL .= " ,      END_DATE " . "\r\n";
        $strSQL .= " FROM   HDTIVENTDATA " . "\r\n";
        $strSQL .= " WHERE  BASE_FLG = '1' " . "\r\n";
        return parent::select($strSQL);
    }

    //取得データを出勤管理グリッドにバインドする
    public function btnPrintOut_ClickSql($BUSYOCD, $IVENTDT)
    {
        $strSQL = "";
        $strSQL .= " SELECT HAI.BUSYO_CD " . "\r\n";
        $strSQL .= " ,      SYA.SYAIN_NO " . "\r\n";
        $strSQL .= " ,      SYA.SYAIN_NM " . "\r\n";
        $strSQL .= " ,      (CASE WHEN WKMN.WORK_STATE IS NOT NULL " . "\r\n";
        $strSQL .= "         THEN " . "\r\n";
        $strSQL .= "              WKMN.WORK_STATE " . "\r\n";
        $strSQL .= "         ELSE " . "\r\n";
        $strSQL .= "              CASE WHEN HAI.IVENT_TARGET_FLG = '0' " . "\r\n";
        $strSQL .= "              THEN " . "\r\n";
        $strSQL .= "                    '2' " . "\r\n";
        $strSQL .= "              ELSE " . "\r\n";
        $strSQL .= "                    '1' " . "\r\n";
        $strSQL .= "                    End " . "\r\n";
        $strSQL .= "         END) FLG " . "\r\n";
        $strSQL .= " ,      to_char(WKMN.CREATE_DATE,'yyyy/mm/dd hh24:mi:ss') as CREATE_DATE " . "\r\n";
        $strSQL .= " ,      HAI.IVENT_TARGET_FLG  IVENT_TARGET_FLG " . "\r\n";
        $strSQL .= " FROM   HSYAINMST SYA " . "\r\n";
        $strSQL .= " INNER JOIN HHAIZOKU HAI " . "\r\n";
        $strSQL .= " ON     HAI.SYAIN_NO = SYA.SYAIN_NO " . "\r\n";
        $strSQL .= " AND    HAI.START_DATE <= TO_CHAR(SYSDATE,'YYYYMMDD') " . "\r\n";
        $strSQL .= " AND    NVL(HAI.END_DATE,'99999999') >= TO_CHAR(SYSDATE,'YYYYMMDD') " . "\r\n";
        $strSQL .= " INNER JOIN HBUSYO MST " . "\r\n";
        $strSQL .= " ON     MST.BUSYO_CD = HAI.BUSYO_CD " . "\r\n";
        $strSQL .= " AND    MST.HDT_TENPO_CD = '@BUSYOCD' " . "\r\n";
        $strSQL .= " LEFT JOIN HDTWORKMANAGE WKMN " . "\r\n";
        $strSQL .= " ON     WKMN.SYAIN_NO = SYA.SYAIN_NO " . "\r\n";
        $strSQL .= " AND    WKMN.IVENT_DATE = '@IVENTDT' " . "\r\n";
        $strSQL .= " WHERE  (NVL(HAI.SYOKUSYU_KB,' ') <> '9' " . "\r\n";
        $strSQL .= " AND     NVL(SYA.TAISYOKU_DATE,'99999999') > TO_CHAR(SYSDATE,'YYYYMMDD')) " . "\r\n";
        $strSQL .= " ORDER BY HAI.BUSYO_CD " . "\r\n";
        $strSQL .= " ,        SYA.SYAIN_NO " . "\r\n";
        $strSQL = str_replace("@BUSYOCD", $BUSYOCD, $strSQL);
        $strSQL = str_replace("@IVENTDT", $IVENTDT, $strSQL);
        return parent::select($strSQL);
    }

    //確報データの存在データSQL
    public function CHECK_KAKU_SQL($IDATE, $SYAINNO)
    {
        $strSQL = "";
        $strSQL .= " SELECT UPD_PRG_ID" . "\r\n";
        $strSQL .= " FROM HDTKAKUHOUDATA" . "\r\n";
        $strSQL .= " WHERE IVENT_DATE = '@IDATE'" . "\r\n";
        $strSQL .= " AND    SYAIN_NO = '@SYAINNO'" . "\r\n";

        $strSQL = str_replace("@IDATE", $IDATE, $strSQL);
        $strSQL = str_replace("@SYAINNO", $SYAINNO, $strSQL);
        return parent::select($strSQL);
    }

    //速報データの存在データSQL
    public function CHECK_SOKU_SQL($IDATE, $SYAINNO)
    {
        $strSQL = "";
        $strSQL .= " SELECT UPD_PRG_ID" . "\r\n";
        $strSQL .= " FROM HDTSOKUHOUDATA" . "\r\n";
        $strSQL .= " WHERE  IVENT_DATE = '@IDATE'" . "\r\n";
        $strSQL .= " AND    SYAIN_NO = '@SYAINNO'" . "\r\n";

        $strSQL = str_replace("@IDATE", $IDATE, $strSQL);
        $strSQL = str_replace("@SYAINNO", $SYAINNO, $strSQL);
        return parent::select($strSQL);
    }

    //出勤管理データを削除する
    public function DATA_DEL_SQL($IVENTDT, $BUSYOCD)
    {
        $strSQL = "";
        $strSQL .= " DELETE FROM HDTWORKMANAGE " . "\r\n";
        $strSQL .= " WHERE  IVENT_DATE = '@IVENTDT'" . "\r\n";
        $strSQL .= " AND    BUSYO_CD = '@BUSYOCD'" . "\r\n";

        $strSQL = str_replace("@IVENTDT", $IVENTDT, $strSQL);
        $strSQL = str_replace("@BUSYOCD", $BUSYOCD, $strSQL);
        return parent::delete($strSQL);
    }

    //チェックボックスをチェックした場合、出勤管理データに追加する
    public function DATA_INS_SQL($params)
    {
        $strSQL = "";
        $strSQL .= " INSERT INTO HDTWORKMANAGE " . "\r\n";
        $strSQL .= " (START_DATE,              " . "\r\n";
        $strSQL .= "  BUSYO_CD,                " . "\r\n";
        $strSQL .= "  IVENT_DATE,              " . "\r\n";
        $strSQL .= "  SYAIN_NO,                " . "\r\n";
        $strSQL .= "  WORK_STATE,              " . "\r\n";
        $strSQL .= "  UPD_DATE,                " . "\r\n";
        $strSQL .= "  CREATE_DATE,             " . "\r\n";
        $strSQL .= "  UPD_SYA_CD,              " . "\r\n";
        $strSQL .= "  UPD_PRG_ID,              " . "\r\n";
        $strSQL .= "  UPD_CLT_NM )             " . "\r\n";
        $strSQL .= "  VALUES(                  " . "\r\n";
        $strSQL .= "  '@START_DATE',           " . "\r\n";
        $strSQL .= "  '@BUSYO_CD',             " . "\r\n";
        $strSQL .= "  '@IVENT_DATE',           " . "\r\n";
        $strSQL .= "  '@SYAIN_NO',             " . "\r\n";
        $strSQL .= "  @WORK_STATE,              " . "\r\n";
        $strSQL .= "  SYSDATE,             " . "\r\n";
        $strSQL .= "  @CREATE_DATE,          " . "\r\n";
        $strSQL .= "  '@UPD_SYA_CD',           " . "\r\n";
        $strSQL .= "  '@UPD_PRG_ID',           " . "\r\n";
        $strSQL .= "  '@UPD_CLT_NM' )          " . "\r\n";

        $strSQL = str_replace("@START_DATE", $params['START_DATE'], $strSQL);
        $strSQL = str_replace("@BUSYO_CD", $params['BUSYO_CD'], $strSQL);
        $strSQL = str_replace("@IVENT_DATE", $params['IVENT_DATE'], $strSQL);
        $strSQL = str_replace("@SYAIN_NO", $params['SYAIN_NO'], $strSQL);
        if ($params['chkYasumi'] == "1") {
            $strSQL = str_replace("@WORK_STATE", "2", $strSQL);
        } else {
            $strSQL = str_replace("@WORK_STATE", "1", $strSQL);
        }
        if (!$params['lblCreateDate'] || $params['lblCreateDate'] == "") {
            $strSQL = str_replace("@CREATE_DATE", "SYSDATE", $strSQL);
        } else {
            $strSQL = str_replace("@CREATE_DATE", "to_date('" . $params['lblCreateDate'] . "','yyyy/mm/dd hh24:mi:ss')", $strSQL);
        }
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", "AttendanceControl", $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return parent::insert($strSQL);
    }

    //登録可能な展示会開催期間であるかのチェックを行う
    public function KAKUTEI_FLG_SEL($IVENTDT)
    {
        $strSQL = "";
        $strSQL .= " SELECT KAKUTEI_FLG " . "\r\n";
        $strSQL .= " FROM   HDTSOKUHOUKAKUTEI " . "\r\n";
        $strSQL .= " WHERE  IVENT_DATE= '@IVENTDT' " . "\r\n";

        $strSQL = str_replace("@IVENTDT", $IVENTDT, $strSQL);
        return parent::select($strSQL);
    }

}