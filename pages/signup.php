<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="icon" type="image/x-icon" href="./images/logos/signup.png">
</head>
<style>
    body {
        background : #643b9f;
        display: flex;
        justify-content: center;
        align-items: center;
        font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        color: #4a0053;
    }

    .signUp {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 600px;
        min-width: 400px;
        background-color: #c8a4d4;
        border: 3px solid antiquewhite;
        border-radius: 0.25%;
        font-weight: bolder;
    }
    
    input , textarea , select{
        background : lightblue;
        border : 1px solid #252525;
    }
    
    span {
        color: #7d055a;
    }

    form > a{
        font-size : 1rem;
        color : #4a0053;
        text-decoration : none;
    }

</style>

<?php

    $emailErr = $nameErr = $passErr = $bdateErr = $contErr = $payErr = $addErr = $bdateErr = $genErr = $choiceErr = $pfpErr = "";

    if( empty( $_POST["suemail"] ) ){
        $emailErr = "* Email Should Not Be Empty";
    }

    if( empty( $_POST["suname"] ) ){
        $nameErr = "* Name Should Not Be Empty";
    }
    
    if( empty( $_POST["supass"] ) ){
        $passErr = "* Password Should Not Be Empty";
    }

    if( empty( $_POST["subdate"] ) ){
        $bdateErr = "* Birthdate Should Not Be Empty";
    }

    if( empty( $_POST["sucont"] ) ){
        $contErr = "* Contact No. Should Not Be Empty";
    }

    if( empty( $_POST["supay"] ) ){
        $payErr = "* Payment No. Should Not Be Empty";
    }
    
    if( empty( $_POST["suadd"] ) ){
        $addErr = "* Address Should Not Be Empty";
    }
    
    if( empty( $_POST["subdate"] ) ){
        $bdateErr = "* Birth Date Should Not Be Empty";
    }

    if( empty( $_POST["sugen"] ) ){
        $genErr = "* Gender Should Not Be Empty";
    }
    
    if( empty( $_POST["suchoice"] ) ){
        $choiceErr = "* Choice Should Not Be Empty";
    }
    
    if( 

        isset( $_POST["suemail"] )      &&
        isset( $_POST["suname"] )       &&
        isset( $_POST["supass"] )       &&
        isset( $_POST["subdate"] )      &&
        isset( $_POST["sugen"] )        &&
        isset( $_POST["suchoice"] )     &&
        isset( $_POST["sucont"] )       &&
        isset( $_POST["supay"] )        &&
        isset( $_POST["suadd"] )        &&
        isset( $_POST["susub"] )           &&
        ! empty( $_POST["suemail"] )

    ){
            
        $flag = 1;
        
        $email = $_POST["suemail"];
        $name = $_POST["suname"];
        $pass = $_POST["supass"];
        $gender = $_POST["sugen"];
        $date = $_POST["subdate"];
        $choice = $_POST["suchoice"];
        $contact = $_POST["sucont"];
        $payment = $_POST["supay"];
        $add = $_POST["suadd"];
        
        
        $pattern = "/([0-9])/";
        $service = preg_split("/@/",$email);
        $service = $service[1];
        $domain = preg_split("/./",$email);
        $domain = $domain[1];
        
        if( preg_match($pattern,$email[0]) ){
            $emailErr = "* Email Should Not Start With Digits";
            $flag = 0;
        }elseif( preg_match($pattern,$service) ){
            $emailErr = "* Domain Cannot Have Digits";
            $flag = 0;
        }elseif( strlen($domain) > 4 && strlen($domain) < 2 ){
            $emailErr = "* Domain Should Be Between 2 to 4 Digits";
            $flag = 0;
        }

        if( preg_match($pattern,$name) ){
            $nameErr = "* Name Should Not Have Digits";
            $flag = 0;
        }

        if( preg_match($pattern,$pass[0]) ){
            $passErr = "* Password Should Not Be Start With Digits";
            $flag = 0;
        }

        if( empty($gender) ){
            $genErr = "* Select Gender Please";
            $flag = 0;
        }


        if( empty($choice) ){
            $genErr = "* Select Category Please";
            $flag = 0;
        }   
        
        if( strlen($contact) == 10 ){
            $pattern = "/([a-z])/i";
            if( preg_match($pattern,$contact) ){
                $contErr = "* Contact Number Should Have Only Digits";
                $flag = 0;
            }
            
        }else{
            $contErr = "* Contact Number Should Have Only 10 Digits";
            $flag = 0;
        }

        if( strlen($payment) == 12 ){
            $pattern = "/([a-z])/i";
            if( preg_match($pattern,$payment) ){
                $payErr = "* Payment Number Should Have Only Digits";
                $flag = 0;
            }
            
        }else{
            $payErr = "* Payment Number Should Have Only 12 Digits";
            $flag = 0;
        }   

        if( empty($date) ){
            $bdateErr = "* Birthdate Should Not Be Empty";
            $flag = 0;
        }
        
        if( empty($add) ){
            $addErr = "* Address Should Not Be Empty";
            $flag = 0;
        }   
        
        if( 
            pathinfo( $_FILES["supfp"]["tmp_name"] , PATHINFO_EXTENSION ) == "jpg"     ||
            pathinfo( $_FILES["supfp"]["tmp_name"] , PATHINFO_EXTENSION ) == "jpeg"    ||
            pathinfo( $_FILES["supfp"]["tmp_name"] , PATHINFO_EXTENSION ) == "png"     
        ){
            $pfpErr = "* Profile Picture Should Have Extension In Either jpg,jpeg or png";
            $flag = 0;
        }

        if( $flag == 1 ){
            
            //  query for inserting data in user
            //  query for inserting image url and name as per userid 

            require_once("scripts/connection/connection.php");

            mysqli_select_db($conn,$databaseName);
            
            $query = "insert into users(uemail,uname,upass,uchoice,ubdate,ugender,ucontact,upayment,uaddress) 
            values('". $email ."','". $name ."','". password_hash($pass,1) ."','". substr($choice,0,-1) ."','". $date ."', 
            '". $gender ."','". $contact ."','". $payment ."','". $add ."')";

            mysqli_query($conn,$query);

            $query = "select userid from users where uhid = (select max(uhid) from users);";
            
            $result = mysqli_query($conn,$query);

            $record = mysqli_fetch_assoc($result);
            
            if( $record['userid'] != NULL){
                $uid = $record['userid'];
            }else{
                $uid = "USR000000";
            }
            
            $result = mysqli_query($conn,$query);
            
            $query = "insert into images(uspid,imageurl,imageasp,imageheight,imagewidth,imagetype) 
            values('". $uid ."','images/users/user-". $uid .".jpg','16:9',1920,1080,'User')";

            if( ! empty( $_FILES["supfp"]["tmp_name"] ) ){
              
                move_uploaded_file( $_FILES["supfp"]["tmp_name"] , "images/users/user-". $uid .".jpg" );

            }else{

                copy( "images/users/default-user-pfp.jpg" , "images/users/user-". $uid .".jpg" );
                $pfpErr = "* Default Profile Picture Will Be Setted";
            
            }
            
            $result = mysqli_query($conn,$query);

            session_start();
            
            $_SESSION["user"] = $uid;

            header("Location:home.php");
        
        }

    }

?>

<body>
    
    <div class="signUp">
    
        <form method="post" enctype="multipart/form-data">
            
            <h1>Sign Up</h1>
            
            <label for="suname">Name</label><br>
            <input type="text" id="suname" name="suname"><br>
            <span class="nameerror"><?php echo $nameErr;  ?></span><br>

            <label for="suemail">E-mail</label><br>
            <input type="email" id="suemail" name="suemail"><br>
            <span class="emailerror"><?php echo $emailErr;  ?></span><br>
            
            <label for="supass">Password</label><br>
            <input type="password" id="supass" name="supass"><br>
            <span class="passerror"><?php echo $passErr;  ?></span><br>
            
            <label for="subdate">Birthdate</label><br>
            <input type="date" id="subdate" name="subdate"><br>
            <span class="bdateerror"><?php echo $bdateErr;  ?></span><br>

            <label for="sugen">Gender</label><br>
            <select id="sugen" name="sugen">
                <option></option>
                <option>Male</option>
                <option>Female</option>
                <option>Others</option>
            </select><br>
            <span class="generror"><?php echo $genErr;  ?></span><br>
            
            <label for="suchoice">Choice</label><br>
            <select id="suchoice" name="suchoice">
                <option></option>
                <option>Headphones</option>
                <option>Earbuds</option>
                <option>Earphones</option>
                <option>All</option>
            </select><br>
            <span class="choiceerror"><?php echo $choiceErr;  ?></span><br>

            <label for="sucont">Conatct Number</label><br>
            <input type="text" id="sucont" name="sucont"><br>
            <span class="conterror"><?php echo $contErr;  ?></span><br>

            <label for="supay">Payment Number</label><br>
            <input type="password" id="supay" name="supay"><br>
            <span class="payerror"><?php echo $payErr;  ?></span><br>

            <label for="suadd">Address</label><br>
            <textarea id="suadd" rows=5 cols="25" name="suadd"></textarea><br>
            <span class="adderror"><?php echo $addErr;  ?></span><br>

            <label for="supfp">Profile Picture </label><br>
            <input type="file" id="supfp" name="supfp"><br>
            <span class="adderror"><?php echo $pfpErr;  ?></span><br>

            <input type="submit" name="susub">
       
            <a href="login.php" > Login </a>

            <br><br>

        </form>
    
    </div>
</body>
</html>