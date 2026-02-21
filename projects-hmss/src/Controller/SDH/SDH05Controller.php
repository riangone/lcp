<?php
/**
 * 説明：
 *
 *
 * @author jinmingai
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20150526           ---                       新規                           FCSDL
 * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\SDH;

use App\Controller\AppController;

/**
 * 車検代替判定画面
 * SDHController
 */
class SDH05Controller extends AppController
{
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $layout;

    private $tenpo_cd = "";
    private $tenpo_nm = "";
    private $hantei_x = "";
    private $id = "";

    // ========== 変数 end ==========

    // ========== コントロール start ==========
    // ========== コントロール end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    /**
     * 注文書ダイアログ
     */
    public function index()
    {
        $this->layout = 'SDH05_layout';

        $this->render('/SDH/SDH05/index', $this->layout);
    }

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    // ==========
    // = メソッド end =
    // ==========

}