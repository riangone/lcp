<?php
// 共通クラスの読込み
namespace App\Model\HMTVE;
use App\Model\Component\ClsComDb;
use App\Model\HMTVE\Component\ClsComFncHMTVE;
use Cake\Routing\Router;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE240ReportPlaceCntEntry extends ClsComDb
{
    public $ClsComFncHMTVE;
    public $SessionComponent;
    public function ExpressShopName()
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSQL = "";
        $strSQL .= " Select MST.BUSYO_CD" . "\r\n";
        $strSQL .= ",      MST.BUSYO_RYKNM" . "\r\n";
        $strSQL .= "FROM HBUSYO MST " . "\r\n";
        $strSQL .= "INNER JOIN HBUSYO BUS" . "\r\n";
        $strSQL .= "ON     MST.BUSYO_CD = BUS.TENPO_CD" . "\r\n";
        $strSQL .= "WHERE(MST.STD_TENPO_DISP_NO Is Not NULL)" . "\r\n";
        $strSQL .= "AND    BUS.BUSYO_CD = '@BUSYOCD'" . "\r\n";

        $strSQL = str_replace("@BUSYOCD", $this->SessionComponent->read('BusyoCD'), $strSQL);
        return parent::select($strSQL);
    }

    public function getReporterSQL($ymd)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSQL = "";
        $strSQL .= "Select YM" . "\r\n";
        $strSQL .= ",      BUSYO_CD" . "\r\n";
        $strSQL .= ",      SYAIN_NO" . "\r\n";
        $strSQL .= ",      SINSEI_KB" . "\r\n";
        $strSQL .= ",      SINSEI_CNT" . "\r\n";
        $strSQL .= ",      TODOKE_CNT" . "\r\n";
        $strSQL .= ",      KAKUNIN_CNT" . "\r\n";
        $strSQL .= ",      CREATE_DATE" . "\r\n";
        $strSQL .= "FROM HDTSTORAGEPLACEREPORT " . "\r\n";
        $strSQL .= "WHERE  YM = '@NENGETU'" . "\r\n";
        $strSQL .= "AND    BUSYO_CD = '@BUSYOCD'" . "\r\n";
        $strSQL .= "AND    SYAIN_NO = '@SYAINNO'" . "\r\n";
        $strSQL .= "ORDER BY SINSEI_KB" . "\r\n";
        $strSQL = str_replace("@NENGETU", $ymd, $strSQL);
        $strSQL = str_replace("@SYAINNO", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@BUSYOCD", $this->SessionComponent->read('BusyoCD'), $strSQL);
        return parent::select($strSQL);
    }

    public function getManageLocaleSQL($ymd)
    {
        $strSQL = "";
        $strSQL .= "SELECT  KAKUTEI_FLG" . "\r\n";
        $strSQL .= "FROM HDTSTORAGEPLACEKAKUTEI " . "\r\n";
        $strSQL .= "WHERE  YM = '@NENGETU'" . "\r\n";
        $strSQL = str_replace("@NENGETU", $ymd, $strSQL);
        return parent::select($strSQL);
    }

    public function DeleteCarMLSQl($ymd)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSQL = "";
        $strSQL .= "DELETE  FROM　HDTSTORAGEPLACEREPORT " . "\r\n";
        $strSQL .= "WHERE YM = '@NENGETU' " . "\r\n";
        $strSQL .= "AND BUSYO_CD = '@BUSYOCD'	" . "\r\n";
        $strSQL .= "AND SYAIN_NO = '@SYAINNO'	" . "\r\n";
        $strSQL = str_replace("@NENGETU", $ymd, $strSQL);
        $strSQL = str_replace("@SYAINNO", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@BUSYOCD", $this->SessionComponent->read('BusyoCD'), $strSQL);
        return parent::delete($strSQL);
    }

    public function InsertCarMLSQl($ymd, $SINSEI_KB, $SINSEI_CNT, $TODOKE_CNT, $KAKUNIN_CNT)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $this->ClsComFncHMTVE = new ClsComFncHMTVE();
        $strSQL = "";
        $strSQL .= "INSERT INTO HDTSTORAGEPLACEREPORT(YM,BUSYO_CD" . "\r\n";
        $strSQL .= ",SYAIN_NO,SINSEI_KB,SINSEI_CNT" . "\r\n";
        $strSQL .= ",TODOKE_CNT,KAKUNIN_CNT,OUT_FLG,UPD_DATE" . "\r\n";
        $strSQL .= ",CREATE_DATE,UPD_SYA_CD,UPD_PRG_ID,UPD_CLT_NM)" . "\r\n";
        $strSQL .= "VALUES('@YM','@BUSYO_CD','@SYAIN_NO'" . "\r\n";
        $strSQL .= ",'@SINSEI_KB','@SINSEI_CNT','@TODOKE_CNT'" . "\r\n";
        $strSQL .= ",'@KAKUNIN_CNT',0" . "\r\n";
        $strSQL .= ",SYSDATE,@CREATE_DATE,'@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",'@UPD_PRG_ID','@UPD_CLT_NM')" . "\r\n";
        $strSQL = str_replace("@YM", $ymd, $strSQL);
        $strSQL = str_replace("@SYAIN_NO", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@BUSYO_CD", $this->SessionComponent->read('BusyoCD'), $strSQL);
        $strSQL = str_replace("@CREATE_DATE", "SYSDATE", $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", "ReportPlac", $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@SINSEI_KB", $SINSEI_KB, $strSQL);
        $strSQL = str_replace("@SINSEI_CNT", $SINSEI_CNT == '' ? 0 : $SINSEI_CNT, $strSQL);
        $strSQL = str_replace("@TODOKE_CNT", $TODOKE_CNT == '' ? 0 : $TODOKE_CNT, $strSQL);
        $strSQL = str_replace("@KAKUNIN_CNT", $KAKUNIN_CNT == '' ? 0 : $KAKUNIN_CNT, $strSQL);
        return parent::insert($strSQL);
    }

}
