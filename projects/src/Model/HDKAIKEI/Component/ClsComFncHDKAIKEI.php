<?php
/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                             内容                                    担当
 * YYYYMMDD           #ID                                     XXXXXX                                 FCSDL
 * 20240227           20240213_機能改善要望対応 NO6    「科目マスタの使用フラグ、使用フラグ名は撤廃」        YIN
 * -------------------------------------------------------------------------------------------------------
 */
// 共通クラスの読込み
namespace App\Model\HDKAIKEI\Component;

// 共通クラスの読込み
use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：ClsComFnc
// * 処理説明：共通関数
//*************************************

class ClsComFncHDKAIKEI extends ClsComDb
{
    // 解放が必要な変数をメンバーに設定
    // protected $conn_orl = "";
    // protected $Sel_Array = "";

    const GSYSTEM_KB = 0;

    //就業日数平均
    // const GMONTHAVGDAYS = "21.7";
    //就業時間平均
    // const GMONTHAVGTIMES = "167.92";

    function selectSql()
    {
        return "SELECT TO_CHAR (SYSDATE,'YYYY-MM-DD HH24:MI:SS') SYS_DATE FROM DUAL";
    }

    public function FncGetKamokuMstValueSQL($strCode, $strKomoku, $strMstFormat)
    {
        //** ＳＱＬ作成
        $strSql = "";
        if ($strKomoku == "999999") {
            //科目名で検索(項目は科目でグルーピングした最初の項目)
            $strSql .= "SELECT KAMOK_NM" . "\r\n";
            $strSql .= "FROM  M_KAMOKU A" . "\r\n";
            $strSql .= "WHERE NVL(KOMOK_CD,'00') = (SELECT MIN(NVL(KOMOK_CD,'00')) FROM M_KAMOKU B WHERE A.KAMOK_CD = B.KAMOK_CD GROUP BY B.KAMOK_CD)" . "\r\n";
            $strSql .= "AND   A.KAMOK_CD = '@KAMOKUCD'" . "\r\n";
        } else {
            //科目・項目名で検索
            $strSql .= "SELECT (KAMOK_NM || ' ' || KOMOK_NM) KAMOK_NM" . "\r\n";
            $strSql .= "FROM   M_KAMOKU" . "\r\n";
            $strSql .= "WHERE  KAMOK_CD = '@KAMOKUCD'" . "\r\n";

            if ($strMstFormat == "") {
                $strSql .= "AND  NVL(TRIM(KOMOK_CD),'00') = NVL(TRIM('@KOMOKU'),'00')" . "\r\n";
            } else {
                $strSql .= "AND  (CASE WHEN LENGTH(TRIM(KOMOK_CD)) > 2 THEN TRIM(KOMOK_CD) ELSE NVL(LPAD(TRIM(KOMOK_CD),2,'@MSTFORMAT'),'00') END) = NVL(TRIM('@KOMOKU'),'00')" . "\r\n";
            }
        }

        $strSql = str_replace("@KAMOKUCD", $strCode, $strSql);
        $strSql = str_replace("@KOMOKU", $strKomoku, $strSql);
        $strSql = str_replace("@MSTFORMAT", $strMstFormat, $strSql);
        return $strSql;
    }

    public function FncGetBusyoMstValueSQL()
    {
        $strSql = "";
        //** ＳＱＬ作成
        $strSql .= "SELECT BUSYO_NM" . "\r\n";
        $strSql .= ",      BUSYO_CD" . "\r\n";
        $strSql .= "FROM   HDK_MST_BUMON" . "\r\n";
        $strSql .= "WHERE  USE_FLG = '1' " . "\r\n";

        return $strSql;
    }

    public function FncGetCreatBusyoMstValueSQL()
    {
        $strSql = "";
        //** ＳＱＬ作成
        $strSql .= "SELECT BUSYO_NM" . "\r\n";
        $strSql .= ",      BUSYO_CD" . "\r\n";
        $strSql .= "FROM   HBUSYO" . "\r\n";

        return $strSql;
    }

    public function FncGetSyainMstValueSQL()
    {
        $strSql = "";
        //** ＳＱＬ作成
        $strSql .= "SELECT SYAIN_NM" . "\r\n";
        $strSql .= ",      SYAIN_NO" . "\r\n";
        $strSql .= "FROM   HSYAINMST" . "\r\n";

        return $strSql;
    }

    //*************************************
    // * 公開メソッド
    //*************************************

    public function select($strsql = NULL)
    {

        return parent::select($this->selectSql());

    }

    //20211115 WANGYING DEL S
    // public function FncGetKamokuMstValue($strCode, $strKomoku, $strMstFormat)
    // {
    // return parent::select($this -> FncGetKamokuMstValueSQL($strCode, $strKomoku, $strMstFormat));
    // }
    //20211115 WANGYING DEL E

    public function FncGetBusyoMstValue()
    {
        return parent::select($this->FncGetBusyoMstValueSQL());
    }

    public function FncGetCreatBusyoMstValue()
    {
        return parent::select($this->FncGetCreatBusyoMstValueSQL());
    }

    public function FncGetSyainMstValue()
    {
        return parent::select($this->FncGetSyainMstValueSQL());
    }

    /*************************************
     * 処理名	：関数終了時処理
     * 関数名	：finally
     * 引数		：無し
     * 戻り値		：無し
     * 処理説明	：register_shutdown_functionで呼び出された関数の終了後に実行する処理
     *************************************/
    function finally()
    {
        if (isset($this->Sel_Array)) {
            if ($this->Sel_Array['Pra_sta'] != false) {
                oci_free_statement($this->Sel_Array['Pra_info']);
            }
        }

        if (isset($this->conn_orl)) {
            if ($this->conn_orl['conn_sta'] != false) {
                oci_close($this->conn_orl['conn_orl']);
            }

        }

        unset($this->Sel_Array);
        unset($this->conn_orl);
    }

    //**********************************************************************
    //処 理 名：Null変換関数(文字)
    //関 数 名：FncNv
    //引     数：$objValue     (I)文字列
    //戻 り 値：変換後の値
    //処理説明：Null変換(文字)を行う。
    //**********************************************************************
    public function FncNv($objValue, $objReturn = "")
    {
        //---NULLの場合---
        if ($objValue === null) {
            return $objReturn;
        }
        //---以外の場合---
        else {
            return $objValue;
        }
    }

    //**********************************************************************
    //処 理 名：Null変換関数(Sql文字)
    //関 数 名：FncSqlNv
    //引     数：$objValue     (I)文字列
    //戻 り 値：変換後の値
    //処理説明：Null変換(Sql文字)を行う。
    //**********************************************************************
    public function FncSqlNv($objValue, $objReturn = "")
    {
        //---NULLの場合---
        if ($objValue === null) {
            if ($objReturn != "") {
                return $objReturn;
            } else {
                return "''";
            }
        }
        //---以外の場合---
        else {
            return "'" . str_replace("'", "''", $objValue) . "'";
        }
    }

    //**********************************************************************
    //処 理 名：Null変換関数(SqlDate文字)
    //関 数 名：FncSqlDate
    //引     数：$objValue     (I)文字列
    //戻 り 値：変換後の値
    //処理説明：Null変換(SqlDate文字)を行う。
    //**********************************************************************
    public function FncSqlDate($objValue, $objReturn = "Null")
    {
        //---NULLの場合---
        if ($objValue === null) {
            return $objReturn;
        }
        //---以外の場合---
        else {
            return "TO_DATE('" . $objValue . "','YYYY/MM/DD HH24:MI:SS')";
        }
    }

    //**********************************************************************
    //処 理 名：Null変換関数(Sql文字)
    //関 数 名：FncSqlNz
    //引     数：$objValue     (I)文字列
    //戻 り 値：変換後の値
    //処理説明：Null変換(Sql文字)を行う。
    //**********************************************************************
    public function FncSqlNz($objValue, $objReturn = "")
    {
        //---NULLの場合---
        if ($objValue === null) {
            if ($objReturn != "") {
                return $objReturn;
            } else {
                return "Null";
            }
        }
        //---以外の場合---
        else {
            return $objValue;
        }
    }

    //**********************************************************************
    //処 理 名：Null変換関数(数値)
    //関 数 名：FncNz
    //引     数：$objValue     (I)文字列
    //戻 り 値：変換後の値
    //処理説明：Null変換(数値)を行う。
    //**********************************************************************
    public function FncNz($objValue)
    {
        //---NULLの場合---
        if ($objValue === null) {
            return 0;
        }
        //---空白の場合---
        elseif (trim($objValue) == '') {
            return 0;
        }
        //---その他---
        else {
            return $objValue;
        }
    }

    public function IsDate($date)
    {
        $dateArray = "";
        $timeType = "";
        $tmpYear = "";
        $tmpMonth = "";
        $tmpDay = "";
        $tmpTime = "";

        $dateArray = mb_split('\-|,|\\\\|\.|\/|\||_|~', $date);
        $timeType = stripos($date, ":");

        if ($timeType > 0) {
            if (count($dateArray) != 3) {
                return FALSE;
            } else {
                $tmpYear = $dateArray[0];
                $tmpMonth = $dateArray[1];
                $tmp = explode(" ", $dateArray[2]);
                $tmpDay = $tmp[0];
                $tmpTime = $tmp[1];
                if (!checkdate($tmpMonth, $tmpDay, $tmpYear)) {
                    return FALSE;
                }
                $tmp = mb_split(":", $tmpTime);

                if ($tmp[0] > 24) {
                    return FALSE;

                } elseif ($tmp[1] >= 60 || $tmp[2] >= 60) {
                    return FALSE;
                }
            }
        } else {
            if (count($dateArray) == 2) {
                $thisYear = date('Y');
                $tmpMonth = $dateArray[0];
                $tmpDay = $dateArray[1];
                if (!checkdate($tmpMonth, $tmpDay, $thisYear)) {
                    return FALSE;
                }
            } elseif (count($dateArray) == 3) {
                $thisYear = date('Y');
                $tmpYear = $dateArray[0];
                $tmpMonth = $dateArray[1];
                $tmpDay = $dateArray[2];
                switch (4 - strlen($tmpYear)) {
                    case '0':
                        // $tmpYear = $tmpYear;
                        break;
                    case '1':
                        $tmpYear = "0" . $tmpYear;
                        break;
                    case '2':
                        if ($tmpYear < 30) {
                            $tmpYear = "20" . $tmpYear;
                        }
                        break;
                    case '3':
                        $tmpYear = "200" . $tmpYear;
                        break;
                    case '4':
                        $tmpYear = $thisYear;
                        break;
                }
                if (!checkdate($tmpMonth, $tmpDay, $tmpYear)) {
                    return FALSE;
                }
            }
        }
        return TRUE;
    }

    public function FncGetSysDate($strFormat = 'Y-M-D')
    {
        try {
            $ClsComFnc = new ClsComFncHDKAIKEI();
            $result = $ClsComFnc->select();
            if ($result["result"] == false) {
                throw new \Exception($result["data"]);
            }
            $strDate = strtotime($result["data"][0]['SYS_DATE']);
            $strDate = date($strFormat, $strDate);
            return $strDate;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /*
           '**********************************************************************
           '処 理 名：丸め処理
           '関 数 名：fncRoundDou
           '引    数：dblDou    (I)数値
           '　 　 　：intRoundKeta (I)桁数
           '　 　 　：strRoundKbn  (I)端数処理区分
           '戻 り 値：変換後の値
           '処理説明：丸め処理を行う
           '**********************************************************************
           */

    public function fncRoundDou($dblDou, $intRoundKeta, $strRoundKbn)
    {
        $dCom1 = 0;
        $dCom2 = 0;
        $val = 0;
        switch ($strRoundKbn) {
            case '0':
                //切り捨て
                $val = intval($dblDou * (pow(10, $intRoundKeta)) / pow(10, $intRoundKeta));
                break;
            case '1':
                //四捨五入
                $val = intval($dblDou * (pow(10, $intRoundKeta)) + $dblDou < 0 ? 0.5 - 1 : 0.5) / pow(10, $intRoundKeta);
                break;
            case '2':
                //切り上げ
                $dCom1 = $dblDou * pow(10, $intRoundKeta);
                $dCom2 = $dblDou < 0 ? -0.999 : 0.999;
                $val = intval($dCom1 + $dCom2) / pow(10, $intRoundKeta);
                break;
            default:
                $val = $dblDou;
                break;
        }
        return $val;
    }

    //西暦=>和暦変換
    public function japDateChange($INDATE, $FORMAT)
    {
        $sql = "SELECT JPDATE('@INDATE','@FORMAT') AS japDate FROM dual";

        $sql = str_replace("@INDATE", $INDATE, $sql);
        $sql = str_replace("@FORMAT", $FORMAT, $sql);

        return parent::select($sql);
    }

    //20211115 WANGYING ADD S
    // 代わりに （TMRH）HD伝票集計科目マスタ（HDK_MST_KAMOK） を使用する
    public function FncGetKamokuMstValue($strCode, $strKomoku, $allFlag)
    {
        $strSQL = "";
        // if ($strKomoku == "999999") {
        // 	if ($allFlag) {
        // 		$strSQL .= "SELECT KAMOK_CD,KAMOK_SSK_NM KAMOK_NM" . "\r\n";
        // 	} else {
        // 		$strSQL .= "SELECT KAMOK_SSK_NM KAMOK_NM" . "\r\n";
        // 	}
        // 	$strSQL .= "FROM   M29FZ6 A" . "\r\n";
        // 	$strSQL .= "WHERE  NVL(KOUMK_CD,'00') = (SELECT MIN(NVL(KOUMK_CD,'00')) FROM M_KAMOKU B WHERE A.KAMOK_CD = B.KAMOK_CD GROUP BY B.KAMOK_CD)" . "\r\n";
        // 	if (!$allFlag) {
        // 		$strSQL .= "AND    A.KAMOK_CD = '@KAMOKUCD'" . "\r\n";
        // 	}
        // } else {
        // if ($allFlag) {
        // 	$strSQL .= "SELECT KAMOK_CD,KOUMK_CD,KMK_KUM_NM KAMOK_NM" . "\r\n";
        // } else {
        // 	$strSQL .= "SELECT KMK_KUM_NM KAMOK_NM" . "\r\n";
        // }
        $strSQL .= "SELECT KAMOK_CD,SUB_KAMOK_CD,KAMOK_NAME,SUB_KAMOK_NAME,KARI_TAX_KBN,KARI_TAX_KBN_NM,KASI_TAX_KBN,KASI_TAX_KBN_NM" . "\r\n";
        $strSQL .= "FROM   HDK_MST_KAMOKU" . "\r\n";
        // if (!$allFlag) {
        // 	$strSQL .= "WHERE  KAMOK_CD = '@KAMOKUCD'" . "\r\n";
        // 	$strSQL .= "AND    NVL(TRIM(KOUMK_CD),'00') = NVL(TRIM('@KOMOKU'),'00')" . "\r\n";
        // }
        // }
        // $strSQL = str_replace("@KAMOKUCD", $strCode, $strSQL);
        // $strSQL = str_replace("@KOMOKU", $strKomoku, $strSQL);
        // 20240227 YIN DEL S
        // $strSQL .= "WHERE USE_FLG='1'" . "\r\n";
        // 20240227 YIN DEL E

        return parent::select($strSQL);
    }

    //20211115 WANGYING ADD E
    //20211123 lqs INS S
    // M28M68	→　HDK_MST_TORIHIKISAKI
    // 取引先、支払先
    public function FncGetTorihikisakiMstValue($strCode, $all)
    {
        $strSQL = "";
        // $strSQL .= "SELECT ATO_DTRPTBNM, ATO_DTRPITCD" . "\r\n";
        $strSQL .= "SELECT TORIHIKISAKI_NAME, TORIHIKISAKI_CD" . "\r\n";
        $strSQL .= "FROM   HDK_MST_TORIHIKISAKI" . "\r\n";
        $strSQL .= "WHERE  1 = 1" . "\r\n";
        if (!$all) {
            $strSQL .= "AND  TORIHIKISAKI_CD = '@TORIHIKISAKI'" . "\r\n";
            $strSQL = str_replace("@TORIHIKISAKI", $strCode, $strSQL);
        }

        // $strSQL .= "AND    HANSH_CD = '3634'" . "\r\n";

        // if (!$all)
        // {
        // $strSQL = str_replace("@TORIHIKISAKI", $strCode, $strSQL);
        // }

        return parent::select($strSQL);
    }

    //20211123 lqs INS E
}