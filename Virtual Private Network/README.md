# Virtual Private Network.

[![ubuntu](https://img.shields.io/badge/Ubuntu-14.04%20LTS-orange.svg)](http://releases.ubuntu.com/14.04/)
[![openVPN](https://img.shields.io/badge/OpenVPN-v2.3.2-blue.svg)](https://community.openvpn.net/openvpn/wiki/ChangesInOpenvpn23#OpenVPN2.3.2)
[![coverage-100%](https://img.shields.io/badge/coverage-100%25-brightgreen.svg)](https://github.com/eellak/gsoc17-donationbox/tree/master/Virtual%20Private%20Network)

In this guide we will describe how you can create your own Virtual Private Network.


# The idea.
One of the main features of the Donation-Box network is the communication of donation boxes with the remote database, so that they can be synchronized with it.
A main concern is that this communication to is as secure as possible. That's why we've decided that all donation boxes will belong to the same Virtual Private Network along with the central database.

> Note : An additional feature that the virtual private network gives us, is that when a machine participates in it, it has secure access to resources and services of a remote local network ( no matter where he is and regardless of whether he uses secure connection or not ). <br> <br>
The Virtual Private Network always offers us a secure and encrypted direct access to the resources of a remote LAN.

#### Important!
###### For the above reasons, the OpenVPN server should be installed on the same machine that is also installed the central database of the donation-box network.


| ![donationbox_network](https://raw.githubusercontent.com/eellak/gsoc17-donationbox/master/Virtual%20Private%20Network/donation-box_network.png) |
|:--:|
| The Donation-Box Network. |

We in this guide show how to install and configure the **[OpenVPN](https://openvpn.net/)**.

The OpenVPN is an open source software that implements a Virtual Private Network **[server](https://openvpn.net/index.php/open-source/documentation/howto.html)**, and also can provide and the role of a Virtual Private Network **[client](https://openvpn.net/index.php/open-source/downloads.html)**.

To avoid the confusion of a very large guide, we divide the process into three smaller parts, these is are :
* [Installation of the OpenVPN and other individual useful packages](https://github.com/eellak/gsoc17-donationbox/tree/master/Virtual%20Private%20Network)
* [OpenVPN Configurations](https://github.com/eellak/gsoc17-donationbox/blob/master/Virtual%20Private%20Network/OpenVPN%20Configurations.md)
* [Create Certificates & Private Keys for clients](https://github.com/eellak/gsoc17-donationbox/blob/master/Virtual%20Private%20Network/Create%20Certificates%20and%20Private%20Keys%20for%20clients.md)

This guide has been implemented and tested on [![ubuntu](https://img.shields.io/badge/Ubuntu_Server-14.04%20LTS-orange.svg)](http://releases.ubuntu.com/14.04/) with [![openVPN](https://img.shields.io/badge/OpenVPN-v2.3.2-blue.svg)](https://community.openvpn.net/openvpn/wiki/ChangesInOpenvpn23#OpenVPN2.3.2)

The following instructions are a brief guide to installing and configuring the OpenVPN. There is not a detailed guide. We do not intend to explain in depth the concepts, οur goal is to help you build a Virtual Private Network, presenting the basic steps. If you want to learn in depth the steps, we initially suggest you start reading the [official guide of OpenVPN](https://openvpn.net/index.php/open-source/documentation/howto.html).



## Installation of the OpenVPN and other individual useful packages.

So let's start by installing the necessary packages. We will need three packages :
* **openvpn** : The main package of OpenVPN software.
* **easy-rsa** : Includes convenient scripts for creating keys.
* **dnsmasq** : The software package who will run the nameserver debts for OpenVPN server clients.

To install the above packages on our Ubuntu Server, get root privileges and run the following command:

`sudo apt-get install openvpn easy-rsa dnsmasq`

So simple, now you have everything you need to go on.

Continue with [configurations](https://github.com/eellak/gsoc17-donationbox/blob/master/Virtual%20Private%20Network/OpenVPN%20Configurations.md).


---------

## Automate the process. [![Future](https://img.shields.io/badge/Future-Work-red.svg)](https://github.com/eellak/gsoc17-donationbox/tree/master/Virtual%20Private%20Network)

I think it would be nice to create a more user-friendly interface for all these process and configurations, through which, many of them configurations are will be made automatically and the user  will not deal so much with these.

Also, could be explored the selection of the [OpenVPN Access Server](https://openvpn.net/index.php/access-server/overview.html), which can facilitate the process.
