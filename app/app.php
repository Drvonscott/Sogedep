<?php

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

// Register global error and exception handlers
ErrorHandler::register();
ExceptionHandler::register();

// Register service providers.
$app->register(new Silex\Provider\DoctrineServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array('twig.path' => __DIR__.'/../views'));
$app->register(new Silex\Provider\AssetServiceProvider(), array('assets.version' => 'v1'));
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\SecurityServiceProvider(), array
 (
  'security.firewalls' => array
   (
    'secured' => array
     (
      'pattern' => '^/',
      'anonymous' => true,
      'logout' => true,
      'form' => array('login_path' => '/login', 'check_path' => '/login_check'),
      'users' => function () use ($app)
       {
        return new SOGEDEP\DAO\UserDAO($app['db']);
       },
     ),
   ),
 ));

// Register services.
$app['dao.client'] = function ($app)
 {
  return new SOGEDEP\DAO\ClientDAO($app['db']);
 };

$app['dao.dossier'] = function ($app)
 {
  return new SOGEDEP\DAO\DossierDAO($app['db']);
 };

$app['dao.marque'] = function ($app)
 {
  return new SOGEDEP\DAO\MarqueDAO($app['db']);
 };

$app['dao.prestataire'] = function ($app)
 {
  return new SOGEDEP\DAO\PrestataireDAO($app['db']);
 };

$app['dao.user'] = function ($app)
 {
  return new SOGEDEP\DAO\UserDAO($app['db']);
 };

$app['dao.historique'] = function ($app)
 {
  $historiqueDAO = new SOGEDEP\DAO\HistoriqueDAO($app['db']);
  $historiqueDAO->setDossierDAO($app['dao.dossier']);
  $historiqueDAO->setUserDAO($app['dao.user']);
  return $historiqueDAO;
 };