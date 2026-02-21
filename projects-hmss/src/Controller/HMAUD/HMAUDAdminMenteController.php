<?php
namespace App\Controller\HMAUD;

use App\Controller\AppController;
use App\Model\HMAUD\HMAUDAdminMente;

//*******************************************
// * sample controller
//*******************************************
class HMAUDAdminMenteController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
// public $autoRender = false;
    public $HMAUDAdminMente;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFncHMAUD');
    }
    public function index()
    {
        // 画面表示内容の設定
        $this->render('index', 'HMAUDAdminMente_layout');
    }

    public function fncSearchSpread()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );

        try {
            $this->HMAUDAdminMente = new HMAUDAdminMente();
            $GetSyainMstValue = $this->HMAUDAdminMente->FncGetSyainMstValue();

            if (!$GetSyainMstValue['result']) {
                throw new \Exception($GetSyainMstValue['data']);
            }

            $result = $this->HMAUDAdminMente->fncSelectMSTdata();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $tmpJqgridShow = $this->ClsComFncHMAUD->FncCreateJqGridShow($result['data']);
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];

            $result = $this->ClsComFncHMAUD->FncCreateJqGridDataIndex($result['data'], $totalPage, $page, $tmpCount);
            $result->GetSyainMstValue = $GetSyainMstValue['data'];
        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }

        // Viewファイル呼出し
        $this->fncReturn($result);
    }

    public function btnUpdateClick()
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
                $this->HMAUDAdminMente = new HMAUDAdminMente();
                //トランザクション開始
                $this->HMAUDAdminMente->Do_transaction();
                $blnTran = TRUE;

                $resultDEL = $this->HMAUDAdminMente->DeleteADMIN();
                if (!$resultDEL['result']) {
                    throw new \Exception($resultDEL['data']);
                }
                for ($i = 0; $i < count($postdata['tableData']); $i++) {
                    $result = $this->HMAUDAdminMente->insertADMIN($postdata['tableData'][$i]);
                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }
                }
                // コミット
                $this->HMAUDAdminMente->Do_commit();
                $result['result'] = TRUE;
            }
        } catch (\Exception $e) {
            //ロールバック
            if ($blnTran) {
                $this->HMAUDAdminMente->Do_rollback();
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $result['data'] = '';

        $this->fncReturn($result);
    }

}