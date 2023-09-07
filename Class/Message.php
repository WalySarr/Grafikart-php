<?php

class Message
{
    private $pseudo;
    private $message;
    private $date;

    const LIMIT_PSEUDO = 3;
    const LIMIT_MESSAGE = 8;

    public function __construct(string $pseudo, string $message, DateTime $date = null)
    {
        $this->pseudo = $pseudo;
        $this->message = $message;
        $this->date = $date ?: new DateTime();
    }

    public static function fromJson(string $json): Message
    {
        $data = json_decode($json, true);
        return new self($data["pseudo"], $data["message"], new DateTime("@" . $data["date"]));
    }
    public function isValid(): bool
    {
        return empty($this->getErrors());
    }

    public function getErrors(): array
    {
        $errors = [];
        if (strlen($this->pseudo) < self::LIMIT_PSEUDO) {
            $errors['limitPseudo'] = "Le pseudo doit faire au moins " . self::LIMIT_PSEUDO . " caractères";
        };
        if (!preg_match("#^[a-zA-Z0-9]+$#", $this->pseudo)) {
            $errors['pregPseudo'] = "Votre nom de compte ne peut contenir que des lettres
            et chiffres";
        };

        if (strlen($this->message) < self::LIMIT_MESSAGE) {
            $errors['message'] = "Le message doit faire au moins " . self::LIMIT_MESSAGE . "  caractères";
        };

        return $errors;
    }

    public function toHTML(): string
    {
        $username = htmlentities($this->pseudo);
        $this->date->setTimezone(new DateTimeZone("Africa/Dakar"));
        $date = $this->date->format("d/m/Y à H:i");

        $message = nl2br(htmlentities($this->message));
        return <<<HTML
    <p>
    <strong>{$username}</strong> le <em> {$date}</em><br>
    {$message}
    </p>

HTML;
    }

    public function toJson(): string
    {
        return json_encode([
            "pseudo" => $this->pseudo,
            "message" => htmlspecialchars($this->message),
            "date" => $this->date->getTimestamp()
        ]);
    }
}
