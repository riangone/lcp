<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmKyotenFurikaeEdit;

//*******************************************
// * sample controller
//*******************************************
class FrmKyotenFurikaeEditController extends AppController
{

    public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    public $FrmKyotenFurikaeEdit;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFncJKSYS');
    }
    public function index()
    {
        // 画面表示内容の設定
        // レイアウトファイルの指定(app/View/Layouts/frmListSelect_layout.ctpを参照)

        $this->render('index', 'FrmKyotenFurikaeEdit_layout');
    }

    public function subSpreadReShow()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {

            if (isset($_POST['request'])) {
                $postdata = $_POST['request'];
                $this->FrmKyotenFurikaeEdit = new FrmKyotenFurikaeEdit();
                $result = $this->FrmKyotenFurikaeEdit->fncFurikaeExist($postdata);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                $tmpJqgridShow = $this->ClsComFncJKSYS->FncCreateJqGridShow($result['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $result = $this->ClsComFncJKSYS->FncCreateJqGridDataIndex($result["data"], $totalPage, $page, $tmpCount);
            }
        } catch (\Exception $e) {
            //前端jqgrid共通，显示error信息的title有问题，所以这里返回值为true
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();

        }
        $this->fncReturn($result);
    }

    public function fncFurikaeInputLoad()
    {
        $result = array(
            'result' => false,
            'data' => array(),
            'error' => 'ErrorInfo',
        );

        try {
            $postData = $_POST['data'];
            $this->FrmKyotenFurikaeEdit = new FrmKyotenFurikaeEdit();
            $GetTougetu = $this->FrmKyotenFurikaeEdit->fncGetTougetu();
            if (!$GetTougetu['result']) {
                throw new \Exception($GetTougetu['data']);
            }
            $result['data']['GetTougetu'] = $GetTougetu['data'];
            $result['row'] = $GetTougetu['row'];
            if ($postData['flg'] !== 'INS' && $result['row'] > 0) {
                $FurikaeExist = $this->FrmKyotenFurikaeEdit->fncFurikaeExist($postData);
                if (!$FurikaeExist['result']) {
                    throw new \Exception($FurikaeExist['data']);
                }
                $result['data']['FurikaeExist'] = $FurikaeExist['data'];
            }
            $getAllSyainJqGrid = $this->FrmKyotenFurikaeEdit->fncGetAllSyainJqGrid();
            if (!$getAllSyainJqGrid['result']) {
                throw new \Exception($getAllSyainJqGrid['data']);
            }
            $result['data']['AllSyainJqGrid'] = $getAllSyainJqGrid['data'];
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);

    }

    public function fncGetUCNO()
    {
        $result = array(
            'result' => false,
            'data' => '',
            'error' => 'ErrorInfo',
        );

        try {
            $postData = $_POST['data'];
            $this->FrmKyotenFurikaeEdit = new FrmKyotenFurikaeEdit();

            $result = $this->FrmKyotenFurikaeEdit->fncM41E10Check($postData);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $result['result'] = true;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncFurikaeExistadd()
    {
        $result = array(
            'result' => false,
            'data' => '',
            'error' => 'ErrorInfo',
        );
        try {
            $postData = $_POST['data'];
            $FrmKyotenFurikaeEdit = new FrmKyotenFurikaeEdit();
            $result = $FrmKyotenFurikaeEdit->fncFurikaeExist($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncExistsCheck()
    {
        $result = array(
            'result' => false,
            'error' => 'ErrorInfo',
        );
        $result['data'] = array(
            'SyainMstCheck' => true,
            'M41E10Check' => true,
        );

        try {
            $postData = $_POST['data'];
            $this->FrmKyotenFurikaeEdit = new FrmKyotenFurikaeEdit();
            if ($postData['strSyainNO'] != "") {
                $SyainMstCheck = $this->FrmKyotenFurikaeEdit->fncSyainMstCheck($postData);
                if (!$SyainMstCheck['result']) {
                    throw new \Exception($SyainMstCheck['data']);
                }
                if ($SyainMstCheck['row'] == 0) {
                    $result['data']['SyainMstCheck'] = false;
                }

            }
            if ($postData['strCMNNO'] != "") {
                $M41E10Check = $this->FrmKyotenFurikaeEdit->fncM41E10Check($postData);
                if (!$M41E10Check['result']) {
                    throw new \Exception($M41E10Check['data']);
                }
                if ($M41E10Check['row'] == 0) {
                    $result['data']['M41E10Check'] = false;
                }

            }
            $result['result'] = true;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncInsertFurikae()
    {
        $blnTran = FALSE;
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => 'ErrorInfo'
        );
        $this->FrmKyotenFurikaeEdit = new FrmKyotenFurikaeEdit();
        try {
            $postData = $_POST['data'];

            $this->FrmKyotenFurikaeEdit->Do_transaction();
            $blnTran = TRUE;

            if ($postData['strMenteFlg'] == "INS") {
                $result = $this->FrmKyotenFurikaeEdit->fncEdaNoSet($postData);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                $postData['txtEdaNO'] = $result['data'][0]['NO'];
            }
            $result = $this->FrmKyotenFurikaeEdit->fncDeleteFurikae($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if ($postData['txtFurikaeKin'] == "") {
                $postData['txtFurikaeKin'] = 0;
            }

            $result = $this->FrmKyotenFurikaeEdit->fncInsertFurikae($postData['txtSyainCD'], $postData['txtFurikaeKin'], "M", $postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            foreach ($postData['rowData'] as $value) {
                if ($value['INPUTED'] == "1") {
                    if ($value['FURIKAE_KIN'] == "") {
                        $value['FURIKAE_KIN'] = 0;
                    }
                    $result = $this->FrmKyotenFurikaeEdit->fncInsertFurikae($value['SYAIN_CD'], $value['FURIKAE_KIN'], "S", $postData);
                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }
                }
            }
            $this->FrmKyotenFurikaeEdit->Do_commit();
            $result['data'] = $postData['txtEdaNO'];

            $blnTran = FALSE;
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        if ($blnTran) {
            $this->FrmKyotenFurikaeEdit->Do_rollback();
        }

        $this->fncReturn($result);
    }

    public function fncDeleteFurikae()
    {
        $result = array(
            'result' => FALSE,
            'data' => ''
        );
        $tranStartFlg = FALSE;
        $this->FrmKyotenFurikaeEdit = new FrmKyotenFurikaeEdit();
        try {
            $postData = $_POST['data'];
            //トランザクション開始
            $this->FrmKyotenFurikaeEdit->Do_transaction();
            $tranStartFlg = TRUE;
            $result = $this->FrmKyotenFurikaeEdit->fncDeleteFurikae($postData);
            if (!$result['result']) {
                throw new \Exception('E0004');
            }
            //コミット
            $this->FrmKyotenFurikaeEdit->Do_commit();
            $result['rows'] = 1;
            $result['data'] = "";
            $result['result'] = TRUE;
            $tranStartFlg = FALSE;
        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $this->FrmKyotenFurikaeEdit->Do_rollback();
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

}
