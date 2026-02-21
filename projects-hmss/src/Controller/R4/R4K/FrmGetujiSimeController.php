<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmGetujiSime;

class FrmGetujiSimeController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $result;
    public $Do_conn;
    public $FrmGetujiSime;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsFncLog');
        $this->loadComponent('ClsCreateCsv');
    }
    public function index()
    {
        $this->render('index', 'FrmGetujiSime_layout');
    }

    /*
           **********************************************************************
           処 理 名：ﾌｫｰﾑﾛｰﾄﾞ
           関 数 名：FrmGetujiSime_Load
           引    数：無し
           戻 り 値：無し
           処理説明：初期設定
           **********************************************************************
           */
    public function fncFrmGetujiSimeLoad()
    {
        try {
            //モデルの仕様するクラスを定義
            $this->FrmGetujiSime = new FrmGetujiSime();
            //モデルクラスのselect処理を呼出し
            $this->result = $this->FrmGetujiSime->frmGetujiSime_Load_select();
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }

            $this->fncReturn($this->result);
        } catch (\Exception $ex) {
            $this->result['result'] = False;
            $this->result['data'] = $ex->getMessage();

            $this->fncReturn($this->result);
        }
    }

    /*
     **********************************************************************
     *処 理 名：実行
     *関 数 名：fncCmdAct_Click
     *引    数：無し
     *戻 り 値：配列.$result
     *処理説明：部署別実績ファイルを作成する
     **********************************************************************
     */
    public function fncCmdActClick()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        $blnTranFlg = "";
        $objLog = array();
        try {
            //モデルの仕様するクラスを定義
            $this->FrmGetujiSime = new FrmGetujiSime();
            $objLog = $this->ClsFncLog->GS_OUTPUTLOG;
            $this->Do_conn = $this->FrmGetujiSime->Do_conn();

            if (!$this->Do_conn['result']) {
                throw new \Exception($this->Do_conn['data']);
            }

            //LOG出力ﾊﾟｽを取得する
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
            $this->ClsFncLog->strLogPath = $strPath . "/mnt/temp/" . $this->ClsComFnc->FncGetPath('pprlogpath');
            if ($this->ClsFncLog->strLogPath == "") {
                $this->ClsFncLog->strLogPath = $strPath . "/" . "mnt/temp/log/log.log";
            } else {
                $tmpPath = "";
                $tmpPathArr = explode("/", $this->ClsFncLog->strLogPath);
                for ($i = 0; $i < count($tmpPathArr) - 1; $i++) {
                    $tmpPath .= $tmpPathArr[$i] . "/";
                }
                if (!file_exists($tmpPath)) {
                    mkdir($tmpPath, 0777, TRUE);
                }
                $this->ClsFncLog->strLogPath = $tmpPath . "R2CSV.log";

            }

            //LOG出力開始
            $sysDate = $this->ClsComFnc->FncGetSysDate("y-m-d h:i:s");

            $objLog['strStartDate'] = $sysDate;
            $objLog["strID"] = "月次締処理 処理年月=" . $_POST['data']['cboYM'];
            $objLog["strState"] = "";
            $this->ClsFncLog->fncStartLog($this->ClsFncLog->strLogPath, $objLog);

            //トランザクション開始
            $this->FrmGetujiSime->Do_transaction();
            $blnTranFlg = TRUE;
            //経理ｺﾝﾄﾛｰﾙﾏｽﾀ更新
            $res = $this->FrmGetujiSime->fncUpdateKeirictl();
            if (!$res['result']) {
                throw new \Exception($res['data']);
            } else {
                if ($res['number_of_rows'] < 0) {
                    //ｴﾗｰLOG出力
                    $this->ClsFncLog->fncErrLog($this->ClsFncLog->strLogPath, $objLog);
                    //終了LOG出力
                    $sysDate = $this->ClsComFnc->FncGetSysDate("y-m-d h:i:s");
                    $objLog['strEndDate'] = $sysDate;
                    $objLog['strState'] = "NG";

                    $this->ClsFncLog->fncEndLog($this->ClsFncLog->strLogPath, $objLog);
                    return;
                }
            }
            //コミット
            $this->FrmGetujiSime->Do_commit();
            //終了LOG出力
            $blnTranFlg = FALSE;
            $sysDate = $this->ClsComFnc->FncGetSysDate("y-m-d h:i:s");
            $objLog['strEndDate'] = $sysDate;
            $objLog['strState'] = "";
            $this->ClsFncLog->fncEndLog($this->ClsFncLog->strLogPath, $objLog);
            $result['result'] = TRUE;
            $result['data'] = "";
        } catch (\Exception $ex) {
            $result['result'] = FALSE;
            $result['data'] = $ex->getMessage();
        }
        //finally
        if ($blnTranFlg) {
            //ロールバック
            $this->FrmGetujiSime->Do_rollback();
        }
        //DB接続解除
        $this->FrmGetujiSime->Do_close();

        $this->fncReturn($result);
    }

}