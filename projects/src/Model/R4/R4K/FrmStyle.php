<?php
namespace App\Model\R4\R4K;

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
 * 20150827           #2090						   BUG                              li  　　
 * --------------------------------------------------------------------------------------------
 */
// 共通クラスの読込み
use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmStyle extends ClsComDb
{
    public function fncFrmStyleSelect()
    {
        $strSql = $this->fncFrmStyleSelect_sql();
        return parent::select($strSql);
    }

    public function fncDelete()
    {
        $strSql = $this->fncDelete_sql();
        return parent::delete($strSql);
    }

    public function fncInsert($data)
    {
        $strSql = $this->fncInsert_sql($data);
        return parent::Do_Execute($strSql);
    }

    public function fncSingleDel($data)
    {
        $strSql = $this->fncSingleDel_sql($data);
        return parent::delete($strSql);
    }

    public function fncPatternDel($data)
    {
        $strSql = $this->fncPatternDel_sql($data);
        return parent::delete($strSql);
    }

    public function fncNmPatternDel($data)
    {
        $strSql = $this->fncNmPatternDel_sql($data);
        return parent::delete($strSql);
    }

    public function fncKaisouMstDel($data)
    {
        $strSql = $this->fncKaisouMstDel_sql($data);
        return parent::delete($strSql);
    }

    public function fncUpdateLog($data)
    {
        $strSql = $this->fncUpdateLog_sql($data);
        return parent::Do_Execute($strSql);
    }


    public function fncFrmStyleSelect_sql()
    {

        $sqlstr = "";
        $sqlstr .= "SELECT STYLE_ID,";
        $sqlstr .= "       STYLE_NM, ";
        $sqlstr .= "       TO_CHAR(CREATE_DATE,'YYYY/MM/DD HH:MI:SS') as CREATE_DATE ";
        $sqlstr .= "FROM   HMENUSTYLE ";
        $sqlstr .= "WHERE  SYS_KB = '0'";
        $sqlstr .= "ORDER  BY STYLE_ID ";

        return $sqlstr;

    }

    //--SQL--
    public function fncDelete_sql()
    {
        $sqlstr = "";
        //---20150827 li UPD S.
        //$sqlstr .= " DELETE　FROM　HMENUSTYLE";
        $sqlstr .= " DELETE FROM HMENUSTYLE";
        //---20150827 li UPD E.
        $sqlstr .= " WHERE  SYS_KB = '0'";
        return $sqlstr;
    }

    //Insert SQL
    public function fncInsert_sql($data)
    {
        $sqlstr = "";
        $sqlstr .= "INSERT INTO HMENUSTYLE\r\n";
        $sqlstr .= "(			SYS_KB\r\n";
        $sqlstr .= ",			STYLE_ID\r\n";
        $sqlstr .= ",			STYLE_NM\r\n";
        $sqlstr .= ",			UPD_DATE\r\n";
        $sqlstr .= ",			CREATE_DATE\r\n";
        $sqlstr .= ",			UPD_SYA_CD\r\n";
        $sqlstr .= ",			UPD_PRG_ID\r\n";
        $sqlstr .= ",			UPD_CLT_NM\r\n";
        $sqlstr .= ") VALUES (  \n ";
        $sqlstr .= $this->fncSqlNv2('0') . " \n";
        $sqlstr .= "," . $this->fncSqlNv2($data['STYLE_ID']) . " \n";
        $sqlstr .= "," . $this->fncSqlNv2($data['STYLE_NM']) . " \n";
        $sqlstr .= ",SYSDATE \n";
        // CREATE_DATE is ＮＵＬＬ
        if (trim($data['CREATE_DATE']) == "") {
            $sqlstr .= "		,SYSDATE  \n";
        } else {
            $sqlstr .= "		,TO_DATE(" . $this->fncSqlNv2($data['CREATE_DATE']) . ",'YYYY/MM/DD HH24:MI:SS')";
        }
        $sqlstr .= ",'" . $this->GS_LOGINUSER['strUserID'] . "' \n";
        $sqlstr .= ",'frmStyle'\n";
        $sqlstr .= ",'" . $this->GS_LOGINUSER['strClientNM'] . "'\n";
        $sqlstr .= ")";
        return $sqlstr;
    }

    public function fncSingleDel_sql($data)
    {
        $sqlstr = "";
        //---20150827 li UPD S.
        //$sqlstr .= " DELETE　FROM　HMENUSTYLE";
        $sqlstr .= " DELETE FROM HMENUSTYLE";
        //---20150827 li UPD E.
        $sqlstr .= " WHERE  SYS_KB = '0'";
        $sqlstr .= " AND    STYLE_ID = '" . $data . "'";
        return $sqlstr;
    }

    //ﾊﾟﾀｰﾝﾏｽﾀ削除処理
    public function fncPatternDel_sql($data)
    {
        $sqlstr = "";
        $sqlstr .= " DELETE FROM HMENUKANRIPATTERN";
        $sqlstr .= " WHERE  SYS_KB = '0'";
        $sqlstr .= " AND    STYLE_ID = '" . $data . "'";
        return $sqlstr;
    }
    //パターン名マスタ削除
    public function fncNmPatternDel_sql($data)
    {
        $sqlstr = "";
        $sqlstr .= " DELETE FROM HPATTERNMST";
        $sqlstr .= " WHERE  SYS_KB = '0'";
        $sqlstr .= " AND    STYLE_ID = '" . $data . "'";
        return $sqlstr;
    }
    //階層ﾏｽﾀ削除処理
    public function fncKaisouMstDel_sql($data)
    {
        $sqlstr = "";
        $sqlstr .= " DELETE FROM HMENUKAISOUMST";
        $sqlstr .= " WHERE  SYS_KB = '0'";
        $sqlstr .= " AND    STYLE_ID = '" . $data . "'";
        return $sqlstr;
    }
    //ログインﾃｰﾌﾞﾙ更新処理(所属とﾊﾟﾀｰﾝを削除)
    public function fncUpdateLog_sql($data)
    {
        $sqlstr = "";
        $sqlstr .= "UPDATE M_LOGIN";
        //---20150827 li UPD S.
        //$sqlstr .= "SET    STYLE_ID = NULL";
        $sqlstr .= " SET    STYLE_ID = NULL";
        //---20150827 li UPD E.
        $sqlstr .= ",      PATTERN_ID = NULL";
        $sqlstr .= ",      REC_UPD_DT = SYSDATE";
        $sqlstr .= ",      UPD_SYA_CD = '@UPDUSER'";
        $sqlstr .= ",      UPD_PRG_ID = 'frmStyle'";
        $sqlstr .= ",      UPD_CLT_NM = '@UPDCLTNM'";
        //---20150827 li UPD S.
        // $sqlstr .= "WHERE  STYLE_ID = '" . $data . "'";
        // $sqlstr .= "AND    SYS_KB = '0'";
        $sqlstr .= " WHERE  STYLE_ID = '" . $data . "'";
        $sqlstr .= " AND    SYS_KB = '0'";
        //---20150827 li UPD E.

        $sqlstr = str_replace("@UPDUSER", $this->GS_LOGINUSER['strUserID'], $sqlstr);
        $sqlstr = str_replace("@UPDCLTNM", $this->GS_LOGINUSER['strClientNM'], $sqlstr);

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