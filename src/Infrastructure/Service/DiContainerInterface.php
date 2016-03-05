<?php


namespace Extasy\API\Infrastructure\Service;


interface DiContainerInterface
{

    /**
     * @return CORE_DependencyInjectorContainerInterface
     */
    public static function getInstance();

    /**
     * @param string $aliasId
     * @return mixed
     */
    public function get($aliasId);

    /**
     * Checks if a class alias exists in the container
     *
     * @param string $aliasId
     * @return bool
     */
    public function exists($aliasId);

    /**
     * @param string $aliasId
     * @param mixed $value
     * @return $this
     * @throws CORE_DependencyInjectorContainerException
     */
    public function add($aliasId, $value = null);

    /**
     * @param string $aliasId
     * @return mixed
     */
    public function build($aliasId);

    /**
     * Returns new instance definition alias
     *
     * @param string $definitionAlias
     * @return string
     */
    public function getDefinition($definitionAlias);

    /**
     * Returns shared instance definition alias
     *
     * @param string $definitionAlias
     * @return string
     */
    public function getSharedDefinition($definitionAlias);

}