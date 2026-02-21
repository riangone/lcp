<?php
namespace App\Controller\HDKAIKEI;
use App\Controller\AppController;
use App\Model\HDKAIKEI\HDKKamokuMst;
//*******************************************
// * sample controller
//*******************************************
class HDKKamokuMstController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
    public $HDKKamokuMst = null;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHDKAIKEI');
    }
    public function index()
    {
        // $this->Session->delete("HDKOut4OBC_XLSX_TYPE_RECHK");
        // 画面表示内容の設定
        $this->render('index', 'HDKKamokuMst_layout');
    }

    //フォーム初期化
    public function fncFormload()
    {
        $result = array(
            'result' => FALSE,
            'data' => array(
                'GetKamokuMstValue' => "",
            ),
            'error' => ''
        );
        try {
            // 科目番号
            $GetKamokuMstValue = $this->ClsComFncHDKAIKEI->FncGetKamokuMstValue();

            if (!$GetKamokuMstValue['result']) {
                throw new \Exception($GetKamokuMstValue['error']);
            }
            $result['data']['GetKamokuMstValue'] = $GetKamokuMstValue['data'];

            $result['result'] = true;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //検索
    public function kensakuClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            //データの取得
            if (isset($_POST['request'])) {
                $postData = $_POST['request'];

                $this->HDKKamokuMst = new HDKKamokuMst();

                $result = $this->HDKKamokuMst->getRelation($postData);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                $tmpJqgridShow = $this->ClsComFncHDKAIKEI->FncCreateJqGridShow($result['data']);
                $start = $tmpJqgridShow['start'];
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $tmpJqgrid = $this->ClsComFncHDKAIKEI->FncCreateJqGridDataReload($result['data'], $totalPage, $page, $tmpCount, $start);
                $result = $tmpJqgrid;
            }

        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);

    }

    //科目データの取得:関係名一覧の選択ボタン押下時の処理
    public function fncSelKamokuData()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['request'])) {
                $postData = $_POST['request'];

                $this->HDKKamokuMst = new HDKKamokuMst();
                $result = $this->HDKKamokuMst->fncGetKamokuList($postData);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                $tmpJqgridShow = $this->ClsComFncHDKAIKEI->FncCreateJqGridShow($result['data']);
                $start = $tmpJqgridShow['start'];
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $tmpJqgrid = $this->ClsComFncHDKAIKEI->FncCreateJqGridDataReload($result['data'], $totalPage, $page, $tmpCount, $start);
                $result = $tmpJqgrid;
            }
        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    // 保存
    public function btnSaveClick()
    {
        $blnTran = FALSE;
        $this->HDKKamokuMst = new HDKKamokuMst();
        $result = array(
            'result' => TRUE,
            'data' => '',
            'msg' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['data'])) {
                $postData = $_POST['data'];
                $resCheck = $this->inputCheck($postData);
                if (!$resCheck['result']) {
                    $result['data'] = $resCheck['data'];
                    $result['html'] = $resCheck['html'];
                    throw new \Exception('W0034');
                }
                //トランザクション開始
                $this->HDKKamokuMst->Do_transaction();
                $blnTran = TRUE;
                if ($postData['relationCD'] == 'null') {
                    $resSelectMaxRelation = $this->HDKKamokuMst->SelectMaxRelation();
                    if (!$resSelectMaxRelation['result']) {
                        throw new \Exception($resSelectMaxRelation['data']);
                    }
                    $postData['relationCD'] = $resSelectMaxRelation['data'][0]['MAX_CD'];
                    $resInsertRelation = $this->HDKKamokuMst->InsertRelation($postData);
                    if (!$resInsertRelation['result']) {
                        throw new \Exception($resInsertRelation['data']);
                    }
                } else {
                    if (strlen($postData['checkStr']) > 0) {
                        $resCheckKamokuData = $this->HDKKamokuMst->CheckKamokuData($postData);
                        if (!$resCheckKamokuData['result']) {
                            throw new \Exception($resCheckKamokuData['data']);
                        }
                        if (count((array) $resCheckKamokuData['data']) > 0) {
                            throw new \Exception('W0025');
                        }
                    }
                    $resCheckRelationData = $this->HDKKamokuMst->CheckRelationData($postData);
                    if (!$resCheckRelationData['result']) {
                        throw new \Exception($resCheckRelationData['data']);
                    }
                    if (count((array) $resCheckRelationData['data']) == 0 || $resCheckRelationData['data'][0]['UPD_DATE'] !== $postData['update']) {
                        throw new \Exception('W0025');
                    }
                    $resUpdateRelation = $this->HDKKamokuMst->UpdateRelation($postData, 'update');
                    if (!$resUpdateRelation['result']) {
                        throw new \Exception($resUpdateRelation['data']);
                    }
                }

                $resDeleteParent = $this->HDKKamokuMst->UpdateKamoku($postData, 'delete');
                if (!$resDeleteParent['result']) {
                    throw new \Exception($resDeleteParent['data']);
                }
                if (strlen($postData['checkStr']) > 0) {
                    $resUpdateKamoku = $this->HDKKamokuMst->UpdateKamoku($postData, 'update');
                    if (!$resUpdateKamoku['result']) {
                        throw new \Exception($resUpdateKamoku['data']);
                    }
                }

                //コミット
                $this->HDKKamokuMst->Do_commit();
                $blnTran = FALSE;


                $result['data'] = true;
                $result['relationCD'] = $postData['relationCD'];
            }

        } catch (\Exception $e) {
            if ($blnTran) {
                $this->HDKKamokuMst->Do_rollback();
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function btnDeleteClick()
    {
        $blnTran = FALSE;
        $this->HDKKamokuMst = new HDKKamokuMst();
        $result = array(
            'result' => TRUE,
            'data' => '',
            'msg' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['data'])) {
                $postData = $_POST['data'];

                //トランザクション開始
                $this->HDKKamokuMst->Do_transaction();
                $blnTran = TRUE;
                if ($postData['relationCD'] == 'null') {
                    throw new \Exception('削除する関係名が見つかりません');
                } else {
                    $resCheckRelationData = $this->HDKKamokuMst->CheckRelationData($postData);
                    if (!$resCheckRelationData['result']) {
                        throw new \Exception($resCheckRelationData['data']);
                    }
                    if (count((array) $resCheckRelationData['data']) == 0 || $resCheckRelationData['data'][0]['UPD_DATE'] !== $postData['update']) {
                        throw new \Exception('W0025');
                    }
                    $resUpdateRelation = $this->HDKKamokuMst->UpdateRelation($postData, 'delete');
                    if (!$resUpdateRelation['result']) {
                        throw new \Exception($resUpdateRelation['data']);
                    }
                }

                $resDeleteParent = $this->HDKKamokuMst->UpdateKamoku($postData, 'delete');
                if (!$resDeleteParent['result']) {
                    throw new \Exception($resDeleteParent['data']);
                }

                //コミット
                $this->HDKKamokuMst->Do_commit();
                $blnTran = FALSE;


                $result['data'] = true;
            }

        } catch (\Exception $e) {
            if ($blnTran) {
                $this->HDKKamokuMst->Do_rollback();
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }
    public function inputCheck($postData)
    {
        $result = array(
            'result' => true,
            'html' => '',
            'data' => ''
        );
        try {
            if (isset($postData['relationName']) && !$this->ClsComFncHDKAIKEI->FncEncodeCheck($postData['relationName'])) {
                $result['html'] = 'txtRelationNameS';
                throw new \Exception('関係名');
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }



}
