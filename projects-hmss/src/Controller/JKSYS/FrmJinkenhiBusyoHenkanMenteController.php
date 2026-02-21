<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmJinkenhiBusyoHenkanMente;
class FrmJinkenhiBusyoHenkanMenteController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;

    // var $components = array(
    //     'RequestHandler',
    //     'ClsComFncJKSYS'
    // );
    public $FrmJinkenhiBusyoHenkanMente;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncJKSYS');
    }
    public function index()
    {
        $this->render('index', 'FrmJinkenhiBusyoHenkanMente_layout');
    }

    //画面初期化(画面起動時)
    public function fncGetBUSYOCNV()
    {
        $result = array(
            'result' => FALSE,
            'error' => ""
        );
        try {
            $this->FrmJinkenhiBusyoHenkanMente = new FrmJinkenhiBusyoHenkanMente();
            //データ取得(人件費部署変換マスタ)
            $result = $this->FrmJinkenhiBusyoHenkanMente->FncGetBUSYOCNV();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $tmpJqgridShow = $this->ClsComFncJKSYS->FncCreateJqGridShow($result['data']);
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];
            $result = $this->ClsComFncJKSYS->FncCreateJqGridData($result["data"], $totalPage, $page, $tmpCount);
        } catch (\Exception $e) {
            //前端jqgrid共通，显示error信息的title有问题，所以这里返回值为true
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //データ取得(部署マスタ)
    public function fncGetBUMON()
    {
        $result = array(
            'result' => FALSE,
            'error' => ""
        );

        try {
            $FrmJinkenhiBusyoHenkanMente = new FrmJinkenhiBusyoHenkanMente();
            $DR = $FrmJinkenhiBusyoHenkanMente->FncGetBUMON();
            if (!$DR['result']) {
                throw new \Exception($DR['data']);
            }
            $result['BUMON'] = $DR['data'];
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //新規登録の場合
    public function fncInsBUSYOCNV()
    {
        $this->FrmJinkenhiBusyoHenkanMente = new FrmJinkenhiBusyoHenkanMente();
        $result = array(
            'result' => FALSE,
            'error' => ""
        );
        $blnTran = FALSE;
        try {
            if (isset($_POST['data'])) {
                $txtAfter = $_POST['data']['txtAfter'];
                $txtBefore = $_POST['data']['txtBefore'];
                //トランザクション開始
                $this->FrmJinkenhiBusyoHenkanMente->Do_transaction();
                $blnTran = TRUE;
                //データ取得(人件費部署変換マスタ)
                $DT_B = $this->FrmJinkenhiBusyoHenkanMente->FncGetBUSYOCNV2($txtBefore);
                if (!$DT_B['result']) {
                    throw new \Exception($DT_B['data']);
                }
                //データが存在する場合
                if ($DT_B['row'] > 0) {
                    $result['key'] = "W9999";
                    throw new \Exception("既に該当の変換前部署コードは存在しています。");
                }
                //新規登録の場合
                $result_ins = $this->FrmJinkenhiBusyoHenkanMente->FncInsBUSYOCNV($txtAfter, $txtBefore);
                if (!$result_ins['result']) {
                    throw new \Exception($result_ins['data']);
                }
                //コミット処理を行う
                $this->FrmJinkenhiBusyoHenkanMente->Do_commit();

                $result['result'] = TRUE;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();

            if ($blnTran) {
                $this->FrmJinkenhiBusyoHenkanMente->Do_rollback();
            }
        }
        $this->fncReturn($result);
    }

    //修正の場合
    public function fncUpdBUSYOCNV()
    {
        $FrmJinkenhiBusyoHenkanMente = new FrmJinkenhiBusyoHenkanMente();
        $result = array(
            'result' => FALSE,
            'error' => ""
        );
        $blnTran = FALSE;
        try {
            if (isset($_POST['data'])) {
                $txtAfter = $_POST['data']['txtAfter'];
                $txtBefore = $_POST['data']['txtBefore'];
                //トランザクション開始
                $FrmJinkenhiBusyoHenkanMente->Do_transaction();
                $blnTran = TRUE;
                //修正の場合
                $result_upd = $FrmJinkenhiBusyoHenkanMente->FncUpdBUSYOCNV($txtAfter, $txtBefore);
                if (!$result_upd['result']) {
                    throw new \Exception($result_upd['data']);
                }

                //コミット
                $FrmJinkenhiBusyoHenkanMente->Do_commit();

                $result['result'] = TRUE;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
            if ($blnTran) {
                $FrmJinkenhiBusyoHenkanMente->Do_rollback();
            }
        }
        $this->fncReturn($result);
    }

    //削除ボタンクリック
    public function fncDelBUSYOCNV()
    {
        $FrmJinkenhiBusyoHenkanMente = new FrmJinkenhiBusyoHenkanMente();
        $result = array(
            'result' => FALSE,
            'error' => ""
        );
        $blnTran = FALSE;

        try {
            if (isset($_POST['data'])) {
                $postData = $_POST["data"]["BEFORE"];
                //トランザクションを開始する
                $FrmJinkenhiBusyoHenkanMente->Do_transaction();
                $blnTran = TRUE;
                //データを削除する(評語職種集計区分マスタ・評語職種集計マスタ
                $result_del = $FrmJinkenhiBusyoHenkanMente->FncDelBUSYOCNV($postData);
                if (!$result_del['result']) {
                    throw new \Exception($result_del['data']);
                }
                //コミット処理を行う
                $FrmJinkenhiBusyoHenkanMente->Do_commit();

                $result['result'] = TRUE;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();

            if ($blnTran) {
                $FrmJinkenhiBusyoHenkanMente->Do_rollback();
            }
        }
        $this->fncReturn($result);
    }

}
