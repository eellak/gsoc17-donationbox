# Donation-Box
[![License: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)
[![SQLite3](https://img.shields.io/badge/Database-SQLite3-brightgreen.svg)](https://www.sqlite.org/)
[![python3](https://img.shields.io/badge/Python-3.x-blue.svg)](https://www.python.org/downloads/)
[![django1.11](https://img.shields.io/badge/Django-1.11.4-green.svg)](https://docs.djangoproject.com/en/1.11/releases/1.11.4/)
[![shell](https://img.shields.io/badge/other-Shell-orange.svg)](https://en.wikipedia.org/wiki/Shell_script)
[![coverage-95%](https://img.shields.io/badge/coverage-95%25-brightgreen.svg)](https://github.com/eellak/gsoc17-donationbox/tree/master/Database)


This directory it contains all the necessary to work the real donation box.<br>
The donation box for its work requires a set of settings and applications which in combination with each other produce the final result and the user experience that the final user will have.

This part of the whole system and network of doantion boxes which is being implemented, it is especially important to us. Because from here (from the actual donation box) everything starts. From this actual donation box, donations are made. So each one of the donaton boxes is an important active member of the donationbox system/network, in fact the most active part of the system.
Also the actual donation box is where the end user has both digital and real experience. For this reason, we are responsible to satisfy both.


## Of what it consists the actual donation box.
The final donation box will consist of the following main parts :
* [Python Django App](https://github.com/eellak/gsoc17-donationbox/tree/master/Donation-Box/DjangoApp) : To view the donation projects to the end user.
* SQLite Database : For storing local data.
* [OpenVPN Client](https://github.com/eellak/gsoc17-donationbox/tree/master/Donation-Box/OpenVPN%20Client) : To synchronize the data safely.
* [APIs daemons for Synchronization](https://github.com/eellak/gsoc17-donationbox/tree/master/Donation-Box/APIs%20daemons%20for%20Synchronization) : To synchronize local data with the remote database.

#### [Python Django App](https://github.com/eellak/gsoc17-donationbox/tree/master/Donation-Box/DjangoApp). [![coverage-97%](https://img.shields.io/badge/coverage-97%25-brightgreen.svg)](https://github.com/eellak/gsoc17-donationbox/tree/master/Database)
Essentially this is a page designed with emphasis and responsibility in presenting donation projects. Using the language Python and Django web application framework we've created the page that will be displayed the donation projects to the user from the donation box screen, and will be able to navigate between them.
We chose the Python and Django web application framework, due to its flexibility and very good performance in conjunction with its minimum requirements and burdens on the system.


#### SQLite Database. [![coverage-100%](https://img.shields.io/badge/coverage-100%25-brightgreen.svg)](https://github.com/eellak/gsoc17-donationbox/tree/master/Database)
It is the local database that will be stored all the required informations for each one of donation projects. For each donation project we will keep a subset of his information locally.

The installation and implementation of the database, is made during when installing the Django App. So, we do not have to worry about it, anymore than we should.

 The local database will be synchronized at regular intervals ( if this is feasible ) with the remote database where all the donation project data is kept and additional information. The local database is only a small subset of the remote database where it contains only the basic information for each donation project.
Responsible for the synchronization are the [APIs daemons](https://github.com/eellak/gsoc17-donationbox/tree/master/Donation-Box/APIs%20daemons%20for%20Synchronizatio).

The local database consists of two tables. Below we see the schema of the database.


| ![Donation-Box local database schema](https://raw.githubusercontent.com/eellak/gsoc17-donationbox/master/Donation-Box/sqlite_database.png) |
|:--:|
| * The schema of local database in Donation-Box * |

* **Donation_Project** table : This is the table in which all information is stored  which we deem it is necessary to have in donation box. These data are only a small subset of [the central (remote) database](https://github.com/eellak/gsoc17-donationbox/tree/master/Database) ( "*donationbox_network*" ).<br>
This table is **read-only** from Django App ( presentation ). The application reads the data (in combination with the other table) and presents it to the user. <br>
[A daemon API process](https://github.com/eellak/gsoc17-donationbox/tree/master/Donation-Box/APIs%20daemons%20for%20Synchronization) is responsible to update the contents of this table, for the synchronization with the central database.

* **temporary_Donation_Project** table : This table contains temporary information until the [daemon API process](https://github.com/eellak/gsoc17-donationbox/tree/master/Donation-Box/APIs%20daemons%20for%20Synchronization) can be synchronize the local database with the remote central database. Specifically, whenever done a donation in the donation box the details of this donation ( such as the amount of money, for which donation project was done and the timing of the donation) stored in this table of the local database. <br>
[A daemon API process](https://github.com/eellak/gsoc17-donationbox/tree/master/Donation-Box/APIs%20daemons%20for%20Synchronization) is responsible when synchronization with the remote database is possible, to get all the data from this table, send them to the remote database and finally to delete them from this table.

#### [OpenVPN Client](https://github.com/eellak/gsoc17-donationbox/tree/master/Donation-Box/OpenVPN%20Client). [![coverage-100%](https://img.shields.io/badge/coverage-100%25-brightgreen.svg)](https://github.com/eellak/gsoc17-donationbox/tree/master/Database)
To have a high level of security, we decided that the communication of the modernized system of donation boxes to be done through an encrypted and fully secure virtual private network ( VPN ). This network will be created on the server where the remote database is located and each donation box will be given a specific access account to that network. For this reason, it should be constantly ( since its inception ) the donation box connected to this network.
If the box is not connected to virtual private network it will not be able to communicate with the remote database and hence he will not be able to update his local database.
For the OpenVPN client, must be done specific settings that are necessary for proper functioning.


#### [APIs daemons for Synchronization](https://github.com/eellak/gsoc17-donationbox/tree/master/Donation-Box/APIs%20daemons%20for%20Synchronization). [![coverage-95%](https://img.shields.io/badge/coverage-95%25-brightgreen.svg)](https://github.com/eellak/gsoc17-donationbox/tree/master/Database)
To synchronize local database data with the data from the remote database, it is necessary to running some processes in the background. They should be running on the background three processes continuously in donation box

εδώ πρέπει να αναφέρω πως θα πρέπει να υπάρχει και ένας daemon για να ανηχνεύει πότε γίνεται μια δωρεά.
