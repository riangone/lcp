<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmJinkenhiEnt;
//*******************************************
// * sample controller
//*******************************************
class FrmJinkenhiEntController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    public $FrmJinkenhiEnt;

    // public $components = array(
    //     'RequestHandler',
    //     'ClsComFncJKSYS'
    // );
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncJKSYS');
    }

    public function index()
    {
        // 画面表示内容の設定
        $this->render('index', 'FrmJinkenhiEnt_layout');
    }

    //フォーム初期化jqgrid
    public function fncSearchSpread()
    {
        $result = array(
            'result' => TRUE,
            'error' => '',
        );

        try {
            $this->FrmJinkenhiEnt = new FrmJinkenhiEnt();
            if (isset($_POST['request'])) {
                //出向者請求明細データの取得
                $result = $this->FrmJinkenhiEnt->procGetJinkenhiData($_POST['request']);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                foreach ((array) $result['data'] as $key => $value) {
                    $result['data'][$key]['SYSKAIHOKENRTOKEI'] = $value['KENKO_HKN_RYO'] + $value['KAIGO_HKN_RYO'] + $value['KOUSEINENKIN'] + $value['KOYOU_HKN_RYO'] + $value['ROUSAI_HKN_RYO'] + $value['JIDOUTEATE'] + $value['TAISYOKU_KYUFU'];
                    $result['data'][$key]['SYOUYOSYAKAIHOKENRYOKEI'] = $value['BNS_KENKO_HKN_RYO'] + $value['BNS_KAIGO_HKN_RYO'] + $value['BNS_KOUSEI_NENKIN'] + $value['BNS_JIDOU_TEATE'];
                }
                $tmpJqgridShow = $this->ClsComFncJKSYS->FncCreateJqGridShow($result['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];

                $result = $this->ClsComFncJKSYS->FncCreateJqGridDataIndex($result["data"], $totalPage, $page, $tmpCount);
            }
        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //フォーム初期化
    public function fncFormload()
    {
        $result = array(
            'result' => FALSE,
            'data' => array(
                'strRetYM' => "",
                'ddlKoyouKbn' => "",
                'GetBusyoMstValue' => "",
                'GetSyainMstValue' => "",
            ),
            'error' => ''
        );
        try {
            $this->FrmJinkenhiEnt = new FrmJinkenhiEnt();
            //人事コントロールマスタの処理年月取得
            $strRetYM = $this->FrmJinkenhiEnt->procGetJinjiCtrlMst_YM();
            if (!$strRetYM['result']) {
                throw new \Exception($strRetYM['data']);
            }
            if ($strRetYM["row"] > 0) {
                //日付形式を確認する
                $date = $strRetYM['data'][0]['SYORI_YM'] . '01';
                if (date('Ymd', strtotime($date)) != $date) {
                    //年月格式正しくない
                    throw new \Exception("String \"" . $strRetYM['data'][0]['SYORI_YM'] . "\" から型 'Date' への変換は無効です。");
                }
            } else {
                //年月なし
                throw new \Exception("コントロールマスタが存在しません！");
            }
            $result['data']['strRetYM'] = $strRetYM;
            //雇用区分ComboBoxのデータ取得
            $ddlKoyouKbn = $this->FrmJinkenhiEnt->procGetKoyouKbnData();
            if (!$ddlKoyouKbn['result']) {
                throw new \Exception($ddlKoyouKbn['data']);
            }
            $result['data']['ddlKoyouKbn'] = $ddlKoyouKbn['data'];
            //部署コード
            $GetBusyoMstValue = $this->FrmJinkenhiEnt->FncGetBusyoMstValue();
            if (!$GetBusyoMstValue['result']) {
                throw new \Exception($GetBusyoMstValue['data']);
            }
            $result['data']['GetBusyoMstValue'] = $GetBusyoMstValue['data'];
            //社員番号
            $GetSyainMstValue = $this->FrmJinkenhiEnt->FncGetSyainMstValue();
            if (!$GetSyainMstValue['result']) {
                throw new \Exception($GetSyainMstValue['data']);
            }
            $result['data']['GetSyainMstValue'] = $GetSyainMstValue['data'];

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //フォーム初期化
    public function fncSearchData()
    {
        $result = array(
            'result' => FALSE,
            'error' => '',
            'data' => array()
        );
        try {
            $postData = $_POST['data'];
            $this->FrmJinkenhiEnt = new FrmJinkenhiEnt();
            //職種Comboboxのデータ取得
            $DT_S = $this->FrmJinkenhiEnt->procGetSyokusyuData($postData['dtpYM']);
            if (!$DT_S['result']) {
                throw new \Exception($DT_S['data']);
            }
            $result['data']['DT_S'] = $DT_S;
            //雇用comboboxのデータ取得
            $DT_K = $this->FrmJinkenhiEnt->procGetKoyouKbnData();
            if (!$DT_K['result']) {
                throw new \Exception($DT_K['data']);
            }
            $result['data']['DT_K'] = $DT_K;
            //更新日付の取得
            $prvUpdateDateTime = $this->FrmJinkenhiEnt->procGetJinkenhiDataUpdateDate($postData);
            if (!$prvUpdateDateTime['result']) {
                throw new \Exception($prvUpdateDateTime['data']);
            }
            $result['data']['prvUpdateDateTime'] = $prvUpdateDateTime;

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //更新日付の取得
    public function updCheck()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $postData = $_POST['data'];
            $this->FrmJinkenhiEnt = new FrmJinkenhiEnt();
            //更新日付の取得
            $result = $this->FrmJinkenhiEnt->procGetJinkenhiDataUpdateDate($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //人件費データの存在チェック
    public function jinkenhiDataChk()
    {
        $result = array(
            'result' => false,
            'error' => ''
        );
        try {
            $postData = $_POST['data'];

            $this->FrmJinkenhiEnt = new FrmJinkenhiEnt();
            $result = $this->FrmJinkenhiEnt->procJinkenhiDataChk($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //更新処理
    public function updateAction()
    {
        $blnTran = FALSE;
        $result = array(
            'result' => FALSE,
            'error' => '',
        );
        try {
            $postData = $_POST['data'];
            $this->FrmJinkenhiEnt = new FrmJinkenhiEnt();
            //トランザクション開始
            $this->FrmJinkenhiEnt->Do_transaction();
            $blnTran = TRUE;
            //人件費データの削除
            $result = $this->FrmJinkenhiEnt->procDeleteJinkenhiData($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if (isset($postData['rowDatas'])) {
                $postData['rowDatas'] = json_decode($postData['rowDatas'], true);
                foreach ($postData['rowDatas'] as $value) {
                    //人件費データの登録
                    $result = $this->FrmJinkenhiEnt->procCreateJinkenhiData($value, $postData['dtpYM']);
                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }
                }
            }
            $result['data'] = '';
            //コミット
            $this->FrmJinkenhiEnt->Do_commit();
        } catch (\Exception $e) {
            //ロールバック
            if ($blnTran) {
                $this->FrmJinkenhiEnt->Do_rollback();
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

}
