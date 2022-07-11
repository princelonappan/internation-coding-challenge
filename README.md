## Overview

This application provides the admin to manage the features.

- User Management (Add/Edit/Delete/Update)
- Group Management (Add/Edit/Delete/Update)
- User Group Management (Add/Delete/Update)
- API Management (List all the APIs that available for future use)

## Requirements and dependencies

- PHP >= 7.2
- Symfony CLI version  v4.28.1

## Features

- Manage the User and Group from the Admin side

## Installation

First, clone the repo:
```bash
$ git clone https://github.com/princelonappan/internations-coding-challenge.git
```
#### Install dependencies
```
$ cd internation-coding-challenge
$ composer install
```
```
$ change the database configuration in the .env file
```
#### Run the following commands to migrate the database change
```
$ php bin/console make:migration
$ php bin/console doctrine:migrations:migrate
```
#### Add the following sql to the database for adding admin user.
admin@admin.com/admin
```
$ INSERT INTO `admin` (`id`, `email`, `roles`, `password`) VALUES
(1, 'admin@admin.com', '[\"ROLE_ADMIN\"]', '$2y$13$SdAsUDtP1TzaG.B8T1ILYuNKEU2bL0jqN19LCQMccHUwaxBMofM9C');
```

### URL Routes
```
$ {{ base_url}}/admin - Admin URL
```

#### Run API Swagger

You can access the Swagger API through the following end point. <br />
```/api```
