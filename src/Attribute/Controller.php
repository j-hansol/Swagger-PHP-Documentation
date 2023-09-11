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
            new Response(response: 200, description: "요청성공"),
            new Response(response: 500, description: "서버오류")
        ]
    )]
    public function base() : string {}

    #[Get(path: "/base2")]
    #[Response(response: 200, description: '요청성공')]
    #[Response(response: 500, description: '서버오류')]
    public function base2() : string {}
}