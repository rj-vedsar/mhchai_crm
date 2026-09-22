<?php
class custom
{
    // ! ||--------------------------------------------------------------------------------||
    // ! ||                        SENT OTP ON MOBILE THROUGH EMAIL                        ||
    // ! ||--------------------------------------------------------------------------------||
    public function send_mail($mail, $email, $html, $subject, $user_email, $user_pass, $host = "", $host_port = 587, $title = 'Unknown')
    {
        ## $mail = new PHPMailer(true);
        ## create a $mail object when use this function dont create here otherwise thrown error
        $mail->IsSMTP();
        $mail->Host = $host;
        $mail->Port = $host_port;
        $mail->SMTPSecure = "tls";
        $mail->SMTPAuth = true;
        $mail->Username = $user_email;
        $mail->Password = $user_pass;
        $mail->SetFrom($user_email, $title);
        $mail->addAddress($email);
        $mail->IsHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $html;
        $mail->SMTPOption = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => false
        ));
        if ($mail->Send()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    // ! ||--------------------------------------------------------------------------------||
    // ! ||                        RETURN FORM GROUP COLUMN INPUT FIELD                    ||
    // ! ||--------------------------------------------------------------------------------||

    public function createFormField($label = '', $type = 'text', $name = '', $placeholder = '', $id = '', $labelClass = 'font-weight-semibold', $value = '',  $class = 'form-control', $customCode = '', $column = 'col-md-6')
    {
        ob_start();
?>
        <div class="<?php echo $column ?> form-group">
                <label class="<?php echo $labelClass ?>" for="<?php echo $id ?>"><?php echo $label ?></label>
                <input type="<?php echo $type; ?>" name="<?php echo $name ?>" id="<?php echo $id ?>" value="<?php echo $value; ?>" class="<?php echo $class ?>" placeholder="<?php echo $placeholder; ?>" <?php echo $customCode ?> />
                <span class="mt-1 text-danger <?php echo $id ?>"></span>
        </div>
    <?php
        return ob_get_clean();
    }

    public function createTextareaField($label = '', $type = 'text', $name = '', $placeholder = '', $id = '', $labelClass = 'font-weight-semibold', $value = '', $class = 'form-control', $customCode = '', $column = 'col-md-6')
    {
        ob_start();
    ?>
        <div class="<?php echo $column ?> form-group">
                <label class="<?php echo $labelClass ?>" for="<?php echo $id ?>"><?php echo $label ?></label>
                <textarea name="<?php echo $name ?>" id="<?php echo $id ?>" class="<?php echo $class ?>" placeholder="<?php echo $placeholder; ?>" <?php echo $customCode ?>><?php echo $value; ?></textarea>
                <span class="mt-1 text-danger <?php echo $id ?>"></span>
        </div>
<?php
        return ob_get_clean();
    }

    // ! ||--------------------------------------------------------------------------------||
    // ! ||                           RETURN TD TAG WITH DATA                              ||
    // ! ||--------------------------------------------------------------------------------||
    public function createTableCell($content = '', $class = '')
    {
        $html = '<td class="' . $class . '">' . $content . '</td>';
        return $html;
    }
    // ! ||--------------------------------------------------------------------------------||
    // ! ||                                  RETURN UNIQUE ID                              ||
    // ! ||--------------------------------------------------------------------------------||

public function generateRandomUniqueId($suffix)
{
    $prefix = $suffix; // Optional prefix to add to the unique ID
    $timestamp = rand(10000,99999).date('mis');
    $randomId = $prefix . bin2hex(random_bytes(8)) . $timestamp;
    $randomId = substr($randomId, 0, 25); // Trim the ID to a maximum of 25 characters
    return $randomId;
}

    // ! ||--------------------------------------------------------------------------------||
    // ! ||                                  VALIDATE EMAIL                                 ||
    // ! ||--------------------------------------------------------------------------------||
    public function validateEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }
    // ! ||--------------------------------------------------------------------------------||
    // ! ||                                  VALIDATE NAME                                 ||
    // ! ||--------------------------------------------------------------------------------||
    public function validateName($name)
    {
        // Specify the desired name pattern
        $pattern = '/^[a-zA-Z\'\- ]+$/u';

        if (preg_match($pattern, $name)) {
            return true;
        } else {
            return false;
        }
    }
    // ! ||--------------------------------------------------------------------------------||
    // ! ||                                 VALIDATE URL                                   ||
    // ! ||--------------------------------------------------------------------------------||
    public function validateURL($url)
    {
        // Specify the desired URL pattern
        $pattern = '/^(http(s)?:\/\/)?(www\.)?[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}(\/\S*)?$/';

        if (preg_match($pattern, $url)) {
            return true;
        } else {
            return false;
        }
    }

    
    // ! ||--------------------------------------------------------------------------------||
    // ! ||                          TRUNCATE CHARACTER FROM A STRING                       ||
    // ! ||--------------------------------------------------------------------------------||
    public function truncateString($string, $maxLength)
    {
        if (strlen($string) > $maxLength) {
            $truncatedString = substr($string, 0, $maxLength) . '...';
        } else {
            $truncatedString = $string;
        }

        return $truncatedString;
    }
   
    // ! ||--------------------------------------------------------------------------------||
    // ! ||                             GENERATE FILE NAME                                 ||
    // ! ||--------------------------------------------------------------------------------||
    public function generateRandomFilename($filename)
    {
        $uniqueId = uniqid();
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $randomFilename = $uniqueId.date('Ymdh'). '.' . $extension;
        return $randomFilename;
    }
    /* ***************************************************************************************************
************                       Function for mail message    ********
**************************************************************************************************** */
    public function verifyAccount($random_pass, $img_src)
    {
        $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Account</title>
    <style>
        * {
            font-family: "Trebuchet MS", "Lucida Sans Unicode", "Lucida Grande", "Lucida Sans", Arial, sans-serif;
        }
    </style>
</head>
<body>
    <div style="border-radius: 20px; margin: 0; align-items: center; background: #007bffb3; padding: 2rem 0;">
        <div style="margin: auto; width: 85%; border-radius: 8px; background: #fff; text-align: center; box-shadow: 2px 2px 3px #c9c9c9, -2px -2px 3px #ffffff; padding: 2rem 0;">
            <img style="width: 80%; margin: 0 auto; height: auto;" src="'. $img_src.'" alt="">
            <br>
            <div style="margin: 0; align-items: center; padding: 0.5rem 0;" class="d-flex justify-content-center">
                <h2 style="margin-left: 20%; font-size: 22px; width: 60%; text-align: center; color: #333; background: #ededed; box-shadow: 2px 2px 3px #c9c9c9, -2px -2px 3px #ffffff; padding: 1.5rem 0;">' . $random_pass . '</h2>
            </div>
<h3><b>Your <span style="color: rgb(0, 170, 0); font-size: 20px;">OTP</span> to verify your account.</b></h3>
            <h3 style="background: rgb(255, 30, 30); border-radius: 8px;  color: #fff; padding: 1rem; letter-spacing: 3px;margin:1.5rem auto 0 auto; width: 85%;"><b>Please do not share this code with anyone.</b></h3>
        </div>
    </div>
</body>
</html>
';
        return $html;
    }
   
  
    // function for forgot mail template
    function forgot_mail_template($random_otp, $img_src)
    {
        $html = '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <style>
            *{
                font-family: "Trebuchet MS", "Lucida Sans Unicode", "Lucida Grande", "Lucida Sans", Arial, sans-serif;
            }
        </style>
    </head>
    <body>
        <div style="border-radius: 20px;margin:0;align-items: center;background:rgba(250,235,215);padding:2rem 0;">
            <div style="margin:auto;width:85%;text-align: center;border-radius: 8px;
background: #fff !important;
box-shadow:  2px 2px 3px #c9c9c9,
-2px -2px 3px #ffffff !important;padding: 2rem 0;">
                <img style="width: 80%;height:auto;" src="' . $img_src . '" alt="">
                <br>
                <div style="margin:0;align-items: center;padding: 0.5rem 0;" class="d-flex justify-content-center">
                    <h2 style="margin-left:20%;font-size:22px;width:60%;text-align: center;color: #333;background: #ededed !important;
box-shadow:  2px 2px 3px #c9c9c9,
-2px -2px 3px #ffffff !important ;padding: 1.5rem 0;">' . $random_otp . '</h2>
                </div>
                <h3><b>Your <i style="color: rgb(0, 170, 0);font-size:20px;">OTP</i> to reset your password.</b></h3>
                <h3 style="background: rgb(255, 30, 30);margin-top: 2rem;color: #fff;padding:1.5rem;letter-spacing: 3px;"><b>Please , Do Not Share This Code With Anyone.</b></h3>
            </div>
        </div>
    </body>
</html>

';
        return $html;
    }
    /* ***************************************************************************************************
************                       Function for mail message    ********
**************************************************************************************************** */
public function contactTemplete($msg, $img_src){
    $html = '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Contact Reply</title>
        <style>
            *{
                font-family: "Trebuchet MS", "Lucida Sans Unicode", "Lucida Grande", "Lucida Sans", Arial, sans-serif;
            }
        </style>
    </head>
    <body>
        <div style="border-radius: 10px;margin:0;align-items: center;background: #F2F2F2;padding:1rem 0;">
            <div style="margin:auto;width:86%;border-radius: 10px;
background: #fff !important;padding: 2rem;">
                <img style="width: 80%;margin:0 auto;display:flex;justify-content-center;height:auto;" src="' . $img_src . '" alt="">
                <div style="margin-top:20px;">
                	' . $msg . '
                </div>
            </div>
        </div>
    </body>
</html>
';
    return $html;
}

public function appointTemplete($msg, $img_src){
    $html = '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Contact Reply</title>
        <style>
            *{
                font-family: "Trebuchet MS", "Lucida Sans Unicode", "Lucida Grande", "Lucida Sans", Arial, sans-serif;
            }
        </style>
    </head>
    <body>
        <div style="border-radius: 10px;margin:0;align-items: center;background: #F2F2F2;padding:1rem 0;">
            <div style="margin:auto;width:86%;border-radius: 10px;
background: #fff !important;padding: 2rem;">
                <img style="width: 80%;margin:0 auto;display:flex;justify-content-center;height:auto;" src="' . $img_src . '" alt="">
                <div style="margin-top:20px;">
                	' . $msg . '
                </div>
            </div>
        </div>
    </body>
</html>
';
    return $html;
}

    public function mail_message($email, $actual_link2, $random_pass, $img_src)
    {
        $html = '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <style>
            *{
                font-family: "Trebuchet MS", "Lucida Sans Unicode", "Lucida Grande", "Lucida Sans", Arial, sans-serif;
            }
        </style>
    </head>
    <body>
        <div style="border-radius: 20px;margin:0;align-items: center;background: #007bffb3;padding:2rem 0;">
            <div style="margin:auto;width:85%;border-radius: 8px;
background: #fff !important;text-align:center;
box-shadow:  2px 2px 3px #c9c9c9,
-2px -2px 3px #ffffff !important;padding: 2rem 0;">
                <img style="width: 80%;margin:0 auto;height:auto;" src="'. $img_src.'" alt="">
                <br>
                <div style="margin:0;padding: 0.5rem 0;text-align:center;" class="">
                    <h2 style="font-size:18px;color: #333;padding: 0.6rem;
                   padding: .5rem 0;"><b>Email - </b>' . $email . '</h2>
                    <h2 style="font-size:18px;color: #333;padding: 0.6rem;
padding: .5rem 0;"><b>Password - </b>' . $random_pass . '</h2>
 <a href="' . $actual_link2 . '"><b>Link for Login : ' . $actual_link2 . '</b></a>
                </div>
                <h3 style="text-align:center;"><b>Your <i style="color: rgb(0, 170, 0);">Email and Password</i> for Login.</b></h3>
                <h3 style="background: #007bffb3;margin-top: 2rem;color: #fff;padding:1.5rem;letter-spacing: 3px;"><b>Please , Do Not Share Your Password With Anyone.</b></h3>
            </div>
        </div>
    </body>
</html>';
        return $html;
    }
    // ! ||--------------------------------------------------------------------------------||
    // ! ||                        AMOUNT IN BILLION, MILLION SYMBOL                       ||
    // ! ||--------------------------------------------------------------------------------||
    public function amount_in_k($amount)
{
    if ($amount < 1000) {
        // Anything less than a thousand
        $format = number_format($amount);
    } else if ($amount < 1000000) {
        // Anything less than a million
        $format = number_format($amount / 1000, 2) . 'k';
    } else if ($amount < 1000000000) {
        // Anything less than a billion
        $format = number_format($amount / 1000000, 2) . 'M';
    } else {
        // At least a billion
        $format = number_format($amount / 1000000000, 2) . 'B';
    }

    return $format;
}

    // ! ||--------------------------------------------------------------------------------||
    // ! ||                              THE END OF MAIN CLASS                             ||
    // ! ||--------------------------------------------------------------------------------||
}
