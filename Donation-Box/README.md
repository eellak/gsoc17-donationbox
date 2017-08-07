# Donation-Box

This directory it contains all the necessary to operate the real donation box.
The donation box for its operation requires a set of settings and applications which in combination with each other produce the final result and the user experience that the final user will have.

This part of the whole system and network of doantion boxes which is being implemented, it is especially important to us. Because from here (from the actual donation box) everything starts. From this actual donation box, donations are made. So each one of the donaton boxes is an important active member of the donationbox system/network, in fact the most active part of the system.
Also the actual donation box is where the end user has both digital and real experience. For this reason, we are responsible to satisfy both.


## Of what it consists the actual donation box.
The final donation box will consist of the following main parts :
* Python Django App : To displaying the donation projects to the end user.
* SQLite Database : For storing local data.
* OpenVPN Client : To synchronize the data safely.

#### Python Django App.
Essentially this is a page designed with emphasis and responsibility in presenting donation projects. Using the language Python and Django web application framework we've created the page that will be displayed to the user from the donation box screen, the donation projects and will be able to navigate between them. Also, once a donation is given is responsible for displaying it to the user.
We chose the Python and Django web application framework, due to its flexibility and very good performance in conjunction with its minimum requirements and burdens on the system.


#### SQLite Database.
It is the local database that will be stored all the informations for each one of donation projects. For each donation project we will keep his information locally. The local database will be synchronized at regular intervals ( if this is feasible ) with the remote database where all the donation project data is kept and additional information. The local database is only a small subset of the remote database where it contains only the basic information for each donation project.

#### OpenVPN Client.
To have a high level of security, we decided that the communication of the modernized system of donation boxes to be done through an encrypted and fully secure virtual private network ( VPN ). This network will be created on the server where the remote database is located and each donation box will be given a specific access account to that network. For this reason, it should be constantly ( since its inception ) the donation box connected to this network.
If the box is not connected to virtual private network it will not be able to communicate with the remote database and hence he will not be able to update his local database.

