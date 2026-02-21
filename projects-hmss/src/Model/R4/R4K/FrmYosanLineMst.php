<?php
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

class FrmYosanLineMst extends ClsComDb
{
    // '**********************************************************************
    // '処 理 名：基本情報を抽出する
    // '関 数 名：fncYosanLineMstSelSQL
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：基本情報を抽出する
    // '**********************************************************************
    function fncYosanLineMstSelSQL($BUSYO_KB)
    {
        $strSQL = "";

        $strSQL .= "SELECT BUSYO_KB";
        $strSQL .= ",      LINE_NO";
        $strSQL .= ",      IDX_LINE_NO";
        $strSQL .= ",      IDX_CAL_KB";
        $strSQL .= ",      IDX_RND_POS";
        $strSQL .= ",      KO_TARGET_KB";
        $strSQL .= ",   to_char(CREATE_DATE,'YYYY/MM/DD HH24:MI:SS')  AS CREATE_DATE";
        $strSQL .= "  FROM   HYOSANLINEMST";

        if (rtrim($BUSYO_KB) != "") {
            $strSQL .= "  WHERE BUSYO_KB = '@BUSYOKB'";
        }

        $strSQL .= "  ORDER BY BUSYO_KB, LINE_NO";
        $strSQL = str_replace("@BUSYOKB", rtrim($BUSYO_KB), $strSQL);

        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：選択行を削除する
    // '関 数 名：fncHYOSANLINEMSTDeleteRowSQL
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：選択行を削除する
    // '**********************************************************************
    function fncHYOSANLINEMSTDeleteRowSQL($BUSYO_KB, $LINE_NO)
    {
        $strSQL = "";

        $strSQL .= "DELETE FROM HYOSANLINEMST WHERE BUSYO_KB = '";
        $strSQL .= $BUSYO_KB . "'";
        $strSQL .= " AND   LINE_NO = '";
        $strSQL .= $LINE_NO . "'";

        return $strSQL;
    }

    function checkExistDataSQL($inputDatas, $l)
    {
        $strSQL = "";
        $strSQL = "SELECT BUSYO_KB, LINE_NO FROM HYOSANLINEMST WHERE BUSYO_KB = '@BUSYOKB'";
        $strSQL = str_replace("@BUSYOKB", rtrim($inputDatas[$l]['BUSYO_KB']), $strSQL);

        $inSQL = "";
        $inSQL = "  AND LINE_NO IN (";
        $inSQL .= "'" . $inputDatas[$l]['LINE_NO'] . "'";

        $strSaveBusyoKB = $inputDatas[$l]['BUSYO_KB'];

        $k = 0;
        $l += 1;

        //IN述語は255までしか対応してないため
        while ($l < count($inputDatas) && $k < 255) {
            if ($strSaveBusyoKB == $inputDatas[$l]['BUSYO_KB']) {
                $inSQL .= ", '" . $inputDatas[$l]['LINE_NO'] . "'";
                $l += 1;
                $k += 1;
            } else {
                break;
            }
        }

        $strSQL = $strSQL . $inSQL . ")";

        $result["strSQL"] = $strSQL;
        $result["rowNum"] = $l;

        return $result;
    }

    //'**********************************************************************
    // '処 理 名：削除する
    // '関 数 名：fncDeleteYosanLineMst
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：削除する
    // '**********************************************************************
    function fncDeleteYosanLineMstSQL($BUSYO_KB)
    {
        $strSQL = "";

        $strSQL = "DELETE FROM HYOSANLINEMST";

        if ($BUSYO_KB != "") {
            $strSQL .= "  WHERE   BUSYO_KB = '@BUSYOKB'";
        }

        $strSQL = str_replace("@BUSYOKB", $BUSYO_KB, $strSQL);

        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：マスタに追加する
    // '関 数 名：fncInsertListSyasuMst
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：マスタに追加する
    // '**********************************************************************
    function fncInsertYosanLineMstSQL($inputData)
    {
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDCLTNM = $this->GS_LOGINUSER['strClientNM'];

        $inputData['BUSYO_KB'] = $this->FncSqlNv2(rtrim($inputData['BUSYO_KB']), "", 1);
        $inputData['LINE_NO'] = $this->FncSqlNv2(rtrim($inputData['LINE_NO']), "", 2);
        $inputData['IDX_LINE_NO'] = $this->FncSqlNv2(rtrim($inputData['IDX_LINE_NO']), "", 2);
        $inputData['IDX_CAL_KB'] = $this->FncSqlNv2(rtrim($inputData['IDX_CAL_KB']), "", 2);
        $inputData['IDX_RND_POS'] = $this->FncSqlNv2(rtrim($inputData['IDX_RND_POS']), "", 2);
        $inputData['KO_TARGET_KB'] = $this->FncSqlNv2(rtrim($inputData['KO_TARGET_KB']), "", 1);
        $inputData['CREATE_DATE'] = rtrim($inputData['CREATE_DATE']) != "" ? "TO_DATE(" . $this->FncSqlNv2(rtrim($inputData['CREATE_DATE']), "", 1) . ",'YYYY/MM/DD HH24:MI:SS')" : "SYSDATE";

        $strSQL = "";

        $strSQL .= "INSERT INTO HYOSANLINEMST";
        $strSQL .= "(      BUSYO_KB";
        $strSQL .= ",      LINE_NO";
        $strSQL .= ",      IDX_LINE_NO";
        $strSQL .= ",      IDX_CAL_KB";
        $strSQL .= ",      IDX_RND_POS";
        $strSQL .= ",      KO_TARGET_KB";
        $strSQL .= ",      UPD_DATE";
        $strSQL .= ",      CREATE_DATE";
        $strSQL .= ",      UPD_SYA_CD";
        $strSQL .= ",      UPD_PRG_ID";
        $strSQL .= ",      UPD_CLT_NM";

        $strSQL .= ") VALUES ( ";

        $strSQL .= $inputData['BUSYO_KB'];
        $strSQL .= " ," . $inputData['LINE_NO'];
        $strSQL .= " ," . $inputData['IDX_LINE_NO'];
        $strSQL .= " ," . $inputData['IDX_CAL_KB'];
        $strSQL .= " ," . $inputData['IDX_RND_POS'];
        $strSQL .= " ," . $inputData['KO_TARGET_KB'];
        $strSQL .= " , SYSDATE";
        $strSQL .= " , " . $inputData['CREATE_DATE'];
        $strSQL .= " , '" . $UPDUSER . "'";
        $strSQL .= " ,'YosanLineMst'";
        $strSQL .= " , '" . $UPDCLTNM . "'";
        $strSQL .= ")";

        return $strSQL;
    }

    public function fncYosanLineMstSel($BUSYO_KB)
    {
        $strsql = $this->fncYosanLineMstSelSQL($BUSYO_KB);
        return parent::select($strsql);
    }

    public function fncHYOSANLINEMSTDeleteRow($BUSYO_KB, $LINE_NO)
    {
        $strsql = $this->fncHYOSANLINEMSTDeleteRowSQL($BUSYO_KB, $LINE_NO);
        return parent::delete($strsql);
    }

    public function checkExistData($inputDatas, $intSaveRowCnt)
    {
        $result = array(
            "result" => TRUE,
            "data" => "",
            "rowNo" => -1
        );

        //重複チェック２ 　新規追加分に重複データがないかチェック
        $l = $intSaveRowCnt;

        while ($l < count($inputDatas)) {
            $strArray = $this->checkExistDataSQL($inputDatas, $l);
            $l = $strArray["rowNum"];

            $result = parent::select($strArray["strSQL"]);

            if (count((array) $result['data']) != 0) {
                $result["rowNo"] = $l;
                return $result;
            }
        }

        return $result;
    }

    public function fncDeleteYosanLineMst($BUSYO_KB)
    {
        $strsql = $this->fncDeleteYosanLineMstSQL($BUSYO_KB);
        return parent::Do_Execute($strsql);
    }

    public function fncInsertYosanLineMst($inputData)
    {
        $strsql = $this->fncInsertYosanLineMstSQL($inputData);
        return parent::Do_Execute($strsql);
    }

    // '**********************************************************************
    // '処 理 名：Null変換関数(文字)
    // '関 数 名：FncNv
    // '引    数：objValue     (I)文字列
    // '　　　　：objReturn    (I)NULL変換後の値
    // '戻 り 値：変換後の値
    // '処理説明：Null変換(文字)を行う。
    // '**********************************************************************
    function FncSqlNv2($objValue, $objReturn, $intKind)
    {
        //'---NULLの場合---
        if ($objValue === null) {
            if ($objReturn != "") {
                return $objReturn;
            } else {
                return "''";
            }
        } else {
            //'---以外の場合
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
