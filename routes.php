<?php

include 'classes/Route.php';

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



