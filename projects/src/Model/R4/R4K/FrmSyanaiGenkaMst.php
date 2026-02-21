<?php
/**
 * 説明：
 *
 *
 * @author FCS
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20150812           #1974      新中区分よりも「社内原価」のASCを優先してください。     FANZHENGZHOU
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

class FrmSyanaiGenkaMst extends ClsComDb
{
    public function fncFrmSyanaiGenkaMstSelect($postData)
    {
        $strSql = $this->fncFrmSyanaiGenkaMstSelect_sql($postData);
        return parent::select($strSql);
    }

    public function fncFrmSyanaiGenkaMstSelect_sql($postData)
    {
        $sortString = "";
        $whereString = "";
        if ($postData != "") {
            if (trim($postData['txtNauKB']) != "") {
                //---20150812 #1974 fanzhengzhou upd s.
                //$whereString .= " WHERE NAU_KB LIKE '" . $postData['txtNauKB'] . "%'\n";
                $whereString .= " WHERE NAU_KB = '" . $postData['txtNauKB'] . "'\n";
                //---20150812 #1974 fanzhengzhou upd e.
            }
        }
        $sortString .= " ORDER BY KIJYUN_DT, KJN_GENKA \n";
        //---20150812 #1974 fanzhengzhou upd s.
        //$sqlstr = "SELECT * FROM HKIJUNGENKATBL  " . $whereString;
        $sqlstr = "SELECT * FROM HKIJUNGENKATBL  " . $whereString . $sortString;
        //---20150812 #1974 fanzhengzhou upd e.
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

    public function fncSingleDelete($KIJYUN_DT, $NAU_KB, $KJN_GENKA)
    {
        $strSql = $this->fncSingleDelete_sql($KIJYUN_DT, $NAU_KB, $KJN_GENKA);
        return parent::delete($strSql);
    }

    //--sql--
    public function fncDelete_sql()
    {
        $sqlstr = "";
        $sqlstr = "DELETE FROM HKIJUNGENKATBL";
        return $sqlstr;
    }

    public function fncInsert_sql($data)
    {
        $sqlstr = "";
        $sqlstr .= "INSERT INTO HKIJUNGENKATBL(";
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
                if ($key != "KJN_GENKA" || $key != "HAIBUN_GK1" || $key != "HAIBUN_GK2" || $key != "HAIBUN_GK3") {
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
        if (trim($data['CREATE_DATE']) == "") {
            $sqlstr .= "		,SYSDATE";
        } else {
            $sqlstr .= "		,TO_DATE(" . $this->fncSqlNv2($data['CREATE_DATE']) . ",'YYYY/MM/DD HH24:MI:SS')";
        }
        $sqlstr .= "		,SYSDATE";
        $sqlstr .= ",'" . $this->GS_LOGINUSER['strUserID'] . "' \n";
        $sqlstr .= ",'SyanaiGenkaMst'\n";
        $sqlstr .= ",'" . $this->GS_LOGINUSER['strClientNM'] . "'\n";
        $sqlstr .= ")";
        return $sqlstr;

    }

    public function fncSingleDelete_sql($KIJYUN_DT, $NAU_KB, $KJN_GENKA)
    {
        $sqlstr = "";
        $sqlstr .= "DELETE FROM HKIJUNGENKATBL\n";
        $sqlstr .= " WHERE KIJYUN_DT = '@KJNDT'\n";
        $sqlstr .= "   AND NAU_KB = '@NAUKB'\n";
        $sqlstr .= "   AND KJN_GENKA = @KJNGENKA\n";

        $sqlstr = str_replace("@KJNDT", $KIJYUN_DT, $sqlstr);
        $sqlstr = str_replace("@NAUKB", $NAU_KB, $sqlstr);
        $sqlstr = str_replace("@KJNGENKA", $KJN_GENKA, $sqlstr);
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