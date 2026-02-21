<?php
namespace App\Model\HDKAIKEI;
// 共通クラスの読込み
use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HDKAttachment extends ClsComDb
{
    function searchFilesSQL($postData, $fileName)
    {
        $strSQL = "SELECT" . "\r\n";
        $strSQL .= "     FILE_NAME" . "\r\n";
        $strSQL .= ",    SEQ" . "\r\n";
        $strSQL .= " FROM " . "\r\n";
        $strSQL .= "      HDK_ATTACHMENT" . "\r\n";
        $strSQL .= " WHERE " . "\r\n";
        $strSQL .= "       SYOHY_NO = '@SYOHY_NO' " . "\r\n";
        $strSQL .= " AND   EDA_NO = '@EDA_NO' " . "\r\n";
        $strSQL .= " AND   GYO_NO = @GYO_NO " . "\r\n";
        $strSQL .= " AND   DEL_FLG = 0 " . "\r\n";
        if ($fileName !== '') {
            $strSQL .= " AND   FILE_NAME = '@FILE_NAME' " . "\r\n";
        }
        $strSQL .= "  ORDER BY " . "\r\n";
        $strSQL .= "       SEQ " . "\r\n";

        $strSQL = str_replace("@SYOHY_NO", $postData['SYOHY_NO'], $strSQL);
        $strSQL = str_replace("@EDA_NO", $postData['EDA_NO'], $strSQL);
        $strSQL = str_replace("@GYO_NO", $postData['GYO_NO'], $strSQL);
        $strSQL = str_replace("@FILE_NAME", $fileName, $strSQL);
        return $strSQL;
    }
    function searchShiwakeSQL($postData)
    {
        $strSQL = "SELECT" . "\r\n";
        $strSQL .= "     PRINT_OUT_FLG" . "\r\n";
        $strSQL .= ",    CSV_OUT_FLG" . "\r\n";
        $strSQL .= ",    XLSX_OUT_FLG" . "\r\n";
        $strSQL .= " FROM " . "\r\n";
        $strSQL .= "      HDPSHIWAKEDATA" . "\r\n";
        $strSQL .= " WHERE " . "\r\n";
        $strSQL .= "       SYOHY_NO = '@SYOHY_NO' " . "\r\n";
        $strSQL .= " AND   EDA_NO = '@EDA_NO' " . "\r\n";
        $strSQL .= " AND   GYO_NO = @GYO_NO " . "\r\n";

        $strSQL = str_replace("@SYOHY_NO", $postData['SYOHY_NO'], $strSQL);
        $strSQL = str_replace("@EDA_NO", $postData['EDA_NO'], $strSQL);
        $strSQL = str_replace("@GYO_NO", $postData['GYO_NO'], $strSQL);
        return $strSQL;
    }

    function fileInsertSQL($SYOHY_NO, $EDA_NO, $GYO_NO, $fileName)
    {
        $strSQL = "INSERT INTO HDK_ATTACHMENT " . "\r\n";
        $strSQL .= "( " . "\r\n";
        $strSQL .= " SYOHY_NO " . "\r\n";
        $strSQL .= " ,EDA_NO " . "\r\n";
        $strSQL .= " ,GYO_NO " . "\r\n";
        $strSQL .= " ,SEQ " . "\r\n";
        $strSQL .= " ,PATH " . "\r\n";
        $strSQL .= " ,FILE_NAME " . "\r\n";
        $strSQL .= " ,DEL_FLG " . "\r\n";
        $strSQL .= " ,CREATE_DATE " . "\r\n";
        $strSQL .= " ,CRE_SYA_CD " . "\r\n";
        $strSQL .= " ,CRE_PRG_ID " . "\r\n";
        $strSQL .= " ,CRE_CLT_NM " . "\r\n";
        $strSQL .= " ,UPD_DATE " . "\r\n";
        $strSQL .= " ,UPD_SYA_CD " . "\r\n";
        $strSQL .= " ,UPD_PRG_ID " . "\r\n";
        $strSQL .= " ,UPD_CLT_NM " . "\r\n";
        $strSQL .= ")  " . "\r\n";
        $strSQL .= "VALUES( " . "\r\n";
        $strSQL .= " '@SYOHY_NO' " . "\r\n";
        $strSQL .= " ,'@EDA_NO' " . "\r\n";
        $strSQL .= " ,@GYO_NO " . "\r\n";
        $strSQL .= " ,(SELECT DECODE(MAX(SEQ), NULL, 1, MAX(SEQ)+1) SEQ FROM HDK_ATTACHMENT WHERE SYOHY_NO = '@SYOHY_NO' AND EDA_NO='@EDA_NO' AND GYO_NO = @GYO_NO) " . "\r\n";
        $strSQL .= " ,'@SYOHY_NO' " . "\r\n";
        $strSQL .= " ,'@FILE_NAME' " . "\r\n";
        $strSQL .= " ,0 " . "\r\n";
        $strSQL .= " ,SYSDATE " . "\r\n";
        $strSQL .= " ,'@USER_ID' " . "\r\n";
        $strSQL .= " ,'@PRG_ID' " . "\r\n";
        $strSQL .= " ,'@CLT_NM' " . "\r\n";
        $strSQL .= " ,SYSDATE " . "\r\n";
        $strSQL .= " ,'@USER_ID' " . "\r\n";
        $strSQL .= " ,'@PRG_ID' " . "\r\n";
        $strSQL .= " ,'@CLT_NM' " . "\r\n";
        $strSQL .= ") ";

        $strSQL = str_replace("@SYOHY_NO", $SYOHY_NO, $strSQL);
        $strSQL = str_replace("@EDA_NO", $EDA_NO, $strSQL);
        $strSQL = str_replace("@GYO_NO", $GYO_NO, $strSQL);
        $strSQL = str_replace("@FILE_NAME", $fileName, $strSQL);
        $strSQL = str_replace("@USER_ID", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@PRG_ID", 'HDK_Attachment', $strSQL);
        $strSQL = str_replace("@CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        return $strSQL;
    }

    function fileUpdataSQL($SYOHY_NO, $EDA_NO, $GYO_NO, $fileName)
    {
        $strSQL = "UPDATE HDK_ATTACHMENT " . "\r\n";
        $strSQL .= " SET " . "\r\n";
        $strSQL .= " UPD_DATE=SYSDATE " . "\r\n";
        $strSQL .= " ,UPD_SYA_CD='@USER_ID'  " . "\r\n";
        $strSQL .= " ,UPD_PRG_ID='@PRG_ID' " . "\r\n";
        $strSQL .= " ,UPD_CLT_NM='@CLT_NM' " . "\r\n";
        $strSQL .= " WHERE " . "\r\n";
        $strSQL .= "       SYOHY_NO = '@SYOHY_NO' " . "\r\n";
        $strSQL .= " AND   EDA_NO = '@EDA_NO' " . "\r\n";
        $strSQL .= " AND   GYO_NO = @GYO_NO " . "\r\n";
        $strSQL .= " AND   FILE_NAME = '@FILE_NAME' " . "\r\n";
        $strSQL .= " AND   DEL_FLG = 0 " . "\r\n";

        $strSQL = str_replace("@SYOHY_NO", $SYOHY_NO, $strSQL);
        $strSQL = str_replace("@EDA_NO", $EDA_NO, $strSQL);
        $strSQL = str_replace("@GYO_NO", $GYO_NO, $strSQL);
        $strSQL = str_replace("@FILE_NAME", $fileName, $strSQL);
        $strSQL = str_replace("@USER_ID", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@PRG_ID", 'HDK_Attachment', $strSQL);
        $strSQL = str_replace("@CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        return $strSQL;
    }

    function fileDeleteSQL($postData)
    {
        $strSQL = "UPDATE HDK_ATTACHMENT " . "\r\n";
        $strSQL .= " SET " . "\r\n";
        $strSQL .= " DEL_FLG = 1 " . "\r\n";
        $strSQL .= " ,DEL_DATE=SYSDATE " . "\r\n";
        $strSQL .= " ,DEL_SYA_CD='@USER_ID'  " . "\r\n";
        $strSQL .= " ,DEL_PRG_ID='@PRG_ID' " . "\r\n";
        $strSQL .= " ,DEL_CLT_NM='@CLT_NM' " . "\r\n";
        $strSQL .= " WHERE " . "\r\n";
        $strSQL .= "       SYOHY_NO = '@SYOHY_NO' " . "\r\n";
        $strSQL .= " AND   EDA_NO = '@EDA_NO' " . "\r\n";
        $strSQL .= " AND   GYO_NO = @GYO_NO " . "\r\n";
        $strSQL .= " AND   SEQ = @SEQ " . "\r\n";

        $strSQL = str_replace("@SYOHY_NO", $postData['SYOHY_NO'], $strSQL);
        $strSQL = str_replace("@EDA_NO", $postData['EDA_NO'], $strSQL);
        $strSQL = str_replace("@GYO_NO", $postData['GYO_NO'], $strSQL);
        $strSQL = str_replace("@SEQ", $postData['SEQ'], $strSQL);
        $strSQL = str_replace("@USER_ID", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@PRG_ID", 'HDK_Attachment', $strSQL);
        $strSQL = str_replace("@CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        return $strSQL;
    }

    function fncDispModeSansyoChk($strSyohy_NO)
    {
        $strSQL = "";
        $strSQL .= "SELECT MAX(TO_CHAR(UPD_DATE,'YYYY/MM/DD HH24:MI:SS'))	  UPD_DATE" . "\r\n";
        $strSQL .= ",	  MAX(PRINT_OUT_FLG) PRINT_OUT_FLG" . "\r\n";
        $strSQL .= ",	  MAX(CSV_OUT_FLG)   CSV_OUT_FLG" . "\r\n";
        $strSQL .= ",	  MAX(XLSX_OUT_FLG)   XLSX_OUT_FLG" . "\r\n";
        $strSQL .= ",	  MAX(HONBU_SYORIZUMI_FLG) HONBU_SYORIZUMI_FLG" . "\r\n";
        $strSQL .= "FROM   HDPSHIWAKEDATA" . "\r\n";
        $strSQL .= "WHERE  SYOHY_NO = '@SYOHY_NO'" . "\r\n";
        $strSQL .= "GROUP BY SYOHY_NO" . "\r\n";

        $strSQL = str_replace("@SYOHY_NO", $strSyohy_NO, $strSQL);

        return parent::select($strSQL);
    }

    //添付ファイルデータを取得
    public function searchFiles($postData, $fileName = '')
    {
        $strSql = $this->searchFilesSQL($postData, $fileName);

        return parent::select($strSql);
    }
    //証憑NO を条件に 仕訳データを検索
    public function searchShiwake($postData)
    {
        $strSql = $this->searchShiwakeSQL($postData);

        return parent::select($strSql);
    }

    //添付ファイルデータを新規
    public function fileInsert($SYOHY_NO, $EDA_NO, $GYO_NO, $fileName)
    {
        $strSql = $this->fileInsertSQL($SYOHY_NO, $EDA_NO, $GYO_NO, $fileName);

        return parent::insert($strSql);
    }

    //添付ファイルデータを更新
    public function fileUpdata($SYOHY_NO, $EDA_NO, $GYO_NO, $fileName)
    {
        $strSql = $this->fileUpdataSQL($SYOHY_NO, $EDA_NO, $GYO_NO, $fileName);

        return parent::update($strSql);
    }

    //添付ファイルデータを削除
    public function fileDelete($postData)
    {
        $strSql = $this->fileDeleteSQL($postData);

        return parent::update($strSql);
    }

}
