<?php
namespace App\Controller\R4\R4G;

use App\Controller\AppController;
use App\Model\R4\R4G\FrmFDDataSelect;
use Cake\Core\Exception\Exception;

//*******************************************
// * sample controller
//*******************************************
class FrmFDDataSelectController extends AppController
{
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
// public $autoRender = false;
    public $FrmFDDataSelect;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsLogControl');
        $this->loadComponent('ClsComFnc');
    }
    public function index()
    {
        // 画面表示内容の設定
        // レイアウトファイルの指定(app/View/Layouts/frmFDDataSelect_layout.ctpを参照)
        $layout = 'FrmFDDataSelect_layout';
        $this->render('/R4/R4G/FrmFDDataSelect/index', $layout);
    }

    protected $intState = 0;
    protected $lngOutCnt = 0;
    protected $errorList = "";

    //**********************************************************************
    //処 理 名：検索データを取得サーバー側処理
    //関 数 名：fncFrmFDDataSelect
    //引    数：フォームロード状態値
    //			 既定値：False
    //引    数：FD未作成データのみ抽出状態値
    //			  説明：画面.FD未作成データのみ抽出のチェック状態値
    //引    数：登録予定日From
    //			  説明：画面.登録予定日Fromの取得値
    //引    数：登録予定日To
    //			  説明：画面.登録予定日Toの取得値
    //戻 り 値：成功：True、失敗：False、該当データなし：Null
    //処理説明：検索データを取得
    //**********************************************************************
    public function fncFrmFDDataSelect()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );
        $returnResult = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            if (isset($_POST['request'])) {
                $postData = $_POST['request'];

            }
            if ($postData == "") {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            } else {
                $this->FrmFDDataSelect = new FrmFDDataSelect();
                //调用model里的fncFrmFDDataSelect方法，获取全部数据
                $result = $this->FrmFDDataSelect->fncFrmFDDataSelect($postData);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                //生成jqGrid专有参数。并且返回数组　$tmpJqgridShow。
                //数组形式array(sortStr => "",start => "",limit => "",page => "",totalPage => "",count => "")
                $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($result['data']);
                //获取$tmpJqgridShow数组元素
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                //将$tmpJqgridShow数组元素（$sortstr, $start, $limit）作为参数，调用model里的fncFrmAkaden方法，获取分页数据。
                $result1 = $this->FrmFDDataSelect->fncFrmFDDataSelect($postData);

                if (!$result1['result']) {
                    throw new \Exception($result1['data']);
                }
                // foreach ($this -> result1 as $key => $value) {
                // $this->result1['FD_CRE']=clscom.fnc($this->result1['FD_CRE']);
                // }
                //调用共通方法FncCreateJqGridData，返回显示jqgrid数据的数组。
                $returnResult = $this->ClsComFnc->FncCreateJqGridData($result1["data"], $totalPage, $page, $tmpCount);
                $result = $returnResult;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        //清空　$_POST['request']变量
        unset($_POST['request']);
        $_POST['request'] = null;
        $this->fncReturn($result);

    }

    //**********************************************************************
    //処 理 名：更新データを取得サーバー側処理
    //関 数 名：funExistCheck
    //引    数：FD未作成データのみ抽出状態値
    //			  説明：画面.FD未作成データのみ抽出のチェック状態値
    //引    数：登録予定日From
    //			  説明：画面.登録予定日Fromの取得値
    //引    数：登録予定日To
    //			  説明：画面.登録予定日Toの取得値
    //引    数：更新データ配列
    //戻 り 値：成功：True、失敗：False、該当データなし：Null
    //処理説明：更新データを取得
    //**********************************************************************
    public function funExistCheck()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        register_shutdown_function(
            array(
                $this,
                "finally"
            )
        );

        try {
            if (isset($_POST['data']['request'])) {
                $postData = $_POST['data']['request'];
            }
            if ($postData == "") {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            } else {
                $this->FrmFDDataSelect = new FrmFDDataSelect();
                $result = $this->FrmFDDataSelect->Do_conn();

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                $this->FrmFDDataSelect->Do_transaction();

                for ($i = 0; $i < count($postData); $i++) {
                    $chumnNo = $postData[$i];

                    $result = $this->FrmFDDataSelect->funExistCheck($chumnNo);

                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    } else {
                        $count = count((array) $result['data']);

                        if ($count == 0) {
                            //新規追加
                            $result = $this->FrmFDDataSelect->fncInsData($chumnNo);

                            if (!$result['result']) {
                                throw new \Exception($result['data']);
                            }
                        } else {
                            //登録されている[更新]
                            $result = $this->FrmFDDataSelect->fncUpdData($chumnNo);

                            if (!$result['result']) {
                                throw new \Exception($result['data']);
                            }
                        }
                    }
                }
            }

            $this->FrmFDDataSelect->Do_commit();
            $result['data'] = "";
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $this->FrmFDDataSelect->Do_rollback();
        }
        $this->fncReturn($result);
    }

    //**********************************************************************
    //処 理 名：ログ管理テーブルに登録
    //関 数 funLogManage
    //戻 り 値：成功：True、失敗：False、該当データなし：Null
    //処理説明：更新データを取得
    //**********************************************************************
    public function funLogManage()
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        register_shutdown_function(
            array(
                $this,
                "fncShutdown"
            )
        );

        try {
            if (isset($_POST['data']['request'])) {
                $this->errorList = $_POST['data']['request'];
            }

            if ($this->errorList == "") {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            } else {
                if (count($this->errorList['ERR_LIST']) > 0) {
                    $this->intState = 9;

                    //-------------プレビュー表示---------------
                    $reportKeys = array(
                        'TOU_Y_DT',
                        'CHUMN_NO',
                        'SHI_USER_NM',
                        'SYO_USER_NM'
                    );

                    $this->intState = 1;
                    $this->lngOutCnt = count($this->errorList['ERR_LIST']);

                    $path_rpxTopdf = dirname(__DIR__);
                    include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';
                    $rpx_file_names = array();
                    $datas = array();
                    array_push($rpx_file_names, "rptIrregularList");

                    $tmp_data = array();
                    array_push($tmp_data, $this->errorList['ERR_LIST']);

                    $tmp = array();
                    $tmp["data"] = $tmp_data;
                    $tmp["mode"] = "3";
                    $datas["rptIrregularList"] = $tmp;
                    $rpx_file_names['rptIrregularList'] = $reportKeys;

                    $obj = new \rpx_to_pdf($rpx_file_names, $datas);
                    //-------------プレビュー表示---------------

                    //返回路径
                    $pdfPath = $obj->to_pdf();

                    $result = array(
                        'result' => 'true',
                        'data' => 'complete',
                        "report_path" => $pdfPath
                    );
                }
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //**********************************************************************
    //処 理 名：更新データを取得のシャトダウン処理
    //関 数 名：fncShutdown
    //引    数：	無し
    //戻 り 値：	無し
    //処理説明：更新データを取得のシャトダウン処理
    //**********************************************************************
    public function fncShutdown()
    {
        if ($this->intState != 0) {
            $i = 0;
            $cnt = 0;
            $intRecCnt = 1;
            $strJyoken = $this->ClsComFnc->initializeArray(20);

            foreach ($this->errorList['ERR_LIST'] as $value) {
                $strJyoken[$cnt] = $value['CHUMN_NO'];
                $cnt = $cnt + 1;

                if ($cnt > 19 || count($this->errorList['ERR_LIST']) - 1 == $i) {
                    $this->ClsLogControl->fncLogEntry("frmFDDataSelect", $this->intState, $this->lngOutCnt, $strJyoken[0], $strJyoken[1], $strJyoken[2], $strJyoken[3], $strJyoken[4], $strJyoken[5], $strJyoken[6], $strJyoken[7], $strJyoken[8], $strJyoken[9], $strJyoken[10], $strJyoken[11], $strJyoken[12], $strJyoken[13], $strJyoken[14], $strJyoken[15], $strJyoken[16], $strJyoken[17], $strJyoken[18], $strJyoken[19], $intRecCnt);

                    $strJyoken = $this->ClsComFnc->initializeArray(20);
                    $cnt = 0;
                    $intRecCnt = $intRecCnt + 1;
                }

                $i = $i + 1;
            }
        }
    }

    //**********************************************************************
    //処 理 名：検索データを取得のシャトダウン処理
    //関 数 名：finally
    //引    数：	無し
    //戻 り 値：	無し
    //処理説明：検索データを取得のシャトダウン処理
    //**********************************************************************
    function finally()
    {
        if (isset($this->FrmFDDataSelect)) {
            $this->FrmFDDataSelect->Do_close();
            unset($this->FrmFDDataSelect);
        }
    }

}
