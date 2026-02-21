<?php
namespace App\Controller\HMTVE;

use App\Controller\AppController;
use App\Model\HMTVE\HMTVE270TargetResultEntry;
//*******************************************
// * sample controller
//*******************************************
class HMTVE270TargetResultEntryController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    private $Session;
    private $HMTVE270TargetResultEntry;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
    }
    //　デフォルトで最初に実行される機
    public function index()
    {
        $this->render('index', 'HMTVE270TargetResultEntry_layout');
    }

    public function pageLoad()
    {
        $res = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            $this->Session = $this->request->getSession();
            $this->HMTVE270TargetResultEntry = new HMTVE270TargetResultEntry();
            if (isset($_POST['data'])) {
                $BusyoCD = $this->Session->read('BusyoCD');
                if (isset($BusyoCD) == FALSE) {
                    $res['data']['msg'] = 'W9999';
                    throw new \Exception('表示できる部署が存在しません。管理者にお問い合わせください。');
                }
                //店舗名を抽出する
                $busyoCd = $this->HMTVE270TargetResultEntry->GET_BUSYO_CD($BusyoCD);
                if (!$busyoCd['result']) {
                    throw new \Exception($busyoCd['data']);
                }

                $res['data']['BUSYO_RYKNM'] = $busyoCd['row'] > 0 && $busyoCd['data'][0]['BUSYO_RYKNM'] ? $busyoCd['data'][0]['BUSYO_RYKNM'] : "";
                //登録データを取得する
                $params = array(
                    'PatternID' => $this->Session->read('PatternID'),
                    'BUSYOCD' => $this->Session->read('BusyoCD'),
                    'CONST_ADMIN_PTN_NO' => $_POST['data']['CONST_ADMIN_PTN_NO'],
                    'CONST_HONBU_PTN_NO' => $_POST['data']['CONST_HONBU_PTN_NO'],
                    'CONST_TESTER_PTN_NO' => $_POST['data']['CONST_TESTER_PTN_NO'],
                    'TAISYOU_YM' => $_POST['data']['TAISYOU_YM']
                );
                $objdr2 = $this->HMTVE270TargetResultEntry->strSQL2($params);
                if (!$objdr2['result']) {
                    throw new \Exception($objdr2['data']);
                }
                $res['data']['objdr2'] = $objdr2['data'];
            }
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }

        $this->fncReturn($res);
    }

    public function btnLoginClick()
    {
        $tranStartFlg = FALSE;
        $this->HMTVE270TargetResultEntry = new HMTVE270TargetResultEntry();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            $this->Session = $this->request->getSession();
            if (!isset($_POST['data'])) {
                throw new \Exception("param error");
            }
            $params = array(
                'PatternID' => $this->Session->read('PatternID'),
                'BUSYOCD' => $this->Session->read('BusyoCD'),
                'CONST_ADMIN_PTN_NO' => $_POST['data']['CONST_ADMIN_PTN_NO'],
                'CONST_HONBU_PTN_NO' => $_POST['data']['CONST_HONBU_PTN_NO'],
                'CONST_TESTER_PTN_NO' => $_POST['data']['CONST_TESTER_PTN_NO'],
                'TAISYOU_YM' => $_POST['data']['TAISYOU_YM'],
                'hidCreateTime' => $_POST['data']['hidCreateTime'],
                'UPD_DATE' => $this->ClsComFncHMTVE->FncGetSysDate()
            );
            //存在チェックを行う
            $prgId = $this->HMTVE270TargetResultEntry->strcheckSQL($params);
            if (!$prgId['result']) {
                throw new \Exception($prgId['data']);
            }
            if ($_POST['data']['strMode'] == "INSERT") {
                //新規モードの場合
                //取得データ件数>0の場合、エラー　処理を中止する
                if ($prgId['row'] > 0) {
                    throw new \Exception("INSERT");
                }
            } else
                if ($_POST['data']['strMode'] == "UPDATE") {
                    //修正モード
                    //取得データ件数=0の場合、エラー　処理を中止する
                    if ($prgId['row'] == 0) {
                        throw new \Exception("UPDATE");
                    }
                }
            //更新処理を行う
            //トランザクション開始
            $this->HMTVE270TargetResultEntry->Do_transaction();
            $tranStartFlg = TRUE;
            //目標と実績データの更新処理を行う
            $del = $this->HMTVE270TargetResultEntry->DEL_SQL($params);
            if (!$del['result']) {
                throw new \Exception($del['data']);
            }
            //②．目標と実績データに追加する
            $datas = $_POST['data']['insData'];
            $ins = $this->HMTVE270TargetResultEntry->insertSQL($params, $datas);
            if (!$ins['result']) {
                throw new \Exception($ins['data']);
            }
            //コミット
            $this->HMTVE270TargetResultEntry->Do_commit();
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $this->HMTVE270TargetResultEntry->Do_rollback();
            }
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }

        $this->fncReturn($res);
    }

    //目標と実績データを削除する
    public function btnDelClick()
    {
        $this->HMTVE270TargetResultEntry = new HMTVE270TargetResultEntry();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            $this->Session = $this->request->getSession();
            if (!isset($_POST['data'])) {
                throw new \Exception("param error");
            }
            $params = array(
                'PatternID' => $this->Session->read('PatternID'),
                'BUSYOCD' => $this->Session->read('BusyoCD'),
                'CONST_ADMIN_PTN_NO' => $_POST['data']['CONST_ADMIN_PTN_NO'],
                'CONST_HONBU_PTN_NO' => $_POST['data']['CONST_HONBU_PTN_NO'],
                'CONST_TESTER_PTN_NO' => $_POST['data']['CONST_TESTER_PTN_NO'],
                'TAISYOU_YM' => $_POST['data']['TAISYOU_YM']
            );
            $res = $this->HMTVE270TargetResultEntry->DEL_SQL($params);
            if (!$res['result']) {
                throw new \Exception($res['data']);
            }
            $res['data'] = "";
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }

        $this->fncReturn($res);
    }
}
