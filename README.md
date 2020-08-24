<h1 align="center">
  <br>
  <img src="https://i.imgur.com/w5PZAuO.png" alt="CMS For Organisations" height="250px">
  <br>
</h1>
<h4 align="center">Responsive Resources and Event Management System for small organisations and clubs. Form Generator, Mailer, Certificate Generator and much more :)</h4>

<p align="center">
  <img src="https://img.shields.io/github/last-commit/K-Kraken/REMS-For-Organisations?color=blue&style=flat-square">
  <a href="/LICENSE"><img src="https://img.shields.io/github/license/K-Kraken/REMS-For-Organisations.svg?style=flat-square"></a>
  <a href="https://github.com/K-Kraken/REMS-For-Organisations/issues"><img src="https://img.shields.io/github/issues-raw/K-Kraken/REMS-For-Organisations?color=red&style=flat-square"/></a>
    <a href="https://github.com/K-Kraken/REMS-For-Organisations/releases"><img src="https://img.shields.io/github/v/tag/K-Kraken/REMS-For-Organisations?label=stable&style=flat-square"/></a>
</p>






### Why are we doing it?
We are running a chapter (club) at our college. We organize 20+ events for our 1500+ participants. It was a tedious process to manually make forms, certificates, advertising via mail so we decided to automate that process. Any organization, clubs or institutions looking for a similar service can fork our project and tweak it according to their needs.



## Getting Started

### Prerequisites
What things you need to run the software:
- A **web server** with **PHP** preferably Apache2.
- A **MySQL Database Server**. (Done and tested on 10.4.8-MariaDB)



### Installation
1. Download the latest stable release from [here](https://github.com/K-Kraken/REMS-For-Organisations/releases)
2. Create and Import the Main Database dump for  **MySQL-MariaDB** from [here](/docs/files/Sample_REMS_Database.sql) 
3. Create a Forms Database.
4. Copy the files from this repository to a location in the root directory of the web server
5. Rename `member/secrets.php_` to `member/secrets.php`
6. Update your database credentials, databases names (Main and Forms) and API Keys in `member/secrets.php` and `public/cds-public.php`
7. With your Web Server and MySQL server running, visit the site
   - ```
      Default Username: admin
      Default Password: admin
     ```
8. If any error occurs, check your configurations in `member/secrets.php` and `public/cds-public.php` and try again



## Features and Screenshots (Click to enlarge)

```bash
Tested on Apache/2.4.41 (Win64) OpenSSL/1.1.1c PHP/7.3.11 with 10.4.8-MariaDB
```

### Certificate Generation and Distribution System (CGDS)

A two-ended system (Both for **admin** and **public**) that'll automatically  generate certificates and make them available for distribution. The admin will have to upload a **Certificate Template** and a **CSV file** with Participant Names, Position Awarded, and Event Name. The generated certificates would be later automatically made for distribution.

| CDS Public (Collection)                                  | CDS Admin (Generation)                                   | Generated Certificates                         |
| -------------------------------------------------------- | -------------------------------------------------------- | ---------------------------------------------- |
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



## Authors

| Krishnakanth Alagiri | Mahalakshumi V |
|----------------------|----------------|
| [![f](https://avatars1.githubusercontent.com/u/39209037?s=86)](https://github.com/bearlike) | [![f](https://avatars2.githubusercontent.com/u/40058339?s=86)](https://github.com/mahavisvanathan) | 
| [@bearlike](https://github.com/bearlike) | [@mahavisvanathan](https://github.com/mahavisvanathan) |


## Outside Contributors
| Contributors | Profile Links                                 | PR                                                           |
| ------------ | --------------------------------------------- | ------------------------------------------------------------ |
| **Dhiraj V** | [dhirajv2000](https://github.com/dhirajv2000) | [#28](https://github.com/bearlike/REMS-For-Organisations/pull/28) |

## Acknowledgments

* Hat tip to anyone whose code was used.




![wave](http://cdn.thekrishna.in/img/common/border.png)
