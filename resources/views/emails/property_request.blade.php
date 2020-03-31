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
                <td valign="top" style="font: 14px/20px Cambria;">
                    Dear {{ $first_name }} {{ $last_name }},<br />
                    You have received a property inquiry request on {{ $property_title }} from Luxury Real Estate Search with the following details:<br /><br />
                    First Name - {{ $client['first_name'] }} <br />
                    Last Name - {{ $client['last_name'] }} <br />
                    Email Address - <a href="mailto:{{ $client['email_address'] }}">{{ $client['email_address'] }}</a><br />
                    Phone - {{ $client['phone'] }}<br />
                    Requested Showing Day & Time - {{ $client['day'] }} at {{ $client['time'] }}<br />
                    Message - {{ $client['message'] }}<br /><br />
                </td>
            </tr>
        </table>
    </body>
</html>