<?php
/**
 * 説明：
 *
 *
 * @author fanzhengzhou
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\R4\KRSS;

use App\Controller\AppController;
use App\Model\R4\KRSS\FrmSimLineMstNew;

class FrmSimLineMstNewController extends AppController
{
    public $autoLayout = TRUE;
    private $ClsComFnc;
    private $Session;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');
    }

    public $FrmSimLineMstNew = "";
    public $Do_conn = array();
    public $Do_Excute = array();
    //　デフォルトで最初に実行される機能
    public function index()
    {
        $this->render('index', 'FrmSimLineMstNew_layout');
    }

    //**********************************************************************
    //処 理 名：フォームロード
    //関 数 名：FrmSimLineMstNew_Load
    //引    数：無し
    //戻 り 値：$result
    //処理説明：初期処理
    //**********************************************************************
    public function frmSimLineMstNewLoad()
    {
        $result = array();
        try {
            $this->FrmSimLineMstNew = new FrmSimLineMstNew();
            $result = $this->FrmSimLineMstNew->select_line_new();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //**********************************************************************
    //処 理 名：科目から集計一覧表示/科目以外から集計
    //関 数 名：showkamoku
    //引    数：
    //戻 り 値：$result
    //処理説明：科目から集計一覧表示/科目以外から集計
    //**********************************************************************
    public function showkamoku()
    {
        $result = array();
        $result1 = array();
        $result2 = array();
        try {
            $line_no = $_POST['data'];
            $this->FrmSimLineMstNew = new FrmSimLineMstNew();
            $result1 = $this->FrmSimLineMstNew->showkamoku($line_no);
            if (!$result1['result']) {
                throw new \Exception($result1['data']);
            }
            $result2 = $this->FrmSimLineMstNew->show_src_viewname($line_no);
            if (!$result2['result']) {
                throw new \Exception($result2['data']);
            }
            $result['result'] = true;
            $result['data']['kamoku'] = $result1['data'];
            $result['data']['viewname'] = $result2['data'];
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //**********************************************************************
    //処 理 名：マスタ存在チェック
    //関 数 名：mastercheck
    //引    数：
    //戻 り 値：$result
    //処理説明：マスタ存在チェック
    //**********************************************************************
    public function mastercheck()
    {
        $result = array();
        try {
            $this->FrmSimLineMstNew = new FrmSimLineMstNew();
            $result = $this->FrmSimLineMstNew->selectkamokmaster();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //**********************************************************************
    //処 理 名：更新
    //関 数 名：update
    //引    数：
    //戻 り 値：$result
    //処理説明：更新
    //**********************************************************************
    public function update()
    {
        $result = array();
        $lineArr = array();
        $kamokuArr = array();
        try {
            if (isset($_POST['data']['lineArr'])) {
                $lineArr = $_POST['data']['lineArr'];
            }
            if (isset($_POST['data']['kamokuArr'])) {
                $kamokuArr = $_POST['data']['kamokuArr'];
            }
            $selLineNo = $_POST['data']['selLineNo'];
            $this->Session = $this->request->getSession();
            $UPD_SYA_CD = $this->Session->read('login_user');
            $UPD_CLT_NM = $this->request->clientIp();
            $this->FrmSimLineMstNew = new FrmSimLineMstNew();
            $this->Do_conn = $this->FrmSimLineMstNew->Do_conn();
            if (!$this->Do_conn['result']) {
                throw new \Exception($this->Do_conn['data']);
            }
            $this->FrmSimLineMstNew->Do_transaction();

            if (count($lineArr) > 0) {
                foreach ($lineArr as $value) {
                    //新ラインマスタのデータを検索する
                    $selLine = $this->FrmSimLineMstNew->select_line($value['LINE_NO']);
                    if (!$selLine['result']) {
                        throw new \Exception($selLine['data']);
                    }
                    if (count((array) $selLine['data']) > 0) {
                        //存在する場合  新ラインマスタの内容と 画面の内容が異なる場合、更新処理を行う
                        if (isset($_POST['data']['kamokuOutside'])) {
                            if ($value['LINE_NO'] == $selLineNo) {
                                $kamokuOutside = $_POST['data']['kamokuOutside'];
                                $this->Do_Excute = $this->FrmSimLineMstNew->update_line($value, $kamokuOutside, $UPD_SYA_CD, $UPD_CLT_NM, TRUE);
                            } else {
                                $this->Do_Excute = $this->FrmSimLineMstNew->update_line($value, "", $UPD_SYA_CD, $UPD_CLT_NM, FALSE);
                            }
                        } else {
                            $this->Do_Excute = $this->FrmSimLineMstNew->update_line($value, "", $UPD_SYA_CD, $UPD_CLT_NM, FALSE);
                        }

                        if (!$this->Do_Excute['result']) {
                            throw new \Exception($this->Do_Excute['data']);
                        }
                    } else {
                        //存在しない場合  新ラインマスタに新規追加する

                        if (isset($_POST['data']['kamokuOutside'])) {
                            if ($value['LINE_NO'] == $selLineNo) {
                                $kamokuOutside = $_POST['data']['kamokuOutside'];
                                $this->Do_Excute = $this->FrmSimLineMstNew->insert_line($value, $kamokuOutside, $UPD_SYA_CD, $UPD_CLT_NM, TRUE);
                            } else {
                                $this->Do_Excute = $this->FrmSimLineMstNew->insert_line($value, "", $UPD_SYA_CD, $UPD_CLT_NM, FALSE);
                            }
                        } else {
                            $this->Do_Excute = $this->FrmSimLineMstNew->insert_line($value, "", $UPD_SYA_CD, $UPD_CLT_NM, FALSE);
                        }
                        if (!$this->Do_Excute['result']) {
                            throw new \Exception($this->Do_Excute['data']);
                        }
                    }
                }
                //---20150522 fan add s.
                $this->Do_Excute = $this->FrmSimLineMstNew->select_line_all();
                if (!$this->Do_Excute['result']) {
                    throw new \Exception($this->Do_Excute['data']);
                }
                $allLineNO = $this->Do_Excute['data'];
                $tmpflag = FALSE;
                foreach ((array) $allLineNO as $value1) {
                    $tmpflag = FALSE;
                    foreach ($lineArr as $vaule2) {
                        if ($value1['LINE_NO'] == $vaule2['LINE_NO']) {
                            $tmpflag = TRUE;
                            break;
                        }
                    }
                    if ($tmpflag == FALSE) {
                        $this->Do_Excute = $this->FrmSimLineMstNew->delete_notexist_line($value1['LINE_NO']);
                        if (!$this->Do_Excute['result']) {
                            throw new \Exception($this->Do_Excute['data']);
                        }
                    }
                }
                //---20150522 fan add e.
            }

            if (count($kamokuArr) > 0) {
                foreach ($kamokuArr as $value1) {
                    //新科目ラインマスタのデータを検索する
                    $selkamoku = $this->FrmSimLineMstNew->select_kamoku($value1, $selLineNo);
                    if (!$selkamoku['result']) {
                        throw new \Exception($selkamoku['data']);
                    }
                    if (count((array) $selkamoku['data']) == 0) {
                        //存在しない場合  新科目ラインマスタに新規追加する
                        $this->Do_Excute = $this->FrmSimLineMstNew->insert_kamoku($value1, $selLineNo, $UPD_SYA_CD, $UPD_CLT_NM);
                        if (!$this->Do_Excute['result']) {
                            throw new \Exception($this->Do_Excute['data']);
                        }
                    } else {
                        //存在する場合
                        //新ライン科目マスタの内容と 画面の内容が異なる場合、更新処理を行う
                        $this->Do_Excute = $this->FrmSimLineMstNew->update_kamoku($value1, $selLineNo, $UPD_SYA_CD, $UPD_CLT_NM);
                        if (!$this->Do_Excute['result']) {
                            throw new \Exception($this->Do_Excute['data']);
                        }
                    }
                }
            }
            //---20150522 fan add s.
            $this->Do_Excute = $this->FrmSimLineMstNew->select_kamoku_all($selLineNo);
            if (!$this->Do_Excute['result']) {
                throw new \Exception($this->Do_Excute['data']);
            }
            $allKamoku = $this->Do_Excute['data'];
            $tmpflag = FALSE;
            foreach ((array) $allKamoku as $value1) {
                $tmpflag = FALSE;
                foreach ($kamokuArr as $vaule2) {
                    if ($value1['KAMOK_CD'] == $vaule2['KAMOK_CD'] && $value1['HIMOK_CD'] == $vaule2['HIMOK_CD']) {
                        $tmpflag = TRUE;
                        break;
                    }
                }
                if ($tmpflag == FALSE) {
                    $this->Do_Excute = $this->FrmSimLineMstNew->delete_notexist_kamoku($value1);
                    if (!$this->Do_Excute['result']) {
                        throw new \Exception($this->Do_Excute['data']);
                    }
                }
            }
            //---20150522 fan add e.

            $this->FrmSimLineMstNew->Do_commit();
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $this->FrmSimLineMstNew->Do_rollback();
        }
        $this->FrmSimLineMstNew->Do_close();
        $this->fncReturn($result);
    }
}
