<?php
namespace App\Controller\R4\R4G;

use App\Controller\AppController;
use App\Model\R4\R4G\FrmOkaiageMst;
use Cake\Core\Exception\Exception;

//*******************************************
// * sample controller
//*******************************************
class FrmOkaiageMstController extends AppController
{
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
// public $autoRender = false;
    public $FrmOkaiageMst;
    public $Do_conn;
    public $Do_Excute;
    public function index()
    {
        // 画面表示内容の設定
        // レイアウトファイルの指定(app/View/Layouts/FrmOkaiageMst_layout.ctpを参照)
        $layout = 'FrmOkaiageMst_layout';
        $this->render('/R4/R4G/FrmOkaiageMst/index', $layout);
    }

    // '**********************************************************************
    // '処理概要：フォームロード
    // '**********************************************************************
    public function fncFrmOkaiageMst()
    {
        try {
            $this->FrmOkaiageMst = new FrmOkaiageMst();
            $result = $this->FrmOkaiageMst->funFrmOkaiageMst();
            if (!$result['result']) {
                throw new \Exception(!$result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);

    }

    //**************************************************************************
    //お買上げ明細マスタのデータを削除する
    //お買上げ明細マスタに追加するためのSQLを発行
    //**************************************************************************
    public function fncDeleteUpdataOkaiageMst()
    {
        $Arr_Insert = '';
        try {
            if (isset($_POST['data']['request'])) {
                $Arr_Insert = $_POST['data']['request'];
            }
            if ($Arr_Insert == '') {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            } else {
                $this->FrmOkaiageMst = new FrmOkaiageMst();
                $this->Do_conn = $this->FrmOkaiageMst->Do_conn();
                if (!$this->Do_conn['result']) {
                    throw new \Exception($this->Do_conn['data']);
                }
                $this->FrmOkaiageMst->Do_transaction();
                $this->Do_Excute = $this->FrmOkaiageMst->fncDelete();
                if (!$this->Do_Excute['result']) {
                    throw new \Exception($this->Do_Excute['data']);
                }
                if ($this->Do_Excute['result']) {
                    $num = count($Arr_Insert);
                    for ($i = 0; $i < $num; $i++) {
                        $this->Do_Excute = $this->FrmOkaiageMst->fncInsert($Arr_Insert[$i]);
                        if (!$this->Do_Excute['result']) {
                            throw new \Exception($this->Do_Excute['data']);
                        }
                    }
                }
                $this->FrmOkaiageMst->Do_commit();
                $result['result'] = TRUE;
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $this->FrmOkaiageMst->Do_rollback();
        }
        $this->FrmOkaiageMst->Do_close();
        $this->fncReturn($result);
    }

}
