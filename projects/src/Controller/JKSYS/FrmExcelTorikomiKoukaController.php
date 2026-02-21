<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmExcelTorikomiKouka;
use PhpOffice\PhpSpreadsheet\IOFactory;

//*******************************************
// * sample controller
//*******************************************
class FrmExcelTorikomiKoukaController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
    // public $autoRender = FALSE;
    public $enmMode = array(
        'EMPTY',
        //考課表_ボディコーティング
        'TBL_EX08093141',
        //考課表_延長保証
        'TBL_EX08093639'
    );
    public $FrmExcelTorikomiKouka = "";

    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFncJKSYS');
    }
    //　デフォルトで最初に実行される機能
    public function index()
    {
        $this->render('index', 'FrmExcelTorikomiKouka_layout');
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
            $pathUpLoad = $strPath . "/" . $this->ClsComFncJKSYS->FncGetPath('JksysUpLoad');
            if (!file_exists($pathUpLoad)) {
                if (!mkdir($pathUpLoad, 0777, TRUE)) {
                    throw new \Exception("フォルダー作成失敗しました。");
                }
                chmod($pathUpLoad, 0777);
            }
            if ($_FILES["file"]["error"] > 0) {
                $result['data'] = "ファイルのアップロードに失敗しました。";
                throw new \Exception($result['data']);
            } else {
                //ファイルのアップロード
                $res_file_name = $this->changeFileName($_FILES["file"]["name"]);
                if (!$res_file_name['result']) {
                    throw new \Exception($res_file_name['error']);
                }
                $file_name = $res_file_name['data'];

                $uploadfile = $pathUpLoad . $file_name;
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $pathUpLoad . $file_name)) {
                    $result['result'] = TRUE;
                    $result['data'] = $uploadfile;
                } else {
                    $result['result'] = FALSE;
                    $result['data'] = 'ファイルのアップロードに失敗しました。';
                }
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        //POST方式的request，直接echo.
        $this->fncCheckFileReturn($result);
    }

    //ファイルのアップロード
    public function changeFileName($param)
    {
        $result = array(
            'result' => FALSE,
            'data' => ''
        );
        $this->FrmExcelTorikomiKouka = new FrmExcelTorikomiKouka();
        try {
            $strUserID = $this->FrmExcelTorikomiKouka->GS_LOGINUSER['strUserID'];
            $arr = explode(".", $param);
            $long = count($arr) - 1;
            $file_type = $arr[$long];
            $file_name = '';
            for ($i = 0; $i < $long; $i++) {
                $file_name = $file_name . $arr[$i] . '.';
            }
            $file_name = substr($file_name, 0, strlen($file_name) - 1);
            $file_name = $strUserID . '_' . $file_name . '.' . $file_type;
            $result['data'] = $file_name;
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    public function btnActionClick()
    {
        $blnTranFlg = FALSE;
        //トランザクションflg
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        $this->FrmExcelTorikomiKouka = new FrmExcelTorikomiKouka();
        try {
            $cboYMFrom = "";
            $cboYMTo = "";
            $pMode = "";
            $txtPath = "";
            if (isset($_POST['data'])) {

                $cboYMFrom = $_POST['data']["dateFrom"];
                $cboYMTo = $_POST['data']["dateTo"];
                $pMode = $_POST['data']["pMode"];
                $txtPath = $_POST['data']["txtPath"];
            }
            if ($cboYMFrom == '' || $cboYMTo == '') {
                throw new \Exception("param error");
            } else {
                $pTableNm = $this->enmMode[$pMode];
                $strPath = dirname(dirname(dirname(__FILE__)));
                $pathUpLoad = $strPath . "/" . $this->ClsComFncJKSYS->FncGetPath('JksysUpLoad');
                $Session = $this->request->getSession();
                $txtPath = $Session->read('login_user') . "_" . $txtPath;
                //トランザクション開始
                $this->FrmExcelTorikomiKouka->Do_transaction();
                $blnTranFlg = TRUE;
                //Excel取込処理
                $result = $this->ExcelTorikomi($pathUpLoad . $txtPath, $pMode, $pTableNm, $cboYMFrom, $cboYMTo);
                if ($result['result'] == FALSE) {
                    throw new \Exception($result['error']);
                }

                //トランザクション終了
                $this->FrmExcelTorikomiKouka->Do_commit();
                $blnTranFlg = FALSE;
            }

        } catch (\Exception $e) {
            if ($blnTranFlg == TRUE) {
                $this->FrmExcelTorikomiKouka->Do_rollback();
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();

        }

        //ファイル削除
        if (isset($pathUpLoad) && isset($txtPath)) {
            $UpLoadfilepath = $pathUpLoad . $txtPath;
            if (file_exists($UpLoadfilepath)) {
                @unlink($UpLoadfilepath);
            }
        }
        $this->fncReturn($result);
    }

    //EXCEL取込
    // * 処理名	：ExcelTorikomi
    // * 関数名	：ExcelTorikomi
    // * 処理説明	：EXCEL取込
    // * 引　　数：filePath   EXCEL取込ファイルパス
    // *    　     pTableNm  テーブル名
    // *    　     $cboYMFrom, $cboYMTo    対象年月
    // *    　     pMode     種類
    private function ExcelTorikomi($filePath, $pMode, $pTableNm, $cboYMFrom, $cboYMTo)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );

        try {
            //同一年月のデータがすでに存在していたら、削除
            //明細データ取得
            $res = $this->FrmExcelTorikomiKouka->fncSelectMeisaiExist($pMode, $pTableNm, $cboYMFrom, $cboYMTo);
            if ($res['result'] == FALSE) {
                throw new \Exception($res['data']);
            }
            if ($res['row'] > 0) {
                //削除
                $result = $this->FrmExcelTorikomiKouka->fncDeleteMeisaiExist($pMode, $pTableNm, $cboYMFrom, $cboYMTo);
                if ($result['result'] == FALSE) {
                    throw new \Exception($result['data']);
                }
            }
            //明細ＮＯ最大値取得
            $result = $this->FrmExcelTorikomiKouka->fncSelectMaxListMeisaiNo($pTableNm);
            if ($result['result'] == FALSE) {
                throw new \Exception($result['data']);
            }
            $MeisaiNo = $result["data"][0]["MAXNO"] + 1;
            $i1StRow = 0;
            $iColumn = 0;
            //考課表_ボディコーティング
            if ($pMode == 1) {
                $i1StRow = 5;
                $iColumn = 5;
            } else
                if ($pMode == 2) {
                    $i1StRow = 2;
                    $iColumn = 3;
                }
            //EXCELデータを取り込む
            $file_info = pathinfo($filePath);
            $extension = $file_info['extension'];
            if ($extension == 'xlsx') {
                $objReader = IOFactory::createReader('Xlsx');
            } else {
                $objReader = IOFactory::createReader('Xls');
            }
            $objPHPExcel = $objReader->load($filePath);
            //シートワークシートの総数を取得
            $sheetCount = $objPHPExcel->getSheetCount();
            if ($sheetCount > 1) {
                //2番目を読む
                $objPHPExcel->setActiveSheetIndex(1);
            } else {
                $objPHPExcel->setActiveSheetIndex(0);
            }
            $objActSheet = $objPHPExcel->getActiveSheet();

            //データ読み込み、配列へ格納
            //行
            for ($i = $i1StRow; $i <= 4001; $i++) {
                if ($objPHPExcel->getActiveSheet()->getCell('B' . $i)->getValue() != null && trim($objPHPExcel->getActiveSheet()->getCell('B' . $i)->getValue()) !== "") {
                    $aryVal = array();
                    //列（明細ＮＯ以外）
                    $blnExist = False;
                    for ($j = 0; $j <= $iColumn; $j++) {
                        $pos = $objPHPExcel->getActiveSheet()->getCell(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($j + 1) . $i)->getCalculatedValue() != null ? trim($objPHPExcel->getActiveSheet()->getCell(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($j + 1) . $i)->getCalculatedValue()) : '';
                        if ($pos !== "") {
                            $aryVal[$j] = $pos;
                            $blnExist = True;
                        } else {
                            $aryVal[$j] = "";
                        }

                    }
                    if ($blnExist) {
                        //追加
                        $result = $this->FrmExcelTorikomiKouka->InsertData($pMode, $pTableNm, $aryVal, $MeisaiNo);
                        $MeisaiNo += 1;
                        if ($result["result"] == FALSE) {
                            throw new \Exception('追加処理中にエラーが発生しました');
                        }
                    }
                }

            }
            $objPHPExcel->disconnectWorksheets();
            unset($objReader, $objPHPExcel);
            $result["result"] = TRUE;
            $result["data"] = '';
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

}
