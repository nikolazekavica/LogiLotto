<?php

include "api.lotto.com/controllers/LottoController.php";

$logilottoengine = new LottoController();

do {
    $logilottoengine->randomGenerateSevenNumbers();
    $logilottoengine->storeDrawnNumbers();
    $logilottoengine->doSettlement();
    $logilottoengine->sleep();
} while (true);

