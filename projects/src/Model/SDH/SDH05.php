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

// 共通クラスの読込み
namespace App\Model\SDH;

use App\Model\Component\ClsComDb;

class SDH05 extends ClsComDb
{
    public function m_select_HANTELIST_sql($id)
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= '  UPDYMDHM, ';
        $str_sql .= '  HANTEI' . $id . ' AS HANTEI_X, ';
        $str_sql .= '  UPDSYACD ';
        $str_sql .= 'FROM ';
        $str_sql .= '  hanteilst ';

        return $str_sql;
    }

    public function m_select_HANTELIST($id)
    {
        $str_sql = $this->m_select_HANTELIST_sql($id);

        return parent::select($str_sql);
    }
}