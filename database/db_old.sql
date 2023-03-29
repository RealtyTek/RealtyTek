-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 21, 2021 at 07:18 AM
-- Server version: 5.7.32
-- PHP Version: 7.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `retrocub_realtytek_qa`
--

-- --------------------------------------------------------

--
-- Table structure for table `application_setting`
--

CREATE TABLE `application_setting` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `identifier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `agent_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `property_id` bigint(20) UNSIGNED NOT NULL,
  `buyer_id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `status` enum('pending','accept','reject') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_content`
--

CREATE TABLE `app_content` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `identifier` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `app_content`
--

INSERT INTO `app_content` (`id`, `identifier`, `slug`, `content`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'term_and_condition', 'term_and_condition', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry', '1', '2021-09-02 06:47:41', '2021-09-02 06:47:41', NULL),
(2, 'privacy_policy', 'privacy_policy', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to usin', '1', '2021-09-02 06:47:41', '2021-09-02 06:47:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `app_faqs`
--

CREATE TABLE `app_faqs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(225) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `app_faqs`
--

INSERT INTO `app_faqs` (`id`, `question`, `answer`, `slug`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'is simply dummy text of the printing and typesetting industry?', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry..', '', '1', '2021-09-02 06:47:41', '2021-09-02 06:47:41', NULL),
(2, 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout?', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry..', '', '1', '2021-09-02 06:47:41', '2021-09-02 06:47:41', NULL),
(3, 'It is distracted by the readable content of a page when looking at its layout?', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry..', '', '1', '2021-09-02 06:47:41', '2021-09-02 06:47:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `buyers`
--

CREATE TABLE `buyers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `agent_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `creator_id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requirements` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `house_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `move_date` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_time_buyer` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pre_approved` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `initiate_contract` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `buyer_properties`
--

CREATE TABLE `buyer_properties` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `agent_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `buyer_id` bigint(20) UNSIGNED NOT NULL,
  `property_id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` int(11) NOT NULL DEFAULT '0',
  `review` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `is_tour` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `initiate_contract` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cms_modules`
--

CREATE TABLE `cms_modules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `route_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fa fa-list',
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `sort_order` decimal(5,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cms_modules`
--

INSERT INTO `cms_modules` (`id`, `parent_id`, `name`, `route_name`, `icon`, `status`, `sort_order`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 0, 'Cms Roles Management', 'cms-roles-management.index', 'fa fa-key', '1', 1.00, '2021-09-02 06:47:40', NULL, NULL),
(2, 0, 'Cms Users Management', 'cms-users-management.index', 'fa fa-users', '1', 2.00, '2021-09-02 06:47:40', NULL, NULL),
(3, 0, 'Application Setting', 'admin.application-setting', 'fa fa-cog', '1', 3.00, '2021-09-02 06:47:40', NULL, NULL),
(4, 0, 'Users Management', 'app-users.index', 'fa fa-users', '1', 4.00, '2021-09-02 06:47:40', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cms_module_permissions`
--

CREATE TABLE `cms_module_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cms_role_id` bigint(20) UNSIGNED NOT NULL,
  `cms_module_id` bigint(20) UNSIGNED NOT NULL,
  `is_add` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `is_view` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `is_update` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `is_delete` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cms_module_permissions`
--

INSERT INTO `cms_module_permissions` (`id`, `cms_role_id`, `cms_module_id`, `is_add`, `is_view`, `is_update`, `is_delete`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 1, '0', '0', '0', '0', '2021-09-07 12:17:41', NULL, NULL),
(2, 2, 2, '0', '0', '0', '0', '2021-09-07 12:17:41', NULL, NULL),
(3, 2, 3, '0', '0', '0', '0', '2021-09-07 12:17:41', NULL, NULL),
(4, 2, 4, '0', '0', '0', '0', '2021-09-07 12:17:41', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cms_roles`
--

CREATE TABLE `cms_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_super_admin` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cms_roles`
--

INSERT INTO `cms_roles` (`id`, `name`, `slug`, `is_super_admin`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Super Admin', 'super-admin', '1', '2021-09-02 06:47:40', NULL, NULL),
(2, 'admin', 'admin', '0', '2021-09-07 12:17:41', '2021-09-07 12:17:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cms_users`
--

CREATE TABLE `cms_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cms_role_id` bigint(20) UNSIGNED NOT NULL,
  `user_ref_id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_url` text COLLATE utf8mb4_unicode_ci,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `is_email_verify` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cms_users`
--

INSERT INTO `cms_users` (`id`, `cms_role_id`, `user_ref_id`, `name`, `username`, `slug`, `email`, `mobile_no`, `password`, `image_url`, `status`, `is_email_verify`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 0, 'RetroCube', 'retrocube', 'retrocube', 'admin@retrocube.com', '1-8882051816', '$2y$10$1h2XmdutrxciLuovVmILJOW.cFdiTyA8eTfCRGcJZxw0/2xAFPSbK', NULL, '1', '0', NULL, '2021-09-02 06:47:40', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lenders_state_disclosure`
--

CREATE TABLE `lenders_state_disclosure` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mail_templates`
--

CREATE TABLE `mail_templates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `identifier` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `wildcard` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mail_templates`
--

INSERT INTO `mail_templates` (`id`, `identifier`, `subject`, `body`, `wildcard`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'forgot-password', 'Forgot Password Confirmation', '<table class=\"main\" style=\"border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;\">\r\n    <tr>\r\n        <td class=\"wrapper\" style=\"font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;\">\r\n            <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;\">\r\n                <tr>\r\n                    <td style=\"font-family: sans-serif; font-size: 14px; vertical-align: top;\">\r\n                        <p style=\"font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;\">Hi [USERNAME],</p>\r\n                        <p style=\"font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;\">A request has been made to recover password for your account. Please follow below link to generate new password for your account :</p>\r\n                        <p><a href=\"[LINK]\">Reset Password</a></p>\r\n                        <br>\r\n                        <br>\r\n                        <p style=\"font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;\">Regards,</p>\r\n                        <p style=\"font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;\">© [YEAR] [APP_NAME] All Rights reserved.</p>\r\n                    </td>\r\n                </tr>\r\n            </table>\r\n        </td>\r\n    </tr>\r\n</table>\r\n', '[USERNAME],[LINK],[YEAR],[APP_NAME]', '2021-09-02 06:47:40', NULL, NULL),
(2, 'user_registration', 'Welcome to [APP_NAME]', '<table class=\"main\" style=\"border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;\">\r\n    <tr>\r\n        <td class=\"wrapper\" style=\"font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;\">\r\n            <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;\">\r\n                <tr>\r\n                    <td style=\"font-family: sans-serif; font-size: 14px; vertical-align: top;\">\r\n                        <p style=\"font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;\">Hi [USERNAME],</p>\r\n                        <p style=\"font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;\">Your account has been created successfully. First, you need to confirm your account. Just click the below link</p>\r\n                        <p><a href=\"[LINK]\">Verify Now</a></p>\r\n                        <br>\r\n                        <br>\r\n                        <p style=\"font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;\">Regards,</p>\r\n                        <p style=\"font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;\">© [YEAR] [APP_NAME] All Rights reserved.</p>\r\n                    </td>\r\n                </tr>\r\n            </table>\r\n        </td>\r\n    </tr>\r\n</table>\r\n', '[USERNAME],[LINK],[YEAR],[APP_NAME]', '2021-09-02 06:47:40', NULL, NULL),
(3, 'customer_invite', 'Welcome to [APP_NAME]', '<table class=\"main\" style=\"border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;\">\r\n   <tr>\r\n      <td class=\"wrapper\" style=\"font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;\">\r\n         <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;\">\r\n            <tr>\r\n               <td style=\"font-family: sans-serif; font-size: 14px; vertical-align: top;\">\r\n                  <p style=\"font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;\">Hi [NAME],</p>\r\n                  <p style=\"font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;\">Your account has been created by [AGENT_NAME]. First, you need to verify your email through the following link.</p>\r\n                   <p><a href=\"[VERIFY_LINK]\">[VERIFY_LINK]</a></p>\r\n                   <p style=\"font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0;\"><b>Admin Panel Credential</b></p>\r\n                   <br />\r\n                  <p style=\"font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0;\">Email: [EMAIL]</p>\r\n                  <p style=\"font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0;\">Password: [PASSWORD]</p>\r\n                  <br />\r\n                  <br />\r\n                  <p style=\"font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;\">Regards,</p>\r\n                  <p style=\"font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;\">The [APP_NAME] team,</p>\r\n               </td>\r\n            </tr>\r\n         </table>\r\n      </td>\r\n   </tr>\r\n</table>', '[NAME],[AGENT_NAME],[EMAIL],[PASSWORD],[APP_NAME],[VERIFY_LINK]', '2021-09-02 06:47:40', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `module` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module_id` int(11) NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_url` text COLLATE utf8mb4_unicode_ci,
  `thumbnail_url` text COLLATE utf8mb4_unicode_ci,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_data` text COLLATE utf8mb4_unicode_ci,
  `status` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_08_19_000000_create_failed_jobs_table', 1),
(2, '2021_03_05_000000_create_cms_roles_table', 1),
(3, '2021_03_05_172240_create_cms_users_table', 1),
(4, '2021_03_05_174918_create_cms_modules_table', 1),
(5, '2021_03_05_175415_create_cms_module_permissions_table', 1),
(6, '2021_03_06_185651_create_mail_templates_table', 1),
(7, '2021_03_08_191839_create_reset_password_table', 1),
(8, '2021_03_13_121206_create_application_setting_table', 1),
(9, '2021_04_20_200223_create_user_groups_table', 1),
(10, '2021_04_20_200451_create_users_table', 1),
(11, '2021_04_20_202053_create_user_api_token_table', 1),
(12, '2021_06_04_181436_create_notification_table', 1),
(13, '2021_06_06_130550_create_notification_setting_table', 1),
(14, '2021_08_02_162534_create_properties_table', 1),
(15, '2021_08_02_165657_create_buyers_table', 1),
(16, '2021_08_02_171133_create_buyer_properties_table', 1),
(17, '2021_08_02_171643_create_property_contract_status_table', 1),
(18, '2021_08_02_172406_create_property_loan_info_table', 1),
(19, '2021_08_02_172922_create_appointments_table', 1),
(20, '2021_08_02_174021_create_subscription_packages_table', 1),
(21, '2021_08_02_174220_create_app_content_table', 1),
(22, '2021_08_02_174235_create_app_faqs_table', 1),
(23, '2021_08_02_174321_create_user_subscriptions_table', 1),
(24, '2021_08_02_205522_create_transactions_table', 1),
(25, '2021_08_12_081800_create_property_contract_status_history_table', 1),
(26, '2021_08_31_073711_create_media_table', 1),
(27, '2021_08_31_074907_create_lenders_state_disclosure_table', 1),
(28, '2021_08_31_104207_create_user_review_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unique_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `identifier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `actor_id` int(11) NOT NULL,
  `actor_type` enum('users','cms_users') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'users',
  `target_id` int(11) NOT NULL,
  `target_type` enum('users','cms_users') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'users',
  `reference_id` int(11) NOT NULL,
  `reference_module` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `web_redirect_link` text COLLATE utf8mb4_unicode_ci,
  `is_read` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `is_view` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_setting`
--

CREATE TABLE `notification_setting` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `meta_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_value` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `agent_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `creator_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_url` text COLLATE utf8mb4_unicode_ci,
  `address` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zipcode` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `property_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mls_detail` text COLLATE utf8mb4_unicode_ci,
  `asking_price` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sell_date` date DEFAULT NULL,
  `cma_appointment` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `property_status` enum('buying','selling') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `initiate_contract` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `rating` int(11) NOT NULL DEFAULT '0',
  `review` int(11) DEFAULT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `property_contract_status`
--

CREATE TABLE `property_contract_status` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `property_id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contract_offer` date DEFAULT NULL,
  `contract_countered` date DEFAULT NULL,
  `contract_accepted` date DEFAULT NULL,
  `contract_executed` date DEFAULT NULL,
  `offer_decline` date DEFAULT NULL,
  `inspection` date DEFAULT NULL,
  `appraisal` date DEFAULT NULL,
  `final_walk_thru` date DEFAULT NULL,
  `sattlement_date` date DEFAULT NULL,
  `add_comment` text COLLATE utf8mb4_unicode_ci,
  `contract_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contract_status_updated_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `property_contract_status_history`
--

CREATE TABLE `property_contract_status_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contract_id` bigint(20) UNSIGNED NOT NULL,
  `property_id` bigint(20) UNSIGNED NOT NULL,
  `contract_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `property_loan_info`
--

CREATE TABLE `property_loan_info` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `property_id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sale_price` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `financing` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emd_submitted` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `down_payment` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `loan_status` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `loan_status_updated_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reset_password`
--

CREATE TABLE `reset_password` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `module` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_packages`
--

CREATE TABLE `subscription_packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration_type` enum('day','month','week','year') COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` int(11) NOT NULL,
  `is_trail` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `trail_period` int(11) NOT NULL DEFAULT '0',
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `module` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gateway_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gateway_transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_group_id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_url` text COLLATE utf8mb4_unicode_ci,
  `license_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `licence_state` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `is_email_verify` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `email_verify_at` datetime DEFAULT NULL,
  `is_mobile_verify` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `mobile_verify_at` datetime DEFAULT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zipcode` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `online_status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `mobile_otp` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_otp` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_property` int(11) NOT NULL DEFAULT '0',
  `total_rating` int(11) NOT NULL DEFAULT '0',
  `total_review` int(11) NOT NULL DEFAULT '0',
  `website` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `about_us` text COLLATE utf8mb4_unicode_ci,
  `tag_line` text COLLATE utf8mb4_unicode_ci,
  `logo_url` text COLLATE utf8mb4_unicode_ci,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_description` text COLLATE utf8mb4_unicode_ci,
  `invitation_acceptance` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_api_token`
--

CREATE TABLE `user_api_token` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `api_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `refresh_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `udid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_token` text COLLATE utf8mb4_unicode_ci,
  `platform_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'custom',
  `platform_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

CREATE TABLE `user_groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_groups`
--

INSERT INTO `user_groups` (`id`, `title`, `slug`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Agent', 'agent', '1', '2021-09-02 06:47:41', NULL, NULL),
(2, 'Customer', 'customer', '1', '2021-09-02 06:47:41', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_review`
--

CREATE TABLE `user_review` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `buyer_id` bigint(20) UNSIGNED NOT NULL,
  `buyer_property_id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `review` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_subscriptions`
--

CREATE TABLE `user_subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `subscription_package_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expiry_date` date NOT NULL,
  `status` enum('active','expire') COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_type` enum('web','android','ios') COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_trail` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `application_setting`
--
ALTER TABLE `application_setting`
  ADD PRIMARY KEY (`id`),
  ADD KEY `identifier` (`identifier`),
  ADD KEY `meta_key` (`meta_key`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `appointments_slug_unique` (`slug`),
  ADD KEY `appointments_buyer_id_foreign` (`buyer_id`),
  ADD KEY `appointments_agent_id_index` (`agent_id`),
  ADD KEY `appointments_customer_id_index` (`customer_id`),
  ADD KEY `appointments_property_id_index` (`property_id`),
  ADD KEY `appointments_appointment_date_index` (`appointment_date`),
  ADD KEY `appointments_status_index` (`status`);

--
-- Indexes for table `app_content`
--
ALTER TABLE `app_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_faqs`
--
ALTER TABLE `app_faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `buyers`
--
ALTER TABLE `buyers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `buyers_slug_unique` (`slug`),
  ADD KEY `buyers_creator_id_foreign` (`creator_id`),
  ADD KEY `buyers_agent_id_index` (`agent_id`),
  ADD KEY `buyers_customer_id_index` (`customer_id`),
  ADD KEY `buyers_status_index` (`status`);

--
-- Indexes for table `buyer_properties`
--
ALTER TABLE `buyer_properties`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `buyer_properties_slug_unique` (`slug`),
  ADD KEY `buyer_properties_agent_id_index` (`agent_id`),
  ADD KEY `buyer_properties_customer_id_index` (`customer_id`),
  ADD KEY `buyer_properties_buyer_id_index` (`buyer_id`),
  ADD KEY `buyer_properties_property_id_index` (`property_id`);

--
-- Indexes for table `cms_modules`
--
ALTER TABLE `cms_modules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cms_modules_name_unique` (`name`),
  ADD UNIQUE KEY `cms_modules_route_name_unique` (`route_name`);

--
-- Indexes for table `cms_module_permissions`
--
ALTER TABLE `cms_module_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cms_module_permissions_cms_role_id_foreign` (`cms_role_id`),
  ADD KEY `cms_module_permissions_cms_module_id_foreign` (`cms_module_id`);

--
-- Indexes for table `cms_roles`
--
ALTER TABLE `cms_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_users`
--
ALTER TABLE `cms_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cms_users_username_unique` (`username`),
  ADD UNIQUE KEY `cms_users_slug_unique` (`slug`),
  ADD UNIQUE KEY `cms_users_email_unique` (`email`),
  ADD UNIQUE KEY `cms_users_mobile_no_unique` (`mobile_no`),
  ADD KEY `cms_users_cms_role_id_foreign` (`cms_role_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `lenders_state_disclosure`
--
ALTER TABLE `lenders_state_disclosure`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lenders_state_disclosure_user_id_foreign` (`user_id`);

--
-- Indexes for table `mail_templates`
--
ALTER TABLE `mail_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mail_templates_identifier_unique` (`identifier`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_setting`
--
ALTER TABLE `notification_setting`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notification_setting_user_id_index` (`user_id`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `properties_slug_unique` (`slug`),
  ADD KEY `properties_creator_id_foreign` (`creator_id`),
  ADD KEY `properties_agent_id_index` (`agent_id`),
  ADD KEY `properties_customer_id_index` (`customer_id`),
  ADD KEY `properties_address_index` (`address`),
  ADD KEY `properties_city_index` (`city`),
  ADD KEY `properties_state_index` (`state`),
  ADD KEY `properties_zipcode_index` (`zipcode`),
  ADD KEY `properties_property_status_index` (`property_status`),
  ADD KEY `properties_initiate_contract_index` (`initiate_contract`),
  ADD KEY `properties_status_index` (`status`);

--
-- Indexes for table `property_contract_status`
--
ALTER TABLE `property_contract_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `property_contract_status_slug_unique` (`slug`),
  ADD KEY `property_contract_status_property_id_foreign` (`property_id`);

--
-- Indexes for table `property_contract_status_history`
--
ALTER TABLE `property_contract_status_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_contract_status_history_contract_id_foreign` (`contract_id`),
  ADD KEY `property_contract_status_history_property_id_foreign` (`property_id`);

--
-- Indexes for table `property_loan_info`
--
ALTER TABLE `property_loan_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `property_loan_info_slug_unique` (`slug`),
  ADD KEY `property_loan_info_property_id_foreign` (`property_id`);

--
-- Indexes for table `reset_password`
--
ALTER TABLE `reset_password`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscription_packages`
--
ALTER TABLE `subscription_packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_user_id_foreign` (`user_id`),
  ADD KEY `transactions_module_module_id_index` (`module`,`module_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_slug_unique` (`slug`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_mobile_no_unique` (`mobile_no`),
  ADD KEY `users_user_group_id_index` (`user_group_id`),
  ADD KEY `users_parent_id_index` (`parent_id`),
  ADD KEY `users_state_index` (`state`),
  ADD KEY `users_name_index` (`name`),
  ADD KEY `users_email_index` (`email`),
  ADD KEY `users_licence_state_index` (`licence_state`),
  ADD KEY `users_status_index` (`status`);

--
-- Indexes for table `user_api_token`
--
ALTER TABLE `user_api_token`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_api_token_api_token_unique` (`api_token`),
  ADD UNIQUE KEY `user_api_token_refresh_token_unique` (`refresh_token`),
  ADD KEY `user_api_token_user_id_foreign` (`user_id`);

--
-- Indexes for table `user_groups`
--
ALTER TABLE `user_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_review`
--
ALTER TABLE `user_review`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_review_user_id_foreign` (`user_id`),
  ADD KEY `user_review_buyer_id_foreign` (`buyer_id`),
  ADD KEY `user_review_buyer_property_id_foreign` (`buyer_property_id`);

--
-- Indexes for table `user_subscriptions`
--
ALTER TABLE `user_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_subscriptions_user_id_foreign` (`user_id`),
  ADD KEY `user_subscriptions_subscription_package_id_foreign` (`subscription_package_id`),
  ADD KEY `user_subscriptions_expiry_date_index` (`expiry_date`),
  ADD KEY `user_subscriptions_status_index` (`status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `application_setting`
--
ALTER TABLE `application_setting`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `app_content`
--
ALTER TABLE `app_content`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `app_faqs`
--
ALTER TABLE `app_faqs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `buyers`
--
ALTER TABLE `buyers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `buyer_properties`
--
ALTER TABLE `buyer_properties`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cms_modules`
--
ALTER TABLE `cms_modules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cms_module_permissions`
--
ALTER TABLE `cms_module_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cms_roles`
--
ALTER TABLE `cms_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cms_users`
--
ALTER TABLE `cms_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lenders_state_disclosure`
--
ALTER TABLE `lenders_state_disclosure`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mail_templates`
--
ALTER TABLE `mail_templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_setting`
--
ALTER TABLE `notification_setting`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `property_contract_status`
--
ALTER TABLE `property_contract_status`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `property_contract_status_history`
--
ALTER TABLE `property_contract_status_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `property_loan_info`
--
ALTER TABLE `property_loan_info`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reset_password`
--
ALTER TABLE `reset_password`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_packages`
--
ALTER TABLE `subscription_packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_api_token`
--
ALTER TABLE `user_api_token`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_groups`
--
ALTER TABLE `user_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_review`
--
ALTER TABLE `user_review`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_subscriptions`
--
ALTER TABLE `user_subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_agent_id_foreign` FOREIGN KEY (`agent_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_buyer_id_foreign` FOREIGN KEY (`buyer_id`) REFERENCES `buyers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `buyers`
--
ALTER TABLE `buyers`
  ADD CONSTRAINT `buyers_agent_id_foreign` FOREIGN KEY (`agent_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `buyers_creator_id_foreign` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `buyers_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `buyer_properties`
--
ALTER TABLE `buyer_properties`
  ADD CONSTRAINT `buyer_properties_agent_id_foreign` FOREIGN KEY (`agent_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `buyer_properties_buyer_id_foreign` FOREIGN KEY (`buyer_id`) REFERENCES `buyers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `buyer_properties_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `buyer_properties_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cms_module_permissions`
--
ALTER TABLE `cms_module_permissions`
  ADD CONSTRAINT `cms_module_permissions_cms_module_id_foreign` FOREIGN KEY (`cms_module_id`) REFERENCES `cms_modules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cms_module_permissions_cms_role_id_foreign` FOREIGN KEY (`cms_role_id`) REFERENCES `cms_roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cms_users`
--
ALTER TABLE `cms_users`
  ADD CONSTRAINT `cms_users_cms_role_id_foreign` FOREIGN KEY (`cms_role_id`) REFERENCES `cms_roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lenders_state_disclosure`
--
ALTER TABLE `lenders_state_disclosure`
  ADD CONSTRAINT `lenders_state_disclosure_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notification_setting`
--
ALTER TABLE `notification_setting`
  ADD CONSTRAINT `notification_setting_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `properties`
--
ALTER TABLE `properties`
  ADD CONSTRAINT `properties_agent_id_foreign` FOREIGN KEY (`agent_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `properties_creator_id_foreign` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `properties_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `property_contract_status`
--
ALTER TABLE `property_contract_status`
  ADD CONSTRAINT `property_contract_status_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `property_contract_status_history`
--
ALTER TABLE `property_contract_status_history`
  ADD CONSTRAINT `property_contract_status_history_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `property_contract_status` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `property_contract_status_history_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `property_loan_info`
--
ALTER TABLE `property_loan_info`
  ADD CONSTRAINT `property_loan_info_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_user_group_id_foreign` FOREIGN KEY (`user_group_id`) REFERENCES `user_groups` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_api_token`
--
ALTER TABLE `user_api_token`
  ADD CONSTRAINT `user_api_token_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_review`
--
ALTER TABLE `user_review`
  ADD CONSTRAINT `user_review_buyer_id_foreign` FOREIGN KEY (`buyer_id`) REFERENCES `buyers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_review_buyer_property_id_foreign` FOREIGN KEY (`buyer_property_id`) REFERENCES `buyer_properties` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_review_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_subscriptions`
--
ALTER TABLE `user_subscriptions`
  ADD CONSTRAINT `user_subscriptions_subscription_package_id_foreign` FOREIGN KEY (`subscription_package_id`) REFERENCES `subscription_packages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
