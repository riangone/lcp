<?php
/**
 * 説明：
 *
 *
 * @author fanzhengzhou
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * --------------------------------------------------------------------------------------------
 */
namespace App\Model\R4\KRSS;

use App\Model\Component\ClsComDb;
class FrmSimLineMstNew extends ClsComDb
{
    public $UPD_PRG_ID = 'SimLineMstNew';

    public function select_line_new_sql()
    {
        $strSQL = "";
        $strSQL .= "SELECT LINE_NO " . "\r\n";
        $strSQL .= ",      ITEM_NM" . "\r\n";
        $strSQL .= ",      SRC_KB" . "\r\n";
        $strSQL .= ",      RND_KB" . "\r\n";
        $strSQL .= ",      RND_POS" . "\r\n";
        $strSQL .= ",      CAL_KB" . "\r\n";
        $strSQL .= ",      DISP_KB" . "\r\n";
        //        $strSQL .= "FROM   HLINEMST_NEW" . "\r\n";
        $strSQL .= "FROM   HLINEMST_KEIEISEIKA " . "\r\n";
        $strSQL .= "ORDER BY LINE_NO" . "\r\n";
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：ライン一覧データの検索
    //関 数 名：select_line_new
    //引    数：
    //戻 り 値：
    //処理説明：ライン一覧データの検索
    //**********************************************************************
    public function select_line_new()
    {
        return parent::select($this->select_line_new_sql());
    }

    public function showkamokusql($post_line_no)
    {
        $strSQL = "";
        $strSQL .= "SELECT LINE_NO " . "\r\n";
        $strSQL .= ",      KAMOK_CD" . "\r\n";
        $strSQL .= ",      HIMOK_CD" . "\r\n";
        $strSQL .= ",      CAL_KB CAL_KB1" . "\r\n";
        //        $strSQL .= "FROM   HKMKLINEMST_NEW " . "\r\n";
        $strSQL .= "FROM   HKMKLINEMST_KEIEISEIKA " . "\r\n";
        $strSQL .= "WHERE  LINE_NO='@LINE_NO' " . "\r\n";
        $strSQL .= "ORDER BY " . "\r\n";
        $strSQL .= " KAMOK_CD,HIMOK_CD,LINE_NO" . "\r\n";
        $strSQL = str_replace("@LINE_NO", $post_line_no, $strSQL);

        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：科目から集計一覧データの検索
    //関 数 名：showkamoku
    //引    数：
    //戻 り 値：
    //処理説明：科目から集計一覧データの検索
    //**********************************************************************
    public function showkamoku($post_line_no)
    {
        return parent::select($this->showkamokusql($post_line_no));
    }

    public function show_src_viewname_sql($post_line_no)
    {
        $strSQL = "";
        $strSQL .= "SELECT SRC_VIEWNAME " . "\r\n";
        //        $strSQL .= "FROM   HLINEMST_NEW " . "\r\n";
        $strSQL .= "FROM   HLINEMST_KEIEISEIKA " . "\r\n";
        $strSQL .= "WHERE  LINE_NO='@LINE_NO'" . "\r\n";
        $strSQL = str_replace("@LINE_NO", $post_line_no, $strSQL);
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：科目以外から集計データの検索
    //関 数 名：show_src_viewname
    //引    数：
    //戻 り 値：
    //処理説明：科目以外から集計データの検索
    //**********************************************************************
    public function show_src_viewname($post_line_no)
    {
        return parent::select($this->show_src_viewname_sql($post_line_no));
    }

    public function select_line_sql($post_line_no)
    {
        $strSQL = "";
        $strSQL .= "SELECT * " . "\r\n";
        //        $strSQL .= "FROM   HLINEMST_NEW " . "\r\n";
        $strSQL .= "FROM   HLINEMST_KEIEISEIKA " . "\r\n";
        $strSQL .= "WHERE  LINE_NO='@LINE_NO'" . "\r\n";
        $strSQL = str_replace("@LINE_NO", $post_line_no, $strSQL);

        return $strSQL;
    }

    public function select_line($post_line_no)
    {
        return parent::Fill($this->select_line_sql($post_line_no));
    }

    public function insert_line_sql($postArr, $post_SRC_VIEWNAME, $UPD_SYA_CD, $UPD_CLT_NM, $flag)
    {
        $strSQL = "";
        //$strSQL .= "INSERT INTO HLINEMST_NEW " . "\r\n";
        $strSQL .= "INSERT INTO HLINEMST_KEIEISEIKA " . "\r\n";
        $strSQL .= "( LINE_NO " . "\r\n";
        $strSQL .= ", ITEM_NM " . "\r\n";
        $strSQL .= ", SRC_KB " . "\r\n";
        $strSQL .= ", RND_KB " . "\r\n";
        $strSQL .= ", RND_POS " . "\r\n";
        $strSQL .= ", CAL_KB " . "\r\n";
        $strSQL .= ", DISP_KB " . "\r\n";
        $strSQL .= ", CREATE_DATE " . "\r\n";
        $strSQL .= ", UPD_DATE " . "\r\n";
        $strSQL .= ", UPD_SYA_CD " . "\r\n";
        $strSQL .= ", UPD_PRG_ID " . "\r\n";
        $strSQL .= ", UPD_CLT_NM " . "\r\n";
        if ($flag == TRUE) {
            $strSQL .= ", SRC_VIEWNAME " . "\r\n";
        }
        $strSQL .= ") " . "\r\n";
        $strSQL .= "VALUES " . "\r\n";
        $strSQL .= "( " . $this->FncSqlNz2($postArr['LINE_NO']) . "\r\n";
        $strSQL .= ", '" . $postArr['ITEM_NM'] . "'" . "\r\n";
        $strSQL .= ", '" . $postArr['SRC_KB'] . "'" . "\r\n";
        $strSQL .= ", '" . $postArr['RND_KB'] . "'" . "\r\n";
        $strSQL .= ", " . $this->FncSqlNz2($postArr['RND_POS']) . "\r\n";
        $strSQL .= ", " . $this->FncSqlNz2($postArr['CAL_KB']) . "\r\n";
        $strSQL .= ", '" . $postArr['DISP_KB'] . "'" . "\r\n";
        $strSQL .= ", SYSDATE" . "\r\n";
        $strSQL .= ", SYSDATE" . "\r\n";
        $strSQL .= ", '" . $UPD_SYA_CD . "'" . "\r\n";
        $strSQL .= ", '" . $this->UPD_PRG_ID . "'" . "\r\n";
        $strSQL .= ", '" . $UPD_CLT_NM . "'" . "\r\n";
        if ($flag == TRUE) {
            $strSQL .= ", '" . $post_SRC_VIEWNAME . "'" . "\r\n";
        }
        $strSQL .= ")" . "\r\n";
        return $strSQL;
    }

    public function insert_line($postArr, $post_SRC_VIEWNAME, $UPD_SYA_CD, $UPD_CLT_NM, $flag)
    {
        return parent::Do_Execute($this->insert_line_sql($postArr, $post_SRC_VIEWNAME, $UPD_SYA_CD, $UPD_CLT_NM, $flag));
    }

    public function update_line_sql($postArr, $post_SRC_VIEWNAME, $UPD_SYA_CD, $UPD_CLT_NM, $flag)
    {
        $strSQL = "";
        //        $strSQL .= "UPDATE HLINEMST_NEW " . "\r\n";
        $strSQL .= "UPDATE HLINEMST_KEIEISEIKA " . "\r\n";
        $strSQL .= "SET " . "\r\n";
        $strSQL .= "ITEM_NM = '" . $postArr['ITEM_NM'] . "'" . "\r\n";
        $strSQL .= ",SRC_KB = '" . $postArr['SRC_KB'] . "'" . "\r\n";
        $strSQL .= ",RND_KB = '" . $postArr['RND_KB'] . "'" . "\r\n";
        $strSQL .= ",RND_POS = " . $this->FncSqlNz2($postArr['RND_POS']) . "\r\n";
        $strSQL .= ",CAL_KB = " . $this->FncSqlNz2($postArr['CAL_KB']) . "\r\n";
        $strSQL .= ",DISP_KB = '" . $postArr['DISP_KB'] . "'" . "\r\n";
        $strSQL .= ", UPD_DATE = SYSDATE" . "\r\n";
        $strSQL .= ", UPD_SYA_CD = '" . $UPD_SYA_CD . "'" . "\r\n";
        $strSQL .= ", UPD_PRG_ID = '" . $this->UPD_PRG_ID . "'" . "\r\n";
        $strSQL .= ", UPD_CLT_NM = '" . $UPD_CLT_NM . "'" . "\r\n";
        if ($flag == TRUE) {
            $strSQL .= ",SRC_VIEWNAME = '" . $post_SRC_VIEWNAME . "'" . "\r\n";
        }
        $strSQL .= " WHERE " . "\r\n";
        $strSQL .= " LINE_NO = " . $postArr['LINE_NO'] . "\r\n";

        return $strSQL;
    }

    public function update_line($postArr, $post_SRC_VIEWNAME, $UPD_SYA_CD, $UPD_CLT_NM, $flag)
    {
        return parent::Do_Execute($this->update_line_sql($postArr, $post_SRC_VIEWNAME, $UPD_SYA_CD, $UPD_CLT_NM, $flag));
    }

    public function select_kamoku_sql($postArr, $postSelLineNo)
    {
        $strSQL = "";
        $strSQL .= "SELECT * " . "\r\n";
        //        $strSQL .= "FROM   HKMKLINEMST_NEW " . "\r\n";
        $strSQL .= "FROM   HKMKLINEMST_KEIEISEIKA " . "\r\n";
        $strSQL .= "WHERE  KAMOK_CD='@KAMOK_CD'" . "\r\n";
        $strSQL .= "AND  HIMOK_CD='@HIMOK_CD'" . "\r\n";
        $strSQL .= "AND  LINE_NO=" . $postSelLineNo . "\r\n";
        $strSQL = str_replace("@KAMOK_CD", $postArr['KAMOK_CD'], $strSQL);
        $strSQL = str_replace("@HIMOK_CD", $postArr['HIMOK_CD'], $strSQL);

        return $strSQL;
    }

    public function select_kamoku($postArr, $postSelLineNo)
    {
        return parent::Fill($this->select_kamoku_sql($postArr, $postSelLineNo));
    }

    public function insert_kamoku_sql($postArr, $postSelLineNo, $UPD_SYA_CD, $UPD_CLT_NM)
    {
        $strSQL = "";
        //        $strSQL .= "INSERT INTO HKMKLINEMST_NEW " . "\r\n";
        $strSQL .= "INSERT INTO HKMKLINEMST_KEIEISEIKA " . "\r\n";
        $strSQL .= "( LINE_NO " . "\r\n";
        $strSQL .= ", KAMOK_CD " . "\r\n";
        $strSQL .= ", HIMOK_CD " . "\r\n";
        $strSQL .= ", CAL_KB " . "\r\n";
        $strSQL .= ", CREATE_DATE " . "\r\n";
        $strSQL .= ", UPD_DATE " . "\r\n";
        $strSQL .= ", UPD_SYA_CD " . "\r\n";
        $strSQL .= ", UPD_PRG_ID " . "\r\n";
        $strSQL .= ", UPD_CLT_NM " . "\r\n";
        $strSQL .= ") " . "\r\n";
        $strSQL .= "VALUES " . "\r\n";
        $strSQL .= "( " . $postSelLineNo . "\r\n";
        $strSQL .= ", '" . $postArr['KAMOK_CD'] . "'" . "\r\n";
        $strSQL .= ", '" . $postArr['HIMOK_CD'] . "'" . "\r\n";
        $strSQL .= "," . $this->FncSqlNz2($postArr['CAL_KB1']) . "\r\n";
        $strSQL .= ", SYSDATE" . "\r\n";
        $strSQL .= ", SYSDATE" . "\r\n";
        $strSQL .= ", '" . $UPD_SYA_CD . "'" . "\r\n";
        $strSQL .= ", '" . $this->UPD_PRG_ID . "'" . "\r\n";
        $strSQL .= ", '" . $UPD_CLT_NM . "'" . "\r\n";
        $strSQL .= ")" . "\r\n";

        return $strSQL;
    }

    public function insert_kamoku($postArr, $postSelLineNo, $UPD_SYA_CD, $UPD_CLT_NM)
    {
        return parent::Do_Execute($this->insert_kamoku_sql($postArr, $postSelLineNo, $UPD_SYA_CD, $UPD_CLT_NM));
    }

    public function update_kamoku_sql($postArr, $postSelLineNo, $UPD_SYA_CD, $UPD_CLT_NM)
    {
        $strSQL = "";
        //        $strSQL .= "UPDATE HKMKLINEMST_NEW " . "\r\n";
        $strSQL .= "UPDATE HKMKLINEMST_KEIEISEIKA " . "\r\n";
        $strSQL .= "SET " . "\r\n";
        $strSQL .= "CAL_KB = " . $this->FncSqlNz2($postArr['CAL_KB1']) . "\r\n";
        $strSQL .= ", UPD_DATE = SYSDATE" . "\r\n";
        $strSQL .= ", UPD_SYA_CD = '" . $UPD_SYA_CD . "'" . "\r\n";
        $strSQL .= ", UPD_PRG_ID = '" . $this->UPD_PRG_ID . "'" . "\r\n";
        $strSQL .= ", UPD_CLT_NM = '" . $UPD_CLT_NM . "'" . "\r\n";
        $strSQL .= "WHERE  KAMOK_CD='@KAMOK_CD'" . "\r\n";
        $strSQL .= "AND  HIMOK_CD='@HIMOK_CD'" . "\r\n";
        $strSQL .= "AND  LINE_NO=" . $postSelLineNo . "\r\n";
        $strSQL = str_replace("@KAMOK_CD", $postArr['KAMOK_CD'], $strSQL);
        $strSQL = str_replace("@HIMOK_CD", $postArr['HIMOK_CD'], $strSQL);

        return $strSQL;
    }

    public function update_kamoku($postArr, $postSelLineNo, $UPD_SYA_CD, $UPD_CLT_NM)
    {
        return parent::Do_Execute($this->update_kamoku_sql($postArr, $postSelLineNo, $UPD_SYA_CD, $UPD_CLT_NM));
    }

    public function selectkamokmaster_sql()
    {
        $strSQL = "";
        $strSQL .= "SELECT KAMOK_CD " . "\r\n";
        $strSQL .= ",      KOMOK_CD" . "\r\n";
        $strSQL .= "FROM   M_KAMOKU" . "\r\n";
        return $strSQL;
    }

    public function selectkamokmaster()
    {
        return parent::select($this->selectkamokmaster_sql());
    }

    public function select_line_all()
    {
        return parent::Fill($this->select_line_new_sql());
    }

    public function delete_notexist_line_sql($post_line)
    {
        $strSQL = "";
        $strSQL .= "DELETE " . "\r\n";
        //        $strSQL .= "FROM   HLINEMST_NEW" . "\r\n";
        $strSQL .= "FROM   HLINEMST_KEIEISEIKA " . "\r\n";
        $strSQL .= "WHERE " . "\r\n";
        $strSQL .= " LINE_NO='@LINE_NO' " . "\r\n";
        $strSQL = str_replace("@LINE_NO", $post_line, $strSQL);
        return $strSQL;
    }

    public function delete_notexist_line($post_line)
    {
        return parent::Do_Execute($this->delete_notexist_line_sql($post_line));
    }

    public function select_kamoku_all($postLine)
    {
        return parent::Fill($this->showkamokusql($postLine));
    }

    public function delete_notexist_kamoku_sql($postArr)
    {
        $strSQL = "";
        $strSQL .= "DELETE " . "\r\n";
        //        $strSQL .= "FROM   HKMKLINEMST_NEW" . "\r\n";
        $strSQL .= "FROM   HKMKLINEMST_KEIEISEIKA " . "\r\n";
        $strSQL .= "WHERE " . "\r\n";
        $strSQL .= "  LINE_NO='@LINE_NO' " . "\r\n";
        $strSQL .= "AND " . "\r\n";
        $strSQL .= "  KAMOK_CD='@KAMOK_CD' " . "\r\n";
        $strSQL .= "AND " . "\r\n";
        $strSQL .= "  HIMOK_CD='@HIMOK_CD' " . "\r\n";
        $strSQL = str_replace("@LINE_NO", $postArr['LINE_NO'], $strSQL);
        $strSQL = str_replace("@KAMOK_CD", $postArr['KAMOK_CD'], $strSQL);
        $strSQL = str_replace("@HIMOK_CD", $postArr['HIMOK_CD'], $strSQL);
        return $strSQL;
    }

    public function delete_notexist_kamoku($postArr)
    {
        return parent::Do_Execute($this->delete_notexist_kamoku_sql($postArr));
    }

    // **********************************************************************
    // '処 理 名：Null変換関数(数値)
    // '関 数 名：FncSqlNz2
    // '引    数：objValue     (I)文字列
    // '　　　　：objReturn    (I)NULL変換後の値
    // '戻 り 値：変換後の値
    // '処理説明：Null変換(文字)を行う。
    // '**********************************************************************
    public function FncSqlNz2($objValue, $objReturn = "")
    {
        //---NULLの場合---
        if ($objValue == null) {
            if ($objReturn != "") {
                return $objReturn;
            } else {
                return "Null";
            }
        }
        //---以外の場合---
        else {
            if ($objValue == "") {
                return "Null";
            } else {
                return $objValue;
            }
        }
    }

}
