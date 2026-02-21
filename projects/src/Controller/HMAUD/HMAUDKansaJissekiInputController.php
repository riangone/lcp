<?php
namespace App\Controller\HMAUD;

use App\Controller\AppController;
use App\Model\HMAUD\HMAUDKansaJissekiInput;

//*******************************************
// * sample controller
//*******************************************
class HMAUDKansaJissekiInputController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
// public $autoRender = false;
    public $Session;
    public $HMAUDKansaJissekiInput;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFncHMAUD');
    }
    public function index()
    {
        // 画面表示内容の設定
        $this->render('index', 'HMAUDKansaJissekiInput_layout');

    }

    //拠点マスタのデータを取得
    public function fncGetKyoten()
    {
        $this->HMAUDKansaJissekiInput = new HMAUDKansaJissekiInput();
        $res = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            $kyoten = $this->HMAUDKansaJissekiInput->getKyotenSql();
            if (!$kyoten['result']) {
                throw new \Exception($kyoten['data']);
            }
            //検索条件・クールには 現在のクール数を初期表示
            $cour = $this->HMAUDKansaJissekiInput->getInitializeCour();
            if (!$cour['result']) {
                throw new \Exception($cour['data']);
            }
            $res['data']['kyoten'] = $kyoten['data'];
            $res['data']['cour'] = $cour['data'];
            $res['result'] = true;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //検索ボタンクリック
    public function btnSearchClick()
    {
        $this->HMAUDKansaJissekiInput = new HMAUDKansaJissekiInput();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['request'])) {
                $this->Session = $this->request->getSession();
                //監査補助人
                $subRes = $this->HMAUDKansaJissekiInput->getAuditSubSql($this->Session->read('login_user'));
                if (!$subRes['result']) {
                    throw new \Exception($subRes['data']);
                }
                //HMAUD_AUDIT_MEMBER　を検索し
                $userRes = $this->HMAUDKansaJissekiInput->getUserRoleSql($this->Session->read('login_user'));
                if (!$userRes['result']) {
                    throw new \Exception($userRes['data']);
                }
                //20230314 LIU UPD S
                //HMAUD_AUDIT_VIEWER　を検索し
                $viewRes = $this->HMAUDKansaJissekiInput->getUser($this->Session->read('login_user'));
                if (!$viewRes['result']) {
                    throw new \Exception($viewRes['data']);
                }
                //ログインIDが HMAUD_MST_ADMINまたはHMAUD_AUDIT_MEMBERに存在しない
                //if ($userRes['row'] == 0 && $subRes['row'] == 0)
                if ($userRes['row'] == 0 && $subRes['row'] == 0 && $viewRes['row'] == 0) {
                    //該当するユーザーは登録されていません！
                    throw new \Exception("nouser");
                }
                //20230314 LIU UPD E

                //監査マスタ.監査ID
                $checkIdRes = $this->HMAUDKansaJissekiInput->getCheckIdSql($_POST['request']);
                if (!$checkIdRes['result']) {
                    throw new \Exception($checkIdRes['data']);
                }
                if ($checkIdRes['row'] == 0) {
                    //該当するデータは登録されていません！
                    throw new \Exception("nodata");
                }
                $checkId = $checkIdRes['data'][0]['CHECK_ID'];
                $check_dt = $checkIdRes['data'][0]['CHECK_DT'];
                //監査担当者
                $memberRes = $this->HMAUDKansaJissekiInput->getAuditMemberSql($this->Session->read('login_user'), $checkId);
                if (!$memberRes['result']) {
                    throw new \Exception($memberRes['data']);
                }
                // 該当「監査ID」の監査人がHMAUD_MST_ADMINとHMAUD_AUDIT_MEMBERに存在しない
                // if ($subRes['row'] == 0 && $memberRes['row'] == 0 )
                // {
                // 	//該当するユーザーは登録されていません！
                // 	throw new \Exception("nouser");
                // }
                //報告書ヘッダ.ステータス
                $statusRes = $this->HMAUDKansaJissekiInput->getStatusSql($checkId);
                if (!$statusRes['result']) {
                    throw new \Exception($statusRes['data']);
                }

                $datas = array(
                    'CHECK_ID' => $checkId,
                    'CHECK_DT' => $check_dt,
                    'ROLE' => $memberRes['row'] > 0 ? $memberRes['data'][0]['ROLE'] : '',
                    'STATUS' => $statusRes['row'] > 0 ? $statusRes['data'][0]['STATUS'] : '',
                    'SUBAUDIT' => $subRes['row'] > 0 ? TRUE : FALSE,
                    //20230315 LIU INS S
                    //\99.提供資料\FromJP\20230310：ログインユーザーが ビューアマスタ上に存在している場合、監査スケジュールに登録されていなくてもデータ検索可能
                    'VIEW' => $viewRes['row'] > 0 && $memberRes['row'] == 0 && $subRes['row'] == 0 ? TRUE : FALSE,
                    //20230315 LIU INS E
                );
                //監査項目マスタテーブルにデータ
                $objdr = $this->HMAUDKansaJissekiInput->getDataSql($_POST['request'], $checkId);
                if (!$objdr['result']) {
                    throw new \Exception($objdr['data']);
                }
                $tmpJqgridShow = $this->ClsComFncHMAUD->FncCreateJqGridShow($objdr['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $res = $this->ClsComFncHMAUD->FncCreateJqGridDataIndex($objdr['data'], $totalPage, $page, $tmpCount);
                $res->audit = $datas;
            }
        } catch (\Exception $e) {
            //前端jqgrid共通，显示error信息的title有问题，所以这里返回值为true
            $res['result'] = TRUE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //保存ボタンクリック
    public function btnSaveClick()
    {
        $tranStartFlg = FALSE;
        $this->HMAUDKansaJissekiInput = new HMAUDKansaJissekiInput();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (!isset($_POST['data'])) {
                throw new \Exception('param error');
            }
            //トランザクション開始
            //更新処理を行う
            $this->HMAUDKansaJissekiInput->Do_transaction();
            $tranStartFlg = TRUE;
            //監査実績データを追加|更新する
            $savedataRes = $this->fncSaveData($_POST['data']);
            if (!$savedataRes['result']) {
                throw new \Exception($savedataRes['error']);
            }
            //実績入力画面で保存したときに、監査マスタ.実施日を更新
            $updRes = $this->HMAUDKansaJissekiInput->updAuditMainSql($_POST['data']['CHECK_ID'], $_POST['data']['sysDate']);
            if (!$updRes['result']) {
                throw new \Exception($updRes['data']);
            }
            //実績入力時、すべて○で登録した場合も 報告書ヘッダを作成し、ステータスを 01に更新
            if ($_POST['data']['changeSTATUS'] == 1) {
                //報告書ヘッダ.ステータスを 「01.監査実績入力済」に更新
                $statusRes = $this->HMAUDKansaJissekiInput->setStatusSql($_POST['data']['CHECK_ID']);
                if (!$statusRes['result']) {
                    throw new \Exception($statusRes['data']);
                }
            }
            //エラーがない場合、コミットする
            $this->HMAUDKansaJissekiInput->Do_commit();
            $tranStartFlg = FALSE;
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $this->HMAUDKansaJissekiInput->Do_rollback();
            }
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //確定ボタンクリック
    public function btnConfirmClick()
    {
        $tranStartFlg = FALSE;
        $this->HMAUDKansaJissekiInput = new HMAUDKansaJissekiInput();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (!isset($_POST['data'])) {
                throw new \Exception('param error');
            }
            //トランザクション開始
            //更新処理を行う
            $this->HMAUDKansaJissekiInput->Do_transaction();
            $tranStartFlg = TRUE;
            //指摘事項NO54:確定ボタンをクリックされたら、保存イベントの処理も実行する
            if (isset($_POST['data']['tableData'])) {
                //監査実績データを追加|更新する
                $savedataRes = $this->fncSaveData($_POST['data']);
                if (!$savedataRes['result']) {
                    throw new \Exception($savedataRes['error']);
                }
            }
            //報告書ヘッダ.ステータスを 「01.監査実績入力済」に更新
            $statusRes = $this->HMAUDKansaJissekiInput->setStatusSql($_POST['data']['CHECK_ID']);
            if (!$statusRes['result']) {
                throw new \Exception($statusRes['data']);
            }
            //実績入力画面で確定したときに、監査マスタ.実施日を更新
            $updRes = $this->HMAUDKansaJissekiInput->updAuditMainSql($_POST['data']['CHECK_ID'], $_POST['data']['sysDate']);
            if (!$updRes['result']) {
                throw new \Exception($updRes['data']);
            }
            //エラーがない場合、コミットする
            $this->HMAUDKansaJissekiInput->Do_commit();
            $tranStartFlg = FALSE;
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $this->HMAUDKansaJissekiInput->Do_rollback();
            }
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //監査実績データを追加|更新する
    public function fncSaveData($datas)
    {
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            //監査実績ID
            $noResult = $this->HMAUDKansaJissekiInput->getAuditResultNo();
            if (!$noResult['result']) {
                throw new \Exception($noResult['data']);
            }
            $no = $noResult['data'][0]['NO'];
            $tableData = $datas['tableData'];
            for ($i = 0; $i < count($tableData); $i++) {
                $param = array(
                    'CHECK_DT' => $datas['sysDate'],
                    'CHECK_ID' => $datas['CHECK_ID'],
                    'ROW_NO' => $tableData[$i]['ROW_NO'],
                    'RESULT' => $tableData[$i]['RESULT'],
                    //insert single quotes into database
                    'REMARKS' => str_replace("'", "''", $tableData[$i]['REMARKS']),
                    'CHECK_RESULT_ID' => $tableData[$i]['CHECK_RESULT_ID'],
                );
                //監査実績既存のデータ
                //20230421 caina upd s
                $selRes = $this->HMAUDKansaJissekiInput->getDateSql($param);
                if (!$selRes['result']) {
                    throw new \Exception($selRes['data']);
                }
                if ($selRes['row'] === 0 || ($selRes['row'] > 0 && $selRes['data'][0]['UPD_DATE'] == $tableData[$i]['UPD_DATE'])) {
                    if ($selRes['row'] > 0 && $selRes['data'][0]['RESULT'] == $tableData[$i]['RESULT'] && $selRes['data'][0]['REMARKS'] == $tableData[$i]['REMARKS']) {
                        continue;
                    }
                    if ($tableData[$i]['CHECK_RESULT_ID'] != null) {
                        //監査実績既存のデータ
                        $existData = $this->HMAUDKansaJissekiInput->getAuditResultExistData($tableData[$i]['CHECK_RESULT_ID']);
                        if (!$existData['result']) {
                            throw new \Exception($existData['data']);
                        }
                        if ($existData['row'] > 0) {
                            //監査実績既存のデータ更新
                            $updRes = $this->HMAUDKansaJissekiInput->updAuditResultSql($param);
                            if (!$updRes['result']) {
                                throw new \Exception($updRes['data']);
                            }
                        } else {
                            $no++;
                            $param['CHECK_RESULT_ID'] = $no;
                            //監査実績データを追加する
                            $insRes = $this->HMAUDKansaJissekiInput->insAuditResultSql($param);
                            if (!$insRes['result']) {
                                throw new \Exception($insRes['data']);
                            }
                        }
                    } else {
                        $no++;
                        $param['CHECK_RESULT_ID'] = $no;
                        //監査実績データを追加する
                        $insRes = $this->HMAUDKansaJissekiInput->insAuditResultSql($param);
                        if (!$insRes['result']) {
                            throw new \Exception($insRes['data']);
                        }
                    }
                } else {
                    throw new \Exception('他ユーザーによってデータが更新されています。再読込してください');
                }
                //20230421 caina upd e
            }
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        return $res;
    }

}