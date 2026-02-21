<?php

namespace App\Controller\Login;

use App\Controller\AppController;
use App\Model\Login\Login;
use mysqli;
use PHPMailer\PHPMailer\PHPMailer;
use Cake\Core\Exception\Exception;
use App\Model\HMTVE\FrmHMTVEMainMenu;
use App\Model\HDKAIKEI\FrmHDKAIKEIMainMenu;
use App\Model\HMDPS\FrmHMDPSMainMenu;
use App\Model\PPRM\FrmPPRMMainMenu;
//20240306 caina ins s
use App\Model\R4\FrmMainMenu;
//20240306 caina ins e

class LoginController extends AppController
{
    // public $autoRender = FALSE;
    private $Login;
    public $FrmHMTVEMainMenu;
    public $FrmHDKAIKEIMainMenu;
    public $FrmHMDPSMainMenu;
    public $FrmPPRMMainMenu;
    public $FrmMainMenu;
    public function index()
    {
        $this->render('/Login/index', 'Login_layout');
    }

    public function forgetAllStep()
    {
        $this->render('/Login/index', 'LoginForgetAllStep_layout');
    }

    public function forgetIdStep()
    {
        $this->render('/Login/index', 'LoginForgetIdStep_layout');
    }

    public function forgetPasswordStep()
    {
        $this->render('/Login/index', 'LoginForgetPasswordStep_layout');
    }

    public function sendMailSuc()
    {
        $this->render('/Login/index', 'LoginSendMailSuc_layout');
    }

    public function passwordsendToEmailById()
    {
        $postData = $_POST['request'];
        $status = false;
        $usrPass = '';
        $usrId = '';
        $usrName = '';
        $sendMailStatus = '';
        $sendMailErrorInfo = '';
        try {
            $this->Login = new Login();

            $result = $this->Login->connMysql();

            if (!$result['result']) {
                throw new \Exception($result['data'], $result['ERROR']);
            }
            $result = $this->Login->isIdExist($postData);

            if (!$result['result']) {
                throw new \Exception($result['data'], $result['ERROR']);
            }

            if ($result['data'] instanceof \mysqli_result && 0 != mysqli_num_rows($result['data'])) {
                while ($result['data'] instanceof \mysqli_result && $row = mysqli_fetch_assoc($result['data'])) {
                    $usrId = $row['USR_ID'];
                    $usrName = $row['USR_NAME'];
                    $usrPass = $row['PASS'];
                    $EmailAddress = $row['email'];
                }
                include_once 'Component/Mailer/mail.inc';
                include_once 'Component/Mailer/PHPMailer/Exception.php';
                include_once 'Component/Mailer/PHPMailer/PHPMailer.php';
                include_once 'Component/Mailer/PHPMailer/SMTP.php';

                $mail = new PHPMailer(true);

                $mail->IsSMTP();
                // 启用SMTP
                $mail->Host = $Host;

                $mail->SMTPSecure = $SMTPSecure;
                // sets the prefix to the servier
                $mail->Port = $Port;
                //SMTP服务器
                $mail->SMTPAuth = $SMTPAuth;
                //开启SMTP认证
                $mail->Username = $Username;
                // SMTP username SMTP用户名
                $mail->Password = $Password;
                // SMTP password SMTP密码
                $mail->From = $From;
                //发件人地址
                $mail->FromName = $FromName;
                //发件人
                $mail->AddAddress($EmailAddress);

                $mail->WordWrap = $WordWrap;
                // set word wrap to 50 characters
                $mail->IsHTML(true);
                // set email format to HTML
                $mail->CharSet = $CharSet;
                // 设置编码
                $mail->Subject = $Subject;

                $mail->Encoding = $Encoding;

                $str = $Content1;
                $str = $str . $Content2;
                $str = $str . $Content1;
                $str = $str . $ContentPASSWORD;
                $str = $str . $ContentResultPassword;
                $str = $str . $usrPass;
                $str = $str . $mark;
                $str = $str . '<br/>';
                $str = $str . $ContentTips;

                $mail->Body = $str;
                $status = $mail->Send();
                if ($status) {
                    $sendMailStatus = true;
                    $sendMailErrorInfo = 'suc';
                } else {
                    throw new \Exception($mail->ErrorInfo, 00000);
                }
            } else {
                $sendMailStatus = true;
                $sendMailErrorInfo = 'suc';
            }

            $result = array(
                'result' => true,
                'data' => '',
                'ERRORCODE' => '',
                'mailResult' => $sendMailStatus,
                'mailInfo' => $sendMailErrorInfo,
            );
        } catch (\Exception $e) {
            $result = array(
                'result' => false,
                'data' => $e->getMessage(),
                'ERRORCODE' => $e->getCode(),
                'mailResult' => false,
                'mailInfo' => $e->getMessage(),
            );
        }
        $this->fncReturn($result);
    }

    public function passwordsendToEmail()
    {
        $postData = $_POST['request'];
        $status = false;
        $usrPass = '';
        $usrId = '';
        $usrName = '';
        $sendMailStatus = '';
        $sendMailErrorInfo = '';

        try {
            $this->Login = new Login();
            $result = $this->Login->connMysql();
            if (!$result['result']) {
                throw new \Exception($result['data'], $result['ERROR']);
            }
            $result = $this->Login->isEmailExist($postData);
            if (!$result['result']) {
                throw new \Exception($result['data'], $result['ERROR']);
            }
            if ($result['data'] instanceof \mysqli_result && 0 != mysqli_num_rows($result['data'])) {
                while ($result['data'] instanceof \mysqli_result && $row = mysqli_fetch_assoc($result['data'])) {
                    $usrId = $row['USR_ID'];
                    $usrName = $row['USR_NAME'];
                    $usrPass = $row['PASS'];
                }
                $status = 'true';
                include_once 'Component/Mailer/mail.inc';
                include_once 'Component/Mailer/PHPMailer/Exception.php';
                include_once 'Component/Mailer/PHPMailer/PHPMailer.php';
                include_once 'Component/Mailer/PHPMailer/SMTP.php';

                $mail = new PHPMailer(true);
                $mail->IsSMTP();
                // 启用SMTP
                $mail->Host = $Host;

                $mail->SMTPSecure = $SMTPSecure;
                // sets the prefix to the servier
                $mail->Port = $Port;
                //SMTP服务器
                $mail->SMTPAuth = $SMTPAuth;
                //开启SMTP认证
                $mail->Username = $Username;
                // SMTP username SMTP用户名
                $mail->Password = $Password;
                // SMTP password SMTP密码
                $mail->From = $From;
                //发件人地址
                $mail->FromName = $FromName;
                //发件人
                $mail->AddAddress($postData['EmailAddress']);

                $mail->WordWrap = $WordWrap;
                // set word wrap to 50 characters
                $mail->IsHTML(true);
                // set email format to HTML
                $mail->CharSet = $CharSet;

                $mail->Encoding = $Encoding;
                // 设置编码
                $mail->Subject = $Subject;

                $str = $Content1;
                $str = $str . $Content2;
                $str = $str . $Content1;
                $str = $str . $ContentPASSWORD;
                $str = $str . $ContentResultPassword;
                $str = $str . $usrPass;
                $str = $str . $mark;
                $str = $str . '<br/>';

                $str = $str . $ContentTips;

                $mail->Body = $str;
                $status = $mail->Send();
                if ($status) {
                    $sendMailStatus = true;
                    $sendMailErrorInfo = 'suc';
                } else {
                    throw new \Exception($mail->ErrorInfo, 00000);
                }
            } else {
                $sendMailStatus = true;
                $sendMailErrorInfo = 'suc';
            }

            $result = array(
                'result' => true,
                'data' => '',
                'ERRORCODE' => '',
                'mailResult' => $sendMailStatus,
                'mailInfo' => $sendMailErrorInfo,
            );
        } catch (\Exception $e) {
            $result = array(
                'result' => false,
                'data' => $e->getMessage(),
                'ERRORCODE' => $e->getCode(),
                'mailResult' => false,
                'mailInfo' => $e->getMessage(),
            );
        }
        $this->fncReturn($result);
    }

    public function iDsendToEmail()
    {
        $postData = $_POST['request'];

        $status = false;
        $usrId = '';
        $usrName = '';
        $sendMailStatus = '';
        $sendMailErrorInfo = '';
        $this->Login = new Login();
        try {
            $result = $this->Login->connMysql();
            if (!$result['result']) {
                throw new \Exception($result['data'], $result['ERROR']);
            }
            $result = $this->Login->isEmailExist($postData);
            if (!$result['result']) {
                throw new \Exception($result['data'], $result['ERROR']);
            }
            if ($result['data'] instanceof \mysqli_result && 0 != mysqli_num_rows($result['data'])) {
                while ($result['data'] instanceof \mysqli_result && $row = mysqli_fetch_assoc($result['data'])) {
                    $usrId = $row['USR_ID'];
                    $usrName = $row['USR_NAME'];
                }
                include_once 'Component/Mailer/mail.inc';
                include_once 'Component/Mailer/PHPMailer/Exception.php';
                include_once 'Component/Mailer/PHPMailer/PHPMailer.php';
                include_once 'Component/Mailer/PHPMailer/SMTP.php';

                $mail = new PHPMailer(true);

                $mail->Host = $Host;

                $mail->SMTPSecure = $SMTPSecure;
                // sets the prefix to the servier
                $mail->Port = $Port;
                // set the SMTP port
                //SMTP服务器
                $mail->SMTPAuth = $SMTPAuth;
                $mail->SMTPDebug = 1;
                //开启SMTP认证
                $mail->IsSMTP();
                // 启用SMTP
                $mail->Username = $Username;
                // SMTP username SMTP用户名
                $mail->Password = $Password;
                // SMTP password SMTP密码
                $mail->From = $From;
                //发件人地址
                $mail->FromName = $FromName;
                //发件人
                $mail->AddAddress($postData['EmailAddress']);

                $mail->WordWrap = $WordWrap;
                // set word wrap to 50 characters
                $mail->IsHTML(true);
                // set email format to HTML
                $mail->CharSet = $CharSet;
                // 设置编码
                $mail->Encoding = $Encoding;

                $mail->Subject = $SubjectIDTIPS;

                $str = $Content1;
                $str = $str . $Content2;
                $str = $str . $Content1;
                $str = $str . $ContentID;
                $str = $str . $ContentResultID;

                $str = $str . $usrId;

                $str = $str . $mark;

                $str = $str . '<br/>';

                $str = $str . $ContentTips;

                $mail->Body = $str;

                $status = $mail->Send();
                if ($status) {
                    $sendMailStatus = true;
                    $sendMailErrorInfo = 'suc';
                } else {
                    throw new \Exception($mail->ErrorInfo, 00000);
                }
            } else {
                $sendMailStatus = true;
                $sendMailErrorInfo = 'suc';
            }

            $result = array(
                'result' => true,
                'data' => '',
                'ERRORCODE' => '',
                'mailResult' => $sendMailStatus,
                'mailInfo' => $sendMailErrorInfo,
            );
        } catch (\Exception $e) {
            $result = array(
                'result' => false,
                'data' => $e->getMessage(),
                'ERRORCODE' => $e->getCode(),
                'mailResult' => false,
                'mailInfo' => $e->getMessage(),
            );
        }
        $this->fncReturn($result);
    }

    public function login()
    {
        $postData = $_POST['request'];
        $result = array(
            'result' => false,
            'ERROR' => '',
            'data' => '',
        );
        $login = array();
        try {
            $this->Login = new Login();

            $result = $this->Login->connMysql();
            if ($result['data'] instanceof mysqli) {
                mysqli_query($result['data'], 'set names utf8');
                mysqli_set_charset($result['data'], 'utf8');
            }


            if (!$result['result']) {
                throw new \Exception($result['data'], (int) $result['ERROR']);
            }

            $result = $this->Login->loginSys($postData);

            if (!$result['result']) {
                throw new \Exception($result['data'], (int) $result['ERROR']);
            }

            if ($result['data'] instanceof \mysqli_result && 0 != mysqli_num_rows($result['data'])) {
                /*
                while ($row = mysql_fetch_assoc($result['data']))
                {
                $pass = $row["PASS"];
                $name = $row["USR_NAME"];
                }
                */
                $row = mysqli_fetch_assoc($result['data']);
                $pass = $row['PASS'];
                $name = $row['USR_NAME'];
                $r4_name = '車両業務システム';
                //---20161229 li INS S.
                $app_name = 'APPシステム';
                //---20161229 li INS E.
                for ($i = 1; $i <= 10; ++$i) {
                    $flg_key = 'SYS' . $i . '_FLG';
                    $arr_flg[$flg_key] = $row[$flg_key];
                    $cd_key = 'SYS' . $i . '_CD';
                    $arr_cd[$cd_key] = $row[$cd_key];

                    if ('1' == $arr_flg[$flg_key] && '006' == $arr_cd[$cd_key]) {
                        $r4_name = '管理会計システム';
                        break;
                    }
                }
                $session = $this->request->getSession();
                if ($pass == $postData['pass']) {
                    $arr_flg = array();
                    $arr_cd = array();
                    $session->write('login_user', $postData['usr_id']);
                    $session->write('username', $name);
                    //20211122 ZHANGBOWEN INS S
                    if (isset($postData['tabId'])) {
                        if ('HMTVE' == $postData['tabId'] || 'HMDPS' == $postData['tabId'] || $postData['tabId'] == 'HDKAIKEI' || $postData['tabId'] == 'R4K' || 'PPRM' == $postData['tabId']) {
                            $session->write('sys_id', $postData['tabId']);
                            if ('HMTVE' == $postData['tabId']) {
                                $this->FrmHMTVEMainMenu = new FrmHMTVEMainMenu();
                                $roledata = $this->FrmHMTVEMainMenu->getmenulist($postData['usr_id'], '2');
                                if (!$roledata['result']) {
                                    throw new \Exception($roledata['data']);
                                }
                            } elseif ($postData['tabId'] == 'HMDPS') {
                                $this->FrmHMDPSMainMenu = new FrmHMDPSMainMenu();
                                $roledata = $this->FrmHMDPSMainMenu->getmenulist($postData['usr_id'], '3');
                                if (!$roledata['result']) {
                                    throw new \Exception($roledata['data']);
                                }
                            }
                            // 20230726 YIN INS S
                            elseif ($postData['tabId'] == 'HDKAIKEI') {
                                $this->FrmHDKAIKEIMainMenu = new FrmHDKAIKEIMainMenu();
                                $roledata = $this->FrmHDKAIKEIMainMenu->getmenulist($postData['usr_id'], '3');
                                if (!$roledata['result']) {
                                    throw new \Exception($roledata['data']);
                                }
                            }
                            // 20230726 YIN INS E
                            //20240306 caina ins s
                            elseif ($postData['tabId'] == 'R4K') {
                                $this->FrmMainMenu = new FrmMainMenu();
                                $roledata = $this->FrmMainMenu->getmenulist($postData['usr_id']);
                                if (!$roledata['result']) {
                                    throw new \Exception($roledata['data']);
                                }
                            }
                            //20240306 caina ins e
                            // 20240823 lhb INS S
                            elseif ($postData['tabId'] == 'PPRM') {
                                $this->FrmMainMenu = new FrmPPRMMainMenu();
                                $roledata = $this->FrmMainMenu->getmenulist15($postData['usr_id'], '15');
                                if (!$roledata['result']) {
                                    throw new \Exception($roledata['data']);
                                }
                            }
                            // 20240823 lhb INS E
                            if ($roledata['row'] > 0) {
                                if (null != $roledata['data'][0]['PATTERN_ID']) {
                                    $session->write('PatternID', $roledata['data'][0]['PATTERN_ID']);
                                }
                                if (null != $roledata['data'][0]['BUSYO_CD']) {
                                    //部署コード
                                    $session->write('BusyoCD', $roledata['data'][0]['BUSYO_CD']);
                                }
                                if ('HMTVE' == $postData['tabId'] || 'PPRM' == $postData['tabId']) {
                                    if (null != $roledata['data'][0]['SYAIN_NM']) {
                                        $session->write('SyainNM', $roledata['data'][0]['SYAIN_NM']);
                                    }
                                } else {
                                    if (null != $roledata['data'][0]['PATTERN_ID']) {
                                        $session->write('PatternID', $roledata['data'][0]['PATTERN_ID']);
                                    }
                                }
                                // 20240823 lhb INS S
                                if ('PPRM' == $postData['tabId']) {
                                    $sysdata = $this->FrmMainMenu->getSysKb();
                                    $syskbdata = mysqli_fetch_assoc($sysdata);
                                    if ($syskbdata) {
                                        $SYS_KB = $syskbdata['sys_cd'];
                                        $SYS_KB = (int) $SYS_KB;
                                        $session->write('Sys_KB', $SYS_KB);
                                    }
                                }
                                // 20240823 lhb INS E
                            }
                        }
                    }

                    //20211122 ZHANGBOWEN INS E
                    $login['result'] = true;
                    $login['loginInfo'] = 'loginSuc';

                    $session->write('r4_name', $r4_name);
                    //---20161229 li INS S.
                    $session->write('app_name', $app_name);
                    //---20161229 li INS E.
                } else {
                    $login['result'] = true;
                    $login['loginInfo'] = 'loginFail';
                }
            } else {
                $login['result'] = true;
                $login['loginInfo'] = 'loginFail';
            }
        } catch (\Exception $e) {
            $login['result'] = false;
            $login['ERRORData'] = $e->getMessage();
            $login['ERROR'] = $e->getCode();
        }

        $this->fncReturn($login);
    }

    public function loginout()
    {
        $session = $this->request->getSession();
        $session->delete('login_user');
        $session->delete('username');
        $result = array('result' => true);
        $this->fncReturn($result);
    }

    public function logineduser()
    {
        $session = $this->request->getSession();
        $result = array();
        if ($session->read('login_user')) {
            $result['userid'] = $session->read('login_user');
            $result['username'] = $session->read('username');
        }
        $this->fncReturn($result);
    }

    public function SendMail($mailAddress, $SubjectIDTIPS, $body)
    {
        include_once 'Component/Mailer/mail.inc';
        include_once 'Component/Mailer/PHPMailer/Exception.php';
        include_once 'Component/Mailer/PHPMailer/PHPMailer.php';
        include_once 'Component/Mailer/PHPMailer/SMTP.php';

        $mail = new PHPMailer(true);

        $mail->IsSMTP();
        // 启用SMTP
        $mail->Host = $Host;

        $mail->SMTPSecure = $SMTPSecure;
        // sets the prefix to the servier
        $mail->Port = $Port;
        // set the SMTP port
        //SMTP服务器
        $mail->SMTPAuth = $SMTPAuth;
        //开启SMTP认证
        $mail->Username = $Username;
        // SMTP username SMTP用户名
        $mail->Password = $Password;
        // SMTP password SMTP密码
        $mail->From = $From;
        //发件人地址
        $mail->FromName = $FromName;
        //发件人
        $mail->AddAddress($mailAddress);

        $mail->WordWrap = $WordWrap;
        // set word wrap to 50 characters
        $mail->IsHTML(true);
        // set email format to HTML
        $mail->CharSet = $CharSet;
        // 设置编码
        $mail->Encoding = $Encoding;

        $mail->Subject = $SubjectIDTIPS;

        $mail->Body = $body;

        $sendFlag = $mail->Send();

        return $sendFlag;
    }

    //Because POST request need echo directly.So not use render.Reference resources of FrmShikakariTorikomiController/fncCheckFile.
    public function donothing()
    {
        $result['result'] = true;
        $result['data'] = 'session is outdate';
        $this->fncReturn($result);
    }
}