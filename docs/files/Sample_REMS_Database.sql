-- CMS For Organisations
-- Link: https://github.com/K-Kraken/REMS-For-Organisations
-- --------------------------------------------------------------
-- Name: SQL Dump
-- Made on version 4.9.1
-- Generation Time: Apr 09, 2020 at 09:13 AM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.11
--

--
-- Database: `main_rems_database` represented by $MainDB in the secrets.php
--

DELIMITER $$
--
-- Procedures
--
CREATE PROCEDURE `AddUser` (IN `pLogin` VARCHAR(40), IN `pPassword` VARCHAR(255), IN `pName` VARCHAR(80), IN `pEmail` VARCHAR(255))  BEGIN
	INSERT INTO login (LoginName, PasswordHash, FullName, Email) VALUES(pLogin, SHA(pPassword), pName, pEmail);
END$$

CREATE PROCEDURE `CheckUser` (IN `pUser` VARCHAR(255), IN `pPassword` VARCHAR(255))  BEGIN
	SELECT COUNT(*) as code FROM login WHERE LoginName=pUser && PasswordHash=SHA(pPassword);
END$$

CREATE PROCEDURE `enterLog` (IN `pUser` VARCHAR(50), IN `pLog` VARCHAR(255)) NO SQL
    COMMENT 'Logs User Actions'
BEGIN
INSERT INTO logging (userid, log) VALUES (pUser, pLog);
END$$

CREATE PROCEDURE `GetUser` (IN `pEmail` VARCHAR(255))  NO SQL
BEGIN
SELECT LoginName as code from login where Email=pEmail;
END$$

CREATE FUNCTION `ForgotPasswordHash` (`mailid` VARCHAR(255)) RETURNS VARCHAR(255) CHARSET utf8mb4 NO SQL
BEGIN	
SELECT NOW() INTO @Now;
SELECT id from login where Email=mailid INTO @id;
SELECT sha(concat(id,LoginName,PasswordHash,Email,@Now)) from login where Email=mailid INTO @Hashstring;
INSERT INTO forgot_password VALUES (@Hashstring,@id,@Now);
RETURN @Hashstring;
END$$

CREATE FUNCTION `GetUserName` (`gen` VARCHAR(255)) RETURNS VARCHAR(255) CHARSET utf8mb4 NO SQL
BEGIN
SELECT id from forgot_password where gen_key=gen INTO @id;
SELECT LoginName from login where id=@id into @UserName;
RETURN @UserName;
END$$

CREATE FUNCTION `PasswordLinkVerification` (`gen` VARCHAR(255)) RETURNS INT(11) NO SQL
BEGIN 
    SELECT times FROM forgot_password where gen_key=gen INTO @pwd_time;
    SELECT TIMESTAMPDIFF(SECOND, @pwd_time, NOW()) INTO @Time_diff;
    return @Time_diff;
END$$


CREATE PROCEDURE `SetPassword` (IN `Hashstring` VARCHAR(255), IN `Password` VARCHAR(255))  NO SQL
BEGIN
	SELECT @ident := id from forgot_password where gen_key=Hashstring;
    UPDATE login SET PasswordHash=sha(Password) WHERE id=@ident;
    DELETE FROM forgot_password where gen_key=Hashstring;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
-- Certicates generated are stored here
--

CREATE TABLE `certificates` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `regno` varchar(255) DEFAULT NULL,
  `dept` varchar(255) DEFAULT NULL,
  `year` int(10) DEFAULT NULL,
  `section` varchar(10) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `cert_link` varchar(255) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `college` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(255) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `isInter` tinyint(1) NOT NULL COMMENT 'True if it''s an inter-college event'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `logging`
--

CREATE TABLE `logging` (
  `id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `userid` varchar(50) NOT NULL COMMENT 'Username',
  `log` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Logs user activity';

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `user` varchar(50) NOT NULL,
  `message` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL COMMENT 'Changes the icon according to type',
  `clickURL` varchar(255) NOT NULL DEFAULT '#' COMMENT 'Redirects to the URL if this alert is clicked'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `LoginName` varchar(40) NOT NULL COMMENT 'Hash of the Password',
  `PasswordHash` varchar(255) NOT NULL COMMENT 'Hash of the Password',
  `Email` varchar(255) NOT NULL,
  `FullName` varchar(80) DEFAULT NULL,
  `IsAdmin` tinyint(1) DEFAULT 0 COMMENT 'True, user needs admin privileges',
  `FirstName` varchar(40) DEFAULT NULL COMMENT 'First Name of the user',
  `LastName` varchar(40) DEFAULT NULL COMMENT 'Last Name of the user',
  `Address` varchar(255) DEFAULT NULL COMMENT 'Address',
  `Phno` varchar(40) DEFAULT NULL COMMENT 'phone Number of User',
  `Signature` varchar(255) DEFAULT NULL COMMENT 'Email Signature',
  `imgsrc` varchar(255) DEFAULT '../image2.png' COMMENT 'Profile Image URL'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping default `admin` user for table `login`
-- Username: admin
-- Password: admin
--

INSERT INTO `login` (`id`, `LoginName`, `PasswordHash`, `Email`, `FullName`, `IsAdmin`, `FirstName`, `LastName`, `Address`, `Phno`, `Signature`, `imgsrc`) VALUES
(3, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'test@test.com', 'admin', 1, NULL, NULL, NULL, NULL, NULL, '../image2.png');

-- --------------------------------------------------------

--
-- Table structure for table `forgot_password`
--

CREATE TABLE `forgot_password` (
  `gen_key` varchar(255) NOT NULL,
  `id` int(11) NOT NULL DEFAULT current_timestamp(),
  `times` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Indexes for table `forgot_password`
--
ALTER TABLE `forgot_password`
  ADD PRIMARY KEY (`gen_key`);


--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logging`
--
ALTER TABLE `logging`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `LoginName` (`LoginName`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `FullName` (`FullName`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`);


--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logging`
--
ALTER TABLE `logging`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;



