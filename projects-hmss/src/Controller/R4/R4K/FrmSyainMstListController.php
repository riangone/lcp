<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmSyainMstList;

class FrmSyainMstListController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $result;
    public $Do_conn;
    public $FrmSyainMstList;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsFncLog');
    }
    public function index()
    {
        $this->render('index', 'FrmSyainMstList_layout');
    }

    public function fncFromSyainSelect()
    {
        try {
            if (!isset($_POST['request'])) {
                $this->fncReturn($this->result);
                return;
            }
            $postData = $_POST['request'];

            //モデルの仕様するクラスを定義
            $this->FrmSyainMstList = new FrmSyainMstList();
            //モデルクラスのselect処理を呼出し
            $this->result = $this->FrmSyainMstList->fncFromSyainSelect($postData);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }

            $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($this->result['data']);

            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];
            for ($ii = 0; $ii < count((array) $this->result['data']); $ii++) {
                foreach ((array) ($this->result['data'][$ii]) as $key => $value) {
                    $this->result['data'][$ii][$key] = trim($this->ClsComFnc->fncNv($value));
                }
            }
            $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridData($this->result["data"], $totalPage, $page, $tmpCount);
            $this->result = $tmpJqgrid;
        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
        }
        $this->fncReturn($this->result);
    }

    public function fncTxtBusyoCDValidating()
    {
        try {
            $objBusyoMst = $this->ClsComFnc->GS_BUSYOMST;
            //モデルクラスのselect処理を呼出し
            $tf = $this->ClsComFnc->FncGetBusyoMstValue(trim($_POST['data']['busyoCD']), $objBusyoMst);
            if ($tf['result']) {
                $this->result['result'] = TRUE;
                $this->result['data'] = $objBusyoMst['strBusyoNM'];
            } else {
                throw new \Exception($tf['data']);
            }
        } catch (\Exception $ex) {
            $this->result['result'] = False;
            $this->result['data'] = $ex->getMessage();
        }
        $this->fncReturn($this->result);
    }

}