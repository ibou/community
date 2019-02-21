<?php

namespace App\Elasticsearch;

use Elastica\Query;
use Elastica\Client;
use Elastica\Query\BoolQuery;
use Elastica\Query\MultiMatch;
use Elastica\Aggregation\Terms;
use Elastica\Aggregation\Filters;
use Elastica\Query\Terms as TermsFiter;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use App\Entity\Post;

class Search
{
    /**
     * Undocumented variable.
     *
     * @var Client
     */
    private $client;
    /**
     * terms of search.
     *
     * @var string|null
     */
    private $terms;
    /**
     * Undocumented variable.
     *
     * @var int
     */
    private $limit;

    /**
     * Undocumented variable.
     *
     * @var [type]
     */
    private $tags;
    /**
     * Undocumented variable.
     *
     * @var int
     */
    private $page = 1;

    /**
     * Undocumented function.
     *
     * @param Client       $client
     * @param string       $terms
     * @param string|false $tags
     */
    public function __construct(Client $client, ?string $terms, $tags = false)
    {
        $this->client = $client;
        $this->terms = $terms;
        $this->tags = $tags;
    }

    /**
     * Get undocumented variable.
     *
     * @return string|null
     */
    public function getTerms()
    {
        return $this->terms;
    }

    /**
     * Set undocumented variable.
     *
     * @param string|null $terms term of search
     *
     * @return self
     */
    public function setTerms(sting $terms)
    {
        $this->terms = $terms;

        return $this;
    }

    /**
     * Get undocumented variable.
     *
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Set undocumented variable.
     *
     * @param int $limit Undocumented variable
     *
     * @return self
     */
    public function setLimit(int $limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Undocumented function.
     */
    public function runSearch()
    {
        $query = $this->terms;
        $limit = $this->limit;
        $tags = $this->tags;

        $match = new MultiMatch();
        $match->setQuery($query);
        $match->setFields(['title^4', 'tags', 'content', 'author', 'comments']);

        $bool = new BoolQuery();
        $bool->addMust($match);
        $termAgg = new Terms('by_tags');
        $termAgg->setSize(50);
        $termAgg->setField('tags');
        $termAgg->setOrders([
            ['_count' => 'asc'], // 1. red,   2. green, 3. blue
            //['_key' => 'asc'],   // 1. green, 2. red,   3. blue
        ]);

        $elasticaQuery = new Query($bool);
        $elasticaQuery->setFrom(0);
        $elasticaQuery->setSize($limit);
        $elasticaQuery->addAggregation($termAgg);

        if (false !== $tags) {
            $filterAgg = new Filters('filter_by_tag');
            $termTags = new TermsFiter('tags', [$tags]);

            $filterAgg->addFilter($termTags);
            $elasticaQuery->setPostFilter($termTags);
            $elasticaQuery->addAggregation($filterAgg);
        }
        $foundPosts = $this->client->getIndex('community')->search($elasticaQuery);
        $results = [];
        $source = [];
        foreach ($foundPosts as $post) {
            $source[] = $post->getSource();
        }
        $results['source'] = $source;

        return $results;
    }

    /**
     * Get undocumented variable.
     *
     * @return [type]
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set undocumented variable.
     *
     * @param [type] $tags Undocumented variable
     *
     * @return self
     */
    public function setTags(string $tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get undocumented variable.
     *
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set undocumented variable.
     *
     * @param int $page undocumented variable
     *
     * @return self
     */
    public function setPage(int $page)
    {
        $this->page = $page;

        return $this;
    }

    public function getPaginatedData(array $data): Pagerfanta
    {
        $adapter = new ArrayAdapter($data);
        $paginator = new Pagerfanta($adapter);
        $paginator->setMaxPerPage(Post::NUM_ITEMS);
        $paginator->setCurrentPage($this->page);

        return $paginator;
    }
}
