# Donation-Box ~ OpenVPN Client

This directory contains all necessary settings and instructios for OpenVPN client.

We have chosen for *secure, reliable, and scalable communication services* reasons, each of the donation boxes is always within the virtual private network ( *VPN* ) and therefore can be connected to the remote database.

Note: Of course it is expected that the donation box sometimes is offline, not have access to the internet and therefore cannot connect to the virtual private network.
For this reason, we also maintain the local database. ;)
That's why we take care of that, In case you can not connect to the virtual private network to try repeatedly to connect at regular intervals.

To be able to connection to the VPN, we need to have a client program (daemon) to take care of this connection. Us to implement the virtual private network
we use ( on the part of the server ) the [OpenVPN](https://openvpn.net/index.php/open-source.html). So we will also use the corresponding client for this.

##Install OpenVPN client.
If we use a GNU/Linux operating system the installation of the OpenVPN client is simple. Get system administrator permissions and execute its installation command of the OpenVPN. Below are some examples of the most known GNU/Linux distributions.

- Debian/Ubuntu:
`apt-get install openvpn`

- Fedora/CentOS/RedHat:
`yum install openvpn`


Once the installation is over, we are almost ready to use the OpenVPN client.
We are almost ready and not fully, because in order for the client program to work and connect us to the virtual private network χit needs a configuration file and certificates - credentials. These files are responsible to give us the administrator of the OpenVPN server. Therefore, we do not have to worry about that.
However, the following four files are needed:
- client.crt
- client.key
- ca.crt
- client.conf


So we just need to transfer these four files to the `/etc/openvpn/` directory.
This way every time our system starts, will also attempt to connect to the VPN οr if lost the connection to with the VPN, it will again automatically try to raise it.

If for some reason we want to terminate our connection, it is enough to run:

`sudo service openvpn stop`

and to start the client that tries to make the connection with the OpenVPN server :

`sudo service openvpn start`

( If he does not succeed immediately, then he will try until he succeeds )
