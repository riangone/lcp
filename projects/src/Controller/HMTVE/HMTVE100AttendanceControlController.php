<?php
namespace App\Controller\HMTVE;
use App\Controller\AppController;
use App\Model\HMTVE\HMTVE100AttendanceControl;
//*******************************************
// * sample controller
//*******************************************
class HMTVE100AttendanceControlController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
    // public $autoRender = FALSE;
    public $Session;
    public $HMTVE100AttendanceControl;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
    }
    //　デフォルトで最初に実行される機能
    public function index()
    {
        $this->render('index', 'HMTVE100AttendanceControl_layout');
    }
    //店舗コード、店舗名を抽出する
    public function pageshopnamesave()
    {
        $this->HMTVE100AttendanceControl = new HMTVE100AttendanceControl();
        $res = array(
            'result' => FALSE,
            'data' => array(),
            'error' => ''
        );
        try {
            $this->Session = $this->request->getSession();
            $shopname = $this->HMTVE100AttendanceControl->Page_ShopNameSaveSql($this->Session->read('BusyoCD'));
            if (!$shopname['result']) {
                throw new \Exception($shopname['data']);

            }
            // print_r($shopname);
            if ($shopname['row'] > 0) {
                $res['data']['BUSYO_RYKNM'] = $shopname['data'][0]['BUSYO_RYKNM'];
                $res['data']['BUSYO_CD'] = $shopname['data'][0]['BUSYO_CD'];
            }
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //展示会開催期間に初期値をセットする
    public function pageclear()
    {
        $this->HMTVE100AttendanceControl = new HMTVE100AttendanceControl();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            //デフォルト日付を取得する
            $res = $this->HMTVE100AttendanceControl->Page_ClearSql();
            if (!$res['result']) {
                throw new \Exception($res['data']);
            }
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //取得データを出勤管理グリッドにバインドする
    public function btnPrintOutClick()
    {
        $this->HMTVE100AttendanceControl = new HMTVE100AttendanceControl();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['request'])) {
                $BUSYOCD = $_POST['request']['BUSYOCD'];
                $IVENTDT = $_POST['request']['IVENTDT'];
                $objdr2 = $this->HMTVE100AttendanceControl->btnPrintOut_ClickSql($BUSYOCD, $IVENTDT);
                if (!$objdr2['result']) {
                    throw new \Exception($objdr2['data']);
                }
                $tmpJqgridShow = $this->ClsComFncHMTVE->FncCreateJqGridShow($objdr2['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $res = $this->ClsComFncHMTVE->FncCreateJqGridDataIndex($objdr2['data'], $totalPage, $page, $tmpCount);
            }
        } catch (\Exception $e) {
            //前端jqgrid共通，显示error信息的title有问题，所以这里返回值为true
            $res['result'] = TRUE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //出勤管理データに追加する
    public function btnRegClick()
    {
        $tranStartFlg = FALSE;
        $this->HMTVE100AttendanceControl = new HMTVE100AttendanceControl();
        $res = array(
            'result' => FALSE,
            'data' => array(),
            'error' => ''
        );
        try {
            $iUpdFlg = "0";
            if (isset($_POST['data'])) {
                //存在チェック
                $gvTenpo = $_POST['data']['gvTenpo'];
                if ($gvTenpo && count($gvTenpo) > 0) {
                    for ($i = 0; $i < count($gvTenpo); $i++) {
                        if ($gvTenpo[$i]['chkbox'] == "1") {
                            //確報データが存在するかのチェック
                            $kaku = $this->HMTVE100AttendanceControl->CHECK_KAKU_SQL($_POST['data']['IDATE'], $gvTenpo[$i]['lblSyainCD']);
                            if (!$kaku['result']) {
                                throw new \Exception($kaku['data']);
                            }
                            if ($kaku['row'] > 0) {
                                $res['data']['msg'] = "確報データが存在しているので、更新できません。";
                                $res['data']['rowNum'] = $i;
                                throw new \Exception("W9999");
                            }
                            //速報データが存在するかのチェック
                            $soku = $this->HMTVE100AttendanceControl->CHECK_SOKU_SQL($_POST['data']['IDATE'], $gvTenpo[$i]['lblSyainCD']);
                            if (!$soku['result']) {
                                throw new \Exception($soku['data']);
                            }
                            if ($soku['row'] > 0) {
                                $res['data']['msg'] = "速報データが存在しているので、更新できません。";
                                $res['data']['rowNum'] = $i;
                                throw new \Exception("W9999");
                            }
                        }
                    }
                    //トランザクション開始
                    //更新処理を行う
                    $this->HMTVE100AttendanceControl->Do_transaction();
                    $tranStartFlg = TRUE;
                    //出勤管理データを削除する
                    $del = $this->HMTVE100AttendanceControl->DATA_DEL_SQL($_POST['data']['IDATE'], $_POST['data']['BUSYOCD']);
                    if (!$del['result']) {
                        throw new \Exception($del['data']);
                    }
                    $iUpdFlg .= $del['number_of_rows'];
                    //出勤管理データに追加する
                    //出勤管理GridViewの件数分繰り返す
                    for ($i = 0; $i < count($gvTenpo); $i++) {
                        if (($gvTenpo[$i]['IVENT_TARGET_FLG'] == "1" && $gvTenpo[$i]['chkbox'] == "1") || ($gvTenpo[$i]['IVENT_TARGET_FLG'] == "0" && $gvTenpo[$i]['chkbox'] == "0")) {
                            //チェックボックスをチェックした場合、出勤管理データに追加する
                            $params = array(
                                'START_DATE' => $_POST['data']['START_DATE'],
                                'BUSYO_CD' => $_POST['data']['BUSYOCD'],
                                'IVENT_DATE' => $_POST['data']['IDATE'],
                                'SYAIN_NO' => $gvTenpo[$i]['lblSyainCD'],
                                'chkYasumi' => $gvTenpo[$i]['chkbox'],
                                'lblCreateDate' => $gvTenpo[$i]['CREATE_DATE']
                            );
                            $ins = $this->HMTVE100AttendanceControl->DATA_INS_SQL($params);
                            if (!$ins['result']) {
                                throw new \Exception($ins['data']);
                            }
                            $iUpdFlg .= $ins['number_of_rows'];
                        }
                    }
                    if (strpos($iUpdFlg, "-1") == FALSE) {
                        //エラーがない場合、コミットする
                        $this->HMTVE100AttendanceControl->Do_commit();
                        $tranStartFlg = FALSE;
                    } else {
                        if ($tranStartFlg) {
                            $this->HMTVE100AttendanceControl->Do_rollback();
                        }
                    }

                }
            }
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $this->HMTVE100AttendanceControl->Do_rollback();
            }
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //出勤管理データを削除する
    public function btnDelClick()
    {
        $tranStartFlg = FALSE;
        $this->HMTVE100AttendanceControl = new HMTVE100AttendanceControl();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['data'])) {
                $iUpdFlg = "0";
                //登録可能な展示会開催期間であるかのチェックを行う
                //チェック用データを抽出する
                $sel = $this->HMTVE100AttendanceControl->KAKUTEI_FLG_SEL($_POST['data']['IVENTDT']);
                if (!$sel['result']) {
                    throw new \Exception($sel['data']);
                }
                if ($sel['row'] > 0) {
                    if ($sel['data'][0]['KAKUTEI_FLG'] == 1) {
                        throw new \Exception("既に速報データの出力が行われていますので、変更は出来ません。");
                    }
                }
                //トランザクションを開始する
                //更新処理を行う
                $this->HMTVE100AttendanceControl->Do_transaction();
                $tranStartFlg = TRUE;
                //出勤管理データを削除する
                $del = $this->HMTVE100AttendanceControl->DATA_DEL_SQL($_POST['data']['IVENTDT'], $_POST['data']['BUSYOCD']);
                if (!$del['result']) {
                    throw new \Exception($del['data']);
                }
                $iUpdFlg .= $del['number_of_rows'];
                if (strpos($iUpdFlg, "-1") == FALSE) {
                    //エラーがない場合、コミットする
                    $this->HMTVE100AttendanceControl->Do_commit();
                    $tranStartFlg = FALSE;
                } else {
                    if ($tranStartFlg) {
                        $this->HMTVE100AttendanceControl->Do_rollback();
                    }
                }
            }
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $this->HMTVE100AttendanceControl->Do_rollback();
            }
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

}
