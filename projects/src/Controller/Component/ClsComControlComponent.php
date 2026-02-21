<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
// App::uses('ClsComControl', 'Model/R4/Component');
use App\Model\R4\Component\ClsComControl;

class ClsComControlComponent extends Component
{

    //**********************************************************************
    //処 理 名：排他制御
    //関 数 名：FncControlCheck
    //引    数：MyControl　(I)ｺﾝﾄﾛｰﾙ番号
    //                      1.ﾀﾞｳﾝﾛｰﾄﾞ 2.取込処理 3.注文書系CSV 4.登録予定CSV
    //                      5.新車納品書CSV 6.売掛CSV 7.会計CSV
    //戻 り 値：True:実行可能　False:実行中断
    //処理説明：排他制御を行う
    //**********************************************************************
    public function FncControlCheck($strMyControl, $strDBLink = "")
    {
        $result = "";
        $strSQL = "";
        // $objDr = "";

        try {
            $strSQL .= " SELECT *";
            $strSQL .= " FROM   M_CONTROL@DBLINK";

            $strSQL = str_replace("@DBLINK", $strDBLink, $strSQL);
            $ClsComControl = new ClsComControl();

            $result = $ClsComControl->select($strSQL);
            if ($result['result'] == false) {
                throw new \Exception($result["data"]);
            }
            // $objDr = $result['data'];
            //ｺﾝﾄﾛｰﾙﾏｽﾀに一件も存在しない場合
            if (count((array) $result["data"]) == 0) {
                return true;
            }
            //ﾀﾞｳﾝﾛｰﾄﾞ
            if ($result['data'][0]['LOCK_ID_1'] == "1") {
                if ($strMyControl == "1" || $strMyControl == "2" || $strMyControl == "8" || $strMyControl == "9") {
                    return false;
                }
            }
            //取込処理
            if ($result['data'][0]['LOCK_ID_2'] == "1") {
                return false;
            }
            //注文書系CSV
            if ($result['data'][0]['LOCK_ID_3'] == "1") {
                if ($strMyControl == "2" || $strMyControl == "3" || $strMyControl == "8") {
                    return false;
                }
            }
            //登録予定CSV
            if ($result['data'][0]['LOCK_ID_4'] == "1") {
                if ($strMyControl == "2" || $strMyControl == "4" || $strMyControl == "9") {
                    return false;
                }
            }
            //新車納品書CSV
            if ($result['data'][0]['LOCK_ID_4'] == "1") {
                if ($strMyControl == "2" || $strMyControl == "5") {
                    return false;
                }
            }
            //売掛CSV
            if ($result['data'][0]['LOCK_ID_6'] == "1") {
                if ($strMyControl == "2" || $strMyControl == "6") {
                    return false;
                }
            }
            //会計CSV
            if ($result['data'][0]['LOCK_ID_7'] == "1") {
                if ($strMyControl == "2" || $strMyControl == "7") {
                    return false;
                }
            }
            //注文書個別ﾀﾞｳﾝﾛｰﾄﾞ
            if ($result['data'][0]['LOCK_ID_8'] == "1") {
                if ($strMyControl == "1" || $strMyControl == "2" || $strMyControl == "8" || $strMyControl == "3") {
                    return false;
                }
            }
            //登録予定個別ﾀﾞｳﾝﾛｰﾄﾞ
            if ($result['data'][0]['LOCK_ID_9'] == "1") {
                if ($strMyControl == "1" || $strMyControl == "2" || $strMyControl == "9" || $strMyControl == "4") {
                    return false;
                }
            }
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

}