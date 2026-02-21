<?php
namespace App\Controller\HMTVE;
use App\Controller\AppController;
use App\Model\HMTVE\HMTVE120PublicityOrderEntry;
//*******************************************
// * sample controller
//*******************************************
class HMTVE120PublicityOrderEntryController extends AppController
{
    public $autoLayout = TRUE;
    public $Session;
    public $HMTVE120PublicityOrderEntry;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
    }

    public function index()
    {
        // 画面表示内容の設定
        $this->render('index', 'HMTVE120PublicityOrderEntry_layout');
    }

    //コンボリストを設定する
    public function pageLoad()
    {
        $result = array(
            'result' => false,
            'data' => null,
            'shopdata' => null,
            'error' => ''
        );
        try {
            $this->HMTVE120PublicityOrderEntry = new HMTVE120PublicityOrderEntry();

            $result = $this->HMTVE120PublicityOrderEntry->getYM();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $this->Session = $this->request->getSession();
            $resultShop = $this->HMTVE120PublicityOrderEntry->getShopNM($this->Session->read('BusyoCD'));

            if (!$resultShop['result']) {
                throw new \Exception($resultShop['data']);
            } else {
                $result['shopdata'] = $resultShop['data'];
            }
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //宣材確定データを取得する
    public function getExCheck()
    {
        $result = array(
            'result' => false,
            'data' => null,
            'error' => ''
        );
        try {
            $this->HMTVE120PublicityOrderEntry = new HMTVE120PublicityOrderEntry();

            $postdata = $_POST['data'];
            $result = $this->HMTVE120PublicityOrderEntry->getExCheck($postdata['NENGETU']);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //展示会テーブルの生成
    public function getCreatDateEx()
    {
        $result = array(
            'result' => false,
            'data' => null,
            'dataHd' => null,
            'dataDt' => null,
            'error' => ''
        );
        $flag = false;
        try {
            if (isset($_POST['request'])) {
                $this->HMTVE120PublicityOrderEntry = new HMTVE120PublicityOrderEntry();
                $postdata = $_POST['request'];
                $this->Session = $this->request->getSession();
                $postdata['BUSYOCD'] = $this->Session->read('BusyoCD');
                $result = $this->HMTVE120PublicityOrderEntry->getCreatDateEx($postdata);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } elseif (count((array) $result['data']) < 1) {
                    //データがありません
                    throw new \Exception("W9999");
                }

                $tmpJqgridShow = $this->ClsComFncHMTVE->FncCreateJqGridShow($result['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $tmpJqgrid = $this->ClsComFncHMTVE->FncCreateJqGridData($result["data"], $totalPage, $page, $tmpCount);
                $result = $tmpJqgrid;
                $flag = true;
                //展示会ﾍｯﾀﾞｰﾃﾞｰﾀ取得
                $resultHd = $this->HMTVE120PublicityOrderEntry->getExHeader($postdata['NENGETU']);
                if (!$resultHd['result']) {
                    throw new \Exception($resultHd['data']);
                } else {
                    $result->dataHd = $resultHd['data'];
                }

                //回収期限ﾃﾞｰﾀを取得する
                $resultDt = $this->HMTVE120PublicityOrderEntry->getDate($postdata['NENGETU']);
                if (!$resultDt['result']) {
                    throw new \Exception($resultDt['data']);
                } else {
                    $result->dataDt = $resultDt['data'];
                }
                $result->error = '';
            }
        } catch (\Exception $e) {
            //前端jqgrid共通，显示error信息的title有问题，所以这里返回值为true

            if ($flag == true) {
                $result->result = TRUE;
                $result->error = $e->getMessage();
            } else {
                $result['result'] = TRUE;
                $result['error'] = $e->getMessage();
            }

        }
        $this->fncReturn($result);
    }

    //登録処理
    public function btnCheckClick()
    {
        $result = array(
            'result' => false,
            'data' => null,
            'error' => ''
        );
        $blnTran = FALSE;
        try {
            $this->HMTVE120PublicityOrderEntry = new HMTVE120PublicityOrderEntry();

            $postdata = $_POST['data'];
            $resultck = $this->HMTVE120PublicityOrderEntry->getExCheck($postdata['NENGETU']);
            if (!$resultck['result']) {
                throw new \Exception($resultck['data']);
            } elseif (count((array) $resultck['data']) > 0) {
                if (array_key_exists("KAKUTEI_FLG", (array) $resultck['data'][0]) && $resultck['data'][0]['KAKUTEI_FLG'] == '1') {
                    throw new \Exception('E9999');
                }
            }
            $this->Session = $this->request->getSession();
            $this->HMTVE120PublicityOrderEntry->Do_transaction();
            $blnTran = TRUE;
            $result_del = $this->HMTVE120PublicityOrderEntry->getWorkDel($postdata['NENGETU'], $this->Session->read('BusyoCD'));
            if (!$result_del['result']) {
                throw new \Exception($result_del['data']);
            }

            for ($i = 0; $i < count($postdata['ROWS']); $i++) {
                $postdata['ROWS'][$i]['IVENT_YM'] = $postdata['NENGETU'];
                $postdata['ROWS'][$i]['BUSYO_CD'] = $this->Session->read('BusyoCD');

                $result_ins = $this->HMTVE120PublicityOrderEntry->getWorkInsert($postdata['ROWS'][$i]);
                if (!$result_ins['result']) {
                    throw new \Exception($result_ins['data']);
                }
            }
            $this->HMTVE120PublicityOrderEntry->Do_commit();
            $result['result'] = true;

        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();

            if ($blnTran) {
                $this->HMTVE120PublicityOrderEntry->Do_rollback();
            }
        }
        $this->fncReturn($result);
    }

}

