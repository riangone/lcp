<?php
namespace App\Controller\HMTVE;
use App\Controller\AppController;
use App\Model\HMTVE\HMTVE160CatalogOrderBase;
//*******************************************
// * sample controller
//*******************************************
class HMTVE160CatalogOrderBaseController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    public $HMTVE160CatalogOrderBase;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
    }
    //  デフォルトで最初に実行される機能
    public function index()
    {
        // 画面表示内容の設定
        // Viewファイル呼出し
        $this->render('index', 'HMTVE160CatalogOrderBase_layout');
    }

    //ページ初期化
    //本ｶﾀﾛｸﾞﾃｰﾌﾞﾙの生成
    public function fncSearchSpread()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );

        try {
            $this->HMTVE160CatalogOrderBase = new HMTVE160CatalogOrderBase();
            $result = $this->HMTVE160CatalogOrderBase->getHonCatalog();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $resultGridView = $this->creatGridView($result['data']);
            if (!$resultGridView['result']) {
                throw new \Exception($resultGridView['data']);
            }

            $tmpJqgridShow = $this->ClsComFncHMTVE->FncCreateJqGridShow($resultGridView['data']);
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];
            $result = $this->ClsComFncHMTVE->FncCreateJqGridDataIndex($resultGridView['data'], $totalPage, $page, $tmpCount);
        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }

        // Viewファイル呼出し
        $this->fncReturn($result);
    }

    //ページ初期化
    //メールアドレスﾃｰﾌﾞﾙの生成
    public function fncSearchSpreadMail()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );

        try {
            $this->HMTVE160CatalogOrderBase = new HMTVE160CatalogOrderBase();
            $result = $this->HMTVE160CatalogOrderBase->getMail();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $tmpJqgridShow = $this->ClsComFncHMTVE->FncCreateJqGridShow($result['data']);
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];
            $result = $this->ClsComFncHMTVE->FncCreateJqGridDataIndex($result['data'], $totalPage, $page, $tmpCount);
        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }

        // Viewファイル呼出し
        $this->fncReturn($result);
    }

    //ページ初期化
    //用品ｶﾀﾛｸﾞﾃｰﾌﾞﾙの生成
    public function fncSearchSpreadYouCata()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );

        try {
            $this->HMTVE160CatalogOrderBase = new HMTVE160CatalogOrderBase();
            $result = $this->HMTVE160CatalogOrderBase->getYouCatalog();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $resultGridView = $this->creatGridView($result['data']);
            if (!$resultGridView['result']) {
                throw new \Exception($resultGridView['data']);
            }

            $tmpJqgridShow = $this->ClsComFncHMTVE->FncCreateJqGridShow($resultGridView['data']);
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];
            $result = $this->ClsComFncHMTVE->FncCreateJqGridDataIndex($resultGridView['data'], $totalPage, $page, $tmpCount);
        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }

        // Viewファイル呼出し
        $this->fncReturn($result);
    }

    //ページ初期化
    //用品ﾃｰﾌﾞﾙの生成
    public function fncSearchSpreadCata()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );

        try {
            $this->HMTVE160CatalogOrderBase = new HMTVE160CatalogOrderBase();
            $result = $this->HMTVE160CatalogOrderBase->getCatalog();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $tmpJqgridShow = $this->ClsComFncHMTVE->FncCreateJqGridShow($result['data']);
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];
            $result = $this->ClsComFncHMTVE->FncCreateJqGridDataIndex($result['data'], $totalPage, $page, $tmpCount);
        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }

        // Viewファイル呼出し
        $this->fncReturn($result);
    }

    //登録ボタンのイベント
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
                $postData = $_POST['data'];

                $this->HMTVE160CatalogOrderBase = new HMTVE160CatalogOrderBase();
                //トランザクション開始
                $this->HMTVE160CatalogOrderBase->Do_transaction();
                $blnTran = TRUE;

                $nowDate = $this->ClsComFncHMTVE->FncGetSysDate('Ymd');
                $result = $this->HMTVE160CatalogOrderBase->getHonCatalogDel($nowDate);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                for ($i = 0; $i < count($postData['grdHonCatalog']); $i++) {
                    if ($postData['grdHonCatalog'][$i]['CATALOG_CD'] != '') {
                        $result = $this->HMTVE160CatalogOrderBase->getHonCatalogLogin($nowDate, $postData['grdHonCatalog'][$i]);
                        if (!$result['result']) {
                            throw new \Exception($result['data']);
                        }
                    }
                }
                for ($i = 0; $i < count($postData['grdYouCatalog']); $i++) {
                    if ($postData['grdYouCatalog'][$i]['CATALOG_CD'] != '') {
                        $result = $this->HMTVE160CatalogOrderBase->getYouCatalogLogin($nowDate, $postData['grdYouCatalog'][$i]);
                        if (!$result['result']) {
                            throw new \Exception($result['data']);
                        }
                    }
                }
                for ($i = 0; $i < count($postData['grdCatalog']); $i++) {
                    if ($postData['grdCatalog'][$i]['CATALOG_CD'] != '') {
                        $result = $this->HMTVE160CatalogOrderBase->getCatalogLogin($nowDate, $postData['grdCatalog'][$i]);
                        if (!$result['result']) {
                            throw new \Exception($result['data']);
                        }
                    }
                }
                $result = $this->HMTVE160CatalogOrderBase->getMailInsDel();
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                if (isset($postData['grdMail'])) {
                    for ($i = 0; $i < count($postData['grdMail']); $i++) {
                        $postData['grdMail'][$i]['SEQ_NO'] = $i + 1;
                        $result_ins = $this->HMTVE160CatalogOrderBase->getMailIns($postData['grdMail'][$i]);
                        if (!$result_ins['result']) {
                            throw new \Exception($result_ins['data']);
                        }
                    }
                }

                $this->HMTVE160CatalogOrderBase->Do_commit();

            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
            if ($blnTran) {
                //エラーが出る場合、ロールバックする
                $this->HMTVE160CatalogOrderBase->Do_rollback();
            }
        }
        $result['data'] = '';
        $this->fncReturn($result);
    }

    //本ｶﾀﾛｸﾞテープルの作成
    //用品ｶﾀﾛｸﾞテープルの作成
    public function creatGridView($objReader)
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );

        try {
            $newData = array();
            for ($CNT = 0; $CNT < count($objReader); $CNT++) {
                $dr = array();
                $dr['HAKKO_YM'] = substr($objReader[$CNT]['HAKKO_YM'], 0, 4) . "/" . substr($objReader[$CNT]['HAKKO_YM'], 4, 2);
                $dr['CATALOG_CD'] = $objReader[$CNT]['CATALOG_CD'];
                $dr['CATALOG_NM'] = $objReader[$CNT]['CATALOG_NM'];
                $dr['TANKA'] = $objReader[$CNT]['TANKA'];
                array_push($newData, $dr);
            }
            $result['data'] = $newData;
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        return $result;
    }

}

