<?php
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;

class FrmFurikaeHiritsuEnt extends ClsComDb
{
    //人事コントロールマスタ
    public function FncGetJKCMST()
    {
        $strSQL = "";
        $strSQL .= " SELECT SYORI_YM" . " \r\n";
        $strSQL .= " FROM JKCONTROLMST JKC" . " \r\n";
        $strSQL .= " WHERE JKC.ID = '01' " . " \r\n";

        return parent::select($strSQL);
    }

    //人件費振替比率データ
    public function FncGetJKFHDAT($dtpTaisyouYM)
    {
        $strSQL = "";
        $strSQL .= "SELECT TAISYOU_YM" . " \r\n";
        $strSQL .= "       ,TO_CHAR(BNS_MITUMORI, 'FM9,999,999,999') BNS_MITUMORI" . " \r\n";
        $strSQL .= "       ,TO_CHAR(KENKO_HKN_RYO, 'FM9,999,999,999') KENKO_HKN_RYO" . " \r\n";
        $strSQL .= "       ,TO_CHAR(KAIGO_HKN_RYO, 'FM9,999,999,999') KAIGO_HKN_RYO" . " \r\n";
        $strSQL .= "       ,TO_CHAR(KOUSEINENKIN, 'FM9,999,999,999') KOUSEINENKIN" . " \r\n";
        $strSQL .= "       ,TO_CHAR(JIDOUTEATE, 'FM9,999,999,999') JIDOUTEATE" . " \r\n";
        $strSQL .= "       ,TO_CHAR(KOYOU_HKN_RYO, 'FM9,999,999,999') KOYOU_HKN_RYO" . " \r\n";
        $strSQL .= "       ,TO_CHAR(ROUSAI_HKN_RYO, 'FM9,999,999,999') ROUSAI_HKN_RYO" . " \r\n";
        $strSQL .= "       ,TO_CHAR(TAISYOKUTEATE, 'FM9,999,999,999') TAISYOKUTEATE" . " \r\n";
        $strSQL .= "       ,TO_CHAR(UPD_DATE,'YYYY/MM/DD HH24:MI:SS') UPD_DATE" . " \r\n";
        $strSQL .= "       ,TO_CHAR(KYK_BNS_MITUMORI, 'FM9,999,999,999') KYK_BNS_MITUMORI" . " \r\n";
        $strSQL .= "       ,TO_CHAR(KYK_KENKO_HKN_RYO, 'FM9,999,999,999') KYK_KENKO_HKN_RYO" . " \r\n";
        $strSQL .= "       ,TO_CHAR(KYK_KAIGO_HKN_RYO, 'FM9,999,999,999') KYK_KAIGO_HKN_RYO" . " \r\n";
        $strSQL .= "       ,TO_CHAR(KYK_KOUSEINENKIN, 'FM9,999,999,999') KYK_KOUSEINENKIN" . " \r\n";
        $strSQL .= "       ,TO_CHAR(KYK_JIDOUTEATE, 'FM9,999,999,999') KYK_JIDOUTEATE" . " \r\n";
        $strSQL .= " FROM JKJINKENHIFURIKAEHIRITU JKFH" . " \r\n";
        $strSQL .= " WHERE JKFH.TAISYOU_YM = '@TAISYOU_YM' " . " \r\n";

        $strSQL = str_replace("@TAISYOU_YM", $dtpTaisyouYM, $strSQL);

        return parent::select($strSQL);
    }

    //人件費振替比率データ(Delete)
    public function FncDelJKFHDAT($dtpTaisyouYM)
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM JKJINKENHIFURIKAEHIRITU JKFH" . " \r\n";
        $strSQL .= "WHERE  JKFH.TAISYOU_YM = '@TAISYOU_YM'" . " \r\n";

        $strSQL = str_replace("@TAISYOU_YM", $dtpTaisyouYM, $strSQL);

        return parent::delete($strSQL);
    }

    //人件費振替比率データ(Insert)
    public function FncInsJKFHDAT($postData)
    {
        $strSQL = "";
        $strSQL .= " INSERT INTO JKJINKENHIFURIKAEHIRITU( " . "\r\n";
        $strSQL .= "       TAISYOU_YM" . "\r\n";
        $strSQL .= "      ,BNS_MITUMORI" . "\r\n";
        $strSQL .= "     ,KENKO_HKN_RYO" . "\r\n";
        $strSQL .= "     ,KAIGO_HKN_RYO" . "\r\n";
        $strSQL .= "     ,KOUSEINENKIN" . "\r\n";
        $strSQL .= "     ,JIDOUTEATE" . "\r\n";
        $strSQL .= "      ,KOYOU_HKN_RYO" . "\r\n";
        $strSQL .= "     ,ROUSAI_HKN_RYO" . "\r\n";
        $strSQL .= "     ,TAISYOKUTEATE" . "\r\n";
        $strSQL .= "     ,KYK_BNS_MITUMORI" . "\r\n";
        $strSQL .= "     ,KYK_KENKO_HKN_RYO" . "\r\n";
        $strSQL .= "     ,KYK_KAIGO_HKN_RYO" . "\r\n";
        $strSQL .= "     ,KYK_KOUSEINENKIN" . "\r\n";
        $strSQL .= "     ,KYK_JIDOUTEATE" . "\r\n";
        $strSQL .= "     ,CREATE_DATE" . "\r\n";
        $strSQL .= "     ,CRE_SYA_CD" . "\r\n";
        $strSQL .= "     ,CRE_PRG_ID" . "\r\n";
        $strSQL .= "     ,UPD_DATE" . "\r\n";
        $strSQL .= "     ,UPD_SYA_CD" . "\r\n";
        $strSQL .= "     ,UPD_PRG_ID" . "\r\n";
        $strSQL .= "     ,UPD_CLT_NM" . "\r\n";
        $strSQL .= "      ) VALUES (" . "\r\n";
        $strSQL .= "     '@TAISYOU_YM'" . "\r\n";
        $strSQL .= "      ,'@BNS_MITUMORI'" . "\r\n";
        $strSQL .= "     ,'@KENKO_HKN_RYO'" . "\r\n";
        $strSQL .= "    ,'@KAIGO_HKN_RYO'" . "\r\n";
        $strSQL .= "     ,'@KOUSEINENKIN'" . "\r\n";
        $strSQL .= "    ,'@JIDOUTEATE'" . "\r\n";
        $strSQL .= "     ,'@KOYOU_HKN_RYO'" . "\r\n";
        $strSQL .= "     ,'@ROUSAI_HKN_RYO'" . "\r\n";
        $strSQL .= "     ,'@TAISYOKUTEATE'" . "\r\n";
        $strSQL .= "     ,'@KYK_BNS'" . "\r\n";
        $strSQL .= "    ,'@KYK_KENKO_HKN'" . "\r\n";
        $strSQL .= "     ,'@KYK_KAIGO_HKN'" . "\r\n";
        $strSQL .= "    ,'@KYK_KOUSEI'" . "\r\n";
        $strSQL .= "    ,'@KYK_JIDOU'" . "\r\n";
        $strSQL .= "     , SYSDATE" . "\r\n";
        $strSQL .= "     , '@SYA_CD'" . "\r\n";
        $strSQL .= ",'FurikaeHiritsuEnt'" . "\r\n";
        $strSQL .= "     , SYSDATE" . "\r\n";
        $strSQL .= "     , '@SYA_CD'" . "\r\n";
        $strSQL .= ",'FurikaeHiritsuEnt'" . "\r\n";
        $strSQL .= "     , '@CLT_NM'" . "\r\n";
        $strSQL .= "     )" . "\r\n";

        $strSQL = str_replace("@TAISYOU_YM", $postData["dtpTaisyouYM"], $strSQL);
        $strSQL = str_replace("@BNS_MITUMORI", $postData["txtSyouyo"], $strSQL);
        $strSQL = str_replace("@KENKO_HKN_RYO", $postData["txtKenkou"], $strSQL);
        $strSQL = str_replace("@KAIGO_HKN_RYO", $postData["txtKaigo"], $strSQL);
        $strSQL = str_replace("@KOUSEINENKIN", $postData["txtKouseiNenkin"], $strSQL);
        $strSQL = str_replace("@JIDOUTEATE", $postData["txtJidouTeate"], $strSQL);
        $strSQL = str_replace("@KOYOU_HKN_RYO", $postData["txtKoyou"], $strSQL);
        $strSQL = str_replace("@ROUSAI_HKN_RYO", $postData["txtRousai"], $strSQL);
        $strSQL = str_replace("@TAISYOKUTEATE", $postData["txtTaisyoku"], $strSQL);
        $strSQL = str_replace("@KYK_BNS", $postData["txtKYKSyouyo"], $strSQL);
        $strSQL = str_replace("@KYK_KENKO_HKN", $postData["txtKYKKenkou"], $strSQL);
        $strSQL = str_replace("@KYK_KAIGO_HKN", $postData["txtKYKKaigo"], $strSQL);
        $strSQL = str_replace("@KYK_KOUSEI", $postData["txtKYKKouseiNenkin"], $strSQL);
        $strSQL = str_replace("@KYK_JIDOU", $postData["txtKYKJidouTeate"], $strSQL);
        $strSQL = str_replace("@SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return parent::insert($strSQL);
    }

    //人件費振替比率データ(Update)
    public function FncUpdJKFHDAT($postData)
    {
        $strSQL = "";
        $strSQL .= "UPDATE JKJINKENHIFURIKAEHIRITU " . "\r\n";
        $strSQL .= "SET    BNS_MITUMORI = '@BNS_MITUMORI'" . "\r\n";
        $strSQL .= ",      KENKO_HKN_RYO = '@KENKO_HKN_RYO'" . "\r\n";
        $strSQL .= ",      KAIGO_HKN_RYO = '@KAIGO_HKN_RYO'" . "\r\n";
        $strSQL .= ",      KOUSEINENKIN = '@KOUSEINENKIN'" . "\r\n";
        $strSQL .= ",      JIDOUTEATE = '@JIDOUTEATE'" . "\r\n";
        $strSQL .= ",      KOYOU_HKN_RYO = '@KOYOU_HKN_RYO'" . "\r\n";
        $strSQL .= ",      ROUSAI_HKN_RYO = '@ROUSAI_HKN_RYO'" . "\r\n";
        $strSQL .= ",      TAISYOKUTEATE = '@TAISYOKUTEATE'" . "\r\n";
        $strSQL .= ",      KYK_BNS_MITUMORI = '@KYK_BNS'" . "\r\n";
        $strSQL .= ",      KYK_KENKO_HKN_RYO = '@KYK_KENKO_HKN'" . "\r\n";
        $strSQL .= ",      KYK_KAIGO_HKN_RYO = '@KYK_KAIGO_HKN'" . "\r\n";
        $strSQL .= ",      KYK_KOUSEINENKIN = '@KYK_KOUSEI'" . "\r\n";
        $strSQL .= ",      KYK_JIDOUTEATE = '@KYK_JIDOU'" . "\r\n";
        $strSQL .= ",      UPD_DATE = SYSDATE" . "\r\n";
        $strSQL .= ",      UPD_SYA_CD = '@SYA_CD'" . "\r\n";
        $strSQL .= ",      UPD_PRG_ID = 'FurikaeHiritsuEnt'" . "\r\n";
        $strSQL .= ",      UPD_CLT_NM = '@CLT_NM'" . "\r\n";
        $strSQL .= "WHERE  TAISYOU_YM = '@TAISYOU_YM'" . "\r\n";

        $strSQL = str_replace("@TAISYOU_YM", $postData["dtpTaisyouYM"], $strSQL);
        $strSQL = str_replace("@BNS_MITUMORI", $postData["txtSyouyo"], $strSQL);
        $strSQL = str_replace("@KENKO_HKN_RYO", $postData["txtKenkou"], $strSQL);
        $strSQL = str_replace("@KAIGO_HKN_RYO", $postData["txtKaigo"], $strSQL);
        $strSQL = str_replace("@KOUSEINENKIN", $postData["txtKouseiNenkin"], $strSQL);
        $strSQL = str_replace("@JIDOUTEATE", $postData["txtJidouTeate"], $strSQL);
        $strSQL = str_replace("@KOYOU_HKN_RYO", $postData["txtKoyou"], $strSQL);
        $strSQL = str_replace("@ROUSAI_HKN_RYO", $postData["txtRousai"], $strSQL);
        $strSQL = str_replace("@TAISYOKUTEATE", $postData["txtTaisyoku"], $strSQL);
        $strSQL = str_replace("@KYK_BNS", $postData["txtKYKSyouyo"], $strSQL);
        $strSQL = str_replace("@KYK_KENKO_HKN", $postData["txtKYKKenkou"], $strSQL);
        $strSQL = str_replace("@KYK_KAIGO_HKN", $postData["txtKYKKaigo"], $strSQL);
        $strSQL = str_replace("@KYK_KOUSEI", $postData["txtKYKKouseiNenkin"], $strSQL);
        $strSQL = str_replace("@KYK_JIDOU", $postData["txtKYKJidouTeate"], $strSQL);
        $strSQL = str_replace("@SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return parent::update($strSQL);
    }

}
