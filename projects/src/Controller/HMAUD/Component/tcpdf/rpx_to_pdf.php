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
 * 20241114         202410_内部統制システム_集計機能改善対応.xlsx                  caina
 * --------------------------------------------------------------------------------------------
 */

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
    //private $FONT_FAMILY_GOTHIC = "kozgopromedium";
    //fan modify s.
    private $FONT_FAMILY_GOTHIC = "msgothic";
    //fan modify e.
    private $FONT_FAMILY_GOTHICUI = "msgothicui";

    private $INKAN_JPG = "inkan.jpg";

    private $OVER_TIME = 3600;

    public $REPORTS_TEMP_PATH = "reports/temp/";

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
    //20241114 lujunxia ins s
    //集計種類
    private $summery = '';
    //20241114 lujunxia ins e
    private $font_family = "";
    private $font_size = 9;

    private $rpx_file_names = array();
    //20161009 YIN INS S
    private $alldatas = array();
    //20161009 YIN INS E
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
    //20230211 lujunxia ins s
    public $lineHeight = 200;
    public $baseStart = 0;
    public $baseEnd = 0;
    //一页最多能放几个据点
    public $KyotenMaxNum = 27;
    public $bgColorData = array();
    //数据中一共有几个据点
    public $totalKyotenCount = 0;
    public $footerLeft = 0;
    //20230211 lujunxia ins e
    public $Orientation;

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
        //20161009 YIN INS S
        $this->alldatas = $datas;
        //20161009 YIN INS E
        $this->file_cnt = (int) $mode;
        //var_dump($rpx_file_names);

        $iterator = new DirectoryIterator(dirname(__FILE__));
        $this->file_path = $iterator->getPath();

        $this->output_path = WWW_ROOT . $this->REPORTS_TEMP_PATH;
    }

    //20230211 lujunxia add s
    //内部統制実績集計専用 Detail:before draw shap,label and field,get max 'Height'
    public function getChangedHeight($pdf, $Sections, $data)
    {
        // 20241114 YIN UPD S
        // $this->lineHeight = 200;
        if ($this->mode == '13') {
            $this->lineHeight = 250;
            $rowheight = 180;
        } else {
            $this->lineHeight = 200;
            $rowheight = 125;
        }
        // 20241114 YIN UPD E
        foreach ($Sections->children() as $Section) {
            $attr_Section = $Section->attributes();

            if ($attr_Section->Name == "Detail") {
                foreach ($Section->children() as $Control) {
                    $attr = $Control->attributes();
                    $name = $attr->Name . '';
                    //格子宽度
                    $w = $this->get_w($attr);
                    //業務手順書項目,業務内容,監査項目
                    // 20241114 YIN UPD S
                    // if ($name == "COLUMN1" || $name == "COLUMN2" || $name == "COLUMN4" || $name == "COLUMN7") {
                    if ($name == "COLUMN1" || $name == "COLUMN2" || $name == "COLUMN4" || $name == "COLUMN7" || $name == "CHECK_LST_ID") {
                        // 20241114 YIN UPD E
                        $val = $data[$name];
                        //改行がある場合（摘要）
                        $arrVal = array($val);
                        if (strpos($val, "\r\n") !== false) {
                            $arrVal = explode("\r\n", $val);
                        } else
                            if (strpos($val, "\n") !== false) {
                                $arrVal = explode("\n", $val);
                            } else
                                if (strpos($val, "\r") !== false) {
                                    $arrVal = explode("\r", $val);
                                }
                        //行数（数据需要几行能够放下）
                        $rowNumber = 0;
                        for ($j = 0; $j < count($arrVal); $j++) {
                            $partVal = $arrVal[$j];
                            //数据宽度
                            $w_partVal = $pdf->GetStringWidth($partVal);
                            // 20241114 YIN UPD S
                            // if ($name == "COLUMN1") {
                            if ($name == "COLUMN1" || $name == "CHECK_LST_ID") {
                                // 20241114 YIN UPD E
                                //有很多空格和有一位半角数字的场合会溢出的问题
                                $w_partVal = $w_partVal + 2;
                            }
                            // 20241114 YIN UPD S
                            // $rowNumber += ceil($w_partVal / $w);
                            $rowNumber += ceil($w_partVal / $w) == 0 ? 1 : ceil($w_partVal / $w);
                            // 20241114 YIN UPD E
                        }
                        // 20241114 YIN UPD S
                        // $lineHeight = $rowNumber * 125;
                        $lineHeight = $rowNumber * $rowheight;
                        // 20241114 YIN UPD E
                        //行的高度，取本行中最高的那个
                        $this->lineHeight = $lineHeight > $this->lineHeight ? $lineHeight : $this->lineHeight;
                    }
                }
            }
        }
    }

    public function writeToFile($source, $header)
    {
        $this->totalKyotenCount = count($header) - 3;
        $file = str_replace("rptJisseki", "temp_file", $source);
        copy($source, $file);
        $myfile = fopen($file, "a");
        $left = '4170';
        $plus_width = 0;
        //据点的总数量，少于页面能放的最大数量的时候
        if ($this->totalKyotenCount < $this->KyotenMaxNum) {
            $num = $this->KyotenMaxNum - $this->totalKyotenCount;
            $plus_width = $num * 399.99 / 3;
            $left = $left + $num * 399.99;

        }
        $this->footerLeft = 150 + $left;
        //文件内容
        $str = '';
        //業務手順書項目,業務内容,監査項目  title
        $str .= '<Control Type="AR.Label" Name="Label4" Left="260" Top="20" Width="' . (710 + $plus_width) . '" Height="300" Caption="業務手順書項目" Style="text-align:left;background-color: #DCDCDC;font-size: 5pt font-family: ＭＳ ゴシック; vertical-align: middle; " />' . "\r\n";
        $str .= '<Control Type="AR.Label" Name="Label5" Left="' . (970 + $plus_width) . '" Top="20" Width="' . (1700 + $plus_width) . '" Height="300" Caption="業務内容" Style="text-align:left;background-color: #DCDCDC;font-size: 5pt font-family: ＭＳ ゴシック; vertical-align: middle; " />' . "\r\n";
        $str .= '<Control Type="AR.Label" Name="Label6" Left="' . (2670 + $plus_width + $plus_width) . '" Top="20" Width="' . (1510 + $plus_width) . '" Height="300" Caption="監査項目" Style="text-align:left;background-color: #DCDCDC;font-size: 5pt font-family: ＭＳ ゴシック; vertical-align: middle; " />' . "\r\n";
        $str .= '<Control Type="AR.Shape" Name="Shape2" Left="250" Top="20" Width="' . (710 + $plus_width) . '" Height="300" />' . "\r\n";
        $str .= '<Control Type="AR.Shape" Name="Shape3" Left="' . (960 + $plus_width) . '" Top="20" Width="' . (1700 + $plus_width) . '" Height="300" />' . "\r\n";
        $str .= '<Control Type="AR.Shape" Name="Shape4" Left="' . (2660 + $plus_width + $plus_width) . '" Top="20" Width="' . (1510 + $plus_width) . '" Height="300" />' . "\r\n";
        $str .= '</Section>' . "\r\n";
        //ID,業務手順書項目,業務内容,監査項目  detail
        $str .= '<Section Type="Detail" Name="Detail" Height="1000" CanShrink="0">' . "\r\n";
        $str .= '<Control Type="AR.Shape" Name="Shape5" Left="0" Top="-260" Width="250" Height="1000" />' . "\r\n";
        $str .= '<Control Type="AR.Shape" Name="Shape6" Left="250" Top="-260" Width="' . (710 + $plus_width) . '" Height="1000" />' . "\r\n";
        $str .= '<Control Type="AR.Shape" Name="Shape7" Left="' . (960 + $plus_width) . '" Top="-260" Width="' . (1700 + $plus_width) . '" Height="1000" />' . "\r\n";
        $str .= '<Control Type="AR.Shape" Name="Shape8" Left="' . (2660 + $plus_width + $plus_width) . '" Top="-260" Width="' . (1510 + $plus_width) . '" Height="1000" />' . "\r\n";
        $str .= '<Control Type="AR.Field" Name="COLUMN1" DataField="COLUMN1" Left="20" Top="-240" Width="220" Height="0" Text = "" MultirowNum ="5" Style="text-align: left;font-weight: normal;font-size: 5pt font-family: ＭＳ ゴシック; vertical-align: top; " />' . "\r\n";
        $str .= '<Control Type="AR.Field" Name="COLUMN2" DataField="COLUMN2" Left="260" Top="-240" Width="' . (710 + $plus_width) . '" Height="0" Text = ""  MultirowNum ="5" Style="text-align: left;font-weight: normal;font-size: 5pt;font-family: ＭＳ ゴシック; vertical-align: top; " />' . "\r\n";
        $str .= '<Control Type="AR.Field" Name="COLUMN4" DataField="COLUMN4" Left="' . (970 + $plus_width) . '" Top="-240" Width="' . (1700 + $plus_width) . '" Height="0" Text = ""  MultirowNum ="5" Style="text-align: left;font-weight: normal;font-size: 5pt;font-family: ＭＳ ゴシック; vertical-align: top; " />' . "\r\n";
        $str .= '<Control Type="AR.Field" Name="COLUMN7" DataField="COLUMN7" Left="' . (2670 + $plus_width + $plus_width) . '" Top="-240" Width="' . (1510 + $plus_width) . '" Height="0" Text = "" MultirowNum ="5" Style="text-align: left;font-weight: normal;font-size: 5pt font-family: ＭＳ ゴシック; vertical-align: top; " />' . "\r\n";

        //detail部分
        for ($i = 0; $i < count($header); $i++) {
            if ($i == count($header) - 3) {
                //合計
                $str .= '<Control Type="AR.Field" Name="TOTALField" DataField="total" Left="' . $left . '" Top="-232" Width="300" Height="1000" Text="" Style="font-weight: normal;font-size: 6.8pt font-family: ＭＳ ゴシック; vertical-align: middle; " />' . "\r\n";
            } else
                if ($i == count($header) - 2) {
                    //実施度合<br>（全１７拠点）
                    $str .= '<Control Type="AR.Field" Name="PERCENTField" DataField="percent" Left="' . $left . '" Top="-232" Width="720" Height="1000" Text="" Style="font-weight: normal;font-size: 6.8pt font-family: ＭＳ ゴシック; vertical-align: middle; " />' . "\r\n";
                } else
                    if ($i == count($header) - 1) {
                        //ランク<br>（項目）
                        $str .= '<Control Type="AR.Field" Name="RANKField" DataField="rank" Left="' . $left . '" Top="-232" Width="420" Height="1000" Text="" Style="font-weight: normal;font-size: 5pt font-family: ＭＳ ゴシック; vertical-align: middle; " />' . "\r\n";
                    } else {
                        //拠点
                        $str .= '<Control Type="AR.Field" Name="KYOTENField" Ktnumber="' . $i . '" DataField="check' . $i . '" Left="' . $left . '" Top="-232" Width="400" Height="1000" Text="" Style="text-align:center;font-weight: normal;font-size: 5pt font-family: ＭＳ ゴシック; vertical-align: middle; " />' . "\r\n";
                    }
        }
        $str .= '</Section>' . "\r\n";
        $str .= '<Section Type="GroupHeader" Name="GroupHeader1" Height="580" BackColor="16777215" CanShrink="0">' . "\r\n";
        //title部分
        for ($i = 0; $i < count($header); $i++) {
            if ($i == count($header) - 3) {
                //合計
                $str .= '<Control Type="AR.Label" Name="KYOTENTotal" Left="' . $left . '" Top="-1560" Width="300" Height="300" Caption="' . $header[$i] . '" Style="text-align:left;background-color: #DCDCDC;font-weight: normal;font-size: 5pt font-family: ＭＳ ゴシック; vertical-align: middle; " />' . "\r\n";
            } else
                if ($i == count($header) - 2) {
                    //実施度合<br>（全１７拠点）
                    $str .= '<Control Type="AR.Label" Name="KYOTENPersent" Left="' . $left . '" Top="-1560" Width="720" Height="300" Caption="' . $header[$i] . '" Style="text-align:left;background-color: #DCDCDC;font-weight: normal;font-size: 5pt font-family: ＭＳ ゴシック; vertical-align: middle; " />' . "\r\n";
                } else
                    if ($i == count($header) - 1) {
                        //ランク<br>（項目）
                        $str .= '<Control Type="AR.Label" Name="KYOTENRank" Left="' . $left . '" Top="-1560" Width="420" Height="300" Caption="' . $header[$i] . '" Style="text-align:left;background-color: #DCDCDC;font-weight: normal;font-size: 5pt font-family: ＭＳ ゴシック; vertical-align: middle; " />' . "\r\n";
                    } else {
                        $backgroundColor = '#DCDCDC';
                        if (in_array($i, $this->bgColorData['detail_first'])) {
                            //指摘数最多
                            $backgroundColor = '#C0504D';
                        } else
                            if (in_array($i, $this->bgColorData['detail_second'])) {
                                //指摘数第二多和第三多
                                $backgroundColor = '#E6B8B7';
                            }
                        //拠点header
                        $str .= '<Control Type="AR.Label" Name="KYOTENLabel" Left="' . $left . '" Top="-1560" Width="400" Height="300" Ktnumber="' . $i . '" Caption="' . $header[$i] . '" Style="text-align:left;background-color: ' . $backgroundColor . '; font-weight: normal;font-size: 5pt font-family: ＭＳ ゴシック; vertical-align: middle; " />' . "\r\n";
                    }
        }
        $str .= '</Section>' . "\r\n";
        $str .= '</Sections>' . "\r\n";
        $str .= '<ReportComponentTray />' . "\r\n";
        $str .= '<PageSettings LeftMargin="150" RightMargin="50" TopMargin="100" BottomMargin="100" PaperSize="9" PaperWidth="21384" PaperHeight="15120" PaperName="" Orientation="2" />' . "\r\n";
        $str .= '<Parameters />' . "\r\n";
        $str .= '</ActiveReportsLayout>';
        fwrite($myfile, $str);
        fclose($myfile);
        // 20241114 caina del s
        // chmod($file, 0666);
        // 20241114 caina del e
        return $file;
    }

    //20230211 lujunxia add e
    /**
     * undocumented function
     *
     * @return
     * @author
     */
    public function to_pdf()
    {
        // Include the main TCPDF library (search for installation path).
        //2013-12-19 qiuqiu modify start
        // $tcpdf_file = $this -> file_path . "/" . 'tcpdf/tcpdf.php';
        $tcpdf_file = str_replace("HMAUD/Component", "Component", $this->file_path) . "/" . 'tcpdf/tcpdf.php';
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
        foreach ($this->datas as $key => $value) {
            $this->mode = $value["mode"];
            //20241114 lujunxia upd s
            //集計種類
            $this->summery = $value["summery"];
            $datas = $value["data"];
            //$cnt_datas = count($datas);
            //for ($i = 0; $i < $cnt_datas; $i++) {
            foreach ($datas as $each_data) {
                foreach ($this->rpx_file_names as $rpx_file_name => $data_fields) {
                    if (strpos($rpx_file_name, $key) !== false) {
                        $this->data_fields = $data_fields;
                        $this->rpx_file_name = $rpx_file_name;
                        //---20151230 li UPD S.
                        // $this -> draw_pdf($pdf, $rpx_file_name, $datas[$i]);
                        //20230213 lujunxia add s
                        //if (isset($datas[$i])) {
                        if (isset($each_data)) {
                            //给指摘数多的数据涂色用
                            // $this->bgColorData = $datas[$i]['color'];
                            // $this->draw_pdf($pdf, $datas[$i]);
                            //20241114 caina upd s
                            if ($this->summery === 'cumulative_issue_table' || $this->summery === 'consecutive_issue_table') {
                                $this->bgColorData = $each_data['color'];
                            }
                            //20241114 caina upd e
                            $this->draw_pdf($pdf, $each_data);
                            //20241114 lujunxia upd e
                        }
                        //20230213 lujunxia add e
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

        // 20240422 caina upd s
        // $output_file = $this->REPORTS_TEMP_PATH . $file_name;
        $output_file = $this->REPORTS_TEMP_PATH . $file_name;
        // 20240422 caina upd e
        return $output_file;
    }

    //**********************************
    //fan add start
    //**********************************
    public function to_pdf2($documentType = "A3")
    {
        // Include the main TCPDF library (search for installation path).
        //2013-12-19 qiuqiu modify start
        $tcpdf_file = str_replace("R4/Component", "Component", $this->file_path) . "/" . 'tcpdf/tcpdf.php';
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
            //---20151230 li DEL S.
            // if($this -> rpx_file_name  = "rptScUriageChk"){
            // $this->DateFrom = $value["DateFrom"];
            // $this->DateTo =  $value["DateTo"];
            // }
            //---20151230 li DEL E.
            //---20151111  Yuanjh  ADD E.

            $datas = $value["data"];
            $cnt_datas = count($datas);
            for ($i = 0; $i < $cnt_datas; $i++) {
                foreach ($this->rpx_file_names as $rpx_file_name => $data_fields) {
                    //---20151230 li INS S.
                    if ($rpx_file_name == "rptScUriageChk") {
                        $this->DateFrom = $value["DateFrom"];
                        $this->DateTo = $value["DateTo"];
                    }
                    //---20151230 li INS E.
                    if (strpos($rpx_file_name, $key) !== false) {
                        $this->data_fields = $data_fields;
                        $this->rpx_file_name = $rpx_file_name;
                        //---20151230 li UPD S.
                        // $this -> draw_pdf($pdf, $rpx_file_name, $datas[$i]);
                        $this->draw_pdf($pdf, $datas[$i]);
                        //---20151230 li UPD E.
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

    //**********************************
    //fan add end
    //**********************************

    //20161008 YIN INS S
    /**
     * undocumented function
     *
     * @return
     * @author
     */
    public function to_pdf3()
    {
        // Include the main TCPDF library (search for installation path).
        //2013-12-19 qiuqiu modify start
        // $tcpdf_file = $this -> file_path . "/" . 'tcpdf/tcpdf.php';
        $tcpdf_file = str_replace("R4/Component", "Component", $this->file_path) . "/" . 'tcpdf/tcpdf.php';
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

        foreach ($this->alldatas as $key1 => $value1) {
            $this->rpx_file_names = $value1['rpx_file_names'];
            $this->datas = $value1['datas'];
            foreach ($this->datas as $key => $value) {
                $this->mode = $value["mode"];
                $datas = $value["data"];
                $cnt_datas = count($datas);
                for ($i = 0; $i < $cnt_datas; $i++) {
                    foreach ($this->rpx_file_names as $rpx_file_name => $data_fields) {
                        if (strpos($rpx_file_name, $key) !== false) {
                            $this->data_fields = $data_fields;
                            $this->rpx_file_name = $rpx_file_name;
                            //---20151230 li UPD S.
                            // $this -> draw_pdf($pdf, $rpx_file_name, $datas[$i]);
                            $this->draw_pdf($pdf, $datas[$i]);
                            //---20151230 li UPD E.
                        }
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

    //20161008 YIN INS E

    /**
     * draw_pdf
     *
     * @return void
     * @author
     */
    //---20151230 li UPD S.
    // public function draw_pdf($pdf, $rpx_file_name, $data)
    public function draw_pdf($pdf, $data)
    //---20151230 li UPD E.
    {
        //20230211 lujunxia add s
        if (!(is_readable($this->file_path) && is_writable($this->file_path))) {
            throw new Exception('フォルダのパーミッションはエラーが発生しました。');
        }
        //---20151230 li UPD S.
        if ($this->summery !== 'cumulative_issue_table' && $this->summery !== 'consecutive_issue_table') {
            $rpx_file = $this->file_path . "/" . $this->rpx_file_name . ".rpx";
        } else {
            $rpx_file_yuan = $this->file_path . "/" . $this->rpx_file_name . ".rpx";
            //---20151230 li UPD E.
            $rpx_file = $this->writeToFile($rpx_file_yuan, $data['data'][0]['header']);
        }
        //20230211 lujunxia add e
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
                            //20240514 YIN DEL S
                            // case "MS PGothic":
                            // 	$this->font_family = $this->FONT_FAMILY_GOTHICP;
                            // 	break;
                            //20240514 YIN DEL E
                            case "ＭＳ 明朝":
                                $this->font_family = $this->FONT_FAMILY_MINCHO;
                                break;
                            case "MS UI Gothic":
                                $this->font_family = $this->FONT_FAMILY_GOTHICUI;
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
                    //20230216 lujunxia upd s
                    $cnt = count($data['data']);

                    $basePageNum = ceil($this->totalKyotenCount / $this->KyotenMaxNum);
                    for ($ib = 0; $ib < $basePageNum; $ib++) {
                        $this->baseStart = $ib == 0 ? 0 : $this->baseEnd + 1;
                        $this->baseEnd = $ib == $basePageNum - 1 ? $this->totalKyotenCount - 1 : ($ib + 1) * $this->KyotenMaxNum - 1;
                        $this->draw_except_detail($pdf, $Sections, $data, '', '', '');
                        $this->get_detail_start_top_margin($Sections, "Detail");

                        for ($i = 0; $i < $cnt; $i++) {
                            if ($this->rpx_file_name != 'rptMicheckIchiran') {
                                //线的宽度
                                if (($i + 1) % 13 == 0) {
                                    $data['data'][$i]['end'] = 1;
                                } else {
                                    $data['data'][$i]['end'] = 0;
                                }
                                //20220107 zhangbowen INS S
                                //逆数2ページが12行しかない場合は、底辺線が太くなります
                                if ($i + 1 == $cnt) {
                                    if (($i + 1) % 13 == 12) {
                                        $data['data'][$i]['end'] = 1;
                                    }
                                }
                                //20220107 zhangbowen INS E
                            }

                            //Detail:before draw shap,label and field,get max 'Height'
                            $this->getChangedHeight($pdf, $Sections, $data['data'][$i]);

                            $data['data'][$i] = array_merge($this->data_fields, $data['data'][$i]);

                            $this->draw_detail($pdf, $Sections, $data['data'][$i], $data['data'][$i], $i);
                        }
                        //20230211 lujunxia add s
                        //footer
                        $y = $pdf->GetY() + $this->twip_to_millimeter($this->lineHeight);
                        $h_row = $this->twip_to_millimeter(283);
                        for ($j = 0; $j < 3; $j++) {
                            $y = $j == 0 ? $y : $y + 5;
                            $pdf->SetXY($this->twip_to_millimeter($this->footerLeft) - 26.65, $y);
                            $pdf->SetFillColor(220, 220, 220);
                            if ($j == 0) {
                                //合計
                                $pdf->Cell(26.65, $h_row, '合計', $border = 1, $ln = 0, $align = 'R', $fill = 1, $link = '', $stretch = 0);
                            }
                            if ($j == 1) {
                                $pdf->SetFont($this->FONT_FAMILY_GOTHIC, '', 5);
                                //実施度合<br>（全29項目）
                                $pdf->MultiCell(26.65, $h_row, '実施度合<br>（全' . $cnt . '項目）', $border = 1, $align = 'R', $fill = 1, $ln = 0, $x = $this->twip_to_millimeter($this->footerLeft) - 26.65, $y = $y, $reseth = true, $stretch = 0, $ishtml = true, $autopadding = true, $maxh = 0);
                            }
                            if ($j == 2) {
                                $pdf->SetFont($this->FONT_FAMILY_GOTHIC, '', 5);
                                $h_row = $this->twip_to_millimeter(220);
                                //ランク（拠点）
                                $pdf->Cell(26.65, $h_row, 'ランク（拠点）', $border = 0, $ln = 0, $align = 'C', $fill = 0, $link = '', $stretch = 0);
                            }
                            $pdf->SetXY($this->twip_to_millimeter($this->footerLeft), $y);
                            $border = $j == 2 ? 0 : 1;
                            for ($k = 0; $k < $this->totalKyotenCount; $k++) {
                                if ($k >= $this->baseStart && $k <= $this->baseEnd) {
                                    $fillFlg = 0;
                                    if (in_array($k, $this->bgColorData['detail_first'])) {
                                        $pdf->SetFillColor(192, 80, 77);
                                        $fillFlg = 1;
                                    } else
                                        if (in_array($k, $this->bgColorData['detail_second'])) {
                                            $pdf->SetFillColor(230, 184, 183);
                                            $fillFlg = 1;
                                        }
                                    //合計
                                    if ($j == 0) {
                                        $pdf->SetFont($this->FONT_FAMILY_GOTHIC, '', 6.8);
                                        $value = $data['footer'][$k]['total'];
                                        $pdf->Cell($this->twip_to_millimeter(400), $h_row, $value, $border, $ln = 0, 'C', $fill = $fillFlg, '', 0);
                                    } elseif ($j == 1) {
                                        $pdf->SetFont($this->FONT_FAMILY_GOTHIC, '', 6.8);
                                        //実施度合
                                        $value = $data['footer'][$k]['percent'];
                                        $pdf->Cell($this->twip_to_millimeter(400), $h_row, $value, $border, $ln = 0, 'C', $fill = $fillFlg, '', 0);
                                    } else {
                                        $pdf->SetFont($this->FONT_FAMILY_GOTHIC, '', 5);
                                        //ランク（拠点）
                                        $value = $data['footer'][$k]['rank'];
                                        $pdf->Cell($this->twip_to_millimeter(400), $h_row, $value, $border, $ln = 0, 'C', $fill = 0, '', 0);
                                    }
                                    $pdf->SetXY($pdf->GetX(), $y);
                                }
                            }
                        }
                        //20230211 lujunxia add e
                    }
                    //数据只有一行的时候，会多加空行的数据？
                    // if ($this -> rpx_file_name != 'rptMicheckIchiran')
                    // {
                    // for ($i = $cnt; $i < 10; $i++)
                    // {
                    // $this -> draw_detail($pdf, $Sections, '', '');
                    // }
                    // }
                    // $this -> detail_is_drowed = true;
                    // $this -> draw_except_detail($pdf, $Sections, $data);
                    // $this -> detail_is_drowed = false;
                    //20230216 lujunxia upd e
                    break;
                case "3":
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
                        $data[$i] = array_merge($this->data_fields, $data[$i]);
                        if ($i == 0) {
                            $this->draw_except_detail($pdf, $Sections, $data[$i]);
                        }
                        //$this -> draw_detail($pdf, $Sections, $data[$i]);
                        $this->draw_detail($pdf, $Sections, $data[$i], $data[$i]);
                        $i++;
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

                    //print_r($data);
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
                    $cnt = count($data);
                    $cnt1 = count($data[$cnt - 1]);
                    $i = 0;
                    $j = 0;
                    $this->draw_except_detail($pdf, $Sections, $data[0]);

                    $current = "";
                    $last = "";
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
                        } else {
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
                    $this->draw_except_detail($pdf, $Sections, $data[$cnt - 1][$j]);
                    $this->tempmark = true;
                    $this->draw_except_detail($pdf, $Sections, $data[$cnt - 1][$cnt1 - 1]);
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
                    $cnt = count($data);
                    $i = 0;
                    while ($i < $cnt - 1) {
                        if (($i % 43) == 0) {
                            $this->draw_except_detail($pdf, $Sections, $data[0]);
                            $this->get_detail_start_top_margin($Sections, "GroupHeader2");
                            $this->TopMargin_current = $this->TopMargin_current + 0.5;
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
                // 20241114 YIN INS S
                case "13":
                    // $data = array_merge($this->data_fields, $data);
                    $this->draw_except_detail($pdf, $Sections, $data);
                    if (count($data['data']) > 0) {
                        foreach ($data['data'] as $key => $value) {
                            $this->getChangedHeight($pdf, $Sections, $value);
                            $this->draw_detail($pdf, $Sections, $data, $value);
                        }
                    } else {
                        $this->draw_detail($pdf, $Sections, $data, $this->data_fields);
                    }
                    $this->detail_is_drowed = true;
                    break;
                // 20241114 YIN INS E
                // 20241114 caina ins s
                case "14":
                    $data = array_merge($this->data_fields, $data);
                    $this->draw_except_detail($pdf, $Sections, $data);

                    if ($this->rpx_file_name == 'rptJissekiIssue') {
                        $y = 2;
                        $h_row = $this->twip_to_millimeter(18.75);
                        $pdf->SetFont($this->FONT_FAMILY_GOTHIC, '', 11);
                        foreach ($data as $key => $value) {
                            if (is_numeric($key)) {
                                $columns_groups = array(
                                    array(
                                        'columns' => array(
                                            array('field' => 'KYOTEN_NAME'),
                                            array('field' => 'CHECK_COUNT'),
                                            array('field' => 'PRECOUNT'),
                                            array('field' => 'COMCOUNT')
                                        ),
                                        'x_offset' => 6.17,
                                        'gap' => 15.22
                                    ),
                                    array(
                                        'columns' => array(
                                            array('field' => 'KYOTEN_NAME_0'),
                                            array('field' => 'CHECK_COUNT_0'),
                                            array('field' => 'PRECOUNT_0'),
                                            array('field' => 'COMCOUNT_0')
                                        ),
                                        'x_offset' => 5.3,
                                        'gap' => 15.22
                                    ),
                                    array(
                                        'columns' => array(
                                            array('field' => 'KYOTEN_NAME_1'),
                                            array('field' => 'CHECK_COUNT_1'),
                                            array('field' => 'PRECOUNT_1'),
                                            array('field' => 'COMCOUNT_1')
                                        ),
                                        'x_offset' => 5.3,
                                        'gap' => 15.22
                                    ),
                                    array(
                                        'columns' => array(
                                            array('field' => 'KYOTEN_NAME_2'),
                                            array('field' => 'CHECK_COUNT_2'),
                                            array('field' => 'PRECOUNT_2'),
                                            array('field' => 'COMCOUNT_2')
                                        ),
                                        'x_offset' => 5.3,
                                        'gap' => 15.22
                                    )
                                );

                                $x = 18.5;
                                $y_position = 32 + $y;

                                foreach ($columns_groups as $group) {
                                    foreach ($group['columns'] as $key => $column) {
                                        $pdf->SetXY($x, $y_position);
                                        $pdf->Cell(15.2, $h_row, $value[$column['field']], 0, 0, $key == 0 ? 'C' : 'R', 0, '', 1);
                                        // 右縦線の描画
                                        $pdf->Line($x + 15.2, $y_position, $x + 15.2, $y_position + 5);
                                        // 左の縦線を描画するには
                                        $pdf->Line($x, $y_position, $x, $y_position + 5);
                                        // 下部の横線を描画するには
                                        $pdf->Line($x, $y_position + 5, $x + 15.2, $y_position + 5);
                                        if ($value[$column['field']] === '') {
                                            $pdf->Line($x, $y_position, $x + 15.2, $y_position + 5);
                                        }
                                        $x += $group['gap'];
                                    }
                                    $x += $group['x_offset'];
                                }

                                $y += 5;
                            }
                            $total_y_position = $y; // 特定のレンダリング後のy値を記録する
                            $tmp_TopMargin_current = $this->TopMargin_current + $this->BottomMargin + $total_y_position;

                            if ($tmp_TopMargin_current + 30 > $pdf->getPageHeight()) {
                                $this->temppagemark = true;
                                $this->draw_except_detail($pdf, $Sections, $data);
                                $y = 2;
                                $this->temppagemark = false;
                            }
                        }

                    }
                    $this->detail_is_drowed = true;
                    $this->TopMargin_current = $this->TopMargin_current + $total_y_position;
                    $this->draw_except_detail($pdf, $Sections, $data);
                    $this->detail_is_drowed = false;
                    break;
                // 20241114 caina ins e
                //fan add e.
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
    //20220105 WL UPD S
    //public function draw_except_detail($pdf, $Sections, $data, $PageSettings = "", $tmpSections = '', $tmpData_0 = '', $newbusyo = false)
    public function draw_except_detail($pdf, $Sections, $data, $PageSettings = "", $tmpSections = '', $tmpData_0 = '')
    //20220105 WL UPD E
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

                $tmpCodeFile = substr($this->ActiveReportsLayout['CodeFile'], 0, count($this->ActiveReportsLayout['CodeFile']) - 3);

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
            //yushuangji add end
            //fan add s.
            if ($this->mode == "5" && $this->temppagemark == true) {
                $this->TopMargin_current = 0;
                $this->TopMargin_current = $this->TopMargin_current + $this->TopMargin;
                $pdf->AddPage($this->Orientation);
                $this->pageNumber++;
            }
        } else {
            $this->TopMargin_current = 0;
            $this->TopMargin_current = $this->TopMargin_current + $this->TopMargin;
            $pdf->AddPage($this->Orientation);
            $this->pageNumber++;
            //20220105 WL DEL S
            //if ($newbusyo == true)
            //{
            //	$this -> pageNumber = 1;
            //}
            //20220105 WL DEL E
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
                // 20241114 caina upd s
                // if (!($this->mode == "1" || $this->mode == "4" || $this->mode == "3" || $this->mode == "5" || $this->mode == "6" || $this->mode == "7" || $this->mode == "8" || $this->mode == "9" || $this->mode == "10" || $this->mode == "11" || $this->mode == "12")) {
                if (!($this->mode == "1" || $this->mode == "4" || $this->mode == "3" || $this->mode == "5" || $this->mode == "6" || $this->mode == "7" || $this->mode == "8" || $this->mode == "9" || $this->mode == "10" || $this->mode == "11" || $this->mode == "12" || $this->mode == "13" || $this->mode == "14")) {
                    // 20241114 caina upd e
                    $this->draw_control($pdf, $Section, $data);
                }

                //20140320 yushuangji edit start
                if ($this->detail_is_drowed) {
                    if ($this->mode == "1" && $attr_Section->Name == "GroupFooter1") {

                        $tmp_TopMargin_current = $this->TopMargin_current + $this->BottomMargin;
                        $tmp_TopMargin_current = $tmp_TopMargin_current + $this->twip_to_millimeter($attr_Section->Height);
                        if ($tmp_TopMargin_current > $pdf->getPageHeight()) {
                            $this->temppagemark = true;
                            $this->detail_is_drowed = false;
                            //20220106 zhangbowen DEL S
                            //$data[0]['end'] = 2;
                            //20220106 zhangbowen DEL E
                            $this->draw_except_detail($pdf, $Sections, $data);
                            $this->detail_is_drowed = true;
                        }
                        $this->TopMargin_current = $this->TopMargin_current - 15;
                        $this->draw_control($pdf, $Section, $data);
                        //20220106 zhangbowen INS S
                        if ($this->rpx_file_name == "rptDenpyoinsatu2") {
                            //仕訳伝票 最後のページの最後の部分
                            $tmp_TopMargin_current = $this->TopMargin_current + $this->BottomMargin;
                            $tmp_TopMargin_current = $tmp_TopMargin_current + $this->twip_to_millimeter($attr_Section->Height);
                            //バーコードのスペースが足りない場合
                            if ($tmp_TopMargin_current > $pdf->getPageHeight() - 10) {
                                $data['BarCodeExcess'] = 'PageHeader';
                                $this->detail_is_drowed = false;
                                $this->draw_except_detail($pdf, $Sections, $data);
                                $this->detail_is_drowed = true;
                            }
                        }
                        //20220106 zhangbowen INS E
                    }

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
                        $this->draw_control($pdf, $Section, $data);
                    }
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
                    //fan add e.
                    // 20241114 YIN INS S
                    if ($this->mode == "13" && $attr_Section->Name == "GroupHeader1") {
                        $tmp_TopMargin_current = $this->TopMargin_current + $this->BottomMargin;
                        $this->lineHeight = 250;
                        if (isset($data['data'][0])) {
                            $this->getChangedHeight($pdf, $Sections, $data['data'][0]);
                        }
                        $tmp_TopMargin_current = $tmp_TopMargin_current + $this->twip_to_millimeter($attr_Section->Height + $this->lineHeight);
                        if ($tmp_TopMargin_current > $pdf->getPageHeight()) {
                            $this->detail_is_drowed = false;
                            $this->draw_except_detail($pdf, $Sections, $data);
                            $this->detail_is_drowed = true;
                        } else {
                            $this->draw_control($pdf, $Section, $data);
                        }
                    }
                    // 20241114 YIN INS E
                    // 20241114 caina ins s
                    if ($this->mode == "14" && $attr_Section->Name == "GroupFooter1") {
                        $this->TopMargin_current = $this->TopMargin_current + 22;
                        $this->draw_control($pdf, $Section, $data);
                    }
                    // 20241114 caina ins e
                } else {
                    if ($this->mode == "1" && $attr_Section->Name != "GroupFooter1") {
                        //20220106 zhangbowen UPD S
                        //最後のページにバーコードのみの場合、ヘッドを追加
                        if (isset($data['BarCodeExcess']) && $data['BarCodeExcess'] == 'PageHeader') {
                            if ($attr_Section->Name == 'PageHeader') {
                                $this->draw_control($pdf, $Section, $data);
                            }
                        } else {
                            $this->draw_control($pdf, $Section, $data);
                        }
                        //20220106 zhangbowen UPD E
                    }
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
                    if ($this->mode == "12" && ($attr_Section->Name == "PageHeader" || $attr_Section->Name == "GroupHeader1" || $attr_Section->Name == "GroupHeader2")) {

                        $this->draw_control($pdf, $Section, $data);

                    }
                    //fan add e.
                    // 20241114 YIN INS S
                    if ($this->mode == "13") {
                        $this->draw_control($pdf, $Section, $data);
                    }
                    // 20241114 YIN INS E
                    // 20241114 caina ins s
                    if ($this->mode == "14" && $attr_Section->Name != "GroupFooter1") {
                        $this->draw_control($pdf, $Section, $data);
                    }
                    // 20241114 caina ins e
                }
                //20140320 yushuangji edit end
            }
            //2014-01-06 qiuqiu update end
            //2013-11-29 zhenghuiyun insert start
            else {
                // 20241114 YIN UPD S
                // if ($this->mode != "3") {
                if ($this->mode != "3" && $this->mode != "13") {
                    // 20241114 YIN UPD E

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
    //20220213 lujunxia upd s
    public function draw_detail($pdf, $Sections, $except_detail_data, $data, $row_no = 0)
    //20220213 lujunxia upd e
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
                    }
                    // 20241114 YIN INS S
                    elseif ($this->mode == "13") {
                        $tmp_TopMargin_current = $this->TopMargin_current + $this->BottomMargin;
                        $tmp_TopMargin_current = $tmp_TopMargin_current + $this->twip_to_millimeter($this->lineHeight);
                        if ($tmp_TopMargin_current > $pdf->getPageHeight()) {
                            $this->detail_is_drowed = false;
                            $this->draw_except_detail($pdf, $Sections, $except_detail_data);
                            $this->detail_is_drowed = true;
                        }
                    }
                    // 20241114 YIN INS E
                    else {
                        $tmp_TopMargin_current = $this->TopMargin_current + $this->BottomMargin;
                        //20230215 lujunxia upd s
                        //$tmp_TopMargin_current = $tmp_TopMargin_current + $this -> twip_to_millimeter($attr_Section -> Height);
                        //计算[何时换页]的height，应该用改变后的行高来计算(再加上 最后三个合计行，如果本页写不下就一起到下一页)
                        $tmp_TopMargin_current = $tmp_TopMargin_current + $this->twip_to_millimeter($this->lineHeight) + 8.5;
                        //20230215 lujunxia upd e
                        if ($tmp_TopMargin_current > $pdf->getPageHeight()) {
                            $this->draw_except_detail($pdf, $Sections, $data);
                            if ($this->rpx_file_name == "rptZandakaMeisai") {
                                $this->TopMargin_current = $this->TopMargin_current - 5;
                            }
                            if ($this->rpx_file_name == "rptDenpyoinsatu" || $this->rpx_file_name == "rptDenpyoinsatu2") {
                                $this->TopMargin_current = $this->TopMargin_current - 18;
                            }
                            if ($this->rpx_file_name == "rptMicheckIchiran") {
                                //20220105 WL UPD S
                                //$this -> TopMargin_current = $this -> TopMargin_current - 10;
                                $this->TopMargin_current = $this->TopMargin_current - 9.5;
                                //20220105 WL UPD E
                            }
                            //20230211 lujunxia add s
                            if ($this->rpx_file_name == "rptJisseki") {
                                //第二页的detail距离title的距离
                                $this->TopMargin_current = $this->TopMargin_current - 27.9;
                                //20220105 WL UPD E
                            }
                            //20230211 lujunxia add e
                        }
                    }
                }
                //20230213 lujunxia upd s
                $this->draw_control($pdf, $Section, $data, $row_no);
                //20230213 lujunxia upd e
            }
        }
    }

    public function draw_detail1($pdf, $Sections, $data)
    {
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
                //print_r($data);
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
    //20230213 lujunxia upd s
    public function draw_control($pdf, $Section, $data, $row_no = 0)
    //20230213 lujunxia upd e
    {
        //获取各区域的对象 s

        $attr_Section = $Section->attributes();

        //获取各区域的对象 e

        if ($attr_Section->Height > 0) {
            foreach ($Section->children() as $Control) {
                $attr = $Control->attributes();
                switch ($attr->Type) {
                    case "AR.Field":
                        //20230213 lujunxia upd s
                        //内部統制実績集計専用
                        if ($attr_Section->Name == 'Detail') {
                            //reset height
                            $attr->Height = $this->lineHeight;
                        }

                        $this->draw_field($pdf, $attr, $data, $row_no);
                        //20230213 lujunxia upd e
                        break;
                    case "AR.Label":
                        $this->draw_label($pdf, $attr, $data);
                        break;
                    case "AR.Line":
                        $this->draw_line($pdf, $attr, $data);
                        break;
                    case "AR.Shape":
                        //20230213 lujunxia upd s
                        //内部統制実績集計専用
                        if ($attr_Section->Name == 'Detail') {
                            //reset height
                            $attr->Height = $this->lineHeight;
                        }

                        $this->draw_shape($pdf, $attr, $row_no);
                        //20230213 lujunxia upd e
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
        //20230211 lujunxia add s
        if ($attr_Section->Name == 'Detail') {
            //由于行高根据数据而变化，top margin也需要改变
            $tmp_height = $this->twip_to_millimeter($this->lineHeight);
        } else {
            //20230211 lujunxia add e
            $tmp_height = $this->twip_to_millimeter($attr_Section->Height);
        }
        $this->TopMargin_current = $this->TopMargin_current + $tmp_height;
        //2013-11-29 zhenghuiyun insert end
    }

    /**
     * undocumented function
     *
     * @return void
     * @author
     */
    //20230213 lujunxia upd s
    public function draw_field($pdf, $attr, $data, $row_no = 0)
    //20230213 lujunxia upd e
    {
        $Visible = "";
        if ($attr->Visible != null) {
            $Visible = $attr->Visible;
            $Visible = (string) $Visible;

            if ($Visible == "0") {
                return;
            }
        }
        //get Style s
        $Style = $this->get_style_attributes($attr->Style);
        // 20241114 caina ins s
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
        // 20241114 caina ins e
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
        //+100;
        $w = $this->get_w($attr);
        $h = $this->get_h($attr);

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
        //20220105 LUJUNXIA UPD S
        if ($this->rpx_file_name == "rptShiharaiDenpyo2" || $this->rpx_file_name == "rptShiharaiDenpyo") {
            //データの左側のpadding
            $pdf->setCellPaddings(0.7, 0, 0, 0);
        } else {
            $pdf->setCellPaddings(0, 0, 0, 0);
        }
        //20220105 LUJUNXIA UPD E
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
                //20240514 YIN DEL S
                // case "MS PGothic":
                // 	$this->font_family = $this->FONT_FAMILY_GOTHICP;
                // 	break;
                //20240514 YIN DEL E
                case "ＭＳ 明朝":
                    $font_family = $this->FONT_FAMILY_MINCHO;
                    break;
                case "MS UI Gothic":
                    $font_family = $this->FONT_FAMILY_GOTHICUI;
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

        //20220106 zhangbowen UPD S
        if ($this->mode == "1") {
            //「借方科目」と「貸方科目」の幅が広すぎる問題対応
            if ($this->rpx_file_name == "rptDenpyoinsatu2" || $this->rpx_file_name == "rptDenpyoinsatu") {
                if (isset($data[$DataField])) {
                    if ($DataField == "R_KAMOKU" || $DataField == "L_KAMOKU") {
                        if (strlen($data[$DataField]) >= 36) {
                            $font_size = "8.5";
                        } elseif (strlen($data[$DataField]) >= 33) {
                            $font_size = "9";
                        } elseif (strlen($data[$DataField]) >= 30) {
                            $font_size = "10";
                        }
                    }
                }
            }
        }
        //20220106 zhangbowen UPD E
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
        if ($DataField != "" && $data != "") {
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

            //20220105 LUJUNXIA INS S
            // 行の数
            $MultirowNum = "1";
            if ($attr->MultirowNum != null) {
                $MultirowNum = $attr->MultirowNum;
            }
            //20220105 LUJUNXIA INS E
            //2013-11-28 18:57 yuanquan update start
            if (($val != "" && $val != null) || is_integer($val)) {

                if ($attr->OutputFormat != null && $attr->OutputFormat != "") {

                    //if ($DataField != "txtOsiharaikin") {
                    if ($DataField != "LEASE_RYO_RT") {
                        $val = (int) $val;

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
                        default:
                            break;
                    }
                    //}

                }
            }
            if ($Multiline == "1") {
                $y = $y - 0.5;
                $pdf->SetXY($x, $y);
                //20230213 lujunxia upd s

                if ($attr->Name == 'RANKField') {
                    //the last page
                    if ($this->baseEnd == $this->totalKyotenCount - 1) {
                        $left = ($this->baseEnd - $this->baseStart + 1) * 400 + 720 + 300;
                        $x = $this->get_x($attr) + $this->twip_to_millimeter($left);
                        $pdf->SetXY($x, $y);
                        $pdf->Cell($w, $h, $val, 0, $ln = 0, 'R', 0, '', 0, false, 'T', 'C');
                    }
                } else
                    if ($attr->Name == 'KYOTENField' || $attr->Name == 'TOTALField' || $attr->Name == 'PERCENTField') {
                        if ($attr->Name == 'TOTALField' || $attr->Name == 'PERCENTField') {
                            //the last page
                            if ($this->baseEnd == $this->totalKyotenCount - 1) {
                                $left = ($this->baseEnd - $this->baseStart + 1) * 400;
                                if ($attr->Name == 'PERCENTField') {
                                    $left = $left + 300;
                                }
                                $x = $this->get_x($attr) + $this->twip_to_millimeter($left);
                                $pdf->SetXY($x, $y);

                                $bgColor = 0;
                                if (in_array($row_no, $this->bgColorData['kyoten_first'])) {
                                    $pdf->SetFillColor(192, 80, 77);
                                    $bgColor = 1;
                                } else
                                    if (in_array($row_no, $this->bgColorData['kyoten_second'])) {
                                        $pdf->SetFillColor(230, 184, 183);
                                        $bgColor = 1;
                                    }
                                $pdf->Cell($w, $h, $val, 1, $ln = 0, 'C', $bgColor, '', 0);
                            }
                        } else {
                            //KYOTENField
                            if ($attr->Ktnumber >= $this->baseStart && $attr->Ktnumber <= $this->baseEnd) {
                                $left = ($attr->Ktnumber % $this->KyotenMaxNum) * 400;
                                $x = $this->get_x($attr) + $this->twip_to_millimeter($left);
                                $pdf->SetXY($x, $y);
                                //20241114 lujunxia ins s
                                if ($this->summery === 'cumulative_issue_table') {
                                    //指摘事項表（累計）
                                    $num_val = (int) $val > 0 ? $val : '';
                                    $pdf->Cell($w, $h, $num_val, 1, $ln = 0, 'C', 0, '', 0, false, 'T', 'C');
                                } else if ($this->summery === 'consecutive_issue_table') {
                                    //指摘事項表（連続）
                                    //20241114 lujunxia ins e
                                    if ($val == 1) {
                                        // $pdf->Cell($w, $h, "×", 1, $ln = 0, 'C', 0, '', 0, false, 'T', 'C');
                                        $pdf->Cell($w, $h, $val, 1, $ln = 0, 'C', 0, '', 0, false, 'T', 'C');
                                    } else
                                        if ($val == 2) {
                                            //yellow
                                            $pdf->SetFillColor(255, 255, 2);
                                            // $pdf->Cell($w, $h, "×", $border = 1, $ln = 0, $align = 'C', $fill = 1, $link = '', $stretch = 1);
                                            $pdf->Cell($w, $h, $val, $border = 1, $ln = 0, $align = 'C', $fill = 1, $link = '', $stretch = 1);
                                        } else
                                            if ($val >= 3) {
                                                //red
                                                $pdf->SetFillColor(255, 0, 2);
                                                // $pdf->Cell($w, $h, "×", $border = 1, $ln = 0, $align = 'C', $fill = 1, $link = '', $stretch = 1);
                                                $pdf->Cell($w, $h, $val, $border = 1, $ln = 0, $align = 'C', $fill = 1, $link = '', $stretch = 1);
                                            } else {
                                                $pdf->Cell($w, $h, "", 1, $ln = 0, 'C', 0, '', 0, false, 'T', 'C');
                                            }
                                }
                            }
                        }
                        //20241114 caina ins s
                    } else if (($this->summery === 'issue_ranking' || $this->summery === 'cumulative_multiple_issue_ranking' || $this->summery === 'consecutive_multiple_issue_ranking') && $attr->Name == 'TITLE') {
                        if ($attr->DataField == 'TITLE3') {
                            $pdf->Cell($w, $h, $val, 0, 0, $text_align, 0, '', 0, false, 'T', 'C');
                        } else {
                            $pdf->Cell($w, $h, $val, 0, 0, $text_align, 1, '', 0, false, 'T', 'C');
                        }
                    }//20241114 caina ins e
                    // 20241114 YIN INS S
                    else if (($this->summery == 'issue_ranking_per_territory' || $this->summery == 'cumulative_multiple_issue_ranking_per_territory')) {
                        if ($attr->Name == 'TITLE') {
                            $val = $this->summery == 'issue_ranking_per_territory' ? '各領域ごと指摘項目ランキング' : '各領域ごと複数回指摘項目ランキング';
                            $pdf->SetFillColor($background_color["red"], $background_color["green"], $background_color["blue"]);
                            $pdf->Cell($w, $h, $val, $border = 0, $ln = 0, $align = 'C', $fill = 1, $link = '', $stretch = 1);
                        } else if ($attr->Name == 'TERRITORYName') {
                            $val = $data['territory_name'];
                            $background_color = $this->hex2RGB($data['territory_color']);
                            $pdf->SetFillColor($background_color["red"], $background_color["green"], $background_color["blue"]);
                            $pdf->Cell($w, $h, $val, $border = 0, $ln = 0, $text_align, $fill = 1, $link = '', $stretch = 1);
                        } else if ($attr->Name == 'COLUMN7' || $attr->Name == 'CHECK_LST_ID') {
                            $pdf->MultiCell($w, $h, $val, $border = 0, $text_align, 0, 0, '', '', true, 0, false, true, $h, $vertical_align, true);
                        } else {
                            $pdf->Cell($w, $h, $val, $border = 0, $ln = 0, $text_align, $fill = 0, $link = '', $stretch = 1);
                        }
                    }
                    // 20241114 YIN INS E
                    else {
                        $pdf->MultiCell($w, $h, $val, $border = 0, $text_align, 0, 0, '', '', true, 0, false, true, '', $vertical_align, true);
                    }
                //20230213 lujunxia upd e
            } else {
                $pdf->Cell($w, $h, $val, 0, $border = 0, $text_align, 0, '', 0, false, 'T', $vertical_align);
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
                //20240514 YIN DEL S
                // case "MS PGothic":
                // 	$this->font_family = $this->FONT_FAMILY_GOTHICP;
                // 	break;
                //20240514 YIN DEL E
                case "ＭＳ 明朝":
                    $font_family = $this->FONT_FAMILY_MINCHO;
                    break;
                case "MS UI Gothic":
                    $font_family = $this->FONT_FAMILY_GOTHICUI;
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
        //20220106 zhangbowen UPD S
        //仕訳伝票専用
        // if ($this -> rpx_file_name == "rptDenpyoinsatu2" || $this -> rpx_file_name == "rptDenpyoinsatu")
        // {
        // //head部分マージン
        // $pdf -> setCellPaddings(1, 0, 1, 0);
        // $paddings = $pdf -> getCellPaddings();
        // if ($text_align == "L")
        // {
        // $pdf -> setCellPaddings($paddings['L'], 0, 0, 0);
        // }
        // elseif ($text_align == "R")
        // {
        // $pdf -> setCellPaddings(0, 0, $paddings['R'], 0);
        // }
        // }
        //20230211 lujunxia add s
        if ($attr->Name == 'KYOTENLabel') {
            if ($attr->Ktnumber >= $this->baseStart && $attr->Ktnumber <= $this->baseEnd) {
                $left = ($attr->Ktnumber % $this->KyotenMaxNum) * 400;
                $x = $this->get_x($attr) + $this->twip_to_millimeter($left);
                $pdf->SetXY($x, $y);
                $pdf->Cell($w, $h, $Caption, $border = 1, $ln = 0, $align = 'C', $fill = 1, $link = '', $stretch = 1);
            }
        } else
            if ($attr->Name == 'KYOTENTotal') {
                //the last page
                if ($this->baseEnd == $this->totalKyotenCount - 1) {
                    $left = ($this->baseEnd - $this->baseStart + 1) * 400;
                    $x = $this->get_x($attr) + $this->twip_to_millimeter($left);
                    $pdf->SetXY($x, $y);
                    $pdf->Cell($w, $h, $Caption, $border = 1, $ln = 0, $align = 'C', $fill = 1, $link = '', $stretch = 1);
                }
            } else
                if ($attr->Name == 'KYOTENRank' || $attr->Name == 'KYOTENPersent') {
                    //the last page
                    if ($this->baseEnd == $this->totalKyotenCount - 1) {
                        $sty = $attr->Name == 'KYOTENRank' ? 0 : 1;
                        $beforeWith = $attr->Name == 'KYOTENRank' ? 720 + 300 : 300;
                        $left = ($this->baseEnd - $this->baseStart + 1) * 400 + $beforeWith;
                        $x = $this->get_x($attr) + $this->twip_to_millimeter($left);
                        $pdf->SetXY($x, $y);
                        //実施度合<br>（全１７拠点）/ランク<br>（項目）
                        $pdf->MultiCell($w, $h, str_replace("**", "<br>", $Caption), $border = $sty, $align = 'C', $fill = $sty, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = true, $autopadding = true, $maxh = 0);
                    }
                }
                //20230211 lujunxia add e
                //20220106 zhangbowen UPD E
                else
                    if ($background_color != null) {
                        // 20241114 caina upd s
                        if ($this->rpx_file_name == 'rptJissekiIssue' && $attr->Name != 'LabelTEM') {
                            $pdf->Cell($w, $h, $Caption, 1, $ln = 0, $text_align, 1, '', 0, false, 'T', 'C');
                        } else {
                            $pdf->Cell($w, $h, $Caption, 0, $ln = 0, $text_align, 1, '', 0, false, 'T', 'C');
                        }
                        // 20241114 caina upd e
                    } else {
                        //从第二页开始 クール和領域label padding没有了（可能是因为写完其他之后设置了padding影响的）
                        $pdf->setCellPaddings(0.7, 0, 0, 0);
                        //字间距跟其他保持一致
                        $pdf->setFontSpacing(-0.12);
                        $pdf->Cell($w, $h, $Caption, 0, $ln = 0, $text_align, 0, '', 0, false, 'T', 'C');
                    }
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
        //20220106 zhangbowen UPD S
        //データ量が1ページより大きい場合の最後の行の枠線設定
        if ($this->rpx_file_name == "rptDenpyoinsatu2" || $this->rpx_file_name == "rptDenpyoinsatu") {
            if ($attr->Name == 'LineEnd') {
                if (isset($data['end'])) {
                    if ($data['end'] == 1) {
                        $line_weight = $this->en_to_millimeter(3);

                    }
                }
            }
        }
        //20220106 zhangbowen UPD E
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
    //20230213 lujunxia upd s
    public function draw_shape($pdf, $attr, $row_no = 0)
    //20230213 lujunxia upd e
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
            //20230213 lujunxia ins s
            if ($attr->Name == 'Shape5') {
                //ID列关于指摘数量的涂色
                if (in_array($row_no, $this->bgColorData['kyoten_first'])) {
                    $pdf->Rect(
                        $x,
                        $y,
                        $w,
                        $h,
                        'DF',
                        $arr,
                        array(
                            192,
                            80,
                            77
                        )
                    );
                } else
                    if (in_array($row_no, $this->bgColorData['kyoten_second'])) {
                        $pdf->Rect(
                            $x,
                            $y,
                            $w,
                            $h,
                            'DF',
                            $arr,
                            array(
                                230,
                                184,
                                183
                            )
                        );
                    } else {
                        $pdf->Rect($x, $y, $w, $h, 'D', $arr);
                    }

            } else
                //20230213 lujunxia ins e
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
     * @return
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
     * @return
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
     * @return
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
     * @return
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
     * @return
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
     * @return
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
     * @return
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
     * @return
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
     * @return array or string (depending on second parameter. Returns False if
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
        return ("#" . substr("000000" . ($n != null ? dechex($n) : ''), -6));
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
        // 20241210 caina upd s
        // $val = call_user_func($this->rpx_file_name, $key, $data);
        $val = call_user_func_array($this->rpx_file_name, [&$key, &$data]);
        // 20241210 caina upd e
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
