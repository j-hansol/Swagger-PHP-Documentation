자세한 해설서
=============

```x-taggroup```
----------------
OpenApi에는 태그를 사용하여 엔드포인트를 그룹화하는 개념이 있다. 또한 일부 도구( 예: redoclyx-tagGroups )는 공급자 확장을 통해 추가 그룹화를 지원한다.
```php
/** 
 * @OA\OpenApi(
 *   x={
 *       "tagGroups"=
 *           {{"name"="User Management", "tags"={"Users", "API keys", "Admin"}}
 *       }
 *   }
 * )
 */ 
```

```@OA\Response```에 예시 추가
-----------------------------
```php
/*
 * @OA\Response(
 *     response=200,
 *     description="OK",
 *     @OA\JsonContent(
 *         oneOf={
 *             @OA\Schema(ref="#/components/schemas/Result"),
 *             @OA\Schema(type="boolean")
 *         },
 *         @OA\Examples(example="result", value={"success": true}, summary="An result object."),
 *         @OA\Examples(example="bool", value=false, summary="A boolean value."),
 *     )
 * )
 */
```

외부 문서
---------
OpenApi는 외부 문서에 대한 단일 참조를 허용한다. 이 항목은 최상위(```@OA\Info```) 부분에 들어간다.
```php
/**
 * @OA\OpenApi(
 *   @OA\ExternalDocumentation(
 *     description="More documentation here...",
 *     url="https://example.com/externaldoc1/"
 *   )
 * )
 */
```

> ### 팁
> ```@OA\OpenApi```구성되지 않은 경우 swagger-php자동으로 생성한다.
> ````php
> /**
>  * @OA\ExternalDocumentation(
>  *   description="More documentation here...",
>  *   url="https://example.com/externaldoc1/"
>  * )
>  */
> ```

공용체 유형이 있는 속성
---------------------
때로는 속성이나 목록에도 다양한 유형의 데이터가 포함될 수 있다. 이는 ```onOf```를 사용하여 가능하다.
```php
/**
 * @OA\Schema(
 *      schema="StringList",
 *      @OA\Property(property="value", type="array", @OA\Items(anyOf={@OA\Schema(type="string")}))
 * )
 * @OA\Schema(
 *      schema="String",
 *      @OA\Property(property="value", type="string")
 * )
 * @OA\Schema(
 *      schema="Object",
 *      @OA\Property(property="value", type="object")
 * )
 * @OA\Schema(
 *     schema="mixedList",
 *     @OA\Property(property="fields", type="array", @OA\Items(oneOf={
 *         @OA\Schema(ref="#/components/schemas/StringList"),
 *         @OA\Schema(ref="#/components/schemas/String"),
 *         @OA\Schema(ref="#/components/schemas/Object")
 *     }))
 * )
 */
 ```

 위 내용은 아래와 같이 yml 형식으로 생성된다.
 ```yml
 openapi: 3.0.0
components:
  schemas:
    StringList:
      properties:
        value:
          type: array
          items:
            anyOf:
              -
                type: string
      type: object
    String:
      properties:
        value:
          type: string
      type: object
    Object:
      properties:
        value:
          type: object
      type: object
    mixedList:
      properties:
        fields:
          type: array
          items:
            oneOf:
              -
                $ref: '#/components/schemas/StringList'
              -
                $ref: '#/components/schemas/String'
              -
                $ref: '#/components/schemas/Object'
      type: object
```

보안 체계 참조
-------------
API에는 0개 이상의 보안 체계가 있을 수 있다. 이는 최상위 수준에서 정의되며 단순한 것부터 복잡한 것까지 다양하다.
```php
/**
 * @OA\SecurityScheme(
 *     type="apiKey",
 *     name="api_key",
 *     in="header",
 *     securityScheme="api_key"
 * )
 * 
 * @OA\SecurityScheme(
 *   type="oauth2",
 *   securityScheme="petstore_auth",
 *   @OA\Flow(
 *      authorizationUrl="http://petstore.swagger.io/oauth/dialog",
 *      flow="implicit",
 *      scopes={
 *         "read:pets": "read your pets",
 *         "write:pets": "modify pets in your account"
 *      }
 *   )
 * )
 */
```

엔드포인트를 보안으로 선언하고 클라이언트를 인증하는 데 사용할 수 있는 보안 체계를 정의하려면 작업에 추가해야 한다. 아래와 같이 정의한다.
```php
/**
 * @OA\Get(
 *      path="/api/secure/",
 *      summary="Requires authentication"
 *    ),
 *    security={ {"api_key": {}} }
 * )
 */
```

> 엔드포인트는 여러 보안 체계를 지원할 수 있으며 사용자 지정 옵션도 있다.
> ```php
> /**
>  * @OA\Get(
>  *      path="/api/secure/",
>  *      summary="Requires authentication"
>  *    ),
>  *    security={
>  *      { "api_key": {} },
>  *      { "petstore_auth": {"write:pets", "read:pets"} }
>  *    }
>  * )
>  */```

헤더가 포함된 파일 업로드
-----------------------
```php
/**
 * @OA\Post(
 *   path="/v1/media/upload",
 *   summary="Upload document",
 *   description="",
 *   tags={"Media"},
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\MediaType(
 *       mediaType="application/octet-stream",
 *       @OA\Schema(
 *         required={"content"},
 *         @OA\Property(
 *           description="Binary content of file",
 *           property="content",
 *           type="string",
 *           format="binary"
 *         )
 *       )
 *     )
 *   ),
 *   @OA\Response(
 *     response=200, description="Success",
 *     @OA\Schema(type="string")
 *   ),
 *   @OA\Response(
 *     response=400, description="Bad Request"
 *   )
 * )
 */
```

XML 루트 이름 설정
-----------------
```@OA\Xml```을 이용하여 XML의 루터 엘리먼트 이름을 지정할 수 있다. 
```php
/**
 * @OA\Schema(
 *     schema="Error",
 *     @OA\Property(property="message"),
 *     @OA\Xml(name="details")
 * )
 */

/**
 * @OA\Post(
 *     path="/foobar",
 *     @OA\Response(
 *         response=400,
 *         description="Request error",
 *         @OA\XmlContent(ref="#/components/schemas/Error",
 *           @OA\Xml(name="error")
 *        )
 *     )
 * )
 */
```

multipart/form-data 업로드
--------------------------
일반적으로 POST는 ```@OA\RequestBody```에 ```multipart/form-data``` 유형을 사용한다.
```php
/**
 * @OA\Post(
 *   path="/v1/user/update",
 *   summary="Form post",
 *   @OA\RequestBody(
 *     @OA\MediaType(
 *       mediaType="multipart/form-data",
 *       @OA\Schema(
 *         @OA\Property(property="name"),
 *         @OA\Property(
 *           description="file to upload",
 *           property="avatar",
 *           type="string",
 *           format="binary",
 *         ),
 *       )
 *     )
 *   ),
 *   @OA\Response(response=200, description="Success")
 * )
 */
```

모든 엔드포인트에 대한 기본 보안 체계
----------------------------------
각 엔드포인트에 지원하는 보안 체계를 선언할 수 있다. 그러나 그 전체 API에 대해 전역적으로 보안 체계를 구성하는 방법도 있다.
```@OA\OpenApi```부분에 기본 보안체계를 지정한다.

```php
/**
 * @OA\OpenApi(
 *   security={{"bearerAuth": {}}}
 * )
 *
 * @OA\SecurityScheme(
 *   securityScheme="bearerAuth",
 *   type="http",
 *   scheme="bearer"
 * )
 */
```

중첩된 객체
----------
property의 유형이 객체인 경우와 같이 복잡하고 중첩된 데이터 구조는 ```@OA\Property``` 주석 내부에 주석을 중첩하여 정의할 수 있다.
```php
/**
 *  @OA\Schema(
 *    schema="Profile",
 *    type="object",
 *     
 *    @OA\Property(
 *      property="Status",
 *      type="string",
 *      example="0"
 *    ),
 *
 *    @OA\Property(
 *      property="Group",
 *      type="object",
 *
 *      @OA\Property(
 *        property="ID",
 *        description="ID de grupo",
 *        type="number",
 *        example=-1
 *      ),
 *
 *      @OA\Property(
 *        property="Name",
 *        description="Nombre de grupo",
 *        type="string",
 *        example="Superadmin"
 *      )
 *    )
 *  )
 */
```

