<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmSyainSearch;

//*******************************************
// * sample controller
//*******************************************
class FrmSyainSearchController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmSyainSearch;
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
        // レイアウトファイルの指定(app/View/Layouts/FrmSyainSearch_layout.phpを参照)
        $this->render('index', 'FrmSyainSearch_layout');
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
            $this->FrmSyainSearch = new FrmSyainSearch();

            $result = $this->FrmSyainSearch->fncDataSet($postData);

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