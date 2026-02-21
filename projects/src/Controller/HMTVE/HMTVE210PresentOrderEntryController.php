<?php
namespace App\Controller\HMTVE;
use App\Controller\AppController;
use App\Model\HMTVE\HMTVE210PresentOrderEntry;
//*******************************************
// * sample controller
//*******************************************
class HMTVE210PresentOrderEntryController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    public $Session;
    public $HMTVE210PresentOrderEntry;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
    }

    /*
           ***********************************************************************
           '処 理 名：初期表示
           '関 数 名：index
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：
           '**********************************************************************
           */
    public function index()
    {
        $this->render('index', 'HMTVE210PresentOrderEntry_layout');
    }
    //店舗コード、店舗名を抽出する
    //展示会開催期間に初期値をセット
    public function pageclear()
    {
        $this->HMTVE210PresentOrderEntry = new HMTVE210PresentOrderEntry();
        $res = array(
            'result' => FALSE,
            'data' => array(),
            'error' => ''
        );
        try {
            $resTime = $this->HMTVE210PresentOrderEntry->setExhibitTermDateSql();
            if (!$resTime['result']) {
                throw new \Exception("データ読込に失敗しました。");
            }
            if ($resTime['row'] > 0) {
                if ($resTime['data'][0]['END_DATE'] !== NULL) {
                    $res['data']['END_DATE'] = date('Y/m/d', strtotime($resTime['data'][0]['END_DATE']));
                }
                $res['data']['START_DATE'] = date('Y/m/d', strtotime($resTime['data'][0]['START_DATE']));
            }
            //店舗コード、店舗名を抽出する
            $resShop = $this->Page_ShopNameSave();
            if (!$resShop['result']) {
                $res['data'] = $resShop['data'];
                throw new \Exception($resShop['error']);
            }
            if ($resShop['row'] > 0) {
                $res['data']['shopName'] = $resShop['data'];
            }
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //店舗コード、店舗名を抽出する
    public function Page_ShopNameSave()
    {
        $this->HMTVE210PresentOrderEntry = new HMTVE210PresentOrderEntry();
        $res = array(
            'result' => FALSE,
            'data' => array(),
            'error' => ''
        );
        try {
            $this->Session = $this->request->getSession();
            $BusyoCD = $this->Session->read('BusyoCD');
            if (!isset($BusyoCD)) {
                $res['data']['msg'] = 'W9999';
                throw new \Exception('表示できる部署が存在しません。管理者にお問い合わせください。');
            }
            $res = $this->HMTVE210PresentOrderEntry->getBCD($BusyoCD);
            if (!$res['result']) {
                throw new \Exception($res['data']);
            }
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        return $res;
    }

    //登録可能年月かをチェックのＳＱＬ文を取得する
    public function getFlag()
    {
        $this->HMTVE210PresentOrderEntry = new HMTVE210PresentOrderEntry();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (!isset($_POST['data'])) {
                throw new \Exception("param error");
            }
            $res = $this->HMTVE210PresentOrderEntry->getFlagSql($_POST['data']['STARTDT']);
            if (!$res['result']) {
                throw new \Exception($res['data']);
            }
            if ($res['row'] > 0) {
                if ($res['data'][0]['KAKUTEI_FLG'] == 1) {
                    throw new \Exception("既に出力が行われていますので、登録は出来ません。");
                }
            }
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //成約プレゼント注文データ取得ＳＱＬ文の取得
    public function getData()
    {
        $this->HMTVE210PresentOrderEntry = new HMTVE210PresentOrderEntry();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (!isset($_POST['request'])) {
                throw new \Exception("param error");
            }
            $this->Session = $this->request->getSession();
            //成約プレゼント注文データ取得ＳＱＬ文を取得する
            $res = $this->HMTVE210PresentOrderEntry->getDataSql($_POST['request']['STARTDT'], $this->Session->read('BusyoCD'));
            if (!$res['result']) {
                throw new \Exception($res['data']);
            }
            $tmpJqgridShow = $this->ClsComFncHMTVE->FncCreateJqGridShow($res['data']);
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];
            $res = $this->ClsComFncHMTVE->FncCreateJqGridDataIndex($res['data'], $totalPage, $page, $tmpCount);
        } catch (\Exception $e) {
            //前端jqgrid共通，显示error信息的title有问题，所以这里返回值为true
            $res['result'] = TRUE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //成約プレゼント注文データを削除する
    public function deleteDataByCD()
    {
        $tranStartFlg = FALSE;
        $this->HMTVE210PresentOrderEntry = new HMTVE210PresentOrderEntry();
        $res = array(
            'result' => FALSE,
            'data' => array(),
            'error' => ''
        );
        try {
            //店舗コード、店舗名を抽出する
            $resShop = $this->Page_ShopNameSave();
            if (!$resShop['result']) {
                $res['data'] = $resShop['data'];
                throw new \Exception($resShop['error']);
            }
            if ($resShop['row'] > 0) {
                $res['data']['shopName'] = $resShop['data'];
            }
            //成約プレゼント注文データ
            if (!isset($_POST['data'])) {
                throw new \Exception("param error");
            }
            //登録可能な展示会開催期間であるかのチェックを行う
            //チェック用データを抽出する
            $objdr2 = $this->HMTVE210PresentOrderEntry->getFlagSql($_POST['data']['STARTDT']);
            if (!$objdr2['result']) {
                throw new \Exception($objdr2['data']);
            }
            if ($objdr2['row'] > 0) {
                if ($objdr2['data'][0]['KAKUTEI_FLG'] == 1) {
                    throw new \Exception("既に出力が行われていますので、登録は出来ません");
                }
            }
            //トランザクション開始
            $this->HMTVE210PresentOrderEntry->Do_transaction();
            $tranStartFlg = TRUE;
            $this->Session = $this->request->getSession();
            //成約プレゼント注文データを削除する
            $del = $this->HMTVE210PresentOrderEntry->getDelOrder($_POST['data']['STARTDT'], $this->Session->read('BusyoCD'));
            if (!$del['result']) {
                throw new \Exception($del['data']);
            }
            //成約プレゼント注文データに登録する
            if (isset($_POST['data']['gvSyouhin']) && count($_POST['data']['gvSyouhin']) > 0) {
                $gvSyouhin = $_POST['data']['gvSyouhin'];
                for ($i = 0; $i < count($gvSyouhin); $i++) {
                    $ins = $this->HMTVE210PresentOrderEntry->getInsertOrder($gvSyouhin[$i], $_POST['data']['STARTDT'], $this->Session->read('BusyoCD'));
                    if (!$ins['result']) {
                        //既に登録されています
                        $res['data']['msg'] = "E0016";
                        throw new \Exception("E0016");
                    }
                }
            }
            //エラーがない場合、コミットする
            $this->HMTVE210PresentOrderEntry->Do_commit();
            $tranStartFlg = FALSE;
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $this->HMTVE210PresentOrderEntry->Do_rollback();
            }
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

}
