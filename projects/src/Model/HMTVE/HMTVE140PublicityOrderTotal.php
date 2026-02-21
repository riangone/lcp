<?php
// 共通クラスの読込み
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE140PublicityOrderTotal extends ClsComDb
{
    //*************************************
    // * SQL文
    //*************************************
    //コンボリストを設定するSQL文を取得
    function setDropDownListSQL()
    {
        $strSql = "";
        $strSql = $strSql . "SELECT MIN(IVENT_YM) IVENTMIN ";
        $strSql = $strSql . "	,      MAX(IVENT_YM) IVENTMAX ";
        $strSql = $strSql . ",      TO_CHAR(ADD_MONTHS(SYSDATE,1), 'YYYY-MM-DD') TD	 ";
        $strSql = $strSql . " FROM   HDTPUBLICITYIVENT ";

        return $strSql;
    }

    //店舗コード、店舗名を抽出するSQL文を取得
    function shopSQL($IVENTYM)
    {
        $strSql = "";
        $strSql = $strSql . " SELECT DT.BUSYO_CD ";
        $strSql = $strSql . " ,      MST.BUSYO_RYKNM ";
        $strSql = $strSql . " FROM   HDTPUBLICITYDATA DT ";
        $strSql = $strSql . ", HBUSYO MST ";
        $strSql = $strSql . " INNER JOIN  (SELECT BUSYO_CD ";
        $strSql = $strSql . "             , (CASE WHEN HDT_TENPO_CD IS NOT NULL ";
        $strSql = $strSql . "                THEN HDT_TENPO_CD ELSE BUSYO_CD END) V_TENPO ";
        $strSql = $strSql . "                FROM HBUSYO) BUS ";
        $strSql = $strSql . " ON     MST.BUSYO_CD = BUS.V_TENPO ";
        $strSql = $strSql . " WHERE  DT.IVENT_YM = '@IVENTYM' ";
        $strSql = $strSql . " AND    BUS.BUSYO_CD = DT.BUSYO_CD ";
        $strSql = $strSql . " GROUP BY  DT.BUSYO_CD ";
        $strSql = $strSql . " ,         MST.BUSYO_RYKNM ";
        $strSql = $strSql . " ,         MST.HDT_TENPO_DISP_NO ";
        $strSql = $strSql . " ORDER BY MST.HDT_TENPO_DISP_NO ";

        $strSql = str_replace("@IVENTYM", $IVENTYM, $strSql);

        return $strSql;
    }

    //追加処理するSQL文を取得
    function insertSQL($IVENTYM)
    {
        $strSql = "";
        $strSql = $strSql . "	INSERT INTO HDTPUBLICITYKAKUTEIDATA	 ";
        $strSql = $strSql . "	(      IVENT_YM		 ";
        $strSql = $strSql . "	,      KAKUTEI_FLG	 ";
        $strSql = $strSql . "	,      CREATE_DATE	 ";
        $strSql = $strSql . "	,      UPD_DATE	     ";
        $strSql = $strSql . "	,      UPD_SYA_CD	 ";
        $strSql = $strSql . "	,      UPD_PRG_ID	 ";
        $strSql = $strSql . "	,      UPD_CLT_NM	 ";
        $strSql = $strSql . "	)    VALUES     (	 ";
        $strSql = $strSql . "	       '@IVENTYM'	 ";
        $strSql = $strSql . "	,      '1'	         ";
        $strSql = $strSql . "	,      SYSDATE	     ";
        $strSql = $strSql . "	,      SYSDATE	     ";
        $strSql = $strSql . "	,      '@UPD_SYA_CD' ";
        $strSql = $strSql . "	,      'PubTotal'    ";
        $strSql = $strSql . "	,      '@UPD_CLT_NM' ";
        $strSql = $strSql . "	)	 ";

        $strSql = str_replace("@IVENTYM", $IVENTYM, $strSql);
        $strSql = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSql);
        $strSql = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSql);

        return $strSql;
    }

    //更新処理するSQL文を取得
    function updateSQL($IVENTYM)
    {
        $strSql = "";
        $strSql = $strSql . "	UPDATE HDTPUBLICITYKAKUTEIDATA		 ";
        $strSql = $strSql . "	SET    KAKUTEI_FLG = '1'	 ";
        $strSql = $strSql . "	WHERE  IVENT_YM = '@IVENTYM'	 ";

        $strSql = str_replace("@IVENTYM", $IVENTYM, $strSql);

        return $strSql;
    }

    //明細データのSQL文を取得
    function SQL1($NENGETU)
    {
        $strSql = "";
        $strSql = $strSql . " SELECT BUS.BUSYO_CD ";
        $strSql = $strSql . " ,      BUS.BUSYO_RYKNM ";
        $strSql = $strSql . " ,      TO_CHAR(TO_DATE(IVDT.START_DATE,'YYYY/MM/DD'),'MM\"月\"DD\"日\"') || '～' || TO_CHAR(TO_DATE(IVDT.END_DATE,'YYYY/MM/DD'),'MM\"月\"DD\"日\"') HIDUKE ";
        $strSql = $strSql . " ,      SUM(NVL(DATA.ORDER_VAL1,0)) ORDER1";
        $strSql = $strSql . " ,      SUM(NVL(DATA.ORDER_VAL2,0)) ORDER2 ";
        $strSql = $strSql . " ,      SUM(NVL(DATA.ORDER_VAL3,0)) ORDER3 ";
        $strSql = $strSql . " ,      PLIV.BIKOU ";
        $strSql = $strSql . " FROM   HDTIVENTDATA IVDT ";
        $strSql = $strSql . " INNER JOIN HDTPUBLICITYIVENT PLIV ";
        $strSql = $strSql . " ON     PLIV.START_DATE = IVDT.START_DATE ";

        $strSql = $strSql . " INNER JOIN  (SELECT BUSYO_CD ";
        $strSql = $strSql . "              ,      BUSYO_RYKNM ";
        $strSql = $strSql . "              ,      (CASE WHEN HDT_TENPO_CD IS NOT NULL ";
        $strSql = $strSql . "                          THEN HDT_TENPO_CD ELSE BUSYO_CD END) V_TENPO ";
        $strSql = $strSql . "              ,      NVL(HDT_TENPO_DISP_NO,999) HDT_TENPO_DISP_NO  ";
        $strSql = $strSql . "             FROM HBUSYO) BUS ";
        $strSql = $strSql . " ON     BUS.V_TENPO IS NOT NULL ";

        $strSql = $strSql . " INNER JOIN ";
        $strSql = $strSql . "        (SELECT DISTINCT BUSYO_CD FROM HDTPUBLICITYDATA A WHERE A.IVENT_YM = '@NENGETU') S_BUS ";
        $strSql = $strSql . " ON     S_BUS.BUSYO_CD = BUS.BUSYO_CD ";
        $strSql = $strSql . " INNER JOIN HDTPUBLICITYDATA DATA ";
        $strSql = $strSql . " ON     DATA.START_DATE = IVDT.START_DATE ";
        $strSql = $strSql . " AND    DATA.BUSYO_CD = BUS.BUSYO_CD ";
        $strSql = $strSql . " WHERE  PLIV.IVENT_YM = '@NENGETU' ";
        $strSql = $strSql . " GROUP BY BUS.BUSYO_RYKNM ";
        $strSql = $strSql . " ,        BUS.HDT_TENPO_DISP_NO ";
        $strSql = $strSql . " ,        IVDT.START_DATE ";
        $strSql = $strSql . " ,        IVDT.END_DATE ";
        $strSql = $strSql . " ,        PLIV.BIKOU ";
        $strSql = $strSql . "  ,        BUS.BUSYO_CD ";
        $strSql = $strSql . "  ORDER BY   BUS.BUSYO_CD ";
        $strSql = $strSql . "  ,        IVDT.START_DATE ";

        $strSql = str_replace("@NENGETU", $NENGETU, $strSql);

        return $strSql;
    }

    //未出力データが存在しないかチェックする
    function checkInputSQL($IVENTYM)
    {
        $strSql = "";
        $strSql = $strSql . "	SELECT COUNT(IVENT_YM) CNT		 ";
        $strSql = $strSql . "	FROM   HDTPUBLICITYDATA		 ";
        $strSql = $strSql . "	WHERE  OUT_FLG = '0'	 ";
        $strSql = $strSql . "	AND    IVENT_YM = '@IVENTYM'	 ";

        $strSql = str_replace("@IVENTYM", $IVENTYM, $strSql);

        return $strSql;
    }

    //存在チェック
    function checkExistSQL($IVENTYM)
    {
        $strSql = "";
        $strSql = $strSql . "	SELECT IVENT_YM		  ";
        $strSql = $strSql . "	FROM   HDTPUBLICITYKAKUTEIDATA	  ";
        $strSql = $strSql . " WHERE  IVENT_YM = '@IVENTYM'  ";

        $strSql = str_replace("@IVENTYM", $IVENTYM, $strSql);

        return $strSql;
    }

    //展示会宣材データの出力ﾌﾗｸﾞを"1"で更新する
    function updateFlgSQL($IVENTYM)
    {
        $strSql = "";
        $strSql = $strSql . "	UPDATE HDTPUBLICITYDATA		 ";
        $strSql = $strSql . "	SET    OUT_FLG = '1'	 ";
        $strSql = $strSql . "	WHERE  IVENT_YM >= '@IVENTYM'	 ";

        $strSql = str_replace("@IVENTYM", $IVENTYM, $strSql);

        return $strSql;
    }

    //宣材注文確定データの更新処理を行う
    function updateConfirmSQL($IVENTYM)
    {
        $strSql = "";
        $strSql = $strSql . "	UPDATE HDTPUBLICITYKAKUTEIDATA		 ";
        $strSql = $strSql . "	SET    KAKUTEI_FLG = '0'	 ";
        $strSql = $strSql . "	WHERE  IVENT_YM >= '@IVENTYM'	 ";

        $strSql = str_replace("@IVENTYM", $IVENTYM, $strSql);

        return $strSql;
    }

    //ロック解除を行う
    function lockReleaseSQL($IVENTYM)
    {
        $strSql = "";
        $strSql = $strSql . "	UPDATE HDTPUBLICITYKAKUTEIDATA		 ";
        $strSql = $strSql . "	SET    KAKUTEI_FLG = '0'	 ";
        $strSql = $strSql . " WHERE  KAKUTEI_FLG = 1 ";
        $strSql = $strSql . " AND    IVENT_YM = '@IVENTYM' ";

        $strSql = str_replace("@IVENTYM", $IVENTYM, $strSql);

        return $strSql;
    }

    //展示会宣材注文_集計Excel出力(上)
    function createExcelDataTable1SQL($IVENTYM)
    {
        $strSql = "";
        $strSql = $strSql . " SELECT GOODS.HINMEI1 ";
        $strSql = $strSql . " ,      GOODS.HINMEI2 ";
        $strSql = $strSql . " ,      GOODS.HINMEI3 ";
        $strSql = $strSql . " FROM   HDTPUBLICITYGOODS GOODS ";
        $strSql = $strSql . " WHERE  GOODS.IVENT_YM = '@NENGETU' ";

        $strSql = str_replace("@NENGETU", $IVENTYM, $strSql);

        return $strSql;
    }

    //店舗コード、店舗名を抽出する
    public function shop($IVENTYM)
    {
        return parent::select($this->shopSQL($IVENTYM));
    }

    //追加処理
    public function insert($IVENTYM)
    {
        return parent::insert($this->insertSQL($IVENTYM));
    }

    //更新処理
    public function update($IVENTYM)
    {
        return parent::update($this->updateSQL($IVENTYM));
    }

    //明細データ
    public function SQL($NENGETU)
    {
        return parent::select($this->SQL1($NENGETU));
    }

    //コンボリストを設定する
    public function setDropDownList()
    {
        return parent::select($this->setDropDownListSQL());
    }

    //未出力データが存在しないかチェックする
    public function checkInput($IVENTYM)
    {
        return parent::select($this->checkInputSQL($IVENTYM));
    }

    //存在チェック
    public function checkExist($IVENTYM)
    {
        return parent::select($this->checkExistSQL($IVENTYM));
    }

    //展示会宣材データの出力ﾌﾗｸﾞを"1"で更新する
    public function updateFlg($IVENTYM)
    {
        return parent::update($this->updateFlgSQL($IVENTYM));
    }

    //宣材注文確定データの更新処理を行う
    public function updateConfirm($IVENTYM)
    {
        return parent::update($this->updateConfirmSQL($IVENTYM));
    }

    //宣材注文確定データの更新処理を行う
    public function lockRelease($IVENTYM)
    {
        return parent::update($this->lockReleaseSQL($IVENTYM));
    }

    //展示会宣材注文_集計Excel出力(上)
    public function createExcelDataTable1($IVENTYM)
    {
        return parent::select($this->createExcelDataTable1SQL($IVENTYM));
    }
}
