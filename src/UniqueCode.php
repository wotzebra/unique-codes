<?php

namespace NextApps\UniqueCodes;

use RuntimeException;

class UniqueCode
{
    /**
     * The number used to generate this unique code.
     *
     * @var int
     */
    protected $number;

    /**
     * The generated code.
     *
     * @var string
     */
    protected $code;

    /**
     * Create a new unique code instance.
     *
     * @param int $number
     * @param string $code
     *
     * @return void
     */
    public function __construct(int $number, string $code)
    {
        $this->number = $number;
        $this->code = $code;
    }

    /**
     * Get the number that was used to generate this unique code.
     *
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Get the generated code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
}
