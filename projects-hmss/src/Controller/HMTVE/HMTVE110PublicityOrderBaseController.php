<?php
namespace App\Controller\HMTVE;
use App\Controller\AppController;
use App\Model\HMTVE\HMTVE110PublicityOrderBase;
//*******************************************
// * sample controller
//*******************************************
class HMTVE110PublicityOrderBaseController extends AppController
{
    public $autoLayout = TRUE;
    public $HMTVE110PublicityOrderBase;

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
    }
    public function index()
    {
        // 画面表示内容の設定
        $this->render('index', 'HMTVE110PublicityOrderBase_layout');
    }

    public function fncpageload()
    {
        $result = array(
            'result' => FALSE,
            'data' => array('getYM' => "", ),
            'error' => ''
        );
        try {
            //時間取得
            $this->HMTVE110PublicityOrderBase = new HMTVE110PublicityOrderBase();

            $result_getYM = $this->HMTVE110PublicityOrderBase->getYM();

            if (!$result_getYM['result']) {
                throw new \Exception($result_getYM['data']);
            }
            if (count((array) $result_getYM['data']) == 0) {
                throw new \Exception('E9999');
            } else {
                if ($result_getYM['data'][0]['IVENTMIN'] == "") {
                    throw new \Exception('E9999');
                } else {
                    $result['data']['getYM'] = $result_getYM['data'][0];
                }

            }

            $result['result'] = true;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function btnETSearchClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => array(
                'getExDetailGrdView' => "",
                'getDate' => "",
            ),
            'error' => ''
        );
        try {
            $this->HMTVE110PublicityOrderBase = new HMTVE110PublicityOrderBase();
            $postdata = $_POST['data'];
            //品名・単価ﾃｰﾌﾞﾙの生成
            $result_getExDetailGrdView = $this->HMTVE110PublicityOrderBase->getExDetailGrdView($postdata);

            if (!$result_getExDetailGrdView['result']) {
                throw new \Exception($result_getExDetailGrdView['data']);
            }
            $result['data']['getExDetailGrdView'] = $result_getExDetailGrdView;

            //回収期限用データ取得

            $result_getDate = $this->HMTVE110PublicityOrderBase->getDate($postdata);

            if (!$result_getDate['result']) {
                throw new \Exception($result_getDate['data']);
            }
            $result['data']['getDate'] = $result_getDate;
            $result['result'] = true;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function btnLoginClick()
    {
        $result = array(
            'result' => false,
            'error' => ''
        );
        $blnTran = FALSE;
        try {
            $postdata = $_POST['data'];
            $this->HMTVE110PublicityOrderBase = new HMTVE110PublicityOrderBase();
            //トランザクション開始
            $this->HMTVE110PublicityOrderBase->Do_transaction();
            $blnTran = TRUE;
            //展示会宣材注文展示会データを削除する
            $result_getExDel = $this->HMTVE110PublicityOrderBase->getExDel($postdata);
            if (!$result_getExDel['result']) {
                throw new \Exception($result_getExDel['data']);
            }
            //宣材注文品名データに登録する
            foreach ($postdata['arr'] as $value) {
                $arr = array();
                $arr['txtRemark'] = $value['BIKOU'];
                $arr['start_date'] = $value['KIKAN'];
                $arr['ddlYear'] = $postdata['ddlYear'];
                $arr['ddlMonth'] = $postdata['ddlMonth'];
                $arr['txtTime'] = $postdata['txtTime'];
                $result_getExInsert = $this->HMTVE110PublicityOrderBase->getExInsert($arr);
                if (!$result_getExInsert['result']) {
                    throw new \Exception($result_getExInsert['data']);
                }
            }
            //宣材注文品名データを削除する
            $result_getExDetailDel = $this->HMTVE110PublicityOrderBase->getExDetailDel($postdata);
            if (!$result_getExDetailDel['result']) {
                throw new \Exception($result_getExDetailDel['data']);
            }
            //宣材注文品名データに登録する
            $result_getExDetailInsert = $this->HMTVE110PublicityOrderBase->getExDetailInsert($postdata);
            if (!$result_getExDetailInsert['result']) {
                throw new \Exception($result_getExDetailInsert['data']);
            }
            //宣材注文回収期限データを削除する
            $result_getDateDel = $this->HMTVE110PublicityOrderBase->getDateDel($postdata);
            if (!$result_getDateDel['result']) {
                throw new \Exception($result_getDateDel['data']);
            }
            //宣材注文回収期限データを登録する
            $result_getDateInsert = $this->HMTVE110PublicityOrderBase->getDateInsert($postdata);
            if (!$result_getDateInsert['result']) {
                throw new \Exception($result_getDateInsert['data']);
            }
            //コミット
            $this->HMTVE110PublicityOrderBase->Do_commit();

            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
            //ロールバック
            if ($blnTran) {
                $this->HMTVE110PublicityOrderBase->Do_rollback();
            }
        }

        $this->fncReturn($result);
    }

    public function btnDelClick()
    {
        $result = array(
            'result' => false,
            'error' => ''
        );
        $blnTran = FALSE;
        try {
            $postdata = $_POST['data'];
            $this->HMTVE110PublicityOrderBase = new HMTVE110PublicityOrderBase();
            //トランザクション開始
            $this->HMTVE110PublicityOrderBase->Do_transaction();
            $blnTran = TRUE;
            //展示会宣材注文展示会データを削除する
            $result_getExDel = $this->HMTVE110PublicityOrderBase->getExDel($postdata);
            if (!$result_getExDel['result']) {
                throw new \Exception($result_getExDel['data']);
            }
            //宣材注文品名データを削除する
            $result_getExDetailDel = $this->HMTVE110PublicityOrderBase->getExDetailDel($postdata);
            if (!$result_getExDetailDel['result']) {
                throw new \Exception($result_getExDetailDel['data']);
            }
            //宣材注文回収期限データを削除する
            $result_getDateDel = $this->HMTVE110PublicityOrderBase->getDateDel($postdata);
            if (!$result_getDateDel['result']) {
                throw new \Exception($result_getDateDel['data']);
            }
            //コミット
            $this->HMTVE110PublicityOrderBase->Do_commit();

            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
            //ロールバック
            if ($blnTran) {
                $this->HMTVE110PublicityOrderBase->Do_rollback();
            }
        }

        $this->fncReturn($result);
    }

    public function getExGrdView()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            //データの取得
            if (isset($_POST['request'])) {
                $postData = $_POST['request'];

                $this->HMTVE110PublicityOrderBase = new HMTVE110PublicityOrderBase();

                $result = $this->HMTVE110PublicityOrderBase->getExGrdView($postData);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                $tmpJqgridShow = $this->ClsComFncHMTVE->FncCreateJqGridShow($result['data']);
                $start = $tmpJqgridShow['start'];
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $tmpJqgrid = $this->ClsComFncHMTVE->FncCreateJqGridDataReload($result["data"], $totalPage, $page, $tmpCount, $start);
                $result = $tmpJqgrid;
            }

        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);

    }

    public function getExDetailGrdView()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            //データの取得
            if (isset($_POST['request'])) {
                $postData = $_POST['request'];

                $this->HMTVE110PublicityOrderBase = new HMTVE110PublicityOrderBase();

                $result = $this->HMTVE110PublicityOrderBase->getExDetailGrdView($postData);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                $tmpJqgridShow = $this->ClsComFncHMTVE->FncCreateJqGridShow($result['data']);
                $start = $tmpJqgridShow['start'];
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $tmpJqgrid = $this->ClsComFncHMTVE->FncCreateJqGridDataReload($result["data"], $totalPage, $page, $tmpCount, $start);
                $result = $tmpJqgrid;
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

}

