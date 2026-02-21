<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmSyokaiFurikaeList;

//*******************************************
// * sample controller
//*******************************************
class FrmSyokaiFurikaeListController extends AppController
{

    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmSyokaiFurikaeList = '';
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }

    public function index()
    {
        // 画面表示内容の設定
        // レイアウトファイルの指定(app/View/Layouts/frmListSelect_layout.ctpを参照)

        $this->render('index', 'FrmSyokaiFurikaeList_layout');
    }

    public function fncDeleteFurikae()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        $postData = $_POST['data']['request'];
        try {

            $this->FrmSyokaiFurikaeList = new FrmSyokaiFurikaeList();

            $result = $this->FrmSyokaiFurikaeList->fncDeleteFurikae($postData);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
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

            $this->FrmSyokaiFurikaeList = new FrmSyokaiFurikaeList();

            $result = $this->FrmSyokaiFurikaeList->fncSearchFurikae($postData);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function frmFurikaeLoad()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        try {

            $this->FrmSyokaiFurikaeList = new FrmSyokaiFurikaeList();

            $result = $this->FrmSyokaiFurikaeList->frmFurikae_Load();

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