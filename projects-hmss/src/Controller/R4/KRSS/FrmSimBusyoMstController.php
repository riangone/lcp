<?php
/**
 * 説明：
 *
 *
 * @author li
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\R4\KRSS;

use App\Controller\AppController;
use App\Model\R4\KRSS\FrmSimBusyoMst;

//*******************************************
// * sample controller
//*******************************************
class FrmSimBusyoMstController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmSimBusyoMst;
    private $Session;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
    }

    //　デフォルトで最初に実行される機能
    public function index()
    {
        $this->render('index', 'FrmSimBusyoMst_layout');
    }
    //データリストの値を設定
    public function subSpreadReShow()
    {
        try {
            //シミュレーションラインマスタﾃﾞｰﾀを取得する
            $this->FrmSimBusyoMst = new FrmSimBusyoMst();
            $result = $this->FrmSimBusyoMst->fncSQL(1);
            //戻るエラー処理
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            //システムエラーの場合、戻る設定
            $result['result'] = FALSE;
            //システムエラーの場合、エラー情報を表示する
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    // '**********************************************************************
    // '処 理 名：登録処理
    // '関 数 名：cmdUpdateClick
    // '引    数：
    // '戻 り 値：
    // '処理説明：更新処理
    // '**********************************************************************
    public function cmdUpdateClick()
    {
        //JSの参数データ「lineArr」、こちらで取得する
        $postData = $_POST['data']['lineArr'];

        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => ''
        );
        try {
            //login_userの値を取得する
            $this->Session = $this->request->getSession();
            $UPDUSER = $this->Session->read('login_user');
            //IPの値を取得する
            $UPDCLTNM = $this->request->clientIp();

            $this->FrmSimBusyoMst = new FrmSimBusyoMst();
            //トランザクション処理を起動する
            $result = $this->FrmSimBusyoMst->Do_conn();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $this->FrmSimBusyoMst->Do_transaction();

            //部署別集計ﾜｰｸを削除する
            $result = $this->FrmSimBusyoMst->fncSQL(4);
            //戻るエラー処理
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //追加処理を行う
            foreach ($postData as $value) {
                //データが更新場合、UPD_SYA_CD、UPD_PRG_ID、UPD_CLT_NMの値を設定する
                $value["UPDUSER"] = $UPDUSER;
                $value["UPDAPP"] = 'FrmSimBusyoMst';
                $value["UPDCLTNM"] = $UPDCLTNM;
                //追加処理を実行
                $result = $this->FrmSimBusyoMst->fncSQL(5, $value);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
            }
            //トランザクション処理をコミットする
            $this->FrmSimBusyoMst->Do_commit();
            $result['result'] = TRUE;
            $result['data'] = '';

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $this->FrmSimBusyoMst->Do_rollback();
        }
        //DB接続解除
        $this->FrmSimBusyoMst->Do_close();

        $this->fncReturn($result);
    }
}
