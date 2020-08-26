@extends('layout')

@section('content')
<h1>Messages</h1>
<table class="table">
  <thead>
    <tr>
      <th scope="col">From</th>
      <th scope="col">Subject</th>
      <th scope="col">Received</th>
    </tr>
  </thead>
  <tbody>
    @isset($messages)
      @foreach($messages as $message)
        <tr class="{{ $message->getIsRead() ? '' : 'font-weight-bold'}}">
          <td class="d-flex justify-content-between">
              <div>
                  {{ $message->getFrom()->getEmailAddress()->getName() }}
              </div>
              <div>
                  @if($message->getHasAttachments())
                      <i class="fas fa-paperclip"></i>
                  @endif
              </div>
          </td>
          <td>
              <a href="{{ route('message_show', [$message->getId()]) }}" class="text-dark">
                {{ $message->getSubject() }}
              </a>
          </td>
          <td>{{ \Carbon\Carbon::parse($message->getReceivedDateTime())->format('Y-m-d H:i:s') }}</td>
        </tr>
      @endforeach
    @endif
  </tbody>
</table>
@endsection
