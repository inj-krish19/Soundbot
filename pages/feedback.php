<?php   session_start();    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
    <link rel="icon" type="image/x-icon" href="./images/logos/feedback.png">
</head>

<style>
    
    body {
        margin : 0;
        background : #643b9f;
        justify-content: center;
        align-items: center;
        font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        color: #4a0053;
    }

    .Wrapper{
        margin-top : 12vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .Feedback {
        height: 400px;
        width: 300px;
        text-align : center;
        background-color: #c8a4d4;
        border: 3px solid antiquewhite;
        border-radius: 0.25%;
        font-weight: bolder;
    }

    ul{
        height : 8vh;
        margin-block-start : 0;
        display : flex;
        align-items : center;
        list-style-type: none;
        flex-wrap : wrap-reverse;
        background : black;
        padding  : 5px 0px 0px 0px;
    }

    li{
        padding : 3px;
        font-size : 25px;
        margin-right : 10px;
        border : 3px solid black;
        transition : inherit;
        background : black;
    }

    li:nth-child(5){
        margin-right:auto;
    }

    li:last-child{
        padding : 1px;
    }

    li:last-child:hover{
        border-radius : 5%;
        border : 3px solid #7d055a;
    }
    
    li:hover{
        border-bottom : 3px solid #252525;
    }
    
    li > a > img{
        margin : 0px;
        align : center;
        height : 50px;
        width : 50px;
    }
    

    a{
        color : #7d055a;
        text-decoration : none;
        font-weight : bolder;
    }

    p > a {
        color : #c8a4d4;
    }

    p,h1{
        font-family : inherit;
        color : white;
        color: lightblue;
    }
    
    p{
        margin-left : 1%;
        margin-right : 1%;
        text-align:left;
        font-size : 1.5rem;
    }
    
    h1{
        text-align : center;
        font-size : 2.5rem;
        color : #c8a4d4;
    }

    b{
        color : lightgreen;
    }
    
    @media screen and (max-width:550px) {
        ul{
            height : auto;
            color : yellow;
            transform:rotate(180deg);
        }

        li{
            transform:rotate(-180deg);
        }

        li:last-child{
            transform:rotate(180deg);
        }

    }

    input,textarea {
        background : lightblue;
        border : 1px solid #252525;
    }
    
    span {
        color: #7d055a;
    }

    .Feedback>form>img {
        width: 170px;
        height: 40px;
    }

</style>

<body>
    <ul>
        <li><a href="home.php" >Home</a></li>
        <li><a href="aboutus.php" >About Us</a></li>
        <li><a href="contactus.php" >Contact Us</a></li>
        <li><a href="feedback.php" >Feedback</a></li>
        <li><a href="product.php?page=0" >Product</a></li>
        <?php
            
            if( $_SESSION["user"] == "guest" ){
                echo "<li><a href=signup.php><img src=./images/users/default-user-pfp.jpg ></a></li>";
            }else{
                
                include_once("scripts/connection/connection.php");

                $query = "select imageurl from images where uspid = '". $_SESSION["user"] ."' ";

                $result = mysqli_query($conn,$query);

                $record = mysqli_fetch_assoc($result);

                $imageLink = $record["imageurl"];

                echo "<li><a href=userprofile.php><img src=". './'.$imageLink ." ></a></li>";
            }

            ?>
    </ul>
    
    <?php
    
        $conn = new mysqli("localhost","root","","soundbot");
    
        $email = "";
    
        if( isset($_SESSION["user"]) && $_SESSION["user"] != "guest" ){
    
            $query = "select uemail from users where userid = '". $_SESSION["user"] ."' ";
    
            $result = $conn->query($query);
    
            $record = $result->fetch_assoc();
            
            $email = $record["uemail"];
    
    ?>

    <div class="Wrapper">

        <div class="Feedback">
            
            <form method="post">
                <h1>Feedback </h1>
                
                <label for="feedemail">E-mail</label><br>
                <input type="email" id="feedemail" name="feedemail" value = <?php echo $email; ?> > <br><br>
                
                <label for="feeddesc">Feedback</label><br>
                <textarea id="feeddesc" rows=5 cols=20 name="feeddesc"></textarea><br><br>

                <input type="submit" name="feedsubmit">

            </form>
            
        </div>

    </div>

</body>
<?php

        if(
            isset($_POST["feeddesc"])   &&
            isset($_POST["feedemail"])  &&
            isset($_POST["feedsubmit"])  
        ){

            $dayOfFeedback = getdate();

            $date = getdate();

            $day = (string)$date["mday"];  
            $month = (string)$date["mon"]; 
            $year = (string)$date["year"];

            $dayOfFeedback = "$year-$month-$day" ;

            $conn = new mysqli("localhost","root","");
            
            $query = "use soundbot";
            
            $conn->query($query);
            
            $query = "select uhid from users where userid='". $_SESSION["user"] ."'";
            
            $result = $conn->query($query);

            $record = $result->fetch_assoc();

            $uid = (int)$record["uhid"];

            $query = "insert into feedback 
            values( ". $uid ." ,'". $dayOfFeedback ."','". $_POST["feeddesc"] ."') ";

            $result = $conn->query($query);

        }

    }else{

        echo "<h1>This Page Is Not For Guest Users</h1>";

        // header("Location:product.php?page=0");

    }

?>
</html>