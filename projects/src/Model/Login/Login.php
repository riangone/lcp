<?php

namespace App\Model\Login;

use Exception;
use mysqli;

$connection = null;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class Login
{
    public $useDbConfig = 'test';

    public function connMysql()
    {
        $resultDB = array(
            'result' => false,
            'ERROR' => '',
            'data' => '',
        );
        try {
            $link = mysqli_connect('192.168.2.251', 'gdmz', 'gdmz123');
            if (!$link) {
                throw new Exception(mysqli_error($link), mysqli_errno($link));
            }
            if (!mysqli_select_db($link, 'gdmz')) {
                throw new Exception(mysqli_error($link), mysqli_errno($link));
            }
            $GLOBALS['connection'] = $link;
            $resultDB['data'] = $link;
            $resultDB['result'] = true;
        } catch (Exception $e) {
            $resultDB = array(
                'result' => false,
                'ERROR' => $e->getCode(),
                'data' => $e->getMessage(),
            );
        }

        return $resultDB;
    }

    public function select($sql)
    {
        try {
            $resultDB = array(
                'result' => false,
                'ERROR' => '',
                'data' => '',
            );
            if ($GLOBALS['connection'] instanceof mysqli) {
                $resultSql = mysqli_query($GLOBALS['connection'], $sql);

                if (!$resultSql) {
                    throw new Exception(mysqli_error($GLOBALS['connection']), mysqli_errno($GLOBALS['connection']));
                }
                $resultDB = array(
                    'result' => true,
                    'ERROR' => '',
                    'data' => $resultSql,
                );
            }

        } catch (Exception $e) {
            $resultDB = array(
                'result' => false,
                'ERROR' => $e->getCode(),
                'data' => $e->getMessage(),
            );
        }

        return $resultDB;
    }

    public function loginSys($postData = null)
    {
        return $this->select($this->loginSysSql($postData));
    }

    public function isEmailExist($postData = null)
    {
        return $this->select($this->isEmailExistSql($postData));
    }

    public function isIdExist($postData = null)
    {
        return $this->select($this->isIdExistSql($postData));
    }

    public function loginSysSql($conditions)
    {
        $strSql = "SELECT * FROM user WHERE USR_ID='" . $conditions['usr_id'] . "'";

        return $strSql;
    }

    public function isEmailExistSql($conditions)
    {
        $strSql = "SELECT * FROM user WHERE email='" . $conditions['EmailAddress'] . "'";

        return $strSql;
    }

    public function isIdExistSql($conditions)
    {
        $strSql = "SELECT * FROM user WHERE USR_ID='" . $conditions['Usr_Id'] . "'";

        return $strSql;
    }
}