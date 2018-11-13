# Extend the PHP app for Microsoft Graph

In this demo you will incorporate the Microsoft Graph into the application. For this application, you will use the [microsoft-graph](https://github.com/microsoftgraph/msgraph-sdk-php) library to make calls to Microsoft Graph.

## Get calendar events from Outlook

Let's start by adding a controller for the calendar view. Create a new file in the `./app/Http/Controllers` folder named `CalendarController.php`, and add the following code.

```php
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use App\TokenStore\TokenCache;

class CalendarController extends Controller
{
  public function calendar()
  {
    $viewData = $this->loadViewData();

    // Get the access token from the cache
    $tokenCache = new TokenCache();
    $accessToken = $tokenCache->getAccessToken();

    // Create a Graph client
    $graph = new Graph();
    $graph->setAccessToken($accessToken);

    $queryParams = array(
      '$select' => 'subject,organizer,start,end',
      '$orderby' => 'createdDateTime DESC'
    );

    // Append query parameters to the '/me/events' url
    $getEventsUrl = '/me/events?'.http_build_query($queryParams);

    $events = $graph->createRequest('GET', $getEventsUrl)
      ->setReturnType(Model\Event::class)
      ->execute();

    return response()->json($events);
  }
}
```

Consider what this code is doing.

- The URL that will be called is `/v1.0/me/events`.
- The `$select` parameter limits the fields returned for each events to just those the view will actually use.
- The `$orderby` parameter sorts the results by the date and time they were created, with the most recent item being first.

Update the routes in `./routes/web.php` to add a route to this new controller

```php
Route::get('/calendar', 'CalendarController@calendar');
```

Now you can test this. Sign in and click the **Calendar** link in the nav bar. If everything works, you should see a JSON dump of events on the user's calendar.

## Display the results

Now you can add a view to display the results in a more user-friendly manner. Create a new file in the `./resources/views` directory named `calendar.blade.php` and add the following code.

```php
@extends('layout')

@section('content')
<h1>Calendar</h1>
<table class="table">
  <thead>
    <tr>
      <th scope="col">Organizer</th>
      <th scope="col">Subject</th>
      <th scope="col">Start</th>
      <th scope="col">End</th>
    </tr>
  </thead>
  <tbody>
    @isset($events)
      @foreach($events as $event)
        <tr>
          <td>{{ $event->getOrganizer()->getEmailAddress()->getName() }}</td>
          <td>{{ $event->getSubject() }}</td>
          <td>{{ \Carbon\Carbon::parse($event->getStart()->getDateTime())->format('n/j/y g:i A') }}</td>
          <td>{{ \Carbon\Carbon::parse($event->getEnd()->getDateTime())->format('n/j/y g:i A') }}</td>
        </tr>
      @endforeach
    @endif
  </tbody>
</table>
@endsection
```

That will loop through a collection of events and add a table row for each one. Remove the `return response()->json($events);` line from the `calendar` action in `./app/Http/Controllers/CalendarController.php`, and replace it with the following code.

```php
$viewData['events'] = $events;
return view('calendar', $viewData);
```

Refresh the page and the app should now render a table of events.

![A screenshot of the table of events](/Images/add-msgraph-01.png)

## Next steps

Now that you have a working app that calls Microsoft Graph, you can experiment and add new features. Visit the [Microsoft Graph documentation](https://developer.microsoft.com/graph/docs/concepts/overview) to see all of the data you can access with Microsoft Graph.