<?php
namespace App\Controller\HMAUD;

use App\Controller\AppController;
use App\Model\HMAUD\HMAUDKyotenMente;

//*******************************************
// * sample controller
//*******************************************
class HMAUDKyotenMenteController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
// public $autoRender = false;
    public $HMAUDKyotenMente;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFncHMAUD');
    }


    public function index()
    {
        $this->render('index', 'HMAUDKyotenMente_layout');
    }

    public function fncSearchSpread()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );

        try {
            $this->HMAUDKyotenMente = new HMAUDKyotenMente();
            $result = $this->HMAUDKyotenMente->fncSelectMSTdata();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if ($result['row'] > 0) {
                for ($i = 0; $i < count((array) $result['data']); $i++) {
                    $result['data'][$i]['DISP_SEQ'] = $i;
                }
            }
            $tmpJqgridShow = $this->ClsComFncHMAUD->FncCreateJqGridShow($result['data']);
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];
            $result = $this->ClsComFncHMAUD->FncCreateJqGridDataIndex($result['data'], $totalPage, $page, $tmpCount);
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
                $postdata['tableData'] = json_decode($postdata['tableData'], true);
                $this->HMAUDKyotenMente = new HMAUDKyotenMente();
                //トランザクション開始
                $this->HMAUDKyotenMente->Do_transaction();
                $blnTran = TRUE;

                $resultDEL = $this->HMAUDKyotenMente->DeleteMSTKTN();
                if (!$resultDEL['result']) {
                    throw new \Exception($resultDEL['data']);
                }
                for ($i = 0; $i < count($postdata['tableData']); $i++) {
                    $result = $this->HMAUDKyotenMente->insertMSTKTN($postdata['tableData'][$i]);
                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }
                }
                // コミット
                $this->HMAUDKyotenMente->Do_commit();
                $result['result'] = TRUE;
            }
        } catch (\Exception $e) {
            //ロールバック
            if ($blnTran) {
                $this->HMAUDKyotenMente->Do_rollback();
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $result['data'] = '';

        $this->fncReturn($result);

    }

}
