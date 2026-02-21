<?php
namespace App\Controller\R4\R4G;

use App\Controller\AppController;
use App\Model\R4\R4G\FrmBillSitoInput;

//*******************************************
// * sample controller
//*******************************************
class FrmBillSitoInputController extends AppController
{
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
// public $autoRender = false;
    public $FrmBillSitoInput;
    public function initialize(): void
    {
        parent::initialize();
        if ($this->request->is('ajax')) {
            $this->viewBuilder()->setClassName('Ajax');
        }
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsLogControl');
        $this->loadComponent('ClsReport');
        $this->loadComponent('ClsComDoRefresh');

    }
    // public $helpers = array('Html');

    public $conn = "";

    public function index()
    {
        // 画面表示内容の設定
        // レイアウトファイルの指定(app/View/Layouts/frmBillSitoInput_layout.ctpを参照)
        $this->render('index', 'FrmBillSitoInput_layout');
    }

    /**********************************************************************
              処 理 名：更新ボタン押下時サーバー処理
              関 数 名：fncFrmBillSitoInputAction
              引    数：注文書番号  手形据置日数
              戻 り 値：無し
              処理説明：入力データを更新する
          **********************************************************************/

    public function fncFrmBillSitoInputAction()
    {
        try {
            register_shutdown_function(
                array(
                    $this,
                    "finally"
                )
            );
            $message_array = array(
                "msg" => "false",
                "flag" => "false"
            );
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                if (isset($_POST['data']) == true) {
                    $strCMN_NO = $_POST['data']['txtCMN_NO'];
                    $strBillSito = $_POST['data']['txtBillSito'];

                    $this->FrmBillSitoInput = new FrmBillSitoInput();

                    $result = $this->FrmBillSitoInput->fncBillSITOSelect($this->ClsComFnc->FncNv($strCMN_NO));

                    $this->conn = $this->FrmBillSitoInput->Do_conn();

                    if (!$this->conn['result']) {
                        throw new \Exception($this->conn['data']);
                    }

                    //ﾄﾗﾝｻﾞｸｼｮﾝ開始
                    $this->FrmBillSitoInput->Do_transaction();

                    //更新を発行
                    if ($result['result'] && count((array) $result['data']) > 0) {
                        $result = $this->FrmBillSitoInput->fncUpdBillSITO($this->ClsComFnc->fncTrimEnd($strCMN_NO), $this->ClsComFnc->fncTrimEnd($strBillSito));
                        if (!$result['result']) {
                            throw new \Exception($result['data']);
                        }
                    } else
                        if ($result['result'] && count((array) $result['data']) <= 0) {
                            $result = $this->FrmBillSitoInput->fncInsBillSITO($this->ClsComFnc->fncTrimEnd($strCMN_NO), $this->ClsComFnc->fncTrimEnd($strBillSito));
                            if (!$result['result']) {
                                throw new \Exception($result['data']);
                            }
                        } else {
                            throw new \Exception($result['data']);
                        }

                    //コミット
                    $this->FrmBillSitoInput->Do_commit();

                    //'バッチﾌｧｲﾙ起動

                    $RefreshSql = array();
                    $RefreshSql[0] = "BEGIN dbms_snapshot.refresh('HBILLSITOD','f'); END;";

                    $this->ClsComDoRefresh->DoRefresh($RefreshSql);

                    $message_array = array(
                        "msg" => "true",
                        "flag" => "true"
                    );

                }
            }
            $this->fncReturn($message_array);
        } catch (\Exception $e) {
            $message_array = array(
                'flag' => 'false',
                'msg' => array(
                    'error_code' => 'E9999',
                    'message' => $e->getMessage()
                )
            );

            $this->FrmBillSitoInput->Do_rollback();
            $this->fncReturn($message_array);
        }
    }

    public function finally()
    {
        if (isset($this->FrmBillSitoInput)) {
            $this->FrmBillSitoInput->Do_close();
            unset($this->FrmBillSitoInput);
        }
    }

    public function fncFrmBillSitoInputValidating()
    {
        try {
            $message_array = array(
                "msg" => "false",
                "flag" => "false"
            );
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                if (isset($_POST['data']) == true) {
                    $strCMN_NO = $_POST['data']['txtCMN_NO'];

                    $this->FrmBillSitoInput = new FrmBillSitoInput();

                    $result = $this->FrmBillSitoInput->fncCMNSelect($strCMN_NO);

                    if ($result['result'] && count((array) $result['data']) > 0) {
                        $message_array = array(
                            'flag' => 'true',
                            'msg' => 'true',
                            'data' => array(
                                'txtUCNO' => $this->ClsComFnc->FncNv($result['data'][0]['UC_NO']),
                                'txtKeiyakusya' => $this->ClsComFnc->FncNv($result['data'][0]['KYK_CUS_NM1']),
                                'txtSiyosya' => $this->ClsComFnc->FncNv($result['data'][0]['SIY_CUS_NM1']),
                                'txtSiyosyaKN' => $this->ClsComFnc->FncNv($result['data'][0]['SIY_FGN']),
                                'txtKaptes' => number_format($this->ClsComFnc->FncNz($result['data'][0]['KAP_MOT_KIN']))
                            )
                        );
                        $result1 = $this->FrmBillSitoInput->fncBillSITOSelect($this->ClsComFnc->FncNv($strCMN_NO));

                        if ($result1['result'] && count((array) $result1['data']) > 0) {
                            $message_array['data']['txtBillSito'] = $this->ClsComFnc->FncNv($result1['data'][0]['SITO']);
                        } elseif (!$result1['result']) {
                            throw new \Exception($result1['data'][0]);
                        }
                    } else
                        if ($result['result'] && count((array) $result['data']) <= 0) {
                            $message_array = array(
                                'flag' => 'true',
                                'msg' => array(
                                    'error_code' => 'I9999',
                                    'message' => '対象データが存在しません'
                                )
                            );
                        } else {
                            throw new \Exception($result['data']);
                        }
                    // $this->log("validate");
                    // $this->log($message_array);

                }
            }
            $this->fncReturn($message_array);
        } catch (\Exception $e) {
            $message_array = array(
                'flag' => 'false',
                'msg' => array(
                    'error_code' => 'E9999',
                    'message' => $e->getMessage()
                )
            );
            $this->fncReturn($message_array);
        }
    }

    /**********************************************************************
              処 理 名：削除ボタン押下時サーバー処理
              関 数 名：fncFrmBillSitoInputDelete
              引    数：$注文書番号
              戻 り 値：JSON
              処理説明：データを削除する
          **********************************************************************/

    public function fncFrmBillSitoInputDelete()
    {
        try {
            $message_array = array(
                'flag' => 'false',
                'msg' => 'false'
            );
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                if (isset($_POST['data']) == true) {
                    $strCMN_NO = $_POST['data']['txtCMN_NO'];

                    $this->FrmBillSitoInput = new FrmBillSitoInput();

                    //レコードの削除処理
                    $result = $this->FrmBillSitoInput->fncDeleteBillSITO(rtrim($strCMN_NO));

                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }

                    //'バッチﾌｧｲﾙ起動

                    $RefreshSql = array();
                    $RefreshSql[0] = "BEGIN dbms_snapshot.refresh('HBILLSITOD','f'); END;";

                    $this->ClsComDoRefresh->DoRefresh($RefreshSql);

                    $message_array = array(
                        'flag' => 'true',
                        'msg' => 'true'
                    );

                }
            }
            $this->fncReturn($message_array);
        } catch (\Exception $e) {
            $message_array = array(
                'flag' => 'false',
                'msg' => $e->getMessage()
            );
            $this->fncReturn($message_array);
        }
    }

}
