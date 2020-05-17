# Makes

## Make Resource

### Attributes

Parameter | Type | Description
--------- | ---- | ----
**id** | *integer* | Resource ID
**value** | *string* | Value of make
**slug** | *string* | URL slug of make

## Retrieve a Make

Retrieve an auto resource.

```shell
curl "https://api.revpourri.com/makes/1" \
  -X GET \
  -H "access-token: 7403eb1eb0f17904177db40aaa418d45fa6a5bb7" \
  -d '{"title": "New Video"}'
```

> Example JSON response

```json
{
    "id": 1,
    "value": "Honda",
    "slug": "honda"
}
```

### HTTP Request

`GET /makes/:id`

## List Makes

Retrieve a list of makes using parameters.

```shell
curl "https://api.revpourri.com/makes" \
  -X GET \
  -H "access-token: 7403eb1eb0f17904177db40aaa418d45fa6a5bb7" \
  -d '{"title": "New Video"}'
```

> Example JSON response

```json
{
    "links": {
        "current": "/makes?page=1",
        "first": "/makes?page=1",
        "last": "/makes?page=1",
        "prev": "/makes?page=1",
        "next": "/makes?page=1"
    },
    "count": 3,
    "data": [
        {
            "id": 1,
            "value": "Honda",
            "slug": "honda"
        },
      //...
    ]
}
```

### HTTP Request

`GET /makes`