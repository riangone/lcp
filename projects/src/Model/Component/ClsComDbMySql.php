<?php
namespace App\Model\Component;

use Cake\Routing\Router;
use PDO;
class ClsComDbMySql
{
    // 解放が必要な変数をメンバーに設定
    protected $conn_sql = "";
    protected $Sel_Array = "";

    //20131023 luchao add start
    public $number_of_rows = "";
    //20131023 luchao add end

    //20131024 luchao add start
    public $GS_LOGINUSER = "";
    //20131024 luchao add edn
    private $SessionComponent;

    protected $ErrorResult = array(
        "result" => FALSE,
        "data" => 'sql error'
    );

    protected $Do_MYSQL = "";

    protected $Con_Sta = FALSE;

    function __construct()
    {
        include_once 'Do_MYSQL.php';
        $this->Do_MYSQL = new Do_MYSQL();
        $this->GS_LOGINUSER = $this->Do_MYSQL->GS_LOGINUSER;
        // App::uses('SessionComponent', 'Controller/Component');
        // $this->GS_LOGINUSER['strUserID'] = SessionComponent::read('login_user');
        $this->SessionComponent = Router::getRequest()->getSession();
        $this->GS_LOGINUSER['strUserID'] = $this->SessionComponent->read('login_user');
    }

    /*************************************
     * 処理名	：データ抽出
     * 関数名	：select
     * 引数		：$strsql		SQL文
     * 戻り値		：配列データ
     * 			：result		正常：true,異常：false
     * 			：data		正常:検索結果,異常:エラーメッセージ
     * 処理説明	：ｓｑｌ文を実行し取得結果を返却する
     *************************************/
    public function select($strsql)
    {
        $result = FALSE;
        $chkstr = stripos($strsql, "select");
        if ($chkstr === FALSE) {
            $result = $this->ErrorResult;
        } else {
            $result = $this->FillNoTransaction($strsql);
        }
        return $result;
    }

    /*************************************
     * 処理名	：データ追加
     * 関数名	：insert
     * 引数		：$strsql		SQL文
     * 戻り値		：配列データ
     * 			：result		正常：true,異常：false
     * 			：data		正常:正常終了メッセージ,異常:エラーメッセージ
     * 			：number_of_rows ｓｑｌ文実行し、影響を受けた行数
     * 処理説明	：ｓｑｌ文を実行し追加結果を返却する
     *************************************/
    public function insert($strsql)
    {
        $result = FALSE;
        $chkstr = stripos($strsql, "insert");
        if ($chkstr === FALSE) {
            $result = $this->ErrorResult;
        } else {
            $result = $this->ExecuteSqlNoTransaction($strsql);
        }
        return $result;
    }

    /*************************************
     * 処理名	：データ更新
     * 関数名	：update
     * 引数		：$strsql		SQL文
     * 戻り値		：配列データ
     * 			：result		正常：true,異常：false
     * 			：data		正常:正常終了メッセージ,異常:エラーメッセージ
     * 			：number_of_rows ｓｑｌ文実行し、影響を受けた行数
     * 処理説明	：ｓｑｌ文を実行し更新結果を返却する
     *************************************/
    public function update($strsql)
    {
        $result = FALSE;
        $chkstr = stripos($strsql, "update");
        if ($chkstr === FALSE) {
            $result = $this->ErrorResult;
        } else {
            $result = $this->ExecuteSqlNoTransaction($strsql);
        }
        return $result;
    }

    /*************************************
     * 処理名	：データ削除
     * 関数名	：delete
     * 引数		：$strsql		SQL文
     * 戻り値		：配列データ
     * 			：result		正常：true,異常：false
     * 			：data		正常:正常終了メッセージ,異常:エラーメッセージ
     * 			：number_of_rows ｓｑｌ文実行し、影響を受けた行数
     * 処理説明	：ｓｑｌ文を実行し削除結果を返却する
     *************************************/
    public function delete($strsql)
    {
        $result = FALSE;
        $chkstr = stripos($strsql, "delete");
        if ($chkstr === FALSE) {
            $result = $this->ErrorResult;
        } else {
            $result = $this->ExecuteSqlNoTransaction($strsql);
        }
        return $result;
    }

    /*************************************
     * 処理名	：データ検索
     * 関数名	：Fill
     * 引数		：$strsql		SQL文
     * 戻り値		：配列データ
     * 			：result		正常：true,異常：false
     * 			：data		正常:検索結果データ,異常:エラーメッセージ
     * 処理説明	：ｓｑｌ文を実行し検索結果を返却する
     *************************************/
    public function Fill($strsql)
    {
        $result = FALSE;
        $resultdata = FALSE;
        $row = "";
        try {
            $this->Sel_Array = $this->Do_MYSQL->Do_Execute($strsql);
            if (!$this->Sel_Array['Pra_sta']) {
                throw new \Exception($this->Sel_Array['Pra_info']);
            }

            $resultdata = $this->Sel_Array['Pra_info']->fetchAll(PDO::FETCH_CLASS);

            $row = $this->Sel_Array['Pra_info']->rowCount();

            $result = TRUE;
        } catch (\Exception $e) {
            $result = FALSE;
            $resultdata = $e->getMessage();
            $row = "";
        }
        return array(
            "result" => $result,
            "data" => $resultdata,
            "row" => $row
        );
    }

    //20140225 luchao add start

    //**********************************************************************
    //処 理 名：SQL実行(SELECT)
    //関 数 名：SubExecute
    //引    数：strSql       (I) 実行するSQL
    //戻 り 値：Object
    //処理説明：SQL(SELECT)を実行する。最初の行の最初の列だけ戻す。
    //**********************************************************************
    public function FncExecuteScalar($strSql)
    {
        register_shutdown_function(
            array(
                $this,
                "finally"
            )
        );
        $result = FALSE;
        $resultdata = FALSE;
        $row = "";

        try {
            // call DB connection
            $this->conn_sql = $this->Do_MYSQL->Do_conn();
            if (!$this->conn_sql['conn_sta']) {
                throw new \Exception($this->conn_sql['conn_sql']);
            }
            $this->Con_Sta = TRUE;

            // call select execute
            $this->Sel_Array = $this->Do_MYSQL->Do_Execute($strSql);
            if (!$this->Sel_Array['Pra_sta']) {
                throw new \Exception($this->Sel_Array['Pra_info']);
            }
            $row = $this->Sel_Array['Pra_info']->rowCount();
            if ($row > 0) {
                $resultdata = $this->Sel_Array['Pra_info']->fetch(PDO::FETCH_CLASS);
            } else {
                $resultdata = NULL;
            }
            $result = TRUE;
        } catch (\Exception $e) {
            $result = FALSE;
            $resultdata = $e->getMessage();
            $row = "";
        }
        return array(
            "result" => $result,
            "data" => $resultdata,
            "row" => $row
        );
    }

    //20140225 luchao add end

    /*************************************
     * 処理名	：データ検索
     * 関数名	：FillNoTransaction
     * 引数		：$strsql		SQL文
     * 戻り値		：配列データ
     * 			：result		正常：true,異常：false
     * 			：data		正常:検索結果データ,異常:エラーメッセージ
     * 処理説明	：ｓｑｌ文を実行し検索結果を返却する
     *************************************/
    public function FillNoTransaction($strsql)
    {
        register_shutdown_function(
            array(
                $this,
                "finally"
            )
        );
        $result = FALSE;
        $resultdata = FALSE;
        $row = "";

        try {
            // call DB connection
            $this->conn_sql = $this->Do_MYSQL->Do_conn();
            if (!$this->conn_sql['conn_sta']) {
                throw new \Exception($this->conn_sql['conn_sql']);
            }
            $this->Con_Sta = TRUE;

            // call select execute
            $this->Sel_Array = $this->Do_MYSQL->Do_Execute($strsql);
            if (!$this->Sel_Array['Pra_sta']) {
                throw new \Exception($this->Sel_Array['Pra_info']);
            }
            $resultdata = $this->Sel_Array['Pra_info']->fetchAll(PDO::FETCH_ASSOC);

            $row = $this->Sel_Array['Pra_info']->rowCount();

            $result = TRUE;
        } catch (\Exception $e) {
            $result = FALSE;
            $resultdata = $e->getMessage();
            $row = "";
        }
        return array(
            "result" => $result,
            "data" => $resultdata,
            "row" => $row
        );
    }

    public function Do_transaction()
    {
        $this->Do_MYSQL->Do_transaction();
    }

    public function Do_commit()
    {
        $this->Do_MYSQL->Do_commit();
    }

    public function Do_rollback($tables)
    {
        $this->Do_MYSQL->Do_rollback($tables);
    }

    public function Do_conn()
    {
        $result = $this->Do_MYSQL->Do_conn();
        return array(
            "result" => $result['conn_sta'],
            "data" => $result['conn_sql']
        );
    }

    public function Do_close()
    {
        $result = $this->Do_MYSQL->Do_close();
        return array(
            "result" => $result['conn_sta'],
            "data" => $result['conn_sql']
        );
    }

    /*************************************
     * 処理名	：SQL実行(INSERT,UPDATE,DELETE)
     * 関数名	：Do_Execute
     * 引数		：$strsql		SQL文
     * 戻り値		：配列データ
     * 			：result		正常：true,異常：false
     * 			：data		正常:正常終了メッセージ,異常:エラーメッセージ
     * 			：number_of_rows ｓｑｌ文実行し、影響を受けた行数
     * 処理説明	：ｓｑｌ文の実行結果を返却する
     *************************************/
    public function Do_Execute($Sql)
    {
        $this->Sel_Array = $this->Do_MYSQL->Do_Execute($Sql);
        $this->number_of_rows = $this->Sel_Array['Pra_info']->rowCount();
        ;
        return array(
            "result" => $this->Sel_Array['Pra_sta'],
            "data" => $this->Sel_Array['Pra_info'],
            "number_of_rows" => $this->number_of_rows
        );
    }

    /*************************************
     * 処理名	：SQL実行(INSERT,UPDATE,DELETE)
     * 関数名	：ExecuteSqlNoTransaction
     * 引数		：$strsql		SQL文
     * 戻り値		：配列データ
     * 			：result		正常：true,異常：false
     * 			：data		正常:正常終了メッセージ,異常:エラーメッセージ
     *			：number_of_rows ｓｑｌ文実行し、影響を受けた行数
     * 処理説明	：ｓｑｌ文の実行結果を返却する
     *************************************/
    public function ExecuteSqlNoTransaction($strsql)
    {
        register_shutdown_function(
            array(
                $this,
                "finally"
            )
        );
        $result = FALSE;
        $resultdata = "";

        try {
            $this->conn_sql = $this->Do_MYSQL->Do_conn();
            if (!$this->conn_sql['conn_sta']) {
                throw new \Exception($this->conn_sql['conn_sql']);
            }
            $this->Con_Sta = TRUE;
            // call select execute
            $this->Sel_Array = $this->Do_MYSQL->Do_Execute($strsql);
            if (!$this->Sel_Array['Pra_sta']) {
                $this->number_of_rows = -1;
                throw new \Exception($this->Sel_Array['Pra_info']);
            }
            $this->number_of_rows = $this->Sel_Array['Pra_info']->rowCount();
            $result = TRUE;
            $resultdata = $this->conn_sql['conn_sql']->lastInsertId();
        } catch (\Exception $e) {
            $resultdata = $e->getMessage();
        }
        return array(
            "result" => $result,
            "data" => $resultdata,
            "number_of_rows" => $this->number_of_rows
        );
    }

    /*************************************
     * 処理名	：関数終了時処理
     * 関数名	：finally
     * 引数		：無し
     * 戻り値		：無し
     * 処理説明	：register_shutdown_functionで呼び出された関数の終了後に実行する処理
     *************************************/
    function finally()
    {
        if (isset($this->Sel_Array) && isset($this->Sel_Array['Pra_sta'])) {
            if ($this->Sel_Array['Pra_sta'] != FALSE) {
                $this->Sel_Array['Pra_info']->closeCursor();
            }
        }

        if (isset($this->conn_sql)) {
            if ($this->conn_sql['conn_sta'] != FALSE) {
                $this->conn_sql['conn_sql'] = null;
            }

        }

        unset($this->Sel_Array);
        unset($this->conn_sql);
    }

}
