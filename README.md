
# Money converter

Sample project that expose Products/Catalogs Rest APIs and perform math operations on old English Currencies


## Requirements

"_Fino al 1970 nel Regno Unito il sistema monetario prevedeva pence, shilling e pound. Un pound valeva 20 shilling e uno shilling 12 pence (quindi un pound 240 pence)._"

Si chiede di realizzare un'API REST che permetta di:

1. Inserire, modificare e rimuovere articoli da un catalogo (un articolo ha un codice identificativo, un nome e un costo)
2. Ottenere la lista degli articoli del catalogo o di un singolo articolo (dato il suo codice identificativo)
3. Eseguire le principali operazioni aritmetiche:
   1. somma - esempio => 5p 17s 8d + 3p 4s 10d= 9p 2s 6d
   2. sottrazione - esempio => 5p 17s 8d - 3p 4s 10d= 2p 12s 10d
   3. moltiplicazione con un intero (no decimali) - esempio => 5p 17s 8d 2 = 11p 15 s 4d
   4. divisione resto (da indicare tra parentesi) con un intero (no decimali) - esempio => 18p 16s 1d/15 = 1p 5s Od (1s 1d)

Attenzione: gli endpoint devono poter ricevere e produrre i valori monetari come stringa, usando lo stesso formato degli esempi ovvero "Xp Ys Zd". Nel caso della divisione, se è presente un resto questo sarà indicato tra parentesi usando lo stesso formato "Xp Ys Zd" (vedi esempio 3.iv).


## Tech Stack

**Client:** Stimulus, Bootstrap

**Server:** Symfony 5.2


## Running Tests

To run tests, run the following command

```bash
  make phpunit
  make phpspec
```


## API Reference

#### Get all articles

```http
  GET /api/v1/articles
```

| Parameter | Type      | Description                                          |
| :-------- | :-------- | :--------------------------------------------------- |
| `page`    | `integer` | **Required**. Page from which to start listing items |
| `limit`   | `integer` | **Required**. How many items to return               |

#### Get article

```http
  GET /api/v1/articles/{code}
```

| Parameter | Type     | Description                         |
| :-------- | :------- | :---------------------------------- |
| `code`    | `string` | **Required**. Code of item to fetch |

#### Create article

```http
  POST /api/v1/articles
```

| Parameter     | Type     | Description                                                                    |
| :------------ | :------- | :----------------------------------------------------------------------------- |
| `name`        | `string` | **Required**. Name of article                                                  |
| `description` | `string` | Description of article                                                         |
| `price`       | `string` | **Required**. Price representation in Xp Ys Zd format                          |
| `photoUrl`    | `string` | Url of an article image (eg. https://picsum.photos/seed/picsum/200/300)        |
| `catalogs`    | `array`  | Array of objects with format `{"slug": "string"}` where "slug" is catalog slug |

#### Update article

```http
  PUT /api/v1/articles/{code}
```

| Parameter     | Type     | Description                                                             |
| :------------ | :------- | :---------------------------------------------------------------------- |
| `code`        | `string` | **Required**. Slug of item to update                                    |
| `name`        | `string` | **Required**. Name of article                                           |
| `description` | `string` | Description of article                                                  |
| `price`       | `string` | **Required**. Price representation in Xp Ys Zd format                   |
| `photoUrl`    | `string` | Url of an article image (eg. https://picsum.photos/seed/picsum/200/300) |

#### Remove article

```http
  DELETE /api/v1/articles/{code}
```

| Parameter     | Type     | Description                          |
| :------------ | :------- | :----------------------------------- |
| `code`        | `string` | **Required**. Slug of item to delete |

#### Add article to catalogs

```http
  PUT /api/v1/articles/{code}/catalogs
```

| Parameter     | Type     | Description                                                                    |
| :------------ | :------- | :----------------------------------------------------------------------------- |
| `code`        | `string` | **Required**. Slug of item to update                                           |
| `catalogs`    | `array`  | Array of objects with format `{"slug": "string"}` where "slug" is catalog slug |

#### Remove article from catalogs

```http
  DELETE /api/v1/articles/{code}/catalogs
```

| Parameter     | Type     | Description                                                                    |
| :------------ | :------- | :----------------------------------------------------------------------------- |
| `code`        | `string` | **Required**. Slug of item to update                                           |
| `catalogs`    | `array`  | Array of objects with format `{"slug": "string"}` where "slug" is catalog slug |

#### Set article image

```http
  POST /api/v1/articles/{code}/image
```

| Parameter     | Type     | Description                          |
| :------------ | :------- | :----------------------------------- |
| `code`        | `string` | **Required**. Slug of item to update |
| `photo`       | `File`   | **Required**. Image to upload        |

#### Get all catalogs

```http
  GET /api/v1/catalogs
```

| Parameter | Type      | Description                                          |
| :-------- | :-------- | :--------------------------------------------------- |
| `page`    | `integer` | **Required**. Page from which to start listing items |
| `limit`   | `integer` | **Required**. How many items to return               |

#### Get catalog

```http
  GET /api/v1/catalogs/{slug}
```

| Parameter | Type      | Description                         |
| :-------- | :-------- | :---------------------------------- |
| `slug`    | `string`  | **Required**. Slug of item to fetch |

#### Create catalog

```http
  POST /api/v1/catalogs
```

| Parameter     | Type     | Description                   |
| :------------ | :------- | :---------------------------- |
| `name`        | `string` | **Required**. Name of catalog |
| `description` | `string` | Description of catalog        |

#### Update catalog

```http
  PUT /api/v1/catalogs/{slug}
```

| Parameter     | Type     | Description                          |
| :------------ | :------- | :----------------------------------- |
| `slug`        | `string` | **Required**. Slug of item to update |
| `name`        | `string` | **Required**. Name of catalog        |
| `description` | `string` | Description of catalog               |

#### Delete catalog

```http
  DELETE /api/v1/catalogs/{slug}
```

| Parameter     | Type     | Description                          |
| :------------ | :------- | :----------------------------------- |
| `slug`        | `string` | **Required**. Slug of item to update |

#### Sum prices

```http
  POST /api/v1/math/sum
```

| Parameter | Type     | Description                        |
| :-------- | :------- | :--------------------------------- |
| `addend1` | `string` | **Required**. First operand of sum |
| `addend2` | `string` | **Required**. First operand of sum |

#### Subtract prices

```http
  POST /api/v1/math/sub
```

| Parameter    | Type     | Description                        |
| :----------- | :------- | :--------------------------------- |
| `minuend`    | `string` | **Required**. First operand of sub |
| `subtrahend` | `string` | **Required**. First operand of sub |

#### Multiply prices

```http
  POST /api/v1/math/mul
```

| Parameter | Type              | Description                        |
| :-------- | :---------------- | :--------------------------------- |
| `factor1` | `string`          | **Required**. First operand of mul |
| `factor2` | `string` or `int` | **Required**. First operand of mul |

#### Divide prices

```http
  POST /api/v1/math/div
```

| Parameter  | Type              | Description                        |
| :--------- | :---------------- | :--------------------------------- |
| `dividend` | `string`          | **Required**. First operand of div |
| `divisor`  | `string` or `int` | **Required**. First operand of div |

## Authors

👤 **Fabiano Roberto**

* Twitter: [@dr_thief](https://twitter.com/dr_thief)
* Github: [@fabianoroberto](https://github.com/fabianoroberto)

  