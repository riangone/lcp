<?php

/**
 * 説明：
 *
 *
 * @author wangying
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL　
 * 20170503           #                            SQLフォーマットが改正する            WANG
 * 20170505           #                            SQL日付が改正する                   WANG
 * 20170508           #                            SQLが改正する                      WANG
 * * --------------------------------------------------------------------------------------------
 */

//共通クラスの読込み
namespace App\Model\APPM;

use App\Model\Component\ClsComDb;
use Cake\Routing\Router;
//*************************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************************
class FrmMessejiToroku extends ClsComDb
{
    public $SessionComponent;
    public function searchTCODE($flg, $postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT NAIBU_CD,NAIBU_CD_MEISHO" . " \r\n";
        $strSQL .= "FROM M_CODE" . " \r\n";
        if ($flg == "1") {
            $strSQL .= "WHERE GAIBU_CD = '001' " . " \r\n";
        }
        if ($flg == "2") {
            $strSQL .= "WHERE GAIBU_CD = '002' " . " \r\n";
        }
        if ($flg == "3") {
            $strSQL .= "WHERE GAIBU_CD = '016' " . " \r\n";
        }
        if ($flg == "4") {
            $strSQL .= "WHERE GAIBU_CD = '003' " . " \r\n";
        }
        $strSQL .= "AND YUKO_KAISHI_YMD<='" . $postData['dateFrom'] . "' " . " \r\n";
        $strSQL .= "AND YUKO_SHURYO_YMD>='" . $postData['dateFrom'] . "' " . " \r\n";
        $strSQL .= "AND YUKO_KAISHI_YMD<='" . $postData['dateTo'] . "' " . " \r\n";
        $strSQL .= "AND YUKO_SHURYO_YMD>='" . $postData['dateTo'] . "' " . " \r\n";
        $strSQL .= "AND DEL_FLG='00' " . " \r\n";

        return parent::select($strSQL);
    }

    public function searchTNAYIO($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT NAIYO_CD, NAIYO_CD_MEISHO" . " \r\n";
        $strSQL .= "FROM M_SHATENKEN_NAIYO" . " \r\n";
        $strSQL .= "WHERE YUKO_KAISHI_YMD<='" . $postData['dateFrom'] . "' " . " \r\n";
        $strSQL .= "AND YUKO_SHURYO_YMD>='" . $postData['dateFrom'] . "' " . " \r\n";
        $strSQL .= "AND YUKO_KAISHI_YMD<='" . $postData['dateTo'] . "' " . " \r\n";
        $strSQL .= "AND YUKO_SHURYO_YMD>='" . $postData['dateTo'] . "' " . " \r\n";
        $strSQL .= "AND DEL_FLG='00' " . " \r\n";

        return parent::select($strSQL);
    }

    public function fncSearch($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT * " . " \r\n";
        $strSQL .= "FROM T_MESSEJI" . " \r\n";
        $strSQL .= "WHERE MESSEJI_ID = '" . $postData['ID'] . "'" . " \r\n";

        return parent::select($strSQL);
    }

    public function FncSaibanSearch($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT REMBAN" . " \r\n";
        $strSQL .= "FROM T_KBN_SAIBAN" . " \r\n";
        $strSQL .= "WHERE TEBURUMEI = 't_messeji'" . " \r\n";
        $strSQL .= "AND SAIBAN_KBN = lpad('" . $postData['kbn'] . "',2,'0')" . " \r\n";

        return parent::select($strSQL);
    }

    public function FncSaibanInsert($postData)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSQL = "";
        $strSQL .= "INSERT INTO T_KBN_SAIBAN" . " \r\n";
        $strSQL .= "(" . " \r\n";
        $strSQL .= "TEBURUMEI" . " \r\n";
        $strSQL .= ",SAIBAN_KBN" . " \r\n";
        $strSQL .= ",REMBAN" . " \r\n";
        $strSQL .= ",UPD_DATE" . " \r\n";
        $strSQL .= ",UPD_USER_ID" . " \r\n";
        $strSQL .= ",CREATE_DATE" . " \r\n";
        $strSQL .= ",CREATE_USER_ID" . " \r\n";
        $strSQL .= ",DEL_FLG" . " \r\n";
        $strSQL .= ")" . " \r\n";
        $strSQL .= "VALUES" . " \r\n";
        $strSQL .= "(" . " \r\n";
        $strSQL .= "'t_messeji'" . " \r\n";
        $strSQL .= ", lpad('" . $postData['kbn'] . "',2,'0')" . " \r\n";
        $strSQL .= ", '1'" . " \r\n";
        $strSQL .= ", SYSDATE" . " \r\n";
        $strSQL .= ", '@UPD_USER_ID'" . " \r\n";
        $strSQL .= ", SYSDATE" . " \r\n";
        $strSQL .= ", '@CREATE_USER_ID'" . " \r\n";
        $strSQL .= ", '00'" . " \r\n";
        $strSQL .= ")" . " \r\n";

        $strSQL = str_replace("@UPD_USER_ID", $this->SessionComponent->read('login_user'), $strSQL);
        $strSQL = str_replace("@CREATE_USER_ID", $this->SessionComponent->read('login_user'), $strSQL);

        return parent::insert($strSQL);
    }

    public function FncTourokuConfirm($postData, $url)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSQL = "";
        $strSQL .= "INSERT INTO T_MESSEJI" . " \r\n";
        $strSQL .= "(" . " \r\n";
        $strSQL .= "  MESSEJI_ID" . " \r\n";
        $strSQL .= " ,NAIYO_KBN" . " \r\n";
        $strSQL .= " ,KIDOKU_KAKUNIN_FLG" . " \r\n";
        $strSQL .= " ,MOGIRI_FLG" . " \r\n";
        $strSQL .= " ,MESSEJI_RIYO_KIKAN_FROM" . " \r\n";
        $strSQL .= " ,MESSEJI_RIYO_KIKAN_TO" . " \r\n";
        $strSQL .= " ,KUPON_KIGEN_FORM" . " \r\n";
        $strSQL .= " ,KUPON_KIGEN_TO" . " \r\n";
        $strSQL .= " ,TAITORU" . " \r\n";
        $strSQL .= " ,SHAKEN_TENKEN_JOHO_KBN" . " \r\n";
        $strSQL .= " ,SHARYO_JOHO_FLG" . " \r\n";
        //20170519 LQS INS S
        if ($postData['txtImg'] != "") {
            $strSQL .= " ,MEIN_GAZO_MEI" . " \r\n";
        }
        //20170519 LQS INS E
        //if ($postData['txtImg'] != "")
        //{
        $strSQL .= " ,MEIN_GAZO_URL" . " \r\n";
        //}
        //20170505 WANG UPD E
        $strSQL .= " ,MESSEJI_NAIYO1" . " \r\n";
        $strSQL .= " ,MESSEJI_NAIYO2" . " \r\n";
        $strSQL .= " ,MESSEJI_NAIYO3" . " \r\n";
        $strSQL .= " ,KONTAKUTO_BOTAN_FLG" . " \r\n";
        $strSQL .= " ,SHIJO_YOYAKU_BOTAN_FLG" . " \r\n";
        $strSQL .= " ,NYUKO_YOYAKU_BOTAN_FLG" . " \r\n";
        $strSQL .= " ,UPD_STS_KBN" . " \r\n";
        $strSQL .= " ,RENKEI_KBN" . " \r\n";
        $strSQL .= " ,UPD_DATE" . " \r\n";
        $strSQL .= " ,UPD_USER_ID" . " \r\n";
        $strSQL .= " ,CREATE_DATE" . " \r\n";
        $strSQL .= " ,CREATE_USER_ID" . " \r\n";
        $strSQL .= " ,DEL_FLG" . " \r\n";
        $strSQL .= ")" . " \r\n";
        $strSQL .= "VALUES" . " \r\n";
        $strSQL .= " (" . " \r\n";
        $strSQL .= "  '@MESSEJI_ID'" . " \r\n";
        $strSQL .= " ,'@NAIYO_KBN'" . " \r\n";
        $strSQL .= " ,'@KIDOKU_KAKUNIN_FLG'" . " \r\n";
        $strSQL .= " ,'@MOGIRI_FLG'" . " \r\n";
        $strSQL .= " ,'@MESSEJI_RIYO_KIKAN_FROM'" . " \r\n";
        $strSQL .= " ,'@MESSEJI_RIYO_KIKAN_TO'" . " \r\n";
        $strSQL .= " ,'@KUPON_KIGEN_FORM'" . " \r\n";
        $strSQL .= " ,'@KUPON_KIGEN_TO'" . " \r\n";
        $strSQL .= " ,'@TAITORU'" . " \r\n";
        $strSQL .= " ,'@SHAKEN_TENKEN_JOHO_KBN'" . " \r\n";
        $strSQL .= " ,'@SHARYO_JOHO_FLG'" . " \r\n";
        //20170519 LQS INS S
        if ($postData['txtImg'] != "") {
            $strSQL .= " ,'@MESSEJI_ID.@extension'" . " \r\n";
        }
        //20170519 LQS INS E
        //20170505 WANG UPD S
        //if ($postData['txtImg'] != "")
        //{
        $strSQL .= " ,'@MEIN_GAZO_URL'" . " \r\n";
        //}
        //20170505 WANG UPD S
        $strSQL .= " ,'@MESSEJI_NAIYO1'" . " \r\n";
        $strSQL .= " ,'@MESSEJI_NAIYO2'" . " \r\n";
        $strSQL .= " ,'@MESSEJI_NAIYO3'" . " \r\n";
        $strSQL .= " ,'@KONTAKUTO_BOTAN_FLG'" . " \r\n";
        $strSQL .= " ,'@SHIJO_YOYAKU_BOTAN_FLG'" . " \r\n";
        $strSQL .= " ,'@NYUKO_YOYAKU_BOTAN_FLG'" . " \r\n";
        $strSQL .= " ,'01'" . " \r\n";
        $strSQL .= " ,'00'" . " \r\n";
        //20170503 WANG UPD S
        //$strSQL .= " , @UPD_DATE" . " \r\n";
        $strSQL .= " , SYSDATE" . " \r\n";
        //20170503 WANG UPD E
        $strSQL .= " ,'@UPD_USER_ID'" . " \r\n";
        //20170503 WANG UPD S
        //$strSQL .= " , @CREATE_DATE" . " \r\n";
        $strSQL .= " , SYSDATE" . " \r\n";
        //20170503 WANG UPD E
        $strSQL .= " ,'@CREATE_USER_ID'" . " \r\n";
        $strSQL .= " ,'00'" . " \r\n";
        $strSQL .= " )" . " \r\n";

        //'値置換
        //内容区分(お知らせ(01)、クーポン(02)、UX（03）)
        if ($postData["txtContent"] != '') {
            $strSQL = str_replace("@NAIYO_KBN", $postData["txtContent"], $strSQL);
        }
        //もぎりフラグ(無(00)、有(01))
        if ($postData["txtMogiri"] != '') {
            $strSQL = str_replace("@MOGIRI_FLG", $postData["txtMogiri"], $strSQL);
        }
        //20170508 WANG INS S
        else {
            $strSQL = str_replace("@MOGIRI_FLG", '00', $strSQL);
        }
        //20170508 WANG INS E
        //車点検月情報区分(不要(00)、無1(01)、無6(02)…)
        if ($postData["txtShaken"] != '') {
            $strSQL = str_replace("@SHAKEN_TENKEN_JOHO_KBN", $postData["txtShaken"], $strSQL);
        } else {
            $strSQL = str_replace("@SHAKEN_TENKEN_JOHO_KBN", "", $strSQL);
        }
        //車両情報フラグ(不要(00)、必要(01))
        if ($postData["txtSharyo"] != '') {
            $strSQL = str_replace("@SHARYO_JOHO_FLG", $postData["txtSharyo"], $strSQL);
        } else {
            $strSQL = str_replace("@SHARYO_JOHO_FLG", "", $strSQL);
        }
        //コンタクトボタンフラグ(不要(00)、必要(01))
        if ($postData["txtRinku"] != '') {
            $strSQL = str_replace("@KONTAKUTO_BOTAN_FLG", $postData["txtRinku"], $strSQL);
        } else {
            $strSQL = str_replace("@KONTAKUTO_BOTAN_FLG", "", $strSQL);
        }
        //入庫予約ボタンフラグ(不要(00)、必要(01))
        if ($postData["txtRu"] != '') {
            $strSQL = str_replace("@NYUKO_YOYAKU_BOTAN_FLG", $postData["txtRu"], $strSQL);
        } else {
            $strSQL = str_replace("@NYUKO_YOYAKU_BOTAN_FLG", "", $strSQL);
        }
        //試乗予約ボタンフラグ(不要(00)、必要(01))
        if ($postData["txtShi"] != '') {
            $strSQL = str_replace("@SHIJO_YOYAKU_BOTAN_FLG", $postData["txtShi"], $strSQL);
        } else {
            $strSQL = str_replace("@SHIJO_YOYAKU_BOTAN_FLG", "", $strSQL);
        }
        //既読確認フラグ
        if ($postData["txtKidoku"] != '') {
            $strSQL = str_replace("@KIDOKU_KAKUNIN_FLG", $postData["txtKidoku"], $strSQL);
        } else {
            $strSQL = str_replace("@KIDOKU_KAKUNIN_FLG", "", $strSQL);
        }
        //20170519 LQS INS S
        if ($postData['txtImg'] != "") {
            $strSQL = str_replace("@extension", pathinfo($postData['txtImg'], PATHINFO_EXTENSION), $strSQL);
        }
        //20170519 LQS INS E

        $strSQL = str_replace("@MESSEJI_ID", $postData["txtCode"], $strSQL);
        $strSQL = str_replace("@MESSEJI_RIYO_KIKAN_FROM", str_replace("/", "", $postData["txtMFromKikan"]), $strSQL);
        $strSQL = str_replace("@MESSEJI_RIYO_KIKAN_TO", str_replace("/", "", $postData["txtMToMKikan"]), $strSQL);
        $strSQL = str_replace("@KUPON_KIGEN_FORM", str_replace("/", "", $postData["txtKFromKikan"]), $strSQL);
        $strSQL = str_replace("@KUPON_KIGEN_TO", str_replace("/", "", $postData["txtKToKikan"]), $strSQL);
        //20170508 WANG UPD S
        //$strSQL = str_replace("@TAITORU", $postData["txtTitle"], $strSQL);
        if ($postData["txtTitle"] != '') {
            //20170519 LQS UPD S
            //$strSQL = str_replace("@TAITORU", $postData["txtTitle"], $strSQL);
            $strSQL = str_replace("@TAITORU", str_replace("'", "''", $postData["txtTitle"]), $strSQL);
            //20170519 LQS UPD E
        } else {
            $strSQL = str_replace("@TAITORU", 'なし', $strSQL);
        }
        //20170508 WANG UPD E

        //20170505 WANG UPD S
        //if ($postData['txtImg'] != "")
        //{
        //20170519 LQS UPD S
        //$strSQL = str_replace("@MEIN_GAZO_URL", $url, $strSQL);
        $strSQL = str_replace("@MEIN_GAZO_URL", str_replace("'", "''", $url), $strSQL);
        //}
        //20170505 WANG UPD E
        // $strSQL = str_replace("@MESSEJI_NAIYO1", $postData["txtMessage1"], $strSQL);
        // $strSQL = str_replace("@MESSEJI_NAIYO2", $postData["txtMessage2"], $strSQL);
        // $strSQL = str_replace("@MESSEJI_NAIYO3", $postData["txtMessage3"], $strSQL);
        $strSQL = str_replace("@MESSEJI_NAIYO1", str_replace("'", "''", $postData["txtMessage1"]), $strSQL);
        $strSQL = str_replace("@MESSEJI_NAIYO2", str_replace("'", "''", $postData["txtMessage2"]), $strSQL);
        $strSQL = str_replace("@MESSEJI_NAIYO3", str_replace("'", "''", $postData["txtMessage3"]), $strSQL);
        //20170519 LQS UPD E
        //20170503 WANG DEL S
        //$strSQL = str_replace("@UPD_DATE", SYSDATE, $strSQL);
        //20170503 WANG DEL E
        $strSQL = str_replace("@UPD_USER_ID", $this->SessionComponent->read('login_user'), $strSQL);
        //20170503 WANG DEL S
        //$strSQL = str_replace("@CREATE_DATE", SYSDATE, $strSQL);
        //20170503 WANG DEL E
        $strSQL = str_replace("@CREATE_USER_ID", $this->SessionComponent->read('login_user'), $strSQL);

        return parent::insert($strSQL);
    }

    public function FncUpdateSaiban($postData)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSQL = "";
        $strSQL .= "UPDATE T_KBN_SAIBAN" . " \r\n";
        $strSQL .= "SET" . " \r\n";
        //20170518 LQS UPD S
        //$strSQL .= "REMBAN = REMBAN + 1" . " \r\n";
        $strSQL .= "REMBAN = REMBAN + 1 ," . " \r\n";
        $strSQL .= "UPD_DATE = SYSDATE, " . " \r\n";
        $strSQL .= "UPD_USER_ID = '" . $this->SessionComponent->read('login_user') . "'" . " \r\n";
        //20170518 LQS UPD E
        $strSQL .= "WHERE TEBURUMEI = 't_messeji'" . " \r\n";
        $strSQL .= "AND SAIBAN_KBN = lpad('" . $postData['txtContent'] . "',2,'0')" . " \r\n";

        return parent::update($strSQL);
    }

    public function FncUpdateConfirm($postData, $url)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSQL = "";
        $strSQL .= "UPDATE T_MESSEJI" . " \r\n";
        $strSQL .= "SET" . " \r\n";
        $strSQL .= " NAIYO_KBN = '@NAIYO_KBN'" . " \r\n";
        $strSQL .= " ,KIDOKU_KAKUNIN_FLG = '@KIDOKU_KAKUNIN_FLG'" . " \r\n";
        $strSQL .= " ,MOGIRI_FLG = '@MOGIRI_FLG'" . " \r\n";
        $strSQL .= " ,MESSEJI_RIYO_KIKAN_FROM = '@MESSEJI_RIYO_KIKAN_FROM'" . " \r\n";
        $strSQL .= " ,MESSEJI_RIYO_KIKAN_TO = '@MESSEJI_RIYO_KIKAN_TO'" . " \r\n";
        $strSQL .= " ,KUPON_KIGEN_FORM = '@KUPON_KIGEN_FORM'" . " \r\n";
        $strSQL .= " ,KUPON_KIGEN_TO = '@KUPON_KIGEN_TO'" . " \r\n";
        $strSQL .= " ,TAITORU = '@TAITORU'" . " \r\n";
        $strSQL .= " ,SHAKEN_TENKEN_JOHO_KBN = '@SHAKEN_TENKEN_JOHO_KBN'" . " \r\n";
        $strSQL .= " ,SHARYO_JOHO_FLG = '@SHARYO_JOHO_FLG'" . " \r\n";
        //20170519 LQS INS S
        $strSQL .= " ,MEIN_GAZO_MEI = '@IMG'" . " \r\n";
        //20170519 LQS INS E
        $strSQL .= " ,MEIN_GAZO_URL = '@MEIN_GAZO_URL'" . " \r\n";
        $strSQL .= " ,MESSEJI_NAIYO1 = '@MESSEJI_NAIYO1'" . " \r\n";
        $strSQL .= " ,MESSEJI_NAIYO2 = '@MESSEJI_NAIYO2'" . " \r\n";
        $strSQL .= " ,MESSEJI_NAIYO3 = '@MESSEJI_NAIYO3'" . " \r\n";
        $strSQL .= " ,KONTAKUTO_BOTAN_FLG = '@KONTAKUTO_BOTAN_FLG'" . " \r\n";
        $strSQL .= " ,SHIJO_YOYAKU_BOTAN_FLG = '@SHIJO_YOYAKU_BOTAN_FLG'" . " \r\n";
        $strSQL .= " ,NYUKO_YOYAKU_BOTAN_FLG = '@NYUKO_YOYAKU_BOTAN_FLG'" . " \r\n";
        $strSQL .= " ,UPD_STS_KBN = '02'" . " \r\n";
        $strSQL .= " ,RENKEI_KBN = '00'" . " \r\n";
        $strSQL .= " ,UPD_DATE = SYSDATE" . " \r\n";
        $strSQL .= " ,UPD_USER_ID = '@UPD_USER_ID'" . " \r\n";
        $strSQL .= " WHERE MESSEJI_ID = '@MESSEJI_ID'" . " \r\n";

        //'値置換
        //内容区分(お知らせ(01)、クーポン(02)、UX（03）)
        if ($postData["txtContent"] != '') {
            $strSQL = str_replace("@NAIYO_KBN", $postData["txtContent"], $strSQL);
        }
        //もぎりフラグ(無(00)、有(01))
        if ($postData["txtMogiri"] != '') {
            $strSQL = str_replace("@MOGIRI_FLG", $postData["txtMogiri"], $strSQL);
        }
        //20170508 WANG INS S
        else {
            $strSQL = str_replace("@MOGIRI_FLG", '00', $strSQL);
        }
        //20170508 WANG INS E
        //車点検月情報区分(不要(00)、無1(01)、無6(02)…)
        if ($postData["txtShaken"] != '') {
            $strSQL = str_replace("@SHAKEN_TENKEN_JOHO_KBN", $postData["txtShaken"], $strSQL);
        } else {
            $strSQL = str_replace("@SHAKEN_TENKEN_JOHO_KBN", "", $strSQL);
        }
        //車両情報フラグ(不要(00)、必要(01))
        if ($postData["txtSharyo"] != '') {
            $strSQL = str_replace("@SHARYO_JOHO_FLG", $postData["txtSharyo"], $strSQL);
        } else {
            $strSQL = str_replace("@SHARYO_JOHO_FLG", "", $strSQL);
        }
        //コンタクトボタンフラグ(不要(00)、必要(01))
        if ($postData["txtRinku"] != '') {
            $strSQL = str_replace("@KONTAKUTO_BOTAN_FLG", $postData["txtRinku"], $strSQL);
        } else {
            $strSQL = str_replace("@KONTAKUTO_BOTAN_FLG", "", $strSQL);
        }
        //入庫予約ボタンフラグ(不要(00)、必要(01))
        if ($postData["txtRu"] != '') {
            $strSQL = str_replace("@NYUKO_YOYAKU_BOTAN_FLG", $postData["txtRu"], $strSQL);
        } else {
            $strSQL = str_replace("@NYUKO_YOYAKU_BOTAN_FLG", "", $strSQL);
        }
        //試乗予約ボタンフラグ(不要(00)、必要(01))
        if ($postData["txtShi"] != '') {
            $strSQL = str_replace("@SHIJO_YOYAKU_BOTAN_FLG", $postData["txtShi"], $strSQL);
        } else {
            $strSQL = str_replace("@SHIJO_YOYAKU_BOTAN_FLG", "", $strSQL);
        }
        //既読確認フラグ
        if ($postData["txtKidoku"] != '') {
            $strSQL = str_replace("@KIDOKU_KAKUNIN_FLG", $postData["txtKidoku"], $strSQL);
        } else {
            $strSQL = str_replace("@KIDOKU_KAKUNIN_FLG", "", $strSQL);
        }
        //20170519 LQS INS S
        if ($postData['txtImg'] != "") {
            $strSQL = str_replace("@IMG", $postData["txtCode"] . "." . pathinfo($postData['txtImg'], PATHINFO_EXTENSION), $strSQL);
        } else {
            $strSQL = str_replace("@IMG", "", $strSQL);
        }
        //20170519 LQS INS E

        $strSQL = str_replace("@MESSEJI_ID", $postData["txtCode"], $strSQL);
        $strSQL = str_replace("@MESSEJI_RIYO_KIKAN_FROM", str_replace("/", "", $postData["txtMFromKikan"]), $strSQL);
        $strSQL = str_replace("@MESSEJI_RIYO_KIKAN_TO", str_replace("/", "", $postData["txtMToMKikan"]), $strSQL);
        $strSQL = str_replace("@KUPON_KIGEN_FORM", str_replace("/", "", $postData["txtKFromKikan"]), $strSQL);
        $strSQL = str_replace("@KUPON_KIGEN_TO", str_replace("/", "", $postData["txtKToKikan"]), $strSQL);
        //20170508 WANG UPD S
        //$strSQL = str_replace("@TAITORU", $postData["txtTitle"], $strSQL);
        if ($postData["txtTitle"] != '') {
            $strSQL = str_replace("@TAITORU", str_replace("'", "''", $postData["txtTitle"]), $strSQL);
        } else {
            $strSQL = str_replace("@TAITORU", 'なし', $strSQL);
        }
        //20170508 WANG UPD E
        //20170505 WANG UPD S
        //if ($postData["txtImg"] != '')
        //{
        $strSQL = str_replace("@MEIN_GAZO_URL", str_replace("'", "''", $url), $strSQL);
        //}
        //else
        //{
        //$strSQL = str_replace("@MEIN_GAZO_URL", "", $strSQL);
        //}
        //20170505 WANG UPD S
        $strSQL = str_replace("@MESSEJI_NAIYO1", str_replace("'", "''", $postData["txtMessage1"]), $strSQL);
        $strSQL = str_replace("@MESSEJI_NAIYO2", str_replace("'", "''", $postData["txtMessage2"]), $strSQL);
        $strSQL = str_replace("@MESSEJI_NAIYO3", str_replace("'", "''", $postData["txtMessage3"]), $strSQL);
        //20170503 WANG DEL S
        //$strSQL = str_replace("@UPD_DATE", SYSDATE, $strSQL);
        //20170503 WANG DEL E
        $strSQL = str_replace("@UPD_USER_ID", $this->SessionComponent->read('login_user'), $strSQL);

        return parent::update($strSQL);
    }

    public function FncDeleteConfirm($postData)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSQL = "";
        $strSQL .= "UPDATE T_MESSEJI" . " \r\n";
        $strSQL .= "SET" . " \r\n";
        $strSQL .= " DEL_FLG = '01'" . " \r\n";
        //20170519 LQS UPD S
        //$strSQL .= " ,MEIN_GAZO_URL = ''" . " \r\n";
        $strSQL .= ", UPD_STS_KBN = '09'" . " \r\n";
        //20170519 LQS UPD E
        //20170508 WANG INS S
        $strSQL .= " ,UPD_DATE = SYSDATE" . " \r\n";
        $strSQL .= " ,UPD_USER_ID = '@UPD_USER_ID'" . " \r\n";
        $strSQL .= " ,DEL_USER_ID = '@DEL_USER_ID'" . " \r\n";
        //20170508 WANG INS E
        $strSQL .= " WHERE MESSEJI_ID = '@MESSEJI_ID'" . " \r\n";

        $strSQL = str_replace("@MESSEJI_ID", $postData["txtCode"], $strSQL);
        //20170508 WANG INS S
        $strSQL = str_replace("@UPD_USER_ID", $this->SessionComponent->read('login_user'), $strSQL);
        $strSQL = str_replace("@DEL_USER_ID", $this->SessionComponent->read('login_user'), $strSQL);
        //20170508 WANG INS E
        return parent::update($strSQL);
    }

}
