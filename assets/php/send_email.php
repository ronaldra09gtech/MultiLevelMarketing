<?php

require "PHPMailer/PHPMailerAutoload.php";


/*  
    1. Login to your Google account and go to the Security page.
       Scroll down to the Less secure app access section. 
        ---------- OR ------
        Go to https://myaccount.google.com/u/0/lesssecureapps
    2. Turn Off 2 Step Verification if enabled otherwise you can't see 'Turn on Less secure app' option
    3. Turn on Less secure app access. 
*/

$regards = $web_name;

function send_test_email($receiver_email)
{
  global $regards;
    $subject = "Email testing";
    $body_heading = 'This is a testing email.';
    $body_content = '<p style="font-size:16px;">Hey, we received a request to send testing email.</p>
                                      <p style="font-size:16px;">Email service is working fine!</p>  
                                      <p style="font-size: 14px;">Didn’t request it? You can ignore this message.</p>
                                      <p style="font-size: 14px;">Sincerely,<br/>'.$regards.' </p>';
    $action = send_email($receiver_email, $subject, $body_heading, $body_content);
    if ($action) {
        return true;
    } else {
        return false;
    }
}

function send_reset_password_successfull_email($user_id, $receiver_email)
{
  global $regards;
  $name = user_name($user_id);
  $subject = "Password reset successfully";
  $body_heading = 'Your password has reset successfully';
  $body_content = '<p style="font-size:16px;">Hi '.$name.',</p>
                        <p style="font-size:16px;">Your password has successfully reset.</p>
                        <p style="font-size:16px;">Thanks,<br>
                        '.$regards.'</p> ';
  send_email($receiver_email, $subject, $body_heading, $body_content);
}

function send_change_password_successfull_email($user_id)
{
  global $regards;
  $receiver_email = user_email($user_id);
  $name = user_name($user_id);
  $subject = "Password changed successfully";
  $body_heading = 'Your password has changed successfully';
  $body_content = '<p style="font-size:16px;">Hi '.$name.',</p>
                        <p style="font-size:16px;">Your password has successfully changed.</p>
                        <p style="font-size:16px;">Thanks,<br>
                        '.$regards.'</p> ';
  send_email($receiver_email, $subject, $body_heading, $body_content);
}

function send_registration_otp($receiver_email, $otp)
{
  global $regards;
  $subject = 'Otp validatation';
  $body_heading = "You're almost there! Just validate otp";
  $body_content = '<p style="font-size:16px;">Hi,</p>
                        <p style="font-size:16px;">Your otp is:- '.$otp.' </p>
                        <p style="font-size:16px;">The otp will expire in 15 minutes. </p>
                        <p style="font-size:16px;">Thanks,<br>
                        '.$regards.'</p>';
  $action = send_email($receiver_email, $subject, $body_heading, $body_content);
  if ($action) {
    return true;
  } else {
    return false;
  }
}


function send_forgot_password_otp($user_id, $receiver_email, $otp)
{
  global $regards;
  $name = user_name($user_id);
  $subject = 'Reset Your Account Password';
  $body_heading = 'Reset Your Account Password';
  $body_content = '<p style="font-size:16px;">Hi '.$name.',</p>
                        <p style="font-size:16px;">We heard that you lost your password. Sorry about that !</p>
                       <p style="font-size:16px;">Your otp is:- '.$otp.' </p>
                        <p style="font-size:16px;">The otp will expire in 15 minutes.</p>
                        <p style="font-size:16px;">Thanks,<br>
                       '.$regards.'</p>';
  $action = send_email($receiver_email, $subject, $body_heading, $body_content);
  if ($action) {
    return true;
  } else {
    return false;
  }
}

function send_email($receiver_email, $subject, $email_heading, $email_body_content)
{
  global $conn;
  global $web_name;
  global $regards;
  global $base_url;
  global $setting_tbl;



  $body = '<body style="background-color: #cfd6f4; margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;">
  <table border="0" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #cfd6f4;" width="100%">
    <tbody>
      <tr>
        <td>
          <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-1" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #cfd6f4;" width="100%">
            <tbody>
              <tr>
                <td>
                  <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000;" width="600">
                    <tbody>
                      <tr>
                        <td class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 20px; padding-bottom: 0px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;" width="100%">
                          <table border="0" cellpadding="10" cellspacing="0" class="image_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                            <tr>
                              <td>
                                <div align="center" style="line-height:10px">
                                  <a href="' . $base_url . '" style="outline:none" tabindex="-1" target="_blank">
                                    <img alt="' . $web_name . '" src="https://img.jamsrworld.com/logo.png" style="display: block; height: auto; border: 0; width: 220px; max-width: 100%;" title="Your Logo" width="220" />
                                  </a>
                                </div>
                              </td>
                            </tr>
                          </table>
                          <table border="0" cellpadding="0" cellspacing="0" class="image_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                            <tr>
                              <td style="width:100%;padding-right:0px;padding-left:0px;">
                                <div align="center" style="line-height:10px">
                                  <img alt="Card Header with Border and Shadow Animated" class="big" src="https://img.jamsrworld.com/animated_header.gif" style="display: block; height: auto; border: 0; width: 600px; max-width: 100%;" title="Card Header with Border and Shadow Animated" width="600" />
                                </div>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </tbody>
          </table>
          <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-2" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #cfd6f4;" width="100%">
            <tbody>
              <tr>
                <td>
                  <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-image: url(\'https://img.jamsrworld.com/body_background_2.png\'); background-position: top center; background-repeat: repeat; color: #000000;" width="600">
                    <tbody>
                      <tr>
                        <td class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-left: 50px; padding-right: 50px; padding-top: 15px; padding-bottom: 15px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;" width="100%">
                          <table border="0" cellpadding="10" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
                            <tr>
                              <td>
                                <div style="font-family: sans-serif">
                                  <div style="font-size: 14px; mso-line-height-alt: 16.8px; color: #506bec; line-height: 1.2; font-family: Helvetica Neue, Helvetica, Arial, sans-serif;">
                                    <p style="margin: 0; font-size: 14px;">
                                      <strong>
                                        <span style="font-size:32px;">' . $email_heading . '</span>
                                      </strong>
                                    </p>
                                  </div>
                                </div>
                              </td>
                            </tr>
                          </table>
                          <table border="0" cellpadding="10" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
                            <tr>
                              <td>
                                <div style="font-family: sans-serif">
                                  <div style="font-size: 14px; mso-line-height-alt: 16.8px; color: #40507a; line-height: 1.2; font-family: Helvetica Neue, Helvetica, Arial, sans-serif;">
                                    <div style="margin: 0; font-size: 14px;">

                                    ' . $email_body_content . '

                                    </p>
                                  </div>
                                </div>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </tbody>
          </table>

          <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-3" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #cfd6f4;" width="100%">
            <tbody>
              <tr>
                <td>
                  <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000;" width="600">
                    <tbody>
                      <tr>
                        <td class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 0px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;" width="100%">
                          <table border="0" cellpadding="0" cellspacing="0" class="image_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                            <tr>
                              <td style="width:100%;padding-right:0px;padding-left:0px;">
                                <div align="center" style="line-height:10px">
                                  <img alt="Card Bottom with Border and Shadow Image" class="big" src="https://img.jamsrworld.com/bottom_img.png" style="display: block; height: auto; border: 0; width: 550px; max-width: 100%;" title="Card Bottom with Border and Shadow Image" />
                                </div>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </tbody>
          </table>

          <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-4" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #cfd6f4;" width="100%">
            <tbody>
              <tr>
                <td>
                  <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000;" width="600">
                    <tbody>
                      <tr>
                        <td class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-left: 10px; padding-right: 10px; padding-top: 10px; padding-bottom: 20px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;" width="100%">
                          <table border="0" cellpadding="10" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
                            <tr>
                              <td>
                                <div style="font-family: sans-serif">
                                  <div style="font-size: 14px; mso-line-height-alt: 16.8px; color: #97a2da; line-height: 1.2; font-family: Helvetica Neue, Helvetica, Arial, sans-serif;">
                                    <p style="margin: 0; text-align: center; font-size: 12px;">
                                      <span style="font-size:12px;">Copyright© 2021, <a style="color:#506bec;"
                                      		href="https://jamsrworld.com/">Jamsrmlm</a>.</span>
                                    </p>
                                  </div>
                                </div>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </tbody>
          </table>
       
        </td>
      </tr>
    </tbody>
  </table>
</body>';


  $query = mysqli_query($conn, "SELECT * FROM $setting_tbl");
  $row = mysqli_fetch_array($query);
  $mail_encryption = $row["mail_encryption"];
  $mail_host = $row["mail_host"];
  $mail_username = $row["mail_username"];
  $mail_port = $row["mail_port"];
  $mail_password = $row["mail_password"];


  $sender_email = $mail_username;
  $password = $mail_password;
  $host = $mail_host;


  $mail = new PHPMailer();
  $mail->IsSMTP();
  $mail->SMTPAuth = true;
  $mail->SMTPSecure = $mail_encryption;
  $mail->Host = $host;
  $mail->Port = $mail_port;
  $mail->Username = $sender_email;
  $mail->Password = $password;
  $mail->IsHTML(true);
  $mail->From = $sender_email;
  $mail->FromName = $regards;
  $mail->Sender = $sender_email;
  $mail->AddReplyTo($sender_email, $regards);
  $mail->Subject = $subject;
  $mail->Body = $body;
  $mail->AddAddress($receiver_email);
  if ($mail->send()) {
    return true;
  } else {
       echo 'Mailer Error: ' . $mail->ErrorInfo;
    return false;
  }
}
