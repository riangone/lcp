<?php
// 共通クラスの読込み
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
/**
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                              担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150813           #1967                         BUG                             Yuanjh
 * * --------------------------------------------------------------------------------------------
 */
class FrmSyasyu extends ClsComDb
{
    public function fncFrmSyasyuSelect($postData, $sortStr)
    {
        $strSql = $this->fncFrmSyasyuSelect_sql($postData, $sortStr);
        return parent::select($strSql);
    }

    public function fncFrmSyasyuSelect_sql($postData, $sortStr)
    {
        $sortString = "  ";
        $whereString = "";
        if ($postData != "") {
            if (trim($postData['txtKANA']) != "") {
                $whereString .= " WHERE UCOYA_CD LIKE '" . $postData['txtKANA'] . "%'\n";
            }
        }
        if (trim($sortStr) != "") {
            $sortString .= " ORDER BY " . $sortStr . "";
        } else {
            $sortString .= " ORDER BY UCOYA_CD \n";
        }

        //---20150813 Yuanjh modify S.
        //$sqlstr = "SELECT * FROM HSYASYUMST  " . $whereString . $sortString;
        $sqlstr = "select UCOYA_CD
			                 ,SS_CD
			                 ,SS_NAME
			                 ,to_char(ARARI,'fm9999999990.00') as ARARI ,UPD_DATE
			                 ,CREATE_DATE
			                 ,UPD_SYA_CD
			                 ,UPD_PRG_ID
			                 ,UPD_CLT_NM
			                 FROM HSYASYUMST" . $whereString . $sortString;
        //---20150813 Yuanjh modify E.
        return $sqlstr;
    }

    public function fncDelete()
    {
        $strSql = $this->fncDelete_sql();
        return parent::Do_Execute($strSql);
    }

    public function fncInsert($data)
    {
        $strSql = $this->fncInsert_sql($data);
        return parent::Do_Execute($strSql);
    }

    //--sql--
    public function fncDelete_sql()
    {
        $sqlstr = "";
        $sqlstr = "DELETE FROM HSYASYUMST";
        return $sqlstr;
    }

    public function fncInsert_sql($data)
    {
        $sqlstr = "";
        $sqlstr .= "INSERT INTO HSYASYUMST(";
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
                if ($key == "ARARI") {
                    if ($value == "") {
                        $sqlstr .= "  0 \n";
                    } else {
                        $sqlstr .= $data[$key] . " \n";
                    }

                } else {
                    $sqlstr .= "		'" . $data[$key] . "' \n";
                }
            }
            $tmpcnt++;

        }

        if (trim($data['UPD_DATE']) == "") {
            $sqlstr .= "		,SYSDATE";
        } else {
            $sqlstr .= "		,TO_DATE(" . $this->fncSqlNv2($data['UPD_DATE']) . ",'YYYY/MM/DD HH24:MI:SS')";
        }
        if (trim($data['CREATE_DATE']) == "") {
            $sqlstr .= "		,SYSDATE";
        } else {
            $sqlstr .= "		,TO_DATE(" . $this->fncSqlNv2($data['CREATE_DATE']) . ",'YYYY/MM/DD HH24:MI:SS')";
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

}