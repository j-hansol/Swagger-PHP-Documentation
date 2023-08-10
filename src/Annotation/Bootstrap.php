<?php
namespace JHansol\PhpSwaggerDocumentation\Annotation;

/**
 * @OA\Info (
 *     title="Open API Documentation",
 *     version="1.0"
 * )
 */
class Bootstrap {
    public function init() : void {
        echo '초기화 및 응용프로그램 실행';
    }
}