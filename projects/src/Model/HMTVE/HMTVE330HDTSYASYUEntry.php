<?php
// 共通クラスの読込み
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：車種マスタメンテナンス
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE330HDTSYASYUEntry extends ClsComDb
{
    //*************************************
    // * SQL文
    //*************************************

    //画面初期化データSQL
    function updateDataSQL($SYASYUCD)
    {
        $strSql = "";
        $strSql = $strSql . " SELECT SYASYU_CD ";
        $strSql = $strSql . ",SYASYU_NM";
        $strSql = $strSql . ",SYASYU_RYKNM";
        $strSql = $strSql . ",SYASYU_KB";
        $strSql = $strSql . ",SOKU_SEIYAKU_OUT_FLG";
        $strSql = $strSql . ",KAKU_DEMO_OUT_FLG";
        $strSql = $strSql . ",DISP_NO";
        $strSql = $strSql . " FROM HDTSYASYU";
        $strSql = $strSql . " WHERE SYASYU_CD = '@SYASYUCD'";

        $strSql = str_replace("@SYASYUCD", $SYASYUCD, $strSql);

        return $strSql;
    }

    //チェックSQL
    function getSqlCheckSql($SYASYUCD)
    {
        $strSql = "";
        $strSql = $strSql . "SELECT SYASYU_CD ";
        $strSql = $strSql . "FROM   HDTSYASYU ";
        $strSql = $strSql . "WHERE  SYASYU_CD = '@SYASYUCD'";

        $strSql = str_replace("@SYASYUCD", $SYASYUCD, $strSql);

        return $strSql;
    }

    //追加処理SQL
    function insertHDTSYASYUSql($postdata)
    {
        $strSql = "";
        $strSql = $strSql . " INSERT INTO HDTSYASYU    " . "\r\n";
        $strSql = $strSql . " (SYASYU_CD,              " . "\r\n";
        $strSql = $strSql . "  SYASYU_NM,              " . "\r\n";
        $strSql = $strSql . "  SYASYU_RYKNM,           " . "\r\n";
        $strSql = $strSql . "  SYASYU_KB,              " . "\r\n";
        $strSql = $strSql . "  SOKU_SEIYAKU_OUT_FLG,   " . "\r\n";
        $strSql = $strSql . "  KAKU_DEMO_OUT_FLG,      " . "\r\n";
        $strSql = $strSql . "  DISP_NO,                " . "\r\n";
        $strSql = $strSql . "  UPD_DATE,               " . "\r\n";
        $strSql = $strSql . "  CREATE_DATE,            " . "\r\n";
        $strSql = $strSql . "  UPD_SYA_CD,             " . "\r\n";
        $strSql = $strSql . "  UPD_PRG_ID,             " . "\r\n";
        $strSql = $strSql . "  UPD_CLT_NM)             " . "\r\n";
        $strSql = $strSql . "  VALUES(                 " . "\r\n";
        $strSql = $strSql . "  '@SYASYU_CD',            " . "\r\n";
        $strSql = $strSql . "  '@SYASYU_NM',            " . "\r\n";
        $strSql = $strSql . "  '@SYASYU_RYKNM',         " . "\r\n";
        $strSql = $strSql . "  '@SYASYU_KB',            " . "\r\n";
        $strSql = $strSql . "  '@SOKU_SEIYAKU_OUT_FLG',   " . "\r\n";
        $strSql = $strSql . "  '@KAKU_DEMO_OUT_FLG',      " . "\r\n";
        $strSql = $strSql . "  '@DISP_NO',               " . "\r\n";
        $strSql = $strSql . "  SYSDATE,                  " . "\r\n";
        $strSql = $strSql . "  SYSDATE,                  " . "\r\n";
        $strSql = $strSql . "  '@UPD_SYA_CD',          " . "\r\n";
        $strSql = $strSql . "  '@UPD_PRG_ID',          " . "\r\n";
        $strSql = $strSql . "  '@UPD_CLT_NM' )        " . "\r\n";

        $strSql = str_replace("@SYASYU_CD", $postdata['SYASYU_CD'], $strSql);
        $strSql = str_replace("@SYASYU_NM", $postdata['SYASYU_NM'], $strSql);
        $strSql = str_replace("@SYASYU_RYKNM", $postdata['SYASYU_RYKNM'], $strSql);
        $strSql = str_replace("@SYASYU_KB", $postdata['SYASYU_KB'], $strSql);
        $strSql = str_replace("@SOKU_SEIYAKU_OUT_FLG", $postdata['SOKU_SEIYAKU_OUT_FLG'], $strSql);
        $strSql = str_replace("@KAKU_DEMO_OUT_FLG", $postdata['KAKU_DEMO_OUT_FLG'], $strSql);
        $strSql = str_replace("@DISP_NO", $postdata['DISP_NO'], $strSql);
        $strSql = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSql);
        $strSql = str_replace("@UPD_PRG_ID", $postdata['UPD_PRG_ID'], $strSql);
        $strSql = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSql);

        return $strSql;
    }

    //更新処理SQL
    function updateHDTSYASYUSql($postdata)
    {
        $strSql = "";
        $strSql = $strSql . " UPDATE HDTSYASYU                                  " . "\r\n";
        $strSql = $strSql . " SET                                               " . "\r\n";
        $strSql = $strSql . "  SYASYU_NM='@SYASYU_NM',                          " . "\r\n";
        $strSql = $strSql . "  SYASYU_RYKNM='@SYASYU_RYKNM',                    " . "\r\n";
        $strSql = $strSql . "  SYASYU_KB='@SYASYU_KB',                          " . "\r\n";
        $strSql = $strSql . "  SOKU_SEIYAKU_OUT_FLG='@SOKU_SEIYAKU_OUT_FLG',    " . "\r\n";
        $strSql = $strSql . "  KAKU_DEMO_OUT_FLG = '@KAKU_DEMO_OUT_FLG',        " . "\r\n";
        $strSql = $strSql . "  DISP_NO='@DISP_NO',                              " . "\r\n";
        $strSql = $strSql . "  UPD_DATE=SYSDATE,                                " . "\r\n";
        $strSql = $strSql . "  CREATE_DATE=SYSDATE,                             " . "\r\n";
        $strSql = $strSql . "  UPD_SYA_CD='@UPD_SYA_CD',                        " . "\r\n";
        $strSql = $strSql . "  UPD_PRG_ID='@UPD_PRG_ID',                        " . "\r\n";
        $strSql = $strSql . "  UPD_CLT_NM = '@UPD_CLT_NM'                       " . "\r\n";
        $strSql = $strSql . "    WHERE SYASYU_CD='@SYASYU_CD'                   " . "\r\n";

        $strSql = str_replace("@SYASYU_CD", $postdata['SYASYU_CD'], $strSql);
        $strSql = str_replace("@SYASYU_NM", $postdata['SYASYU_NM'], $strSql);
        $strSql = str_replace("@SYASYU_RYKNM", $postdata['SYASYU_RYKNM'], $strSql);
        $strSql = str_replace("@SYASYU_KB", $postdata['SYASYU_KB'], $strSql);
        $strSql = str_replace("@SOKU_SEIYAKU_OUT_FLG", $postdata['SOKU_SEIYAKU_OUT_FLG'], $strSql);
        $strSql = str_replace("@KAKU_DEMO_OUT_FLG", $postdata['KAKU_DEMO_OUT_FLG'], $strSql);
        $strSql = str_replace("@DISP_NO", $postdata['DISP_NO'], $strSql);
        $strSql = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSql);
        $strSql = str_replace("@UPD_PRG_ID", $postdata['UPD_PRG_ID'], $strSql);
        $strSql = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSql);

        return $strSql;
    }

    //画面初期化データ
    public function updateData($SYASYUCD)
    {
        return parent::select($this->updateDataSQL($SYASYUCD));
    }

    //チェック
    public function getSqlCheck($SYASYUCD)
    {
        return parent::select($this->getSqlCheckSql($SYASYUCD));
    }

    //追加処理
    public function insertHDTSYASYU($postdata)
    {
        return parent::insert($this->insertHDTSYASYUSql($postdata));
    }

    //更新処理
    public function updateHDTSYASYU($postdata)
    {
        return parent::update($this->updateHDTSYASYUSql($postdata));
    }

}
