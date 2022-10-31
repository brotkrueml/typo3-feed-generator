<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Feed Generator',
    'description' => 'Generator for Atom, JSON and RSS feeds',
    'category' => 'fe',
    'author' => 'Chris Müller',
    'author_email' => 'typo3@krue.ml',
    'state' => 'alpha',
    'version' => '0.4.0',
    'constraints' => [
        'depends' => [
            'php' => '8.1.0-0.0.0',
            'typo3' => '11.5.0-11.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'psr-4' => ['Brotkrueml\\FeedGenerator\\' => 'Classes']
    ],
];
