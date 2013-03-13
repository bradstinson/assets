<?php namespace Assets;

interface AssetInterface{

    /**
     * Filters an asset before it is dumped.
     *
     * @param content $content content of file
     * @param FilterInterface $asset An asset
     */
    // public function filter($content, FilterInterface $filter);

    /**
     * Returns file contents of asset
     *
     * @return string
     */
    public function getContents();

    /**
     * Sets file contents of asset
     *
     * @param  $content  string
     * @return string
     */
    public function setContents($content);

    /**
     * Gets extension for file
     *
     * @return string
     */
    public function getExtension();
    
    /**
     * Retrieves timestamp for file
     *
     * @return string
     */
    public function getLastModified();    

    /**
     * Get the group for the asset.
     * 
     * @return string
     */
    public function getGroup();

    /**
     * Determines if an asset has valid extension
     * 
     * @return bool
     */
    public function isValid();   

    /**
     * Applies filters to an asset before it is dumped.
     *
     * @return  FilterInterface $asset An asset
     */
    public function compile();
}
