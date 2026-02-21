<?php
namespace App\Controller\R4\R4K;

/**
 * 説明：
 *
 *
 * @author FCS
 * @copyright (GD) FCS
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20151207           #2286						   BUG                              li
 * --------------------------------------------------------------------------------------------
 */
use App\Controller\AppController;
use App\Model\R4\R4K\FrmReOutRptPrint;

use PHPMailer\PHPMailer\Exception;

class FrmReOutRptPrintController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    private $FrmReOutRptPrint;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    public function index()
    {
        $this->render('index', 'FrmReOutRptPrint_layout');
    }

    public function frmKanrSyukeiLoad()
    {
        $result = array();
        try {
            $this->FrmReOutRptPrint = new FrmReOutRptPrint();
            $result = $this->FrmReOutRptPrint->reselect();
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
            $this->FrmReOutRptPrint = new FrmReOutRptPrint();
            $result = $this->FrmReOutRptPrint->fncPrintSelect(str_replace("/", "", $cboYMStart));

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //印刷処理
            if (count((array) $result['data']) > 0) {
                // $TOTALKBN = [
                //     'BUNHIN_SUM' => 0
                // ];
                //'プレビュー表示
                $path_rpxTopdf = dirname(__DIR__);
                include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';
                include_once $path_rpxTopdf . '/Component/tcpdf/rptReOutToukeiReport.inc';

                $rpx_file_names = array();
                $tmp_data = array();
                $tmp = array();

                $tatol_array = array();
                $current_KBN = "";
                $last_KBN = "";
                $TOTALKBN = [
                    'BUNHIN_SUM' => 0,
                    'GOUKEI_SUM' => 0,
                    'BUHIN_SUM' => 0,
                    'GAICHU_SUM' => 0,
                    'KOUCHIN_SUM' => 0,
                    'KBN_COUNT' => 0
                ];
                foreach ((array) $result['data'] as $key => $value) {
                    $result['data'][$key]['TOUGETU'] = substr($value['TOUGETU'], 0, 4) . "/" . substr($value['TOUGETU'], 4, 2);
                    $TOTALKBN['TOUGETU'] = $result['data'][$key]['TOUGETU'];
                    $TOTALKBN['TODAY'] = $value['TODAY'];
                    $TOTAL['TOUGETU'] = $result['data'][$key]['TODAY'];
                    $TOTAL['TODAY'] = $value['TODAY'];
                    $current_KBN = $value['KBN'];
                    if ($last_KBN === "") {

                        foreach ($value as $key1 => $value1) {
                            switch ($key1) {
                                case "BUHIN":
                                    //小　計
                                    $TOTALKBN['BUHIN_SUM'] += $value1;
                                    // 合　計
                                    $TOTAL['BUHIN_SUM'] += $value1;
                                    break;
                                case "GAICHU":
                                    //小　計
                                    $TOTALKBN['GAICHU_SUM'] += $value1;
                                    //合　計
                                    $TOTAL['GAICHU_SUM'] += $value1;
                                    break;
                                case "KOUCHIN":
                                    //小　計
                                    $TOTALKBN['KOUCHIN_SUM'] += $value1;
                                    //合　計
                                    $TOTAL['KOUCHIN_SUM'] += $value1;
                                    break;
                                case "GOUKEI":
                                    //小　計
                                    $TOTALKBN['GOUKEI_SUM'] += $value1;
                                    //合　計
                                    $TOTAL['GOUKEI_SUM'] += $value1;
                                    break;
                            }
                        }
                        if ($current_KBN != "") {
                            $TOTALKBN['KBN_COUNT'] += 1;
                            $TOTAL['KBN_COUNT'] += 1;
                        }
                    } elseif ($current_KBN == $last_KBN) {
                        foreach ($value as $key2 => $value2) {
                            switch ($key2) {
                                case "BUHIN":
                                    $TOTALKBN['BUHIN_SUM'] += $value2;
                                    $TOTAL['BUHIN_SUM'] += $value2;
                                    break;
                                case "GAICHU":
                                    $TOTALKBN['GAICHU_SUM'] += $value2;
                                    $TOTAL['GAICHU_SUM'] += $value2;
                                    break;
                                case "KOUCHIN":
                                    $TOTALKBN['KOUCHIN_SUM'] += $value2;
                                    $TOTAL['KOUCHIN_SUM'] += $value2;
                                    break;
                                case "GOUKEI":
                                    $TOTALKBN['GOUKEI_SUM'] += $value2;
                                    $TOTAL['GOUKEI_SUM'] += $value2;
                                    break;
                            }
                        }
                        if ($current_KBN != "") {
                            $TOTALKBN['KBN_COUNT'] += 1;
                            $TOTAL['KBN_COUNT'] += 1;
                        }
                        //---20151207 li INS S.
                        $TOTALKBN['KBN'] = $current_KBN;
                        $TOTAL['KBN'] = $current_KBN;
                        //---20151207 li INS E.
                    } else {
                        //---20151207 li INS S.
                        $TOTALKBN['KBN'] = $last_KBN;
                        $TOTAL['KBN'] = $last_KBN;
                        //---20151207 li INS E.
                        //小　計
                        $TOTALKBN['BUHIN_AVG'] = $TOTALKBN['KBN_COUNT'] == 0 ? 0 : round($TOTALKBN['BUHIN_SUM'] / $TOTALKBN['KBN_COUNT']);
                        $TOTALKBN['GAICHU_AVG'] = $TOTALKBN['KBN_COUNT'] == 0 ? 0 : round($TOTALKBN['GAICHU_SUM'] / $TOTALKBN['KBN_COUNT']);
                        $TOTALKBN['KOUCHIN_AVG'] = $TOTALKBN['KBN_COUNT'] == 0 ? 0 : round($TOTALKBN['KOUCHIN_SUM'] / $TOTALKBN['KBN_COUNT']);
                        $TOTALKBN['GOUKEI_AVG'] = $TOTALKBN['KBN_COUNT'] == 0 ? 0 : round($TOTALKBN['GOUKEI_SUM'] / $TOTALKBN['KBN_COUNT']);
                        array_push($tatol_array, $TOTALKBN);
                        //reset
                        $TOTALKBN = $TOTALKBN1;

                        foreach ((array) $value as $key3 => $value3) {
                            switch ($key3) {
                                case "BUHIN":
                                    $TOTALKBN['BUHIN_SUM'] += $value3;
                                    $TOTAL['BUHIN_SUM'] += $value3;
                                    break;
                                case "GAICHU":
                                    $TOTALKBN['GAICHU_SUM'] += $value3;
                                    $TOTAL['GAICHU_SUM'] += $value3;
                                    break;
                                case "KOUCHIN":
                                    $TOTALKBN['KOUCHIN_SUM'] += $value3;
                                    $TOTAL['KOUCHIN_SUM'] += $value3;
                                    break;
                                case "GOUKEI":
                                    $TOTALKBN['GOUKEI_SUM'] += $value3;
                                    $TOTAL['GOUKEI_SUM'] += $value3;
                                    break;
                            }
                        }
                        if ($current_KBN != "") {
                            $TOTALKBN['KBN_COUNT'] += 1;
                            $TOTAL['KBN_COUNT'] += 1;
                        }
                    }
                    $last_KBN = $current_KBN;
                }
                //平　均(小　計)
                $TOTALKBN['BUHIN_AVG'] = $TOTALKBN['KBN_COUNT'] == 0 ? 0 : round($TOTALKBN['BUHIN_SUM'] / $TOTALKBN['KBN_COUNT']);
                $TOTALKBN['GAICHU_AVG'] = $TOTALKBN['KBN_COUNT'] == 0 ? 0 : round($TOTALKBN['GAICHU_SUM'] / $TOTALKBN['KBN_COUNT']);
                $TOTALKBN['KOUCHIN_AVG'] = $TOTALKBN['KBN_COUNT'] == 0 ? 0 : round($TOTALKBN['KOUCHIN_SUM'] / $TOTALKBN['KBN_COUNT']);
                $TOTALKBN['GOUKEI_AVG'] = $TOTALKBN['KBN_COUNT'] == 0 ? 0 : round($TOTALKBN['GOUKEI_SUM'] / $TOTALKBN['KBN_COUNT']);
                //平　均(合　計)
                $TOTAL['BUHIN_AVG'] = $TOTALKBN['KBN_COUNT'] == 0 ? 0 : round($TOTAL['BUHIN_SUM'] / $TOTAL['KBN_COUNT']);
                $TOTAL['GAICHU_AVG'] = $TOTALKBN['KBN_COUNT'] == 0 ? 0 : round($TOTAL['GAICHU_SUM'] / $TOTAL['KBN_COUNT']);
                $TOTAL['KOUCHIN_AVG'] = $TOTALKBN['KBN_COUNT'] == 0 ? 0 : round($TOTAL['KOUCHIN_SUM'] / $TOTAL['KBN_COUNT']);
                $TOTAL['GOUKEI_AVG'] = $TOTALKBN['KBN_COUNT'] == 0 ? 0 : round($TOTAL['GOUKEI_SUM'] / $TOTAL['KBN_COUNT']);
                array_push($tatol_array, $TOTALKBN);
                array_push($tatol_array, $TOTAL);
                array_push($result['data'], $tatol_array);
                array_push($tmp_data, $result['data']);

                $tmp["data"] = $tmp_data;
                $tmp["mode"] = "5";
                $datas["rptReOutToukeiReport"] = $tmp;
                $rpx_file_names["rptReOutToukeiReport"] = $data;
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