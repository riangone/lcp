<?php
namespace App\Controller\R4\R4K;

/**
 * 説明：
 *
 *
 * @author FCS
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20151208           #2089                                                         li
 * --------------------------------------------------------------------------------------------
 */
use App\Controller\AppController;
use App\Model\R4\Component\ClsComFnc;
use App\Model\R4\R4K\FrmPattern;

class FrmPatternController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    private $FrmPattern;
    private $errorFlag;
    // public $ClsComFnc = '';
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }

    public function index()
    {
        $this->render('index', 'FrmPattern_layout');
    }

    public function fncPatternSelect()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            if (isset($_POST['request'])) {
                $postData = $_POST['request'];
            }

            if ($postData['STYLE_ID'] != 'load') {
                $this->FrmPattern = new FrmPattern();
                $result = $this->FrmPattern->fncPatternSelect($postData["STYLE_ID"]);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {
                    $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($result['data']);
                    $page = $tmpJqgridShow['page'];
                    $totalPage = $tmpJqgridShow['totalPage'];
                    $tmpCount = (int) $tmpJqgridShow['count'];

                    $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridData($result["data"], $totalPage, $page, $tmpCount);

                    unset($_POST['request']);
                    $result = $tmpJqgrid;
                }
            } else {
                $result['result'] = TRUE;
                $result['data'] = "";
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();

            unset($_POST['request']);
        }

        $this->fncReturn($result);
    }

    public function fncPatternListSelect()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            if (isset($_POST['request'])) {
                $postData = $_POST['request'];
            }

            if ($postData['STYLE_ID'] != 'load') {
                $this->FrmPattern = new FrmPattern();
                $result = $this->FrmPattern->fncPatternListSelect($postData["STYLE_ID"]);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {
                    $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($result['data']);
                    $page = $tmpJqgridShow['page'];
                    $totalPage = $tmpJqgridShow['totalPage'];
                    $tmpCount = (int) $tmpJqgridShow['count'];

                    $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridData($result["data"], $totalPage, $page, $tmpCount);

                    unset($_POST['request']);
                    $result = $tmpJqgrid;
                }
            } else {
                $result['result'] = TRUE;
                $result['data'] = "";
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();

            unset($_POST['request']);
        }

        $this->fncReturn($result);
    }

    public function fncHMENUSTYLESelect()
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            $this->FrmPattern = new FrmPattern();
            $result = $this->FrmPattern->fncHMENUSTYLESelect();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncPatternListSel()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            if (isset($_POST['data'])) {
                $postData = $_POST['data'];
            }
            //Ⅰ.HMENUKANRIPATTERNに登録されているﾃﾞｰﾀを抽出する
            for ($i = 0; $i < count((array) $postData['patarn_data']); $i++) {
                $this->FrmPattern = new FrmPattern();
                $result = $this->FrmPattern->fncPatternListSel($postData['STYLE_ID'], $postData['patarn_data'][$i]['PATTERN_ID']);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                // $returnData[$postData['patarn_data'][$i]['PATTERN_ID']] = $result['data'];
                $returnData[$i] = $result['data'];
            }

            $result['data'] = $returnData;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncDeleteUpdataMst()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        register_shutdown_function(
            array(
                $this,
                "frmPattern_finally"
            )
        );

        try {
            if (isset($_POST['data'])) {
                $postData = $_POST['data'];
            }

            if ($postData == "") {
                $result = array(
                    'result' => FALSE,
                    'data' => 'ErrorInfo'
                );
            } else {
                $this->FrmPattern = new FrmPattern();
                //DB接続
                $result = $this->FrmPattern->Do_conn();

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                //*****登録処理*****
                //データベースのトランザクション処理を開始する。
                $this->FrmPattern->Do_transaction();

                //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
                $this->errorFlag = TRUE;
                //--- 20151208 LI INS S
                $ClsComFnc = new ClsComFnc();
                //--- 20151208 LI INS E
                //HPATTERNMSTの削除処理を行う
                $result = $this->FrmPattern->fncDelTbl("HPATTERNMST", $postData['selectIndex']);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {
                    $UPDCLTNM = $this->request->clientIp();

                    //HPATTERNMSTの登録処理を行う
                    for ($i = 0; $i < count((array) $postData['patternData']); $i++) {
                        //ﾊﾟﾀｰﾝﾏｽﾀに追加する
                        //--- 20151208 LI UPD S
                        // $result = $this -> FrmPattern -> fncInsertPatternMst($postData['patternData'][$i], $postData['selectIndex'], $UPDCLTNM);
                        $result = $this->FrmPattern->fncInsertPatternMst($postData['patternData'][$i], $postData['selectIndex'], $UPDCLTNM, $ClsComFnc);
                        //--- 20151208 LI UPD E

                        if (!$result['result']) {
                            throw new \Exception("データベースエラーが発生します。");
                        }
                    }

                    //HMENUKANRIPATTERNの削除処理を行う
                    $result = $this->FrmPattern->fncDelTbl("HMENUKANRIPATTERN", $postData['selectIndex']);

                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    } else {
                        if (isset($postData['inputDatas'])) {
                            //ﾊﾟﾀｰﾝﾘｽﾄ登録処理
                            foreach ($postData['inputDatas'] as $key => $value) {
                                $rowData = $postData['inputDatas'][$key];
                                $rowDataPattern = $postData['patternData'][$key];

                                if ($rowData != "") {
                                    for ($j = 0; $j < count((array) $rowData); $j++) {
                                        if (strtoupper($rowData[$j]['ADD_FLAG']) == "YES") {
                                            //HMENUKANRIPATTERNの登録処理を行う
                                            //--- 20151208 LI UPD S
                                            // $result = $this -> FrmPattern -> fncInsPatternList($rowDataPattern['PATTERN_ID'], $rowData[$j], $postData['selectIndex'], $UPDCLTNM);
                                            $result = $this->FrmPattern->fncInsPatternList($rowDataPattern['PATTERN_ID'], $rowData[$j], $postData['selectIndex'], $UPDCLTNM, $ClsComFnc);
                                            //--- 20151208 LI UPD E

                                            if (!$result['result']) {
                                                throw new \Exception($result['data']);
                                            }
                                        }
                                    }
                                }
                            }
                            for ($i = 0; $i < count((array) $postData['inputDatas']); $i++) {

                            }
                        }
                    }

                    $result['result'] = TRUE;
                    $result['data'] = "";
                }

                //コミット
                $this->FrmPattern->Do_commit();
                //ﾄﾗﾝｻﾞｸｼｮﾝ終了
                $this->errorFlag = FALSE;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function frmPattern_finally()
    {
        //トランザクションがかかったままの場合はロールバックする
        if ($this->errorFlag) {
            $this->FrmPattern->Do_rollback();
        }
        //DB接続解除
        $this->FrmPattern->Do_close();
    }

}