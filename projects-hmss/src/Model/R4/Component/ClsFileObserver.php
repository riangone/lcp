<?php
// 共通クラスの読込み

namespace App\Model\R4\Component;

// 共通クラスの読込み
use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：ClsFileObserver
// * 処理説明：共通関数
//*************************************

class ClsFileObserver extends ClsComDb
{
    // 20131004 kamei add Start
    // 解放が必要な変数をメンバーに設定
    protected $conn_orl = "";
    protected $Sel_Array = "";
    // 20131004 kamei add end

    public function fncUpdTrnTblUpdateSQL($strId, $strStartDate, $strStartTime, $strClientNM, $intStep, $intState, $strMessage, $strDBLink)
    {
        $strSQL = "";

        //UPDATE文
        $strSQL .= "UPDATE HFTS_TRANSFER_LIST@DBLINK ";
        $strSQL .= "   SET END_DATE = TO_CHAR(SYSDATE,'YYYYMMDD') ";
        $strSQL .= ",       END_TIME = TO_CHAR(SYSDATE,'HH24MISS') ";
        $strSQL .= ",       STEP = '@step' ";
        $strSQL .= ",       STATE = '@state' ";
        $strSQL .= ",       MESSAGE = '@message' ";
        $strSQL .= " WHERE ID = '@id' ";
        $strSQL .= "   AND CLIENT_NAME = '@syain_cd' ";
        $strSQL .= "   AND START_DATE = '@start_date' ";
        $strSQL .= "   AND START_TIME = '@start_time' ";

        //条件を設定
        $strSQL = str_replace("@id", $strId, $strSQL);
        $strSQL = str_replace("@syain_cd", $strClientNM, $strSQL);
        $strSQL = str_replace("@start_date", $strStartDate, $strSQL);
        $strSQL = str_replace("@start_time", $strStartTime, $strSQL);
        $strSQL = str_replace("@step", $intStep, $strSQL);
        $strSQL = str_replace("@state", $intState, $strSQL);
        $strSQL = str_replace("@message", $strMessage, $strSQL);
        $strSQL = str_replace("@DBLINK", $strDBLink, $strSQL);

        return $strSQL;
    }

    public function fncUpdTrnTblInsertSQL($strId, $strStartDate, $strStartTime, $strClientNM, $intStep, $intState, $strMessage, $strDBLink)
    {
        $strSQL = "";

        //INSERT文
        $strSQL .= "INSERT INTO HFTS_TRANSFER_LIST@DBLINK ";
        $strSQL .= "( ID ";
        $strSQL .= ", START_DATE ";
        $strSQL .= ", START_TIME ";
        $strSQL .= ", CLIENT_NAME ";
        $strSQL .= ", END_DATE ";
        $strSQL .= ", END_TIME ";
        $strSQL .= ", STEP ";
        $strSQL .= ", STATE ";
        $strSQL .= ", KAKUNIN ";
        $strSQL .= ", MESSAGE ";
        $strSQL .= ", PARA1 ";
        $strSQL .= ", PARA2 ";
        $strSQL .= ", PARA3 ";
        $strSQL .= ";";
        $strSQL .= " VALUES ";
        $strSQL .= "( '@id' ";
        $strSQL .= ", '@start_date' ";
        $strSQL .= ", '@start_time' ";
        $strSQL .= ", '@syain_cd' ";
        $strSQL .= ", TO_CHAR(SYSDATE,'YYYYMMDD'; ";
        $strSQL .= ", TO_CHAR(SYSDATE,'HH24MISS'; ";
        $strSQL .= ", '@step' ";
        $strSQL .= ", '@state' ";
        $strSQL .= ", '0' ";
        $strSQL .= ", '@message' ";
        $strSQL .= ", NULL ";
        $strSQL .= ", NULL ";
        $strSQL .= ", NULL ";
        $strSQL .= ")";

        //条件を設定
        $strSQL = str_replace("@id", $strId, $strSQL);
        $strSQL = str_replace("@syain_cd", $strClientNM, $strSQL);
        $strSQL = str_replace("@start_date", $strStartDate, $strSQL);
        $strSQL = str_replace("@start_time", $strStartTime, $strSQL);
        $strSQL = str_replace("@step", $intStep, $strSQL);
        $strSQL = str_replace("@state", $intState, $strSQL);
        $strSQL = str_replace("@message", $strMessage, $strSQL);
        $strSQL = str_replace("@DBLINK", $strDBLink, $strSQL);

        return $strSQL;
    }

    public function Fnc_GetSysDateSQL()
    {
        $strSQL = "";

        $strSQL .= "SELECT TO_CHAR (SYSDATE,'YYYY-MM-DD HH24:MI:SS') SYS_DATE FROM DUAL";

        return $strSQL;
    }

    public function fncControlCheckSQL()
    {
        $strSQL = "";
        $strSQL .= "";
        $strSQL .= " SELECT *";
        $strSQL .= " FROM   M_CONTROL";

        return $strSQL;
    }

    public function fncParaSetSQL($strId, $strStartDate, $strStartTime, $strClientNM)
    {
        $strSQL = "";
        //条件取得用SQL
        $strSQL .= "SELECT ID" . "\r\n";
        $strSQL .= ",      PARA1" . "\r\n";
        $strSQL .= ",      PARA2" . "\r\n";
        $strSQL .= ",      PARA3" . "\r\n";
        $strSQL .= "FROM   HFTS_TRANSFER_LIST" . "\r\n";
        $strSQL .= "WHERE  ID = '@ID'" . "\r\n";
        $strSQL .= "AND    START_DATE = '@STARTDATE'" . "\r\n";
        $strSQL .= "AND    START_TIME = '@STARTTIME'" . "\r\n";
        $strSQL .= "AND    CLIENT_NAME = '@CLIENTNM'" . "\r\n";

        //パラメータに値を設定
        $strSQL = str_replace("@ID", $strId, $strSQL);
        $strSQL = str_replace("@STARTDATE", $strStartDate, $strSQL);
        $strSQL = str_replace("@STARTTIME", $strStartTime, $strSQL);
        $strSQL = str_replace("@CLIENTNM", $strClientNM, $strSQL);
        return $strSQL;
    }

    public function fncLockStateUpdSQL($strDLKind, $strState)
    {
        $strSQL = "";

        $strSQL .= " UPDATE RCT_DATARECEP" . "\r\n";
        $strSQL .= " SET    ACT_STATE = '@ACT_STATE'" . "\r\n";
        //''$strSQL .= " WHERE  TABLE_ID = '@TABLE_ID'" & vbCrLf)
        $strSQL .= " WHERE  TABLE_ID IN " . "\r\n";
        $strSQL .= "        (SELECT TABLE_ID FROM RCT_DL_GROUP" . "\r\n";
        $strSQL .= "         WHERE  DL_KIND = '@DL_KIND')" . "\r\n";

        $strSQL = str_replace("@DL_KIND", $strDLKind, $strSQL);
        $strSQL = str_replace("@ACT_STATE", $strState, $strSQL);

        return $strSQL;
    }

    public function fcLockCheckSQL($strTableID)
    {
        $strSQL = "";
        //ロック状況チェックＳＱＬ
        $strSQL .= " SELECT ACT_STATE" . "\r\n";
        $strSQL .= " ,      TO_CHAR(ACT_DT,'YYYY/MM/DD HH24:MI:SS') ACT_DT" . "\r\n";
        $strSQL .= " FROM   RCT_DATARECEP" . "\r\n";
        $strSQL .= " WHERE  TABLE_ID = '@TABLE_ID'" . "\r\n";

        $strSQL = str_replace("@TABLE_ID", $strTableID, $strSQL);
        return $strSQL;
    }

    public function fcLockCheckDBLinkSQL($strTableID, $strDBLink)
    {
        $strSQL = "";
        //ロック状況チェックＳＱＬ
        $strSQL .= " SELECT ACT_STATE" . "\r\n";
        $strSQL .= " ,      TO_CHAR(ACT_DT,'YYYY/MM/DD HH24:MI:SS') ACT_DT" . "\r\n";
        $strSQL .= " FROM   RCT_DATARECEP" . $strDBLink . "\r\n";
        $strSQL .= " WHERE  TABLE_ID = '@TABLE_ID'" . "\r\n";
        $strSQL = str_replace("@TABLE_ID", $strTableID, $strSQL);
        return $strSQL;
    }

    //*************************************
    // * 公開メソッド
    //*************************************

    public function fncUpdTrnTbl($strId, $strStartDate, $strStartTime, $strClientNM, $intStep, $intState, &$strMessage, $blnUpd = true, $strDBLink = "")
    {
        if ($blnUpd) {
            return parent::update($this->fncUpdTrnTblUpdateSQL($strId, $strStartDate, $strStartTime, $strClientNM, $intStep, $intState, $strMessage, $strDBLink));
        } else {
            return parent::insert($this->fncUpdTrnTblInsertSQL($strId, $strStartDate, $strStartTime, $strClientNM, $intStep, $intState, $strMessage, $strDBLink));
        }
    }

    public function Fnc_GetSysDate()
    {
        return parent::select($this->Fnc_GetSysDateSQL());
    }

    public function fncControlCheck()
    {
        return parent::select($this->fncControlCheckSQL());
    }

    public function fncParaSet($strId, $strStartDate, $strStartTime, $strClientNM)
    {
        return parent::select($this->fncParaSetSQL($strId, $strStartDate, $strStartTime, $strClientNM));
    }

    public function fncLockStateUpd($strDLKind, $strState)
    {
        return parent::update($this->fncLockStateUpdSQL($strDLKind, $strState));
    }

    public function fcLockCheck($strTableID)
    {
        return parent::select($this->fcLockCheckSQL($strTableID));
    }

    public function fcLockCheckDBLink($strTableID, $strDBLink)
    {
        return parent::select($this->fcLockCheckDBLinkSQL($strTableID, $strDBLink));
    }

}
