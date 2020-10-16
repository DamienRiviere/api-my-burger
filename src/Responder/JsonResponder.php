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
     * @phpstan-ignore-next-line
     * @param $data
     * @param int $statusCode
     * @param array $headers
     * @return Response
     * @phpstan-ignore-next-line
     */
    public function __invoke($data, int $statusCode, array $headers = []): Response
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
