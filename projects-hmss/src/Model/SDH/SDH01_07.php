<?php
/**
 * 説明：
 *
 *
 * @author zhenghuiyun
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

// 共通クラスの読込み
namespace App\Model\SDH;

use App\Model\Component\ClsComDb;

class SDH01_07 extends ClsComDb
{
    /**
     * メモを取得.
     *
     * @param {String} 注文書NO:$orderno
     *
     * @return {parent} result
     */
    public function m_select_sdh01_07($VINWMIVDS, $VINVIS)
    {
        $str_sql = $this->m_select_sdh01_07_sql($VINWMIVDS, $VINVIS);

        return parent::select($str_sql);
    }

    /**
     * メモを取得.
     *
     * @param {String} 注文書NO:$orderno
     *
     * @return {String} select文
     */
    public function m_select_sdh01_07_sql($VINWMIVDS, $VINVIS)
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= '  HANTEIMEMO.MEMO ';
        $str_sql .= 'FROM ';
        $str_sql .= '  HANTEIMEMO ';
        $str_sql .= 'WHERE ';
        $str_sql .= "  HANTEIMEMO.SYADAI = '" . $VINWMIVDS . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  HANTEIMEMO.CARNO = '" . $VINVIS . "' ";

        return $str_sql;
    }
}