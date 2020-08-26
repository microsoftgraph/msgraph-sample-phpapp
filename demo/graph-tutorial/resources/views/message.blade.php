@extends('layout')

@section('content')

<div class="container mt-3">
    <a href="{{ route('message_index') }}" class="btn">
        <i class="fas fa-chevron-left"></i>
        Back
    </a>
    <div class="card mt-3">
        <div class="card-header d-flex justify-content-between">
            <div>
                {{ $message->getSubject() }}
            </div>
            <div>
                {{ \Carbon\Carbon::parse($message->getReceivedDateTime())->format('Y-m-d H:i:s') }}
            </div>
        </div>
        <div class="card-body">
            {!! $message->getBody()->getContent() !!}
        </div>
        @if($message->getHasAttachments())
        <div class="card-footer">
            <h5>Attachments</h5>
            <div class="d-flex flex-column">
            @foreach($attachments as $attachment)
                <a
                    href="{{ route('message_attachment', ['id' => $message->getId(), 'attachmentId' => $attachment->getId()]) }}"
                    target="_blank"
                    class="text-info"
                >
                    <i class="fas {{ $getFAIcon($attachment->getContentType()) }}"></i>
                    {{ $attachment->getName() }}
                    ({{ round($attachment->getSize()/1024, 3) }}Kb)
                </a>
            @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

@endsection
