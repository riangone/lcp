<?php
namespace App\Controller\HMTVE;
use App\Controller\AppController;
use App\Model\HMTVE\HMTVE130PublicityOrderConfirm;
//*******************************************
// * sample controller
//*******************************************
class HMTVE130PublicityOrderConfirmController extends AppController
{
    public $autoLayout = TRUE;
    private $Session;
    public $HMTVE130PublicityOrderConfirm;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
    }
    public function index()
    {
        $this->render('index', 'HMTVE130PublicityOrderConfirm_layout');
    }
    //ページ初期化
    public function pageLoad()
    {
        $result = array(
            'result' => false,
            'datasp' => null,
            'datadt' => null,
            'datahd' => null,
            'error' => ''
        );
        try {
            if (isset($_POST['request'])) {
                $postdata = $_POST['request'];
                $this->Session = $this->request->getSession();
                $BUSYOCD = $this->Session->read('BusyoCD');
                $this->HMTVE130PublicityOrderConfirm = new HMTVE130PublicityOrderConfirm();
                //展示会テーブルの生成(GRIDVIEW)
                $result = $this->HMTVE130PublicityOrderConfirm->getDetail($postdata['NENGETU'], $BUSYOCD);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                $tmpJqgridShow = $this->ClsComFncHMTVE->FncCreateJqGridShow($result['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $result = $this->ClsComFncHMTVE->FncCreateJqGridData($result["data"], $totalPage, $page, $tmpCount);
                $result = json_decode(json_encode($result), true);

                //店舗名を表示する
                $resultsp = $this->HMTVE130PublicityOrderConfirm->getShopNM($BUSYOCD);
                if (!$resultsp['result']) {
                    throw new \Exception($resultsp['data']);
                }
                $result['datasp'] = $resultsp['data'];

                //展示会ﾍｯﾀﾞｰﾃﾞｰﾀ取得
                $resulthd = $this->HMTVE130PublicityOrderConfirm->getTitle($postdata['NENGETU']);
                if (!$resulthd['result']) {
                    throw new \Exception($resulthd['data']);
                }
                $result['datahd'] = $resulthd['data'];
                $result['error'] = '';
            }
        } catch (\Exception $e) {
            //前端jqgrid共通，显示error信息的title有问题，所以这里返回值为true
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //注文を確定ボタンのイベント
    public function btnValidateClick()
    {
        $result = array(
            'result' => false,
            'error' => ''
        );
        $blnTran = FALSE;
        try {
            $this->HMTVE130PublicityOrderConfirm = new HMTVE130PublicityOrderConfirm();

            $postdata = $_POST['data'];
            $this->Session = $this->request->getSession();
            $BUSYOCD = $this->Session->read('BusyoCD');
            //登録可能年月かをチェックする
            $resultck = $this->HMTVE130PublicityOrderConfirm->getCheckYMD($postdata['NENGETU']);
            if (!$resultck['result']) {
                throw new \Exception($resultck['data']);
            } elseif (count((array) $resultck['data']) > 0) {
                if (array_key_exists("KAKUTEI_FLG", (array) $resultck['data'][0]) && $resultck['data'][0]['KAKUTEI_FLG'] == '1') {
                    throw new \Exception('E9999');
                }
            }

            $this->HMTVE130PublicityOrderConfirm->Do_transaction();
            $blnTran = TRUE;
            //宣材注文データの削除処理
            $result = $this->HMTVE130PublicityOrderConfirm->getOrderDelete($postdata['NENGETU'], $BUSYOCD);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //宣材注文データに登録する
            $result = $this->HMTVE130PublicityOrderConfirm->getOrderLogin($postdata['NENGETU'], $BUSYOCD);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //ワークテーブルを削除する
            $result = $this->HMTVE130PublicityOrderConfirm->getWorkDel($postdata['NENGETU'], $BUSYOCD);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $this->HMTVE130PublicityOrderConfirm->Do_commit();
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();

            if ($blnTran) {
                $this->HMTVE130PublicityOrderConfirm->Do_rollback();
            }
        }

        $result['data'] = '';
        $this->fncReturn($result);
    }

}
