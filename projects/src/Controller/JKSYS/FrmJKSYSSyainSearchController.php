<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmJKSYSSyainSearch;
//*******************************************
// * sample controller
//*******************************************
class FrmJKSYSSyainSearchController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFncJKSYS');
    }
    public function index()
    {
        // 画面表示内容の設定
        // レイアウトファイルの指定(app/View/Layouts/FrmSyainSearch_layout.ctpを参照)

        $this->render('index', 'FrmSyainSearch_layout');
    }

    public function fncDataSet()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            if (isset($_POST['request'])) {
                $postData = $_POST['request'];
                if ($postData['txtSyainKN'] != "") {
                    $postData['txtSyainKN'] = mb_convert_kana($postData['txtSyainKN'], "C");
                    $postData['txtSyainKN'] = mb_convert_kana($postData['txtSyainKN'], "k");
                }
                $frmJKSYSSyainSearch = new FrmJKSYSSyainSearch();

                $result = $frmJKSYSSyainSearch->fncDataSet($postData);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                $tmpJqgridShow = $this->ClsComFncJKSYS->FncCreateJqGridShow($result['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $tmpJqgrid = $this->ClsComFncJKSYS->FncCreateJqGridDataIndex($result["data"], $totalPage, $page, $tmpCount);
                $result = $tmpJqgrid;
            }
        } catch (\Exception $e) {
            //前端jqgrid共通，显示error信息的title有问题，所以这里返回值为true
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

}
