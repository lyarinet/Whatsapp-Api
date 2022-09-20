<?php

declare(strict_types=1);

namespace Adrii\Whatsapp\Actions;

use Adrii\Whatsapp\OAuth\Config;
use Adrii\Whatsapp\Http\Request;

class Messages
{
    private $config;
    private $uri = "/messages";

    public function __construct(Config $config)
    {
        $this->config        = $config;
        $this->http_request  = new Request();
    }

    /**
     * @param string $message
     * @param string $recipientId
     * @param string $recipientType
     * @param bool $previewUrl
     * @return mixed
     */
    public function text(string $message, string $recipientId, string $recipientType = "individual", bool $previewUrl = true)
    {
        $data = [
            "messaging_product" => "whatsapp",
            "recipient_type"    => $recipientType,
            "to"                => $recipientId,
            "type"              => "text",
            "text"              => ["preview_url" => $previewUrl, "body" => $message]
        ];

        $url     = $this->config->getApiUri($this->uri);
        $bearer  = $this->config->getAccessToken();
        $headers = ["Authorization" => "Bearer {$bearer}"];

        $response = $this->http_request->post($url, $data, $headers);

        return $response;
    }

    /**
     * @param string $template
     * @param string $recipientId
     * @param string $lang
     * @return mixed
     */
    public function template(string $template, string $recipientId, string $lang = "en_US")
    {
        $data = [
            "messaging_product" => "whatsapp",
            "to"                => $recipientId,
            "type"              => "template",
            "template"          => ["name" => $template, "language" => ["code" => $lang]],
        ];

        $url     = $this->config->getApiUri($this->uri);
        $bearer  = $this->config->getAccessToken();
        $headers = ["Authorization" => "Bearer {$bearer}"];

        $response = $this->http_request->post($url, $data, $headers);

        return $response;
    }

    /**
     * @param string $lat
     * @param string $long
     * @param string $name
     * @param string $address
     * @param string $recipientId
     * @return mixed
     */
    public function location(string $lat, string $long, string $name, string $address, string $recipientId)
    {
        $data = [
            "messaging_product" => "whatsapp",
            "to"                => $recipientId,
            "type"              => "location",
            "location"          => [
                "latitude"  => $lat,
                "longitude" => $long,
                "name"      => $name,
                "address"   => $address,
            ],
        ];

        $url     = $this->config->getApiUri($this->uri);
        $bearer  = $this->config->getAccessToken();
        $headers = ["Authorization" => "Bearer {$bearer}"];

        $response = $this->http_request->post($url, $data, $headers);

        return $response;
    }

    /**
     * @param $image
     * @param $recipientId
     * @param string $recipientType
     * @param $caption
     * @param $link
     * @return mixed
     */
    public function image(string $image, string $recipientId, string $recipientType = "individual", $caption = null, bool $link = true)
    {
        $prefab = ($link) ? "link" : "id";

        $data = [
            "messaging_product" => "whatsapp",
            "recipient_type"    => $recipientType,
            "to"                => $recipientId,
            "type"              => "image",
            "image"             => [
                $prefab     => $image,
                "caption"   => $caption,
            ],
        ];

        $url     = $this->config->getApiUri($this->uri);
        $bearer  = $this->config->getAccessToken();
        $headers = ["Authorization" => "Bearer {$bearer}"];

        $response = $this->http_request->post($url, $data, $headers);

        return $response;
    }

    /**
     * @param $audio
     * @param $recipientId
     * @param $link
     * @return mixed
     */
    public function audio(string $audio, string $recipientId, bool $link = true)
    {
        $data = [
            "messaging_product" => "whatsapp",
            "to"                => $recipientId,
            "type"              => "audio",
            "audio"             => ($link) ? ["link" => $audio] : ["id" => $audio],
        ];

        $url     = $this->config->getApiUri($this->uri);
        $bearer  = $this->config->getAccessToken();
        $headers = ["Authorization" => "Bearer {$bearer}"];

        $response = $this->http_request->post($url, $data, $headers);

        return $response;
    }

    /**
     * @param $video
     * @param $recipientId
     * @param $caption
     * @param $link
     * @return mixed
     */
    public function video(string $video, string $recipientId, $caption = null, bool $link = true)
    {
        $prefab = ($link) ? "link" : "id";

        $data = [
            'messaging_product' => 'whatsapp',
            'to'                => $recipientId,
            'type'              => 'video',
            'video'             => [
                $prefab     => $video,
                'caption'   => $caption,
            ],
        ];

        $url     = $this->config->getApiUri($this->uri);
        $bearer  = $this->config->getAccessToken();
        $headers = ["Authorization" => "Bearer {$bearer}"];

        $response = $this->http_request->post($url, $data, $headers);

        return $response;
    }

    /**
     * @param $document
     * @param $recipientId
     * @param $caption
     * @param $link
     * @return mixed
     */
    public function document(string $document, string $recipientId, $caption = null, bool $link = true)
    {
        $prefab = ($link) ? "link" : "id";

        $data = [
            "messaging_product" => "whatsapp",
            "to"                => $recipientId,
            "type"              => "document",
            "document"          => [
                $prefab     => $document,
                "caption"   => $caption
            ]
        ];

        $url     = $this->config->getApiUri($this->uri);
        $bearer  = $this->config->getAccessToken();
        $headers = ["Authorization" => "Bearer {$bearer}"];

        $response = $this->http_request->post($url, $data, $headers);

        return $response;
    }

    //create button

    /**
     * @param array $button
     * @return array
     */
    public function createInteraction(array $button, string $type)
    {
        $elem =  [
            "type"      => $type,
            "body"      => ["text" => $button["body"]],
            "action"    => $button["action"]
        ];

        if(isset($button['header'])){
            $elem["header"] = ["type" => "text", "text" => $button["header"]];
        }
        
        if(isset($button['footer'])){
            $elem["footer"] = ["text" => $button["footer"]];
        }

        return $elem;
    }

    /**
     * @param $button
     * @param $recipientId
     * @return mixed
     */
    public function interactive(array $button, string $recipientId, string $type = "list")
    {
        $data = [
            "messaging_product" => "whatsapp",
            "to"                => $recipientId,
            "type"              => "interactive",
            "interactive"       => $this->createInteraction($button, $type),
        ];

        $url     = $this->config->getApiUri($this->uri);
        $bearer  = $this->config->getAccessToken();
        $headers = ["Authorization" => "Bearer {$bearer}"];

        $response = $this->http_request->post($url, $data, $headers);

        return $response;
    }
}
