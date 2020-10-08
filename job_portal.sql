-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 19, 2020 at 12:31 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `job_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_login`
--

CREATE TABLE `admin_login` (
  `id` int(11) NOT NULL,
  `admin_email` varchar(100) NOT NULL,
  `admin_pass` varchar(100) NOT NULL,
  `admin_username` varchar(100) NOT NULL,
  `first_name` varchar(111) NOT NULL,
  `last_name` varchar(111) NOT NULL,
  `admin_type` varchar(111) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_login`
--

INSERT INTO `admin_login` (`id`, `admin_email`, `admin_pass`, `admin_username`, `first_name`, `last_name`, `admin_type`) VALUES
(1, 'bktripathi1977@gmail.com', '123456', 'user1', 'Krishna', 'Tripathi', '1'),
(8, 'krishna@gmail.com', '12345', 'user2', 'Lovkush', 'Tripathi', '2'),
(21, 'rahul@gmail.com', '1', 'rahul123', 'Rahul', 'Mongia1', '2'),
(23, 'azahar@gmail.com', '7071', 'azahar7071', 'Azahar', 'Shivani', '2');

-- --------------------------------------------------------

--
-- Table structure for table `admin_type`
--

CREATE TABLE `admin_type` (
  `id` int(111) NOT NULL,
  `admin` varchar(111) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_type`
--

INSERT INTO `admin_type` (`id`, `admin`) VALUES
(1, 'Super Admin'),
(2, 'Customer Admin');

-- --------------------------------------------------------

--
-- Table structure for table `all_jobs`
--

CREATE TABLE `all_jobs` (
  `job_id` int(100) NOT NULL,
  `customer_email` varchar(111) NOT NULL,
  `job_title` varchar(111) NOT NULL,
  `des` varchar(111) NOT NULL,
  `country` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `keyword` varchar(111) NOT NULL,
  `category` varchar(111) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `all_jobs`
--

INSERT INTO `all_jobs` (`job_id`, `customer_email`, `job_title`, `des`, `country`, `state`, `city`, `keyword`, `category`) VALUES
(2, 'krishna@gmail.com', 'Software Engineer', 'python web develper', 'United States', 'Alaska', 'Cantwell', '', ''),
(5, 'krishna@gmail.com', 'JAVA- Front-End and Back-End Developer ', 'Dear Participants Greeting from www.web_java/system Engineer.com', 'India', 'Karnataka', 'Bangalore', '', ''),
(6, 'rishab@gmail.com', 'system Analyst', 'python web develper', 'United States', 'Alaska', 'Cantwell', '', ''),
(7, 'azahar@gmail.com', 'Software  Development', 'Open Source Interactive Developer', 'Canada', 'Ontario', 'Brantford', '', ''),
(9, 'bk@gmail.com', 'Urgent Requirement of PHP Developer  1 To  4 Years', 'Dear Candidates \r\nGreeting From \r\nReliance Industries, Services', 'India', 'Rajasthan', 'Jaipur', 'System Analyst', '5'),
(10, 'bk@gmail.com', 'Graphics Designer', 'Only for Fresher', 'Australia', 'Northern Territory', 'Birdum', 'abcd', '4'),
(11, 'rahul@gmail.com', 'Project Management', 'system programmer', 'India', 'Uttar Pradesh', 'Lucknow', 'System1', '1'),
(12, 'bk@gmail.com', 'Full Stack Developer', 'Two year work experience', 'India', 'Bihar', 'Begusarai', 'Data Management', '6'),
(13, 'bk@gmail.com', 'Mu-Sigma', 'developer112233', 'India', 'Delhi', 'Delhi', 'sigma', '7'),
(14, 'bk@gmail.com', 'Urgent Requirement of  Product Developer  1 To  2 Years', 'Dear  candidate\r\nGreeting from \r\nBuyjues Services', 'India', 'Tamil Nadu', 'Chennai', 'product developer', '6'),
(15, 'azahar@gmail.com', 'Computer Scientist', 'Urgent Requirement of computer scientist \r\nwork Experience 1 to 2 Years', 'India', 'Tamil Nadu', 'Chennai', 'Scientist', '1'),
(16, 'azahar@gmail.com', 'SQL Developer', 'Work Experience Required: 3 to 4 Years\r\nSalary: 6 LPA', 'United Kingdom', 'England', 'Aberford', 'sql', '4');

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `company_id` int(111) NOT NULL,
  `company_name` varchar(111) NOT NULL,
  `des` varchar(111) NOT NULL,
  `admin` varchar(111) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`company_id`, `company_name`, `des`, `admin`) VALUES
(2, 'Accenture', 'desktop application    ', 'bktripathi1977@gmail.com'),
(28, 'Infosys', 'NYSE listed global consulting and IT services                                                                  ', 'krishna@gmail.com'),
(29, 'AMAZON', 'E-commerce, Cloud Computing, Digital Streaming, and Artificial Intelligence                                    ', 'bk@gmail.com'),
(30, 'google', 'Online Advertising Technologies, A Search Engine, Cloud Computing, Software, and Hardware                      ', 'rahul@gmail.com'),
(32, 'Buyjues', ' Marketing Manager & Product Developer   2 to 3 Years                   ', 'azahar@gmail.com'),
(33, 'BK-Photography Pvt. Ltd.', 'Only for fresher', 'azahar@gmail.com'),
(34, 'Kaar Technology', 'Software Developer                   ', 'krishna@gmail.com'),
(35, 'TATA CONSULTANCY  SERVICE', 'Indian Multinational Conglomerate Tata Group\r\nBusiness and Consulting Solutions ', 'krishna@gmail.com'),
(36, 'SUN PHARMA ', 'Indian Multinational Pharmaceutical Enterprise\r\nDiverse range of  Pharmaceutical  Products', 'rahul@gmail.com'),
(37, 'State Bank of India', 'Banking and Financial Services', 'bk@gmail.com'),
(38, 'Reliance Industries Limited', 'Lucrative Industries including Energy, Petrochemicals, Textiles, Natural Resources, Retail and Telecommunicatio', 'azahar@gmail.com'),
(39, 'APPLE Technology Company', 'Designs, Develops, and Sells Consumer Electronics, Computer Software, and Online Services.', 'krishna@gmail.com'),
(40, 'FACEBOOK', 'Social Networking Service', 'rahul@gmail.com'),
(41, 'MindTree', 'Indian multinational information technology and outsourcing company\r\nAnnual Revenue of $1 Billion.', 'bk@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `jobseeker`
--

CREATE TABLE `jobseeker` (
  `id` int(111) NOT NULL,
  `email` varchar(111) NOT NULL,
  `password` varchar(111) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `mobile_number` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jobseeker`
--

INSERT INTO `jobseeker` (`id`, `email`, `password`, `first_name`, `last_name`, `dob`, `mobile_number`) VALUES
(2, 'bktripathi1977@gmail.com', '1212', 'Krishna', 'Tripathi', '2020-01-01', '09450201977 '),
(3, 'vtu8687@veltechuniv.edu.in', '8687', 'Lovkush', 'Tripathi', '2020-04-01', '1234567890 '),
(4, 'krishna@gmail.com', '12345', 'Lovkush', 'Tripathi', '1999-12-25', '7397321073 '),
(5, 'sagar@gmail.com', 'sagar1', 'Sagar', 'Bhardwaj', '2020-04-24', '9023232322 '),
(6, 'ip@gmail.com', 'ip1', 'IP', 'CLASS', '2020-04-17', '9876543210 '),
(7, 'Roshani@gmail.com', 'roshani1', 'Roshani', 'Chikhudiya', '2020-03-30', '9636363636 '),
(8, 'rishab@gmail.com', 'r1', 'RISHAB', 'RICHARD', '2020-04-15', '9654256969 '),
(10, 'richard@gmail.com', '1', 'Rishab', 'Richard', '2020-05-27', '9450201977 ');

-- --------------------------------------------------------

--
-- Table structure for table `job_apply`
--

CREATE TABLE `job_apply` (
  `id` int(111) NOT NULL,
  `first_name` varchar(111) NOT NULL,
  `last_name` varchar(111) NOT NULL,
  `dob` date NOT NULL,
  `file` date NOT NULL,
  `id_job` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `job_apply`
--

INSERT INTO `job_apply` (`id`, `first_name`, `last_name`, `dob`, `file`, `id_job`, `email`, `phone`) VALUES
(1, 'Lovkush ', 'Tripathi', '2020-04-19', '0000-00-00', '10', 'vtu8687@veltechuniv.edu.in', '0'),
(3, 'Lovkush', 'Singhaniya', '2020-04-19', '0000-00-00', '9', 'krishna@gmail.com', '0'),
(4, 'Mahesh', 'Shukla', '2020-04-24', '0000-00-00', '9', 'coderlktripathi1073@gmail.com', '2147483647'),
(5, 'Azahar', 'Ansari', '2020-04-10', '0000-00-00', '12', 'azahar@gmail.com', '2147483647'),
(6, 'Manoj', 'Sinha', '2020-04-04', '0000-00-00', '12', 'manoj@gmail.com', '9455226633'),
(31, 'Sagar ', 'Bhardwaj', '2020-04-24', '0000-00-00', '6', 'sagar@gmail.com', '9023232322'),
(33, 'Krishna', 'Rajan', '2020-04-23', '0000-00-00', '10', 'rajan@gmail.com', '9450201977'),
(34, 'BALKRISHNA ', 'TRIPATHI', '2020-04-10', '0000-00-00', '14', 'lkrishna@gmail.com', '9450201977'),
(36, 'Kishan ', 'Kanhaiya', '2020-04-13', '0000-00-00', '10', 'kanhaiya@gmail.com', '9415784692'),
(37, 'Roshani', 'Chikhudiya', '2020-03-30', '0000-00-00', '5', 'Roshani@gmail.com', '9636363636'),
(38, 'Mohit', 'yaduvanshi', '2020-02-24', '0000-00-00', '2', 'mohit123@gmail.com', '9232323232'),
(39, 'Rahul ', 'Mongia', '2020-05-01', '0000-00-00', '9', '21951.rahul@gmail.com', '09450201977');

-- --------------------------------------------------------

--
-- Table structure for table `job_category`
--

CREATE TABLE `job_category` (
  `id` int(111) NOT NULL,
  `category` varchar(111) NOT NULL,
  `des` varchar(111) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `job_category`
--

INSERT INTO `job_category` (`id`, `category`, `des`) VALUES
(1, 'php', '                     this is for fresher                   '),
(4, 'Web Developer', 'Back end web developer'),
(5, 'System Analyst', '                     Interactive Web Development                   '),
(6, 'Information Technology', 'System Engineer'),
(7, 'wordpress', 'wordpress123456');

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `id` int(111) NOT NULL,
  `img` varchar(111) NOT NULL,
  `name` varchar(111) NOT NULL,
  `dob` varchar(111) NOT NULL,
  `number` varchar(111) NOT NULL,
  `email` varchar(111) NOT NULL,
  `user_email` varchar(111) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`id`, `img`, `name`, `dob`, `number`, `email`, `user_email`) VALUES
(13, 'my_img.jpeg', 'Krishna Tripathi', '', '', '', 'krishna@gmail.com'),
(14, 'my_img.jpeg', 'L K TRIPATHI', '', '', '', 'bktripathi1977@gmail.com'),
(15, 'roshani.jpeg', 'Roshani Chikhudiya', '2020-03-30', '9636363636', 'Roshani@gmail.com', 'Roshani@gmail.com'),
(16, 'my_img.jpeg', 'Krishna Tripathi', '', '', '', 'bk@gmail.com'),
(17, 'my_img.jpeg', '', '', '', '', 'richard@gmail.com'),
(18, 'my_img.jpeg', 'Krishna Tripathi', '2020-05-06', '09450201977', 'krishna987@gmail.com', 'rahul@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_login`
--
ALTER TABLE `admin_login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `all_jobs`
--
ALTER TABLE `all_jobs`
  ADD PRIMARY KEY (`job_id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `jobseeker`
--
ALTER TABLE `jobseeker`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `job_apply`
--
ALTER TABLE `job_apply`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_category`
--
ALTER TABLE `job_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_login`
--
ALTER TABLE `admin_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `all_jobs`
--
ALTER TABLE `all_jobs`
  MODIFY `job_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `company_id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `jobseeker`
--
ALTER TABLE `jobseeker`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `job_apply`
--
ALTER TABLE `job_apply`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `job_category`
--
ALTER TABLE `job_category`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
