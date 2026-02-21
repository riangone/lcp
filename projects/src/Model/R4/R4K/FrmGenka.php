<?php

/**
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug             内容                               担当
 * YYYYMMDD           #ID                     XXXXXX                            FCSDL
 * 20150717           #1965                   原価マスタを表示するときに時間がかかる        ZHENGHUIYUN
 * 20150831           #2101                   BUG                                   LI
 * --------------------------------------------------------------------------------------------
 */

// 共通クラスの読込み
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmGenka extends ClsComDb
{
    //20150717 #1965 zhenghuiyun add s

    public function fncFrmGenkaSelectCnt($postData, $sortStr)
    {
        //20150717 e
        $strSql = $this->fncFrmGenkaSelectCnt_sql($postData, $sortStr);
        return parent::select($strSql);
    }

    public function fncFrmGenkaSelectCnt_sql($postData, $sortStr)
    {
        $sortString = "  ";
        $whereString = "";
        if ($postData != "") {
            if (trim($postData['txtTOA_NAME']) != "") {
                $whereString .= " WHERE TOA_NAME LIKE'" . $postData['txtTOA_NAME'] . "%'\n";
            }
        }
        if (trim($sortStr) != "") {
            $sortString .= " ORDER BY " . $sortStr . "";
        }
        $sqlstr = "SELECT COUNT(*) AS CNT FROM HGENKAMST  " . $whereString . $sortString;

        return $sqlstr;
    }

    //20150717 #1965 zhenghuiyun add e

    //20150717 #1965 zhenghuiyun upd s

    // public function fncFrmGenkaSelect($postData, $sortStr)
    // {
    // 	$strSql = $this -> fncFrmGenkaSelect_sql($postData, $sortStr);
    // 	return parent::select($strSql);
    // }
    public function fncFrmGenkaSelect($postData, $sortStr, $start, $limit)
    {
        $strSql = $this->fncFrmGenkaSelect_sql($postData, $sortStr, $start, $limit);
        return parent::select($strSql);
    }

    //20150717 #1965 zhenghuiyun upd e

    //20150717 #1965 zhenghuiyun upd s
    // public function fncFrmGenkaSelect_sql($postData, $sortStr)
    public function fncFrmGenkaSelect_sql($postData, $sortStr, $start, $limit)
    //20150717 #1965 zhenghuiyun upd e
    {
        $sortString = "  ";
        $whereString = "";
        if ($postData != "") {
            if (trim($postData['txtTOA_NAME']) != "") {
                $whereString .= " WHERE TOA_NAME LIKE'" . $postData['txtTOA_NAME'] . "%'\n";
            }
        }
        if (trim($sortStr) != "") {
            $sortString .= " ORDER BY " . $sortStr . "";
        }
        $sqlstr = "SELECT * FROM HGENKAMST  " . $whereString . $sortString;

        //20150717 #1965 zhenghuiyun add e
        $cell = "*";
        if (trim($start) != "") {
            $start = " WHERE RNM >" . $start;
        }
        if (trim($limit) != "") {
            $limit = " WHERE ROWNUM<=" . $limit;
        }
        $sqlstr = "SELECT " . $cell . " FROM (SELECT TBL." . $cell . ",ROWNUM RNM FROM ( " . $sqlstr . ") TBL " . $limit . ") " . $start;
        //20150717 #1965 zhenghuiyun add e
        //---20150831 li INS S.
        $sqlstr = $sqlstr . " ORDER BY ID, TOA_NAME ";
        //---20150831 li INS E.

        return $sqlstr;
    }

    public function fncDelete()
    {
        $strSql = $this->fncDelete_sql();
        return parent::Do_Execute($strSql);
    }

    public function fncSingleDelete($data)
    {
        $strSql = $this->fncSingleDelete_sql($data);
        return parent::delete($strSql);
    }

    public function fncInsert($data)
    {
        $strSql = $this->fncInsert_sql($data);
        return parent::Do_Execute($strSql);
    }

    //20150717 #1965 zhenghuiyun add s

    public function fncDeleteByKey($data)
    {
        $strSql = $this->fncDeleteByKey_sql($data);
        return parent::Do_Execute($strSql);
    }

    //20150717 #1965 zhenghuiyun add e

    //--sql--
    public function fncDelete_sql()
    {
        $sqlstr = "";
        $sqlstr = "DELETE FROM HGENKAMST";
        return $sqlstr;
    }

    public function fncSingleDelete_sql($data)
    {
        $sqlstr = "";
        $sqlstr .= "DELETE FROM HGENKAMST WHERE KIJYUN_DT='" . $data . "'";
        return $sqlstr;
    }

    public function fncInsert_sql($data)
    {
        $sqlstr = "";
        $sqlstr .= "INSERT INTO HGENKAMST(";
        $tmpcnt = 0;
        foreach ($data as $key => $value) {
            if ($tmpcnt > 0) {
                $sqlstr .= ",";
            }
            $sqlstr .= " " . $key . " \n";
            $tmpcnt++;
        }

        $sqlstr .= "		) VALUES (";
        $tmpcnt = 0;
        foreach ($data as $key => $value) {

            if ($key != "UPD_DATE" && $key != "CREATE_DATE" && $key != "UPD_SYA_CD" && $key != "UPD_PRG_ID" && $key != "UPD_CLT_NM") {
                if ($tmpcnt > 0) {
                    $sqlstr .= ",";
                }
                if ($key == "ID" || $key == "TOA_NAME") {
                    $sqlstr .= "		'" . $data[$key] . "' \n";
                } else {

                    if ($value == "") {
                        $sqlstr .= "  0 \n";
                    } else {
                        $sqlstr .= $data[$key] . " \n";
                    }
                }
            }
            $tmpcnt++;

        }

        if (count($data) != 14 && trim($data['UPD_DATE']) == "") {
            $sqlstr .= "		,SYSDATE";
        } else {
            if (count($data) != 14) {
                $sqlstr .= "		,TO_DATE(" . $this->fncSqlNv2($data['UPD_DATE']) . ",'YYYY/MM/DD HH24:MI:SS')";
            }
        }
        if (count($data) != 14 && trim($data['CREATE_DATE']) == "") {
            $sqlstr .= "		,SYSDATE";
        } else {
            if (count($data) != 14) {
                $sqlstr .= "		,TO_DATE(" . $this->fncSqlNv2($data['CREATE_DATE']) . ",'YYYY/MM/DD HH24:MI:SS')";
            }
        }
        $sqlstr .= ",'" . $this->GS_LOGINUSER['strUserID'] . "' \n";
        $sqlstr .= ",'Genka'\n";
        $sqlstr .= ",'" . $this->GS_LOGINUSER['strClientNM'] . "'\n";
        $sqlstr .= ")";
        //echo $sqlstr;
        return $sqlstr;
    }

    public function fncSqlNv2($objValue, $objReturn = "", $intKind = 1)
    {
        if ($objValue == "" || $objValue === null) {
            if ($objReturn != "") {
                return $objReturn;
            } else {
                return "''";
            }
        } else {
            if ($objValue == "") {
                return "Null";
            } else {
                if ($intKind == 1) {
                    return "'" . str_replace("'", "''", $objValue) . "'";
                } else {
                    return str_replace("'", "''", $objValue);
                }
            }
        }
    }

    //20150717 #1965 zhenghuiyun add s

    public function fncDeleteByKey_sql($data)
    {
        $sqlstr = "";
        $sqlstr .= "DELETE FROM " . "\r\n";
        $sqlstr .= "HGENKAMST " . "\r\n";
        $sqlstr .= "WHERE " . "\r\n";
        $sqlstr .= "TOA_NAME='" . $data["TOA_NAME"] . "'" . "\r\n";
        $sqlstr .= "AND " . " HTA_PRC='" . $data["HTA_PRC"] . "'" . "\r\n";
        return $sqlstr;
    }

    //20150717 #1965 zhenghuiyun add e

}