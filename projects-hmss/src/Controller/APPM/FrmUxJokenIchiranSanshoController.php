<?php
/**
 * 説明：
 *
 *
 * @author YANGYANG
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
use App\Model\APPM\FrmUxJokenIchiranSansho;
class FrmUxJokenIchiranSanshoController extends AppController
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
        $this->render('index', 'FrmUxJokenIchiranSansho_layout');
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
            $FrmUxJokenIchiranSansho = new FrmUxJokenIchiranSansho();
            $result = $FrmUxJokenIchiranSansho->FncGetNaiBu("");
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $result['del'] = $FrmUxJokenIchiranSansho->FncGetNaiBu("1");
            if (!$result['del']['result']) {
                throw new \Exception($result['del']['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //'***********************************************************************
    //'処 理 名：メッセージのオートコンプリート
    //'関 数 名：FncAutoComplete
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：メッセージのオートコンプリート
    //'***********************************************************************
    public function fncAutoComplete()
    {
        $result = array();
        try {
            $FrmUxJokenIchiranSansho = new FrmUxJokenIchiranSansho();
            $result = $FrmUxJokenIchiranSansho->FncAutoComplete();
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
    //'処 理 名：UX条件一覧データの取得
    //'関 数 名：FncSearch
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：UX条件一覧データの取得
    //'***********************************************************************
    public function fncSearch()
    {
        $result = array();
        try {
            if (isset($_POST['request'])) {
                $txtHyoJI = trim($_POST['request']['txtHyoJI']);
                $chkZenkensofuFlg = trim($_POST['request']['chkZenkensofuFlg']);
                $txtMesseJi = substr(trim($_POST['request']['txtMesseJi']), 0, 6);
                $ddlRenKeiKbn = trim($_POST['request']['ddlRenKeiKbn']);
                $ddlDelFlg = trim($_POST['request']['ddlDelFlg']);

                $FrmUxJokenIchiranSansho = new FrmUxJokenIchiranSansho();
                $result = $FrmUxJokenIchiranSansho->FncSearch($txtHyoJI, $chkZenkensofuFlg, $txtMesseJi, $ddlRenKeiKbn, $ddlDelFlg, "");
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($result['data']);
                $sortstr = $tmpJqgridShow['sortStr'];
                $start = $tmpJqgridShow['start'];
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];

                $result = $FrmUxJokenIchiranSansho->FncSearch($txtHyoJI, $chkZenkensofuFlg, $txtMesseJi, $ddlRenKeiKbn, $ddlDelFlg, $sortstr);

                foreach ((array) $result["data"] as $key => $value) {
                    $result["data"][$key]['TAITORU'] = htmlspecialchars(isset($result["data"][$key]['TAITORU']) ? $result["data"][$key]['TAITORU'] : '', ENT_COMPAT, 'UTF-8');
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
