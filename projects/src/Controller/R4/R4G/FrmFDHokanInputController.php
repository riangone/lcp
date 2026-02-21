<?php
namespace App\Controller\R4\R4G;

use App\Controller\AppController;
use App\Model\R4\R4G\FrmFDHokanInput;
use Cake\Core\Exception\Exception;

//*******************************************
// * sample controller
//*******************************************
class FrmFDHokanInputController extends AppController
{
    public $autoLayout = TRUE;
    public $FrmFDHokanInput;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
// public $autoRender = false;
    public function index()
    {
        // 画面表示内容の設定
        // レイアウトファイルの指定(app/View/Layouts/FrmFDHokanInput_layout.ctpを参照)
        $layout = 'FrmFDHokanInput_layout';
        $this->render('/R4/R4G/FrmFDHokanInput/index', $layout);
    }

    //**********************************************************************
    //処 理 名：検索データを取得サーバー側処理
    //関 数 名：fncKeijiReport
    //引    数：注文書番号
    //			  説明：親画面の検索結果データjqGridの選択行の9列目
    //戻 り 値：$result
    //処理説明：検索データを取得
    //**********************************************************************
    public function fncKeijiReport()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            if (isset($_POST['data']['request'])) {
                $postData = $_POST['data']['request'];
            }
            if ($postData == "") {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            } else {
                $this->FrmFDHokanInput = new FrmFDHokanInput();

                // 処理の呼出
                $result = $this->FrmFDHokanInput->fncKeijiReportSelect($postData['CHUMN_NO']);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {
                    $count = count((array) $result['data']);

                    if ($count <= 0) {
                        $result = array(
                            'result' => 'noData',
                            'data' => $count
                        );
                    } else {
                        $result['result'] = TRUE;
                    }
                }
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //**********************************************************************
    //処 理 名：データ修正サーバー側処理
    //関 数 名：fncUPDATE
    //引    数：更新データ
    //              説明：画面項目の内容
    //戻 り 値：$result処理結果
    //処理説明：データ修正サーバー側処理
    //**********************************************************************
    public function fncUPDATE()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            if (isset($_POST['data']['request'])) {
                $postData = $_POST['data']['request'];
            }
            if ($postData == "") {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            } else {
                $this->FrmFDHokanInput = new FrmFDHokanInput();
                // 処理の呼出
                $result = $this->FrmFDHokanInput->fncUPDKeijiReport($postData);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {
                    $count = count((array) $result['data']);

                    if ($count < 0) {
                        $result = array(
                            'result' => 'noData',
                            'data' => $count
                        );
                    } else {
                        $result['result'] = TRUE;
                    }
                }
            }

            $result['data'] = "";
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

}
