# WordPress Plugin

[![License: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)
[![PHP](https://img.shields.io/badge/PHP-v5.6-blue.svg)](https://secure.php.net/releases/5_6_0.php)
[![HTML5](https://img.shields.io/badge/HTML-5-red.svg)](https://www.w3.org/TR/html5/) [![CSS3](https://img.shields.io/badge/CSS-3-blue.svg)](https://www.w3.org/Style/CSS/Overview.en.html) [![JavaScript](https://img.shields.io/badge/Java-Script-yellow.svg)](https://www.javascript.com/)
[![coverage-95%](https://img.shields.io/badge/coverage-95%25-brightgreen.svg)](https://github.com/eellak/gsoc17-donationbox/tree/master/plugins)


One of the main targets of the new donation-box network/system is the easy centralized access and management of all donation projects from a central website. We do this by implementing an plugin for the successful and mature [WordPress CMS](https://wordpress.org/).
Specifically, the plugin has been implemented and tested on [![WordPress](https://img.shields.io/badge/WordPress-v4.8.1-blue.svg)](https://wordpress.org/news/2017/08/wordpress-4-8-1-maintenance-release/).

This directory contains the WordPress plugin.


# What it offers now.
At this time, the add-on offers the following features.

## The **Project Creator** users.
It offers to the page administrator the ability to add users with specific capabilities. Users who will be responsible for creating their own donation projects.

| ![project_creator](https://i.imgur.com/RR2azL1.png) |
|:--:|
| From the WordPress Users Menu. |

Only these users (beyond the administrator), have the capability to create new donation projects. They also have partial access to the "Donation Boxes" menu. They have **only** the following capabilities :
* View the list with all donation projects.
* Create donation projects.
* They can edit **only** the donation projects they have created itself.


## The **Donation Boxes** menu.
For easy, user-friendly management of the features offered by the add-on, we created a menu on the left sidebar, where it contains all the features offered by the add-on.

| ![Donation Boxes menu](https://i.imgur.com/KdbkWzC.png) |
|:--:|
| The Donation Boxes Menu from the system administrator Dashboard. |

To display this menu on the WordPress dashboard administrator page , you must first install the add-on.
For the submenus that you see, we will expand more below.

Keep it in your mind, from this menu, the actions of the WordPress administrator or from the responsible users, will have universal reach across the donation-box network/system.


## The **All Donation Projects** submenu.
From this menu you will be able to see all the donation projects that have been created, and basic information about them.
Also every time that this page is loaded, update int the database of WordPress and appears to you the current amount of money gathered for each donation project.

When a donation project is deleted, you also delete it from the [central database](https://github.com/eellak/gsoc17-donationbox/tree/master/Database).
To delete a donation project, just click "Move to trash".


## The **Add New Project** submenu.
From this menu you can create new donation projects with the following attributes :
* **Title** (*required*) : The title of the donation project.

* **Content** : A small text referring to the donation project.

* **Start date** ( *required* ) : Since when this donation project will be active.

* **End date** ( *required* ) : When will the project expire.

* **Status** : Set a current status for this project (Active or Not).

* **Amount target** ( *required* ) : Set the amount that is intended to collect this project.

* **Organization to which it belongs** ( *required* ) : Set a organization to which this project will belong.

* **Picture** ( *required* ) : Set a image that will frame the presentation of the donation project.

* **Video** : Set a video that will frame the presentation of the donation project.

* **Stylesheet file** : Να ορίσετε το δικό σας αρχείο με το οποίο θα επιρεάζετε την εμφάνιση του έργου δωρεάς.

It also gives you the opportunity to see the current amount of money collected for this project ( *but you can not change it* )

Moreover, you can preview the project to see how it will be presented in the donation box.

When creating or updating a donation project, you also update the [central database](https://github.com/eellak/gsoc17-donationbox/tree/master/Database). When you delete a donation project, also deleted from the [central database](https://github.com/eellak/gsoc17-donationbox/tree/master/Database).


## The **Organization** submenu.
From this page you can manage the organizations to which a donation project may belong. In each organization, beyond his name ( *required* ), you can also give a short description about it.

When you insert, update, or delete an organization, this action is also performed in the [central database](https://github.com/eellak/gsoc17-donationbox/tree/master/Database).


## The **General Settings** submenu.
This menu is very important, because through it it is defined which is the central database of the donation-box network/system.

**The WordPress administrator, should after the installation of the plugin, to complete the fields of this menu.**

You must give the credentials of the user, who on the database side, is the one that is [accepted from the index.php page](https://github.com/eellak/gsoc17-donationbox/tree/master/Database#basic-settings-for-proper-operation-of-the-php-script), and has the appropriate privileges to the [central database](https://github.com/eellak/gsoc17-donationbox/tree/master/Database).

The **Database URL** field is very important because it determines where the database is located. In this you should fill in the public IP address of the machine where the database is installed( or a domain which corresponds to this ).

For example :
`http://donationbox.database.org/` or `http://216.58.205.177`


| ![project_creator](https://i.imgur.com/AFKHfQp.png) |
|:--:|
| The "General Settings" submenu of the "Donation Boxes" menu. |


> Note : You may also have to append the `/index.php` at the end. Depending on the settings they have made on the [Apache web server](https://github.com/eellak/gsoc17-donationbox/tree/master/Database#manual-installation).

When you submit the page, the WordPress will automatically check the credentials you provided and let you know if it is correct or not.

#### Notify and prompt the user to fill out this page. [![Future](https://img.shields.io/badge/Future-Work-red.svg)](https://github.com/eellak/gsoc17-donationbox/tree/master/plugins)
I would suggest once the add-on is installed, display a notifce to the user to visit this page, and set the necessary settings or even better redirect them to this page automatically.


## The **Non-synchronized Donation Projects** submenu.
This menu display all the actions done in WordPress regarding donation projects, and failed to synchronize with the remote database. WordPress will automatically sync this actions with the remote database. This page is for informing the WordPress administrator, so that he knows at all times what happening with synchronization with the remote central database.

Do not forget the purpose of the central WordPress page, and especially the add-on that we are developing, is to offer a user-friendly way to manage their donation projects of the central database. So, what happens on the WordPress page must be synchronized with the central database.

#### Important!
###### Donation boxes are updated through the central database, not through the WordPress page.


---------


## The Donation-Box WordPress REST API.
We also created a WordPress endpoint REST API so can someone from the WordPress page request information about donation projects and take them in **JSON** format.
Let's look at the three possibilities offered by REST API through simple examples.

* `http://localhost/wp-json/wp/v2/donationboxes` : Root, display all relevant information about donationbox WordPress REST API.

* `http://localhost/wp-json/wp/v2/donationboxes/projects` : Return all active donation projects ( id & title ).

* `http://localhost/wp-json/wp/v2/donationboxes/108` : Return all informations about the donation project with id = 108.

* `http://localhost/wp-json/wp/v2/donationboxes/updated/2017-06-18/18:38:33`: Return the donation projects which were modified after that datetime ( `2017-06-18 18:38:33` ).


#### Make use of this feature. [![Future](https://img.shields.io/badge/Future-Work-red.svg)](https://github.com/eellak/gsoc17-donationbox/tree/master/plugins)
Could be built software which take advantage of this easy access to the data. For example, applications for smart phones.


----------

# How to install.
The installation the plugin is very easy. Because this directory contains the WordPress plugin ( `donationBox` directory). <br>
Just add it to your system at the directory :
`wordpress/wp-content/plugins/` <br>
<br> for example :
`wordpress/wp-content/plugins/donationBox`

After that, the plugin will appear on the plugins page ( `/wp-admin/plugins.php` )

![DonationBoxPlugin](https://i.imgur.com/5LmI64T.png)

and to activate, just click "**Activate**".

| ![DonationBox_plugin](https://i.imgur.com/al0KIld.png) |
|:--:|
| The "DonationBox" plugin on the "Plugins" menu. |




Once activated, you will see the "**Donation Boxes**" menu on the left sidebar of the dashboard.
<br> <br> <br>


----------

#### Host the plugin on the official WordPress.org directory.. [![Future](https://img.shields.io/badge/Future-Work-red.svg)](https://github.com/eellak/gsoc17-donationbox/tree/master/plugins)
Once the addon have been fully stabilized, he could also be available on the [official WordPress page](https://wordpress.org/plugins/). In this way installation and maintenance would be much easier.
