<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function graphql(string $query)
    {
        return $this->post('/graphql', [
            'query' => $query
        ]);
    }

    public function assertJsonStructure(
        string $url,
        array $params,
        array $expected
    ) {
        $response = $this->json('post', $url, $params);
        try {
            $response->assertStatus(200)->assertJsonStructure($expected);
        } catch (\Exception $ex) {
            $this->printDie($params, $expected, $ex, $response);
        }
    }
    private function printDie($params, $expected, \Exception $ex, $response, $user_id = null)
    {
        $content = substr($response->getContent(), 0, 1500);
        $trace = debug_backtrace();
        $error = [
            'class' => static::class.'::'.$trace[2]['function'],
            'params' => $params,
            'expected' => $expected,
            'user' => $user_id,
            'error' => $ex->toString(),
            'content' => $content,
        ];
        dd($error);
    }
}
