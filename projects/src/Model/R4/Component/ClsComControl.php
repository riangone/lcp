<?php
// 共通クラスの読込み

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：ClsComControl
// * 処理説明：共通関数
//*************************************
namespace App\Model\R4\Component;

// 共通クラスの読込み
// App::uses('ClsComDb', 'Model/R4');
// App::uses('ClsComFnc', 'Model/R4/Component');
use App\Model\Component\ClsComDb;

class ClsComControl extends ClsComDb
{
    // 20131004 kamei add Start
    // 解放が必要な変数をメンバーに設定
    protected $conn_orl = "";
    protected $Sel_Array = "";
    public $number_of_rows = "";
    // 20131004 kamei add end

    //*************************************
    // * 公開メソッド
    //*************************************

    public function select($strsql = NULL)
    {

        return parent::select($strsql);

    }

}