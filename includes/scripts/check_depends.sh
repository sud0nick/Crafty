#!/bin/sh

# Check if hping3 is installed
test=$(opkg list-installed | grep 'hping3')

if [ -z "$test" ]; then
	echo "Not Installed";
else
	echo "Installed";
fi
