<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmFurikae;

//*******************************************
// * sample controller
//*******************************************
class FrmFurikaeController extends AppController
{

    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmFurikae;

    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsFncLog');
    }

    public function fncSearchFurikae()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        $postData = $_POST['data']['request'];
        try {

            $this->FrmFurikae = new FrmFurikae();

            $result = $this->FrmFurikae->fncSearchFurikae($postData);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);

    }

    // public function frmFurikae_Load()
    // {
    // $result = array(
    // 'result' => 'false',
    // 'data' => 'ErrorInfo',
    // 'row' => '',
    // );
    // try
    // {
//
    // $this -> FrmFurikae = new FrmFurikae();
//
    // $result = $this -> FrmFurikae -> frmFurikae_Load();
//
    // if (!$result['result'])
    // {
    // throw new Exception($result['data']);
    // }
//
    // }
    // catch(Exception $e)
    // {
    // $result['result'] = FALSE;
    // $result['data'] = $e -> getMessage();
    // }
    // $this -> set('result', $result);
    // $this -> render('frmfurikaeload');
    // }

    public function index()
    {
        // 画面表示内容の設定
        // レイアウトファイルの指定(app/View/Layouts/frmListSelect_layout.ctpを参照)

        $this->render('index', 'FrmFurikae_layout');
    }

    public function controlCheck()
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {

            $this->FrmFurikae = new FrmFurikae();
            $result = $this->FrmFurikae->ControlCheck();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncTorikomiPatternData()
    {
        $result = array(
            'result' => FALSE,
            'errMsg' => '',
            'data' => 'ErrorInfo'
        );

        try {
            $path = $this->ClsComFnc->FncGetPath('FRPTNINpath');
            if ($path == "" || $path == null) {
                $result['errMsg'] = "I9999";
                $result['data'] = "パターンデータが存在しません。";
                throw new \Exception($result['data']);
            }

            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
            $strErrLogPath = $strPath . "/" . $this->ClsComFnc->FncGetPath('FRPTNINpath');

            if (!file_exists($strErrLogPath)) {
                $result['errMsg'] = "I9999";
                $result['data'] = "パターンデータが存在しません。";
                throw new \Exception($result['data']);
            }

            $result['result'] = TRUE;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

}