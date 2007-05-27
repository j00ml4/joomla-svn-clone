<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="*">
        <xsl:copy>
            <xsl:apply-templates select="@* | * | comment() |  text()" />
        </xsl:copy>
    </xsl:template>
    <xsl:template match="/chapter/section/section/section/section/section">
    <sect5>
        <xsl:apply-templates/>
    </sect5>
    </xsl:template>
    <xsl:template match="/chapter/section/section/section/section">
    <sect4>
        <xsl:apply-templates/>
    </sect4>
    </xsl:template>
    <xsl:template match="/chapter/section/section/section">
    <sect3>
        <xsl:apply-templates/>
    </sect3>
    </xsl:template>
    <xsl:template match="/chapter/section/section">
    <sect2>
        <xsl:apply-templates/>
    </sect2>
    </xsl:template>
    <xsl:template match="/chapter/section">
    <sect1>
        <xsl:apply-templates/>
    </sect1>
    </xsl:template>
</xsl:stylesheet>
