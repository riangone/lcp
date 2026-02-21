<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmKaikei;

//*******************************************
// * sample controller
//*******************************************
class FrmKaikeiController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmKaikei;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsFncLog');
    }
    public function index()
    {
        // 画面表示内容の設定
        // レイアウトファイルの指定(app/View/Layouts/FrmBusyoSearch_layout.ctpを参照)

        $this->render('index', 'FrmKaikei_layout');
    }

    public function getSysDate()
    {

        $TIME = $this->ClsComFnc->FncGetSysDate("Y/m/d H:i:s");
        $TIME = substr($TIME, 0, 10);

        $this->fncReturn($TIME);

    }

    public function fncSearchKaikei()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            if (isset($_POST['request'])) {
                $postData = $_POST['request'];
                $this->FrmKaikei = new FrmKaikei();
                $result = $this->FrmKaikei->fncSelect($postData, "", "", "");

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {

                    $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($result['data'], TRUE);
                    $sortStr = $tmpJqgridShow['sortStr'];
                    $start = $tmpJqgridShow['start'];
                    $limit = $tmpJqgridShow['limit'];
                    $page = $tmpJqgridShow['page'];
                    $totalPage = $tmpJqgridShow['totalPage'];
                    $tmpCount = (int) $tmpJqgridShow['count'];

                    $result = $this->FrmKaikei->fncSelect($postData, $sortStr, $start, $limit);
                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }
                    foreach ((array) $result['data'] as $key => $value) {

                        foreach ((array) $value as $key1 => $value1) {

                            if ($key1 == 'KEIJO_DT') {

                                $result['data'][$key][$key1] = substr($value1, 0, 4) . '/' . substr($value1, 4, 2) . '/' . substr($value1, 6, 2);

                            }
                        }
                    }

                    $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridData($result["data"], $totalPage, $page, $tmpCount, $start);

                    unset($_POST['request']);
                    $result = $tmpJqgrid;
                    $this->fncReturn($result);
                }
            } else {
                $this->fncReturn($result);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();

            unset($_POST['request']);
            $this->fncReturn($result);
        }
    }

    public function fncDeleteFurikae()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            $postData = $_POST['data']['request'];

            $this->FrmKaikei = new FrmKaikei();
            $result = $this->FrmKaikei->fncDeleteFurikae($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $result['data'] = "";
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function controlCheck()
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {

            $this->FrmKaikei = new FrmKaikei();
            $result = $this->FrmKaikei->ControlCheck();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

}