<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// mssql
$container['dbconn'] = function ($c) {
    $settings = $c->get('settings')['db'];
    // $connectionOptions = array(
    //     "Database" => $settings['dbname'],
    //     "Uid" => $settings['user'],
    //     "PWD" => $settings['pass']
    // );
    // $conn = sqlsrv_connect($settings['host'], $connectionOptions);
    $conn = null;
    try {
        $conn = new PDO("sqlsrv:server = tcp:" . $settings['host'] . ",1433; Database = " . $settings['dbname'], $settings['user'], $settings['pass']);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (PDOException $e) {
        print("Error connecting to SQL Server.");
        die(print_r($e));
    }
    return $conn;
};