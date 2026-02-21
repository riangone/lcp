<?php
/**
 * 説明：
 *
 * システム名　　：ペーパーレスシステム
 * プログラム名　：イメージファイル関連付け
 *
 * @author yinhuaiyu
 * @copyright (GD) (ZM)
 * @package $filename
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         GSDL
 * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\PPRM;

use App\Controller\AppController;
use App\Model\PPRM\PPRM201DCImageRelation;
use App\Model\PPRM\Component\ClsProc;
use App\Model\PPRM\Component\ClsComFncPprm;
use App\Controller\PPRM\PHPTree;

//*******************************************
// * sample controller
//*******************************************
class PPRM201DCImageRelationController extends AppController
{
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    public $autoLayout = TRUE;
    // public $autoRender = false;
    // public $ClsComFnc = '';
    public $ClsComFncPprm;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');
    }
    public $result = array();
    private $strProgramID = "DC_ImageRelation";
    private $BTN_PRINT = "btnPrintView";
    private $BTN_IMAGE = "btnImageIns";
    private $BTN_MEISAI = "btnMeisai";
    private $BTN_IDISP = "btnImageDisp";
    private $BTN_IDEL = "btnImageDel";
    private $Session;

    // ========== 変数 end ==========

    // ========== コントロール start ==========
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //　デフォルトで最初に実行される機能
    public function index()
    {
        $layout = 'PPRM201DCImageRelation_layout';
        $this->render('/PPRM/PPRM201DCImageRelation/index', $layout);
    }

    public function pprm201DCImageRelationLoad()
    {
        $ClsProc = new ClsProc();
        $this->Session = $this->request->getSession();
        $btnEnabled = $ClsProc->SubSetEnabled_OnPageLoad($this->Session->read('Sys_KB'), "PPRM201DCImageRelation", $this->Session->read('login_user'));
        $result['result'] = true;
        $result['data'] = $btnEnabled;
        $this->fncReturn($result);
    }

    // '**********************************************************************
    // '処 理 名：検索を行う
    // '関 数 名：btnSearchClick
    // '引 数 １：なし
    // '戻 り 値：イメージファイル関連付け
    // '処理説明：条件に一致する検索結果を一覧に表示する
    // '**********************************************************************
    public function btnSearchClick()
    {
        $result = array();
        try {
            $this->Session = $this->request->getSession();
            if (isset($_POST['request'])) {
                $postData = $_POST["request"];
                $PPRM201DCImageRelation = new PPRM201DCImageRelation();
                $postData['Sys_KB'] = $this->Session->read('Sys_KB');
                $postData['login_user'] = $this->Session->read('login_user');
                $postData['BusyoCD'] = $this->Session->read('BusyoCD');
                $postData['BTN_PRINT'] = $this->BTN_PRINT;
                $postData['BTN_IMAGE'] = $this->BTN_IMAGE;
                $postData['BTN_MEISAI'] = $this->BTN_MEISAI;
                $this->result = $PPRM201DCImageRelation->fncSelectSearch($postData);
                if (!$this->result['result']) {
                    throw new \Exception($this->result['data']);
                }
                $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($this->result['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];

                $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridDataIndex($this->result["data"], $totalPage, $page, $tmpCount);
                $this->result = $tmpJqgrid;
            } else {
                $this->result = $result;
            }
        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }

        $this->fncReturn($this->result);

    }

    //'**********************************************************************
    //'処 理 名：イメージファイルデータ登録
    //'関 数 名：btnUpdateClick
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：イメージファイルデータ登録
    //'**********************************************************************
    public function btnUpdateClick()
    {
        try {
            $this->Session = $this->request->getSession();
            $postData = $_POST["data"];
            $tenpoCd = $postData['tenpoCD'];
            $HJMNo = $postData['HJMNo'];

            $PPRM201DCImageRelation = new PPRM201DCImageRelation();
            $DB_Conn = $PPRM201DCImageRelation->Do_conn();
            if (!$DB_Conn['result']) {
                throw new \Exception($DB_Conn['data']);
            }

            //トランザクション開始
            $PPRM201DCImageRelation->Do_transaction();
            $blnUpdFlg = TRUE;
            $cntFile = 0;
            foreach ($postData['liArr'] as $value) {
                $this->result = $PPRM201DCImageRelation->checkNUM($tenpoCd, $HJMNo);
                if (!$this->result['result']) {
                    throw new \Exception($this->result['data']);
                }
                if ($this->result['row'] >= 100) {
                    $this->result['data'] = "max";
                    break;
                }
                $value['MachineNM'] = $this->request->clientIp();
                $value['login_user'] = $this->Session->read('login_user');
                $value['BusyoCD'] = $this->Session->read('BusyoCD');
                $value['strProgramID'] = $this->strProgramID;
                $this->result = $PPRM201DCImageRelation->InsertImageFile($value, $tenpoCd, $HJMNo);
                if (!$this->result['result']) {
                    throw new \Exception($this->result['data']);
                }
                $cntFile = $cntFile + 1;
            }
            $PPRM201DCImageRelation->Do_commit();
            $blnUpdFlg = FALSE;
            if ($cntFile == 0) {
                $this->result['data'] = "nodatains";
            }

        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
            if ($blnUpdFlg) {
                $PPRM201DCImageRelation->Do_rollback();
            }
        }
        if (isset($PPRM201DCImageRelation->conn_orl)) {
            $PPRM201DCImageRelation->Do_close();
            unset($PPRM201DCImageRelation->conn_orl);
        }

        $this->fncReturn($this->result);

    }

    //'**********************************************************************
    //'処 理 名：イメージファイルデータ削除
    //'関 数 名：cmdEventClick
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：イメージファイルデータ削除
    //'**********************************************************************
    public function cmdEventClick()
    {
        try {
            $postData = $_POST["data"];
            $strID = $postData['strID'];

            $PPRM201DCImageRelation = new PPRM201DCImageRelation();
            $DB_Conn = $PPRM201DCImageRelation->Do_conn();
            if (!$DB_Conn['result']) {
                throw new \Exception($DB_Conn['data']);
            }
            //20170918 lqs INS S
            //トランザクション開始
            $PPRM201DCImageRelation->Do_transaction();
            $blnUpdFlg = TRUE;
            //20170918 lqs INS E

            $this->result = $PPRM201DCImageRelation->DeleteImageFile($strID);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }

            //20170918 lqs DEL S
            // //トランザクション開始
            // $PPRM201DCImageRelation -> Do_transaction();
            // $blnUpdFlg = TRUE;
            //20170918 lqs DEL E

            $PPRM201DCImageRelation->Do_commit();
            $blnUpdFlg = FALSE;

        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
            if ($blnUpdFlg) {
                $PPRM201DCImageRelation->Do_rollback();
            }
        }
        if (isset($PPRM201DCImageRelation->conn_orl)) {
            $PPRM201DCImageRelation->Do_close();
            unset($PPRM201DCImageRelation->conn_orl);
        }

        $this->fncReturn($this->result);

    }

    public function subMeisaiDisp()
    {
        $result = array();
        try {
            if (isset($_POST['request'])) {
                $postData = $_POST["request"];
                $PPRM201DCImageRelation = new PPRM201DCImageRelation();
                $this->result = $PPRM201DCImageRelation->fncSelectSearch2($postData);
                if (!$this->result['result']) {
                    throw new \Exception($this->result['data']);
                }
                $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($this->result['data']);

                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];

                $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridDataIndex($this->result["data"], $totalPage, $page, $tmpCount);
                $this->result = $tmpJqgrid;
            } else {
                $this->result = $result;
            }
        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }

        $this->fncReturn($this->result);

    }

    public function getTreeView()
    {
        try {
            //path
            $path = "img";
            //id
            $id = 1;
            $dataArr = array();

            $this->clearData($path, $id, $dataArr, '0');
            global $r;

            $PHPTree = new PHPTree();
            $array = $PHPTree->makeTree((array) $r, array('expanded' => true));

            $result['path'] = $path;
            $result['data'] = $array;
            $message_array = array(
                'flag' => 'true',
                'msg' => 'true',
                'reports' => $result
            );

        } catch (\Exception $e) {
            $message_array = array(
                'flag' => 'false',
                'msg' => $e->getMessage()
            );

        }
        $this->fncReturn($message_array);
    }

    public function clearData($path, $id, $dataArr, $parentId)
    {

        if (is_dir($path)) {

            $file = scandir($path);
            global $r;
            $r = array();
            foreach ($file as $value) {
                if ($value != '.' && $value != '..') {
                    $data = array(
                        "id" => count($r) + 1,
                        "text" => $value,
                        "parent_id" => $parentId
                    );
                    if ($r) {
                        array_push($dataArr, $data);
                        array_push($r, $data);
                    } else {
                        array_push($dataArr, $data);
                        $r = $dataArr;
                    }

                    $id = $id + 1;
                }
            }

            foreach ($dataArr as $value) {
                $text = $value['text'];
                if (substr($text, -4) != '.jpg') {
                    $parentId = $value['id'];
                    $pathTemp = $path;
                    $path = $pathTemp . '/' . $text;

                    $this->clearData($path, $id, $dataArr, $parentId);
                    $path = $pathTemp;
                }
            }
        }
    }

    public function getButton()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
        );
        $tenpoCD = $_POST["data"]["tenpoCD"];
        try {
            $this->Session = $this->request->getSession();
            $ClsProc = new ClsProc();
            $blnIdisp = $ClsProc->FncCheckEnabled_Control($this->Session->read('Sys_KB'), "PPRM201DCImageRelation", $this->Session->read('login_user'), $tenpoCD, $this->BTN_IDISP);
            $blnIdel = $ClsProc->FncCheckEnabled_Control($this->Session->read('Sys_KB'), "PPRM201DCImageRelation", $this->Session->read('login_user'), $tenpoCD, $this->BTN_IDEL);
            $blnarr = array(
                "blnIdisp" => $blnIdisp,
                "blnIdel" => $blnIdel
            );
            $result['result'] = true;
            $result['data'] = $blnarr;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    // //'**********************************************************************
    // //'処 理 名：店舗名取得（関数）
    // //'関 数 名：FncGetBusyoNM
    // //'処理説明：値変更時に店舗名を取得する
    // //'**********************************************************************
    // public function FncGetBusyoNM()
    // {
    // 	$result = array(
    // 		'result' => 'false',
    // 		'data' => 'ErrorInfo'
    // 	);
    // 	$postData = $_POST["data"]["request"];
    // 	try {
    // 		$this->ClsComFncPprm = new ClsComFncPprm();
    // 		$result = $this->ClsComFncPprm->FncGetBusyoMstValue_ppr($postData["txtTenpoCD"], TRUE);
    // 		if (!$result['result']) {
    // 			throw new Exception($result['data']);
    // 		}
    // 		$result['data'] = $this->ClsComFnc->FncNv($result['data'][0]);
    // 	} catch (Exception $e) {
    // 		$result['result'] = FALSE;
    // 		$result['data'] = $e->getMessage();
    // 	}
    // 	$this->fncReturn($result);
    // }

    //20170908 ZHANGXIAOLEI INS S
    //'**********************************************************************
    //'処 理 名：全部店舗名取得（関数）
    //'関 数 名：FncGetBusyoNM
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：値変更時に店舗名を取得する
    //'**********************************************************************
    public function fncGetALLBusyoNM()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
        );
        try {
            $this->ClsComFncPprm = new ClsComFncPprm();
            $result = $this->ClsComFncPprm->FncGetALLBusyoMstPpr();
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //20170908 ZHANGXIAOLEI INS E
}
