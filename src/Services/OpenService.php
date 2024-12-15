<?php
namespace OpenAiPackage\Services;

use OpenAI;

class OpenService
{
    public function openAi($prompts)
    {
        return $this->openAiEngine($prompts);
    }

    private function openAiEngine($prompts)
    {
        $temperature = $this->determineTemperature($prompts);
        $finalPrompt = $prompts;

        // Fetch AI data from the database (this is just an example)
        $aiResponseData = AIResponse::pluck('description', 'title')->toArray();

        $data = array_map(function ($title, $description) {
            return [
                'title' => $title,
                'description' => $description,
            ];
        }, array_keys($aiResponseData), $aiResponseData);

        $titles = array_column($data, 'title');
        $titleList = implode("\n", $titles);

        $aiPrompt = "Here are some topics:\n$titleList\n\nWhich topic is most relevant to this query: \"$finalPrompt\"? Provide only the matching topic title.";

        $aiResponse = OpenAI::chat()->create([
            'model' => 'gpt-4',
            'messages' => [
                ['role' => 'user', 'content' => $aiPrompt],
            ],
            'temperature' => $temperature,
        ]);

        $matchedTitle = trim($aiResponse->choices[0]->message->content);

        $description = null;
        foreach ($data as $item) {
            if (strcasecmp($item['title'], $matchedTitle) === 0) {
                $description = $item['description'];
                break;
            }
        }

        if ($description) {
            return $description;
        } else {
            $ai_default_response = BusinessSetting::where('key', 'ai_default_response')->first();
            return $ai_default_response?->value ?? "Apologies, we couldn't find a matching topic. Please contact an agent for immediate assistance or try rephrasing your query.";
        }
    }

    private function determineTemperature($question)
    {
        return $this->isComplex($question) ? 0.8 : 0.3;
    }

    private function isComplex($question)
    {
        return strlen($question) > 50 || preg_match('/\?/', $question);
    }
}

?>