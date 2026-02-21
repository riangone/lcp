<?php
namespace App\Controller\R4\R4K;

/**
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug             内容                               担当
 * YYYYMMDD           #ID                     XXXXXX                            FCSDL
 * 20151225           #2292                   BUG                               LI
 * --------------------------------------------------------------------------------------------
 */
use App\Controller\AppController;
use App\Model\R4\R4K\FrmShikakariPrint;

class FrmShikakariPrintController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;

    public $FrmShikakariPrint = "";
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
        $this->render('index', 'FrmShikakariPrint_layout');
    }

    public function frmKanrSyukeiLoad()
    {
        $result = array();
        try {
            $this->FrmShikakariPrint = new FrmShikakariPrint();
            $result = $this->FrmShikakariPrint->reselect();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncPrint()
    {
        $result = array();
        $result1 = array();
        $result2 = array();
        $tyohan1 = "";
        $cboYMStart = "";
        $intState = 0;
        $lngOutCntS = 0;
        $lngOutCntC = 0;
        try {
            $tyohan1 = $_POST['data']['tyohan1'];
            $cboYMStart = $_POST['data']['cboYMStart'];
            //ログ管理
            $intState = 9;
            $this->FrmShikakariPrint = new FrmShikakariPrint();
            switch ($tyohan1) {
                case "新車":
                    $result = $this->FrmShikakariPrint->fncPrintNew(str_replace("/", "", $cboYMStart));
                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }
                    $lngOutCntS = count((array) $result['data']);
                    break;
                case "中古車":
                    $result = $this->FrmShikakariPrint->fncPrintOld(str_replace("/", "", $cboYMStart));
                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }
                    $lngOutCntC = count((array) $result['data']);
                    break;
                case "両方":
                    $result1 = $this->FrmShikakariPrint->fncPrintNew(str_replace("/", "", $cboYMStart));
                    if (!$result1['result']) {
                        throw new \Exception($result['data']);
                    }
                    $lngOutCntS = count((array) $result1['data']);
                    $result2 = $this->FrmShikakariPrint->fncPrintOld(str_replace("/", "", $cboYMStart));
                    if (!$result2['result']) {
                        throw new \Exception($result['data']);
                    }
                    $lngOutCntC = count((array) $result2['data']);
                    $result['result'] = TRUE;
                    $result['data'] = array_merge((array) $result1['data'], (array) $result2['data']);
                    break;
            }

            if (count((array) $result['data']) > 0) {
                $path_rpxTopdf = dirname(__DIR__);
                include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';
                $rpx_file_names = array();
                $tmp_data1 = array();
                $tmp1 = array();
                $data1 = array(
                    "SYADAIKATA" => "",
                    "CARNO" => "",
                    "NEXTPAGE" => "",
                    "TODAY" => "",
                    //---20151225 li INS S.
                    "TODAY1" => "",
                    //---20151225 li INS E.
                    "GOUKEI" => ""
                );
                $tmp_data2 = array();
                $tmp2 = array();
                $data2 = array(
                    "SYADAIKATA" => "",
                    "CARNO" => "",
                    "NEXTPAGE" => "",
                    "TODAY" => "",
                    //---20151225 li INS S.
                    "TODAY1" => "",
                    //---20151225 li INS E.
                    "BUHIN" => "",
                    "GAICHU" => "",
                    "GOUKEI" => ""
                );
                switch ($tyohan1) {
                    case "新車":
                        $TOTAL = array('GOUKEI_TOTAL' => 0);
                        foreach ((array) $result['data'] as $key => $value) {
                            //---20151225 li INS S.
                            // $TOTAL['TODAY1'] = $this -> getToday($value['TODAY']);
                            $result['data'][$key]['TODAY1'] = $this->getToday($value['TODAY']);
                            //---20151225 li INS E.
                            $TOTAL['GOUKEI_TOTAL'] += $value['GOUKEI'];
                        }
                        array_push($result['data'], $TOTAL);
                        array_push($tmp_data1, $result['data']);

                        $tmp1["data"] = $tmp_data1;
                        $tmp1["mode"] = "12";
                        $datas["rptZandakaNew"] = $tmp1;
                        $rpx_file_names["rptZandakaNew"] = $data1;
                        break;
                    case "中古車":
                        $TOTAL = array(
                            'BUHIN_TOTAL' => 0,
                            'GAICHU_TOTAL' => 0,
                            'GOUKEI_TOTAL' => 0
                        );
                        foreach ((array) $result['data'] as $key => $value) {
                            //---20151225 li INS S.
                            $result['data'][$key]['TODAY1'] = $this->getToday($value['TODAY']);
                            //---20151225 li INS E.
                            $TOTAL['BUHIN_TOTAL'] += $value['BUHIN'];
                            $TOTAL['GAICHU_TOTAL'] += $value['GAICHU'];
                            $TOTAL['GOUKEI_TOTAL'] += $value['GOUKEI'];
                        }
                        array_push($result['data'], $TOTAL);
                        array_push($tmp_data2, $result['data']);

                        $tmp2["data"] = $tmp_data2;
                        $tmp2["mode"] = "12";
                        $datas["rptZandakaOld"] = $tmp2;
                        $rpx_file_names["rptZandakaOld"] = $data2;
                        break;
                    case "両方":
                        if (count((array) $result1['data']) > 0) {
                            $TOTAL = array('GOUKEI_TOTAL' => 0);
                            foreach ((array) $result1['data'] as $key => $value) {
                                //---20151225 li INS S.
                                $result1['data'][$key]['TODAY1'] = $this->getToday($value['TODAY']);
                                //---20151225 li INS E.
                                $TOTAL['GOUKEI_TOTAL'] += $value['GOUKEI'];
                            }
                            array_push($result1['data'], $TOTAL);
                            array_push($tmp_data1, $result1['data']);
                            $tmp1["data"] = $tmp_data1;
                            $tmp1["mode"] = "12";
                            $datas["rptZandakaNew"] = $tmp1;
                            $rpx_file_names["rptZandakaNew"] = $data1;
                        }
                        if (count((array) $result2['data']) > 0) {
                            $TOTAL = array(
                                'BUHIN_TOTAL' => 0,
                                'GAICHU_TOTAL' => 0,
                                'GOUKEI_TOTAL' => 0
                            );
                            foreach ((array) $result2['data'] as $key => $value) {
                                //---20151225 li INS S.
                                $result2['data'][$key]['TODAY1'] = $this->getToday($value['TODAY']);
                                //---20151225 li INS E.
                                $TOTAL['BUHIN_TOTAL'] += $value['BUHIN'];
                                $TOTAL['GAICHU_TOTAL'] += $value['GAICHU'];
                                $TOTAL['GOUKEI_TOTAL'] += $value['GOUKEI'];
                            }
                            array_push($result2['data'], $TOTAL);
                            array_push($tmp_data2, $result2['data']);
                            $tmp2["data"] = $tmp_data2;
                            $tmp2["mode"] = "12";
                            $datas["rptZandakaOld"] = $tmp2;
                            $rpx_file_names["rptZandakaOld"] = $data2;
                        }
                        break;
                }
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
            if (rtrim($tyohan1) == "新車" || rtrim($tyohan1) == "両方") {
                $this->ClsLogControl->fncLogEntry("frmShikakariPrint_Sinsya", $intState, $lngOutCntS, $tyohan1, $cboYMStart);
            }
            if (rtrim($tyohan1) == "中古車" || rtrim($tyohan1) == "両方") {
                $this->ClsLogControl->fncLogEntry("frmShikakariPrint_Chuko", $intState, $lngOutCntC, $tyohan1, $cboYMStart);
            }
        }
        $this->fncReturn($result);
    }

    //---20151225 li INS S.
    public function getToday($inData)
    {
        $strToday = $inData;
        switch (substr($strToday, 0, 1)) {
            case "S":
                $toData = "昭和";
                break;
            case "H":
                $toData = "平成";
                break;
            case "R":
                $toData = "令和";
                break;
        }
        return $toData = $toData . substr($strToday, 1, 2) . "年" . substr($strToday, 3, 2) . "月" . substr($strToday, 5, 2) . "日";
    }
    //---20151225 li INS E.
}