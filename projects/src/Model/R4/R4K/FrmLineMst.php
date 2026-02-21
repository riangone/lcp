<?php
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

class FrmLineMst extends ClsComDb
{
    // **********************************************************************
    // 処 理 名：ラインマスタのデータを抽出SQL
    // 関 数 名：fncLineMstSelectSQL
    // 引    数：	無し
    // 戻 り 値：	$SQL文字列
    // 処理説明：ラインマスタのデータを抽出する
    // **********************************************************************
    function fncLineMstSelectSQL()
    {
        $strSQL = "";

        $strSQL = "SELECT  ";
        $strSQL .= "    LINE_NO  ";
        $strSQL .= ",   ITEM_NM ";
        $strSQL .= ",   TANI  ";
        $strSQL .= ",   RND_KB  ";
        $strSQL .= ",   RND_POS  ";
        $strSQL .= ",   CAL_KB  ";
        $strSQL .= ",   DISP_KB  ";
        $strSQL .= ",   IDX_NM  ";
        $strSQL .= ",   IDX_LINE_NO  ";
        $strSQL .= ",   IDX_CAL_KB  ";
        $strSQL .= ",   IDX_TANI  ";
        $strSQL .= ",   IDX_RND_KB  ";
        $strSQL .= ",   IDX_RND_POS  ";
        $strSQL .= ",   SONEK_PRN_FLG  ";
        $strSQL .= ",   to_char(CREATE_DATE,'YYYY/MM/DD HH24:MI:SS')  AS CREATE_DATE";

        $strSQL .= "    FROM   HLINEMST";
        $strSQL .= "    ORDER BY LINE_NO ASC";
        return $strSQL;
    }

    // **********************************************************************
    // 処 理 名：ラインマスタのデータを削除SQL
    // 関 数 名：fncDeleteLineMst
    // 引    数：	無し
    // 戻 り 値：	$SQL文字列
    // 処理説明：ラインマスタのデータを削除SQL
    // **********************************************************************
    function fncDeleteLineMst()
    {
        $strSQL = "";
        $strSQL = "DELETE FROM HLINEMST";
        return $strSQL;
    }

    // **********************************************************************
    // 処 理 名：ラインマスタに追加するためのSQL
    // 関 数 名：fncInsertLineMst
    // 引    数：	$入力データ配列
    // 戻 り 値：	$SQL文字列
    // 処理説明：ラインマスタに追加するためのSQL
    // **********************************************************************
    function fncInsertLineMst($strInsertData)
    {
        $strSQL = "";
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDCLTNM = $this->GS_LOGINUSER['strClientNM'];
        $strInsertData['LINE_NO'] = $this->FncSqlNv2(is_string($strInsertData['LINE_NO']) ? rtrim($strInsertData['LINE_NO']) : '', "", 2);
        $strInsertData['ITEM_NM'] = $this->FncSqlNv2(is_string($strInsertData['ITEM_NM']) ? rtrim($strInsertData['ITEM_NM']) : '', "", 1);
        $strInsertData['TANI'] = $this->FncSqlNv2(is_string($strInsertData['TANI']) ? rtrim($strInsertData['TANI']) : '', "", 1);
        $strInsertData['RND_KB'] = $this->FncSqlNv2(is_string($strInsertData['RND_KB']) ? rtrim($strInsertData['RND_KB']) : '', "", 1);
        $strInsertData['RND_POS'] = $this->FncSqlNv2(is_string($strInsertData['RND_POS']) ? rtrim($strInsertData['RND_POS']) : '', "", 2);
        $strInsertData['CAL_KB'] = $this->FncSqlNv2(is_string($strInsertData['CAL_KB']) ? rtrim($strInsertData['CAL_KB']) : '', "", 2);
        $strInsertData['DISP_KB'] = $this->FncSqlNv2(is_string($strInsertData['DISP_KB']) ? rtrim($strInsertData['DISP_KB']) : '', "", 1);
        $strInsertData['IDX_NM'] = $this->FncSqlNv2(is_string($strInsertData['IDX_NM']) ? rtrim($strInsertData['IDX_NM']) : '', "", 1);
        $strInsertData['IDX_LINE_NO'] = $this->FncSqlNv2(is_string($strInsertData['IDX_LINE_NO']) ? rtrim($strInsertData['IDX_LINE_NO']) : '', "", 2);
        $strInsertData['IDX_CAL_KB'] = $this->FncSqlNv2(is_string($strInsertData['IDX_CAL_KB']) ? rtrim($strInsertData['IDX_CAL_KB']) : '', "", 1);
        $strInsertData['IDX_TANI'] = $this->FncSqlNv2(is_string($strInsertData['IDX_TANI']) ? rtrim($strInsertData['IDX_TANI']) : '', "", 1);
        $strInsertData['IDX_RND_KB'] = $this->FncSqlNv2(is_string($strInsertData['IDX_RND_KB']) ? rtrim($strInsertData['IDX_RND_KB']) : '', "", 1);
        $strInsertData['IDX_RND_POS'] = $this->FncSqlNv2(is_string($strInsertData['IDX_RND_POS']) ? rtrim($strInsertData['IDX_RND_POS']) : '', "", 2);
        $strInsertData['SONEK_PRN_FLG'] = $this->FncSqlNv2(is_string($strInsertData['SONEK_PRN_FLG']) ? rtrim($strInsertData['SONEK_PRN_FLG']) : '', "", 1);
        $strInsertData['CREATE_DATE'] = (is_string($strInsertData['CREATE_DATE']) ? rtrim($strInsertData['CREATE_DATE']) : "") != "" ? "TO_DATE(" . $this->FncSqlNv2(is_string($strInsertData['CREATE_DATE']) ? rtrim($strInsertData['CREATE_DATE']) : '', "", 1) . ",'YYYY/MM/DD HH24:MI:SS')" : "SYSDATE";
        $strSQL = "INSERT INTO HLINEMST";
        $strSQL .= "(      LINE_NO";
        $strSQL .= ",      ITEM_NM";
        $strSQL .= ",      TANI";
        $strSQL .= ",      RND_KB";
        $strSQL .= ",      RND_POS";
        $strSQL .= ",      CAL_KB";
        $strSQL .= ",      DISP_KB";
        $strSQL .= ",      IDX_NM";
        $strSQL .= ",      IDX_LINE_NO";
        $strSQL .= ",      IDX_CAL_KB";
        $strSQL .= ",      IDX_TANI";
        $strSQL .= ",      IDX_RND_KB";
        $strSQL .= ",      IDX_RND_POS";
        $strSQL .= ",      SONEK_PRN_FLG";
        $strSQL .= ",      UPD_DATE";
        $strSQL .= ",      CREATE_DATE";
        $strSQL .= ",      UPD_SYA_CD";
        $strSQL .= ",      UPD_PRG_ID";
        $strSQL .= ",      UPD_CLT_NM";
        $strSQL .= ") VALUES ( ";
        $strSQL .= " " . $strInsertData['LINE_NO'];
        $strSQL .= " , " . $strInsertData['ITEM_NM'];
        $strSQL .= " , " . $strInsertData['TANI'];
        $strSQL .= " , " . $strInsertData['RND_KB'];
        $strSQL .= " , " . $strInsertData['RND_POS'];
        $strSQL .= " , " . $strInsertData['CAL_KB'];
        $strSQL .= " , " . $strInsertData['DISP_KB'];
        $strSQL .= " , " . $strInsertData['IDX_NM'];
        $strSQL .= " , " . $strInsertData['IDX_LINE_NO'];
        $strSQL .= " , " . $strInsertData['IDX_CAL_KB'];
        $strSQL .= " , " . $strInsertData['IDX_TANI'];
        $strSQL .= " , " . $strInsertData['IDX_RND_KB'];
        $strSQL .= " , " . $strInsertData['IDX_RND_POS'];
        $strSQL .= " , " . $strInsertData['SONEK_PRN_FLG'];
        $strSQL .= ", SYSDATE";
        $strSQL .= " , " . $strInsertData['CREATE_DATE'];
        $strSQL .= ", '" . $UPDUSER . "'";
        $strSQL .= ", 'LineMst'";
        $strSQL .= ", '" . $UPDCLTNM . "'";
        $strSQL .= " )";

        return $strSQL;
    }

    // **********************************************************************
    // 処 理 名：ラインマスタに削除行ためのSQL
    // 関 数 名：fncDeleteRowSQL
    // 引    数：	$選択行データ
    // 戻 り 値：	$SQL文字列
    // 処理説明：ラインマスタに削除行ためのSQL
    // **********************************************************************
    function fncDeleteRowSQL($strDeleteData)
    {
        $strSQL = "";
        $strSQL = "DELETE FROM HLINEMST WHERE LINE_NO = " . $strDeleteData;
        return $strSQL;
    }

    // **********************************************************************
    // 処 理 名：SQL-Select実行
    // 関 数 名：fncSelect
    // 引    数：	無し
    // 戻 り 値：	SQL実行の結果
    // 処理説明：SQL-Select実行
    // **********************************************************************
    public function fncSelectLineMst()
    {
        return parent::select($this->fncLineMstSelectSQL());
    }

    // **********************************************************************
    // 処 理 名：SQL-Delete実行
    // 関 数 名：fncLineMstDelete
    // 引    数：	無し
    // 戻 り 値：	SQL実行の結果
    // 処理説明：SQL-Delete実行
    // **********************************************************************
    public function fncLineMstDelete()
    {
        return parent::Do_Execute($this->fncDeleteLineMst());
    }

    // **********************************************************************
    // 処 理 名：SQL-Insert実行
    // 関 数 名：fncLineMstInsert
    // 引    数：	$入力データ配列
    // 戻 り 値：	SQL実行の結果
    // 処理説明：SQL-Insert実行
    // **********************************************************************
    public function fncLineMstInsert($strInsertData)
    {
        return parent::Do_Execute($this->fncInsertLineMst($strInsertData));
    }

    // **********************************************************************
    // 処 理 名：選択行データ削除
    // 関 数 名：fncDeleteRow
    // 引    数：	$選択行データ
    // 戻 り 値：	SQL実行の結果
    // 処理説明：選択行データ削除
    // **********************************************************************
    public function fncDeleteRow($strDeleteData)
    {
        return parent::delete($this->fncDeleteRowSQL($strDeleteData));
    }

    // **********************************************************************
    // 処 理 名：Null変換関数(文字)
    // 関 数 名：FncSqlNv2
    // 引    数：	objValue			(I)文字列
    //                bjReturn			(I)NULL変換後の値					default値：""
    //                intKind			(I)類型No.	                 				default値：1
    // 戻 り 値：	変換後の値
    // 処理説明：Null変換関数(文字)
    // **********************************************************************
    function FncSqlNv2($objValue, $objReturn, $intKind)
    {
        if ($objValue === null) {
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
