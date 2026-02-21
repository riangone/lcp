<?php
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;
class HMTVE280IntroduceConfirmEntry extends ClsComDb
{
    //'**********************************************************************
    //'処 理 名：対象期間のＳＱＬ文を取得
    //'関 数 名：getTermSQL
    //'引 数   ：なし
    //'戻 り 値：strSQL　　　String
    //'処理説明：対象期間のＳＱＬ文を取得
    //'**********************************************************************
    public function getTermSQL()
    {
        $strSQL = "";
        $strSQL .= "SELECT MIN(JYURI_YMD) HI_MIN " . "\r\n";
        $strSQL .= ",      MAX(JYURI_YMD) HI_MAX " . "\r\n";
        $strSQL .= "FROM   HDTINTRODUCEDATA " . "\r\n";

        return parent::select($strSQL);
    }

    //'**********************************************************************
    //'処 理 名：紹介者確認データのＳＱＬ文を取得
    //'関 数 名：getIntroductionSQL
    //'引 数   ：なし
    //'戻 り 値：strSQL　　　String
    //'処理説明：紹介者確認データのＳＱＬ文を取得する
    //'**********************************************************************
    public function getIntroductionSQL($postData)
    {
        $strSQL = "";
        $strSQL .= "  " . "\r\n";
        $strSQL .= "        SELECT INTRO.JYURI_NO " . "\r\n";
        $strSQL .= " ,      TO_CHAR(TO_DATE(INTRO.JYURI_YMD,'YYYY/MM/DD'),'YYYY/MM/DD') JYURI_DT " . "\r\n";
        $strSQL .= " ,      INTRO.BUSYO_CD " . "\r\n";
        $strSQL .= " ,      MST.BUSYO_RYKNM " . "\r\n";
        $strSQL .= " ,      INTRO.SYAIN_NO " . "\r\n";
        $strSQL .= " ,      SYA.SYAIN_NM " . "\r\n";
        $strSQL .= " ,      INTRO.OKYAKU_NM " . "\r\n";
        $strSQL .= " ,      INTRO.SYOUKAI_NM " . "\r\n";
        $strSQL .= " ,      INTRO.SYOUDAN_FLG " . "\r\n";
        $strSQL .= " ,      INTRO.MANEGER_CHK " . "\r\n";
        $strSQL .= " ,      INTRO.TANTO_CHK " . "\r\n";
        $strSQL .= " ,      INTRO.KAKUNIN_FLG " . "\r\n";
        $strSQL .= " ,      INTRO.FUBI_RIYU " . "\r\n";
        $strSQL .= " ,      INTRO.UPD_DATE " . "\r\n";
        $strSQL .= " ,      INTRO.CREATE_DATE " . "\r\n";
        $strSQL .= " ,      INTRO.UPD_PRG_ID " . "\r\n";
        $strSQL .= " ,      INTRO.UPD_CLT_NM " . "\r\n";
        $strSQL .= " FROM   HDTINTRODUCEDATA INTRO " . "\r\n";
        $strSQL .= " LEFT JOIN HSYAINMST SYA " . "\r\n";
        $strSQL .= " ON     SYA.SYAIN_NO = INTRO.SYAIN_NO " . "\r\n";

        $strSQL .= "LEFT JOIN  (SELECT BUSYO_CD" . "\r\n";
        $strSQL .= "            ,      (CASE WHEN HDT_TENPO_CD IS NOT NULL" . "\r\n";
        $strSQL .= "                         THEN HDT_TENPO_CD ELSE BUSYO_CD END) V_TENPO" . "\r\n";
        $strSQL .= "            FROM HBUSYO) BUS" . "\r\n";
        $strSQL .= "ON          BUS.BUSYO_CD = INTRO.BUSYO_CD" . "\r\n";

        $strSQL .= "LEFT JOIN HBUSYO MST" . "\r\n";
        $strSQL .= "ON     MST.BUSYO_CD = BUS.V_TENPO" . "\r\n";
        $strSQL .= "AND    MST.STD_TENPO_DISP_NO IS NOT NULL" . "\r\n";

        $strSQL .= " WHERE 1=1 " . "\r\n";
        if ($postData["txtJyuriNo"] != "") {
            $strSQL .= " AND  INTRO.JYURI_NO = '" . $postData["txtJyuriNo"] . "'" . "\r\n";
        }
        if ($postData["rdoKaku"] == "true") {
            $strSQL .= " AND  NVL(INTRO.KAKUNIN_FLG,0) = '1'   " . "\r\n";
        } else
            if ($postData["rdoMikaku"] == "true") {
                $strSQL .= " AND  NVL(INTRO.KAKUNIN_FLG,0) = '0'   " . "\r\n";
            }

        if ($postData["flg"] == "") {
            if ($postData["txtExhibitTitle1"] != "") {
                $strSQL .= " AND  INTRO.BUSYO_CD = '@BUSYOCD'  AND " . "\r\n";
                $strSQL = str_replace("@BUSYOCD", $postData["txtExhibitTitle1"], $strSQL);
            } else {
                $strSQL .= " AND  " . "\r\n";
            }
            $strSQL .= "   TO_CHAR(TO_DATE(INTRO.JYURI_YMD,'YYYY/MM/DD'),'YYYY/MM/DD') >= '@FROMDT' " . "\r\n";
            $strSQL = str_replace("@FROMDT", $postData["ddlYear"] . "/" . $postData["ddlMonth"] . "/" . $postData["ddlDay"], $strSQL);

            $strSQL .= "AND    TO_CHAR(TO_DATE(INTRO.JYURI_YMD,'YYYY/MM/DD'),'YYYY/MM/DD') <= '@TODT' " . "\r\n";
            $strSQL = str_replace("@TODT", $postData["ddlYear2"] . "/" . $postData["ddlMonth2"] . "/" . $postData["ddlDay2"], $strSQL);
        } else {
            if ($postData["txtExhibitTitle1"] != "") {
                $strSQL .= " AND  INTRO.BUSYO_CD = '@BUSYOCD'  AND " . "\r\n";
                $strSQL = str_replace("@BUSYOCD", $postData["txtExhibitTitle1"], $strSQL);
            }
        }
        $strSQL .= " ORDER BY STD_TENPO_DISP_NO,JYURI_NO";

        return parent::select($strSQL);
    }

    //'**********************************************************************
    //'処 理 名：更新対象の紹介者確認ﾃﾞｰﾀのＳＱＬ文の取得
    //'関 数 名：getReObjectSQL
    //'引 数   ：なし
    //'戻 り 値：strSQL　　　String
    //'処理説明：更新対象の紹介者確認ﾃﾞｰﾀのＳＱＬ文を取得する
    //'**********************************************************************
    public function getReObjectSQL($postData)
    {
        $strSQL = "";
        $strSQL .= " SELECT JYURI_NO  " . "\r\n";
        $strSQL .= " FROM            HDTINTRODUCEDATA INTRO  " . "\r\n";
        $strSQL .= "WHERE  INTRO.JYURI_NO = '@JYURINO' " . "\r\n";

        $strSQL = str_replace("@JYURINO", $postData["txtAcceptNo"], $strSQL);

        return parent::select($strSQL);
    }

    //'**********************************************************************
    //'処 理 名：部署名のＳＱＬ文の取得
    //'関 数 名：getPostNameSQL
    //'引 数   ：なし
    //'戻 り 値：strSQL　　　String
    //'処理説明：部署名のＳＱＬ文の取得する
    //'**********************************************************************
    // public function getPostNameSQL($postData)
    // {
    // $strSQL = "";
    // $strSQL .= "    " . "\r\n";
    // $strSQL .= "  Select  MST.BUSYO_CD  " . "\r\n";
    // $strSQL .= "  ,      MST.BUSYO_RYKNM  " . "\r\n";
    // $strSQL .= "  FROM HBUSYO MST  " . "\r\n";
    // $strSQL .= "  INNER JOIN  (SELECT BUSYO_CD  " . "\r\n";
    // $strSQL .= "  ,      (CASE WHEN HDT_TENPO_CD IS NOT NULL  " . "\r\n";
    // $strSQL .= "  THEN HDT_TENPO_CD ELSE BUSYO_CD END) V_TENPO  " . "\r\n";
    // $strSQL .= "  FROM HBUSYO) BUS  " . "\r\n";
    // $strSQL .= "  ON     MST.BUSYO_CD = BUS.V_TENPO  " . "\r\n";
    //
    // $strSQL .= "  WHERE(MST.STD_TENPO_DISP_NO Is Not NULL)  " . "\r\n";
    //
    // $strSQL .= "  AND    BUS.BUSYO_CD = '@BUSYOCD'  " . "\r\n";
    //
    // if (isset($postData["flg"]) && $postData['flg'] == "Exhibit")
    // {
    // $strSQL = str_replace("@BUSYOCD", $postData["txtExhibitTitle1"], $strSQL);
    // }
    // else
    // {
    // $strSQL = str_replace("@BUSYOCD", $postData["txtPost"], $strSQL);
    // }
    //
    // return parent::select($strSQL);
    // }

    //'**********************************************************************
    //'処 理 名：部署に所属する社員のＳＱＬの取得
    //'関 数 名：getEmployeSQL
    //'引 数   ：なし
    //'戻 り 値：strSQL　　　String
    //'処理説明：部署に所属する社員のＳＱＬを取得する
    //'**********************************************************************
    public function getEmployeSQL($postData)
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

        $strSQL = str_replace("@BUSYOCD", $postData["BUSYOCD"], $strSQL);

        return parent::select($strSQL);
    }

    //'**********************************************************************
    //'処 理 名：紹介者確認データの削除のＳＱＬの取得
    //'関 数 名：getReObjectSQL
    //'引 数   ：なし
    //'戻 り 値：strSQL　　　String
    //'処理説明：紹介者確認データを削除のＳＱＬの取得する
    //'**********************************************************************
    public function getIntroDeleteSQL($postData)
    {
        $strSQL = "";
        $strSQL .= " DELETE FROM HDTINTRODUCEDATA " . "\r\n";
        $strSQL .= " WHERE  JYURI_NO = '@JYURINO' " . "\r\n";

        $strSQL = str_replace("@JYURINO", $postData["txtAcceptNo"], $strSQL);

        return parent::delete($strSQL);
    }

    //'**********************************************************************
    //'処 理 名：更新対象の紹介者確認ﾃﾞｰﾀの更新処理のＳＱＬ文の取得
    //'関 数 名：getReObjectSQL
    //'引 数   ：なし
    //'戻 り 値：strSQL　　　String
    //'処理説明：更新対象の紹介者確認ﾃﾞｰﾀの更新処理のＳＱＬ文を取得する
    //'**********************************************************************
    public function getIntroUpdateSQL($postData)
    {
        $strSQL = "";
        $strSQL .= " UPDATE HDTINTRODUCEDATA SET " . "\r\n";
        $strSQL .= " JYURI_YMD = @JYURI_YMD,  " . "\r\n";
        $strSQL .= " BUSYO_CD = @BUSYO_CD, " . "\r\n";
        $strSQL .= " SYAIN_NO = @SYAIN_NO, " . "\r\n";
        $strSQL .= " OKYAKU_NM = @OKYAKU_NM, " . "\r\n";
        $strSQL .= " SYOUKAI_NM = @SYOUKAI_NM, " . "\r\n";
        $strSQL .= " SYOUDAN_FLG = @SYOUDAN_FLG, " . "\r\n";
        $strSQL .= " UPD_DATE = @UPD_DATE, " . "\r\n";
        $strSQL .= " UPD_SYA_CD = @UPD_SYA_CD, " . "\r\n";
        $strSQL .= " UPD_PRG_ID = @UPD_PRG_ID, " . "\r\n";
        $strSQL .= " UPD_CLT_NM = @UPD_CLT_NM" . "\r\n";
        $strSQL .= " WHERE JYURI_NO = @JYURI_NO" . "\r\n";

        if ($postData['txtAcceptDate'] != "") {
            $strSQL = str_replace("@JYURI_YMD", "'" . $postData["txtAcceptDate"] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@JYURI_YMD", "NULL", $strSQL);
        }

        if ($postData['txtPost'] != "") {
            $strSQL = str_replace("@BUSYO_CD", "'" . $postData["txtPost"] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@BUSYO_CD", "NULL", $strSQL);
        }

        if ($postData['ddlDirector'] != "") {
            $strSQL = str_replace("@SYAIN_NO", "'" . $postData["ddlDirector"] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@SYAIN_NO", "NULL", $strSQL);
        }

        if ($postData['txtClient'] != "") {
            $strSQL = str_replace("@OKYAKU_NM", "'" . $postData["txtClient"] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@OKYAKU_NM", "NULL", $strSQL);
        }

        if ($postData['txtIntroPeople'] != "") {
            $strSQL = str_replace("@SYOUKAI_NM", "'" . $postData["txtIntroPeople"] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@SYOUKAI_NM", "NULL", $strSQL);
        }
        $strSQL = str_replace("@SYOUDAN_FLG", $postData['chkJudge'], $strSQL);
        $strSQL = str_replace("@UPD_DATE", "SYSDATE", $strSQL);
        $strSQL = str_replace("@CREATE_DATE", "SYSDATE", $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", "'" . $this->GS_LOGINUSER['strUserID'] . "'", $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", "'IntroduceConfirmEntr'", $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", "'" . $this->GS_LOGINUSER['strClientNM'] . "'", $strSQL);
        if ($postData['txtAcceptNo'] != "") {
            $strSQL = str_replace("@JYURI_NO", "'" . $postData["txtAcceptNo"] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@JYURI_NO", "NULL", $strSQL);
        }

        return parent::update($strSQL);
    }

    //'**********************************************************************
    //'処 理 名：更新対象の紹介者確認ﾃﾞｰﾀの追加処理のＳＱＬ文の取得
    //'関 数 名：getIntroInsertSQL
    //'引 数   ：なし
    //'戻 り 値：strSQL　　　String
    //'処理説明：更新対象の紹介者確認ﾃﾞｰﾀの追加処理のＳＱＬ文を取得する
    //'**********************************************************************
    public function getIntroInsertSQL($postData)
    {
        $strSQL = "";
        $strSQL .= "insert into HDTINTRODUCEDATA" . "\r\n";
        $strSQL .= "( JYURI_NO , JYURI_YMD , BUSYO_CD  " . "\r\n";
        $strSQL .= ", SYAIN_NO , OKYAKU_NM , SYOUKAI_NM " . "\r\n";
        $strSQL .= ", SYOUDAN_FLG , MANEGER_CHK , TANTO_CHK " . "\r\n";
        $strSQL .= ", KAKUNIN_FLG , SYOUNIN_FLG , FUBI_RIYU" . "\r\n";
        $strSQL .= ", UPD_DATE , CREATE_DATE , UPD_SYA_CD " . "\r\n";
        $strSQL .= ", UPD_PRG_ID , UPD_CLT_NM" . "\r\n";
        $strSQL .= " ) values  " . "\r\n";
        $strSQL .= "(@JYURI_NO , @JYURI_YMD , @BUSYO_CD," . "\r\n";
        $strSQL .= "@SYAIN_NO , @OKYAKU_NM , @SYOUKAI_NM," . "\r\n";
        $strSQL .= "@SYOUDAN_FLG , @MANEGER_CHK , @TANTO_CHK," . "\r\n";
        $strSQL .= "@KAKUNIN_FLG , @SYOUNIN_FLG , @FUBI_RIYU," . "\r\n";
        $strSQL .= "@UPD_DATE , @CREATE_DATE , @UPD_SYA_CD," . "\r\n";
        $strSQL .= "@UPD_PRG_ID , @UPD_CLT_NM ) " . "\r\n";

        if ($postData['txtAcceptNo'] != "") {
            $strSQL = str_replace("@JYURI_NO", "'" . $postData["txtAcceptNo"] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@JYURI_NO", "NULL", $strSQL);
        }

        if ($postData['txtAcceptDate'] != "") {
            $strSQL = str_replace("@JYURI_YMD", "'" . $postData["txtAcceptDate"] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@JYURI_YMD", "NULL", $strSQL);
        }

        if ($postData['txtPost'] != "") {
            $strSQL = str_replace("@BUSYO_CD", "'" . $postData["txtPost"] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@BUSYO_CD", "NULL", $strSQL);
        }

        if ($postData['ddlDirector'] != "") {
            $strSQL = str_replace("@SYAIN_NO", "'" . $postData["ddlDirector"] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@SYAIN_NO", "NULL", $strSQL);
        }

        if ($postData['txtClient'] != "") {
            $strSQL = str_replace("@OKYAKU_NM", "'" . $postData["txtClient"] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@OKYAKU_NM", "NULL", $strSQL);
        }

        if ($postData['txtIntroPeople'] != "") {
            $strSQL = str_replace("@SYOUKAI_NM", "'" . $postData["txtIntroPeople"] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@SYOUKAI_NM", "NULL", $strSQL);
        }
        $strSQL = str_replace("@SYOUDAN_FLG", $postData['chkJudge'], $strSQL);
        $strSQL = str_replace("@MANEGER_CHK", "NULL", $strSQL);
        $strSQL = str_replace("@TANTO_CHK", "NULL", $strSQL);
        $strSQL = str_replace("@KAKUNIN_FLG", "NULL", $strSQL);
        $strSQL = str_replace("@SYOUNIN_FLG", "NULL", $strSQL);
        $strSQL = str_replace("@FUBI_RIYU", "NULL", $strSQL);
        $strSQL = str_replace("@UPD_DATE", "SYSDATE", $strSQL);
        $strSQL = str_replace("@CREATE_DATE", "SYSDATE", $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", "'" . $this->GS_LOGINUSER['strUserID'] . "'", $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", "'IntroduceConfirmEntr'", $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", "'" . $this->GS_LOGINUSER['strClientNM'] . "'", $strSQL);

        return parent::insert($strSQL);
    }

    //Ⅰー３．店舗名を表示する
    public function FoucsMove()
    {
        $strSQL = "";
        $strSQL .= "SELECT BUS.BUSYO_CD" . "\r\n";
        $strSQL .= ",      MST.BUSYO_RYKNM" . "\r\n";
        $strSQL .= "FROM HBUSYO MST" . "\r\n";
        $strSQL .= "INNER JOIN  (SELECT BUSYO_CD" . "\r\n";
        $strSQL .= " ,      (CASE WHEN HDT_TENPO_CD IS NOT NULL" . "\r\n";
        $strSQL .= " THEN HDT_TENPO_CD ELSE BUSYO_CD END) V_TENPO" . "\r\n";
        $strSQL .= "FROM HBUSYO) BUS" . "\r\n";
        $strSQL .= "ON     MST.BUSYO_CD = BUS.V_TENPO" . "\r\n";

        $strSQL .= "WHERE  MST.STD_TENPO_DISP_NO IS NOT NULL" . "\r\n";

        return parent::select($strSQL);
    }

}

