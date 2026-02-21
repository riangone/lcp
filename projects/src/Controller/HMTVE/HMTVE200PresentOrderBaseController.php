<?php
namespace App\Controller\HMTVE;
use App\Controller\AppController;
use App\Model\HMTVE\HMTVE200PresentOrderBase;
//*******************************************
// * sample controller
//*******************************************
class HMTVE200PresentOrderBaseController extends AppController
{
    public $autoLayout = TRUE;
    public $HMTVE200PresentOrderBase;
    // public $autoRender = FALSE;

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
    }

    public function index()
    {
        $this->render('index', 'HMTVE200PresentOrderBase_layout');
    }
    //表示ボタンのイベント
    public function btnPrintOutClick()
    {
        $this->HMTVE200PresentOrderBase = new HMTVE200PresentOrderBase();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (!isset($_POST['data'])) {
                throw new \Exception("param error");
            }
            //取得データをグリッドビューにバインドする
            $res = $this->HMTVE200PresentOrderBase->CreateDataSource($_POST['data']['STARTDT']);
            if (!$res['result']) {
                throw new \Exception($res['data']);
            }
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //登録ボタンのイベント
    public function btnRegClick()
    {
        $tranStartFlg = FALSE;
        $this->HMTVE200PresentOrderBase = new HMTVE200PresentOrderBase();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            $iUpdFlg = "0";
            if (isset($_POST['data'])) {
                //トランザクション開始
                $this->HMTVE200PresentOrderBase->Do_transaction();
                $tranStartFlg = TRUE;
                //成約プレゼント設定データを削除する
                $del = $this->HMTVE200PresentOrderBase->DEL_SQL($_POST['data']['STARTDT']);
                if (!$del['result']) {
                    throw new \Exception($del['data']);
                }
                $iUpdFlg .= $del['number_of_rows'];

                //追加処理を行う
                $gvTenpo = $_POST['data']['gvTenpo'];
                if ($gvTenpo && count($gvTenpo) > 0) {
                    for ($i = 0; $i < count($gvTenpo); $i++) {
                        $txtHinmei = $gvTenpo[$i]['txtHinmei'];
                        $txtTanka = $gvTenpo[$i]['txtTanka'];
                        $lblCREATE_DATE = $gvTenpo[$i]['lblCREATE_DATE'];
                        if ($txtHinmei != "") {
                            //品名設定ﾃｰﾌﾞﾙ_品名≠""の場合
                            $params = array(
                                'lblExhibitTermStart' => $_POST['data']['STARTDT'],
                                'ORDER_NO' => $gvTenpo[$i]['ORDER_NO'],
                                'HINMEI' => $txtHinmei,
                                'TANKA' => $txtTanka,
                                'lblCREATE_DATE' => $lblCREATE_DATE
                            );
                            $ins = $this->HMTVE200PresentOrderBase->INS_SQL($params);
                            if (!$ins['result']) {
                                throw new \Exception($ins['data']);
                            }
                            $iUpdFlg .= $ins['number_of_rows'];
                        }
                    }
                }
                if (strpos($iUpdFlg, "-1") == FALSE) {
                    //エラーがない場合、コミットする
                    $this->HMTVE200PresentOrderBase->Do_commit();
                    $tranStartFlg = FALSE;
                } else {
                    if ($tranStartFlg) {
                        $this->HMTVE200PresentOrderBase->Do_rollback();
                    }
                }
            }
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $this->HMTVE200PresentOrderBase->Do_rollback();
            }
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //データを削除する
    public function btnDelClick()
    {
        $this->HMTVE200PresentOrderBase = new HMTVE200PresentOrderBase();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (!isset($_POST['data'])) {
                throw new \Exception("param error");
            }
            $res = $this->HMTVE200PresentOrderBase->DEL_SQL($_POST['data']['STARTDT']);
            if (!$res['result']) {
                throw new \Exception($res['data']);
            }
            $res['data'] = "";
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

}
