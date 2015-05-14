#!/bin/sh

# Update the opkg list
opkg update > /dev/null;

# Check if hping3 is installed
test=$(opkg list-installed | grep 'hping3')

if [ -z "$test" ]; then
	opkg install hping3 > /dev/null;
fi

echo "Complete"
