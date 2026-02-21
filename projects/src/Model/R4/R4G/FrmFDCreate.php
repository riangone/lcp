<?php
// 共通クラスの読込み
namespace App\Model\R4\R4G;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class FrmFDCreate extends ClsComDb
{

    public function fncSearchTorokuyotei($postData)
    {
        $strSQL = "		SELECT ";
        $strSQL .= "	     DECODE(NVL(KEI.FD_CRE_FLG,0),1,'True','False') FD_CRE";
        $strSQL .= ",        DECODE(NVL(KEI.INP_FLG,0),1,'True','False') INP_FLG";
        $strSQL .= ",        KEI.KATASIKI_RUIBETU KATASIKI						";
        $strSQL .= ",        KEI.SYADAI_NO CARNO";
        $strSQL .= ",        KEI.SINSEI_SIYO_NM SHI_USER_NM	";
        $strSQL .= ",        KEI.SINSEI_SIYO_ADDR SHI_ADDRESS";
        $strSQL .= ",        KEI.SINSEI_SYOYU_NM SYO_USER_NM";
        $strSQL .= ",        SINSEI_SYOYU_ADDR SYO_ADDRESS";
        $strSQL .= ",        KEI.CHUMN_NO";
        $strSQL .= ",        KEI.TOU_Y_DT";
        $strSQL .= ",        KEI.SYOYU_NM_SIYO";
        $strSQL .= ",        KEI.SYOYU_ADDR_SIYO";
        $strSQL .= "  	FROM   ";
        $strSQL .= " 	    HKEIJIREPORT KEI";
        $strSQL .= " 	WHERE ";
        $strSQL .= "        KEI.TOU_Y_DT = ";
        $strSQL .= "'" . date("Ymd", strtotime($postData['Touroku'])) . "'";
        if (isset($postData['Misakusei']) == true && $postData['Misakusei'] == "true") {
            $strSQL .= " AND   NVL(KEI.FD_CRE_FLG,'0') = '0'  ";
        }
        $strSQL .= " ORDER BY KEI.TOU_Y_DT, KEI.CHUMN_NO ";
        return $strSQL;

    }
    public function fncSearchTorokuyotei_part($postData)
    {
        $strSQL = "		SELECT ";
        $strSQL .= "        DECODE(NVL(KEI.INP_FLG,0),1,'True','False') INP_FLG";
        $strSQL .= ",        KEI.SINSEI_SIYO_NM SHI_USER_NM	";
        $strSQL .= ",        KEI.SINSEI_SYOYU_NM SYO_USER_NM";
        $strSQL .= ",        KEI.CHUMN_NO";
        $strSQL .= ",        KEI.TOU_Y_DT";
        $strSQL .= "  	FROM   ";
        $strSQL .= " 	    HKEIJIREPORT KEI";
        $strSQL .= " 	WHERE ";
        $strSQL .= "        KEI.TOU_Y_DT = ";
        $strSQL .= "'" . date("Ymd", strtotime($postData['Touroku'])) . "'";
        if (isset($postData['Misakusei']) == true && $postData['Misakusei'] == "true") {
            $strSQL .= " AND   NVL(KEI.FD_CRE_FLG,'0') = '0'  ";
        }
        $strSQL .= " ORDER BY KEI.TOU_Y_DT, KEI.CHUMN_NO ";
        return $strSQL;
    }
    public function fncFrmFDCreate($postData)
    {
        $strSql = $this->fncSearchTorokuyotei($postData);
        return $this->m_run_sql($strSql);
    }
    public function fncFrmFDCreate_part($postData)
    {
        $strSql = $this->fncSearchTorokuyotei_part($postData);
        return $this->m_run_sql($strSql);
    }
    public function m_run_sql($strSql)
    {
        /*
         * 运行sql文
         * 说明：返回数组。
         * 数组形式：{result:true,data:[key:value]}
         */
        return parent::select($strSql);
    }

    //excel line 382　 , vb line 1602
    public function fncCsvSelect($DFChumnno)
    {
        $strSQL = "";
        $strSQL .= "		   SELECT REPORT_ID	";
        $strSQL .= "	,      GYOUMU_SYUBETU	";
        $strSQL .= "	,      SRY_SIJI	";
        $strSQL .= "	,      TESURYO	";
        $strSQL .= "	,      HOJO_SHEET	";
        $strSQL .= "	,      BAN_SIJI_YOT_1	";
        $strSQL .= "	,      BAN_SIJI_YOT_2	";
        $strSQL .= "	,      BAN_SIJI_HBN_1   ";
        $strSQL .= "	,      BAN_SIJI_HBN_2	";
        $strSQL .= "	,      BIKOU	";
        $strSQL .= "	,      SYORI1	";
        $strSQL .= "	,      SYORI2	";
        $strSQL .= "	,      REIGAI	";
        $strSQL .= "	,      NULL SEIGEN_KAIJYO	";
        $strSQL .= "	,      SYOMEI_SIJI	";
        //20170104 Ins Start
        $strSQL .= "	,      SYOMEI_SIJI2	";
        //20170104 Ins End
        $strSQL .= "	,      KIBO_SRY_BUNRUI	";
        $strSQL .= "	,      KIBO_SRY_KANA	";
        $strSQL .= "	,      KIBO_SRY_KIBO	";
        $strSQL .= "	,      SRY_BAN_MOJI		";
        $strSQL .= "	,      SRY_BAN_BUNRUI	";
        $strSQL .= "	,      SRY_BAN_KANA		";
        $strSQL .= "	,      SRY_BAN_SITEI	";
        $strSQL .= "	,      SRY_BAN_SYOUBAN	";
        $strSQL .= "	,      SUBSTR(SYADAI_NO,INSTR(SYADAI_NO,'-') + 1) SYADAI_NO		";
        $strSQL .= "	,      SYADAI_NO_HENKO		";
        $strSQL .= "	,      SHIYOU_NM	";
        $strSQL .= "	,      SHIYOU_ADDR_CD	";
        $strSQL .= "	,      SHIYOU_ADDR_1	";
        $strSQL .= "	,      SHIYOU_ADDR_2	";
        $strSQL .= "	,      RYUTU_KAKUNIN	";
        $strSQL .= "	,      HNB_CD	";
        $strSQL .= "	,      SYOYU_NM_SIYO	";
        $strSQL .= "	,      SYOYU_NM		";
        $strSQL .= "	,      SYOYU_ADDR_SIYO	";
        $strSQL .= "	,      SYOYU_ADDR_CD	";
        $strSQL .= "	,      SYOYU_ADDR_1	";
        $strSQL .= "	,      SYOYU_ADDR_2	";
        $strSQL .= "	,      SYOYU_CD		";
        $strSQL .= "	,      SYOYU_SIYO	";
        $strSQL .= "	,      HONKYO_ADDR_SIYO	";
        $strSQL .= "	,      HONKYO_ADDR_CD	";
        $strSQL .= "	,      HONKYO_ADDR_1	";
        $strSQL .= "	,      HONKYO_ADDR_2 ";
        $strSQL .= "	,      IRO_CD	";
        $strSQL .= "	,      KATASIKI_RUIBETU	 ";
        $strSQL .= "	,      SEISAKU_GENGO	";
        $strSQL .= "	,      SEISAKU_YMD	";
        $strSQL .= "	,      NULL SOUKO_KYORI_DISP	";
        $strSQL .= "	,      NULL SOUKO_KYORI_MILE ";
        $strSQL .= "	,      NULL SEIBI_KOJYO_1	";
        $strSQL .= "	,      NULL SEIBI_KOJYO_2	";
        $strSQL .= "	,      TEIKI_TENKEN1	";
        $strSQL .= "	,      NULL JYUKEN_KEITAI";
        $strSQL .= "	,      SOUCHI_CD1		";
        $strSQL .= "	,      SOUCHI_CD2		";
        $strSQL .= "	,      SOUCHI_CD3	";
        $strSQL .= "	,      SOUCHI_CD4	";
        $strSQL .= "	,      SOUCHI_CD5	";
        $strSQL .= "	,      NULL SOUCHI_CD6	";
        $strSQL .= "	,      NULL SOUCHI_CD7	";
        $strSQL .= "	,      SINSEI_SIYO_NM	";
        $strSQL .= "	,      SINSEI_SIYO_ADDR	";
        $strSQL .= "	,      SINSEI_SYOYU_NM	";
        $strSQL .= "	,      SINSEI_SYOYU_ADDR  ";
        $strSQL .= "	,      NULL SINSEI_OLDSIYO_NM ";
        $strSQL .= "	,      NULL SINSEI_OLDSIYO_ADDR	 ";
        $strSQL .= "	,      NULL SINSEI_OLDSYOYU_NM	";
        $strSQL .= "	,      NULL SINSEI_OLDSYOYU_ADDR	";
        $strSQL .= "	,      SINSEI_JUKEN_NM	";
        $strSQL .= "	,      SINSEI_JUKEN_ADDR	";
        $strSQL .= "	,      TEIKYO_JIKOU		";
        $strSQL .= "	,      NULL HENKO_RIRNO	";
        $strSQL .= "	,      HONKYO_ADDR_NM	";
        $strSQL .= "	,      SYADAI_NO PRINT_SYADAI_NO	";
        $strSQL .= "    FROM   HKEIJIREPORT			";
        $strSQL .= "    WHERE  CHUMN_NO = '" . $DFChumnno . "'";
        return parent::Fill($strSQL);
    }

    public function fncUpdCreateFlg($DFChumnno)
    {
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDAPP = "FDCreate";
        $UPDCLTNM = $this->GS_LOGINUSER['strClientNM'];

        $strSQL = "";
        $strSQL = "UPDATE HKEIJIREPORT SET ";
        $strSQL .= " FD_CRE_FLG = '1' ";
        $strSQL .= " ,UPD_DATE = SYSDATE ";
        $strSQL .= " ,UPD_SYA_CD = '" . $UPDUSER . "'";
        $strSQL .= " ,UPD_PRG_ID = '" . $UPDAPP . "'";
        $strSQL .= " ,UPD_CLT_NM = '" . $UPDCLTNM . "'";
        $strSQL .= "WHERE  CHUMN_NO = '" . $DFChumnno . "'";
        return parent::Do_Execute($strSQL);
    }

}