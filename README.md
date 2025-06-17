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

## Large WHERE condition
The complex nature of `LIKE` means performance will suffer when it comes to long condition. Because it has to scan multiple table and many columns with `LIKE` condition.
```sql
WHERE (
  JobCategories.name LIKE '%キャビンアテンダント%' OR
  JobTypes.name LIKE '%キャビンアテンダント%' OR
  Jobs.name LIKE '%キャビンアテンダント%' OR
  Jobs.description LIKE '%キャビンアテンダント%' OR
  ...
)

```

## Sub query with UNION
In order to reduce the performance impact, the query should split into simple sub query with `UNION`. Because of this simpler logic and condition MySQL optimizer will have and easier time to do its thing.
```sql
SELECT Jobs.id FROM Jobs WHERE Jobs.name LIKE '%キャビンアテンダント%'
UNION
SELECT Jobs.id FROM JobTypes WHERE JobTypes.name LIKE '%キャビンアテンダント%'
UNION
SELECT Jobs.id FROM JobCategories WHERE JobCategories.name LIKE '%キャビンアテンダント%'

```

## Add INDEX
Works well when one or multiple column are commonly used to match `WHERE`. Like a bookmark, MySQL can jump straight to the known rows that associated with the matching `WHERE` condition rather than scanning all rows for potential match.
```sql
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_jobs_type_status ON jobs(job_type_id, status);
ALTER TABLE jobs ADD FULLTEXT(name);
```

## Use Temporary table
For repeatedly used table, using `WITH` (MySQL v8.0 above) is desirable so that the repeated logic is only needed to be done once. Resulting in a much resource heavy query. Manual build of the table should be used if MySQL version is before version 8.0.
```sql
WITH FilteredJobs AS (
  SELECT id, name FROM Jobs
  WHERE publish_status = 1 AND deleted IS NULL
)
SELECT fj.id, jt.name
FROM FilteredJobs fj
JOIN job_types jt ON jt.id = fj.id;
```
