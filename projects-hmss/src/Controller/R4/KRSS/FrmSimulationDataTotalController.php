<?php
/**
 * 説明：
 *
 *
 * @author fuxiaolin
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
use App\Model\R4\KRSS\FrmSimulationDataTotal;

class FrmSimulationDataTotalController extends AppController
{
    public $autoLayout = TRUE;
    private $ClsComFnc;
    private $Session;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsSimKeieiSeika');
    }

    public $FrmSimulationDataTotal = "";
    public function index()
    {
        $this->render('index', 'FrmSimulationDataTotal_layout');
    }


    public function frmSimulationDataTotalLoad()
    {
        $result = array('result' => 'false', 'data' => 'ErrorInfo', 'row' => '');

        try {

            $this->FrmSimulationDataTotal = new FrmSimulationDataTotal();

            $result = $this->FrmSimulationDataTotal->fncSQL(1);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);

    }

    public function cmdSearchClick()
    {

        $postData = $_POST['data']['request'];

        $result = array('result' => 'false', 'data' => 'ErrorInfo', 'row' => '');
        try {

            $this->FrmSimulationDataTotal = new FrmSimulationDataTotal();

            $result = $this->FrmSimulationDataTotal->fncChkHKANRIZ(2, $postData);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);

    }

    public function cmdActClick()
    {
        $postData = $_POST['data']['request'];

        $result = array('result' => 'false', 'data' => 'ErrorInfo', 'row' => '');
        try {
            $this->Session = $this->request->getSession();
            $UPDUSER = $this->Session->read('login_user');
            $UPDCLTNM = $this->request->clientIp();
            $UPDAPP = "SimulationDataTotal";

            $this->FrmSimulationDataTotal = new FrmSimulationDataTotal();

            $result = $this->FrmSimulationDataTotal->Do_conn();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $this->FrmSimulationDataTotal->Do_transaction();

            //部署別集計ﾜｰｸを削除する
            $result = $this->FrmSimulationDataTotal->fncDeleteWkKanr();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // print_r($result);
            // return;
            $dtlSyoriYM = rtrim(str_replace("/", "", $postData['cboYM']));
            $strUpdUser = $UPDUSER;
            $strUpdPro = $UPDAPP;
            $strUpdCltNm = $UPDCLTNM;

            $result = $this->FrmSimulationDataTotal->fncSyukeiToBusyo($dtlSyoriYM, $strUpdUser, $strUpdCltNm, $strUpdPro);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //            //部署別実績処理②
//            //ライン集計
//            $result = $this -> FrmSimulationDataTotal -> fncSyukeiLine($strUpdUser, $strUpdCltNm, $strUpdPro);
//            if (!$result['result']) {
//                throw new \Exception($result['data']);
//            }

            //経営成果対象でないものをﾜｰｸﾃｰﾌﾞﾙから削除する
            $result = $this->FrmSimulationDataTotal->fncDeleteKanr();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //シミュレーション用部署別実績データを作成する
            $result = $this->FrmSimulationDataTotal->fncSQL(3, $postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //WK_HSIMKANRからHSIMLINEMSTに登録されているラインのみをHSIMTOTALDATAに登録する
//            $result = $this -> FrmSimulationDataTotal -> fncSQL(4, $postData);
//            if (!$result['result']) {
//                throw new \Exception($result['data']);
//            }

            //HSIMRUISEKIKANRから計上年月のデータを削除する
            $result = $this->FrmSimulationDataTotal->fncSQL(6, $postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //WK_HSIMKANRからHSIMRUISEKIKANRに登録する
            $result = $this->FrmSimulationDataTotal->fncSQL(7, $postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //経理ｺﾝﾄﾛｰﾙﾏｽﾀを更新する(シミュレーション年月の更新)
            $result = $this->FrmSimulationDataTotal->fncSQL(5, $postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //コミット
            $this->FrmSimulationDataTotal->Do_commit();

            $result['result'] = TRUE;
            $result['data'] = '';

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $this->FrmSimulationDataTotal->Do_rollback();

        }
        //DB接続解除
        $this->FrmSimulationDataTotal->Do_close();
        $this->fncReturn($result);
    }

}
