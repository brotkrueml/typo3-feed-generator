<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Feed Generator',
    'description' => 'Generator for Atom, JSON and RSS feeds',
    'category' => 'fe',
    'author' => 'Chris Müller',
    'author_email' => 'typo3@krue.ml',
    'state' => 'beta',
    'version' => '0.7.0-dev',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-13.4.99',
        ],
        'conflicts' => [],
        'suggests' => [
            'lowlevel' => '',
        ],
    ],
    'autoload' => [
        'psr-4' => ['Brotkrueml\\FeedGenerator\\' => 'Classes']
    ],
];
