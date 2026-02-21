<?php
/**
 * 説明：
 *
 *
 * @author WANGYING,LIQIUSHUANG
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL　　　　　　　　
 * * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\APPM;

use App\Controller\AppController;
use App\Model\APPM\FrmUxJokenToroku;
class FrmUxJokenTorokuController extends AppController
{
    // ========== 変数 start ==========
    public $autoLayout = TRUE;
    public $result = array();
    // ========== 変数 end ==========
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsFncLog');
    }
    /**
     * デフォルトで最初に実行される機能
     */
    public function index()
    {
        // Viewファイル呼出し
        $this->render('index', 'FrmUxJokenToroku_layout');
    }

    //'***********************************************************************
    //'処 理 名：メッセージのオートコンプリート
    //'関 数 名：FncAutoComplete
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：メッセージのオートコンプリート
    //'***********************************************************************
    public function fncAutoComplete()
    {
        try {
            $postData = $_POST["data"]["request"];
            $FrmUxJokenToroku = new FrmUxJokenToroku();
            $result = $FrmUxJokenToroku->FncAutoComplete("", $postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //'***********************************************************************
    //'処 理 名：入力欄取得
    //'関 数 名：fncGetTCodeData
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：入力欄取得
    //'***********************************************************************
    public function fncGetTCodeData()
    {
        $arr = array();

        try {
            $postData = $_POST["data"]["request"];
            $FrmUxJokenToroku = new FrmUxJokenToroku();
            //・性別
            $arr['gender'] = $FrmUxJokenToroku->fncGetTCodeData("1", $postData);
            if (!$arr['gender']['result']) {
                throw new \Exception($arr['gender']['data']);
            }
            //・カテゴリ
            $arr['category'] = $FrmUxJokenToroku->fncGetTCodeData("2", $postData);
            if (!$arr['category']['result']) {
                throw new \Exception($arr['category']['data']);
            }
            //・年代
            $arr['year'] = $FrmUxJokenToroku->fncGetTCodeData("3", $postData);
            if (!$arr['year']['result']) {
                throw new \Exception($arr['year']['data']);
            }
            //・メーカー名
            $arr['manufacture'] = $FrmUxJokenToroku->fncGetTCodeData("4", $postData);
            if (!$arr['manufacture']['result']) {
                throw new \Exception($arr['manufacture']['data']);
            }
            //・固定化区分
            $arr['classification'] = $FrmUxJokenToroku->fncGetTCodeData("5", $postData);
            if (!$arr['classification']['result']) {
                throw new \Exception($arr['classification']['data']);
            }
            //・パックdeメンテ現在加入 ・（DZM）延長保証現在加入 ・ボディーコーティング現在加入
            $arr['maintenance'] = $FrmUxJokenToroku->fncGetTCodeData("6", $postData);
            if (!$arr['maintenance']['result']) {
                throw new \Exception($arr['maintenance']['data']);
            }
            //・点検
            $arr['tenken'] = $FrmUxJokenToroku->fncGetTCodeData("9", $postData);
            if (!$arr['tenken']['result']) {
                throw new \Exception($arr['tenken']['data']);
            }
            //・車検
            $arr['cartenken'] = $FrmUxJokenToroku->fncGetTCodeData("10", $postData);
            if (!$arr['cartenken']['result']) {
                throw new \Exception($arr['cartenken']['data']);
            }
            //・点検ステータス ・車検ステータス
            $arr['inspection'] = $FrmUxJokenToroku->fncGetTCodeData("7", $postData);
            if (!$arr['inspection']['result']) {
                throw new \Exception($arr['inspection']['data']);
            }
            //・車点検ＤＭ発信結果タイプ名称
            $arr['vehicleInspection'] = $FrmUxJokenToroku->fncGetTCodeData("8", $postData);
            if (!$arr['vehicleInspection']['result']) {
                throw new \Exception($arr['vehicleInspection']['data']);
            }
            //・管理拠点 ・サービス拠点
            $arr['place'] = $FrmUxJokenToroku->fncGetTHBUSYOData();
            if (!$arr['place']['result']) {
                throw new \Exception($arr['place']['data']);
            }

            $result = $arr;
            $result["result"] = TRUE;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //'***********************************************************************
    //'処 理 名：DB検索処理を実行する(画面初期化)
    //'関 数 名：fncGetInformation
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：DB検索処理を実行する
    //'***********************************************************************
    public function fncGetInformation()
    {
        $postData = $_POST['data']['request'];
        try {
            $FrmUxJokenToroku = new FrmUxJokenToroku();
            $result = $FrmUxJokenToroku->fncGetInformation($postData);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if ($result['row'] > 0) {
                $msgId = $result['data'][0]['MESSEJI_ID'];
                $result['message'] = $FrmUxJokenToroku->FncAutoComplete($msgId, "");
                if (!$result['message']['result']) {
                    throw new \Exception($result['message']['result']);
                }
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }


    //'***********************************************************************
    //'処 理 名：登録ボタンクリック
    //'関 数 名：FncToroku
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：登録ボタンクリック
    //'***********************************************************************
    public function fncToroku()
    {
        $postData = $_POST['data']['request'];
        try {
            $FrmUxJokenToroku = new FrmUxJokenToroku();
            //メッセージ・コード存在チェック
            $result['result'] = TRUE;
            $result['checkId'] = $FrmUxJokenToroku->fncCheckId($postData);
            if (!$result['checkId']['result']) {
                throw new \Exception($result['checkId']['data']);
            } else {
                if ($result['checkId']['row'] <> 0) {
                    //対象件数取得
                    $result['objectNm'] = $FrmUxJokenToroku->fncGetObjectNumber($postData);
                    if (!$result['objectNm']['result']) {
                        throw new \Exception($result['objectNm']['data']);
                    }
                }

            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //'***********************************************************************
    //'処 理 名：採番検索
    //'関 数 名：fncSaiban
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：採番検索
    //'***********************************************************************
    public function fncSaiban()
    {
        try {
            $FrmUxJokenToroku = new FrmUxJokenToroku();
            $result = $FrmUxJokenToroku->fncGetSaiBan();
            if (!$result['result']) {
                $result['data'] = "採番処理でエラーが発生しました。";
                throw new \Exception($result['data']);
            }
            if ($result['row'] <= 0) {
                $result = $FrmUxJokenToroku->fncGetSaiBan1();
                $result['data'][0]['REMBAN'] = $result['data'][0]['REMBAN'] + 1;
                if (!$result['result']) {
                    $result['data'] = "採番処理でエラーが発生しました。";
                    throw new \Exception($result['data']);
                }

                $result1 = $FrmUxJokenToroku->fncInsSaiban($result['data'][0]['REMBAN']);
                if (!$result1['result']) {
                    throw new \Exception($result1['data']);
                }
            }


        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //'***********************************************************************
    //'処 理 名：データ新規登録
    //'関 数 名：insData
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：データ新規登録
    //'***********************************************************************
    public function insData()
    {
        $postData = $_POST['data']['request'];
        try {
            $FrmUxJokenToroku = new FrmUxJokenToroku();
            $DB_Conn = $FrmUxJokenToroku->Do_conn();
            if (!$DB_Conn['result']) {
                throw new \Exception($DB_Conn['data']);
            }
            //トランザクション開始
            $FrmUxJokenToroku->Do_transaction();

            $result = $FrmUxJokenToroku->fncInsData($postData);
            if (!$result['result']) {
                $result['data'] = "新規登録時にエラーが発生しました。";
                throw new \Exception($result['data']);
            }

            $result = $FrmUxJokenToroku->fncUpdSaiban();
            if (!$result['result']) {
                $result['data'] = "採番テーブルの更新に失敗しました。";
                throw new \Exception($result['data']);
            }

            for ($i = 0; $i < count($postData['objData']); $i++) {
                $result = $FrmUxJokenToroku->fncInsUXData($postData, $postData['objData'][$i]);
                if (!$result['result']) {
                    $result['data'] = "UX条件ワーク新規登録時にエラーが発生しました。";
                    throw new \Exception($result['data']);
                }
            }
            $result['data'] = '';
            $FrmUxJokenToroku->Do_commit();
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $FrmUxJokenToroku->Do_rollback();
        }
        if (isset($FrmUxJokenToroku->conn_orl)) {
            $FrmUxJokenToroku->Do_close();
            unset($FrmUxJokenToroku->conn_orl);
        }
        $this->fncReturn($result);
    }

    //'***********************************************************************
    //'処 理 名：排他ロック
    //'関 数 名：fncHaita
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：排他ロック
    //'***********************************************************************
    public function fncHaita()
    {
        $postData = $_POST['data']['request'];
        try {
            $FrmUxJokenToroku = new FrmUxJokenToroku();
            $result = $FrmUxJokenToroku->fncHaitaLogin($postData);

            if (!$result['result']) {
                if (!stristr($result['data'], 'ORA-00054')) {
                    throw new \Exception($result['data']);
                } else {
                    $result['data'] = "他のユーザー編集中です。";
                    throw new \Exception($result['data']);
                }

            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //'***********************************************************************
    //'処 理 名：データ更新
    //'関 数 名：updData
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：データ更新
    //'***********************************************************************
    public function updData()
    {
        $postData = $_POST['data']['request'];
        try {
            $FrmUxJokenToroku = new FrmUxJokenToroku();
            $DB_Conn = $FrmUxJokenToroku->Do_conn();
            if (!$DB_Conn['result']) {
                throw new \Exception($DB_Conn['data']);
            }
            //トランザクション開始
            $FrmUxJokenToroku->Do_transaction();

            $result = $FrmUxJokenToroku->fncUpdData($postData);
            if (!$result['result']) {
                $result['data'] = "UX条件データの更新に失敗しました。";
                throw new \Exception($result['data']);
            }

            //UX条件ワークの削除
            $result = $FrmUxJokenToroku->fncWkUXDelet($postData['uxId']);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //UX条件ワークの新规
            for ($i = 0; $i < count($postData['objData']); $i++) {
                $result = $FrmUxJokenToroku->fncInsUXData($postData, $postData['objData'][$i]);
                if (!$result['result']) {
                    $result['data'] = "UX条件ワーク新規登録時にエラーが発生しました。";
                    throw new \Exception($result['data']);
                }
            }

            $result['data'] = '';
            $FrmUxJokenToroku->Do_commit();
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $FrmUxJokenToroku->Do_rollback();
        }
        if (isset($FrmUxJokenToroku->conn_orl)) {
            $FrmUxJokenToroku->Do_close();
            unset($FrmUxJokenToroku->conn_orl);
        }
        $this->fncReturn($result);
    }

    //'***********************************************************************
    //'処 理 名：データ削除
    //'関 数 名：fncDelData
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：データ削除
    //'***********************************************************************
    public function fncDelData()
    {
        $postData = $_POST['data']['request'];
        try {
            $FrmUxJokenToroku = new FrmUxJokenToroku();
            $DB_Conn = $FrmUxJokenToroku->Do_conn();
            if (!$DB_Conn['result']) {
                throw new \Exception($DB_Conn['data']);
            }
            //トランザクション開始
            $FrmUxJokenToroku->Do_transaction();

            $result = $FrmUxJokenToroku->fncDelData($postData);
            if (!$result['result']) {
                $result['data'] = "UX条件データの更新に失敗しました。";
                throw new \Exception($result['data']);
            }
            //UX条件ワークの削除
            $result = $FrmUxJokenToroku->fncWkUXDelet($postData['uxId']);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $result['data'] = '';
            $FrmUxJokenToroku->Do_commit();
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $FrmUxJokenToroku->Do_rollback();
        }
        if (isset($FrmUxJokenToroku->conn_orl)) {
            $FrmUxJokenToroku->Do_close();
            unset($FrmUxJokenToroku->conn_orl);
        }
        $this->fncReturn($result);
    }
}
