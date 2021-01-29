<?php

namespace NextApps\UniqueCodes\Tests;

use Generator;
use NextApps\UniqueCodes\UniqueCode;
use NextApps\UniqueCodes\UniqueCodes;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class UniqueCodesTest extends TestCase
{
    /** @test */
    public function it_returns_generator_by_default()
    {
        // var_dump(count(array_unique(str_split('H2ZMLL'))));
        // die();

        $a = (new UniqueCodes())
        ->setPrime(184259)
        ->setMaxPrime(7230323)
        ->setCharacters('LQJCKZM4WDPT69S7XRGANY23VBH58F1')
        ->setLength(6)
        ->generate(1, 200000, true)
        ->filter(function (UniqueCode $uniqueCode) {
            return count(array_unique(str_split($uniqueCode->getCode()))) > 4; //183729 results
            if (count(array_unique(str_split($uniqueCode->getCode()))) === 3) {
                var_dump($uniqueCode->getCode());
                die();
            }
        });

        // 31*31*31*31*30*29

        // 31*31*31*31*31*3

        var_dump($a);
        var_dump(count($a->all()));
        die();
        var_dump(($b = array_values($a->all()))[0]->getCode());
        var_dump($b[1]->getCode());
        var_dump($b[2]->getCode());
        var_dump($b[3]->getCode());
        var_dump($b[4]->getCode());
        var_dump($b[5]->getCode());
        var_dump($b[6]->getCode());
        var_dump($b[7]->getCode());
        var_dump($b[8]->getCode());
        var_dump($b[9]->getCode());
        var_dump($b[10]->getCode());
        var_dump($b[11]->getCode());
        var_dump($b[12]->getCode());
        die();
        // ["number":protected]=>
        // int(1901614)
        // ["code":protected]=>
        // string(6) "WQL44C"

        var_dump(count($a));
        var_dump(count(array_values(array_unique($a))));

        foreach ($a as $index => $item) {
            var_dump($item);
            if ($index > 50000) {
                die();
            }
        }

        var_dump($a[0], $a[1], $a[2], $a[3]);
        die();

        $codes = (new UniqueCodes())
            ->setPrime(17)
            ->setMaxPrime(101)
            ->setCharacters('LQJCKZM4WDPT69S7XRGANY23VBH58F1')
            ->setLength(6)
            ->setMaxDuplicateCharacters(4)
            ->generate(1, 100, true);

        var_dump(count($codes));

        var_dump($codes);
        var_dump(count(array_filter($codes)));

        // $this->assertInstanceOf(Generator::class, $codes);
    }

    /* @test */
    // public function it_returns_array_if_requested()
    // {
    //     $codes = (new UniqueCodes())
    //         ->setPrime(17)
    //         ->setMaxPrime(101)
    //         ->setCharacters('LQJCKZM4WDPT69S7XRGANY23VBH58F1')
    //         ->setLength(6)
    //         ->generate(1, 100, true);

    //     $this->assertIsArray($codes);
    // }

    // /** @test */
    // public function it_returns_string_if_no_end_provided()
    // {
    //     $code = (new UniqueCodes())
    //         ->setPrime(17)
    //         ->setMaxPrime(101)
    //         ->setCharacters('LQJCKZM4WDPT69S7XRGANY23VBH58F1')
    //         ->setLength(6)
    //         ->generate(100);

    //     $this->assertIsString($code);
    // }

    // /** @test */
    // public function it_generates_unique_codes()
    // {
    //     $codes = iterator_to_array(
    //         (new UniqueCodes())
    //             ->setPrime(17)
    //             ->setMaxPrime(101)
    //             ->setCharacters('LQJCKZM4WDPT69S7XRGANY23VBH58F1')
    //             ->setLength(6)
    //             ->generate(1, 100)
    //     );

    //     $this->assertCount(100, $codes);
    //     $this->assertCount(100, array_unique($codes));

    //     $codes = iterator_to_array(
    //         (new UniqueCodes())
    //             ->setPrime(30983)
    //             ->setMaxPrime(98893)
    //             ->setCharacters('LQJCKZM4WDPT69S7XRGANY23VBH58F1')
    //             ->setLength(6)
    //             ->generate(1, 98892)
    //     );

    //     $this->assertCount(98892, $codes);
    //     $this->assertCount(98892, array_unique($codes));

    //     $codes = iterator_to_array(
    //         (new UniqueCodes())
    //             ->setPrime(13)
    //             ->setMaxPrime(113)
    //             ->setCharacters('ABCDE')
    //             ->setLength(4)
    //             ->generate(1, 112)
    //     );

    //     $this->assertCount(112, $codes);
    //     $this->assertCount(112, array_unique($codes));
    // }

    // /** @test */
    // public function it_generates_unique_codes_within_range()
    // {
    //     $codes = iterator_to_array(
    //         (new UniqueCodes())
    //             ->setPrime(17)
    //             ->setMaxPrime(101)
    //             ->setCharacters('LQJCKZM4WDPT69S7XRGANY23VBH58F1')
    //             ->setLength(6)
    //             ->generate(25, 75)
    //     );

    //     $this->assertCount(51, $codes);
    //     $this->assertCount(51, array_unique($codes));
    // }

    // /** @test */
    // public function it_generates_one_unique_code()
    // {
    //     $codes = iterator_to_array(
    //         (new UniqueCodes())
    //             ->setPrime(17)
    //             ->setMaxPrime(101)
    //             ->setCharacters('LQJCKZM4WDPT69S7XRGANY23VBH58F1')
    //             ->setLength(6)
    //             ->generate(25, 25)
    //     );

    //     $this->assertCount(1, $codes);
    //     $this->assertCount(1, array_unique($codes));
    // }

    // /** @test */
    // public function it_generates_codes_that_only_contain_characters_from_specified_character_list()
    // {
    //     $codes = iterator_to_array(
    //         (new UniqueCodes())
    //             ->setPrime(17)
    //             ->setMaxPrime(101)
    //             ->setCharacters($characters = 'ABCDEFG')
    //             ->setLength(6)
    //             ->generate(1, 100)
    //     );

    //     foreach ($codes as $code) {
    //         $this->assertEquals(6, strlen($code));
    //         $this->assertCount(0, array_diff(str_split($code), str_split($characters)));
    //     }
    // }

    // /** @test */
    // public function it_generates_codes_with_character_list_array()
    // {
    //     $codes = iterator_to_array(
    //         (new UniqueCodes())
    //             ->setPrime(17)
    //             ->setMaxPrime(101)
    //             ->setCharacters($characters = ['A', 'B', 'C', 'D', 'E', 'F', 'G'])
    //             ->setLength(6)
    //             ->generate(1, 100)
    //     );

    //     $this->assertCount(100, array_unique($codes));

    //     foreach ($codes as $code) {
    //         $this->assertEquals(6, strlen($code));
    //         $this->assertCount(0, array_diff(str_split($code), $characters));
    //     }
    // }

    // /** @test */
    // public function it_generates_codes_with_prefix()
    // {
    //     $codes = iterator_to_array(
    //         (new UniqueCodes())
    //             ->setPrime(17)
    //             ->setMaxPrime(101)
    //             ->setCharacters('LQJCKZM4WDPT69S7XRGANY23VBH58F1')
    //             ->setLength(6)
    //             ->setPrefix('TEST')
    //             ->generate(1, 100)
    //     );

    //     $this->assertCount(100, array_unique($codes));

    //     foreach ($codes as $code) {
    //         $this->assertEquals(10, strlen($code));
    //         $this->assertEquals('TEST', substr($code, 0, 4));
    //     }
    // }

    // /** @test */
    // public function it_generates_codes_with_suffix()
    // {
    //     $codes = iterator_to_array(
    //         (new UniqueCodes())
    //             ->setPrime(17)
    //             ->setMaxPrime(101)
    //             ->setCharacters('LQJCKZM4WDPT69S7XRGANY23VBH58F1')
    //             ->setLength(6)
    //             ->setSuffix('TEST')
    //             ->generate(1, 100)
    //     );

    //     $this->assertCount(100, array_unique($codes));

    //     foreach ($codes as $code) {
    //         $this->assertEquals(10, strlen($code));
    //         $this->assertEquals('TEST', substr($code, 6, 4));
    //     }
    // }

    // /** @test */
    // public function it_generates_codes_with_prefix_and_suffix()
    // {
    //     $codes = iterator_to_array(
    //         (new UniqueCodes())
    //             ->setPrime(17)
    //             ->setMaxPrime(101)
    //             ->setCharacters('LQJCKZM4WDPT69S7XRGANY23VBH58F1')
    //             ->setLength(6)
    //             ->setPrefix('PREFIX')
    //             ->setSuffix('SUFFIX')
    //             ->generate(1, 100)
    //     );

    //     $this->assertCount(100, array_unique($codes));

    //     foreach ($codes as $code) {
    //         $this->assertEquals(18, strlen($code));
    //         $this->assertEquals('PREFIX', substr($code, 0, 6));
    //         $this->assertEquals('SUFFIX', substr($code, 12, 6));
    //     }
    // }

    // /** @test */
    // public function it_generates_codes_with_delimiter()
    // {
    //     $codes = iterator_to_array(
    //         (new UniqueCodes())
    //             ->setPrime(17)
    //             ->setMaxPrime(101)
    //             ->setCharacters('LQJCKZM4WDPT69S7XRGANY23VBH58F1')
    //             ->setLength(6)
    //             ->setDelimiter('-', 3)
    //             ->generate(1, 100)
    //     );

    //     $this->assertCount(100, array_unique($codes));

    //     foreach ($codes as $code) {
    //         $this->assertEquals(7, strlen($code));
    //         $this->assertEquals('-', substr($code, 3, 1));
    //     }
    // }

    // /** @test */
    // public function it_generates_codes_with_suffix_and_prefix_and_delimiter()
    // {
    //     $codes = iterator_to_array(
    //         (new UniqueCodes())
    //             ->setPrime(17)
    //             ->setMaxPrime(101)
    //             ->setCharacters('LQJCKZM4WDPT69S7XRGANY23VBH58F1')
    //             ->setLength(6)
    //             ->setPrefix('PREFIX')
    //             ->setSuffix('SUFFIX')
    //             ->setDelimiter('-', 3)
    //             ->generate(1, 100)
    //     );

    //     $this->assertCount(100, array_unique($codes));

    //     foreach ($codes as $code) {
    //         $this->assertEquals(21, strlen($code));
    //         $this->assertEquals('PREFIX-', substr($code, 0, 7));
    //         $this->assertEquals('-', substr($code, 10, 1));
    //         $this->assertEquals('-SUFFIX', substr($code, 14, 7));
    //     }
    // }

    // /** @test */
    // public function it_throws_exception_if_prime_is_not_specified()
    // {
    //     $this->expectException(RuntimeException::class);
    //     $this->expectExceptionMessage('Prime number must be specified');

    //     (new UniqueCodes())
    //         ->setMaxPrime(101)
    //         ->setCharacters('LQJCKZM4WDPT69S7XRGANY23VBH58F1')
    //         ->setLength(6)
    //         ->generate(1, 100);
    // }

    // /** @test */
    // public function it_throws_exception_if_max_prime_is_not_specified()
    // {
    //     $this->expectException(RuntimeException::class);
    //     $this->expectExceptionMessage('Max prime number must be specified');

    //     (new UniqueCodes())
    //         ->setPrime(17)
    //         ->setCharacters('LQJCKZM4WDPT69S7XRGANY23VBH58F1')
    //         ->setLength(6)
    //         ->generate(1, 100);
    // }

    // /** @test */
    // public function it_throws_exception_if_characters_are_not_specified()
    // {
    //     $this->expectException(RuntimeException::class);
    //     $this->expectExceptionMessage('Character list must be specified');

    //     (new UniqueCodes())
    //         ->setPrime(17)
    //         ->setMaxPrime(101)
    //         ->setLength(6)
    //         ->generate(1, 100);
    // }

    // /** @test */
    // public function it_throws_exception_if_length_is_not_specified()
    // {
    //     $this->expectException(RuntimeException::class);
    //     $this->expectExceptionMessage('Length must be specified');

    //     (new UniqueCodes())
    //         ->setPrime(17)
    //         ->setMaxPrime(101)
    //         ->setCharacters('LQJCKZM4WDPT69S7XRGANY23VBH58F1')
    //         ->generate(1, 100);
    // }

    // /** @test */
    // public function it_throws_exception_if_prime_number_is_bigger_than_max_prime_number()
    // {
    //     $this->expectException(RuntimeException::class);
    //     $this->expectExceptionMessage('Prime number must be smaller than the max prime number');

    //     (new UniqueCodes())
    //         ->setPrime(101)
    //         ->setMaxPrime(17)
    //         ->setCharacters('LQJCKZM4WDPT69S7XRGANY23VBH58F1')
    //         ->setLength(6)
    //         ->generate(1, 100);
    // }

    // /** @test */
    // public function it_throws_exception_if_prime_number_is_equal_to_max_prime_number()
    // {
    //     $this->expectException(RuntimeException::class);
    //     $this->expectExceptionMessage('Prime number must be smaller than the max prime number');

    //     (new UniqueCodes())
    //         ->setPrime(101)
    //         ->setMaxPrime(101)
    //         ->setCharacters('LQJCKZM4WDPT69S7XRGANY23VBH58F1')
    //         ->setLength(6)
    //         ->generate(1, 100);
    // }

    // /** @test */
    // public function it_throws_exception_if_size_of_character_list_is_smaller_than_specified_code_length()
    // {
    //     $this->expectException(RuntimeException::class);
    //     $this->expectExceptionMessage('The size of the character list must be bigger or equal to the length of the code');

    //     (new UniqueCodes())
    //         ->setPrime(17)
    //         ->setMaxPrime(101)
    //         ->setCharacters('LQJCK')
    //         ->setLength(6)
    //         ->generate(1, 100);
    // }

    // /** @test */
    // public function it_throws_exception_if_size_of_character_list_equals_specified_code_length()
    // {
    //     $this->expectException(RuntimeException::class);
    //     $this->expectExceptionMessage('The size of the character list must be bigger or equal to the length of the code');

    //     (new UniqueCodes())
    //         ->setPrime(17)
    //         ->setMaxPrime(101)
    //         ->setCharacters('LQJCKZ')
    //         ->setLength(6)
    //         ->generate(1, 100);
    // }

    // /** @test */
    // public function it_throws_exception_if_character_list_contains_duplicates()
    // {
    //     $this->expectException(RuntimeException::class);
    //     $this->expectExceptionMessage('The character list can not contain duplicates');

    //     (new UniqueCodes())
    //         ->setPrime(17)
    //         ->setMaxPrime(101)
    //         ->setCharacters('LQJCKZL')
    //         ->setLength(6)
    //         ->generate(1, 100);
    // }

    // /** @test */
    // public function it_throws_exception_if_max_prime_number_is_too_big_for_the_specified_character_list_and_code_length()
    // {
    //     $this->expectException(RuntimeException::class);
    //     $this->expectExceptionMessage('The length of the code is too short or the character list is too small to create the number of unique codes equal to the max prime number');

    //     (new UniqueCodes())
    //         ->setPrime(17)
    //         ->setMaxPrime(101)
    //         ->setCharacters('LQJC')
    //         ->setLength(3)
    //         ->generate(1, 100);
    // }

    // /** @test */
    // public function it_throws_exception_if_start_is_less_than_zero()
    // {
    //     $this->expectException(RuntimeException::class);
    //     $this->expectExceptionMessage('The start number must be bigger than zero');

    //     (new UniqueCodes())
    //         ->setPrime(17)
    //         ->setMaxPrime(101)
    //         ->setCharacters('LQJCKZM4WDPT69S7XRGANY23VBH58F1')
    //         ->setLength(6)
    //         ->generate(-1, 100);
    // }

    // /** @test */
    // public function it_throws_exception_if_start_equals_zero()
    // {
    //     $this->expectException(RuntimeException::class);
    //     $this->expectExceptionMessage('The start number must be bigger than zero');

    //     (new UniqueCodes())
    //         ->setPrime(17)
    //         ->setMaxPrime(101)
    //         ->setCharacters('LQJCKZM4WDPT69S7XRGANY23VBH58F1')
    //         ->setLength(6)
    //         ->generate(0, 100);
    // }

    // /** @test */
    // public function it_throws_exception_if_end_is_bigger_than_max_prime_number()
    // {
    //     $this->expectException(RuntimeException::class);
    //     $this->expectExceptionMessage('The end number can not be bigger or equal to the max prime number');

    //     (new UniqueCodes())
    //         ->setPrime(17)
    //         ->setMaxPrime(101)
    //         ->setCharacters('LQJCKZM4WDPT69S7XRGANY23VBH58F1')
    //         ->setLength(6)
    //         ->generate(50, 150);
    // }

    // /** @test */
    // public function it_throws_exception_if_end_equals_max_prime_number()
    // {
    //     $this->expectException(RuntimeException::class);
    //     $this->expectExceptionMessage('The end number can not be bigger or equal to the max prime number');

    //     (new UniqueCodes())
    //         ->setPrime(17)
    //         ->setMaxPrime(101)
    //         ->setCharacters('LQJCKZM4WDPT69S7XRGANY23VBH58F1')
    //         ->setLength(6)
    //         ->generate(50, 101);
    // }
}
