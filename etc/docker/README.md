# Description
This Docker Compose development environment includes

* PHP 7.0
* MariaDB
* Nginx 
* Composer

# Usage

First you need to install Docker and Docker Compose.

      cd etc/docker
      docker-compose up

Next, You need to know ipAddress of your website. 

      docker-machine ip default

Now you have a few options to get started

## Basic

Get the ip of the Nginx container.

      docker inspect $(docker-compose ps -q nginx) | grep IPAddress

## Advanced

Run a `dnsdock` container before `docker-compose up`, more info: https://github.com/tonistiigi/dnsdock
Access the containers from the dns records.

# Troubleshooting

## How to enter a container?

Enter the php container to install composer vendors etc.

      docker exec -it $(docker-compose ps -q php) bash

## The application is too slow.

Install composer vendors in the container and symlink them to the application directory.
Execute inside the php container:

      mkdir /vendor && ln -sf /vendor ./vendor

Using Symfony2 inside Vagrant can be slow due to synchronisation delay incurred by NFS.
You can write the app logs and cache to a folder on the php container.

Enter the php container and create the directory:

      docker exec -it $(docker-compose ps -q php) bash
      mkdir /dev/shm/jdj/
      setfacl -R -m u:"www-data":rwX -m u:`whoami`:rwX /dev/shm/jdj/
      setfacl -dR -m u:"www-data":rwX -m u:`whoami`:rwX /dev/shm/jdj/

To view the application logs, run the following commands:

      tail -f /dev/shm/jdj/app/logs/prod.log
      tail -f

