<?php
/**
 * 説明：
 *
 *
 * @author YINHUAIYU
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
use App\Model\APPM\FrmOshiraseJokenToroku;
class FrmOshiraseJokenTorokuController extends AppController
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
        $this->render('index', 'FrmOshiraseJokenToroku_layout');
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
        $hyojiymd = $_POST['data']['hyojiymd'];
        try {
            $FrmOshiraseJokenToroku = new FrmOshiraseJokenToroku();
            $result = $FrmOshiraseJokenToroku->FncAutoComplete($hyojiymd);
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
        $hyojiymd = $_POST['data']['hyojiymd'];

        try {
            $FrmOshiraseJokenToroku = new FrmOshiraseJokenToroku();
            //・性別
            $arr['seibetsu'] = $FrmOshiraseJokenToroku->fncGetTCodeData("1", $hyojiymd);
            if (!$arr['seibetsu']['result']) {
                throw new \Exception($arr['seibetsu']['data']);
            }
            //・カテゴリ
            $arr['kategori'] = $FrmOshiraseJokenToroku->fncGetTCodeData("2", $hyojiymd);
            if (!$arr['kategori']['result']) {
                throw new \Exception($arr['kategori']['data']);
            }
            //・年代
            $arr['nendai'] = $FrmOshiraseJokenToroku->fncGetTCodeData("3", $hyojiymd);
            if (!$arr['nendai']['result']) {
                throw new \Exception($arr['nendai']['data']);
            }
            //・メーカー名
            $arr['makerNm'] = $FrmOshiraseJokenToroku->fncGetTCodeData("4", $hyojiymd);
            if (!$arr['makerNm']['result']) {
                throw new \Exception($arr['makerNm']['data']);
            }
            //・固定化区分
            $arr['koteikakbn'] = $FrmOshiraseJokenToroku->fncGetTCodeData("5", $hyojiymd);
            if (!$arr['koteikakbn']['result']) {
                throw new \Exception($arr['koteikakbn']['data']);
            }
            //・パックdeメンテ現在加入 ・（DZM）延長保証現在加入 ・ボディーコーティング現在加入
            $arr['maintenance'] = $FrmOshiraseJokenToroku->fncGetTCodeData("6", $hyojiymd);
            if (!$arr['maintenance']['result']) {
                throw new \Exception($arr['maintenance']['data']);
            }
            //・点検ステータス ・車検ステータス
            $arr['inspection'] = $FrmOshiraseJokenToroku->fncGetTCodeData("7", $hyojiymd);
            if (!$arr['inspection']['result']) {
                throw new \Exception($arr['inspection']['data']);
            }
            //・車点検ＤＭ発信結果タイプ名称
            $arr['dmhasshinkekkameisho'] = $FrmOshiraseJokenToroku->fncGetTCodeData("8", $hyojiymd);
            if (!$arr['dmhasshinkekkameisho']['result']) {
                throw new \Exception($arr['dmhasshinkekkameisho']['data']);
            }
            //・点検
            $arr['tenken'] = $FrmOshiraseJokenToroku->fncGetTCodeData("9", $hyojiymd);
            if (!$arr['tenken']['result']) {
                throw new \Exception($arr['tenken']['data']);
            }
            //・車検
            $arr['shaken'] = $FrmOshiraseJokenToroku->fncGetTCodeData("10", $hyojiymd);
            if (!$arr['shaken']['result']) {
                throw new \Exception($arr['shaken']['data']);
            }
            //・管理拠点 ・サービス拠点
            $arr['place'] = $FrmOshiraseJokenToroku->fncGetTHBUSYOData();
            if (!$arr['place']['result']) {
                throw new \Exception($arr['place']['data']);
            }

            $result = $arr;
            $result['result'] = true;
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
        $oshiraseId = $_POST['data']['OSHIRASEJOKEN_ID'];
        try {
            $FrmOshiraseJokenToroku = new FrmOshiraseJokenToroku();
            $result = $FrmOshiraseJokenToroku->fncGetInformation($oshiraseId);
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
    //'処 理 名：登録
    //'関 数 名：fncToroku
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：お知らせ条件登録
    //'***********************************************************************
    public function fncToroku()
    {
        $postData = $_POST['data']['data'];
        $oshiraseId = $_POST['data']['oshiraseId'];
        $messid = $_POST['data']['messid'];
        $zenkensofu = $_POST['data']['zenkensofu'];
        $mode = $_POST['data']['mode'];
        $upddt = $_POST['data']['upddt'];
        try {
            $FrmOshiraseJokenToroku = new FrmOshiraseJokenToroku();
            $DB_Conn = $FrmOshiraseJokenToroku->Do_conn();
            if (!$DB_Conn['result']) {
                throw new \Exception($DB_Conn['data']);
            }

            //トランザクション開始
            $FrmOshiraseJokenToroku->Do_transaction();

            if ($mode == 1 || $mode == 2) {
                //メッセージ・コード存在チェック
                $result = $FrmOshiraseJokenToroku->fncCheckId($messid, $postData['hyojiymd']);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                if ($result['row'] <= 0) {
                    throw new \Exception('入力されたメッセージIDは登録されていません。');
                }
                //対象件数取得
                $result = $FrmOshiraseJokenToroku->fncGetObjectNumber($postData);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                if ($result['row'] <= 0) {
                    throw new \Exception('対象件数が０件です');
                } else {
                    $ObjectNumber = $result['row'];
                    $ObjectData = $result['data'];
                }
            }
            if ($mode == 1) {
                //年月基準採番
                $result = $FrmOshiraseJokenToroku->fncRembanSelect();
                if (!$result['result']) {
                    if (!stristr($result['data'], 'ORA-00054')) {
                        throw new \Exception($result['data']);
                    } else {
                        $result['data'] = "他のユーザが登録中です";
                        throw new \Exception($result['data']);
                    }
                }

                if ($result['row'] == 0) {
                    $result = $FrmOshiraseJokenToroku->fncRembanSelect2();
                    if (!$result['result']) {
                        if (!stristr($result['data'], 'ORA-00054')) {
                            throw new \Exception($result['data']);
                        } else {
                            $result['data'] = "他のユーザが登録中です";
                            throw new \Exception($result['data']);
                        }
                    }
                    $result['data'][0]['REMBAN'] = $result['data'][0]['REMBAN'] + 1;
                    //採番結果を新規登録
                    $result2 = $FrmOshiraseJokenToroku->fncRembanInsert($result['data'][0]['REMBAN']);
                    if (!$result2['result']) {
                        throw new \Exception($result2['data']);
                    }

                }

                $saibanym = $result['data'][0]['SAIBAN_YM'];
                $remban = $result['data'][0]['REMBAN'];

                $OsId = $saibanym . sprintf("%04s", $remban);
                //お知らせ条件データ新規登録
                $result = $FrmOshiraseJokenToroku->fncOshiraseJokenInsert($OsId, $messid, $zenkensofu, $ObjectNumber, $postData);
                if (!$result['result']) {
                    throw new \Exception('新規登録時にエラーが発生しました');
                }
                //年月基準採番テーブル．連番を1インクリメント更新する
                $result = $FrmOshiraseJokenToroku->fncRembanUpdata();
                if (!$result['result']) {
                    throw new \Exception('採番テーブルの更新に失敗しました');
                }

                //お知らせ条件ワーク新規登録
                foreach ((array) $ObjectData as $value) {
                    $result = $FrmOshiraseJokenToroku->fncWkOshiraseInsert($OsId, $messid, $value, $postData);
                    if (!$result['result']) {
                        throw new \Exception('お知らせ条件ワーク新規登録時にエラーが発生しました');
                    }
                }

                $result['data'] = '';
                $FrmOshiraseJokenToroku->Do_commit();

            }
            if ($mode == 2) {

                //更新対象データを検索
                $result = $FrmOshiraseJokenToroku->fncOshirasejokenIdSelect($oshiraseId);
                if (!$result['result']) {
                    if (!stristr($result['data'], 'ORA-00054')) {
                        throw new \Exception($result['data']);
                    } else {
                        $result['data'] = "他のユーザが編集中です";
                        throw new \Exception($result['data']);
                    }
                }
                if ($result['data'][0]['RENKEI_KBN'] == '01') {
                    throw new \Exception('このお知らせ条件は既に連携済みです');
                }
                if ($result['data'][0]['UPD_DATE'] != $upddt) {
                    throw new \Exception('他のユーザによって変更されています');
                }

                //お知らせ条件データ更新
                $result = $FrmOshiraseJokenToroku->fncOshiraseJokenUpdata($oshiraseId, $messid, $zenkensofu, $ObjectNumber, $postData);
                if (!$result['result']) {
                    throw new \Exception('お知らせ条件の更新に失敗しました');
                }

                //お知らせ条件ワークの削除
                $result = $FrmOshiraseJokenToroku->fncWkOshiraseDelet($oshiraseId);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                //お知らせ条件ワーク新規登録
                foreach ((array) $ObjectData as $value) {
                    $result = $FrmOshiraseJokenToroku->fncWkOshiraseInsert($oshiraseId, $messid, $value, $postData);
                    if (!$result['result']) {
                        throw new \Exception('お知らせ条件ワーク変更登録時にエラーが発生しました');
                    }
                }

                $result['data'] = '';
                $FrmOshiraseJokenToroku->Do_commit();
            }
            if ($mode == 3) {
                //削除対象データを検索
                $result = $FrmOshiraseJokenToroku->fncOshirasejokenIdSelect($oshiraseId);
                if (!$result['result']) {
                    if (!stristr($result['data'], 'ORA-00054')) {
                        throw new \Exception($result['data']);
                    } else {
                        $result['data'] = "他のユーザが編集中です";
                        throw new \Exception($result['data']);
                    }
                }
                if ($result['data'][0]['UPD_DATE'] != $upddt) {
                    throw new \Exception('他のユーザによって変更されています');
                }

                //お知らせ条件データを更新する
                $result = $FrmOshiraseJokenToroku->fncOshiraseJokenDelet($oshiraseId);
                if (!$result['result']) {
                    throw new \Exception('お知らせ条件の更新に失敗しました');
                }
                //お知らせ条件ワークの削除
                $result = $FrmOshiraseJokenToroku->fncWkOshiraseDelet($oshiraseId);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                $result['data'] = '';
                $FrmOshiraseJokenToroku->Do_commit();
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $FrmOshiraseJokenToroku->Do_rollback();
        }
        if (isset($FrmOshiraseJokenToroku->conn_orl)) {
            $FrmOshiraseJokenToroku->Do_close();
            unset($FrmOshiraseJokenToroku->conn_orl);
        }
        $this->fncReturn($result);
    }

}
