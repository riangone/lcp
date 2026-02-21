<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmHRAKUOutput;

//*******************************************
// * sample controller
//*******************************************
class FrmHRAKUOutputController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
// public $autoRender = false;
    public $FrmHRAKUOutput = null;
    // 変換パターン３
    public $pattern3 = array();
    // 税率区分
    public $TAX_MST = array();
    // 取引区分
    public $TORIHIKI_KBN_MST = array();

    //　デフォルトで最初に実行される機能
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    public function index()
    {
        // Viewファイル呼出し
        $this->render('index', 'FrmHRAKUOutput_layout');
    }

    public function btnViewClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            //データの取得

            $this->FrmHRAKUOutput = new FrmHRAKUOutput();

            $result = $this->FrmHRAKUOutput->btnView_Click();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($result['data']);
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];
            $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridDataIndex($result["data"], $totalPage, $page, $tmpCount);
            $result = $tmpJqgrid;


        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function csvOutputClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            //データの取得
            if ($_POST["data"]) {
                $postData = $_POST["data"];
                $this->FrmHRAKUOutput = new FrmHRAKUOutput();

                $res = $this->FrmHRAKUOutput->getPattern('変換パターン３');
                if (!$res['result']) {
                    throw new \Exception($res['data']);
                }
                if ($res['row'] > 0) {
                    for ($i = 0; $i < $res['row']; $i++) {
                        $this->pattern3[$res['data'][$i]['CODE']] = $res['data'][$i]['VALUE1'];
                    }
                }
                $res = $this->FrmHRAKUOutput->getPattern('税率区分');
                if (!$res['result']) {
                    throw new \Exception($res['data']);
                }
                if ($res['row'] > 0) {
                    for ($i = 0; $i < $res['row']; $i++) {
                        $this->TAX_MST[$res['data'][$i]['VALUE1']] = $res['data'][$i]['CODE'];
                    }
                }
                $res = $this->FrmHRAKUOutput->getPattern('取引区分');
                if (!$res['result']) {
                    throw new \Exception($res['data']);
                }
                if ($res['row'] > 0) {
                    for ($i = 0; $i < $res['row']; $i++) {
                        $this->TORIHIKI_KBN_MST[$res['data'][$i]['VALUE1']] = $res['data'][$i]['CODE'];
                    }
                }
                //CSV出力日取得
                $strStartDate = $this->ClsComFnc->FncGetSysDate();
                if (strlen($strStartDate) !== 10) {
                    throw new \Exception($strStartDate);
                }
                $postData['sysdate'] = str_replace('-', '', $strStartDate);

                $res = $this->FrmHRAKUOutput->getMibaraiData($postData, 0);
                if (!$res['result']) {
                    throw new \Exception($res['data']);
                }
                if ($res['row'] > 0) {
                    $cntRes = $this->FrmHRAKUOutput->getMibaraiDataCnt($postData);
                    if (!$cntRes['result']) {
                        throw new \Exception($cntRes['data']);
                    }
                    $postData['cnt'] = $cntRes['row'];
                    (array) $MibaraiRes = $this->OutData4R4Mibarai($res['data'], $postData);
                    if (!$MibaraiRes['result']) {
                        throw new \Exception($MibaraiRes['error']);
                    }
                    $result['data']['url'] = $MibaraiRes['data']['url'];
                } else {
                    $res = $this->FrmHRAKUOutput->getMibaraiData($postData, 1);
                    if ($res['row'] == 0) {
                        throw new \Exception('W0024');
                    }
                    $postData['cnt'] = $res['row'];
                    (array) $ShiwakeRes = $this->OutData4R4Shiwake($res['data'], $postData);
                    if (!$ShiwakeRes['result']) {
                        throw new \Exception($ShiwakeRes['error']);
                    }
                    $result['data']['url'] = $ShiwakeRes['data']['url'];
                }

                $result['result'] = true;

            }

        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }
    function OutData4R4Mibarai($resData, $postData)
    {
        $result = array(
            'data' => [],
            'result' => false
        );
        try {
            $outputData = array();
            for ($i = 0; $i < count($resData); $i++) {
                $row = $this->SetkouzakeyData($resData[$i]);
                $row = $this->SetHissutekiyoData($row);
                $enmColR4Mibarai = array();
                $enmColR4Mibarai['販社コード'] = "3634";
                $enmColR4Mibarai['レコードID'] = "B";
                $enmColR4Mibarai['外部経費等支払取込№'] = $row['グループNO'];
                $enmColR4Mibarai['行№'] = $i + 1;
                $enmColR4Mibarai['支払先コード'] = $row['申請_支払先CD'];
                $enmColR4Mibarai['支払先名'] = "";
                $enmColR4Mibarai['支払先カナ名'] = "";
                $enmColR4Mibarai['出金口座'] = "";
                $enmColR4Mibarai['支払方法区分'] = "";
                $enmColR4Mibarai['手数料負担区分'] = "";
                $enmColR4Mibarai['決済方法区分'] = "";
                $enmColR4Mibarai['振込指定区分'] = "";
                $enmColR4Mibarai['銀行コード'] = "";
                $enmColR4Mibarai['支店コード'] = "";
                $enmColR4Mibarai['銀行名'] = "";
                $enmColR4Mibarai['支店名'] = "";
                $enmColR4Mibarai['銀行カナ'] = "";
                $enmColR4Mibarai['支店カナ'] = "";
                $enmColR4Mibarai['預金種目'] = "";
                $enmColR4Mibarai['口座番号'] = "";
                $enmColR4Mibarai['口座名義人'] = "";
                $enmColR4Mibarai['口座名義人カナ'] = "";
                $enmColR4Mibarai['証憑№'] = $row['申請_伝票NO'];
                $enmColR4Mibarai['取引発生日'] = $row['SHIWAKE_CRE_DATE'];
                $enmColR4Mibarai['支払予定日'] = $row['SHIWAKE_CRE_DATE'];
                $enmColR4Mibarai['未払計上日'] = $row['SHIWAKE_CRE_DATE'];
                $enmColR4Mibarai['計上科目科目コード'] = $row['借方_勘定科目コード'];
                $enmColR4Mibarai['計上科目項目コード'] = $row['借方_補助科目コード'];
                $enmColR4Mibarai['計上科目課税区分'] = $row['借方_税区分コード'];
                if ($row['借方_税区分コード'] === '7' || $row['借方_税区分コード'] === '6' || $row['借方_税区分コード'] === '5' || $row['借方_税区分コード'] === '4' || $row['借方_税区分コード'] === '07' || $row['借方_税区分コード'] === '06' || $row['借方_税区分コード'] === '05' || $row['借方_税区分コード'] === '04') {
                    $enmColR4Mibarai['計上科目課税区分'] = "0";
                    $enmColR4Mibarai['計上科目取引区分'] = isset($this->TORIHIKI_KBN_MST[$row['明細_取引区分']]) ? $this->TORIHIKI_KBN_MST[$row['明細_取引区分']] : '';
                    $enmColR4Mibarai['計上科目税率区分'] = isset($this->TAX_MST[$row['借方_税区分名']]) ? $this->TAX_MST[$row['借方_税区分名']] : '';
                } else if ($row['借方_税区分コード'] == '10') {
                    $enmColR4Mibarai['計上科目課税区分'] = "1";
                    $enmColR4Mibarai['計上科目取引区分'] = isset($this->TORIHIKI_KBN_MST[$row['明細_取引区分']]) ? $this->TORIHIKI_KBN_MST[$row['明細_取引区分']] : '';
                    $enmColR4Mibarai['計上科目税率区分'] = "";
                } else if ($row['借方_税区分コード'] == '90') {
                    $enmColR4Mibarai['計上科目課税区分'] = "9";
                    $enmColR4Mibarai['計上科目取引区分'] = "";
                    $enmColR4Mibarai['計上科目税率区分'] = "";
                } else {
                    $enmColR4Mibarai['計上科目取引区分'] = "";
                    $enmColR4Mibarai['計上科目税率区分'] = "";
                }
                $enmColR4Mibarai['計上科目口座キー1'] = $row['借方_口座キー1'];
                $enmColR4Mibarai['計上科目口座キー2'] = $row['借方_口座キー2'];
                $enmColR4Mibarai['計上科目口座キー3'] = $row['借方_口座キー3'];
                $enmColR4Mibarai['計上科目口座キー4'] = $row['借方_口座キー4'];
                $enmColR4Mibarai['計上科目口座キー5'] = $row['借方_口座キー5'];
                $enmColR4Mibarai['計上科目依頼拠点コード'] = $row['借方_負担部門コード'];
                $enmColR4Mibarai['計上科目必須摘要1'] = $row['借方_必須摘要1'];
                $enmColR4Mibarai['計上科目必須摘要2'] = $row['借方_必須摘要2'];
                $enmColR4Mibarai['計上科目必須摘要3'] = $row['借方_必須摘要3'];
                $enmColR4Mibarai['計上科目必須摘要4'] = $row['借方_必須摘要4'];
                $enmColR4Mibarai['計上科目必須摘要5'] = $row['借方_必須摘要5'];
                $enmColR4Mibarai['計上科目必須摘要6'] = $row['借方_必須摘要6'];
                $enmColR4Mibarai['計上科目必須摘要7'] = $row['借方_必須摘要7'];
                $enmColR4Mibarai['計上科目必須摘要8'] = $row['借方_必須摘要8'];
                $enmColR4Mibarai['計上科目必須摘要9'] = $row['借方_必須摘要9'];
                $enmColR4Mibarai['計上科目必須摘要10'] = $row['借方_必須摘要10'];
                $enmColR4Mibarai['経過科目科目コード'] = "21152";
                $enmColR4Mibarai['経過科目項目コード'] = "9";
                if ($row['貸方_税区分コード'] === '7' || $row['貸方_税区分コード'] === '6' || $row['貸方_税区分コード'] === '5' || $row['貸方_税区分コード'] === '4') {
                    $enmColR4Mibarai['経過科目課税区分'] = "0";
                    $enmColR4Mibarai['経過科目取引区分'] = "";
                    $enmColR4Mibarai['経過科目税率区分'] = isset($this->TAX_MST[$row['貸方_税率']]) ? $this->TAX_MST[$row['貸方_税率']] : '';
                } else if ($row['貸方_税区分コード'] == '10') {
                    $enmColR4Mibarai['経過科目課税区分'] = "1";
                    $enmColR4Mibarai['経過科目取引区分'] = "";
                    $enmColR4Mibarai['経過科目税率区分'] = "";
                } else if ($row['貸方_税区分コード'] == '90') {
                    $enmColR4Mibarai['経過科目課税区分'] = "9";
                    $enmColR4Mibarai['経過科目取引区分'] = "";
                    $enmColR4Mibarai['経過科目税率区分'] = "";
                } else {
                    $enmColR4Mibarai['経過科目課税区分'] = "";
                    $enmColR4Mibarai['経過科目取引区分'] = "";
                    $enmColR4Mibarai['経過科目税率区分'] = "";
                }
                $enmColR4Mibarai['経過科目口座キー1'] = $row['貸方_口座キー1'];
                $enmColR4Mibarai['経過科目口座キー2'] = $row['貸方_口座キー2'];
                $enmColR4Mibarai['経過科目口座キー3'] = $row['貸方_口座キー3'];
                $enmColR4Mibarai['経過科目口座キー4'] = $row['貸方_口座キー4'];
                $enmColR4Mibarai['経過科目口座キー5'] = $row['貸方_口座キー5'];
                $enmColR4Mibarai['経過科目発生拠点コード'] = $row['貸方_負担部門コード'];
                $enmColR4Mibarai['経過科目必須摘要1'] = $row['貸方_必須摘要1'];
                $enmColR4Mibarai['経過科目必須摘要2'] = $row['貸方_必須摘要2'];
                $enmColR4Mibarai['経過科目必須摘要3'] = $row['貸方_必須摘要3'];
                $enmColR4Mibarai['経過科目必須摘要4'] = $row['貸方_必須摘要4'];
                $enmColR4Mibarai['経過科目必須摘要5'] = $row['貸方_必須摘要5'];
                $enmColR4Mibarai['経過科目必須摘要6'] = $row['貸方_必須摘要6'];
                $enmColR4Mibarai['経過科目必須摘要7'] = $row['貸方_必須摘要7'];
                $enmColR4Mibarai['経過科目必須摘要8'] = $row['貸方_必須摘要8'];
                $enmColR4Mibarai['経過科目必須摘要9'] = $row['貸方_必須摘要9'];
                $enmColR4Mibarai['経過科目必須摘要10'] = $row['貸方_必須摘要10'];
                $enmColR4Mibarai['税込金額'] = $row['借方_金額'];
                $enmColR4Mibarai['税抜金額'] = $row['借方_税抜き額'];
                $enmColR4Mibarai['消費税額'] = $row['借方_税額'];
                $enmColR4Mibarai['摘要'] = $row['申請_明細_フリー1'];
                $enmColR4Mibarai['相手先区分'] = $row['相手先区分'] == null ? '' : $row['相手先区分'];
                $enmColR4Mibarai['お客様NO取引先'] = $row['申請_支払先CD'];
                $enmColR4Mibarai['登録番号課税免税事業者'] = $row['申請_支払先名'];
                if ($row['特例区分'] == "0") {
                    $enmColR4Mibarai['事業者名登録番号'] = "T0000000000000";
                } else {
                    $enmColR4Mibarai['事業者名登録番号'] = "";
                }
                $enmColR4Mibarai['特例区分'] = $row['特例区分'] == null ? '' : $row['特例区分'];
                array_push($outputData, $enmColR4Mibarai);
            }
            (array) $outputRes = $this->OutData4R4ALL($outputData, $postData);
            if (!$outputRes['result']) {
                throw new \Exception($outputRes['error']);
            }
            $result['result'] = true;
            $result['data']['url'] = $outputRes['data']['url'];

        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    function OutData4R4Shiwake($resData, $postData)
    {
        $result = array(
            'data' => [],
            'result' => false,
        );
        try {
            $outputData = array();
            for ($i = 0; $i < count($resData); $i++) {
                $row = $this->SetkouzakeyData($resData[$i]);
                $row = $this->SetHissutekiyoData($row);
                $enmColR4Shiwake = array();
                $enmColR4Shiwake['販社コード'] = "3634";
                $enmColR4Shiwake['レコードID'] = "B";
                $enmColR4Shiwake['外部仕訳取込№'] = $row['グループNO'];
                $enmColR4Shiwake['行№'] = $i + 1;
                $enmColR4Shiwake['計上日'] = $row['仕訳日'];
                $enmColR4Shiwake['計上区分'] = "1";
                $enmColR4Shiwake['証憑№'] = $row['申請_伝票NO'];
                $enmColR4Shiwake['振戻日'] = "";
                $enmColR4Shiwake['計上金額'] = $row['借方_税抜き額'];
                $enmColR4Shiwake['税込金額'] = $row['借方_金額'];
                $enmColR4Shiwake['消費税額'] = $row['借方_税額'];
                $enmColR4Shiwake['摘要'] = $row['申請_明細_フリー1'];
                $enmColR4Shiwake['借方科目コード'] = $row['借方_勘定科目コード'];
                $enmColR4Shiwake['借方項目コード'] = $row['借方_補助科目コード'];
                if ($row['借方_税区分コード'] === '7' || $row['借方_税区分コード'] === '6' || $row['借方_税区分コード'] === '5' || $row['借方_税区分コード'] === '4' || $row['借方_税区分コード'] === '07' || $row['借方_税区分コード'] === '06' || $row['借方_税区分コード'] === '05' || $row['借方_税区分コード'] === '04') {
                    $enmColR4Shiwake['借方課税区分'] = 0;
                    if ($row['明細_取引区分'] == "仕入") {
                        $enmColR4Shiwake['借方取引区分'] = "0";
                    } else {
                        $enmColR4Shiwake['借方取引区分'] = isset($this->TORIHIKI_KBN_MST[$row['明細_取引区分']]) ? $this->TORIHIKI_KBN_MST[$row['明細_取引区分']] : '';
                    }
                    $enmColR4Shiwake['借方税率区分'] = isset($this->TAX_MST[$row['借方_税区分名']]) ? $this->TAX_MST[$row['借方_税区分名']] : '';
                } else if ($row['借方_税区分コード'] === '10') {
                    $enmColR4Shiwake['借方課税区分'] = "1";
                    if ($row['明細_取引区分'] == "仕入") {
                        $enmColR4Shiwake['借方取引区分'] = "0";
                    } else {
                        $enmColR4Shiwake['借方取引区分'] = isset($this->TORIHIKI_KBN_MST[$row['明細_取引区分']]) ? $this->TORIHIKI_KBN_MST[$row['明細_取引区分']] : '';
                    }
                    $enmColR4Shiwake['借方税率区分'] = "";
                } else if ($row['借方_税区分コード'] === '90') {
                    $enmColR4Shiwake['借方課税区分'] = "9";
                    if ($row['明細_取引区分'] == "仕入") {
                        $enmColR4Shiwake['借方取引区分'] = "0";
                    } else {
                        $enmColR4Shiwake['借方取引区分'] = isset($this->TORIHIKI_KBN_MST[$row['明細_取引区分']]) ? $this->TORIHIKI_KBN_MST[$row['明細_取引区分']] : '';
                    }
                    $enmColR4Shiwake['借方税率区分'] = "";
                } else {
                    $enmColR4Shiwake['借方課税区分'] = "";
                    if ($row['明細_取引区分'] == "仕入") {
                        $enmColR4Shiwake['借方取引区分'] = "0";
                    } else {
                        $enmColR4Shiwake['借方取引区分'] = isset($this->TORIHIKI_KBN_MST[$row['明細_取引区分']]) ? $this->TORIHIKI_KBN_MST[$row['明細_取引区分']] : '';
                    }
                    $enmColR4Shiwake['借方税率区分'] = "";
                }
                $enmColR4Shiwake['借方口座キー1'] = $row['借方_口座キー1'];
                $enmColR4Shiwake['借方口座キー2'] = $row['借方_口座キー2'];
                $enmColR4Shiwake['借方口座キー3'] = $row['借方_口座キー3'];
                $enmColR4Shiwake['借方口座キー4'] = $row['借方_口座キー4'];
                $enmColR4Shiwake['借方口座キー5'] = $row['借方_口座キー5'];
                $enmColR4Shiwake['借方発生拠点コード'] = $row['借方_負担部門コード'];
                $enmColR4Shiwake['借方発生日'] = $row['仕訳日'];
                $enmColR4Shiwake['借方必須摘要1'] = $row['借方_必須摘要1'];
                $enmColR4Shiwake['借方必須摘要2'] = $row['借方_必須摘要2'];
                $enmColR4Shiwake['借方必須摘要3'] = $row['借方_必須摘要3'];
                $enmColR4Shiwake['借方必須摘要4'] = $row['借方_必須摘要4'];
                $enmColR4Shiwake['借方必須摘要5'] = $row['借方_必須摘要5'];
                $enmColR4Shiwake['借方必須摘要6'] = $row['借方_必須摘要6'];
                $enmColR4Shiwake['借方必須摘要7'] = $row['借方_必須摘要7'];
                $enmColR4Shiwake['借方必須摘要8'] = $row['借方_必須摘要8'];
                $enmColR4Shiwake['借方必須摘要9'] = $row['借方_必須摘要9'];
                $enmColR4Shiwake['借方必須摘要10'] = $row['借方_必須摘要10'];
                $enmColR4Shiwake['貸方科目コード'] = $row['貸方_勘定科目コード'];
                $enmColR4Shiwake['貸方項目コード'] = $row['貸方_補助科目コード'];
                if ($row['貸方_税区分コード'] === '7' || $row['貸方_税区分コード'] === '6' || $row['貸方_税区分コード'] === '5' || $row['貸方_税区分コード'] === '4' || $row['貸方_税区分コード'] === '07' || $row['貸方_税区分コード'] === '06' || $row['貸方_税区分コード'] === '05' || $row['貸方_税区分コード'] === '04') {
                    $enmColR4Shiwake['貸方課税区分'] = "0";
                    $enmColR4Shiwake['貸方取引区分'] = "";
                    $enmColR4Shiwake['貸方税率区分'] = isset($this->TAX_MST[$row['貸方_税区分名']]) ? $this->TAX_MST[$row['貸方_税区分名']] : '';
                } else if ($row['貸方_税区分コード'] === '10') {
                    $enmColR4Shiwake['貸方課税区分'] = "1";
                    $enmColR4Shiwake['貸方取引区分'] = "";
                    $enmColR4Shiwake['貸方税率区分'] = "";
                } else if ($row['貸方_税区分コード'] === '90') {
                    $enmColR4Shiwake['貸方課税区分'] = "9";
                    $enmColR4Shiwake['貸方取引区分'] = "";
                    $enmColR4Shiwake['貸方税率区分'] = "";
                } else {
                    $enmColR4Shiwake['貸方課税区分'] = "";
                    $enmColR4Shiwake['貸方取引区分'] = "";
                    $enmColR4Shiwake['貸方税率区分'] = "";
                }

                $enmColR4Shiwake['貸方口座キー1'] = $row['貸方_口座キー1'];
                $enmColR4Shiwake['貸方口座キー2'] = $row['貸方_口座キー2'];
                $enmColR4Shiwake['貸方口座キー3'] = $row['貸方_口座キー3'];
                $enmColR4Shiwake['貸方口座キー4'] = $row['貸方_口座キー4'];
                $enmColR4Shiwake['貸方口座キー5'] = $row['貸方_口座キー5'];
                $enmColR4Shiwake['貸方発生拠点コード'] = $row['貸方_負担部門コード'];
                $enmColR4Shiwake['貸方発生日'] = $row['仕訳日'];
                $enmColR4Shiwake['貸方必須摘要1'] = $row['貸方_必須摘要1'];
                $enmColR4Shiwake['貸方必須摘要2'] = $row['貸方_必須摘要2'];
                $enmColR4Shiwake['貸方必須摘要3'] = $row['貸方_必須摘要3'];
                $enmColR4Shiwake['貸方必須摘要4'] = $row['貸方_必須摘要4'];
                $enmColR4Shiwake['貸方必須摘要5'] = $row['貸方_必須摘要5'];
                $enmColR4Shiwake['貸方必須摘要6'] = $row['貸方_必須摘要6'];
                $enmColR4Shiwake['貸方必須摘要7'] = $row['貸方_必須摘要7'];
                $enmColR4Shiwake['貸方必須摘要8'] = $row['貸方_必須摘要8'];
                $enmColR4Shiwake['貸方必須摘要9'] = $row['貸方_必須摘要9'];
                $enmColR4Shiwake['貸方必須摘要10'] = $row['貸方_必須摘要10'];
                $enmColR4Shiwake['BLANK'] = "";
                $enmColR4Shiwake['相手先区分'] = $row['相手先区分'] == null ? '' : $row['相手先区分'];
                $enmColR4Shiwake['お客様NO取引先'] = $row['申請_支払先CD'];
                $enmColR4Shiwake['登録番号課税免税事業者'] = $row['申請_支払先名'];
                if ($row['特例区分'] == "0") {
                    $enmColR4Shiwake['事業者名登録番号'] = "T0000000000000";
                } else {
                    $enmColR4Shiwake['事業者名登録番号'] = "";
                }
                $enmColR4Shiwake['特例区分'] = $row['特例区分'] == null ? '' : $row['特例区分'];
                array_push($outputData, $enmColR4Shiwake);
            }
            (array) $outputRes = $this->OutData4R4ALL($outputData, $postData);
            if (!$outputRes['result']) {
                throw new \Exception($outputRes['error']);
            }
            $result['result'] = true;
            $result['data']['url'] = $outputRes['data']['url'];

        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }
    function OutData4R4ALL($outputData, $postData)
    {
        $result = array(
            'data' => [],
            'result' => false
        );
        try {
            //出力先パス
            $tmpPath1 = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
            $tmpPath2 = "webroot/files/R4k/";
            $tmpPath = $tmpPath1 . "/" . $tmpPath2;

            if (file_exists($tmpPath)) {
                if (!(is_readable($tmpPath) && is_writable($tmpPath) && is_executable($tmpPath))) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
                //フォルダ削除
                $dh = opendir($tmpPath);
                while ($file = readdir($dh)) {
                    if ($file != "." && $file != "..") {
                        $fullpath = $tmpPath . "/" . $file;
                        if (!is_dir($fullpath)) {
                            @chmod($tmpPath, 0777);
                            @unlink($fullpath);
                        } else {
                            rmdir($tmpPath);
                        }
                    }
                }
            } else {
                $outFloder = dirname($tmpPath);
                if (!(is_readable($outFloder) && is_writable($outFloder) && is_executable($outFloder))) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
                if (!mkdir($tmpPath, 0777, TRUE)) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
            }

            $filename = $postData['grNm'] . ".csv";
            $filefullpath = $tmpPath . $filename;
            if (file_exists($filefullpath)) {
                @unlink($filefullpath);
            }
            $myfile = fopen($filefullpath, 'w');
            //'ヘッダ行
            $strOut = '';
            // 販社コード 3634
            $strOut .= "3634" . "\t";
            // 'レコードID A
            $strOut .= "A" . "\t";
            // 外部仕訳取込№ グループ№
            $strOut .= $postData['grNo'] . "\t";
            // 明細件数 取込件数
            $strOut .= $postData['cnt'] . "\t";
            // 入力担当者コード 作成者
            $strOut .= isset($postData['strTan']) ? $postData['strTan'] : '' . "\t";
            // 入力担当者所属販社コード
            $strOut .= "3634" . "\t";
            // 入力拠点コード
            $strOut .= isset($postData['strKtn']) ? $postData['strKtn'] : '' . "\t";
            // 入力日 CSV出力日
            $strOut .= $postData['sysdate'] . "\t";
            // 拠点送信区分 1
            $strOut .= "1" . "\t";
            // 外部仕訳データ内容 グループ名
            $strOut .= $postData['grNm'] . "\t" . "\n";

            // 明細
            for ($i = 0; $i < count($outputData); $i++) {
                foreach ($outputData[$i] as $value) {
                    $strOut .= $value . "\t";
                }
                $strOut .= "\t" . "end" . "\n";
            }

            // フッタ
            // 販社コード 3634
            $strOut .= "3634" . "\t";
            // 'レコードID A
            $strOut .= "C" . "\t";
            // 外部仕訳取込№ グループ№
            $strOut .= $postData['grNo'] . "\t";

            $strOut = mb_convert_encoding($strOut, "SJIS-win");
            fwrite($myfile, $strOut);
            @fclose($myfile);
            $result['result'] = TRUE;
            $result['data']['url'] = "files/R4k/" . $filename;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    // 口座キー１～５へのセット
    function SetkouzakeyData($typTarget)
    {
        // 借方・口座キー
        $typTarget['借方_口座キー1'] = "";
        $typTarget['借方_口座キー2'] = "";
        $typTarget['借方_口座キー3'] = "";
        $typTarget['借方_口座キー4'] = "";
        $typTarget['借方_口座キー5'] = "";
        if ($typTarget['借方口座キー'] !== null && $typTarget['借方口座キー'] !== ",,,,") {
            $wk = explode(",", $typTarget['借方口座キー']);
            if ($wk[0] !== "") {
                $typTarget['借方_口座キー1'] = $this->GetValueByKeyword($typTarget, $this->SetHissutekiyoByKeyword($wk[0]), $wk[0], "kari");
            }
            if ($wk[1] !== "") {
                $typTarget['借方_口座キー2'] = $this->GetValueByKeyword($typTarget, $this->SetHissutekiyoByKeyword($wk[1]), $wk[1], "kari");
            }
            if ($wk[2] !== "") {
                $typTarget['借方_口座キー3'] = $this->GetValueByKeyword($typTarget, $this->SetHissutekiyoByKeyword($wk[2]), $wk[2], "kari");
            }
            if ($wk[3] !== "") {
                $typTarget['借方_口座キー4'] = $this->GetValueByKeyword($typTarget, $this->SetHissutekiyoByKeyword($wk[3]), $wk[3], "kari");
            }
            if ($wk[4] !== "") {
                $typTarget['借方_口座キー5'] = $this->GetValueByKeyword($typTarget, $this->SetHissutekiyoByKeyword($wk[4]), $wk[4], "kari");
            }
        }
        // 貸方・口座キー
        $typTarget['貸方_口座キー1'] = "";
        $typTarget['貸方_口座キー2'] = "";
        $typTarget['貸方_口座キー3'] = "";
        $typTarget['貸方_口座キー4'] = "";
        $typTarget['貸方_口座キー5'] = "";
        if ($typTarget['貸方口座キー'] !== null && $typTarget['貸方口座キー'] !== ",,,,") {
            $wk = explode(",", $typTarget['貸方口座キー']);
            if ($wk[0] !== "") {
                $typTarget['貸方_口座キー1'] = $this->GetValueByKeyword($typTarget, $this->SetHissutekiyoByKeyword($wk[0]), $wk[0], "kasi");
            }
            if ($wk[1] !== "") {
                $typTarget['貸方_口座キー2'] = $this->GetValueByKeyword($typTarget, $this->SetHissutekiyoByKeyword($wk[1]), $wk[1], "kasi");
            }
            if ($wk[2] !== "") {
                $typTarget['貸方_口座キー3'] = $this->GetValueByKeyword($typTarget, $this->SetHissutekiyoByKeyword($wk[2]), $wk[2], "kasi");
            }
            if ($wk[3] !== "") {
                $typTarget['貸方_口座キー4'] = $this->GetValueByKeyword($typTarget, $this->SetHissutekiyoByKeyword($wk[3]), $wk[3], "kasi");
            }
            if ($wk[4] !== "") {
                $typTarget['貸方_口座キー5'] = $this->GetValueByKeyword($typTarget, $this->SetHissutekiyoByKeyword($wk[4]), $wk[4], "kasi");
            }
        }

        return $typTarget;
    }
    // 必須摘要のセット
    function SetHissutekiyoData($typTarget)
    {
        // 借方・必須摘要
        $typTarget['借方_必須摘要1'] = "";
        $typTarget['借方_必須摘要2'] = "";
        $typTarget['借方_必須摘要3'] = "";
        $typTarget['借方_必須摘要4'] = "";
        $typTarget['借方_必須摘要5'] = "";
        $typTarget['借方_必須摘要6'] = "";
        $typTarget['借方_必須摘要7'] = "";
        $typTarget['借方_必須摘要8'] = "";
        $typTarget['借方_必須摘要9'] = "";
        $typTarget['借方_必須摘要10'] = "";
        if ($typTarget['借方必須摘要'] !== null && $typTarget['借方必須摘要'] !== ",,,,,,,,,") {
            $wk = explode(",", $typTarget['借方必須摘要']);
            if ($wk[0] !== "") {
                $typTarget['借方_必須摘要1'] = $this->GetValueByKeyword($typTarget, $this->SetHissutekiyoByKeyword($wk[0]), $wk[0], "kari");
            }
            if ($wk[1] !== "") {
                $typTarget['借方_必須摘要2'] = $this->GetValueByKeyword($typTarget, $this->SetHissutekiyoByKeyword($wk[1]), $wk[1], "kari");
            }
            if ($wk[2] !== "") {
                $typTarget['借方_必須摘要3'] = $this->GetValueByKeyword($typTarget, $this->SetHissutekiyoByKeyword($wk[2]), $wk[2], "kari");
            }
            if ($wk[3] !== "") {
                $typTarget['借方_必須摘要4'] = $this->GetValueByKeyword($typTarget, $this->SetHissutekiyoByKeyword($wk[3]), $wk[3], "kari");
            }
            if ($wk[4] !== "") {
                $typTarget['借方_必須摘要5'] = $this->GetValueByKeyword($typTarget, $this->SetHissutekiyoByKeyword($wk[4]), $wk[4], "kari");
            }
            if ($wk[5] !== "") {
                $typTarget['借方_必須摘要6'] = $this->GetValueByKeyword($typTarget, $this->SetHissutekiyoByKeyword($wk[5]), $wk[5], "kari");
            }
            if ($wk[6] !== "") {
                $typTarget['借方_必須摘要7'] = $this->GetValueByKeyword($typTarget, $this->SetHissutekiyoByKeyword($wk[6]), $wk[6], "kari");
            }
            if ($wk[7] !== "") {
                $typTarget['借方_必須摘要8'] = $this->GetValueByKeyword($typTarget, $this->SetHissutekiyoByKeyword($wk[7]), $wk[7], "kari");
            }
            if ($wk[8] !== "") {
                $typTarget['借方_必須摘要9'] = $this->GetValueByKeyword($typTarget, $this->SetHissutekiyoByKeyword($wk[8]), $wk[8], "kari");
            }
            if ($wk[9] !== "") {
                $typTarget['借方_必須摘要10'] = $this->GetValueByKeyword($typTarget, $this->SetHissutekiyoByKeyword($wk[9]), $wk[9], "kari");
            }
        }
        // 貸方・必須摘要
        $typTarget['貸方_必須摘要1'] = "";
        $typTarget['貸方_必須摘要2'] = "";
        $typTarget['貸方_必須摘要3'] = "";
        $typTarget['貸方_必須摘要4'] = "";
        $typTarget['貸方_必須摘要5'] = "";
        $typTarget['貸方_必須摘要6'] = "";
        $typTarget['貸方_必須摘要7'] = "";
        $typTarget['貸方_必須摘要8'] = "";
        $typTarget['貸方_必須摘要9'] = "";
        $typTarget['貸方_必須摘要10'] = "";
        if ($typTarget['貸方必須摘要'] !== null && $typTarget['貸方必須摘要'] !== ",,,,,,,,,") {
            $wk = explode(",", $typTarget['貸方必須摘要']);
            if ($wk[0] !== "") {
                $typTarget['貸方_必須摘要1'] = $this->GetValueByKeyword($typTarget, $this->SetHissutekiyoByKeyword($wk[0]), $wk[0], "kasi");
            }
            if ($wk[1] !== "") {
                $typTarget['貸方_必須摘要2'] = $this->GetValueByKeyword($typTarget, $this->SetHissutekiyoByKeyword($wk[1]), $wk[1], "kasi");
            }
            if ($wk[2] !== "") {
                $typTarget['貸方_必須摘要3'] = $this->GetValueByKeyword($typTarget, $this->SetHissutekiyoByKeyword($wk[2]), $wk[2], "kasi");
            }
            if ($wk[3] !== "") {
                $typTarget['貸方_必須摘要4'] = $this->GetValueByKeyword($typTarget, $this->SetHissutekiyoByKeyword($wk[3]), $wk[3], "kasi");
            }
            if ($wk[4] !== "") {
                $typTarget['貸方_必須摘要5'] = $this->GetValueByKeyword($typTarget, $this->SetHissutekiyoByKeyword($wk[4]), $wk[4], "kasi");
            }
            if ($wk[5] !== "") {
                $typTarget['貸方_必須摘要6'] = $this->GetValueByKeyword($typTarget, $this->SetHissutekiyoByKeyword($wk[5]), $wk[5], "kasi");
            }
            if ($wk[6] !== "") {
                $typTarget['貸方_必須摘要7'] = $this->GetValueByKeyword($typTarget, $this->SetHissutekiyoByKeyword($wk[6]), $wk[6], "kasi");
            }
            if ($wk[7] !== "") {
                $typTarget['貸方_必須摘要8'] = $this->GetValueByKeyword($typTarget, $this->SetHissutekiyoByKeyword($wk[7]), $wk[7], "kasi");
            }
            if ($wk[8] !== "") {
                $typTarget['貸方_必須摘要9'] = $this->GetValueByKeyword($typTarget, $this->SetHissutekiyoByKeyword($wk[8]), $wk[8], "kasi");
            }
            if ($wk[9] !== "") {
                $typTarget['貸方_必須摘要10'] = $this->GetValueByKeyword($typTarget, $this->SetHissutekiyoByKeyword($wk[9]), $wk[9], "kasi");
            }
        }
        return $typTarget;
    }
    // 楽楽データ上の値を取得する
    function GetValueByKeyword($typTarget, $c, $koban, $karikasi)
    {
        $ret = "";
        $ret = isset($typTarget[$c]) ? $typTarget[$c] : "";
        if ($koban == "350" && $karikasi == "kasi") {
            $ret = $typTarget['FREE1'];
        }
        if (strlen($ret) > 20) {
            $ret = mb_strimwidth($ret, 0, 20);
        }

        return $ret;
    }
    // 文字列から見出し以降の値を切り出して返す
    function SetHissutekiyoByKeyword($koban)
    {
        $ret = "";
        if (isset($this->pattern3[$koban])) {
            $ret = $this->pattern3[$koban];
        }
        return $ret;
    }

}
