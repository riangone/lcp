<?php
// App::uses('ClsComFnc', 'Model/R4/Component');
// App::uses('ClsComFncPprm', 'Model/PPRM/Component');

namespace App\Controller\Component;

use App\Model\R4\Component\ClsComFnc;
use Cake\Controller\Component;
use stdClass;
use Cake\Core\Exception\Exception;

class ClsComFncComponent extends Component
{
    //メンバー変数宣言
    public $xml = '';
    public $xml_path = '';
    public $ClsComFnc;

    // 処理名	:メッセージ出力
    // 関数名	:FncMsgBox
    // 引数		:strMsgNo
    // 			:strRepText1
    // 			:strRepText2
    // 戻り値		:配列["result"]			正常終了:0,異常終了:-1
    // 			:配列["Msg"]				画面表示メッセージ
    // 			:配列["Title"]			ダイアログタイトル
    // 			:配列["Buttons"]			表示ボタン
    // 			:配列["Icon"]			表示アイコン
    // 			:配列["deaultButton"]	初期フォーカスボタン
    // 処理説明	:メッセージ表示用の情報を返却する
    public function FncMsgBox($strMsgNo, $strRepText1 = '', $strRepText2 = '')
    {
        $GSYSTEM_NAME = 'R4→（GD）（DZM）データ連携サブシステム';
        // 変数宣言
        register_shutdown_function(array($this, 'finally'));
        $rtn = array('result' => -1, 'Msg' => '', 'Title' => '', 'Buttons' => '', 'Icon' => '', 'defaultButton' => '');
        $strTitle = '';
        $strDbutton = '';
        $strMsg = '';

        try {
            // パス取得
            $strPath = dirname(__FILE__);
            $filename = $strPath . '/' . 'HMMsg.xml';
            // メッセージ番号の置換え
            $strMsgNo = strtoupper($strMsgNo);

            // 値取得
            $this->xml = @simplexml_load_file($filename); // XMLの取得

            if ($this->xml) {
                // 階層が不足する場合は、直接名称を指定してデータを取得する
                if (isset($this->xml->{$strMsgNo . '_TITLE'})) { // タイトル
                    $strTitle = $this->xml->{$strMsgNo . '_TITLE'};
                }
                if (isset($this->xml->{$strMsgNo . '_MESSAGE'})) { // メッセージ
                    $strMsg = $this->xml->{$strMsgNo . '_MESSAGE'};
                }
                if (isset($this->xml->{$strMsgNo . '_DBUTTON'})) { // デフォルトボタン
                    $strDbutton = $this->xml->{$strMsgNo . '_DBUTTON'};
                }
            } else {
                // 読み込めなかった場合のエラー処理
            }

            // タイトル
            if ('' == trim($strTitle)) {
                $strTitle = $GSYSTEM_NAME;
            }

            // メッセージ
            if ('' == trim($strMsg)) {
                $strTitle = $GSYSTEM_NAME;
                $strMsg = '【' . $strMsgNo . '】' . "\n" . 'メッセージが登録されていません';

                $rtn['result'] = '-1';
                $rtn['Msg'] = $strMsg;
                $rtn['Title'] = $strTitle;
                $rtn['Buttons'] = '';
                $rtn['Icon'] = '';
                $rtn['defaultButton'] = '';

                return $rtn;
            } else {
                // 置換え
                $strMsg = str_replace('%1', $strRepText1, $strMsg);
                if ('E9997' != $strMsgNo) {
                    $strMsg = str_replace('%2', $strRepText2, $strMsg);
                }
                $strMsg = '【' . $strMsgNo . '】' . "\n" . $strMsg;
            }

            // デフォルトボタン
            if ('1' == $strDbutton or '256' == $strDbutton) {
                $strDbutton = '256';
            } else {
                $strDbutton = '0';
            }
            // メッセージ種類
            switch (substr($strMsgNo, 0, 1)) {
                case 'E': // エラー
                    if ('E9997' == $strMsgNo) {
                        // フォームロード時エラー専用
                        $rtn['result'] = '0';
                        $rtn['Msg'] = $strMsg;
                        $rtn['Title'] = $strRepText2;
                        $rtn['Buttons'] = '0';
                        $rtn['Icon'] = '16';
                        $rtn['defaultButton'] = '0';
                    } else {
                        $rtn['result'] = '0';
                        $rtn['Msg'] = $strMsg;
                        $rtn['Title'] = $strTitle;
                        $rtn['Buttons'] = '0';
                        $rtn['Icon'] = '16';
                        $rtn['defaultButton'] = '0';
                    }
                    break;
                case 'I': // インフォ
                    $rtn['result'] = '0';
                    $rtn['Msg'] = $strMsg;
                    $rtn['Title'] = $strTitle;
                    $rtn['Buttons'] = '0';
                    $rtn['Icon'] = '64';
                    $rtn['defaultButton'] = '0';

                    break;
                case 'W': // 警告
                    $rtn['result'] = '0';
                    $rtn['Msg'] = $strMsg;
                    $rtn['Title'] = $strTitle;
                    $rtn['Buttons'] = '0';
                    $rtn['Icon'] = '48';
                    $rtn['defaultButton'] = '0';

                    break;
                case 'Q': // 問合せ
                    if ('O' == substr($strMsgNo, 1, 1)) {
                        $rtn['result'] = '0';
                        $rtn['Msg'] = $strMsg;
                        $rtn['Title'] = $strTitle;
                        $rtn['Buttons'] = '1';
                        $rtn['Icon'] = '32';
                        $rtn['defaultButton'] = $strDbutton;
                    } elseif ('Y' == substr($strMsgNo, 1, 1)) {
                        $rtn['result'] = '0';
                        $rtn['Msg'] = $strMsg;
                        $rtn['Title'] = $strTitle;
                        $rtn['Buttons'] = '4';
                        $rtn['Icon'] = '32';
                        $rtn['defaultButton'] = $strDbutton;
                    } else {
                        $rtn['result'] = '-1';
                    }

                    break;
                default: // 例外
                    $rtn['result'] = '-1';
                    break;
            }

            return $rtn;
        } catch (\Exception $e) {
            $rtn['result'] = '-1';

            return $rtn;
        }
    }

    public function FncCreateJqGridData($data, $totalPage, $page, $count, $start = 0)
    {
        /*
         * 生成显示jqGrid数据数组
         */
        register_shutdown_function(
            array(
                $this,
                'finally',
            )
        );

        $responce = new stdClass();
        $responce->total = $totalPage;
        $responce->page = $page;
        $responce->records = $count;
        $i = 0;

        foreach ($data as $value) {
            $responce->rows[$i]['id'] = $start;
            $tmpArr = array();
            foreach ($value as $value1) {
                array_push($tmpArr, $value1);
            }

            $responce->rows[$i]['cell'] = $tmpArr;

            ++$i;
            ++$start;
        }

        return $responce;
    }

    //20170223 YIN INS S
    public function FncCreateJqGridDataIndex($data, $totalPage, $page, $count, $start = 0)
    {
        /*
         * 生成显示jqGrid数据数组
         */
        register_shutdown_function(
            array(
                $this,
                'finally',
            )
        );

        $responce = new stdClass();
        $responce->total = $totalPage;
        $responce->page = $page;
        $responce->records = $count;
        $i = 0;

        foreach ($data as $value) {
            $responce->rows[$i]['id'] = $start;
            $tmpArr = array();
            foreach ($value as $key1 => $value1) {
                $tmpArr[$key1] = $value1;
            }

            $responce->rows[$i]['cell'] = $tmpArr;

            ++$i;
            ++$start;
        }

        return $responce;
    }

    //20170223 YIN INS E

    //20170614 YIN INS S
    public function FncCreateJqGridDataReload($data, $totalPage, $page, $count, $start = 0)
    {
        /*
         * 生成显示jqGrid数据数组
         */
        register_shutdown_function(
            array(
                $this,
                'finally',
            )
        );

        $responce = new stdClass();
        $responce->total = $totalPage;
        $responce->page = $page;
        $responce->records = $count;
        $i = 0;
        $start1 = $start;

        foreach ($data as $key => $value) {
            $responce->rows[$i]['id'] = $start;
            $tmpArr = array();
            if ($key >= $start1) {
                foreach ($value as $key1 => $value1) {
                    $tmpArr[$key1] = $value1;
                }

                $responce->rows[$i]['cell'] = $tmpArr;

                ++$i;
                ++$start;
            }
        }

        return $responce;
    }

    //20170614 YIN INS E

    public function FncCreateJqGridShow($tmpResult, $getCntTf = false)
    {
        /*
         * 生成jqGrid专有参数
         */
        if ($getCntTf) {
            //$count = $tmpResult[0]['cnt'];
            $count = $tmpResult[0]['CNT'];
        } else {
            $count = count($tmpResult);
        }
        //$count = count($tmpResult);
        $page = $_POST['page'];
        // get the requested page
        $limit = $_POST['rows'];
        // get how many rows we want to have into the grid
        $sidx = $_POST['sidx'];
        // get index row - i.e. user click to sort
        $sord = $_POST['sord'];
        // echo "<br/>page=".$page."----limit=".$limit."-----sidx=".$sidx."-----sord=".$sord."<br/>";
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }
        //要求ページ数が総ページするより大きい場合
        if ($page > $total_pages) {
            $page = $total_pages;
        }
        // ========== ページ数計算 E ==========

        // ========== 問い合せ結果の行数を制限する S ==========

        //開始行番取得
        $start = $limit * $page - $limit;
        if ($start < 0) {
            $start = 0;
        }
        // ========== 問い合せ結果の行数を制限する E ==========

        // ========== レコードの並び替え S ==========

        if (!$sidx) {
            $sidx = 1;
        }

        $sortStr = ' ';
        //20140219 yushuangji edit start
        if ('1' == $sidx && 'asc' == $sord) {
            $sortStr = ' ';
        } else {
            $sortStr .= ' ' . $sidx . ' ' . $sord . ' ';
        }
        //20140219 yushuangji edit start
        $tmpArr = array(
            'sortStr' => $sortStr,
            'start' => $start,
            'limit' => $limit + $start,
            'page' => $page,
            'totalPage' => $total_pages,
            'count' => $count,
        );

        return $tmpArr;
    }

    //20131021 luchao add start

    //**********************************************************************
    //処 理 名：Null変換関数(文字)
    //関 数 名：FncNv
    //引     数：$objValue     (I)文字列
    //戻 り 値：変換後の値
    //処理説明：Null変換(文字)を行う。
    //**********************************************************************
    public function FncNv($objValue, $objReturn = '')
    {
        //---NULLの場合---
        if (null === $objValue) {
            return $objReturn;
        }
        //---以外の場合---
        else {
            return $objValue;
        }
    }

    //**********************************************************************
    //処 理 名：Null変換関数(Sql文字)
    //関 数 名：FncSqlNv
    //引     数：$objValue     (I)文字列
    //戻 り 値：変換後の値
    //処理説明：Null変換(Sql文字)を行う。
    //**********************************************************************
    public function FncSqlNv($objValue, $objReturn = '')
    {
        //---NULLの場合---
        if (null === $objValue) {
            if ('' != $objReturn) {
                return $objReturn;
            } else {
                return "''";
            }
        }
        //---以外の場合---
        else {
            return "'" . str_replace("'", "''", $objValue) . "'";
        }
    }

    //**********************************************************************
    //処 理 名：Null変換関数(SqlDate文字)
    //関 数 名：FncSqlDate
    //引     数：$objValue     (I)文字列
    //戻 り 値：変換後の値
    //処理説明：Null変換(SqlDate文字)を行う。
    //**********************************************************************
    public function FncSqlDate($objValue, $objReturn = 'Null')
    {
        //---NULLの場合---
        if (null === $objValue) {
            return $objReturn;
        }
        //---以外の場合---
        else {
            return "TO_DATE('" . $objValue . "','YYYY/MM/DD HH24:MI:SS')";
        }
    }

    //**********************************************************************
    //処 理 名：Null変換関数(Sql文字)
    //関 数 名：FncSqlNz
    //引     数：$objValue     (I)文字列
    //戻 り 値：変換後の値
    //処理説明：Null変換(Sql文字)を行う。
    //**********************************************************************
    public function FncSqlNz($objValue, $objReturn = '')
    {
        //---NULLの場合---
        if (null === $objValue) {
            if ('' != $objReturn) {
                return $objReturn;
            } else {
                return 'Null';
            }
        }
        //---以外の場合---
        else {
            return "'" . str_replace("'", "''", $objValue) . "'";
            //return $objValue;
        }
    }

    //**********************************************************************
    //処 理 名：Null変換関数(数値)
    //関 数 名：FncNz
    //引     数：$objValue     (I)文字列
    //戻 り 値：変換後の値
    //処理説明：Null変換(数値)を行う。
    //**********************************************************************
    public function FncNz($objValue)
    {
        //---NULLの場合---
        if (null === $objValue) {
            return 0;
        }
        //---空白の場合---
        elseif ('' == trim($objValue)) {
            return 0;
        }
        //---その他---
        else {
            return $objValue;
        }
    }

    //**********************************************************************
    //処 理 名：ファイルパス存在チェック
    //関 数 名：FncFileExists
    //引    数：$strFile  (I)ファイルパス
    //戻 り 値：True ：正常
    //　　　　　	False：異常
    //処理説明：ファイルの存在チェックを行う。
    //**********************************************************************
    public function FncFileExists($strFile)
    {
        //return file_exists($strFile);
        if (!file_exists($strFile)) {
            return false;
        } else {
            return true;
        }
    }

    public function FncGetPath($strPathName)
    {
        register_shutdown_function(array($this, 'finally'));
        $strPath = '';
        // パス取得
        $strPath = dirname(dirname(dirname(__FILE__)));
        $filename = $strPath . '/Model/Component/' . 'HMDB.xml';

        // 値取得
        $this->xml_path = simplexml_load_file($filename);

        if (isset($this->xml_path->{$strPathName})) {
            // タイトル
            $strPath = $this->xml_path->{$strPathName};
        } else {
            $strPath = '';
        }
        $strPath = (array) $strPath;

        return $strPath[0];
    }

    public function FncGetSysDate($strFormat = 'Y-m-d')
    {
        try {
            $this->ClsComFnc = new ClsComFnc();
            $result = $this->ClsComFnc->select();
            if (false == $result['result']) {
                throw new \Exception($result['data']);
            }
            $strDate = strtotime($result['data'][0]['SYS_DATE']);
            $strDate = date($strFormat, $strDate);

            return $strDate;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    //20131021 luchao add end

    //20131024 qiuqiu add start

    public function fncTrimEnd($str, $strEnd = ' ')
    {
        if (' ' == $strEnd && ' ' == substr($str, strlen($str) - 1)) {
            return substr($str, 0, strlen($str) - 1);
        } else {
            return $str;
        }

        // return substr($str, -(strlen($strEnd))) == $strEnd ? substr($str, 0, strlen($str) - strlen($strEnd)) : $str;
    }

    public function initializeArray($arrLen)
    {
        $arr = array();
        for ($i = 0; $i < $arrLen; ++$i) {
            array_push($arr, '');
        }

        return $arr;
    }

    //20131024 qiuqiu add end

    //20140127 luchao add start
    public function IsLeapYear($year)
    {
        if ((0 == $year % 4 && 0 != $year % 100) || (0 == $year % 400)) {
            return true;
        } else {
            return false;
        }
    }

    //20140127 luchao add end

    //20140227 luchao add start

    //**********************************************************************
    //処 理 名：科目名取得
    //関 数 名：FncGetBusyoMstValue
    //引    数：strCode      (I)部署コード
    //          objHosyaMst  (O)保社マスタ構造体
    //戻 り 値：= True   ：正常
    //       　 = False  ：エラー
    //処理説明：引数の科目コードで値を取得し構造体に格納。
    //**********************************************************************
    public function FncGetKamokuMstValue($strCode, &$objKamokuMst, $strKomoku = '999999', $strMstFormat = '')
    {
        $objDR = '';
        $result = array();
        $Do_Execute = array();
        try {
            $this->ClsComFnc = new ClsComFnc();
            $Do_Execute = $this->ClsComFnc->FncGetKamokuMstValue($strCode, $strKomoku, $strMstFormat);
            if (!$Do_Execute['result']) {
                throw new \Exception($Do_Execute['data']);
            }

            //** ＳＱＬ実行＆構造体へ格納
            //fuxiaolin edit 20140806
            $objDR = $Do_Execute['data'];
            //fuxiaolin edit 20140806
            if (count((array) $objDR) > 0) {
                //--------------------
                //　該当データ有
                //--------------------
                //---リターンコード---
                $objKamokuMst['intRtnCD'] = 1;
                //---保社名---
                $objKamokuMst['strKamokuNM'] = $this->FncNv($objDR[0]['KAMOK_NM']);
            } else {
                //--------------------
                //　該当データ無
                //--------------------
                //---リターンコード---
                $objKamokuMst['intRtnCD'] = -1;
            }
            //** 後処理
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['data'] = $e->getMessage();
        }

        return $result;
    }

    //**********************************************************************
    //処 理 名：部署名取得
    //関 数 名：FncGetBusyoMstValue
    //引    数：strCode      (I)部署コード
    //          objHosyaMst  (O)保社マスタ構造体
    //戻 り 値：= True   ：正常
    //       　 = False  ：エラー
    //処理説明：引数の部署コードで値を取得し構造体に格納。
    //**********************************************************************
    public function FncGetBusyoMstValue($strCode, &$objBusyoMst, $blnSyukei = false)
    {
        $objDR = '';
        $result = array();
        $Do_Execute = array();
        try {
            //** 初期設定
            $this->ClsComFnc = new ClsComFnc();
            $Do_Execute = $this->ClsComFnc->FncGetBusyoMstValue($blnSyukei, $strCode);
            if (!$Do_Execute['result']) {
                throw new \Exception($Do_Execute['data']);
            }
            //** ＳＱＬ実行＆構造体へ格納
            $objDR = $Do_Execute['data'];

            if (count((array) $objDR) > 0) {
                //--------------------
                //　該当データ有
                //--------------------
                //---リターンコード---
                $objBusyoMst['intRtnCD'] = 1;
                //---保社名---
                $objBusyoMst['strBusyoNM'] = $this->FncNv($objDR[0]['BUSYO_NM']);
                $objBusyoMst['strKKRBusyo'] = $this->FncNv($objDR[0]['KKR_BUSYO_CD']);
            } else {
                //--------------------
                //　該当データ無
                //--------------------
                //---リターンコード---
                $objBusyoMst['intRtnCD'] = -1;
            }

            //** 後処理
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['data'] = $e->getMessage();
        }

        return $result;
    }

    //**********************************************************************
    //処 理 名：丸め処理
    //関 数 名：fncRoundDou
    //引    数：dblDou    (I)数値
    //　 　 　：intRoundKeta (I)桁数
    //　 　 　：strRoundKbn  (I)端数処理区分
    //戻 り 値：変換後の値
    //処理説明：丸め処理を行う
    //**********************************************************************
    public function fncRoundDou($dblDou, $intRoundKeta, $strRoundKbn)
    {
        $dCom1 = 0.0;
        $dCom2 = 0.0;
        switch ($strRoundKbn) {
            case '0': //切り捨て
                $fncRoundDou = floor($dblDou * pow(10, $intRoundKeta)) / pow(10, $intRoundKeta);
                break;
            case '1': //四捨五入
                $fncRoundDou = floor($dblDou * pow(10, $intRoundKeta) + (($dblDou < 0) ? 0.5 - 1 : 0.5)) / pow(10, $intRoundKeta);
                break;
            case '2': //切り上げ
                $dCom1 = $dblDou * pow(10, $intRoundKeta);
                $dCom2 = ($dblDou < 0) ? -0.999 : 0.999;
                $fncRoundDou = floor($dCom1 + $dCom2) / pow(10, $intRoundKeta);
                break;
            default:
                $fncRoundDou = $dblDou;
                break;
        }

        return sprintf('%.1f', $fncRoundDou);
    }

    public function StringToArray($str)
    {
        $start = 0;
        $len = strlen($str);
        $strlen = $start + $len;
        $tmpstr = '';
        $tmpArr = array();
        for ($i = 0; $i < $strlen; ++$i) {
            if (ord(substr($str, $i, 1)) > 0xa0) {
                $tmpstr .= substr($str, $i, 2);
                array_push($tmpArr, substr($str, $i, 3));
                ++$i;
                ++$i;
            } else {
                $tmpstr .= substr($str, $i, 1);
                array_push($tmpArr, substr($str, $i, 1));
            }
        }

        return $tmpArr;
    }

    public function StringLength($str, &$StringArray = array())
    {
        $StringArray = $this->StringToArray($str);

        return count($StringArray);
    }

    //**********************************************************************
    //処 理 名：指定バイト数文字列取得
    //関 数 名：FncGetByteString
    //引    数：strTarget   (I) 取り出す元になる文字列
    //　    　：intStart    (I) 取り出しを開始する位置
    //　    　：intByteSize (I) 取り出すバイト数
    //戻 り 値：指定されたバイト位置から指定されたバイト数分の文字列
    //処理説明：文字列の指定されたバイト位置から、指定されたバイト数分の文字列を返す。
    //**********************************************************************
    public function FncGetByteString($strTarget, $intStart, $intByteSize)
    {
        $StringArray = array();
        // $tmpStringArray = array();
        $tmpStart = 0;
        // $tmpEnd = 0;
        $tmpString = '';
        $tmpChrLength = 0;
        $tmpAllLength = 0;
        $StringCount = $this->StringLength($strTarget, $StringArray);

        for ($i = 0; $i < $StringCount; ++$i) {
            $tmpChrLength = 0;
            //20170926 YIN UPD S
            // $tmpChrLength = $this -> GetByteCount($tmpStringArray[$i]);
            $tmpChrLength = $this->GetByteCount($StringArray[$i]);
            //20170926 YIN UPD E
            $tmpAllLength += $tmpChrLength;

            if ($tmpAllLength > $intByteSize) {
                break;
            }
            if ($tmpStart >= $intStart) {
                $tmpString .= $StringArray[$i];
            } else {
                $tmpStart = $tmpStart + $tmpChrLength;
                continue;
            }
        }

        return $tmpString;
    }

    public function GetByteCount($str)
    {
        $strLength = '';
        $str = mb_convert_encoding($str, 'SJIS');
        $strLength = strlen($str);

        return $strLength;
    }

    //**********************************************************************
    //処 理 名：固定長編集（数値）
    //関 数 名：fncGetFixNum
    //引    数：strStr 入力文字列
    //　    　　intLen 長さ(バイト)
    //戻 り 値：固定長文字列
    //処理説明：入力文字列を長さでゼロ埋め編集し固定長文字列を返す
    //**********************************************************************
    public function fncGetFixNum($strNum, $intLen)
    {
        $strNum = trim($strNum);
        $StringArray = array();
        $StringCount = $this->StringLength($strNum, $StringArray);
        $tmpString = '';
        if ($intLen > $StringCount) {
            for ($i = 0; $i < $intLen - $StringCount; ++$i) {
                $tmpString .= '0';
            }
            $tmpString .= $strNum;
        } else {
            for ($i = 0; $i < $intLen; ++$i) {
                $tmpString .= $StringArray[$StringCount - $intLen + $i];
            }
        }

        return $tmpString;
    }

    //20140227 luchao add end

    //20140203 luchao add start

    //**********************************************************************
    //処 理 名：和暦⇒西暦変換（取込用）
    //関 数 名：FncDateChange2
    //引    数：strKbn　 (I)年号区分（1：明治/2：大正/3：昭和/4：平成/5:令和）
    //                              （M：明治/T：大正/S：昭和/H：平成/R:令和）
    //          strYmd   (I)年月日（YYMMDD）
    //          dtmYmd   (O)日付
    //戻 り 値：True ：正常
    //　　　　　False：異常
    //処理説明：和暦日付（X-YYMMDD）を西暦日付に変換する。
    //**********************************************************************
    public function FncDateChange2($strKbn, $strYmd, &$dtmYmd)
    {
        $strChgYmd = '';
        //変換日付
        //---数値ﾁｪｯｸ---
        if (!is_numeric($strYmd)) {
            //異常終了
            return false;
        }
        //区分変換
        switch (strtoupper($strKbn)) {
            case '1':
            case 'M':
                //---明治---
                $strChgYmd = 19670000 + $strYmd;
                break;
            case '2':
            case 'T':
                //---大正---
                $strChgYmd = 19110000 + $strYmd;
                break;
            case '3':
            case 'S':
                //---昭和---
                $strChgYmd = 19250000 + $strYmd;
                break;
            case '4':
            case 'H':
                //---平成---
                $strChgYmd = 19880000 + $strYmd;
                break;
            //20211110 WL INS S
            case '5':
            case 'R':
                //---令和---
                $strChgYmd = 20180000 + $strYmd;
                break;
            //20211110 WL INS E
            default:
                //異常終了
                return false;
        }
        // echo "========1======";
        // echo $strChgYmd;
        // echo "==============";
        $strChgYmd = substr($strChgYmd, 0, 4) . '/' . substr($strChgYmd, 4, 2) . '/' . substr($strChgYmd, 6, 2);
        // echo "=======2=======";
        // echo $strChgYmd;
        // echo "==============";
        //---日付ﾁｪｯｸ---
        if (!$this->IsDate($strChgYmd)) {
            //異常終了
            return false;
        }

        //日付の設定
        $dtmYmd = $strChgYmd;

        //正常終了
        return true;
    }

    //**********************************************************************
    //処 理 名：和暦⇒西暦変換（取込用）
    //関 数 名：FncDateChange3
    //引    数：strYmd   (I)和暦（年号YY年MM月DD日）
    //          dtmYmd   (O)西暦 (YYYYMMDD)
    //戻 り 値：変換後の日付
    //処理説明：和暦日付（年号YY年MM月DD日）を西暦日付に変換する。
    //**********************************************************************
    public function FncDateChange3($strYmd)
    {
        $strChgYmd = '';
        //変換日付
        $strSmpYmd = '';
        $strChkYmd = '';
        $strYear = '';
        $strMonth = '';
        $strDay = '';

        //---数値ﾁｪｯｸ---
        $strSmpYmd = str_replace('日', '', str_replace('月', '', str_replace('年', '', substr($strYmd, 2))));
        $strYear = rtrim(substr($strSmpYmd, 0, 2));
        $strMonth = str_pad(ltrim(substr($strSmpYmd, 2, 2)), 2, '0', STR_PAD_LEFT);
        $strDay = str_pad(ltrim(substr($strSmpYmd, 4, 2)), 2, '0', STR_PAD_LEFT);
        $strSmpYmd = $strYear . $strMonth . $strDay;

        if (!is_numeric($strSmpYmd)) {
            //異常終了
            return '';
        }

        //区分変換
        switch (substr($strYmd, 0, 2)) {
            case '明治':
                $strChgYmd = 19670000 + $strSmpYmd;
                break;
            case '大正':
                $strChgYmd = 19110000 + $strSmpYmd;
                break;
            case '昭和':
                $strChgYmd = 19250000 + $strSmpYmd;
                break;
            case '平成':
                $strChgYmd = 19880000 + $strSmpYmd;
                break;
            //20211110 WL INS S
            case '令和':
                $strChgYmd = 20180000 + $strSmpYmd;
                break;
            //20211110 WL INS E
            default:
                //異常終了
                return '';
        }

        $strChkYmd = substr($strChgYmd, 0, 4) . '/' . substr($strChgYmd, 4, 2) . '/' . substr($strChgYmd, 6, 2);

        //---日付ﾁｪｯｸ---
        if (!is_numeric($strChkYmd)) {
            //異常終了
            return '';
        }

        //正常終了
        return $strChgYmd;
    }

    //**********************************************************************
    //処 理 名：西暦⇒和暦変換（取込用）
    //関 数 名：FncDateChange3
    //引    数：strYmd   (I)西暦（YYYY年MM月DD日）
    //         strKbn   (O) 和暦 (明治 大正 昭和 平成） 和暦年月日
    //戻 り 値：和暦 + 年月日
    //処理説明：西暦日付を和暦日付（和暦年月日）に変換する。
    //**********************************************************************
    public function FncDateChange4($strYmd)
    {
        $strData = '';
        $strTrm = '';
        $strKbn = '';

        if (!is_numeric($strYmd)) {
            //異常終了
            return '';
        }

        //区分変換

        if ($strYmd >= 18670000 && $strYmd < 19120000) {
            $strTrm = '明治';
            $strData = $strYmd - 18670000;
        }

        if ($strYmd >= 19120000 && $strYmd < 19260000) {
            $strTrm = '大正';
            $strData = $strYmd - 19120000;
        }

        if ($strYmd >= 19260000 && $strYmd < 19890000) {
            $strTrm = '昭和';
            $strData = $strYmd - 19260000;
        }
        //20211110 WL UPD S
        //if ($strYmd >= 19890000){
        if ($strYmd >= 19890000 && $strYmd < 20190000) {
            //20211110 WL UPD E
            $strTrm = '平成';
            $strData = $strYmd - 19890000;
        }
        //20211110 WL INS S
        if ($strYmd >= 20190000) {
            $strTrm = '令和';
            $strData = $strYmd - 20190000;
        }
        //20211110 WL INS E
        $strKbn = $strTrm . (substr($strData, 0, 2) + 1) . '/' . substr($strData, 2, 2) . '/' . substr($strData, 4, 2);

        //正常終了
        return $strKbn;
    }

    public function IsDate($date)
    {
        //$tmp = "";

        $dateArray = '';
        $timeType = '';
        $tmpYear = '';
        $tmpMonth = '';
        $tmpDay = '';
        $tmpTime = '';

        $dateArray = mb_split('\-|,|\\\\|\.|\/|\||_|~', $date);
        $timeType = stripos($date, ':');

        if ($timeType > 0) {
            if (3 != count($dateArray)) {
                return false;
            } else {
                $tmpYear = $dateArray[0];
                $tmpMonth = $dateArray[1];
                $tmp = explode(' ', $dateArray[2]);
                $tmpDay = $tmp[0];
                $tmpTime = $tmp[1];
                if (!checkdate($tmpMonth, $tmpDay, $tmpYear)) {
                    return false;
                }
                $tmp = mb_split(':', $tmpTime);

                if ($tmp[0] > 24) {
                    return false;
                } elseif ($tmp[1] >= 60 || $tmp[2] >= 60) {
                    return false;
                }
            }
        } else {
            if (2 == count($dateArray)) {
                $thisYear = date('Y');
                $tmpMonth = $dateArray[0];
                $tmpDay = $dateArray[1];
                if (!checkdate($tmpMonth, $tmpDay, $thisYear)) {
                    return false;
                }
            } elseif (3 == count($dateArray)) {
                $thisYear = date('Y');
                $tmpYear = $dateArray[0];
                $tmpMonth = $dateArray[1];
                $tmpDay = $dateArray[2];
                switch (4 - strlen($tmpYear)) {
                    case '0':
                        // $tmpYear = $tmpYear;
                        break;
                    case '1':
                        $tmpYear = '0' . $tmpYear;
                        break;
                    case '2':
                        if ($tmpYear < 30) {
                            $tmpYear = '20' . $tmpYear;
                        }
                        break;
                    case '3':
                        $tmpYear = '200' . $tmpYear;
                        break;
                    case '4':
                        $tmpYear = $thisYear;
                        break;
                }
                if (!checkdate($tmpMonth, $tmpDay, $tmpYear)) {
                    return false;
                }
            }
        }

        return true;
    }

    public function mb_str_pad($input, $pad_length, $pad_string = ' ', $pad_style = STR_PAD_RIGHT, $encoding = 'UTF-8')
    {
        return str_pad($input, strlen($input) - mb_strlen($input, $encoding) + $pad_length, $pad_string, $pad_style);
    }

    //20140203 luchao add end

    /*************************************
     * 処理名	：関数終了時処理
     * 関数名	：finally
     * 引数		：無し
     * 戻り値		：無し
     * 処理説明	：register_shutdown_functionで呼び出された関数の終了後に実行する処理
     *************************************/
    public function finally()
    {
        if (isset($this->xml)) {
            unset($this->xml);
        }
        if (isset($this->xml_path)) {
            unset($this->xml_path);
        }
    }

    //***********************************************************************
    //処 理 名：部署マスタ構造体
    //戻 り 値：配列
    //***********************************************************************
    public $GS_KAMOKUMST = array(
        'intRtnCD' => 0,
        //リターンコード
        'strKamokuNM' => '', //科目名
    );

    //***********************************************************************
    //処 理 名：部署マスタ構造体
    //戻 り 値：配列
    //***********************************************************************
    public $GS_BUSYOMST = array(
        'intRtnCD' => 0,
        //リターンコード
        'strBusyoNM' => '',
        //部署名
        'strKKRBusyo' => '', //'括り部署コード
    );

    //20150928 yin add S
    //**********************************************************************
    //処 理 名：浮動小数点数転整数（数値）
    //関 数 名：Dou_to_Long
    //引    数：strDou
    //戻 り 値：整数
    //処理説明：浮動小数点数転整数
    //**********************************************************************
    public function Dou_to_Long($strDou)
    {
        $arrDou = explode('.', $strDou);
        if (2 == count($arrDou)) {
            $firstDou = substr($arrDou[1], 0, 1);
            if (5 == $firstDou) {
                $revlong = strrev($arrDou[0]);
                $endlong = substr($revlong, 0, 1);
                if (0 == $endlong || 2 == $endlong || 4 == $endlong || 6 == $endlong || 8 == $endlong) {
                    if ($strDou < 0) {
                        $strDou = ceil($strDou);
                    } else {
                        $strDou = floor($strDou);
                    }

                    return $strDou;
                } else {
                    if ($strDou < 0) {
                        $strDou = floor($strDou);
                    } else {
                        $strDou = ceil($strDou);
                    }

                    return $strDou;
                }
            } else {
                $strDou = round($strDou);

                return $strDou;
            }
        } else {
            return $strDou;
        }
    }

    //20150928 yin add E
}
