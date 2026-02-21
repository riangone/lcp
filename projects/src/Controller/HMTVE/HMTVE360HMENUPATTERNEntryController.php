<?php
namespace App\Controller\HMTVE;

use App\Controller\AppController;
use App\Model\HMTVE\HMTVE360HMENUPATTERNEntry;
use App\Controller\HMTVE\HMTVEController;
//*******************************************
// * sample controller
//*******************************************
class HMTVE360HMENUPATTERNEntryController extends AppController
{
    public $autoLayout = TRUE;
    public $FrmJinkenhiEnt = "";
    private $HMTVE360HMENUPATTERNEntry;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
    }
    public function index()
    {
        // 画面表示内容の設定
        $this->render('index', 'HMTVE360HMENUPATTERNEntry_layout');
    }

    public function pageLoad()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            $this->HMTVE360HMENUPATTERNEntry = new HMTVE360HMENUPATTERNEntry();
            $result = $this->HMTVE360HMENUPATTERNEntry->pageloadSQL(HMTVEController::SYS_KB);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $tmpJqgridShow = $this->ClsComFncHMTVE->FncCreateJqGridShow($result['data']);
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];
            $result = $this->ClsComFncHMTVE->FncCreateJqGridDataIndex($result["data"], $totalPage, $page, $tmpCount);
        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function gvRightsSelectedIndexChanged()
    {
        $result = array(
            'result' => TRUE,
            'data' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['request'])) {
                $postdata = $_POST['request'];
                $this->HMTVE360HMENUPATTERNEntry = new HMTVE360HMENUPATTERNEntry();
                if ($postdata['type'] == 'insert') {
                    $result = $this->HMTVE360HMENUPATTERNEntry->SelectedIndexChangedSQL(HMTVEController::SYS_KB, $postdata['selectedRow']);
                } else {
                    $result = $this->HMTVE360HMENUPATTERNEntry->btnAddClickSQL(HMTVEController::SYS_KB);
                }
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                $tmpJqgridShow = $this->ClsComFncHMTVE->FncCreateJqGridShow($result['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $result = $this->ClsComFncHMTVE->FncCreateJqGridDataIndex($result["data"], $totalPage, $page, $tmpCount);
            } else {
                throw new \Exception('値が受信されません');
            }
        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function btnLoginClick()
    {
        $blnTran = FALSE;
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['data'])) {
                $postdata = $_POST['data'];
                $this->HMTVE360HMENUPATTERNEntry = new HMTVE360HMENUPATTERNEntry();
                //トランザクション開始
                $this->HMTVE360HMENUPATTERNEntry->Do_transaction();
                $blnTran = TRUE;
                $result = $this->HMTVE360HMENUPATTERNEntry->btnLoginSelectSQL(HMTVEController::SYS_KB, $postdata['txtRightsID']);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                if ($postdata['type'] == 'insert') {

                    if ($result['row'] != 0) {
                        $result['key'] = 'E0016';
                        throw new \Exception('E0016');
                    }
                } else
                    if ($postdata['type'] == 'update') {
                        if ($result['row'] == 0) {
                            $result['key'] = 'W0004';
                            throw new \Exception('他のユーザーにより更新されています。最新の情報を確認してください。');
                        }
                    }
                if ($result['row'] != 0) {
                    $result = $this->HMTVE360HMENUPATTERNEntry->btnLoginUpdateSQL(HMTVEController::SYS_KB, $postdata['txtRightsName'], $postdata['txtRightsID']);
                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }
                } else {
                    $result = $this->HMTVE360HMENUPATTERNEntry->btnLoginInsertSQL(HMTVEController::SYS_KB, $postdata['txtRightsName'], $postdata['txtRightsID']);
                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }
                }
                $result = $this->HMTVE360HMENUPATTERNEntry->btnLoginDeleteSQL(HMTVEController::SYS_KB, $postdata['txtRightsID']);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                for ($i = 0; $i < count($postdata['rowData']); $i++) {
                    if ($postdata['rowData'][$i]['KBN'] == 'Yes') {

                        $result = $this->HMTVE360HMENUPATTERNEntry->btnLoginClickSQL($postdata['rowData'][$i], HMTVEController::SYS_KB, $postdata['txtRightsID']);

                        if (!$result['result']) {
                            throw new \Exception($result['data']);
                        }
                    }
                }
                $result['key'] = '';
                // コミット
                $this->HMTVE360HMENUPATTERNEntry->Do_commit();
            }
        } catch (\Exception $e) {
            //ロールバック
            if ($blnTran) {
                $this->HMTVE360HMENUPATTERNEntry->Do_rollback();
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $result['data'] = '';

        $this->fncReturn($result);
    }

    public function btnDeleteClick()
    {
        $blnTran = FALSE;
        $result = array(
            'result' => FALSE,
            'data' => '',
            'key' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['data'])) {
                $postdata = $_POST['data'];
                $this->HMTVE360HMENUPATTERNEntry = new HMTVE360HMENUPATTERNEntry();
                //トランザクション開始
                $this->HMTVE360HMENUPATTERNEntry->Do_transaction();
                $blnTran = TRUE;
                $result = $this->HMTVE360HMENUPATTERNEntry->btnDeleteClickSQL(HMTVEController::SYS_KB, $postdata['txtRightsID']);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                $result = $this->HMTVE360HMENUPATTERNEntry->btnLoginDeleteSQL(HMTVEController::SYS_KB, $postdata['txtRightsID']);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                $result['key'] = '';
                // コミット
                $this->HMTVE360HMENUPATTERNEntry->Do_commit();
            }
        } catch (\Exception $e) {
            //ロールバック
            if ($blnTran) {
                $this->HMTVE360HMENUPATTERNEntry->Do_rollback();
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $result['data'] = '';

        $this->fncReturn($result);
    }

}
