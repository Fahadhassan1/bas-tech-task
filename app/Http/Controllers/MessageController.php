<?php

namespace App\Http\Controllers;

use App\Services\MessageService;
use Illuminate\Http\Request;
use \Illuminate\Validation\ValidationException;
use App\Http\Requests\MessageRequest;

class MessageController extends Controller
{
    protected $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

     /**
     * store encyrptyed messages.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(MessageRequest $request) 
    {
        try {

            $validated = $request->validated();
            // Call the service to store the message
            $response = $this->messageService->storeMessage(
                $validated['message'],
                $validated['recipient'],
                $validated['read_once'] ?? false
            );

            return response()->json($response);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->validator->errors(),
            ], $e->getCode() ?: 422);
        }
    }


    /**
     * get a decrypted message.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        try {
            $validated = $request->validate([
                'identifier' => 'required|string',
                'decryption_key' => 'required|string',
            ]);

            $result = $this->messageService->getMessage($validated['identifier'], $validated['decryption_key']);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    
                ], 200);
            }

            return response()->json([
                'success' => false,
                'error' => $result['message'],
            ], 404);


        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->validator->errors(),
            ], $e->getCode() ?: 500);
        }
    }
}
