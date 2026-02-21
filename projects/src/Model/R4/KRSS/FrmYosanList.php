<?php
namespace App\Model\R4\KRSS;

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFnc;
use App\Model\R4\Component\ClsComFncKRSS;

class FrmYosanList extends ClsComDb
{
    // 解放が必要な変数をメンバーに設定
    protected $conn_orl = null;
    protected $Sel_Array = null;
    public $ClsComFnc;
    //--execute--
    public function fncAuthority()
    {
        $sql = $this->fncAuthority_sql();
        return parent::select($sql);
    }

    //-EXCELデータ取込 s-
    public function fncSQL1()
    {
        $sql = $this->fncSQL1_sql();
        return parent::select($sql);
    }

    public function fncSQL3($ki, $busyo_cd)
    {
        $sql = $this->fncSQL3_sql($ki, $busyo_cd);
        return parent::Do_Execute($sql);
    }

    public function fncSQL4()
    {
        $sql = $this->fncSQL4_sql();
        return parent::delete($sql);
    }

    public function fncSQL5($excelDataArr, $UPDAPP, $UPDCLTNM, $UPDUSER)
    {
        $sql = $this->fncSQL5_sql($excelDataArr, $UPDAPP, $UPDCLTNM, $UPDUSER);
        return parent::insert($sql);
    }

    public function fncSQL6($UPDAPP, $UPDCLTNM, $UPDUSER)
    {
        $sql = $this->fncSQL6_sql($UPDAPP, $UPDCLTNM, $UPDUSER);
        return parent::insert($sql);
    }

    //-EXCELデータ取込 e-

    //-集計部署単位の集計 s-
    public function fncSQL2_DataTotal($KI)
    {
        $sql = $this->fncSQL2_DataTotal_sql($KI);
        return parent::select($sql);
    }

    public function fncDeleteHSHIHYO_DataTotal($KI)
    {
        $sql = $this->fncDeleteHSHIHYO_DataTotal_sql($KI);
        return parent::delete($sql);
    }

    public function fncInsertHSHIHYO_DataTotal($KI, $UPDAPP, $UPDCLTNM, $UPDUSER)
    {
        $sql = $this->fncInsertHSHIHYO_DataTotal_sql($KI, $UPDAPP, $UPDCLTNM, $UPDUSER);
        return parent::insert($sql);
    }

    public function fncDeleteHTTLYOSAN_DataTotal($KI)
    {
        $sql = $this->fncDeleteHTTLYOSAN_DataTotal_sql($KI);
        return parent::delete($sql);
    }

    public function fncInsertHTTLYOSAN_DataTotal($KI, $UPDAPP, $UPDCLTNM, $UPDUSER)
    {
        $sql = $this->fncInsertHTTLYOSAN_DataTotal_sql($KI, $UPDAPP, $UPDCLTNM, $UPDUSER);
        return parent::insert($sql);
    }

    public function fncInsertHTTLSHIHYO_DataTotal($KI, $UPDAPP, $UPDCLTNM, $UPDUSER)
    {
        $sql = $this->fncInsertHTTLSHIHYO_DataTotal_sql($KI, $UPDAPP, $UPDCLTNM, $UPDUSER);
        return parent::insert($sql);
    }

    //-集計部署単位の集計 e-

    //-EXCEL出力 s-
    public function subCtlSql()
    {
        $sql = $this->subCtlSql_sql();
        return parent::select($sql);
    }

    public function fncYOJITSUSQL($strKisyu, $KI, $Y, $M)
    {
        $sql = $this->fncYOJITSUSQL_sql($strKisyu, $KI, $Y, $M);
        return parent::select($sql);
    }

    //-EXCEL出力 e-

    //--sql--
    //-権限のﾁｪｯｸを行う s-
    private function fncAuthority_sql()
    {
        $ClsComFncKRSS = new ClsComFncKRSS();
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $sql = "";
        $sql .= " SELECT SYAIN_NO \n";
        $sql .= "     ,      BUSYO_CD \n";
        $sql .= " FROM HAUTHORITY_CTL \n";
        $sql .= " WHERE SYAIN_NO = '@SYAIN_NO' \n";
        $sql .= " AND   SYS_KB = '@SYS_KB'\n";

        $sql .= " GROUP BY SYAIN_NO \n";
        $sql .= "     ,        BUSYO_CD \n";
        $sql = str_replace("@SYAIN_NO", $UPDUSER, $sql);
        //'2010/07/01 INS
        $sql = str_replace("@SYS_KB", $ClsComFncKRSS::GSYSTEM_KB, $sql);
        //'2010/07/01 INS
        return $sql;
    }

    //-権限のﾁｪｯｸを行う e-

    //-EXCELデータ取込 s-
    public function fncSQL1_sql()
    {
        $sqlstr = "";
        $sqlstr .= " SELECT ID, \n\r";
        $sqlstr .= "        KI + 1 RKI\n\r";
        $sqlstr .= " FROM HKEIRICTL  \n\r";
        $sqlstr .= " WHERE  ID = '01' \n\r";
        return $sqlstr;
    }

    public function fncSQL3_sql($ki, $busyo_cd)
    {
        $sqlstr = "";
        $sqlstr .= " DELETE FROM HYOSAN_NEW\r\n";
        $sqlstr .= " WHERE  KI = @KI\r\n";
        $sqlstr .= " AND    BUSYO_CD = '@BUSYO'\r\n";
        $sqlstr = str_replace("@KI", $ki, $sqlstr);
        $sqlstr = str_replace("@BUSYO", str_pad($busyo_cd, 3, "0", STR_PAD_LEFT), $sqlstr);
        return $sqlstr;
    }

    public function fncSQL4_sql()
    {
        $sqlstr = "DELETE FROM WK_YOSANTORIKOMI";
        return $sqlstr;
    }

    public function fncSQL5_sql($excelDataArr, $UPDAPP, $UPDCLT, $UPDUSER)
    {
        $this->ClsComFnc = new ClsComFnc();

        $sqlstr = "";
        $sqlstr .= "INSERT INTO WK_YOSANTORIKOMI ( \r\n";
        $sqlstr .= "  YOSAN_YMD \r\n";
        $sqlstr .= ", KI \r\n";
        $sqlstr .= ", BUSYO_CD \r\n";
        $sqlstr .= ", EXCEL_LINE_NO \r\n";
        $sqlstr .= ", YOSAN_GK10 \r\n";
        $sqlstr .= ", YOSAN_GK11 \r\n";
        $sqlstr .= ", YOSAN_GK12 \r\n";
        $sqlstr .= ", YOSAN_GK1 \r\n";
        $sqlstr .= ", YOSAN_GK2 \r\n";
        $sqlstr .= ", YOSAN_GK3 \r\n";
        $sqlstr .= ", YOSAN_GK4 \r\n";
        $sqlstr .= ", YOSAN_GK5 \r\n";
        $sqlstr .= ", YOSAN_GK6 \r\n";
        $sqlstr .= ", YOSAN_GK7 \r\n";
        $sqlstr .= ", YOSAN_GK8 \r\n";
        $sqlstr .= ", YOSAN_GK9 \r\n";
        $sqlstr .= ", UPD_SYA_CD \r\n";
        $sqlstr .= ", UPD_PRG_ID \r\n";
        $sqlstr .= ", UPD_CLT_NM \r\n";

        $sqlstr .= ") VALUES ( \r\n";
        $sqlstr .= "  '@YOSAN_YMD' \r\n";
        $sqlstr .= ", @KI \r\n";
        $sqlstr .= ", '@BUSYO_CD' \r\n";
        $sqlstr .= ", @EXCEL_LINE_NO \r\n";
        $sqlstr .= ", @YOSAN_GK10 \r\n";
        $sqlstr .= ", @YOSAN_GK11 \r\n";
        $sqlstr .= ", @YOSAN_GK12 \r\n";
        $sqlstr .= ", @YOSAN_GK1 \r\n";
        $sqlstr .= ", @YOSAN_GK2 \r\n";
        $sqlstr .= ", @YOSAN_GK3 \r\n";
        $sqlstr .= ", @YOSAN_GK4 \r\n";
        $sqlstr .= ", @YOSAN_GK5 \r\n";
        $sqlstr .= ", @YOSAN_GK6 \r\n";
        $sqlstr .= ", @YOSAN_GK7 \r\n";
        $sqlstr .= ", @YOSAN_GK8 \r\n";
        $sqlstr .= ", @YOSAN_GK9 \r\n";
        $sqlstr .= ", '@UPDUSER' \r\n";
        $sqlstr .= ", '@UPDAPP' \r\n";
        $sqlstr .= ", '@UPDCLT' \r\n";
        $sqlstr .= ") \r\n";
        $sqlstr = str_replace("@KI", $excelDataArr["KI"], $sqlstr);
        $sqlstr = str_replace("@BUSYO_CD", str_pad($excelDataArr["BUSYO_CD"], 3, "0", STR_PAD_LEFT), $sqlstr);
        $sqlstr = str_replace("@YOSAN_YMD", ($excelDataArr["KI"] + 1917) . "10", $sqlstr);
        $sqlstr = str_replace("@EXCEL_LINE_NO", $excelDataArr["LINE_NO"], $sqlstr);

        $sqlstr = str_replace("@UPDUSER", $UPDUSER, $sqlstr);
        $sqlstr = str_replace("@UPDAPP", $UPDAPP, $sqlstr);
        $sqlstr = str_replace("@UPDCLT", $UPDCLT, $sqlstr);

        /*if ($iRow < 31) {
               $sqlstr = str_replace("@EXCEL_LINE_NO", $this -> ClsComFnc -> fncRoundDou($iRow / 2, 0, 0) + 1);
               } else {
               if ($iRow > 31) {
               $sqlstr = str_replace("@EXCEL_LINE_NO", $this -> ClsComFnc -> fncRoundDou($iRow / 2, 0, 0));
               }
               }*/

        $varT = "YOSAN_GK";
        $varF = 0;
        for ($k = 1; $k <= 12; $k++) {
            if ($excelDataArr["YSN_GK" . $k] == "" || $excelDataArr["YSN_GK" . $k] == null) {
                $varF = "NULL";
            } else {
                $varF = $excelDataArr["YSN_GK" . $k];
            }
            $sqlstr = str_replace("@" . $varT . $k . " ", $varF, $sqlstr);
        }
        return $sqlstr;
    }

    public function fncSQL6_sql($UPDAPP, $UPDCLT, $UPDUSER)
    {
        $sqlstr = "";
        $this->ClsComFnc = new ClsComFnc();

        $sqlstr .= "INSERT INTO HYOSAN_NEW \r\n";
        $sqlstr .= "(      YOSAN_YMD \r\n";
        $sqlstr .= ",      KI \r\n";
        $sqlstr .= ",      KKR_BUSYO_CD \r\n";
        $sqlstr .= ",      BUSYO_CD \r\n";
        $sqlstr .= ",      LINE_NO \r\n";
        $sqlstr .= ",      UPD_FPG \r\n";
        $sqlstr .= ",      YSN_GK10 \r\n";
        $sqlstr .= ",      YSN_GK11 \r\n";
        $sqlstr .= ",      YSN_GK12 \r\n";
        $sqlstr .= ",      YSN_GK1 \r\n";
        $sqlstr .= ",      YSN_GK2 \r\n";
        $sqlstr .= ",      YSN_GK3 \r\n";
        $sqlstr .= ",      YSN_GK4 \r\n";
        $sqlstr .= ",      YSN_GK5 \r\n";
        $sqlstr .= ",      YSN_GK6 \r\n";
        $sqlstr .= ",      YSN_GK7 \r\n";
        $sqlstr .= ",      YSN_GK8 \r\n";
        $sqlstr .= ",      YSN_GK9 \r\n";
        $sqlstr .= ",      UPD_DATE \r\n";
        $sqlstr .= ",      CREATE_DATE \r\n";
        $sqlstr .= ",      UPD_SYA_CD \r\n";
        $sqlstr .= ",      UPD_PRG_ID \r\n";
        $sqlstr .= ",      UPD_CLT_NM \r\n";
        $sqlstr .= ",      TOUROKU_FLG \r\n";
        //'2007/08/19 INS 確定・仮登録フラグ追加
        $sqlstr .= ") \r\n";
        $sqlstr .= "SELECT YTK.YOSAN_YMD \r\n";
        $sqlstr .= ",      YTK.KI \r\n";
        $sqlstr .= ",      '   ' \r\n";
        $sqlstr .= ",      YTK.BUSYO_CD \r\n";
        $sqlstr .= ",      YLINE.LINE_NO \r\n";
        $sqlstr .= ",      '*' \r\n";
        $sqlstr .= ",      SUM(YTK.YOSAN_GK10 * POWER(10,(NVL(YLINE.RND_POS,0))) * NVL(YLINE.CAL_KB,1)) \r\n";
        $sqlstr .= ",      SUM(YTK.YOSAN_GK11 * POWER(10,(NVL(YLINE.RND_POS,0))) * NVL(YLINE.CAL_KB,1)) \r\n";
        $sqlstr .= ",      SUM(YTK.YOSAN_GK12 * POWER(10,(NVL(YLINE.RND_POS,0))) * NVL(YLINE.CAL_KB,1)) \r\n";
        $sqlstr .= ",      SUM(YTK.YOSAN_GK1 * POWER(10,(NVL(YLINE.RND_POS,0))) * NVL(YLINE.CAL_KB,1)) \r\n";
        $sqlstr .= ",      SUM(YTK.YOSAN_GK2 * POWER(10,(NVL(YLINE.RND_POS,0))) * NVL(YLINE.CAL_KB,1)) \r\n";
        $sqlstr .= ",      SUM(YTK.YOSAN_GK3 * POWER(10,(NVL(YLINE.RND_POS,0))) * NVL(YLINE.CAL_KB,1)) \r\n";
        $sqlstr .= ",      SUM(YTK.YOSAN_GK4 * POWER(10,(NVL(YLINE.RND_POS,0))) * NVL(YLINE.CAL_KB,1)) \r\n";
        $sqlstr .= ",      SUM(YTK.YOSAN_GK5 * POWER(10,(NVL(YLINE.RND_POS,0))) * NVL(YLINE.CAL_KB,1)) \r\n";
        $sqlstr .= ",      SUM(YTK.YOSAN_GK6 * POWER(10,(NVL(YLINE.RND_POS,0))) * NVL(YLINE.CAL_KB,1)) \r\n";
        $sqlstr .= ",      SUM(YTK.YOSAN_GK7 * POWER(10,(NVL(YLINE.RND_POS,0))) * NVL(YLINE.CAL_KB,1)) \r\n";
        $sqlstr .= ",      SUM(YTK.YOSAN_GK8 * POWER(10,(NVL(YLINE.RND_POS,0))) * NVL(YLINE.CAL_KB,1)) \r\n";
        $sqlstr .= ",      SUM(YTK.YOSAN_GK9 * POWER(10,(NVL(YLINE.RND_POS,0))) * NVL(YLINE.CAL_KB,1)) \r\n";
        $sqlstr .= ",      SYSDATE \r\n";
        $sqlstr .= ",      @CREATE_DATE \r\n";
        $sqlstr .= ",      '@UPDUSER' \r\n";
        $sqlstr .= ",      '@UPDAPP' \r\n";
        $sqlstr .= ",      '@UPDCLT' \r\n";
        $sqlstr .= ",      @TOUROKUFLG \r\n";
        //'20007/08/19 INS

        $sqlstr .= "FROM   WK_YOSANTORIKOMI YTK \r\n";
        $sqlstr .= "INNER JOIN HBUSYO BUS \r\n";
        $sqlstr .= "ON     BUS.BUSYO_CD = YTK.BUSYO_CD \r\n";
        //		$sqlstr .= "INNER JOIN HSIMYOSANTORIKOMIMST_NEW YLINE \r\n";
        $sqlstr .= "INNER JOIN HSIMYOSANTORIKOMIMST_KRSS YLINE \r\n";
        $sqlstr .= "ON     YLINE.SIM_LINE_NO = YTK.EXCEL_LINE_NO \r\n";
        $sqlstr .= "WHERE  BUS.TORIKOMI_BUSYO_KB = YLINE.BUSYO_KB \r\n";
        $sqlstr .= "GROUP BY YTK.YOSAN_YMD \r\n";
        $sqlstr .= ",        YTK.KI \r\n";
        $sqlstr .= ",        YTK.BUSYO_CD \r\n";
        $sqlstr .= ",        YLINE.LINE_NO \r\n";

        $sqlstr = str_replace("@UPDUSER", $UPDUSER, $sqlstr);
        $sqlstr = str_replace("@UPDAPP", $UPDAPP, $sqlstr);
        $sqlstr = str_replace("@UPDCLT", $UPDCLT, $sqlstr);
        $sqlstr = str_replace("@CREATE_DATE", "SYSDATE", $sqlstr);
        $sqlstr = str_replace("@TOUROKUFLG", "1", $sqlstr);
        /*If String.IsNullOrEmpty(strCRE_DT) Then
               strsql.Replace("@CREATE_DATE", "SYSDATE")
               Else
               strsql.Replace("@CREATE_DATE", "TO_DATE('" & strCRE_DT & "','YYYY/MM/DD HH24:MI:SS')")
               End If*/
        return $sqlstr;
    }

    //-EXCELデータ取込 e-

    //-集計部署単位の集計 s-
    public function fncSQL2_DataTotal_sql($KI)
    {
        $sqlstr = "";
        $sqlstr .= " SELECT KI \n\r";
        $sqlstr .= " FROM   HYOSAN_NEW  \n\r";
        $sqlstr .= " WHERE  KI = '@KI' \n\r";
        $sqlstr = str_replace("@KI", $KI, $sqlstr);
        return $sqlstr;

    }

    public function fncDeleteHSHIHYO_DataTotal_sql($KI)
    {
        $sqlstr = "";
        $sqlstr .= "DELETE FROM HSHIHYO_NEW ";
        $sqlstr .= "WHERE KI=@KI";
        $sqlstr = str_replace("@KI", $KI, $sqlstr);
        return $sqlstr;
    }

    public function fncInsertHSHIHYO_DataTotal_sql($KI, $UPDAPP, $UPDCLT, $UPDUSER)
    {
        $sqlstr = "";

        $sqlstr .= "INSERT INTO HSHIHYO_NEW \n\r";
        $sqlstr .= "(      YOSAN_YMD \n\r";
        $sqlstr .= ",      KI \n\r";
        $sqlstr .= ",      KKR_BUSYO_CD \n\r";
        $sqlstr .= ",      BUSYO_CD \n\r";
        $sqlstr .= ",      LINE_NO \n\r";
        $sqlstr .= ",      UPD_FPG \n\r";
        $sqlstr .= ",      YSN_GK10 \n\r";
        $sqlstr .= ",      YSN_GK11 \n\r";
        $sqlstr .= ",      YSN_GK12 \n\r";
        $sqlstr .= ",      YSN_GK1 \n\r";
        $sqlstr .= ",      YSN_GK2 \n\r";
        $sqlstr .= ",      YSN_GK3 \n\r";
        $sqlstr .= ",      YSN_GK4 \n\r";
        $sqlstr .= ",      YSN_GK5 \n\r";
        $sqlstr .= ",      YSN_GK6 \n\r";
        $sqlstr .= ",      YSN_GK7 \n\r";
        $sqlstr .= ",      YSN_GK8 \n\r";
        $sqlstr .= ",      YSN_GK9 \n\r";
        $sqlstr .= ",      UPD_DATE \n\r";
        $sqlstr .= ",      CREATE_DATE \n\r";
        //'TODO 2006/12/08 UPD Start
        $sqlstr .= ",      UPD_SYA_CD \n\r";
        $sqlstr .= ",      UPD_PRG_ID \n\r";
        $sqlstr .= ",      UPD_CLT_NM \n\r";
        //'2006/12/08 UPD End

        $sqlstr .= ") \n\r";
        $sqlstr .= "SELECT YSN_TBL.YOSAN_YMD \n\r";
        $sqlstr .= ",      YSN_TBL.KI \n\r";
        $sqlstr .= ",      NULL \n\r";
        $sqlstr .= ",      YSN_TBL.BUSYO_CD \n\r";
        $sqlstr .= ",      YSN_TBL.LINE_NO \n\r";
        $sqlstr .= ",      NULL \n\r";
        $sqlstr .= ",      (CASE WHEN NVL(SHY_TBL.YSN_GK10,0) <> 0 AND YL.IDX_CAL_KB = '1' THEN ROUND(NVL(YSN_TBL.YSN_GK10,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY_TBL.YSN_GK10,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(SHY_TBL.YSN_GK10,0) <> 0 AND YL.IDX_CAL_KB = '2' THEN ROUND(NVL(YSN_TBL.YSN_GK10,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY_TBL.YSN_GK10,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN_TBL.YSN_GK10,0) <> 0 AND YL.IDX_CAL_KB = '3' THEN ROUND(NVL(SHY_TBL.YSN_GK10,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN_TBL.YSN_GK10,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN_TBL.YSN_GK10,0) <> 0 AND YL.IDX_CAL_KB = '4' THEN ROUND(NVL(SHY_TBL.YSN_GK10,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN_TBL.YSN_GK10,0),0) \n\r";
        $sqlstr .= "        END) \n\r";
        $sqlstr .= ",      (CASE WHEN NVL(SHY_TBL.YSN_GK11,0) <> 0 AND YL.IDX_CAL_KB = '1' THEN ROUND(NVL(YSN_TBL.YSN_GK11,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY_TBL.YSN_GK11,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(SHY_TBL.YSN_GK11,0) <> 0 AND YL.IDX_CAL_KB = '2' THEN ROUND(NVL(YSN_TBL.YSN_GK11,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY_TBL.YSN_GK11,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN_TBL.YSN_GK11,0) <> 0 AND YL.IDX_CAL_KB = '3' THEN ROUND(NVL(SHY_TBL.YSN_GK11,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN_TBL.YSN_GK11,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN_TBL.YSN_GK11,0) <> 0 AND YL.IDX_CAL_KB = '4' THEN ROUND(NVL(SHY_TBL.YSN_GK11,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN_TBL.YSN_GK11,0),0) \n\r";
        $sqlstr .= "        END) \n\r";
        $sqlstr .= ",      (CASE WHEN NVL(SHY_TBL.YSN_GK12,0) <> 0 AND YL.IDX_CAL_KB = '1' THEN ROUND(NVL(YSN_TBL.YSN_GK12,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY_TBL.YSN_GK12,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(SHY_TBL.YSN_GK12,0) <> 0 AND YL.IDX_CAL_KB = '2' THEN ROUND(NVL(YSN_TBL.YSN_GK12,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY_TBL.YSN_GK12,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN_TBL.YSN_GK12,0) <> 0 AND YL.IDX_CAL_KB = '3' THEN ROUND(NVL(SHY_TBL.YSN_GK12,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN_TBL.YSN_GK12,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN_TBL.YSN_GK12,0) <> 0 AND YL.IDX_CAL_KB = '4' THEN ROUND(NVL(SHY_TBL.YSN_GK12,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN_TBL.YSN_GK12,0),0) \n\r";
        $sqlstr .= "        END) \n\r";
        $sqlstr .= ",      (CASE WHEN NVL(SHY_TBL.YSN_GK1,0) <> 0 AND YL.IDX_CAL_KB = '1' THEN ROUND(NVL(YSN_TBL.YSN_GK1,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY_TBL.YSN_GK1,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(SHY_TBL.YSN_GK1,0) <> 0 AND YL.IDX_CAL_KB = '2' THEN ROUND(NVL(YSN_TBL.YSN_GK1,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY_TBL.YSN_GK1,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN_TBL.YSN_GK1,0) <> 0 AND YL.IDX_CAL_KB = '3' THEN ROUND(NVL(SHY_TBL.YSN_GK1,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN_TBL.YSN_GK1,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN_TBL.YSN_GK1,0) <> 0 AND YL.IDX_CAL_KB = '4' THEN ROUND(NVL(SHY_TBL.YSN_GK1,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN_TBL.YSN_GK1,0),0) \n\r";
        $sqlstr .= "        END) \n\r";
        $sqlstr .= ",      (CASE WHEN NVL(SHY_TBL.YSN_GK2,0) <> 0 AND YL.IDX_CAL_KB = '1' THEN ROUND(NVL(YSN_TBL.YSN_GK2,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY_TBL.YSN_GK2,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(SHY_TBL.YSN_GK2,0) <> 0 AND YL.IDX_CAL_KB = '2' THEN ROUND(NVL(YSN_TBL.YSN_GK2,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY_TBL.YSN_GK2,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN_TBL.YSN_GK2,0) <> 0 AND YL.IDX_CAL_KB = '3' THEN ROUND(NVL(SHY_TBL.YSN_GK2,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN_TBL.YSN_GK2,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN_TBL.YSN_GK2,0) <> 0 AND YL.IDX_CAL_KB = '4' THEN ROUND(NVL(SHY_TBL.YSN_GK2,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN_TBL.YSN_GK2,0),0) \n\r";
        $sqlstr .= "        END) \n\r";
        $sqlstr .= ",      (CASE WHEN NVL(SHY_TBL.YSN_GK3,0) <> 0 AND YL.IDX_CAL_KB = '1' THEN ROUND(NVL(YSN_TBL.YSN_GK3,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY_TBL.YSN_GK3,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(SHY_TBL.YSN_GK3,0) <> 0 AND YL.IDX_CAL_KB = '2' THEN ROUND(NVL(YSN_TBL.YSN_GK3,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY_TBL.YSN_GK3,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN_TBL.YSN_GK3,0) <> 0 AND YL.IDX_CAL_KB = '3' THEN ROUND(NVL(SHY_TBL.YSN_GK3,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN_TBL.YSN_GK3,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN_TBL.YSN_GK3,0) <> 0 AND YL.IDX_CAL_KB = '4' THEN ROUND(NVL(SHY_TBL.YSN_GK3,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN_TBL.YSN_GK3,0),0) \n\r";
        $sqlstr .= "        END) \n\r";
        $sqlstr .= ",      (CASE WHEN NVL(SHY_TBL.YSN_GK4,0) <> 0 AND YL.IDX_CAL_KB = '1' THEN ROUND(NVL(YSN_TBL.YSN_GK4,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY_TBL.YSN_GK4,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(SHY_TBL.YSN_GK4,0) <> 0 AND YL.IDX_CAL_KB = '2' THEN ROUND(NVL(YSN_TBL.YSN_GK4,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY_TBL.YSN_GK4,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN_TBL.YSN_GK4,0) <> 0 AND YL.IDX_CAL_KB = '3' THEN ROUND(NVL(SHY_TBL.YSN_GK4,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN_TBL.YSN_GK4,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN_TBL.YSN_GK4,0) <> 0 AND YL.IDX_CAL_KB = '4' THEN ROUND(NVL(SHY_TBL.YSN_GK4,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN_TBL.YSN_GK4,0),0) \n\r";
        $sqlstr .= "        END) \n\r";
        $sqlstr .= ",      (CASE WHEN NVL(SHY_TBL.YSN_GK5,0) <> 0 AND YL.IDX_CAL_KB = '1' THEN ROUND(NVL(YSN_TBL.YSN_GK5,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY_TBL.YSN_GK5,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(SHY_TBL.YSN_GK5,0) <> 0 AND YL.IDX_CAL_KB = '2' THEN ROUND(NVL(YSN_TBL.YSN_GK5,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY_TBL.YSN_GK5,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN_TBL.YSN_GK5,0) <> 0 AND YL.IDX_CAL_KB = '3' THEN ROUND(NVL(SHY_TBL.YSN_GK5,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN_TBL.YSN_GK5,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN_TBL.YSN_GK5,0) <> 0 AND YL.IDX_CAL_KB = '4' THEN ROUND(NVL(SHY_TBL.YSN_GK5,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN_TBL.YSN_GK5,0),0) \n\r";
        $sqlstr .= "        END) \n\r";
        $sqlstr .= ",      (CASE WHEN NVL(SHY_TBL.YSN_GK6,0) <> 0 AND YL.IDX_CAL_KB = '1' THEN ROUND(NVL(YSN_TBL.YSN_GK6,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY_TBL.YSN_GK6,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(SHY_TBL.YSN_GK6,0) <> 0 AND YL.IDX_CAL_KB = '2' THEN ROUND(NVL(YSN_TBL.YSN_GK6,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY_TBL.YSN_GK6,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN_TBL.YSN_GK6,0) <> 0 AND YL.IDX_CAL_KB = '3' THEN ROUND(NVL(SHY_TBL.YSN_GK6,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN_TBL.YSN_GK6,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN_TBL.YSN_GK6,0) <> 0 AND YL.IDX_CAL_KB = '4' THEN ROUND(NVL(SHY_TBL.YSN_GK6,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN_TBL.YSN_GK6,0),0) \n\r";
        $sqlstr .= "        END) \n\r";
        $sqlstr .= ",      (CASE WHEN NVL(SHY_TBL.YSN_GK7,0) <> 0 AND YL.IDX_CAL_KB = '1' THEN ROUND(NVL(YSN_TBL.YSN_GK7,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY_TBL.YSN_GK7,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(SHY_TBL.YSN_GK7,0) <> 0 AND YL.IDX_CAL_KB = '2' THEN ROUND(NVL(YSN_TBL.YSN_GK7,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY_TBL.YSN_GK7,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN_TBL.YSN_GK7,0) <> 0 AND YL.IDX_CAL_KB = '3' THEN ROUND(NVL(SHY_TBL.YSN_GK7,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN_TBL.YSN_GK7,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN_TBL.YSN_GK7,0) <> 0 AND YL.IDX_CAL_KB = '4' THEN ROUND(NVL(SHY_TBL.YSN_GK7,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN_TBL.YSN_GK7,0),0) \n\r";
        $sqlstr .= "        END) \n\r";
        $sqlstr .= ",      (CASE WHEN NVL(SHY_TBL.YSN_GK8,0) <> 0 AND YL.IDX_CAL_KB = '1' THEN ROUND(NVL(YSN_TBL.YSN_GK8,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY_TBL.YSN_GK8,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(SHY_TBL.YSN_GK8,0) <> 0 AND YL.IDX_CAL_KB = '2' THEN ROUND(NVL(YSN_TBL.YSN_GK8,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY_TBL.YSN_GK8,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN_TBL.YSN_GK8,0) <> 0 AND YL.IDX_CAL_KB = '3' THEN ROUND(NVL(SHY_TBL.YSN_GK8,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN_TBL.YSN_GK8,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN_TBL.YSN_GK8,0) <> 0 AND YL.IDX_CAL_KB = '4' THEN ROUND(NVL(SHY_TBL.YSN_GK8,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN_TBL.YSN_GK8,0),0) \n\r";
        $sqlstr .= "        END) \n\r";
        $sqlstr .= ",      (CASE WHEN NVL(SHY_TBL.YSN_GK9,0) <> 0 AND YL.IDX_CAL_KB = '1' THEN ROUND(NVL(YSN_TBL.YSN_GK9,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY_TBL.YSN_GK9,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(SHY_TBL.YSN_GK9,0) <> 0 AND YL.IDX_CAL_KB = '2' THEN ROUND(NVL(YSN_TBL.YSN_GK9,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY_TBL.YSN_GK9,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN_TBL.YSN_GK9,0) <> 0 AND YL.IDX_CAL_KB = '3' THEN ROUND(NVL(SHY_TBL.YSN_GK9,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN_TBL.YSN_GK9,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN_TBL.YSN_GK9,0) <> 0 AND YL.IDX_CAL_KB = '4' THEN ROUND(NVL(SHY_TBL.YSN_GK9,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN_TBL.YSN_GK9,0),0) \n\r";
        $sqlstr .= "        END) \n\r";
        $sqlstr .= ",      SYSDATE \n\r";
        $sqlstr .= ",      SYSDATE \n\r";
        //'TODO 2006/12/08 UPD Start
        $sqlstr .= ",      '@UPDUSER' \n\r";
        $sqlstr .= ",      '@UPDAPP' \n\r";
        $sqlstr .= ",      '@UPDCLT' \n\r";
        //'2006/12/08 UPD End

        //		$sqlstr .= "FROM    (SELECT DISTINCT BUSYO_KB, LINE_NO, IDX_LINE_NO, IDX_CAL_KB, IDX_RND_POS FROM HYOSANLINEMST_NEW) YL \n\r";
        $sqlstr .= "FROM    (SELECT DISTINCT BUSYO_KB, LINE_NO, IDX_LINE_NO, IDX_CAL_KB, IDX_RND_POS FROM HYOSANLINEMST_KRSS) YL \n\r";
        $sqlstr .= ",       (SELECT YSN.LINE_NO \n\r";
        $sqlstr .= "        ,      YLINE.IDX_LINE_NO \n\r";
        $sqlstr .= "        ,      YSN.BUSYO_CD \n\r";
        $sqlstr .= "        ,      BUS.BUSYO_KB \n\r";
        $sqlstr .= "		,      YSN.YOSAN_YMD \n\r";
        $sqlstr .= "        ,      YSN.KI \n\r";
        $sqlstr .= "        ,      YSN.YSN_GK10 \n\r";
        $sqlstr .= "		,      (YSN.YSN_GK10 + YSN.YSN_GK11) / 2 YSN_GK11 \n\r";
        $sqlstr .= "		,      (YSN.YSN_GK10 + YSN.YSN_GK11 + YSN.YSN_GK12) / 3  YSN_GK12 \n\r";
        $sqlstr .= "		,      (YSN.YSN_GK10 + YSN.YSN_GK11 + YSN.YSN_GK12 + YSN.YSN_GK1) / 4 YSN_GK1 \n\r";
        $sqlstr .= "        ,      (YSN.YSN_GK10 + YSN.YSN_GK11 + YSN.YSN_GK12 + YSN.YSN_GK1 + YSN.YSN_GK2) / 5 YSN_GK2 \n\r";
        $sqlstr .= "        ,      (YSN.YSN_GK10 + YSN.YSN_GK11 + YSN.YSN_GK12 + YSN.YSN_GK1 + YSN.YSN_GK2 + YSN.YSN_GK3) / 6 YSN_GK3 \n\r";
        $sqlstr .= "        ,      (YSN.YSN_GK10 + YSN.YSN_GK11 + YSN.YSN_GK12 + YSN.YSN_GK1 + YSN.YSN_GK2 + YSN.YSN_GK3 + YSN.YSN_GK4) / 7 YSN_GK4 \n\r";
        $sqlstr .= "        ,      (YSN.YSN_GK10 + YSN.YSN_GK11 + YSN.YSN_GK12 + YSN.YSN_GK1 + YSN.YSN_GK2 + YSN.YSN_GK3 + YSN.YSN_GK4 + YSN.YSN_GK5) / 8 YSN_GK5 \n\r";
        $sqlstr .= "		,      (YSN.YSN_GK10 + YSN.YSN_GK11 + YSN.YSN_GK12 + YSN.YSN_GK1 + YSN.YSN_GK2 + YSN.YSN_GK3 + YSN.YSN_GK4 + YSN.YSN_GK5 + YSN.YSN_GK6) / 9 YSN_GK6 \n\r";
        $sqlstr .= "		,      (YSN.YSN_GK10 + YSN.YSN_GK11 + YSN.YSN_GK12 + YSN.YSN_GK1 + YSN.YSN_GK2 + YSN.YSN_GK3 + YSN.YSN_GK4 + YSN.YSN_GK5 + YSN.YSN_GK6 + YSN.YSN_GK7) / 10 YSN_GK7 \n\r";
        $sqlstr .= "		,      (YSN.YSN_GK10 + YSN.YSN_GK11 + YSN.YSN_GK12 + YSN.YSN_GK1 + YSN.YSN_GK2 + YSN.YSN_GK3 + YSN.YSN_GK4 + YSN.YSN_GK5 + YSN.YSN_GK6 + YSN.YSN_GK7 + YSN.YSN_GK8) / 11 YSN_GK8 \n\r";
        $sqlstr .= "		,      (YSN.YSN_GK10 + YSN.YSN_GK11 + YSN.YSN_GK12 + YSN.YSN_GK1 + YSN.YSN_GK2 + YSN.YSN_GK3 + YSN.YSN_GK4 + YSN.YSN_GK5 + YSN.YSN_GK6 + YSN.YSN_GK7 + YSN.YSN_GK8 + YSN.YSN_GK9) / 12 YSN_GK9 \n\r";
        $sqlstr .= "		FROM   HYOSAN_NEW YSN \n\r";
        //$sqlstr .= "     ,      HYOSANLINEMST_NEW YLINE \n\r";
        $sqlstr .= "     ,      HYOSANLINEMST_KRSS YLINE \n\r";
        $sqlstr .= "        ,      HBUSYO BUS \n\r";
        $sqlstr .= "        WHERE  YSN.LINE_NO = YLINE.LINE_NO \n\r";
        $sqlstr .= "        AND    BUS.BUSYO_CD = YSN.BUSYO_CD \n\r";
        $sqlstr .= "        AND    BUS.BUSYO_KB = YLINE.BUSYO_KB \n\r";
        $sqlstr .= "        AND    NVL(BUS.SYUKEI_KB,'0') <> '1' \n\r";
        $sqlstr .= "        AND    YLINE.KO_TARGET_KB = '1' \n\r";
        $sqlstr .= "        AND    YSN.KI = '@KONKI' \n\r";
        $sqlstr .= "       ) YSN_TBL \n\r";
        $sqlstr .= ",       (SELECT YSN.LINE_NO SHIHYO_LINE \n\r";
        $sqlstr .= "        ,      YLINE.LINE_NO \n\r";
        $sqlstr .= "		,      BUS.BUSYO_CD \n\r";
        $sqlstr .= "        ,      BUS.BUSYO_KB \n\r";
        $sqlstr .= "       	,      YSN.YSN_GK10 \n\r";
        $sqlstr .= "		,      (YSN.YSN_GK10 + YSN.YSN_GK11) / 2 YSN_GK11 \n\r";
        $sqlstr .= "		,      (YSN.YSN_GK10 + YSN.YSN_GK11 + YSN.YSN_GK12) / 3  YSN_GK12 \n\r";
        $sqlstr .= "		,      (YSN.YSN_GK10 + YSN.YSN_GK11 + YSN.YSN_GK12 + YSN.YSN_GK1) / 4 YSN_GK1 \n\r";
        $sqlstr .= "        ,      (YSN.YSN_GK10 + YSN.YSN_GK11 + YSN.YSN_GK12 + YSN.YSN_GK1 + YSN.YSN_GK2) / 5 YSN_GK2 \n\r";
        $sqlstr .= "        ,      (YSN.YSN_GK10 + YSN.YSN_GK11 + YSN.YSN_GK12 + YSN.YSN_GK1 + YSN.YSN_GK2 + YSN.YSN_GK3) / 6 YSN_GK3 \n\r";
        $sqlstr .= "        ,      (YSN.YSN_GK10 + YSN.YSN_GK11 + YSN.YSN_GK12 + YSN.YSN_GK1 + YSN.YSN_GK2 + YSN.YSN_GK3 + YSN.YSN_GK4) / 7 YSN_GK4 \n\r";
        $sqlstr .= "        ,      (YSN.YSN_GK10 + YSN.YSN_GK11 + YSN.YSN_GK12 + YSN.YSN_GK1 + YSN.YSN_GK2 + YSN.YSN_GK3 + YSN.YSN_GK4 + YSN.YSN_GK5) / 8 YSN_GK5 \n\r";
        $sqlstr .= "		,      (YSN.YSN_GK10 + YSN.YSN_GK11 + YSN.YSN_GK12 + YSN.YSN_GK1 + YSN.YSN_GK2 + YSN.YSN_GK3 + YSN.YSN_GK4 + YSN.YSN_GK5 + YSN.YSN_GK6) / 9 YSN_GK6 \n\r";
        $sqlstr .= "		,      (YSN.YSN_GK10 + YSN.YSN_GK11 + YSN.YSN_GK12 + YSN.YSN_GK1 + YSN.YSN_GK2 + YSN.YSN_GK3 + YSN.YSN_GK4 + YSN.YSN_GK5 + YSN.YSN_GK6 + YSN.YSN_GK7) / 10 YSN_GK7 \n\r";
        $sqlstr .= "		,      (YSN.YSN_GK10 + YSN.YSN_GK11 + YSN.YSN_GK12 + YSN.YSN_GK1 + YSN.YSN_GK2 + YSN.YSN_GK3 + YSN.YSN_GK4 + YSN.YSN_GK5 + YSN.YSN_GK6 + YSN.YSN_GK7 + YSN.YSN_GK8) / 11 YSN_GK8 \n\r";
        $sqlstr .= "		,      (YSN.YSN_GK10 + YSN.YSN_GK11 + YSN.YSN_GK12 + YSN.YSN_GK1 + YSN.YSN_GK2 + YSN.YSN_GK3 + YSN.YSN_GK4 + YSN.YSN_GK5 + YSN.YSN_GK6 + YSN.YSN_GK7 + YSN.YSN_GK8 + YSN.YSN_GK9) / 12 YSN_GK9 \n\r";
        $sqlstr .= "		FROM   HYOSAN_NEW YSN \n\r";
        //		$sqlstr .= "     ,      HYOSANLINEMST_NEW YLINE \n\r";
        $sqlstr .= "     ,      HYOSANLINEMST_KRSS YLINE \n\r";
        $sqlstr .= "     ,      HBUSYO BUS \n\r";
        $sqlstr .= "		WHERE  YSN.LINE_NO = YLINE.IDX_LINE_NO \n\r";
        $sqlstr .= "        AND    BUS.BUSYO_CD = YSN.BUSYO_CD \n\r";
        $sqlstr .= "        AND    BUS.BUSYO_KB = YLINE.BUSYO_KB \n\r";
        $sqlstr .= "        AND    NVL(BUS.SYUKEI_KB,'0') <> '1' \n\r";
        $sqlstr .= "        AND    YLINE.KO_TARGET_KB = '1' \n\r";
        $sqlstr .= "        AND    YSN.KI = '@KONKI' \n\r";
        $sqlstr .= "       ) SHY_TBL \n\r";
        $sqlstr .= "WHERE  SHY_TBL.BUSYO_KB = YSN_TBL.BUSYO_KB \n\r";
        $sqlstr .= "AND    SHY_TBL.BUSYO_CD = YSN_TBL.BUSYO_CD \n\r";
        $sqlstr .= "AND    SHY_TBL.SHIHYO_LINE = YSN_TBL.IDX_LINE_NO \n\r";
        $sqlstr .= "AND    SHY_TBL.LINE_NO = YSN_TBL.LINE_NO \n\r";
        $sqlstr .= "AND    YSN_TBL.BUSYO_KB = YL.BUSYO_KB \n\r";
        $sqlstr .= "AND    YSN_TBL.LINE_NO = YL.LINE_NO \n\r";

        //strSQL.Replace("@KONKI", intKi)
        $sqlstr = str_replace("@KONKI", $KI, $sqlstr);
        $sqlstr = str_replace("@UPDUSER", $UPDUSER, $sqlstr);
        $sqlstr = str_replace("@UPDAPP", $UPDAPP, $sqlstr);
        $sqlstr = str_replace("@UPDCLT", $UPDCLT, $sqlstr);
        return $sqlstr;
    }

    public function fncDeleteHTTLYOSAN_DataTotal_sql($KI)
    {
        $sqlstr = "";
        $sqlstr .= "DELETE FROM HYOSAN_NEW \n\r";
        $sqlstr .= "WHERE  KI = @KI \n\r";
        $sqlstr .= "AND    BUSYO_CD IN (SELECT TTL.TOTAL_BUSYO_CD  \n\r";
        $sqlstr .= "                    FROM HTTLBUSYO TTL \n\r";
        $sqlstr .= "                    ,    HBUSYO BUS \n\r";
        $sqlstr .= "                    WHERE TTL.BUSYO_CD = BUS.BUSYO_CD \n\r";
        $sqlstr .= "                    AND   NVL(BUS.SYUKEI_KB,'0') <> '1') \n\r";
        $sqlstr = str_replace("@KI", $KI, $sqlstr);
        return $sqlstr;
    }

    public function fncInsertHTTLYOSAN_DataTotal_sql($KI, $UPDAPP, $UPDCLT, $UPDUSER)
    {

        $sqlstr = "";

        $sqlstr .= "INSERT INTO HYOSAN_NEW \n\r";
        $sqlstr .= "(      YOSAN_YMD \n\r";
        $sqlstr .= ",      KI \n\r";
        $sqlstr .= ",      KKR_BUSYO_CD \n\r";
        $sqlstr .= ",      BUSYO_CD \n\r";
        $sqlstr .= ",      LINE_NO \n\r";
        $sqlstr .= ",      UPD_FPG \n\r";
        $sqlstr .= ",      YSN_GK10 \n\r";
        $sqlstr .= ",      YSN_GK11 \n\r";
        $sqlstr .= ",      YSN_GK12 \n\r";
        $sqlstr .= ",      YSN_GK1 \n\r";
        $sqlstr .= ",      YSN_GK2 \n\r";
        $sqlstr .= ",      YSN_GK3 \n\r";
        $sqlstr .= ",      YSN_GK4 \n\r";
        $sqlstr .= ",      YSN_GK5 \n\r";
        $sqlstr .= ",      YSN_GK6 \n\r";
        $sqlstr .= ",      YSN_GK7 \n\r";
        $sqlstr .= ",      YSN_GK8 \n\r";
        $sqlstr .= ",      YSN_GK9 \n\r";
        $sqlstr .= ",      UPD_DATE \n\r";
        $sqlstr .= ",      CREATE_DATE \n\r";
        //'TODO 2006/12/08 UPD Start
        $sqlstr .= ",      UPD_SYA_CD \n\r";
        $sqlstr .= ",      UPD_PRG_ID \n\r";
        $sqlstr .= ",      UPD_CLT_NM \n\r";
        //'2006/12/08 UPD End

        $sqlstr .= ") \n\r";
        $sqlstr .= "SELECT YSN.YOSAN_YMD \n\r";
        $sqlstr .= ",      YSN.KI \n\r";
        $sqlstr .= ",      '   ' \n\r";
        $sqlstr .= ",      TTL_BUS.TOTAL_BUSYO_CD --WK_TTL.TOTAL_BUSYO_CD \n\r";
        $sqlstr .= ",      YSN.LINE_NO \n\r";
        $sqlstr .= ",      '*' \n\r";
        $sqlstr .= ",      SUM(NVL(YSN.YSN_GK10,0)) \n\r";
        $sqlstr .= ",      SUM(NVL(YSN.YSN_GK11,0)) \n\r";
        $sqlstr .= ",      SUM(NVL(YSN.YSN_GK12,0)) \n\r";
        $sqlstr .= ",      SUM(NVL(YSN.YSN_GK1,0)) \n\r";
        $sqlstr .= ",      SUM(NVL(YSN.YSN_GK2,0)) \n\r";
        $sqlstr .= ",      SUM(NVL(YSN.YSN_GK3,0)) \n\r";
        $sqlstr .= ",      SUM(NVL(YSN.YSN_GK4,0)) \n\r";
        $sqlstr .= ",      SUM(NVL(YSN.YSN_GK5,0)) \n\r";
        $sqlstr .= ",      SUM(NVL(YSN.YSN_GK6,0)) \n\r";
        $sqlstr .= ",      SUM(NVL(YSN.YSN_GK7,0)) \n\r";
        $sqlstr .= ",      SUM(NVL(YSN.YSN_GK8,0)) \n\r";
        $sqlstr .= ",      SUM(NVL(YSN.YSN_GK9,0)) \n\r";
        $sqlstr .= ",      SYSDATE \n\r";
        $sqlstr .= ",      SYSDATE \n\r";
        //'TODO 2006/12/08 UPD Start
        $sqlstr .= ",           '@UPDUSER' \n\r";
        $sqlstr .= ",           '@UPDAPP' \n\r";
        $sqlstr .= ",           '@UPDCLT' \n\r";
        //'2006/12/08 UPD End

        $sqlstr .= "FROM   HYOSAN_NEW YSN \n\r";
        $sqlstr .= "INNER JOIN HBUSYO BUS \n\r";
        $sqlstr .= "ON     BUS.BUSYO_CD = YSN.BUSYO_CD \n\r";
        $sqlstr .= "AND    NVL(BUS.SYUKEI_KB,'0') <> '1' \n\r";
        $sqlstr .= "INNER JOIN HTTLBUSYO TTL_BUS \n\r";
        $sqlstr .= "ON    TTL_BUS.BUSYO_CD = BUS.BUSYO_CD \n\r";
        //'$sqlstr.="INNER JOIN HTTLBUSYO WK_TTL \n\r";
        //'$sqlstr.="ON    WK_TTL.TOTAL_BUSYO_CD = TTL_BUS.TOTAL_BUSYO_CD \n\r";
        //'$sqlstr.="WHERE WK_TTL.BUSYO_CD = YSN.BUSYO_CD \n\r";
        $sqlstr .= "WHERE YSN.KI = '@KONKI' \n\r";
        $sqlstr .= "GROUP BY TTL_BUS.TOTAL_BUSYO_CD  --WK_TTL.TOTAL_BUSYO_CD \n\r";
        $sqlstr .= ",        YSN.LINE_NO \n\r";
        $sqlstr .= ",        YSN.YOSAN_YMD \n\r";
        $sqlstr .= ",        YSN.KI \n\r";

        $sqlstr = str_replace("@KONKI", $KI, $sqlstr);
        //'TODO 2006/12/08 UPD Start
        $sqlstr = str_replace("@UPDUSER", $UPDUSER, $sqlstr);
        $sqlstr = str_replace("@UPDAPP", $UPDAPP, $sqlstr);
        $sqlstr = str_replace("@UPDCLT", $UPDCLT, $sqlstr);
        //'2006/12/08 UPD End
        return $sqlstr;
    }

    public function fncInsertHTTLSHIHYO_DataTotal_sql($KI, $UPDAPP, $UPDCLT, $UPDUSER)
    {
        $sqlstr = "";

        $sqlstr .= "INSERT INTO HSHIHYO_NEW \n\r";
        $sqlstr .= "(      YOSAN_YMD \n\r";
        $sqlstr .= ",      KI \n\r";
        $sqlstr .= ",      KKR_BUSYO_CD \n\r";
        $sqlstr .= ",      BUSYO_CD \n\r";
        $sqlstr .= ",      LINE_NO \n\r";
        $sqlstr .= ",      UPD_FPG \n\r";
        $sqlstr .= ",      YSN_GK10 \n\r";
        $sqlstr .= ",      YSN_GK11 \n\r";
        $sqlstr .= ",      YSN_GK12 \n\r";
        $sqlstr .= ",      YSN_GK1 \n\r";
        $sqlstr .= ",      YSN_GK2 \n\r";
        $sqlstr .= ",      YSN_GK3 \n\r";
        $sqlstr .= ",      YSN_GK4 \n\r";
        $sqlstr .= ",      YSN_GK5 \n\r";
        $sqlstr .= ",      YSN_GK6 \n\r";
        $sqlstr .= ",      YSN_GK7 \n\r";
        $sqlstr .= ",      YSN_GK8 \n\r";
        $sqlstr .= ",      YSN_GK9 \n\r";
        $sqlstr .= ",      UPD_DATE \n\r";
        $sqlstr .= ",      CREATE_DATE \n\r";
        //'TODO 2006/12/08 UPD Start
        $sqlstr .= ",           UPD_SYA_CD \n\r";
        $sqlstr .= ",           UPD_PRG_ID \n\r";
        $sqlstr .= ",           UPD_CLT_NM \n\r";
        //'2006/12/08 UPD End

        $sqlstr .= ") \n\r";
        $sqlstr .= "SELECT YSN.YOSAN_YMD \n\r";
        $sqlstr .= ",      YSN.KI \n\r";
        $sqlstr .= ",      NULL \n\r";
        $sqlstr .= ",      YSN.BUSYO_CD \n\r";
        $sqlstr .= ",      YL.LINE_NO \n\r";
        $sqlstr .= ",      NULL \n\r";
        $sqlstr .= ",      (CASE WHEN NVL(SHY.SHY10,0) <> 0 AND YL.IDX_CAL_KB = '1' THEN ROUND(NVL(YSN.YSN10,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY.SHY10,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(SHY.SHY10,0) <> 0 AND YL.IDX_CAL_KB = '2' THEN ROUND(NVL(YSN.YSN10,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY.SHY10,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN.YSN10,0) <> 0 AND YL.IDX_CAL_KB = '3' THEN ROUND(NVL(SHY.SHY10,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN.YSN10,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN.YSN10,0) <> 0 AND YL.IDX_CAL_KB = '4' THEN ROUND(NVL(SHY.SHY10,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN.YSN10,0),0) \n\r";
        $sqlstr .= "        END) \n\r";
        $sqlstr .= ",      (CASE WHEN NVL(SHY.SHY11,0) <> 0 AND YL.IDX_CAL_KB = '1' THEN ROUND(NVL(YSN.YSN11,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY.SHY11,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(SHY.SHY11,0) <> 0 AND YL.IDX_CAL_KB = '2' THEN ROUND(NVL(YSN.YSN11,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY.SHY11,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN.YSN11,0) <> 0 AND YL.IDX_CAL_KB = '3' THEN ROUND(NVL(SHY.SHY11,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN.YSN11,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN.YSN11,0) <> 0 AND YL.IDX_CAL_KB = '4' THEN ROUND(NVL(SHY.SHY11,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN.YSN11,0),0) \n\r";
        $sqlstr .= "        END) \n\r";
        $sqlstr .= ",      (CASE WHEN NVL(SHY.SHY12,0) <> 0 AND YL.IDX_CAL_KB = '1' THEN ROUND(NVL(YSN.YSN12,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY.SHY12,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(SHY.SHY12,0) <> 0 AND YL.IDX_CAL_KB = '2' THEN ROUND(NVL(YSN.YSN12,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY.SHY12,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN.YSN12,0) <> 0 AND YL.IDX_CAL_KB = '3' THEN ROUND(NVL(SHY.SHY12,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN.YSN12,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN.YSN12,0) <> 0 AND YL.IDX_CAL_KB = '4' THEN ROUND(NVL(SHY.SHY12,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN.YSN12,0),0) \n\r";
        $sqlstr .= "        END) \n\r";
        $sqlstr .= ",      (CASE WHEN NVL(SHY.SHY1,0) <> 0 AND YL.IDX_CAL_KB = '1' THEN ROUND(NVL(YSN.YSN1,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY.SHY1,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(SHY.SHY1,0) <> 0 AND YL.IDX_CAL_KB = '2' THEN ROUND(NVL(YSN.YSN1,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY.SHY1,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN.YSN1,0) <> 0 AND YL.IDX_CAL_KB = '3' THEN ROUND(NVL(SHY.SHY1,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN.YSN1,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN.YSN1,0) <> 0 AND YL.IDX_CAL_KB = '4' THEN ROUND(NVL(SHY.SHY1,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN.YSN1,0),0) \n\r";
        $sqlstr .= "        END) \n\r";
        $sqlstr .= ",      (CASE WHEN NVL(SHY.SHY2,0) <> 0 AND YL.IDX_CAL_KB = '1' THEN ROUND(NVL(YSN.YSN2,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY.SHY2,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(SHY.SHY2,0) <> 0 AND YL.IDX_CAL_KB = '2' THEN ROUND(NVL(YSN.YSN2,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY.SHY2,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN.YSN2,0) <> 0 AND YL.IDX_CAL_KB = '3' THEN ROUND(NVL(SHY.SHY2,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN.YSN2,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN.YSN2,0) <> 0 AND YL.IDX_CAL_KB = '4' THEN ROUND(NVL(SHY.SHY2,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN.YSN2,0),0) \n\r";
        $sqlstr .= "        END) \n\r";
        $sqlstr .= ",      (CASE WHEN NVL(SHY.SHY3,0) <> 0 AND YL.IDX_CAL_KB = '1' THEN ROUND(NVL(YSN.YSN3,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY.SHY3,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(SHY.SHY3,0) <> 0 AND YL.IDX_CAL_KB = '2' THEN ROUND(NVL(YSN.YSN3,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY.SHY3,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN.YSN3,0) <> 0 AND YL.IDX_CAL_KB = '3' THEN ROUND(NVL(SHY.SHY3,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN.YSN3,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN.YSN3,0) <> 0 AND YL.IDX_CAL_KB = '4' THEN ROUND(NVL(SHY.SHY3,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN.YSN3,0),0) \n\r";
        $sqlstr .= "        END) \n\r";
        $sqlstr .= ",      (CASE WHEN NVL(SHY.SHY4,0) <> 0 AND YL.IDX_CAL_KB = '1' THEN ROUND(NVL(YSN.YSN4,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY.SHY4,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(SHY.SHY4,0) <> 0 AND YL.IDX_CAL_KB = '2' THEN ROUND(NVL(YSN.YSN4,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY.SHY4,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN.YSN4,0) <> 0 AND YL.IDX_CAL_KB = '3' THEN ROUND(NVL(SHY.SHY4,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN.YSN4,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN.YSN4,0) <> 0 AND YL.IDX_CAL_KB = '4' THEN ROUND(NVL(SHY.SHY4,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN.YSN4,0),0) \n\r";
        $sqlstr .= "        END) \n\r";
        $sqlstr .= ",      (CASE WHEN NVL(SHY.SHY5,0) <> 0 AND YL.IDX_CAL_KB = '1' THEN ROUND(NVL(YSN.YSN5,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY.SHY5,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(SHY.SHY5,0) <> 0 AND YL.IDX_CAL_KB = '2' THEN ROUND(NVL(YSN.YSN5,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY.SHY5,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN.YSN5,0) <> 0 AND YL.IDX_CAL_KB = '3' THEN ROUND(NVL(SHY.SHY5,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN.YSN5,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN.YSN5,0) <> 0 AND YL.IDX_CAL_KB = '4' THEN ROUND(NVL(SHY.SHY5,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN.YSN5,0),0) \n\r";
        $sqlstr .= "        END) \n\r";
        $sqlstr .= ",      (CASE WHEN NVL(SHY.SHY6,0) <> 0 AND YL.IDX_CAL_KB = '1' THEN ROUND(NVL(YSN.YSN6,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY.SHY6,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(SHY.SHY6,0) <> 0 AND YL.IDX_CAL_KB = '2' THEN ROUND(NVL(YSN.YSN6,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY.SHY6,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN.YSN6,0) <> 0 AND YL.IDX_CAL_KB = '3' THEN ROUND(NVL(SHY.SHY6,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN.YSN6,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN.YSN6,0) <> 0 AND YL.IDX_CAL_KB = '4' THEN ROUND(NVL(SHY.SHY6,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN.YSN6,0),0) \n\r";
        $sqlstr .= "        END) \n\r";
        $sqlstr .= ",      (CASE WHEN NVL(SHY.SHY7,0) <> 0 AND YL.IDX_CAL_KB = '1' THEN ROUND(NVL(YSN.YSN7,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY.SHY7,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(SHY.SHY7,0) <> 0 AND YL.IDX_CAL_KB = '2' THEN ROUND(NVL(YSN.YSN7,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY.SHY7,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN.YSN7,0) <> 0 AND YL.IDX_CAL_KB = '3' THEN ROUND(NVL(SHY.SHY7,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN.YSN7,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN.YSN7,0) <> 0 AND YL.IDX_CAL_KB = '4' THEN ROUND(NVL(SHY.SHY7,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN.YSN7,0),0) \n\r";
        $sqlstr .= "        END) \n\r";
        $sqlstr .= ",      (CASE WHEN NVL(SHY.SHY8,0) <> 0 AND YL.IDX_CAL_KB = '1' THEN ROUND(NVL(YSN.YSN8,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY.SHY8,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(SHY.SHY8,0) <> 0 AND YL.IDX_CAL_KB = '2' THEN ROUND(NVL(YSN.YSN8,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY.SHY8,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN.YSN8,0) <> 0 AND YL.IDX_CAL_KB = '3' THEN ROUND(NVL(SHY.SHY8,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN.YSN8,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN.YSN8,0) <> 0 AND YL.IDX_CAL_KB = '4' THEN ROUND(NVL(SHY.SHY8,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN.YSN8,0),0) \n\r";
        $sqlstr .= "        END) \n\r";
        $sqlstr .= ",      (CASE WHEN NVL(SHY.SHY9,0) <> 0 AND YL.IDX_CAL_KB = '1' THEN ROUND(NVL(YSN.YSN9,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY.SHY9,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(SHY.SHY9,0) <> 0 AND YL.IDX_CAL_KB = '2' THEN ROUND(NVL(YSN.YSN9,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(SHY.SHY9,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN.YSN9,0) <> 0 AND YL.IDX_CAL_KB = '3' THEN ROUND(NVL(SHY.SHY9,0) * POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN.YSN9,0),0) \n\r";
        $sqlstr .= "             WHEN NVL(YSN.YSN9,0) <> 0 AND YL.IDX_CAL_KB = '4' THEN ROUND(NVL(SHY.SHY9,0) / POWER(10,NVL(YL.IDX_RND_POS,0)) / NVL(YSN.YSN9,0),0) \n\r";
        $sqlstr .= "        END) \n\r";
        $sqlstr .= ",       SYSDATE \n\r";
        $sqlstr .= ",       SYSDATE \n\r";
        //'TODO 2006/12/08 UPD Start
        $sqlstr .= ",       '@UPDUSER' \n\r";
        $sqlstr .= ",       '@UPDAPP' \n\r";
        $sqlstr .= ",       '@UPDCLT' \n\r";
        //'2006/12/08 UPD End

        $sqlstr .= "FROM \n\r";
        $sqlstr .= "		( \n\r";
        $sqlstr .= "		SELECT  YL.LINE_NO \n\r";
        $sqlstr .= "        ,       YSN_TBL.BUSYO_CD \n\r";
        $sqlstr .= "		,       MAX(YL.IDX_CAL_KB) SIHYO_KB \n\r";
        $sqlstr .= "		,       MAX(YL.IDX_RND_POS) SIHYO_POS \n\r";
        $sqlstr .= "        ,       MAX(YSN_TBL.YOSAN_YMD) YOSAN_YMD \n\r";
        $sqlstr .= "        ,       MAX(YSN_TBL.KI) KI \n\r";
        $sqlstr .= "		,       MAX(YSN_TBL.YSN_GK10) YSN10 \n\r";
        $sqlstr .= "		,       MAX(YSN_TBL.YSN_GK11) YSN11 \n\r";
        $sqlstr .= "		,       MAX(YSN_TBL.YSN_GK12) YSN12 \n\r";
        $sqlstr .= "		,       MAX(YSN_TBL.YSN_GK1) YSN1 \n\r";
        $sqlstr .= "		,       MAX(YSN_TBL.YSN_GK2) YSN2 \n\r";
        $sqlstr .= "		,       MAX(YSN_TBL.YSN_GK3) YSN3 \n\r";
        $sqlstr .= "		,       MAX(YSN_TBL.YSN_GK4) YSN4 \n\r";
        $sqlstr .= "		,       MAX(YSN_TBL.YSN_GK5) YSN5 \n\r";
        $sqlstr .= "		,       MAX(YSN_TBL.YSN_GK6) YSN6 \n\r";
        $sqlstr .= "		,       MAX(YSN_TBL.YSN_GK7) YSN7 \n\r";
        $sqlstr .= "		,       MAX(YSN_TBL.YSN_GK8) YSN8 \n\r";
        $sqlstr .= "		,       MAX(YSN_TBL.YSN_GK9) YSN9 \n\r";
        $sqlstr .= "	 \n\r";
        //		$sqlstr .= "		FROM    (SELECT DISTINCT LINE_NO, IDX_LINE_NO, IDX_CAL_KB, IDX_RND_POS FROM HYOSANLINEMST_NEW \n\r";
        $sqlstr .= "		FROM    (SELECT DISTINCT LINE_NO, IDX_LINE_NO, IDX_CAL_KB, IDX_RND_POS FROM HYOSANLINEMST_KRSS \n\r";
        $sqlstr .= "		         WHERE  BUSYO_KB IN (SELECT BUS.BUSYO_KB FROM HBUSYO BUS, HTTLBUSYO TTL WHERE BUS.BUSYO_CD = TTL.BUSYO_CD AND NVL(BUS.SYUKEI_KB,'0') <> '1')  AND IDX_CAL_KB IS NOT NULL) YL \n\r";
        $sqlstr .= "		,       (SELECT YSN.LINE_NO \n\r";
        $sqlstr .= "		        ,      YSN.BUSYO_CD \n\r";
        $sqlstr .= "		        ,      YSN.YOSAN_YMD \n\r";
        $sqlstr .= "		        ,      YSN.KI \n\r";
        $sqlstr .= "		        ,      SUM(YSN.YSN_GK10) YSN_GK10 \n\r";
        $sqlstr .= "				,      (SUM(NVL(YSN.YSN_GK10,0) + NVL(YSN.YSN_GK11,0)) / 2) YSN_GK11 \n\r";
        $sqlstr .= "				,      (SUM(NVL(YSN.YSN_GK10,0) + NVL(YSN.YSN_GK11,0) + NVL(YSN.YSN_GK12,0)) / 3) YSN_GK12 \n\r";
        $sqlstr .= "				,      (SUM(NVL(YSN.YSN_GK10,0) + NVL(YSN.YSN_GK11,0) + NVL(YSN.YSN_GK12,0) + NVL(YSN.YSN_GK1,0)) / 4) YSN_GK1 \n\r";
        $sqlstr .= "		        ,      (SUM(NVL(YSN.YSN_GK10,0) + NVL(YSN.YSN_GK11,0) + NVL(YSN.YSN_GK12,0) + NVL(YSN.YSN_GK1,0) + NVL(YSN.YSN_GK2,0)) / 5) YSN_GK2 \n\r";
        $sqlstr .= "		        ,      (SUM(NVL(YSN.YSN_GK10,0) + NVL(YSN.YSN_GK11,0) + NVL(YSN.YSN_GK12,0) + NVL(YSN.YSN_GK1,0) + NVL(YSN.YSN_GK2,0) + NVL(YSN.YSN_GK3,0)) / 6) YSN_GK3 \n\r";
        $sqlstr .= "		        ,      (SUM(NVL(YSN.YSN_GK10,0) + NVL(YSN.YSN_GK11,0) + NVL(YSN.YSN_GK12,0) + NVL(YSN.YSN_GK1,0) + NVL(YSN.YSN_GK2,0) + NVL(YSN.YSN_GK3,0) + NVL(YSN.YSN_GK4,0)) / 7) YSN_GK4 \n\r";
        $sqlstr .= "		        ,      (SUM(NVL(YSN.YSN_GK10,0) + NVL(YSN.YSN_GK11,0) + NVL(YSN.YSN_GK12,0) + NVL(YSN.YSN_GK1,0) + NVL(YSN.YSN_GK2,0) + NVL(YSN.YSN_GK3,0) + NVL(YSN.YSN_GK4,0) + NVL(YSN.YSN_GK5,0)) / 8) YSN_GK5 \n\r";
        $sqlstr .= "				,      (SUM(NVL(YSN.YSN_GK10,0) + NVL(YSN.YSN_GK11,0) + NVL(YSN.YSN_GK12,0) + NVL(YSN.YSN_GK1,0) + NVL(YSN.YSN_GK2,0) + NVL(YSN.YSN_GK3,0) + NVL(YSN.YSN_GK4,0) + NVL(YSN.YSN_GK5,0) + NVL(YSN.YSN_GK6,0)) / 9) YSN_GK6 \n\r";
        $sqlstr .= "				,      (SUM(NVL(YSN.YSN_GK10,0) + NVL(YSN.YSN_GK11,0) + NVL(YSN.YSN_GK12,0) + NVL(YSN.YSN_GK1,0) + NVL(YSN.YSN_GK2,0) + NVL(YSN.YSN_GK3,0) + NVL(YSN.YSN_GK4,0) + NVL(YSN.YSN_GK5,0) + NVL(YSN.YSN_GK6,0) + NVL(YSN.YSN_GK7,0)) / 10) YSN_GK7 \n\r";
        $sqlstr .= "				,      (SUM(NVL(YSN.YSN_GK10,0) + NVL(YSN.YSN_GK11,0) + NVL(YSN.YSN_GK12,0) + NVL(YSN.YSN_GK1,0) + NVL(YSN.YSN_GK2,0) + NVL(YSN.YSN_GK3,0) + NVL(YSN.YSN_GK4,0) + NVL(YSN.YSN_GK5,0) + NVL(YSN.YSN_GK6,0) + NVL(YSN.YSN_GK7,0) + NVL(YSN.YSN_GK8,0)) / 11) YSN_GK8 \n\r";
        $sqlstr .= "				,      (SUM(NVL(YSN.YSN_GK10,0) + NVL(YSN.YSN_GK11,0) + NVL(YSN.YSN_GK12,0) + NVL(YSN.YSN_GK1,0) + NVL(YSN.YSN_GK2,0) + NVL(YSN.YSN_GK3,0) + NVL(YSN.YSN_GK4,0) + NVL(YSN.YSN_GK5,0) + NVL(YSN.YSN_GK6,0) + NVL(YSN.YSN_GK7,0) + NVL(YSN.YSN_GK8,0) + NVL(YSN.YSN_GK9,0)) / 12) YSN_GK9 \n\r";
        $sqlstr .= "                ,      SUM(NVL(YSN.YSN_GK10,0) + NVL(YSN.YSN_GK11,0) + NVL(YSN.YSN_GK12,0) + NVL(YSN.YSN_GK1,0) + NVL(YSN.YSN_GK2,0) + NVL(YSN.YSN_GK3,0) + NVL(YSN.YSN_GK4,0) + NVL(YSN.YSN_GK5,0) + NVL(YSN.YSN_GK6,0) + NVL(YSN.YSN_GK7,0) + NVL(YSN.YSN_GK8,0) + NVL(YSN.YSN_GK9,0)) HANDAN \n\r";
        $sqlstr .= "				FROM   HYOSAN_NEW YSN \n\r";
        $sqlstr .= "				,      HBUSYO BUS \n\r";
        $sqlstr .= "		        ,      HTTLBUSYO TTL_BUS \n\r";
        $sqlstr .= "		        WHERE  TTL_BUS.TOTAL_BUSYO_CD = YSN.BUSYO_CD \n\r";
        $sqlstr .= "		        AND    TTL_BUS.BUSYO_CD = BUS.BUSYO_CD \n\r";
        $sqlstr .= "		        AND    YSN.KI = @KONKI \n\r";
        $sqlstr .= "		        GROUP BY  YSN.LINE_NO \n\r";
        $sqlstr .= "		        ,         YSN.BUSYO_CD \n\r";
        $sqlstr .= "		        ,         YSN.YOSAN_YMD \n\r";
        $sqlstr .= "		        ,         YSN.KI \n\r";
        $sqlstr .= "		       ) YSN_TBL \n\r";
        $sqlstr .= "        WHERE  YL.LINE_NO = YSN_TBL.LINE_NO \n\r";
        $sqlstr .= "        AND    YSN_TBL.HANDAN <> 0 \n\r";
        $sqlstr .= "        GROUP BY YL.LINE_NO, YSN_TBL.BUSYO_CD \n\r";
        $sqlstr .= "		) YSN \n\r";
        $sqlstr .= ",       (SELECT YL.LINE_NO \n\r";
        $sqlstr .= "        ,       SHY_TBL.BUSYO_CD \n\r";
        $sqlstr .= "		,       MAX(YL.IDX_CAL_KB) SIHYO_KB \n\r";
        $sqlstr .= "		,       MAX(YL.IDX_RND_POS) SIHYO_POS \n\r";
        $sqlstr .= "        ,       MAX(SHY_TBL.YOSAN_YMD) YOSAN_YMD \n\r";
        $sqlstr .= "        ,       MAX(SHY_TBL.KI) KI \n\r";
        $sqlstr .= "		,       SUM(SHY_TBL.YSN_GK10) SHY10 \n\r";
        $sqlstr .= "		,       SUM(SHY_TBL.YSN_GK11) SHY11 \n\r";
        $sqlstr .= "		,       SUM(SHY_TBL.YSN_GK12) SHY12 \n\r";
        $sqlstr .= "		,       SUM(SHY_TBL.YSN_GK1) SHY1 \n\r";
        $sqlstr .= "		,       SUM(SHY_TBL.YSN_GK2) SHY2 \n\r";
        $sqlstr .= "		,       SUM(SHY_TBL.YSN_GK3) SHY3 \n\r";
        $sqlstr .= "		,       SUM(SHY_TBL.YSN_GK4) SHY4 \n\r";
        $sqlstr .= "		,       SUM(SHY_TBL.YSN_GK5) SHY5 \n\r";
        $sqlstr .= "		,       SUM(SHY_TBL.YSN_GK6) SHY6 \n\r";
        $sqlstr .= "		,       SUM(SHY_TBL.YSN_GK7) SHY7 \n\r";
        $sqlstr .= "		,       SUM(SHY_TBL.YSN_GK8) SHY8 \n\r";
        $sqlstr .= "		,       SUM(SHY_TBL.YSN_GK9) SHY9 \n\r";
        //		$sqlstr .= "		FROM    (SELECT DISTINCT LINE_NO, IDX_LINE_NO, IDX_CAL_KB, IDX_RND_POS FROM HYOSANLINEMST_NEW \n\r";
        $sqlstr .= "		FROM    (SELECT DISTINCT LINE_NO, IDX_LINE_NO, IDX_CAL_KB, IDX_RND_POS FROM HYOSANLINEMST_KRSS \n\r";
        $sqlstr .= "		         WHERE  BUSYO_KB IN (SELECT BUS.BUSYO_KB FROM HBUSYO BUS, HTTLBUSYO TTL WHERE BUS.BUSYO_CD = TTL.BUSYO_CD AND NVL(BUS.SYUKEI_KB,'0') <> '1')  AND IDX_CAL_KB IS NOT NULL) YL \n\r";
        $sqlstr .= "		,       (SELECT YSN.LINE_NO \n\r";
        $sqlstr .= "		        ,      YSN.BUSYO_CD \n\r";
        $sqlstr .= "		        ,      YSN.YOSAN_YMD \n\r";
        $sqlstr .= "		        ,      YSN.KI \n\r";
        $sqlstr .= "		        ,      SUM(YSN.YSN_GK10) YSN_GK10 \n\r";
        $sqlstr .= "				,      (SUM(NVL(YSN.YSN_GK10,0) + NVL(YSN.YSN_GK11,0)) / 2) YSN_GK11 \n\r";
        $sqlstr .= "				,      (SUM(NVL(YSN.YSN_GK10,0) + NVL(YSN.YSN_GK11,0) + NVL(YSN.YSN_GK12,0)) / 3)  YSN_GK12 \n\r";
        $sqlstr .= "				,      (SUM(NVL(YSN.YSN_GK10,0) + NVL(YSN.YSN_GK11,0) + NVL(YSN.YSN_GK12,0) + NVL(YSN.YSN_GK1,0)) / 4) YSN_GK1 \n\r";
        $sqlstr .= "		        ,      (SUM(NVL(YSN.YSN_GK10,0) + NVL(YSN.YSN_GK11,0) + NVL(YSN.YSN_GK12,0) + NVL(YSN.YSN_GK1,0) + NVL(YSN.YSN_GK2,0)) / 5) YSN_GK2 \n\r";
        $sqlstr .= "		        ,      (SUM(NVL(YSN.YSN_GK10,0) + NVL(YSN.YSN_GK11,0) + NVL(YSN.YSN_GK12,0) + NVL(YSN.YSN_GK1,0) + NVL(YSN.YSN_GK2,0) + NVL(YSN.YSN_GK3,0)) / 6) YSN_GK3 \n\r";
        $sqlstr .= "		        ,      (SUM(NVL(YSN.YSN_GK10,0) + NVL(YSN.YSN_GK11,0) + NVL(YSN.YSN_GK12,0) + NVL(YSN.YSN_GK1,0) + NVL(YSN.YSN_GK2,0) + NVL(YSN.YSN_GK3,0) + NVL(YSN.YSN_GK4,0)) / 7) YSN_GK4 \n\r";
        $sqlstr .= "		        ,      (SUM(NVL(YSN.YSN_GK10,0) + NVL(YSN.YSN_GK11,0) + NVL(YSN.YSN_GK12,0) + NVL(YSN.YSN_GK1,0) + NVL(YSN.YSN_GK2,0) + NVL(YSN.YSN_GK3,0) + NVL(YSN.YSN_GK4,0) + NVL(YSN.YSN_GK5,0)) / 8) YSN_GK5 \n\r";
        $sqlstr .= "				,      (SUM(NVL(YSN.YSN_GK10,0) + NVL(YSN.YSN_GK11,0) + NVL(YSN.YSN_GK12,0) + NVL(YSN.YSN_GK1,0) + NVL(YSN.YSN_GK2,0) + NVL(YSN.YSN_GK3,0) + NVL(YSN.YSN_GK4,0) + NVL(YSN.YSN_GK5,0) + NVL(YSN.YSN_GK6,0)) / 9) YSN_GK6 \n\r";
        $sqlstr .= "				,      (SUM(NVL(YSN.YSN_GK10,0) + NVL(YSN.YSN_GK11,0) + NVL(YSN.YSN_GK12,0) + NVL(YSN.YSN_GK1,0) + NVL(YSN.YSN_GK2,0) + NVL(YSN.YSN_GK3,0) + NVL(YSN.YSN_GK4,0) + NVL(YSN.YSN_GK5,0) + NVL(YSN.YSN_GK6,0) + NVL(YSN.YSN_GK7,0)) / 10) YSN_GK7 \n\r";
        $sqlstr .= "				,      (SUM(NVL(YSN.YSN_GK10,0) + NVL(YSN.YSN_GK11,0) + NVL(YSN.YSN_GK12,0) + NVL(YSN.YSN_GK1,0) + NVL(YSN.YSN_GK2,0) + NVL(YSN.YSN_GK3,0) + NVL(YSN.YSN_GK4,0) + NVL(YSN.YSN_GK5,0) + NVL(YSN.YSN_GK6,0) + NVL(YSN.YSN_GK7,0) + NVL(YSN.YSN_GK8,0)) / 11) YSN_GK8 \n\r";
        $sqlstr .= "				,      (SUM(NVL(YSN.YSN_GK10,0) + NVL(YSN.YSN_GK11,0) + NVL(YSN.YSN_GK12,0) + NVL(YSN.YSN_GK1,0) + NVL(YSN.YSN_GK2,0) + NVL(YSN.YSN_GK3,0) + NVL(YSN.YSN_GK4,0) + NVL(YSN.YSN_GK5,0) + NVL(YSN.YSN_GK6,0) + NVL(YSN.YSN_GK7,0) + NVL(YSN.YSN_GK8,0) + NVL(YSN.YSN_GK9,0)) / 12) YSN_GK9 \n\r";
        $sqlstr .= "				FROM   HYOSAN_NEW YSN \n\r";
        $sqlstr .= "				,      HBUSYO BUS \n\r";
        $sqlstr .= "		        ,      HTTLBUSYO TTL_BUS \n\r";
        $sqlstr .= "		        WHERE  TTL_BUS.TOTAL_BUSYO_CD = YSN.BUSYO_CD \n\r";
        $sqlstr .= "		        AND    TTL_BUS.BUSYO_CD = BUS.BUSYO_CD \n\r";
        $sqlstr .= "		        AND    YSN.KI = @KONKI \n\r";
        $sqlstr .= "		        GROUP BY  YSN.LINE_NO \n\r";
        $sqlstr .= "		        ,         YSN.BUSYO_CD \n\r";
        $sqlstr .= "		        ,         YSN.YOSAN_YMD \n\r";
        $sqlstr .= "		        ,         YSN.KI \n\r";
        $sqlstr .= "		       ) SHY_TBL \n\r";
        $sqlstr .= "        WHERE  SHY_TBL.LINE_NO = YL.IDX_LINE_NO \n\r";
        $sqlstr .= "        GROUP BY YL.LINE_NO, SHY_TBL.BUSYO_CD \n\r";
        $sqlstr .= "        ) SHY \n\r";
        //		$sqlstr .= ",      (SELECT DISTINCT LINE_NO, IDX_CAL_KB, IDX_RND_POS FROM HYOSANLINEMST_NEW \n\r";
        $sqlstr .= ",      (SELECT DISTINCT LINE_NO, IDX_CAL_KB, IDX_RND_POS FROM HYOSANLINEMST_KRSS \n\r";
        $sqlstr .= "       WHERE  BUSYO_KB IN (SELECT BUS.BUSYO_KB FROM HBUSYO BUS, HTTLBUSYO TTL WHERE BUS.BUSYO_CD = TTL.BUSYO_CD AND NVL(BUS.SYUKEI_KB,'0') <> '1')  AND IDX_CAL_KB IS NOT NULL) YL \n\r";
        $sqlstr .= " \n\r";
        $sqlstr .= "WHERE  YL.LINE_NO = YSN.LINE_NO \n\r";
        $sqlstr .= "AND    YSN.LINE_NO = SHY.LINE_NO \n\r";
        $sqlstr .= "AND    YSN.BUSYO_CD = SHY.BUSYO_CD \n\r";

        $sqlstr = str_replace("@KONKI", $KI, $sqlstr);
        $sqlstr = str_replace("@UPDUSER", $UPDUSER, $sqlstr);
        $sqlstr = str_replace("@UPDAPP", $UPDAPP, $sqlstr);
        $sqlstr = str_replace("@UPDCLT", $UPDCLT, $sqlstr);

        return $sqlstr;
    }

    //-集計部署単位の集計 e-

    //-EXCEL出力 s-
    public function subCtlSql_sql()
    {
        $sqlstr = "";
        $sqlstr .= " SELECT ID  \n\r";
        $sqlstr .= "     ,      SUBSTR(SYR_YMD,1,4) || '/' || SUBSTR(SYR_YMD,5,2) || '/' || '01' TOU_YM  \n\r";
        $sqlstr .= " FROM HKEIRICTL   \n\r";
        $sqlstr .= " WHERE  ID = '01' \n\r";
        return $sqlstr;
    }

    public function fncYOJITSUSQL_sql($strKisyu, $KI, $Y, $M)
    {
        $sqlstr = "";
        $sqlstr .= "SELECT * FROM \n";
        $sqlstr .= "(	SELECT (CASE WHEN BUS.JISSEKITTL_KB = '0' THEN BS.BUSYO_CD ELSE YSN.BUSYO_CD END) TENPO_CD \n";
        $sqlstr .= "    ,      YSN.BUSYO_CD YSN_BUSYO_CD \n";
        $sqlstr .= "    ,      (CASE WHEN BUS.JISSEKITTL_KB = '0' THEN BS.BUSYO_NM ELSE BUS.BUSYO_NM END) BUSYO_NM \n";
        $sqlstr .= "    ,      YSN.LINE_NO \n";
        $sqlstr .= "    ,      '(予算)' || MEI.MEISYOU KOUMOKUMEI \n";
        $sqlstr .= "    ,      MEI.SUCHI1 \n";
        $sqlstr .= "    ,      '1' KBN \n";
        $sqlstr .= "    ,      ROUND(SUM(NVL(YSN.YSN_GK10,0) + NVL(YSN.YSN_GK11,0) + NVL(YSN.YSN_GK12,0) + NVL(YSN.YSN_GK1,0) + NVL(YSN.YSN_GK2,0) + NVL(YSN.YSN_GK3,0)  \n";
        $sqlstr .= "    + NVL(YSN.YSN_GK4,0) + NVL(YSN.YSN_GK5,0) + NVL(YSN.YSN_GK6,0) + NVL(YSN.YSN_GK7,0) + NVL(YSN.YSN_GK8,0) + NVL(YSN.YSN_GK9,0)) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) GOUKEI  \n";
        $sqlstr .= "    ,      ROUND(SUM(YSN.YSN_GK10) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) GK10  \n";
        $sqlstr .= "    ,      ROUND(SUM(YSN.YSN_GK11) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) GK11  \n";
        $sqlstr .= "    ,      ROUND(SUM(YSN.YSN_GK12) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) GK12  \n";
        $sqlstr .= "    ,      ROUND(SUM(YSN.YSN_GK1) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) GK1  \n";
        $sqlstr .= "    ,      ROUND(SUM(YSN.YSN_GK2) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) GK2  \n";
        $sqlstr .= "    ,      ROUND(SUM(YSN.YSN_GK3) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) GK3  \n";
        $sqlstr .= "    ,      ROUND(SUM(YSN.YSN_GK4) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) GK4  \n";
        $sqlstr .= "    ,      ROUND(SUM(YSN.YSN_GK5) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) GK5  \n";
        $sqlstr .= "    ,      ROUND(SUM(YSN.YSN_GK6) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) GK6  \n";
        $sqlstr .= "    ,      ROUND(SUM(YSN.YSN_GK7) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) GK7  \n";
        $sqlstr .= "    ,      ROUND(SUM(YSN.YSN_GK8) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) GK8  \n";
        $sqlstr .= "    ,      ROUND(SUM(YSN.YSN_GK9) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) GK9 \n";
        $sqlstr .= "    ,      ROUND(SUM(NVL(M_YSN.YSN_GK10,0) + NVL(M_YSN.YSN_GK11,0) + NVL(M_YSN.YSN_GK12,0) + NVL(M_YSN.YSN_GK1,0) + NVL(M_YSN.YSN_GK2,0) + NVL(M_YSN.YSN_GK3,0)  \n";
        $sqlstr .= "    + NVL(M_YSN.YSN_GK4,0) + NVL(M_YSN.YSN_GK5,0) + NVL(M_YSN.YSN_GK6,0) + NVL(M_YSN.YSN_GK7,0) + NVL(M_YSN.YSN_GK8,0) + NVL(M_YSN.YSN_GK9,0)) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) ZEN_GOUKEI  \n";
        $sqlstr .= "	FROM   HYOSAN_NEW YSN  \n";
        $sqlstr .= "	INNER JOIN HBUSYO BUS  \n";
        $sqlstr .= "	ON     BUS.BUSYO_CD = YSN.BUSYO_CD  \n";
        $sqlstr .= "	AND    BUS.BUSYO_KB = 'S' \n";
        $sqlstr .= "	AND    BUS.JISSEKITTL_KB IS NOT NULL \n";
        $sqlstr .= "	INNER JOIN HBUSYO BS \n";
        $sqlstr .= "	ON     BS.BUSYO_CD = BUS.TENPO_CD \n";
        $sqlstr .= "	INNER JOIN HMEISYOUMST MEI \n";
        $sqlstr .= "	ON     MEI.MEISYOU_CD = YSN.LINE_NO \n";
        //$sqlstr .= "	AND    MEI.MEISYOU_ID = '50' \n";
        $sqlstr .= "	AND    MEI.MEISYOU_ID = '52' \n";
        //$sqlstr .= "	INNER JOIN HLINEMST_NEW LINE  \n";
        $sqlstr .= "	INNER JOIN HLINEMST_KEIEISEIKA LINE  \n";
        $sqlstr .= "	ON     YSN.LINE_NO = LINE.LINE_NO \n";
        $sqlstr .= "    LEFT JOIN HYOSAN_NEW M_YSN \n";
        $sqlstr .= "    ON     M_YSN.BUSYO_CD = YSN.BUSYO_CD \n";
        $sqlstr .= "    AND    M_YSN.LINE_NO = YSN.LINE_NO \n";
        $sqlstr .= "    AND    M_YSN.YOSAN_YMD = '@ZENKISYU' \n";
        $sqlstr .= "	WHERE  YSN.YOSAN_YMD = '@KONKISYU' \n";
        $sqlstr .= "	GROUP BY YSN.BUSYO_CD  \n";
        $sqlstr .= "	,        BS.BUSYO_CD \n";
        $sqlstr .= "	,        BS.BUSYO_NM \n";
        $sqlstr .= "	,        BUS.BUSYO_NM \n";
        $sqlstr .= "	,        BUS.JISSEKITTL_KB \n";
        $sqlstr .= "	,        YSN.LINE_NO \n";
        $sqlstr .= "	,        MEI.MEISYOU \n";
        $sqlstr .= "	,        LINE.RND_POS \n";
        $sqlstr .= "    ,      MEI.SUCHI1 \n";
        $sqlstr .= "	UNION ALL \n";
        $sqlstr .= "	SELECT     (CASE WHEN V.JISSEKITTL_KB = '0' THEN BS.BUSYO_CD ELSE V.SIN_BUSYO_CD END) TENPO_CD \n";
        $sqlstr .= "		,      V.BUSYO_CD \n";
        $sqlstr .= "	    ,      (CASE WHEN V.JISSEKITTL_KB = '0' THEN BS.BUSYO_NM ELSE V.BUSYO_NM END) BUSYO_NM \n";
        $sqlstr .= "		,      YSN.LINE_NO \n";
        $sqlstr .= "	    ,      '(予算)' || MEI.MEISYOU \n";
        $sqlstr .= "        ,      MEI.SUCHI1 \n";
        $sqlstr .= "        ,      '1' KBN \n";
        $sqlstr .= "		,      ROUND(SUM(NVL(YSN.YSN_GK10,0) + NVL(YSN.YSN_GK11,0) + NVL(YSN.YSN_GK12,0) + NVL(YSN.YSN_GK1,0) + NVL(YSN.YSN_GK2,0) + NVL(YSN.YSN_GK3,0)  \n";
        $sqlstr .= "		+ NVL(YSN.YSN_GK4,0) + NVL(YSN.YSN_GK5,0) + NVL(YSN.YSN_GK6,0) + NVL(YSN.YSN_GK7,0) + NVL(YSN.YSN_GK8,0) + NVL(YSN.YSN_GK9,0)) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) YSNGK  \n";
        $sqlstr .= "		,      ROUND(SUM(YSN.YSN_GK10) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) YSN10  \n";
        $sqlstr .= "		,      ROUND(SUM(YSN.YSN_GK11) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) YSN11  \n";
        $sqlstr .= "		,      ROUND(SUM(YSN.YSN_GK12) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) YSN12  \n";
        $sqlstr .= "		,      ROUND(SUM(YSN.YSN_GK1) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) YSN1  \n";
        $sqlstr .= "		,      ROUND(SUM(YSN.YSN_GK2) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) YSN2  \n";
        $sqlstr .= "		,      ROUND(SUM(YSN.YSN_GK3) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) YSN3  \n";
        $sqlstr .= "		,      ROUND(SUM(YSN.YSN_GK4) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) YSN4  \n";
        $sqlstr .= "		,      ROUND(SUM(YSN.YSN_GK5) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) YSN5  \n";
        $sqlstr .= "		,      ROUND(SUM(YSN.YSN_GK6) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) YSN6  \n";
        $sqlstr .= "		,      ROUND(SUM(YSN.YSN_GK7) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) YSN7  \n";
        $sqlstr .= "		,      ROUND(SUM(YSN.YSN_GK8) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) YSN8  \n";
        $sqlstr .= "		,      ROUND(SUM(YSN.YSN_GK9) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) YSN9 \n";
        $sqlstr .= "	    ,      ROUND(SUM(NVL(M_YSN.YSN_GK10,0) + NVL(M_YSN.YSN_GK11,0) + NVL(M_YSN.YSN_GK12,0) + NVL(M_YSN.YSN_GK1,0) + NVL(M_YSN.YSN_GK2,0) + NVL(M_YSN.YSN_GK3,0)  \n";
        $sqlstr .= "	    + NVL(M_YSN.YSN_GK4,0) + NVL(M_YSN.YSN_GK5,0) + NVL(M_YSN.YSN_GK6,0) + NVL(M_YSN.YSN_GK7,0) + NVL(M_YSN.YSN_GK8,0) + NVL(M_YSN.YSN_GK9,0)) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) ZEN_GOUKEI  \n";
        $sqlstr .= "	FROM   HYOSAN_NEW YSN  \n";
        $sqlstr .= "	INNER JOIN (SELECT A.BUSYO_CD, B.BUSYO_NM, B.BUSYO_CD SIN_BUSYO_CD, B.TENPO_CD, A.JISSEKITTL_KB  \n";
        $sqlstr .= "	            FROM 　HBUSYO A, HBUSYO B \n";
        $sqlstr .= "	            WHERE　A.BUSYO_KB = 'F' AND A.JISSEKITTL_KB IS NOT NULL \n";
        $sqlstr .= "	            AND B.BUSYO_KB = 'S' AND B.JISSEKITTL_KB IS NOT NULL \n";
        $sqlstr .= "	            AND A.TENPO_CD = B.TENPO_CD AND A.JISSEKITTL_KB = B.JISSEKITTL_KB) V \n";
        $sqlstr .= "	ON     V.BUSYO_CD = YSN.BUSYO_CD \n";
        $sqlstr .= "	INNER JOIN HBUSYO BS \n";
        $sqlstr .= "	ON     BS.BUSYO_CD = V.TENPO_CD \n";
        $sqlstr .= "	INNER JOIN HMEISYOUMST MEI \n";
        $sqlstr .= "	ON     MEI.MEISYOU_CD = YSN.LINE_NO \n";
        //$sqlstr .= "	AND    MEI.MEISYOU_ID = '51' \n";
        $sqlstr .= "	AND    MEI.MEISYOU_ID = '53' \n";
        //$sqlstr .= "	INNER JOIN HLINEMST_NEW LINE  \n";
        $sqlstr .= "	INNER JOIN HLINEMST_KEIEISEIKA LINE  \n";
        $sqlstr .= "	ON     YSN.LINE_NO = LINE.LINE_NO \n";
        $sqlstr .= "    LEFT JOIN HYOSAN_NEW M_YSN \n";
        $sqlstr .= "    ON     M_YSN.BUSYO_CD = YSN.BUSYO_CD \n";
        $sqlstr .= "    AND    M_YSN.LINE_NO = YSN.LINE_NO \n";
        $sqlstr .= "    AND    M_YSN.YOSAN_YMD = '@ZENKISYU' \n";
        $sqlstr .= "	WHERE  YSN.YOSAN_YMD = '@KONKISYU' \n";
        $sqlstr .= "	GROUP BY BS.BUSYO_CD \n";
        $sqlstr .= "	,        V.SIN_BUSYO_CD \n";
        $sqlstr .= "	,        V.BUSYO_CD \n";
        $sqlstr .= "	,        BS.BUSYO_NM \n";
        $sqlstr .= "	,        V.BUSYO_NM \n";
        $sqlstr .= "	,        V.JISSEKITTL_KB \n";
        $sqlstr .= "	,        YSN.LINE_NO \n";
        $sqlstr .= "	,        MEI.MEISYOU \n";
        $sqlstr .= "	,        LINE.RND_POS \n";
        $sqlstr .= "    ,        MEI.SUCHI1 \n";
        $sqlstr .= "	UNION ALL \n";
        $sqlstr .= "	SELECT (CASE WHEN BUS.JISSEKITTL_KB = '0' THEN BS.BUSYO_CD ELSE KANR.BUSYO_CD END) TENPO_CD \n";
        $sqlstr .= "	,      KANR.BUSYO_CD JSK_BUSYO_CD \n";
        $sqlstr .= "	,      (CASE WHEN BUS.JISSEKITTL_KB = '0' THEN BS.BUSYO_NM ELSE BUS.BUSYO_NM END) BUSYO_NM \n";
        $sqlstr .= "	,      KANR.LINE_NO \n";
        //$sqlstr .= "	,      '(実績)' || MEI.MEISYOU \n";
        $sqlstr .= "	,      '(実績)' || MEI.MEISYOU \n";
        $sqlstr .= "	,      MEI.SUCHI1 \n";
        $sqlstr .= "    ,      '2' KBN \n";
        $sqlstr .= "	,      ROUND(SUM(KANR.TOU_ZAN) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) JSKGK  \n";
        $sqlstr .= "	,      ROUND(SUM(CASE WHEN SUBSTR(KANR.KEIJO_DT,5,2) = '10' THEN KANR.TOU_ZAN ELSE 0 END) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) JSK10  \n";
        $sqlstr .= "	,      ROUND(SUM(CASE WHEN SUBSTR(KANR.KEIJO_DT,5,2) = '11' THEN KANR.TOU_ZAN ELSE 0 END) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) JSK11  \n";
        $sqlstr .= "	,      ROUND(SUM(CASE WHEN SUBSTR(KANR.KEIJO_DT,5,2) = '12' THEN KANR.TOU_ZAN ELSE 0 END) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) JSK12  \n";
        $sqlstr .= "	,      ROUND(SUM(CASE WHEN SUBSTR(KANR.KEIJO_DT,5,2) = '01' THEN KANR.TOU_ZAN ELSE 0 END) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) JSK1  \n";
        $sqlstr .= "	,      ROUND(SUM(CASE WHEN SUBSTR(KANR.KEIJO_DT,5,2) = '02' THEN KANR.TOU_ZAN ELSE 0 END) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) JSK2  \n";
        $sqlstr .= "	,      ROUND(SUM(CASE WHEN SUBSTR(KANR.KEIJO_DT,5,2) = '03' THEN KANR.TOU_ZAN ELSE 0 END) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) JSK3  \n";
        $sqlstr .= "	,      ROUND(SUM(CASE WHEN SUBSTR(KANR.KEIJO_DT,5,2) = '04' THEN KANR.TOU_ZAN ELSE 0 END) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) JSK4  \n";
        $sqlstr .= "	,      ROUND(SUM(CASE WHEN SUBSTR(KANR.KEIJO_DT,5,2) = '05' THEN KANR.TOU_ZAN ELSE 0 END) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) JSK5  \n";
        $sqlstr .= "	,      ROUND(SUM(CASE WHEN SUBSTR(KANR.KEIJO_DT,5,2) = '06' THEN KANR.TOU_ZAN ELSE 0 END) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) JSK6  \n";
        $sqlstr .= "	,      ROUND(SUM(CASE WHEN SUBSTR(KANR.KEIJO_DT,5,2) = '07' THEN KANR.TOU_ZAN ELSE 0 END) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) JSK7  \n";
        $sqlstr .= "	,      ROUND(SUM(CASE WHEN SUBSTR(KANR.KEIJO_DT,5,2) = '08' THEN KANR.TOU_ZAN ELSE 0 END) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) JSK8  \n";
        $sqlstr .= "	,      ROUND(SUM(CASE WHEN SUBSTR(KANR.KEIJO_DT,5,2) = '09' THEN KANR.TOU_ZAN ELSE 0 END) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) JSK9  \n";
        $sqlstr .= "	,      ROUND(SUM(M_KANR.TOU_ZAN) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) M_JSKGK  \n";
        //		$sqlstr .= "	FROM   HSIMRUISEKIKANR_NEW KANR  \n";
        $sqlstr .= "	FROM   HSIMRUISEKIKANR_KRSS KANR  \n";
        $sqlstr .= "	INNER JOIN HBUSYO BUS  \n";
        $sqlstr .= "	ON     BUS.BUSYO_CD = KANR.BUSYO_CD  \n";
        $sqlstr .= "	AND    BUS.BUSYO_KB = 'S' \n";
        $sqlstr .= "	AND    BUS.JISSEKITTL_KB IS NOT NULL \n";
        $sqlstr .= "	INNER JOIN HBUSYO BS \n";
        $sqlstr .= "	ON     BS.BUSYO_CD = BUS.TENPO_CD \n";
        //$sqlstr .= "	INNER JOIN HLINEMST_NEW LINE  \n";
        $sqlstr .= "	INNER JOIN HLINEMST_KEIEISEIKA LINE  \n";
        $sqlstr .= "	ON     LINE.LINE_NO = KANR.LINE_NO \n";
        $sqlstr .= "	INNER JOIN HMEISYOUMST MEI \n";
        $sqlstr .= "	ON     MEI.MEISYOU_CD = LINE.LINE_NO \n";
        //$sqlstr .= "	AND    MEI.MEISYOU_ID = '50' \n";
        $sqlstr .= "	AND    MEI.MEISYOU_ID = '52' \n";
        //		$sqlstr .= "    LEFT JOIN HSIMRUISEKIKANR_NEW M_KANR \n";
        $sqlstr .= "    LEFT JOIN HSIMRUISEKIKANR_KRSS M_KANR \n";
        $sqlstr .= "    ON     M_KANR.BUSYO_CD = KANR.BUSYO_CD \n";
        $sqlstr .= "    AND    M_KANR.LINE_NO = KANR.LINE_NO \n";
        $sqlstr .= "    AND    SUBSTR(M_KANR.KEIJO_DT,5,2) = SUBSTR(KANR.KEIJO_DT,5,2) \n";
        $sqlstr .= "    AND    (M_KANR.KEIJO_DT >= '@ZENKISYU' AND M_KANR.KEIJO_DT <= '@ZENTOUGETU') \n";
        $sqlstr .= "	WHERE (KANR.KEIJO_DT >= '@KONKISYU' AND  KANR.KEIJO_DT <= '@KONTOUGETU') \n";
        $sqlstr .= "	GROUP BY BUS.BUSYO_CD  \n";
        $sqlstr .= "	,        BUS.BUSYO_NM \n";
        $sqlstr .= "	,        BS.BUSYO_CD \n";
        $sqlstr .= "	,        BS.BUSYO_NM \n";
        $sqlstr .= "	,        KANR.BUSYO_CD \n";
        $sqlstr .= "	,        KANR.LINE_NO  \n";
        $sqlstr .= "	,        LINE.RND_POS \n";
        $sqlstr .= "	,        BUS.JISSEKITTL_KB \n";
        $sqlstr .= "	,        MEI.MEISYOU \n";
        $sqlstr .= "    ,      MEI.SUCHI1 \n";
        $sqlstr .= "	UNION ALL \n";
        $sqlstr .= "	SELECT (CASE WHEN V.JISSEKITTL_KB = '0' THEN BS.BUSYO_CD ELSE V.SIN_BUSYO_CD END) TENPO_CD \n";
        $sqlstr .= "	,      V.BUSYO_CD \n";
        $sqlstr .= "	,      (CASE WHEN V.JISSEKITTL_KB = '0' THEN BS.BUSYO_NM ELSE V.BUSYO_NM END) BUSYO_NM \n";
        $sqlstr .= "	,      KANR.LINE_NO \n";
        $sqlstr .= "	,      '(実績)' || MEI.MEISYOU \n";
        $sqlstr .= "    ,      MEI.SUCHI1 \n";
        $sqlstr .= "    ,      '2' KBN \n";
        $sqlstr .= "	,      ROUND(SUM(KANR.TOU_ZAN) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) JSKGK  \n";
        $sqlstr .= "	,      ROUND(SUM(CASE WHEN SUBSTR(KANR.KEIJO_DT,5,2) = '10' THEN KANR.TOU_ZAN ELSE 0 END) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) JSK10  \n";
        $sqlstr .= "	,      ROUND(SUM(CASE WHEN SUBSTR(KANR.KEIJO_DT,5,2) = '11' THEN KANR.TOU_ZAN ELSE 0 END) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) JSK11  \n";
        $sqlstr .= "	,      ROUND(SUM(CASE WHEN SUBSTR(KANR.KEIJO_DT,5,2) = '12' THEN KANR.TOU_ZAN ELSE 0 END) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) JSK12  \n";
        $sqlstr .= "	,      ROUND(SUM(CASE WHEN SUBSTR(KANR.KEIJO_DT,5,2) = '01' THEN KANR.TOU_ZAN ELSE 0 END) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) JSK1  \n";
        $sqlstr .= "	,      ROUND(SUM(CASE WHEN SUBSTR(KANR.KEIJO_DT,5,2) = '02' THEN KANR.TOU_ZAN ELSE 0 END) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) JSK2  \n";
        $sqlstr .= "	,      ROUND(SUM(CASE WHEN SUBSTR(KANR.KEIJO_DT,5,2) = '03' THEN KANR.TOU_ZAN ELSE 0 END) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) JSK3  \n";
        $sqlstr .= "	,      ROUND(SUM(CASE WHEN SUBSTR(KANR.KEIJO_DT,5,2) = '04' THEN KANR.TOU_ZAN ELSE 0 END) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) JSK4  \n";
        $sqlstr .= "	,      ROUND(SUM(CASE WHEN SUBSTR(KANR.KEIJO_DT,5,2) = '05' THEN KANR.TOU_ZAN ELSE 0 END) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) JSK5  \n";
        $sqlstr .= "	,      ROUND(SUM(CASE WHEN SUBSTR(KANR.KEIJO_DT,5,2) = '06' THEN KANR.TOU_ZAN ELSE 0 END) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) JSK6  \n";
        $sqlstr .= "	,      ROUND(SUM(CASE WHEN SUBSTR(KANR.KEIJO_DT,5,2) = '07' THEN KANR.TOU_ZAN ELSE 0 END) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) JSK7  \n";
        $sqlstr .= "	,      ROUND(SUM(CASE WHEN SUBSTR(KANR.KEIJO_DT,5,2) = '08' THEN KANR.TOU_ZAN ELSE 0 END) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) JSK8  \n";
        $sqlstr .= "	,      ROUND(SUM(CASE WHEN SUBSTR(KANR.KEIJO_DT,5,2) = '09' THEN KANR.TOU_ZAN ELSE 0 END) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) JSK9 \n";
        $sqlstr .= "	,      ROUND(SUM(M_KANR.TOU_ZAN) / POWER(10,(NVL(LINE.RND_POS,0) * -1))) M_JSKGK  \n";
        //		$sqlstr .= "	FROM   HSIMRUISEKIKANR_NEW KANR  \n";
        $sqlstr .= "	FROM   HSIMRUISEKIKANR_KRSS KANR  \n";
        $sqlstr .= "	INNER JOIN (SELECT A.BUSYO_CD, B.BUSYO_NM, B.BUSYO_CD SIN_BUSYO_CD, B.TENPO_CD, A.JISSEKITTL_KB  \n";
        $sqlstr .= "	            FROM 　HBUSYO A, HBUSYO B \n";
        $sqlstr .= "	            WHERE　A.BUSYO_KB = 'F' AND A.JISSEKITTL_KB IS NOT NULL \n";
        $sqlstr .= "	            AND B.BUSYO_KB = 'S' AND B.JISSEKITTL_KB IS NOT NULL \n";
        $sqlstr .= "	            AND A.TENPO_CD = B.TENPO_CD AND A.JISSEKITTL_KB = B.JISSEKITTL_KB) V \n";
        $sqlstr .= "	ON     V.BUSYO_CD = KANR.BUSYO_CD \n";
        $sqlstr .= "	INNER JOIN HBUSYO BS \n";
        $sqlstr .= "	ON     BS.BUSYO_CD = V.TENPO_CD \n";
        //$sqlstr .= "	INNER JOIN HLINEMST_NEW LINE  \n";
        $sqlstr .= "	INNER JOIN HLINEMST_KEIEISEIKA LINE  \n";
        $sqlstr .= "	ON     LINE.LINE_NO = KANR.LINE_NO \n";
        $sqlstr .= "	INNER JOIN HMEISYOUMST MEI \n";
        $sqlstr .= "	ON     MEI.MEISYOU_CD = LINE.LINE_NO \n";
        //$sqlstr .= "	AND    MEI.MEISYOU_ID = '51' \n";
        $sqlstr .= "	AND    MEI.MEISYOU_ID = '53' \n";
        //		$sqlstr .= "    LEFT JOIN HSIMRUISEKIKANR_NEW M_KANR \n";
        $sqlstr .= "    LEFT JOIN HSIMRUISEKIKANR_KRSS M_KANR \n";
        $sqlstr .= "    ON     M_KANR.BUSYO_CD = KANR.BUSYO_CD \n";
        $sqlstr .= "    AND    M_KANR.LINE_NO = KANR.LINE_NO \n";
        $sqlstr .= "    AND    SUBSTR(M_KANR.KEIJO_DT,5,2) = SUBSTR(KANR.KEIJO_DT,5,2) \n";
        $sqlstr .= "    AND    (M_KANR.KEIJO_DT >= '@ZENKISYU' AND M_KANR.KEIJO_DT <= '@ZENTOUGETU') \n";
        $sqlstr .= "	WHERE (KANR.KEIJO_DT >= '@KONKISYU' AND  KANR.KEIJO_DT <= '@KONTOUGETU') \n";
        $sqlstr .= "	GROUP BY V.SIN_BUSYO_CD \n";
        $sqlstr .= "	,        V.BUSYO_CD \n";
        $sqlstr .= "	,        V.BUSYO_NM \n";
        $sqlstr .= "	,        BS.BUSYO_CD \n";
        $sqlstr .= "	,        BS.BUSYO_NM \n";
        $sqlstr .= "	,        KANR.BUSYO_CD \n";
        $sqlstr .= "	,        KANR.LINE_NO  \n";
        $sqlstr .= "	,        LINE.RND_POS \n";
        $sqlstr .= "	,        V.JISSEKITTL_KB \n";
        $sqlstr .= "	,        MEI.MEISYOU \n";
        $sqlstr .= "    ,       MEI.SUCHI1 \n";
        $sqlstr .= ") MST \n";
        $sqlstr .= "ORDER BY TENPO_CD \n";
        $sqlstr .= ",        KBN \n";
        $sqlstr .= ",        YSN_BUSYO_CD \n";
        $sqlstr .= ",        SUCHI1 \n";

        $sqlstr = str_replace("@ZENKISYU", ((int) $KI - 1 + 1917) . "10", $sqlstr);
        $sqlstr = str_replace("@KONKISYU", $strKisyu, $sqlstr);
        $sqlstr = str_replace("@ZENTOUGETU", ((int) $Y - 1) . str_pad($M, 2, "0", STR_PAD_LEFT), $sqlstr);
        $sqlstr = str_replace("@KONTOUGETU", $Y . str_pad($M, 2, "0", STR_PAD_LEFT), $sqlstr);
        return $sqlstr;
    }

    //-EXCEL出力 e-
}
