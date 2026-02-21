<?php
/**
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug             内容                               担当
 * YYYYMMDD           #ID                     XXXXXX                            FCSDL
 * 20150728           #2002                  閉鎖日が「999999」が設定されているとき、更新ができない       FANZHENGZHOU
 * --------------------------------------------------------------------------------------------
 */
// 共通クラスの読込み
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmBusyoMstEdit extends ClsComDb
{
    //--execute sql--
    public function fncExistsCheck($BUSYO_CD)
    {
        $strSql = $this->fncExistsCheck_sql($BUSYO_CD);
        return parent::select($strSql);
    }

    public function fncInsertBusyo($arrData)
    {
        $strSql = $this->fncInsertBusyo_sql($arrData);
        return parent::insert($strSql);
    }

    public function fncBusyoSet($BUSYO_CD)
    {
        $strSql = $this->fncBusyoSet_sql($BUSYO_CD);
        return parent::select($strSql);
    }

    public function fncUpdateBusyo($arrData)
    {
        $strSql = $this->fncUpdateBusyo_sql($arrData);
        return parent::update($strSql);
    }

    //--sql--
    public function fncExistsCheck_sql($BUSYO_CD)
    {
        $sqlstr = "";
        $sqlstr .= "SELECT BUSYO_CD ";
        $sqlstr .= "FROM   HBUSYO ";
        $sqlstr .= "WHERE  BUSYO_CD = '@BUSYO_CD' ";
        //  strSQL.Replace("@BUSYO_CD", Me.txtBusyoCD.Text.TrimEnd)
        $sqlstr = str_replace("@BUSYO_CD", $BUSYO_CD, $sqlstr);
        return $sqlstr;
    }

    public function fncInsertBusyo_sql($arrData)
    {
        $sqlstr = "";
        $sqlstr .= "INSERT INTO HBUSYO\r\n";
        $sqlstr .= "(	       BUSYO_CD\r\n";
        $sqlstr .= ",	       BUSYO_NM\r\n";
        $sqlstr .= ",	       BUSYO_KANANM\r\n";
        $sqlstr .= ",	       BUSYO_RYKNM\r\n";
        $sqlstr .= ",	       KKR_BUSYO_CD\r\n";
        $sqlstr .= ",	       CNV_BUSYO_CD\r\n";
        $sqlstr .= ",           BUSYO_KB\r\n";
        $sqlstr .= ",           TENPO_CD\r\n";
        $sqlstr .= ",	       SYUKEI_KB\r\n";
        $sqlstr .= ",           TORIKOMI_BUSYO_KB\r\n";
        $sqlstr .= ",	       MANEGER_CD\r\n";
        $sqlstr .= ",	       START_DATE\r\n";
        $sqlstr .= ",	       END_DATE\r\n";
        $sqlstr .= ",	       DSP_SEQNO\r\n";
        $sqlstr .= ",	       PRN_KB1\r\n";
        $sqlstr .= ",	       PRN_KB2\r\n";
        $sqlstr .= ",	       PRN_KB3\r\n";
        $sqlstr .= ",	       PRN_KB4\r\n";
        $sqlstr .= ",	       PRN_KB5\r\n";
        $sqlstr .= ",           PRN_KB6\r\n";
        $sqlstr .= ",           HKNSYT_DSP_KB\r\n";
        $sqlstr .= ",           JISSEKITTL_KB\r\n";
        $sqlstr .= ",           UPD_DATE\r\n";
        $sqlstr .= ",           CREATE_DATE\r\n";
        $sqlstr .= ",           UPD_SYA_CD\r\n";
        $sqlstr .= ",           UPD_PRG_ID\r\n";
        $sqlstr .= ",           UPD_CLT_NM\r\n";

        $sqlstr .= ")";

        $sqlstr .= "VALUES";
        $sqlstr .= "(           '@txtBusyoCD'\r\n";
        $sqlstr .= ",           '@txtBusyoNM'\r\n";
        $sqlstr .= ",           '@txtBusyoKN'\r\n";
        $sqlstr .= ",           '@txtBusyoRK'\r\n";
        $sqlstr .= ",           '@txtKkrBusyoCD'\r\n";
        $sqlstr .= ",           '@txtCnvBusyoCD'\r\n";
        $sqlstr .= ",           '@txtBusyoKB'\r\n";
        $sqlstr .= ",           '@txtTenpoCD'\r\n";
        $sqlstr .= ",           '@txtSyukeiKB'\r\n";
        $sqlstr .= ",           '@txtTorikomiBusyoKB'\r\n";
        $sqlstr .= ",           '@txtManeger_CD'\r\n";
        $sqlstr .= ",           '@txtStartDate'\r\n";
        $sqlstr .= ",           '@txtEndDate'\r\n";
        $sqlstr .= ",           '@txtDsp_SeqNO'\r\n";
        $sqlstr .= ",           '@txtPRN_KB1'\r\n";
        $sqlstr .= ",           '@txtPRN_KB2'\r\n";
        $sqlstr .= ",           '@txtPRN_KB3'\r\n";
        $sqlstr .= ",           '@txtPRN_KB4'\r\n";
        $sqlstr .= ",           '@txtPRN_KB5'\r\n";
        $sqlstr .= ",           '@txtPRN_KB6'\r\n";
        $sqlstr .= ",           '@txtHknSytDspKB'\r\n";
        $sqlstr .= ",           '@txtJissekiOutFlg'\r\n";
        $sqlstr .= ",           SYSDATE\r\n";
        $sqlstr .= ",           SYSDATE\r\n";
        $sqlstr .= ",           '@UPDUSER'\r\n";
        $sqlstr .= ",           '@UPDAPP'\r\n";
        $sqlstr .= ",           '@UPDCLT'\r\n";
        $sqlstr .= ")";
        foreach ($arrData as $key => $value) {
            //20150728 #2002 fanzhengzhou add s.
            switch ($key) {
                case 'START_DATE':
                    $sqlstr = str_replace("@" . $key, rtrim($value) == "" ? "" : str_replace('/', '', rtrim($value)), $sqlstr);
                    break;
                case 'END_DATE':
                    $sqlstr = str_replace("@" . $key, rtrim($value) == "" ? "999999" : str_replace('/', '', rtrim($value)), $sqlstr);
                    break;
                default:
                    $sqlstr = str_replace("@" . $key, $value, $sqlstr);
                    break;
            }
            //20150728 #2002 fanzhengzhou add e.
        }
        $sqlstr = str_replace("@UPDUSER", $this->GS_LOGINUSER['strUserID'], $sqlstr);
        $sqlstr = str_replace("@UPDAPP", "BusyoMstEdit", $sqlstr);
        $sqlstr = str_replace("@UPDCLT", $this->GS_LOGINUSER['strClientNM'], $sqlstr);
        return $sqlstr;
    }

    public function fncBusyoSet_sql($BUSYO_CD)
    {
        $sqlstr = "";
        $sqlstr .= "SELECT	BUSYO_CD";
        $sqlstr .= ",	    BUSYO_NM";
        $sqlstr .= ",	    BUSYO_KANANM";
        $sqlstr .= ",	    BUSYO_RYKNM";
        $sqlstr .= ",	    KKR_BUSYO_CD";
        $sqlstr .= ",	    CNV_BUSYO_CD";
        $sqlstr .= ",        BUSYO_KB";
        $sqlstr .= ",        TENPO_CD";
        $sqlstr .= ",	    SYUKEI_KB";
        $sqlstr .= ",	    MANEGER_CD";
        $sqlstr .= ",	    START_DATE";
        $sqlstr .= ",	    END_DATE";
        $sqlstr .= ",	    DSP_SEQNO";
        $sqlstr .= ",	    PRN_KB1";
        $sqlstr .= ",	    PRN_KB2";
        $sqlstr .= ",	    PRN_KB3";
        $sqlstr .= ",	    PRN_KB4";
        $sqlstr .= ",	    PRN_KB5";
        $sqlstr .= ",        PRN_KB6";
        $sqlstr .= ",        HKNSYT_DSP_KB";
        $sqlstr .= ",        TORIKOMI_BUSYO_KB";
        $sqlstr .= ",        JISSEKITTL_KB ";
        $sqlstr .= "FROM    HBUSYO ";
        $sqlstr .= "WHERE   BUSYO_CD = '@BUSYO_CD'";
        $sqlstr = str_replace("@BUSYO_CD", $BUSYO_CD, $sqlstr);
        return $sqlstr;
    }

    public function fncUpdateBusyo_sql($arrData)
    {
        $sqlstr = "";
        $sqlstr .= "UPDATE HBUSYO \n\r";
        $sqlstr .= "SET    BUSYO_NM = '@BUSYO_NM' \n\r";
        $sqlstr .= ",      BUSYO_KANANM = '@BUSYO_KANANM' \n\r";
        $sqlstr .= ",      BUSYO_RYKNM = '@BUSYO_RYKNM' \n\r";
        $sqlstr .= ",      KKR_BUSYO_CD = '@KKR_BUSYO_CD' \n\r";
        $sqlstr .= ",      CNV_BUSYO_CD = '@CNV_BUSYO_CD' \n\r";
        $sqlstr .= ",      BUSYO_KB = '@BUSYO_KB' \n\r";
        $sqlstr .= ",      TENPO_CD = '@TENPO_CD' \n\r";
        $sqlstr .= ",      SYUKEI_KB = '@SYUKEI_KB' \n\r";
        $sqlstr .= ",      MANEGER_CD = '@MANEGER_CD' \n\r";
        $sqlstr .= ",      START_DATE = '@START_DATE' \n\r";
        $sqlstr .= ",      END_DATE = '@END_DATE' \n\r";
        $sqlstr .= ",      DSP_SEQNO = '@DSP_SEQNO' \n\r";
        $sqlstr .= ",      PRN_KB1 = '@PRN_KB1' \n\r";
        $sqlstr .= ",      PRN_KB2 = '@PRN_KB2' \n\r";
        $sqlstr .= ",      PRN_KB3 = '@PRN_KB3' \n\r";
        $sqlstr .= ",      PRN_KB4 = '@PRN_KB4' \n\r";
        $sqlstr .= ",      PRN_KB5 = '@PRN_KB5' \n\r";
        $sqlstr .= ",      PRN_KB6 = '@PRN_KB6' \n\r";
        $sqlstr .= ",      HKNSYT_DSP_KB = '@HKNSYT_DSP_KB' \n\r";
        $sqlstr .= ",      TORIKOMI_BUSYO_KB = '@TORIKOMI_BUSYO_KB' \n\r";
        $sqlstr .= ",      JISSEKITTL_KB = '@JISSEKITTL_KB' \n\r";
        $sqlstr .= ",      UPD_DATE = SYSDATE \n\r";
        $sqlstr .= ",      UPD_SYA_CD = '@UPDUSER' \n\r";
        $sqlstr .= ",      UPD_PRG_ID = '@UPDAPP' \n\r";
        $sqlstr .= ",      UPD_CLT_NM = '@UPDCLT' \n\r";

        $sqlstr .= "WHERE  BUSYO_CD = '@BUSYO_CD' \n\r";

        foreach ($arrData as $key => $value) {
            //20150728 #2002 fanzhengzhou add s.
            switch ($key) {
                case 'START_DATE':
                    $sqlstr = str_replace("@" . $key, rtrim($value) == "" ? "" : str_replace('/', '', rtrim($value)), $sqlstr);
                    break;
                case 'END_DATE':
                    $sqlstr = str_replace("@" . $key, rtrim($value) == "" ? "999999" : str_replace('/', '', rtrim($value)), $sqlstr);
                    break;
                default:
                    $sqlstr = str_replace("@" . $key, $value, $sqlstr);
                    break;
            }
            //20150728 #2002 fanzhengzhou add e.
        }
        $sqlstr = str_replace("@UPDUSER", $this->GS_LOGINUSER['strUserID'], $sqlstr);
        $sqlstr = str_replace("@UPDAPP", "BusyoMstEdit", $sqlstr);
        $sqlstr = str_replace("@UPDCLT", $this->GS_LOGINUSER['strClientNM'], $sqlstr);
        return $sqlstr;
    }

}