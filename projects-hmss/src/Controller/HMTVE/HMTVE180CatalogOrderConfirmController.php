<?php
namespace App\Controller\HMTVE;
use App\Controller\AppController;
use App\Model\HMTVE\HMTVE180CatalogOrderConfirm;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
//*******************************************
// * sample controller
//*******************************************
class HMTVE180CatalogOrderConfirmController extends AppController
{
    public $autoLayout = TRUE;
    public $HMTVE180CatalogOrderConfirm;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
    }
    public function index()
    {
        // 画面表示内容の設定
        $this->render('index', 'HMTVE180CatalogOrderConfirm_layout');
    }

    //店舗名を取得する
    //カタログ配送希望
    //対象データを取得し、表示する-本カタログ
    public function setGvBookDir()
    {
        $this->HMTVE180CatalogOrderConfirm = new HMTVE180CatalogOrderConfirm();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (!isset($_POST['request'])) {
                throw new \Exception("param error");
            }
            $lblOrderDayShow = $_POST['request']['lblOrderDayShow'];
            $lblOrderTimeShow = $_POST['request']['lblOrderTimeShow'];
            $lblShopCD = $_POST['request']['BUSYOCD'];
            $busyo = $this->HMTVE180CatalogOrderConfirm->shopSQL($lblShopCD);
            if (!$busyo['result']) {
                throw new \Exception($busyo['data']);
            }
            //カタログ配送希望
            $chkHaisouKibou = $this->HMTVE180CatalogOrderConfirm->SQL3($lblOrderDayShow, $lblOrderTimeShow, $lblShopCD);
            if (!$chkHaisouKibou['result']) {
                throw new \Exception($chkHaisouKibou['data']);
            }
            //本カタログ
            $gvBookDir = $this->HMTVE180CatalogOrderConfirm->SQL($lblOrderDayShow, $lblOrderTimeShow, $lblShopCD);
            if (!$gvBookDir['result']) {
                throw new \Exception($gvBookDir['data']);
            }
            $tmpJqgridShow = $this->ClsComFncHMTVE->FncCreateJqGridShow($gvBookDir['data']);
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];
            $res = $this->ClsComFncHMTVE->FncCreateJqGridDataIndex($gvBookDir['data'], $totalPage, $page, $tmpCount);
            //店舗名
            $res->shopName = $busyo['data'];
            //カタログ配送希望
            $res->chkHaisouKibou = $chkHaisouKibou['data'];
        } catch (\Exception $e) {
            //前端jqgrid共通，显示error信息的title有问题，所以这里返回值为true
            $res['result'] = TRUE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //対象データを取得し、表示する-用品カタログ
    public function setGvProductDir()
    {
        $this->HMTVE180CatalogOrderConfirm = new HMTVE180CatalogOrderConfirm();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (!isset($_POST['request'])) {
                throw new \Exception("param error");
            }
            $lblOrderDayShow = $_POST['request']['lblOrderDayShow'];
            $lblOrderTimeShow = $_POST['request']['lblOrderTimeShow'];
            $lblShopCD = $_POST['request']['BUSYOCD'];
            //用品カタログ
            $gvProductDir = $this->HMTVE180CatalogOrderConfirm->SQL1($lblOrderDayShow, $lblOrderTimeShow, $lblShopCD);
            if (!$gvProductDir['result']) {
                throw new \Exception($gvProductDir['data']);
            }
            $tmpJqgridShow = $this->ClsComFncHMTVE->FncCreateJqGridShow($gvProductDir['data']);
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];
            $res = $this->ClsComFncHMTVE->FncCreateJqGridDataIndex($gvProductDir['data'], $totalPage, $page, $tmpCount);
        } catch (\Exception $e) {
            //前端jqgrid共通，显示error信息的title有问题，所以这里返回值为true
            $res['result'] = TRUE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //対象データを取得し、表示する-用品
    public function setGvProdect()
    {
        $this->HMTVE180CatalogOrderConfirm = new HMTVE180CatalogOrderConfirm();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (!isset($_POST['request'])) {
                throw new \Exception("param error");
            }
            $lblOrderDayShow = $_POST['request']['lblOrderDayShow'];
            $lblOrderTimeShow = $_POST['request']['lblOrderTimeShow'];
            $lblShopCD = $_POST['request']['BUSYOCD'];
            //用品
            $gvProdect = $this->HMTVE180CatalogOrderConfirm->SQL2($lblOrderDayShow, $lblOrderTimeShow, $lblShopCD);
            if (!$gvProdect['result']) {
                throw new \Exception($gvProdect['data']);
            }
            $tmpJqgridShow = $this->ClsComFncHMTVE->FncCreateJqGridShow($gvProdect['data']);
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];
            $res = $this->ClsComFncHMTVE->FncCreateJqGridDataIndex($gvProdect['data'], $totalPage, $page, $tmpCount);
        } catch (\Exception $e) {
            //前端jqgrid共通，显示error信息的title有问题，所以这里返回值为true
            $res['result'] = TRUE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //注文を確定ボタンのイベント
    public function btnConfirmClick()
    {
        $this->HMTVE180CatalogOrderConfirm = new HMTVE180CatalogOrderConfirm();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (!isset($_POST['data'])) {
                throw new \Exception("param error");
            }
            //同一年月日の更新がないかチェックする
            //同一年月日のカタログ注文データを取得する
            $res = $this->HMTVE180CatalogOrderConfirm->ORDER_DATE_GET($_POST['data']['BUSYOCD'], $_POST['data']['lblOrderDayShow']);
            if (!$res['result']) {
                throw new \Exception($res['data']);
            }
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    public function cmdEventClick()
    {
        $tranStartFlg = FALSE;
        $this->HMTVE180CatalogOrderConfirm = new HMTVE180CatalogOrderConfirm();
        $res = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            $fncUpdSaiban = "99999999";
            $BANGO = '';
            $strNengetu = $this->ClsComFncHMTVE->FncGetSysDate("Ym");
            //注文番号をふる
            //採番ﾃｰﾌﾞﾙから採番する
            $objDr = $this->HMTVE180CatalogOrderConfirm->fncUpdSaiban_SEL($strNengetu);
            if (!$objDr['result']) {
                throw new \Exception($objDr['data']);
            }
            //新たに取得した番号を架装番号に設定
            $HasRows = false;
            if ($objDr['row'] > 0) {
                $BANGO = $this->ClsComFncHMTVE->FncNv($objDr['data'][0]['BANGO']);
                $fncUpdSaiban = $strNengetu . str_pad($BANGO, 4, "0", STR_PAD_LEFT);
                $HasRows = true;
            } else {
                $fncUpdSaiban = $strNengetu . "0001";
                $HasRows = false;
            }
            //トランザクション開始
            $this->HMTVE180CatalogOrderConfirm->Do_transaction();
            $tranStartFlg = TRUE;
            //採番ﾃｰﾌﾞﾙに既に同一年月のものがあればUPDATE、なければINSERT
            $sysDate = $this->ClsComFncHMTVE->FncGetSysDate("Y/m/d H:i:s");
            $upd = $this->HMTVE180CatalogOrderConfirm->fncUpdSaiban_UPD($HasRows, $BANGO, $sysDate, $strNengetu);
            if (!$upd['result']) {
                throw new \Exception($upd['data']);
            }
            $strOrderNo = $fncUpdSaiban;
            if ($strOrderNo == "99999999") {
                $res["data"]["errorMsg"] = "W0030";
                //出力に失敗しました。
                throw new \Exception("W0030");
            }
            //カタログ注文データに登録する
            if (!isset($_POST['data'])) {
                throw new \Exception("param error");
            }
            $lblOrderDayShow = $_POST['data']['lblOrderDayShow'];
            $lblOrderTimeShow = $_POST['data']['lblOrderTimeShow'];
            $lblShopCD = $_POST['data']['BUSYOCD'];
            $ins = $this->HMTVE180CatalogOrderConfirm->insertSQL($strOrderNo, $lblOrderDayShow, $lblOrderTimeShow, $lblShopCD);
            if (!$ins['result']) {
                throw new \Exception($ins['data']);
            }
            //明細データ取得SQL
            $mailDataReader = $this->HMTVE180CatalogOrderConfirm->MailSQL($strOrderNo, $lblOrderDayShow, $lblOrderTimeShow, $lblShopCD);
            if (!$mailDataReader['result']) {
                throw new \Exception($mailDataReader['data']);
            }
            //ワークテーブルを削除する
            $del1 = $this->HMTVE180CatalogOrderConfirm->DEL1_SQL($lblOrderDayShow, $lblOrderTimeShow, $lblShopCD);
            if (!$del1['result']) {
                throw new \Exception($del1['data']);
            }
            //配送希望ワークテーブルを削除する
            $del2 = $this->HMTVE180CatalogOrderConfirm->DEL2_SQL($lblOrderDayShow, $lblOrderTimeShow, $lblShopCD);
            if (!$del2['result']) {
                throw new \Exception($del2['data']);
            }
            //Eメールを送信する
            //メールアドレスを取得する
            $mailAddressReader = $this->HMTVE180CatalogOrderConfirm->MAIL_ADDRESS_SEL();
            if (!$mailAddressReader['result']) {
                throw new \Exception($mailAddressReader['data']);
            }
            //配送希望チェック
            $chkHaisouKibou = $_POST['data']['chkHaisouKibou'];
            //メールを送信する
            $params = array(
                'strOrderNo' => $strOrderNo,
                'lblOrderDayShow' => $lblOrderDayShow,
                'chkHaisouKibou' => $chkHaisouKibou,
                'lblShopNameShow' => $_POST['data']['lblShopNameShow']
            );
            $sendmail = $this->sendMail($mailAddressReader['data'], $mailDataReader['data'], $params);
            if ($sendmail != TRUE) {
                $res["data"]["errorMsg"] = "W9999";
                $errorMsg = "メールの送信途中にエラーが発生しました【" . $strOrderNo . "】。管理者にご連絡下さい。";
                throw new \Exception($errorMsg);
            }
            //エラーがない場合、コミットする
            $this->HMTVE180CatalogOrderConfirm->Do_commit();
            $tranStartFlg = FALSE;
            $res['data']['strOrderNo'] = $strOrderNo;
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $this->HMTVE180CatalogOrderConfirm->Do_rollback();
            }
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //メールを送信する
    public function sendMail($strEmailAddressReader, $mailReader, $params)
    {
        try {
            include_once dirname(__DIR__) . '/Login/Component/Mailer/mail.inc';
            include_once dirname(__DIR__) . '/Login/Component/Mailer/PHPMailer/Exception.php';
            include_once dirname(__DIR__) . '/Login/Component/Mailer/PHPMailer/PHPMailer.php';
            include_once dirname(__DIR__) . '/Login/Component/Mailer/PHPMailer/SMTP.php';

            $mail = new PHPMailer();
            // 启用SMTP
            $mail->IsSMTP();

            $mail->Host = $Host;
            // sets the prefix to the servier
            $mail->SMTPSecure = $SMTPSecure;
            //SMTP服务器
            $mail->Port = $Port;
            //开启SMTP认证
//            $mail->SMTPAuth = true;
            $mail->SMTPAuth = false;
            // SMTP username SMTP用户名
//            $mail->Username = $Username;
            // SMTP password SMTP密码
 //           $mail->Password = $Password;
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
            $mail->Subject = $Subject1 . $params['strOrderNo'] . $Subject2;

            $mail->Encoding = $Encoding;
            //注文日
            $strBdy = $ContentOrderDay . $params['lblOrderDayShow'] . "<br/>";
            //部署
            $strBdy .= $ContentShopName . $params['lblShopNameShow'] . "<br/><br/>";
            //配送希望チェックOnなら、文言を追加する
            if ($params['chkHaisouKibou'] == '1') {
                //【配送を希望しない】
                $strBdy .= $ContentHaisou;
            }
            //【ご注文明細】
            $strBdy .= $ContentDetail;
            $strBdy .= $ContentCut;
            if (count($mailReader) > 0) {
                for ($i = 0; $i < count($mailReader); $i++) {
                    //コード
                    $strBdy .= $ContentCode . $mailReader[$i]['CATALOG_CD'] . "<br/>";
                    //カタログ名
                    $strBdy .= $ContentName . $this->ClsComFncHMTVE->FncNv($mailReader[$i]['CATALOG_NM']) . "<br/>";
                    //注文数
                    $strBdy .= $ContentNum . str_pad($mailReader[$i]['ORDER_NUM'], 6) . "<br/>";
                    $strBdy .= $ContentCut;
                }
            }
            if (count($strEmailAddressReader) > 0) {
                for ($i = 0; $i < count($strEmailAddressReader); $i++) {
                    $resAddr = $mail->AddAddress($strEmailAddressReader[$i]['MAIL_ADDRESS']);
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
        } catch (\Exception $e) {
            return FALSE;
        }
    }

}

