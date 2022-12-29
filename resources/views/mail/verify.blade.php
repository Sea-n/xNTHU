<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" />
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
  <head> </head>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="x-apple-disable-message-reformatting" />
    <!--[if !mso]><!-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!--<![endif]-->
    <style type="text/css">
      * {
        text-size-adjust: 100%;
        -ms-text-size-adjust: 100%;
        -moz-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%;
      }

      html {
        height: 100%;
        width: 100%;
      }

      body {
        height: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        mso-line-height-rule: exactly;
      }

      div[style*="margin: 16px 0"] {
        margin: 0 !important;
      }

      table,
      td {
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
      }

      img {
        border: 0;
        height: auto;
        line-height: 100%;
        outline: none;
        text-decoration: none;
        -ms-interpolation-mode: bicubic;
      }

      .ReadMsgBody,
      .ExternalClass {
        width: 100%;
      }

      .ExternalClass,
      .ExternalClass p,
      .ExternalClass span,
      .ExternalClass td,
      .ExternalClass div {
        line-height: 100%;
      }
    </style>
    <!--[if gte mso 9]>
      <style type="text/css">
      li { text-indent: -1em; }
      table td { border-collapse: collapse; }
      </style>
      <![endif]-->
    <title> </title>
    <!-- content -->
    <!--[if gte mso 9]><xml>
       <o:OfficeDocumentSettings>
        <o:AllowPNG/>
        <o:PixelsPerInch>96</o:PixelsPerInch>
       </o:OfficeDocumentSettings>
      </xml><![endif]-->
  </head>
  <body class="body" style="margin: 0; width: 100%;">
    <table class="bodyTable" role="presentation" width="100%" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin: 0;">
      <tr>
        <td class="body__content" align="left" width="100%" valign="top" style="color: #000000; font-family: Helvetica,Arial,sans-serif; font-size: 16px; line-height: 20px; color: #333333; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; line-height: 1.5;">
          <div class="main" style="margin: 0 auto; max-width: 600px;">
            <p class="text p" style="display: block; margin: 14px 0; color: #000000; font-family: Helvetica,Arial,sans-serif; font-size: 16px; line-height: 20px; color: #333333; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; line-height: 1.5;">{{ $google->name }} 您好，</p>
            <p class="text p" style="display: block; margin: 14px 0; color: #000000; font-family: Helvetica,Arial,sans-serif; font-size: 16px; line-height: 20px; color: #333333; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; line-height: 1.5;">感謝您註冊 <a href="{{ url('/') }}" class="a" style="color: #108EE9; text-decoration: none;"><span class="a__text" style="color: #108EE9; text-decoration: none;">{{ env('APP_CHINESE_NAME') }}</span></a>，請點擊下方連結啟用帳號：<br/> <span style="font-size: 12px;"><a href="{{ $verify_link }}" class="a" style="color: #108EE9; text-decoration: none;"><span class="a__text" style="color: #108EE9; text-decoration: none;">{{ $verify_link }}</span></a>
              </span>
            </p>
            <p class="text p" style="display: block; margin: 14px 0; color: #000000; font-family: Helvetica,Arial,sans-serif; font-size: 16px; line-height: 20px; color: #333333; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; line-height: 1.5;">為了維持更新頻率，{{ env('APP_CHINESE_NAME') }} 將審核工作下放至全體師生，因此您的每一票對我們來說都相當重要。<br/> 雖然所有審核者皆為自由心證，未經過訓練也不強制遵從統一標準；但透過保留所有審核紀錄、被駁回的投稿皆提供全校師生檢視，增加審核標準的透明度。</p>
            <p class="text p" style="display: block; margin: 14px 0; color: #000000; font-family: Helvetica,Arial,sans-serif; font-size: 16px; line-height: 20px; color: #333333; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; line-height: 1.5;">有了您的貢獻，期望能以嶄新的姿態，將{{ env('APP_CHINESE_NAME') }} 推向靠北生態系巔峰。</p>
            <p class="text p" style="display: block; margin: 14px 0; color: #000000; font-family: Helvetica,Arial,sans-serif; font-size: 16px; line-height: 20px; color: #333333; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; line-height: 1.5; text-align: right;">{{ env('HASHTAG') }}維護團隊<br/>{{ $date }}</p>
            <p class="text p" style="display: block; margin: 14px 0; font-family: Helvetica,Arial,sans-serif; line-height: 20px; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; line-height: 1.5; color: #888888; font-size: 10px; text-align: center;">
            由於 <a href="mailto:{{ $google->email }}" class="a" style="color: #108EE9; text-decoration: none;"><span class="a__text" style="color: #108EE9; text-decoration: none;">{{ $google->name }} &lt;{{ $google->email }}&gt;</span></a> 在{{ env('APP_CHINESE_NAME') }} 網站申請寄送驗證碼，因此寄發本信件給您。
              （來自「{{ $ip_from }}」，IP 位址為 <code>{{ $ip_addr }}</code>） 如不是由您本人註冊，很可能是同學手滑打錯學號了，請不要點擊驗證按鈕以避免爭議。 若是未來不想再收到相關信件，請來信 <a href="mailto:{{ env('MAIL_FROM_ADDRESS') }}" class="a" style="color: #108EE9; text-decoration: none;"><span class="a__text" style="color: #108EE9; text-decoration: none;">與我們聯絡</span></a>，將會盡快將您的學號放入拒收清單內。
              </p>
          </div>
        </td>
      </tr>
    </table>
    <div style="display:none; white-space:nowrap; font-size:15px; line-height:0;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </div>
  </body>
</html>
