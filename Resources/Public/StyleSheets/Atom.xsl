<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE stylesheet [<!ENTITY css SYSTEM "../Css/xsl.css">]>
<xsl:stylesheet version="3.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:atom="http://www.w3.org/2005/Atom">
    <xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
    <xsl:template match="/atom:feed">
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <title>
                    <xsl:value-of select="atom:title"/>
                </title>
                <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
                <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
                <style>&css;</style>
            </head>
            <body>
                <aside>
                    <p>
                        This is an automatically generated Atom feed that allows you to keep up with the latest posts
                        from this site. You can use an RSS reader app to subscribe.
                    </p>
                </aside>
                <div class="feed">
                    <section class="summary">
                        <xsl:if test="atom:logo">
                            <img alt="">
                                <xsl:attribute name="src">
                                    <xsl:value-of select="atom:logo"/>
                                </xsl:attribute>
                            </img>
                        </xsl:if>
                        <h1>
                            <xsl:value-of select="atom:title"/>
                        </h1>
                        <xsl:if test="atom:description">
                            <p>
                                <xsl:value-of select="atom:description"/>
                            </p>
                        </xsl:if>
                    </section>
                    <xsl:for-each select="atom:entry">
                        <xsl:sort select="atom:updated" order="descending"/>
                        <article>
                            <h2>
                                <a target="_blank">
                                    <xsl:attribute name="href">
                                        <xsl:value-of select="atom:link/@href"/>
                                    </xsl:attribute>
                                    <xsl:value-of select="atom:title"/>
                                </a>
                            </h2>
                            <dl>
                                <dt>Updated</dt>
                                <dd>
                                    <xsl:value-of select="atom:updated"/>
                                </dd>
                                <xsl:if test="atom:summary">
                                    <dt>Summary</dt>
                                    <dd>
                                        <xsl:value-of select="atom:summary" disable-output-escaping="yes"/>
                                    </dd>
                                </xsl:if>
                                <xsl:if test="atom:content">
                                    <dt>Content</dt>
                                    <dd>
                                        <xsl:value-of select="atom:content" disable-output-escaping="yes"/>
                                    </dd>
                                </xsl:if>
                            </dl>
                        </article>
                    </xsl:for-each>
                </div>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
