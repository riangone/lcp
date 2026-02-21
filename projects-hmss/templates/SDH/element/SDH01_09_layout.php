<!-- /**
* 説明：
*
*
* @author fuxiaolin
* @copyright (GD) (ZM)
* @package default
*
* 履歴：
* --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20150526           ---                       新規                           FCSDL
* --------------------------------------------------------------------------------------------
*/ -->

<div class="sdh sdh09 dialog" style="width: 100%;height: 100%;font-size: 10pt">
    <div>
        <fieldset>
            <legend>
                ◆集計情報
            </legend>
            <table>
                <tr>
                    <td width="100px">
                        <div class="sdh sdh09 label">
                            <label for=""> 年月: </label>
                        </div>
                    </td>
                    <td width="130px">
                        <div class="sdh sdh09 nengetu">

                        </div>
                    </td>
                    <td width="100px">
                        <div class="sdh sdh09 label">
                            <label for=""> 担当者: </label>
                        </div>
                    </td>
                    <td width="130px">
                        <div class="sdh sdh09 tantocd">

                        </div>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
    <div>
        <table>
            <tr>
                <td style=" vertical-align: top; ">
                    <fieldset>
                        <legend>
                            ◆活動状況
                        </legend>
                        <table border="0">
                            <tr>
                                <td width="160px"></td>
                                <td width="110px">
                                    <div class="sdh sdh09 09hanteinengetu_01 label"></div>
                                </td>
                                <td width="110px">
                                    <div class="sdh sdh09 09hanteinengetu_02 label"></div>
                                </td>
                                <td width="110px">
                                    <div class="sdh sdh09 09hanteinengetu_03 label"></div>
                                </td>
                                <td width="110px">
                                    <div class="sdh sdh09 09hanteinengetu_04 label"></div>
                                </td>
                                <td width="110px">
                                    <div class="sdh sdh09 09hanteinengetu_05 label"></div>
                                </td>
                                <td width="110px">
                                    <div class="sdh sdh09 09hanteinengetu_06 label"></div>
                                </td>
                                <td width="110px">
                                    <div class="sdh sdh09 09hanteinengetu_07 label"></div>
                                </td>
                            </tr>

                        </table>
                        <div id="getAccDetailsTable"></div>
                        <!-- </hr> -->
                        <div style="border-bottom: 1px grey solid;"></div>
                        <div id="getAccTotal"></div>
                    </fieldset>
                </td>
                <td style=" vertical-align: top; ">
                    <fieldset>
                        <legend>
                            ◆最終結果
                        </legend>
                        <table>
                            <tr>
                                <td width="150px"></td>
                                <td width="110px">
                                    <div class="sdh sdh09 09hanteinengetu_saisyu label">
                                </td>
                            </tr>
                        </table>
                        <div id="getAccDetailsSaisyuTable"></div>
                        <div style="border-bottom: 1px grey solid;width: 99%"></div>
                        <div id="getAccTotal1"></div>
                    </fieldset>
                </td>
            </tr>
        </table>

    </div>
</div>