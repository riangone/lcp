<?php
namespace App\Controller\HMAUD;

use App\Controller\AppController;
use App\Model\HMAUD\HMAUDKuruMente;

//*******************************************
// * sample controller
//*******************************************
class HMAUDKuruMenteController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFncHMAUD');
        // $this->loadComponent('ClsLogControl');
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
        $this->render('index', 'HMAUDKuruMente_layout');
    }

    public function pageload()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            $HMAUDKuruMente = new HMAUDKuruMente();
            $courres = $HMAUDKuruMente->getCours();
            if (!$courres['result']) {
                throw new \Exception($courres['data']);
            }

            $data = $courres['data'];
            if (count((array) $data) == 0) {
                throw new \Exception('W0024');
            }

            $tmpJqgridShow = $this->ClsComFncHMAUD->FncCreateJqGridShow($data);
            $start = $tmpJqgridShow['start'];
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];
            $result = $this->ClsComFncHMAUD->FncCreateJqGridDataIndex($data, $totalPage, $page, $tmpCount, $start);

        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function updData()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );

        try {
            //データの取得
            if (isset($_POST['data'])) {
                $postData = $_POST['data'];
                $insData = $postData['data'];
                $HMAUDKuruMente = new HMAUDKuruMente();

                $HMAUDKuruMente->Do_transaction();
                $blnTran = TRUE;

                $courDelRes = $HMAUDKuruMente->courDel();
                if (!$courDelRes['result']) {
                    throw new \Exception($courDelRes['data']);
                }
                for ($i = 0; $i < count($insData); $i++) {
                    if ($insData[$i]['COURS'] !== "") {
                        $insertDataRes = $HMAUDKuruMente->insertData($insData[$i]);
                        if (!$insertDataRes['result']) {
                            throw new \Exception($insertDataRes['data']);
                        }
                    }
                }

                $HMAUDKuruMente->Do_commit();
                $result['result'] = true;
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
            if ($blnTran) {
                $HMAUDKuruMente->Do_rollback();
            }
        }
        $result['data'] = '';

        $this->fncReturn($result);
    }

}