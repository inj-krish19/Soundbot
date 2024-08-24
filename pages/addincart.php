<link rel="icon" type="image/x-icon" href="./images/logos/wishlist.png">
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message</title>
</head>
<style>
    
    body{
        margin : 0;
        background : #4a0053;
    }

    .container{
        display : flex;
        margin : 0px;
        justify-content : center;
        align-items : center;
    }

    
    ul{
        margin-top : 0%;
        height : 8vh;
        display : flex;
        align-items : center;
        list-style-type: none;
        flex-wrap : wrap-reverse;
        background : black;
        padding  : 5px 5px 0px 0px;
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

</style>
<body>
</body>
</html>
<?php

    session_start();
    
    if(
        isset( $_REQUEST["category"] ) &&
        isset( $_REQUEST["id"] )    &&
        isset( $_REQUEST["quant"] )    &&
        isset( $_REQUEST["paymet"] ) 
    ){

        try{    

            require_once("scripts/connection/connection-pdo.php");

            $query = "use soundbot";

            $connection->query($query);
            
            $query = "select phid as 'id' from products where productid='". $_REQUEST["id"] ."'";

            $result = $connection->query($query);
            
            $record = $result->fetch(PDO::FETCH_ASSOC);
            

            if( $_SESSION["user"] == "guest" ){
                header("Location:product.php?page=0");
            }

            $pid = (int)$record["id"];

            $query = "select uhid as 'id' from users where userid='". $_SESSION["user"] ."'";

            $result = $connection->query($query);

            $record = $result->fetch(PDO::FETCH_ASSOC);
            
            $uid = (int)$record["id"];

            $query = " insert into cart(userid,productid,quant,orderstatus,paystatus,paymethod)
                values(". $uid .",". $pid .",". (int)$_REQUEST["quant"] .",'Cart','Pending','". $_REQUEST["paymet"] ."')";

            $connection->query($query);

            header("Location:product.php?page=0");

        }catch(Exception $e){
            echo "<div class=container><h1>Duplication Is Not Allowed</h1></div>";
        }

    }

?>