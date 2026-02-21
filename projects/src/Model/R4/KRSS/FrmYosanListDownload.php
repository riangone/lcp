<?php
/**
 *
 *
 *
 * @author yushuangji
 * @copyright (GD) (ZM)
 * @package default
 *
 *
 * --------------------------------------------------------------------------------------------
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * --------------------------------------------------------------------------------------------
 */

namespace App\Model\R4\KRSS;

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFnc;
class FrmYosanListDownload extends ClsComDb
{
    //--execute--
    public function fncPatternNMSel()
    {
        $sql = $this->fncPatternNMSel_sql();
        return parent::select($sql);
    }

    public function fncHKEIRICTL()
    {
        $sql = $this->fncHKEIRICTL_sql();
        return parent::select($sql);
    }

    public function fncGetBusyo()
    {
        $sql = $this->fncGetBusyo_sql();
        return parent::select($sql);
    }

    public function fncGetMaxMinBusyoCD($syain_no)
    {
        $sql = $this->fncGetMaxMinBusyoCD_sql($syain_no);
        return parent::select($sql);
    }

    public function fncSelect($busyoCD_From, $busyoCD_TO, $pattern_No, $NENGTU)
    {
        $sql = $this->fncSelect_sql($busyoCD_From, $busyoCD_TO, $pattern_No, $NENGTU);
        return parent::select($sql);
    }

    public function fncDeleteKanr($intPatternNo, $strBusyoCDF = "", $strBusyoCDT = "", $intProNo, $strUpdUser)
    {
        $sql = $this->fncDeleteKanr_sql($intPatternNo, $strBusyoCDF, $strBusyoCDT, $intProNo, $strUpdUser);
        return parent::Do_Execute($sql);
    }

    public function fncWKInsert($NENGTU_cboYM, $UPDAPP, $UPDCLTNM, $UPDUSER)
    {
        $sql = $this->fncWKInsert_sql($NENGTU_cboYM, $UPDAPP, $UPDCLTNM, $UPDUSER);
        return parent::Do_Execute($sql);
    }

    public function fncWKTRUNCATE()
    {
        $sql = $this->fncWKTRUNCATE_sql();
        return parent::Do_Execute($sql);
    }


    public function fncSelectYosan($KI, $busyoCD_From, $busyoCD_TO, $pattern_No)
    {
        $sql = $this->fncSelectYosan_sql($KI, $busyoCD_From, $busyoCD_TO, $pattern_No);
        return parent::select($sql);
    }



    //20160620 Ins Start
    public function fncWKKIKANTRUNCATE()
    {
        $sql = $this->fncWKKIKANTRUNCATE_sql();
        return parent::Do_Execute($sql);
    }
    public function fncWKKIKANINSERT($Nengetu = "")
    {
        $sql = $this->fncWKKIKANINSERT_sql($Nengetu);
        return parent::Do_Execute($sql);
    }

    //--sql--
    private function fncWKKIKANTRUNCATE_sql()
    {
        $sqlstr = "";
        $sqlstr .= "TRUNCATE TABLE WK_KEIEISEIKA_KIKAN ";
        return $sqlstr;
    }

    private function fncWKKIKANINSERT_sql($Nengetu)
    {
        $sqlstr = "";

        $tpM = substr($Nengetu, 4, 2);
        $tpY = substr($Nengetu, 0, 4);
        if ((int) $tpM >= 10) {
            $tpY = (int) $tpY - 1;
            $KIFM = $tpY . "10";
            $Nengetu = $tpY + 1 . "09";

        } else {
            $KIFM = (int) $tpY;
            $KIFM = $KIFM - 1 . "10";
            $Nengetu = $tpY . "09";
        }

        $sqlstr .= "INSERT INTO WK_KEIEISEIKA_KIKAN VALUES ('" . $KIFM . "','" . $Nengetu . "') ";
        //	$this->log($sqlstr);
        return $sqlstr;
    }
    //20160620 Ins End


    //20160914 Ins Start
    public function fncWKYOSANKIKANTRUNCATE()
    {
        $sql = $this->fncWKYOSANKIKANTRUNCATE_sql();
        return parent::Do_Execute($sql);
    }
    public function fncWKYOSANKIKANINSERT($Nengetu = "")
    {
        $sql = $this->fncWKYOSANKIKANINSERT_sql($Nengetu);
        return parent::Do_Execute($sql);
    }

    //--sql--
    private function fncWKYOSANKIKANTRUNCATE_sql()
    {
        $sqlstr = "";
        $sqlstr .= "TRUNCATE TABLE WK_YOSAN_KIKAN ";
        return $sqlstr;
    }

    private function fncWKYOSANKIKANINSERT_sql($Nengetu)
    {
        $sqlstr = "";

        $tpM = substr($Nengetu, 4, 2);
        $tpY = substr($Nengetu, 0, 4);
        if ((int) $tpM >= 10) {
            $tpY = (int) $tpY;
            $KIFM = $tpY . "10";
            $Nengetu = $tpY + 1 . "09";

        } else {
            $KIFM = (int) $tpY - 1;
            $KIFM = $KIFM . "10";
            $Nengetu = $tpY . "09";
        }
        $sqlstr .= "INSERT INTO WK_YOSAN_KIKAN VALUES ('" . $KIFM . "','" . $Nengetu . "') ";
        //	$this->log($sqlstr);
        return $sqlstr;
    }
    //20160914 Ins End



    //--sql--
    private function fncPatternNMSel_sql()
    {
        $sqlstr = "";
        $sqlstr .= "SELECT PATTERN_NO,PATTERN_NM,CREATE_DATE ";
        $sqlstr .= "FROM HKSPATTERNNAMEMST_KRSS ";
        $sqlstr .= "ORDER BY PATTERN_NO";
        return $sqlstr;
    }

    public function fncHKEIRICTL_sql()
    {
        $sqlstr = "";
        $sqlstr .= "SELECT ID \r";
        $sqlstr .= ",		(SUBSTR(SYR_YMD,1,4) || '/' || SUBSTR(SYR_YMD,5,2) || '/01') TOUGETU\r";
        $sqlstr .= ",		KISYU_YMD KISYU	\r";
        $sqlstr .= ",     KI \r";
        $sqlstr .= "FROM  HKEIRICTL \r";
        $sqlstr .= "WHERE ID='01'";
        return $sqlstr;
    }

    public function fncGetBusyo_sql()
    {
        $strSQL = "";
        $strSQL .= "SELECT BUSYO_CD ";
        $strSQL .= ", BUSYO_NM ";
        $strSQL .= "  FROM ";
        $strSQL .= "  HBUSYO ";
        $strSQL .= "  WHERE ";
        $strSQL .= "  SYUKEI_KB IS NULL OR  SYUKEI_KB <> '1' ";
        return $strSQL;
    }

    public function fncGetMaxMinBusyoCD_sql($syainNo)
    {
        $sqlstr = "";
        $sqlstr .= "select busyo_cd \n";
        $sqlstr .= " from \n";
        $sqlstr .= " hauthority_ctl \n";
        $sqlstr .= " where \n";
        $sqlstr .= " sys_kb='11' and \n";

        $sqlstr .= " syain_no='" . $syainNo . "' \n";
        $sqlstr .= " group by busyo_cd \n";
        $sqlstr .= " order by busyo_cd ";
        return $sqlstr;
    }

    public function fncSelect_sql($busyoCD_From, $busyoCD_TO, $pattern_No, $NENGTU_cboYM)
    {
        $KIFM = "";
        $sqlstr = "";
        $sqlstr .= "SELECT \r";
        $sqlstr .= "	KI\r";
        $sqlstr .= ",	NENGETU\r";
        $sqlstr .= ",	BUSYO_CD\r";
        $sqlstr .= ",	BUSYO_NM\r";
        $sqlstr .= ", COMMENT_STR\r";
        $sqlstr .= ",	LINE_NO\r";
        $sqlstr .= ",	KEIKAKU\r";
        $sqlstr .= ",	JISSEKI\r";
        $sqlstr .= ",	SHIHYO\r";
        $sqlstr .= ",	KEIKAKUSA\r";

        $sqlstr .= ",	TKI_JISSEKI\r";
        $sqlstr .= ",	ZKI_JISSEKI\r";

        $sqlstr .= ",	ZENNENHI\r";
        //        $sqlstr .= "	FROM MVW_KEIEISEIKA_ALL \r";
        $sqlstr .= "	FROM VW_KEIEISEIKA_ALL \r";

        $sqlstr .= "	WHERE\r";
        //$sqlstr .= "	KI='" . $KI . "'";
        $tpM = substr($NENGTU_cboYM, 4, 2);
        $tpY = substr($NENGTU_cboYM, 0, 4);
        if ((int) $tpM >= 10) {
            //            $KIFM = $tpY . "10";
            $KIFM = $tpY - 1 . "10";
        } else {
            //            $KIFM = (int)$tpY - 1;
            $KIFM = (int) $tpY;

            //            $KIFM = $KIFM . "10";

        }

        $sqlstr .= " NENGETU >='" . $KIFM . "'";
        $sqlstr .= " AND NENGETU <='" . str_replace("/", "", $NENGTU_cboYM) . "'";

        if ($pattern_No == 0) {
            if ($busyoCD_From == "" && $busyoCD_TO == "") {

            } else {
                if ($busyoCD_From != "" && $busyoCD_TO == "") {
                    $sqlstr .= " AND BUSYO_CD >= $busyoCD_From\r";
                } elseif ($busyoCD_From == "" && $busyoCD_TO != "") {
                    $sqlstr .= " AND BUSYO_CD <= $busyoCD_TO\r";
                } else {
                    if ($busyoCD_From == $busyoCD_TO) {
                        $sqlstr .= " AND BUSYO_CD =$busyoCD_From ";
                    } else {
                        $sqlstr .= " AND BUSYO_CD >=$busyoCD_From AND BUSYO_CD<=$busyoCD_TO";
                    }
                }
            }
        } else {
            //            $sqlstr .= "	AND BUSYO_CD IN(SELECT BUSYO_CD FROM HKSPATTERNLISTMST WHERE PATTERN_NO=$pattern_No)";
            $sqlstr .= "	AND BUSYO_CD IN ( ";


            $sqlstr .= "SELECT BUSYO_CD FROM HKSPATTERNLISTMST WHERE PATTERN_NO=$pattern_No ";
            //            $sqlstr .= " UNION ";
//            $sqlstr .= "SELECT DISTINCT BUSYO_CD FROM HTTLBUSYO WHERE  TOTAL_BUSYO_CD IN ";
//            $sqlstr .= "(SELECT BUSYO_CD FROM HKSPATTERNLISTMST WHERE PATTERN_NO=$pattern_No)";
            $sqlstr .= "	)";
        }

        $sqlstr .= "ORDER BY \r";
        $sqlstr .= "BUSYO_CD  \r";
        $sqlstr .= ",LINE_NO  \r";


        //        $this -> log($sqlstr);
        return $sqlstr;
    }

    function fncDeleteKanr_sql($intPatternNo, $strBusyoCDF, $strBusyoCDT, $intProNo, $strUpdUser)
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM WK_HKANRIZ_KEIEISEIKA KR" . "\r\n";
        $strSQL .= " WHERE   NOT EXISTS" . "\r\n";

        if ($intPatternNo == 0) {
            $strSQL .= "       (SELECT BUS.BUSYO_CD" . "\r\n";
            $strSQL .= "        FROM   HBUSYO BUS" . "\r\n";

            if ($intProNo == 1) {
                $strSQL .= "     INNER JOIN HAUTHORITY_CTL AUT" . "\r\n";
                $strSQL .= "		ON     AUT.BUSYO_CD = BUS.BUSYO_CD" . "\r\n";
                $strSQL .= "		AND    AUT.SYAIN_NO = '@UPDUSER'" . "\r\n";
                $strSQL .= "		AND    AUT.HAUTH_ID = '002'" . "\r\n";
                $strSQL .= "     AND    AUT.SYS_KB = '@SYS_KB'" . "\r\n";

                $this->clsComFnc = new ClsComFnc();
                $strSQL = str_replace("@SYS_KB", ClsComFnc::GSYSTEM_KB, $strSQL);
            }

            $strSQL .= "        WHERE  KR.BUSYO_CD = BUS.BUSYO_CD" . "\r\n";
            $strSQL .= "        AND    BUS.PRN_KB5 = 'O'" . "\r\n";

            if (trim($strBusyoCDF) != "") {
                $strSQL .= "        AND    BUS.BUSYO_CD >= '@F_BUSYO'" . "\r\n";
            }

            if (trim($strBusyoCDT) != "") {
                $strSQL .= "        AND    BUS.BUSYO_CD <= '@T_BUSYO'" . "\r\n";
            }

            $strSQL .= ")" . "\r\n";
        } else {
            $strSQL .= "       (SELECT BUS.BUSYO_CD" . "\r\n";
            $strSQL .= "        FROM   HBUSYO BUS" . "\r\n";
            $strSQL .= "        INNER JOIN HKSPATTERNLISTMST PTN" . "\r\n";
            $strSQL .= "        ON     PTN.PATTERN_NO = '@PTNNO'" . "\r\n";
            $strSQL .= "        AND    BUS.BUSYO_CD = PTN.BUSYO_CD" . "\r\n";
            $strSQL .= "        WHERE  KR.BUSYO_CD = BUS.BUSYO_CD" . "\r\n";
            $strSQL .= "       )" . "\r\n";
        }

        $strSQL = str_replace("@F_BUSYO", $strBusyoCDF, $strSQL);
        $strSQL = str_replace("@T_BUSYO", $strBusyoCDT, $strSQL);
        $strSQL = str_replace("@PTNNO", $intPatternNo, $strSQL);
        $strSQL = str_replace("@UPDUSER", $strUpdUser, $strSQL);

        return $strSQL;
    }

    public function fncWKInsert_sql($NENGTU_cboYM, $UPDAPP, $UPDCLT, $UPDUSER)
    {
        $sqlstr = "";
        $sqlstr .= "INSERT INTO \r";
        $sqlstr .= "	WK_HKANRIZ_KEIEISEIKA \r";
        $sqlstr .= "(";
        $sqlstr .= "KEIJO_DT\r";
        $sqlstr .= ",KAMOKU_CD\r";
        $sqlstr .= ",HIMOKU_CD\r";
        $sqlstr .= ",BUSYO_CD\r";
        $sqlstr .= ",L_GK\r";
        $sqlstr .= ",R_GK\r";
        $sqlstr .= ",TOU_ZAN\r";
        $sqlstr .= ",CREATE_DATE\r";
        //---ysj 20151113 noted s---
        //$sqlstr.=",LINE_NO\r";
        //---ysj 20151113 noted e---
        $sqlstr .= ",UPD_SYA_CD\r";
        $sqlstr .= ",UPD_PRG_ID\r";
        $sqlstr .= ",UPD_CLT_NM\r";
        $sqlstr .= ",UPD_DATE\r";
        $sqlstr .= ")";

        $sqlstr .= " SELECT \r";
        $sqlstr .= "KEIJO_DT\r";
        $sqlstr .= ",KAMOKU_CD\r";
        $sqlstr .= ",HIMOKU_CD\r";
        $sqlstr .= ",BUSYO_CD\r";
        $sqlstr .= ",L_GK\r";
        $sqlstr .= ",R_GK\r";
        $sqlstr .= ",TOU_ZAN\r";
        $sqlstr .= ",CREATE_DATE\r";
        //---ysj 20151113 noted s---
        //$sqlstr.=",LINE_NO\r";
        //---ysj 20151113 noted e---
        $sqlstr .= ", '@UPDUSER' \r\n";
        $sqlstr .= ", '@UPDAPP' \r\n";
        $sqlstr .= ", '@UPDCLT' \r\n";
        $sqlstr .= ",SYSDATE \r";
        $sqlstr .= " FROM \r";
        $sqlstr .= "	HKANRIZ\r";
        $sqlstr .= "	WHERE ";

        $tpM = substr($NENGTU_cboYM, 4, 2);
        $tpY = substr($NENGTU_cboYM, 0, 4);
        if ((int) $tpM >= 10) {
            $tpY = (int) $tpY - 1;
            $KIFM = $tpY . "10";
        } else {
            $KIFM = (int) $tpY - 2;
            $KIFM = $KIFM . "10";
        }

        $sqlstr .= " KEIJO_DT >='" . $KIFM . "'";
        $sqlstr .= " AND KEIJO_DT <='" . $NENGTU_cboYM . "'";

        //        if ($busyoCD_From != "" && $busyoCD_TO == "") {
//           $sqlstr .= " AND BUSYO_CD >= $busyoCD_From\r";
//        } elseif ($busyoCD_From == "" && $busyoCD_TO != "") {
//            $sqlstr .= " AND BUSYO_CD <= $busyoCD_TO\r";
//        } else {
//            if ($busyoCD_From != "" && $busyoCD_TO != "") {
//                $sqlstr .= " AND BUSYO_CD >=$busyoCD_From AND BUSYO_CD<=$busyoCD_TO";
        //           }
        //       }
        $sqlstr = str_replace("@UPDUSER", $UPDUSER, $sqlstr);
        $sqlstr = str_replace("@UPDAPP", $UPDAPP, $sqlstr);
        $sqlstr = str_replace("@UPDCLT", $UPDCLT, $sqlstr);
        //$this->log($sqlstr);
        return $sqlstr;
    }

    public function fncWKTRUNCATE_sql()
    {
        $sqlstr = "TRUNCATE TABLE WK_HKANRIZ_KEIEISEIKA";
        return $sqlstr;
    }

    public function fncGetAuth($postData = null)
    {
        $str_sql = $this->fncGetAuth_sql($postData);
        return parent::select($str_sql);
    }

    public function fncGetAuth_sql($postData = null)
    {
        $strSQL = "";
        $strSQL .= " SELECT SYAIN_NO " . "\r\n";
        $strSQL .= "     ,      BUSYO_CD " . "\r\n";
        $strSQL .= " FROM HAUTHORITY_CTL " . "\r\n";
        $strSQL .= " WHERE SYAIN_NO = '@SYAIN_NO' " . "\r\n";
        $strSQL .= " AND   SYS_KB = '@SYS_KB'" . "\r\n";

        $strSQL .= " GROUP BY SYAIN_NO " . "\r\n";
        $strSQL .= "     ,        BUSYO_CD " . "\r\n";

        $strSQL = str_replace("@SYS_KB", "11", $strSQL);
        $strSQL = str_replace("@SYAIN_NO", $postData, $strSQL);
        return $strSQL;
    }

    public function selectComment_sql($KI, $BUSYOCD)
    {
        $strSQL = "";
        $strSQL .= "SELECT ";
        $strSQL .= " COMMENT_STR ";
        $strSQL .= "FROM ";
        $strSQL .= " HSIM_COMMENT ";
        $strSQL .= "WHERE ";
        $strSQL .= " KI='{$KI}' ";
        $strSQL .= "AND ";
        $strSQL .= " NENGETU='000000' ";
        $strSQL .= "AND ";
        $strSQL .= " BUSYO_CD='{$BUSYOCD}' ";
        return $strSQL;
    }

    public function selectComment($KI, $BUSYOCD)
    {
        return parent::select($this->selectComment_sql($KI, $BUSYOCD));
    }


    public function fncSelectYosan_sql($KI, $busyoCD_From, $busyoCD_TO, $pattern_No)
    {
        $sqlstr = "";
        $sqlstr .= "SELECT \r";
        $sqlstr .= "	KI\r";
        $sqlstr .= ",	NENGETU\r";
        $sqlstr .= ",	BUSYO_CD\r";
        $sqlstr .= ",	BUSYO_NM\r";
        $sqlstr .= ",	LINE_NO\r";
        $sqlstr .= ",	KEIKAKU\r";
        $sqlstr .= ",	JISSEKI\r";

        //$sqlstr .= ",	SHIHYO\r";
        //$sqlstr .= ",	KEIKAKUSA\r";
        //$sqlstr .= ",	ZENNENHI\r";
//        $sqlstr .= "	FROM VW_KEIEISEIKA_YOSANHYO \r";
//        $sqlstr .= "	FROM VW_KEIEISEIKA_ALL \r";
        $sqlstr .= "	FROM VW_KEIEISEIKA_YOSANHYO \r";

        $sqlstr .= "	WHERE\r";

        //        $KI = (int)$KI -1;
        $KI = (int) $KI;
        $sqlstr .= "	KI='" . $KI . "'";

        //        $tpM = substr($NENGTU_cboYM, 4, 2);
//        $tpY = substr($NENGTU_cboYM, 0, 4);
//        if ((int)$tpM >= 10) {
//            $KIFM = $tpY . "10";
//        } else {
//            $KIFM = (int)$tpY - 1;
//            $KIFM = $KIFM . "10";
//        //}
//        $sqlstr .= " NENGETU >='" . $KIFM . "'";
//        $sqlstr .= " AND NENGETU <='" . str_replace("/", "", $NENGTU_cboYM) . "'";

        if ($pattern_No == 0) {
            if ($busyoCD_From == "" && $busyoCD_TO == "") {

            } else {
                if ($busyoCD_From != "" && $busyoCD_TO == "") {
                    $sqlstr .= " AND BUSYO_CD >= '$busyoCD_From'\r";
                } elseif ($busyoCD_From == "" && $busyoCD_TO != "") {
                    $sqlstr .= " AND BUSYO_CD <= '$busyoCD_TO'\r";
                } else {
                    if ($busyoCD_From == $busyoCD_TO) {
                        $sqlstr .= " AND BUSYO_CD ='$busyoCD_From' ";
                    } else {
                        $sqlstr .= " AND BUSYO_CD >='$busyoCD_From' AND BUSYO_CD<='$busyoCD_TO'";
                    }
                }
            }
        } else {
            $sqlstr .= "	AND BUSYO_CD IN ( ";
            $sqlstr .= "SELECT BUSYO_CD FROM HKSPATTERNLISTMST_KRSS WHERE PATTERN_NO=$pattern_No ";
            $sqlstr .= " UNION ";
            $sqlstr .= "SELECT DISTINCT BUSYO_CD FROM HTTLBUSYO WHERE  TOTAL_BUSYO_CD IN ";
            $sqlstr .= "(SELECT BUSYO_CD FROM HKSPATTERNLISTMST_KRSS WHERE PATTERN_NO=$pattern_No)";
            $sqlstr .= "	)";
        }

        $sqlstr .= "ORDER BY \r";
        $sqlstr .= "BUSYO_CD  \r";
        $sqlstr .= ",LINE_NO  \r";
        //$this->log($sqlstr);
        return $sqlstr;
    }


}
