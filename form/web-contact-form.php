<?php 
$your_email ='monishsherkhane07@gmail.com'; // <<=== update to your email address
session_start();
$errors = '';

$company = '';
$tel = '';
$visitor_email = '';
$user_message = '';

if(isset($_POST['submit']))
{
	
	$name = $_POST['name'];
	$visitor_email = $_POST['email'];
	$company = $_POST['company'];
	$tel = $_POST['tel'];
	$user_message = $_POST['message'];	
	///------------Do Validations-------------
	

		
	if(empty($visitor_email) || empty($company))
	{
		$errors .= "\n Name, Email, Phone are required fields. ";	
	}
	if(IsInjected($visitor_email))
	{
		$errors .= "\n Bad email value!";
	}
	if(empty($_SESSION['6_letters_code'] ) ||
	  strcasecmp($_SESSION['6_letters_code'], $_POST['6_letters_code']) != 0)
	{
	//Note: the captcha code is compared case insensitively.
	//if you want case sensitive match, update the check above to
	// strcmp()
		$errors .= "\n The captcha code does not match!";
	}

		
	if(!preg_match('/^[0-9]{10}+$/', $tel))
	{
		$errors .= "\n Enter valid phone number. ";	
	}
	
	if(empty($errors))
	{
		//send the email
		$to = $your_email;
		$subject="Enquiry form";
		$from = $your_email;
		$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
		
		$body = "A user  $name submitted the contact form:\n".
		
		"Company Name: $company\n".
		"Tel: $tel\n".
		"Email: $visitor_email \n".
		"Message: \n ".
		"$user_message\n".	
		
		$headers = "From: $from \r\n";
		$headers .= "Reply-To:monishsherkhane07@gmail.com\r\n";
		$headers .= "Cc:monishsherkhane07@gmail.com\r\n"; 
		
	
		
		mail($to, $subject, $body,$headers);
		
		header('Location: thanks.html');
	}
}

// Function to validate against any email injection attempts
function IsInjected($str)
{
  $injections = array('(\n+)',
              '(\r+)',
              '(\t+)',
              '(%0A+)',
              '(%0D+)',
              '(%08+)',
              '(%09+)'
              );
  $inject = join('|', $injections);
  $inject = "/$inject/i";
  if(preg_match($inject,$str))
    {
    return true;
  }
  else
    {
    return false;
  }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> 
<html>
<head>
	
<!-- define some style elements-->
<style>
label,a, body 
{
	font-family : Arial, Helvetica, sans-serif;
	font-size : 14px; 
}
.err
{
	font-family : Verdana, Helvetica, sans-serif;
	font-size : 12px;
	color: red;
}
</style>	
<!-- a helper script for vaidating the form-->
<script language="JavaScript" src="scripts/gen_validatorv31.js" type="text/javascript"></script>	
   <style>
        .back-img {
    background: url(images/grey1.jpg) !important;
    background-repeat: no-repeat;
}
    </style>
    
</head>

<body>
<?php
if(!empty($errors)){
echo "<p class='err'>".nl2br($errors)."</p>";
}
?>
<div id='contact_form_errorloc' class='err'></div>
<form method="POST" name="contact_form" 
action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>"> 
<p>
<label for='company'>Name<span style="color:#FF0000">*</span></label> <br>
<input style=" width: 50%; padding: 10px 15px; margin-top: 10px;" type="text" name="company" value='<?php echo htmlentities($company) ?>'>
</p>


<p>
<label for='email'>Email<span style="color:#FF0000">*</span> </label><br>
<input style=" width: 50%; padding: 10px 15px; margin-top: 10px;"  type="text" name="email" value='<?php echo htmlentities($visitor_email) ?>'>
</p>
<p>
<label for='tel'>Phone<span style="color:#FF0000">*</span></label> <br>
<input style=" width: 50%; padding: 10px 15px; margin-top: 10px;"  type="text" name="tel" value='<?php echo htmlentities($tel) ?>'></p><br>
<p>
<label for='requirement'>Message<span style="color:#FF0000">*</span></label> <br>
<textarea  style=" width: 70%; padding: 10px 15px; margin-top: 10px;" name="message" rows=8 cols=25><?php echo htmlentities($user_message) ?></textarea>
</p>
<p>
<img src="captcha_code_file.php?rand=<?php echo rand(); ?>" id='captchaimg' ><br>
<label for='message'>Enter the code above here </label><br>
<input style=" width: 50%; padding: 10px 15px; margin-top: 10px;"  id="6_letters_code" name="6_letters_code" type="text"><br>
<small>Can't read the image? click <a href='javascript: refreshCaptcha();'>here</a> to refresh</small>
</p>
<input type="submit" value="Submit" name='submit' style="background: linear-gradient(0deg, rgba(28,99,184,1) 0%, rgba(255,255,255,1) 100%);
    padding: 10px 20px;
    border: 1px solid #a1bee1;
	border-radius:5px;
    width:150px">
</form>
<script language="JavaScript">
// Code for validating the form
// Visit http://www.javascript-coder.com/html-form/javascript-form-validation.phtml
// for details
var frmvalidator  = new Validator("contact_form");
//remove the following two lines if you like error message box popups
frmvalidator.EnableOnPageErrorDisplaySingleBox();
frmvalidator.EnableMsgsTogether();

frmvalidator.addValidation("name","req","Please provide your name"); 
frmvalidator.addValidation("email","req","Please provide your email"); 
frmvalidator.addValidation("email","email","Please enter a valid email address"); 
frmvalidator.addValidation("tel","req","Please enter the Telephone number"); 
frmvalidator.addValidation("tel","tel","Please enter a valid Telephone number");
frmvalidator.addValidation("requirement","req","Please provide requirement.");
frmvalidator.addValidation("company","req","Please provide company name.");
</script>
<script language='JavaScript' type='text/javascript'>
function refreshCaptcha()
{
	var img = document.img['captchaimg'];
	img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
}
</script>
</body>
</html>