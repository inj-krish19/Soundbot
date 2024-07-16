<?php

    require_once("connection.php");

    $query = "create database if not exists $databaseName";
    
    $result = mysqli_query($conn,$query);
    
    mysqli_select_db($conn,$databaseName);

    
    $query = "create table if not exists users( 
        uhid int 
        auto_increment,
        userid varchar(9),
        
        uemail text(75) not null, 
        uname text(50) not null, 
        upass text(500) not null,

        ubdate date
        default curdate(),
        uage int 
        check(uage > 0),

        uchoice enum('Headphone','Earbud','Earphone','All') not null,
        ugender enum('Male','Female','Others') default 'Male' not null,
        ucontact text(10) not null,

        upayment text(12) not null,
        uaddress text(250) not null,

        primary key(uhid,userid)

    ) ";
        
    $result = mysqli_query($conn,$query);

    $query = "create table if not exists products( 
        phid int 
        auto_increment,
        productid varchar(9)
        unique key,
        
        pname text(50) not null, 
        pquant int not null, 
        pprice real(8,2) not null,

        ptype enum('Wired','Wireless') not null,
        prgb enum('Yes','No') not null,
        pcolor enum('Black','White','Others') not null,
        pcategory enum('Headphone','Earbud','Earphone') not null,
        
        pwareadd text(250) not null,
        pwarecont text(10) not null,
        pdescription text(250) default '',

        primary key(phid,productid)

    ) ";
        
    $result = mysqli_query($conn,$query);
    
    $query = "create table if not exists cart( 

        userid int,
        productid int,
        
        quant int , 
        price float(8,2) ,
        amount float(10,2) ,
        
        orderstatus enum('Cart','Purchase') ,
        paystatus enum('Done','Pending') ,
        paymethod enum('Cash','Online') ,
        
        warecont text(10) ,
        wareadd text(250) ,
        custcont text(10) ,
        custadd text(250) ,
        
        primary key(userid,productid),
        foreign key (userid) references users(uhid),
        foreign key (productid) references products(phid)

    ) ";
        
    $result = mysqli_query($conn,$query);

    // usp id works as foreign key

    $query = "create table if not exists images(
        
        ihid int
        auto_increment,
        imageid varchar(9),
        uspid varchar(9)
        unique key,
        imageurl text(50),

        imageasp text(5),
        imageheight int,
        imagewidth int,
        imagetype enum('User','Product'),

        primary key(ihid,imageid)
            
    ) ";

    // foreign key (uspid) references users(userid),
    // foreign key (uspid) references products(productid)

    $result = mysqli_query($conn,$query);
    
    $query = "create table if not exists deletedusers( 
        duhid int 
        auto_increment,
        duserid varchar(9),
        
        duemail text(75) not null, 
        duname text(50) not null, 
        dupass text(500) not null,

        duage int 
        default 18
        check (duage > 0),
        dugender enum('Male','Female','Others') default 'Male' not null,
        ducontact text(10) not null,

        dupayment text(12) not null,
        duaddress text(250) not null,

        primary key(duhid,duserid),
        foreign key (duhid) references users(uhid)

    ) ";
        
    $result = mysqli_query($conn,$query);

    $query = "create table if not exists deletedproduts( 
        dphid int 
        auto_increment,
        dproductid varchar(9),
        
        dpname text(50) not null, 
        dpquant int not null, 
        dpprice real(8,2) not null,

        dpcategory enum('Wired','Wireless') not null,
        dprgb enum('Yes','No') not null,
        dpcolor enum('Black','White','Others') not null,
        dptype enum('Headphone','Earbuds','Earphone') not null,
        
        dpwareadd text(250) not null,
        dpwarecont text(10) not null,
        dpdescription text(250) default '',

        primary key(dphid,dproductid),
        foreign key (dphid) references products(phid)

    ) ";
        
    $result = mysqli_query($conn,$query);

    $query = "create table if not exists feedback( 
        
        userid int
        primary key,
        feed_date date
        default curdate(),
        description text(250) default '',

        foreign key (userid) references users(uhid)

    ) ";
        
    $result = mysqli_query($conn,$query);
  
    $query = "create table if not exists wishlist( 

        userid int,
        productid int,
        
        orderstatus enum('Available','Not Available') not null,
        favsatatus enum('Starred','Not Starred') not null default 'Not Starred',
        
        primary key(userid,productid),
        foreign key (userid) references users(uhid),
        foreign key (productid) references products(phid)

    ) ";
        
    $result = mysqli_query($conn,$query);

    $query = "create table if not exists trackorder( 

        userid int,
        productid int,
        
        orderstatus enum('Pending','Sended','Arriving','Reached') not null,

        primary key(userid,productid),
        foreign key (userid) references users(uhid),
        foreign key (productid) references products(phid)

    ) ";
        
    $result = mysqli_query($conn,$query);

    

    /*  triggers queries    

    $query = "
        delimiter //
        create trigger generate_guestid 
        before insert on guest 
        for each row begin 
            declare next_guestid int; 
            select ifnull(max(cast(substring(guestid, 4) as unsigned)), 0) + 1 into next_guestid from guest where guestid like 'GUE%'; 
            set new.guestid = concat('GUE', lpad(next_guestid, 6, '0')); 
        end;//
        delimiter ; ";

    $result = mysqli_query($conn, $query);


    

    $query = "delimiter //

        create trigger generate_userid
        before insert on users
        for each row
        begin
            declare next_userid int;
            select ifnull(max(cast(substring(userid, 4) as unsigned)), 0) + 1 into next_userid from users where userid like 'USR%';
            set new.userid = concat('USR', lpad(next_userid, 6, '0'));
        end;
        //

        delimiter ;";

    $result = mysqli_query($conn,$query);


    

    $query = "delimiter //

        create trigger generate_productid
        before insert on products
        for each row
        begin
            declare next_productid int;

            -- Finding the next available number
            select ifnull(max(cast(substring(productid, 4) as unsigned)), 0) + 1 into next_productid from products where productid like 'PRD%';

            -- Formatting the next productid
            set new.productid = concat('PRD', lpad(next_productid, 6, '0'));
        end;
        //

        delimiter ;";

    $result = mysqli_query($conn,$query);


    
    $query = "delimiter //

        create trigger generate_imageid
        before insert on images
        for each row
        begin
            declare next_imageid int;

            -- Finding the next available number
            select ifnull(max(cast(substring(imageid, 4) as unsigned)), 0) + 1 into next_imageid from images where imageid like 'IMG%';

            -- Formatting the next imageid
            set new.imageid = concat('IMG', lpad(next_imageid, 6, '0'));
        end;
        //

        delimiter ;";

    $result = mysqli_query($conn,$query);

    */

?>