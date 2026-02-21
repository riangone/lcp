<?php

namespace App\Controller\R4\R4G;

use App\Controller\AppController;
use App\Model\R4\R4G\FrmOptionInput;

//*******************************************
// * sample controller
//*******************************************
class FrmOptionInputController extends AppController
{
    public $autoLayout = TRUE;
    public $FrmOptionInput;
    public $DB_Conn;
    public $Do_Excute;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
// public $autoRender = false;

    // var $components = array('RequestHandler');

    public function index()
    {
        $this->render('index', 'FrmOptionInput_layout');
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
    public function fncMeisaiFirstSet()
    {
        $postData = $_POST['data']['request'];
        try {

            $this->FrmOptionInput = new FrmOptionInput();
            $result = $this->FrmOptionInput->fncMeisaiFirstSet($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
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
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            $postData = $_POST['data']['request'];
            if (isset($postData)) {
                $this->FrmOptionInput = new FrmOptionInput();
                $result = $this->FrmOptionInput->fncMeisaiSecondSet($postData);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

            } else {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
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
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );
        $postData = $_POST['data']['request'];
        try {
            if (isset($postData)) {
                $this->FrmOptionInput = new FrmOptionInput();
                $result1 = $this->FrmOptionInput->fnc41E12TeikaSum($postData);
                if (!$result1['result']) {
                    throw new \Exception($result1['data']);
                }
                $result2 = $this->FrmOptionInput->fncKasouDifTeika($postData);
                if (!$result2['result']) {
                    throw new \Exception($result2['data']);
                }
                $result['result'] = TRUE;
                $result['data'] = array();
                $result['data']['FzkTeikaTbl'] = $result1['data'];
                $result['data']['KasTeikaTbl'] = $result2['data'];

            } else {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncToriNmSelect()
    {
        $postData = '';
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
        );
        $postData = $_POST['data']['request'];
        try {
            if (isset($postData)) {
                $this->FrmOptionInput = new FrmOptionInput();
                $result = $this->FrmOptionInput->fncToriNmSelect($postData);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
            } else {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncDeleteUpdataMeisai()
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
                $sysdate = $this->ClsComFnc->FncGetSysDate("Y/m/d H:i:s");
                $this->FrmOptionInput = new FrmOptionInput();
                $res = $this->FrmOptionInput->Do_conn();
                if (!$res['result']) {
                    throw new \Exception($res['data']);
                }
                $this->FrmOptionInput->Do_transaction();
                $result1 = $this->FrmOptionInput->fncDeleteKasouMeisai($postData['arr']);
                if (!$result1['result']) {
                    throw new \Exception($result1['data']);
                }
                // if ($this -> Do_Excute['result'])
                // {
                $postnum = count($postData);
                if ($postnum != 1) {
                    $num = count($postData['jqData']);
                    for ($i = 0; $i < $num; $i++) {
                        $result2 = $this->FrmOptionInput->fncOptionMeisaiIns($postData['arr'], $postData['jqData'][$i], $sysdate, $i + 1);
                        if (!$result2['result']) {
                            throw new \Exception($result2['data']);
                        }
                    }
                }
                // }
                $this->FrmOptionInput->Do_commit();
                $result['result'] = TRUE;
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $this->FrmOptionInput->Do_rollback();
        }

        $this->FrmOptionInput->Do_close();
        $this->fncReturn($result);

    }

    // public function fncDeleteKasouMeisai()
    // {
    // $postData = '';
    // $result = array(
    // 'result' => 'false',
    // 'data' => 'ErrorInfo'
    // );
    // $postData = $_POST['request'];
    // try
    // {
    // if (isset($postData))
    // {
    // $this -> FrmOptionInput = new FrmOptionInput();
    // }
    // else
    // {
    // $result = array(
    // 'result' => FALSE,
    // 'data' => 'param error'
    // );
    // }
    // }
    // catch(\Exception $e)
    // {
    // $result['result'] = FALSE;
    // $result['data'] = $e -> getMessage();
    // }
    //
    // $this -> set('result', $result);
    // $this -> render('fncdeletekasoumeisai');
    // }

}
