<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmSyoreikinSyoriMente;

//*******************************************
// * sample controller
//*******************************************
class FrmSyoreikinSyoriMenteController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;

    //係数種類/係数項目(SUBSTR(種別コード,2,2))
    public $pcnKeisuSyurui = "00";
    //係数種類別対象販売ルート(SUBSTR(種別コード,2,2))
    public $pcnTaisyoRoute = "20";
    //支給対象(SUBSTR(種別コード,2,4))
    public $pcnTaisyo = "1000";
    //算出奨励金掛け率(SUBSTR(種別コード,2,4))
    public $pcnKakeritu = "2000";
    //限界/経常取得部署
    public $pcnTenSyutoku = "21100";
    //支給上限
    public $pcnJogen = "JOGEN";
    //販売ルート
    public $pcnGyoHanbaiRoute = "10001";
    public $FrmSyoreikinSyoriMente;

    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFncJKSYS');
    }
    public function index()
    {
        // 画面表示内容の設定
        $this->render('index', 'FrmSyoreikinSyoriMente_layout');
    }

    //係数種類_ｺﾝﾎﾞｾｯﾄ
    public function subSetCmbKeisuSyurui()
    {
        $result = array(
            'result' => false,
            'data' => array(
                'cmbGyoKeisuSyurui1' => array(),
                'cmbGyoKeisuSyurui2' => array(),
                'cmbTenKeisuSyurui1' => array(),
                'cmbTenKeisuSyurui2' => array()
            ),
            'error' => '',
        );
        try {
            $this->FrmSyoreikinSyoriMente = new FrmSyoreikinSyoriMente();
            //奨励金処理マスタ係数種類の取得
            for ($intLoop = 1; $intLoop <= 2; $intLoop++) {
                $dt1 = $this->FrmSyoreikinSyoriMente->fncSyoreiKinSyoriSQL($intLoop . $this->pcnKeisuSyurui . '00', $this->pcnGyoHanbaiRoute, "", "", true);
                if (!$dt1['result']) {
                    throw new \Exception($dt1['data']);
                }
                $addRow = array(
                    'CODE' => "00",
                    'MEISYO' => "",
                );
                array_unshift($dt1['data'], $addRow);
                $dt2 = $dt1['data'];
                if ($intLoop == 1) {
                    //係数種類別対象販売ルートから係数種類="01:販売ルート"を削除する
                    foreach ((array) $dt2 as $key => $value) {
                        if ($value['CODE'] == '01') {
                            array_splice($dt2, $key, 1);
                        }
                    }
                } else {
                    //係数種類別対象販売ルートから係数種類="11:経常利益"を削除する
                    foreach ((array) $dt2 as $key => $value) {
                        if ($value['CODE'] == '11') {
                            array_splice($dt2, $key, 1);
                        }
                    }
                }

                if ($intLoop == 1) {
                    $result['data']['cmbGyoKeisuSyurui1'] = $dt1['data'];
                    $result['data']['cmbGyoKeisuSyurui2'] = $dt2;
                } else {
                    $result['data']['cmbTenKeisuSyurui1'] = $dt1['data'];
                    $result['data']['cmbTenKeisuSyurui2'] = $dt2;
                }
            }
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //画面初期化
    public function subInit()
    {
        $result = array(
            'result' => false,
            'data' => array(),
            'error' => '',
        );
        try {
            $this->FrmSyoreikinSyoriMente = new FrmSyoreikinSyoriMente();
            //業績奨励_係数種類
            $getKeisuSyurui1 = $this->FrmSyoreikinSyoriMente->fncSyoreiKinSyoriSQL('1' . $this->pcnKeisuSyurui . '00', $this->pcnGyoHanbaiRoute);
            if (!$getKeisuSyurui1['result']) {
                throw new \Exception($getKeisuSyurui1['data']);
            }
            $result['data']['getKeisuSyurui1'] = $getKeisuSyurui1;
            //業績奨励_支給対象
            $getSikyuTaisyo1 = $this->FrmSyoreikinSyoriMente->fncSyoreiKinSyoriSQL('1' . $this->pcnTaisyo, $this->pcnGyoHanbaiRoute);
            if (!$getSikyuTaisyo1['result']) {
                throw new \Exception($getSikyuTaisyo1['data']);
            }
            $result['data']['getSikyuTaisyo1'] = $getSikyuTaisyo1;
            //業績奨励_支給上限
            $txtGyoJogen = $this->FrmSyoreikinSyoriMente->fncSyoreiKinSyoriSQL($this->pcnJogen, $this->pcnGyoHanbaiRoute, "1");
            if (!$txtGyoJogen['result']) {
                throw new \Exception($txtGyoJogen['data']);
            }
            $result['data']['txtGyoJogen'] = $txtGyoJogen;
            $getJogen1 = $this->FrmSyoreikinSyoriMente->fncSyoreiKinSyoriSQL($this->pcnJogen, $this->pcnGyoHanbaiRoute, "", "1");
            if (!$getJogen1['result']) {
                throw new \Exception($getJogen1['data']);
            }
            $result['data']['getJogen1'] = $getJogen1;
            //業績奨励_掛け率
            $getKakeritu1 = $this->FrmSyoreikinSyoriMente->fncSyoreiKinSyoriSQL('1' . $this->pcnKakeritu, $this->pcnGyoHanbaiRoute);
            if (!$getKakeritu1['result']) {
                throw new \Exception($getKakeritu1['data']);
            }
            $result['data']['getKakeritu1'] = $getKakeritu1;
            //店長奨励_係数種類
            $getKeisuSyurui2 = $this->FrmSyoreikinSyoriMente->fncSyoreiKinSyoriSQL('2' . $this->pcnKeisuSyurui . '00', $this->pcnGyoHanbaiRoute);
            if (!$getKeisuSyurui2['result']) {
                throw new \Exception($getKeisuSyurui2['data']);
            }
            $result['data']['getKeisuSyurui2'] = $getKeisuSyurui2;
            //店長奨励_支給対象
            $getSikyuTaisyo2 = $this->FrmSyoreikinSyoriMente->fncSyoreiKinSyoriSQL('2' . $this->pcnTaisyo, $this->pcnGyoHanbaiRoute);
            if (!$getSikyuTaisyo2['result']) {
                throw new \Exception($getSikyuTaisyo2['data']);
            }
            $result['data']['getSikyuTaisyo2'] = $getSikyuTaisyo2;
            //店長奨励_支給上限
            $txtTenJogen = $this->FrmSyoreikinSyoriMente->fncSyoreiKinSyoriSQL($this->pcnJogen, $this->pcnGyoHanbaiRoute, "2");
            if (!$txtTenJogen['result']) {
                throw new \Exception($txtTenJogen['data']);
            }
            $result['data']['txtTenJogen'] = $txtTenJogen;
            //店長奨励_掛け率
            $getKakeritu2 = $this->FrmSyoreikinSyoriMente->fncSyoreiKinSyoriSQL('2' . $this->pcnKakeritu, $this->pcnGyoHanbaiRoute);
            if (!$getKakeritu2['result']) {
                throw new \Exception($getKakeritu2['data']);
            }
            $result['data']['getKakeritu2'] = $getKakeritu2;
            //店長奨励_限界/経常利益取得部署
            $getTenSyutoku = $this->FrmSyoreikinSyoriMente->fncSyoreiKinSyoriSQL($this->pcnTenSyutoku, $this->pcnGyoHanbaiRoute);
            if (!$getTenSyutoku['result']) {
                throw new \Exception($getTenSyutoku['data']);
            }
            $result['data']['getTenSyutoku'] = $getTenSyutoku;
            //職種名_取得
            $allSyokusyuName = $this->FrmSyoreikinSyoriMente->fncSelSyokusyuSQL();
            if (!$allSyokusyuName['result']) {
                throw new \Exception($allSyokusyuName['data']);
            }
            $result['data']['allSyokusyuName'] = $allSyokusyuName;
            //部署名_取得
            $allBusyoName = $this->FrmSyoreikinSyoriMente->fncSelBusyoSQL();
            if (!$allBusyoName['result']) {
                throw new \Exception($allBusyoName['data']);
            }
            $result['data']['allBusyoName'] = $allBusyoName;
            //販売ルート名_取得
            $allRouteName = $this->FrmSyoreikinSyoriMente->fncSelRouteSQL($this->pcnGyoHanbaiRoute);
            if (!$allRouteName['result']) {
                throw new \Exception($allRouteName['data']);
            }
            $result['data']['allRouteName'] = $allRouteName;
            //雇用区分名_取得
            $allKoyouName = $this->FrmSyoreikinSyoriMente->fncSelKoyouSQL();
            if (!$allKoyouName['result']) {
                throw new \Exception($allKoyouName['data']);
            }
            $result['data']['allKoyouName'] = $allKoyouName;

            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //更新処理
    public function fncUpdate()
    {
        $this->FrmSyoreikinSyoriMente = new FrmSyoreikinSyoriMente();
        $result = array(
            'result' => false,
            'error' => '',
        );
        $blnTran = FALSE;
        try {
            $postData = $_POST['data'];
            //--- トランザクション開始 ---
            $this->FrmSyoreikinSyoriMente->Do_transaction();
            $blnTran = TRUE;

            if ($postData['rdoName'] == 'rdoGyoKeisuSyurui') {
                //-------------------
                // 業績奨励_係数種類
                //-------------------
                $datas = isset($postData['datas']) ? $postData['datas'] : array();
                foreach ($datas as $key => $value) {
                    //--- SQL実行(UPDATE) ---
                    $result_upd = $this->FrmSyoreikinSyoriMente->fncUpdGyoKeisuSyuruiSQL($value['CODE'], $this->ClsComFncJKSYS->FncNv($value['ATAI_2']), $value['HYOJI_JUN']);
                    if (!$result_upd['result']) {
                        throw new \Exception($result_upd['data']);
                    }
                }
            } elseif ($postData['rdoName'] == 'rdoGyokeisuKomoku') {
                //-------------------
                // 業績奨励_係数項目
                //-------------------
                $SelectedValue = $postData['SelectedValue'];
                //--- SQL実行(DELETE) ---
                $result_del = $this->FrmSyoreikinSyoriMente->fncDelSyoreiKinSyoriSQL("1" . $this->pcnKeisuSyurui . $SelectedValue);
                if (!$result_del['result']) {
                    throw new \Exception($result_del['data']);
                }
                $datas = isset($postData['datas']) ? $postData['datas'] : array();
                foreach ($datas as $key => $value) {
                    //--- SQL実行(INSERT) ---
                    $result_ins = $this->FrmSyoreikinSyoriMente->fncInsGyokeisuKomokuSQL("1" . $this->pcnKeisuSyurui . $SelectedValue, $key + 1, $value['MEISYO'], $value['ATAI_1'], $value['ATAI_2'], $value['HYOJI_JUN']);
                    if (!$result_ins['result']) {
                        throw new \Exception($result_ins['data']);
                    }
                }
            } elseif ($postData['rdoName'] == 'rdoGyoTaisyoRoute') {
                //-------------------
                // 業績奨励_対象販売ルート
                //-------------------
                $SelectedValue = $postData['SelectedValue'];
                //--- SQL実行(DELETE) ---
                $result_del = $this->FrmSyoreikinSyoriMente->fncDelSyoreiKinSyoriSQL("1" . $this->pcnTaisyoRoute . $SelectedValue);
                if (!$result_del['result']) {
                    throw new \Exception($result_del['data']);
                }
                $datas = isset($postData['datas']) ? $postData['datas'] : array();
                foreach ($datas as $key => $value) {
                    if ($value['CHECK'] == "Yes") {
                        //--- SQL実行(INSERT) ---
                        $result_ins = $this->FrmSyoreikinSyoriMente->fncInsTaisyoRouteSQL("1" . $this->pcnTaisyoRoute . $SelectedValue, $value['CODE'], $value['MEISYO']);
                        if (!$result_ins['result']) {
                            throw new \Exception($result_ins['data']);
                        }
                    }
                }
            } elseif ($postData['rdoName'] == 'rdoGyoTaisyo') {
                //-------------------
                // 業績奨励_支給対象
                //-------------------

                ////--- SQL実行(DELETE) ---
                $result_del = $this->FrmSyoreikinSyoriMente->fncDelSyoreiKinSyoriSQL("1" . $this->pcnTaisyo);
                if (!$result_del['result']) {
                    throw new \Exception($result_del['data']);
                }
                $datas = isset($postData['datas']) ? $postData['datas'] : array();
                foreach ($datas as $key => $value) {
                    //--- SQL実行(INSERT) ---
                    $result_ins = $this->FrmSyoreikinSyoriMente->fncInsGyoTaisyoSQL("1" . $this->pcnTaisyo, $value['SYOKUSYU'], $value['BUSYO'], $value['ROUTE']);
                    if (!$result_ins['result']) {
                        throw new \Exception($result_ins['data']);
                    }
                }
            } elseif ($postData['rdoName'] == 'rdoGyoJogen') {
                //-------------------
                // 業績奨励_支給上限
                //-------------------
                //** 正社員 **

                //--- SQL実行(UPDATE) ---
                $result_upd = $this->FrmSyoreikinSyoriMente->fncUpdJogenSQL("1", $postData['txtGyoJogen']);
                if (!$result_upd['result']) {
                    throw new \Exception($result_upd['data']);
                }
                //** 正社員以外 **

                //--- SQL実行(DELETE) ---
                $result_del = $this->FrmSyoreikinSyoriMente->fncDelSyoreiKinSyoriSQL($this->pcnJogen, "1");
                if (!$result_del['result']) {
                    throw new \Exception($result_del['data']);
                }
                $datas = isset($postData['datas']) ? $postData['datas'] : array();
                foreach ($datas as $key => $value) {
                    //--- SQL実行(INSERT) ---
                    $result_ins = $this->FrmSyoreikinSyoriMente->fncInsGyoJogenSQL($this->pcnJogen, $value['KOYOU'], $value['SYOKUSYU'], $value['JOGEN']);
                    if (!$result_ins['result']) {
                        throw new \Exception($result_ins['data']);
                    }
                }
            } elseif ($postData['rdoName'] == 'rdoGyoKakeritu') {
                //-------------------
                // 業績奨励_掛け率
                //-------------------

                //--- SQL実行(UPDATE) ---
                $result_upd = $this->FrmSyoreikinSyoriMente->fncUpdKakerituSQL("1" . $this->pcnKakeritu, $postData['txtGyoKakeritu']);
                if (!$result_upd['result']) {
                    throw new \Exception($result_upd['data']);
                }
            } elseif ($postData['rdoName'] == 'rdoTenKeisuSyurui') {
                //-------------------
                // 店長奨励_係数種類
                //-------------------
                $datas = isset($postData['datas']) ? $postData['datas'] : array();
                foreach ($datas as $key => $value) {
                    $ATAI_1_NM = substr($value['ATAI_1_NM'], -10, 3);
                    $umu = "";
                    if ($ATAI_1_NM == "有") {
                        $umu = "1";
                    } else {
                        $umu = "";
                    }
                    //--- SQL実行(UPDATE) ---
                    $result_upd = $this->FrmSyoreikinSyoriMente->fncUpdTenKeisuSyuruiSQL($value['CODE'], $umu, $value['ATAI_2'], $value['HYOJI_JUN']);
                    if (!$result_upd['result']) {
                        throw new \Exception($result_upd['data']);
                    }
                }
            } elseif ($postData['rdoName'] == 'rdoTenkeisuKomoku') {
                //-------------------
                // 店長奨励_係数項目
                //-------------------
                $SelectedValue = $postData['SelectedValue'];
                //--- SQL実行(DELETE) ---
                $result_del = $this->FrmSyoreikinSyoriMente->fncDelSyoreiKinSyoriSQL("2" . $this->pcnKeisuSyurui . $SelectedValue);
                if (!$result_del['result']) {
                    throw new \Exception($result_del['data']);
                }
                $datas = isset($postData['datas']) ? $postData['datas'] : array();
                foreach ($datas as $key => $value) {
                    //--- SQL実行(INSERT) ---
                    $result_ins = $this->FrmSyoreikinSyoriMente->fncInsTenkeisuKomokuSQL("2" . $this->pcnKeisuSyurui . $SelectedValue, $key + 1, $value['MEISYO'], $value['HYOJI_JUN']);
                    if (!$result_ins['result']) {
                        throw new \Exception($result_ins['data']);
                    }
                }
            } elseif ($postData['rdoName'] == 'rdoTenTaisyoRoute') {
                //-------------------
                // 店長奨励_対象販売ルート
                //-------------------
                $SelectedValue = $postData['SelectedValue'];
                //--- SQL実行(DELETE) ---
                $result_del = $this->FrmSyoreikinSyoriMente->fncDelSyoreiKinSyoriSQL("2" . $this->pcnTaisyoRoute . $SelectedValue);
                if (!$result_del['result']) {
                    throw new \Exception($result_del['data']);
                }
                $datas = isset($postData['datas']) ? $postData['datas'] : array();
                foreach ($datas as $key => $value) {
                    if ($value['CHECK'] == "Yes") {
                        //--- SQL実行(INSERT) ---
                        $result_ins = $this->FrmSyoreikinSyoriMente->fncInsTaisyoRouteSQL("2" . $this->pcnTaisyoRoute . $SelectedValue, $value['CODE'], $value['MEISYO']);
                        if (!$result_ins['result']) {
                            throw new \Exception($result_ins['data']);
                        }
                    }
                }
            } elseif ($postData['rdoName'] == 'rdoTenTaisyo') {
                //-------------------
                // 店長奨励_支給対象
                //-------------------

                //--- SQL実行(DELETE) ---
                $result_del = $this->FrmSyoreikinSyoriMente->fncDelSyoreiKinSyoriSQL("2" . $this->pcnTaisyo);
                if (!$result_del['result']) {
                    throw new \Exception($result_del['data']);
                }
                $datas = isset($postData['datas']) ? $postData['datas'] : array();
                foreach ($datas as $key => $value) {
                    //--- SQL実行(INSERT) ---
                    $result_ins = $this->FrmSyoreikinSyoriMente->fncInsTenTaisyoSQL("2" . $this->pcnTaisyo, $value['BUSYO'], $value['SYOKUSYU'], $value['ROUTE']);
                    if (!$result_ins['result']) {
                        throw new \Exception($result_ins['data']);
                    }
                }
            } elseif ($postData['rdoName'] == 'rdoTenJogen') {
                //-------------------
                // 店長奨励_支給上限
                //-------------------
                //--- SQL実行(UPDATE) ---
                $result_upd = $this->FrmSyoreikinSyoriMente->fncUpdJogenSQL("2", $postData['txtTenJogen']);
                if (!$result_upd['result']) {
                    throw new \Exception($result_upd['data']);
                }
            } elseif ($postData['rdoName'] == 'rdoTenKakeritu') {
                //-------------------
                // 店長奨励_掛け率
                //-------------------
                //--- SQL実行(UPDATE) ---
                $result_upd = $this->FrmSyoreikinSyoriMente->fncUpdKakerituSQL("2" . $this->pcnKakeritu, $postData['txtTenKakeritu']);
                if (!$result_upd['result']) {
                    throw new \Exception($result_upd['data']);
                }
            } elseif ($postData['rdoName'] == 'rdoTenSyutoku') {
                //-------------------
                // 店長奨励_限界/経常利益取得部署
                //-------------------

                //--- SQL実行(DELETE) ---
                $result_del = $this->FrmSyoreikinSyoriMente->fncDelSyoreiKinSyoriSQL($this->pcnTenSyutoku);
                if (!$result_del['result']) {
                    throw new \Exception($result_del['data']);
                }
                $datas = isset($postData['datas']) ? $postData['datas'] : array();
                foreach ($datas as $key => $value) {
                    //--- SQL実行(INSERT) ---
                    $result_ins = $this->FrmSyoreikinSyoriMente->fncInsTenSyutokuSQL($this->pcnTenSyutoku, $value['BUSYO'], $value['RIEKI'], $value['GENKAI']);
                    if (!$result_ins['result']) {
                        throw new \Exception($result_ins['data']);
                    }
                }
            }
            //--- コミット ---
            $this->FrmSyoreikinSyoriMente->Do_commit();
            $blnTran = FALSE;

            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
            //ロールバック
            if ($blnTran) {
                $this->FrmSyoreikinSyoriMente->Do_rollback();
            }
        }

        $this->fncReturn($result);
    }

    //係数種類
    public function getKeisuSyurui()
    {
        $result = array(
            'result' => false,
            'error' => '',
        );
        try {
            $postData = $_POST['data'];

            $this->FrmSyoreikinSyoriMente = new FrmSyoreikinSyoriMente();
            $result = $this->FrmSyoreikinSyoriMente->fncSyoreiKinSyoriSQL($postData['strSyoreiKbn'] . $this->pcnKeisuSyurui . '00', $this->pcnGyoHanbaiRoute);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //係数項目セット
    public function getKeisuKomoku()
    {
        $result = array(
            'result' => false,
            'error' => '',
        );

        try {
            $postData = $_POST['data'];

            $this->FrmSyoreikinSyoriMente = new FrmSyoreikinSyoriMente();
            $result = $this->FrmSyoreikinSyoriMente->fncSyoreiKinSyoriSQL($postData['strSyoreiKbn'] . $this->pcnKeisuSyurui . $postData['SelectedValue'], $this->pcnGyoHanbaiRoute);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //対象販売ルートセット
    public function getTaisyoRoute()
    {
        $result = array(
            'result' => false,
            'data' => array(),
            'error' => '',
        );

        try {
            $postData = $_POST['data'];

            $this->FrmSyoreikinSyoriMente = new FrmSyoreikinSyoriMente();
            $SprTaisyoRoute = $this->FrmSyoreikinSyoriMente->fncSyoreiKinSyoriSQL($this->pcnGyoHanbaiRoute, $this->pcnGyoHanbaiRoute);
            if (!$SprTaisyoRoute['result']) {
                throw new \Exception($SprTaisyoRoute['data']);
            }
            $result['data']['SprTaisyoRoute'] = $SprTaisyoRoute;

            $dt = $this->FrmSyoreikinSyoriMente->fncSyoreiKinSyoriSQL($postData['strSyoreiKbn'] . $this->pcnTaisyoRoute . $postData['SelectedValue'], $this->pcnGyoHanbaiRoute);
            if (!$dt['result']) {
                throw new \Exception($dt['data']);
            }
            $result['data']['dt'] = $dt;

            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //支給対象
    public function getSikyuTaisyo()
    {
        $result = array(
            'result' => false,
            'error' => '',
        );

        try {
            $postData = $_POST['data'];

            $this->FrmSyoreikinSyoriMente = new FrmSyoreikinSyoriMente();
            $result = $this->FrmSyoreikinSyoriMente->fncSyoreiKinSyoriSQL($postData['strSyoreiKbn'] . $this->pcnTaisyo, $this->pcnGyoHanbaiRoute);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //支給上限セット
    public function getJogen()
    {
        $result = array(
            'result' => false,
            'error' => '',
        );
        try {
            $this->FrmSyoreikinSyoriMente = new FrmSyoreikinSyoriMente();

            $postData = $_POST['data'];
            if ($postData['strSyoreiKbn'] == "1") {
                //業績奨励_支給上限
                $txtGyoJogen = $this->FrmSyoreikinSyoriMente->fncSyoreiKinSyoriSQL($this->pcnJogen, $this->pcnGyoHanbaiRoute, "1");
                if (!$txtGyoJogen['result']) {
                    throw new \Exception($txtGyoJogen['data']);
                }
                $result['data']['txtJogen'] = $txtGyoJogen;
                $getJogen1 = $this->FrmSyoreikinSyoriMente->fncSyoreiKinSyoriSQL($this->pcnJogen, $this->pcnGyoHanbaiRoute, "", "1");
                if (!$getJogen1['result']) {
                    throw new \Exception($getJogen1['data']);
                }
                $result['data']['getJogen'] = $getJogen1;
            } else {
                //店長奨励_支給上限
                $txtTenJogen = $this->FrmSyoreikinSyoriMente->fncSyoreiKinSyoriSQL($this->pcnJogen, $this->pcnGyoHanbaiRoute, "2");
                if (!$txtTenJogen['result']) {
                    throw new \Exception($txtTenJogen['data']);
                }
                $result['data']['txtJogen'] = $txtTenJogen;
                $result['data']['getJogen'] = "";
            }
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //掛け率セット
    public function getKakeritu()
    {
        $result = array(
            'result' => false,
            'error' => '',
        );

        try {
            $postData = $_POST['data'];

            $this->FrmSyoreikinSyoriMente = new FrmSyoreikinSyoriMente();
            $result = $this->FrmSyoreikinSyoriMente->fncSyoreiKinSyoriSQL($postData['strSyoreiKbn'] . $this->pcnKakeritu, $this->pcnGyoHanbaiRoute);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //店長奨励_限界/経常利益取得部署セット
    public function getTenSyutoku()
    {
        $result = array(
            'result' => false,
            'error' => '',
        );

        try {
            $this->FrmSyoreikinSyoriMente = new FrmSyoreikinSyoriMente();
            $result = $this->FrmSyoreikinSyoriMente->fncSyoreiKinSyoriSQL($this->pcnTenSyutoku, $this->pcnGyoHanbaiRoute);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

}
