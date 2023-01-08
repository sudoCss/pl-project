<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\GetMessageRequest;
use App\Http\Requests\StoreMessageRequest;
use App\Models\ChatMessage;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Events\NewMessageSent;

class ChatMessageController extends Controller
{

    /**
     * Get  chat message
     *
     * @param  GetMessageRequest $request
     * @return JsonResponse
     */
    public function index(GetMessageRequest $request) : JsonResponse
    {
        $data = $request->validated();
        $chatId = $data['chat_id'];
        $currentPage = $data['page'];
        $pageSize = $data['page_size'] ?? 15;

        $messages = ChatMessage::where('chat_id', $chatId)
                    ->with('user')
                    ->latest('created_at')
                    ->simplePaginate(
                        $pageSize,
                        ['*'],
                        'page',
                        $currentPage
                    );

        $messages = $messages->getCollection();

        return response()->json([
            'status' =>  'success',
            'message' => 'Messages successfully',
            'data' => [
                'Messages' => $messages
            ]
        ], Response::HTTP_OK);
    }

    /**
     * create a chat message
     *
     * @param  StoreMessageRequest $request
     * @return JsonResponse
     */
    public function store(StoreMessageRequest $request) : JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->user()->id;

        $chatMessage = ChatMessage::create($data);
        $chatMessage->load('user');

        ///  TODO send broadcast event to pusher and send notification to onesignal services
        $this->sendNotificationToOther($chatMessage);

        return response()->json([
            'status' =>  'success',
            'message' => 'Message has been sent succesfully',
            'data' => [
                'Messages' => $chatMessage
            ]
        ], Response::HTTP_OK);
    }

    /**
     * Send notification to other users
     *
     * @param  ChatMessage $chatMessage
     *
     */
    private function sendNotificationToOther(ChatMessage $chatMessage) : void
    {
       // $chatId = $chatMessage->chat_id;

        broadcast(new NewMessageSent($chatMessage))->toOthers();
    }
}
