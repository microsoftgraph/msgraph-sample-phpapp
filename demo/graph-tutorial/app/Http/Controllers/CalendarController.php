<?php
// Copyright (c) Microsoft Corporation.
// Licensed under the MIT License.

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use App\TokenStore\TokenCache;
use App\TimeZones\TimeZones;

class CalendarController extends Controller
{ /**
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  public function calendar()
  {
    $viewData = $this->loadViewData();

    $graph = $this->getGraph();

    // Get user's timezone
    $timezone = TimeZones::getTzFromWindows($viewData['userTimeZone']);

    // Get start and end of week
    $startOfWeek = new \DateTimeImmutable('sunday -1 week', $timezone);
    $endOfWeek = new \DateTimeImmutable('sunday', $timezone);

    $viewData['dateRange'] = $startOfWeek->format('M j, Y').' - '.$endOfWeek->format('M j, Y');

    $queryParams = array(
      'startDateTime' => $startOfWeek->format(\DateTimeInterface::ISO8601),
      'endDateTime' => $endOfWeek->format(\DateTimeInterface::ISO8601),
      // Only request the properties used by the app
      '$select' => 'subject,organizer,start,end',
      // Sort them by start time
      '$orderby' => 'start/dateTime',
      // Limit results to 25
      '$top' => 25
    );

    // Append query parameters to the '/me/calendarView' url
    $getEventsUrl = '/me/calendarView?'.http_build_query($queryParams);

    $events = $graph->createRequest('GET', $getEventsUrl)
      // Add the user's timezone to the Prefer header
      ->addHeaders(array(
        'Prefer' => 'outlook.timezone="'.$viewData['userTimeZone'].'"'
      ))
      ->setReturnType(Model\Event::class)
      ->execute();

    $viewData['events'] = $events;
    return view('calendar', $viewData);
  }

  private function getGraph(): Graph
  {
    // Get the access token from the cache
    $tokenCache = new TokenCache();
    $accessToken = $tokenCache->getAccessToken();

    // Create a Graph client
    $graph = new Graph();
    $graph->setAccessToken($accessToken);
    return $graph;
  }
}