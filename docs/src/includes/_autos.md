# Autos

## Auto Resource

### Attributes

Parameter | Type | Description
--------- | ---- | ----
**id** | *integer* | Resource ID
**year** | *integer* | Year of auto
**make** | *object* | Make object of auto
**model** | *object* | Model object of auto

## Create Video Resource

Create new auto

```shell
curl "https://api.revpourri.com/autos" \
  -X POST \
  -H "access-token: 7403eb1eb0f17904177db40aaa418d45fa6a5bb7" \
  -d '{"year": "2001"}'
```

> Example JSON response

```json
{
    "id": 1,
    "year": 2001,
    "make": {
        "id": 1,
        "value": "Honda",
        "slug": "honda"
    },
    "model": {
        "id": 1,
        "value": "S2000",
        "slug": "s2000"
    }
}
```

### HTTP Request

`POST /autos`

### Attributes

Parameter | Type | Description
--------- | ---- | ----
**year** | *integer* | Year of auto
**make_id** | *integer* | Make ID
**model_id** | *integer* | Model ID

## Retrieve an Auto

Retrieve an auto resource.

```shell
curl "https://api.revpourri.com/autos/1" \
  -X GET \
  -H "access-token: 7403eb1eb0f17904177db40aaa418d45fa6a5bb7" \
  -d '{"title": "New Video"}'
```

> Example JSON response

```json
{
    "id": 1,
    "year": 2001,
    "make": {
        "id": 1,
        "value": "Honda",
        "slug": "honda"
    },
    "model": {
        "id": 1,
        "value": "S2000",
        "slug": "s2000"
    }
}
```

### HTTP Request

`GET /autos/:id`

## Update Auto Resource

Update video resource.

```shell
curl "https://api.revpourri.com/videos/1" \
  -X PUT \
  -H "access-token: 7403eb1eb0f17904177db40aaa418d45fa6a5bb7" \
  -d '{"year": "2020"}'
```

> Example JSON response

```json
{
    "id": 1,
    "year": 2020,
    "make": {
        "id": 1,
        "value": "Honda",
        "slug": "honda"
    },
    "model": {
        "id": 1,
        "value": "S2000",
        "slug": "s2000"
    }
}
```

### HTTP Request

`PUT /autos/1`

### Attributes

Parameter | Type | Description
--------- | ---- | ----
**year** | *integer* | Year of auto
**make_id** | *integer* | Make ID
**model_id** | *integer* | Model ID

## List Autos

Retrieve a list of autos using parameters.

```shell
curl "https://api.revpourri.com/autos" \
  -X GET \
  -H "access-token: 7403eb1eb0f17904177db40aaa418d45fa6a5bb7" \
  -d '{"title": "New Video"}'
```

> Example JSON response

```json
{
    "links": {
        "current": "/videos?page=2",
        "first": "/videos?page=1",
        "last": "/videos?page=2",
        "prev": "/videos?page=1",
        "next": "/videos?page=2"
    },
    "count": 4,
    "data": [
        {
            "id": 1,
            "year": 2001,
            "make": {
                "id": 1,
                "value": "Honda",
                "slug": "honda"
            },
            "model": {
                "id": 1,
                "value": "S2000",
                "slug": "s2000"
            }
        },
      //...
    ]
}
```

### HTTP Request

`GET /autos`

## Delete Video

Delete auto.

```shell
curl "https://api.revpourri.com/autos/1" \
  -X DELETE \
  -H "access-token: 7403eb1eb0f17904177db40aaa418d45fa6a5bb7" \
  -d '{"title": "New Video"}'
```

> Example JSON response

```json
{
    "deleted": true
}
```

### HTTP Request

`DELETE /autos/1`