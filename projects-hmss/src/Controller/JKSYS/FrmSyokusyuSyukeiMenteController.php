<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmSyokusyuSyukeiMente;
//*******************************************
// * sample controller
//*******************************************
class FrmSyokusyuSyukeiMenteController extends AppController
{
    public $autoLayout = TRUE;
    public $FrmSyokusyuSyukeiMente;

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncJKSYS');
    }
    public function index()
    {
        $this->render('index', 'FrmSyokusyuSyukeiMente_layout');
    }

    //データ取得(評語職種集計区分マスタ)
    public function fncGetHSSTTLKBNMST()
    {
        $result = array(
            'result' => false,
            'error' => ''
        );

        try {
            $this->FrmSyokusyuSyukeiMente = new FrmSyokusyuSyukeiMente();
            //データ取得(評語職種集計区分マスタ)
            $hssttl = $this->FrmSyokusyuSyukeiMente->FncGetHSSTTLKBNMST("");
            if (!$hssttl['result']) {
                throw new \Exception($hssttl['data']);
            }

            $tmpJqgridShow = $this->ClsComFncJKSYS->FncCreateJqGridShow($hssttl['data']);
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];

            $tmpJqgrid = $this->ClsComFncJKSYS->FncCreateJqGridData($hssttl["data"], $totalPage, $page, $tmpCount);

            $this->fncReturn($tmpJqgrid);
        } catch (\Exception $e) {
            //前端jqgrid共通，显示error信息的title有问题，所以这里返回值为true
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
            $this->fncReturn($result);
        }
    }

    //データ取得(評語職種集計区分マスタ)
    public function fncGetCODEMST()
    {
        $result = array(
            'result' => false,
            'error' => ''
        );

        try {
            $this->FrmSyokusyuSyukeiMente = new FrmSyokusyuSyukeiMente();
            //データ取得(コードマスタ)
            if (isset($_POST['request']['txtKbn'])) {
                $txtKbn = $_POST['request']['txtKbn'];
                $alldatas = $_POST['request']['alldatas'];
                $result = $this->FrmSyokusyuSyukeiMente->FncGetHSSTTLMST($txtKbn);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                //reset
                for ($i = 0; $i < count($alldatas); $i++) {
                    $alldatas[$i]['display_code'] = '';
                }
                for ($i = 0; $i < $result['row']; $i++) {
                    if ($result['data'][$i]['SYOKUSYU_CD']) {
                        $alldatas[$i]['display_code'] = $result['data'][$i]['SYOKUSYU_CD'];
                    }
                }
                $result['data'] = $alldatas;
            } else {
                $result = $this->FrmSyokusyuSyukeiMente->FncGetCODEMST();
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
            }

            $tmpJqgridShow = $this->ClsComFncJKSYS->FncCreateJqGridShow($result['data']);
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];
            $tmpJqgrid = $this->ClsComFncJKSYS->FncCreateJqGridData($result["data"], $totalPage, $page, $tmpCount);
            $this->fncReturn($tmpJqgrid);
        } catch (\Exception $e) {
            //前端jqgrid共通，显示error信息的title有问题，所以这里返回值为true
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
            $this->fncReturn($result);
        }
    }

    //***** データを登録する **********
    //(評語職種集計区分マスタ)
    public function fncUpdInsHSSTTLKBNMST()
    {
        $this->FrmSyokusyuSyukeiMente = new FrmSyokusyuSyukeiMente();
        $result = array(
            'result' => false,
            'error' => ''
        );
        $blnTran = FALSE;

        try {
            $txtKbn = $_POST['data']['txtKbn'];

            //トランザクションを開始する
            $this->FrmSyokusyuSyukeiMente->Do_transaction();
            $blnTran = TRUE;
            if ($_POST['data']['isDisabled'] == 'true') {
                //修正の場合
                $result_upd = $this->FrmSyokusyuSyukeiMente->FncUpdHSSTTLKBNMST($_POST['data']);
            } else {
                //重複チェック(新規登録の場合のみ)
                //データ取得(評語職種集計区分マスタ)
                $hssttl = $this->FrmSyokusyuSyukeiMente->FncGetHSSTTLKBNMST($txtKbn);
                if (!$hssttl['result']) {
                    throw new \Exception($hssttl['data']);
                }
                //データが存在する場合
                if ($hssttl['row'] > 0) {
                    throw new \Exception("W9999");
                }

                //新規登録の場合
                $result_upd = $this->FrmSyokusyuSyukeiMente->FncInsHSSTTLKBNMST($_POST['data']);
            }
            if (!$result_upd['result']) {
                throw new \Exception($result_upd['data']);
            }

            //(評語職種集計マスタ)
            $result_del = $this->FrmSyokusyuSyukeiMente->FncDelHSSTTLMST($txtKbn);
            if (!$result_del['result']) {
                throw new \Exception($result_del['data']);
            }

            foreach ($_POST['data']['data'] as $value) {
                $result_ins = $this->FrmSyokusyuSyukeiMente->FncInsHSSTTLMST($value, $txtKbn);
                if (!$result_ins['result']) {
                    throw new \Exception($result_ins['data']);
                }
            }
            //コミット
            $this->FrmSyokusyuSyukeiMente->Do_commit();

            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
            if ($blnTran) {
                $this->FrmSyokusyuSyukeiMente->Do_rollback();
            }
        }

        $this->fncReturn($result);
    }

    //データを削除する(評語職種集計区分マスタ・評語職種集計マスタ
    public function fncDelHSSTTLKBNMST()
    {
        $this->FrmSyokusyuSyukeiMente = new FrmSyokusyuSyukeiMente();
        $result = array(
            'result' => false,
            'error' => ''
        );
        $blnTran = FALSE;
        try {
            //トランザクションを開始する
            $this->FrmSyokusyuSyukeiMente->Do_transaction();
            $blnTran = TRUE;
            //データを削除する(評語職種集計区分マスタ・評語職種集計マスタ
            $result = $this->FrmSyokusyuSyukeiMente->FncDelHSSTTLKBNMST($_POST['data']['txtKbn']);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $result = $this->FrmSyokusyuSyukeiMente->FncDelHSSTTLMST($_POST['data']['txtKbn']);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //コミット処理を行う
            $this->FrmSyokusyuSyukeiMente->Do_commit();
            $result['data'] = '';
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();

            if ($blnTran) {
                $this->FrmSyokusyuSyukeiMente->Do_rollback();
            }
        }

        $this->fncReturn($result);
    }
}
