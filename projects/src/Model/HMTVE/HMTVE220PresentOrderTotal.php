<?php
// 共通クラスの読込み
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE220PresentOrderTotal extends ClsComDb
{
    //*************************************
    // * SQL文
    //*************************************

    //部署データのＳＱＬ文を取得する
    function getTenpoSQL($STARTDT)
    {
        $strSql = "";
        $strSql = $strSql . " SELECT DT.BUSYO_CD" . "\r\n";
        $strSql = $strSql . " ,      MST.BUSYO_RYKNM" . "\r\n";
        $strSql = $strSql . " FROM   HDTPRESENTORDER DT" . "\r\n";
        $strSql = $strSql . " INNER JOIN HBUSYO MST" . "\r\n";
        $strSql = $strSql . " ON     MST.STD_TENPO_DISP_NO IS NOT NULL " . "\r\n";
        $strSql = $strSql . "INNER JOIN (SELECT BUSYO_CD                                      " . "\r\n";
        $strSql = $strSql . "            ,      (CASE WHEN HDT_TENPO_CD IS NOT NULL           " . "\r\n";
        $strSql = $strSql . "                    THEN HDT_TENPO_CD ELSE BUSYO_CD END) V_TENPO " . "\r\n";
        $strSql = $strSql . "            FROM   HBUSYO) BUS                                   " . "\r\n";
        $strSql = $strSql . "ON     MST.BUSYO_CD = BUS.V_TENPO " . "\r\n";
        $strSql = $strSql . "  WHERE  DT.START_DATE = '@STARTDT'" . "\r\n";
        $strSql = $strSql . " AND    BUS.BUSYO_CD = DT.BUSYO_CD" . "\r\n";
        $strSql = $strSql . " GROUP BY DT.BUSYO_CD " . "\r\n";
        $strSql = $strSql . " , MST.BUSYO_RYKNM" . "\r\n";
        $strSql = $strSql . " , MST.STD_TENPO_DISP_NO" . "\r\n";
        $strSql = $strSql . " ORDER BY MST.STD_TENPO_DISP_NO" . "\r\n";

        $strSql = str_replace("@STARTDT", $STARTDT, $strSql);

        return $strSql;
    }

    //存在チェックのＳＱＬ文を取得する
    function getChkSQL($STARTDT)
    {
        $strSql = "";
        $strSql = $strSql . "　Select  START_DATE ";
        $strSql = $strSql . "　FROM   HDTPRESENTKAKUTEIDATA  ";
        $strSql = $strSql . "　WHERE  START_DATE = '@STARTDT' ";

        $strSql = str_replace("@STARTDT", $STARTDT, $strSql);

        return $strSql;
    }

    //追加処理のＳＱＬ文を取得する
    function getUpdateSQL($STARTDT)
    {
        $strSql = "";
        $strSql = $strSql . "　INSERT INTO HDTPRESENTKAKUTEIDATA　 ";
        $strSql = $strSql . "　(      START_DATE　 ";
        $strSql = $strSql . "　,      KAKUTEI_FLG　 ";
        $strSql = $strSql . "　,      CREATE_DATE　 ";
        $strSql = $strSql . "　,      UPD_DATE　 ";
        $strSql = $strSql . "　,      UPD_SYA_CD　 ";
        $strSql = $strSql . "　,      UPD_PRG_ID　 ";
        $strSql = $strSql . "　,      UPD_CLT_NM　 ";
        $strSql = $strSql . "　)　 ";
        $strSql = $strSql . "　VALUES ('@STARTDT'　 ";
        $strSql = $strSql . "　,       '1'　 ";
        $strSql = $strSql . "　,       SYSDATE　 ";
        $strSql = $strSql . "　,       SYSDATE　 ";
        $strSql = $strSql . "　,       '@LoginID'";
        $strSql = $strSql . "　,       'PresentOrderTotal'　 ";
        $strSql = $strSql . "　,      '@MachineNM'";
        $strSql = $strSql . "　)　 ";

        $strSql = str_replace("@LoginID", $this->GS_LOGINUSER['strUserID'], $strSql);
        $strSql = str_replace("@MachineNM", $this->GS_LOGINUSER['strClientNM'], $strSql);
        $strSql = str_replace("@STARTDT", $STARTDT, $strSql);

        return $strSql;
    }

    //更新処理のＳＱＬ文を取得する
    function getInsertSQL($STARTDT)
    {
        $strSql = "";
        $strSql = $strSql . "　UPDATE  HDTPRESENTKAKUTEIDATA 　 ";
        $strSql = $strSql . "　SET    KAKUTEI_FLG = '1'　 ";
        $strSql = $strSql . "　WHERE  START_DATE = '@STARTDT'　 ";

        $strSql = str_replace("@STARTDT", $STARTDT, $strSql);

        return $strSql;
    }

    //出力ﾌﾗｸﾞを"1"で更新のＳＱＬ文を取得する
    function getUpdate1SQL($STARTDT)
    {
        $strSql = "";
        $strSql = $strSql . "　UPDATE  HDTPRESENTORDER  ";
        $strSql = $strSql . "　SET    OUT_FLG = '1' ";
        $strSql = $strSql . "　WHERE  START_DATE >= '@STARTDT' ";

        $strSql = str_replace("@STARTDT", $STARTDT, $strSql);

        return $strSql;
    }

    //出力ﾌﾗｸﾞを"0"で更新のＳＱＬ文を取得する
    function getUpdate0SQL($STARTDT)
    {
        $strSql = "";
        $strSql = $strSql . "　UPDATE  HDTPRESENTKAKUTEIDATA  ";
        $strSql = $strSql . "　SET    KAKUTEI_FLG = '0' ";
        $strSql = $strSql . "　WHERE  KAKUTEI_FLG = 1  ";
        $strSql = $strSql . "　AND    START_DATE = '@STARTDT' ";

        $strSql = str_replace("@STARTDT", $STARTDT, $strSql);

        return $strSql;
    }

    //プレゼント注文データのＳＱＬ文を取得する
    function getTitleSQL($STARTDT)
    {
        $strSql = "";
        $strSql = $strSql . "　Select  BASE.HINMEI ";
        $strSql = $strSql . "　,  　　BASE.ORDER_NO ";
        $strSql = $strSql . "　FROM   HDTPRESENTBASE BASE ";
        $strSql = $strSql . "　WHERE  BASE.START_DATE = '@START_DATE' ";
        $strSql = $strSql . "　ORDER BY ORDER_NO ";

        $strSql = str_replace("@START_DATE", $STARTDT, $strSql);

        return $strSql;
    }

    //品名データのＳＱＬ文を取得する
    function getTotalSQL($STARTDT)
    {
        $strSql = "";
        $strSql = $strSql . "　Select ORDER_NO,SUM(ORDER_NUM) TOTAL ";
        $strSql = $strSql . "　FROM   HDTPRESENTORDER  ";
        $strSql = $strSql . "　WHERE  START_DATE = '@START_DATE' ";
        $strSql = $strSql . "　GROUP BY ORDER_NO ";
        $strSql = $strSql . "　ORDER BY ORDER_NO ";

        $strSql = str_replace("@START_DATE", $STARTDT, $strSql);

        return $strSql;
    }

    //品名データのＳＱＬ文を取得する
    function getMaxOrderNoSQL($STARTDT)
    {
        $strSql = "";
        $strSql = $strSql . "　Select MAX(ORDER_NO) MAXNo ";
        $strSql = $strSql . "　FROM   HDTPRESENTBASE ";
        $strSql = $strSql . "　WHERE  START_DATE = '@START_DATE' ";

        $strSql = str_replace("@START_DATE", $STARTDT, $strSql);

        return $strSql;
    }

    //明細データのＳＱＬ文を取得する
    function getDetailSQL($STARTDT)
    {
        $strSql = "";
        $strSql = $strSql . "　Select BUS.BUSYO_CD　 " . "\r\n";
        $strSql = $strSql . "　,      MST.BUSYO_RYKNM　 " . "\r\n";
        $strSql = $strSql . "　,      BASE.HINMEI　 " . "\r\n";
        $strSql = $strSql . "　,      BASE.ORDER_NO　 " . "\r\n";
        $strSql = $strSql . "　,      DATA.ORDER_NUM　 " . "\r\n";
        $strSql = $strSql . "　FROM   HDTPRESENTBASE BASE　 " . "\r\n";
        $strSql = $strSql . "　INNER JOIN HBUSYO MST　 " . "\r\n";
        $strSql = $strSql . "　ON     MST.STD_TENPO_DISP_NO IS NOT NULL　 " . "\r\n";
        $strSql = $strSql . "INNER JOIN (SELECT BUSYO_CD                                      " . "\r\n";
        $strSql = $strSql . "            ,      (CASE WHEN HDT_TENPO_CD IS NOT NULL           " . "\r\n";
        $strSql = $strSql . "                    THEN HDT_TENPO_CD ELSE BUSYO_CD END) V_TENPO " . "\r\n";
        $strSql = $strSql . "            FROM   HBUSYO) BUS                                   " . "\r\n";
        $strSql = $strSql . "ON     MST.BUSYO_CD = BUS.V_TENPO " . "\r\n";
        $strSql = $strSql . "　INNER JOIN　 " . "\r\n";
        $strSql = $strSql . "　(SELECT DISTINCT BUSYO_CD FROM HDTPRESENTORDER A WHERE A.START_DATE = '@START_DATE') S_BUS　 " . "\r\n";
        $strSql = $strSql . "　ON     S_BUS.BUSYO_CD = BUS.BUSYO_CD　 " . "\r\n";
        $strSql = $strSql . "　LEFT JOIN HDTPRESENTORDER DATA　 " . "\r\n";
        $strSql = $strSql . "　ON     DATA.START_DATE = BASE.START_DATE　 " . "\r\n";
        $strSql = $strSql . "　AND    DATA.ORDER_NO = BASE.ORDER_NO　 " . "\r\n";
        $strSql = $strSql . "　AND    DATA.BUSYO_CD = BUS.BUSYO_CD　 " . "\r\n";
        $strSql = $strSql . "　WHERE  BASE.START_DATE = '@START_DATE'　 " . "\r\n";
        $strSql = $strSql . "　ORDER BY MST.STD_TENPO_DISP_NO" . "\r\n";
        $strSql = $strSql . ",          BUS.BUSYO_CD" . "\r\n";
        $strSql = $strSql . "　,        BASE.ORDER_NO　 " . "\r\n";

        $strSql = str_replace("@START_DATE", $STARTDT, $strSql);

        return $strSql;
    }

    //品名データのＳＱＬ文を取得する
    function getUpdate2SQL($STARTDT)
    {
        $strSql = "";
        $strSql = $strSql . "　UPDATE HDTPRESENTKAKUTEIDATA ";
        $strSql = $strSql . "　SET    KAKUTEI_FLG = '0' ";
        $strSql = $strSql . "　WHERE  START_DATE >= '@STARTDT' ";

        $strSql = str_replace("@STARTDT", $STARTDT, $strSql);

        return $strSql;
    }

    //未出力データのSQL文を取得
    function getSelect1SQL($STARTDT)
    {
        $strSql = "";
        $strSql = $strSql . " SELECT COUNT(START_DATE) CNT ";
        $strSql = $strSql . " FROM   HDTPRESENTORDER ";
        $strSql = $strSql . " WHERE  OUT_FLG = '0' ";
        $strSql = $strSql . " AND    START_DATE = '@STARTDT' ";

        $strSql = str_replace("@STARTDT", $STARTDT, $strSql);

        return $strSql;
    }

    //部署データのＳＱＬ文を取得する
    public function getTenpo($STARTDT)
    {
        return parent::select($this->getTenpoSQL($STARTDT));
    }

    //存在チェックのＳＱＬ文を取得する
    public function getChk($STARTDT)
    {
        return parent::select($this->getChkSQL($STARTDT));
    }

    //追加処理のＳＱＬ文を取得する
    public function getUpdate($STARTDT)
    {
        return parent::insert($this->getUpdateSQL($STARTDT));
    }

    //更新処理のＳＱＬ文を取得する
    public function getInsert($STARTDT)
    {
        return parent::update($this->getInsertSQL($STARTDT));
    }

    //出力ﾌﾗｸﾞを"1"で更新のＳＱＬ文を取得する
    public function getUpdate1($STARTDT)
    {
        return parent::update($this->getUpdate1SQL($STARTDT));
    }

    //出力ﾌﾗｸﾞを"0"で更新のＳＱＬ文を取得する
    public function getUpdate0($STARTDT)
    {
        return parent::update($this->getUpdate0SQL($STARTDT));
    }

    //プレゼント注文データのＳＱＬ文を取得する
    public function getTitle($STARTDT)
    {
        return parent::select($this->getTitleSQL($STARTDT));
    }

    //品名データのＳＱＬ文を取得する
    public function getTotal($STARTDT)
    {
        return parent::select($this->getTotalSQL($STARTDT));
    }

    //宣材注文確定データの更新処理を行う
    public function getMaxOrderNo($STARTDT)
    {
        return parent::select($this->getMaxOrderNoSQL($STARTDT));
    }

    //宣材注文確定データの更新処理を行う
    public function getDetail($STARTDT)
    {
        return parent::select($this->getDetailSQL($STARTDT));
    }

    //展示会宣材注文_集計Excel出力(上)
    public function getUpdate2($STARTDT)
    {
        return parent::update($this->getUpdate2SQL($STARTDT));
    }

    //展示会宣材注文_集計Excel出力(上)
    public function getSelect1($STARTDT)
    {
        return parent::select($this->getSelect1SQL($STARTDT));
    }

}
