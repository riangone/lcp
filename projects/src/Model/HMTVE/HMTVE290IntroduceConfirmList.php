<?php
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;
class HMTVE290IntroduceConfirmList extends ClsComDb
{
    //'**********************************************************************
    //'処 理 名：sql
    //'関 数 名：getCreateSql
    //'引 数 　：strSql
    //'戻 り 値：なし
    //'処理説明：Excelまでデータを導入
    //'**********************************************************************
    public function getCreateSql($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT INTRO.JYURI_NO" . "\r\n";
        $strSQL .= ",      TO_CHAR(TO_DATE(INTRO.JYURI_YMD,'YYYY/MM/DD'),'YYYY/MM/DD') JYURI_DT" . "\r\n";
        $strSQL .= ",      INTRO.BUSYO_CD" . "\r\n";
        $strSQL .= ",      MST.BUSYO_RYKNM" . "\r\n";
        $strSQL .= ",      INTRO.SYAIN_NO" . "\r\n";
        $strSQL .= ",      SYA.SYAIN_NM" . "\r\n";
        $strSQL .= ",      INTRO.OKYAKU_NM" . "\r\n";
        $strSQL .= ",      INTRO.SYOUKAI_NM" . "\r\n";
        $strSQL .= ",      INTRO.SYOUDAN_FLG" . "\r\n";
        $strSQL .= ",      INTRO.MANEGER_CHK" . "\r\n";
        $strSQL .= ",      INTRO.TANTO_CHK" . "\r\n";
        $strSQL .= ",      INTRO.KAKUNIN_FLG" . "\r\n";
        $strSQL .= ",      INTRO.SYOUNIN_FLG" . "\r\n";
        $strSQL .= ",      INTRO.FUBI_RIYU" . "\r\n";
        $strSQL .= ",      INTRO.UPD_DATE" . "\r\n";
        $strSQL .= ",      INTRO.CREATE_DATE" . "\r\n";
        $strSQL .= ",      INTRO.UPD_PRG_ID" . "\r\n";
        $strSQL .= ",      INTRO.UPD_CLT_NM" . "\r\n";
        $strSQL .= "FROM   HDTINTRODUCEDATA INTRO" . "\r\n";
        $strSQL .= "LEFT JOIN HSYAINMST SYA" . "\r\n";
        $strSQL .= "ON     SYA.SYAIN_NO = INTRO.SYAIN_NO" . "\r\n";

        $strSQL .= "LEFT JOIN  (SELECT BUSYO_CD" . "\r\n";
        $strSQL .= "            ,      (CASE WHEN HDT_TENPO_CD IS NOT NULL" . "\r\n";
        $strSQL .= "                         THEN HDT_TENPO_CD ELSE BUSYO_CD END) V_TENPO" . "\r\n";
        $strSQL .= "            FROM HBUSYO) BUS" . "\r\n";
        $strSQL .= "ON          BUS.BUSYO_CD = INTRO.BUSYO_CD" . "\r\n";

        $strSQL .= "LEFT JOIN HBUSYO MST" . "\r\n";
        $strSQL .= "ON     MST.BUSYO_CD = BUS.V_TENPO" . "\r\n";
        $strSQL .= "AND    MST.STD_TENPO_DISP_NO IS NOT NULL" . "\r\n";

        $strSQL .= "WHERE    1=1" . "\r\n";

        //画面項目15.対象_両方以外にチェックが入っている場合
        if ($postData["rdoTwo"] == "false") {
            $strSQL .= "AND  NVL(KAKUNIN_FLG,'0') = '@KAKUNIN'" . "\r\n";
            //@KAKUNIN = 画面項目13.対象_未確認が選択されている場合は、"0"
            if ($postData["rdoNotConfirm"] == "true") {
                $strSQL = str_replace("@KAKUNIN", "0", $strSQL);
            } elseif ($postData["rdoConfirm"] == "true") {
                //@KAKUNIN =画面項目NO14.対象_確認済みが選択されている場合は、"１"
                $strSQL = str_replace("@KAKUNIN", "1", $strSQL);
            }
        }
        //画面項目NO5.部署コード≠""の場合
        if ($postData["txtPosition"] != "") {
            $strSQL .= "AND  INTRO.BUSYO_CD = '@BUSYOCD'" . "\r\n";
            //@BUSYOCD = 画面項目NO5.部署コード
            $strSQL = str_replace("@BUSYOCD", $postData["txtPosition"], $strSQL);
        }
        //画面項目NO7.日付_FROM_年≠""の場合
        if ($postData["ddlYear"] != "") {
            $strSQL .= "AND    TO_CHAR(TO_DATE(INTRO.JYURI_YMD,'YYYY/MM/DD'),'YYYY/MM/DD') >= '@FROMDT'" . "\r\n";
            //@FROMDT = 画面項目NO7.日付_FROM_年 & 画面項目NO8.日付_FROM_月 & 画面項目NO9.日付_FROM_日
            $strSQL = str_replace("@FROMDT", $postData["ddlYear"] . "/" . $postData["ddlMonth"] . "/" . $postData["ddlDay"], $strSQL);
        }
        //画面項目NO10.日付_TO_年≠""の場合
        if ($postData["ddlYear2"] != "") {
            $strSQL .= "AND    TO_CHAR(TO_DATE(INTRO.JYURI_YMD,'YYYY/MM/DD'),'YYYY/MM/DD') <= '@TODT'" . "\r\n";
            //@TODT = 画面項目NO10.日付_TO_年 & 画面項目NO11.日付_TO_月 & 画面項目NO12.日付_TO_日
            $strSQL = str_replace("@TODT", $postData["ddlYear2"] . "/" . $postData["ddlMonth2"] . "/" . $postData["ddlDay2"], $strSQL);
        }
        $strSQL .= "ORDER BY INTRO.BUSYO_CD, INTRO.SYAIN_NO, INTRO.JYURI_NO" . "\r\n";

        return parent::select($strSQL);
    }

    //'**********************************************************************
    //'処 理 名：フォーカス
    //'関 数 名：FoucsMove
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：フォーカス移動時
    //'**********************************************************************
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

    //'**********************************************************************
    //'処 理 名：sql句
    //'関 数 名：CreateIntroducerSql
    //'引 数 　：strSql
    //'戻 り 値：なし
    //'処理説明：生成sql句
    //'**********************************************************************
    public function CreateIntroducerSql($postData)
    {
        $strWhere = " WHERE ";

        $strSQL = "";
        $strSQL .= "SELECT INTRO.JYURI_NO" . "\r\n";
        $strSQL .= ",      TO_CHAR(TO_DATE(INTRO.JYURI_YMD,'YYYY/MM/DD'),'YYYY/MM/DD') JYURI_DT" . "\r\n";
        $strSQL .= ",      INTRO.BUSYO_CD" . "\r\n";
        $strSQL .= ",      MST.BUSYO_RYKNM" . "\r\n";
        $strSQL .= ",      INTRO.SYAIN_NO" . "\r\n";
        $strSQL .= ",      SYA.SYAIN_NM" . "\r\n";
        $strSQL .= ",      INTRO.OKYAKU_NM" . "\r\n";
        $strSQL .= ",      INTRO.SYOUKAI_NM" . "\r\n";
        $strSQL .= ",      INTRO.SYOUDAN_FLG" . "\r\n";
        $strSQL .= ",      NVL(INTRO.MANEGER_CHK,0) MANEGER_CHK" . "\r\n";
        //20211123 WANGYING ADD S
        //チェック用
        $strSQL .= ",      NVL(INTRO.MANEGER_CHK,0) MANEGER_CHK_CHK" . "\r\n";
        //20211123 WANGYING ADD E
        $strSQL .= ",      NVL(INTRO.TANTO_CHK,0) TANTO_CHK" . "\r\n";
        //20211123 WANGYING ADD S
        //チェック用
        $strSQL .= ",      NVL(INTRO.TANTO_CHK,0) TANTO_CHK_CHK" . "\r\n";
        //20211123 WANGYING ADD E
        $strSQL .= ",      INTRO.KAKUNIN_FLG" . "\r\n";
        $strSQL .= ",      NVL(INTRO.SYOUNIN_FLG,0) SYOUNIN_FLG " . "\r\n";
        //20211123 WANGYING ADD S
        //チェック用
        $strSQL .= ",      NVL(INTRO.SYOUNIN_FLG,0) SYOUNIN_FLG_CHK " . "\r\n";
        //20211123 WANGYING ADD E
        $strSQL .= ",      INTRO.FUBI_RIYU" . "\r\n";
        //20211123 WANGYING ADD S
        //チェック用
        $strSQL .= ",      INTRO.FUBI_RIYU FUBI_RIYU_CHK" . "\r\n";
        //20211123 WANGYING ADD E
        $strSQL .= ",      INTRO.UPD_DATE" . "\r\n";
        $strSQL .= ",      INTRO.CREATE_DATE" . "\r\n";
        $strSQL .= ",      INTRO.UPD_PRG_ID" . "\r\n";
        $strSQL .= ",      INTRO.UPD_CLT_NM" . "\r\n";
        $strSQL .= "FROM   HDTINTRODUCEDATA INTRO" . "\r\n";
        $strSQL .= "LEFT JOIN HSYAINMST SYA" . "\r\n";
        $strSQL .= "ON     SYA.SYAIN_NO = INTRO.SYAIN_NO" . "\r\n";

        $strSQL .= "LEFT JOIN  (SELECT BUSYO_CD" . "\r\n";
        $strSQL .= "            ,      (CASE WHEN HDT_TENPO_CD IS NOT NULL" . "\r\n";
        $strSQL .= "                         THEN HDT_TENPO_CD ELSE BUSYO_CD END) V_TENPO" . "\r\n";
        $strSQL .= "            FROM HBUSYO) BUS" . "\r\n";
        $strSQL .= "ON          BUS.BUSYO_CD = INTRO.BUSYO_CD" . "\r\n";

        $strSQL .= "LEFT JOIN HBUSYO MST" . "\r\n";
        $strSQL .= "ON     MST.BUSYO_CD = BUS.V_TENPO" . "\r\n";
        $strSQL .= "AND    MST.STD_TENPO_DISP_NO IS NOT NULL" . "\r\n";

        //対象_両方以外にチェックが入っている場合
        if ($postData["rdoConfirm"] == "true" || $postData["rdoNotConfirm"] == "true") {
            $strSQL .= $strWhere . "\r\n";
            $strSQL .= "  NVL(KAKUNIN_FLG,'0') = '@KAKUNIN'" . "\r\n";

            $strWhere = " AND ";
            //対象_未確認が選択されている場合
            if ($postData["rdoNotConfirm"] == "true") {
                $strSQL = str_replace("@KAKUNIN", "0", $strSQL);
            } else {
                //対象_確認済みが選択されている場合
                $strSQL = str_replace("@KAKUNIN", "1", $strSQL);
            }
        }
        //部署コード≠""の場合
        if ($postData["txtPosition"] != "") {
            $strSQL .= $strWhere . "\r\n";
            $strSQL .= "  INTRO.BUSYO_CD = '@BUSYOCD'" . "\r\n";
            $strWhere = " AND ";
            //@BUSYOCD = 部署コード
            $strSQL = str_replace("@BUSYOCD", $postData["txtPosition"], $strSQL);
        }
        //日付_FROM_年≠""の場合
        if ($postData["ddlYear"] != "") {
            $strSQL .= $strWhere . "\r\n";
            $strSQL .= "   TO_CHAR(TO_DATE(INTRO.JYURI_YMD,'YYYY/MM/DD'),'YYYY/MM/DD') >= '@FROMDT'" . "\r\n";
            $strWhere = " AND ";
            //@FROMDT = 日付_FROM_年 & 日付_FROM_月 & 日付_FROM_日
            $strSQL = str_replace("@FROMDT", $postData["ddlYear"] . "/" . $postData["ddlMonth"] . "/" . $postData["ddlDay"], $strSQL);
        }
        //日付_TO_年≠""の場合
        if ($postData["ddlYear2"] != "") {
            $strSQL .= $strWhere . "\r\n";
            $strSQL .= "   TO_CHAR(TO_DATE(INTRO.JYURI_YMD,'YYYY/MM/DD'),'YYYY/MM/DD') <= '@TODT'" . "\r\n";
            $strWhere = " AND ";
            //@TODT = 日付_TO_年 & 日付_TO_月 & 日付_TO_日
            $strSQL = str_replace("@TODT", $postData["ddlYear2"] . "/" . $postData["ddlMonth2"] . "/" . $postData["ddlDay2"], $strSQL);
        }
        $strSQL .= "ORDER BY INTRO.BUSYO_CD, INTRO.SYAIN_NO, INTRO.JYURI_NO";

        return parent::select($strSQL);
    }

    //'**********************************************************************
    //'処 理 名：店舗名
    //'関 数 名：ExpressShopName
    //'引 数 　：strSql
    //'戻 り 値：なし
    //'処理説明：店舗名を表示する
    //'2009/04/02 UPD clsdb追加
    //'**********************************************************************
    public function ExpressShopName($txtPosition)
    {
        $strSQL = "";
        $strSQL .= "SELECT MST.BUSYO_CD" . "\r\n";
        $strSQL .= ",      MST.BUSYO_RYKNM" . "\r\n";
        $strSQL .= "FROM HBUSYO MST" . "\r\n";
        $strSQL .= "INNER JOIN  (SELECT BUSYO_CD" . "\r\n";
        $strSQL .= ",      (CASE WHEN HDT_TENPO_CD IS NOT NULL" . "\r\n";
        $strSQL .= "THEN HDT_TENPO_CD ELSE BUSYO_CD END) V_TENPO" . "\r\n";
        $strSQL .= "FROM HBUSYO) BUS" . "\r\n";
        $strSQL .= "ON     MST.BUSYO_CD = BUS.V_TENPO" . "\r\n";
        $strSQL .= "WHERE  MST.STD_TENPO_DISP_NO IS NOT NULL" . "\r\n";
        $strSQL .= "AND    BUS.BUSYO_CD = '@BUSYOCD'" . "\r\n";

        //@BUSYOCD = 部署コード
        $strSQL = str_replace("@BUSYOCD", $txtPosition, $strSQL);

        return parent::select($strSQL);
    }

    //'**********************************************************************
    //'処 理 名：対象期間
    //'関 数 名：getObjectTerm
    //'引 数 　：strSql
    //'戻 り 値：なし
    //'処理説明：対象期間を取得する
    //'2009/04/02 UPD clsdb追加
    //'**********************************************************************
    public function getObjectTerm()
    {
        $strSQL = "";
        $strSQL .= "SELECT MIN(JYURI_YMD) HI_MIN" . "\r\n";
        $strSQL .= ",      MAX(JYURI_YMD) HI_MAX" . "\r\n";
        $strSQL .= "FROM   HDTINTRODUCEDATA" . "\r\n";

        return parent::select($strSQL);
    }

    //'**********************************************************************
    //'処 理 名：確認済みへボタンクリックのイベント
    //'関 数 名：btnExpression_Click
    //'引 数 １：(I)sender イベントソース
    //'引 数 ２：(I)e      イベントパラメータ
    //'戻 り 値：なし
    //'処理説明：入力データの登録
    //'**********************************************************************
    public function btnConfirm_Click($value)
    {
        $strSQL = "";
        $strSQL .= " UPDATE   HDTINTRODUCEDATA " . "\r\n";
        $strSQL .= " SET  KAKUNIN_FLG = '1' " . "\r\n";
        $strSQL .= " ,    UPD_DATE = SYSDATE  " . "\r\n";
        $strSQL .= " ,    UPD_SYA_CD = '@UPD_SYA_CD' " . "\r\n";
        $strSQL .= " ,    UPD_PRG_ID = '@UPD_PRG_ID' " . "\r\n";
        $strSQL .= " ,    UPD_CLT_NM = '@UPD_CLT_NM' " . "\r\n";
        $strSQL .= " WHERE  JYURI_NO = '@JYURI_NO' " . "\r\n";

        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", "IntroduceConfirmEnt", $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        //画面項目NO18.紹介者ﾃｰﾌﾞﾙ_受理№(RowCnt ※４－５。内部仕様　登録ボタンクリック参照)
        $strSQL = str_replace("@JYURI_NO", $value['JYURI_NO'], $strSQL);

        return parent::update($strSQL);
    }

    //'**********************************************************************
    //'処 理 名：登録ボタンクリックのイベント
    //'関 数 名：btnLogin_Click
    //'引 数 １：(I)sender イベントソース
    //'引 数 ２：(I)e      イベントパラメータ
    //'戻 り 値：なし
    //'処理説明：入力データの登録
    //'**********************************************************************
    public function btnLogin_Click1($value)
    {
        $strSQL = "";
        $strSQL .= "UPDATE   HDTINTRODUCEDATA" . "\r\n";
        $strSQL .= "SET  SYOUNIN_FLG = @FLG" . "\r\n";
        $strSQL .= ",    UPD_DATE = SYSDATE" . "\r\n";
        $strSQL .= ",    UPD_SYA_CD = '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",    UPD_PRG_ID = '@UPD_PRG_ID'" . "\r\n";
        $strSQL .= ",    UPD_CLT_NM = '@UPD_CLT_NM'" . "\r\n";
        $strSQL .= "WHERE  JYURI_NO = '@JYURI_NO'" . "\r\n";

        //承認が選択されている場合は"1"
        if ($value["SYOUNIN_FLG"] == "") {
            $strSQL = str_replace("@FLG", "null", $strSQL);
        } else {
            $strSQL = str_replace("@FLG", $value["SYOUNIN_FLG"], $strSQL);
        }

        //画面項目NO18.紹介者ﾃｰﾌﾞﾙ_受理№(RowCnt ※４－５。内部仕様　登録ボタンクリック参照)
        $strSQL = str_replace("@JYURI_NO", $value['JYURI_NO'], $strSQL);
        //@UPD_SYA_CD=Session("LoginID")
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        //@UPD_PRG_ID="IntroduceConfirmEnt"
        $strSQL = str_replace("@UPD_PRG_ID", "IntroduceConfirmEnt", $strSQL);
        //@UPD_CLT_NM=Session("MachineNM")
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return parent::update($strSQL);
    }

    //'**********************************************************************
    //'処 理 名：登録ボタンクリックのイベント
    //'関 数 名：btnLogin_Click
    //'引 数 １：(I)sender イベントソース
    //'引 数 ２：(I)e      イベントパラメータ
    //'戻 り 値：なし
    //'処理説明：入力データの登録
    //'**********************************************************************
    public function btnLogin_Click2($value)
    {
        $strSQL = "";
        $strSQL .= "UPDATE   HDTINTRODUCEDATA" . "\r\n";
        $strSQL .= "SET  MANEGER_CHK = @MANEGER_CHK" . "\r\n";
        $strSQL .= ",    TANTO_CHK = @TANTO_CHK " . "\r\n";
        $strSQL .= ",    FUBI_RIYU = @FUBI_RIYU" . "\r\n";
        $strSQL .= ",    UPD_DATE = SYSDATE" . "\r\n";
        $strSQL .= ",    UPD_SYA_CD = '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",    UPD_PRG_ID = '@UPD_PRG_ID'" . "\r\n";
        $strSQL .= ",    UPD_CLT_NM = '@UPD_CLT_NM'" . "\r\n";
        $strSQL .= " WHERE  JYURI_NO = '@JYURI_NO' ";
        //画面項目NO18.紹介者ﾃｰﾌﾞﾙ_受理№(RowCnt ※４－５。内部仕様　登録ボタンクリック参照)
        $strSQL = str_replace("@JYURI_NO", $value['JYURI_NO'], $strSQL);
        if ($value['MANEGER_CHK'] == "") {
            //紹介者ﾃｰﾌﾞﾙ_店長チェックが何もない場合は場合はNULL
            $strSQL = str_replace("@MANEGER_CHK", "NULL", $strSQL);
        } else {
            $strSQL = str_replace("@MANEGER_CHK", $value['MANEGER_CHK'], $strSQL);
        }
        if ($value['TANTO_CHK'] == "") {
            //紹介者ﾃｰﾌﾞﾙ_担当者チェックが何もない場合は場合はNULL
            $strSQL = str_replace("@TANTO_CHK", "NULL", $strSQL);
        } else {
            $strSQL = str_replace("@TANTO_CHK", $value['TANTO_CHK'], $strSQL);
        }
        if ($value['FUBI_RIYU'] == "") {
            //不備理由に何も入力されていない場合はNULL
            $strSQL = str_replace("@FUBI_RIYU", "null", $strSQL);
        } else {
            $strSQL = str_replace("@FUBI_RIYU", "'" . $value['FUBI_RIYU'] . "'", $strSQL);
        }
        $strSQL = str_replace("@UPD_PRG_ID", "IntroduceConfirmEnt", $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return parent::update($strSQL);
    }

    public function btnLogin_Click_Other($postData)
    {
        $strWhere = " WHERE ";

        $strSQL = "";
        $strSQL .= "UPDATE   HDTINTRODUCEDATA INTRO" . "\r\n";
        $strSQL .= "SET  UPD_DATE = SYSDATE" . "\r\n";
        $strSQL .= ",    UPD_SYA_CD = '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",    UPD_PRG_ID = '@UPD_PRG_ID'" . "\r\n";
        $strSQL .= ",    UPD_CLT_NM = '@UPD_CLT_NM'" . "\r\n";

        //対象_両方以外にチェックが入っている場合
        if ($postData["rdoConfirm"] == "true" || $postData["rdoNotConfirm"] == "true") {
            $strSQL .= $strWhere . "\r\n";
            $strSQL .= "  NVL(KAKUNIN_FLG,'0') = '@KAKUNIN'" . "\r\n";

            $strWhere = " AND ";
            //対象_未確認が選択されている場合
            if ($postData["rdoNotConfirm"] == "true") {
                $strSQL = str_replace("@KAKUNIN", "0", $strSQL);
            } else {
                //対象_確認済みが選択されている場合
                $strSQL = str_replace("@KAKUNIN", "1", $strSQL);
            }
        }
        //部署コード≠""の場合
        if ($postData["txtPosition"] != "") {
            $strSQL .= $strWhere . "\r\n";
            $strSQL .= "  INTRO.BUSYO_CD = '@BUSYOCD'" . "\r\n";
            $strWhere = " AND ";
            //@BUSYOCD = 部署コード
            $strSQL = str_replace("@BUSYOCD", $postData["txtPosition"], $strSQL);
        }
        //日付_FROM_年≠""の場合
        if ($postData["ddlYear"] != "") {
            $strSQL .= $strWhere . "\r\n";
            $strSQL .= "   TO_CHAR(TO_DATE(INTRO.JYURI_YMD,'YYYY/MM/DD'),'YYYY/MM/DD') >= '@FROMDT'" . "\r\n";
            //@FROMDT = 日付_FROM_年 & 日付_FROM_月 & 日付_FROM_日
            $strSQL = str_replace("@FROMDT", $postData["ddlYear"] . "/" . $postData["ddlMonth"] . "/" . $postData["ddlDay"], $strSQL);
        }
        //日付_TO_年≠""の場合
        if ($postData["ddlYear2"] != "") {
            $strSQL .= $strWhere . "\r\n";
            $strSQL .= "   TO_CHAR(TO_DATE(INTRO.JYURI_YMD,'YYYY/MM/DD'),'YYYY/MM/DD') <= '@TODT'" . "\r\n";
            $strWhere = " AND ";
            //@TODT = 日付_TO_年 & 日付_TO_月 & 日付_TO_日
            $strSQL = str_replace("@TODT", $postData["ddlYear2"] . "/" . $postData["ddlMonth2"] . "/" . $postData["ddlDay2"], $strSQL);
        }
        $strSQL = str_replace("@UPD_PRG_ID", "IntroduceConfirmEnt", $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return parent::update($strSQL);
    }

}
