<?php
/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD         #ID                         XXXXXX                        FCSDL
 * 20151120           #2273                        BUG                          Yuanjh
 * * --------------------------------------------------------------------------------------------
 */

namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmKeieiSeikaPatternMst;

class FrmKeieiSeikaPatternMstController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    var $errorFlag = FALSE;
    public $FrmKeieiSeikaPatternMst;

    public function index()
    {
        $this->render('index', 'FrmKeieiSeikaPatternMst_layout');
    }

    public function fncBusyoListSel()
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            $this->FrmKeieiSeikaPatternMst = new FrmKeieiSeikaPatternMst();
            $result = $this->FrmKeieiSeikaPatternMst->fncBusyoListSel();

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
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();

            unset($_POST['request']);
        }
        $this->fncReturn($result);
    }

    public function fncPatternNMSel()
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            $this->FrmKeieiSeikaPatternMst = new FrmKeieiSeikaPatternMst();
            $result = $this->FrmKeieiSeikaPatternMst->fncPatternNMSel();

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
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();

            unset($_POST['request']);
        }
        $this->fncReturn($result);
    }

    public function fncPatternListSel()
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            $this->FrmKeieiSeikaPatternMst = new FrmKeieiSeikaPatternMst();
            $result = $this->FrmKeieiSeikaPatternMst->fncPatternListSel();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncGetBusyoMstValue()
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

            if ($postData == "") {
                $result['result'] = FALSE;
                $result['data'] = "ErrorInfo";
            } else {
                // $this -> FrmKeieiSeikaPatternMst = new FrmKeieiSeikaPatternMst();
                $result = $this->ClsComFnc->FncGetBusyoMstValue($postData['Busyo_CD'], $this->ClsComFnc->GS_BUSYOMST, TRUE);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {
                    $result['result'] = TRUE;
                    $result['data'] = $this->ClsComFnc->GS_BUSYOMST;
                }
            }
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
                "frmKeieiSeikaPatternFinally"
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
                $this->FrmKeieiSeikaPatternMst = new FrmKeieiSeikaPatternMst();
                $result = $this->FrmKeieiSeikaPatternMst->Do_conn();

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                //*****登録処理*****
                //トランザクション処理
                $this->FrmKeieiSeikaPatternMst->Do_transaction();

                //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
                $this->errorFlag = TRUE;

                //ﾊﾟﾀｰﾝﾏｽﾀ削除処理
                $result = $this->FrmKeieiSeikaPatternMst->fncDelTbl("HKSPATTERNNAMEMST");

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {
                    //ﾊﾟﾀｰﾝﾏｽﾀ登録処理
                    if (rtrim($this->ClsComFnc->FncNv($postData["patternData"][0]['PATTERN_NM'])) != "") {
                        for ($i = 0; $i < count($postData['patternData']); $i++) {
                            //ﾊﾟﾀｰﾝﾏｽﾀに追加する
                            $result = $this->FrmKeieiSeikaPatternMst->fncInsertPatternMst($i, $postData['patternData'][$i]);

                            if (!$result['result']) {
                                throw new \Exception($result['data']);
                            }
                        }
                    }

                    //ﾊﾟﾀｰﾝﾘｽﾄ削除処理
                    //----20151120   Yuanjh   UPD   S.
                    //$result = $this -> FrmKeieiSeikaPatternMst -> fncDelTbl("HKSPATTERNLISTMST");
                    $arrFlg = $postData["inputFlgs"];
                    for ($i = 0; $i < count($arrFlg); $i++) {
                        if ($arrFlg[$i] <> "E999") {
                            $result = $this->FrmKeieiSeikaPatternMst->fncDelTblhkspatternlistmst($i + 1);
                            if (!$result['result']) {
                                throw new \Exception($result['data']);
                            }
                        }
                    }
                    //----20151120   Yuanjh   UPD   E.
                    //----20151120   Yuanjh   UPD   S.
                    /*
                    if (!$result['result'])
                    {
                        throw new Exception($result['data']);
                    }
                    else
                    {
                        //ﾊﾟﾀｰﾝﾘｽﾄ登録処理
                        for ($i = 0; $i < count($postData['inputDatas']); $i++)
                        {
                            $rowData = $postData['inputDatas'][$i];

                            if ($rowData != "")
                            {
                                for ($j = 0; $j < count($rowData); $j++)
                                {
                                    //$this->log($rowData[$j]['ADD_FLAG'] );  Yuanjh
                                    if ($rowData[$j]['ADD_FLAG'] == "Yes")
                                    {
                                        //追加にﾁｪｯｸが入っているデータのみ登録
                                        $result = $this -> FrmKeieiSeikaPatternMst -> fncInsPatternList($i, $rowData[$j]);

                                        if (!$result['result'])
                                        {
                                            throw new Exception($result['data']);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    */
                    for ($i = 0; $i < count($postData['inputDatas']); $i++) {
                        $rowData = $postData['inputDatas'][$i];

                        if ($rowData != "") {
                            for ($j = 0; $j < count($rowData); $j++) {
                                //$this->log($rowData[$j]['ADD_FLAG'] );  Yuanjh
                                if ($rowData[$j]['ADD_FLAG'] == "Yes") {
                                    //追加にﾁｪｯｸが入っているデータのみ登録
                                    $result = $this->FrmKeieiSeikaPatternMst->fncInsPatternList($i, $rowData[$j]);

                                    if (!$result['result']) {
                                        throw new \Exception($result['data']);
                                    }
                                }
                            }
                        }
                    }
                    //----20151120   Yuanjh   UPD   E.
                    $result['result'] = TRUE;
                    $result['data'] = "";
                }

                //コミット
                $this->FrmKeieiSeikaPatternMst->Do_commit();
                //ﾄﾗﾝｻﾞｸｼｮﾝ終了
                $this->errorFlag = FALSE;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function frmKeieiSeikaPatternFinally()
    {
        //トランザクションがかかったままの場合はロールバックする
        if ($this->errorFlag) {
            $this->FrmKeieiSeikaPatternMst->Do_rollback();
        }
        //DB接続解除
        $this->FrmKeieiSeikaPatternMst->Do_close();
    }

}