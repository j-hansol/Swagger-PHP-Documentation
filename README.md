PHP Swagger 정리
================

목차
----

시작하며
-------
이 글은 Swagger API(Open API 기반)를 PHP에 적용하기 위한 프로젝트인 [Swagger PHP](https://zircote.github.io/swagger-php/) 자료를 나름대로 정리하기 위한 것이다. 현재 내가 진행하고 있는 프로젝트가 주로 PHP를 이용하여 진행하다 보니 PHP가 주가 되어 이번 저장소의 제목을 ```PHP Swagger```라고 표현하였다. 정식 명칭은 ```Swagger PHP```이다. 이 저장소에 영문으로 되어 있는 자료를 한글화(?)하고, 나름대로 정리하려고 한다.

소개
----

### Swagger PHP란?
```Swagger PHP```는 PHP 소스 파일에서 API 메타데이터를 추출하는 라이브러리이다. 이를 위해서 응용프로그램의 관련 PHP 코드에 주석(Annotation) 또는 속성(Attribute)을 이용하여 API의 상세 내용을 추가하고 ```Swagger PHP```를 이용하여 전용 프로그램이 판독 가능한 API 문서로 변환하도록 한다. 이렇게 소스코드에 API 내용을 문서화함으로 보다 쉽게 모든 정보를 최신정보르 쉽게 반영할 수 있게된다.
단, 속성을 사용하기 위해서는 ```PHP 8.x``` 이상이어야 한다.

### 설치
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

### OpenAPI 문서 생성
문서를 생성하는 부분은 두 가지가 있다. 하나는 패키지를 설치할 때 같이 설치된 ```./bin/openapi```를 이용하는 것, 그리고 두 번째는 문서화 코드를 직접 작성하여 실행하는 방법이 있다. 개인적으로는 기존 제공되는 것을 활용하는 것을 추천한다.

#### ```./bin/openapi```
```
./vendor/bin/openapi app -o openapi.yaml
```

> ### 출력 형식
> 출력 형식은 기본적으로 ```YAML```이다. 출력형식은 ```--output``` 또는 ```-o```를 통해 파일명과 함께 확장명을 지정하여 결정할 수 있다.
> ```---format```은 출력파일 이름과 상관없이 출력 형식을 강제할 수 있다.

