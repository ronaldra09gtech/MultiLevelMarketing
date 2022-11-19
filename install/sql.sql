-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 09, 2022 at 10:06 AM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 8.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mlm_pikachu`
--

-- --------------------------------------------------------

--
-- Table structure for table `deposit_tickets`
--

CREATE TABLE `deposit_tickets` (
  `ticket_id` int(11) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `ticket_creator` int(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_acc_num` varchar(255) DEFAULT NULL,
  `amount` int(255) DEFAULT NULL,
  `created_at` varchar(255) DEFAULT NULL,
  `status` enum('1','2','3') NOT NULL COMMENT '1=pending,2=approve,3=rejected',
  `last_reply_date` varchar(255) DEFAULT NULL,
  `approve_date` varchar(255) DEFAULT NULL,
  `ticket_purpose` enum('1','2') DEFAULT NULL COMMENT '1=deposit,2=withdraw\r\n',
  `closed_on` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `deposit_ticket_messages`
--

CREATE TABLE `deposit_ticket_messages` (
  `id` int(255) NOT NULL,
  `ticket_id` varchar(255) NOT NULL,
  `replier` int(255) NOT NULL,
  `message` text NOT NULL,
  `date` varchar(255) NOT NULL,
  `files` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `image_id` bigint(255) NOT NULL,
  `image_src` text NOT NULL,
  `is_web_img` enum('1','2') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `login_session`
--

CREATE TABLE `login_session` (
  `id` bigint(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `valid_till` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `manual_deposit_method`
--

CREATE TABLE `manual_deposit_method` (
  `gateway_id` bigint(255) NOT NULL,
  `gateway_image` bigint(255) NOT NULL,
  `gateway_name` varchar(255) NOT NULL,
  `processing_time` varchar(255) NOT NULL,
  `deposit_details` text NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  `date_created` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `otps`
--

CREATE TABLE `otps` (
  `otp_id` bigint(255) NOT NULL,
  `otp` bigint(255) NOT NULL,
  `otp_sender` varchar(255) NOT NULL,
  `valid_till` varchar(255) NOT NULL,
  `otp_status` enum('1','2') NOT NULL COMMENT '1=inactive,2=active',
  `otp_purpose` enum('1','2') NOT NULL COMMENT '1=registration,2=forgot-password'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `package_id` int(255) NOT NULL,
  `package` varchar(255) NOT NULL,
  `price` bigint(255) NOT NULL,
  `pair_income` bigint(255) NOT NULL,
  `maximum_pair_income` bigint(255) NOT NULL,
  `minimum_withdraw` bigint(255) NOT NULL,
  `withdraw_charge` varchar(244) NOT NULL,
  `validity` int(11) NOT NULL,
  `status` enum('enable','disable') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `package_history`
--

CREATE TABLE `package_history` (
  `serial_id` bigint(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `package_id` bigint(255) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `amount` bigint(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `activated_date` varchar(255) DEFAULT NULL,
  `expired_date` varchar(255) DEFAULT NULL,
  `status` enum('active','pending','expired') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pair_income`
--

CREATE TABLE `pair_income` (
  `id` int(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `new_user_id` varchar(255) NOT NULL,
  `pair_income` decimal(65,2) NOT NULL,
  `level` int(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `status` enum('pending','credit','capping') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `paytm_payments`
--

CREATE TABLE `paytm_payments` (
  `payment_id` bigint(255) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `payable_amount` bigint(255) NOT NULL,
  `paid_amount` bigint(255) DEFAULT NULL,
  `paid_date` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `id` bigint(255) NOT NULL,
  `mail_encryption` enum('ssl','tls') DEFAULT NULL,
  `mail_host` varchar(255) DEFAULT NULL,
  `mail_port` int(255) DEFAULT NULL,
  `mail_username` varchar(255) DEFAULT NULL,
  `mail_password` varchar(255) DEFAULT NULL,
  `web_currency` varchar(11) DEFAULT NULL,
  `web_currency_position` enum('prefix','suffix') DEFAULT NULL,
  `notice` text DEFAULT NULL,
  `web_primary_color` varchar(255) DEFAULT NULL,
  `logo_id` int(255) NOT NULL DEFAULT 0,
  `logo_icon_id` int(255) NOT NULL DEFAULT 0,
  `favicon_id` int(255) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `ticket_id` bigint(255) NOT NULL,
  `ticket_creator` varchar(255) NOT NULL,
  `ticket_subject` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  `last_reply_date` varchar(255) DEFAULT NULL,
  `status` enum('1','2','3') NOT NULL COMMENT '1=pending,2=active,3=closed',
  `activated_on` varchar(255) DEFAULT NULL,
  `closed_on` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tickets_messages`
--

CREATE TABLE `tickets_messages` (
  `id` bigint(255) NOT NULL,
  `ticket_id` bigint(255) NOT NULL,
  `replier` varchar(255) NOT NULL,
  `message` varchar(2000) NOT NULL,
  `date` varchar(255) NOT NULL,
  `files` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `user_mobile_number` varchar(255) DEFAULT NULL,
  `user_image_id` bigint(255) DEFAULT NULL,
  `user_registration_date` varchar(255) NOT NULL,
  `status` enum('active','blocked') NOT NULL,
  `aadhar_image_id` bigint(255) DEFAULT NULL,
  `pan_image_id` bigint(255) DEFAULT NULL,
  `user_gender` enum('male','female') DEFAULT NULL,
  `user_dob` varchar(255) DEFAULT NULL,
  `user_address_1` text DEFAULT NULL,
  `user_address_2` text DEFAULT NULL,
  `user_country` varchar(255) DEFAULT NULL,
  `user_state` varchar(255) DEFAULT NULL,
  `user_city` varchar(255) DEFAULT NULL,
  `user_pin_code` bigint(255) DEFAULT NULL,
  `user_aadhar_number` bigint(255) DEFAULT NULL,
  `user_pan_number` varchar(255) DEFAULT NULL,
  `kyc` enum('1','2','3','4') DEFAULT '1' COMMENT '1=not-verified,2=progress,3=success,4=rejected',
  `kyc_submitted` varchar(255) DEFAULT NULL,
  `payment_gateways` varchar(255) DEFAULT NULL,
  `user_role` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1=user,2=admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users_tree`
--

CREATE TABLE `users_tree` (
  `tree_id` bigint(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `referral_id` varchar(255) NOT NULL,
  `placement_id` varchar(255) NOT NULL,
  `placement_type` enum('left','right') NOT NULL,
  `left_count` int(255) NOT NULL,
  `right_count` int(255) NOT NULL,
  `left_id` varchar(255) NOT NULL,
  `right_id` varchar(255) NOT NULL,
  `pair_count` int(255) NOT NULL,
  `premium_left_count` bigint(255) NOT NULL DEFAULT 0,
  `premium_right_count` bigint(255) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_balance`
--

CREATE TABLE `user_balance` (
  `id` bigint(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `wallet` bigint(255) DEFAULT 0,
  `income` bigint(255) DEFAULT 0,
  `referral_income` bigint(255) NOT NULL DEFAULT 0,
  `pair_income` bigint(255) NOT NULL DEFAULT 0,
  `total_income` bigint(255) DEFAULT 0,
  `total_withdrawl` bigint(255) DEFAULT 0,
  `deposit_review` bigint(255) DEFAULT 0,
  `pending` bigint(255) DEFAULT 0,
  `last_added_money` bigint(255) DEFAULT 0,
  `total_added_money` bigint(255) DEFAULT 0,
  `last_withdrawl` bigint(255) DEFAULT 0,
  `has_purchased_premium` enum('no','yes') NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_capping`
--

CREATE TABLE `user_capping` (
  `id` bigint(255) NOT NULL,
  `user_id` bigint(255) NOT NULL,
  `pair_income` bigint(255) NOT NULL,
  `date` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_deposits`
--

CREATE TABLE `user_deposits` (
  `deposit_id` bigint(255) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `amount` bigint(255) NOT NULL,
  `gateway` varchar(255) NOT NULL,
  `payment_image` bigint(255) DEFAULT NULL,
  `date` varchar(255) NOT NULL,
  `payment_type` enum('automatic','verification') DEFAULT 'verification',
  `message` text DEFAULT NULL,
  `success_date` varchar(255) DEFAULT NULL,
  `status` enum('review','approved','rejected') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_gateway_val`
--

CREATE TABLE `user_gateway_val` (
  `value_id` bigint(255) NOT NULL,
  `requirement_id` bigint(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_referrs`
--

CREATE TABLE `user_referrs` (
  `id` bigint(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `referral_user_id` varchar(255) NOT NULL,
  `date_referred` varchar(255) NOT NULL,
  `referral_bonus` bigint(255) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_transactions`
--

CREATE TABLE `user_transactions` (
  `id` bigint(255) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `amount` bigint(255) NOT NULL,
  `transaction_charge` varchar(255) NOT NULL,
  `net_amount` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `payment_image` bigint(255) DEFAULT NULL,
  `category` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `status` enum('credit','debit','pending','review','rejected','capping') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_withdraws`
--

CREATE TABLE `user_withdraws` (
  `withdraw_id` bigint(255) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `amount` bigint(255) NOT NULL,
  `total_charge` varchar(255) NOT NULL,
  `net_amount` varchar(255) NOT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_account_details` text NOT NULL,
  `payment_img` varchar(255) DEFAULT NULL,
  `requested_date` varchar(255) NOT NULL,
  `success_date` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `withdraw_methods`
--

CREATE TABLE `withdraw_methods` (
  `gateway_id` bigint(255) NOT NULL,
  `gateway_image` bigint(255) NOT NULL,
  `gateway_name` varchar(255) NOT NULL,
  `processing_time` varchar(255) NOT NULL,
  `requirement_card_heading` varchar(255) DEFAULT NULL,
  `date_added` varchar(255) NOT NULL,
  `status` enum('enabled','disabled') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `withdraw_requirements`
--

CREATE TABLE `withdraw_requirements` (
  `requirement_id` bigint(255) NOT NULL,
  `gateway_id` bigint(255) NOT NULL,
  `label_text` varchar(255) NOT NULL,
  `is_image_chooser` enum('1','0') DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `deposit_tickets`
--
ALTER TABLE `deposit_tickets`
  ADD PRIMARY KEY (`ticket_id`);

--
-- Indexes for table `deposit_ticket_messages`
--
ALTER TABLE `deposit_ticket_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`image_id`);

--
-- Indexes for table `login_session`
--
ALTER TABLE `login_session`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manual_deposit_method`
--
ALTER TABLE `manual_deposit_method`
  ADD PRIMARY KEY (`gateway_id`);

--
-- Indexes for table `otps`
--
ALTER TABLE `otps`
  ADD PRIMARY KEY (`otp_id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`package_id`);

--
-- Indexes for table `package_history`
--
ALTER TABLE `package_history`
  ADD PRIMARY KEY (`serial_id`);

--
-- Indexes for table `pair_income`
--
ALTER TABLE `pair_income`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paytm_payments`
--
ALTER TABLE `paytm_payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD UNIQUE KEY `order_id` (`transaction_id`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`ticket_id`);

--
-- Indexes for table `tickets_messages`
--
ALTER TABLE `tickets_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `user_name` (`user_name`);

--
-- Indexes for table `users_tree`
--
ALTER TABLE `users_tree`
  ADD PRIMARY KEY (`tree_id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `user_id_2` (`user_id`);

--
-- Indexes for table `user_balance`
--
ALTER TABLE `user_balance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_capping`
--
ALTER TABLE `user_capping`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_deposits`
--
ALTER TABLE `user_deposits`
  ADD PRIMARY KEY (`deposit_id`);

--
-- Indexes for table `user_gateway_val`
--
ALTER TABLE `user_gateway_val`
  ADD PRIMARY KEY (`value_id`);

--
-- Indexes for table `user_referrs`
--
ALTER TABLE `user_referrs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_transactions`
--
ALTER TABLE `user_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_id` (`transaction_id`),
  ADD UNIQUE KEY `transaction_id_3` (`transaction_id`),
  ADD KEY `transaction_id_2` (`transaction_id`);

--
-- Indexes for table `user_withdraws`
--
ALTER TABLE `user_withdraws`
  ADD PRIMARY KEY (`withdraw_id`),
  ADD UNIQUE KEY `withdraw_id` (`transaction_id`),
  ADD UNIQUE KEY `transaction_id` (`transaction_id`);

--
-- Indexes for table `withdraw_methods`
--
ALTER TABLE `withdraw_methods`
  ADD PRIMARY KEY (`gateway_id`);

--
-- Indexes for table `withdraw_requirements`
--
ALTER TABLE `withdraw_requirements`
  ADD PRIMARY KEY (`requirement_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `deposit_tickets`
--
ALTER TABLE `deposit_tickets`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deposit_ticket_messages`
--
ALTER TABLE `deposit_ticket_messages`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `image_id` bigint(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_session`
--
ALTER TABLE `login_session`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `manual_deposit_method`
--
ALTER TABLE `manual_deposit_method`
  MODIFY `gateway_id` bigint(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `otps`
--
ALTER TABLE `otps`
  MODIFY `otp_id` bigint(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `package_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `package_history`
--
ALTER TABLE `package_history`
  MODIFY `serial_id` bigint(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pair_income`
--
ALTER TABLE `pair_income`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `paytm_payments`
--
ALTER TABLE `paytm_payments`
  MODIFY `payment_id` bigint(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `ticket_id` bigint(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tickets_messages`
--
ALTER TABLE `tickets_messages`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_tree`
--
ALTER TABLE `users_tree`
  MODIFY `tree_id` bigint(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_balance`
--
ALTER TABLE `user_balance`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_capping`
--
ALTER TABLE `user_capping`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_deposits`
--
ALTER TABLE `user_deposits`
  MODIFY `deposit_id` bigint(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_gateway_val`
--
ALTER TABLE `user_gateway_val`
  MODIFY `value_id` bigint(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_referrs`
--
ALTER TABLE `user_referrs`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_transactions`
--
ALTER TABLE `user_transactions`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_withdraws`
--
ALTER TABLE `user_withdraws`
  MODIFY `withdraw_id` bigint(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdraw_methods`
--
ALTER TABLE `withdraw_methods`
  MODIFY `gateway_id` bigint(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdraw_requirements`
--
ALTER TABLE `withdraw_requirements`
  MODIFY `requirement_id` bigint(255) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
