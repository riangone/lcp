<?php
/**
 * 説明：
 *
 *
 * @author wangying
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * ----------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL　　　　　　　　
 * 20170503                                        变更                              WANGYING　
 * 20170504				#					　　	jqgrid機能が改正する					YIN
 * 20170522				#					　　	jqgrid機能が改正する					LQS
 * * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\APPM;

use App\Controller\AppController;
use App\Model\APPM\FrmMessejiIchiranSansho;
//*******************************************
// * sample controller
//*******************************************
class FrmMessejiIchiranSanshoController extends AppController
{
    public $autoLayout = TRUE;
    public $FrmMessejiIchiranSansho;
    public $result = array();
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsFncLog');
    }
    public function index()
    {
        // Viewファイル呼出し
        $this->render('index', 'FrmMessejiIchiranSansho_layout');
    }

    //'**********************************************************************
    //'処 理 名：メッセージ取得
    //'関 数 名：Search
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function search()
    {
        $result = array();

        try {
            $FrmMessejiIchiranSansho = new FrmMessejiIchiranSansho();
            $result = $FrmMessejiIchiranSansho->Search();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //20170503 WANG DEL S

            // $tmpJqgridShow = $this -> ClsComFnc -> FncCreateJqGridShow($result['data']);
            // $sortstr = $tmpJqgridShow['sortStr'];
            // $start = $tmpJqgridShow['start'];
            // $limit = $tmpJqgridShow['limit'];
            // $page = $tmpJqgridShow['page'];
            // $totalPage = $tmpJqgridShow['totalPage'];
            // $tmpCount = $tmpJqgridShow['count'];
            //
            // $tmpJqgrid = $this -> ClsComFnc -> FncCreateJqGridData($result["data"], $totalPage, $page, $tmpCount);
            // $result = $tmpJqgrid;

            //20170503 WANG DEL E
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：入力欄取得
    //'関 数 名：fncSearchData
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function fncSearchData()
    {
        $result = array();
        //20170504 WANG DEL S
        //$arr = array();
        //20170504 WANG DEL E

        try {
            $FrmMessejiIchiranSansho = new FrmMessejiIchiranSansho();
            //20170504 WANG UPD S
            //内容区分
            //$arr['content'] = $FrmMessejiIchiranSansho -> fncSearchData("1");
            $result['content'] = $FrmMessejiIchiranSansho->fncSearchData("1");
            if (!$result['content']['result']) {
                throw new \Exception($result['content']['data']);
            }
            //既読
            //$arr['kidoku'] = $FrmMessejiIchiranSansho -> fncSearchData("2");
            $result['kidoku'] = $FrmMessejiIchiranSansho->fncSearchData("2");
            //$result = $arr;
            // if (!$result['content']['result'])
            // {
            // throw new \Exception($result['data']);
            // }
            if (!$result['kidoku']['result']) {
                throw new \Exception($result['kidoku']['data']);
            }
            $result['delete'] = $FrmMessejiIchiranSansho->fncSearchData("3");
            if (!$result['delete']['result']) {
                throw new \Exception($result['delete']['data']);
            }
            $result['result'] = TRUE;
            // if (!$result['result'])
            // {
            // throw new \Exception($result['data']);
            // }
            // $tmpJqgridShow = $this -> ClsComFnc -> FncCreateJqGridShow($result['data']);
            // $sortstr = $tmpJqgridShow['sortStr'];
            // $start = $tmpJqgridShow['start'];
            // $limit = $tmpJqgridShow['limit'];
            // $page = $tmpJqgridShow['page'];
            // $totalPage = $tmpJqgridShow['totalPage'];
            // $tmpCount = $tmpJqgridShow['count'];
            //
            // $tmpJqgrid = $this -> ClsComFnc -> FncCreateJqGridData($result["data"], $totalPage, $page, $tmpCount);
            // $result = $tmpJqgrid;

            //20170504 WANG UPD E
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：メッセージ取得
    //'関 数 名：msgSearch
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function msgSearch()
    {
        $result = array();
        try {
            if (isset($_POST['request'])) {
                $postData = $_POST["request"];
                $this->FrmMessejiIchiranSansho = new FrmMessejiIchiranSansho();
                //20170504 YIN UPD S
                // $result = $FrmMessejiIchiranSansho -> msgSearch($postData);
                //20170522 LQS UPD S
                //$result = $FrmMessejiIchiranSansho -> msgSearch($postData, "");
                $this->result = $this->FrmMessejiIchiranSansho->msgSearch($postData, "");
                //20170522 LQS UPD E
                //20170504 YIN UPD E
                if (!$this->result['result']) {
                    throw new \Exception($this->result['data']);
                }

                $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($this->result['data']);
                $sortstr = $tmpJqgridShow['sortStr'];
                $start = $tmpJqgridShow['start'];
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                //20170504 YIN INS S
                //20170522 LQS UPD S
                //$result = $FrmMessejiIchiranSansho -> msgSearch($postData, $sortstr);
                $this->result = $this->FrmMessejiIchiranSansho->msgSearch($postData, $sortstr);
                //20170522 LQS UPD E
                //20170504 YIN INS E
                foreach ((array) $this->result["data"] as $key => $value) {
                    //20241224 lujunxia upd s
                    if (!is_null($this->result["data"][$key]['MESSEJI_NAIYO'])) {
                        $this->result["data"][$key]['MESSEJI_NAIYO'] = htmlspecialchars($this->result["data"][$key]['MESSEJI_NAIYO'], ENT_COMPAT, 'UTF-8');
                    }
                    //20241224 lujunxia upd e
                }

                //20170522 LQS UPD S
                //$tmpJqgrid = $this -> ClsComFnc -> FncCreateJqGridDataIndex($result["data"], $totalPage, $page, $tmpCount);
                $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridDataReload($this->result["data"], $totalPage, $page, $tmpCount, $start);
                //20170522 LQS UPD E
                $this->result = $tmpJqgrid;
            } else {
                $this->result = $result;
            }
        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }
        $this->fncReturn($this->result);
    }

}
