<?php
session_start();

# Require core files
require('vendor/autoload.php');
require("app/libraries/apiutils.php");
require("app/configs/constants.php");
header('Content-Type: application/json');

# Get QueryAuth (authentication) namespace into our scope
use QueryAuth\Credentials\Credentials;
use QueryAuth\Factory;
use QueryAuth\Request\Adapter\Incoming\SlimRequestAdapter;

# Instance Slim
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$utils = new ApiUtils;

# Debug mode on.
$app->config("debug", true);

# Authentication code, decide if to die & return a 401, or a 200 OK.
$factory = new Factory();
$requestValidator = $factory->newRequestValidator();
$credentials = new Credentials('key', 'secret');
$request = $app->request;

# Catch issues in validation. Ignore them, else the app will die, this isn't done yet.
try {
    $isValid = $requestValidator->isValid(new SlimRequestAdapter($request), $credentials);
} catch (Exception $e) {}

# Start routes!
require('app/assets/routes.php'); 

# Run the app, with the middleware and everything.
$app->run();