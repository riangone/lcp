<?php
/**
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20230103           機能追加　　　　　　          20221226_内部統制_仕様変更        YIN
 * 20250403            機能追加　　　　　　202504_内部統制_要望.xlsx             YIN
 * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\HMAUD;

use App\Controller\AppController;
use App\Model\HMAUD\HMAUDSKDToroku;

//*******************************************
// * sample controller
//*******************************************
class HMAUDSKDTorokuController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFncHMAUD');
    }

    public function index()
    {
        // 画面表示内容の設定
        $this->render('index', 'HMAUDSKDToroku_layout');
    }

    public function getMainData()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            //データの取得
            if (isset($_POST['data'])) {
                $postData = $_POST['data'];

                $HMAUDSKDToroku = new HMAUDSKDToroku();

                $mainRes = $HMAUDSKDToroku->getMainData($postData);
                if (!$mainRes['result']) {
                    throw new \Exception($mainRes['data']);
                }
                $defaultMembersRes = $HMAUDSKDToroku->getDefaultMembers($postData);
                if (!$defaultMembersRes['result']) {
                    throw new \Exception($defaultMembersRes['data']);
                }
                $defaultMembers = $defaultMembersRes['data'][0];
                if (count((array) $mainRes['data']) > 0) {
                    $mainData = $mainRes['data'][0];
                    $mainData['RESPONSIBLE_KYOTEN'] = $defaultMembers['RESPONSIBLE_EIGYO'];
                    $mainData['RESPONSIBLE_KYOTEN_NAME'] = $defaultMembers['RESPONSIBLE_EIGYO_NAME'];
                    $mainData['RESPONSIBLE_TERRITORY'] = $defaultMembers['RESPONSIBLE_TERRITORY'];
                    $mainData['RESPONSIBLE_TERRITORY_NAME'] = $defaultMembers['RESPONSIBLE_TERRITORY_NAME'];
                    $mainData['KEY_PERSON'] = $defaultMembers['KEY_PERSON'];
                    $mainData['KEY_PERSON_NAME'] = $defaultMembers['KEY_PERSON_NAME'];
                    $check_id = $mainData['CHECK_ID'];
                    $memberRes = $HMAUDSKDToroku->getMembers($check_id);
                    if (!$memberRes['result']) {
                        throw new \Exception($memberRes['data']);
                    }
                    $memberData = $memberRes['data'];
                    foreach ((array) $mainRes['data'] as $value) {
                        if ($value['ROLE'] == '2') {
                            $mainData['IMPROVEMENT_REPORT'] = $value['MEMBER'];
                            $mainData['IMPROVEMENT_REPORT_NAME'] = $value['SYAIN_NM'];
                        } else
                            if ($value['ROLE'] == '3') {
                                $mainData['RESPONSIBLE_KYOTEN'] = $value['MEMBER'];
                                $mainData['RESPONSIBLE_KYOTEN_NAME'] = $value['SYAIN_NM'];
                            } else
                                if ($value['ROLE'] == '4') {
                                    $mainData['RESPONSIBLE_TERRITORY'] = $value['MEMBER'];
                                    $mainData['RESPONSIBLE_TERRITORY_NAME'] = $value['SYAIN_NM'];
                                } else
                                    if ($value['ROLE'] == '5') {
                                        $mainData['KEY_PERSON'] = $value['MEMBER'];
                                        $mainData['KEY_PERSON_NAME'] = $value['SYAIN_NM'];
                                    } else
                                        if ($value['ROLE'] == '6') {
                                            $mainData['DIRECTOR_GENERAL'] = $value['MEMBER'];
                                            $mainData['DIRECTOR_GENERAL_NAME'] = $value['SYAIN_NM'];
                                        }
                                        // 20230103 YIN INS S
                                        else
                                            if ($value['ROLE'] == '7') {
                                                $mainData['EXECUTIVE'] = $value['MEMBER'];
                                                $mainData['EXECUTIVE_NAME'] = $value['SYAIN_NM'];
                                            }
                                            // 20230103 YIN INS E
                                            // 20250403 YIN INS S
                                            else
                                                if ($value['ROLE'] == '8') {
                                                    $mainData['VICE_PRESIDENT'] = $value['MEMBER'];
                                                    $mainData['VICE_PRESIDENT_NAME'] = $value['SYAIN_NM'];
                                                }
                                                // 20250403 YIN INS E
                                                else
                                                    // 20250403 YIN UPD S
                                                    // if ($value['ROLE'] == '8') {
                                                    if ($value['ROLE'] == '9') {
                                                        // 20250403 YIN UPD E
                                                        $mainData['PRESIDENT'] = $value['MEMBER'];
                                                        $mainData['PRESIDENT_NAME'] = $value['SYAIN_NM'];
                                                    }
                    }

                    $result['data']['mainData'] = $mainData;
                    $result['data']['memberData'] = $memberData;

                } else {
                    $result['data']['data'] = 'W0024';
                    $result['data']['defaultMembers'] = $defaultMembersRes['data'][0];
                }

                $syainRes = $HMAUDSKDToroku->getsyains();
                if (!$syainRes['result']) {
                    throw new \Exception($syainRes['data']);
                }
                $syainmst = $syainRes['data'];
                $result['data']['syainmst'] = $syainmst;

                $result['result'] = true;
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);

    }

    public function updMainData()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );

        try {
            //データの取得
            if (isset($_POST['data'])) {
                $postData = $_POST['data'];
                $HMAUDSKDToroku = new HMAUDSKDToroku();

                $mainRes = $HMAUDSKDToroku->getMainData($postData['mainData']);
                if (!$mainRes['result']) {
                    throw new \Exception($mainRes['data']);
                }
                $HMAUDSKDToroku->Do_transaction();
                $blnTran = TRUE;
                $checkId = "";
                if (count((array) $mainRes['data']) > 0) {
                    $checkId = $mainRes['data'][0]['CHECK_ID'];
                    $postData['mainData']['CHECK_ID'] = $checkId;
                    $updMainRes = $HMAUDSKDToroku->updMainData($postData['mainData']);
                    if (!$updMainRes['result']) {
                        throw new \Exception($updMainRes['data']);
                    }
                    $memberDelRes = $HMAUDSKDToroku->memberDel($checkId);
                    if (!$memberDelRes['result']) {
                        throw new \Exception($memberDelRes['data']);
                    }
                } else {
                    $maxMainRes = $HMAUDSKDToroku->maxMainData();
                    if (!$maxMainRes['result']) {
                        throw new \Exception($maxMainRes['data']);
                    }
                    $checkId = $maxMainRes['data'][0]['CHECK_ID'] + 1;
                    $postData['mainData']['CHECK_ID'] = $checkId;
                    $insMainRes = $HMAUDSKDToroku->insMainData($postData['mainData']);
                    if (!$insMainRes['result']) {
                        throw new \Exception($insMainRes['data']);
                    }

                }
                foreach ($postData['checkMemberData'] as $value) {
                    $insMemberRes = $HMAUDSKDToroku->insMemberData($value, $checkId);
                    if (!$insMemberRes['result']) {
                        throw new \Exception($insMemberRes['data']);
                    }
                }
                $getHeaderRes = $HMAUDSKDToroku->getHeaderData($checkId);
                if (!$getHeaderRes['result']) {
                    throw new \Exception($getHeaderRes['data']);
                }
                if (count((array) $getHeaderRes['data']) == 0) {
                    $insHeaderRes = $HMAUDSKDToroku->insHeaderData($checkId);
                    if (!$insHeaderRes['result']) {
                        throw new \Exception($insHeaderRes['data']);
                    }
                }

                $HMAUDSKDToroku->Do_commit();
                $result['result'] = true;
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
            if ($blnTran) {
                $HMAUDSKDToroku->Do_rollback();
            }
        }
        $result['data'] = '';

        $this->fncReturn($result);
    }

}