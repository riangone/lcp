<?php
/**
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug             内容                               担当
 * YYYYMMDD           #ID                     XXXXXX                            FCSDL
 * 20150917           #2118                   BUG                               LI
 * --------------------------------------------------------------------------------------------
 */
// 共通クラスの読込み
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFnc;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmSyainMstEdit extends ClsComDb
{
    //---execute
    public function getFormValue($syainNO)
    {
        $strSql = $this->getFormValue_sql($syainNO);
        return parent::select($strSql);
    }

    public function getGridValue($syainNO)
    {
        $strSql = $this->getGridValue_sql($syainNO);
        return parent::select($strSql);
    }

    public function FncGetSyain_no($syainNO)
    {
        $strSql = $this->FncGetSyain_no_sql($syainNO);
        return parent::select($strSql);
    }

    public function fncDelete_HSYAINMST($txtSyainNO)
    {
        $strSql = $this->fncDelete_HSYAINMST_sql($txtSyainNO);
        return parent::Do_Execute($strSql);
    }

    public function fncDelete_HHAIZOKU($txtSyainNO)
    {
        $strSql = $this->fncDelete_HHAIZOKU_sql($txtSyainNO);
        //echo $strSql;
        return parent::Do_Execute($strSql);
    }

    public function fncInsert_HSYAINMST($dataArr)
    {
        $strSql = $this->fncInsert_HSYAINMST_sql($dataArr);
        return parent::Do_Execute($strSql);
        //			echo '--insert--';
        //		echo $strSql;
    }

    public function fncInsert_HHAIZOKU($dataArr, $txtSyainNO, $tmpCnt)
    {
        $strSql = $this->fncInsert_HHAIZOKU_sql($dataArr, $txtSyainNO, $tmpCnt);
        //echo $strSql;
        return parent::Do_Execute($strSql);
        //echo $strSql;
    }

    public function FncDelGridData($postData)
    {
        $strSql = $this->FncDelGridData_sql($postData);
        //echo $strSql;
        return parent::Do_Execute($strSql);
    }

    //---sql---
    public function getFormValue_sql($syainNO)
    {

        $sqlstr = "";
        $sqlstr .= "SELECT SYAIN_NO \n ";
        $sqlstr .= ",      SYAIN_NM \n ";
        $sqlstr .= ",      SYAIN_KN \n ";
        $sqlstr .= ",      SIKAKU_CD \n ";
        $sqlstr .= ",      SLSSUTAFF_KB \n ";
        $sqlstr .= ",      to_char(TO_DATE(TAISYOKU_DATE,'yyyy/MM/dd') ,'yyyy/MM/dd')as TAISYOKU_DATE \n ";
        $sqlstr .= ",		to_char(CREATE_DATE,'yyyy/MM/dd hh24:mi:ss') as CREATE_DATE \n";
        $sqlstr .= "FROM   HSYAINMST \n ";
        $sqlstr .= "WHERE  SYAIN_NO = '" . $syainNO . "' \n ";
        return $sqlstr;
    }

    public function getGridValue_sql($syainNO)
    {
        $sqlstr = "";
        $sqlstr .= "SELECT ''as txtSyainNO \n ";
        $sqlstr .= ",		BUSYO_CD \n ";
        $sqlstr .= ",      SYUKEI_BUSYO_CD \n ";
        $sqlstr .= ",      to_char(TO_DATE(START_DATE,'yyyy/MM/dd') ,'yyyy/MM/dd') as START_DATE \n ";
        $sqlstr .= ",      to_char(TO_DATE(END_DATE,'yyyy/MM/dd') ,'yyyy/MM/dd')as END_DATE \n ";
        $sqlstr .= ",      SYOKUSYU_KB \n ";
        $sqlstr .= ",      DISP_KB \n ";
        $sqlstr .= ",      DAI_HYOUJI \n ";
        //---20150917 li UPD S.
        //$sqlstr .= ",      to_char(CREATE_DATE,'yyyy/MM/dd hh24:MM:ss') as CREATE_DATE \n ";
        $sqlstr .= ",      to_char(CREATE_DATE,'YYYY/MM/DD HH24:MI:SS') as CREATE_DATE \n ";
        //---20150917 li UPD E.
        $sqlstr .= ",      SYAIN_NO \n ";
        $sqlstr .= ",      RIR_NO \n ";
        $sqlstr .= "FROM   HHAIZOKU \n ";
        $sqlstr .= "WHERE  SYAIN_NO = '" . $syainNO . "' \n ";
        $sqlstr .= "ORDER BY START_DATE DESC \n ";
        return $sqlstr;
    }

    public function FncGetSyain_no_sql($syainNO)
    {
        $sqlstr = "SELECT SYAIN_NO FROM HSYAINMST WHERE SYAIN_NO='" . $syainNO . "'";
        return $sqlstr;
    }

    public function fncDelete_HSYAINMST_sql($txtSyainNO)
    {
        $sqlstr = "";
        $sqlstr .= "DELETE FROM HSYAINMST \n";
        $sqlstr .= "WHERE  SYAIN_NO ='" . $txtSyainNO . "' \n";
        return $sqlstr;
    }

    public function fncDelete_HHAIZOKU_sql($txtSyainNO)
    {
        $sqlstr = "";
        $sqlstr .= "DELETE FROM HHAIZOKU \n";
        $sqlstr .= "WHERE  SYAIN_NO ='" . $txtSyainNO . "' \n";
        return $sqlstr;
    }

    public function fncInsert_HSYAINMST_sql($dataArr)
    {
        $sqlstr = "";
        $ttC = new ClsComFnc();
        $sqlstr .= "INSERT INTO HSYAINMST( \n";
        $sqlstr .= "SYAIN_NO \n";
        $sqlstr .= ",SYAIN_NM \n";
        $sqlstr .= ",SYAIN_KN \n";
        $sqlstr .= ",SIKAKU_CD \n";
        $sqlstr .= ",SLSSUTAFF_KB \n";
        $sqlstr .= ",TAISYOKU_DATE \n";
        $sqlstr .= ",UPD_DATE \n";
        $sqlstr .= ",CREATE_DATE \n";
        $sqlstr .= ",UPD_SYA_CD \n";
        $sqlstr .= ",UPD_PRG_ID \n";
        $sqlstr .= ",UPD_CLT_NM) \n";
        $sqlstr .= "VALUES( \n";
        $sqlstr .= "'" . $dataArr['txtSyainNO'] . "', \n";
        $sqlstr .= "'" . $dataArr['txtSyainNM'] . "', \n";
        $sqlstr .= "'" . $dataArr['txtSyainKN'] . "', \n";
        $sqlstr .= "'" . $dataArr['txtSikakuCD'] . "', \n";
        $sqlstr .= "'" . $dataArr['txtKBN'] . "', \n";
        if ($dataArr['chkTaisyokuYMD'] == 'true') {
            $sqlstr .= "'" . str_replace("/", "", $dataArr['cboTaisyokuYMD']) . "', \n";
        } else {
            $sqlstr .= "'',\n";
        }

        $sqlstr .= "sysdate, \n";

        if (trim($dataArr['txtCreateDate']) != "") {
            $sqlstr .= "to_date('" . $dataArr['txtCreateDate'] . "','yyyy/MM/dd HH24:MI:ss'), \n";
        } else {
            $sqlstr .= "sysdate,\n";
        }

        $sqlstr .= "'" . $ttC->FncNv($this->GS_LOGINUSER['strUserID']) . "', \n";
        $sqlstr .= "'SyainMstEdit',\n";
        $sqlstr .= "'" . $ttC->FncNv($this->GS_LOGINUSER['strClientNM']) . "'\n";
        $sqlstr .= ")";
        return $sqlstr;
    }

    public function fncInsert_HHAIZOKU_sql($dataArr, $txtSyainNO, $tmpCnt)
    {
        $sqlstr = "";
        $ttC = new ClsComFnc();
        $sqlstr .= "INSERT INTO HHAIZOKU( \n ";
        $sqlstr .= "SYAIN_NO \n ";
        $sqlstr .= ",RIR_NO \n ";
        $sqlstr .= ",BUSYO_CD \n ";
        $sqlstr .= ",SYUKEI_BUSYO_CD \n ";
        $sqlstr .= ",START_DATE \n ";
        $sqlstr .= ",END_DATE \n ";
        $sqlstr .= ",SYOKUSYU_KB \n ";
        $sqlstr .= ",DISP_KB \n ";
        $sqlstr .= ",DAI_HYOUJI \n ";
        $sqlstr .= ",UPD_DATE \n ";
        $sqlstr .= ",CREATE_DATE \n ";
        $sqlstr .= ",UPD_SYA_CD \n ";
        $sqlstr .= ",UPD_PRG_ID \n ";
        $sqlstr .= ",UPD_CLT_NM) \n ";
        $sqlstr .= "VALUES( \n ";
        $sqlstr .= "'" . $txtSyainNO . "',\n";
        $sqlstr .= "'" . ((int) $tmpCnt + 1) . "',\n";
        $sqlstr .= "'" . $dataArr['BUSYO_CD'] . "',\n";
        $sqlstr .= "'" . $dataArr['SYUKEI_BUSYO_CD'] . "',\n";
        if (trim($dataArr['START_DATE']) != "") {
            $sqlstr .= "'" . str_replace("/", "", $dataArr['START_DATE']) . "',\n";
        } else {
            $sqlstr .= "'',\n";
        }
        if (trim($dataArr['END_DATE']) != "") {
            $sqlstr .= "'" . str_replace("/", "", $dataArr['END_DATE']) . "',\n";
        } else {
            $sqlstr .= "'',\n";
        }

        $sqlstr .= "'" . $dataArr['SYOKUSYU_KB'] . "',\n";
        $sqlstr .= "'" . $dataArr['DISP_KB'] . "',\n";
        $sqlstr .= "'" . $dataArr['DAI_HYOUJI'] . "',\n";
        $sqlstr .= "sysdate,\n";
        if (trim($dataArr['CREATE_DATE']) == "") {
            $sqlstr .= "sysdate,\n";
        } else {
            $sqlstr .= "to_date('" . trim($dataArr['CREATE_DATE']) . "','yyyy/MM/dd HH24:MI:ss'),\n";
        }

        $sqlstr .= "'" . $ttC->FncNv($this->GS_LOGINUSER['strUserID']) . "', \n";
        $sqlstr .= "'SyainMstEdit',\n";
        $sqlstr .= "'" . $ttC->FncNv($this->GS_LOGINUSER['strClientNM']) . "'\n";
        $sqlstr .= ")";
        return $sqlstr;
    }

    public function FncDelGridData_sql($postData)
    {
        $sqlstr = "";
        $sqlstr .= "DELETE FROM HHAIZOKU \n";
        $sqlstr .= "WHERE  SYAIN_NO = '" . $postData['txtSyainNO'] . "' \n";
        $sqlstr .= "AND    RIR_NO = '" . $postData['txtRIR_NO'] . "' \n";
        return $sqlstr;

    }

}
