
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
  make phpspec
```


## API Reference

#### Get all articles

```http
  GET /api/v1/articles
```

#### Get article

```http
  GET /api/v1/articles/{id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. Id of item to fetch |

#### Create article

```http
  POST /api/v1/articles
```

TODO - to be continue


## Authors

👤 **Fabiano Roberto**

* Twitter: [@dr_thief](https://twitter.com/dr_thief)
* Github: [@fabianoroberto](https://github.com/fabianoroberto)

  