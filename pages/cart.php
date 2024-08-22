<?php   
    session_start();    

    if( isset($_SESSION["user"]) && $_SESSION["user"] != "guest" ){

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="icon" type="image/x-icon" href="./images/logos/cart.png">
</head>
<style>
    
    body{
        margin : 0px;
        background : #4a0053;
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

    li:nth-child(4){
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
    
    ul > h1{
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

    .container > .card > img{
        height : 200px;
        width : 200px;
    }

    .container{
        margin : 1% ;
        display : flex;
        justify-content : center;
        align-items:center;
        flex-wrap : wrap;
    }

    .card{
        height : 400px;
        margin : 0% 1% 1% 0%;
        padding : 1%;
        width : 300px;
        border : 2px solid black;
        border-radius : 1%;
        text-align : center;
    }

    h2,h3,h4,h5{
        text-align : left;
        font-weight : bolder;
    }
    
    h4,h5{
        margin-block-start : 0.5rem;
        margin-block-end : 0.5rem;
    }

    button{
        background : black;
        color : white;
        height : 25px;
        border-radius : 1%;
        border : 2px solid white;
    }

</style>
<body>
    <ul>
        <li><a href="home.php" >Home</a></li>
        <li><a href="aboutus.php" >About Us</a></li>
        <li><a href="contactus.php" >Contact Us</a></li>
        <li><a href="feedback.php" >Feedback</a></li>
        <li><a href="product.php?page=0" ><img src=./images/logos/product.png ></a></li>
        
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
</body>
</html>
<?php

        echo "<div class=container>";

        require_once("scripts/connection/connection-pdo.php");

        $query = "use soundbot";

        $result = $connection->query($query);
        
        $query = "
            select uhid from users where userid = '". $_SESSION["user"] ."';
        ";

        $result = $connection->query($query);
        
        $record = $result->fetch(PDO::FETCH_ASSOC);

        $uid = $record["uhid"];

        $query = "
            select P.productid,P.pname,P.pcategory,P.pdescription,P.pprice,I.imageurl
            from products P inner join images I
            where P.productid = I.uspid and P.phid in (
                select A.productid from cart A where userid='". $uid ."'
            )
        ";

        $result = $connection->query($query);

        $notFound = true;

        while ( $record = $result->fetch(PDO::FETCH_ASSOC)  ){
            
            echo "
            <div class=card> 
                <img src= ". $record["imageurl"] ." > 
                <h2> ". $record["pname"] ." </h2>
                <h3> ". $record["pprice"] ." </h3>
                <h4> ". $record["pcategory"] ." </h4>
                <h5> ". $record["pdescription"] ." </h5>
                <button onclick=window.location.href='bill.php?id=". $record["productid"] ."'> Bill </h5>
            </div>";

            $notFound = false;

        }

        if( $notFound ){
            echo "<h1> Your Cart Is Empty </h1>";
        }

        echo "</div>";  

    }else{

        echo "<h1>This Page Is Not For Guest</h1>";

        header("Location:product.php?page=0");

    }

?>