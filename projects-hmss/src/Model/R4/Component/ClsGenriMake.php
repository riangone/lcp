<?php
namespace App\Model\R4\Component;

// 共通クラスの読込み
// App::uses('ClsComDb', 'Model/R4');
// App::uses('ClsComFnc', 'Model/R4/Component');
use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFnc;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：ClsGenriMake
// * 処理説明：共通関数
//*************************************

class ClsGenriMake extends ClsComDb
{
    public $ClsComFnc;
    function __construct()
    {
        parent::__construct();
        $this->ClsComFnc = new ClsComFnc();
    }

    //**********************************************************************
    //処 理 名：SQL作成(売上げ存在チェック)
    //関 数 名：fncHscUriExistCheck
    //引    数：strSyoriym，strActmode
    //戻 り 値：SQL文
    //処理説明：SQL作成(売上げ存在チェック)
    //**********************************************************************
    public function fncHscUriExistCheck($strSyoriym, $strActmode = "K")
    {
        $strSQL = "";
        $strSQL .= " SELECT CMN_NO " . "\r\n";
        if ($strActmode == "S") {
            $strSQL .= " FROM   HSCURI_S" . "\r\n";
        } else {
            $strSQL .= " FROM   HSCURI" . "\r\n";
        }

        $strSQL .= " WHERE  KEIJYO_YM = '@KEIJYOBI'" . "\r\n";
        $strSQL = str_replace("@KEIJYOBI", $strSyoriym, $strSQL);

        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：SQL作成(限界利益データ削除)
    //関 数 名：fncDeleteGenri
    //引    数：strSyoriym，strActmode
    //戻 り 値：SQL文
    //処理説明：SQL作成(限界利益データ削除)
    //**********************************************************************
    public function fncDeleteGenri($strSyoriym, $strActmode = "K")
    {
        $strSQL = "";
        if ($strActmode == "S") {
            $strSQL .= " DELETE FROM HGENRI_S" . "\r\n";
        } else {
            $strSQL .= " DELETE FROM HGENRI" . "\r\n";
        }
        $strSQL .= " WHERE  NENGETU = '@KEIJYOBI'" . "\r\n";
        $strSQL = str_replace("@KEIJYOBI", $strSyoriym, $strSQL);

        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：SQL作成(追加限界利益データ追加項目(Values前まで))
    //関 数 名：fncInsertHGENRICreateSQL
    //引    数：strActmode
    //戻 り 値：SQL文
    //処理説明：SQL作成(追加限界利益データ追加項目(Values前まで))
    //**********************************************************************
    public function fncInsertHGENRICreateSQL($strActmode = "K")
    {
        $strSQL = "";
        if ($strActmode == "S") {
            $strSQL .= "INSERT INTO HGENRI_S" . "\r\n";
        } else {
            $strSQL .= "INSERT INTO HGENRI" . "\r\n";
        }

        $strSQL .= "(           NENGETU" . "\r\n";
        $strSQL .= ",           DATA_KB" . "\r\n";
        $strSQL .= ",           KUKURI_BUSYO" . "\r\n";
        $strSQL .= ",           ATUKAI_BUSYO" . "\r\n";
        $strSQL .= ",           ATUKAI_SYAIN" . "\r\n";
        $strSQL .= ",           ATUKAI_GYOSYA" . "\r\n";
        $strSQL .= ",           UC_NO" . "\r\n";
        $strSQL .= ",           CARNO" . "\r\n";
        $strSQL .= ",           NENSIKI" . "\r\n";
        $strSQL .= ",           SYADAIKATA" . "\r\n";
        $strSQL .= ",           MEIGININ" . "\r\n";
        $strSQL .= ",           DAISU" . "\r\n";
        $strSQL .= ",           TOU_DAISU" . "\r\n";
        $strSQL .= ",           SIT_DAISU" . "\r\n";
        $strSQL .= ",           CHU_KB" . "\r\n";
        $strSQL .= ",           HNB_KB" . "\r\n";
        $strSQL .= ",           KUKURI_CD" . "\r\n";
        $strSQL .= ",           CKO_CGY" . "\r\n";
        $strSQL .= ",           CKO_HNB_KB" . "\r\n";
        $strSQL .= ",           CKO_SYA_KB" . "\r\n";
        $strSQL .= ",           CKO_SHIIRE_KB" . "\r\n";
        $strSQL .= ",           CKO_SEIBI_KB" . "\r\n";
        $strSQL .= ",           CKO_GYO_MEI" . "\r\n";
        $strSQL .= ",           CKO_MEI_HEN_FLG" . "\r\n";
        $strSQL .= ",           KAIYAKU_YMD" . "\r\n";
        $strSQL .= ",           SYARYOU_KIN" . "\r\n";
        $strSQL .= ",           SYARYOU_NEBIKI" . "\r\n";
        $strSQL .= ",           SYARYOU_CHU_PCS" . "\r\n";
        $strSQL .= ",           SYARYOU_KTN_PCS" . "\r\n";
        $strSQL .= ",           SYARYOU_S_BETU_PCS" . "\r\n";
        $strSQL .= ",           TENPU_TEIKA" . "\r\n";
        $strSQL .= ",           TENPU_KEIYAKU" . "\r\n";
        $strSQL .= ",           TENPU_GENKA" . "\r\n";
        $strSQL .= ",           TOKUBETU_TEIKA" . "\r\n";
        $strSQL .= ",           TOKUBETU_KEIYAKU" . "\r\n";
        $strSQL .= ",           TOKUBETU_GENKA" . "\r\n";
        $strSQL .= ",           KAPPU_TES_KYK" . "\r\n";
        $strSQL .= ",           KAPPU_TES_KJN" . "\r\n";
        $strSQL .= ",           TOU_SYH_KYK" . "\r\n";
        $strSQL .= ",           TOU_SYH_KJN" . "\r\n";
        $strSQL .= ",           HOUTEIH_GK" . "\r\n";
        $strSQL .= ",           ZEI_HKN_GK" . "\r\n";
        $strSQL .= ",           ZANSAI" . "\r\n";
        $strSQL .= ",           SHR_GK_SUM" . "\r\n";
        $strSQL .= ",           SHR_JKN_SIT_KIN" . "\r\n";
        $strSQL .= ",           SHR_JKN_ATM_KIN" . "\r\n";
        $strSQL .= ",           SHR_JKN_TRK_SYH" . "\r\n";
        $strSQL .= ",           SHR_JKN_CKO_FTK" . "\r\n";
        $strSQL .= ",           SHR_JKN_TGT_KAI" . "\r\n";
        $strSQL .= ",           SHR_JKN_TGT_KIN" . "\r\n";
        $strSQL .= ",           SHR_JKN_KRJ_KAI" . "\r\n";
        $strSQL .= ",           SHR_JKN_KRJ_KIN" . "\r\n";
        $strSQL .= ",           SIT_KTR_KIN" . "\r\n";
        $strSQL .= ",           SIT_SATEI_KIN" . "\r\n";
        $strSQL .= ",           JIDOUSYA_ZEI" . "\r\n";
        $strSQL .= ",           SYARYOU_ZEI" . "\r\n";
        $strSQL .= ",           EAKON_ZEI" . "\r\n";
        $strSQL .= ",           SUTEREO_ZEI" . "\r\n";
        $strSQL .= ",           JYURYO_ZEI" . "\r\n";
        $strSQL .= ",           SHZ_KEI" . "\r\n";
        $strSQL .= ",           JIBAI_HOK_RYO" . "\r\n";
        $strSQL .= ",           OPTHOK_RYO" . "\r\n";
        $strSQL .= ",           HNB_TES_RYO_KZI_KBN" . "\r\n";
        $strSQL .= ",           HNB_SHZ" . "\r\n";
        $strSQL .= ",           HONBU_FTK" . "\r\n";
        $strSQL .= ",           UKM_SNY_TES" . "\r\n";
        $strSQL .= ",           UKM_SINSEI_SYR" . "\r\n";
        $strSQL .= ",           KAP_TES_SGK" . "\r\n";
        $strSQL .= ",           ETC_SKI_RYO" . "\r\n";
        $strSQL .= ",           SRY_GENKAI_RIE" . "\r\n";
        $strSQL .= ",           GNK_HJN_PCS" . "\r\n";
        $strSQL .= ",           GNK_SIT_URI_SKI" . "\r\n";
        $strSQL .= ",           CKO_BAI_SIT_KIN" . "\r\n";
        $strSQL .= ",           CKO_BAI_SATEI" . "\r\n";
        $strSQL .= ",           CKO_SAI_MITUMORI" . "\r\n";
        $strSQL .= ",           CKO_SYOGAKARI" . "\r\n";
        $strSQL .= ",           CKO_MIKEI_JDO_KIN" . "\r\n";
        $strSQL .= ",           CKO_MIKEI_JIBAI_KIN" . "\r\n";
        //strSQL.Append(",           TKB_KSH_PCS_TEI" & vbCrLf)
        $strSQL .= ",           GYOUSYA_NM" . "\r\n";
        $strSQL .= ",           KEIYAKUTEN" . "\r\n";
        $strSQL .= ",           TOUROKUTEN" . "\r\n";
        $strSQL .= ",           TOK_KEI_KHN_MGN" . "\r\n";
        $strSQL .= ",           TOK_KEI_RUI_MGN" . "\r\n";
        $strSQL .= ",           TOK_KEI_KHN_SYR" . "\r\n";
        $strSQL .= ",           KB" . "\r\n";
        $strSQL .= ",           NEBIKI_RT" . "\r\n";
        $strSQL .= ",           KIJUN_NEBIKI_RT" . "\r\n";
        $strSQL .= ",           UPD_DATE" . "\r\n";
        //2006/08/23 UPDATE START
        //strSQL.Append(",           CREATE_DATE)" & vbCrLf)
        $strSQL .= ",           CREATE_DATE" . "\r\n";
        $strSQL .= ",           RCY_YOT_KIN" . "\r\n";
        $strSQL .= ",           RCY_SKN_KAN_HI" . "\r\n";
        $strSQL .= ",           KICK_BACK" . "\r\n";
        $strSQL .= ",           TOU_SYH_KJN_GK" . "\r\n";
        $strSQL .= ",           SYUEKI_SYOKEI" . "\r\n";
        $strSQL .= ",           HOUTEIH_SIT" . "\r\n";
        $strSQL .= ",           TENPU_NEBIKI" . "\r\n";
        $strSQL .= ",           TOKUBETU_NEBIKI" . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",           UPD_SYA_CD" . "\r\n";
        $strSQL .= ",           UPD_PRG_ID" . "\r\n";
        $strSQL .= ",           UPD_CLT_NM" . "\r\n";
        //2006/12/08 UPD End
        //2009/12/21 INS Start   R4連携集計システムのために追加
        $strSQL .= ",           TRA_CAR_RCYYTK_SUM" . "\r\n";
        $strSQL .= ",           KAP_GKN" . "\r\n";
        $strSQL .= ",           TOU_SYH_KEN" . "\r\n";
        $strSQL .= ",           TOU_SYH_SYAKEN" . "\r\n";
        $strSQL .= ",           TOU_SYH_SYAKO_SYO" . "\r\n";
        $strSQL .= ",           TOU_SYH_NOUSYA" . "\r\n";
        $strSQL .= ",           TOU_SYH_SIT_TTK" . "\r\n";
        $strSQL .= ",           TOU_SYH_SATEI" . "\r\n";
        $strSQL .= ",           TOU_SYH_JIKOU" . "\r\n";
        $strSQL .= ",           TOU_SYH_ETC" . "\r\n";
        $strSQL .= ",           HOUTEIH_KEN" . "\r\n";
        $strSQL .= ",           HOUTEIH_SYAKEN" . "\r\n";
        $strSQL .= ",           HOUTEIH_SYAKO_SYO" . "\r\n";
        $strSQL .= ",           PENALTY" . "\r\n";
        $strSQL .= ",           EGO_GAI_SYUEKI" . "\r\n";
        $strSQL .= ",           SAI_SONEKI" . "\r\n";
        $strSQL .= ",           JAF" . "\r\n";
        $strSQL .= ",           PACK_DE_753" . "\r\n";
        $strSQL .= ",           PACK_DE_MENTE" . "\r\n";
        $strSQL .= "      ,KYK_CSRRANK" . "\r\n";
        $strSQL .= "      ,SIY_CSRRANK" . "\r\n";
        //''strSQL.Append("      ,UC_KENSU_FLG" & vbCrLf)
        //''strSQL.Append("      ,MI_JISSEKI_FLG" & vbCrLf)
        //''strSQL.Append("      ,TOU_JISSEKI_FLG" & vbCrLf)
        //''strSQL.Append("      ,TA_KYK_JI_TRK_FLG" & vbCrLf)
        //''strSQL.Append("      ,JI_KYK_TA_TRK_FLG" & vbCrLf)
        //''strSQL.Append("      ,MAKER_FLG" & vbCrLf)
        //''strSQL.Append("      ,FUKUSHI_FLG" & vbCrLf)
        //''strSQL.Append("      ,SYAMEI_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI_JISSEKI_FLG" & vbCrLf)
        //''strSQL.Append("      ,LEASE_FLG" & vbCrLf)
        //''strSQL.Append("      ,SEAVICE_CAR_FLG" & vbCrLf)
        //''strSQL.Append("      ,SAIBAI_FLG" & vbCrLf)
        //''strSQL.Append("      ,KARUTE_FLG" & vbCrLf)
        //''strSQL.Append("      ,TRKKB_URI_FLG" & vbCrLf)
        //''strSQL.Append("      ,TRKKB_TOU_FLG" & vbCrLf)
        //''strSQL.Append("      ,TRKKB_SONTA_FLG" & vbCrLf)
        $strSQL .= "      ,UC_KENSU" . "\r\n";
        $strSQL .= "      ,MI_JISSEKI_DAISU" . "\r\n";
        $strSQL .= "      ,TOU_JISSEKI_DAISU" . "\r\n";
        $strSQL .= "      ,TA_KYK_JI_TRK_DAISU" . "\r\n";
        $strSQL .= "      ,JI_KYK_TA_TRK_DAISU" . "\r\n";
        $strSQL .= "      ,MAKER_DAISU" . "\r\n";
        $strSQL .= "      ,FUKUSHI_DAISU" . "\r\n";
        $strSQL .= "      ,SYAMEI_DAISU" . "\r\n";
        $strSQL .= "      ,URI_JISSEKI_DAISU" . "\r\n";
        $strSQL .= "      ,LEASE_DAISU" . "\r\n";
        $strSQL .= "      ,SEAVICE_CAR_DAISU" . "\r\n";
        $strSQL .= "      ,SAIBAI_DAISU" . "\r\n";
        $strSQL .= "      ,KARUTE_DAISU" . "\r\n";
        $strSQL .= "      ,TRKKB_URI_DAISU" . "\r\n";
        $strSQL .= "      ,TRKKB_TOU_DAISU" . "\r\n";
        $strSQL .= "      ,TRKKB_SONTA_DAISU" . "\r\n";
        $strSQL .= "      ,KAIYAKU_DAISU" . "\r\n";
        $strSQL .= "      ,CMN_NO" . "\r\n";
        //2009/12/21 INS end

        //2009/12/11 INS End
        $strSQL .= ")" . "\r\n";
        //2006/08/23 UPDATE END

        return $strSQL;
    }

    public function fncInsertNoExist($strSyoriYM, $strUpdApp, $strActmode = "K")
    {
        $strUpdUser = $this->GS_LOGINUSER['strUserID'];
        $strUpdCltNM = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";

        $strSQL .= $this->fncInsertHGENRICreateSQL($strActmode);

        $strSQL .= "SELECT KEIJYO_YM" . "\r\n";
        //年月
        $strSQL .= ",      (CASE WHEN NAU_KB = '1'" . "\r\n";
        $strSQL .= "             THEN (CASE WHEN SUBSTR(UC_NO,10,1) IN ('Z','P','Q','R','S','T','U','V','W')" . "\r\n";
        $strSQL .= "                        THEN '3' ELSE '1' END)" . "\r\n";
        $strSQL .= "             ELSE '2' END)" . "\r\n";
        //データ区分
        $strSQL .= ",      (CASE WHEN NAU_KB = '2' AND CKO_HNB_KB = '5'" . "\r\n";
        $strSQL .= "             THEN '20' ELSE BUY.KKR_BUSYO_CD END) KKR_BUSYO_CD " . "\r\n";
        $strSQL .= ",      (CASE WHEN NVL(URI_BUSYO_CD,'') = '168' THEN NVL(URK_BUSYO_CD,'') ELSE NVL(URI_BUSYO_CD,'') END)" . "\r\n";
        //扱い部署
        $strSQL .= ",      NVL(URI_TANNO,'')	" . "\r\n";
        //扱い社員
        $strSQL .= ",      NVL(URI_GYOSYA,'')" . "\r\n";
        //扱い業者
        $strSQL .= ",      NVL(UC_NO,' ')" . "\r\n";
        //UCNO
        $strSQL .= ",      CARNO" . "\r\n";
        //CARNO
        $strSQL .= ",      NENSIKI" . "\r\n";
        //年式
        $strSQL .= ",      (CASE WHEN NAU_KB = '1' THEN TOA_NAME ELSE SYANAI_KOSYO END)" . "\r\n";
        //型式
        $strSQL .= ",      SUBSTRB((CASE WHEN NAU_KB = '1' THEN MGN_MEI_KNJ1 ELSE KYK_MEI_KNJ1 END),1,40)" . "\r\n";
        //名義人
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,URI_DAISU,0)" . "\r\n";
        //売上台数
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,TOU_DAISU,0)" . "\r\n";
        //登録台数
        if ($strActmode == "S") {
            $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL((SELECT COUNT(CMN_NO) FROM HSCSIT_S_VW SIT WHERE SIT.CMN_NO = URI.CMN_NO AND SIT.KEIJYO_YM = URI.KEIJYO_YM),0),0)" . "\r\n";
            //下取台数
        } else {
            $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL((SELECT COUNT(CMN_NO) FROM HSCSIT_VW SIT WHERE SIT.CMN_NO = URI.CMN_NO AND SIT.KEIJYO_YM = URI.KEIJYO_YM),0),0)" . "\r\n";
            //下取台数
        }

        $strSQL .= ",      CHUMON_KB	" . "\r\n";
        //注文書区分
        $strSQL .= ",      HNB_KB" . "\r\n";
        //販売形態
        $strSQL .= ",      KKR_CD" . "\r\n";
        //括りコード
        $strSQL .= ",      CKG_KB" . "\r\n";
        //中古車直業
        $strSQL .= ",      CKO_HNB_KB" . "\r\n";
        //中古車販売区分
        $strSQL .= ",      CKO_SS_KB" . "\r\n";
        //中古車車種区分
        $strSQL .= ",      CKO_SIR_KB" . "\r\n";
        //中古車仕入区分
        $strSQL .= ",      CKO_SEB_KB" . "\r\n";
        //中古車整備区分
        $strSQL .= ",      CKO_MEG_KB" . "\r\n";
        //中古車業売名義
        $strSQL .= ",      CKO_MHN_KB" . "\r\n";
        //中古車名変FLG
        $strSQL .= ",      CEL_DATE" . "\r\n";
        //解約年月日
        //strSQL.Append(",      SRY_PRC" & vbCrLf)            '車両価格
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,(CASE WHEN NAU_KB = '1' AND KYK_HNS='17349'" . "\r\n";
        //車両価格
        $strSQL .= "             THEN (SRY_PRC-SRY_CMN_PCS) ELSE SRY_PRC END),0) SRY_PRC" . "\r\n";
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,SRY_NBK,0)" . "\r\n";
        //車両値引
        //strSQL.Append(",      SRY_CMN_PCS" & vbCrLf)       '車両注文書原価
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,(CASE WHEN NAU_KB = '1' AND KYK_HNS='17349'" . "\r\n";
        //車両注文書原価
        $strSQL .= "             THEN 0 ELSE SRY_CMN_PCS END),0) SRY_CMN_PCS" . "\r\n";
        //strSQL.Append(",      SRY_KTN_PCS" & vbCrLf)        '車両拠点原価
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,(CASE WHEN NAU_KB = '1' AND KYK_HNS='17349'" . "\r\n";
        //車両拠点原価
        $strSQL .= "             THEN 0 ELSE SRY_KTN_PCS END),0) SRY_KTN_PCS" . "\r\n";
        //strSQL.Append(",      SRY_BUY_PCS" & vbCrLf)        '車両新車車両部署別用原価
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,(CASE WHEN NAU_KB = '1' AND KYK_HNS='17349'" . "\r\n";
        //車両新車車両部署別用原価
        $strSQL .= "             THEN 0 ELSE SRY_BUY_PCS END),0) SRY_BUY_PCS" . "\r\n";
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,FHZ_TEIKA,0)" . "\r\n";
        //添付品定価
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,FHZ_KYK,0)" . "\r\n";
        //添付品契約
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,FHZ_PCS,0)" . "\r\n";
        //添付品原価
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,TKB_KSH_TEIKA,0)" . "\r\n";
        //特別仕様定価
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,TKB_KSH_KYK,0)" . "\r\n";
        //特別仕様契約
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,TKB_KSH_PCS,0)" . "\r\n";
        //特別仕様原価
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,KAP_TES_KYK,0)" . "\r\n";
        //割賦手数料契約
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,KAP_TES_KJN,0)" . "\r\n";
        //割賦手数料基準
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,TOU_SYH_KYK,0)" . "\r\n";
        //登録諸費用3%契約
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,TOU_SYH_KJN,0)" . "\r\n";
        //登録諸費用3%基準
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL, HOUTEIH_GK,0)" . "\r\n";
        //預かり法定費用
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,HKN_GK,0)" . "\r\n";
        //税金保険料
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,TRA_CAR_ZSI_SUM,0)" . "\r\n";
        //残債
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,SHR_GK_SUM,0)" . "\r\n";
        //支払金合計
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,SHR_JKN_SIT_KIN,0)" . "\r\n";
        //支払条件下取価格
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,SHR_JKN_ATM_KIN,0)" . "\r\n";
        //支払条件頭金
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,SHR_JKN_TRK_SYH,0)" . "\r\n";
        //支払条件登録諸費用
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,SHR_JKN_CKO_FTK,0)" . "\r\n";
        //支払条件中古車負担金
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,SHR_JKN_TGT_KAI,0)" . "\r\n";
        //支払条件手形回数
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,SHR_JKN_TGT_KIN,0)" . "\r\n";
        //支払条件手形金額
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,SHR_JKN_KRJ_KAI,0)" . "\r\n";
        //支払条件クレジット回数
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,SHR_JKN_KRJ_KIN,0)" . "\r\n";
        //支払条件クレジット金額
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,TRA_CAR_PRC_SUM,0)" . "\r\n";
        //下取車買取価格
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,TRA_CAR_STI_SUM,0)" . "\r\n";
        //下取車査定価格
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,JIDOUSYA_ZEI,0)" . "\r\n";
        //税金自動車税
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,SYARYOU_ZEI,0)" . "\r\n";
        //税金車両税
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,EAKON_ZEI,0)" . "\r\n";
        //税金エアコン税
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,SUTEREO_ZEI,0)" . "\r\n";
        //税金ステレオ税
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,JYURYO_ZEI,0)" . "\r\n";
        //税金重量税
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,SHZ_KEI,0)" . "\r\n";
        //税金消費税
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,JIBAI_HOK_RYO,0)" . "\r\n";
        //自賠責保険料
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,OPTHOK_RYO,0)" . "\r\n";
        //任意保険料
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,HNB_TES_GKU,0)" . "\r\n";
        //販売手数料額
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,HNB_SHZ,0)" . "\r\n";
        //販売消費税
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,HONBU_FTK,0)" . "\r\n";
        //本部負担金
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,UKM_SNY_TES,0)" . "\r\n";
        //打込金収入手数料
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,UKM_SINSEI_SYR,0)" . "\r\n";
        //打込金申請奨励金
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,KAP_TES_SGK,0)" . "\r\n";
        //割賦手数料差額
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL, ETC_SKI_RYO,0)" . "\r\n";
        //その他紹介料
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,SRY_GENKAI_RIE,0)" . "\r\n";
        //車両F号限界利益
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,GNK_HJN_PCS,0)" . "\r\n";
        //原価標準原価
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,GNK_SIT_URI_SKI,0)" . "\r\n";
        //原価下取車売上仕切
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,CKO_BAI_SIT_KIN,0)" . "\r\n";
        //中古車売車下取価格
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,CKO_BAI_SATEI,0)" . "\r\n";
        //中古車売車査定価格
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,CKO_SAI_MITUMORI,0)" . "\r\n";
        //中古車再生見積価格
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,CKO_SYOGAKARI,0)" . "\r\n";
        //中古車諸掛
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,CKO_MIKEI_JDO_KIN,0)" . "\r\n";
        //中古車未経過自動車税金額
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,CKO_MIKEI_JIBAI_KIN,0)" . "\r\n";
        //中古車未経過自賠責金額
        //strSQL.Append(",     URI_GYOSYA" & vbCrLf)              '業者名
        $strSQL .= ",     GYO_NAME  " . "\r\n";
        //業者名
        $strSQL .= ",     KYK_HNS" . "\r\n";
        //契約店
        $strSQL .= ",     TOU_HNS" . "\r\n";
        //登録店
        $strSQL .= ",     TOK_KEI_KHN_MGN" . "\r\n";
        //特約店契約基本マージン
        $strSQL .= ",     TOK_KEI_RUI_MGN" . "\r\n";
        //特約店契約累積マージン
        $strSQL .= ",     TOK_KEI_KHN_SYR" . "\r\n";
        //特約店契約拡販奨励金
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,(CASE WHEN NVL(CHUMON_KB,' ') = 'A' THEN KICK_BACK ELSE 0 END),0)" . "\r\n";
        //ｷｯｸバック
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,(CASE WHEN NVL(CHUMON_KB,' ') = 'A' THEN NEBIKI_RT ELSE 0 END),0)" . "\r\n";
        //値引率
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,(CASE WHEN NVL(CHUMON_KB,' ') = 'A' THEN KIJUN_NEBIKI_RT ELSE 0 END),0)" . "\r\n";
        //基準値引率
        $strSQL .= ",     URI.UPD_DATE" . "\r\n";
        $strSQL .= ",     URI.CREATE_DATE" . "\r\n";
        //2006/08/23 UPDATE START
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,RCY_YOT_KIN,0)" . "\r\n";
        //リサイクル預託金
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,RCY_SKN_KAN_HI,0)" . "\r\n";
        //リサイクル資金管理費
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,KICK_BACK,0)" . "\r\n";
        //キックバック
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,TOU_SYH_KJN_GK,0)" . "\r\n";
        //登録諸費用基準
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,SYUEKI_SYOKEI,0)" . "\r\n";
        //収益小計
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,HOUTEIH_SIT,0)" . "\r\n";
        //法定費下取り
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,FHZ_NBK,0)" . "\r\n";
        //付属品値引
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,TKB_KSH_NBK,0)" . "\r\n";
        //特別架装品値引
        //2006/08/23 UPDATE END
        //TODO 2006/12/08 UPDATE STart
        $strSQL .= " , " . $this->ClsComFnc->FncSqlNv($strUpdUser);
        //ユーザID
        $strSQL .= " , " . $this->ClsComFnc->FncSqlNv($strUpdApp);
        //アプリケーション
        $strSQL .= " , " . $this->ClsComFnc->FncSqlNv($strUpdCltNM);
        //マシン名
        //2006/12/08 UPDATE End
        //2009/12/21 INS Start R4連携集計システムのために追加    //解約日

        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,URI.TRA_CAR_RCYYTK_SUM,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,URI.KAP_GKN,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,URI.TOU_SYH_KEN,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,URI.TOU_SYH_SYAKEN,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,URI.TOU_SYH_SYAKO_SYO,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,URI.TOU_SYH_NOUSYA,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,URI.TOU_SYH_SIT_TTK,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,URI.TOU_SYH_SATEI,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,URI.TOU_SYH_JIKOU,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,URI.TOU_SYH_ETC,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,URI.HOUTEIH_KEN,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,URI.HOUTEIH_SYAKEN,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,URI.HOUTEIH_SYAKO_SYO,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,URI.PENALTY,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,URI.EGO_GAI_SYUEKI,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,URI.SAI_SONEKI,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,URI.JAF,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,URI.PACK_DE_753,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,URI.PACK_DE_MENTE,0)" . "\r\n";

        $strSQL .= "      ,URI.KYK_CSRRANK" . "\r\n";
        $strSQL .= "      ,URI.SIY_CSRRANK" . "\r\n";

        //''strSQL.Append("      ,URI.UC_KENSU_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.MI_JISSEKI_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.TOU_JISSEKI_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.TA_KYK_JI_TRK_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.JI_KYK_TA_TRK_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.MAKER_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.FUKUSHI_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.SYAMEI_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.URI_JISSEKI_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.LEASE_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.SEAVICE_CAR_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.SAIBAI_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.KARUTE_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.TRKKB_URI_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.TRKKB_TOU_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.TRKKB_SONTA_FLG" & vbCrLf)
        $strSQL .= "      ,URI.UC_KENSU" . "\r\n";
        $strSQL .= "      ,URI.MI_JISSEKI_DAISU" . "\r\n";
        $strSQL .= "      ,URI.TOU_JISSEKI_DAISU" . "\r\n";
        $strSQL .= "      ,URI.TA_KYK_JI_TRK_DAISU" . "\r\n";
        $strSQL .= "      ,URI.JI_KYK_TA_TRK_DAISU" . "\r\n";
        $strSQL .= "      ,URI.MAKER_DAISU" . "\r\n";
        $strSQL .= "      ,URI.FUKUSHI_DAISU" . "\r\n";
        $strSQL .= "      ,URI.SYAMEI_DAISU" . "\r\n";
        $strSQL .= "      ,URI.URI_JISSEKI_DAISU" . "\r\n";
        $strSQL .= "      ,URI.LEASE_DAISU" . "\r\n";
        $strSQL .= "      ,URI.SEAVICE_CAR_DAISU" . "\r\n";
        $strSQL .= "      ,URI.SAIBAI_DAISU" . "\r\n";
        $strSQL .= "      ,URI.KARUTE_DAISU" . "\r\n";
        $strSQL .= "      ,URI.TRKKB_URI_DAISU" . "\r\n";
        $strSQL .= "      ,URI.TRKKB_TOU_DAISU" . "\r\n";
        $strSQL .= "      ,URI.TRKKB_SONTA_DAISU" . "\r\n";
        $strSQL .= "      ,URI.KAIYAKU_DAISU" . "\r\n";
        $strSQL .= "      ,URI.CMN_NO" . "\r\n";
        //2009/12/21 INS End
        if ($strActmode == "S") {
            //速報の場合
            $strSQL .= "FROM  HSCURI_S_VW URI" . "\r\n";
        } else {
            $strSQL .= "FROM  HSCURI_VW URI" . "\r\n";
        }
        $strSQL .= "     ,HBUSYO BUY" . "\r\n";

        $strSQL .= " WHERE      NOT EXISTS" . "\r\n";
        $strSQL .= "            (SELECT *" . "\r\n";
        if ($strActmode == "S") {
            $strSQL .= "             FROM   HJYOUHEN_S JYO" . "\r\n";
        } else {
            $strSQL .= "             FROM   HJYOUHEN JYO" . "\r\n";
        }
        $strSQL .= "             WHERE  KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSQL .= "               AND  URI.CMN_NO = JYO.CMN_NO)" . "\r\n";
        $strSQL .= " AND        DECODE(URI.URI_BUSYO_CD,'168',URI.URK_BUSYO_CD,URI.URI_BUSYO_CD) = BUY.BUSYO_CD" . "\r\n";
        $strSQL .= " AND        URI.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL = str_replace("@KEIJYOBI", $strSyoriYM, $strSQL);
        return $strSQL;
    }

    public function fncInsertAkaJyohen($strSyoriYM, $strUpdApp, $strActmode = "K")
    {
        $strUpdUser = $this->GS_LOGINUSER['strUserID'];
        $strUpdCltNM = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";

        // $strWhereSQL = "";
        $strSelectSQL = "";

        $strSQL .= $this->fncInsertHGENRICreateSQL($strActmode);

        $strSelectSQL .= "SELECT JYO.KEIJYO_YM" . "\r\n";
        //年月
        $strSelectSQL .= ",      (CASE WHEN JYO.NAU_KB = '1'" . "\r\n";
        $strSelectSQL .= "             THEN (CASE WHEN SUBSTR(JYO.UC_NO,10,1) IN ('Z','P','Q','R','S','T','U','V','W')" . "\r\n";
        $strSelectSQL .= "                        THEN '3' ELSE '1' END)" . "\r\n";
        $strSelectSQL .= "             ELSE '2' END)" . "\r\n";
        //データ区分
        //strSelectSQL.Append(",      BUY.KKR_BUSYO_CD" & vbCrLf)                '括り部署
        $strSelectSQL .= ",      (CASE WHEN JYO.NAU_KB = '2' AND JYO.CKO_HNB_KB = '5'" . "\r\n";
        $strSelectSQL .= "             THEN '20' ELSE BUY.KKR_BUSYO_CD END) KKR_BUSYO_CD " . "\r\n";
        $strSelectSQL .= ",      (CASE WHEN NVL(JYO.URI_BUSYO_CD,' ') = '168' THEN NVL(JYO.URK_BUSYO_CD,' ') ELSE NVL(JYO.URI_BUSYO_CD,' ') END)" . "\r\n";
        //扱い部署
        $strSelectSQL .= ",      NVL(JYO.URI_TANNO,' ')	" . "\r\n";
        //扱い社員
        $strSelectSQL .= ",      NVL(JYO.URI_GYOSYA,' ')" . "\r\n";
        //扱い業者
        $strSelectSQL .= ",      NVL(JYO.UC_NO,' ')" . "\r\n";
        //UCNO
        $strSelectSQL .= ",      JYO.CARNO" . "\r\n";
        //CARNO
        $strSelectSQL .= ",      JYO.NENSIKI" . "\r\n";
        //年式
        $strSelectSQL .= ",      (CASE WHEN JYO.NAU_KB = '1' THEN JYO.TOA_NAME ELSE JYO.SYANAI_KOSYO END)" . "\r\n";
        //型式
        $strSelectSQL .= ",      SUBSTRB((CASE WHEN JYO.NAU_KB = '1' THEN JYO.MGN_MEI_KNJ1 ELSE JYO.KYK_MEI_KNJ1 END),1,40)" . "\r\n";
        //名義人
        $strSelectSQL .= ",      JYO.URI_DAISU*-1" . "\r\n";
        //売上台数
        $strSelectSQL .= ",      JYO.TOU_DAISU*-1" . "\r\n";
        //登録台数
        if ($strActmode == "S") {
            $strSelectSQL .= ",      NVL((SELECT COUNT(CMN_NO) FROM HJYOUHENSIT_S SIT WHERE SIT.CMN_NO = URI.CMN_NO AND SIT.KEIJYO_YM = URI.KEIJYO_YM),0)*-1" . "\r\n";
            //下取台数
        } else {
            $strSelectSQL .= ",      NVL((SELECT COUNT(CMN_NO) FROM HJYOUHENSIT SIT WHERE SIT.CMN_NO = URI.CMN_NO AND SIT.KEIJYO_YM = URI.KEIJYO_YM),0)*-1" . "\r\n";
            //下取台数
        }

        $strSelectSQL .= ",      JYO.CHUMON_KB	" . "\r\n";
        //注文書区分
        $strSelectSQL .= ",      JYO.HNB_KB" . "\r\n";
        //販売形態
        $strSelectSQL .= ",      JYO.KKR_CD" . "\r\n";
        //括りコード
        $strSelectSQL .= ",      JYO.CKG_KB" . "\r\n";
        //中古車直業
        $strSelectSQL .= ",      JYO.CKO_HNB_KB" . "\r\n";
        //中古車販売区分
        $strSelectSQL .= ",      JYO.CKO_SS_KB" . "\r\n";
        //中古車車種区分
        $strSelectSQL .= ",      JYO.CKO_SIR_KB" . "\r\n";
        //中古車仕入区分
        $strSelectSQL .= ",      JYO.CKO_SEB_KB" . "\r\n";
        //中古車整備区分
        $strSelectSQL .= ",      JYO.CKO_MEG_KB" . "\r\n";
        //中古車業売名義
        $strSelectSQL .= ",      JYO.CKO_MHN_KB" . "\r\n";
        //中古車名変FLG
        $strSelectSQL .= ",      JYO.CEL_DATE" . "\r\n";
        //解約年月日
        //strSelectSQL.Append(",      NVL(JYO.SRY_PRC,0) * -1" & vbCrLf)            '車両価格
        $strSelectSQL .= ",      (CASE WHEN JYO.NAU_KB = '1' AND JYO.KYK_HNS='17349'" . "\r\n";
        //車両価格
        $strSelectSQL .= "             THEN (NVL(JYO.SRY_PRC,0)-NVL(JYO.SRY_CMN_PCS,0))* -1 ELSE JYO.SRY_PRC END) SRY_PRC" . "\r\n";
        $strSelectSQL .= ",      NVL(JYO.SRY_NBK,0) * -1" . "\r\n";
        //車両値引
        //strSelectSQL.Append(",      NVL(JYO.SRY_CMN_PCS,0) * -1" & vbCrLf)        '車両注文書原価
        $strSelectSQL .= ",      (CASE WHEN JYO.NAU_KB = '1' AND JYO.KYK_HNS='17349'" . "\r\n";
        //車両注文書原価
        $strSelectSQL .= "             THEN 0 ELSE NVL(JYO.SRY_CMN_PCS,0) * -1 END) SRY_CMN_PCS" . "\r\n";
        //strSelectSQL.Append(",      NVL(JYO.SRY_KTN_PCS,0) * -1" & vbCrLf)        '車両拠点原価
        $strSelectSQL .= ",      (CASE WHEN JYO.NAU_KB = '1' AND JYO.KYK_HNS='17349'" . "\r\n";
        //車両拠点原価
        $strSelectSQL .= "             THEN 0 ELSE NVL(JYO.SRY_KTN_PCS,0) * -1 END) SRY_KTN_PCS" . "\r\n";
        //strSelectSQL.Append(",      NVL(JYO.SRY_BUY_PCS,0) * -1" & vbCrLf)        '車両新車車両部署別用原価
        $strSelectSQL .= ",      (CASE WHEN JYO.NAU_KB = '1' AND JYO.KYK_HNS='17349'" . "\r\n";
        //車両新車車両部署別用原価
        $strSelectSQL .= "             THEN 0 ELSE  NVL(JYO.SRY_BUY_PCS,0) * -1 END) SRY_BUY_PCS" . "\r\n";
        $strSelectSQL .= ",      NVL(JYO.FHZ_TEIKA,0) * -1" . "\r\n";
        //添付品定価
        $strSelectSQL .= ",      NVL(JYO.FHZ_KYK,0) * -1" . "\r\n";
        //添付品契約
        $strSelectSQL .= ",      NVL(JYO.FHZ_PCS,0) * -1" . "\r\n";
        //添付品原価
        $strSelectSQL .= ",      NVL(JYO.TKB_KSH_TEIKA,0) * -1" . "\r\n";
        //特別仕様定価
        $strSelectSQL .= ",      NVL(JYO.TKB_KSH_KYK,0) * -1" . "\r\n";
        //特別仕様契約
        $strSelectSQL .= ",      NVL(JYO.TKB_KSH_PCS,0) * -1" . "\r\n";
        //特別仕様原価
        $strSelectSQL .= ",      NVL(JYO.KAP_TES_KYK,0) * -1" . "\r\n";
        //割賦手数料契約
        $strSelectSQL .= ",      NVL(JYO.KAP_TES_KJN,0) * -1" . "\r\n";
        //登録諸費用3%契約
        $strSelectSQL .= ",      NVL(JYO.TOU_SYH_KYK,0) * -1" . "\r\n";
        //登録諸費用3%基準
        $strSelectSQL .= ",      NVL(JYO.TOU_SYH_KJN,0) * -1" . "\r\n";
        //預かり法定費用
        $strSelectSQL .= ",      NVL(JYO.HOUTEIH_GK,0) * -1" . "\r\n";
        //税金保険料
        $strSelectSQL .= ",      NVL(JYO.HKN_GK,0) * -1" . "\r\n";
        //残債
        $strSelectSQL .= ",      NVL(JYO.TRA_CAR_ZSI_SUM,0) * -1" . "\r\n";
        //残債
        $strSelectSQL .= ",      NVL(JYO.SHR_GK_SUM,0) * -1" . "\r\n";
        //支払金合計
        $strSelectSQL .= ",      NVL(JYO.SHR_JKN_SIT_KIN,0) * -1" . "\r\n";
        //支払条件下取価格
        $strSelectSQL .= ",      NVL(JYO.SHR_JKN_ATM_KIN,0) * -1" . "\r\n";
        //支払条件頭金
        $strSelectSQL .= ",      NVL(JYO.SHR_JKN_TRK_SYH,0) * -1" . "\r\n";
        //支払条件登録諸費用
        $strSelectSQL .= ",      NVL(JYO.SHR_JKN_CKO_FTK,0) * -1" . "\r\n";
        //支払条件中古車負担金
        $strSelectSQL .= ",      NVL(JYO.SHR_JKN_TGT_KAI,0) * -1" . "\r\n";
        //支払条件手形回数
        $strSelectSQL .= ",      NVL(JYO.SHR_JKN_TGT_KIN,0) * -1" . "\r\n";
        //支払条件手形金額
        $strSelectSQL .= ",      NVL(JYO.SHR_JKN_KRJ_KAI,0) * -1" . "\r\n";
        //支払条件クレジット回数
        $strSelectSQL .= ",      NVL(JYO.SHR_JKN_KRJ_KIN,0) * -1" . "\r\n";
        //支払条件クレジット金額
        $strSelectSQL .= ",      NVL(JYO.TRA_CAR_PRC_SUM,0) * -1" . "\r\n";
        //下取車買取価格
        $strSelectSQL .= ",      NVL(JYO.TRA_CAR_STI_SUM,0) * -1" . "\r\n";
        //下取車査定価格
        $strSelectSQL .= ",      NVL(JYO.JIDOUSYA_ZEI,0) * -1" . "\r\n";
        //税金自動車税
        $strSelectSQL .= ",      NVL(JYO.SYARYOU_ZEI,0) * -1" . "\r\n";
        //税金車両税
        $strSelectSQL .= ",      NVL(JYO.EAKON_ZEI,0) * -1" . "\r\n";
        //税金エアコン税
        $strSelectSQL .= ",      NVL(JYO.SUTEREO_ZEI,0) * -1" . "\r\n";
        //税金ステレオ税
        $strSelectSQL .= ",      NVL(JYO.JYURYO_ZEI,0) * -1" . "\r\n";
        //税金重量税
        $strSelectSQL .= ",      NVL(JYO.SHZ_KEI,0) * -1" . "\r\n";
        //税金消費税
        $strSelectSQL .= ",      NVL(JYO.JIBAI_HOK_RYO,0) * -1" . "\r\n";
        //自賠責保険料
        $strSelectSQL .= ",      NVL(JYO.OPTHOK_RYO,0) * -1" . "\r\n";
        //任意保険料
        $strSelectSQL .= ",      NVL(JYO.HNB_TES_GKU,0) * -1" . "\r\n";
        //販売手数料額
        $strSelectSQL .= ",      NVL(JYO.HNB_SHZ,0) * -1" . "\r\n";
        //販売消費税
        $strSelectSQL .= ",      NVL(JYO.HONBU_FTK,0) * -1" . "\r\n";
        //本部負担金
        $strSelectSQL .= ",      NVL(JYO.UKM_SNY_TES,0) * -1" . "\r\n";
        //打込金収入手数料
        $strSelectSQL .= ",      NVL(JYO.UKM_SINSEI_SYR,0) * -1" . "\r\n";
        //打込金申請奨励金
        $strSelectSQL .= ",      NVL(JYO.KAP_TES_SGK,0) * -1" . "\r\n";
        //割賦手数料差額
        $strSelectSQL .= ",      NVL(JYO.ETC_SKI_RYO,0) * -1" . "\r\n";
        //その他紹介料
        $strSelectSQL .= ",      NVL(JYO.SRY_GENKAI_RIE,0) * -1" . "\r\n";
        //車両F号限界利益
        $strSelectSQL .= ",      NVL(JYO.GNK_HJN_PCS,0) * -1" . "\r\n";
        //原価標準原価
        $strSelectSQL .= ",      NVL(JYO.GNK_SIT_URI_SKI,0) * -1" . "\r\n";
        //原価下取車売上仕切
        $strSelectSQL .= ",      NVL(JYO.CKO_BAI_SIT_KIN,0) * -1" . "\r\n";
        //中古車売車下取価格
        $strSelectSQL .= ",      NVL(JYO.CKO_BAI_SATEI,0) * -1" . "\r\n";
        //中古車売車査定価格
        $strSelectSQL .= ",      NVL(JYO.CKO_SAI_MITUMORI,0) * -1" . "\r\n";
        //中古車再生見積価格
        $strSelectSQL .= ",      NVL(JYO.CKO_SYOGAKARI,0) * -1" . "\r\n";
        //中古車諸掛
        $strSelectSQL .= ",      NVL(JYO.CKO_MIKEI_JDO_KIN,0) * -1" . "\r\n";
        //中古車未経過自動車税金額
        $strSelectSQL .= ",      NVL(JYO.CKO_MIKEI_JIBAI_KIN,0) * -1" . "\r\n";
        //中古車未経過自賠責金額
        //strSelectSQL.Append(",      JYO.URI_GYOSYA" & vbCrLf)              '業者名
        $strSelectSQL .= ",      JYO.URI_GYOSYA  " . "\r\n";
        //業者名
        $strSelectSQL .= ",      JYO.KYK_HNS" . "\r\n";
        //契約店
        $strSelectSQL .= ",      JYO.TOU_HNS" . "\r\n";
        //登録店
        //2006/11/02 UPDATE Start 条変分が加味されていなっかた
        //strSelectSQL.Append(",      JYO.TOK_KEI_KHN_MGN" & vbCrLf)     '特約店契約基本マージン
        //strSelectSQL.Append(",      JYO.TOK_KEI_RUI_MGN" & vbCrLf)     '特約店契約累積マージン
        //strSelectSQL.Append(",      JYO.TOK_KEI_KHN_SYR" & vbCrLf)     '特約店契約拡販奨励金
        //strSelectSQL.Append(",      (CASE WHEN NVL(JYO.CHUMON_KB,' ') = 'A' THEN JYO.KICK_BACK ELSE 0 END)" & vbCrLf)  'ｷｯｸバック
        $strSelectSQL .= ",      NVL(JYO.TOK_KEI_KHN_MGN,0) * -1" . "\r\n";
        //特約店契約基本マージン
        $strSelectSQL .= ",      NVL(JYO.TOK_KEI_RUI_MGN,0) * -1" . "\r\n";
        //特約店契約累積マージン
        $strSelectSQL .= ",      NVL(JYO.TOK_KEI_KHN_SYR,0) * -1" . "\r\n";
        //特約店契約拡販奨励金
        $strSelectSQL .= ",      (CASE WHEN NVL(JYO.CHUMON_KB,' ') = 'A' THEN NVL(JYO.KICK_BACK,0) * -1 ELSE 0 END)" . "\r\n";
        //ｷｯｸバック
        //2006/11/02 UPDATE End
        $strSelectSQL .= ",      (CASE WHEN NVL(JYO.CHUMON_KB,' ') = 'A' THEN JYO.NEBIKI_RT ELSE 0 END)" . "\r\n";
        //値引率
        $strSelectSQL .= ",      (CASE WHEN NVL(JYO.CHUMON_KB,' ') = 'A' THEN JYO.KIJUN_NEBIKI_RT ELSE 0 END)" . "\r\n";
        //基準値引率
        $strSelectSQL .= ",      JYO.UPD_DATE" . "\r\n";
        $strSelectSQL .= ",      JYO.CREATE_DATE" . "\r\n";
        //2006/08/23 UPDATE START
        $strSelectSQL .= ",      NVL(JYO.RCY_YOT_KIN,0) * -1" . "\r\n";
        //リサイクル預託金
        $strSelectSQL .= ",      NVL(JYO.RCY_SKN_KAN_HI,0) * -1" . "\r\n";
        //リサイクル資金管理費
        $strSelectSQL .= ",      NVL(JYO.KICK_BACK,0) * -1" . "\r\n";
        //キックバック
        //2006/08/30に売上ﾃﾞｰﾀ追加分
        $strSelectSQL .= ",      DECODE(JYO.TOU_SYH_KJN_GK,NULL,NULL,NVL(JYO.TOU_SYH_KJN_GK,0) * -1)" . "\r\n";
        $strSelectSQL .= ",      DECODE(JYO.SYUEKI_SYOKEI,NULL,NULL,NVL(JYO.SYUEKI_SYOKEI,0) * -1)" . "\r\n";
        //2006/08/30 update start
        $strSelectSQL .= ",      NVL(JYO.HOUTEIH_SIT,0) * -1" . "\r\n";
        //キックバック
        $strSelectSQL .= ",      NVL(JYO.FHZ_NBK,0) * -1" . "\r\n";
        //付属品値引
        $strSelectSQL .= ",      NVL(JYO.TKB_KSH_NBK,0) * -1" . "\r\n";
        //特別架装品値引
        //2006/08/23 UPDATE END
        //TODO 2006/12/08 UPDATE STart
        $strSelectSQL .= " , " . $this->ClsComFnc->FncSqlNv($strUpdUser);
        //ユーザID
        $strSelectSQL .= " , " . $this->ClsComFnc->FncSqlNv($strUpdApp);
        //アプリケーション
        $strSelectSQL .= " , " . $this->ClsComFnc->FncSqlNv($strUpdCltNM);
        //マシン名
        //2006/12/08 UPDATE End
        //2009/12/21 INS Start R4連携集計システムのために追加    '解約日
        $strSelectSQL .= ",           NVL(JYO.TRA_CAR_RCYYTK_SUM,0) * -1" . "\r\n";
        $strSelectSQL .= ",           NVL(JYO.KAP_GKN,0) * -1" . "\r\n";
        $strSelectSQL .= ",           NVL(JYO.TOU_SYH_KEN,0) * -1" . "\r\n";
        $strSelectSQL .= ",           NVL(JYO.TOU_SYH_SYAKEN,0) * -1" . "\r\n";
        $strSelectSQL .= ",           NVL(JYO.TOU_SYH_SYAKO_SYO,0) * -1" . "\r\n";
        $strSelectSQL .= ",           NVL(JYO.TOU_SYH_NOUSYA,0) * -1" . "\r\n";
        $strSelectSQL .= ",           NVL(JYO.TOU_SYH_SIT_TTK,0) * -1" . "\r\n";
        $strSelectSQL .= ",           NVL(JYO.TOU_SYH_SATEI,0) * -1" . "\r\n";
        $strSelectSQL .= ",           NVL(JYO.TOU_SYH_JIKOU,0) * -1" . "\r\n";
        $strSelectSQL .= ",           NVL(JYO.TOU_SYH_ETC,0) * -1" . "\r\n";
        $strSelectSQL .= ",           NVL(JYO.HOUTEIH_KEN,0) * -1" . "\r\n";
        $strSelectSQL .= ",           NVL(JYO.HOUTEIH_SYAKEN,0) * -1" . "\r\n";
        $strSelectSQL .= ",           NVL(JYO.HOUTEIH_SYAKO_SYO,0) * -1" . "\r\n";
        $strSelectSQL .= ",           NVL(JYO.PENALTY,0) * -1" . "\r\n";
        $strSelectSQL .= ",           NVL(JYO.EGO_GAI_SYUEKI,0) * -1" . "\r\n";
        $strSelectSQL .= ",           NVL(JYO.SAI_SONEKI,0) * -1" . "\r\n";
        $strSelectSQL .= ",           NVL(JYO.JAF,0) * -1" . "\r\n";
        $strSelectSQL .= ",           NVL(JYO.PACK_DE_753,0) * -1" . "\r\n";
        $strSelectSQL .= ",           NVL(JYO.PACK_DE_MENTE,0) * -1" . "\r\n";
        $strSelectSQL .= "      ,JYO.KYK_CSRRANK" . "\r\n";
        $strSelectSQL .= "      ,JYO.SIY_CSRRANK" . "\r\n";
        //''strSQL.Append("      ,URI.UC_KENSU_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.MI_JISSEKI_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.TOU_JISSEKI_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.TA_KYK_JI_TRK_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.JI_KYK_TA_TRK_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.MAKER_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.FUKUSHI_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.SYAMEI_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.URI_JISSEKI_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.LEASE_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.SEAVICE_CAR_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.SAIBAI_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.KARUTE_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.TRKKB_URI_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.TRKKB_TOU_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.TRKKB_SONTA_FLG" & vbCrLf)
        $strSelectSQL .= "      ,NVL(JYO.UC_KENSU,0) * -1" . "\r\n";
        $strSelectSQL .= "      ,NVL(JYO.MI_JISSEKI_DAISU,0) * -1" . "\r\n";
        $strSelectSQL .= "      ,NVL(JYO.TOU_JISSEKI_DAISU,0) * -1" . "\r\n";
        $strSelectSQL .= "      ,NVL(JYO.TA_KYK_JI_TRK_DAISU,0) * -1" . "\r\n";
        $strSelectSQL .= "      ,NVL(JYO.JI_KYK_TA_TRK_DAISU,0) * -1" . "\r\n";
        $strSelectSQL .= "      ,NVL(JYO.MAKER_DAISU,0) * -1" . "\r\n";
        $strSelectSQL .= "      ,NVL(JYO.FUKUSHI_DAISU,0) * -1" . "\r\n";
        $strSelectSQL .= "      ,NVL(JYO.SYAMEI_DAISU,0) * -1" . "\r\n";
        $strSelectSQL .= "      ,NVL(JYO.URI_JISSEKI_DAISU,0) * -1" . "\r\n";
        $strSelectSQL .= "      ,NVL(JYO.LEASE_DAISU,0) * -1" . "\r\n";
        $strSelectSQL .= "      ,NVL(JYO.SEAVICE_CAR_DAISU,0) * -1" . "\r\n";
        $strSelectSQL .= "      ,NVL(JYO.SAIBAI_DAISU,0) * -1" . "\r\n";
        $strSelectSQL .= "      ,NVL(JYO.KARUTE_DAISU,0) * -1" . "\r\n";
        $strSelectSQL .= "      ,NVL(JYO.TRKKB_URI_DAISU,0) * -1" . "\r\n";
        $strSelectSQL .= "      ,NVL(JYO.TRKKB_TOU_DAISU,0) * -1" . "\r\n";
        $strSelectSQL .= "      ,NVL(JYO.TRKKB_SONTA_DAISU,0) * -1" . "\r\n";
        $strSelectSQL .= "      ,NVL(JYO.KAIYAKU_DAISU,0) * -1" . "\r\n";
        $strSelectSQL .= "      ,JYO.CMN_NO" . "\r\n";
        //2009/12/21 INS End
        if ($strActmode == "S") {
            $strSelectSQL .= "FROM  HSCURI_S_VW URI" . "\r\n";
            $strSelectSQL .= ",     HJYOUHEN_S JYO" . "\r\n";
        } else {
            $strSelectSQL .= "FROM  HSCURI_VW URI" . "\r\n";
            $strSelectSQL .= ",     HJYOUHEN JYO" . "\r\n";
        }
        $strSelectSQL .= ",     HBUSYO   BUY" . "\r\n";
        //条件変更履歴データの注文書番号ごとのMAXの履歴番号を取得する
        $strSelectSQL .= ",     (SELECT CMN_NO" . "\r\n";
        $strSelectSQL .= "       ,      MAX(JKN_HKO_RIRNO) MAX_RIRNO" . "\r\n";
        if ($strActmode == "S") {
            $strSelectSQL .= "       FROM   HJYOUHEN_S" . "\r\n";
        } else {
            $strSelectSQL .= "       FROM   HJYOUHEN" . "\r\n";
        }

        $strSelectSQL .= "      WHERE   KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSelectSQL .= "       GROUP BY CMN_NO) JYO_NO" . "\r\n";

        $strSQL .= $strSelectSQL;

        $strSQL .= "WHERE URI.CMN_NO = JYO.CMN_NO" . "\r\n";
        $strSQL .= "  AND DECODE(URI.URI_BUSYO_CD,'168',URI.URK_BUSYO_CD,URI.URI_BUSYO_CD)  <> DECODE(JYO.URI_BUSYO_CD,'168',JYO.URK_BUSYO_CD,JYO.URI_BUSYO_CD)" . "\r\n";
        $strSQL .= "  AND JYO.CMN_NO = JYO_NO.CMN_NO" . "\r\n";
        $strSQL .= "  AND JYO.JKN_HKO_RIRNO = JYO_NO.MAX_RIRNO" . "\r\n";
        $strSQL .= "  AND DECODE(JYO.URI_BUSYO_CD,'168',JYO.URK_BUSYO_CD,JYO.URI_BUSYO_CD) = BUY.BUSYO_CD" . "\r\n";
        $strSQL .= "  AND URI.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL .= " UNION " . "\r\n";

        $strSQL .= $strSelectSQL . "\r\n";

        $strSQL .= "WHERE URI.CMN_NO = JYO.CMN_NO" . "\r\n";
        $strSQL .= "  AND URI.URI_TANNO <> JYO.URI_TANNO" . "\r\n";
        $strSQL .= "  AND JYO.CMN_NO = JYO_NO.CMN_NO" . "\r\n";
        $strSQL .= "  AND JYO.JKN_HKO_RIRNO = JYO_NO.MAX_RIRNO" . "\r\n";
        $strSQL .= "  AND JYO.KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSQL .= "  AND DECODE(JYO.URI_BUSYO_CD,'168',JYO.URK_BUSYO_CD,JYO.URI_BUSYO_CD) = BUY.BUSYO_CD" . "\r\n";
        $strSQL .= "  AND URI.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL = str_replace("@KEIJYOBI", $strSyoriYM, $strSQL);
        return $strSQL;
    }

    public function fncInsertUriageSagaku($strSyoriYM, $strUpdApp, $strActmode = "K")
    {
        $strUpdUser = $this->GS_LOGINUSER['strUserID'];
        $strUpdCltNM = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";

        $strSQL = $this->fncInsertHGENRICreateSQL($strActmode);

        $strSQL .= "SELECT URI.KEIJYO_YM" . "\r\n";
        //年月
        $strSQL .= ",      (CASE WHEN URI.NAU_KB = '1'" . "\r\n";
        $strSQL .= "             THEN (CASE WHEN SUBSTR(URI.UC_NO,10,1) IN ('Z','P','Q','R','S','T','U','V','W')" . "\r\n";
        $strSQL .= "                        THEN '3' ELSE '1' END)" . "\r\n";
        $strSQL .= "             ELSE '2' END)" . "\r\n";
        //データ区分
        //strSQL.Append(",      BUY.KKR_BUSYO_CD" & vbCrLf)                '括り部署
        $strSQL .= ",      (CASE WHEN URI.NAU_KB = '2' AND URI.CKO_HNB_KB = '5'" . "\r\n";
        $strSQL .= "             THEN '20' ELSE BUY.KKR_BUSYO_CD END) KKR_BUSYO_CD " . "\r\n";
        $strSQL .= ",      (CASE WHEN NVL(URI.URI_BUSYO_CD,' ') = '168' THEN NVL(URI.URK_BUSYO_CD,' ') ELSE NVL(URI.URI_BUSYO_CD,' ') END)" . "\r\n";
        //扱い部署
        $strSQL .= ",      NVL(URI.URI_TANNO,' ')" . "\r\n";
        //扱い社員
        $strSQL .= ",      NVL(URI.URI_GYOSYA,' ')" . "\r\n";
        //扱い業者
        $strSQL .= ",      NVL(URI.UC_NO,' ')" . "\r\n";
        //UCNO
        $strSQL .= ",      URI.CARNO" . "\r\n";
        //CARNO
        $strSQL .= ",      URI.NENSIKI" . "\r\n";
        //年式
        $strSQL .= ",      (CASE WHEN URI.NAU_KB = '1' THEN URI.TOA_NAME ELSE URI.SYANAI_KOSYO END)" . "\r\n";
        //型式
        $strSQL .= ",      SUBSTRB((CASE WHEN URI.NAU_KB = '1' THEN URI.MGN_MEI_KNJ1 ELSE URI.KYK_MEI_KNJ1 END),1,40)" . "\r\n";
        //名義人
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.URI_DAISU,0),0)-NVL(JYO.URI_DAISU,0)" . "\r\n";
        //売上台数
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.TOU_DAISU,0),0)-NVL(JYO.TOU_DAISU,0)" . "\r\n";
        //登録台数
        if ($strActmode == "S") {
            $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL((SELECT COUNT(CMN_NO) FROM HSCSIT_S_VW SIT WHERE SIT.CMN_NO = URI.CMN_NO AND SIT.KEIJYO_YM = URI.KEIJYO_YM),0),0) " . "\r\n";
            //下取台数
            $strSQL .= "      -NVL((SELECT COUNT(CMN_NO) FROM HJYOUHENSIT_S SIT WHERE SIT.CMN_NO = JYO.CMN_NO AND SIT.KEIJYO_YM = JYO.KEIJYO_YM),0) SIT_DAISU" . "\r\n";
            //下取台数
        } else {
            $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL((SELECT COUNT(CMN_NO) FROM HSCSIT_VW SIT WHERE SIT.CMN_NO = URI.CMN_NO AND SIT.KEIJYO_YM = URI.KEIJYO_YM),0),0) " . "\r\n";
            //下取台数
            $strSQL .= "      -NVL((SELECT COUNT(CMN_NO) FROM HJYOUHENSIT SIT WHERE SIT.CMN_NO = JYO.CMN_NO AND SIT.KEIJYO_YM = JYO.KEIJYO_YM),0) SIT_DAISU" . "\r\n";
            //下取台数
        }

        $strSQL .= ",      URI.CHUMON_KB	" . "\r\n";
        //注文書区分
        $strSQL .= ",      URI.HNB_KB" . "\r\n";
        //販売形態
        $strSQL .= ",      URI.KKR_CD" . "\r\n";
        //括りコード
        $strSQL .= ",      URI.CKG_KB" . "\r\n";
        //中古車直業
        $strSQL .= ",      URI.CKO_HNB_KB" . "\r\n";
        //中古車販売区分
        $strSQL .= ",      URI.CKO_SS_KB" . "\r\n";
        //中古車車種区分
        $strSQL .= ",      URI.CKO_SIR_KB" . "\r\n";
        //中古車仕入区分
        $strSQL .= ",      URI.CKO_SEB_KB" . "\r\n";
        //中古車整備区分
        $strSQL .= ",      URI.CKO_MEG_KB" . "\r\n";
        //中古車業売名義
        $strSQL .= ",      URI.CKO_MHN_KB" . "\r\n";
        //中古車名変FLG
        $strSQL .= ",      URI.CEL_DATE" . "\r\n";
        //解約年月日
        //strSQL.Append(",      NVL(URI.SRY_PRC,0) - NVL(JYO.SRY_PRC,0)" & vbCrLf)                '車両価格
        $strSQL .= ",      (CASE WHEN URI.NAU_KB = '1' AND URI.KYK_HNS='17349'" . "\r\n";
        //車両価格
        $strSQL .= "             THEN (DECODE(URI.CEL_DATE,NULL,NVL(URI.SRY_PRC,0),0)-NVL(JYO.SRY_PRC,0))-(DECODE(URI.CEL_DATE,NULL,NVL(URI.SRY_CMN_PCS,0),0)-NVL(JYO.SRY_CMN_PCS,0)) ELSE DECODE(URI.CEL_DATE,NULL,NVL(URI.SRY_PRC,0),0) - NVL(JYO.SRY_PRC,0) END) SRY_PRC" . "\r\n";
        //2008/05/16 UPDATE Start
        //strSQL.Append(",      NVL(URI.SRY_NBK,0) - NVL(JYO.SRY_NBK,0)" & vbCrLf)                 '車両値引
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.SRY_NBK,0),0) - NVL(JYO.SRY_NBK,0)" . "\r\n";
        //車両値引
        //2008/05/16 UPDATE End
        //strSQL.Append(",      NVL(URI.SRY_CMN_PCS,0) - NVL(JYO.SRY_CMN_PCS,0)" & vbCrLf)        '車両注文書原価
        $strSQL .= ",      (CASE WHEN URI.NAU_KB = '1' AND URI.KYK_HNS='17349'" . "\r\n";
        //車両注文書原価
        $strSQL .= "             THEN 0 ELSE DECODE(URI.CEL_DATE,NULL,NVL(URI.SRY_CMN_PCS,0),0) - NVL(JYO.SRY_CMN_PCS,0) END) SRY_CMN_PCS" . "\r\n";
        //strSQL.Append(",      NVL(URI.SRY_KTN_PCS,0) - NVL(JYO.SRY_KTN_PCS,0)" & vbCrLf)        '車両拠点原価
        $strSQL .= ",      (CASE WHEN URI.NAU_KB = '1' AND URI.KYK_HNS='17349'" . "\r\n";
        //車両拠点原価
        $strSQL .= "             THEN 0 ELSE DECODE(URI.CEL_DATE,NULL,NVL(URI.SRY_KTN_PCS,0),0) - NVL(JYO.SRY_KTN_PCS,0) END) SRY_KTN_PCS" . "\r\n";
        //strSQL.Append(",      NVL(URI.SRY_BUY_PCS,0) - NVL(JYO.SRY_BUY_PCS,0)" & vbCrLf)        '車両新車車両部署別用原価
        $strSQL .= ",      (CASE WHEN URI.NAU_KB = '1' AND URI.KYK_HNS='17349'" . "\r\n";
        //車両新車車両部署別用原価
        $strSQL .= "             THEN 0 ELSE DECODE(URI.CEL_DATE,NULL,NVL(URI.SRY_BUY_PCS,0),0) - NVL(JYO.SRY_BUY_PCS,0) END) SRY_BUY_PCS" . "\r\n";

        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.FHZ_TEIKA,0),0) - NVL(JYO.FHZ_TEIKA,0)" . "\r\n";
        //添付品定価
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.FHZ_KYK,0),0) - NVL(JYO.FHZ_KYK,0)" . "\r\n";
        //添付品契約
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.FHZ_PCS,0),0) - NVL(JYO.FHZ_PCS,0)" . "\r\n";
        //添付品原価
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.TKB_KSH_TEIKA,0),0) - NVL(JYO.TKB_KSH_TEIKA,0)" . "\r\n";
        //特別仕様定価
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.TKB_KSH_KYK,0),0) - NVL(JYO.TKB_KSH_KYK,0)" . "\r\n";
        //特別仕様契約
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.TKB_KSH_PCS,0),0) - NVL(JYO.TKB_KSH_PCS,0)" . "\r\n";
        //特別仕様原価
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.KAP_TES_KYK,0),0) - NVL(JYO.KAP_TES_KYK,0)" . "\r\n";
        //割賦手数料契約
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL, NVL(URI.KAP_TES_KJN,0),0) - NVL(JYO.KAP_TES_KJN,0)" . "\r\n";
        //登録諸費用3%契約
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.TOU_SYH_KYK,0),0) - NVL(JYO.TOU_SYH_KYK,0)" . "\r\n";
        //登録諸費用3%基準
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.TOU_SYH_KJN,0),0) - NVL(JYO.TOU_SYH_KJN,0)" . "\r\n";
        //預かり法定費用
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.HOUTEIH_GK,0),0) - NVL(JYO.HOUTEIH_GK,0)" . "\r\n";
        //税金保険料
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.HKN_GK,0),0) - NVL(JYO.HKN_GK,0)" . "\r\n";
        //残債
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.TRA_CAR_ZSI_SUM,0),0) - NVL(JYO.TRA_CAR_ZSI_SUM,0)" . "\r\n";
        //残債
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.SHR_GK_SUM,0),0) - NVL(JYO.SHR_GK_SUM,0)" . "\r\n";
        //支払金合計
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.SHR_JKN_SIT_KIN,0),0) - NVL(JYO.SHR_JKN_SIT_KIN,0)" . "\r\n";
        //支払条件下取価格
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.SHR_JKN_ATM_KIN,0),0) - NVL(JYO.SHR_JKN_ATM_KIN,0)" . "\r\n";
        //支払条件頭金
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.SHR_JKN_TRK_SYH,0),0) - NVL(JYO.SHR_JKN_TRK_SYH,0)" . "\r\n";
        //支払条件登録諸費用
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.SHR_JKN_CKO_FTK,0),0) - NVL(JYO.SHR_JKN_CKO_FTK,0)" . "\r\n";
        //支払条件中古車負担金
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.SHR_JKN_TGT_KAI,0),0) - NVL(JYO.SHR_JKN_TGT_KAI,0)" . "\r\n";
        //支払条件手形回数
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.SHR_JKN_TGT_KIN,0),0) - NVL(JYO.SHR_JKN_TGT_KIN,0)" . "\r\n";
        //支払条件手形金額
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.SHR_JKN_KRJ_KAI,0),0) - NVL(JYO.SHR_JKN_KRJ_KAI,0)" . "\r\n";
        //支払条件クレジット回数
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.SHR_JKN_KRJ_KIN,0),0) - NVL(JYO.SHR_JKN_KRJ_KIN,0)" . "\r\n";
        //支払条件クレジット金額
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.TRA_CAR_PRC_SUM,0),0) - NVL(JYO.TRA_CAR_PRC_SUM,0)" . "\r\n";
        //下取車買取価格
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.TRA_CAR_STI_SUM,0),0) - NVL(JYO.TRA_CAR_STI_SUM,0)" . "\r\n";
        //下取車査定価格
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.JIDOUSYA_ZEI,0),0) - NVL(JYO.JIDOUSYA_ZEI,0)" . "\r\n";
        //税金自動車税
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.SYARYOU_ZEI,0),0) - NVL(JYO.SYARYOU_ZEI,0)" . "\r\n";
        //税金車両税
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.EAKON_ZEI,0),0) - NVL(JYO.EAKON_ZEI,0)" . "\r\n";
        //税金エアコン税
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.SUTEREO_ZEI,0),0) - NVL(JYO.SUTEREO_ZEI,0)" . "\r\n";
        //税金ステレオ税
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.JYURYO_ZEI,0),0) - NVL(JYO.JYURYO_ZEI,0)" . "\r\n";
        //税金重量税
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.SHZ_KEI,0),0) - NVL(JYO.SHZ_KEI,0)" . "\r\n";
        //税金消費税
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.JIBAI_HOK_RYO,0),0) - NVL(JYO.JIBAI_HOK_RYO,0)" . "\r\n";
        //自賠責保険料
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.OPTHOK_RYO,0),0) - NVL(JYO.OPTHOK_RYO,0)" . "\r\n";
        //任意保険料
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.HNB_TES_GKU,0),0) - NVL(JYO.HNB_TES_GKU,0)" . "\r\n";
        //販売手数料額
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.HNB_SHZ,0),0) - NVL(JYO.HNB_SHZ,0)" . "\r\n";
        //販売消費税
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.HONBU_FTK,0),0) - NVL(JYO.HONBU_FTK,0)" . "\r\n";
        //本部負担金
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.UKM_SNY_TES,0),0) - NVL(JYO.UKM_SNY_TES,0)" . "\r\n";
        //打込金収入手数料
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.UKM_SINSEI_SYR,0),0) - NVL(JYO.UKM_SINSEI_SYR,0)" . "\r\n";
        //打込金申請奨励金
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.KAP_TES_SGK,0),0) - NVL(JYO.KAP_TES_SGK,0)" . "\r\n";
        //割賦手数料差額
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.ETC_SKI_RYO,0),0) - NVL(JYO.ETC_SKI_RYO,0)" . "\r\n";
        //その他紹介料
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.SRY_GENKAI_RIE,0),0) - NVL(JYO.SRY_GENKAI_RIE,0)" . "\r\n";
        //車両F号限界利益
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.GNK_HJN_PCS,0),0) - NVL(JYO.GNK_HJN_PCS,0)" . "\r\n";
        //原価標準原価
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.GNK_SIT_URI_SKI,0),0) - NVL(JYO.GNK_SIT_URI_SKI,0)" . "\r\n";
        //原価下取車売上仕切
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.CKO_BAI_SIT_KIN,0),0) - NVL(JYO.CKO_BAI_SIT_KIN,0)" . "\r\n";
        //中古車売車下取価格
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.CKO_BAI_SATEI,0),0) - NVL(JYO.CKO_BAI_SATEI,0)" . "\r\n";
        //中古車売車査定価格
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.CKO_SAI_MITUMORI,0),0) - NVL(JYO.CKO_SAI_MITUMORI,0)" . "\r\n";
        //中古車再生見積価格
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.CKO_SYOGAKARI,0),0) - NVL(JYO.CKO_SYOGAKARI,0)" . "\r\n";
        //中古車諸掛
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.CKO_MIKEI_JDO_KIN,0),0) - NVL(JYO.CKO_MIKEI_JDO_KIN,0)" . "\r\n";
        //中古車未経過自動車税金額
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.CKO_MIKEI_JIBAI_KIN,0),0) - NVL(JYO.CKO_MIKEI_JIBAI_KIN,0)" . "\r\n";
        //中古車未経過自賠責金額
        //strSQL.Append(",     URI.URI_GYOSYA" & vbCrLf)              '業者名
        $strSQL .= ",     URI.GYO_NAME  " . "\r\n";
        //業者名
        $strSQL .= ",     URI.KYK_HNS" . "\r\n";
        //契約店
        $strSQL .= ",     URI.TOU_HNS" . "\r\n";
        //登録店
        //2006/11/02 UPDATE Start    '条変分が加味されていなかったため
        //strSQL.Append(",     URI.TOK_KEI_KHN_MGN" & vbCrLf)     '特約店契約基本マージン
        //strSQL.Append(",     URI.TOK_KEI_RUI_MGN" & vbCrLf)     '特約店契約累積マージン
        //strSQL.Append(",     URI.TOK_KEI_KHN_SYR" & vbCrLf)     '特約店契約拡販奨励金
        //strSQL.Append(",     (CASE WHEN NVL(URI.CHUMON_KB,' ') = 'A' THEN URI.KICK_BACK ELSE 0 END)" & vbCrLf)  'ｷｯｸバック
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,NVL(URI.TOK_KEI_KHN_MGN,0),0) - NVL(JYO.TOK_KEI_KHN_MGN,0)" . "\r\n";
        //特約店契約基本マージン
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,NVL(URI.TOK_KEI_RUI_MGN,0),0) - NVL(JYO.TOK_KEI_RUI_MGN,0)" . "\r\n";
        //特約店契約累積マージン
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,NVL(URI.TOK_KEI_KHN_SYR,0),0) - NVL(JYO.TOK_KEI_KHN_SYR,0)" . "\r\n";
        //特約店契約拡販奨励金
        $strSQL .= ",     DECODE(URI.CEL_DATE,NULL,(CASE WHEN NVL(URI.CHUMON_KB,' ') = 'A' THEN NVL(URI.KICK_BACK,0) ELSE 0 END),0)" . "\r\n";
        //ｷｯｸバック
        $strSQL .= "      - (CASE WHEN NVL(JYO.CHUMON_KB,' ') = 'A' THEN NVL(JYO.KICK_BACK,0) ELSE 0 END)" . "\r\n";
        //2006/11/02 UPDATE End
        $strSQL .= ",     (CASE WHEN NVL(URI.CHUMON_KB,' ') = 'A' THEN URI.NEBIKI_RT ELSE 0 END)" . "\r\n";
        //値引率
        $strSQL .= ",     (CASE WHEN NVL(URI.CHUMON_KB,' ') = 'A' THEN URI.KIJUN_NEBIKI_RT ELSE 0 END)" . "\r\n";
        //基準値引率
        $strSQL .= ",     URI.UPD_DATE" . "\r\n";
        $strSQL .= ",     URI.CREATE_DATE" . "\r\n";
        //2006/08/23 UPDATE START
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.RCY_YOT_KIN,0),0) - NVL(JYO.RCY_YOT_KIN,0)" . "\r\n";
        //リサイクル預託金
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.RCY_SKN_KAN_HI,0),0) - NVL(JYO.RCY_SKN_KAN_HI,0)" . "\r\n";
        //リサイクル資金管理費
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.KICK_BACK,0),0) - NVL(JYO.KICK_BACK,0)" . "\r\n";
        //キックバック
        //2006/08/30に売上ﾃﾞｰﾀに追加分
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,DECODE(JYO.TOU_SYH_KJN_GK,NULL,NULL,NVL(URI.TOU_SYH_KJN_GK,0) - NVL(JYO.TOU_SYH_KJN_GK,0)),0)" . "\r\n";
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,DECODE(JYO.SYUEKI_SYOKEI,NULL,NULL,NVL(URI.SYUEKI_SYOKEI,0) - NVL(JYO.SYUEKI_SYOKEI,0)),0)" . "\r\n";
        //2006/08/30 UPDATE Start
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.HOUTEIH_SIT,0),0) - NVL(JYO.HOUTEIH_SIT,0)" . "\r\n";
        //法定費下取
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.FHZ_NBK,0),0) - NVL(JYO.FHZ_NBK,0)" . "\r\n";
        //付属品値引
        $strSQL .= ",      DECODE(URI.CEL_DATE,NULL,NVL(URI.TKB_KSH_NBK,0),0) - NVL(JYO.TKB_KSH_NBK,0)" . "\r\n";
        //特別架装品値引
        //2006/08/23 UPDATE END
        //TODO 2006/12/08 UPDATE STart
        $strSQL .= " , " . $this->ClsComFnc->FncSqlNv($strUpdUser);
        //ユーザID
        $strSQL .= " , " . $this->ClsComFnc->FncSqlNv($strUpdApp);
        //アプリケーション
        $strSQL .= " , " . $this->ClsComFnc->FncSqlNv($strUpdCltNM);
        //マシン名
        //2006/12/08 UPDATE End
        //2009/12/21 INS Start R4連携集計システムのために追加    '解約日
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,NVL(URI.TRA_CAR_RCYYTK_SUM,0),0) - NVL(JYO.TRA_CAR_RCYYTK_SUM,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,NVL(URI.KAP_GKN,0),0) - NVL(JYO.KAP_GKN,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,NVL(URI.TOU_SYH_KEN,0),0) - NVL(JYO.TOU_SYH_KEN,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,NVL(URI.TOU_SYH_SYAKEN,0),0) - NVL(JYO.TOU_SYH_SYAKEN,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,NVL(URI.TOU_SYH_SYAKO_SYO,0),0) - NVL(JYO.TOU_SYH_SYAKO_SYO,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,NVL(URI.TOU_SYH_NOUSYA,0),0) - NVL(JYO.TOU_SYH_NOUSYA,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,NVL(URI.TOU_SYH_SIT_TTK,0),0) - NVL(JYO.TOU_SYH_SIT_TTK,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,NVL(URI.TOU_SYH_SATEI,0),0) - NVL(JYO.TOU_SYH_SATEI,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,NVL(URI.TOU_SYH_JIKOU,0),0) - NVL(JYO.TOU_SYH_JIKOU,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,NVL(URI.TOU_SYH_ETC,0),0) - NVL(JYO.TOU_SYH_ETC,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,NVL(URI.HOUTEIH_KEN,0),0) - NVL(JYO.HOUTEIH_KEN,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,NVL(URI.HOUTEIH_SYAKEN,0),0) - NVL(JYO.HOUTEIH_SYAKEN,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,NVL(URI.HOUTEIH_SYAKO_SYO,0),0) - NVL(JYO.HOUTEIH_SYAKO_SYO,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,NVL(URI.PENALTY,0),0) - NVL(JYO.PENALTY,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,NVL(URI.EGO_GAI_SYUEKI,0),0) - NVL(JYO.EGO_GAI_SYUEKI,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,NVL(URI.SAI_SONEKI,0),0) - NVL(JYO.SAI_SONEKI,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,NVL(URI.JAF,0),0) - NVL(JYO.JAF,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,NVL(URI.PACK_DE_753,0),0) - NVL(JYO.PACK_DE_753,0)" . "\r\n";
        $strSQL .= ",           DECODE(URI.CEL_DATE,NULL,NVL(URI.PACK_DE_MENTE,0),0) - NVL(JYO.PACK_DE_MENTE,0)" . "\r\n";

        $strSQL .= "      ,URI.KYK_CSRRANK" . "\r\n";
        $strSQL .= "      ,URI.SIY_CSRRANK" . "\r\n";
        //''strSQL.Append("      ,URI.UC_KENSU_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.MI_JISSEKI_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.TOU_JISSEKI_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.TA_KYK_JI_TRK_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.JI_KYK_TA_TRK_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.MAKER_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.FUKUSHI_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.SYAMEI_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.URI_JISSEKI_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.LEASE_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.SEAVICE_CAR_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.SAIBAI_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.KARUTE_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.TRKKB_URI_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.TRKKB_TOU_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.TRKKB_SONTA_FLG" & vbCrLf)
        $strSQL .= "      ,NVL(URI.UC_KENSU,0) - NVL(JYO.UC_KENSU,0)" . "\r\n";
        $strSQL .= "      ,NVL(URI.MI_JISSEKI_DAISU,0) - NVL(JYO.MI_JISSEKI_DAISU,0)" . "\r\n";
        $strSQL .= "      ,NVL(URI.TOU_JISSEKI_DAISU,0) - NVL(JYO.TOU_JISSEKI_DAISU,0)" . "\r\n";
        $strSQL .= "      ,NVL(URI.TA_KYK_JI_TRK_DAISU,0) - NVL(JYO.TA_KYK_JI_TRK_DAISU,0)" . "\r\n";
        $strSQL .= "      ,NVL(URI.JI_KYK_TA_TRK_DAISU,0) - NVL(JYO.JI_KYK_TA_TRK_DAISU,0)" . "\r\n";
        $strSQL .= "      ,NVL(URI.MAKER_DAISU,0) - NVL(JYO.MAKER_DAISU,0)" . "\r\n";
        $strSQL .= "      ,NVL(URI.FUKUSHI_DAISU,0) - NVL(JYO.FUKUSHI_DAISU,0)" . "\r\n";
        $strSQL .= "      ,NVL(URI.SYAMEI_DAISU,0) - NVL(JYO.SYAMEI_DAISU,0)" . "\r\n";
        $strSQL .= "      ,NVL(URI.URI_JISSEKI_DAISU,0) - NVL(JYO.URI_JISSEKI_DAISU,0)" . "\r\n";
        $strSQL .= "      ,NVL(URI.LEASE_DAISU,0) - NVL(JYO.LEASE_DAISU,0)" . "\r\n";
        $strSQL .= "      ,NVL(URI.SEAVICE_CAR_DAISU,0) - NVL(JYO.SEAVICE_CAR_DAISU,0)" . "\r\n";
        $strSQL .= "      ,NVL(URI.SAIBAI_DAISU,0) - NVL(JYO.SAIBAI_DAISU,0)" . "\r\n";
        $strSQL .= "      ,NVL(URI.KARUTE_DAISU,0) - NVL(JYO.KARUTE_DAISU,0)" . "\r\n";
        $strSQL .= "      ,NVL(URI.TRKKB_URI_DAISU,0) - NVL(JYO.TRKKB_URI_DAISU,0)" . "\r\n";
        $strSQL .= "      ,NVL(URI.TRKKB_TOU_DAISU,0) - NVL(JYO.TRKKB_TOU_DAISU,0)" . "\r\n";
        $strSQL .= "      ,NVL(URI.TRKKB_SONTA_DAISU,0) - NVL(JYO.TRKKB_SONTA_DAISU,0)" . "\r\n";
        $strSQL .= "      ,NVL(URI.KAIYAKU_DAISU,0) - NVL(JYO.KAIYAKU_DAISU,0)" . "\r\n";
        $strSQL .= "      ,URI.CMN_NO" . "\r\n";
        //2009/12/21 INS End
        if ($strActmode == "S") {
            $strSQL .= "FROM  HSCURI_S_VW URI" . "\r\n";
            $strSQL .= ",     HJYOUHEN_S JYO" . "\r\n";
        } else {
            $strSQL .= "FROM  HSCURI_VW URI" . "\r\n";
            $strSQL .= ",     HJYOUHEN JYO" . "\r\n";
        }

        $strSQL .= ",     HBUSYO   BUY" . "\r\n";
        //条件変更履歴データの注文書番号ごとのMAXの履歴番号を取得する
        $strSQL .= ",     (SELECT CMN_NO" . "\r\n";
        $strSQL .= "       ,      MAX(JKN_HKO_RIRNO) MAX_RIRNO" . "\r\n";
        if ($strActmode == "S") {
            $strSQL .= "       FROM   HJYOUHEN_S" . "\r\n";
        } else {
            $strSQL .= "       FROM   HJYOUHEN" . "\r\n";
        }
        $strSQL .= "       WHERE  KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSQL .= "       GROUP BY CMN_NO) JYO_NO" . "\r\n";

        $strSQL .= "WHERE URI.CMN_NO = JYO.CMN_NO" . "\r\n";
        $strSQL .= "  AND URI.NAU_KB = JYO.NAU_KB" . "\r\n";
        $strSQL .= "  AND DECODE(URI.URI_BUSYO_CD,'168',URI.URK_BUSYO_CD,URI.URI_BUSYO_CD) = DECODE(JYO.URI_BUSYO_CD,'168',JYO.URK_BUSYO_CD,JYO.URI_BUSYO_CD)" . "\r\n";
        $strSQL .= "  AND URI.URI_TANNO = JYO.URI_TANNO" . "\r\n";
        $strSQL .= "  AND JYO.CMN_NO = JYO_NO.CMN_NO" . "\r\n";
        $strSQL .= "  AND JYO.JKN_HKO_RIRNO = JYO_NO.MAX_RIRNO" . "\r\n";
        $strSQL .= "  AND JYO.KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSQL .= "  AND DECODE(JYO.URI_BUSYO_CD,'168',JYO.URK_BUSYO_CD,JYO.URI_BUSYO_CD) = BUY.BUSYO_CD" . "\r\n";
        $strSQL .= "  AND URI.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL = str_replace("@KEIJYOBI", $strSyoriYM, $strSQL);
        return $strSQL;
    }

    public function fncInsertForExist($strSyoriYM, $strUpdApp, $strActmode = "K")
    {
        $strUpdUser = $this->GS_LOGINUSER['strUserID'];
        $strUpdCltNM = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";
        // $strWhereSQL = "";
        $strSelectSQL = "";

        $strSQL .= $this->fncInsertHGENRICreateSQL($strActmode);

        $strSelectSQL .= "SELECT URI.KEIJYO_YM" . "\r\n";
        //年月
        $strSelectSQL .= ",      (CASE WHEN URI.NAU_KB = '1'" . "\r\n";
        $strSelectSQL .= "             THEN (CASE WHEN SUBSTR(URI.UC_NO,10,1) IN ('Z','P','Q','R','S','T','U','V','W')" . "\r\n";
        $strSelectSQL .= "                        THEN '3' ELSE '1' END)" . "\r\n";
        $strSelectSQL .= "             ELSE '2' END)" . "\r\n";
        //データ区分
        //strSelectSQL.Append(",      BUY.KKR_BUSYO_CD" & vbCrLf)                '括り部署
        $strSelectSQL .= ",      (CASE WHEN URI.NAU_KB = '2' AND URI.CKO_HNB_KB = '5'" . "\r\n";
        $strSelectSQL .= "             THEN '20' ELSE BUY.KKR_BUSYO_CD END) KKR_BUSYO_CD " . "\r\n";
        $strSelectSQL .= ",      (CASE WHEN NVL(URI.URI_BUSYO_CD,' ') = '168' THEN NVL(URI.URK_BUSYO_CD,' ') ELSE NVL(URI.URI_BUSYO_CD,' ') END)" . "\r\n";
        //扱い部署
        $strSelectSQL .= ",      NVL(URI.URI_TANNO,' ')	" . "\r\n";
        //扱い社員
        $strSelectSQL .= ",      NVL(URI.URI_GYOSYA,' ')" . "\r\n";
        //扱い業者
        $strSelectSQL .= ",      NVL(URI.UC_NO,' ')" . "\r\n";
        //UCNO
        $strSelectSQL .= ",      URI.CARNO" . "\r\n";
        //CARNO
        $strSelectSQL .= ",      URI.NENSIKI" . "\r\n";
        //年式
        $strSelectSQL .= ",      (CASE WHEN URI.NAU_KB = '1' THEN URI.TOA_NAME ELSE URI.SYANAI_KOSYO END)" . "\r\n";
        //型式
        $strSelectSQL .= ",      SUBSTRB((CASE WHEN URI.NAU_KB = '1' THEN URI.MGN_MEI_KNJ1 ELSE URI.KYK_MEI_KNJ1 END),1,40)" . "\r\n";
        //名義人
        $strSelectSQL .= ",      URI.URI_DAISU" . "\r\n";
        //売上台数
        $strSelectSQL .= ",      URI.TOU_DAISU" . "\r\n";
        //登録台数
        if ($strActmode == "S") {
            $strSelectSQL .= ",      NVL((SELECT COUNT(CMN_NO) FROM HSCSIT_S_VW SIT WHERE SIT.CMN_NO = URI.CMN_NO AND SIT.KEIJYO_YM = URI.KEIJYO_YM),0)" . "\r\n";
            //下取台数
        } else {
            $strSelectSQL .= ",      NVL((SELECT COUNT(CMN_NO) FROM HSCSIT_VW SIT WHERE SIT.CMN_NO = URI.CMN_NO AND SIT.KEIJYO_YM = URI.KEIJYO_YM),0)" . "\r\n";
            //下取台数
        }

        $strSelectSQL .= ",      URI.CHUMON_KB	" . "\r\n";
        //注文書区分
        $strSelectSQL .= ",      URI.HNB_KB" . "\r\n";
        //販売形態
        $strSelectSQL .= ",      URI.KKR_CD" . "\r\n";
        //括りコード
        $strSelectSQL .= ",      URI.CKG_KB" . "\r\n";
        //中古車直業
        $strSelectSQL .= ",      URI.CKO_HNB_KB" . "\r\n";
        //中古車販売区分
        $strSelectSQL .= ",      URI.CKO_SS_KB" . "\r\n";
        //中古車車種区分
        $strSelectSQL .= ",      URI.CKO_SIR_KB" . "\r\n";
        //中古車仕入区分
        $strSelectSQL .= ",      URI.CKO_SEB_KB" . "\r\n";
        //中古車整備区分
        $strSelectSQL .= ",      URI.CKO_MEG_KB" . "\r\n";
        //中古車業売名義
        $strSelectSQL .= ",      URI.CKO_MHN_KB" . "\r\n";
        //中古車名変FLG
        $strSelectSQL .= ",      URI.CEL_DATE" . "\r\n";
        //解約年月日
        //strSelectSQL.Append(",      URI.SRY_PRC" & vbCrLf)           //車両価格
        $strSelectSQL .= ",      (CASE WHEN URI.NAU_KB = '1' AND URI.KYK_HNS='17349'" . "\r\n";
        //車両価格
        $strSelectSQL .= "             THEN (NVL(URI.SRY_PRC,0)-NVL(URI.SRY_CMN_PCS,0)) ELSE URI.SRY_PRC END) SRY_PRC" . "\r\n";
        $strSelectSQL .= ",      URI.SRY_NBK" . "\r\n";
        //車両値引
        //strSelectSQL.Append(",      URI.SRY_CMN_PCS" & vbCrLf)        //車両注文書原価
        $strSelectSQL .= ",      (CASE WHEN URI.NAU_KB = '1' AND URI.KYK_HNS='17349'" . "\r\n";
        //車両注文書原価
        $strSelectSQL .= "             THEN 0 ELSE URI.SRY_CMN_PCS END) SRY_CMN_PCS" . "\r\n";
        //strSelectSQL.Append(",      URI.SRY_KTN_PCS" & vbCrLf)        //車両拠点原価
        $strSelectSQL .= ",      (CASE WHEN URI.NAU_KB = '1' AND URI.KYK_HNS='17349'" . "\r\n";
        //車両拠点原価
        $strSelectSQL .= "             THEN 0 ELSE URI.SRY_KTN_PCS END) SRY_KTN_PCS" . "\r\n";
        //strSelectSQL.Append(",      URI.SRY_BUY_PCS" & vbCrLf)        //車両新車車両部署別用原価
        $strSelectSQL .= ",      (CASE WHEN URI.NAU_KB = '1' AND URI.KYK_HNS='17349'" . "\r\n";
        //車両新車車両部署別用原価
        $strSelectSQL .= "             THEN 0 ELSE URI.SRY_BUY_PCS END) SRY_BUY_PCS" . "\r\n";

        $strSelectSQL .= ",      URI.FHZ_TEIKA" . "\r\n";
        //添付品定価
        $strSelectSQL .= ",      URI.FHZ_KYK" . "\r\n";
        //添付品契約
        $strSelectSQL .= ",      URI.FHZ_PCS" . "\r\n";
        //添付品原価
        $strSelectSQL .= ",      URI.TKB_KSH_TEIKA" . "\r\n";
        //特別仕様定価
        $strSelectSQL .= ",      URI.TKB_KSH_KYK" . "\r\n";
        //特別仕様契約
        $strSelectSQL .= ",      URI.TKB_KSH_PCS" . "\r\n";
        //特別仕様原価
        $strSelectSQL .= ",      URI.KAP_TES_KYK" . "\r\n";
        //割賦手数料契約
        $strSelectSQL .= ",      URI.KAP_TES_KJN" . "\r\n";
        //割賦手数料基準
        $strSelectSQL .= ",      URI.TOU_SYH_KYK" . "\r\n";
        //登録諸費用3%契約
        $strSelectSQL .= ",      URI.TOU_SYH_KJN" . "\r\n";
        //登録諸費用3%基準
        $strSelectSQL .= ",      URI.HOUTEIH_GK" . "\r\n";
        //預かり法定費用
        $strSelectSQL .= ",      URI.HKN_GK" . "\r\n";
        //税金保険料
        $strSelectSQL .= ",      URI.TRA_CAR_ZSI_SUM" . "\r\n";
        //残債
        $strSelectSQL .= ",      URI.SHR_GK_SUM" . "\r\n";
        //支払金合計
        $strSelectSQL .= ",      URI.SHR_JKN_SIT_KIN" . "\r\n";
        //支払条件下取価格
        $strSelectSQL .= ",      URI.SHR_JKN_ATM_KIN" . "\r\n";
        //支払条件頭金
        $strSelectSQL .= ",      URI.SHR_JKN_TRK_SYH" . "\r\n";
        //支払条件登録諸費用
        $strSelectSQL .= ",      URI.SHR_JKN_CKO_FTK" . "\r\n";
        //支払条件中古車負担金
        $strSelectSQL .= ",      URI.SHR_JKN_TGT_KAI" . "\r\n";
        //支払条件手形回数
        $strSelectSQL .= ",      URI.SHR_JKN_TGT_KIN" . "\r\n";
        //支払条件手形金額
        $strSelectSQL .= ",      URI.SHR_JKN_KRJ_KAI" . "\r\n";
        //支払条件クレジット回数
        $strSelectSQL .= ",      URI.SHR_JKN_KRJ_KIN" . "\r\n";
        //支払条件クレジット金額
        $strSelectSQL .= ",      URI.TRA_CAR_PRC_SUM" . "\r\n";
        //下取車買取価格
        $strSelectSQL .= ",      URI.TRA_CAR_STI_SUM" . "\r\n";
        //下取車査定価格
        $strSelectSQL .= ",     URI.JIDOUSYA_ZEI" . "\r\n";
        //税金自動車税
        $strSelectSQL .= ",     URI.SYARYOU_ZEI" . "\r\n";
        //税金車両税
        $strSelectSQL .= ",     URI.EAKON_ZEI" . "\r\n";
        //税金エアコン税
        $strSelectSQL .= ",     URI.SUTEREO_ZEI" . "\r\n";
        //税金ステレオ税
        $strSelectSQL .= ",     URI.JYURYO_ZEI" . "\r\n";
        //税金重量税
        $strSelectSQL .= ",     URI.SHZ_KEI" . "\r\n";
        //税金消費税
        $strSelectSQL .= ",     URI.JIBAI_HOK_RYO" . "\r\n";
        //自賠責保険料
        $strSelectSQL .= ",     URI.OPTHOK_RYO" . "\r\n";
        //任意保険料
        $strSelectSQL .= ",     URI.HNB_TES_GKU" . "\r\n";
        //販売手数料額
        $strSelectSQL .= ",     URI.HNB_SHZ" . "\r\n";
        //販売消費税
        $strSelectSQL .= ",     URI.HONBU_FTK" . "\r\n";
        //本部負担金
        $strSelectSQL .= ",     URI.UKM_SNY_TES" . "\r\n";
        //打込金収入手数料
        $strSelectSQL .= ",     URI.UKM_SINSEI_SYR" . "\r\n";
        //打込金申請奨励金
        $strSelectSQL .= ",     URI.KAP_TES_SGK" . "\r\n";
        //割賦手数料差額
        $strSelectSQL .= ",     URI.ETC_SKI_RYO" . "\r\n";
        //その他紹介料
        $strSelectSQL .= ",     URI.SRY_GENKAI_RIE" . "\r\n";
        //車両F号限界利益
        $strSelectSQL .= ",     URI.GNK_HJN_PCS" . "\r\n";
        //原価標準原価
        $strSelectSQL .= ",     URI.GNK_SIT_URI_SKI" . "\r\n";
        //原価下取車売上仕切
        $strSelectSQL .= ",     URI.CKO_BAI_SIT_KIN" . "\r\n";
        //中古車売車下取価格
        $strSelectSQL .= ",     URI.CKO_BAI_SATEI" . "\r\n";
        //中古車売車査定価格
        $strSelectSQL .= ",     URI.CKO_SAI_MITUMORI" . "\r\n";
        //中古車再生見積価格
        $strSelectSQL .= ",     URI.CKO_SYOGAKARI" . "\r\n";
        //中古車諸掛
        $strSelectSQL .= ",     URI.CKO_MIKEI_JDO_KIN" . "\r\n";
        //中古車未経過自動車税金額
        $strSelectSQL .= ",     URI.CKO_MIKEI_JIBAI_KIN" . "\r\n";
        //中古車未経過自賠責金額
        //strSelectSQL.Append(",     URI.URI_GYOSYA" & vbCrLf)          //業者名
        $strSelectSQL .= ",     URI.GYO_NAME  " . "\r\n";
        //業者名
        $strSelectSQL .= ",     URI.KYK_HNS" . "\r\n";
        //契約店
        $strSelectSQL .= ",     URI.TOU_HNS" . "\r\n";
        //登録店
        $strSelectSQL .= ",     URI.TOK_KEI_KHN_MGN" . "\r\n";
        //特約店契約基本マージン
        $strSelectSQL .= ",     URI.TOK_KEI_RUI_MGN" . "\r\n";
        //特約店契約累積マージン
        $strSelectSQL .= ",     URI.TOK_KEI_KHN_SYR" . "\r\n";
        //特約店契約拡販奨励金
        $strSelectSQL .= ",     (CASE WHEN NVL(URI.CHUMON_KB,' ') = 'A' THEN URI.KICK_BACK ELSE 0 END)" . "\r\n";
        //ｷｯｸバック
        $strSelectSQL .= ",     (CASE WHEN NVL(URI.CHUMON_KB,' ') = 'A' THEN URI.NEBIKI_RT ELSE 0 END)" . "\r\n";
        //値引率
        $strSelectSQL .= ",     (CASE WHEN NVL(URI.CHUMON_KB,' ') = 'A' THEN URI.KIJUN_NEBIKI_RT ELSE 0 END)" . "\r\n";
        //基準値引率
        $strSelectSQL .= ",     URI.UPD_DATE" . "\r\n";
        $strSelectSQL .= ",     URI.CREATE_DATE" . "\r\n";
        //2006/08/23 UPDATE START
        $strSelectSQL .= ",     URI.RCY_YOT_KIN" . "\r\n";
        //リサイクル預託金
        $strSelectSQL .= ",     URI.RCY_SKN_KAN_HI" . "\r\n";
        //リサイクル資金管理費
        $strSelectSQL .= ",     URI.KICK_BACK" . "\r\n";
        //キックバック
        //2006/08/30に売上ﾃﾞｰﾀ追加分
        $strSelectSQL .= ",     DECODE(URI.TOU_SYH_KJN_GK,NULL,NULL,URI.TOU_SYH_KJN_GK)" . "\r\n";
        //登録諸費用基準(K54300)
        $strSelectSQL .= ",     DECODE(URI.SYUEKI_SYOKEI,NULL,NULL,URI.SYUEKI_SYOKEI)" . "\r\n";
        //収益小計
        //2006/08/30 update
        $strSelectSQL .= ",     URI.HOUTEIH_SIT" . "\r\n";
        //法定費
        $strSelectSQL .= ",     URI.FHZ_NBK" . "\r\n";
        //付属品値引
        $strSelectSQL .= ",     URI.TKB_KSH_NBK" . "\r\n";
        //特別架装品値引
        //2006/08/23 UPDATE END
        //TODO 2006/12/08 UPDATE STart
        $strSelectSQL .= " , " . $this->ClsComFnc->FncSqlNv($strUpdUser);
        //ユーザID
        $strSelectSQL .= " , " . $this->ClsComFnc->FncSqlNv($strUpdApp);
        //アプリケーション
        $strSelectSQL .= " , " . $this->ClsComFnc->FncSqlNv($strUpdCltNM);
        //マシン名
        //2006/12/08 UPDATE End
        //2009/12/21 INS Start R4連携集計システムのために追加    '解約日
        $strSelectSQL .= ",           URI.TRA_CAR_RCYYTK_SUM" . "\r\n";
        $strSelectSQL .= ",           URI.KAP_GKN" . "\r\n";
        $strSelectSQL .= ",           URI.TOU_SYH_KEN" . "\r\n";
        $strSelectSQL .= ",           URI.TOU_SYH_SYAKEN" . "\r\n";
        $strSelectSQL .= ",           URI.TOU_SYH_SYAKO_SYO" . "\r\n";
        $strSelectSQL .= ",           URI.TOU_SYH_NOUSYA" . "\r\n";
        $strSelectSQL .= ",           URI.TOU_SYH_SIT_TTK" . "\r\n";
        $strSelectSQL .= ",           URI.TOU_SYH_SATEI" . "\r\n";
        $strSelectSQL .= ",           URI.TOU_SYH_JIKOU" . "\r\n";
        $strSelectSQL .= ",           URI.TOU_SYH_ETC" . "\r\n";
        $strSelectSQL .= ",           URI.HOUTEIH_KEN" . "\r\n";
        $strSelectSQL .= ",           URI.HOUTEIH_SYAKEN" . "\r\n";
        $strSelectSQL .= ",           URI.HOUTEIH_SYAKO_SYO" . "\r\n";
        $strSelectSQL .= ",           URI.PENALTY" . "\r\n";
        $strSelectSQL .= ",           URI.EGO_GAI_SYUEKI" . "\r\n";
        $strSelectSQL .= ",           URI.SAI_SONEKI" . "\r\n";
        $strSelectSQL .= ",           URI.JAF" . "\r\n";
        $strSelectSQL .= ",           URI.PACK_DE_753" . "\r\n";
        $strSelectSQL .= ",           URI.PACK_DE_MENTE" . "\r\n";
        $strSelectSQL .= "      ,URI.KYK_CSRRANK" . "\r\n";
        $strSelectSQL .= "      ,URI.SIY_CSRRANK" . "\r\n";
        //''strSQL.Append("      ,URI.UC_KENSU_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.MI_JISSEKI_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.TOU_JISSEKI_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.TA_KYK_JI_TRK_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.JI_KYK_TA_TRK_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.MAKER_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.FUKUSHI_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.SYAMEI_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.URI_JISSEKI_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.LEASE_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.SEAVICE_CAR_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.SAIBAI_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.KARUTE_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.TRKKB_URI_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.TRKKB_TOU_FLG" & vbCrLf)
        //''strSQL.Append("      ,URI.TRKKB_SONTA_FLG" & vbCrLf)
        $strSelectSQL .= "      ,URI.UC_KENSU" . "\r\n";
        $strSelectSQL .= "      ,URI.MI_JISSEKI_DAISU" . "\r\n";
        $strSelectSQL .= "      ,URI.TOU_JISSEKI_DAISU" . "\r\n";
        $strSelectSQL .= "      ,URI.TA_KYK_JI_TRK_DAISU" . "\r\n";
        $strSelectSQL .= "      ,URI.JI_KYK_TA_TRK_DAISU" . "\r\n";
        $strSelectSQL .= "      ,URI.MAKER_DAISU" . "\r\n";
        $strSelectSQL .= "      ,URI.FUKUSHI_DAISU" . "\r\n";
        $strSelectSQL .= "      ,URI.SYAMEI_DAISU" . "\r\n";
        $strSelectSQL .= "      ,URI.URI_JISSEKI_DAISU" . "\r\n";
        $strSelectSQL .= "      ,URI.LEASE_DAISU" . "\r\n";
        $strSelectSQL .= "      ,URI.SEAVICE_CAR_DAISU" . "\r\n";
        $strSelectSQL .= "      ,URI.SAIBAI_DAISU" . "\r\n";
        $strSelectSQL .= "      ,URI.KARUTE_DAISU" . "\r\n";
        $strSelectSQL .= "      ,URI.TRKKB_URI_DAISU" . "\r\n";
        $strSelectSQL .= "      ,URI.TRKKB_TOU_DAISU" . "\r\n";
        $strSelectSQL .= "      ,URI.TRKKB_SONTA_DAISU" . "\r\n";
        $strSelectSQL .= "      ,URI.KAIYAKU_DAISU" . "\r\n";
        $strSelectSQL .= "      ,URI.CMN_NO" . "\r\n";
        //2009/12/21 INS End
        if ($strActmode == "S") {
            $strSelectSQL .= "FROM  HSCURI_S_VW URI" . "\r\n";
            $strSelectSQL .= ",     HJYOUHEN_S JYO" . "\r\n";
        } else {
            $strSelectSQL .= "FROM  HSCURI_VW URI" . "\r\n";
            $strSelectSQL .= ",     HJYOUHEN JYO" . "\r\n";
        }
        $strSelectSQL .= ",     HBUSYO   BUY" . "\r\n";
        //条件変更履歴データの注文書番号ごとのMAXの履歴番号を取得する
        $strSelectSQL .= ",     (SELECT CMN_NO" . "\r\n";
        $strSelectSQL .= "       ,      MAX(JKN_HKO_RIRNO) MAX_RIRNO" . "\r\n";
        if ($strActmode == "S") {
            $strSelectSQL .= "       FROM   HJYOUHEN_S" . "\r\n";
        } else {
            $strSelectSQL .= "       FROM   HJYOUHEN" . "\r\n";
        }

        $strSelectSQL .= "       WHERE  KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSelectSQL .= "       GROUP BY CMN_NO) JYO_NO" . "\r\n";

        $strSQL .= $strSelectSQL;

        $strSQL .= "WHERE URI.CMN_NO = JYO.CMN_NO" . "\r\n";
        $strSQL .= "  AND DECODE(URI.URI_BUSYO_CD,'168',URI.URK_BUSYO_CD,URI.URI_BUSYO_CD) <> DECODE(JYO.URI_BUSYO_CD,'168',JYO.URK_BUSYO_CD,JYO.URI_BUSYO_CD)" . "\r\n";
        $strSQL .= "  AND JYO.CMN_NO = JYO_NO.CMN_NO" . "\r\n";
        $strSQL .= "  AND JYO.JKN_HKO_RIRNO = JYO_NO.MAX_RIRNO" . "\r\n";
        $strSQL .= "  AND DECODE(JYO.URI_BUSYO_CD,'168',JYO.URK_BUSYO_CD,JYO.URI_BUSYO_CD) = BUY.BUSYO_CD" . "\r\n";
        $strSQL .= "  AND URI.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL .= " UNION " . "\r\n";

        $strSQL .= $strSelectSQL . "\r\n";

        $strSQL .= "WHERE URI.CMN_NO = JYO.CMN_NO" . "\r\n";
        $strSQL .= "  AND URI.URI_TANNO <> JYO.URI_TANNO" . "\r\n";
        $strSQL .= "  AND JYO.CMN_NO = JYO_NO.CMN_NO" . "\r\n";
        $strSQL .= "  AND JYO.JKN_HKO_RIRNO = JYO_NO.MAX_RIRNO" . "\r\n";
        $strSQL .= "  AND JYO.KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSQL .= "  AND DECODE(URI.URI_BUSYO_CD,'168',URI.URK_BUSYO_CD,URI.URI_BUSYO_CD) = BUY.BUSYO_CD" . "\r\n";
        $strSQL .= "  AND URI.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL = str_replace("@KEIJYOBI", $strSyoriYM, $strSQL);
        return $strSQL;
    }

}