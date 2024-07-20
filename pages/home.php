<link rel="icon" type="image/x-icon" href="./images/logos/home.png">

<?php

    session_start();

    if( !isset($_SESSION["user"]) ){
        $_SESSION["user"] = "guest";
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
    
<style>
    
    body{
        margin : 0px;
        font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        background : #4a0053;
    }

    ul{
        height : 8vh;
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
        padding : 3px;
    }

    li:last-child:hover{
        border-radius : 5%;
        border : 3px solid #7d055a;
    }
    
    li:hover{
        border-bottom : 3px solid #252525;
    }
    
    img{
        display : block;
        margin-top : 2vh;
        margin-left : 35vw;
        height : 35vh;
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
    }
    
    @media screen and (max-width:900px) {

        img{
            margin : 10vw;
            width : 75%;
        }

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

</style>

<body>
    <nav>
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
    </nav>
    <img src="./images/logos/Soundbot.png">
    <h1>Soundbot</h1>
</body>
<?php

    $file = "files/Home.txt";

    $fileHandler = fopen($file,"r");

    $content = fread($fileHandler,filesize($file));

    $content = explode("-----",$content);

    for($i=0;$i<count($content);$i++){
        echo "<p>$content[$i] </p>";
    }
?>
</html>