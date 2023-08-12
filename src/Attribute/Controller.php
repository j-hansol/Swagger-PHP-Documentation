<?php

namespace JHansol\PhpSwaggerDocumentation\Attribute;

use OpenApi\Attributes\Get;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Tag;

#[Tag (
    name: "controller",
    description: "기본 컨트롤러"
)]
class Controller {
    #[Get (
        path: "/base",
        responses: [
            new Response(response: 200, description: "요청성공")
        ]
    )]
    public function base() : string {

    }
}