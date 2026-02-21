<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmKyotenFurikae;

//*******************************************
// * sample controller
//*******************************************
class FrmKyotenFurikaeController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFncJKSYS');
    }

    public function index()
    {
        // 画面表示内容の設定
        // レイアウトファイルの指定(app/View/Layouts/FrmBusyoSearch_layout.ctpを参照)
        $this->render('index', 'FrmKyotenFurikae_layout');
    }

    //フォーム初期化
    public function fncFurikaeLoad()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            $frmKyotenFurikae = new FrmKyotenFurikae();
            //コントロールマスタ存在ﾁｪｯｸ
            $result = $frmKyotenFurikae->fncGetTougetu();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $SYORI_YM = "";
            if ($result["row"] > 0) {
                $SYORI_YM = $result["data"][0]["TOUGETU"];
                if (date('Y/m/d', strtotime($SYORI_YM)) != $SYORI_YM) {
                    //年月格式正しくない
                    throw new \Exception("String \"" . $SYORI_YM . "\" から型 'Date' への変換は無効です。");
                }
            } else {
                //年月なし
                throw new \Exception("コントロールマスタが存在しません！");
            }
            $result['data'] = array('TOUGETU' => $SYORI_YM);
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //データグリッドの再表示
    public function fncSearchFurikae()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );

        try {
            if (isset($_POST['request'])) {
                $postData = $_POST['request'];
                $frmKyotenFurikae = new FrmKyotenFurikae();
                $result = $frmKyotenFurikae->fncSelect($postData);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                foreach ((array) $result['data'] as $key => $value) {
                    if ((string) $this->ClsComFncJKSYS->FncNz($value['DISP_KB']) != '0') {
                        $value['MOTO_SYAIN_CD'] = '';
                        $value['MOTO_SYAIN_NM'] = '';
                        $value['MOTO_KIN'] = '';
                        $result['data'][$key] = $value;
                    }
                }

                $tmpJqgridShow = $this->ClsComFncJKSYS->FncCreateJqGridShow($result['data']);
                $start = $tmpJqgridShow['start'];
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = (int) $tmpJqgridShow['count'];

                $result = $this->ClsComFncJKSYS->FncCreateJqGridDataReload($result["data"], $totalPage, $page, $tmpCount, $start);
            }
        } catch (\Exception $e) {
            $result['result'] = true;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

}
