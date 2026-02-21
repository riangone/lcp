<?php
// App::uses("ClsComDbMySql", "Model/Component");
namespace App\Model\HMHRMS;

use App\Model\Component\ClsComDbMySql;

//*************************************
// * 処理名	：FrmExcelTorikomi
// * 関数名	：FrmExcelTorikomi
// * 処理説明	：共通クラスの読込み
//*************************************
class HMHRMS extends ClsComDbMySql
{
    //履歴部分column取得
    public function getColumns()
    {
        return parent::select($this->getcolumnsSql());
    }

    //履歴部分column取得SQL
    public function getColumnsSql()
    {
        $strSQL = "";
        $strSQL .= " SELECT" . "\r\n";
        $strSQL .= "     id, type, name, description, column_align, column_sortable" . "\r\n";
        $strSQL .= " FROM" . "\r\n";
        $strSQL .= "     custom_fields" . "\r\n";
        $strSQL .= " WHERE" . "\r\n";
        $strSQL .= "     (visible = '1')";
        return $strSQL;
    }

    //通勤方法option取得
    public function getOptionData($comment)
    {
        return parent::select($this->getOptionDataSql($comment));
    }

    //通勤方法option取得SQL
    public function getOptionDataSql($comment)
    {
        $strSQL = "";
        $strSQL .= " SELECT" . "\r\n";
        $strSQL .= "     code_name,code_value" . "\r\n";
        $strSQL .= " FROM" . "\r\n";
        $strSQL .= "     m_code" . "\r\n";
        $strSQL .= " WHERE" . "\r\n";
        $strSQL .= "     comment = '@comment' AND deleted = 0";
        $strSQL = str_replace('@comment', $comment, $strSQL);
        return $strSQL;
    }

    //社員個人情報部分データ取得
    public function getEmpData($empId)
    {
        return parent::select($this->getEmpDataSql($empId));
    }

    //社員個人情報部分データ取得SQL
    public function getEmpDataSql($empId)
    {
        $strSQL = "";
        $strSQL .= " SELECT" . "\r\n";
        $strSQL .= "     employee.*, m_code.code_name, EDN.dispName, EDN.dispNamePhonetic" . "\r\n";
        $strSQL .= " FROM" . "\r\n";
        $strSQL .= "     employee" . "\r\n";
        $strSQL .= "         LEFT JOIN" . "\r\n";
        $strSQL .= "     employee_display_name EDN ON EDN.empId = employee.empId and EDN.deleted = 0" . "\r\n";
        $strSQL .= "         LEFT JOIN" . "\r\n";
        $strSQL .= "     m_code ON m_code.code_value = employee.commuteMethod" . "\r\n";
        $strSQL .= "        AND m_code.comment = '通勤方法'" . "\r\n";
        $strSQL .= " WHERE" . "\r\n";
        $strSQL .= "     employee.empId = '@id'";

        $strSQL = str_replace('@id', $empId, $strSQL);

        return $strSQL;
    }

    //社員個人情報部分データ取得
    public function getRirekiData($empId, $table)
    {
        return parent::select($this->getRirekiDataSql($empId, $table));
    }

    //履歴部分データ取得sql文
    public function getRirekiDataSql($empId, $table)
    {
        // $result = array(
        //     'result' => FALSE,
        //     'data' => null,
        //     'error' => ''
        // );
        $custom_values = "custom_values_" . $table;
        $employee_sub_table = "employee_sub_table_" . $table;
        $strSQL = "";
        $strSQL .= " SELECT " . "\r\n";
        $strSQL .= "`@customValues`.`id`, " . "\r\n";
        $strSQL .= "`custom_fields`.`name`,";
        $strSQL .= "`@employeeSubTable`.`id` AS `estid`," . "\r\n";
        $strSQL .= "`@customValues`.`value` AS `value`," . "\r\n";
        $strSQL .= "`@customValues`.`custom_field_id` AS `custom_field_id`" . "\r\n";
        $strSQL .= " FROM" . "\r\n";
        $strSQL .= "   (SELECT" . "\r\n";
        $strSQL .= "       `id`" . "\r\n";
        $strSQL .= "   FROM" . "\r\n";
        $strSQL .= "       `employee`" . "\r\n";
        $strSQL .= "   WHERE" . "\r\n";
        $strSQL .= "       `employee`.`empId` = '@empid') `employee`" . "\r\n";
        $strSQL .= "   INNER JOIN" . "\r\n";
        $strSQL .= "       `@employeeSubTable` ON `employee`.`id` = `@employeeSubTable`.`employee_id`" . "\r\n";
        $strSQL .= "   JOIN" . "\r\n";
        $strSQL .= "       `@customValues` ON `@employeeSubTable`.`id` = `@customValues`.`customized_id`" . "\r\n";
        $strSQL .= "   LEFT JOIN" . "\r\n";
        $strSQL .= "        `custom_fields` ON `custom_fields`.`id` = `@customValues`.`custom_field_id`" . "\r\n";

        $strSQL = str_replace('@customValues', $custom_values, $strSQL);
        $strSQL = str_replace('@employeeSubTable', $employee_sub_table, $strSQL);
        $strSQL = str_replace('@empid', $empId, $strSQL);
        return $strSQL;
    }

    //社員個人情報部分update
    public function updateEmp($column, $time)
    {
        return parent::update($this->updateEmpSql($column, $time));
    }

    //社員個人情報部分update sql文
    public function updateEmpSql($column, $time)
    {
        $strSQL = "";
        $strSQL .= "UPDATE employee SET" . "\r\n";
        if (isset($column['emp'])) {
            foreach ($column['emp'] as $key => $value) {
                if ($key == 'commuteDistance') {
                    if ($value !== "") {
                        $strSQL .= $key . "=" . $value . ",";
                    } else {
                        $strSQL .= $key . "=NULL,";
                    }
                } else {
                    $strSQL .= $key . "='" . addslashes($value) . "',";
                }
            }
        }
        $strSQL .= " updated_datetime='" . $time . "'\r\n";
        $strSQL .= " WHERE" . "\r\n";
        $strSQL .= "     employee.empId = '" . $column['empId'] . "'";
        return $strSQL;
    }

    //employeeテーブルのid取得
    public function getEmpId($empId)
    {
        return parent::select($this->getEmpIdSql($empId));
    }

    //employeeテーブルのid取得sql文
    public function getEmpIdSql($empId)
    {

        $strSQL = "";
        $strSQL .= " SELECT id FROM employee" . "\r\n";
        $strSQL .= " WHERE empId = '@empid'";
        $strSQL = str_replace("@empid", $empId, $strSQL);

        return $strSQL;

    }

    public function alterIncrementHistory()
    {
        return parent::Do_Execute($this->alterIncrementHistorySql());
    }

    public function alterIncrementHistorySql()
    {
        $strSQL = "";
        $strSQL .= " ALTER TABLE " . "\r\n";
        $strSQL .= " custom_values_history " . "\r\n";
        $strSQL .= " AUTO_INCREMENT=1 " . "\r\n";
        return $strSQL;
    }

    //変更履歴 insert
    public function insertHistory($data, $time)
    {
        return parent::insert($this->insertHistorySql($data, $time));
    }

    //変更履歴 insert sql文
    public function insertHistorySql($data, $time)
    {
        $m_code = array(
            'commuteMethod' => '通勤方法',
            'together' => '同居別居',
            'kinds_of_schools' => '学校種別'
        );

        $strSQL = "";
        $strSQL .= " INSERT INTO custom_values_history" . "\r\n";
        $strSQL .= " (`employee_id`,`update_user_id`,`item1`,`item2`,`row`,`update_before`,`update_after`,`updated_datetime`,`m_code_comment`)" . "\r\n";
        $strSQL .= " VALUES(" . "\r\n";
        $strSQL .= "'@employee_id'," . "\r\n";
        $strSQL .= "'@update_user_id'," . "\r\n";
        $strSQL .= "'@item1'," . "\r\n";
        if (isset($data['item2']) && $data['item2'] !== '') {
            $strSQL .= "'" . $data['item2'] . "'," . "\r\n";
        } else {
            $strSQL .= " NULL," . "\r\n";
        }
        if (isset($data['row']) && $data['row'] !== '') {
            $strSQL .= "'" . $data['row'] . "'," . "\r\n";
        } else {
            $strSQL .= " NULL," . "\r\n";
        }
        if (isset($data['update_before']) && $data['update_before'] !== '') {
            $strSQL .= "'" . addslashes($data['update_before']) . "'," . "\r\n";
        } else {
            $strSQL .= " NULL," . "\r\n";
        }
        if (isset($data['update_after']) && $data['update_after'] !== '') {
            $strSQL .= "'" . addslashes($data['update_after']) . "'," . "\r\n";
        } else {
            $strSQL .= " NULL," . "\r\n";
        }
        $strSQL .= "'@updated_datetime'," . "\r\n";
        $m_code_comment = FALSE;
        if (isset($data['item2']) && $data['item2'] !== '') {
            //履歴部分のm_code_comment
            foreach ($m_code as $value) {
                if ($data['item2'] == $value) {
                    $m_code_comment = TRUE;
                    $strSQL .= "'" . $value . "')" . "\r\n";
                }
            }
        } else
            if (isset($data['item1']) && $data['item1'] == $m_code['commuteMethod']) {
                //社員個人情報部分のm_code_comment
                $m_code_comment = TRUE;
                $strSQL .= "'" . $m_code['commuteMethod'] . "')" . "\r\n";
            }
        if (!$m_code_comment) {
            $strSQL .= " NULL)" . "\r\n";
        }

        $strSQL = str_replace('@employee_id', $data['id'] ?? '', $strSQL);
        $strSQL = str_replace('@update_user_id', $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace('@item1', $data['item1'], $strSQL);
        $strSQL = str_replace('@updated_datetime', $time, $strSQL);

        return $strSQL;
    }

    //employeeテーブルのcomment
    public function getInfo()
    {
        return parent::select($this->getInfoSql());
    }

    //employeeテーブルのcomment SQL
    public function getInfoSql()
    {

        $strSQL = "";
        $strSQL .= " SELECT" . "\r\n";
        $strSQL .= "      COLUMN_COMMENT,COLUMN_NAME" . "\r\n";
        $strSQL .= " FROM" . "\r\n";
        $strSQL .= "      information_schema.columns" . "\r\n";
        $strSQL .= " WHERE" . "\r\n";
        $strSQL .= "     table_name = 'employee'" . "\r\n";

        return $strSQL;
    }

    //履歴テーブルのフィールド名
    public function getDescription($type)
    {
        return parent::select($this->getDescriptionSql($type));
    }

    //履歴テーブルのフィールド名 SQL
    public function getDescriptionSql($type)
    {

        $strSQL = "";
        $strSQL .= " SELECT" . "\r\n";
        $strSQL .= "          id, name, position, description" . "\r\n";
        $strSQL .= " FROM" . "\r\n";
        $strSQL .= "      custom_fields" . "\r\n";
        $strSQL .= " WHERE" . "\r\n";
        $strSQL .= "      type = '@type'" . "\r\n";

        $strSQL = str_replace("@type", $type, $strSQL);
        return $strSQL;
    }

    public function alterIncrementSt($employee_sub_table)
    {
        return parent::Do_Execute($this->alterIncrementStSql($employee_sub_table));
    }

    public function alterIncrementStSql($employee_sub_table)
    {
        $strSQL = "";
        $strSQL .= " ALTER TABLE " . "\r\n";
        $strSQL .= " employee_sub_table_@type " . "\r\n";
        $strSQL .= " AUTO_INCREMENT=1 " . "\r\n";

        $strSQL = str_replace("@type", $employee_sub_table["type"], $strSQL);
        return $strSQL;
    }

    public function alterIncrementCv($employee_sub_table)
    {
        return parent::Do_Execute($this->alterIncrementCvSql($employee_sub_table));
    }

    public function alterIncrementCvSql($employee_sub_table)
    {
        $strSQL = "";
        $strSQL .= " ALTER TABLE ";
        $strSQL .= " custom_values_@type ";
        $strSQL .= " AUTO_INCREMENT=1 ";

        $strSQL = str_replace("@type", $employee_sub_table["type"], $strSQL);
        return $strSQL;
    }

    public function gettableColumnsOrder($type)
    {
        return parent::select($this->gettableColumnsOrderSql($type));
    }

    public function gettableColumnsOrderSql($type)
    {
        $strSQL = "";
        $strSQL .= " SELECT " . "\r\n";
        $strSQL .= " id,position " . "\r\n";
        $strSQL .= " FROM custom_fields " . "\r\n";
        $strSQL .= " WHERE type = '@type' " . "\r\n";
        $strSQL .= " ORDER BY " . "\r\n";
        $strSQL .= " position " . "\r\n";

        $strSQL = str_replace("@type", $type, $strSQL);
        return $strSQL;
    }

    public function getfieldsid($type)
    {
        return parent::select($this->getfieldsidSql($type));
    }

    public function getfieldsidSql($type)
    {
        $strSQL = "";
        $strSQL .= " SELECT " . "\r\n";
        $strSQL .= " id,name " . "\r\n";
        $strSQL .= " FROM custom_fields " . "\r\n";
        $strSQL .= " WHERE is_for_all=1 " . "\r\n";
        $strSQL .= " AND visible=1 " . "\r\n";
        $strSQL .= " AND type='@type' " . "\r\n";

        $strSQL = str_replace("@type", $type, $strSQL);
        return $strSQL;
    }

    public function selectForImport($employee_sub_table, $custom_values, $cris, $table_name, $isforallarr, $updcustomized_ids = null)
    {
        return parent::select($this->selectForImportSql($employee_sub_table, $custom_values, $cris, $table_name, $isforallarr, $updcustomized_ids));
    }

    public function selectForImportSql($employee_sub_table, $custom_values, $cris, $table_name, $isforallarr, $updcustomized_ids = null)
    {
        $type = $employee_sub_table['type'];
        $employee_id = $employee_sub_table['employee_id'];
        $sub_table = $table_name['sub_table'];
        $cv_table = $table_name['cv_table'];

        $strSQL = "";
        if ($type == 'punish') {
            $strSQL .= " SELECT MAX(cv_seq.value) + 1 AS seq ";
            $strSQL .= " FROM " . $cv_table . " AS cv_seq ";
            $strSQL .= " INNER JOIN " . $sub_table;
            $strSQL .= " ON " . $sub_table . ".employee_id = '" . $employee_id . "'";
            $strSQL .= " AND " . $sub_table . ".id = cv_seq.customized_id ";
            $strSQL .= " WHERE ";
            $strSQL .= " cv_seq.custom_field_id = " . $cris[1];
        } else {
            $strSQL .= " SELECT " . $sub_table . ".id ";
            $strSQL .= " FROM " . $sub_table;
            for ($i = 0; $i < count($isforallarr); $i++) {
                $strSQL .= " INNER JOIN " . $cv_table . " AS cve_" . $isforallarr[$i] . " ";
                $strSQL .= " ON cve_" . $isforallarr[$i] . ".value = '" . addslashes($custom_values[$isforallarr[$i]]) . "'";
                $strSQL .= " AND cve_" . $isforallarr[$i] . ".custom_field_id = " . $isforallarr[$i];
                if ($i > 0) {
                    $strSQL .= " AND cve_" . $isforallarr[$i - 1] . ".customized_id = cve_" . $isforallarr[$i] . ".customized_id ";
                }
            }

            $strSQL .= " WHERE ";
            $strSQL .= " " . $sub_table . ".employee_id = '" . $employee_id . "'";
            $strSQL .= " AND cve_" . $isforallarr[0] . ".customized_id = " . $sub_table . ".id ";
            if ($updcustomized_ids != null) {
                $strSQL .= " AND cve_" . $isforallarr[0] . ".customized_id <> " . $updcustomized_ids;
            }
        }
        return $strSQL;
    }

    public function subIns($stm, $employee_sub_table, $time)
    {
        return parent::insert($this->subInsSql($stm, $employee_sub_table, $time));
    }

    public function subInsSql($stm, $employee_sub_table, $time)
    {
        $strSQL = "";
        $strSQL .= " INSERT INTO @stm " . "\r\n";
        $strSQL .= " (employee_id,created_datetime,updated_datetime) " . "\r\n";
        $strSQL .= " VALUES ( " . "\r\n";
        $strSQL .= " @employee_id " . "\r\n";
        $strSQL .= " ,@created_datetime " . "\r\n";
        $strSQL .= " ,@updated_datetime " . "\r\n";
        $strSQL .= " ) " . "\r\n";

        $strSQL = str_replace("@stm", $stm, $strSQL);
        $strSQL = str_replace("@employee_id", $employee_sub_table['employee_id'], $strSQL);
        $strSQL = str_replace("@created_datetime", $time, $strSQL);
        $strSQL = str_replace("@updated_datetime", $time, $strSQL);
        return $strSQL;
    }

    public function customValueIns($cvparam)
    {
        return parent::insert($this->customValueInsSql($cvparam));
    }

    public function customValueInsSql($cvparam)
    {
        $cv_table = $cvparam['cv_table'];
        $custom_values = $cvparam['custom_values'];
        $type = $cvparam['type'];
        $customized_id = $cvparam['customized_id'];
        $cris = $cvparam['cris'];
        $seq = $cvparam['seq'];
        $time = $cvparam['time'];

        $strSQL = "";
        $strSQL .= " INSERT INTO @cv_table " . "\r\n";
        $strSQL .= " ( " . "\r\n";
        $strSQL .= " customized_id " . "\r\n";
        $strSQL .= " ,custom_field_id " . "\r\n";
        $strSQL .= " ,value " . "\r\n";
        $strSQL .= " ,created_datetime " . "\r\n";
        $strSQL .= " ,updated_datetime " . "\r\n";
        $strSQL .= " ) " . "\r\n";
        $strSQL .= " VALUES " . "\r\n";
        if ($type == 'punish') {
            $strSQL .= "(" . $customized_id . "," . $cris[1] . ",'" . $seq . "','" . $time . "','" . $time . "'),";
        }
        foreach ($custom_values as $key => $value) {
            $strSQL .= "(" . $customized_id . "," . $key . ",'" . addslashes($value) . "','" . $time . "','" . $time . "'),";
        }
        ;
        $strSQL = substr($strSQL, 0, -1);

        $strSQL = str_replace("@cv_table", $cv_table, $strSQL);

        return $strSQL;
    }

    public function customValueSel($cv_table, $customized_id)
    {
        return parent::select($this->customValueSelSql($cv_table, $customized_id));
    }

    public function customValueSelSql($cv_table, $customized_id)
    {
        $strSQL = "";
        $strSQL .= " SELECT " . "\r\n";
        $strSQL .= " V.id " . "\r\n";
        $strSQL .= " ,F.name " . "\r\n";
        $strSQL .= " FROM " . "\r\n";
        $strSQL .= " @cv_table V " . "\r\n";
        $strSQL .= " LEFT JOIN ( " . "\r\n";
        $strSQL .= " SELECT " . "\r\n";
        $strSQL .= " id " . "\r\n";
        $strSQL .= " ,name " . "\r\n";
        $strSQL .= " FROM " . "\r\n";
        $strSQL .= " custom_fields ) F " . "\r\n";
        $strSQL .= " ON V.custom_field_id=F.id " . "\r\n";
        $strSQL .= " WHERE " . "\r\n";
        $strSQL .= " customized_id = @customized_id " . "\r\n";

        $strSQL = str_replace("@cv_table", $cv_table, $strSQL);
        $strSQL = str_replace("@customized_id", $customized_id, $strSQL);

        return $strSQL;
    }

    public function subUpd($stm, $employee_sub_table, $time)
    {
        return parent::update($this->subUpdSql($stm, $employee_sub_table, $time));
    }

    public function subUpdSql($stm, $employee_sub_table, $time)
    {

        $strSQL = "";
        $strSQL .= " UPDATE @stm" . "\r\n";
        $strSQL .= " SET updated_datetime = @time " . "\r\n";
        $strSQL .= " WHERE id = '@estid' " . "\r\n";

        $strSQL = str_replace("@stm", $stm, $strSQL);
        $strSQL = str_replace("@time", $time, $strSQL);
        $strSQL = str_replace("@estid", $employee_sub_table["estid"], $strSQL);

        return $strSQL;
    }

    public function customValueUpd($cvparam)
    {
        return parent::update($this->customValueUpdSql($cvparam));
    }

    public function customValueUpdSql($cvparam)
    {
        $cv_table = $cvparam['cv_table'];
        $custom_values = $cvparam['custom_values'];
        $customized_id = $cvparam['customized_id'];
        $time = $cvparam['time'];
        $strSQL = "";
        $strSQL .= "UPDATE @cv_table ";
        $strSQL .= " SET ";
        $strSQL .= " @cv_table.value = CASE @cv_table.custom_field_id ";
        foreach ($custom_values as $key => $value) {
            $strSQL .= " WHEN " . $key . " THEN '" . addslashes($value) . "'";
        }
        $strSQL .= " ELSE @cv_table.value ";
        $strSQL .= " END, ";
        $strSQL .= " @cv_table.updated_datetime = @time ";
        $strSQL .= " WHERE ";
        $strSQL .= " @cv_table.customized_id = @customized_id ";

        $strSQL = str_replace("@cv_table", $cv_table, $strSQL);
        $strSQL = str_replace("@time", $time, $strSQL);
        $strSQL = str_replace("@customized_id", $customized_id, $strSQL);
        return $strSQL;
    }

    public function cvdelete($cv_table, $delcustomized_ids)
    {
        return parent::delete($this->cvdeleteSql($cv_table, $delcustomized_ids));
    }

    public function cvdeleteSql($cv_table, $delcustomized_ids)
    {

        $strSQL = "";
        $strSQL .= " DELETE FROM @cv_table" . "\r\n";
        $strSQL .= " where customized_id in ( " . "\r\n";
        for ($i = 0; $i < count($delcustomized_ids); $i++) {
            if ($i == 0) {
                $strSQL .= " " . $delcustomized_ids[$i] . " " . "\r\n";
            } else {
                $strSQL .= " ," . $delcustomized_ids[$i] . " " . "\r\n";
            }
        }
        $strSQL .= " ) " . "\r\n";

        $strSQL = str_replace("@cv_table", $cv_table, $strSQL);

        return $strSQL;
    }

    public function stdelete($sub_table, $delcustomized_ids)
    {
        return parent::delete($this->stdeleteSql($sub_table, $delcustomized_ids));
    }

    public function stdeleteSql($sub_table, $delcustomized_ids)
    {
        $strSQL = "";
        $strSQL .= " DELETE FROM @sub_table" . "\r\n";
        $strSQL .= " WHERE id in ( " . "\r\n";
        for ($i = 0; $i < count($delcustomized_ids); $i++) {
            if ($i == 0) {
                $strSQL .= " " . $delcustomized_ids[$i] . " " . "\r\n";
            } else {
                $strSQL .= " ," . $delcustomized_ids[$i] . " " . "\r\n";
            }
        }
        $strSQL .= " ) " . "\r\n";

        $strSQL = str_replace("@sub_table", $sub_table, $strSQL);
        return $strSQL;
    }

    //社员权限取得
    public function getRoleData($empId)
    {
        return parent::select($this->getRoleDataSql($empId));
    }

    public function getRoleDataSql($empId)
    {
        $strSQL = "";
        $strSQL .= " SELECT " . "\r\n";
        $strSQL .= " sys_kb " . "\r\n";
        $strSQL .= " FROM " . "\r\n";
        $strSQL .= " m_login " . "\r\n";
        $strSQL .= " WHERE " . "\r\n";
        $strSQL .= " empId = '@empId' " . "\r\n";

        $strSQL = str_replace("@empId", $empId, $strSQL);

        return $strSQL;
    }

}
