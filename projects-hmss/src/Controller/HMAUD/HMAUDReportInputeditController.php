<?php
/**
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20230103           機能追加　　　　　　          20221226_内部統制_仕様変更        YIN
 * 20240530           機能追加      メール通知機能にて、クールと領域名も一緒に出力する   YIN
 * 20240612           機能追加      報告書入力で 差戻を実行する際、差戻先を ユーザーが選択可能にしてほしい    CI
 * 20240619           機能修正           HMDB.xml の <SMTPAuth> 設定             YIN
 * 20250403           機能追加       		     202504_内部統制_要望.xlsx        CI
 * 20251016           機能追加      202510_内部統制システム_仕様変更対応.xlsx         YIN
 * 20260126     「社長」欄を１つ廃止     202601_内部統制_変更要望.xlsx               YIN
 * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\HMAUD;

use App\Controller\AppController;
use App\Model\HMAUD\HMAUDReportInputedit;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
//*******************************************
// * sample controller
//*******************************************
class HMAUDReportInputeditController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
    // public $autoRender = false;
    public $HMAUDReportInputedit;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');

    }


    public function index()
    {
        $this->render('index', 'HMAUDReportInputedit_layout');
    }

    public function btnOKClick()
    {
        $tranStartFlg = FALSE;
        $this->HMAUDReportInputedit = new HMAUDReportInputedit();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (!isset($_POST['data'])) {
                throw new \Exception('param error');
            }
            $gethistoryid = $this->HMAUDReportInputedit->getMaxid();
            if (!$gethistoryid['result']) {
                throw new \Exception($gethistoryid['data']);
            }
            $_POST['data']['REPORT_HISTORY_ID'] = $gethistoryid['data'][0]['REPORT_HISTORY_ID'];
            //トランザクション開始
            //更新処理を行う
            $this->HMAUDReportInputedit->Do_transaction();
            $tranStartFlg = TRUE;
            $upddata = $this->HMAUDReportInputedit->updReportHeadSql($_POST['data']);
            if (!$upddata['result']) {
                throw new \Exception($upddata['data']);
            }
            //確定／差戻ボタンクリック時、報告書処理履歴データを追加する
            $insHistorydata = $this->HMAUDReportInputedit->insHistorySql($_POST['data']);
            if (!$insHistorydata['result']) {
                throw new \Exception($insHistorydata['data']);
            }
            //社長＿確認ボタンクリック メール送付はしない

            $this->HMAUDReportInputedit->Do_commit();
            $tranStartFlg = FALSE;
            // 20230103 YIN UPD S
            // if ($_POST['data']['flag'] !== '10')
            if ($_POST['data']['flag'] !== '14')
            // 20230103 YIN UPD E
            {
                $params = array(
                    'title' => '',
                    'unit' => '',
                    'condition' => '',
                );
                $postdata = array(
                    'type' => '',
                    'pattren' => '',
                    'phase' => '',
                );
                //20240614 CI INS S
                $role = "";
                if ($_POST['data']['return_flag'] == "91") {
                    $role = "1";
                }
                if ($_POST['data']['return_flag'] == "94") {
                    $role = "3";
                }
                if ($_POST['data']['return_flag'] == "95") {
                    $role = "4";
                }
                if ($_POST['data']['return_flag'] == "96") {
                    $role = "5";
                }
                if ($_POST['data']['return_flag'] == "97") {
                    $role = "6";
                }
                if ($_POST['data']['return_flag'] == "98") {
                    $role = "7";
                }
                // 20250403 CI INS S
                if ($_POST['data']['return_flag'] == "99") {
                    $role = "8";
                }
                // 20250403 CI INS E
                //20240614 CI INS S
                //　監査人確定ボタンクリック
                if ($_POST['data']['flag'] == '0') {
                    $params['unit'] = $_POST['data']['kyoten'] . "+" . $_POST['data']['territory'];
                    $postdata['type'] = "1";
                    $postdata['pattren'] = "1";
                    $postdata['phase'] = "1";
                    $_POST['data']['ROLE'] = "2";
                }
                //　改善報告書担当＿確定ボタンクリック
                if ($_POST['data']['flag'] == '1') {
                    $params['unit'] = $_POST['data']['kyoten'] . "+" . $_POST['data']['territory'];
                    $postdata['type'] = "1";
                    $postdata['pattren'] = "1";
                    if ($_POST['data']['skip'] == '1') {
                        $postdata['phase'] = "3";
                        $_POST['data']['ROLE'] = "4";
                    } else {
                        $postdata['phase'] = "2";
                        $_POST['data']['ROLE'] = "3";
                    }

                }
                //　改善報告書担当＿差戻ボタンクリック
                if ($_POST['data']['flag'] == '2') {
                    $params['unit'] = $_POST['data']['kyoten'] . "+" . $_POST['data']['territory'];
                    $postdata['type'] = "1";
                    $postdata['pattren'] = "2";
                    $postdata['phase'] = "2";
                    //20240614 CI UPD S
                    //$_POST['data']['ROLE'] = "1";
                    $_POST['data']['ROLE'] = $role;
                    //20240614 CI UPD E
                }
                //　改善取組責任者＿提出	ボタンクリック
                if ($_POST['data']['flag'] == '3') {
                    $params['unit'] = $_POST['data']['kyoten'] . "+" . $_POST['data']['territory'];
                    $postdata['type'] = "1";
                    $postdata['pattren'] = "1";
                    $postdata['phase'] = "3";
                    $_POST['data']['ROLE'] = "4";
                }
                //　各領域責任者＿確認ボタンクリック
                if ($_POST['data']['flag'] == '4') {
                    $params['unit'] = $_POST['data']['kyoten'];
                    $postdata['type'] = "1";
                    $postdata['pattren'] = "1";
                    $postdata['phase'] = "4";
                    $_POST['data']['ROLE'] = "5";
                }
                //　各領域責任者＿差戻ボタンクリック
                if ($_POST['data']['flag'] == '5') {
                    $params['unit'] = $_POST['data']['kyoten'] . "+" . $_POST['data']['territory'];
                    $postdata['type'] = "1";
                    $postdata['pattren'] = "2";
                    $postdata['phase'] = "4";
                    //20240614 CI UPD S
                    //$_POST['data']['ROLE'] = "3";
                    $_POST['data']['ROLE'] = $role;
                    //20240614 CI UPD E
                }
                //　キーマン＿確認ボタンクリック
                if ($_POST['data']['flag'] == '6') {
                    $params['unit'] = $_POST['data']['kyoten'];
                    $postdata['type'] = "1";
                    $postdata['pattren'] = "1";
                    $postdata['phase'] = "5";
                    $_POST['data']['ROLE'] = "6";
                }
                //　キーマン＿差戻ボタンクリック
                if ($_POST['data']['flag'] == '7') {
                    $params['unit'] = $_POST['data']['kyoten'];
                    $postdata['type'] = "1";
                    $postdata['pattren'] = "2";
                    $postdata['phase'] = "5";
                    //20240614 CI UPD S
                    //$_POST['data']['ROLE'] = "3";
                    $_POST['data']['ROLE'] = $role;
                    //20240614 CI UPD E
                }
                //　総括責任者＿確認ボタンクリック
                if ($_POST['data']['flag'] == '8') {
                    $params['unit'] = $_POST['data']['kyoten'];
                    $postdata['type'] = "1";
                    $postdata['pattren'] = "1";
                    $postdata['phase'] = "6";
                    $_POST['data']['ROLE'] = "7";
                    // 20251016 YIN INS S
                    if ($_POST['data']['cour'] > 18) {
                        $postdata['pattren'] = "3";
                        $_POST['data']['ROLE'] = "8";
                    }
                    // 20251016 YIN INS E
                }
                //　総括責任者＿差戻ボタンクリック
                if ($_POST['data']['flag'] == '9') {
                    $params['unit'] = $_POST['data']['kyoten'];
                    $postdata['type'] = "1";
                    $postdata['pattren'] = "2";
                    $postdata['phase'] = "6";
                    //20240614 CI UPD S
                    //$_POST['data']['ROLE'] = "3";
                    $_POST['data']['ROLE'] = $role;
                    //20240614 CI UPD E
                }
                // 20230103 YIN INS S
                //　常務＿確認ボタンクリック
                if ($_POST['data']['flag'] == '10') {
                    $params['unit'] = $_POST['data']['kyoten'];
                    $postdata['type'] = "1";
                    $postdata['pattren'] = "1";
                    $postdata['phase'] = "7";
                    $_POST['data']['ROLE'] = "8";
                }
                //　常務＿差戻ボタンクリック
                if ($_POST['data']['flag'] == '11') {
                    $params['unit'] = $_POST['data']['kyoten'];
                    $postdata['type'] = "1";
                    $postdata['pattren'] = "2";
                    $postdata['phase'] = "7";
                    //20240614 CI UPD S
                    //$_POST['data']['ROLE'] = "3";
                    $_POST['data']['ROLE'] = $role;
                    //20240614 CI UPD E
                }
                // 20230103 YIN INS E
                // 20250403 CI UPD S
                // 副社長＿確認ボタンクリック
                if ($_POST['data']['cour'] < 20) {
                    if ($_POST['data']['flag'] == '12') {
                        $params['unit'] = $_POST['data']['kyoten'];
                        $postdata['type'] = "1";
                        $postdata['pattren'] = "1";
                        $postdata['phase'] = "8";
                        $_POST['data']['ROLE'] = "9";
                    }
                }
                // 副社長＿差戻ボタンクリック
                if ($_POST['data']['flag'] == '13') {
                    $params['unit'] = $_POST['data']['kyoten'];
                    $postdata['type'] = "1";
                    $postdata['pattren'] = "2";
                    $postdata['phase'] = "8";
                    $_POST['data']['ROLE'] = $role;
                }

                //　社長＿差戻ボタンクリック
                if ($_POST['data']['flag'] == '15') {
                    $params['unit'] = $_POST['data']['kyoten'];
                    $postdata['type'] = "1";
                    $postdata['pattren'] = "2";
                    $postdata['phase'] = "9";
                    $_POST['data']['ROLE'] = $role;
                }

                // 20250403 CI UPD E

                $getmaildata = $this->HMAUDReportInputedit->getmaildata($postdata);
                if (!$getmaildata['result']) {
                    throw new \Exception($getmaildata['data']);
                }
                $params['title'] = $getmaildata['data'][0]['TITLE'];
                $params['condition'] = str_replace("\n", "<br/>", $getmaildata['data'][0]['DESCRIPTION']);
                // 20240530 YIN INS S
                $params['cour'] = $_POST['data']['cour'];
                // 20240530 YIN INS E
                //メールアドレスを取得する
                $mailAddressReader = $this->HMAUDReportInputedit->getEmailAddress($_POST['data']);
                if (!$mailAddressReader['result']) {
                    throw new \Exception($mailAddressReader['data']);
                }
                //メールを送信する
                if (count((array) $mailAddressReader['data']) > 0) {
                    $sendmail = $this->sendMail($mailAddressReader['data'], $params);
                    if ($sendmail != TRUE) {
                        throw new \Exception("W9999");
                    }
                }

            }
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $this->HMAUDReportInputedit->Do_rollback();
            }
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //メールを送信する
    public function sendMail($strEmailAddressReader, $params)
    {
        try {
            include_once dirname(__DIR__) . '/Login/Component/Mailer/mail.inc';
            include_once dirname(__DIR__) . '/Login/Component/Mailer/PHPMailer/Exception.php';
            include_once dirname(__DIR__) . '/Login/Component/Mailer/PHPMailer/PHPMailer.php';
            include_once dirname(__DIR__) . '/Login/Component/Mailer/PHPMailer/SMTP.php';
            // 20240619 YIN INS S
            // パス取得
            $strPath = dirname(dirname(dirname(__FILE__)));
            $filename = $strPath . "/Model/Component/" . 'HMDB.xml';
            // 値取得
            $xml = simplexml_load_file($filename);
            // XMLの取得
            $Ora = (array) $xml;
            // 20240619 YIN INS E

            $mail = new PHPMailer();
            // 启用SMTP
            $mail->IsSMTP();
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->Host = $Host;
            // sets the prefix to the servier
            //$mail->SMTPSecure = $SMTPSecure;
            //SMTP服务器
            $mail->Port = $Port;
            //开启SMTP认证
            // 20240619 YIN UPS S
            // $mail->SMTPAuth = true;
            $mail->SMTPAuth = $Ora['SMTPAuth'];
            $mail->SMTPAutoTLS = false;
            // 20240619 YIN UPS E
            // SMTP username SMTP用户名
            //$mail->Username = $Username;
            // SMTP password SMTP密码
            //$mail->Password = $Password;
            //发件人地址
            $mail->From = $From;
            //发件人
            $mail->FromName = $FromName;

            // set word wrap to 50 characters
            $mail->WordWrap = $WordWrap;
            // set email format to HTML
            $mail->IsHTML(true);
            // 设置编码
            $mail->CharSet = $CharSet;
            //メール件名
            $mail->Subject = $params['title'];

            $mail->Encoding = $Encoding;
            //邮件内容
            // 20240530 YIN INS S
            $strBdy = 'クール：' . $params['cour'] . "<br/>";
            // 20240530 YIN INS E
            $strBdy .= $Hmaud_Subject1 . $params['unit'] . "<br/>";
            $strBdy .= $params['condition'];

            if (count($strEmailAddressReader) > 0) {
                for ($i = 0; $i < count($strEmailAddressReader); $i++) {
                    $resAddr = $mail->AddAddress($strEmailAddressReader[$i]['EMAIL']);
                    if ($resAddr == false) {
                        return FALSE;
                    }
                }
                $mail->Body = $strBdy;
                $status = $mail->Send();
                if ($status != true) {
                    return FALSE;
                }
            }
            return TRUE;
        } catch (Exception $e) {
            return FALSE;
        }
    }

}
