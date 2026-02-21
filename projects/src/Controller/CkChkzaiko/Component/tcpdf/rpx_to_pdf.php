<?php

/**
 * rpxファイルから描画情報を読み込んで、pdfファイルを出力する
 *
 * @package default
 * @author zheng.huiyun
 */
class rpx_to_pdf
{
    //定数定義 s

    private $NL = "<br/>";
    private $SPACE = "&nbsp;";
    private $INDENT = "&nbsp;&nbsp;&nbsp;&nbsp;";

    private $FONT_FAMILY_MINCHO = "msmincho";
    private $FONT_FAMILY_GOTHIC = "kozgopromedium";

    private $INKAN_JPG = "inkan.jpg";

    private $OVER_TIME = 3600;

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

    private $rpx_file_names = array();
    private $datas = array();
    private $data_fields = array();

    private $Orientation = "";

    private $file_path = "";
    private $output_path = "";
    private $file_cnt = 1;

    //変数定義 ｅ

    /**
     *
     *
     * @return void
     * @author
     */
    public function __construct($rpx_file_names, $datas, $mode = "1")
    {
        $this->rpx_file_names = $rpx_file_names;
        $this->datas = $datas;
        $this->file_cnt = (int) $mode;
        //var_dump($rpx_file_names);

        $iterator = new DirectoryIterator(dirname(__FILE__));
        $this->file_path = $iterator->getPath();

        $this->output_path = WWW_ROOT . $this->REPORTS_TEMP_PATH;
    }

    /**
     * undocumented function
     *
     * @return string
     * @author
     */
    public function to_pdf($pdf_page_orientation = 'L', $documentType = "A4")
    {
        // Include the main TCPDF library (search for installation path).
        //2013-12-19 qiuqiu modify start
        // $tcpdf_file = $this -> file_path . "/" . 'tcpdf/tcpdf.php';
        $tcpdf_file = str_replace("CkChkzaiko/Component", "Component", $this->file_path) . "/" . 'tcpdf/tcpdf.php';
        //2013-12-19 qiuqiu modify end
        require_once $tcpdf_file;
        // create new PDF document
        //20140219 yushuangji edit start
        //$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        //$pdf_page_orientation = "L";
        //$documentType = "A5";
        $pdf = new TCPDF($pdf_page_orientation, PDF_UNIT, $documentType, true, 'UTF-8', false);
        //overwrite page height
        //$pdf -> h = 100;

        //20140219 yushuangji edit end

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // remove default header/footer s

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // remove default header/footer e

        // set image scale factor s

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set image scale factor e
        foreach ($this->datas as $key => $value) {
            $this->mode = $value["mode"];
            $datas = $value["data"];
            $cnt_datas = count($datas);
            for ($i = 0; $i < $cnt_datas; $i++) {
                foreach ($this->rpx_file_names as $rpx_file_name => $data_fields) {
                    if (strpos($rpx_file_name, $key) !== false) {
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

        $output_file = $this->REPORTS_TEMP_PATH . $file_name;
        return $output_file;
    }

    /**
     * draw_pdf
     *
     * @return void
     * @author
     */
    public function draw_pdf($pdf, $rpx_file_name, $data)
    {

        //20140219 yushuangji add start
        $createFlg = "Double";
        //if(strstr($rpx_file_name,""))
        if ($rpx_file_name == "rptCkChkzaiko_Single") {
            $createFlg = "Single";
        }
        //20140219 yushuangji add end
        $rpx_file = $this->file_path . "/" . $rpx_file_name . ".rpx";
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
                            default:
                                break;
                        }
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

            switch ($this->mode) {
                case "0":
                    //2013-11-29 zhenghuiyun delete start
                    // case "2" :
                    //2013-11-29 zhenghuiyun delete end
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
                    $cnt = count($data);
                    for ($i = 0; $i < $cnt; $i++) {
                        $data[$i] = array_merge($this->data_fields, $data[$i]);
                        if ($i == 0) {
                            $this->draw_except_detail($pdf, $Sections, $data[$i], $createFlg);
                        }
                        //$this -> draw_detail($pdf, $Sections, $data[$i]);
                        $this->draw_detail($pdf, $Sections, $data[$i], $data[$i]);
                    }
                    break;
                default:
                    break;
            }

        } else {
            exit('Error.');
        }
        ;
    }

    /**
     * draw_except_detail
     * add new page
     * @return void
     * @author
     */
    public function draw_except_detail($pdf, $Sections, $data, $createFlg = "Double")
    {
        $this->TopMargin_current = 0;
        $this->TopMargin_current = $this->TopMargin_current + $this->TopMargin;
        //20140219 yushuangji edit start
        switch ($createFlg) {
            case "Double":
                $pdf->AddPage($this->Orientation);
                break;
            case "Single":
                $this->Orientation = "L";
                $resolution = array(
                    158,
                    210
                );
                $pdf->AddPage($this->Orientation, $resolution);
                break;
        }

        //20140219 yushuangji edit start
        foreach ($Sections->children() as $Section) {
            $attr_Section = $Section->attributes();

            //2014-01-06 qiuqiu update start
            if ($attr_Section->Name != "Detail") {
                if ($this->mode == "0" && $attr_Section->Name == "PageFooter") {
                    // $tmp_height = $this -> twip_to_millimeter($attr_Section -> Height);
                    $this->TopMargin_current = $this->TopMargin_current + 3.5;
                }
                $this->draw_control($pdf, $Section, $data);
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
    // public function draw_detail($pdf, $Sections, $data)
    // {
    // foreach ($Sections->children() as $Section)
    // {
    // $attr_Section = $Section -> attributes();
    //
    // if ($attr_Section -> Name == "Detail")
    // {
    // if ($this -> mode != "0")
    // {
    // $tmp_TopMargin_current = $this -> TopMargin_current + $this -> BottomMargin;
    // $tmp_TopMargin_current = $tmp_TopMargin_current + $this -> twip_to_millimeter($attr_Section -> Height);
    // if ($tmp_TopMargin_current > $pdf -> getPageHeight())
    // {
    // $this -> draw_except_detail($pdf, $Sections, $data);
    // }
    // }
    //
    // $this -> draw_control($pdf, $Section, $data);
    // }
    // }
    // }

    public function draw_detail($pdf, $Sections, $except_detail_data, $data)
    {
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
                        }
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
                        $this->draw_line($pdf, $attr);
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

        $Multiline = "1";
        if ($attr->Multiline != null) {
            $Multiline = $attr->Multiline;
            $Multiline = (string) $Multiline;
            $Multiline = trim($Multiline);
        }

        $x = $this->get_x($attr);
        $y = $this->get_y($attr);
        $w = $this->get_w($attr);
        $h = $this->get_h($attr);

        //get Style s
        $Style = $this->get_style_attributes($attr->Style);

        //font_size s
        $font_size = 9;
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
        $pdf->SetFont($font_family, '', $font_size);
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
                    $val = (int) $val;

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
                // echo $DataField;
                // echo "<br />";
                // echo $data[$DataField];
                // echo "<br />";

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
        $font_size = 9;
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
            switch ($background_color) {
                case "Azure":
                    $background_color = "#F0FFFF";
                    break;
                default:
                    break;
            }
            $background_color = $this->hex2RGB($background_color);
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
                    break;
            }
        }
        //set underline s
        //2013-12-18 qiuqiu add end

        // set font s
        $pdf->SetFont($font_family, $text_decoration, $font_size);
        // set font e

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
    public function draw_line($pdf, $attr)
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
                //$dash = "0.25,1";
                $dash = "0.25,2";
            }
            ;
        }
        ;
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
            $r = floatval($w / 50);
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
        // $w = $this->get_w($attr);
        // $h = $this->get_h($attr);

        //line_color s
        // $line_color = $attr->LineColor;
        // $rgb = $this->int_to_rgb($line_color);
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
     * @return void
     * @author
     */
    public function get_x1($attr): float
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
     * @return void
     * @author
     */
    public function get_x2($attr): float
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
     * @return void
     * @author
     */
    public function get_y1($attr): float
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
     * @return void
     * @author
     */
    public function get_y2($attr): float
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
     * @return void
     * @author
     */
    public function get_x($attr): float
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
     * @return void
     * @author
     */
    public function get_y($attr): float
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
     * @return void
     * @author
     */
    public function get_w($attr): float
    {
        $w = 0;
        $w = $this->twip_to_millimeter($attr->Width);

        return $w;
    }

    /**
     * undocumented function
     *
     * @return void
     * @author
     */
    public function get_h($attr): float
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
     * @return array | string | bool or string (depending on second parameter. Returns False if
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
        // 20241205 caina upd s
        if (!is_numeric($n) || $n === null) {
            return "#000000";
        }
        return "#" . substr("000000" . dechex((int) $n), -6);
        // 20241205 caina upd e
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
        // 20241205 caina upd s
        // $val = call_user_func($this->rpx_file_name, $key, $data);
        $val = call_user_func_array($this->rpx_file_name, [&$key, &$data]);
        // 20241205 caina upd e
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
        if ($objValue == null) {
            return $objReturn;
        }
        //---以外の場合---
        else {
            return $objValue;
        }
    }

}

// END
