<?php
/**
 * 説明：
 *
 *
 * @author yin
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                   Feature/Bug                       内容                         担当
 * YYYYMMDD                  #ID                          XXXXXX                       FCSDL
 * 20240722               仕様変更           202407_人事考課表作成ツール_再生係仕様変更     YIN
 * 20240829               仕様変更           202407_人事考課表作成ツール_再生係仕様変更(仕様記載漏れ修正)     lhb
 * 20240903               仕様変更           CNS_17 の場合、07:総数と05:順位は不要です     lhb
 * 20250418               仕様変更       202504_人事考課表作成ツール_集計仕様変更.xlsx     lujunxia
 * 20250429               仕様変更     202504_人事考課表作成ツール_集計仕様変更.xlsx-仕様変更20250428      lujunxia
 * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmEvaluationtotal;
//*******************************************
// * sample controller
//*******************************************
//店長
define('CNS_01', '01');
//販売課長
define('CNS_02', '02');
//新車業販管理職
define('CNS_03', '03');
//中古車直販管理職
define('CNS_04', '04');
//中古車業販管理職
define('CNS_05', '05');
//サービス管理職
define('CNS_06', '06');
//間接管理職
define('CNS_07', '07');
//新車営業職
define('CNS_08', '08');
//新車業販営業職
define('CNS_09', '09');
//中古車直販営業職
define('CNS_10', '10');
//中古車業販営業職
define('CNS_11', '11');
//サービス・アドバイザー
define('CNS_12', '12');
//サービス・エンジニア
define('CNS_13', '13');
//間接スタッフ
define('CNS_14', '14');
// 20240722 YIN INS S
//BPアドバイザ用集計
define('CNS_15', '15');
//BPエンジニア用集計
define('CNS_16', '16');
//BP管理職用集計
define('CNS_17', '17');
//再生エンジニア用集計
define('CNS_18', '18');
// 20240722 YIN INS E
// 20250429 lujunxia ins s
// サービス拠点
define('SERVICE_BASE', "'247','297','327','337','367','387','397','417','427','437','447','467','517','527','537','637','667','707'");
// 20250429 lujunxia ins e
class FrmEvaluationtotalController extends AppController
{

    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    public $FrmEvaluationtotal;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsLogControl');
        $this->loadComponent('ClsComFncJKSYS');
    }
    //　デフォルトで最初に実行される機能
    public function index()
    {
        // Viewファイル呼出し
        $this->render('index', 'FrmEvaluationtotal_layout');
    }

    public function getJKCONTROLMST()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            $this->FrmEvaluationtotal = new FrmEvaluationtotal();
            //データ取得(人事コントロールマスタ)
            $getJKCONTROLMST = $this->FrmEvaluationtotal->SelJKCONTROLMSE_SQL('01');
            if (!$getJKCONTROLMST['result']) {
                throw new \Exception($getJKCONTROLMST['data']);
            }
            if ($getJKCONTROLMST['row'] > 0) {
                $result['data']['ymd'] = $getJKCONTROLMST['data'];
            } else {
                throw new \Exception('W0008');
            }
            //データ取得(社員別考課表タイプデータ.評価対象期間終了)
            $DT = $this->FrmEvaluationtotal->SelJKKOUKA_SYAIN_TYPE_SQL('');
            if (!$DT['result']) {
                throw new \Exception($DT['data']);
            }
            //データが存在する場合
            if ($DT['data'][0]['HYOUKA_KIKAN_END']) {
                $date = $this->GetEndDate($DT['data'][0]['HYOUKA_KIKAN_END']);
                if (!$date['result']) {
                    throw new \Exception($date['error']);
                }
                $result['data']['dtpTaisyouKE'] = $date['data'];
            } else {
                $date = date('Ym');
                $result['data']['dtpTaisyouKE'] = $date;
            }
            /* ---------------------------------------------------------------------
             *考課表ﾀｲﾌﾟｺﾝﾎﾞﾎﾞｯｸｽに値を設定する
             * ---------------------------------------------------------------------*/
            include_once dirname(__DIR__) . '/JKSYS/KoukaTypeController.php';
            $KoukaType = new KoukaTypeController();
            $cboKoukaType = $KoukaType->SetComboBox();
            if (!$cboKoukaType['result']) {
                throw new \Exception($cboKoukaType['error']);
            }
            $result['data']['select'] = $cboKoukaType['data'];

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function cmdApplyClick()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        $prv6Month = '';
        $prv1Yere = '';
        $blnTran = FALSE;
        try {
            $prvKisyuYM = $_POST['data']['prvKisyuYM'];
            $rdoBoth = $_POST['data']['rdoBoth'];
            $rdo6Months = $_POST['data']['rdo6Months'];
            $rdo1year = $_POST['data']['rdo1year'];
            $dtpTaisyouKE = $_POST['data']['dtpTaisyouKE'];
            $cboKoukaType = $_POST['data']['cboKoukaType'];
            $rdoExct_Grop = $_POST['data']['rdoExct_Grop'];
            $rdoExct_Type = $_POST['data']['rdoExct_Type'];
            $kensu = $_POST['data']['kensu'];
            $this->FrmEvaluationtotal = new FrmEvaluationtotal();
            //評価期間開始6ヶ月
            if (($rdoBoth == "true") || ($rdo6Months == "true")) {
                $prv6Month = $this->AddMonths($dtpTaisyouKE, '-5');
            }

            //評価期間開始1年
            if (($rdoBoth == "true") || ($rdo1year == "true")) {
                $prv1Yere = $this->AddMonths($dtpTaisyouKE, '-11');
            }
            //トランザクション開始
            $this->FrmEvaluationtotal->Do_transaction();
            $blnTran = TRUE;
            if ($kensu !== '0') {
                //実績集計データ削除
                $result_syukeidel = $this->FrmEvaluationtotal->DEL_JISSEKI_SYUKEI_SQL('0', $dtpTaisyouKE, $rdoBoth, $rdo1year, $rdo6Months, $cboKoukaType);
                if (!$result_syukeidel['result']) {
                    throw new \Exception($result_syukeidel['data']);
                }
                //周辺利益集計データ削除
                $result_riekidel = $this->FrmEvaluationtotal->DEL_SYUHEN_RIEKI_SQL('0', $cboKoukaType, $dtpTaisyouKE, $rdoBoth, $rdo6Months, $rdo1year);
                if (!$result_riekidel['result']) {

                    throw new \Exception($result_riekidel['data']);
                }
            }

            //実績集計データ及び周辺利益集計データの作成
            $result_syukei = $this->fncSyukei_Create($dtpTaisyouKE, $cboKoukaType, $prv6Month, $prv1Yere, $prvKisyuYM);
            if ($result_syukei['result'] == FALSE) {
                throw new \Exception($result_syukei['error']);
            }
            //順位、達成度の設定
            $result_junni = $this->fncJunni_Create($dtpTaisyouKE, $cboKoukaType, $prv6Month, $prv1Yere, $rdoExct_Grop, $rdoExct_Type);
            if ($result_junni['result'] == FALSE) {
                throw new \Exception($result_junni['error']);
            }
            //コミット
            $this->FrmEvaluationtotal->Do_commit();
            $blnTran = FALSE;
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
            //ロールバック
            if ($blnTran) {
                $this->FrmEvaluationtotal->Do_rollback();
            }
        }

        $this->fncReturn($result);
    }

    public function cmdReApplyClick()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        $prv6Month = '';
        $prv1Yere = '';
        $blnTran = FALSE;
        try {
            $rdoBoth = $_POST['data']['rdoBoth'];
            $rdo6Months = $_POST['data']['rdo6Months'];
            $rdo1year = $_POST['data']['rdo1year'];
            $dtpTaisyouKE = $_POST['data']['dtpTaisyouKE'];
            $cboKoukaType = $_POST['data']['cboKoukaType'];
            $rdoExct_Grop = $_POST['data']['rdoExct_Grop'];
            $rdoExct_Type = $_POST['data']['rdoExct_Type'];
            $this->FrmEvaluationtotal = new FrmEvaluationtotal();

            //評価期間開始6ヶ月
            if (($rdoBoth == "true") || ($rdo6Months == "true")) {
                $prv6Month = $this->AddMonths($dtpTaisyouKE, '-5');
            }

            //評価期間開始1年
            if (($rdoBoth == "true") || ($rdo1year == "true")) {
                $prv1Yere = $this->AddMonths($dtpTaisyouKE, '-11');
            }

            //トランザクション開始
            $this->FrmEvaluationtotal->Do_transaction();
            $blnTran = TRUE;
            //実績集計データ削除
            $result_syukeidel = $this->FrmEvaluationtotal->DEL_JISSEKI_SYUKEI_SQL('04', $dtpTaisyouKE, $rdoBoth, $rdo1year, $rdo6Months, $cboKoukaType);
            if (!$result_syukeidel['result']) {
                throw new \Exception($result_syukeidel['data']);
            }
            //周辺利益集計データ削除
            $result_riekidel = $this->FrmEvaluationtotal->DEL_SYUHEN_RIEKI_SQL('04', $cboKoukaType, $dtpTaisyouKE, $rdoBoth, $rdo6Months, $rdo1year);
            if (!$result_riekidel['result']) {
                throw new \Exception($result_riekidel['data']);
            }
            //順位、達成度の設定
            $result_junni = $this->fncJunni_Create($dtpTaisyouKE, $cboKoukaType, $prv6Month, $prv1Yere, $rdoExct_Grop, $rdoExct_Type);
            if ($result_junni['result'] == FALSE) {
                throw new \Exception($result_junni['error']);
            }
            //コミット
            $this->FrmEvaluationtotal->Do_commit();
            $blnTran = FALSE;
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
            //ロールバック
            if ($blnTran) {
                $this->FrmEvaluationtotal->Do_rollback();
            }
        }

        $this->fncReturn($result);
    }

    //実績集計データ/周辺利益集計データ作成
    private function fncSyukei_Create($dtpTaisyouKE, $cboKoukaType, $prv6Month, $prv1Yere, $prvKisyuYM)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );

        try {
            //対象データ取得
            $DT = $this->FrmEvaluationtotal->SelJKKOUKA_SYAIN_SQL($cboKoukaType, $dtpTaisyouKE);
            if ($DT['result'] == FALSE) {
                throw new \Exception($DT['data']);
            }
            //対象ﾃﾞｰﾀがない場合、ﾒｯｾｰｼﾞを表示し処理を抜ける
            if ($DT['row'] == 0) {
                throw new \Exception('I0001');
            }

            // ---------------------------------------------------------------------
            // 集計データ作成
            // ---------------------------------------------------------------------
            // 考課表パターン='07':間接管理職 '14':間接スタッフの実績は作成しない
            if ($cboKoukaType == '' || $cboKoukaType == '01') {
                //◆◆◆　店長　◆◆◆
                if ($prv6Month !== '') {
                    $Ins_01 = $this->Ins_01($prv6Month, 0, $dtpTaisyouKE, $prvKisyuYM);
                    if ($Ins_01['result'] == FALSE) {
                        throw new \Exception($Ins_01['error']);
                    }

                }
                if ($prv1Yere !== '') {
                    $Ins_01 = $this->Ins_01($prv1Yere, 1, $dtpTaisyouKE, $prvKisyuYM);
                    if ($Ins_01['result'] == FALSE) {
                        throw new \Exception($Ins_01['error']);
                    }

                }
            }
            if ($cboKoukaType == '' || $cboKoukaType == '02') {
                //◆◆◆　販売課長　◆◆◆
                if ($prv6Month !== '') {
                    $Ins_02 = $this->Ins_02($prv6Month, 0, $dtpTaisyouKE, $prvKisyuYM);
                    if ($Ins_02['result'] == FALSE) {
                        throw new \Exception($Ins_02['error']);
                    }

                }
                if ($prv1Yere !== '') {
                    $Ins_02 = $this->Ins_02($prv1Yere, 1, $dtpTaisyouKE, $prvKisyuYM);
                    if ($Ins_02['result'] == FALSE) {
                        throw new \Exception($Ins_02['error']);
                    }

                }
            }
            if ($cboKoukaType == '' || $cboKoukaType == '03') {
                //◆◆◆　新車業販管理職　◆◆◆
                if ($prv6Month !== '') {
                    $Ins_03 = $this->Ins_03($prv6Month, 0, $dtpTaisyouKE, $prvKisyuYM);
                    if ($Ins_03['result'] == FALSE) {
                        throw new \Exception($Ins_03['error']);
                    }

                }
                if ($prv1Yere !== '') {
                    $Ins_03 = $this->Ins_03($prv1Yere, 1, $dtpTaisyouKE, $prvKisyuYM);
                    if ($Ins_03['result'] == FALSE) {
                        throw new \Exception($Ins_03['error']);
                    }

                }
            }

            if ($cboKoukaType == '' || $cboKoukaType == '04') {
                //◆◆◆　中古車直販管理職　◆◆◆
                if ($prv6Month !== '') {
                    $Ins_04 = $this->Ins_04($prv6Month, 0, $dtpTaisyouKE, $prvKisyuYM);
                    if ($Ins_04['result'] == FALSE) {
                        throw new \Exception($Ins_04['error']);
                    }

                }
                if ($prv1Yere !== '') {
                    $Ins_04 = $this->Ins_04($prv1Yere, 1, $dtpTaisyouKE, $prvKisyuYM);
                    if ($Ins_04['result'] == FALSE) {
                        throw new \Exception($Ins_04['error']);
                    }

                }
            }

            if ($cboKoukaType == '' || $cboKoukaType == '05') {
                //◆◆◆　中古車業販管理職　◆◆◆
                if ($prv6Month !== '') {
                    $Ins_05 = $this->Ins_05($prv6Month, 0, $dtpTaisyouKE, $prvKisyuYM);
                    if ($Ins_05['result'] == FALSE) {
                        throw new \Exception($Ins_05['error']);
                    }

                }
                if ($prv1Yere !== '') {
                    $Ins_05 = $this->Ins_05($prv1Yere, 1, $dtpTaisyouKE, $prvKisyuYM);
                    if ($Ins_05['result'] == FALSE) {
                        throw new \Exception($Ins_05['error']);
                    }

                }
            }
            if ($cboKoukaType == '' || $cboKoukaType == '06') {
                //◆◆◆　サービス管理職　◆◆◆
                if ($prv6Month !== '') {
                    $Ins_06 = $this->Ins_06($prv6Month, 0, $dtpTaisyouKE, $prvKisyuYM);
                    if ($Ins_06['result'] == FALSE) {
                        throw new \Exception($Ins_06['error']);
                    }

                }
                if ($prv1Yere !== '') {
                    $Ins_06 = $this->Ins_06($prv1Yere, 1, $dtpTaisyouKE, $prvKisyuYM);
                    if ($Ins_06['result'] == FALSE) {
                        throw new \Exception($Ins_06['error']);
                    }

                }
            }

            if ($cboKoukaType == '' || $cboKoukaType == '08') {
                //◆◆◆　新車営業職　◆◆◆
                if ($prv6Month !== '') {
                    $Ins_08 = $this->Ins_08($prv6Month, 0, $dtpTaisyouKE);
                    if ($Ins_08['result'] == FALSE) {
                        throw new \Exception($Ins_08['error']);
                    }

                }
                if ($prv1Yere !== '') {
                    $Ins_08 = $this->Ins_08($prv1Yere, 1, $dtpTaisyouKE);
                    if ($Ins_08['result'] == FALSE) {
                        throw new \Exception($Ins_08['error']);
                    }

                }
            }

            if ($cboKoukaType == '' || $cboKoukaType == '09') {
                //◆◆◆　新車業販営業職　◆◆◆
                if ($prv6Month !== '') {
                    $Ins_09 = $this->Ins_09($prv6Month, $dtpTaisyouKE);
                    if ($Ins_09['result'] == FALSE) {
                        throw new \Exception($Ins_09['error']);
                    }

                }
                if ($prv1Yere !== '') {
                    $Ins_09 = $this->Ins_09($prv1Yere, $dtpTaisyouKE);
                    if ($Ins_09['result'] == FALSE) {
                        throw new \Exception($Ins_09['error']);
                    }

                }
            }

            if ($cboKoukaType == '' || $cboKoukaType == '10') {
                //◆◆◆　中古車直販営業職　◆◆◆
                if ($prv6Month !== '') {
                    $Ins_10 = $this->Ins_10($prv6Month, 0, $dtpTaisyouKE);
                    if ($Ins_10['result'] == FALSE) {
                        throw new \Exception($Ins_10['error']);
                    }

                }
                if ($prv1Yere !== '') {
                    $Ins_10 = $this->Ins_10($prv1Yere, 1, $dtpTaisyouKE);
                    if ($Ins_10['result'] == FALSE) {
                        throw new \Exception($Ins_10['error']);
                    }

                }
            }

            if ($cboKoukaType == '' || $cboKoukaType == '11') {
                //◆◆◆　中古車業販営業職　◆◆◆
                if ($prv6Month !== '') {
                    $Ins_11 = $this->Ins_11($prv6Month, $dtpTaisyouKE);
                    if ($Ins_11['result'] == FALSE) {
                        throw new \Exception($Ins_11['error']);
                    }

                }
                if ($prv1Yere !== '') {
                    $Ins_11 = $this->Ins_11($prv1Yere, $dtpTaisyouKE);
                    if ($Ins_11['result'] == FALSE) {
                        throw new \Exception($Ins_11['error']);
                    }

                }
            }
            if ($cboKoukaType == '' || $cboKoukaType == '12') {
                //◆◆◆　サービス・アドバイザー　◆◆◆
                if ($prv6Month !== '') {
                    $Ins_12 = $this->Ins_12($prv6Month, 0, $dtpTaisyouKE, $prvKisyuYM);
                    if ($Ins_12['result'] == FALSE) {
                        throw new \Exception($Ins_12['error']);
                    }

                }
                if ($prv1Yere !== '') {
                    $Ins_12 = $this->Ins_12($prv1Yere, 1, $dtpTaisyouKE, $prvKisyuYM);
                    if ($Ins_12['result'] == FALSE) {
                        throw new \Exception($Ins_12['error']);
                    }

                }
            }
            if ($cboKoukaType == '' || $cboKoukaType == '13') {
                //◆◆◆　サービス・エンジニア　◆◆◆
                if ($prv6Month !== '') {
                    $Ins_13 = $this->Ins_13($prv6Month, 0, $dtpTaisyouKE, $prvKisyuYM);
                    if ($Ins_13['result'] == FALSE) {
                        throw new \Exception($Ins_13['error']);
                    }

                }
                if ($prv1Yere !== '') {
                    $Ins_13 = $this->Ins_13($prv1Yere, 1, $dtpTaisyouKE, $prvKisyuYM);
                    if ($Ins_13['result'] == FALSE) {
                        throw new \Exception($Ins_13['error']);
                    }

                }
            }
            // 20240722 YIN INS S
            if ($cboKoukaType == '' || $cboKoukaType == '15') {
                //◆◆◆　BP・アドバイザー　◆◆◆
                if ($prv6Month !== '') {
                    $Ins_15 = $this->Ins_15($prv6Month, 0, $dtpTaisyouKE, $prvKisyuYM);
                    if ($Ins_15['result'] == FALSE) {
                        throw new \Exception($Ins_15['error']);
                    }
                }
            }
            if ($cboKoukaType == '' || $cboKoukaType == '16') {
                //◆◆◆　BP・エンジニア　◆◆◆
                if ($prv6Month !== '') {
                    $Ins_16 = $this->Ins_16($prv6Month, 0, $dtpTaisyouKE, $prvKisyuYM);
                    if ($Ins_16['result'] == FALSE) {
                        throw new \Exception($Ins_16['error']);
                    }

                }
            }
            if ($cboKoukaType == '' || $cboKoukaType == '17') {
                //◆◆◆　BP・管理職　◆◆◆
                if ($prv6Month !== '') {
                    $Ins_17 = $this->Ins_17($prv6Month, 0, $dtpTaisyouKE, $prvKisyuYM);
                    if ($Ins_17['result'] == FALSE) {
                        throw new \Exception($Ins_17['error']);
                    }
                }
            }
            if ($cboKoukaType == '' || $cboKoukaType == '18') {
                //◆◆◆　再生・エンジニア　◆◆◆
                if ($prv6Month !== '') {
                    $Ins_18 = $this->Ins_18($prv6Month, $dtpTaisyouKE);
                    if ($Ins_18['result'] == FALSE) {
                        throw new \Exception($Ins_18['error']);
                    }
                }
            }
            // 20240722 YIN INS E
            $result['result'] = TRUE;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    private function Ins_01($strStartYm, $intKikanKbn, $dtpTaisyouKE, $prvKisyuYM)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        //前年実績用
        $strSumStartYm = '';
        $strSumEndYm = $this->AddMonths($dtpTaisyouKE, '-12');
        $strSyukeiKomoku = '';
        $strKomoku = '';
        $intLineNo = 0;

        try {
            $prvEndYm = $dtpTaisyouKE;
            if ($intKikanKbn == 0) {
                //6ヶ月
                $strSumStartYm = $this->AddMonths($dtpTaisyouKE, '-17');
            } else {
                //1年
                $strSumStartYm = $this->AddMonths($dtpTaisyouKE, '-23');
            }
            // '==================================================================
            // '     実績集計データ 作成
            // '==================================================================
            // '------------------------------
            // '01:経常利益
            // '------------------------------
            $strSyukeiKomoku = '01';
            $intLineNo = 114;
            //'◆　01:基準
            $result = $this->FrmEvaluationtotal->fnc_InsYosan(CNS_01, $strStartYm, $intKikanKbn, $strSyukeiKomoku, $intLineNo, TRUE, $dtpTaisyouKE, $prvKisyuYM, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_01, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_01, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // '------------------------------
            // '02:経常利益前年比
            // '------------------------------
            $strSyukeiKomoku = '02';
            $intLineNo = 114;
            //'◆　01:基準
            $strKomoku = '01';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_01, $strStartYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_01, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_01, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // '------------------------------
            // '05:新車台数
            // '------------------------------
            $strSyukeiKomoku = '05';
            $intLineNo = 11;
            //'◆　01:基準
            $result = $this->FrmEvaluationtotal->fnc_InsYosan(CNS_01, $strStartYm, $intKikanKbn, $strSyukeiKomoku, $intLineNo, FALSE, $dtpTaisyouKE, $prvKisyuYM, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_01, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, FALSE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_01, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            // '------------------------------
            // '06:新車台数前年比
            // '------------------------------
            $strSyukeiKomoku = '06';
            $intLineNo = 11;
            $strKomoku = '01';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_01, $strStartYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, FALSE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_01, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, FALSE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_01, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // '------------------------------
            // '11:整備限界利益
            // '------------------------------
            $strSyukeiKomoku = '11';
            $intLineNo = 87;

            //'◆　01:基準
            $result = $this->FrmEvaluationtotal->fnc_InsYosan(CNS_01, $strStartYm, $intKikanKbn, $strSyukeiKomoku, $intLineNo, TRUE, $dtpTaisyouKE, $prvKisyuYM, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_01, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_01, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // '------------------------------
            // '12:保険
            // '------------------------------
            $strSyukeiKomoku = '12';
            //'◆　01:基準
            $strKomoku = '01';
            $result = $this->FrmEvaluationtotal->fnc_InsHoken(CNS_01, $strStartYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $strKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsHoken(CNS_01, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_01, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // '==================================================================
            // '     周辺利益集計データ 作成
            // '==================================================================
            // '------------------------------
            // '00:延べ人員
            // '------------------------------
            // '◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsJinin(CNS_01, $strStartYm, $strStartYm, $prvEndYm, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // '------------------------------
            // '01:ボディコーティング
            // '------------------------------
            $intKikan = 0;
            if ($intKikanKbn == 0) {
                $intKikan = 6;
            } else {
                $intKikan = 12;
            }
            // '◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsBodyCoat(CNS_01, $strStartYm, $strStartYm, $prvEndYm, $intKikan, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // '------------------------------
            // '02:クレジットＫＢ
            // '------------------------------
            // '◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsCredit(CNS_01, $strStartYm, $strStartYm, $prvEndYm, $intKikan, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            // '------------------------------
            // '03:再リース
            // '------------------------------
            // '◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsSaiLease(CNS_01, $strStartYm, $strStartYm, $prvEndYm, $intKikan, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // '------------------------------
            // '04:ﾊﾟｯｸdeﾒﾝﾃ
            // '------------------------------
            // '◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsPackdeMente(CNS_01, $strStartYm, $strStartYm, $prvEndYm, $intKikan, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // '------------------------------
            // '05:ﾊﾟｯｸde753
            // '------------------------------
            // '◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsPackde753(CNS_01, $strStartYm, $strStartYm, $prvEndYm, $intKikan, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // '------------------------------
            // '06:JAF
            // '------------------------------
            // '◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsJAF(CNS_01, $strStartYm, $strStartYm, $prvEndYm, $intKikan, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    private function Ins_02($strStartYm, $intKikanKbn, $dtpTaisyouKE, $prvKisyuYM)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        //前年実績用
        $strSumStartYm = '';
        $strSumEndYm = $this->AddMonths($dtpTaisyouKE, '-12');
        $strSyukeiKomoku = '';
        $strKomoku = '';
        $intLineNo = 0;

        try {
            $prvEndYm = $dtpTaisyouKE;
            if ($intKikanKbn == 0) {
                //6ヶ月
                $strSumStartYm = $this->AddMonths($dtpTaisyouKE, '-17');
            } else {
                //1年
                $strSumStartYm = $this->AddMonths($dtpTaisyouKE, '-23');
            }
            // '==================================================================
            // '     実績集計データ 作成
            // '==================================================================
            // '------------------------------
            // '01:経常利益
            // '------------------------------
            $strSyukeiKomoku = '01';
            $intLineNo = 114;
            //'◆　01:基準
            $result = $this->FrmEvaluationtotal->fnc_InsYosan(CNS_02, $strStartYm, $intKikanKbn, $strSyukeiKomoku, $intLineNo, TRUE, $dtpTaisyouKE, $prvKisyuYM, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_02, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_02, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // '------------------------------
            // '02:経常利益前年比
            // '------------------------------
            $strSyukeiKomoku = '02';
            $intLineNo = 114;
            //'◆　01:基準
            $strKomoku = '01';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_02, $strStartYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_02, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_02, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // '------------------------------
            // '05:新車台数
            // '------------------------------
            $strSyukeiKomoku = '05';
            $intLineNo = 11;
            //'◆　01:基準
            $result = $this->FrmEvaluationtotal->fnc_InsYosan(CNS_02, $strStartYm, $intKikanKbn, $strSyukeiKomoku, $intLineNo, FALSE, $dtpTaisyouKE, $prvKisyuYM, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_02, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, FALSE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_02, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            // '------------------------------
            // '06:新車台数前年比
            // '------------------------------
            $strSyukeiKomoku = '06';
            $intLineNo = 11;
            $strKomoku = '01';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_02, $strStartYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, FALSE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_02, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, FALSE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_02, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //12:保険
            $strSyukeiKomoku = '12';
            //◆　01:基準
            $strKomoku = '01';
            $result = $this->FrmEvaluationtotal->fnc_InsHoken(CNS_02, $strStartYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $strKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsHoken(CNS_02, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_02, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //周辺利益集計データ 作成
            $intKikan = 0;
            if ($intKikanKbn == 0) {
                $intKikan = 6;
            } else {
                $intKikan = 12;
            }
            //00:延べ人員
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsJinin(CNS_02, $strStartYm, $strStartYm, $prvEndYm, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //01:ボディコーティング
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsBodyCoat(CNS_02, $strStartYm, $strStartYm, $prvEndYm, $intKikan, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //02:クレジットＫＢ
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsCredit(CNS_02, $strStartYm, $strStartYm, $prvEndYm, $intKikan, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //03:再リース
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsSaiLease(CNS_02, $strStartYm, $strStartYm, $prvEndYm, $intKikan, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //04:ﾊﾟｯｸdeﾒﾝﾃ
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsPackdeMente(CNS_02, $strStartYm, $strStartYm, $prvEndYm, $intKikan, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //05:ﾊﾟｯｸde753
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsPackde753(CNS_02, $strStartYm, $strStartYm, $prvEndYm, $intKikan, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //06:JAF
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsJAF(CNS_02, $strStartYm, $strStartYm, $prvEndYm, $intKikan, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    private function Ins_03($strStartYm, $intKikanKbn, $dtpTaisyouKE, $prvKisyuYM)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        //前年実績用
        $strSumStartYm = '';
        $strSumEndYm = $this->AddMonths($dtpTaisyouKE, '-12');
        $strSyukeiKomoku = '';
        $strKomoku = '';
        $intLineNo = 0;

        try {
            $prvEndYm = $dtpTaisyouKE;
            if ($intKikanKbn == 0) {
                //6ヶ月
                $strSumStartYm = $this->AddMonths($dtpTaisyouKE, '-17');
            } else {
                //1年
                $strSumStartYm = $this->AddMonths($dtpTaisyouKE, '-23');
            }
            // '==================================================================
            // '     実績集計データ 作成
            // '==================================================================
            // '------------------------------
            // '01:経常利益
            // '------------------------------
            $strSyukeiKomoku = '01';
            $intLineNo = 114;
            //'◆　01:基準
            $result = $this->FrmEvaluationtotal->fnc_InsYosan(CNS_03, $strStartYm, $intKikanKbn, $strSyukeiKomoku, $intLineNo, TRUE, $dtpTaisyouKE, $prvKisyuYM, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_03, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_03, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // '------------------------------
            // '02:経常利益前年比
            // '------------------------------
            $strSyukeiKomoku = '02';
            $intLineNo = 114;
            //'◆　01:基準
            $strKomoku = '01';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_03, $strStartYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_03, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_03, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // '------------------------------
            // '05:新車台数
            // '------------------------------
            $strSyukeiKomoku = '05';
            $intLineNo = 11;
            //'◆　01:基準
            $result = $this->FrmEvaluationtotal->fnc_InsYosan(CNS_03, $strStartYm, $intKikanKbn, $strSyukeiKomoku, $intLineNo, FALSE, $dtpTaisyouKE, $prvKisyuYM, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_03, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, FALSE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_03, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            // '------------------------------
            // '06:新車台数前年比
            // '------------------------------
            $strSyukeiKomoku = '06';
            $intLineNo = 11;
            $strKomoku = '01';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_03, $strStartYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, FALSE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_03, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, FALSE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_03, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    private function Ins_04($strStartYm, $intKikanKbn, $dtpTaisyouKE, $prvKisyuYM)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        //前年実績用
        $strSumStartYm = '';
        $strSumEndYm = $this->AddMonths($dtpTaisyouKE, '-12');
        $strSyukeiKomoku = '';
        $strKomoku = '';
        $intLineNo = 0;

        try {
            $prvEndYm = $dtpTaisyouKE;
            if ($intKikanKbn == 0) {
                //6ヶ月
                $strSumStartYm = $this->AddMonths($dtpTaisyouKE, '-17');
            } else {
                //1年
                $strSumStartYm = $this->AddMonths($dtpTaisyouKE, '-23');
            }
            // '==================================================================
            // '     実績集計データ 作成
            // '==================================================================
            // '------------------------------
            // '01:経常利益
            // '------------------------------
            $strSyukeiKomoku = '01';
            $intLineNo = 114;
            //'◆　01:基準
            $result = $this->FrmEvaluationtotal->fnc_InsYosan(CNS_04, $strStartYm, $intKikanKbn, $strSyukeiKomoku, $intLineNo, TRUE, $dtpTaisyouKE, $prvKisyuYM, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_04, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_04, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // '------------------------------
            // '02:経常利益前年比
            // '------------------------------
            $strSyukeiKomoku = '02';
            $intLineNo = 114;
            //'◆　01:基準
            $strKomoku = '01';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_04, $strStartYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_04, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_04, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //07:中古車台数
            $strSyukeiKomoku = '07';
            $intLineNo = 44;
            //◆　01:基準
            $result = $this->FrmEvaluationtotal->fnc_InsYosan(CNS_04, $strStartYm, $intKikanKbn, $strSyukeiKomoku, $intLineNo, FALSE, $dtpTaisyouKE, $prvKisyuYM, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_04, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, FALSE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_04, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //08:中古車台数前年比
            $strSyukeiKomoku = '08';
            $intLineNo = 44;
            //◆　01:基準
            $strKomoku = '01';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_04, $strStartYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, FALSE, $prvEndYm, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_04, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, FALSE, $prvEndYm, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_04, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //12:保険/クレジット
            $strSyukeiKomoku = '12';
            //◆　01:基準
            $strKomoku = '01';
            $result = $this->FrmEvaluationtotal->fnc_InsHokenCredit(CNS_04, $strStartYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $strKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsHokenCredit(CNS_04, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_04, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    private function Ins_05($strStartYm, $intKikanKbn, $dtpTaisyouKE, $prvKisyuYM)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        //前年実績用
        $strSumStartYm = '';
        $strSumEndYm = $this->AddMonths($dtpTaisyouKE, '-12');
        $strSyukeiKomoku = '';
        $strKomoku = '';
        $intLineNo = 0;

        try {
            $prvEndYm = $dtpTaisyouKE;
            if ($intKikanKbn == 0) {
                //6ヶ月
                $strSumStartYm = $this->AddMonths($dtpTaisyouKE, '-17');
            } else {
                //1年
                $strSumStartYm = $this->AddMonths($dtpTaisyouKE, '-23');
            }
            // '==================================================================
            // '     実績集計データ 作成
            // '==================================================================
            // '------------------------------
            // '01:経常利益
            // '------------------------------
            $strSyukeiKomoku = '01';
            $intLineNo = 114;
            //'◆　01:基準
            $result = $this->FrmEvaluationtotal->fnc_InsYosan(CNS_05, $strStartYm, $intKikanKbn, $strSyukeiKomoku, $intLineNo, TRUE, $dtpTaisyouKE, $prvKisyuYM, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_05, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_05, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // '------------------------------
            // '02:経常利益前年比
            // '------------------------------
            $strSyukeiKomoku = '02';
            $intLineNo = 114;
            //'◆　01:基準
            $strKomoku = '01';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_05, $strStartYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_05, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_05, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //07:中古車台数
            $strSyukeiKomoku = '07';
            $intLineNo = 44;
            //◆　01:基準
            $result = $this->FrmEvaluationtotal->fnc_InsYosan(CNS_05, $strStartYm, $intKikanKbn, $strSyukeiKomoku, $intLineNo, FALSE, $dtpTaisyouKE, $prvKisyuYM, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_05, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, FALSE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_05, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //08:中古車台数前年比
            $strSyukeiKomoku = '08';
            $intLineNo = 44;
            //◆　01:基準
            $strKomoku = '01';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_05, $strStartYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, FALSE, $prvEndYm, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_05, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, FALSE, $prvEndYm, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_05, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    private function Ins_06($strStartYm, $intKikanKbn, $dtpTaisyouKE, $prvKisyuYM)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        //前年実績用
        $strSumStartYm = '';
        $strSumEndYm = $this->AddMonths($dtpTaisyouKE, '-12');
        $strSyukeiKomoku = '';
        $strKomoku = '';
        $intLineNo = 0;

        try {
            $prvEndYm = $dtpTaisyouKE;
            if ($intKikanKbn == 0) {
                //6ヶ月
                $strSumStartYm = $this->AddMonths($dtpTaisyouKE, '-17');
            } else {
                //1年
                $strSumStartYm = $this->AddMonths($dtpTaisyouKE, '-23');
            }
            // '==================================================================
            // '     実績集計データ 作成
            // '==================================================================
            // 20250418 lujunxia upd s
            // '------------------------------
            // '01:経常利益
            // '------------------------------
            // $strSyukeiKomoku = '01';
            // $intLineNo = 114;
            // '------------------------------
            // '19:サービス総員当りサービス総限界利益
            // '------------------------------
            $strSyukeiKomoku = '19';
            $intLineNo = 87;
            //'◆　01:基準
            //$result = $this->FrmEvaluationtotal->fnc_InsYosan(CNS_06, $strStartYm, $intKikanKbn, $strSyukeiKomoku, $intLineNo, TRUE, $dtpTaisyouKE, $prvKisyuYM, "");
            // 20250429 lujunxia upd s
            // $result = $this->FrmEvaluationtotal->fnc_InsYosan2(CNS_06, $strStartYm, $strSyukeiKomoku, $intLineNo, $dtpTaisyouKE, $prvKisyuYM);
            $result = $this->FrmEvaluationtotal->fnc_InsYosan3(CNS_06, $strStartYm, $strSyukeiKomoku, $intLineNo, $dtpTaisyouKE, $prvKisyuYM);
            // 20250429 lujunxia upd e
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　02:実績
            $strKomoku = '02';
            //$result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_06, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki2(CNS_06, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, $dtpTaisyouKE);
            // 20250418 lujunxia upd e
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_06, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // 20250418 lujunxia upd s
            // '------------------------------
            // '02:経常利益前年比
            // '------------------------------
            // $strSyukeiKomoku = '02';
            // $intLineNo = 114;
            // '------------------------------
            // '20:サービス総員当りサービス総限界利益前年比
            // '------------------------------
            $strSyukeiKomoku = '20';
            $intLineNo = 87;
            //'◆　01:基準
            $strKomoku = '01';
            //$result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_06, $strStartYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki2(CNS_06, $strStartYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　02:実績
            $strKomoku = '02';
            //$result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_06, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki2(CNS_06, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, $dtpTaisyouKE);
            // 20250418 lujunxia upd e
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_06, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //09:整備売上
            $strSyukeiKomoku = '09';
            $intLineNo = 56;
            //◆　01:基準
            $result = $this->FrmEvaluationtotal->fnc_InsYosan(CNS_06, $strStartYm, $intKikanKbn, $strSyukeiKomoku, $intLineNo, TRUE, $dtpTaisyouKE, $prvKisyuYM, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_06, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_06, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //10:整備売上前年比
            $strSyukeiKomoku = '10';
            $intLineNo = 56;
            //◆　01:基準
            $strKomoku = '01';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_06, $strStartYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_06, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_06, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //14:車検入庫
            $strSyukeiKomoku = '14';
            $strNyukoKbn = '01';
            //◆　01:基準
            $strKomoku = '01';
            $result = $this->FrmEvaluationtotal->fnc_InsNyuko(CNS_06, $strStartYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $strKomoku, $strNyukoKbn, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsNyuko(CNS_06, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $strNyukoKbn, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_06, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //15:点検入庫
            $strSyukeiKomoku = '15';
            $strNyukoKbn = '11';
            //◆　01:基準
            $strKomoku = '01';
            $result = $this->FrmEvaluationtotal->fnc_InsNyuko(CNS_06, $strStartYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $strKomoku, $strNyukoKbn, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsNyuko(CNS_06, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $strNyukoKbn, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_06, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //周辺利益集計データ 作成
            $intKikan = 0;
            if ($intKikanKbn == 0) {
                $intKikan = 6;
            } else {
                $intKikan = 12;
            }
            //00:延べ人員
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsJinin(CNS_06, $strStartYm, $strStartYm, $prvEndYm, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //04:ﾊﾟｯｸdeﾒﾝﾃ
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsPackdeMente(CNS_06, $strStartYm, $strStartYm, $prvEndYm, $intKikan, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //05:ﾊﾟｯｸde753
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsPackde753(CNS_06, $strStartYm, $strStartYm, $prvEndYm, $intKikan, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //06:JAF
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsJAF(CNS_06, $strStartYm, $strStartYm, $prvEndYm, $intKikan, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    private function Ins_08($strStartYm, $intKikanKbn, $dtpTaisyouKE)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        //前年実績用
        // $strSumStartYm = '';
        // $strSumEndYm = $this->AddMonths($dtpTaisyouKE, '-12');
        $strSyukeiKomoku = '';
        $strKomoku = '';

        try {
            $prvEndYm = $dtpTaisyouKE;
            // if ($intKikanKbn == 0) {
            //     //6ヶ月
            //     $strSumStartYm = $this->AddMonths($dtpTaisyouKE, '-17');
            // } else {
            //     //1年
            //     $strSumStartYm = $this->AddMonths($dtpTaisyouKE, '-23');
            // }
            // '==================================================================
            // '     実績集計データ 作成
            // '==================================================================
            // '------------------------------
            // '03:限界利益
            // '------------------------------
            $strSyukeiKomoku = '03';
            //◆　01:基準
            $result = $this->FrmEvaluationtotal->fnc_InsMokuhyo(CNS_08, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsStaff(CNS_08, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_08, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //05:新車台数
            $strSyukeiKomoku = '05';
            //◆　01:基準
            $result = $this->FrmEvaluationtotal->fnc_InsMokuhyo(CNS_08, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsStaff(CNS_08, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_08, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //12:保険
            $strSyukeiKomoku = '12';
            //◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsHoken(CNS_08, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //16:サービス入庫
            $strSyukeiKomoku = '16';
            $strNyukoKbn = '60';
            //◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsNyuko(CNS_08, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $strNyukoKbn, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //17:固定費/カバー率
            $strSyukeiKomoku = '17';
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsStaff(CNS_08, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //18:労働分配率
            $strSyukeiKomoku = '18';
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsStaff(CNS_08, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //周辺利益集計データ 作成
            $intKikan = 0;
            if ($intKikanKbn == 0) {
                $intKikan = 6;
            } else {
                $intKikan = 12;
            }
            //01:ボディコーティング
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsBodyCoat(CNS_08, $strStartYm, $strStartYm, $prvEndYm, $intKikan, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //02:クレジットＫＢ
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsCredit(CNS_08, $strStartYm, $strStartYm, $prvEndYm, $intKikan, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //03:再リース
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsSaiLease(CNS_08, $strStartYm, $strStartYm, $prvEndYm, $intKikan, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //04:ﾊﾟｯｸdeﾒﾝﾃ
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsPackdeMente(CNS_08, $strStartYm, $strStartYm, $prvEndYm, $intKikan, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //05:ﾊﾟｯｸde753
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsPackde753(CNS_08, $strStartYm, $strStartYm, $prvEndYm, $intKikan, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //06:JAF
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsJAF(CNS_08, $strStartYm, $strStartYm, $prvEndYm, $intKikan, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    private function Ins_09($strStartYm, $dtpTaisyouKE)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        //前年実績用
        // $strSumStartYm = '';
        // $strSumEndYm = $this->AddMonths($dtpTaisyouKE, '-12');
        $strSyukeiKomoku = '';
        // $strKomoku = '';

        try {
            $prvEndYm = $dtpTaisyouKE;
            // if ($intKikanKbn == 0) {
            //     //6ヶ月
            //     $strSumStartYm = $this->AddMonths($dtpTaisyouKE, '-17');
            // } else {
            //     //1年
            //     $strSumStartYm = $this->AddMonths($dtpTaisyouKE, '-23');
            // }
            // '==================================================================
            // '     実績集計データ 作成
            // '==================================================================
            // '------------------------------
            // '03:限界利益
            // '------------------------------
            $strSyukeiKomoku = '03';
            //◆　01:基準
            $result = $this->FrmEvaluationtotal->fnc_InsMokuhyo(CNS_09, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsStaff(CNS_09, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_09, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //05:新車台数
            $strSyukeiKomoku = '05';
            //◆　01:基準
            $result = $this->FrmEvaluationtotal->fnc_InsMokuhyo(CNS_09, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsStaff(CNS_09, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_09, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //17:固定費/カバー率
            $strSyukeiKomoku = '17';
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsStaff(CNS_09, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //18:労働分配率
            $strSyukeiKomoku = '18';
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsStaff(CNS_09, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    private function Ins_10($strStartYm, $intKikanKbn, $dtpTaisyouKE)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        //前年実績用
        // $strSumStartYm = '';
        // $strSumEndYm = $this->AddMonths($dtpTaisyouKE, '-12');
        $strSyukeiKomoku = '';
        $strKomoku = '';

        try {
            $prvEndYm = $dtpTaisyouKE;
            // if ($intKikanKbn == 0) {
            //     //6ヶ月
            //     $strSumStartYm = $this->AddMonths($dtpTaisyouKE, '-17');
            // } else {
            //     //1年
            //     $strSumStartYm = $this->AddMonths($dtpTaisyouKE, '-23');
            // }
            // '==================================================================
            // '     実績集計データ 作成
            // '==================================================================
            // '------------------------------
            // '03:限界利益
            // '------------------------------
            $strSyukeiKomoku = '03';
            //◆　01:基準
            $result = $this->FrmEvaluationtotal->fnc_InsMokuhyo(CNS_10, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsStaff(CNS_10, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_10, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //07:中古車台数
            $strSyukeiKomoku = '07';
            //◆　01:基準
            $result = $this->FrmEvaluationtotal->fnc_InsMokuhyo(CNS_10, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsStaff(CNS_10, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_10, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //12:保険
            $strSyukeiKomoku = '12';
            //◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsHoken(CNS_10, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //16:サービス入庫
            $strSyukeiKomoku = '16';
            $strNyukoKbn = '60';
            //◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsNyuko(CNS_10, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $strNyukoKbn, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //17:固定費/カバー率
            $strSyukeiKomoku = '17';
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsStaff(CNS_10, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //18:労働分配率
            $strSyukeiKomoku = '18';
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsStaff(CNS_10, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //周辺利益集計データ 作成
            $intKikan = 0;
            if ($intKikanKbn == 0) {
                $intKikan = 6;
            } else {
                $intKikan = 12;
            }

            //02:クレジットＫＢ
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsCredit(CNS_10, $strStartYm, $strStartYm, $prvEndYm, $intKikan, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //04:ﾊﾟｯｸdeﾒﾝﾃ
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsPackdeMente(CNS_10, $strStartYm, $strStartYm, $prvEndYm, $intKikan, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //06:JAF
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsJAF(CNS_10, $strStartYm, $strStartYm, $prvEndYm, $intKikan, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    private function Ins_11($strStartYm, $dtpTaisyouKE)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        //前年実績用
        // $strSumStartYm = '';
        // $strSumEndYm = $this->AddMonths($dtpTaisyouKE, '-12');
        $strSyukeiKomoku = '';
        // $strKomoku = '';

        try {
            $prvEndYm = $dtpTaisyouKE;
            // if ($intKikanKbn == 0) {
            //     //6ヶ月
            //     $strSumStartYm = $this->AddMonths($dtpTaisyouKE, '-17');
            // } else {
            //     //1年
            //     $strSumStartYm = $this->AddMonths($dtpTaisyouKE, '-23');
            // }
            // '==================================================================
            // '     実績集計データ 作成
            // '==================================================================
            // '------------------------------
            // '03:限界利益
            // '------------------------------
            $strSyukeiKomoku = '03';
            //◆　01:基準
            $result = $this->FrmEvaluationtotal->fnc_InsMokuhyo(CNS_11, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsStaff(CNS_11, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_11, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //07:中古車台数
            $strSyukeiKomoku = '07';
            //◆　01:基準
            $result = $this->FrmEvaluationtotal->fnc_InsMokuhyo(CNS_11, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsStaff(CNS_11, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_11, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $result['result'] = TRUE;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    private function Ins_12($strStartYm, $intKikanKbn, $dtpTaisyouKE, $prvKisyuYM)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        //前年実績用
        $strSumStartYm = '';
        $strSumEndYm = $this->AddMonths($dtpTaisyouKE, '-12');
        $strSyukeiKomoku = '';
        $strKomoku = '';
        $intLineNo = 0;

        try {
            $prvEndYm = $dtpTaisyouKE;
            if ($intKikanKbn == 0) {
                //6ヶ月
                $strSumStartYm = $this->AddMonths($dtpTaisyouKE, '-17');
            } else {
                //1年
                $strSumStartYm = $this->AddMonths($dtpTaisyouKE, '-23');
            }
            // '==================================================================
            // '     実績集計データ 作成
            // '==================================================================
            // 20250418 lujunxia upd s
            // '------------------------------
            // '03:限界利益
            // '------------------------------
            // $strSyukeiKomoku = '03';
            // '------------------------------
            // '19:サービス総員当りサービス総限界利益
            // '------------------------------
            $strSyukeiKomoku = '19';
            $intLineNo = 87;
            //'◆　01:基準
            //$result = $this->FrmEvaluationtotal->fnc_InsYosan(CNS_12, $strStartYm, $intKikanKbn, $strSyukeiKomoku, $intLineNo, TRUE, $dtpTaisyouKE, $prvKisyuYM, "");
            // 20250429 lujunxia upd s
            // $result = $this->FrmEvaluationtotal->fnc_InsYosan2(CNS_12, $strStartYm, $strSyukeiKomoku, $intLineNo, $dtpTaisyouKE, $prvKisyuYM);
            $result = $this->FrmEvaluationtotal->fnc_InsYosan3(CNS_12, $strStartYm, $strSyukeiKomoku, $intLineNo, $dtpTaisyouKE, $prvKisyuYM);
            // 20250429 lujunxia upd e
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　02:実績
            $strKomoku = '02';
            //$result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_12, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki2(CNS_12, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, $dtpTaisyouKE);
            // 20250418 lujunxia upd e
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_12, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // 202050418 lujunxia upd s
            // '------------------------------
            // '04:限界利益前年比
            // '------------------------------
            // '------------------------------
            // '20:サービス総員当りサービス総限界利益前年比
            // '------------------------------
            // $strSyukeiKomoku = '04';
            $strSyukeiKomoku = '20';
            $intLineNo = 87;
            //'◆　01:基準
            $strKomoku = '01';
            //$result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_12, $strStartYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki2(CNS_12, $strStartYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　02:実績
            $strKomoku = '02';
            //$result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_12, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki2(CNS_12, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, $dtpTaisyouKE);
            // 202050418 lujunxia upd e
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_12, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //09:整備売上
            $strSyukeiKomoku = '09';
            $intLineNo = 56;
            //◆　01:基準
            $result = $this->FrmEvaluationtotal->fnc_InsYosan(CNS_12, $strStartYm, $intKikanKbn, $strSyukeiKomoku, $intLineNo, TRUE, $dtpTaisyouKE, $prvKisyuYM, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_12, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_12, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //10:整備売上前年比
            $strSyukeiKomoku = '10';
            $intLineNo = 56;
            //◆　01:基準
            $strKomoku = '01';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_12, $strStartYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_12, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_12, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // 20250418 lujunxia upd s
            //18:労働分配率
            // $strSyukeiKomoku = '18';
            //'◆　02:実績
            // $result = $this->FrmEvaluationtotal->fnc_InsBunpai(CNS_12, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $prvEndYm);
            // if (!$result['result']) {
            //     throw new \Exception($result['data']);
            // }
            // '------------------------------
            // '21:サービス総員当り総入庫台数
            // '------------------------------
            $strSyukeiKomoku = '21';
            $intLineNo = 70;
            //'◆　01:基準
            // 20250429 lujunxia upd s
            // $result = $this->FrmEvaluationtotal->fnc_InsYosan2(CNS_12, $strStartYm, $strSyukeiKomoku, $intLineNo, $dtpTaisyouKE, $prvKisyuYM);
            $result = $this->FrmEvaluationtotal->fnc_InsYosan3(CNS_12, $strStartYm, $strSyukeiKomoku, $intLineNo, $dtpTaisyouKE, $prvKisyuYM);
            // 20250429 lujunxia upd e
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki2(CNS_12, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_12, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // 20250418 lujunxia upd e
            //周辺利益集計データ 作成
            $intKikan = 0;
            if ($intKikanKbn == 0) {
                $intKikan = 6;
            } else {
                $intKikan = 12;
            }
            //00:延べ人員
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsJinin(CNS_12, $strStartYm, $strStartYm, $prvEndYm, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //04:ﾊﾟｯｸdeﾒﾝﾃ
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsPackdeMente(CNS_12, $strStartYm, $strStartYm, $prvEndYm, $intKikan, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //05:ﾊﾟｯｸde753
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsPackde753(CNS_12, $strStartYm, $strStartYm, $prvEndYm, $intKikan, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //06:JAF
            //◆　02:実績
            $result = $this->FrmEvaluationtotal->fnc_InsJAF(CNS_12, $strStartYm, $strStartYm, $prvEndYm, $intKikan, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    private function Ins_13($strStartYm, $intKikanKbn, $dtpTaisyouKE, $prvKisyuYM)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        //前年実績用
        // $strSumStartYm = '';
        // $strSumEndYm = $this->AddMonths($dtpTaisyouKE, '-12');
        $strSyukeiKomoku = '';
        $strKomoku = '';
        $intLineNo = 0;

        try {
            $prvEndYm = $dtpTaisyouKE;
            // if ($intKikanKbn == 0) {
            //     //6ヶ月
            //     $strSumStartYm = $this->AddMonths($dtpTaisyouKE, '-17');
            // } else {
            //     //1年
            //     $strSumStartYm = $this->AddMonths($dtpTaisyouKE, '-23');
            // }
            // '==================================================================
            // '     実績集計データ 作成
            // '==================================================================
            // 20250418 lujunxia upd s
            // '------------------------------
            // '03:限界利益
            // '------------------------------
            // $strSyukeiKomoku = '03';
            // $intLineNo = 76;
            // '------------------------------
            // '19:サービス総員当りサービス総限界利益
            // '------------------------------
            $strSyukeiKomoku = '19';
            $intLineNo = 87;
            //'◆　01:基準
            //$result = $this->FrmEvaluationtotal->fnc_InsYosan(CNS_13, $strStartYm, $intKikanKbn, $strSyukeiKomoku, $intLineNo, TRUE, $dtpTaisyouKE, $prvKisyuYM, "");
            // 20250429 lujunxia upd s
            // $result = $this->FrmEvaluationtotal->fnc_InsYosan2(CNS_13, $strStartYm, $strSyukeiKomoku, $intLineNo, $dtpTaisyouKE, $prvKisyuYM);
            $result = $this->FrmEvaluationtotal->fnc_InsYosan3(CNS_13, $strStartYm, $strSyukeiKomoku, $intLineNo, $dtpTaisyouKE, $prvKisyuYM);
            // 20250429 lujunxia upd e
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // $result = $this->FrmEvaluationtotal->fnc_InsYosan(CNS_13, $strStartYm, $intKikanKbn, $strSyukeiKomoku, $intLineNo, TRUE, '212', $dtpTaisyouKE, $prvKisyuYM);
            // if (!$result['result']) {
            //     throw new \Exception($result['data']);
            // }
            //'◆　02:実績
            $strKomoku = '02';
            //$result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_13, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki2(CNS_13, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_13, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, '212');
            // if (!$result['result']) {
            //     throw new \Exception($result['data']);
            // }
            // 20250418 lujunxia upd e
            //◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_13, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    // 20240722 YIN INS S
    private function Ins_15($strStartYm, $intKikanKbn, $dtpTaisyouKE, $prvKisyuYM)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        //前年実績用
        $strSumStartYm = '';
        $strSumEndYm = $this->AddMonths($dtpTaisyouKE, '-12');
        $strSyukeiKomoku = '';
        $strKomoku = '';
        $intLineNo = 0;

        try {
            $prvEndYm = $dtpTaisyouKE;
            if ($intKikanKbn == 0) {
                //6ヶ月
                $strSumStartYm = $this->AddMonths($dtpTaisyouKE, '-17');
            }
            // '==================================================================
            // '     実績集計データ 作成
            // '==================================================================
            // 20250418 lujunxia del s
            // '------------------------------
            // '03:限界利益
            // '------------------------------
            // $strSyukeiKomoku = '03';
            // $intLineNo = 87;
            //'◆　01:基準
            //20240829 lhb del s
            // $result = $this->FrmEvaluationtotal->fnc_InsYosan(CNS_15, $strStartYm, $intKikanKbn, $strSyukeiKomoku, $intLineNo, TRUE, $dtpTaisyouKE, $prvKisyuYM, "");
            // if (!$result['result']) {
            // 	throw new \Exception($result['data']);
            // }
            //20240829 lhb del e
            //'◆　02:実績
            // $strKomoku = '02';
            // $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_15, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            // if (!$result['result']) {
            //     throw new \Exception($result['data']);
            // }
            //◆　03:達成率
            //20240829 lhb del s
            // $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_15, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            // if (!$result['result']) {
            // 	throw new \Exception($result['data']);
            // }
            //20240829 lhb del e
            // '------------------------------
            // '04:限界利益前年比
            // '------------------------------
            // $strSyukeiKomoku = '04';
            // $intLineNo = 87;
            //'◆　01:基準
            // $strKomoku = '01';
            // $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_15, $strStartYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            // if (!$result['result']) {
            //     throw new \Exception($result['data']);
            // }
            //'◆　02:実績
            // $strKomoku = '02';
            // $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_15, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            // if (!$result['result']) {
            //     throw new \Exception($result['data']);
            // }
            //'◆　03:達成率
            // $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_15, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            // if (!$result['result']) {
            //     throw new \Exception($result['data']);
            // }

            //18:労働分配率
            // $strSyukeiKomoku = '18';
            //'◆　02:実績
            // $result = $this->FrmEvaluationtotal->fnc_InsBunpai(CNS_15, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $prvEndYm);
            // if (!$result['result']) {
            //     throw new \Exception($result['data']);
            // }
            // 20250418 lujunxia del e
            // 20250418 lujunxia ins s
            // '------------------------------
            // '19:サービス総員当りサービス総限界利益
            // '------------------------------
            $strSyukeiKomoku = '19';
            $intLineNo = 87;
            //'◆　01:基準
            $result = $this->FrmEvaluationtotal->fnc_InsYosan2(CNS_15, $strStartYm, $strSyukeiKomoku, $intLineNo, $dtpTaisyouKE, $prvKisyuYM);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki2(CNS_15, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率は以前と同じで空白

            // '------------------------------
            // '20:サービス総員当りサービス総限界利益前年比
            // '------------------------------
            $strSyukeiKomoku = '20';
            $intLineNo = 87;
            //'◆　01:基準
            $strKomoku = '01';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki2(CNS_15, $strStartYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki2(CNS_15, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_15, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // '------------------------------
            // '21:サービス総員当り総入庫台数
            // '------------------------------
            $strSyukeiKomoku = '21';
            $intLineNo = 70;
            //'◆　01:基準
            $result = $this->FrmEvaluationtotal->fnc_InsYosan2(CNS_15, $strStartYm, $strSyukeiKomoku, $intLineNo, $dtpTaisyouKE, $prvKisyuYM);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki2(CNS_15, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率は以前と同じで空白
            // '------------------------------
            // '22:有償粗利
            // '------------------------------
            $strSyukeiKomoku = '22';
            $intLineNo = 69;
            //'◆　01:基準
            $result = $this->FrmEvaluationtotal->fnc_InsYosan(CNS_15, $strStartYm, $intKikanKbn, $strSyukeiKomoku, $intLineNo, TRUE, $dtpTaisyouKE, $prvKisyuYM, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_15, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率は以前と同じで空白

            // '------------------------------
            // '23:有償粗利前年比
            // '------------------------------
            $strSyukeiKomoku = '23';
            $intLineNo = 69;
            //'◆　01:基準
            $strKomoku = '01';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_15, $strStartYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_15, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_15, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // 20250418 lujunxia ins e

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }
    private function Ins_16($strStartYm, $intKikanKbn, $dtpTaisyouKE, $prvKisyuYM)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        //前年実績用
        $strSyukeiKomoku = '';
        $strKomoku = '';
        $intLineNo = 0;

        try {
            $prvEndYm = $dtpTaisyouKE;
            // '==================================================================
            // '     実績集計データ 作成
            // '==================================================================
            // 20250418 lujunxia upd s
            // '------------------------------
            // '03:限界利益
            // '------------------------------
            // $strSyukeiKomoku = '03';
            //20240829 lhb upd s
            // $intLineNo = 76;
            // $intLineNo = 75;
            //20240829 lhb upd e
            //'◆　02:実績
            // $strKomoku = '02';
            // $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_16, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            // if (!$result['result']) {
            //     throw new \Exception($result['data']);
            // }
            // $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_16, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, '212');
            // if (!$result['result']) {
            //     throw new \Exception($result['data']);
            // }
            // '------------------------------
            // '19:サービス総員当りサービス総限界利益
            // '------------------------------
            $strSyukeiKomoku = '19';
            $intLineNo = 87;
            //'◆　01:基準
            $result = $this->FrmEvaluationtotal->fnc_InsYosan2(CNS_16, $strStartYm, $strSyukeiKomoku, $intLineNo, $dtpTaisyouKE, $prvKisyuYM);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki2(CNS_16, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率は以前と同じで空白
            // 20250418 lujunxia upd e

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    private function Ins_17($strStartYm, $intKikanKbn, $dtpTaisyouKE, $prvKisyuYM)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        //前年実績用
        $strSumStartYm = '';
        $strSumEndYm = $this->AddMonths($dtpTaisyouKE, '-12');
        $strSyukeiKomoku = '';
        $strKomoku = '';
        $intLineNo = 0;

        try {
            $prvEndYm = $dtpTaisyouKE;
            if ($intKikanKbn == 0) {
                //6ヶ月
                $strSumStartYm = $this->AddMonths($dtpTaisyouKE, '-17');
            }
            // '==================================================================
            // '     実績集計データ 作成
            // '==================================================================
            // 20250418 lujunxia upd s
            // '------------------------------
            // '01:経常利益
            // '------------------------------
            // $strSyukeiKomoku = '01';
            // $intLineNo = 114;
            //'◆　01:基準
            //20240829 lhb del s
            // $result = $this->FrmEvaluationtotal->fnc_InsYosan(CNS_17, $strStartYm, $intKikanKbn, $strSyukeiKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "", $prvKisyuYM);
            // if (!$result['result']) {
            // 	throw new \Exception($result['data']);
            // }
            //20240829 lhb del e
            // '------------------------------
            // '19:サービス総員当りサービス総限界利益
            // '------------------------------
            $strSyukeiKomoku = '19';
            $intLineNo = 87;
            //'◆　01:基準
            $result = $this->FrmEvaluationtotal->fnc_InsYosan2(CNS_17, $strStartYm, $strSyukeiKomoku, $intLineNo, $dtpTaisyouKE, $prvKisyuYM);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　02:実績
            $strKomoku = '02';
            // $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_17, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki2(CNS_17, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率は以前と同じで空白

            //20240829 lhb del s
            // $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_17, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            // if (!$result['result']) {
            // 	throw new \Exception($result['data']);
            // }
            //20240829 lhb del e
            // '------------------------------
            // '02:経常利益前年比
            // '------------------------------
            // $strSyukeiKomoku = '02';
            // $intLineNo = 114;
            // '------------------------------
            // '20:サービス総員当りサービス総限界利益前年比
            // '------------------------------
            $strSyukeiKomoku = '20';
            $intLineNo = 87;
            //'◆　01:基準
            $strKomoku = '01';
            // $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_17, $strStartYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki2(CNS_17, $strStartYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　02:実績
            $strKomoku = '02';
            // $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_17, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki2(CNS_17, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, $dtpTaisyouKE);
            // 20250418 lujunxia upd e
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_17, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // 20250418 lujunxia ins s
            // '------------------------------
            // '22:有償粗利
            // '------------------------------
            $strSyukeiKomoku = '22';
            $intLineNo = 69;
            //'◆　01:基準
            $result = $this->FrmEvaluationtotal->fnc_InsYosan(CNS_17, $strStartYm, $intKikanKbn, $strSyukeiKomoku, $intLineNo, TRUE, $dtpTaisyouKE, $prvKisyuYM, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_17, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　03:達成率は以前と同じで空白

            // '------------------------------
            // '23:有償粗利前年比
            // '------------------------------
            $strSyukeiKomoku = '23';
            $intLineNo = 69;
            //'◆　01:基準
            $strKomoku = '01';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_17, $strStartYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_17, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　03:達成率
            $result = $this->FrmEvaluationtotal->fnc_InsTassei_Ritu(CNS_17, $strStartYm, $strSyukeiKomoku, $prvEndYm);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // 20250418 lujunxia ins e
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    private function Ins_18($strStartYm, $dtpTaisyouKE)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        $strSyukeiKomoku = '';
        $strKomoku = '';
        $intLineNo = 0;

        try {
            $prvEndYm = $dtpTaisyouKE;
            // '==================================================================
            // '     実績集計データ 作成
            // '==================================================================
            // '------------------------------
            // '03:限界利益
            // '------------------------------
            $strSyukeiKomoku = '03';
            $intLineNo = 76;
            //'◆　02:実績
            $strKomoku = '02';
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_18, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, "");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $result = $this->FrmEvaluationtotal->fnc_InsJisseki(CNS_18, $strStartYm, $strStartYm, $prvEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, TRUE, $dtpTaisyouKE, '212');
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }
    // 20240722 YIN INS E

    //順位・達成度作成
    private function fncJunni_Create($dtpTaisyouKE, $cboKoukaType, $prv6Month, $prv1Yere, $rdoExct_Grop, $rdoExct_Type)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );

        try {
            //集計データ取得
            $DT = $this->FrmEvaluationtotal->SelJKKOUKA_SYAIN_SQL($cboKoukaType, $dtpTaisyouKE);
            if ($DT['result'] == FALSE) {
                throw new \Exception($DT['data']);
            }
            //対象ﾃﾞｰﾀがない場合、ﾒｯｾｰｼﾞを表示し処理を抜ける
            if ($DT['row'] == 0) {
                throw new \Exception('I0001');
            }

            // ---------------------------------------------------------------------
            // 集計データ作成
            // ---------------------------------------------------------------------
            // 考課表パターン='07':間接管理職 '14':間接スタッフの実績は作成しない1'))
            if ($cboKoukaType == '' || $cboKoukaType == '01') {
                //◆◆◆　店長　◆◆◆
                if ($prv6Month !== '') {
                    $Ins_01_Ranking = $this->Ins_01_Ranking($prv6Month, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type);
                    if ($Ins_01_Ranking['result'] == FALSE) {
                        throw new \Exception($Ins_01_Ranking['error']);
                    }

                }
                if ($prv1Yere !== '') {
                    $Ins_01_Ranking = $this->Ins_01_Ranking($prv1Yere, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type);
                    if ($Ins_01_Ranking['result'] == FALSE) {
                        throw new \Exception($Ins_01_Ranking['error']);
                    }

                }
            }
            if ($cboKoukaType == '' || $cboKoukaType == '02') {
                //◆◆◆　販売課長　◆◆◆
                if ($prv6Month !== '') {
                    $Ins_02_Ranking = $this->Ins_02_Ranking($prv6Month, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type);
                    if ($Ins_02_Ranking['result'] == FALSE) {
                        throw new \Exception($Ins_02_Ranking['error']);
                    }

                }
                if ($prv1Yere !== '') {
                    $Ins_02_Ranking = $this->Ins_02_Ranking($prv1Yere, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type);
                    if ($Ins_02_Ranking['result'] == FALSE) {
                        throw new \Exception($Ins_02_Ranking['error']);
                    }

                }
            }
            if ($cboKoukaType == '' || $cboKoukaType == '03') {
                //◆◆◆　新車業販管理職　◆◆◆
                if ($prv6Month !== '') {
                    $Ins_03_Ranking = $this->Ins_03_Ranking($prv6Month, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type);
                    if ($Ins_03_Ranking['result'] == FALSE) {
                        throw new \Exception($Ins_03_Ranking['error']);
                    }

                }
                if ($prv1Yere !== '') {
                    $Ins_03_Ranking = $this->Ins_03_Ranking($prv1Yere, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type);
                    if ($Ins_03_Ranking['result'] == FALSE) {
                        throw new \Exception($Ins_03_Ranking['error']);
                    }

                }
            }

            if ($cboKoukaType == '' || $cboKoukaType == '04') {
                //◆◆◆　中古車直販管理職　◆◆◆
                if ($prv6Month !== '') {
                    $Ins_04_Ranking = $this->Ins_04_Ranking($prv6Month, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type);
                    if ($Ins_04_Ranking['result'] == FALSE) {
                        throw new \Exception($Ins_04_Ranking['error']);
                    }

                }
                if ($prv1Yere !== '') {
                    $Ins_04_Ranking = $this->Ins_04_Ranking($prv1Yere, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type);
                    if ($Ins_04_Ranking['result'] == FALSE) {
                        throw new \Exception($Ins_04_Ranking['error']);
                    }

                }
            }

            if ($cboKoukaType == '' || $cboKoukaType == '05') {
                //◆◆◆　中古車業販管理職　◆◆◆
                if ($prv6Month !== '') {
                    $Ins_05_Ranking = $this->Ins_05_Ranking($prv6Month, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type);
                    if ($Ins_05_Ranking['result'] == FALSE) {
                        throw new \Exception($Ins_05_Ranking['error']);
                    }

                }
                if ($prv1Yere !== '') {
                    $Ins_05_Ranking = $this->Ins_05_Ranking($prv1Yere, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type);
                    if ($Ins_05_Ranking['result'] == FALSE) {
                        throw new \Exception($Ins_05_Ranking['error']);
                    }

                }
            }
            if ($cboKoukaType == '' || $cboKoukaType == '06') {
                //◆◆◆　サービス管理職　◆◆◆
                if ($prv6Month !== '') {
                    $Ins_06_Ranking = $this->Ins_06_Ranking($prv6Month, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type);
                    if ($Ins_06_Ranking['result'] == FALSE) {
                        throw new \Exception($Ins_06_Ranking['error']);
                    }

                }
                if ($prv1Yere !== '') {
                    $Ins_06_Ranking = $this->Ins_06_Ranking($prv1Yere, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type);
                    if ($Ins_06_Ranking['result'] == FALSE) {
                        throw new \Exception($Ins_06_Ranking['error']);
                    }

                }
            }

            if ($cboKoukaType == '' || $cboKoukaType == '08') {
                //◆◆◆　新車営業職　◆◆◆
                if ($prv6Month !== '') {
                    $Ins_08_Ranking = $this->Ins_08_Ranking($prv6Month, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type);
                    if ($Ins_08_Ranking['result'] == FALSE) {
                        throw new \Exception($Ins_08_Ranking['error']);
                    }

                }
                if ($prv1Yere !== '') {
                    $Ins_08_Ranking = $this->Ins_08_Ranking($prv1Yere, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type);
                    if ($Ins_08_Ranking['result'] == FALSE) {
                        throw new \Exception($Ins_08_Ranking['error']);
                    }

                }
            }

            if ($cboKoukaType == '' || $cboKoukaType == '09') {
                //◆◆◆　新車業販営業職　◆◆◆
                if ($prv6Month !== '') {
                    $Ins_09_Ranking = $this->Ins_09_Ranking($prv6Month, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type);
                    if ($Ins_09_Ranking['result'] == FALSE) {
                        throw new \Exception($Ins_09_Ranking['error']);
                    }

                }
                if ($prv1Yere !== '') {
                    $Ins_09_Ranking = $this->Ins_09_Ranking($prv1Yere, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type);
                    if ($Ins_09_Ranking['result'] == FALSE) {
                        throw new \Exception($Ins_09_Ranking['error']);
                    }

                }
            }

            if ($cboKoukaType == '' || $cboKoukaType == '10') {
                //◆◆◆　中古車直販営業職　◆◆◆
                if ($prv6Month !== '') {
                    $Ins_10_Ranking = $this->Ins_10_Ranking($prv6Month, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type);
                    if ($Ins_10_Ranking['result'] == FALSE) {
                        throw new \Exception($Ins_10_Ranking['error']);
                    }

                }
                if ($prv1Yere !== '') {
                    $Ins_10_Ranking = $this->Ins_10_Ranking($prv1Yere, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type);
                    if ($Ins_10_Ranking['result'] == FALSE) {
                        throw new \Exception($Ins_10_Ranking['error']);
                    }

                }
            }

            if ($cboKoukaType == '' || $cboKoukaType == '11') {
                //◆◆◆　中古車業販営業職　◆◆◆
                if ($prv6Month !== '') {
                    $Ins_11_Ranking = $this->Ins_11_Ranking($prv6Month, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type);
                    if ($Ins_11_Ranking['result'] == FALSE) {
                        throw new \Exception($Ins_11_Ranking['error']);
                    }

                }
                if ($prv1Yere !== '') {
                    $Ins_11_Ranking = $this->Ins_11_Ranking($prv1Yere, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type);
                    if ($Ins_11_Ranking['result'] == FALSE) {
                        throw new \Exception($Ins_11_Ranking['error']);
                    }

                }
            }
            if ($cboKoukaType == '' || $cboKoukaType == '12') {
                //◆◆◆　サービス・アドバイザー　◆◆◆
                if ($prv6Month !== '') {
                    $Ins_12_Ranking = $this->Ins_12_Ranking($prv6Month, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type);
                    if ($Ins_12_Ranking['result'] == FALSE) {
                        throw new \Exception($Ins_12_Ranking['error']);
                    }

                }
                if ($prv1Yere !== '') {
                    $Ins_12_Ranking = $this->Ins_12_Ranking($prv1Yere, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type);
                    if ($Ins_12_Ranking['result'] == FALSE) {
                        throw new \Exception($Ins_12_Ranking['error']);
                    }

                }
            }
            if ($cboKoukaType == '' || $cboKoukaType == '13') {
                //◆◆◆　サービス・エンジニア　◆◆◆
                if ($prv6Month !== '') {
                    $Ins_13_Ranking = $this->Ins_13_Ranking($prv6Month, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type);
                    if ($Ins_13_Ranking['result'] == FALSE) {
                        throw new \Exception($Ins_13_Ranking['error']);
                    }

                }
                if ($prv1Yere !== '') {
                    $Ins_13_Ranking = $this->Ins_13_Ranking($prv1Yere, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type);
                    if ($Ins_13_Ranking['result'] == FALSE) {
                        throw new \Exception($Ins_13_Ranking['error']);
                    }

                }
            }
            // 20240722 YIN INS S
            if ($cboKoukaType == '' || $cboKoukaType == '15') {
                //◆◆◆　BP・アドバイザー　◆◆◆
                if ($prv6Month !== '') {
                    $Ins_15_Ranking = $this->Ins_15_Ranking($prv6Month, $dtpTaisyouKE, $rdoExct_Type);
                    if ($Ins_15_Ranking['result'] == FALSE) {
                        throw new \Exception($Ins_15_Ranking['error']);
                    }

                }
            }
            //20240903 lhb del s
            // if ($cboKoukaType == '' || $cboKoukaType == '17') {
            //     //◆◆◆　BP管理職　◆◆◆
            //     if ($prv6Month !== '') {
            //         $Ins_17_Ranking = $this->Ins_17_Ranking($prv6Month, 0, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type);
            //         if ($Ins_17_Ranking['result'] == FALSE) {
            //             throw new \Exception($Ins_17_Ranking['error']);
            //         }

            //     }
            // }
            //20240903 lhb del e
            // 20240722 YIN INS E
            $result['result'] = TRUE;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    private function Ins_01_Ranking($strStartYm, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        //前年実績用
        $strSyukeiKomoku = '';
        try {
            // '==================================================================
            // '     実績集計データ 作成
            // '==================================================================
            //◆　07:総数
            //固定費/ｶﾊﾞｰ率
            $strSyukeiKomoku = '17';
            $result = $this->FrmEvaluationtotal->fnc_InsTotal(CNS_01, $strStartYm, $strSyukeiKomoku, $dtpTaisyouKE, $rdoExct_Grop);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　05:順位
            $result = $this->FrmEvaluationtotal->fnc_InsRank(CNS_01, $strStartYm, $dtpTaisyouKE, $rdoExct_Type);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　06:達成度
            $result = $this->FrmEvaluationtotal->fnc_InsTasseido(CNS_01, $strStartYm, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // '------------------------------
            // ' 周辺利益集計データ 作成
            // '------------------------------
            //◆　07:総数
            $result = $this->FrmEvaluationtotal->fnc_InsTotal_Syuhen(CNS_01, $strStartYm, $dtpTaisyouKE, $rdoExct_Grop);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　05:順位
            $result = $this->FrmEvaluationtotal->fnc_InsRank_Syuhen(CNS_01, $strStartYm, $dtpTaisyouKE, $rdoExct_Type);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　04:指数
            $result = $this->FrmEvaluationtotal->fnc_InsShisu_Syuhen(CNS_01, $strStartYm, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　05:順位(指数の順位)
            $result = $this->FrmEvaluationtotal->fnc_InsRank_Syuhen(CNS_01, $strStartYm, $dtpTaisyouKE, $rdoExct_Type, TRUE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　06:達成度
            $result = $this->FrmEvaluationtotal->fnc_InsTasseido_Syuhen(CNS_01, $strStartYm, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    private function Ins_02_Ranking($strStartYm, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        //前年実績用
        $strSyukeiKomoku = '';
        try {
            // '==================================================================
            // '     実績集計データ 作成
            // '==================================================================
            //◆　07:総数
            //固定費/ｶﾊﾞｰ率
            $strSyukeiKomoku = '17';
            $result = $this->FrmEvaluationtotal->fnc_InsTotal(CNS_02, $strStartYm, $strSyukeiKomoku, $dtpTaisyouKE, $rdoExct_Grop);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　05:順位
            $result = $this->FrmEvaluationtotal->fnc_InsRank(CNS_02, $strStartYm, $dtpTaisyouKE, $rdoExct_Type);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　06:達成度
            $result = $this->FrmEvaluationtotal->fnc_InsTasseido(CNS_02, $strStartYm, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // '------------------------------
            // ' 周辺利益集計データ 作成
            // '------------------------------
            //◆　07:総数
            $result = $this->FrmEvaluationtotal->fnc_InsTotal_Syuhen(CNS_02, $strStartYm, $dtpTaisyouKE, $rdoExct_Grop);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　05:順位
            $result = $this->FrmEvaluationtotal->fnc_InsRank_Syuhen(CNS_02, $strStartYm, $dtpTaisyouKE, $rdoExct_Type);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　04:指数
            $result = $this->FrmEvaluationtotal->fnc_InsShisu_Syuhen(CNS_02, $strStartYm, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　05:順位(指数の順位)
            $result = $this->FrmEvaluationtotal->fnc_InsRank_Syuhen(CNS_02, $strStartYm, $dtpTaisyouKE, $rdoExct_Type, TRUE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　06:達成度
            $result = $this->FrmEvaluationtotal->fnc_InsTasseido_Syuhen(CNS_02, $strStartYm, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    private function Ins_03_Ranking($strStartYm, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        //前年実績用
        $strSyukeiKomoku = '';
        try {
            // '==================================================================
            // '     実績集計データ 作成
            // '==================================================================
            //◆　07:総数
            //固定費/ｶﾊﾞｰ率
            $strSyukeiKomoku = '17';
            $result = $this->FrmEvaluationtotal->fnc_InsTotal(CNS_03, $strStartYm, $strSyukeiKomoku, $dtpTaisyouKE, $rdoExct_Grop);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　05:順位
            $result = $this->FrmEvaluationtotal->fnc_InsRank(CNS_03, $strStartYm, $dtpTaisyouKE, $rdoExct_Type);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　06:達成度
            $result = $this->FrmEvaluationtotal->fnc_InsTasseido(CNS_03, $strStartYm, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    private function Ins_04_Ranking($strStartYm, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        //前年実績用
        $strSyukeiKomoku = '';
        try {
            // '==================================================================
            // '     実績集計データ 作成
            // '==================================================================
            //◆　07:総数
            //固定費/ｶﾊﾞｰ率
            $strSyukeiKomoku = '17';
            $result = $this->FrmEvaluationtotal->fnc_InsTotal(CNS_04, $strStartYm, $strSyukeiKomoku, $dtpTaisyouKE, $rdoExct_Grop);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　05:順位
            $result = $this->FrmEvaluationtotal->fnc_InsRank(CNS_04, $strStartYm, $dtpTaisyouKE, $rdoExct_Type);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　06:達成度
            $result = $this->FrmEvaluationtotal->fnc_InsTasseido(CNS_04, $strStartYm, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    private function Ins_05_Ranking($strStartYm, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        //前年実績用
        $strSyukeiKomoku = '';
        try {
            // '==================================================================
            // '     実績集計データ 作成
            // '==================================================================
            //◆　07:総数
            //固定費/ｶﾊﾞｰ率
            $strSyukeiKomoku = '17';
            $result = $this->FrmEvaluationtotal->fnc_InsTotal(CNS_05, $strStartYm, $strSyukeiKomoku, $dtpTaisyouKE, $rdoExct_Grop);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　05:順位
            $result = $this->FrmEvaluationtotal->fnc_InsRank(CNS_05, $strStartYm, $dtpTaisyouKE, $rdoExct_Type);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　06:達成度
            $result = $this->FrmEvaluationtotal->fnc_InsTasseido(CNS_05, $strStartYm, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    private function Ins_06_Ranking($strStartYm, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        //前年実績用
        $strSyukeiKomoku = '';
        try {
            // '==================================================================
            // '     実績集計データ 作成
            // '==================================================================
            //◆　07:総数
            //固定費/ｶﾊﾞｰ率
            $strSyukeiKomoku = '17';
            $result = $this->FrmEvaluationtotal->fnc_InsTotal(CNS_06, $strStartYm, $strSyukeiKomoku, $dtpTaisyouKE, $rdoExct_Grop);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　05:順位
            $result = $this->FrmEvaluationtotal->fnc_InsRank(CNS_06, $strStartYm, $dtpTaisyouKE, $rdoExct_Type);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　06:達成度
            $result = $this->FrmEvaluationtotal->fnc_InsTasseido(CNS_06, $strStartYm, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // '------------------------------
            // ' 周辺利益集計データ 作成
            // '------------------------------
            //◆　07:総数
            $result = $this->FrmEvaluationtotal->fnc_InsTotal_Syuhen(CNS_06, $strStartYm, $dtpTaisyouKE, $rdoExct_Grop);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　05:順位
            $result = $this->FrmEvaluationtotal->fnc_InsRank_Syuhen(CNS_06, $strStartYm, $dtpTaisyouKE, $rdoExct_Type);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　04:指数
            $result = $this->FrmEvaluationtotal->fnc_InsShisu_Syuhen(CNS_06, $strStartYm, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　05:順位(指数の順位)
            $result = $this->FrmEvaluationtotal->fnc_InsRank_Syuhen(CNS_06, $strStartYm, $dtpTaisyouKE, $rdoExct_Type, TRUE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　06:達成度
            $result = $this->FrmEvaluationtotal->fnc_InsTasseido_Syuhen(CNS_06, $strStartYm, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    private function Ins_08_Ranking($strStartYm, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        //前年実績用
        $strSyukeiKomoku = '';
        try {
            // '==================================================================
            // '     実績集計データ 作成
            // '==================================================================
            //◆　07:総数
            //新車台数
            $strSyukeiKomoku = '05';
            $result = $this->FrmEvaluationtotal->fnc_InsTotal(CNS_08, $strStartYm, $strSyukeiKomoku, $dtpTaisyouKE, $rdoExct_Grop);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //固定費/ｶﾊﾞｰ率
            $strSyukeiKomoku = '17';
            $result = $this->FrmEvaluationtotal->fnc_InsTotal(CNS_08, $strStartYm, $strSyukeiKomoku, $dtpTaisyouKE, $rdoExct_Grop);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　05:順位
            $result = $this->FrmEvaluationtotal->fnc_InsRank(CNS_08, $strStartYm, $dtpTaisyouKE, $rdoExct_Type, TRUE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　06:達成度
            $result = $this->FrmEvaluationtotal->fnc_InsTasseido(CNS_08, $strStartYm, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // '------------------------------
            // ' 周辺利益集計データ 作成
            // '------------------------------
            //◆　07:総数
            $result = $this->FrmEvaluationtotal->fnc_InsTotal_Syuhen(CNS_08, $strStartYm, $dtpTaisyouKE, $rdoExct_Grop);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　05:順位
            $result = $this->FrmEvaluationtotal->fnc_InsRank_Syuhen(CNS_08, $strStartYm, $dtpTaisyouKE, $rdoExct_Type);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　04:指数
            $result = $this->FrmEvaluationtotal->fnc_InsShisu_Syuhen(CNS_08, $strStartYm, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　05:順位(指数の順位)
            $result = $this->FrmEvaluationtotal->fnc_InsRank_Syuhen(CNS_08, $strStartYm, $dtpTaisyouKE, $rdoExct_Type, TRUE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　06:達成度
            $result = $this->FrmEvaluationtotal->fnc_InsTasseido_Syuhen(CNS_08, $strStartYm, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    private function Ins_09_Ranking($strStartYm, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        //前年実績用
        $strSyukeiKomoku = '';
        try {
            // '==================================================================
            // '     実績集計データ 作成
            // '==================================================================
            //◆　07:総数
            //新車台数
            $strSyukeiKomoku = '05';
            $result = $this->FrmEvaluationtotal->fnc_InsTotal(CNS_09, $strStartYm, $strSyukeiKomoku, $dtpTaisyouKE, $rdoExct_Grop);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //固定費/ｶﾊﾞｰ率
            $strSyukeiKomoku = '17';
            $result = $this->FrmEvaluationtotal->fnc_InsTotal(CNS_09, $strStartYm, $strSyukeiKomoku, $dtpTaisyouKE, $rdoExct_Grop);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　05:順位
            $result = $this->FrmEvaluationtotal->fnc_InsRank(CNS_09, $strStartYm, $dtpTaisyouKE, $rdoExct_Type, True);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　06:達成度
            $result = $this->FrmEvaluationtotal->fnc_InsTasseido(CNS_09, $strStartYm, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    private function Ins_10_Ranking($strStartYm, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        //前年実績用
        $strSyukeiKomoku = '';
        try {
            // '==================================================================
            // '     実績集計データ 作成
            // '==================================================================
            //◆　07:総数
            //中古車台数
            $strSyukeiKomoku = '07';
            $result = $this->FrmEvaluationtotal->fnc_InsTotal(CNS_10, $strStartYm, $strSyukeiKomoku, $dtpTaisyouKE, $rdoExct_Grop);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //固定費/ｶﾊﾞｰ率
            $strSyukeiKomoku = '17';
            $result = $this->FrmEvaluationtotal->fnc_InsTotal(CNS_10, $strStartYm, $strSyukeiKomoku, $dtpTaisyouKE, $rdoExct_Grop);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　05:順位
            $result = $this->FrmEvaluationtotal->fnc_InsRank(CNS_10, $strStartYm, $dtpTaisyouKE, $rdoExct_Type, TRUE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　06:達成度
            $result = $this->FrmEvaluationtotal->fnc_InsTasseido(CNS_10, $strStartYm, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // '------------------------------
            // ' 周辺利益集計データ 作成
            // '------------------------------
            //◆　07:総数
            $result = $this->FrmEvaluationtotal->fnc_InsTotal_Syuhen(CNS_10, $strStartYm, $dtpTaisyouKE, $rdoExct_Grop);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　05:順位
            $result = $this->FrmEvaluationtotal->fnc_InsRank_Syuhen(CNS_10, $strStartYm, $dtpTaisyouKE, $rdoExct_Type);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　04:指数
            $result = $this->FrmEvaluationtotal->fnc_InsShisu_Syuhen(CNS_10, $strStartYm, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　05:順位(指数の順位)
            $result = $this->FrmEvaluationtotal->fnc_InsRank_Syuhen(CNS_10, $strStartYm, $dtpTaisyouKE, $rdoExct_Type, TRUE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　06:達成度
            $result = $this->FrmEvaluationtotal->fnc_InsTasseido_Syuhen(CNS_10, $strStartYm, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    private function Ins_11_Ranking($strStartYm, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        //前年実績用
        $strSyukeiKomoku = '';
        try {
            // '==================================================================
            // '     実績集計データ 作成
            // '==================================================================
            //◆　07:総数
            //中古車台数
            $strSyukeiKomoku = '07';
            $result = $this->FrmEvaluationtotal->fnc_InsTotal(CNS_11, $strStartYm, $strSyukeiKomoku, $dtpTaisyouKE, $rdoExct_Grop);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //固定費/ｶﾊﾞｰ率
            $strSyukeiKomoku = '17';
            $result = $this->FrmEvaluationtotal->fnc_InsTotal(CNS_11, $strStartYm, $strSyukeiKomoku, $dtpTaisyouKE, $rdoExct_Grop);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　05:順位
            $result = $this->FrmEvaluationtotal->fnc_InsRank(CNS_11, $strStartYm, $dtpTaisyouKE, $rdoExct_Type);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　06:達成度
            $result = $this->FrmEvaluationtotal->fnc_InsTasseido(CNS_11, $strStartYm, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    private function Ins_12_Ranking($strStartYm, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        //前年実績用
        $strSyukeiKomoku = '';
        try {
            // '==================================================================
            // '     実績集計データ 作成
            // '==================================================================

            //◆　07:総数
            //固定費/ｶﾊﾞｰ率
            $strSyukeiKomoku = '17';
            $result = $this->FrmEvaluationtotal->fnc_InsTotal(CNS_12, $strStartYm, $strSyukeiKomoku, $dtpTaisyouKE, $rdoExct_Grop);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　05:順位
            $result = $this->FrmEvaluationtotal->fnc_InsRank(CNS_12, $strStartYm, $dtpTaisyouKE, $rdoExct_Type);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'--- 労働分配率 ---
            $result = $this->FrmEvaluationtotal->fnc_InsRank(CNS_12, $strStartYm, $dtpTaisyouKE, $rdoExct_Type, TRUE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　06:達成度
            $result = $this->FrmEvaluationtotal->fnc_InsTasseido(CNS_12, $strStartYm, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // '------------------------------
            // ' 周辺利益集計データ 作成
            // '------------------------------
            //◆　07:総数
            $result = $this->FrmEvaluationtotal->fnc_InsTotal_Syuhen(CNS_12, $strStartYm, $dtpTaisyouKE, $rdoExct_Grop);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　05:順位
            $result = $this->FrmEvaluationtotal->fnc_InsRank_Syuhen(CNS_12, $strStartYm, $dtpTaisyouKE, $rdoExct_Type);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　04:指数
            $result = $this->FrmEvaluationtotal->fnc_InsShisu_Syuhen(CNS_12, $strStartYm, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　05:順位(指数の順位)
            $result = $this->FrmEvaluationtotal->fnc_InsRank_Syuhen(CNS_12, $strStartYm, $dtpTaisyouKE, $rdoExct_Type, TRUE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　06:達成度
            $result = $this->FrmEvaluationtotal->fnc_InsTasseido_Syuhen(CNS_12, $strStartYm, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    private function Ins_13_Ranking($strStartYm, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        //前年実績用
        $strSyukeiKomoku = '';
        try {
            // '==================================================================
            // '     実績集計データ 作成
            // '==================================================================
            //◆　07:総数
            //固定費/ｶﾊﾞｰ率
            $strSyukeiKomoku = '17';
            $result = $this->FrmEvaluationtotal->fnc_InsTotal(CNS_13, $strStartYm, $strSyukeiKomoku, $dtpTaisyouKE, $rdoExct_Grop);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //'◆　05:順位
            $result = $this->FrmEvaluationtotal->fnc_InsRank(CNS_13, $strStartYm, $dtpTaisyouKE, $rdoExct_Type);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　06:達成度
            $result = $this->FrmEvaluationtotal->fnc_InsTasseido(CNS_13, $strStartYm, $dtpTaisyouKE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    // 20240722 YIN INS S
    private function Ins_15_Ranking($strStartYm, $dtpTaisyouKE, $rdoExct_Type)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        //前年実績用
        // $strSyukeiKomoku = '';
        try {
            // '==================================================================
            // '     実績集計データ 作成
            // '==================================================================

            //◆　07:総数
            //固定費/ｶﾊﾞｰ率
            // $strSyukeiKomoku = '17';
            //202040829 lhb del s
            // $result = $this->FrmEvaluationtotal->fnc_InsTotal(CNS_15, $strStartYm, $strSyukeiKomoku, $dtpTaisyouKE, $rdoExct_Grop);
            // if (!$result['result']) {
            // 	throw new \Exception($result['data']);
            // }
            //202040829 lhb del e
            //'◆　05:順位
            $result = $this->FrmEvaluationtotal->fnc_InsRank(CNS_15, $strStartYm, $dtpTaisyouKE, $rdoExct_Type);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //◆　06:達成度
            //202040829 lhb del s
            // $result = $this->FrmEvaluationtotal->fnc_InsTasseido(CNS_15, $strStartYm, $dtpTaisyouKE);
            // if (!$result['result']) {
            // 	throw new \Exception($result['data']);
            // }
            //202040829 lhb del e
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    //20240903 lhb del s
    // private function Ins_17_Ranking($strStartYm, $intKikanKbn, $dtpTaisyouKE, $rdoExct_Grop, $rdoExct_Type)
    // {
    //     $result = array(
    //         'result' => FALSE,
    //         'error' => ''
    //     );
    //     //前年実績用
    //     $strSyukeiKomoku = '';
    //     try {
    //         // '==================================================================
    //         // '     実績集計データ 作成
    //         // '==================================================================

    //         //◆　07:総数
    //         //固定費/ｶﾊﾞｰ率
    //         $strSyukeiKomoku = '17';
    //         $result = $this->FrmEvaluationtotal->fnc_InsTotal(CNS_17, $strStartYm, $strSyukeiKomoku, $dtpTaisyouKE, $rdoExct_Grop);
    //         if (!$result['result']) {
    //             throw new \Exception($result['data']);
    //         }
    //         //'◆　05:順位
    //         $result = $this->FrmEvaluationtotal->fnc_InsRank(CNS_17, $strStartYm, $dtpTaisyouKE, $rdoExct_Type);
    //         if (!$result['result']) {
    //             throw new \Exception($result['data']);
    //         }
    //         //◆　06:達成度
    //         //202040829 lhb del s
    //         // $result = $this->FrmEvaluationtotal->fnc_InsTasseido(CNS_17, $strStartYm, $dtpTaisyouKE);
    //         // if (!$result['result']) {
    //         // 	throw new \Exception($result['data']);
    //         // }
    //         //202040829 lhb del e
    //     } catch (\Exception $e) {
    //         $result['result'] = FALSE;
    //         $result['error'] = $e->getMessage();
    //     }
    //     return $result;
    // }
    //20240903 lhb del e
    // 20240722 YIN INS E

    public function fncChkJISSEKISYUKEI()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            if (isset($_POST['data'])) {
                $rdoBoth = $_POST['data']['rdoBoth'];
                $rdo6Months = $_POST['data']['rdo6Months'];
                $rdo1year = $_POST['data']['rdo1year'];
                $dtpTaisyouKE = $_POST['data']['dtpTaisyouKE'];
                $cboKoukaType = $_POST['data']['cboKoukaType'];
            } else {
                throw new \Exception('params error');
            }
            $this->FrmEvaluationtotal = new FrmEvaluationtotal();
            //データ取得(社員別考課表タイプデータ.評価対象期間終了)
            $dt = $this->FrmEvaluationtotal->SelJKKOUKA_SYAIN_TYPE_SQL();
            if (!$dt['result']) {
                throw new \Exception($dt['data']);
            }
            if (!$dt['data'][0]['HYOUKA_KIKAN_END']) {
                throw new \Exception('W0002');
            }
            //データ取得(実績集計データ)
            $result_chk = $this->FrmEvaluationtotal->CHK_JISSEKI_SYUKEI_SQL($cboKoukaType, $rdoBoth, $rdo6Months, $rdo1year, $dtpTaisyouKE);
            if (!$result_chk['result']) {
                throw new \Exception($result_chk['data']);
            }
            //データが存在する場合
            $result['data']['KENSU'] = $result_chk['data'][0]['KENSU'];

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function button1Click()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        $blnTran = FALSE;
        try {

            $dtpTaisyouKE = $_POST['data']['dtpTaisyouKE'];
            $this->FrmEvaluationtotal = new FrmEvaluationtotal();
            //データ取得(社員別考課表タイプデータ.評価対象期間終了)
            $dt = $this->FrmEvaluationtotal->SelJKKOUKA_SYAIN_TYPE_SQL();
            if (!$dt['result']) {
                throw new \Exception($dt['data']);
            }
            if (!$dt['data'][0]['HYOUKA_KIKAN_END']) {
                throw new \Exception('W0002');
            }
            //トランザクション開始
            $this->FrmEvaluationtotal->Do_transaction();
            $blnTran = TRUE;
            //削除処理(SQL)
            $result_del = $this->FrmEvaluationtotal->FncDelSYUKEI($dtpTaisyouKE);
            if (!$result_del['result']) {
                throw new \Exception($result_del['data']);
            }
            $result_ins = $this->FrmEvaluationtotal->FncInsSYUKEI($dtpTaisyouKE);
            if (!$result_ins['result']) {
                throw new \Exception($result_ins['data']);
            }
            //コミット
            $this->FrmEvaluationtotal->Do_commit();
            $blnTran = FALSE;
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
            //ロールバック
            if ($blnTran) {
                $this->FrmEvaluationtotal->Do_rollback();
            }
        }

        $this->fncReturn($result);
    }

    //年月-numか月
    public function AddMonths($dtpYM, $num)
    {
        $dtpYM = $dtpYM . "01";
        $rtnDate = date('Ym', strtotime("$dtpYM $num month"));

        return $rtnDate;
    }

    //指定年月の末日を取得する
    public function GetEndDate($ym)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $date = $ym . '01';
            if (date('Ymd', strtotime($date)) == $date) {
                //翌月の1日前を返す
                $result['data'] = date('Ym', strtotime($date . ' +1 month -1 day'));
            } else {
                throw new \Exception('年月が不正です。yyyyMMを指定してください。');
            }
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

}
