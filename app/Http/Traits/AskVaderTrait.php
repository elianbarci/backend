<?php
namespace App\Http\Traits;
use App\Models\Student;
use GuzzleHttp\Client;
use OpenAI;

trait AskVaderTrait {
    public function askVader($question) {

        $client = new Client();
        
        $response = $client->post('https://api.openai.com/v1/completions', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . env('CHATGPT_KEY', false),
            ],
            'json' => [
                "model"=> "text-curie-001",
                'prompt' => $question,
                // 'temperature' => 0.7,
                // 'max_tokens' => 60,
                'top_p' => 1,
                'n' => 1,
                'stop' => ['\n'],
            ],
            'verify' => false
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        return response()->json($result['choices'][0]['text'])->original;


    }
}