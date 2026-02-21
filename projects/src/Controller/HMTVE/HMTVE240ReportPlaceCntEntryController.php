<?php
namespace App\Controller\HMTVE;
use App\Controller\AppController;
use App\Model\HMTVE\HMTVE240ReportPlaceCntEntry;

//*******************************************
// * sample controller
//*******************************************
class HMTVE240ReportPlaceCntEntryController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    public $HMTVE240ReportPlaceCntEntry;
    private $Session;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
    }

    public function index()
    {
        $this->render('index', 'HMTVE240ReportPlaceCntEntry_layout');
    }
    public function pageLoad()
    {
        $result = array(
            'result' => true,
            'data' => array(),
            'error' => ''
        );
        try {
            $result['data']['date'] = $this->ClsComFncHMTVE->FncGetSysDate('Ymd');
            $this->Session = $this->request->getSession();
            $result['data']['BusyoCD'] = $this->Session->read('BusyoCD');
            if (!isset($result['data']['BusyoCD']) || $result['data']['BusyoCD'] == "") {
                $result['key'] = 'W9999';
                throw new \Exception("表示できる部署が存在しません。管理者にお問い合わせください。");
            }
            $result['data']['SyainNM'] = $this->Session->read('SyainNM');

            $this->HMTVE240ReportPlaceCntEntry = new HMTVE240ReportPlaceCntEntry();
            $resultName = $this->HMTVE240ReportPlaceCntEntry->ExpressShopName();
            if (!$resultName['result']) {
                throw new \Exception($resultName['data']);
            }
            if ($resultName['row'] > 0) {
                $result['data']['BUSYO_RYKNM'] = $resultName['data'][0]['BUSYO_RYKNM'];
            }
            $result['key'] = '';
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);

    }

    public function getReporter()
    {
        $result = array(
            'result' => TRUE,
            'data' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['request'])) {
                $postdata = $_POST['request'];
                $this->HMTVE240ReportPlaceCntEntry = new HMTVE240ReportPlaceCntEntry();
                $result = $this->HMTVE240ReportPlaceCntEntry->getReporterSQL($postdata['nenfetu']);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                if ($result["row"] > 0) {
                    $allnum = true;
                } else {
                    $allnum = false;
                }
                $result['data']['0']['Classification'] = '1.新車新規';
                $result['data']['1']['Classification'] = '2.中古新規';
                $result['data']['2']['Classification'] = '3.転　　　入';
                $result['data']['3']['Classification'] = '4.記　　　入';
                $tmpJqgridShow = $this->ClsComFncHMTVE->FncCreateJqGridShow($result['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $result = $this->ClsComFncHMTVE->FncCreateJqGridDataIndex($result["data"], $totalPage, $page, $tmpCount);
                $resultFLG = $this->HMTVE240ReportPlaceCntEntry->getManageLocaleSQL($postdata['nenfetu']);
                $result->allnum = $allnum;
                if (!$resultFLG['result']) {
                    throw new \Exception($resultFLG['data']);
                }
                if ($resultFLG['row'] > 0) {
                    $result->kakuteiFLG = $resultFLG['data'][0]['KAKUTEI_FLG'];
                }
            } else {
                throw new \Exception('値が受信されません');
            }
        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

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
                $postdata = $_POST['data'];
                $this->HMTVE240ReportPlaceCntEntry = new HMTVE240ReportPlaceCntEntry();
                //トランザクション開始
                $this->HMTVE240ReportPlaceCntEntry->Do_transaction();
                $blnTran = TRUE;
                $this->Session = $this->request->getSession();
                $busyocd = $this->Session->read('BusyoCD');
                if (!isset($busyocd)) {
                    $result['key'] = 'W9999';
                    throw new \Exception('表示できる部署が存在しません。管理者にお問い合わせください。');
                }
                $resultFLG = $this->HMTVE240ReportPlaceCntEntry->getManageLocaleSQL($postdata['nenfetu']);
                if (!$resultFLG['result']) {
                    throw new \Exception($resultFLG['data']);
                }
                if ($resultFLG['row'] > 0) {
                    if ($resultFLG['data'][0]['KAKUTEI_FLG'] == 1) {
                        $result['key'] = 'W9999';
                        throw new \Exception('既に出力が行われていますので、登録は出来ません');
                    }
                }
                $resultdel = $this->HMTVE240ReportPlaceCntEntry->DeleteCarMLSQl($postdata['nenfetu']);

                if (!$resultdel['result']) {
                    throw new \Exception($resultdel['data']);
                }
                $tableDate = $postdata['tableDate'];
                for ($i = 0; $i < count($tableDate); $i++) {
                    $result = $this->HMTVE240ReportPlaceCntEntry->InsertCarMLSQl($postdata['nenfetu'], $i + 1, $tableDate[$i]['SINSEI_CNT'], $tableDate[$i]['TODOKE_CNT'], $tableDate[$i]['KAKUNIN_CNT']);
                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }
                }
                $result['key'] = '';
                // コミット
                $this->HMTVE240ReportPlaceCntEntry->Do_commit();
            }
        } catch (\Exception $e) {
            //ロールバック
            if ($blnTran) {
                $this->HMTVE240ReportPlaceCntEntry->Do_rollback();
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $result['data'] = '';
        $this->fncReturn($result);
    }

    public function btnDeleteClick()
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
                $this->HMTVE240ReportPlaceCntEntry = new HMTVE240ReportPlaceCntEntry();
                //トランザクション開始
                $this->HMTVE240ReportPlaceCntEntry->Do_transaction();
                $blnTran = TRUE;
                $this->Session = $this->request->getSession();
                $busyocd = $this->Session->read('BusyoCD');
                if (!isset($busyocd)) {
                    $result['key'] = 'W9999';
                    throw new \Exception('表示できる部署が存在しません。管理者にお問い合わせください。');
                }
                $resultFLG = $this->HMTVE240ReportPlaceCntEntry->getManageLocaleSQL($postdata['nenfetu']);
                if (!$resultFLG['result']) {
                    throw new \Exception($resultFLG['data']);
                }
                if ($resultFLG['row'] > 0) {
                    if ($resultFLG['data'][0]['KAKUTEI_FLG'] == 1) {
                        $result['key'] = 'W9999';
                        throw new \Exception('既に出力が行われていますので、削除は出来ません');
                    }
                }
                $result = $this->HMTVE240ReportPlaceCntEntry->DeleteCarMLSQl($postdata['nenfetu']);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                if ($result['number_of_rows'] == 0) {
                    $result['key'] = 'W0024';
                    throw new \Exception('該当データはありません。');
                }
                $result['key'] = '';
                // コミット
                $this->HMTVE240ReportPlaceCntEntry->Do_commit();
            }
        } catch (\Exception $e) {
            //ロールバック
            if ($blnTran) {
                $this->HMTVE240ReportPlaceCntEntry->Do_rollback();
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $result['data'] = '';
        $this->fncReturn($result);

    }

}
