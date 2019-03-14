<?php

include "api.lotto.com/LottoController.php";

$logilottoengine = new LottoController();

do {
    $logilottoengine->randomGenerateSevenNumbers();
    $logilottoengine->storeDrawnNumbers();
    $logilottoengine->doSettlement();
    $logilottoengine->sleep();
} while (true);

