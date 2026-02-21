<?php
namespace App\Controller\HDKAIKEI;

use App\Controller\AppController;
use App\Model\HDKAIKEI\HDKPatternSearch;

//*******************************************
// * sample controller
//*******************************************
class HDKPatternSearchController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    public $HDKPatternSearch;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHDKAIKEI');
    }
    public function index()
    {
        // Viewファイル呼出し
        $this->render('index', 'HDKPatternSearch_layout');
    }

    public function kensakuClick()
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

                $this->HDKPatternSearch = new HDKPatternSearch();

                $result = $this->HDKPatternSearch->Kensaku_Click($postData);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                $tmpJqgridShow = $this->ClsComFncHDKAIKEI->FncCreateJqGridShow($result['data']);
                $start = $tmpJqgridShow['start'];
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $tmpJqgrid = $this->ClsComFncHDKAIKEI->FncCreateJqGridDataReload($result["data"], $totalPage, $page, $tmpCount, $start);
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
            $GetBusyoMstValue = $this->ClsComFncHDKAIKEI->FncGetBusyoMstValue();

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
