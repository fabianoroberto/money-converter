<?php

declare(strict_types=1);

namespace App\Controller\Rest;

use App\Controller\Rest\Traits\ErrorResponseTrait;
use App\Controller\Rest\Traits\HateoasResponseTrait;
use App\Dto\Request\ArticleAddToCatalogRequest;
use App\Dto\Request\ArticleCreateRequest;
use App\Dto\Request\ArticleRemoveFromCatalogRequest;
use App\Dto\Request\ArticleUpdateRequest;
use App\Entity\Article;
use App\Repository\ArticleRepositoryInterface;
use App\Service\ArticleService;
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
 * @OA\Tag(name="Articles")
 */
class ApiArticleController extends AbstractFOSRestController
{
    use ErrorResponseTrait;
    use HateoasResponseTrait;

    public function __construct(
        private ArticleRepositoryInterface $articleRepository,
        private ArticleService $articleService,
    ) {
    }

    /**
     * @Rest\Get("/api/v1/articles", name="api_get_articles")
     *
     * @OA\Parameter(
     *     in="query",
     *     name="page",
     *     description="Page from which to start listing articles.",
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
     *                 "articles",
     *                 "article",
     *                 "article.catalogs",
     *                 "catalogs"
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
     *             ref=@Model(type=Article::class, groups={"articles"})
     *         )
     *     )
     * )
     */
    public function index(Request $request): Response
    {
        return $this->handleCollectionRequest($this->articleRepository, $request, [
            'serializerGroups' => [
                'article',
            ],
        ]);
    }

    /**
     * @Rest\Get("/api/v1/articles/{code}", name="api_get_article")
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
     *                 "article",
     *                 "article.catalogs",
     *                 "catalogs"
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
     *             type=Article::class,
     *             groups={"article"}
     *         )
     *     )
     * )
     */
    public function detail(Request $request, Article $article): Response
    {
        $params = (new OptionsResolver())
            ->setDefaults([
                'serializerGroups' => [
                    'article',
                ],
            ])
            ->resolve($request->query->all());

        return $this->serializeResponse($article, $params['serializerGroups']);
    }

    /**
     * @Rest\Post("/api/v1/articles", name="api_post_article")
     * @ParamConverter("createRequest", converter="fos_rest.request_body")
     *
     * @OA\RequestBody(
     *     @Model(type=ArticleCreateRequest::class),
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
     *                 "article",
     *                 "article.catalogs",
     *                 "catalogs"
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
     *             type=Article::class,
     *             groups={"article"}
     *         )
     *     )
     * )
     *
     * @throws \Exception
     */
    public function create(
        ArticleCreateRequest $createRequest,
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
                    'article',
                ],
            ])
            ->resolve($request->query->all());

        $article = $this->articleService->store($createRequest);

        return $this->serializeResponse($article, $params['serializerGroups']);
    }

    /**
     * @Rest\Put("/api/v1/articles/{code}", name="api_put_article")
     * @ParamConverter("updateRequest", converter="fos_rest.request_body")
     *
     * @OA\RequestBody(
     *     @Model(type=ArticleUpdateRequest::class),
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
     *                 "article",
     *                 "article.catalogs",
     *                 "catalogs"
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
     *             type=Article::class,
     *             groups={"article"}
     *         )
     *     )
     * )
     *
     * @throws \Exception
     */
    public function update(
        ArticleUpdateRequest $updateRequest,
        Article $article,
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
                    'article',
                ],
            ])
            ->resolve($request->query->all());

        $article = $this->articleService->update($updateRequest, $article);

        return $this->serializeResponse($article, $params['serializerGroups']);
    }

    /**
     * @Rest\Put("/api/v1/articles/{code}/catalogs", name="api_put_article_catalog")
     * @ParamConverter("addToCatalogRequest", converter="fos_rest.request_body")
     *
     * @OA\RequestBody(
     *     @Model(type=ArticleAddToCatalogRequest::class),
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
     *                 "article",
     *                 "article.catalogs",
     *                 "catalogs"
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
     *             type=Article::class,
     *             groups={"article"}
     *         )
     *     )
     * )
     *
     * @throws \Exception
     */
    public function addToCatalogs(
        ArticleAddToCatalogRequest $addToCatalogRequest,
        Article $article,
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
                    'article',
                ],
            ])
            ->resolve($request->query->all());

        $article = $this->articleService->addToCatalogs($addToCatalogRequest, $article);

        return $this->serializeResponse($article, $params['serializerGroups']);
    }

    /**
     * @Rest\Post("/api/v1/articles/{code}/image", name="api_post_article_image")
     *
     * @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *         mediaType="multipart/form-data",
     *         @OA\Schema(
     *             @OA\Property(
     *                 description="File to upload",
     *                 property="photo",
     *                 type="string",
     *                 format="file",
     *             ),
     *             required={"file"}
     *         )
     *     )
     * ),
     * @OA\Parameter(
     *     description="Code of article to update",
     *     in="path",
     *     name="code",
     *     required=true,
     *     @OA\Schema(
     *         type="string",
     *     ),
     * )
     *
     * @OA\Response(
     *     response=Response::HTTP_OK,
     *     description="Success",
     *     @OA\Schema(
     *         type="object",
     *         ref=@Model(
     *             type=Article::class,
     *             groups={"article"}
     *         )
     *     )
     * )
     */
    public function saveImage(Request $request, Article $article): Response
    {
        $image = $request->files->get('photo');

        $params = (new OptionsResolver())
            ->setDefaults([
                'serializerGroups' => [
                    'article',
                ],
            ])
            ->resolve($request->query->all());

        $article = $this->articleService->saveImage($article, $image);

        return $this->serializeResponse($article, $params['serializerGroups']);
    }

    /**
     * @Rest\Delete("/api/v1/articles/{code}", name="api_delete_article")
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
     *                 "article",
     *                 "article.catalogs",
     *                 "catalogs"
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
    public function delete(Article $article): JsonResponse
    {
        $status = $this->articleService->delete($article);

        return new JsonResponse(['success' => $status]);
    }

    /**
     * @Rest\Delete("/api/v1/articles/{code}/catalogs", name="api_delete_article_catalog")
     * @ParamConverter("removeFromCatalogRequest", converter="fos_rest.request_body")
     *
     * @OA\RequestBody(
     *     @Model(type=ArticleRemoveFromCatalogRequest::class),
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
     *                 "article",
     *                 "article.catalogs",
     *                 "catalogs"
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
     *             type=Article::class,
     *             groups={"article"}
     *         )
     *     )
     * )
     *
     * @throws \Exception
     */
    public function removeFromCatalogs(
        ArticleRemoveFromCatalogRequest $removeFromCatalogRequest,
        Article $article,
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
                    'article',
                ],
            ])
            ->resolve($request->query->all());

        $article = $this->articleService->removeFromCatalogs($removeFromCatalogRequest, $article);

        return $this->serializeResponse($article, $params['serializerGroups']);
    }
}
