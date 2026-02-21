<?php
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;
use App\Model\JKSYS\Component\ClsComFncJKSYS;

class FrmSyukkouSeikyuInfoEnt extends ClsComDb
{
    public $ClsComFncJKSYS;
    //処理年月取得SQL
    public function selShoriYMSQL()
    {
        $strSQL = "";
        $strSQL .= "SELECT SYORI_YM " . " \r\n";
        $strSQL .= "FROM   JKCONTROLMST " . " \r\n";
        $strSQL .= "WHERE  ID = '01' " . " \r\n";

        return parent::select($strSQL);
    }

    //出向先コンボリスト取得SQL
    public function selComSyukkou()
    {
        $strSQL = "";
        $strSQL .= "SELECT KUB.KUBUN_CD " . " \r\n";
        $strSQL .= "      ,BUM.BUSYO_NM " . " \r\n";
        $strSQL .= "FROM   JKKUBUNMST KUB " . " \r\n";
        $strSQL .= "      ,JKBUMON BUM " . " \r\n";
        $strSQL .= "WHERE  KUB.KUBUN_CD = BUM.BUSYO_CD " . " \r\n";
        $strSQL .= " AND   KUB.KUBUN_ID = 'JKSKOBSY' " . " \r\n";
        $strSQL .= "ORDER BY KUB.KUBUN_CD " . " \r\n";

        return parent::select($strSQL);
    }

    //出向社員請求明細データ取得SQL
    public function selSyukkouSeikyuSQL($postdata)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSql = "";
        $strSql .= "SELECT BUM.BUSYO_NM" . "\r\n";
        $strSql .= "      ,SKO.SYAIN_NO" . "\r\n";
        $strSql .= "      ,SYA.SYAIN_NM" . "\r\n";
        $strSql .= "      ,SKO.KIHONKYU" . "\r\n";
        $strSql .= "      ,SKO.CHOUSEIKYU" . "\r\n";
        $strSql .= "      ,SKO.SYOKUMU_TEATE" . "\r\n";
        $strSql .= "      ,SKO.KAZOKU_TEATE" . "\r\n";
        $strSql .= "      ,SKO.TUKIN_TEATE" . "\r\n";
        $strSql .= "      ,SKO.SYARYOU_TEATE" . "\r\n";
        $strSql .= "      ,SKO.SYOUREIKIN" . "\r\n";
        $strSql .= "      ,SKO.ZANGYOU_TEATE" . "\r\n";
        $strSql .= "      ,SKO.SYUKKOU_TEATE " . "\r\n";
        $strSql .= "      ,SKO.JIKANSA_TEATE" . "\r\n";
        $strSql .= "      ,SKO.KENKO_HKN_RYO" . "\r\n";
        $strSql .= "       ,SKO.KAIGO_HKN_RYO" . "\r\n";
        $strSql .= "      ,SKO.KOUSEINENKIN" . "\r\n";
        $strSql .= "      ,SKO.JIDOU_TEATE" . "\r\n";
        $strSql .= "      ,SKO.KOYOU_HKN_RYO" . "\r\n";
        $strSql .= "      ,SKO.TAISYOKU_NENKIN" . "\r\n";
        $strSql .= "      ,SKO.ROUSAI_UWA_HKN_RYO" . "\r\n";
        $strSql .= "      ,SKO.BNS_GK" . "\r\n";
        $strSql .= "      ,SKO.BNS_KENKO_HKN_RYO" . "\r\n";
        $strSql .= "      ,SKO.BNS_KAIGO_HKN_RYO" . "\r\n";
        $strSql .= "      ,SKO.BNS_KOUSEI_NENKIN" . "\r\n";
        $strSql .= "      ,SKO.BNS_JIDOU_TEATE " . "\r\n";
        $strSql .= "      ,SKO.BNS_KOYOU_HOKEN" . "\r\n";
        $strSql .= " FROM   JKSKOSEIKYUMEISAI SKO" . "\r\n";
        $strSql .= "       INNER JOIN JKSYAIN SYA" . "\r\n";
        $strSql .= "           ON    SKO.SYAIN_NO = SYA.SYAIN_NO " . "\r\n";
        $strSql .= "       INNER JOIN JKBUMON BUM " . "\r\n";
        $strSql .= "           ON    SKO.BUSYO_CD = BUM.BUSYO_CD" . "\r\n";
        $strSql .= "WHERE  SKO.TAISYOU_YM = '" . $postdata['taishoYM'] . "'" . " \r\n";
        if ($this->ClsComFncJKSYS->FncNv($postdata['comSyukkou']) != '') {
            $strSql .= "AND    SKO.BUSYO_CD = '" . $postdata['comSyukkou'] . "'" . " \r\n";
        }
        $strSql .= "ORDER BY  SKO.BUSYO_CD, SKO.SYAIN_NO" . " \r\n";

        return parent::select($strSql);
    }

    //更新日付取得SQL
    public function selUpdDate($taisyouym, $comSyukkou)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "SELECT TO_CHAR(MAX(SKO.UPD_DATE),'YYYY/MM/DD HH24:MI:SS') UPD_DATE " . " \r\n";
        $strSQL .= "FROM   JKSKOSEIKYUMEISAI SKO " . " \r\n";
        $strSQL .= "WHERE  SKO.TAISYOU_YM = '" . $taisyouym . "' " . " \r\n";
        if ($this->ClsComFncJKSYS->FncNv($comSyukkou) != '') {
            $strSQL .= "AND    SKO.BUSYO_CD = '" . $comSyukkou . "'" . " \r\n";
        }
        return parent::select($strSQL);
    }

    //出向社員請求明細データ登録SQL
    public function updSyukkouSeikyu($data, $taisyouym)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= " UPDATE JKSKOSEIKYUMEISAI" . "\r\n";
        $strSQL .= " SET    KIHONKYU = '" . $this->ClsComFncJKSYS->FncNz($data['KIHONKYU']) . "'" . "\r\n";
        $strSQL .= "       ,CHOUSEIKYU = '" . $this->ClsComFncJKSYS->FncNz($data['CHOUSEIKYU']) . "'" . "\r\n";
        $strSQL .= "       ,SYOKUMU_TEATE = '" . $this->ClsComFncJKSYS->FncNz($data['SYOKUMU_TEATE']) . "'" . "\r\n";
        $strSQL .= "       ,KAZOKU_TEATE = '" . $this->ClsComFncJKSYS->FncNz($data['KAZOKU_TEATE']) . "'" . "\r\n";
        $strSQL .= "       ,TUKIN_TEATE = '" . $this->ClsComFncJKSYS->FncNz($data['TUKIN_TEATE']) . "'" . "\r\n";
        $strSQL .= "       ,SYARYOU_TEATE = '" . $this->ClsComFncJKSYS->FncNz($data['SYARYOU_TEATE']) . "'" . "\r\n";
        $strSQL .= "       ,SYOUREIKIN = '" . $this->ClsComFncJKSYS->FncNz($data['SYOUREIKIN']) . "'" . "\r\n";
        $strSQL .= "       ,ZANGYOU_TEATE = '" . $this->ClsComFncJKSYS->FncNz($data['ZANGYOU_TEATE']) . "'" . "\r\n";
        $strSQL .= "       ,SYUKKOU_TEATE = '" . $this->ClsComFncJKSYS->FncNz($data['SYUKKOU_TEATE']) . "'" . "\r\n";
        $strSQL .= "       ,JIKANSA_TEATE = '" . $this->ClsComFncJKSYS->FncNz($data['JIKANSA_TEATE']) . "'" . "\r\n";
        $strSQL .= "       ,KENKO_HKN_RYO = '" . $this->ClsComFncJKSYS->FncNz($data['KENKO_HKN_RYO']) . "'" . "\r\n";
        $strSQL .= "       ,KAIGO_HKN_RYO = '" . $this->ClsComFncJKSYS->FncNz($data['KAIGO_HKN_RYO']) . "'" . "\r\n";
        $strSQL .= "       ,KOUSEINENKIN = '" . $this->ClsComFncJKSYS->FncNz($data['KOUSEINENKIN']) . "'" . "\r\n";
        $strSQL .= "       ,JIDOU_TEATE = '" . $this->ClsComFncJKSYS->FncNz($data['JIDOU_TEATE']) . "'" . "\r\n";
        $strSQL .= "       ,KOYOU_HKN_RYO = '" . $this->ClsComFncJKSYS->FncNz($data['KOYOU_HKN_RYO']) . "'" . "\r\n";
        $strSQL .= "       ,TAISYOKU_NENKIN = '" . $this->ClsComFncJKSYS->FncNz($data['TAISYOKU_NENKIN']) . "'" . "\r\n";
        $strSQL .= "       ,ROUSAI_UWA_HKN_RYO = '" . $this->ClsComFncJKSYS->FncNz($data['ROUSAI_UWA_HKN_RYO']) . "'" . "\r\n";
        $strSQL .= "       ,BNS_GK = '" . $this->ClsComFncJKSYS->FncNz($data['BNS_GK']) . "'" . "\r\n";
        $strSQL .= "       ,BNS_KENKO_HKN_RYO = '" . $this->ClsComFncJKSYS->FncNz($data['BNS_KENKO_HKN_RYO']) . "'" . "\r\n";
        $strSQL .= "       ,BNS_KAIGO_HKN_RYO = '" . $this->ClsComFncJKSYS->FncNz($data['BNS_KAIGO_HKN_RYO']) . "'" . "\r\n";
        $strSQL .= "       ,BNS_KOUSEI_NENKIN = '" . $this->ClsComFncJKSYS->FncNz($data['BNS_KOUSEI_NENKIN']) . "'" . "\r\n";
        $strSQL .= "       ,BNS_JIDOU_TEATE = '" . $this->ClsComFncJKSYS->FncNz($data['BNS_JIDOU_TEATE']) . "'" . "\r\n";
        $strSQL .= "       ,BNS_KOYOU_HOKEN = '" . $this->ClsComFncJKSYS->FncNz($data['BNS_KOYOU_HOKEN']) . "'" . "\r\n";
        $strSQL .= "       ,UPD_DATE = SYSDATE" . "\r\n";
        $strSQL .= "       ,UPD_SYA_CD = '" . $this->GS_LOGINUSER['strUserID'] . "'" . "\r\n";
        $strSQL .= "       ,UPD_PRG_ID = 'SyukkouSeikyuInfoEnt'" . "\r\n";
        $strSQL .= "       ,UPD_CLT_NM = '" . $this->GS_LOGINUSER['strClientNM'] . "'" . "\r\n";
        $strSQL .= "   WHERE TAISYOU_YM = '" . $taisyouym . "'" . "\r\n";
        $strSQL .= "   AND    SYAIN_NO = '" . $data['SYAIN_NO'] . "'" . "\r\n";

        return parent::update($strSQL);
    }

}
