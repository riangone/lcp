<?php
// 共通クラスの読込み
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE110PublicityOrderBase extends ClsComDb
{
    // '**********************************************************************
    // '処 理 名：年月のＳＱＬ文の取得
    // '関 数 名：getYMSQL
    // '引 数   ：なし
    // '戻 り 値：strSQL　　　String
    // '処理説明：年月のＳＱＬ文の取得する
    // '**********************************************************************
    public function getYMSQL()
    {
        $strSQL = "";
        $strSQL .= " SELECT MIN(START_DATE) AS IVENTMIN " . "\r\n";
        $strSQL .= ",       MAX(START_DATE) AS IVENTMAX " . "\r\n";
        $strSQL .= ",       TO_CHAR(ADD_MONTHS(SYSDATE,1), 'YYYY/MM/DD hh24:mi:ss') TD " . "\r\n";
        $strSQL .= " FROM   HDTIVENTDATA " . "\r\n";
        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：展示会設定ﾃｰﾌﾞﾙのＳＱＬ文の取得
    // '関 数 名：getExGrdViewSQL
    // '引 数   ：なし
    // '戻 り 値：strSQL　　　String
    // '処理説明：展示会設定ﾃｰﾌﾞﾙのＳＱＬ文の取得する
    // '**********************************************************************
    public function getExGrdViewSQL($postData)
    {
        $strSQL = "";
        $strSQL .= " SELECT DT.START_DATE " . "\r\n";
        $strSQL .= ",       TO_CHAR(TO_DATE(DT.START_DATE,'YYYY/MM/DD'),'YYYY/MM/DD') " . "\r\n";
        $strSQL .= "        || '～' || TO_CHAR(TO_DATE(DT.END_DATE,'YYYY/MM/DD'),'YYYY/MM/DD') KIKAN " . "\r\n";
        $strSQL .= ",       DT.IVENT_NM " . "\r\n";
        $strSQL .= ",       IV.BIKOU " . "\r\n";
        $strSQL .= " FROM   HDTIVENTDATA DT " . "\r\n";
        $strSQL .= "        LEFT JOIN HDTPUBLICITYIVENT IV " . "\r\n";
        $strSQL .= "        ON DT.START_DATE = IV.START_DATE " . "\r\n";
        $strSQL .= " WHERE  SUBSTR(DT.START_DATE,1,6) = '@NENGETU' " . "\r\n";
        $strSQL .= " ORDER BY DT.START_DATE " . "\r\n";
        $strSQL = str_replace("@NENGETU", $postData['ddlYear'] . $postData['ddlMonth'], $strSQL);
        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：品名・単価ﾃｰﾌﾞﾙのＳＱＬ文の取得
    // '関 数 名：getExDetailGrdViewSQL
    // '引 数   ：なし
    // '戻 り 値：strSQL　　　String
    // '処理説明：品名・単価ﾃｰﾌﾞﾙのＳＱＬ文の取得する
    // '**********************************************************************
    public function getExDetailGrdViewSQL($postData)
    {
        $strSQL = "";
        $strSQL .= " SELECT GOODS.HINMEI1 " . "\r\n";
        $strSQL .= " ,      GOODS.TANKA1 " . "\r\n";
        $strSQL .= " ,      GOODS.HINMEI2 " . "\r\n";
        $strSQL .= " ,      GOODS.TANKA2 " . "\r\n";
        $strSQL .= " ,      GOODS.HINMEI3 " . "\r\n";
        $strSQL .= " ,      GOODS.TANKA3 " . "\r\n";
        $strSQL .= " FROM   HDTPUBLICITYGOODS GOODS " . "\r\n";
        $strSQL .= " WHERE  IVENT_YM IN (SELECT MAX(IVENT_YM) " . "\r\n";
        $strSQL .= "                     FROM   HDTPUBLICITYGOODS PUB " . "\r\n";
        $strSQL .= "                     WHERE  PUB.IVENT_YM <= '@NENGETU' ) " . "\r\n";
        $strSQL = str_replace("@NENGETU", $postData['ddlYear'] . $postData['ddlMonth'], $strSQL);
        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：回収期限用データのＳＱＬ文の取得
    // '関 数 名：getDateSQL
    // '引 数   ：なし
    // '戻 り 値：strSQL　　　String
    // '処理説明：回収期限用データのＳＱＬ文の取得する
    // '**********************************************************************
    public function getDateSQL($postData)
    {
        $strSQL = "";
        $strSQL .= " SELECT KIGEN.KIGEN_YM " . "\r\n";
        $strSQL .= ",       TO_CHAR(TO_DATE(KIGEN.CREATE_DATE,'YYYY/MM/DD'),'YYYY/MM/DD')" . "\r\n";
        $strSQL .= " FROM   HDTPUBLICITYTERM KIGEN " . "\r\n";
        $strSQL .= " WHERE  KIGEN.IVENT_YM = '@NENGETU'" . "\r\n";
        $strSQL = str_replace("@NENGETU", $postData['ddlYear'] . $postData['ddlMonth'], $strSQL);
        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：展示会宣材注文展示会データを削除のＳＱＬ文の取得
    // '関 数 名：getExDelSQL
    // '引 数   ：なし
    // '戻 り 値：strSQL　　　String
    // '処理説明：展示会宣材注文展示会データを削除のＳＱＬ文の取得する
    // '**********************************************************************
    public function getExDelSQL($postData)
    {
        $strSQL = "";
        $strSQL .= " DELETE FROM HDTPUBLICITYIVENT " . "\r\n";
        $strSQL .= "WHERE  IVENT_YM = '@IVENTYM'" . "\r\n";
        $strSQL = str_replace("@IVENTYM", $postData['ddlYear'] . $postData['ddlMonth'], $strSQL);
        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：展示会宣材注文展示会データに登録のＳＱＬ文の取得
    // '関 数 名：getExInsertSQL
    // '引 数   ：なし
    // '戻 り 値：strSQL　　　String
    // '処理説明：展示会宣材注文展示会データに登録のＳＱＬ文の取得する
    // '**********************************************************************
    public function getExInsertSQL($postData)
    {
        $strSQL = "";
        $strSQL .= " INSERT INTO HDTPUBLICITYIVENT " . "\r\n";
        $strSQL .= " (  IVENT_YM , START_DATE , BIKOU , UPD_DATE " . "\r\n";
        $strSQL .= "  , CREATE_DATE , UPD_SYA_CD , UPD_PRG_ID , UPD_CLT_NM) " . "\r\n";
        $strSQL .= " VALUES " . "\r\n";
        $strSQL .= " (  '@IVENT_YM' , '@START_DATE' , '@BIKOU' , SYSDATE, " . "\r\n";
        if ($postData['txtTime'] == "") {
            $strSQL .= "SYSDATE," . "\r\n";
        } else {
            $strSQL .= "     TO_CHAR(TO_DATE('@CREATE_DATE', 'YYYY/MM/DD'),'YYYY-MM-DD') ," . "\r\n";
            $strSQL = str_replace("@CREATE_DATE", $postData['txtTime'], $strSQL);
        }
        $strSQL .= " '@UPD_SYA_CD' , '@UPD_PRG_ID' , '@UPD_CLT_NM' ) " . "\r\n";
        $strSQL = str_replace("@IVENT_YM", $postData['ddlYear'] . $postData['ddlMonth'], $strSQL);
        $strSQL = str_replace("@START_DATE", substr($postData['start_date'], 0, 4) . substr($postData['start_date'], 5, 2) . substr($postData['start_date'], 8, 2), $strSQL);
        $strSQL = str_replace("@BIKOU", $postData['txtRemark'], $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", "Pub", $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：宣材注文品名データを削除のＳＱＬ文の取得
    // '関 数 名：getExDetailDelSQL
    // '引 数   ：なし
    // '戻 り 値：strSQL　　　String
    // '処理説明：宣材注文品名データを削除のＳＱＬ文の取得する
    // '**********************************************************************
    public function getExDetailDelSQL($postData)
    {
        $strSQL = "";
        $strSQL .= " DELETE FROM HDTPUBLICITYGOODS " . "\r\n";
        $strSQL .= "WHERE  IVENT_YM = '@IVENTYM'" . "\r\n";
        $strSQL = str_replace("@IVENTYM", $postData['ddlYear'] . $postData['ddlMonth'], $strSQL);
        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：宣材注文品名データに登録のＳＱＬ文の取得
    // '関 数 名：getExDetailInsertSQL
    // '引 数   ：なし
    // '戻 り 値：strSQL　　　String
    // '処理説明：宣材注文品名データに登録のＳＱＬ文の取得する
    // '**********************************************************************
    public function getExDetailInsertSQL($postData)
    {
        $strSQL = "";
        $strSQL .= " INSERT INTO HDTPUBLICITYGOODS " . "\r\n";
        $strSQL .= " (IVENT_YM, HINMEI1, TANKA1, HINMEI2, TANKA2, HINMEI3 " . "\r\n";
        $strSQL .= ", TANKA3, UPD_DATE, CREATE_DATE, UPD_SYA_CD, UPD_PRG_ID, UPD_CLT_NM ) " . "\r\n";
        $strSQL .= " VALUES " . "\r\n";
        $strSQL .= " ('@IVENT_YM', '@HINMEI1', '@TANKA1', '@HINMEI2', '@TANKA2', '@HINMEI3' " . "\r\n";
        $strSQL .= ", '@TANKA3', SYSDATE, " . "\r\n";
        if ($postData['txtTime'] == "") {
            $strSQL .= "SYSDATE," . "\r\n";
        } else {
            $strSQL .= "     TO_CHAR(TO_DATE('@CREATE_DATE', 'YYYY/MM/DD'),'YYYY-MM-DD') ," . "\r\n";
            $strSQL = str_replace("@CREATE_DATE", $postData['txtTime'], $strSQL);
        }
        $strSQL .= " '@UPD_SYA_CD' , '@UPD_PRG_ID' , '@UPD_CLT_NM' ) " . "\r\n";
        $strSQL = str_replace("@IVENT_YM", $postData['ddlYear'] . $postData['ddlMonth'], $strSQL);
        $strSQL = str_replace("@HINMEI1", $postData['txtName1'], $strSQL);
        $strSQL = str_replace("@TANKA1", $postData['txtPrice1'], $strSQL);
        $strSQL = str_replace("@HINMEI2", $postData['txtName2'], $strSQL);
        $strSQL = str_replace("@TANKA2", $postData['txtPrice2'], $strSQL);
        $strSQL = str_replace("@HINMEI3", $postData['txtName3'], $strSQL);
        $strSQL = str_replace("@TANKA3", $postData['txtPrice3'], $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", "PublicityOrderBase", $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：宣材注文回収期限データを削除のＳＱＬ文の取得
    // '関 数 名：getDateDelSQL
    // '引 数   ：なし
    // '戻 り 値：strSQL　　　String
    // '処理説明：宣材注文回収期限データを削除のＳＱＬ文の取得する
    // '**********************************************************************
    public function getDateDelSQL($postData)
    {
        $strSQL = "";
        $strSQL .= " DELETE FROM HDTPUBLICITYTERM " . "\r\n";
        $strSQL .= "WHERE  IVENT_YM = '@IVENTYM'" . "\r\n";
        $strSQL = str_replace("@IVENTYM", $postData['ddlYear'] . $postData['ddlMonth'], $strSQL);
        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：宣材注文回収期限データを登録のＳＱＬ文の取得
    // '関 数 名：getDateInsertSQL
    // '引 数   ：なし
    // '戻 り 値：strSQL　　　String
    // '処理説明：宣材注文回収期限データを登録のＳＱＬ文の取得する
    // '**********************************************************************
    public function getDateInsertSQL($postData)
    {
        $strSQL = "";
        $strSQL .= " INSERT INTO HDTPUBLICITYTERM " . "\r\n";
        $strSQL .= " ( IVENT_YM, KIGEN_YM, UPD_DATE, CREATE_DATE, UPD_SYA_CD, UPD_PRG_ID, UPD_CLT_NM ) " . "\r\n";
        $strSQL .= " VALUES " . "\r\n";
        $strSQL .= " ( '@IVENT_YM', '@KIGEN_YM', SYSDATE, " . "\r\n";
        if ($postData['txtTime'] == "") {
            $strSQL .= "SYSDATE," . "\r\n";
        } else {
            $strSQL .= "     TO_CHAR(TO_DATE('@CREATE_DATE', 'YYYY/MM/DD'),'YYYY-MM-DD')  ," . "\r\n";
            $strSQL = str_replace("@CREATE_DATE", $postData['txtTime'], $strSQL);
        }
        $strSQL .= " '@UPD_SYA_CD' , '@UPD_PRG_ID' , '@UPD_CLT_NM' ) " . "\r\n";
        $strSQL = str_replace("@IVENT_YM", $postData['ddlYear'] . $postData['ddlMonth'], $strSQL);
        $strSQL = str_replace("@KIGEN_YM", str_replace("/", "", $postData["txtDate"]), $strSQL);

        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", "Publ", $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        return $strSQL;
    }

    public function getYM()
    {
        $strSql = $this->getYMSQL();

        return parent::select($strSql);
    }

    public function getExGrdView($postData)
    {
        $strSql = $this->getExGrdViewSQL($postData);

        return parent::select($strSql);
    }

    public function getExDetailGrdView($postData)
    {
        $strSql = $this->getExDetailGrdViewSQL($postData);

        return parent::select($strSql);
    }

    public function getDate($postData)
    {
        $strSql = $this->getDateSQL($postData);

        return parent::select($strSql);
    }

    public function getExDel($postData)
    {
        $strSql = $this->getExDelSQL($postData);

        return parent::delete($strSql);
    }

    public function getExInsert($postData)
    {
        $strSql = $this->getExInsertSQL($postData);

        return parent::insert($strSql);
    }

    public function getExDetailDel($postData)
    {
        $strSql = $this->getExDetailDelSQL($postData);

        return parent::delete($strSql);
    }

    public function getExDetailInsert($postData)
    {
        $strSql = $this->getExDetailInsertSQL($postData);

        return parent::insert($strSql);
    }

    public function getDateDel($postData)
    {
        $strSql = $this->getDateDelSQL($postData);

        return parent::delete($strSql);
    }

    public function getDateInsert($postData)
    {
        $strSql = $this->getDateInsertSQL($postData);

        return parent::insert($strSql);
    }

}

