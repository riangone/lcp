<?php
/**
 * 説明：
 *
 *
 * @author li
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
use App\Model\R4\KRSS\FrmLoginSelKRSS;
//*******************************************
// * sample controller
//*******************************************
class FrmLoginSelKRSSController extends AppController
{
    public $autoLayout = TRUE;
    private $FrmLoginSelKRSS;
    private $result;
    private $result1;
    private $clsComFnc;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');
    }

    //　デフォルトで最初に実行される機能
    public function index()
    {
        $this->render('index', 'FrmLoginSelKRSS_layout');
    }
    //データリストの値を設定
    public function fncHKEIRICTL()
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );
        $strTougetu = "";
        try {
            $this->FrmLoginSelKRSS = new FrmLoginSelKRSS();
            $result = $this->FrmLoginSelKRSS->fncHKEIRICTL();
            if (!$result['result']) {
                throw new \Exception($result['data'], 1);
            }
            if (count((array) $result['data']) == 0) {
                throw new \Exception("コントロールマスタが存在しません！");
            }

            //コンボボックスに当月年月を設定
            $strTougetu = $this->ClsComFnc->FncNv($result['data'][0]["TOUGETU"]);

            $result = $this->FrmLoginSelKRSS->fncHMENUSTYLE($strTougetu);
            if (!$result['result']) {
                throw new \Exception($result['data'], 1);
            }
            if (count((array) $result['data']) == 0) {
                throw new \Exception("システムマスタが存在しません！");
            }
        } catch (\Exception $ex) {
            $result['result'] = FALSE;
            $result['data'] = $ex->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncSyozokuComboSet()
    {
        $tmpData = $_POST['data'];
        try {
            $this->FrmLoginSelKRSS = new FrmLoginSelKRSS();
            $result = $this->FrmLoginSelKRSS->getComboxListTable($tmpData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $ex) {
            $this->result['data'] = $ex->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncButton1Click()
    {
        $tmpData = $_POST['request'];
        try {
            $this->FrmLoginSelKRSS = new FrmLoginSelKRSS();
            $tmpval = $this->FrmLoginSelKRSS->fncButton1Click($tmpData['strTougetu'], $tmpData['UcUserID'], $tmpData['UcComboBox1'], strlen($tmpData['UcUserID']), strlen($tmpData['UcComboBox1']), $tmpData['cboSysKB']);
            if (!$tmpval['result']) {
                throw new \Exception($tmpval['data']);
            }
            $tmpval = $tmpval['data'];
        } catch (\Exception $ex) {
            $tmpval['data'] = $ex->getMessage();
        }

        $this->fncReturn($tmpval);
    }
}
