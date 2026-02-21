<?php
namespace App\Controller\HDKAIKEI;

use App\Controller\AppController;
use App\Model\HDKAIKEI\HDKKamokuSearch;

//*******************************************
// * sample controller
//*******************************************
class HDKKamokuSearchController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    public $HDKKamokuSearch = null;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHDKAIKEI');
    }
    //　デフォルトで最初に実行される機能
    public function index()
    {
        // Viewファイル呼出し
        $this->render('index', 'HDKKamokuSearch_layout');
    }
    public function searchmax($arr, $field)
    {
        if (!is_array($arr) || !$field || empty($arr)) { //判断是否是数组以及传过来的字段是否是空
            return 0;
        }
        $temp = array();
        foreach ($arr as $val) {
            $temp[] = $val[$field]; // 用一个空数组来承接字段
        }
        return max($temp); // 用php自带函数 max 来返回该数组的最大值，一维数组可直接用max函数
    }
    public function btnTreeHyoujiClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            //データの取得
            if (isset($_POST['request'])) {
                $postData = $_POST['request'];
                $postData['mode'] = 'tree';
                $this->HDKKamokuSearch = new HDKKamokuSearch();

                $resultP = $this->HDKKamokuSearch->GetTreeParent($postData);
                if (!$resultP['result']) {
                    throw new \Exception($resultP['data']);
                }
                $resultParent = $resultP['data'];

                $this->HDKKamokuSearch = new HDKKamokuSearch();

                $result = $this->HDKKamokuSearch->btnHyouji_Click($postData);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                $resultSon = $result['data'];

                $data = array();
                $maxValue = $this->searchmax($resultParent, 'RELATION_CD') + 1;
                foreach ((array) $resultParent as $value1) {
                    $value1['id'] = (int) $value1['RELATION_CD'];
                    $value1['expanded'] = true;
                    $value1['level'] = 0;
                    $value1['isLeaf'] = false;
                    $value1['PARENT_ID'] = null;
                    if ($value1["COUNTSON"] < 1) {
                        $value1["isLeaf"] = true;
                        array_push($data, $value1);
                        continue;
                    } else {
                        array_push($data, $value1);
                        foreach ((array) $resultSon as $key2 => $value2) {
                            if ($value1['RELATION_CD'] == $value2['PARENT_ID']) {
                                $subkamcd = $value2['SUB_KAMOK_CD'];
                                $subkam = $value2['SUB_KAMOK_NAME'];
                                if ($key2 == 0 || ($resultSon[$key2]['KAMOK_CD'] != $resultSon[$key2 - 1]['KAMOK_CD'])) {
                                    $maxValue++;
                                    $value2['SUB_KAMOK_CD'] = '';
                                    $value2['SUB_KAMOK_NAME'] = '';
                                    $value2['id'] = (int) $maxValue;
                                    $value2['expanded'] = true;
                                    $value2['level'] = 1;
                                    $value2['isLeaf'] = false;
                                    $pid = $value2['id'];
                                    array_push($data, $value2);
                                }
                                $value2['SUB_KAMOK_CD'] = $subkamcd;
                                $value2['SUB_KAMOK_NAME'] = $subkam;
                                $value2['expanded'] = true;
                                $value2['level'] = 2;
                                $value2['isLeaf'] = true;
                                $value2['PARENT_ID'] = $pid;
                                $maxValue++;
                                $value2['id'] = (int) $maxValue;
                                array_push($data, $value2);
                            }
                        }
                    }
                }
                $flg = 1;
                foreach ((array) $resultSon as $key2 => $value2) {
                    $subkamcd = $value2['SUB_KAMOK_CD'];
                    $subkam = $value2['SUB_KAMOK_NAME'];
                    if ($resultSon[$key2]['PARENT_ID'] == '') {
                        if ($flg == 1) {
                            $maxValue++;
                            $val['SUB_KAMOK_CD'] = '';
                            $val['SUB_KAMOK_NAME'] = '';
                            $val['id'] = (int) $maxValue;
                            $val['KAMOK_CD'] = '未分類';
                            $val['KAMOK_NAME'] = '';
                            $val['expanded'] = true;
                            $val['level'] = 0;
                            $val['isLeaf'] = false;
                            $val['PARENT_ID'] = null;
                            $pid2 = $val['id'];
                            array_push($data, $val);
                            $flg = 2;
                        }
                        if ($key2 == 0 || $resultSon[$key2]['KAMOK_CD'] != $resultSon[$key2 - 1]['KAMOK_CD']) {
                            $maxValue++;
                            $value2['SUB_KAMOK_CD'] = '';
                            $value2['SUB_KAMOK_NAME'] = '';
                            $value2['id'] = (int) $maxValue;
                            $value2['expanded'] = true;
                            $value2['level'] = 1;
                            $value2['isLeaf'] = false;
                            $value2['PARENT_ID'] = $pid2;
                            $pid = $value2['id'];
                            array_push($data, $value2);
                            $value2['SUB_KAMOK_CD'] = $subkamcd;
                            $value2['SUB_KAMOK_NAME'] = $subkam;
                        }
                        $value2['expanded'] = true;
                        $value2['level'] = 2;
                        $value2['isLeaf'] = true;
                        $value2['PARENT_ID'] = $pid;
                        $maxValue++;
                        $value2['id'] = (int) $maxValue;
                        array_push($data, $value2);
                    }
                }
                $result['data'] = $data;
                $tmpJqgridShow = $this->ClsComFncHDKAIKEI->FncCreateJqGridShow($result['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $tmpJqgrid = $this->ClsComFncHDKAIKEI->FncCreateJqGridDataIndex($result["data"], $totalPage, $page, $tmpCount);
                $tmpJqgrid->rows = $data;
                $result = $tmpJqgrid;
            }
        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }
    public function btnHyoujiClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            //データの取得
            if (isset($_POST['request'])) {
                $postData = $_POST['request'];
                $postData['mode'] = 'normal';
                $this->HDKKamokuSearch = new HDKKamokuSearch();

                $result = $this->HDKKamokuSearch->btnHyouji_Click($postData);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                $data = $result['data'];
                $result['data'] = $data;
                $tmpJqgridShow = $this->ClsComFncHDKAIKEI->FncCreateJqGridShow($result['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $tmpJqgrid = $this->ClsComFncHDKAIKEI->FncCreateJqGridDataIndex($result["data"], $totalPage, $page, $tmpCount);
                $result = $tmpJqgrid;
            }
        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }
}
