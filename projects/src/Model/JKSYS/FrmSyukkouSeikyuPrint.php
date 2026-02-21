<?php
// 共通クラスの読込み
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class FrmSyukkouSeikyuPrint extends ClsComDb
{
    //人事コントロールマスタ取得SQL
    function fncJinjiCtlMstSQL()
    {
        $strSQL = "  SELECT ID" . "\r\n";
        $strSQL .= "      , SYORI_YM " . "\r\n";
        $strSQL .= " FROM   JKCONTROLMST  " . "\r\n";
        $strSQL .= " WHERE  ID = '01' ";

        return parent::select($strSQL);
    }

    //出向先コンボデータ取得SQL
    function fncSyukkoSakiComboSQL()
    {
        $strSQL = "  SELECT kb.KUBUN_CD " . "\r\n";
        $strSQL .= "      , bu.BUSYO_NM " . "\r\n";
        $strSQL .= " FROM   JKKUBUNMST kb, JKBUMON bu  " . "\r\n";
        $strSQL .= " WHERE  kb.KUBUN_CD = bu.BUSYO_CD " . "\r\n";
        $strSQL .= " AND    kb.KUBUN_ID = 'JKSKOBSY' " . "\r\n";
        $strSQL .= " ORDER BY kb.KUBUN_CD ";

        return parent::select($strSQL);
    }

    //出向先存在チェックSQL
    function fncGetSyukkoSakiSQL($postData)
    {
        $strSQL = "  SELECT TAISYOU_YM " . "\r\n";
        $strSQL .= " FROM   JKSKOSEIKYUMEISAI  " . "\r\n";
        $strSQL .= " WHERE  TAISYOU_YM = '@TAISYOU_YM' ";

        $strSQL = str_replace("@TAISYOU_YM", $postData['taisyoYM'], $strSQL);

        return parent::select($strSQL);
    }

    //印刷プレビューSQL
    function fncGetPreviewDataSQL($postData)
    {
        $strSQL = "  SELECT ss.BUSYO_CD  " . "\r\n";
        $strSQL .= "      , bu.BUSYO_NM || '　御中' AS BUSYO_NM " . "\r\n";
        $strSQL .= "      , substr(ss.TAISYOU_YM, 0, 4) AS NEN " . "\r\n";
        $strSQL .= "      , substr(ss.TAISYOU_YM, 5, 2) AS GETU " . "\r\n";
        $strSQL .= "      , ss.SYAIN_NO " . "\r\n";
        $strSQL .= "      , sy.SYAIN_NM " . "\r\n";
        $strSQL .= "      , ss.KIHONKYU + ss.CHOUSEIKYU + ss.SYOKUMU_TEATE + ss.KAZOKU_TEATE + ss.TUKIN_TEATE + ss.SYARYOU_TEATE + ss.SYOUREIKIN + ss.ZANGYOU_TEATE + ss.SYUKKOU_TEATE + ss.JIKANSA_TEATE AS KOTEI_TINGIN_KEI " . "\r\n";
        $strSQL .= "      , ss.KIHONKYU " . "\r\n";
        $strSQL .= "      , ss.CHOUSEIKYU " . "\r\n";
        $strSQL .= "      , ss.SYOKUMU_TEATE " . "\r\n";
        $strSQL .= "      , ss.KAZOKU_TEATE " . "\r\n";
        $strSQL .= "      , ss.TUKIN_TEATE " . "\r\n";
        $strSQL .= "      , ss.SYARYOU_TEATE " . "\r\n";
        $strSQL .= "      , ss.SYOUREIKIN " . "\r\n";
        $strSQL .= "      , ss.ZANGYOU_TEATE " . "\r\n";
        $strSQL .= "      , ss.SYUKKOU_TEATE " . "\r\n";
        $strSQL .= "      , ss.JIKANSA_TEATE " . "\r\n";
        $strSQL .= "      , ss.KENKO_HKN_RYO + ss.KOUSEINENKIN + ss.JIDOU_TEATE + ss.KOYOU_HKN_RYO + ss.KAIGO_HKN_RYO + ss.TAISYOKU_NENKIN + ss.ROUSAI_UWA_HKN_RYO AS KAIKEI_FUTAN_KEI " . "\r\n";
        $strSQL .= "      , ss.KENKO_HKN_RYO " . "\r\n";
        $strSQL .= "      , ss.KAIGO_HKN_RYO " . "\r\n";
        $strSQL .= "      , ss.KOUSEINENKIN " . "\r\n";
        $strSQL .= "      , ss.JIDOU_TEATE " . "\r\n";
        $strSQL .= "      , ss.KOYOU_HKN_RYO " . "\r\n";
        $strSQL .= "      , ss.TAISYOKU_NENKIN " . "\r\n";
        $strSQL .= "      , ss.ROUSAI_UWA_HKN_RYO " . "\r\n";
        $strSQL .= "      , ss.BNS_GK + ss.BNS_KENKO_HKN_RYO + ss.BNS_KAIGO_HKN_RYO + ss.BNS_KOUSEI_NENKIN + ss.BNS_JIDOU_TEATE + ss.BNS_KOYOU_HOKEN AS BNS_KEI " . "\r\n";
        $strSQL .= "      , ss.BNS_GK " . "\r\n";
        $strSQL .= "      , ss.BNS_KENKO_HKN_RYO " . "\r\n";
        $strSQL .= "      , ss.BNS_KAIGO_HKN_RYO " . "\r\n";
        $strSQL .= "      , ss.BNS_KOUSEI_NENKIN " . "\r\n";
        $strSQL .= "      , ss.BNS_JIDOU_TEATE " . "\r\n";
        $strSQL .= "      , ss.BNS_KOYOU_HOKEN " . "\r\n";
        $strSQL .= "      , ss.KIHONKYU + ss.CHOUSEIKYU + ss.SYOKUMU_TEATE + ss.KAZOKU_TEATE + ss.TUKIN_TEATE + ss.SYARYOU_TEATE + ss.SYOUREIKIN + ss.ZANGYOU_TEATE + ss.SYUKKOU_TEATE + ss.JIKANSA_TEATE + ss.KENKO_HKN_RYO + ss.KAIGO_HKN_RYO + ss.KOUSEINENKIN + ss.JIDOU_TEATE + ss.KOYOU_HKN_RYO + ss.TAISYOKU_NENKIN + ss.ROUSAI_UWA_HKN_RYO + ss.BNS_GK + ss.BNS_KENKO_HKN_RYO + ss.BNS_KAIGO_HKN_RYO + ss.BNS_KOUSEI_NENKIN + ss.BNS_JIDOU_TEATE + ss.BNS_KOYOU_HOKEN AS FUTANKIN_KEI " . "\r\n";
        $strSQL .= " FROM   JKSKOSEIKYUMEISAI ss" . "\r\n";
        $strSQL .= "        LEFT JOIN JKSYAIN sy" . "\r\n";
        $strSQL .= "           ON ss.SYAIN_NO = sy.SYAIN_NO" . "\r\n";
        $strSQL .= "        LEFT JOIN JKBUMON bu" . "\r\n";
        $strSQL .= "           ON ss.BUSYO_CD = bu.BUSYO_CD" . "\r\n";
        $strSQL .= " WHERE  ss.TAISYOU_YM = '@TAISYOU_YM' " . "\r\n";
        if ($postData['busyoCD'] <> '999999') {
            $strSQL .= " AND    bu.BUSYO_CD = '" . $postData['busyoCD'] . "' " . "\r\n";
        }
        $strSQL .= " ORDER BY ss.BUSYO_CD, ss.SYAIN_NO ";

        $strSQL = str_replace("@TAISYOU_YM", $postData['taisyoYM'], $strSQL);

        return parent::select($strSQL);
    }

    //データ取得SQL
    function fncGetDataSQL($postData)
    {
        $strSQL = " SELECT ss.BUSYO_CD " . "\r\n";
        $strSQL .= "        ,bu.BUSYO_NM " . "\r\n";
        $strSQL .= "        ,count(*) AS NINZU " . "\r\n";
        $strSQL .= "        ,SUM(ss.KIHONKYU + ss.CHOUSEIKYU + ss.SYOKUMU_TEATE + ss.KAZOKU_TEATE + ss.TUKIN_TEATE + ss.SYARYOU_TEATE + ss.SYOUREIKIN + ss.ZANGYOU_TEATE + ss.SYUKKOU_TEATE + ss.JIKANSA_TEATE + ss.KENKO_HKN_RYO + ss.KAIGO_HKN_RYO + ss.KOUSEINENKIN + ss.JIDOU_TEATE + ss.KOYOU_HKN_RYO + ss.TAISYOKU_NENKIN + ss.ROUSAI_UWA_HKN_RYO + ss.BNS_GK + ss.BNS_KENKO_HKN_RYO + ss.BNS_KAIGO_HKN_RYO + ss.BNS_KOUSEI_NENKIN + ss.BNS_JIDOU_TEATE + ss.BNS_KOYOU_HOKEN) AS SOU_KINGAKU " . "\r\n";
        $strSQL .= "        ,SUM(ss.KIHONKYU + ss.CHOUSEIKYU + ss.SYOKUMU_TEATE + ss.KAZOKU_TEATE + ss.TUKIN_TEATE + ss.SYARYOU_TEATE + ss.SYOUREIKIN + ss.ZANGYOU_TEATE + ss.SYUKKOU_TEATE + ss.JIKANSA_TEATE) AS KYUYO_TEATE " . "\r\n";
        $strSQL .= "        ,SUM(ss.BNS_GK) AS SYOUYO " . "\r\n";
        $strSQL .= "        ,SUM(ss.TAISYOKU_NENKIN) AS TAISYOKU_NENKIN " . "\r\n";
        $strSQL .= "        ,SUM(ss.KENKO_HKN_RYO + ss.KOUSEINENKIN + ss.JIDOU_TEATE + ss.KOYOU_HKN_RYO + ss.KAIGO_HKN_RYO + ss.TAISYOKU_NENKIN + ss.ROUSAI_UWA_HKN_RYO) + SUM(ss.BNS_GK + ss.BNS_KENKO_HKN_RYO + ss.BNS_KAIGO_HKN_RYO + ss.BNS_KOUSEI_NENKIN + ss.BNS_JIDOU_TEATE + ss.BNS_KOYOU_HOKEN) - (SUM(ss.BNS_GK) + SUM(ss.TAISYOKU_NENKIN)) AS FUKURI_KOUSEIHI " . "\r\n";
        $strSQL .= " FROM   JKSKOSEIKYUMEISAI ss" . "\r\n";
        $strSQL .= "        LEFT JOIN JKBUMON bu" . "\r\n";
        $strSQL .= "           ON ss.BUSYO_CD = bu.BUSYO_CD" . "\r\n";
        $strSQL .= " WHERE  ss.TAISYOU_YM = '@TAISYOU_YM' " . "\r\n";
        if ($postData['busyoCD'] <> '999999') {
            $strSQL .= " AND    bu.BUSYO_CD = '" . $postData['busyoCD'] . "' " . "\r\n";
        }
        $strSQL .= " GROUP BY ss.BUSYO_CD,bu.BUSYO_NM " . "\r\n";
        $strSQL .= " ORDER BY ss.BUSYO_CD ";

        $strSQL = str_replace("@TAISYOU_YM", $postData['taisyoYM'], $strSQL);

        return parent::select($strSQL);
    }

}
