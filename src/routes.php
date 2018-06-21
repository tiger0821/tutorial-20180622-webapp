<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // GET parameters
    $getParams = $request->getQueryParams();

    // DB Query
    $conn = $this->dbconn;
    $sqlParams = array();
    $tsql = "SELECT TOP 20 c.FirstName as FirstName, c.MiddleName as MiddleName, c.LastName as LastName FROM SalesLT.Customer c ";
    if (isset($getParams['name'])) {
        $name = $getParams['name'];
        $args['name'] = $name;
        $tsql .= "WHERE c.FirstName LIKE '%' + ? + '%' OR c.MiddleName LIKE '%' + ? + '%' OR c.LastName LIKE '%' + ? + '%'";
        $sqlParams = array($name, $name, $name);
    }
    $getCustomers = $conn->prepare($tsql);
    $getCustomers->execute($sqlParams);
    $customers = $getCustomers->fetchAll(PDO::FETCH_ASSOC);  

    $args['customers'] = $customers;

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
