<!-- markdownlint-disable MD002 MD041 -->

In this exercise you will incorporate the Microsoft Graph into the application. For this application, you will use the [microsoft-graph](https://github.com/microsoftgraph/msgraph-sdk-php) library to make calls to Microsoft Graph.

## Get calendar events from Outlook

1. Create a new file in the **./app/Http/Controllers** directory named `CalendarController.php`, and add the following code.

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

1. Update the routes in **./routes/web.php** to add a route to this new controller.

    ```php
    Route::get('/calendar', 'CalendarController@calendar');
    ```

1. Sign in and click the **Calendar** link in the nav bar. If everything works, you should see a JSON dump of events on the user's calendar.

## Display the results

Now you can add a view to display the results in a more user-friendly manner.

1. Create a new file in the **./resources/views** directory named `calendar.blade.php` and add the following code.

    :::code language="php" source="../demo/graph-tutorial/resources/views/calendar.blade.php" id="CalendarSnippet":::

    That will loop through a collection of events and add a table row for each one.

1. Remove the `return response()->json($events);` line from the `calendar` action in **./app/Http/Controllers/CalendarController.php**, and replace it with the following code.

    ```php
    $viewData['events'] = $events;
    return view('calendar', $viewData);
    ```

1. Refresh the page and the app should now render a table of events.

    ![A screenshot of the table of events](./images/add-msgraph-01.png)
