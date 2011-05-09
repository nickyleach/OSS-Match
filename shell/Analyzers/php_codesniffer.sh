#! /bin/bash
# php_codesniffer.php

# Arguments:
# $1 - file to be analyzed

# Returns:
# COMPLETE: 0
# ERROR: 1

if [ $# -ge 1 ]
then
	INPUT=$1
else
	echo "$0 : Please enter the path to the file you'd like to analyze"
	exit 1;
fi

cd /tmp
mkdir analysis > /dev/null 2>&1
cd analysis

OUTPUT="`pwd`/`basename $1`.xml"

phpcs --standard=Zend --report-xml=$OUTPUT $INPUT > /dev/null 2>&1

if [ -f $OUTPUT ]; then
	echo "$OUTPUT"
	exit 0
fi

echo "$0 : Unable to analyze $INPUT"
exit 1