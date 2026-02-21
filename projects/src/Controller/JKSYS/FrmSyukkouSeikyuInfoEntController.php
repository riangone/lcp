<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmSyukkouSeikyuInfoEnt;
class FrmSyukkouSeikyuInfoEntController extends AppController
{
    // var $components = array(
    //     'RequestHandler',
    //     'ClsComFncJKSYS'
    // );

    public $autoLayout = TRUE;
    // public $autoRender = FALSE;

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncJKSYS');
    }
    public function index()
    {
        $this->render('index', 'FrmSyukkouSeikyuInfoEnt_layout');
    }

    //出向社員請求明細データ取得
    public function selSyukkouSeikyuSQL()
    {
        $result = array(
            'result' => TRUE,
            'error' => ''
        );
        try {
            if (isset($_POST['request'])) {
                $postdata = $_POST['request'];

                $resUpdate = $this->selUpdDate($postdata);
                if (!$resUpdate['result']) {
                    throw new \Exception($resUpdate['error']);
                }

                $FrmSyukkouSeikyuInfoEnt = new FrmSyukkouSeikyuInfoEnt();
                //出向社員請求明細データ
                $result = $FrmSyukkouSeikyuInfoEnt->selSyukkouSeikyuSQL($postdata);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                foreach ((array) $result['data'] as $key => $value) {
                    array_splice($result['data'][$key], 3, 0, '');
                    $sum1 = $result['data'][$key]['KIHONKYU'] + $result['data'][$key]['CHOUSEIKYU'] + $result['data'][$key]['SYOKUMU_TEATE'] + $result['data'][$key]['KAZOKU_TEATE'] + $result['data'][$key]['TUKIN_TEATE'] + $result['data'][$key]['SYARYOU_TEATE'] + $result['data'][$key]['SYOUREIKIN'] + $result['data'][$key]['ZANGYOU_TEATE'] + $result['data'][$key]['SYUKKOU_TEATE'] + $result['data'][$key]['JIKANSA_TEATE'];
                    array_splice($result['data'][$key], 4, 0, $sum1);
                    $sum2 = $result['data'][$key]['KENKO_HKN_RYO'] + $result['data'][$key]['KAIGO_HKN_RYO'] + $result['data'][$key]['KOUSEINENKIN'] + $result['data'][$key]['JIDOU_TEATE'] + $result['data'][$key]['KOYOU_HKN_RYO'] + $result['data'][$key]['TAISYOKU_NENKIN'] + $result['data'][$key]['ROUSAI_UWA_HKN_RYO'];
                    array_splice($result['data'][$key], 15, 0, $sum2);
                    $sum3 = $result['data'][$key]['BNS_GK'] + $result['data'][$key]['BNS_KENKO_HKN_RYO'] + $result['data'][$key]['BNS_KAIGO_HKN_RYO'] + $result['data'][$key]['BNS_KOUSEI_NENKIN'] + $result['data'][$key]['BNS_JIDOU_TEATE'] + $result['data'][$key]['BNS_KOYOU_HOKEN'];
                    array_splice($result['data'][$key], 23, 0, $sum3);
                    array_splice($result['data'][$key], 30, 0, $sum1 + $sum2 + $sum3);
                }

                $tmpJqgridShow = $this->ClsComFncJKSYS->FncCreateJqGridShow($result['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];

                $result = $this->ClsComFncJKSYS->FncCreateJqGridData($result['data'], $totalPage, $page, $tmpCount);
                $result->updDate = $resUpdate['updDate'];
                $result->ShoriYM = $resUpdate['ShoriYM'];
            }
        } catch (\Exception $e) {
            $result['result'] = true;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //更新日付,処理年月取得
    public function selUpdDate($postdata)
    {
        $result = array(
            'result' => TRUE,
            'error' => '',
            'updDate' => '',
            'ShoriYM' => ''
        );
        try {
            //更新日付取得
            $FrmSyukkouSeikyuInfoEnt = new FrmSyukkouSeikyuInfoEnt();
            $data = $FrmSyukkouSeikyuInfoEnt->selUpdDate($postdata['taishoYM'], $postdata['comSyukkou']);
            if (!$data['result']) {
                throw new \Exception($data['data']);
            }
            if ($data['row'] > 0) {
                $result['updDate'] = $data['data'][0]['UPD_DATE'];
            }
            //処理年月取得
            $data = $FrmSyukkouSeikyuInfoEnt->selShoriYMSQL();
            if (!$data['result']) {
                throw new \Exception($data['data']);
            }
            $SYORI_YM = "";
            if ($data['row'] > 0) {
                $SYORI_YM = $data['data'][0]['SYORI_YM'];
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
            $result['ShoriYM'] = $SYORI_YM;
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        return $result;
    }

    public function selShoriYMSQL()
    {
        $result = array(
            'result' => false,
            'error' => ''
        );
        try {
            $FrmSyukkouSeikyuInfoEnt = new FrmSyukkouSeikyuInfoEnt();
            $tblCTL = $FrmSyukkouSeikyuInfoEnt->selShoriYMSQL();
            if (!$tblCTL['result']) {
                throw new \Exception($tblCTL['data']);
            }
            $SYORI_YM = "";
            if ($tblCTL['row'] > 0) {
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
            $result['data']['ShoriYM'] = $SYORI_YM;
            //出向先
            $resultComSyu = $FrmSyukkouSeikyuInfoEnt->selComSyukkou();
            if (!$resultComSyu['result']) {
                throw new \Exception($resultComSyu['data']);
            }
            $result['data']['ComSyu'] = $resultComSyu['data'];

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function updSyukkouSeikyu()
    {
        $FrmSyukkouSeikyuInfoEnt = new FrmSyukkouSeikyuInfoEnt();
        $result = array(
            'result' => false,
            'error' => ''
        );
        $blnTran = FALSE;
        try {
            $taisyouym = $_POST['data']['taisyouym'];
            $comSyukkou = $_POST['data']['comSyukkou'];
            $updDate = $_POST['data']['updDate'];
            $_POST['data']['new'] = json_decode($_POST['data']['new'], true);

            //チェック３(更新日チェック)
            $data = $FrmSyukkouSeikyuInfoEnt->selUpdDate($taisyouym, $comSyukkou);
            if (!$data['result']) {
                throw new \Exception($data['data']);
            }

            $checkDate = '';
            if ($data['row'] > 0) {
                $checkDate = $data['data'][0]['UPD_DATE'];
            }
            if ($updDate != $checkDate) {
                throw new \Exception('W0018');
            }

            //トランザクション開始
            $FrmSyukkouSeikyuInfoEnt->Do_transaction();
            $blnTran = TRUE;

            foreach ($_POST['data']['new'] as $value) {
                $result = $FrmSyukkouSeikyuInfoEnt->updSyukkouSeikyu($value, $taisyouym);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
            }

            //コミット
            $FrmSyukkouSeikyuInfoEnt->Do_commit();
            $result['data'] = '';
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
            if ($blnTran) {
                $FrmSyukkouSeikyuInfoEnt->Do_rollback();
            }
        }
        $this->fncReturn($result);
    }

}
