# LOGAN - Lifestyle Organiser, Greetings And Notifications

A smart API - primarily for automation and notifications.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

What things you need to install the software and how to install them

```
composer
php
php-fpm
php-mysql
nginx
```

### Installing

After cloning the repo, just do this:

```
php /usr/bin/composer install
```

You'll need to ensure you configure nginx to allow php processing:

```
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php7.0-fpm.sock;
    }

    location ~ /\.ht {
        deny all;
    }
```

### Database Setup

After install the DB schema (TODO), the following options must be included in the `config` table:

```
+--------------------------+--------------------------------------+
| config_key               | config_value                         |
+--------------------------+--------------------------------------+
| logan_email              | [smtp_username]                      |
| logan_email_password     | [smtp_password]                      |
| logan_email_server       | [smtp_server]                        |
| default_email_recipients | [comma_delimited_list_of_recipients] |
| logan_email_sender       | [smtp_sender]                        |
| default_city             | [default_city]                       |
+--------------------------+--------------------------------------+
```

## Built With

* [Composer](https://getcomposer.org/) - Dependency Manager for PHP
* [PHPMailer](https://github.com/PHPMailer/PHPMailer) - A full-featured email creation and transfer class for PHP

* **Adam Foster** - *Initial work* - [LOGAN](https://github.com/a-foster/logan)

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
