<?xml version="1.0" encoding="utf-8"?>
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
                <style>
                    * {
                    box-sizing: border-box;
                    }

                    html {
                    font-size: 62.5%;
                    }

                    body {
                    font-family: sans-serif;
                    font-size: 1.8rem;
                    margin: 0;
                    }

                    article {
                    border-bottom: 1px solid lightgray;
                    }

                    article:last-child {
                    border: none;
                    }

                    aside {
                    padding: 1rem;
                    background: aliceblue;
                    text-align: center;
                    }

                    dt {
                    font-weight: bold;
                    margin-bottom: .5rem;
                    }

                    dt::after {
                    content: ":";
                    }

                    dd {
                    margin-bottom: .5rem;
                    }

                    dd p {
                    margin: .5rem 0;
                    }

                    dd p:first-child {
                    margin-top: 0;
                    }

                    dd p:last-child {
                    margin-bottom: 0;
                    }

                    img {
                    float: right;
                    max-width: 100px;
                    }

                    h1 {
                    font-size: 3.5rem;
                    font-weight: normal;
                    margin: 0;
                    }

                    h2 {
                    font-size: 2.2rem;
                    font-weight: normal;
                    }

                    ul {
                    margin: 0;
                    padding-left: 2rem;
                    }

                    .feed {
                    max-width: 80rem;
                    margin: 2rem auto;
                    padding: 0 1rem;
                    }

                    .summary {
                    margin-bottom: 2rem;
                    }

                    .summary::after {
                    clear: both;
                    }
                </style>
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
                                <xsl:if test="atom:link/@rel='enclosure'">
                                    <dt>Media</dt>
                                    <dd>
                                        <ul>
                                            <xsl:apply-templates select="atom:link[@rel='enclosure']"/>
                                        </ul>
                                    </dd>
                                </xsl:if>
                            </dl>
                        </article>
                    </xsl:for-each>
                </div>
            </body>
        </html>
    </xsl:template>

    <xsl:template match="atom:link">
        <li>
            <a target="_blank" rel="noreferrer">
                <xsl:attribute name="href">
                    <xsl:value-of select="@href"/>
                </xsl:attribute>
                <xsl:value-of select="@href"/>
            </a>
            (<xsl:value-of select="@type"/>, <xsl:value-of select="format-number(@length,'#,#00')"/> bytes)
        </li>
    </xsl:template>
</xsl:stylesheet>
