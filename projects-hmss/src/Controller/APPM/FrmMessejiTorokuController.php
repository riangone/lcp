<?php
/**
 * 説明：
 *
 *
 * @author wangying
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL　　
 * 20170503                                        变更                              WANGYING　　
 * 20170504                                        变更                              LQS
 * 20170505                                        画像のパス变更                      WANGYING
 * 20170508                                        画像变更                           WANGYING
 * 20170508                                        code变更                          YIN
 * * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\APPM;

use App\Controller\AppController;
use App\Model\APPM\FrmMessejiToroku;
//*******************************************
// * sample controller
//*******************************************
class FrmMessejiTorokuController extends AppController
{
    public $autoLayout = TRUE;
    public $result = array();
    public $img = "";
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsFncLog');
    }
    public function index()
    {
        // Viewファイル呼出し
        $this->render('index', 'FrmMessejiToroku_layout');
    }

    //'**********************************************************************
    //'処 理 名：入力欄取得
    //'関 数 名：searchTCODE
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function searchTCODE()
    {
        $result = array();
        //20170504 LQS DEL S
        //$arr = array();
        //20170504 LQS DEL E

        try {
            $postData = $_POST["data"]["request"];
            $FrmMessejiToroku = new FrmMessejiToroku();
            //20170504 LQS UPD S
            // //コードマスタより「内容区分」のデータを一覧にセット
            // $arr['content'] = $FrmMessejiToroku -> searchTCODE("1");
            // //コードマスタより「有無区分（共通）」のデータを一覧にセット
            // $arr['have'] = $FrmMessejiToroku -> searchTCODE("2");
            // //コードマスタより「表示非表示（共通）」のデータを一覧にセット
            // $arr['show'] = $FrmMessejiToroku -> searchTCODE("3");
            // //コードマスタより「（共通）要不要区分」のデータを一覧にセット
            // $arr['common'] = $FrmMessejiToroku -> searchTCODE("4");
            // //車点検内容マスタより「内容コード」のデータを一覧にセット
            // $arr['code'] = $FrmMessejiToroku -> searchTNAYIO();
            //
            // $result = $arr;
            // //20170503 WANG UPD S
            // // if (!$result['result'])
            // // {
            // // throw new \Exception($result['data']);
            // // }
            // if (!$result['content']['result'])
            // {
            // throw new \Exception($result['content']['data']);
            // }
            // if (!$result['have']['result'])
            // {
            // throw new \Exception($result['have']['data']);
            // }
            // if (!$result['show']['result'])
            // {
            // throw new \Exception($result['show']['data']);
            // }
            // if (!$result['common']['result'])
            // {
            // throw new \Exception($result['common']['data']);
            // }
            // if (!$result['code']['result'])
            // {
            // throw new \Exception($result['code']['data']);
            // }
            // $result['result'] = TRUE;
            // //20170503 WANG UPD E
            $result['result'] = TRUE;
            $result['content'] = $FrmMessejiToroku->searchTCODE("1", $postData);
            if (!$result['content']['result']) {
                throw new \Exception($result['content']['data']);
            }
            $result['have'] = $FrmMessejiToroku->searchTCODE("2", $postData);
            if (!$result['have']['result']) {
                throw new \Exception($result['have']['data']);
            }
            $result['show'] = $FrmMessejiToroku->searchTCODE("3", $postData);
            if (!$result['show']['result']) {
                throw new \Exception($result['show']['data']);
            }
            $result['common'] = $FrmMessejiToroku->searchTCODE("4", $postData);
            if (!$result['common']['result']) {
                throw new \Exception($result['common']['data']);
            }
            $result['code'] = $FrmMessejiToroku->searchTNAYIO($postData);
            if (!$result['code']['result']) {
                throw new \Exception($result['code']['data']);
            }
            //20170504 LQS UPD E
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：取得したIMGを画面に表示する
    //'関 数 名：fncSearch
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function fncSearch()
    {
        $result = array();
        $arr = array();

        try {
            $postData = $_POST["data"]["request"];
            //20241224 lujunxia upd s
            //20170505 WANG UPD S
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
            //$strPath = dirname(dirname(dirname(__FILE__)));
            //20170505 WANG UPD E
            //20241224 lujunxia upd e
            $pathCopyFrom = $strPath . "/" . "webroot" . "/" . 'img';
            $pathCopyTo = $strPath . "/" . "webroot" . "/" . 'temp';

            $stCopyFromFileName = scandir($pathCopyFrom);
            //20170519 LQS INS S
            $FrmMessejiToroku = new FrmMessejiToroku();

            $arr['other'] = $FrmMessejiToroku->fncSearch($postData);
            if ($arr['other']['row'] > 0) {
                $imgName = $arr['other']['data'][0]['MEIN_GAZO_MEI'];
                for ($i = 0; $i < count($stCopyFromFileName); $i++) {
                    //サーバIMGフォルダに画像ファイルが存在しない場合
                    if ($stCopyFromFileName[$i] == $arr['other']['data'][0]['MEIN_GAZO_MEI']) {
                        copy($pathCopyFrom . "/" . $imgName, $pathCopyTo . "/" . $imgName);
                        $arr['img'] = $imgName;
                        $arr['ID'] = $postData['ID'];
                        break;
                    }
                }
            }
            //20170519 LQS INS E

            //20170519 LQS DEL S
            // for ($i = 0; $i < count($stCopyFromFileName); $i++)
            // {
            // //サーバIMGフォルダに画像ファイルが存在しない場合
            // if ($stCopyFromFileName[$i] == $postData['ID'] . ".jpg" || $stCopyFromFileName[$i] == $postData['ID'] . ".png")
            // {
            // $imgName = $stCopyFromFileName[$i];
            // copy($pathCopyFrom . "/" . $imgName, $pathCopyTo . "/" . $imgName);
            // $arr['img'] = $imgName;
            // $arr['ID'] = $postData['ID'];
            // }
            // }

            // $FrmMessejiToroku = new FrmMessejiToroku();
// 
            // $arr['other'] = $FrmMessejiToroku -> fncSearch($postData);
            //20170519 LQS DEL E

            $result = $arr;

            //20170504 LQS UPD S
            // if (!$result['result'])
            // {
            // throw new Exception($result['data']);
            // }
            $result['result'] = TRUE;
            if (!$result['other']['result']) {
                throw new \Exception($result['other']['data']);
            }
            //20170504 LQS UPD E
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：指定された画像ファイルをサーバへアップロードする
    //'関 数 名：fncFile
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function fncFile()
    {
        try {
            //20241224 lujunxia upd s
            //20170505 WANG UPD S
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
            //$strPath = dirname(dirname(dirname(__FILE__)));
            //20170505 WANG UPD E
            //20241224 lujunxia upd e

            $pathUpLoad = $strPath . "/" . "webroot" . "/" . 'temp';

            if (!file_exists($pathUpLoad)) {
                mkdir($pathUpLoad, 0777, TRUE);
            }

            if ($_FILES["file"]["error"] > 0) {
                //20170503 WANG DEL S
                //$result['result'] = FALSE;
                //20170503 WANG DEL E
                $result['data'] = "ファイルのアップロードに失敗しました。";
                throw new \Exception($result['data']);
            } else {
                $file_name = $this->changeFileName($_FILES["file"]["name"]);
                $result['img'] = $file_name;

                if (move_uploaded_file($_FILES["file"]["tmp_name"], $pathUpLoad . '/' . $file_name)) {
                    $result['result'] = TRUE;
                    //20170504 WANG DEL S
                    //$result['data'] = 'succeed';
                    //20170504 WANG DEL E
                } else {
                    $result['result'] = FALSE;
                    //20170504 WANG DEL S
                    //$result['data'] = 'ファイルのアップロードに失敗しました。';
                    //20170504 WANG DEL E
                }
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：採番テーブルから メッセージコードを採番する
    //'関 数 名：FncTourokuTSaiban
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function fncTourokuTSaiban()
    {
        $result = array();
        //20170504 WANG DEL S
        //$arr = array();
        //20170504 WANG DEL E

        try {
            $postData = $_POST["data"]["request"];
            $FrmMessejiToroku = new FrmMessejiToroku();

            $DB_Conn = $FrmMessejiToroku->Do_conn();
            if (!$DB_Conn['result']) {
                throw new \Exception($DB_Conn['data']);
            }
            $FrmMessejiToroku->Do_transaction();

            $search = $FrmMessejiToroku->FncSaibanSearch($postData);
            //20170504 WANG INS S
            if (!$search['result']) {
                throw new \Exception($search['data']);
            }
            //20170509 YIN DEL S
            $result['result'] = TRUE;
            //20170509 YIN DEL E
            //20170504 WANG INS E
            //採番データ0件時
            if ($search['row'] == 0) {
                //20170504 WANG UPD S
                //$arr['insert'] = $FrmMessejiToroku -> FncSaibanInsert();
                //20170509 YIN UPD S
                // $result['insert'] = $FrmMessejiToroku -> FncSaibanInsert();
                // if (!$result['insert']['result'])
                // {
                // throw new Exception($result['insert']['data']);
                // }
                $result = $FrmMessejiToroku->FncSaibanInsert($postData);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                //20170509 YIN UPD E
                //$arr['insMsg'] = $FrmMessejiToroku -> FncSaibanSearch();
                //20170509 YIN UPD S
                // $result['insMsg'] = $FrmMessejiToroku -> FncSaibanSearch();
                // if (!$result['insMsg']['result'])
                // {
                // throw new Exception($result['insMsg']['data']);
                // }
                $result = $FrmMessejiToroku->FncSaibanSearch($postData);
                if (!$result['result']) {

                    throw new \Exception($result['data']);
                }
                //20170509 YIN UPD E
                //20170504 WANG UPD E
            }
            //採番データ1件時
            //20170504 WANG UPD S
            if ($search['row'] == 1) {
                //$arr['insert']['result'] = TRUE;
                $result = $search;
            }
            // else
            // {
            // $result['insMsg']['result'] = FALSE;
            // }

            $FrmMessejiToroku->Do_commit();

            //$result = $arr;
            //20170504 WANG UPD E
            //20170504 WANG DEL S
            //if (!$result['insMsg']['result']) 
            //{
            //throw new Exception($this -> result['insMsg']['data']);
            //throw new Exception($result['insMsg']['data']);
            //}
            //20170504 WANG DEL E
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $FrmMessejiToroku->Do_rollback();
        }
        if (isset($FrmMessejiToroku->conn_orl)) {
            $FrmMessejiToroku->Do_close();
            unset($FrmMessejiToroku->conn_orl);
        }

        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：DBデータ新規登録処理を実行する
    //'関 数 名：FncTourokuConfirm
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function fncTourokuConfirm()
    {
        $result = array();

        $postData = $_POST["data"]["request"];

        //20170505 WANG DEL S
        //$url = dirname(dirname(dirname(dirname(__FILE__)))) . "/" . "webroot" . "/" . 'img';
        //$url = dirname(dirname(dirname(__FILE__))) . "/" . "webroot" . "/" . 'temp';
        //20170505 WANG DEL E

        //$arr = explode("/", $url);
        //$long = count($arr) - 1;
        //20170505 WANG UPD S
        //$url = $arr[$long - 2] . "/" . $arr[$long - 1] . "/" . $arr[$long];
        $url = $postData['txtImgUrl'];
        //20170505 WANG UPD E
        try {
            $FrmMessejiToroku = new FrmMessejiToroku();

            $DB_Conn = $FrmMessejiToroku->Do_conn();
            if (!$DB_Conn['result']) {
                throw new \Exception($DB_Conn['data']);
            }
            $FrmMessejiToroku->Do_transaction();
            //メッセージ （t_messeji）テーブル更新処理を実行する
            $result = $FrmMessejiToroku->FncTourokuConfirm($postData, $url);
            //20170504 WANG UPD S
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $result = $FrmMessejiToroku->FncUpdateSaiban($postData);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $result['result'] = TRUE;
            //採番テーブル更新処理を実行する
            // if ($result['msg']['number_of_rows'] > 0)
            // {
            // $result['ym'] = $FrmMessejiToroku -> FncUpdateSaiban();
            // }
            // else
            // {
            // $result['msg']['result'] = FALSE;
            // }
            //20170504 WANG UPD E
            //サーバIMGフォルダに画像ファイルを保存
            //20241224 lujunxia upd s
            //20170505 WANG UPD S
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
            //$strPath = dirname(dirname(dirname(__FILE__)));
            //20170505 WANG UPD E
            //20241224 lujunxia upd e

            $pathImg = $strPath . "/" . "webroot" . "/" . 'img';
            $pathImgTMP = $strPath . "/" . "webroot" . "/" . 'temp';

            //20170504 WANG DEL S
            //if ($result['ym']['number_of_rows'] > 0)
            //20170504 WANG DEL E
            //{
            if (!file_exists($pathImg)) {
                mkdir($pathImg, 0777, TRUE);
            }

            $arr = explode(".", $postData['txtImg']);
            //文件类型
            $long = count($arr) - 1;
            $file_type = $arr[$long];
            //文件名
            $img_name = '';

            $img_name = $postData['txtCode'] . '.' . $file_type;

            //20170508 LQS INS S
            if ($postData['tmp'] != "")
            //20170508 LQS INS E
            {
                copy($pathImgTMP . '/' . $postData['tmp'], $pathImg . '/' . $img_name);
            }

            if (is_file($pathImgTMP . '/' . $postData['tmp'])) {
                chmod($pathImgTMP . '/' . $postData['tmp'], 0777);
                unlink($pathImgTMP . '/' . $postData['tmp']);
            }
            //20170504 WANG DEL S
            //$result['ym']['result'] = TRUE;
            //}

            //else
            //{
            //$result['ym']['result'] = FALSE;
            //}
            //20170504 WANG DEL E
            $result['data'] = '';

            $FrmMessejiToroku->Do_commit();
            //20170504 WANG DEL S
            //if (!$result['result'])
            //{
            //throw new Exception($this -> result['data']);
            //throw new Exception($result['data']);
            //}
            //20170504 WANG DEL S
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $FrmMessejiToroku->Do_rollback();
        }
        if (isset($FrmMessejiToroku->conn_orl)) {
            $FrmMessejiToroku->Do_close();
            unset($FrmMessejiToroku->conn_orl);
        }

        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：DBデータ変更処理を実行する
    //'関 数 名：FncUpdateConfirm
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function fncUpdateConfirm()
    {
        $result = array();
        $postData = $_POST['data']['request'];

        try {
            $FrmMessejiToroku = new FrmMessejiToroku();

            $DB_Conn = $FrmMessejiToroku->Do_conn();
            if (!$DB_Conn['result']) {
                throw new \Exception($DB_Conn['data']);
            }
            $FrmMessejiToroku->Do_transaction();

            //20170505 WANG UPD S
            // $url = dirname(dirname(dirname(dirname(__FILE__)))) . "/" . "webroot" . "/" . 'img';
            //$arr = explode("/", $url);
            //$long = count($arr) - 1;
            //$url = $arr[$long - 2] . "/" . $arr[$long - 1] . "/" . $arr[$long];
            $url = $postData['txtImgUrl'];
            //20170505 WANG UPD E

            $result = $FrmMessejiToroku->FncUpdateConfirm($postData, $url);

            //20170503 WANG INS S
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //20170503 WANG INS E

            //20241224 lujunxia upd s
            //20170505 WANG UPD S
            //$strPath = dirname(dirname(dirname(__FILE__)));
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
            //20170505 WANG UPD E
            //20241224 lujunxia upd e

            $pathImg = $strPath . "/" . "webroot" . "/" . 'img';
            $pathImgTMP = $strPath . "/" . "webroot" . "/" . 'temp';

            if ($result['number_of_rows'] == 1) {
                //20170508 WANG INS S
                if ($postData['tmp'] != "")
                //20170508 WANG INS E
                {
                    $arr = explode(".", $postData['txtImg']);
                    //文件类型
                    $long = count($arr) - 1;
                    $file_type = $arr[$long];
                    //文件名
                    $img_name = '';

                    $img_name = $postData['txtCode'] . '.' . $file_type;

                    copy($pathImgTMP . '/' . $postData['tmp'], $pathImg . '/' . $img_name);

                    //20170508 WANG UPD S
                    //chmod($pathImgTMP . '/' . $postData['tmp'] . $postData['txtCode'] . '.' . $file_type, 0777);
                    //unlink($pathImgTMP . '/' . $postData['tmp'] . $postData['txtCode'] . '.' . $file_type);
                    chmod($pathImgTMP . '/' . $postData['tmp'], 0777);
                    unlink($pathImgTMP . '/' . $postData['tmp']);
                }
                //20170508 WANG UPD E
            }
            //20170509 YIN INS S
            $result['data'] = '';
            //20170509 YIN INS E

            $FrmMessejiToroku->Do_commit();

            //20170503 WANG DEL S
            // if (!$result['result'])
            // {
            // throw new \Exception($result['data']);
            // }
            //20170503 WANG DEL E
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $FrmMessejiToroku->Do_rollback();
        }
        if (isset($FrmMessejiToroku->conn_orl)) {
            $FrmMessejiToroku->Do_close();
            unset($FrmMessejiToroku->conn_orl);
        }

        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：DBデータ削除処理を実行する
    //'関 数 名：FncDeleteConfirm
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function fncDeleteConfirm()
    {
        $result = array();
        $postData = $_POST['data']['request'];

        try {
            $FrmMessejiToroku = new FrmMessejiToroku();

            $DB_Conn = $FrmMessejiToroku->Do_conn();
            if (!$DB_Conn['result']) {
                throw new \Exception($DB_Conn['data']);
            }
            $FrmMessejiToroku->Do_transaction();

            $result = $FrmMessejiToroku->FncDeleteConfirm($postData);

            //20170503 WANG INS S
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //20170503 WANG INS E
            //20241224 lujunxia upd s
            //20170505 WANG UPD S
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
            //$strPath = dirname(dirname(dirname(__FILE__)));
            //20170505 WANG UPD E
            //20241224 lujunxia upd e

            $pathImg = $strPath . "/" . "webroot" . "/" . 'img';

            if ($result['number_of_rows'] == 1) {
                $arr = explode(".", $postData['txtImg']);
                //文件类型
                $long = count($arr) - 1;
                $file_type = $arr[$long];
                //文件名
                $img_name = $postData['txtCode'] . '.' . $file_type;

                if (file_exists($pathImg . '/' . $img_name)) {
                    chmod($pathImg . '/' . $img_name, 0777);
                    unlink($pathImg . '/' . $img_name);
                }
            }

            //20170509 YIN INS S
            $result['data'] = '';
            //20170509 YIN INS E

            $FrmMessejiToroku->Do_commit();

            //20170503 WANG DEL S
            // if (!$result['result'])
            // {
            // throw new \Exception($result['data']);
            // }
            //20170503 WANG DEL E
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $FrmMessejiToroku->Do_rollback();
        }
        if (isset($FrmMessejiToroku->conn_orl)) {
            $FrmMessejiToroku->Do_close();
            unset($FrmMessejiToroku->conn_orl);
        }

        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：入力文字の機種依存チェック
    //'関 数 名：fncCheckStr
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function fncCheckStr()
    {
        try {
            $postData = $_POST["data"]["request"];

            if (($postData['title']) != "") {
                if (strlen($postData['title']) !== strlen(mb_convert_encoding(mb_convert_encoding($postData['title'], 'SJIS', 'UTF-8'), 'UTF-8', 'SJIS'))) {
                    $result['title']['result'] = FALSE;
                } else {
                    $result['title']['result'] = TRUE;
                }
            } else {
                $result['title']['result'] = TRUE;
            }
            if (($postData['img']) != "") {
                if (strlen($postData['img']) !== strlen(mb_convert_encoding(mb_convert_encoding($postData['img'], 'SJIS', 'UTF-8'), 'UTF-8', 'SJIS'))) {
                    $result['img']['result'] = FALSE;
                } else {
                    $result['img']['result'] = TRUE;
                }
            } else {
                $result['img']['result'] = TRUE;
            }
            if (($postData['imgUrl']) != "") {
                if (strlen($postData['imgUrl']) !== strlen(mb_convert_encoding(mb_convert_encoding($postData['imgUrl'], 'SJIS', 'UTF-8'), 'UTF-8', 'SJIS'))) {
                    $result['imgUrl']['result'] = FALSE;
                } else {
                    $result['imgUrl']['result'] = TRUE;
                }
            } else {
                $result['imgUrl']['result'] = TRUE;
            }
            if (($postData['msg1']) != "") {
                if (strlen($postData['msg1']) !== strlen(mb_convert_encoding(mb_convert_encoding($postData['msg1'], 'SJIS', 'UTF-8'), 'UTF-8', 'SJIS'))) {
                    $result['code1']['result'] = FALSE;
                } else {
                    $result['code1']['result'] = TRUE;
                }
            } else {
                $result['code1']['result'] = TRUE;
            }
            if (($postData['msg2']) != "") {
                if (strlen($postData['msg2']) !== strlen(mb_convert_encoding(mb_convert_encoding($postData['msg2'], 'SJIS', 'UTF-8'), 'UTF-8', 'SJIS'))) {
                    $result['code2']['result'] = FALSE;
                } else {
                    $result['code2']['result'] = TRUE;
                }
            } else {
                $result['code2']['result'] = TRUE;
            }
            if (($postData['msg3']) != "") {
                if (strlen($postData['msg3']) !== strlen(mb_convert_encoding(mb_convert_encoding($postData['msg3'], 'SJIS', 'UTF-8'), 'UTF-8', 'SJIS'))) {
                    $result['code3']['result'] = FALSE;
                } else {
                    $result['code3']['result'] = TRUE;
                }
            } else {
                $result['code3']['result'] = TRUE;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：ファイル名：8桁の乱数 ＆ "." ＆ 拡張子
    //'関 数 名：changeFileName
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function changeFileName($param)
    {
        $arr = explode(".", $param);
        //文件类型
        $long = count($arr) - 1;
        $file_type = $arr[$long];
        //文件名
        $file_name = '';
        //生成的随机数位数
        $num = 8;
        //随机数组成
        $a = array(
            'a',
            'b',
            'c',
            'd',
            'e',
            'f',
            'g',
            'h',
            'i',
            'j',
            'k',
            'l',
            'm',
            'n',
            'o',
            'p',
            'q',
            'r',
            's',
            't',
            'u',
            'v',
            'w',
            'x',
            'y',
            'z',
            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
            'G',
            'H',
            'I',
            'J',
            'K',
            'L',
            'M',
            'N',
            'O',
            'P',
            'Q',
            'R',
            'S',
            'T',
            'U',
            'V',
            'W',
            'X',
            'Y',
            'Z',
            '0',
            '1',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7',
            '8',
            '9'
        );

        for ($i = 0; $i < $num; $i++) {
            $file_name .= $a[rand(0, 61)];
        }

        $file_name = $file_name . '.' . $file_type;

        return $file_name;
    }

}
