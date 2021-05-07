<?php

declare(strict_types=1);

namespace App\Controller\Rest;

use App\Dto\Request\MathDivRequest;
use App\Dto\Request\MathMulRequest;
use App\Dto\Request\MathSubRequest;
use App\Dto\Request\MathSumRequest;
use App\Service\MathService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Webmozart\Assert\InvalidArgumentException;

/**
 * @OA\Tag(name="Maths")
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
     * @OA\Response(
     *     response=Response::HTTP_BAD_REQUEST,
     *     description="Validation errors"
     * )
     *
     * @throws \Exception
     */
    public function sum(MathSumRequest $mathSumRequest, ConstraintViolationListInterface $validationErrors): Response
    {
        if ($validationErrors->count() > 0) {
            $view = $this->view($validationErrors, Response::HTTP_BAD_REQUEST);

            return $this->handleView($view);
        }

        $sum = $this->mathService->sumGbpPrices($mathSumRequest->getPrice1(), $mathSumRequest->getPrice2());
        $view = $this->view(['result' => $sum], Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Rest\Post("/api/v1/math/sub", name="api_post_math_sub")
     * @ParamConverter("mathSubRequest", converter="fos_rest.request_body")
     *
     * @OA\RequestBody(
     *     @Model(type=MathSubRequest::class),
     * ),
     * @OA\Response(
     *     response=Response::HTTP_OK,
     *     description="Success"
     * )
     * @OA\Response(
     *     response=Response::HTTP_BAD_REQUEST,
     *     description="Validation errors"
     * )
     *
     * @throws \Exception
     */
    public function sub(MathSubRequest $mathSubRequest, ConstraintViolationListInterface $validationErrors): Response
    {
        if ($validationErrors->count() > 0) {
            $view = $this->view($validationErrors, Response::HTTP_BAD_REQUEST);

            return $this->handleView($view);
        }

        try {
            $sub = $this->mathService->subGbpPrices($mathSubRequest->getPrice1(), $mathSubRequest->getPrice2());
            $view = $this->view(['result' => $sub], Response::HTTP_OK);
        } catch (InvalidArgumentException $exception) {
            $view = $this->view(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->handleView($view);
    }

    /**
     * @Rest\Post("/api/v1/math/mul", name="api_post_math_mul")
     * @ParamConverter("mathMulRequest", converter="fos_rest.request_body")
     *
     * @OA\RequestBody(
     *     @Model(type=MathMulRequest::class),
     * ),
     * @OA\Response(
     *     response=Response::HTTP_OK,
     *     description="Success"
     * )
     * @OA\Response(
     *     response=Response::HTTP_BAD_REQUEST,
     *     description="Validation errors"
     * )
     *
     * @throws \Exception
     */
    public function mul(MathMulRequest $mathMulRequest, ConstraintViolationListInterface $validationErrors): Response
    {
        if ($validationErrors->count() > 0) {
            $view = $this->view($validationErrors, Response::HTTP_BAD_REQUEST);

            return $this->handleView($view);
        }

        $mul = $this->mathService->mulGbpPrices($mathMulRequest->getPrice1(), $mathMulRequest->getPrice2());
        $view = $this->view(['result' => $mul], Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Rest\Post("/api/v1/math/div", name="api_post_math_div")
     * @ParamConverter("mathDivRequest", converter="fos_rest.request_body")
     *
     * @OA\RequestBody(
     *     @Model(type=MathDivRequest::class),
     * ),
     * @OA\Response(
     *     response=Response::HTTP_OK,
     *     description="Success"
     * )
     * @OA\Response(
     *     response=Response::HTTP_BAD_REQUEST,
     *     description="Validation errors"
     * )
     *
     * @throws \Exception
     */
    public function div(MathDivRequest $mathDivRequest, ConstraintViolationListInterface $validationErrors): Response
    {
        if ($validationErrors->count() > 0) {
            $view = $this->view($validationErrors, Response::HTTP_BAD_REQUEST);

            return $this->handleView($view);
        }

        try {
            $div = $this->mathService->divGbpPrices($mathDivRequest->getPrice1(), $mathDivRequest->getPrice2());
            $view = $this->view(['result' => $div], Response::HTTP_OK);
        } catch (InvalidArgumentException $exception) {
            $view = $this->view(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->handleView($view);
    }
}
