<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmExcelTorikomiKyufu;
use PhpOffice\PhpSpreadsheet\IOFactory;

//*******************************************
// * sample controller
//*******************************************
class FrmExcelTorikomiKyufuController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
    // public $autoRender = FALSE;
    public $FrmExcelTorikomiKyufu = "";
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFncJKSYS');
    }
    //　デフォルトで最初に実行される機能
    public function index()
    {
        $this->render('index', 'FrmExcelTorikomiKyufu_layout');
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
                $file_path = dirname($pathUpLoad);
                if (!(is_readable($file_path) && is_writable($file_path) && is_executable($file_path))) {
                    throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                }
                if (!mkdir($pathUpLoad, 0777, TRUE)) {
                    throw new \Exception("フォルダー作成失敗しました。");
                }
                chmod($pathUpLoad, 0777);
            } else {
                if (!(is_readable($pathUpLoad) && is_writable($pathUpLoad) && is_executable($pathUpLoad))) {
                    throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                }
            }
            if ($_FILES["file"]["error"] > 0) {
                $result['result'] = FALSE;
                $result['data'] = "ファイルのアップロードに失敗しました。";
                throw new \Exception($result['data']);
            } else {
                $result_file = $this->changeFileName($_FILES["file"]["name"]);
                if ($result_file['result'] == FALSE) {
                    throw new \Exception($result_file['data']);
                }
                $file_name = $result_file['file_name'];
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
        // echo json_encode($result);
        $this->fncCheckFileReturn($result);
    }

    //ファイルのアップロード
    public function changeFileName($param)
    {
        $result = array(
            'result' => FALSE,
            'data' => ''
        );
        try {
            $this->FrmExcelTorikomiKyufu = new FrmExcelTorikomiKyufu();
            $strUserID = $this->FrmExcelTorikomiKyufu->GS_LOGINUSER['strUserID'];
            $arr = explode(".", $param);
            $long = count($arr) - 1;
            $file_type = $arr[$long];
            $file_name = '';
            for ($i = 0; $i < $long; $i++) {
                $file_name = $file_name . $arr[$i] . '.';
            }
            $file_name = substr($file_name, 0, strlen($file_name) - 1);
            $file_name = $strUserID . '_' . $file_name . '.' . $file_type;
            $result['file_name'] = $file_name;
            $result['result'] = True;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
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

        $this->FrmExcelTorikomiKyufu = new FrmExcelTorikomiKyufu();
        try {
            $cboYM = "";
            $txtPath = "";
            if (isset($_POST['data'])) {
                $cboYM = $_POST['data']["cboYM"];
                $txtPath = $_POST['data']["txtPath"];
            }
            if ($cboYM == '') {
                $result = array(
                    'result' => FALSE,
                    'error' => 'param error'
                );
            } else {
                $strPath = dirname(dirname(dirname(__FILE__)));
                $pathUpLoad = $strPath . "/" . $this->ClsComFncJKSYS->FncGetPath('JksysUpLoad');
                $Session = $this->request->getSession();
                $txtPath = $Session->read('login_user') . "_" . $txtPath;
                //トランザクション開始
                $this->FrmExcelTorikomiKyufu->Do_transaction();
                $blnTranFlg = TRUE;
                //Excel取込処理
                $result = $this->ExcelTorikomi($pathUpLoad . $txtPath, $cboYM);

                if ($result['result'] == FALSE) {
                    throw new \Exception($result['error']);
                }
                //トランザクション終了
                $this->FrmExcelTorikomiKyufu->Do_commit();
                $blnTranFlg = FALSE;
            }

        } catch (\Exception $e) {
            if ($blnTranFlg == TRUE) {
                $this->FrmExcelTorikomiKyufu->Do_rollback();
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();

        }

        //ファイル削除
        $UpLoadfilepath = $pathUpLoad . $txtPath;
        if (isset($UpLoadfilepath) && file_exists($UpLoadfilepath)) {
            @unlink($UpLoadfilepath);
        }
        $this->fncReturn($result);
    }

    private function ExcelTorikomi($filePath, $strYM)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );

        try {
            //同一年月のデータがすでに存在していたら、削除
            $res = $this->FrmExcelTorikomiKyufu->fncSelectMeisaiExist($strYM);
            if ($res['result'] == FALSE) {
                throw new \Exception($res['data']);
            }
            if ($res['row'] > 0) {
                //削除
                $result_del = $this->FrmExcelTorikomiKyufu->fncDeleteMeisaiExist($strYM);
                if ($result_del['result'] == FALSE) {
                    throw new \Exception($result_del['data']);
                }
            }
            //EXCELデータを取り込む
            // include __DIR__ . '/Component/Classes/PHPExcel.php';
            $file_info = pathinfo($filePath);
            $extension = $file_info['extension'];
            if ($extension == 'xlsx') {
                $objReader = IOFactory::createReader('Xlsx');
            } else {
                $objReader = IOFactory::createReader('Xls');
            }

            $objPHPExcel = $objReader->load($filePath);
            $objPHPExcel->setActiveSheetIndex(0);
            $objActSheet = $objPHPExcel->getActiveSheet();
            //取得总行数
            $highestRow = $objActSheet->getHighestRow();
            if ($highestRow <= 2) {
                throw new \Exception('取り込みできませんでした');
            }
            //データ読み込み、配列へ格納
            //行
            $row = 0;
            for ($i = 3; $i <= $highestRow - 2; $i++) {
                if ($objPHPExcel->getActiveSheet()->getCell('A' . $i)->getValue() != null && trim($objPHPExcel->getActiveSheet()->getCell('A' . $i)->getValue()) !== "") {
                    $aryVal = array();
                    //列
                    $blnExist = False;
                    for ($j = 0; $j <= 1; $j++) {
                        if ($objPHPExcel->getActiveSheet()->getCell(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($j + 1) . $i)->getCalculatedValue() != null) {
                            $pos = trim($objPHPExcel->getActiveSheet()->getCell(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($j + 1) . $i)->getCalculatedValue());
                        } else {
                            $pos = '';
                        }
                        if ($pos !== "") {
                            $aryVal[$j] = $pos;
                            $blnExist = True;

                        } else {
                            $aryVal[$j] = "0";
                        }

                    }
                    if ($blnExist) {
                        //追加
                        $result_ins = $this->FrmExcelTorikomiKyufu->InsertData($strYM, $aryVal);
                        if ($result_ins["result"] == FALSE) {
                            throw new \Exception('追加処理中にエラーが発生しました');
                        }
                    }
                    $row++;
                }

            }
            if ($row == 0) {
                throw new \Exception('取り込みできませんでした');
            }

            $objPHPExcel->disconnectWorksheets();
            unset($objReader, $objPHPExcel);
            $result['row'] = $row;
            $result["result"] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

}
