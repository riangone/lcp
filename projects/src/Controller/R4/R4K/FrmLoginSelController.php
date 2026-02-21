<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmLoginSel;

class FrmLoginSelController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;

    private $FrmLoginSel;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }

    public function index()
    {
        $this->render('index', 'FrmLoginSel_layout');
    }

    public function fncGetLoginInfo()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            if (isset($_POST['request'])) {
                $postData = $_POST['request'];
            }

            if ($postData['KJNBI'] != 'load') {
                $this->FrmLoginSel = new FrmLoginSel();
                $result = $this->FrmLoginSel->fncGetLoginInfo($postData["KJNBI"], $postData["SYAIN_NO"], $postData["PATTERN_ID"]);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {
                    $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($result['data']);
                    $page = $tmpJqgridShow['page'];
                    $totalPage = $tmpJqgridShow['totalPage'];
                    $tmpCount = (int) $tmpJqgridShow['count'];

                    $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridData($result["data"], $totalPage, $page, $tmpCount);

                    unset($_POST['request']);
                    $result = $tmpJqgrid;
                }
            } else {
                $result['result'] = TRUE;
                $result['data'] = "";
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();

            unset($_POST['request']);
        }

        $this->fncReturn($result);
    }

    public function fncLoadDeal()
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            $this->FrmLoginSel = new FrmLoginSel();
            $result = $this->FrmLoginSel->fncHKEIRICTL();



            //コントロールマスタ存在ﾁｪｯｸ
            if (!$result['result']) {
                throw new \Exception($result['data']);
            } else
                if (count((array) $result['data']) == 0) {
                    //コントロールマスタが存在していない場合
                    throw new \Exception("コントロールマスタが存在しません！");
                }

            //コンボボックスに当月年月を設定
            $strTougetu = $this->ClsComFnc->FncNv($result['data'][0]["TOUGETU"]);

            $result = $this->FrmLoginSel->getComboxListTable();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $result['strTougetu'] = $strTougetu;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

}