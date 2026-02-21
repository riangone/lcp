<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmSyanaiLeasePrint;

class FrmSyanaiLeasePrintController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $result;
    public $filePathName;
    public $FrmSyanaiLeasePrint;

    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsCreateCsv');
    }
    public function index()
    {
        $this->render('index', 'FrmSyanaiLeasePrint_layout');
    }

    //**********************************************************************
    //処 理 名：ﾌｫｰﾑﾛｰﾄﾞ
    //関 数 名：frmsyanaiLeasePrint_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：初期設定
    //**********************************************************************
    public function fncFrmSyanaiLeasePrintLoad()
    {
        try {
            //モデルの仕様するクラスを定義
            $this->FrmSyanaiLeasePrint = new FrmSyanaiLeasePrint();
            //モデルクラスのselect処理を呼出し
            $this->result = $this->FrmSyanaiLeasePrint->frmSyanaiLeasePrint_Load_select();
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            $this->fncReturn($this->result);
        } catch (\Exception $ex) {
            $this->result['result'] = False;
            $this->result['data'] = $ex->getMessage();
            $this->fncReturn($this->result);
        }
    }

    /*
           **********************************************************************
           処 理 名：名称取得
           関 数 名：txtBusyoCDValidating
           引    数：$部署_val
           戻 り 値：無し
           処理説明：部署名称を取得する
           **********************************************************************
           */
    public function fncTxtBusyoCDValidating()
    {
        try {
            //モデルクラスのselect処理を呼出し
            //$this -> result = $this -> FrmSyanaiLeasePrint -> fncTxtBusyoCDValidating_select();
            $objBusyoMst = $this->ClsComFnc->GS_BUSYOMST;
            $this->result['result'] = FALSE;
            if (trim($_POST['data']['busyoCD']) != "") {
                $tf = $this->ClsComFnc->FncGetBusyoMstValue(trim($_POST['data']['busyoCD']), $objBusyoMst);
                if ($tf['result']) {
                    $this->result['result'] = TRUE;
                    $this->result['data'] = $objBusyoMst['strBusyoNM'];
                } else {
                    throw new \Exception($tf['data']);
                }
            }
            $this->fncReturn($this->result);

        } catch (\Exception $ex) {
            $this->result['result'] = False;
            $this->result['data'] = $ex->getMessage();
            $this->fncReturn($this->result);
        }
    }

    /*
           **********************************************************************
           処 理 名：振替データCSV出力
           関 数 名：fncOutput1
           引    数：$tmpdata
           戻 り 値：無し
           処理説明：振替データCSV出力
           **********************************************************************
           */
    public function fncOutput1()
    {
        $blnTranFlg = TRUE;
        $f = "";
        $filePath_1 = "";
        try {
            //モデルの仕様するクラスを定義
            $this->FrmSyanaiLeasePrint = new FrmSyanaiLeasePrint();
            //モデルクラスのselect処理を呼出し
            $cboYM = str_replace("/", "", $_POST['data']['cboYM']);
            //データ取得
            $this->result = $this->FrmSyanaiLeasePrint->fncOutput1($cboYM);
            if ($this->result['result'] != TRUE) {
                throw new \Exception($this->result['data'], 1);
            } else {
                if (count((array) $this->result['data']) <= 0) {
                    $this->fncReturn($this->result);
                    return;
                }
            }

            //インスタンス作成
            $tmpTime = date("ymdhms");
            $tmpStr = "";
            $tmpPath1 = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
            $tmpPath2 = "webroot/files/R4k/";
            $tmpPath = $tmpPath1 . "/" . $tmpPath2;
            $this->filePathName = $tmpPath . "tmpFile" . $tmpTime . ".csv";
            $filePath_1 = "files/R4k/tmpFile" . $tmpTime . ".csv";

            if (file_exists($tmpPath)) {
                //フォルダ削除
                $dh = opendir($tmpPath);
                while ($file = readdir($dh)) {
                    if ($file != "." && $file != "..") {
                        $fullpath = $tmpPath . "/" . $file;
                        if (!is_dir($fullpath)) {
                            unlink($fullpath);
                        } else {
                            rmdir($tmpPath);
                        }
                    }
                }
            } else {
                mkdir($tmpPath, 0777, TRUE);
            }
            $f = fopen($this->filePathName, "w");
            //文字列 連結
            foreach ((array) $this->result['data'] as $value) {
                $tmpStr = "";
                $strArr = array();
                foreach ($value as $value1) {
                    //$tmpStr .= $value1;
                    if ($value1 != "") {
                        array_push($strArr, $value1);
                    } else {
                        array_push($strArr, " ");
                    }

                }
                $tmpStr .= join(",", $strArr);
                $tmpStr .= "\n";
                //文字列 出力
                fwrite($f, $tmpStr);
            }
            $blnTranFlg = FALSE;
            $this->result['data'] = $filePath_1;
            fclose($f);

        } catch (\Exception $ex) {
            if ($f == FALSE) {
                fclose($f);
            } else {
                if (file_exists($this->filePathName)) {
                    unlink($this->filePathName);
                }
            }
            $blnTranFlg = FALSE;
            $this->result['result'] = False;
            $this->result['data'] = $ex->getMessage();

        }
        //finally
        if ($blnTranFlg) {
            //エラー時出力ファイル削除
            if ($this->cslComFnc->FncFileExists($this->filePathName)) {
                if ($f == FALSE) {
                    fclose($f);
                }
                unlink($this->filePathName);
            }
        }
        $this->fncReturn($this->result);
    }

    /*
           **********************************************************************
           処 理 名：実行
           関 数 名：cmdAction_Click
           引    数：$tmpData
           戻 り 値：無し
           処理説明：印刷する
           **********************************************************************
           */
    public function fncCmdActionClick()
    {
        try {
            //モデルの仕様するクラスを定義
            $this->FrmSyanaiLeasePrint = new FrmSyanaiLeasePrint();
            //データ取得
            $this->result = $this->FrmSyanaiLeasePrint->fncCmdAction_Click($_POST['data']);
            if ($this->result['result'] != TRUE) {
                throw new \Exception($this->result['data'], 1);
            } else {
                if (count((array) $this->result['data']) <= 0) {
                    $this->fncReturn($this->result);
                    return;
                } else {
                    $data = $this->result['data'];
                    $tmpVal = 0;
                    if (trim($_POST['data']['rad1']) == 'true') {
                        $tmpVal = 1;
                    } else {
                        if (trim($_POST['data']['rad2']) == 'true') {
                            $tmpVal = 2;
                        } else {
                            if (trim($_POST['data']['radAll']) == 'true') {
                                $tmpVal = 3;
                            }
                        }
                    }
                    $tmpPdfName = "";
                    $tmpCase = "";
                    switch ($tmpVal) {
                        case 1:
                            $tmpCase = "3";
                            $tmpPdfName = "rptSyanaiLeasePrint3";
                            break;
                        case 2:
                            $tmpCase = "2";
                            $tmpPdfName = "rptSyanaiLeasePrint2";
                            break;
                        case 3:
                            $tmpCase = "1";
                            $tmpPdfName = "rptSyanaiLeasePrint1";
                            break;
                    }
                    //'プレビュー表示
                    $path_rpxTopdf = dirname(__DIR__);
                    include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';
                    include_once $path_rpxTopdf . '/Component/tcpdf/' . $tmpPdfName . '.inc';
                    $tmp_data = array();
                    $rpx_file_names = array();
                    $rpx_file_names[$tmpPdfName] = $data_fields_rptSyanaiLeasePrint;
                    array_push($tmp_data, $data);
                    $tmp = array();
                    $tmp["data"] = $tmp_data;
                    $tmp["mode"] = "4";
                    $datas[$tmpPdfName] = $tmp;
                    $obj = new \rpx_to_pdf($rpx_file_names, $datas, 4, $tmpCase);
                    $pdfPath = $obj->to_pdf2();

                    $this->result['data'] = $pdfPath;
                    $this->fncReturn($this->result);

                }
            }

        } catch (\Exception $ex) {
            $this->result['result'] = False;
            $this->result['data'] = $ex->getMessage();

            $this->fncReturn($this->result);
        }
    }

    public function fncGetAllValidating()
    {
        //モデルの仕様するクラスを定義
        $this->FrmSyanaiLeasePrint = new FrmSyanaiLeasePrint();
        //データ取得
        $this->result = $this->FrmSyanaiLeasePrint->fncGetAllValidatingSelect();

        $this->fncReturn($this->result);

    }

}