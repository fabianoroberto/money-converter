<?php

declare(strict_types=1);

namespace App\Controller\Rest;

use App\Dto\Request\MathSumRequest;
use App\Service\MathService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(name="Catalogs")
 */
class ApiMathController extends AbstractFOSRestController
{
    public function __construct(private MathService $mathService)
    {
    }

    /**
     * @Rest\Post("/api/v1/math/sum", name="api_post_math_sum")
     * @ParamConverter("mathSumRequest", converter="fos_rest.request_body")
     *
     * @OA\RequestBody(
     *     @Model(type=MathSumRequest::class),
     * ),
     * @OA\Response(
     *     response=Response::HTTP_OK,
     *     description="Success"
     * )
     *
     * @throws \Exception
     */
    public function sum(MathSumRequest $mathSumRequest): JsonResponse
    {
        $toReturn = $this->mathService->sumGbpPrices($mathSumRequest->getPrice1(), $mathSumRequest->getPrice2());

        return new JsonResponse(['result' => $toReturn]);
    }
}
