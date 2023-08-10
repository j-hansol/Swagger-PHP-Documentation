<?php

namespace JHansol\PhpSwaggerDocumentation\Attribute;

use OpenApi\Attributes\OpenApi as OA;

#[OA\Tag (
    name: "controller",
    description: "기본 컨트롤러"
)]
class Controller {
    #[OA\Get (
        path: "/base",
        responses: [
            new OA\Response(response: 200, description: "요청성공")
        ]
    )]
    public function base() : string {

    }
}