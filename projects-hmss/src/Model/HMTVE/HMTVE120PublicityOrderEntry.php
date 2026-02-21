<?php
// 共通クラスの読込み
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE120PublicityOrderEntry extends ClsComDb
{
    //*************************************
    // * SQL文
    //*************************************

    //宣材確定データを取得する
    function getExCheckSQL($NENGETU)
    {
        $strSql = "";
        $strSql = $strSql . " SELECT KAKU.KAKUTEI_FLG             " . "\r\n";
        $strSql = $strSql . " FROM   HDTPUBLICITYKAKUTEIDATA KAKU " . "\r\n";
        $strSql = $strSql . " WHERE  KAKU.IVENT_YM = '@NENGETU'   " . "\r\n";

        $strSql = str_replace("@NENGETU", $NENGETU, $strSql);

        return $strSql;
    }

    //展示会データ取得のＳＱＬ文を取得する
    function getCreatDateExSQL($postdata)
    {
        $strSql = "";
        $strSql = $strSql . " SELECT IVDT.START_DATE                                                    " . "\r\n";
        $strSql = $strSql . " ,      TO_CHAR(TO_DATE(IVDT.START_DATE,'YYYY/MM/DD'),'YYYY/MM/DD') || '～' ||  " . "\r\n";
        //$strSql = $strSql . "        TO_CHAR(TO_DATE(IVDT.END_DATE,'YYYY/MM/DD'),'YYYY/MM/DD') HIDUKE        " . "\r\n";
        //$strSql = $strSql . " ,      IVDT.IVENT_NM                                                      " . "\r\n";
        $strSql = $strSql . "        TO_CHAR(TO_DATE(IVDT.END_DATE,'YYYY/MM/DD'),'YYYY/MM/DD') ||       " . "\r\n";
        $strSql = $strSql . "        '<br/><br/>'  ||  IVDT.IVENT_NM  AS HIDUKE_IVENT_NM                " . "\r\n";
        $strSql = $strSql . " ,      NVL(DATA.ORDER_VAL1,0) ORDER_VAL1                                  " . "\r\n";
        $strSql = $strSql . " ,      NVL(DATA.ORDER_VAL2,0) ORDER_VAL2                                  " . "\r\n";
        $strSql = $strSql . " ,      NVL(DATA.ORDER_VAL3,0) ORDER_VAL3                                  " . "\r\n";
        $strSql = $strSql . " ,      ' ' TARGET                                                          " . "\r\n";
        $strSql = $strSql . " ,      PLIV.BIKOU                                                         " . "\r\n";
        $strSql = $strSql . " ,      TO_CHAR(DATA.CREATE_DATE, 'YYYY/MM/DD hh24:mi:ss')　as CREATE_DATE  " . "\r\n";
        $strSql = $strSql . " ,      DATA.UPD_DATE                                                      " . "\r\n";
        $strSql = $strSql . " FROM   HDTIVENTDATA IVDT                                                  " . "\r\n";
        $strSql = $strSql . " INNER JOIN HDTPUBLICITYIVENT PLIV                                         " . "\r\n";
        $strSql = $strSql . " ON     PLIV.START_DATE = IVDT.START_DATE                                  " . "\r\n";
        $strSql = $strSql . " LEFT JOIN @TABLENM DATA                                                   " . "\r\n";
        $strSql = $strSql . " ON     DATA.START_DATE = IVDT.START_DATE                                  " . "\r\n";
        $strSql = $strSql . " AND    DATA.BUSYO_CD = '@BUSYOCD'                                         " . "\r\n";
        $strSql = $strSql . " WHERE  PLIV.IVENT_YM = '@NENGETU'                                         " . "\r\n";
        $strSql = $strSql . " ORDER BY IVDT.START_DATE                                                  " . "\r\n";

        $strSql = str_replace("@TABLENM", $postdata['TABLENM'], $strSql);
        $strSql = str_replace("@NENGETU", $postdata['NENGETU'], $strSql);
        $strSql = str_replace("@BUSYOCD", $postdata['BUSYOCD'], $strSql);

        return $strSql;
    }

    //展示会ﾍｯﾀﾞｰﾃﾞｰﾀ取得のＳＱＬ文を取得する
    function getExHeaderSQL($NENGETU)
    {
        $strSql = "";
        $strSql = $strSql . " SELECT (GOODS.HINMEI1 || '<br />' || ' @' || GOODS.TANKA1) COL_HED_1 " . "\r\n";
        $strSql = $strSql . " ,      (GOODS.HINMEI2 || '<br />' || ' @' || GOODS.TANKA2) COL_HED_2 " . "\r\n";
        $strSql = $strSql . " ,      (GOODS.HINMEI3 || '<br />' || ' @' || GOODS.TANKA3) COL_HED_3 " . "\r\n";
        $strSql = $strSql . " ,      DECODE(GOODS.HINMEI1,NULL,'0','1') HANDAN_1       " . "\r\n";
        $strSql = $strSql . " ,      DECODE(GOODS.HINMEI2,NULL,'0','1') HANDAN_2       " . "\r\n";
        $strSql = $strSql . " ,      DECODE(GOODS.HINMEI3,NULL,'0','1') HANDAN_3       " . "\r\n";
        $strSql = $strSql . " FROM   HDTPUBLICITYGOODS GOODS                           " . "\r\n";
        $strSql = $strSql . " WHERE  GOODS.IVENT_YM = '@NENGETU'                       " . "\r\n";
        $strSql = str_replace("@NENGETU", $NENGETU, $strSql);

        return $strSql;
    }

    //回収期限ﾃﾞｰﾀを取得する
    function getDateSQL($NENGETU)
    {
        $strSql = "";
        $strSql = $strSql . " SELECT KIGEN.KIGEN_YM              " . "\r\n";
        $strSql = $strSql . " FROM   HDTPUBLICITYTERM KIGEN      " . "\r\n";
        $strSql = $strSql . " WHERE  KIGEN.IVENT_YM = '@NENGETU' " . "\r\n";
        $strSql = str_replace("@NENGETU", $NENGETU, $strSql);

        return $strSql;
    }

    //日付のＳＱＬ文を取得する
    function getYMSQL()
    {
        $strSql = "";
        $strSql = $strSql . " SELECT MAX(IVENT_YM) IVENTMAX   " . "\r\n";
        $strSql = $strSql . " ,      MIN(IVENT_YM) IVENTMIN   " . "\r\n";
        $strSql = $strSql . " ,       TO_CHAR(ADD_MONTHS(SYSDATE,1), 'YYYY-MM-DD') TD " . "\r\n";
        $strSql = $strSql . " FROM   HDTPUBLICITYIVENT        " . "\r\n";

        return $strSql;
    }

    //店舗名のＳＱＬ文を取得する
    function getShopNMSQL($BusyoCD)
    {

        $strSql = "";
        $strSql = $strSql . " SELECT MST.BUSYO_CD                                               " . "\r\n";
        $strSql = $strSql . " ,      MST.BUSYO_RYKNM                                            " . "\r\n";
        $strSql = $strSql . " FROM HBUSYO MST                                                   " . "\r\n";
        $strSql = $strSql . " INNER JOIN  (SELECT BUSYO_CD                                      " . "\r\n";
        $strSql = $strSql . "              ,      (CASE WHEN HDT_TENPO_CD IS NOT NULL           " . "\r\n";
        $strSql = $strSql . "                      THEN HDT_TENPO_CD ELSE BUSYO_CD END) V_TENPO " . "\r\n";
        $strSql = $strSql . "              FROM   HBUSYO) BUS                                   " . "\r\n";
        $strSql = $strSql . " ON     MST.BUSYO_CD = BUS.V_TENPO                                 " . "\r\n";
        $strSql = $strSql . " WHERE  BUS.BUSYO_CD = '@BUSYOCD'                                  " . "\r\n";

        $strSql = str_replace("@BUSYOCD", $BusyoCD, $strSql);

        return $strSql;
    }

    //ワーク宣材注文データを削除する
    function getWorkDelSQL($IVENTYM, $BUSYOCD)
    {
        $strSql = "";
        $strSql = $strSql . " DELETE FROM WK_HDTPUBLICITYDATA " . "\r\n";
        $strSql = $strSql . " WHERE  IVENT_YM = '@IVENTYM'    " . "\r\n";
        $strSql = $strSql . " AND    BUSYO_CD = '@BUSYOCD'    " . "\r\n";
        $strSql = str_replace("@IVENTYM", $IVENTYM, $strSql);
        $strSql = str_replace("@BUSYOCD", $BUSYOCD, $strSql);

        return $strSql;
    }

    //ワーク宣材注文データに登録のＳＱＬ文を取得する
    function getWorkInsertSQL($postdata)
    {
        $strSql = "";
        $strSql = $strSql . " INSERT INTO WK_HDTPUBLICITYDATA " . "\r\n";
        $strSql = $strSql . " (  IVENT_YM , BUSYO_CD , START_DATE , ORDER_VAL1 , ORDER_VAL2 , ORDER_VAL3   " . "\r\n";
        $strSql = $strSql . "  , OUT_FLG , CREATE_DATE , UPD_DATE , UPD_SYA_CD , UPD_PRG_ID , UPD_CLT_NM ) " . "\r\n";
        $strSql = $strSql . " VALUES                                                                       " . "\r\n";
        $strSql = $strSql . " (  '@IVENT_YM' , '@BUSYO_CD'                                               " . "\r\n";
        $strSql = $strSql . " , '@START_DATE' " . "\r\n";
        $strSql = $strSql . " , '@ORDER_VAL1' , '@ORDER_VAL2' , '@ORDER_VAL3' " . "\r\n";
        $strSql = $strSql . "    , '0'    " . "\r\n";

        if (!array_key_exists("CREATE_DATE", $postdata) || $postdata['CREATE_DATE'] == null || $postdata['CREATE_DATE'] == "") {
            $strSql = $strSql . " , SYSDATE " . "\r\n";
        } else {
            $strSql = $strSql . " , TO_DATE('@CREATE_DATE', 'YYYY/MM/DD hh24:mi:ss') " . "\r\n";
            $strSql = str_replace("@CREATE_DATE", $postdata['CREATE_DATE'], $strSql);
        }
        if (!array_key_exists("UPDATEDATE", $postdata) || $postdata['UPDATEDATE'] == null || $postdata['UPDATEDATE'] == "") {
            $strSql = $strSql . " , SYSDATE " . "\r\n";
        } else {
            $strSql = $strSql . " , TO_DATE('@UPD_DATE', 'YYYY/MM/DD hh24:mi:ss') " . "\r\n";
            $strSql = str_replace("@UPD_DATE", $postdata['UPDATEDATE'], $strSql);
        }

        $strSql = $strSql . " , '@UPD_SYA_CD' , 'PublicityO' , '@UPD_CLT_NM' ) " . "\r\n";

        $strSql = str_replace("@IVENT_YM", $postdata['IVENT_YM'], $strSql);
        $strSql = str_replace("@BUSYO_CD", $postdata['BUSYO_CD'], $strSql);
        $strSql = str_replace("@START_DATE", $postdata['START_DATE'], $strSql);
        $strSql = str_replace("@ORDER_VAL1", $this->space2zero($postdata['ORDER_VAL1']), $strSql);
        $strSql = str_replace("@ORDER_VAL2", $this->space2zero($postdata['ORDER_VAL2']), $strSql);
        $strSql = str_replace("@ORDER_VAL3", $this->space2zero($postdata['ORDER_VAL3']), $strSql);
        $strSql = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSql);
        $strSql = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSql);

        return $strSql;
    }

    function space2zero($str)
    {
        try {
            if (trim($str) == "") {
                return '0';
            } else {
                return $str;
            }
        } catch (\Exception $e) {
            return '0';
        }
    }

    //宣材確定データを取得する
    public function getExCheck($NENGETU)
    {
        return parent::select($this->getExCheckSQL($NENGETU));
    }

    //展示会データ取得を取得する
    public function getCreatDateEx($postdata)
    {
        return parent::select($this->getCreatDateExSQL($postdata));
    }

    //展示会ﾍｯﾀﾞｰﾃﾞｰﾀ取得を取得する
    public function getExHeader($NENGETU)
    {
        return parent::select($this->getExHeaderSQL($NENGETU));
    }

    //回収期限ﾃﾞｰﾀを取得する
    public function getDate($NENGETU)
    {
        return parent::select($this->getDateSQL($NENGETU));
    }

    //日付を取得する
    public function getYM()
    {
        return parent::select($this->getYMSQL());
    }

    //店舗名を取得する
    public function getShopNM($BusyoCD)
    {
        return parent::select($this->getShopNMSQL($BusyoCD));
    }

    //ワーク宣材注文データを削除する
    public function getWorkDel($IVENTYM, $BUSYOCD)
    {
        return parent::delete($this->getWorkDelSQL($IVENTYM, $BUSYOCD));
    }

    //ワーク宣材注文データに登録を取得する
    public function getWorkInsert($postdata)
    {
        return parent::insert($this->getWorkInsertSQL($postdata));
    }

}

