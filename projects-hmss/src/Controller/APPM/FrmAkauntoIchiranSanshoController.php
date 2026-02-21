<?php
/**
 * 説明：
 *
 *
 * @author YINHUAIYU
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL　　　　　
 * * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\APPM;

use App\Controller\AppController;
use App\Model\APPM\FrmAkauntoIchiranSansho;

/**
 * アカウント参照
 * FrmAkauntoIchiranSanshoController
 */
class FrmAkauntoIchiranSanshoController extends AppController
{
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    public $autoLayout = TRUE;
    public $FrmAkauntoIchiranSansho;
    public $result = array();
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsFncLog');
    }
    /**
     * デフォルトで最初に実行される機能
     */
    public function index()
    {
        // Viewファイル呼出し
        $this->render('index', 'FrmAkauntoIchiranSansho_layout');
    }

    //'**********************************************************************
    //'処 理 名：アカウント情報取得
    //'関 数 名：fncFrmAkauntoIchiranSansho
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function fncFrmAkauntoIchiranSansho()
    {
        $result = array();
        try {
            if (isset($_POST['request'])) {
                $postData = $_POST["request"];

                $this->FrmAkauntoIchiranSansho = new FrmAkauntoIchiranSansho();
                $this->result = $this->FrmAkauntoIchiranSansho->getListDataSel($postData, "");
                if (!$this->result['result']) {
                    throw new \Exception($this->result['data']);
                }

                $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($this->result['data']);
                $sortstr = $tmpJqgridShow['sortStr'];
                $start = $tmpJqgridShow['start'];
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];

                $this->result = $this->FrmAkauntoIchiranSansho->getListDataSel($postData, $sortstr);

                $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridDataReload($this->result["data"], $totalPage, $page, $tmpCount, $start);

                $this->result = $tmpJqgrid;
            } else {
                $this->result = $result;
            }
        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }

        $this->fncReturn($this->result);
    }

    //'**********************************************************************
    //'処 理 名：店舗取得
    //'関 数 名：fncGetTenpo
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function fncGetTenpo()
    {
        try {
            $this->FrmAkauntoIchiranSansho = new FrmAkauntoIchiranSansho();

            $this->result = $this->FrmAkauntoIchiranSansho->getTenpoData();
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            $result = $this->FrmAkauntoIchiranSansho->getMeTenpo();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $this->result['meTenpo'] = $result['data'];

        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }

        $this->fncReturn($this->result);

    }

    //'**********************************************************************
    //'処 理 名：PDF出力
    //'関 数 名：btnPdf_Click
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function btnPdfClick()
    {
        try {
            $postData = $_POST['data'];

            include_once "rpx_to_pdf.php";
            include_once 'rptKyakuPrint.inc';

            $tmp = array();

            foreach ($postData as $key => $value) {
                $arr = array();

                $arr['CSRNM'] = $value['CSRNM'] . " 様";
                $arr['ROGUIN_ID'] = $value['ROGUIN_ID'];
                $arr['KARI_PASUWADO'] = $value['KARI_PASUWADO'];
                array_push($tmp, $arr);
            }

            $rpx_file_names = array();
            $rpx_file_names['rptKyakuPrint'] = $data_fields_rptKyakuPrint;

            $tmp_data = array();
            $tmp_data['data'] = $tmp;
            $tmp_data['mode'] = "0";

            $datas = array();
            $datas['rptKyakuPrint'] = $tmp_data;

            $obj = new \rpx_to_pdf($rpx_file_names, $datas);
            $pdfPath = $obj->to_pdf();

            $this->result = array(
                'result' => true,
                'flag' => 'true',
                'msg' => 'true',
                'reports' => $pdfPath
            );
        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }

        $this->fncReturn($this->result);
    }

}
