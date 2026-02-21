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
 * YYYYMMDD           #ID                          XXXXXX                           GSDL　　　　　　　　
 * * --------------------------------------------------------------------------------------------
 */

namespace App\Controller\PPRM;

use App\Controller\AppController;
use App\Model\PPRM\PPRM802MenuAuthMstMnt;

//*******************************************
// * sample controller
//*******************************************
class PPRM802MenuAuthMstMntController extends AppController
{
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    public $autoLayout = TRUE;
    // public $autoRender = false;
    private $Session;
    public $PPRM802MenuAuthMstMnt;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');
    }
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

    //　デフォルトで最初に実行される機能
    public function index()
    {
        $layout = 'PPRM802MenuAuthMstMnt_layout';
        $this->render('/PPRM/PPRM802MenuAuthMstMnt/index', $layout);
    }

    //'***********************************************************************
    //'処 理 名：左側のjqGridテーブルを取得する
    //'関 数 名：getLjqGridData
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：左側のjqGridテーブルを取得する
    //'***********************************************************************
    public function getLjqGridData()
    {
        try {
            //20170907 ZHANGXIAOLEI DEL S
            // if (isset($_POST['request']))
            // {
            //20170907 ZHANGXIAOLEI DEL E
            $this->PPRM802MenuAuthMstMnt = new PPRM802MenuAuthMstMnt();
            $this->Session = $this->request->getSession();
            $sys_kb = $this->Session->read('Sys_KB');
            $this->result = $this->PPRM802MenuAuthMstMnt->getLjqGridData($sys_kb);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }

            $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($this->result['data']);
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];

            $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridData($this->result["data"], $totalPage, $page, $tmpCount);
            $this->result = $tmpJqgrid;
            //20170907 ZHANGXIAOLEI DEL S
            // }
            // else
            // {
            // $this -> result = $result;
            // }
            //20170907 ZHANGXIAOLEI DEL E
        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }
        $this->fncReturn($this->result);
    }

    //'***********************************************************************
    //'処 理 名：選択/追加ボタンのイベント
    //'関 数 名：getRjqGridData
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：取得データを権限管理ﾃｰﾌﾞﾙの生成
    //'***********************************************************************
    public function getRjqGridData()
    {
        $result = array();
        try {
            if (isset($_POST['request'])) {
                $strFlg = $_POST['request']['strFlg'];
                $this->Session = $this->request->getSession();
                $sys_kb = $this->Session->read('Sys_KB');

                if ($strFlg == "1") {
                    //選択の場合
                    $PTNID = $_POST['request']['PTNID'];

                    $this->PPRM802MenuAuthMstMnt = new PPRM802MenuAuthMstMnt();

                    $this->result = $this->PPRM802MenuAuthMstMnt->gvRights_SelectedIndexChanged($PTNID, $sys_kb);
                } else {
                    //追加の場合
                    $this->PPRM802MenuAuthMstMnt = new PPRM802MenuAuthMstMnt();
                    $this->result = $this->PPRM802MenuAuthMstMnt->btnAdd_click($sys_kb);
                }

                if (!$this->result['result']) {
                    throw new \Exception($this->result['data']);
                }

                $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($this->result['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];

                $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridData($this->result["data"], $totalPage, $page, $tmpCount);
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

    //'***********************************************************************
    //'処 理 名：登録処理
    //'関 数 名：btnLogin_click
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：登録処理
    //'***********************************************************************
    public function btnLoginClick()
    {
        try {
            $txtRightsID = $_POST['data']['txtRightsID'];
            $txtRightsName = $_POST['data']['txtRightsName'];
            $txtRightsIDEnabled = $_POST['data']['txtRightsIDEnabled'];
            $arr = isset($_POST['data']['arr']) ? $_POST['data']['arr'] : '';

            //DB接続
            $this->PPRM802MenuAuthMstMnt = new PPRM802MenuAuthMstMnt();
            $DB_Conn = $this->PPRM802MenuAuthMstMnt->Do_conn();
            if (!$DB_Conn['result']) {
                throw new \Exception($DB_Conn['data']);
            }

            //メニュー権限名称ﾃﾞｰﾀの取得
            $result1 = $this->getPatternID($txtRightsID);
            if (!$result1['result']) {
                throw new \Exception($result1['data']);
            }

            //存在チェックを行う
            //画面項目No7(権限ID)が活性の場合(新規)
            if ($txtRightsIDEnabled == 'true' && $result1['row'] > 0) {
                $result1['result1'] = "E0005_PPRM";
                $this->result = $result1;
                $this->fncReturn($this->result);
                return;
            } else
                if ($txtRightsIDEnabled == 'false' && $result1['row'] < 0) {
                    $result1['result1'] = "W0004_PPRM";
                    $this->result = $result1;
                    $this->fncReturn($this->result);
                    return;
                }

            //トランザクション開始
            $this->PPRM802MenuAuthMstMnt->Do_transaction();

            //登録処理を行う
            if ($result1['row'] > 0) {
                $result2 = $this->UpdateHPATTERNMST($txtRightsID, $txtRightsName);
            } else {
                $result2 = $this->InsertHPATTERNMST($txtRightsID, $txtRightsName);
            }

            if (!$result2['result']) {
                throw new \Exception($result2['data']);
            }

            if ($result2['number_of_rows'] < 0) {
                return;
            }

            //メニュー権限管理マスタへの登録を行う
            //削除処理を行う
            $result3 = $this->DeleteHMENUKANRIPATTERN($txtRightsID);
            if (!$result3['result']) {
                throw new \Exception($result3['data']);
            }

            if ($result3['number_of_rows'] < 0) {
                return;
            }

            //権限管理ﾃｰﾌﾞﾙ_追加にチェックが入っている場合
            if ($arr != "" && $arr != null && isset($arr)) {
                for ($i = 0; $i < count((array) $arr); $i++) {
                    $PRONO = $arr[$i]['PRO_NO'];
                    $CREATEDATE = $this->ClsComFnc->FncNv($arr[$i]['CREATE_DATE']);

                    $result4 = $this->InsertHMENUKANRIPATTERN($txtRightsID, $PRONO, $CREATEDATE);
                    if (!$result4['result']) {
                        throw new \Exception($result4['data']);
                    }

                    if ($result4['number_of_rows'] < 0) {
                        return;
                    }
                }
            }

            //コミット
            $this->PPRM802MenuAuthMstMnt->Do_commit();

            $result1['result2'] = true;
            $this->result = $result1;
        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
            $this->PPRM802MenuAuthMstMnt->Do_rollback();
        }
        if (isset($this->PPRM802MenuAuthMstMnt->conn_orl)) {
            $this->PPRM802MenuAuthMstMnt->Do_close();
            unset($this->PPRM802MenuAuthMstMnt->conn_orl);
        }
        $this->fncReturn($this->result);
    }

    //'***********************************************************************
    //'処 理 名：メニュー権限名称ﾃﾞｰﾀの取得
    //'関 数 名：getPatternID
    //'引 数   ：$txtRightsID
    //'戻 り 値：$result
    //'処理説明：登録処理(メニュー権限名称ﾃﾞｰﾀの取得)
    //'***********************************************************************
    public function getPatternID($txtRightsID)
    {
        try {
            $this->Session = $this->request->getSession();
            $sys_kb = $this->Session->read('Sys_KB');
            $result = $this->PPRM802MenuAuthMstMnt->getPatternID($txtRightsID, $sys_kb);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }
        return $result;
    }

    //'***********************************************************************
    //'処 理 名：登録処理を行う
    //'関 数 名：UpdateHPATTERNMST
    //'引 数   ：$txtRightsID, $txtRightsName
    //'戻 り 値：$result
    //'処理説明：登録処理(登録処理を行う)
    //'***********************************************************************
    public function UpdateHPATTERNMST($txtRightsID, $txtRightsName)
    {
        try {
            $this->Session = $this->request->getSession();
            $session['Sys_KB'] = $this->Session->read('Sys_KB');
            $session['login_user'] = $this->Session->read('login_user');
            $session['MachineNM'] = $this->request->clientIp();
            $result = $this->PPRM802MenuAuthMstMnt->UpdateHPATTERNMST($txtRightsID, $txtRightsName, $session);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }
        return $result;
    }

    //'***********************************************************************
    //'処 理 名：登録処理を行う
    //'関 数 名：InsertHPATTERNMST
    //'引 数   ：$txtRightsID, $txtRightsName
    //'戻 り 値：$result
    //'処理説明：登録処理(登録処理を行う)
    //'***********************************************************************
    public function InsertHPATTERNMST($txtRightsID, $txtRightsName)
    {
        try {
            $this->Session = $this->request->getSession();
            $session['Sys_KB'] = $this->Session->read('Sys_KB');
            $session['login_user'] = $this->Session->read('login_user');
            $session['MachineNM'] = $this->request->clientIp();
            $result = $this->PPRM802MenuAuthMstMnt->InsertHPATTERNMST($txtRightsID, $txtRightsName, $session);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }
        return $result;
    }

    //'***********************************************************************
    //'処 理 名：削除処理を行う
    //'関 数 名：DeleteHMENUKANRIPATTERN
    //'引 数   ：$txtRightsID
    //'戻 り 値：$result
    //'処理説明：登録処理(削除処理を行う)
    //'***********************************************************************
    public function DeleteHMENUKANRIPATTERN($txtRightsID)
    {
        try {
            $this->Session = $this->request->getSession();
            $sys_kb = $this->Session->read('Sys_KB');
            $result = $this->PPRM802MenuAuthMstMnt->DeleteHMENUKANRIPATTERN($txtRightsID, $sys_kb);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }
        return $result;
    }

    //'***********************************************************************
    //'処 理 名：権限管理ﾃｰﾌﾞﾙ_追加にチェックが入っている場合
    //'関 数 名：InsertHMENUKANRIPATTERN
    //'引 数   ：$txtRightsID, $PRONO, $KBN, $PRONM, $CREATEDATE
    //'戻 り 値：$result
    //'処理説明：登録処理(権限管理ﾃｰﾌﾞﾙ_追加にチェックが入っている場合)
    //'***********************************************************************
    public function InsertHMENUKANRIPATTERN($txtRightsID, $PRONO, $CREATEDATE)
    {
        try {
            $this->Session = $this->request->getSession();
            $session['Sys_KB'] = $this->Session->read('Sys_KB');
            $session['login_user'] = $this->Session->read('login_user');
            $session['MachineNM'] = $this->request->clientIp();
            $result = $this->PPRM802MenuAuthMstMnt->InsertHMENUKANRIPATTERN($txtRightsID, $PRONO, $CREATEDATE, $session);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }
        return $result;
    }

    //'***********************************************************************
    //'処 理 名：削除処理
    //'関 数 名：btnDelete_click
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：削除処理
    //'***********************************************************************
    public function btnDeleteClick()
    {
        try {
            $txtRightsID = $_POST['data']['txtRightsID'];

            //DB接続
            $this->PPRM802MenuAuthMstMnt = new PPRM802MenuAuthMstMnt();
            $DB_Conn = $this->PPRM802MenuAuthMstMnt->Do_conn();
            if (!$DB_Conn['result']) {
                throw new \Exception($DB_Conn['data']);
            }

            //トランザクション開始
            $this->PPRM802MenuAuthMstMnt->Do_transaction();

            //メニュー権限名称マスタを削除する
            $result1 = $this->DeleteHPATTERNMST($txtRightsID);
            if (!$result1['result']) {
                throw new \Exception($result1['data']);
            }

            if ($result1['number_of_rows'] < 0) {
                return;
            }

            //メニュー権限管理マスタを削除する
            $result2 = $this->DeleteHMENUKANRIPATTERN($txtRightsID);
            if (!$result2['result']) {
                throw new \Exception($result2['data']);
            }

            if ($result2['number_of_rows'] < 0) {
                return;
            }

            //コミット
            $this->PPRM802MenuAuthMstMnt->Do_commit();

            $this->result['result'] = TRUE;
        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
            $this->PPRM802MenuAuthMstMnt->Do_rollback();
        }
        if (isset($this->PPRM802MenuAuthMstMnt->conn_orl)) {
            $this->PPRM802MenuAuthMstMnt->Do_close();
            unset($this->PPRM802MenuAuthMstMnt->conn_orl);
        }
        $this->fncReturn($this->result);
    }

    //'***********************************************************************
    //'処 理 名：メニュー権限管理マスタを削除する
    //'関 数 名：DeleteHPATTERNMST
    //'引 数   ：$txtRightsID
    //'戻 り 値：$result
    //'処理説明：削除処理(メニュー権限名称マスタを削除する)
    //'***********************************************************************
    public function DeleteHPATTERNMST($txtRightsID)
    {
        try {
            $this->Session = $this->request->getSession();
            $sys_kb = $this->Session->read('Sys_KB');
            $result = $this->PPRM802MenuAuthMstMnt->DeleteHPATTERNMST($txtRightsID, $sys_kb);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }
        return $result;
    }

}
