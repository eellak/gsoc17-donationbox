## Local connections to remote databases through the Virtual Private Network.

> Using a virtual private network beyond its security, yet another very important advantage is all devices connected to the network can communicate with each other as if they were in the same LAN. This advantage you offer us would be a shame not to use it.

### Configurations on the remote machine with the database.
The most important of all is when you create the database or when you add a user to have access/privileges to it is define and from **where** this user can be connect to the database.
So in our case, we created the user `tassos` with password `1234567890` who has access to the database `donation_box_database` when it has any possible IP address that it can take when it is connected to the virtual private network (`10.8.%.%`).

( *In your case, you can change the name of the user and the database even the subnet. Here we show our own tried and general use case.* )

So on the remote machine we open via a terminal the shell of the database we have and execute the following commands:

`CREATE USER 'tassos'@'10.8.%.%' IDENTIFIED BY '1234567890';` <br>
`GRANT ALL PRIVILEGES ON donation_box_database.* TO 'tassos'@'10.8.%.%';` <br>
`FLUSH PRIVILEGES;` <br>
`QUIT;`<br>

We need to make two other settings on the remote machine.
They are not necessarily needed, but the reasons do, but as you will understand are useful to do.

##### Determined from which IP address, the database will accept connections (**even locally**).

Again via terminal and if we have acquired root privileges, execute :

* For **MariaDB** & **MySQL** : `vi /etc/mysql/my.cnf`

We find the line:

`bind-address            = 127.0.0.1`

and change it to be like this:

`bind-address            = donationBoxes.database`

<br>
After that, we have one last configuration. The domain name `donationBoxes.database` in which IP address is corresponding. To do this (always local), execute with root privileges :


`vi /etc/hosts`

and at the end of this file, add this line :


`10.8.0.1        donationBoxes.database`

<br>
Finally restart the database daemon :

`/etc/init.d/mysql restart`

<br>

### Configurations on the local machine.
These settings essentially concern each donation box, which is because it is connected to the virtual private network has the ability to connect to the central remote database that is also within the virtual private network.

Here, all we have to do is the following.
Through a terminal with root privileges, run :

`vi /etc/hosts`

and at the end of this file, add this line :

`10.8.0.1        donationBoxes.database`

<br>
Now you are ready to connect with the remote database
due to the virtual private network we have the impression that he is near us.
So, to connect with it, simply run:

`mysql -u tassos -h donationboxes.database -p`

and type your password.

> Note : Probably in this use case, it is necessary to have a compatible client with the remote database server. In my case, the remote database is MySQL and on my local machine i have it installed the MariaDB.

***

#### Important!
###### In the above way, we do not create remote access to the database (i.e. outside of the local network), we just set up how local machines connect to the database.
