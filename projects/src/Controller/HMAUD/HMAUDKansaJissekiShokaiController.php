<?php
namespace App\Controller\HMAUD;

use App\Controller\AppController;
use App\Model\HMAUD\HMAUDKansaJissekiShokai;

//*******************************************
// * sample controller
//*******************************************
class HMAUDKansaJissekiShokaiController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
// public $autoRender = false;
    public $Session;
    public $HMAUDKansaJissekiShokai;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFncHMAUD');
    }
    public function index()
    {
        // 画面表示内容の設定
        $this->render('index', 'HMAUDKansaJissekiShokai_layout');
    }

    //検索ボタンクリック
    public function btnSearchClick()
    {
        $this->HMAUDKansaJissekiShokai = new HMAUDKansaJissekiShokai();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['request'])) {
                //役割
                $audit = '';
                $this->Session = $this->request->getSession();
                //監査補助人
                $subRes = $this->HMAUDKansaJissekiShokai->getAuditSubSql($this->Session->read('login_user'));
                if (!$subRes['result']) {
                    throw new \Exception($subRes['data']);
                }
                if ($subRes['row'] > 0) {
                    //監査補助人
                    $audit = 'sub_audit';

                }
                //20230314 LIU INS S
                else {
                    $viewRes = $this->HMAUDKansaJissekiShokai->getUser($this->Session->read('login_user'));
                    if (!$viewRes['result']) {
                        throw new \Exception($viewRes['data']);
                    }
                    if ($viewRes['row'] > 0) {
                        //ログインユーザーが ビューアマスタ上に存在している場合、監査スケジュールに登録されていなくてもデータ検索、照会可能とする
                        $audit = 'sub_audit';
                    }
                    ;
                }
                //20230314 LIU INS E
                //監査項目マスタテーブルにデータ
                $objdr = $this->HMAUDKansaJissekiShokai->getDataSql($_POST['request'], $audit, $this->Session->read('login_user'));

                if (!$objdr['result']) {
                    throw new \Exception($objdr['data']);
                }

                $tmpJqgridShow = $this->ClsComFncHMAUD->FncCreateJqGridShow($objdr['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $res = $this->ClsComFncHMAUD->FncCreateJqGridDataIndex($objdr['data'], $totalPage, $page, $tmpCount);

            }
        } catch (\Exception $e) {
            //前端jqgrid共通，显示error信息的title有问题，所以这里返回值为true
            $res['result'] = TRUE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //拠点マスタのデータを取得
    public function fncGetKyoten()
    {
        $this->HMAUDKansaJissekiShokai = new HMAUDKansaJissekiShokai();
        $res = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            $kyoten = $this->HMAUDKansaJissekiShokai->getKyotenSql();
            if (!$kyoten['result']) {
                throw new \Exception($kyoten['data']);
            }
            //検索条件・クールには 現在のクール数を初期表示
            $cour = $this->HMAUDKansaJissekiShokai->getInitializeCour();
            if (!$cour['result']) {
                throw new \Exception($cour['data']);
            }
            $res['data']['kyoten'] = $kyoten['data'];
            $res['data']['cour'] = $cour['data'];
            $res['result'] = true;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }
}