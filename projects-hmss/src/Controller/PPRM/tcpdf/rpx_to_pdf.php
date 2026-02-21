<?php
/**
 *
 * ラインマスタメンテナンス
 *
 * @alias FrmLineMst
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug             内容                               担当
 * YYYYMMDD           #ID                     XXXXXX                            FCSDL
 * 20150916           #2141                   BUG                               Yuanjh
 * 20151106           #2258                   BUG                               Yuanjh
 * 20151111           #2115                   BUG                               Yuanjh
 * 20151207           #2286					  BUG                               li
 * 20151230           #2294					  BUG                               li
 * 20170728                                        li
 * 20250514           Bug   承認状況検索画面で、２つの拠点でほぼ同時に承認処理を行ったところ  caina
 *                              帳票プレビューに 別拠点の内容が表示されたというものです
 * --------------------------------------------------------------------------------------------
 */

/**
 * rpxファイルから描画情報を読み込んで、pdfファイルを出力する
 *
 * @package default
 * @author zheng.huiyun
 */

use App\Controller\AppController;

class rpx_to_pdf extends AppController
{
    //定数定義 s

    private $NL = "<br/>";
    private $SPACE = "&nbsp;";
    private $INDENT = "&nbsp;&nbsp;&nbsp;&nbsp;";

    private $FONT_FAMILY_MINCHO = "msmincho";
    //private $FONT_FAMILY_GOTHIC = "kozgopromedium";
    //fan modify s.
    private $FONT_FAMILY_GOTHIC = "msgothic";
    //fan modify e.
    private $FONT_FAMILY_GOTHICUI = "msgothicui";

    private $INKAN_JPG = "inkan.jpg";

    // 20250514 caina upd s
    // private $OVER_TIME = 3600;
    private $OVER_TIME = 3600000;
    // 20250514 caina upd e

    private $REPORTS_TEMP_PATH = "reports/temp/";

    //定数定義 e

    //変数定義 s

    public $data = array();
    public $rpx_file = "";
    public $rpx_file_name = "";

    private $LeftMargin = 0;
    private $RightMargin = 0;
    private $TopMargin = 0;
    private $BottomMargin = 0;

    private $TopMargin_current = 0;

    private $TopMargin_current_GroupHeader2 = 0;
    private $has_GroupHeader2 = false;

    private $mode = "1";
    private $font_family = "";
    private $font_size = 9;

    private $rpx_file_names = array();
    private $datas = array();
    private $data_fields = array();

    private $orientation = "";

    private $file_path = "";
    private $output_path = "";
    private $file_cnt = 1;

    //zheng
    private $detail_is_drowed = false;
    //zheng
    //20140401 yushuangji add start
    private $pageNumber = 0;
    private $tmpCaseForMode4;
    private $ActiveReportsLayout;
    //20140401 yushuangji add end
    //fan add s
    private $tempmark = false;
    private $temppagemark = false;
    private $GroupFlag = "";
    //fan add e
    //変数定義 ｅ

    //--20151106  Yuanjh ADD   S.
    private $pageKijyunListflg = FALSE;
    private $pageZanErrListflg = FALSE;
    private $pageSiwakeErrList = FALSE;
    private $pageKijyunUnmachiListNew = FALSE;
    //--20151106  Yuanjh ADD   E.

    //--20151111  Yuanjh ADD   S.
    private $DateFrom = "";
    private $DateTo = "";
    //--20151111  Yuanjh ADD   E.
    private $GroupFooter1 = false;
    private $GroupFooter2 = false;
    private $GroupFooter3 = false;
    private $GroupFooter4 = false;
    private $GroupFooter5 = false;
    private $PageMargin;
    private $Orientation;
    private $first;
    private $suitoEigKarikatasum;
    private $suitoEigKashikatasum;
    private $suitoEigInpdenpyno;
    private $suitoEigKon;
    private $bankNMFT;
    private $rpx_file_name_son;
    private $rptSeibinippoMaineData;
    private $GroupHeader1;
    private $GroupHeader2;
    private $GroupHeader3;
    private $GroupHeader4;
    private $GroupHeader5;
    private $GroupHeader6;
    private $GroupHeader7;
    private $GroupHeader8;
    private $GroupHeader9;
    private $GroupHeader10;
    private $sum1;
    private $sum2;
    private $sum3;
    private $sum123;
    /**
     *
     *
     * @return void
     * @author
     */
    public function __construct($rpx_file_names, $datas, $mode = "1", $tmpCaseForMode4 = "")
    {
        $this->tmpCaseForMode4 = $tmpCaseForMode4;
        $this->rpx_file_names = $rpx_file_names;
        $this->datas = $datas;
        $this->file_cnt = (int) $mode;
        //var_dump($rpx_file_names);

        $iterator = new DirectoryIterator(dirname(__FILE__));
        $this->file_path = $iterator->getPath();

        $this->output_path = WWW_ROOT . $this->REPORTS_TEMP_PATH;
    }

    //20161227 yangzi add s
    public function circle($pdf, $dotX, $dotY, $radius, $text)
    {
        $style = array(
            //20170728 LQS UPD S
            // 'width' => 0.75,
            'width' => 0.4,
            //20170728 LQS UPD E
            'cap' => 'butt',
            'join' => 'miter',
            'dash' => 0,
            'color' => array(
                255,
                0,
                0
            )
        );

        $lineX1 = $dotX - sqrt($radius * $radius - ((($radius * 2) / 3) / 2) * ((($radius * 2) / 3) / 2));
        $lineX2 = $dotX + sqrt($radius * $radius - ((($radius * 2) / 3) / 2) * ((($radius * 2) / 3) / 2));
        $line1Y = $dotY - ((($radius * 2) / 3) / 2);
        $line2Y = $dotY + ((($radius * 2) / 3) / 2);
        $pdf->SetLineStyle($style);
        //20170803 lqs INS S
        if (!($text['text1'] == "" && $text['text2'] == "" && $text['text3'] == "")) {
            //20170803 lqs INS E
            $pdf->Line($lineX1, $line1Y, $lineX2, $line1Y);
            $pdf->Line($lineX1, $line2Y, $lineX2, $line2Y);
            $pdf->Circle($dotX, $dotY, $radius);
        }

        //180是圆点x，40是圆点y，10是半径

        $style['color'] = array(
            0,
            0,
            0
        );
        //20170728 LQS UPD S
        //$pdf -> SetTextColor(0, 0, 0);
        $pdf->SetTextColor(255, 0, 0);
        $pdf->SetFontSize(7.5);
        // $pdf -> Text($lineX1 + 6, $line1Y - 6, $text['text1']);
        // $pdf -> Text($lineX1 + 6, $line2Y - 6, $text['text2']);
        // $pdf -> Text($lineX1 + 6, $line2Y, $text['text3']);
        //20171010 YIN UPD S
        // $pdf -> Text($lineX1 + 3, $line1Y - 4, $text['text1']);
        $this->Text($pdf, 10, $lineX1 + 3, $line1Y - 4, $text['text1']);
        //20171010 YIN UPD E
        //20170925 YIN UPD S
        //20171010 YIN INS S
        $pdf->SetFontSize(10);
        //20171010 YIN INS E
        // $pdf -> Text($lineX1, $line2Y - 4.5, $text['text2']);
        $this->Text($pdf, 14, $lineX1 + 1, $line2Y - 5, $text['text2']);
        //20170925 YIN UPD E
        //20171010 YIN INS S
        $pdf->SetFontSize(7.5);
        //20171010 YIN INS E
        $this->Text($pdf, 10, $lineX1 + 3, $line2Y + 0.5, $text['text3']);
        //20170728 LQS UPD E
    }

    //20171010 YIN INS S
    public function Text($pdf, $w, $x, $y, $txt, $fstroke = false, $fclip = false, $ffill = true, $border = 0, $ln = 0, $align = 'C', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'T', $valign = 'M', $rtloff = false)
    {
        $pdf->setTextRenderingMode($fstroke, $ffill, $fclip);
        $pdf->SetXY($x, $y, $rtloff);
        $pdf->Cell($w, 0, $txt, $border, $ln, $align, $fill, $link, $stretch, $ignore_min_height, $calign, $valign);
    }

    //20171010 YIN INS E

    //20161227 yangzi add e

    /**
     * undocumented function
     *
     * @return string
     * @author
     */
    public function to_pdf()
    {
        // Include the main TCPDF library (search for installation path).
        //2013-12-19 qiuqiu modify start
        // $tcpdf_file = $this -> file_path . "/" . 'tcpdf/tcpdf.php';
        $tcpdf_file = str_replace("PPRM/tcpdf", "Component", $this->file_path) . "/" . 'tcpdf/tcpdf/tcpdf.php';
        //2013-12-19 qiuqiu modify end
        require_once $tcpdf_file;

        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // remove default header/footer s

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // remove default header/footer e

        // set image scale factor s

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set image scale factor e

        //20170926 lqs INS S
        $beforeFilePages = 0;
        //20170926 lqs INS E
        foreach ($this->datas as $key => $value) {
            $this->mode = $value["mode"];
            $datas = $value["data"];
            $cnt_datas = count($datas);
            for ($i = 0; $i < $cnt_datas; $i++) {
                foreach ($this->rpx_file_names as $rpx_file_name => $data_fields) {
                    //20170824 lqs UPD S
                    //if (strpos($rpx_file_name, $key) !== false)
                    if ($rpx_file_name == $key)
                    //20170824 lqs UPD E
                    {
                        //20170926 lqs INS S
                        $this->PageMargin = array();
                        //20170926 lqs INS E
                        $this->data_fields = $data_fields;
                        $this->rpx_file_name = $rpx_file_name;
                        //---20151230 li UPD S.
                        // $this -> draw_pdf($pdf, $rpx_file_name, $datas[$i]);
                        $this->draw_pdf($pdf, $datas[$i]);
                        //---20151230 li UPD E.
                        //20170926 lqs INS S
                        $allPages = $pdf->getNumPages();
                        $curFilePages = $allPages - $beforeFilePages;
                        for ($j = 0; $j < $curFilePages; $j++) {
                            $pdf->setPage($j + 1 + $beforeFilePages);
                            $this->draw_pdf($pdf, $curFilePages, true, $j);
                        }
                        $beforeFilePages = $allPages;
                        //20170926 lqs INS E
                    }
                }
            }
        }

        //Close and output PDF document
        // 20250514 caina upd s
        // $date = new DateTime();
        // $ts = $date->getTimestamp();
        $ts = round(microtime(true) * 1000);
        // 20250514 caina upd e

        //1時間以前生成されたファイルを削除する s
        $files = array();
        $files = scandir($this->output_path);
        foreach ($files as $key => $value) {
            if ($value === '.' || $value === '..') {
                continue;
            }
            if (strpos($value, "output_") !== false) {
                $tmp_ts = str_replace("output_", "", $value);
                $tmp_ts = (int) $tmp_ts;
                if ($ts - $tmp_ts >= $this->OVER_TIME) {
                    unlink($this->output_path . "/" . $value);
                }
            }
        }
        //1時間以前生成されたファイルを削除する e

        $file_name = "output_" . $ts . ".pdf";
        $output_file = $this->output_path . $file_name;
        $pdf->Output($output_file, 'F');

        $output_file = $this->REPORTS_TEMP_PATH . $file_name;
        return $output_file;
    }

    //**********************************
    //fan add start
    //**********************************
    public function to_pdf2($documentType = "A3")
    {
        // Include the main TCPDF library (search for installation path).
        //2013-12-19 qiuqiu modify start
        $tcpdf_file = str_replace("PPRM/tcpdf", "Component", $this->file_path) . "/" . 'tcpdf/tcpdf/tcpdf.php';
        //2013-12-19 qiuqiu modify end
        require_once $tcpdf_file;
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, $documentType, true, 'UTF-8', false);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // remove default header/footer s

        $pdf->setPrintHeader(FALSE);
        $pdf->setPrintFooter(FALSE);

        // remove default header/footer e

        // set image scale factor s

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set image scale factor e
        foreach ($this->datas as $key => $value) {
            $this->mode = $value["mode"];
            //---20151111  Yuanjh  ADD S.
            if ($this->rpx_file_name = "rptScUriageChk") {
                $this->DateFrom = $value["DateFrom"];
                $this->DateTo = $value["DateTo"];
            }
            //---20151111  Yuanjh  ADD E.

            $datas = $value["data"];
            $cnt_datas = count($datas);
            for ($i = 0; $i < $cnt_datas; $i++) {
                foreach ($this->rpx_file_names as $rpx_file_name => $data_fields) {
                    //20170824 lqs UPD S
                    //if (strpos($rpx_file_name, $key) !== false)
                    if ($rpx_file_name == $key)
                    //20170824 lqs UPD E
                    {
                        $this->data_fields = $data_fields;
                        $this->rpx_file_name = $rpx_file_name;
                        $this->draw_pdf($pdf, $rpx_file_name, $datas[$i]);
                    }

                }
            }
        }
        //Close and output PDF document
        $date = new DateTime();
        $ts = $date->getTimestamp();

        //1時間以前生成されたファイルを削除する s
        $files = array();
        $files = scandir($this->output_path);
        foreach ($files as $key => $value) {
            if ($value === '.' || $value === '..') {
                continue;
            }
            if (strpos($value, "output_") !== false) {
                $tmp_ts = str_replace("output_", "", $value);
                $tmp_ts = (int) $tmp_ts;
                if ($ts - $tmp_ts >= $this->OVER_TIME) {
                    unlink($this->output_path . "/" . $value);
                }
            }
        }
        //1時間以前生成されたファイルを削除する e

        $file_name = "output_" . $ts . ".pdf";
        $output_file = $this->output_path . $file_name;
        $pdf->Output($output_file, 'F');

        $output_file = '../../' . $this->REPORTS_TEMP_PATH . $file_name;
        return $output_file;
    }

    //**********************************
    //fan add end
    //**********************************

    /**
     * draw_pdf
     *
     * @return void
     * @author
     */
    //---20151230 li UPD S.
    // public function draw_pdf($pdf, $rpx_file_name, $data)
    //20170926 lqs UPD S
    //public function draw_pdf($pdf, $data)
    public function draw_pdf($pdf, $data, $sumPage = false, $pageIndex = 0)
    //20170926 lqs UPD E
    //---20151230 li UPD E.
    {
        //---20151230 li UPD S.
        // $rpx_file = $this -> file_path . "/" . $rpx_file_name . ".rpx";
        $rpx_file = $this->file_path . "/" . $this->rpx_file_name . ".rpx";
        //---20151230 li UPD E.

        //确认文件是否存在
        if (file_exists($rpx_file)) {
            //把rpx文件导入，生成xml对象
            $xml = simplexml_load_file($rpx_file);

            //1级节点
            //无可用的信息，略过

            $StyleSheet = $xml->StyleSheet;
            foreach ($StyleSheet->children() as $Style) {
                $attr_Style = $Style->attributes();
                if ($attr_Style->Name == "Normal") {
                    $Value = $this->get_style_attributes($attr_Style->Value);
                    if (array_key_exists("font-family", $Value) == true) {
                        $this->font_family = $Value["font-family"];
                        switch ($this->font_family) {
                            case "ＭＳ ゴシック":
                                $this->font_family = $this->FONT_FAMILY_GOTHIC;
                                break;
                            case "ＭＳ 明朝":
                                $this->font_family = $this->FONT_FAMILY_MINCHO;
                                break;
                            case "MS UI Gothic":
                                $this->font_family = $this->FONT_FAMILY_GOTHICUI;
                                break;
                            default:
                                break;
                        }
                    }
                    if (array_key_exists("font-size", $Value) == true) {
                        $this->font_size = $Value["font-size"];
                    }
                }
            }

            // set margins s

            //首先需要设定生成pdf的页面余白
            //rpx文件里对应的余白信息在2级节点的"PageSettings"里
            //所以先读取"PageSettings"
            $PageSettings = $xml->PageSettings;

            foreach ($PageSettings->attributes() as $key => $value) {
                switch ($key) {
                    case 'LeftMargin':
                        $this->LeftMargin = $this->twip_to_millimeter($value);
                        break;
                    case 'RightMargin':
                        $this->RightMargin = $this->twip_to_millimeter($value);
                        break;
                    case 'TopMargin':
                        $this->TopMargin = $this->twip_to_millimeter($value);
                        break;
                    case 'BottomMargin':
                        $this->BottomMargin = $this->twip_to_millimeter($value);
                        break;
                    case 'Orientation':
                        switch ($value) {
                            case "1":
                                $this->Orientation = "P";
                                break;
                            case "2":
                                $this->Orientation = "L";
                                break;
                            default:
                                break;
                        }
                        break;
                    default:
                        break;
                }
            }
            ;
            $pdf->SetMargins($this->LeftMargin, $this->TopMargin, $this->RightMargin);

            // set margins e

            // set auto page breaks s

            $pdf->SetAutoPageBreak(TRUE, $this->BottomMargin);

            // set auto page breaks e

            //开始描画具体的项目
            //从第4级节点开始
            //"Sections"->"Section"->"Control"开始解析节点的属性
            $Sections = $xml->Sections;
            $PageSettings = $xml->PageSettings;
            //20140428 yushuangji add start
            $this->ActiveReportsLayout = $xml;
            //20140428 yushuangji add end
            //20170926 lqs INS S
            if ($sumPage) {
                $arr = $this->PageMargin;
                foreach ($Sections->children() as $Section) {
                    $attr_Section = $Section->attributes();
                    if ($attr_Section->Name == "PageFooter") {
                        $footerData['PAGESUM'] = $data;
                        foreach ($Section->children() as $Control) {
                            $attr = $Control->attributes();
                            if ($attr->Name == "txtPageCntSum") {
                                if (isset($arr[$pageIndex])) {
                                    $this->TopMargin_current = $arr[$pageIndex];
                                }
                                $this->draw_field($pdf, $attr, $footerData);
                                return;
                            }
                        }
                    }
                }
            }
            //20170926 lqs INS E
            switch ($this->mode) {
                case "0":
                    //2013-11-29 zhenghuiyun delete start
                    // case "2" :
                    //2013-11-29 zhenghuiyun delete end
                    //20150815 lqs INS S
                    $this->pageNumber = 0;
                    //20150815 lqs INS E
                    $data = array_merge($this->data_fields, $data);

                    $this->draw_except_detail($pdf, $Sections, $data);

                    //2013-12-02 qiuqiu update start
                    //2013-11-29 zhenghuiyun insert start
                    // $this -> TopMargin_current = 0;
                    // $this -> TopMargin_current = $this -> TopMargin_current + $this -> TopMargin;
                    //
                    // foreach ($Sections->children() as $Section)
                    // {
                    // $attr_Section = $Section -> attributes();
                    //
                    // if ($attr_Section -> Name == "Detail")
                    // {
                    // break;
                    // }
                    //
                    // $tmp_height = $this -> twip_to_millimeter($attr_Section -> Height);
                    // $this -> TopMargin_current = $this -> TopMargin_current + $tmp_height;
                    // }
                    //2013-11-29 zhenghuiyun insert end

                    //$this -> draw_detail($pdf, $Sections, $data);
                    $this->get_detail_start_top_margin($Sections, "Detail");
                    $this->draw_detail($pdf, $Sections, $data, $data);
                    //2013-12-02 qiuqiu update end

                    break;
                //2013-11-29 zhenghuiyun insert start
                case "2":
                    $data = array_merge($this->data_fields, $data);

                    //2013-12-03 yuanquan update start
                    for ($j = 0; $j <= ($this->file_cnt - 1); $j++) {

                        $this->draw_except_detail($pdf, $Sections, $data);

                        //2013-12-03 yuanquan update end

                        // 2013-12-02 update start qq
                        // $this -> TopMargin_current = 0;
                        // $this -> TopMargin_current = $this -> TopMargin_current + $this -> TopMargin;
                        //
                        // foreach ($Sections->children() as $Section)
                        // {
                        // $attr_Section = $Section -> attributes();
                        //
                        // if ($attr_Section -> Name == "GroupHeader1")
                        // {
                        // break;
                        // }
                        //
                        // $tmp_height = $this -> twip_to_millimeter($attr_Section -> Height);
                        // $this -> TopMargin_current = $this -> TopMargin_current + $tmp_height;
                        // }
                        $this->get_detail_start_top_margin($Sections, "GroupHeader1");

                        //$this -> draw_detail($pdf, $Sections, $data);

                        $this->TopMargin_current_GroupHeader2 = $this->TopMargin_current;

                        if (array_key_exists("sub_datas", $data) == true) {
                            $cnt = count($data["sub_datas"]);
                            for ($i = 0; $i < $cnt; $i++) {
                                $data["sub_datas"][$i] = array_merge($this->data_fields, $data["sub_datas"][$i]);
                                $this->draw_detail($pdf, $Sections, $data, $data["sub_datas"][$i]);
                            }
                        }

                    }
                    // 2013-12-02 update end qq
                    break;
                //2013-11-29 zhenghuiyun insert end
                case "1":
                    $this->draw_except_detail($pdf, $Sections, $data);

                    $cnt = count($data);
                    for ($i = 0; $i < $cnt; $i++) {
                        $data[$i] = array_merge($this->data_fields, $data[$i]);
                        // $this -> draw_detail($pdf, $Sections, $data[$i]);
                        $this->draw_detail($pdf, $Sections, $data[$i], $data[$i]);
                    }
                    break;
                case "3":
                    //20150815 lqs INS S
                    $this->pageNumber = 0;
                    //20150815 lqs INS E
                    $cnt = count($data);
                    $i = 0;
                    //fan add s.
                    //---20151207 li UPD S.
                    //if ($this -> rpx_file_name == "rptJiKykTaTrkList")
                    //---20151230 li UPD S.
                    // if ($rpx_file_name == "rptJiKykTaTrkList")
                    if ($this->rpx_file_name == "rptJiKykTaTrkList")
                    //---20151230 li UPD E.
                    //---20151207 li UPD E.
                    {
                        $data[$i]['UC_NUM'] = $cnt;
                    }
                    //fan add e.
                    while ($i < $cnt) {
                        //20170802 YIN DEL S
                        // $data[$i] = array_merge($this -> data_fields, $data[$i]);
                        // if ($i == 0)
                        // {
                        // $this -> draw_except_detail($pdf, $Sections, $data[$i]);
                        // }
                        // //$this -> draw_detail($pdf, $Sections, $data[$i]);
                        // $this -> draw_detail($pdf, $Sections, $data[$i], $data[$i]);
                        // $i++;
                        //20170802 YIN DEL S

                        //20170802 YIN INS S
                        if ($this->rpx_file_name == "rptGaichuKensyuIchiran") {
                            if (($i % 13) == 0) {
                                $this->draw_except_detail($pdf, $Sections, $data[$i]);
                                $this->get_detail_start_top_margin($Sections, "GroupHeader1");
                                $this->TopMargin_current = $this->TopMargin_current + 0.5;
                            }
                            //$this -> draw_detail($pdf, $Sections, $data[$i]);
                            $this->draw_detail($pdf, $Sections, $data[$i], $data[$i]);
                            $i++;

                        } else {
                            $data[$i] = array_merge($this->data_fields, $data[$i]);
                            if ($i == 0) {
                                $this->draw_except_detail($pdf, $Sections, $data[$i]);
                            }
                            //$this -> draw_detail($pdf, $Sections, $data[$i]);
                            $this->draw_detail($pdf, $Sections, $data[$i], $data[$i]);
                            $i++;

                        }
                        //20170802 YIN INS E
                    }
                    //20140320 yushuangji add start
                    $this->detail_is_drowed = true;
                    $this->draw_except_detail($pdf, $Sections, $data[0]);
                    //20141014 fuxl add start
                    $this->detail_is_drowed = false;
                    //20141014 fuxl add end
                    //20140320 yushuangji add end
                    //zheng
                    break;
                //20140321 yushuangji add start
                case "4":
                    $tmpArr = array();
                    $cnt = count($data);
                    $pageCnt = 0;

                    for ($i = 0; $i < $cnt; $i++) {
                        if (array_key_exists($data[$i]['BUSYO_CD'], $tmpArr)) {
                            array_push($tmpArr[$data[$i]['BUSYO_CD']], $data[$i]);
                        } else {
                            $tmpArr[$data[$i]['BUSYO_CD']] = array();
                            array_push($tmpArr[$data[$i]['BUSYO_CD']], $data[$i]);
                        }
                    }
                    $tf = fopen("a_log.log", "w+");
                    foreach ($tmpArr as $key => $value) {
                        $existCnt = 0;
                        $pageCnt++;
                        $cnt = count($value);
                        $this->detail_is_drowed = false;
                        $footerData = array();
                        $footerData = $value[0];
                        $tmpData_0 = "";
                        $tmpSections = "";

                        $footerData['SERVICE_CNT'] = 0;
                        $footerData['KIGU_CNT'] = 0;
                        $footerData['KIKAI_CNT'] = 0;
                        $footerData['KOUGU_CNT'] = 0;
                        $footerData['SERVICE_KIN'] = 0;
                        $footerData['KIGU_KIN'] = 0;
                        $footerData['KIKAI_KIN'] = 0;
                        $footerData['KOUGU_KIN'] = 0;
                        $footerData['SERVICE_REASE_RYO'] = 0;
                        $footerData['KIGU_REASE_RYO'] = 0;
                        $footerData['KIKAI_REASE_RYO'] = 0;
                        $footerData['KOUGU_REASE_RYO'] = 0;

                        for ($j = 0; $j < $cnt; $j++) {
                            //set group footer datas
                            if ($footerData['SERVICE_CNT'] == 0 || $footerData['SERVICE_CNT'] == "0") {
                                $footerData['SERVICE_CNT'] = $value[$j]['SERVICE_CNT'];
                            } else {
                                $footerData['SERVICE_CNT'] += $value[$j]['SERVICE_CNT'];
                            }

                            if ($footerData['KIGU_CNT'] == 0 || $footerData['KIGU_CNT'] == "0") {
                                $footerData['KIGU_CNT'] = $value[$j]['KIGU_CNT'];
                            } else {
                                $footerData['KIGU_CNT'] += $value[$j]['KIGU_CNT'];
                            }

                            if ($footerData['KIKAI_CNT'] == 0 || $footerData['KIKAI_CNT'] == "0") {
                                $footerData['KIKAI_CNT'] = $value[$j]['KIKAI_CNT'];
                            } else {
                                $footerData['KIKAI_CNT'] += $value[$j]['KIKAI_CNT'];
                            }

                            if ($footerData['KOUGU_CNT'] == 0 || $footerData['KOUGU_CNT'] == "0") {
                                $footerData['KOUGU_CNT'] = $value[$j]['KOUGU_CNT'];
                            } else {
                                $footerData['KOUGU_CNT'] += $value[$j]['KOUGU_CNT'];
                            }

                            //--
                            if ($footerData['SERVICE_KIN'] == 0 || $footerData['SERVICE_KIN'] == "0") {
                                $footerData['SERVICE_KIN'] = $value[$j]['SERVICE_KIN'];
                            } else {
                                $footerData['SERVICE_KIN'] += $value[$j]['SERVICE_KIN'];
                            }

                            if ($footerData['KIGU_KIN'] == 0 || $footerData['KIGU_KIN'] == "0") {
                                $footerData['KIGU_KIN'] = $value[$j]['KIGU_KIN'];
                            } else {
                                $footerData['KIGU_KIN'] += $value[$j]['KIGU_KIN'];
                            }

                            if ($footerData['KIKAI_KIN'] == 0 || $footerData['KIKAI_KIN'] == "0") {
                                $footerData['KIKAI_KIN'] = $value[$j]['KIKAI_KIN'];
                            } else {
                                $footerData['KIKAI_KIN'] += $value[$j]['KIKAI_KIN'];
                            }

                            if ($footerData['KOUGU_KIN'] == 0 || $footerData['KOUGU_KIN'] == "0") {
                                $footerData['KOUGU_KIN'] = $value[$j]['KOUGU_KIN'];
                            } else {
                                $footerData['KOUGU_KIN'] += $value[$j]['KOUGU_KIN'];
                            }

                            //--

                            //---
                            if ($footerData['SERVICE_REASE_RYO'] == 0 || $footerData['SERVICE_REASE_RYO'] == "0") {
                                $footerData['SERVICE_REASE_RYO'] = $value[$j]['SERVICE_REASE_RYO'];
                            } else {
                                $footerData['SERVICE_REASE_RYO'] += $value[$j]['SERVICE_REASE_RYO'];
                            }

                            if ($footerData['KIGU_REASE_RYO'] == 0 || $footerData['KIGU_REASE_RYO'] == "0") {
                                $footerData['KIGU_REASE_RYO'] = $value[$j]['KIGU_REASE_RYO'];
                            } else {
                                $footerData['KIGU_REASE_RYO'] += $value[$j]['KIGU_REASE_RYO'];
                            }

                            if ($footerData['KIKAI_REASE_RYO'] == 0 || $footerData['KIKAI_REASE_RYO'] == "0") {
                                $footerData['KIKAI_REASE_RYO'] = $value[$j]['KIKAI_REASE_RYO'];
                            } else {
                                $footerData['KIKAI_REASE_RYO'] += $value[$j]['KIKAI_REASE_RYO'];
                            }

                            if ($footerData['KOUGU_REASE_RYO'] == 0 || $footerData['KOUGU_REASE_RYO'] == "0") {
                                $footerData['KOUGU_REASE_RYO'] = $value[$j]['KOUGU_REASE_RYO'];
                            } else {
                                $footerData['KOUGU_REASE_RYO'] += $value[$j]['KOUGU_REASE_RYO'];
                            }

                            //---
                            if ($value[$j]['SYUTOKU_KIN'] != "") {
                                $existCnt++;
                            } else {
                                $existCnt = "0";
                            }

                            $value[$j]['PAGE'] = $pageCnt;
                            $value[$j] = array_merge($this->data_fields, $value[$j]);

                            if ($j == 0) {
                                $tmpData_0 = $value[$j];
                                $this->draw_except_detail($pdf, $Sections, $value[$j]);
                                $tmpSections = $Sections;
                            }
                            $this->draw_detail($pdf, $Sections, $value[$j], $value[$j]);
                        }
                        $this->detail_is_drowed = true;
                        $footerData['SYUTOKU_CNT_Total'] = $existCnt;
                        $this->draw_except_detail($pdf, $Sections, $footerData, $PageSettings, $tmpSections, $tmpData_0);
                    }
                    fclose($tf);
                    break;
                //20140321 yushuangji add end
                //fan add s.
                case "5":
                    //20150815 lqs INS S
                    $this->pageNumber = 0;
                    //20150815 lqs INS E
                    $cnt = count($data);
                    $cnt1 = count($data[$cnt - 1]);
                    $i = 0;
                    $j = 0;
                    $this->draw_except_detail($pdf, $Sections, $data[0]);

                    $current = "";
                    $last = "";
                    //20170801 lqs INS S
                    if ($this->rpx_file_name == "rptGenkinSuitochoEigyoKinshu") {
                        $this->TopMargin_current = $this->TopMargin_current - 6;
                        $cnt = $cnt + 1;
                    }
                    //20170801 lqs INS E
                    while ($i < $cnt - 1) {
                        //---20151207 li UPD S.
                        // if ($this -> rpx_file_name == "rptReOutToukeiReport")
                        //---20151230 li UPD S.
                        // if ($rpx_file_name == "rptReOutToukeiReport")
                        if ($this->rpx_file_name == "rptReOutToukeiReport")
                        //---20151230 li UPD E.
                        //---20151207 li UPD E.
                        {
                            $current = $data[$i]['KBN'];
                        }
                        //20170801 lqs INS S
                        else
                            if ($this->rpx_file_name == "rptGenkinSuitochoEigyoKinshu") {
                                $current = $data[$i]['TEN_HJM_NO'];
                            }
                            //20170801 lqs INS E
                            else {
                                $current = $data[$i]['CAR_KBN'];
                            }
                        $this->detail_is_drowed = true;
                        if ($current !== $last && $last !== "") {
                            $this->draw_except_detail($pdf, $Sections, $data[$cnt - 1][$j]);
                            $j++;
                        }
                        $data[$i] = array_merge($this->data_fields, $data[$i]);
                        $this->draw_detail($pdf, $Sections, $data[$i], $data[$i]);
                        $i++;
                        $last = $current;
                        //---20151207 li UPD S.
                        // if ($this -> TopMargin_current > 280)
                        //---20151230 li UPD S.
                        // if ($rpx_file_name == "rptReOutToukeiReport" && $this -> TopMargin_current > 270)
                        if ($this->rpx_file_name == "rptReOutToukeiReport" && $this->TopMargin_current > 270)
                        //---20151230 li UPD E.
                        //---20151207 li UPD E.
                        {
                            $this->temppagemark = true;
                            $this->draw_except_detail($pdf, $Sections, $data[0]);
                        }
                        //---20151207 li INS S.
                        else {
                            if ($this->TopMargin_current > 280) {
                                $this->temppagemark = true;
                                $this->draw_except_detail($pdf, $Sections, $data[0]);
                            }
                        }
                        //---20151207 li INS E.
                        $this->temppagemark = false;
                    }
                    $this->draw_except_detail($pdf, $Sections, $this->rpx_file_name == "rptGenkinSuitochoEigyoKinshu" ? null : $data[$cnt - 1][$j]);
                    $this->tempmark = true;
                    $this->draw_except_detail($pdf, $Sections, $this->rpx_file_name == "rptGenkinSuitochoEigyoKinshu" ? null : $data[$cnt - 1][$cnt1 - 1]);
                    $this->detail_is_drowed = false;
                    $this->temppagemark = false;
                    $this->tempmark = false;
                    break;
                case "6":
                    $cnt = count($data);
                    $cnt1 = count($data[$cnt - 1]);
                    $i = 0;
                    $j = 0;
                    $this->draw_except_detail($pdf, $Sections, $data[0]);

                    $current = "";
                    $last = "";
                    while ($i < $cnt - 1) {
                        $current = $data[$i]['WHERE_KRI_DATE'];
                        $this->detail_is_drowed = true;
                        if ($current !== $last && $last !== "") {
                            $this->draw_except_detail($pdf, $Sections, $data[$cnt - 1][$j]);
                            $j++;
                            $this->detail_is_drowed = false;
                            $this->draw_except_detail($pdf, $Sections, $data[$i]);
                        }
                        $data[$i] = array_merge($this->data_fields, $data[$i]);
                        $this->draw_detail($pdf, $Sections, $data[$i], $data[$i]);
                        $i++;
                        $last = $current;
                        if ($this->TopMargin_current > 280) {
                            $this->detail_is_drowed = false;
                            $this->draw_except_detail($pdf, $Sections, $data[$i]);
                        }
                    }
                    $this->detail_is_drowed = true;
                    $this->draw_except_detail($pdf, $Sections, $data[$cnt - 1][$j]);
                    $this->detail_is_drowed = false;
                    break;
                case "7":
                    $cnt = count($data);
                    $i = 0;
                    $this->draw_except_detail($pdf, $Sections, $data[0]);
                    while ($i < $cnt - 1) {
                        $data[$i] = array_merge($this->data_fields, $data[$i]);
                        $this->draw_detail($pdf, $Sections, $data[$i], $data[$i]);
                        $i++;
                    }
                    $this->detail_is_drowed = true;
                    $this->draw_except_detail($pdf, $Sections, $data[$cnt - 1]);
                    break;
                case "8":
                    $cnt = count($data);
                    $cnt1 = count($data[$cnt - 1]);
                    $i = 0;
                    $j = 0;
                    $this->draw_except_detail($pdf, $Sections, $data[0]);
                    $this->get_detail_start_top_margin($Sections, "GroupHeader1");
                    $current = "";
                    $last = "";
                    while ($i < $cnt - 1) {
                        $this->detail_is_drowed = true;
                        $current = $data[$i]['CNT'];
                        if ($current !== $last && $last !== "") {
                            $this->TopMargin_current = $this->TopMargin_current - 9.5;
                            $this->draw_except_detail($pdf, $Sections, $data[$cnt - 1][$j]);
                            $j++;
                            $this->detail_is_drowed = false;
                            $this->draw_except_detail($pdf, $Sections, $data[$i]);
                            $this->get_detail_start_top_margin($Sections, "GroupHeader1");
                        }

                        $data[$i] = array_merge($this->data_fields, $data[$i]);
                        $this->draw_detail($pdf, $Sections, $data[$i], $data[$i]);
                        $last = $current;
                        $i++;
                    }
                    $this->detail_is_drowed = true;
                    $this->get_detail_start_top_margin($Sections, "Detail");
                    $this->TopMargin_current = $this->TopMargin_current - 9.5;
                    $this->draw_except_detail($pdf, $Sections, $data[$cnt - 1][$j]);
                    break;
                case "9":
                    $cnt = count($data);

                    foreach ($data as $key => $value) {
                        if (array_key_exists("TOUKI_JUNI", $value)) {
                            $c1 = $key;
                            break;
                        }
                    }

                    foreach ($data as $key => $value) {
                        if (array_key_exists("MEMO", $value)) {
                            $c2 = $key;
                            break;
                        }
                    }

                    $i = 0;
                    $this->draw_except_detail($pdf, $Sections, $data[$i]);
                    //---20151207 li UPD S.
                    //if ($this -> rpx_file_name == "rptChukoKaverRank" || $this -> rpx_file_name == "rptChukoKaverRank_yachin")
                    //---20151230 li UPD S.
                    // if ($rpx_file_name == "rptChukoKaverRank" || $rpx_file_name == "rptChukoKaverRank_yachin")
                    if ($this->rpx_file_name == "rptChukoKaverRank" || $this->rpx_file_name == "rptChukoKaverRank_yachin")
                    //---20151230 li UPD E.
                    //---20151207 li UPD E.
                    {
                        $this->TopMargin_current = $this->TopMargin_current - 14;
                    }

                    $this->TopMargin_current = $this->TopMargin_current - 3.55;
                    while ($i < $c1) {

                        $this->draw_detail($pdf, $Sections, $data[$i], $data[$i]);
                        $i++;
                    }
                    $this->get_detail_start_top_margin($Sections, "Detail");
                    //---20151207 li UPD S.
                    //if ($this -> rpx_file_name == "rptChukoKaverRank" || $this -> rpx_file_name == "rptChukoKaverRank_yachin")
                    //---20151230 li UPD S.
                    // if ($rpx_file_name == "rptChukoKaverRank" || $rpx_file_name == "rptChukoKaverRank_yachin")
                    if ($this->rpx_file_name == "rptChukoKaverRank" || $this->rpx_file_name == "rptChukoKaverRank_yachin")
                    //---20151230 li UPD E.
                    //---20151207 li UPD E.
                    {
                        $this->TopMargin_current = $this->TopMargin_current - 0.5;
                    }
                    $this->TopMargin_current = $this->TopMargin_current + 0.5;
                    while ($i < $c2) {
                        $this->draw_detail1($pdf, $Sections, $data[$i]);
                        $i++;
                    }
                    //---20151207 li UPD S.
                    //if ($this -> rpx_file_name == "rptChukoKaverRank")
                    //---20151230 li UPD S.
                    // if ($rpx_file_name == "rptChukoKaverRank")
                    if ($this->rpx_file_name == "rptChukoKaverRank")
                    //---20151230 li UPD E.
                    //---20151207 li UPD E.
                    {
                        $this->TopMargin_current = $this->TopMargin_current + 4;
                    }
                    $this->TopMargin_current = $this->TopMargin_current + 3;
                    while ($i < $cnt) {
                        $this->draw_detail2($pdf, $Sections, $data[$i]);
                        $i++;
                    }
                    break;
                case "10":
                    $cnt = count($data);
                    $this->draw_except_detail($pdf, $Sections, $data[0]);
                    $CURRENT_BUSYO = "";
                    $LAST_BUSYO = "";
                    $CURRENT_SYAIN = "";
                    $LAST_SYAIN = "";
                    $i = 0;
                    while ($i < $cnt - 3) {
                        $CURRENT_BUSYO = $data[$i]['ATUKAI_BUSYO'];
                        $CURRENT_SYAIN = $data[$i]['ATUKAI_SYAIN'];
                        if ($LAST_BUSYO != "" && $CURRENT_BUSYO != $LAST_BUSYO) {
                            $this->detail_is_drowed = false;
                            $this->draw_except_detail($pdf, $Sections, $data[$i]);
                        }
                        if ($LAST_SYAIN != "" && $CURRENT_SYAIN != $LAST_SYAIN) {
                            $this->GroupFlag = "GroupHeader2";
                            $this->TopMargin_current = $this->TopMargin_current + 5.5;
                            $this->detail_is_drowed = true;
                            $this->draw_except_detail($pdf, $Sections, $data[$i]);
                        }
                        if ($data[$i]['flag'] == "Detail") {
                            $this->detail_is_drowed = false;
                            $this->draw_detail($pdf, $Sections, $data[$i], $data[$i]);
                        } else {
                            $this->detail_is_drowed = true;
                            $this->GroupFlag = $data[$i]['flag'];
                            $temp = $this->GroupFlag;
                            if ($this->GroupFlag == "GroupFooter5") {
                                $this->GroupFlag = "GroupFooter10";
                                $this->TopMargin_current = $this->TopMargin_current - 6;
                                $this->draw_except_detail($pdf, $Sections, $data[$i]);
                                $this->TopMargin_current = $this->TopMargin_current - 5.5;
                            } elseif ($this->GroupFlag == "GroupFooter6") {
                                $this->GroupFlag = "GroupFooter10";
                                $this->TopMargin_current = $this->TopMargin_current - 2;
                                $this->draw_except_detail($pdf, $Sections, $data[$i]);
                                $this->TopMargin_current = $this->TopMargin_current - 5.5;
                            }

                            $this->GroupFlag = $temp;
                            if ($data[$i]['visible']) {
                                $this->draw_except_detail($pdf, $Sections, $data[$i]);
                                $this->TopMargin_current = $this->TopMargin_current - 4.5;
                            }
                        }
                        $i++;
                        $LAST_BUSYO = $CURRENT_BUSYO;
                        $LAST_SYAIN = $CURRENT_SYAIN;
                    }
                    $this->GroupFlag = "GroupFooter10";
                    $this->draw_except_detail($pdf, $Sections, $data[$i]);
                    $this->detail_is_drowed = false;
                    $this->tempmark = false;
                    $this->temppagemark = true;
                    $this->draw_except_detail($pdf, $Sections, $data[$i]);
                    $this->detail_is_drowed = true;
                    while ($i < $cnt) {
                        $this->GroupFlag = $data[$i]['flag'];
                        if ($data[$i]['visible']) {
                            $this->draw_except_detail($pdf, $Sections, $data[$i]);
                            $this->TopMargin_current = $this->TopMargin_current - 4;
                        }
                        $i++;
                    }
                    break;
                case "11":
                    $cnt = count($data);
                    $cnt1 = count($data[$cnt - 1]);
                    $i = 0;
                    $j = 0;
                    $this->draw_except_detail($pdf, $Sections, $data[0]);
                    $this->TopMargin_current = $this->TopMargin_current - 10;
                    $this->tempmark = true;
                    $current = "";
                    $last = "";
                    while ($i < $cnt - 1) {
                        $current = $data[$i]['CARNO'];
                        $this->detail_is_drowed = true;
                        if ($current !== $last && $last !== "") {
                            $this->GroupFlag = "GroupFooter2";
                            $this->TopMargin_current = $this->TopMargin_current - 5;
                            $this->draw_except_detail($pdf, $Sections, $data[$cnt - 1][$j]);
                            $j++;
                            $this->GroupFlag = "GroupHeader1";
                            $this->draw_except_detail($pdf, $Sections, $data[$i]);
                            $this->TopMargin_current = $this->TopMargin_current - 10;
                        }
                        $data[$i] = array_merge($this->data_fields, $data[$i]);
                        $this->detail_is_drowed = false;
                        $this->draw_detail($pdf, $Sections, $data[$i], $data[$i]);
                        $this->detail_is_drowed = true;
                        $i++;
                        $last = $current;
                        // if ($this -> TopMargin_current > 280)
                        // {
                        // $this -> detail_is_drowed = false;
                        // $this -> draw_except_detail($pdf, $Sections, $data[0]);
                        // $this -> TopMargin_current = $this -> TopMargin_current - 5;
                        // }
                    }
                    $this->GroupFlag = "GroupFooter2";
                    $this->TopMargin_current = $this->TopMargin_current - 5;
                    $this->draw_except_detail($pdf, $Sections, $data[$cnt - 1][$j]);
                    $this->GroupFlag = "GroupFooter1";
                    $this->TopMargin_current = $this->TopMargin_current - 5;
                    $this->draw_except_detail($pdf, $Sections, $data[$cnt - 1][$cnt1 - 1]);
                    break;
                case "12":
                    //20150815 lqs INS S
                    $this->pageNumber = 0;
                    //20150815 lqs INS E
                    $cnt = count($data);
                    $i = 0;

                    while ($i < $cnt) {
                        //20170802 lqs INS S
                        $this->first = false;
                        if ($i < 1) {
                            $this->first = true;
                        }
                        if ($this->rpx_file_name == "rptGenkinSuitochoEigyo") {
                            if (($i % 8) == 0) {
                                $this->draw_except_detail($pdf, $Sections, $data[0]);
                                $this->get_detail_start_top_margin($Sections, "GroupHeader3");
                                $this->TopMargin_current = $this->TopMargin_current + 0.5;
                            }
                        } elseif ($this->rpx_file_name == "rptShiireMeisaiIchiran") {
                            if (($i % 8) == 0) {
                                $this->draw_except_detail($pdf, $Sections, $data[0]);
                                $this->get_detail_start_top_margin($Sections, "GroupHeader3");
                                $this->TopMargin_current = $this->TopMargin_current + 0.5;
                            }
                        } elseif ($this->rpx_file_name == "rptFurikaeMeisaiIchiran") {
                            if (($i % 6) == 0) {
                                $this->draw_except_detail($pdf, $Sections, $data[0]);
                                $this->get_detail_start_top_margin($Sections, "GroupHeader2");
                                $this->TopMargin_current = $this->TopMargin_current + 0.5;
                            }
                        } else {
                            //20170802 lqs INS E
                            if (($i % 43) == 0) {
                                $this->draw_except_detail($pdf, $Sections, $data[0]);
                                $this->get_detail_start_top_margin($Sections, "GroupHeader2");
                                $this->TopMargin_current = $this->TopMargin_current + 0.5;
                            }
                        }
                        $data[$i] = array_merge($this->data_fields, $data[$i]);
                        $this->draw_detail($pdf, $Sections, $data[$i], $data[$i]);
                        $i++;

                    }
                    if (($i % 43) == 0) {
                        $this->draw_except_detail($pdf, $Sections, $data[0]);
                        $this->get_detail_start_top_margin($Sections, "GroupHeader2");
                        $this->TopMargin_current = $this->TopMargin_current + 0.5;
                    }
                    $this->detail_is_drowed = true;
                    $this->get_detail_start_top_margin($Sections, "Detail");
                    $this->TopMargin_current = $this->TopMargin_current - 31;
                    $this->draw_except_detail($pdf, $Sections, $data[$cnt - 1]);
                    $this->detail_is_drowed = false;
                    break;
                //fan add e.
                //20170803 lqs INS S
                case "13":
                    //20150815 lqs INS S
                    $this->pageNumber = 0;
                    //20150815 lqs INS E
                    $cnt = count($data);
                    $i = 0;
                    if ($this->rpx_file_name == "rptCardMeisaiNyukinIchiran" || $this->rpx_file_name == "rptCardMeisaiFurikaeIchiran") {
                        $BANK_HD = $data[0]['BANK_NM_HD'];
                        $j = 0;
                    }
                    if ($this->rpx_file_name == "rptSonotaMeisaiIchiran") {
                        $BANK_HD = $data[0]['BANK_HD'];
                        $j = 0;
                    }
                    $this->first = true;
                    while ($i < $cnt) {

                        if ($this->rpx_file_name == "rptCardMeisaiNyukinIchiran" || $this->rpx_file_name == "rptCardMeisaiFurikaeIchiran") {
                            if ($data[$i]['BANK_NM_HD'] == $BANK_HD) {
                                if (($j % 8) == 0) {
                                    $this->draw_except_detail($pdf, $Sections, $data[$i]);
                                    $this->get_detail_start_top_margin($Sections, "GroupHeader3");
                                    $this->TopMargin_current = $this->TopMargin_current + 0.5;
                                }
                                $data[$i] = array_merge($this->data_fields, $data[$i]);
                                $this->draw_detail($pdf, $Sections, $data[$i], $data[$i]);
                                $i++;
                                $j++;
                                $this->first = false;
                            } else {
                                $this->first = true;
                                $this->detail_is_drowed = true;
                                $this->get_detail_start_top_margin($Sections, "Detail");
                                $this->TopMargin_current = $this->TopMargin_current - 31;
                                $this->draw_except_detail($pdf, $Sections, $data[$i - 1]);
                                $this->detail_is_drowed = false;
                                $BANK_HD = $data[$i]['BANK_NM_HD'];
                                $j = 0;
                                $this->draw_except_detail($pdf, $Sections, $data[$i]);
                                $this->get_detail_start_top_margin($Sections, "GroupHeader3");
                                $this->TopMargin_current = $this->TopMargin_current + 0.5;
                                $data[$i] = array_merge($this->data_fields, $data[$i]);
                                $this->draw_detail($pdf, $Sections, $data[$i], $data[$i]);
                                $i++;
                                $j++;
                            }
                        }
                        if ($this->rpx_file_name == "rptSonotaMeisaiIchiran") {
                            if ($data[$i]['BANK_HD'] == $BANK_HD) {

                                if (($j % 8) == 0) {
                                    $this->draw_except_detail($pdf, $Sections, $data[$i]);
                                    $this->get_detail_start_top_margin($Sections, "GroupHeader3");
                                    $this->TopMargin_current = $this->TopMargin_current + 0.5;
                                }
                                $data[$i] = array_merge($this->data_fields, $data[$i]);
                                $this->draw_detail($pdf, $Sections, $data[$i], $data[$i]);
                                $i++;
                                $j++;
                                $this->first = false;
                            } else {
                                $this->first = true;
                                $this->detail_is_drowed = true;
                                $this->get_detail_start_top_margin($Sections, "Detail");
                                $this->TopMargin_current = $this->TopMargin_current - 31;
                                $this->draw_except_detail($pdf, $Sections, $data[$i - 1]);
                                $this->detail_is_drowed = false;
                                $BANK_HD = $data[$i]['BANK_HD'];
                                $j = 0;
                                $this->draw_except_detail($pdf, $Sections, $data[$i]);
                                $this->get_detail_start_top_margin($Sections, "GroupHeader3");
                                $this->TopMargin_current = $this->TopMargin_current + 0.5;
                                $data[$i] = array_merge($this->data_fields, $data[$i]);
                                $this->draw_detail($pdf, $Sections, $data[$i], $data[$i]);
                                $i++;
                                $j++;
                            }
                        }
                    }
                    $this->detail_is_drowed = true;
                    $this->get_detail_start_top_margin($Sections, "Detail");
                    $this->TopMargin_current = $this->TopMargin_current - 31;
                    $this->draw_except_detail($pdf, $Sections, $data[$cnt - 1]);
                    $this->detail_is_drowed = false;
                    break;
                //20170803 lqs INS E
                //20170808 lqs INS S
                case "14":
                    $a = 0;
                    $b = 0;
                    $c = 0;
                    //20150815 lqs INS S
                    $this->pageNumber = 0;
                    //20150815 lqs INS E
                    $rptUriageMeisaiIchiranData = array();
                    $rptUriMeisaiData = array();
                    $rptUriSyohiyoData = array();
                    $rptUriPackData = array();
                    $footerData = array();

                    $this->TopMargin_current = 0;
                    $this->TopMargin_current = $this->TopMargin_current + $this->TopMargin;
                    $pdf->AddPage($this->Orientation);
                    $this->pageNumber++;
                    foreach ($data as $key => $value) {
                        if ($key == "rptUriageMeisaiIchiran") {
                            array_push($rptUriageMeisaiIchiranData, $value[0]);
                        }
                        if ($key == "rptUriMeisai") {
                            $cntUriMeisai = count($value);
                            while ($a < $cntUriMeisai) {
                                array_push($rptUriMeisaiData, $value[$a]);
                                $a++;
                            }

                        }
                        if ($key == "rptUriSyohiyo") {
                            $cntUriSyohiyo = count($value);
                            while ($b < $cntUriSyohiyo) {
                                array_push($rptUriSyohiyoData, $value[$b]);
                                $b++;
                            }
                        }
                        if ($key == "rptUriPack") {
                            $cntUriPack = count($value);
                            while ($c < $cntUriPack) {
                                array_push($rptUriPackData, $value[$c]);
                                $c++;
                            }
                        }
                    }
                    if (count($rptUriageMeisaiIchiranData) == 0) {
                        break;
                    }
                    $i = 1;
                    $j = 0;
                    $x = 0;
                    $SEB_NOU_NO_KEY = "";
                    foreach ($Sections->children() as $Section) {
                        $attr_Section = $Section->attributes();
                        if ($attr_Section->Name == "PageHeader") {
                            $pageHeaderSection = $Section;
                        }
                        if ($attr_Section->Name == "GroupHeader1") {
                            $header1Section = $Section;
                        }

                        if ($attr_Section->Name == "GroupHeader2") {
                            $header2Section = $Section;
                        }
                        if ($attr_Section->Name == "Detail1") {
                            $detail1Section = $Section;
                        }
                        if ($attr_Section->Name == "GroupHeader4") {
                            $header4Section = $Section;
                        }
                        if ($attr_Section->Name == "GroupHeader6") {
                            $header6Section = $Section;
                        }
                        if ($attr_Section->Name == "PageFooter") {
                            $pageFooterSection = $Section;
                        }

                    }
                    foreach ($Sections->children() as $Section) {
                        $attr_Section = $Section->attributes();
                        if ($attr_Section->Name == "PageHeader") {
                            $this->draw_control($pdf, $Section, $rptUriageMeisaiIchiranData[0]);
                        }
                        if ($attr_Section->Name == "GroupHeader1" && count($rptUriMeisaiData) != 0) {
                            $this->draw_control($pdf, $Section, $rptUriMeisaiData[0]);
                        }

                        if ($attr_Section->Name == "GroupHeader2" && count($rptUriMeisaiData) != 0) {
                            $this->draw_control($pdf, $Section, $rptUriMeisaiData[0]);
                            $SEB_NOU_NO_KEY = $rptUriMeisaiData[0]['SEB_NOU_NO_KEY'];
                        }
                        if ($attr_Section->Name == "Detail1" && count($rptUriMeisaiData) != 0) {
                            $this->draw_control($pdf, $Section, $rptUriMeisaiData[0]);
                            while ($i < count($rptUriMeisaiData)) {
                                if ($SEB_NOU_NO_KEY == $rptUriMeisaiData[$i]['SEB_NOU_NO_KEY']) {
                                    $this->draw_control($pdf, $detail1Section, $rptUriMeisaiData[$i]);
                                } else {
                                    $this->draw_control($pdf, $header2Section, $rptUriMeisaiData[$i]);
                                    $this->draw_control($pdf, $detail1Section, $rptUriMeisaiData[$i]);
                                    $SEB_NOU_NO_KEY = $rptUriMeisaiData[$i]['SEB_NOU_NO_KEY'];
                                }
                                $i++;
                                if ($this->addNewPage($pdf, $pageFooterSection, 22.8, 8)) {
                                    $this->draw_control($pdf, $pageHeaderSection, $rptUriageMeisaiIchiranData[0]);
                                    $this->draw_control($pdf, $header1Section, $rptUriMeisaiData[0]);
                                }
                            }
                        }
                        if ($attr_Section->Name == "GroupFooter1" && count($rptUriMeisaiData) != 0) {
                            $this->draw_control($pdf, $Section, $rptUriMeisaiData[0]);
                            if (count($rptUriMeisaiData) <> 0 || count($rptUriPackData) <> 0) {
                                if ($this->addNewPage($pdf, $pageFooterSection, 22.8, 8)) {
                                    $this->draw_control($pdf, $pageHeaderSection, $rptUriageMeisaiIchiranData[0]);
                                }
                            }
                        }
                        if ($attr_Section->Name == "GroupHeader3" && count($rptUriSyohiyoData) != 0) {
                            $this->draw_control($pdf, $Section, $rptUriSyohiyoData[0]);
                            if ($this->addNewPage($pdf, $pageFooterSection, 22.8, 10)) {
                                $this->draw_control($pdf, $pageHeaderSection, $rptUriageMeisaiIchiranData[0]);
                            }
                        }
                        if ($attr_Section->Name == "GroupHeader4" && count($rptUriSyohiyoData) != 0) {
                            $this->draw_control($pdf, $Section, $rptUriSyohiyoData[0]);
                            if ($this->addNewPage($pdf, $pageFooterSection, 22.8, 8)) {
                                $this->draw_control($pdf, $pageHeaderSection, $rptUriageMeisaiIchiranData[0]);
                                $this->draw_control($pdf, $Section, $rptUriSyohiyoData[0]);
                            }
                        }
                        if ($attr_Section->Name == "Detail2" && count($rptUriSyohiyoData) != 0) {
                            while ($j < count($rptUriSyohiyoData)) {
                                $this->draw_control($pdf, $Section, $rptUriSyohiyoData[$j]);
                                $j++;
                                if ($this->addNewPage($pdf, $pageFooterSection, 22.8, 8)) {
                                    $this->draw_control($pdf, $pageHeaderSection, $rptUriageMeisaiIchiranData[0]);
                                    $this->draw_control($pdf, $header4Section, $rptUriSyohiyoData[0]);
                                }
                            }
                        }
                        if ($attr_Section->Name == "GroupFooter2" && count($rptUriSyohiyoData) != 0) {
                            $this->draw_control($pdf, $Section, $rptUriSyohiyoData[0]);
                            if (count($rptUriPackData) <> 0) {
                                if ($this->addNewPage($pdf, $pageFooterSection, 22.8, 8)) {
                                    $this->draw_control($pdf, $pageHeaderSection, $rptUriageMeisaiIchiranData[0]);
                                }
                            }

                        }
                        if ($attr_Section->Name == "GroupHeader5" && count($rptUriPackData) != 0) {
                            $this->draw_control($pdf, $Section, $rptUriPackData[0]);
                            if ($this->addNewPage($pdf, $pageFooterSection, 22.8, 8)) {
                                $this->draw_control($pdf, $pageHeaderSection, $rptUriageMeisaiIchiranData[0]);
                            }
                        }

                        if ($attr_Section->Name == "GroupHeader6" && count($rptUriPackData) != 0) {
                            $this->draw_control($pdf, $Section, $rptUriPackData[0]);
                            if ($this->addNewPage($pdf, $pageFooterSection, 22.8, 8)) {
                                $this->draw_control($pdf, $pageHeaderSection, $rptUriageMeisaiIchiranData[0]);
                                $this->draw_control($pdf, $Section, $rptUriPackData[0]);
                            }
                        }
                        if ($attr_Section->Name == "Detail3" && count($rptUriPackData) != 0) {
                            while ($x < count($rptUriPackData)) {
                                $this->draw_control($pdf, $Section, $rptUriPackData[$x]);
                                $x++;
                                if ($this->addNewPage($pdf, $pageFooterSection, 22.8, 8)) {
                                    $this->draw_control($pdf, $pageHeaderSection, $rptUriageMeisaiIchiranData[0]);
                                    $this->draw_control($pdf, $header6Section, $rptUriPackData[0]);
                                }
                            }
                        }
                        if ($attr_Section->Name == "GroupFooter3" && count($rptUriPackData) != 0) {
                            $this->draw_control($pdf, $Section, $rptUriPackData[0]);
                        }
                        if ($attr_Section->Name == "PageFooter") {
                            $footerData['PAGE'] = $this->pageNumber;
                            $this->TopMargin_current = $pdf->getPageHeight() - $this->BottomMargin - 8;
                            $this->draw_control($pdf, $Section, $footerData);
                        }
                    }

                    break;
                //20170808 lqs INS E
                //20170809 YIN INS S
                case '15':
                    //20150815 lqs INS S
                    $this->pageNumber = 0;
                    //20150815 lqs INS E
                    $this->rpx_file_name_son = 'rptSeibinippoMaine';
                    $this->rptSeibinippoMaineData = $data['rptSeibinippoMaine'];
                    $this->draw_except_detail($pdf, $Sections, $data['rptSeibinippoMaine'][0]);

                    $this->get_detail_start_top_margin($Sections, "GroupHeader1");

                    //有償売上分
                    if (isset($data['rptSeibiYusho']) && count($data['rptSeibiYusho']) > 0) {
                        $this->detail_is_drowed = true;
                        $this->rpx_file_name_son = 'rptSeibiYusho';
                        $this->GroupHeader1 = true;
                        $this->GroupHeader2 = true;
                        $this->GroupFooter1 = false;
                        $this->GroupFooter2 = false;
                        $this->draw_except_detail($pdf, $Sections, $data['rptSeibiYusho'][0]);
                        $this->GroupHeader1 = false;
                        $this->GroupHeader2 = false;
                        $this->get_detail_start_top_margin($Sections, "Detail1");

                        $RESULTKBN = $data['rptSeibiYusho'][0]['RESULTKBN'];

                        foreach ($data['rptSeibiYusho'] as $key => $value) {
                            if ($value['RESULTKBN'] != $RESULTKBN) {
                                $this->GroupFooter1 = true;

                                $pdfheitgh = 558 * 0.01763888888889;
                                if (($pdfheitgh + $this->TopMargin_current) > ($pdf->getPageHeight() - 15)) {
                                    $this->detail_is_drowed = false;
                                    $this->draw_except_detail($pdf, $Sections, $data['rptSeibinippoMaine'][0]);
                                    $this->get_detail_start_top_margin($Sections, "GroupHeader1");
                                    $this->detail_is_drowed = true;
                                }

                                $data_value = array_merge($this->data_fields['rptSeibiYusho'], $data['rptSeibiYusho'][$key - 1]);
                                $this->draw_except_detail($pdf, $Sections, $data_value);

                                $RESULTKBN = $value['RESULTKBN'];

                                $value = array_merge($this->data_fields['rptSeibiYusho'], $value);
                                $this->draw_detail($pdf, $Sections, $value, $value);

                            } else {
                                $value = array_merge($this->data_fields['rptSeibiYusho'], $value);
                                $this->draw_detail($pdf, $Sections, $value, $value);
                            }

                        }
                        $this->GroupFooter1 = true;

                        $pdfheitgh = 558 * 0.01763888888889;
                        if (($pdfheitgh + $this->TopMargin_current) > ($pdf->getPageHeight() - 15)) {
                            $this->detail_is_drowed = false;
                            $this->draw_except_detail($pdf, $Sections, $data['rptSeibinippoMaine'][0]);
                            $this->get_detail_start_top_margin($Sections, "GroupHeader1");
                            $this->detail_is_drowed = true;
                        }

                        $data_value = array_merge($this->data_fields['rptSeibiYusho'], $data['rptSeibiYusho'][$key]);
                        $this->draw_except_detail($pdf, $Sections, $data_value);

                        $this->GroupFooter1 = false;
                        $this->GroupFooter2 = true;

                        $pdfheitgh = 1091 * 0.01763888888889;
                        if (($pdfheitgh + $this->TopMargin_current) > ($pdf->getPageHeight() - 15)) {
                            $this->detail_is_drowed = false;
                            $this->draw_except_detail($pdf, $Sections, $data['rptSeibinippoMaine'][0]);
                            $this->get_detail_start_top_margin($Sections, "GroupHeader1");
                            $this->detail_is_drowed = true;
                        }

                        $data_value = array_merge($this->data_fields['rptSeibiYusho'], $data['rptSeibiYusho'][$key]);
                        $this->draw_except_detail($pdf, $Sections, $data_value);

                        $this->GroupFooter2 = false;
                        $this->detail_is_drowed = false;

                        $this->TopMargin_current = $this->TopMargin_current + 3;

                    }

                    //無償売上分
                    if (isset($data['rptSeibiMusho']) && count($data['rptSeibiMusho']) > 0) {
                        $pdfheitgh = (432 + 285 + (285 * count($data['rptSeibiMusho'])) + 285) * 0.01763888888889;
                        if (($pdfheitgh + $this->TopMargin_current) > ($pdf->getPageHeight() - 15)) {
                            $this->draw_except_detail($pdf, $Sections, $data['rptSeibinippoMaine'][0]);
                            $this->get_detail_start_top_margin($Sections, "GroupHeader1");
                        }
                        $this->detail_is_drowed = true;
                        $this->rpx_file_name_son = 'rptSeibiMusho';
                        $this->GroupHeader4 = true;
                        $this->GroupHeader5 = true;
                        $this->draw_except_detail($pdf, $Sections, $data['rptSeibiMusho'][0]);
                        $this->GroupHeader4 = false;
                        $this->GroupHeader5 = false;
                        foreach ($data['rptSeibiMusho'] as $key => $value) {
                            $value = array_merge($this->data_fields['rptSeibiYusho'], $value);
                            $this->draw_detail($pdf, $Sections, $value, $value);
                        }
                        $this->GroupFooter3 = true;

                        $data_value = array_merge($this->data_fields['rptSeibiMusho'], $data['rptSeibiMusho'][$key]);
                        $this->draw_except_detail($pdf, $Sections, $data_value);
                        $this->GroupFooter3 = false;
                        $this->detail_is_drowed = false;
                        $this->TopMargin_current = $this->TopMargin_current + 3;

                    }

                    //総計
                    if (isset($data['rptSeibiSokei']) && count($data['rptSeibiSokei']) > 0) {
                        $pdfheitgh = (432 + 285 + (285 * count($data['rptSeibiSokei'])) + 285) * 0.01763888888889;
                        if (($pdfheitgh + $this->TopMargin_current) > ($pdf->getPageHeight() - 15)) {
                            $this->draw_except_detail($pdf, $Sections, $data['rptSeibinippoMaine'][0]);
                            $this->get_detail_start_top_margin($Sections, "GroupHeader1");
                        }
                        $this->detail_is_drowed = true;
                        $this->rpx_file_name_son = 'rptSeibiSokei';
                        $this->GroupHeader6 = true;
                        $this->GroupHeader7 = true;
                        $this->draw_except_detail($pdf, $Sections, $data['rptSeibiSokei'][0]);
                        $this->GroupHeader6 = false;
                        $this->GroupHeader7 = false;
                        foreach ($data['rptSeibiSokei'] as $key => $value) {
                            $value = array_merge($this->data_fields['rptSeibiSokei'], $value);
                            $this->draw_detail($pdf, $Sections, $value, $value);
                        }
                        $this->GroupFooter4 = true;

                        $data_value = array_merge($this->data_fields['rptSeibiSokei'], $data['rptSeibiSokei'][$key]);
                        $this->draw_except_detail($pdf, $Sections, $data_value);
                        $this->GroupFooter4 = false;
                        $this->detail_is_drowed = false;
                        $this->TopMargin_current = $this->TopMargin_current + 3;

                    }

                    //諸費用
                    if (isset($data['rptSeibiSyohiyo']) && count($data['rptSeibiSyohiyo']) > 0) {
                        $pdfheitgh = (432 + 817) * 0.01763888888889;
                        if (($pdfheitgh + $this->TopMargin_current) > ($pdf->getPageHeight() - 15)) {
                            $this->draw_except_detail($pdf, $Sections, $data['rptSeibinippoMaine'][0]);
                            $this->get_detail_start_top_margin($Sections, "GroupHeader1");
                        }
                        $this->detail_is_drowed = true;
                        $this->rpx_file_name_son = 'rptSeibiSyohiyo';
                        $this->GroupHeader8 = true;
                        $this->draw_except_detail($pdf, $Sections, $data['rptSeibiSyohiyo'][0]);
                        $this->GroupHeader8 = false;
                        foreach ($data['rptSeibiSyohiyo'] as $key => $value) {
                            $value = array_merge($this->data_fields['rptSeibiSyohiyo'], $value);
                            $this->draw_detail($pdf, $Sections, $value, $value);
                        }

                        $this->detail_is_drowed = false;
                        $this->TopMargin_current = $this->TopMargin_current + 3;

                    }

                    //前受金
                    if (isset($data['rptSeibiMaeuke']) && count($data['rptSeibiMaeuke']) > 0) {
                        $pdfheitgh = (432 + 645 + (285 * count($data['rptSeibiMaeuke'])) + 285) * 0.01763888888889;
                        if (($pdfheitgh + $this->TopMargin_current) > ($pdf->getPageHeight() - 15)) {
                            $this->draw_except_detail($pdf, $Sections, $data['rptSeibinippoMaine'][0]);
                            $this->get_detail_start_top_margin($Sections, "GroupHeader1");
                        }
                        $this->detail_is_drowed = true;
                        $this->rpx_file_name_son = 'rptSeibiMaeuke';
                        $this->GroupHeader9 = true;
                        $this->GroupHeader10 = true;
                        $this->draw_except_detail($pdf, $Sections, $data['rptSeibiMaeuke'][0]);
                        $this->GroupHeader9 = false;
                        $this->GroupHeader10 = false;
                        foreach ($data['rptSeibiMaeuke'] as $key => $value) {
                            $value = array_merge($this->data_fields['rptSeibiMaeuke'], $value);
                            $this->draw_detail($pdf, $Sections, $value, $value);
                        }
                        $this->GroupFooter5 = true;

                        $data_value = array_merge($this->data_fields['rptSeibiMaeuke'], $data['rptSeibiMaeuke'][$key]);
                        $this->draw_except_detail($pdf, $Sections, $data_value);
                        $this->GroupFooter5 = false;
                        $this->detail_is_drowed = false;
                        $this->TopMargin_current = $this->TopMargin_current + 3;

                    }

                    break;
                //20170809 YIN INS E

                default:
                    break;
            }
        } else {
            exit('Error.');
        }
        ;

    }

    //20170810 lqs INS S
    public function addNewPage($pdf, $pageFooterSection, $x, $y)
    {
        $addPage = false;
        $tmp_TopMargin_current = $this->TopMargin_current + $this->BottomMargin;
        $tmp_TopMargin_current = $tmp_TopMargin_current + $x;
        if ($tmp_TopMargin_current > $pdf->getPageHeight()) {
            $addPage = true;
            $footerData['PAGE'] = $this->pageNumber;
            $this->TopMargin_current = $pdf->getPageHeight() - $this->BottomMargin - $y;
            $this->draw_control($pdf, $pageFooterSection, $footerData);
            $this->TopMargin_current = 0;
            $this->TopMargin_current = $this->TopMargin_current + $this->TopMargin;
            $pdf->AddPage($this->Orientation);
            $this->pageNumber++;
        }
        return $addPage;
    }

    //20170810 lqs INS E
    /**
     * draw_except_detail
     * add new page
     * @return void
     * @author
     */
    public function draw_except_detail($pdf, $Sections, $data = null, $PageSettings = "", $tmpSections = '', $tmpData_0 = '')
    {
        if ($this->detail_is_drowed == TRUE) {
            //yushuangji add start
            //mode 4 for 固定資産社内リース料印刷
            if ($this->mode == "4") {

                switch ($this->tmpCaseForMode4) {
                    case "1":
                        $tmpMargin = 260;
                        break;
                    case "2":
                        $tmpMargin = 270;
                        break;
                    case "3":
                        $tmpMargin = 270;
                        break;
                }

                if ($this->TopMargin_current > $tmpMargin) {
                    $pdf->AddPage($this->Orientation);
                    $this->pageNumber++;
                    $tmpData_0['PAGE'] = $this->pageNumber;
                    $this->TopMargin_current = 10;
                    foreach ($tmpSections->children() as $tmpSections) {
                        $attr_Section = $tmpSections->attributes();

                        if ($attr_Section->Name != "Detail") {
                            if ($this->detail_is_drowed) {
                                /*
                                 * mode 4 for 固定資産社内リース料印刷
                                 * group header
                                 *
                                 */

                                if ($this->mode == "4" && $attr_Section->Name != "GroupFooter1") {

                                    $this->draw_control($pdf, $tmpSections, $tmpData_0);

                                }
                            }
                        }
                    }
                    $this->TopMargin_current += 5;
                }
            }
            //mode 3 for 新車車種別粗利益表,或者 带有Groupheader、detail、Groupfooter的账票
            if ($this->mode == "3") {
                if ($this->ActiveReportsLayout['CodeFile']) {
                    $tmpCodeFile = substr($this->ActiveReportsLayout['CodeFile'], 0, strlen($this->ActiveReportsLayout['CodeFile']) - 3);

                    switch ($tmpCodeFile) {
                        case "rptArariekiHyo":
                            if ($this->TopMargin_current > 280) {
                                $pdf->AddPage($this->Orientation);
                                $this->pageNumber++;
                                $tmpData_0['PAGE'] = $this->pageNumber;
                                $this->TopMargin_current = 10;
                            }
                            break;
                        case "rptArariekiChkList":
                        case "rptJinkenInUnmachi":
                            //20150916  Yuanjh   DEL S.
                            //$pdf -> AddPage($this -> Orientation);
                            //20150916  Yuanjh   DEL E.
                            $this->pageNumber++;
                            $tmpData_0['PAGE'] = $this->pageNumber;
                            $this->TopMargin_current = 10;
                    }
                }
            }
            //yushuangji add end
            //fan add s.
            if ($this->mode == "5" && $this->temppagemark == true) {
                $this->TopMargin_current = 0;
                $this->TopMargin_current = $this->TopMargin_current + $this->TopMargin;
                $pdf->AddPage($this->Orientation);
                $this->pageNumber++;
            }
            //fan add e.
        } else {
            $this->TopMargin_current = 0;
            $this->TopMargin_current = $this->TopMargin_current + $this->TopMargin;
            $pdf->AddPage($this->Orientation);
            $this->pageNumber++;
            //20170802 lqs INS S
            if ($this->rpx_file_name == "rptGenkinSuitochoEigyoKinshu") {
                $this->sum1 = $data['ZANDAKA_SHIHEI_SUM'];
                $this->sum2 = $data['ZANDAKA_KOUKA_SUM'];
                $this->sum3 = $data['ZANDAKA_KOGITTE_SUM'];
                $this->sum123 = $data['KON_HJM_EGK_KKS_GK'];
            }
            if ($this->rpx_file_name == "rptGenkinSuitochoEigyo") {
                $this->suitoEigKarikatasum = $data['KARIKATA_SUM'];
                $this->suitoEigKashikatasum = $data['KASHIKATA_SUM'];
                $this->suitoEigInpdenpyno = $data['INP_DENPY_NO_SUM'];
                $this->suitoEigKon = $data['KON_HJM_EGK_KKS_GK'];
            }
            if ($this->rpx_file_name == "rptCardMeisaiNyukinIchiran" || $this->rpx_file_name == "rptCardMeisaiFurikaeIchiran") {
                $this->bankNMFT = $data['BANK_NM_FT'];
            }
            //20170802 lqs INS E
            //----20151106 Yuanjh S.
            if ($this->rpx_file_name == "rptKijyunList" && $this->pageKijyunListflg == FALSE) {
                $this->pageNumber = 1;
                $this->pageKijyunListflg = TRUE;
            }
            if ($this->rpx_file_name == "rptZanErrList" && $this->pageZanErrListflg == FALSE) {
                $this->pageNumber = 1;
                $this->pageZanErrListflg = TRUE;
            }
            if ($this->rpx_file_name == "rptSiwakeErrList" && $this->pageSiwakeErrList == FALSE) {
                $this->pageNumber = 1;
                $this->pageSiwakeErrList = TRUE;
            }
            if ($this->rpx_file_name == "rptKijyunUnmachiListNew" && $this->pageKijyunUnmachiListNew == FALSE) {
                $this->pageNumber = 1;
                $this->pageKijyunUnmachiListNew = TRUE;
            }
            if ($this->rpx_file_name == "rptKijyunUnmachiListOld" && $this->pageKijyunUnmachiListNew == FALSE) {
                $this->pageNumber = 1;
                $this->pageKijyunUnmachiListNew = TRUE;
            }

            //----20151106 Yuanjh  E.
            //fan add s.
            if ($this->rpx_file_name == "rptSyasyuTouroku1" || $this->rpx_file_name == "rptSyasyuUriage1") {
                $this->pageNumber = 1;
            }
            //fan add e.
        }

        foreach ($Sections->children() as $Section) {
            $data['PAGE'] = $this->pageNumber;
            //20170801 lqs INS S
            if ($this->rpx_file_name == "rptGenkinSuitochoEigyoKinshu") {
                $data['ZANDAKA_SHIHEI_SUM'] = $this->sum1;
                $data['ZANDAKA_KOUKA_SUM'] = $this->sum2;
                $data['ZANDAKA_KOGITTE_SUM'] = $this->sum3;
                $data['KON_HJM_EGK_KKS_GK'] = $this->sum123;
            }
            if ($this->rpx_file_name == "rptGenkinSuitochoEigyo") {
                $data['KARIKATA_SUM'] = $this->suitoEigKarikatasum;
                $data['KASHIKATA_SUM'] = $this->suitoEigKashikatasum;
                $data['INP_DENPY_NO_SUM'] = $this->suitoEigInpdenpyno;
                $data['KON_HJM_EGK_KKS_GK'] = $this->suitoEigKon;
            }
            if ($this->rpx_file_name == "rptCardMeisaiNyukinIchiran" || $this->rpx_file_name == "rptCardMeisaiFurikaeIchiran") {
                $data['BANK_NM_FT'] = $this->bankNMFT;
            }
            //20170801 lqs INS E
            //--201111  Yuanjh  ADD  S.
            //---20151230 li UPD S.
            // if($this -> rpx_file_name  = "rptScUriageChk"){
            if ($this->rpx_file_name == "rptScUriageChk") {
                //---20151230 li UPD E.
                $data['TODAY'] = date('Y/m/d', time());
                $data['KIKANF'] = $this->DateFrom;
                $data['KIKANT'] = $this->DateTo;
            }
            //--201111  Yuanjh  ADD  E.

            $attr_Section = $Section->attributes();

            //2014-01-06 qiuqiu update start
            if ($attr_Section->Name != "Detail") {
                if ($this->mode == "0" && $attr_Section->Name == "PageFooter") {
                    $this->TopMargin_current = $this->TopMargin_current + 3.5;
                }
                if (!($this->mode == "4" || $this->mode == "3" || $this->mode == "5" || $this->mode == "6" || $this->mode == "7" || $this->mode == "8" || $this->mode == "9" || $this->mode == "10" || $this->mode == "11" || $this->mode == "12" || $this->mode == "13" || $this->mode == "15")) {
                    $this->draw_control($pdf, $Section, $data);
                }

                //20140320 yushuangji edit start
                if ($this->detail_is_drowed) {
                    if ($this->mode == "3" && $attr_Section->Name == "GroupFooter1") {

                        $this->draw_control($pdf, $Section, $data);

                    }
                    /*
                     * mode 4 for 固定資産社内リース料印刷
                     *
                     */
                    //20140328 yushuangji add start
                    if ($this->mode == "4" && $attr_Section->Name == "GroupFooter1") {
                        $this->TopMargin_current = $this->TopMargin_current - 5;
                        //$this -> TopMargin_current = $this -> TopMargin_current;
                        $this->draw_control($pdf, $Section, $data);
                    }
                    //20140328 yushuangji add end
                    //fan add s.
                    if ($this->mode == "5" && $attr_Section->Name == "GroupFooter2" && $this->tempmark == false && $this->temppagemark == false) {
                        $this->TopMargin_current = $this->TopMargin_current - 5;
                        //20170801 lqs INS S
                        if ($this->rpx_file_name == "rptGenkinSuitochoEigyoKinshu") {
                            $this->TopMargin_current = $this->TopMargin_current - 1;
                        }
                        //20170801 lqs INS E
                        $tmp_TopMargin_current = $this->TopMargin_current + $this->BottomMargin;
                        $tmp_TopMargin_current = $tmp_TopMargin_current + $this->twip_to_millimeter($attr_Section->Height);
                        if ($tmp_TopMargin_current > $pdf->getPageHeight()) {
                            $this->temppagemark = true;
                            $this->draw_except_detail($pdf, $Sections, $data);
                        }
                        $this->temppagemark = false;
                        $this->draw_control($pdf, $Section, $data);

                    }
                    if ($this->mode == "5" && $attr_Section->Name == "GroupFooter1" && $this->tempmark == true && $this->temppagemark == false) {
                        $tmp_TopMargin_current = $this->TopMargin_current + $this->BottomMargin;
                        $tmp_TopMargin_current = $tmp_TopMargin_current + $this->twip_to_millimeter($attr_Section->Height);
                        if ($tmp_TopMargin_current > $pdf->getPageHeight()) {
                            $this->temppagemark = true;
                            $this->draw_except_detail($pdf, $Sections, $data);
                        }
                        $this->temppagemark = false;
                        //20170801 lqs INS S
                        if ($this->rpx_file_name == "rptGenkinSuitochoEigyoKinshu") {
                            $this->TopMargin_current = $pdf->getPageHeight() - 40;
                        }
                        //20170801 lqs INS E
                        $this->draw_control($pdf, $Section, $data);
                    }
                    //20170801 lqs INS S
                    if ($this->rpx_file_name == "rptGenkinSuitochoEigyoKinshu" && $this->mode == "5" && $attr_Section->Name == "PageFooter" && $this->tempmark == true && $this->temppagemark == false) {
                        $this->draw_control($pdf, $Section, $data);
                    }
                    //20170801 lqs INS E
                    if ($this->mode == "5" && $attr_Section->Name == "PageHeader" && $this->temppagemark == true) {
                        $this->draw_control($pdf, $Section, $data);
                    }
                    if ($this->mode == "6" && $attr_Section->Name == "GroupFooter1") {
                        $this->draw_control($pdf, $Section, $data);
                    }
                    if ($this->mode == "7" && $attr_Section->Name == "GroupFooter1") {
                        $this->TopMargin_current = $this->TopMargin_current - 5;
                        $this->draw_control($pdf, $Section, $data);
                    }
                    if ($this->mode == "8" && $attr_Section->Name == "GroupFooter2") {
                        $this->draw_control($pdf, $Section, $data);
                    }
                    if ($this->mode == "10" && $attr_Section->Name == $this->GroupFlag) {
                        $tmp_TopMargin_current = $this->TopMargin_current + $this->BottomMargin;
                        $tmp_TopMargin_current = $tmp_TopMargin_current + $this->twip_to_millimeter($attr_Section->Height);
                        if ($tmp_TopMargin_current > $pdf->getPageHeight()) {
                            $this->detail_is_drowed = false;
                            if ($attr_Section->Name == "GroupFooter6" || $attr_Section->Name == "GroupFooter7" || $attr_Section->Name == "GroupFooter1") {
                                $this->tempmark = true;
                            }
                            $this->draw_except_detail($pdf, $Sections, $data);
                            $this->tempmark = false;
                        }
                        if ($attr_Section->Name != "GroupHeader2" || $this->detail_is_drowed == true) {
                            $this->draw_control($pdf, $Section, $data);
                        }
                        if ($attr_Section->Name == "GroupHeader2") {
                            $this->TopMargin_current = $this->TopMargin_current - 5.5;
                        }
                    }
                    if ($this->mode == "11" && $attr_Section->Name == $this->GroupFlag) {
                        $tmp_TopMargin_current = $this->TopMargin_current + $this->BottomMargin;
                        $tmp_TopMargin_current = $tmp_TopMargin_current + $this->twip_to_millimeter($attr_Section->Height);
                        if ($tmp_TopMargin_current > $pdf->getPageHeight()) {
                            $this->detail_is_drowed = false;
                            $this->draw_except_detail($pdf, $Sections, $data);
                            $this->TopMargin_current = $this->TopMargin_current - 5;
                            $this->detail_is_drowed = true;
                        }
                        $this->draw_control($pdf, $Section, $data);
                    }
                    if ($this->mode == "12" && $attr_Section->Name == "GroupFooter2") {
                        $this->draw_control($pdf, $Section, $data);
                    }
                    //20170803 lqs INS S
                    if ($this->mode == "13" && $attr_Section->Name == "GroupFooter2") {
                        $this->draw_control($pdf, $Section, $data);
                    }
                    //20170803 lqs INS E
                    //fan add e.
                    //20170809 YIN INS S
                    if ($this->mode == "15" && $this->rpx_file_name_son == "rptSeibiYusho" && ($attr_Section->Name == "GroupHeader1" || $attr_Section->Name == "GroupHeader2")) {
                        if ($this->GroupHeader1 && $this->GroupHeader2) {
                            $this->draw_control($pdf, $Section, $data);
                        }
                    }
                    if ($this->mode == "15" && $this->rpx_file_name_son == "rptSeibiYusho" && ($attr_Section->Name == "GroupFooter1")) {
                        if ($this->GroupFooter1) {
                            $this->draw_control($pdf, $Section, $data);
                        }
                    }
                    if ($this->mode == "15" && $this->rpx_file_name_son == "rptSeibiYusho" && ($attr_Section->Name == "GroupFooter2")) {
                        if ($this->GroupFooter2) {
                            $this->draw_control($pdf, $Section, $data);
                        }
                    }
                    if ($this->mode == "15" && $this->rpx_file_name_son == "rptSeibiMusho" && ($attr_Section->Name == "GroupHeader4" || $attr_Section->Name == "GroupHeader5")) {
                        if ($this->GroupHeader4 && $this->GroupHeader5) {
                            $this->draw_control($pdf, $Section, $data);
                        }
                    }
                    if ($this->mode == "15" && $this->rpx_file_name_son == "rptSeibiMusho" && ($attr_Section->Name == "GroupFooter3")) {
                        if ($this->GroupFooter3) {
                            $this->draw_control($pdf, $Section, $data);
                        }
                    }
                    if ($this->mode == "15" && $this->rpx_file_name_son == "rptSeibiSokei" && ($attr_Section->Name == "GroupHeader6" || $attr_Section->Name == "GroupHeader7")) {
                        if ($this->GroupHeader6 && $this->GroupHeader7) {
                            $this->draw_control($pdf, $Section, $data);
                        }
                    }
                    if ($this->mode == "15" && $this->rpx_file_name_son == "rptSeibiSokei" && ($attr_Section->Name == "GroupFooter4")) {
                        if ($this->GroupFooter4) {
                            $this->draw_control($pdf, $Section, $data);
                        }
                    }
                    if ($this->mode == "15" && $this->rpx_file_name_son == "rptSeibiSyohiyo" && ($attr_Section->Name == "GroupHeader8")) {
                        if ($this->GroupHeader8) {
                            $this->draw_control($pdf, $Section, $data);
                        }
                    }
                    if ($this->mode == "15" && $this->rpx_file_name_son == "rptSeibiMaeuke" && ($attr_Section->Name == "GroupHeader9" || $attr_Section->Name == "GroupHeader10")) {
                        if ($this->GroupHeader9 && $this->GroupHeader10) {
                            $this->draw_control($pdf, $Section, $data);
                        }
                    }
                    if ($this->mode == "15" && $this->rpx_file_name_son == "rptSeibiMaeuke" && ($attr_Section->Name == "GroupFooter5")) {
                        if ($this->GroupFooter5) {
                            $this->draw_control($pdf, $Section, $data);
                        }
                    }
                    //20170809 YIN INS E

                } else {
                    if ($this->mode == "3" && $attr_Section->Name != "GroupFooter1") {

                        $this->draw_control($pdf, $Section, $data);

                    }

                    if ($this->mode == "4" && $attr_Section->Name != "GroupFooter1") {

                        $this->draw_control($pdf, $Section, $data);

                    }
                    //fan add s.
                    if ($this->mode == "5" && ($attr_Section->Name == "PageHeader" || $attr_Section->Name == "GroupHeader1" || $attr_Section->Name == "GroupHeader2")) {

                        $this->draw_control($pdf, $Section, $data);

                    }
                    if ($this->mode == "6" && ($attr_Section->Name == "PageHeader" || $attr_Section->Name == "GroupHeader1")) {
                        $this->draw_control($pdf, $Section, $data);

                    }
                    if ($this->mode == "7" && $attr_Section->Name != "GroupFooter1") {

                        $this->draw_control($pdf, $Section, $data);

                    }
                    if ($this->mode == "8" && $attr_Section->Name != "GroupFooter2") {

                        $this->draw_control($pdf, $Section, $data);

                    }
                    if ($this->mode == "9" && ($attr_Section->Name != "Detail1" && $attr_Section->Name != "Detail2")) {

                        $this->draw_control($pdf, $Section, $data);

                    }
                    if ($this->mode == "10" && $this->tempmark == false && $this->temppagemark == false && ($attr_Section->Name == "PageHeader" || $attr_Section->Name == "GroupHeader1" || $attr_Section->Name == "GroupHeader2")) {
                        $this->draw_control($pdf, $Section, $data);
                        if ($attr_Section->Name == "GroupHeader2") {
                            $this->TopMargin_current = $this->TopMargin_current - 6;
                        }
                    }
                    if ($this->mode == "10" && $this->tempmark == true && ($attr_Section->Name == "PageHeader" || $attr_Section->Name == "GroupHeader1")) {
                        $this->draw_control($pdf, $Section, $data);
                    }
                    if ($this->mode == "10" && $this->temppagemark == true && $attr_Section->Name == "PageHeader") {
                        $this->draw_control($pdf, $Section, $data);
                    }
                    if ($this->mode == "11" && $this->tempmark == false && ($attr_Section->Name == "PageHeader" || $attr_Section->Name == "GroupHeader1")) {
                        $this->draw_control($pdf, $Section, $data);

                    }
                    if ($this->mode == "11" && $this->tempmark == true && $attr_Section->Name == "PageHeader") {

                        $this->draw_control($pdf, $Section, $data);

                    }
                    if ($this->mode == "12" && ($attr_Section->Name == "PageHeader" || $attr_Section->Name == "GroupHeader1" || $attr_Section->Name == "GroupHeader2" || $attr_Section->Name == "GroupHeader3")) {
                        //20170802 lqs INS S
                        if ($this->rpx_file_name == "rptGenkinSuitochoEigyo" && $attr_Section->Name == "GroupHeader2") {
                            if ($this->first) {
                                $this->draw_control($pdf, $Section, $data);
                            } else {
                                $this->TopMargin_current = $this->TopMargin_current + 4.5;
                            }
                        } else {
                            //20170802 lqs INS E
                            $this->draw_control($pdf, $Section, $data);
                        }

                    }
                    //20170802 YIN INS S
                    if ($this->mode == "12" && ($this->rpx_file_name == "rptShiireMeisaiIchiran" || $this->rpx_file_name == "rptFurikaeMeisaiIchiran" || $this->rpx_file_name == "rptGenkinSuitochoEigyo") && ($attr_Section->Name == "PageFooter")) {
                        $this->TopMargin_current = $this->TopMargin_current - 18;

                        $this->draw_control($pdf, $Section, $data);

                    }
                    //20170802 YIN INS E
                    //20170803 lqs INS S
                    if ($this->mode == "13" && ($attr_Section->Name == "PageHeader" || $attr_Section->Name == "GroupHeader1" || $attr_Section->Name == "GroupHeader2" || $attr_Section->Name == "GroupHeader3")) {

                        if ($attr_Section->Name == "GroupHeader2") {
                            if ($this->first) {
                                $this->draw_control($pdf, $Section, $data);
                            } else {
                                $this->TopMargin_current = $this->TopMargin_current + 8;
                            }
                        } else {
                            $this->draw_control($pdf, $Section, $data);
                        }
                    }
                    //20170803 lqs INS E
                    //20170802 lqs INS S
                    //20170803 lqs INS S
                    if ($this->mode == "13" && $attr_Section->Name == "PageFooter") {
                        if ($this->rpx_file_name == "rptCardMeisaiNyukinIchiran" || $this->rpx_file_name == "rptCardMeisaiFurikaeIchiran") {
                            $this->TopMargin_current = $this->TopMargin_current - 22;
                        }
                        if ($this->rpx_file_name == "rptSonotaMeisaiIchiran") {
                            $this->TopMargin_current = $this->TopMargin_current - 18;
                        }

                        $this->draw_control($pdf, $Section, $data);
                    }
                    //20170803 lqs INS E
                    //20170802 lqs INS E
                    //fan add e.
                    //20170809 YIN INS S
                    if ($this->mode == "15" && $attr_Section->Name == "PageHeader") {
                        $this->draw_control($pdf, $Section, $data);
                    }
                    if ($this->mode == "15" && $attr_Section->Name == "PageFooter") {
                        $this->TopMargin_current = $pdf->getPageHeight() - 15;
                        $this->draw_control($pdf, $Section, $data);
                    }
                    //20170809 YIN INS E

                }
                //20140320 yushuangji edit end
            }
            //2014-01-06 qiuqiu update end
            //2013-11-29 zhenghuiyun insert start
            else {
                if ($this->mode != "3") {

                    $tmp_height = $this->twip_to_millimeter($attr_Section->Height);
                    $this->TopMargin_current = $this->TopMargin_current + $tmp_height;

                }
            }
            //2013-11-29 zhenghuiyun insert end
        }
    }

    /**
     * draw_detail
     *
     * @return void
     * @author
     */

    //2013-12-02 qiuqiu update start

    public function draw_detail($pdf, $Sections, $except_detail_data, $data)
    {
        if (isset($data['Circle']) && isset($data['Text'])) {
            //---20170728 li INS S.
            $Circle = $data['Circle'];
            $Text = $data['Text'];
            if (count($Circle) && count($Text)) {
                foreach ($Circle['X'] as $key => $value) {
                    if (isset($Text[$key])) {
                        $this->circle($pdf, $Circle['X'][$key], $Circle['Y'], $Circle['R'], $Text[$key]);
                    }
                }
            }
            //---20170728 li INS E.
        }
        foreach ($Sections->children() as $Section) {
            $attr_Section = $Section->attributes();

            if ($attr_Section->Name == "Detail") {
                if ($this->mode != "0") {
                    // $tmp_TopMargin_current = $this -> TopMargin_current + $this -> BottomMargin;
                    // $tmp_TopMargin_current = $tmp_TopMargin_current + $this -> twip_to_millimeter($attr_Section -> Height);
                    // if ($tmp_TopMargin_current > $pdf -> getPageHeight())
                    // {
                    // $this -> draw_except_detail($pdf, $Sections, $data);
                    // }

                    if ($this->mode == "2") {
                        $tmp_TopMargin_current = $this->TopMargin_current + $this->twip_to_millimeter($attr_Section->Height);

                        if ($tmp_TopMargin_current > $this->TopMargin_current_GroupHeader2 + $this->twip_to_millimeter($attr_Section->Height) * 27) {
                            $this->draw_except_detail($pdf, $Sections, $except_detail_data);

                            // $this -> TopMargin_current = 0;
                            // $this -> TopMargin_current = $this -> TopMargin_current + $this -> TopMargin;
                            //
                            // foreach ($Sections->children() as $Section1)
                            // {
                            // $attr_Section1 = $Section1 -> attributes();
                            //
                            // if ($attr_Section1 -> Name == "GroupHeader1")
                            // {
                            // break;
                            // }
                            //
                            // $tmp_height = $this -> twip_to_millimeter($attr_Section1 -> Height);
                            // $this -> TopMargin_current = $this -> TopMargin_current + $tmp_height;
                            // }
                            $this->get_detail_start_top_margin($Sections, "GroupHeader1");
                        }
                    } else {
                        $tmp_TopMargin_current = $this->TopMargin_current + $this->BottomMargin;
                        $tmp_TopMargin_current = $tmp_TopMargin_current + $this->twip_to_millimeter($attr_Section->Height);
                        if ($tmp_TopMargin_current > $pdf->getPageHeight()) {
                            $this->draw_except_detail($pdf, $Sections, $data);
                            if ($this->rpx_file_name == "rptZandakaMeisai") {
                                $this->TopMargin_current = $this->TopMargin_current - 5;
                            }
                        }
                    }

                }

                $this->draw_control($pdf, $Section, $data);
            }
            //20170809 YIN INS S
            if ($attr_Section->Name == "Detail1") {
                if ($this->rpx_file_name_son == "rptSeibiYusho") {

                    $tmp_TopMargin_current = $this->TopMargin_current + $this->BottomMargin;
                    $tmp_TopMargin_current = $tmp_TopMargin_current + $this->twip_to_millimeter($attr_Section->Height);
                    if ($tmp_TopMargin_current > $pdf->getPageHeight() - 10) {
                        $this->detail_is_drowed = false;
                        $this->draw_except_detail($pdf, $Sections, $this->rptSeibinippoMaineData[0]);
                        $this->get_detail_start_top_margin($Sections, "GroupHeader1");
                        $this->detail_is_drowed = true;
                    }

                    $this->draw_control($pdf, $Section, $data);
                }
            }
            if ($attr_Section->Name == "Detail2") {
                if ($this->rpx_file_name_son == "rptSeibiMusho") {

                    $tmp_TopMargin_current = $this->TopMargin_current + $this->BottomMargin;
                    $tmp_TopMargin_current = $tmp_TopMargin_current + $this->twip_to_millimeter($attr_Section->Height);
                    if ($tmp_TopMargin_current > $pdf->getPageHeight() - 10) {
                        $this->detail_is_drowed = false;
                        $this->draw_except_detail($pdf, $Sections, $this->rptSeibinippoMaineData[0]);
                        $this->get_detail_start_top_margin($Sections, "GroupHeader1");
                        $this->detail_is_drowed = true;
                    }

                    $this->draw_control($pdf, $Section, $data);
                }
            }
            if ($attr_Section->Name == "Detail3") {
                if ($this->rpx_file_name_son == "rptSeibiSokei") {
                    $tmp_TopMargin_current = $this->TopMargin_current + $this->BottomMargin;
                    $tmp_TopMargin_current = $tmp_TopMargin_current + $this->twip_to_millimeter($attr_Section->Height);
                    if ($tmp_TopMargin_current > $pdf->getPageHeight() - 10) {
                        $this->detail_is_drowed = false;
                        $this->draw_except_detail($pdf, $Sections, $this->rptSeibinippoMaineData[0]);
                        $this->get_detail_start_top_margin($Sections, "GroupHeader1");
                        $this->detail_is_drowed = true;
                    }

                    $this->draw_control($pdf, $Section, $data);
                }
            }
            if ($attr_Section->Name == "Detail4") {
                if ($this->rpx_file_name_son == "rptSeibiSyohiyo") {
                    $tmp_TopMargin_current = $this->TopMargin_current + $this->BottomMargin;
                    $tmp_TopMargin_current = $tmp_TopMargin_current + $this->twip_to_millimeter($attr_Section->Height);
                    if ($tmp_TopMargin_current > $pdf->getPageHeight() - 10) {
                        $this->detail_is_drowed = false;
                        $this->draw_except_detail($pdf, $Sections, $this->rptSeibinippoMaineData[0]);
                        $this->get_detail_start_top_margin($Sections, "GroupHeader1");
                        $this->detail_is_drowed = true;
                    }

                    $this->draw_control($pdf, $Section, $data);
                }
            }
            if ($attr_Section->Name == "Detail5") {
                if ($this->rpx_file_name_son == "rptSeibiMaeuke") {
                    $tmp_TopMargin_current = $this->TopMargin_current + $this->BottomMargin;
                    $tmp_TopMargin_current = $tmp_TopMargin_current + $this->twip_to_millimeter($attr_Section->Height);
                    if ($tmp_TopMargin_current > $pdf->getPageHeight()) {
                        $this->detail_is_drowed = false;
                        $this->draw_except_detail($pdf, $Sections, $this->rptSeibinippoMaineData[0]);
                        $this->get_detail_start_top_margin($Sections, "GroupHeader1");
                        $this->detail_is_drowed = true;
                    }

                    $this->draw_control($pdf, $Section, $data);
                }
            }

            //20170809 YIN INS E
        }
    }

    public function draw_detail1($pdf, $Sections, $data)
    {
        //---20170728 li INS S.
        $Circle = $data['Circle'];
        $Text = $data['Text'];
        if (count($Circle)) {
            foreach ($Circle['X'] as $key => $value) {
                $this->circle($pdf, $Circle['X'][$key], $Circle['Y'], $Circle['R'], $Text[$key]);
            }
        }
        //---20170728 li INS E.
        foreach ($Sections->children() as $Section) {
            $attr_Section = $Section->attributes();

            if ($attr_Section->Name == "Detail1") {
                if ($this->mode != "0") {
                    $tmp_TopMargin_current = $this->TopMargin_current + $this->BottomMargin;
                    $tmp_TopMargin_current = $tmp_TopMargin_current + $this->twip_to_millimeter($attr_Section->Height);
                    if ($tmp_TopMargin_current > $pdf->getPageHeight()) {
                        $this->draw_except_detail($pdf, $Sections, $data);
                    }

                }
                $this->draw_control($pdf, $Section, $data);
            }
        }
    }

    public function draw_detail2($pdf, $Sections, $data)
    {
        //---20170728 li INS S.
        $Circle = $data['Circle'];
        $Text = $data['Text'];
        if (count($Circle)) {
            foreach ($Circle['X'] as $key => $value) {
                $this->circle($pdf, $Circle['X'][$key], $Circle['Y'], $Circle['R'], $Text[$key]);
            }
        }
        //---20170728 li INS E.
        foreach ($Sections->children() as $Section) {
            $attr_Section = $Section->attributes();

            if ($attr_Section->Name == "Detail2") {
                if ($this->mode != "0") {
                    $tmp_TopMargin_current = $this->TopMargin_current + $this->BottomMargin;
                    $tmp_TopMargin_current = $tmp_TopMargin_current + $this->twip_to_millimeter($attr_Section->Height);
                    if ($tmp_TopMargin_current > $pdf->getPageHeight()) {
                        $this->draw_except_detail($pdf, $Sections, $data);
                    }

                }
                $this->draw_control($pdf, $Section, $data);
            }
        }
    }

    //2013-12-02 qiuqiu update end

    //2013-12-02 qiuqiu insert start
    public function get_detail_start_top_margin($Sections, $section_name)
    {
        $this->TopMargin_current = 0;
        $this->TopMargin_current = $this->TopMargin_current + $this->TopMargin;

        foreach ($Sections->children() as $Section) {
            $attr_Section = $Section->attributes();

            if ($attr_Section->Name == $section_name) {
                break;
            }

            $tmp_height = $this->twip_to_millimeter($attr_Section->Height);
            $this->TopMargin_current = $this->TopMargin_current + $tmp_height;
        }
    }

    //2013-12-02 qiuqiu insert end

    /**
     * draw_control
     *
     * @return void
     * @author
     */
    public function draw_control($pdf, $Section, $data)
    {
        //获取各区域的对象 s

        $attr_Section = $Section->attributes();

        //获取各区域的对象 e

        if ($attr_Section->Height > 0) {
            foreach ($Section->children() as $Control) {
                $attr = $Control->attributes();
                switch ($attr->Type) {
                    case "AR.Field":
                        $this->draw_field($pdf, $attr, $data);
                        break;
                    case "AR.Label":
                        $this->draw_label($pdf, $attr, $data);
                        break;
                    case "AR.Line":
                        $this->draw_line($pdf, $attr, $data);
                        break;
                    case "AR.Shape":
                        $this->draw_shape($pdf, $attr);
                        break;
                    case "AR.Image":
                        $this->draw_image($pdf, $attr);
                        break;
                    default:
                        break;
                }
            }
        }

        //20131129 delete start
        // if ($this -> mode == "2")
        // {
        // if ($this -> has_GroupHeader2 == true && $attr_Section -> Name == "GroupHeader1")
        // {
        // $this -> TopMargin_current = $this -> TopMargin_current_GroupHeader2;
        // }
        // else
        // {
        // $tmp_height = $this -> twip_to_millimeter($attr_Section -> Height);
        // $this -> TopMargin_current = $this -> TopMargin_current + $tmp_height;
        // }
        // if ($attr_Section -> Name == "GroupHeader2")
        // {
        // $this -> has_GroupHeader2 = true;
        // $this -> TopMargin_current_GroupHeader2 = $this -> TopMargin_current;
        // }
        // }
        // else
        // {
        // $tmp_height = $this -> twip_to_millimeter($attr_Section -> Height);
        // $this -> TopMargin_current = $this -> TopMargin_current + $tmp_height;
        // }
        //20131129 delete end

        //2013-11-29 zhenghuiyun insert start
        $tmp_height = $this->twip_to_millimeter($attr_Section->Height);
        $this->TopMargin_current = $this->TopMargin_current + $tmp_height;
        //2013-11-29 zhenghuiyun insert end
    }

    /**
     * undocumented function
     *
     * @return void
     * @author
     */
    public function draw_field($pdf, $attr, $data)
    {
        $Visible = "";
        if ($attr->Visible != null) {
            $Visible = $attr->Visible;
            $Visible = (string) $Visible;

            if ($Visible == "0") {
                return;
            }
        }

        $Name = "";
        $DataField = "";
        $Text = "";

        if ($attr->Name != null) {
            $Name = $attr->Name;
            $Name = (string) $Name;
            $Name = trim($Name);
        }

        if ($attr->Text != null) {
            $Text = $attr->Text;
            $Text = (string) $Text;
            $Text = trim($Text);
        }

        if ($attr->DataField != null) {
            $DataField = $attr->DataField;
            $DataField = (string) $DataField;
            $DataField = trim($DataField);
        } else {
            $DataField = $Name;
            $data[$DataField] = $Text;
            $Name = "";
        }
        //20170926 lqs INS S
        if ($DataField == "PAGE") {
            array_push($this->PageMargin, $this->TopMargin_current);
        }
        //20170926 lqs INS E

        $Multiline = "1";
        if ($attr->Multiline != null) {
            $Multiline = $attr->Multiline;
            $Multiline = (string) $Multiline;
            $Multiline = trim($Multiline);
        }

        $x = $this->get_x($attr);
        $y = $this->get_y($attr);
        //+100;
        $w = $this->get_w($attr);
        $h = $this->get_h($attr);

        //get Style s
        $Style = $this->get_style_attributes($attr->Style);

        //font_size s
        $font_size = $this->font_size;
        if (array_key_exists("font-size", $Style) == true) {
            $font_size = $Style["font-size"];
            $font_size = str_replace("pt", "", $font_size);
        }
        //font_size e

        //text_align s
        $text_align = 'L';
        if (array_key_exists("text-align", $Style) == true) {
            $text_align = $Style["text-align"];
            switch ($text_align) {
                case "right":
                    $text_align = "R";
                    break;
                case "left":
                    $text_align = "L";
                    break;
                case "center":
                    $text_align = "C";
                    break;
                default:
                    $text_align = "J";
                    break;
            }
        }
        //text_align e

        //2013-12-18 qiuqiu add start
        $pdf->setCellPaddings(0, 0, 0, 0);
        $paddings = $pdf->getCellPaddings();

        $padding_L = $paddings['L'] - (doubleval(2));
        $padding_R = $paddings['R'] - (doubleval(2));
        if ($text_align == "L") {
            $pdf->setCellPaddings($padding_L, 0, 0, 0);
        } elseif ($text_align == "R") {
            $pdf->setCellPaddings(0, 0, $padding_R, 0);
        }
        //2013-12-18 qiuqiu end end

        //color s
        $color = null;
        if (array_key_exists("color", $Style) == true) {
            $color = $Style["color"];
            $color = $this->hex2RGB($color);
        }
        //color e

        //vertical-align s
        $vertical_align = null;
        if (array_key_exists("vertical-align", $Style) == true) {
            $vertical_align = $Style["vertical-align"];
            switch ($vertical_align) {
                case "top":
                    $vertical_align = 'T';
                    break;
                case "middle":
                    $vertical_align = 'M';
                    break;
                case "bottom":
                    $vertical_align = 'B';
                    break;
                default:
                    $vertical_align = 'T';
                    break;
            }
        }
        //vertical-align e
        //fan add start.
        $font_weight = '';
        if (array_key_exists("font-weight", $Style) == true) {
            $font_weight = $Style["font-weight"];
            switch ($font_weight) {
                case "bold":
                    $font_weight = 'B';
                    break;
                case "normal":
                    $font_weight = '';
                    break;
                default:
                    $font_weight = '';
                    break;
            }
        }
        //fan add end.
        //font-family s
        $font_family = null;
        if (array_key_exists("font-family", $Style) == true) {
            $font_family = $Style["font-family"];
            switch ($font_family) {
                case "ＭＳ ゴシック":
                    $font_family = $this->FONT_FAMILY_GOTHIC;
                    break;
                case "ＭＳ 明朝":
                    $font_family = $this->FONT_FAMILY_MINCHO;
                    break;
                default:
                    break;
            }
        }
        if ($font_family == null) {
            $font_family = $this->font_family;
        }
        //font-family e

        //get Style e

        // set color s
        if ($color != null) {
            $pdf->SetTextColor($color["red"], $color["green"], $color["blue"]);
        } else {
            $pdf->SetTextColor(0, 0, 0);
        }
        ;
        // set color e

        // set font s
        //20140924 zhangxl update start
        // $fontS = $font_weight . $font_style . $text_decoration;
        $fontS = $font_weight;
        $pdf->SetFont($font_family, $fontS, $font_size);
        //20140924 zhangxl update end
        // set font e

        $pdf->SetXY($x, $y);

        if ($Name != "") {
            $val = $this->before_print($Name, $data);
            if (gettype($val) != "boolean") {
                $pdf->Cell($w, $h, $val, 0, $ln = 0, $text_align, 0, '', 0, false, 'T', 'C');
            }
        }

        $pdf->SetXY($x, $y);

        if ($DataField != "") {
            $val = $this->before_print($DataField, $data);

            // if(isset($data[$DataField])){
            // $dataVal = $this->FncNv($data[$DataField]);
            // }

            //2013-12-02 qiuqiu update start
            if (array_key_exists($DataField, $data) == true) {
                if (gettype($val) == "boolean") {

                    $dataVal = $this->FncNv($data[$DataField]);

                    $val = $dataVal;
                }
            } else {
                $val = $Text;
            }

            //2013-12-02 qiuqiu update end

            // if (gettype($val) == "integer")
            // {
            // if ($attr -> OutputFormat != null)
            // {
            // $val = (int)$val;
            // $OutputFormat = $attr -> OutputFormat;
            // $OutputFormat = (string)$OutputFormat;
            // switch ($OutputFormat)
            // {
            // case "#,###" :
            // $val = number_format($val);
            // break;
            // case "#,##0" :
            // $val = number_format($val);
            // break;
            // default :
            // break;
            // }
            // }
            // }

            //2013-11-28 18:57 yuanquan update start
            if (($val != "" && $val != null) || is_integer($val)) {

                if ($attr->OutputFormat != null && $attr->OutputFormat != "") {

                    //if ($DataField != "txtOsiharaikin") {
                    if ($DataField != "LEASE_RYO_RT") {
                        //20170829 YIN DEL S
                        // $val = (int)$val;
                        //20170829 YIN DEL E

                    } else {
                        if (strlen($val) < 5) {
                            $tm = "";
                            for ($jj = 0; $jj < (5 - strlen($val)); $jj++) {
                                $tm .= "0";
                            }
                            $val .= $tm;
                        }
                    }

                    $OutputFormat = $attr->OutputFormat;
                    $OutputFormat = (string) $OutputFormat;
                    switch ($OutputFormat) {
                        case "#,###":
                            $val = number_format($val);
                            if ($val == 0) {
                                $val = "";
                            }
                            break;
                        case "#,##0":
                            $val = number_format($val);
                            break;

                        case "#":
                            $val == '0' ? $val = "" : $val;
                            break;
                        case "#.0000":
                            break;
                        case "#,##0.0":
                            $val = number_format($val, 1);
                            break;
                        //20170926 YIN INS S
                        case "#,##0.00":
                            $val = number_format($val, 2);
                            break;
                        //20170926 YIN INS E
                        default:
                            break;
                    }
                    //}

                }
            }

            //2013-11-28 18:57 yuanquan update end

            //2014-01-02 yuanquan modify start
            $strlength = 0;
            for ($i = 0; $i < mb_strlen($val, "UTF-8"); $i++) {
                $tmp_w = $pdf->GetStringWidth(mb_substr($val, 0, $i, "UTF-8"));

                if ($tmp_w >= $w) {
                    $strlength += $tmp_w;

                    if ($i > 0) {
                        if ($Multiline == "0") {

                            $val = mb_substr($val, 0, $i - 1, "UTF-8");
                        } else {
                            $val = mb_substr($val, 0, 2 * ($i - 2), "UTF-8");
                        }
                    }
                }

            }
            if ($strlength < $w) {
                $Multiline = "0";
            }

            //2014-01-02 yuanquan modify end

            if ($Multiline == "1") {
                $y = $y - 0.5;
                $pdf->SetXY($x, $y);
                $pdf->MultiCell($w, $h, $val, $ln = 0, $text_align, 0, 0, '', '', true, 0, false, true, '', $vertical_align, true);
            } else {
                $pdf->Cell($w, $h, $val, 0, $ln = 0, $text_align, 0, '', 0, false, 'T', $vertical_align);
            }
        }

    }

    /**
     * undocumented function
     *
     * @return void
     * @author
     */

    public function draw_label($pdf, $attr, $data)
    {
        $Visible = "";
        if ($attr->Visible != null) {
            $Visible = $attr->Visible;
            $Visible = (string) $Visible;

            if ($Visible == "0") {
                return;
            }
        }

        if ($attr->Name != null) {
            $Name = $attr->Name;
            $Name = (string) $Name;
            $Name = trim($Name);

            $val = $this->before_print($Name, $data);

            if (gettype($val) == "boolean") {
                if ($val == false) {
                    return;
                }
                ;
            } elseif (gettype($val) == "string") {
                $attr->Caption = $val;
            }
            ;
        }
        ;
        $Caption = $attr->Caption;

        $x = $this->get_x($attr);
        $y = $this->get_y($attr);
        $w = $this->get_w($attr);
        $h = $this->get_h($attr);

        //get Style s
        $Style = $this->get_style_attributes($attr->Style);

        //font_size s
        $font_size = $this->font_size;
        if (array_key_exists("font-size", $Style) == true) {
            $font_size = $Style["font-size"];
            $font_size = str_replace("pt", "", $font_size);
        }
        //font_size e

        //text_align s
        $text_align = 'L';
        if (array_key_exists("text-align", $Style) == true) {
            $text_align = $Style["text-align"];
            switch ($text_align) {
                case "right":
                    $text_align = "R";
                    break;
                case "left":
                    $text_align = "L";
                    break;
                case "center":
                    $text_align = "C";
                    break;
                default:
                    $text_align = "J";
                    break;
            }
        }
        //text_align e

        //color s
        //http://www.html-color-names.com/color-chart.php
        $color = null;
        if (array_key_exists("color", $Style) == true) {
            $color = $Style["color"];
            switch ($color) {
                case "SandyBrown":
                    $color = "#F4A460";
                    break;
                case "MediumBlue":
                    $color = "#0000CD";
                    break;
                case "OrangeRed":
                    $color = "#FF4500";
                    break;
                case "Black":
                    $color = "#000000";
                    break;
                case "White":
                    $color = "#FFFFFF";
                    break;
                case "DarkOrange":
                    $color = "#FF8C00";
                    break;
                default:
                    break;
            }
            $color = $this->hex2RGB($color);
        }
        //color e

        //get Style e

        // set color s
        if ($color != null) {
            $pdf->SetTextColor($color["red"], $color["green"], $color["blue"]);
        } else {
            $pdf->SetTextColor(0, 0, 0);
        }
        ;
        // set color e

        //font-family s
        $font_family = null;
        if (array_key_exists("font-family", $Style) == true) {
            $font_family = $Style["font-family"];
            switch ($font_family) {
                case "ＭＳ ゴシック":
                    $font_family = $this->FONT_FAMILY_GOTHIC;
                    break;
                case "ＭＳ 明朝":
                    $font_family = $this->FONT_FAMILY_MINCHO;
                    break;
                case "MS UI Gothic":
                    $font_family = $this->FONT_FAMILY_GOTHICUI;
                    break;
                default:
                    break;
            }
        }
        if ($font_family == null) {
            $font_family = $this->font_family;
        }
        //font-family e

        //background_color s
        $background_color = null;
        if (array_key_exists("background-color", $Style) == true) {

            $background_color = $Style["background-color"];
            if ($this->mode != '9') {
                switch ($background_color) {
                    case "Azure":
                        $background_color = "#F0FFFF";
                        break;
                    default:
                        break;
                }

                $background_color = $this->hex2RGB($background_color);
            } else {
                if ($data['COLOR'] == 'White') {
                    $background_color = "#FFFFFF";
                    $background_color = $this->hex2RGB($background_color);
                } else {
                    $background_color = "#CFCFCF";
                    $background_color = $this->hex2RGB($background_color);
                }
            }

        }
        //background_color e

        // set background_color s
        if ($background_color != null) {
            $pdf->SetFillColor($background_color["red"], $background_color["green"], $background_color["blue"]);
        }
        ;
        // set background_color e

        //2013-12-18 qiuqiu add start
        //set underline s
        $text_decoration = '';
        if (array_key_exists("text-decoration", $Style) == true) {
            $text_decoration = $Style["text-decoration"];
            switch ($text_decoration) {
                case "underline":
                    $text_decoration = "U";
                    break;
                default:
                    $text_decoration = "";
                    break;
            }
        }
        //fuxl updata
        $font_weight = '';
        if (array_key_exists("font-weight", $Style) == true) {
            $font_weight = $Style["font-weight"];
            switch ($font_weight) {
                case "bold":
                    $font_weight = 'B';
                    break;
                case "normal":
                    $font_weight = '';
                    break;
                default:
                    $font_weight = '';
                    break;
            }
        }

        $font_style = '';
        if (array_key_exists("font-style", $Style) == true) {
            $font_style = $Style["font-style"];
            switch ($font_style) {
                case "italic":
                    $font_style = 'I';
                    break;
                default:
                    $font_style = '';
                    break;
            }
        }
        $fontS = $font_weight . $font_style . $text_decoration;

        //set underline s
        //2013-12-18 qiuqiu add end

        // set font s
        $pdf->SetFont($font_family, $fontS, $font_size);
        // set font e
        //fuxl updata
        $pdf->SetXY($x, $y);
        if ($background_color != null) {
            $pdf->Cell($w, $h, $Caption, 0, $ln = 0, $text_align, 1, '', 0, false, 'T', 'C');
        } else {
            $pdf->Cell($w, $h, $Caption, 0, $ln = 0, $text_align, 0, '', 0, false, 'T', 'C');
        }
        ;

    }

    /**
     * undocumented function
     *
     * @return void
     * @author
     */
    public function draw_line($pdf, $attr, $data = NULL)
    {
        $x1 = $this->get_x1($attr);
        $y1 = $this->get_y1($attr);
        $x2 = $this->get_x2($attr);
        $y2 = $this->get_y2($attr);

        //line_color s
        $line_color = $attr->LineColor;
        $rgb = $this->int_to_rgb($line_color);
        //line_color e

        //line_weight s
        $line_weight = 1;
        if ($attr->LineWeight != null) {
            $line_weight = $attr->LineWeight;
        }
        $line_weight = $this->en_to_millimeter($line_weight);
        //line_weight e

        $dash = "0";
        if ($attr->LineStyle != null) {
            if ($attr->LineStyle == "3") {
                $dash = "0.25,1";
            }
            ;
            if ($attr->LineStyle == "4") {
                $dash = "0.2,2,2";
            }
            ;
        }
        ;

        if ($this->mode == '9') {
            if ($data['Max'] == 1) {
                if ($x2 - $x1 != 0) {
                    $line_weight = $this->en_to_millimeter(3);

                } else {
                    if ($line_weight == $this->en_to_millimeter(3)) {
                        $line_weight = $this->en_to_millimeter(3);

                    } else {

                        $line_weight = $this->en_to_millimeter(1);
                    }
                }

            }

        }

        $arr = array(
            "width" => $line_weight,
            "cap" => "square",
            "join" => "bevel",
            "dash" => $dash,
            "phase" => "1",
            "color" => array(
                $rgb["red"],
                $rgb["green"],
                $rgb["blue"]
            )
        );

        $pdf->SetLineStyle($arr);

        $pdf->Line($x1, $y1, $x2, $y2);

    }

    /**
     * undocumented function
     *
     * @return void
     * @author
     */
    public function draw_shape($pdf, $attr)
    {
        $x = $this->get_x($attr);
        $y = $this->get_y($attr);
        $w = $this->get_w($attr);
        $h = $this->get_h($attr);

        //line_color s
        $line_color = $attr->LineColor;
        $rgb = $this->int_to_rgb($line_color);
        //line_color e

        $back_color = $attr->BackColor;
        $back_color_rgb = $this->int_to_rgb($back_color);
        if ($back_color_rgb != null) {
            $pdf->SetFillColor($back_color_rgb["red"], $back_color_rgb["green"], $back_color_rgb["blue"]);
        }
        ;

        //line_weight s
        $line_weight = 1;
        if ($attr->LineWeight != null) {
            $line_weight = $attr->LineWeight;
        }

        $line_weight = $this->en_to_millimeter($line_weight);

        $arr = array(
            'all' => array(
                "width" => $line_weight,
                "cap" => "square",
                "join" => "miter",
                "dash" => "0",
                "phase" => "0",
            )
        );
        $pdf->SetLineStyle($arr);
        $pdf->SetDrawColor($rgb["red"], $rgb["green"], $rgb["blue"]);

        //2013-12-18 qiuqiu add start

        $shapeType = null;
        if ($attr->Shape != null) {
            $shapeType = $attr->Shape;
        }

        //2013-12-18 qiuqiu add end

        //$pdf -> Rect($x, $y, $w, $h, 'D', $arr);
        //2013-12-18 yuanquan update start

        if ($shapeType == "2") {
            $arr['all']['cap'] = 'butt';
            $r = (floatval($w / 50));
            $pdf->RoundedRect($x, $y, $w, $h, $r, '1111', 'D', $arr['all']);
        } else {
            if ($back_color == '26367') {
                $pdf->Rect($x, $y, $w, $h, 'DF', $arr);
            } else {
                $pdf->Rect($x, $y, $w, $h, 'D', $arr);
            }
        }
        //2013-12-18 yuanquan update end
    }

    /**
     * undocumented function
     *
     * @return void
     * @author
     */
    public function draw_image($pdf, $attr)
    {
        $x = $this->get_x($attr);
        $y = $this->get_y($attr);
        $w = $this->get_w($attr);
        $h = $this->get_h($attr);

        //line_color s
        $line_color = $attr->LineColor;
        $rgb = $this->int_to_rgb($line_color);
        //line_color e

        //line_weight s
        $line_weight = 1;
        if ($attr->LineWeight != null) {
            $line_weight = $attr->LineWeight;
        }
        $line_weight = $this->en_to_millimeter($line_weight);
        //line_weight e

        $pdf->SetXY($x, $y);

        $img_file = $this->file_path . "/" . $this->INKAN_JPG;
        $pdf->Image($img_file, "", "", "", "");
    }

    /**
     * undocumented function
     *
     * @return number
     * @author
     */
    public function get_x1($attr)
    {
        $x1 = 0;
        $x1 = $this->twip_to_millimeter($attr->X1);
        //加余白
        $x1 = $x1 + $this->LeftMargin;

        return $x1;
    }

    /**
     * undocumented function
     *
     * @return number
     * @author
     */
    public function get_x2($attr)
    {
        $x2 = 0;
        $x2 = $this->twip_to_millimeter($attr->X2);
        //加余白
        $x2 = $x2 + $this->LeftMargin;

        return $x2;
    }

    /**
     * undocumented function
     *
     * @return number
     * @author
     */
    public function get_y1($attr)
    {
        $y1 = 0;
        $y1 = $this->twip_to_millimeter($attr->Y1);
        //加余白
        $y1 = $y1 + $this->TopMargin_current;

        return $y1;
    }

    /**
     * undocumented function
     *
     * @return number
     * @author
     */
    public function get_y2($attr)
    {
        $y2 = 0;
        $y2 = $this->twip_to_millimeter($attr->Y2);
        //加余白
        $y2 = $y2 + $this->TopMargin_current;

        return $y2;
    }

    /**
     * undocumented function
     *
     * @return number
     * @author
     */
    public function get_x($attr)
    {
        $x = 0;
        $x = $this->twip_to_millimeter($attr->Left);
        //加余白
        $x = $x + $this->LeftMargin;

        return $x;
    }

    /**
     * undocumented function
     *
     * @return number
     * @author
     */
    public function get_y($attr)
    {
        $y = 0;
        $y = $this->twip_to_millimeter($attr->Top);
        //加余白
        $y = $y + $this->TopMargin_current;

        return $y;
    }

    /**
     * undocumented function
     *
     * @return number
     * @author
     */
    public function get_w($attr)
    {
        $w = 0;
        $w = $this->twip_to_millimeter($attr->Width);

        return $w;
    }

    /**
     * undocumented function
     *
     * @return number
     * @author
     */
    public function get_h($attr)
    {
        $h = 0;
        $h = $this->twip_to_millimeter($attr->Height);

        return $h;
    }

    /**
     * 因为rpx内的度量单位为twip,而tcpdf内度量单位为millimeter，所以需要转换
     * 关于"twip"去网上搜索
     * 1 twip = 0.01763888888889 millimeter
     * 单位转换可以参考一下网站
     * http://www.translatorscafe.com/cafe/EN/units-converter/typography/9-8/
     */
    function twip_to_millimeter($value = 1)
    {
        return $value * 0.01763888888889;
    }

    /**
     * 因为rpx内的Line宽度（LineWeight）的度量单位为en,而tcpdf内度量单位为millimeter，所以需要转换
     * 关于"en"去网上搜索
     * 1 en = 0.1757299017573 millimeter
     * 单位转换可以参考一下网站
     * http://www.translatorscafe.com/cafe/EN/units-converter/typography/9-8/
     */
    public function en_to_millimeter($value = 1)
    {
        return $value * 0.1757299017573;
    }

    /**
     * 把原来rpx文件内的"Control"节点的"Style"属性的值转换至数组
     * 原来rpx文件内的"Style"是CSS类型的值
     * Style="color: #66CC99; background-color: #FFEEE2; "
     */
    public function get_style_attributes($value = "")
    {
        if ($value == "") {
            return $value;
        }

        $str_arr = array();
        //把Style的字符串用"；"分割得到CSS属性值
        $arr = explode(";", $value);
        //遍历CSS属性值
        foreach ($arr as $val) {
            //把单个CSS属性值字符串用"："分割得到属性和值
            $arr_val = explode(":", $val);
            //把属性和值添加到数组
            if (trim($arr_val[0]) != "") {
                $arr_val[0] = trim($arr_val[0]);
                $arr_val[1] = trim($arr_val[1]);
                $str_arr[$arr_val[0]] = $arr_val[1];
            }
            ;
        }
        ;
        return $str_arr;
    }

    /**
     * Convert a hexa decimal color code to its RGB equivalent
     *
     * @param string $hexStr (hexadecimal color value)
     * @param boolean $returnAsString (if set true, returns the value separated by
     * the separator character. Otherwise returns associative array)
     * @param string $seperator (to separate RGB values. Applicable only if second
     * parameter is true.)
     * @return array | string | bool (depending on second parameter. Returns False if
     * invalid hex color value)
     *
     * http://php.net/manual/ja/function.hexdec.php
     */
    public function hex2RGB($hexStr, $returnAsString = false, $seperator = ',')
    {
        $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr);
        // Gets a proper hex string
        $rgbArray = array();
        if (strlen($hexStr) == 6) {
            //If a proper hex code, convert using bitwise operation. No overhead... faster
            $colorVal = hexdec($hexStr);
            $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
            $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
            $rgbArray['blue'] = 0xFF & $colorVal;
        } elseif (strlen($hexStr) == 3) {
            //if shorthand notation, need some string manipulations
            $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
            $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
            $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
        } else {
            //Invalid hex color code
            return false;
        }
        // returns the rgb string or the associative array
        return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray;
    }

    /**
     *
     */
    public function int_to_hex($n)
    {
        return "#" . substr("000000" . dechex((int) $n), -6);
    }

    /**
     *
     */
    public function int_to_rgb($n)
    {
        $hex = $this->int_to_hex($n);
        $rgb = $this->hex2RGB($hex);
        $tmp = $rgb['red'];
        $rgb['red'] = $rgb['blue'];
        $rgb['blue'] = $tmp;
        return $rgb;
    }

    public function before_print(&$key, &$data)
    {
        require_once $this->rpx_file_name . ".php";
        $val = call_user_func_array($this->rpx_file_name, array(&$key, &$data));
        $data = $val["data"];
        return $val["val"];
    }

    //**********************************************************************
    //処 理 名：Null変換関数(文字)
    //関 数 名：FncNv
    //引     数：$objValue     (I)文字列
    //戻 り 値：変換後の値
    //処理説明：Null変換(文字)を行う。
    //**********************************************************************
    public function FncNv($objValue, $objReturn = "")
    {
        //---NULLの場合---
        if ($objValue === null) {
            return $objReturn;
        }
        //---以外の場合---
        else {
            return $objValue;
        }
    }

}

// END
