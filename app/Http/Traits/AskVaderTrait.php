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
                'Authorization' => 'Bearer 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',
            ],
            'json' => [
                "model"=> "text-curie-001",
                'prompt' => $question,
                // 'temperature' => 0.7,
                // 'max_tokens' => 60,
                // 'top_p' => 1,
                // 'n' => 1,
                //  'stop' => ['\n'],
            ],
            'verify' => false
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        return response()->json($result['choices'][0]['text'])->original;


        // $yourApiKey = env('CHATGPT_KEY', 'APISECRET');

        // $client = OpenAI::factory()
        //     ->withApiKey($yourApiKey)
        //     ->withHttpClient($client = new \GuzzleHttp\Client(['verify' => false])) // default: HTTP client found using PSR-18 HTTP Client Discovery
        //     ->make();

        // $result = $client->completions()->create([
        //     'model' => 'text-davinci-003',
        //     'prompt' => $question,
        // ]);

        // return $result['choices'][0]['text'];

    }
}