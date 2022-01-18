# ElasticsearchBundle

Elasticsearch integrates the official PHP-Elasticsearch Client (https://github.com/elastic/elasticsearch-php) into Symfony.
The main idea is based on the [Specification pattern](https://en.wikipedia.org/wiki/Specification_pattern), while trying not to overengineer things.
At the moment the bundle only covers the parts (queries, filters, etc.), we needed. But it can be easily extended.

## Installation

```console
composer require faibl/elasticsearch-bundle
```

## Configuration

Installing the bundle with Symfony flex will generate a faibl_elasticsearch.yaml inside your config-folder.
The part under 'elasticsearch' is your Elasticsearch-Config. The other parts are needed be the bundle to retrieve the entities from the db, serialize, deserialize from entities to documents and back, and log exceptions thrown from PHP-Elasticsearch.

Example:
```yaml
faibl_elasticsearch:
    entity:
        class: 'App\Entity\YourEntity'
        serializer: 'App\Serializer\Serializer\YourEntitySerializer'
    logger: 'monolog.logger'
    elasticsearch:
        index_name: "%elasticsearch_index_name%"
        settings:
            number_of_shards: 1
            number_of_replicas: 0
        mapping:
            yourentity:
                _source:
                    enabled: true
                properties:
                    status:
                        type: keyword
                    approved:
                        type: boolean
                    category:
                        type: keyword
                    user:
                        type: object
                        properties:
                            id:
                                type: keyword
                            name:
                                type: text

```

## Usage

### Index management commands
```console
fbl:elasticsearch:setup --create
fbl:elasticsearch:setup --recreate
fbl:elasticsearch:setup --delete
fbl:elasticsearch:setup --update-settings
fbl:elasticsearch:setup --update-mapping
```

### Index documents commands
```console
fbl:elasticsearch:index --all
fbl:elasticsearch:index --id=299
```

### Simple example of how to create a query and get result
```php
$query = (new Query())
             ->setQuery((new BoolQuery())
                 ->addFilters([
                     new TermFilter('field', 'value'),
                     new TermsFilter('field', ['value1', 'value2']),
                     new OnlineAtFilter($this->onlineAt), //custom filter containing domain logic
                 ])
                 ->addMust(
                     new MatchQuery('content', 'string to search for')
                 )
             )
             ->addSorts([
                 new OnlineAtSort($this->onlineAt), //custom sort containing domain logic
                 new FieldSort('_id'),
             ])
             ->setFrom(0)
             ->setSize(20)
             ->getQuery();

$result = $this->searchRepository->findForQuery($query, SearchService::HYDRATE_RESULT);
```

The bundle offers global queries, filters and sort-functions.
If you need more complex filters or queries, you should created your own by combining global/other filters and implementing the FilterInterface.
Creating custom queries and filters makes the reusable and easily testable.

### Custom filter

Here is an example of a custom filter that combines global filter provided by gthe bundle and some business logic.

```php
namespace App\Search\Filter;

use App\Entity\OrderItemInterface;
use Faibl\ElasticsearchBundle\Search\Filter\BoolFilter;
use Faibl\ElasticsearchBundle\Search\Filter\FilterInterface;
use Faibl\ElasticsearchBundle\Search\Filter\NestedFilter;

class OnlineAtFilter implements FilterInterface
{
    private $dateTime;

    public function __construct(?\DateTimeInterface $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public function hasFilter(): bool
    {
        return $this->dateTime instanceof \DateTimeInterface;
    }

    public function getFilter(): array
    {
        return (new NestedFilter('orderItems'))
            ->setFilter((new BoolFilter())
                ->addFilters([
                    new OrderItemStatusFilter(OrderItemInterface::STATUS_ORDERED),
                    new OrderItemOnlineAtFilter($this->dateTime),
                ])
            )->getFilter();
    }
}
```

### Todo
* Write tests..
* Extend filters/queries
