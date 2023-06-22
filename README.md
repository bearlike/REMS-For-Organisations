<h1 align="center">
  <br>
  <img src="https://cdn.thekrishna.in/img/common/rems.png" alt="CMS For Organisations" height="250px">
  <br>
</h1>
<h4 align="center">Efficiently manage resources and events with ease. Our system includes a Form Generator, Mailer, Certificate Generator, and more – perfect for small organizations and clubs!</h4>

<p align="center">
  <img src="https://img.shields.io/github/last-commit/bearlike/REMS-For-Organisations?color=blue&style=flat-square">
  <a href="/LICENSE"><img src="https://img.shields.io/github/license/bearlike/REMS-For-Organisations.svg?style=flat-square"></a>
  <a href="https://hub.docker.com/r/krishnaalagiri/rems"><img src="https://img.shields.io/docker/image-size/krishnaalagiri/rems/latest?label=Image%20Size&logo=docker&style=flat-square" /></a>
   <a href="https://github.com/bearlike/REMS-For-Organisations/issues"><img src="https://img.shields.io/github/issues-raw/bearlike/REMS-For-Organisations?color=red&style=flat-square"/></a>
  <a href="https://github.com/bearlike/REMS-For-Organisations/releases"><img src="https://img.shields.io/github/v/tag/bearlike/REMS-For-Organisations?label=stable&style=flat-square"/></a>
</p>

> *This repository is currently not actively maintained due to our team's commitments elsewhere. :speech_balloon: We're seeking open-source contributors who can lend a hand! If you're interested in contributing, don't hesitate. Every bit of help makes a difference. :sparkles:*

# Introduction
Looking to streamline your organization's event planning process? REMS can help you! Originally created for our college chapter, we've automated the tedious task of creating forms, certificates, and advertising via mail for our 20+ events with 1500+ participants. Now, any organization, club, or institution can easily fork our project and customize it to fit their unique needs. Join the thousands who have already benefited from our service and give it a try today!

## Getting Started

### Prerequisites
What things you need to run the software:
- A **web server** with **PHP** preferably Apache2.
- A **MySQL Database Server**. (Done and tested on 10.4.8-MariaDB)
- PHP Extensions [`pdo_mysql`](https://www.php.net/manual/en/ref.pdo-mysql.php), [`xdebug`](https://xdebug.org/docs/install), and [`gd`](https://www.php.net/manual/en/image.installation.php) needs to be installed.


### Installation
1. Download the latest stable release from [here](https://github.com/bearlike/REMS-For-Organisations/releases)
2. Create and Import the Main Database dump for  **MySQL-MariaDB** from [here](/docs/files/Sample_REMS_Database.sql) 
3. Create a Forms Database.
4. Copy the files from this repository to a location in the root directory of the web server
5. Rename `member/secrets.php_` to `member/secrets_.php`
6. Update your database credentials, databases names (Main and Forms) and API Keys in `member/secrets.php` and `public/cds-public.php`
7. With your Web Server and MySQL server running, visit the site
   - ```
      Default Username: admin
      Default Password: admin
     ```
8. If any error occurs, check your configurations in `member/secrets.php` and `public/cds-public.php` and try again


### Running REMS as a Docker stack (Experimental).
- Installation guide for [Docker](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-on-ubuntu-22-04) and [docker-compose](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-compose-on-ubuntu-22-04).
- Modify the default values in `docker-compose.yml`, `docker/mysql/database.sql` and `docker/secrets_.php` to your requirement. 
- By default, exposes to host port `8080`. A `krishnaalagiri/rems` and `mariadb` container would be configured and deployed.
- For only the first time, the stack needs to be restarted due `initdb` in database containers. From the second time, you can directly start the stack.
```bash
# From the root of the repository
docker-compose up -d
sleep 60
docker-compose restart
```


## Features and Screenshots (Click to enlarge)

```
Tested on Apache/2.4.41 (Win64) OpenSSL/1.1.1c PHP/7.3.11 with 10.4.8-MariaDB
```

### Certificate Generation and Distribution System (CGDS)

A two-ended system (Both for **admin** and **public**) that'll automatically  generate certificates and make them available for distribution. The admin will have to upload a **Certificate Template** and a **CSV file** with Participant Names, Position Awarded, and Event Name. The generated certificates would be later automatically made for distribution.

| CDS Public (Collection)                                  | CDS Admin (Generation)                                   | Generated Certificates                                   |
| -------------------------------------------------------- | -------------------------------------------------------- | -------------------------------------------------------- |
| <img src="https://i.imgur.com/gaMfSOr.png" width="650"/> | <img src="https://i.imgur.com/APt8LDL.png" width="650"/> | <img src="https://i.imgur.com/av6OHek.png" width="650"/> |

### Database Management
This is an interface for the databases that are used. The administrator can update, insert and delete values from any table of the databases without having to risk cluttering with the structure or format. The interface is made as generic possible to support future development and extensions.

| Database Manager                                         | Modify Tuples                                            | If Error Occurs                                          |
| -------------------------------------------------------- | -------------------------------------------------------- | -------------------------------------------------------- |
| <img src="https://i.imgur.com/lRIwQna.png" width="650"/> | <img src="https://i.imgur.com/696Ipzy.png" width="650"/> | <img src="https://i.imgur.com/li7c2zi.png" width="650"/> |

### Form Generation

This tool is used to generate forms for events. Initially, the specifications of the event must be selected and a form is generated with the designated fields and a **table is automatically created in `forms-db`**. The form also has **built-in validation** for all the fields (emails, URLs, etc) and **Markdown support** for event descriptions. Once the form is sent out, the entered values are updated in the database. 

| Form Generator                                           | Sample Generated Form                                    |
| -------------------------------------------------------- | -------------------------------------------------------- |
| <img src="https://i.imgur.com/F7Dci7Z.png" width="650"/> | <img src="https://i.imgur.com/RnTQ3Jq.png" width="650"/> |

### Bulk Mailer

The mailer can send automatically send emails to a specific mailing list. It supports HTML emails and comes with a pre-designed template. The parameters for the pre-defined template can be modified for the specifications of the organization.  There is also a feature to create mailing lists to use with these mailers. A CSV of the emails and names has to be uploaded ana a mailing list is created.  

| Bulk Mailer Interface                                    | Mailing List Generator                                   | Sample Sent Mail                                         |
| -------------------------------------------------------- | -------------------------------------------------------- | -------------------------------------------------------- |
| <img src="https://i.imgur.com/R2RJOvu.png" width="650"/> | <img src="https://i.imgur.com/NOEJiH4.png" width="650"/> | <img src="https://i.imgur.com/OvPolMF.png" width="650"/> |

### View Responses

To view all the Responses for generated forms with an option to download the responses as a `CSV`. 

| View Form Responses                                      |
| -------------------------------------------------------- |
| <img src="https://i.imgur.com/8HfJ5rF.png" width="500"/> |

### Link Shortener

A link shortener that uses the `short.io` API to render shortened links for distribution. It can make shortened link either with a custom slug or can automatically generate slugs for links.

| Link Shortener with short.io API                          |
| --------------------------------------------------------- |
| <img src="https://i.imgur.com//LB4QqoL.png" width="550"/> |

### Dark Mode :crescent_moon:

Perhaps our most desired feature, it gives an option to toggle the page between dark mode and light mode. Saves your eyes in the night :eyes:

|                    Dark Mode Preview                     |
| :------------------------------------------------------: |
| <img src="https://i.imgur.com/itX7cW9.gif" width="550"/> |


## Got Issues?
If you're new to the project and run into any blockers, please open an issue on this repository. We'd love to get it fixed for you! Please use the appropriate issue template as it would help us understand the issue faster.


## Contributors 🌟
> ✨ Looking to contribute to our GitHub project? We'd love to have you on board! Feel free to raise issues and pull requests for bug fixes, new features, and improvements. ✨

| Krishnakanth Alagiri 👨‍💻                                                                         | Mahalakshumi V 👩‍💻                                                                                   | Dhiraj V                                                                                        |
| ----------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------- |
| [![f](https://avatars1.githubusercontent.com/u/39209037?s=86)](https://kanth.tech/github/) | [![f](https://avatars2.githubusercontent.com/u/40058339?s=86)](https://github.com/mahavisvanathan) | [![f](https://i.imgur.com/KqeCBQX.jpg)](https://github.com/dhirajv2000)                         |
| [@bearlike](https://kanth.tech/github/)                                                    | [@mahavisvanathan](https://github.com/mahavisvanathan)                                             | [@dhirajv2000](https://github.com/dhirajv2000)                                                  |

## Acknowledgments
* Hat tip to anyone whose code was used.




![wave](http://cdn.thekrishna.in/img/common/border.png)
