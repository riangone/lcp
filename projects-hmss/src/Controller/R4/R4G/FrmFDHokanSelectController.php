<?php
namespace App\Controller\R4\R4G;

use App\Controller\AppController;
use App\Model\R4\R4G\FrmFDHokanSelect;

//*******************************************
// * sample controller
//*******************************************
class FrmFDHokanSelectController extends AppController
{
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
// public $autoRender = false;
    public $FrmFDHokanSelectSelect;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');
    }

    public function index()
    {
        // 画面表示内容の設定
        // レイアウトファイルの指定(app/View/Layouts/FrmFDHokanSelect_layout.ctpを参照)
        $layout = 'FrmFDHokanSelect_layout';
        $this->render('/R4/R4G/FrmFDHokanSelect/index', $layout);
    }

    //   **********************************
    //   '処 理 名：検索データを取得サーバー側処理
    //   '関 数 名：fncFrmFDHokanSelect
    //   '引    数：フォームロード状態値
    //   '既定値：False
    //   '引    数：FD未作成データのみ抽出状態値
    //   '説明：画面.FD未作成データのみ抽出のチェック状態値
    //   '引    数：登録予定日From
    //   '説明：画面.登録予定日Fromの取得値
    //   '引    数：登録予定日To
    //   '説明：画面.登録予定日Toの取得値
    //   '戻 り 値：成功：True、失敗：False、該当データなし：Null
    //   '処理説明：検索データを取得
    //   **********************************
    public function fncFrmFDHokanSelect()
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            // 呼出クラスのインスタンス作成
            $postData = $_POST['request'];

            if (isset($postData)) {
                $this->FrmFDHokanSelectSelect = new FrmFDHokanSelect();
                $result = $this->FrmFDHokanSelectSelect->fncFrmFDHokanSelect($postData);

                $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($result['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];

                $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridData($result["data"], $totalPage, $page, $tmpCount);

                unset($_POST['request']);
                $_POST['request'] = null;

                $this->fncReturn($tmpJqgrid);
            }

        }

    }

}
