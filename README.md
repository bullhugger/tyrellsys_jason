# Jason's Test Programs Card
Simple CodeIgniter4 web app that tackles Tyrell's question A and B.

## Requirements
- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Installation
Clone this repo and run: 
```
docker-compose build
docker-compose up -d
```

Check http://localhost:8080/Home once docker completed its process.

# Question B

## Excessive LEFT JOIN
There are 14 LEFT JOIN in the query that still returns all left table rows even if there is no matching on the right table.

## Use EXIST
One way to reduce the weight is using **EXISTS** to check for first match. It stops once a match is found, rather than continuing on to combine the rows.

So instead of:
```sql
LEFT JOIN jobs_personalities ON Jobs.id = jobs_personalities.job_id
```
Do
```sql
WHERE EXISTS (
    SELECT 1
    FROM jobs_personalities jp
    WHERE jp.job_id = Jobs.id
)
```
