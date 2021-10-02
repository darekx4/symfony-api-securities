<?php
namespace App\Controller;

use App\Constants\Generic;
use App\Mappers\ExpressionsMapper;
use App\Models\Analytics;
use App\Service\AssetValuation;
use App\Service\RequestProcessor;
use App\Validator\SecurityJsonDSL;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ApiSecuritiesController controller.
 * @Route("/api", name="api_")
 */
class ApiSecuritiesController extends AbstractController
{

    private AssetValuation $assetValuation;
    private ExpressionsMapper $expressionsMapper;
    private RequestProcessor $processor;

    public function __construct(
        RequestProcessor $processor,
        ExpressionsMapper $expressionsMapper,
        AssetValuation $assetValuation
    )
    {
        $this->processor = $processor;
        $this->expressionsMapper = $expressionsMapper;
        $this->assetValuation = $assetValuation;
    }

    /**
     * @Route("/securities/analytics", name="securities_analytics", methods={"POST"})
     */
    public function analytics(Request $request): JsonResponse
    {
        try {
            // Validate request
            SecurityJsonDSL::validate($request);
            // Deserialize request
            $analyticsRequest = $this->processor->deserialize($request, Analytics::class);
        } catch (Exception $e) {
            return new JsonResponse(['error' => true, 'status' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        // Map request expression
        $expression = $this->expressionsMapper->map($analyticsRequest);

        // Det security code
        $asset = $analyticsRequest->getSecurity();

        try {
            $assetValuation = $this->assetValuation->calculate($expression, $asset);
        } catch (Exception $e) {
            return new JsonResponse(['error' => true, 'status' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['error' => false, 'status' => Generic::ASSET_HAS_BEEN_VALUED, 'valuation_result' => $assetValuation,], Response::HTTP_OK);
    }
}
