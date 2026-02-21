<?php

// 共通クラスの読込み
// App::uses('ClsComDb', 'Model/Component');

namespace App\Model\R4\Component;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：ClsComFnc
// * 処理説明：共通関数
//*************************************

class ClsComFnc extends ClsComDb
{
    // 20131004 kamei add Start
    // 解放が必要な変数をメンバーに設定
    // protected $conn_orl = '';
    // protected $Sel_Array = '';
    // 20131004 kamei add end
    protected $ClsComFnc;

    // 20140728 add start
    const GSYSTEM_KB = 0;
    // 20140728 add end

    public function selectSql()
    {
        return "SELECT TO_CHAR (SYSDATE,'YYYY-MM-DD HH24:MI:SS') SYS_DATE FROM DUAL";
    }

    public function FncGetKamokuMstValueSQL($strCode, $strKomoku, $strMstFormat)
    {
        //** ＳＱＬ作成
        $strSql = '';
        //2007/09/20 UPD Start
        if ('999999' == $strKomoku) {
            //科目名で検索(項目は科目でグルーピングした最初の項目)
            $strSql .= 'SELECT KAMOK_NM' . "\r\n";
            $strSql .= 'FROM  M_KAMOKU A' . "\r\n";
            $strSql .= "WHERE NVL(KOMOK_CD,'00') = (SELECT MIN(NVL(KOMOK_CD,'00')) FROM M_KAMOKU B WHERE A.KAMOK_CD = B.KAMOK_CD GROUP BY B.KAMOK_CD)" . "\r\n";
            $strSql .= "AND   A.KAMOK_CD = '@KAMOKUCD'" . "\r\n";
        } else {
            //科目・項目名で検索
            $strSql .= "SELECT (KAMOK_NM || ' ' || KOMOK_NM) KAMOK_NM" . "\r\n";
            $strSql .= 'FROM   M_KAMOKU' . "\r\n";
            $strSql .= "WHERE  KAMOK_CD = '@KAMOKUCD'" . "\r\n";
            //2007/09/20 UPD Start   科目ﾏｽﾀ統合
            //If strKomoku <> "" Then

            //strSql.Append("AND  KOMOK_CD = '@KOMOKU'" & vbCrLf)
            if ('' == $strMstFormat) {
                //2007/09/27 条件追加
                $strSql .= "AND  NVL(TRIM(KOMOK_CD),'00') = NVL(TRIM('@KOMOKU'),'00')" . "\r\n";
            } else {
                $strSql .= "AND  (CASE WHEN LENGTH(TRIM(KOMOK_CD)) > 2 THEN TRIM(KOMOK_CD) ELSE NVL(LPAD(TRIM(KOMOK_CD),2,'@MSTFORMAT'),'00') END) = NVL(TRIM('@KOMOKU'),'00')" . "\r\n";
                //2007/09/27 追加
            }
            //2007/09/20 UPD End
        }

        $strSql = str_replace('@KAMOKUCD', $strCode, $strSql);
        $strSql = str_replace('@KOMOKU', $strKomoku, $strSql);
        $strSql = str_replace('@MSTFORMAT', $strMstFormat, $strSql);

        return $strSql;
    }

    public function FncGetBusyoMstValueSQL($blnSyukei, $strCode)
    {
        $strSql = '';
        //** ＳＱＬ作成
        $strSql .= 'SELECT BUSYO_NM' . "\r\n";
        $strSql .= ',      KKR_BUSYO_CD ' . "\r\n";
        $strSql .= 'FROM   HBUSYO ' . "\r\n";
        $strSql .= "WHERE  BUSYO_CD = '@BUSYOCD' " . "\r\n";

        if (false == $blnSyukei) {
            $strSql .= "AND  ( SYUKEI_KB IS NULL OR  SYUKEI_KB <> '1')" . "\r\n";
        }

        $strSql = str_replace('@BUSYOCD', $strCode, $strSql);

        return $strSql;
    }

    //*************************************
    // * 公開メソッド
    //*************************************

    public function select($strsql = null)
    {
        return parent::select($this->selectSql());
    }

    public function FncGetKamokuMstValue($strCode, $strKomoku, $strMstFormat)
    {
        return parent::select($this->FncGetKamokuMstValueSQL($strCode, $strKomoku, $strMstFormat));
    }

    public function FncGetBusyoMstValue($blnSyukei, $strCode)
    {
        return parent::select($this->FncGetBusyoMstValueSQL($blnSyukei, $strCode));
    }

    /*************************************
     * 処理名	：関数終了時処理
     * 関数名	：finally
     * 引数		：無し
     * 戻り値		：無し
     * 処理説明	：register_shutdown_functionで呼び出された関数の終了後に実行する処理
     *************************************/
    public function finally()
    {
        if (isset($this->Sel_Array)) {
            if (false != $this->Sel_Array['Pra_sta']) {
                oci_free_statement($this->Sel_Array['Pra_info']);
            }
        }

        if (isset($this->conn_orl)) {
            if (false != $this->conn_orl['conn_sta']) {
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
    public function FncNv($objValue, $objReturn = '')
    {
        //---NULLの場合---
        if (null === $objValue) {
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
    public function FncSqlNv($objValue, $objReturn = '')
    {
        //---NULLの場合---
        if (null === $objValue) {
            if ('' != $objReturn) {
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
    public function FncSqlDate($objValue, $objReturn = 'Null')
    {
        //---NULLの場合---
        if (null === $objValue) {
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
    public function FncSqlNz($objValue, $objReturn = '')
    {
        //---NULLの場合---
        if (null === $objValue) {
            if ('' != $objReturn) {
                return $objReturn;
            } else {
                return 'Null';
            }
        }
        //---以外の場合---
        else {
            return "'" . str_replace("'", "''", $objValue) . "'";
            //return $objValue;
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
        if (null === $objValue) {
            return 0;
        }
        //---空白の場合---
        elseif ('' == trim($objValue)) {
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

        $dateArray = '';
        $timeType = '';
        $tmpYear = '';
        $tmpMonth = '';
        $tmpDay = '';
        $tmpTime = '';

        $dateArray = mb_split('\-|,|\\\\|\.|\/|\||_|~', $date);
        $timeType = stripos($date, ':');

        if ($timeType > 0) {
            if (3 != count($dateArray)) {
                return false;
            } else {
                $tmpYear = $dateArray[0];
                $tmpMonth = $dateArray[1];
                $tmp = explode(' ', $dateArray[2]);
                $tmpDay = $tmp[0];
                $tmpTime = $tmp[1];
                if (!checkdate($tmpMonth, $tmpDay, $tmpYear)) {
                    return false;
                }
                $tmp = mb_split(':', $tmpTime);

                if ($tmp[0] > 24) {
                    return false;
                } elseif ($tmp[1] >= 60 || $tmp[2] >= 60) {
                    return false;
                }
            }
        } else {
            if (2 == count($dateArray)) {
                $thisYear = date('Y');
                $tmpMonth = $dateArray[0];
                $tmpDay = $dateArray[1];
                if (!checkdate($tmpMonth, $tmpDay, $thisYear)) {
                    return false;
                }
            } elseif (3 == count($dateArray)) {
                $thisYear = date('Y');
                $tmpYear = $dateArray[0];
                $tmpMonth = $dateArray[1];
                $tmpDay = $dateArray[2];
                switch (4 - strlen($tmpYear)) {
                    case '0':
                        // $tmpYear = $tmpYear;
                        break;
                    case '1':
                        $tmpYear = '0' . $tmpYear;
                        break;
                    case '2':
                        if ($tmpYear < 30) {
                            $tmpYear = '20' . $tmpYear;
                        }
                        break;
                    case '3':
                        $tmpYear = '200' . $tmpYear;
                        break;
                    case '4':
                        $tmpYear = $thisYear;
                        break;
                }
                if (!checkdate($tmpMonth, $tmpDay, $tmpYear)) {
                    return false;
                }
            }
        }

        return true;
    }

    public function FncGetSysDate($strFormat = 'Y-M-D')
    {
        try {
            $this->ClsComFnc = new ClsComFnc();
            $result = $this->ClsComFnc->select();
            if (false == $result['result']) {
                throw new \Exception($result['data']);
            }
            $strDate = strtotime($result['data'][0]['SYS_DATE']);
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
            case '0': //切り捨て
                $val = intval($dblDou * (pow(10, $intRoundKeta)) / pow(10, $intRoundKeta));
                break;
            case '1': //四捨五入
                $val = intval($dblDou * (pow(10, $intRoundKeta)) + $dblDou < 0 ? 0.5 - 1 : 0.5) / pow(10, $intRoundKeta);
                break;
            case '2': //切り上げ
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
}