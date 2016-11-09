<?php
namespace Morilog\FlexibleRepository\Contracts;

interface CriteriaInterface
{
    public function apply($query, RepositoryInterface $repository);
}