<?php
/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                             内容                               担当
 * YYYYMMDD           #ID                                     XXXXXX                            GSDL
 * -------------------------------------------------------------------------------------------------------
 */
namespace App\Controller\HDKAIKEI;

use App\Controller\AppController;
use App\Model\HDKAIKEI\HDKBankSearch;

class HDKBankSearchController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    public $HDKBankSearch = null;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHDKAIKEI');
    }
    //　デフォルトで最初に実行される機能
    public function index()
    {
        // Viewファイル呼出し
        $this->render('index', 'HDKBankSearch_layout');
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
                $this->HDKBankSearch = new HDKBankSearch();

                $result = $this->HDKBankSearch->btnHyouji_Click($postData);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                $tmpJqgridShow = $this->ClsComFncHDKAIKEI->FncCreateJqGridShow($result['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $start = $tmpJqgridShow['start'];
                $tmpJqgrid = $this->ClsComFncHDKAIKEI->FncCreateJqGridDataReload($result["data"], $totalPage, $page, $tmpCount, $start);
                $result = $tmpJqgrid;
            }
        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }
}
