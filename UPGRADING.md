# Upgrading

## From v1 to v2

- The `setPrime` method has been renamed to `setObfuscatingPrime'`. The number you provide to this method should also be larger than the prime number you provide to the `setMaxPrime` number.
- You should change the code length you use (if you can not regenerate all the codes you created in v1). If you generate code using a number in v2 you will not receive the same code as in v1. This means there could be collisions between your v1 and v2 codes. Changing the code length of your v2 codes will prevent such collisions.
- The encoding mechanism in v1 could sometimes generate duplicates because it tried to prevent duplicate characters in the encoded result. This logic has been removed, which also means that codes will now contain duplicate characters. If you don't want that, you could always just skip the numbers that are converted in a unique code with duplicate characters.
