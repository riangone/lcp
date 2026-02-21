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
class HMTVE040InputDataS extends ClsComDb
{
    public $ClsComFncHMTVE = null;
    public $SessionComponent;
    public function setExhibitTermDateSql()
    {
        $strSQL = "";
        $strSQL .= "SELECT  START_DATE " . "\r\n";
        $strSQL .= ",       END_DATE " . "\r\n";
        $strSQL .= "FROM    HDTIVENTDATA " . "\r\n";
        $strSQL .= "WHERE   BASE_FLG = '1' " . "\r\n";

        return parent::select($strSQL);
    }

    public function checkUserWorkSql($ddlExhibitDay)
    {
        $strSQL = "";
        $strSQL .= "SELECT HAI.SYAIN_NO" . "\r\n";
        $strSQL .= ",      HAI.IVENT_TARGET_FLG" . "\r\n";
        $strSQL .= ",      WK.WORK_STATE" . "\r\n";
        $strSQL .= "FROM   HHAIZOKU HAI" . "\r\n";
        $strSQL .= "LEFT JOIN HDTWORKMANAGE WK" . "\r\n";
        $strSQL .= "ON     HAI.SYAIN_NO = WK.SYAIN_NO" . "\r\n";
        $strSQL .= "AND    WK.IVENT_DATE = '@IVENTDT'" . "\r\n";
        $strSQL .= "WHERE  HAI.START_DATE <= '@IVENTDT'" . "\r\n";
        $strSQL .= "AND    NVL(HAI.END_DATE,'99999999') >= '@IVENTDT'" . "\r\n";
        $strSQL .= "AND    HAI.SYAIN_NO = '@SYAINNO'" . "\r\n";
        $strSQL .= "AND    ((HAI.IVENT_TARGET_FLG = '0' AND (WK.START_DATE IS NULL OR WK.WORK_STATE = '2'))" . "\r\n";
        $strSQL .= "       OR" . "\r\n";
        $strSQL .= "       (HAI.IVENT_TARGET_FLG = '1' AND WK.WORK_STATE = '2'))" . "\r\n";

        $strSQL = str_replace("@SYAINNO", $this->GS_LOGINUSER['strUserID'], $strSQL);

        $strSQL = str_replace("@IVENTDT", str_replace("/", '', $ddlExhibitDay), $strSQL);
        return parent::select($strSQL);
    }

    public function getCarItem($ddlExhibitDay)
    {
        $strSQL = "";
        $strSQL .= " SELECT SYA.SYASYU_CD " . "\r\n";
        $strSQL .= " ,      SYA.SYASYU_RYKNM " . "\r\n";
        $strSQL .= " ,      SOKU.SEIYAKU_DAISU " . "\r\n";
        $strSQL .= " ,      to_char(SOKU.CREATE_DATE,'yyyy/mm/dd hh24:mi:ss') as CREATE_DATE " . "\r\n";
        $strSQL .= " ,      SYA.SYASYU_RYKNM " . "\r\n";
        $strSQL .= " ,      (SELECT COUNT(SYASYU_CD) FROM HDTSOKUHOUDATA WHERE SYAIN_NO='@SYAIN' AND IVENT_DATE = '@IVENTDT') FLG " . "\r\n";
        $strSQL .= " FROM   HDTSYASYU SYA " . "\r\n";
        $strSQL .= " LEFT JOIN HDTSOKUHOUDATA SOKU " . "\r\n";
        $strSQL .= " ON     SOKU.SYASYU_CD = SYA.SYASYU_CD " . "\r\n";
        $strSQL .= " AND    SOKU.SYAIN_NO = '@SYAIN' " . "\r\n";
        $strSQL .= " AND    SOKU.IVENT_DATE = '@IVENTDT' " . "\r\n";
        $strSQL .= " ORDER BY SYA.DISP_NO " . "\r\n";
        $strSQL .= " ,        SYA.SYASYU_CD" . "\r\n";

        $strSQL = str_replace("@SYAIN", $this->GS_LOGINUSER['strUserID'], $strSQL);

        $strSQL = str_replace("@IVENTDT", str_replace("/", '', $ddlExhibitDay), $strSQL);
        return parent::select($strSQL);
    }

    public function checkExhibitTermDate($ddlExhibitDay)
    {
        $strSQL = "";
        $strSQL .= " SELECT KAKUTEI_FLG " . "\r\n";
        $strSQL .= " FROM   HDTSOKUHOUKAKUTEI " . "\r\n";
        $strSQL .= " WHERE  START_DATE = '@IVENTDT' " . "\r\n";

        $strSQL = str_replace("@IVENTDT", str_replace("/", '', $ddlExhibitDay), $strSQL);
        return parent::select($strSQL);
    }

    public function checkExhibitTermDateDelete($ddlExhibitDay)
    {
        $strSQL = "";
        $strSQL .= " SELECT KAKUTEI_FLG " . "\r\n";
        $strSQL .= " FROM   HDTSOKUHOUKAKUTEI " . "\r\n";
        $strSQL .= " WHERE  IVENT_DATE = '@IVENTDT' " . "\r\n";

        $strSQL = str_replace("@IVENTDT", str_replace("/", '', $ddlExhibitDay), $strSQL);
        return parent::select($strSQL);
    }

    public function updateDataSQL($ddlExhibitDay)
    {
        $strSQL = "";
        $strSQL .= " DELETE FROM HDTSOKUHOUDATA " . "\r\n";
        $strSQL .= " WHERE  IVENT_DATE = '@IVENTDT' " . "\r\n";
        $strSQL .= " AND    SYAIN_NO = '@SYAINNO' " . "\r\n";

        $strSQL = str_replace("@SYAINNO", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@IVENTDT", str_replace("/", '', $ddlExhibitDay), $strSQL);
        return parent::delete($strSQL);
    }

    public function insertSql($postdata, $tabledata)
    {
        $this->ClsComFncHMTVE = new ClsComFncHMTVE();
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSQL = "";
        $strSQL .= " INSERT INTO HDTSOKUHOUDATA" . "\r\n";
        $strSQL .= " (START_DATE,              " . "\r\n";
        $strSQL .= "  BUSYO_CD,                 " . "\r\n";
        $strSQL .= "  SYAIN_NO,                 " . "\r\n";
        $strSQL .= "  IVENT_DATE,               " . "\r\n";
        $strSQL .= "  SYASYU_CD,                " . "\r\n";
        $strSQL .= "  SEIYAKU_DAISU,            " . "\r\n";
        $strSQL .= "  OUT_FLG,                  " . "\r\n";
        $strSQL .= "  UPD_DATE,                 " . "\r\n";
        $strSQL .= "  CREATE_DATE,              " . "\r\n";
        $strSQL .= "  UPD_SYA_CD,               " . "\r\n";
        $strSQL .= "  UPD_PRG_ID,               " . "\r\n";
        $strSQL .= "  UPD_CLT_NM)               " . "\r\n";
        $strSQL .= "  VALUES(                   " . "\r\n";
        $strSQL .= "  '@START_DATE',            " . "\r\n";
        $strSQL .= "  '@BUSYO_CD',              " . "\r\n";
        $strSQL .= "  '@SYAIN_NO',              " . "\r\n";
        $strSQL .= "  '@IVENT_DATE',            " . "\r\n";
        $strSQL .= "  '@SYASYU_CD',             " . "\r\n";
        $strSQL .= "  '@SEIYAKU_DAISU',         " . "\r\n";
        $strSQL .= "  '0',                      " . "\r\n";
        $strSQL .= "   SYSDATE,              " . "\r\n";
        $strSQL .= "   @CREATE_DATE,           " . "\r\n";
        $strSQL .= "  '@UPD_SYA_CD',            " . "\r\n";
        $strSQL .= "  'InputData_S',            " . "\r\n";
        $strSQL .= "  '@UPD_CLT_NM')            " . "\r\n";
        $ddlExhibitDay = $postdata['ddlExhibitDay'];
        $ivenDaye = $postdata['lblExhibitTermFrom'];
        $strSQL = str_replace("@START_DATE", str_replace("/", '', $ivenDaye), $strSQL);
        $strSQL = str_replace("@BUSYO_CD", $this->SessionComponent->read('BusyoCD'), $strSQL);
        $strSQL = str_replace("@SYAIN_NO", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@IVENT_DATE", str_replace("/", '', $ddlExhibitDay), $strSQL);
        $strSQL = str_replace("@SYASYU_CD", $tabledata['SYASYU_CD'], $strSQL);
        $strSQL = str_replace("@SEIYAKU_DAISU", $this->ClsComFncHMTVE->FncNz($tabledata['SEIYAKU_DAISU']), $strSQL);
        if (trim($tabledata['CREATE_DATE']) == null || trim($tabledata['CREATE_DATE']) == '') {
            $strSQL = str_replace("@CREATE_DATE", 'SYSDATE', $strSQL);
        } else {
            $strSQL = str_replace("@CREATE_DATE", "to_date('" . $tabledata['CREATE_DATE'] . "','yyyy-mm-dd hh24:mi:ss')", $strSQL);
        }
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        return parent::insert($strSQL);
    }

}
