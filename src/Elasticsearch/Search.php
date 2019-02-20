<?php

namespace App\Elasticsearch;

use Elastica\Aggregation\Filters;
use Elastica\Aggregation\Terms;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\MultiMatch;
use Elastica\Query\Terms as TermsFiter;
use Elastica\Client;

class Search
{
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

    public function __construct(string $terms = '', int $limit = 30)
    {
        $this->terms = $terms;
        $this->limit = $limit;
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
    public function setTerms($terms)
    {
        $this->terms = (string) $terms;

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
     *
     * @param Client $client
     */
    public function runSearch(Client $client)
    {
        $match = new MultiMatch();
        $match->setQuery($this->terms);
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
        $elasticaQuery->setSize($this->limit);
        $elasticaQuery->addAggregation($termAgg);
        if (false !== $tags) {
            $filterAgg = new Filters('filter_by_tag');
            $termTags = new TermsFiter('tags', [$tags]);
            $filterAgg->addFilter($termTags);
            $elasticaQuery->setPostFilter($termTags);
            $elasticaQuery->addAggregation($filterAgg);
        }
        $foundPosts = $client->getIndex('community')->search($elasticaQuery);
        $results = [];
        $source = [];
        foreach ($foundPosts as $post) {
            $source[] = $post->getSource();
        }
        $results['source'] = $source;
    }
}
