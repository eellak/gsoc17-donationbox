# Virtual Private Network ~ Create Certificates & Private Keys for clients.

[![ubuntu](https://img.shields.io/badge/Ubuntu-14.04%20LTS-orange.svg)](http://releases.ubuntu.com/14.04/)
[![openVPN](https://img.shields.io/badge/OpenVPN-v2.3.2-blue.svg)](https://community.openvpn.net/openvpn/wiki/ChangesInOpenvpn23#OpenVPN2.3.2)
[![coverage-100%](https://img.shields.io/badge/coverage-100%25-brightgreen.svg)](https://github.com/eellak/gsoc17-donationbox/tree/master/Virtual%20Private%20Network)

Since we have [installed all the necessary packages](https://github.com/eellak/gsoc17-donationbox/blob/master/Virtual%20Private%20Network/README.md) and we have proceeded to the [configuration of them](https://github.com/eellak/gsoc17-donationbox/blob/master/Virtual%20Private%20Network/OpenVPN%20Configurations.md), it's time to create users who can connect to the Virtual Private Network. In particular, we create certificates and private keys with which a user will request access to the Virtual Private Network.

How the connection is made from a user, [reference it here](https://github.com/eellak/gsoc17-donationbox/tree/master/Donation-Box/OpenVPN%20Client).


## Create certificates and private keys for the users of the OpenVPN.
We create the certificates and private keys for the users of the Virtual Private Network. Users give these certificates to the client who is responsible for making the connection to the Virtual Private Network.

Our purpose here is to create the credentials and give them to the user. So, get root privileges and run the following commands:

`cd /etc/openvpn/easy-rsa`

`source vars`

`./build-key donationBox_1`

> Note : We leave all the fields with their optional values and, in particular, we **do not set a password!** <br> Also, we respond positively to all questions.


The script created a (`donationBox_1.crt`) certificate and a private key (`donationBox_1.key`) for the client, which they stored in the `/etc/openvpn/easy-rsa/keys` directory.

then copy the following files :
* **ca.crt** : the CA certificate
* **donationBox_1.crt** : the certificate of `donationBox_1`
* **donationBox_1.key** : the private key of `donationBox_1`

in another directory, to transfer these three files to the client.<br>

> Essentially we are ready now. With the above so simple way we create the basic files that a user needs to connect to the virtual private network.
However, these files are in a directory where a simple user does not have access. So, we will move them to a directory where the simple user has access, and we will also add a configuration file.


`mkdir -p /home/user/vpn_client_files_for_donation_boxes/donationbox1`

> *Note : Where `user`, set your own username of the system, and the subdirectory where you will put the files is also your choice, in my case i used the `vpn_client_files_for_donation_boxes/donationbox1`.*

`cd /etc/openvpn/easy-rsa/keys/`

`cp ca.crt  donationBox_1.crt  donationBox_1.key /home/user/vpn_client_files_for_donation_boxes/donationbox1/`


and correct the privileges in this directory :


`chown -R user:user /home/user/vpn_client_files_for_donation_boxes/donationbox1/`


## Set a configuration file for the client.

In the directory where previously transfer the files for the client we must also make a properly configured configuration file. The openvpn package provides us with a template for this configuration file, so we will use it.<br>
Run the following commands *without* root privileges :


`cd vpn_client_files_for_donation_boxes/donationbox1/`


`cp /usr/share/doc/openvpn/examples/sample-config-files/client.conf .`


`vi client.conf`

and make the following changes :

change the line :

`remote my-server-1 1194`

and set where `my-server-1` the IP address of your machine or the domain name that corresponding to the IP address of your machine. To find your public, visit pages like `http://www.whatsmyip.org/` and set for example something like that :

`remote 216.58.205.241 1194`

change the line :

`cert client.crt`

to :

`cert donationBox_1.crt`

change the line :

`key client.key`

to :

`key donationBox_1.key`

So now you have the following four files ready for use :
```
ca.crt
client.conf
donationBox_1.crt
donationBox_1.key
```

All you have to do is give them to the user who wants to access the network, in our case, in every donation box.

#### Important Note!
###### Each donation box should be have its own files!

<br>Repeat this steps as many times as you want, to create as many users as you want for your virtual private network.

The settings that should be made on the side of the donation box are described [here](https://github.com/eellak/gsoc17-donationbox/tree/master/Donation-Box/OpenVPN%20Client).
