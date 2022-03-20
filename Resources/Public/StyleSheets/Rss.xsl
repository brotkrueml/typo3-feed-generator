<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE stylesheet [<!ENTITY css SYSTEM "../Css/xsl.css">]>
<xsl:stylesheet version="3.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
    <xsl:template match="/rss/channel">
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <title>
                    <xsl:value-of select="title"/>
                </title>
                <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
                <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
                <style>&css;</style>
            </head>
            <body>
                <aside>
                    <p>
                        This is an automatically generated RSS feed that allows you to keep up with the latest posts
                        from this site. You can use an RSS reader app to subscribe.
                    </p>
                </aside>
                <div class="feed">
                    <section class="summary">
                        <xsl:if test="image/url">
                            <img alt="">
                                <xsl:attribute name="src">
                                    <xsl:value-of select="image/url"/>
                                </xsl:attribute>
                            </img>
                        </xsl:if>
                        <h1>
                            <xsl:value-of select="title"/>
                        </h1>
                        <xsl:if test="description">
                            <p>
                                <xsl:value-of select="description"/>
                            </p>
                        </xsl:if>
                    </section>
                    <xsl:for-each select="item">
                        <xsl:sort select="pubDate" order="descending"/>
                        <article>
                            <h2>
                                <a target="_blank">
                                    <xsl:attribute name="href">
                                        <xsl:value-of select="link"/>
                                    </xsl:attribute>
                                    <xsl:value-of select="title"/>
                                </a>
                            </h2>
                            <dl>
                                <dt>Publication date</dt>
                                <dd>
                                    <xsl:value-of select="pubDate"/>
                                </dd>
                                <xsl:if test="description">
                                    <dt>Description</dt>
                                    <dd>
                                        <xsl:value-of select="description" disable-output-escaping="yes"/>
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
