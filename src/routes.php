<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    $conn = $this->dbconn;
    $params = array();
    $tsql = "SELECT TOP 20 c.FirstName as FirstName, c.MiddleName as MiddleName, c.LastName as LastName FROM SalesLT.Customer c ";
    if (isset($args['name'])) {
        $tsql .= "WHERE c.FirstName LIKE '%' + ? + '%' OR c.MiddleName LIKE '%' + ? + '%' OR c.LastName LIKE '%' + ? + '%'";
        $params = array($args['name'], $args['name'], $args['name']);
    }
    $getCustomers = $conn->prepare($tsql);
    $getCustomers->execute($params);  
    $customers = $getCustomers->fetchAll(PDO::FETCH_ASSOC);  

    $args['customers'] = $customers;

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
