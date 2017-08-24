#!/bin/bash

#    install script - Automates the process of moving and assigning the
#    appropriate privileges to the files.
#
#     * It should be run with root privileges! *
#
#    GNU General Public License v3.0
#
#    Authors: Anastasios Lisgaras <tas-sos@g-lts.info>,
#
#    This program is free software; you can redistribute it and/or modify
#    it under the terms of the GNU General Public License as published by
#    the Free Software Foundation; either version 3 of the License, or
#    (at your option) any later version.
#
#    This program is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU General Public License for more details.
#
#    You should have received a copy of the GNU General Public License along
#    with this program; if not, write to the Free Software Foundation, Inc.,
#    51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
#
#    This software was created under the Google Summer of Code 2017 program
#    for the Donation-Box project :
#    https://summerofcode.withgoogle.com/projects/#5171214440988672

# For startup boot screen messages.
mv donationbox /etc/init.d/
chmod 755 /etc/init.d/donationbox
update-rc.d donationbox defaults


# For Welcome messages.
mkdir /etc/update-motd.d/
mv 40-donation-box /etc/update-motd.d/
chmod 755 /etc/update-motd.d/40-donation-box
