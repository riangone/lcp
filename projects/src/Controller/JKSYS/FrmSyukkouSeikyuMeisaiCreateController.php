<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmSyukkouSeikyuMeisaiCreate;
//*******************************************
// * sample controller
//*******************************************
class FrmSyukkouSeikyuMeisaiCreateController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    // var $components = array(
    //     'RequestHandler',
    //     'ClsComFncJKSYS'
    // );
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncJKSYS');
    }
    /*
           ***********************************************************************
           '処 理 名：初期表示
           '関 数 名：index
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：
           '**********************************************************************
           */
    public function index()
    {
        $this->render('index', 'FrmSyukkouSeikyuMeisaiCreate_layout');
    }

    /*
           ***********************************************************************
           '処 理 名：出向者請求明細データの取得
           '関 数 名：procGetSeikyuMeisaiData
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：
           '**********************************************************************
           */
    public function procGetSeikyuMeisaiData()
    {
        $result = array(
            'result' => false,
            'data' => 'ErrorInfo'
        );

        $isExistMeisaiData = true;

        try {
            $FrmSyukkouSeikyuMeisaiCreate = new FrmSyukkouSeikyuMeisaiCreate();
            //フォームロード
            if (isset($_POST['request'])) {
                $dtpYM = $_POST['request']['MstYM'];
                //出向者請求明細データの取得
                $DT_SSM = $FrmSyukkouSeikyuMeisaiCreate->procGetSeikyuMeisaiData($dtpYM);
                if (!$DT_SSM['result']) {
                    throw new \Exception($DT_SSM['data']);
                }
                if ($DT_SSM['row'] == 0) {
                    //該当データがない場合はマスタから取得
                    $DT_SSM = $FrmSyukkouSeikyuMeisaiCreate->procGetSyainMstData($dtpYM);
                    if (!$DT_SSM['result']) {
                        throw new \Exception($DT_SSM['data']);
                    }

                    $isExistMeisaiData = False;
                }
                $tmpJqgridShow = $this->ClsComFncJKSYS->FncCreateJqGridShow($DT_SSM['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $result = $this->ClsComFncJKSYS->FncCreateJqGridData($DT_SSM["data"], $totalPage, $page, $tmpCount);
                $result->isExistMeisaiData = $isExistMeisaiData;
            }
        } catch (\Exception $e) {
            $result['result'] = true;
            $result['error'] = $e->getMessage();
        }
        // Viewファイル呼出し
        $this->fncReturn($result);
    }

    /*
           ***********************************************************************
           '処 理 名：人事コントロールマスタの処理年月取得
           '関 数 名：procGetJinjiCtrlMstYM
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：
           '**********************************************************************
           */
    public function procGetJinjiCtrlMstYM()
    {
        $result = array(
            'result' => false,
            'error' => ''
        );

        try {
            $postdata = $_POST['data'];
            $FrmSyukkouSeikyuMeisaiCreate = new FrmSyukkouSeikyuMeisaiCreate();

            if ($postdata['isFormLoad']) {
                //フォームロード
                $strRetYM = $FrmSyukkouSeikyuMeisaiCreate->procGetJinjiCtrlMst_YM();
                if (!$strRetYM['result']) {
                    throw new \Exception($strRetYM['data']);
                }
                $SYORI_YM = "";
                //処理年月
                if ($strRetYM['row'] > 0) {
                    //日付形式を確認する
                    $SYORI_YM = $strRetYM['data'][0]['SYORI_YM'];
                    $date = $SYORI_YM . '01';
                    if (date('Ymd', strtotime($date)) != $date) {
                        //年月格式正しくない
                        throw new \Exception("String \"" . $SYORI_YM . "\" から型 'Date' への変換は無効です。");
                    }
                    $result['data']['MstYM'] = $strRetYM['data'][0];
                } else {
                    //年月なし
                    throw new \Exception("コントロールマスタが存在しません！");
                }
                $result['data']['SYORI_YM'] = $SYORI_YM;
            }

            //出向者Comboboxのデータ取得
            $DT_S = $FrmSyukkouSeikyuMeisaiCreate->procGetSyukkousakiData();
            if (!$DT_S['result']) {
                throw new \Exception($DT_S['data']);
            }
            $result['data']['comboboxData'] = $DT_S['data'];

            //社員名称
            $syainName = $FrmSyukkouSeikyuMeisaiCreate->fncGetName();
            if (!$syainName['result']) {
                throw new \Exception($syainName['data']);
            }
            $result['data']['syainName'] = $syainName['data'];

            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }

        // Viewファイル呼出し
        $this->fncReturn($result);
    }

    /*
           ***********************************************************************
           '処 理 名：データの存在チェック
           '関 数 名：fncCheckData
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：
           '**********************************************************************
           */
    public function fncCheckData()
    {
        $result = array(
            'result' => false,
            'error' => ''
        );

        try {
            $data = $_POST['data'];
            $FrmSyukkouSeikyuMeisaiCreate = new FrmSyukkouSeikyuMeisaiCreate();

            //①	支給データに画面．対象年月のデータが存在しない場合、エラー。メッセージを表示し、処理を中断します。
            $result = $FrmSyukkouSeikyuMeisaiCreate->procCheckDataLogic(1, $data);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            } else {
                if ($result['data'][0]['CNT'] == 0) {
                    //メッセージコード：W9999 %1=画面．対象年月 & "月分の支給データが存在しません。給与データの取込を行ってください"
                    $result['result'] = false;
                    $result['row'] = '1';
                    throw new \Exception('W9999');
                }
            }
            //②	事業主データに画面．対象年月のデータが存在しない場合、エラー。メッセージを表示し、処理を中断します。
            $result = $FrmSyukkouSeikyuMeisaiCreate->procCheckDataLogic(2, $data);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            } else {
                if ($result['data'][0]['CNT'] == 0) {
                    //メッセージコード：W9999　%1=画面．対象年月 & "月分の事業主データが存在しません。給与データの取込を行ってください"
                    $result['result'] = false;
                    $result['row'] = '2';
                    throw new \Exception('W9999');
                }
            }
            //③	画面．対象年月（月）＝変数．夏季ボーナス月の場合、
            //  支給データ．対象年月＝画面．対象年月　かつ　支給データ．給与・賞与区分＝"2"　（賞与）のデータが存在しない場合、エラー
            //       エラーメッセージを表示し、処理を中断します。
            if (substr($data['dtpYM'], 4, 2) == $data['summer']) {
                $result = $FrmSyukkouSeikyuMeisaiCreate->procCheckDataLogic(3, $data);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {
                    if ($result['data'][0]['CNT'] == 0) {
                        //メッセージコード：W9999　%1="夏季賞与データが存在しません。" & 画面．対象年月 & "月は賞与支給月ですので、賞与データの取込を行ってください"
                        $result['result'] = false;
                        $result['row'] = '3';
                        throw new \Exception('W9999');
                    }
                }
            }
            //④	画面．対象年月（月）＝変数．冬季ボーナス月の場合、
            //  支給データ．対象年月＝画面．対象年月　かつ　支給データ．給与・賞与区分＝"2"　（賞与）のデータが存在しない場合、エラー
            //       エラーメッセージを表示し、処理を中断します。
            if (substr($data['dtpYM'], 4, 2) == $data['winter']) {
                $result = $FrmSyukkouSeikyuMeisaiCreate->procCheckDataLogic(4, $data);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {
                    if ($result['data'][0]['CNT'] == 0) {
                        //メッセージコード：W9999 %1="冬季賞与データが存在しません。" & 画面．対象年月 & "月は賞与支給月ですので、賞与データの取込を行ってください"
                        $result['result'] = false;
                        $result['row'] = '4';
                        throw new \Exception('W9999');
                    }
                }
            }

            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }

        // Viewファイル呼出し
        $this->fncReturn($result);
    }

    /*
           ***********************************************************************
           '処 理 名：出向者請求明細データの生成
           '関 数 名：procCreateSeikyuMeisai
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：
           '**********************************************************************
           */
    public function procCreateSeikyuMeisai()
    {
        $result = array(
            'result' => false,
            'error' => ''
        );
        $blnTran = FALSE;

        $FrmSyukkouSeikyuMeisaiCreate = new FrmSyukkouSeikyuMeisaiCreate();

        try {
            $data = $_POST['data'];
            //トランザクション開始
            $FrmSyukkouSeikyuMeisaiCreate->Do_transaction();
            $blnTran = TRUE;
            //５－１．ワーク出向社員請求明細対象者データを削除します。
            $resultDel = $FrmSyukkouSeikyuMeisaiCreate->procCreateSeikyuMeisai();
            if (!$resultDel['result']) {
                throw new \Exception($resultDel['data']);
            }

            //５－２．入力領域スプレッド件数分繰り返す
            foreach ($data['data'] as $value) {
                //○更新対象欄にチェックが入っている場合
                //   ①	出向社員請求明細データを削除する
                //       条件
                //           出向社員請求明細データ．対象年月＝画面．対象年月
                //           出向社員請求明細データ．社員番号＝画面（入力領域)．社員番号
                $resultDel = $FrmSyukkouSeikyuMeisaiCreate->procDeleteSeikyuMeisaiData($data['dtpYM'], $value['SYAIN_NO']);
                if (!$resultDel['result']) {
                    throw new \Exception($resultDel['data']);
                }

                //○削除対象欄にチェックが入っていない場合
                if ($value['chkDelete'] == 'false') {
                    //   ①	ワーク出向社員請求明細対象者データ
                    //       対象年月＝画面．対象年月
                    //       社員番号＝画面（入力領域）．社員番号
                    //       出向先部署コード＝画面（入力領域）．出向先.value
                    //       出勤日数＝画面（入力領域）．日割日数(出勤）
                    //       就業日数＝画面（入力領域）．日割日数(月）
                    $resultIns = $FrmSyukkouSeikyuMeisaiCreate->procInsertWorkData($data['dtpYM'], $value);
                    if (!$resultIns['result']) {
                        throw new \Exception($resultIns['data']);
                    }
                }

            }

            //５－３．出向社員請求明細データに請求明細情報を登録する
            //①	給与データを出向社員請求明細データに登録する
            $resultIns = $FrmSyukkouSeikyuMeisaiCreate->procInsertSeikyuMeisaiDataFromKyuyo($data['dtpYM']);
            if (!$resultIns['result']) {
                throw new \Exception($resultIns['data']);
            }

            //②	賞与データを出向社員請求明細データに更新する
            if (substr($data['dtpYM'], 4, 2) == $data['prvMonth_Summer'] || substr($data['dtpYM'], 4, 2) == $data['prvMonth_Winter']) {
                //i.	画面．対象年月（月）＝変数．夏季ボーナス月又は画面．対象年月（月）＝変数．冬季ボーナス月の場合
                //      １．賞与を再見積りし請求明細データに登録する
                $resultSel = $FrmSyukkouSeikyuMeisaiCreate->procUpdateSeikyuMeisaiDataFromSyoyo($data['dtpYM']);
                if (!$resultSel['result']) {
                    throw new \Exception($resultSel['data']);
                }
                foreach ((array) $resultSel['data'] as $value) {
                    $resultUpd = $FrmSyukkouSeikyuMeisaiCreate->procUpdateSeikyuMeisaiDataFromSyoyo2($value);
                    if (!$resultUpd['result']) {
                        throw new \Exception($resultUpd['data']);
                    }
                }

                //２．実際の賞与と見積り額の差額調整を行う
                //								実際の賞与ー見積の賞与で差額を調整する
                //									※「テーブル編集仕様書③」を参照
                //									結合条件
                //										実際の賞与．社員番号＝見積の賞与．社員番号
                //									更新条件
                //										出向社員請求明細データ．対象年月＝画面．対象年月
                //										出向社員請求明細データ．社員番号＝実際の賞与．社員番号
                $resultSel = $FrmSyukkouSeikyuMeisaiCreate->procUpdateSeikyuMeisaiDataTyousei($data);
                if (!$resultSel['result']) {
                    throw new \Exception($resultSel['data']);
                }
                foreach ((array) $resultSel['data'] as $value) {
                    $resultUpd = $FrmSyukkouSeikyuMeisaiCreate->procUpdateSeikyuMeisaiDataTyousei2($data['dtpYM'], $value);
                    if (!$resultUpd['result']) {
                        throw new \Exception($resultUpd['data']);
                    }
                }
            } else {
                //ⅱ．	上記以外の場合
                //						１．前月の賞与見積り額を計上する
                //							※「テーブル編集仕様書④」を参照
                //							使用テーブル
                //								出向社員請求明細データ　AS 前月出向社員請求情報
                //							抽出条件
                //								前月出向社員請求情報．対象年月＝画面．対象年月ー１月
                //							更新条件
                //								出向社員請求明細データ．対象年月＝画面．対象年月
                //								出向社員請求明細データ．社員番号＝前月出向社員請求情報．社員番号
                $resultSel = $FrmSyukkouSeikyuMeisaiCreate->procUpdateSeikyuMeisaiDataKurikoshi($data);
                if (!$resultSel['result']) {
                    throw new \Exception($resultSel['data']);
                }
                foreach ((array) $resultSel['data'] as $value) {
                    $resultUpd = $FrmSyukkouSeikyuMeisaiCreate->procUpdateSeikyuMeisaiDataKurikoshi2($value);
                    if (!$resultUpd['result']) {
                        throw new \Exception($resultUpd['data']);
                    }
                }
            }
            //コミット
            $FrmSyukkouSeikyuMeisaiCreate->Do_commit();
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
            //ロールバック
            if ($blnTran) {
                $FrmSyukkouSeikyuMeisaiCreate->Do_rollback();
            }
        }
        // Viewファイル呼出し
        $this->fncReturn($result);
    }

}
