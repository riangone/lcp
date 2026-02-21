<?php
namespace App\Controller\HDKAIKEI;

use App\Controller\AppController;
use App\Model\HDKAIKEI\HDKAttachment;

//*******************************************
// * sample controller
//*******************************************
class HDKAttachmentController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    public $HDKAttachment = null;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHDKAIKEI');
    }
    //　デフォルトで最初に実行される機能
    public function index()
    {
        // Viewファイル呼出し
        $this->render('index', 'HDKAttachment_layout');
    }

    public function searchFiles()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            //データの取得
            if (isset($_POST['request'])) {
                $postData = $_POST['request'];

                $this->HDKAttachment = new HDKAttachment();

                $result = $this->HDKAttachment->searchFiles($postData);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                $dispMode = 'true';
                $resShiwake = $this->HDKAttachment->searchShiwake($postData);
                if (!$resShiwake['result']) {
                    throw new \Exception($resShiwake['data']);
                }
                if (count((array) $resShiwake['data']) == 0) {
                    $dispMode = 'nodata';
                } else {
                    if ($resShiwake['data'][0]['CSV_OUT_FLG'] == '1' || $resShiwake['data'][0]['XLSX_OUT_FLG'] == '1' || $resShiwake['data'][0]['PRINT_OUT_FLG'] == '1') {
                        $dispMode = 'none';
                    }
                    $checkRes = $this->HDKAttachment->fncDispModeSansyoChk($postData['SYOHY_NO']);
                    if (!$checkRes['result']) {
                        throw new \Exception($checkRes['data']);
                    }
                    $objRes = $checkRes['data'][0];
                    $Session = $this->request->getSession();
                    $PatternID = $Session->read('PatternID');
                    if ($this->ClsComFncHDKAIKEI->FncNv($objRes["CSV_OUT_FLG"] == "1") || $this->ClsComFncHDKAIKEI->FncNv($objRes["XLSX_OUT_FLG"] == "1") || ($this->ClsComFncHDKAIKEI->FncNv($objRes["HONBU_SYORIZUMI_FLG"] == "1") && $PatternID != $postData['CONST_HONBU_PTN_NO'] && $PatternID != $postData['CONST_ADMIN_PTN_NO'])) {
                        $dispMode = 'none';
                    } else
                        if ($this->ClsComFncHDKAIKEI->FncNv($objRes["PRINT_OUT_FLG"] == "1") && $PatternID != $postData['CONST_HONBU_PTN_NO'] && $PatternID != $postData['CONST_ADMIN_PTN_NO']) {
                            $dispMode = 'none';
                        }
                }

                $tmpJqgridShow = $this->ClsComFncHDKAIKEI->FncCreateJqGridShow($result['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $tmpJqgrid = $this->ClsComFncHDKAIKEI->FncCreateJqGridDataIndex($result["data"], $totalPage, $page, $tmpCount);
                $result = $tmpJqgrid;

                $result->dispMode = $dispMode;
            }

        } catch (\Exception $e) {
            $result['result'] = TRUE;
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
            //$pathUpLoad = $strPath . "/" . $this->ClsComFncHDKAIKEI->FncGetPath('HdkaikeiUpLoad');
            $pathUpLoad = $this->ClsComFncHDKAIKEI->FncGetPath('HdkaikeiUpLoad');
            if (!file_exists($pathUpLoad)) {
                if (!mkdir($pathUpLoad, 0777, TRUE)) {
                    throw new \Exception("フォルダー作成失敗しました。" .$pathUpLoad);
                }
                chmod($pathUpLoad, 0777);
            }
            for ($i = 0; $i < count($_FILES["file"]["error"]); $i++) {
                if ($_FILES["file"]["error"][$i] > 0) {
                    $result['result'] = FALSE;
                    $result['data'] = "ファイルのアップロードに失敗しました。";
                    throw new \Exception($result['data']);
                }
            }
            for ($i = 0; $i < count($_FILES["file"]["name"]); $i++) {
                $file_name = $_FILES["file"]["name"][$i];
                $uploadfile = $pathUpLoad . $file_name;
                if (move_uploaded_file($_FILES["file"]["tmp_name"][$i], $pathUpLoad . $file_name)) {
                    $result['result'] = TRUE;
                    $result['data'] = $uploadfile;
                } else {
                    $result['result'] = FALSE;
                    $result['data'] = 'ファイルのアップロードに失敗しました。' .  $pathUpLoad . $file_name;
                }
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        //POST方式的request，直接echo.
        //echo json_encode($result);
        $this->fncCheckFileReturn($result);
    }

    public function btnActionClick()
    {
        $blnTranFlg = FALSE;
        //トランザクションflg
        $result = array(
            'result' => FALSE,
            'error' => ''
        );

        try {
            $param = $_POST['data'];
            $files = $param['files'];
            $this->HDKAttachment = new HDKAttachment();
            $resShiwake = $this->HDKAttachment->searchShiwake($param);
            if (!$resShiwake['result']) {
                throw new \Exception($resShiwake['data']);
            }
            if ($resShiwake['data'][0]['CSV_OUT_FLG'] == '1' || $resShiwake['data'][0]['XLSX_OUT_FLG'] == '1' || $resShiwake['data'][0]['PRINT_OUT_FLG'] == '1') {
                throw new \Exception('W0025');
            }
            $checkRes = $this->HDKAttachment->fncDispModeSansyoChk($param['SYOHY_NO']);
            if (!$checkRes['result']) {
                throw new \Exception($checkRes['data']);
            }
            $objRes = $checkRes['data'][0];
            $Session = $this->request->getSession();
            $PatternID = $Session->read('PatternID');
            if ($this->ClsComFncHDKAIKEI->FncNv($objRes["CSV_OUT_FLG"] == "1") || $this->ClsComFncHDKAIKEI->FncNv($objRes["XLSX_OUT_FLG"] == "1") || ($this->ClsComFncHDKAIKEI->FncNv($objRes["HONBU_SYORIZUMI_FLG"] == "1") && $PatternID != $param['CONST_HONBU_PTN_NO'] && $PatternID != $param['CONST_ADMIN_PTN_NO'])) {
                throw new \Exception('W0025');
            } else
                if ($this->ClsComFncHDKAIKEI->FncNv($objRes["PRINT_OUT_FLG"] == "1") && $PatternID != $param['CONST_HONBU_PTN_NO'] && $PatternID != $param['CONST_ADMIN_PTN_NO']) {
                    throw new \Exception('W0025');
                }
            $resFiles = $this->HDKAttachment->searchFiles($param);
            if (!$resFiles['result']) {
                throw new \Exception($resFiles['data']);
            }

            if (count((array) $resFiles['data']) + count($files) > 5) {
                throw new \Exception('W0025');
            }
            //$strPath = dirname(dirname(dirname(__FILE__)));
            //$pathUpLoad = $strPath . "/" . $this->ClsComFncHDKAIKEI->FncGetPath('HdkaikeiUpLoad');
            $pathUpLoad = $this->ClsComFncHDKAIKEI->FncGetPath('HdkaikeiUpLoad');

            //20250113 lujunxia upd s
            //$pathMom = $strPath . "/" . $this->ClsComFncHDKAIKEI->FncGetPath('HdkaikeiMomUpLoad');
            //$pathMom = dirname($strPath) . "/" . $this->ClsComFncHDKAIKEI->FncGetPath('HdkaikeiMomUpLoad');
            $pathMom = $this->ClsComFncHDKAIKEI->FncGetPath('HdkaikeiMomUpLoad');
            //20250113 lujunxia upd e
            if (!file_exists($pathMom)) {
                if (!mkdir($pathMom, 0777, TRUE)) {
                    throw new \Exception("フォルダー作成失敗しました。" . $pathMom);
                }
                chmod($pathMom, 0777);
            }
            $syohyFilePath = $pathMom . $param['SYOHY_NO'] . "/";
            if (!file_exists($syohyFilePath)) {
                if (!mkdir($syohyFilePath, 0777, TRUE)) {
                    throw new \Exception("フォルダー作成失敗しました。");
                }
                chmod($syohyFilePath, 0777);
            }
            for ($i = 0; $i < count($files); $i++) {
                if (rename($pathUpLoad . $files[$i], $syohyFilePath . $files[$i])) {
                    $result['data'] = '';
                } else {
                    throw new \Exception("ファイルのアップロードに失敗しました。" . $pathUpLoad . $files[$i]);
                }
            }
            $this->HDKAttachment->Do_transaction();
            $blnTranFlg = TRUE;
            for ($i = 0; $i < count($files); $i++) {
                $search_res = $this->HDKAttachment->searchFiles($param, $files[$i]);
                if (!$search_res['result']) {
                    throw new \Exception($search_res['data']);
                }
                if (count((array) $search_res['data']) > 0) {
                    $updata_res = $this->HDKAttachment->fileUpdata($param['SYOHY_NO'], $param['EDA_NO'], $param['GYO_NO'], $files[$i]);
                    if (!$updata_res['result']) {
                        throw new \Exception($updata_res['data']);
                    }
                } else {
                    $insert_res = $this->HDKAttachment->fileInsert($param['SYOHY_NO'], $param['EDA_NO'], $param['GYO_NO'], $files[$i]);
                    if (!$insert_res['result']) {
                        throw new \Exception($insert_res['data']);
                    }
                }
            }
            $this->HDKAttachment->Do_commit();
            $blnTranFlg = FALSE;
            $result['result'] = TRUE;

        } catch (\Exception $e) {
            if ($blnTranFlg == TRUE) {
                $this->HDKAttachment->Do_rollback();
            }
            $result['result'] = FALSE;
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
            $this->HDKAttachment = new HDKAttachment();
            $resShiwake = $this->HDKAttachment->searchShiwake($param);
            if (!$resShiwake['result']) {
                throw new \Exception($resShiwake['data']);
            }
            if ($resShiwake['data'][0]['CSV_OUT_FLG'] == '1' || $resShiwake['data'][0]['XLSX_OUT_FLG'] == '1' || $resShiwake['data'][0]['PRINT_OUT_FLG'] == '1') {
                throw new \Exception('W0025');
            }
            $checkRes = $this->HDKAttachment->fncDispModeSansyoChk($param['SYOHY_NO']);
            if (!$checkRes['result']) {
                throw new \Exception($checkRes['data']);
            }
            $objRes = $checkRes['data'][0];
            $Session = $this->request->getSession();
            $PatternID = $Session->read('PatternID');
            if ($this->ClsComFncHDKAIKEI->FncNv($objRes["CSV_OUT_FLG"] == "1") || $this->ClsComFncHDKAIKEI->FncNv($objRes["XLSX_OUT_FLG"] == "1") || ($this->ClsComFncHDKAIKEI->FncNv($objRes["HONBU_SYORIZUMI_FLG"] == "1") && $PatternID != $param['CONST_HONBU_PTN_NO'] && $PatternID != $param['CONST_ADMIN_PTN_NO'])) {
                throw new \Exception('W0025');
            } else
                if ($this->ClsComFncHDKAIKEI->FncNv($objRes["PRINT_OUT_FLG"] == "1") && $PatternID != $param['CONST_HONBU_PTN_NO'] && $PatternID != $param['CONST_ADMIN_PTN_NO']) {
                    throw new \Exception('W0025');
                }
            $delete_res = $this->HDKAttachment->fileDelete($param);
            if (!$delete_res['result']) {
                throw new \Exception($delete_res['data']);
            }
            $result['result'] = TRUE;
            $result['data'] = '';
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }
}
