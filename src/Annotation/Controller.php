<?php

namespace JHansol\PhpSwaggerDocumentation\Annotation;

/**
 * @OA\Tag (
 *     name="controller",
 *     description="기본 컨트롤러"
 * )
 */
class Controller {
    /**
     * @return string
     * @OA\Get (
     *     path="/base",
     *     @OA\Response (
     *          response=200,
     *          description="요청성공"
     *     )
     * )
     */
    public function base() : string {

    }
}