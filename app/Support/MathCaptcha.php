<?php

namespace App\Support;

class MathCaptcha
{
    /**
     * Generate a new arithmetic challenge, persist it in the session and
     * return the operands + operator so the view can render the question.
     *
     * @return array{int, string, int}
     */
    public static function generate(): array
    {
        $a = random_int(1, 9);
        $b = random_int(1, 9);
        $operators = ['+', '-'];
        $operator = $operators[array_rand($operators)];

        // Ensure the result is always non-negative.
        if ($operator === '-' && $b > $a) {
            [$a, $b] = [$b, $a];
        }

        $answer = $operator === '+' ? $a + $b : $a - $b;

        session()->put('math_captcha', $answer);
        session()->put('math_captcha_created_at', time());

        return [$a, $operator, $b];
    }

    /**
     * Validate the submitted answer against the stored challenge.
     */
    public static function validate(mixed $submitted): bool
    {
        $expected = session()->get('math_captcha');
        $createdAt = session()->get('math_captcha_created_at');

        // Challenge must exist and be fresh (max 10 minutes).
        if ($expected === null || $createdAt === null || (time() - (int) $createdAt) > 600) {
            return false;
        }

        self::forget();

        return $expected == $submitted;
    }

    /**
     * Clear the stored challenge.
     */
    public static function forget(): void
    {
        session()->forget(['math_captcha', 'math_captcha_created_at']);
    }
}
