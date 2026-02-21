<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/KRSS/FrmYosanList/FrmYosanList"));
?>

<div class='KRSS FrmYosanList_KRSS' id="KRSS_FrmYosanList_KRSS" style="width:100%;height:100%">
    <div class='KRSS FrmYosanList_KRSS R4-content'>
        <fieldset>
            <legend>
                予算表取込
            </legend>

            <table width=100%>
                <tr height="50">
                    <td width='150'><span>取込ファイル</span></td>
                    <td width='300' align="left">
                        <!--<input type='file' class='KRSS FrmYosanList_KRSS file1 Tab Enter'  name="name_FrmYosanList_KRSS_File1" id="id_FrmYosanList_KRSS_File1"/>-->
                        <input type='text' class="KRSS FrmYosanList_KRSS file1Text" style='width:200px'
                            disabled="disabled" />
                        <button class="KRSS FrmYosanList_KRSS cmd001 Tab Enter">
                            参照
                        </button>
                    </td>
                    <td>

                    </td>
                </tr>
                <tr height='50'>
                    <td width='150'><span> 期 </span></td>
                    <td>
                        <input type='number' class='KRSS FrmYosanList_KRSS KI Tab Enter' id="id_FrmYosanList_KRSS_KI"
                            style='width:50px' maxlength="3" size="3" max="110" min="0" />
                        <button class='KRSS FrmYosanList_KRSS cmd002 Tab Enter'>
                            登録
                        </button>
                    </td>
                    <td>
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset>
            <legend>
                実績集計表出力
            </legend>
            <table width=100%>
                <tr height="50">
                    <td width='150'><span>対象年月</span></td>
                    <td width='300'>
                        <input type="input" class="KRSS FrmYosanList_KRSS cboYM Tab Enter" style="width:70px" />
                        <button class='KRSS FrmYosanList_KRSS cmd004 Tab Enter'>
                            出力
                        </button>
                    </td>
                    <td>
                    </td>
                </tr>
            </table>
        </fieldset>
        <table width=100%>
            <tr height='50'>
                <td colspan="2">
                    <div class="HMS-button-pane" style="margin-top: 20px">
                        <div class="HMS-button-set">

                            <button class='KRSS FrmYosanList_KRSS btn_cancel Tab Enter'>
                                キャンセル
                            </button>

                        </div>
                    </div>
                </td>
            </tr>
        </table>

    </div>
</div>
<div id="tmpFileUpload"></div>
