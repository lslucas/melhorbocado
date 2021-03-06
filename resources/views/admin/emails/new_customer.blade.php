<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Olá, {{ $to_name }}</title>
  <style type="text/css">
    /* Based on The MailChimp Reset INLINE: Yes. */
    /* Client-specific Styles */
    #outlook a {padding:0;} /* Force Outlook to provide a "view in browser" menu link. */
    body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0;}
    /* Prevent Webkit and Windows Mobile platforms from changing default font sizes.*/
    .ExternalClass {width:100%;} /* Force Hotmail to display emails at full width */
    .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;}
    /* Forces Hotmail to display normal line spacing.  More on that: http://www.emailonacid.com/forum/viewthread/43/ */
    #backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}
    /* End reset */

    /* Some sensible defaults for images
    Bring inline: Yes. */
    img {outline:none; text-decoration:none; -ms-interpolation-mode: bicubic;}
    a img {border:none;}
    .image_fix {display:block;}

    /* Yahoo paragraph fix
    Bring inline: Yes. */
    p {margin: 1em 0;}

    /* Hotmail header color reset
    Bring inline: Yes. */
    h1, h2, h3, h4, h5, h6 {color: black !important;}

    h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {color: blue !important;}

    h1 a:active, h2 a:active,  h3 a:active, h4 a:active, h5 a:active, h6 a:active {
    color: red !important; /* Preferably not the same color as the normal header link color.  There is limited support for psuedo classes in email clients, this was added just for good measure. */
    }

    h1 a:visited, h2 a:visited,  h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited {
    color: purple !important; /* Preferably not the same color as the normal header link color. There is limited support for psuedo classes in email clients, this was added just for good measure. */
    }

    /* Outlook 07, 10 Padding issue fix
    Bring inline: No.*/
    table td {border-collapse: collapse;}

    /* Remove spacing around Outlook 07, 10 tables
    Bring inline: Yes */
    table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }

    /* Styling your links has become much simpler with the new Yahoo.  In fact, it falls in line with the main credo of styling in email and make sure to bring your styles inline.  Your link colors will be uniform across clients when brought inline.
    Bring inline: Yes. */
    a {color: orange;}

  </style>

</head>
<body>
<!-- Wrapper/Container Table: Use a wrapper table to control the width and the background color consistently of your email. Use this approach instead of setting attributes on the body tag. -->
<table cellpadding="0" cellspacing="0" border="0" id="backgroundTable" style="background-color: #3e3e42;">
  <tr>
    <td valign="top">
    <!-- Tables are the most common way to format your email consistently. Set your table widths inside cells and in most cases reset cellpadding, cellspacing, and border to zero. Use nested tables as a way to space effectively in your message. -->
    <table cellpadding="0" cellspacing="0" border="0" align="center" style="font-family: Gotham, Verdana, Geneva, sans-serif;">
      <tr>
        <td valign="top">

          <table cellpadding="0" cellspacing="0" border="0" align="center" style="background-color: #6dc9b9; color: #fff;">
            <tr>
              <td width="610" style="padding: 20px;">

                <table cellpadding="0" cellspacing="0" border="0" align="center">
                  <tr>
                    <td width="311">
                        <img class="image_fix" src="{{ Config::get('app.url') }}/assets/images/layout/email/message-header.png" alt="" width="311" height="118" />
                    </td>
                  </tr>
                </table>

                <p style="margin: 2em 0; text-align: center; font-size: 27px;">Olá, <b style="font-weight: bold;">{{ $to_name }}</b></p>

                <div style="font-size: 16px; line-height: 1.2;">

                  <p style="margin: 1.3em 0;">
                      O cliente #{{ $cliente_id }} foi vinculado ao seu cadastro no {{ \Config::get('app.name') }}!
                  </p>

                  <p style="margin: 1.3em 0;">
                    <br/><br/>Veja mais detalhes sobre o cliente fazendo seu login em <a href='{{ \Config::get('app.url') }}/admin' title='Logar no {{ Config::get('app.name') }}'>{{ \Config::get('app.url') }}/admin</a>
                  </p>

                </div>

              </td>
            </tr>
          </table>

        </td>
      </tr>
      <tr>
        <td width="650" valign="top">
          <img class="image_fix" src="{{ Config::get('app.url') }}/assets/images/layout/email/message-footer.png" alt="" width="650" height="34" />
        </td>
      </tr>
    </table>
    <!-- End example table -->

    </td>
  </tr>
</table>
<!-- End of wrapper table -->
</body>
</html> 
