<?php
// 代码生成时间: 2025-11-02 17:12:32
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * WebContentGrabber class is designed to fetch content from a given URL.
 * It uses the Symfony HttpClient to perform the HTTP request.
 * Error handling is implemented to manage potential transport errors.
 */
class WebContentGrabber
{
    private HttpClient $client;

    /**
     * Constructor to initialize the HttpClient.
     */
    public function __construct()
    {
        $this->client = HttpClient::create();
    }

    /**
     * Fetches content from a given URL and returns it as a string.
     *
     * @param string $url The URL to fetch content from.
     * @return string The fetched content.
     * @throws TransportExceptionInterface If there is a transport error.
     */
    public function fetchContent(string $url): string
    {
        try {
            // Send a GET request to the specified URL.
            $response = $this->client->request('GET', $url);

            // Return the content of the response as a string.
            return $response->getContent();
        } catch (TransportExceptionInterface $exception) {
            // Handle transport exceptions and throw them to be caught by the caller.
            throw $exception;
        }
    }
}

// Usage example:
try {
    $grabber = new WebContentGrabber();
    $url = 'https://example.com';
    $content = $grabber->fetchContent($url);
    echo "Content fetched successfully: " . $content;
} catch (TransportExceptionInterface $e) {
    echo "An error occurred while fetching content: " . $e->getMessage();
}
