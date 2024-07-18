/*	Run It Manually		*/

use Soundbot;

delimiter //
create trigger generate_userid
before insert on users
for each row 
begin
	declare next_userid int;
	select ifnull(max(cast(substring(userid, 4) as unsigned)), 0) + 1 into next_userid from users where userid like 'USR%';
	set new.userid = concat('USR', lpad(next_userid, 6, '0'));
end;//
delimiter ;

delimiter //
create trigger calculate_userage_insert
before insert on users
for each row 
begin
	set new.uage = timestampdiff(year, new.ubdate, CURDATE());
end;//
delimiter ;

delimiter //
create trigger calculate_userage_update
before update on users
for each row 
begin
	set new.uage = timestampdiff(year, new.ubdate, CURDATE());
end;//
delimiter ;

delimiter //
create trigger generate_productid
before insert on products
for each row 
begin
	declare next_productid int;
	select ifnull(max(cast(substring(productid, 4) as unsigned)), 0) + 1 into next_productid from products where productid like 'PRD%';
	set new.productid = concat('PRD', lpad(next_productid, 6, '0'));
end;//
delimiter ;

delimiter //
create trigger generate_imageid
before insert on images
for each row 
begin
	declare next_imageid int;
	select ifnull(max(cast(substring(imageid, 4) as unsigned)), 0) + 1 into next_imageid from images where imageid like 'IMG%';
	set new.imageid = concat('IMG', lpad(next_imageid, 6, '0'));
end;//
delimiter ;

delimiter //
create trigger update_cart_product_insert
before insert on cart 
for each row  
begin
	set new.price = (select pprice from products where phid = new.productid);
    set new.warecont = (select pwarecont from products where phid = new.productid);
    set new.wareadd = (select pwareadd from products where phid = new.productid);
end;//
delimiter ;

delimiter //
create trigger update_cart_customer_insert
before insert on cart 
for each row  
begin
	set new.custadd = (select uaddress from users where uhid = new.userid);
    set new.custcont = (select ucontact from users where uhid = new.userid);
end;//
delimiter ;

delimiter //
create trigger update_cart_status_insert
before insert on cart 
for each row  
begin
	
    set paystatus = "Pending";
    
    set paystatus = "Done";

end;//
delimiter ;

delimiter //
create trigger update_cart_product_update
after update on cart 
for each row  
begin
	set new.price = (select pprice from products where phid = new.productid);
    set new.warecont = (select pwarecont from products where phid = new.productid);
    set new.wareadd = (select pwareadd from products where phid = new.productid);
end;//
delimiter ;

delimiter //
create trigger update_cart_customer_update
after update on cart 
for each row  
begin
	set new.custadd = (select uaddress from users where uhid = new.userid);
    set new.custcont = (select ucontact from users where uhid = new.userid);
end;//
delimiter ;

delimiter //
create trigger update_cart_status_update
after update on cart 
for each row  
begin

    set paystatus = "Pending";
    
    set paystatus = "Done";

end;//
delimiter ;