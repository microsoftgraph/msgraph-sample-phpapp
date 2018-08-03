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