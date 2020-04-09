<h1 align="center">
  <br>
  CMS For Organisations
  <br>
</h1>

<h4 align="center">User-friendly CMS for small organisations and clubs. Mailers, Certificate Generation and much more :)</h4>

<p align="center">
  <img src="https://img.shields.io/github/last-commit/K-Kraken/cms-for-organisations?color=blue&style=flat-square">
  <a href="/LICENSE"><img src="https://img.shields.io/github/license/K-Kraken/cms-for-organisations.svg?style=flat-square"></a>
</p>




### Why are we doing it?
We are running a chapter (club) at our college. We organize 20+ events for our 1500+ participants. It was a tedious process to manually make forms, certificates, advertising via mail so we decided to automate that process. Any organization, clubs or institutions looking for a similar service can fork our project and tweak it according to their needs.



## Getting Started

### Prerequisites
What things you need to run the software:
- A **web server** preferably Apache2 with **PHP**.
- A **MySQL Database Server**. (Done and tested on 10.4.8-MariaDB)
- Import the Sample CMS Database dump for **MariaDB** from [here](docs\files\Sample_CMS_Database.sql) 
  - ```
      Default Username: admin
      Default Password: admin
    ```
  - Contains no other data other than the default user above.



## Features

### Certificate Distribution System (CDS)

A two-ended system (Both for **admin** and **public**) that'll automatically  generate certificates and make them available for distribution. The admin will have to upload a **Certificate Template** and a **CSV file** with Participant Names, Position Awarded, and Event Name. The generated certificates would be later automatically made for distribution.



## Screenshot

```bash
Tested on Apache/2.4.41 (Win64) OpenSSL/1.1.1c PHP/7.3.11 with 10.4.8-MariaDB
```

<img src="/docs/images/cds-public.png" width="650"/>

<img src="/docs/images/cds-admin.png" width="650"/>

<img src="/docs/images/cert.png" width="650"/>





## Authors

| Authors             | Profile Links                            |
| ------------------- | :--------------------------------------- |
| **Krishna Alagiri** | [K-Kraken](https://github.com/K-Kraken/) |
| **Mahalakshumi V**  | [maha2000](https://github.com/maha2000/) |



## Acknowledgments

* Hat tip to anyone whose code was used.




![wave](http://cdn.thekrishna.in/img/common/border.png)
