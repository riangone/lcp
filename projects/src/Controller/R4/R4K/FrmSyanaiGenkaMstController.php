<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmSyanaiGenkaMst;

class FrmSyanaiGenkaMstController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $result;
    public $Do_conn;
    public $FrmSyanaiGenkaMst;
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
        $this->render('index', 'FrmSyanaiGenkaMst_layout');
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
    public function fncFrmSyanaiGenkaMstSelect()
    {
        try {
            if (!isset($_POST['request'])) {
                $postData = "";
                //Viewへ返却値を設定
                $this->fncReturn($this->result);
                return;
            } else {
                $postData = $_POST['request'];
            }
            //モデルの仕様するクラスを定義
            $this->FrmSyanaiGenkaMst = new FrmSyanaiGenkaMst();
            //モデルクラスのselect処理を呼出し
            $this->result = $this->FrmSyanaiGenkaMst->fncFrmSyanaiGenkaMstSelect($postData);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }

            $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($this->result['data']);

            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];
            for ($ii = 0; $ii < count((array) $this->result['data']); $ii++) {
                foreach ((array) $this->result['data'][$ii] as $key => $value) {
                    $this->result['data'][$ii][$key] = trim($this->ClsComFnc->fncNv($value));
                }
            }
            $this->result = $this->FrmSyanaiGenkaMst->fncFrmSyanaiGenkaMstSelect($postData);
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
        try {
            //モデルの仕様するクラスを定義
            $this->FrmSyanaiGenkaMst = new FrmSyanaiGenkaMst();

            $this->Do_conn = $this->FrmSyanaiGenkaMst->Do_conn();
            if (!$this->Do_conn['result']) {
                throw new \Exception($this->Do_conn['data']);
            }
            //トランザクション開始
            $this->FrmSyanaiGenkaMst->Do_transaction();
            //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
            $this->blnTranFlg = True;
            //---

            $this->result = $this->FrmSyanaiGenkaMst->fncDelete();
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            foreach ($_POST['data'] as $value) {
                if (trim($value['KIJYUN_DT']) != "" && trim($value['NAU_KB']) != "" && trim($value['KJN_GENKA']) != "" && trim($value['HAIBUN_GK1']) != "" && trim($value['HAIBUN_GK2']) != "" && trim($value['HAIBUN_GK3']) != "") {
                    $this->result = $this->FrmSyanaiGenkaMst->fncInsert($value);
                    if (!$this->result['result']) {
                        throw new \Exception($this->result['data']);
                    }
                }
            }
            //---
            $this->result['data'] = "success";
            //コミット
            $this->FrmSyanaiGenkaMst->Do_commit();
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
            $this->FrmSyanaiGenkaMst->Do_rollback();
        }
        //DB接続解除
        $this->FrmSyanaiGenkaMst->Do_close();

        $this->fncReturn($this->result);
    }

    public function fncSingleDelete()
    {
        try {
            if (!isset($_POST['data'])) {
                $this->fncReturn($this->result);
                return;
            }
            $postData = $_POST['data'];
            $this->FrmMeisyoMst = new FrmSyanaiGenkaMst();
            //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
            $this->blnTranFlg = True;
            $this->result = $this->FrmMeisyoMst->fncSingleDelete($postData['KIJYUN_DT'], $postData['NAU_KB'], $postData['KJN_GENKA']);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            $this->result['data'] = "success";
            //ﾄﾗﾝｻﾞｸｼｮﾝ終了
            $this->blnTranFlg = False;

        } catch (\Exception $ex) {
            $this->result['result'] = False;
            $this->result['data'] = $ex->getMessage();
        }
        $this->fncReturn($this->result);
    }

}
