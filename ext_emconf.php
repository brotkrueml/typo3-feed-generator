<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Feed Generator',
    'description' => 'Generator for Atom, JSON and RSS feeds',
    'category' => 'fe',
    'author' => 'Chris Müller',
    'author_email' => 'typo3@krue.ml',
    'state' => 'alpha',
    'version' => '0.6.0-dev',
    'constraints' => [
        'depends' => [
            'php' => '8.1.0-0.0.0',
            'typo3' => '11.5.0-12.4.99',
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
