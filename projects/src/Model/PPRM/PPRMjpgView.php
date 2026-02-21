<?php

/**
 * 説明：
 *
 *
 * @author wangying
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           GSDL　　　　　　　　
 * * --------------------------------------------------------------------------------------------
 */

//共通クラスの読込み
namespace App\Model\PPRM;

use App\Model\Component\ClsComDb;

//*************************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************************
class PPRMjpgView extends ClsComDb
{
    public function fncImgPath1($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT SAVE_PATH" . " \r\n";
        $strSQL .= "FROM   PPRIMAGEFILEDATA" . " \r\n";
        $strSQL .= "WHERE IMAGE_FILE_ID = '" . $postData["ID"] . "'" . " \r\n";

        return parent::select($strSQL);
    }

    public function fncImgPath2($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT SAVE_PATH" . " \r\n";
        $strSQL .= "FROM   PPRIMAGEFILEDATA" . " \r\n";
        $strSQL .= "WHERE TENPO_CD = '" . $postData["TENPO_CD"] . "'" . " \r\n";
        $strSQL .= "AND TEN_HJM_NO = '" . $postData["TEN_HJM_NO"] . "'" . " \r\n";
        $strSQL .= "AND ROWNUM <= 100" . " \r\n";
        $strSQL .= "ORDER BY IMAGE_FILE_ID" . " \r\n";

        return parent::select($strSQL);
    }

}