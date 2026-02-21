<?php
// 共通クラスの読込み
namespace App\Model\HMTVE;
use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE250ReportPlaceCntTotal extends ClsComDb
{
    //取得データをグリッドビューにバインドする
    public function getObjDateSql()
    {
        $strSQL = "";
        $strSQL .= "SELECT MIN(YM) IVENTMIN" . "\r\n";
        $strSQL .= ",      MAX(YM) IVENTMAX" . "\r\n";
        $strSQL .= ",      to_char(ADD_MONTHS(SYSDATE,-1),'yyyy/mm/dd hh24:mi:ss') TD	" . "\r\n";
        $strSQL .= "FROM HDTSTORAGEPLACEREPORT " . "\r\n";

        return parent::select($strSQL);
    }

    //部署データ取得
    public function getPartSql($yearMon)
    {
        $strSQL = "";
        $strSQL .= "SELECT DT.BUSYO_CD" . "\r\n";
        $strSQL .= ",      MST.BUSYO_RYKNM" . "\r\n";
        $strSQL .= "FROM   HDTSTORAGEPLACEREPORT DT	" . "\r\n";
        $strSQL .= "INNER JOIN HBUSYO MST" . "\r\n";
        $strSQL .= "ON MST.STD_TENPO_DISP_NO IS NOT NULL" . "\r\n";
        $strSQL .= "INNER JOIN HBUSYO BUS" . "\r\n";
        $strSQL .= "ON     MST.BUSYO_CD = BUS.TENPO_CD" . "\r\n";
        $strSQL .= "WHERE(BUS.BUSYO_CD = DT.BUSYO_CD)" . "\r\n";
        $strSQL .= "AND    DT.YM = '@NENGETU'" . "\r\n";
        $strSQL .= "GROUP BY DT.BUSYO_CD" . "\r\n";
        $strSQL .= ", MST.BUSYO_RYKNM" . "\r\n";
        $strSQL .= ", MST.STD_TENPO_DISP_NO" . "\r\n";
        $strSQL .= "ORDER BY MST.STD_TENPO_DISP_NO" . "\r\n";
        //@NENGETU = 画面項目NO5.対象年月(年) & 画面項目NO6.対象年月(月)
        $strSQL = str_replace("@NENGETU", $yearMon, $strSQL);

        return parent::select($strSQL);
    }

    //ロック解除を行う
    public function updateHdtorSql($yearMon)
    {
        $strSQL = "";
        $strSQL .= "UPDATE HDTSTORAGEPLACEKAKUTEI " . "\r\n";
        $strSQL .= "SET    KAKUTEI_FLG = '0'	" . "\r\n";
        $strSQL .= "WHERE (KAKUTEI_FLG = 1)" . "\r\n";
        $strSQL .= "AND    YM = '@NENGETU'" . "\r\n";
        //@NENGETU = 画面項目NO5.対象年月(年) & 画面項目NO6.対象年月(月)
        $strSQL = str_replace("@NENGETU", $yearMon, $strSQL);

        return parent::update($strSQL);
    }

    //保管場所届出件数
    public function getManageLocaleSql($yearMon)
    {
        $strSQL = "";
        $strSQL .= "SELECT YM" . "\r\n";
        $strSQL .= "FROM HDTSTORAGEPLACEKAKUTEI " . "\r\n";
        $strSQL .= "WHERE  YM = '@NENGETU'" . "\r\n";
        $strSQL = str_replace("@NENGETU", $yearMon, $strSQL);

        return parent::select($strSQL);
    }

    //保管場所届出を追加処理
    public function insertManageLocaleSql($yearMon)
    {
        $strSQL = "";
        $strSQL .= "INSERT INTO HDTSTORAGEPLACEKAKUTEI" . "\r\n";
        $strSQL .= "(      YM						" . "\r\n";
        $strSQL .= ",      KAKUTEI_FLG				" . "\r\n";
        $strSQL .= ",      UPD_DATE				" . "\r\n";
        $strSQL .= ",      CREATE_DATE			" . "\r\n";
        $strSQL .= ",      UPD_SYA_CD			" . "\r\n";
        $strSQL .= ",      UPD_PRG_ID			" . "\r\n";
        $strSQL .= ",      UPD_CLT_NM			" . "\r\n";
        $strSQL .= ")						" . "\r\n";
        $strSQL .= "VALUES ('@NENGETU'		" . "\r\n";
        $strSQL .= ",       '1'				" . "\r\n";
        $strSQL .= ",       SYSDATE			" . "\r\n";
        $strSQL .= ",       SYSDATE			" . "\r\n";
        $strSQL .= ",       '@UPD_SYA_CD'	" . "\r\n";
        $strSQL .= ",       'ReportPlac'" . "\r\n";
        $strSQL .= ",       '@UPD_CLT_NM'		" . "\r\n";
        $strSQL .= ")							" . "\r\n";

        $strSQL = str_replace("@NENGETU", $yearMon, $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return parent::insert($strSQL);
    }

    //更新処理
    public function updateHdtorSql2($yearMon)
    {
        $strSQL = "";
        $strSQL .= "UPDATE HDTSTORAGEPLACEKAKUTEI " . "\r\n";
        $strSQL .= "SET    KAKUTEI_FLG = '1'	" . "\r\n";
        $strSQL .= "WHERE YM = '@NENGETU'" . "\r\n";
        //@NENGETU = 画面項目NO5.対象年月(年) & 画面項目NO6.対象年月(月)
        $strSQL = str_replace("@NENGETU", $yearMon, $strSQL);

        return parent::update($strSQL);
    }

    //軽自動車保管場所届出件数データの出力ﾌﾗｸﾞを"1"で更新する
    public function updateCar($yearMon)
    {
        $strSQL = "";
        $strSQL .= "UPDATE HDTSTORAGEPLACEREPORT " . "\r\n";
        $strSQL .= "SET    OUT_FLG = '1'" . "\r\n";
        $strSQL .= "WHERE  YM >= '@NENGETU'	" . "\r\n";
        //@NENGETU = 画面項目NO5.対象年月(年) & 画面項目NO6.対象年月(月)
        $strSQL = str_replace("@NENGETU", $yearMon, $strSQL);

        return parent::update($strSQL);
    }

    //Excel出力データを取ります
    public function getExcelExportSql($yearMon)
    {
        $strSQL = "";
        $strSQL .= "SELECT DT.BUSYO_CD" . "\r\n";
        $strSQL .= ",      MST.BUSYO_RYKNM		" . "\r\n";
        $strSQL .= ",      DT.SINSEI_KB		" . "\r\n";
        $strSQL .= ",      SUM(DT.SINSEI_CNT) SINSEICNT	" . "\r\n";
        $strSQL .= ",      SUM(DT.TODOKE_CNT) TODOKECNT	" . "\r\n";
        $strSQL .= ",      SUM(DT.KAKUNIN_CNT) KAKUNINCNT" . "\r\n";
        $strSQL .= ",      DECODE(SUM(DT.SINSEI_CNT),0,0,ROUND(((SUM(DT.TODOKE_CNT) + SUM(DT.KAKUNIN_CNT))/ SUM(DT.SINSEI_CNT)) * 100,1)) AS TODOKE_RITU" . "\r\n";
        $strSQL .= "FROM   HDTSTORAGEPLACEREPORT DT	" . "\r\n";
        $strSQL .= "INNER JOIN HBUSYO MST	" . "\r\n";
        $strSQL .= "ON     MST.STD_TENPO_DISP_NO IS NOT NULL		" . "\r\n";
        $strSQL .= "INNER JOIN HBUSYO BUS	" . "\r\n";
        $strSQL .= "ON     MST.BUSYO_CD = BUS.TENPO_CD	" . "\r\n";
        $strSQL .= "        WHERE(BUS.BUSYO_CD = DT.BUSYO_CD)" . "\r\n";
        $strSQL .= "AND    DT.YM = '@NENGETU'" . "\r\n";
        $strSQL .= "GROUP BY DT.BUSYO_CD		" . "\r\n";
        $strSQL .= ",        MST.BUSYO_RYKNM		" . "\r\n";
        $strSQL .= ",        MST.STD_TENPO_DISP_NO	" . "\r\n";
        $strSQL .= ",        DT.SINSEI_KB			" . "\r\n";
        $strSQL .= "ORDER BY MST.STD_TENPO_DISP_NO	" . "\r\n";
        $strSQL .= ",        DT.BUSYO_CD			" . "\r\n";
        $strSQL .= ",        DT.SINSEI_KB		" . "\r\n";
        //@NENGETU = 画面項目NO5.対象年月(年) & 画面項目NO6.対象年月(月)
        $strSQL = str_replace("@NENGETU", $yearMon, $strSQL);

        return parent::select($strSQL);
    }

    //成約プレゼント確定データの更新処理を行う
    public function updateAppoint($yearMon)
    {
        $strSQL = "";
        $strSQL .= "UPDATE HDTSTORAGEPLACEKAKUTEI" . "\r\n";
        $strSQL .= "SET    KAKUTEI_FLG = '0'	" . "\r\n";
        $strSQL .= "WHERE  YM >= '@NENGETU'	" . "\r\n";
        //@NENGETU = 画面項目NO5.対象年月(年) & 画面項目NO6.対象年月(月)
        $strSQL = str_replace("@NENGETU", $yearMon, $strSQL);

        return parent::update($strSQL);
    }

    //未出力データが存在しないかチェックする
    public function getNotexport($yearMon)
    {
        $strSQL = "";
        $strSQL .= "SELECT COUNT(YM) CNT	" . "\r\n";
        $strSQL .= "FROM(HDTSTORAGEPLACEREPORT)" . "\r\n";
        $strSQL .= "WHERE  OUT_FLG = '0'	" . "\r\n";
        $strSQL .= "AND    YM = '@NENGETU'" . "\r\n";
        //@NENGETU = 画面項目NO5.対象年月(年) & 画面項目NO6.対象年月(月)
        $strSQL = str_replace("@NENGETU", $yearMon, $strSQL);

        return parent::select($strSQL);
    }

}
