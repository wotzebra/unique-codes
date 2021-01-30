<?php

namespace NextApps\UniqueCodes\Tests;

use Generator;
use ReflectionClass;
use RuntimeException;
use PHPUnit\Framework\TestCase;
use NextApps\UniqueCodes\UniqueCodes;

class UniqueCodesTest extends TestCase
{
    /**
     * @test
     * @dataProvider uniqueCodesProvider
     */
    public function it_generates_unique_codes($maxPrime, $obfuscatingPrime, $obfuscatingPrimeMultiplicativeInverse, $length, $characters)
    {
        $uniqueCodes = (new UniqueCodes())
            ->setObfuscatingPrime($obfuscatingPrime)
            ->setMaxPrime($maxPrime)
            ->setCharacters($characters)
            ->setLength($length)
            ->generate(1, $maxPrime - 1, true);

        $this->assertCount($maxPrime - 1, $uniqueCodes);
        $this->assertCount($maxPrime - 1, array_unique($uniqueCodes));

        foreach ($uniqueCodes as $index => $code) {
            $obfuscatedNumber = $this->decode($code, $characters);

            $this->assertEquals($index + 1, ($obfuscatedNumber * $obfuscatingPrimeMultiplicativeInverse) % $maxPrime);
        }
    }

    /**
     * @return array
     */
    public function uniqueCodesProvider()
    {
        return [
            [101, 387420489, 47, 3, 'ABCDE'],
            [101, 191, 55, 3, '12345'],
            [30983, 98893, 3925, 4, '123456ABCDEFGH'],
            [495563, 968197, 86214, 6, 'ABCDEFGHI'],
            [1340021, 6824473, 46234, 8, 'ABCDEF'],
            [7230323, 9006077, 4263725, 6, 'LQJCKZMWDPTSXRGANYVBHF']
        ];
    }

    /**
     * @test
     * @dataProvider obfuscateNumbersProvider
     */
    public function it_obfuscates_numbers($maxPrime, $obfuscatingPrime, $obfuscatingPrimeMultiplicativeInverse, $number, $expectedObfuscatedNumber)
    {
        $uniqueCodes = (new UniqueCodes())->setObfuscatingPrime($obfuscatingPrime)->setMaxPrime($maxPrime);

        $class = new ReflectionClass($uniqueCodes);
        $method = $class->getMethod('obfuscateNumber');
        $method->setAccessible(true);

        $this->assertEquals($expectedObfuscatedNumber, $obfuscatedNumber = $method->invokeArgs($uniqueCodes, [$number]));
        $this->assertEquals($number, ($obfuscatedNumber * $obfuscatingPrimeMultiplicativeInverse) % $maxPrime);
    }

    /**
     * @return array
     */
    public function obfuscateNumbersProvider()
    {
        return [
            [101, 387420489, 47, 1, 43],
            [101, 387420489, 47, 2, 86],
            [101, 387420489, 47, 3, 28],
            [101, 387420489, 47, 4, 71],
            [101, 387420489, 47, 5, 13],
            [101, 387420489, 47, 6, 56],
            [101, 387420489, 47, 7, 99],
            [101, 387420489, 47, 8, 41],
            [101, 387420489, 47, 9, 84],
            [101, 387420489, 47, 10, 26],
        ];
    }

    /**
     * @test
     * @dataProvider encodeNumbersProvider
     */
    public function it_encodes_numbers($start, $end, $length, $characters)
    {
        $uniqueCodes = (new UniqueCodes())->setLength($length)->setCharacters($characters);

        $class = new ReflectionClass($uniqueCodes);
        $method = $class->getMethod('encodeNumber');
        $method->setAccessible(true);

        $result = [];
        for ($i = $start; $i <= $end; $i++) {
            $string = $method->invokeArgs($uniqueCodes, [$i]);

            $this->assertEquals($i, $this->decode($string, $characters));

            $result[] = $string;
        }

        $this->assertCount($end - $start + 1, $result);
        $this->assertCount($end - $start + 1, array_unique($result));
        $this->assertEquals(0, $this->decode($string, $method->invokeArgs($uniqueCodes, [$end + 1])));
    }

    /**
     * @return array
     */
    public function encodeNumbersProvider()
    {
        return [
            [1, 1295, 4, 'ABCDEF'],
            [1, 531440, 6, 'ABCDEFGHI'],
            [1, 1419856, 5, 'ABCDEFGHIJLKMNOPQ'],
        ];
    }

    /**
     * Decode string to base10 number.
     *
     * @param string $string
     * @param string $alphabet
     *
     * @return int
     */
    public function decode(string $value, string $alphabet)
    {
        $digits = str_split($value);
        $stringLength = strlen($value);

        $characters = str_split($alphabet);
        $alphabetLength = strlen($alphabet);

        $result = 0;

        for ($i = 1; $i <= $stringLength; $i++) {
            $result += (array_search($digits[$i-1], $characters) * bcpow(strlen($alphabet), strlen($value) - $i));
        }

        return $result;
    }

    /** @test */
    public function it_returns_generator_by_default()
    {
        $codes = (new UniqueCodes())
            ->setObfuscatingPrime(191)
            ->setMaxPrime(101)
            ->setCharacters('ABCDEFGHI')
            ->setLength(6)
            ->generate(1, 100);

        $this->assertInstanceOf(Generator::class, $codes);
    }

    /** @test */
    public function it_returns_array_if_requested()
    {
        $codes = (new UniqueCodes())
            ->setObfuscatingPrime(191)
            ->setMaxPrime(101)
            ->setCharacters('ABCDEFGHI')
            ->setLength(6)
            ->generate(1, 100, true);

        $this->assertIsArray($codes);
    }

    /** @test */
    public function it_returns_string_if_no_end_provided()
    {
        $code = (new UniqueCodes())
            ->setObfuscatingPrime(191)
            ->setMaxPrime(101)
            ->setCharacters('ABCDEFGHI')
            ->setLength(6)
            ->generate(100);

        $this->assertIsString($code);
    }

    /** @test */
    public function it_generates_unique_codes_within_range()
    {
        $codes = iterator_to_array(
            (new UniqueCodes())
                ->setObfuscatingPrime(191)
                ->setMaxPrime(101)
                ->setCharacters('ABCDEFGHI')
                ->setLength(6)
                ->generate(25, 75)
        );

        $this->assertCount(51, $codes);
        $this->assertCount(51, array_unique($codes));
    }

    /** @test */
    public function it_generates_one_unique_code()
    {
        $codes = iterator_to_array(
            (new UniqueCodes())
                ->setObfuscatingPrime(191)
                ->setMaxPrime(101)
                ->setCharacters('ABCDEFGHI')
                ->setLength(6)
                ->generate(25, 25)
        );

        $this->assertCount(1, $codes);
        $this->assertCount(1, array_unique($codes));
    }

    /** @test */
    public function it_generates_codes_that_only_contain_characters_from_specified_character_list()
    {
        $codes = iterator_to_array(
            (new UniqueCodes())
                ->setObfuscatingPrime(191)
                ->setMaxPrime(101)
                ->setCharacters($characters = 'ABCDEFG')
                ->setLength(6)
                ->generate(1, 100)
        );

        foreach ($codes as $code) {
            $this->assertEquals(6, strlen($code));
            $this->assertCount(0, array_diff(str_split($code), str_split($characters)));
        }
    }

    /** @test */
    public function it_generates_codes_with_character_list_array()
    {
        $codes = iterator_to_array(
            (new UniqueCodes())
                ->setObfuscatingPrime(191)
                ->setMaxPrime(101)
                ->setCharacters($characters = ['A', 'B', 'C', 'D', 'E', 'F', 'G'])
                ->setLength(6)
                ->generate(1, 100)
        );

        $this->assertCount(100, array_unique($codes));

        foreach ($codes as $code) {
            $this->assertEquals(6, strlen($code));
            $this->assertCount(0, array_diff(str_split($code), $characters));
        }
    }

    /** @test */
    public function it_generates_codes_with_prefix()
    {
        $codes = iterator_to_array(
            (new UniqueCodes())
                ->setObfuscatingPrime(191)
                ->setMaxPrime(101)
                ->setCharacters('ABCDEFGHI')
                ->setLength(6)
                ->setPrefix('TEST')
                ->generate(1, 100)
        );

        $this->assertCount(100, array_unique($codes));

        foreach ($codes as $code) {
            $this->assertEquals(10, strlen($code));
            $this->assertEquals('TEST', substr($code, 0, 4));
        }
    }

    /** @test */
    public function it_generates_codes_with_suffix()
    {
        $codes = iterator_to_array(
            (new UniqueCodes())
                ->setObfuscatingPrime(191)
                ->setMaxPrime(101)
                ->setCharacters('ABCDEFGHI')
                ->setLength(6)
                ->setSuffix('TEST')
                ->generate(1, 100)
        );

        $this->assertCount(100, array_unique($codes));

        foreach ($codes as $code) {
            $this->assertEquals(10, strlen($code));
            $this->assertEquals('TEST', substr($code, 6, 4));
        }
    }

    /** @test */
    public function it_generates_codes_with_prefix_and_suffix()
    {
        $codes = iterator_to_array(
            (new UniqueCodes())
                ->setObfuscatingPrime(191)
                ->setMaxPrime(101)
                ->setCharacters('ABCDEFGHI')
                ->setLength(6)
                ->setPrefix('PREFIX')
                ->setSuffix('SUFFIX')
                ->generate(1, 100)
        );

        $this->assertCount(100, array_unique($codes));

        foreach ($codes as $code) {
            $this->assertEquals(18, strlen($code));
            $this->assertEquals('PREFIX', substr($code, 0, 6));
            $this->assertEquals('SUFFIX', substr($code, 12, 6));
        }
    }

    /** @test */
    public function it_generates_codes_with_delimiter()
    {
        $codes = iterator_to_array(
            (new UniqueCodes())
                ->setObfuscatingPrime(191)
                ->setMaxPrime(101)
                ->setCharacters('ABCDEFGHI')
                ->setLength(6)
                ->setDelimiter('-', 3)
                ->generate(1, 100)
        );

        $this->assertCount(100, array_unique($codes));

        foreach ($codes as $code) {
            $this->assertEquals(7, strlen($code));
            $this->assertEquals('-', substr($code, 3, 1));
        }
    }

    /** @test */
    public function it_generates_codes_with_suffix_and_prefix_and_delimiter()
    {
        $codes = iterator_to_array(
            (new UniqueCodes())
                ->setObfuscatingPrime(191)
                ->setMaxPrime(101)
                ->setCharacters('ABCDEFGHI')
                ->setLength(6)
                ->setPrefix('PREFIX')
                ->setSuffix('SUFFIX')
                ->setDelimiter('-', 3)
                ->generate(1, 100)
        );

        $this->assertCount(100, array_unique($codes));

        foreach ($codes as $code) {
            $this->assertEquals(21, strlen($code));
            $this->assertEquals('PREFIX-', substr($code, 0, 7));
            $this->assertEquals('-', substr($code, 10, 1));
            $this->assertEquals('-SUFFIX', substr($code, 14, 7));
        }
    }

    /** @test */
    public function it_throws_exception_if_obfuscating_prime_is_not_specified()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Obfuscating prime number must be specified');

        (new UniqueCodes())
            ->setMaxPrime(101)
            ->setCharacters('ABCDEFGHI')
            ->setLength(6)
            ->generate(1, 100);
    }

    /** @test */
    public function it_throws_exception_if_max_prime_is_not_specified()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Max prime number must be specified');

        (new UniqueCodes())
            ->setObfuscatingPrime(191)
            ->setCharacters('ABCDEFGHI')
            ->setLength(6)
            ->generate(1, 100);
    }

    /** @test */
    public function it_throws_exception_if_characters_are_not_specified()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Character list must be specified');

        (new UniqueCodes())
            ->setObfuscatingPrime(191)
            ->setMaxPrime(101)
            ->setLength(6)
            ->generate(1, 100);
    }

    /** @test */
    public function it_throws_exception_if_length_is_not_specified()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Length must be specified');

        (new UniqueCodes())
            ->setObfuscatingPrime(191)
            ->setMaxPrime(101)
            ->setCharacters('ABCDEFGHI')
            ->generate(1, 100);
    }

    /** @test */
    public function it_throws_exception_if_obfuscating_prime_number_is_smaller_than_max_prime_number()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Obfuscating prime number must be larger than the max prime number');

        (new UniqueCodes())
            ->setObfuscatingPrime(17)
            ->setMaxPrime(101)
            ->setCharacters('ABCDEFGHI')
            ->setLength(6)
            ->generate(1, 100);
    }

    /** @test */
    public function it_throws_exception_if_obfuscating_prime_number_is_equal_to_max_prime_number()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Obfuscating prime number must be larger than the max prime number');

        (new UniqueCodes())
            ->setObfuscatingPrime(101)
            ->setMaxPrime(101)
            ->setCharacters('ABCDEFGHI')
            ->setLength(6)
            ->generate(1, 100);
    }

    /** @test */
    public function it_throws_exception_if_character_list_contains_duplicates()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The character list can not contain duplicates');

        (new UniqueCodes())
            ->setObfuscatingPrime(191)
            ->setMaxPrime(101)
            ->setCharacters('ABCDEFGHIA')
            ->setLength(6)
            ->generate(1, 100);
    }

    /** @test */
    public function it_throws_exception_if_max_prime_number_is_too_big_for_the_specified_character_list_and_code_length()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The length of the code is too short or the character list is too small to create the number of unique codes equal to the max prime number');

        (new UniqueCodes())
            ->setObfuscatingPrime(191)
            ->setMaxPrime(101)
            ->setCharacters('ABCD')
            ->setLength(3)
            ->generate(1, 100);
    }

    /** @test */
    public function it_throws_exception_if_start_is_less_than_zero()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The start number must be bigger than zero');

        (new UniqueCodes())
            ->setObfuscatingPrime(191)
            ->setMaxPrime(101)
            ->setCharacters('ABCDEFGHI')
            ->setLength(6)
            ->generate(-1, 100);
    }

    /** @test */
    public function it_throws_exception_if_start_equals_zero()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The start number must be bigger than zero');

        (new UniqueCodes())
            ->setObfuscatingPrime(191)
            ->setMaxPrime(101)
            ->setCharacters('ABCDEFGHI')
            ->setLength(6)
            ->generate(0, 100);
    }

    /** @test */
    public function it_throws_exception_if_end_is_bigger_than_max_prime_number()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The end number can not be bigger or equal to the max prime number');

        (new UniqueCodes())
            ->setObfuscatingPrime(191)
            ->setMaxPrime(101)
            ->setCharacters('ABCDEFGHI')
            ->setLength(6)
            ->generate(50, 150);
    }

    /** @test */
    public function it_throws_exception_if_end_equals_max_prime_number()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The end number can not be bigger or equal to the max prime number');

        (new UniqueCodes())
            ->setObfuscatingPrime(191)
            ->setMaxPrime(101)
            ->setCharacters('ABCDEFGHI')
            ->setLength(6)
            ->generate(50, 101);
    }
}
