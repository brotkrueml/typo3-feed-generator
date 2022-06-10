<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Feed Generator',
    'description' => 'Generator for Atom, JSON and RSS feeds',
    'category' => 'fe',
    'author' => 'Chris Müller',
    'author_email' => 'typo3@krue.ml',
    'state' => 'experimental',
    'version' => '0.2.0',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-11.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'psr-4' => ['Brotkrueml\\FeedGenerator\\' => 'Classes']
    ],
];
