<?php

namespace CreaceptLms\HomeworkChat\Http\Controllers;

use CreaceptLms\HomeworkChat\Http\Requests\ChatMessageRequest;
use CreaceptLms\HomeworkChat\Repositories\ChatMessageRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Modules\Learning\Models\Lesson\Homework\ChatMessage;
use Throwable;

/**
 *
 */
class HomeworkChatController
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMessages(Request $request): JsonResponse
    {
        $gradeId = $request->query('grade');
        if (!$gradeId) {
            return new JsonResponse([
                'message' => 'Не указан студент или занятие'
            ], 422);
        }

        $chatMessages = ChatMessage::with(['sender', 'attachments'])
            ->where('grade_id', $gradeId)
            ->get();

        return new JsonResponse([
            'messages' => $chatMessages,
        ]);
    }

    /**
     * @param \CreaceptLms\HomeworkChat\Http\Requests\ChatMessageRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addMessage(ChatMessageRequest $request): JsonResponse
    {
        $admin = Auth::user();
        $gradeId = $request->post('grade_id');
        $message = $request->post('message');

        try {
            /** @var \CreaceptLms\HomeworkChat\Repositories\ChatMessageRepository $chatMessageRepository */
            $chatMessageRepository = App::make(ChatMessageRepository::class);
            $chatMessageRepository->storeChatMessage($admin->id, $gradeId, $message);
        } catch (Throwable $e) {
            dd($e->getMessage());
            report($e);
            return new JsonResponse([
                'message' => 'Произошла ошибка, попробуйте еще раз',
            ], 500);
        }

        return new JsonResponse([
            'message' => 'Сообщение успешно отправлено',
        ], 201);
    }
}