#! /bin/bash
# remove_repository.sh

# Arguments:
# $1 - Repository ID

# Returns:
# COMPLETE: 0
# ERROR: 1

if [ $# -ge 1 ]
then
	ID=$1
else
	echo "$0 : Please enter the repository ID you'd like to delete"
	exit 1;
fi

redis-cli -p 6401 keys Repository*$ID* | xargs redis-cli -p 6401 del
for key in `redis-cli -p 6401 keys Match*`; do redis-cli -p 6401 zrem $key $ID; done

exit 0;