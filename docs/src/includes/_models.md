# Models

## Model Resource

### Attributes

Parameter | Type | Description
--------- | ---- | ----
**id** | *integer* | Resource ID
**value** | *string* | Value of model
**slug** | *string* | URL slug of model

## Retrieve a Model

Retrieve an model resource.

```shell
curl "https://api.revpourri.com/models/1" \
  -X GET \
  -H "access-token: 7403eb1eb0f17904177db40aaa418d45fa6a5bb7"
```

> Example JSON response

```json
{
    "id": 1,
    "value": "S2000",
    "slug": "s2000"
}
```

### HTTP Request

`GET /models/:id`

## List Models

Retrieve a list of makes using parameters.

```shell
curl "https://api.revpourri.com/models" \
  -X GET \
  -H "access-token: 7403eb1eb0f17904177db40aaa418d45fa6a5bb7"
```

> Example JSON response

```json
{
    "links": {
        "current": "/videos?page=1",
        "first": "/videos?page=1",
        "last": "/videos?page=1",
        "prev": "/videos?page=1",
        "next": "/videos?page=1"
    },
    "count": 4,
    "data": [
        {
            "id": 4,
            "value": "Civic",
            "slug": "civic"
        },
        //...
    ]
}
```

### HTTP Request

`GET /models`