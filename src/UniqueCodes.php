<?php

namespace App\UniqueCodes;

use RuntimeException;

class UniqueCodesGenerator
{
    /**
     * The prime number that is used to convert a number to a unique other number within the maximum range.
     *
     * @var float
     */
    protected $prime;

    /**
     * The prime number that is one larger than the maximum number that can be converted to a code.
     *
     * @var float
     */
    protected $maxPrime;

    /**
     * The suffix that will be added to every code.
     *
     * @var string|null
     */
    protected $suffix;

    /**
     * The prefix that will be added to every code.
     *
     * @var string|null
     */
    protected $prefix;

    /**
     * The delimiter that separates the different parts of the generated code.
     *
     * @var string|null
     */
    protected $delimiter;

    /**
     * The size of every part of the generated code.
     *
     * @var int|null
     */
    protected $splitLength;

    /**
     * The list of characters that a generated code can contain.
     *
     * @var string
     */
    protected $characters;

    /**
     * The length of the code.
     *
     * @var int
     */
    protected $length;

    /**
     * Set the prime number.
     *
     * @param float $prime
     *
     * @return self
     */
    public function setPrime(float $prime)
    {
        $this->prime = $prime;

        return $this;
    }

    /**
     * Set the max prime number.
     *
     * @param float $maxPrime
     *
     * @return self
     */
    public function setMaxPrime(float $maxPrime)
    {
        $this->maxPrime = $maxPrime;

        return $this;
    }

    /**
     * Set the suffix.
     *
     * @param string $suffix
     *
     * @return self
     */
    public function setSuffix(string $suffix)
    {
        $this->suffix = $suffix;

        return $this;
    }

    /**
     * Set the prefix.
     *
     * @param string $prefix
     *
     * @return self
     */
    public function setPrefix(string $prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Set the delimiter.
     *
     * @param string $delimiter
     * @param null|int $splitLength
     *
     * @return self
     */
    public function setDelimiter(string $delimiter, int $splitLength = null)
    {
        $this->delimiter = $delimiter;
        $this->splitLength = $splitLength;

        return $this;
    }

    /**
     * Set the characters.
     *
     * @param array|string $characters
     *
     * @return self
     */
    public function setCharacters($characters)
    {
        if (is_array($characters)) {
            $characters = implode('', $characters);
        }

        $this->characters = $characters;

        return $this;
    }

    /**
     * Set the length.
     *
     * @param int $length
     *
     * @return self
     */
    public function setLength(int $length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Generate the necessary amount of codes.
     *
     * @param int $start
     * @param int $amount
     *
     * @return Generator<string>
     */
    public function generate(int $start, int $amount = 1)
    {
        $this->validateInput($start, $amount);

        $codes = [];

        $end = $start + $amount;

        for ($i = $start; $i < $end; $i++) {
            $number = $this->mapNumber($i);
            $string = $this->encodeNumber($number);

            $codes[] = $this->constructCode($string);
        }

        return $codes;
    }

    /**
     * Map number to a unique other number smaller than the max prime number.
     *
     * @param float|int $number
     *
     * @return float|int
     */
    protected function mapNumber($number)
    {
        return ($number * $this->prime) % $this->maxPrime;
    }

    /**
     * Encode number into characters.
     *
     * @param float|int $number
     *
     * @return string
     */
    protected function encodeNumber($number)
    {
        $string = '';
        $characters = $this->characters;

        for ($i = 0; $i < $this->length; $i++) {
            $digit = $number % strlen($characters);

            $string .= $characters[$digit];
            $characters = strtr($characters, $characters[$digit], '');

            $number = $number / strlen($characters);
        }

        return $string;
    }

    /**
     * Construct the code.
     *
     * @param string $string
     *
     * @return string
     */
    protected function constructCode($string)
    {
        $code = '';

        if ($this->prefix !== null) {
            $code .= $this->prefix . $this->delimiter;
        }

        if ($this->splitLength !== null) {
            $code .= implode($this->delimiter, str_split($string, $this->splitLength));
        } else {
            $code .= $string;
        }

        if ($this->suffix !== null) {
            $code .= $this->delimiter . $this->suffix;
        }

        return $code;
    }

    /**
     * Check if all property values are valid.
     *
     * @param int $start
     * @param int $amount
     *
     * @throws \RuntimeException
     *
     * @return void
     */
    protected function validateInput(int $start, int $amount = 1)
    {
        if (empty($this->prime)) {
            throw new RuntimeException('Prime number must be specified');
        }

        if (empty($this->maxPrime)) {
            throw new RuntimeException('Max prime number must be specified');
        }

        if (empty($this->characters)) {
            throw new RuntimeException('Character list must be specified');
        }

        if (empty($this->length)) {
            throw new RuntimeException('Length must be specified');
        }

        if ($this->prime <= $this->maxPrime) {
            throw new RuntimeException('Prime number must be larger than the max prime number');
        }

        if (strlen($this->characters) <= $this->length) {
            throw new RuntimeException('The size of the character list must be bigger or equal to the length of the code');
        }

        if (count(array_unique(str_split($this->characters))) !== strlen($this->characters)) {
            throw new RuntimeException('The character list can not contain duplicates');
        }

        if ($this->getMaximumUniqueCodes() <= $this->maxPrime) {
            throw new RuntimeException('The length of the code is too short to create the number of unique codes equal to the max prime number');
        }

        if ($amount >= $this->maxPrime) {
            throw new RuntimeException('The number of codes you create can not be bigger or equal to the max prime number');
        }
    }

    /**
     * Get the maximum amount of unique codes that can create based the characters.
     *
     * @return int
     */
    protected function getMaximumUniqueCodes()
    {
        $maxCombinations = 1;

        for ($i = 0; $i < $this->length; $i++) {
            $maxCombinations = $maxCombinations * (strlen($this->characters) - $i);
        }

        return $maxCombinations;
    }
}
