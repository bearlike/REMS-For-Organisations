<h1 align="center">
  <br>
  <img src="https://i.imgur.com/LIkrG7E.png" alt="CMS For Organisations" height="250px">
  <br>
</h1>
<h4 align="center">User-friendly CMS for small organisations and clubs. Form Generator, Mailer, Certificate Generator and much more :)</h4>

<p align="center">
  <img src="https://img.shields.io/github/last-commit/K-Kraken/cms-for-organisations?color=blue&style=flat-square">
  <a href="/LICENSE"><img src="https://img.shields.io/github/license/K-Kraken/cms-for-organisations.svg?style=flat-square"></a>
     <a href="https://github.com/K-Kraken/cms-for-organisations/issues"><img src="https://img.shields.io/github/issues-raw/K-Kraken/cms-for-organisations?color=red&style=flat-square"/></a>
</p>





### Why are we doing it?
We are running a chapter (club) at our college. We organize 20+ events for our 1500+ participants. It was a tedious process to manually make forms, certificates, advertising via mail so we decided to automate that process. Any organization, clubs or institutions looking for a similar service can fork our project and tweak it according to their needs.



## Getting Started

### Prerequisites
What things you need to run the software:
- A **web server** with **PHP** preferably Apache2.
- A **MySQL Database Server**. (Done and tested on 10.4.8-MariaDB)



### Installation

1. Create and Import the CMS Database dump for  **MySQL-MariaDB** from [here](/docs/files/Sample_CMS_Database.sql) 
  
2. Create a Forms Database.
  
3. Copy the files from this repository to a location in the root directory of the web server
  
4. Rename `member/secrets.php_` to `member/secrets.php`
  
5. Update your database credentials, databases names (CMS and Forms) and API Keys in `member/secrets.php` and `public/cds-public.php`
  
6. With your Web Server and MySQL server running, visit the site
	  - ```
      	Default Username: admin
     Default Password: admin
        ```
    
7. If any error occurs, check your configurations in `member/secrets.php` and `public/cds-public.php` and try again



## Features and Screenshots (Click to enlarge)

```bash
Tested on Apache/2.4.41 (Win64) OpenSSL/1.1.1c PHP/7.3.11 with 10.4.8-MariaDB
```

### Certificate Generation and Distribution System (CGDS)

A two-ended system (Both for **admin** and **public**) that'll automatically  generate certificates and make them available for distribution. The admin will have to upload a **Certificate Template** and a **CSV file** with Participant Names, Position Awarded, and Event Name. The generated certificates would be later automatically made for distribution.

| CDS Public (Collection)                              | CDS Admin (Generation)                              | Generated Certificates                         |
| ---------------------------------------------------- | --------------------------------------------------- | ---------------------------------------------- |
| <img src="/docs/images/cds-public.png" width="650"/> | <img src="/docs/images/cds-admin.png" width="650"/> | <img src="/docs/images/cert.png" width="650"/> |

### Database Management
This is an interface for the databases that are used. The administrator can update, insert and delete values from any table of the databases without having to risk cluttering with the structure or format. The interface is made as generic possible to support future development and extensions.

| Database Manager                                         | Modify Tuples                                            | If Error Occurs                                          |
| -------------------------------------------------------- | -------------------------------------------------------- | -------------------------------------------------------- |
| <img src="https://i.imgur.com/Ildz42T.png" width="650"/> | <img src="https://i.imgur.com/L0Bbraq.png" width="650"/> | <img src="https://i.imgur.com/CYXE1mq.png" width="650"/> |

### Form Generation

This tool is used to generate forms for events. Initially, the specifications of the event must be selected and a form is generated with the designated fields and a **table is automatically created in `forms-db`**. The form also has **built-in validation** for all the fields (emails, URLs, etc) and **Markdown support** for event descriptions. Once the form is sent out, the entered values are updated in the database. 

| Form Generator                                           | Sample Generated Form                                    |
| -------------------------------------------------------- | -------------------------------------------------------- |
| <img src="https://i.imgur.com/mxOyjaP.png" width="650"/> | <img src="https://i.imgur.com/RnTQ3Jq.png" width="650"/> |

### View Responses

To view all the Responses for generated forms with an option to download the responses as a `CSV`. 

| View Form Responses                                      |
| -------------------------------------------------------- |
| <img src="https://i.imgur.com/V3nZ9sG.png" width="650"/> |

### Link Shortener

A link shortener that uses the `short.io` API to render shortened links for distribution. It can make shortened link either with a custom slug or can automatically generate slugs for links.

| Link Shortener with short.io API                         |
| -------------------------------------------------------- |
| <img src="https://i.imgur.com/o66dfJm.png" width="650"/> |

### Dark Mode :crescent_moon:

Perhaps our most desired feature, it gives an option to toggle the page between dark mode and light mode. Saves your eyes in the night :eyes:

| Dark Mode Preview                                        |
| :------------------------------------------------------- |
| <img src="https://i.imgur.com/iG7DyED.gif" width="650"/> |



## Authors

| Authors             | Profile Links                            |
| ------------------- | :--------------------------------------- |
| **Krishna Alagiri** | [K-Kraken](https://github.com/K-Kraken/) |
| **Mahalakshumi V**  | [mahavisvanathan](https://github.com/mahavisvanathan/) |



## Acknowledgments

* Hat tip to anyone whose code was used.




![wave](http://cdn.thekrishna.in/img/common/border.png)
