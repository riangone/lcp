<?php
namespace App\Controller\HMAUD;

use App\Controller\AppController;
use App\Model\HMAUD\HMAUDKansaJinMente;

//*******************************************
// * sample controller
//*******************************************
class HMAUDKansaJinMenteController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    public $HMAUDKansaJinMente;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMAUD');
    }


    public function index()
    {
        $this->render('index', 'HMAUDKansaJinMente_layout');
    }

    public function fncSearchSpread()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );

        try {
            $this->HMAUDKansaJinMente = new HMAUDKansaJinMente();

            $GetSyainMstValue = $this->HMAUDKansaJinMente->FncGetSyainMstValue();

            if (!$GetSyainMstValue['result']) {
                throw new \Exception($GetSyainMstValue['data']);
            }
            $result = $this->HMAUDKansaJinMente->fncSelectMSTdata();
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
                $postdata['tableData'] = json_decode($postdata['tableData'], true);
                $this->HMAUDKansaJinMente = new HMAUDKansaJinMente();
                //トランザクション開始
                $this->HMAUDKansaJinMente->Do_transaction();
                $blnTran = TRUE;

                for ($i = 0; $i < count($postdata['tableData']); $i++) {
                    if ($postdata['tableData'][$i]['SYAIN_NO'] == "") {
                        continue;
                    }
                    $ressel = $this->HMAUDKansaJinMente->SelectMSTdata($postdata['tableData'][$i]['SYAIN_NO']);
                    if (!$ressel['result']) {
                        throw new \Exception($ressel['data']);
                    }
                    if (count((array) $ressel['data']) > 0) {
                        $result = $this->HMAUDKansaJinMente->updateMSTAUD($postdata['tableData'][$i]);
                        if (!$result['result']) {
                            throw new \Exception($result['data']);
                        }
                    } else {
                        $result = $this->HMAUDKansaJinMente->insertMSTAUD($postdata['tableData'][$i]);
                        if (!$result['result']) {
                            throw new \Exception($result['data']);
                        }
                    }
                }
                // コミット
                $this->HMAUDKansaJinMente->Do_commit();
                $result['result'] = TRUE;
            }
        } catch (\Exception $e) {
            //ロールバック
            if ($blnTran) {
                $this->HMAUDKansaJinMente->Do_rollback();
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $result['data'] = '';

        $this->fncReturn($result);

    }

}
