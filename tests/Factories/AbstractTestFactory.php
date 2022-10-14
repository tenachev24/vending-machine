<?php
declare(strict_types=1);

namespace Tests\Factories;

abstract class AbstractTestFactory
{
    protected ?string $description = null;

    protected array $expectedErrorMessages = [];

    protected array $data = [];

    protected ?bool $shouldPass = null;

    protected string $forCreateOrUpdate = 'create';

    /**
     * @return $this
     */
    public function forCreate(): self
    {
        $this->forCreateOrUpdate = 'create';

        return $this;
    }

    /**
     * @return $this
     */
    public function forUpdate(): self
    {
        $this->forCreateOrUpdate = 'update';

        return $this;
    }

    /**
     * @param array $messages
     * @return $this
     */
    public function withExpectedErrorMessages(array $messages): self
    {
        $this->expectedErrorMessages = $messages;

        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function withData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function withDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return $this
     */
    public function shouldPass(): self
    {
        $this->shouldPass = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function shouldNotPass(): self
    {
        $this->shouldPass = false;

        return $this;
    }

    /**
     * @return array
     */
    public function toDataProviderArray(): array
    {
        return [
            $this->description,
            $this->forCreateOrUpdate,
            $this->data,
            $this->expectedErrorMessages,
            $this->shouldPass,
        ];
    }
}
