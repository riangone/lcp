<?php
namespace App\Controller\HMTVE;

use App\Controller\AppController;
use App\Model\HMTVE\HMTVE230PresentOrderCareer;
//*******************************************
// * sample controller
//*******************************************
class HMTVE230PresentOrderCareerController extends AppController
{
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    private $HMTVE230PresentOrderCareer;
    private $Session;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
    }
    public function index()
    {
        // 画面表示内容の設定
        $this->render('index', 'HMTVE230PresentOrderCareer_layout');
    }

    public function pageLoad()
    {
        $this->HMTVE230PresentOrderCareer = new HMTVE230PresentOrderCareer();
        $res = array(
            'result' => false,
            'data' => '',
            'error' => '',
        );
        try {
            if (!isset($_POST['data'])) {
                throw new \Exception("param error");
            }
            //対象期間を取得する
            $res = $this->HMTVE230PresentOrderCareer->getSQLPL();
            if (!$res['result']) {
                throw new \Exception($res['data']);
            }

        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }

        $this->fncReturn($res);
    }

    //店舗名を表示する
    public function pageShopNameSave()
    {
        $res = array(
            'result' => false,
            'data' => '',
            'error' => '',
        );
        try {
            $this->HMTVE230PresentOrderCareer = new HMTVE230PresentOrderCareer();
            //対象期間を取得する
            $this->Session = $this->request->getSession();
            $res = $this->HMTVE230PresentOrderCareer->getSQLTenpo($this->Session->read("BusyoCD"));
            if (!$res['result']) {
                throw new \Exception($res['data']);
            }

        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }

        $this->fncReturn($res);
    }

    //表示ボタンクリックする
    public function btnClick()
    {
        $this->HMTVE230PresentOrderCareer = new HMTVE230PresentOrderCareer();
        $res = array(
            'result' => false,
            'data' => '',
            'error' => '',
        );
        try {
            if (isset($_POST['request'])) {
                $this->Session = $this->request->getSession();
                $BusyoCD = $this->Session->read("BusyoCD");
                $strB = $_POST['request']['strB'];
                $strE = $_POST['request']['strE'];
                //履歴データ取得
                $objds = $this->HMTVE230PresentOrderCareer->getSQLGrid($BusyoCD, $strB, $strE);
                if (!$objds['result']) {
                    throw new \Exception($objds['data']);
                }
                $tmpJqgridShow = $this->ClsComFncHMTVE->FncCreateJqGridShow($objds['data']);
                $start = $tmpJqgridShow['start'];
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $res = $this->ClsComFncHMTVE->FncCreateJqGridDataReload($objds["data"], $totalPage, $page, $tmpCount, $start);
            }
        } catch (\Exception $e) {
            //前端jqgrid共通，显示error信息的title有问题，所以这里返回值为true
            $res['result'] = TRUE;
            $res['error'] = $e->getMessage();
        }

        $this->fncReturn($res);
    }

}
