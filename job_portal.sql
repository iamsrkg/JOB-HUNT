-- JobHunt database: schema + fictional demo data.
-- Import into an empty database, e.g.:  mysql -u root -e "CREATE DATABASE job_portal" && mysql -u root job_portal < job_portal.sql
--
-- Demo accounts (fictional; change or delete before any real deployment):
--   Super admin   admin@jobhunt.test      / Admin@12345    -> /Admin/admin_login.php
--   Recruiter     talent@acmecloud.test   / Recruit@12345  -> /new-post.php (or /Admin)
--   Recruiter     hr@globexsystems.test   / Recruit@12345
--   Job seeker    jane.doe@example.com    / Seeker@12345   -> /job-post.php
--   Job seeker    ravi.kumar@example.com  / Seeker@12345
-- Passwords are stored as bcrypt hashes (password_hash); plaintext is never stored.

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET NAMES utf8mb4;
START TRANSACTION;

DROP TABLE IF EXISTS `job_apply`, `profile`, `all_jobs`, `company`, `job_category`, `jobseeker`, `admin_login`, `admin_type`;

-- Admin accounts: type 1 = super admin, type 2 = company recruiter
CREATE TABLE `admin_type` (
  `id` int NOT NULL,
  `admin` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `admin_type` (`id`, `admin`) VALUES
(1, 'Super Admin'),
(2, 'Customer Admin');

CREATE TABLE `admin_login` (
  `id` int NOT NULL AUTO_INCREMENT,
  `admin_email` varchar(190) NOT NULL,
  `admin_pass` varchar(255) NOT NULL,
  `admin_username` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `admin_type` varchar(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_email` (`admin_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `admin_login` (`id`, `admin_email`, `admin_pass`, `admin_username`, `first_name`, `last_name`, `admin_type`) VALUES
(1, 'admin@jobhunt.test',    '$2y$10$OZKnJTxmHJXinwRxF1wz6O/9ASas/WVsbH3P2R2iEajKBOFk5toMy', 'admin',   'Site',  'Admin',     '1'),
(2, 'talent@acmecloud.test', '$2y$10$DFUeeshneLst3/C7Axyquu7rm/bO7UQYnu0MIzQ1.tbExJBudEnAu', 'acme',    'Asha',  'Recruiter', '2'),
(3, 'hr@globexsystems.test', '$2y$10$DFUeeshneLst3/C7Axyquu7rm/bO7UQYnu0MIzQ1.tbExJBudEnAu', 'globex',  'Marco', 'Hiring',    '2');

CREATE TABLE `company` (
  `company_id` int NOT NULL AUTO_INCREMENT,
  `company_name` varchar(100) NOT NULL,
  `des` varchar(1000) NOT NULL DEFAULT '',
  `admin` varchar(190) NOT NULL,
  PRIMARY KEY (`company_id`),
  KEY `admin` (`admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `company` (`company_id`, `company_name`, `des`, `admin`) VALUES
(1, 'Acme Cloud',     'Cloud infrastructure and managed Kubernetes for mid-size teams.', 'talent@acmecloud.test'),
(2, 'Globex Systems', 'Payments and banking software for South-East Asia.',              'hr@globexsystems.test');

CREATE TABLE `job_category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category` varchar(100) NOT NULL,
  `des` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `job_category` (`id`, `category`, `des`) VALUES
(1, 'Backend Development', 'APIs, services and databases'),
(2, 'Frontend Development', 'Web and mobile user interfaces'),
(3, 'DevOps & Cloud', 'CI/CD, infrastructure and operations'),
(4, 'Data & Analytics', 'Data engineering and BI'),
(5, 'Quality Assurance', 'Manual and automated testing');

-- category holds job_category.id (kept as varchar for compatibility with older data)
CREATE TABLE `all_jobs` (
  `job_id` int NOT NULL AUTO_INCREMENT,
  `customer_email` varchar(190) NOT NULL,
  `job_title` varchar(150) NOT NULL,
  `des` text NOT NULL,
  `country` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL DEFAULT '',
  `city` varchar(100) NOT NULL DEFAULT '',
  `keyword` varchar(100) NOT NULL DEFAULT '',
  `category` varchar(11) NOT NULL DEFAULT '',
  PRIMARY KEY (`job_id`),
  KEY `customer_email` (`customer_email`),
  KEY `category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `all_jobs` (`job_id`, `customer_email`, `job_title`, `des`, `country`, `state`, `city`, `keyword`, `category`) VALUES
(1, 'talent@acmecloud.test', 'Backend Engineer (Java / Spring Boot)', 'Build and run REST APIs on Kubernetes. PostgreSQL, Redis, Kafka. 3+ years.', 'Thailand', 'Bangkok', 'Bangkok', 'java spring', '1'),
(2, 'talent@acmecloud.test', 'Platform Engineer', 'Own CI/CD pipelines, Terraform and observability for 40+ services.', 'Thailand', 'Bangkok', 'Bangkok', 'kubernetes terraform', '3'),
(3, 'talent@acmecloud.test', 'Frontend Engineer (React)', 'Ship the customer dashboard in React and TypeScript.', 'India', 'Karnataka', 'Bengaluru', 'react typescript', '2'),
(4, 'hr@globexsystems.test', 'Node.js Backend Developer', 'NestJS microservices for card payments. MySQL, RabbitMQ.', 'India', 'Karnataka', 'Bengaluru', 'nodejs nestjs', '1'),
(5, 'hr@globexsystems.test', 'Data Engineer', 'Batch and streaming pipelines feeding the risk models.', 'Singapore', 'Singapore', 'Singapore', 'spark airflow', '4'),
(6, 'hr@globexsystems.test', 'QA Automation Engineer', 'API and UI test automation with Playwright and REST Assured.', 'Remote', '', '', 'playwright', '5');

CREATE TABLE `jobseeker` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(190) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `mobile_number` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `jobseeker` (`id`, `email`, `password`, `first_name`, `last_name`, `dob`, `mobile_number`) VALUES
(1, 'jane.doe@example.com',   '$2y$10$OnqtOgvmZYEeaq8OflXCyeAFeMwOrtyNE4qk/HtFvGcinh0Fdw/Xa', 'Jane', 'Doe',   '1996-04-12', '0000000001'),
(2, 'ravi.kumar@example.com', '$2y$10$OnqtOgvmZYEeaq8OflXCyeAFeMwOrtyNE4qk/HtFvGcinh0Fdw/Xa', 'Ravi', 'Kumar', '1998-09-30', '0000000002');

-- file = stored resume name (random, e.g. 3f9c...e1.pdf); status = new | accepted | rejected
CREATE TABLE `job_apply` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `file` varchar(255) NOT NULL DEFAULT '',
  `id_job` int NOT NULL,
  `email` varchar(190) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `one_application_per_job` (`email`, `id_job`),
  KEY `id_job` (`id_job`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `job_apply` (`id`, `first_name`, `last_name`, `dob`, `file`, `id_job`, `email`, `phone`, `status`) VALUES
(1, 'Jane', 'Doe',   '1996-04-12', '', 1, 'jane.doe@example.com',   '0000000001', 'new'),
(2, 'Ravi', 'Kumar', '1998-09-30', '', 4, 'ravi.kumar@example.com', '0000000002', 'new');

CREATE TABLE `profile` (
  `id` int NOT NULL AUTO_INCREMENT,
  `img` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(100) NOT NULL DEFAULT '',
  `dob` varchar(10) NOT NULL DEFAULT '',
  `number` varchar(20) NOT NULL DEFAULT '',
  `email` varchar(190) NOT NULL DEFAULT '',
  `user_email` varchar(190) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `profile` (`id`, `img`, `name`, `dob`, `number`, `email`, `user_email`) VALUES
(1, 'roshani.png', 'Jane Doe', '1996-04-12', '0000000001', 'jane.doe@example.com', 'jane.doe@example.com');

COMMIT;
