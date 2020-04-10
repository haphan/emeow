<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

require 'vendor/autoload.php';

use Symfony\Component\Console\Application;
use Emeow\NetworkManager;
use Emeow\EmailManager;
use Emeow\Command\EmeowCommand;

$app = new Application('emeow', '1.0.0');

$app->add(new EmeowCommand(null, new EmailManager()))
    ->getApplication()
    ->setDefaultCommand('emeow', true)
    ->run();
