<html>
    <head>
        <style type="text/css">
            a, a:link, a:active, a:visited {
                font: 14px/20px Cambria;
                color: #3C7AC0;
                text-decoration: none;
            }

            a:hover {
                font: 14px/20px Cambria;
                color: #3C7AC0;
                text-decoration: underline;
            }
        </style>
    </head>
    <body style="margin: 0px;">
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td align="center"><img src="{{ url('public/frontend/images/email-logo.jpg') }}" /></td>
            </tr>
            <tr>
                <td valign="top" style="font: 14px/20px Cambria;">
                    Dear Administrator,<br />
                    You have received a new email subscription on the website with the following details:<br /><br />
                    Email Address - <a href="mailto:$maildata[email_address]">{{ $client['email_address'] }}</a><br /><br />
                </td>
            </tr>
        </table>
    </body>
</html>