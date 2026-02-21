<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmPassMente;

class FrmPassMenteController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
    }
    public function index()
    {
        $this->render('index', 'FrmPassMente_layout');
    }

    public function cmdDelClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => "",
            'error' => ""
        );
        try {
            $FrmPassMente = new FrmPassMente();
            $cmbPGNM = $_POST["data"]["cmbPGNM"];

            //存在チェック
            $intSonzai = $FrmPassMente->mSonzaiCheck($cmbPGNM);
            if (!$intSonzai['result']) {
                throw new \Exception($intSonzai['data']);
            }
            if ($intSonzai['row'] == 0) {
                throw new \Exception('I0001');
            }

            //データを削除する
            $result = $FrmPassMente->fncDelPassSQL($cmbPGNM);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $result['data'] = "";
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //コンボボックス設定
    public function fncGetPGMSTSQL()
    {
        $result = array(
            'result' => FALSE,
            'error' => ""
        );
        try {
            $FrmPassMente = new FrmPassMente();
            $result = $FrmPassMente->fncGetPGMSTSQL();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncGetPass()
    {
        $result = array(
            'result' => FALSE,
            'error' => ""
        );
        try {
            $cmbPGNM = $_POST['data']['cmbPGNM'];

            $FrmPassMente = new FrmPassMente();
            $result = $FrmPassMente->fncGetPass($cmbPGNM);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function cmdRegClick()
    {
        $result = array(
            'result' => FALSE,
            'error' => ""
        );

        try {
            $cmbPGNM = $_POST['data']['cmbPGNM'];
            $txtPass1 = $_POST['data']['txtPass1'];
            $FrmPassMente = new FrmPassMente();

            //存在チェック
            $intSonzai = $FrmPassMente->mSonzaiCheck($cmbPGNM);
            if (!$intSonzai['result']) {
                throw new \Exception($intSonzai['data']);
            }

            //判定
            if ($intSonzai['row'] > 0) {
                //更新
                $result = $FrmPassMente->fncUpdPassSQL($cmbPGNM, $txtPass1);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
            } else {
                //登録
                $result = $FrmPassMente->fncInsPassSQL($cmbPGNM, $txtPass1);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
            }

            $result['result'] = true;
            $result['data'] = "";
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

}
