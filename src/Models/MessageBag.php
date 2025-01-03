<?php

namespace Vluzrmos\Precodahora\Models;

class MessageBag
{
    protected array $messages = [];

    public function __construct(array $messages = [])
    {
        $this->fromArray($messages);
    }

    public function fromArray(array $messages = [])
    {
        foreach ($messages as $key => $group) {
            $group = is_array($group) ? $group : [$group];

            foreach ($group as $message) {
                $this->add($key, $message);
            }
        }
    }

    public function add(string $key, string $message)
    {
        $this->messages[$key][] = $message;

        return $this;
    }

    public function messages(): array
    {
        return $this->messages;
    }

    public function all(): array
    {
        $all = [];

        foreach ($this->messages as $messages) {
            $all = array_merge($all, $messages);
        }

        return $all;
    }

    public function has(string $key): bool
    {
        return isset($this->messages[$key]);
    }

    public function first(?string $key = null): ?string
    {
        return $this->messages[$key][0] ?? null;
    }

    public function get(string $key): ?array
    {
        return $this->messages[$key] ?? null;
    }

    public function isEmpty(): bool
    {
        return empty($this->messages);
    }

    public function clear()
    {
        $this->messages = [];
    }
}
