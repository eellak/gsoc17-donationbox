# Donation-Box ~ OpenVPN Client
[![License: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)
[![shell](https://img.shields.io/badge/other-Shell-orange.svg)](https://en.wikipedia.org/wiki/Shell_script)
[![coverage-100%](https://img.shields.io/badge/coverage-100%25-brightgreen.svg)](https://github.com/eellak/gsoc17-donationbox/tree/master/Donation-Box/OpenVPN%20Cliente)

This directory contains all necessary settings and instructios for OpenVPN client.


###### Read and follow the instructions below because they are very important for the proper functioning of the donation box. <br><br>


We have chosen for *secure, reliable, and scalable communication services* reasons, each of the donation boxes is always within the virtual private network ( VPN ) and therefore can be connected through him to the remote database.

> Note: Of course it is expected that the donation box sometimes can be offline, to not have access to the internet and therefore to cannot be connect to the virtual private network. For this reason, we also maintain the local database.
We take care of that, in case you can not connect directly to the virtual private network the system will automatically try to recover the connection.

To be able to connect to the VPN, we need to have a client program (daemon) to take care of this connection. Us to implement the virtual private network ( *on the part of the server* ) we use the [OpenVPN](https://openvpn.net/index.php/open-source.html). So we will also use the corresponding client for this.

## Install OpenVPN client.
If we use a GNU/Linux operating system the installation of the OpenVPN client is simple. Get root privileges and execute its installation command of the OpenVPN. Below are some examples of the most known GNU/Linux distributions.

- Debian/Ubuntu:
`apt-get install openvpn`

- Fedora/CentOS/RedHat:
`yum install openvpn`

## OpenVPN keys and certificates.
Once the installation is over, we are almost ready to use the OpenVPN client.
We are almost ready and not fully, because in order for the client program to work and connect us to the virtual private network it needs a configuration file and keys - certificates. These files are responsible to give us the administrator of the [OpenVPN server](https://github.com/eellak/gsoc17-donationbox/tree/master/Virtual%20Private%20Network). Therefore, we do not have to worry about that. <br>
However, the following four files are needed:

- ca.crt
- client.**conf**
- client.**crt**
- client.**key**


So we just need to transfer these four files to the `/etc/openvpn/` directory.
This way every time our system starts, will also attempt to connect to the VPN οr if you lost the connection with the VPN, it will automatically try to recover it.

If for some reason we want to terminate our connection, it is enough to run:

`sudo service openvpn stop`

and to start the client that tries to make the connection with the OpenVPN server :

`sudo service openvpn start`

( *If he does not succeed immediately, then he will try until he succeeds it.* )


## Some utilities.
To make even better your user experience, design and implementation some scripts, which give you much better user experience and knowledge of the system status.
Specifically, they are meant to notify you when the donation box starts or when you connect to it via command line ( ssh ) if you are inside or outside of Virtual Private Network.

If you want to install these scripts in your donation box, just download the files in this directory. Once you do that, get **root privileges** and run sequentially the following commands :

 `chmod +x installation.sh`

`./installation.sh`


Now, are you ready to enjoy the full donation-box experience. :-)
