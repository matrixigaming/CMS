@if($is_admin == 1)
<html>
    <head>
        <style type="text/css">
            a, a:link, a:active, a:visited { font: 14px/20px Cambria; color: #3C7AC0; text-decoration: none; }
            a:hover { font: 14px/20px Cambria; color: #3C7AC0; text-decoration: underline; }
        </style>
    </head>
    <body style="margin: 0px;">
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td valign="top" style="font: 14px/20px Cambria;">
                    Dear Administrator,<br />
                    You have received a contact request on the website with the following details:<br /><br />
                    First Name - {{ $client['first_name'] }} <br />
                    Last Name - {{ $client['last_name'] }} <br />
                    Email Address - <a href="mailto:{{ $client['email_address'] }}">{{ $client['email_address'] }}</a><br />
                    Phone - {{ $client['phone'] }} <br />
                    Looking For - {{ $client['looking_to'] }} <br />
                    Contact Method - {{ $client['contact_method'] }} <br />
                    Message - {{ $client['message'] }} <br /><br />
                </td>
            </tr>
        </table>
    </body>
</html>
@endif

@if($is_admin == 0)
<html>
    <head>
        <style type="text/css">
        a, a:link, a:active, a:visited { font: 14px/20px Cambria; color: #3C7AC0; text-decoration: none; }
        a:hover { font: 14px/20px Cambria; color: #3C7AC0; text-decoration: underline; }
        </style>
    </head>
    <body style="margin: 0px;">
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td valign="top" style="font: 14px/20px Cambria;">
                    Dear {{ $user['first_name'] }} {{ $user['last_name'] }}<br />
                    You have received a contact request on www.luxuryrealestatesearch.com with the following details:<br /><br />
                    First Name - {{ $client['first_name'] }}<br />
                    Last Name - {{ $client['last_name'] }}<br />
                    Email Address - <a href="mailto:{{ $client['email_address'] }}">{{ $client['email_address'] }}</a><br />
                    Phone - {{ $client['phone'] }}<br />
                    Looking For - {{ $client['looking_to'] }}<br />
                    Contact Method - {{ $client['contact_method'] }}<br />
                    Message - {{ $client['message'] }}<br /><br />
                    Regards,<br />
                    LRES Team
                </td>
            </tr>
        </table>
    </body>
</html>
@endif