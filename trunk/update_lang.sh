#!/bin/sh

LCDIR='locale/en/LC_MESSAGES'
MFILE="$LCDIR/messages.po"

#TEMPFILE="/tmp/gettext_files.$$"

#find ../ | egrep -v '(.svn|Templates/Default/templates_c|Templates/Default/cache)' | egrep '(.php$)' > $TEMPFILE

#xgettext -L PHP --keyword=_ public/index.php Includes/*php Modules/*php tpl/* --output=$MFILE
xgettext -L PHP --keyword=_ test.xml --output=$MFILE
sed -i -e 's/CHARSET/UTF-8/' $MFILE
sed -i -e 's!FULL NAME <EMAIL@ADDRESS>!Bill Shupp <hostmaster@shupp.org>!' $MFILE
(cd $LCDIR ; msgfmt messages.po)
#rm $TEMPFILE
