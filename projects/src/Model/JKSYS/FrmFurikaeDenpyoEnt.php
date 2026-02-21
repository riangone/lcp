<?php
/**
 * 説明：
 *
 *
 * @author yuan
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * --------------------------------------------------------------------------------------------
 */
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：FrmFurikaeDenpyoEnt
// * 関数名	：FrmFurikaeDenpyoEnt
// * 処理説明	：共通クラスの読込み
//*************************************
class FrmFurikaeDenpyoEnt extends ClsComDb
{
    // * 処理名	：fncGetJKCMST
    // * 関数名	：fncGetJKCMST
    // * 処理説明	：人事コントロールマスタ
    public function fncGetJKCMST()
    {
        $strSql = $this->fncGetJKCMSTSQL();
        return parent::select($strSql);
    }

    public function fncGetJKCMSTSQL()
    {
        $strSQL = "";
        $strSQL = "SELECT SYORI_YM" . "\r\n";
        $strSQL .= " FROM JKCONTROLMST JKC " . "\r\n";
        $strSQL .= " WHERE JKC.ID = '01' " . "\r\n";
        return $strSQL;
    }

    // * 処理名	：fncGetJKTFDAT
    // * 関数名	：fncGetJKTFDAT
    // * 処理説明	：人件費他部署振替データ
    public function fncGetJKTFDAT($SyoriYM)
    {
        $strSql = $this->fncGetJKTFDATSQL($SyoriYM);
        return parent::select($strSql);
    }

    public function fncGetJKTFDATSQL($SyoriYM)
    {
        $strSQL = "";
        $strSQL .= " SELECT " . "\r\n";
        $strSQL .= " JKTF.SYAIN_NO SYAIN_NO" . "\r\n";
        $strSQL .= ",JKI.BUSYO_CD FRI_MOTO_BUSYO_CD" . "\r\n";
        $strSQL .= ",JKTF.FRI_SAKI_BUSYO_CD FRI_SAKI_BUSYO_CD" . "\r\n";
        $strSQL .= ",JKTF.BIKOU BIKOU" . "\r\n";
        $strSQL .= ",TO_CHAR(JKTF.CREATE_DATE,'YYYY/MM/DD HH24:MI:SS') CREATE_DATE" . "\r\n";
        $strSQL .= ",JKTF.CRE_SYA_CD CRE_SYA_CD" . "\r\n";
        $strSQL .= ",JKTF.CRE_PRG_ID CRE_PRG_ID" . "\r\n";
        $strSQL .= ",TO_CHAR(JKTF.UPD_DATE,'YYYY/MM/DD HH24:MI:SS') UPD_DATE " . "\r\n";
        $strSQL .= ",JKS.SYAIN_NM SYAIN_NM" . "\r\n";
        $strSQL .= ",JKI.BUSYO_NM BUSYO_NM1" . "\r\n";
        $strSQL .= ",JKB2.BUSYO_NM BUSYO_NM2" . "\r\n";
        $strSQL .= ",''" . "\r\n";
        $strSQL .= " FROM JKJINKENHITABUSYOFRI JKTF " . "\r\n";
        $strSQL .= " LEFT JOIN JKSYAIN JKS" . "\r\n";
        $strSQL .= " ON JKTF.SYAIN_NO = JKS.SYAIN_NO" . "\r\n";
        $strSQL .= " LEFT JOIN (SELECT SYAIN_NO,BUSYO_CD,BUSYO_NM" . "\r\n";
        $strSQL .= "            FROM JKIDOURIREKI A" . "\r\n";
        $strSQL .= "            WHERE ANNOUNCE_DT = " . "\r\n";
        $strSQL .= "	                           (SELECT MAX(ANNOUNCE_DT)" . "\r\n";
        $strSQL .= "		                        FROM JKIDOURIREKI " . "\r\n";
        $strSQL .= "                             WHERE ANNOUNCE_DT <= LAST_DAY(TO_DATE('@TAISYOU_YM' || '01','yyyy/mm/dd'))" . "\r\n";
        $strSQL .= "	                            AND A.SYAIN_NO = SYAIN_NO" . "\r\n";
        $strSQL .= "	                            GROUP BY SYAIN_NO" . "\r\n";
        $strSQL .= "	                            )" . "\r\n";
        $strSQL .= "            ) JKI" . "\r\n";
        $strSQL .= " ON JKTF.SYAIN_NO = JKI.SYAIN_NO" . "\r\n";
        $strSQL .= " LEFT JOIN JKBUMON JKB2" . "\r\n";
        $strSQL .= " ON JKTF.FRI_SAKI_BUSYO_CD = JKB2.BUSYO_CD" . "\r\n";
        $strSQL .= " WHERE JKTF.TAISYOU_YM = SUBSTR('@TAISYOU_YM',1,4) || SUBSTR('@TAISYOU_YM',5,6) " . "\r\n";
        $strSQL .= " ORDER BY SYAIN_NO " . "\r\n";

        //条件を設定
        $strSQL = str_replace("@TAISYOU_YM", $SyoriYM, $strSQL);
        return $strSQL;
    }

    public function fncGetMaxJKTFDAT($SyoriYM)
    {
        $strSql = $this->fncGetMaxJKTFDATSQL($SyoriYM);
        return parent::select($strSql);
    }

    public function fncGetMaxJKTFDATSQL($SyoriYM)
    {
        $strSQL = "";
        $strSQL = "select max(UPD_DATE) UPD_DATE from (" . "\r\n";
        $GetJKTFDATSQL = $this->fncGetJKTFDATSQL($SyoriYM);
        $strSQL .= $GetJKTFDATSQL;
        $strSQL .= ")" . "\r\n";
        return $strSQL;
    }

    // * 処理名	：fncGetJKTFDAT
    // * 関数名	：fncGetJKTFDAT
    // * 処理説明	：人件費振替長期欠勤者データ
    public function fncGetJKFKDAT($SyoriYM)
    {
        $strSql = $this->fncGetJKFKDATSQL($SyoriYM);
        return parent::select($strSql);
    }

    public function fncGetJKFKDATSQL($SyoriYM)
    {
        $strSQL = "";
        $strSQL = " SELECT " . "\r\n";
        $strSQL .= " JKFK.SYAIN_NO " . "\r\n";
        $strSQL .= ",JKFK.BUSYO_CD " . "\r\n";
        $strSQL .= ",JKFK.SYUKKIN_RITU " . "\r\n";
        $strSQL .= ",TO_CHAR(JKFK.CREATE_DATE,'YYYY/MM/DD HH24:MI:SS') AS CREATE_DATE " . "\r\n";
        $strSQL .= ",JKFK.CRE_SYA_CD " . "\r\n";
        $strSQL .= ",JKFK.CRE_PRG_ID " . "\r\n";
        $strSQL .= ",TO_CHAR(JKFK.UPD_DATE,'YYYY/MM/DD HH24:MI:SS') UPD_DATE " . "\r\n";
        $strSQL .= ",JKS.SYAIN_NM " . "\r\n";
        $strSQL .= ",JKB.BUSYO_NM " . "\r\n";
        $strSQL .= " FROM JKJINKENHIKEKKIN JKFK " . "\r\n";
        $strSQL .= " LEFT JOIN JKSYAIN JKS" . "\r\n";
        $strSQL .= " ON JKFK.SYAIN_NO = JKS.SYAIN_NO" . "\r\n";
        $strSQL .= " LEFT JOIN JKBUMON JKB" . "\r\n";
        $strSQL .= " ON JKFK.BUSYO_CD = JKB.BUSYO_CD" . "\r\n";
        $strSQL .= " WHERE JKFK.TAISYOU_YM = SUBSTR('@TAISYOU_YM',1,4) || SUBSTR('@TAISYOU_YM',5,6) " . "\r\n";
        $strSQL .= " ORDER BY SYAIN_NO " . "\r\n";

        //条件を設定
        $strSQL = str_replace("@TAISYOU_YM", $SyoriYM, $strSQL);
        return $strSQL;
    }

    public function fncGetMaxJKFKDAT($SyoriYM)
    {
        $strSql = $this->fncGetMaxJKFKDATSQL($SyoriYM);
        return parent::select($strSql);
    }

    public function fncGetMaxJKFKDATSQL($SyoriYM)
    {
        $strSQL = "";
        $strSQL = "select max(UPD_DATE) UPD_DATE from (" . "\r\n";
        $GetJKFKDATSQL = $this->fncGetJKFKDATSQL($SyoriYM);
        $strSQL .= $GetJKFKDATSQL;
        $strSQL .= ")" . "\r\n";
        return $strSQL;
    }

    // * 処理名	：fncGetJKKTDAT
    // * 関数名	：fncGetJKKTDAT
    // * 処理説明	：勤怠データ
    public function fncGetJKKTDAT($SyoriYM)
    {
        $strSql = $this->fncGetJKKTDATSQL($SyoriYM);
        return parent::select($strSql);
    }

    public function fncGetJKKTDATSQL($SyoriYM)
    {
        $strSQL = "";
        $strSQL .= " SELECT " . "\r\n";
        $strSQL .= " JKKT.SYAIN_NO " . "\r\n";
        //（出勤日数＋有給日数＋休出日数）／（出勤日数＋有給日数＋特休日数＋欠勤日数＋休出日数）
        $strSQL .= ",DECODE((JKKT.KINTAI_NISSU1 + JKKT.KINTAI_NISSU4 + JKKT.KINTAI_NISSU2 + JKKT.KINTAI_NISSU3 + JKKT.KINTAI_NISSU5),0,0,ROUND(((JKKT.KINTAI_NISSU1 + JKKT.KINTAI_NISSU4 + JKKT.KINTAI_NISSU2) / (JKKT.KINTAI_NISSU1 + JKKT.KINTAI_NISSU4 + JKKT.KINTAI_NISSU2 + JKKT.KINTAI_NISSU3 + JKKT.KINTAI_NISSU5)) * 100, 1)) SYUKKIN_RITU" . "\r\n";
        $strSQL .= ",JKS.SYAIN_NM " . "\r\n";
        $strSQL .= ",JKI.BUSYO_CD " . "\r\n";
        $strSQL .= ",JKB.BUSYO_NM " . "\r\n";
        $strSQL .= " FROM JKKINTAI JKKT " . "\r\n";
        $strSQL .= " INNER JOIN JKSONOTA JKSNT " . "\r\n";
        $strSQL .= " ON JKKT.SYAIN_NO = JKSNT.SYAIN_NO " . "\r\n";
        $strSQL .= " AND JKKT.TAISYOU_YM = JKSNT.TAISYOU_YM " . "\r\n";
        $strSQL .= " AND JKKT.KS_KB = JKSNT.KS_KB" . "\r\n";
        $strSQL .= " INNER JOIN JKSYAIN JKS " . "\r\n";
        $strSQL .= " ON JKKT.SYAIN_NO = JKS.SYAIN_NO " . "\r\n";
        $strSQL .= " AND JKS.KOYOU_KB_CD NOT IN ('07','97') " . "\r\n";
        $strSQL .= " LEFT JOIN (SELECT SYAIN_NO,BUSYO_CD" . "\r\n";
        $strSQL .= "            FROM JKIDOURIREKI A" . "\r\n";
        $strSQL .= "            WHERE ANNOUNCE_DT = " . "\r\n";
        $strSQL .= "	                           (SELECT MAX(ANNOUNCE_DT)" . "\r\n";
        $strSQL .= "		                        FROM JKIDOURIREKI " . "\r\n";
        $strSQL .= "                             WHERE ANNOUNCE_DT <= LAST_DAY(TO_DATE('@TAISYOU_YM' || '01','yyyy/mm/dd'))" . "\r\n";
        $strSQL .= "	                            AND A.SYAIN_NO = SYAIN_NO" . "\r\n";
        $strSQL .= "	                            GROUP BY SYAIN_NO" . "\r\n";
        $strSQL .= "	                            )" . "\r\n";
        $strSQL .= "            ) JKI" . "\r\n";
        $strSQL .= "ON JKKT.SYAIN_NO = JKI.SYAIN_NO" . "\r\n";
        $strSQL .= " LEFT JOIN JKBUMON JKB" . "\r\n";
        $strSQL .= " ON JKI.BUSYO_CD = JKB.BUSYO_CD" . "\r\n";
        $strSQL .= " WHERE JKKT.TAISYOU_YM = @TAISYOU_YM" . "\r\n";
        $strSQL .= " AND   JKKT.KS_KB = '1'" . "\r\n";
        $strSQL .= " AND DECODE((JKKT.KINTAI_NISSU1 + JKKT.KINTAI_NISSU4 + JKKT.KINTAI_NISSU2 + JKKT.KINTAI_NISSU3 + JKKT.KINTAI_NISSU5),0,0,ROUND(((JKKT.KINTAI_NISSU1 + JKKT.KINTAI_NISSU4 + JKKT.KINTAI_NISSU2) / (JKKT.KINTAI_NISSU1 + JKKT.KINTAI_NISSU4 + JKKT.KINTAI_NISSU2 + JKKT.KINTAI_NISSU3 + JKKT.KINTAI_NISSU5)) * 100, 1)) < 50" . "\r\n";
        $strSQL .= " AND NVL(JKS.KOYOU_KB_CD,'1') <> '0' " . "\r\n";
        $strSQL .= " ORDER BY SYAIN_NO " . "\r\n";
        //条件を設定
        $strSQL = str_replace("@TAISYOU_YM", $SyoriYM, $strSQL);
        return $strSQL;
    }

    // * 処理名	fncDelJKTFDAT
    // * 関数名	fncDelJKTFDAT
    // * 処理説明	：人件費他部署振替データ(Delete)
    public function fncDelJKTFDAT($dtpTaisyouYM, $strSyainNo = "")
    {
        $strSql = $this->fncDelJKTFDATSQL($dtpTaisyouYM, $strSyainNo);
        return parent::delete($strSql);
    }

    public function fncDelJKTFDATSQL($dtpTaisyouYM, $strSyainNo = "")
    {
        $strSQL = "";
        $strSQL = " DELETE FROM JKJINKENHITABUSYOFRI JKTF" . "\r\n";
        $strSQL .= " WHERE  JKTF.TAISYOU_YM = @TAISYOU_YM " . "\r\n";
        if ($strSyainNo <> "") {
            $strSQL .= " AND  JKTF.SYAIN_NO = '@SYAIN_NO'" . "\r\n";
        }
        //条件を設定
        $strSQL = str_replace("@TAISYOU_YM", $dtpTaisyouYM, $strSQL);
        $strSQL = str_replace("@SYAIN_NO", $strSyainNo, $strSQL);
        return $strSQL;
    }

    // * 処理名	：FncInsJKTFDAT
    // * 関数名	：FncInsJKTFDAT
    // * 処理説明	：人件費他部署振替データ(Insert)
    public function fncInsJKTFDAT($dtpTaisyouYM, $strSyainNo, $strMotoBusyoCD, $strSakiBusyoCD, $strBiko, $strCreateDate, $strCreateCD, $strCreateAPP)
    {
        $strSql = $this->fncInsJKTFDATSQL($dtpTaisyouYM, $strSyainNo, $strMotoBusyoCD, $strSakiBusyoCD, $strBiko, $strCreateDate, $strCreateCD, $strCreateAPP);
        return parent::insert($strSql);
    }

    public function fncInsJKTFDATSQL($dtpTaisyouYM, $strSyainNo, $strMotoBusyoCD, $strSakiBusyoCD, $strBiko, $strCreateDate = "SYSDATE", $strCreateCD = "@CreateCD", $strCreateAPP = "FurikaeDenpyoEnt")
    {
        $strSQL = "";
        $strSQL = "INSERT INTO JKJINKENHITABUSYOFRI(" . "\r\n";
        $strSQL .= " TAISYOU_YM" . "\r\n";
        $strSQL .= ",SYAIN_NO" . "\r\n";
        $strSQL .= ",FRI_MOTO_BUSYO_CD" . "\r\n";
        $strSQL .= ",FRI_SAKI_BUSYO_CD" . "\r\n";
        $strSQL .= ",BIKOU" . "\r\n";
        $strSQL .= ",CREATE_DATE" . "\r\n";
        $strSQL .= ",CRE_SYA_CD" . "\r\n";
        $strSQL .= ",CRE_PRG_ID" . "\r\n";
        $strSQL .= ",UPD_DATE" . "\r\n";
        $strSQL .= ",UPD_SYA_CD" . "\r\n";
        $strSQL .= ",UPD_PRG_ID" . "\r\n";
        $strSQL .= ",UPD_CLT_NM" . "\r\n";
        $strSQL .= ")VALUES(" . "\r\n";
        $strSQL .= " '@TAISYOU_YM'" . "\r\n";
        $strSQL .= ",'@SYAIN_NO'" . "\r\n";
        $strSQL .= ",'@FRI_MOTO_BUSYO_CD'" . "\r\n";
        $strSQL .= ",'@FRI_SAKI_BUSYO_CD'" . "\r\n";
        $strSQL .= ",'@BIKOU'" . "\r\n";
        if ($strCreateDate <> "") {
            $strSQL .= ",TO_DATE('@CREATE_DATE','yyyy/mm/dd hh24:mi:ss')" . "\r\n";
        } else {
            $strSQL .= ",SYSDATE" . "\r\n";
        }
        if ($strCreateCD <> "") {
            $strSQL .= ",'@CRE_SYA_CD'" . "\r\n";
        } else {
            $strSQL .= ",'" . $this->GS_LOGINUSER['strUserID'] . "'" . "\r\n";
        }
        if ($strCreateAPP <> "") {
            $strSQL .= ",'@CRE_PRG_ID'" . "\r\n";
        } else {
            $strSQL .= ",'FurikaeDenpyoEnt'" . "\r\n";
        }

        $strSQL .= ",SYSDATE" . "\r\n";
        $strSQL .= ",'" . $this->GS_LOGINUSER['strUserID'] . "'" . "\r\n";
        $strSQL .= ",'FurikaeDenpyoEnt'" . "\r\n";
        $strSQL .= ",'" . $this->GS_LOGINUSER['strClientNM'] . "')" . "\r\n";
        //条件を設定
        $strSQL = str_replace("@TAISYOU_YM", $dtpTaisyouYM, $strSQL);
        $strSQL = str_replace("@SYAIN_NO", $strSyainNo, $strSQL);
        $strSQL = str_replace("@FRI_MOTO_BUSYO_CD", $strMotoBusyoCD, $strSQL);
        $strSQL = str_replace("@FRI_SAKI_BUSYO_CD", $strSakiBusyoCD, $strSQL);
        $strSQL = str_replace("@BIKOU", $strBiko, $strSQL);
        $strSQL = str_replace("@CREATE_DATE", $strCreateDate, $strSQL);
        $strSQL = str_replace("@CRE_SYA_CD", $strCreateCD, $strSQL);
        $strSQL = str_replace("@CRE_PRG_ID", $strCreateAPP, $strSQL);
        return $strSQL;
    }

    // * 処理名	：fncDelJKFKDAT
    // * 関数名	：fncDelJKFKDAT
    // * 処理説明	：人件費振替長期欠勤者データ(Delete)
    public function fncDelJKFKDAT($dtpTaisyouYM)
    {
        $strSql = $this->fncDelJKFKDATSQL($dtpTaisyouYM);
        return parent::delete($strSql);
    }

    public function fncDelJKFKDATSQL($dtpTaisyouYM)
    {
        $strSQL = "";
        $strSQL = " DELETE FROM JKJINKENHIKEKKIN JKFK" . "\r\n";
        $strSQL .= " WHERE  JKFK.TAISYOU_YM = @TAISYOU_YM " . "\r\n";
        //条件を設定
        $strSQL = str_replace("@TAISYOU_YM", $dtpTaisyouYM, $strSQL);
        return $strSQL;
    }

    // * 処理名	：fncInsJKFKDAT
    // * 関数名	：fncInsJKFKDAT
    // * 処理説明	：人件費振替長期欠勤者データ(Delete)
    public function fncInsJKFKDAT($dtpTaisyouYM, $strSyainNo, $strBusyoCD, $strSyukkin, $strCreateDate, $strCreateCD, $strCreateAPP)
    {
        $strSql = $this->fncInsJKFKDATSQL($dtpTaisyouYM, $strSyainNo, $strBusyoCD, $strSyukkin, $strCreateDate, $strCreateCD, $strCreateAPP);
        return parent::insert($strSql);
    }

    public function fncInsJKFKDATSQL($dtpTaisyouYM, $strSyainNo, $strBusyoCD, $strSyukkin, $strCreateDate, $strCreateCD, $strCreateAPP)
    {
        $strSQL = "";
        $strSQL = "INSERT INTO JKJINKENHIKEKKIN(" . "\r\n";
        $strSQL .= " TAISYOU_YM" . "\r\n";
        $strSQL .= ",SYAIN_NO" . "\r\n";
        $strSQL .= ",BUSYO_CD" . "\r\n";
        $strSQL .= ",SYUKKIN_RITU" . "\r\n";
        $strSQL .= ",CREATE_DATE" . "\r\n";
        $strSQL .= ",CRE_SYA_CD" . "\r\n";
        $strSQL .= ",CRE_PRG_ID" . "\r\n";
        $strSQL .= ",UPD_DATE" . "\r\n";
        $strSQL .= ",UPD_SYA_CD" . "\r\n";
        $strSQL .= ",UPD_PRG_ID" . "\r\n";
        $strSQL .= ",UPD_CLT_NM" . "\r\n";
        $strSQL .= ")VALUES(" . "\r\n";
        $strSQL .= " '@TAISYOU_YM'" . "\r\n";
        $strSQL .= ",'@SYAIN_NO'" . "\r\n";
        $strSQL .= ",'@BUSYO_CD'" . "\r\n";
        $strSQL .= ",'@SYUKKIN_RITU'" . "\r\n";

        if ($strCreateDate <> "") {
            $strSQL .= ",TO_DATE('@CREATE_DATE','yyyy/mm/dd hh24:mi:ss')" . "\r\n";
        } else {
            $strSQL .= ",SYSDATE" . "\r\n";
        }
        if ($strCreateCD <> "") {
            $strSQL .= ",'@CRE_SYA_CD'" . "\r\n";
        } else {
            $strSQL .= ",'" . $this->GS_LOGINUSER['strUserID'] . "'" . "\r\n";
        }
        if ($strCreateAPP <> "") {
            $strSQL .= ",'@CRE_PRG_ID'" . "\r\n";
        } else {
            $strSQL .= ",'FurikaeDenpyoEnt'" . "\r\n";
        }

        $strSQL .= ",SYSDATE" . "\r\n";
        //20200109 LUJUNXIA UPD S
        $strSQL .= ",'" . $this->GS_LOGINUSER['strUserID'] . "'" . "\r\n";
        $strSQL .= ",'FurikaeDenpyoEnt'" . "\r\n";
        $strSQL .= ",'" . $this->GS_LOGINUSER['strClientNM'] . "')" . "\r\n";
        //20200109 LUJUNXIA UPD E

        //条件を設定
        $strSQL = str_replace("@TAISYOU_YM", $dtpTaisyouYM, $strSQL);
        $strSQL = str_replace("@SYAIN_NO", $strSyainNo, $strSQL);
        $strSQL = str_replace("@BUSYO_CD", $strBusyoCD, $strSQL);
        $strSQL = str_replace("@SYUKKIN_RITU", $strSyukkin, $strSQL);
        $strSQL = str_replace("@CREATE_DATE", $strCreateDate, $strSQL);
        $strSQL = str_replace("@CRE_SYA_CD", $strCreateCD, $strSQL);
        $strSQL = str_replace("@CRE_PRG_ID", $strCreateAPP, $strSQL);
        return $strSQL;
    }

    // * 処理名	：fncGetBusyoCD
    // * 関数名	：fncGetBusyoCD
    // * 処理説明	：振替元部署コード取得
    public function fncGetBusyoCD($SyoriYM)
    {
        $strSql = $this->fncGetBusyoCDSQL($SyoriYM);
        return parent::select($strSql);
    }

    public function fncGetBusyoCDSQL($SyoriYM)
    {
        $strSQL = "";
        $strSQL = " SELECT " . "\r\n";
        $strSQL .= "  JKS.SYAIN_NO " . "\r\n";
        $strSQL .= " ,JKI.BUSYO_CD " . "\r\n";
        $strSQL .= " ,JKI.BUSYO_NM " . "\r\n";
        $strSQL .= " FROM JKSYAIN JKS " . "\r\n";
        $strSQL .= " INNER JOIN (SELECT BUSYO_CD,BUSYO_NM,SYAIN_NO" . "\r\n";
        $strSQL .= "             FROM JKIDOURIREKI A" . "\r\n";
        $strSQL .= "             WHERE ANNOUNCE_DT = " . "\r\n";
        $strSQL .= "	                            (SELECT MAX(ANNOUNCE_DT)" . "\r\n";
        $strSQL .= "                              FROM JKIDOURIREKI " . "\r\n";
        $strSQL .= "                              WHERE ANNOUNCE_DT <= LAST_DAY(TO_DATE('@TAISYOU_YM' || '01','yyyy/mm/dd')) " . "\r\n";
        $strSQL .= "                              AND A.SYAIN_NO = SYAIN_NO" . "\r\n";
        $strSQL .= "                              GROUP BY SYAIN_NO" . "\r\n";
        $strSQL .= "	                             )" . "\r\n";
        $strSQL .= "             ) JKI" . "\r\n";
        $strSQL .= " ON JKS.SYAIN_NO = JKI.SYAIN_NO" . "\r\n";
        //条件を設定
        $strSQL = str_replace("@TAISYOU_YM", $SyoriYM, $strSQL);
        return $strSQL;
    }

    // * 処理名	：fncGetSyainMstValue
    // * 関数名	：fncGetSyainMstValue
    // * 処理説明	：社員署名取得
    public function fncGetSyainMstValue()
    {
        $strSql = $this->fncGetSyainMstValueSQL();
        return parent::select($strSql);
    }

    public function fncGetSyainMstValueSQL()
    {
        $strSQL = "";
        $strSQL = "SELECT SYAIN_NO, SYAIN_NM FROM   JKSYAIN" . "\r\n";
        return $strSQL;
    }

    // * 処理名	：FncGetBusyoMstValue
    // * 関数名	：FncGetBusyoMstValue
    // * 処理説明	：部署名取得
    public function fncGetBusyoMstValue()
    {
        $strSql = $this->FncGetBusyoMstValueSQL();
        return parent::select($strSql);
    }

    public function fncGetBusyoMstValueSQL()
    {
        $strSQL = "";
        $strSQL = "SELECT BUSYO_NM, BUSYO_CD FROM  JKBUMON" . "\r\n";
        return $strSQL;
    }

}
