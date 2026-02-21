<?php
// 共通クラスの読込み
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmSyanaiLeasePrint extends ClsComDb
{
    public function frmSyanaiLeasePrint_Load_select()
    {
        $strSql = $this->frmSyanaiLeasePrint_Load_sql();
        return parent::select($strSql);
    }

    public function frmSyanaiLeasePrint_Load_sql()
    {
        $sqlstr = "select";
        $sqlstr .= "		ID ,";
        $sqlstr .= "		(substr(SYR_YMD,1,4) || '/' || SUBSTR(SYR_YMD,5,2)|| '/01') TOUGETU ";
        $sqlstr .= "from ";
        $sqlstr .= "		HKEIRICTL ";
        $sqlstr .= "WHERE ID='01'";
        return $sqlstr;
    }

    public function fncFurikaeCSVSel($strYM)
    {
        $strsql = "SELECT V.GYO_NO ";
        $strsql .= ",      V.BUSYO_CD ";
        $strsql .= ",      V.KAMOK_CD ";
        $strsql .= ",      V.HIMOK_CD ";
        $strsql .= ",      V.LEASE_RYO ";
        $strsql .= ",      V.DENPY_NO ";
        $strsql .= ",      V.MOTOSAKI_KB ";
        $strsql .= "FROM   ( ";
        $strsql .= "SELECT '1' GYO_NO ";
        $strsql .= ",      LS.BUSYO_CD ";
        $strsql .= ",      '43361' KAMOK_CD ";
        $strsql .= ",      DECODE(SUBSTR(LS.SHISAN_CD,1,1),'4','1','5','2','6','2','7','2') HIMOK_CD ";
        $strsql .= ",      SUM(LS.LEASE_RYO) LEASE_RYO ";
        $strsql .= ",      DECODE(SUBSTR(LS.SHISAN_CD,1,1),'4','150004','5','150005','6','150006','7','150007') DENPY_NO ";
        $strsql .= ",      '' MOTOSAKI_KB ";
        $strsql .= "FROM   HSYANAILEASERYO LS ";
        $strsql .= "INNER JOIN HBUSYO BUS ";
        $strsql .= "ON     BUS.BUSYO_CD = LS.BUSYO_CD ";
        $strsql .= "WHERE  SUBSTR(LS.SHISAN_CD,1,1) IN ('4','5','6','7') ";
        $strsql .= "AND    LS.TORIKOMI_YM = '" . $strYM . "' ";
        $strsql .= "GROUP BY LS.BUSYO_CD,SUBSTR(LS.SHISAN_CD,1,1) ";
        $strsql .= "UNION ALL ";
        $strsql .= "SELECT '2' GYO_NO ";
        $strsql .= ",      DECODE(SUBSTR(LS.SHISAN_CD,1,1),'4','013','5','014','6','015','7','015') ";
        $strsql .= ",      '43361' ";
        $strsql .= ",      DECODE(SUBSTR(LS.SHISAN_CD,1,1),'4','1','5','2','6','2','7','2') ";
        $strsql .= ",      SUM(NVL(LS.LEASE_RYO,0)) ";
        $strsql .= ",      DECODE(SUBSTR(LS.SHISAN_CD,1,1),'4','150004','5','150005','6','150006','7','150007') ";
        $strsql .= ",      '1' MOTOSAKI_KB ";
        $strsql .= "FROM   HSYANAILEASERYO LS ";
        $strsql .= "INNER JOIN HBUSYO BUS ";
        $strsql .= "ON     BUS.BUSYO_CD = LS.BUSYO_CD ";
        $strsql .= "WHERE  SUBSTR(LS.SHISAN_CD,1,1) IN ('4','5','6','7') ";
        $strsql .= "AND    LS.TORIKOMI_YM = '" . $strYM . "' ";
        $strsql .= "GROUP BY SUBSTR(LS.SHISAN_CD,1,1) ";
        $strsql .= ") V ";
        $strsql .= "ORDER BY V.DENPY_NO, V.GYO_NO, V.BUSYO_CD	";
        return $strsql;

    }

    public function fncOutput1($strYM)
    {
        $strsql = $this->fncFurikaeCSVSel($strYM);
        return parent::select($strsql);
    }

    public function fncCmdAction_Click($tmpData)
    {
        $sqlstr = $this->fncPrintSelect($tmpData);
        return parent::select($sqlstr);
    }

    //**********************************************************************
    //処 理 名：			printデータ取得
    //関 数 名：			fncPrintSelect
    //引    数：			$tmpData
    //戻 り 値：			無し
    //処理説明：fncPrintSelect処理
    //**********************************************************************
    public function fncPrintSelect($tmpData)
    {
        $strsql = "";
        $strsql .= "SELECT LS.BUSYO_CD ";
        $strsql .= ",      BUS.BUSYO_NM ";
        $strsql .= ",      LS.SHISAN_NM ";
        $strsql .= ",      LS.SHISAN_CD 	 ";
        $strsql .= ",      SUBSTR(JPDATE(LS.SYUTOKU_YMD),1,3) || '.' || SUBSTR(JPDATE(LS.SYUTOKU_YMD),4,2) || '.' || SUBSTR(JPDATE(LS.SYUTOKU_YMD),6,2) SYUTOKUBI ";
        $strsql .= ",      LS.SYUTOKU_KIN  ";
        $strsql .= ",      LS.LEASE_RYO ";
        $strsql .= ",      DECODE(SUBSTR(LS.SHISAN_CD,1,1),'4',1,NULL) SERVICE_CNT ";
        $strsql .= ",      DECODE(SUBSTR(LS.SHISAN_CD,1,1),'4',NVL(LS.SYUTOKU_KIN,0),NULL) SERVICE_KIN ";
        $strsql .= ",      DECODE(SUBSTR(LS.SHISAN_CD,1,1),'4',NVL(LS.LEASE_RYO,0),NULL) SERVICE_REASE_RYO ";
        $strsql .= ",      DECODE(SUBSTR(LS.SHISAN_CD,1,1),'7',1,NULL) KIGU_CNT ";
        $strsql .= ",      DECODE(SUBSTR(LS.SHISAN_CD,1,1),'7',NVL(LS.SYUTOKU_KIN,0),NULL) KIGU_KIN ";
        $strsql .= ",      DECODE(SUBSTR(LS.SHISAN_CD,1,1),'7',NVL(LS.LEASE_RYO,0),NULL) KIGU_REASE_RYO ";
        $strsql .= ",      DECODE(SUBSTR(LS.SHISAN_CD,1,1),'5',1,NULL) KIKAI_CNT ";
        $strsql .= ",      DECODE(SUBSTR(LS.SHISAN_CD,1,1),'5',NVL(LS.SYUTOKU_KIN,0),NULL) KIKAI_KIN ";
        $strsql .= ",      DECODE(SUBSTR(LS.SHISAN_CD,1,1),'5',NVL(LS.LEASE_RYO,0),NULL) KIKAI_REASE_RYO ";
        $strsql .= ",      DECODE(SUBSTR(LS.SHISAN_CD,1,1),'6',1,NULL) KOUGU_CNT ";
        $strsql .= ",      DECODE(SUBSTR(LS.SHISAN_CD,1,1),'6',NVL(LS.SYUTOKU_KIN,0),NULL) KOUGU_KIN ";
        $strsql .= ",      DECODE(SUBSTR(LS.SHISAN_CD,1,1),'6',NVL(LS.LEASE_RYO,0),NULL) KOUGU_REASE_RYO ";
        $strsql .= ",      LS.LEASE_KIKAN ";
        $strsql .= ",      LS.LEASE_RYO_RT ";
        $strsql .= ",      (CASE WHEN LS.STATUS_KB = '1' THEN '計上' ELSE '　管理' END) STATUS ";
        $strsql .= ",      (CASE WHEN LS.SAI_LEASE_KB = 'N' THEN '再' ELSE '' END) LEASEKB ";
        if (trim($tmpData['radAll']) == 'true') {
            $strsql .= ",      '対象：4:サービスカー　5:機械　6:工具　7:器具・備品' TAISYOU";
        }
        if (trim($tmpData['rad1']) == 'true') {
            $strsql .= ",      '対象：4:サービスカー　7:器具・備品' TAISYOU";
        }
        if (trim($tmpData['rad2']) == 'true') {
            $strsql .= ",      '対象：5:機械　6:工具・備品' TAISYOU";
        }
        $strsql .= " ,      JPDATE(TO_CHAR(SYSDATE,'YYYYMMDD')) TODAY  ";
        $strsql .= "FROM   HSYANAILEASERYO LS ";
        $strsql .= "INNER JOIN HBUSYO BUS ";
        $strsql .= "ON     BUS.BUSYO_CD = LS.BUSYO_CD ";

        if (trim($tmpData['radAll']) == 'true') {
            $strsql .= " WHERE  SUBSTR(LS.SHISAN_CD,1,1) IN ('4','5','6','7') ";
        } else {
            if (trim($tmpData['rad1']) == 'true') {
                $strsql .= " WHERE  SUBSTR(LS.SHISAN_CD,1,1) IN ('4','7') ";
            } else {
                $strsql .= " WHERE  SUBSTR(LS.SHISAN_CD,1,1) IN ('5','6') ";
            }
        }
        if (trim($tmpData['busyoCDFrom']) != "") {
            $strsql .= " AND    LS.BUSYO_CD >= '@STARTBUSYOCD' ";
        }
        if (trim($tmpData['busyoCDTo']) != "") {
            $strsql .= " AND    LS.BUSYO_CD <= '@ENDBUSYOCD' ";
        }
        $strsql .= " AND    LS.TORIKOMI_YM = '@TOUGETU' ";

        $strsql .= " ORDER BY LS.BUSYO_CD, LS.SHISAN_CD ";

        $tmpDateYM = str_replace("/", "", $tmpData['cboYm']);

        $strsql = str_replace("@TOUGETU", $tmpDateYM, $strsql);
        $strsql = str_replace("@STARTBUSYOCD", $tmpData['busyoCDFrom'], $strsql);
        $strsql = str_replace("@ENDBUSYOCD", $tmpData['busyoCDTo'], $strsql);
        return $strsql;
    }

    public function fncGetAllValidatingSelect()
    {
        $sqlstr = $this->fncGetAllValidatingSql();
        return parent::select($sqlstr);
    }

    public function fncGetAllValidatingSql()
    {
        $strSql = "";
        $strSql .= "SELECT BUSYO_NM" . "\r\n";
        $strSql .= ",      KKR_BUSYO_CD " . "\r\n";
        $strSql .= ",      BUSYO_CD " . "\r\n";
        $strSql .= " FROM   HBUSYO " . "\r\n";
        $strSql .= " WHERE  ( SYUKEI_KB IS NULL OR  SYUKEI_KB <> '1')" . "\r\n";
        return $strSql;
    }

}