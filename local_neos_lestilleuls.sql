-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Jeu 26 Mars 2015 à 23:56
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `local_neos_lestilleuls`
--

-- --------------------------------------------------------

--
-- Structure de la table `flow_doctrine_migrationstatus`
--

DROP TABLE IF EXISTS `flow_doctrine_migrationstatus`;
CREATE TABLE IF NOT EXISTS `flow_doctrine_migrationstatus` (
  `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `flow_doctrine_migrationstatus`
--

INSERT INTO `flow_doctrine_migrationstatus` (`version`) VALUES
('20110613223837'),
('20110613224537'),
('20110620155001'),
('20110620155002'),
('20110714212900'),
('20110824124835'),
('20110824124935'),
('20110824125035'),
('20110824125135'),
('20110824181409'),
('20110919164835'),
('20110920104739'),
('20110920125736'),
('20110923125535'),
('20110923125536'),
('20110923125537'),
('20110923125538'),
('20110925123119'),
('20110925123120'),
('20110928114048'),
('20111215172027'),
('20120328152041'),
('20120329220340'),
('20120329220341'),
('20120329220342'),
('20120329220343'),
('20120329220344'),
('20120412093748'),
('20120420132456'),
('20120429213445'),
('20120429213446'),
('20120429213447'),
('20120429213448'),
('20120520211354'),
('20120521125401'),
('20120525141545'),
('20120625211647'),
('20120829124549'),
('20120930203452'),
('20120930211542'),
('20121001181137'),
('20121001201712'),
('20121001202223'),
('20121001204512'),
('20121011140946'),
('20121014005902'),
('20121030221151'),
('20121030221352'),
('20121031190213'),
('20130201104344'),
('20130213130515'),
('20130218100324'),
('20130319131400'),
('20130522131641'),
('20130522132835'),
('20130605174712'),
('20130702151425'),
('20130730151319'),
('20130919143352'),
('20130930182839'),
('20131111235827'),
('20131129110302'),
('20131205174631'),
('20140206124123'),
('20140208173140'),
('20140325173151'),
('20140826164246'),
('20141001151417'),
('20141003233738'),
('20141127195800'),
('20150211181736');

-- --------------------------------------------------------

--
-- Structure de la table `typo3_flow_mvc_routing_objectpathmapping`
--

DROP TABLE IF EXISTS `typo3_flow_mvc_routing_objectpathmapping`;
CREATE TABLE IF NOT EXISTS `typo3_flow_mvc_routing_objectpathmapping` (
  `objecttype` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `uripattern` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pathsegment` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `identifier` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`objecttype`,`uripattern`,`pathsegment`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `typo3_flow_resource_publishing_abstractpublishingconfiguration`
--

DROP TABLE IF EXISTS `typo3_flow_resource_publishing_abstractpublishingconfiguration`;
CREATE TABLE IF NOT EXISTS `typo3_flow_resource_publishing_abstractpublishingconfiguration` (
  `persistence_object_identifier` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `dtype` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`persistence_object_identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `typo3_flow_resource_resource`
--

DROP TABLE IF EXISTS `typo3_flow_resource_resource`;
CREATE TABLE IF NOT EXISTS `typo3_flow_resource_resource` (
  `persistence_object_identifier` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `resourcepointer` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fileextension` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `publishingconfiguration` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`persistence_object_identifier`),
  KEY `IDX_B4D45B323CB65D1` (`resourcepointer`),
  KEY `IDX_B4D45B32A4A851AF` (`publishingconfiguration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `typo3_flow_resource_resourcepointer`
--

DROP TABLE IF EXISTS `typo3_flow_resource_resourcepointer`;
CREATE TABLE IF NOT EXISTS `typo3_flow_resource_resourcepointer` (
  `hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `typo3_flow_security_account`
--

DROP TABLE IF EXISTS `typo3_flow_security_account`;
CREATE TABLE IF NOT EXISTS `typo3_flow_security_account` (
  `persistence_object_identifier` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `party` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `accountidentifier` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `authenticationprovidername` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `credentialssource` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `creationdate` datetime NOT NULL,
  `expirationdate` datetime DEFAULT NULL,
  PRIMARY KEY (`persistence_object_identifier`),
  UNIQUE KEY `flow3_identity_typo3_flow3_security_account` (`accountidentifier`,`authenticationprovidername`),
  KEY `IDX_65EFB31C89954EE0` (`party`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `typo3_flow_security_account`
--

INSERT INTO `typo3_flow_security_account` (`persistence_object_identifier`, `party`, `accountidentifier`, `authenticationprovidername`, `credentialssource`, `creationdate`, `expirationdate`) VALUES
('5e7ab7a4-b6a0-39f6-53ec-134cfc3c980d', '285cb57d-0eca-1d76-0d26-0d2fbf5548f1', 'gcopin', 'Typo3BackendProvider', 'bcrypt=>$2a$14$14a6.Zu.oj9vnJn1L1KwCOteMyYL3RAcIwac9FacNxpC/oOikLJlq', '2015-03-26 22:47:55', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `typo3_flow_security_account_roles_join`
--

DROP TABLE IF EXISTS `typo3_flow_security_account_roles_join`;
CREATE TABLE IF NOT EXISTS `typo3_flow_security_account_roles_join` (
  `flow_security_account` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `flow_policy_role` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`flow_security_account`,`flow_policy_role`),
  KEY `IDX_ADF11BBC58842EFC` (`flow_security_account`),
  KEY `IDX_ADF11BBC23A1047C` (`flow_policy_role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `typo3_flow_security_account_roles_join`
--

INSERT INTO `typo3_flow_security_account_roles_join` (`flow_security_account`, `flow_policy_role`) VALUES
('5e7ab7a4-b6a0-39f6-53ec-134cfc3c980d', 'TYPO3.Neos:Administrator');

-- --------------------------------------------------------

--
-- Structure de la table `typo3_flow_security_authorization_resource_securitypublis_861cb`
--

DROP TABLE IF EXISTS `typo3_flow_security_authorization_resource_securitypublis_861cb`;
CREATE TABLE IF NOT EXISTS `typo3_flow_security_authorization_resource_securitypublis_861cb` (
  `persistence_object_identifier` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `allowedroles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  PRIMARY KEY (`persistence_object_identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `typo3_flow_security_policy_role`
--

DROP TABLE IF EXISTS `typo3_flow_security_policy_role`;
CREATE TABLE IF NOT EXISTS `typo3_flow_security_policy_role` (
  `identifier` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sourcehint` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `typo3_flow_security_policy_role`
--

INSERT INTO `typo3_flow_security_policy_role` (`identifier`, `sourcehint`) VALUES
('Anonymous', 'system'),
('AuthenticatedUser', 'system'),
('Everybody', 'system'),
('TYPO3.Neos:Administrator', 'policy'),
('TYPO3.Neos:Editor', 'policy'),
('TYPO3.Setup:Administrator', 'policy'),
('TYPO3.TYPO3CR:Administrator', 'policy');

-- --------------------------------------------------------

--
-- Structure de la table `typo3_flow_security_policy_role_parentroles_join`
--

DROP TABLE IF EXISTS `typo3_flow_security_policy_role_parentroles_join`;
CREATE TABLE IF NOT EXISTS `typo3_flow_security_policy_role_parentroles_join` (
  `flow_policy_role` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `parent_role` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`flow_policy_role`,`parent_role`),
  KEY `IDX_D459C58E23A1047C` (`flow_policy_role`),
  KEY `IDX_D459C58E6A8ABCDE` (`parent_role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `typo3_flow_security_policy_role_parentroles_join`
--

INSERT INTO `typo3_flow_security_policy_role_parentroles_join` (`flow_policy_role`, `parent_role`) VALUES
('TYPO3.Neos:Administrator', 'TYPO3.Neos:Editor'),
('TYPO3.Neos:Editor', 'TYPO3.TYPO3CR:Administrator');

-- --------------------------------------------------------

--
-- Structure de la table `typo3_media_domain_model_asset`
--

DROP TABLE IF EXISTS `typo3_media_domain_model_asset`;
CREATE TABLE IF NOT EXISTS `typo3_media_domain_model_asset` (
  `persistence_object_identifier` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `dtype` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `resource` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `caption` longtext COLLATE utf8_unicode_ci NOT NULL,
  `lastmodified` datetime NOT NULL,
  PRIMARY KEY (`persistence_object_identifier`),
  KEY `IDX_B8306B8EBC91F416` (`resource`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `typo3_media_domain_model_asset_tags_join`
--

DROP TABLE IF EXISTS `typo3_media_domain_model_asset_tags_join`;
CREATE TABLE IF NOT EXISTS `typo3_media_domain_model_asset_tags_join` (
  `media_asset` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `media_tag` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`media_asset`,`media_tag`),
  KEY `IDX_DAF7A1EB1DB69EED` (`media_asset`),
  KEY `IDX_DAF7A1EB48D8C57E` (`media_tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `typo3_media_domain_model_audio`
--

DROP TABLE IF EXISTS `typo3_media_domain_model_audio`;
CREATE TABLE IF NOT EXISTS `typo3_media_domain_model_audio` (
  `persistence_object_identifier` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`persistence_object_identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `typo3_media_domain_model_document`
--

DROP TABLE IF EXISTS `typo3_media_domain_model_document`;
CREATE TABLE IF NOT EXISTS `typo3_media_domain_model_document` (
  `persistence_object_identifier` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`persistence_object_identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `typo3_media_domain_model_image`
--

DROP TABLE IF EXISTS `typo3_media_domain_model_image`;
CREATE TABLE IF NOT EXISTS `typo3_media_domain_model_image` (
  `persistence_object_identifier` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `imagevariants` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  PRIMARY KEY (`persistence_object_identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `typo3_media_domain_model_tag`
--

DROP TABLE IF EXISTS `typo3_media_domain_model_tag`;
CREATE TABLE IF NOT EXISTS `typo3_media_domain_model_tag` (
  `persistence_object_identifier` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`persistence_object_identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `typo3_media_domain_model_video`
--

DROP TABLE IF EXISTS `typo3_media_domain_model_video`;
CREATE TABLE IF NOT EXISTS `typo3_media_domain_model_video` (
  `persistence_object_identifier` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  PRIMARY KEY (`persistence_object_identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `typo3_neosdemotypo3org_domain_model_registration`
--

DROP TABLE IF EXISTS `typo3_neosdemotypo3org_domain_model_registration`;
CREATE TABLE IF NOT EXISTS `typo3_neosdemotypo3org_domain_model_registration` (
  `persistence_object_identifier` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`persistence_object_identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `typo3_neos_domain_model_domain`
--

DROP TABLE IF EXISTS `typo3_neos_domain_model_domain`;
CREATE TABLE IF NOT EXISTS `typo3_neos_domain_model_domain` (
  `persistence_object_identifier` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `site` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hostpattern` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`persistence_object_identifier`),
  UNIQUE KEY `flow_identity_typo3_neos_domain_model_domain` (`hostpattern`),
  KEY `IDX_F227E8F6694309E4` (`site`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `typo3_neos_domain_model_site`
--

DROP TABLE IF EXISTS `typo3_neos_domain_model_site`;
CREATE TABLE IF NOT EXISTS `typo3_neos_domain_model_site` (
  `persistence_object_identifier` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nodename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state` int(11) NOT NULL,
  `siteresourcespackagekey` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`persistence_object_identifier`),
  UNIQUE KEY `flow3_identity_typo3_typo3_domain_model_site` (`nodename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `typo3_neos_domain_model_site`
--

INSERT INTO `typo3_neos_domain_model_site` (`persistence_object_identifier`, `name`, `nodename`, `state`, `siteresourcespackagekey`) VALUES
('617b3c9f-b16a-5ba5-1f1d-73e66efbe8de', 'Tilleuls', 'tilleuls', 1, 'Gc.Tilleuls');

-- --------------------------------------------------------

--
-- Structure de la table `typo3_neos_domain_model_user`
--

DROP TABLE IF EXISTS `typo3_neos_domain_model_user`;
CREATE TABLE IF NOT EXISTS `typo3_neos_domain_model_user` (
  `persistence_object_identifier` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `preferences` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`persistence_object_identifier`),
  UNIQUE KEY `UNIQ_E3F98B13E931A6F5` (`preferences`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `typo3_neos_domain_model_user`
--

INSERT INTO `typo3_neos_domain_model_user` (`persistence_object_identifier`, `preferences`) VALUES
('285cb57d-0eca-1d76-0d26-0d2fbf5548f1', '0748901e-c30c-b712-18a7-05ae5f8ec7e5');

-- --------------------------------------------------------

--
-- Structure de la table `typo3_neos_domain_model_userpreferences`
--

DROP TABLE IF EXISTS `typo3_neos_domain_model_userpreferences`;
CREATE TABLE IF NOT EXISTS `typo3_neos_domain_model_userpreferences` (
  `persistence_object_identifier` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `preferences` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  PRIMARY KEY (`persistence_object_identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `typo3_neos_domain_model_userpreferences`
--

INSERT INTO `typo3_neos_domain_model_userpreferences` (`persistence_object_identifier`, `preferences`) VALUES
('0748901e-c30c-b712-18a7-05ae5f8ec7e5', 'a:0:{}');

-- --------------------------------------------------------

--
-- Structure de la table `typo3_party_domain_model_abstractparty`
--

DROP TABLE IF EXISTS `typo3_party_domain_model_abstractparty`;
CREATE TABLE IF NOT EXISTS `typo3_party_domain_model_abstractparty` (
  `persistence_object_identifier` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `dtype` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`persistence_object_identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `typo3_party_domain_model_abstractparty`
--

INSERT INTO `typo3_party_domain_model_abstractparty` (`persistence_object_identifier`, `dtype`) VALUES
('285cb57d-0eca-1d76-0d26-0d2fbf5548f1', 'typo3_neos_user');

-- --------------------------------------------------------

--
-- Structure de la table `typo3_party_domain_model_electronicaddress`
--

DROP TABLE IF EXISTS `typo3_party_domain_model_electronicaddress`;
CREATE TABLE IF NOT EXISTS `typo3_party_domain_model_electronicaddress` (
  `persistence_object_identifier` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `identifier` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `usagetype` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `approved` tinyint(1) NOT NULL,
  PRIMARY KEY (`persistence_object_identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `typo3_party_domain_model_person`
--

DROP TABLE IF EXISTS `typo3_party_domain_model_person`;
CREATE TABLE IF NOT EXISTS `typo3_party_domain_model_person` (
  `persistence_object_identifier` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `primaryelectronicaddress` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`persistence_object_identifier`),
  UNIQUE KEY `UNIQ_C60479E15E237E06` (`name`),
  KEY `IDX_C60479E1A7CECF13` (`primaryelectronicaddress`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `typo3_party_domain_model_person`
--

INSERT INTO `typo3_party_domain_model_person` (`persistence_object_identifier`, `name`, `primaryelectronicaddress`) VALUES
('285cb57d-0eca-1d76-0d26-0d2fbf5548f1', '4e337b04-eb55-03b1-c0f8-f645587c9173', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `typo3_party_domain_model_personname`
--

DROP TABLE IF EXISTS `typo3_party_domain_model_personname`;
CREATE TABLE IF NOT EXISTS `typo3_party_domain_model_personname` (
  `persistence_object_identifier` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `middlename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `othername` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fullname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`persistence_object_identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `typo3_party_domain_model_personname`
--

INSERT INTO `typo3_party_domain_model_personname` (`persistence_object_identifier`, `title`, `firstname`, `middlename`, `lastname`, `othername`, `alias`, `fullname`) VALUES
('4e337b04-eb55-03b1-c0f8-f645587c9173', '', 'Grégory', '', 'Copin', '', 'gcopin', 'Grégory Copin');

-- --------------------------------------------------------

--
-- Structure de la table `typo3_party_domain_model_person_electronicaddresses_join`
--

DROP TABLE IF EXISTS `typo3_party_domain_model_person_electronicaddresses_join`;
CREATE TABLE IF NOT EXISTS `typo3_party_domain_model_person_electronicaddresses_join` (
  `party_person` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `party_electronicaddress` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`party_person`,`party_electronicaddress`),
  KEY `IDX_759CC08F72AAAA2F` (`party_person`),
  KEY `IDX_759CC08FB06BD60D` (`party_electronicaddress`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `typo3_typo3cr_domain_model_contentobjectproxy`
--

DROP TABLE IF EXISTS `typo3_typo3cr_domain_model_contentobjectproxy`;
CREATE TABLE IF NOT EXISTS `typo3_typo3cr_domain_model_contentobjectproxy` (
  `persistence_object_identifier` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `targettype` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `targetid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`persistence_object_identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `typo3_typo3cr_domain_model_nodedata`
--

DROP TABLE IF EXISTS `typo3_typo3cr_domain_model_nodedata`;
CREATE TABLE IF NOT EXISTS `typo3_typo3cr_domain_model_nodedata` (
  `persistence_object_identifier` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `workspace` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contentobjectproxy` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `path` varchar(4000) COLLATE utf8_unicode_ci NOT NULL,
  `identifier` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sortingindex` int(11) DEFAULT NULL,
  `properties` longblob NOT NULL COMMENT '(DC2Type:objectarray)',
  `nodetype` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `removed` tinyint(1) NOT NULL,
  `hidden` tinyint(1) NOT NULL,
  `hiddenbeforedatetime` datetime DEFAULT NULL,
  `hiddenafterdatetime` datetime DEFAULT NULL,
  `hiddeninindex` tinyint(1) NOT NULL,
  `accessroles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `version` int(11) NOT NULL DEFAULT '1',
  `parentpath` varchar(4000) COLLATE utf8_unicode_ci NOT NULL,
  `pathhash` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `dimensionshash` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `dimensionvalues` longblob NOT NULL COMMENT '(DC2Type:objectarray)',
  `parentpathhash` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `movedto` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`persistence_object_identifier`),
  UNIQUE KEY `UNIQ_60A956B92DBEC7578D94001992F8FB01` (`pathhash`,`workspace`,`dimensionshash`),
  UNIQUE KEY `UNIQ_60A956B9772E836A8D94001992F8FB012D45FE4D` (`identifier`,`workspace`,`dimensionshash`,`movedto`),
  KEY `IDX_820CADC88D940019` (`workspace`),
  KEY `IDX_820CADC84930C33C` (`contentobjectproxy`),
  KEY `parentpath_sortingindex` (`parentpathhash`,`sortingindex`),
  KEY `identifierindex` (`identifier`),
  KEY `nodetypeindex` (`nodetype`),
  KEY `IDX_60A956B92D45FE4D` (`movedto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `typo3_typo3cr_domain_model_nodedata`
--

INSERT INTO `typo3_typo3cr_domain_model_nodedata` (`persistence_object_identifier`, `workspace`, `contentobjectproxy`, `path`, `identifier`, `sortingindex`, `properties`, `nodetype`, `removed`, `hidden`, `hiddenbeforedatetime`, `hiddenafterdatetime`, `hiddeninindex`, `accessroles`, `version`, `parentpath`, `pathhash`, `dimensionshash`, `dimensionvalues`, `parentpathhash`, `movedto`) VALUES
('038235e5-13de-9c40-a98e-5bae339d113c', 'live', NULL, '/sites/tilleuls', 'ec6213d9-4651-332e-f267-c45135a12789', 100, 0x613a323a7b733a353a227469746c65223b733a343a22486f6d65223b733a31343a22757269506174685365676d656e74223b733a343a22686f6d65223b7d, 'TYPO3.Neos.NodeTypes:Page', 0, 0, NULL, NULL, 0, 'a:0:{}', 1, '/sites', '65032f929c7109f29bf1c9d9c7df4e6d', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, 'dbd87ae51cbf5240fea77283585e69d5', NULL),
('0cd30cf0-103a-6b67-6dfa-3bc98aaebe64', 'live', NULL, '/sites/tilleuls/node-55148bfbf297c/main', 'b7c7c43a-3ae1-4aa3-2446-6a79ee1c1eb5', 100, 0x613a303a7b7d, 'TYPO3.Neos:ContentCollection', 0, 0, NULL, NULL, 0, 'a:0:{}', 3, '/sites/tilleuls/node-55148bfbf297c', '6a1a04baed28f7d9b1777780fd63a11b', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, '7bb187e09d33f2b06dc86dabd42618e7', NULL),
('1334e608-4f7d-c502-7ddd-1585579d01a2', 'live', NULL, '/sites/tilleuls/node-55148bcc570dc/teaser', '69cc52d0-7f51-5427-e7f2-45293013f4b2', 200, 0x613a303a7b7d, 'TYPO3.Neos:ContentCollection', 0, 0, NULL, NULL, 0, 'a:0:{}', 3, '/sites/tilleuls/node-55148bcc570dc', '9b99920fd76c51359de91101b5c6a1cd', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, 'c53b965c9117bb7a4fbcf87cdad4d11e', NULL),
('183dd2b4-bf2a-f4b3-fa17-c0537315f843', 'live', NULL, '/sites/tilleuls/node-55148c2fa49d8', '075f1f50-7f95-c291-10ef-5752097d920f', 550, 0x613a323a7b733a31343a22757269506174685365676d656e74223b733a353a224163747573223b733a353a227469746c65223b733a353a224163747573223b7d, 'TYPO3.Neos.NodeTypes:Page', 0, 0, NULL, NULL, 0, 'a:0:{}', 4, '/sites/tilleuls', '526485c0802709a76f676f581464d32a', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, '65032f929c7109f29bf1c9d9c7df4e6d', NULL),
('2fd710d5-3840-b3a7-a7a5-46f9038f8068', 'live', NULL, '/sites/tilleuls/node-55148c1fc5ade/main', 'dc9a38dc-2119-191e-872d-711486b21bdf', 100, 0x613a303a7b7d, 'TYPO3.Neos:ContentCollection', 0, 0, NULL, NULL, 0, 'a:0:{}', 3, '/sites/tilleuls/node-55148c1fc5ade', '936a8b5ee74dd5785786e0a825ba667f', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, 'ea28f4489fde258f002a0a76a7d5ada0', NULL),
('3c60f8d8-2563-e40e-68ac-ed42bef9fe3c', 'live', NULL, '/sites/tilleuls/node-55148c1fc5ade', '6b955393-0a36-ccd9-1c26-dc93ad8538cc', 500, 0x613a323a7b733a31343a22757269506174685365676d656e74223b733a31313a2254656d6f69676e61676573223b733a353a227469746c65223b733a31323a2254c3a96d6f69676e61676573223b7d, 'TYPO3.Neos.NodeTypes:Page', 0, 0, NULL, NULL, 0, 'a:0:{}', 4, '/sites/tilleuls', 'ea28f4489fde258f002a0a76a7d5ada0', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, '65032f929c7109f29bf1c9d9c7df4e6d', NULL),
('3ff4ed43-8d43-300a-99b2-d65784c7516f', 'live', NULL, '/', 'd0ff6381-1050-8bd5-30f9-706a32355438', NULL, 0x613a303a7b7d, 'unstructured', 0, 0, NULL, NULL, 0, 'a:0:{}', 2, '', '6666cd76f96956469e7be39d750cc7d9', 'd751713988987e9331980363e24189ce', 0x613a303a7b7d, 'd41d8cd98f00b204e9800998ecf8427e', NULL),
('40fe6b70-f8b9-dd7e-0a15-22469184c0f6', 'live', NULL, '/sites/tilleuls/node-55148c3abe80f', '1b848da2-afab-241c-f576-4e424c84fa5b', 600, 0x613a323a7b733a31343a22757269506174685365676d656e74223b733a31313a22526563727574656d656e74223b733a353a227469746c65223b733a31313a22526563727574656d656e74223b7d, 'TYPO3.Neos.NodeTypes:Page', 0, 0, NULL, NULL, 0, 'a:0:{}', 4, '/sites/tilleuls', '582536536511bf66ff1cb9c74bae904c', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, '65032f929c7109f29bf1c9d9c7df4e6d', NULL),
('46088f48-02be-0822-8cbc-84c8e3e52e0f', 'live', NULL, '/sites/tilleuls/node-55148bc17270c', 'b2523bc9-7e21-665a-a86c-beb2dacf185c', 200, 0x613a323a7b733a31343a22757269506174685365676d656e74223b733a383a225365727669636573223b733a353a227469746c65223b733a383a225365727669636573223b7d, 'TYPO3.Neos.NodeTypes:Page', 0, 0, NULL, NULL, 0, 'a:0:{}', 4, '/sites/tilleuls', '661923daa933d825b43fdc6532b8dd9e', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, '65032f929c7109f29bf1c9d9c7df4e6d', NULL),
('46bf5b04-1077-1678-466b-a06981b06925', 'live', NULL, '/sites/tilleuls/node-55148bc17270c/main', '6083b2fe-8116-d8b3-21f3-b7b39c7c9220', 100, 0x613a303a7b7d, 'TYPO3.Neos:ContentCollection', 0, 0, NULL, NULL, 0, 'a:0:{}', 3, '/sites/tilleuls/node-55148bc17270c', '63f7dbc0c8eab198fd4e11fbc80cfd76', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, '661923daa933d825b43fdc6532b8dd9e', NULL),
('54dc100f-472e-4d45-afe7-d91313097970', 'live', NULL, '/sites/tilleuls/node-55148bd683bce/main', '376ca7aa-49b4-e2cd-acb5-06517b8df943', 100, 0x613a303a7b7d, 'TYPO3.Neos:ContentCollection', 0, 0, NULL, NULL, 0, 'a:0:{}', 3, '/sites/tilleuls/node-55148bd683bce', '67bc8ef45f3a2c115e324faf1d903eb7', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, '49d65b46f6f72e351db7fdca89773045', NULL),
('6afb8219-fa1a-4977-9468-dfa19b6bfcf9', 'live', NULL, '/sites/tilleuls/node-55148c3abe80f/teaser', 'b3ca8b47-573e-d8cd-45cc-978e9c44dc76', 200, 0x613a303a7b7d, 'TYPO3.Neos:ContentCollection', 0, 0, NULL, NULL, 0, 'a:0:{}', 3, '/sites/tilleuls/node-55148c3abe80f', 'fe97291417ae37a55ebce7a926b642e7', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, '582536536511bf66ff1cb9c74bae904c', NULL),
('6b403bd6-2dce-1d8a-621f-a9f31f2f2c48', 'live', NULL, '/sites/tilleuls/node-55148bed6ba35/main', '0e7d659c-6296-a02d-bc71-b948380251e9', 100, 0x613a303a7b7d, 'TYPO3.Neos:ContentCollection', 0, 0, NULL, NULL, 0, 'a:0:{}', 3, '/sites/tilleuls/node-55148bed6ba35', '9c05b60372c94dda77378212f6a86174', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, '5e1748fba1db5440a32ec9ad50474b3f', NULL),
('6b6c898c-d9c6-64ca-0dc6-f629f34755ac', 'live', NULL, '/sites/tilleuls/node-55148bc17270c/teaser', 'd162fb43-e297-6e32-cb75-c45a9516ea04', 200, 0x613a303a7b7d, 'TYPO3.Neos:ContentCollection', 0, 0, NULL, NULL, 0, 'a:0:{}', 3, '/sites/tilleuls/node-55148bc17270c', '584af006253c7d046fcd71f96f4470a8', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, '661923daa933d825b43fdc6532b8dd9e', NULL),
('6e4db79b-4061-0581-48c5-443bcb2f642a', 'live', NULL, '/sites/tilleuls/node-55148c45cfbfe/teaser', 'f3625d47-cd71-7892-ad1f-b1c7bbd966ce', 200, 0x613a303a7b7d, 'TYPO3.Neos:ContentCollection', 0, 0, NULL, NULL, 0, 'a:0:{}', 3, '/sites/tilleuls/node-55148c45cfbfe', '7cc2a99cf2d8252662d4bf8151700ac9', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, 'efb5bcfc93330f4d851b9e02418afa20', NULL),
('840f5165-53b5-cf9b-f5b4-490a03d430e4', 'live', NULL, '/sites/tilleuls/node-55148c2fa49d8/main', '22fb5f8b-5db4-f2d5-49d7-f878693919f9', 100, 0x613a303a7b7d, 'TYPO3.Neos:ContentCollection', 0, 0, NULL, NULL, 0, 'a:0:{}', 3, '/sites/tilleuls/node-55148c2fa49d8', '9417dcc5916143630a827c85a20cca95', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, '526485c0802709a76f676f581464d32a', NULL),
('96452f1a-24e8-a14c-6aa9-d207621f0032', 'live', NULL, '/sites/tilleuls/node-55148c2fa49d8/teaser', '0f92385f-8ac4-4dbb-2ad2-efcfea236b1d', 200, 0x613a303a7b7d, 'TYPO3.Neos:ContentCollection', 0, 0, NULL, NULL, 0, 'a:0:{}', 3, '/sites/tilleuls/node-55148c2fa49d8', '15cff4ac2fe360c63c75ff8f041a6a2d', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, '526485c0802709a76f676f581464d32a', NULL),
('9b77629a-b198-b1c6-8768-0d5076ca2c4e', 'live', NULL, '/sites/tilleuls/node-55148bed6ba35', '4c752391-3652-c163-ff45-ff55bb133119', 350, 0x613a323a7b733a31343a22757269506174685365676d656e74223b733a363a22457175697065223b733a353a227469746c65223b733a363a22457175697065223b7d, 'TYPO3.Neos.NodeTypes:Page', 0, 0, NULL, NULL, 0, 'a:0:{}', 4, '/sites/tilleuls', '5e1748fba1db5440a32ec9ad50474b3f', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, '65032f929c7109f29bf1c9d9c7df4e6d', NULL),
('9d6f0723-eaf1-3973-d1b8-6beb0217b60e', 'user-gcopin', NULL, '/', '52b2fbdd-28be-81f3-be3d-9accd48a611a', NULL, 0x613a303a7b7d, 'unstructured', 0, 0, NULL, NULL, 0, 'a:0:{}', 2, '', '6666cd76f96956469e7be39d750cc7d9', 'd751713988987e9331980363e24189ce', 0x613a303a7b7d, 'd41d8cd98f00b204e9800998ecf8427e', NULL),
('a3f17db8-de66-db0f-829c-add096265289', 'live', NULL, '/sites/tilleuls/node-55148bcc570dc', 'b6ae68ff-4f7e-bf99-f0c6-02d59ab1be70', 250, 0x613a323a7b733a31343a22757269506174685365676d656e74223b733a31303a22466f726d6174696f6e73223b733a353a227469746c65223b733a31303a22466f726d6174696f6e73223b7d, 'TYPO3.Neos.NodeTypes:Page', 0, 0, NULL, NULL, 0, 'a:0:{}', 4, '/sites/tilleuls', 'c53b965c9117bb7a4fbcf87cdad4d11e', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, '65032f929c7109f29bf1c9d9c7df4e6d', NULL),
('a79b0499-6906-8940-cc33-1532b9cde297', 'live', NULL, '/sites/tilleuls/node-55148c3abe80f/main', '64c11bc2-9044-8647-c7c2-c9f6308c1bf1', 100, 0x613a303a7b7d, 'TYPO3.Neos:ContentCollection', 0, 0, NULL, NULL, 0, 'a:0:{}', 3, '/sites/tilleuls/node-55148c3abe80f', '27162ddc21ca5b930bd6b933a68bd7ed', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, '582536536511bf66ff1cb9c74bae904c', NULL),
('a8550d44-9a5a-62cd-11a9-f3020e4558a4', 'live', NULL, '/sites/tilleuls/node-55148bed6ba35/teaser', 'ce073724-b775-df6e-9e6c-5880abcd91ef', 200, 0x613a303a7b7d, 'TYPO3.Neos:ContentCollection', 0, 0, NULL, NULL, 0, 'a:0:{}', 3, '/sites/tilleuls/node-55148bed6ba35', 'b1359dac3f8eb51b8c94183bcfa141f6', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, '5e1748fba1db5440a32ec9ad50474b3f', NULL),
('b04dc0c6-a83f-93f3-212d-37dc978c83cf', 'live', NULL, '/sites/tilleuls/node-55148c08af1e7', '0deffbbf-52ab-ccb9-f31e-d2d66bf56c64', 450, 0x613a323a7b733a31343a22757269506174685365676d656e74223b733a31333a2250726f6a65742d736f6369616c223b733a353a227469746c65223b733a31333a2250726f6a657420736f6369616c223b7d, 'TYPO3.Neos.NodeTypes:Page', 0, 0, NULL, NULL, 0, 'a:0:{}', 4, '/sites/tilleuls', 'ae731cd8046943ed989a60aaa87eaeee', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, '65032f929c7109f29bf1c9d9c7df4e6d', NULL),
('b233116a-551d-8bb5-281e-df1e7e0ee978', 'live', NULL, '/sites/tilleuls/node-55148c1fc5ade/teaser', 'b96b2589-7624-3d30-ac71-82766a6abb2c', 200, 0x613a303a7b7d, 'TYPO3.Neos:ContentCollection', 0, 0, NULL, NULL, 0, 'a:0:{}', 3, '/sites/tilleuls/node-55148c1fc5ade', '5c5c31c0cedba2019829e6273a14032c', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, 'ea28f4489fde258f002a0a76a7d5ada0', NULL),
('be6e9b14-8251-0c46-4368-7e57ca0f90d9', 'live', NULL, '/sites/tilleuls/node-55148c08af1e7/teaser', '0ded6df5-bd26-e51d-cfc3-48bd26d01a28', 200, 0x613a303a7b7d, 'TYPO3.Neos:ContentCollection', 0, 0, NULL, NULL, 0, 'a:0:{}', 3, '/sites/tilleuls/node-55148c08af1e7', 'd7b475e6280ef42bde3b0cd2284fa148', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, 'ae731cd8046943ed989a60aaa87eaeee', NULL),
('c033046d-d78a-9dab-8106-36f8df76be07', 'live', NULL, '/sites/tilleuls/node-55148c08af1e7/main', 'ab16bc9c-2b4f-bb8b-d979-4284c462537b', 100, 0x613a303a7b7d, 'TYPO3.Neos:ContentCollection', 0, 0, NULL, NULL, 0, 'a:0:{}', 3, '/sites/tilleuls/node-55148c08af1e7', '95648521a95be14fc81c24f6f8ac13ce', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, 'ae731cd8046943ed989a60aaa87eaeee', NULL),
('c6af1080-bd64-9e4c-c386-bc25389cc078', 'live', NULL, '/sites/tilleuls/node-55148bd683bce/teaser', 'de6750a4-edcc-9c7f-4e67-63163cb4b287', 200, 0x613a303a7b7d, 'TYPO3.Neos:ContentCollection', 0, 0, NULL, NULL, 0, 'a:0:{}', 3, '/sites/tilleuls/node-55148bd683bce', '0915b1d2a03d1c516757c3429791d49a', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, '49d65b46f6f72e351db7fdca89773045', NULL),
('c874a082-709c-589a-5ca4-930f78b8067d', 'live', NULL, '/sites/tilleuls/node-55148bfbf297c', '77cdae8b-cf9a-eca6-81b5-b149b513c3d2', 400, 0x613a323a7b733a31343a22757269506174685365676d656e74223b733a373a22436c69656e7473223b733a353a227469746c65223b733a373a22436c69656e7473223b7d, 'TYPO3.Neos.NodeTypes:Page', 0, 0, NULL, NULL, 0, 'a:0:{}', 4, '/sites/tilleuls', '7bb187e09d33f2b06dc86dabd42618e7', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, '65032f929c7109f29bf1c9d9c7df4e6d', NULL),
('d183006b-3531-322b-cc52-6e6efd195249', 'live', NULL, '/sites/tilleuls/node-55148c45cfbfe/main', '6f95b140-2267-afdd-0afd-99a241d36a84', 100, 0x613a303a7b7d, 'TYPO3.Neos:ContentCollection', 0, 0, NULL, NULL, 0, 'a:0:{}', 3, '/sites/tilleuls/node-55148c45cfbfe', '375c28e8d50a045eb864d222b8f47c9e', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, 'efb5bcfc93330f4d851b9e02418afa20', NULL),
('d1f94e11-472c-ba4b-1648-37cacdb1dbee', 'live', NULL, '/sites/tilleuls/main', '2f6fc9a4-c258-b69c-ffe1-b64ceea14fb4', 100, 0x613a303a7b7d, 'TYPO3.Neos:ContentCollection', 0, 0, NULL, NULL, 0, 'a:0:{}', 1, '/sites/tilleuls', '28ff7bcca8302ec6e57b3f75c0a5ffd0', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, '65032f929c7109f29bf1c9d9c7df4e6d', NULL),
('d69da431-f645-6583-e683-5b1ed93cbde6', 'live', NULL, '/sites/tilleuls/node-55148c45cfbfe', 'f20a6505-43d3-bc08-7d57-45b2d3996ada', 650, 0x613a323a7b733a31343a22757269506174685365676d656e74223b733a373a22436f6e74616374223b733a353a227469746c65223b733a373a22436f6e74616374223b7d, 'TYPO3.Neos.NodeTypes:Page', 0, 0, NULL, NULL, 0, 'a:0:{}', 4, '/sites/tilleuls', 'efb5bcfc93330f4d851b9e02418afa20', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, '65032f929c7109f29bf1c9d9c7df4e6d', NULL),
('d8636c52-7afe-066c-3ab7-6070d27046b8', 'live', NULL, '/sites', 'ea896fc6-690e-d78b-835c-a2e06431b394', 100, 0x613a303a7b7d, 'unstructured', 0, 0, NULL, NULL, 0, 'a:0:{}', 1, '/', 'dbd87ae51cbf5240fea77283585e69d5', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, '6666cd76f96956469e7be39d750cc7d9', NULL),
('e859b3a9-e651-c1d3-82b4-e8384b69823b', 'live', NULL, '/sites/tilleuls/node-55148bd683bce', '4e65bcca-68d0-fcb7-67d7-d28f6f5f587f', 300, 0x613a323a7b733a31343a22757269506174685365676d656e74223b733a383a2250726f6475697473223b733a353a227469746c65223b733a383a2250726f6475697473223b7d, 'TYPO3.Neos.NodeTypes:Page', 0, 0, NULL, NULL, 0, 'a:0:{}', 4, '/sites/tilleuls', '49d65b46f6f72e351db7fdca89773045', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, '65032f929c7109f29bf1c9d9c7df4e6d', NULL),
('e8ac5c7a-ff49-f3f9-3227-448bf8f16666', 'live', NULL, '/sites/tilleuls/node-55148bfbf297c/teaser', '8fea05bb-e464-3af6-caf8-a822394aa47e', 200, 0x613a303a7b7d, 'TYPO3.Neos:ContentCollection', 0, 0, NULL, NULL, 0, 'a:0:{}', 3, '/sites/tilleuls/node-55148bfbf297c', '8b9b30ebfeb765ddcdc248bc02e5174b', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, '7bb187e09d33f2b06dc86dabd42618e7', NULL),
('e8d64d5d-2983-dfc3-9806-82aa7ea9c47e', 'live', NULL, '/sites/tilleuls/main/text1', 'fbcdd86f-874c-b02c-4fea-edb1abc838db', 100, 0x613a313a7b733a343a2274657874223b733a32373a223c703e546869732069732074686520686f6d65706167653c2f703e223b7d, 'TYPO3.Neos.NodeTypes:Text', 0, 0, NULL, NULL, 0, 'a:0:{}', 1, '/sites/tilleuls/main', '54363c262b2b5c1e4baac8b0b4b4ad7f', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, '28ff7bcca8302ec6e57b3f75c0a5ffd0', NULL),
('e9758238-2b5e-3679-c674-78fcff963a44', 'live', NULL, '/sites/tilleuls/node-55148bcc570dc/main', 'cf7303fb-2a7e-b5ea-b723-f55781fe40a4', 100, 0x613a303a7b7d, 'TYPO3.Neos:ContentCollection', 0, 0, NULL, NULL, 0, 'a:0:{}', 3, '/sites/tilleuls/node-55148bcc570dc', 'ff357513943ee23fab1026cf37ef8572', '4f534b1eb0c1a785da31e681fb5e91ff', 0x613a313a7b733a383a226c616e6775616765223b613a313a7b693a303b733a353a22656e5f5553223b7d7d, 'c53b965c9117bb7a4fbcf87cdad4d11e', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `typo3_typo3cr_domain_model_nodedimension`
--

DROP TABLE IF EXISTS `typo3_typo3cr_domain_model_nodedimension`;
CREATE TABLE IF NOT EXISTS `typo3_typo3cr_domain_model_nodedimension` (
  `persistence_object_identifier` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `nodedata` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`persistence_object_identifier`),
  UNIQUE KEY `UNIQ_6C144D3693BDC8E25E237E061D775834` (`nodedata`,`name`,`value`),
  KEY `IDX_6C144D3693BDC8E2` (`nodedata`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `typo3_typo3cr_domain_model_nodedimension`
--

INSERT INTO `typo3_typo3cr_domain_model_nodedimension` (`persistence_object_identifier`, `nodedata`, `name`, `value`) VALUES
('ad5d72e9-48fd-c5e0-90f2-4bc6d827a2e4', '038235e5-13de-9c40-a98e-5bae339d113c', 'language', 'en_US'),
('96f6b5da-6f08-06f0-a812-4a95f6619162', '0cd30cf0-103a-6b67-6dfa-3bc98aaebe64', 'language', 'en_US'),
('0fa8e0d0-9809-1b49-a6be-e336f39428de', '1334e608-4f7d-c502-7ddd-1585579d01a2', 'language', 'en_US'),
('8157ed7f-7d25-13b2-baab-51204b1b7ff2', '183dd2b4-bf2a-f4b3-fa17-c0537315f843', 'language', 'en_US'),
('5120d18d-0fd4-e7e5-fd56-8d60e0ff2897', '2fd710d5-3840-b3a7-a7a5-46f9038f8068', 'language', 'en_US'),
('f0e4842b-140f-c0d7-3d3a-608cb3fda2b4', '3c60f8d8-2563-e40e-68ac-ed42bef9fe3c', 'language', 'en_US'),
('7c1e1a32-71f8-6245-860b-1b6b9c144dca', '40fe6b70-f8b9-dd7e-0a15-22469184c0f6', 'language', 'en_US'),
('55a724a3-899f-b325-99da-9fda83759b10', '46088f48-02be-0822-8cbc-84c8e3e52e0f', 'language', 'en_US'),
('544d3eca-b102-afe2-7eb9-fed9a2b292fb', '46bf5b04-1077-1678-466b-a06981b06925', 'language', 'en_US'),
('88863086-65d4-d2af-ac40-f766056a8f63', '54dc100f-472e-4d45-afe7-d91313097970', 'language', 'en_US'),
('cef893db-ba62-b6bb-a334-6fb790fb6fc7', '6afb8219-fa1a-4977-9468-dfa19b6bfcf9', 'language', 'en_US'),
('b8017165-6db0-c304-cfd8-1208968e91c7', '6b403bd6-2dce-1d8a-621f-a9f31f2f2c48', 'language', 'en_US'),
('8c5dc7a7-2b78-624a-54d9-0b02a62ee7a4', '6b6c898c-d9c6-64ca-0dc6-f629f34755ac', 'language', 'en_US'),
('7236a8c2-58f9-d19c-38ef-bec69222edbc', '6e4db79b-4061-0581-48c5-443bcb2f642a', 'language', 'en_US'),
('78995e36-8789-c3b4-ad29-b0b9d17e1f5a', '840f5165-53b5-cf9b-f5b4-490a03d430e4', 'language', 'en_US'),
('a94163f4-23d8-d218-a78d-e83d2ab415db', '96452f1a-24e8-a14c-6aa9-d207621f0032', 'language', 'en_US'),
('eab79eb6-62b4-43a9-1044-cc74ca108d16', '9b77629a-b198-b1c6-8768-0d5076ca2c4e', 'language', 'en_US'),
('bc73a47d-6cc7-14d4-e2bb-c1fa0f00e9cd', 'a3f17db8-de66-db0f-829c-add096265289', 'language', 'en_US'),
('d10a078c-e6bd-c24c-9b4f-9c188a0ea90c', 'a79b0499-6906-8940-cc33-1532b9cde297', 'language', 'en_US'),
('3a1da442-72ee-06d3-e2db-c0697f8f9ecb', 'a8550d44-9a5a-62cd-11a9-f3020e4558a4', 'language', 'en_US'),
('614af0de-1037-258d-dc70-23fe60579773', 'b04dc0c6-a83f-93f3-212d-37dc978c83cf', 'language', 'en_US'),
('6c8fb87b-a1fd-904e-7a32-1df352248fc8', 'b233116a-551d-8bb5-281e-df1e7e0ee978', 'language', 'en_US'),
('81c5ee1b-92dc-a9b7-f8db-9b91033a6c56', 'be6e9b14-8251-0c46-4368-7e57ca0f90d9', 'language', 'en_US'),
('7ff9dd80-87eb-4f45-1e8d-d7ab4dc690be', 'c033046d-d78a-9dab-8106-36f8df76be07', 'language', 'en_US'),
('f51906cd-ff3b-0d17-a4b9-589ac99e7f83', 'c6af1080-bd64-9e4c-c386-bc25389cc078', 'language', 'en_US'),
('ab0a4d89-d0b1-14fe-fcb1-8fb45b92acd5', 'c874a082-709c-589a-5ca4-930f78b8067d', 'language', 'en_US'),
('cb5baa53-c466-0f57-04ed-ae6417b84def', 'd183006b-3531-322b-cc52-6e6efd195249', 'language', 'en_US'),
('41298948-a599-fc75-0e31-9297a774b3ae', 'd1f94e11-472c-ba4b-1648-37cacdb1dbee', 'language', 'en_US'),
('c0dbe94a-0c10-7832-d743-ea5cea1d16c5', 'd69da431-f645-6583-e683-5b1ed93cbde6', 'language', 'en_US'),
('a9ce4941-ba94-8248-d961-472489610080', 'd8636c52-7afe-066c-3ab7-6070d27046b8', 'language', 'en_US'),
('0ed28078-c3bf-ba6c-a5b9-1330b087b4e6', 'e859b3a9-e651-c1d3-82b4-e8384b69823b', 'language', 'en_US'),
('e18a7ff9-8a7c-c072-309f-1c62bece0f15', 'e8ac5c7a-ff49-f3f9-3227-448bf8f16666', 'language', 'en_US'),
('64c7736d-a80d-8e63-ccc2-d9c8ef04790c', 'e8d64d5d-2983-dfc3-9806-82aa7ea9c47e', 'language', 'en_US'),
('22fc3f79-eef9-9dbb-15a0-4fa2275688bc', 'e9758238-2b5e-3679-c674-78fcff963a44', 'language', 'en_US');

-- --------------------------------------------------------

--
-- Structure de la table `typo3_typo3cr_domain_model_workspace`
--

DROP TABLE IF EXISTS `typo3_typo3cr_domain_model_workspace`;
CREATE TABLE IF NOT EXISTS `typo3_typo3cr_domain_model_workspace` (
  `baseworkspace` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rootnodedata` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`name`),
  KEY `IDX_71DE9CFBE9BFE681` (`baseworkspace`),
  KEY `IDX_71DE9CFBBB46155` (`rootnodedata`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `typo3_typo3cr_domain_model_workspace`
--

INSERT INTO `typo3_typo3cr_domain_model_workspace` (`baseworkspace`, `rootnodedata`, `name`) VALUES
(NULL, '3ff4ed43-8d43-300a-99b2-d65784c7516f', 'live'),
('live', '9d6f0723-eaf1-3973-d1b8-6beb0217b60e', 'user-gcopin');

-- --------------------------------------------------------

--
-- Structure de la table `typo3_typo3cr_migration_domain_model_migrationstatus`
--

DROP TABLE IF EXISTS `typo3_typo3cr_migration_domain_model_migrationstatus`;
CREATE TABLE IF NOT EXISTS `typo3_typo3cr_migration_domain_model_migrationstatus` (
  `persistence_object_identifier` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `version` varchar(14) COLLATE utf8_unicode_ci NOT NULL,
  `direction` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `applicationtimestamp` datetime NOT NULL,
  PRIMARY KEY (`persistence_object_identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `typo3_flow_resource_resource`
--
ALTER TABLE `typo3_flow_resource_resource`
  ADD CONSTRAINT `FK_B4D45B32A4A851AF` FOREIGN KEY (`publishingconfiguration`) REFERENCES `typo3_flow_resource_publishing_abstractpublishingconfiguration` (`persistence_object_identifier`),
  ADD CONSTRAINT `typo3_flow_resource_resource_ibfk_1` FOREIGN KEY (`resourcepointer`) REFERENCES `typo3_flow_resource_resourcepointer` (`hash`);

--
-- Contraintes pour la table `typo3_flow_security_account`
--
ALTER TABLE `typo3_flow_security_account`
  ADD CONSTRAINT `typo3_flow_security_account_ibfk_1` FOREIGN KEY (`party`) REFERENCES `typo3_party_domain_model_abstractparty` (`persistence_object_identifier`);

--
-- Contraintes pour la table `typo3_flow_security_account_roles_join`
--
ALTER TABLE `typo3_flow_security_account_roles_join`
  ADD CONSTRAINT `FK_ADF11BBC23A1047C` FOREIGN KEY (`flow_policy_role`) REFERENCES `typo3_flow_security_policy_role` (`identifier`),
  ADD CONSTRAINT `FK_ADF11BBC58842EFC` FOREIGN KEY (`flow_security_account`) REFERENCES `typo3_flow_security_account` (`persistence_object_identifier`);

--
-- Contraintes pour la table `typo3_flow_security_authorization_resource_securitypublis_861cb`
--
ALTER TABLE `typo3_flow_security_authorization_resource_securitypublis_861cb`
  ADD CONSTRAINT `FK_234846D521E3D446` FOREIGN KEY (`persistence_object_identifier`) REFERENCES `typo3_flow_resource_publishing_abstractpublishingconfiguration` (`persistence_object_identifier`) ON DELETE CASCADE;

--
-- Contraintes pour la table `typo3_flow_security_policy_role_parentroles_join`
--
ALTER TABLE `typo3_flow_security_policy_role_parentroles_join`
  ADD CONSTRAINT `FK_D459C58E6A8ABCDE` FOREIGN KEY (`parent_role`) REFERENCES `typo3_flow_security_policy_role` (`identifier`),
  ADD CONSTRAINT `FK_D459C58E23A1047C` FOREIGN KEY (`flow_policy_role`) REFERENCES `typo3_flow_security_policy_role` (`identifier`);

--
-- Contraintes pour la table `typo3_media_domain_model_asset`
--
ALTER TABLE `typo3_media_domain_model_asset`
  ADD CONSTRAINT `FK_B8306B8EBC91F416` FOREIGN KEY (`resource`) REFERENCES `typo3_flow_resource_resource` (`persistence_object_identifier`);

--
-- Contraintes pour la table `typo3_media_domain_model_asset_tags_join`
--
ALTER TABLE `typo3_media_domain_model_asset_tags_join`
  ADD CONSTRAINT `FK_DAF7A1EB48D8C57E` FOREIGN KEY (`media_tag`) REFERENCES `typo3_media_domain_model_tag` (`persistence_object_identifier`),
  ADD CONSTRAINT `FK_DAF7A1EB1DB69EED` FOREIGN KEY (`media_asset`) REFERENCES `typo3_media_domain_model_asset` (`persistence_object_identifier`);

--
-- Contraintes pour la table `typo3_media_domain_model_audio`
--
ALTER TABLE `typo3_media_domain_model_audio`
  ADD CONSTRAINT `FK_A2E2074747A46B0A` FOREIGN KEY (`persistence_object_identifier`) REFERENCES `typo3_media_domain_model_asset` (`persistence_object_identifier`) ON DELETE CASCADE;

--
-- Contraintes pour la table `typo3_media_domain_model_document`
--
ALTER TABLE `typo3_media_domain_model_document`
  ADD CONSTRAINT `FK_F089E2F547A46B0A` FOREIGN KEY (`persistence_object_identifier`) REFERENCES `typo3_media_domain_model_asset` (`persistence_object_identifier`) ON DELETE CASCADE;

--
-- Contraintes pour la table `typo3_media_domain_model_image`
--
ALTER TABLE `typo3_media_domain_model_image`
  ADD CONSTRAINT `FK_7FA2358D47A46B0A` FOREIGN KEY (`persistence_object_identifier`) REFERENCES `typo3_media_domain_model_asset` (`persistence_object_identifier`) ON DELETE CASCADE;

--
-- Contraintes pour la table `typo3_media_domain_model_video`
--
ALTER TABLE `typo3_media_domain_model_video`
  ADD CONSTRAINT `FK_C658EBFE47A46B0A` FOREIGN KEY (`persistence_object_identifier`) REFERENCES `typo3_media_domain_model_asset` (`persistence_object_identifier`) ON DELETE CASCADE;

--
-- Contraintes pour la table `typo3_neos_domain_model_domain`
--
ALTER TABLE `typo3_neos_domain_model_domain`
  ADD CONSTRAINT `typo3_neos_domain_model_domain_ibfk_1` FOREIGN KEY (`site`) REFERENCES `typo3_neos_domain_model_site` (`persistence_object_identifier`);

--
-- Contraintes pour la table `typo3_neos_domain_model_user`
--
ALTER TABLE `typo3_neos_domain_model_user`
  ADD CONSTRAINT `typo3_neos_domain_model_user_ibfk_1` FOREIGN KEY (`preferences`) REFERENCES `typo3_neos_domain_model_userpreferences` (`persistence_object_identifier`),
  ADD CONSTRAINT `typo3_neos_domain_model_user_ibfk_2` FOREIGN KEY (`persistence_object_identifier`) REFERENCES `typo3_party_domain_model_abstractparty` (`persistence_object_identifier`) ON DELETE CASCADE;

--
-- Contraintes pour la table `typo3_party_domain_model_person`
--
ALTER TABLE `typo3_party_domain_model_person`
  ADD CONSTRAINT `typo3_party_domain_model_person_ibfk_3` FOREIGN KEY (`persistence_object_identifier`) REFERENCES `typo3_party_domain_model_abstractparty` (`persistence_object_identifier`) ON DELETE CASCADE,
  ADD CONSTRAINT `typo3_party_domain_model_person_ibfk_1` FOREIGN KEY (`name`) REFERENCES `typo3_party_domain_model_personname` (`persistence_object_identifier`),
  ADD CONSTRAINT `typo3_party_domain_model_person_ibfk_2` FOREIGN KEY (`primaryelectronicaddress`) REFERENCES `typo3_party_domain_model_electronicaddress` (`persistence_object_identifier`);

--
-- Contraintes pour la table `typo3_party_domain_model_person_electronicaddresses_join`
--
ALTER TABLE `typo3_party_domain_model_person_electronicaddresses_join`
  ADD CONSTRAINT `typo3_party_domain_model_person_electronicaddresses_join_ibfk_1` FOREIGN KEY (`party_person`) REFERENCES `typo3_party_domain_model_person` (`persistence_object_identifier`),
  ADD CONSTRAINT `typo3_party_domain_model_person_electronicaddresses_join_ibfk_2` FOREIGN KEY (`party_electronicaddress`) REFERENCES `typo3_party_domain_model_electronicaddress` (`persistence_object_identifier`);

--
-- Contraintes pour la table `typo3_typo3cr_domain_model_nodedata`
--
ALTER TABLE `typo3_typo3cr_domain_model_nodedata`
  ADD CONSTRAINT `FK_60A956B92D45FE4D` FOREIGN KEY (`movedto`) REFERENCES `typo3_typo3cr_domain_model_nodedata` (`persistence_object_identifier`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_60A956B98D940019` FOREIGN KEY (`workspace`) REFERENCES `typo3_typo3cr_domain_model_workspace` (`name`) ON DELETE SET NULL,
  ADD CONSTRAINT `typo3_typo3cr_domain_model_nodedata_ibfk_2` FOREIGN KEY (`contentobjectproxy`) REFERENCES `typo3_typo3cr_domain_model_contentobjectproxy` (`persistence_object_identifier`);

--
-- Contraintes pour la table `typo3_typo3cr_domain_model_nodedimension`
--
ALTER TABLE `typo3_typo3cr_domain_model_nodedimension`
  ADD CONSTRAINT `FK_6C144D3693BDC8E2` FOREIGN KEY (`nodedata`) REFERENCES `typo3_typo3cr_domain_model_nodedata` (`persistence_object_identifier`) ON DELETE CASCADE;

--
-- Contraintes pour la table `typo3_typo3cr_domain_model_workspace`
--
ALTER TABLE `typo3_typo3cr_domain_model_workspace`
  ADD CONSTRAINT `FK_71DE9CFBE9BFE681` FOREIGN KEY (`baseworkspace`) REFERENCES `typo3_typo3cr_domain_model_workspace` (`name`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_71DE9CFBBB46155` FOREIGN KEY (`rootnodedata`) REFERENCES `typo3_typo3cr_domain_model_nodedata` (`persistence_object_identifier`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
