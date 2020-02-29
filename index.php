<?php

require_once "./config/bootstrap.php";

use Core\Route;
(new Route())->setRoutes($routes)->run();