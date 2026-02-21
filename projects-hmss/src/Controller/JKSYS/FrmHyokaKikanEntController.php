<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmHyokaKikanEnt;

class FrmHyokaKikanEntController extends AppController
{
    public $autoLayout = TRUE;
    public $FrmHyokaKikanEnt;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncJKSYS');
    }
    public function index()
    {
        $this->render('index', 'FrmHyokaKikanEnt_layout');
    }

    //スプレッドの初期値設定
    public function frmHyokaKikanEntLoad()
    {
        $result = array(
            'result' => false,
            'error' => ''
        );
        try {
            $this->FrmHyokaKikanEnt = new FrmHyokaKikanEnt();
            //評価実施年月データの取得
            $result = $this->FrmHyokaKikanEnt->fncHyoukaJisshiYMDataSQL();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $tmpJqgridShow = $this->ClsComFncJKSYS->FncCreateJqGridShow($result['data']);
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];
            $result = $this->ClsComFncJKSYS->FncCreateJqGridData($result["data"], $totalPage, $page, $tmpCount);
        } catch (\Exception $e) {
            $result['result'] = true;
            $result['error'] = $e->getMessage();
            $result = (object) $result;
        }

        //現在の年月日、年月日
        $nowyearmonth = date('Ym');
        $nowdate = date('Y/m/d');
        $result->fulldate = $nowdate;
        $result->ymdate = $nowyearmonth;

        $this->fncReturn($result);
    }

    //人事コントロールマスタの取得
    public function getControlKakiMonth()
    {
        $result = array(
            'result' => false,
            'error' => ''
        );
        try {
            $FrmHyokaKikanEnt = new FrmHyokaKikanEnt();
            //人事コントロールマスタの取得
            $JinjiCtl = $FrmHyokaKikanEnt->fncJinjiCtlMstSQL();
            if (!$JinjiCtl['result']) {
                throw new \Exception($JinjiCtl['data']);
            }
            //支給年月
            if ($JinjiCtl["row"] > 0) {
                $SYORI_YM = $JinjiCtl['data'][0]['SYORI_YM'];
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

            $result['data']['JinjiCtl'] = $JinjiCtl['data'];

            //評価実施年月月份取得
            $GetKakiMonth = $FrmHyokaKikanEnt->GetControlKakiMonth();
            if (!$GetKakiMonth['result']) {
                throw new \Exception($GetKakiMonth['data']);
            }

            $result['data']['GetKakiMonth'] = $GetKakiMonth['data'];

            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //評価取込履歴データ取得
    public function fncHyoukaTriRirekiDataSQL()
    {
        $result = array(
            'result' => false,
            'error' => ''
        );
        try {
            $dtpJisshiYM = $_POST['data']['dtpJisshiYM'];
            //評価取込履歴データ取得
            $FrmHyokaKikanEnt = new FrmHyokaKikanEnt();
            $result = $FrmHyokaKikanEnt->fncHyoukaTriRirekiDataSQL($dtpJisshiYM);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //評価履歴データ削除
    public function fncDelHyoukaJisshiYMDataSQL()
    {
        $FrmHyokaKikanEnt = new FrmHyokaKikanEnt();
        $result = array(
            'result' => false,
            'error' => ''
        );
        $blnTran = FALSE;
        try {
            $dtpJisshiYM = $_POST["data"]["dtpJisshiYM"];
            //トランザクション開始
            $FrmHyokaKikanEnt->Do_transaction();
            $blnTran = TRUE;

            $result = $FrmHyokaKikanEnt->fncDelHyoukaJisshiYMDataSQL($dtpJisshiYM);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //コミット処理を行う
            $FrmHyokaKikanEnt->Do_commit();
            $result['data'] = "";
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
            if ($blnTran) {
                $FrmHyokaKikanEnt->Do_rollback();
            }
        }

        $this->fncReturn($result);
    }

    //評価実施年月データ更新
    public function fncUpdHyoukaJisshiYMDataSQL()
    {
        $FrmHyokaKikanEnt = new FrmHyokaKikanEnt();
        $result = array(
            'result' => false,
            'error' => ''
        );
        $blnTran = FALSE;
        try {
            //トランザクション開始
            $FrmHyokaKikanEnt->Do_transaction();
            $blnTran = TRUE;

            $result = $FrmHyokaKikanEnt->fncUpdHyoukaJisshiYMDataSQL($_POST['data']);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //コミット
            $FrmHyokaKikanEnt->Do_commit();
            $result['data'] = "";
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
            if ($blnTran) {
                $FrmHyokaKikanEnt->Do_rollback();
            }
        }

        $this->fncReturn($result);
    }

    //評価実施年月データ登録
    public function fncInsHyoukaJisshiYMDataSQL()
    {
        $this->FrmHyokaKikanEnt = new FrmHyokaKikanEnt();
        $result = array(
            'result' => false,
            'error' => ''
        );
        $blnTran = FALSE;
        try {
            //トランザクション開始
            $this->FrmHyokaKikanEnt->Do_transaction();
            $blnTran = TRUE;
            $result = $this->FrmHyokaKikanEnt->fncInsHyoukaJisshiYMDataSQL($_POST['data']);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //コミット
            $this->FrmHyokaKikanEnt->Do_commit();
            $result['data'] = "";
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
            if ($blnTran) {
                $this->FrmHyokaKikanEnt->Do_rollback();
            }
        }

        $this->fncReturn($result);
    }

    //期間重複チェック
    public function fncHyoukaKikanRepChkSQL()
    {
        $result = array(
            'result' => false,
            'error' => ''
        );
        try {
            $FrmHyokaKikanEnt = new FrmHyokaKikanEnt();
            $result_Rep = $FrmHyokaKikanEnt->fncHyoukaKikanRepChkSQL($_POST['data']);
            if (!$result_Rep['result']) {
                throw new \Exception($result_Rep['data']);
            }
            $result['data']['rep'] = $result_Rep;
            //存在チェック
            $result_check = $this->fncHyoukaCheck($_POST['data']);
            if (!$result_check['result']) {
                throw new \Exception($result_check['error']);
            }
            $result['data']['check'] = $result_check;

            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //評価対象期間が重複
    public function checkExistSyokoukyuData()
    {
        $result = array(
            'result' => false,
            'error' => ''
        );
        try {
            $FrmHyokaKikanEnt = new FrmHyokaKikanEnt();
            $result_exist = $FrmHyokaKikanEnt->CheckExistSyokoukyuData($_POST['data']);
            if (!$result_exist['result']) {
                throw new \Exception($result_exist['data']);
            }
            $result['data']['exist'] = $result_exist;
            //存在チェック
            $result_check = $this->fncHyoukaCheck($_POST['data']);
            if (!$result_check['result']) {
                throw new \Exception($result_check['error']);
            }
            $result['data']['check'] = $result_check;
            $result['data']['server_time'] = date('Y/m/d');

            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //存在チェック
    public function fncHyoukaCheck($postdata)
    {
        $result = array(
            'result' => false,
            'error' => ''
        );
        try {
            $FrmHyokaKikanEnt = new FrmHyokaKikanEnt();
            if ($postdata['flag'] == 1) {
                $result = $FrmHyokaKikanEnt->fncHyoukaRirekiDataSQL($postdata);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
            } else {
                $result = $FrmHyokaKikanEnt->fncHyoukaTriRirekiDataSQL($postdata['dtpJisshiYM']);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
            }
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    public function getTaisyoKSKE()
    {
        $result = array(
            'result' => false,
            'error' => ''
        );
        try {
            $FrmHyokaKikanEnt = new FrmHyokaKikanEnt();
            //START
            $strJissiYM = $_POST['data']['strJissiYM'];
            $resultks = $FrmHyokaKikanEnt->GetTaisyoKSKE($strJissiYM, "MIN", "START");
            if (!$resultks['result']) {
                throw new \Exception($resultks['data']);
            }
            if ($resultks['row'] > 0) {
                $result['data']['strTaisyoKS'] = $resultks['data'][0]['HYOUKA_KIKAN'];
            } else {
                $result['data']['strTaisyoKS'] = '';
            }
            //END
            $resultke = $FrmHyokaKikanEnt->GetTaisyoKSKE($strJissiYM, "MAX", "END");
            if (!$resultke['result']) {
                throw new \Exception($resultke['data']);
            }
            if ($resultke['row'] > 0) {
                $result['data']['strTaisyoKE'] = $resultke['data'][0]['HYOUKA_KIKAN'];
            } else {
                $result['data']['strTaisyoKE'] = '';
            }
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }
}
