<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Navitas Digital Food Safety</title>
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Lora|Oswald" media="screen">
</head>
<body style="margin: 0;overflow: hidden;" bgcolor="#fff">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td>
            <table  width="950" border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" align="center">
                <tr>
                    <td><table width="20%" style="float: right;" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="display: none" width="61"></td>
                                <td width="200">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td height="46" align="right" valign="middle"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tr style="margin-right: 10px">
                                                        <td style="padding:5px;" width="5%" align="right"> <a href="https://www.facebook.com/HelloNavitas" target="_blank"><img src="{{URL::to('/assets/images/fb.png')}}" alt="facebook" width="18" height="19" border="0"></a> </td>
                                                        <td style="padding:5px;" width="3%" align="right"> <a href="@yield('forward-link', '#')" target="_blank"><img src="{{URL::to('/assets/images/forward.png')}}" alt="forward" width="19" height="19" border="0"></a> </td>
                                                        <td style="padding:5px;" width="5%" align="right">&nbsp;</td>
                                                    </tr>
                                                </table></td>
                                        </tr>
                                        <tr style="display: none;">
                                            <td height="30"></td>
                                        </tr>
                                    </table></td>
                            </tr>
                        </table></td>
                </tr>
                <tr>
                    <td align="center"><img src="{{URL::to('/assets/images/navitasEmailBG.jpg')}}" alt="" width="950" height="247" border="0"/></td>
                </tr>
                <tr>
                    <td align="center" valign="middle"><table width="100%" border="0" cellspacing="0" cellpadding="0">

                        </table></td>
                </tr>
                <tr style="display: none">
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr height="200" style="background-color: #424242;">
                                <td width="5%">&nbsp;</td>
                                <td width="90%" align="left" valign="top" style="padding: 10px 0;">
                                    @yield('content')
                                <td width="5%">&nbsp;</td>
                            </tr>
                        </table></td>
                </tr>
                <tr style="display: none;">
                    <td></td>
                </tr>
                <tr>
                </tr>
                <tr>
                    <td valign="top" style="padding: 46px 0;"  align="left">
                        <font style="color:#424242; font-family:'Myriad Pro', Helvetica, Arial, sans-serif; color:white; font-size:12px">
                            <strong style="padding: 20px;color:  #ff9642">
                                You have been sent this email as a valued client of Navitas system
                            </strong>
                        </font>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>