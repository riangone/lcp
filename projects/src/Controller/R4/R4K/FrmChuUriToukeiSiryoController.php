<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmChuUriToukeiSiryo;

class FrmChuUriToukeiSiryoController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmChuUriToukeiSiryo = "";
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    public function index()
    {
        $this->render('index', 'FrmChuUriToukeiSiryo_layout');
    }

    public function frmKanrSyukeiLoad()
    {
        $result = array();
        try {
            $this->FrmChuUriToukeiSiryo = new FrmChuUriToukeiSiryo();
            $result = $this->FrmChuUriToukeiSiryo->fncSelect();
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
        $cboYMStart = "";
        try {
            $cboYMStart = $_POST['data'];
            $this->FrmChuUriToukeiSiryo = new FrmChuUriToukeiSiryo();
            $result = $this->FrmChuUriToukeiSiryo->fncPrintSelect(str_replace("/", "", $cboYMStart));
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //印刷処理
            if (count($result['data']) > 0) {
                //'プレビュー表示
                $path_rpxTopdf = dirname(__DIR__);
                include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';
                include_once $path_rpxTopdf . '/Component/tcpdf/rptChuUriToukeiSiryo.inc';
                $rpx_file_names = array();
                $tmp_data = array();
                $tmp = array();
                foreach ($result['data'] as $value) {
                    foreach ($total as $key1 => $value1) {
                        $total[$key1] = (string) ($value1 + $value[$key1]);
                    }
                }
                array_push($result['data'], $total);
                array_push($tmp_data, $result['data']);

                $tmp["data"] = $tmp_data;
                $tmp["mode"] = "7";
                $datas["rptChuUriToukeiSiryo"] = $tmp;
                $rpx_file_names["rptChuUriToukeiSiryo"] = $data;
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