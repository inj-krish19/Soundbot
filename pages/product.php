<?php   session_start();  ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
    <link rel="icon" type="image/x-icon" href="./images/logos/product.png">
</head>
<style>
    
    body{
        margin : 0px;
        background : #4a0053;
        font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
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
        padding : 3px;
    }

    li:nth-child(6):hover,li:last-child:hover{
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

    a.slide{
        text-align : center;
        width : 50px;
        background : #252525;
        font-size : 40px;
        margin : 0 10px;
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
        <li><a href="product.php?page=0" >Product</a></li>
        <li><a href="cart.php" ><img src=./images/logos/cart.png ></a></li>
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


    require_once("scripts/connection/connection-pdo.php");

    if( isset($_REQUEST["page"]) ){

        if( ((int)$_REQUEST["page"]) - 1 > 0 && ((int)$_REQUEST["page"]) < ceil(120/16) ){
            $page = (int)$_REQUEST["page"];
        }else{
            $page = 0;
        }

        $query = "use soundbot";

        $result = $connection->query($query);

        $query = "
        select P.productid,P.pname,P.pcategory,P.pdescription,P.pprice,I.imageurl
        from products P inner join images I
        where P.productid = I.uspid
        limit ". ($page*16) .",16
                ";

        $result = $connection->query($query);

        $page = (int)$_REQUEST["page"];

        $last = $page - 1;
        $next = $page + 1;
        
        echo "<div class=container>";
        
        while ( $record = $result->fetch(PDO::FETCH_ASSOC)  ){
        
        echo "
            <div class=card> 
                <img src= ". $record["imageurl"] ." > 
                <h2> ". $record["pname"] ." </h2>
                <h3> ". $record["pprice"] ." </h3>
                <h4> ". $record["pcategory"] ." </h4>
                <h5> ". $record["pdescription"] ." </h5>
                <button onclick=window.location.href='productdetail.php?category=". $record["pcategory"] ."&id=". $record["productid"] ."' >View Product</button>
                <button onclick=window.location.href='addincart.php?category=". $record["pcategory"] ."&id=". $record["productid"] ."' >Add To Cart</button>
            </div>";
    }

    echo "</div>";  

}

?>