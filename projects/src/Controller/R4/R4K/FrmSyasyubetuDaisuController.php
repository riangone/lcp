<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmSyasyubetuDaisu;

class FrmSyasyubetuDaisuController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmSyasyubetuDaisu = "";
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    public function index()
    {
        $this->render('index', 'FrmSyasyubetuDaisu_layout');
    }

    public function frmKanrSyukeiLoad()
    {
        $result = array();
        try {
            $this->FrmSyasyubetuDaisu = new FrmSyasyubetuDaisu();
            $result = $this->FrmSyasyubetuDaisu->fncSelect();
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
        $cboYMFrom = "";
        try {
            $cboYMFrom = $_POST['data'];
            $this->FrmSyasyubetuDaisu = new FrmSyasyubetuDaisu();
            $result = $this->FrmSyasyubetuDaisu->fncPrintTougetut($cboYMFrom);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if (count($result['data']) > 0) {
                //'プレビュー表示
                $path_rpxTopdf = dirname(__DIR__);
                include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';
                include_once $path_rpxTopdf . '/Component/tcpdf/rptSyasyubetuDaisu.inc';
                $rpx_file_names = array();
                $tmp_data = array();
                $tmp = array();

                $tatol_array = array();
                $current_KBN = "";
                $last_KBN = "";
                foreach ($result['data'] as $value) {
                    $current_KBN = $value['CAR_KBN'];
                    if ($last_KBN === "") {
                        foreach ($value as $key1 => $value1) {
                            $totalcar1[$key1] = (string) ((float) $value1 + (float) $totalcar1[$key1]);
                            $total[$key1] = (string) ((float) $value1 + (float) $total[$key1]);
                        }
                    } elseif ($current_KBN == $last_KBN) {
                        foreach ($value as $key2 => $value2) {
                            $totalcar1[$key2] = (string) ((float) $value2 + (float) $totalcar1[$key2]);
                            $total[$key2] = (string) ((float) $value2 + (float) $total[$key2]);
                        }
                        $totalcar1['CAR_KBN'] = $current_KBN;
                    } else {
                        $totalcar1['CAR_KBN'] = $last_KBN;
                        array_push($tatol_array, $totalcar1);
                        $totalcar1 = $totalcar2;
                        foreach ($value as $key3 => $value3) {
                            $totalcar1[$key3] = (string) ((float) $value3 + (float) $totalcar1[$key3]);
                            $total[$key3] = (string) ((float) $value3 + (float) $total[$key3]);
                        }
                    }
                    $last_KBN = $current_KBN;
                }
                array_push($tatol_array, $totalcar1);
                array_push($tatol_array, $total);
                array_push($result['data'], $tatol_array);
                array_push($tmp_data, $result['data']);
                $tmp["data"] = $tmp_data;
                $tmp["mode"] = "5";
                $datas["rptSyasyubetuDaisu"] = $tmp;
                $rpx_file_names["rptSyasyubetuDaisu"] = $data;
                $obj = new \rpx_to_pdf($rpx_file_names, $datas);
                $pdfPath = $obj->to_pdf2();
                $result['pdfmark'] = TRUE;
                $result['pdfpath'] = $pdfPath;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

}