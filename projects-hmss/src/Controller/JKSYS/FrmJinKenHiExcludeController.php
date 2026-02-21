<?php
/**
 * 説明：
 *
 *
 * @author caina
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                   Feature/Bug                 内容                         担当
 * YYYYMMDD                  #ID                     XXXXXX                      FCSDL
 * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmJinKenHiExclude;
//*******************************************
// * sample controller
//*******************************************
class FrmJinKenHiExcludeController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
    // public $autoRender = FALSE;

    // var $components = array(
    //     'RequestHandler',
    //     'ClsComFncJKSYS'
    // );
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncJKSYS');
    }

    //　デフォルトで最初に実行される機能
    public function index()
    {
        // Viewファイル呼出し
        $this->render('index', 'FrmJinKenHiExclude_layout');
    }

    //初期設定
    public function frmJinKenHiExcludeLoad()
    {
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            $frmFurikaeDenpyoEnt = new FrmJinKenHiExclude();
            //データ取得(人事コントロールマスタ)
            $DTJKC = $frmFurikaeDenpyoEnt->fncGetJKCMST();
            if ($DTJKC['result'] == false) {
                throw new \Exception($DTJKC['data']);
            }
            $SYORI_YM = "";
            if ($DTJKC['row'] > 0) {
                $SYORI_YM = $DTJKC['data'][0]['SYORI_YM'];
                //日付形式を確認する
                $date = $SYORI_YM . '01';
                if (date('Ymd', strtotime($date)) != $date) {
                    //年月格式正しくない
                    throw new \Exception("String \"" . $SYORI_YM . "\" から型 'Date' への変換は無効です。");
                }
            } else {
                //年月なし
                throw new \Exception("コントロールマスタが存在しません！");
            }
            //社員署名取得
            $DTSyainMst = $frmFurikaeDenpyoEnt->fncGetSyainMstValue();
            if ($DTSyainMst['result'] == false) {
                throw new \Exception($DTSyainMst['data']);
            }
            $res['data'] = array(
                'SyainMst' => $DTSyainMst['data'],
                'SYORI_YM' => $SYORI_YM,
            );
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //データ取得(人件費他部署振替データ)
    public function fncGetJKTFDATLoad()
    {
        $res = array(
            'result' => TRUE,
            'data' => '',
            'error' => ''
        );
        try {
            //データ取得
            $FrmJinKenHiExclude = new FrmJinKenHiExclude();
            $res = $FrmJinKenHiExclude->fncSelectDAT();
            if (!$res['result']) {
                throw new \Exception($res['data']);
            }
            $tmpJqgridShow = $this->ClsComFncJKSYS->FncCreateJqGridShow($res['data']);
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];
            $res = $this->ClsComFncJKSYS->FncCreateJqGridDataIndex($res['data'], $totalPage, $page, $tmpCount);
        } catch (\Exception $e) {
            //设置为false会多弹出一个没有内容且标题不正的msg
            $res['result'] = TRUE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //登録ボタンクリック
    public function entClick()
    {
        $tranStartFlg = FALSE;
        $FrmJinKenHiExclude = new FrmJinKenHiExclude();

        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            $DataTF = '';
            if (isset($_POST['data']['DataTF'])) {
                $DataTF = $_POST['data']['DataTF'];
            }

            //トランザクション開始
            $FrmJinKenHiExclude->Do_transaction();
            $tranStartFlg = TRUE;
            //人件費集計対象外データ削除処理(SQL)
            $res = $FrmJinKenHiExclude->fncDelJKTFDAT();
            if (!$res['result']) {
                throw new \Exception($res['data']);
            }
            if (isset($_POST['data']['DataTF'])) {
                //追加処理
                //一覧(人件費集計対象外)が表示されて
                for ($i = 0; $i < count($DataTF); $i++) {
                    if ($this->ClsComFncJKSYS->FncNv($DataTF[$i]['SYAIN_NO']) <> '') {
                        $strSyainNo = $DataTF[$i]['SYAIN_NO'];
                        $strBiko = $this->ClsComFncJKSYS->FncNv($DataTF[$i]['REMARKS']);
                        $res = $FrmJinKenHiExclude->fncInsJKTFDAT($strSyainNo, $strBiko);
                        if (!$res['result']) {
                            throw new \Exception($res['data']);
                        }
                    }
                }
            }
            //コミット
            $FrmJinKenHiExclude->Do_commit();
            $res['data'] = '';
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $FrmJinKenHiExclude->Do_rollback();
            }
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }
}
