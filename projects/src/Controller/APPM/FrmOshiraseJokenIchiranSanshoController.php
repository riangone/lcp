<?php
/**
 * 説明：
 *
 *
 * @author yinhuaiyu
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL　　　　　　　　
 * * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\APPM;

use App\Controller\AppController;
use App\Model\APPM\FrmOshiraseJokenIchiranSansho;
class FrmOshiraseJokenIchiranSanshoController extends AppController
{
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    public $autoLayout = TRUE;
    public $result = array();
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsFncLog');
    }
    /**
     * デフォルトで最初に実行される機能
     */
    public function index()
    {
        // Viewファイル呼出し
        $this->render('index', 'FrmOshiraseJokenIchiranSansho_layout');
    }

    //'***********************************************************************
    //'処 理 名：連携区分の取得
    //'関 数 名：FncGetNaiBu
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：連携区分の取得
    //'***********************************************************************
    public function fncGetNaiBu()
    {
        $result = array();

        try {
            $FrmOshiraseJokenIchiranSansho = new FrmOshiraseJokenIchiranSansho();
            $result['RenKeiKbn'] = $FrmOshiraseJokenIchiranSansho->FncGetNaiBu('1');
            if (!$result['RenKeiKbn']['result']) {
                throw new \Exception($result['RenKeiKbn']['data']);
            }
            $result['DelFlg'] = $FrmOshiraseJokenIchiranSansho->FncGetNaiBu('2');
            if (!$result['DelFlg']['result']) {
                throw new \Exception($result['DelFlg']['data']);
            }
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：メッセージ取得
    //'関 数 名：fncGetMesseji
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function fncGetMesseji()
    {
        $result = array();

        try {
            $FrmOshiraseJokenIchiranSansho = new FrmOshiraseJokenIchiranSansho();
            $result = $FrmOshiraseJokenIchiranSansho->fncGetMesseji();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //'***********************************************************************
    //'処 理 名：お知らせ条件一覧データの取得
    //'関 数 名：fncGetOshiraseData
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：お知らせ条件一覧データの取得
    //'***********************************************************************
    public function fncGetOshiraseData()
    {
        $result = array();
        try {
            if (isset($_POST['request'])) {
                $txtHyoJiFrom = trim($_POST['request']['txtHyoJiFrom']);
                $txtHyoJiTo = trim($_POST['request']['txtHyoJiTo']);
                $chkZenkensofuFlg = trim($_POST['request']['chkZenkensofuFlg']);
                $txtMesseJi = substr(trim($_POST['request']['txtMesseJi']), 0, 6);
                $ddlRenKeiKbn = trim($_POST['request']['ddlRenKeiKbn']);
                $ddlDelFlg = trim($_POST['request']['ddlDelFlg']);

                $FrmOshiraseJokenIchiranSansho = new FrmOshiraseJokenIchiranSansho();
                $result = $FrmOshiraseJokenIchiranSansho->fncGetOshiraseData($txtHyoJiFrom, $txtHyoJiTo, $chkZenkensofuFlg, $txtMesseJi, $ddlRenKeiKbn, $ddlDelFlg, "");
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($result['data']);
                $sortstr = $tmpJqgridShow['sortStr'];
                $start = $tmpJqgridShow['start'];
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $result = $FrmOshiraseJokenIchiranSansho->fncGetOshiraseData($txtHyoJiFrom, $txtHyoJiTo, $chkZenkensofuFlg, $txtMesseJi, $ddlRenKeiKbn, $ddlDelFlg, $sortstr);

                foreach ((array) $result["data"] as $key => $value) {
                    $result["data"][$key]['TAITORU'] = htmlspecialchars($result["data"][$key]['TAITORU'], ENT_COMPAT, 'UTF-8');
                }

                $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridDataReload($result["data"], $totalPage, $page, $tmpCount, $start);
                $result = $tmpJqgrid;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

}
