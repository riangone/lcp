<?php
namespace App\Controller\HDKAIKEI;
use App\Controller\AppController;
use App\Model\HDKAIKEI\HDKSyainSearch;
//*******************************************
// * sample controller
//*******************************************
class HDKSyainSearchController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
    // public $autoRender = FALSE;
    public $HDKSyainSearch = null;
    // public $ClsComFncHDKAIKEI = null;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHDKAIKEI');
    }
    //　デフォルトで最初に実行される機能
    public function index()
    {
        // Viewファイル呼出し
        $this->render('index', 'HDKSyainSearch_layout');
    }
    public function btnHyoujiClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            //データの取得
            if (isset($_POST['request'])) {
                $postData = $_POST['request'];

                $this->HDKSyainSearch = new HDKSyainSearch();

                $result = $this->HDKSyainSearch->btnHyouji_Click($postData);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                $tmpJqgridShow = $this->ClsComFncHDKAIKEI->FncCreateJqGridShow($result['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $tmpJqgrid = $this->ClsComFncHDKAIKEI->FncCreateJqGridDataIndex($result["data"], $totalPage, $page, $tmpCount);
                $result = $tmpJqgrid;
            }

        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //部署名取得
    public function fncGetBusyoMstValue()
    {

        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            //部署コード
            $GetBusyoMstValue = $this->ClsComFncHDKAIKEI->FncGetCreatBusyoMstValue();

            if (!$GetBusyoMstValue['result']) {
                throw new \Exception($GetBusyoMstValue['data']);
            }
            $result['data'] = $GetBusyoMstValue['data'];
            $result['result'] = TRUE;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

}
