.. Generated by https://github.com/TYPO3-Documentation/t3docs-codesnippets 

================================================================================
Brotkrueml\\FeedGenerator\\Entity\\Item
================================================================================

.. php:namespace::  Brotkrueml\FeedGenerator\Entity

.. php:class:: Item

   
   .. php:method:: getId()
   
      :returntype: string
      
   .. php:method:: setId(string id)
   
      :param string $id: the id
      :returntype: self
      
   .. php:method:: getTitle()
   
      :returntype: string
      
   .. php:method:: setTitle(string title)
   
      :param string $title: the title
      :returntype: self
      
   .. php:method:: getDescription()
   
      :returntype: Brotkrueml\\FeedGenerator\\Contract\\TextInterface|string
      
   .. php:method:: setDescription(Brotkrueml\\FeedGenerator\\Contract\\TextInterface|string description)
   
      :param Brotkrueml\\FeedGenerator\\Contract\\TextInterface|string $description: the description
      :returntype: self
      
   .. php:method:: getContent()
   
      :returntype: string
      
   .. php:method:: setContent(string content)
   
      :param string $content: the content
      :returntype: self
      
   .. php:method:: getLink()
   
      :returntype: string
      
   .. php:method:: setLink(string link)
   
      :param string $link: the link
      :returntype: self
      
   .. php:method:: getAuthors()
   
      :returntype: Brotkrueml\\FeedGenerator\\Collection\\Collection
      
   .. php:method:: addAuthors(Brotkrueml\\FeedGenerator\\Contract\\AuthorInterface authors)
   
      :returntype: self
      
   .. php:method:: getDatePublished()
   
      :returntype: DateTimeInterface
      
   .. php:method:: setDatePublished(DateTimeInterface datePublished)
   
      :param DateTimeInterface $datePublished: the datePublished
      :returntype: self
      
   .. php:method:: getDateModified()
   
      :returntype: DateTimeInterface
      
   .. php:method:: setDateModified(DateTimeInterface dateModified)
   
      :param DateTimeInterface $dateModified: the dateModified
      :returntype: self
      
   .. php:method:: getAttachments()
   
      :returntype: Brotkrueml\\FeedGenerator\\Collection\\Collection
      
   .. php:method:: addAttachments(Brotkrueml\\FeedGenerator\\Contract\\AttachmentInterface attachments)
   
      :returntype: self
      
   .. php:method:: getCategories()
   
      :returntype: Brotkrueml\\FeedGenerator\\Collection\\Collection
      
   .. php:method:: addCategories(Brotkrueml\\FeedGenerator\\Contract\\CategoryInterface categories)
   
      :returntype: self
      
   .. php:method:: addExtensionContents(Brotkrueml\\FeedGenerator\\Contract\\ExtensionContentInterface contents)
   
      :returntype: self
      
   .. php:method:: getExtensionContents()
   
      :returntype: Brotkrueml\\FeedGenerator\\Collection\\Collection

