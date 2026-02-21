<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\KoukaType;
//*******************************************
// * sample controller
//*******************************************
class KoukaTypeController extends AppController
{
    public $KoukaType;
    public function __construct()
    {
    }
    /*考課表タイプコンボボックスの一覧を格納して返す。
     *すでにITEMがある場合は　DbReload=False(既定)でDBから再度の読み込みはしない
     *DbReload=Trueにすると、その都度DBからリストを再生成する
     *ComboBox:対象コンボボックス
     *SelectedCode:選択アイテムコード
     *BlankItem:(既定) False: 先頭にブランクを追加しない True:先頭にブランクを追加する
     *DbReload:（既定）False：アイテムがすでにコンボボックスにある場合はDB読み込みしない。 True：常にDBからリストの更新を行う
     */
    public function SetComboBox()
    {
        $result = array(
            'result' => false,
            'data' => array(),
            'error' => ''
        );
        try {
            /* ---------------------------------------
             *   コンボボックスのアイテムをDBから取得する
             * ---------------------------------------*/
            $this->KoukaType = new KoukaType();
            $dt = $this->KoukaType->SetComboBoxSql();
            if (!$dt['result']) {
                throw new \Exception($dt['data']);
            }
            if ($dt['row'] == 0) {
                throw new \Exception("考課表タイプマスタにデータがありません。");
            }
            //--- 取得値をプロパティ変数に設定 ---
            //--- コンボ用 -----------------------
            $result['data'] = $dt['data'];

            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }

        return $result;
    }

}
