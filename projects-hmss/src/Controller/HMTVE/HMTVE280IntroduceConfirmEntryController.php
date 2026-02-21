<?php
namespace App\Controller\HMTVE;
use App\Controller\AppController;
use App\Model\HMTVE\HMTVE280IntroduceConfirmEntry;
//*******************************************
// * sample controller
//*******************************************
class HMTVE280IntroduceConfirmEntryController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    public $HMTVE280IntroduceConfirmEntry;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
    }
    public function index()
    {
        // 画面表示内容の設定
        $this->render('index', 'HMTVE280IntroduceConfirmEntry_layout');
    }

    //Jqgrid
    public function fncSearchSpread()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );

        try {
            if (isset($_POST['request'])) {
                $this->HMTVE280IntroduceConfirmEntry = new HMTVE280IntroduceConfirmEntry();
                $result = $this->HMTVE280IntroduceConfirmEntry->getIntroductionSQL($_POST['request']);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                $tmpJqgridShow = $this->ClsComFncHMTVE->FncCreateJqGridShow($result['data']);
                $start = $tmpJqgridShow['start'];
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];

                $tmpJqgrid = $this->ClsComFncHMTVE->FncCreateJqGridDataReload($result["data"], $totalPage, $page, $tmpCount, $start);
                if ($result['row'] == 0) {
                    $objdr2 = $this->HMTVE280IntroduceConfirmEntry->getTermSQL();
                    if (!$objdr2['result']) {
                        throw new \Exception($objdr2['data']);
                    }
                    $tmpJqgrid->T_Term = $objdr2['data'];
                }

                $this->fncReturn($tmpJqgrid);
            } else {
                $result = array(
                    'result' => TRUE,
                    'data' => array()
                );
                $this->fncReturn($result);
            }
        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();

            $this->fncReturn($result);
        }
    }

    //'**********************************************************************
    //'処 理 名：ページロード
    //'関 数 名：Page_Load
    //'引 数 １：(I)sender イベントソース
    //'引 数 ２：(I)e      イベントパラメータ
    //'戻 り 値：なし
    //'処理説明：ページ初期化
    //'**********************************************************************
    public function pageLoad()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $this->HMTVE280IntroduceConfirmEntry = new HMTVE280IntroduceConfirmEntry();
            //システム日付を取得する
            $strSysdate = $this->ClsComFncHMTVE->FncGetSysDate("Y/m/d");
            $result['data']['sysdata'] = date('Y/m/d', strtotime("$strSysdate -1 day"));
            //対象期間を取得する
            $objdr = $this->HMTVE280IntroduceConfirmEntry->getTermSQL();
            if (!$objdr['result']) {
                throw new \Exception($objdr['data']);
            }
            $result['data']['getTerm'] = $objdr['data'];
            //Ⅰー３．店舗名を表示する
            $objReader = $this->HMTVE280IntroduceConfirmEntry->FoucsMove();
            if (!$objReader['result']) {
                throw new \Exception($objReader['data']);
            }
            $result['data']['MST'] = $objReader['data'];

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //**********************************************************************
    //処 理 名：フォーカス
    //関 数 名：FoucsMove
    //引 数 　：なし
    //戻 り 値：なし
    //処理説明：フォーカス移動時
    //**********************************************************************
    public function foucsMove()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $this->HMTVE280IntroduceConfirmEntry = new HMTVE280IntroduceConfirmEntry();
            //部署に所属する社員を取得する
            if (isset($_POST['data']['T_SYAIN'])) {
                $objdr = $this->HMTVE280IntroduceConfirmEntry->getEmployeSQL($_POST['data']);
                if (!$objdr['result']) {
                    throw new \Exception($objdr['data']);
                }
                $result['data']['T_SYAIN'] = $objdr['data'];
            }

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：紹介者登録ボタンのイベント
    //'関 数 名：btnLand_Click
    //'引 数 １：(I)sender イベントソース
    //'引 数 ２：(I)e      イベントパラメータ
    //'戻 り 値：なし
    //'処理説明：紹介者情報登録
    //'**********************************************************************
    public function btnLandClick()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        $tranStartFlg = FALSE;
        $this->HMTVE280IntroduceConfirmEntry = new HMTVE280IntroduceConfirmEntry();
        try {
            //トランザクション開始
            $this->HMTVE280IntroduceConfirmEntry->Do_transaction();
            $tranStartFlg = TRUE;
            $objdr = $this->HMTVE280IntroduceConfirmEntry->getReObjectSQL($_POST['data']);
            if (!$objdr['result']) {
                throw new \Exception($objdr['data']);
            }
            if ($objdr['row'] == 0) {
                //取得データ件数=0の場合
                //追加処理を行う
                $objdr = $this->HMTVE280IntroduceConfirmEntry->getIntroInsertSQL($_POST['data']);
                if (!$objdr['result']) {
                    throw new \Exception($objdr['data']);
                }
            } else {
                //取得データ件数＞0の場合
                //更新処理を行う
                if ($_POST['data']['txtAcceptNoEnabled'] == "true") {
                    throw new \Exception("E9999");
                } else {
                    $objdr = $this->HMTVE280IntroduceConfirmEntry->getIntroUpdateSQL($_POST['data']);
                    if (!$objdr['result']) {
                        throw new \Exception($objdr['data']);
                    }
                }
            }
            //コミット
            $this->HMTVE280IntroduceConfirmEntry->Do_commit();
            $tranStartFlg = FALSE;
            //対象期間を取得する
            $objdr = $this->HMTVE280IntroduceConfirmEntry->getTermSQL();
            if (!$objdr['result']) {
                throw new \Exception($objdr['data']);
            }
            $result['data']['getTerm'] = $objdr['data'];
            //入力ﾃｰﾌﾞﾙ_受理日に初期値を表示する
            $strSysdate = $this->ClsComFncHMTVE->FncGetSysDate("Y/m/d");
            $result['data']['sysdata'] = date('Y/m/d', strtotime("$strSysdate -1 day"));

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $this->HMTVE280IntroduceConfirmEntry->Do_rollback();
            }

            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：紹介者削除ボタンのイベント
    //'関 数 名：btnDelete_Click
    //'引 数 １：(I)sender イベントソース
    //'引 数 ２：(I)e      イベントパラメータ
    //'戻 り 値：なし
    //'処理説明：紹介者情報削除
    //'**********************************************************************
    public function btnDeleteClick()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        $tranStartFlg = FALSE;
        $this->HMTVE280IntroduceConfirmEntry = new HMTVE280IntroduceConfirmEntry();
        try {
            //トランザクション開始
            $this->HMTVE280IntroduceConfirmEntry->Do_transaction();
            $tranStartFlg = TRUE;
            //紹介者確認データを削除する
            $objdr = $this->HMTVE280IntroduceConfirmEntry->getIntroDeleteSQL($_POST['data']);
            if (!$objdr['result']) {
                throw new \Exception($objdr['data']);
            }
            //ｴﾗｰﾒｯｾｰｼﾞを表示し、処理を中断(ロールバック)
            if ($objdr['number_of_rows'] < 1) {
                throw new \Exception("W0024");
            }
            //コミット
            $this->HMTVE280IntroduceConfirmEntry->Do_commit();
            $tranStartFlg = FALSE;
            //対象期間を取得する
            $objdr = $this->HMTVE280IntroduceConfirmEntry->getTermSQL();
            if (!$objdr['result']) {
                throw new \Exception($objdr['data']);
            }
            $result['data']['getTerm'] = $objdr['data'];
            //入力ﾃｰﾌﾞﾙ_受理日に初期値を表示する
            $strSysdate = $this->ClsComFncHMTVE->FncGetSysDate("Y/m/d");
            $result['data']['sysdata'] = date('Y/m/d', strtotime("$strSysdate -1 day"));

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $this->HMTVE280IntroduceConfirmEntry->Do_rollback();
            }

            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：紹介者クリアボタンのイベント
    //'関 数 名：btnClear_Click
    //'引 数 １：(I)sender イベントソース
    //'引 数 ２：(I)e      イベントパラメータ
    //'戻 り 値：なし
    //'処理説明：紹介者情報クリア
    //'**********************************************************************
    public function btnClearClick()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $this->HMTVE280IntroduceConfirmEntry = new HMTVE280IntroduceConfirmEntry();
            //システム日付を取得する
            $strSysdate = $this->ClsComFncHMTVE->FncGetSysDate("Y/m/d");
            $result['data']['sysdata'] = date('Y/m/d', strtotime("$strSysdate -1 day"));

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

}

