<?php
namespace App\Controller\HMTVE;
use App\Controller\AppController;
use App\Model\HMTVE\HMTVE090ExhibitionEntry;
//*******************************************
// * sample controller
//*******************************************
class HMTVE090ExhibitionEntryController extends AppController
{
    public $autoLayout = TRUE;
    public $HMTVE090ExhibitionEntry;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
    }
    public function index()
    {
        // 画面表示内容の設定
        $this->render('index', 'HMTVE090ExhibitionEntry_layout');
    }

    //カレンダーデータを取得する（一ヶ月）
    public function calendarDayRender()
    {
        $result = array(
            'result' => false,
            'data' => null,
            'dataFlg' => null,
            'error' => ''
        );
        try {
            $this->HMTVE090ExhibitionEntry = new HMTVE090ExhibitionEntry();

            $postdata = $_POST['data'];
            $result = $this->HMTVE090ExhibitionEntry->calendarDayRender($postdata['STDT'], $postdata['EDDT']);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            } else {
                $result = $this->createFullCalendar($result['data']);
                if (!$result['result']) {
                    throw new \Exception($result['error']);
                }
            }

            $resultFlag = $this->HMTVE090ExhibitionEntry->baseFlagGet();
            if (!$resultFlag['result']) {
                throw new \Exception($resultFlag['data']);
            }

            if (count((array) $resultFlag['data']) < 1) {
                $result['dataFlg'] = array();
            } else {
                $result['dataFlg'] = $resultFlag['data'];
            }
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //カレンダー内の日付セルクリック時,展示会データを取得する
    public function calendarSelectionChanged()
    {
        $result = array(
            'result' => false,
            'data' => null,
            'error' => ''
        );
        try {
            $this->HMTVE090ExhibitionEntry = new HMTVE090ExhibitionEntry();

            $postdata = $_POST['data'];
            $result = $this->HMTVE090ExhibitionEntry->calendarSelectionChanged($postdata['STDT']);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            } else {
                $result = $this->createFullCalendar($result['data']);
                if (!$result['result']) {
                    throw new \Exception($result['error']);
                }

            }
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //DetailsViewのデータソース設定
    public function bindDetailView()
    {
        $result = array(
            'result' => false,
            'data' => null,
            'error' => ''
        );
        try {
            $this->HMTVE090ExhibitionEntry = new HMTVE090ExhibitionEntry();

            $postdata = $_POST['data'];
            $result = $this->HMTVE090ExhibitionEntry->bindDetailView($postdata['STDT']);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            } else {
                $result = $this->createFullCalendar($result['data']);
                if (!$result['result']) {
                    throw new \Exception($result['error']);
                }
            }
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //更新ボタンクリックの場合,更新処理を行う
    public function btnEditOnClick()
    {
        $result = array(
            'result' => false,
            'error' => ''
        );
        $blnTran = FALSE;
        try {
            $this->HMTVE090ExhibitionEntry = new HMTVE090ExhibitionEntry();

            $postdata = $_POST['data'];
            $this->HMTVE090ExhibitionEntry->Do_transaction();
            $blnTran = TRUE;
            if ($postdata['BASE_FLG'] == 1) {
                $result = $this->HMTVE090ExhibitionEntry->baseFlagNull();
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
            }
            $postdata['UPD_PRG_ID'] = "ExbEntry";
            $result = $this->HMTVE090ExhibitionEntry->update($postdata);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $this->HMTVE090ExhibitionEntry->Do_commit();

        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();

            if ($blnTran) {
                $this->HMTVE090ExhibitionEntry->Do_rollback();
            }
        }
        $result['data'] = '';
        $this->fncReturn($result);
    }

    //追加ボタンクリックの場合,追加処理を行う
    public function btnInsertOnClick()
    {
        $result = array(
            'result' => false,
            'error' => ''
        );
        $blnTran = FALSE;
        try {
            $this->HMTVE090ExhibitionEntry = new HMTVE090ExhibitionEntry();

            $postdata = $_POST['data'];
            $result = $this->HMTVE090ExhibitionEntry->existCheck($postdata['STDT']);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            } else {
                if (count((array) $result['data']) > 0) {
                    throw new \Exception('E9999');
                }
            }

            $this->HMTVE090ExhibitionEntry->Do_transaction();
            $blnTran = TRUE;
            if ($postdata['BASE_FLG'] == 1) {
                $result = $this->HMTVE090ExhibitionEntry->baseFlagNull();
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
            }
            $postdata['UPD_PRG_ID'] = "ExbEntry";
            $result = $this->HMTVE090ExhibitionEntry->insert($postdata);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $this->HMTVE090ExhibitionEntry->Do_commit();

        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();

            if ($blnTran) {
                $this->HMTVE090ExhibitionEntry->Do_rollback();
            }
        }
        $result['data'] = '';
        $this->fncReturn($result);
    }

    //削除ボタンクリックの場合,削除処理を行う
    public function btnDeleteOnClick()
    {
        $result = array(
            'result' => false,
            'error' => ''
        );
        $blnTran = FALSE;
        try {
            $this->HMTVE090ExhibitionEntry = new HMTVE090ExhibitionEntry();

            $postdata = $_POST['data'];
            $this->HMTVE090ExhibitionEntry->Do_transaction();
            $blnTran = TRUE;

            $result = $this->HMTVE090ExhibitionEntry->delete($postdata['STDT']);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $this->HMTVE090ExhibitionEntry->Do_commit();

        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();

            if ($blnTran) {
                $this->HMTVE090ExhibitionEntry->Do_rollback();
            }
        }
        $result['data'] = '';
        $this->fncReturn($result);
    }

    //fullcalendarのデータ形式によって変換する
    function createFullCalendar($data)
    {
        $result = array(
            'result' => false,
            'data' => null,
            'error' => ''
        );
        try {
            $newData = array();

            foreach ($data as $value) {
                $tempData = array();
                foreach ($value as $k => $v) {
                    if ($k == 'START_DATE') {
                        $tempData['start'] = date("Y-m-d", strtotime($v));
                    } elseif ($k == 'END_DATE') {
                        $tempData['end'] = date("Y-m-d", strtotime($v)) . 'T24:00:00';
                    } elseif ($k == 'IVENT_NM') {
                        $tempData['title'] = $v;
                    } elseif ($k == 'BASE_FLG') {
                        $tempData['BASE_FLG'] = filter_var($v, FILTER_VALIDATE_BOOLEAN);
                    } elseif ($k == 'CREATE_DATE') {
                        $tempData['CREATE_DATE'] = $v;
                    }
                }
                array_push($newData, $tempData);
            }
            $result['result'] = true;
            $result['data'] = $newData;

            return $result;

        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();

            return $result;
        }

    }

    public function getSysDate()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );

        try {
            //時間取得
            $strStartDate = $this->ClsComFncHMTVE->FncGetSysDate("Y/m/d H:i:s");
            $result['data'] = $strStartDate;

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

}
