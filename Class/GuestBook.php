<?php
require_once "Message.php";
class GuestBook
{

    private $file;

    public function __construct($file)
    {
        $directory = dirname($file);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        if (!file_exists($file)) {
            touch($file);
        }

        $this->file = $file;
    }

    public function addMessage(Message $message)
    {
        file_put_contents($this->file, $message->toJson() . PHP_EOL, FILE_APPEND);
    }

    public function getMessage(): array
    {
        $messages = [];
        $content = trim(file_get_contents($this->file, true));
        $lines = explode(PHP_EOL, $content);
        foreach ($lines as $line) {
            $messages[] = Message::fromJson($line);
        }
        return array_reverse($messages);
    }
}
