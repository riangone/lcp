<?php
namespace App\Controller\R4\R4G;

use App\Controller\AppController;
use App\Model\R4\R4G\FrmSpecialInput;

//*******************************************
// * sample controller
//*******************************************
class FrmSpecialInputController extends AppController
{
    public $autoLayout = TRUE;
    public $FrmSpecialInput;
    public $Do_Excute;
    public $DB_Conn;
    // public $autoRender = false;
    public function index()
    {
        $this->render('index', 'FrmSpecialInput_layout');
    }

    public function initialize(): void
    {
        parent::initialize();
        if ($this->request->is('ajax')) {
            $this->viewBuilder()->setClassName('Ajax');
        }
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsFncLog');
    }
    // '架装明細ﾃｰﾌﾞﾙにデータが存在していない場合は、M41E12から表示する
    public function fncMeisaiFirstSet()
    {
        $postData = '';
        try {
            if (isset($_POST['data']['request'])) {
                $postData = $_POST['data']['request'];
            }
            if ($postData == '') {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            } else {
                $this->FrmSpecialInput = new FrmSpecialInput();
                $result = $this->FrmSpecialInput->fncMeisaiFirstSet($postData);
                if (!$result['result']) {
                    throw new \Exception(!$result['data']);
                }
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncMeisaiSecondSet()
    {
        $postData = '';
        try {
            if (isset($_POST['data']['request'])) {
                $postData = $_POST['data']['request'];
            }
            if ($postData == '') {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            } else {
                $FrmSpecialInput = new FrmSpecialInput();
                $result = $FrmSpecialInput->fncMeisaiSecondSet($postData);
                if (!$result['result']) {
                    throw new \Exception(!$result['data']);
                }
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fnc41E12TeikaSum()
    {
        $postData = '';
        try {
            if (isset($_POST['data']['request'])) {
                $postData = $_POST['data']['request'];
            }
            if ($postData == '') {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            } else {
                $this->FrmSpecialInput = new FrmSpecialInput();
                $result = $this->FrmSpecialInput->fnc41E12TeikaSum($postData);
                if (!$result['result']) {
                    throw new \Exception(!$result['data']);
                }
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncKasouDifTeika()
    {
        $postData = '';
        try {
            if (isset($_POST['data']['request'])) {
                $postData = $_POST['data']['request'];
            }
            if ($postData == '') {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            } else {
                $this->FrmSpecialInput = new FrmSpecialInput();
                $result = $this->FrmSpecialInput->fncKasouDifTeika($postData);
                if (!$result['result']) {
                    throw new \Exception(!$result['data']);
                }
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //delete and updata
    public function fncDeleteUpdataMeisai()
    {
        $postData = '';
        $postnum = '';
        $num = '';
        try {
            if (isset($_POST['data']['request'])) {
                $postData = $_POST['data']['request'];
            }
            if ($postData == '') {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            } else {
                $sysdate = $this->ClsComFnc->FncGetSysDate("Y/m/d H:i:s");
                $this->FrmSpecialInput = new FrmSpecialInput();
                $this->DB_Conn = $this->FrmSpecialInput->Do_conn();
                if (!$this->DB_Conn['result']) {
                    throw new \Exception($this->DB_Conn['data']);
                }
                //架装明細ﾃｰﾌﾞﾙに登録開始
                $this->FrmSpecialInput->Do_transaction();
                $this->Do_Excute = $this->FrmSpecialInput->fncDeleteKasouMeisai($postData['arr']);
                if (!$this->Do_Excute['result']) {
                    throw new \Exception($this->Do_Excute['data']);
                }
                if ($this->Do_Excute['result']) {
                    $postnum = count($postData);
                    if ($postnum != 1) {
                        $num = count($postData['jqData']);
                        for ($i = 0; $i < $num; $i++) {
                            $this->Do_Excute = $this->FrmSpecialInput->fncOptionMeisaiIns($postData['arr'], $postData['jqData'][$i], $sysdate, $i + 1);
                            if (!$this->Do_Excute['result']) {
                                throw new \Exception($this->Do_Excute['data']);
                            }
                        }
                    }
                }
                $this->FrmSpecialInput->Do_commit();
                $result['result'] = TRUE;
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $this->FrmSpecialInput->Do_rollback();
        }
        $this->fncReturn($result);
        $this->FrmSpecialInput->Do_close();
    }

    public function fncToriNmSelect()
    {
        $postData = '';
        try {
            if (isset($_POST['data']['request'])) {
                $postData = $_POST['data']['request'];
            }
            if ($postData == '') {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            } else {
                $this->FrmSpecialInput = new FrmSpecialInput();
                $result = $this->FrmSpecialInput->fncToriNmSelect($postData);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

}
