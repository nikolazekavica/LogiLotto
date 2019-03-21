<?php

include './api.lotto.com/classes/Route.php';
include './api.lotto.com/controllers/BetController.php';
include './api.lotto.com/controllers/StatusController.php';

Route::set('/', function () {
    BetController::CreateView('Home');
});

Route::set('status', function () {
    StatusController::CreateView('Status');
});

Route::set('create-bet', function () {
    BetController::createBet();
});

Route::set('list-of-bets', function () {
    StatusController::listOfBets();
});



