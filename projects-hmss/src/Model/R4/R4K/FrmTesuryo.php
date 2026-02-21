<?php
/**
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20150804           BUG2017                   BUG                            li
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

class FrmTesuryo extends ClsComDb
{
    public function fncFrmTesuryoSelect($sortStr = "")
    {
        $strSql = $this->fncFrmTesuryoSelect_sql($sortStr);
        return parent::select($strSql);
    }

    public function fncFrmTesuryoSelect_sql($sortStr = "")
    {
        $sortString = "";
        $sqlstr = "SELECT \n";
        $sqlstr .= "KIJYUN_DT\n";
        $sqlstr .= ", NEN_RT\n";
        $sqlstr .= ", SYANAI_RT\n";
        //---20150804 li UPD S.
        //$sqlstr .= ", ROUND(TESURYO,0) as TESURYO\n";
        //$sqlstr .= ", ROUND(TORITATERYO,0) as TORITATERYO\n";
        $sqlstr .= ", ROUND(TESURYO,0) as TESURYO\n";
        $sqlstr .= ", DECODE(ROUND(TESURYO,0), 0, '', ROUND(TESURYO,0)) as TESURYO \n";
        $sqlstr .= ", DECODE(ROUND(TORITATERYO,0), 0, '', ROUND(TORITATERYO,0)) as TORITATERYO \n";
        //---20150804 li UPD E.
        $sqlstr .= ", CAL_NISU \n";
        $sqlstr .= ", UPD_DATE \n";
        $sqlstr .= ", CREATE_DATE\n";
        $sqlstr .= ", UPD_SYA_CD\n";
        $sqlstr .= ", UPD_PRG_ID\n";
        $sqlstr .= ", UPD_CLT_NM \n";
        $sqlstr .= " FROM \n";
        $sqlstr .= " HTESURYO\n";
        if (trim($sortStr) != "") {
            $sortString .= " ORDER BY " . $sortStr . "";
        }
        return $sqlstr . " " . $sortString;
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

    //--sql--
    public function fncDelete_sql()
    {
        $sqlstr = "";
        $sqlstr = "DELETE FROM HTESURYO";
        return $sqlstr;
    }

    public function fncSingleDelete_sql($data)
    {
        $sqlstr = "";
        $sqlstr .= "DELETE FROM HTESURYO WHERE KIJYUN_DT='" . $data . "'";
        return $sqlstr;
    }

    public function fncInsert_sql($data)
    {
        $sqlstr = "";
        $sqlstr .= "INSERT INTO HTESURYO(";
        $sqlstr .= "		KIJYUN_DT \n";
        $sqlstr .= "		, NEN_RT \n";
        $sqlstr .= "		, SYANAI_RT \n";
        $sqlstr .= "		, TESURYO \n";
        $sqlstr .= "		, TORITATERYO \n";
        $sqlstr .= "		, CAL_NISU \n";
        $sqlstr .= "		, UPD_DATE \n";
        $sqlstr .= "		, CREATE_DATE \n";
        $sqlstr .= "		, UPD_SYA_CD \n";
        $sqlstr .= "		, UPD_PRG_ID \n";
        $sqlstr .= "		, UPD_CLT_NM \n";
        $sqlstr .= "		) VALUES (";
        $sqlstr .= "		'" . $data['KIJYUN_DT'] . "' \n";
        if ($data['NEN_RT'] == "") {
            $sqlstr .= "		,0 \n";
        } else {
            $sqlstr .= "		," . $data['NEN_RT'] . " \n";
        }
        if ($data['SYANAI_RT'] == "") {
            $sqlstr .= "		,0 \n";
        } else {
            $sqlstr .= "		," . $data['SYANAI_RT'] . " \n";
        }
        if ($data['TESURYO'] == "") {
            $sqlstr .= "		,0 \n";
        } else {
            $sqlstr .= "		," . $data['TESURYO'] . " \n";
        }
        if ($data['TORITATERYO'] == "") {
            $sqlstr .= "		,0 \n";
        } else {
            $sqlstr .= "		," . $data['TORITATERYO'] . " \n";
        }
        $sqlstr .= "		,365 \n";
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
        $sqlstr .= ",'Tesuryo'\n";
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