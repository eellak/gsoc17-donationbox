# Donation-Box ~ Database

This directory contains all the files that are required for the database. This is the central database of the system. It contains all information about the donation boxes and donations projects. The whole structure of the donation system/network is based on this database.
The WordPress central page as well as the donation boxes communicate with her and update it.

To set up the database, just download the `create_database.sql` file and run the command:

<center>
`mysql -u root -p < create_database.sql`
</center>


So, in this way you will create very easily your database ( *named `donationbox_network`*) with the necessary tables and their relationships.

> Note: The password of the *root* user is your own affair. Is the password you provided during installation of the database.

So far, you have implemented the database, but there must be specific users with specific permissions in the database where they can to act on it.
We have two categories of users :
* the user of the central WordPress site
* the users of donation boxes.

#### The user of the central WordPress site.
This is the user who will have the permissions to send data to the database from the central web page.
**Such a user, there will be only one.**

#### The users of donation boxes.
These will be the users of the donation boxes. For each donation box there will also be the respective user.
Where it will have the respective specific permissions specified in the database.
