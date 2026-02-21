<?php
/**
 * 説明：.
 *
 *
 * @author zhangbowen
 * @copyright (GD) (ZM)
 */

namespace App\Model\Main;

// 共通クラスの読込み
use App\Model\Component\ClsComDb;
use mysqli;

$connection = null;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class Main extends ClsComDb
{
    public $useDbConfig = 'test';

    public function connMysql()
    {
        $result = array(
            'result' => false,
            'data' => '',
        );
        $link = mysqli_connect('192.168.2.251', 'gdmz', 'gdmz123');
        if (!$link) {
            throw new \Exception(mysqli_error($link), mysqli_errno($link));
        }
        if (!mysqli_select_db($link, 'gdmz')) {
            throw new \Exception(mysqli_error($link), mysqli_errno($link));
        }
        $GLOBALS['connection'] = $link;
        $result['data'] = $link;

        mysqli_select_db($link, 'gdmz');
        $result['result'] = true;

        return $result;
    }

    public function select($sql)
    {
        $result = null;
        if ($GLOBALS['connection'] instanceof mysqli)
            $result = mysqli_query($GLOBALS['connection'], $sql);

        return $result;
    }

    public function mainSys($postData = null)
    {
        return $this->select($this->mainSysSql($postData));
    }

    public function mainSysSql($conditions)
    {
        $strSql = "SELECT * FROM user WHERE USR_ID='" . $conditions . "'";

        return $strSql;
    }

    //根据sys_cd,查询sys_name
    public function select_by_cd($postData)
    {
        return $this->select($this->mainSysCdSql($postData));
        //return $this -> statement = $this -> conn -> query("SELECT sys_name,sys_key FROM $this->table WHERE sys_cd='$condition'");
    }

    public function mainSysCdSql($conditions)
    {
        $strSql = "SELECT sys_name, sys_key FROM system_m WHERE sys_cd='" . $conditions . "'";
        //echo $strSql."<BR>";
        return $strSql;
    }

    //20210113 ZBW INS S
    public function implementsql($sql)
    {
        try {
            $resultDB = array(
                'result' => false,
                'ERROR' => '',
                'data' => '',
            );
            if ($GLOBALS['connection'] instanceof mysqli) {
                mysqli_query($GLOBALS['connection'], 'SET NAMES UTF8');
                $resultSql = mysqli_query($GLOBALS['connection'], $sql);
                if (!$resultSql) {
                    throw new \Exception(mysqli_error($GLOBALS['connection']), mysqli_errno($GLOBALS['connection']));
                }
                $resultDB = array(
                    'result' => true,
                    'ERROR' => '',
                    'data' => $resultSql,
                );
            }


        } catch (\Exception $e) {
            $resultDB = array(
                'result' => false,
                'ERROR' => $e->getCode(),
                'data' => $e->getMessage(),
            );
        }

        return $resultDB;
    }

    public function FunLoadData($postData = null)
    {
        return $this->implementsql($this->FunLoadDataSql($postData));
    }

    public function FunLoadDataSql($postData)
    {
        $strSql = '';
        $strSql .= 'SELECT ' . "\r\n";
        $strSql .= '	USR_ID,' . "\r\n";
        $strSql .= '	USR_NAME,' . "\r\n";
        $strSql .= '	email,' . "\r\n";
        $strSql .= '	POSITION' . "\r\n";
        $strSql .= 'FROM user ' . "\r\n";
        $strSql .= "WHERE USR_ID='@USR_ID'";
        $strSql = str_replace('@USR_ID', $postData['USR_ID'], $strSql);

        return $strSql;
    }

    public function Funcheckpass($postData = null)
    {
        return $this->implementsql($this->FuncheckpassSql($postData));
    }

    public function FuncheckpassSql($postData)
    {
        $strSql = '';
        $strSql .= 'SELECT ' . "\r\n";
        $strSql .= '	PASS' . "\r\n";
        $strSql .= 'FROM user ' . "\r\n";
        $strSql .= "WHERE USR_ID='@USR_ID'";
        $strSql = str_replace('@USR_ID', $postData['USR_ID'], $strSql);

        return $strSql;
    }

    public function FunUserUpd($postData = null)
    {
        return $this->implementsql($this->FunUserUpdSql($postData));
    }

    public function FunUserUpdSql($postData)
    {
        $strSql = '';
        $strSql .= 'UPDATE' . "\r\n";
        $strSql .= '	gdmz.user' . "\r\n";
        $strSql .= 'SET' . "\r\n";
        $strSql .= "	USR_NAME='@USR_NAME'," . "\r\n";
        $strSql .= "	email= '@email'" . "\r\n";
        $strSql .= "WHERE USR_ID='@USR_ID'" . "\r\n";
        $postData['USR_NAME'] = addslashes($postData['USR_NAME']);
        $strSql = str_replace('@USR_ID', $postData['USR_ID'], $strSql);
        $strSql = str_replace('@USR_NAME', $postData['USR_NAME'], $strSql);
        $strSql = str_replace('@email', $postData['email'], $strSql);

        return $strSql;
    }

    public function FunPassUpd($postData = null)
    {
        return $this->implementsql($this->FunPassUpdSql($postData));
    }

    public function FunPassUpdSql($postData)
    {
        $strSql = '';
        $strSql .= 'UPDATE' . "\r\n";
        $strSql .= '	user' . "\r\n";
        $strSql .= 'SET' . "\r\n";
        $strSql .= "	PASS = '@PASS'" . "\r\n";
        $strSql .= "WHERE USR_ID ='@USR_ID'" . "\r\n";

        $strSql = str_replace('@USR_ID', $postData['USR_ID'], $strSql);
        $strSql = str_replace('@PASS', $postData['NewPASS'], $strSql);

        return $strSql;
    }

    //20210113 ZBW INS E
}