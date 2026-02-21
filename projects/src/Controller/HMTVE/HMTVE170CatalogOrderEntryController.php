<?php
namespace App\Controller\HMTVE;
use App\Controller\AppController;
use App\Model\HMTVE\HMTVE170CatalogOrderEntry;
//*******************************************
// * sample jchl
//*******************************************
class HMTVE170CatalogOrderEntryController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    public $HMTVE170CatalogOrderEntry;
    private $Session;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
    }
    public function index()
    {
        $this->render('index', 'HMTVE170CatalogOrderEntry_layout');
    }
    public function loadRowDataOne()
    {
        $result = array(
            'result' => TRUE,
            'data' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['request'])) {
                $openTime = $this->ClsComFncHMTVE->FncGetSysDate('Y/m/d H:i:s');
                $postdata = $_POST['request'];
                $this->HMTVE170CatalogOrderEntry = new HMTVE170CatalogOrderEntry();
                $resultLoad = $this->loadData($openTime, $postdata['BUSYOCD'], 1);
                if (!$resultLoad['result']) {
                    throw new \Exception($resultLoad['data']);
                }
                $resultHBUSYO = $this->HMTVE170CatalogOrderEntry->FoucsMoveSql();
                if (!$resultHBUSYO['result']) {
                    throw new \Exception($resultHBUSYO['data']);
                }
                $tmpJqgridShow = $this->ClsComFncHMTVE->FncCreateJqGridShow($resultLoad['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $result = $this->ClsComFncHMTVE->FncCreateJqGridDataIndex($resultLoad["data"], $totalPage, $page, $tmpCount);
                $result->openTime = $openTime;
                $result->HBUSYO = $resultHBUSYO['data'];
                $this->Session = $this->request->getSession();
                $result->BusyoCD = $this->Session->read('BusyoCD');
            } else {
                throw new \Exception('値が受信されません');
            }
        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function loadRowDataTwo()
    {
        $result = array(
            'result' => TRUE,
            'data' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['request'])) {
                $postdata = $_POST['request'];
                $this->HMTVE170CatalogOrderEntry = new HMTVE170CatalogOrderEntry();
                $resultLoad = $this->loadData($postdata['openTime'], $postdata['BUSYOCD'], 2);
                if (!$resultLoad['result']) {
                    throw new \Exception($resultLoad['data']);
                }
                $tmpJqgridShow = $this->ClsComFncHMTVE->FncCreateJqGridShow($resultLoad['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $result = $this->ClsComFncHMTVE->FncCreateJqGridDataIndex($resultLoad["data"], $totalPage, $page, $tmpCount);
            } else {
                throw new \Exception('値が受信されません');
            }
        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function loadRowDataThree()
    {
        $result = array(
            'result' => TRUE,
            'data' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['request'])) {
                $postdata = $_POST['request'];
                $this->HMTVE170CatalogOrderEntry = new HMTVE170CatalogOrderEntry();
                $resultLoad = $this->loadData($postdata['openTime'], $postdata['BUSYOCD'], 3);
                if (!$resultLoad['result']) {
                    throw new \Exception($resultLoad['data']);
                }
                $tmpJqgridShow = $this->ClsComFncHMTVE->FncCreateJqGridShow($resultLoad['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $result = $this->ClsComFncHMTVE->FncCreateJqGridDataIndex($resultLoad["data"], $totalPage, $page, $tmpCount);
            } else {
                throw new \Exception('値が受信されません');
            }
        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function loadData($openTime, $postdata, $id)
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            $result = $this->HMTVE170CatalogOrderEntry->loadRowDataOneSql($openTime, $postdata, $id);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    public function btnETOrderClick()
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
                $this->HMTVE170CatalogOrderEntry = new HMTVE170CatalogOrderEntry();
                //トランザクション開始
                $this->HMTVE170CatalogOrderEntry->Do_transaction();
                $blnTran = TRUE;
                $resultFLG = $this->HMTVE170CatalogOrderEntry->DeleteSQL_HDTCATALOGDATA($postdata['openTime'], $postdata['BUSYOCD']);
                if (!$resultFLG['result']) {
                    throw new \Exception($resultFLG['data']);
                }
                $resultDel = $this->HMTVE170CatalogOrderEntry->DeleteSQL_CatalogHaisouKibou($postdata['openTime'], $postdata['BUSYOCD']);
                if (!$resultDel['result']) {
                    throw new \Exception($resultDel['data']);
                }

                for ($i = 0; $i < count($postdata['rowDatas1']); $i++) {
                    if ($postdata['rowDatas1'][$i]['ORDER_NUM'] != "") {
                        $resultIns = $this->HMTVE170CatalogOrderEntry->insertSQL($postdata['openTime'], $postdata['BUSYOCD'], $postdata['rowDatas1'][$i], $postdata['rowDatas1'][$i]['ORDER_NUM']);
                        if (!$resultIns['result']) {
                            throw new \Exception($resultIns['data']);
                        }
                    }
                }
                for ($i = 0; $i < count($postdata['rowDatas2']); $i++) {
                    if ($postdata['rowDatas2'][$i]['ORDER_NUM2'] != "") {
                        $resultIns = $this->HMTVE170CatalogOrderEntry->insertSQL($postdata['openTime'], $postdata['BUSYOCD'], $postdata['rowDatas2'][$i], $postdata['rowDatas2'][$i]['ORDER_NUM2']);
                        if (!$resultIns['result']) {
                            throw new \Exception($resultIns['data']);
                        }
                    }
                }
                for ($i = 0; $i < count($postdata['rowDatas3']); $i++) {
                    if ($postdata['rowDatas3'][$i]['ORDER_NUM3'] != "") {
                        $resultIns = $this->HMTVE170CatalogOrderEntry->insertSQL($postdata['openTime'], $postdata['BUSYOCD'], $postdata['rowDatas3'][$i], $postdata['rowDatas3'][$i]['ORDER_NUM3']);
                        if (!$resultIns['result']) {
                            throw new \Exception($resultIns['data']);
                        }
                    }
                }
                if ($postdata['checked'] != 'false') {
                    $resultIns = $this->HMTVE170CatalogOrderEntry->insertSQLCatalogHaisouKibou($postdata['openTime'], $postdata['BUSYOCD']);
                    if (!$resultIns['result']) {
                        throw new \Exception($resultIns['data']);
                    }
                }
                // コミット
                $this->HMTVE170CatalogOrderEntry->Do_commit();
                $result['result'] = TRUE;
            }
        } catch (\Exception $e) {
            //ロールバック
            if ($blnTran) {
                $this->HMTVE170CatalogOrderEntry->Do_rollback();
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

}