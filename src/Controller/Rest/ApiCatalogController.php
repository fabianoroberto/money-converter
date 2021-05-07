<?php

declare(strict_types=1);

namespace App\Controller\Rest;

use App\Controller\Rest\Traits\ErrorResponseTrait;
use App\Controller\Rest\Traits\HateoasResponseTrait;
use App\Dto\Request\CatalogCreateRequest;
use App\Dto\Request\CatalogUpdateRequest;
use App\Entity\Catalog;
use App\Repository\CatalogRepositoryInterface;
use App\Service\CatalogService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @OA\Tag(name="Catalogs")
 */
class ApiCatalogController extends AbstractFOSRestController
{
    use ErrorResponseTrait;
    use HateoasResponseTrait;

    public function __construct(
        private CatalogRepositoryInterface $catalogRepository,
        private CatalogService $catalogService,
    ) {
    }

    /**
     * @Rest\Get("/api/v1/catalogs", name="api_get_catalogs")
     *
     * @OA\Parameter(
     *     in="query",
     *     name="page",
     *     description="Page from which to start listing catalogs.",
     *     required=true,
     *     @OA\Schema(type="integer", default="1")
     * )
     * @OA\Parameter(
     *     in="query",
     *     name="limit",
     *     description="How many items to return",
     *     required=true,
     *     @OA\Schema(type="integer", default="10")
     * )
     * @OA\Parameter(
     *     in="query",
     *     name="orderBy[updatedAt]",
     *     description="Order by last updated",
     *     required=false,
     *     @OA\Schema(
     *         type="string",
     *         enum={"ASC", "DESC"},
     *         default="DESC"
     *     )
     * )
     * @OA\Parameter(
     *     in="query",
     *     name="serializerGroups[]",
     *     description="Custom serializer groups array",
     *     required=false,
     *     style="form",
     *     @OA\Schema(
     *         type="array",
     *         @OA\Items(
     *             type="string",
     *             enum={
     *                 "catalog",
     *                 "catalog.articles",
     *                 "articles"
     *             }
     *         )
     *     )
     * )
     * @OA\Response(
     *     response=Response::HTTP_OK,
     *     description="Success",
     *     @OA\Schema(
     *         type="array",
     *         @OA\Items(
     *             ref=@Model(type=Catalog::class, groups={"catalog"})
     *         )
     *     )
     * )
     */
    public function index(Request $request): Response
    {
        return $this->handleCollectionRequest($this->catalogRepository, $request, [
            'serializerGroups' => [
                'catalog',
            ],
        ]);
    }

    /**
     * @Rest\Get("/api/v1/catalogs/{slug}", name="api_get_catalog")
     *
     * @OA\Parameter(
     *     in="query",
     *     name="serializerGroups[]",
     *     description="Custom serializer groups array",
     *     required=false,
     *     style="form",
     *     @OA\Schema(
     *         type="array",
     *         @OA\Items(
     *             type="string",
     *             enum={
     *                 "catalog",
     *                 "catalog.articles",
     *                 "articles"
     *             }
     *         )
     *     )
     * )
     * @OA\Response(
     *     response=Response::HTTP_OK,
     *     description="Success",
     *     @OA\Schema(
     *         type="object",
     *         ref=@Model(
     *             type=Catalog::class,
     *             groups={"catalog"}
     *         )
     *     )
     * )
     */
    public function detail(Request $request, Catalog $catalog): Response
    {
        $params = (new OptionsResolver())
            ->setDefaults([
                'serializerGroups' => [
                    'catalog',
                ],
            ])
            ->resolve($request->query->all());

        return $this->serializeResponse($catalog, $params['serializerGroups']);
    }

    /**
     * @Rest\Post("/api/v1/catalogs", name="api_post_catalog")
     * @ParamConverter("catalogCreateRequest", converter="fos_rest.request_body")
     *
     * @OA\RequestBody(
     *     @Model(type=CatalogCreateRequest::class),
     * ),
     * @OA\Parameter(
     *     in="query",
     *     name="serializerGroups[]",
     *     description="Custom serializer groups array",
     *     required=false,
     *     style="form",
     *     @OA\Schema(
     *         type="array",
     *         @OA\Items(
     *             type="string",
     *             enum={
     *                 "catalog",
     *                 "catalog.articles",
     *                 "articles"
     *             }
     *         )
     *     )
     * )
     * @OA\Response(
     *     response=Response::HTTP_OK,
     *     description="Success",
     *     @OA\Schema(
     *         type="object",
     *         ref=@Model(
     *             type=Catalog::class,
     *             groups={"catalog"}
     *         )
     *     )
     * )
     *
     * @throws \Exception
     */
    public function create(
        CatalogCreateRequest $catalogCreateRequest,
        Request $request,
        ConstraintViolationListInterface $validationErrors
    ): Response {
        if (\count($validationErrors) > 0) {
            $view = $this->constraintViolationView($validationErrors);

            return $this->handleView($view);
        }

        $params = (new OptionsResolver())
            ->setDefaults([
                'serializerGroups' => [
                    'catalog',
                ],
            ])
            ->resolve($request->query->all());

        $catalog = $this->catalogService->store($catalogCreateRequest);

        return $this->serializeResponse($catalog, $params['serializerGroups']);
    }

    /**
     * @Rest\Put("/api/v1/catalogs/{slug}", name="api_put_catalog")
     * @ParamConverter("catalogUpdateRequest", converter="fos_rest.request_body")
     *
     * @OA\RequestBody(
     *     @Model(type=CatalogUpdateRequest::class),
     * ),
     * @OA\Parameter(
     *     in="query",
     *     name="serializerGroups[]",
     *     description="Custom serializer groups array",
     *     required=false,
     *     style="form",
     *     @OA\Schema(
     *         type="array",
     *         @OA\Items(
     *             type="string",
     *             enum={
     *                 "catalog",
     *                 "catalog.articles",
     *                 "articles"
     *             }
     *         )
     *     )
     * )
     * @OA\Response(
     *     response=Response::HTTP_OK,
     *     description="Success",
     *     @OA\Schema(
     *         type="object",
     *         ref=@Model(
     *             type=Catalog::class,
     *             groups={"catalog"}
     *         )
     *     )
     * )
     *
     * @throws \Exception
     */
    public function update(
        CatalogUpdateRequest $catalogUpdateRequest,
        Catalog $catalog,
        Request $request,
        ConstraintViolationListInterface $validationErrors
    ): Response {
        if (\count($validationErrors) > 0) {
            $view = $this->constraintViolationView($validationErrors);

            return $this->handleView($view);
        }

        $params = (new OptionsResolver())
            ->setDefaults([
                'serializerGroups' => [
                    'catalog',
                ],
            ])
            ->resolve($request->query->all());

        $catalog = $this->catalogService->update($catalogUpdateRequest, $catalog);

        return $this->serializeResponse($catalog, $params['serializerGroups']);
    }

    /**
     * @Rest\Delete("/api/v1/catalogs/{slug}", name="api_delete_catalog")
     *
     * @OA\Parameter(
     *     in="query",
     *     name="serializerGroups[]",
     *     description="Custom serializer groups array",
     *     required=false,
     *     style="form",
     *     @OA\Schema(
     *         type="array",
     *         @OA\Items(
     *             type="string",
     *             enum={
     *                 "catalog",
     *                 "catalog.articles",
     *                 "articles"
     *             }
     *         )
     *     )
     * )
     * @OA\Response(
     *     response=Response::HTTP_OK,
     *     description="Success"
     * )
     *
     * @throws \Exception
     */
    public function delete(Catalog $catalog): JsonResponse
    {
        $status = $this->catalogService->delete($catalog);

        return new JsonResponse(['success' => $status]);
    }
}
