<?php
namespace App\Controller\R4\R4G;

use App\Controller\AppController;
use App\Model\R4\R4G\FrmControl;

class FrmControlController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmControl;
    public $Update;
    public function initialize(): void
    {
        parent::initialize();
        if ($this->request->is('ajax')) {
            $this->viewBuilder()->setClassName('Ajax');
        }
    }
    public function index()
    {
        $this->render('index', 'FrmControl_layout');
    }

    // '**********************************************************************
    // '処 理 名：データグリッドの再表示
    // '関 数 名：subSpreadReShow
    // '引    数：objDr (I) オブジェクト
    // '戻 り 値：無し
    // '処理説明：データグリッドを再表示する
    // '**********************************************************************
    public function subSpreadReShow()
    {
        try {
            $this->FrmControl = new FrmControl();
            $result = $this->FrmControl->fncControlDateSelect();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //ロック解除
    public function fncunLock()
    {
        $UnlockRow = array();
        try {
            if (isset($_POST['data']['request'])) {
                $UnlockRow = $_POST['data']['request'];
            }
            if ($UnlockRow == '') {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            } else {
                $this->FrmControl = new FrmControl();
                $this->Update = $this->FrmControl->fncUpdateControl($UnlockRow);
                if (!$this->Update['result']) {
                    throw new \Exception($this->Update['data']);
                }
            }
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

}
