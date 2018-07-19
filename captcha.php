<?php
function verify_captcha($secretkey, $response){

    
$url = 'https://www.google.com/recaptcha/api/siteverify';
$data = array(
    'secret' => $secretkey, 
    'response' => $response
);
$options = array(
    'http' => array(
        'method' => 'POST',
        'content' => http_build_query($data)
    )
);
$context = stream_context_create($options);
$verify = file_get_contents($url, false, $context);
$captcha_success = json_decode($verify);
return $captcha_success->success;


}


function contact2($lang, $prod_id)
{
?>

<?
require_once('recaptchalib.php');
$sitekey = "6LeaBGUUAAAAALWr7lxrQBTPS9SPvLB3fmVlRG5T";
$secretkey = "6LeaBGUUAAAAAEiC3gOBG9RZnnmi2joCqsvWbxf6";

if($_POST['btn_submit']=="Submit")
{
    $toaddress=get_config(68);
    $subject= get_config(7)." Website Feedback";
    
    if($prod_id!="")
        $subject= "Product Inquiry";
    
    $name=$_POST['txt_name'];
    $email=$_POST['txt_email'];
    $company=$_POST['txt_company'];
    $web=$_POST['txt_web'];
    $address=$_POST['txt_address'];
    $tel=$_POST['txt_tel'];
    $mob=$_POST['txt_mob'];
    $fax=$_POST['txt_fax'];
    $message=$_POST['txt_comment'];
    $string = strtoupper($_SESSION['string']);
    $userstring = strtoupper($_POST['userstring']);
    $response=verify_captcha($secretkey, $_POST["g-recaptcha-response"]);
    
    $mailcontent="";
    
    if($prod_id!="")
        $mailcontent.= "Product inquired: ".get_prod_name($prod_id, $lang);
        
     $mailcontent.="
Name: ".$name."\n
Email: ".$email."\n
Address: ".$address."\n
Phone: ".$tel."\n
Mobile: ".$mob."\n
Fax: ".$fax."\n
Message: ".$message."\n";

//$fromaddress= "From: ".$email."\r\n";

$error=0;
if(check_empty($name))
{
      $msg['name']="<br><span class=error>".get_display(35, $lang, 2, "con")."</span>";
      $error++; 
}
if(check_empty($email))
{
    $msg['email1']="<br><span class=error>".get_display(39, $lang, 2, "con")."</span>";
    $error++; 
}
if(!check_email_address($email) && !check_empty($email))
{
    $msg['email2']="<br><span class=error>".get_display(40, $lang, 2, "con")."</span>";
    $error++; 
}

if(check_empty($tel))
{
      $msg['tel']="<br><span class=error>".get_display(37, $lang, 2, "con")."</span>";
      $error++; 
}



if(check_empty($message))
{
      $msg['comment']="<br><span class=error>".get_display(41, $lang, 2, "con")."</span>";
      $error++; 
}
if (!$response) 
{
    $msg['security']="<br><span class=error>Wrong Security Code</span>";
    $error++; 
}


if($error==0)
{					  
    $find = array("/bcc\:/i","/Content\-Type\:/i","/cc\:/i","/to\:/i");
   $mailcontent = preg_replace($find, '', $mailcontent);
   $fromaddress = preg_replace($find, '', $_POST['txt_email']);
   $fromaddress= "From: ".$fromaddress."\r\n";
   $fromaddress.= "Reply-To: ".$toaddress."\r\n";
   if(get_config(70)!="")
          $fromaddress.= "Bcc: ".get_config(70)." \r\n";
       
   //unset($_SESSION['string']);
    if(mail($toaddress, $subject, $mailcontent, $fromaddress))
    {
        //$msg1=get_display(42, $lang, 2, "con");
        ?>
        <script language="javascript">
            window.location="<?=$_SERVER['PHP_SELF']."?success=1&lang=$lang"?>";
        </script>
        <?
        exit();
    }
}
}
?>
<script src='https://www.google.com/recaptcha/api.js'></script>
<style type="text/css">
/* uncomment to reduce size of captcha */
#recaptcha_image img { /*width: 240px; height: 57px; border: solid 1px #a3a9ac;*/ }
#recaptcha_image { /*width: 240px; height: 57px; margin: 3px 0 5px 0;*/}
</style>
<table cellpadding="0" cellspacing="0" border="0" align="left">
        
        
        <?php
        if($_GET['success']==1)
        {
            ?>
            <tr>
                <td width="" align="left" class="success"><?=get_display(42, $lang, 2, "con")?></td>
            </tr>
            <?
        }
        ?>
        
        
        <tr>
            <td width="" class="content">&nbsp;</td>
        </tr>
        <tr>
            <td width="" class="content"><strong><?php display(2, $lang, 2, "con") ?></strong></td>
        </tr>

        <tr>
            <td width="" valign="top">
                <form method="post" onsubmit="javascript:return ValidateForm2(this)" name="form_contact">
        <table cellpadding="0" cellspacing="5" border="0" align="left" width="94%">
            <tr>
                <td width="" class="text102"><?php display(3, $lang, 2, "con") ?>:</td>
            </tr>
            <tr>
                <td width=""><input type="text" name="txt_name" value="<?=$name?>" style="width:196px;" /> * <?=$msg['name']?></td>

            </tr>
            <tr>
                <td width="" class="text102"><?php display(13, $lang, 2, "con") ?>:</td>
            </tr>

            <tr>
                <td width=""><input type="text" name="txt_email" value="<?=$email?>" style="width:196px;" /> * <?=$msg['email1']?><?=$msg['email2']?></td>
            </tr>
            <!--<tr>
                <td width="" class="text102"><?php display(17, $lang, 2, "con") ?>:</td>
            </tr>
            <tr>
                <td width=""><input type="text" name="txt_company" value="<?=$company?>" style="width:196px;" />  <?=$msg['company']?></td>
            </tr>
            <tr>
                <td width="" class="text102"><?php display(18, $lang, 2, "con") ?>:</td>
            </tr>
            <tr>
                <td width=""><input type="text" name="txt_web" value="<?=$web?>" style="width:196px;" />  <?=$msg['web']?></td>
            </tr>-->
            <tr>

                <td width="" class="text102"><?php display(7, $lang, 2, "con") ?>:</td>
            </tr>
            <tr>
                <td width=""><input type="text" name="txt_address" value="<?=$address?>" style="width:196px;" />  <?=$msg['address']?></td>
            </tr>
            <tr>

                <td width="" class="text102"><?php display(5, $lang, 2, "con") ?>:</td>
            </tr>
            <tr>
                <td width=""><input type="text" name="txt_tel" value="<?=$tel?>" style="width:196px;" /> * <?=$msg['tel']?></td>
            </tr>
            <tr>

                <td width="" class="text102"><?php display(6, $lang, 2, "con") ?>:</td>
            </tr>
            <tr>
                <td width=""><input type="text" name="txt_mob" value="<?=$mob?>" style="width:196px;" />  <?=$msg['mob']?></td>
            </tr>
            <tr>

                <td width="" class="text102"><?php display(19, $lang, 2, "con") ?>:</td>
            </tr>
            <tr>
                <td width=""><input type="text" name="txt_fax" value="<?=$fax?>" style="width:196px;" />  <?=$msg['fax']?></td>
            </tr>
            <tr>
                <td width="" class="text102"><?php display(16, $lang, 2, "con") ?>:</td>
            </tr>
            <tr>
                <td width=""><textarea cols="45" rows="10" name="txt_comment"><?=$message?></textarea><?=$msg['comment']?></td>
            </tr>
            <tr>
                <td width="" class="text102">
                    <div class="g-recaptcha" data-sitekey="<?=$sitekey?>"></div><?=$msg['security']?></td>
            </tr>

            <tr>
                <td width="" class="text102"></td>
            </tr>
            <tr>
                <td width=""><input type="submit" value="Submit" name="btn_submit" class="but" /></td>
            </tr>

        </table>
        </form>
            </td>
        </tr>
        </td>
    </tr>
</table>					
<?
}
?>
