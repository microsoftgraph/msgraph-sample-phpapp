<?php

namespace App\Http\Controllers;

use App\Providers\FontAwesomeServiceProvider;
use App\TokenStore\TokenCache;
use Illuminate\Support\Facades\Response;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model\Attachment;
use Microsoft\Graph\Model\FileAttachment;
use Microsoft\Graph\Model\Message;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class MessageController extends Controller
{
    public function index()
    {
        $graph = $this->getGraph();

        /** @var Message[] $messages */
        $messages = $graph->createRequest('GET', '/me/messages')
            ->setReturnType(Message::class)
            ->execute();

        $viewData = $this->loadViewData();
        $viewData['messages'] = $messages;
        return view('messages', $viewData);
    }

    public function show(string $id)
    {
        $graph = $this->getGraph();

        /** @var Message $message */
        $message = $graph->createRequest('GET', "/me/messages/{$id}")
            ->setReturnType(Message::class)
            ->execute()
        ;
        /** @var Attachment[] $attachments */
        $attachments = $graph->createRequest('GET', "/me/messages/{$id}/attachments")
            ->setReturnType(Attachment::class)
            ->execute()
        ;

        $viewData = $this->loadViewData();
        $viewData['message'] = $message;
        $viewData['attachments'] = $attachments;
        $viewData['getFAIcon'] = function (string $mimetype) {
            return FontAwesomeServiceProvider::getIcon($mimetype);
        };
        return view('message', $viewData);
    }

    public function getAttachment($id, $attachmentId)
    {
        $graph = $this->getGraph();

        /** @var FileAttachment $attachment */
        $attachment = $graph->createRequest('GET', "/me/messages/{$id}/attachments/{$attachmentId}")
            ->setReturnType(FileAttachment::class)
            ->execute()
        ;

        $headers = [
            'Content-type' => $attachment->getContentType(),
            'Content-Disposition' => "attachment; filename=\"{$attachment->getName()}\"",
        ];

        return Response::make(base64_decode($attachment->getContentBytes()), 200, $headers);
    }

    /**
     * @return Graph
     */
    private function getGraph(): Graph
    {
        // Get the access token from the cache
        $tokenCache = new TokenCache();
        $accessToken = $tokenCache->getAccessToken();
        if (!$accessToken) {
            throw new UnauthorizedHttpException('No access token');
        }

        // Create a Graph client
        $graph = new Graph();
        $graph->setAccessToken($accessToken);
        return $graph;
    }
}
