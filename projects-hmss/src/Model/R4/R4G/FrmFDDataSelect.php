<?php
// 共通クラスの読込み
namespace App\Model\R4\R4G;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class FrmFDDataSelect extends ClsComDb
{
    //**********************************************************************
    //処 理 名：検索結果データ取得SQL
    //関 数 名：fncSearchTorokuyotei
    //引    数：$抽出状態値
    //引    数：$登録予定日From
    //引    数：$登録予定日To
    //戻 り 値：SQL文(string)
    //処理説明：検索結果データ取得します
    //**********************************************************************
    function fncSearchTorokuyotei($postData)
    {
        //対象データ取得SQL文字列を設定
        $strSQL = "";
        $strSQL .= "SELECT";
        $strSQL .= "             DECODE(NVL(KEI.FD_CRE_FLG,0),1,'True','False') FD_CRE ";
        $strSQL .= " ,           DECODE(NVL(KEI.INP_FLG,0),1,'True','False') INP_FLG ";
        $strSQL .= " ,           (SUB.STKSKNO || SUB.STRBTNO) KATASIKI ";
        $strSQL .= " ,           TRIM(TRK.WMI_SDAIKAT_CD) || '-' || TRIM(TRK.CAR_NO) CARNO ";
        $strSQL .= " ,           TRK.SHI_USER_NM ";
        $strSQL .= " ,           (TRK.SHI_ADDR1 || TRK.SHI_ADDR2) SHI_ADDRESS ";
        //20160531 Upd Start
//			$strSQL .= " ,           (CASE WHEN TRK.SYO_USER_NM IS NULL THEN CUS.CSRNM1 ELSE TRK.SYO_USER_NM END) SYO_USER_NM ";
        $strSQL .= " ,           (CASE WHEN TRK.SYO_USER_NM IS NULL THEN CUS.CSRNM1||CUS.CSRNM2  ELSE TRK.SYO_USER_NM END) SYO_USER_NM ";
        //20160531 Upd End
        $strSQL .= " ,           (TRK.SYO_ADDR1 || TRK.SYO_ADDR2) SYO_ADDRESS ";
        $strSQL .= " ,           TRK.TOU_Y_DT ";
        $strSQL .= " ,           TRK.CHUMN_NO ";

        $strSQL .= " FROM       M28T13 TRK ";
        $strSQL .= " LEFT JOIN M28T14 SUB ";
        $strSQL .= " ON    SUB.CHUMN_NO = TRK.CHUMN_NO ";
        $strSQL .= " LEFT  JOIN HKEIJIREPORT KEI ";
        $strSQL .= " ON    TRK.CHUMN_NO = KEI.CHUMN_NO ";
        $strSQL .= " LEFT JOIN M41C01 CUS ";
        $strSQL .= " ON    CUS.DLRCSRNO =  TRK.SYO_USER_CD ";

        $strSQL .= " WHERE      TRK.TOU_Y_DT > '";
        $strSQL .= date("Ymd", strtotime($postData['KAISHI'] . " -1    day"));
        $strSQL .= "'  AND      TRK.TOU_Y_DT < '";
        $strSQL .= date("Ymd", strtotime($postData['SYURYO'] . " +1    day"));
        $strSQL .= "'";

        if (isset($postData['Misakusei']) == true && $postData['Misakusei'] == "true") {
            $strSQL .= " AND   NVL(KEI.FD_CRE_FLG,'0') = '0'  ";
        }

        $strSQL .= " AND     TRK.FUKEI_KB = '2'  ";
        $strSQL .= " AND     TRK.TOROKU_KB = '00'  ";

        $strSQL .= "  ORDER BY  TRK.TOU_Y_DT, TRK.CHUMN_NO  ";

        //関数呼び出し元に設定したSQL文字列を返却
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：存在データチェックSQL
    //関 数 名：fncExistsTouroku
    //引    数：	$注文書番号
    //戻 り 値：SQL文(string)
    //処理説明：存在データチェックSQL取得します
    //**********************************************************************
    function fncExistsTouroku($strChumnNo)
    {
        //対象データ取得SQL文字列を設定
        $strSQL = "";
        $strSQL .= "SELECT";
        $strSQL .= "             KJI.CHUMN_NO ";

        $strSQL .= " FROM       HKEIJIREPORT KJI ";

        $strSQL .= " WHERE      KJI.CHUMN_NO = '";
        $strSQL .= $strChumnNo;
        $strSQL .= "' ";

        //関数呼び出し元に設定したSQL文字列を返却
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：新規追加のSQL
    //関 数 名：fncInsTourokuyotei
    //引    数：  $注文書番号
    //戻 り 値：SQL文(string)
    //処理説明：新規追加のSQL取得します
    //**********************************************************************
    function fncInsTourokuyotei($strChumnNo)
    {
        //対象データ取得SQL文字列を設定
        $strSQL = "";
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDCLTNM = $this->GS_LOGINUSER['strClientNM'];

        $strSQL .= " INSERT INTO HKEIJIREPORT  ";
        $strSQL .= "(";
        $strSQL .= "             CHUMN_NO ";
        $strSQL .= ",            TOU_Y_DT   ";
        $strSQL .= ",            REPORT_ID   ";
        $strSQL .= ",            GYOUMU_SYUBETU   ";
        $strSQL .= ",            SRY_SIJI   ";
        $strSQL .= ",            TESURYO   ";
        $strSQL .= ",            BAN_SIJI_YOT_1   ";
        $strSQL .= ",            BAN_SIJI_YOT_2   ";
        $strSQL .= ",            BAN_SIJI_HBN_1   ";
        $strSQL .= ",            BAN_SIJI_HBN_2   ";
        $strSQL .= ",            HOJO_SHEET   ";
        $strSQL .= ",            KIBO_SRY_BUNRUI   ";
        $strSQL .= ",            KIBO_SRY_KANA   ";
        $strSQL .= ",            KIBO_SRY_KIBO   ";
        $strSQL .= ",            BIKOU   ";
        $strSQL .= ",            SYORI1   ";
        $strSQL .= ",            SYORI2   ";
        $strSQL .= ",            SRY_BAN_MOJI   ";
        $strSQL .= ",            SRY_BAN_BUNRUI   ";
        $strSQL .= ",            SRY_BAN_KANA   ";
        $strSQL .= ",            SRY_BAN_SITEI   ";
        $strSQL .= ",            SRY_BAN_SYOUBAN   ";
        $strSQL .= ",            REIGAI   ";
        $strSQL .= ",            TEIKI_TENKEN1   ";
        $strSQL .= ",            TEIKI_TENKEN2   ";
        $strSQL .= ",            SYADAI_NO   ";
        $strSQL .= ",            SYADAI_NO_HENKO   ";
        $strSQL .= ",            SYOYU_CD   ";
        $strSQL .= ",            SYOYU_SIYO   ";
        $strSQL .= ",            SHIYOU_NM   ";
        $strSQL .= ",            SHIYOU_ADDR_CD   ";
        $strSQL .= ",            SHIYOU_ADDR_1   ";
        $strSQL .= ",            SHIYOU_ADDR_2   ";
        $strSQL .= ",            SYOYU_NM_SIYO   ";
        $strSQL .= ",            SYOYU_NM   ";
        $strSQL .= ",            SYOYU_ADDR_SIYO   ";
        $strSQL .= ",            SYOYU_ADDR_CD   ";
        $strSQL .= ",            SYOYU_ADDR_1   ";
        $strSQL .= ",            SYOYU_ADDR_2   ";
        $strSQL .= ",            HONKYO_ADDR_SIYO   ";
        $strSQL .= ",            HONKYO_ADDR_CD   ";
        $strSQL .= ",            HONKYO_ADDR_1   ";
        $strSQL .= ",            HONKYO_ADDR_2   ";
        $strSQL .= ",            HONKYO_ADDR_NM   ";
        $strSQL .= ",            KATASIKI_RUIBETU   ";
        $strSQL .= ",            IRO_CD   ";
        $strSQL .= ",            SEISAKU_GENGO   ";
        $strSQL .= ",            SEISAKU_YMD   ";
        $strSQL .= ",            SYOMEI_SIJI   ";
        //20170104 Ins Start
        $strSQL .= ",            SYOMEI_SIJI2  ";
        //20170104 Ins End
        $strSQL .= ",            SOUCHI_CD1   ";
        $strSQL .= ",            SOUCHI_CD2   ";
        $strSQL .= ",            SOUCHI_CD3   ";
        $strSQL .= ",            SOUCHI_CD4   ";
        $strSQL .= ",            SOUCHI_CD5   ";
        $strSQL .= ",            RYUTU_KAKUNIN   ";
        $strSQL .= ",            HNB_CD   ";
        $strSQL .= ",            SINSEI_SIYO_NM   ";
        $strSQL .= ",            SINSEI_SIYO_ADDR   ";
        $strSQL .= ",            SINSEI_SYOYU_NM   ";
        $strSQL .= ",            SINSEI_SYOYU_ADDR   ";
        $strSQL .= ",            SINSEI_JUKEN_NM   ";
        $strSQL .= ",            SINSEI_JUKEN_ADDR   ";
        $strSQL .= ",            TEIKYO_JIKOU   ";
        $strSQL .= ",            FD_CRE_FLG   ";
        $strSQL .= ",            INP_FLG   ";
        $strSQL .= ",            UPD_DATE   ";
        $strSQL .= ",            CREATE_DATE   ";
        $strSQL .= ",            UPD_SYA_CD   ";
        $strSQL .= ",            UPD_PRG_ID   ";
        $strSQL .= ",            UPD_CLT_NM   ";
        $strSQL .= ")";
        $strSQL .= "    SELECT  ";
        $strSQL .= "    '";
        $strSQL .= $strChumnNo;
        $strSQL .= "'  ";
        $strSQL .= ",    SUBSTRB(TRK.TOU_Y_DT,1,8)  ";
        $strSQL .= ",    '00109'  ";
        $strSQL .= ",    '1'  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    1  ";
        $strSQL .= ",    SUBSTRB(SUB.TR_JIKOUSIKI_KB,1,1)  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    SUBSTRB(TRIM(TRK.WMI_SDAIKAT_CD) || '-' || TRIM(TRK.CAR_NO),1,20)  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    SUBSTRB(TRK.SHI_USER_NM,1,34)  ";
        $strSQL .= ",    SUBSTRB(TRK.SHI_ADDR_CD,1,12)  ";
        $strSQL .= ",    SUBSTRB(TO_SINGLE_BYTE(SUBSTR(TRK.SHI_ADDR2,0,INSTR(TRK.SHI_ADDR2,'丁目') - 1)),1,2)  ";
        $strSQL .= ",    SUBSTRB(TO_SINGLE_BYTE(REPLACE(SUBSTR(TRK.SHI_ADDR2,INSTR(TRK.SHI_ADDR2,'丁目')),'丁目')),1,16)  ";
        $strSQL .= ",    NULL  ";
        //20160531 Upd Start
//			$strSQL .= ",    TRIM(SUBSTRB((CASE WHEN TRK.SYO_USER_NM IS NULL THEN CUS.CSRNM1 ELSE TRK.SYO_USER_NM END),1,32))  ";
        $strSQL .= ",    TRIM(SUBSTRB((CASE WHEN TRK.SYO_USER_NM IS NULL THEN CUS.CSRNM1||CUS.CSRNM2 ELSE TRK.SYO_USER_NM END),1,32))  ";
        //20160531 Upd End
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    SUBSTRB(TRK.SYO_ADDR_CD,1,12)  ";
        $strSQL .= ",    SUBSTRB(TO_SINGLE_BYTE(SUBSTR(TRK.SYO_ADDR2,0,INSTR(TRK.SYO_ADDR2,'丁目') -1)),1,2)  ";
        $strSQL .= ",    SUBSTRB(TO_SINGLE_BYTE(REPLACE(SUBSTR(TRK.SYO_ADDR2,INSTR(TRK.SYO_ADDR2,'丁目')),'丁目')),1,14)  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    SUBSTRB(SUB.TR_HON_ADDR_CD,1,12)  ";
        $strSQL .= ",    SUBSTRB(TO_SINGLE_BYTE(SUBSTR(SUB.TR_HON_ADDR2,0,INSTR(SUB.TR_HON_ADDR2,'丁目') -1)),1,2)  ";
        $strSQL .= ",    SUBSTRB(TO_SINGLE_BYTE(REPLACE(SUBSTR(SUB.TR_HON_ADDR2,INSTR(SUB.TR_HON_ADDR2,'丁目')),'丁目')),1,14)  ";
        $strSQL .= ",    SUBSTRB(SUB.TR_HON_ADDR1 || SUB.TR_HON_ADDR2,1,160)  ";
        $strSQL .= ",    SUBSTRB(SUB.STKSKNO || SUB.STRBTNO,1,9)  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    NULL  ";
        //20170104 Ins Start
        $strSQL .= ",    NULL  ";
        //20170104 Ins End
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    '40012'  ";
        $strSQL .= ",    SUBSTRB(TRK.SHI_USER_NM,1,160)  ";
        $strSQL .= ",    SUBSTRB(TRK.SHI_ADDR1 || TRK.SHI_ADDR2,1,160) ";
        //20160531 Upd Start
//			$strSQL .= ",    SUBSTRB((CASE WHEN TRK.SYO_USER_NM IS NULL THEN CUS.CSRNM1 ELSE TRK.SYO_USER_NM END),1,160)  ";
        $strSQL .= ",    SUBSTRB((CASE WHEN TRK.SYO_USER_NM IS NULL THEN CUS.CSRNM1||CUS.CSRNM2 ELSE TRK.SYO_USER_NM END),1,160)  ";
        //20160531 Upd End
        $strSQL .= ",    SUBSTRB(TRK.SYO_ADDR1 || TRK.SYO_ADDR2,1,160)  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    '完成検査終了証' ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    NULL  ";
        $strSQL .= ",    SYSDATE  ";
        $strSQL .= ",    SYSDATE  ";
        $strSQL .= ",     '";
        $strSQL .= $UPDUSER;
        $strSQL .= "'  ";
        $strSQL .= ",     'FDDataSelect'";
        $strSQL .= ",     '";
        $strSQL .= $UPDCLTNM;
        $strSQL .= "'  ";

        $strSQL .= " FROM       M28T13 TRK  ";
        $strSQL .= " INNER JOIN M28T14 SUB  ";
        $strSQL .= " ON    SUB.CHUMN_NO = TRK.CHUMN_NO  ";
        $strSQL .= " LEFT JOIN M41C01 CUS  ";
        $strSQL .= " ON    CUS.DLRCSRNO =  TRK.SYO_USER_CD  ";

        $strSQL .= " WHERE      TRK.CHUMN_NO = '";
        $strSQL .= $strChumnNo;
        $strSQL .= "'";

        //関数呼び出し元に設定したSQL文字列を返却
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：更新データのSQL
    //関 数 名：fncUpdTourokuyotei
    //引    数：	$注文書番号
    //戻 り 値：SQL文(string)
    //処理説明：更新データのSQL取得します
    //**********************************************************************
    function fncUpdTourokuyotei($strChumnNo)
    {
        //対象データ取得SQL文字列を設定
        $strSQL = "";
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDCLTNM = $this->GS_LOGINUSER['strClientNM'];

        $strSQL .= " UPDATE HKEIJIREPORT KJI  ";
        $strSQL .= "SET";
        $strSQL .= "             KJI.BAN_SIJI_HBN_1 = (SELECT SUBSTRB(TR_JIKOUSIKI_KB,1,1) FROM M28T14 SUB WHERE SUB.CHUMN_NO = KJI.CHUMN_NO) ";
        $strSQL .= ",            KJI.SYADAI_NO = (SELECT SUBSTRB(TRIM(WMI_SDAIKAT_CD) || '-' || TRIM(CAR_NO),1,20) FROM M28T13 TOU WHERE TOU.CHUMN_NO = KJI.CHUMN_NO)   ";
        $strSQL .= ",            KJI.SHIYOU_NM = (SELECT SUBSTRB(SHI_USER_NM,1,34) FROM  M28T13 TOU WHERE TOU.CHUMN_NO = KJI.CHUMN_NO)   ";
        $strSQL .= ",            KJI.SHIYOU_ADDR_CD = (SELECT SUBSTRB(SHI_ADDR_CD,1,12) FROM M28T13 TOU WHERE TOU.CHUMN_NO = KJI.CHUMN_NO)  ";
        $strSQL .= ",            KJI.SHIYOU_ADDR_1 = (SELECT SUBSTRB(TO_SINGLE_BYTE(SUBSTR(TOU.SHI_ADDR2,0,INSTR(TOU.SHI_ADDR2,'丁目') - 1)),1,2) FROM M28T13 TOU WHERE TOU.CHUMN_NO = KJI.CHUMN_NO)  ";
        $strSQL .= ",            KJI.SHIYOU_ADDR_2 = (SELECT SUBSTRB(TO_SINGLE_BYTE(REPLACE(SUBSTR(TOU.SHI_ADDR2,INSTR(TOU.SHI_ADDR2,'丁目')),'丁目')),1,16) FROM M28T13 TOU WHERE TOU.CHUMN_NO = KJI.CHUMN_NO)  ";
        //20160531 Upd Start
//			$strSQL .= ",            KJI.SYOYU_NM =  TRIM(SUBSTRB((SELECT (CASE WHEN TOU.SYO_USER_NM IS NULL THEN CUS.CSRNM1 ELSE TOU.SYO_USER_NM END) FROM M28T13 TOU LEFT JOIN M41C01 CUS ON CUS.DLRCSRNO = TOU.SYO_USER_CD WHERE TOU.CHUMN_NO = KJI.CHUMN_NO),1,32))  ";
        $strSQL .= ",            KJI.SYOYU_NM =  TRIM(SUBSTRB((SELECT (CASE WHEN TOU.SYO_USER_NM IS NULL THEN CUS.CSRNM1||CUS.CSRNM2 ELSE TOU.SYO_USER_NM END) FROM M28T13 TOU LEFT JOIN M41C01 CUS ON CUS.DLRCSRNO = TOU.SYO_USER_CD WHERE TOU.CHUMN_NO = KJI.CHUMN_NO),1,32))  ";
        //20160531 Upd End
        $strSQL .= ",            KJI.SYOYU_NM_SIYO = NULL  ";
        $strSQL .= ",            KJI.SYOYU_ADDR_CD = (SELECT SUBSTRB(SYO_ADDR_CD,1,12) FROM M28T13 TOU WHERE TOU.CHUMN_NO = KJI.CHUMN_NO)  ";
        $strSQL .= ",            KJI.SYOYU_ADDR_1 = (SELECT SUBSTRB(TO_SINGLE_BYTE(SUBSTR(TOU.SYO_ADDR2,0,INSTR(TOU.SYO_ADDR2,'丁目') - 1)),1,2) FROM M28T13 TOU WHERE TOU.CHUMN_NO = KJI.CHUMN_NO)  ";
        $strSQL .= ",            KJI.SYOYU_ADDR_2 = (SELECT SUBSTRB(TO_SINGLE_BYTE(REPLACE(SUBSTR(TOU.SYO_ADDR2,INSTR(TOU.SYO_ADDR2,'丁目')),'丁目')),1,14) FROM M28T13 TOU WHERE TOU.CHUMN_NO = KJI.CHUMN_NO)  ";
        $strSQL .= ",            KJI.SYOYU_ADDR_SIYO = NULL  ";
        $strSQL .= ",            KJI.HONKYO_ADDR_CD = (SELECT SUBSTRB(TR_HON_ADDR_CD,1,12) FROM M28T14 SUB WHERE SUB.CHUMN_NO = KJI.CHUMN_NO)  ";
        $strSQL .= ",            KJI.HONKYO_ADDR_1 = (SELECT SUBSTRB(TO_SINGLE_BYTE(SUBSTR(SUB.TR_HON_ADDR2,0,INSTR(SUB.TR_HON_ADDR2 , '丁目') - 1)),1,2) FROM M28T14 SUB WHERE SUB.CHUMN_NO = KJI.CHUMN_NO)  ";
        $strSQL .= ",            KJI.HONKYO_ADDR_2 =  (SELECT SUBSTRB(TO_SINGLE_BYTE(REPLACE(SUBSTR(TOU.TR_HON_ADDR2, INSTR(TOU.TR_HON_ADDR2,'丁目')),'丁目')),1,14) FROM M28T14 TOU WHERE TOU.CHUMN_NO = KJI.CHUMN_NO)  ";
        $strSQL .= ",            KJI.HONKYO_ADDR_NM = (SELECT SUBSTRB(TR_HON_ADDR1 || TR_HON_ADDR2,1,160) FROM M28T14 TOU WHERE TOU.CHUMN_NO = KJI.CHUMN_NO)  ";
        $strSQL .= ",            KJI.HONKYO_ADDR_SIYO = NULL  ";
        $strSQL .= ",            KJI.KATASIKI_RUIBETU = (SELECT SUBSTRB(TOU.STKSKNO || TOU.STRBTNO,1,9) FROM M28T14 TOU WHERE TOU.CHUMN_NO = KJI.CHUMN_NO)  ";
        $strSQL .= ",            KJI.SINSEI_SIYO_NM = (SELECT SUBSTRB(SHI_USER_NM,1,160) FROM M28T13 TOU WHERE TOU.CHUMN_NO = KJI.CHUMN_NO)  ";
        $strSQL .= ",            KJI.SINSEI_SIYO_ADDR = (SELECT SUBSTRB(TOU.SHI_ADDR1 || TOU.SHI_ADDR2,1,160) FROM M28T13 TOU WHERE TOU.CHUMN_NO = KJI.CHUMN_NO)  ";
        //2016/05/31 Upd Start
//			$strSQL .= ",            KJI.SINSEI_SYOYU_NM = (SELECT SUBSTRB((CASE WHEN TOU.SYO_USER_NM IS NULL THEN CUS.CSRNM1 ELSE TOU.SYO_USER_NM END),1,160) FROM M28T13 TOU LEFT JOIN M41C01 CUS ON CUS.DLRCSRNO = TOU.SYO_USER_CD WHERE TOU.CHUMN_NO = KJI.CHUMN_NO)  ";
        $strSQL .= ",            KJI.SINSEI_SYOYU_NM = (SELECT SUBSTRB((CASE WHEN TOU.SYO_USER_NM IS NULL THEN CUS.CSRNM1||CUS.CSRNM2 ELSE TOU.SYO_USER_NM END),1,160) FROM M28T13 TOU LEFT JOIN M41C01 CUS ON CUS.DLRCSRNO = TOU.SYO_USER_CD WHERE TOU.CHUMN_NO = KJI.CHUMN_NO)  ";
        //2016/05/31 Upd End
        $strSQL .= ",            KJI.SINSEI_SYOYU_ADDR = (SELECT SUBSTRB(TOU.SYO_ADDR1 || TOU.SYO_ADDR2,1,160) FROM M28T13 TOU WHERE TOU.CHUMN_NO = KJI.CHUMN_NO)  ";
        $strSQL .= ",            KJI.INP_FLG = NULL  ";
        $strSQL .= ",            KJI.UPD_DATE = SYSDATE  ";
        $strSQL .= ",            UPD_SYA_CD = '";
        $strSQL .= $UPDUSER;
        $strSQL .= "'  ";
        $strSQL .= ",            UPD_PRG_ID = 'FDDataSelect'  ";
        $strSQL .= ",            UPD_CLT_NM = '";
        $strSQL .= $UPDCLTNM;
        $strSQL .= "'  ";
        $strSQL .= ",            KJI.REPORT_ID = '00109'  ";
        $strSQL .= " WHERE ";
        $strSQL .= " KJI.CHUMN_NO = '";
        $strSQL .= $strChumnNo;
        $strSQL .= "'";

        //関数呼び出し元に設定したSQL文字列を返却
        return $strSQL;
    }

    public function fncFrmFDDataSelect($postData)
    {
        $strSql = $this->fncSearchTorokuyotei($postData);
        return $this->m_run_sql($strSql);
    }

    public function createJqGrid($postData)
    {
        $strSql = $this->fncSearchTorokuyotei($postData);
        return $this->m_run_sql($strSql);
    }

    public function m_run_sql($strSql)
    {
        // include_once ("base.php");
        // $objBase = new base();
        // return parent::select($objBase -> create_sql($strSql, $sortStr, $start, $limit));

        return parent::select($strSql);
    }

    public function funExistCheck($strChumnNo)
    {
        return parent::Fill($this->fncExistsTouroku($strChumnNo));
    }

    public function fncInsData($strChumnNo)
    {
        return parent::Do_Execute($this->fncInsTourokuyotei($strChumnNo));
    }

    public function fncUpdData($strChumnNo)
    {
        return parent::Do_Execute($this->fncUpdTourokuyotei($strChumnNo));
    }

}
