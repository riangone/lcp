<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmShikakariMeisaiPrint;

class FrmShikakariMeisaiPrintController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;

    public $FrmShikakariMeisaiPrint = "";
    // public $ClsLogControl = '';
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsLogControl');
    }
    public function index()
    {
        $this->render('index', 'FrmShikakariMeisaiPrint_layout');
    }

    public function frmKanrSyukeiLoad()
    {
        $result = array();
        try {
            $this->FrmShikakariMeisaiPrint = new FrmShikakariMeisaiPrint();
            $result = $this->FrmShikakariMeisaiPrint->reselect();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function cmdActionClick()
    {
        $result = array();
        $cboYM = "";
        $intState = 0;
        $lngOutCnt = 0;
        try {
            $cboYM = $_POST['data'];
            //ログ管理
            $intState = 9;
            $this->FrmShikakariMeisaiPrint = new FrmShikakariMeisaiPrint();
            $result = $this->FrmShikakariMeisaiPrint->fncPrintMeisai(str_replace("/", "", $cboYM));
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //印刷処理
            if (count((array) $result['data']) > 0) {
                $lngOutCnt = count((array) $result['data']);
                //'プレビュー表示
                $path_rpxTopdf = dirname(__DIR__);
                include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';

                $rpx_file_names = array();
                $tmp_data = array();
                $tmp = array();
                $data = array(
                    "SYADAIKATA" => "",
                    "CARNO" => "",
                    "GYOUSYA" => "",
                    "SYORI_DT" => "",
                    "DENPY_NO" => "",
                    "GOUKEI" => "",
                    "TODAY" => "",
                    "TUKI" => ""
                );
                $TempArr = array();
                $SUM = array('GOUKEI_SUM' => 0, "TODAY" => $result['data'][0]["TODAY"], "TUKI" => $result['data'][0]["TUKI"]);
                $TOTAL = array('GOUKEI_TOTAL' => 0, "TODAY" => $result['data'][0]["TODAY"], "TUKI" => $result['data'][0]["TUKI"]);
                $current = "";
                $last = "";
                foreach ((array) $result['data'] as $key => $value) {
                    $current = $value['CARNO'];
                    if ($last == "" || $last == $current) {
                        $SUM['GOUKEI_SUM'] += $value['GOUKEI'];
                    } else {
                        array_push($TempArr, $SUM);
                        $SUM['GOUKEI_SUM'] = 0;
                        $SUM['GOUKEI_SUM'] += $value['GOUKEI'];
                    }
                    $TOTAL['GOUKEI_TOTAL'] += $value['GOUKEI'];
                    $last = $current;
                }
                array_push($TempArr, $SUM);
                array_push($TempArr, $TOTAL);
                array_push($result['data'], $TempArr);
                array_push($tmp_data, $result['data']);

                $tmp["data"] = $tmp_data;
                $tmp["mode"] = "11";
                $datas["rptZandakaMeisai"] = $tmp;
                $rpx_file_names["rptZandakaMeisai"] = $data;
                $obj = new \rpx_to_pdf($rpx_file_names, $datas);
                $pdfPath = $obj->to_pdf();

                $result['pdfmark'] = TRUE;
                $result['pdfpath'] = $pdfPath;
            }
            //ログ管理
            $intState = 1;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        //ログ管理 Start
        if ($intState != 0) {
            //$intState!=0の場合、ログ管理テーブルに登録
            $this->ClsLogControl->fncLogEntry("frmShikakariMeisaiPrint", $intState, $lngOutCnt, $cboYM);
        }
        $this->fncReturn($result);
    }

}