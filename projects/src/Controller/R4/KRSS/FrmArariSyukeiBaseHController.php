<?php

/**
 * 説明：
 *
 *
 * @author yinhuaiyu
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL　　　　　　　　
 * * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\R4\KRSS;

use App\Controller\AppController;
use App\Model\R4\KRSS\FrmArariSyukeiBaseH;

class FrmArariSyukeiBaseHController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $Session;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    public $FrmArariSyukeiBaseH = "";
    //　デフォルトで最初に実行される機能
    public function index()
    {

        $this->render('index', 'FrmArariSyukeiBaseH_layout');
    }

    // '**********************************************************************
    // '処 理 名：基本情報を抽出する
    // '関 数 名：subSpreadReShow
    // '引    数：
    // '戻 り 値：ＳＱＬ文
    // '処理説明：基本情報を抽出する
    // '**********************************************************************
    public function subSpreadReShow()
    {

        try {

            $this->FrmArariSyukeiBaseH = new FrmArariSyukeiBaseH();

            $result1 = $this->FrmArariSyukeiBaseH->fncListSelect();
            if (!$result1['result']) {
                throw new \Exception($result1['data']);
            }
            $result2 = $this->FrmArariSyukeiBaseH->fncSyaSelect();
            if (!$result2['result']) {
                throw new \Exception($result2['data']);
            }
            $result['data1'] = $result1['data'];
            $result['data2'] = $result2['data'];
            $result['data'] = "";
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    // '**********************************************************************
    // '処 理 名：更新処理
    // '関 数 名：cmdUpdate_Click
    // '引    数：
    // '戻 り 値：
    // '処理説明：更新処理
    // '**********************************************************************
    public function cmdUpdateClick()
    {
        $result = array();
        try {
            if (isset($_POST['data'])) {
                $lineArr = json_decode($_POST['data'], true);
            }

            $this->FrmArariSyukeiBaseH = new FrmArariSyukeiBaseH();

            $this->Session = $this->request->getSession();
            $UPDUSER = $this->Session->read('login_user');
            $UPDCLTNM = $this->request->clientIp();
            $UPDAPP = "frmArariSyukeiBaseH";
            $result1 = $this->FrmArariSyukeiBaseH->frmBasehCdSel();
            $data = $result1['data'];

            $result = $this->FrmArariSyukeiBaseH->Do_conn();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $this->FrmArariSyukeiBaseH->Do_transaction();

            foreach ($lineArr['lineArr'] as $value) {
                $flag = 1;
                foreach ((array) $data as $value1) {

                    if ($value['BASEH_CD'] == $value1['BASEH_CD']) {

                        $result = $this->FrmArariSyukeiBaseH->frmUpDate($value, $UPDUSER, $UPDCLTNM, $UPDAPP);

                        $flag = 2;
                    }

                }

                if ($flag == 1) {

                    $result = $this->FrmArariSyukeiBaseH->frmInsert($value, $UPDUSER, $UPDCLTNM, $UPDAPP);
                }
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
            }

            $this->FrmArariSyukeiBaseH->Do_commit();
            $result['result'] = TRUE;
            $result['data'] = '';

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $this->FrmArariSyukeiBaseH->Do_rollback();
        }
        $this->FrmArariSyukeiBaseH->Do_close();

        $this->fncReturn($result);
    }

    // '**********************************************************************
    // '処 理 名：車種マスタを検索
    // '関 数 名：fncToriNmSelect
    // '引    数：
    // '戻 り 値：
    // '処理説明：車種マスタを検索
    // '**********************************************************************
    public function fncToriNmSelect()
    {
        $postData = '';
        try {
            if (isset($_POST['data']['lineTableData'])) {
                $postData = $_POST['data']['lineTableData'];
            }

            if ($postData == '') {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            } else {
                $this->FrmArariSyukeiBaseH = new FrmArariSyukeiBaseH();
                $result = $this->FrmArariSyukeiBaseH->fncToriNmSelect($postData);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }
}
