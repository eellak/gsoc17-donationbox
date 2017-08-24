# Donation-Box ~ DjangoApp

This directory contains all its files of Django App.

Specifically (probably at present) currently contains all the files of the development Django project.
The Django project consists of a app, the `presentation` app.

The application has been written and tested in `Python 3.5.2` & `Django 1.11.4`.

## How to test/run the project.
The process is very simple. First of all, you need to have the Python 3.x in your system. Then install the [Django Web Application Framework](https://www.djangoproject.com/) with the following simple command :

`pip3 install django==1.11.4`

Now you are ready to run the Django projects.

> Note: I suggest you to create a Virtual Python Environment a isolated Python environments with the help of the [virtualenvwrapper tool](https://virtualenvwrapper.readthedocs.io/en/latest/). This will probably save you from many possible problems.

First of all for our application we need to initialize the database - *remember that our application consists of a local database* -. This action will you do at first time where you will run the application.

You must always be inside the root directory of the project :

`cd Donation-Box/DjangoApp/donationProjects/`

> Note : This directory contains the following :
> * donationProjects
> * manage.py
> * presentation

(* If you have already initialized the databases, skip this step. * ) <br>
To initialize databases, run :

`python3 manage.py migrate`

`python3 manage.py makemigrations`

`python3 manage.py makemigrations presentation`

`python3 manage.py check`

`python3 manage.py sqlmigrate presentation 0001`

`python3 manage.py migrate`

Now you can run the project.
Just run :

`python3 manage.py runserver`

As your project is running, open a web browser and go to

`127.0.0.1:8000/`
or
`http://127.0.0.1:8000/project/1/`

You will see the first donation project, if you press the **left** or **right** arrow keys on your keyboard you will go to the next or previous donation project.

> Remember that: The projects you see in the Django web application **loaded from the local database**.

Also, once a donation is given is responsible for displaying it to the user.
