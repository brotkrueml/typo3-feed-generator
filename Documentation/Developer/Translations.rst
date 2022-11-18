.. include:: /Includes.rst.txt

.. _developer-translations:

============
Translations
============

When configuring a :ref:`feed for different languages <developer-multiple-feeds>`,
it may be convenient to use translations from :file:`locallang.xlf` files. One
possible implementation could be:

.. code-block:: php
   :caption: EXT:your_extension/Classes/Feed/YourFeed.php

   // use TYPO3\CMS\Core\Localization\LanguageService;
   // use TYPO3\CMS\Core\Localization\LanguageServiceFactory;

   #[Feed('/en/your-feed.atom', FeedFormat::ATOM)]
   #[Feed('/de/dein-feed.atom', FeedFormat::ATOM)]
   #[Feed('/nl/je-feed.atom', FeedFormat::ATOM)]
   final class YourFeed implements FeedInterface, RequestAwareInterface
   {
      private ?LanguageService $languageService = null;

      public function __construct(
         private readonly LanguageServiceFactory $languageServiceFactory,
      ) {
      }

      public function getDescription(): string
      {
         // feed.description is defined in your extension's locallang.xlf
         return $this->translate('feed.description');
      }

      public function getTitle(): string
      {
         // feed.title is defined in your extension's locallang.xlf
         return $this->translate('feed.title');
      }

      private function translate(string $key): string
      {
         if ($this->languageService === null) {
            $this->languageService = $this->languageServiceFactory->createFromSiteLanguage(
               $this->request->getAttribute('language')
                  ?? $this->request->getAttribute('site')->getDefaultLanguage();
            );
         }

         return $this->languageService->sL(
            'LLL:EXT:your_extension/Resources/Private/Language/locallang.xlf:' . $key
         );
      }

      // ... the other methods from the introduction example are untouched
   }

.. note::
   To get the correct language, the configured feed path must be in the defined
   entry point of the language in the site configuration.
