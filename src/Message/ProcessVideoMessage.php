<?php
namespace App\Message;

class ProcessVideoMessage
{
    private int $videoId;
    private string $path;

    public function __construct(int $videoId, string $path)
    {
        $this->videoId = $videoId;
        $this->path = $path;
    }

    public function getVideoId(): int { return $this->videoId; }
    public function getPath(): string { return $this->path; }
}
