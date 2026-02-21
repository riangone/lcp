<?php
// 共通クラスの読込み
namespace App\Model\R4\R4G;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class FrmFDHokanInput extends ClsComDb
{
    //**********************************************************************
    //処 理 名：検索データ取得ためのSQL
    //関 数 名：fncKeijiReportSet
    //引    数：$注文書番号
    //戻 り 値：SQL文(string)
    //処理説明：検索結果データ取得します
    //**********************************************************************
    function fncKeijiReportSet($strChumnNo)
    {
        //対象データ取得SQL文字列を設定
        $strSQL = "";
        $strSQL .= "SELECT";
        $strSQL .= "             CHUMN_NO ";
        $strSQL .= ",            (SUBSTR(TOU_Y_DT,1,4) || '/' || SUBSTR(TOU_Y_DT,5,2) || '/' || SUBSTR(TOU_Y_DT,7,2)) TOU_Y_DT ";
        $strSQL .= ",             REPORT_ID ";
        $strSQL .= ",             GYOUMU_SYUBETU ";
        $strSQL .= ",             SRY_SIJI ";
        $strSQL .= ",             TESURYO ";
        $strSQL .= ",             BAN_SIJI_YOT_1 ";
        $strSQL .= ",             DECODE(NVL(BAN_SIJI_YOT_2,0),0,0,BAN_SIJI_YOT_2 - 1) BAN_SIJI_YOT_2 ";
        $strSQL .= ",             BAN_SIJI_HBN_1 ";
        $strSQL .= ",             BAN_SIJI_HBN_2 ";
        $strSQL .= ",             HOJO_SHEET ";
        $strSQL .= ",             KIBO_SRY_BUNRUI ";
        $strSQL .= ",             KIBO_SRY_KANA ";
        $strSQL .= ",             KIBO_SRY_KIBO ";
        $strSQL .= ",             BIKOU ";
        $strSQL .= ",             SYORI1 ";
        $strSQL .= ",             SYORI2 ";
        $strSQL .= ",             SRY_BAN_MOJI ";
        $strSQL .= ",             SRY_BAN_BUNRUI ";
        $strSQL .= ",             SRY_BAN_KANA ";
        $strSQL .= ",             SRY_BAN_SITEI ";
        $strSQL .= ",             SRY_BAN_SYOUBAN ";
        $strSQL .= ",             REIGAI ";
        $strSQL .= ",             TEIKI_TENKEN1 ";
        $strSQL .= ",             TEIKI_TENKEN2 ";
        $strSQL .= ",             SYADAI_NO ";
        $strSQL .= ",             SYADAI_NO_HENKO ";
        $strSQL .= ",             SYOYU_CD ";
        $strSQL .= ",             SYOYU_SIYO ";
        $strSQL .= ",             SHIYOU_NM ";
        $strSQL .= ",             SHIYOU_ADDR_CD ";
        $strSQL .= ",             SHIYOU_ADDR_1 ";
        $strSQL .= ",             SHIYOU_ADDR_2 ";
        $strSQL .= ",             SYOYU_NM_SIYO ";
        $strSQL .= ",             SYOYU_NM ";
        $strSQL .= ",             SYOYU_ADDR_SIYO ";
        $strSQL .= ",             SYOYU_ADDR_CD ";
        $strSQL .= ",             SYOYU_ADDR_1";
        $strSQL .= ",             SYOYU_ADDR_2 ";
        $strSQL .= ",             HONKYO_ADDR_SIYO ";
        $strSQL .= ",             HONKYO_ADDR_CD ";
        $strSQL .= ",             HONKYO_ADDR_1 ";
        $strSQL .= ",             HONKYO_ADDR_2 ";
        $strSQL .= ",             HONKYO_ADDR_NM ";
        $strSQL .= ",             KATASIKI_RUIBETU ";
        $strSQL .= ",             (CASE WHEN IRO_CD = 0 THEN 10 ELSE IRO_CD END) IRO_CD ";
        $strSQL .= ",             SEISAKU_GENGO ";
        $strSQL .= ",             SUBSTR(SEISAKU_YMD,1,2) SEISAKU_Y ";
        $strSQL .= ",             SUBSTR(SEISAKU_YMD,3,2) SEISAKU_M ";
        $strSQL .= ",             SUBSTR(SEISAKU_YMD,5,2) SEISAKU_D ";
        $strSQL .= ",             SYOMEI_SIJI ";
        //20170104 Add Start
        $strSQL .= ",             SYOMEI_SIJI2 ";
        //20170104 Add End
        $strSQL .= ",             SOUCHI_CD1 ";
        $strSQL .= ",             SOUCHI_CD2 ";
        $strSQL .= ",             SOUCHI_CD3 ";
        $strSQL .= ",             SOUCHI_CD4 ";
        $strSQL .= ",             SOUCHI_CD5 ";
        $strSQL .= ",             RYUTU_KAKUNIN ";
        $strSQL .= ",             HNB_CD ";
        $strSQL .= ",             SINSEI_SIYO_NM ";
        $strSQL .= ",             SINSEI_SIYO_ADDR ";
        $strSQL .= ",             SINSEI_SYOYU_NM ";
        $strSQL .= ",             SINSEI_SYOYU_ADDR ";
        $strSQL .= ",             SINSEI_JUKEN_NM ";
        $strSQL .= ",             SINSEI_JUKEN_ADDR ";
        $strSQL .= ",             TEIKYO_JIKOU ";
        $strSQL .= ",             FD_CRE_FLG ";
        $strSQL .= ",             INP_FLG ";
        $strSQL .= ",             UPD_DATE ";
        $strSQL .= ",             CREATE_DATE ";
        $strSQL .= ",             UPD_SYA_CD ";
        $strSQL .= ",             UPD_PRG_ID	";
        $strSQL .= ",             UPD_CLT_NM ";

        $strSQL .= " FROM       HKEIJIREPORT KEI ";

        $strSQL .= " WHERE      KEI.CHUMN_NO = '";
        $strSQL .= $strChumnNo;
        $strSQL .= "' ";

        // $this -> log($strSQL,LOG_DEBUG);
        //関数呼び出し元に設定したSQL文字列を返却
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：データ修正SQL
    //関 数 名：fncUPDATEKeijiReport()
    //引    数：$更新データ
    //戻 り 値：SQL文(string)
    //処理説明：データ修正SQL取得します
    //**********************************************************************
    function fncUPDATEKeijiReport($arrayData)
    {
        //修正データSQL文字列を設定
        $strSQL = "";
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDCLTNM = $this->GS_LOGINUSER['strClientNM'];

        $strSQL .= "UPDATE HKEIJIREPORT  ";
        $strSQL .= "  SET TESURYO = '";
        $strSQL .= $arrayData['TESURYO'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   BAN_SIJI_YOT_2 = '";
        $strSQL .= $arrayData['BAN_SIJI_YOT_2'];
        $strSQL .= "'  ";

        //20160616 Update Start
        //$strSQL .= "  ,   BAN_SIJI_HBN_1 = '";
        //$strSQL .= $arrayData['BAN_SIJI_YOT_2'];
        //$strSQL .= "'  ";
        if ($arrayData['BAN_SIJI_HBN_1'] == 0) {
            $strSQL .= ",   BAN_SIJI_HBN_1 = null  ";
        } else {
            $strSQL .= ",   BAN_SIJI_HBN_1 = '";
            $strSQL .= $arrayData['BAN_SIJI_HBN_1'];
            $strSQL .= "' ";
        }
        //20160616 Update End

        $strSQL .= "  ,   KIBO_SRY_BUNRUI = '";
        $strSQL .= $arrayData['KIBO_SRY_BUNRUI'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   KIBO_SRY_KANA = '";
        $strSQL .= $arrayData['KIBO_SRY_KANA'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   KIBO_SRY_KIBO = '";
        $strSQL .= $arrayData['KIBO_SRY_KIBO'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   SRY_BAN_MOJI = '";
        $strSQL .= $arrayData['SRY_BAN_MOJI'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   SRY_BAN_BUNRUI = '";
        $strSQL .= $arrayData['SRY_BAN_BUNRUI'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   SRY_BAN_KANA = '";
        $strSQL .= $arrayData['SRY_BAN_KANA'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   SRY_BAN_SITEI = '";
        $strSQL .= $arrayData['SRY_BAN_SITEI'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   SRY_BAN_SYOUBAN = '";
        $strSQL .= $arrayData['SRY_BAN_SYOUBAN'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   SYADAI_NO = '";
        $strSQL .= $arrayData['SYADAI_NO'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   SYOYU_CD = '";
        $strSQL .= $arrayData['SYOYU_CD'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   SYOYU_SIYO = '";
        $strSQL .= $arrayData['SYOYU_SIYO'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   SHIYOU_NM = '";
        $strSQL .= $arrayData['SHIYOU_NM'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   SHIYOU_ADDR_CD = '";
        $strSQL .= $arrayData['SHIYOU_ADDR_CD'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   SHIYOU_ADDR_1 = '";
        $strSQL .= $arrayData['SHIYOU_ADDR_1'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   SHIYOU_ADDR_2 = '";
        $strSQL .= $arrayData['SHIYOU_ADDR_2'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   SYOYU_NM_SIYO = '";
        $strSQL .= $arrayData['SYOYU_NM_SIYO'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   SYOYU_NM = '";
        $strSQL .= $arrayData['SYOYU_NM'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   SYOYU_ADDR_SIYO = '";
        $strSQL .= $arrayData['SYOYU_ADDR_SIYO'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   SYOYU_ADDR_CD = '";
        $strSQL .= $arrayData['SYOYU_ADDR_CD'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   SYOYU_ADDR_1 = '";
        $strSQL .= $arrayData['SYOYU_ADDR_1'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   SYOYU_ADDR_2 = '";
        $strSQL .= $arrayData['SYOYU_ADDR_2'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   HONKYO_ADDR_SIYO = '";
        $strSQL .= $arrayData['HONKYO_ADDR_SIYO'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   HONKYO_ADDR_CD = '";
        $strSQL .= $arrayData['HONKYO_ADDR_CD'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   HONKYO_ADDR_1 = '";
        $strSQL .= $arrayData['HONKYO_ADDR_1'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   HONKYO_ADDR_2 = '";
        $strSQL .= $arrayData['HONKYO_ADDR_2'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   HONKYO_ADDR_NM = '";
        $strSQL .= $arrayData['HONKYO_ADDR_NM'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   KATASIKI_RUIBETU = '";
        $strSQL .= $arrayData['KATASIKI_RUIBETU'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   IRO_CD = '";
        $strSQL .= $arrayData['IRO_CD'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   SEISAKU_GENGO = '";
        $strSQL .= $arrayData['SEISAKU_GENGO'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   SEISAKU_YMD = '";
        $strSQL .= $arrayData['SEISAKU_YMD'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   SYOMEI_SIJI = '";
        $strSQL .= $arrayData['SYOMEI_SIJI'];
        $strSQL .= "'  ";
        //20170104 Ins Start
        $strSQL .= "  ,   SYOMEI_SIJI2 = '";
        $strSQL .= $arrayData['SYOMEI_SIJI2'];
        $strSQL .= "'  ";
        //20170104 Ins End
        $strSQL .= "  ,   SINSEI_SIYO_NM = '";
        $strSQL .= $arrayData['SINSEI_SIYO_NM'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   SINSEI_SIYO_ADDR = '";
        $strSQL .= $arrayData['SINSEI_SIYO_ADDR'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   SINSEI_SYOYU_NM = '";
        $strSQL .= $arrayData['SINSEI_SYOYU_NM'];
        $strSQL .= "'  ";
        $strSQL .= "  ,   SINSEI_SYOYU_ADDR = '";
        $strSQL .= $arrayData['SINSEI_SYOYU_ADDR'];
        $strSQL .= "'  ";

        if ($arrayData['SYOMEI_SIJI'] != "") {
            $strSQL .= ",   TEIKYO_JIKOU = NULL";
        } else {
            $strSQL .= ",   TEIKYO_JIKOU = '完成検査終了証'";
        }

        $strSQL .= ",   INP_FLG = '1'";
        $strSQL .= ",   UPD_DATE = SYSDATE";
        $strSQL .= ",   UPD_SYA_CD = '";
        $strSQL .= $UPDUSER;
        $strSQL .= "'  ";
        $strSQL .= ",   UPD_PRG_ID = 'FDHokanInput'";
        $strSQL .= ",   UPD_CLT_NM = '";
        $strSQL .= $UPDCLTNM;
        $strSQL .= "'  ";
        $strSQL .= "WHERE CHUMN_NO = '";
        $strSQL .= $arrayData['CHUMNNO'];
        $strSQL .= "'  ";

        //関数呼び出し元に設定したSQL文字列を返却
        return $strSQL;
    }

    public function fncKeijiReportSelect($strChumnNo)
    {
        return parent::select($this->fncKeijiReportSet($strChumnNo));
    }

    public function fncUPDKeijiReport($arrayData)
    {
        return parent::update($this->fncUPDATEKeijiReport($arrayData));
    }

}