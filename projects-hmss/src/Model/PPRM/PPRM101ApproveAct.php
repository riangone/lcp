<?php

namespace App\Model\PPRM;

use App\Model\Component\ClsComDb;

class PPRM101ApproveAct extends ClsComDb
{

    public function getUpdDate($postData)
    {
        $sql = $this->getUpdDateSql($postData);

        return parent::select($sql);
    }

    public function chkKeiri($postData)
    {
        $sql = $this->chkKeiriSql($postData);

        return parent::select($sql);
    }

    public function jpgKakunin($postData)
    {
        $sql = $this->jpgKakuninSql($postData);

        return parent::select($sql);
    }

    public function SerchData($postData)
    {
        $sql = $this->SerchDataSql($postData);

        return parent::select($sql);
    }

    public function DataInsert($postData)
    {
        $sql = $this->DataInsertSql($postData);

        return parent::insert($sql);
    }

    public function getBusyoRNM($postData)
    {
        $sql = $this->getBusyoRNMSql($postData);

        return parent::select($sql);
    }

    public function UpdateSyounin($postData)
    {
        $sql = $this->UpdateSyouninSql($postData);

        return parent::update($sql);
    }

    public function getUpdDateSql($postData)
    {
        $sql = "";
        //20180410 YIN UPD S
        // $sql .= "SELECT UPD_DATE" . " \r\n";
        $sql .= "SELECT TO_CHAR(UPD_DATE,'YYYYMMDD HH24MISS') UPD_DATE" . " \r\n";
        //20180410 YIN UPD E
        $sql .= "FROM PPRHJMAPPROVEDATA" . " \r\n";
        $sql .= "WHERE 1=1" . " \r\n";
        $sql .= "  AND TENPO_CD = '@TCD'" . " \r\n";
        $sql .= "  AND TEN_HJM_NO = '@HJMNo'" . " \r\n";
        $sql .= "  AND HJM_KIND = '@KIND'" . " \r\n";

        $sql = str_replace("@TCD", $postData['TCD'], $sql);

        if ($postData['FLG'] == '1') {
            $sql = str_replace("@HJMNo", $postData['HJMNo'], $sql);
            $sql = str_replace("@KIND", 1, $sql);
        } else {
            $sql = str_replace("@HJMNo", $postData['TCD'] . str_replace("/", "", substr($postData['HJMDT'], 2, 8)) . "S01", $sql);
            $sql = str_replace("@KIND", 2, $sql);
        }

        return $sql;
    }

    public function chkKeiriSql($postData)
    {
        $sql = "";
        $sql .= "SELECT KEIRI_SNN_FLG" . " \r\n";
        $sql .= "FROM PPRHJMAPPROVEDATA" . " \r\n";
        $sql .= "WHERE 1=1" . " \r\n";
        $sql .= "  AND TENPO_CD = '@TCD'" . " \r\n";
        $sql .= "  AND TEN_HJM_NO = '@HJMNo'" . " \r\n";
        $sql .= "  AND HJM_KIND = '@KIND'" . " \r\n";

        $sql = str_replace("@TCD", $postData['TCD'], $sql);

        if ($postData['FLG'] == '1') {
            $sql = str_replace("@HJMNo", $postData['HJMNo'], $sql);
            $sql = str_replace("@KIND", 1, $sql);
        } else {
            $sql = str_replace("@HJMNo", $postData['TCD'] . str_replace("/", "", substr($postData['HJMDT'], 2, 8)) . "S01", $sql);
            $sql = str_replace("@KIND", 2, $sql);
        }

        return $sql;
    }

    public function jpgKakuninSql($postData)
    {
        $sql = "";
        $sql .= "SELECT SAVE_PATH" . " \r\n";
        $sql .= "FROM PPRIMAGEFILEDATA" . " \r\n";
        $sql .= "WHERE  TENPO_CD = '@TCD'" . " \r\n";
        $sql .= "  AND TEN_HJM_NO = '@HNO'" . " \r\n";

        $sql = str_replace("@TCD", $postData['TCD'], $sql);
        $sql = str_replace("@HNO", $postData['HJMNo'], $sql);

        return $sql;
    }

    public function SerchDataSql($postData)
    {
        $sql = "";
        $sql .= "SELECT TENPO_CD," . " \r\n";
        $sql .= "       KEIRI_SNN_FLG," . " \r\n";
        $sql .= "       TENCHO_SNN_FLG," . " \r\n";
        $sql .= "       KACHO_SNN_FLG," . " \r\n";
        $sql .= "       TAN_SNN_FLG" . " \r\n";
        $sql .= "FROM PPRHJMAPPROVEDATA" . " \r\n";
        $sql .= " WHERE 1=1" . " \r\n";
        $sql .= "   AND TENPO_CD = '@TCD'" . " \r\n";
        $sql .= "   AND TEN_HJM_NO = '@HJMNo'" . " \r\n";
        $sql .= "   AND HJM_KIND = '@KIND'" . " \r\n";

        $sql = str_replace("@TCD", $postData['TCD'], $sql);

        if ($postData['FLG'] == '1') {
            $sql = str_replace("@HJMNo", $postData['HJMNo'], $sql);
            $sql = str_replace("@KIND", 1, $sql);
        } else {
            $sql = str_replace("@HJMNo", $postData['TCD'] . str_replace("/", "", substr($postData['HJMDT'], 2, 8)) . "S01", $sql);
            $sql = str_replace("@KIND", 2, $sql);
        }

        return $sql;
    }

    public function DataInsertSql($postData)
    {
        $sql = "";
        $sql .= "INSERT INTO PPRHJMAPPROVEDATA" . " \r\n";
        $sql .= " (" . " \r\n";
        $sql .= "  TENPO_CD," . " \r\n";
        $sql .= "  HJM_KIND," . " \r\n";
        $sql .= "  TEN_HJM_NO," . " \r\n";
        $sql .= "  HJM_SYR_DTM," . " \r\n";
        $sql .= "  TAN_SNN_FLG," . " \r\n";
        $sql .= "  TAN_SNN_DATE," . " \r\n";
        $sql .= "  TAN_SNN_BUSYO_CD," . " \r\n";
        $sql .= "  TAN_SNN_TANTO_CD," . " \r\n";
        $sql .= "  TAN_SNN_TANTO_NM," . " \r\n";
        $sql .= "  KACHO_SNN_FLG," . " \r\n";
        $sql .= "  KACHO_SNN_DATE," . " \r\n";
        $sql .= "  KACHO_SNN_BUSYO_CD," . " \r\n";
        $sql .= "  KACHO_SNN_TANTO_CD," . " \r\n";
        $sql .= "  KACHO_SNN_TANTO_NM," . " \r\n";
        $sql .= "  TENCHO_SNN_FLG," . " \r\n";
        $sql .= "  TENCHO_SNN_DATE," . " \r\n";
        $sql .= "  TENCHO_SNN_BUSYO_CD," . " \r\n";
        $sql .= "  TENCHO_SNN_TANTO_CD," . " \r\n";
        $sql .= "  TENCHO_SNN_TANTO_NM," . " \r\n";
        $sql .= "  KEIRI_SNN_FLG," . " \r\n";
        $sql .= "  KEIRI_SNN_DATE," . " \r\n";
        $sql .= "  KEIRI_SNN_BUSYO_CD," . " \r\n";
        $sql .= "  KEIRI_SNN_TANTO_CD," . " \r\n";
        $sql .= "  KEIRI_SNN_TANTO_NM," . " \r\n";
        $sql .= "  CRE_BUSYO_CD," . " \r\n";
        $sql .= "  CRE_SYA_CD," . " \r\n";
        $sql .= "  CRE_CLT_NM," . " \r\n";
        $sql .= "  CRE_DATE," . " \r\n";
        $sql .= "  CRE_PRG_ID," . " \r\n";
        $sql .= "  UPD_BUSYO_CD," . " \r\n";
        $sql .= "  UPD_SYA_CD," . " \r\n";
        $sql .= "  UPD_CLT_NM," . " \r\n";
        $sql .= "  UPD_DATE," . " \r\n";
        $sql .= "  UPD_PRG_ID" . " \r\n";
        $sql .= " )" . " \r\n";
        $sql .= " VALUES" . " \r\n";
        $sql .= " (" . " \r\n";
        $sql .= "  '@TENPO_CD'," . " \r\n";
        $sql .= "  '@HJM_KIND'," . " \r\n";
        $sql .= "  '@TEN_HJM_NO'," . " \r\n";
        $sql .= "  @HJM_SYR_DTM," . " \r\n";
        $sql .= "  0," . " \r\n";
        $sql .= "  NULL," . " \r\n";
        $sql .= "  NULL," . " \r\n";
        $sql .= "  NULL," . " \r\n";
        $sql .= "  NULL," . " \r\n";
        $sql .= "  0," . " \r\n";
        $sql .= "  NULL," . " \r\n";
        $sql .= "  NULL," . " \r\n";
        $sql .= "  NULL," . " \r\n";
        $sql .= "  NULL," . " \r\n";
        $sql .= "  0," . " \r\n";
        $sql .= "  NULL," . " \r\n";
        $sql .= "  NULL," . " \r\n";
        $sql .= "  NULL," . " \r\n";
        $sql .= "  NULL," . " \r\n";
        $sql .= "  0," . " \r\n";
        $sql .= "  NULL," . " \r\n";
        $sql .= "  NULL," . " \r\n";
        $sql .= "  NULL," . " \r\n";
        $sql .= "  NULL," . " \r\n";
        $sql .= "  '@CRE_BUSYO_CD'," . " \r\n";
        $sql .= "  '@CRE_SYA_CD'," . " \r\n";
        $sql .= "  '@CRE_CLT_NM'," . " \r\n";
        $sql .= "  SYSDATE," . " \r\n";
        $sql .= "  '@CRE_PRG_ID'," . " \r\n";
        $sql .= "  '@UPD_BUSYO_CD'," . " \r\n";
        $sql .= "  '@UPD_SYA_CD'," . " \r\n";
        $sql .= "  '@UPD_CLT_NM'," . " \r\n";
        $sql .= "  SYSDATE," . " \r\n";
        $sql .= "  '@UPD_PRG_ID'" . " \r\n";
        $sql .= " )" . " \r\n";

        $sql = str_replace("@TENPO_CD", $postData['TCD'], $sql);

        if ($postData['FLG'] == '1') {
            $sql = str_replace("@TEN_HJM_NO", $postData['HJMNo'], $sql);
            $sql = str_replace("@HJM_KIND", 1, $sql);
        } else {
            $sql = str_replace("@TEN_HJM_NO", $postData['TCD'] . str_replace("/", "", substr($postData['HJMDT'], 2, 8)) . "S01", $sql);
            $sql = str_replace("@HJM_KIND", 2, $sql);
        }

        $sql = str_replace("@HJM_SYR_DTM", "TO_DATE('" . $postData['HJMDT'] . "','YYYY/MM/DD HH24:MI:SS')", $sql);
        $sql = str_replace("@CRE_BUSYO_CD", $postData['BusyoCD'], $sql);
        $sql = str_replace("@CRE_SYA_CD", $postData['login_user'], $sql);
        $sql = str_replace("@CRE_CLT_NM", $postData['MachineNM'], $sql);
        $sql = str_replace("@CRE_PRG_ID", $postData['strProgramID'], $sql);
        $sql = str_replace("@UPD_BUSYO_CD", $postData['BusyoCD'], $sql);
        $sql = str_replace("@UPD_SYA_CD", $postData['login_user'], $sql);
        $sql = str_replace("@UPD_CLT_NM", $postData['MachineNM'], $sql);
        $sql = str_replace("@UPD_PRG_ID", $postData['strProgramID'], $sql);

        return $sql;
    }

    public function getBusyoRNMSql($postData)
    {
        $sql = "";
        $sql .= "SELECT SUBSTR(BUSYO_RYKNM,1,2) AS BUSYO_RYKNM" . " \r\n";
        $sql .= "FROM HBUSYO" . " \r\n";
        $sql .= "WHERE 1=1" . " \r\n";
        $sql .= "  AND BUSYO_CD = '@BCD'" . " \r\n";

        $sql = str_replace("@BCD", $postData['BusyoCD'], $sql);

        return $sql;
    }

    public function UpdateSyouninSql($postData)
    {
        $sql = "";
        $sql .= "UPDATE PPRHJMAPPROVEDATA" . " \r\n";

        switch ($postData['strSyurui']) {
            case 1:
                $sql .= " SET KEIRI_SNN_FLG = '@FLG'," . " \r\n";
                $sql .= "     KEIRI_SNN_DATE = @DATE," . " \r\n";
                $sql .= "     KEIRI_SNN_BUSYO_CD = '@BUSYO_CD'," . " \r\n";
                $sql .= "     KEIRI_SNN_TANTO_CD = '@TANTO_CD'," . " \r\n";
                $sql .= "     KEIRI_SNN_TANTO_NM = '@TANTO_NM'," . " \r\n";
                break;
            case 2:
                $sql .= " SET TENCHO_SNN_FLG = '@FLG'," . " \r\n";
                $sql .= "     TENCHO_SNN_DATE = @DATE," . " \r\n";
                $sql .= "     TENCHO_SNN_BUSYO_CD = '@BUSYO_CD'," . " \r\n";
                $sql .= "     TENCHO_SNN_TANTO_CD = '@TANTO_CD'," . " \r\n";
                $sql .= "     TENCHO_SNN_TANTO_NM = '@TANTO_NM'," . " \r\n";
                break;
            case 3:
                $sql .= " SET KACHO_SNN_FLG = '@FLG'," . " \r\n";
                $sql .= "     KACHO_SNN_DATE = @DATE," . " \r\n";
                $sql .= "     KACHO_SNN_BUSYO_CD = '@BUSYO_CD'," . " \r\n";
                $sql .= "     KACHO_SNN_TANTO_CD = '@TANTO_CD'," . " \r\n";
                $sql .= "     KACHO_SNN_TANTO_NM = '@TANTO_NM'," . " \r\n";
                break;
            case 4:
                $sql .= " SET TAN_SNN_FLG = '@FLG'," . " \r\n";
                $sql .= "     TAN_SNN_DATE = @DATE," . " \r\n";
                $sql .= "     TAN_SNN_BUSYO_CD = '@BUSYO_CD'," . " \r\n";
                $sql .= "     TAN_SNN_TANTO_CD = '@TANTO_CD'," . " \r\n";
                $sql .= "     TAN_SNN_TANTO_NM = '@TANTO_NM'," . " \r\n";
                break;
        }
        $sql .= "     UPD_BUSYO_CD = '@UPD_BUSYO_CD'," . " \r\n";
        $sql .= "     UPD_SYA_CD = '@UPD_SYA_CD'," . " \r\n";
        $sql .= "     UPD_CLT_NM = '@UPD_CLT_NM'," . " \r\n";
        $sql .= "     UPD_DATE = SYSDATE," . " \r\n";
        $sql .= "     UPD_PRG_ID = '@UPD_PRG_ID'" . " \r\n";
        $sql .= "WHERE TENPO_CD = '@TENPO_CD'" . " \r\n";
        $sql .= "  AND HJM_KIND = '@HJM_KIND'" . " \r\n";
        $sql .= "  AND TEN_HJM_NO = '@TEN_HJM_NO'" . " \r\n";

        $sql = str_replace("@FLG", $postData['strFLG'], $sql);

        if ($postData['strFLG'] == 0) {
            $sql = str_replace("@DATE", "NULL", $sql);
            $sql = str_replace("@BUSYO_CD", "", $sql);
            $sql = str_replace("@TANTO_CD", "", $sql);
            $sql = str_replace("@TANTO_NM", "", $sql);
        } else {
            $sql = str_replace("@DATE", "SYSDATE", $sql);
            $sql = str_replace("@BUSYO_CD", $postData['BusyoCD'], $sql);
            $sql = str_replace("@TANTO_CD", $postData['login_user'], $sql);
            $sql = str_replace("@TANTO_NM", $postData['SyainNM'], $sql);
        }

        $sql = str_replace("@UPD_BUSYO_CD", $postData['BusyoCD'], $sql);
        $sql = str_replace("@UPD_SYA_CD", $postData['login_user'], $sql);
        $sql = str_replace("@UPD_CLT_NM", $postData['MachineNM'], $sql);
        $sql = str_replace("@UPD_PRG_ID", $postData['strProgramID'], $sql);

        $sql = str_replace("@TENPO_CD", $postData['TCD'], $sql);
        $sql = str_replace("@HJM_KIND", $postData['FLG'], $sql);

        if ($postData['FLG'] == '1') {
            $sql = str_replace("@TEN_HJM_NO", $postData['HJMNo'], $sql);
        } else {
            $sql = str_replace("@TEN_HJM_NO", $postData['TCD'] . str_replace("/", "", substr($postData['HJMDT'], 2, 8)) . "S01", $sql);
        }

        return $sql;
    }

}