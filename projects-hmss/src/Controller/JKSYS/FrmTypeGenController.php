<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmTypeGen;

//*******************************************
// * sample controller
//*******************************************
class FrmTypeGenController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    public $frmTypeGen;
    // var $components = array(
    //     'RequestHandler',
    //     'ClsComFncJKSYS'
    // );
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncJKSYS');
    }
    //　デフォルトで最初に実行される機能
    public function index()
    {
        // Viewファイル呼出し
        $this->render('index', 'FrmTypeGen_layout');
    }

    //ページロード
    public function frmTypeGenLoad()
    {
        $res = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            $frmTypeGen = new FrmTypeGen();
            //データ取得(評価対象期間)
            $prvSyori = $frmTypeGen->Sel_JKCONTROLMST_SQL();
            if (!$prvSyori['result']) {
                throw new \Exception($prvSyori['data']);
            }
            //処理年月
            $prvSyoriYM = date('Y/m/d');
            $res['data']['prvSyoriYM'] = $prvSyoriYM;
            $res['data']['prvKakiBonusMonth'] = date('m');
            $res['data']['prvKakiBonusStartMt'] = date('m');
            $res['data']['prvKakiBonusEndMt'] = date('m');
            $res['data']['prvToukiBonusMonth'] = date('m');
            $res['data']['prvToukiBonusStartMt'] = date('m');
            $res['data']['prvToukiBonusEndMt'] = date('m');
            //データが存在する場合
            if ($prvSyori['row'] > 0) {
                //冬季ボーナス月
                $prvSyoriMT = $prvSyori['data'][0]['TOUKI_BONUS_MONTH'];
                //夏季ボーナス月
                $prvSyoriMK = $prvSyori['data'][0]['KAKI_BONUS_MONTH'];
                //夏季評価期間終了
                $prvSyoriSE = $prvSyori['data'][0]['KAKI_HYOUKA_END_MT'];
                //冬季評価期間終了
                $prvSyoriWE = $prvSyori['data'][0]['TOUKI_HYOUKA_END_MT'];
                //日付形式を確認する
                $dateMT = date('Y') . $prvSyoriMT . '01';
                $dateMK = date('Y') . $prvSyoriMK . '01';
                $dateSE = date('Y') . $prvSyoriSE . '01';
                $dateWE = date('Y') . $prvSyoriWE . '01';
                if (date('Ymd', strtotime($dateMT)) != $dateMT || date('Ymd', strtotime($dateMK)) != $dateMK || date('Ymd', strtotime($dateSE)) != $dateSE || date('Ymd', strtotime($dateWE)) != $dateWE) {
                    throw new \Exception("年月が不正です。yyyyMMを指定してください。");
                } else {
                    //夏季ボーナス月
                    $prvKakiBonusMonth = $this->ClsComFncJKSYS->FncNv($prvSyoriMK);
                    //夏季評価期間開始
                    $prvKakiBonusStartMt = $this->ClsComFncJKSYS->FncNv($prvSyori['data'][0]['KAKI_HYOUKA_START_MT']);
                    //夏季評価期間終了
                    $prvKakiBonusEndMt = $this->ClsComFncJKSYS->FncNv($prvSyoriSE);
                    //冬季ボーナス月
                    $prvToukiBonusMonth = $this->ClsComFncJKSYS->FncNv($prvSyoriMT);
                    //冬季評価期間開始
                    $prvToukiBonusStartMt = $this->ClsComFncJKSYS->FncNv($prvSyori['data'][0]['TOUKI_HYOUKA_START_MT']);
                    //冬季評価期間終了
                    $prvToukiBonusEndMt = $this->ClsComFncJKSYS->FncNv($prvSyoriWE);

                    $res['data']['prvKakiBonusMonth'] = $prvKakiBonusMonth;
                    $res['data']['prvKakiBonusStartMt'] = $prvKakiBonusStartMt;
                    $res['data']['prvKakiBonusEndMt'] = $prvKakiBonusEndMt;
                    $res['data']['prvToukiBonusMonth'] = $prvToukiBonusMonth;
                    $res['data']['prvToukiBonusStartMt'] = $prvToukiBonusStartMt;
                    $res['data']['prvToukiBonusEndMt'] = $prvToukiBonusEndMt;
                }
            } else {
                //年月なし
                throw new \Exception("コントロールマスタが存在しません！");
            }
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        // Viewファイル呼出し

        $this->fncReturn($res);
    }

    //社員別考課表タイプデータを作成する（存在チェック）
    public function fncCreateData()
    {
        $result = array(
            'result' => FALSE,
            'error' => '',
            'data' => ''
        );
        try {
            $frmTypeGen = new FrmTypeGen();
            if (isset($_POST['data'])) {
                $dtpYM = $_POST['data']['dtpTaisyouKE'];
                //(1)存在チェック
                $resultChk = $frmTypeGen->CHK_JKKOUKA_SYAIN_TYPE_SQL($dtpYM);
                if (!$resultChk['result']) {
                    throw new \Exception($resultChk['data']);
                }
                $result['data'] = $resultChk['data'][0];
            }
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        // Viewファイル呼出し
        $this->fncReturn($result);
    }

    //社員別考課表タイプデータを作成する
    public function cmdApplyClick()
    {
        $result = array(
            'result' => FALSE,
            'error' => '',
            'data' => ''
        );
        $blnTran = FALSE;
        $this->frmTypeGen = new FrmTypeGen();
        try {
            if (isset($_POST['data'])) {
                //発令年月日
                $strANNOUNCE_DT = '';
                //所属コード
                $strBUSYO_CD = '';
                //職種コード
                $strSYOKUSYU_CD = '';
                //考課表タイプコード
                $strKOUKATYPE_CD = '';
                //グループコード
                $strGROUP_CD = '';
                $dtpYM = $_POST['data']['dtpTaisyouKE'];
                $dtpYMD = $_POST['data']['dtpTaisyouKE'] . '01';
                //Months(-5)
                $dtpYMChk = date('Y/m/d', strtotime($dtpYMD . ' -5 month'));
                $dtpYMChkNoD = date('Ym', strtotime($dtpYMD . ' -5 month'));
                //トランザクション開始
                $this->frmTypeGen->Do_transaction();
                $blnTran = TRUE;
                //(2)社員別考課表タイプデータ、実績集計データ、周辺利益集計データを削除する
                //社員別考課表タイプデータ削除する
                $resultDelST = $this->frmTypeGen->DEL_JKKOUKA_SYAIN_TYPE_SQL($dtpYM);
                if (!$resultDelST['result']) {
                    throw new \Exception($resultDelST['data']);
                }
                //実績集計データ削除する
                $resultDelJJS = $this->frmTypeGen->DEL_JKKOUKA_JISSEKI_SYUKEI_SQL($dtpYM);
                if (!$resultDelJJS['result']) {
                    throw new \Exception($resultDelJJS['data']);
                }
                //周辺利益集計データ削除する
                $resultDelJSR = $this->frmTypeGen->DEL_JKKOUKA_SYUHEN_RIEKI_SQL($dtpYM);
                if (!$resultDelJSR['result']) {
                    throw new \Exception($resultDelJSR['data']);
                }

                //(3)社員別効果表タイプデータを作成する
                $resultSel = $this->frmTypeGen->Sel_DATA_SQL($dtpYM);
                if (!$resultSel['result']) {
                    throw new \Exception($resultSel['data']);
                }
                for ($intIdx = 0; $intIdx < count((array) $resultSel['data']); $intIdx++) {
                    $resultSelIdx = $resultSel['data'][$intIdx];
                    $dtpDayFoM = date('Ym', strtotime($resultSel['data'][$intIdx]['ANNOUNCE_DT_MAX']));
                    //Days(-1)
                    $dtpDayChk = date('Y/m', strtotime($resultSel['data'][$intIdx]['ANNOUNCE_DT_MAX'] . ' -1 day'));
                    //最大発令年月が期間開始日より小さい or
                    //評価期間終了月から最大発令年月が３ヶ月以上の場合
                    if ($resultSel['row'] > 0) {
                        $datetimeBef = date_create($dtpDayChk . '/01');
                        $datetimeAft = date_create(date('Y/m/d', strtotime($dtpYM . '01')));
                        // 20220419 lqs upd s
                        // $interval = date_diff($datetimeBef, $datetimeAft);
                        // $diffMM = ($interval -> m + ($interval -> y * 12));
                        $res = $this->frmTypeGen->getDiffMonth(date_format($datetimeBef, 'Y-m-01'), date_format($datetimeAft, 'Y-m-01'));
                        if (!$res['result']) {
                            throw new \Exception($res['data']);
                        }
                        $diffMM = $res['data'][0]['MONTHS'];
                        // 20220419 lqs upd E

                        if ($dtpDayFoM <= $dtpYMChkNoD || $diffMM >= 3 && $dtpDayFoM >= $dtpYMChkNoD) {
                            //考課表タイプ設定マスタデータ取得
                            $resultSelGT = $this->fncGetTYPE_SETTEI($resultSel['data'][$intIdx]['SYOKUSYU_CD'], $strKOUKATYPE_CD, $strGROUP_CD);
                            if (!$resultSelGT['result']) {
                                throw new \Exception($resultSelGT['error']);
                            }

                            $resultIns = $this->frmTypeGen->fncCreateDataBef($resultSelIdx, $dtpYM, $strKOUKATYPE_CD, $strGROUP_CD);
                            if (!$resultIns['result']) {
                                throw new \Exception($resultIns['data']);
                            }
                        } else {
                            //評価期間内で所属期間が最長のデータを取得する
                            $resultSelGM = $this->fncGetMaxIdouRireki($resultSel['data'][$intIdx]['SYAIN_NO'], $dtpYM, $dtpYMChk, $strANNOUNCE_DT, $strBUSYO_CD, $strSYOKUSYU_CD, $strKOUKATYPE_CD, $strGROUP_CD);
                            if (!$resultSelGM['result']) {
                                throw new \Exception($resultSelGM['error']);
                            }

                            $resultIns = $this->frmTypeGen->fncCreateDataAft($resultSelIdx, $dtpYM, $strBUSYO_CD, $strSYOKUSYU_CD, $strKOUKATYPE_CD, $strGROUP_CD);
                            if (!$resultIns['result']) {
                                throw new \Exception($resultIns['data']);
                            }
                        }
                    }
                }
                //コミット処理を行う
                $this->frmTypeGen->Do_commit();
                $blnTran = FALSE;
            }
            $result['result'] = TRUE;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
            if ($blnTran) {
                $this->frmTypeGen->Do_rollback();
            }
        }
        // Viewファイル呼出し
        $this->fncReturn($result);
    }

    //考課表タイプ設定マスタ取得
    public function fncGetTYPE_SETTEI($vSKS, &$rKOUKA_TYPE, &$rGROUP_CD)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $resultSelGTS = $this->frmTypeGen->fncGetTYPE_SETTEI($vSKS);
            if (!$resultSelGTS['result']) {
                throw new \Exception($resultSelGTS['data']);
            }
            if ($resultSelGTS['row'] <> 0) {
                $rKOUKA_TYPE = $resultSelGTS['data'][0]['KOUKATYPE_CD'];
                $rGROUP_CD = $resultSelGTS['data'][0]['GROUP_CD'];
            } else {
                $rKOUKA_TYPE = '';
                $rGROUP_CD = '';
            }
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    //異動履歴データから最長データの情報を取得する
    public function fncGetMaxIdouRireki($vSB, $dtpYM, $dtpYMChk, &$rANN_DT, &$rBSHO_CD, &$rSKS_CD, &$rTYP_CD, &$rGRP_CD)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            //古い発令日
            $strOLD_ANN = '';
            //発令日（決定）
            // $strFIX_ANN = '';

            $lngOLD_KIKAN = 0;
            //評価期間内で所属期間が最長のデータを取得する
            $resultSelGMIR = $this->frmTypeGen->fncGetMaxIdouRireki($vSB, $dtpYM);
            if (!$resultSelGMIR['result']) {
                throw new \Exception($resultSelGMIR['data']);
            }
            if ($resultSelGMIR['row'] > 0) {
                for ($intIdx = 0; $intIdx < count((array) $resultSelGMIR['data']); $intIdx++) {
                    if ($strOLD_ANN == '') {
                        $strOLD_ANN = date('Y/m/d', strtotime($resultSelGMIR['data'][$intIdx]['ANNOUNCE_DT']));
                        // $strFIX_ANN = date('Y/m/d', strtotime($resultSelGMIR['data'][$intIdx]['ANNOUNCE_DT']));
                        //発令年月日
                        $rANN_DT = date('Y/m/d', strtotime($resultSelGMIR['data'][$intIdx]['ANNOUNCE_DT']));
                        //所属コード
                        $rBSHO_CD = $resultSelGMIR['data'][$intIdx]['BUSYO_CD'];
                        //職種コード
                        $rSKS_CD = $resultSelGMIR['data'][$intIdx]['SYOKUSYU_CD'];
                        //考課表タイプコード
                        $rTYP_CD = $resultSelGMIR['data'][$intIdx]['KOUKATYPE_CD'];
                        //グループコード
                        $rGRP_CD = $resultSelGMIR['data'][$intIdx]['GROUP_CD'];

                        $lngOLD_KIKAN = 0;
                    }
                    //発令日が開始日より前のデータを読み込んだ場合
                    if ($resultSelGMIR['data'][$intIdx]['ANNOUNCE_DT'] <= $dtpYMChk) {
                        //対象期間の月数を取得する
                        $datetimeBef = date_create($dtpYMChk);
                        $datetimeAft = date_create($strOLD_ANN);
                        //20220419 lqs upd S
                        // $interval = date_diff($datetimeBef, $datetimeAft);
                        // $diffMM = ($interval -> m + ($interval -> y * 12));
                        $res = $this->frmTypeGen->getDiffMonth(date_format($datetimeBef, 'Y-m-01'), date_format($datetimeAft, 'Y-m-01'));
                        if (!$res['result']) {
                            throw new \Exception($res['data']);
                        }
                        $diffMM = $res['data'][0]['MONTHS'];
                        //20220419 lqs upd E

                        //期間が３ヶ月以上の場合は終了
                        if ($diffMM > 3) {
                            //発令年月日
                            $rANN_DT = date('Y/m/d', strtotime($resultSelGMIR['data'][$intIdx]['ANNOUNCE_DT']));
                            //所属コード
                            $rBSHO_CD = $resultSelGMIR['data'][$intIdx]['BUSYO_CD'];
                            //職種コード
                            $rSKS_CD = $resultSelGMIR['data'][$intIdx]['SYOKUSYU_CD'];
                            //考課表タイプコード
                            $rTYP_CD = $resultSelGMIR['data'][$intIdx]['KOUKATYPE_CD'];
                            //グループコード
                            $rGRP_CD = $resultSelGMIR['data'][$intIdx]['GROUP_CD'];
                        }
                        break;
                    } else {
                        //対象期間の月数を取得する
                        $datetimeBef = date_create(date('Y/m/d', strtotime($resultSelGMIR['data'][$intIdx]['ANNOUNCE_DT'])));
                        $datetimeAft = date_create($strOLD_ANN);
                        //20220419 lqs upd S
                        // $interval = date_diff($datetimeBef, $datetimeAft);
                        // $diffMM = ($interval -> m + ($interval -> y * 12));
                        $res = $this->frmTypeGen->getDiffMonth(date_format($datetimeBef, 'Y-m-01'), date_format($datetimeAft, 'Y-m-01'));
                        if (!$res['result']) {
                            throw new \Exception($res['data']);
                        }
                        $diffMM = $res['data'][0]['MONTHS'];
                        //20220419 lqs upd E

                        //期間が３ヶ月以上の場合は終了
                        if ($diffMM >= 3) {
                            //発令年月日
                            $rANN_DT = date('Y/m/d', strtotime($resultSelGMIR['data'][$intIdx]['ANNOUNCE_DT']));
                            //所属コード
                            $rBSHO_CD = $resultSelGMIR['data'][$intIdx]['BUSYO_CD'];
                            //職種コード
                            $rSKS_CD = $resultSelGMIR['data'][$intIdx]['SYOKUSYU_CD'];
                            //考課表タイプコード
                            $rTYP_CD = $resultSelGMIR['data'][$intIdx]['KOUKATYPE_CD'];
                            //グループコード
                            $rGRP_CD = $resultSelGMIR['data'][$intIdx]['GROUP_CD'];

                            break;
                        } else {
                            $lngKIKAN = $diffMM;
                            //前データの期間より大きい場合はデータを退避する
                            if ($lngOLD_KIKAN < $lngKIKAN || $lngKIKAN = 0) {
                                // $strFIX_ANN = date('Y/m/d', strtotime($resultSelGMIR['data'][$intIdx]['ANNOUNCE_DT']));
                                //発令年月日
                                $rANN_DT = date('Y/m/d', strtotime($resultSelGMIR['data'][$intIdx]['ANNOUNCE_DT']));
                                //所属コード
                                $rBSHO_CD = $resultSelGMIR['data'][$intIdx]['BUSYO_CD'];
                                //職種コード
                                $rSKS_CD = $resultSelGMIR['data'][$intIdx]['SYOKUSYU_CD'];
                                //考課表タイプコード
                                $rTYP_CD = $resultSelGMIR['data'][$intIdx]['KOUKATYPE_CD'];
                                //グループコード
                                $rGRP_CD = $resultSelGMIR['data'][$intIdx]['GROUP_CD'];

                            } else {
                                //前データの期間が大きい場合は
                                $lngOLD_KIKAN = $lngKIKAN;
                                $strOLD_ANN = date('Y/m/d', strtotime($resultSelGMIR['data'][$intIdx]['ANNOUNCE_DT']));
                            }
                        }
                    }

                    //発令日が開始日より前のデータを読み込んだ場合は終了
                    if ($resultSelGMIR['data'][0]['ANNOUNCE_DT'] <= $dtpYMChk) {
                        break;
                    } else {
                        $strOLD_ANN = date('Y/m/d', strtotime($resultSelGMIR['data'][$intIdx]['ANNOUNCE_DT']));
                    }
                }
            }
            $result['result'] = TRUE;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

}
