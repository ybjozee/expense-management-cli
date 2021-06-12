# expense-management-cli


This project is a reference application created to show how
to build a CLI tool using Symfony. It also integrates with [Twilio SendGrid][8] to send emails.

Requirements
------------

  * PHP 7.4 or higher
  * PDO-SQLite PHP extension enabled;
  * [Git][2]
  * [Composer][3]
  * [Symfony CLI][4]
  * and the [usual Symfony application requirements][5].
  * A [Twilio SendGrid][8] account.


Installation
------------

[Download Symfony][4] to install the `symfony` binary on your computer and run
this command:

```bash
$ git clone https://github.com/ybjozee/expense-management-cli.git
$ cd expense-management-cli
$ composer install
```


Usage
-----

Make a local version of the `.env` file 

```bash
$ cp .env .env.local
```

Update the database and SendGrid parameters accordingly

``` ini
DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
SENDGRID_API_KEY=YOUR_SENDGRID_API_KEY
SENDGRID_SENDER_EMAIL=YOUR_SENDGRID_SENDER_EMAIL
SENDGRID_SENDER_NAME=YOUR_SENDGRID_SENDER_NAME
```

Set up your database

```bash
$ ./bin/console doctrine:database:create
$ ./bin/console doctrine:schema:update --force
```
run this command to get more information on how to use the command:

```bash
$ symfony console generateExpenseReport --help
```

```bash
Description:
  Generates expense report

Usage:
  generateExpenseReport [options]

Options:
  -s, --status[=STATUS]  Only include expenses matching the specified status
  -m, --mailTo[=MAILTO]  Send the report as an attachment to the specified email address
  -h, --help             Display help for the given command. When no command is given display help for the list command
  -q, --quiet            Do not output any message
  -V, --version          Display this application version
      --ansi|--no-ansi   Force (or disable --no-ansi) ANSI output
  -n, --no-interaction   Do not ask any interactive question
  -e, --env=ENV          The Environment name. [default: "dev"]
      --no-debug         Switch off debug mode.
  -v|vv|vvv, --verbose   Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Help:
  This command helps you generate an expense report based on provided arguments
```



[1]: https://authy.com/blog/authy-vs-google-authenticator/
[2]: https://git-scm.com/
[3]: https://getcomposer.org/
[4]: https://symfony.com/download
[5]: https://symfony.com/doc/current/reference/requirements.html
[7]: https://github.com/symfony/webpack-encore
[8]: https://app.sendgrid.com/
