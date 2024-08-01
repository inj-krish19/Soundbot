<?php   session_start();    ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Profile</title>
        <link rel="icon" type="image/x-icon" href="./images/logos/user.png">
</head>

<style>
    
    body{
        margin : 0px;
        color: #4a0053;
        background : #4a0053;
        font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
    }

    ul{
        margin-top : 0;
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

    li:last-child:hover{
        border-radius : 50%;
        border : 3px solid blue;
        transform:rotate(0deg);
    }
    
    li:hover{
        border-bottom : 3px solid blue;
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
        color : green;
        text-decoration : none;
        font-weight : bolder;
    }

    p,h1{
        font-family : inherit;
        color : white;
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

    
    @media screen and (max-width:550px) {
        ul{
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

    .Profile {
        margin-left : 38vw;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 400px;
        width: 300px;
        background-color: #c8a4d4;
        border: 3px solid antiquewhite;
        border-radius: 0.25%;
        font-weight: bolder;
    }

    .Delete {
        margin-top : 2vh;
        padding-top : 2vh;
        height: 50px;
        width: 300px;
        background-color: #c8a4d4;
        border: 3px solid antiquewhite;
        border-radius: 0.25%;
        font-weight: bolder;
    }

    input {
        background : lightblue;
        border : 1px solid #252525;
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

                $res = mysqli_query($conn,$query);

                $rec = mysqli_fetch_assoc($res);

                $imageLink = $rec["imageurl"];

                echo "<li><a href=userprofile.php><img src=". './'.$imageLink ." ></a></li>";
            }

        ?>
    </ul>

<?php

    require_once("scripts/connection/connection.php");

    $query = "select * from users where userid='". $_SESSION["user"] ."' ";

    $rec = mysqli_query($conn,$query);

    $res = mysqli_fetch_assoc($rec);

?>

    <div class="Profile">

        <form method="post">

            <label for="fname">Name : </label>
            <input type="text" id="fname" name="uname" value= <?php echo $res["uname"]; ?> > <br><br>

            <label for="email">Email : </label>
            <input type="email" id="email" name="uemail" value= <?php echo $res["uemail"]; ?> > <br><br>

            <label for="contact">Conact Number : </label>
            <input type="text" id="contact" name="ucontact" value= <?php echo $res["ucontact"]; ?> > <br><br>

            <input type="submit" name="submit"> <br><br>

        </form>

    </div>
    
    <div class="Profile Delete">

        <form method="post">

            <input type="submit" value="Delete Account" name="delete"> <br><br>

        </form>

    </div>

</body>

<?php

    if(
        isset( $_POST["uname"] ) && 
        isset( $_POST["uemail"] ) && 
        isset( $_POST["ucontact"] ) &&
        isset( $_POST["submit"] ) 

    ){

        $query = "update users 
        set uname = '". $_POST["uname"] ."',
            uemail = '". $_POST["uemail"] ."',
            ucontact = '". $_POST["ucontact"] ."'
        where userid='". $_SESSION["user"] ."'; ";

        try{

            if( strlen($_POST["ucontact"]) != 10 ){
                throw new Exception("Error");
            }

        }catch(Exception $e){
            $e->errorMessage();
        }

        $rec = mysqli_query($conn,$query);

        echo "<h1>Data Updated Successfully</h1>";

        header("Location:home.php");

    }

    if(
        isset( $_POST["delete"] )
    ){

        // $query = "delete from users where userid = '". $_SESSION["user"]  ."'";

        // mysqli_query($conn,$query);
        
        $_SESSION["user"] = "guest";

        header("Location:home.php");

    }


?>

</html>