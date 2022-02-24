<?php

namespace NextApps\UniqueCodes;

use RuntimeException;

class UniqueCodes
{
    /**
     * The prime number that is used to convert a number to a unique other number within the maximum range.
     *
     * @var int
     */
    protected $obfuscatingPrime;

    /**
     * The prime number that is one larger than the maximum number that can be converted to a code.
     *
     * @var int
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
     * Set the obfuscating prime number.
     *
     * @param int $prime
     *
     * @return self
     */
    public function setObfuscatingPrime(int $obfuscatingPrime)
    {
        $this->obfuscatingPrime = $obfuscatingPrime;

        return $this;
    }

    /**
     * Set the max prime number.
     *
     * @param int $maxPrime
     *
     * @return self
     */
    public function setMaxPrime(int $maxPrime)
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
     * @param int|null $splitLength
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
     * @param int|null $end
     * @param bool $toArray
     *
     * @return array|\Generator|string
     */
    public function generate(int $start, int $end = null, bool $toArray = false)
    {
        $this->validateInput($start, $end);

        $generator = (function () use ($start, $end) {
            for ($i = $start; $i <= ($end ?? $start); $i++) {
                $number = $this->obfuscateNumber($i);
                $string = $this->encodeNumber($number);

                yield $this->constructCode($string);
            }
        })();

        if ($end === null) {
            return iterator_to_array($generator)[0];
        }

        if ($toArray) {
            return iterator_to_array($generator);
        }

        return $generator;
    }

    /**
     * Map number to a unique other number smaller than the max prime number.
     *
     * @param int $number
     *
     * @return int
     */
    protected function obfuscateNumber(int $number)
    {
        return ($number * $this->obfuscatingPrime) % $this->maxPrime;
    }

    /**
     * Encode number into characters.
     *
     * @param int $number
     *
     * @return string
     */
    protected function encodeNumber(int $number)
    {
        $string = '';
        $characters = $this->characters;

        for ($i = 0; $i < $this->length; $i++) {
            $digit = (int) ($number % strlen($characters));

            $string = $characters[$digit] . $string;

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
     * @param int|null $end
     *
     * @throws \RuntimeException
     *
     * @return void
     */
    protected function validateInput(int $start, int $end = null)
    {
        if (empty($this->obfuscatingPrime)) {
            throw new RuntimeException('Obfuscating prime number must be specified');
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

        if ($this->obfuscatingPrime <= $this->maxPrime) {
            throw new RuntimeException('Obfuscating prime number must be larger than the max prime number');
        }

        if (count(array_unique(str_split($this->characters))) !== strlen($this->characters)) {
            throw new RuntimeException('The character list can not contain duplicates');
        }

        if ($this->getMaximumUniqueCodes() <= $this->maxPrime) {
            throw new RuntimeException(
                'The length of the code is too short or the character list is too small ' .
                'to create the number of unique codes equal to the max prime number'
            );
        }

        if ($start <= 0) {
            throw new RuntimeException('The start number must be bigger than zero');
        }

        if ($end !== null && $end >= $this->maxPrime) {
            throw new RuntimeException('The end number can not be bigger or equal to the max prime number');
        }
    }

    /**
     * Get the maximum amount of unique codes that can create based the characters.
     *
     * @return int
     */
    protected function getMaximumUniqueCodes()
    {
        return pow(strlen($this->characters), $this->length);
    }
}
