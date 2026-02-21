<?php
/**
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20240909           Bug                      oci_close警告修正              　     caina
 * * --------------------------------------------------------------------------------------------
 */

namespace App\Model\Component;

// use App\Model\Component\SessionComponent;
use Cake\Routing\Router;

class ClsComDb
{
    // 解放が必要な変数をメンバーに設定
    protected $conn_orl = '';
    protected $Sel_Array = '';

    //20131023 luchao add start
    public $number_of_rows = '';
    //20131023 luchao add end

    //20131024 luchao add start
    public $GS_LOGINUSER = '';
    private $SessionComponent;
    private $request;
    //20131024 luchao add edn

    protected $ErrorResult = array(
        'result' => false,
        'data' => 'sql error',
    );

    protected $Do_SQL = '';

    protected $Con_Sta = false;

    public function __construct()
    {
        include_once 'Do_SQL.php';
        $this->Do_SQL = new Do_SQL();
        $this->GS_LOGINUSER = $this->Do_SQL->GS_LOGINUSER;
        // App::uses('SessionComponent', 'Controller/Component');
        $this->SessionComponent = Router::getRequest()->getSession();
        $this->GS_LOGINUSER['strUserID'] = $this->SessionComponent->read('login_user');

        // $this->GS_LOGINUSER['strUserID'] = SessionComponent::read('login_user');
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
        $result = false;
        $chkstr = stripos($strsql, 'select');
        if (false === $chkstr) {
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
        $result = false;
        $chkstr = stripos($strsql, 'insert');
        if (false === $chkstr) {
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
        $result = false;
        $chkstr = stripos($strsql, 'update');
        if (false === $chkstr) {
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
        $result = false;
        $chkstr = stripos($strsql, 'delete');
        if (false === $chkstr) {
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
        $result = false;
        $resultdata = false;
        $row = '';
        try {
            $this->Sel_Array = $this->Do_SQL->Do_Execute($strsql);
            if (!$this->Sel_Array['Pra_sta']) {
                throw new \Exception($this->Sel_Array['Pra_info']);
            }
            //20240419 lujunxia PHP8 upd s
            //$row = oci_fetch_all($this->Sel_Array['Pra_info'], $resultdata, null, null, OCI_FETCHSTATEMENT_BY_ROW);
            //default $offset = 0 $limit = -1
            $row = oci_fetch_all($this->Sel_Array['Pra_info'], $resultdata, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
            //20240419 lujunxia PHP8 upd e
            $result = true;
        } catch (\Exception $e) {
            $result = false;
            $resultdata = $e->getMessage();
            $row = '';
        }

        return array(
            'result' => $result,
            'data' => $resultdata,
            'row' => $row,
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
    // public function FncExecuteScalar($strSql)
    // {
    //     register_shutdown_function(
    //         array(
    //             $this,
    //             'finally',
    //         )
    //     );
    //     $result = false;
    //     $resultdata = false;
    //     $row = '';

    //     try {
    //         // call DB connection
    //         $this->conn_orl = $this->Do_SQL->Do_conn();
    //         if (!$this->conn_orl['conn_sta']) {
    //             throw new \Exception($this->conn_orl['conn_orl']);
    //         }
    //         $this->Con_Sta = true;

    //         // call select execute
    //         $this->Sel_Array = $this->Do_SQL->Do_Execute($strSql);
    //         if (!$this->Sel_Array['Pra_sta']) {
    //             throw new \Exception($this->Sel_Array['Pra_info']);
    //         }
    //         $row = oci_fetch_all($this->Sel_Array['Pra_info'], $resultdata, 0, 1, OCI_FETCHSTATEMENT_BY_ROW);
    //         if ($row > 0) {
    //             $resultdata = reset($resultdata[0]);
    //         } else {
    //             $resultdata = null;
    //         }
    //         $result = true;
    //     } catch (\Exception $e) {
    //         $result = false;
    //         $resultdata = $e->getMessage();
    //         $row = '';
    //     }

    //     return array(
    //         'result' => $result,
    //         'data' => $resultdata,
    //         'row' => $row,
    //     );
    // }

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
                'finally',
            )
        );
        $result = false;
        $resultdata = false;
        $row = '';

        try {
            // call DB connection
            $this->conn_orl = $this->Do_SQL->Do_conn();
            if (!$this->conn_orl['conn_sta']) {
                throw new \Exception($this->conn_orl['conn_orl']);
            }
            $this->Con_Sta = true;

            // call select execute
            $this->Sel_Array = $this->Do_SQL->Do_Execute($strsql);
            if (!$this->Sel_Array['Pra_sta']) {
                throw new \Exception($this->Sel_Array['Pra_info']);
            }
            $row = oci_fetch_all($this->Sel_Array['Pra_info'], $resultdata, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);

            $result = true;
        } catch (\Exception $e) {
            $result = false;
            $resultdata = $e->getMessage();
            $row = '';
        }

        return array(
            'result' => $result,
            'data' => $resultdata,
            'row' => $row,
        );
    }

    // public function deleteInsert($strsql_Delete, $strsql_Insert)
    // {
    // register_shutdown_function(array(
    // $this,
    // "finally"
    // ));
    // $result = FALSE;
    // $resultdata = "";
    //
    // try
    // {
    // ////////////////////////////////////////
    // // DB接続からトランザクション開始までの共通処理
    // // include_once 'Do_SQL.php';
    // // $Do_SQL = new Do_SQL();
    // $this -> conn_orl = $this -> Do_SQL -> Do_conn();
    // if (!$this -> conn_orl['conn_sta'])
    // {
    // throw new \Exception($this -> conn_orl['conn_orl']);
    // }
    // //$this -> Do_SQL -> Do_transaction();
    // ////////////////////////////////////////
    //
    // // 20131004 kamei upd Start
    // // 複数回実行対象のSQL処理
    // $this -> Sel_Array = $this -> Do_SQL -> Do_Execute($strsql_Delete);
    // if (!$this -> Sel_Array['Pra_sta'])
    // {
    // throw new \Exception($this -> Sel_Array['Pra_info']);
    // }
    // $this -> Sel_Array = $this -> Do_SQL -> Do_Execute($strsql_Insert);
    // if (!$this -> Sel_Array['Pra_sta'])
    // {
    // throw new \Exception($this -> Sel_Array['Pra_info']);
    // }
    // // 20131004 kamei upd end
    //
    // ////////////////////////////////////////
    // // トランザクション終了から正常終了までの共通処理
    // $this -> conn_orl = $this -> Do_SQL -> Do_commit();
    // if (!$this -> conn_orl['conn_sta'])
    // {
    // throw new \Exception($this -> conn_orl['conn_orl']);
    // }
    // $resultdata = 'sql success';
    // $result = FALSE;
    // ////////////////////////////////////////
    // }
    // catch(\Exception $e)
    // {
    // $resultdata = $e -> getMessage();
    // //$this -> conn_orl = $this -> Do_SQL -> Do_rollback();
    // if (!$this -> conn_orl['conn_sta'])
    // {
    // $resultdata = $this -> conn_orl['conn_orl'];
    // }
    //
    // }
    //
    // if ($this -> Sel_Array['Pra_info'] != FALSE)
    // {
    // oci_free_statement($this -> Sel_Array['Pra_info']);
    // }
    //
    // if ($this -> conn_orl['conn_orl'] != FALSE)
    // {
    // $this -> Do_SQL -> Do_close();
    // }
    //
    // unset($this -> Sel_Array);
    // unset($this -> conn_orl);
    //
    // return array(
    // "result" => $result,
    // "data" => $resultdata
    // );
    // }

    public function Do_transaction()
    {
        $this->Do_SQL->Do_transaction();
    }

    public function Do_commit()
    {
        $this->Do_SQL->Do_commit();
    }

    public function Do_rollback()
    {
        $this->Do_SQL->Do_rollback();
    }

    public function Do_conn()
    {
        $result = $this->Do_SQL->Do_conn();

        return array(
            'result' => $result['conn_sta'],
            'data' => $result['conn_orl'],
        );
    }

    public function Do_close()
    {
        $result = $this->Do_SQL->Do_close();

        return array(
            'result' => $result['conn_sta'],
            'data' => $result['conn_orl'],
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
        $this->Sel_Array = $this->Do_SQL->Do_Execute($Sql);
        if (!$this->Sel_Array['Pra_sta']) {
            $this->number_of_rows = -1;
        } else {
            $this->number_of_rows = oci_num_rows($this->Sel_Array['Pra_info']);
        }

        return array(
            'result' => $this->Sel_Array['Pra_sta'],
            'data' => $this->Sel_Array['Pra_info'],
            'number_of_rows' => $this->number_of_rows,
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
                'finally',
            )
        );
        $result = false;
        $resultdata = '';

        try {
            $this->conn_orl = $this->Do_SQL->Do_conn();
            if (!$this->conn_orl['conn_sta']) {
                throw new \Exception($this->conn_orl['conn_orl']);
            }
            $this->Con_Sta = true;
            // call select execute
            $this->Sel_Array = $this->Do_SQL->Do_Execute($strsql);
            if (!$this->Sel_Array['Pra_sta']) {
                $this->number_of_rows = -1;
                throw new \Exception($this->Sel_Array['Pra_info']);
            }
            $this->number_of_rows = oci_num_rows($this->Sel_Array['Pra_info']);
            $result = true;
            $resultdata = null;
        } catch (\Exception $e) {
            $resultdata = $e->getMessage();
        }

        return array(
            'result' => $result,
            'data' => $resultdata,
            'number_of_rows' => $this->number_of_rows,
        );
    }

    /*************************************
     * 処理名	：関数終了時処理
     * 関数名	：finally
     * 引数		：無し
     * 戻り値		：無し
     * 処理説明	：register_shutdown_functionで呼び出された関数の終了後に実行する処理
     *************************************/
    public function finally()
    {
        if (isset($this->Sel_Array) && $this->Sel_Array !== '') {
            if (false != $this->Sel_Array['Pra_sta']) {
                oci_free_statement($this->Sel_Array['Pra_info']);
            }
        }

        if (isset($this->conn_orl)) {
            if (false != $this->conn_orl['conn_sta']) {
                //20240909 caina upd s
                // oci_close($this->conn_orl['conn_orl']);
                // $this->Con_Sta = FALSE;
                if (isset($this->conn_orl['conn_orl']) && is_resource($this->conn_orl['conn_orl'])) {
                    oci_close($this->conn_orl['conn_orl']);
                    $this->Con_Sta = false;
                }
                //20240909 caina upd e
            }
        }

        unset($this->Sel_Array);
        unset($this->conn_orl);
    }
}