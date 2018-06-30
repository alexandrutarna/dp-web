


CREATE TABLE `booking` (
  `user_id` int(11) NOT NULL,
  `start_addr` int(3) DEFAULT NULL,
  `stop_addr` int(3) DEFAULT NULL,
  `passengers` int(1) DEFAULT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



insert into booking (user_id, start_addr, stop_addr, passengers, id) 
values 
    (111, 'AA', 'BB', 2, 3), 
    (222, 'BB', 'DD', 1, 4), 
    (333, 'DD', 'EE', 5), 
    (444, 'AL', 'BZ', 6);


INSERT INTO `booking`(`user_id`, `start_addr`, `stop_addr`, `id`) 
VALUES (444, 	'AL', 'BZ',	6)


DELETE FROM `booking` WHERE user_id = 444


SELECT * FROM `booking` WHERE start_addr >= 'AL' and stop_addr <= 'DD' 


user_id, start_addr, stop_addr, passengers

    (111, 'AA', 'BB', 2),   
    (222, 'BB', 'DD', 1), 
    (333, 'DD', 'EE', 1), 
    (444, 'AL', 'BZ', 1);

max capacity = 4

'AA' -> 'BB': total 2; user u1 (2 passengers)
'BB' -> 'DD': total 3; user u1 (2 passengers), user u2 (1 passenger)
'DD' -> 'EE': total 2; user u2 (1 passenger), user u3 (1 passenger)





INSERT INTO `bookings`(`user_id`, `departure`, `destination`, `nr_passengers`) 
VALUES 
('u1@p.it', 'AA', 'DD', 2),
('u2@p.it', 'BB', 'EE', 1),
('u3@p.it', 'DD', 'EE', 1);


INSERT INTO `bookings`(`user_id`, `departure`, `destination`, `nr_passengers`) 
VALUES 
(111, 'AA', 'DD', 2),
(222, 'BB', 'EE', 1),
(333, 'DD', 'EE', 1);

INSERT INTO `bookings`(`user_id`, `departure`, `destination`, `nr_passengers`) 
VALUES (555, 'BA', 'DF', 2)


SELECT * FROM `bookings` b1 
join `bookings`b2

SELECT * FROM `bookings` b1
WHERE b1.departure <= 'AM'

SELECT sum(nr_passengers) FROM `bookings` b1
WHERE b1.departure <= 'AM'
group by user_id


SELECT * FROM `bookings` b1 
join `bookings`b2
order by b1.departure, b2.destination 



SELECT * FROM `bookings` b1 
join `bookings`b2 
where b1.departure < b2.departure
group by b1.booking_id, b2.booking_id, b1.user_id, b2.user_id
order by b1.departure, b2.destination



