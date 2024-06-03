<?php

use CodelyTv\CodingStyle;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([
        realpath(__DIR__ . '/../src'),
        realpath(__DIR__ . '/../tests'),
    ]);

    $ecsConfig->sets([CodingStyle::DEFAULT]);

    // Or this if you prefer to have the code aligned
    // $ecsConfig->sets([CodingStyle::ALIGNED]);
};