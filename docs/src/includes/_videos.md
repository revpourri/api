# Videos

## Video Resource

### Attributes

Parameter | Type | Description
--------- | ---- | ----
**id** | *integer* | Resource ID
**title** | *string* | Video title
**slug** | *string* | URL slug, generated from title
**youtube_id** | *string* | Youtube's video ID
**uploader_id** | *integer* | Uploader resource ID
**created_time** | *string* | Resource created time
**published_date** | *string* | Youtube's video published date
**type** | *string* | Type of video: `review`, `project`
**featured** | *boolean* | Is a featured video
**preview_video** | *string* | Filename of preview video

## Create Video Resource

```shell
curl "https://api.revpourri.com/videos" \
  -X POST \
  -H "access-token: 7403eb1eb0f17904177db40aaa418d45fa6a5bb7" \
  -d '{"title": "New Video"}'
```

```json
{
    "id": 1,
    "title": "S2000 Review",
    "slug": "/video/s2000-review",
    "created_time": "2020-05-17T03:11:29+00:00",
    "published_date": "2001-02-01",
    "youtube_id": "fYq5PXgSsbE",
    "type": "review",
    "featured": true,
    "preview_video": "s2000.mp4",
    "uploader": {
        "id": 1,
        "name": "Car Reviewer",
        "youtube_id": "1",
        "avatar": ""
    },
    "autos": [
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
    ]
}
```

Create new video

### HTTP Request

`POST /videos`

### Attributes

Parameter | Type | Description
--------- | ---- | ----
**title** | *string* | Video title
**youtube_id** | *string* | Youtube's video ID
**uploader_id** | *integer* | Uploader resource ID
**published_date** | *string* | Youtube's video published date
**type** | *string* | Type of video: `review`, `project`
**featured** | *boolean* | Is a featured video
**preview_video** | *string* | Filename of preview video

## Retrieve a Video

```shell
curl "https://api.revpourri.com/videos/1" \
  -X GET \
  -H "access-token: 7403eb1eb0f17904177db40aaa418d45fa6a5bb7"
```

> Example JSON response

```json
{
    "id": 1,
    "title": "S2000 Review",
    "slug": "/video/s2000-review",
    "created_time": "2020-05-17T03:11:29+00:00",
    "published_date": "2001-02-01",
    "youtube_id": "fYq5PXgSsbE",
    "type": "review",
    "featured": true,
    "preview_video": "s2000.mp4",
    "uploader": {
        "id": 1,
        "name": "Car Reviewer",
        "youtube_id": "1",
        "avatar": ""
    },
    "autos": [
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
    ]
}
```

Retrieve a video resource.

### HTTP Request

`GET /videos/:id`

## Update Video Resource

```shell
curl "https://api.revpourri.com/videos/1" \
  -X PUT \
  -H "access-token: 7403eb1eb0f17904177db40aaa418d45fa6a5bb7" \
  -d '{"title": "New S2000 Review"}'
```

> Example JSON response

```json
{
    "id": 1,
    "title": "New S2000 Review",
    "slug": "/video/s2000-review",
    "created_time": "2020-05-17T03:11:29+00:00",
    "published_date": "2001-02-01",
    "youtube_id": "fYq5PXgSsbE",
    "type": "review",
    "featured": true,
    "preview_video": "s2000.mp4",
    "uploader": {
        "id": 1,
        "name": "Car Reviewer",
        "youtube_id": "1",
        "avatar": ""
    },
    "autos": [
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
    ]
}
```

Update video resource.

### HTTP Request

`PUT /videos/1`

### Attributes

Parameter | Type | Description
--------- | ---- | ----
**title** | *string* | Video title
**slug** | *string* | URL slug of video
**youtube_id** | *string* | Youtube's video ID
**uploader_id** | *integer* | Uploader resource ID
**published_date** | *string* | Youtube's video published date
**type** | *string* | Type of video: `review`, `project`

## List Videos

```shell
curl "https://api.revpourri.com/videos" \
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
    "count": 10,
    "data": [
        {
            "id": 1,
            "title": "S2000 Review",
            "slug": "/video/s2000-review",
            "created_time": "2020-05-17T03:11:29+00:00",
            "published_date": "2001-02-01",
            "youtube_id": "fYq5PXgSsbE",
            "type": "review",
            "featured": true,
            "preview_video": "s2000.mp4",
            "uploader": {
                "id": 1,
                "name": "Car Reviewer",
                "youtube_id": "1",
                "avatar": ""
            },
            "autos": [
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
            ]
        },
        //...
    ]
}
```

Retrieve a list of videos using parameters.

### HTTP Request

`GET /videos`

## Delete Video

```shell
curl "https://api.revpourri.com/videos/1" \
  -X DELETE \
  -H "access-token: 7403eb1eb0f17904177db40aaa418d45fa6a5bb7"
```

> Example JSON response

```json
{
    "deleted": true
}
```

Delete video.

### HTTP Request

`DELETE /videos/1`