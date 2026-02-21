<?php
/**
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug             内容                               担当
 * YYYYMMDD           #ID                     XXXXXX                            FCSDL
 * 20151201           #2281		    		  車両業務システム_要望対応 		  	   	 YIN
 * 20161008           #2575		             納品請求書印刷処理改善 		  	   	         yangyang
 * 20221017           対応		R4との総額不一致チェックを行っているが不一致となった場合   yinhuaiyu
 * --------------------------------------------------------------------------------------------
 */

namespace App\Controller\R4\R4G;

use App\Controller\AppController;
use App\Model\R4\R4G\FrmOkaiagePrint;

class FrmOkaiagePrintController extends AppController
{

    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
// public $autoRender = false;
    private $Session;

    public $statuFlag = 9;
    public $chkFlag = 0;
    public $strCMN_NO = "";
    public $strStartDate = "";
    public $strEndDate = "";
    public $lngOutCnt = 0;
    public $conn = "";
    public $FrmOkaiagePrint;
    public function index()
    {
        $this->render('index', 'FrmOkaiagePrint_layout');
    }

    public function initialize(): void
    {
        parent::initialize();
        if ($this->request->is('ajax')) {
            $this->viewBuilder()->setClassName('Ajax');
        }
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsLogControl');
        $this->loadComponent('ClsReport');
    }
    public function styleidload()
    {
        $result = array(
            'result' => false,
            'error' => ''
        );
        try {
            $this->Session = $this->request->getSession();
            $STYLE_ID = $this->Session->read('STYLE_ID');
            $result['data']['STYLE_ID'] = $STYLE_ID;
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        // $this->set('result', $result);
        // $this->render('styleidload');
        $this->fncReturn($result);

    }

    /***********************************************************************
           処 理 名：プレビューボタン押下時
           関 数 名：fncFrmOkaiagePrintPreview
           引    数：出力条件、チャックフラグ、注文書番号、登録日From、登録日To
           戻 り 値：無し
           処理説明：帳票印刷
           ***********************************************************************/

    public function fncFrmOkaiagePrintPreview()
    {
        // ajax呼出処理チェック
        // 関数を直接呼び出してテストする場合はこの判定をコメント
        // http://IP/gdmz/cake/Smpkame/ajaxSelect/ で直接確認可能
        try {
            register_shutdown_function(
                array(
                    $this,
                    "fncShutdown"
                )
            );
            $message_array = array(
                'flag' => 'false',
                'msg' => 'false',
            );
            $this->Session = $this->request->getSession();
            $STYLE_ID = $this->Session->read('STYLE_ID');
            $login_user = $this->Session->read('login_user');
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                if (isset($_POST['data']) == true) {

                    $this->strCMN_NO = isset($_POST['data']['txtCMN_NO']) ? $_POST['data']['txtCMN_NO'] : [];
                    $this->strStartDate = $_POST['data']['cboStartDate'];
                    $this->strEndDate = $_POST['data']['cboEndDate'];
                    $this->chkFlag = $_POST['data']['flag'];
                    $this->FrmOkaiagePrint = new FrmOkaiagePrint();
                    // 処理の呼出

                    //データが一件もなかった場合、処理を抜ける
                    $result = $this->FrmOkaiagePrint->fncOKaiageCnt($this->chkFlag, $this->strCMN_NO, $this->strStartDate, $this->strEndDate);

                    if ($result['result'] && count((array) $result['data']) == 0) {
                        $this->statuFlag = 1;

                        $message_array = array(
                            "msg" => array(
                                "MsgFlag" => "OKaiageCnt",
                                "error_code" => "I9999",
                                "message" => "対象データが存在しません"
                            ),
                            "flag" => "true"
                        );
                        $this->fncReturn($message_array);
                        return;
                    } else
                        if (!$result['result']) {
                            throw new \Exception($result['data']);
                        }

                    //--------------------- 2014-01-09 仕様変更 Delete start ----------------------

                    /*
                                       * $result = $this -> FrmOkaiagePrint -> fncSitadoriCount($this -> chkFlag, $this -> strCMN_NO, $this -> strStartDate, $this -> strEndDate);

                                       if ($result['result'] && count($result['data']) > 0)
                                       {
                                       $message_array = array(
                                       "msg" => array(
                                       "MsgFlag" => "SitadoriCount",
                                       "CMN_NO" => $result['data'][0]['CMN_NO'],
                                       "KENSU" => $result['data'][0]['KENSU']
                                       ),
                                       "flag" => "true"
                                       );

                                       $this -> set('result', $message_array);
                                       $this -> render('fncfrmokaiageprintpreview');

                                       $this -> statuFlag = 1;

                                       return;
                                       }
                                       else
                                       if (!$result['result'])
                                       {
                                       throw new Exception($result['data']);
                                       }
                                       */

                    //--------------------- 2014-01-09 仕様変更 Delete end ----------------------

                    //明細行をデータセットに格納
                    //$result = $this -> FrmOkaiagePrint -> fncFuzokuMeisaiSelect($this -> chkFlag, $this -> strCMN_NO, $this -> strStartDate, $this -> strEndDate);

                    //ﾜｰｸﾃｰﾌﾞﾙ削除
                    $strsql = " DELETE FROM WK_FUZOKUHIN";
                    $result = $this->FrmOkaiagePrint->fncFuzokuhinDelete($strsql);

                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }

                    //付属品ﾜｰｸﾃｰﾌﾞﾙ作成
                    $result = $this->FrmOkaiagePrint->fncFuzokuMeisaiSelect($this->chkFlag, $this->strCMN_NO, $this->strStartDate, $this->strEndDate);

                    $recordCnt = 0;
                    $fuzokuNmCnt = 0;
                    $fuzokuNmArr = $this->ClsComFnc->initializeArray(27);

                    //---------同一注文書番号の明細を一列にする処理----------
                    while ($recordCnt < count((array) $result['data'])) {
                        $chumonNo = $result['data'][$recordCnt]['CMN_NO'];
                        $fuzokuNmArr[$fuzokuNmCnt] = $chumonNo;
                        $fuzokuNmCnt += 1;
                        while ($fuzokuNmCnt < 27) {
                            if ($recordCnt >= count((array) $result['data'])) {
                                break;
                            }
                            if ($result['data'][$recordCnt]['CMN_NO'] == $chumonNo) {
                                $fuzokuNmArr[$fuzokuNmCnt] = $result['data'][$recordCnt]['YHN_NM'];
                                $recordCnt += 1;
                            } else {
                                $fuzokuNmArr[$fuzokuNmCnt] = "";
                            }
                            $fuzokuNmCnt += 1;
                        }
                        while ($recordCnt < count((array) $result['data'])) {
                            if ($result['data'][$recordCnt]['CMN_NO'] == $chumonNo) {
                                $recordCnt += 1;
                            } else {
                                break;
                            }
                        }
                        $strsql = "INSERT INTO WK_FUZOKUHIN ( CMN_NO ";
                        $strsql .= " ,  FUZOKUHIN_NM1 ";
                        $strsql .= " ,  FUZOKUHIN_NM2 ";
                        $strsql .= " ,  FUZOKUHIN_NM3 ";
                        $strsql .= " ,  FUZOKUHIN_NM4 ";
                        $strsql .= " ,  FUZOKUHIN_NM5 ";
                        $strsql .= " ,  FUZOKUHIN_NM6 ";
                        $strsql .= " ,  FUZOKUHIN_NM7 ";
                        $strsql .= " ,  FUZOKUHIN_NM8 ";
                        $strsql .= " ,  FUZOKUHIN_NM9 ";
                        $strsql .= " ,  FUZOKUHIN_NM10 ";
                        $strsql .= " ,  FUZOKUHIN_NM11 ";
                        $strsql .= " ,  FUZOKUHIN_NM12 ";
                        $strsql .= " ,  FUZOKUHIN_NM13 ";
                        $strsql .= " ,  FUZOKUHIN_NM14 ";
                        $strsql .= " ,  FUZOKUHIN_NM15 ";
                        $strsql .= " ,  FUZOKUHIN_NM16 ";
                        $strsql .= " ,  FUZOKUHIN_NM17 ";
                        $strsql .= " ,  FUZOKUHIN_NM18 ";
                        $strsql .= " ,  FUZOKUHIN_NM19 ";
                        $strsql .= " ,  FUZOKUHIN_NM20 ";
                        $strsql .= " ,  FUZOKUHIN_NM21 ";
                        $strsql .= " ,  FUZOKUHIN_NM22 ";
                        $strsql .= " ,  FUZOKUHIN_NM23 ";
                        $strsql .= " ,  FUZOKUHIN_NM24 ";
                        $strsql .= " ,  FUZOKUHIN_NM25 ";
                        $strsql .= " ,  FUZOKUHIN_NM26) ";
                        $strsql .= " VALUES ( ";
                        $strsql .= $this->ClsComFnc->FncSqlNv($fuzokuNmArr[0]);

                        for ($i = 1; $i < 27; $i++) {
                            $strsql .= " , " . $this->ClsComFnc->FncSqlNv($fuzokuNmArr[$i]);
                        }
                        $strsql .= ")";

                        //WK_FUZOKUHINﾃｰﾌﾞﾙに挿入
                        $insertResult = $this->FrmOkaiagePrint->fncFuzokuhinInsert($strsql);

                        if ($insertResult['result'] != "true") {
                            throw new \Exception($result['data']);
                        }

                        $fuzokuNmArr = $this->ClsComFnc->initializeArray(27);
                        $fuzokuNmCnt = 0;
                    }

                    //トランザクション開始
                    $this->conn = $this->FrmOkaiagePrint->Do_conn();

                    if (!$this->conn['result']) {
                        throw new \Exception($this->conn['data']);
                    }

                    $this->FrmOkaiagePrint->Do_transaction();

                    //既存のお買上げ明細ﾃｰﾌﾞﾙの重複する行の削除
                    $result = $this->FrmOkaiagePrint->fncOkaiageDelete($this->chkFlag, $this->strCMN_NO, $this->strStartDate, $this->strEndDate);

                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }

                    //お買上げ明細ﾃｰﾌﾞﾙにﾃﾞｰﾀをINSERTする
                    $result = $this->FrmOkaiagePrint->fncOkaiageMSelect($this->chkFlag, $this->strCMN_NO, $this->strStartDate, $this->strEndDate);

                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }

                    //コミット
                    $this->FrmOkaiagePrint->Do_commit();

                    $this->FrmOkaiagePrint->Do_close();
                    //印刷する
                    $result = $this->FrmOkaiagePrint->fncOkaiagePrint($this->chkFlag, $this->strCMN_NO, $this->strStartDate, $this->strEndDate);

                    // 20221017 YIN INS S
                    $print_data = array();
                    $gross_error_message = '';
                    $recycle_error_message = '';
                    $gross_error_count = 0;
                    $recycle_error_count = 0;
                    // 20221017 YIN INS E

                    //総額がR4と一致するかチェックする
                    if ($result['result']) {
                        for ($j = 0; $j < count((array) $result['data']); $j++) {
                            if ($this->ClsComFnc->FncNz($result['data'][$j]['CHECK_KEI']) != $this->ClsComFnc->FncNz($result['data'][$j]['OSHIHARAIKEI'])) {
                                //20221017 YIN UPD S
                                // $err_msg = "注文書番号：" . $this -> ClsComFnc -> FncNz($result['data'][$j]['CMN_NO']) . " の総額がR4データと一致しないため処理を中断します。";
                                // $message_array = array(
                                // "msg" => array(
                                // "MsgFlag" => "OkaiagePrint",
                                // "error_code" => "E9999",
                                // "message" => $err_msg
                                // ),
                                // "flag" => "true"
                                // );
                                // $this -> statuFlag = 1;
                                // $this -> set('result', $message_array);
                                // $this -> render('fncfrmokaiageprintpreview');
                                // return;
                                if ($gross_error_count == 5) {
                                    $gross_error_message .= "<br />";
                                    $gross_error_count = 0;
                                }
                                $gross_error_message .= $this->ClsComFnc->FncNz($result['data'][$j]['CMN_NO']) . '　　';
                                $gross_error_count++;
                                continue;
                                //20221017 YIN UPD E
                            }
                            if ($this->ClsComFnc->FncNz($result['data'][$j]['GENKINCHK']) == 0 && $this->ClsComFnc->FncNz($result['data'][$j]['YOTAKU_KIN']) != 0) {
                                //20221017 YIN UPD S
                                // $err_msg = "現金=0　リサイクル預託金が発生しています！（注文書番号： " . $this -> ClsComFnc -> FncNz($result['data'][$j]['CMN_NO']) . "）";
                                // $message_array = array(
                                // "msg" => array(
                                // "MsgFlag" => "OkaiagePrint",
                                // "error_code" => "E9999",
                                // "message" => $err_msg
                                // ),
                                // "flag" => "true"
                                // );
                                // $this -> statuFlag = 1;
                                // $this -> set('result', $message_array);
                                // $this -> render('fncfrmokaiageprintpreview');
                                // return;
                                if ($recycle_error_count == 5) {
                                    $recycle_error_message .= "<br />";
                                    $recycle_error_count = 0;
                                }
                                $recycle_error_message .= $this->ClsComFnc->FncNz($result['data'][$j]['CMN_NO']) . '　　';
                                $recycle_error_count++;
                                continue;
                                //20221017 YIN UPD E
                            }
                            //20221017 YIN INS S
                            array_push($print_data, $result['data'][$j]);
                            //20221017 YIN INS E
                        }
                    } else {
                        throw new \Exception($result['data']);
                    }
                    if (count($print_data) == 0) {
                        $message_array = array(
                            "msg" => array(
                                "MsgFlag" => "OKaiageCnt",
                                "error_code" => "I9999",
                                "message" => "印刷可能なデータが存在しません"
                            ),
                            'gross_error_message' => $gross_error_message,
                            'recycle_error_message' => $recycle_error_message,
                            "flag" => "true"
                        );

                        $this->fncReturn($message_array);
                        return;
                    }

                    include_once dirname(__DIR__) . "/Component/tcpdf/rpx_to_pdf.php";
                    include_once dirname(__DIR__) . '/Component/tcpdf/rptMeisai.inc';

                    //-------20141204 fuxiaolin add s
                    if ($_POST['data']['printMark'] == "1") {
                        $rpx_file_names["rptMeisai"] = $data_fields_rptMeisai;
                        $rpx_file_names["rptMeisaiOkyaku"] = $data_fields_rptMeisai;

                    } elseif ($_POST['data']['printMark'] == "2") {
                        //--20151201 YIN DEL S
                        //$rpx_file_names["rptMeisaiOkya2"] = $data_fields_rptMeisai;
                        //--20151201 YIN DEL E
                        $rpx_file_names["rptMeisaiEigyo"] = $data_fields_rptMeisai;
                        //--20151201 YIN INS S
                        $rpx_file_names["rptMeisaiOkya2"] = $data_fields_rptMeisai;
                        //--20151201 YIN INS E
                    } else {
                        $rpx_file_names["rptMeisai"] = $data_fields_rptMeisai;
                        $rpx_file_names["rptMeisaiOkyaku"] = $data_fields_rptMeisai;
                        //--20151201 YIN DEL S
                        //$rpx_file_names["rptMeisaiOkya2"] = $data_fields_rptMeisai;
                        //--20151201 YIN DEL E
                        $rpx_file_names["rptMeisaiEigyo"] = $data_fields_rptMeisai;
                        //--20151201 YIN INS S
                        $rpx_file_names["rptMeisaiOkya2"] = $data_fields_rptMeisai;
                        //--20151201 YIN INS E
                    }
                    //-------20141204 fuxiaolin add e
                    $datas = array();
                    $tmp_data = array();
                    //20221017 YIN UPD S
                    // $tmp_data['data'] = $result['data'];
                    $tmp_data['data'] = $print_data;
                    //20221017 YIN UPD E
                    $tmp_data['mode'] = "0";

                    // データセットの値を設定
                    $datas['rptMeisai'] = $tmp_data;
                    $obj = new \rpx_to_pdf($rpx_file_names, $datas);

                    //帳票パスを返る
                    $pdfPath = $obj->to_pdf();

                    $message_array = array(
                        'flag' => 'true',
                        'msg' => 'true',
                        'gross_error_message' => $gross_error_message,
                        'recycle_error_message' => $recycle_error_message,
                        'reports' => $pdfPath
                    );

                    //20221017 YIN UPD S
                    // $this -> lngOutCnt = count($result['data']);
                    $this->lngOutCnt = count($print_data);
                    //20221017 YIN UPD E

                    $this->fncReturn($message_array);
                }
            }
            $this->fncReturn($message_array);
        } catch (\Exception $e) {
            $message_array = array(
                'flag' => 'false',
                'msg' => $e->getMessage()
            );
            //ロールバック
            $this->FrmOkaiagePrint->Do_rollback();
            // $this->set('result', $message_array);
            // $this->render('fncfrmokaiageprintpreview');

            $this->fncReturn($message_array);
        }

    }

    public function fncShutdown()
    {

        if (isset($this->FrmOkaiagePrint)) {
            unset($this->FrmOkaiagePrint);
        }
        //ログ管理
        if ($this->statuFlag != 0) {
            //intState<>0の場合、ログ管理テーブルに登録
            if ($this->chkFlag == 1) {
                $this->ClsLogControl->fncLogEntry("frmOkaiagePrint", $this->statuFlag, $this->lngOutCnt, $this->chkFlag, $this->strStartDate, $this->strEndDate);
            } else
                if ($this->chkFlag == 2) {
                    $this->strStartDate = "";
                    $this->strEndDate = "";
                    $this->ClsLogControl->fncLogEntry("frmOkaiagePrint", $this->statuFlag, $this->lngOutCnt, $this->chkFlag, $this->strStartDate, $this->strEndDate, $this->strCMN_NO);
                }
        }
    }

}
