<?php
namespace App\Controller\HMTVE;
use App\Controller\AppController;
use App\Model\HMTVE\HMTVE150PublicityOrderCareer;
//*******************************************
// * sample controller
//*******************************************
class HMTVE150PublicityOrderCareerController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    public $HMTVE150PublicityOrderCareer;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
    }
    //　デフォルトで最初に実行される機能
    public function index()
    {
        // Viewファイル呼出し
        $this->render('index', 'HMTVE150PublicityOrderCareer_layout');
    }

    public function getYM()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            $this->HMTVE150PublicityOrderCareer = new HMTVE150PublicityOrderCareer();
            $result = $this->HMTVE150PublicityOrderCareer->getYMSQL();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if ($result['row'] > 0) {
                if ($result['data'][0]['IVENTMAX'] == null || $result['data'][0]['IVENTMAX'] == '') {
                    $result['key'] = 'W9999';
                    throw new \Exception("展示会が設定されていません。先に展示会データ登録を行ってください！");
                }
                $result['data'][0]['getNowDate'] = $this->ClsComFncHMTVE->FncGetSysDate('Y/m/d');

            }

            $resultShopNM = $this->HMTVE150PublicityOrderCareer->getShopNMSQL();
            if (!$resultShopNM['result']) {
                throw new \Exception($resultShopNM['data']);
            }
            if ($resultShopNM['row'] > 0) {
                $result['data'][0]['BUSYO_RYKNM'] = $resultShopNM['data'][0]['BUSYO_RYKNM'];
            }
            $result['key'] = '';
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);

    }

    //'**********************************************************************
    //'処 理 名：メッセージ取得
    //'関 数 名：getgrdExView
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function getgrdExView()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['request'])) {
                $postData = $_POST["request"]["request"];
                $this->HMTVE150PublicityOrderCareer = new HMTVE150PublicityOrderCareer();
                $result = $this->HMTVE150PublicityOrderCareer->getgrdExViewSQL($postData['ddlYearStart'] . $postData['ddlMonthStart'], $postData['ddlYearEnd'] . $postData['ddlMonthEnd']);
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

}

