<?php

declare(strict_types=1);

namespace {

    return array_merge(

        require('prooph.php'),

        [
            'settings.displayErrorDetails' => true,
        ]
    );
}
