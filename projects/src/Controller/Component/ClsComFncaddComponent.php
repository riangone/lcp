<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;

class ClsComFncaddComponent extends Component
{
    public $ClsComFnc;

    public function __construct(ComponentRegistry $registry)
    {
        parent::__construct($registry);
        $this->ClsComFnc = $registry->load('ClsComFnc');
    }

    //**********************************************************************
    //処 理 名：R4/HiT4の住所CDの変換
    //関 数 名：fncChangeAddrCD
    //引    数：strAddrCD    (I)住所CD
    //戻 り 値：string:変換後住所CD
    //処理説明：市区郡ｺｰﾄﾞを3桁から2桁に変換します。
    //**********************************************************************
    public function fncChangeAddrCD(&$strAddrCD)
    {
        $strTodofuken = "";
        $strShikugun = "";
        $strChoson = "";
        $strkoaza = "";
        $strNewShikugun = "";
        $strNewCheck = "";

        $intWeit = "";
        $intShow = "";

        //R4の住所ｺｰﾄﾞ分割
        //都道府県CD
        $strAddrCD = $this->ClsComFnc->mb_str_pad($strAddrCD, 12);
        $strTodofuken = mb_substr($strAddrCD . str_repeat(" ", 12), 0, 2);
        //市区郡CD
        $strShikugun = mb_substr($strAddrCD . str_repeat(" ", 12), 2, 3);
        //町村字CD
        $strChoson = mb_substr($strAddrCD . str_repeat(" ", 12), 5, 3);
        //小字CD
        $strkoaza = mb_substr($strAddrCD . str_repeat(" ", 12), 9, 3);

        // '市区郡CD変換
        // 'If strShikugun.Trim <> "" Then
        // '    Select Case True
        // '        '埼玉県
        // '    Case strTodofuken = "11" And strShikugun = "543"
        // '            strNewShikugun = "26"
        // '        Case strTodofuken = "11" And strShikugun = "542"
        // '            strNewShikugun = "27"
        // '        Case strTodofuken = "11" And strShikugun = "541"
        // '            strNewShikugun = "28"
        // '        Case strTodofuken = "11" And strShikugun = "540"
        // '            strNewShikugun = "29"
        // '            '神奈川県
        // '        Case strTodofuken = "14"
        // '            If IsNumeric(strShikugun.Substring(1, 2)) = True And _
        // '                CInt(strShikugun.Substring(1, 2)) >= 30 Then
        // '                strNewShikugun = (CInt(strShikugun.Substring(1, 2)) - 30).ToString
        // '            Else
        // '                strNewShikugun = Space(2)
        // '            End If
        // '            '福岡県
        // '        Case strTodofuken = "40"
        // '            If IsNumeric(strShikugun.Substring(1, 2)) = True And _
        // '                CInt(strShikugun.Substring(1, 2)) >= 40 Then
        // '                strNewShikugun = (CInt(strShikugun.Substring(1, 2)) - 40).ToString
        // '            ElseIf IsNumeric(strShikugun.Substring(1, 2)) = True Then
        // '                strNewShikugun = strShikugun.Substring(1, 2)
        // '            Else
        // '                strNewShikugun = Space(2)
        // '            End If
        // '        Case Else
        // '            If strTodofuken.Trim <> "" Then
        // '                If IsNumeric(strShikugun) Then
        // '                    If strShikugun.Substring(0, 1) = "0" Then
        // '                        strNewShikugun = strShikugun.Substring(1, 2)
        // '                    ElseIf strShikugun.Substring(0, 1) = "5" And _
        // '                           CInt(strShikugun) >= 470 Then
        // '                        strNewShikugun = (CInt(strShikugun) - CInt(470)).ToString
        // '                    ElseIf strShikugun.Substring(0, 1) = "8" And _
        // '                        CInt(strShikugun) >= 730 Then
        // '                        strNewShikugun = (CInt(strShikugun) - CInt(730)).ToString
        // '                    Else
        // '                        strNewShikugun = strShikugun.Substring(1, 2)
        // '                    End If
        // '                Else
        // '                    strNewShikugun = Space(2)
        // '                End If
        // '            End If
        // '    End Select
        // 'Else
        // '    strNewShikugun = Space(2)
        // 'End If

        if (rtrim($strShikugun) != "") {
            //埼玉県
            if ($strTodofuken == "11" && $strShikugun == "543") {
                $strNewShikugun = "26";
            } elseif ($strTodofuken == "11" && $strShikugun == "542") {
                $strNewShikugun = "27";
            } elseif ($strTodofuken == "11" && $strShikugun == "541") {
                $strNewShikugun = "28";
            } elseif ($strTodofuken == "11" && $strShikugun == "540") {
                $strNewShikugun = "29";
                //神奈川県
            } elseif ($strTodofuken == "14" && $strShikugun >= "050" && $strShikugun <= "099") {
                $strNewShikugun = (int) substr($strShikugun, 1, 2) - 30;
                //福岡県
            } elseif ($strTodofuken == "40" && $strShikugun >= "050" && $strShikugun <= "099") {
                $strNewShikugun = (int) substr($strShikugun, 1, 2) - 40;
            } else {
                if ($strShikugun >= "001" && $strShikugun <= "029") {
                    $strNewShikugun = substr($strShikugun, 1, 2);
                } elseif ($strShikugun >= "500" && $strShikugun <= "539") {
                    $strNewShikugun = (int) $strShikugun - 470;
                } elseif ($strShikugun >= "800" && $strShikugun <= "829") {
                    $strNewShikugun = (int) $strShikugun - 730;
                } else {
                    $strNewShikugun = substr($strShikugun, 1, 2);
                }
            }
        } else {
            $strNewShikugun = str_repeat(" ", 2);
        }

        if (rtrim($strNewShikugun) == "") {
            $strNewCheck = "";
            //2009/10/02 UPDATE Start
            //'''Else
        } elseif ($this->ClsComFnc->StringLength(rtrim($strNewShikugun)) != 12) {
            $strNewCheck = "";
        } else {
            //2009/10/02 UPDATE end
            $intWeit = (int) (substr($strTodofuken, 0, 1)) * 9 + (int) (substr($strTodofuken, 1, 1)) * 8 + (int) (substr($strNewShikugun, 0, 1)) * 7 + (int) (substr($strNewShikugun, 1, 1)) * 6 + (int) (substr($strChoson, 0, 1)) * 4 + (int) (substr($strChoson, 1, 1)) * 3 + (int) (substr($strChoson, 2, 1)) * 2;

            $intShow = floor($intWeit / 11);
            $strNewCheck = 11 - ($intWeit - $intShow * 11);
            if ((int) $strNewCheck > 9) {
                //                    strNewCheck = "0"
                $strNewCheck = (int) $strNewCheck - 10;
            }
        }

        return mb_substr($strTodofuken . str_repeat(" ", 2), 0, 2) . mb_substr($strNewShikugun . str_repeat(" ", 2), 0, 2) . mb_substr($strChoson . str_repeat(" ", 2), 0, 3) . mb_substr($strNewCheck . str_repeat(" ", 1), 0, 1) . mb_substr($strkoaza . str_repeat(" ", 3), 0, 3);

    }

    //**********************************************************************
    //処 理 名：R4/HiT4の注文書NOの変換
    //関 数 名：fncChangeCmnNO
    //引    数：strChumonNO    (I)注文書№
    //　    　　strDairitenKbn (I)業販店コード
    //          strHanbaiKbn   (I)販売区分
    //戻 り 値：true:正常終了 false:異常終了
    //処理説明：注文書ＮｏをＲ４からＲ２対応に変換します。
    //**********************************************************************
    public function fncChangeCmnNO(&$strChumonNO, &$strDairitnCD, &$strHanbaiKbn)
    {
        //Dim objDr As OracleDataReader      'ﾃﾞｰﾀﾘｰﾀﾞ
        //Dim strSQL As String
        $strBusyoCD = "";
        $strKbn = "";
        //Dim strDairitnCD As String
        //Dim strHanbaiKbn As String
        $strOiban = "";

        $strCnvChumonNO = "";
        //Dim strNewCmnNO As String

        // ''注文書履歴ﾌｧｲﾙより販売区分、業販店ｺｰﾄﾞを取得
        // 'strSQL = ""
        // 'strSQL = strSQL & " SELECT DAIRITN_CD" & vbCrLf        '業販店コード
        // 'strSQL = strSQL & "       ,HNB_KB" & vbCrLf            '販売区分
        // 'strSQL = strSQL & "   FROM M41E10" & vbCrLf
        // 'strSQL = strSQL & "  WHERE CMN_NO = '" & strChumonNO & "'" & vbCrLf
        // ''データリーダに格納
        // 'objDr = ClsComOdp.Fnc_DataReader(strSQL)
        //
        // 'If Not objDr.HasRows Then
        // '    '存在しない場合　注文書履歴ﾌｧｲﾙより取得する
        // '    strSQL = ""
        // '    strSQL = strSQL & " SELECT DAIRITN_CD" & vbCrLf        '業販店コード
        // '    strSQL = strSQL & "       ,HNB_KB" & vbCrLf            '販売区分
        // '    strSQL = strSQL & "  FROM M41E15　M_RIR" & vbCrLf
        // '    strSQL = strSQL & "     ,(SELECT CMN_NO,MAX(RIR_NO) RIR_NO FROM M41E15 B GROUP BY CMN_NO) V" & vbCrLf
        // '    strSQL = strSQL & " WHERE M_RIR.CMN_NO = V.CMN_NO" & vbCrLf
        // '    strSQL = strSQL & "   AND M_RIR.RIR_NO = V.RIR_NO" & vbCrLf
        // '    strSQL = strSQL & "   AND M_RIR.CMN_NO = '" & strChumonNO & "'" & vbCrLf
        // '    'データリーダに格納
        // '    objDr = ClsComOdp.Fnc_DataReader(strSQL)
        // 'End If
        //
        // 'strDairitnCD = ""
        // 'strHanbaiKbn = ""
        // 'If objDr.HasRows Then
        // '    '格納する
        // '    objDr.Read()
        // '    strDairitnCD = clsComFnc.FncNv(objDr("DAIRITN_CD").ToString.TrimEnd)
        // '    strHanbaiKbn = clsComFnc.FncNv(objDr("HNB_KB").ToString.TrimEnd)
        // 'End If
        // ''データリーダの解放
        // 'objDr.Close()

        //R4の注文書No.を部署(1～3)、区分(4)、追番(5～10)に分ける
        $strChumonNO = $this->ClsComFnc->mb_str_pad($strChumonNO, 10);
        $strBusyoCD = mb_substr($strChumonNO, 0, 3);
        $strKbn = mb_substr($strChumonNO, 3, 1);
        $strOiban = mb_substr($strChumonNO, 5, 5);

        $strCnvChumonNO = "";

        //区分を変換
        switch ($strKbn) {
            case 'N':
                if ($strDairitnCD == "") {
                    $strCnvChumonNO = 1;
                } else {
                    $strCnvChumonNO = 2;
                }
                break;
            case 'U':
                switch ($strHanbaiKbn) {
                    case '1':
                    case '2':
                    case '7':
                        $strCnvChumonNO = 5;
                        break;
                    case '3':
                    case '4':
                    case '5':
                    case '6':
                    case '9':
                        $strCnvChumonNO = 6;
                        break;
                    default:
                        return FALSE;
                    //20240425 lujunxia PHP8 del s
                    //break;
                    //20240425 lujunxia PHP8 del e
                }
                break;
            default:
                return FALSE;
            //20240425 lujunxia PHP8 del s
            //break;
            //20240425 lujunxia PHP8 del e
        }
        $strCnvChumonNO = $strCnvChumonNO . "-";

        //部署コードを変換
        switch ($strBusyoCD) {
            case "181":
                $strCnvChumonNO = $strCnvChumonNO . "1";
                break;
            case "211":
                switch ($strKbn) {
                    case "N":
                        return FALSE;
                    case "U":
                        if ($strHanbaiKbn == "7") {
                            $strCnvChumonNO = $strCnvChumonNO . "Z";
                        } else {
                            $strCnvChumonNO = $strCnvChumonNO . "0";
                        }
                        break;
                }
                break;
            case "224":
                switch ($strKbn) {
                    case "N":
                        return FALSE;
                    case "U":
                        $strCnvChumonNO = $strCnvChumonNO . "4";
                        break;
                }
                break;
            case "231":
                switch ($strKbn) {
                    case "N":
                        return FALSE;
                    case "U":
                        $strCnvChumonNO = $strCnvChumonNO . "5";
                        break;
                }
                break;
            case "232":
                switch ($strKbn) {
                    case "N":
                        return FALSE;
                    case "U":
                        $strCnvChumonNO = $strCnvChumonNO . "7";
                        break;
                }
                break;
            //2013/12/20 修正 START
            //Case "291"
            case "271":
            case "291":
                //2013/12/20 修正 END
                $strCnvChumonNO = $strCnvChumonNO . "8";
                break;
            case "191":
                $strCnvChumonNO = $strCnvChumonNO . "9";
                break;
            case "321":
                $strCnvChumonNO = $strCnvChumonNO . "A";
                break;
            case "331":
                $strCnvChumonNO = $strCnvChumonNO . "B";
                break;
            case "361":
                $strCnvChumonNO = $strCnvChumonNO . "C";
                break;
            case "381":
                $strCnvChumonNO = $strCnvChumonNO . "D";
                break;
            case "391":
                $strCnvChumonNO = $strCnvChumonNO . "E";
                break;
            case "411":
                $strCnvChumonNO = $strCnvChumonNO . "F";
                break;
            case "421":
                $strCnvChumonNO = $strCnvChumonNO . "G";
                break;
            case "431":
                $strCnvChumonNO = $strCnvChumonNO . "H";
                break;
            case "441":
                $strCnvChumonNO = $strCnvChumonNO . "J";
                break;
            case "443":
                $strCnvChumonNO = $strCnvChumonNO . "K";
                break;
            case "461":
                $strCnvChumonNO = $strCnvChumonNO . "L";
                break;
            case "471":
                $strCnvChumonNO = $strCnvChumonNO . "M";
                break;
            case "511":
                $strCnvChumonNO = $strCnvChumonNO . "N";
                break;
            case "521":
                $strCnvChumonNO = $strCnvChumonNO . "P";
                break;
            case "551":
                $strCnvChumonNO = $strCnvChumonNO . "Q";
                break;
            case "631":
                $strCnvChumonNO = $strCnvChumonNO . "R";
                break;
            case "661":
                $strCnvChumonNO = $strCnvChumonNO . "U";
                break;
            case "166":
                $strCnvChumonNO = $strCnvChumonNO . "W";
                break;
            case "169":
                $strCnvChumonNO = $strCnvChumonNO . "X";
                break;
            case "161":
                $strCnvChumonNO = $strCnvChumonNO . "Y";
                break;
            case "013":
                $strCnvChumonNO = $strCnvChumonNO . "Z";
                break;
            default:
                return FALSE;
        }

        //追番をセットする
        $strChumonNO = $strCnvChumonNO . $strOiban;
        //正常終了
        return TRUE;
    }

}