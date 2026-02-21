<?php
/**
 * 説明：
 *
 *
 * @author zhangbowen
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20210113			   add						   リンククリックで変更			       	zbw
 * * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\Main;

use App\Controller\AppController;
use App\Model\Main\Main;
//*******************************************
// * sample controller
//*******************************************
class MainController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    // public $components = array(
    //     'RequestHandler',
    //     'ClsComFnc'
    // );
    public $Main;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');
    }
    //　デフォルトで最初に実行される機能
    public function index()
    {
        $this->layout = 'Main_layout';

        // Viewファイル呼出し
        $this->render('/Main/index', $this->layout);
    }

    //20210113 ZBW INS S
    //初期化取得値
    public function funLoadData()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $postData = $_POST['data'];
            if ($postData['USR_ID'] === '') {
                throw new \Exception('社員番号が不正です。!');
            }
            $this->Main = new Main();

            //リンクデータベース
            $resultsqlcon = $this->Main->connMysql();
            if (!$resultsqlcon) {
                throw new \Exception("データベースリンクに失敗しました。");
            }
            //クエリーを実行
            $result = $this->Main->FunLoadData($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //処理戻り値
            if ($result['data'] instanceof \mysqli_result) {
                $result['data'] = mysqli_fetch_array($result['data']);
            }

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //email,name 更新
    public function funUserUpd()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $this->Main = new Main();
            $postData = $_POST['data'];
            if ($postData['USR_ID'] == '') {
                throw new \Exception('保存に失敗しました。!');
            }
            //リンクデータベース
            $resultsqlcon = $this->Main->connMysql();
            if (!$resultsqlcon) {
                throw new \Exception("データベースリンクに失敗しました。");
            }
            $result = $this->Main->FunUserUpd($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $session = $this->request->getSession();
            $session->write('username', $postData['USR_NAME']);

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //pass 更新
    public function funPassUpd()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $this->Main = new Main();
            $postData = $_POST['data'];
            if ($postData['USR_ID'] == '') {
                throw new \Exception('保存に失敗しました。!');
            }
            //リンクデータベース
            $resultsqlcon = $this->Main->connMysql();
            if (!$resultsqlcon) {
                throw new \Exception("データベースリンクに失敗しました。");
            }
            $result = $this->Main->Funcheckpass($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if ($result['data'] instanceof \mysqli_result) {
                $result['data'] = mysqli_fetch_array($result['data']);
            }
            if ($result['data']['PASS'] !== $postData['OldPASS']) {
                throw new \Exception('古いパスワードが間違っています，入力し直してください');
            }
            $result = $this->Main->FunPassUpd($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //20210113 ZBW INS E

}
