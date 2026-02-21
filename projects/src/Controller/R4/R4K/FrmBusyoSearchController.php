<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmBusyoSearch;

//*******************************************
// * sample controller
//*******************************************
class FrmBusyoSearchController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmBusyoSearch;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    public function index()
    {
        // 画面表示内容の設定
        // レイアウトファイルの指定(app/View/Layouts/FrmBusyoSearch_layout.ctpを参照)
        $this->render('index', 'FrmBusyoSearch_layout');
    }

    public function fncDataSet()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        $postData = $_POST['data']['request'];
        try {
            $this->FrmBusyoSearch = new FrmBusyoSearch();

            $result = $this->FrmBusyoSearch->fncDataSet($postData);

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