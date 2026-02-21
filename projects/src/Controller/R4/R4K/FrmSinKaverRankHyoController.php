<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmSinKaverRankHyo;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

// use PhpOffice\PhpSpreadsheet\Settings;
// use Symfony\Component\Cache\Adapter\FilesystemAdapter;
// use Symfony\Component\Cache\Psr16Cache;

//*******************************************
// * sample controller
//*******************************************
class FrmSinKaverRankHyoController extends AppController
{

    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmSinKaverRankHyo = '';
    public $FORMAT_NUMBER_COMMA_SEPARATED1 = '#,##0';
    public $FORMAT_NUMBER_COMMA_SEPARATED4 = '#,###.0';
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsLogControl');
    }
    public $lngOutCntK = "";
    public $lngOutCntU = "";

    public function index()
    {
        // 画面表示内容の設定
        // レイアウトファイルの指定(app/View/Layouts/frmListSelect_layout.ctpを参照)

        $this->render('index', 'FrmSinKaverRankHyo_layout');
    }

    public function frmKanrSyukeiLoad()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        try {

            $this->FrmSinKaverRankHyo = new FrmSinKaverRankHyo();

            $result = $this->FrmSinKaverRankHyo->frmKanrSyukei_Load();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {

            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function subKomokuSet($objPHPExcel)
    {
        //＊＊＊＊＊固定費表＊＊＊＊＊
        $objPHPExcel->getActiveSheet(0)->setCellValue('A5', "(固定費カバー率ランキング)");
        $objPHPExcel->getActiveSheet(0)->setCellValue('L5', "(金額単位：千円)");

        // 当期順位
        $objPHPExcel->getActiveSheet(0)->setCellValue('A6', "当期");
        $objPHPExcel->getActiveSheet(0)->getStyle('A6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->setCellValue('A7', "順位");
        $objPHPExcel->getActiveSheet(0)->getStyle('A7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('A6')->applyFromArray(
            array(
                'borders' => array(
                    'left' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('A7')->applyFromArray(
            array(
                'borders' => array(
                    'left' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM),
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );

        // 社員№
        $objPHPExcel->getActiveSheet(0)->setCellValue('B6', "社員№");
        $objPHPExcel->getActiveSheet(0)->getStyle('B6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('B6')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('B6')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('B7')->applyFromArray(array('borders' => array('right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN))));
        $objPHPExcel->getActiveSheet(0)->getStyle('B8')->applyFromArray(array('borders' => array('top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM))));
        $objPHPExcel->getActiveSheet(0)->mergeCells('B6:B7');

        // 氏名
        $objPHPExcel->getActiveSheet(0)->setCellValue('C6', "氏名");
        $objPHPExcel->getActiveSheet(0)->getStyle('C6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('C6')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('C6')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('C7')->applyFromArray(array('borders' => array('right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN))));
        $objPHPExcel->getActiveSheet(0)->getStyle('C8')->applyFromArray(array('borders' => array('top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM))));
        $objPHPExcel->getActiveSheet(0)->mergeCells('C6:C7');

        // 部署
        $objPHPExcel->getActiveSheet(0)->setCellValue('D6', "部署");
        $objPHPExcel->getActiveSheet(0)->getStyle('D6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->setCellValue('D7', "ｺｰﾄﾞ");
        $objPHPExcel->getActiveSheet(0)->getStyle('D7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('D6')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('D7')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );

        // 部署名
        $objPHPExcel->getActiveSheet(0)->setCellValue('E6', "部署名");
        $objPHPExcel->getActiveSheet(0)->getStyle('E6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('E6')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('E6')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('E7')->applyFromArray(array('borders' => array('right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN))));
        $objPHPExcel->getActiveSheet(0)->getStyle('E8')->applyFromArray(array('borders' => array('top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM))));
        $objPHPExcel->getActiveSheet(0)->mergeCells('E6:E7');

        //固定費ｶﾊﾞｰ率
        $objPHPExcel->getActiveSheet(0)->setCellValue('F6', "固定費");
        $objPHPExcel->getActiveSheet(0)->getStyle('F6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->setCellValue('F7', "ｶﾊﾞｰ率");
        $objPHPExcel->getActiveSheet(0)->getStyle('F7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('F6')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('F7')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );

        //当期総限界利益
        $objPHPExcel->getActiveSheet(0)->setCellValue('G6', "当期");
        $objPHPExcel->getActiveSheet(0)->getStyle('G6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->setCellValue('G7', "総限界利益");
        $objPHPExcel->getActiveSheet(0)->getStyle('G7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('G6')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('G7')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );

        // 固定費
        $objPHPExcel->getActiveSheet(0)->setCellValue('H6', "固定費");
        $objPHPExcel->getActiveSheet(0)->getStyle('H6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('H6')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('H6')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('H7')->applyFromArray(array('borders' => array('right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN))));
        $objPHPExcel->getActiveSheet(0)->getStyle('H8')->applyFromArray(array('borders' => array('top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM))));
        $objPHPExcel->getActiveSheet(0)->mergeCells('H6:H7');

        //労働分配率
        $objPHPExcel->getActiveSheet(0)->setCellValue('I6', "労働");
        $objPHPExcel->getActiveSheet(0)->getStyle('I6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->setCellValue('I7', "分配率");
        $objPHPExcel->getActiveSheet(0)->getStyle('I7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('I6')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('I7')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );

        //家賃を除く
        $objPHPExcel->getActiveSheet(0)->setCellValue('J6', "(参考)家賃を除く");
        $objPHPExcel->getActiveSheet(0)->getStyle('J6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('J6')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->mergeCells('J6:L6');
        $objPHPExcel->getActiveSheet(0)->getStyle('J6')->applyFromArray(
            array(
                'borders' => array(
                    'bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('K6')->applyFromArray(
            array(
                'borders' => array(
                    'bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('L6')->applyFromArray(
            array(
                'borders' => array(
                    'bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('M6')->applyFromArray(array('borders' => array('left' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM))));

        // 固定費(家賃を除く)
        $objPHPExcel->getActiveSheet(0)->setCellValue('J7', "固定費");
        $objPHPExcel->getActiveSheet(0)->getStyle('J7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('J7')->applyFromArray(
            array(
                'borders' => array(
                    'bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM),
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
                )
            )
        );

        // ｶﾊﾞｰ率(家賃を除く)
        $objPHPExcel->getActiveSheet(0)->setCellValue('K7', "ｶﾊﾞｰ率");
        $objPHPExcel->getActiveSheet(0)->getStyle('K7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('K7')->applyFromArray(
            array(
                'borders' => array(
                    'bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM),
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
                )
            )
        );

        // 順位(家賃を除く)
        $objPHPExcel->getActiveSheet(0)->setCellValue('L7', "順位");
        $objPHPExcel->getActiveSheet(0)->getStyle('L7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('L7')->applyFromArray(
            array(
                'borders' => array(
                    'bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM),
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
                )
            )
        );

        // 管理台数
        $objPHPExcel->getActiveSheet(0)->setCellValue('M6', "管理");
        $objPHPExcel->getActiveSheet(0)->getStyle('M6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->setCellValue('M7', "台数");
        $objPHPExcel->getActiveSheet(0)->getStyle('M7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('M6')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'left' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('M7')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );

        // 経験年数
        $objPHPExcel->getActiveSheet(0)->setCellValue('N6', "経験");
        $objPHPExcel->getActiveSheet(0)->getStyle('N6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->setCellValue('N7', "年数");
        $objPHPExcel->getActiveSheet(0)->getStyle('N7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('N6')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('N7')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM),
                    'bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );

        //＊＊＊＊＊台数表＊＊＊＊＊＊
        $objPHPExcel->getActiveSheet(0)->setCellValue('P5', "(売上台数ランキング)");

        // 当期順位
        $objPHPExcel->getActiveSheet(0)->setCellValue('P6', "当期");
        $objPHPExcel->getActiveSheet(0)->getStyle('P6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->setCellValue('P7', "順位");
        $objPHPExcel->getActiveSheet(0)->getStyle('P7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('P6')->applyFromArray(
            array(
                'borders' => array(
                    'left' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM),
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('P7')->applyFromArray(
            array(
                'borders' => array(
                    'left' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM),
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );

        // 氏名
        $objPHPExcel->getActiveSheet(0)->setCellValue('Q6', "氏名");
        $objPHPExcel->getActiveSheet(0)->getStyle('Q6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('Q6')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('Q6')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('Q7')->applyFromArray(array('borders' => array('right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN))));
        $objPHPExcel->getActiveSheet(0)->getStyle('Q8')->applyFromArray(array('borders' => array('top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM))));
        $objPHPExcel->getActiveSheet(0)->mergeCells('Q6:Q7');

        // 部署名
        $objPHPExcel->getActiveSheet(0)->setCellValue('R6', "部署名");
        $objPHPExcel->getActiveSheet(0)->getStyle('R6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('R6')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('R6')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('R7')->applyFromArray(array('borders' => array('right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN))));
        $objPHPExcel->getActiveSheet(0)->getStyle('R8')->applyFromArray(array('borders' => array('top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM))));
        $objPHPExcel->getActiveSheet(0)->mergeCells('R6:R7');

        // 当期台数
        $objPHPExcel->getActiveSheet(0)->setCellValue('S6', "当期");
        $objPHPExcel->getActiveSheet(0)->getStyle('S6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->setCellValue('S7', "台数");
        $objPHPExcel->getActiveSheet(0)->getStyle('S7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('S6')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('S7')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM),
                    'bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );

        // 当月順位
        $objPHPExcel->getActiveSheet(0)->setCellValue('U6', "当月");
        $objPHPExcel->getActiveSheet(0)->getStyle('U6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->setCellValue('U7', "順位");
        $objPHPExcel->getActiveSheet(0)->getStyle('U7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('U6')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM),
                    'left' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('U7')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM),
                    'left' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );

        // 当月台数
        $objPHPExcel->getActiveSheet(0)->setCellValue('V6', "当月");
        $objPHPExcel->getActiveSheet(0)->getStyle('V6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->setCellValue('V7', "台数");
        $objPHPExcel->getActiveSheet(0)->getStyle('V7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('V6')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('V7')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM),
                    'bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
    }

    public function subKomokuSet_Yachin($objPHPExcel)
    {
        //＊＊＊＊＊固定費表＊＊＊＊＊
        $objPHPExcel->getActiveSheet(0)->setCellValue('A5', "(固定費カバー率ランキング)");
        $objPHPExcel->getActiveSheet(0)->setCellValue('I5', "(金額単位：千円)");

        // 当期順位
        $objPHPExcel->getActiveSheet(0)->setCellValue('A6', "当期");
        $objPHPExcel->getActiveSheet(0)->getStyle('A6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->setCellValue('A7', "順位");
        $objPHPExcel->getActiveSheet(0)->getStyle('A7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('A6')->applyFromArray(
            array(
                'borders' => array(
                    'left' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('A7')->applyFromArray(
            array(
                'borders' => array(
                    'left' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM),
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );

        // 社員№
        $objPHPExcel->getActiveSheet(0)->setCellValue('B6', "社員№");
        $objPHPExcel->getActiveSheet(0)->getStyle('B6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('B6')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('B6')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('B7')->applyFromArray(array('borders' => array('right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN))));
        $objPHPExcel->getActiveSheet(0)->getStyle('B8')->applyFromArray(array('borders' => array('top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM))));
        $objPHPExcel->getActiveSheet(0)->mergeCells('B6:B7');

        // 氏名
        $objPHPExcel->getActiveSheet(0)->setCellValue('C6', "氏名");
        $objPHPExcel->getActiveSheet(0)->getStyle('C6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('C6')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('C6')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('C7')->applyFromArray(array('borders' => array('right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN))));
        $objPHPExcel->getActiveSheet(0)->getStyle('C8')->applyFromArray(array('borders' => array('top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM))));
        $objPHPExcel->getActiveSheet(0)->mergeCells('C6:C7');

        // 部署
        $objPHPExcel->getActiveSheet(0)->setCellValue('D6', "部署");
        $objPHPExcel->getActiveSheet(0)->getStyle('D6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->setCellValue('D7', "ｺｰﾄﾞ");
        $objPHPExcel->getActiveSheet(0)->getStyle('D7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('D6')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('D7')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );

        // 部署名
        $objPHPExcel->getActiveSheet(0)->setCellValue('E6', "部署名");
        $objPHPExcel->getActiveSheet(0)->getStyle('E6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('E6')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('E6')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('E7')->applyFromArray(array('borders' => array('right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN))));
        $objPHPExcel->getActiveSheet(0)->getStyle('E8')->applyFromArray(array('borders' => array('top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM))));
        $objPHPExcel->getActiveSheet(0)->mergeCells('E6:E7');

        //固定費ｶﾊﾞｰ率
        $objPHPExcel->getActiveSheet(0)->setCellValue('F6', "固定費");
        $objPHPExcel->getActiveSheet(0)->getStyle('F6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->setCellValue('F7', "ｶﾊﾞｰ率");
        $objPHPExcel->getActiveSheet(0)->getStyle('F7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('F6')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('F7')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );

        //当期総限界利益
        $objPHPExcel->getActiveSheet(0)->setCellValue('G6', "当期");
        $objPHPExcel->getActiveSheet(0)->getStyle('G6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->setCellValue('G7', "総限界利益");
        $objPHPExcel->getActiveSheet(0)->getStyle('G7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('G6')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('G7')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );

        // 固定費
        $objPHPExcel->getActiveSheet(0)->setCellValue('H6', "固定費");
        $objPHPExcel->getActiveSheet(0)->getStyle('H6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('H6')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('H6')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('H7')->applyFromArray(array('borders' => array('right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN))));
        $objPHPExcel->getActiveSheet(0)->getStyle('H8')->applyFromArray(array('borders' => array('top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM))));
        $objPHPExcel->getActiveSheet(0)->mergeCells('H6:H7');

        //労働分配率
        $objPHPExcel->getActiveSheet(0)->setCellValue('I6', "労働");
        $objPHPExcel->getActiveSheet(0)->getStyle('I6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->setCellValue('I7', "分配率");
        $objPHPExcel->getActiveSheet(0)->getStyle('I7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('I6')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('I7')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );

        // 管理台数
        $objPHPExcel->getActiveSheet(0)->setCellValue('J6', "管理");
        $objPHPExcel->getActiveSheet(0)->getStyle('J6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->setCellValue('J7', "台数");
        $objPHPExcel->getActiveSheet(0)->getStyle('J7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('J6')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'left' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('J7')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );

        // 経験年数
        $objPHPExcel->getActiveSheet(0)->setCellValue('K6', "経験");
        $objPHPExcel->getActiveSheet(0)->getStyle('K6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->setCellValue('K7', "年数");
        $objPHPExcel->getActiveSheet(0)->getStyle('K7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('K6')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('K7')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM),
                    'bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );

        //＊＊＊＊＊台数表＊＊＊＊＊＊
        $objPHPExcel->getActiveSheet(0)->setCellValue('M5', "(売上台数ランキング)");

        // 当期順位
        $objPHPExcel->getActiveSheet(0)->setCellValue('M6', "当期");
        $objPHPExcel->getActiveSheet(0)->getStyle('M6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->setCellValue('M7', "順位");
        $objPHPExcel->getActiveSheet(0)->getStyle('M7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('M6')->applyFromArray(
            array(
                'borders' => array(
                    'left' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM),
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('M7')->applyFromArray(
            array(
                'borders' => array(
                    'left' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM),
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );

        //	社員№
        $objPHPExcel->getActiveSheet(0)->setCellValue('N6', "社員№");
        $objPHPExcel->getActiveSheet(0)->getStyle('N6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('N6')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('N6')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('N7')->applyFromArray(array('borders' => array('right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN))));
        $objPHPExcel->getActiveSheet(0)->getStyle('N8')->applyFromArray(array('borders' => array('top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM))));
        $objPHPExcel->getActiveSheet(0)->mergeCells('N6:N7');

        // 氏名
        $objPHPExcel->getActiveSheet(0)->setCellValue('O6', "氏名");
        $objPHPExcel->getActiveSheet(0)->getStyle('O6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('O6')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('O6')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('O7')->applyFromArray(array('borders' => array('right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN))));
        $objPHPExcel->getActiveSheet(0)->getStyle('O8')->applyFromArray(array('borders' => array('top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM))));
        $objPHPExcel->getActiveSheet(0)->mergeCells('O6:O7');

        // 部署名
        $objPHPExcel->getActiveSheet(0)->setCellValue('P6', "部署名");
        $objPHPExcel->getActiveSheet(0)->getStyle('P6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('P6')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('P6')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('P7')->applyFromArray(array('borders' => array('right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN))));
        $objPHPExcel->getActiveSheet(0)->getStyle('P8')->applyFromArray(array('borders' => array('top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM))));
        $objPHPExcel->getActiveSheet(0)->mergeCells('P6:P7');

        // 当期台数
        $objPHPExcel->getActiveSheet(0)->setCellValue('Q6', "当期");
        $objPHPExcel->getActiveSheet(0)->getStyle('Q6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->setCellValue('Q7', "台数");
        $objPHPExcel->getActiveSheet(0)->getStyle('Q7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('Q6')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('Q7')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM),
                    'bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );

        // 当月順位
        $objPHPExcel->getActiveSheet(0)->setCellValue('S6', "当月");
        $objPHPExcel->getActiveSheet(0)->getStyle('S6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->setCellValue('S7', "順位");
        $objPHPExcel->getActiveSheet(0)->getStyle('S7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('S6')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM),
                    'left' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('S7')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM),
                    'left' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );

        // 当月台数
        $objPHPExcel->getActiveSheet(0)->setCellValue('T6', "当月");
        $objPHPExcel->getActiveSheet(0)->getStyle('T6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->setCellValue('T7', "台数");
        $objPHPExcel->getActiveSheet(0)->getStyle('T7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet(0)->getStyle('T6')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM),
                    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
        $objPHPExcel->getActiveSheet(0)->getStyle('T7')->applyFromArray(
            array(
                'borders' => array(
                    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM),
                    'bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                )
            )
        );
    }

    public function fncExcelOutput2($postData, $file)
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'MsgID' => 'E9999'
        );
        try {
            $blnOutFlg = FALSE;
            // Create new PHPExcel object
            // $psr6Cache = new FilesystemAdapter();

            // $psr16Cache = new Psr16Cache($psr6Cache);
            // Settings::setCache($psr16Cache);
            $objPHPExcel = new Spreadsheet();
            // $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
            // PHPExcel_Settings::setCacheStorageMethod($cacheMethod);

            // Set document properties
            $objPHPExcel->getProperties()->setCreator("付曉琳")->setLastModifiedBy("付曉琳")->setTitle("カバー率ランキング表")->setSubject("PHPExcel Test Document")->setDescription("Test document for PHPExcel, generated using PHP classes.")->setCategory("Test result file");
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            // Set page orientation and size
            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A3);

            // Set column widths
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(4.09);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(5.84);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12.00);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(4.21);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12.40);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(5.59);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(8.71);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(6.85);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(5.46);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(4.71);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(4.71);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(1.63);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(4.09);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(5.84);
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(12.00);
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(12.40);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(5.09);
            $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(1.00);
            $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(4.42);
            $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(4.42);
            $objPHPExcel->getActiveSheet()->getStyle('A1:U1000')->getFont()->setName('ＭＳ Ｐゴシック');
            $objPHPExcel->getActiveSheet()->getStyle('A3:U1000')->getFont()->setSize(9);

            $this->FrmSinKaverRankHyo = new FrmSinKaverRankHyo();
            //タイトル行用SQL
            $result1 = $this->FrmSinKaverRankHyo->fncStandardInfoSel($postData);

            if (!$result1['result']) {
                throw new \Exception($result1['data']);
            }
            $strTitle = $result1['data'][0]["TITLE"];
            $value1 = $this->ClsComFnc->FncNv($result1['data'][0]['KISYU_Y']);
            $value2 = $this->ClsComFnc->FncNv($result1['data'][0]['KISYU_M']);
            $value3 = $this->ClsComFnc->FncNv($result1['data'][0]['TUKI_Y']);
            $value4 = $this->ClsComFnc->FncNv($result1['data'][0]['TUKI_M']);
            $strHaniYM = "(平成 " . $value1 . " 年 " . $value2 . " 月  ～　平成 " . $value3 . " 年 " . $value4 . " 月)";

            $objPHPExcel->getActiveSheet()->setCellValue('A2', $strTitle);
            $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(11);
            $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->mergeCells('A2:T2');
            $objPHPExcel->getActiveSheet()->setCellValue('A3', $strHaniYM);
            $objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->mergeCells('A3:T3');

            $this->subKomokuSet_Yachin($objPHPExcel);

            //フッター用SQL
            $result2 = $this->FrmSinKaverRankHyo->fncMemoSel();

            if (!$result2['result']) {
                throw new \Exception($result2['data']);
            }

            $intMemoCnt = count((array) $result2['data']);

            //固定費カバー率ランキング用SQL
            $result3 = $this->FrmSinKaverRankHyo->fncKaverRankSel($postData, $intMemoCnt);

            if (!$result3['result']) {
                throw new \Exception($result3['data']);
            }
            $this->lngOutCntK = $result3['row'];
            if ($result3['row'] > 0) {
                $blnOutFlg = TRUE;
                $RowCnt = count((array) $result3['data']);
                $RowCnt = (int) $RowCnt + 7;
                $intRowCnt = 8;

                foreach ((array) $result3['data'] as $value) {
                    $this->subKoteihiMeisaiSet_Yachin($objPHPExcel, $value, $intRowCnt);

                    $intRowCnt = $intRowCnt + 1;
                }
                //
                $this->subKoteihiLineSet_Yachin($objPHPExcel, $RowCnt, $postData, $result3['data']);

            }

            //売上台数用SQL

            $result4 = $this->FrmSinKaverRankHyo->fncUriageRankSel($postData);
            $this->lngOutCntU = $result4['row'];
            if (!$result4['result']) {
                throw new \Exception($result4['data']);
            }

            if ($result4['row'] > 0) {
                $blnOutFlg = TRUE;
                $intRowCnt = 8;

                foreach ((array) $result4['data'] as $value) {
                    $this->subUriDaisuMeisaiSet_Yachin($objPHPExcel, $value, $intRowCnt);

                    $intRowCnt = $intRowCnt + 1;
                }

                $RowCnt = (int) $result4['row'] + 7;
                // $this -> subUriDaisuLineSet($objPHPExcel, $RowCnt);
                $styleArrayOut = array('borders' => array('outline' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)));
                $styleArrayIn = array('borders' => array('inside' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)));
                $objPHPExcel->getActiveSheet()->getStyle('M8:' . 'Q' . $RowCnt)->applyFromArray($styleArrayIn);
                $objPHPExcel->getActiveSheet()->getStyle('M8:' . 'Q' . $RowCnt)->applyFromArray($styleArrayOut);

                $objPHPExcel->getActiveSheet()->getStyle('S8:' . 'T' . $RowCnt)->applyFromArray($styleArrayIn);
                $objPHPExcel->getActiveSheet()->getStyle('S8:' . 'T' . $RowCnt)->applyFromArray($styleArrayOut);
            }
            if (!$blnOutFlg) {
                $result['MsgID'] = 'I0001';
                throw new \Exception('');
            }
            $RowCnt = $RowCnt + 1;
            foreach ((array) $result2['data'] as $value) {
                $RowCnt = $RowCnt + 1;
                $objPHPExcel->getActiveSheet()->setCellValue('M' . $RowCnt, $value['MEMO']);
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('カバー率ランキング表');
            // Save Excel5 file
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Xls');

            $objWriter->save($file);

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['data'] = $e->getMessage();
            $result['result'] = FALSE;
        }

        return $result;
    }

    public function fncExcelOutput($postData, $file)
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'MsgID' => 'E9999'
        );
        try {
            $blnOutFlg = FALSE;
            // Create new PHPExcel object
            // $psr6Cache = new FilesystemAdapter();

            // $psr16Cache = new Psr16Cache($psr6Cache);
            // Settings::setCache($psr16Cache);

            $objPHPExcel = new Spreadsheet();
            // $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
            // PHPExcel_Settings::setCacheStorageMethod($cacheMethod);

            // Set document properties
            $objPHPExcel->getProperties()->setCreator("付曉琳")->setLastModifiedBy("付曉琳")->setTitle("カバー率ランキング表")->setSubject("PHPExcel Test Document")->setDescription("Test document for PHPExcel, generated using PHP classes.")->setCategory("Test result file");
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            // Set page orientation and size
            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A3);

            // Set column widths
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(4.09);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(5.84);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10.71);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(4.21);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10.80);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(5.59);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(8.71);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(6.85);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(5.46);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(6.85);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(5.84);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(4.21);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(4.71);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(4.71);
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(1.63);
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(4.09);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(10.71);
            $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(10.80);
            $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(5.09);
            $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(1.00);
            $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(4.42);
            $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(4.42);
            $objPHPExcel->getActiveSheet()->getStyle('A3:Z1000')->getFont()->setSize(9);
            $objPHPExcel->getActiveSheet()->getStyle('A1:Z1000')->getFont()->setName('ＭＳ Ｐゴシック');
            // Add some data

            $this->FrmSinKaverRankHyo = new FrmSinKaverRankHyo();
            //タイトル行用SQL
            $result1 = $this->FrmSinKaverRankHyo->fncStandardInfoSel($postData);

            if (!$result1['result']) {
                throw new \Exception($result1['data']);
            }

            $strTitle = $result1['data'][0]["TITLE"];
            $value1 = $this->ClsComFnc->FncNv($result1['data'][0]['KISYU_Y']);
            $value2 = $this->ClsComFnc->FncNv($result1['data'][0]['KISYU_M']);
            $value3 = $this->ClsComFnc->FncNv($result1['data'][0]['TUKI_Y']);
            $value4 = $this->ClsComFnc->FncNv($result1['data'][0]['TUKI_M']);
            $strHaniYM = "(平成 " . $value1 . " 年 " . $value2 . " 月  ～　平成 " . $value3 . " 年 " . $value4 . " 月)";

            $objPHPExcel->getActiveSheet()->setCellValue('A2', $strTitle);

            $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(11);
            $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->mergeCells('A2:V2');
            $objPHPExcel->getActiveSheet()->setCellValue('A3', $strHaniYM);
            $objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->mergeCells('A3:V3');

            $this->subKomokuSet($objPHPExcel);

            //フッター用SQL
            $result2 = $this->FrmSinKaverRankHyo->fncMemoSel();

            if (!$result2['result']) {
                throw new \Exception($result2['data']);
            }

            $intMemoCnt = count((array) $result2['data']);

            //固定費カバー率ランキング用SQL
            $result3 = $this->FrmSinKaverRankHyo->fncKaverRankSel($postData, $intMemoCnt);

            if (!$result3['result']) {
                throw new \Exception($result3['data']);
            }
            $this->lngOutCntK = $result3['row'];
            if ($result3['row'] > 0) {
                $blnOutFlg = TRUE;
                $RowCnt = count((array) $result3['data']);
                $RowCnt = (int) $RowCnt + 7;
                $intRowCnt = 8;

                foreach ((array) $result3['data'] as $value) {
                    $this->subKoteihiMeisaiSet($objPHPExcel, $value, $intRowCnt);

                    $intRowCnt = $intRowCnt + 1;
                }
                $this->subKoteihiLineSet($objPHPExcel, $RowCnt, $postData, $result3['data']);

            }
            //売上台数用SQL

            $result4 = $this->FrmSinKaverRankHyo->fncUriageRankSel($postData);

            if (!$result4['result']) {
                throw new \Exception($result4['data']);
            }
            $this->lngOutCntU = $result4['row'];
            if ($result4['row'] > 0) {
                $blnOutFlg = TRUE;
                $intRowCnt = 8;

                foreach ((array) $result4['data'] as $value) {
                    $this->subUriDaisuMeisaiSet($objPHPExcel, $value, $intRowCnt);

                    $intRowCnt = $intRowCnt + 1;
                }

                $RowCnt = (int) $result4['row'] + 7;
                $this->subUriDaisuLineSet($objPHPExcel, $RowCnt);

            }
            if (!$blnOutFlg) {
                $result['MsgID'] = 'I0001';
                throw new \Exception('');
            }

            $RowCnt = $RowCnt + 1;
            foreach ((array) $result2['data'] as $value) {
                $RowCnt = $RowCnt + 1;
                $objPHPExcel->getActiveSheet()->setCellValue('P' . $RowCnt, $value['MEMO']);
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('カバー率ランキング表');
            // Save Excel5 file
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Xls');

            $objWriter->save($file);

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['data'] = $e->getMessage();
            $result['result'] = FALSE;
        }

        return $result;
    }

    public function subUriDaisuLineSet($objPHPExcel, $RowCnt)
    {
        $styleArrayOut = array('borders' => array('outline' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)));
        $styleArrayIn = array('borders' => array('inside' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)));
        $objPHPExcel->getActiveSheet()->getStyle('P8:' . 'S' . $RowCnt)->applyFromArray($styleArrayIn);
        $objPHPExcel->getActiveSheet()->getStyle('P8:' . 'S' . $RowCnt)->applyFromArray($styleArrayOut);

        $objPHPExcel->getActiveSheet()->getStyle('U8:' . 'V' . $RowCnt)->applyFromArray($styleArrayIn);
        $objPHPExcel->getActiveSheet()->getStyle('U8:' . 'V' . $RowCnt)->applyFromArray($styleArrayOut);
    }

    public function subUriDaisuMeisaiSet($objPHPExcel, $value, $intRowCnt)
    {
        $objPHPExcel->getActiveSheet(0)->setCellValue('P' . $intRowCnt, $value['TOUKI_JUNI']);
        $objPHPExcel->getActiveSheet(0)->setCellValue('Q' . $intRowCnt, $value['SYAIN_NM']);
        $objPHPExcel->getActiveSheet(0)->setCellValue('R' . $intRowCnt, $value['BUSYO_NM']);
        $objPHPExcel->getActiveSheet(0)->setCellValue('S' . $intRowCnt, $value['TOUKI_DAISU']);
        $objPHPExcel->getActiveSheet(0)->setCellValue('U' . $intRowCnt, $value['TOUGETU_JUNI']);
        $objPHPExcel->getActiveSheet(0)->setCellValue('V' . $intRowCnt, $value['TOUGETU_DAISU']);
    }

    public function subUriDaisuMeisaiSet_Yachin($objPHPExcel, $value, $intRowCnt)
    {
        $objPHPExcel->getActiveSheet(0)->setCellValue('M' . $intRowCnt, $value['TOUKI_JUNI']);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('N' . $intRowCnt, $value['SYAIN_NO'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet(0)->setCellValue('O' . $intRowCnt, $value['SYAIN_NM']);
        $objPHPExcel->getActiveSheet(0)->setCellValue('P' . $intRowCnt, $value['BUSYO_NM']);
        $objPHPExcel->getActiveSheet(0)->setCellValue('Q' . $intRowCnt, $value['TOUKI_DAISU']);
        $objPHPExcel->getActiveSheet(0)->setCellValue('S' . $intRowCnt, $value['TOUGETU_JUNI']);
        $objPHPExcel->getActiveSheet(0)->setCellValue('T' . $intRowCnt, $value['TOUGETU_DAISU']);
    }

    public function subKoteihiMeisaiSet_Yachin($objPHPExcel, $value, $intRowCnt)
    {

        $objPHPExcel->getActiveSheet(0)->setCellValue('A' . $intRowCnt, $value['TOUKI_JUN']);

        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('B' . $intRowCnt, $value['SYAIN_NO'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

        $objPHPExcel->getActiveSheet(0)->setCellValue('C' . $intRowCnt, $value['SYAIN_NM']);

        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('D' . $intRowCnt, $value['BUSYO_CD'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

        $objPHPExcel->getActiveSheet(0)->setCellValue('E' . $intRowCnt, $value['BUSYO_NM']);

        $objPHPExcel->getActiveSheet(0)->setCellValue('F' . $intRowCnt, $value['KOTEI_KAVER_RT']);

        $objPHPExcel->getActiveSheet(0)->setCellValue('G' . $intRowCnt, $value['TOUKI_GENRI']);

        $objPHPExcel->getActiveSheet(0)->setCellValue('H' . $intRowCnt, $value['KOTEIHI']);

        $objPHPExcel->getActiveSheet(0)->setCellValue('I' . $intRowCnt, $value['WORK_BUNPAI_RT']);

        $objPHPExcel->getActiveSheet(0)->setCellValue('J' . $intRowCnt, $value['KANRI_DAISU']);

        $objPHPExcel->getActiveSheet(0)->setCellValue('K' . $intRowCnt, $value['KEIKEN_NENSU']);

    }

    public function subKoteihiMeisaiSet($objPHPExcel, $value, $intRowCnt)
    {

        $objPHPExcel->getActiveSheet(0)->setCellValue('A' . $intRowCnt, $value['TOUKI_JUN']);

        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('B' . $intRowCnt, $value['SYAIN_NO'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

        $objPHPExcel->getActiveSheet(0)->setCellValue('C' . $intRowCnt, $value['SYAIN_NM']);

        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('D' . $intRowCnt, $value['BUSYO_CD'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

        $objPHPExcel->getActiveSheet(0)->setCellValue('E' . $intRowCnt, $value['BUSYO_NM']);

        $objPHPExcel->getActiveSheet(0)->setCellValue('F' . $intRowCnt, $value['KOTEI_KAVER_RT']);

        $objPHPExcel->getActiveSheet(0)->setCellValue('G' . $intRowCnt, $value['TOUKI_GENRI']);

        $objPHPExcel->getActiveSheet(0)->setCellValue('H' . $intRowCnt, $value['KOTEIHI']);

        $objPHPExcel->getActiveSheet(0)->setCellValue('I' . $intRowCnt, $value['WORK_BUNPAI_RT']);

        $objPHPExcel->getActiveSheet(0)->setCellValue('J' . $intRowCnt, $value['Y_MINUS_KOTEI']);

        $objPHPExcel->getActiveSheet(0)->setCellValue('K' . $intRowCnt, $value['Y_MINUS_KAVER_RT']);

        $objPHPExcel->getActiveSheet(0)->setCellValue('L' . $intRowCnt, $value['SANKO_JUN']);

        $objPHPExcel->getActiveSheet(0)->setCellValue('M' . $intRowCnt, $value['KANRI_DAISU']);

        $objPHPExcel->getActiveSheet(0)->setCellValue('N' . $intRowCnt, $value['KEIKEN_NENSU']);

    }

    public function subKoteihiLineSet($objPHPExcel, $RowCnt, $postData, $data)
    {

        // $dataRow = $RowCnt - 7;

        // $dataOther = $dataRow % 10;

        // $dataAll = intval(($dataRow - 8) / 10);

        if ($postData['radRankingCheck'] == 'true') {
            for ($i = 7; $i <= $RowCnt; $i++) {
                if ($i == $RowCnt) {
                    $row = $i;
                } else {
                    $row = $i + 1;
                }

                if ((intval(($i - 7) / 10)) % 2 == 0) {
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':N' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':N' . $row)->getFill()->getStartColor()->setRGB('FFFFFF');
                } else {
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':N' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':N' . $row)->getFill()->getStartColor()->setRGB('d3d3d3');
                }

            }

            // for ($i = 1; $i <= $dataAll; $i++)
            // {
            //
            // if ($i % 2 == 0)
            // {
            // //ou
            // $all = $default + 10 - 1;
            // $objPHPExcel -> getActiveSheet() -> getStyle('A' . $default . ':N' . $all) -> getFill() -> setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            // $objPHPExcel -> getActiveSheet() -> getStyle('A' . $default . ':N' . $all) -> getFill() -> getStartColor() -> setRGB('d3d3d3');
            // }
            // else
            // {
            // $all = $default + 10 - 1;
            // // $objPHPExcel -> getActiveSheet() -> getStyle('A' . $default . ':N' . $all) -> getFill() -> setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            // // $objPHPExcel -> getActiveSheet() -> getStyle('A' . $default . ':N' . $all) -> getFill() -> getStartColor() -> setRGB('FFFFFF');
            // }
            // $default = $all + 1;
            // }
            //
            // if ($dataOther != 0)
            // {
            // if ($dataAll % 2 != 0)
            // {
            // $all = $default + $dataOther - 1;
            // $objPHPExcel -> getActiveSheet() -> getStyle('A' . $default . ':N' . $all) -> getFill() -> setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            // $objPHPExcel -> getActiveSheet() -> getStyle('A' . $default . ':N' . $all) -> getFill() -> getStartColor() -> setRGB('FFFFFF');
            // }
            // // else
            // // {
            // // $all = $default + $dataOther - 1;
            // // $objPHPExcel -> getActiveSheet() -> getStyle('A' . $default . ':N' . $all) -> getFill() -> setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            // // $objPHPExcel -> getActiveSheet() -> getStyle('A' . $default . ':N' . $all) -> getFill() -> getStartColor() -> setRGB('d3d3d3');
            // // }
            // }
        } else {
            $default = 8;

            foreach ($data as $key => $value) {
                if ($value['COLOR_NO'] % 2 == 0) {
                    $id = $key + $default;
                    $position = 'A' . $id . ':N' . $id;

                    $objPHPExcel->getActiveSheet()->getStyle($position)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                    $objPHPExcel->getActiveSheet()->getStyle($position)->getFill()->getStartColor()->setRGB('d3d3d3');
                }

            }

        }
        $objPHPExcel->getActiveSheet(0)->getStyle('B8:' . 'B' . $RowCnt)->getNumberFormat()->setFormatCode('00000');
        $objPHPExcel->getActiveSheet(0)->getStyle('F8:' . 'F' . $RowCnt)->getNumberFormat()->setFormatCode($this->FORMAT_NUMBER_COMMA_SEPARATED4);
        $objPHPExcel->getActiveSheet(0)->getStyle('G8:' . 'G' . $RowCnt)->getNumberFormat()->setFormatCode($this->FORMAT_NUMBER_COMMA_SEPARATED1);
        $objPHPExcel->getActiveSheet(0)->getStyle('H8:' . 'H' . $RowCnt)->getNumberFormat()->setFormatCode($this->FORMAT_NUMBER_COMMA_SEPARATED1);
        $objPHPExcel->getActiveSheet(0)->getStyle('I8:' . 'I' . $RowCnt)->getNumberFormat()->setFormatCode($this->FORMAT_NUMBER_COMMA_SEPARATED4);
        $objPHPExcel->getActiveSheet(0)->getStyle('J8:' . 'J' . $RowCnt)->getNumberFormat()->setFormatCode($this->FORMAT_NUMBER_COMMA_SEPARATED1);
        $objPHPExcel->getActiveSheet(0)->getStyle('K8:' . 'K' . $RowCnt)->getNumberFormat()->setFormatCode($this->FORMAT_NUMBER_COMMA_SEPARATED4);
        $objPHPExcel->getActiveSheet(0)->getStyle('L8:' . 'L' . $RowCnt)->getNumberFormat()->setFormatCode($this->FORMAT_NUMBER_COMMA_SEPARATED1);
        $objPHPExcel->getActiveSheet(0)->getStyle('M8:' . 'M' . $RowCnt)->getNumberFormat()->setFormatCode($this->FORMAT_NUMBER_COMMA_SEPARATED1);
        $objPHPExcel->getActiveSheet(0)->getStyle('N8:' . 'N' . $RowCnt)->getNumberFormat()->setFormatCode($this->FORMAT_NUMBER_COMMA_SEPARATED1);

        $styleArrayOut = array('borders' => array('outline' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)));
        $styleArrayIn = array('borders' => array('inside' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)));
        $objPHPExcel->getActiveSheet()->getStyle('A8:' . 'N' . $RowCnt)->applyFromArray($styleArrayIn);
        $objPHPExcel->getActiveSheet()->getStyle('A8:' . 'N' . $RowCnt)->applyFromArray($styleArrayOut);

    }

    public function subKoteihiLineSet_Yachin($objPHPExcel, $RowCnt, $postData, $data)
    {

        for ($i = 7; $i <= $RowCnt; $i++) {
            if ($i == $RowCnt) {
                $row = $i;
            } else {
                $row = $i + 1;
            }

            if ((intval(($i - 7) / 10)) % 2 == 0) {

                $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':K' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':K' . $row)->getFill()->getStartColor()->setRGB('FFFFFF');
            } else {
                $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':K' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':K' . $row)->getFill()->getStartColor()->setRGB('d3d3d3');
            }

        }

        // $dataRow = $RowCnt - 7;
        //
        // $dataOther = $dataRow % 10;
        //
        // $dataAll = intval(($dataRow - 8) / 10);
        //
        // $default = 8;
        //
        // for ($i = 1; $i <= $dataAll; $i++)
        // {
        //
        // if ($i % 2 == 0)
        // {
        // //ou
        // $all = $default + 10 - 1;
        // $objPHPExcel -> getActiveSheet() -> getStyle('A' . $default . ':K' . $all) -> getFill() -> setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        // $objPHPExcel -> getActiveSheet() -> getStyle('A' . $default . ':K' . $all) -> getFill() -> getStartColor() -> setRGB('d3d3d3');
        // }
        // else
        // {
        // $all = $default + 10 - 1;
        // // $objPHPExcel -> getActiveSheet() -> getStyle('A' . $default . ':N' . $all) -> getFill() -> setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        // // $objPHPExcel -> getActiveSheet() -> getStyle('A' . $default . ':N' . $all) -> getFill() -> getStartColor() -> setRGB('FFFFFF');
        // }
        // $default = $all + 1;
        // }
        //
        // if ($dataOther != 0)
        // {
        // if ($dataAll % 2 != 0)
        // {
        // $all = $default + $dataOther - 1;
        // $objPHPExcel -> getActiveSheet() -> getStyle('A' . $default . ':K' . $all) -> getFill() -> setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        // $objPHPExcel -> getActiveSheet() -> getStyle('A' . $default . ':K' . $all) -> getFill() -> getStartColor() -> setRGB('FFFFFF');
        // }
        // // else
        // // {
        // // $all = $default + $dataOther - 1;
        // // $objPHPExcel -> getActiveSheet() -> getStyle('A' . $default . ':N' . $all) -> getFill() -> setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        // // $objPHPExcel -> getActiveSheet() -> getStyle('A' . $default . ':N' . $all) -> getFill() -> getStartColor() -> setRGB('d3d3d3');
        // // }
        // }

        $objPHPExcel->getActiveSheet(0)->getStyle('B8:' . 'B' . $RowCnt)->getNumberFormat()->setFormatCode('00000');
        $objPHPExcel->getActiveSheet(0)->getStyle('F8:' . 'F' . $RowCnt)->getNumberFormat()->setFormatCode($this->FORMAT_NUMBER_COMMA_SEPARATED4);
        $objPHPExcel->getActiveSheet(0)->getStyle('I8:' . 'I' . $RowCnt)->getNumberFormat()->setFormatCode($this->FORMAT_NUMBER_COMMA_SEPARATED4);

        $objPHPExcel->getActiveSheet(0)->getStyle('G8:' . 'G' . $RowCnt)->getNumberFormat()->setFormatCode($this->FORMAT_NUMBER_COMMA_SEPARATED1);
        $objPHPExcel->getActiveSheet(0)->getStyle('H8:' . 'H' . $RowCnt)->getNumberFormat()->setFormatCode($this->FORMAT_NUMBER_COMMA_SEPARATED1);
        $objPHPExcel->getActiveSheet(0)->getStyle('J8:' . 'J' . $RowCnt)->getNumberFormat()->setFormatCode($this->FORMAT_NUMBER_COMMA_SEPARATED1);
        $objPHPExcel->getActiveSheet(0)->getStyle('K8:' . 'K' . $RowCnt)->getNumberFormat()->setFormatCode($this->FORMAT_NUMBER_COMMA_SEPARATED1);

        $styleArrayOut = array('borders' => array('outline' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)));
        $styleArrayIn = array('borders' => array('inside' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)));
        $objPHPExcel->getActiveSheet()->getStyle('A8:' . 'K' . $RowCnt)->applyFromArray($styleArrayIn);
        $objPHPExcel->getActiveSheet()->getStyle('A8:' . 'K' . $RowCnt)->applyFromArray($styleArrayOut);

    }

    public function fileReadDialog()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
        );
        $postData = $_POST['data']['request'];
        try {
            $intState = 0;
            $tmpPath1 = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
            $tmpPath2 = "webroot/files/R4k/";
            $tmpPath = $tmpPath1 . "/" . $tmpPath2;
            $file = $tmpPath . $postData['fileName'];

            if (!file_exists($tmpPath)) {
                if (!mkdir($tmpPath, 0777, TRUE)) {
                    $result["data"] = "Execl Error";
                    throw new \Exception($result["data"]);
                }
            }

            //ログ管理
            $intState = 9;
            if ($postData['radRankingCheck'] == 'true' || $postData['radBusyoCheck'] == 'true') {
                $resultOutPut = $this->fncExcelOutput($postData, $file);

                if (!$resultOutPut['result']) {
                    if ($resultOutPut['MsgID'] == 'I0001') {
                        $intState = 1;
                        $result['MsgID'] = 'I0001';
                        throw new \Exception('noData');
                    } else {
                        $result['MsgID'] = 'E9999';
                        throw new \Exception($resultOutPut['data']);
                    }

                }
            } else {
                $resultOutPut2 = $this->fncExcelOutput2($postData, $file);
                if (!$resultOutPut2['result']) {
                    if ($resultOutPut2['MsgID'] == 'I0001') {
                        $intState = 1;
                        $result['MsgID'] = 'I0001';
                        throw new \Exception('noData');
                    } else {
                        $result['MsgID'] = 'E9999';
                        throw new \Exception($resultOutPut2['data']);
                    }

                }
            }
            $intState = 1;
            $result['result'] = TRUE;
            $result['data'] = "files/R4k/" . $postData['fileName'];

        } catch (\Exception $e) {
            $result['data'] = $e->getMessage();
            $result['result'] = FALSE;
        }

        //ログ管理 Start
        if ($intState != 0) {

            //$intState!=0の場合、ログ管理テーブルに登録
            $this->ClsLogControl->fncLogEntry("frmSinKaverRankHyo_Koteihi_Excel", $intState, $this->lngOutCntK, $postData['cboYMStart'], $postData['YMEnd'], $postData['Rank'], $postData['rad1'], $postData['fileName']);
            $this->ClsLogControl->fncLogEntry("frmSinKaverRankHyo_UriageDaisu_Excel", $intState, $this->lngOutCntU, $postData['cboYMStart'], $postData['YMEnd'], $postData['Rank'], $postData['rad1'], $postData['fileName']);
        }
        //ログ管理 End

        $this->fncReturn($result);
    }

    public function printSinsya()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'errMsg' => 'E9999'
        );
        $postData = $_POST['data']['request'];

        try {
            $intState = 9;

            $this->FrmSinKaverRankHyo = new FrmSinKaverRankHyo();
            $result1 = $this->FrmSinKaverRankHyo->fncMemoSel();

            if (!$result1['result']) {
                throw new \Exception($result1['data']);
            }

            $intMaxRow = count((array) $result1['data']);

            $result2 = $this->FrmSinKaverRankHyo->fncKaverRankSel($postData, $intMaxRow);

            if (!$result2['result']) {
                throw new \Exception($result2['data']);
            }
            $this->lngOutCntK = $result2['row'];
            $result3 = $this->FrmSinKaverRankHyo->fncUriageRankSel($postData);

            if (!$result3['result']) {
                throw new \Exception($result3['data']);
            }

            $this->lngOutCntU = $result3['row'];

            if ($result2['row'] == 0 && $result3['row'] == 0) {
                $result['errMsg'] = 'I0001';
                $intState = '1';
                throw new \Exception("I0001");
            }

            $result4 = $this->FrmSinKaverRankHyo->fncStandardInfoSel($postData);

            if (!$result4['result']) {
                throw new \Exception($result4['data']);
            }

            $strTitle = $result4['data'][0]["TITLE"];
            $value1 = $this->ClsComFnc->FncNv($result4['data'][0]['KISYU_Y']);
            $value2 = $this->ClsComFnc->FncNv($result4['data'][0]['KISYU_M']);
            $value3 = $this->ClsComFnc->FncNv($result4['data'][0]['TUKI_Y']);
            $value4 = $this->ClsComFnc->FncNv($result4['data'][0]['TUKI_M']);
            $path_rpxTopdf = dirname(__DIR__);
            include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';

            $data = $result2['data'];

            if ($postData['radRankingCheck'] == 'true' || $postData['radBusyoCheck'] == 'true') {

                $tmpPdfName = "rptSinsyaKaverRank";

                include_once $path_rpxTopdf . '/Component/tcpdf/' . $tmpPdfName . '.inc';
                $cntMax = count((array) $data);
                foreach ((array) $data as $key => $value) {
                    if ($postData['radRankingCheck'] == 'true') {
                        $n = $key;
                        $n = (int) ($n / 10);
                        if ($n % 2 == 0) {
                            $data[$key]['COLOR'] = "White";
                        } else {
                            $data[$key]['COLOR'] = "LightGray";
                        }
                    } else {
                        if ($value['COLOR_NO'] % 2 == 0) {
                            $data[$key]['COLOR'] = "LightGray";
                        } else {
                            $data[$key]['COLOR'] = "White";
                        }
                    }

                    if ($key == $cntMax - 1) {
                        $data[$key]['Max'] = 1;
                    } else {
                        $data[$key]['Max'] = 0;
                    }
                    $data[$key]['BUSYO_NM'] = $value['BUSYO_NM'] != null ? substr($value['BUSYO_NM'], 0, 18) : '';
                    $data[$key]['TITLE'] = $strTitle;
                    $data[$key]['KISYU_Y'] = $value1;
                    $data[$key]['KISYU_M'] = $value2;
                    $data[$key]['TUKI_Y'] = $value3;
                    $data[$key]['TUKI_M'] = $value4;
                }
                $cntMax3 = count((array) $result3['data']);
                foreach ((array) $result3['data'] as $key => $value) {
                    $value['BUSYO_NM'] = $value['BUSYO_NM'] != null ? substr($value['BUSYO_NM'], 0, 18) : '';
                    if ($key == $cntMax3 - 1) {
                        $value['Max'] = 1;
                    } else {
                        $value['Max'] = 0;
                    }
                    $value['TITLE'] = $strTitle;
                    $value['KISYU_Y'] = $value1;
                    $value['KISYU_M'] = $value2;
                    $value['TUKI_Y'] = $value3;
                    $value['TUKI_M'] = $value4;
                    array_push($data, $value);
                }
                foreach ((array) $result1['data'] as $key => $value) {
                    $value['TITLE'] = $strTitle;
                    $value['KISYU_Y'] = $value1;
                    $value['KISYU_M'] = $value2;
                    $value['TUKI_Y'] = $value3;
                    $value['TUKI_M'] = $value4;
                    array_push($data, $value);
                }
            } else {
                $tmpPdfName = "rptSinsyaKaverRank_yachin";
                include_once $path_rpxTopdf . '/Component/tcpdf/' . $tmpPdfName . '.inc';
                $cntMax = count((array) $data);
                foreach ((array) $data as $key => $value) {
                    $n = $key;
                    $n = (int) ($n / 10);
                    if ($n % 2 == 0) {
                        $data[$key]['COLOR'] = "White";
                    } else {
                        $data[$key]['COLOR'] = "LightGray";
                    }

                    if ($key == $cntMax - 1) {
                        $data[$key]['Max'] = 1;
                    } else {
                        $data[$key]['Max'] = 0;
                    }
                    $data[$key]['BUSYO_NM'] = $value['BUSYO_NM'] != null ? substr($value['BUSYO_NM'], 0, 24) : '';
                    $data[$key]['TITLE'] = $strTitle;
                    $data[$key]['KISYU_Y'] = $value1;
                    $data[$key]['KISYU_M'] = $value2;
                    $data[$key]['TUKI_Y'] = $value3;
                    $data[$key]['TUKI_M'] = $value4;
                }
                $cntMax3 = count((array) $result3['data']);
                foreach ((array) $result3['data'] as $key => $value) {
                    $value['BUSYO_NM'] = $value['BUSYO_NM'] != null ? substr($value['BUSYO_NM'], 0, 24) : '';
                    if ($key == $cntMax3 - 1) {
                        $value['Max'] = 1;
                    } else {
                        $value['Max'] = 0;
                    }
                    $value['TITLE'] = $strTitle;
                    $value['KISYU_Y'] = $value1;
                    $value['KISYU_M'] = $value2;
                    $value['TUKI_Y'] = $value3;
                    $value['TUKI_M'] = $value4;
                    array_push($data, $value);
                }
                foreach ((array) $result1['data'] as $key => $value) {
                    $value['TITLE'] = $strTitle;
                    $value['KISYU_Y'] = $value1;
                    $value['KISYU_M'] = $value2;
                    $value['TUKI_Y'] = $value3;
                    $value['TUKI_M'] = $value4;
                    array_push($data, $value);
                }
            }

            $tmp_data = array();
            $rpx_file_names = array();
            $rpx_file_names[$tmpPdfName] = $data_fields_rptSinsyaKaverRank;
            array_push($tmp_data, $data);
            $tmp = array();
            $datas = array();
            $tmp["data"] = $tmp_data;
            $tmp["mode"] = "9";
            $datas[$tmpPdfName] = $tmp;
            $obj = new \rpx_to_pdf($rpx_file_names, $datas);
            $pdfPath = $obj->to_pdf2();
            $result['path'] = $pdfPath;
            $intState = '1';
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['data'] = $e->getMessage();
            $result['result'] = FALSE;
        }

        //ログ管理 Start
        if ($intState != 0) {

            //$intState!=0の場合、ログ管理テーブルに登録
            $this->ClsLogControl->fncLogEntry("frmSinKaverRankHyo_Koteihi_Print", $intState, $this->lngOutCntK, $postData['cboYMStart'], $postData['YMEnd'], $postData['Rank'], $postData['rad1']);
            $this->ClsLogControl->fncLogEntry("frmSinKaverRankHyo_UriageDaisu_Print", $intState, $this->lngOutCntU, $postData['cboYMStart'], $postData['YMEnd'], $postData['Rank'], $postData['rad1']);
        }

        $this->fncReturn($result);

    }

}