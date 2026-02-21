<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmTentyoSyoreiCalc;

//*******************************************
// * sample controller
//*******************************************
class FrmTentyoSyoreiCalcController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
    // public $autoRender = FALSE;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsLogControl');
        $this->loadComponent('ClsComFncJKSYS');
    }

    private $prvArgNM = "店長奨励金計算処理";
    public $frmTentyoSyoreiCalc;

    //　デフォルトで最初に実行される機能
    public function index()
    {
        $this->render('index', 'FrmTentyoSyoreiCalc_layout');
    }

    //フォームロード
    public function frmTentyoSyoreiCalcLoad()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );

        try {
            $this->frmTentyoSyoreiCalc = new FrmTentyoSyoreiCalc();
            //人事コントロールマスタの処理年月取得
            $tblCTL = $this->frmTentyoSyoreiCalc->procGetJinjiCtrlMst_YM();
            if (!$tblCTL['result']) {
                throw new \Exception($tblCTL['data']);
            }
            //支給年月
            $SYORI_YM = "";
            if ($tblCTL["row"] > 0) {
                $SYORI_YM = $tblCTL['data'][0]['SYORI_YM'];
                //日付形式を確認する
                $date = $SYORI_YM . '01';
                if (date('Ymd', strtotime($date)) != $date) {
                    //年月格式正しくない
                    throw new \Exception("String \"" . $SYORI_YM . "\" から型 'Date' への変換は無効です。");
                }
            } else {
                //年月なし
                throw new \Exception("コントロールマスタが存在しません！");
            }
            $result['data']['SYORI_YM'] = $SYORI_YM;

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //実行ボタンクリック
    public function btnActionClick()
    {
        $this->frmTentyoSyoreiCalc = new FrmTentyoSyoreiCalc();
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        $blnTran = FALSE;

        try {
            if (isset($_POST['data'])) {
                $dtpYM = $_POST['data']['dtpYM'];
                //指定パスのファイルチェック
                $basePath = dirname(dirname(dirname(__FILE__)));
                $tmpPath = $basePath . "/" . $this->ClsComFncJKSYS->FncGetPath("JksysPathFrom");
                if ($this->ClsComFncJKSYS->FncGetPath("JksysPathFrom") == "") {
                    $result['message'] = '取込対象のファイルを指定してください。';
                    throw new \Exception('W9999');
                }
                if (($this->ClsComFncJKSYS->FncFileExists($tmpPath)) == FALSE) {
                    $result['message'] = '指定されたフォルダは存在しません。';
                    throw new \Exception("W9999");
                }
                if (!(is_readable($tmpPath) && is_writable($tmpPath) && is_executable($tmpPath))) {
                    throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                }

                //トランザクション開始
                $this->frmTentyoSyoreiCalc->Do_transaction();
                $blnTran = TRUE;

                //1.店長奨励金データの前準備
                $result_del = $this->procPreTenchoSyoureiKinData($dtpYM);
                if (!$result_del['result']) {
                    throw new \Exception($result_del['error']);
                }
                //2.データの存在チェック
                $result_check = $this->procCheckData($dtpYM);
                if (!$result_check['result']) {
                    if (isset($result_check['message'])) {
                        $result['message'] = $result_check['message'];
                    }
                    throw new \Exception($result_check['error']);
                }
                //3.店長奨励金データの登録(INSERT)
                $result_ins = $this->frmTentyoSyoreiCalc->procInsertTenchoSyoreiKinData($dtpYM);
                if (!$result_ins['result']) {
                    throw new \Exception($result_ins['data']);
                }
                //4.店長奨励金係数データの登録(INSERT) *一部あとでUPDATE
                $result_upd = $this->frmTentyoSyoreiCalc->procInsertTenchoSyoreiKeisuData($dtpYM);
                if (!$result_upd['result']) {
                    throw new \Exception($result_upd['data']);
                }
                //5.店長奨励金実績1人当たりデータの更新(UPDATE)
                $result_upd = $this->frmTentyoSyoreiCalc->procUpdateTenchoSyoreiJisseki_1Data($dtpYM);
                if (!$result_upd['result']) {
                    throw new \Exception($result_upd['data']);
                }
                //6.店長奨励金係数データの更新(UPDATE)
                $result_upd = $this->frmTentyoSyoreiCalc->procUpdateTenchoSyoreiKeisuData($dtpYM);
                if (!$result_upd['result']) {
                    throw new \Exception($result_upd['data']);
                }
                //7.店長奨励金データの更新(UPDATE)
                $result_upd = $this->frmTentyoSyoreiCalc->procUpdateTenchoSyoreiKinData($dtpYM);
                if (!$result_upd['result']) {
                    throw new \Exception($result_upd['data']);
                }
                //8.店長奨励手当社員別支給データの登録(INSERT)
                $result_ins = $this->frmTentyoSyoreiCalc->procInsertTenchoSyoreiKinSyainData($dtpYM);
                if (!$result_ins['result']) {
                    throw new \Exception($result_ins['data']);
                }
                //9.奨励金マスタの更新(PHP中此表相应的字段没用到，不更新)
                //コミット
                $this->frmTentyoSyoreiCalc->Do_commit();
                $blnTran = FALSE;

                //10.CSV出力
                include_once dirname(__DIR__) . "/JKSYS/BsyoreiInfoCsvController.php";
                $bsyoreiInfoCsv = new BsyoreiInfoCsvController();
                $bsyoreiInfoCsv->intSyourei_Kbn = "2";
                $bsyoreiInfoCsv->strTaisyou_YM = $dtpYM;
                $bsyoreiInfoCsv->ClsLogControl = $this->ClsLogControl;
                $bsyoreiInfoCsv->strPass = $tmpPath;
                //完了メッセージ表示
                $bsyoreiInfoCsv->prvArgNM = $this->prvArgNM;
                $bsyoreiInfoCsv->ClsComFncJKSYS = $this->ClsComFncJKSYS;
                $result_csv = $bsyoreiInfoCsv->Fnc_CSVOut();
                if (isset($result_csv['logResult']) && !$result_csv['logResult']) {
                    throw new \Exception($result_csv['logError']);
                }
                if (!$result_csv['result']) {
                    throw new \Exception($result_csv['error']);
                }
            }
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
            if ($blnTran) {
                $this->frmTentyoSyoreiCalc->Do_rollback();
            }
        }
        $this->fncReturn($result);
    }

    //業績奨励金データの削除
    public function procPreTenchoSyoureiKinData($dtpYM)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            //1.JKGYOSEKISYOREI
            $result_del = $this->frmTentyoSyoreiCalc->proDeleteJKGYOSEKISYOREI($dtpYM);
            if (!$result_del['result']) {
                throw new \Exception($result_del['data']);
            }
            //2.JKGYOSEKISYOREIKEISU
            $result_del = $this->frmTentyoSyoreiCalc->proDeleteJKGYOSEKISYOREIKEISU($dtpYM);
            if (!$result_del['result']) {
                throw new \Exception($result_del['data']);
            }
            //2.JKTENCHOSYOREISYAIN
            $result_del = $this->frmTentyoSyoreiCalc->proDeleteJKTENCHOSYOREISYAIN($dtpYM);
            if (!$result_del['result']) {
                throw new \Exception($result_del['data']);
            }
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    //データの存在チェック
    public function procCheckData($dtpYM)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );

        try {
            $dtpYMVal = $dtpYM;
            $strDataNM = array(
                "人員情報",
                "任意保険新規情報",
                "パックｄｅメンテ情報",
                "サービス貢献度情報",
                "ＪＡＦ情報",
                "（TMRH）リース_新規"
            );
            $strDataTblNM = array(
                "EXJININ00000001",
                "EXNINIHOKEN0001",
                "EXPACKDEMENTE01",
                "EXSVCKOUKEN0001",
                "EXJAF0000000001",
                "EXHMLEASE000001"
            );

            for ($intIdx = 0; $intIdx < count($strDataNM); $intIdx++) {
                $result_check = $this->frmTentyoSyoreiCalc->procCheckDataLogic($strDataTblNM[$intIdx], $dtpYMVal);
                if (!$result_check["result"]) {
                    throw new \Exception($result_check['data']);
                }
                //データが存在しない場合、エラー。メッセージを表示し、処理を中断します
                if ($result_check['data'][0]['CNT'] == 0) {
                    $dtpYM = $this->getPreMonth($dtpYMVal, -1);
                    $year = substr($dtpYM, 0, 4);
                    $mon = substr($dtpYM, -2, 2);
                    $message = "YYYY年MM月の" . $strDataNM[$intIdx];
                    $message = str_replace("YYYY", $year, $message);
                    $message = str_replace("MM", $mon, $message);
                    //メッセージコード：W0008 %1=データ名称"
                    $result['message'] = $message;

                    throw new \Exception('W0008');
                }
            }
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    //支給年月
    public function getPreMonth($dtpYM, $num)
    {
        $dtpYM = $dtpYM . "01";
        $rtnDate = date('Ym', strtotime("$dtpYM $num month"));

        return $rtnDate;
    }

}
