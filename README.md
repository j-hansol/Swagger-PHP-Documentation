  PHP Swagger 정리
================

* [시작하며](#시작하며)
* [소개](#소개)
  * [Swagger PHP란?](#swagger-php란)
  * [설치](#설치)
  * [OpenAPI 문서 생성](#openapi-문서-생성)
    * [./bin/openapi](#binopenapi0)
    * [출력형식](#출력-형식)
    * [PHP 코드 작성](#php-코드-작성)
* [코드에 주석달기](#코드에-주석달기)
  * [속성](#속성)
    * [중첩](#중첩)
  * [어노테이션](#어노테이션)
  * [Doctrine](#doctrine)
    * [큰 따음표 표시](#큰-따음표-표시)
    * [배열과 객체](#배열과-객체)
    * [상수](#상수)
  * [필수 요소](#필수-요소)
    * [어노테이션](#어노테이션-1)
    * [선택적 요소](#선택적-요소)
* [일반적인 기술](#일반적인-기술)
  * [어노테이션 배치](#어노테이션-배치)
  * [상황 인식](#상황-인식)
  * [참조 사용](#참조-사용)
  * [쿼리스트링에서 배열 파라메터](#쿼리스트링에서-배열-파라메터)
  * [공급자 확장](#공급자-확장)
  * [열거형](#열거형)
    * [값에 따른 열거형](#값에-따른-열거형)
      * [기본적인 열거형 예](#기본적인-열거형-예)
      * [Backend 열거형](#backend-열거형)
  * [열거형 스키마](#열거형-스키마)
    * [지원되는 열거형](#지원되는-열거형)
    * [이름 사용 열거형](#이름-사용-열거형)
    * [값 사용 열거형](#값-사용-열거형)
* [자세한 설명서](cookbook.md)

시작하며
=======
이 글은 Swagger API(Open API 기반)를 PHP에 적용하기 위한 프로젝트인 [Swagger PHP](https://zircote.github.io/swagger-php/) 자료를 나름대로 정리하기 위한 것이다. 현재 내가 진행하고 있는 프로젝트가 주로 PHP를 이용하여 진행하다 보니 PHP가 주가 되어 이번 저장소의 제목을 ```PHP Swagger```라고 표현하였다. 정식 명칭은 ```Swagger PHP```이다. 이 저장소에 영문으로 되어 있는 자료를 한글화(?)하고, 나름대로 정리하려고 한다.

소개
====

## Swagger PHP란?
```Swagger PHP```는 PHP 소스 파일에서 API 메타데이터를 추출하는 라이브러리이다. 이를 위해서 응용프로그램의 관련 PHP 코드에 주석(Annotation) 또는 속성(Attribute)을 이용하여 API의 상세 내용을 추가하고 ```Swagger PHP```를 이용하여 전용 프로그램이 판독 가능한 API 문서로 변환하도록 한다. 이렇게 소스코드에 API 내용을 문서화함으로 보다 쉽게 모든 정보를 최신정보르 쉽게 반영할 수 있게된다.
단, 속성을 사용하기 위해서는 ```PHP 8.x``` 이상이어야 한다.

## 설치
```Swagger PHP```를 설치하는 방법으로 ```Composer```를 이용하여 설치하는 것을 권장한다.

```bash
composer require zircote/swagger-php
```

전역으로 설치하고자 한다면 아래와 같이 입력하여 설치한다.
```
composer global require zircote/swagger-php
```

그리고 Laravel을 이용하는 경우 ```DarkaOnLine / L5-Swagger``` 패키지를 설치하여 이용하면 편리하다.
```
composer require "darkaonline/l5-swagger"
```

## OpenAPI 문서 생성
문서를 생성하는 부분은 두 가지가 있다. 하나는 패키지를 설치할 때 같이 설치된 ```./bin/openapi```를 이용하는 것, 그리고 두 번째는 문서화 코드를 직접 작성하여 실행하는 방법이 있다. 개인적으로는 기존 제공되는 것을 활용하는 것을 추천한다.

### ```./bin/openapi```
```
./vendor/bin/openapi app -o openapi.yaml
```

> ### 출력 형식
> 출력 형식은 기본적으로 ```YAML```이다. 출력형식은 ```--output``` 또는 ```-o```를 통해 파일명과 함께 확장명을 지정하여 결정할 수 있다.
> ```---format```은 출력파일 이름과 상관없이 출력 형식을 강제할 수 있다.

그 외 사용가능한 옵션을 보려면 ```--help``` 또는 ```-h```를 입력하여 확인할 수 있다.
```bash
./vendor/bin/openapi -h

Usage: openapi [--option value] [/path/to/project ...]

Options:
  --config (-c)     Generator config
                    ex: -c operationId.hash=false
  --legacy (-l)     Use legacy TokenAnalyser; default is the new ReflectionAnalyser
  --output (-o)     Path to store the generated documentation.
                    ex: --output openapi.yaml
  --exclude (-e)    Exclude path(s).
                    ex: --exclude vendor,library/Zend
  --pattern (-n)    Pattern of files to scan.
                    ex: --pattern "*.php" or --pattern "/\.(phps|php)$/"
  --bootstrap (-b)  Bootstrap a php file for defining constants, etc.
                    ex: --bootstrap config/constants.php
  --processor (-p)  Register an additional processor.
  --format (-f)     Force yaml or json.
  --debug (-d)      Show additional error information.
  --version         The OpenAPI version; defaults to 3.0.0.
  --help (-h)       Display this help message.
```

### PHP 코드 작성
문서를 생성하는 코드를 직접 작성하여 사용할 수 도 있다. 아래의 코드는 가장 간단하게 별도의 옵션 없이 YAML 형식으로 생성하여 출력하는 예이다. 이를 파일로 저장하는 기능을 추가할 수도 있을 것이다.
```php
<?php
require("vendor/autoload.php");

$openapi = \OpenApi\Generator::scan(['/path/to/project']);

header('Content-Type: application/x-yaml');
echo $openapi->toYaml();
```

코드에 주석달기
=============

메타데이터릴 기록하는 방법은 속성(Attribute)와 어노테이션(Annotation) 두 가지 방법을 잉용하여 가능하다.

속성
----
> ### 네임스페이스
> 네임스페이스 별칭을 이용하여 입력하면 보다 단순하고, 가독성을 향상시킬 수 있다.
> 모든 속성은 ```OpenApi\Attributes```에 있다.


### 중첩
오노테이션과 마찬가지로 속성 역시 중첩이 가능하다. 모호성이 없는 경우 중첩을 하지 안고 사용할 수 있다. ```swagger-php```는 부모/자식 관계애 대한 정의된 구축에 따라 속성을 변합한다. 단 중첩은 ```PHP 8.2``` 부터 가능하다.
```php
#[OA\Get(
    path: '/api/users',
    responses: [
        new OA\Response(response: 200, description: 'AOK'),
        new OA\Response(response: 401, description: 'Not allowed'),
    ]
)]
public function users() { /* ... */ }
```

중첩을 하지 안는 경우
```php
#[OA\Get(path: '/api/users')]
#[OA\Response(response: 200, description: 'AOK')]
#[OA\Response(response: 401, description: 'Not allowed')]
public function users() { /* ... */ }
```

어노테이션
---------
> ### 네임스페이스
> 네임스페이스 별칭을 이용하여 입력하면 보다 단순하고, 가독성을 향상시킬 수 있다.
> 모든 어노테이션은 ```OpenApi\Annotations```에 있다.

어노테이션은 PHP 주석이므로 ```use OpenApi\Annotations as OA```라는 문장은 추가할 필요는 없다. 그러나 Doctrline은 해당 어노테이션이 유효한지 아닌지 엄격히 구분한다.
```swagger-php```는 자동으로 ```@OA``` 라는 별칭을 자동으로 모든 어노테이션에 등록한다. 그래서 모든 어노테이션은 ```@OA``` 없이 이용가능하다.

Doctrine
--------
어노테이션은 Doctrine 스타일의 주석을 포함하는 PHP 주석(DockBlocks)이다.

> ### 정보
> Doctrine과 관련된 모든 문서는 주석에만 적용된다.

```php
<?php

use OpenApi\Annotations as OA;

/**
 * @OA\Info(title="My First API", version="0.1")
 */
class OpenApi {}

class MyController {

    /**
     * @OA\Get(
     *     path="/api/resource.json",
     *     @OA\Response(response="200", description="An example resource")
     * )
     */
    public function resource() {
        // ...
    }
}
```

### 큰 따음표 표시

> ### 큰따은표 표시
> 출력 내용에 큰 따음표를 표시할 때 ```\``` 대신 연속으로 큰따음표를 입력하여 표시할 수 있다.
> 대신 
> ```php
> @OA\Schema(
>    title="Request",
>    schema="Request",
>    example={
>       "configuration":"{""formConfig"":123}"
>    }
> )
> ```

### 배열과 객체

Doctrine의 주석은 배열을 지원한다. 그러나 속성에서 사용하는 ```[```과 ```]``` 대신 ```{```과 ```}```를 사용한다.

Doctrine는 객체를 지원한다. ```{```과 ```}```, 그리고 큰따음표로 묶인 속성 이름들이 필요하다.

> ### 이렇게 사용하는 것은 권장하지 않음
> ```php
> /**
>  * @OA\Info(
>  *   title="My first API",
>  *   version="1.0.0",
>  *   contact={
>  *     "email": "support@example.com"
>  *   }
>  * )
>  */
> ```

위와 같이 쓰도 동작하지만 아래와 같이 속성과 동일한 ```contact```라는 주석이 있다.
> ### 이렇게 사용하는 것을 권장
> ```php
> /**
>  * @OA\Info(
>  *   title="My first API",
>  *   version="1.0.0",
>  *   @OA\Contact(
>  *     email="support@example.com"
>  *   )
>  * )
>  */
> ```

이렇게 하여 어노테이션의 유효성을 검사하여 속성 철자가 틀리거나 필수 속성이 빠진 경우 경고가 출력된다.
예를 들어 ```emial="support@example.com"``` 이라고 쓰면 ```swagger-php```는 ```Unexpected field "emial" for @OA\Contact(), expecting "name", "email", ...```라는 메시지가 출력된다.

그리고 동일한 유형의 어노테이션을 배치하면 생성된 문서에는 배열로 표시됩니다. 개체의 경우 이름이 같은 필드 ```response``` => ```@OA\Response```, ```response``` => ```@OA\Response``` 로 정의된다.
```php
/**
 * @OA\Get(
 *   path="/products",
 *   summary="list products",
 *   @OA\Response(
 *     response=200,
 *     description="A list with products"
 *   ),
 *   @OA\Response(
 *     response="default",
 *     description="an ""unexpected"" error"
 *   )
 * )
 */
```

결과
```yaml
openapi: 3.0.0
paths:
  /products:
    get:
      summary: "list products"
      responses:
        "200":
          description: "A list with products"
        default:
          description: 'an "unexpected" error'
```

### 상수

Doctrine은 주석 내에 상수를 사용할 수 있다.
```php
define("API_HOST", ($env === "production") ? "example.com" : "localhost");
```

> ### 팁
> CLI를 사용하면 옵션을 사용하여 상수와 함께 php 파일을 포함해야 할 수도 있다. 이 때 ```--bootstrap```을 이용한다.
> ```php
> openapi --bootstrap constants.php
> ```

필수 요소
--------
OpenAPI 사양은 유효한 문서에 대한 최소 정보 집합을 정의한다.
대부분의 ```name```과 ```version```을 갖는 일반적인 정보와 하나 이상의 엔드포인트가 있어야 한다.

엔드포인트에는 경로와 하나 이상의 응답이 있어야 한다.

### 어노테이션
```php
<?php

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="My First API",
 *     version="0.1"
 * )
 */
class OpenApi {}

class MyController {

    /**
     * @OA\Get(
     *     path="/api/data.json",
     *     @OA\Response(
     *         response="200",
     *         description="The data"
     *     )
     * )
     */
    public function getResource() {
        // ...
    }
}
```

속성
```php
#[OA\Info(title: "My First API", version: "0.1")]
class OpenApi {}

class MyController {

    #[OA\Get(path: '/api/data.json')]
    #[OA\Response(response: '200', description: 'The data')]
    public function getResource() {
        // ...
    }
}
```

위 두 내용은 모두 동일하다. 위 문서는 아래와 같이 OpenAPI 문서로 작성된다.
```yaml
openapi: 3.0.0
info:
  title: 'My First API'
  version: '0.1'
paths:
  /api/data.json:
    get:
      operationId: 236f26ae21b015a60adbce41f8f316e3
      responses:
        '200':
          description: 'The data'
```

> ### 주의
> 속성 및 어노테이션은 PHP 문서에 정의된 대로 코드의 선언 어디에나 추가할 수 있다. 이는 PHP Reflection API를 지원하는 범위로 제한된다. 즉 ```class```, ```enum``` 등의 파일만 국한된다는 것이다.

선택적 요소
---------
위의 속성이나 어노테이션 내용과 생성된 문서를 보면 누락된 정보가 ```swagger-php```가 자동으로 추가되어 있음을 알 수 있다.

```@OA\OpenApi```, ```@OA\Components```, ```@OA\PathItem``` 등은 자동으로 추가된다.

일반적인 기술
===========

어노테이션 배치
--------------
모든 주석을 하나의 큰 블록 안에 배치하지 말고 관련 소스 코드에 정확하게 일치된 코드부분에 배치하는 것을 권장한다.

```swagger-php```는 프로젝트를 스켄하고 모든 위치의 데이터를 하나의 ```@OA\OpenApi``` 어노테이션으로 작성한다.

> ### 주의
> ```swagger-php``` v4의 모든 어노테이션 또는 속성은 반드시 ```class```, ```method```, ```parameter```, ```enum``` 등에 배치해야 한다.

상황 인식
---------
```swagger-PHP```는 주석의 컨텍스트를 살펴보고 ```property name```, ```data type``` ```description``` 등을 (doctype 및 기본 유형 힌트) 및 기타 몇 가지 사항으로 주석을 보강한다. 이는 많은 경우 모든 세부 사항을 명시적으로 문서화할 필요가 없음을 의미이기도 하다.

```php
<?php

/**
 * @OA\Schema()
 */
class Product {

    /**
     * The product name,
     * @var string
     * @OA\Property()
     */
    public $name;
}
```

위 내용은 아래와 같이 생성된다.
```yml
openapi: 3.0.0
components:
  schemas:
    Product:
      properties:
        name:
          description: "The product name"
          type: string
      type: object
```

결국 아래와 같이 문서화한 것과 동일하다.
```php
    /**
     * The product name
     * @var string
     *
     * @OA\Property(
     *   property="name",
     *   type="string",
     *   description="The product name"
     * )
     */
    public $name;
    ```

응답 미디어 유형(Response Media Type)
------------------------------------
응답을 위한 미디어 타입은 ```@OA\MediaType```으로 컨텐츠를 설명한다.
```php
/**
 * @OA\Response(
 *     response=200,
 *     description="successful operation",
 *     @OA\MediaType(
 *         mediaType="application/json",
 *         @OA\Schema(ref="#/components/schemas/User"),
 *     )
 * ),
 */
```

그러나 대부분의 API 요청 및 응답은 JSON이므로 ```@OA\JsonContent```를 사용하면 다음과 같이 작성하여 이를 단순화할 수 있다.
```php
/**
 * @OA\Response(
 *     response=200,
 *     description="successful operation",
 *     @OA\JsonContent(ref="#/components/schemas/User"),
 * )
 */
```

참조 사용
---------
클라이언트에서의 요청 및 응답 데이터가 겹치는 경우가 많다. 이런 경우 겹치는 데이터를 컴포넌트로 생성해두고 ```$ref```를 이용하여 재사용 하도록 할 수 있다.
```php
/**
 * @OA\Schema(
 *   schema="product_id",
 *   type="integer",
 *   format="int64",
 *   description="The unique identifier of a product in our catalog"
 * )
 */
```

위 내용은 아래와 같이 ```yml```코드로 생성된다.
```yml
openapi: 3.0.0
components:
  schemas:
    product_id:
      description: "The unique identifier of a product in our catalog"
      type: integer
      format: int64
```

이렇게 생성된 컴포넌트는 아래와 같이 참조할 수 있다.
```php
/**
 * @OA\Property(ref="#/components/schemas/product_id")
 */
public $id;
```

쿼리스트링에서 배열 파라메터
--------------------------
스타일 값에 따라 ```@OA\Parameter(in="query", name="param", ...)```는 ```path?param=123&param=abc``` 형태로 URL을 생성할 수 있다. 하지만 PHP에서는 정상적으로 동작하지 않는다. 해결책은 ```param```이라는 이름을 ```param[]```로 변경하여 ```path?param[]=123&param[]=abc```로 생성하도록 하는 것이다.

공급자 확장
----------
사양에서는 "x-"로 시작하는 사용자 지정 속성을 허용합니다. 따라서 모든 swagger-php 주석에는 배열(맵)을 허용하고 "x-" 속성으로 펼쳐지는 x 속성이 있다.

```php
/**
 * @OA\Info(
 *   title="Example",
 *   version="1.0.0",
 *   x={
 *     "some-name": "a-value",
 *     "another": 2,
 *     "complex-type": {
 *       "supported":{
 *         {"version": "1.0", "level": "baseapi"},
 *         {"version": "2.1", "level": "fullapi"},
 *       }
 *     }
 *   }
 * )
 */
```

위 내용은 아래와 같이 생성된다.
```yml
openapi: 3.0.0
info:
  title: Example
  version: 1
  x-some-name: a-value
  x-another: 2
  x-complex-type:
    supported:
      - version: "1.0"
        level: baseapi
      - version: "2.1"
        level: fullapi
```

열거형
-----
PHP 8.1부터 두 가지 사례의 열거형을 지원한다.

### 값에 따른 열거형
열거형 문자열, 정수 또는 기타 기본 유형과 마찬가지로 열거형 목록의 값으로 사용될 수 있다

#### 기본적인 열거형 예
```php
use OpenApi\Attributes as OAT;

enum Suit
{
    case Hearts;
    case Diamonds;
    case Clubs;
    case Spades;
}

class Model
{
    #[OAT\Property(enum: [Suit::Hearts, Suit::Diamonds])]
    protected array $someSuits;
}
```

위의 코드는 아래와 같이 생성된다.
```yml
openapi: 3.0.0
components:
  schemas:
    Model:
      properties:
        someSuits:
          type: array
          enum:
            - Hearts
            - Diamonds
      type: object
```

#### Backend 열거형
Backend 열거형은 이름 대신 값이 이용된다.

열거형 스키마
------------
열거형을 사용하는 간단한 방법은 열거형에 Schema 속성을 이용하여 컴포넌트를 생성하는 것이다. 이를 통해 사양의 다른 스키마처럼 참조할 수 있다.
```php
use OpenApi\Attributes as OAT;

#[OAT\Schema()]
enum Colour
{
    case GREEN;
    case BLUE;
    case RED;
}

#[OAT\Schema()]
class Product
{
    #[OAT\Property()]
    public Colour $colour;
}
```

위 내용은 아래와 같이 생성된다.
```yml
openapi: 3.0.0
components:
  schemas:
    Colour:
      type: string
      enum:
        - GREEN
        - BLUE
        - RED
    Product:
      properties:
        colour:
          $ref: '#/components/schemas/Colour'
      type: object
```

#### 지원되는 열거형
지원되는 열거형에는 이름 또는 Backend 열거형의 값이 사용되는지 여부를 결정하는 두 가지 규칙이 있다.

1. 스키마 유형이 지정되지 않으면 열거형 이름이 사용됩니다.
2. 스키마 유형이 제공되고 유형과 일치하는 경우 열거형 지원 사용됩니다.

#### 이름 사용 열거형
```php
#[OAT\Schema()]
enum Colour: int
{
    case GREEN = 1;
    case BLUE = 2;
    case RED = 3;
}

#[OAT\Schema()]
class Product
{
    #[OAT\Property()]
    public Colour $colour;
}
```

생성 결과
```yml
openapi: 3.0.0
components:
  schemas:
    Colour:
      type: string
      enum:
        - GREEN
        - BLUE
        - RED
    Product:
      properties:
        colour:
          $ref: '#/components/schemas/Colour'
      type: object
```

#### 값 사용 열거형
```php
use OpenApi\Attributes as OAT;

#[OAT\Schema(type: 'integer')]
enum Colour: int
{
    case GREEN = 1;
    case BLUE = 2;
    case RED = 3;
}

#[OAT\Schema()]
class Product
{
    #[OAT\Property()]
    public Colour $colour;
}
```

위 내용은 아래와 같이 생성된다.
```yml
openapi: 3.0.0
components:
  schemas:
    Colour:
      type: integer
      enum:
        - 1
        - 2
        - 3
    Product:
      properties:
        colour:
          $ref: '#/components/schemas/Colour'
      type: object
```

