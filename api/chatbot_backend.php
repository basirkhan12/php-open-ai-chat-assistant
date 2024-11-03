<?php


session_start();  // Start the PHP session
header('Content-Type: application/json');

$apiKey = 'sk-......';// Replace with your OpenAI API key
$assistantId = 'asst_......';// Replace with your OpenAI Assistant ID
function make_post_request($url, $headers, $data = null) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); // Make sure the method is POST
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // Add data to POST
    }

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        error_log("cURL error: " . curl_error($ch));
        curl_close($ch);
        return null;
    }

    curl_close($ch);

    if ($httpcode == 200) {
        return json_decode($response);
    } else {
        error_log("HTTP error: $httpcode - Response: $response");
        return null;
    }
}

// function get requester
function make_get_request($url, $headers) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response);
}


function createThread($apiKey) {
    $url = 'https://api.openai.com/v1/threads';
    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey,
        'OpenAI-Beta: assistants=v2'
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

function sendMessage($apiKey, $threadId, $message) {
    $url = "https://api.openai.com/v1/threads/$threadId/messages";
    $data = [
        "role" => "user",
        "content" => $message
    ];

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey,
        'OpenAI-Beta: assistants=v2'
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

function runAssistant($apiKey, $threadId, $assistantId) {
    $url = "https://api.openai.com/v1/threads/$threadId/runs";
    $data = [ "assistant_id" => $assistantId ];

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey,
        'OpenAI-Beta: assistants=v2'
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

function check_run_status($api_key, $thread, $run_id) {
    $url = "https://api.openai.com/v1/threads/$thread/runs/$run_id";
    $headers = [
        "Authorization: Bearer $api_key",
        "OpenAI-Beta: assistants=v2"
    ];

    $response = make_get_request($url, $headers);
    if ($response && isset($response->status)) {
        return $response->status;
    }
    return false;
}



function get_new_content($api_key, $thread, $last_message_id) {
    $url = "https://api.openai.com/v1/threads/$thread/messages";
    $headers = [
        "Authorization: Bearer $api_key",
        "OpenAI-Beta: assistants=v2"
    ];

    $response = make_get_request($url, $headers);
    if ($response && isset($response->data)) {
        foreach ($response->data as $message) {
            error_log(json_encode($message));
            if ($message->role === "assistant" && count($message->content) > 0 && (!$last_message_id || $message->id !== $last_message_id)) {
                return [
                    'lastMessageId' => $message->id,
                    'assistantMessage' => $message->content[0]->text->value
                ];
            }
        }
    }
    return null;
}

function fetchThreadMessages($apiKey, $threadId) {
    $url = "https://api.openai.com/v1/threads/$threadId/messages";

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey,
        'OpenAI-Beta: assistants=v2'
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

function getAssistantReply($apiKey, $threadId, $lastMessageId = null, $runId) {
    $startTime = time();
    $assistantMessage = "";
    $currentLastMessageId = "";
    $complete = false;
    $retry_count = 0;
    $max_retries = 50;  // Adjust as needed

    while (!$complete && $retry_count < $max_retries) {
        $run_status = check_run_status($apiKey, $threadId, $runId);
        if ($run_status === 'completed') {
            $complete = true;
        } elseif ($run_status === false) {
            $retry_count++;
            sleep(1);
           
        } 
        
    }
        
        if($complete){
        
          $new_content = get_new_content($apiKey, $threadId, $lastMessageId);
          return $new_content;
        }
       
    return null; // Return null if no response after the timeout
}

$postData = json_decode(file_get_contents('php://input'), true);
$message = $postData['message'] ?? '';
$threadId = $postData['threadId'] ?? null;
$lastMessageId = $postData['lastMessageId'] ?? null;  // Track the last processed message

if (!$threadId) {
    // Create a new thread if no thread ID is provided
    $threadResponse = createThread($apiKey);
    $threadId = $threadResponse['id'];
}

// Send user message only if it's new or the thread is new
$sendMessageResponse = null;
if (!$lastMessageId || $message !== $lastMessageId) {
    $sendMessageResponse = sendMessage($apiKey, $threadId, $message);
}

// Initiate a run for the assistant if no error from the message API call
if ($sendMessageResponse) {
    $runResponse = runAssistant($apiKey, $threadId, $assistantId);
    

}

// Fetch the assistant's reply, ensuring we track the last message correctly
$assistantResponse = getAssistantReply($apiKey, $threadId, $lastMessageId, $runResponse["id"]);  // Timeout of 30 seconds, check every 5 seconds

if ($assistantResponse) {
    
  
    // Return the assistant's reply and the last message ID as JSON if found
    echo json_encode([
        'threadId' => $threadId,
        'reply' => $assistantResponse['assistantMessage'],
        'lastMessageId' => $assistantResponse['lastMessageId']  // Save the last message ID from the assistant's message
    ]);
} else {
    // If no reply was found within the timeout, return a message indicating a delay
    echo json_encode([
        'threadId' => $threadId,
        'reply' => "The assistant is taking longer than expected. Please try again later.",
        'lastMessageId' => $lastMessageId  // Preserve the last valid message ID
    ]);
}