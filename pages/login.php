<?php    

    session_start();   

    /*if( empty($_SESSION["user"]) ){
        $_SESSION["user"] = "guest";
    }
    if( isset($_SESSION["user"]) ){
        $_SESSION["user"] = "guest";
    }*/

    $emailErr = $passErr = $captchaErr = "";

    if( empty( $_POST["luemail"] ) ){
        $emailErr = "* Email Should Not Be Empty";
    }

    if( empty( $_POST["lupass"] ) ){
        $passErr = "* Password Should Not Be Empty";
    }

    function generateCaptcha() {
        // creating image of 170 x 30
        $image = imagecreate(170, 30);
        // defining background color as white in image
        $background_color = imagecolorallocate($image, 255, 255, 255);
        // defining text color as black
        $text_color = imagecolorallocate($image, 0, 0, 0);
        // accessing captch code in a variable
        $captcha_code = $_SESSION['captcha_code'];
        // converting string to image 
        // arg 1 : image handler arg 2 : fontsize arg 3 : margin-right 
        // arg 4 : margin-bottom arg 5 : captcha code arg 6 : text color
        imagestring($image, 15, 45, 10, $captcha_code, $text_color);
        // turn on output buffering
        ob_start();
        // made image in jpeg 
        imagejpeg($image);
        // cleaned image
        $image_data = ob_get_clean();
        // deleted temp image from location
        imagedestroy($image);
        // returning final image
        return $image_data;
    }

    if (
        isset($_POST["luemail"])    && 
        isset($_POST["lupass"])     && 
        isset($_POST["lucaptcha"])  && 
        isset($_POST["lusubmit"])   &&
        ! empty($_POST["luemail"])
    ) {
    
        $flag = 1;

        $useremail = $_POST["luemail"];
        $userpass = $_POST["lupass"];
        $captcha = $_POST["lucaptcha"];
        
        
        $pattern = "/([0-9])/";
        $service = preg_split("/@/",$useremail);
        $service = $service[1];
        $domain = preg_split("/./",$userpass);
        $domain = $domain[1];
        
        if( preg_match($pattern,$useremail[0]) ){
            $emailErr = "* Email Should Not Start With Digits";
            $flag = 0;
        }elseif( preg_match($pattern,$service) ){
            $emailErr = "* Domain Should Not Have With Digits";
            $flag = 0;
        }elseif( strlen($domain) > 4 && strlen($domain) < 2 ){
            $emailErr = "* Domain Should Be Between 2 to 4 Digits";
            $flag = 0;
        }
        
        if( preg_match($pattern,$userpass[0]) ){
            $passErr = "* Password Should Not Start With Digits";
            $flag = 0;
        }
        
        if( $flag == 1){
            
            require_once("scripts/connection/connection.php");        
            
            $query = "select userid,uname,upass,count(*) as 'count' from users where uemail='". $useremail ."' ";
            
            $result = mysqli_query($conn,$query);
            
            
            while( $record = mysqli_fetch_assoc($result) ){
                
                if( password_verify($userpass,$record["upass"]) ) {
                    $_SESSION["user"] = $record["userid"];
                    $flag = 1;
                    break;
                }else{
                    $flag = 0;
                }

            }

            if($record != NULL){
                $record = (int)$record["count"];
            }else{
                $record = 0;
            }

            if( $record >= 1 ){
                
                if( $flag == 0 ){
                    $passErr = "* Wrong Password";
                    echo "<script>alert('Wrong Password')</script>";
                }else{
                    
                    if( ( strtolower($_POST["lucaptcha"]) == $_SESSION["captcha_code"] ) ){
                        header("Location:home.php");
                    }elseif(  ( strtolower($_POST["lucaptcha"]) == $_SESSION["captcha_code"] ) ){
                        $captchaErr = "* Wrong Captcha Code";
                        echo "<script>alert('Invalid Captcha')</script>";
                    }

                }

            }else{
                
                echo "<script>alert('Record Not Found Go And Signup')</script>";
                $captcha_code = strtolower(substr(md5(mt_rand()), 0, 9));
                $_SESSION['captcha_code'] = $captcha_code;

            }
        
        }else{
            $emailErr = "* Email and Password Failed In Verification";
        }

    }else{
        
        $captcha_code = strtolower(substr(md5(mt_rand()), 0, 9));
        $_SESSION['captcha_code'] = $captcha_code;
    
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" type="image/x-icon" href="./images/logos/login.png">
</head>

<style>
    body {
        background : #643b9f;
        display: flex;
        margin-top : 25vh;
        justify-content: center;
        align-items: center;
        font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        color: #4a0053;
    }

    .Login {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 400px;
        min-width: 300px;
        background-color: #c8a4d4;
        border: 3px solid antiquewhite;
        border-radius: 0.25%;
        font-weight: bolder;
    }

    input {
        background : lightblue;
        border : 1px solid #252525;
    }
    
    span {
        color: #7d055a;
    }

    .Login>form>img {
        width: 170px;
        height: 40px;
    }

    form > a{
        font-size : 1rem;
        color : #4a0053;
        text-decoration : none;
    }

</style>

<body>
    
    <div class="Login">
        
        <form method="post">
            <h1>Login </h1>
            
            <label for="luemail">E-mail</label><br>
            <input type="email" id="luemail" name="luemail"><br>
            <span class="ueerror"> <?php echo $emailErr; ?> </span><br>
            
            <label for="lupass">Password</label><br>
            <input type="password" id="lupass" name="lupass"><br>
            <span class="uperror"> <?php echo $passErr; ?> </span><br><br>
            
            <label>Captcha</label><br>
            
            <img id="lucaptcha" src="data:image/jpeg;base64,<?php echo base64_encode(generateCaptcha()); ?>"><br><br>

            <label for="lucaptcha">Verify</label><br>

            <input type="text" id="lucaptcha" name="lucaptcha"><br>
            <span class="uperror"> <?php echo $captchaErr; ?> </span><br>

            <input type="submit" name="lusubmit">
            <a href="signup.php" >Signup</a>
            <a href="home.php" >Login As Guest</a>
        </form>
        
    </div>
</body>
</html>

<!-- 

Note : If Captcha Is Not Showing In Login Page
Then Go To php.ini and 
remove semicolon (;) from 

;extension:gd => extension:gd

-->