<?php
require ('./engine/engine.php');
ErrorHandler::setPrintFormat('json');

ResponseHandler::setPrintFormat('json');
ResponseHandler::printResponse(Router::init());