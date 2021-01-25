<!-- Copyright (c) Microsoft Corporation.
     Licensed under the MIT License. -->

<!-- <NewEventFormSnippet> -->
@extends('layout')

@section('content')
<h1>New event</h1>
<form method="POST">
  @csrf
  <div class="form-group">
    <label>Subject</label>
    <input type="text" class="form-control" name="eventSubject" />
  </div>
  <div class="form-group">
    <label>Attendees</label>
    <input type="text" class="form-control" name="eventAttendees" />
  </div>
  <div class="form-row">
    <div class="col">
      <div class="form-group">
        <label>Start</label>
        <input type="datetime-local" class="form-control" name="eventStart" id="eventStart" />
      </div>
      @error('eventStart')
        <div class="alert alert-danger">{{ $message }}</div>
      @enderror
    </div>
    <div class="col">
      <div class="form-group">
        <label>End</label>
        <input type="datetime-local" class="form-control" name="eventEnd" />
      </div>
      @error('eventEnd')
        <div class="alert alert-danger">{{ $message }}</div>
      @enderror
    </div>
  </div>
  <div class="form-group">
    <label>Body</label>
    <textarea type="text" class="form-control" name="eventBody" rows="3"></textarea>
  </div>
  <input type="submit" class="btn btn-primary mr-2" value="Create" />
  <a class="btn btn-secondary" href={{ action('CalendarController@calendar') }}>Cancel</a>
</form>
@endsection
<!-- </NewEventFormSnippet> -->
