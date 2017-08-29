## Donation-Box ~ APIs daemons for Synchronization.

[![License: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)
[![python3](https://img.shields.io/badge/Python-3.x-blue.svg)](https://www.python.org/downloads/)
[![coverage-50%](https://img.shields.io/badge/coverage-50%25-green.svg)](https://github.com/eellak/gsoc17-donationbox/tree/master/Donation-Box/APIs%20daemons%20for%20Synchronization)

This directory it contains the APIs that have been implemented to achieve communication between the local database and the remote database.


### How they work.
Required a [configuration file](https://github.com/eellak/gsoc17-donationbox/blob/master/Donation-Box/APIs%20daemons%20for%20Synchronization/Databases/donationbox.conf), in which you should set basic settings, also only for synchronization required that the donation box is connected with the [Virtual Private Network](https://github.com/eellak/gsoc17-donationbox/tree/master/Virtual%20Private%20Network), and therefore, they can connect to the remote database ( *like a simple local connection* ).

If the configuration file is setup correctly, you will be able to run a central process that will synchronize the data of the two databases( [local](https://github.com/eellak/gsoc17-donationbox/tree/master/Donation-Box) & [remote](https://github.com/eellak/gsoc17-donationbox/tree/master/Database)). If the donation box is not connected to the [Virtual Private Network](https://github.com/eellak/gsoc17-donationbox/tree/master/Virtual%20Private%20Network), it will not be able to connect to the remote database for the synchronization. Once it has been connected to the [Virtual Private Network](https://github.com/eellak/gsoc17-donationbox/tree/master/Virtual%20Private%20Network), it will be able to synchronize.


### What has been implemented.
The main [classes](https://github.com/eellak/gsoc17-donationbox/blob/master/Donation-Box/APIs%20daemons%20for%20Synchronization/Databases/Databases.py) on which this operation will be based have been developed.
The [class](https://github.com/eellak/gsoc17-donationbox/blob/master/Donation-Box/APIs%20daemons%20for%20Synchronization/Databases/SQLite3.py) that is responsible for communicating with the local database has also [been fully developed](https://github.com/eellak/gsoc17-donationbox/blob/master/Donation-Box/APIs%20daemons%20for%20Synchronization/Databases/SQLite3.py).


### What has not been implemented.
The [class](https://github.com/eellak/gsoc17-donationbox/blob/master/Donation-Box/APIs%20daemons%20for%20Synchronization/Databases/MariaDB.py) that is responsible for communicating with the [remote database](https://github.com/eellak/gsoc17-donationbox/tree/master/Database) is not fully developed. Due to the fact that the central database has not been upgraded to the standard required by the DonationBox-Network. Because of this, because we do not have the full final mode/schema of the central database, we can not create (as is obvious) the appropriate queries to it. But the core of this functionality is already created. Remains a little partial upgrade when the central database is ready.
