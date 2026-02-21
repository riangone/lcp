<?php
/**
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug             内容                               担当
 * YYYYMMDD           #ID                     XXXXXX                            FCSDL
 * 20150717           #1965                   原価マスタを表示するときに時間がかかる        ZHENGHUIYUN
 * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmGenka;

class FrmGenkaController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $result;
    public $Do_conn;
    public $FrmGenka;
    public $blnTranFlg;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsFncLog');
    }
    public function index()
    {
        $this->render('index', 'FrmGenka_layout');
    }

    /*
           ***********************************************************************
           '処 理 名：データグリッドの再表示
           '関 数 名：subSpreadReShow
           '引    数：無し
           '戻 り 値：無し
           '処理説明：データグリッドを再表示する
           '**********************************************************************
           */
    public function fncFrmGenkaSelect()
    {
        try {

            if (!isset($_POST['request'])) {
                $postData = "";
            } else {
                $postData = $_POST['request'];
            }

            //モデルの仕様するクラスを定義
            $this->FrmGenka = new FrmGenka();
            //モデルクラスのselect処理を呼出し
            //20150717 #1965 zhenghuiyun upd s
            // $this -> result = $this -> FrmGenka -> fncFrmGenkaSelect($postData, "");
            $this->result = $this->FrmGenka->fncFrmGenkaSelectCnt($postData, "");
            //20150717 #1965 zhenghuiyun upd e
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }

            //20150717 #1965 zhenghuiyun upd s
            // $tmpJqgridShow = $this -> ClsComFnc -> FncCreateJqGridShow($this -> result['data']);
            $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($this->result['data'], true);
            //20150717 #1965 zhenghuiyun upd e

            $sortstr = $tmpJqgridShow['sortStr'];
            $start = $tmpJqgridShow['start'];
            $limit = $tmpJqgridShow['limit'];
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];
            for ($ii = 0; $ii < count((array) $this->result['data']); $ii++) {
                foreach ((array) $this->result['data'][$ii] as $key => $value) {
                    $this->result['data'][$ii][$key] = is_string($this->ClsComFnc->fncNv($value)) ? trim($this->ClsComFnc->fncNv($value)) : '';
                }
            }
            //20150717 #1965 zhenghuiyun upd s
            // $this -> result = $this -> FrmGenka -> fncFrmGenkaSelect($postData, $sortstr);
            $this->result = $this->FrmGenka->fncFrmGenkaSelect($postData, $sortstr, $start, $limit);
            //20150717 #1965 zhenghuiyun upd e
            $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridData($this->result["data"], $totalPage, $page, $tmpCount);

            $this->result = $tmpJqgrid;
        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
        }
        $this->fncReturn($this->result);
    }

    public function fncUpdate()
    {
        //print_r($_POST['data']);
        try {
            //モデルの仕様するクラスを定義
            $this->FrmGenka = new FrmGenka();

            $this->Do_conn = $this->FrmGenka->Do_conn();
            if (!$this->Do_conn['result']) {
                throw new \Exception($this->Do_conn['data']);
            }
            //トランザクション開始
            $this->FrmGenka->Do_transaction();
            //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
            $this->blnTranFlg = True;
            //---

            //20150717 #1965 zhenghuiyun upd s
            // $this -> result = $this -> FrmGenka -> fncDelete();
            // if (!$this -> result['result'])
            // {
            // throw new Exception($this -> result['data']);
            // }

            foreach (json_decode($_POST['data']['old'], true) as $value) {
                if ($value['TOA_NAME'] != "" && $value['HTA_PRC'] != "") {
                    // echo $this -> FrmGenka -> fncDeleteByKey_sql($value);
                    $this->result = $this->FrmGenka->fncDeleteByKey($value);
                    if (!$this->result['result']) {
                        throw new \Exception($this->result['data']);
                    }
                }
            }
            //20150717 #1965 zhenghuiyun upd e

            //20150717 #1965 zhenghuiyun upd s
            // foreach ($_POST['data'] as $key => $value)
            foreach (json_decode($_POST['data']['new'], true) as $value)
            //20150717 #1965 zhenghuiyun upd e
            {
                if ($value['TOA_NAME'] != "" && $value['HTA_PRC'] != "") {
                    $this->result = $this->FrmGenka->fncInsert($value);
                    if (!$this->result['result']) {
                        throw new \Exception($this->result['data']);
                    }
                }
            }
            //---
            $this->result['data'] = "success";
            //コミット
            $this->FrmGenka->Do_commit();
            //ﾄﾗﾝｻﾞｸｼｮﾝ終了
            $this->blnTranFlg = False;
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }
        //finally
        if ($this->blnTranFlg) {
            //ロールバック
            $this->FrmGenka->Do_rollback();
        }
        //DB接続解除
        $this->FrmGenka->Do_close();

        $this->fncReturn($this->result);
    }

    public function fncSingleDelete()
    {
        try {
            //モデルの仕様するクラスを定義
            $this->FrmGenka = new FrmGenka();

            //---

            $this->result = $this->FrmGenka->fncSingleDelete($_POST['data']['KIJYUN_DT']);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            $this->result['data'] = "success";
            //---
        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
        }
        $this->fncReturn($this->result);
    }

}
