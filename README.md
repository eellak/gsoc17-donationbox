# DonationBox Network.
## Google Summer of Code 2017.
[![License: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)
[![MariaDb](https://img.shields.io/badge/Database-MariaDB-red.svg)](https://mariadb.org/)
[![SQLite3](https://img.shields.io/badge/Database-SQLite3-brightgreen.svg)](https://www.sqlite.org/)
[![python3](https://img.shields.io/badge/Python-3.x-blue.svg)](https://www.python.org/downloads/)
[![django1.11](https://img.shields.io/badge/Django-1.11.4-green.svg)](https://docs.djangoproject.com/en/1.11/releases/1.11.4/)
[![djangoApp](https://img.shields.io/badge/DjangoApp-v1-orange.svg)](https://github.com/eellak/gsoc17-donationbox/tree/master/Donation-Box/DjangoApp/donationProjects/presentation)
[![PHP](https://img.shields.io/badge/PHP-v5.6-blue.svg)](https://secure.php.net/releases/5_6_0.php)
[![HTML5](https://img.shields.io/badge/HTML-5-red.svg)](https://www.w3.org/TR/html5/) [![CSS3](https://img.shields.io/badge/CSS-3-blue.svg)](https://www.w3.org/Style/CSS/Overview.en.html) [![JavaScript](https://img.shields.io/badge/Java-Script-yellow.svg)](https://www.javascript.com/) [![shell](https://img.shields.io/badge/other-Shell-orange.svg)](https://en.wikipedia.org/wiki/Shell_script)
[![ubuntu](https://img.shields.io/badge/Ubuntu-14.04%20LTS-orange.svg)](http://releases.ubuntu.com/14.04/)
[![openVPN](https://img.shields.io/badge/OpenVPN-v2.3.2-blue.svg)](https://community.openvpn.net/openvpn/wiki/ChangesInOpenvpn23#OpenVPN2.3.2)
[![coverage-95%](https://img.shields.io/badge/coverage-95%25-brightgreen.svg)](https://github.com/eellak/gsoc17-donationbox/tree/master/Donation-Box)

[View the project on Google Summer of Code website.](https://summerofcode.withgoogle.com/projects/#5171214440988672)

## Description of the Donation-Box Project.
The main purpose of the project is to modernize the so called donation boxes, to keep pace in the new digital era.
This will achieve by implementing all the necessary infrastructure to create ultimately a decentralized (_from specific donations boxes_) network, where all donation boxes are interconnected and can be managed from a central node. Doing the **modern** donation box, actually.


# What I have done.
To achieve this decentralized system, and we can manage the donation boxes ( *at least initially in part* ) remotely, we designed and implemented the following parts of the **DonationBox Network**:

* [A plugin for the WordPress CMS](https://github.com/eellak/gsoc17-donationbox/tree/master/plugins). Which allows the user to import data which can be synchronized with the central database of the DonationBox Network.

* [The central database of the DonationBox Network](https://github.com/eellak/gsoc17-donationbox/tree/master/Database), and the necessary infrastructure to be able to receive data from the main WordPress page.

* [The way in which the donation projects will be presented in the donation box](https://github.com/eellak/gsoc17-donationbox/tree/master/Donation-Box).

* The [Virtual Private Network](https://github.com/eellak/gsoc17-donationbox/tree/master/Virtual%20Private%20Network) and who is installed on the server where the central database is also installed. All donation boxes will be connected to this network.

[![Commits](https://img.shields.io/badge/Commits-200-blue.svg)](https://github.com/eellak/gsoc17-donationbox/commits?author=Tas-sos)


# What’s left to be done.
The main parts and basic functionalities that the system has to perform have been implemented. What has not been fully decided, is the central database. Perhaps it should be enriched with further information which will increase the capability and flexibility of the  DonationBox network.

However, in all the above parts of the system, which are explained in detail, you will notice my suggestions for ![Future](https://img.shields.io/badge/Future-Work-red.svg).




# Do you want to contribute?
If you like this project and do you want to contribute to it, a good starting point is to read [this guide](https://github.com/eellak/gsoc17-donationbox/blob/master/Documentation/Set%20up%20a%20developer%20workspace%20environment%20for%20the%20development%20of%20the%20WordPress.pdf).
It's a small guide to how to create your own workspace.
We hope to help you and we will try to upgrade it if you find it helpful.

In any case, we are open to your suggestions!



# Conclusion.
Working on Google Summer of Code was an amazing and unique experience.
I need to thank both Diomidi Spinelli and Dimitri Koukoulaki for mentoring me on this project. With their comments on my code, you made this project even more enjoyable and interactive. I think this is one of the most beautiful features of open source. I also want to thank the Open Technologies Alliance - GFOSS community which I had the pleasure to working with her. Finally, I would like to thank and congratulate Google for the **Google Summer of Code**, and the experience it offers us.

Thank you.

-----

## Timeline.

* May 5 - 19 [ Identifying, understanding and integration in the existing system. ]

* May 20 - 21 [ Installation of infrastructure & start design Wordrees Plugin. ]

* June 1-30 [ Implementation of Wordpress Plugin ]

* July 1 - 12 [ Implementation of API for sending data ]

* July 13 - 24 [ Implementation of API for receiving data ]

* July 25 - 28 August [ Implementation of website ]

* ##### August 29 [ Finish ]

See the detailed timeline [here](https://github.com/eellak/gsoc17-donationbox/blob/master/timeline.md).




#### Mentors :

 * [Diomidis Spinellis](https://github.com/dspinellis)

 * [Dimitris Koukoulakis](https://github.com/dkoukoul)

#### Student :
* Anastasios Lisgaras

#### Organization : [Open Technologies Alliance - GFOSS](https://summerofcode.withgoogle.com/organizations/4825634544025600/)
