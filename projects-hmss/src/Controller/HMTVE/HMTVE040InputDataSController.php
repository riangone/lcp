<?php
namespace App\Controller\HMTVE;
use App\Controller\AppController;
use App\Model\HMTVE\HMTVE040InputDataS;
//*******************************************
// * sample controller
//*******************************************
class HMTVE040InputDataSController extends AppController
{
    public $autoLayout = TRUE;
    public $HMTVE040InputDataS;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
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
        $this->render('index', 'HMTVE040InputDataS_layout');
    }

    public function setExhibitTermDate()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            $this->HMTVE040InputDataS = new HMTVE040InputDataS();
            $result = $this->HMTVE040InputDataS->setExhibitTermDateSql();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if ($result['row'] > 0) {
                $result['data']['END_DATE'] = date('Y/m/d', strtotime($result['data'][0]['END_DATE']));
                $result['data']['START_DATE'] = date('Y/m/d', strtotime($result['data'][0]['START_DATE']));
                if ($result['data'][0]['END_DATE'] == "") {
                    $result['data']['END_DATE'] = '';
                }
                if ($result['data'][0]['START_DATE'] == "") {
                    $result['data']['START_DATE'] = '';
                }
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function checkUserWork()
    {
        $result = array(
            'result' => TRUE,
            'data' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['request'])) {
                $postdata = $_POST['request'];
                $this->HMTVE040InputDataS = new HMTVE040InputDataS();
                $result = $this->HMTVE040InputDataS->checkUserWorkSql($postdata['ddlExhibitDay']);

                $result['key'] = '';
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                if ($result["row"] > 0) {
                    if ($result['data'][0]['IVENT_TARGET_FLG'] == "0") {
                        throw new \Exception("入力対象外です。");
                    } else {
                        throw new \Exception("は休みの設定がされています。");
                    }
                }
                $result = $this->HMTVE040InputDataS->getCarItem($postdata['ddlExhibitDay']);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                $tmpJqgridShow = $this->ClsComFncHMTVE->FncCreateJqGridShow($result['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $result = $this->ClsComFncHMTVE->FncCreateJqGridDataIndex($result["data"], $totalPage, $page, $tmpCount);
                $resultFLG = $this->HMTVE040InputDataS->checkExhibitTermDate($postdata['ddlExhibitDay']);
                if (!$resultFLG['result']) {
                    throw new \Exception($resultFLG['data']);
                }
                if ($resultFLG["row"] > 0) {
                    $kakuteiFLG = $resultFLG['data'];
                } else {
                    $kakuteiFLG = "";
                }
                $result->kakuteiFLG = $kakuteiFLG;
            } else {
                throw new \Exception('値が受信されません');
            }
        } catch (\Exception $e) {
            //前端jqgrid共通，显示error信息的title有问题，所以这里返回值为true
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function btnUpdateExecute()
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
                $this->HMTVE040InputDataS = new HMTVE040InputDataS();
                //トランザクション開始
                $this->HMTVE040InputDataS->Do_transaction();
                $Session = $this->request->getSession();
                $busyocd = $Session->read('BusyoCD');
                if (!isset($busyocd)) {
                    $result['key'] = 'noBusyo';
                    throw new \Exception('表示できる部署が存在しません。管理者にお問い合わせください。');
                }
                $blnTran = TRUE;
                $resultFLG = $this->HMTVE040InputDataS->checkExhibitTermDateDelete($postdata['ddlExhibitDay']);
                if (!$resultFLG['result']) {
                    throw new \Exception('データ読込に失敗しました。15504');
                }
                if ($resultFLG["row"] > 0) {
                    if ($resultFLG['data'][0]['KAKUTEI_FLG'] == 1) {
                        $result['key'] = 'E9999';
                        throw new \Exception("既に速報データの出力が行われていますので、変更は出来ません");
                    }
                }
                $resultDel = $this->HMTVE040InputDataS->updateDataSQL($postdata['ddlExhibitDay']);
                if (!$resultDel['result']) {
                    throw new \Exception('データ読込に失敗しました。UPDATE');
                }
                for ($i = 0; $i < count($postdata['tableDate']); $i++) {
                    $result = $this->HMTVE040InputDataS->insertSql($postdata, $postdata['tableDate'][$i]);
                    if (!$result['result']) {
                        throw new \Exception('データ読込に失敗しました。UPDATE');
                    }
                }
                $result['key'] = '';
                // コミット
                $this->HMTVE040InputDataS->Do_commit();
            }
        } catch (\Exception $e) {
            //ロールバック
            if ($blnTran) {
                $this->HMTVE040InputDataS->Do_rollback();
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
                $this->HMTVE040InputDataS = new HMTVE040InputDataS();
                //トランザクション開始
                $this->HMTVE040InputDataS->Do_transaction();
                $blnTran = TRUE;
                $resultFLG = $this->HMTVE040InputDataS->checkExhibitTermDateDelete($postdata['ddlExhibitDay']);
                if (!$resultFLG['result']) {
                    throw new \Exception('データ読込に失敗しました。');
                }
                if ($resultFLG["row"] > 0) {
                    if ($resultFLG['data'][0]['KAKUTEI_FLG'] == 1) {
                        $result['key'] = 'E9999';
                        throw new \Exception("既に速報データの出力が行われていますので、削除は出来ません");
                    }
                }
                $result = $this->HMTVE040InputDataS->updateDataSQL($postdata['ddlExhibitDay']);
                if (!$result['result']) {
                    throw new \Exception('データ読込に失敗しました。');
                }
                $result['key'] = '';
                // コミット
                $this->HMTVE040InputDataS->Do_commit();
            }
        } catch (\Exception $e) {
            //ロールバック
            if ($blnTran) {
                $this->HMTVE040InputDataS->Do_rollback();
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $result['data'] = '';
        $this->fncReturn($result);
    }
}
