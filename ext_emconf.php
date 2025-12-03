<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Feed Generator',
    'description' => 'Generator for Atom, JSON and RSS feeds',
    'category' => 'fe',
    'author' => 'Chris Müller',
    'author_email' => 'typo3@brotkrueml.dev',
    'state' => 'beta',
    'version' => '0.8.0',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.0-14.3.99',
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
