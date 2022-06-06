-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2022 at 08:49 PM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lavacms`
--

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `id` int(10) UNSIGNED NOT NULL,
  `asset_name` varchar(190) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `asset_filename` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `asset_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `asset_slug` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `asset_size` int(10) DEFAULT NULL,
  `asset_filetype` varchar(65) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `asset_path` varchar(190) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(24) COLLATE utf8mb4_unicode_ci NOT NULL,
  `folder` varchar(24) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_width` int(10) NOT NULL,
  `image_height` int(10) DEFAULT NULL,
  `image_attr` varchar(65) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `created_byid` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(10) UNSIGNED NOT NULL,
  `branch` varchar(150) NOT NULL,
  `code` varchar(12) NOT NULL,
  `address` varchar(190) DEFAULT NULL,
  `status` varchar(15) NOT NULL DEFAULT 'active',
  `created_byid` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `branch`, `code`, `address`, `status`, `created_byid`, `created_at`, `modified_at`, `deleted_at`) VALUES
(1, 'North MES Miscellaneous Job', 'XX-210-MIS', 'Unit 7, Temple Point, Bullerthorpe Ln, Colton, Leeds LS15 9JL', 'active', 1270, '2022-06-04 22:07:26', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `cat_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cat_slug` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cat_parent` int(10) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `created_byid` int(10) UNSIGNED NOT NULL,
  `deleted_at` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `cat_name`, `cat_slug`, `cat_parent`, `created_at`, `modified_at`, `created_byid`, `deleted_at`) VALUES
(96, 'Willow Myers', 'voluptatem-reiciend', NULL, '2022-05-27 13:14:28', NULL, 1270, 0),
(97, 'Quamar Briggs', 'nobis-dolorum-ea-id', NULL, '2022-05-27 13:23:51', NULL, 1270, 0);

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_byid` int(10) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `phone`, `created_byid`, `created_at`, `modified_at`, `deleted_at`) VALUES
(1, 'The Bakery', '0800-256-9847', 1, '2021-03-07 22:51:37', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `controllers`
--

CREATE TABLE `controllers` (
  `id` int(10) UNSIGNED NOT NULL,
  `controller` varchar(50) NOT NULL,
  `methods` longtext DEFAULT NULL,
  `method_permissions` longtext DEFAULT NULL,
  `active` int(1) DEFAULT 0,
  `current_new_method` longtext DEFAULT NULL,
  `current_new_method_datetime` datetime DEFAULT NULL,
  `current_method_count` int(11) DEFAULT NULL,
  `is_parent_menu` int(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `controllers`
--

INSERT INTO `controllers` (`id`, `controller`, `methods`, `method_permissions`, `active`, `current_new_method`, `current_new_method_datetime`, `current_method_count`, `is_parent_menu`, `created_at`, `modified_at`) VALUES
(208, 'dashboard', 'a:7:{i:0;s:11:\"indexAction\";i:1;s:14:\"datetimeAction\";i:2;s:14:\"settingsAction\";i:3;s:12:\"healthAction\";i:4;s:13:\"historyAction\";i:7;s:15:\"changeRowAction\";i:8;s:22:\"discoveryRefreshAction\";}', NULL, 0, 'a:3:{i:9;s:12:\"importAction\";i:10;s:12:\"exportAction\";i:17;s:16:\"chooseBulkAction\";}', NULL, 3, 0, '2022-05-13 21:38:48', '2022-06-05 17:07:35'),
(209, 'discovery', 'a:7:{i:0;s:14:\"discoverAction\";i:1;s:24:\"discoverControllerAction\";i:2;s:13:\"installAction\";i:3;s:10:\"testAction\";i:6;s:14:\"settingsAction\";i:7;s:15:\"changeRowAction\";i:8;s:22:\"discoveryRefreshAction\";}', NULL, 0, 'a:3:{i:7;s:12:\"importAction\";i:8;s:12:\"exportAction\";i:16;s:16:\"chooseBulkAction\";}', NULL, 3, 0, '2022-05-13 21:38:51', '2022-06-05 19:18:29'),
(210, 'accessdenied', 'a:4:{i:0;s:11:\"indexAction\";i:3;s:14:\"settingsAction\";i:4;s:15:\"changeRowAction\";i:5;s:22:\"discoveryRefreshAction\";}', NULL, 0, 'a:3:{i:5;s:12:\"importAction\";i:6;s:12:\"exportAction\";i:14;s:16:\"chooseBulkAction\";}', NULL, 3, 0, '2022-05-13 21:38:53', '2022-06-05 19:20:10'),
(211, 'category', 'a:12:{i:0;s:11:\"indexAction\";i:1;s:9:\"newAction\";i:2;s:10:\"editAction\";i:3;s:10:\"copyAction\";i:4;s:11:\"trashAction\";i:5;s:13:\"untrashAction\";i:6;s:16:\"hardDeleteAction\";i:7;s:10:\"testAction\";i:8;s:14:\"settingsAction\";i:11;s:15:\"changeRowAction\";i:12;s:22:\"discoveryRefreshAction\";i:19;s:16:\"chooseBulkAction\";}', NULL, 0, 'a:2:{i:13;s:12:\"importAction\";i:14;s:12:\"exportAction\";}', NULL, 2, 0, '2022-05-13 21:38:53', NULL),
(212, 'github', 'a:0:{}', NULL, 0, NULL, NULL, NULL, 0, '2022-05-13 21:38:53', NULL),
(213, 'group', 'a:12:{i:0;s:11:\"indexAction\";i:1;s:9:\"newAction\";i:2;s:10:\"editAction\";i:3;s:11:\"trashAction\";i:4;s:13:\"untrashAction\";i:5;s:16:\"hardDeleteAction\";i:6;s:12:\"deleteAction\";i:7;s:14:\"assignedAction\";i:8;s:14:\"settingsAction\";i:11;s:15:\"changeRowAction\";i:12;s:22:\"discoveryRefreshAction\";i:19;s:16:\"chooseBulkAction\";}', NULL, 0, 'a:2:{i:13;s:12:\"importAction\";i:14;s:12:\"exportAction\";}', NULL, 2, 0, '2022-05-13 21:38:53', NULL),
(214, 'history', 'a:4:{i:0;s:11:\"indexAction\";i:3;s:14:\"settingsAction\";i:4;s:15:\"changeRowAction\";i:5;s:22:\"discoveryRefreshAction\";}', NULL, 0, 'a:2:{i:5;s:12:\"importAction\";i:6;s:12:\"exportAction\";}', NULL, 2, 0, '2022-05-13 21:38:54', NULL),
(215, 'menu', 'a:16:{i:0;s:11:\"trashAction\";i:1;s:11:\"indexAction\";i:2;s:9:\"newAction\";i:3;s:10:\"editAction\";i:4;s:13:\"untrashAction\";i:5;s:16:\"hardDeleteAction\";i:6;s:12:\"deleteAction\";i:7;s:16:\"removeItemAction\";i:8;s:12:\"toggleAction\";i:9;s:14:\"untoggleAction\";i:10;s:14:\"settingsAction\";i:11;s:15:\"quickSaveAction\";i:14;s:15:\"changeRowAction\";i:15;s:22:\"discoveryRefreshAction\";i:22;s:16:\"chooseBulkAction\";i:23;s:15:\"quickEditAction\";}', NULL, 0, 'a:2:{i:16;s:12:\"importAction\";i:17;s:12:\"exportAction\";}', NULL, 2, 0, '2022-05-13 21:38:54', '2022-06-05 19:19:38'),
(216, 'message', 'a:13:{i:1;s:11:\"indexAction\";i:2;s:9:\"newAction\";i:3;s:10:\"showAction\";i:4;s:11:\"replyAction\";i:5;s:13:\"forwardAction\";i:6;s:13:\"starredAction\";i:7;s:15:\"unstarredAction\";i:8;s:12:\"markedAction\";i:9;s:14:\"unmarkedAction\";i:10;s:14:\"settingsAction\";i:11;s:11:\"draftAction\";i:14;s:15:\"changeRowAction\";i:15;s:22:\"discoveryRefreshAction\";}', NULL, 0, NULL, NULL, NULL, 0, '2022-05-13 21:38:54', NULL),
(217, 'notification', 'a:6:{i:0;s:11:\"indexAction\";i:1;s:10:\"showAction\";i:2;s:14:\"settingsAction\";i:5;s:15:\"changeRowAction\";i:6;s:22:\"discoveryRefreshAction\";i:13;s:16:\"chooseBulkAction\";}', NULL, 0, NULL, NULL, NULL, 0, '2022-05-13 21:38:54', NULL),
(218, 'permission', 'a:10:{i:0;s:11:\"indexAction\";i:1;s:9:\"newAction\";i:2;s:10:\"editAction\";i:3;s:11:\"trashAction\";i:4;s:13:\"untrashAction\";i:5;s:16:\"hardDeleteAction\";i:6;s:14:\"settingsAction\";i:9;s:15:\"changeRowAction\";i:10;s:22:\"discoveryRefreshAction\";i:17;s:16:\"chooseBulkAction\";}', NULL, 0, 'a:2:{i:10;s:12:\"importAction\";i:11;s:12:\"exportAction\";}', NULL, 2, 0, '2022-05-13 21:38:54', '2022-06-05 17:07:43'),
(219, 'plugin', 'a:4:{i:0;s:11:\"indexAction\";i:3;s:14:\"settingsAction\";i:4;s:15:\"changeRowAction\";i:5;s:22:\"discoveryRefreshAction\";}', NULL, 0, NULL, NULL, NULL, 0, '2022-05-13 21:38:54', NULL),
(220, 'post', 'a:9:{i:1;s:11:\"indexAction\";i:2;s:9:\"newAction\";i:3;s:10:\"editAction\";i:4;s:11:\"trashAction\";i:5;s:13:\"untrashAction\";i:6;s:16:\"hardDeleteAction\";i:7;s:14:\"settingsAction\";i:10;s:15:\"changeRowAction\";i:11;s:22:\"discoveryRefreshAction\";}', NULL, 0, NULL, NULL, NULL, 0, '2022-05-13 21:38:54', NULL),
(221, 'profile', 'a:24:{i:0;s:11:\"indexAction\";i:1;s:14:\"overviewAction\";i:2;s:10:\"showAction\";i:3;s:9:\"newAction\";i:4;s:10:\"editAction\";i:5;s:12:\"deleteAction\";i:6;s:11:\"cloneAction\";i:7;s:16:\"hardDeleteAction\";i:8;s:10:\"lockAction\";i:9;s:12:\"unlockAction\";i:10;s:11:\"trashAction\";i:11;s:13:\"untrashAction\";i:12;s:18:\"trashRestoreAction\";i:13;s:12:\"activeAction\";i:14;s:17:\"preferencesAction\";i:15;s:15:\"privilegeAction\";i:16;s:25:\"privilegeExpirationAction\";i:17;s:9:\"logAction\";i:18;s:11:\"notesAction\";i:19;s:14:\"personalAction\";i:20;s:14:\"settingsAction\";i:23;s:15:\"changeRowAction\";i:24;s:22:\"discoveryRefreshAction\";i:31;s:16:\"chooseBulkAction\";}', NULL, 0, NULL, NULL, NULL, 0, '2022-05-13 21:38:54', NULL),
(222, 'role', 'a:13:{i:0;s:11:\"indexAction\";i:1;s:9:\"newAction\";i:2;s:10:\"editAction\";i:3;s:11:\"trashAction\";i:4;s:13:\"untrashAction\";i:5;s:16:\"hardDeleteAction\";i:6;s:14:\"assignedAction\";i:7;s:9:\"logAction\";i:8;s:24:\"unassignPermissionAction\";i:9;s:14:\"settingsAction\";i:12;s:15:\"changeRowAction\";i:13;s:22:\"discoveryRefreshAction\";i:20;s:16:\"chooseBulkAction\";}', NULL, 0, 'a:2:{i:13;s:12:\"importAction\";i:14;s:12:\"exportAction\";}', NULL, 2, 0, '2022-05-13 21:38:54', '2022-06-05 18:41:23'),
(223, 'search', 'a:4:{i:0;s:12:\"searchAction\";i:3;s:14:\"settingsAction\";i:4;s:15:\"changeRowAction\";i:5;s:22:\"discoveryRefreshAction\";}', NULL, 0, NULL, NULL, NULL, 0, '2022-05-13 21:38:54', NULL),
(224, 'setting', 'a:12:{i:0;s:11:\"indexAction\";i:1;s:13:\"generalAction\";i:2;s:14:\"securityAction\";i:3;s:11:\"purgeAction\";i:4;s:11:\"toolsAction\";i:5;s:18:\"localisationAction\";i:6;s:14:\"brandingAction\";i:7;s:15:\"extensionAction\";i:8;s:17:\"applicationAction\";i:11;s:14:\"settingsAction\";i:12;s:15:\"changeRowAction\";i:13;s:22:\"discoveryRefreshAction\";}', NULL, 0, 'a:3:{i:13;s:12:\"importAction\";i:14;s:12:\"exportAction\";i:22;s:16:\"chooseBulkAction\";}', NULL, 3, 0, '2022-05-13 21:38:54', '2022-06-05 18:30:15'),
(225, 'support', 'a:5:{i:0;s:19:\"documentationAction\";i:1;s:15:\"changelogAction\";i:4;s:14:\"settingsAction\";i:5;s:15:\"changeRowAction\";i:6;s:22:\"discoveryRefreshAction\";}', NULL, 0, 'a:3:{i:6;s:12:\"importAction\";i:7;s:12:\"exportAction\";i:15;s:16:\"chooseBulkAction\";}', NULL, 3, 0, '2022-05-13 21:38:54', '2022-06-05 18:30:08'),
(226, 'system', 'a:6:{i:1;s:11:\"indexAction\";i:2;s:10:\"showAction\";i:3;s:11:\"trashAction\";i:5;s:14:\"settingsAction\";i:6;s:15:\"changeRowAction\";i:7;s:22:\"discoveryRefreshAction\";}', NULL, 0, 'a:3:{i:4;s:23:\"requestPermissionAction\";i:8;s:12:\"importAction\";i:9;s:12:\"exportAction\";}', NULL, 3, 0, '2022-05-13 21:38:54', NULL),
(227, 'tag', 'a:11:{i:0;s:11:\"indexAction\";i:1;s:9:\"newAction\";i:2;s:10:\"editAction\";i:3;s:11:\"trashAction\";i:4;s:13:\"untrashAction\";i:5;s:16:\"hardDeleteAction\";i:6;s:14:\"settingsAction\";i:7;s:10:\"testAction\";i:10;s:15:\"changeRowAction\";i:11;s:22:\"discoveryRefreshAction\";i:18;s:16:\"chooseBulkAction\";}', NULL, 0, 'a:2:{i:12;s:12:\"importAction\";i:13;s:12:\"exportAction\";}', NULL, 2, 0, '2022-05-13 21:38:54', NULL),
(228, 'ticket', 'a:10:{i:1;s:11:\"indexAction\";i:2;s:9:\"newAction\";i:3;s:10:\"editAction\";i:4;s:11:\"trashAction\";i:5;s:13:\"untrashAction\";i:6;s:16:\"hardDeleteAction\";i:7;s:14:\"settingsAction\";i:10;s:15:\"changeRowAction\";i:11;s:22:\"discoveryRefreshAction\";i:17;s:16:\"chooseBulkAction\";}', NULL, 0, 'a:2:{i:11;s:12:\"importAction\";i:12;s:12:\"exportAction\";}', NULL, 2, 0, '2022-05-13 21:38:54', '2022-06-05 18:50:00'),
(229, 'user', 'a:24:{i:0;s:11:\"indexAction\";i:1;s:14:\"overviewAction\";i:2;s:10:\"showAction\";i:3;s:9:\"newAction\";i:4;s:10:\"editAction\";i:5;s:12:\"deleteAction\";i:6;s:11:\"cloneAction\";i:7;s:16:\"hardDeleteAction\";i:8;s:10:\"lockAction\";i:9;s:12:\"unlockAction\";i:10;s:11:\"trashAction\";i:11;s:13:\"untrashAction\";i:12;s:18:\"trashRestoreAction\";i:13;s:12:\"activeAction\";i:14;s:17:\"preferencesAction\";i:15;s:15:\"privilegeAction\";i:16;s:25:\"privilegeExpirationAction\";i:17;s:9:\"logAction\";i:18;s:11:\"notesAction\";i:19;s:14:\"personalAction\";i:20;s:14:\"settingsAction\";i:23;s:15:\"changeRowAction\";i:24;s:22:\"discoveryRefreshAction\";i:31;s:16:\"chooseBulkAction\";}', NULL, 0, 'a:4:{i:0;s:9:\"getAction\";i:1;s:10:\"testAction\";i:26;s:12:\"importAction\";i:27;s:12:\"exportAction\";}', NULL, 4, 0, '2022-05-13 21:38:54', '2022-06-05 17:13:58'),
(230, 'userrole', 'a:3:{i:2;s:14:\"settingsAction\";i:3;s:15:\"changeRowAction\";i:4;s:22:\"discoveryRefreshAction\";}', NULL, 0, NULL, NULL, NULL, 0, '2022-05-13 21:38:54', NULL),
(231, 'home', 'a:1:{i:1;s:11:\"indexAction\";}', NULL, 0, NULL, NULL, NULL, 0, '2022-05-14 00:12:31', NULL),
(232, 'security', 'a:2:{i:1;s:11:\"indexAction\";i:2;s:13:\"sessionAction\";}', NULL, 0, NULL, NULL, NULL, 0, '2022-05-14 00:12:34', NULL),
(233, 'error', 'a:1:{i:0;s:11:\"indexAction\";}', NULL, 0, 'a:3:{i:0;s:11:\"errorAction\";i:1;s:12:\"errormAction\";i:2;s:12:\"erroraAction\";}', NULL, 3, 0, '2022-05-17 19:28:23', NULL),
(234, 'logout', 'a:1:{i:1;s:12:\"logoutAction\";}', NULL, 0, NULL, NULL, NULL, 0, '2022-05-22 10:54:43', NULL),
(235, 'leave', 'a:12:{i:2;s:14:\"settingsAction\";i:3;s:15:\"changeRowAction\";i:4;s:22:\"discoveryRefreshAction\";i:5;s:12:\"importAction\";i:6;s:12:\"exportAction\";i:13;s:16:\"chooseBulkAction\";i:14;s:11:\"indexAction\";i:15;s:9:\"newAction\";i:16;s:10:\"editAction\";i:17;s:11:\"trashAction\";i:18;s:13:\"untrashAction\";i:19;s:16:\"hardDeleteAction\";}', NULL, 0, NULL, NULL, NULL, 0, '2022-06-01 16:29:21', NULL),
(236, 'adminleaves', 'a:6:{i:2;s:14:\"settingsAction\";i:3;s:15:\"changeRowAction\";i:4;s:22:\"discoveryRefreshAction\";i:5;s:12:\"importAction\";i:6;s:12:\"exportAction\";i:13;s:16:\"chooseBulkAction\";}', NULL, 0, NULL, NULL, NULL, 0, '2022-06-01 16:42:13', NULL),
(237, 'costcentre', 'a:6:{i:2;s:14:\"settingsAction\";i:3;s:15:\"changeRowAction\";i:4;s:22:\"discoveryRefreshAction\";i:5;s:12:\"importAction\";i:6;s:12:\"exportAction\";i:13;s:16:\"chooseBulkAction\";}', NULL, 0, NULL, NULL, NULL, 0, '2022-06-01 16:42:13', NULL),
(238, 'employeeleaves', 'a:6:{i:2;s:14:\"settingsAction\";i:3;s:15:\"changeRowAction\";i:4;s:22:\"discoveryRefreshAction\";i:5;s:12:\"importAction\";i:6;s:12:\"exportAction\";i:13;s:16:\"chooseBulkAction\";}', NULL, 0, NULL, NULL, NULL, 0, '2022-06-01 16:42:13', NULL),
(239, 'holiday', 'a:14:{i:0;s:11:\"indexAction\";i:1;s:9:\"newAction\";i:2;s:10:\"editAction\";i:3;s:14:\"settingsAction\";i:6;s:15:\"changeRowAction\";i:7;s:22:\"discoveryRefreshAction\";i:8;s:12:\"importAction\";i:9;s:12:\"exportAction\";i:16;s:16:\"chooseBulkAction\";i:17;s:11:\"trashAction\";i:18;s:13:\"untrashAction\";i:19;s:16:\"hardDeleteAction\";i:20;s:12:\"activeAction\";i:21;s:14:\"deactiveAction\";}', NULL, 0, NULL, NULL, NULL, 0, '2022-06-03 23:17:48', NULL),
(240, 'costcenter', 'a:14:{i:0;s:11:\"indexAction\";i:1;s:9:\"newAction\";i:2;s:10:\"editAction\";i:3;s:11:\"trashAction\";i:4;s:13:\"untrashAction\";i:5;s:16:\"hardDeleteAction\";i:6;s:12:\"activeAction\";i:7;s:14:\"deactiveAction\";i:8;s:14:\"settingsAction\";i:11;s:15:\"changeRowAction\";i:12;s:22:\"discoveryRefreshAction\";i:13;s:12:\"importAction\";i:14;s:12:\"exportAction\";i:21;s:16:\"chooseBulkAction\";}', NULL, 0, NULL, NULL, NULL, 0, '2022-06-04 16:28:20', NULL),
(241, 'project', 'a:14:{i:0;s:11:\"indexAction\";i:1;s:9:\"newAction\";i:2;s:10:\"editAction\";i:3;s:11:\"trashAction\";i:4;s:13:\"untrashAction\";i:5;s:16:\"hardDeleteAction\";i:6;s:12:\"activeAction\";i:7;s:14:\"deactiveAction\";i:8;s:14:\"settingsAction\";i:11;s:15:\"changeRowAction\";i:12;s:22:\"discoveryRefreshAction\";i:13;s:12:\"importAction\";i:14;s:12:\"exportAction\";i:21;s:16:\"chooseBulkAction\";}', NULL, 0, NULL, NULL, NULL, 0, '2022-06-04 21:17:00', NULL),
(242, 'branch', 'a:14:{i:0;s:11:\"indexAction\";i:1;s:9:\"newAction\";i:2;s:10:\"editAction\";i:3;s:11:\"trashAction\";i:4;s:13:\"untrashAction\";i:5;s:16:\"hardDeleteAction\";i:6;s:12:\"activeAction\";i:7;s:14:\"deactiveAction\";i:8;s:14:\"settingsAction\";i:11;s:15:\"changeRowAction\";i:12;s:22:\"discoveryRefreshAction\";i:13;s:12:\"importAction\";i:14;s:12:\"exportAction\";i:21;s:16:\"chooseBulkAction\";}', NULL, 0, NULL, NULL, NULL, 0, '2022-06-04 21:53:16', NULL),
(243, 'timesheet', 'a:14:{i:0;s:11:\"indexAction\";i:1;s:9:\"newAction\";i:2;s:10:\"editAction\";i:3;s:11:\"trashAction\";i:4;s:13:\"untrashAction\";i:5;s:16:\"hardDeleteAction\";i:6;s:12:\"activeAction\";i:7;s:14:\"deactiveAction\";i:10;s:15:\"changeRowAction\";i:11;s:22:\"discoveryRefreshAction\";i:12;s:12:\"importAction\";i:13;s:12:\"exportAction\";i:14;s:14:\"settingsAction\";i:21;s:16:\"chooseBulkAction\";}', NULL, 0, NULL, NULL, NULL, 0, '2022-06-05 19:18:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `controller_settings`
--

CREATE TABLE `controller_settings` (
  `id` int(10) NOT NULL,
  `controller_menu_id` int(10) UNSIGNED NOT NULL,
  `controller_name` varchar(100) NOT NULL,
  `records_per_page` int(3) DEFAULT NULL,
  `additional_conditions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`additional_conditions`)),
  `visibility` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `sortable` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `searchable` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `query_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `query` varchar(30) DEFAULT NULL,
  `alias` varchar(10) DEFAULT NULL,
  `filter` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cost_centers`
--

CREATE TABLE `cost_centers` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL,
  `slug` varchar(40) NOT NULL,
  `code` varchar(10) DEFAULT NULL,
  `description` tinytext DEFAULT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'active',
  `created_byid` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cost_centers`
--

INSERT INTO `cost_centers` (`id`, `name`, `slug`, `code`, `description`, `status`, `created_byid`, `created_at`, `modified_at`, `deleted_at`) VALUES
(1, 'Apprentice Time', 'apprentice-time', 'LAB-APP', 'Cost center for apprentice time on site', 'active', 1270, '2022-06-04 20:34:55', '2022-06-05 17:15:12', 0),
(2, 'Bank Holiday', 'bank-holiday', 'LAB-BAN', 'Cost center for bank holidays', 'active', 1270, '2022-06-04 20:36:50', '2022-06-05 17:15:12', 0),
(3, 'Covid 19 Idle Time', 'covid-19-idle-time', 'LAB-CVID', 'Cost center for covid 19 idle time', 'active', 1270, '2022-06-04 20:37:32', '2022-06-05 17:15:12', 0),
(4, 'Expenses', 'expenses', 'LAB-EEX', 'Cost center for employee expenses', 'active', 1270, '2022-06-04 20:38:06', '2022-06-05 17:15:12', 0),
(5, 'Furlough Leave', 'furlough-leave', 'LAB-FURL', 'Cost center for furlough leave', 'active', 1270, '2022-06-04 20:38:46', '2022-06-05 17:15:12', 0),
(6, 'Standard Holiday', 'standard-holiday', 'LAB-HOL', 'Cost center for standard holidays', 'active', 1270, '2022-06-04 20:42:08', NULL, 0),
(7, 'Management Time', 'management-time', 'LAB-MAN', 'Cost center for management time', 'active', 1270, '2022-06-04 20:42:32', NULL, 0),
(8, 'Office Time', 'office-time', 'LAB-OFF', 'Cost center for office time', 'active', 1270, '2022-06-04 20:42:53', NULL, 0),
(9, 'Any Other Time', 'any-other-time', 'LAB-OTH', 'Cost center for any other time', 'active', 1270, '2022-06-04 20:43:19', NULL, 0),
(10, 'Sickness Absense', 'sickness-absense', 'LAB-SIC', 'Cost center for sickness absense', 'active', 1270, '2022-06-04 20:43:47', NULL, 0),
(11, 'Pre-Contract Survey Work', 'pre-contract-survey-work', 'LAB-SUR', 'Cost center for pre-contract survey work', 'active', 1270, '2022-06-04 20:44:18', NULL, 0),
(12, 'Training', 'training', 'LAB-TRA', 'Cost center for training', 'active', 1270, '2022-06-04 20:44:41', NULL, 0),
(13, 'Travelling Time', 'travelling-time', 'LAB-TRV', 'Cost center for travelling time', 'active', 1270, '2022-06-04 20:45:05', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `dashboard`
--

CREATE TABLE `dashboard` (
  `id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `event_log`
--

CREATE TABLE `event_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `event_log_name` varchar(10) NOT NULL,
  `event_type` varchar(100) NOT NULL,
  `source` tinytext NOT NULL,
  `user` int(10) UNSIGNED NOT NULL,
  `method` varchar(100) NOT NULL,
  `event_context` longtext CHARACTER SET utf8 NOT NULL,
  `event_browser` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `IP` int(4) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `event_log`
--

INSERT INTO `event_log` (`id`, `event_log_name`, `event_type`, `source`, `user`, `method`, `event_context`, `event_browser`, `IP`, `created_at`) VALUES
(123, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:5:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:357;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-12 17:11:17'),
(124, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:5:{s:9:\"menu_name\";s:4:\"user\";s:16:\"menu_description\";s:22:\"user parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:358;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-12 17:15:27'),
(125, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:5:{s:9:\"menu_name\";s:4:\"role\";s:16:\"menu_description\";s:22:\"role parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:359;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-12 17:15:58'),
(126, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:5:{s:9:\"menu_name\";s:10:\"permission\";s:16:\"menu_description\";s:28:\"permission parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:360;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-12 17:16:03'),
(127, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:5:{s:9:\"menu_name\";s:4:\"menu\";s:16:\"menu_description\";s:22:\"menu parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:361;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-12 17:16:14'),
(128, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:0:\"\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:362;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-12 17:31:10'),
(129, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:363;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-12 17:31:55'),
(130, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"menu\";s:16:\"menu_description\";s:22:\"menu parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:364;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-12 17:32:15'),
(131, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"user\";s:16:\"menu_description\";s:22:\"user parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:365;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-12 17:45:04'),
(132, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:10:\"permission\";s:16:\"menu_description\";s:28:\"permission parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:366;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-12 18:45:56'),
(133, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"role\";s:16:\"menu_description\";s:22:\"role parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:367;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-12 18:46:01'),
(134, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"setting\";s:16:\"menu_description\";s:25:\"setting parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:368;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-12 19:02:22'),
(135, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"message\";s:16:\"menu_description\";s:25:\"message parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:369;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-15 23:25:56'),
(136, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:370;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 11:29:44'),
(137, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"role\";s:16:\"menu_description\";s:22:\"role parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:371;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 11:30:22'),
(138, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"role\";s:16:\"menu_description\";s:22:\"role parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:372;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 11:31:44'),
(139, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"role\";s:16:\"menu_description\";s:22:\"role parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:373;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 11:35:39'),
(140, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"role\";s:16:\"menu_description\";s:22:\"role parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:374;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 11:36:56'),
(141, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"role\";s:16:\"menu_description\";s:22:\"role parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:377;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 11:41:14'),
(142, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:10:\"permission\";s:16:\"menu_description\";s:28:\"permission parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:379;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 11:43:24'),
(143, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"role\";s:16:\"menu_description\";s:22:\"role parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:380;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 11:44:48'),
(144, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"user\";s:16:\"menu_description\";s:22:\"user parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:381;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 11:45:03'),
(145, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:10:\"permission\";s:16:\"menu_description\";s:28:\"permission parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:382;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 11:45:14'),
(146, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"role\";s:16:\"menu_description\";s:22:\"role parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:383;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 11:52:58'),
(147, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:10:\"permission\";s:16:\"menu_description\";s:28:\"permission parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:384;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 11:53:33'),
(148, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:387;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 11:59:12'),
(149, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:388;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 12:00:25'),
(150, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:389;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 12:00:49'),
(151, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:390;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 12:01:32'),
(152, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:391;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 12:02:25'),
(153, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:392;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 12:03:22'),
(154, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"role\";s:16:\"menu_description\";s:22:\"role parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:393;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 12:03:45'),
(155, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:10:\"permission\";s:16:\"menu_description\";s:28:\"permission parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:394;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 12:05:33'),
(156, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:10:\"permission\";s:16:\"menu_description\";s:28:\"permission parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:395;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 12:06:00'),
(157, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:396;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 12:06:30'),
(158, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:397;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 12:06:41'),
(159, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:398;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 12:07:59');
INSERT INTO `event_log` (`id`, `event_log_name`, `event_type`, `source`, `user`, `method`, `event_context`, `event_browser`, `IP`, `created_at`) VALUES
(160, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"role\";s:16:\"menu_description\";s:22:\"role parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:399;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 12:08:13'),
(161, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:10:\"permission\";s:16:\"menu_description\";s:28:\"permission parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:400;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 12:09:07'),
(162, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:401;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 12:37:58'),
(163, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:402;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 12:38:29'),
(164, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:403;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 12:46:54'),
(165, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:404;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 12:48:36'),
(166, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"role\";s:16:\"menu_description\";s:22:\"role parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:405;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 12:48:49'),
(167, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"role\";s:16:\"menu_description\";s:22:\"role parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:406;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 12:50:32'),
(168, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:10:\"permission\";s:16:\"menu_description\";s:28:\"permission parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:407;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 12:51:48'),
(169, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"role\";s:16:\"menu_description\";s:22:\"role parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:408;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 12:54:04'),
(170, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"role\";s:16:\"menu_description\";s:22:\"role parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:409;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 12:55:19'),
(171, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:410;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 22:29:24'),
(172, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"user\";s:16:\"menu_description\";s:22:\"user parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:411;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 22:30:52'),
(173, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:10:\"permission\";s:16:\"menu_description\";s:28:\"permission parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:412;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 22:31:03'),
(174, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"setting\";s:16:\"menu_description\";s:25:\"setting parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:413;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 22:31:09'),
(175, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"menu\";s:16:\"menu_description\";s:22:\"menu parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:414;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-20 22:31:14'),
(176, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"menu\";s:16:\"menu_description\";s:22:\"menu parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:415;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-22 12:24:13'),
(177, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"message\";s:16:\"menu_description\";s:25:\"message parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:416;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-22 12:24:48'),
(178, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:417;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-22 12:25:27'),
(179, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"menu\";s:16:\"menu_description\";s:22:\"menu parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:418;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-22 12:27:31'),
(180, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"role\";s:16:\"menu_description\";s:22:\"role parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:420;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-22 12:30:07'),
(181, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:10:\"permission\";s:16:\"menu_description\";s:28:\"permission parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:421;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-22 12:50:13'),
(182, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"setting\";s:16:\"menu_description\";s:25:\"setting parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:422;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-22 12:50:20'),
(183, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"user\";s:16:\"menu_description\";s:22:\"user parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:423;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-22 12:50:27'),
(184, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:425;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-22 13:05:02'),
(185, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"user\";s:16:\"menu_description\";s:22:\"user parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:426;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-22 13:06:10'),
(186, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"role\";s:16:\"menu_description\";s:22:\"role parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:427;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-22 13:06:40'),
(187, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:10:\"permission\";s:16:\"menu_description\";s:28:\"permission parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:428;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-22 13:06:55'),
(188, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"setting\";s:16:\"menu_description\";s:25:\"setting parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:429;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-22 13:07:25'),
(189, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:431;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-22 13:15:50'),
(190, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"user\";s:16:\"menu_description\";s:22:\"user parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:432;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-22 13:17:22'),
(191, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"role\";s:16:\"menu_description\";s:22:\"role parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:433;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-22 13:17:28'),
(192, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:10:\"permission\";s:16:\"menu_description\";s:28:\"permission parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:434;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-22 13:17:32'),
(193, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"setting\";s:16:\"menu_description\";s:25:\"setting parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:435;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-22 13:17:37'),
(194, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"menu\";s:16:\"menu_description\";s:22:\"menu parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:436;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-22 13:17:41'),
(195, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:437;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-22 14:40:19'),
(196, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:439;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-22 15:08:53');
INSERT INTO `event_log` (`id`, `event_log_name`, `event_type`, `source`, `user`, `method`, `event_context`, `event_browser`, `IP`, `created_at`) VALUES
(197, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:441;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-22 15:19:30'),
(198, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"user\";s:16:\"menu_description\";s:22:\"user parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:442;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-22 15:23:39'),
(199, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"menu\";s:16:\"menu_description\";s:22:\"menu parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:443;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-22 15:31:38'),
(200, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:445;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-22 15:54:37'),
(201, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"user\";s:16:\"menu_description\";s:22:\"user parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:446;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-22 15:54:43'),
(202, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"role\";s:16:\"menu_description\";s:22:\"role parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:447;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-22 15:54:50'),
(203, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:10:\"permission\";s:16:\"menu_description\";s:28:\"permission parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:448;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-22 15:54:54'),
(204, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"setting\";s:16:\"menu_description\";s:25:\"setting parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:449;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-22 15:54:58'),
(205, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"menu\";s:16:\"menu_description\";s:22:\"menu parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:450;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-22 15:55:01'),
(206, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:451;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-22 16:32:08'),
(207, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:5:\"group\";s:16:\"menu_description\";s:23:\"group parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:452;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-23 00:08:25'),
(208, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"message\";s:16:\"menu_description\";s:25:\"message parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:453;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-23 00:26:18'),
(209, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:454;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-23 10:55:05'),
(210, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"user\";s:16:\"menu_description\";s:22:\"user parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:455;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-23 10:55:15'),
(211, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:5:\"group\";s:16:\"menu_description\";s:23:\"group parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:456;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-23 10:55:21'),
(212, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"role\";s:16:\"menu_description\";s:22:\"role parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:457;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-23 10:55:25'),
(213, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:10:\"permission\";s:16:\"menu_description\";s:28:\"permission parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:458;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-23 10:55:29'),
(214, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"menu\";s:16:\"menu_description\";s:22:\"menu parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:459;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-23 10:55:50'),
(215, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"setting\";s:16:\"menu_description\";s:25:\"setting parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:460;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-23 10:55:55'),
(216, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"message\";s:16:\"menu_description\";s:25:\"message parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:462;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-23 20:33:14'),
(217, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:6:\"ticket\";s:16:\"menu_description\";s:24:\"ticket parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:463;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-23 20:52:15'),
(218, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:6:\"system\";s:16:\"menu_description\";s:24:\"system parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:464;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-25 11:18:36'),
(219, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:465;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-27 21:35:21'),
(220, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:10:\"permission\";s:16:\"menu_description\";s:28:\"permission parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:466;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-27 21:38:50'),
(221, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:467;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-27 21:43:43'),
(222, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"user\";s:16:\"menu_description\";s:22:\"user parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:468;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-27 21:45:14'),
(223, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"role\";s:16:\"menu_description\";s:22:\"role parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:470;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-27 21:47:15'),
(224, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:10:\"permission\";s:16:\"menu_description\";s:28:\"permission parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:471;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-27 21:47:25'),
(225, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"menu\";s:16:\"menu_description\";s:22:\"menu parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:472;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-27 21:47:32'),
(226, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"setting\";s:16:\"menu_description\";s:25:\"setting parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:473;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-27 21:47:51'),
(227, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"message\";s:16:\"menu_description\";s:25:\"message parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:474;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-27 21:48:34'),
(228, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:6:\"ticket\";s:16:\"menu_description\";s:24:\"ticket parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:475;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-01-27 21:48:39'),
(229, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"post\";s:16:\"menu_description\";s:22:\"post parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:476;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-01 05:40:35'),
(230, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:8:\"category\";s:16:\"menu_description\";s:26:\"category parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:477;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-02 21:32:26'),
(231, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:479;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-06 09:49:54'),
(232, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:480;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-06 10:11:42'),
(233, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"user\";s:16:\"menu_description\";s:22:\"user parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:481;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-06 10:11:52');
INSERT INTO `event_log` (`id`, `event_log_name`, `event_type`, `source`, `user`, `method`, `event_context`, `event_browser`, `IP`, `created_at`) VALUES
(234, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"role\";s:16:\"menu_description\";s:22:\"role parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:482;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-06 10:11:59'),
(235, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:10:\"permission\";s:16:\"menu_description\";s:28:\"permission parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:483;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-06 10:12:06'),
(236, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:6:\"plugin\";s:16:\"menu_description\";s:24:\"plugin parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:484;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-06 10:12:11'),
(237, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"setting\";s:16:\"menu_description\";s:25:\"setting parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:485;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-06 10:12:21'),
(238, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:486;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-06 10:16:26'),
(239, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:8:\"category\";s:16:\"menu_description\";s:26:\"category parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:487;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-06 10:16:39'),
(240, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:3:\"tag\";s:16:\"menu_description\";s:21:\"tag parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:488;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-06 10:16:45'),
(241, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:10:\"permission\";s:16:\"menu_description\";s:28:\"permission parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:489;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-06 10:17:02'),
(242, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"post\";s:16:\"menu_description\";s:22:\"post parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:490;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-06 10:17:14'),
(243, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:5:\"group\";s:16:\"menu_description\";s:23:\"group parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:491;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-06 10:17:42'),
(244, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"user\";s:16:\"menu_description\";s:22:\"user parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:492;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-06 10:51:58'),
(245, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"message\";s:16:\"menu_description\";s:25:\"message parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:5:\"alert\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:493;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-06 12:21:17'),
(246, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:6:\"ticket\";s:16:\"menu_description\";s:24:\"ticket parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:494;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-07 21:59:01'),
(247, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:8:\"category\";s:16:\"menu_description\";s:26:\"category parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:495;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-08 21:56:23'),
(248, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:496;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-08 21:57:11'),
(249, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"user\";s:16:\"menu_description\";s:22:\"user parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:497;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-08 21:57:26'),
(250, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"post\";s:16:\"menu_description\";s:22:\"post parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:498;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-08 21:57:38'),
(251, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:10:\"permission\";s:16:\"menu_description\";s:28:\"permission parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:499;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-08 21:58:51'),
(252, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"setting\";s:16:\"menu_description\";s:25:\"setting parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:500;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-08 21:59:45'),
(253, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:8:\"category\";s:16:\"menu_description\";s:26:\"category parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:501;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-08 23:47:28'),
(254, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:3:\"tag\";s:16:\"menu_description\";s:21:\"tag parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:502;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-08 23:48:55'),
(255, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"role\";s:16:\"menu_description\";s:22:\"role parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:503;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-09 21:21:09'),
(256, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:5:\"group\";s:16:\"menu_description\";s:23:\"group parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:504;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-09 21:23:47'),
(257, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"menu\";s:16:\"menu_description\";s:22:\"menu parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:505;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-10 03:16:20'),
(258, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:506;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-10 03:16:55'),
(259, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:507;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-10 03:17:51'),
(260, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"setting\";s:16:\"menu_description\";s:25:\"setting parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:508;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-10 03:18:10'),
(261, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:509;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-10 03:21:56'),
(262, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:510;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-10 03:24:19'),
(263, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"setting\";s:16:\"menu_description\";s:25:\"setting parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:511;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-10 03:24:38'),
(264, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:6:\"ticket\";s:16:\"menu_description\";s:24:\"ticket parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:512;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-10 12:31:14'),
(265, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"message\";s:16:\"menu_description\";s:25:\"message parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:513;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-12 00:26:03'),
(266, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:6:\"plugin\";s:16:\"menu_description\";s:24:\"plugin parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:514;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-13 14:35:20'),
(267, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"menu\";s:16:\"menu_description\";s:22:\"menu parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:515;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-17 03:54:33'),
(268, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:516;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-17 03:54:58'),
(269, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"user\";s:16:\"menu_description\";s:22:\"user parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:517;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-17 10:23:33'),
(270, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"role\";s:16:\"menu_description\";s:22:\"role parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:518;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-17 10:23:43');
INSERT INTO `event_log` (`id`, `event_log_name`, `event_type`, `source`, `user`, `method`, `event_context`, `event_browser`, `IP`, `created_at`) VALUES
(271, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:10:\"permission\";s:16:\"menu_description\";s:28:\"permission parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:519;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-17 10:23:48'),
(272, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"setting\";s:16:\"menu_description\";s:25:\"setting parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:520;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-17 11:04:02'),
(273, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:6:\"ticket\";s:16:\"menu_description\";s:24:\"ticket parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:521;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-17 11:04:25'),
(274, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:8:\"category\";s:16:\"menu_description\";s:26:\"category parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:522;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-17 11:04:33'),
(275, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:3:\"tag\";s:16:\"menu_description\";s:21:\"tag parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:523;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-17 11:04:38'),
(276, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"message\";s:16:\"menu_description\";s:25:\"message parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:524;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-17 11:05:58'),
(277, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"discovery\";s:16:\"menu_description\";s:27:\"discovery parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:525;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-20 16:45:47'),
(278, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"post\";s:16:\"menu_description\";s:22:\"post parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:526;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-02-22 06:30:58'),
(279, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:6:\"system\";s:16:\"menu_description\";s:24:\"system parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:527;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-04-14 11:30:30'),
(280, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"history\";s:16:\"menu_description\";s:25:\"history parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:528;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-04-18 12:48:47'),
(281, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"support\";s:16:\"menu_description\";s:25:\"support parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:529;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-04-20 19:04:56'),
(282, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:5:\"group\";s:16:\"menu_description\";s:23:\"group parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:530;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-01 15:36:07'),
(283, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:5:\"group\";s:16:\"menu_description\";s:23:\"group parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:531;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-09 20:59:42'),
(284, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:532;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-12 21:37:11'),
(285, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:6:\"system\";s:16:\"menu_description\";s:24:\"system parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:533;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-12 21:38:55'),
(286, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"menu\";s:16:\"menu_description\";s:22:\"menu parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:534;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-12 21:39:15'),
(287, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"support\";s:16:\"menu_description\";s:25:\"support parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:535;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-12 21:40:10'),
(288, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"history\";s:16:\"menu_description\";s:25:\"history parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:536;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-12 21:41:11'),
(289, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"user\";s:16:\"menu_description\";s:22:\"user parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:537;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-12 22:25:23'),
(290, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:538;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-12 22:29:29'),
(291, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"user\";s:16:\"menu_description\";s:22:\"user parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:539;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-12 22:29:40'),
(292, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"menu\";s:16:\"menu_description\";s:22:\"menu parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:540;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-12 22:29:58'),
(293, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"message\";s:16:\"menu_description\";s:25:\"message parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:541;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-12 22:30:01'),
(294, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:8:\"category\";s:16:\"menu_description\";s:26:\"category parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:542;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-12 22:30:12'),
(295, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:3:\"tag\";s:16:\"menu_description\";s:21:\"tag parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:543;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-12 22:30:25'),
(296, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"post\";s:16:\"menu_description\";s:22:\"post parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:544;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-12 22:30:29'),
(297, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"history\";s:16:\"menu_description\";s:25:\"history parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:545;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 10:36:28'),
(298, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"support\";s:16:\"menu_description\";s:25:\"support parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:546;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 10:36:49'),
(299, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"setting\";s:16:\"menu_description\";s:25:\"setting parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:547;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 10:36:56'),
(300, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:6:\"system\";s:16:\"menu_description\";s:24:\"system parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:548;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 10:37:10'),
(301, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:549;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 11:08:37'),
(302, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:8:\"category\";s:16:\"menu_description\";s:26:\"category parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:550;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 11:53:00'),
(303, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:3:\"tag\";s:16:\"menu_description\";s:21:\"tag parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:551;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 11:53:07'),
(304, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:6:\"ticket\";s:16:\"menu_description\";s:24:\"ticket parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:552;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 11:53:53'),
(305, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"setting\";s:16:\"menu_description\";s:25:\"setting parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:553;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 17:23:40'),
(306, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"support\";s:16:\"menu_description\";s:25:\"support parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:554;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 17:24:23'),
(307, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"user\";s:16:\"menu_description\";s:22:\"user parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:555;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 17:24:26');
INSERT INTO `event_log` (`id`, `event_log_name`, `event_type`, `source`, `user`, `method`, `event_context`, `event_browser`, `IP`, `created_at`) VALUES
(308, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:6:\"system\";s:16:\"menu_description\";s:24:\"system parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:556;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 17:24:32'),
(309, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"menu\";s:16:\"menu_description\";s:22:\"menu parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:557;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 17:24:39'),
(310, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:558;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 21:26:13'),
(311, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"user\";s:16:\"menu_description\";s:22:\"user parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:559;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 21:26:19'),
(312, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:6:\"system\";s:16:\"menu_description\";s:24:\"system parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:560;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 21:29:35'),
(313, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:561;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 21:30:07'),
(314, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"user\";s:16:\"menu_description\";s:22:\"user parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:562;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 21:30:22'),
(315, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:563;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 21:38:48'),
(316, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"user\";s:16:\"menu_description\";s:22:\"user parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:564;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 21:38:59'),
(317, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:565;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 21:40:44'),
(318, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"user\";s:16:\"menu_description\";s:22:\"user parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:566;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 21:40:48'),
(319, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:567;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 21:53:56'),
(320, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"user\";s:16:\"menu_description\";s:22:\"user parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:568;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 21:54:03'),
(321, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:569;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 21:55:23'),
(322, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"user\";s:16:\"menu_description\";s:22:\"user parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:570;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 21:55:28'),
(323, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"user\";s:16:\"menu_description\";s:22:\"user parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:571;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 21:56:15'),
(324, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:572;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 21:56:18'),
(325, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:8:\"category\";s:16:\"menu_description\";s:26:\"category parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:573;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 21:59:25'),
(326, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:3:\"tag\";s:16:\"menu_description\";s:21:\"tag parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:574;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 21:59:30'),
(327, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"menu\";s:16:\"menu_description\";s:22:\"menu parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:575;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 21:59:38'),
(328, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"message\";s:16:\"menu_description\";s:25:\"message parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:576;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 22:00:49'),
(329, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:6:\"ticket\";s:16:\"menu_description\";s:24:\"ticket parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:577;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 22:00:54'),
(330, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:6:\"system\";s:16:\"menu_description\";s:24:\"system parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:578;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 22:00:58'),
(331, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:579;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 22:38:22'),
(332, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"dashboard\";s:16:\"menu_description\";s:27:\"dashboard parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:580;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 22:40:02'),
(333, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"user\";s:16:\"menu_description\";s:22:\"user parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:581;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 22:40:19'),
(334, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:3:\"tag\";s:16:\"menu_description\";s:21:\"tag parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:582;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 22:40:51'),
(335, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:8:\"category\";s:16:\"menu_description\";s:26:\"category parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:583;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 22:40:56'),
(336, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:3:\"tag\";s:16:\"menu_description\";s:21:\"tag parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:584;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 22:45:02'),
(337, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:3:\"tag\";s:16:\"menu_description\";s:21:\"tag parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:585;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 22:45:35'),
(338, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:3:\"tag\";s:16:\"menu_description\";s:21:\"tag parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:586;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 22:49:13'),
(339, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:3:\"tag\";s:16:\"menu_description\";s:21:\"tag parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:587;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 22:49:40'),
(340, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:8:\"category\";s:16:\"menu_description\";s:26:\"category parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:588;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 22:49:44'),
(341, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:6:\"ticket\";s:16:\"menu_description\";s:24:\"ticket parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:589;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 22:49:48'),
(342, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"history\";s:16:\"menu_description\";s:25:\"history parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:590;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 22:49:51'),
(343, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"user\";s:16:\"menu_description\";s:22:\"user parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:591;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-13 22:50:04'),
(344, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"support\";s:16:\"menu_description\";s:25:\"support parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:592;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-14 00:12:43');
INSERT INTO `event_log` (`id`, `event_log_name`, `event_type`, `source`, `user`, `method`, `event_context`, `event_browser`, `IP`, `created_at`) VALUES
(345, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:6:\"system\";s:16:\"menu_description\";s:24:\"system parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:594;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-14 11:01:34'),
(346, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:10:\"permission\";s:16:\"menu_description\";s:28:\"permission parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:595;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-14 20:34:59'),
(347, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:4:\"role\";s:16:\"menu_description\";s:22:\"role parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:596;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-14 20:35:02'),
(348, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:5:\"error\";s:16:\"menu_description\";s:23:\"error parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:597;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-17 19:28:23'),
(349, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:5:\"group\";s:16:\"menu_description\";s:23:\"group parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:600;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-05-23 00:59:57'),
(350, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:5:\"leave\";s:16:\"menu_description\";s:23:\"leave parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:601;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-06-01 16:29:21'),
(351, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:5:\"leave\";s:16:\"menu_description\";s:23:\"leave parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:602;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-06-01 17:14:31'),
(352, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"holiday\";s:16:\"menu_description\";s:25:\"holiday parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:603;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-06-03 23:17:48'),
(353, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:10:\"costCenter\";s:16:\"menu_description\";s:28:\"costCenter parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:604;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-06-04 16:28:20'),
(354, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:7:\"project\";s:16:\"menu_description\";s:25:\"project parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:605;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-06-04 21:17:00'),
(355, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:6:\"branch\";s:16:\"menu_description\";s:24:\"branch parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:606;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-06-04 21:53:16'),
(356, 'system', 'information', 'magmacore.system.event_system_action_event', 1270, 'MagmaCore\\Base\\Traits\\ControllerMenuTrait::buildControllerMenu', 'a:3:{s:4:\"menu\";a:6:{s:9:\"menu_name\";s:9:\"timesheet\";s:16:\"menu_description\";s:27:\"timesheet parent menu item.\";s:10:\"menu_order\";N;s:16:\"menu_break_point\";N;s:9:\"menu_icon\";s:7:\"warning\";s:11:\"parent_menu\";i:1;}s:7:\"last_id\";i:607;s:6:\"status\";b:1;}', 'a:30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}', 0, '2022-06-05 19:19:45');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `group_name` varchar(64) NOT NULL,
  `group_description` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `created_byid` int(10) UNSIGNED NOT NULL,
  `deleted_at` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `group_name`, `group_description`, `created_at`, `modified_at`, `created_byid`, `deleted_at`) VALUES
(7, 'Master Author', 'Master author group groups author and contributor roles together which provides additional permissions', '2022-02-12 10:03:19', '2022-05-09 20:58:04', 1270, 1);

-- --------------------------------------------------------

--
-- Table structure for table `group_role`
--

CREATE TABLE `group_role` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `group_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `group_role`
--

INSERT INTO `group_role` (`role_id`, `group_id`) VALUES
(151, 2);

-- --------------------------------------------------------

--
-- Table structure for table `holidays`
--

CREATE TABLE `holidays` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(65) NOT NULL,
  `slug` varchar(65) NOT NULL,
  `description` tinytext DEFAULT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'active',
  `created_byid` int(10) UNSIGNED DEFAULT NULL,
  `holiday_date` varchar(20) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `holidays`
--

INSERT INTO `holidays` (`id`, `name`, `slug`, `description`, `status`, `created_byid`, `holiday_date`, `created_at`, `modified_at`, `deleted_at`) VALUES
(14, 'New Years Day', 'new-years-day', 'New Year&#39;s Day is a festival observed in most of the world on 1 January, the first day of the year in the modern Gregorian calendar. 1 January is also New Year&#39;s Day on the Julian calendar, but this is not the same day as the Gregorian one.', 'active', 1270, 'Jan 3rd', '2022-06-04 12:43:26', '2022-06-05 18:22:32', 0),
(15, 'Good Friday', 'good-friday', 'Good Friday is a Christian holiday commemorating the crucifixion of Jesus and his death at Calvary. It is observed during Holy Week as part of the Paschal Triduum. It is also known as Holy Friday, Great Friday, Great and Holy Friday, and Black Friday.', 'active', 1270, 'Apr 15th', '2022-06-04 12:45:42', '2022-06-04 14:53:27', 0),
(16, 'Easter Monday', 'easter-monday', 'Easter Monday refers to the day after Easter Sunday in either the Eastern or Western Christian traditions. It is a public holiday in some countries. It is the second day of Eastertide.', 'active', 1270, 'Apr 18th', '2022-06-04 12:46:47', '2022-06-04 14:53:30', 0),
(17, 'Easter May Bank Holiday', 'easter-may-bank-holiday', 'May Day, officially known as Early May Day Bank Holiday, is a combination of two holidays in the UK: May Day is an ancient celebration of spring, rebirth, and fertility. International Workers&#39; Day, also known as Labour Day, is about workers&#39; right', 'active', 1270, 'May 2nd', '2022-06-04 12:47:31', NULL, 0),
(18, 'Spring Bank Holiday', 'spring-bank-holiday', 'The spring bank holiday, also known as the late May bank holiday, is a time for people in the United Kingdom to have a day off work or school. It falls on the last Monday of May but it used to be on the Monday after Pentecost.', 'active', 1270, 'May 30th', '2022-06-04 12:48:20', NULL, 0),
(19, 'Summer Bank Holiday', 'summer-bank-holiday', 'In England, Wales and Northern Ireland, the summer bank holiday is on the last Monday of August. In Scotland it is on the first Monday of August. This day marks the end of the summer holidays for many people who return to work or school in the autumn.', 'active', 1270, 'Aug 29th', '2022-06-04 12:49:16', NULL, 0),
(25, 'Christmas Day', 'christmas-day', 'Christmas is an annual festival commemorating the birth of Jesus Christ, observed primarily on December 25 as a religious and cultural celebration among billions of people around the world.', 'active', 1270, 'Dec 27th', '2022-06-04 13:16:02', NULL, 0),
(26, 'Boxing Day', 'boxing-day', 'Boxing Day is a holiday celebrated after Christmas Day, occurring on the second day of Christmastide. Though it originated as a holiday to give gifts to the poor, today Boxing Day is primarily known as a shopping holiday.', 'active', 1270, 'Dec 26th', '2022-06-04 13:16:19', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `leave_types`
--

CREATE TABLE `leave_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `leave_type` varchar(20) NOT NULL,
  `leave_type_slug` varchar(20) DEFAULT NULL,
  `leave_description` text NOT NULL,
  `status` enum('active','disabled','','') NOT NULL DEFAULT 'active',
  `created_byid` int(10) UNSIGNED NOT NULL,
  `deleted_at` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `leave_types`
--

INSERT INTO `leave_types` (`id`, `leave_type`, `leave_type_slug`, `leave_description`, `status`, `created_byid`, `deleted_at`, `created_at`, `modified_at`) VALUES
(1, 'Sick', 'sick', 'A leave of absence granted because of illness.', 'active', 1270, 0, '2022-06-01 17:01:34', '2022-06-04 15:13:27'),
(2, 'Maternity', 'maternity', 'A period of absence from work granted to a mother before and after the birth of her child.', 'active', 1270, 0, '2022-06-01 17:27:20', '2022-06-05 17:23:48'),
(3, 'Paternity', 'paternity', 'A period of absence from work granted to a father after or shortly before the birth of his child.', 'active', 1270, 0, '2022-06-01 17:27:29', '2022-06-05 17:23:48'),
(4, 'Casual Leave', 'holidays', 'Casual Leave is granted to an eligible employee if they cannot report to work due to an unforeseen situation. Casual leave can also be utilised if an eligible employee wants to take leave for a couple of days for personal reasons, but not for a vacation.', 'active', 1270, 0, '2022-06-01 17:27:40', '2022-06-05 17:23:48'),
(5, 'Bank Holiday', 'bank-holiday', 'A bank holiday is a national public holiday in the United Kingdom, Republic of Ireland and the Crown dependencies. The term refers to all public holidays in the United Kingdom, be they set out in statute, declared by royal proclamation or held by convention under common law.', 'active', 1270, 0, '2022-06-01 17:27:52', '2022-06-05 17:23:48'),
(6, 'Holiday', 'holiday', 'A leave time accrued by working a state holiday or accrued when the holiday falls on a day the employee is not scheduled to work or is on paid sick leave. Holiday leave may be included in annual leave time.', 'active', 1270, 0, '2022-06-04 15:15:54', '2022-06-05 17:23:48'),
(7, 'Authorized', 'authorized', 'Authorized absence is where an employee is away from work due to a pre-agreed reason.', 'active', 1270, 0, '2022-06-04 15:17:48', '2022-06-05 17:23:48'),
(8, 'Training', 'training', 'The Personal training leave allows a private sector employee to take a leave from work for a training, under specific conditions; the objective is to enhance his/her qualifications or perspectives in his/her job or to prepare for a professional reconversion.', 'active', 1270, 0, '2022-06-04 15:18:17', '2022-06-05 17:23:48'),
(9, 'Other', 'other', 'Other denotes a leave other from what&#39;s define in the HR list of leaves.', 'active', 1270, 0, '2022-06-04 15:19:42', '2022-06-05 17:23:48');

-- --------------------------------------------------------

--
-- Table structure for table `localisations`
--

CREATE TABLE `localisations` (
  `id` int(10) UNSIGNED NOT NULL,
  `file_name` varchar(100) NOT NULL,
  `locale` varchar(20) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_size` float DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `localisations`
--

INSERT INTO `localisations` (`id`, `file_name`, `locale`, `file_path`, `file_size`, `created_at`, `modified_at`) VALUES
(13, 'english', 'gb', 'C:\\xampp\\htdocs\\MagmaSkeleton/Localegb.yml', NULL, '2022-01-25 22:23:26', NULL),
(14, 'french', 'fr', 'C:\\xampp\\htdocs\\MagmaSkeleton/Localepassword.yml', NULL, '2022-01-26 00:21:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int(10) UNSIGNED NOT NULL,
  `menu_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `menu_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_menu` tinyint(1) NOT NULL,
  `menu_order` int(10) DEFAULT NULL,
  `menu_break_point` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL,
  `menu_icon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` int(1) DEFAULT 0,
  `created_byid` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `menu_name`, `menu_description`, `parent_menu`, `menu_order`, `menu_break_point`, `menu_icon`, `created_at`, `modified_at`, `deleted_at`, `created_byid`) VALUES
(575, 'menu', 'menu parent menu item.', 0, NULL, NULL, 'warning', '2022-05-13 21:59:37', '2022-05-13 22:14:25', 0, 0),
(580, 'dashboard', 'dashboard parent menu item.', 1, 100, '', 'home', '2022-05-13 22:40:02', '2022-06-04 22:13:33', 0, 1270),
(587, 'tag', 'tag parent menu item.', 0, NULL, NULL, 'warning', '2022-05-13 22:49:40', '2022-06-04 16:06:48', 0, 0),
(588, 'category', 'category parent menu item.', 0, 98, NULL, 'warning', '2022-05-13 22:49:44', '2022-06-04 16:06:20', 0, 0),
(589, 'ticket', 'ticket parent menu item.', 0, NULL, NULL, 'warning', '2022-05-13 22:49:48', '2022-05-13 22:50:58', 0, 0),
(590, 'history', 'history parent menu item.', 0, NULL, NULL, 'warning', '2022-05-13 22:49:51', '2022-05-13 22:49:59', 0, 0),
(591, 'user', 'user parent menu item.', 1, 99, '', 'users', '2022-05-13 22:50:04', '2022-06-04 22:13:14', 0, 1270),
(592, 'support', 'support parent menu item.', 0, NULL, NULL, 'warning', '2022-05-14 00:12:43', '2022-05-14 11:02:15', 0, 0),
(593, 'setting', 'setting parent menu item.', 0, NULL, NULL, 'warning', '2022-05-14 10:14:11', '2022-05-14 11:02:30', 0, 0),
(594, 'system', 'system parent menu item.', 0, NULL, NULL, 'warning', '2022-05-14 11:01:34', '2022-05-14 11:02:25', 0, 0),
(595, 'permission', 'permission parent menu item.', 1, 0, '', 'lock', '2022-05-14 20:34:59', '2022-06-04 22:12:45', 0, 1270),
(596, 'role', 'role parent menu item.', 1, 0, '', 'user', '2022-05-14 20:35:02', '2022-06-04 22:14:00', 0, 1270),
(597, 'error', 'error parent menu item.', 0, NULL, NULL, 'warning', '2022-05-17 19:28:23', '2022-05-17 22:21:14', 0, 0),
(600, 'group', 'group parent menu item.', 0, NULL, NULL, 'warning', '2022-05-23 00:59:56', '2022-05-23 01:15:03', 0, 0),
(602, 'leave', 'leave parent menu item.', 1, 0, 'Manage', 'future', '2022-06-01 17:14:31', '2022-06-04 22:52:18', 0, 1270),
(603, 'holiday', 'holiday parent menu item.', 1, 0, '', 'calendar', '2022-06-03 23:17:48', '2022-06-04 13:18:46', 0, 1270),
(604, 'costCenter', 'costCenter parent menu item.', 1, 0, '', 'bag', '2022-06-04 16:28:20', '2022-06-04 20:46:25', 0, 1270),
(605, 'project', 'project parent menu item.', 1, 0, '', 'folder', '2022-06-04 21:17:00', '2022-06-04 21:30:23', 0, 1270),
(606, 'branch', 'branch parent menu item.', 1, 0, '', 'location', '2022-06-04 21:53:16', '2022-06-04 22:10:55', 0, 1270),
(607, 'timesheet', 'timesheet parent menu item.', 1, NULL, NULL, 'warning', '2022-06-05 19:19:45', NULL, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `item_original_id` int(10) UNSIGNED DEFAULT NULL,
  `item_original_label` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_label` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_type` varchar(24) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_url` varchar(190) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_order` int(10) DEFAULT NULL,
  `item_usable` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `item_original_id`, `item_original_label`, `item_label`, `item_type`, `item_url`, `item_order`, `item_usable`) VALUES
(2938, 575, 'menu_trashAction', 'trash', 'child_of_menu', '/admin/menu', 0, NULL),
(2939, 575, 'menu_indexAction', 'index', 'child_of_menu', '/admin/menu/index', 0, 1),
(2940, 575, 'menu_newAction', 'new', 'child_of_menu', '/admin/menu/new', 0, 1),
(2941, 575, 'menu_editAction', 'edit', 'child_of_menu', '/admin/menu', 0, NULL),
(2942, 575, 'menu_untrashAction', 'untrash', 'child_of_menu', '/admin/menu', 0, NULL),
(2943, 575, 'menu_hardDeleteAction', 'hardDelete', 'child_of_menu', '/admin/menu', 0, NULL),
(2944, 575, 'menu_deleteAction', 'delete', 'child_of_menu', '/admin/menu', 0, NULL),
(2945, 575, 'menu_removeItemAction', 'removeItem', 'child_of_menu', '/admin/menu', 0, NULL),
(2946, 575, 'menu_toggleAction', 'toggle', 'child_of_menu', '/admin/menu', 0, NULL),
(2947, 575, 'menu_untoggleAction', 'untoggle', 'child_of_menu', '/admin/menu', 0, NULL),
(2948, 575, 'menu_settingsAction', 'settings', 'child_of_menu', '/admin/menu/settings', 0, 1),
(2949, 575, 'menu_quickSaveAction', 'quickSave', 'child_of_menu', '/admin/menu', 0, NULL),
(2950, 575, 'menu_changeRowAction', 'changeRow', 'child_of_menu', '/admin/menu', 0, NULL),
(2951, 575, 'menu_discoveryRefreshAction', 'discoveryRefresh', 'child_of_menu', '/admin/menu', 0, NULL),
(2952, 575, 'menu_chooseBulkAction', 'chooseBulk', 'child_of_menu', '/admin/menu', 0, NULL),
(2989, 580, 'dashboard_indexAction', 'index', 'child_of_dashboard', '/admin/dashboard/index', 0, 1),
(2990, 580, 'dashboard_datetimeAction', 'datetime', 'child_of_dashboard', '/admin/dashboard', 0, NULL),
(2991, 580, 'dashboard_settingsAction', 'settings', 'child_of_dashboard', '/admin/dashboard/settings', 0, NULL),
(2992, 580, 'dashboard_healthAction', 'health', 'child_of_dashboard', '/admin/dashboard', 0, NULL),
(2993, 580, 'dashboard_historyAction', 'history', 'child_of_dashboard', '/admin/dashboard', 0, NULL),
(2994, 580, 'dashboard_changeRowAction', 'changeRow', 'child_of_dashboard', '/admin/dashboard', 0, NULL),
(2995, 580, 'dashboard_discoveryRefreshAction', 'discoveryRefresh', 'child_of_dashboard', '/admin/dashboard', 0, NULL),
(3076, 587, 'tag_indexAction', 'index', 'child_of_tag', '/admin/tag/index', 0, 1),
(3077, 587, 'tag_newAction', 'new', 'child_of_tag', '/admin/tag/new', 0, 1),
(3078, 587, 'tag_editAction', 'edit', 'child_of_tag', '/admin/tag', 0, NULL),
(3079, 587, 'tag_trashAction', 'trash', 'child_of_tag', '/admin/tag', 0, NULL),
(3080, 587, 'tag_untrashAction', 'untrash', 'child_of_tag', '/admin/tag', 0, NULL),
(3081, 587, 'tag_hardDeleteAction', 'hardDelete', 'child_of_tag', '/admin/tag', 0, NULL),
(3082, 587, 'tag_settingsAction', 'settings', 'child_of_tag', '/admin/tag/settings', 0, 1),
(3083, 587, 'tag_testAction', 'test', 'child_of_tag', '/admin/tag', 0, NULL),
(3084, 587, 'tag_changeRowAction', 'changeRow', 'child_of_tag', '/admin/tag', 0, NULL),
(3085, 587, 'tag_discoveryRefreshAction', 'discoveryRefresh', 'child_of_tag', '/admin/tag', 0, NULL),
(3086, 587, 'tag_chooseBulkAction', 'chooseBulk', 'child_of_tag', '/admin/tag', 0, NULL),
(3087, 588, 'category_indexAction', 'index', 'child_of_category', '/admin/category/index', 0, 1),
(3088, 588, 'category_newAction', 'new', 'child_of_category', '/admin/category/new', 0, 1),
(3089, 588, 'category_editAction', 'edit', 'child_of_category', '/admin/category', 0, NULL),
(3090, 588, 'category_copyAction', 'copy', 'child_of_category', '/admin/category', 0, NULL),
(3091, 588, 'category_trashAction', 'trash', 'child_of_category', '/admin/category', 0, NULL),
(3092, 588, 'category_untrashAction', 'untrash', 'child_of_category', '/admin/category', 0, NULL),
(3093, 588, 'category_hardDeleteAction', 'hardDelete', 'child_of_category', '/admin/category', 0, NULL),
(3094, 588, 'category_testAction', 'test', 'child_of_category', '/admin/category', 0, NULL),
(3095, 588, 'category_settingsAction', 'settings', 'child_of_category', '/admin/category/settings', 0, 1),
(3096, 588, 'category_changeRowAction', 'changeRow', 'child_of_category', '/admin/category', 0, NULL),
(3097, 588, 'category_discoveryRefreshAction', 'discoveryRefresh', 'child_of_category', '/admin/category', 0, NULL),
(3098, 588, 'category_chooseBulkAction', 'chooseBulk', 'child_of_category', '/admin/category', 0, NULL),
(3099, 589, 'ticket_indexAction', 'index', 'child_of_ticket', '/admin/ticket/index', 0, 1),
(3100, 589, 'ticket_newAction', 'new', 'child_of_ticket', '/admin/ticket/new', 0, 1),
(3101, 589, 'ticket_editAction', 'edit', 'child_of_ticket', '/admin/ticket', 0, NULL),
(3102, 589, 'ticket_trashAction', 'trash', 'child_of_ticket', '/admin/ticket', 0, NULL),
(3103, 589, 'ticket_untrashAction', 'untrash', 'child_of_ticket', '/admin/ticket', 0, NULL),
(3104, 589, 'ticket_hardDeleteAction', 'hardDelete', 'child_of_ticket', '/admin/ticket', 0, NULL),
(3105, 589, 'ticket_settingsAction', 'settings', 'child_of_ticket', '/admin/ticket/settings', 0, 1),
(3106, 589, 'ticket_changeRowAction', 'changeRow', 'child_of_ticket', '/admin/ticket', 0, NULL),
(3107, 589, 'ticket_discoveryRefreshAction', 'discoveryRefresh', 'child_of_ticket', '/admin/ticket', 0, NULL),
(3108, 589, 'ticket_chooseBulkAction', 'chooseBulk', 'child_of_ticket', '/admin/ticket', 0, NULL),
(3109, 590, 'history_indexAction', 'index', 'child_of_history', '/admin/history/index', 0, 1),
(3110, 590, 'history_settingsAction', 'settings', 'child_of_history', '/admin/history/settings', 0, 1),
(3111, 590, 'history_changeRowAction', 'changeRow', 'child_of_history', '/admin/history', 0, NULL),
(3112, 590, 'history_discoveryRefreshAction', 'discoveryRefresh', 'child_of_history', '/admin/history', 0, NULL),
(3113, 591, 'user_indexAction', 'index', 'child_of_user', '/admin/user/index', 0, 1),
(3114, 591, 'user_overviewAction', 'overview', 'child_of_user', '/admin/user', 0, NULL),
(3115, 591, 'user_showAction', 'show', 'child_of_user', '/admin/user', 0, NULL),
(3116, 591, 'user_newAction', 'new', 'child_of_user', '/admin/user/new', 0, 1),
(3117, 591, 'user_editAction', 'edit', 'child_of_user', '/admin/user', 0, NULL),
(3118, 591, 'user_deleteAction', 'delete', 'child_of_user', '/admin/user', 0, NULL),
(3119, 591, 'user_cloneAction', 'clone', 'child_of_user', '/admin/user', 0, NULL),
(3120, 591, 'user_hardDeleteAction', 'hardDelete', 'child_of_user', '/admin/user', 0, NULL),
(3121, 591, 'user_lockAction', 'lock', 'child_of_user', '/admin/user', 0, NULL),
(3122, 591, 'user_unlockAction', 'unlock', 'child_of_user', '/admin/user', 0, NULL),
(3123, 591, 'user_trashAction', 'trash', 'child_of_user', '/admin/user', 0, NULL),
(3124, 591, 'user_untrashAction', 'untrash', 'child_of_user', '/admin/user', 0, NULL),
(3125, 591, 'user_trashRestoreAction', 'trashRestore', 'child_of_user', '/admin/user', 0, NULL),
(3126, 591, 'user_activeAction', 'active', 'child_of_user', '/admin/user', 0, NULL),
(3127, 591, 'user_preferencesAction', 'preferences', 'child_of_user', '/admin/user', 0, NULL),
(3128, 591, 'user_privilegeAction', 'privilege', 'child_of_user', '/admin/user', 0, NULL),
(3129, 591, 'user_privilegeExpirationAction', 'privilegeExpiration', 'child_of_user', '/admin/user', 0, NULL),
(3130, 591, 'user_logAction', 'log', 'child_of_user', '/admin/user/log', 0, 1),
(3131, 591, 'user_notesAction', 'notes', 'child_of_user', '/admin/user', 0, NULL),
(3132, 591, 'user_personalAction', 'personal', 'child_of_user', '/admin/user', 0, NULL),
(3133, 591, 'user_settingsAction', 'settings', 'child_of_user', '/admin/user/settings', 0, 1),
(3134, 591, 'user_changeRowAction', 'changeRow', 'child_of_user', '/admin/user', 0, NULL),
(3135, 591, 'user_discoveryRefreshAction', 'discoveryRefresh', 'child_of_user', '/admin/user', 0, NULL),
(3136, 591, 'user_chooseBulkAction', 'chooseBulk', 'child_of_user', '/admin/user', 0, NULL),
(3137, 592, 'support_documentationAction', 'documentation', 'child_of_support', '/admin/support', 0, NULL),
(3138, 592, 'support_changelogAction', 'changelog', 'child_of_support', '/admin/support', 0, NULL),
(3139, 592, 'support_settingsAction', 'settings', 'child_of_support', '/admin/support/settings', 0, 1),
(3140, 592, 'support_changeRowAction', 'changeRow', 'child_of_support', '/admin/support', 0, NULL),
(3141, 592, 'support_discoveryRefreshAction', 'discoveryRefresh', 'child_of_support', '/admin/support', 0, NULL),
(3142, 593, 'setting_indexAction', 'index', 'child_of_setting', '/admin/setting/index', 0, 1),
(3143, 593, 'setting_generalAction', 'general', 'child_of_setting', '/admin/setting', 0, NULL),
(3144, 593, 'setting_securityAction', 'security', 'child_of_setting', '/admin/setting', 0, NULL),
(3145, 593, 'setting_purgeAction', 'purge', 'child_of_setting', '/admin/setting', 0, NULL),
(3146, 593, 'setting_toolsAction', 'tools', 'child_of_setting', '/admin/setting', 0, NULL),
(3147, 593, 'setting_localisationAction', 'localisation', 'child_of_setting', '/admin/setting', 0, NULL),
(3148, 593, 'setting_brandingAction', 'branding', 'child_of_setting', '/admin/setting', 0, NULL),
(3149, 593, 'setting_extensionAction', 'extension', 'child_of_setting', '/admin/setting', 0, NULL),
(3150, 593, 'setting_applicationAction', 'application', 'child_of_setting', '/admin/setting', 0, NULL),
(3151, 593, 'setting_settingsAction', 'settings', 'child_of_setting', '/admin/setting/settings', 0, 1),
(3152, 593, 'setting_changeRowAction', 'changeRow', 'child_of_setting', '/admin/setting', 0, NULL),
(3153, 593, 'setting_discoveryRefreshAction', 'discoveryRefresh', 'child_of_setting', '/admin/setting', 0, NULL),
(3154, 594, 'system_indexAction', 'index', 'child_of_system', '/admin/system/index', 0, 1),
(3155, 594, 'system_showAction', 'show', 'child_of_system', '/admin/system', 0, NULL),
(3156, 594, 'system_trashAction', 'trash', 'child_of_system', '/admin/system', 0, NULL),
(3157, 594, 'system_settingsAction', 'settings', 'child_of_system', '/admin/system/settings', 0, 1),
(3158, 594, 'system_changeRowAction', 'changeRow', 'child_of_system', '/admin/system', 0, NULL),
(3159, 594, 'system_discoveryRefreshAction', 'discoveryRefresh', 'child_of_system', '/admin/system', 0, NULL),
(3160, 595, 'permission_indexAction', 'index', 'child_of_permission', '/admin/permission/index', 0, 1),
(3161, 595, 'permission_newAction', 'new', 'child_of_permission', '/admin/permission/new', 0, 1),
(3162, 595, 'permission_editAction', 'edit', 'child_of_permission', '/admin/permission', 0, NULL),
(3163, 595, 'permission_trashAction', 'trash', 'child_of_permission', '/admin/permission', 0, NULL),
(3164, 595, 'permission_untrashAction', 'untrash', 'child_of_permission', '/admin/permission', 0, NULL),
(3165, 595, 'permission_hardDeleteAction', 'hardDelete', 'child_of_permission', '/admin/permission', 0, NULL),
(3166, 595, 'permission_settingsAction', 'settings', 'child_of_permission', '/admin/permission/settings', 0, 1),
(3167, 595, 'permission_changeRowAction', 'changeRow', 'child_of_permission', '/admin/permission', 0, NULL),
(3168, 595, 'permission_discoveryRefreshAction', 'discoveryRefresh', 'child_of_permission', '/admin/permission', 0, NULL),
(3169, 595, 'permission_chooseBulkAction', 'chooseBulk', 'child_of_permission', '/admin/permission', 0, NULL),
(3170, 596, 'role_indexAction', 'index', 'child_of_role', '/admin/role/index', 0, 1),
(3171, 596, 'role_newAction', 'new', 'child_of_role', '/admin/role/new', 0, 1),
(3172, 596, 'role_editAction', 'edit', 'child_of_role', '/admin/role', 0, NULL),
(3173, 596, 'role_trashAction', 'trash', 'child_of_role', '/admin/role', 0, NULL),
(3174, 596, 'role_untrashAction', 'untrash', 'child_of_role', '/admin/role', 0, NULL),
(3175, 596, 'role_hardDeleteAction', 'hardDelete', 'child_of_role', '/admin/role', 0, NULL),
(3176, 596, 'role_assignedAction', 'assigned', 'child_of_role', '/admin/role', 0, NULL),
(3177, 596, 'role_logAction', 'log', 'child_of_role', '/admin/role/log', 0, 1),
(3178, 596, 'role_unassignPermissionAction', 'unassignPermission', 'child_of_role', '/admin/role', 0, NULL),
(3179, 596, 'role_settingsAction', 'settings', 'child_of_role', '/admin/role/settings', 0, 1),
(3180, 596, 'role_changeRowAction', 'changeRow', 'child_of_role', '/admin/role', 0, NULL),
(3181, 596, 'role_discoveryRefreshAction', 'discoveryRefresh', 'child_of_role', '/admin/role', 0, NULL),
(3182, 596, 'role_chooseBulkAction', 'chooseBulk', 'child_of_role', '/admin/role', 0, NULL),
(3183, 597, 'error_indexAction', 'index', 'child_of_error', '//error/index', 0, 1),
(3184, 600, 'group_indexAction', 'index', 'child_of_group', '/admin/group/index', 0, 1),
(3185, 600, 'group_newAction', 'new', 'child_of_group', '/admin/group/new', 0, 1),
(3186, 600, 'group_editAction', 'edit', 'child_of_group', '/admin/group', 0, NULL),
(3187, 600, 'group_trashAction', 'trash', 'child_of_group', '/admin/group', 0, NULL),
(3188, 600, 'group_untrashAction', 'untrash', 'child_of_group', '/admin/group', 0, NULL),
(3189, 600, 'group_hardDeleteAction', 'hardDelete', 'child_of_group', '/admin/group', 0, NULL),
(3190, 600, 'group_deleteAction', 'delete', 'child_of_group', '/admin/group', 0, NULL),
(3191, 600, 'group_assignedAction', 'assigned', 'child_of_group', '/admin/group', 0, NULL),
(3192, 600, 'group_settingsAction', 'settings', 'child_of_group', '/admin/group/settings', 0, 1),
(3193, 600, 'group_changeRowAction', 'changeRow', 'child_of_group', '/admin/group', 0, NULL),
(3194, 600, 'group_discoveryRefreshAction', 'discoveryRefresh', 'child_of_group', '/admin/group', 0, NULL),
(3195, 600, 'group_importAction', 'import', 'child_of_group', '/admin/group', 0, NULL),
(3196, 600, 'group_exportAction', 'export', 'child_of_group', '/admin/group', 0, NULL),
(3197, 600, 'group_chooseBulkAction', 'chooseBulk', 'child_of_group', '/admin/group', 0, NULL),
(3204, 602, 'leave_indexAction', 'index', 'child_of_leave', '/admin/leave/index', 0, 1),
(3205, 602, 'leave_newAction', 'new', 'child_of_leave', '/admin/leave/new', 0, 1),
(3206, 602, 'leave_settingsAction', 'settings', 'child_of_leave', '/admin/leave/settings', 0, 1),
(3207, 602, 'leave_changeRowAction', 'changeRow', 'child_of_leave', '/admin/leave', 0, NULL),
(3208, 602, 'leave_discoveryRefreshAction', 'discoveryRefresh', 'child_of_leave', '/admin/leave', 0, NULL),
(3209, 602, 'leave_importAction', 'import', 'child_of_leave', '/admin/leave', 0, NULL),
(3210, 602, 'leave_exportAction', 'export', 'child_of_leave', '/admin/leave', 0, NULL),
(3211, 602, 'leave_chooseBulkAction', 'chooseBulk', 'child_of_leave', '/admin/leave', 0, NULL),
(3212, 603, 'holiday_indexAction', 'index', 'child_of_holiday', '/admin/holiday/index', 0, 1),
(3213, 603, 'holiday_newAction', 'new', 'child_of_holiday', '/admin/holiday/new', 0, 1),
(3214, 603, 'holiday_editAction', 'edit', 'child_of_holiday', '/admin/holiday', 0, NULL),
(3215, 603, 'holiday_settingsAction', 'settings', 'child_of_holiday', '/admin/holiday/settings', 0, 1),
(3216, 603, 'holiday_changeRowAction', 'changeRow', 'child_of_holiday', '/admin/holiday', 0, NULL),
(3217, 603, 'holiday_discoveryRefreshAction', 'discoveryRefresh', 'child_of_holiday', '/admin/holiday', 0, NULL),
(3218, 603, 'holiday_importAction', 'import', 'child_of_holiday', '/admin/holiday', 0, NULL),
(3219, 603, 'holiday_exportAction', 'export', 'child_of_holiday', '/admin/holiday', 0, NULL),
(3220, 603, 'holiday_chooseBulkAction', 'chooseBulk', 'child_of_holiday', '/admin/holiday', 0, NULL),
(3221, 604, 'costCenter_indexAction', 'index', 'child_of_costCenter', '/admin/costcenter/index', 0, 1),
(3222, 604, 'costCenter_newAction', 'new', 'child_of_costCenter', '/admin/costcenter/new', 0, 1),
(3223, 604, 'costCenter_editAction', 'edit', 'child_of_costCenter', '/admin/costcenter', 0, NULL),
(3224, 604, 'costCenter_trashAction', 'trash', 'child_of_costCenter', '/admin/costcenter', 0, NULL),
(3225, 604, 'costCenter_untrashAction', 'untrash', 'child_of_costCenter', '/admin/costcenter', 0, NULL),
(3226, 604, 'costCenter_hardDeleteAction', 'hardDelete', 'child_of_costCenter', '/admin/costcenter', 0, NULL),
(3227, 604, 'costCenter_activeAction', 'active', 'child_of_costCenter', '/admin/costcenter', 0, NULL),
(3228, 604, 'costCenter_deactiveAction', 'deactive', 'child_of_costCenter', '/admin/costcenter', 0, NULL),
(3229, 604, 'costCenter_settingsAction', 'settings', 'child_of_costCenter', '/admin/costcenter/settings', 0, 1),
(3230, 604, 'costCenter_changeRowAction', 'changeRow', 'child_of_costCenter', '/admin/costcenter', 0, NULL),
(3231, 604, 'costCenter_discoveryRefreshAction', 'discoveryRefresh', 'child_of_costCenter', '/admin/costcenter', 0, NULL),
(3232, 604, 'costCenter_importAction', 'import', 'child_of_costCenter', '/admin/costcenter', 0, NULL),
(3233, 604, 'costCenter_exportAction', 'export', 'child_of_costCenter', '/admin/costcenter', 0, NULL),
(3234, 604, 'costCenter_chooseBulkAction', 'chooseBulk', 'child_of_costCenter', '/admin/costcenter', 0, NULL),
(3235, 605, 'project_indexAction', 'index', 'child_of_project', '/admin/project/index', 0, 1),
(3236, 605, 'project_newAction', 'new', 'child_of_project', '/admin/project/new', 0, 1),
(3237, 605, 'project_editAction', 'edit', 'child_of_project', '/admin/project', 0, NULL),
(3238, 605, 'project_trashAction', 'trash', 'child_of_project', '/admin/project', 0, NULL),
(3239, 605, 'project_untrashAction', 'untrash', 'child_of_project', '/admin/project', 0, NULL),
(3240, 605, 'project_hardDeleteAction', 'hardDelete', 'child_of_project', '/admin/project', 0, NULL),
(3241, 605, 'project_activeAction', 'active', 'child_of_project', '/admin/project', 0, NULL),
(3242, 605, 'project_deactiveAction', 'deactive', 'child_of_project', '/admin/project', 0, NULL),
(3243, 605, 'project_settingsAction', 'settings', 'child_of_project', '/admin/project/settings', 0, 1),
(3244, 605, 'project_changeRowAction', 'changeRow', 'child_of_project', '/admin/project', 0, NULL),
(3245, 605, 'project_discoveryRefreshAction', 'discoveryRefresh', 'child_of_project', '/admin/project', 0, NULL),
(3246, 605, 'project_importAction', 'import', 'child_of_project', '/admin/project', 0, NULL),
(3247, 605, 'project_exportAction', 'export', 'child_of_project', '/admin/project', 0, NULL),
(3248, 605, 'project_chooseBulkAction', 'chooseBulk', 'child_of_project', '/admin/project', 0, NULL),
(3249, 606, 'branch_indexAction', 'index', 'child_of_branch', '/admin/branch/index', 0, 1),
(3250, 606, 'branch_newAction', 'new', 'child_of_branch', '/admin/branch/new', 0, 1),
(3251, 606, 'branch_editAction', 'edit', 'child_of_branch', '/admin/branch', 0, NULL),
(3252, 606, 'branch_trashAction', 'trash', 'child_of_branch', '/admin/branch', 0, NULL),
(3253, 606, 'branch_untrashAction', 'untrash', 'child_of_branch', '/admin/branch', 0, NULL),
(3254, 606, 'branch_hardDeleteAction', 'hardDelete', 'child_of_branch', '/admin/branch', 0, NULL),
(3255, 606, 'branch_activeAction', 'active', 'child_of_branch', '/admin/branch', 0, NULL),
(3256, 606, 'branch_deactiveAction', 'deactive', 'child_of_branch', '/admin/branch', 0, NULL),
(3257, 606, 'branch_settingsAction', 'settings', 'child_of_branch', '/admin/branch/settings', 0, 1),
(3258, 606, 'branch_changeRowAction', 'changeRow', 'child_of_branch', '/admin/branch', 0, NULL),
(3259, 606, 'branch_discoveryRefreshAction', 'discoveryRefresh', 'child_of_branch', '/admin/branch', 0, NULL),
(3260, 606, 'branch_importAction', 'import', 'child_of_branch', '/admin/branch', 0, NULL),
(3261, 606, 'branch_exportAction', 'export', 'child_of_branch', '/admin/branch', 0, NULL),
(3262, 606, 'branch_chooseBulkAction', 'chooseBulk', 'child_of_branch', '/admin/branch', 0, NULL),
(3263, 607, 'timesheet_indexAction', 'index', 'child_of_timesheet', '/admin/timesheet/index', 0, 1),
(3264, 607, 'timesheet_newAction', 'new', 'child_of_timesheet', '/admin/timesheet/new', 0, 1),
(3265, 607, 'timesheet_editAction', 'edit', 'child_of_timesheet', '/admin/timesheet', 0, NULL),
(3266, 607, 'timesheet_trashAction', 'trash', 'child_of_timesheet', '/admin/timesheet', 0, NULL),
(3267, 607, 'timesheet_untrashAction', 'untrash', 'child_of_timesheet', '/admin/timesheet', 0, NULL),
(3268, 607, 'timesheet_hardDeleteAction', 'hardDelete', 'child_of_timesheet', '/admin/timesheet', 0, NULL),
(3269, 607, 'timesheet_activeAction', 'active', 'child_of_timesheet', '/admin/timesheet', 0, NULL),
(3270, 607, 'timesheet_deactiveAction', 'deactive', 'child_of_timesheet', '/admin/timesheet', 0, NULL),
(3271, 607, 'timesheet_changeRowAction', 'changeRow', 'child_of_timesheet', '/admin/timesheet', 0, NULL),
(3272, 607, 'timesheet_discoveryRefreshAction', 'discoveryRefresh', 'child_of_timesheet', '/admin/timesheet', 0, NULL),
(3273, 607, 'timesheet_importAction', 'import', 'child_of_timesheet', '/admin/timesheet', 0, NULL),
(3274, 607, 'timesheet_exportAction', 'export', 'child_of_timesheet', '/admin/timesheet', 0, NULL),
(3275, 607, 'timesheet_settingsAction', 'settings', 'child_of_timesheet', '/admin/timesheet/settings', 0, 1),
(3276, 607, 'timesheet_chooseBulkAction', 'chooseBulk', 'child_of_timesheet', '/admin/timesheet', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `body` longtext NOT NULL,
  `attachment` varchar(256) DEFAULT NULL,
  `subject` text NOT NULL,
  `status` varchar(24) NOT NULL,
  `is_starred` int(1) NOT NULL DEFAULT 0,
  `receiver` int(10) UNSIGNED NOT NULL,
  `is_marked` int(1) NOT NULL DEFAULT 0,
  `deleted_at` int(1) DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `body`, `attachment`, `subject`, `status`, `is_starred`, `receiver`, `is_marked`, `deleted_at`, `created_at`, `modified_at`) VALUES
(23, 1270, 'Hello,\r\n\r\nWe have great news! Your subscription includes DataSpell, a new tool for data scientists.\r\n\r\nDataSpell is an IDE designed specifically for those involved in exploratory data analysis and prototyping ML models. DataSpell combines the interactivity of Jupyter notebooks with the intelligent Python and R coding assistance of PyCharm in one convenient environment.', NULL, 'Your subscription includes a new tool – DataSpell', 'sent', 0, 1270, 0, 0, '2022-01-23 23:20:13', '2022-05-09 20:56:01'),
(24, 1270, '', '', 'Et et nihil ex et se', 'sent', 0, 0, 0, 0, '2022-02-12 22:58:14', '2022-05-09 20:55:29'),
(25, 1270, '', '', '', 'sent', 0, 0, 0, 0, '2022-02-15 00:38:00', '2022-05-09 20:55:49');

-- --------------------------------------------------------

--
-- Table structure for table `metadata`
--

CREATE TABLE `metadata` (
  `post_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `keywords` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `robots` varchar(24) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'index'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration_name` varchar(65) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration_name`, `created_at`, `modified_at`) VALUES
(1, 'm239356a02513f99345bcbbd908b4e1bfa7e180f160d9115e9b5e5f66b3e1e8f0', '2021-04-15 21:48:16', NULL),
(2, 'm44d7e693abb227b9a2186af0d62df324ca3c8d89ce0fa703348dc9f22fa1b005', '2021-04-15 21:48:16', NULL),
(3, 'm60117464348516685df2faf89b2b6d70663d64511f76395569444a1bcb4babb9', '2021-04-15 21:48:16', NULL),
(4, 'm9585f98d0b086be9e0dcd25e0c4db4d61496ab4c7e2493f9e0e72ab2eb9c17d8', '2021-04-15 21:48:16', NULL),
(5, 'ma15612338cd498432a5aa7ff98c9c309632f1e8f73a2e8a8fcc16ee122d1ec3a', '2021-04-15 21:48:16', NULL),
(6, 'me5586ad6449afbb0721c52ea3935931eca396eb214d8632ce9cbf96ea508ca42', '2021-04-15 21:48:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `notify_title` varchar(190) NOT NULL,
  `notify_type` varchar(30) NOT NULL,
  `notify_status` varchar(10) NOT NULL DEFAULT 'unread',
  `notifier` varchar(190) NOT NULL,
  `notify_description` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_byid` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(190) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(190) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(190) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `author` int(10) UNSIGNED DEFAULT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `template` int(10) UNSIGNED DEFAULT NULL,
  `status` varchar(24) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `visible` varchar(24) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `extras` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `parent` int(10) DEFAULT NULL,
  `page_order` int(10) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `permission_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permission_description` text COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `resource_group` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `created_byid` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `permission_name`, `permission_description`, `resource_group`, `created_at`, `modified_at`, `created_byid`, `deleted_at`) VALUES
(72, 'can_add_user', 'allows to create new user', NULL, '2021-07-03 16:25:09', '2022-05-09 20:37:48', 1, 0),
(73, 'can_edit_user', 'Allows to edit a user', NULL, '2021-07-03 16:25:43', '2021-07-16 22:57:08', 1, 0),
(74, 'can_delete_user', 'Allows to delete a user', NULL, '2021-07-03 16:25:59', '2021-07-16 22:57:00', 1, 0),
(76, 'have_admin_access', 'Basic permission for accessing the admin panel', NULL, '2021-07-07 14:20:26', '2021-07-16 23:01:40', 1, 0),
(77, 'basic_access', 'Permission which allow basic access for all subscriber and user', NULL, '2021-07-07 14:31:15', '2022-05-09 20:37:48', 1270, 0),
(78, 'can_log_user', 'Permission which allows a user to manage the user logs', NULL, '2021-07-07 14:37:51', '2021-07-16 22:58:46', 1, 0),
(79, 'can_view_statistics_user', 'Permissions which allows viewing the user statistics', NULL, '2021-07-07 14:41:41', '2021-07-17 08:13:31', 1, 0),
(81, 'can_show_user', 'Permission for showing a single use row', NULL, '2021-07-09 00:46:41', '2021-07-16 22:57:27', 1, 0),
(82, 'can_view_user', 'access to use index', NULL, '2021-07-14 10:25:17', '2021-07-16 22:10:20', 1, 0),
(84, 'can_edit_privilege_user', 'Allows to edit the user privilege', NULL, '2021-07-16 23:05:19', '2021-07-17 08:12:29', 1, 0),
(85, 'can_add_permission', 'Allow adding of new permissions', 'permission', '2021-07-17 07:58:56', '2022-05-10 20:50:53', 1270, 0),
(86, 'can_view_permission', 'Allow to view the table of permission', NULL, '2021-07-17 07:59:20', NULL, 1, 0),
(87, 'can_edit_permission', 'Allows to edit a permission', NULL, '2021-07-17 07:59:36', NULL, 1, 0),
(88, 'can_delete_permission', 'Allows to delete a permission', NULL, '2021-07-17 07:59:55', '2021-07-25 00:14:26', 1, 0),
(89, 'can_log_permisson', 'Allows to view and modify the permission log', NULL, '2021-07-17 08:00:31', NULL, 1, 0),
(91, 'can_view_role', 'Allows to view the roles table', NULL, '2021-07-17 08:01:29', NULL, 1, 0),
(92, 'can_edit_role', 'Allows to edit a role', NULL, '2021-07-17 08:01:42', NULL, 1, 0),
(93, 'can_delete_role', 'Allows to delete a role', NULL, '2021-07-17 08:01:58', NULL, 1, 0),
(94, 'can_assign_role', 'Allows to assign a group of permissions to a role', NULL, '2021-07-17 08:02:32', '2022-05-09 20:37:48', 1, 0),
(95, 'can_remove_assignment_role', 'Allows to remove permission from a role', NULL, '2021-07-17 08:03:38', '2021-07-17 08:13:02', 1, 0),
(96, 'can_log_role', 'Allows to view and modify role log', NULL, '2021-07-17 08:08:48', NULL, 1, 0),
(97, 'can_set_privilege_expiration_user', 'Allows to set an expiration for a privilege', NULL, '2021-07-17 08:14:03', NULL, 1, 0),
(98, 'can_view_dashboard', 'Allows to view the admin dashboard', NULL, '2021-07-17 08:20:11', NULL, 1, 0),
(99, 'can_edit_menu', 'Allows to edit controller menu', NULL, '2021-07-18 14:10:56', NULL, 1, 0),
(100, 'can_view_menu', 'Allows to view menu table', NULL, '2021-07-18 21:21:31', NULL, 1, 0),
(103, 'can_trash_user', 'Allows to view and delete user trash', NULL, '2021-07-18 23:28:51', NULL, 1, 0),
(104, 'can_edit_preferences_user', 'Allows to edit user preferences', NULL, '2021-07-19 11:08:37', '2021-07-19 11:10:06', 1, 0),
(105, 'can_edit_general_setting', 'Allows to edit the system general settings', NULL, '2021-07-20 00:44:50', NULL, 1, 0),
(106, 'can_edit_purge_setting', 'Allows editing purge setting', NULL, '2021-07-20 14:17:08', NULL, 1, 0),
(107, 'can_edit_security_setting', 'Allows editing security setting', NULL, '2021-07-20 14:18:02', NULL, 1, 0),
(108, 'can_edit_tool_setting', 'Allows editing tool setting', NULL, '2021-07-20 14:18:25', NULL, 1, 0),
(109, 'can_add_menu', 'Allows to add new controller menu updated!', NULL, '2021-07-21 22:54:02', '2022-05-10 20:50:53', 1, 0),
(110, 'can_edit_files', 'Allows to edit core yaml files', NULL, '2021-07-22 12:09:35', NULL, 1, 0),
(111, 'can_view_notification', 'Allows to view the notification table', NULL, '2021-07-22 18:19:37', '2021-07-22 18:21:44', 1, 0),
(114, 'can_edit_theme', '', NULL, '2021-07-25 00:12:44', NULL, 1, 0),
(117, 'can_hard_delete_user', 'Allows to permanently delete a user account from the database', NULL, '2021-07-27 15:09:51', NULL, 1, 0),
(118, 'can_restore_trash_user', 'Allows user to manage user trash', NULL, '2021-07-28 20:50:00', NULL, 1, 0),
(119, 'can_lock_user', 'Allows to lock and unlock a user account', NULL, '2021-07-29 08:07:52', NULL, 1, 0),
(120, 'can_unlock_user', 'Allows to unlock locked user account', NULL, '2021-07-29 18:55:29', NULL, 1, 0),
(121, 'can_change_status_user', 'Allows to change an account status', NULL, '2021-07-30 08:42:56', NULL, 1, 0),
(124, 'can_add_role', 'Allow to add roles to system', NULL, '2021-08-09 10:18:56', '2022-05-09 20:37:48', 1, 0),
(128, 'can_edit_theme_delete', 'can edit theme delete', NULL, '2021-09-10 10:42:03', NULL, 1, 0),
(131, 'can_bulk_delete_user', 'Can  carry out bulk deletion of users', NULL, '2021-09-21 19:25:10', NULL, 1270, 0),
(132, 'can_bulk_clone_user', 'Can bulk clone user accounts', NULL, '2021-09-22 11:16:10', NULL, 1270, 0),
(133, 'can_clone_user', 'Can clone a single user account', NULL, '2021-09-22 22:13:12', NULL, 1270, 0),
(135, 'can_edit_own_account', 'Allow backend user to edit their own account', NULL, '2022-01-04 16:28:14', NULL, 1270, 0),
(138, 'can_view_message', 'Permission for viewing messages', NULL, '2022-01-16 19:52:47', NULL, 1270, 0),
(140, 'can_bulk_delete_permission', 'Can bulk delete permission', NULL, '2022-01-18 16:44:12', NULL, 1270, 0),
(142, 'can_bulk_delete_role', '', NULL, '2022-01-18 21:11:59', NULL, 1270, 0),
(145, 'can_view_group', 'Can view the permission group table', NULL, '2022-01-23 00:17:51', NULL, 1270, 0),
(146, 'can_add_group', '', 'category', '2022-01-23 00:33:57', '2022-05-21 14:27:25', 1270, 0),
(147, 'can_edit_group', 'Allows to edit and manage all user account', NULL, '2022-01-23 00:43:14', NULL, 1270, 0),
(148, 'can_delete_group', '', NULL, '2022-01-23 00:45:58', NULL, 1270, 0),
(149, 'can_log_group', '', NULL, '2022-01-23 00:48:02', NULL, 1270, 0),
(150, 'can_assign_group', '', NULL, '2022-01-23 11:49:19', '2022-05-09 20:37:48', 1270, 0),
(151, 'can_view_ticket', '', NULL, '2022-01-23 19:15:33', NULL, 1270, 0),
(152, 'can_add_ticket', '', NULL, '2022-01-23 19:15:46', '2022-05-09 20:37:48', 1270, 0),
(153, 'can_new_ticket', '', NULL, '2022-01-23 19:15:54', NULL, 1270, 0),
(154, 'can_edit_ticket', '', NULL, '2022-01-23 19:16:06', NULL, 1270, 0),
(155, 'can_delete_ticket', '', NULL, '2022-01-23 19:16:22', NULL, 1270, 0),
(156, 'can_delete_menu', 'can delete menu', NULL, '2022-01-27 21:39:10', NULL, 1270, 0),
(157, 'can_view_post', 'Can View post articles', NULL, '2022-02-02 00:35:07', NULL, 1270, 0),
(158, 'can_add_post', 'Can add a post article', NULL, '2022-02-02 00:35:21', '2022-05-09 20:37:48', 1270, 0),
(159, 'can_edit_post', 'Can edit a post article', NULL, '2022-02-02 00:35:33', NULL, 1270, 0),
(160, 'can_delete_post', 'Can delete a post article', NULL, '2022-02-02 00:35:58', NULL, 1270, 0),
(161, 'can_trash_post', 'Can put post in trash', NULL, '2022-02-02 00:36:14', NULL, 1270, 0),
(162, 'can_add_category', 'Allow to add new categories', 'category', '2022-02-02 15:42:28', '2022-05-10 20:50:53', 1270, 0),
(163, 'can_edit_category', 'Can edit a category', NULL, '2022-02-02 15:42:47', NULL, 1270, 0),
(164, 'can_delete_category', 'can delete category', NULL, '2022-02-02 15:43:07', NULL, 1270, 0),
(165, 'can_view_category', 'Can view the category listings', NULL, '2022-02-02 15:43:27', NULL, 1270, 0),
(166, 'can_trash_category', 'Allow to trash the queried category', NULL, '2022-02-03 01:26:24', NULL, 1270, 0),
(167, 'can_bulk_restore_category', '', NULL, '2022-02-04 00:41:09', NULL, 1270, 0),
(168, 'can_bulk_delete_category', '', NULL, '2022-02-04 00:58:51', NULL, 1270, 0),
(169, 'can_untrash_category', '', NULL, '2022-02-04 02:30:30', NULL, 1270, 0),
(170, 'can_view_tag', '', NULL, '2022-02-04 03:59:06', NULL, 1270, 0),
(171, 'can_edit_tag', '', NULL, '2022-02-04 04:00:04', NULL, 1270, 0),
(172, 'can_add_tag', '', NULL, '2022-02-04 04:00:26', '2022-05-09 20:37:48', 1270, 0),
(173, 'can_delete_tag', '', NULL, '2022-02-04 20:53:12', NULL, 1270, 0),
(174, 'can_bulk_delete_tag', '', NULL, '2022-02-04 20:53:26', NULL, 1270, 0),
(176, 'can_untrash_tag', '', NULL, '2022-02-04 20:54:54', NULL, 1270, 0),
(177, 'can_trash_tag', '', NULL, '2022-02-04 20:55:25', NULL, 1270, 0),
(179, 'can_trash_permission', '', NULL, '2022-02-05 10:29:01', NULL, 1270, 0),
(182, 'can_trash_role', '', NULL, '2022-02-05 10:38:33', NULL, 1270, 0),
(183, 'can_untrash_permission', '', NULL, '2022-02-05 12:07:50', NULL, 1270, 0),
(185, 'can_bulk_restore_permission', '', NULL, '2022-02-05 12:27:54', NULL, 1270, 0),
(188, 'can_untrash_role', '', NULL, '2022-02-05 14:49:26', NULL, 1270, 0),
(189, 'can_bulk_untrash_role', '', NULL, '2022-02-05 14:49:37', NULL, 1270, 0),
(190, 'can_bulk_trash_role', '', NULL, '2022-02-05 14:53:23', NULL, 1270, 0),
(191, 'can_bulk_restore_role', '', NULL, '2022-02-05 14:54:19', NULL, 1270, 0),
(192, 'can_bulk_trash_category', '', NULL, '2022-02-05 15:06:59', NULL, 1270, 0),
(193, 'can_bulk_trash_tag', '', NULL, '2022-02-05 15:07:13', NULL, 1270, 0),
(194, 'can_bulk_untrash_category', '', NULL, '2022-02-05 15:07:30', NULL, 1270, 0),
(195, 'can_bulk_untrash_tag', '', NULL, '2022-02-05 15:07:42', NULL, 1270, 0),
(196, 'can_bulk_clone_category', '', NULL, '2022-02-05 15:07:58', '2022-05-09 20:37:48', 1270, 0),
(197, 'can_bulk_restore_tag', '', NULL, '2022-02-05 15:17:22', NULL, 1270, 0),
(198, 'can_trash_ticket', '', NULL, '2022-02-05 19:23:04', NULL, 1270, 0),
(199, 'can_bulk_restore_ticket', '', NULL, '2022-02-05 19:23:27', NULL, 1270, 0),
(200, 'can_bulk_untrash_ticket', '', NULL, '2022-02-05 19:23:43', NULL, 1270, 0),
(201, 'can_restore_ticket', '', NULL, '2022-02-05 19:29:49', NULL, 1270, 0),
(202, 'can_untrash_ticket', '', NULL, '2022-02-05 19:30:37', NULL, 1270, 0),
(203, 'can_bulk_trash_ticket', '', NULL, '2022-02-05 19:43:27', NULL, 1270, 0),
(205, 'can_bulk_delete_ticket', '', NULL, '2022-02-05 20:17:33', NULL, 1270, 0),
(206, 'can_bulk_trash_menu', '', NULL, '2022-02-06 10:00:00', NULL, 1270, 0),
(207, 'can_bulk_restore_menu', '', NULL, '2022-02-06 10:00:16', NULL, 1270, 0),
(208, 'can_bulk_delete_menu', '', NULL, '2022-02-06 10:00:29', NULL, 1270, 0),
(209, 'can_bulk_untrash_menu', '', NULL, '2022-02-06 10:00:42', NULL, 1270, 0),
(210, 'can_restore_menu', '', NULL, '2022-02-06 10:00:53', NULL, 1270, 0),
(212, 'can_untrash_menu', '', NULL, '2022-02-06 10:04:24', NULL, 1270, 0),
(213, 'can_bulk_trash_permission', '', NULL, '2022-02-08 20:00:27', NULL, 1270, 0),
(214, 'can_manage_settings_user', '', NULL, '2022-02-09 09:29:47', NULL, 1270, 0),
(215, 'can_manage_settings_permission', '', NULL, '2022-02-10 02:42:47', NULL, 1270, 0),
(216, 'can_manage_settings_tag', '', NULL, '2022-02-10 03:06:17', NULL, 1270, 0),
(218, 'can_manage_settings_category', '', NULL, '2022-02-10 03:07:13', NULL, 1270, 0),
(219, 'can_manage_settings_post', '', NULL, '2022-02-10 03:07:56', NULL, 1270, 0),
(220, 'can_manage_settings_group', '', NULL, '2022-02-10 03:10:11', NULL, 1270, 0),
(221, 'can_manage_settings_dashboard', '', NULL, '2022-02-10 03:33:55', NULL, 1270, 0),
(223, 'can_manage_settings_role', '', NULL, '2022-02-10 05:39:34', NULL, 1270, 0),
(225, 'can_hardDelete_tag', 'This is a default description for can_hardDelete_tag', 'tag', '2022-02-16 01:04:03', NULL, 1, 0),
(226, 'can_settings_tag', 'This is a default description for can_settings_tag', 'tag', '2022-02-16 01:05:35', NULL, 1, 0),
(228, 'can_hardDelete_ticket', 'This is a default description for can_hardDelete_ticket', 'ticket', '2022-02-16 01:08:48', NULL, 1, 0),
(229, 'can_settings_ticket', 'This is a default description for can_settings_ticket', 'ticket', '2022-02-16 01:08:49', NULL, 1, 0),
(232, 'can_settings_permission', 'This is a default description for can_settings_permission', 'permission', '2022-02-16 01:08:55', NULL, 1, 0),
(237, 'can_before_permission', 'This is a default description for can_before_permission', 'permission', '2022-02-16 01:09:25', '2022-05-09 20:37:48', 1, 0),
(238, 'can_after_permission', 'This is a default description for can_after_permission', 'permission', '2022-02-16 01:09:25', '2022-05-09 20:37:48', 1, 0),
(242, 'can_hardDelete_category', 'This is a default description for can_hardDelete_category', 'category', '2022-02-16 01:09:55', NULL, 1, 0),
(243, 'can_settings_category', 'This is a default description for can_settings_category', 'category', '2022-02-16 01:09:55', NULL, 1, 0),
(247, 'can_view_security', 'This is a default description for can_view_security', 'security', '2022-02-16 18:18:46', NULL, 1, 0),
(248, 'can_session_security', 'This is a default description for can_session_security', 'security', '2022-02-16 18:18:47', NULL, 1, 0),
(251, 'can_before_security', 'This is a default description for can_before_security', 'security', '2022-02-16 18:31:05', '2022-05-09 20:37:48', 1, 0),
(252, 'can_after_security', 'This is a default description for can_after_security', 'security', '2022-02-16 18:31:06', '2022-05-09 20:37:48', 1, 0),
(255, 'can_view_home', 'This is a default description for can_view_home', 'home', '2022-02-16 20:58:54', NULL, 1, 0),
(260, 'can_before_home', 'This is a default description for can_before_home', 'home', '2022-02-16 20:59:44', '2022-05-09 20:37:48', 1, 0),
(261, 'can_after_home', 'This is a default description for can_after_home', 'home', '2022-02-16 20:59:45', '2022-05-09 20:37:48', 1, 0),
(265, 'can_before_category', 'This is a default description for can_before_category', 'category', '2022-02-16 21:01:33', '2022-05-09 20:37:48', 1, 0),
(266, 'can_after_category', 'This is a default description for can_after_category', 'category', '2022-02-16 21:01:33', '2022-05-09 20:37:48', 1, 0),
(271, 'can_hardDelete_menu', 'This is a default description for can_hardDelete_menu', 'menu', '2022-02-16 21:02:14', NULL, 1, 0),
(272, 'can_removeItem_menu', 'This is a default description for can_removeItem_menu', 'menu', '2022-02-16 21:02:16', NULL, 1, 0),
(273, 'can_settings_menu', 'This is a default description for can_settings_menu', 'menu', '2022-02-16 21:09:04', NULL, 1, 0),
(274, 'can_quickSave_menu', 'This is a default description for can_quickSave_menu', 'menu', '2022-02-16 21:09:05', NULL, 1, 0),
(279, 'can_before_menu', 'This is a default description for can_before_menu', 'menu', '2022-02-16 21:09:46', '2022-05-09 20:37:48', 1, 0),
(280, 'can_after_menu', 'This is a default description for can_after_menu', 'menu', '2022-02-16 21:09:47', '2022-05-09 20:37:48', 1, 0),
(285, 'can_order_menu', 'This is a default description for can_order_menu', 'menu', '2022-02-16 21:21:53', NULL, 1, 0),
(287, 'can_manage_settings_menu', '', 'menu', '2022-02-17 10:25:26', NULL, 1270, 0),
(288, 'can_view_history', '', NULL, '2022-04-18 13:02:53', NULL, 1270, 0),
(289, 'can_note_user', '', NULL, '2022-04-24 23:24:53', NULL, 1270, 0),
(290, 'can_untrash_user', '', NULL, '2022-05-01 00:45:08', NULL, 1270, 0),
(292, 'can_bulk_clone_tag', '', NULL, '2022-05-02 18:11:00', NULL, 1270, 0),
(293, 'can_bulk_clone_group', '', NULL, '2022-05-02 19:26:03', '2022-05-09 20:37:48', 1270, 0),
(294, 'can_bulk_clone_permission', '', NULL, '2022-05-02 19:30:48', NULL, 1270, 0),
(295, 'can_bulk_clone_role', '', NULL, '2022-05-02 19:30:57', NULL, 1270, 0),
(298, 'can_bulk_clone_ticket', '', NULL, '2022-05-02 19:33:17', NULL, 1270, 0),
(299, 'can_bulk_clone_message', '', NULL, '2022-05-02 19:33:26', NULL, 1270, 0),
(300, 'can_bulk_clone_menu', '', NULL, '2022-05-02 19:33:40', '2022-05-09 20:37:48', 1270, 0),
(301, 'can_bulk_restore_user', '', NULL, '2022-05-08 11:48:43', NULL, 1270, 0),
(302, 'can_bulk_trash_user', '', NULL, '2022-05-08 12:02:46', NULL, 1270, 0),
(303, 'can_bulk_trash_group', '', NULL, '2022-05-09 20:57:24', NULL, 1270, 0),
(305, 'can_bulk_delete_group', '', NULL, '2022-05-09 20:57:45', NULL, 1270, 0),
(306, 'can_bulk_restore_group', '', NULL, '2022-05-09 20:57:55', NULL, 1270, 0),
(310, 'can_view_holiday', '', NULL, '2022-06-03 23:18:29', NULL, 1270, 0),
(311, 'can_add_holiday', '', NULL, '2022-06-03 23:18:36', NULL, 1270, 0),
(312, 'can_edit_holiday', '', NULL, '2022-06-03 23:18:46', NULL, 1270, 0),
(313, 'can_hardDelete_holiday', '', NULL, '2022-06-03 23:18:59', NULL, 1270, 0),
(315, 'can_trash_holiday', '', NULL, '2022-06-04 13:07:52', NULL, 1270, 0),
(316, 'can_untrash_holiday', '', NULL, '2022-06-04 13:07:59', NULL, 1270, 0),
(317, 'can_delete_holiday', '', NULL, '2022-06-04 13:08:05', NULL, 1270, 0),
(318, 'can_bulk_trash_holiday', '', NULL, '2022-06-04 13:14:37', NULL, 1270, 0),
(319, 'can_bulk_clone_holiday', '', NULL, '2022-06-04 13:14:46', NULL, 1270, 0),
(320, 'can_bulk_untrash_holiday', '', NULL, '2022-06-04 13:15:02', NULL, 1270, 0),
(321, 'can_bulk_delete_holiday', '', NULL, '2022-06-04 13:15:10', NULL, 1270, 0),
(322, 'can_bulk_restore_holiday', '', NULL, '2022-06-04 13:15:19', NULL, 1270, 0),
(323, 'can_view_settings', '', NULL, '2022-06-04 13:22:37', NULL, 1270, 0),
(324, 'can_manage_settings_holiday', '', NULL, '2022-06-04 13:23:17', NULL, 1270, 0),
(325, 'can_view_costCenter', '', NULL, '2022-06-04 16:31:01', NULL, 1270, 0),
(326, 'can_add_costCenter', '', NULL, '2022-06-04 16:31:48', NULL, 1270, 0),
(328, 'can_edit_costcenter', '', NULL, '2022-06-04 20:39:16', NULL, 1270, 0),
(329, 'can_manage_settings_costcenter', '', NULL, '2022-06-04 20:48:09', NULL, 1270, 0),
(330, 'can_view_project', '', NULL, '2022-06-04 21:17:37', NULL, 1270, 0),
(331, 'can_edit_project', '', NULL, '2022-06-04 21:26:40', NULL, 1270, 0),
(332, 'can_view_branch', '', NULL, '2022-06-04 21:54:14', NULL, 1270, 0),
(333, 'can_ad_branch', '', NULL, '2022-06-04 21:54:21', NULL, 1270, 0),
(334, 'can_add_branch', '', NULL, '2022-06-04 21:54:26', NULL, 1270, 0),
(335, 'can_edit_branch', '', NULL, '2022-06-04 21:54:32', NULL, 1270, 0),
(336, 'can_hardDelete_branch', '', NULL, '2022-06-04 21:54:38', NULL, 1270, 0),
(337, 'can_delete_branch', '', NULL, '2022-06-04 21:54:44', NULL, 1270, 0),
(338, 'can_trash_branch', '', NULL, '2022-06-04 21:54:51', NULL, 1270, 0),
(339, 'can_manage_settings_branch', '', NULL, '2022-06-04 21:55:03', NULL, 1270, 0),
(340, 'can_bulk_trash_branch', '', NULL, '2022-06-04 21:55:16', NULL, 1270, 0),
(341, 'can_bulk_clone_branch', '', NULL, '2022-06-04 21:55:25', NULL, 1270, 0),
(342, 'can_bulk_delete_branch', '', NULL, '2022-06-04 21:55:38', NULL, 1270, 0),
(343, 'can_bulk_restore_branch', '', NULL, '2022-06-04 21:55:50', NULL, 1270, 0),
(344, 'can_bulk_untrash_branch', '', NULL, '2022-06-04 21:56:04', NULL, 1270, 0),
(345, 'can_bulk_trash_costcenter', '', NULL, '2022-06-05 17:14:28', NULL, 1270, 0),
(346, 'can_bulk_restore_costcenter', '', NULL, '2022-06-05 17:14:41', NULL, 1270, 0),
(347, 'can_bulk_trash_leave', '', NULL, '2022-06-05 17:15:54', NULL, 1270, 0),
(348, 'can_bulk_restore_leave', '', NULL, '2022-06-05 17:16:01', NULL, 1270, 0),
(349, 'can_manage_settings_leave', '', NULL, '2022-06-05 17:18:57', NULL, 1270, 0),
(350, 'can_view_leave', '', NULL, '2022-06-05 17:21:18', NULL, 1270, 0),
(351, 'can_add_leave', '', NULL, '2022-06-05 17:21:36', NULL, 1270, 0),
(352, 'can_edit_leave', '', NULL, '2022-06-05 17:21:41', NULL, 1270, 0),
(353, 'can_hardDelete_leave', '', NULL, '2022-06-05 17:21:48', NULL, 1270, 0),
(354, 'can_trash_leave', '', NULL, '2022-06-05 17:21:55', NULL, 1270, 0),
(355, 'can_hardDelete_costcenter', '', NULL, '2022-06-05 17:27:39', NULL, 1270, 0),
(357, 'can_delete_costcenter', '', NULL, '2022-06-05 17:28:59', NULL, 1270, 0),
(358, 'can_view_timesheet', '', NULL, '2022-06-05 19:20:38', NULL, 1270, 0),
(359, 'can_add_timesheet', '', NULL, '2022-06-05 19:20:45', NULL, 1270, 0),
(360, 'can_edit_timesheet', '', NULL, '2022-06-05 19:20:52', NULL, 1270, 0),
(361, 'can_delete_timesheet', '', NULL, '2022-06-05 19:20:58', NULL, 1270, 0),
(362, 'can_bulk_trash_timesheet', '', NULL, '2022-06-05 19:21:06', NULL, 1270, 0),
(363, 'can_update_status_timesheet', '', NULL, '2022-06-05 19:21:24', NULL, 1270, 0);

-- --------------------------------------------------------

--
-- Table structure for table `plugins`
--

CREATE TABLE `plugins` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `uri` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `author` varchar(255) NOT NULL,
  `homepage` varchar(196) NOT NULL,
  `version` varchar(10) NOT NULL,
  `status` enum('activate','deactivate','','') NOT NULL DEFAULT 'deactivate'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `plugins`
--

INSERT INTO `plugins` (`id`, `name`, `uri`, `description`, `author`, `homepage`, `version`, `status`) VALUES
(28, 'HelloDolly', 'www.wordpress.org/plugins/hello-dolly/', 'This is not just a plugin it symbolizes the hope and enthusiasm of an entire', 'Matt Mullenweg', 'www.ma.tt/', '1.0.0', 'activate');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(190) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(190) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author` int(10) UNSIGNED NOT NULL,
  `article` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `summary` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','published','schedule') COLLATE utf8mb4_unicode_ci NOT NULL,
  `visible` enum('private','protected','public') COLLATE utf8mb4_unicode_ci NOT NULL,
  `format` enum('article','chat','quote','image','video','audio','link','gallery','quote') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'article',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `schedule_at` datetime DEFAULT NULL,
  `deleted_at` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_assets`
--

CREATE TABLE `post_assets` (
  `id` int(10) UNSIGNED NOT NULL,
  `post_id` int(10) UNSIGNED NOT NULL,
  `asset_id` int(10) UNSIGNED NOT NULL,
  `asset_type` varchar(65) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_categories`
--

CREATE TABLE `post_categories` (
  `rel_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `post_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_tags`
--

CREATE TABLE `post_tags` (
  `rel_id` int(10) UNSIGNED NOT NULL,
  `post_id` int(10) UNSIGNED NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(10) UNSIGNED NOT NULL,
  `branch_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(190) NOT NULL,
  `code` varchar(12) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `duration` varchar(20) DEFAULT NULL,
  `cost` decimal(4,0) DEFAULT NULL,
  `description` tinytext DEFAULT NULL,
  `location` varchar(190) DEFAULT NULL,
  `client` varchar(100) NOT NULL,
  `coordinator` int(10) UNSIGNED NOT NULL,
  `attachment` blob DEFAULT NULL,
  `status` varchar(15) NOT NULL DEFAULT 'active',
  `created_byid` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `remembered_logins`
--

CREATE TABLE `remembered_logins` (
  `token_hash` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id` int(10) UNSIGNED NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `remembered_logins`
--

INSERT INTO `remembered_logins` (`token_hash`, `id`, `expires_at`) VALUES
('6704e220b3e15477d24b58c5dacdb5c2b7a314cbf06ff7fe6409941ecd6c226d', 1270, '2022-02-13 08:23:19'),
('f79f219082c82bc2680de7655b77984e59e1ca3b19810206aaab6a76ffc6bb74', 1270, '2022-03-25 12:44:23');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `role_description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `created_byid` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `role_description`, `created_at`, `modified_at`, `created_byid`, `deleted_at`) VALUES
(1, 'Superadmin', 'Superadmin privileges give complete control to anyone who&#39;s been assigned this role.', '2020-05-07 00:08:07', '2022-05-10 20:36:20', 1270, 0),
(2, 'Subscriber', 'Basic role for users', '2021-02-19 01:46:04', '2022-05-21 14:42:29', 1270, 0),
(208, 'Office Manager', '', '2022-05-27 20:20:39', NULL, 1270, 0),
(209, 'Project Manager', '', '2022-05-27 20:20:53', NULL, 1270, 0),
(210, 'Operation Manager', '', '2022-05-27 20:21:08', NULL, 1270, 0),
(211, 'Technical Manager', '', '2022-05-27 20:21:19', NULL, 1270, 0),
(212, 'L3Engineer', '', '2022-05-27 20:21:33', NULL, 1270, 0),
(213, 'L2Engineer', '', '2022-05-27 20:21:40', NULL, 1270, 0),
(214, 'L1Engineer', '', '2022-05-27 20:21:46', NULL, 1270, 0),
(215, 'Office Administrator', '', '2022-05-27 20:22:13', NULL, 1270, 0),
(216, 'Regional Manager', '', '2022-05-27 20:25:28', NULL, 1270, 0),
(217, 'Sales Manager', '', '2022-05-27 20:25:34', NULL, 1270, 0),
(218, 'Project Engineer', '', '2022-05-27 20:25:42', NULL, 1270, 0),
(219, 'Technical Support', '', '2022-05-27 20:25:52', NULL, 1270, 0);

-- --------------------------------------------------------

--
-- Table structure for table `role_permission`
--

CREATE TABLE `role_permission` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `permission_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_permission`
--

INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES
(1, 76),
(2, 77),
(1, 72),
(1, 74),
(1, 73),
(1, 78),
(1, 81),
(1, 84),
(1, 79),
(1, 85),
(1, 86),
(1, 87),
(1, 88),
(1, 89),
(1, 92),
(1, 93),
(1, 94),
(1, 95),
(1, 96),
(1, 97),
(1, 98),
(1, 99),
(1, 100),
(1, 103),
(1, 77),
(1, 104),
(1, 82),
(1, 105),
(1, 106),
(1, 107),
(1, 108),
(1, 109),
(1, 110),
(1, 111),
(1, 114),
(1, 117),
(1, 118),
(1, 119),
(1, 120),
(1, 121),
(1, 91),
(1, 124),
(1, 128),
(1, 131),
(1, 132),
(1, 133),
(1, 135),
(1, 138),
(1, 140),
(1, 142),
(1, 145),
(1, 146),
(1, 147),
(1, 148),
(1, 149),
(1, 150),
(1, 151),
(1, 152),
(1, 153),
(1, 154),
(1, 155),
(1, 156),
(1, 157),
(1, 158),
(1, 159),
(1, 160),
(1, 161),
(1, 162),
(1, 163),
(1, 164),
(1, 165),
(1, 166),
(1, 167),
(1, 168),
(1, 169),
(1, 170),
(1, 171),
(1, 172),
(1, 173),
(1, 174),
(1, 176),
(1, 177),
(1, 179),
(1, 182),
(1, 183),
(1, 185),
(1, 188),
(1, 189),
(1, 190),
(1, 191),
(1, 192),
(1, 193),
(1, 194),
(1, 195),
(1, 196),
(1, 197),
(1, 198),
(1, 199),
(1, 200),
(1, 201),
(1, 202),
(1, 203),
(1, 205),
(1, 206),
(1, 207),
(1, 208),
(1, 209),
(1, 210),
(1, 212),
(1, 213),
(1, 214),
(1, 215),
(1, 216),
(1, 218),
(1, 219),
(1, 220),
(1, 221),
(1, 223),
(1, 287),
(1, 288),
(1, 289),
(1, 290),
(1, 292),
(1, 293),
(1, 294),
(1, 295),
(1, 298),
(1, 299),
(1, 300),
(1, 301),
(1, 302),
(1, 303),
(1, 305),
(1, 306),
(208, 77),
(209, 77),
(210, 77),
(211, 77),
(212, 77),
(213, 77),
(214, 77),
(215, 77),
(216, 77),
(217, 77),
(218, 77),
(219, 77),
(1, 310),
(1, 311),
(1, 312),
(1, 313),
(1, 315),
(1, 316),
(1, 317),
(1, 318),
(1, 319),
(1, 320),
(1, 321),
(1, 322),
(1, 323),
(1, 324),
(1, 325),
(1, 326),
(1, 328),
(1, 329),
(1, 330),
(1, 331),
(1, 332),
(1, 333),
(1, 334),
(1, 335),
(1, 336),
(1, 337),
(1, 338),
(1, 339),
(1, 340),
(1, 341),
(1, 342),
(1, 343),
(1, 344),
(1, 345),
(1, 346),
(1, 347),
(1, 348),
(1, 349),
(1, 350),
(1, 351),
(1, 352),
(1, 353),
(1, 354),
(1, 355),
(1, 357),
(1, 358),
(1, 359),
(1, 360),
(1, 361),
(1, 362),
(1, 363);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(10) NOT NULL,
  `session_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `session_access` decimal(15,0) NOT NULL,
  `session_variable` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `session_backup`
--

CREATE TABLE `session_backup` (
  `id` int(10) UNSIGNED NOT NULL,
  `controller` varchar(20) NOT NULL,
  `context` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `session_backup`
--

INSERT INTO `session_backup` (`id`, `controller`, `context`, `created_at`) VALUES
(67, 'user_settings', 'a:11:{s:16:\"records_per_page\";s:2:\"25\";s:21:\"additional_conditions\";s:27:\"deleted_at:0, status:active\";s:17:\"trash_can_support\";s:4:\"true\";s:10:\"paging_top\";s:4:\"true\";s:13:\"paging_bottom\";s:5:\"false\";s:10:\"bulk_trash\";s:4:\"true\";s:10:\"bulk_clone\";s:5:\"false\";s:5:\"query\";s:6:\"status\";s:12:\"filter_alias\";s:1:\"s\";s:9:\"filter_by\";a:2:{i:0;s:9:\"firstname\";i:1;s:8:\"lastname\";}s:12:\"sort_columns\";a:3:{i:0;s:9:\"firstname\";i:1;s:10:\"created_at\";i:2;s:11:\"modified_at\";}}', '2022-05-24 00:57:14'),
(68, 'permission_settings', 'a:11:{s:16:\"records_per_page\";s:1:\"5\";s:21:\"additional_conditions\";s:12:\"deleted_at:0\";s:17:\"trash_can_support\";s:4:\"true\";s:10:\"paging_top\";s:4:\"true\";s:13:\"paging_bottom\";s:5:\"false\";s:10:\"bulk_trash\";s:4:\"true\";s:10:\"bulk_clone\";s:5:\"false\";s:5:\"query\";s:0:\"\";s:12:\"filter_alias\";s:1:\"s\";s:9:\"filter_by\";a:1:{i:0;s:15:\"permission_name\";}s:12:\"sort_columns\";a:3:{i:0;s:15:\"permission_name\";i:1;s:10:\"created_at\";i:2;s:11:\"modified_at\";}}', '2022-05-24 01:22:11');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `setting_name` varchar(65) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_name`, `setting_value`) VALUES
(1, 'app_version', '1.0.0'),
(2, 'app_db_version', '1.0.0'),
(3, 'default_category', ''),
(4, 'default_role', 'subscriber'),
(5, 'date_format', 'Y-m-d'),
(6, 'time_format', 'H:i:s'),
(7, 'show_gravatar', '1'),
(8, 'gravatar_size', '80'),
(9, 'gravatar_rating', 'r'),
(10, 'gravatar_default', 'wavatar'),
(11, 'can_register', '1'),
(12, 'summary_limit', '1'),
(13, 'site_name', 'LavaStudio'),
(14, 'site_url', 'http://lava-studio.co.uk'),
(15, 'site_email', 'ricardo.nalio.miller@gmail.com'),
(16, 'timezone', 'en'),
(17, 'locale', 'en'),
(18, 'app_name', 'Lava-Studio'),
(19, 'site_tagline', 'This is magmacore framework'),
(20, 'site_description', 'This is a generic description'),
(21, 'site_keywords', 'framework, magmacore'),
(22, 'week_starts_on', 'sunday'),
(23, 'global_table_rows_per_page', '5'),
(24, 'menu_icon', 'on'),
(25, 'menu_icon_size', '0.9');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `tag_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag_slug` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `created_byid` int(10) UNSIGNED NOT NULL,
  `deleted_at` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `tag_name`, `tag_slug`, `created_at`, `modified_at`, `created_byid`, `deleted_at`) VALUES
(29, 'Eve Booker', 'excepteur-mollitia-a', '2022-05-24 23:32:13', '2022-05-29 14:51:18', 1270, 0),
(30, 'Branden Blackburn', 'dolor-in-consectetur', '2022-05-24 23:32:16', '2022-05-29 14:51:18', 1270, 0),
(31, 'Marcia Adams', 'nihil-id-pariatur-e', '2022-05-24 23:32:19', NULL, 1270, 0);

-- --------------------------------------------------------

--
-- Table structure for table `temporary_role`
--

CREATE TABLE `temporary_role` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `prev_role_id` int(10) NOT NULL,
  `current_role_id` int(10) NOT NULL,
  `duration` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `extend_duration` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `temporary_role`
--

INSERT INTO `temporary_role` (`id`, `user_id`, `prev_role_id`, `current_role_id`, `duration`, `created_at`, `extend_duration`) VALUES
(13, 699, 110, 2, NULL, '2021-07-19 16:42:06', NULL),
(14, 702, 1, 2, NULL, '2021-07-19 23:08:44', NULL),
(15, 701, 1, 2, NULL, '2021-07-19 23:09:06', NULL),
(16, 701, 2, 110, NULL, '2021-07-20 00:39:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(10) UNSIGNED NOT NULL,
  `category` varchar(65) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ticket_desc` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachment` blob DEFAULT NULL,
  `status` enum('open','closed','resolved','') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `priority` enum('low','medium','high','critical') COLLATE utf8mb4_unicode_ci NOT NULL,
  `assigned_to` int(10) UNSIGNED DEFAULT NULL,
  `reassigned_to` int(10) UNSIGNED DEFAULT NULL,
  `created_byid` int(10) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_comments`
--

CREATE TABLE `ticket_comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `ticket_id` int(10) UNSIGNED NOT NULL,
  `comment` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `timesheets`
--

CREATE TABLE `timesheets` (
  `id` int(10) UNSIGNED NOT NULL,
  `employee_id` int(10) UNSIGNED NOT NULL,
  `customer` varchar(150) NOT NULL,
  `reference` varchar(25) NOT NULL,
  `project` varchar(25) NOT NULL,
  `costcenter` varchar(15) NOT NULL,
  `overtime` tinyint(1) NOT NULL DEFAULT 0,
  `week_start` datetime NOT NULL,
  `status` enum('Accepted','Pending','Waiting','Rejected') NOT NULL DEFAULT 'Pending',
  `status_note` tinytext DEFAULT NULL,
  `time` longtext NOT NULL,
  `created_byid` int(10) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `updates`
--

CREATE TABLE `updates` (
  `id` int(10) UNSIGNED NOT NULL,
  `old_version` varchar(5) NOT NULL,
  `new_version` varchar(5) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `updates`
--

INSERT INTO `updates` (`id`, `old_version`, `new_version`, `created_at`) VALUES
(1, '1.3', '1.4', '2022-02-08 13:52:06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `firstname` varchar(150) CHARACTER SET utf8mb4 NOT NULL,
  `lastname` varchar(150) CHARACTER SET utf8mb4 NOT NULL,
  `email` varchar(190) CHARACTER SET utf8mb4 NOT NULL,
  `gravatar` varchar(190) CHARACTER SET utf8mb4 DEFAULT NULL,
  `status` varchar(24) CHARACTER SET utf8mb4 NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `password_reset_hash` varchar(64) CHARACTER SET utf8mb4 DEFAULT NULL,
  `password_reset_expires_at` datetime DEFAULT NULL,
  `activation_token` varchar(64) CHARACTER SET utf8mb4 DEFAULT NULL,
  `created_byid` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` tinyint(1) DEFAULT 0,
  `deleted_at_datetime` datetime DEFAULT NULL,
  `remote_addr` varchar(65) CHARACTER SET utf8mb4 NOT NULL,
  `user_failed_logins` tinyint(1) NOT NULL,
  `user_last_failed_login` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `gravatar`, `status`, `password_hash`, `password_reset_hash`, `password_reset_expires_at`, `activation_token`, `created_byid`, `created_at`, `modified_at`, `deleted_at`, `deleted_at_datetime`, `remote_addr`, `user_failed_logins`, `user_last_failed_login`) VALUES
(1270, 'Ricardo', 'Miller', 'rnm@example.com', 'https://www.gravatar.com/avatar/a166ea8de2bcaf91d509aadaa0e41ae5?s=200&d=mystery&r=R', 'active', '$2y$10$l5pRuc4z0oMX2B7FPstCCur.uY7tp7GyqAYtUV0VzlKWyBxjTFZ7G', NULL, NULL, NULL, 1270, '2021-09-21 12:25:55', '2022-05-23 17:39:15', 0, NULL, '::1', 0, NULL),
(1425, 'Levi', 'Knox', 'wutyj@mailinator.com', 'https://www.gravatar.com/avatar/3b98e242236d37860c3d884e837ed118?s=200&d=mystery&r=R', 'active', '$2y$10$gCDnlEFxtfjVnrUZAwQGM.VvT596qqndduL9lqhmO2yIPPnNA8zv6', NULL, NULL, '5b0da80b586326eadb0bf38e90229b215fb94d6ce51d7bf3ff37520fc5f3d988', 1270, '2022-02-08 19:43:09', '2022-05-23 01:21:40', 0, '2022-05-19 19:13:34', '::1', 0, NULL),
(1430, 'Nina', 'Massey', 'dyjigubi@mailinator.com', 'https://www.gravatar.com/avatar/a63aa26ffcbf9730e2c08eca7af33f28?s=200&d=mystery&r=R', 'active', '$2y$10$F3YFOt13WBurWriK2RPmO.Gm9FRpZDEaSldmw8kiYptduDkAiPFtO', NULL, NULL, 'd2863f052ecb0e35a0577ebc02bcfba5b628921bfc533fb12198f67e24b3c120', 1270, '2022-05-08 11:29:05', '2022-05-23 01:21:55', 0, NULL, '::1', 0, NULL),
(1433, 'Sylvia', 'Giles', 'mavy@mailinator.com', 'https://www.gravatar.com/avatar/261229d57ceb75267def2e83d3203718?s=200&d=mystery&r=R', 'active', '$2y$10$/F2YtgrjuiQvLnAsoz9vrOsK2Zj7EGmEGzru/MSnJSEmoUxHdtayi', NULL, NULL, 'ade56f341d0b57cfbc8965f2e449224ad90d154d11a523115827a10a967d68f2', 1270, '2022-05-23 00:24:55', '2022-05-23 18:22:24', 0, NULL, '::1', 0, NULL),
(1436, 'Diana', 'Maldonado', 'nyzu@mailinator.com', 'https://www.gravatar.com/avatar/1f11829311f1ef3296c9d51bcc48d416?s=200&d=mystery&r=R', 'active', '$2y$10$bYcrhY8Fe98u/VzE2ELUw.b5DmjTHPtRR7XUKUNMWzPgeonwriqNW', NULL, NULL, 'e5d3aeb40546e1f373ad19eaa4dba88619a73b4ccfa32da436a8dc38a1e02d89', 1270, '2022-05-23 18:20:42', '2022-05-23 18:22:27', 0, NULL, '::1', 0, NULL),
(1437, 'Jim', 'Willis', 'jimwillis@gmail.com', 'https://www.gravatar.com/avatar/986ac02dd05b4533d457a939c80afb75?s=200&d=mystery&r=R', 'active', '$2y$10$Koc9g0wQ7Li5NRqIWRNFC.Fe0lE9XXTmuLKjkTLxC3v0m2Qqhs/fy', NULL, NULL, '23baff250b03366149cd62c63c4f4971c9c8c4aa3ee9df25a77bc7a7396e70e1', 1270, '2022-05-28 08:42:07', '2022-05-28 13:30:36', 0, NULL, '::1', 0, NULL),
(1438, 'Julian', 'Lyons', 'rynas@mailinator.com', 'https://www.gravatar.com/avatar/aeb179f553bbefa68fc3b77931774dd0?s=200&d=mystery&r=R', 'active', '$2y$10$1Mk3V5V6VJUuvgZPtISdieyIPF5E2jCAGK32AFA19PUN1S7VIk0m2', NULL, NULL, 'a2dffa479e83e5103e1eeec9c97b1459fa215bd0a193e161e1d5aaf5972e5d7c', 1270, '2022-06-05 13:00:54', '2022-06-05 13:02:47', 0, NULL, '::1', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_group`
--

CREATE TABLE `user_group` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `group_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_group`
--

INSERT INTO `user_group` (`user_id`, `group_id`) VALUES
(1270, 2);

-- --------------------------------------------------------

--
-- Table structure for table `user_logs`
--

CREATE TABLE `user_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `level` int(10) NOT NULL,
  `level_name` varchar(100) NOT NULL,
  `context` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `message` text NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_metadata`
--

CREATE TABLE `user_metadata` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `login` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `logout` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `brute_force` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `user_browser` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_metadata`
--

INSERT INTO `user_metadata` (`id`, `user_id`, `login`, `logout`, `brute_force`, `user_browser`) VALUES
(97, 1425, 'a:2:{s:10:\"last_login\";N;s:10:\"login_from\";N;}', 'a:2:{s:11:\"last_logout\";N;s:11:\"logout_from\";N;}', 'a:2:{s:13:\"failed_logins\";N;s:22:\"failed_login_timestamp\";N;}', 'O:8:\"stdClass\":30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}'),
(102, 1430, 'a:2:{s:10:\"last_login\";N;s:10:\"login_from\";N;}', 'a:2:{s:11:\"last_logout\";N;s:11:\"logout_from\";N;}', 'a:2:{s:13:\"failed_logins\";N;s:22:\"failed_login_timestamp\";N;}', 'O:8:\"stdClass\":30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}'),
(104, 1270, 'a:2:{s:10:\"last_login\";s:19:\"2022-06-05 21:35:27\";s:10:\"login_from\";s:22:\"http://localhost/login\";}', 'a:2:{s:11:\"last_logout\";N;s:11:\"logout_from\";N;}', 'a:2:{s:13:\"failed_logins\";N;s:22:\"failed_login_timestamp\";N;}', 'O:8:\"stdClass\":30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}'),
(106, 1433, 'a:2:{s:10:\"last_login\";N;s:10:\"login_from\";N;}', 'a:2:{s:11:\"last_logout\";N;s:11:\"logout_from\";N;}', 'a:2:{s:13:\"failed_logins\";N;s:22:\"failed_login_timestamp\";N;}', 'O:8:\"stdClass\":30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}'),
(107, 1436, 'a:2:{s:10:\"last_login\";N;s:10:\"login_from\";N;}', 'a:2:{s:11:\"last_logout\";N;s:11:\"logout_from\";N;}', 'a:2:{s:13:\"failed_logins\";N;s:22:\"failed_login_timestamp\";N;}', 'O:8:\"stdClass\":30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}'),
(108, 1437, 'a:2:{s:10:\"last_login\";s:19:\"2022-05-29 12:06:38\";s:10:\"login_from\";s:22:\"http://localhost/login\";}', 'a:2:{s:11:\"last_logout\";N;s:11:\"logout_from\";N;}', 'a:2:{s:13:\"failed_logins\";N;s:22:\"failed_login_timestamp\";N;}', 'O:8:\"stdClass\":30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}'),
(109, 1438, 'a:2:{s:10:\"last_login\";N;s:10:\"login_from\";N;}', 'a:2:{s:11:\"last_logout\";N;s:11:\"logout_from\";N;}', 'a:2:{s:13:\"failed_logins\";N;s:22:\"failed_login_timestamp\";N;}', 'O:8:\"stdClass\":30:{s:18:\"browser_name_regex\";s:6:\"~^.*$~\";s:20:\"browser_name_pattern\";s:1:\"*\";s:7:\"browser\";s:15:\"Default Browser\";s:7:\"version\";s:1:\"0\";s:8:\"majorver\";s:1:\"0\";s:8:\"minorver\";s:1:\"0\";s:8:\"platform\";s:7:\"unknown\";s:5:\"alpha\";s:0:\"\";s:4:\"beta\";s:0:\"\";s:5:\"win16\";s:0:\"\";s:5:\"win32\";s:0:\"\";s:5:\"win64\";s:0:\"\";s:6:\"frames\";s:1:\"1\";s:7:\"iframes\";s:0:\"\";s:6:\"tables\";s:1:\"1\";s:7:\"cookies\";s:0:\"\";s:16:\"backgroundsounds\";s:0:\"\";s:3:\"cdf\";s:0:\"\";s:8:\"vbscript\";s:0:\"\";s:11:\"javaapplets\";s:0:\"\";s:10:\"javascript\";s:0:\"\";s:15:\"activexcontrols\";s:0:\"\";s:8:\"isbanned\";s:0:\"\";s:14:\"ismobiledevice\";s:0:\"\";s:19:\"issyndicationreader\";s:0:\"\";s:7:\"crawler\";s:0:\"\";s:10:\"cssversion\";s:1:\"0\";s:11:\"supportscss\";s:0:\"\";s:3:\"aol\";s:0:\"\";s:10:\"aolversion\";s:1:\"0\";}');

-- --------------------------------------------------------

--
-- Table structure for table `user_note`
--

CREATE TABLE `user_note` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `notes` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `user_note`
--

INSERT INTO `user_note` (`id`, `user_id`, `notes`, `created_at`, `modified_at`) VALUES
(11, 1425, 'Welcome Lev Knox', '2022-02-08 19:43:09', NULL),
(16, 1430, 'Welcome Nina Massey', '2022-05-08 11:29:05', NULL),
(17, 1270, 'Welcome Oprah Montoya', '2022-02-08 19:37:50', '2022-05-15 16:28:32'),
(1272, 1433, 'Welcome Sylvia Giles', '2022-05-23 00:24:55', NULL),
(1273, 1436, 'Welcome Diana Maldonado', '2022-05-23 18:20:43', NULL),
(1274, 1437, 'Welcome Jim Willis', '2022-05-28 08:42:07', NULL),
(1275, 1438, 'Welcome Julian Lyons', '2022-06-05 13:00:54', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_preferences`
--

CREATE TABLE `user_preferences` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `address` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `language` varchar(5) DEFAULT NULL,
  `week_start_on` varchar(10) DEFAULT 'Sunday',
  `enable_notification` int(1) DEFAULT 1,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_preferences`
--

INSERT INTO `user_preferences` (`id`, `user_id`, `address`, `language`, `week_start_on`, `enable_notification`, `updated_at`) VALUES
(4, 1425, NULL, 'en_GB', '0', 1, NULL),
(9, 1430, NULL, 'en_GB', '0', 1, NULL),
(12, 1433, NULL, 'en_GB', 'Monday', 1, NULL),
(13, 1436, NULL, 'en_GB', 'Monday', 1, NULL),
(14, 1437, NULL, 'en_GB', 'Monday', 1, NULL),
(15, 1438, NULL, 'en_GB', 'Monday', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`user_id`, `role_id`) VALUES
(1270, 1),
(1425, 2),
(1430, 2),
(1433, 2),
(1437, 214),
(1436, 2),
(1438, 212);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `branch` (`branch`),
  ADD UNIQUE KEY `code` (`code`),
  ADD UNIQUE KEY `address` (`address`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cat_name` (`cat_name`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `controllers`
--
ALTER TABLE `controllers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `controller` (`controller`);

--
-- Indexes for table `controller_settings`
--
ALTER TABLE `controller_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `controller_name` (`controller_name`),
  ADD KEY `controller_menu_id` (`controller_menu_id`);

--
-- Indexes for table `cost_centers`
--
ALTER TABLE `cost_centers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `dashboard`
--
ALTER TABLE `dashboard`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_log`
--
ALTER TABLE `event_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `group_name` (`group_name`);

--
-- Indexes for table `group_role`
--
ALTER TABLE `group_role`
  ADD UNIQUE KEY `role_id` (`role_id`),
  ADD UNIQUE KEY `group_id` (`group_id`);

--
-- Indexes for table `holidays`
--
ALTER TABLE `holidays`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD UNIQUE KEY `holiday_date` (`holiday_date`);

--
-- Indexes for table `leave_types`
--
ALTER TABLE `leave_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `leave_type` (`leave_type`);

--
-- Indexes for table `localisations`
--
ALTER TABLE `localisations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `locale` (`locale`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_name` (`menu_name`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_item_original_name` (`item_original_label`),
  ADD KEY `main_menu_id` (`item_original_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `metadata`
--
ALTER TABLE `metadata`
  ADD UNIQUE KEY `post_id` (`post_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `migration_name` (`migration_name`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `title` (`title`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permission_name` (`permission_name`);

--
-- Indexes for table `plugins`
--
ALTER TABLE `plugins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `title` (`title`);

--
-- Indexes for table `post_assets`
--
ALTER TABLE `post_assets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `asset_id` (`asset_id`);

--
-- Indexes for table `post_categories`
--
ALTER TABLE `post_categories`
  ADD PRIMARY KEY (`rel_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `post_tags`
--
ALTER TABLE `post_tags`
  ADD PRIMARY KEY (`rel_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `remembered_logins`
--
ALTER TABLE `remembered_logins`
  ADD PRIMARY KEY (`token_hash`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `role_permission`
--
ALTER TABLE `role_permission`
  ADD KEY `role_id` (`role_id`) USING BTREE,
  ADD KEY `permission_id` (`permission_id`) USING BTREE;

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `session_backup`
--
ALTER TABLE `session_backup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_name` (`setting_name`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tag_name` (`tag_name`);

--
-- Indexes for table `temporary_role`
--
ALTER TABLE `temporary_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_comments`
--
ALTER TABLE `ticket_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_comment_id` (`ticket_id`);

--
-- Indexes for table `timesheets`
--
ALTER TABLE `timesheets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `updates`
--
ALTER TABLE `updates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `old_version` (`old_version`),
  ADD UNIQUE KEY `new_version` (`new_version`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `password_reset_hash` (`password_reset_hash`),
  ADD UNIQUE KEY `activation_token` (`activation_token`);

--
-- Indexes for table `user_group`
--
ALTER TABLE `user_group`
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `user_logs`
--
ALTER TABLE `user_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_log_id` (`user_id`);

--
-- Indexes for table `user_metadata`
--
ALTER TABLE `user_metadata`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `user_note`
--
ALTER TABLE `user_note`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_note_id` (`user_id`);

--
-- Indexes for table `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `controllers`
--
ALTER TABLE `controllers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=244;

--
-- AUTO_INCREMENT for table `controller_settings`
--
ALTER TABLE `controller_settings`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `cost_centers`
--
ALTER TABLE `cost_centers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `dashboard`
--
ALTER TABLE `dashboard`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_log`
--
ALTER TABLE `event_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=357;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `holidays`
--
ALTER TABLE `holidays`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `leave_types`
--
ALTER TABLE `leave_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `localisations`
--
ALTER TABLE `localisations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=608;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3277;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=364;

--
-- AUTO_INCREMENT for table `plugins`
--
ALTER TABLE `plugins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `post_assets`
--
ALTER TABLE `post_assets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `post_categories`
--
ALTER TABLE `post_categories`
  MODIFY `rel_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `post_tags`
--
ALTER TABLE `post_tags`
  MODIFY `rel_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=220;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=245;

--
-- AUTO_INCREMENT for table `session_backup`
--
ALTER TABLE `session_backup`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `temporary_role`
--
ALTER TABLE `temporary_role`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `ticket_comments`
--
ALTER TABLE `ticket_comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timesheets`
--
ALTER TABLE `timesheets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `updates`
--
ALTER TABLE `updates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1439;

--
-- AUTO_INCREMENT for table `user_logs`
--
ALTER TABLE `user_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_metadata`
--
ALTER TABLE `user_metadata`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `user_note`
--
ALTER TABLE `user_note`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1276;

--
-- AUTO_INCREMENT for table `user_preferences`
--
ALTER TABLE `user_preferences`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `branches`
--
ALTER TABLE `branches`
  ADD CONSTRAINT `project_branch_id` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `controller_settings`
--
ALTER TABLE `controller_settings`
  ADD CONSTRAINT `controller_menu_id` FOREIGN KEY (`controller_menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `main_menu_id` FOREIGN KEY (`item_original_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `metadata`
--
ALTER TABLE `metadata`
  ADD CONSTRAINT `post_metadata_rel` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `post_assets`
--
ALTER TABLE `post_assets`
  ADD CONSTRAINT `asset` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `post_categories`
--
ALTER TABLE `post_categories`
  ADD CONSTRAINT `category_rel` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `post_rel` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `post_tags`
--
ALTER TABLE `post_tags`
  ADD CONSTRAINT `post_tag_rel` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tag_rel` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `role_permission`
--
ALTER TABLE `role_permission`
  ADD CONSTRAINT `permission` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ticket_comments`
--
ALTER TABLE `ticket_comments`
  ADD CONSTRAINT `ticket_comment_id` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_logs`
--
ALTER TABLE `user_logs`
  ADD CONSTRAINT `user_log_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_metadata`
--
ALTER TABLE `user_metadata`
  ADD CONSTRAINT `user_metadata_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_note`
--
ALTER TABLE `user_note`
  ADD CONSTRAINT `user_note_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD CONSTRAINT `user_preferences_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_role_id` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
