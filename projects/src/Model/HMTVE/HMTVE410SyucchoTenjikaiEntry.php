<?php
// 共通クラスの読込み
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE410SyucchoTenjikaiEntry extends ClsComDb
{
    //*************************************
    // * SQL文
    //*************************************

    //対象期間のＳＱＬ文を取得
    function getTermSQL($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT MIN(KAISAI_YMD) HI_MIN " . "\r\n";
        $strSQL .= ",      MAX(KAISAI_YMD) HI_MAX " . "\r\n";
        $strSQL .= " FROM   HDTSYUCCHOTENJIKAI " . "\r\n";

        if ($postData['txtExhibitTitle1'] != "") {
            $strSQL .= " WHERE TENPO_CD ='" . $postData['txtExhibitTitle1'] . "'" . "\r\n";
        }

        return $strSQL;
    }

    //一覧データのＳＱＬ文を取得
    function getIntroductionSQL($postData, $flg)
    {
        $strSQL = "";
        $strSQL .= " SELECT " . "\r\n";
        $strSQL .= "        ST.LIST_MEISAI_NO " . "\r\n";
        $strSQL .= " ,      TO_CHAR(TO_DATE(ST.KAISAI_YMD,'YYYY/MM/DD'),'YYYY/MM/DD') KAISAI_YMD " . "\r\n";
        $strSQL .= " ,      ST.KAISAI_YMD AS KAISAI_YMD1" . "\r\n";
        $strSQL .= " ,      ST.START_TIME " . "\r\n";
        $strSQL .= " ,      ST.END_TIME " . "\r\n";
        $strSQL .= " ,      ST.PLACE " . "\r\n";
        $strSQL .= " ,      ST.DEMO_CARS " . "\r\n";
        $strSQL .= " ,      MST.BUSYO_CD " . "\r\n";
        $strSQL .= " ,      MST.BUSYO_RYKNM " . "\r\n";
        $strSQL .= " ,      SYA.SYAIN_NM " . "\r\n";
        $strSQL .= " ,      NVL(ST.RAIJYO_SU,0) RAIJYO_SU" . "\r\n";
        $strSQL .= " ,      NVL(ST.ENQUETE_SU,0) ENQUETE_SU  " . "\r\n";
        $strSQL .= " ,      NVL(ST.ABHOT_SU,0) ABHOT_SU" . "\r\n";
        $strSQL .= " ,      NVL(ST.MITUMORI_SU,0) MITUMORI_SU" . "\r\n";
        $strSQL .= " ,      NVL(ST.SEIYAKU_SU,0) SEIYAKU_SU" . "\r\n";
        $strSQL .= " ,      ST.TENPO_CD " . "\r\n";
        $strSQL .= " ,      ST.SYAIN_NO " . "\r\n";
        $strSQL .= " ,      ST.UPD_DATE " . "\r\n";
        $strSQL .= " ,      ST.CREATE_DATE" . "\r\n";
        $strSQL .= " ,      ST.UPD_PRG_ID " . "\r\n";
        $strSQL .= " ,      ST.UPD_CLT_NM " . "\r\n";
        $strSQL .= " FROM   HDTSYUCCHOTENJIKAI ST " . "\r\n";
        $strSQL .= " LEFT JOIN HSYAINMST SYA " . "\r\n";
        $strSQL .= " ON     SYA.SYAIN_NO = ST.SYAIN_NO " . "\r\n";
        $strSQL .= "LEFT JOIN  (SELECT BUSYO_CD" . "\r\n";
        $strSQL .= "            ,      (CASE WHEN HDT_TENPO_CD IS NOT NULL" . "\r\n";
        $strSQL .= "                        THEN HDT_TENPO_CD ELSE BUSYO_CD END) V_TENPO" . "\r\n";
        $strSQL .= "            FROM HBUSYO) BUS" . "\r\n";
        $strSQL .= "ON          BUS.BUSYO_CD = ST.TENPO_CD " . "\r\n";
        $strSQL .= "LEFT JOIN HBUSYO MST" . "\r\n";
        $strSQL .= "ON     MST.BUSYO_CD = BUS.V_TENPO" . "\r\n";
        $strSQL .= "AND    MST.STD_TENPO_DISP_NO IS NOT NULL" . "\r\n";
        $strSQL .= "WHERE 1=1 " . "\r\n";

        if ($flg == "") {
            if ($postData['txtExhibitTitle1'] != "") {
                $strSQL .= " AND  ST.TENPO_CD = '@BUSYOCD'  AND " . "\r\n";
                $strSQL = str_replace("@BUSYOCD", $postData['txtExhibitTitle1'], $strSQL);
            } else {
                $strSQL .= " AND  " . "\r\n";
            }

            $strSQL .= "   ST.KAISAI_YMD >= '@FROMDT' " . "\r\n";
            $strSQL = str_replace("@FROMDT", $postData['ddlYear'] . $postData['ddlMonth'] . $postData['ddlDay'], $strSQL);
            $strSQL .= "AND    ST.KAISAI_YMD <= '@TODT' " . "\r\n";
            $strSQL = str_replace("@TODT", $postData['ddlYear2'] . $postData['ddlMonth2'] . $postData['ddlDay2'], $strSQL);

        } else {
            if ($postData['txtExhibitTitle1'] != "") {
                $strSQL .= " AND  ST.TENPO_CD = '@BUSYOCD'  " . "\r\n";
                $strSQL = str_replace("@BUSYOCD", $postData['txtExhibitTitle1'], $strSQL);
            }
        }

        $strSQL .= " ORDER BY ST.KAISAI_YMD DESC,ST.START_TIME DESC " . "\r\n";

        return $strSQL;

    }

    //更新対象の紹介者確認ﾃﾞｰﾀのＳＱＬ文の取得
    function getMaxNoSQL()
    {
        $strSQL = "";
        $strSQL .= " SELECT MAX(TO_NUMBER(ST.LIST_MEISAI_NO))+1  as MAXNO  " . "\r\n";
        $strSQL .= " FROM   HDTSYUCCHOTENJIKAI ST " . "\r\n";

        return $strSQL;
    }

    //更新対象の紹介者確認ﾃﾞｰﾀのＳＱＬ文の取得
    function getReObjectSQL($postData)
    {
        $strSQL = "";
        $strSQL .= " SELECT ST.LIST_MEISAI_NO  " . "\r\n";
        $strSQL .= " FROM   HDTSYUCCHOTENJIKAI ST " . "\r\n";
        $strSQL .= "WHERE  ST.LIST_MEISAI_NO = '@NO' " . "\r\n";
        $strSQL = str_replace("@NO", $postData['txtAcceptNo'], $strSQL);

        return $strSQL;
    }

    //部署に所属する社員のＳＱＬの取得
    function getEmployeSQL($postData)
    {
        $strSQL = "";
        $strSQL .= " SELECT HAI.SYAIN_NO " . "\r\n";
        $strSQL .= " ,      SYA.SYAIN_NM " . "\r\n";
        $strSQL .= " FROM   HHAIZOKU HAI " . "\r\n";
        $strSQL .= " INNER JOIN HSYAINMST SYA " . "\r\n";
        $strSQL .= " ON     SYA.SYAIN_NO = HAI.SYAIN_NO " . "\r\n";
        $strSQL .= " AND    NVL(SYA.TAISYOKU_DATE,'99999999') > TO_CHAR(SYSDATE,'YYYYMMDD') " . "\r\n";
        $strSQL .= " WHERE  NVL(HAI.SYOKUSYU_KB,'A') <> '9' " . "\r\n";
        $strSQL .= " AND    HAI.START_DATE <= TO_CHAR(SYSDATE,'YYYYMMDD')" . "\r\n";
        $strSQL .= " AND    NVL(HAI.END_DATE,'99999999') >= TO_CHAR(SYSDATE,'YYYYMMDD')" . "\r\n";
        $strSQL .= " AND    HAI.BUSYO_CD = '@BUSYOCD' " . "\r\n";

        $strSQL = str_replace("@BUSYOCD", $postData['txtPost'], $strSQL);

        return $strSQL;
    }

    //紹介者確認データの削除のＳＱＬの取得
    function getIntroDeleteSQL($postData)
    {
        $strSQL = "";
        $strSQL .= " DELETE FROM HDTSYUCCHOTENJIKAI " . "\r\n";
        $strSQL .= "  WHERE  LIST_MEISAI_NO = '@JYURINO' " . "\r\n";

        $strSQL = str_replace("@JYURINO", $postData['txtAcceptNo'], $strSQL);

        return $strSQL;
    }

    //更新対象の紹介者確認ﾃﾞｰﾀの更新処理のＳＱＬ文の取得
    function getIntroUpdateSQL($postData)
    {
        $strSQL = "";
        $strSQL .= " UPDATE HDTSYUCCHOTENJIKAI SET " . "\r\n";
        $strSQL .= " KAISAI_YMD = @KAISAI_YMD,  " . "\r\n";
        $strSQL .= " TENPO_CD = @TENPO_CD, " . "\r\n";
        $strSQL .= " SYAIN_NO = @SYAIN_NO, " . "\r\n";
        $strSQL .= " START_TIME = @START_TIME, " . "\r\n";
        $strSQL .= " END_TIME = @END_TIME, " . "\r\n";
        $strSQL .= " PLACE = @PLACE, " . "\r\n";
        $strSQL .= " DEMO_CARS = @DEMO_CARS, " . "\r\n";
        $strSQL .= " RAIJYO_SU = @RAIJYO_SU, " . "\r\n";
        $strSQL .= " ENQUETE_SU = @ENQUETE_SU, " . "\r\n";
        $strSQL .= " ABHOT_SU = @ABHOT_SU, " . "\r\n";
        $strSQL .= " MITUMORI_SU = @MITUMORI_SU, " . "\r\n";
        $strSQL .= " SEIYAKU_SU = @SEIYAKU_SU, " . "\r\n";
        $strSQL .= " UPD_DATE = @UPD_DATE, " . "\r\n";
        $strSQL .= " UPD_SYA_CD = @UPD_SYA_CD, " . "\r\n";
        $strSQL .= " UPD_PRG_ID = @UPD_PRG_ID, " . "\r\n";
        $strSQL .= " UPD_CLT_NM = @UPD_CLT_NM" . "\r\n";

        $strSQL .= " WHERE LIST_MEISAI_NO = @LIST_MEISAI_NO" . "\r\n";

        if ($postData['txtAcceptDate'] != "") {
            $strSQL = str_replace("@KAISAI_YMD", "'" . str_replace("/", "", $postData['txtAcceptDate']) . "'", $strSQL);
        } else {
            $strSQL = str_replace("@KAISAI_YMD", "NULL", $strSQL);
        }

        if ($postData['txtPost'] != "") {
            $strSQL = str_replace("@TENPO_CD", "'" . $postData['txtPost'] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@TENPO_CD", "NULL", $strSQL);
        }

        if (!$postData['ddlDirector']) {
            $strSQL = str_replace("@SYAIN_NO", "NULL", $strSQL);

        } else {
            if ($postData['ddlDirector'] != "") {
                $strSQL = str_replace("@SYAIN_NO", "'" . $postData['ddlDirector'] . "'", $strSQL);
            } else {
                $strSQL = str_replace("@SYAIN_NO", "NULL", $strSQL);
            }
        }
        if ($postData['txtPlace'] != "") {
            $strSQL = str_replace("@PLACE", "'" . $postData['txtPlace'] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@PLACE", "NULL", $strSQL);
        }

        if ($postData['txtStartTime'] != "") {
            $strSQL = str_replace("@START_TIME", "'" . $postData['txtStartTime'] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@START_TIME", "NULL", $strSQL);
        }

        if ($postData['txtEndTime'] != "") {
            $strSQL = str_replace("@END_TIME", "'" . $postData['txtEndTime'] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@END_TIME", "NULL", $strSQL);
        }

        if ($postData['txtDemoCars'] != "") {
            $strSQL = str_replace("@DEMO_CARS", "'" . $postData['txtDemoCars'] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@DEMO_CARS", "NULL", $strSQL);
        }

        if ($postData['txtRaijoSu'] != "") {
            $strSQL = str_replace("@RAIJYO_SU", "'" . $postData['txtRaijoSu'] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@RAIJYO_SU", "NULL", $strSQL);
        }

        if ($postData['txtEnqueteSu'] != "") {
            $strSQL = str_replace("@ENQUETE_SU", "'" . $postData['txtEnqueteSu'] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@ENQUETE_SU", "NULL", $strSQL);
        }

        if ($postData['txtABHotSu'] != "") {
            $strSQL = str_replace("@ABHOT_SU", "'" . $postData['txtABHotSu'] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@ABHOT_SU", "NULL", $strSQL);
        }

        if ($postData['txtMitumoriSu'] != "") {
            $strSQL = str_replace("@MITUMORI_SU", "'" . $postData['txtMitumoriSu'] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@MITUMORI_SU", "NULL", $strSQL);
        }

        if ($postData['txtSeiyakuSu'] != "") {
            $strSQL = str_replace("@SEIYAKU_SU", "'" . $postData['txtSeiyakuSu'] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@SEIYAKU_SU", "NULL", $strSQL);
        }

        $strSQL = str_replace("@UPD_DATE", 'SYSDATE', $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", "'" . $this->GS_LOGINUSER['strUserID'] . "'", $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", "'" . 'IntroduceConfirmEntr' . "'", $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", "'" . $this->GS_LOGINUSER['strClientNM'] . "'", $strSQL);
        if ($postData['txtAcceptNo'] != "") {
            $strSQL = str_replace("@LIST_MEISAI_NO", "'" . $postData['txtAcceptNo'] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@LIST_MEISAI_NO", "NULL", $strSQL);
        }

        return $strSQL;
    }

    //ﾃﾞｰﾀの追加処理のＳＱＬ文の取得
    function getIntroInsertSQL($postData)
    {
        $strSQL = "";
        $strSQL .= "insert into HDTSYUCCHOTENJIKAI " . "\r\n";
        $strSQL .= "( LIST_MEISAI_NO " . "\r\n";
        $strSQL .= " , KAISAI_YMD " . "\r\n";
        $strSQL .= " , TENPO_CD   " . "\r\n";
        $strSQL .= " , SYAIN_NO " . "\r\n";
        $strSQL .= " , START_TIME " . "\r\n";
        $strSQL .= " , END_TIME " . "\r\n";
        $strSQL .= " , PLACE " . "\r\n";
        $strSQL .= " , DEMO_CARS " . "\r\n";

        $strSQL .= " , RAIJYO_SU " . "\r\n";
        $strSQL .= " , ENQUETE_SU " . "\r\n";
        $strSQL .= " , ABHOT_SU " . "\r\n";
        $strSQL .= " , MITUMORI_SU " . "\r\n";
        $strSQL .= " , SEIYAKU_SU " . "\r\n";

        $strSQL .= " , UPD_DATE " . "\r\n";
        $strSQL .= " , CREATE_DATE " . "\r\n";
        $strSQL .= " , UPD_SYA_CD  " . "\r\n";
        $strSQL .= " , UPD_PRG_ID " . "\r\n";
        $strSQL .= " , UPD_CLT_NM " . "\r\n";
        $strSQL .= ") values " . "\r\n";

        $strSQL .= "(" . "\r\n";
        $strSQL .= "   @LIST_MEISAI_NO " . "\r\n";
        $strSQL .= " , @KAISAI_YMD " . "\r\n";
        $strSQL .= " , @TENPO_CD " . "\r\n";
        $strSQL .= " , @SYAIN_NO " . "\r\n";
        $strSQL .= " , @START_TIME " . "\r\n";
        $strSQL .= " , @END_TIME " . "\r\n";
        $strSQL .= " , @PLACE " . "\r\n";
        $strSQL .= " , @DEMO_CARS " . "\r\n";

        $strSQL .= " , @RAIJYO_SU " . "\r\n";
        $strSQL .= " , @ENQUETE_SU " . "\r\n";
        $strSQL .= " , @ABHOT_SU " . "\r\n";
        $strSQL .= " , @MITUMORI_SU " . "\r\n";
        $strSQL .= " , @SEIYAKU_SU " . "\r\n";

        $strSQL .= " , @UPD_DATE " . "\r\n";
        $strSQL .= " , @CREATE_DATE " . "\r\n";
        $strSQL .= " , @UPD_SYA_CD " . "\r\n";
        $strSQL .= " , @UPD_PRG_ID " . "\r\n";
        $strSQL .= " , @UPD_CLT_NM " . "\r\n";
        $strSQL .= " )" . "\r\n";

        if ($postData['txtAcceptNo'] != "") {
            $strSQL = str_replace("@LIST_MEISAI_NO", "'" . $postData['txtAcceptNo'] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@LIST_MEISAI_NO", "NULL", $strSQL);
        }

        if ($postData['txtAcceptDate'] != "") {
            $strSQL = str_replace("@KAISAI_YMD", "'" . str_replace("/", "", $postData['txtAcceptDate']) . "'", $strSQL);
        } else {
            $strSQL = str_replace("@KAISAI_YMD", "NULL", $strSQL);
        }

        if ($postData['txtPost'] != "") {
            $strSQL = str_replace("@TENPO_CD", "'" . $postData['txtPost'] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@TENPO_CD", "NULL", $strSQL);
        }

        if (!$postData['ddlDirector']) {
            $strSQL = str_replace("@SYAIN_NO", "NULL", $strSQL);
        } else {
            if ($postData['ddlDirector'] != "") {
                $strSQL = str_replace("@SYAIN_NO", "'" . $postData['ddlDirector'] . "'", $strSQL);
            } else {
                $strSQL = str_replace("@SYAIN_NO", "NULL", $strSQL);
            }
        }
        if ($postData['txtPlace'] != "") {
            $strSQL = str_replace("@PLACE", "'" . $postData['txtPlace'] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@PLACE", "NULL", $strSQL);
        }

        if ($postData['txtStartTime'] != "") {
            $strSQL = str_replace("@START_TIME", "'" . $postData['txtStartTime'] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@START_TIME", "NULL", $strSQL);
        }

        if ($postData['txtEndTime'] != "") {
            $strSQL = str_replace("@END_TIME", "'" . $postData['txtEndTime'] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@END_TIME", "NULL", $strSQL);
        }

        if ($postData['txtDemoCars'] != "") {
            $strSQL = str_replace("@DEMO_CARS", "'" . $postData['txtDemoCars'] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@DEMO_CARS", "NULL", $strSQL);
        }

        if ($postData['txtRaijoSu'] != "") {
            $strSQL = str_replace("@RAIJYO_SU", $postData['txtRaijoSu'], $strSQL);
        } else {
            $strSQL = str_replace("@RAIJYO_SU", "NULL", $strSQL);
        }

        if ($postData['txtEnqueteSu'] != "") {
            $strSQL = str_replace("@ENQUETE_SU", $postData['txtEnqueteSu'], $strSQL);
        } else {
            $strSQL = str_replace("@ENQUETE_SU", "NULL", $strSQL);
        }

        if ($postData['txtABHotSu'] != "") {
            $strSQL = str_replace("@ABHOT_SU", $postData['txtABHotSu'], $strSQL);
        } else {
            $strSQL = str_replace("@ABHOT_SU", "NULL", $strSQL);
        }

        if ($postData['txtMitumoriSu'] != "") {
            $strSQL = str_replace("@MITUMORI_SU", $postData['txtMitumoriSu'], $strSQL);
        } else {
            $strSQL = str_replace("@MITUMORI_SU", "NULL", $strSQL);
        }

        if ($postData['txtSeiyakuSu'] != "") {
            $strSQL = str_replace("@SEIYAKU_SU", $postData['txtSeiyakuSu'], $strSQL);
        } else {
            $strSQL = str_replace("@SEIYAKU_SU", "NULL", $strSQL);
        }

        $strSQL = str_replace("@UPD_DATE", 'SYSDATE', $strSQL);
        $strSQL = str_replace("@CREATE_DATE", 'SYSDATE', $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", "'" . $this->GS_LOGINUSER['strUserID'] . "'", $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", "'" . 'IntroduceConfirmEntr' . "'", $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", "'" . $this->GS_LOGINUSER['strClientNM'] . "'", $strSQL);

        return $strSQL;
    }

    //店舗名を表示する
    function FncGetBusyoMstValue($BusyoCD)
    {
        $strSQL = "";
        $strSQL .= " SELECT MST.BUSYO_CD " . "\r\n";
        $strSQL .= " ,      MST.BUSYO_RYKNM " . "\r\n";
        $strSQL .= " ,      NVL(BUS.HDT_TENPO_CD,' ') HDT_TENPO_CD" . "\r\n";
        $strSQL .= " ,      BUS.BUSYO_CD " . "\r\n";
        $strSQL .= " FROM HBUSYO MST " . "\r\n";
        $strSQL .= " INNER JOIN  (SELECT BUSYO_CD " . "\r\n";
        $strSQL .= "              ,      HDT_TENPO_CD " . "\r\n";
        $strSQL .= "              ,      (CASE WHEN HDT_TENPO_CD IS NOT NULL " . "\r\n";
        $strSQL .= "                     THEN HDT_TENPO_CD ELSE BUSYO_CD END) V_TENPO " . "\r\n";
        $strSQL .= "              FROM   HBUSYO) BUS " . "\r\n";
        $strSQL .= " ON     MST.BUSYO_CD = BUS.V_TENPO " . "\r\n";
        $strSQL .= " WHERE  BUS.BUSYO_CD = '@BUSYOCD' " . "\r\n";

        $strSQL = str_replace("@BUSYOCD", $BusyoCD, $strSQL);

        return parent::select($strSQL);
    }

    //店舗名を表示する
    function FncBusyoMstValueSQL()
    {
        $strSQL = "";
        $strSQL .= "SELECT BUS.BUSYO_CD" . "\r\n";
        $strSQL .= ",      MST.BUSYO_RYKNM" . "\r\n";
        $strSQL .= "FROM HBUSYO MST" . "\r\n";
        $strSQL .= "INNER JOIN  (SELECT BUSYO_CD" . "\r\n";
        $strSQL .= ",      (CASE WHEN HDT_TENPO_CD IS NOT NULL" . "\r\n";
        $strSQL .= " THEN HDT_TENPO_CD ELSE BUSYO_CD END) V_TENPO" . "\r\n";
        $strSQL .= "FROM HBUSYO) BUS" . "\r\n";
        $strSQL .= "ON     MST.BUSYO_CD = BUS.V_TENPO" . "\r\n";
        $strSQL .= "WHERE  MST.STD_TENPO_DISP_NO IS NOT NULL" . "\r\n";

        return $strSQL;
    }

    //対象期間のＳＱＬ文を取得
    public function getTerm($postData)
    {
        $strSql = $this->getTermSQL($postData);

        return parent::select($strSql);
    }

    //一覧データのＳＱＬ文を取得
    public function getIntroduction($postData, $flg)
    {
        $strSql = $this->getIntroductionSQL($postData, $flg);

        return parent::select($strSql);
    }

    //更新対象の紹介者確認ﾃﾞｰﾀのＳＱＬ文の取得
    public function getMaxNo()
    {
        $strSql = $this->getMaxNoSQL();

        return parent::select($strSql);
    }

    //更新対象の紹介者確認ﾃﾞｰﾀのＳＱＬ文の取得
    public function getReObject($postData)
    {
        $strSql = $this->getReObjectSQL($postData);

        return parent::select($strSql);
    }

    //部署に所属する社員のＳＱＬの取得
    public function getEmploye($postData)
    {
        $strSql = $this->getEmployeSQL($postData);

        return parent::select($strSql);
    }

    //紹介者確認データの削除のＳＱＬの取得
    public function getIntroDelete($postData)
    {
        $strSql = $this->getIntroDeleteSQL($postData);

        return parent::delete($strSql);
    }

    //更新対象の紹介者確認ﾃﾞｰﾀの更新処理のＳＱＬ文の取得
    public function getIntroUpdate($postData)
    {
        $strSql = $this->getIntroUpdateSQL($postData);

        return parent::update($strSql);
    }

    //ﾃﾞｰﾀの追加処理のＳＱＬ文の取得
    public function getIntroInsert($postData)
    {
        $strSql = $this->getIntroInsertSQL($postData);

        return parent::insert($strSql);
    }

    //店舗名を表示する
    public function FncBusyoMstValue()
    {
        $strSql = $this->FncBusyoMstValueSQL();

        return parent::select($strSql);
    }

}
