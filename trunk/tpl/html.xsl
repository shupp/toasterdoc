<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

 <xsl:output method="html"
        doctype-public = "-//W3C//DTD HTML 4.01 Transitional//EN"/>

 <xsl:template match="article">
   <html>
   <head>
     <title><xsl:value-of select="title"/></title>
     <link rel="STYLESHEET" type="text/css" href="doc-style.css" />
   </head>
   <body>
     <a name="top"></a>
     <h1><xsl:value-of select="title"/></h1>
     <xsl:apply-templates select="section"/>

    <center>
    <table border="0" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td><div align="left"><a href="./?page={previousPage}"><b><?php echo _("Back")?></b></a></div></td>
        <td><div align="center"><a href="./?page={defaultPage}"><b><?php echo _("Home") ?></b></a></div></td>
        <td><div align="right"><b><a href="./?page={nextPage}"><?php echo _("Next")?></a></b></div></td>
    </tr>
    </table>
    </center>
   </body>
   </html>
 </xsl:template>

 <xsl:template match="section">
   <xsl:apply-templates/>
   <hr />
 </xsl:template>

 <xsl:template match="section/title">
   <h2><xsl:apply-templates/></h2>
 </xsl:template>

 <xsl:template match="emphasis">
   <b><xsl:apply-templates/></b>
 </xsl:template>
 <xsl:template match="para">
   <p><xsl:apply-templates/></p>
 </xsl:template>
 <xsl:template match="programlisting">
    <blockquote class="command">
        <xsl:apply-templates/>
    </blockquote>
 </xsl:template>
 <xsl:template match="blockquote">
    <blockquote class="edit">
        <xsl:apply-templates/>
    </blockquote>
 </xsl:template>
 <xsl:template match="lineannotation">
    # <xsl:apply-templates/><br />
 </xsl:template>
 <xsl:template match="literal">
    <xsl:apply-templates/><br />
 </xsl:template>
 <xsl:template match="literallayout">
    <xsl:apply-templates/><br />
 </xsl:template>

 <xsl:template match="itemizedlist">
   <ul><xsl:apply-templates/></ul>
 </xsl:template>
 <xsl:template match="ulink">
   <a>
        <xsl:attribute name="href">
        <xsl:value-of select="@url"/>
        </xsl:attribute>
        <xsl:apply-templates/>
   </a>
 </xsl:template>
 <xsl:template match="anchor">
   <a>
        <xsl:attribute name="name"><xsl:value-of select="@id"/></xsl:attribute>
    </a>
 </xsl:template>

 <xsl:template match="listitem">
   <li><xsl:apply-templates/></li>
 </xsl:template>
 <xsl:template match="userinput">
        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
        <input type="text" name="varsrc" value="<?php echo $varsrc?>" /> <input type="submit" value="set" />
        </form>
 </xsl:template>
</xsl:stylesheet>