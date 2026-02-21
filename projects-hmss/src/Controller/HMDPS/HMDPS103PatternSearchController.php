<?php
namespace App\Controller\HMDPS;

use App\Controller\AppController;
use App\Model\HMDPS\HMDPS103PatternSearch;
//*******************************************
// * sample controller
//*******************************************
class HMDPS103PatternSearchController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
    // public $autoRender = FALSE;
    // var $components = array(
    //     'RequestHandler',
    //     'ClsComFncHMDPS'
    // );
    public $HMDPS103PatternSearch;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFncHMDPS');
    }
    public function index()
    {
        // Viewファイル呼出し
        $this->render('index', 'HMDPS103PatternSearch_layout');
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

                $this->HMDPS103PatternSearch = new HMDPS103PatternSearch();

                $result = $this->HMDPS103PatternSearch->Kensaku_Click($postData);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                $tmpJqgridShow = $this->ClsComFncHMDPS->FncCreateJqGridShow($result['data']);
                $start = $tmpJqgridShow['start'];
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $tmpJqgrid = $this->ClsComFncHMDPS->FncCreateJqGridDataReload($result["data"], $totalPage, $page, $tmpCount, $start);
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
            $GetBusyoMstValue = $this->ClsComFncHMDPS->FncGetBusyoMstValue();

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
