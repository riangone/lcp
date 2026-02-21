<?php
/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                            内容                                 担当
 * YYYYMMDD           #ID                                    XXXXXX                               FCSDL
 * 20251222         機能追加要望                    2026年1月から、法人営業が２Gr体制となります      YIN
 * -------------------------------------------------------------------------------------------------------
 */
// 共通クラスの読込み
namespace App\Model\HMTVE\Component;

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFnc;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：ClsComFnc
// * 処理説明：共通関数
//*************************************

class ClsComFncHMTVE extends ClsComDb
{
    public $ClsComFnc;
    //*************************************
    // * 公開メソッド
    //*************************************
    public function FncGetBusyoMstValueSQL()
    {
        $strSql = "";
        //** ＳＱＬ作成
        $strSql .= "SELECT BUSYO_CD" . "\r\n";
        $strSql .= ",      BUSYO_RYKNM" . "\r\n";
        $strSql .= ",      BUSYO_NM" . "\r\n";
        $strSql .= "FROM   HBUSYO" . "\r\n";

        return $strSql;
    }

    public function FncGetBusyoMstValue()
    {
        return parent::select($this->FncGetBusyoMstValueSQL());
    }
    public function select($strsql = NULL)
    {
        return parent::select($this->selectSql());
    }

    function selectSql()
    {
        return "SELECT TO_CHAR (SYSDATE,'YYYY-MM-DD HH24:MI:SS') SYS_DATE FROM DUAL";
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
        //$tmp = "";

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
            $this->ClsComFnc = new ClsComFnc();
            $result = $this->ClsComFnc->select();
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

    //20150512 yushuangji add s
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
                $val = intval(($dblDou * (pow(10, $intRoundKeta))) + $dblDou < 0 ? 0.5 - 1 : 0.5) / pow(10, $intRoundKeta);
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

    //20150512 yushuangji add s

    //西暦=>和暦変換
    public function japDateChange($INDATE, $FORMAT)
    {
        $sql = "SELECT JPDATE('@INDATE','@FORMAT') AS japDate FROM dual";

        $sql = str_replace("@INDATE", $INDATE, $sql);
        $sql = str_replace("@FORMAT", $FORMAT, $sql);

        return parent::select($sql);
    }

    function appendBusyoCondition(string $strSQL, string $month, string $table): string
    {
        if ($month >= "20160501") {
            // 471：中広・販
            $strSQL .= " AND  $table.BUSYO_CD not in ('471') " . "\r\n";
        }

        if ($month >= "20170301") {
            // 271：ボルボ西販
            $strSQL .= " AND  $table.BUSYO_CD not in ('271') " . "\r\n";
        }

        if ($month >= "20220901") {
            // ・2022/9 以降、黒瀬店 を非表示に
            $strSQL .= " AND  $table.BUSYO_CD NOT IN ('550') " . "\r\n";
        }

        if ($month >= "20251201") {
            // 290（ボルボ大州）、291（ボルボ販）、241（ｶｰｴｰｽ比販）、240（ｶｰｴｰｽ比）を 2025/12以降は非表示
            $strSQL .= " AND  $table.BUSYO_RYKNM NOT LIKE '%ボルボ%' " . "\r\n";
            $strSQL .= " AND  $table.BUSYO_RYKNM NOT LIKE '%カーエース%' " . "\r\n";
            $strSQL .= " AND  $table.BUSYO_RYKNM NOT LIKE '%ﾎﾞﾙﾎﾞ%' " . "\r\n";
            $strSQL .= " AND  $table.BUSYO_RYKNM NOT LIKE '%ｶｰｴｰｽ%' " . "\r\n";
            // ・2025/12以降、新車特約を非表示
            $strSQL .= " AND  $table.BUSYO_CD NOT IN ('191') " . "\r\n";
        }

        if ($month < "20251201") {
            // ・224：大州UC
            // ・261：カーセブンを追加
            // ・新たに表示する部署に中古拡販Grを表示（中古拡販：表示　部署231）
            $strSQL .= " AND  $table.BUSYO_CD not in ('224','261','231') " . "\r\n";
        }

        return $strSQL;
    }

}
