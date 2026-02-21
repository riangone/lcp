<?php
namespace App\Controller\HMTVE;
use App\Controller\AppController;
use App\Model\HMTVE\HMTVE400HDTCOMPANYMSTEntry;
//*******************************************
// * sample controller
//*******************************************
class HMTVE400HDTCOMPANYMSTEntryController extends AppController
{
    public $autoLayout = TRUE;
    public $HMTVE400HDTCOMPANYMSTEntry;
    // public $autoRender = FALSE;

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
    }


    /*
        ***********************************************************************
        '処 理 名：初期表示
        '関 数 名：index
        '引    数：無し
        '戻 り 値 ：無し
        '処理説明 ：
        '**********************************************************************
    */

    public function index()
    {
        $this->render('index', 'HMTVE400HDTCOMPANYMSTEntry_layout');
    }

    //登録ボタンのイベント
    public function btnSearchClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['request'])) {
                $postdata = $_POST['request'];
                //var_dump($postdata);
                $this->HMTVE400HDTCOMPANYMSTEntry = new HMTVE400HDTCOMPANYMSTEntry();
                $result = $this->HMTVE400HDTCOMPANYMSTEntry->dataGet($postdata['COMPANY_CD'], $postdata['COMPANY_NM']);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                $tmpJqgridShow = $this->ClsComFncHMTVE->FncCreateJqGridShow($result['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $result = $this->ClsComFncHMTVE->FncCreateJqGridData($result["data"], $totalPage, $page, $tmpCount);
            }
        } catch (\Exception $e) {
            //前端jqgrid共通，显示error信息的title有问题，所以这里返回值为true
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);

    }

    //存在チェックを行う
    //更新処理を行う
    public function check()
    {
        $result = array(
            'result' => false,
            'error' => ''
        );
        $blnTran = FALSE;
        try {
            $this->HMTVE400HDTCOMPANYMSTEntry = new HMTVE400HDTCOMPANYMSTEntry();

            $postdata = $_POST['data'];
            $strMode = $postdata['strMode'];
            $result = $this->HMTVE400HDTCOMPANYMSTEntry->check($postdata['COMPANY_CD']);
            if ($strMode == 'INSERT') {
                if (count((array) $result['data']) > 0) {
                    throw new \Exception('E0005');
                }
            } elseif ($strMode == 'UPDATE') {
                if (count((array) $result['data']) == 0) {
                    throw new \Exception('W0004');
                }
            }

            $this->HMTVE400HDTCOMPANYMSTEntry->Do_transaction();
            $blnTran = TRUE;
            if ($strMode == 'INSERT' && count((array) $result['data']) == 0) {
                $result = $this->HMTVE400HDTCOMPANYMSTEntry->insert_data($postdata['COMPANY_CD'], $postdata['COMPANY_NM']);
            } elseif ($strMode == 'UPDATE' && count((array) $result['data']) > 0) {
                $result = $this->HMTVE400HDTCOMPANYMSTEntry->upDate_data($postdata['COMPANY_CD'], $postdata['COMPANY_NM']);
            }

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //コミット処理を行う
            $this->HMTVE400HDTCOMPANYMSTEntry->Do_commit();
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();

            if ($blnTran) {
                $this->HMTVE400HDTCOMPANYMSTEntry->Do_rollback();
            }
        }
        $result['data'] = '';

        $this->fncReturn($result);
    }

    //削除処理を行う
    public function delete()
    {
        $result = array(
            'result' => false,
            'error' => ''
        );
        $blnTran = FALSE;
        try {
            $this->HMTVE400HDTCOMPANYMSTEntry = new HMTVE400HDTCOMPANYMSTEntry();

            $postdata = $_POST['data'];
            $this->HMTVE400HDTCOMPANYMSTEntry->Do_transaction();
            $blnTran = TRUE;
            $result = $this->HMTVE400HDTCOMPANYMSTEntry->delete($postdata['COMPANY_CD']);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //コミット処理を行う
            $this->HMTVE400HDTCOMPANYMSTEntry->Do_commit();
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();

            if ($blnTran) {
                $this->HMTVE400HDTCOMPANYMSTEntry->Do_rollback();
            }
        }

        $result['data'] = '';
        $this->fncReturn($result);
    }

}
