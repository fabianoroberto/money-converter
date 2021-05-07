<?php

declare(strict_types=1);

namespace App\Tests\Controller\Rest;

use App\Tests\JsonApiTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @covers \App\Controller\Rest\ApiMathController
 *
 * @internal
 */
class ApiMathControllerTest extends JsonApiTestCase
{
    /**
     * @covers \App\Controller\Rest\ApiMathController::sum
     */
    public function testSuccessfulSum(): void
    {
        $data = $this->apiRequest('POST', '/api/v1/math/sum', [
            'addend1' => '5p 17s 8d',
            'addend2' => '3p 4s 10d',
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertSame(['result' => '9p 2s 6d'], $data);
    }

    /**
     * @covers \App\Controller\Rest\ApiMathController::sub
     */
    public function testSuccessfulSub()
    {
        $data = $this->apiRequest('POST', '/api/v1/math/sub', [
            'minuend' => '5p 17s 8d',
            'subtrahend' => '3p 4s 10d',
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertSame(['result' => '2p 12s 10d'], $data);
    }

    /**
     * @covers \App\Controller\Rest\ApiMathController::syb
     */
    public function testFailedSubDueMinuendLessThanSubtrahend()
    {
        $data = $this->apiRequest('POST', '/api/v1/math/sub', [
            'subtrahend' => '5p 17s 8d',
            'minuend' => '3p 4s 10d',
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertSame(['error' => 'Minuend cannot less than subtrahend'], $data);
    }

    /**
     * @covers \App\Controller\Rest\ApiMathController::mul
     */
    public function testSuccessfulMul()
    {
        $data = $this->apiRequest('POST', '/api/v1/math/mul', [
            'factor1' => '5p 17s 8d',
            'factor2' => '2',
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertSame(['result' => '11p 15s 4d'], $data);
    }

    /**
     * @covers \App\Controller\Rest\ApiMathController::div
     */
    public function testSuccessfulDiv()
    {
        $data = $this->apiRequest('POST', '/api/v1/math/div', [
            'dividend' => '18p 16s 1d',
            'divisor' => '15',
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertSame(['result' => '1p 5s 0d (1s 1d)'], $data);
    }

    /**
     * @covers \App\Controller\Rest\ApiMathController::div
     */
    public function testFailedDivDueDividendLessThanDivisor()
    {
        $data = $this->apiRequest('POST', '/api/v1/math/div', [
            'dividend' => '1p 16s 1d',
            'divisor' => '2p 16s 1d',
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertSame(['error' => 'Dividend cannot less than divisor'], $data);
    }
}
