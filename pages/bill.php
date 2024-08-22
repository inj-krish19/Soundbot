<?php   session_start();    ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Bill</title>
        <link rel="icon" type="image/x-icon" href="./images/logos/bill.png">
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
</body>
</html>
<?php

    class Bill{

        public $id;
        public $name;
        public $quan;
        public $price;
        public $amount;

        public function doTasks(){
            if( isset($_REQUEST["id"]) ){
                $this->id = $_REQUEST["id"];
            }
            
            $conn = new mysqli("localhost","root","","soundbot");

            $query = "select uhid from users where userid = '".  $_SESSION["user"] ."' ";

            $result = $conn->query($query);

            $record = $result->fetch_assoc();

            $uid = $record["uhid"];

            $query = "select phid from products where productid = '".  $_REQUEST["id"] ."' ";

            $result = $conn->query($query);

            $record = $result->fetch_assoc();

            $pid = $record["phid"];

            $query = "select P.pname,P.pprice,P.pquant from products P inner join Cart C on P.phid = C.productid where C.productid=". $pid ." and userid = ". $uid ." ";

            $result = $conn->query($query);

            $record = $result->fetch_assoc();

            $this->name = $record["pname"];
            $this->quan = (float)$record["pquant"];
            $this->price = (float)$record["pprice"];

            $this->calculateAmount();

        }

        public function calculateAmount(){

            $this->amount = $this->price * $this->quan;

        }

    }

    $obj = new Bill();

    $obj->doTasks();

    
    echo "<div class=container><h3><table align=center border=2px solid white>
        <h1>Your Bill Is </h1><br>
            <tr><td> Product </td> <td> Details </td> </tr>
            <tr><td> Name </td> <td> $obj->name </td> </tr>
            <tr><td> Quantity</td> <td> $obj->quan </td> </tr>
            <tr><td> Price</td> <td> $obj->price </td> </tr>
            <tr><td> Amount </td> <td> $obj->amount </td> </tr>
        </table></h3></div>";



?>