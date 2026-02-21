<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmExcelTorikomi;
use PhpOffice\PhpSpreadsheet\IOFactory;

//*******************************************
// * sample controller
//*******************************************
class FrmExcelTorikomiController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
    // public $autoRender = FALSE;
    public $enmMode = array(
        'EMPTY',
        //（TMRH）リース_新規
        'TBL_EXHMLEASE000001',
        //JAF件数
        'TBL_EXJAF0000000001',
        //人員
        'TBL_EXJININ00000001',
        //管理台数表
        'TBL_EXKANRIDAISU001',
        //任意保険新規
        'TBL_EXNINIHOKEN0001',
        //パックDeメンテ
        'TBL_EXPACKDEMENTE01',
        //（TMRH）リース_再リース
        'TBL_EXSAILEASE00001',
        'EMPTY',
        //サービス貢献度
        'TBL_EXSVCKOUKEN0001',
        //営業活動報告書
        'TBL_EXURIDAISU00001'
    );
    public $frmExcelTorikomi = "";

    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsLogControl');
        $this->loadComponent('ClsComFncJKSYS');
    }
    //　デフォルトで最初に実行される機能
    public function index()
    {
        $this->render('index', 'FrmExcelTorikomi_layout');
    }

    //取得対象年月
    public function dateGet()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            $getdate = date('Ym');
            $result['data'] = $getdate;
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
            //$pathUpLoad = $strPath . "/" . $this->ClsComFncJKSYS->FncGetPath('JksysUpLoad');
            $pathUpLoad = $this->ClsComFncJKSYS->FncGetPath('JksysUpLoad');
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
                $file_name = $this->changeFileName($_FILES["file"]["name"]);
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
        $this->fncCheckFileReturn($result);
    }

    //ファイルのアップロード
    public function changeFileName($param)
    {
        $this->frmExcelTorikomi = new FrmExcelTorikomi();
        $strUserID = $this->frmExcelTorikomi->GS_LOGINUSER['strUserID'];
        $arr = explode(".", $param);
        $long = count($arr) - 1;
        $file_type = $arr[$long];
        $file_name = '';
        for ($i = 0; $i < $long; $i++) {
            $file_name = $file_name . $arr[$i] . '.';
        }
        $file_name = substr($file_name, 0, strlen($file_name) - 1);
        $file_name = $strUserID . '_' . $file_name . '.' . $file_type;
        return $file_name;
    }

    public function btnActionClick()
    {
        $blnTranFlg = FALSE;
        //トランザクションflg
        $result = array(
            'result' => FALSE,
            'error' => ''
        );

        $this->frmExcelTorikomi = new FrmExcelTorikomi();
        try {
            $cboYM = "";
            $pMode = "";
            $txtPath = "";
            if (isset($_POST['data'])) {
                $cboYM = $_POST['data']["cboYM"];
                $pMode = $_POST['data']["pMode"];
                $txtPath = $_POST['data']["txtPath"];
            }
            if ($cboYM == '') {
                $result = array(
                    'result' => FALSE,
                    'error' => 'param error'
                );
            } else {
                $pTableNm = $this->enmMode[$pMode];
                $strPath = dirname(dirname(dirname(__FILE__)));
                //$pathUpLoad = $strPath . "/" . $this->ClsComFncJKSYS->FncGetPath('JksysUpLoad');
                $pathUpLoad = $this->ClsComFncJKSYS->FncGetPath('JksysUpLoad');
                $Session = $this->request->getSession();
                $txtPath = $Session->read('login_user') . "_" . $txtPath;
                $year = substr($cboYM, 0, 4);
                $month = substr($cboYM, 4, 2);
                $cboYM = date_create($year . "-" . $month);
                date_add($cboYM, date_interval_create_from_date_string("-1 month"));
                $cboYM = date_format($cboYM, "Ym");
                //トランザクション開始
                $this->frmExcelTorikomi->Do_transaction();
                $blnTranFlg = TRUE;
                //Excel取込処理
                $result = $this->ExcelTorikomi($pathUpLoad . $txtPath, $pMode, $pTableNm, $cboYM);

                if ($result['result'] == FALSE) {
                    throw new \Exception($result['error']);
                }
                //トランザクション終了
                $this->frmExcelTorikomi->Do_commit();
                $blnTranFlg = FALSE;
            }

        } catch (\Exception $e) {
            if ($blnTranFlg == TRUE) {
                $this->frmExcelTorikomi->Do_rollback();
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

    //EXCEL取込
    // * 処理名	：ExcelTorikomi
    // * 関数名	：ExcelTorikomi
    // * 処理説明	：EXCEL取込
    // * 引　　数：filePath   EXCEL取込ファイルパス
    // *    　     pTableNm  テーブル名
    // *    　     strYM     対象年月
    // *    　     pMode     種類
    private function ExcelTorikomi($filePath, $pMode, $pTableNm, $strYM)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );

        try {
            //同一年月のデータがすでに存在していたら、削除
            //明細データ取得
            $res = $this->frmExcelTorikomi->fncSelectMeisaiExist($strYM, $pTableNm);
            if ($res['result'] == FALSE) {
                throw new \Exception($res['data']);
            }
            if ($res['row'] > 0) {
                //削除
                $result = $this->frmExcelTorikomi->fncDeleteMeisaiExist($strYM, $pTableNm);
                if ($result['result'] == FALSE) {
                    throw new \Exception($result['data']);
                }
            }
            //明細ＮＯ最大値取得
            $result = $this->frmExcelTorikomi->fncSelectMaxListMeisaiNo($pTableNm);
            if ($result['result'] == FALSE) {
                throw new \Exception($result['data']);
            }
            $MeisaiNo = $result["data"][0]["MAXNO"] + 1;
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
            //シートワークシートの総数を取得
            $sheetCount = $objPHPExcel->getSheetCount();
            if ($sheetCount > 1) {
                //2番目を読む
                $objPHPExcel->setActiveSheetIndex(1);
            } else {
                $objPHPExcel->setActiveSheetIndex(0);
            }
            $objActSheet = $objPHPExcel->getActiveSheet();
            //取得总行数

            //データ読み込み、配列へ格納
            //行
            for ($i = 2; $i <= 4001; $i++) {
                if ($objPHPExcel->getActiveSheet()->getCell('B' . $i)->getValue() != null && trim($objPHPExcel->getActiveSheet()->getCell('B' . $i)->getValue()) !== "") {
                    $aryVal = array();
                    //列（明細ＮＯ以外）
                    $blnExist = False;
                    for ($j = 1; $j <= 21; $j++) {
                        $pos = $objPHPExcel->getActiveSheet()->getCell(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($j + 1) . $i)->getCalculatedValue() != null ? trim($objPHPExcel->getActiveSheet()->getCell(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($j + 1) . $i)->getCalculatedValue()) : '';
                        if ($pos !== "") {
                            $aryVal[$j] = $pos;
                            $blnExist = True;
                        } else {
                            $aryVal[$j] = "";
                        }

                    }
                    if ($blnExist) {
                        //明細ＮＯセット
                        $aryVal[0] = $MeisaiNo;
                        $MeisaiNo += 1;
                        //追加
                        $result = $this->frmExcelTorikomi->InsertData($pMode, $strYM, $pTableNm, $aryVal);
                        if ($result["result"] == FALSE) {
                            throw new \Exception('追加処理中にエラーが発生しました');
                        }
                    }
                }

            }
            $objPHPExcel->disconnectWorksheets();
            unset($objReader, $objPHPExcel);
            $result["result"] = TRUE;
            $result['data'] = "I0007";
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

}
