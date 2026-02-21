<?php
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;
use App\Model\JKSYS\Component\ClsComFncJKSYS;

//*************************************
// * 処理名	：FrmExcelTorikomi
// * 関数名	：FrmExcelTorikomi
// * 処理説明	：共通クラスの読込み
//*************************************
class FrmExcelTorikomi extends ClsComDb
{
    public $ClsComFncJKSYS;

    // * 処理名	：fncSelectMaxListMeisaiNo
    // * 関数名	：fncSelectMaxListMeisaiNo
    // * 処理説明	：SELECT文を返す（リスト明細ＮＯの最大値取得）
    public function fncSelectMaxListMeisaiNo($vTableNm)
    {
        $strSql = $this->fncSelectMaxListMeisaiNoSQL($vTableNm);
        return parent::select($strSql);
    }

    // * 処理名	：fncSelectMaxListMeisaiNoSQL
    // * 関数名	：fncSelectMaxListMeisaiNoSQL
    // * 処理説明	：SELECT文を返す（リスト明細ＮＯの最大値取得）SQL
    public function fncSelectMaxListMeisaiNoSQL($vTableNm)
    {
        $strSQL = "";
        $strSQL = "SELECT  ";
        $strSQL .= " MAX(NVL(LIST_MEISAI_NO,0)) as MAXNO " . "\r\n";
        $strSQL .= " FROM  " . "\r\n";
        $strSQL .= " @TABLENAME ";
        $strSQL = str_replace('@TABLENAME', $vTableNm, $strSQL);
        return $strSQL;
    }

    // * 処理名	：fncSelectMeisaiExist
    // * 関数名	：fncSelectMeisaiExist
    // * 処理説明	：SELECT文を返す（同一年月データ存在チェック）
    public function fncSelectMeisaiExist($vYYYYMM, $vTableNm)
    {
        $strSql = $this->fncSelectMeisaiExistSQL($vYYYYMM, $vTableNm);
        return parent::select($strSql);
    }

    // * 処理名	：fncSelectMeisaiExistSQL
    // * 関数名	：fncSelectMeisaiExistSQL
    // * 処理説明	：SELECT文を返す（同一年月データ存在チェック）
    public function fncSelectMeisaiExistSQL($vYYYYMM, $vTableNm)
    {
        $strSQL = "";
        $strSQL = " SELECT  " . "\r\n";
        $strSQL .= "  * " . "\r\n";
        $strSQL .= " FROM " . "\r\n";
        $strSQL .= "  @TABLENAME " . "\r\n";
        $strSQL .= " WHERE " . "\r\n";
        $strSQL .= "  VALUE1 = '@YYYYMM' " . "\r\n";
        $strSQL = str_replace('@TABLENAME', $vTableNm, $strSQL);
        $strSQL = str_replace('@YYYYMM', $vYYYYMM, $strSQL);
        return $strSQL;
    }

    // * 処理名	：fncDeleteMeisaiExist
    // * 関数名	：fncDeleteMeisaiExist
    // * 処理説明	：DELETE文を返す（同一年月データ存在チェック）
    public function fncDeleteMeisaiExist($vYYYYMM, $vTableNm)
    {
        $strSql = $this->fncDeleteMeisaiExistSQL($vYYYYMM, $vTableNm);
        return parent::delete($strSql);
    }

    // * 処理名	：fncDeleteMeisaiExistSQL
    // * 関数名	：fncDeleteMeisaiExistSQL
    // * 処理説明	：DELETE文を返す（同一年月データ存在チェック）SQL
    public function fncDeleteMeisaiExistSQL($vYYYYMM, $vTableNm)
    {
        $strSQL = "";
        $strSQL = " DELETE  " . "\r\n";
        $strSQL .= " FROM " . "\r\n";
        $strSQL .= "  @TABLENAME " . "\r\n";
        $strSQL .= " WHERE " . "\r\n";
        $strSQL .= "  VALUE1 = '@YYYYMM'" . "\r\n";
        $strSQL = str_replace('@TABLENAME', $vTableNm, $strSQL);
        $strSQL = str_replace('@YYYYMM', $vYYYYMM, $strSQL);
        return $strSQL;
    }

    // * 処理名	：InsertData
    // * 関数名	：InsertData
    // * 処理説明	：DB新規追加
    public function InsertData($vMode, $vYYYYMM, $vTableNm, $vAry)
    {
        $strSql = $this->InsertDataSQL($vMode, $vYYYYMM, $vTableNm, $vAry);
        return parent::insert($strSql);
    }

    //指定された文字でパディング
    //<param name="target">対象となる文字列</param>
    //<param name="padChar">パディングする文字列</param>
    public function fncPadding($target, $padChar, $length)
    {
        $wkStr = $target;
        for ($i = 0; $i < $length; $i++) {
            $wkStr = $padChar . $wkStr;
        }
        $wkStr = substr($wkStr, strlen($wkStr) - $length, $length);
        return $wkStr;
    }

    // * 処理名	：InsertDataSQL
    // * 関数名	：InsertDataSQL
    // * 処理説明	：DB新規追加SQL
    public function InsertDataSQL($vMode, $vYYYYMM, $vTableNm, $vAry)
    {
        ini_set('precision', 14);
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();
        $strSQL = "";
        $strSQL = "Insert into @TABLENM " . "\r\n";
        $strSQL .= "( " . "\r\n";
        $strSQL .= " LIST_MEISAI_NO " . "\r\n";
        $strSQL .= " ,VALUE1 " . "\r\n";
        $strSQL .= " ,VALUE2 " . "\r\n";
        $strSQL .= " ,VALUE3 " . "\r\n";
        $strSQL .= " ,VALUE4 " . "\r\n";
        $strSQL .= " ,VALUE5 " . "\r\n";
        $strSQL .= " ,VALUE6 " . "\r\n";
        $strSQL .= " ,VALUE7 " . "\r\n";
        $strSQL .= " ,VALUE8 " . "\r\n";
        $strSQL .= " ,VALUE9 " . "\r\n";
        $strSQL .= " ,VALUE10 " . "\r\n";
        $strSQL .= " ,VALUE11 " . "\r\n";
        $strSQL .= " ,VALUE12 " . "\r\n";
        $strSQL .= " ,VALUE13 " . "\r\n";
        $strSQL .= " ,VALUE14 " . "\r\n";
        $strSQL .= " ,VALUE15 " . "\r\n";
        $strSQL .= " ,VALUE16 " . "\r\n";
        $strSQL .= " ,VALUE17 " . "\r\n";
        $strSQL .= " ,VALUE18 " . "\r\n";
        $strSQL .= " ,VALUE19 " . "\r\n";
        $strSQL .= " ,VALUE20 " . "\r\n";
        $strSQL .= " ,CRE_BUSYO_CD " . "\r\n";
        $strSQL .= " ,CRE_SYA_CD " . "\r\n";
        $strSQL .= " ,CRE_CLT_NM " . "\r\n";
        $strSQL .= " ,CRE_DATE " . "\r\n";
        $strSQL .= " ,UPD_BUSYO_CD " . "\r\n";
        $strSQL .= " ,UPD_SYA_CD " . "\r\n";
        $strSQL .= " ,UPD_CLT_NM " . "\r\n";
        $strSQL .= " ,UPD_DATE " . "\r\n";
        $strSQL .= " ,UPD_PRG_ID " . "\r\n";
        $strSQL .= ")  " . "\r\n";
        $strSQL .= "Values( " . "\r\n";
        $strSQL .= "  @LIST_MEISAI_NO " . "\r\n";
        $strSQL .= " ,'@VALUE01' " . "\r\n";
        $strSQL .= " ,'@VALUE02' " . "\r\n";
        $strSQL .= " ,'@VALUE03' " . "\r\n";
        $strSQL .= " ,'@VALUE04' " . "\r\n";
        $strSQL .= " ,'@VALUE05' " . "\r\n";
        $strSQL .= " ,'@VALUE06' " . "\r\n";
        $strSQL .= " ,'@VALUE07' " . "\r\n";
        $strSQL .= " ,'@VALUE08' " . "\r\n";
        $strSQL .= " ,'@VALUE09' " . "\r\n";
        $strSQL .= " ,'@VALUE10' " . "\r\n";
        $strSQL .= " ,'@VALUE11' " . "\r\n";
        $strSQL .= " ,'@VALUE12' " . "\r\n";
        $strSQL .= " ,'@VALUE13' " . "\r\n";
        $strSQL .= " ,'@VALUE14' " . "\r\n";
        $strSQL .= " ,'@VALUE15' " . "\r\n";
        $strSQL .= " ,'@VALUE16' " . "\r\n";
        $strSQL .= " ,'@VALUE17' " . "\r\n";
        $strSQL .= " ,'@VALUE18' " . "\r\n";
        $strSQL .= " ,'@VALUE19' " . "\r\n";
        $strSQL .= " ,'@VALUE20' " . "\r\n";
        $strSQL .= " ,@BUSYO " . "\r\n";
        $strSQL .= " ,@USERID " . "\r\n";
        $strSQL .= " ,@CLIENT " . "\r\n";
        $strSQL .= " ,SYSDATE " . "\r\n";
        $strSQL .= " ,@BUSYO " . "\r\n";
        $strSQL .= " ,@USERID " . "\r\n";
        $strSQL .= " ,@CLIENT " . "\r\n";
        $strSQL .= " ,SYSDATE " . "\r\n";
        $strSQL .= " ,@PRG_ID " . "\r\n";
        $strSQL .= ") ";
        switch ($vMode) {
            case '2': //JAF件数
                //年月
                $strSQL = str_replace("@VALUE01", $vYYYYMM, $strSQL);
                //部署コード
                $strSQL = str_replace("@VALUE02", $this->fncPadding($vAry[1], "0", 3), $strSQL);
                //部署名
                $strSQL = str_replace("@VALUE03", $vAry[2], $strSQL);
                //社員番号
                $strSQL = str_replace("@VALUE04", $this->fncPadding($vAry[3], "0", 5), $strSQL);
                //社員名
                $strSQL = str_replace("@VALUE05", $vAry[4], $strSQL);
                //新規契約件数
                $strSQL = str_replace("@VALUE06", $vAry[5], $strSQL);
                $strSQL = str_replace("@VALUE07", $vAry[6], $strSQL);
                $strSQL = str_replace("@VALUE08", $vAry[7], $strSQL);
                $strSQL = str_replace("@VALUE09", $vAry[8], $strSQL);
                $strSQL = str_replace("@VALUE10", $vAry[9], $strSQL);
                $strSQL = str_replace("@VALUE11", $vAry[10], $strSQL);
                $strSQL = str_replace("@VALUE12", $vAry[11], $strSQL);
                $strSQL = str_replace("@VALUE13", $vAry[12], $strSQL);
                $strSQL = str_replace("@VALUE14", $vAry[13], $strSQL);
                $strSQL = str_replace("@VALUE15", $vAry[14], $strSQL);
                $strSQL = str_replace("@VALUE16", $vAry[15], $strSQL);
                $strSQL = str_replace("@VALUE17", $vAry[16], $strSQL);
                $strSQL = str_replace("@VALUE18", $vAry[17], $strSQL);
                $strSQL = str_replace("@VALUE19", $vAry[18], $strSQL);
                $strSQL = str_replace("@VALUE20", $vAry[19], $strSQL);
                break;
            case '6': //パックDeメンテ
                //年月
                $strSQL = str_replace("@VALUE01", $vYYYYMM, $strSQL);
                //部署コード
                $strSQL = str_replace("@VALUE02", $this->fncPadding($vAry[1], "0", 3), $strSQL);
                //部署名
                $strSQL = str_replace("@VALUE03", $vAry[2], $strSQL);
                //社員番号
                $strSQL = str_replace("@VALUE04", $this->fncPadding($vAry[3], "0", 5), $strSQL);
                //社員名
                $strSQL = str_replace("@VALUE05", $vAry[4], $strSQL);
                //６０Ｐ＆５４Ｐ
                $strSQL = str_replace("@VALUE06", $vAry[5], $strSQL);
                //３６Ｐ
                $strSQL = str_replace("@VALUE07", $vAry[6], $strSQL);
                //３０Ｐ
                $strSQL = str_replace("@VALUE08", $vAry[7], $strSQL);
                //Ｓ１８Ｐ
                $strSQL = str_replace("@VALUE09", $vAry[8], $strSQL);
                //１８Ｓ
                $strSQL = str_replace("@VALUE10", $vAry[9], $strSQL);
                //１８Ｐ
                $strSQL = str_replace("@VALUE11", $vAry[10], $strSQL);
                //６Ｓ
                $strSQL = str_replace("@VALUE12", $vAry[11], $strSQL);
                //小計
                $strSQL = str_replace("@VALUE13", $vAry[12], $strSQL);
                $strSQL = str_replace("@VALUE14", $vAry[13], $strSQL);
                $strSQL = str_replace("@VALUE15", $vAry[14], $strSQL);
                $strSQL = str_replace("@VALUE16", $vAry[15], $strSQL);
                $strSQL = str_replace("@VALUE17", $vAry[16], $strSQL);
                $strSQL = str_replace("@VALUE18", $vAry[17], $strSQL);
                $strSQL = str_replace("@VALUE19", $vAry[18], $strSQL);
                $strSQL = str_replace("@VALUE20", $vAry[19], $strSQL);
                break;
            case '1': //（TMRH）リース_新規
                //年月
                $strSQL = str_replace("@VALUE01", $vYYYYMM, $strSQL);
                //部署コード
                $strSQL = str_replace("@VALUE02", $this->fncPadding($vAry[1], "0", 3), $strSQL);
                //部署名
                $strSQL = str_replace("@VALUE03", $vAry[2], $strSQL);
                //社員番号
                $strSQL = str_replace("@VALUE04", $this->fncPadding($vAry[3], "0", 5), $strSQL);
                //社員名
                $strSQL = str_replace("@VALUE05", $vAry[4], $strSQL);
                //件数
                $strSQL = str_replace("@VALUE06", $vAry[5], $strSQL);
                $strSQL = str_replace("@VALUE07", $vAry[6], $strSQL);
                $strSQL = str_replace("@VALUE08", $vAry[7], $strSQL);
                $strSQL = str_replace("@VALUE09", $vAry[8], $strSQL);
                $strSQL = str_replace("@VALUE10", $vAry[9], $strSQL);
                $strSQL = str_replace("@VALUE11", $vAry[10], $strSQL);
                $strSQL = str_replace("@VALUE12", $vAry[11], $strSQL);
                $strSQL = str_replace("@VALUE13", $vAry[12], $strSQL);
                $strSQL = str_replace("@VALUE14", $vAry[13], $strSQL);
                $strSQL = str_replace("@VALUE15", $vAry[14], $strSQL);
                $strSQL = str_replace("@VALUE16", $vAry[15], $strSQL);
                $strSQL = str_replace("@VALUE17", $vAry[16], $strSQL);
                $strSQL = str_replace("@VALUE18", $vAry[17], $strSQL);
                $strSQL = str_replace("@VALUE19", $vAry[18], $strSQL);
                $strSQL = str_replace("@VALUE20", $vAry[19], $strSQL);
                break;
            case '7': //（TMRH）リース_再リース
                //年月
                $strSQL = str_replace("@VALUE01", $vYYYYMM, $strSQL);
                //部署コード
                $strSQL = str_replace("@VALUE02", $this->fncPadding($vAry[1], "0", 3), $strSQL);
                //部署名
                $strSQL = str_replace("@VALUE03", $vAry[2], $strSQL);
                //社員番号
                $strSQL = str_replace("@VALUE04", $this->fncPadding($vAry[3], "0", 5), $strSQL);
                //社員名
                $strSQL = str_replace("@VALUE05", $vAry[4], $strSQL);
                //金額
                $strSQL = str_replace("@VALUE06", $vAry[5], $strSQL);
                $strSQL = str_replace("@VALUE07", $vAry[6], $strSQL);
                $strSQL = str_replace("@VALUE08", $vAry[7], $strSQL);
                $strSQL = str_replace("@VALUE09", $vAry[8], $strSQL);
                $strSQL = str_replace("@VALUE10", $vAry[9], $strSQL);
                $strSQL = str_replace("@VALUE11", $vAry[10], $strSQL);
                $strSQL = str_replace("@VALUE12", $vAry[11], $strSQL);
                $strSQL = str_replace("@VALUE13", $vAry[12], $strSQL);
                $strSQL = str_replace("@VALUE14", $vAry[13], $strSQL);
                $strSQL = str_replace("@VALUE15", $vAry[14], $strSQL);
                $strSQL = str_replace("@VALUE16", $vAry[15], $strSQL);
                $strSQL = str_replace("@VALUE17", $vAry[16], $strSQL);
                $strSQL = str_replace("@VALUE18", $vAry[17], $strSQL);
                $strSQL = str_replace("@VALUE19", $vAry[18], $strSQL);
                $strSQL = str_replace("@VALUE20", $vAry[19], $strSQL);
                break;
            case '10': //営業活動報告書
                //年月
                $strSQL = str_replace("@VALUE01", $vYYYYMM, $strSQL);
                //部署コード
                $strSQL = str_replace("@VALUE02", $this->fncPadding($vAry[1], "0", 3), $strSQL);
                //部署名
                $strSQL = str_replace("@VALUE03", $vAry[2], $strSQL);
                //社員番号
                $strSQL = str_replace("@VALUE04", $this->fncPadding($vAry[3], "0", 5), $strSQL);
                //社員名
                $strSQL = str_replace("@VALUE05", $vAry[4], $strSQL);
                //総限界利益_目標
                $strSQL = str_replace("@VALUE06", $vAry[5], $strSQL);
                //総限界利益_達成率
                $strSQL = str_replace("@VALUE07", $vAry[6], $strSQL);
                //台数または売上目標メイン
                $strSQL = str_replace("@VALUE08", round((float) $vAry[7], 5), $strSQL);
                //台数又は売上_目標_他チャン
                $strSQL = str_replace("@VALUE09", $vAry[8], $strSQL);
                //台数又は売上_目標_他チャン
                $strSQL = str_replace("@VALUE10", $vAry[9], $strSQL);
                //台数又は売上達成率
                $strSQL = str_replace("@VALUE11", $vAry[10], $strSQL);
                $strSQL = str_replace("@VALUE12", round((float) $vAry[11], 5), $strSQL);
                if ($vAry[12] == '1') {
                    $strSQL = str_replace("@VALUE13", "TRUE", $strSQL);
                } else {
                    $strSQL = str_replace("@VALUE13", "", $strSQL);
                }
                $strSQL = str_replace("@VALUE14", $vAry[13], $strSQL);
                $strSQL = str_replace("@VALUE15", $vAry[14], $strSQL);
                $strSQL = str_replace("@VALUE16", $vAry[15], $strSQL);
                $strSQL = str_replace("@VALUE17", $vAry[16], $strSQL);
                $strSQL = str_replace("@VALUE18", $vAry[17], $strSQL);
                $strSQL = str_replace("@VALUE19", $vAry[18], $strSQL);
                $strSQL = str_replace("@VALUE20", $vAry[19], $strSQL);
                break;
            case '4': //管理台数表
                //年月
                $strSQL = str_replace("@VALUE01", $vYYYYMM, $strSQL);
                //部署コード
                $strSQL = str_replace("@VALUE02", $this->fncPadding($vAry[1], "0", 3), $strSQL);
                //部署名
                $strSQL = str_replace("@VALUE03", $vAry[2], $strSQL);
                //社員番号
                $strSQL = str_replace("@VALUE04", $this->fncPadding($vAry[3], "0", 5), $strSQL);
                //社員名
                $strSQL = str_replace("@VALUE05", $vAry[4], $strSQL);
                //自己開拓
                $strSQL = str_replace("@VALUE06", $vAry[5], $strSQL);
                //自己開拓リース
                $strSQL = str_replace("@VALUE07", $vAry[6], $strSQL);
                //引継顧客
                $strSQL = str_replace("@VALUE08", $vAry[7], $strSQL);
                //引継顧客リース
                $strSQL = str_replace("@VALUE09", $vAry[8], $strSQL);
                //引継未管理
                $strSQL = str_replace("@VALUE10", $vAry[9], $strSQL);
                //業販
                $strSQL = str_replace("@VALUE11", $vAry[10], $strSQL);
                //業販未管理
                $strSQL = str_replace("@VALUE12", $vAry[11], $strSQL);
                //その他他契自登
                $strSQL = str_replace("@VALUE13", $vAry[12], $strSQL);
                //空白
                $strSQL = str_replace("@VALUE14", $vAry[13], $strSQL);
                //合計
                $strSQL = str_replace("@VALUE15", $vAry[14], $strSQL);
                //基盤管理台数
                $strSQL = str_replace("@VALUE16", $vAry[15], $strSQL);
                //軒数
                $strSQL = str_replace("@VALUE17", $vAry[16], $strSQL);
                //管理台数
                $strSQL = str_replace("@VALUE18", $vAry[17], $strSQL);
                //未管理台数
                $strSQL = str_replace("@VALUE19", $vAry[18], $strSQL);
                $strSQL = str_replace("@VALUE20", $vAry[19], $strSQL);
                //
                break;
            case '9': //サービス貢献度
                //年月
                $strSQL = str_replace("@VALUE01", $vYYYYMM, $strSQL);
                //社員番号
                $strSQL = str_replace("@VALUE02", $this->fncPadding($vAry[1], "0", 5), $strSQL);
                //部署名
                $strSQL = str_replace("@VALUE03", $vAry[2], $strSQL);
                //集計区分１
                $strSQL = str_replace("@VALUE04", $this->fncPadding($vAry[3], "0", 2), $strSQL);
                $strSQL = str_replace("@VALUE05", $vAry[4], $strSQL);
                //入庫区分
                $strSQL = str_replace("@VALUE06", $this->fncPadding($vAry[5], "0", 2), $strSQL);

                $strSQL = str_replace("@VALUE07", $vAry[6], $strSQL);
                $strSQL = str_replace("@VALUE08", $vAry[7], $strSQL);
                $strSQL = str_replace("@VALUE09", $vAry[8], $strSQL);
                $strSQL = str_replace("@VALUE10", $vAry[9], $strSQL);
                $strSQL = str_replace("@VALUE11", $vAry[10], $strSQL);
                $strSQL = str_replace("@VALUE12", $vAry[11], $strSQL);
                $strSQL = str_replace("@VALUE13", $vAry[12], $strSQL);
                $strSQL = str_replace("@VALUE14", $vAry[13], $strSQL);
                $strSQL = str_replace("@VALUE15", $vAry[14], $strSQL);
                $strSQL = str_replace("@VALUE16", $vAry[15], $strSQL);
                $strSQL = str_replace("@VALUE17", $vAry[16], $strSQL);
                $strSQL = str_replace("@VALUE18", $vAry[17], $strSQL);
                //部署コード)
                $strSQL = str_replace("@VALUE19", $this->fncPadding($vAry[18], "0", 3), $strSQL);
                $strSQL = str_replace("@VALUE20", $vAry[19], $strSQL);
                break;
            case '3': //人員
                //年月
                $strSQL = str_replace("@VALUE01", $vYYYYMM, $strSQL);
                //部署コード
                $strSQL = str_replace("@VALUE02", $this->fncPadding($vAry[1], "0", 3), $strSQL);
                //部署名
                $strSQL = str_replace("@VALUE03", $vAry[2], $strSQL);
                //人員
                $strSQL = str_replace("@VALUE04", $this->fncPadding($vAry[3], "0", 5), $strSQL);
                $strSQL = str_replace("@VALUE05", $vAry[4], $strSQL);
                $strSQL = str_replace("@VALUE06", $vAry[5], $strSQL);
                $strSQL = str_replace("@VALUE07", $vAry[6], $strSQL);
                $strSQL = str_replace("@VALUE08", $vAry[7], $strSQL);
                $strSQL = str_replace("@VALUE09", $vAry[8], $strSQL);
                $strSQL = str_replace("@VALUE10", $vAry[9], $strSQL);
                $strSQL = str_replace("@VALUE11", $vAry[10], $strSQL);
                $strSQL = str_replace("@VALUE12", $vAry[11], $strSQL);
                $strSQL = str_replace("@VALUE13", $vAry[12], $strSQL);
                $strSQL = str_replace("@VALUE14", $vAry[13], $strSQL);
                $strSQL = str_replace("@VALUE15", $vAry[14], $strSQL);
                $strSQL = str_replace("@VALUE16", $vAry[15], $strSQL);
                $strSQL = str_replace("@VALUE17", $vAry[16], $strSQL);
                $strSQL = str_replace("@VALUE18", $vAry[17], $strSQL);
                $strSQL = str_replace("@VALUE19", $vAry[18], $strSQL);
                $strSQL = str_replace("@VALUE20", $vAry[19], $strSQL);
                break;
            case '5': //任意保険新規
                //年月
                $strSQL = str_replace("@VALUE01", $vYYYYMM, $strSQL);
                //部署コード
                $strSQL = str_replace("@VALUE02", $this->fncPadding($vAry[1], "0", 3), $strSQL);
                //部署名
                $strSQL = str_replace("@VALUE03", $vAry[2], $strSQL);
                //社員番号
                $strSQL = str_replace("@VALUE04", $this->fncPadding($vAry[3], "0", 5), $strSQL);
                //社員名
                $strSQL = str_replace("@VALUE05", $vAry[4], $strSQL);
                //件数
                $strSQL = str_replace("@VALUE06", $vAry[5], $strSQL);
                $strSQL = str_replace("@VALUE07", $vAry[6], $strSQL);
                $strSQL = str_replace("@VALUE08", $vAry[7], $strSQL);
                $strSQL = str_replace("@VALUE09", $vAry[8], $strSQL);
                $strSQL = str_replace("@VALUE10", $vAry[9], $strSQL);
                $strSQL = str_replace("@VALUE11", $vAry[10], $strSQL);
                $strSQL = str_replace("@VALUE12", $vAry[11], $strSQL);
                $strSQL = str_replace("@VALUE13", $vAry[12], $strSQL);
                $strSQL = str_replace("@VALUE14", $vAry[13], $strSQL);
                $strSQL = str_replace("@VALUE15", $vAry[14], $strSQL);
                $strSQL = str_replace("@VALUE16", $vAry[15], $strSQL);
                $strSQL = str_replace("@VALUE17", $vAry[16], $strSQL);
                $strSQL = str_replace("@VALUE18", $vAry[17], $strSQL);
                $strSQL = str_replace("@VALUE19", $vAry[18], $strSQL);
                $strSQL = str_replace("@VALUE20", $vAry[19], $strSQL);
                break;
        }
        $strSQL = str_replace("@TABLENM", $vTableNm, $strSQL);
        $strSQL = str_replace("@LIST_MEISAI_NO", $vAry[0], $strSQL);
        $strSQL = str_replace("@BUSYO", $this->ClsComFncJKSYS->FncSqlNv("112"), $strSQL);
        $strSQL = str_replace("@USERID", $this->ClsComFncJKSYS->FncSqlNv($this->GS_LOGINUSER['strUserID']), $strSQL);
        $strSQL = str_replace("@CLIENT", $this->ClsComFncJKSYS->FncSqlNv($this->GS_LOGINUSER['strClientNM']), $strSQL);
        $strSQL = str_replace("@PRG_ID", $this->ClsComFncJKSYS->FncSqlNv("ExcelTorikomi"), $strSQL);
        return $strSQL;
    }

}
