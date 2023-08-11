<?php
namespace JHansol\PhpSwaggerDocumentation\Attribute;

use OpenApi\Attributes as OA;

#[OA\Info (
    version: "1.0",
    title: "Open API Documentation"
)]
class Bootstrap {
    public function init() : void {
        echo '초기화 및 응용프로그램 실행';
    }
}