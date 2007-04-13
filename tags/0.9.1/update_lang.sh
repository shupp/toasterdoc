#!/bin/sh

LCDIR='locale/en/LC_MESSAGES'
MFILE="$LCDIR/messages.po"

xgettext -L PHP --keyword=_ index.php tpl/* --output=$MFILE
sed -i -e 's/CHARSET/UTF-8/' $MFILE
sed -i -e 's!FULL NAME <EMAIL@ADDRESS>!Bill Shupp <hostmaster@shupp.org>!' $MFILE
sed -i -e 's!LANGUAGE <LL@li.org>!Toaster Translators <toaster-tr@shupp.org>!' $MFILE
(cd $LCDIR ; msgfmt messages.po)
