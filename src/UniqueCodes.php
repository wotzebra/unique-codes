<?php

namespace NextApps\UniqueCodes;

use RuntimeException;

class UniqueCodes
{
    protected int $obfuscatingPrime;

    protected int $maxPrime;

    protected ?string $suffix = null;

    protected ?string $prefix = null;

    protected ?string $delimiter = null;

    protected ?int $splitLength = null;

    protected string $characters;

    protected int $length;

    public function setObfuscatingPrime(int $obfuscatingPrime) : self
    {
        $this->obfuscatingPrime = $obfuscatingPrime;

        return $this;
    }

    public function setMaxPrime(int $maxPrime) : self
    {
        $this->maxPrime = $maxPrime;

        return $this;
    }

    public function setSuffix(string $suffix) : self
    {
        $this->suffix = $suffix;

        return $this;
    }

    public function setPrefix(string $prefix) : self
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function setDelimiter(string $delimiter, ?int $splitLength = null) : self
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
    public function setCharacters($characters) : self
    {
        if (is_array($characters)) {
            $characters = implode('', $characters);
        }

        $this->characters = $characters;

        return $this;
    }

    public function setLength(int $length) : self
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Generate the necessary amount of codes.
     *
     * @param int $start
     * @param null|int $end
     * @param bool $toArray
     *
     * @return \Generator|array|string
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

    protected function obfuscateNumber(int $number) : int
    {
        return ($number * $this->obfuscatingPrime) % $this->maxPrime;
    }

    protected function encodeNumber(int $number) : string
    {
        $string = '';
        $characters = $this->characters;

        for ($i = 0; $i < $this->length; $i++) {
            $digit = (int) $number % strlen($characters);

            $string = $characters[$digit].$string;

            $number = $number / strlen($characters);
        }

        return $string;
    }

    protected function constructCode(string $string) : string
    {
        $code = '';

        if ($this->prefix !== null) {
            $code .= $this->prefix.$this->delimiter;
        }

        if ($this->splitLength !== null) {
            $code .= implode($this->delimiter, str_split($string, $this->splitLength));
        } else {
            $code .= $string;
        }

        if ($this->suffix !== null) {
            $code .= $this->delimiter.$this->suffix;
        }

        return $code;
    }

    protected function validateInput(int $start, ?int $end = null) : void
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
                'The length of the code is too short or the character list is too small to create the number of unique codes equal to the max prime number'
            );
        }

        if ($start <= 0) {
            throw new RuntimeException('The start number must be bigger than zero');
        }

        if ($end !== null && $end >= $this->maxPrime) {
            throw new RuntimeException('The end number can not be bigger or equal to the max prime number');
        }
    }

    protected function getMaximumUniqueCodes() : int
    {
        return pow(strlen($this->characters), $this->length);
    }
}
