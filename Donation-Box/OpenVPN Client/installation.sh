#!/bin/bash

# For start boot screen.
sudo mv donationbox /etc/init.d/
sudo chmod 755 /etc/init.d/donationbox
sudo update-rc.d donationbox defaults


# For Welcome message.
sudo mkdir /etc/update-motd.d/
sudo mv 40-donation-box /etc/update-motd.d/
sudo chmod 755 /etc/update-motd.d/40-donation-box



