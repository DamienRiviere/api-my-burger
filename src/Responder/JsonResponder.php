<?php

namespace App\Responder;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class JsonResponder
 * @package App\Responder
 */
final class JsonResponder
{

    /**
     * @param ?array $data
     * @param int $statusCode
     * @param array $headers
     * @return Response
     */
    public function __invoke(?array $data, int $statusCode, array $headers = []): Response
    {
        if (is_array($data)) {
            $data = json_encode($data);
        }

        return new Response(
            $data,
            $statusCode,
            array_merge(
                [
                    'Content-Type' => 'application/json'
                ],
                $headers
            )
        );
    }
}
