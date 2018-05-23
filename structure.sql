-- phpMyAdmin SQL Dump
-- version 3.1.5
-- http://www.phpmyadmin.net
--
-- Serveur: melvindiez249.sql.free.fr
-- Généré le : Lun 14 Mai 2018 à 12:38
-- Version du serveur: 5.0.83
-- Version de PHP: 5.3.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `melvindiez249`
--

-- --------------------------------------------------------

--
-- Structure de la table `choix`
--

CREATE TABLE IF NOT EXISTS `choix` (
  `id` int(11) NOT NULL auto_increment,
  `user` int(11) NOT NULL,
  `poste` int(11) NOT NULL,
  `numerotation` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=230 ;

-- --------------------------------------------------------

--
-- Structure de la table `postes`
--

CREATE TABLE IF NOT EXISTS `postes` (
  `id` int(11) NOT NULL auto_increment,
  `poste` varchar(35) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL auto_increment,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `pwd` varchar(90) NOT NULL,
  `email` varchar(50) NOT NULL,
  `classement` tinyint(4) NOT NULL,
  `moyenne` float NOT NULL,
  `has_power` tinyint(1) NOT NULL,
  `sur` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;
