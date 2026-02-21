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
 * 管理担当履歴ダイアログ
 * SDHController
 */
class SDH06Controller extends AppController
{
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $layout;

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
        $this->layout = 'SDH06_layout';

        $this->render('/SDH/SDH06/index', $this->layout);
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