SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `messages` (
  `msg_id` int(11) NOT NULL,
  `incoming_msg_id` int(255) NOT NULL,
  `outgoing_msg_id` int(255) NOT NULL,
  `msg` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `unique_id` int(255) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `role` int(11) NOT NULL --  1 : user, 2 : agent, 3 : admin
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`user_id`, `unique_id`, `fname`, `lname`, `email`, `password`, `status`, `role`) VALUES
(1, 455158, 'FirstName1', 'LastName1', 'user1@example.com', 'password1', 'Offline now', 2),
(2, 665004, 'FirstName2', 'LastName2', 'user2@example.com', 'password2', 'Offline now', 1),
(3, 566653, 'FirstName3', 'LastName3', 'user3@example.com', 'password3', 'Offline now', 2),
(4, 781619, 'FirstName4', 'LastName4', 'user4@example.com', 'password4', 'Offline now', 2),
(5, 426344, 'FirstName5', 'LastName5', 'user5@example.com', 'password5', 'Offline now', 2),
(6, 650551, 'FirstName6', 'LastName6', 'user6@example.com', 'password6', 'Offline now', 2),
(7, 453126, 'FirstName7', 'LastName7', 'user7@example.com', 'password7', 'Offline now', 2),
(8, 624551, 'FirstName8', 'LastName8', 'user8@example.com', 'password8', 'Offline now', 1),
(9, 433572, 'FirstName9', 'LastName9', 'user9@example.com', 'password9', 'Offline now', 2),
(10, 480440, 'FirstName10', 'LastName10', 'user10@example.com', 'password10', 'Offline now', 2),
(11, 338438, 'FirstName11', 'LastName11', 'user11@example.com', 'password11', 'Offline now', 2),
(12, 490361, 'FirstName12', 'LastName12', 'user12@example.com', 'password12', 'Offline now', 1),
(13, 344190, 'FirstName13', 'LastName13', 'user13@example.com', 'password13', 'Offline now', 1),
(14, 937490, 'FirstName14', 'LastName14', 'user14@example.com', 'password14', 'Offline now', 2),
(15, 459484, 'FirstName15', 'LastName15', 'user15@example.com', 'password15', 'Offline now', 2),
(16, 816940, 'FirstName16', 'LastName16', 'user16@example.com', 'password16', 'Offline now', 1),
(17, 926189, 'FirstName17', 'LastName17', 'user17@example.com', 'password17', 'Offline now', 2),
(18, 614011, 'FirstName18', 'LastName18', 'user18@example.com', 'password18', 'Offline now', 2),
(19, 803460, 'FirstName19', 'LastName19', 'user19@example.com', 'password19', 'Offline now', 1),
(20, 749874, 'FirstName20', 'LastName20', 'user20@example.com', 'password20', 'Offline now', 1),
(21, 313131, 'FirstName21', 'LastName21', 'user21@example.com', 'password21', 'Offline now', 2),
(22, 425557, 'FirstName22', 'LastName22', 'user22@example.com', 'password22', 'Offline now', 2),
(23, 297319, 'FirstName23', 'LastName23', 'user23@example.com', 'password23', 'Offline now', 1),
(24, 387196, 'FirstName24', 'LastName24', 'user24@example.com', 'password24', 'Offline now', 1),
(25, 427391, 'FirstName25', 'LastName25', 'user25@example.com', 'password25', 'Offline now', 1);


ALTER TABLE `messages`
  ADD PRIMARY KEY (`msg_id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

ALTER TABLE `messages`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
