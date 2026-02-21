<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmHRAKUDataSet;

class FrmHRAKUDataSetController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmHRAKUDataSet = null;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    public function index()
    {
        $this->render('index', 'FrmHRAKUDataSet_layout');
    }
    public function fnCallListDialog()
    {
        $this->FrmHRAKUDataSet = new FrmHRAKUDataSet();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            $result = $this->FrmHRAKUDataSet->getData();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($result['data']);
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];
            $res = $this->ClsComFnc->FncCreateJqGridDataIndex($result['data'], $totalPage, $page, $tmpCount);
        } catch (\Exception $e) {
            //前端jqgrid共通，显示error信息的title有问题，所以这里返回值为true
            $res['result'] = TRUE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }
    public function btnChooseClick()
    {
        $this->FrmHRAKUDataSet = new FrmHRAKUDataSet();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        $blnTran = FALSE;
        try {
            if (!isset($_POST['data'])) {
                throw new \Exception('no param');
            }
            $params = $_POST['data'];
            $already = $this->FrmHRAKUDataSet->alreadyUpdated($params['idStr']);
            if (!$already['result']) {
                throw new \Exception($already['data']);
            }
            if ($already['row'] > 0) {
                $res['data'] = $already['data'];
                //既に設定されたデータがあるので、確認してください。
                throw new \Exception('already');
            }

            $getNo = $this->FrmHRAKUDataSet->getMaxGroupNo();
            if (!$getNo['result']) {
                throw new \Exception($getNo['data']);
            }
            //トランザクション開始
            $this->FrmHRAKUDataSet->Do_transaction();
            $blnTran = TRUE;
            //グループ№
            $params['no'] = $getNo['data'][0]['MAX_CD'];
            //取込件数
            $selArr = explode(",", $params['idStr']);
            $params['count'] = count($selArr);
            $group = $this->FrmHRAKUDataSet->insGroupData($params);
            if (!$group['result']) {
                throw new \Exception($group['data']);
            }
            $upd = $this->FrmHRAKUDataSet->setSelectedData($params);
            if (!$upd['result']) {
                throw new \Exception($upd['data']);
            }
            //コミット処理を行う
            $this->FrmHRAKUDataSet->Do_commit();
            $blnTran = FALSE;
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
            if ($blnTran) {
                $this->FrmHRAKUDataSet->Do_rollback();
            }
        }
        $this->fncReturn($res);
    }
}