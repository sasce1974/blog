
<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

##  Laravel - Blog

<h3>Installation</h2>

Before starting with this project, the following dependencies are REQUIRED:
<ul>
    <li>composer</li>
    <li>node.js</li>
    <li>server</li>
    <li>PHP v.7.2.5 or later</li>
    <li>Database (MySQL)</li>
</ul>
This project can be downloaded from the Github repository. If you are usin Git on your CLI, the pull request CAN be done with the following line:

	git clone https://github.com/sasce1974/blog.git

After the project is downloaded locally, additional program dependencies for the Laravel project MUST be installed. Please run into your CLI:

	composer install

And:

	npm install 
	npm run dev

Create a copy from the .env.example file from the root folder of the project and name it .env
Can be done it in the CLI with the command:

	cp .env.example .env

The .env file (environment file) contains all the basic configurations for the program to run. 
As the application is using "Verify email" functionality (All users after registering will receive 
an email with verification link), Mail SHOULD be set up in the .env file for sending emails. For testing 
convenience, 'verify' middleware is not been used.

Generate an App Encryption Key:

	php artisan key:generate

This will create an app key string into the APP_KEY setting of the .env

Create empty database for the application.

Add the details for the database connection to the .env file <i>(Host, database name, username and password)</i>

Migrate the database with the following code in your CLI:

	php artisan migrate

This WILL generate all the tables in the created database.

The artisan command for running the seeder is implemented into
the 'photos' table migration file to be executed along after the 
table migration.

#####Database will be automatically seeded with data.
#####There will be one admin user created 
with username: <b>admin@email.com</b> and password: 
<b>password</b>. 
Please use this account to login as an admin user
into the application. All other generated users for
testing convenience will have the same password 
("password"); 


Admin users can assign roles to other users and to itself trough the profile interface.

Create symlink to the storage space for the images:
    
    php artisan storage:link

With this, the Blog application is ready to be used on the local machine.
