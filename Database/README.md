# Donation-Box ~ Database
[![License: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0) [![MariaDb](https://img.shields.io/badge/Database-MariaDB-red.svg)](https://mariadb.org/)
[![coverage-90%](https://img.shields.io/badge/coverage-90%25-brightgreen.svg)](https://github.com/eellak/gsoc17-donationbox/tree/master/Database)


This directory contains all the files that are required for the database. This is the central database of the system. It contains all information about the donation boxes and donations projects. The whole structure of the donation system/network is based on this database.
The WordPress central page as well as the donation boxes communicate with her and update it.


# The idea.
The central database will contain all the informations. Data for the donation projects ( i.e. title, content, current total amount, goal amount, etc.). Data on the organizations to which the donation projects will belong ( i.e. name of organization, description, etc. ). Data on the donation boxes ( i.e. location, local total amount ,  box status etc. ) All critical information, therefore, that will start from here and ends here. The whole system functions also depend on database structure.


| ![donationbox_network](https://raw.githubusercontent.com/eellak/gsoc17-donationbox/master/Database/databasediagram.png) |
|:--:|
| The schema of donationbox_network database. |

This central database will be installed in a system independent of all other parts of donation-box system/network. So we assume that whoever is trying to communicate with her, should know where he is located.
As described in the central report for system operation, there are two possible types of database clients/users:
* the main WordPress site
* the donation boxes

Each of them has a specific way of communicating.

#### Τhe main WordPress site database client.
The main WordPress site communicates indirectly with the database through the PHP page. Through *POST* requests in the [PHP script](https://github.com/eellak/gsoc17-donationbox/blob/master/Database/index.php), it sends and receives data from the database.


##### Basic settings for proper operation of the PHP script.
To work properly the PHP page you need to set some basic settings ( i.e. settings such as the name of database, the username and password of the database user etc. ). In our effort to make this optimal and as easy as possible for you, we created the [config.php](https://github.com/eellak/gsoc17-donationbox/blob/master/Database/config.php) configuration file. So, you can give to this file all the basic settings that required from the PHP page to work properly. In this way we discourage you to see a lot of PHP code ( if you don't want it ) and we encourage you just change from some obvious fields their values.


Returning to the side of the main WordPress site, you only need to define on the settings page of the [donation-box plugin](https://github.com/eellak/gsoc17-donationbox/tree/master/plugins), in which address it is located and publicly available this PHP file and the username and password of the user, which on the database side - *from the PHP page* - are acceptable to make changes to the database.<br>
For example, the page may be available at : `http://donationbox.database.org/index.php` and the credentials of the user who have the privileges ( which is accepted from the PHP page ) :
```
username : db_admin
password : 123456789
```
Beyond that, on the side of the WordPress site, no further adjustment is required for communication.

Now go to the [configuration file](https://github.com/eellak/gsoc17-donationbox/blob/master/Database/config.php) and set it to the corresponding fields for the user the credentials you provided on the WordPress page. <br><br>

____

###### About the user of the database. [![Future](https://img.shields.io/badge/Future-Work-red.svg)](https://github.com/eellak/gsoc17-donationbox/tree/master/Database)
Currently, the user which is having the privileges from the [PHP page](https://github.com/eellak/gsoc17-donationbox/blob/master/Database/index.php) ( will be accepted ) is also [defined directly as the user of the database](https://github.com/eellak/gsoc17-donationbox/tree/master/Database#the-user-of-the-central-wordpress-site-).
Personally, i don't find it so safe.

Therefore, i suggest you to change that in the future these two different users, these two different roles to differentiate it. They are completely independent of each other.

____


#### Τhe client of donation boxes.
This client communicates *directly* with the database. Because donation boxes as described in the central description of the system, for security reasons, to be able to communicate with the database must be on the same (local) virtual private network, the API daemon that is responsible for updating the local data of the donation box, exploits this facility and communicates directly with the database.

However, it is not possible to connect to the database, if we have not properly defined a  database user and we do not know where the database is located.

How we define database users for donation boxes, we see it below, so go to the installation section below, *follow the instructions there and then return here*.
So after you install the database, it remains to set a fixed address from which the database will accept connections. To do this, simply edit a file by running with root privileges :

* For MariaDB or MySQL database server (on Debian based GNU/Linux distributions):<br> `vi /etc/mysql/my.cnf`

We find the line:

`bind-address = 127.0.0.1`

and change it to be like this:

`bind-address = donationBoxes.database`

After that, we have one last configuration. The domain name `donationBoxes.database` in which IP address is corresponding. To do this (always local), execute with root privileges : `vi /etc/hosts`

and at the end of this file, add this line :

`10.8.0.1 donationBoxes.database`

Finally restart the database daemon :

`/etc/init.d/mysql restart`


#### Important!
###### In the above way, we do not create remote access to the database (i.e. outside of the local network), we just set up how local machines connect to the database.

-----

To install and configure the database, you have two ways.
____
### Automatic installation. [![Future](https://img.shields.io/badge/Future-Work-red.svg)](https://github.com/eellak/gsoc17-donationbox/tree/master/Database)
Just download the SQL script `create_database.sql` and Python script `database_config.py` at the same directory.
Then run the Python script with root privileges and will take care everything for you.

i.e. :

`sudo python3 database_config.py`

> Note: The automatic installation does not concern the user configurations, just the installation and creation of the database.<br>
In a future version, its functionality could be upgraded offering even more automatic settings, under a friendly user interface.

____

### Manual installation.
To set up the database, you need first of all to have a database server installed on your system, to do this on GNU/Linux operating systems run the follow command ( Debian based distributions ) :

`apt-get install mariadb-common mariadb-client mariadb-server`

 after that, just download the `create_database.sql` file and run the command:

```
mysql -u root -p < create_database.sql

[ OK ] The database installation has been successfully completed.
```

If you receive the above confirmation message and didn't unexpectedly interrupt the script with displaying you some Warning or Error, then everything went well.



So, in this way you will create very easily your database ( *named `donationbox_network`*) with the necessary tables and their relationships.

> Note: The password of the *root* user is your own affair. Is the password you provided during installation of the database.

So far, you have implemented the database, but there must be specific users with specific permissions in the database where they can to act on it.
We have two categories of users :
* the user of the central WordPress site
* the user(s) of donation boxes.
____
#### The user of the central WordPress site. [![Future](https://img.shields.io/badge/Future-Work-red.svg)](https://github.com/eellak/gsoc17-donationbox/tree/master/Database)
This is the user who will have the privileges to send data on the server where the database is installed ( [*that is accepted from the index.php page*](https://github.com/eellak/gsoc17-donationbox/blob/master/Database/index.php) ) from the central WordPress site. **This user will be only one** And not the user of the database itself.
Furthermore, this user would be nice can be added it through a nice user interface.


*For now, we have only one user (the same for both jobs) and his credentials are given in the [configuration file](https://github.com/eellak/gsoc17-donationbox/blob/master/Database/config.php).*
____
#### The users of donation boxes.
These will be the users of the donation boxes. For each donation box there will also be the respective user.
Where it will have the respective *specific* permissions specified in the database.
This user needs to be configured correctly both for safety reasons and for communication purposes.
> Note :
* Security : Ensure it does not have privileges that it does not need to have.
* Communication : Ensure that allowed connections comes only from your [Virtual Private Network](https://github.com/eellak/gsoc17-donationbox/tree/master/Virtual%20Private%20Network).

For example in our case, we want a donation box it can only import data to a specific table of the database (`donation`), by a particular user (`tassos`) and since he is connected from our VPN subnet (`10.8.%.%`). We will also allow the above user to can be able view (`SELECT`) the data of all the tables in the database. <br>
So, log in to the database as root user and create the user named `tassos` with password `123456789` :

`CREATE USER 'tassos'@'10.8.%.%' IDENTIFIED BY '1234567890';`

then give the privileges we said above that we want to have this user:

`GRANT INSERT ON donationbox_network.donation TO 'tassos'@'10.8.%.%';`

`GRANT SELECT ON donationbox_network.* TO 'tassos'@'10.8.%.%';`

`FLUSH PRIVILEGES;`

`QUIT`

Now, you are ready to use the credentials of the above user to connect in from the donation box.


---


These are all you need to do to have the database installed and configured properly, to work smoothly with the rest of the system.


## Enrichment of the database. [![Future](https://img.shields.io/badge/Future-Work-red.svg)](https://github.com/eellak/gsoc17-donationbox/tree/master/Database)

As we can see [in the index.php file](https://github.com/eellak/gsoc17-donationbox/blob/master/Database/index.php#L109), there are some data that is sent it to the machine where the database server is installed, but for now, this data is not being used, while received, are not stored in the database. I believe that this data should be included in the database, because it provides very useful information.
