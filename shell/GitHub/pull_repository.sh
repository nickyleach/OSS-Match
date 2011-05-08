#! /bin/bash
# pull_repository.sh

# Arguments:
# $1 - Repository URL (ssh)
# $2 - (optonal) Destination directory

# Output:
# Path to root repository directory

# Returns:
# COMPLETE: 0
# ERROR: 1

if [ $# -ge 1 ]
then
	URL=$1
else
	echo "$0 : Please enter the ssh URL for the github repository"
	exit 1;
fi

if [ $# -ge 2 ]
then
	DESTINATION=$2
else
	file=$(basename $URL)
	DESTINATION=${file%.*}
fi

cd /tmp

if [ ! -d $DESTINATION ]; then
	git clone $URL $DESTINATION > /dev/null 2>&1
	rm -Rf $DESTINATION/.git
fi

if [ -d $DESTINATION ]; then
	cd $DESTINATION
	echo `pwd`
	exit 0;
fi

echo "$0 : Unable to pull the repository"
exit 1;