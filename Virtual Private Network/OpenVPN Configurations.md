# Virtual Private Network ~ OpenVPN Configurations.

[![ubuntu](https://img.shields.io/badge/Ubuntu-14.04%20LTS-orange.svg)](http://releases.ubuntu.com/14.04/)
[![openVPN](https://img.shields.io/badge/OpenVPN-v2.3.2-blue.svg)](https://community.openvpn.net/openvpn/wiki/ChangesInOpenvpn23#OpenVPN2.3.2)
[![coverage-100%](https://img.shields.io/badge/coverage-100%25-brightgreen.svg)](https://github.com/eellak/gsoc17-donationbox/tree/master/Virtual%20Private%20Network)

If you have read the [previous guide](https://github.com/eellak/gsoc17-donationbox/blob/master/Virtual%20Private%20Network/README.md) and install the OpenVPN and the other packages, it's time to make the necessary configurations for proper and smooth operation of the OpenVPN server, to create the Virtual Private Network.

So, get root privileges and run the following commands:

## Create the Public Key Infrastructure.

`cd /etc/openvpn/`

`mkdir easy-rsa`

`cd easy-rsa`

`cp -r /usr/share/easy-rsa/* .`

`vi vars`


and make the following changes :

```
export KEY_COUNTRY="GR"
export KEY_PROVINCE="Commons lab"
export KEY_CITY="Heraklion"
export KEY_ORG="Commons lab Team"
export KEY_EMAIL="info@commonslab.gr"
export KEY_OU="commonslab"
export KEY_NAME="DonationBoxVPN"
```
> *Note : Of course you can set your own choices.*

After these changes, save and close the text editor. So, to produce the certificate and private key, run :

`sudo su`

`cd /etc/openvpn/easy-rsa/`

`source vars`

`sh clean-all`

`sh build-ca`

> *Note : In the fields that tell us to fill in we can leave them as it is by simply pressing "enter".*

The certificate we just created is the `keys/ca.crt` file, while the private key is the `keys/ca.key`.

#### Important!
###### The above files are very important and should not be disclosed to third parties!

____

## Certificate & private key for the server.

Run the following commands:

`sh build-key-server donationBoxNetwork`

> *Note : Of course you can set your own name instead of "donationBoxNetwork".*

`sh build-dh`

The certificates and keys we have created are in the `/etc/openvpn/easy-rsa/keys` directory :

`cd keys/`

```
ca.crt
ca.key
donationBoxNetwork.crt
donationBoxNetwork.key
dh2048.pem
```
> *Note : Of course in you the `donationBoxNetwork.crt` and `donationBoxNetwork.key` files may have a different name.*

All of these files, **except the `ca.key`**, copy it them to the `/etc/openvpn` directory :

`cp ca.crt donationBoxNetwork.crt donationBoxNetwork.key dh2048.pem /etc/openvpn/`


____

## OpenVPN server configurations.

In this step, we need to configure a configuration file. The openvpn package provides us with a template for this configuration file, so let's use it :

`cd /etc/openvpn`

`cp /usr/share/doc/openvpn/examples/sample-config-files/server.conf.gz .`

`gunzip -d server.conf.gz`

Open the configuration file with a text editor such as **vi** :

`vi server.conf`

and make the following changes :

change the line :

`cert server.crt`

to :

`cert donationBoxNetwork.crt`


change the line :

`key server.key`

to:

`key donationBoxNetwork.key`


change the line :

`dh dh1024.pem`

to :

`dh dh2048.pem`


and at the end of the file add these two lines :

```
push "redirect-gateway def1"
push "dhcp-option DNS 10.8.0.1"
```

After these changes, save and close the text editor.

____

## DNS server setup for OpenVPN clients.

We also want the OpenVPN server machine to have nameserver debts for OpenVPN clients.
So, for this reason, we do the following:

`vi /etc/dnsmasq.conf`

change the line :

`#listen-address=`

to :

`listen-address=127.0.0.1, 10.8.0.1`


change the line :

`#bind-interfaces`

to :

`bind-interfaces`

After these changes, save and close the text editor. Continue with execution of the following commands :


`echo "1" > /proc/sys/net/ipv4/ip_forward`


`vi /etc/sysctl.conf`


change the line :

`#net.ipv4.ip_forward=1`

to :

`net.ipv4.ip_forward=1`

Save and close the text editor. Finally we continue with the last settings in the `/etc/rc.local` file :

`vi /etc/rc.local`

αnd before the `exit 0` line, add the following lines :
```
iptables -A FORWARD -m state --state RELATED,ESTABLISHED -j ACCEPT
iptables -A FORWARD -s 10.8.0.0/24 -j ACCEPT
iptables -A FORWARD -j REJECT
iptables -t nat -A POSTROUTING -s 10.8.0.0/24 -o eth0 -j MASQUERADE

service dnsmasq restart
```

#### Attention!
###### Observe the `eth0` in the line:
`iptables -t nat -A POSTROUTING -s 10.8.0.0/24 -o eth0 -j MASQUERADE` <br>
###### You may need to set `eth1` or `enp5s0` or anything else. Consult the name of your network interface, from the `ifconfig` command.


<br>Now restart your machine ( `shutdown -r now` ) and the OpenVPN server will should be start automatically.<br>


Now you are ready to continue with [creating users who can access to connect to your Virtual Private Network](https://github.com/eellak/gsoc17-donationbox/blob/master/Virtual%20Private%20Network/Create%20Certificates%20and%20Private%20Keys%20for%20clients.md).
