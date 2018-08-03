# Create a PHP web app

## Prerequisites

Before you start this demo, you should have [PHP](http://php.net/downloads.php), [Composer](https://getcomposer.org/), and [Laravel](https://laravel.com/) installed on your development machine.

> **Note:** This tutorial was written with PHP version 7.2. The steps in this guide may work with other versions, but that has not been tested.

Open your CLI, navigate to a directory where you have rights to create files, and run the following command to create a new PHP app.

```Shell
laravel new graph-tutorial
```

Laravel creates a new directory called `graph-tutorial` and scaffolds a PHP app. Navigate to this new directory and enter the following command to start a local web server.

```Shell
php artisan serve
```

Open your browser and navigate to `http://localhost:8000`. If everything is working, you will see a default Laravel page. If you don't see that page, check the [Laravel docs](https://laravel.com/docs/5.6).

Before moving on, install some additional libraries that you will use later:

- [oauth2-client](https://github.com/thephpleague/oauth2-client) for handling sign-in and OAuth token flows.
- [microsoft-graph](https://github.com/microsoftgraph/msgraph-sdk-php) for making calls to Microsoft Graph.

Run the following command in your CLI.

```Shell
composer require league/oauth2-client:dev-master microsoft/microsoft-graph
```

## Design the app

Start by creating the global layout for the app. Create a new file in the  `./resources/views/layouts` directory named `layout.blade.php` and add the following code.

```php
```

This code adds [Bootstrap](http://getbootstrap.com/) for simple styling, and [Font Awesome](https://fontawesome.com/) for some simple icons. It also defines a global layout with a nav bar.

Now open `./public/css/app.css` and replace its entire contents with the following.

```css
body {
  padding-top: 4.5rem;
}

.alert-pre {
  word-wrap: break-word;
  word-break: break-all;
  white-space: pre-wrap;
}
```

Now update the default page. Open the `./resources/views/layouts/welcome.blade.php` file and replace its contents with the following.

```php
@extends('layout')

@section('content')
<div class="jumbotron">
  <h1>PHP Graph Tutorial</h1>
  <p class="lead">This sample app shows how to use the Microsoft Graph API to access Outlook and OneDrive data from PHP</p>
  <?php if(isset($username)) { ?>
    <h4>Welcome <?php echo $user_name ?>!</h4>
    <p>Use the navigation bar at the top of the page to get started.</p>
  <?php } else { ?>
    <a href="#" class="btn btn-primary btn-large">Click here to sign in</a>
  <?php } ?>
</div>
@endsection
```

Save all of your changes and restart the server. Now, the app should look very different.

![A screenshot of the redesigned home page](/Images/create-app-01.png)

## Next steps

Now that you've created the basic app, you can continue to the next module, [Create an Azure AD web application with the Application Registration Portal](../02-arp-app/README.md).