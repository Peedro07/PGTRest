<?php

return [
    // ...
    // Autres bundles Symfony
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],

    // Ton bundle personnalisé (PGTRestBundle dans cet exemple)
    PGTRest\PGTRestBundle::class => ['all' => true],
    // ...
];
