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
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL　　　　　　　　
 * 20150812           #1972    「出力順」：本来空白になる値が「0」と表示されてしまっている。（更に「更新」すると0が保存されてしまう）  FANZHENGZHOU
 * * --------------------------------------------------------------------------------------------
 */
// 共通クラスの読込み
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmArariSyukeiMst extends ClsComDb
{
    public function fncFrmArariSyukeiMstSelect()
    {
        $strSql = $this->fncFrmArariSyukeiMstSelect_sql();
        return parent::select($strSql);
    }

    public function fncFrmArariSyukeiMstSelect_sql()
    {
        $sqlstr = "SELECT * FROM HARARISYUKEIMST ORDER BY OYA_CD  ";
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
        $sqlstr = "DELETE FROM HARARISYUKEIMST";
        return $sqlstr;
    }

    public function fncInsert_sql($data)
    {
        $sqlstr = "";
        $sqlstr .= "INSERT INTO HARARISYUKEIMST(";
        $tmpcnt = 0;
        foreach ($data as $key => $value) {
            if ($tmpcnt > 0) {
                $sqlstr .= ",";
            }
            $sqlstr .= " " . $key . " \n";
            $tmpcnt++;
        }
        $sqlstr .= ", UPD_DATE  \n";
        $sqlstr .= ", UPD_SYA_CD \n ";
        $sqlstr .= ", UPD_PRG_ID \n ";
        $sqlstr .= ", UPD_CLT_NM \n ";
        $sqlstr .= "		) VALUES (";
        $tmpcnt = 0;
        foreach ($data as $key => $value) {
            if ($key != "CREATE_DATE") {
                if ($tmpcnt > 0) {
                    $sqlstr .= ",";
                }
                //---20150812 #1972 fanzhengzhou upd s.
                //if ($key == "UNTIN_RITU" || $key == "DISP_NO")
                if ($key == "UNTIN_RITU")
                //---20150812 #1972 fanzhengzhou upd e.
                {
                    if ($value == "") {
                        $sqlstr .= "0\n";
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
        $sqlstr .= ",'ArariSyukeiMst'\n";
        $sqlstr .= ",'" . $this->GS_LOGINUSER['strClientNM'] . "'\n";
        $sqlstr .= ")";
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
