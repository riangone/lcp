<?php
namespace App\Controller\HMTVE;

use App\Controller\AppController;
use App\Model\HMTVE\HMTVE310HSYAINMSTEntry;
//*******************************************
// * sample controller
//*******************************************
class HMTVE310HSYAINMSTEntryController extends AppController
{
    public $autoLayout = TRUE;
    public $FrmJinkenhiEnt = "";
    private $HMTVE310HSYAINMSTEntry;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
    }
    public function index()
    {
        // 画面表示内容の設定
        $this->render('index', 'HMTVE310HSYAINMSTEntry_layout');
    }

    public function updateData()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['request'])) {
                $postdata = $_POST['request'];
                $this->HMTVE310HSYAINMSTEntry = new HMTVE310HSYAINMSTEntry();
                $result = $this->HMTVE310HSYAINMSTEntry->UpdateDataSql($postdata['SYAIN_NO']);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                if ($result['row'] > 0) {
                    $rowIdArray = $result['data'][0];
                    for ($i = 0; $i < count((array) $result['data']); $i++) {
                        $result['data'][$i]['id'] = $i;
                    }
                } else {
                    $rowIdArray = array('');
                }
                $tmpJqgridShow = $this->ClsComFncHMTVE->FncCreateJqGridShow($result['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $result = $this->ClsComFncHMTVE->FncCreateJqGridDataIndex($result["data"], $totalPage, $page, $tmpCount);
                $result->inputDate = $rowIdArray;
            } else {
                throw new \Exception('値が受信されません');
            }
        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function btnAddClick()
    {
        $blnTran = FALSE;
        $result = array(
            'result' => FALSE,
            'data' => '',
            'key' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['data'])) {
                $postdata = $_POST['data'];
                $this->HMTVE310HSYAINMSTEntry = new HMTVE310HSYAINMSTEntry();
                //トランザクション開始
                $this->HMTVE310HSYAINMSTEntry->Do_transaction();
                $blnTran = TRUE;
                $resultFLG = $this->HMTVE310HSYAINMSTEntry->getSqlCheck($postdata['SYAIN_NO']);
                if (!$resultFLG['result']) {
                    throw new \Exception($resultFLG['data']);
                }
                if ($postdata['MODE'] == 1 || $postdata['MODE'] == '') {
                    if ($resultFLG['row'] != 0) {
                        $result['key'] = 'E0016';
                        throw new \Exception('E0016');
                    } else {
                        $resultINS = $this->HMTVE310HSYAINMSTEntry->insertHSYAINMST($postdata);
                        if (!$resultINS['result']) {
                            throw new \Exception($resultINS['data']);
                        }
                        $resultDEL = $this->HMTVE310HSYAINMSTEntry->DeleteHHAIZOKU($postdata['SYAIN_NO']);
                        if (!$resultDEL['result']) {
                            throw new \Exception($resultDEL['data']);
                        }
                        if (isset($postdata['tableData']) && is_array($postdata['tableData'])) {
                            for ($i = 0; $i < count($postdata['tableData']); $i++) {
                                $result = $this->HMTVE310HSYAINMSTEntry->insertHHAIZOKU($postdata, $postdata['tableData'][$i], $i);
                                if (!$result['result']) {
                                    throw new \Exception($result['data']);
                                }
                            }
                        }
                    }

                } else
                    if ($postdata['MODE'] == 2) {
                        if ($resultFLG['row'] == 0) {
                            $result['key'] = 'W0024';
                            throw new \Exception('W0024');
                        } else {
                            if ($this->ClsComFncHMTVE->FncNv($resultFLG['data'][0]['UPD_PRG_ID']) != "HSYAINMSTENTRY") {
                                if (isset($postdata['tableData']) && is_array($postdata['tableData'])) {
                                    for ($i = 0; $i < count($postdata['tableData']); $i++) {
                                        $result = $this->HMTVE310HSYAINMSTEntry->fncUpdHaizokusaki($postdata, $postdata['tableData'][$i]);
                                        if (!$result['result']) {
                                            throw new \Exception($result['data']);
                                        }
                                    }
                                }
                            } else {
                                $resultHSY = $this->HMTVE310HSYAINMSTEntry->updateHSYAINMST($postdata);
                                if (!$resultHSY['result']) {
                                    throw new \Exception($resultHSY['data']);
                                }
                                $resultDEL = $this->HMTVE310HSYAINMSTEntry->DeleteHHAIZOKU($postdata['SYAIN_NO']);
                                if (!$resultDEL['result']) {
                                    throw new \Exception($resultDEL['data']);
                                }
                                if (isset($postdata['tableData']) && is_array($postdata['tableData'])) {
                                    for ($i = 0; $i < count($postdata['tableData']); $i++) {
                                        $result = $this->HMTVE310HSYAINMSTEntry->insertHHAIZOKU($postdata, $postdata['tableData'][$i], $i);
                                        if (!$result['result']) {
                                            throw new \Exception($result['data']);
                                        }
                                    }
                                }
                            }
                        }
                    } else
                        if ($postdata['MODE'] == 3) {
                            if ($resultFLG['row'] == 0) {
                                $result['key'] = 'W0024';
                                throw new \Exception('W0024');
                            } else {
                                $resultDEL = $this->HMTVE310HSYAINMSTEntry->DeleteHHAIZOKU($postdata['SYAIN_NO']);
                                if (!$resultDEL['result']) {
                                    throw new \Exception($resultDEL['data']);
                                }
                                $result = $this->HMTVE310HSYAINMSTEntry->DeleteHSYAINMST($postdata['SYAIN_NO']);
                                if (!$result['result']) {
                                    throw new \Exception($result['data']);
                                }
                            }
                        }
                $result['key'] = '';
                // コミット
                $this->HMTVE310HSYAINMSTEntry->Do_commit();
                $result['result'] = TRUE;
            }
        } catch (\Exception $e) {
            //ロールバック
            if ($blnTran) {
                $this->HMTVE310HSYAINMSTEntry->Do_rollback();
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $result['data'] = '';

        $this->fncReturn($result);
    }
}
