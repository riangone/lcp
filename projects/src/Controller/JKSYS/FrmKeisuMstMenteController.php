<?php
/**
 * 説明：
 *
 *
 * @author CIYUANCHEN
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           GSDL　　　　　　　
 * * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmKeisuMstMente;

//*******************************************
// * sample controller
//*******************************************
class FrmKeisuMstMenteController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
    // public $autoRender = FALSE;
    public $FrmKeisuMstMente;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncJKSYS');
    }

    //　デフォルトで最初に実行される機能
    public function index()
    {
        // 画面表示内容の設定
        // レイアウトファイルの指定(app/View/Layouts/frmListSelect_layout.ctpを参照)

        $this->render('index', 'FrmKeisuMstMente_layout');
    }

    public function fncSearchSpread()
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            if (isset($_POST['request'])) {
                $this->FrmKeisuMstMente = new FrmKeisuMstMente();

                //検索項目の取得
                $result = $this->FrmKeisuMstMente->fncSearch($_POST['request']);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                $tmpJqgridShow = $this->ClsComFncJKSYS->FncCreateJqGridShow($result['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];

                $tmpJqgrid = $this->ClsComFncJKSYS->FncCreateJqGridData($result["data"], $totalPage, $page, $tmpCount);

                $this->fncReturn($tmpJqgrid);
            } else //スプレッドの初期値設定
            {
                $result['result'] = TRUE;
                $result['data'] = '';

                $this->fncReturn($result);
            }
        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();

            $this->fncReturn($result);
        }
    }

    public function fncSyoureikinMstEigyou()
    {
        $result = array(
            'result' => FALSE,
            'error' => 'ErrorInfo'
        );
        try {
            $this->FrmKeisuMstMente = new FrmKeisuMstMente();
            $result = $this->FrmKeisuMstMente->fncSyoureikinMstEigyou();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncSyoureikinMstTencyou()
    {
        $result = array(
            'result' => FALSE,
            'error' => 'ErrorInfo'
        );
        try {
            $this->FrmKeisuMstMente = new FrmKeisuMstMente();
            $result = $this->FrmKeisuMstMente->fncSyoureikinMstTencyou();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncSyoureikinMstKmk()
    {
        $result = array(
            'result' => FALSE,
            'error' => 'ErrorInfo'
        );
        try {
            $postData = $_POST['data'];
            $this->FrmKeisuMstMente = new FrmKeisuMstMente();
            $result = $this->FrmKeisuMstMente->fncSyoureikinMstKmk($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //登録モードの場合のみ存在チェック
    //範囲指定ありの場合のみ範囲が他ﾃﾞｰﾀと重なっていないかチェック
    public function fncKeisuMstChk()
    {
        $result = array(
            'result' => FALSE,
            'error' => 'ErrorInfo',
            'data' => array(
                'fncKeisuMst' => TRUE,
                'fncKeisuMstChk' => TRUE
            )
        );

        try {
            $this->FrmKeisuMstMente = new FrmKeisuMstMente();

            $postData = $_POST['data'];
            //'登録モードの場合のみ存在チェック
            if ($postData['isrdbEigyou'] == 'true') {
                $fncKeisuMst = $this->FrmKeisuMstMente->fncKeisuMst($postData);
                if (!$fncKeisuMst['result']) {
                    throw new \Exception($fncKeisuMst['data']);
                } else {
                    if ($fncKeisuMst['row'] > 0) {
                        $result['data']['fncKeisuMst'] = FALSE;
                    }
                }
            }
            //'範囲指定ありの場合のみ範囲が他ﾃﾞｰﾀと重なっていないかチェック
            if ($postData['isHaniS'] == 'true') {
                $fncKeisuMstChk = $this->FrmKeisuMstMente->fncKeisuMstChk($postData);
                if (!$fncKeisuMstChk['result']) {
                    throw new \Exception($fncKeisuMstChk['data']);
                } else {
                    if ($fncKeisuMstChk['row'] > 0) {
                        $result['data']['fncKeisuMstChk'] = FALSE;
                    }
                }
            }
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //登録ボタン
    public function fncKeisuMstIns()
    {
        $this->FrmKeisuMstMente = new FrmKeisuMstMente();
        $result = array(
            'result' => FALSE,
            'error' => 'ErrorInfo',
        );
        $blnTran = FALSE;
        try {
            $postData = $_POST['data'];
            //トランザクションを開始する
            $this->FrmKeisuMstMente->Do_transaction();
            $blnTran = TRUE;
            //データを登録する
            if ($postData['isrdbEigyou'] == 'true') {
                $result_ins = $this->FrmKeisuMstMente->fncKeisuMstIns($postData);
                if (!$result_ins['result']) {
                    throw new \Exception($result_ins['data']);
                }
            } else {
                $result_upd = $this->FrmKeisuMstMente->fncKeisuMstUpd($postData);
                if (!$result_upd['result']) {
                    throw new \Exception($result_upd['data']);
                }
            }
            //コミット処理を行う
            $this->FrmKeisuMstMente->Do_commit();
            $blnTran = FALSE;

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();

            if ($blnTran) {
                $this->FrmKeisuMstMente->Do_rollback();
            }
        }

        $this->fncReturn($result);
    }

    //削除ボタン
    public function fncKeisuMstDel()
    {
        $this->FrmKeisuMstMente = new FrmKeisuMstMente();
        $result = array(
            'result' => FALSE,
            'error' => 'ErrorInfo'
        );
        $blnTran = FALSE;
        try {
            $postData = $_POST['data'];

            $this->FrmKeisuMstMente->Do_transaction();
            $blnTran = TRUE;

            $result_del = $this->FrmKeisuMstMente->fncKeisuMstDel($postData);
            if (!$result_del['result']) {
                throw new \Exception($result_del['data']);
            }

            $this->FrmKeisuMstMente->Do_commit();
            $blnTran = FALSE;

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();

            if ($blnTran) {
                $this->FrmKeisuMstMente->Do_rollback();
            }
        }

        $this->fncReturn($result);
    }

}
