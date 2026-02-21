<?php
namespace App\Controller\HMAUD;

use App\Controller\AppController;
use App\Model\HMAUD\HMAUDGijirokuULDL;

//*******************************************
// * sample controller
//*******************************************
class HMAUDGijirokuULDLController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
// public $autoRender = false;
    public $HMAUDGijirokuULDL;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        // $this->loadComponent('ClsLogControl');
        $this->loadComponent('ClsComFncHMAUD');
    }
    public $uploadfile;

    //　デフォルトで最初に実行される機能
    public function index()
    {
        $this->render('index', 'HMAUDGijirokuULDL_layout');
    }

    public function pageLoad()
    {
        $this->HMAUDGijirokuULDL = new HMAUDGijirokuULDL();
        $res = array(
            'result' => FALSE,
            'data' => array(),
            'error' => ''
        );
        try {
            //検索条件・クールには 現在のクール数を初期表示
            $cour = $this->HMAUDGijirokuULDL->getInitializeCour();
            if (!$cour['result']) {
                throw new \Exception($cour['data']);
            }

            $admin = $this->HMAUDGijirokuULDL->getAdmin();
            if (!$admin['result']) {
                throw new \Exception($admin['data']);
            }
            //20230310 CAI INS S
            $viewer = $this->HMAUDGijirokuULDL->getViewer();
            if (!$viewer['result']) {
                throw new \Exception($viewer['data']);
            }
            //20230310 CAI INS E

            $res['data']['cour'] = $cour['data'];
            $res['data']['admin'] = $admin['data'];
            //20230310 CAI INS S
            $res['data']['viewer'] = $viewer['data'];
            //20230310 CAI INS E
            $res['result'] = true;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    public function isExstsFile()
    {
        //トランザクションflg
        $result = array(
            'result' => FALSE,
            'error' => ''
        );

        try {
            $param = $_POST['data'];

            $this->HMAUDGijirokuULDL = new HMAUDGijirokuULDL();
            $res = $this->HMAUDGijirokuULDL->checkFile($param);
            if (!$res['result']) {
                throw new \Exception($res['data']);
            }
            if (count((array) $res['data']) > 0) {
                $result['data'] = "IsExstsFile";
            } else {
                $result['data'] = "";
            }
            $result['result'] = TRUE;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //ファイルのアップロード
    public function fncCheckFile()
    {
        $result = array(
            'result' => FALSE,
            'data' => ''
        );

        try {
            $strPath = dirname(dirname(dirname(__FILE__)));
//            $pathUpLoad = $strPath . "/" . $this->ClsComFncHMAUD->FncGetPath('HmaudUpLoad');
            $pathUpLoad = $this->ClsComFncHMAUD->FncGetPath('HmaudUpLoad');
            if (!file_exists($pathUpLoad)) {
                if (!mkdir($pathUpLoad, 0777, TRUE)) {
                    throw new \Exception("フォルダー作成失敗しました。");
                }
                chmod($pathUpLoad, 0777);
            }
            if ($_FILES["file"]["error"] > 0) {
                $result['result'] = FALSE;
                $result['data'] = "ファイルのアップロードに失敗しました。";
                throw new \Exception($result['data']);
            } else {
                $file_name = $_FILES["file"]["name"];
                $this->uploadfile = $pathUpLoad . $file_name;
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $pathUpLoad . $file_name)) {
                    $result['result'] = TRUE;
                    $result['data'] = $this->uploadfile;
                } else {
                    $result['result'] = FALSE;
                    $result['data'] = 'ファイルのアップロードに失敗しました。';
                }
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncCheckFileReturn($result);
    }

    public function btnActionClick()
    {
        //トランザクションflg
        $result = array(
            'result' => FALSE,
            'error' => ''
        );

        try {
            $param = $_POST['data'];
            $file_name = $param['txtPath'];
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
            $upLoadPath = dirname(dirname(dirname(__FILE__)));
//            $pathUpLoad = $upLoadPath . "/" . $this->ClsComFncHMAUD->FncGetPath('HmaudUpLoad');
            $pathUpLoad = $this->ClsComFncHMAUD->FncGetPath('HmaudUpLoad');
//            $pathMom = $strPath . "/" . $this->ClsComFncHMAUD->FncGetPath('HmaudMomUpLoad');
            $pathMom = $this->ClsComFncHMAUD->FncGetPath('HmaudMomUpLoad');
            if (!file_exists($pathMom)) {
                if (!mkdir($pathMom, 0777, TRUE)) {
                    throw new \Exception("フォルダー作成失敗しました。");
                }
                chmod($pathMom, 0777);
            }
            $courFilePath = $pathMom . $param['COURS'] . "/";
            if (!file_exists($courFilePath)) {
                if (!mkdir($courFilePath, 0777, TRUE)) {
                    throw new \Exception("フォルダー作成失敗しました。");
                }
                chmod($courFilePath, 0777);
            }
            if (rename($pathUpLoad . $file_name, $courFilePath . $file_name)) {
                $result['result'] = TRUE;
                $result['data'] = $this->uploadfile;
            } else {
                $result['result'] = FALSE;
                $result['data'] = 'ファイルのアップロードに失敗しました。';
            }
            $this->HMAUDGijirokuULDL = new HMAUDGijirokuULDL();
            $update_res = $this->HMAUDGijirokuULDL->fileInsert($param);
            if (!$update_res['result']) {
                throw new \Exception($update_res['data']);
            }

            $result['result'] = TRUE;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function getFileList()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            $data = array();
            if (isset($_POST['request'])) {
                $cour = $_POST['request']['COUR'];
                $keyword = $_POST['request']['keyword'];

                $this->HMAUDGijirokuULDL = new HMAUDGijirokuULDL();
                $files_res = $this->HMAUDGijirokuULDL->getFiles($cour, $keyword);
                if (!$files_res['result']) {
                    throw new \Exception($files_res['data']);
                }
                $member = $this->HMAUDGijirokuULDL->getmember($cour);
                if (!$member['result']) {
                    throw new \Exception($member['data']);
                }
                $data = $files_res['data'];
            }

            $tmpJqgridShow = $this->ClsComFncHMAUD->FncCreateJqGridShow($data);
            $start = $tmpJqgridShow['start'];
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];
            $result = $this->ClsComFncHMAUD->FncCreateJqGridDataIndex($data, $totalPage, $page, $tmpCount, $start);
            $result->member = $member['data'];
        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function btnDeleteClick()
    {
        //トランザクションflg
        $result = array(
            'result' => FALSE,
            'error' => ''
        );

        try {
            $param = $_POST['data'];
            $gridDatas = $param['data'];
            $this->HMAUDGijirokuULDL = new HMAUDGijirokuULDL();
            $delete_res = $this->HMAUDGijirokuULDL->deleteFiles($gridDatas);
            if (!$delete_res['result']) {
                throw new \Exception($delete_res['data']);
            }

            $result['result'] = TRUE;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

}
