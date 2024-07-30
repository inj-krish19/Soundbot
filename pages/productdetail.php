<link rel="icon" type="image/x-icon" href="./images/logos/productdetail.png">
<?php

    session_start();

    include_once("scripts/connection/connection.php");

    if( !isset($_SESSION["user"]) ){
        $_SESSION["user"] = "guest";
    }
    
    if( 
        $_SESSION["user"] == "guest"    &&
        isset( $_REQUEST["id"] )
    ){
        
        $query = "select pname from products where productid = '". $_REQUEST["id"] ."' ";

        $result = mysqli_query($conn,$query);

        $record = mysqli_fetch_assoc($result);

        $title = $record["pname"];
    
    }else{
        $title = "Product Details";
    }
    
    ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> <?php echo $title; ?> </title>
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
        padding : 1px;
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

    .container{
        height : 88%;
        width : 100%;
        display : flex;
        flex-wrap : wrap;
        flex-direction : column; 
    }

    .image{
        display : flex;
        align-items : center;
        justify-content : center;
        margin : 1%;
        min-width : 400px;
        max-height : 600px;
    }

    .container > .image > img{
        margin : 0 1% 1% 1%;
        height : 75%;
        min-width : 300px;
        width : auto;
        /* mix-blend-mode : multiply; */
    }
    
    .content{
        margin : 1%;
        width : 50%;
        min-height : 600px;
    }

    .content > h1{
        text-align : left;
        font-size : 2.5rem;
    }

    input{
        width : 50px;
        height : 50px;
        text-align : center;
    }

    button.left{
        margin-left : 15px;
        text-align : center;
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

        .container{
            flex-direction : row; 
        }

        .content{
            width : 100%;
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
    <div class="container">
    <?php
        if( 
            isset($_REQUEST["id"])
        ){

            $query = "select I.imageurl from products P inner join images I on I.uspid = P.productid where productid = '". $_REQUEST["id"] ."' ";

            $result = mysqli_query($conn,$query);

            $record = mysqli_fetch_assoc($result);

            $imageLink = $record["imageurl"];

            echo "<div class=image><img src=". './'.$imageLink ." ></div>";

        }else{
            echo "<div class=image><img src=images/logos/Soundbot.png ></div>";
        }

    ?>

    <?php

        if( 
            isset($_REQUEST["id"])
        ){
            $query = "select pname,pquant,pprice,ptype,prgb,pcolor,pcategory,pwareadd,pwarecont,pdescription from products where productid = '". $_REQUEST["id"] ."' ";

            $result = mysqli_query($conn,$query);

            $record = mysqli_fetch_assoc($result);

            $title = $record["pname"];
            $description = $record["pdescription"];
            $price = $record["pprice"];

            $type = "Type : " . $record["ptype"];
            $color = "Color : " . $record["pcolor"];
            $rgb = "RGB : " . $record["prgb"];
            $category = "Category : " . $record["pcategory"];
            $type = "Type : " . $record["ptype"];
            $quantity = $record["pquant"];

        }else{
            $title = "This Product Is Removed By Owner";
            $description = "This Product Is Removed By Owner";
            $price = "9999";
        }

        echo "<script> localStorage.quantity = ". $quantity ."; </script>";

        $quantity = 1;

    ?>

        <div class="content">
            <h1> <?php echo $title;  ?> </h1>
            <h2> <?php echo "₹ " . $price;  ?> </h2>
            <h2> <?php echo $description;  ?> </h2>
            <h4> <?php echo $type;  ?> </h4>
            <h4> <?php echo $color;  ?> </h4>
            <h4> <?php echo $rgb;  ?> </h4>
            <h4> <?php echo $category;  ?> </h4>
            
            <form method="post">
            
                <h2>
                    <label for="quantity">Quantity : </label>
                    <button class="left" onclick="if( document.getElementsByName('quantity')[0].value > 1 ){  document.getElementsByName('quantity')[0].value--; }else{ document.getElementsByName('quantity')[0].value = 1; } ;" > - </button>
                    <input type="number" name="quantity" value=<?php echo $quantity ?>>
                    <button onclick="if( parseInt(document.getElementsByName('quantity')[0].value) + 1 < parseInt(localStorage.quantity) && parseInt(document.getElementsByName('quantity')[0].value) + 1 < 100 && parseInt(document.getElementsByName('quantity')[0].value) >= 1 ){ document.getElementsByName('quantity')[0].value++; }else{ document.getElementsByName('quantity')[0].value = 1; }" > + </button>
                </h2>

                <h2>
                    <label for="payment">Payment Method : </label>
                    <select name="payment" id="payment">
                        <option name="payment">Select Payment Method</option>
                        <option name="payment">Cash</option>
                        <option name="payment">Net Banking</option>
                    </select>
                </h2>

           </form>

            <button onclick=window.location.href='addincart.php?category=<?php echo $_REQUEST["category"] . "&id=" . $_REQUEST["id"]; ?>' >Add To Cart</button>

        </div>

    </div>
</body>

</html>