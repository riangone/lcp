<?php
namespace App\Controller\HMTVE;
use App\Controller\AppController;
use App\Model\HMTVE\HMTVE030InputDataK;
//*******************************************
// * sample controller
//*******************************************
class HMTVE030InputDataKController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    private $Session;
    public $HMTVE030InputDataK;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
    }

    public function index()
    {
        $this->render('index', 'HMTVE030InputDataK_layout');
    }
    public function setExhibitTermDate()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            $this->HMTVE030InputDataK = new HMTVE030InputDataK();
            $result = $this->HMTVE030InputDataK->setExhibitTermDateSQL();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function getCarItem()
    {
        $result = array(
            'result' => TRUE,
            'data' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['request'])) {
                $postdata = $_POST['request'];
                $this->HMTVE030InputDataK = new HMTVE030InputDataK();
                $resultFLG = $this->HMTVE030InputDataK->btnViewClickSQL($postdata['ddlExhibitDay']);
                $result['key'] = '';
                if (!$resultFLG['result']) {
                    throw new \Exception($resultFLG['data']);
                }
                if ($resultFLG["row"] > 0) {
                    if ($resultFLG['data'][0]['IVENT_TARGET_FLG'] == 0) {
                        $result['key'] = 'W9999';
                        throw new \Exception("入力対象外です。");
                    } else {
                        $result['key'] = 'W9999';
                        throw new \Exception($postdata['ddlExhibitDay'] . "は休みの設定がされています。");
                    }
                }

                $resultSUM1 = $this->HMTVE030InputDataK->getKakuhouItemSQL($postdata['ddlExhibitDay'], 0);
                if (!$resultSUM1['result']) {
                    throw new \Exception($resultSUM1['data']);
                }
                $resultSUM2 = $this->HMTVE030InputDataK->getKakuhouItemSQL($postdata['ddlExhibitDay'], 1);
                if (!$resultSUM2['result']) {
                    throw new \Exception($resultSUM2['data']);
                }
                $result = $this->HMTVE030InputDataK->getCarItemSQL($postdata['ddlExhibitDay']);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                $resultFLG = $this->HMTVE030InputDataK->checkExhibitTermDate($postdata['lblExhibitTermFrom']);
                if (!$resultFLG['result']) {
                    throw new \Exception($resultFLG['data']);
                }
                $tmpJqgridShow = $this->ClsComFncHMTVE->FncCreateJqGridShow($result['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $result = $this->ClsComFncHMTVE->FncCreateJqGridDataIndex($result["data"], $totalPage, $page, $tmpCount);
                $result->objReader1 = $resultSUM1['data'];
                $result->objReader2 = $resultSUM2['data'];
                if ($resultFLG["row"] > 0) {
                    if ($resultFLG['data'][0]['KAKUTEI_FLG'] == 1) {
                        $result->KAKUTEIFLG = true;
                    } else {

                        $result->KAKUTEIFLG = false;
                    }
                } else {
                    $result->KAKUTEIFLG = '';
                }
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

    public function btnDecideClick()
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
                $this->HMTVE030InputDataK = new HMTVE030InputDataK();
                $this->Session = $this->request->getSession();
                $busyocd = $this->Session->read("BusyoCD");
                if (!isset($busyocd)) {
                    $result['key'] = 'W9999';
                    throw new \Exception('表示できる部署が存在しません。管理者にお問い合わせください。');
                }
                $resultFLG = $this->HMTVE030InputDataK->checkExhibitTermDate($postdata['lblExhibitTermFrom']);
                if (!$resultFLG['result']) {
                    throw new \Exception($resultFLG['data']);
                }
                if (count((array) $resultFLG['data']) > 0) {
                    if ($resultFLG['data'][0]['KAKUTEI_FLG'] == 1) {
                        throw new \Exception("既に確報データの出力が行われていますので、変更は出来ません");
                    }
                }

                //トランザクション開始
                $this->HMTVE030InputDataK->Do_transaction();
                $blnTran = TRUE;
                $resultFLG = $this->HMTVE030InputDataK->IVENTDATESQL($postdata['ddlExhibitDay'], 0);
                if (!$resultFLG['result']) {
                    throw new \Exception($resultFLG['data']);
                }
                //抽出結果＞0件の場合
                if ($resultFLG["row"] == 0) {
                    $result = $this->HMTVE030InputDataK->textNumInsSQL($postdata['txtForecast'], $postdata['ddlExhibitDay'], $postdata['lblExhibitTermFrom'], 0);
                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }
                } else {
                    $result = $this->HMTVE030InputDataK->textNumUpdSQL($postdata['txtForecast'], $postdata['ddlExhibitDay'], 0);
                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }

                }
                $resultFLG = $this->HMTVE030InputDataK->IVENTDATESQL($postdata['ddlExhibitDay'], 1);
                if (!$resultFLG['result']) {
                    throw new \Exception($resultFLG['data']);
                }

                //抽出結果＝0件の場合
                if ($resultFLG["row"] == 0) {
                    $result = $this->HMTVE030InputDataK->textNumInsSQL($postdata['txtResults'], $postdata['ddlExhibitDay'], $postdata['lblExhibitTermFrom'], 1);
                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }
                } else {
                    $result = $this->HMTVE030InputDataK->textNumUpdSQL($postdata['txtResults'], $postdata['ddlExhibitDay'], 1);
                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }
                }
                $resultDel = $this->HMTVE030InputDataK->textNumDelSQL($postdata['ddlExhibitDay']);
                if (!$resultDel['result']) {
                    throw new \Exception($resultDel['data']);
                }
                for ($i = 0; $i < count($postdata['tableDate']); $i++) {
                    $result = $this->HMTVE030InputDataK->insertSql($postdata['tableDate'][$i], $postdata['ddlExhibitDay'], $postdata['lblExhibitTermFrom']);
                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }
                }
                $result['key'] = '';
                // コミット
                $this->HMTVE030InputDataK->Do_commit();
            }
        } catch (\Exception $e) {
            //ロールバック
            if ($blnTran) {
                $this->HMTVE030InputDataK->Do_rollback();
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
                $this->HMTVE030InputDataK = new HMTVE030InputDataK();
                $resultFLG = $this->HMTVE030InputDataK->checkExhibitTermDate($postdata['lblExhibitTermFrom']);
                if (!$resultFLG['result']) {
                    throw new \Exception($resultFLG['data']);
                }
                if (count((array) $resultFLG['data']) > 0) {
                    if ($resultFLG['data'][0]['KAKUTEI_FLG'] == 1) {
                        $result['key'] = 'W9999';
                        throw new \Exception("既に確報データの出力が行われていますので、変更は出来ません");
                    }
                }

                //トランザクション開始
                $this->HMTVE030InputDataK->Do_transaction();
                $blnTran = TRUE;
                $result = $this->HMTVE030InputDataK->btnDeleteclickSql($postdata['ddlExhibitDay']);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                $result = $this->HMTVE030InputDataK->textNumDelSQL($postdata['ddlExhibitDay']);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                $result['key'] = '';
                // コミット
                $this->HMTVE030InputDataK->Do_commit();
            }
        } catch (\Exception $e) {
            //ロールバック
            if ($blnTran) {
                $this->HMTVE030InputDataK->Do_rollback();
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $result['data'] = '';
        $this->fncReturn($result);
    }

}
